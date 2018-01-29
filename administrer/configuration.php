<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: configuration.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_content", true, true);
$id = vn($_GET['id']);

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_CONFIGURATION_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_configuration($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_configuration($id, $frm);
		break;

	case "suppr" :
		supprime_configuration($id);
		affiche_liste_configuration();
		break;

	case "generate" :
		// Pour migrer le contenu d'une table peel_sites :
		// appeler /administrer/configuration.php?mode=generate&migrate=1&full=1
		if(!empty($_GET['migrate'])) {
			$configuration_fields = get_table_field_names('peel_sites', null, true);
			if(!empty($configuration_fields)) {
				// La table peel_sites pour d'anciens sites existe => on charge son contenu pour ensuite mettre les informations dans peel_configuration
				$query = query("SELECT ps.*, pd.devise, pd.conversion, pd.symbole, pd.symbole_place, pd.code
					FROM peel_sites ps
					LEFT JOIN peel_devises pd ON pd.id = ps.devise_defaut AND " . get_filter_site_cond('devises') . "
					WHERE ps.id = '1'");
				$configuration_fields = fetch_assoc($query);
			}
		} else {
			$configuration_fields = $GLOBALS['site_parameters'];
		}
		if(!empty($configuration_fields)) {
			foreach($configuration_fields as $this_key => $this_value) {
				if(!in_array($this_key, array('id', 'devise', 'conversion', 'symbole', 'symbole_place', 'code'))) {
					// On crée la configuration
					$frm['etat'] = 1;
					$frm['technical_code'] = $this_key;
					$frm['string'] = $this_value;
					$frm['origin'] = 'auto';
					$frm['lang'] = '';
					$frm['site_id'] = 1;
					if(is_array($this_value)) {
						$frm['type'] = 'array';
						$frm['string'] = get_string_from_array($this_value);
					}elseif(is_bool($this_value)) {
						$frm['type'] = 'boolean';
						if($this_value){
							$frm['string'] = 'true';
						} else {
							$frm['string'] = 'false';
						}
					}elseif(is_int($this_value)) {
						$frm['type'] = 'integer';
					}elseif(is_float($this_value)) {
						$frm['type'] = 'float';
					}else {
						$frm['type'] = 'string';
					}
					$qid = query("SELECT *
						FROM peel_configuration
						WHERE technical_code = '" . real_escape_string($this_key) . "' AND " . get_filter_site_cond('configuration', null, true) . "");
					$select = fetch_assoc($qid);
					if (!$select) {
						set_configuration_variable($frm);
						echo 'INSERTED '.$frm['technical_code'].'<br />';			
					} elseif(!empty($_GET['full'])) {
						unset($frm['type']);
						update_configuration_variable($select['id'], $frm);
						echo 'UPDATED '.$frm['technical_code'].'<br />';			
					}
				}
			}
		}
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => 'Generate OK'))->fetch();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			set_configuration_variable($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CONFIGURATION_MSG_CREATED'], vb($_POST['technical_code']))))->fetch();
			affiche_liste_configuration();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_configuration($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			update_configuration_variable($_POST['id'], $_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CONFIGURATION_MSG_UPDATED'], vn($_POST['id']))))->fetch();
			affiche_liste_configuration();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_configuration($id, $frm);
		}
		break;

	default :
		affiche_liste_configuration();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter une zone HTML
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_configuration(&$frm)
{
	/* Default value*/
	if(empty($frm)) {
		$frm['etat'] = 1;
		$frm['technical_code'] = "";
		$frm['origin'] = "";
		$frm['string'] = "";
		$frm['type'] = "";
		$frm['lang'] = "";
		$frm['site_id'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_ADD'];

	affiche_formulaire_configuration($frm);
}

/**
 * Affiche le formulaire de modification pour la zone HTML sélectionnée
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_configuration($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_configuration
			WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('configuration', null, true) . "");
		if ($frm = fetch_assoc($qid)) {
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_CONFIGURATION_ERR_NOT_FOUND']))->fetch();
			return false;
		}
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_configuration($frm);
}

/**
 * affiche_formulaire_configuration()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_configuration(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_configuration.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl_langs = array();
	$tpl_langs[] = array('lng' => '',
		'issel' => vb($frm['lang']) == '',
		'name' => $GLOBALS['STR_ALL']
		);
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'issel' => vb($frm['lang']) == $lng,
			'name' => $GLOBALS['lang_names'][$lng]
			);
	}
	if(StringMb::strpos(vb($frm['string']), "\r") !== false || StringMb::strpos(vb($frm['string']), "\n") !== false || StringMb::strpos(vb($frm['technical_code']), "tag_") !== false || StringMb::strpos(vb($frm['technical_code']), "_tag") !== false) {
		$tpl->assign('string_as_textarea', true);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('etat', $frm["etat"]);
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
	$tpl->assign('origin', vb($frm['origin']));
	$tpl->assign('type', vb($frm['type']));
	$tpl->assign('technical_code', vb($frm['technical_code']));
	$tpl->assign('string', vb($frm['string']));
	$tpl->assign('explain', vb($frm['explain']));
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_CONFIGURATION_FORM_TITLE', $GLOBALS['STR_ADMIN_CONFIGURATION_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_TYPE', $GLOBALS['STR_TYPE']);
	$tpl->assign('STR_ADMIN_CONFIGURATION_TEXT', $GLOBALS['STR_ADMIN_CONFIGURATION_TEXT']);
	$tpl->assign('STR_ADMIN_CONFIGURATION_ORIGIN', $GLOBALS['STR_ADMIN_CONFIGURATION_ORIGIN']);
	$tpl->assign('STR_VALIDATE', $GLOBALS['STR_VALIDATE']);
	$tpl->assign('STR_COMMENTS', $GLOBALS['STR_COMMENTS']);
	echo $tpl->fetch();
}

/**
 * Supprime la variable de configuration spécifiée par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_configuration($id)
{
	query("DELETE FROM peel_configuration 
		WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('configuration', null, true) . "");
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_CONFIGURATION_MSG_DELETED']))->fetch();
}

/**
 * Affiche la liste des variables de configuration pour tous les sites
 *
 * @return
 */
function affiche_liste_configuration()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_configuration.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$sql = "SELECT *
		FROM peel_configuration
		WHERE " . get_filter_site_cond('configuration', null, true) . "";
	$Links = new Multipage($sql, 'configuration', 500);
	$HeaderTitlesArray = array($GLOBALS['STR_ADMIN_ACTION'], 'lang' => $GLOBALS['STR_ADMIN_LANGUAGE'], 'type' => $GLOBALS['STR_TYPE'], 'technical_code' => $GLOBALS['STR_ADMIN_TECHNICAL_CODE'], 'string' => $GLOBALS['STR_VALUE'], 'last_update' => $GLOBALS['STR_DATE'], 'origin' => $GLOBALS['STR_ADMIN_CONFIGURATION_ORIGIN'], 'etat' => $GLOBALS['STR_STATUS'], 'site_id' => $GLOBALS['STR_ADMIN_SITE_ID']);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = "site_id, technical_code, lang";
	$Links->SortDefault = "ASC";
	$results_array = $Links->Query();
	if (!empty($results_array)) {
		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $ligne) {
			$string = $ligne['string'];
			$comment = '';
			$tpl->assign('HeaderRow', $Links->getHeaderRow());
			if(StringMb::substr($ligne['technical_code'], 0, 4) != 'STR_' && $string != vb($GLOBALS['site_parameters'][$ligne['technical_code']]) && get_string_from_array(get_array_from_string($string)) != get_string_from_array(vb($GLOBALS['site_parameters'][$ligne['technical_code']])) && (empty($_POST['technical_code']) || $_POST['technical_code'] != $ligne['technical_code']) && (!is_bool($GLOBALS['site_parameters'][$ligne['technical_code']]) || !in_array($string, array('false', 'true')))){
				$comment .= '<br /><span class="text-danger">(Current : ' . str_replace(array("Array,", "),", "(,", ",)"), array("Array ", ")", "(", ")"), str_replace(array("\r\n", "\n"), ',', StringMb::textEncode(print_r(vb($GLOBALS['site_parameters'][$ligne['technical_code']]), true)))).')</span>';
			}
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'technical_code' => StringMb::str_shorten_words($ligne['technical_code'], 50),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'lang' => $ligne['lang'],
				'date' => get_formatted_date($ligne['last_update'], 'short', 'long'),
				'origin' => $ligne['origin'],
				'site_id' => $ligne['site_id'],
				'type' => $ligne['type'],
				'string' => StringMb::str_shorten_words($string, 50),
				'comment' => StringMb::str_shorten_words($comment, 50),
				'etat_onclick' => 'change_status("configuration", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif')
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('Multipage', $Links->GetMultipage());
	if (check_if_module_active('welcome_ad')) {
		$tpl->assign('is_welcome_ad_module_active', true);
		unset($_SESSION['session_info_inter_set']);
	} else {
		$tpl->assign('is_welcome_ad_module_active', false);
	}
	$tpl->assign('STR_ADMIN_CONFIGURATION_TITLE', $GLOBALS['STR_ADMIN_CONFIGURATION_TITLE']);
	$tpl->assign('STR_ADMIN_CONFIGURATION_CREATE', $GLOBALS['STR_ADMIN_CONFIGURATION_CREATE']);
	$tpl->assign('STR_ADMIN_CONFIGURATION_EXPLAIN', $GLOBALS['STR_ADMIN_CONFIGURATION_EXPLAIN']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_NOTA_BENE', $GLOBALS['STR_NOTA_BENE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_DATE', $GLOBALS['STR_DATE']);
	$tpl->assign('STR_VALIDATE', $GLOBALS['STR_VALIDATE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_VALUE', $GLOBALS['STR_VALUE']);
	$tpl->assign('STR_ADMIN_CONFIGURATION_ORIGIN', $GLOBALS['STR_ADMIN_CONFIGURATION_ORIGIN']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_CONFIGURATION_UPDATE', $GLOBALS['STR_ADMIN_CONFIGURATION_UPDATE']);
	$tpl->assign('STR_TYPE', $GLOBALS['STR_TYPE']);
	echo $tpl->fetch();
}


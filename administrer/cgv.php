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
// $Id: cgv.php 55332 2017-12-01 10:44:06Z sdelaporte $

define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv('admin_content,admin_communication');

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_CGV_TITLE'];

$id = intval(vn($_REQUEST['id']));

if (!isset($form_error_object)) {
	$form_error_object = new FormError();
}
$output ='';
switch (vb($_REQUEST['mode'])) {
	case "maj" :
		if (!empty($_POST)) {
			$frm = $_POST;
			$form_error_object->valide_form($frm,
				array('titre_' . $_SESSION['session_langue'] => sprintf($GLOBALS['STR_ADMIN_CGV_ERR_TITLE_EMPTY'], StringMb::strtoupper($_SESSION['session_langue'])),
					'token' => $GLOBALS['STR_INVALID_TOKEN']));
			if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
				$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
			}
		}
		if (!$form_error_object->count()) {
			$output .= maj_cgv($id, $frm);
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_CGV_MSG_UPDATED_OK']))->fetch();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .=  $form_error_object->text('token');
			} else {
				$output .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_FORM_INCOMPLETE']))->fetch();
			}
		}
		$output .= affiche_formulaire_modif_cgv($id, $frm, $form_error_object);
		break;

	case "suppr" :
		$output .= supprime_cgv($_GET['id']);
		$output .= affiche_liste_cgv();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $_POST['mode'] . $_POST['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= insere_cgv($_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CGV_CREATED'], vb($_POST['nom_' . $_SESSION["session_langue"]]))))->fetch();
			$output .= affiche_liste_cgv();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_ajout_cgv($frm, $form_error_object);
		}
		break;

	case "ajout" :
		$output .= affiche_formulaire_ajout_cgv($frm, $form_error_object);
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_cgv($_GET['id'], $frm, $form_error_object);
		break;

	default :
		$output .= affiche_liste_cgv();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche le formulaire de modification pour les CGV sélectionnées
 *
 * @param mixed $frm
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_modif_cgv($id, &$frm, &$form_error_object)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations des CGV */
		$qid = query("SELECT *
			FROM peel_cgv
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('cgv', null, true));
		if ($frm = fetch_assoc($qid)) {
			$frm['site_country'] = explode(',', vb($frm['site_country']));
		}
	}
	if (!empty($frm)) {
		$frm['nouveau_mode'] = "maj";
		$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		return affiche_formulaire_cgv($frm, $form_error_object);
	} else {
		redirect_and_die(get_current_url(false).'?mode=ajout');
	}
}

/**
 * affiche_formulaire_cgv()
 *
 * @param mixed $frm
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_cgv(&$frm, &$form_error_object)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_cgv.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vn($frm['id']))));
	$tpl->assign('mode', $frm['nouveau_mode']);
	$tpl->assign('id', intval(vn($frm['id'])));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'error' => $form_error_object->text('titre_' . $lng),
			'titre' => vb($frm['titre_' . $lng]),
			'texte_te' => getTextEditor('texte_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['texte_' . $lng])))
			);
	}
	$tpl->assign('site_id_select_options', get_site_id_select_options(vn($frm['site_id'])));
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('normal_bouton', $frm['normal_bouton']);
	$tpl->assign('site_country_checkboxes', get_site_country_checkboxes(vb($frm['site_country'], array())));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
	}
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_CGV_FORM_EXPLAIN', $GLOBALS['STR_ADMIN_CGV_FORM_EXPLAIN']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_TITLE', $GLOBALS['STR_ADMIN_TITLE']);
	$tpl->assign('STR_ADMIN_CGV_TEXT', $GLOBALS['STR_ADMIN_CGV_TEXT']);
	return $tpl->fetch();
}

/**
 * maj_cgv()
 *
 * @param mixed $frm
 * @return
 */
function maj_cgv($id, $frm)
{
	$sql = "UPDATE peel_cgv 
		SET site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		, date_maj = '" . date('Y-m-d H:i:s', time()) . "'";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= "
		, site_country = '" . real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'";
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", titre_" . $lng . "='" . real_escape_string($frm['titre_' . $lng]) . "'
			, texte_" . $lng . "='" . real_escape_string($frm['texte_' . $lng]) . "'";
	}
	$sql .= "
		WHERE id = " . intval($id) . " AND " . get_filter_site_cond('cgv', null, true);
	$qid = query($sql);
}

/**
 * Affiche un formulaire vierge pour ajouter un cgv
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_cgv(&$frm, &$form_error_object)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
		}
		$frm['position'] = "";
		$frm['tarif'] = 0;
		$frm['tarif_percent'] = 0;
		$frm['tva'] = 0;
		$frm['technical_code'] = '';
		$frm['retour_possible'] = 1;
		$frm['totalmin'] = 0;
		$frm['totalmax'] = 0;
		$frm['site_id'] = 0;
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_ADD'];
	return affiche_formulaire_cgv($frm, $form_error_object);
}


/**
 * Supprime le cgv spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_cgv($id)
{
	$qid = query("SELECT *
		FROM peel_cgv
		WHERE id = " . intval($id) . " AND " . get_filter_site_cond('cgv', null, true));
	$p = fetch_assoc($qid);

	/* Efface le cgv */
	$qid = query("DELETE FROM peel_cgv 
		WHERE id=" . intval($id) . " AND " . get_filter_site_cond('cgv', null, true));
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_REQUEST_OK'], $p['titre_' . $_SESSION['session_langue']])))->fetch();
}

/**
 * Ajoute le cgv dans la table cgv
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_cgv(&$frm)
{
	$sql = "INSERT INTO peel_cgv 
		SET site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		, date_maj = '" . date('Y-m-d H:i:s', time()) . "'";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= ", site_country = '" . real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'";
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", titre_" . $lng . "='" . real_escape_string($frm['titre_' . $lng]) . "'
			, texte_" . $lng . "='" . real_escape_string($frm['texte_' . $lng]) . "'";
	}

	query($sql);
}
/**
 * affiche_liste_cgv()
 *
 * @return
 */
function affiche_liste_cgv()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_cgv.tpl');

	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	
	$sql = "SELECT *
		FROM peel_cgv
		WHERE " . get_filter_site_cond('cgv', null, true);
	$query = query($sql);
	if (!(num_rows($query) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'nom' => (!empty($ligne['titre_' . $_SESSION['session_langue']])?$ligne['titre_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'site_name' => get_site_name($ligne['site_id']));
			if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
				$site_country_array = array();
				if(StringMb::strlen($ligne['site_country'])>0) {
					foreach(explode(',', $ligne['site_country']) as $this_id) {
						$site_country_array[] = ($this_id == 0? $GLOBALS['STR_OTHER']:get_country_name($this_id));
					}
				}
				$tpl_results['site_country'] = implode(', ', $site_country_array);
			}
		}
		$tpl->assign('results', $tpl_results);
	}

	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_CGV_ADD', $GLOBALS['STR_ADMIN_CGV_ADD']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_CGV_TITLE', $GLOBALS['STR_ADMIN_CGV_TITLE']);
	$tpl->assign('STR_ADMIN_CGV_UPDATE', $GLOBALS['STR_ADMIN_CGV_UPDATE']);
	$tpl->assign('STR_ADMIN_CGV_NO_FOUND', $GLOBALS['STR_ADMIN_CGV_NO_FOUND']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
	}
	return $tpl->fetch();
}


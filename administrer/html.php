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
// $Id: html.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_content,admin_communication,admin_finance");
$id = vn($_GET['id']);

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_HTML_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_home($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_home($id, $frm);
		break;

	case "suppr" :
		supprime_home($id);
		affiche_liste_home($_GET);
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_home($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_HTML_MSG_ZONE_CREATED'], vb($_POST['titre']))))->fetch();
			affiche_liste_home($_GET);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_home($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_home($_POST['id'], $_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_HTML_MSG_ZONE_UPDATED'], vn($_POST['id']))))->fetch();
			affiche_liste_home($_GET);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_home($id, $frm);
		}
		break;

	default :
		affiche_liste_home($_GET);
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
function affiche_formulaire_ajout_home(&$frm)
{
	/* Default value*/
	if(empty($frm)) {
		$frm['etat'] = 1;
		$frm['titre'] = "";
		$frm['contenu_html'] = "";
		$frm['site_id'] = "";
		if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			$frm['site_country'] = $GLOBALS['site_parameters']['site_country_allowed_array'];
		}
	}
	$frm['lang'] = $_SESSION['session_langue'];
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['emplacement'] = "header";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_ADD'];

	affiche_formulaire_home($frm);
}

/**
 * Affiche le formulaire de modification pour la zone HTML sélectionnée
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_home($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_html
			WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('html', null, true) . "");
		if ($frm = fetch_assoc($qid)) {
			if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
				$frm['site_country'] = explode(',', vb($frm['site_country']));
			}
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_HTML_ERR_ZONE_NOT_FOUND']))->fetch();
			return false;
		}
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_home($frm);
}

/**
 * affiche_formulaire_home()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_home(&$frm)
{
	$output = '';
	$output .= call_module_hook('affiche_formulaire_home', $frm, 'string');
	// liste des emplacements prévus
	$emplacement_array['affiche_contenu_html_menu'] = $GLOBALS['STR_ADMIN_HTML_PLACE_CONTENU_HTML_MENU'];
	$emplacement_array['header'] = $GLOBALS['STR_ADMIN_HTML_PLACE_HEADER'];
	$emplacement_array['footer'] = $GLOBALS['STR_ADMIN_HTML_PLACE_FOOTER'];
	$emplacement_array['home'] = $GLOBALS['STR_ADMIN_HTML_PLACE_HOME'];
	$emplacement_array['home_bottom'] = $GLOBALS['STR_ADMIN_HTML_PLACE_HOME_BOTTOM'];
	$emplacement_array['conversion_page'] = $GLOBALS['STR_ADMIN_HTML_PLACE_CONVERSION_PAGE'];
	$emplacement_array['footer_link'] = $GLOBALS['STR_ADMIN_HTML_PLACE_FOOTER_LINK'];
	$emplacement_array['interstitiel'] = $GLOBALS['STR_ADMIN_HTML_PLACE_INTERSTITIEL'];
	$emplacement_array['error404'] = $GLOBALS['STR_ADMIN_HTML_PLACE_ERROR404'];
	$emplacement_array['scrolling'] = $GLOBALS['STR_ADMIN_HTML_PLACE_SCROLLING'];
	$emplacement_array['contact_page'] = $GLOBALS['STR_ADMIN_HTML_PLACE_CONTACT_PAGE'];
	if(check_if_module_active('carrousel', null, true)){
		$emplacement_array['entre_carrousel'] = $GLOBALS['STR_ADMIN_HTML_PLACE_CARROUSEL_TOP'];
	}
	if(check_if_module_active('reseller', null, true)){
		$emplacement_array['devenir_revendeur'] = $GLOBALS['STR_ADMIN_HTML_PLACE_BECOME_RESELLER'];
	}
	if(check_if_module_active('partenaires', null, true)){
		$emplacement_array['partner'] = $GLOBALS['STR_ADMIN_HTML_PLACE_PARTNER'];
	}
	if(check_if_module_active('reseller_map', null, true)){
		$emplacement_array['reseller_map'] = $GLOBALS['STR_ADMIN_HTML_PLACE_RESELLER_MAP'];
	}
	if(check_if_module_active('annonces', null, true)){
		$emplacement_array['home_ad'] = $GLOBALS['STR_ADMIN_HTML_PLACE_ADS_TOP'];
		$emplacement_array['top_create_ad'] = $GLOBALS['STR_ADMIN_HTML_PLACE_TOP_CREATE_AD'];
	}
	if(check_if_module_active('parrainage', null, true)){
		$emplacement_array['intro_parrainage'] = $GLOBALS['STR_ADMIN_HTML_PLACE_INTRO_PARRAINAGE'];
	}
	if(!empty($GLOBALS['site_parameters']['short_order_process'])){
		$emplacement_array['short_order_process'] = $GLOBALS['STR_ADMIN_HTML_PLACE_END_SHORT_ORDER_PROCESS'];
	}
	if(empty($emplacement_array[vb($frm['emplacement'])])){
		$emplacement_array[vb($frm['emplacement'])] = str_replace('_', ' ', ucfirst(vb($frm['emplacement'])));
	}
	asort($emplacement_array);
	
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_home.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('site_country_checkboxes', get_site_country_checkboxes(vb($frm['site_country'], array())));
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
	}
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'issel' => vb($frm['lang']) == $lng,
			'name' => $GLOBALS['lang_names'][$lng]
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('etat', vb($frm["etat"]));
	$tpl->assign('emplacement', vb($frm['emplacement']));
	$tpl->assign('emplacement_array', $emplacement_array);
	// Test sur la presence du fichier pour permettre le choix de l'emplacement independamment de la configuration du site
	$tpl->assign('titre', vb($frm['titre']));
	$tpl->assign('contenu_html_te', getTextEditor('contenu_html', '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['contenu_html']))));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_HTML_FORM_TITLE', $GLOBALS['STR_ADMIN_HTML_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_END_SHORT_ORDER_PROCESS', $GLOBALS['STR_ADMIN_HTML_PLACE_END_SHORT_ORDER_PROCESS']);
	$tpl->assign('STR_ADMIN_HTML_PLACE', $GLOBALS['STR_ADMIN_HTML_PLACE']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_CONTENU_HTML_MENU', $GLOBALS['STR_ADMIN_HTML_PLACE_CONTENU_HTML_MENU']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_HEADER', $GLOBALS['STR_ADMIN_HTML_PLACE_HEADER']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_FOOTER', $GLOBALS['STR_ADMIN_HTML_PLACE_FOOTER']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_HOME', $GLOBALS['STR_ADMIN_HTML_PLACE_HOME']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_HOME_BOTTOM', $GLOBALS['STR_ADMIN_HTML_PLACE_HOME_BOTTOM']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_CONVERSION_PAGE', $GLOBALS['STR_ADMIN_HTML_PLACE_CONVERSION_PAGE']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_FOOTER_LINK', $GLOBALS['STR_ADMIN_HTML_PLACE_FOOTER_LINK']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_INTERSTITIEL', $GLOBALS['STR_ADMIN_HTML_PLACE_INTERSTITIEL']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_ERROR404', $GLOBALS['STR_ADMIN_HTML_PLACE_ERROR404']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_SCROLLING', $GLOBALS['STR_ADMIN_HTML_PLACE_SCROLLING']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_CONTACT_PAGE', $GLOBALS['STR_ADMIN_HTML_PLACE_CONTACT_PAGE']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_CARROUSEL_TOP', $GLOBALS['STR_ADMIN_HTML_PLACE_CARROUSEL_TOP']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_BECOME_RESELLER', $GLOBALS['STR_ADMIN_HTML_PLACE_BECOME_RESELLER']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_PARTNER', $GLOBALS['STR_ADMIN_HTML_PLACE_PARTNER']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_RESELLER_MAP', $GLOBALS['STR_ADMIN_HTML_PLACE_RESELLER_MAP']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_ADS_TOP', $GLOBALS['STR_ADMIN_HTML_PLACE_ADS_TOP']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_TOP_CREATE_AD', $GLOBALS['STR_ADMIN_HTML_PLACE_TOP_CREATE_AD']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_TOP_CREATE_AD', $GLOBALS['STR_ADMIN_HTML_PLACE_TOP_CREATE_AD']);
	$tpl->assign('STR_ADMIN_HTML_PLACE_INTRO_PARRAINAGE', $GLOBALS['STR_ADMIN_HTML_PLACE_INTRO_PARRAINAGE']);
	$tpl->assign('STR_ADMIN_TITLE_NOT_DISPLAYED', $GLOBALS['STR_ADMIN_TITLE_NOT_DISPLAYED']);
	$tpl->assign('STR_ADMIN_HTML_TEXT', $GLOBALS['STR_ADMIN_HTML_TEXT']);
	$tpl->assign('STR_ADMIN_HTML_PHOTOS_WARNING', $GLOBALS['STR_ADMIN_HTML_PHOTOS_WARNING']);
	$tpl->assign('STR_VALIDATE', $GLOBALS['STR_VALIDATE']);
	$output .= $tpl->fetch();
	echo $output;
}

/**
 * Supprime la zone HTML spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_home($id)
{
	query("DELETE FROM peel_html WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('html', null, true) . "");
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_HTML_MSG_ZONE_DELETED']))->fetch();
}

/**
 * Ajoute la zone HTML dans la table peel_html
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_home($frm)
{
	$sql = "INSERT INTO peel_html (etat, titre, contenu_html, o_timestamp, a_timestamp, emplacement, lang, site_id";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= ", site_country";
	}
	$sql .= ")
		VALUES ('" . intval($frm['etat']) . "', '" . nohtml_real_escape_string($frm['titre']) . "', '" . real_escape_string($frm['contenu_html']) . "', '" . date('Y-m-d H:i:s', time()) . "', '" . date('Y-m-d H:i:s', time()) . "', '" . nohtml_real_escape_string($frm['emplacement']) . "', '" . nohtml_real_escape_string($frm['lang']) . "', '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= ", '" . word_real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'";
	}
	$sql .= ")";
	query($sql);
}

/**
 * maj_home()
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_home($id, $frm)
{
	$sql = "UPDATE peel_html
		SET etat = '" . intval($frm['etat']) . "'
			, titre = '" . nohtml_real_escape_string($frm['titre']) . "'
			, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
			".(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])?", site_country = '" . real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'":"")."
			, contenu_html = '" . real_escape_string($frm['contenu_html']) . "'
			".(!empty($frm['emplacement'])?", emplacement = '" . nohtml_real_escape_string($frm['emplacement']) . "'":"")."
			, a_timestamp = '" . date('Y-m-d H:i:s', time()) . "'
			, lang = '" . nohtml_real_escape_string(vb($frm['lang'])) . "'
		WHERE id = '" . intval($id) . "'";
	query($sql);
}

/**
 * affiche_liste_home()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_liste_home($frm=null)
{
	$sql = "SELECT *
		FROM peel_html
		WHERE " . get_filter_site_cond('html', null, true) . "";
	if(!empty($frm['technical_code'])) {
		$sql .= " AND emplacement LIKE '".real_escape_string($frm['technical_code'])."'";
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_home.tpl');

	$Links = new Multipage($sql, 'admin_liste_home');
	$HeaderTitlesArray = array($GLOBALS["STR_ADMIN_ACTION"], 'lang' => $GLOBALS["STR_ADMIN_LANGUAGE"], 'titre' => $GLOBALS["STR_ADMIN_TITLE"], 'a_timestamp' => $GLOBALS["STR_DATE"], 'emplacement' => $GLOBALS["STR_ADMIN_PLACE"], 'etat' => $GLOBALS["STR_STATUS"], 'site_id' => $GLOBALS["STR_ADMIN_WEBSITE"]);
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
		$HeaderTitlesArray['site_country'] = $GLOBALS["STR_ADMIN_SITE_COUNTRY"];
	}
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = 'a_timestamp';
	$Links->SortDefault = "DESC";
	$results_array = $Links->Query();
	
	$tpl->assign('links_header_row', $Links->getHeaderRow());
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	if (!empty($results_array)) {
		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $ligne) {
			$tmpLigne = array('tr_rollover' => tr_rollover($i, true),
				'site_name' => get_site_name($ligne['site_id']),
				'titre' => $ligne['titre'],
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'lang' => $ligne['lang'],
				'date' => get_formatted_date($ligne['a_timestamp'], 'short', 'long'),
				'emplacement' => $ligne['emplacement'],
				'etat_onclick' => 'change_status("html", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif')
				);
			if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
				$tmpLigne['site_country'] = get_country_name($ligne['site_country']);
			}
			$tpl_results[] = $tmpLigne;
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	if (check_if_module_active('welcome_ad')) {
		$tpl->assign('is_welcome_ad_module_active', true);
		unset($_SESSION['session_info_inter_set']);
	} else {
		$tpl->assign('is_welcome_ad_module_active', false);
	}
	$tpl->assign('STR_ADMIN_HTML_TITLE', $GLOBALS['STR_ADMIN_HTML_TITLE']);
	$tpl->assign('STR_ADMIN_HTML_CREATE', $GLOBALS['STR_ADMIN_HTML_CREATE']);
	$tpl->assign('STR_ADMIN_HTML_EXPLAIN', $GLOBALS['STR_ADMIN_HTML_EXPLAIN']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_NOTA_BENE', $GLOBALS['STR_NOTA_BENE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_ADMIN_TITLE', $GLOBALS['STR_ADMIN_TITLE']);
	$tpl->assign('STR_DATE', $GLOBALS['STR_DATE']);
	$tpl->assign('STR_VALIDATE', $GLOBALS['STR_VALIDATE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_PLACE', $GLOBALS['STR_ADMIN_PLACE']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_HTML_UPDATE', $GLOBALS['STR_ADMIN_HTML_UPDATE']);
	$tpl->assign('STR_ADMIN_HTML_DELETE_COOKIE_LINK', $GLOBALS['STR_ADMIN_HTML_DELETE_COOKIE_LINK']);
	echo $tpl->fetch();
}


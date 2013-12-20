<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: html.php 39392 2013-12-20 11:08:42Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_content");
$id = vn($_GET['id']);

$DOC_TITLE = $GLOBALS['STR_ADMIN_HTML_TITLE'];
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
		affiche_liste_home();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_home($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_HTML_MSG_ZONE_CREATED'], vb($_POST['titre']))))->fetch();
			affiche_liste_home();
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
			affiche_liste_home();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_home($id, $frm);
		}
		break;

	default :
		affiche_liste_home();
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
			WHERE id = '" . intval($id) . "'");
		if ($frm = fetch_assoc($qid)) {
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
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_home.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'issel' => vb($frm['lang']) == $lng,
			'name' => $GLOBALS['lang_names'][$lng]
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('etat', $frm["etat"]);
	$tpl->assign('emplacement', vb($frm['emplacement']));
	// Test sur la presence du fichier pour permettre le choix de l'emplacement independamment de la configuration du site
	$tpl->assign('is_carrousel_allowed', file_exists($GLOBALS['fonctionscarrousel']));
	$tpl->assign('is_reseller_allowed', is_reseller_module_active());
	$tpl->assign('is_partenaires_allowed', file_exists($GLOBALS['fonctionspartenaires']));
	$tpl->assign('is_reseller_map_allowed', file_exists($GLOBALS['fonctionsresellermap']));
	$tpl->assign('is_annonce_allowed', file_exists($GLOBALS['fonctionsannonces']));
	$tpl->assign('is_parrain_allowed', file_exists($GLOBALS['fonctionsparrain']));
	$tpl->assign('titre', vb($frm['titre']));
	$tpl->assign('contenu_html_te', getTextEditor('contenu_html', '100%', 500, String::html_entity_decode_if_needed(vb($frm['contenu_html']))));
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_HTML_FORM_TITLE', $GLOBALS['STR_ADMIN_HTML_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_HTML_PLACE', $GLOBALS['STR_ADMIN_HTML_PLACE']);
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
	echo $tpl->fetch();
}

/**
 * Supprime la zone HTML spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_home($id)
{
	query("DELETE FROM peel_html WHERE id='" . intval($id) . "'");
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
	$sql = "INSERT INTO peel_html (etat, titre, contenu_html, o_timestamp, a_timestamp, emplacement,lang)
		VALUES ('" . intval($frm['etat']) . "', '" . nohtml_real_escape_string($frm['titre']) . "', '" . real_escape_string($frm['contenu_html']) . "', '" . date('Y-m-d H:i:s', time()) . "', '" . date('Y-m-d H:i:s', time()) . "', '" . nohtml_real_escape_string($frm['emplacement']) . "', '" . nohtml_real_escape_string($frm['lang']) . "')";
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
			, contenu_html = '" . real_escape_string($frm['contenu_html']) . "'
			".(!empty($frm['emplacement'])?", emplacement = '" . nohtml_real_escape_string($frm['emplacement']) . "'":"")."
			, a_timestamp = '" . date('Y-m-d H:i:s', time()) . "'
			, lang = '" . nohtml_real_escape_string($frm['lang']) . "'
		WHERE id = '" . intval($id) . "'";
	query($sql);
}

/**
 * affiche_liste_home()
 *
 * @return
 */
function affiche_liste_home()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_home.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$result = query("SELECT *
		FROM peel_html
		ORDER BY a_timestamp DESC");
	if (!(num_rows($result) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($result)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'titre' => $ligne['titre'],
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'lang' => $ligne['lang'],
				'date' => get_formatted_date($ligne['a_timestamp'], 'short', 'long'),
				'emplacement' => $ligne['emplacement'],
				'etat_onclick' => 'change_status("html", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif')
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('wwwroot', $GLOBALS['wwwroot'] . '/');
	if (is_welcome_ad_module_active()) {
		$tpl->assign('is_welcome_ad_module_active', true);
		unset($_SESSION['session_info_inter_set']);
	} else {
		$tpl->assign('is_welcome_ad_module_active', false);
	}
	$tpl->assign('STR_ADMIN_HTML_TITLE', $GLOBALS['STR_ADMIN_HTML_TITLE']);
	$tpl->assign('STR_ADMIN_HTML_CREATE', $GLOBALS['STR_ADMIN_HTML_CREATE']);
	$tpl->assign('STR_ADMIN_HTML_EXPLAIN', $GLOBALS['STR_ADMIN_HTML_EXPLAIN']);
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

?>
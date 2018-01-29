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
// $Id: legal.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_content,admin_communication");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_LEGAL_TITLE'];

$id = intval(vn($_REQUEST['id']));
$frm = $_POST;

if (!isset($form_error_object)) {
	$form_error_object = new FormError();
}
$output = '';
switch (vb($_REQUEST['mode'])) {
	case "suppr" :
		$output .= supprime_legal($_GET['id']);
		$output .= affiche_liste_legal();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $_POST['mode'] . $_POST['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= insere_legal($_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_LEGAL_CREATED'], vb($_POST['nom_' . $_SESSION["session_langue"]]))))->fetch();
			$output .= affiche_liste_legal();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_ajout_legal($frm, $form_error_object);
		}
		break;

	case "ajout" :
		$output .= affiche_formulaire_ajout_legal($frm, $form_error_object);
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_legal($id, $frm, $form_error_object);
		break;

	case "maj" :
		if (!empty($_POST)) {
			foreach ($GLOBALS['admin_lang_codes'] as $lng) {
				$empty_field_messages_array['titre_' . $lng] = sprintf($GLOBALS['STR_ADMIN_LEGAL_ERR_EMPTY_TITLE'], StringMb::strtoupper($GLOBALS['lang_names'][$lng]));
			}
			$empty_field_messages_array['token'] = $GLOBALS['STR_INVALID_TOKEN'];
			$form_error_object->valide_form($frm, $empty_field_messages_array);
		}
		if (!$form_error_object->count()) {
			$output .= maj_legal($_POST['id'], $_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_LEGAL_MSG_UPDATE_OK']))->fetch();
		}
		if ($form_error_object->count()) {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			} else {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_FORM_INCOMPLETE']))->fetch();
			}
		}
		$output .= affiche_formulaire_modif_legal($id,$frm, $form_error_object);
		break;

	default :
		$output .= affiche_liste_legal();
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
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_modif_legal($id, &$frm, &$form_error_object)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations des CGV */
		$qid = query("SELECT * 
			FROM peel_legal
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('legal', null, true));
		$frm = fetch_assoc($qid);
	}
	if (!empty($frm)) {
		$frm['nouveau_mode'] = "maj";
		$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		return affiche_formulaire_legal($frm, $form_error_object);
	} else {
		redirect_and_die(get_current_url(false).'?mode=ajout');
	}
}

/**
 * affiche_formulaire_legal()
 *
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_legal(&$frm, &$form_error_object)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_legal.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . vb($frm['nouveau_mode']) . intval(vb($frm['id']))));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval(vb($frm['id'])));
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'error' => $form_error_object->text('titre_' . $lng),
			'titre' => vb($frm['titre_' . $lng]),
			'texte_te' => getTextEditor('texte_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['texte_' . $lng])))
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('normal_bouton', $frm["normal_bouton"]);
	$tpl->assign('STR_ADMIN_LEGAL_TITLE', $GLOBALS['STR_ADMIN_LEGAL_TITLE']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_TITLE', $GLOBALS['STR_ADMIN_TITLE']);
	$tpl->assign('STR_ADMIN_LEGAL_TEXT', $GLOBALS['STR_ADMIN_LEGAL_TEXT']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	return $tpl->fetch();
}

/**
 * maj_legal()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function maj_legal($id, $frm)
{
	$sql = "UPDATE peel_legal
		SET date_maj='" . date('Y-m-d H:i:s', time()) . "', site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])). "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", titre_" . $lng . "='" . real_escape_string($frm['titre_' . $lng]) . "'";
		$sql .= ", texte_" . $lng . "='" . real_escape_string($frm['texte_' . $lng]) . "'";
	}
	$sql .= "WHERE id = " . intval($id) . " AND " . get_filter_site_cond('legal', null, true);
	$qid = query($sql);
}

/**
 * Affiche un formulaire vierge pour ajouter un contacts
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_legal(&$frm, $form_error_object)
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
	return affiche_formulaire_legal($frm, $form_error_object);
}


/**
 * Supprime le contact spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_legal($id)
{
	/* Efface le contact */
	$qid = query("DELETE FROM peel_legal 
		WHERE id=" . intval($id) . " AND ". get_filter_site_cond('legal', null, true));
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_REQUEST_OK']))->fetch();
}

/**
 * Ajoute les informations dans la table legal
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_legal(&$frm)
{
	$sql = 'INSERT INTO peel_legal SET 
		site_id = "' . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . '"
		, date_maj = "' . date('Y-m-d H:i:s', time()) . '" ';
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= '
		, texte_' . $lng . ' = "' . real_escape_string(vb($frm['texte_' . $lng])) . '"';
	}
	query($sql);
}
/**
 * affiche_liste_legal()
 *
 * @return
 */
function affiche_liste_legal()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_legal.tpl');

	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	
	$sql = "SELECT *
		FROM peel_legal
		WHERE " . get_filter_site_cond('legal', null, true);
	$query = query($sql);
	if (!(num_rows($query) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'nom' => (!empty($ligne['titre_' . $_SESSION['session_langue']])?$ligne['titre_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'site_name' => get_site_name($ligne['site_id'])
				);
		}
		$tpl->assign('results', $tpl_results);
	}

	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_LEGAL_ADD', $GLOBALS['STR_ADMIN_LEGAL_ADD']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_LEGAL_TITLE', $GLOBALS['STR_ADMIN_LEGAL_TITLE']);
	$tpl->assign('STR_ADMIN_LEGAL_UPDATE', $GLOBALS['STR_ADMIN_LEGAL_UPDATE']);
	$tpl->assign('STR_ADMIN_LEGAL_NO_FOUND', $GLOBALS['STR_ADMIN_LEGAL_NO_FOUND']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	return $tpl->fetch();
}

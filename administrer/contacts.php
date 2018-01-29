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
// $Id: contacts.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users,admin_content,admin_webmastering");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_CONTACTS_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$id = intval(vn($_REQUEST['id']));

if (!isset($form_error_object)) {
	$form_error_object = new FormError();
}
$frm = $_POST;
$output = '';
switch (vb($_REQUEST['mode'])) {
	case "suppr" :
		$output .= supprime_contacts($_GET['id']);
		$output .= affiche_liste_contacts();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $_POST['mode'] . $_POST['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= insere_contacts($_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_LEGAL_CREATED'], vb($_POST['nom_' . $_SESSION["session_langue"]]))))->fetch();
			$output .= affiche_liste_contacts();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_ajout_contacts($frm, $form_error_object);
		}
		break;

	case "ajout" :
		$output .= affiche_formulaire_ajout_contacts($frm, $form_error_object);
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_contacts($id, $frm, $form_error_object);
		break;

	case "maj" :
		if (!empty($frm)) {
			foreach ($GLOBALS['admin_lang_codes'] as $lng) {
				$empty_field_messages_array['titre_' . $lng] = $GLOBALS['STR_ADMIN_ERR_CHOOSE_TITLE'];
			}
			$empty_field_messages_array['token'] = $GLOBALS['STR_INVALID_TOKEN'];
			$form_error_object->valide_form($frm, $empty_field_messages_array);
		}
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_contacts($id, $frm);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_MSG_UPDATE_OK']))->fetch();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			} else {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_FORM_INCOMPLETE']))->fetch();
			}
		}
		$output .= affiche_formulaire_modif_contacts($id, $frm, $form_error_object);
		break;

	default :
		$output .= affiche_liste_contacts();
		break;
}

echo $output;

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche le formulaire de modification pour le contact sélectionné
 * Charge les informations du contact
 *
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_modif_contacts($id, &$frm, &$form_error_object)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		$qid = query("SELECT *
			FROM peel_contacts
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('contacts', null, true));
		$frm = fetch_assoc($qid);
	}
	if (!empty($frm)) {
		$frm['nouveau_mode'] = "maj";
		$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		return affiche_formulaire_contact($frm, $form_error_object);
	} else {
		redirect_and_die(get_current_url(false).'?mode=ajout');
	}
}

/**
 * affiche_formulaire_contact()
 *
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_contact(&$frm, &$form_error_object)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_contact.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vn($frm['id']))));
	$tpl->assign('mode', $frm['nouveau_mode']);
	$tpl->assign('id', intval(vn($frm['id'])));
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'error' => $form_error_object->text('titre_' . $lng),
			'titre' => vb($frm['titre_' . $lng]),
			'texte_te' => getTextEditor('texte_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['texte_' . $lng]))),
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('normal_bouton', $frm['normal_bouton']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_CONTACTS_COMPANY_CONTACT_EXPLAIN', $GLOBALS['STR_ADMIN_CONTACTS_COMPANY_CONTACT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_TITLE', $GLOBALS['STR_ADMIN_TITLE']);
	$tpl->assign('STR_ADMIN_CONTACTS_TEXT_IN', $GLOBALS['STR_ADMIN_CONTACTS_TEXT_IN']);
	return $tpl->fetch();
}

/**
 * Met à jour le contact $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param array $frm Array with all fields data
 * @return
 */
function maj_contacts($id, $frm)
{
	$sql = 'UPDATE peel_contacts SET 
		site_id = "' . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . '"
		, date_maj = "' . date('Y-m-d H:i:s', time()) . '"';
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= '
			, titre_' . $lng . ' = "' . real_escape_string($frm['titre_' . $lng]) . '"
			, texte_' . $lng . ' = "' . real_escape_string($frm['texte_' . $lng]) . '"';
	}
	$sql .= ' WHERE
		id = "' . intval($id) . '" AND ' . get_filter_site_cond('contacts', null, true);
	query($sql);
}

/**
 * Affiche un formulaire vierge pour ajouter un contacts
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_contacts(&$frm, &$form_error_object)
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
	return affiche_formulaire_contact($frm, $form_error_object);
}


/**
 * Supprime le contact spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_contacts($id)
{
	/* Efface le contact */
	$qid = query("DELETE FROM peel_contacts 
		WHERE id=" . intval($id) . " AND " . get_filter_site_cond('contacts', null, true));
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_CONTACTS_MSG_DELETED_OK']))->fetch();
}

/**
 * Ajoute le contact dans la table contacts
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_contacts(&$frm)
{
	$sql = 'INSERT INTO peel_contacts SET 
		site_id = "' . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . '"
		, date_maj = "' . date('Y-m-d H:i:s', time()) . '"';
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= '
			, titre_' . $lng . ' = "' . real_escape_string($frm['titre_' . $lng]) . '"
			, texte_' . $lng . ' = "' . real_escape_string($frm['texte_' . $lng]) . '"';
	}
	query($sql);
}
/**
 * affiche_liste_contacts()
 *
 * @return
 */
function affiche_liste_contacts()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_contacts.tpl');

	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	
	$sql = "SELECT *
		FROM peel_contacts
		WHERE " . get_filter_site_cond('contacts', null, true);
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
	$tpl->assign('STR_ADMIN_CONTACTS_ADD', $GLOBALS['STR_ADMIN_CONTACTS_ADD']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_CONTACTS_TITLE', $GLOBALS['STR_ADMIN_CONTACTS_TITLE']);
	$tpl->assign('STR_ADMIN_CONTACTS_UPDATE', $GLOBALS['STR_ADMIN_CONTACTS_UPDATE']);
	$tpl->assign('STR_ADMIN_CONTACTS_NO_FOUND', $GLOBALS['STR_ADMIN_CONTACTS_NO_FOUND']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	return $tpl->fetch();
}


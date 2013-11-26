<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: plan.php 38682 2013-11-13 11:35:48Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv('admin_content');

$DOC_TITLE = $GLOBALS['STR_ADMIN_PLAN_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$id = intval(vn($_REQUEST['id']));

if (!isset($form_error_object)) {
	$form_error_object = new FormError();
}

switch (vb($_REQUEST['mode'])) {
	case "maj" :
		if (!empty($_POST)) {
			$frm = $_POST;
			$empty_field_messages_array['map_tag'] = $GLOBALS['STR_ADMIN_PLAN_ERR_TAG_VALID_NEEDED'];
			$empty_field_messages_array['token'] = $GLOBALS['STR_INVALID_TOKEN'];
			$form_error_object->valide_form($frm, $empty_field_messages_array);
		}
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_contacts($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_PLAN_MSG_UPDATED_OK']))->fetch();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_FORM_INCOMPLETE']))->fetch();
			}
		}
		affiche_formulaire_modif_contacts($frm, $form_error_object);
		break;

	default :
		affiche_formulaire_modif_contacts($frm, $form_error_object);
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche le formulaire de modification pour le contacts sélectionné
 * Charge les informations du contacts
 *
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_modif_contacts(&$frm, &$form_error_object)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		$qid = query("SELECT *
			FROM peel_access_map
			WHERE id = 1");
		$frm = fetch_assoc($qid);
	}
	$frm['nouveau_mode'] = "maj";
	$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_contact($frm, $form_error_object);
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
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_plan_formulaire_contact.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vn($frm['id']))));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval(vn($frm['id'])));

	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'text_te' => getTextEditor('text_' . $lng, '100%', 500, String::html_entity_decode_if_needed(vb($frm['text_' . $lng]))),
			);
	}
	$tpl->assign('langs', $tpl_langs);

	$tpl->assign('error', $form_error_object->text('map_tag'));
	$tpl->assign('map_tag', vb($frm['map_tag']));
	$tpl->assign('normal_bouton', $frm['normal_bouton']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_PLAN_UPDATE', $GLOBALS['STR_ADMIN_PLAN_UPDATE']);
	$tpl->assign('STR_ADMIN_PLAN_TAG_EXPLAIN', $GLOBALS['STR_ADMIN_PLAN_TAG_EXPLAIN']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_HEADER_HTML_TEXT', $GLOBALS['STR_ADMIN_HEADER_HTML_TEXT']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_PLAN_TAG_CODE', $GLOBALS['STR_ADMIN_PLAN_TAG_CODE']);
	echo $tpl->fetch();
}

/**
 * Met à jour le contact $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param array $frm Array with all fields data
 * @return
 */
function maj_contacts($frm)
{
	$sql = 'UPDATE peel_access_map SET
		map_tag = "' . real_escape_string($frm['map_tag']) . '"
		, date_maj = "' . date('Y-m-d H:i:s', time()) . '" ';
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= '
		, text_' . $lng . ' = "' . real_escape_string($frm['text_' . $lng]) . '"';
	}
	$sql .= 'WHERE id = "1"';
	query($sql);
}

?>
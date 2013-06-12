<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: legal.php 37040 2013-05-30 13:17:16Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_content");

$DOC_TITLE = $GLOBALS['STR_ADMIN_LEGAL_TITLE'];
include("modeles/haut.php");

$id = intval(vn($_REQUEST['id']));

if (!isset($form_error_object)) {
	$form_error_object = new FormError();
}

switch (vb($_REQUEST['mode'])) {
	case "maj" :
		if (!empty($_POST)) {
			$frm = $_POST;
			foreach ($GLOBALS['admin_lang_codes'] as $lng) {
				$empty_field_messages_array['titre_' . $lng] = sprintf($GLOBALS['STR_ADMIN_LEGAL_ERR_EMPTY_TITLE'], String::strtoupper($lng));
			}
			$empty_field_messages_array['token'] = $GLOBALS['STR_INVALID_TOKEN'];
			$form_error_object->valide_form($frm, $empty_field_messages_array);
		}
		if (!$form_error_object->count()) {
			maj_affiliation($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_LEGAL_MSG_UPDATE_OK']))->fetch();
		}
		if ($form_error_object->count()) {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_FORM_INCOMPLETE']))->fetch();
			}
		}
		affiche_formulaire_modif_affiliation($frm, $form_error_object);
		break;

	default :
		affiche_formulaire_modif_affiliation($frm, $form_error_object);
		break;
}

include("modeles/bas.php");

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
function affiche_formulaire_modif_affiliation(&$frm, &$form_error_object)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations des CGV */
		$qid = query("SELECT * 
			FROM peel_legal
			WHERE id = 1");
		$frm = fetch_assoc($qid);
	}
	$frm['nouveau_mode'] = "maj";
	$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_affiliation($frm, $form_error_object);
}

/**
 * affiche_formulaire_affiliation()
 *
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_affiliation(&$frm, &$form_error_object)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_affiliation.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . vb($frm['nouveau_mode']) . intval(vb($frm['id']))));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval(vb($frm['id'])));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'error' => $form_error_object->text('titre_' . $lng),
			'titre' => vb($frm['titre_' . $lng]),
			'texte_te' => getTextEditor('texte_' . $lng, 760, 500, String::html_entity_decode_if_needed(vb($frm['texte_' . $lng])))
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('normal_bouton', $frm["normal_bouton"]);
	$tpl->assign('STR_ADMIN_LEGAL_TITLE', $GLOBALS['STR_ADMIN_LEGAL_TITLE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_TITLE', $GLOBALS['STR_ADMIN_TITLE']);
	$tpl->assign('STR_ADMIN_LEGAL_TEXT', $GLOBALS['STR_ADMIN_LEGAL_TEXT']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	echo $tpl->fetch();
}

/**
 * maj_affiliation()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function maj_affiliation($frm)
{
	$sql = "UPDATE peel_legal SET date_maj = '" . date('Y-m-d H:i:s', time()) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", titre_" . $lng . "='" . real_escape_string($frm['titre_' . $lng]) . "'";
		$sql .= ", texte_" . $lng . "='" . real_escape_string($frm['texte_' . $lng]) . "'";
	}
	$sql .= "WHERE id = '1'";
	$qid = query($sql);
}

?>
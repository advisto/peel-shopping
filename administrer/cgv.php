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
// $Id: cgv.php 39392 2013-12-20 11:08:42Z gboussin $

define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv('admin_content');

$DOC_TITLE = $GLOBALS['STR_ADMIN_CGV_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$id = intval(vn($_REQUEST['id']));

if (!isset($form_error_object)) {
	$form_error_object = new FormError();
}

switch (vb($_REQUEST['mode'])) {
	case "maj" :

		if (!empty($_POST)) {
			$frm = $_POST;
			$form_error_object->valide_form($frm,
				array('titre_' . $_SESSION['session_langue'] => sprintf($GLOBALS['STR_ADMIN_CGV_ERR_TITLE_EMPTY'], String::strtoupper($_SESSION['session_langue'])),
					'token' => $GLOBALS['STR_INVALID_TOKEN']));
			if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
				$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
			}
		}
		if (!$form_error_object->count()) {
			maj_cgv($frm);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_CGV_MSG_UPDATED_OK']))->fetch();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_FORM_INCOMPLETE']))->fetch();
			}
		}
		affiche_formulaire_modif_cgv($frm, $form_error_object);
		break;

	default :
		affiche_formulaire_modif_cgv($frm, $form_error_object);
		break;
}

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
function affiche_formulaire_modif_cgv(&$frm, &$form_error_object)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations des CGV */
		$qid = query("SELECT *
			FROM peel_cgv
			WHERE id = 1");
		$frm = fetch_assoc($qid);
	}
	$frm['nouveau_mode'] = "maj";
	$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_cgv($frm, $form_error_object);
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
			'texte_te' => getTextEditor('texte_' . $lng, '100%', 500, String::html_entity_decode_if_needed(vb($frm['texte_' . $lng])))
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('normal_bouton', $frm['normal_bouton']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_CGV_FORM_EXPLAIN', $GLOBALS['STR_ADMIN_CGV_FORM_EXPLAIN']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_TITLE', $GLOBALS['STR_ADMIN_TITLE']);
	$tpl->assign('STR_ADMIN_CGV_TEXT', $GLOBALS['STR_ADMIN_CGV_TEXT']);
	echo $tpl->fetch();
}

/**
 * maj_cgv()
 *
 * @param mixed $frm
 * @return
 */
function maj_cgv($frm)
{
	$sql = "UPDATE peel_cgv 
		SET date_maj = '" . date('Y-m-d H:i:s', time()) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", titre_" . $lng . "='" . real_escape_string($frm['titre_' . $lng]) . "'
			, texte_" . $lng . "='" . real_escape_string($frm['texte_' . $lng]) . "'";
	}
	$sql .= "
		WHERE id='1'";
	$qid = query($sql);
}

?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: tva.php 35064 2013-02-08 14:16:40Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$DOC_TITLE = $GLOBALS['STR_ADMIN_TVA_TITLE'];
include("modeles/haut.php");

if (isset($_POST['mode'])) {
	$mode = $_POST['mode'];
} elseif (isset($_GET['mode'])) {
	$mode = $_GET['mode'];
} else {
	$mode = "liste";
}
$frm = $_POST;
$form_error_object = new FormError();

switch ($mode) {
	case "ajout" :
		affiche_formulaire_ajout_tva($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_tva($_GET['id'], $frm);
		break;

	case "suppr" :
		supprime_tva($_GET['id']);
		affiche_liste_tva();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_tva($_POST);
			affiche_liste_tva();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_tva($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_tva($_POST['id'], $_POST);
			affiche_liste_tva();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_tva($_GET['id'], $frm);
		}
		break;

	default :
		affiche_liste_tva();
		break;
}

include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter une TVA
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_tva(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm['tva'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_ADD'];

	affiche_formulaire_tva($frm);
}

/**
 * Affiche le formulaire de modification pour la TVA sélectionnée
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_tva(&$id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_tva
			WHERE id = '" . intval($id) . "'");
		$frm = fetch_assoc($qid);
	}
	$frm['nouveau_mode'] = "maj";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_TVA_SAVE'];

	affiche_formulaire_tva($frm);
}

/**
 * affiche_formulaire_tva()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_tva($frm)
{
	if (!empty($frm['id'])) {
		$id = $frm['id'];
	} else {
		$id = "";
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_tva.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($id)));
	$tpl->assign('mode', vb($frm['nouveau_mode']));
	$tpl->assign('id', intval($id));
	$tpl->assign('tva', $frm['tva']);
	$tpl->assign('titre_bouton', $frm['titre_bouton']);
	$tpl->assign('STR_ADMIN_TVA_FORM_TITLE', $GLOBALS['STR_ADMIN_TVA_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_VAT_PERCENTAGE', $GLOBALS['STR_ADMIN_VAT_PERCENTAGE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	echo $tpl->fetch();
}

/**
 * Supprime la TVA spécifiée par $id
 *
 * @param integer $id
 * @return
 */
function supprime_tva($id)
{
	query("DELETE FROM peel_tva WHERE id='" . intval($id) . "'");
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_TVA_MSG_DELETED_OK']))->fetch();
}

/**
 * Ajoute la TVA dans la table tva
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_tva($frm)
{
	$frm['tva'] = get_float_from_user_input($frm['tva']);
	$qid = query("SELECT *
		FROM peel_tva
		WHERE tva = '" . floatval($frm['tva']) . "'");
	if (!fetch_assoc($qid)) {
		$qid = query("INSERT INTO peel_tva (tva)
			VALUES ('" . floatval($frm['tva']) . "')");
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_TVA_MSG_CREATED_OK']))->fetch();
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_TVA_ERR_ALREADY_EXISTS']))->fetch();
	}
}

/**
 * Met à jour de la TVA $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_tva($id, $frm)
{
	$frm['tva'] = get_float_from_user_input($frm['tva']);
	query("UPDATE peel_tva
		SET tva='" . floatval($frm['tva']) . "'
		WHERE id='" . intval($frm['id']) . "'");
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_TVA_MSG_UPDATED_OK']))->fetch();
}

/**
 * affiche_liste_tva()
 *
 * @return
 */
function affiche_liste_tva()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_tva.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$result = query("SELECT id, tva
		FROM peel_tva
		ORDER BY id ASC");
	if (!(num_rows($result) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($result)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'modif_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'tva' => $ligne['tva']
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_TVA_TITLE', $GLOBALS['STR_ADMIN_TVA_TITLE']);
	$tpl->assign('STR_ADMIN_TVA_FORM_EXPLAIN', $GLOBALS['STR_ADMIN_TVA_FORM_EXPLAIN']);
	$tpl->assign('STR_ADMIN_TVA_CREATE', $GLOBALS['STR_ADMIN_TVA_CREATE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_VAT_PERCENTAGE', $GLOBALS['STR_ADMIN_VAT_PERCENTAGE']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_ADMIN_TVA_DELETE', $GLOBALS['STR_ADMIN_TVA_DELETE']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_TVA_UPDATE', $GLOBALS['STR_ADMIN_TVA_UPDATE']);
	$tpl->assign('STR_ADMIN_TVA_NOTHING_FOUND', $GLOBALS['STR_ADMIN_TVA_NOTHING_FOUND']);
	echo $tpl->fetch();
}

?>
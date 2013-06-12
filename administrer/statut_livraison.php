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
// $Id: statut_livraison.php 37040 2013-05-30 13:17:16Z gboussin $

define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage,admin_sales");

$DOC_TITLE = $GLOBALS['STR_ADMIN_STATUT_LIVRAISON_TITLE'];
include("modeles/haut.php");

$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_statut($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_statut($_GET['id'], $frm);
		break;

	case "suppr" :
		supprime_statut($_GET['id']);
		affiche_liste_statut();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_statut($_POST);
			affiche_liste_statut();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_statut($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_statut($_POST['id'], $_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_STATUT_LIVRAISON_MSG_UPDATED_OK'], $_POST['id'])))->fetch();
			affiche_liste_statut();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_statut($_GET['id'], $frm);
		}
		break;

	default :
		affiche_liste_statut();
		break;
}

include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un statut
 *
 * @return
 */
function affiche_formulaire_ajout_statut(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm['position'] = 0;
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
		}
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['new_id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_STATUT_LIVRAISON_CREATE'];

	affiche_formulaire_statut($frm);
}

/**
 * Affiche le formulaire de modification pour le statut sélectionné
 *
 * @param integer $id
 * @param array $frm
 * @return
 */
function affiche_formulaire_modif_statut($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_statut_livraison
			WHERE id = " . intval($id) . "");
		$frm = fetch_assoc($qid);
	}
	$frm['new_id'] = $frm['id'];
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_statut($frm);
}

/**
 * affiche_formulaire_statut()
 *
 * @return
 */
function affiche_formulaire_statut(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_statut.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', vb($frm['nouveau_mode']));
	$tpl->assign('id', intval(vb($frm['id'])));
	$tpl->assign('new_id', intval(vb($frm['new_id'])));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => $frm['nom_' . $lng]
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('position', $frm['position']);
	$tpl->assign('titre_bouton', $frm['titre_bouton']);
	$tpl->assign('STR_ADMIN_STATUT_FORM_TITLE', $GLOBALS['STR_ADMIN_STATUT_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	echo $tpl->fetch();
}

/**
 * Supprime le statut spécifié par $id
 *
 * @param integer $id
 * @return
 */
function supprime_statut($id)
{
	/* Efface le statut */
	query("DELETE FROM peel_statut_livraison WHERE id=" . intval($id));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_STATUT_LIVRAISON_MSG_DELETED_OK'], get_delivery_status_name($id))))->fetch();
}

/**
 * Ajoute le statut dans la table statut
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_statut($frm)
{
	$sql = "INSERT INTO peel_statut_livraison (position, id";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng;
	}
	$sql .= "
	) VALUES ('" . intval($frm['position']) . "', '" . intval($frm['new_id']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= ")";
	query($sql);
}

/**
 * Met à jour le statut $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_statut($id, $frm)
{
	$sql = "UPDATE peel_statut_livraison SET position='" . intval($frm['position']) . "', id='" . intval($frm['new_id']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . " = '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= "WHERE id = '" . intval($id) . "'";

	query($sql);
}

/**
 * affiche_liste_statut()
 *
 * @return
 */
function affiche_liste_statut()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_statut_livraison.tpl');
	$result = query("SELECT id, position, nom_" . $_SESSION['session_langue'] . "
		FROM peel_statut_livraison
		ORDER BY position ASC, id ASC");
	if (!(num_rows($result) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($result)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'id' => $ligne['id'],
				'modif_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'nom' => $ligne['nom_' . $_SESSION['session_langue']],
				'position' => $ligne['position']
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$GLOBALS['sortable_rpc'] = 'rpc_positions.php?mode=statut_livraison';
	$tpl->assign('add_button_url', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_status_url', get_current_url(false) . '?mode=ajout');
	$tpl->assign('STR_ADMIN_STATUT_LIVRAISON_TITLE', $GLOBALS['STR_ADMIN_STATUT_LIVRAISON_TITLE']);
	$tpl->assign('STR_ADMIN_STATUT_LIVRAISON_EXPLAIN', $GLOBALS['STR_ADMIN_STATUT_LIVRAISON_EXPLAIN']);
	$tpl->assign('STR_ADMIN_STATUT_LIVRAISON_CREATE', $GLOBALS['STR_ADMIN_STATUT_LIVRAISON_CREATE']);
	$tpl->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
	$tpl->assign('STR_ADMIN_STATUT_STATUS_TYPE', $GLOBALS['STR_ADMIN_STATUT_STATUS_TYPE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_STATUT_UPDATE', $GLOBALS['STR_ADMIN_STATUT_UPDATE']);
	$tpl->assign('STR_ADMIN_STATUT_NO_STATUS_FOUND', $GLOBALS['STR_ADMIN_STATUT_NO_STATUS_FOUND']);
	echo $tpl->fetch();
}

?>
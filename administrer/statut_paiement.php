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
// $Id: statut_paiement.php 55332 2017-12-01 10:44:06Z sdelaporte $

define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage,admin_sales,admin_finance");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_STATUT_PAIEMENT_TITLE'];

$frm = $_POST;
$form_error_object = new FormError();
$output = '';

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		$output .= affiche_formulaire_ajout_statut($frm);
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_statut($_GET['id'], $frm);
		break;

	case "suppr" :
		$output .= supprime_statut($_GET['id']);
		$output .= affiche_liste_statut();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_statut($_POST);
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_STATUT_PAIEMENT_MSG_CREATED_OK']))->fetch();
			$output .= affiche_liste_statut();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .=  $form_error_object->text('token');
			}
			$output .= affiche_formulaire_ajout_statut($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_statut($_POST['id'], $_POST);
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_STATUT_PAIEMENT_MSG_UPDATED_OK'], $_POST['id'])))->fetch();
			$output .= affiche_liste_statut();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .=  $form_error_object->text('token');
			}
			$output .= affiche_formulaire_modif_statut($_GET['id'], $frm);
		}
		break;

	default :
		$output .= affiche_liste_statut();
		break;
}
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un statut
 *
 * @param array $frm Array with all fields data
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
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_STATUT_PAIEMENT_CREATE'];

	return affiche_formulaire_statut($frm);
}

/**
 * Affiche le formulaire de modification pour le statut sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_statut($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_statut_paiement
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('statut_paiement', null, true) . "");
		$frm = fetch_assoc($qid);
	}
	if (!empty($frm)) {
		$frm["nouveau_mode"] = "maj";
		$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		return affiche_formulaire_statut($frm);
	} else {
		redirect_and_die(get_current_url(false).'?mode=ajout');
	}
}

/**
 * affiche_formulaire_statut()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_statut(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_statut.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', vb($frm['nouveau_mode']));
	$tpl->assign('id', intval(vn($frm['id'])));
	$tpl->assign('technical_code', vb($frm['technical_code']));
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => $frm['nom_' . $lng]
			);
	}
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
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
	return $tpl->fetch();
}

/**
 * Supprime le statut spécifié par $id
 *
 * @param integer $id
 * @return
 */
function supprime_statut($id)
{
	$qid = query("SELECT nom_" . $_SESSION['session_langue'] . "
		FROM peel_statut_paiement
		WHERE id = " . intval($id) . " AND " . get_filter_site_cond('statut_paiement', null, true) . "");
	$p = fetch_assoc($qid);

	/* Efface le statut */
	query("DELETE FROM peel_statut_paiement WHERE id=" . intval($id) . " AND " . get_filter_site_cond('statut_paiement', null, true));
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_STATUT_PAIEMENT_CREATE'], $p['nom_' . $_SESSION['session_langue']])))->fetch();
}

/**
 * insere_statut()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_statut(&$frm)
{
	/* ajoute le statut dans la table statut */
	$sql = "INSERT INTO peel_statut_paiement (position, technical_code, site_id";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng;
	}
	$sql .= "
		) VALUES ('" . intval($frm['position']) . "', '" . nohtml_real_escape_string($frm['technical_code']) . "', '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
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
function maj_statut($id, &$frm)
{
	$sql = "UPDATE peel_statut_paiement
		SET	position='" . intval($frm['position']) . "', technical_code='" . nohtml_real_escape_string($frm['technical_code']) . "', site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . " = '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= " WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('statut_paiement', null, true);

	query($sql);
}

/**
 * affiche_liste_statut()
 *
 * @return
 */
function affiche_liste_statut()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_statut_paiement.tpl');
	$query = query("SELECT id, position, nom_" . $_SESSION['session_langue'] . ", site_id, technical_code
		FROM peel_statut_paiement
		WHERE " . get_filter_site_cond('statut_paiement', null, true) . "
		ORDER BY position ASC, id ASC");
	if (!(num_rows($query) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, null, null, 'sortable_'.$ligne['id']),
				'technical_code' => $ligne['technical_code'],
				'modif_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'nom' => $ligne['nom_' . $_SESSION['session_langue']],
				'position' => $ligne['position'],
				'site_name' => get_site_name($ligne['site_id'])
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$GLOBALS['sortable_rpc'] = 'rpc_positions.php?mode=statut_paiement';
	$tpl->assign('STR_ADMIN_STATUT_PAIEMENT_TITLE', $GLOBALS['STR_ADMIN_STATUT_PAIEMENT_TITLE']);
	$tpl->assign('STR_ADMIN_STATUT_PAIEMENT_EXPLAIN', $GLOBALS['STR_ADMIN_STATUT_PAIEMENT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_STATUT_PAIEMENT_CREATE', $GLOBALS['STR_ADMIN_STATUT_PAIEMENT_CREATE']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_ADMIN_STATUT_STATUS_TYPE', $GLOBALS['STR_ADMIN_STATUT_STATUS_TYPE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_STATUT_UPDATE', $GLOBALS['STR_ADMIN_STATUT_UPDATE']);
	$tpl->assign('STR_ADMIN_STATUT_NO_STATUS_FOUND', $GLOBALS['STR_ADMIN_STATUT_NO_STATUS_FOUND']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	return $tpl->fetch();
}


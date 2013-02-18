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
// $Id: ecotaxes.php 35064 2013-02-08 14:16:40Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$DOC_TITLE = $GLOBALS['STR_ADMIN_ECOTAXES_TITLE'];
include("modeles/haut.php");

$form_error_object = new FormError();
$frm = $_POST;

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_ecotaxes($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_ecotaxes($_GET['id'], $frm);
		break;

	case "suppr" :
		supprime_ecotaxes($_GET['id']);
		affiche_liste_ecotaxes();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_ecotaxes($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ECOTAXES_MSG_ECOTAX_CREATED'], vb($_POST['nom_'.$_SESSION['session_langue']]))))->fetch();
			affiche_liste_ecotaxes();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_ecotaxes($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_ecotaxes($frm['id'], $frm);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ECOTAXES_MSG_ECOTAX_UPDATED'], vn($frm['id']))))->fetch();
			affiche_liste_ecotaxes();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_ecotaxes($frm['id'], $frm);
		}
		break;

	default :
		affiche_liste_ecotaxes();
		break;
}

include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter une ecotaxe
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_ecotaxes(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm['code'] = "";
		foreach ($GLOBALS['lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
		}
		$frm['prix_ht'] = "";
		$frm['ttc'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_ADD'];

	affiche_formulaire_ecotaxes($frm);
}

/**
 * Affiche le formulaire de modification pour le ecotaxes sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_ecotaxes($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations de l'écotaxe */
		$qid = query("SELECT *
			FROM peel_ecotaxes
			WHERE id = " . intval($id));
		if ($frm = fetch_assoc($qid)) {
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ECOTAXES_ERR_ECOTAX_NOT_FOUND']))->fetch();
			return false;
		}
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_ecotaxes($frm);
}

/**
 * affiche_formulaire_ecotaxes()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ecotaxes(&$frm)
{
	if (vb($_REQUEST['mode']) == "modif") {
		$sql = "SELECT prix_ht, prix_ttc
			FROM peel_ecotaxes
			WHERE id ='" . intval($frm['id']) . "'";
		$query = query($sql);
		$result = fetch_assoc($query);
		$frm["prix_ht"] = $result['prix_ht'];
		$prix_ttc = $result['prix_ttc'];
		$calculated_vat = round(($prix_ttc - $frm["prix_ht"]) / $frm["prix_ht"] * 100 * 100) / 100;
	} else {
		$calculated_vat = 0;
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_ecotaxes.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('code', $frm["code"]);
	$tpl_langs = array();
	foreach ($GLOBALS['lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => $frm['nom_' . $lng]
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('prix_ht', $frm["prix_ht"]);
	$tpl->assign('vat_options', get_vat_select_options($calculated_vat, true));
	$tpl->assign('titre_bouton', $frm["titre_bouton"]);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_ECOTAXES_FORM_TITLE', $GLOBALS['STR_ADMIN_ECOTAXES_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_CODE', $GLOBALS['STR_ADMIN_CODE']);
	$tpl->assign('STR_PRICE', $GLOBALS['STR_PRICE']);
	$tpl->assign('STR_HT', $GLOBALS['STR_HT']);
	$tpl->assign('STR_TAXE', $GLOBALS['STR_TAXE']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	echo $tpl->fetch();
}

/**
 * Supprime les ecotaxes spécifiées par $id
 *
 * @param integer $id
 * @return
 */
function supprime_ecotaxes($id)
{
	$qid = query("SELECT *
		FROM peel_ecotaxes
		WHERE id=" . intval($id));
	$e = fetch_assoc($qid);

	/* Efface le ecotaxes */
	query("DELETE FROM peel_ecotaxes
		WHERE id=" . intval($id));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ECOTAXES_MSG_ECOTAX_DELETED'], $e['nom_'.$_SESSION['session_langue']])))->fetch();
}

/**
 * Ajoute le ecotaxes dans la table ecotaxes
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_ecotaxes($frm)
{
	$sql = "INSERT INTO peel_ecotaxes
		SET code = '" . nohtml_real_escape_string($frm['code']) . "'";
	foreach ($GLOBALS['lang_codes'] as $lng) {
		$sql .= "
			, nom_" . $lng . "='" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= "
			, prix_ht = '" . nohtml_real_escape_string($frm['prix']) . "'
			, prix_ttc = '" . nohtml_real_escape_string($frm['prix'] * (1 + $frm['taxes'] / 100)) . "'";
	$qid = query($sql);
}

/**
 * maj_ecotaxes()
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_ecotaxes($id, $frm)
{
	$sql = "UPDATE peel_ecotaxes
		SET code = '" . nohtml_real_escape_string($frm['code']) . "'";
	foreach ($GLOBALS['lang_codes'] as $lng) {
		$sql .= "
			, nom_" . $lng . "='" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= "
			, prix_ht = '" . nohtml_real_escape_string($frm['prix']) . "'
			, prix_ttc = '" . nohtml_real_escape_string($frm['prix'] * (1 + $frm['taxes'] / 100)) . "'
		WHERE id = '" . intval($id) . "'";
	$qid = query($sql);
}

/**
 * affiche_liste_ecotaxes()
 *
 * @return
 */
function affiche_liste_ecotaxes()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_ecotaxes.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');

	$result = query("SELECT *
		FROM peel_ecotaxes
		ORDER BY code");
	if (!(num_rows($result) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($result)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'nom' => (!empty($ligne['nom_' . $_SESSION['session_langue']])?$ligne['nom_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'code' => $ligne['code'],
				'prix_ht' => fprix($ligne['prix_ht'], true, $GLOBALS['site_parameters']['code'], false),
				'prix_ttc' => fprix($ligne['prix_ttc'], true, $GLOBALS['site_parameters']['code'], false)
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_ADMIN_ECOTAXES_TITLE', $GLOBALS['STR_ADMIN_ECOTAXES_TITLE']);
	$tpl->assign('STR_ADMIN_ECOTAXES_EXPLAIN', $GLOBALS['STR_ADMIN_ECOTAXES_EXPLAIN']);
	$tpl->assign('STR_ADMIN_ECOTAXES_ADD_ECOTAX', $GLOBALS['STR_ADMIN_ECOTAXES_ADD_ECOTAX']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_CODE', $GLOBALS['STR_ADMIN_CODE']);
	$tpl->assign('STR_ADMIN_ECOTAX', $GLOBALS['STR_ADMIN_ECOTAX']);
	$tpl->assign('STR_PRICE', $GLOBALS['STR_PRICE']);
	$tpl->assign('STR_HT', $GLOBALS['STR_HT']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_ADMIN_ECOTAXES_MODIFY_ECOTAX', $GLOBALS['STR_ADMIN_ECOTAXES_MODIFY_ECOTAX']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	echo $tpl->fetch();
}

?>
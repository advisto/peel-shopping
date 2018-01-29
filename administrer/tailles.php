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
// $Id: tailles.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_TAILLES_TITRE'];

$output = '';
$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		$output .= affiche_formulaire_ajout_taille($frm);
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_taille($_GET['id'], $frm);
		break;

	case "suppr" :
		$output .= supprime_taille($_GET['id']);
		$output .= affiche_liste_taille();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= insere_taille($_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_TAILLES_MSG_CREATED_OK'], vb($_POST['nom_' . $_SESSION["session_langue"]]))))->fetch();
			$output .= affiche_liste_taille();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_ajout_taille($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= maj_taille($_POST['id'], $_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_TAILLES_MSG_UPDATED_OK'], vn($_POST['id']))))->fetch();
			$output .= affiche_liste_taille();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_modif_taille($_GET['id'], $frm);
		}
		break;

	default :
		$output .= affiche_liste_taille();
		break;
}
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un taille
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_taille(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
		}
		$frm['position'] = 0;
		$frm['prix'] = "";
		$frm['poids'] = "";
		$frm['signe'] = "";
		$frm['prix_revendeur'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_TAILLES_CREATE'];

	return affiche_formulaire_taille($frm);
}

/**
 * Affiche le formulaire de modification pour le taille sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_taille($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_tailles
			WHERE id = " . intval($id) . " AND " .  get_filter_site_cond('tailles', null, true));
		if ($frm = fetch_assoc($qid)) {
		} else {
			return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_TAILLES_NOT_FOUND']))->fetch();
		}
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	return affiche_formulaire_taille($frm);
}

/**
 * affiche_formulaire_taille()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_taille(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_taille.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', vb($frm['nouveau_mode']));
	$tpl->assign('id', intval(vb($frm['id'])));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => $frm['nom_' . $lng]
			);
	}
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('poids', $frm['poids']);
	$tpl->assign('prix', str_replace("-", "", $frm["prix"]));
	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
	$tpl->assign('prix_revendeur', str_replace("-", "", $frm["prix_revendeur"]));
	$tpl->assign('signe', $frm['signe']);
	$tpl->assign('position', $frm['position']);
	$tpl->assign('titre_bouton', $frm['titre_bouton']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_TAILLES_FORM_TITLE', $GLOBALS['STR_ADMIN_TAILLES_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_TAILLES_OVERWEIGHT', $GLOBALS['STR_ADMIN_TAILLES_OVERWEIGHT']);
	$tpl->assign('STR_ADMIN_TAILLES_OVERCOST', $GLOBALS['STR_ADMIN_TAILLES_OVERCOST']);
	$tpl->assign('STR_ADMIN_TAILLES_OVERCOST_RESELLER', $GLOBALS['STR_ADMIN_TAILLES_OVERCOST_RESELLER']);
	$tpl->assign('STR_ADMIN_TAILLES_SIGN', $GLOBALS['STR_ADMIN_TAILLES_SIGN']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_GRAMS', $GLOBALS['STR_ADMIN_GRAMS']);
	return $tpl->fetch();
}

/**
 * supprime_taille()
 *
 * @param integer $id
 * @return
 */
function supprime_taille($id)
{
	/* Supprime le taille spécifié par $id. */

	$qid = query("SELECT nom_" . $_SESSION['session_langue'] . "
		FROM peel_tailles
		WHERE id = " . intval($id) . " AND " .  get_filter_site_cond('tailles', null, true));

	$col = fetch_assoc($qid);

	/* Efface le taille */
	query("DELETE FROM peel_tailles WHERE id=" . intval($id) . " AND " . get_filter_site_cond('tailles', null, true));
	/* Efface ce taille de la table produits_taille */
	query("DELETE FROM peel_produits_tailles WHERE taille_id=" . intval($id));

	/* Supprime le stock correspondant */
	if (check_if_module_active('stock_advanced')) {
		query("DELETE FROM peel_stocks WHERE taille_id = '" . intval($id) . "'");
	}
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_TAILLES_MSG_DELETED_OK'], $col['nom_' . $_SESSION['session_langue']])))->fetch();
}

/**
 * Ajoute le taille dans la table taille
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_taille(&$frm)
{
	$sql = "INSERT INTO peel_tailles (
		poids
		, position
		, prix
		, prix_revendeur
		, signe
		, site_id
		";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng;
	}
	$sql .= "
	) VALUES (
		'" . nohtml_real_escape_string($frm['poids']) . "'
		, '" . intval($frm['position']) . "'
		, '" . nohtml_real_escape_string($frm['signe'] . $frm['prix']) . "'
		, '" . nohtml_real_escape_string($frm['signe'] . $frm['prix_revendeur']) . "'
		,'" . nohtml_real_escape_string($frm['signe']) . "'
		,'" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= ")";

	query($sql);
}

/**
 * Met à jour le taille $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_taille($id, &$frm)
{
	$prix = $frm['signe'] . $frm['prix'];
	$prix_revendeur = $frm['signe'] . $frm['prix_revendeur'];

	/* Met à jour la table taille */
	$sql = "UPDATE peel_tailles
		SET	poids = '" . nohtml_real_escape_string($frm['poids']) . "'
			, position = '" . intval($frm['position']) . "'
			, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . " = '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= "	, prix = '" . nohtml_real_escape_string($prix) . "', prix_revendeur = '" . nohtml_real_escape_string($prix_revendeur) . "', signe ='" . nohtml_real_escape_string($frm['signe']) . "'
	WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('tailles', null, true);

	query($sql);
}

/**
 * affiche_liste_taille()
 *
 * @return
 */
function affiche_liste_taille()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_taille.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$query = query("SELECT t.*
		FROM peel_tailles t
		WHERE " .  get_filter_site_cond('tailles', 't', true) . "
		ORDER BY t.position ASC, t.prix ASC");
	if (!(num_rows($query) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, null, null, 'sortable_'.$ligne['id']),
				'nom' => (!empty($ligne['nom_' . $_SESSION['session_langue']])?$ligne['nom_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'modif_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'prix' => ($ligne['prix'] != 0 ? fprix($ligne['prix'], true, $GLOBALS['site_parameters']['code'], false) : "n.a"),
				'prix_revendeur' => ($ligne['prix_revendeur'] != 0 ? fprix($ligne['prix_revendeur'], true, $GLOBALS['site_parameters']['code'], false) : "n.a"),
				'position' => $ligne['position'],
				'site_name' => get_site_name($ligne['site_id'])
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$GLOBALS['sortable_rpc'] = 'rpc_positions.php?mode=tailles';
	$tpl->assign('STR_ADMIN_TAILLES_TITRE', $GLOBALS['STR_ADMIN_TAILLES_TITRE']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_TAILLES_LIST_EXPLAIN', $GLOBALS['STR_ADMIN_TAILLES_LIST_EXPLAIN']);
	$tpl->assign('STR_ADMIN_TAILLES_CREATE', $GLOBALS['STR_ADMIN_TAILLES_CREATE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_SIZE', $GLOBALS['STR_SIZE']);
	$tpl->assign('STR_PRICE', $GLOBALS['STR_PRICE']);
	$tpl->assign('STR_ADMIN_RESELLER_PRICE', $GLOBALS['STR_ADMIN_RESELLER_PRICE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_TAILLES_UPDATE', $GLOBALS['STR_ADMIN_TAILLES_UPDATE']);
	$tpl->assign('STR_ADMIN_TAILLES_NOTHING_FOUND', $GLOBALS['STR_ADMIN_TAILLES_NOTHING_FOUND']);
	return $tpl->fetch();
}


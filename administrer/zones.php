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
// $Id: zones.php 37040 2013-05-30 13:17:16Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$DOC_TITLE = $GLOBALS['STR_ADMIN_ZONES_TITLE'];
include("modeles/haut.php");

$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_zone($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_zone($_GET['id'], $frm);
		break;

	case "suppr" :
		supprime_zone($_GET['id']);
		affiche_liste_zone();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_zone($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ZONES_MSG_CREATED_OK'], vb($_POST['nom_' . $_SESSION["session_langue"]]))))->fetch();
			affiche_liste_zone();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_zone($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_zone($_POST['id'], $_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ZONES_MSG_UPDATED_OK'], vn($_POST['id']))))->fetch();
			affiche_liste_zone();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
				affiche_liste_zone();
			} else {
				affiche_formulaire_modif_zone(vn($_GET['id']), $frm);
			}
		}
		break;

	default :
		affiche_liste_zone();
		break;
}

include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter une zone
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_zone(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
		}
		$frm['tva'] = "";
		$frm['on_franco'] = "";
		$frm['on_franco_amount'] = "";
		$frm['on_franco_nb_products'] = "";
		$frm['position'] = "";
		$frm['technical_code'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_ZONES_CREATE'];

	affiche_formulaire_zone($frm);
}

/**
 * Affiche le formulaire de modification pour la zone sélectionnée
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_zone($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations de la zone */
		$qid = query("SELECT *
			FROM peel_zones
			WHERE id = " . intval($id) . "");
		if ($frm = fetch_assoc($qid)) {
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ZONES_NOT_FOUND']))->fetch();
			return false;
		}
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
	affiche_formulaire_zone($frm);
}

/**
 * affiche_formulaire_zone()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_zone(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_zone.tpl');
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
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('tva', $frm['tva']);
	$tpl->assign('on_franco', $frm['on_franco']);
	$tpl->assign('on_franco_amount', $frm['on_franco_amount']);
	$tpl->assign('on_franco_nb_products', $frm['on_franco_nb_products']);
	$tpl->assign('position', $frm['position']);
	$tpl->assign('is_fianet_module_active', is_fianet_module_active());
	$tpl->assign('technical_code', $frm['technical_code']);
	$tpl->assign('titre_bouton', $frm['titre_bouton']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_ZONES_FORM_TITLE', $GLOBALS['STR_ADMIN_ZONES_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_ZONES_DOES_VAT_APPLY_IN_ZONE', $GLOBALS['STR_ADMIN_ZONES_DOES_VAT_APPLY_IN_ZONE']);
	$tpl->assign('STR_ADMIN_ZONES_DELIVERY_COSTS_IN_ZONE', $GLOBALS['STR_ADMIN_ZONES_DELIVERY_COSTS_IN_ZONE']);
	$tpl->assign('STR_ADMIN_ZONES_DELIVERY_COSTS_EXPLAIN', $GLOBALS['STR_ADMIN_ZONES_DELIVERY_COSTS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_ZONES_FRANCO_LIMIT_AMOUNT', $GLOBALS['STR_ADMIN_ZONES_FRANCO_LIMIT_AMOUNT']);
	$tpl->assign('STR_ADMIN_ZONES_FRANCO_LIMIT_AMOUNT_EXPLAIN', $GLOBALS['STR_ADMIN_ZONES_FRANCO_LIMIT_AMOUNT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_ZONES_FRANCO_LIMIT_PRODUCTS', $GLOBALS['STR_ADMIN_ZONES_FRANCO_LIMIT_PRODUCTS']);
	$tpl->assign('STR_ADMIN_ZONES_FRANCO_LIMIT_PRODUCTS_EXPLAIN', $GLOBALS['STR_ADMIN_ZONES_FRANCO_LIMIT_PRODUCTS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_ZONES_TECHNICAL_CODE_EXPLAIN', $GLOBALS['STR_ADMIN_ZONES_TECHNICAL_CODE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	echo $tpl->fetch();
}

/**
 * Supprime la zone spécifiée par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_zone($id)
{
	$qid = query("SELECT nom_" . $_SESSION['session_langue'] . "
		FROM peel_zones
		WHERE id=" . intval($id) . "");
	$col = fetch_assoc($qid);

	/* Efface la zone */
	query("DELETE FROM peel_zones WHERE id=" . intval($id));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ZONES_MSG_DELETED_OK'], $col['nom_' . $_SESSION['session_langue']])))->fetch();
}

/**
 * Ajoute la zone dans la table zone
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_zone($frm)
{
	$sql = "INSERT INTO peel_zones (
		tva
		, position
		, on_franco
		, on_franco_amount
		, on_franco_nb_products";
	if (is_fianet_module_active()) {
		$sql .= ", technical_code";
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng;
	}
	$sql .= "
	) VALUES (
		'" . nohtml_real_escape_string(vn($frm['tva'])) . "'
		, '" . intval($frm['position']) . "'
		, '" . intval(vn($frm['on_franco'])) . "'
		, '" . nohtml_real_escape_string(vn($frm['on_franco_amount'])) . "'
		, '" . nohtml_real_escape_string(vn($frm['on_franco_nb_products'])) . "'";
	if (is_fianet_module_active()) {
		$sql .= ", '" . nohtml_real_escape_string(vb($frm['technical_code'])) . "'";
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= ")";

	query($sql);
}

/**
 * Met à jour la zone $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_zone($id, $frm)
{
	$sql = "UPDATE peel_zones
		SET tva = '" . nohtml_real_escape_string(vn($frm['tva'])) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . " = '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	if (is_fianet_module_active()) {
		$sql .= ", technical_code = '" . nohtml_real_escape_string($frm['technical_code']) . "'";
	}
	$sql .= ", position = '" . nohtml_real_escape_string($frm['position']) . "'
		, on_franco_amount = '" . nohtml_real_escape_string($frm['on_franco_amount']) . "'
		, on_franco_nb_products = '" . nohtml_real_escape_string($frm['on_franco_nb_products']) . "', on_franco = '" . intval(vn($frm['on_franco'])) . "'
		WHERE id = '" . intval($id) . "'";
	query($sql);
}

/**
 * affiche_liste_zone()
 *
 * @return
 */
function affiche_liste_zone()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_zone.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$result = query("SELECT *
		FROM peel_zones z
		ORDER BY position");
	if (!(num_rows($result) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($result)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'nom' => (!empty($ligne['nom_' . $_SESSION['session_langue']])?$ligne['nom_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'modif_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'tva' => $ligne['tva'],
				'on_franco' => $ligne['on_franco'],
				'position' => $ligne['position']
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_ZONES_TITLE', $GLOBALS['STR_ADMIN_ZONES_TITLE']);
	$tpl->assign('STR_ADMIN_ZONES_CREATE', $GLOBALS['STR_ADMIN_ZONES_CREATE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_SHIPPING_ZONE', $GLOBALS['STR_SHIPPING_ZONE']);
	$tpl->assign('STR_VAT', $GLOBALS['STR_VAT']);
	$tpl->assign('STR_ADMIN_ZONES_FREE_DELIVERY', $GLOBALS['STR_ADMIN_ZONES_FREE_DELIVERY']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_ZONES_UPDATE', $GLOBALS['STR_ADMIN_ZONES_UPDATE']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	$tpl->assign('STR_ADMIN_ZONES_NOTHING_FOUND', $GLOBALS['STR_ADMIN_ZONES_NOTHING_FOUND']);
	echo $tpl->fetch();
}

?>
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
// $Id: tarifs.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage,admin_finance");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_TARIFS_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_tarif($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_tarif($_GET['id'], $frm);
		break;

	case "suppr" :
		supprime_tarif($_GET['id']);
		affiche_liste_tarif();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_tarif($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_TARIFS_MSG_CREATED_OK'], vb($_POST['tarif']))))->fetch();
			affiche_liste_tarif();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_tarif($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_tarif($_POST['id'], $_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_TARIFS_MSG_UPDATED_OK'], vn($_POST['id']))))->fetch();
			affiche_liste_tarif();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_tarif($frm['id'], $frm);
		}
		break;

	default :
		affiche_liste_tarif();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un tarif
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_tarif(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm['zone'] = "";
		$frm['type'] = "";
		$frm['poidsmin'] = "";
		$frm['poidsmax'] = "";
		$frm['totalmin'] = "";
		$frm['tarif'] = "";
		$frm["totalmax"] = "";
		$frm['tva'] = 0;
		$frm['site_id'] = '';
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_TARIFS_CREATE'];

	affiche_formulaire_tarif($frm);
}

/**
 * Affiche le formulaire de modification pour le tarif sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_tarif($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_tarifs
			WHERE id =" . intval($id) . " AND " . get_filter_site_cond('tarifs', null, true) . "");
		if ($frm = fetch_assoc($qid)) {
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_TARIFS_NOT_FOUND']))->fetch();
			return false;
		}
	}
	$frm['id'] = $id;

	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_tarif($frm);
}

/**
 * affiche_formulaire_tarif()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_tarif(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_tarif.tpl');
	$tpl->assign('mode_transport', $GLOBALS['site_parameters']['mode_transport']);
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', vb($frm['nouveau_mode']));
	$tpl->assign('id', intval(vn($frm['id'])));
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));

	$tpl_zones_options = array();
	$sql_zone = "SELECT id, nom_" . $_SESSION['session_langue'] . "
		FROM peel_zones
		WHERE " . get_filter_site_cond('zones') . "
		ORDER BY nom_" . $_SESSION['session_langue'];
	$res_zone = query($sql_zone);
	while ($tab_zone = fetch_assoc($res_zone)) {
		$tpl_zones_options[] = array('value' => intval($tab_zone['id']),
			'issel' => vb($frm['zone']) == $tab_zone['id'],
			'name' => $tab_zone['nom_' . $_SESSION['session_langue']]
			);
	}
	$tpl->assign('zones_options', $tpl_zones_options);

	$tpl_type_options = array();
	$sql_type = "SELECT id, nom_" . $_SESSION['session_langue'] . "
		FROM peel_types
		WHERE " . get_filter_site_cond('types') . "
		ORDER BY nom_" . $_SESSION['session_langue'];
	$res_type = query($sql_type);
	while ($tab_type = fetch_assoc($res_type)) {
		$tpl_type_options[] = array('value' => intval($tab_type['id']),
			'issel' => vb($frm['type']) == $tab_type['id'],
			'name' => $tab_type['nom_' . $_SESSION['session_langue']]
			);
	}
	$tpl->assign('type_options', $tpl_type_options);
	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
	$tpl->assign('poidsmin', $frm['poidsmin']);
	$tpl->assign('poidsmax', $frm['poidsmax']);
	$tpl->assign('totalmin', $frm['totalmin']);
	$tpl->assign('totalmax', $frm['totalmax']);
	$tpl->assign('tarif', $frm['tarif']);
	$tpl->assign('vat_select_options', get_vat_select_options(vb($frm['tva'])));
	$tpl->assign('titre_bouton', $frm['titre_bouton']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_TARIFS_CONFIG_STATUS', $GLOBALS['STR_ADMIN_TARIFS_CONFIG_STATUS']);
	$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
	$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
	$tpl->assign('STR_ADMIN_TARIFS_CONFIG_DEACTIVATED_COMMENT', $GLOBALS['STR_ADMIN_TARIFS_CONFIG_DEACTIVATED_COMMENT']);
	$tpl->assign('STR_ADMIN_TARIFS_FORM_TITLE', $GLOBALS['STR_ADMIN_TARIFS_FORM_TITLE']);
	$tpl->assign('STR_SHIPPING_ZONE', $GLOBALS['STR_SHIPPING_ZONE']);
	$tpl->assign('STR_SHIPPING_TYPE', $GLOBALS['STR_SHIPPING_TYPE']);
	$tpl->assign('STR_ADMIN_TARIFS_MINIMAL_WEIGHT', $GLOBALS['STR_ADMIN_TARIFS_MINIMAL_WEIGHT']);
	$tpl->assign('STR_ADMIN_TARIFS_MAXIMAL_WEIGHT', $GLOBALS['STR_ADMIN_TARIFS_MAXIMAL_WEIGHT']);
	$tpl->assign('STR_ADMIN_TARIFS_MINIMAL_TOTAL', $GLOBALS['STR_ADMIN_TARIFS_MINIMAL_TOTAL']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_ADMIN_TARIFS_MAXIMAL_TOTAL', $GLOBALS['STR_ADMIN_TARIFS_MAXIMAL_TOTAL']);
	$tpl->assign('STR_ADMIN_TARIF', $GLOBALS['STR_ADMIN_TARIF']);
	$tpl->assign('STR_ADMIN_VAT_PERCENTAGE', $GLOBALS['STR_ADMIN_VAT_PERCENTAGE']);
	echo $tpl->fetch();
}

/**
 * Supprime le tarif spécifié par $id
 *
 * @param integer $id
 * @return
 */
function supprime_tarif($id)
{
	/* Efface le tarif */
	query("DELETE FROM peel_tarifs WHERE id=" . intval($id) . " AND " . get_filter_site_cond('tarifs', null, true) . "");
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_TARIFS_MSG_DELETED_OK']))->fetch();
}

/**
 * Ajoute le tarif dans la table tarif
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_tarif($frm)
{
	$qid = query("INSERT INTO peel_tarifs (
		zone
		, type
		, poidsmin
		, poidsmax
		, totalmin
		, totalmax
		, tarif
		, tva
		, site_id
	) VALUES (
		'" . nohtml_real_escape_string($frm['zone']) . "'
		,'" . nohtml_real_escape_string($frm['type']) . "'
		,'" . nohtml_real_escape_string($frm['poidsmin']) . "'
		,'" . nohtml_real_escape_string($frm['poidsmax']) . "'
		,'" . nohtml_real_escape_string($frm['totalmin']) . "'
		,'" . nohtml_real_escape_string($frm['totalmax']) . "'
		,'" . nohtml_real_escape_string($frm['tarif']) . "'
		,'" . nohtml_real_escape_string($frm['tva']) . "'
		,'" . real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
	)");
}

/**
 * Met à jour le tarif $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_tarif($id, $frm)
{
	query("UPDATE peel_tarifs SET
		zone = '" . nohtml_real_escape_string($frm['zone']) . "'
		,type = '" . nohtml_real_escape_string($frm['type']) . "'
		,poidsmin = '" . nohtml_real_escape_string($frm['poidsmin']) . "'
		,poidsmax = '" . nohtml_real_escape_string($frm['poidsmax']) . "'
		,totalmin = '" . nohtml_real_escape_string($frm['totalmin']) . "'
		,totalmax = '" . nohtml_real_escape_string($frm['totalmax']) . "'
		,tarif = '" . nohtml_real_escape_string($frm['tarif']) . "'
		,tva = '" . nohtml_real_escape_string($frm['tva']) . "'
		,site_id = '" . real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
	WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('tarifs', null, true) . "");
}

/**
 * affiche_liste_tarif()
 *
 * @return
 */
function affiche_liste_tarif()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_tarif.tpl');
	$tpl->assign('mode_transport', $GLOBALS['site_parameters']['mode_transport']);
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);

	$query = query("SELECT t.*, z.nom_" . $_SESSION['session_langue'] . " AS zone_name
		FROM peel_tarifs t
		LEFT JOIN peel_zones z ON z.id=t.zone AND " . get_filter_site_cond('zones', 'z') . "
		WHERE " . get_filter_site_cond('tarifs', 't', true) . "
		ORDER BY zone_name ASC, t.type ASC, t.tarif ASC");
	if (!(num_rows($query) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'poidsmin' => $ligne['poidsmin'],
				'poidsmax' => $ligne['poidsmax'],
				'tarif' => $ligne['tarif'],
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'modif_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'zone_name' => vb($ligne['zone_name']),
				'delivery_type_name' => get_delivery_type_name($ligne['type']),
				'totalmin' => $ligne['totalmin'],
				'totalmax' => $ligne['totalmax'],
				'site_name' => get_site_name($ligne['site_id'])
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_TARIFS_TITLE', $GLOBALS['STR_ADMIN_TARIFS_TITLE']);
	$tpl->assign('STR_ADMIN_TARIFS_CONFIG_STATUS', $GLOBALS['STR_ADMIN_TARIFS_CONFIG_STATUS']);
	$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
	$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
	$tpl->assign('STR_ADMIN_TARIFS_CONFIG_DEACTIVATED_COMMENT', $GLOBALS['STR_ADMIN_TARIFS_CONFIG_DEACTIVATED_COMMENT']);
	$tpl->assign('STR_ADMIN_TARIFS_CREATE', $GLOBALS['STR_ADMIN_TARIFS_CREATE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_SHIPPING_ZONE', $GLOBALS['STR_SHIPPING_ZONE']);
	$tpl->assign('STR_SHIPPING_TYPE', $GLOBALS['STR_SHIPPING_TYPE']);
	$tpl->assign('STR_ADMIN_TARIFS_MINIMAL_WEIGHT_SHORT', $GLOBALS['STR_ADMIN_TARIFS_MINIMAL_WEIGHT_SHORT']);
	$tpl->assign('STR_ADMIN_TARIFS_MAXIMAL_WEIGHT_SHORT', $GLOBALS['STR_ADMIN_TARIFS_MAXIMAL_WEIGHT_SHORT']);
	$tpl->assign('STR_ADMIN_TARIFS_MINIMAL_TOTAL_SHORT', $GLOBALS['STR_ADMIN_TARIFS_MINIMAL_TOTAL_SHORT']);
	$tpl->assign('STR_ADMIN_TARIFS_MAXIMAL_TOTAL_SHORT', $GLOBALS['STR_ADMIN_TARIFS_MAXIMAL_TOTAL_SHORT']);
	$tpl->assign('STR_ADMIN_TARIFS_TARIFS', $GLOBALS['STR_ADMIN_TARIFS_TARIFS']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_GRAMS_SHORT', $GLOBALS['STR_ADMIN_GRAMS_SHORT']);
	$tpl->assign('STR_ADMIN_TARIFS_UPDATE', $GLOBALS['STR_ADMIN_TARIFS_UPDATE']);
	$tpl->assign('STR_ADMIN_TARIFS_NOTHING_FOUND', $GLOBALS['STR_ADMIN_TARIFS_NOTHING_FOUND']);
	$tpl->assign('STR_ADMIN_TARIFS_SETUP_FREE_EXPLAIN', $GLOBALS['STR_ADMIN_TARIFS_SETUP_FREE_EXPLAIN']);
	
	echo $tpl->fetch();
}


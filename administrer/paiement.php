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
// $Id: paiement.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage,admin_finance");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_PAIEMENT_TITLE'];

$output = '';
$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		$output .= affiche_formulaire_ajout_paiement($frm);
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_paiement($_GET['id'], $frm);
		break;

	case "suppr" :
		$output .= supprime_paiement($_GET['id']);
		$output .= affiche_liste_paiement();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= insere_paiement($_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PAIEMENT_PAYMENT_MEAN_CREATED'], vb($_POST['nom_' . $_SESSION["session_langue"]]))))->fetch();
			$output .= affiche_liste_paiement();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_ajout_paiement($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= maj_paiement($_POST['id'], $_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PAIEMENT_MSG_UPDATED_OK'], vn($_POST['id']))))->fetch();
			$output .= affiche_liste_paiement();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_modif_paiement($_GET['id'], $frm);
		}
		break;

	default :
		$output .= affiche_liste_paiement();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un paiement
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_paiement(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
		}
		$frm['position'] = "";
		$frm['tarif'] = 0;
		$frm['tarif_percent'] = 0;
		$frm['tva'] = 0;
		$frm['technical_code'] = '';
		$frm['retour_possible'] = 1;
		$frm['totalmin'] = 0;
		$frm['totalmax'] = 0;
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_PAIEMENT_ADD_PAYMENT_MEAN'];
	return affiche_formulaire_paiement($frm);
}

/**
 * Affiche le formulaire de modification pour le paiement sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_paiement($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du moyen de paiement */
		$qid = query("SELECT *
			FROM peel_paiement
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('paiement', null, true));
		if ($frm = fetch_assoc($qid)) {
		} else {
			return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_PAIEMENT_PAYMENT_MEAN_NOT_FOUND']))->fetch();
		}
	}
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	return affiche_formulaire_paiement($frm);
}

/**
 * affiche_formulaire_paiement()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_paiement(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_paiement.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('etat', vb($frm["etat"]));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => vb($frm['nom_' . $lng]),
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('technical_code', vb($frm["technical_code"]));
	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
	$tpl->assign('tarif', vb($frm["tarif"]));
	$tpl->assign('tarif_percent', vb($frm["tarif_percent"]));
	$tpl->assign('totalmin', vb($frm["totalmin"]));
	$tpl->assign('totalmax', vb($frm["totalmax"]));
	$tpl->assign('tva', get_vat_select_options(vb($frm['tva'])));
	$tpl->assign('position', vb($frm["position"]));
	$tpl->assign('is_payback_module_active', check_if_module_active('payback'));
	if (check_if_module_active('payback')) {
		$tpl->assign('is_retour_possible1', ($frm["retour_possible"] || !isset($frm["retour_possible"])));
		$tpl->assign('is_retour_possible0', (isset($frm["retour_possible"]) && !$frm["retour_possible"]));
	}
	
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('titre_bouton', vb($frm["titre_bouton"]));
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_PAIEMENT_FORM_TITLE', $GLOBALS['STR_ADMIN_PAIEMENT_FORM_TITLE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_ADMIN_PAIEMENT_WARNING', $GLOBALS['STR_ADMIN_PAIEMENT_WARNING']);
	$tpl->assign('STR_ADMIN_PAIEMENT_ORDER_OVERCOST', $GLOBALS['STR_ADMIN_PAIEMENT_ORDER_OVERCOST']);
	$tpl->assign('STR_VAT', $GLOBALS['STR_VAT']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_PAIEMENT_ALLOW_REIMBURSMENTS', $GLOBALS['STR_ADMIN_PAIEMENT_ALLOW_REIMBURSMENTS']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	$tpl->assign('STR_ADMIN_PAIEMENT_TECHNICAL_CODE_DEFAULT_EXPLAIN', $GLOBALS['STR_ADMIN_PAIEMENT_TECHNICAL_CODE_DEFAULT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_TARIFS_MINIMAL_TOTAL', $GLOBALS['STR_ADMIN_TARIFS_MINIMAL_TOTAL']);
	$tpl->assign('STR_ADMIN_TARIFS_MAXIMAL_TOTAL', $GLOBALS['STR_ADMIN_TARIFS_MAXIMAL_TOTAL']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	return $tpl->fetch();
}

/**
 * Supprime le paiement spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_paiement($id)
{
	$qid = query("SELECT *
		FROM peel_paiement
		WHERE id = " . intval($id) . " AND " . get_filter_site_cond('paiement', null, true));
	$p = fetch_assoc($qid);

	/* Efface le paiement */
	$qid = query("DELETE FROM peel_paiement 
		WHERE id=" . intval($id) . " AND " . get_filter_site_cond('paiement', null, true));
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PAIEMENT_MSG_DELETED_OK'], $p['nom_' . $_SESSION['session_langue']])))->fetch();
}

/**
 * Ajoute le paiement dans la table paiement
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_paiement(&$frm)
{
	$sql = "INSERT INTO peel_paiement (
		position
		, technical_code
		, tva
		, etat
		, site_id";
	if (check_if_module_active('payback')) {
		$sql .= ", retour_possible";
	}
	$sql .= ", tarif";
	$sql .= ", tarif_percent";
	$sql .= ", totalmin";
	$sql .= ", totalmax";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {		$sql .= ", nom_" . $lng;
	}
	$sql .= "
	) VALUES (
		'" . intval($frm['position']) . "'
		, '" . nohtml_real_escape_string($frm['technical_code']) . "'
		, '" . nohtml_real_escape_string($frm['tva']) . "'
		, '" . intval($frm['etat']) . "'
		, '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	if (check_if_module_active('payback')) {
		$sql .= ", '" . intval($frm['retour_possible']) . "'";
	}
	$sql .= "
		,'" . nohtml_real_escape_string($frm['tarif']) . "'
		,'" . nohtml_real_escape_string($frm['tarif_percent']) . "'
		,'" . nohtml_real_escape_string($frm['totalmin']) . "'
		,'" . nohtml_real_escape_string($frm['totalmax']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= ")";

	query($sql);
}

/**
 * Met à jour le paiement $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_paiement($id, $frm)
{
	$sql = "UPDATE peel_paiement
		SET position = '" . intval($frm['position']) . "'
			, technical_code = '" . nohtml_real_escape_string($frm['technical_code']) . "'
			, tva = '" . nohtml_real_escape_string($frm['tva']) . "'";
	if (check_if_module_active('payback')) {
		$sql .= ", retour_possible = '" . nohtml_real_escape_string($frm['retour_possible']) . "'";
	}
	$sql .= ", etat = '" . nohtml_real_escape_string($frm['etat']) . "'
			, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . " = '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}

	$sql .= ", tarif = '" . nohtml_real_escape_string($frm['tarif']) . "'";
	$sql .= ", totalmin = '" . nohtml_real_escape_string($frm['totalmin']) . "'";
	$sql .= ", totalmax = '" . nohtml_real_escape_string($frm['totalmax']) . "'";
	$sql .= ", tarif_percent = '" . nohtml_real_escape_string($frm['tarif_percent']) . "'
		WHERE id = '" . intval($id) . "'";

	query($sql);
}

/**
 * affiche_liste_paiement()
 *
 * @return
 */
function affiche_liste_paiement()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_paiement.tpl');
	$tpl->assign('sites_href', $GLOBALS['administrer_url'] . '/sites.php');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$sql = "SELECT p.*
		FROM peel_paiement p
		WHERE " . get_filter_site_cond('paiement', 'p', true) . " 
		ORDER BY p.position";
	$query = query($sql);
	if (!(num_rows($query) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			if ($ligne['technical_code'] == 'paypal' && empty($GLOBALS['site_parameters']['email_paypal'])) {
				$explain = '<br /><span class="red">' . StringMb::strtoupper($GLOBALS["STR_ADMIN_DEACTIVATED"]) . $GLOBALS["STR_BEFORE_TWO_POINTS"] . ': <a href="'.$GLOBALS['administrer_url'].'/sites.php" style="color:#999999">' . $GLOBALS["STR_ADMIN_SITES_PAYPAL_EMAIL"].'</a></span>';
			}elseif ($ligne['technical_code'] == 'moneybookers' && empty($GLOBALS['site_parameters']['email_moneybookers'])) {
				$explain = '<br /><span class="red">' . StringMb::strtoupper($GLOBALS["STR_ADMIN_DEACTIVATED"]) . $GLOBALS["STR_BEFORE_TWO_POINTS"] . ': <a href="'.$GLOBALS['administrer_url'].'/sites.php" style="color:#999999">' . $GLOBALS["STR_ADMIN_SITES_MONEYBOOKERS_EMAIL"].'</a></span>';
			}else{
				$explain = '';
			}
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, null, null, 'sortable_'.$ligne['id']),
				'nom' => (!empty($ligne['nom_' . $_SESSION['session_langue']])?$ligne['nom_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'explain' => $explain,
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'technical_code' => $ligne['technical_code'],
				'position' => $ligne['position'],
				'prix' => ($ligne['tarif'] != "0.00000" ? fprix($ligne['tarif'], true, $GLOBALS['site_parameters']['code'], false) . "" : "-"),
				'etat_onclick' => 'change_status("paiement", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				'site_name' => get_site_name($ligne['site_id'])
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$GLOBALS['sortable_rpc'] = 'rpc_positions.php?mode=paiement';
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_PAIEMENT_TITLE', $GLOBALS['STR_ADMIN_PAIEMENT_TITLE']);
	$tpl->assign('STR_ADMIN_PAIEMENT_EXPLAIN', $GLOBALS['STR_ADMIN_PAIEMENT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_NOTA_BENE', $GLOBALS['STR_ADMIN_NOTA_BENE']);
	$tpl->assign('STR_ADMIN_PAIEMENT_TECHNICAL_CODE_EXPLAIN', $GLOBALS['STR_ADMIN_PAIEMENT_TECHNICAL_CODE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_PAIEMENT_ADD_PAYMENT_MEAN', $GLOBALS['STR_ADMIN_PAIEMENT_ADD_PAYMENT_MEAN']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_ADMIN_PAIEMENT_PAYMENT_MEAN', $GLOBALS['STR_ADMIN_PAIEMENT_PAYMENT_MEAN']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_PAIEMENT_ORDER_OVERCOST', $GLOBALS['STR_ADMIN_PAIEMENT_ORDER_OVERCOST']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_PAIEMENT_UPDATE', $GLOBALS['STR_ADMIN_PAIEMENT_UPDATE']);
	$tpl->assign('STR_ADMIN_PAIEMENT_NO_PAYMENT_MEAN_FOUND', $GLOBALS['STR_ADMIN_PAIEMENT_NO_PAYMENT_MEAN_FOUND']);
	$tpl->assign('STR_ADMIN_TARIFS_MINIMAL_TOTAL', $GLOBALS['STR_ADMIN_TARIFS_MINIMAL_TOTAL']);
	$tpl->assign('STR_ADMIN_TARIFS_MAXIMAL_TOTAL', $GLOBALS['STR_ADMIN_TARIFS_MAXIMAL_TOTAL']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	return $tpl->fetch();
}


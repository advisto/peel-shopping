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
// $Id: codes_promos.php 35064 2013-02-08 14:16:40Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales,admin_users");

$DOC_TITLE = $GLOBALS['STR_ADMIN_CODES_PROMOS_TITLE'];
include("modeles/haut.php");

$mode = vb($_REQUEST['mode']);
$frm = $_POST;
$form_error_object = new FormError();

switch ($mode) {
	case "ajout" :
		affiche_formulaire_ajout_code_promo($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_code_promo(intval($_GET['id']), $frm);
		break;

	case "suppr" :
		supprime_code_promo(intval($_REQUEST['id']));
		affiche_liste_code_promo();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$frm['source'] = 'ADM';
			$frm['on_check'] = '0';
			if (insere_code_promo($frm)) {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CODES_PROMOS_MSG_CREATED_OK'], String::strtoupper(vb($_POST['nom'])))))->fetch();
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CODES_PROMOS_ERR_ALREADY_EXISTS'], String::strtoupper(vb($_POST['nom'])))))->fetch();
			}
			affiche_liste_code_promo();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_code_promo($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_code_promo($_POST['id'], $_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CODES_PROMOS_MSG_MODIFY_OK'], String::strtoupper(vb($_POST['nom'])))))->fetch();
			affiche_liste_code_promo();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_code_promo($_REQUEST['id'], $frm);
		}
		break;

	case "code_pour_client" :
		affiche_liste_code_pour_client(intval($_REQUEST['id_utilisateur']));
		break;

	case "envoi_client" :
		if (!verify_token($_SERVER['PHP_SELF'] . 'envoi_client' . $_POST['id_utilisateur'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			if (empty($_REQUEST['code_promo_id'])) {
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_CODES_PROMOS_ERR_NO_CODE_SELECTED']))->fetch();
			} elseif (empty($_REQUEST['id_utilisateur'])) {
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_CODES_PROMOS_ERR_NO_USER_SELECTED']))->fetch();
			} else {
				echo envoie_client_code_promo(intval($_REQUEST['id_utilisateur']), intval($_REQUEST['code_promo_id']));
			}
			affiche_liste_code_promo();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_liste_code_pour_client(intval($_REQUEST['id_utilisateur']));
		}
		break;

	default :
		affiche_liste_code_promo();
		break;
}

include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * affiche_formulaire_ajout_code_promo()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_code_promo(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm["remise_percent"] = "";
		$frm["remise_valeur"] = "";
		$frm["montant_min"] = "";
		$frm["etat"] = "";
		$frm["on_type"] = vb($_GET['on_type']);
		$frm["nom"] = "";
	}
	$frm["nouveau_mode"] = "insere";
	$frm["date_debut"] = get_formatted_date();
	$frm["date_fin"] = get_formatted_date(time() + 7 * 24 * 3600);
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_CODES_PROMOS_CREATE'];
	affiche_formulaire_code_promo($frm);
}

/**
 * affiche_formulaire_modif_code_promo()
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_code_promo($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		// Charge les informations du code promo
		$qid = query("SELECT *
			FROM peel_codes_promos
			WHERE id = '" . intval($id) . "'");
		if ($frm = fetch_assoc($qid)) {
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_CODES_PROMOS_ERR_NOT_FOUND']))->fetch();
			return false;
		}
	}
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_code_promo($frm);
}

/**
 * affiche_formulaire_code_promo()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_code_promo(&$frm)
{
	construit_arbo_categorie($categorie_options, vb($frm['id_categorie']));
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_code_promo.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . vb($frm['nouveau_mode']) . intval(vb($frm['id']))));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval(vb($frm['id'])));
	$tpl->assign('on_type', vn($frm['on_type']));
	$tpl->assign('nom', vn($frm['nom']));
	$tpl->assign('date_debut', get_formatted_date(vb($frm['date_debut'])));
	$tpl->assign('date_fin', get_formatted_date(vb($frm['date_fin'])));
	$tpl->assign('remise_percent', vn($frm['remise_percent']));
	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
	$tpl->assign('remise_valeur', vn($frm['remise_valeur']));
	$tpl->assign('montant_min', vn($frm['montant_min']));
	$tpl->assign('categorie_options', $categorie_options);
	$tpl->assign('nombre_prevue', vb($frm["nombre_prevue"]));
	$tpl->assign('nb_used_per_client', vb($frm["nb_used_per_client"]));
	$tpl->assign('etat', vn($frm['etat']));
	$tpl->assign('titre_bouton', vn($frm['titre_bouton']));
	$tpl->assign('STR_ADMIN_CODES_PROMOS_ALREADY_USED', sprintf($GLOBALS['STR_ADMIN_CODES_PROMOS_ALREADY_USED'], vn($frm['compteur_utilisation'])));
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
	$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_NB_USED_PER_CLIENT', $GLOBALS['STR_ADMIN_CODES_PROMOS_NB_USED_PER_CLIENT']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_NB_USED_PER_CLIENT_EXPLAIN', $GLOBALS['STR_ADMIN_CODES_PROMOS_NB_USED_PER_CLIENT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_NB_FORECASTED', $GLOBALS['STR_ADMIN_CODES_PROMOS_NB_FORECASTED']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_NB_FORECASTED_EXPLAIN', $GLOBALS['STR_ADMIN_CODES_PROMOS_NB_FORECASTED_EXPLAIN']);
	$tpl->assign('STR_ADMIN_ALL_CATEGORIES', $GLOBALS['STR_ADMIN_ALL_CATEGORIES']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_MIN', sprintf($GLOBALS['STR_ADMIN_CODES_PROMOS_MIN'], $GLOBALS['site_parameters']['symbole']));
	$tpl->assign('STR_ADMIN_CODES_PROMOS_MIN_EXPLAIN', $GLOBALS['STR_ADMIN_CODES_PROMOS_MIN_EXPLAIN']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_PERCENT', $GLOBALS['STR_ADMIN_CODES_PROMOS_PERCENT']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_VALUE', $GLOBALS['STR_ADMIN_CODES_PROMOS_VALUE']);
	$tpl->assign('STR_ADMIN_BEGIN_DATE', $GLOBALS['STR_ADMIN_BEGIN_DATE']);
	$tpl->assign('STR_ADMIN_END_DATE', $GLOBALS['STR_ADMIN_END_DATE']);
	$tpl->assign('STR_PROMO_CODE', $GLOBALS['STR_PROMO_CODE']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_ADD_CODE_PROMO_HEADER', $GLOBALS['STR_ADMIN_CODES_PROMOS_ADD_CODE_PROMO_HEADER']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_STATUS', $GLOBALS['STR_ADMIN_CODES_PROMOS_STATUS']);
	echo $tpl->fetch();
}

/**
 * Supprime le code promo spécifié par $id
 *
 * @param integer $id
 * @return
 */
function supprime_code_promo($id)
{
	$result = fetch_assoc(query("SELECT *
		FROM peel_codes_promos
		WHERE id=" . intval($id)));
	query("DELETE FROM peel_codes_promos
		WHERE id=" . intval($id));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CODES_PROMOS_ERR_DELETED'], String::strtoupper($result['nom']))))->fetch();
}

/**
 * maj_code_promo()
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_code_promo($id, $frm)
{
	$sql = "UPDATE peel_codes_promos SET
			nom = '" . nohtml_real_escape_string(String::strtoupper($frm['nom'])) . "'
			, date_debut = '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['date_debut'])) . "'
			, date_fin = '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['date_fin'])) . "'
			, remise_percent = '" . floatval(get_float_from_user_input($frm['remise_percent'])) . "'
			, remise_valeur = '" . floatval(get_float_from_user_input($frm['remise_valeur'])) . "'
			, on_type = '" . intval($frm['on_type']) . "'
			, montant_min = '" . floatval(get_float_from_user_input($frm['montant_min'])) . "'
			, etat = '" . intval($frm['etat']) . "'
			, id_site = '" . $GLOBALS['site_parameters']['id'] . "'
			, id_categorie = '" . intval($frm['id_categorie']) . "'
			, nombre_prevue = '" . intval($frm['nombre_prevue']) . "'
			, nb_used_per_client ='" . intval($frm['nb_used_per_client']) . "'
		WHERE id='" . intval($id) . "'";
	query($sql);
}

/**
 * affiche_liste_code_promo()
 *
 * @return
 */
function affiche_liste_code_promo()
{
	$sql = "SELECT cp.*, nom_" . $_SESSION['session_langue'] . " AS category_name
		FROM peel_codes_promos cp
		LEFT JOIN peel_categories c ON c.id=cp.id_categorie";
	$Links = new Multipage($sql, 'codes_promos');
	$HeaderTitlesArray = array($GLOBALS['STR_ADMIN_ACTION'], 'nom' => $GLOBALS['STR_ADMIN_CODE'], 'date_debut' => $GLOBALS['STR_ADMIN_BEGIN_DATE'], 'date_fin' => $GLOBALS['STR_ADMIN_END_DATE'], 'remise_percent,remise_valeur' => $GLOBALS['STR_ADMIN_DISCOUNT'], 'montant_min' => $GLOBALS['STR_ADMIN_DATE_STARTING'], 'category_name' => $GLOBALS['STR_CATEGORY'], 'etat' => $GLOBALS['STR_STATUS'], 'source' => $GLOBALS['STR_ADMIN_SOURCE']);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = "date_debut";
	$Links->SortDefault = "ASC";
	$results_array = $Links->Query();

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_code_promo.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);

	if (empty($results_array)) {
		$tpl->assign('are_results', false);
	} else {
		$tpl->assign('are_results', true);
		$tpl->assign('links_header_row', $Links->getHeaderRow());
		$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
		$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
		$tpl_results = array();

		$i = 0;
		foreach ($results_array as $ligne) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'nom' => $ligne['nom'],
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'date_debut' => get_formatted_date($ligne['date_debut']),
				'date_fin' => get_formatted_date($ligne['date_fin']),
				'on_type' => $ligne['on_type'],
				'percent' => number_format($ligne['remise_percent'], 2, ',', ' '),
				'valeur' => fprix($ligne['remise_valeur'], true, $GLOBALS['site_parameters']['code'], false),
				'montant_min' => ($ligne['montant_min'] > 0 ? fprix($ligne['montant_min'], true, $GLOBALS['site_parameters']['code'], false) : "-"),
				'category_name' => $ligne['category_name'],
				'etat_onclick' => 'change_status("codes_promos", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				'source' => $ligne['source']
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
		$tpl->assign('links_multipage', $Links->GetMultipage());
	}
	$tpl->assign('STR_ADMIN_CODES_PROMOS_LIST_TITLE', $GLOBALS['STR_ADMIN_CODES_PROMOS_LIST_TITLE']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_CREATE_PERCENTAGE_REBATE', $GLOBALS['STR_ADMIN_CODES_PROMOS_CREATE_PERCENTAGE_REBATE']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_CREATE_AMOUNT_REBATE', $GLOBALS['STR_ADMIN_CODES_PROMOS_CREATE_AMOUNT_REBATE']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
	$tpl->assign('STR_ADMIN_ALL_CATEGORIES', $GLOBALS['STR_ADMIN_ALL_CATEGORIES']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_NOT_FOUND', $GLOBALS['STR_ADMIN_CODES_PROMOS_NOT_FOUND']);
	echo $tpl->fetch();
}

/**
 * affiche_liste_code_pour_client()
 *
 * @param integer $id_utilisateur
 * @return
 */
function affiche_liste_code_pour_client($id_utilisateur)
{
	$utilisateur = get_user_information($id_utilisateur);
	$qcpromo = query("SELECT *
		FROM peel_codes_promos
		WHERE etat = '1' AND source != 'CHQ'");

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_code_pour_client.tpl');
	$tpl_options = array();
	while ($codespromos = fetch_assoc($qcpromo)) {
		$tpl_options[] = array('value' => intval($codespromos['id']),
			'nom' => $codespromos['nom'],
			'on_type' => $codespromos['on_type'],
			'percent' => number_format($codespromos['remise_percent'], 2, ',', ' '),
			'valeur' => fprix($codespromos['remise_valeur'], true, $GLOBALS['site_parameters']['code'], false),
			'montant_min' => fprix($codespromos['montant_min'], true, $GLOBALS['site_parameters']['code'], false)
			);
	}
	$tpl->assign('options', $tpl_options);

	$tpl->assign('modif_util_href', $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $id_utilisateur);
	$tpl->assign('civilite', $utilisateur['civilite']);
	$tpl->assign('prenom', $utilisateur['prenom']);
	$tpl->assign('nom_famille', $utilisateur['nom_famille']);
	$tpl->assign('email', $utilisateur['email']);
	$tpl->assign('codes_promos_href', $GLOBALS['administrer_url'] . '/codes_promos.php');

	if (!empty($tpl_options)) {
		$tpl->assign('action', get_current_url(false));
		$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . 'envoi_client' . $id_utilisateur));
		$tpl->assign('id_utilisateur', intval($id_utilisateur));
		$tpl->assign('cancel_href', $GLOBALS['administrer_url'] . '/utilisateurs.php');
	}
	$tpl->assign('STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_TITLE', $GLOBALS['STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_TITLE']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_SUBTITLE', $GLOBALS['STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_SUBTITLE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_UPDATE', $GLOBALS['STR_ADMIN_UTILISATEURS_UPDATE']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_SELECT_TO_SEND', $GLOBALS['STR_ADMIN_CODES_PROMOS_SELECT_TO_SEND']);
	$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
	$tpl->assign('STR_ADMIN_DISCOUNT', $GLOBALS['STR_ADMIN_DISCOUNT']);
	$tpl->assign('STR_VALUE', $GLOBALS['STR_VALUE']);
	$tpl->assign('STR_ADMIN_DATE_STARTING', $GLOBALS['STR_ADMIN_DATE_STARTING']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL', $GLOBALS['STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL']);
	$tpl->assign('STR_CANCEL', $GLOBALS['STR_CANCEL']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_ERR_FIRST_CREATE_CODE_PROMO', $GLOBALS['STR_ADMIN_CODES_PROMOS_ERR_FIRST_CREATE_CODE_PROMO']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_MIN', sprintf($GLOBALS['STR_ADMIN_CODES_PROMOS_MIN'], $GLOBALS['site_parameters']['code']));
	echo $tpl->fetch();
}

?>
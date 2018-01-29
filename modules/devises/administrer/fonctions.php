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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Renvoie les éléments de menu affichables
 *
 * @param array $params
 * @return
 */
function devises_hook_admin_menu_items($params) {
	$result['menu_items']['manage_payments'][$GLOBALS['wwwroot_in_admin'] . '/modules/devises/administrer/devises.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DEVISES"];
	return $result;
}

/**
 * Affiche un formulaire vierge pour ajouter une devise
 *
 * @return
 */
function affiche_formulaire_ajout_devise($frm)
{
	/* Default value*/
	if(empty($frm)) {
		$frm['devise'] = "";
		$frm['conversion'] = 0;
		$frm['symbole'] = "";
		$frm['code'] = "";
		$frm['etat'] = 0;
		$frm['symbole_place'] = 1;
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['site_id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_ADD'];

	affiche_formulaire_devise($frm);
}

/**
 * Affiche le formulaire de modification pour la devise sélectionnée
 *
 * @param integer $id
 * @return
 */
function affiche_formulaire_modif_devise($id, $frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations de la devise */
		$qid = query("SELECT * 
			FROM peel_devises 
			WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('devises', null, true) . "");
		$frm = fetch_assoc($qid);
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_devise($frm);
}

/**
 * affiche_formulaire_devise()
 *
 * @return
 */
function affiche_formulaire_devise($frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/devisesAdmin_formulaire.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('etat', $frm["etat"]);
	$tpl->assign('devise', $frm["devise"]);
	$tpl->assign('symbole', $frm["symbole"]);
	$tpl->assign('symbole_place', $frm["symbole_place"]);
	$tpl->assign('code', $frm["code"]);
	$tpl->assign('symbole_parameters', $GLOBALS['site_parameters']['symbole']);
	$tpl->assign('conversion', $frm["conversion"]);
	$tpl->assign('titre_bouton', $frm["titre_bouton"]);
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_TITLE', $GLOBALS['STR_MODULE_DEVISES_ADMIN_TITLE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_DEVISE', $GLOBALS['STR_DEVISE']);
	$tpl->assign('STR_ADMIN_SYMBOL', $GLOBALS['STR_ADMIN_SYMBOL']);
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_SYMBOL_AT_RIGHT', $GLOBALS['STR_MODULE_DEVISES_ADMIN_SYMBOL_AT_RIGHT']);
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_SYMBOL_AT_LEFT', $GLOBALS['STR_MODULE_DEVISES_ADMIN_SYMBOL_AT_LEFT']);
	$tpl->assign('STR_ADMIN_CODE', $GLOBALS['STR_ADMIN_CODE']);
	$tpl->assign('STR_ADMIN_CONVERSION', $GLOBALS['STR_ADMIN_CONVERSION']);
	echo $tpl->fetch();
}

/**
 * Supprime la devise spécifié par $id
 *
 * @param integer $id
 * @return
 */
function supprime_devise($id)
{
	$qid = query("SELECT devise 
		FROM peel_devises 
		WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('devises', null, true) . "");
	$col = fetch_assoc($qid);
	/* Efface la devise */
	query("DELETE FROM peel_devises WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('devises', null, true) . "");
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_DEVISES_ADMIN_MSG_DELETED_OK'], $col['devise'])))->fetch();
}

/**
 * insere_devise()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_devise($frm)
{
	$sql = "INSERT INTO peel_devises (
			etat
			, site_id
			, symbole
			, symbole_place
			, devise
			, conversion
			, code
		) VALUES (
			'" . intval($frm['etat']) . "'
			, '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
			, '" . nohtml_real_escape_string($frm['symbole']) . "'
			, '" . nohtml_real_escape_string($frm['symbole_place']) . "'
			, '" . nohtml_real_escape_string($frm['devise']) . "'
			, '" . nohtml_real_escape_string(floatval(str_replace(",", ".", $frm['conversion']))) . "'
			, '" . nohtml_real_escape_string($frm['code']) . "'
		)";
	query($sql);
}

/**
 * maj_devise()
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_devise($id, $frm)
{
	$conversion = str_replace(",", ".", $frm['conversion']);
	$conversion = floatval($conversion);

	$sql = "UPDATE peel_devises 
		SET etat = '" . intval($frm['etat']) . "'
			, symbole = '" . nohtml_real_escape_string($frm['symbole']) . "'
			, symbole_place = '" . nohtml_real_escape_string($frm['symbole_place']) . "'
			, devise = '" . nohtml_real_escape_string($frm['devise']) . "'
			, conversion = '" . nohtml_real_escape_string($conversion) . "'
			, code = '" . nohtml_real_escape_string($frm['code']) . "'
			, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('devises', null, true) . "";

	query($sql);
}

/**
 * affiche_liste_devise()
 *
 * @param integer $start
 * @return
 */
function affiche_liste_devise($start)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/devisesAdmin_liste.tpl');
	$tpl->assign('ajout_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('update_rates_href', get_current_url(false) . '?mode=update_rates');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl_results = array();
	$query = query("SELECT * 
		FROM peel_devises 
		WHERE " . get_filter_site_cond('devises', null, true) . "
		ORDER BY devise");
	if (!(num_rows($query) == 0)) {
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			$tpl_results[] = array(
				'tr_rollover' => tr_rollover($i, true),
				'devise' => $ligne['devise'],
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'symbole' => $ligne['symbole'],
				'conversion' => $ligne['conversion'],
				'code' => $ligne['code'],
				'site_name' => get_site_name($ligne['site_id']),
				'etat_onclick' => 'change_status("devises", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif')
			);
			$i++;
		}
	}
	$tpl->assign('results', $tpl_results);
	$tpl->assign('site_code', vb($GLOBALS['site_parameters']['code']));
	$tpl->assign('modif_href', $GLOBALS['administrer_url'] . '/sites.php?mode=modif&id=1');
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_DEFAULT_CURRENCY', sprintf($GLOBALS['STR_MODULE_DEVISES_ADMIN_DEFAULT_CURRENCY'], vb($GLOBALS['site_parameters']['code'])));
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_DEFAULT_CURRENCY_EXPLAIN', $GLOBALS['STR_MODULE_DEVISES_ADMIN_DEFAULT_CURRENCY_EXPLAIN']);
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_LIST_TITLE', $GLOBALS['STR_MODULE_DEVISES_ADMIN_LIST_TITLE']);
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_CREATE', $GLOBALS['STR_MODULE_DEVISES_ADMIN_CREATE']);
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_CREATE_EXPLAIN', $GLOBALS['STR_MODULE_DEVISES_ADMIN_CREATE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_DEVISE', $GLOBALS['STR_DEVISE']);
	$tpl->assign('STR_ADMIN_SYMBOL', $GLOBALS['STR_ADMIN_SYMBOL']);
	$tpl->assign('STR_ADMIN_CONVERSION', $GLOBALS['STR_ADMIN_CONVERSION']);
	$tpl->assign('STR_ADMIN_CODE', $GLOBALS['STR_ADMIN_CODE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_UPDATE', $GLOBALS['STR_MODULE_DEVISES_ADMIN_UPDATE']);
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_NOTHING_FOUND', $GLOBALS['STR_MODULE_DEVISES_ADMIN_NOTHING_FOUND']);
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_DEFAULT_CURRENCY', sprintf($GLOBALS['STR_MODULE_DEVISES_ADMIN_DEFAULT_CURRENCY'], vb($GLOBALS['site_parameters']['code'])));
	$tpl->assign('STR_MODULE_DEVISES_ADMIN_DEFAULT_CURRENCY_EXPLAIN', $GLOBALS['STR_MODULE_DEVISES_ADMIN_DEFAULT_CURRENCY_EXPLAIN']);
	echo $tpl->fetch();
}

/**
 * Mise à jour de la table peel_devises
 *
 * @param mixed $base_currency_code
 * @param mixed $commission_percentage Permet de corriger les taux de change en fonction des frais bancaire de conversion
 * @return
 */
function update_currencies_rates($base_currency_code, $commission_percentage = 2.5)
{
	$output = '<b>'.sprintf($GLOBALS['STR_MODULE_DEVISES_ADMIN_UPDATE_TITLE'], $commission_percentage).' :</b><br />';
	$q = query("SELECT code, conversion
		FROM peel_devises
		WHERE code!='" . nohtml_real_escape_string($base_currency_code) . "' AND " . get_filter_site_cond('devises', null, true) . "");
	while ($result = fetch_object($q)) {
		unset($rate);
		$rate = quote_xe_currency($result->code, $base_currency_code);
		$output .= 'XE : ' . $result->code . '=' . $rate . '<br />';
		if (empty($rate)) {
			$rate = quote_google_currency($result->code, $base_currency_code);
			$output .= 'Google : ' . $result->code . '=' . $rate . '<br />';
		}
		if (empty($rate)) {
			$rate = quote_oanda_currency($result->code, $base_currency_code);
			$output .= 'Oanda : ' . $result->code . '=' . $rate . '<br />';
		}
		if (!empty($rate) && is_numeric($rate)) {
			$currency_update[$result->code] = $rate * (1 + $commission_percentage / 100);
		}
		if (rand(0, 3) == 1) {
			// Ne pas surcharger les sites d'appels trop fréquents
			sleep(1);
		}
	}
	if (!empty($currency_update)) {
		foreach($currency_update as $code => $rate) {
			query("UPDATE peel_devises
				SET conversion='" . str_replace(',', '.', $rate) . "'
				WHERE code='" . nohtml_real_escape_string($code) . "' AND " . get_filter_site_cond('devises', null, true) . "");
		}
		$output = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $output))->fetch();
	} else {
		$output = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $output . '<br />' . sprintf($GLOBALS['STR_MODULE_DEVISES_ADMIN_ERR_GET_DATA'], @ini_get("allow_url_fopen"))))->fetch();
	}
	return $output;
}

/**
 * Fonctions pour récupérer les taux de change
 *
 * @param string $to
 * @param string $from
 * @return
 */
function quote_oanda_currency($to, $from)
{
	$page = @file('http://www.oanda.com/convert/fxdaily?value=1&redirected=1&exch=' . $to . '&format=CSV&dest=Get+Table&sel_list=' . $from);
	$match = array();

	if (!empty($page)) {
		preg_match('/(.+),(\w{3,4}),([0-9.]+),([0-9.]+)/i', StringMb::strip_tags(str_replace(array("\r", "\n"), '', implode('', $page))), $match);
	}
	if (sizeof($match) > 0) {
		return $match[3];
	} else {
		return false;
	}
}

/**
 * quote_xe_currency()
 *
 * @param string $to
 * @param string $from
 * @return
 */
function quote_xe_currency($to, $from)
{
	$url = 'http://www.xe.com/ucc/convert?Amount=1&From=' . $from . '&To=' . $to;
	$page = file($url);
	// Debug : var_dump($url, $page);
	$match = array();
	if (!empty($page)) {
		preg_match('/[0-9.]+\s*' . $from . '\s*=\s*([0-9.]+)\s*' . $to . '/', StringMb::strip_tags(str_replace(array("\r", "\n", '&nbsp;'), array("", "", ' '), implode('', $page))), $match);
	}
	if (sizeof($match) > 0) {
		return $match[1];
	} else {
		return false;
	}
}

/**
 * quote_google_currency()
 *
 * @param string $to
 * @param string $from
 * @return
 */
function quote_google_currency($to, $from)
{
	$page = @file('http://www.google.com/ig/calculator?hl=en&q=' . urlencode(1 . '' . $from . '=?' . $to));
	$page = implode('', $page);
	if (!empty($page)) {
		$page = StringMb::substr($page, StringMb::strpos($page, 'rhs: "') + StringMb::strlen('rhs: "'));
		$amount = StringMb::substr($page, 0, StringMb::strpos($page, ' '));
	}
	if (!empty($amount) && is_numeric($amount)) {
		return $amount;
	} else {
		return false;
	}
	return $matches[1] ? $matches[1] : false;
}


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
// $Id: livraisons.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales");

$GLOBALS['DOC_TITLE'] = "";
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_livraisons_information_select.tpl');
$tpl->assign('delivery_status_options', get_delivery_status_options(vb($_GET['statut'])));
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ORDER_STATUT_LIVRAISON', $GLOBALS['STR_ORDER_STATUT_LIVRAISON']);
$tpl->assign('STR_ADMIN_ALL_ORDERS', $GLOBALS['STR_ADMIN_ALL_ORDERS']);
$information_select_html = $tpl->fetch();

echo get_admin_date_filter_form($GLOBALS['STR_ADMIN_LIVRAISONS_SALES_HEADER'], $information_select_html);
// /////////// PROCESS /////////////
if (isset($_GET['jour1']) or isset($dateAdded1)) {
	$check_admin_date_data = check_admin_date_data($_GET);
	if (empty($check_admin_date_data)) {
		$dateAdded1 = $_GET['an1'] . '-' . str_pad($_GET['mois1'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($_GET['jour1'], 2, 0, STR_PAD_LEFT) . " 00:00:00";
		$dateAdded2 = $_GET['an2'] . '-' . str_pad($_GET['mois2'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($_GET['jour2'], 2, 0, STR_PAD_LEFT) . " 23:59:59";
		if (vb($_GET['order_date_field_filter']) == 'a_timestamp') {
			$date_field = "a_timestamp";
		} elseif (vb($_GET['order_date_field_filter']) == 'e_datetime') {
			$date_field = "e_datetime";
		} elseif (vb($_GET['order_date_field_filter']) == 'f_datetime') {
			$date_field = "f_datetime";
		} else {
			$date_field = "o_timestamp";
		}
		$sql = "SELECT *
			FROM peel_commandes pc
			LEFT JOIN peel_utilisateurs pu ON pc.id_utilisateur = pu.id_utilisateur AND " . get_filter_site_cond('utilisateurs', 'pu') . "
			WHERE " . get_filter_site_cond('commandes', 'pc', true) . " AND pc." . word_real_escape_string($date_field) . " >= '" . nohtml_real_escape_string($dateAdded1) . "' AND pc." . word_real_escape_string($date_field) . " <= '" . nohtml_real_escape_string($dateAdded2) . "' " . (isset($_GET['statut']) && is_numeric($_GET['statut'])? "AND id_statut_livraison = '" . intval($_GET['statut']) . "'" : "") . "
			ORDER BY pc." . word_real_escape_string($date_field);
		if (isset($_GET['statut']) && is_numeric($_GET['statut'])) {
			$extra_csv_param = "&id_statut_livraison=" . intval($_GET['statut']);
		} else {
			$extra_csv_param = '';
		}
		$query = query($sql);

		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_livraisons.tpl');
		$tpl->assign('period_text', ucfirst($GLOBALS['strStartingOn']) . ' ' . get_formatted_date($dateAdded1) . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($dateAdded2));
		$tpl->assign('update_src', $GLOBALS['wwwroot_in_admin'] . '/images/update-on.png');
		$tpl->assign('ttc_ht', (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));
		if (num_rows($query) > 0) {
			$tpl_results = array();

			$i = 0;
			while ($result = fetch_assoc($query)) {
				if (display_prices_with_taxes_in_admin()) {
					$montant_displayed = $result['montant'];
				} else {
					$montant_displayed = $result['montant_ht'];
				}
				$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, 'height:30px;'),
					'date' => get_formatted_date($result[$date_field]),
					'notcheckUserInfo' => !checkUserInfo($result, $result),
					'id' => $result['id'],
					'commande_edit_href' => $GLOBALS['administrer_url'] . '/commander.php?commandeid=' . $result['id'] . '&mode=modif',
					'prix' => fprix($montant_displayed, true, $result['devise'], true, $result['currency_rate']),
					'prenom_bill' => $result['prenom_bill'],
					'nom_bill' => $result['nom_bill'],
					'adresse_bill' => $result['adresse_bill'],
					'zip_bill' => $result['zip_bill'],
					'ville_bill' => $result['ville_bill'],
					'telephone_bill' => $result['telephone_bill'],
					'email' => $result['email'],
					'util_edit_href' => $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $result['id_utilisateur']
					);
				$i++;
			}
			$tpl->assign('results', $tpl_results);
			$tpl->assign('excel_src', $GLOBALS['administrer_url'] . '/images/excel.jpg');
			if (check_if_module_active('export', '/administrer/export_livraisons.php')) {
				$tpl->assign('export_encoding', $GLOBALS['site_parameters']['export_encoding']);
				$tpl->assign('export_href', $GLOBALS['wwwroot_in_admin'] . '/modules/export/administrer/export_livraisons.php?dateadded1=' . $dateAdded1 . '&dateadded2=' . $dateAdded2 . $extra_csv_param);
			}
			if (!empty($_GET['statut'])) {
				$tpl->assign('delivery_status', get_delivery_status_name($_GET['statut']));
			}
		}
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_LIVRAISONS_EXPLAIN', sprintf($GLOBALS['STR_ADMIN_LIVRAISONS_EXPLAIN'], '<img src="'.$GLOBALS['wwwroot_in_admin'] . '/images/update-on.png" alt="update-on.png" />'));
		$tpl->assign('STR_DATE', $GLOBALS['STR_DATE']);
		$tpl->assign('STR_ORDER_NAME', $GLOBALS['STR_ORDER_NAME']);
		$tpl->assign('STR_AMOUNT', $GLOBALS['STR_AMOUNT']);
		$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
		$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
		$tpl->assign('STR_SHIP_ADDRESS', $GLOBALS['STR_SHIP_ADDRESS']);
		$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
		$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
		$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
		$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
		$tpl->assign('STR_ADMIN_ALL_ORDERS', $GLOBALS['STR_ADMIN_ALL_ORDERS']);
		$tpl->assign('STR_ADMIN_LIVRAISONS_NO_ORDER_FOUND', $GLOBALS['STR_ADMIN_LIVRAISONS_NO_ORDER_FOUND']);
		$tpl->assign('STR_ADMIN_ASKED_STATUS', $GLOBALS['STR_ADMIN_ASKED_STATUS']);
		$tpl->assign('STR_DATE', $GLOBALS['STR_DATE']);
		$tpl->assign('STR_EXPEDITION_DATE', $GLOBALS['STR_EXPEDITION_DATE']);
		$tpl->assign('STR_ORDER_NAME', $GLOBALS['STR_ORDER_NAME']);
		$tpl->assign('STR_AMOUNT', $GLOBALS['STR_AMOUNT']);
		$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
		$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
		$tpl->assign('STR_SHIP_ADDRESS', $GLOBALS['STR_SHIP_ADDRESS']);
		$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
		$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
		$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
		$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
		$tpl->assign('STR_ADMIN_LIVRAISONS_EXCEL_EXPORT', $GLOBALS['STR_ADMIN_LIVRAISONS_EXCEL_EXPORT']);
		$tpl->assign('STR_ADMIN_SEE', $GLOBALS['STR_ADMIN_SEE']);
		$tpl->assign('STR_ADMIN_ASKED_STATUS', $GLOBALS['STR_ADMIN_ASKED_STATUS']);
		$tpl->assign('STR_ADMIN_ALL_ORDERS', $GLOBALS['STR_ADMIN_ALL_ORDERS']);
		$tpl->assign('STR_ADMIN_LIVRAISONS_NO_ORDER_FOUND', $GLOBALS['STR_ADMIN_LIVRAISONS_NO_ORDER_FOUND']);
		$tpl->assign('export_encoding_explain', sprintf($GLOBALS['STR_ADMIN_LIVRAISONS_FORMAT_EXPLAIN'], $GLOBALS['site_parameters']['export_encoding']));
		echo $tpl->fetch();
	} else {
		echo $check_admin_date_data;
	}
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


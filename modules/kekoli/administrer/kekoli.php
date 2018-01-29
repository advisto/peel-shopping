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
// $Id: kekoli.php 55332 2017-12-01 10:44:06Z sdelaporte $

define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales,admin_webmastering");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_MODULE_KEKOLI_ADMIN_TITLE'];
$output = '';

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_ventes_information_select.tpl');
$tpl->assign('payment_status_options', get_payment_status_options(vb($_GET['statut'])));
$tpl->assign('STR_ORDER_STATUT_PAIEMENT', $GLOBALS['STR_ORDER_STATUT_PAIEMENT']);
$tpl->assign('STR_ADMIN_ALL_ORDERS', $GLOBALS['STR_ADMIN_ALL_ORDERS']);
$information_select_html = $tpl->fetch();
$output .= get_admin_date_filter_form($GLOBALS['STR_MODULE_KEKOLI_ADMIN_RESULTS_TITLE'], $information_select_html);

// PROCESS
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
		$sql = "SELECT c.*, sp.nom_" . $_SESSION['session_langue'] . " AS statut_paiement
			FROM peel_commandes c
			LEFT JOIN peel_statut_paiement sp ON c.id_statut_paiement=sp.id AND " . get_filter_site_cond('statut_paiement', 'sp') . "
			LEFT JOIN peel_types t ON t.id=c.typeId AND " . get_filter_site_cond('types', 't') . "
			WHERE " . get_filter_site_cond('commandes', 'c', true) . " AND t.without_delivery_address!='1' AND c." . word_real_escape_string($date_field) . ">='" . nohtml_real_escape_string($dateAdded1) . "' AND c." . word_real_escape_string($date_field) . "<='" . nohtml_real_escape_string($dateAdded2) . "'";
		if (isset($_GET['statut']) && is_numeric($_GET['statut'])) {
			$sql .= " AND c.id_statut_paiement = '" . intval($_GET['statut']) . "'";
			$extra_csv_param = "&id_statut_paiement=" . intval($_GET['statut']);
		} else {
			$extra_csv_param = '';
		}
		$sql .= "
				ORDER BY c." . word_real_escape_string($date_field);
		$query = query($sql);

		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_ventes.tpl');
		$tpl->assign('period_text', ucfirst($GLOBALS['strStartingOn']) . ' ' . get_formatted_date($dateAdded1) . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($dateAdded2));
		if (num_rows($query) > 0) {
			$tpl_results = array();

			$totalVente = $totalVenteHt = 0;
			$totalTransport = 0;
			$netapayer = 0;
			$totalAvoir = 0;
			$totalTva = $totalTvaTransport = 0;
			$i = 1;
			while ($result = fetch_assoc($query)) {
				$totalVente += $result['montant']+$result['avoir'];
				$netapayer += $result['montant'];
				$totalAvoir += $result['avoir'];
				$totalVenteHt += $result['montant_ht'];
				$totalTransport += $result['cout_transport'];
				$totalTva += $result['total_tva'];
				$totalTvaTransport += $result['tva_cout_transport'];
				$vat_arrays[] = get_vat_array($result['code_facture']);
				$avoir_devise_commande = '';
				$montant_devise_commande = '';
				$netapayer_devise_commande = '';
				$montant_ht_devise_commande = '';
				$total_tva_devise_commande = '';
				$cout_transport_devise_commande = '';
				if ($result['devise'] != $GLOBALS['site_parameters']['code']) {
					// Si la devise de la commande est différente de la devise de l'admin alors on affiche le prix dans la devise de la commande en plus
					$avoir_devise_commande = '(' . fprix($result['avoir'], true, $result['devise'], true, $result['currency_rate']) . ')';
					$montant_devise_commande = '(' . fprix($result['montant']+$result['avoir'], true, $result['devise'], true, $result['currency_rate']) . ')';
					$netapayer_devise_commande = '(' . fprix($result['montant'], true, $result['devise'], true, $result['currency_rate']) . ')';
					$montant_ht_devise_commande = '(' . fprix($result['montant_ht'], true, $result['devise'], true, $result['currency_rate']) . ')';
					$total_tva_devise_commande = '(' . fprix($result['total_tva'], true, $result['devise'], true, $result['currency_rate']) . ')';
					if ($result['cout_transport'] != 0) {
						// Si les frais de port ne sont pas nuls alors on affiche le prix dans la devise de la commande pour éviter un doublon
						$cout_transport_devise_commande = '(' . fprix($result['cout_transport'], true, $result['devise'], true, $result['currency_rate']) . ')';
					}
				}
				$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
					'date' => get_formatted_date($result['o_timestamp']),
					'id' => $result['id'],
					'modif_href' => $GLOBALS['administrer_url'] . '/commander.php?commandeid=' . $result['id'] . '&mode=modif',
					'statut_paiement' => $result['statut_paiement'],
					'email' => $result['email'],
					'montant_ht_prix' => fprix($result['montant_ht'], true, $GLOBALS['site_parameters']['code'], false),
					'montant_ht_devise_commande' => $montant_ht_devise_commande,
					'total_tva_prix' => fprix($result['total_tva'], true, $GLOBALS['site_parameters']['code'], false),
					'total_tva_devise_commande' => $total_tva_devise_commande,
					'montant_prix' => fprix($result['montant']+$result['avoir'], true, $GLOBALS['site_parameters']['code'], false),
					'montant_devise_commande' => $montant_devise_commande,
					'netapayer' => fprix($result['montant'], true, $GLOBALS['site_parameters']['code'], false),
					'netapayer_devise_commande' => $netapayer_devise_commande,
					'avoir' => fprix($result['avoir'], true, $GLOBALS['site_parameters']['code'], false),
					'avoir_devise_commande' => $avoir_devise_commande,
					'cout_transport_prix' => fprix($result['cout_transport'], true, $GLOBALS['site_parameters']['code'], false),
					'cout_transport_devise_commande' => $cout_transport_devise_commande,
					);
				$i++;
			}
			$tpl->assign('results', $tpl_results);
			foreach ($vat_arrays as $this_vat_array) {
				foreach ($this_vat_array as $this_vat_rate => $this_amount) {
					if (!isset($total_vat_array[$this_vat_rate])) {
						$total_vat_array[$this_vat_rate] = $this_amount;
					} else {
						$total_vat_array[$this_vat_rate] += $this_amount;
					}
				}
			}
			$tpl_vats = array();
			if(!empty($total_vat_array)) {
				ksort($total_vat_array);
				foreach ($total_vat_array as $this_vat_rate => $this_amount) {
					$tpl_vats[] = array('rate' => $this_vat_rate,
						'prix' => fprix($this_amount, true, $GLOBALS['site_parameters']['code'], false)
						);
				}
			}
			$tpl->assign('vats', $tpl_vats);
			$tpl->assign('totalVenteHt_prix', fprix($totalVenteHt, true, $GLOBALS['site_parameters']['code'], false));
			$tpl->assign('totalTva_prix', fprix($totalTva, true, $GLOBALS['site_parameters']['code'], false));
			$tpl->assign('totalVente_prix', fprix($totalVente, true, $GLOBALS['site_parameters']['code'], false));
			$tpl->assign('totalNet_a_payer', fprix($netapayer, true, $GLOBALS['site_parameters']['code'], false));
			$tpl->assign('total_avoir', fprix($totalAvoir, true, $GLOBALS['site_parameters']['code'], false));
			$tpl->assign('totalTransport_prix', fprix($totalTransport, true, $GLOBALS['site_parameters']['code'], false));
			$tpl->assign('is_module_export_ventes_active', check_if_module_active('export', 'administrer/export_ventes.php'));
			$tpl->assign('export_href', $GLOBALS['wwwroot_in_admin'] . '/modules/export/administrer/export_ventes.php?dateadded1=' . $dateAdded1 . '&dateadded2=' . $dateAdded2 . $extra_csv_param);
			$tpl->assign('export_href_one_line_per_order', $GLOBALS['wwwroot_in_admin'] . '/modules/export/administrer/export_ventes.php?mode=one_line_per_order&dateadded1=' . $dateAdded1 . '&dateadded2=' . $dateAdded2 . $extra_csv_param);
			$tpl->assign('excel_src', $GLOBALS['administrer_url'] . '/images/excel.jpg');

			if (!empty($_GET['statut'])) {
				$tpl->assign('payment_status_name', get_payment_status_name($_GET['statut']));
			}
		} else {
			$tpl->assign('are_results', false);
		}
		$tpl->assign('only_delivered', true);
		
		$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
		$tpl->assign('STR_HT', $GLOBALS['STR_HT']);
		$tpl->assign('STR_PDF_AVOIR', $GLOBALS['STR_PDF_AVOIR']);
		$tpl->assign('STR_NET', $GLOBALS['STR_NET']);
		$tpl->assign('STR_ADMIN_VENTES_FORM_EXPLAIN', $GLOBALS['STR_ADMIN_VENTES_FORM_EXPLAIN']);
		$tpl->assign('STR_DATE', $GLOBALS['STR_DATE']);
		$tpl->assign('STR_ORDER_NAME', $GLOBALS['STR_ORDER_NAME']);
		$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
		$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
		$tpl->assign('STR_AMOUNT', $GLOBALS['STR_AMOUNT']);
		$tpl->assign('STR_VAT', $GLOBALS['STR_VAT']);
		$tpl->assign('STR_AMOUNT', $GLOBALS['STR_AMOUNT']);
		$tpl->assign('STR_ADMIN_INCLUDING_DELIVERY_COST', $GLOBALS['STR_ADMIN_INCLUDING_DELIVERY_COST']);
		$tpl->assign('STR_ADMIN_BILL_TOTALS', $GLOBALS['STR_ADMIN_BILL_TOTALS']);
		$tpl->assign('STR_ADMIN_TOTAL_VAT', $GLOBALS['STR_ADMIN_TOTAL_VAT']);
		$tpl->assign('STR_ADMIN_VENTES_EXPORT_EXCEL', $GLOBALS['STR_MODULE_KEKOLI_ADMIN_EXPORT_EXCEL']);
		$tpl->assign('STR_ADMIN_VENTES_EXPORT_EXCEL_ONE_LINE_PER_ORDER', $GLOBALS['STR_ADMIN_VENTES_EXPORT_EXCEL_ONE_LINE_PER_ORDER']);
		$tpl->assign('STR_ADMIN_ASKED_STATUS', $GLOBALS['STR_ADMIN_ASKED_STATUS']);
		$tpl->assign('STR_ADMIN_ALL_ORDERS', $GLOBALS['STR_ADMIN_ALL_ORDERS']);
		$tpl->assign('STR_ADMIN_VENTES_NO_ORDER_FOUND', $GLOBALS['STR_ADMIN_VENTES_NO_ORDER_FOUND']);
		$tpl->assign('STR_MODULE_KEKOLI_ADMIN_ONLY_DELIVERED', $GLOBALS["STR_MODULE_KEKOLI_ADMIN_ONLY_DELIVERED"]);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$output .= $tpl->fetch();
	} else {
		$output .= $check_admin_date_data;
	}
}
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


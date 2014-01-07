<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an		|
// | opensource GPL license: you are allowed to customize the code			|
// | for your own needs, but must keep your changes under GPL				|
// | More information: https://www.peel.fr/lire/licence-gpl-70.html			|
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/		|
// +----------------------------------------------------------------------+
// $Id: commande_html.php 39443 2014-01-06 16:44:24Z sdelaporte $
include("../../configuration.inc.php");

if (!is_module_factures_html_active() || is_user_bot()) {
	// This module is not activated or this user is a bot => we redirect to the homepage
	redirect_and_die($GLOBALS['wwwroot'] . "/");
}

/* Charge les détails d'une commande et les affiche */

if (!empty($_GET['code_facture'])) {
	$code_facture = $_GET['code_facture'];
	$sql = 'SELECT *
		FROM peel_commandes
		WHERE HEX(code_facture) = HEX("' . nohtml_real_escape_string($code_facture) . '")';
} elseif ((!empty($_GET['id']) && !empty($_GET['timestamp']))) {
	$id = intval($_GET['id']);
	$timestamp = $_GET['timestamp'];
	$sql = 'SELECT *
		FROM peel_commandes
		WHERE id = "' . intval($id) . '" AND o_timestamp = "' . nohtml_real_escape_string($timestamp) . '"';
} else {
	die();
}

$qid_commande = query($sql);

if ($commande = fetch_object($qid_commande)) {
	$_SESSION['session_last_bill_viewed'] = $commande->id;
	$output = '';
	$GLOBALS['site_parameters']['css'] = 'html_bill.css';
	$order_infos = get_order_infos_array($commande);
	$client = get_user_information($commande->id_utilisateur);
	$id = intval($commande->id);
	$numero = $id;
	$date_document = $commande->o_timestamp;
	if (!empty($_GET['partial'])) {
		if (!empty($_GET['currency_rate'])) {
			$amount_to_pay = get_float_from_user_input($_GET['partial']) / get_float_from_user_input(vn($_GET['currency_rate']));
		} else {
			$amount_to_pay = get_float_from_user_input($_GET['partial']);
		}
	} else {
		$amount_to_pay = get_float_from_user_input($commande->montant);
	}
	if (!empty($_GET['mode']) && $_GET['mode'] == 'bdc') {
		$libelle = $GLOBALS['STR_PROFORMA'];
	} else {
		$libelle = $GLOBALS['STR_INVOICE'];
	}
	$bill_address_title = $GLOBALS['STR_INVOICE_ADDRESS'];
	if (!empty($commande->id_utilisateur)) {
		$bill_address_title .= ' ' . $commande->id_utilisateur;
	}

	$output .= '
<div class="total">
	<table class="main_table">
		<tr>
			<td class="center">
				<h1 class="bill_title">' . $libelle . " " . $GLOBALS['STR_NUMBER'] . " " . $numero . " - " . get_formatted_date($date_document) . "" . '</h1>
			</td>
		</tr>
		<tr>
			<td>
				<table cellpadding="5" style="width:50%">
					<tr>
						<td>' . print_societe(true) . '</td>
					</tr>
				</table>
				<table class="full_width">
					<tr>
						<td class="top" style="width:50%; padding-right:10px">
							<table class="full_width" cellpadding="5">
								<tr>
									<td class="bill_cell_title">' . $bill_address_title . '</td>
								</tr>
								<tr>
									<td class="bill_cell">' . nl2br($order_infos['client_infos_bill']) . '</td>
								</tr>
							</table>
						</td>
						<td class="top" style="width:50%">
							' . (!empty($order_infos['client_infos_ship'])?'
							<table class="full_width" cellpadding="5">
								<tr>
									<td class="bill_cell_title">' . $GLOBALS['STR_SHIP_ADDRESS'] . '</td>
								</tr>
								<tr>
									<td class="bill_cell">' . nl2br($order_infos['client_infos_ship']) . '</td>
								</tr>
							</table>':'') . '
						</td>
					</tr>
				</table>
				<br />
				<table class="full_width" cellpadding="5">
					<tr>
				 		<td class="bill_cell_title">' . $GLOBALS['STR_REFERENCE'] . '</td>
						<td class="bill_cell_title">' . $GLOBALS['STR_PRODUCT'] . '</td>
						<td class="bill_cell_title">' . $GLOBALS['STR_MODULE_FACTURES_CATALOGUE_PRICE'] . ' ' . $GLOBALS['STR_TTC'] . '</td>
						<td class="bill_cell_title">' . $GLOBALS['STR_SOLD_PRICE'] . ' ' . $GLOBALS['STR_TTC'] . '</td>
						<td class="bill_cell_title">' . $GLOBALS['STR_QUANTITY'] . '</td>
						<td class="bill_cell_title">' . $GLOBALS['STR_TOTAL_TTC'] . '</td>';
	if (!is_micro_entreprise_module_active()) {
		$output .= '
						<td class="bill_cell_title">' .$GLOBALS['STR_VAT'] . '</td>';
	}
	$output .= '
					</tr>
	';

	$product_infos_array = get_product_infos_array_in_order($id, $commande->devise, get_float_from_user_input(vn($commande->currency_rate)));
	foreach ($product_infos_array as $this_ordered_product) {
		$reference = $this_ordered_product["reference"];
		$prix_cat = fprix($this_ordered_product["prix_cat"], true, $commande->devise, true, get_float_from_user_input(vn($commande->currency_rate)));
		$prix = fprix($this_ordered_product["prix"], true, $commande->devise, true, get_float_from_user_input(vn($commande->currency_rate)));
		$quantite = $this_ordered_product['quantite'];
		if(empty($GLOBALS['site_parameters']['allow_float_quantity']) || intval($quantite) == floatval($quantite)) {
			$quantite = intval($quantite);
		}
		$total_prix = fprix($this_ordered_product["total_prix"], true, $commande->devise, true, get_float_from_user_input(vn($commande->currency_rate)));
		$tva = $this_ordered_product['tva_percent'];

		$output .= '
					<tr>
						<td style="width:90px" class="bill_cell center">' . $reference . '</td>
						<td class="left bill_cell">' . str_replace("\n", '<br />', $this_ordered_product["product_text"]) . '</td>
						<td style="width:90px" class="bill_cell right">' . $prix_cat . '</td>
						<td style="width:70px" class="bill_cell right">' . $prix . '</td>
						<td style="width:70px" class="bill_cell center">' . $quantite . '</td>
						<td style="width:70px" class="bill_cell right">' . $total_prix . '</td>';
		if (!is_micro_entreprise_module_active()) {
			$output .= '
						<td style="width:70px" class="bill_cell right">' . $tva . ' %</td>';
		}
		$output .= '
					</tr>
	';
	}
	$output .= '
				</table>
				<table class="full_width" cellpadding="5">
					<tr>
						<td class="right" style="width:80%" >' . $GLOBALS['STR_SHIPPING_COST'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</td>
						<td class="right">' . $order_infos['net_infos_array']['displayed_cout_transport'] . '</td>
					</tr>
					<tr>
						<td class="right" style="width:80%">' . $GLOBALS['STR_TOTAL_HT'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</td>
						<td class="right"><b>' . $order_infos['net_infos_array']['montant_ht'] . '</b></td>
					</tr>';

	if (!is_micro_entreprise_module_active()) {
		$output .= '
					<tr>
						<td class="right" style="width:80%">' . $GLOBALS['STR_VAT'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</td>
						<td class="right" ><b>' . $order_infos['net_infos_array']['total_tva'] . '</b></td>
					</tr>';
	} else {
		$output .= '
					<tr>
						<td></td>
						<td class="right">' . $GLOBALS['STR_NO_VAT_APPLIABLE'] . '</td>
					</tr>';
	}
	if ($commande->tarif_paiement > 0) {
		$output .= '
					<tr>
						<td class="right" style="width:80%">' . $GLOBALS['STR_MODULE_FACTURES_PAY_COST'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</td>
						<td class="right">+ ' . $order_infos['net_infos_array']['tarif_paiement'] . ' ' . $GLOBALS['site_parameters']['symbole'] . '</td>
					</tr>
	';
	}
	$output .= '
					<tr>
						<td class="right" style="width:80%"><b>' . $GLOBALS['STR_NET'] . '</b>' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</td>
						<td class="bill_cell_to_pay right"><b>' . $order_infos['net_infos_array']['montant'] . '</b></td>
					</tr>
	';
	if (!empty($commande->commentaires)) {
		$output .= '
					<tr>
						<td colspan="2" class="titre" class="bill_cell_title"><b>' . $GLOBALS['STR_COMMENTS'] . '</b></td>
					</tr>
					<tr>
						<td colspan="2">' . String::html_entity_decode_if_needed($commande->commentaires) . '</td>
					</tr>
	';
	}
	$output .= '
				 </table>
	';
	if (!empty($_GET['mode']) && $_GET['mode'] == 'bdc') {
		if (round($amount_to_pay, 2) != round($commande->montant, 2)) {
			$output .= '
				<p><b>' . $GLOBALS['STR_MODULE_FACTURES_WARNING_PARTIAL_PAYMENT'] . ' ' . fprix($amount_to_pay, true, vb($commande->devise), true, get_float_from_user_input(vn($commande->currency_rate))) . ' ' . $GLOBALS['STR_TTC'] . '</p>';
		}
		// Affichage du mode de paiement défini pour cette commande, ou de tous les modes de paiement si aucun défini (seulement si commande passée dans l'administration)
		$output .= '
				<table class="full_width" cellpadding="10">
					<tr>
						<td colspan="2">' . get_payment_form($commande->id, $commande->paiement, false, $amount_to_pay, false) . '</td>
					</tr>
				</table>
';
	}
	$output .= '
			</td>
		</tr>
	';
	if (!empty($_GET['mode']) && $_GET['mode'] == 'bdc') {
		$output .= '
		<tr>
			<td class="center">
				<table cellpadding="10" style="width:350px">
					<tr>
						<td class="bill_cell_title">' . $GLOBALS['STR_ACCORD'] . '</td>
					</tr>
					<tr>
						<td class="bill_cell left">
							<div class="left">
								<p><i>' . $GLOBALS['STR_DATE'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</i></p>
							</div>
							<div class="left">
								<p><i>' . $GLOBALS['STR_ACCORD_OK'] . '</i></p>
							</div>
							<div class="left">
								<p><i>' . $GLOBALS['STR_SIGNATURE'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</i></p>
							</div>
							<div class="center" style="height:50px">
								<p>&nbsp;</p>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	';
	}
	$output .= '
	</table>
</div>
';
	$title = $libelle . " " . $GLOBALS['STR_NUMBER'] . " " . $numero . " - " . get_formatted_date($date_document) ;
	output_light_html_page($output, $title);
} else {
	echo '<h1>' . $GLOBALS['STR_NO_ORDER'] . '</h1>';
}

?>
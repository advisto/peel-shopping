<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an		|
// | opensource GPL license: you are allowed to customize the code			|
// | for your own needs, but must keep your changes under GPL				|
// | More information: https://www.peel.fr/lire/licence-gpl-70.html			|
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/		|
// +----------------------------------------------------------------------+
// $Id: commande_html.php 55332 2017-12-01 10:44:06Z sdelaporte $
include("../../configuration.inc.php");

define('IN_INVOICE_HTML', true);
if(!empty($GLOBALS['site_parameters']['require_login_for_html_bill'])) {
	necessite_identification();
}
if (!check_if_module_active('factures', '/commande_html.php') || is_user_bot()) {
	// This module is not activated or this user is a bot => we redirect to the homepage
	redirect_and_die(get_url('/'));
}

/* Charge les détails d'une commande et les affiche */
$auto_refresh = false;
if (!empty($_GET['code_facture'])) {
	$code_facture = $_GET['code_facture'];
	$sql = 'SELECT c.*, sp.technical_code AS statut_paiement
		FROM peel_commandes c
		LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND ' . get_filter_site_cond('statut_paiement', 'sp') . '
		WHERE HEX(c.code_facture) = HEX("' . nohtml_real_escape_string($code_facture) . '") AND ' . get_filter_site_cond('commandes', 'c') . '';
} elseif ((!empty($_GET['id']) && !empty($_GET['timestamp']))) {
	$id = intval($_GET['id']);
	$timestamp = $_GET['timestamp'];
	$sql = 'SELECT  c.*, sp.technical_code AS statut_paiement
		FROM peel_commandes c
		LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND ' . get_filter_site_cond('statut_paiement', 'sp') . '
		WHERE c.id = "' . intval($id) . '" AND c.o_timestamp = "' . nohtml_real_escape_string($timestamp) . '" AND ' . get_filter_site_cond('commandes', 'c') . '';
} else {
	die();
}

$qid_commande = query($sql);

if ($commande = fetch_object($qid_commande)) {
	if (!empty($GLOBALS['site_parameters']['bill_redirect_html_to_pdf']) && !empty($commande->numero)) {
		// On ne veut pas afficher la page pour les factures (commande avec un numéro de facture.). Donc on redirige vers la facture PDF
		redirect_and_die(get_site_wwwroot($commande->site_id) . '/factures/commande_pdf.php?mode=facture&code_facture=' . $commande->code_facture);
	}
	$_SESSION['session_last_bill_viewed'] = $commande->id;
	$output = '';
	$GLOBALS['site_parameters']['css'] = 'html_bill.css';
	$order_infos = get_order_infos_array($commande);
	$client = get_user_information($commande->id_utilisateur);
	$id = intval($commande->id);
	$numero = intval($commande->order_id);
	if(empty($commande->o_timestamp) || substr($commande->o_timestamp, 0, 10) == '0000-00-00') {
		// On a besoin d'une date à afficher par défaut : si pas de date de commande, alors on prend la date du jour
		$commande->o_timestamp = date('Y-m-d H:i:s');
	}
	if (!empty($_GET['mode']) && $_GET['mode'] == 'bdc') {
		$displayed_date = get_formatted_date($commande->o_timestamp, 'short', vb($GLOBALS['site_parameters']['order_hour_display_mode'], 'long'));
	} else {
		// On veut une date de facture si possible et pas de commande
		if(!empty($commande->f_datetime) && StringMb::substr($commande->f_datetime, 0, 10) != '0000-00-00') {
			// Une date de facture est définie
			$displayed_date = get_formatted_date($commande->f_datetime, 'short');
		} else {
			// Pas de date de facture, on indique la date de commande
			$displayed_date = $GLOBALS['STR_ORDER_NAME'] . $GLOBALS["STR_BEFORE_TWO_POINTS"] . ': ' . get_formatted_date($commande->o_timestamp, 'short', vb($GLOBALS['site_parameters']['order_hour_display_mode'], 'long'));
		}
	}
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
				<h1 property="name" class="bill_title">' . $libelle . " " . $GLOBALS['STR_NUMBER'] . " " . $numero . " - " . $displayed_date . "" . '</h1>
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
									<td class="bill_cell">' . StringMb::nl2br_if_needed($order_infos['client_infos_bill']) . '</td>
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
									<td class="bill_cell">' . StringMb::nl2br_if_needed($order_infos['client_infos_ship']) . '</td>
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
	if (!check_if_module_active('micro_entreprise')) {
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
		if (!check_if_module_active('micro_entreprise')) {
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
					</tr>
					<tr>
						<td class="right" style="width:80%" >' . $GLOBALS['STR_PDF_AVOIR'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</td>
						<td class="right">' . $order_infos['net_infos_array']['avoir'] . '</td>
					</tr>';

	if (!check_if_module_active('micro_entreprise')) {
		$output .= '
					<tr>
						<td class="right" style="width:80%">' . $GLOBALS['STR_VAT'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</td>
						<td class="right" ><b>' . $order_infos['net_infos_array']['total_tva'] . '</b></td>
					</tr>';
	}
	if (floatval($order_infos['net_infos_array']['total_tva'])==0) {
		if (check_if_module_active('micro_entreprise')) {
			// Pour les entreprises bénéficiant du régime de franchise de base de TVA, il faut obligatoirement porter sur chaque facture la mention suivante : « TVA non applicable, article 293 B du CGI ».
			$output .= '
						<tr>
							<td class="right" colspan="2">' . $GLOBALS['STR_NO_VAT_APPLIABLE'] . '</td>
						</tr>';
		} elseif(is_user_tva_intracom_for_no_vat($commande->id_utilisateur)) {
			// Pour les livraisons de biens intracommunautaires, les factures doivent obligatoirement comporter la mention suivante : « Exonération de TVA, article 262 ter 1 du CGI ».
			// Lorsqu'il s'agit de prestations de services intracommunautaires dont la taxe est autoliquidée par le preneur, il faudra faire figurer, à notre sens, les mentions « TVA due par le preneur, art. CGI 283-2, et art. 194 de la directive TVA 2006/112/CE »
			// => Texte à définir en conséquence en fonction de votre site dans $GLOBALS['STR_INVOICE_BOTTOM_TEXT2']
			$output .= '
						<tr>
							<td class="right" colspan="2">' . $GLOBALS['STR_INVOICE_BOTTOM_TEXT2'] . '</td>
						</tr>';

		}
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
						<td colspan="2">' . StringMb::html_entity_decode_if_needed(nl2br($commande->commentaires)) . '</td>
					</tr>
	';
	}
	$output .= '
				 </table>
	';
	if (!empty($_GET['mode']) && $_GET['mode'] == 'bdc') {
		// On raffraichit régulièrement la page pour éviter d'avoir un problème de timestamp entre le formulaire et la banque
		$auto_refresh = true;
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
	$title = $libelle . " " . $GLOBALS['STR_NUMBER'] . " " . $numero . " - " . $displayed_date ;
	output_light_html_page($output, $title, ($auto_refresh ? '<meta http-equiv="refresh" content="900; url='. get_current_url() . '" />' : ''));
} else {
	echo '<h1 property="name">' . $GLOBALS['STR_NO_ORDER'] . '</h1>';
}


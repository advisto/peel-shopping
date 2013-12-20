<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: export_ventes.php 39392 2013-12-20 11:08:42Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales,admin_webmastering");

if (!empty($_GET['mode'])) {
	$mode = $_GET['mode'];
} else {
	$mode = '';
}

if (!empty($_GET['encoding'])) {
	$page_encoding = $_GET['encoding'];
} elseif (!empty($GLOBALS['site_parameters']['export_encoding'])) {
	$page_encoding = $GLOBALS['site_parameters']['export_encoding'];
} else {
	$page_encoding = 'utf-8';
}
$output = '';
$filename = "export_ventes_" . str_replace('/', '-', date($GLOBALS['date_basic_format_short'])) . ".csv";
// On ne veut pas polluer le fichier exporté par un quelconque message d'erreur
@ini_set('display_errors', 0);
output_csv_http_export_header($filename, 'csv', $page_encoding);

if (empty($_GET["dateadded1"]) || empty($_GET["dateadded2"])) {
	die();
}
if (!empty($_GET["id_statut_paiement"])) {
	$extra_sql = "AND id_statut_paiement = '" . intval($_GET["id_statut_paiement"]) . "'";
} else {
	$extra_sql = "";
}
$sqlC = "SELECT *
	FROM peel_commandes
	WHERE id_ecom = '" . intval($GLOBALS['site_parameters']['id']) . "' AND o_timestamp>='" . nohtml_real_escape_string($_GET["dateadded1"]) . "' AND o_timestamp<='" . nohtml_real_escape_string($_GET["dateadded2"]) . "' " . $extra_sql . "
	ORDER BY o_timestamp";

$ensemble_cout_transport = $ensemble_total_ht = $ensemble_total_ttc = $super_total = 0;

$total_transport = 0;
$total_transport_ht = 0;
$total_ht = 0;
$total_tva = 0;
$total = 0;

$ligne_total_produit_ht = $ligne_total_produit_ttc = 0;
$ligne_cout_transport_ht = $ligne_tva_cout_transport = $ligne_cout_transport = 0;
$ligne_tarif_paiement_ht = $ligne_tva_tarif_paiement = $ligne_tarif_paiement = 0;

if ($mode != 'one_line_per_order') {
	$output .= "Numéro commande\tDate de vente\tNom de l'acheteur\tAdresse\tVille\tCode postal\tPays\tArticle\tQuantité\tPrix unitaire HT\tTotal HT\tTaux TVA\tTVA\tTotal TTC\tFrais port HT\tTVA Frais de port\tFrais port TTC\tTarif paiement HT\tTVA Tarif paiement\tTarif paiement\tMode de paiement\r\n";
} else {
	$output .= "Numéro commande\tNuméro de facture\tDate de vente\tNom de l'acheteur\tAdresse\tVille\tCode postal\tPays\tTotal HT\tTaux TVA\tTotal TTC\tFrais port HT\tTVA Frais de port\tFrais port TTC\tTarif paiement HT\tTVA Tarif paiement\tTarif paiement\tMode de paiement\tTotal HT des produits\tTVA des produits\tTotal des produits\r\n";
}
$resC = query($sqlC);

while ($commande = fetch_assoc($resC)) {
	$i = 0;

	$numero = $commande['id'];

	$date_vente = get_formatted_date($commande['o_timestamp'], 'short', 'long');
	$nom_acheteur = String::htmlspecialchars_decode($commande['nom_bill'], ENT_QUOTES);
	$adresse = String::htmlspecialchars_decode($commande['adresse_bill'], ENT_QUOTES);
	$ville = String::htmlspecialchars_decode($commande['ville_bill'], ENT_QUOTES);
	$code_postal = $commande['zip_bill'];
	$pays = String::htmlspecialchars_decode($commande['pays_bill'], ENT_QUOTES);

	$total_transport += $commande['cout_transport'];
	$total_transport_ht += $commande['cout_transport_ht'];
	$total_tva += $commande['total_tva'];
	$total_ht += $commande['montant_ht'];
	$total += $commande['montant'];

	$vat_arrays[] = get_vat_array($commande['code_facture']);

	if ($mode != 'one_line_per_order') {
		$product_infos_array = get_product_infos_array_in_order($commande['id'], $commande['devise'], $commande['currency_rate']);
		foreach ($product_infos_array as $this_ordered_product) {
			if ($this_ordered_product['quantite'] != 0) {
				$article = $this_ordered_product['nom_produit'];

				$cout_transport = ($i == 0) ? $commande['cout_transport'] : "";
				$cout_transport_ht = ($i == 0) ? $commande['cout_transport_ht'] : "";
				$tva_cout_transport = $cout_transport - $cout_transport_ht;
				$tarif_paiement = ($i == 0) ? $commande['tarif_paiement'] : "";
				$tarif_paiement_ht = ($i == 0) ? $commande['tarif_paiement_ht'] : "";
				$tva_tarif_paiement = $tarif_paiement - $tarif_paiement_ht;

				$ligne_total_produit_ttc += $this_ordered_product['total_prix'];
				$ligne_total_produit_ht += $this_ordered_product['total_prix_ht'];
				$ligne_cout_transport_ht += $cout_transport_ht;
				$ligne_cout_transport += $cout_transport;
				$ligne_tarif_paiement_ht += $tarif_paiement_ht;
				$ligne_tarif_paiement += $tarif_paiement;
				$ligne_tva_cout_transport += $tva_cout_transport;
				$ligne_tva_tarif_paiement += $tva_tarif_paiement;

				$output .= intval($commande['id']) . "\t" . filtre_csv($date_vente) . "\t" . filtre_csv($nom_acheteur) . "\t" . filtre_csv($adresse) . "\t" . filtre_csv($ville) . "\t" . filtre_csv($code_postal) . "\t" . filtre_csv($pays) . "\t" . filtre_csv($article) . "\t" . filtre_csv($this_ordered_product['quantite']) . "\t" . fxsl($this_ordered_product['prix_ht']) . "\t" . fxsl($this_ordered_product['total_prix_ht']) . "\t" . fxsl($this_ordered_product['tva_percent']) . "\t" . fxsl($this_ordered_product['total_prix'] - $this_ordered_product['total_prix_ht']) . "\t" . fxsl($this_ordered_product['total_prix']) . "";
				$output .= "\t" . vb(fxsl($cout_transport_ht)) . "\t" . vb(fxsl($tva_cout_transport)) . "\t" . vb(fxsl($cout_transport)) . "";
				$output .= "\t" . vb(fxsl($tarif_paiement_ht)) . "\t" . vb(fxsl($tva_tarif_paiement)) . "\t" . vb(fxsl($tarif_paiement)) . "";
				$output .= "\t" . filtre_csv($commande['paiement']);
				$output .= "\r\n";

				$i++;
			}
		}
	} else {
			$output .= intval($commande['id']) . "\t" .filtre_csv($commande['numero']) . "\t" . filtre_csv($date_vente) . "\t" . filtre_csv($nom_acheteur) . "\t" . filtre_csv($adresse) . "\t" . filtre_csv($ville) . "\t" . filtre_csv($code_postal) . "\t" . filtre_csv($pays) . "\t" . filtre_csv($commande['montant_ht']) . "\t" . filtre_csv($commande['total_tva']) . "\t" . filtre_csv($commande['montant']) . "\t" .  filtre_csv($commande['cout_transport_ht']) . "\t" .  filtre_csv($commande['tva_cout_transport']) . "\t" .  filtre_csv($commande['cout_transport']) ."\t" .  filtre_csv($commande['tarif_paiement']) ."\t" .  filtre_csv($commande['tva_tarif_paiement']) . "\t" . filtre_csv($commande['tarif_paiement_ht']) ."\t" .  filtre_csv($commande['paiement']) . "\t" . filtre_csv($commande['total_produit_ht']) . "\t" . filtre_csv($commande['tva_total_produit']) . "\t" . filtre_csv($commande['total_produit']) ."\r\n";
	}
}

if ($mode != 'one_line_per_order') {
	$output .= "\r\n\t\t\t\t\t\t\t\t\tTOTAUX :\t" . fxsl($ligne_total_produit_ht) . "\t\t" . fxsl($ligne_total_produit_ttc - $ligne_total_produit_ht) . "\t" . fxsl($ligne_total_produit_ttc) . "\t" . fxsl($ligne_cout_transport_ht) . "\t" . fxsl($ligne_tva_cout_transport) . "\t" . fxsl($ligne_cout_transport) . "\t" . fxsl($ligne_tarif_paiement_ht) . "\t" . fxsl($ligne_tva_tarif_paiement) . "\t" . fxsl($ligne_tarif_paiement) . "\r\n\r\n";

	$output .= "\t\t\t\t\t\t\t\t\tTOTAL HT tout compris :\t" . fxsl($ligne_total_produit_ht + $ligne_cout_transport_ht + $ligne_tarif_paiement_ht) . "\r\n";
	$output .= "\t\t\t\t\t\t\t\t\tTVA tout compris :\t" . fxsl(($ligne_total_produit_ttc - $ligne_total_produit_ht) + $ligne_tva_cout_transport + $ligne_tva_tarif_paiement) . "\r\n";
	$output .= "\t\t\t\t\t\t\t\t\tTOTAL TTC tout compris :\t" . fxsl($ligne_total_produit_ttc + $ligne_cout_transport + $ligne_tarif_paiement) . "\r\n";
} 
echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);

?>
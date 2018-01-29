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
// $Id: export_ventes.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales,admin_webmastering");

// On ne veut pas polluer le fichier exporté par un quelconque message d'erreur
error_reporting(0);
if (!empty($_GET['mode'])) {
	$mode = $_GET['mode'];
} elseif(!empty($_POST['mode'])) {
	$mode = $_POST['mode'];
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
if (!empty($_GET['mode']) && $_GET['mode']=="affiche_liste_clients_par_produit" && !empty($_GET['id'])) {
	// Export des clients qui ont acheté le produit $_GET['id']
	if (empty($GLOBALS['site_parameters']['cegid_order_export'])) {
		$filename = "export_ventes_" . str_replace('/', '-', date($GLOBALS['date_basic_format_short'])) . ".csv";
	} else {
		$cegid_date = StringMB::strtolower(StringMb::substr(date('F', strtotime($_GET["dateadded1"])),0,4)).date('y', time());
		$filename = "jdv_" . $cegid_date . ".csv";
	}
	output_csv_http_export_header($filename, 'csv', $page_encoding);
	$clients_array = affiche_liste_clients_par_produit($_GET['id'], true);
	$output .= $GLOBALS['STR_LAST_NAME']."\t".$GLOBALS['STR_FIRST_NAME']."\t".$GLOBALS['STR_ADDRESS']."\t".$GLOBALS['STR_TOWN']."\t".$GLOBALS['STR_EMAIL']."\t".$GLOBALS['STR_TELEPHONE']."\r\n";
	foreach($clients_array as $this_client) {
		$output .= $this_client['nom_famille']."\t".$this_client['prenom']."\t".$this_client['adresse']."\t".$this_client['ville']."\t".$this_client['email']."\t". $this_client['telephone']."\r\n";
	}
} else {
	// export des ventes selon un filtre de date et de statut de paiement
	$filename = "export_ventes_" . str_replace('/', '-', date($GLOBALS['date_basic_format_short'])) . ".csv";
	output_csv_http_export_header($filename, 'csv', $page_encoding);
	if (empty($_GET["dateadded1"]) || empty($_GET["dateadded2"])) {
		die();
	}
	if (!empty($_GET["id_statut_paiement"])) {
		$extra_sql = "AND id_statut_paiement = '" . intval($_GET["id_statut_paiement"]) . "'";
	} else {
		$extra_sql = "";
}
if (!empty($_POST['export_selected_order']) && !empty($_POST['order_id'])) {
	$sql_cond = "AND c.id IN (".implode(',', nohtml_real_escape_string($_POST['order_id'])).")";
} else {
	$sql_cond = "AND c." . word_real_escape_string($_GET["date_field"]) . ">='" . nohtml_real_escape_string($_GET["dateadded1"]) . "' AND c." . word_real_escape_string($_GET["date_field"]) . "<='" . nohtml_real_escape_string($_GET["dateadded2"]) . "' " . $extra_sql . "";
}
$sqlC = "SELECT *
	FROM peel_commandes c
	WHERE " . get_filter_site_cond('commandes', 'c', true) . " " . $sql_cond . " 
	ORDER BY c." . word_real_escape_string(vb($_GET["date_field"], 'o_timestamp')) . "";

	$ensemble_cout_transport = $ensemble_total_ht = $ensemble_total_ttc = $super_total = 0;

	$total_transport = 0;
	$total_transport_ht = 0;
	$total_ht = 0;
	$total_tva = 0;
	$total = 0;
	$netapayer = 0;

	$ligne_total_produit_ht = $ligne_total_produit_ttc = 0;
	$ligne_cout_transport_ht = $ligne_tva_cout_transport = $ligne_cout_transport = 0;
	$ligne_tarif_paiement_ht = $ligne_tva_tarif_paiement = $ligne_tarif_paiement = 0;
	if (empty($GLOBALS['site_parameters']['cegid_order_export'])) {
		if ($mode != 'one_line_per_order' && $mode != 'one_line_per_product' && $mode != 'chronopost') {
			$output .= "Numéro commande\tDate de vente\tNom de l'acheteur\tSociété\tAdresse\tVille\tCode postal\tPays\tArticle\tQuantité\tPrix unitaire HT\tTotal HT\tTaux TVA\tTVA\tTotal TTC\tFrais port HT\tTVA Frais de port\tFrais port TTC\tTarif paiement HT\tTVA Tarif paiement\tTarif paiement\tMode de paiement\r\n";
		} elseif($mode == 'one_line_per_product') {
			$output .= "Produit\tQuantite\r\n";
		} elseif($mode != 'chronopost') {
			if (!empty($GLOBALS['site_parameters']['export_order_custom_field']) && is_array($GLOBALS['site_parameters']['export_order_custom_field'])) {
				// $GLOBALS['site_parameters']['export_order_custom_field'] : array('nom_de_la_variable'=>'Nom du champ')
				$output .= implode("\t", $GLOBALS['site_parameters']['export_order_custom_field']) . "\r\n";
			} else {
				$output .= "Numéro commande\tNuméro de facture\tDate de vente\tNom de l'acheteur\tSociété\tAdresse\tVille\tCode postal\tPays\tTotal HT\tTaux TVA\tTotal TTC\tAvoir client\tNet à payer\tFrais port HT\tTVA Frais de port\tFrais port TTC\tTarif paiement HT\tTVA Tarif paiement\tTarif paiement\tMode de paiement\tTotal HT des produits\tTVA des produits\tTotal des produits\r\n";
			}
		}
	} else {
		$output .= "Journal;Date;Général;Auxiliaire;Référence;Libellé;Crédit;Débit\r\n";
	}
	$resC = query($sqlC);

	$output_array = array();
	while ($commande = fetch_assoc($resC)) {
		$i = 0;

		$numero = $commande['id'];
		if (!empty($commande['numero'])) {
			$numero_facture = $commande['numero'];
		} else {
			$numero_facture = $commande['id'];
		}

		$date_vente = get_formatted_date($commande['o_timestamp'], 'short', 'long');
		$date_achat = get_formatted_date($commande['a_timestamp'], 'short', 'long');
		$nom_acheteur = StringMb::htmlspecialchars_decode($commande['nom_bill'], ENT_QUOTES);
		$societe = StringMb::htmlspecialchars_decode(str_replace("\t","",$commande['societe_bill']), ENT_QUOTES);
		$adresse = StringMb::htmlspecialchars_decode($commande['adresse_bill'], ENT_QUOTES);
		$ville = StringMb::htmlspecialchars_decode($commande['ville_bill'], ENT_QUOTES);
		$code_postal = $commande['zip_bill'];
		$pays = StringMb::htmlspecialchars_decode($commande['pays_bill'], ENT_QUOTES);

		$total_transport += $commande['cout_transport'];
		$total_transport_ht += $commande['cout_transport_ht'];
		$total_tva += $commande['total_tva'];
		$total_ht += $commande['montant_ht'];
		$total += $commande['montant']+$commande['avoir'];
		$netapayer += $commande['montant'];

		$vat_arrays[] = get_vat_array($commande['code_facture']);
		if ($mode != 'one_line_per_order' && $mode != 'one_line_per_product' && $mode != 'chronopost') {
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

					$i++;
					if (empty($GLOBALS['site_parameters']['cegid_order_export'])) {
						$output .= intval($commande['id']) . "\t" . filtre_csv($date_vente) . "\t" . filtre_csv($nom_acheteur) . "\t" . filtre_csv($adresse) . "\t" . filtre_csv($ville) . "\t" . filtre_csv($code_postal) . "\t" . filtre_csv($pays) . "\t" . filtre_csv($article) . "\t" . filtre_csv($this_ordered_product['quantite']) . "\t" . fxsl($this_ordered_product['prix_ht']) . "\t" . fxsl($this_ordered_product['total_prix_ht']) . "\t" . fxsl($this_ordered_product['tva_percent']) . "\t" . fxsl($this_ordered_product['total_prix'] - $this_ordered_product['total_prix_ht']) . "\t" . fxsl($this_ordered_product['total_prix']) . "";
						$output .= "\t" . vb(fxsl($cout_transport_ht)) . "\t" . vb(fxsl($tva_cout_transport)) . "\t" . vb(fxsl($cout_transport)) . "";
						$output .= "\t" . vb(fxsl($tarif_paiement_ht)) . "\t" . vb(fxsl($tva_tarif_paiement)) . "\t" . vb(fxsl($tarif_paiement)) . "";
						$output .= "\t" . filtre_csv($commande['paiement']);
						$output .= "\r\n";
					} else {
						$first_reference_caractere = StringMb::substr($this_ordered_product['reference'],0,1);
						$general = ($first_reference_caractere == 0)?'706100':'70710'.$first_reference_caractere;
						$output .= "VEN;".get_formatted_date($commande['f_datetime'], 'short').";".filtre_csv($general).";;".intval($numero_facture).";".filtre_csv($commande['nom_bill']).";".fxsl($this_ordered_product['total_prix_ht']+$this_ordered_product['total_prix_attribut_ht']).";\r\n";
					}
				}
			}
		} elseif($mode=="chronopost") {
			$output .=';'.filtre_csv($commande['societe_ship'], ';').';'.filtre_csv($commande['nom_ship'], ';').';'.filtre_csv($commande['prenom_ship'], ';').';'.filtre_csv($commande['adresse_ship'], ';').';;;'.filtre_csv($commande['zip_ship'], ';').';'. filtre_csv($commande['ville_ship'], ';').';'.filtre_csv(get_country_iso_2_letter_code($commande['pays_ship']), ';').';'.filtre_csv($commande['telephone_ship'], ';').';'.filtre_csv($commande['email_ship'], ';').';'.filtre_csv($commande['id'], ';').';;'.filtre_csv(vn($GLOBALS['site_parameters']['order_chronopost_export_default_product']), ';').';'.filtre_csv(vn($GLOBALS['site_parameters']['chronopost_contract_number']), ';').';'.filtre_csv(vn($GLOBALS['site_parameters']['chronopost_sub_account_contract_number']), ';').';;;M;;;;'.filtre_csv($commande['total_poids'], ';').';;;;;;'. filtre_csv(date('d-m-Y', strtotime($commande['o_timestamp']) + 3600*24*vn($GLOBALS['site_parameters']['order_date_delivery_delay'],2)), ';').';;;;;;;;'."\r\n";;
		} elseif($mode == 'one_line_per_product') {
			$product_infos_array = get_product_infos_array_in_order($commande['id'], $commande['devise'], $commande['currency_rate']);
			foreach ($product_infos_array as $this_ordered_product) {
				if (!isset($output_array[$this_ordered_product['nom_produit']])) {
					$output_array[$this_ordered_product['nom_produit']] = 0;
				}
				$output_array[$this_ordered_product['nom_produit']] += $this_ordered_product['quantite'];
			}
		} else {
			if (!empty($GLOBALS['site_parameters']['export_order_custom_field'])) {
				if (in_array('peel_transactions', listTables()) && in_array('reglements', array_keys($GLOBALS['site_parameters']['export_order_custom_field']))) {
					// La variable de réglements est à mettre dans $GLOBALS['site_parameters']['export_order_custom_field']. Elle sera utilisée dans $$this_var plus bas dans le code.
					// Récupération des informations de règlement;
					$sql = "SELECT id, AMOUNT, datetime, type AS payment_technical_code
						FROM peel_transactions
						WHERE ORDER_ID = ".intval($commande['id']);
					$query = query($sql);
					$reglement_array = array();
					while ($result = fetch_assoc($query)) {
						$reglement_array[] = '' . get_formatted_date($result['datetime']) . ' : ' . get_payment_name($result['payment_technical_code']) . ' - ' . $GLOBALS['STR_AMOUNT'] . ' : ' . fprix($result['AMOUNT'], true, $commande['devise'], true, $commande['currency_rate']) . '';
					}
					$reglements = implode('#', $reglement_array);
				}
				foreach(array_keys($GLOBALS['site_parameters']['export_order_custom_field']) as $this_var) {
					// les index du tableau export_order_custom_field doivent avoir le même nom que les variables définies dans le fichier. 
					$output .= filtre_csv($$this_var) . "\t";
				}
				$output .= "\r\n";
			} else {
				$output .= intval($commande['id']) . "\t" .filtre_csv($commande['numero']) . "\t" . filtre_csv($date_vente) . "\t" . filtre_csv($nom_acheteur) . "\t" . filtre_csv($societe) . "\t" . filtre_csv($adresse) . "\t" . filtre_csv($ville) . "\t" . filtre_csv($code_postal) . "\t" . filtre_csv($pays) . "\t" . filtre_csv($commande['montant_ht']) . "\t" . filtre_csv($commande['total_tva']) . "\t" . filtre_csv($commande['montant']+$commande['avoir'])  ."\t" . filtre_csv($commande['avoir']) . "\t"  . filtre_csv($commande['montant']) . "\t" .  filtre_csv($commande['cout_transport_ht']) . "\t" .  filtre_csv($commande['tva_cout_transport']) . "\t" .  filtre_csv($commande['cout_transport']) ."\t" .  filtre_csv($commande['tarif_paiement']) ."\t" .  filtre_csv($commande['tva_tarif_paiement']) . "\t" . filtre_csv($commande['tarif_paiement_ht']) ."\t" .  filtre_csv($commande['paiement']) . "\t" . filtre_csv($commande['total_produit_ht']) . "\t" . filtre_csv($commande['tva_total_produit']) . "\t" . filtre_csv($commande['total_produit']) ."\r\n";
			}
		}
	}
	if (empty($GLOBALS['site_parameters']['cegid_order_export']) && $mode!="chronopost") {
		if ($mode != 'one_line_per_order' && $mode != 'one_line_per_product') {
			$output .= "\r\n\t\t\t\t\t\t\t\t\tTOTAUX :\t" . fxsl($ligne_total_produit_ht) . "\t\t" . fxsl($ligne_total_produit_ttc - $ligne_total_produit_ht) . "\t" . fxsl($ligne_total_produit_ttc) . "\t" . fxsl($ligne_cout_transport_ht) . "\t" . fxsl($ligne_tva_cout_transport) . "\t" . fxsl($ligne_cout_transport) . "\t" . fxsl($ligne_tarif_paiement_ht) . "\t" . fxsl($ligne_tva_tarif_paiement) . "\t" . fxsl($ligne_tarif_paiement) . "\r\n\r\n";

			$output .= "\t\t\t\t\t\t\t\t\tTOTAL HT tout compris :\t" . fxsl($ligne_total_produit_ht + $ligne_cout_transport_ht + $ligne_tarif_paiement_ht) . "\r\n";
			$output .= "\t\t\t\t\t\t\t\t\tTVA tout compris :\t" . fxsl(($ligne_total_produit_ttc - $ligne_total_produit_ht) + $ligne_tva_cout_transport + $ligne_tva_tarif_paiement) . "\r\n";
			$output .= "\t\t\t\t\t\t\t\t\tTOTAL TTC tout compris :\t" . fxsl($ligne_total_produit_ttc + $ligne_cout_transport + $ligne_tarif_paiement) . "\r\n";
		} elseif($mode == 'one_line_per_product' && !empty($output_array)) {
			foreach($output_array as $product_name => $total_quantity) {
				$output .= filtre_csv($product_name) . "\t" .intval($total_quantity). "\r\n";
			}
		}
	}
}
echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);

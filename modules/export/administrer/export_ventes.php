<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: export_ventes.php 59873 2019-02-26 14:47:11Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_white_label,admin_sales,admin_webmastering");

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
	if (!empty($_GET['zone_id'])) {
		$zone_id = $_GET['zone_id'];
		$res_zone = query("SELECT z.nom_" . $_SESSION['session_langue'] . " AS nom
			FROM peel_zones z
			WHERE " . get_filter_site_cond('zones', 'z', true) . " AND id = '" . intval($zone_id) . "'");
		if($result = fetch_assoc($res_zone)){
			$sql_cond .= " AND (c.zone = '" . intval($zone_id) . "' OR c.zone = '" . nohtml_real_escape_string($result['nom']) . "')";
		}
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
	$small_order_overcost_amount=0;
	$total_general_avoir = 0;
	$total_general_code_promo = 0;
	$total_general_transport = 0;

	$ligne_total_produit_ht = $ligne_total_produit_ttc = 0;
	$ligne_cout_transport_ht = $ligne_tva_cout_transport = $ligne_cout_transport = 0;
	$ligne_tarif_paiement_ht = $ligne_tva_tarif_paiement = $ligne_tarif_paiement = 0;
	if (empty($GLOBALS['site_parameters']['cegid_order_export'])) {
		if (!empty($GLOBALS['site_parameters']['export_order_custom_field']) && is_array($GLOBALS['site_parameters']['export_order_custom_field']) && $mode != 'one_line_per_order' && $mode != 'one_line_per_product') {
			// $GLOBALS['site_parameters']['export_order_custom_field'] : array('nom_de_la_variable'=>'Nom du champ')
			$output .= implode("\t", $GLOBALS['site_parameters']['export_order_custom_field']) . "\r\n";
		} elseif($mode != 'one_line_per_order' && $mode != 'one_line_per_product' && $mode != 'chronopost') {
			$output .= "Numéro commande\tDate de vente\tNom de l'acheteur\tSociété\tAdresse\tVille\tCode postal\tPays\tArticle\tQuantité\tPrix unitaire HT\tTotal HT\tTaux TVA\tTVA\tTotal TTC\tFrais port HT\tTVA Frais de port\tFrais port TTC\tTarif paiement HT\tTVA Tarif paiement\tTarif paiement\tMode de paiement\r\n";
		} elseif($mode == 'one_line_per_product') {
			$output .= "Produit\tQuantite\r\n";
		} elseif($mode != 'chronopost') {
			$output .= "Numéro commande\tNuméro de facture\tDate de vente\tNom de l'acheteur\tSociété\tAdresse\tVille\tCode postal\tPays\tTotal HT\tTaux TVA\tTotal TTC\tAvoir client\tNet à payer\tFrais port HT\tTVA Frais de port\tFrais port TTC\tTarif paiement HT\tTVA Tarif paiement\tTarif paiement\tMode de paiement\tTotal HT des produits\tTVA des produits\tTotal des produits\r\n";
		}
	} else {
		$output .= "Journal;Date;Général;Auxiliaire;Référence;Libellé;Crédit;Débit\r\n";
	}
	//Ancienne génération de toutes les commandes au format CSV
	if($mode == 'only_inline_quantity_order'){
		$output = '';
		$output .= "Quantité\tRéf Produit\tArticle\tTotal HT\tTVA\tTaux TVA\r\n";
		
		// Requête pour lister tous les produits commandés, filtrer sur la date, le statut de paiement et la zone de livraison.
		// Une ligne par référence, il faut additionner les montants et les quantités.
		$sql_uio = "SELECT ca.produit_id, SUM(ca.quantite) as quantite, SUM(ca.total_prix) as total_prix, SUM(ca.total_prix_ht) as total_prix_ht, tva_percent, reference, nom_produit
			FROM peel_commandes_articles ca
			INNER JOIN peel_commandes c ON ca.commande_id = c.id
			WHERE '" . get_filter_site_cond('commandes', 'c') . "' AND c.a_timestamp>='" . nohtml_real_escape_string($_GET["dateadded1"]) . "' AND c.a_timestamp<='" . nohtml_real_escape_string($_GET["dateadded2"]) . "'
			" . $sql_cond . "
			GROUP BY ca.produit_id
			ORDER BY reference";
		$query_tes = query($sql_uio);
		while ($this_ordered_product = fetch_assoc($query_tes)) {
			if ($this_ordered_product['quantite'] != 0) {
			

				$output .= filtre_csv($this_ordered_product['quantite']);
				$output .= "\t" . filtre_csv($this_ordered_product['reference']);
				$output .= "\t" . filtre_csv(StringMB::htmlspecialchars_decode(StringMB::html_entity_decode_if_needed($this_ordered_product['nom_produit']), ENT_QUOTES));
				// Prix total HT 
				$output .= "\t" . fxsl($this_ordered_product['total_prix_ht']);
				// Total TVA 
				$output .= "\t" . fxsl($this_ordered_product['total_prix'] - $this_ordered_product['total_prix_ht']);
				// Pourcentage de TVA 
				$output .= "\t" . fxsl($this_ordered_product['tva_percent']);
				$output .= "\r\n";
			}
		}
		$resC = query($sqlC);
		while ($commandes = fetch_assoc($resC)) {
			$product_infos_array = get_product_infos_array_in_order($commandes['id'], $commandes['devise'], $commandes['currency_rate']);
			foreach ($product_infos_array as $this_ordered_product) {
				// test sur quantité !=0 à priori inutile, puisque la quantité 0 supprime les produits de la commande dans le panier en front office, ainsi que dans l'édition de commande en back office.
				if (!isset($total_general_tva[$this_ordered_product['tva_percent']])) {
					// Génération des totaux par pourcentage de TVA.
					$total_general_tva[$this_ordered_product['tva_percent']] = 0;
				}
				$total_general_tva[$this_ordered_product['tva_percent']] += $this_ordered_product['total_prix'] - $this_ordered_product['total_prix_ht'];
				// Totaux des produits commandés (hors réduction donc.)
				$ligne_total_produit_ht += $this_ordered_product['total_prix_ht'];
				$ligne_total_produit_ttc += $this_ordered_product['total_prix'];
			}
		}
		// Récupération des totaux pour les commandes.
		$sql = "SELECT c.*
			FROM peel_commandes c
			WHERE '" . get_filter_site_cond('commandes', 'c') . "' AND c.a_timestamp>='" . nohtml_real_escape_string($_GET["dateadded1"]) . "' AND c.a_timestamp<='" . nohtml_real_escape_string($_GET["dateadded2"]) . "'
			" . $sql_cond . "";
		$query = query($sql);

		while ($this_order_array = fetch_assoc($query)) {
			// Totaux frais de port
			$total_transport += $this_order_array['cout_transport'];
			$total_transport_ht += $this_order_array['cout_transport_ht'];

			// Totaux par tarif de paiement
			$total_tarif_paiement_ttc += $this_order_array['tarif_paiement'];
			$total_tarif_paiement_ht += $this_order_array['tarif_paiement_ht'];

			// Total des montants payés par le client (réductions incluses)
			$total_tva += $this_order_array['total_tva'];
			$total_ht += $this_order_array['montant_ht'];
			$total += $this_order_array['montant'];
			$small_order_overcost_amount += $this_order_array['small_order_overcost_amount'];
			
			// Totaux par moyen de paiement
			if (!empty($this_order_array['paiement'])) {
				$paiement = $this_order_array['paiement'];
			} else {
				$paiement = "Autres";
			}
			if (!isset($total_general_paiement[$paiement])) {
				$total_general_paiement[$paiement] = 0;
			}
			$total_general_paiement[$paiement] += $this_order_array['montant'];

			// Totaux Avoir et code promo
			$total_general_avoir += $this_order_array['avoir'];
			$total_general_code_promo += $this_order_array['valeur_code_promo'];
		}
		
		// Affichage des valeurs calculées
		$output .= "\r\n";
		$output .= "\t\tTotal General vente produit H.T :\t" . fxsl($ligne_total_produit_ht) . "\n";
		foreach($total_general_tva as $taux_tva => $montant) {
			$output .= "\t\tTotal General TVA ".$taux_tva." :\t" . fxsl($montant) . "\n";
		}
		$output .= "\t\tTotal General vente produit T.T.C:\t" . fxsl($ligne_total_produit_ttc) . "\n";
		$output .= "\r\n";
		$output .= "\t\tTotal General du port H.T :\t" . fxsl($total_transport_ht) . "\n";
		$output .= "\t\tTotal General TVA du port :\t" . fxsl($total_transport-$total_transport_ht) . "\n";
		$output .= "\t\tTotal General du port TTC :\t" . fxsl($total_transport) . "\n";
		$output .= "\r\n";
		$output .= "\t\tMontant surcout petite commande :\t" . fxsl($small_order_overcost_amount) . "\n";
		$output .= "\r\n";
		$output .= "\t\tTotal General :\t" . fxsl($total) . "\n";
		$output .= "\r\n";
		$output .= "\t\tTotal General des avoirs TTC :\t" . fxsl($total_general_avoir) . "\n";
		$output .= "\t\tTotal General des codes promos :\t" . fxsl($total_general_code_promo) . "\n";
		$output .= "\r\n";
		$output .= "\r\n";
		foreach($total_general_paiement as $paiment => $montant) {
			$output .= "\t\tTotal General par  ".$paiment." :\t" . fxsl($montant) . "\n";
		}

	} else {
		$resC = query($sqlC);
		while ($commande = fetch_assoc($resC)) {
			$i = 0;

			$numero = $commande['id'];
			if (!empty($commande['numero'])) {
				$numero_facture = $commande['numero'];
			} else {
				$numero_facture = $commande['id'];
			}
					$quantite = $this_ordered_product['quantite'];
					$reference = $this_ordered_product['reference'];
					$total_prix_ht = $this_ordered_product['total_prix_ht'];
					$tva = $this_ordered_product['total_prix']-$this_ordered_product['total_prix_ht'];
					$tva_percent = $this_ordered_product['tva_percent'];

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
			
			// Totaux par moyen de paiement
			if (!empty($commande['paiement'])) {
				$paiement = $commande['paiement'];
			} else {
				$paiement = "Autres";
			}
			if (!isset($total_general_paiement[$paiement])) {
				$total_general_paiement[$paiement] = 0;
			}
			$total_general_paiement[$paiement] += $commande['montant'];
			$small_order_overcost_amount += $commande['small_order_overcost_amount'];
			$total_general_avoir += $commande['avoir'];
			$total_general_code_promo += $commande['valeur_code_promo'];

			$vat_arrays[] = get_vat_array($commande['code_facture']);
			
			if (!empty($GLOBALS['site_parameters']['export_order_custom_field']) && $mode != 'one_line_per_order' && $mode != 'one_line_per_product') {
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
				$product_infos_array = get_product_infos_array_in_order($commande['id'], $commande['devise'], $commande['currency_rate']);
				foreach ($product_infos_array as $this_ordered_product) {
					$article = $this_ordered_product['nom_produit'];
					$quantite = $this_ordered_product['quantite'];
					$reference = $this_ordered_product['reference'];
					$total_prix_ht = fxsl($this_ordered_product['total_prix_ht']);
					$ligne_total_produit_ht += $this_ordered_product['total_prix_ht'];
					$ligne_total_produit_ttc += $this_ordered_product['total_prix'];
					$tva = fxsl($this_ordered_product['total_prix']-$this_ordered_product['total_prix_ht']);
					$tva_percent = fxsl($this_ordered_product['tva_percent']);
					if (!isset($total_general_tva[$this_ordered_product['tva_percent']])) {
						// Génération des totaux par pourcentage de TVA.
						$total_general_tva[$this_ordered_product['tva_percent']] = 0;
					}
					$total_general_tva[$this_ordered_product['tva_percent']] += $this_ordered_product['total_prix'] - $this_ordered_product['total_prix_ht'];
					foreach(array_keys($GLOBALS['site_parameters']['export_order_custom_field']) as $this_var) {
						// les index du tableau export_order_custom_field doivent avoir le même nom que les variables définies dans le fichier.
						$output .= filtre_csv($$this_var) . "\t";
					}
					$output .= "\r\n";
				}
			} elseif ($mode != 'one_line_per_order' && $mode != 'one_line_per_product' && $mode != 'chronopost') {
				$product_infos_array = get_product_infos_array_in_order($commande['id'], $commande['devise'], $commande['currency_rate']);
				foreach ($product_infos_array as $this_ordered_product) {
					if (!isset($total_general_tva[$this_ordered_product['tva_percent']])) {
						// Génération des totaux par pourcentage de TVA.
						$total_general_tva[$this_ordered_product['tva_percent']] = 0;
					}
					$total_general_tva[$this_ordered_product['tva_percent']] += $this_ordered_product['total_prix'] - $this_ordered_product['total_prix_ht'];
					if ($this_ordered_product['quantite'] != 0) {


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
							$output .= intval($commande['id']) . "\t" . filtre_csv($date_vente) . "\t" . filtre_csv($nom_acheteur) . "\t" . filtre_csv($societe) . "\t". filtre_csv($adresse) . "\t" . filtre_csv($ville) . "\t" . filtre_csv($code_postal) . "\t" . filtre_csv($pays) . "\t" . filtre_csv($article) . "\t" . filtre_csv($this_ordered_product['quantite']) . "\t" . fxsl($this_ordered_product['prix_ht']) . "\t" . fxsl($this_ordered_product['total_prix_ht']) . "\t" . fxsl($this_ordered_product['tva_percent']) . "\t" . fxsl($this_ordered_product['total_prix'] - $this_ordered_product['total_prix_ht']) . "\t" . fxsl($this_ordered_product['total_prix']) . "";
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
				$product_infos_array = get_product_infos_array_in_order($commande['id'], $commande['devise'], $commande['currency_rate']);
				foreach ($product_infos_array as $this_ordered_product) {
					$ligne_total_produit_ht += $this_ordered_product['total_prix_ht'];
					$ligne_total_produit_ttc += $this_ordered_product['total_prix'];
				}
				$output .= intval($commande['id']) . "\t" .filtre_csv($commande['numero']) . "\t" . filtre_csv($date_vente) . "\t" . filtre_csv($nom_acheteur) . "\t" . filtre_csv($societe) . "\t" . filtre_csv($adresse) . "\t" . filtre_csv($ville) . "\t" . filtre_csv($code_postal) . "\t" . filtre_csv($pays) . "\t" . fxsl($commande['montant_ht']) . "\t" . fxsl($commande['total_tva']) . "\t" . fxsl($commande['montant']+$commande['avoir'])  ."\t" . fxsl($commande['avoir']) . "\t"  . fxsl($commande['montant']) . "\t" .  fxsl($commande['cout_transport_ht']) . "\t" .  fxsl($commande['tva_cout_transport']) . "\t" .  fxsl($commande['cout_transport']) ."\t" .  fxsl($commande['tarif_paiement']) ."\t" .  fxsl($fxsl['tva_tarif_paiement']) . "\t" . fxsl($commande['tarif_paiement_ht']) ."\t" .  filtre_csv($commande['paiement']) . "\t" . fxsl($commande['total_produit_ht']) . "\t" . fxsl($commande['tva_total_produit']) . "\t" . fxsl($commande['total_produit']) ."\r\n";
			}
		}
	}


	if (empty($GLOBALS['site_parameters']['cegid_order_export']) && $mode!="chronopost" && $mode != 'only_inline_quantity_order') {
		if (!empty($GLOBALS['site_parameters']['export_order_all_total_display']) && (empty($mode) || $mode == 'one_line_per_order')) {
			$output .= "\r\n";
			$output .= "\t\tTotal General vente produit H.T :\t" . fxsl($ligne_total_produit_ht) . "\n";
			foreach($total_general_tva as $taux_tva => $montant) {
				$output .= "\t\tTotal General TVA ".$taux_tva." :\t" . fxsl($montant) . "\n";
			}
			$output .= "\t\tTotal General vente produit T.T.C:\t" . fxsl($ligne_total_produit_ttc) . "\n";
			$output .= "\r\n";
			$output .= "\t\tTotal General du port H.T :\t" . fxsl($total_transport_ht) . "\n";
			$output .= "\t\tTotal General TVA du port :\t" . fxsl($total_transport-$total_transport_ht) . "\n";
			$output .= "\t\tTotal General du port TTC :\t" . fxsl($total_transport) . "\n";
			$output .= "\r\n";
			$output .= "\t\tMontant surcout petite commande :\t" . fxsl($small_order_overcost_amount) . "\n";
			$output .= "\r\n";
			$output .= "\t\tTotal General :\t" . fxsl($total) . "\n";
			$output .= "\r\n";
			$output .= "\t\tTotal General des avoirs TTC :\t" . fxsl($total_general_avoir) . "\n";
			$output .= "\t\tTotal General des codes promos :\t" . fxsl($total_general_code_promo) . "\n";
			$output .= "\r\n";
			$output .= "\r\n";
			foreach($total_general_paiement as $paiment => $montant) {
				$output .= "\t\tTotal General par ".$paiment." :\t" . fxsl($montant) . "\n";
			}
		} elseif ($mode != 'one_line_per_order' && $mode != 'one_line_per_product') {
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

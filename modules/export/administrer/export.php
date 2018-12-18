<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: export.php 59053 2018-12-18 10:20:50Z sdelaporte $

define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();

if (!empty($_GET['encoding'])) {
	$page_encoding = $_GET['encoding'];
} elseif (!empty($GLOBALS['site_parameters']['export_encoding'])) {
	$page_encoding = $GLOBALS['site_parameters']['export_encoding'];
} else {
	$page_encoding = 'utf-8';
}
$output = '';
$output_footer = '';

$results_array = array();
$csv_separator = vb($_GET['csv_separator'], "\t"); // Par défaut

$type = trim(vb($_GET['type']));

if(!empty($_POST['mode'])) {
	// POST a priorité sur GET
	$mode = $_POST['mode'];
} elseif (!empty($_GET['mode'])) {
	$mode = $_GET['mode'];
} else {
	$mode = '';
}

// Format généré : CSV, HTML ou PDF
if($mode == 'export_pdf') {
	$format = 'pdf';
} else {
	$format = vb($_POST['format'], vb($_GET['format'], 'csv'));
}

$field_titles_array['clients'] = array('email' => 'Email', 'nom_famille' => 'Nom', 'prenom' => 'Prénom', 'societe' => 'Société', 'adresse' => 'Adresse', 'code_postal' => 'Code postal', 'ville' => 'Ville', 'telephone' => 'Téléphone');
$user_rights_by_type['clients'] = 'admin_webmastering';

$field_titles_array['livraisons'] = array('nom_ship' => 'Nom', 'prenom_ship' => 'Prénom', 'societe_ship' => 'Société', 'adresse_ship' => 'Adresse', 'zip_ship' => 'Code postal', 'ville_ship' => 'Ville', 'commentaires' => 'Etages', 'pays_ship' => 'Pays', 'poids_calc' => 'Poids', 'article_calc' => 'Article', 'quantite' => 'Quantité', 'transport' => 'Transport', 'id' => 'Commande', 'o_timestamp' => 'Date');
$user_rights_by_type['livraisons'] = 'admin_sales,admin_webmastering';

$user_rights_by_type['ventes'] = 'admin_sales,admin_webmastering';

$user_rights_by_type['produits'] = 'admin_sales,admin_webmastering';

if(empty($_GET['debug'])) {
	// On ne veut pas polluer le fichier exporté par un quelconque message d'erreur
	@ini_set('display_errors', 0);
}

if(empty($type)) {
	necessite_priv("admin*");
} else {
	if(empty($field_titles_array[$type])) {
		die();
	}
	necessite_priv($user_rights_by_type[$type]);
}

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_EXPORT_TITLE'];

$output = handle_export();

if(!empty($output)) {
	include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
	echo $output;
	include($GLOBALS['repertoire_modele'] . "/admin_bas.php");
	die();
}

if($type == 'clients') {
	// CLIENTS
	$cle = trim(vb($_GET['cle']));
	$priv = trim(vb($_GET['priv']));
	if (!empty($_GET['export']) && $_GET['export'] == 'search_user') {
		$sql_csv = afficher_liste_utilisateurs($priv, $cle, $_GET, 'date_insert', false, true);
	} else {
		$sql_csv = "SELECT u.*
			FROM peel_utilisateurs u
			WHERE " . get_filter_site_cond('utilisateurs', 'u', true) . "";
		if (!empty($_GET['priv'])) {
			$sql_csv .= " AND CONCAT('+',u.priv,'+') LIKE '%+" . nohtml_real_escape_string($_GET['priv']) . "+%'";
		}
		if (!empty($cle)) {
			$sql_csv .= " AND (u.code_client LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.email LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.ville LIKE  '%" . nohtml_real_escape_string($cle) . "%' OR u.nom_famille LIKE  '%" . nohtml_real_escape_string($cle) . "%' OR " . get_zip_cond($cle, 'u', false) . ")";
		}
	}	
	// **********
} elseif($type == 'livraisons') {
	// LIVRAISONS
	if (empty($_GET["dateadded1"]) || empty($_GET["dateadded2"])) {
		die();
	}
	if (!empty($_GET["id_statut_livraison"])) {
		$extra_sql = "AND id_statut_livraison = '" . intval($_GET["id_statut_livraison"]) . "'";
	} else {
		$extra_sql = "";
	}
	$sql_csv = "SELECT *, poids*quantite AS poids_calc, CONCAT('reference', ' - ', nom_produit) AS article_calc
		FROM peel_commandes c
		LEFT JOIN peel_commandes_articles ca ON ca.commande_id = c.id AND quantite!=0 AND " . get_filter_site_cond('commandes_articles', null, true) . "
		WHERE o_timestamp>='" . nohtml_real_escape_string($_GET["dateadded1"]) . "' AND o_timestamp<='" . nohtml_real_escape_string($_GET["dateadded2"]) . "' AND " . get_filter_site_cond('commandes', null, true) . " " . $extra_sql . "
		ORDER BY o_timestamp";
	// **********
} elseif($type == 'ventes') {
	// VENTES
	if ($mode == "affiche_liste_clients_par_produit" && !empty($_GET['id'])) {
		// Export des clients qui ont acheté le produit $_GET['id']
		if (!empty($GLOBALS['site_parameters']['cegid_order_export'])) {
			$filename = "jdv_" . StringMB::strtolower(StringMb::substr(date('F', strtotime($_GET["dateadded1"])),0,4)).date('y', time()) . ".csv";
		}
		$results_array = affiche_liste_clients_par_produit($_GET['id'], true);
		$field_titles_array['ventes'] = array('nom_famille' => $GLOBALS['STR_LAST_NAME'], 'prenom' => $GLOBALS['STR_FIRST_NAME'], 'adresse' => $GLOBALS['STR_ADDRESS'], 'ville' => $GLOBALS['STR_TOWN'], 'email' => $GLOBALS['STR_EMAIL'], 'telephone' => $GLOBALS['STR_TELEPHONE']);
	} else {
		// Export des ventes selon un filtre de date et de statut de paiement
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
		$sql_csv = "SELECT *
			FROM peel_commandes c
			WHERE " . get_filter_site_cond('commandes', 'c', true) . " " . $sql_cond . " 
			ORDER BY c." . word_real_escape_string(vb($_GET["date_field"], 'o_timestamp')) . "";

		$total_cout_transport = 0;
		$total_cout_transport_ht = 0;
		$total_ht = 0;
		$total_tva = 0;
		$total = 0;
		$total_netapayer = 0;
		$total_total_produit_ht = 0;
		$total_total_produit = 0;
		$total_tarif_paiement_ht = 0;
		$total_tarif_paiement = 0;
		
		// Format de $field_titles_array["table"] : array('nom_de_la_variable'=>'Nom du champ')
		if($mode == 'one_line_per_product') {
			// Mode simplifié : une ligne par produit commandé
			$field_titles_array['ventes'] = array('Produit', 'Quantite');
		} elseif($mode == 'one_line_per_order') {
			if (!empty($GLOBALS['site_parameters']['export_order_custom_field']) && is_array($GLOBALS['site_parameters']['export_order_custom_field'])) {
				$field_titles_array['ventes'] = $GLOBALS['site_parameters']['export_order_custom_field'];
			} else {
				$field_titles_array['ventes'] = array('id' => 'Numéro commande', 'numero' => 'Numéro de facture', 'date_vente' => 'Date de vente', 'nom_acheteur' => 'Nom de l\'acheteur', 'societe' => 'Société', 'adresse' => 'Adresse', 'ville' => 'Ville', 'code_postal' => 'Code postal', 'pays' => 'Pays', 'montant_ht' => 'Total HT', 'total_tva' => 'Taux TVA', 'montant_avec_avoir' => 'Total TTC', 'avoir' => 'Avoir client', 'montant' => 'Net à payer', 'cout_transport_ht' => 'Frais port HT', 'tva_cout_transport' => 'TVA Frais de port', 'cout_transport' => 'Frais port TTC', 'tarif_paiement' => 'Tarif paiement HT', 'tva_tarif_paiement' => 'TVA Tarif paiement', 'tarif_paiement_ht' => 'Tarif paiement', 'paiement' => 'Mode de paiement', 'total_produit_ht' => 'Total HT des produits', 'tva_total_produit' => 'TVA des produits', 'total_produit' => 'Total des produits');
			}
		} elseif ($mode == 'chronopost') {
			// Pas de titres de colonnes
			$csv_separator = ';';
		} elseif (!empty($GLOBALS['site_parameters']['cegid_order_export'])) {
			// Ici : $mode == ''
			$field_titles_array['ventes'] = array('Journal', 'Date', 'Général', 'Auxiliaire', 'Référence', 'Libellé', 'Crédit', 'Débit');
			$csv_separator = ';';
		} else {
			// Ici : $mode == '' : une ligne par produit commandé
			$field_titles_array['ventes'] = array('Numéro commande', 'Date de vente', 'Nom de l\'acheteur', 'Société', 'Adresse', 'Ville', 'Code postal', 'Pays', 'Article', 'Quantité', 'Prix unitaire HT', 'Total HT', 'Taux TVA', 'TVA', 'Total TTC', 'Frais port HT', 'TVA Frais de port', 'Frais port TTC', 'Tarif paiement HT', 'TVA Tarif paiement', 'Tarif paiement', 'Mode de paiement');
			$add_total_footer = true;
		} 
		
		$query_csv = query($sql_csv);
		unset($sql_csv);
		$output_array = array();
		while ($commande = fetch_assoc($query_csv)) {
			
			if($mode == 'one_line_per_product') {
				// Expport simplifié avec liste des noms de produits et quantité
				$product_infos_array = get_product_infos_array_in_order($commande['id'], $commande['devise'], $commande['currency_rate']);
				foreach ($product_infos_array as $this_ordered_product) {
					if (!isset($output_array[$this_ordered_product['nom_produit']])) {
						$output_array[$this_ordered_product['nom_produit']] = 0;
					}
					$output_array[$this_ordered_product['nom_produit']] += $this_ordered_product['quantite'];
				}
				if(!empty($output_array)) {
					foreach($output_array as $product_name => $total_quantity) {
						$results_array[] = array($product_name, intval($total_quantity));
					}
					unset($output_array);
				} 
			} else {
				// Préparation des données complémentaires à la commande
				$commande['numero'] = $commande['id'];
				if (!empty($commande['numero'])) {
					$commande['numero_facture'] = $commande['numero'];
				} else {
					$commande['numero_facture'] = $commande['id'];
				}

				$commande['date_vente'] = get_formatted_date($commande['o_timestamp'], 'short', 'long');
				$commande['date_achat'] = get_formatted_date($commande['a_timestamp'], 'short', 'long');
				
				// Compatibilité avec anciens PEEL : décodage des entités HTML stockées en BDD
				// Pas besoin de protégé du séparateur CSV, car on applique filtre_csv tout à la fin
				$commande['nom_acheteur'] = StringMb::htmlspecialchars_decode($commande['nom_bill'], ENT_QUOTES);
				$commande['societe'] = StringMb::htmlspecialchars_decode($commande['societe_bill'], ENT_QUOTES);
				$commande['adresse'] = StringMb::htmlspecialchars_decode($commande['adresse_bill'], ENT_QUOTES);
				$commande['ville'] = StringMb::htmlspecialchars_decode($commande['ville_bill'], ENT_QUOTES);
				$commande['code_postal'] = $commande['zip_bill'];
				$commande['pays'] = StringMb::htmlspecialchars_decode($commande['pays_bill'], ENT_QUOTES);

				$commande['montant_avec_avoir'] = $commande['montant']+$commande['avoir'];

				$total_cout_transport += $commande['cout_transport'];
				$total_cout_transport_ht += $commande['cout_transport_ht'];
				$total_tva += $commande['total_tva'];
				$total_ht += $commande['montant_ht'];
				$total += $commande['montant']+$commande['avoir'];
				$total_netapayer += $commande['montant'];

				$commande['vat_arrays'][] = get_vat_array($commande['code_facture']);

				if (in_array('peel_transactions', listTables()) && in_array('reglements', array_keys($field_titles_array['ventes']))) {
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
					$commande['reglements'] = implode('#', $reglement_array);
				}

				if ($mode == 'one_line_per_order') {
					$this_result = array();
					foreach(array_keys($field_titles_array['ventes']) as $this_item) {
						// les index du tableau export_order_custom_field doivent avoir le même nom que les éléments de $commande. 
						$this_result[] = $commande[$this_item];
					}
					$results_array[] = $this_result;
				} elseif($mode == "chronopost") {
					// Une ligne par commande
					$results_array[] = array('', $commande['societe_ship'], $commande['nom_ship'], $commande['prenom_ship'], $commande['adresse_ship'], '', '', '', $commande['zip_ship'], $commande['ville_ship'], get_country_iso_2_letter_code($commande['pays_ship']), $commande['telephone_ship'], $commande['email_ship'], $commande['id'], '', '', vn($GLOBALS['site_parameters']['order_chronopost_export_default_product']), vn($GLOBALS['site_parameters']['chronopost_contract_number']), vn($GLOBALS['site_parameters']['chronopost_sub_account_contract_number']), '', '', 'M', '', '', '', $commande['total_poids'], '', '', '', '', '', date('d-m-Y', strtotime($commande['o_timestamp']) + 3600*24*vn($GLOBALS['site_parameters']['order_date_delivery_delay'],2)), '', '', '', '', '', '', '', '');
				} else {
					// Ici : $mode == '' on affiche sur la première ligne d'une commande le premier produit avec les infos générales de coût de la commande, puis sur les lignes suivantes les produits suivants de la commande sans indication de coût de commande
					$product_infos_array = get_product_infos_array_in_order($commande['id'], $commande['devise'], $commande['currency_rate']);
					$i = 0;
					foreach ($product_infos_array as $this_ordered_product) {
						if ($this_ordered_product['quantite'] != 0) {
							// On affiche le coût de transport et de transaction uniquement sur la ligne du premier produit commandé
							$cout_transport = ($i == 0) ? $commande['cout_transport'] : "";
							$cout_transport_ht = ($i == 0) ? $commande['cout_transport_ht'] : "";
							$tva_cout_transport = $cout_transport - $cout_transport_ht;
							$tarif_paiement = ($i == 0) ? $commande['tarif_paiement'] : "";
							$tarif_paiement_ht = ($i == 0) ? $commande['tarif_paiement_ht'] : "";
							$tva_tarif_paiement = $tarif_paiement - $tarif_paiement_ht;

							$i++;
							if (!empty($GLOBALS['site_parameters']['cegid_order_export'])) {
								$first_reference_caractere = StringMb::substr($this_ordered_product['reference'],0,1);
								$general = ($first_reference_caractere == 0)?'706100':'70710'.$first_reference_caractere;
								$results_array[] = array("VEN", get_formatted_date($commande['f_datetime'], 'short'), $general, '', intval($commande['numero_facture']), $commande['nom_bill'], fxsl($this_ordered_product['total_prix_ht']+$this_ordered_product['total_prix_attribut_ht']), '');
							} else {
								$results_array[] = array(intval($commande['id']), $commande['date_vente'], $commande['nom_acheteur'], $commande['adresse'], $commande['ville'], $commande['code_postal'], $commande['pays'], $this_ordered_product['nom_produit'], $this_ordered_product['quantite'], fxsl($this_ordered_product['prix_ht']), fxsl($this_ordered_product['total_prix_ht']), fxsl($this_ordered_product['tva_percent']), fxsl($this_ordered_product['total_prix'] - $this_ordered_product['total_prix_ht']), fxsl($this_ordered_product['total_prix']), fxsl($cout_transport_ht), fxsl($tva_cout_transport), fxsl($cout_transport), fxsl($tarif_paiement_ht), fxsl($tva_tarif_paiement), fxsl($tarif_paiement), $commande['paiement']);
							}
						}
					}
				}
			}
		}
		if (!empty($add_total_footer)) {
			// On décale un petit tableau de totaux à droite
			$decalage = $csv_separator . $csv_separator . $csv_separator . $csv_separator . $csv_separator . $csv_separator . $csv_separator . $csv_separator . $csv_separator;
			$output_footer .= "\r\n" . $decalage . "TOTAUX :" . $csv_separator . fxsl($total_total_produit_ht) . $csv_separator . $csv_separator . fxsl($total_total_produit - $total_total_produit_ht) . $csv_separator . fxsl($total_total_produit) . $csv_separator . fxsl($total_cout_transport_ht) . $csv_separator . fxsl($total_cout_transport-$total_cout_transport_ht) . $csv_separator . fxsl($total_cout_transport) . $csv_separator . fxsl($total_tarif_paiement_ht) . $csv_separator . fxsl($total_tarif_paiement - $total_tarif_paiement_ht) . $csv_separator . fxsl($total_tarif_paiement) . "\r\n\r\n";

			$output_footer .= "" . $decalage . "TOTAL HT tout compris :" . $csv_separator . fxsl($total_total_produit_ht + $total_cout_transport_ht + $total_tarif_paiement_ht) . "\r\n";
			$output_footer .= "" . $decalage . "TVA tout compris :" . $csv_separator . fxsl(($total_total_produit - $total_total_produit_ht) + $total_cout_transport - $total_cout_transport_ht + $total_tarif_paiement - $total_tarif_paiement_ht) . "\r\n";
			$output_footer .= "" . $decalage . "TOTAL TTC tout compris :" . $csv_separator . fxsl($total_total_produit + $total_cout_transport + $total_tarif_paiement) . "\r\n";
		}
	}
	// **********
} elseif($type == 'produits') {
	// PRODUITS
	switch($mode) {
		case 'export':
			// DEBUT PARAMETRAGE
			// La colonne stock dans peel_produits ne sert pas, donc l'exporter induit en confusion
			$excluded_fields = array('stock');
			// Configuration de l'ajout de colonnes
			$specific_fields_array = array($GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_INCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_EXCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_BRAND'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY']);
			$hook_result = call_module_hook('export_products_get_configuration_array', array(), 'array');
			$specific_fields_array = array_merge_recursive_distinct($specific_fields_array, vb($hook_result['product_field_names'], array()));
			// FIN PARAMETRAGE
			// 
			// On récupère les noms des champs de la table de produits
			$product_field_names = get_table_field_names('peel_produits');

			// On rajoute ensuite des colonnes calculées
			foreach ($specific_fields_array as $this_field) {
				$product_field_names[] = $this_field;
			}
			// On retire les colonnes non désirées
			foreach($product_field_names as $this_key => $this_field) {
				if (in_array($this_field, $excluded_fields)) {
					unset($product_field_names[$this_key]);
				}
			}
			// On trie les colonnes
			sort($product_field_names);
			// On construit la ligne des titres
			$field_titles_array = $product_field_names;
			
			// On construit toutes les lignes de données
			$q = "SELECT p.*, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
				FROM peel_produits p
				INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
				INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
				WHERE " . get_filter_site_cond('produits', 'p', true) . "
				GROUP BY id
				ORDER BY id";
			$query = query($q);
			$i = 0;
			while ($result = fetch_assoc($query)) {
				// On récupère les infos liées à chaque produit
				$product_attributs_id_array = array();
				$product_object = new Product($result['id'], $result, true, null, true, !check_if_module_active('micro_entreprise'));
				$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_INCLUDING_VAT']] = fxsl($product_object->get_original_price(true, false, false));
				$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_EXCLUDING_VAT']] = fxsl($product_object->get_original_price(false, false, false));
				$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES']] = implode(',', $product_object->get_possible_sizes('export'));
				$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS']] = implode(',', $product_object->get_possible_colors());
				$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_BRAND']] = implode(',', $product_object->get_product_brands());
				$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS']] = implode(',', $product_object->get_product_references());
				$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY']] = implode(',', $product_object->get_possible_categories());

				$hook_result = call_module_hook('export_products_get_line_infos_array', array('id' => $product_object->id), 'array');
				$result = array_merge_recursive_distinct($result, $hook_result);

				// On génère la ligne
				$this_line_output = array();
				foreach($product_field_names as $this_field_name) {
					if (in_array($this_field_name, $specific_fields_array) || StringMb::substr($this_field_name, 0, StringMb::strlen('descriptif_')) == 'descriptif_' || StringMb::substr($this_field_name, 0, StringMb::strlen('description_')) == 'description_') {
						$this_line_output[] = StringMb::html_entity_decode_if_needed(vb($result[$this_field_name]));
					} else {
						$this_line_output[] = vb($result[$this_field_name]);
					}
				}
				$results_array[] = $this_line_output;
				unset($product_object);
				$i++;
				if($i%10==0) {
					// On transfère au fur et à mesure pour faire patienter utilisateur, et pour éviter erreur du type : Script timed out before returning headers
					echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
					$output = '';
				}
			}

			echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
		break;

		case 'export_pdf':
			$output = "<table cellspacing='0' cellpadding='1' border='1'><tr>";
			$output .= "<td>" . $GLOBALS["STR_PRODUCT_NAME"] . "</td>" ;
			$output .= "<td>" . $GLOBALS["STR_ADMIN_SHORT_DESCRIPTION"] . "</td>" ;
			$output .= "<td>" . $GLOBALS["STR_PDF_PRIX_HT"] . "</td>" ;
			$output .= "<td>" . $GLOBALS["STR_IMAGE"] . "</td>" ;
			$output .= "<td>" . $GLOBALS["STR_ADMIN_EXPORT_PRODUCTS_COLORS"] . "</td>" ;
			$output .= "<td>" . $GLOBALS["STR_ADMIN_EXPORT_PRODUCTS_SIZES"] . "</td>" ;
			$output .= '</tr>';
			$where = '';
			if (!empty($_POST['categories'])) {
				$where .= " c.id IN (" . implode(',',vn($_POST['categories'])) . ") AND " ;
			}
			$q = "SELECT p.*, p.nom_" . (!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']) . " AS nom, p.descriptif_" . $_SESSION['session_langue'] . " AS descriptif, p.image1
				FROM peel_produits p
				INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
				INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
				WHERE " . $where  . get_filter_site_cond('produits', 'p', true) . "
				GROUP BY p.id
				ORDER BY p.id";
				// var_dump($q);die();
			$query = query($q);
			while ($result = fetch_assoc($query)) {
				$output .= '<tr>';
				// On récupère les infos liées à chaque produit
				$product_object = new Product($result['id'], $result, true, null, true, !check_if_module_active('micro_entreprise'));
				$possible_sizes = $product_object->get_possible_sizes('infos', 0, true, false, false, true);
				$size_options_html = '';
				if (!empty($possible_sizes)) {
					$purchase_prix = $product_object->get_final_price();
					foreach ($possible_sizes as $this_size_id => $this_size_infos) {
						$option_content = $this_size_infos['name'];
						$option_content .= "<br/><span style='font-size:10px;'>" . $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_OVERCOST'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . fprix($purchase_prix + $this_size_infos['final_price_formatted'], true);
						$size_options_html .= $option_content . "</span><br/>";
					}
				}
				$possible_colors = $product_object->get_possible_colors();
				$color_options_html = '';
				if (!empty($possible_colors)) {
					// Code pour recupérer select des couleurs
					foreach ($possible_colors as $this_color_id => $this_color_name) {
						$color_options_html .= $this_color_name . '<br/>';
					}
				}
				$output .= "<td>" . vb($result['nom']) . "</td>";
				$output .= "<td>" . vb($result['descriptif']) . "</td>";
				$output .= "<td>" . fprix($product_object->get_original_price(false, false, false), true) . "<br/><span style='font-size:10px;'>" . $GLOBALS["STR_ADMIN_ECOTAX"] .$GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '. fprix($product_object->get_ecotax(), true) . "</span></td>";
				$output .= "<td>";
				if (!empty($result['image1'])) {
					$output .= "<img src='" . thumbs(vb($result['image1']), 80, 50, 'fit', null, null, true, true) . "'/>";
				}
				$output .= "</td>";
				$output .= "<td align='center'>" . (empty($color_options_html)?'<span>-</span>':$color_options_html) . "</td>";
				$output .= "<td align='center'>" . (empty($size_options_html)?'<span>-</span>':$size_options_html) . "</td>";
				$output .= '</tr>';
				unset($product_object);
			}

			$output .= '</table>';
			$output .= '<div style="position:absolute;bottom:0px;">' . vb($_POST['text_bottom']) . '</div>';
		break;

		default:
			$format = 'html';
			$output = '
			<form class="entryform form-inline" role="form" method="post" action="' . get_current_url(false) . '" enctype="multipart/form-data">
			<table class="main_table">
				<tr>
					<td class="entete" colspan="2">' . $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CHOOSE_EXPORT_CRITERIA'] . '</td>
				</tr>
				<tr>
					<td class="top">' . $GLOBALS['STR_ADMIN_SELECT_CATEGORIES_TO_EXPORT'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</td>
					<td>
						<select class="form-control" name="categories[]" multiple="multiple" style="width:100%" size="10">' 
							. get_categories_output(null, 'categories',  vb($frm['categories']), 'option', '&nbsp;&nbsp;', null, null, true, 80) . '
						</select>
					</td>
				</tr>
				<tr>
					<td class="top">'.$GLOBALS['STR_ADMIN_EXPORT_PRICES_DISABLE'].$GLOBALS['STR_BEFORE_TWO_POINTS'].':</td>
					<td><input type="checkbox" name="price_disable" value="1" /></td>
				</tr>
				<tr>
					<td class="top">'.$GLOBALS["STR_ADMIN_EXPORT_CSV"].$GLOBALS['STR_BEFORE_TWO_POINTS'].':</td>
					<td><input type="radio" name="mode" value="export" /></td>
				</tr>
				<tr>
					<td class="top">'.$GLOBALS['STR_MODULE_FACTURES_ADVANCED_EXPORT_LIST_PDF'].''.$GLOBALS['STR_BEFORE_TWO_POINTS'].':</td>
					<td><input type="radio" name="mode" value="export_pdf" /></td>
				</tr>
				<tr>
					<td class="top">'.$GLOBALS["STR_ADMIN_TEXT_FOR_PDF_EXPORT"].$GLOBALS['STR_BEFORE_TWO_POINTS'].':</td>
					<td><input style="width:100%;" type="text" name="text_bottom" value="" /></td>
				</tr>
				<tr>
					<td colspan="2" class="center"><p><input class="btn btn-primary" type="submit" value="' . $GLOBALS['STR_SUBMIT'] . '" /></p></td>
				</tr>
			</table>
		</form>';
	}
}

$hook_result = call_module_hook('export', array('type' => $type, 'field_titles_array' => $field_titles_array, 'results_array' => $results_array), 'array');
$field_titles_array = array_merge_recursive_distinct($field_titles_array, $hook_result['field_titles_array']);
$results_array = array_merge_recursive_distinct($results_array, $hook_result['results_array']);

if(!empty($field_titles_array)) {
	// On affiche les titres des colonnes
	$output_array = array();
	foreach($field_titles_array as $this_field => $this_title) {
		$output_array[] = $this_title;
	}
	$output .= implode($csv_separator, $output_array) . "\r\n";
}

// On récupère les résultats de la BDD si pas déjà fait plus haut
if(empty($results_array) && !empty($sql_csv)) {
	$query_csv = query($sql_csv);
	while ($result = fetch_assoc($query_csv)) {
		$results_array[] = $result;
	}
}

if(!empty($results_array)) {
	// On génère les lignes de résultat
	foreach($results_array as $this_result) {
		$output_array = array();
		if(!empty($field_titles_array)) {
			// On prend les résultats dans le même ordre que les titres
			$fields_array = array_keys($field_titles_array);
		} else {
			$fields_array = array_keys($this_result);
		}
		foreach($fields_array as $this_field) {
			$csv_value = $this_result[$this_field];
			if (is_array($csv_value)) {
				$csv_value = implode(',', $csv_value);
			}
			if(strpos($this_field, '_date') !== false || strpos($this_field, 'date_') === 0 || $this_field == 'date') {
				$csv_value = get_formatted_date($csv_value, 'short', 'long');
			}
			$output_array[] = filtre_csv($csv_value, $csv_separator);
		}
		$output .= implode($csv_separator, $output_array) . "\r\n";
	}
}

if($format == 'pdf') {
	$pdf_output = '';
	require_once($GLOBALS['dirroot'].'/lib/class/pdf/html2pdf/html2pdf.class.php');
	try
	{
		$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', array(2, 10, 10, 10));
		// $html2pdf->setModeDebug();
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($output, isset($_GET['vuehtml']));
		ob_start();
		$html2pdf->Output();
		$pdf_output .= ob_get_contents();
		ob_end_clean();
	}
	catch(HTML2PDF_exception $e) {
		echo $e;
		exit;
	}
	// On envoie le PDF
	echo $pdf_output;
	die();
} elseif($format == 'html') {
	include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
	echo $output;
	include($GLOBALS['repertoire_modele'] . "/admin_bas.php");
	die();
} else {
	// On transmet le fichier qu'on vient de préparer
	if(empty($filename)) {
		$filename = "export_".$type."_" . str_replace('/', '-', date($GLOBALS['date_basic_format_short'])) . ".csv";
	}
	output_csv_http_export_header($filename, 'csv', $page_encoding);
	echo StringMb::convert_encoding($output . $output_footer, $page_encoding, GENERAL_ENCODING);
}



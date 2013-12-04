<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an     |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/   |
// +----------------------------------------------------------------------+
// $Id: export_produits.php 39162 2013-12-04 10:37:44Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products,admin_webmastering");

// DEBUT PARAMETRAGE
// La colonne stock dans peel_produits ne sert pas, donc l'exporter induit en confusion
$excluded_fields = array('stock');
$specific_fields_array = array($GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_INCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_EXCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_BRAND'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY']);
if (is_stock_advanced_module_active()) {
	$specific_fields_array[] = 'Stock';
}
// FIN PARAMETRAGE
if (!empty($_GET['encoding'])) {
	$page_encoding = $_GET['encoding'];
} elseif (!empty($GLOBALS['site_parameters']['export_encoding'])) {
	$page_encoding = $GLOBALS['site_parameters']['export_encoding'];
} else {
	$page_encoding = 'utf-8';
}
$output = '';
$filename = "export_produits_" . str_replace('/', '-', date($GLOBALS['date_basic_format_short'])) . ".csv";
// On ne veut pas polluer le fichier exporté par un quelconque message d'erreur
@ini_set('display_errors', 0);
output_csv_http_export_header($filename, 'csv', $page_encoding);
// On récupère les noms des champs de la table de produits
$product_fields_infos = get_table_fields('peel_produits');
foreach($product_fields_infos as $this_field_infos) {
	if (!in_array($this_field_infos['Field'], $excluded_fields)) {
		$product_field_names[] = $this_field_infos['Field'];
	}
}
// On trie les colonnes
sort($product_field_names);
// et on rajoute ensuite des colonnes calculées
foreach ($specific_fields_array as $this_field) {
	$product_field_names[] = $this_field;
}
// et on ajoute les colonnes pour chaque attribut
$sql_n = "SELECT *
	FROM peel_nom_attributs
	ORDER BY id";
$nom_attrib = query($sql_n);
while ($this_attribut = fetch_assoc($nom_attrib)) {
	$attribut_infos_array[$this_attribut['id']] = $this_attribut;
	$product_field_names[] = $this_attribut['nom_' . $_SESSION['session_langue']] . '#' . $this_attribut['id'];
}

if (is_lot_module_active()) {
	// Gestion des prix par lots
	$i = 1;
	$query_produits_lot = query("SELECT * 
		FROM peel_quantites
		WHERE produit_id='" . intval($product_object->id) . "'");
	while ($prix_lot = fetch_assoc($query_produits_lot)) {
		$product_field_names[] = 'quantite§prix§prix_revendeur'.$i;
		$result['quantite§prix§prix_revendeur'.$i] = $prix_lot['quantite'].'§'.$prix_lot['prix'].'§'.$prix_lot['prix_revendeur'];
		$i++;
	}
}

// On construit la ligne des titres
$title_line_output = array();
foreach($product_field_names as $this_field_name) {
	$title_line_output[] = filtre_csv($this_field_name);
}
$output .= implode("\t", $title_line_output) . "\r\n";
// On construit toutes les lignes de données
$q = "SELECT p.*, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
	FROM peel_produits p
	INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
	INNER JOIN peel_categories c ON c.id = pc.categorie_id
	GROUP BY id
	ORDER BY id";
$query = query($q);
$i = 0;
while ($result = fetch_assoc($query)) {
	// On récupère les infos liées à chaque produit
	$product_attributs_id_array = array();
	$product_object = new Product($result['id'], $result, true, null, true, !is_micro_entreprise_module_active());
	$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_INCLUDING_VAT']] = fxsl($product_object->get_original_price(true, false, false));
	$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_EXCLUDING_VAT']] = fxsl($product_object->get_original_price(false, false, false));
	//gestion de taille
	$infos_taille = array();
	unset($possible_sizes);
	$sql_taille = query('SELECT t.*
		FROM peel_produits_tailles pt 
		INNER JOIN peel_tailles t ON t.id=pt.taille_id
		WHERE pt.produit_id = "'.intval($product_object->id).'"');
	while($taille = fetch_assoc($sql_taille)){
		$temp = $taille['nom_'.$_SESSION['session_langue']];
		if($taille['prix']!=0 || $taille['prix_revendeur']!=0) {
			// Ajout d'informations sur le prix si adapté
			$temp .= '§'.$taille['prix'].'§'.$taille['prix_revendeur'];
		}
		$infos_taille[] = $temp;
		$possible_sizes[$taille['id']] = $taille['nom_'.$_SESSION['session_langue']];
	}
	$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES']] = implode(',', $infos_taille);
	$possible_colors = $product_object->get_possible_colors();
	$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS']] = implode(',', $possible_colors);
	$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_BRAND']] = implode(',', $product_object->get_product_brands());
	$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS']] = implode(',', $product_object->get_product_references());
	$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY']] = implode(',', $product_object->get_possible_categories());
	// Récupération des valeurs pour les attributs
	$query_produits_attributs = query("SELECT ppa.nom_attribut_id, pa.id, pa.descriptif_" . $_SESSION['session_langue'] . " AS descriptif
		FROM peel_produits_attributs ppa
		LEFT JOIN peel_attributs pa ON pa.id=ppa.attribut_id AND pa.id_nom_attribut=ppa.nom_attribut_id
		WHERE produit_id='" . intval($product_object->id) . "'");
	while ($this_attribut = fetch_assoc($query_produits_attributs)) {
		if (!empty($attribut_infos_array[$this_attribut['nom_attribut_id']])) {
			if ($attribut_infos_array[$this_attribut['nom_attribut_id']]['upload'] == 1) {
				// L'attribut concerné est un champ d'upload => on exporte cette info, sans notion d'id d'attribut car il n'y en a pas
				$this_value = '0#__upload';
			} elseif ($attribut_infos_array[$this_attribut['nom_attribut_id']]['texte_libre'] == 1) {
				// L'attribut concerné est un champ de texte libre => on exporte cette info, sans notion d'id d'attribut car il n'y en a pas
				$this_value = '0#__texte_libre';
			} else {
				$this_value = $this_attribut['id'] . '#' . $this_attribut['descriptif'];
			}
			$result[$attribut_infos_array[$this_attribut['nom_attribut_id']]['nom_' . $_SESSION['session_langue']] . '#' . $this_attribut['nom_attribut_id']][] = $this_value;
		}
	}
	if (is_stock_advanced_module_active()) {
		$infos_stocks = array();
		$product_stock_infos = get_product_stock_infos($product_object->id);
		foreach ($product_stock_infos as $this_stock_info) {
			if ($this_stock_info['stock'] != 0) {
				$infos_stocks[] = $this_stock_info['stock'].'§'.vb($possible_colors[$this_stock_info['couleur_id']]).'§'.vb($possible_sizes[$this_stock_info['taille_id']]);
			}
		}
		$result['Stock'] = implode(',', $infos_stocks);
	}
	
	if (is_lot_module_active()) {
		// Gestion des prix par lots
		$i = 1;
		$query_produits_lot = query("SELECT * 
			FROM peel_quantites
			WHERE produit_id='" . intval($product_object->id) . "'");
		while ($prix_lot = fetch_assoc($query_produits_lot)) {
			$result['quantite§prix§prix_revendeur'.$i] = $prix_lot['quantite'].'§'.$prix_lot['prix'].'§'.$prix_lot['prix_revendeur'];
			$i++;
		}
	}
	// On génère la ligne
	$this_line_output = array();
	foreach($product_field_names as $this_field_name) {
		// La colonne stock dans peel_produits ne sert pas, donc l'exporter induit en confusion
		if (in_array($this_field_name, $specific_fields_array) || String::substr($this_field_name, 0, String::strlen('descriptif_')) == 'descriptif_' || String::substr($this_field_name, 0, String::strlen('description_')) == 'description_') {
			$this_line_output[] = filtre_csv(String::nl2br_if_needed(String::html_entity_decode_if_needed(vb($result[$this_field_name]), ENT_QUOTES)));
		} elseif (!empty($result[$this_field_name]) && is_array($result[$this_field_name])) {
			$this_line_output[] = filtre_csv(implode(',', $result[$this_field_name]));
		} else {
			$this_line_output[] = filtre_csv(vb($result[$this_field_name]));
		}
	}
	$output .= implode("\t", $this_line_output) . "\r\n";
	unset($product_object);
	$i++;
	if($i%10==0) {
		// On transfère au fur et à mesure pour faire patienter utilisateur, et pour éviter erreur du type : Script timed out before returning headers
		echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
		$output = '';
	}
}

echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);

?>
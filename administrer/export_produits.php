<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an     |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/   |
// +----------------------------------------------------------------------+
// $Id: export_produits.php 48447 2016-01-11 08:40:08Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products,admin_webmastering");

// DEBUT PARAMETRAGE
// La colonne stock dans peel_produits ne sert pas, donc l'exporter induit en confusion
$excluded_fields = array('stock');
$specific_fields_array = array($GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_INCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_EXCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_BRAND'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY']);

$hook_result = call_module_hook('export_products_get_configuration_array', array(), 'array');
$specific_fields_array = array_merge_recursive($specific_fields_array, vb($hook_result['product_field_names'], array()));

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
$title_line_output = array();
foreach($product_field_names as $this_field_name) {
	$title_line_output[] = filtre_csv($this_field_name);
}
$output .= implode("\t", $title_line_output) . "\r\n";
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
	$result = array_merge_recursive($result, $hook_result);
	
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


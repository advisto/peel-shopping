<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produit.php 47592 2015-10-30 16:40:22Z sdelaporte $

define('LOAD_NO_OPTIONAL_MODULE', true);
include('../../configuration.inc.php');

if (!check_if_module_active('search')) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die(get_url('/'));
}
// Javascript
$page_encoding='utf-8';
output_general_http_header($page_encoding);
$output='';
$results_array = array();
$labels_array = array();
$maxRows = 10;

if (vb($_POST['type']) == "update_session_add") {
	$_SESSION['session_search_product_list'][$_POST['id']] = $_POST['quantite'];
} elseif (vb($_POST['type']) == "update_session_delete") {
	unset($_SESSION['session_search_product_list'][$_POST['id']]);
} else {
	$form_add_search_product_list = vb($_POST['type']) == "search_product_list";
	$search = trim(vb($_POST['search']));
	$search_category = intval(vb($_POST['search_category']));

	if (String::strlen($search)>0 || !empty($search_category)) {
		if(String::strpos($search, vb($GLOBALS['site_parameters']['product_reference_prefix'])) !== false) {
			// Si les références ont un préfix commun, on peut insérer plusieurs régérence d'un seul coup.
			$search_array = explode($GLOBALS['site_parameters']['product_reference_prefix'], $search);
			$result_array=array();
			foreach($search_array as $this_result) {
				if (!empty($this_result)) {
					$result_array[] = $GLOBALS['site_parameters']['product_reference_prefix'].$this_result;
				}
			}
			$search = $result_array;
		}
		$queries_results_array = get_quick_search_results($search, $maxRows, true, $search_category);
		if(!empty($queries_results_array)) {
			$cat_search_sql = "SELECT pc.produit_id, c.id as categorie_id, c.nom_" . $_SESSION['session_langue'] . " as categorie
				FROM peel_produits_categories pc
				LEFT JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
				WHERE pc.produit_id IN (" . implode(', ', array_keys($queries_results_array)) . ")";
			$query = query($cat_search_sql);
			while ($result = fetch_assoc($query)) {
				$queries_results_array[$result['produit_id']]->categorie_id = $result['categorie_id'];
				$queries_results_array[$result['produit_id']]->categorie = $result['categorie'];
			}
			foreach($queries_results_array as $result) {
				$product_object = new Product($result->id, null, false, null, true, !check_if_module_active('micro_entreprise'));
				// Prix hors ecotaxe
				$display_picture = $product_object->get_product_main_picture(false);
				if ($display_picture) {
					$product_picture = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($display_picture, 75, 75, 'fit');
			} elseif(!empty($GLOBALS['site_parameters']['default_picture'])) {
				$product_picture = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($GLOBALS['site_parameters']['default_picture'], 75, 75, 'fit');
			} else {
				$product_picture = null;
			}
				$this_label = (!empty($GLOBALS['site_parameters']['autocomplete_hide_images'])?'<div>':'<div class="autocomplete_image"><img src="'.$product_picture.'" /></div><div style="display:table-cell; vertical-align:middle; height:45px;">') . highlight_found_text(String::html_entity_decode($result->nom), $search, $found_words_array) . (!empty($GLOBALS['site_parameters']['autocomplete_show_references']) && String::strlen($result->reference) ? ' - <span class="autocomplete_reference_result">' . highlight_found_text(String::html_entity_decode($result->reference), $search, $found_words_array) . '</span>' : '') . '</div><div class="clearfix" />';
				if(!in_array($this_label, $labels_array)) { 
					$results_array[] = array(
						'id' => $result->id,
						'reference' => $result->reference,
						'urlprod' => get_product_url($result->id, $result->nom, $result->categorie_id, $result->categorie),
						'label' => $this_label,
						'name' => $result->nom,
						'category_name' => $product_object->categorie,
						'nom_produit' => $product_object->name,
						'href_produit' => $product_object->get_product_url(),
						'brand_link_html' => (!empty($product_object->id_marque)? get_brand_link_html($product_object->id_marque, false, false, null, "_blank") : ''),
						'href_category' => get_product_category_url($product_object->categorie_id,  $product_object->categorie),
						'photo_src' => vb($product_picture),
						'prix' => $product_object->get_original_price(display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), false),
						'ean_code' => $product_object->ean_code,
						'barcode_image_src'=> $product_object->barcode_image_src(),
						'reference' => $product_object->reference,
						'minimal_price' => $product_object->get_minimal_price(false),
						'product_id' => $product_object->id
					);
					$labels_array[] = $this_label;
				}
				unset($product_object);
			}
		}
	if (check_if_module_active('annonces')) {
		$sql = get_ad_search_select($_POST, 10);
		$query = query($sql);
		while ($result = fetch_assoc($query)) {
			$ad_object = new Annonce($result['ref'], $result);
			$product_picture = $ad_object->get_annonce_picture(true, 75, 75);
			$this_title = $ad_object->get_titre(); 
			$this_label = (!empty($GLOBALS['site_parameters']['autocomplete_hide_images']) || empty($product_picture)?'<div>':'<div class="autocomplete_image"><img src="'.$product_picture.'" /></div><div style="display:table-cell; vertical-align:middle; height:45px;">') . highlight_found_text(String::html_entity_decode($this_title), $search, $found_words_array) . (!empty($GLOBALS['site_parameters']['autocomplete_show_references']) && String::strlen(vb($ad_object->reference)) ? ' - <span class="autocomplete_reference_result">' . highlight_found_text(String::html_entity_decode($ad_object->reference), $search, $found_words_array) . '</span>' : '') . '</div><div class="clearfix" />';
			$results_array[] = array(
					'id' => $ad_object->ref,
					'reference' => vb($ad_object->reference),
					'urlprod' => $ad_object->get_annonce_url(),
					'label' => $this_label,
					'name' => $this_title
				);
		}
	}
}
	if (!empty($_POST['return_json_array_with_raw_information'])) {
		$output = json_encode($results_array);
	} else {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/search_produit.tpl');
		$tpl->assign('STR_AUCUN_RESULTAT', $GLOBALS['STR_AUCUN_RESULTAT']);
		$tpl->assign('display_barcode', !empty($GLOBALS['site_parameters']['display_ean_code_on_product_list']));
		$tpl->assign('results', $results_array);
		$tpl->assign('form_add_search_product_list', $form_add_search_product_list);
		$output .= $tpl->fetch();
	}
	echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
}

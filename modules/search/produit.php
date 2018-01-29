<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produit.php 55325 2017-11-30 10:47:17Z sdelaporte $

include('../../configuration.inc.php');

if (!check_if_module_active('search')) {
	// This module is not activated
	die();
}
// Javascript
$page_encoding='utf-8';
output_general_http_header($page_encoding);
$output='';
$results_array = array();
$labels_array = array();
$GLOBALS['found_words_array'] = array();

$maxRows = vb($GLOBALS['site_parameters']['search_autocomplete_max_rows'], 10);

if (vb($_POST['type']) == "update_session_add") {
	$_SESSION['session_search_product_list'][$_POST['id']] = $_POST['quantite'];
} elseif (vb($_POST['type']) == "update_session_delete") {
	unset($_SESSION['session_search_product_list'][$_POST['id']]);
} else {
	$form_add_search_product_list = vb($_POST['type']) == "search_product_list";
	$search = trim(vb($_POST['search']));
	$search_category = intval(vb($_POST['search_category']));

	if (!empty($GLOBALS['site_parameters']['main_site_id']) && $GLOBALS['site_id'] == $GLOBALS['site_parameters']['main_site_id']) {
		// On cherche sur tous les sites pour l'autocomplete
		$GLOBALS['multisite_disable_if_no_specific_site_id'] = true;
	}
	if (StringMb::strlen($search)>0 || !empty($search_category)) {
		if(!empty($GLOBALS['site_parameters']['product_reference_prefix']) && StringMb::strpos($search, $GLOBALS['site_parameters']['product_reference_prefix']) !== false) {
			// Si les références ont un préfix commun, on peut insérer plusieurs références d'un seul coup.
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
				WHERE pc.produit_id IN (" . implode(', ', array_keys($queries_results_array)) . ")
				GROUP BY pc.produit_id";
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
					$product_picture = thumbs($display_picture, 75, 75, 'fit', null, null, true, true);
				} elseif(!empty($GLOBALS['site_parameters']['default_picture'])) {
					$product_picture = thumbs($GLOBALS['site_parameters']['default_picture'], 75, 75, 'fit', null, null, true, true);
				} else {
					$product_picture = null;
				}
				$url = $product_object->get_product_url();
				$this_label = (!empty($GLOBALS['site_parameters']['autocomplete_hide_images'])?'<div>':'<div class="autocomplete_image"><a href="'.$url.'"><img src="'.$product_picture.'" /></a></div><div style="display:table-cell; vertical-align:middle; height:45px;">') . '<a href="'.$url.'">'. highlight_found_text(StringMb::html_entity_decode($result->nom), $search, $GLOBALS['found_words_array']) . (!empty($GLOBALS['site_parameters']['autocomplete_show_references']) && StringMb::strlen($result->reference) ? ' - <span class="autocomplete_reference_result">' . highlight_found_text(StringMb::html_entity_decode($result->reference), $search, $GLOBALS['found_words_array']) . '</span>' : '') . '</a></div><div class="clearfix" />';
				if(!in_array($this_label, $labels_array)) { 
					$results_array[] = array(
						'id' => $result->id,
						'reference' => $result->reference,
						'href' => get_product_url($result->id, $result->nom, vb($result->categorie_id), vb($result->categorie)),
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
		// Résultats du hook : à renvoyer sous le format 'XXX(modulename)' => array('results' => $results_found, 'title' => $GLOBALS['STR_XXX_TITLE'], 'no_result' => null)
		$frm = $_POST;
		$match = 1;
		$terms = build_search_terms($search, $match);
		$GLOBALS['search_complementary_results_array'] = call_module_hook('search_complementary', array('frm' => $frm, 'match' => $match, 'terms' => $terms, 'real_search' => $search, 'taille_texte_affiche' => 60, 'mode' => 'autocomplete'), 'array');
		if(function_exists('resultsTypeCompareArgsOrder')) {
			if(!empty($GLOBALS['site_parameters']['search_complementary_found_sort_array'])) {
				// Tri des thématiques de résultats si défini
				// Le tableau search_complementary_found_sort_array peut être sous la forme 'type' => N, ...  ou simplement 'type1', 'type2', ...
				uksort($GLOBALS['search_complementary_results_array'], 'resultsTypeCompareArgsOrder');
			} elseif(!empty($GLOBALS['site_parameters']['search_complementary_found_sort_by_count'])) {
				// Tri des thématiques de résultats par nombre de résultats décroissant
				uksort($GLOBALS['search_complementary_results_array'], 'resultsTypeCompareArgsOrder');
			}
		}
		foreach($GLOBALS['search_complementary_results_array'] as $this_hook_result) {
			$results_array = array_merge_recursive_distinct($results_array, $this_hook_result['results']);
		}
		foreach($results_array as $this_key => $this_infos) {
			if(!isset($this_infos['label']) && !empty($this_infos['href']) && !empty($this_infos['name'])) {
				$results_array[$this_key]['label'] = (!empty($GLOBALS['site_parameters']['autocomplete_hide_images']) || empty($this_infos['photo_src'])?'<div>':'<div class="autocomplete_image"><a href="'.$this_infos['href'].'"><img src="'.$this_infos['photo_src'].'" /></a></div><div style="display:table-cell; vertical-align:middle; height:45px;">') . '<a href="'.$this_infos['href'].'">'. highlight_found_text(StringMb::html_entity_decode($this_infos['name']), $search, $GLOBALS['found_words_array']) . (!empty($GLOBALS['site_parameters']['autocomplete_show_references']) && StringMb::strlen($this_infos['reference']) ? ' - <span class="autocomplete_reference_result">' . highlight_found_text(StringMb::html_entity_decode($this_infos['reference']), $search, $GLOBALS['found_words_array']) . '</span>' : '') . '</a></div><div class="clearfix" />';
			}
		}
	}

	// Suppression des doublons
	$href_array = array();
	$results_output_array = array();
	foreach($results_array as $this_key => $this_infos) {
		// Test sur l'url, si elle est déjà présente dans la liste de résultats, on ne l'affiche pas une nouvelle fois.
		// Il peut y avoir des résultats en double dans $GLOBALS['search_complementary_results_array'] dans $GLOBALS['search_complementary_results_array'] suite au resultat de l'appel au hook
		if(!in_array($this_infos['href'], $href_array)) {
			$results_output_array[$this_key] = $this_infos;
			$href_array[] = $this_infos['href'];
		}
	}

	if (!empty($_POST['return_json_array_with_raw_information'])) {
		$output = json_encode($results_output_array);
	} else {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/search_produit.tpl');
		$tpl->assign('STR_AUCUN_RESULTAT', $GLOBALS['STR_AUCUN_RESULTAT']);
		$tpl->assign('display_barcode', !empty($GLOBALS['site_parameters']['display_ean_code_on_product_list']));
		$tpl->assign('results', $results_output_array);
		$tpl->assign('form_add_search_product_list', $form_add_search_product_list);
		$output .= $tpl->fetch();
	}
	echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
}

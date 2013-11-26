<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produit.php 38682 2013-11-13 11:35:48Z gboussin $

define('LOAD_NO_OPTIONAL_MODULE', true);
include('../../configuration.inc.php');

if (!is_advanced_search_active()) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die($GLOBALS['wwwroot']."/");
}
// Javascript
$page_encoding='utf-8';
output_general_http_header($page_encoding);
$output='';
$results_array = array();
$maxRows = 10;
$search = trim(vb($_POST['search']));
$search_category = intval(vb($_POST['search_category']));

if (String::strlen($search)>0 || !empty($search_category)) {
	$queries_results_array = get_quick_search_results($search, $maxRows, true, $search_category);
	if(!empty($queries_results_array)) {
		$cat_search_sql = "SELECT pc.produit_id, c.id as categorie_id, c.nom_" . $_SESSION['session_langue'] . " as categorie
			FROM peel_produits_categories pc
			LEFT JOIN peel_categories c ON c.id = pc.categorie_id
			WHERE pc.produit_id IN (" . implode(', ', array_keys($queries_results_array)) . ")";
		$query = query($cat_search_sql);
		while ($result = fetch_assoc($query)) {
			$queries_results_array[$result['produit_id']]->categorie_id = $result['categorie_id'];
			$queries_results_array[$result['produit_id']]->categorie = $result['categorie'];
		}
		foreach($queries_results_array as $result) {
			$product_object = new Product($result->id, $result, true, null, true, !is_micro_entreprise_module_active());
			// Prix hors ecotaxe
			$display_picture = $product_object->get_product_main_picture(false);
			if ($display_picture) {
				$product_picture = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($display_picture, 75, 75, 'fit');
			} else {
				$product_picture = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($GLOBALS['site_parameters']['default_picture'], 75, 75, 'fit');
			}
			$results_array[] = array(
				'id' => $result->id,
				'reference' => $result->reference,
				'urlprod' => get_product_url($result->id, $result->nom, $result->categorie_id, $result->categorie),
				'label' => (!empty($GLOBALS['site_parameters']['autocomplete_hide_images'])?'<div>':'<div class="autocomplete_image"><img src="'.$product_picture.'" /></div><div style="display:table-cell; vertical-align:middle; height:45px;">') . highlight_found_text(String::html_entity_decode($result->nom), $search, $found_words_array) . (!empty($GLOBALS['site_parameters']['autocomplete_show_references']) && String::strlen($result->reference) ? ' - <span class="autocomplete_reference_result">' . highlight_found_text(String::html_entity_decode($result->reference), $search, $found_words_array) . '</span>' : '') . '</div><div class="clearfix" />',
				'nom' => $result->nom
			);
			unset($product_object);
		}
	}
}
if (!empty($_POST['return_json_array_with_raw_information'])) {
	$output = json_encode($results_array);
} else {
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/search_produit.tpl');
	$tpl->assign('STR_AUCUN_RESULTAT', $GLOBALS['STR_AUCUN_RESULTAT']);
	$tpl->assign('results', $results_array);
	$output .= $tpl->fetch();
}
echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rpc.php 39443 2014-01-06 16:44:24Z sdelaporte $

define('IN_PEEL_ADMIN', true);
define('IN_RPC', true);
define('LOAD_NO_OPTIONAL_MODULE', true);
include("../configuration.inc.php");

if (!empty($_GET['encoding'])) {
	$page_encoding = $_GET['encoding'];
} else {
	$page_encoding = 'utf-8';
}
if (!est_identifie() || !a_priv("admin_products", true) || empty($_POST)) {
	die();
}
output_general_http_header($page_encoding);
$output = '';
$search = vb($_POST['search']);
$return_mode_for_displayed_values = vb($_POST['return_mode_for_displayed_values']);
$id_utilisateur = vb($_POST['id_utilisateur']);
$apply_vat = vb($_POST['apply_vat']);
$currency = vb($_POST['currency']);
$currency_rate = vn($_POST['currency_rate']);
$results_array = array();
if (!empty($_POST['maxRows'])) {
	$maxRows = $_POST['maxRows'];
} else {
	$maxRows = 7;
}

if (empty($currency_rate)) {
	$currency_rate = 1;
}
if (String::strlen($search)>0) {
	$queries_results_array = get_quick_search_results($search, $maxRows);
	if(!empty($queries_results_array)) {
		$is_reseller = false;
		if(!empty($id_utilisateur)) {
			$priv = query("SELECT priv
				FROM peel_utilisateurs
				WHERE id_utilisateur='" . intval($id_utilisateur) . "'");
			$rep = fetch_assoc($priv);
			if ($rep['priv'] == 'reve') {
				$is_reseller = true;
			}
		}
		foreach($queries_results_array as $result) {
			$product_object = new Product($result->id, $result, true, null, true, !is_micro_entreprise_module_active());
			// Prix hors ecotaxe
			$purchase_prix_ht = $product_object->get_final_price(0, false, $is_reseller) * $currency_rate;
			$purchase_prix = $product_object->get_final_price(0, $apply_vat, $is_reseller) * $currency_rate;
			$prix_cat_ht = $product_object->get_original_price(false, false, false, false) * $currency_rate;
			$prix_cat = $product_object->get_original_price($apply_vat, false, false, false) * $currency_rate;
			if (display_prices_with_taxes_in_admin()) {
				$purchase_prix_displayed = fprix($purchase_prix, true, $currency, false, $currency_rate, false);
			} else {
				$purchase_prix_displayed = fprix($purchase_prix_ht, true, $currency, false, $currency_rate, false);
			}
			// Code pour recupérer select des tailles
			$possible_sizes = $product_object->get_possible_sizes('infos', 0, true, false, false, true);
			$size_options_html = '';
			if (!empty($possible_sizes)) {
				foreach ($possible_sizes as $this_size_id => $this_size_infos) {
					$option_content = $this_size_infos['name'];
					$option_content .= $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . fprix($purchase_prix + $this_size_infos['final_price_formatted'], true) . ' => ' . $GLOBALS["STR_ADMIN_UPDATE"];
					$size_options_html .= '<option value="' . intval($this_size_id) . '">' . $option_content . '</option>';
				}
			}
			$possible_colors = $product_object->get_possible_colors();
			$color_options_html = '';
			if (!empty($possible_colors)) {
				// Code pour recupérer select des couleurs
				foreach ($possible_colors as $this_color_id => $this_color_name) {
					$color_options_html .= '<option value="' . intval($this_color_id) . '">' . $this_color_name . '</option>';
				}
			}
			$display_picture = $product_object->get_product_main_picture(false);
			if ($display_picture) {
				$product_picture = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($display_picture, 75, 75, 'fit');
			} else {
				$product_picture = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($GLOBALS['site_parameters']['default_picture'], 75, 75, 'fit');
			}
			$tva_options_html = get_vat_select_options($result->tva);
			$results_array[] = array('id' => $result->id,
				'reference' => $result->reference,
				'label' => (!empty($GLOBALS['site_parameters']['autocomplete_hide_images'])?'<div>':'<div class="autocomplete_image"><img src="'.$product_picture.'" /></div><div style="display:table-cell; vertical-align:middle; height:45px;">') . highlight_found_text(String::html_entity_decode($result->nom), $search, $found_words_array) . (String::strlen($result->reference) ? ' - <span class="autocomplete_reference_result">' . highlight_found_text(String::html_entity_decode($result->reference), $search, $found_words_array) . '</span>' : '') . '</div><div class="clearfix" />',
				'nom' => $result->nom,
				'prix' => fprix(String::str_form_value($result->prix)),
				'promotion' => null,
				'size_options_html' => $size_options_html,
				'color_options_html' => $color_options_html,
				'tva_options_html' => $tva_options_html,
				'prix_cat' => $prix_cat,
				'prix_cat_ht' => $prix_cat_ht,
				'purchase_prix' => $purchase_prix,
				'purchase_prix_ht' => $purchase_prix_ht,
				'purchase_prix_displayed' => $purchase_prix_displayed
				);
			unset($product_object);
		}
	}
}

if (!empty($_POST['return_json_array_with_raw_information'])) {
	$output = json_encode($results_array);
} elseif (!empty($search)) {
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_rpc.tpl');
	if (!empty($results_array)) {
		$tpl->assign('results', $results_array);
	}
	$tpl->assign('return_mode_for_displayed_values', $return_mode_for_displayed_values);
	$tpl->assign('STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER', $GLOBALS['STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_AUCUN_RESULTAT', $GLOBALS['STR_AUCUN_RESULTAT']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_PRODUITS_ADD_PRODUCT', $GLOBALS['STR_ADMIN_PRODUITS_ADD_PRODUCT']);
	$output .= $tpl->fetch();
}

echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
?>
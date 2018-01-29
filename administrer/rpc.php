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
// $Id: rpc.php 55332 2017-12-01 10:44:06Z sdelaporte $
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
$mode = vb($_POST['type']);
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
if (StringMb::strlen($search)>0) {
	if($mode=="products"){
		$queries_results_array = get_quick_search_results($search, $maxRows);
		if(!empty($queries_results_array)) {
			$is_reseller = false;
			if(!empty($id_utilisateur)) {
				$user_query = query("SELECT *
					FROM peel_utilisateurs
					WHERE id_utilisateur='" . intval($id_utilisateur) . "' AND " . get_filter_site_cond('utilisateurs') . "");
				$user_infos = fetch_assoc($user_query);
				if ($user_infos['priv'] == 'reve') {
					$is_reseller = true;
				}
				$promotion_percentage = get_current_user_promotion_percentage($user_infos);
			} else {
				$promotion_percentage = 0;
			}
			foreach($queries_results_array as $result) {
				$product_object = new Product($result->id, $result, true, null, true, !check_if_module_active('micro_entreprise'));
				// Prix hors ecotaxe
				$purchase_prix_ht = $product_object->get_final_price($promotion_percentage, false, $is_reseller) * $currency_rate;
				$purchase_prix = $product_object->get_final_price($promotion_percentage, $apply_vat, $is_reseller) * $currency_rate;
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
					$product_picture = thumbs($display_picture, 75, 75, 'fit', null, null, true, true);
				} elseif(!empty($GLOBALS['site_parameters']['default_picture'])) {
					$product_picture = thumbs($GLOBALS['site_parameters']['default_picture'], 75, 75, 'fit', null, null, true, true);
				} else {
					$product_picture = null;
				}
				$tva_options_html = get_vat_select_options($result->tva);
				$results_array[] = array('id' => $result->id,
					'reference' => $result->reference,
					'label' => (!empty($GLOBALS['site_parameters']['autocomplete_hide_images']) && !empty($product_picture)?'<div>':'<div class="autocomplete_image"><img src="'.$product_picture.'" /></div><div style="display:table-cell; vertical-align:middle; height:45px;">') . highlight_found_text(StringMb::html_entity_decode($result->nom), $search, $GLOBALS['found_words_array']) . (StringMb::strlen($result->reference) ? ' - <span class="autocomplete_reference_result">' . highlight_found_text(StringMb::html_entity_decode($result->reference), $search, $GLOBALS['found_words_array']) . '</span>' : '') . '</div><div class="clearfix" />',
					'nom' => $result->nom,
					'image' => $display_picture,
					'image_thumbs' => $product_picture,
					'prix' => fprix(StringMb::str_form_value($result->prix)),
					'promotion' => null,
					'size_options_html' => $size_options_html,
					'color_options_html' => $color_options_html,
					'tva_options_html' => $tva_options_html,
					'prix_cat' => $prix_cat,
					'prix_cat_ht' => $prix_cat_ht,
					'purchase_prix' => $purchase_prix,
					'purchase_prix_ht' => $purchase_prix_ht,
					'quantite' => (vn($result->quantity_min_order)>1?$result->quantity_min_order:1),
					'purchase_prix_displayed' => $purchase_prix_displayed
					);
				unset($product_object);
			}
		}
	} elseif($mode=="offers" && !empty($GLOBALS['site_parameters']['user_offers_table_enable'])) {
		$queries_results_array = get_quick_search_results($search, $maxRows, false, null, "offers");
		foreach($queries_results_array as $result) {
			$results_array[] = array('id' => $result->id_offre,
				'nom' => $result->num_offre,
				'user_id' => $id_utilisateur
				);
		}
	} elseif($mode=="categories") {
		$queries_results_array = get_quick_search_results($search, $maxRows, false, null, "categories");
		foreach($queries_results_array as $result) {
			$results_array[] = array('id' => $result->id,
				'nom' => $result->name
				);
		}
	} elseif($mode == "offer_add_user" && !empty($GLOBALS['site_parameters']['user_offers_table_enable'])) {
		$queries_results_array = get_quick_search_results($search, $maxRows, false, null, "offer_add_user");
		foreach($queries_results_array as $result_object) {
			$result = (array)$result_object;
			$result['msg'] = $GLOBALS['STR_ADMIN_MSG_UPDATE_OK'];
			$results_array[] = $result;
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
	$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
	if(!empty($GLOBALS['site_parameters']['user_offers_table_enable'])) {
		$tpl->assign('STR_OFFER_NO_RESULT', $GLOBALS['STR_OFFER_NO_RESULT']);
		$tpl->assign('STR_ADMIN_OFFER_ADD_OFFER', $GLOBALS['STR_ADMIN_OFFER_ADD_OFFER']);
	}
	$tpl->assign('mode', $mode);
	$output .= $tpl->fetch();
}

echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);

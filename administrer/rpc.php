<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rpc.php 37904 2013-08-27 21:19:26Z gboussin $
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
header('Content-type: text/html; charset=' . $page_encoding);
$output = '';
$search = vb($_POST['search']);
$id_utilisateur = vb($_POST['id_utilisateur']);
$apply_vat = vb($_POST['apply_vat']);
$currency = vb($_POST['currency']);
$currency_rate = vn($_POST['currency_rate']);
if (empty($currency_rate)) {
	$currency_rate = 1;
}
if (!empty($search)) {
	$query = query("SELECT *, nom_" . $_SESSION['session_langue'] . " AS nom
		FROM peel_produits
		WHERE reference LIKE '" . nohtml_real_escape_string($search) . "%' OR nom_" . $_SESSION['session_langue'] . " LIKE '%" . nohtml_real_escape_string($search) . "%' OR id='" . nohtml_real_escape_string($search) . "'
		ORDER BY IF(SUBSTRING(nom,1," . strlen($search) . ")='" . $search . "',1,0) DESC, IF(SUBSTRING(reference,1," . strlen($search) . ")='" . $search . "',1,0) DESC, nom_" . $_SESSION['session_langue'] . " ASC
		LIMIT 7");
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_rpc.tpl');
	if (num_rows($query) > 0) {
		$tpl_results = array();
		while ($result = fetch_object($query)) {
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
			$tva_options_html = get_vat_select_options($result->tva);
			$tpl_results[] = array('id' => $result->id,
				'reference' => $result->reference,
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
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_AUCUN_RESULTAT', $GLOBALS['STR_AUCUN_RESULTAT']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_PRODUITS_ADD_PRODUCT', $GLOBALS['STR_ADMIN_PRODUITS_ADD_PRODUCT']);
	$output .= $tpl->fetch();
}
echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);

?>
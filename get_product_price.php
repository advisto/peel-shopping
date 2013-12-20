<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: get_product_price.php 39392 2013-12-20 11:08:42Z gboussin $
include("configuration.inc.php");

if (empty($_POST) || empty($_POST['product_id']) || vb($_POST['hash']) != sha256('HFhza8462naf' . $_POST['product_id'])) {
	die();
}
if (!empty($_GET['encoding'])) {
	$page_encoding = $_GET['encoding'];
} else {
	$page_encoding = 'utf-8';
}
output_general_http_header($page_encoding);
$output = '';
$product_id = intval(vn($_POST['product_id']));
$attribut_list = vb($_POST['attribut_list']);
$size_id = intval(vn($_POST['size_id']));
$product_object = new Product($product_id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
$product_object->set_configuration(null, $size_id, $attribut_list, is_reseller_module_active() && is_reseller());
$product_id = intval(vn($_POST['product_id']));
$prix = $product_object->get_final_price(get_current_user_promotion_percentage(), display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), false, false, 1, true, true, true);
if(!empty($_POST['product2_id'])) {
	$product2_id = intval(vn($_POST['product2_id']));
	$product_object2 = new Product($product2_id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
	$product_object2->set_configuration(null, $size_id, $attribut_list, is_reseller_module_active() && is_reseller());
	$prix += $product_object2->get_final_price(get_current_user_promotion_percentage(), display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), false, false, 1, true, true, true);
}
$output = fprix($prix, true, null, true, null, false, true, ','); 
// On n'affiche pas d'info sur taxes pour présentation plus agréable
// . ' ' . (display_prices_with_taxes_active()?$GLOBALS['STR_TTC']:$GLOBALS['STR_HT']);
echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);

?>
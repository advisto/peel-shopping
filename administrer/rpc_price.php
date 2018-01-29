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
// $Id: rpc_price.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
define('IN_RPC', true);
define('LOAD_NO_OPTIONAL_MODULE', true);
define('SKIP_SET_LANG', true);
include("../configuration.inc.php");

if (!empty($_GET['encoding'])) {
	$page_encoding = $_GET['encoding'];
} else {
	$page_encoding = 'utf-8';
}
output_general_http_header($page_encoding);
$output = '';

if (!est_identifie() || empty($_POST)) {
	$output .= 'nok';
} else {
	// On fait les tests de droits une bonne fois pour toutes
	$new_price = $_POST['price'];
	if(a_priv("admin_products")) {
		$sql = "UPDATE peel_produits
			SET prix='%s'".(!display_prices_with_taxes_in_admin()?'*(1+tva/100)':'')."
			WHERE id='%s' AND " . get_filter_site_cond('produits', null, true) . "";
	} else {
		die('nok');
	}
	// On met à jour les positions en fonction de la liste reçue en POST
	query(sprintf($sql, floatval(get_float_from_user_input($new_price)), intval($_POST['id'])));
	$output .= 'ok';
}
echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);


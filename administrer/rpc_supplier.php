<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rpc_supplier.php 55325 2017-11-30 10:47:17Z sdelaporte $
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
$output = '';

if (!est_identifie() || empty($_POST)) {
	$output .= 'nok';
} else {
	output_general_http_header($page_encoding);
	// On fait les tests de droits une bonne fois pour toutes
	if(a_priv("admin_products")) {
		$sql = "UPDATE peel_produits
			SET id_utilisateur='". intval($_POST['id_utilisateur']) ."'
			WHERE id='". intval($_POST['id']) ."' AND " . get_filter_site_cond('produits', null, true) . "";
	} else {
		die('nok');
	}
	query($sql);
	$output .= 'ok';
}
echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);


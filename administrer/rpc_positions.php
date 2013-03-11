<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rpc_positions.php 35805 2013-03-10 20:43:50Z gboussin $
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
	header('Content-type: text/html; charset=' . $page_encoding);
	// On fait les tests de droits une bonne fois pour toutes
	if(vb($_GET['mode']) == 'countries' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_pays
			SET position='%s'
			WHERE id='%s'";
	}elseif(vb($_GET['mode']) == 'types' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_types
			SET position='%s'
			WHERE id='%s'";
	}elseif(vb($_GET['mode']) == 'tailles' && a_priv("admin_products")) {
		$sql = "UPDATE peel_tailles
			SET position='%s'
			WHERE id='%s'";
	}elseif(vb($_GET['mode']) == 'couleurs' && a_priv("admin_products")) {
		$sql = "UPDATE peel_couleurs
			SET position='%s'
			WHERE id='%s'";
	}elseif(vb($_GET['mode']) == 'paiement' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_paiement
			SET position='%s'
			WHERE id='%s'";
	} else {
		die('nok');
	}
	foreach($_POST['sortable'] as $this_form_position => $this_id) {
		// On veut commencer à 1 et non pas à 0 : ça fait plus propre
		$this_position = $this_form_position + 1;
		// On met à jour les positions en fonction de la liste reçue en POST
		query(sprintf($sql, intval($this_position), intval($this_id)));
	}
	$output .= 'ok';
}
echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);

?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an     |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/   |
// +----------------------------------------------------------------------+
// $Id: import.php 59873 2019-02-26 14:47:11Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin*");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_IMPORT_TITLE'];

// On fait la correspondance entre des URL d'export et la réalité technique de l'export à faire
// Si vide pour un type, alors il faut mettre le type directement dans l'URL
$GLOBALS['database_import_export_type_by_urlpart_array'] = array('clients' => 'peel_utilisateurs', 'produits' => 'peel_produits');
if(!empty($_POST['type'])) {
	$params['type'] = $_POST['type'];
} elseif(!empty($_GET['type'])) {
	$params['type'] = vb($GLOBALS['database_import_export_type_by_urlpart_array'][$_GET['type']], $_GET['type']);
} 
$params['mode'] = vb($_POST['mode'], vb($_GET['mode']));

$output = handle_import(true, $params);
		
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


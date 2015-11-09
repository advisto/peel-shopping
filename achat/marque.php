<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: marque.php 47592 2015-10-30 16:40:22Z sdelaporte $
define('IN_SEARCH_BRAND', true);
include("../configuration.inc.php");

$GLOBALS['page_name'] = 'marque';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_BRAND'];

$id_marque = vn($_GET['id']);
$output = '';

if(!empty($id_marque)) {
	// Affichage d'une marque avec ses produits
	$output .= get_brand_description_html($id_marque, true, false) . '<div class="clearfix"></div>' . affiche_produits($id_marque, 2, 'catalogue', $GLOBALS['site_parameters']['nb_produit_page'], 'general', true);
} else {
	// Affichage de toutes les marques disponibles sur le site
	$output .= get_brand_description_html(null, true, true);
}
include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


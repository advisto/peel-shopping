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
// $Id: marque.php 37904 2013-08-27 21:19:26Z gboussin $
include("../configuration.inc.php");

define('IN_SEARCH_BRAND', true);
$page_name = 'marque';
$id_marque = vn($_GET['id']);
$output = '';

if(!empty($id_marque)) {
	// Affichage d'une marque avec ces produits
	$output .= get_brand_description_html($id_marque, true, false) . affiche_produits($id_marque, null, 'catalogue', $GLOBALS['site_parameters']['nb_produit_page'], 'general', true);
} else {
	// affichage de toutes les marques disponible sur le site
	$output .= get_brand_description_html(null, true, true);
}
include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: index.php 39162 2013-12-04 10:37:44Z gboussin $
/*! \mainpage PEEL Shopping 7.1.1 - Open eCommerce
 * \section intro_sec PEEL Shopping
 * Visit <a href="https://www.peel.fr/">PEEL web site</a> to find more information about this open source ecommerce solution.
 * \section install_sec Installation
 * Unzip all files to your hosting space, and call http://your_url/installation
 */
include("configuration.inc.php");

$output = '';

if (strpos($_SERVER['REQUEST_URI'], '/index.php') !== false) {
	redirect_and_die($GLOBALS['wwwroot'] . "/", true);
}
$rubrique_template = 'home';
define('IN_HOME', true);

$tpl = $GLOBALS['tplEngine']->createTemplate('index.tpl');
if (is_module_vacances_active() && get_vacances_type() == 1) {
	$tpl->assign('error', vb($GLOBALS['site_parameters']['module_vacances_client_msg_' . $_SESSION['session_langue']]));
}
if (function_exists('get_home_title')) {
	$tpl->assign('home_title', get_home_title());
}

$tpl->assign('categorie_accueil', affiche_categorie_accueil(true));
if(vb($GLOBALS['site_parameters']['skip_home_top_products'])) {
	$tpl->assign('meilleurs_ventes', '');
} else {
	$tpl->assign('meilleurs_ventes', affiche_produits(null, 2, "top", 10, 'home', true, null, 2, true, false));
}
if(vb($GLOBALS['site_parameters']['skip_home_special_products'])) {
	$tpl->assign('notre_selection', '');
} else {
	$tpl->assign('notre_selection', affiche_produits(null, 2, "special", 10, 'home', true, null, 2, true, false));
}
if(vb($GLOBALS['site_parameters']['skip_home_new_products'])) {
	$tpl->assign('nouveaute', '');
} else {
	$tpl->assign('nouveaute', affiche_produits(null, 2, "nouveaute", 10, 'home', true, null, 2, true, false));
}

$tpl->assign('pub1', affiche_banner(1, true));
$tpl->assign('pub2', affiche_banner(2, true));

$tpl->assign('actu', print_actu(true, 0));

$tpl->assign('image_accueil', vb($GLOBALS['site_parameters']['general_home_image1']));
$tpl->assign('image_accueil_2', vb($GLOBALS['site_parameters']['general_home_image2']));
if(vb($GLOBALS['site_parameters']['skip_home_ad_categories_presentation']) && is_annonce_module_active()) {
	$tpl->assign('categorie_annonce', get_ad_categories_presentation(true));
}
$tpl->assign('contenu_html', affiche_contenu_html("home", true));
$tpl->assign('contenu_html_bottom', affiche_contenu_html("home_bottom", true));
$tpl->assign('center_middle_home', get_modules('center_middle_home', true));
if(is_abonnement_module_active()) {
	$tpl->assign('vitrine_list', getVerifiedVitrineList());
}
if(is_carrousel_module_active()) {
	$tpl->assign('carrousel_html',  affiche_carrousel('top_home', true, true, false));
}
$output .= $tpl->fetch();

$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['site_index_page_columns_count'];
include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
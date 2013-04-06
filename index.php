<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: index.php 36232 2013-04-05 13:16:01Z gboussin $
/*! \mainpage PEEL Shopping 7.0.2 - Open eCommerce
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
if (is_advistofr_module_active()) {
	$tpl->assign('home_title', get_home_title());
}

$tpl->assign('categorie_accueil', affiche_categorie_accueil(true));

$tpl->assign('meilleurs_ventes', affiche_produits(null, null, "top", 10, 'home', true, null, 2, true, false));
$tpl->assign('notre_selection', affiche_produits(null, null, "special", 10, 'home', true, null, 2, true, false));
$tpl->assign('nouveaute', affiche_produits(null, null, "nouveaute", 10, 'home', true, null, 2, true, false));

$tpl->assign('pub1', affiche_banner(1, true));
$tpl->assign('pub2', affiche_banner(2, true));

$tpl->assign('actu', print_actu(true, 0));

$tpl->assign('image_accueil', vb($GLOBALS['site_parameters']['general_home_image1']));
$tpl->assign('image_accueil_2', vb($GLOBALS['site_parameters']['general_home_image2']));
if(is_annonce_module_active()) {
	$tpl->assign('categorie_annonce', get_ad_categories_presentation(true));
	$tpl->assign('ads_in_focus', getVerifiedAdsList());
	$tpl->assign('ads_last', get_annonces_in_box('last'));
	$tpl->assign('ads_last_search', get_annonces_in_box('search_by_list'));
}
$tpl->assign('contenu_html', affiche_contenu_html("home", true));
$tpl->assign('contenu_html_bottom', affiche_contenu_html("home_bottom", true));
$output .= $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
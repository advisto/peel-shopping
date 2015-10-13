<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: index.php 47234 2015-10-08 11:03:43Z gboussin $
/*! \mainpage PEEL Shopping 8.0.0 - Open eCommerce
 * \section intro_sec PEEL Shopping
 * Visit <a href="https://www.peel.fr/">PEEL web site</a> to find more information about this open source ecommerce solution.
 * \section install_sec Installation
 * Unzip all files to your hosting space, and call http://your_url/installation
 */
define('IN_HOME', true);
include("configuration.inc.php");
$GLOBALS['page_name'] = 'index';

$output = '';

if (strpos($_SERVER['REQUEST_URI'], '/index.php') !== false) {
	redirect_and_die(get_url('/'), true);
}
$rubrique_template = 'home';

$tpl = $GLOBALS['tplEngine']->createTemplate('index.tpl');
if (check_if_module_active('vacances') && get_vacances_type() == 1) {
	$tpl->assign('error', vb($GLOBALS['site_parameters']['module_vacances_client_msg_' . $_SESSION['session_langue']]));
}
if (function_exists('get_home_title')) {
	$tpl->assign('home_title', get_home_title());
}
$tpl->assign('site', $GLOBALS['site'] );
$tpl->assign('categorie_accueil', affiche_categorie_accueil(true));
if(!empty($GLOBALS['site_parameters']['skip_home_top_products'])) {
	$tpl->assign('meilleurs_ventes', '');
} else {
	$tpl->assign('meilleurs_ventes', affiche_produits(null, 2, "top", 10, 'home', true, null, 4, true, false));
}
if(!empty($GLOBALS['site_parameters']['skip_home_special_products'])) {
	$tpl->assign('notre_selection', '');
} else {
	$tpl->assign('notre_selection', affiche_produits(null, 2, "special", 10, 'home', true, null, 4, true, false));
}
if(!empty($GLOBALS['site_parameters']['skip_home_new_products'])) {
	$tpl->assign('nouveaute', '');
} else {
	$tpl->assign('nouveaute', affiche_produits(null, 2, "nouveaute", 10, 'home', true, null, 4, true, false));
}
if (!est_identifie()) {
	// Si pas identifie, on regarde si on affiche les blocs de connexion et d'inscription
	if(!empty($GLOBALS['site_parameters']['skip_home_affiche_compte'])) {
		$tpl->assign('affiche_compte', '');
	} else {
		$tpl->assign('affiche_compte', affiche_compte(true, "home"));
	}

	if(!empty($GLOBALS['site_parameters']['skip_home_register_form'])) {
		$tpl->assign('user_register_form', '');
	} else {
		$form_error_object = new FormError();
		$tpl->assign('user_register_form', get_user_register_form($frm, $form_error_object, false, true, get_url('/utilisateurs/enregistrement.php')));
	}
}elseif(!empty($GLOBALS['site_parameters']['home_affiche_compte_loggedin'])) {
	$tpl->assign('affiche_compte', affiche_compte(true, "home"));
}
if(!empty($GLOBALS['site_parameters']['home_affiche_banner_ids'])) {
	foreach($GLOBALS['site_parameters']['home_affiche_banner_ids'] as $this_id) {
		$tpl->assign('pub'.$this_id, affiche_banner($this_id, true));
	}
}

$tpl->assign('actu', print_actu(true, 0));

$tpl->assign('image_accueil', vb($GLOBALS['site_parameters']['general_home_image1']));
$tpl->assign('image_accueil_2', vb($GLOBALS['site_parameters']['general_home_image2']));

if(empty($GLOBALS['site_parameters']['skip_home_ad_categories_presentation']) && check_if_module_active('annonces')) {
	// Ajoute la liste des catégories d'annonces sur la home
	$tpl->assign('categorie_annonce', get_ad_categories_presentation(true));
	// Présentation des annonces récentes. Fonction multisite qui affiche toutes les annonces récente et les classe par site.
	if (!empty($GLOBALS['site_parameters']['display_home_fresh_ad_presentation'])) {
		// L'entête de la liste d'annonce est différent selon les sites :
		$tpl->assign('fresh_ad_presentation', get_fresh_ad_presentation());
	}
}


$tpl->assign('contenu_html', affiche_contenu_html("home", true));
$tpl->assign('contenu_html_bottom', affiche_contenu_html("home_bottom", true));
$tpl->assign('center_middle_home', get_modules('center_middle_home', true));
if(check_if_module_active('abonnement')) {
	$tpl->assign('vitrine_list', getVerifiedVitrineList());
}
if(check_if_module_active('carrousel')) {
	$tpl->assign('carrousel_html',  Carrousel::display('top_home', true, vb($GLOBALS['site_parameters']['module_carrousel_top_home_show_pagination'], true), vb($GLOBALS['site_parameters']['module_carrousel_top_home_show_previous_next_buttons'], false)));
	if(vb($GLOBALS['site_parameters']['module_carrousel_display_categorie_on_homepage'])) {
		$tpl->assign('CARROUSEL_CATEGORIE', '<div class="affiche_contenu_entre_module">'. affiche_contenu_html("entre_carrousel", true) .'</div>' . Carrousel::display('categorie', true, false, false));
	}
}
$output .= $tpl->fetch();

$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['site_index_page_columns_count'];
include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

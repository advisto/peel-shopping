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
// $Id: index.php 55332 2017-12-01 10:44:06Z sdelaporte $
/*! \mainpage PEEL Shopping 9.0.0 - Open eCommerce
 * \section intro_sec PEEL Shopping
 * Visit <a href="https://www.peel.fr/">PEEL web site</a> to find more information about this open source ecommerce solution.
 * \section install_sec Installation
 * Unzip all files to your hosting space, and call http://your_url/installation
 */
define('IN_HOME', true);
include("configuration.inc.php");
$GLOBALS['page_name'] = 'index';

$output = '';

if (empty($GLOBALS['site_parameters']['disable_index_redirection']) && strpos($_SERVER['REQUEST_URI'], '/index.php') !== false) {
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
$tpl->assign('site', $GLOBALS['site']);
if(!empty($GLOBALS['site_parameters']['skip_home_categorie_accueil'])) {
	$tpl->assign('categorie_accueil', '');
} else {
	$tpl->assign('categorie_accueil', affiche_categorie_accueil(true));
}

if(!empty($GLOBALS['site_parameters']['skip_home_top_products'])) {
	$tpl->assign('meilleurs_ventes', '');
} else {
	$tpl->assign('meilleurs_ventes', affiche_produits(null, 2, "top", 10, 'home', true, null, 4, true, false));
}
if(!empty($GLOBALS['site_parameters']['skip_home_special_products'])) {
	$tpl->assign('notre_selection', '');
} else {
	$tpl->assign('notre_selection', affiche_produits(null, 2, "special", 10, 'home', true, null, (empty($GLOBALS['site_parameters']['notre_selection_index_page_columns_count'])?4:$GLOBALS['site_parameters']['notre_selection_index_page_columns_count']), true, false));
}
if(!empty($GLOBALS['site_parameters']['skip_home_new_products'])) {
	$tpl->assign('nouveaute', '');
} else {
	$tpl->assign('nouveaute', affiche_produits(null, 2, "nouveaute", 10, 'home', true, null, 4, true, false));
}
$tpl->assign('focus_article', get_articles_html(0, false, 0, $GLOBALS['site_id']));
$tpl->assign('nouveaute_article', get_articles_html(0, false, $GLOBALS['site_id'], 0, 'articles_html'));

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
if (function_exists('html_zone_custom_template_tags')) {
	$custom_template_tags = html_zone_custom_template_tags('home');
} else {
	$custom_template_tags = array();
}
$tpl->assign('contenu_html', affiche_contenu_html("home", true, $custom_template_tags));
$tpl->assign('center_middle_home', get_modules('center_middle_home', true));
$tpl->assign('home_middle_top', affiche_contenu_html('home_middle_top', true)); 
$tpl->assign('home_middle', affiche_contenu_html('home_middle', true));
$tpl->assign('website_type', vb($GLOBALS['site_parameters']['website_type']));

$modules_left = get_modules('home_left', true, null, null);
$tpl->assign('MODULES_LEFT', $modules_left);

$hook_result = call_module_hook('index_form_template_data', array(), 'array');
foreach($hook_result as $this_key => $this_value) {
	$tpl->assign($this_key, $this_value);
}

$output .= $tpl->fetch();

$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['site_index_page_columns_count'];
include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

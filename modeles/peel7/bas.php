<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: bas.php 44077 2015-02-17 10:20:38Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('bas.tpl');
$tpl->assign('MODULES_BOTTOM_MIDDLE', get_modules('bottom_middle', true, null, vn($_GET['catid'])));
$tpl->assign('page_columns_count', $GLOBALS['page_columns_count']);
if ($GLOBALS['page_columns_count'] == 3) {
	$modules_right = '';
	if(check_if_module_active('annonces')) {
		$modules_right .= get_modules('right_annonce', true, null, vn($_GET['catid'])); 
	}
	$modules_right .= get_modules('below_middle', true, null, vn($_GET['catid']));
	$tpl->assign('MODULES_RIGHT', $modules_right);
}
$tpl->assign('IN_HOME', defined('IN_HOME'));
if (defined('IN_HOME')) {
	$tpl->assign('CONTENT_HOME_BOTTOM', affiche_contenu_html("home_bottom", true));
}
$tpl->assign('CONTENT_FOOTER', affiche_contenu_html("footer", true));
$tpl->assign('MODULES_FOOTER', get_modules('footer', true, null, vn($_GET['catid'])));

$tpl->assign('FOOTER', affiche_footer(true));
$tpl->assign('footer_columns_width_sm', vb($GLOBALS['site_parameters']['footer_columns_width_sm'], 4));
$tpl->assign('footer_columns_width_md', vb($GLOBALS['site_parameters']['footer_columns_width_md'], 3));

$tpl->assign('flags', affiche_flags(true, null, false, $GLOBALS['lang_codes'], false, 26));
if (is_devises_module_active()) {
	$tpl->assign('module_devise', affiche_module_devise(true));
}

if (!empty($_SESSION['session_display_popup']['error_text'])) {
	// Dévelopement de la popup affichant les détails de l'ajout au caddie (si la quantité demandée est supérieure à la quantité disponible en stock) et suppression de la variable de session
	$GLOBALS['js_ready_content_array'][] = "bootbox.alert('".filtre_javascript($_SESSION['session_display_popup']['error_text'], true, true, false, true, false) ."')";
	unset($_SESSION['session_display_popup']['error_text']);
}
// Message d'alerte de problème de téléchargement d'image
if (!empty($_SESSION["session_display_popup"]["no_uploaded_image"])) {
	unset($_SESSION["session_display_popup"]["no_uploaded_image"]);
}

$tpl->assign('tag_analytics', get_tag_analytics());
if (is_butterflive_module_active()) {
	$tpl->assign('butterflive_tracker', get_butterflive_tracker());
}

$end_javascript = '';
if (!empty($GLOBALS['site_parameters']['google_premium_account_id']) && !empty($GLOBALS['integrate_javascript_google_ads']) && empty($GLOBALS['disable_google_ads']) && strpos($GLOBALS['wwwroot'], '://localhost')===false && strpos($GLOBALS['wwwroot'], '://127.0.0.1')===false && check_if_module_active('annonces')) {
	include($GLOBALS['dirroot'] . '/modules/annonces/fonctions_google_premium.php');
	if (!empty($GLOBALS['integrate_afs_ads'])) {
		$end_javascript .= get_google_afs_script($GLOBALS['afs_searched'], (isset($_GET['page'])?$_GET['page']:(isset($_GET['pageNum_rs1'])?$_GET['pageNum_rs1'] + 1:1)));
	} else {
		$end_javascript .= get_google_afc_script((isset($_GET['page'])?$_GET['page']:1));
	}
}
if (!empty($GLOBALS['site_parameters']['twenga_ads_account_url']) && !empty($GLOBALS['integrate_twenga_ads']) && !empty($GLOBALS['twenga_ads_searched']) && strpos($GLOBALS['wwwroot'], '://localhost')===false && strpos($GLOBALS['wwwroot'], '://127.0.0.1')===false && check_if_module_active('annonces')) {
	include($GLOBALS['dirroot'] . '/modules/annonces/fonctions_twenga_ads.php');
	$end_javascript .= get_twenga_ads_script($GLOBALS['twenga_ads_searched'], (isset($_GET['page'])?$_GET['page']:1));
}
$tpl->assign('end_javascript', $end_javascript);

if (defined('PEEL_DEBUG') && PEEL_DEBUG == true) {
	$tpl->assign('peel_debug', $GLOBALS['peel_debug']);
}
$tpl->assign('DEBUG_TEMPLATES', DEBUG_TEMPLATES);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
// Si js_files pas mis dans haut.php (chargement asynchrone ou demandés entre temps dans la génération PHP)
// Au cas où on veuille mettre les javascript en pied de <body> au lieu de head, pour plus de vitesse (mais moins conforme aux usages)
$tpl->assign('js_output', get_javascript_output(!empty($GLOBALS['site_parameters']['load_javascript_async']), !empty($GLOBALS['site_parameters']['minify_js'])));

$tpl->assign('rss', affiche_social_icons(true));
$tpl->assign('footer_link', affiche_contenu_html("footer_link", true));
$tpl->assign('block_columns_width_sm', vb($GLOBALS['site_parameters']['block_columns_width_sm'], 4));
$tpl->assign('block_columns_width_md', vb($GLOBALS['site_parameters']['block_columns_width_md'], 3));
if(function_exists('get_footer_bottom')) {
	$footer_bottom = get_footer_bottom();
} else {
	$footer_bottom = vb($GLOBALS['site_parameters']['footer_bottom']);
}
$tpl->assign('footer_bottom', $footer_bottom);
if(function_exists('get_footer_column')) {
	$tpl->assign('footer_column', get_footer_column());
}

echo $tpl->fetch();

// Clôture de la génération de page - affiche des informations de debug si mode debug activé
echo close_page_generation();

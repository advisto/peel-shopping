<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: bas.php 39126 2013-12-03 16:12:18Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('bas.tpl');
$tpl->assign('MODULES_BOTTOM_MIDDLE', get_modules('bottom_middle', true, null, vn($_GET['catid'])));
$tpl->assign('page_columns_count', $GLOBALS['page_columns_count']);
if ($GLOBALS['page_columns_count'] == 3) {
	$modules_right = '';
	if(is_annonce_module_active()) {
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
$tpl->assign('flags', affiche_flags(true, null, false, $GLOBALS['lang_codes'], true, 26));
if (is_devises_module_active()) {
	$tpl->assign('module_devise', affiche_module_devise(true));
}

if (!empty($_SESSION['session_display_popup']['error_text'])) {
	// Dévelopement de la popup affichant les détails de l'ajout au caddie (si la quantité demandée est supérieure à la quantité disponible en stock) et suppression de la variable de session
	$GLOBALS['js_content_array'][] = "bootbox.alert('".filtre_javascript($_SESSION['session_display_popup']['error_text'],true,true,false) ."')";
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
if (!empty($GLOBALS['site_parameters']['google_premium_account_id']) && !empty($GLOBALS['integrate_javascript_google_ads']) && empty($GLOBALS['disable_google_ads']) && strpos($GLOBALS['wwwroot'], '://localhost')===false && strpos($GLOBALS['wwwroot'], '://127.0.0.1')===false && is_annonce_module_active()) {
	include($GLOBALS['dirroot'] . '/modules/annonces/fonctions_google_premium.php');
	if (!empty($GLOBALS['integrate_afs_ads'])) {
		$end_javascript = get_google_afs_script($GLOBALS['afs_searched'], (isset($_GET['page'])?$_GET['page']:(isset($_GET['pageNum_rs1'])?$_GET['pageNum_rs1'] + 1:1)));
	} else {
		$end_javascript = get_google_afc_script((isset($_GET['page'])?$_GET['page']:1));
	}
	$tpl->assign('end_javascript', $end_javascript);
}
if (defined('PEEL_DEBUG') && PEEL_DEBUG == true) {
	$tpl->assign('peel_debug', $GLOBALS['peel_debug']);
}
$tpl->assign('DEBUG_TEMPLATES', DEBUG_TEMPLATES);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
// Si js_files pas mis dans haut.php (chargement asynchrone ou demandés entre temps dans la génération PHP)
// Au cas où on veuille mettre les javascript en pied de <body> au lieu de head, pour plus de vitesse (mais moins conforme aux usages)
$tpl->assign('js_output', get_javascript_output(!empty($GLOBALS['site_parameters']['load_javascript_async']), !empty($GLOBALS['site_parameters']['minify_js'])));
if (is_module_rss_active()) {
	$tpl->assign('rss', affiche_rss(true));
}
$tpl->assign('footer_link', affiche_contenu_html("footer_link", true));

echo $tpl->fetch();

// Clôture de la génération de page - affiche des informations de debug si mode debug activé
echo close_page_generation();

?>
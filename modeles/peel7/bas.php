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
// $Id: bas.php 36232 2013-04-05 13:16:01Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('bas.tpl');
$tpl->assign('MODULES_BOTTOM_MIDDLE', get_modules('bottom_middle', true, null, vn($_GET['catid'])));
$tpl->assign('page_columns_count', $GLOBALS['page_columns_count']);
if ($GLOBALS['page_columns_count'] == 3) {
	$tpl->assign('MODULES_RIGHT', get_modules('right', true, null, vn($_GET['catid'])));
}
$tpl->assign('IN_HOME', defined('IN_HOME'));
if (defined('IN_HOME')) {
	$tpl->assign('CONTENT_HOME_BOTTOM', affiche_contenu_html("home_bottom", true));
}
$tpl->assign('CONTENT_FOOTER', affiche_contenu_html("footer", true));
$tpl->assign('MODULES_FOOTER', get_modules('footer', true, null, vn($_GET['catid'])));
$tpl->assign('FOOTER', affiche_footer(true));
// Dévelopement de la popup affichant les détail de l'ajout au caddie (si la quantité demandée est supérieure à la quantité disponible en stock) et suppression de la variable de session
if (is_stock_advanced_module_active() && !empty($_SESSION['session_display_popup']['error_text'])) {
	// $tpl->assign('add_cart_alert', filtre_javascript($_SESSION['session_display_popup']['add_cart'], true, true, false));
	$tpl->assign('add_cart_alert', $_SESSION['session_display_popup']['error_text']);
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
if (!empty($GLOBALS['google_premium_account_id']) && !empty($GLOBALS['integrate_javascript_google_ads']) && empty($GLOBALS['disable_google_ads']) && strpos($GLOBALS['wwwroot'], '://localhost')===false && strpos($GLOBALS['wwwroot'], '://127.0.0.1')===false && is_annonce_module_active()) {
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
// Au cas où on veuille mettre les javascript en pied de <body> au lieu de head, pour plus de vitesse (mais moins conforme aux usages)
$tpl->assign('js_files', $GLOBALS['js_files']);
echo $tpl->fetch();

// Clôture de la génération de page - affiche des informations de debug si mode debug activé
echo close_page_generation();

?>
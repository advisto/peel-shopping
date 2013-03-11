<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: haut.php 35805 2013-03-10 20:43:50Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}
if (empty($GLOBALS['page_name']) && function_exists('get_current_page')) {
	$GLOBALS['page_name'] = get_current_page();
}
// header_html va être rempli par getHTMLHead
$GLOBALS['header_html'] = '';
if (empty($GLOBALS['page_columns_count'])) {
	$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['site_general_columns_count'];
}
header('Content-type: text/html; charset=' . GENERAL_ENCODING);

$tpl = $GLOBALS['tplEngine']->createTemplate('haut.tpl');
$tpl->assign('page_columns_count', $GLOBALS['page_columns_count']);
// header-html est passé par référence à getHTMLHead pour être rempli
$tpl->assign('lang', $_SESSION['session_langue']);
if (is_facebook_module_active()) {
	$tpl->assign('facebook_xmls', get_facebook_xmlns());
}
$tpl->assign('HTML_HEAD', getHTMLHead(vb($page_name), $header_html));
if (!defined('IN_PEEL_ADMIN') && !defined('IN_ACCES_ACCOUNT') && $GLOBALS['site_parameters']['site_suspended'] === 'TRUE' && a_priv('admin')) {
	$tpl->assign('update_msg', $GLOBALS['STR_UPDATE_WEBSITE']);
}
if (is_facebook_connect_module_active()) {
	if (empty($_SESSION['session_utilisateur']['email']) && empty($_SESSION['session_utilisateur']['disable_facebook_autologin'])) {
		$tpl->assign('auto_login_with_facebook', auto_login_with_facebook(true));
	} elseif (!empty($_SESSION['session_utilisateur']['email'])) {
		$tpl->assign('logout_with_facebook', logout_with_facebook(true));
	}
}

if (is_welcome_ad_module_active()) {
	$tpl->assign('welcome_ad_div', get_welcome_ad_div());
}
if (is_cart_popup_module_active() && !empty($_SESSION['session_show_caddie_popup'])) {
	$tpl->assign('cart_popup_div', get_cart_popup_div());
	unset($_SESSION['session_show_caddie_popup']);
}
$tpl->assign('flags', affiche_flags(true));

if (is_devises_module_active()) {
	$tpl->assign('module_devise', affiche_module_devise(true));
}
if (!empty($GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']]) && $GLOBALS['site_parameters']['on_logo'] == 1) {
	$tpl->assign('logo_link',
		array('href' => $GLOBALS['wwwroot'] . '/',
			'src' => $GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']])
		);
}

$tpl->assign('header_html', $header_html);
$tpl->assign('repertoire_images', $GLOBALS['repertoire_images']);
$tpl->assign('MODULES_HEADER', get_modules('header', true, null, vn($_GET['catid'])));
$tpl->assign('CONTENT_HEADER', affiche_contenu_html('header', true));
$tpl->assign('MODULES_ARIANE', get_modules(null, true, 'ariane', vn($_GET['catid'])));
$tpl->assign('CONTENT_SCROLLING', affiche_contenu_html('scrolling', true));

if (is_carrousel_module_active()) {
	$tpl->assign('CARROUSEL_CATEGORIE', affiche_carrousel('categorie', true));
}
if ($GLOBALS['page_columns_count'] > 1) {
	$tpl->assign('MODULES_LEFT', get_modules('left', true, null, vn($_GET['catid'])));
	if (is_vitrine_module_active() && isset($_GET['bt'])) {
		$tpl->assign('user_information_boutique', display_user_information_boutique($_GET['bt']));
	}
}

if (is_module_ariane_panier_active() && (defined('IN_CADDIE') || defined('IN_STEP1') || defined('IN_STEP2') || defined('IN_STEP3'))) {
	$tpl->assign('ariane_panier', ariane_panier());
}
$tpl->assign('MODULES_TOP_MIDDLE', get_modules('top_middle', true, null, vn($_GET['catid'])));
$tpl->assign('error_text_to_display', vb($GLOBALS['error_text_to_display']));

echo $tpl->fetch();

?>
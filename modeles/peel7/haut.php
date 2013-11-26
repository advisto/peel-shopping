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
// $Id: haut.php 39003 2013-11-25 17:02:11Z sdelaporte $
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
$GLOBALS['page_columns_count'] = 3;

output_general_http_header();

$tpl = $GLOBALS['tplEngine']->createTemplate('haut.tpl');
$tpl->assign('page_columns_count', $GLOBALS['page_columns_count']);
// header-html est passé par référence à getHTMLHead pour être rempli
$tpl->assign('lang', $_SESSION['session_langue']);
if (is_facebook_module_active()) {
	$tpl->assign('facebook_xmls', get_facebook_xmlns());
}
$tpl->assign('page_name', vb($GLOBALS['page_name']));
if (!defined('IN_PEEL_ADMIN') && !defined('IN_ACCES_ACCOUNT') && vb($GLOBALS['site_parameters']['site_suspended']) && a_priv('admin')) {
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
	load_welcome_ad();
	$tpl->assign('welcome_ad_div', get_welcome_ad_div());
}
if (is_cart_popup_module_active() && !empty($_SESSION['session_show_caddie_popup'])) {
	$tpl->assign('cart_popup_div', get_cart_popup_div());
}
$tpl->assign('flags', affiche_flags(true, null, false, $GLOBALS['lang_codes'], true, 26));

if (is_devises_module_active()) {
	$tpl->assign('module_devise', affiche_module_devise(true));
}
if (!empty($GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']]) && $GLOBALS['site_parameters']['on_logo'] == 1) {
	$tpl->assign('logo_link',
		array('href' => $GLOBALS['wwwroot'] . '/',
			'src' => $GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']])
		);
}

$tpl->assign('repertoire_images', $GLOBALS['repertoire_images']);
$tpl->assign('MODULES_HEADER', get_modules('header', true, null, vn($_GET['catid'])));
if(empty($_COOKIE['page_warning_close']) || $_COOKIE['page_warning_close']!='closed') {
	$tpl->assign('CONTENT_HEADER', affiche_contenu_html('header', true));
} else {
	$tpl->assign('CONTENT_HEADER', null);
}
$tpl->assign('MODULES_ARIANE', get_modules(null, true, 'ariane', vn($_GET['catid'])));
$tpl->assign('CONTENT_SCROLLING', affiche_contenu_html('scrolling', true));

if (is_carrousel_module_active()) {
	$tpl->assign('CARROUSEL_CATEGORIE', affiche_carrousel('categorie', true));
}
if ($GLOBALS['page_columns_count'] > 1) {
	$modules_left = '';
	if((defined('IN_CATALOGUE_ANNONCE') || defined('IN_CATALOGUE') || defined('IN_CATALOGUE_ANNONCE_DETAILS')) && is_annonce_module_active()) {
		$modules_left .= get_modules('left_annonce', true, null, vn($_GET['catid'])); 
	}
	$modules_left .= get_modules('above_middle', true, null, vn($_GET['catid']));
	$tpl->assign('MODULES_LEFT', $modules_left);
}
if (is_vitrine_module_active() && !empty($GLOBALS['vitrine_and_user_infos'])) {
	if(!empty($GLOBALS['vitrine_and_user_infos']['id_vitrine'])) {
		$id_vitrine = $GLOBALS['vitrine_and_user_infos']['id_vitrine'];
	} else {
		$id_vitrine = $GLOBALS['vitrine_and_user_infos']['id'];
	}
	$tpl->assign('user_information_boutique', display_user_information_boutique($id_vitrine));
}

if (is_module_ariane_panier_active() && (defined('IN_CADDIE') || defined('IN_STEP1') || defined('IN_STEP2') || defined('IN_STEP3'))) {
	$tpl->assign('ariane_panier', ariane_panier());
}
$tpl->assign('MODULES_TOP_MIDDLE', get_modules('top_middle', true, null, vn($_GET['catid'])));
$tpl->assign('error_text_to_display', vb($GLOBALS['error_text_to_display']));
if(!empty($_SERVER['HTTP_USER_AGENT']) && (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad'))) {
	// Site vu sur ipad, ipod ou iphone alors qu'une application Appstore existe => on met un lien vers elle en début de page
	if(!empty($GLOBALS['site_parameters']['appstore_link_'.$_SESSION['session_langue']])){
		$appstore_link = $GLOBALS['site_parameters']['appstore_link_'.$_SESSION['session_langue']];
	}else {
		$appstore_link = vb($GLOBALS['site_parameters']['appstore_link']);
	}
	if(!empty($appstore_link)) {
		$tpl->assign('appstore_link', $appstore_link);
		if(in_array($_SESSION['session_langue'], array('fr', 'en'))){
			$appstore_image = $GLOBALS['wwwroot'] . '/images/download_appstore_'.$_SESSION['session_langue'].'.png';
		}else {
			$appstore_image = $GLOBALS['wwwroot'] . '/images/download_appstore_en.png';
		}
		$tpl->assign('appstore_image', $appstore_image);
	}
}
$tpl->assign('est_identifie', est_identifie());
$tpl->assign('show_open_account', est_identifie() && !empty($GLOBALS['site_parameters']['show_open_account']));
if(!empty($_SESSION['session_utilisateur']['email'])) {
	$tpl->assign('session_utilisateur_email', $_SESSION['session_utilisateur']['email']);
}
$tpl->assign('account_dropdown', affiche_compte(true, 'popup'));
$tpl->assign('STR_LOGIN', $GLOBALS['STR_LOGIN']);
$tpl->assign('account_register_url', get_account_register_url(false, false));
$tpl->assign('STR_OPEN_ACCOUNT', $GLOBALS['STR_OPEN_ACCOUNT']);

// A exécuter en dernier dans ce fichier car prend tous les javascripts
// header_html est passé par référence à getHTMLHead pour être rempli
$tpl->assign('HTML_HEAD', getHTMLHead(vb($GLOBALS['page_name']), $GLOBALS['header_html']));
$tpl->assign('header_html', $GLOBALS['header_html']);
echo $tpl->fetch();

?>
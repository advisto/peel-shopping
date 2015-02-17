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
// $Id: haut.php 44077 2015-02-17 10:20:38Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}
if (empty($GLOBALS['page_name']) && function_exists('get_current_page')) {
	$GLOBALS['page_name'] = get_current_page();
}
// header_html va être rempli par getHTMLHead
$GLOBALS['header_html'] = '';
if (empty($GLOBALS['page_columns_count'])) {
	$GLOBALS['page_columns_count'] = vn($GLOBALS['site_parameters']['site_general_columns_count'], 3);
}

if(!empty($output) && (String::strpos($output, 'vimeo.') !== false || String::strpos($output, 'youtube.') !== false || String::strpos($output, 'youtube-nocookie.') !== false || String::strpos($output, 'kickstarter.') !== false)) {
	// Gestion responsive des vidéos
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/jquery.fitvid.js';
	$GLOBALS['js_ready_content_array'][] = '
		$("#main_content").fitVids();
';
}

output_general_http_header();

$tpl = $GLOBALS['tplEngine']->createTemplate('haut.tpl');
$tpl->assign('page_columns_count', $GLOBALS['page_columns_count']);
$tpl->assign('disable_header_login', !empty($GLOBALS['site_parameters']['disable_header_login']));
// header-html est passé par référence à getHTMLHead pour être rempli
$tpl->assign('lang', $_SESSION['session_langue']);
if (check_if_module_active('facebook')) {
	$tpl->assign('facebook_xmls', get_facebook_xmlns());
}
$tpl->assign('page_name', vb($GLOBALS['page_name']));
if (!defined('IN_PEEL_ADMIN') && !defined('IN_ACCES_ACCOUNT') && vb($GLOBALS['site_parameters']['site_suspended']) && a_priv('admin')) {
	$tpl->assign('update_msg', $GLOBALS['STR_UPDATE_WEBSITE']);
}
if (is_facebook_connect_module_active()) {
	if (empty($_SESSION['session_utilisateur']['email']) && empty($_SESSION['disable_facebook_autologin'])) {
		$tpl->assign('auto_login_with_facebook', auto_login_with_facebook(true));
	} elseif (!empty($_SESSION['session_utilisateur']['email'])) {
		$tpl->assign('logout_with_facebook', logout_with_facebook(true));
	}
}

if (check_if_module_active('welcome_ad')) {
	load_welcome_ad();
	$tpl->assign('welcome_ad_div', get_welcome_ad_div());
}
if (check_if_module_active('cart_popup') && !empty($_SESSION['session_show_caddie_popup'])) {
	if (defined('IN_CATALOGUE_PRODUIT') && !empty($_GET['id'])) {
		$product_added_id = $_GET['id'];
	}
	$tpl->assign('cart_popup_div', get_cart_popup_div(vb($product_added_id)));
}
$tpl->assign('flags', affiche_flags(true, null, false, $GLOBALS['lang_codes'], false, 26));

if (is_devises_module_active()) {
	$tpl->assign('module_devise', affiche_module_devise(true));
}
if (!empty($GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']]) && $GLOBALS['site_parameters']['on_logo'] == 1) {
	$this_logo = $GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']];
	if(String::strpos($this_logo, '//') === false && String::substr($this_logo, 0, 1) == '/') {
		// Chemin absolu
		$this_logo = $GLOBALS['wwwroot'] . $this_logo;
	}
	$tpl->assign('logo_link',
		array('href' => $GLOBALS['wwwroot'] . '/',
			'src' => $this_logo)
		);
}

$tpl->assign('repertoire_images', $GLOBALS['repertoire_images']);
$tpl->assign('MODULES_HEADER', get_modules('header', true, null, vn($_GET['catid'])));
if(empty($_COOKIE['page_warning_close']) || $_COOKIE['page_warning_close']!='closed') {
	$tpl->assign('CONTENT_HEADER', affiche_contenu_html('header', true));
} else {
	$tpl->assign('CONTENT_HEADER', null);
}
$tpl->assign('CONTENT_SCROLLING', affiche_contenu_html('scrolling', true));

if(empty($GLOBALS['site_parameters']['skip_carrousel_categorie']) && check_if_module_active('carrousel')) {
	$tpl->assign('CARROUSEL_CATEGORIE', affiche_carrousel('categorie', true));
}
if ($GLOBALS['page_columns_count'] > 1) {
	$modules_left = '';
	if((defined('IN_CATALOGUE_ANNONCE') || defined('IN_CATALOGUE') || defined('IN_CATALOGUE_ANNONCE_DETAILS')) && check_if_module_active('annonces')) {
		$modules_left .= get_modules('left_annonce', true, null, vn($_GET['catid'])); 
	}
	$modules_left .= get_modules('above_middle', true, null, vn($_GET['catid']));
	$tpl->assign('MODULES_LEFT', $modules_left);
}
if (check_if_module_active('vitrine') && !empty($GLOBALS['vitrine_and_user_infos'])) {
	if(!empty($GLOBALS['vitrine_and_user_infos']['id_vitrine'])) {
		$id_vitrine = $GLOBALS['vitrine_and_user_infos']['id_vitrine'];
	} else {
		$id_vitrine = $GLOBALS['vitrine_and_user_infos']['id'];
	}
	$tpl->assign('user_information_boutique', display_user_information_boutique($id_vitrine));
}

if (check_if_module_active('ariane_panier') && (defined('IN_CADDIE') || defined('IN_STEP1') || defined('IN_STEP2') || defined('IN_STEP3'))) {
	$tpl->assign('ariane_panier', ariane_panier());
}
$tpl->assign('MODULES_TOP_MIDDLE', get_modules('top_middle', true, null, vn($_GET['catid'])));
$tpl->assign('output_create_or_update_order', vb($GLOBALS['output_create_or_update_order']));
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
	$tpl->assign('session_utilisateur_email', String::str_shorten($_SESSION['session_utilisateur']['email'], vb($GLOBALS['site_parameters']['login_in_header_length'], 20)));
}
$tpl->assign('account_dropdown', affiche_compte(true, 'popup'));
$tpl->assign('STR_LOGIN', $GLOBALS['STR_LOGIN']);
$tpl->assign('account_register_url', get_account_register_url(false, false));
$tpl->assign('STR_OPEN_ACCOUNT', $GLOBALS['STR_OPEN_ACCOUNT']);

// A exécuter en dernier dans ce fichier car prend tous les javascripts
// category_introduction_text est passé par référence à getHTMLHead pour être rempli
$tpl->assign('HTML_HEAD', getHTMLHead(vb($GLOBALS['page_name']), $GLOBALS['category_introduction_text']));
$tpl->assign('category_introduction_text', $GLOBALS['category_introduction_text']);
echo $tpl->fetch();


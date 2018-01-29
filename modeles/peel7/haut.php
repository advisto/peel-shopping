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
// $Id: haut.php 55930 2018-01-29 08:36:36Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}
if (empty($GLOBALS['page_name']) && function_exists('get_current_page')) {
	$GLOBALS['page_name'] = get_current_page();
}
if (empty($GLOBALS['page_columns_count'])) {
	$GLOBALS['page_columns_count'] = vn($GLOBALS['site_parameters']['site_general_columns_count'], 3);
}

if(!empty($output) && (StringMb::strpos($output, 'vimeo.') !== false || StringMb::strpos($output, 'youtube.') !== false || StringMb::strpos($output, 'youtube-nocookie.') !== false || StringMb::strpos($output, 'kickstarter.') !== false)) {
	// Gestion responsive des vidéos
	$GLOBALS['js_files'][] = get_url('/lib/js/jquery.fitvid.js');
	$GLOBALS['js_ready_content_array'][] = '
		$("#main_content").fitVids();
';
}

output_general_http_header(null, (est_identifie()?null:vb($GLOBALS['site_parameters']['page_cache_if_not_loggued_in_seconds'])));

$tpl = $GLOBALS['tplEngine']->createTemplate('haut.tpl');
$tpl->assign('page_columns_count', $GLOBALS['page_columns_count']);
$tpl->assign('disable_navbar_toggle', !empty($GLOBALS['site_parameters']['disable_navbar_toggle']));
$tpl->assign('disable_header_login', !empty($GLOBALS['site_parameters']['disable_header_login']));
// header-html est passé par référence à getHTMLHead pour être rempli
$tpl->assign('lang', $_SESSION['session_langue']);
$tpl->assign('page_name', vb($GLOBALS['page_name']));
if (!defined('IN_PEEL_ADMIN') && vb($GLOBALS['site_parameters']['site_suspended'])) {
	$tpl->assign('update_msg', $GLOBALS['STR_UPDATE_WEBSITE']);
}
$tpl->assign('flags', affiche_flags(true, null, false, $GLOBALS['lang_codes'], false, 26));

if (!empty($GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']]) && $GLOBALS['site_parameters']['on_logo'] == 1) {
	$this_logo = $GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']];
	if(StringMb::strpos($this_logo, '//') === false && StringMb::substr($this_logo, 0, 1) == '/') {
		// Chemin absolu
		$this_logo = $GLOBALS['wwwroot'] . $this_logo;
	}
	if (!empty($GLOBALS['site_parameters']['main_site_id'])) {
		// Il y a plusieurs sites et un site principal est défini. L'url du logo dans le header doit pointer vers le site principal.
		$logo_href = get_site_wwwroot($GLOBALS['site_parameters']['main_site_id'], $_SESSION['session_langue']);
	} else {
		// Lien vers la home défini pour la configuration du site.
		$logo_href = $GLOBALS['wwwroot'];
	}
	$tpl->assign('logo_link',
		array('href' => $logo_href . '/',
			'src' => $this_logo,
			'alt' => $GLOBALS['site']));
			
	//Permet d'administrer plus que un logo
	if(!empty($GLOBALS['site_parameters']['multi_logo_header'])){
		foreach ($GLOBALS['site_parameters']['multi_logo_header'] as $multi_logo_header => $link_multi_logo_header){
			$link = $GLOBALS['repertoire_images'] . '/' . $link_multi_logo_header;
			$array_link_multi_logo_header[] = array('class' => $multi_logo_header, 'src' => $link, 'href' => $logo_href . '/', 'alt' => $GLOBALS['site'] );
		}
		$tpl->assign('multi_logo_header', $array_link_multi_logo_header);
	}
}

$tpl->assign('repertoire_images', $GLOBALS['repertoire_images']);
$tpl->assign('MODULES_HEADER', get_modules('header', true, null, vn($_GET['catid'])));
if(empty($_COOKIE['page_warning_close']) || $_COOKIE['page_warning_close']!='closed') {
	$tpl->assign('CONTENT_HEADER', affiche_contenu_html('header', true));
} else {
	$tpl->assign('CONTENT_HEADER', null);
}
$tpl->assign('CONTENT_HEADER_LOGIN', affiche_contenu_html('header_login', true));
$tpl->assign('CONTENT_SCROLLING', affiche_contenu_html('scrolling', true));

if(empty($GLOBALS['site_parameters']['skip_carrousel_categorie']) && check_if_module_active('carrousel')) {
	$tpl->assign('CARROUSEL_CATEGORIE', Carrousel::display('categorie', true));
}
$tpl->assign('MODULES_ABOVE_MIDDLE', get_modules('above_middle', true, null, vn($_GET['catid'])));
if ($GLOBALS['page_columns_count'] > 1) {
	$modules_left = '';
	if((defined('IN_CATALOGUE_ANNONCE') || defined('IN_CATALOGUE') || defined('IN_CATALOGUE_ANNONCE_DETAILS')) && check_if_module_active('annonces')) {
		$modules_left .= get_modules('left_annonce', true, null, vn($_GET['catid'])); 
	}
	$modules_left .= get_modules('left', true, null, vn($_GET['catid']));
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
$tpl->assign('notification_output', implode('', $GLOBALS['notification_output_array']));
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
			$appstore_image = get_url('/images/download_appstore_'.$_SESSION['session_langue'].'.png');
		}else {
			$appstore_image = get_url('/images/download_appstore_en.png');
		}
		$tpl->assign('appstore_image', $appstore_image);
	}
}
$tpl->assign('est_identifie', est_identifie());
$tpl->assign('show_open_account', est_identifie() && !empty($GLOBALS['site_parameters']['show_open_account']));
if(!empty($_SESSION['session_utilisateur']['email'])) {
	$tpl->assign('session_utilisateur_email', StringMb::str_shorten($_SESSION['session_utilisateur']['email'], vb($GLOBALS['site_parameters']['login_in_header_length'], 20)));
}
if(!empty($_SESSION['session_utilisateur']['logo'])) {
	$tpl->assign('user_logo_src', thumbs($_SESSION['session_utilisateur']['logo'], 40, 28, 'fit', null, null, true, true));
}
if(!empty($GLOBALS['site_parameters']['header_show_user_account_completion']) && est_identifie()) {
	$tpl->assign('user_account_completion', user_account_completion($_SESSION['session_utilisateur']));
}
$tpl->assign('account_dropdown', affiche_compte(true, 'popup'));
$tpl->assign('STR_LOGIN', $GLOBALS['STR_LOGIN']);
$tpl->assign('account_register_url', get_account_register_url(false, false));
$tpl->assign('STR_OPEN_ACCOUNT', $GLOBALS['STR_OPEN_ACCOUNT']);

if (!empty($GLOBALS['allow_fineuploader_on_page']) && vb($GLOBALS['site_parameters']['used_uploader']) == 'fineuploader') {
	// Il faut explicitement autoriser fineuploader sur une page en front-office
	init_fineuploader_interface();
}

// *** LAISSER A LA FIN ***
$tpl->assign('product_category_introduction_text_display_disable', !empty($GLOBALS['site_parameters']['product_category_introduction_text_display_disable']));
$tpl->assign('header_custom_html', vb($GLOBALS['site_parameters']['header_custom_html']));
$tpl->assign('header_custom_baseline_html', vb($GLOBALS['site_parameters']['header_custom_baseline_html']));

if ($GLOBALS['page_columns_count'] == 3) {
	$GLOBALS['modules_right'] = '';
	if(check_if_module_active('annonces')) {
		$GLOBALS['modules_right'] .= get_modules('right_annonce', true, null, vn($_GET['catid'])); 
	}
	$GLOBALS['modules_right'] .= get_modules('right', true, null, vn($_GET['catid']));
}
$tpl->assign('MODULES_RIGHT', vb($GLOBALS['modules_right']));

$hook_result = call_module_hook('header_template_data', array(), 'array');
foreach($hook_result as $this_key => $this_value) {
	$tpl->assign($this_key, $this_value);
}
// A exécuter en dernier dans ce fichier car prend tous les javascripts
// category_introduction_text est passé par référence à getHTMLHead pour être rempli
$tpl->assign('HTML_HEAD', getHTMLHead(vb($GLOBALS['page_name']), $GLOBALS['category_introduction_text']));
$tpl->assign('category_introduction_text', $GLOBALS['category_introduction_text']);

echo $tpl->fetch();


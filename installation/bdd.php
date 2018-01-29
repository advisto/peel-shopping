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
// $Id: bdd.php 55858 2018-01-22 17:04:25Z sdelaporte $
define('IN_INSTALLATION', 2);
include("../configuration.inc.php");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_INSTALL_STEP2_TITLE'];
unset($_SESSION['session_install_finished']);

if (!isset($_SESSION['session_admin_ssl']) && (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')) {
	$_SESSION['session_admin_ssl'] = "1";
}
$confirm_message = '';
if(vb($_GET['err']) == '1') {
	$confirm_message .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_ERROR_CONNEXION']))->fetch();
}
if(vb($_GET['err']) == 'empty') {
	$confirm_message .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_FILL_IN_ALL']))->fetch();
}
@include ($GLOBALS['dirroot'] . '/lib/lang/database_langues_'.$_SESSION['session_langue'].'.php');
foreach($GLOBALS['available_languages'] as $this_lang){
	if(!empty($peel_langues["nom"][$this_lang])) {
		$GLOBALS['select_languages'][$this_lang] = $peel_langues["nom"][$this_lang];
	} elseif(!empty($GLOBALS['lang_names'][$this_lang])) {
		$GLOBALS['select_languages'][$this_lang] = $GLOBALS['lang_names'][$this_lang];
	} else {
		$GLOBALS['select_languages'][$this_lang] = $this_lang;
	}
	if(!in_array($this_lang, $GLOBALS['site_parameters']['complete_lang_files'])) {
		$GLOBALS['select_languages'][$this_lang] .= ' &lt; 100% => '.StringMb::strtolower($GLOBALS["STR_WITH"]).' '.$peel_langues["nom"]['en'];
	}
}
asort($GLOBALS['select_languages']);

$tpl = $GLOBALS['tplEngine']->createTemplate('installation_bdd.tpl');
$tpl->assign('step_title', $GLOBALS['DOC_TITLE']);
$tpl->assign('confirm_message', $confirm_message);
$tpl->assign('url_installation', str_replace('http://', 'https://', $detected_wwwroot . '/installation/'));
$tpl->assign('ssl_admin_explain', (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off'));
$tpl->assign('admin_force_ssl_selected', !empty($_SESSION['session_admin_ssl']));
$tpl->assign('wwwroot_value', (!empty($_SESSION['session_install_wwwroot'])?$_SESSION['session_install_wwwroot']:$GLOBALS['detected_wwwroot']));
$tpl->assign('site_name_value', (!empty($_SESSION['session_install_site_name'])?$_SESSION['session_install_site_name']:''));
$tpl->assign('email_webmaster_value', (!empty($_SESSION['session_install_email_webmaster'])?$_SESSION['session_install_email_webmaster']:''));
$tpl->assign('serveur_value', (!empty($_SESSION['session_install_serveur'])?$_SESSION['session_install_serveur']:'localhost'));
$tpl->assign('utilisateur_value', (!empty($_SESSION['session_install_utilisateur'])?$_SESSION['session_install_utilisateur']:''));
$tpl->assign('motdepasse_value', (!empty($_SESSION['session_install_motdepasse'])?$_SESSION['session_install_motdepasse']:''));
$tpl->assign('website_type_value', (!empty($_SESSION['session_install_website_type'])?$_SESSION['session_install_website_type']:''));
$tpl->assign('fill_db', (!empty($_SESSION['session_install_fill_db'])?$_SESSION['session_install_fill_db']:''));
$tpl->assign('ad_site_disable', !file_exists($GLOBALS['dirroot'].'/modules/annonces/fonctions.php'));
$tpl->assign('select_languages', $GLOBALS['select_languages']);
$tpl->assign('install_langs_value', (!empty($_SESSION['session_install_langs'])?$_SESSION['session_install_langs']:$_SESSION['session_langue']));
$tpl->assign('STR_ADMIN_SITES_GENERAL_PARAMETERS', $GLOBALS['STR_ADMIN_SITES_GENERAL_PARAMETERS']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_INTRO_1', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_INTRO_1']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_INTRO_2', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_INTRO_2']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_INTRO_3', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_INTRO_3']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_INTRO_4', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_INTRO_4']);
$tpl->assign('STR_ADMIN_INSTALL_ERROR_CONNEXION', $GLOBALS['STR_ADMIN_INSTALL_ERROR_CONNEXION']);
$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL']);
$tpl->assign('STR_ADMIN_SITES_SITE_NAME', $GLOBALS['STR_ADMIN_SITES_SITE_NAME']);
$tpl->assign('STR_ERR_FILL_IN_ALL', $GLOBALS['STR_ERR_FILL_IN_ALL']);
$tpl->assign('STR_ADMIN_INSTALL_EXPLAIN_SSL', $GLOBALS['STR_ADMIN_INSTALL_EXPLAIN_SSL']);
$tpl->assign('STR_ADMIN_INSTALL_URL_STORE', $GLOBALS['STR_ADMIN_INSTALL_URL_STORE']);
$tpl->assign('STR_ADMIN_INSTALL_SSL_ADMIN', $GLOBALS['STR_ADMIN_INSTALL_SSL_ADMIN']);
$tpl->assign('STR_ADMIN_INSTALL_SSL_ADMIN_NO', $GLOBALS['STR_ADMIN_INSTALL_SSL_ADMIN_NO']);
$tpl->assign('STR_ADMIN_INSTALL_SSL_ADMIN_YES', $GLOBALS['STR_ADMIN_INSTALL_SSL_ADMIN_YES']);
$tpl->assign('STR_ADMIN_INSTALL_SSL_ADMIN_EXPLAIN', $GLOBALS['STR_ADMIN_INSTALL_SSL_ADMIN_EXPLAIN']);
$tpl->assign('STR_ADMIN_INSTALL_LANGUAGE_CHOOSE', $GLOBALS['STR_ADMIN_INSTALL_LANGUAGE_CHOOSE']);
$tpl->assign('STR_ADMIN_INSTALL_FILL_DB', $GLOBALS['STR_ADMIN_INSTALL_FILL_DB']);
$tpl->assign('STR_ADMIN_INSTALL_FILL_DB_EXPLANATION', $GLOBALS['STR_ADMIN_INSTALL_FILL_DB_EXPLANATION']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_SERVER', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_SERVER']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_SERVER_EXPLAIN', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_SERVER_EXPLAIN']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_USERNAME', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_USERNAME']);
$tpl->assign('STR_PASSWORD', $GLOBALS['STR_PASSWORD']);
$tpl->assign('STR_CONTINUE', $GLOBALS['STR_CONTINUE']);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_INSTALL_CHOOSE_WEBSITE_TYPE', $GLOBALS['STR_ADMIN_INSTALL_CHOOSE_WEBSITE_TYPE']);
$tpl->assign('STR_ADMIN_INSTALL_WEBSITE_SHOP', $GLOBALS['STR_ADMIN_INSTALL_WEBSITE_SHOP']);
$tpl->assign('STR_ADMIN_INSTALL_WEBSITE_SHOWCASE', $GLOBALS['STR_ADMIN_INSTALL_WEBSITE_SHOWCASE']);
$tpl->assign('STR_ADMIN_INSTALL_WEBSITE_AD', $GLOBALS['STR_ADMIN_INSTALL_WEBSITE_AD']);

$output = $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

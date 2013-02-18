<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: index.php 35064 2013-02-08 14:16:40Z gboussin $
define('IN_INSTALLATION', 1);
include("../configuration.inc.php");


$DOC_TITLE = $GLOBALS['STR_ADMIN_INSTALL_STEP1_TITLE'] . ' ' . PEEL_VERSION;
// Pour éviter qu'un utilisateur ne lance l'installation avec les droits de demo par exemple
unset($_SESSION['session_utilisateur']);
unset($_SESSION['session_commande']);
unset($_SESSION['session_droit']);
$_SESSION['session_install_serveur'] = "";
$_SESSION['session_install_utilisateur'] = "";
$_SESSION['session_install_motdepasse'] = "";
$_SESSION['session_install_choixbase'] = "";
$_SESSION['session_install_langs'] = "";

$tpl = $GLOBALS['tplEngine']->createTemplate('installation_index.tpl');
$tpl->assign('php_version_info', (version_compare(PHP_VERSION, '5.0.0', '>=')?'<span class="global_success">' . PHP_VERSION . '</span>':'<div class="global_error">' . PHP_VERSION . ' - ' . $GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_PHP5"] . '</div>'));
$tpl->assign('mbstring_info', (function_exists('mb_internal_encoding')?'<span class="global_success">'.$GLOBALS['STR_YES'].'</span>':'<div class="global_error">'.$GLOBALS['STR_NO'].' - ' . $GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_MBSTRING"] . '</div>'));
$tpl->assign('utf8_info', ((function_exists('mb_list_encodings') && in_array('UTF-8', mb_list_encodings())) || (function_exists('mb_internal_encoding') && strtolower(mb_internal_encoding()) == 'utf-8')?'<span class="global_success">'.$GLOBALS['STR_YES'].'</span>':'<div class="global_error">'.$GLOBALS['STR_NO'].' - ' . $GLOBALS["STR_ADMIN_INSTALL_UTF8"] . '</div>'));
$tpl->assign('allow_url_fopen_info', (function_exists('ini_get') && (@ini_get('allow_url_fopen') == 1 || strtolower(@ini_get('allow_url_fopen')) == 'on')?'<span class="global_success">'.$GLOBALS['STR_YES'].'</span>':'<div class="global_error">'.$GLOBALS['STR_NO'].' - ' . $GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_URL_FOPEN"] . '</div>'));
$tpl->assign('step_title', $DOC_TITLE);
$tpl->assign('STR_CONTINUE', $GLOBALS['STR_CONTINUE']);
$tpl->assign('STR_ADMIN_INSTALL_WELCOME', $GLOBALS['STR_ADMIN_INSTALL_WELCOME']);
$tpl->assign('STR_ADMIN_INSTALL_WELCOME_INTRO', $GLOBALS['STR_ADMIN_INSTALL_WELCOME_INTRO']);
$tpl->assign('STR_ADMIN_INSTALL_VERIFY_SERVER_CONFIGURATION', $GLOBALS['STR_ADMIN_INSTALL_VERIFY_SERVER_CONFIGURATION']);
$tpl->assign('STR_ADMIN_INSTALL_PHP_VERSION', $GLOBALS['STR_ADMIN_INSTALL_PHP_VERSION']);
$tpl->assign('STR_ADMIN_INSTALL_MBSTRING', $GLOBALS['STR_ADMIN_INSTALL_MBSTRING']);
$tpl->assign('STR_ADMIN_INSTALL_UTF8', $GLOBALS['STR_ADMIN_INSTALL_UTF8']);
$tpl->assign('STR_ADMIN_INSTALL_ALLOW_URL_FOPEN', $GLOBALS['STR_ADMIN_INSTALL_ALLOW_URL_FOPEN']);
$output = $tpl->fetch();

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");
echo $output;
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

?>
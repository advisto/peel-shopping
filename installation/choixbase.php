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
// $Id: choixbase.php 35460 2013-02-22 12:53:50Z gboussin $
define('IN_INSTALLATION', 3);
include("../configuration.inc.php");


$DOC_TITLE = $GLOBALS['STR_ADMIN_INSTALL_STEP3_TITLE'];
$error_message = '';

if (isset($_POST['admin_force_ssl'])) $_SESSION['session_install_admin_force_ssl'] = $_POST['admin_force_ssl'];
if (isset($_POST['serveur'])) $_SESSION['session_install_serveur'] = $_POST['serveur'];
if (isset($_POST['utilisateur'])) $_SESSION['session_install_utilisateur'] = $_POST['utilisateur'];
if (isset($_POST['motdepasse'])) $_SESSION['session_install_motdepasse'] = $_POST['motdepasse'];
if (isset($_POST['langs'])) $_SESSION['session_install_langs'] = $_POST['langs'];

if ((isset($_POST['langs']) && empty($_POST['langs'])) || (isset($_POST['wwwroot']) && empty($_POST['wwwroot'])) || (isset($_POST['serveur']) && empty($_POST['serveur'])) || (isset($_POST['utilisateur']) && empty($_POST['utilisateur']))) {
	redirect_and_die("bdd.php?err=empty");
}
if (!empty($_POST['wwwroot'])) {
	while (!empty($_POST['wwwroot']) && String::substr($_POST['wwwroot'], - 1) == '/') {
		// Suppression du / à la fin le cas si nécessaire
		$_POST['wwwroot'] = String::substr($_POST['wwwroot'], 0, strlen($_POST['wwwroot']) - 1);
	}
	$_SESSION['session_install_wwwroot'] = $_POST['wwwroot'];
}

if (@mysql_connect($_SESSION['session_install_serveur'], $_SESSION['session_install_utilisateur'], $_SESSION['session_install_motdepasse']) === false) {
	redirect_and_die("bdd.php?err=1");
}
$i = 0;
$listbdd = @mysql_list_dbs();
$available_databases = array();
if ($listbdd) {
	while ($row = mysql_fetch_object($listbdd)) {
		if ($row->Database == 'information_schema') {
			continue;
		}
		$available_databases[] = $row->Database;
		$i++;
	}
}
if (isset($_GET['err']) && $_GET['err']) {
	$error_message .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_DATABASE_NO_ACCESS']))->fetch();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('installation_choixbase.tpl');
$tpl->assign('step_title', $DOC_TITLE);
$tpl->assign('available_databases', $available_databases);
$tpl->assign('error_message', $error_message);
$tpl->assign('selected_database', vb($_SESSION['session_install_choixbase']));
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_SELECT', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_SELECT']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC']);
$tpl->assign('STR_CONTINUE', $GLOBALS['STR_CONTINUE']);
$tpl->assign('step_title', $DOC_TITLE);
$output = $tpl->fetch();

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");
echo $output;
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

?>
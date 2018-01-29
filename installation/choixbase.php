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
// $Id: choixbase.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_INSTALLATION', 3);
include("../configuration.inc.php");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_INSTALL_STEP3_TITLE'];
$error_message = '';
unset($_SESSION['session_install_finished']);

if (isset($_POST['admin_force_ssl'])) $_SESSION['session_install_admin_force_ssl'] = $_POST['admin_force_ssl'];
if (isset($_POST['serveur'])) $_SESSION['session_install_serveur'] = $_POST['serveur'];
if (isset($_POST['utilisateur'])) $_SESSION['session_install_utilisateur'] = $_POST['utilisateur'];
if (isset($_POST['motdepasse'])) $_SESSION['session_install_motdepasse'] = $_POST['motdepasse'];
if (isset($_POST['langs'])) $_SESSION['session_install_langs'] = $_POST['langs'];
if (isset($_POST['site_name'])) $_SESSION['session_install_site_name'] = $_POST['site_name'];
if (isset($_POST['email_webmaster'])) $_SESSION['session_install_email_webmaster'] = $_POST['email_webmaster'];
if (isset($_POST['website_type'])) $_SESSION['session_install_website_type'] = $_POST['website_type'];
if (isset($_POST['fill_db'])) $_SESSION['session_install_fill_db'] = $_POST['fill_db'];

if ((isset($_POST['langs']) && empty($_POST['langs'])) || (isset($_POST['site_name']) && empty($_POST['site_name'])) || (isset($_POST['email_webmaster']) && empty($_POST['email_webmaster'])) || (isset($_POST['serveur']) && empty($_POST['serveur'])) || (isset($_POST['utilisateur']) && empty($_POST['utilisateur']))) {
	redirect_and_die("bdd.php?err=empty");
}
// On accepte wwwroot vide : dans ce cas, c'est une configuration pour multisite.
// Elle marche aussi pour les sites seuls, mais cela permet moins de vérifications par rapport à la détection automatique de chemin
if (isset($_POST['wwwroot'])) {
	$_POST['wwwroot'] = trim($_POST['wwwroot']);
	while (!empty($_POST['wwwroot']) && StringMb::substr($_POST['wwwroot'], - 1) == '/') {
		// Suppression du / à la fin le cas si nécessaire
		$_POST['wwwroot'] = StringMb::substr($_POST['wwwroot'], 0, strlen($_POST['wwwroot']) - 1);
	}
	$_SESSION['session_install_wwwroot'] = $_POST['wwwroot'];
}

if (empty($_SESSION['session_install_serveur']) || empty($_SESSION['session_install_utilisateur'])) {
	redirect_and_die("bdd.php?err=1");
}
$GLOBALS['serveur_mysql'] = $_SESSION['session_install_serveur'];
$GLOBALS['utilisateur_mysql'] = $_SESSION['session_install_utilisateur'];
$GLOBALS['mot_de_passe_mysql'] = $_SESSION['session_install_motdepasse'];
db_connect($GLOBALS['database_object'], false);
if (!$GLOBALS['database_object']) {
	redirect_and_die("bdd.php?err=1");
}

$available_databases = list_dbs();
if (isset($_GET['err']) && $_GET['err']) {
	$error_message .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_DATABASE_NO_ACCESS']))->fetch();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('installation_choixbase.tpl');
$tpl->assign('step_title', $GLOBALS['DOC_TITLE']);
$tpl->assign('available_databases', $available_databases);
$tpl->assign('error_message', $error_message);
$tpl->assign('selected_database', vb($_SESSION['session_install_choixbase']));
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_SELECT', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_SELECT']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL']);
$tpl->assign('STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC', $GLOBALS['STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC']);
$tpl->assign('STR_CONTINUE', $GLOBALS['STR_CONTINUE']);
$tpl->assign('step_title', $GLOBALS['DOC_TITLE']);
$output = $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

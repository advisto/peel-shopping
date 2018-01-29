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
// $Id: verifdroits.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_INSTALLATION', 4);
include("../configuration.inc.php");


$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_INSTALL_STEP4_TITLE'];

unset($_SESSION['session_peel_sql']);
unset($_SESSION['session_peel_sql_premium']);
unset($_SESSION['session_peel_sql_website_type']);
unset($_SESSION['session_install_finished']);

if (empty($_POST['choixbase'])) {
	redirect_and_die("choixbase.php?err=1");
}

$_SESSION['session_install_choixbase'] = $_POST['choixbase'];

if (!select_db($_SESSION['session_install_choixbase'], $GLOBALS['database_object'], true)) {
	redirect_and_die("choixbase.php?err=1");
}
$error = 0;
$directories_checkup_messages = '';
$liste = array($GLOBALS['dirroot'] . "/lib/setup", $GLOBALS['dirroot'] . "/upload", $GLOBALS['dirroot'] . "/upload/thumbs", $GLOBALS['dirroot'] . "/download", $GLOBALS['dirroot'] . "/comparateur", $GLOBALS['dirroot'] . "/cache", $GLOBALS['dirroot'] . "/lib/templateEngines/smarty/compile", $GLOBALS['dirroot'] . "/modules/captcha/security_codes");
for($i = 0; $i < count($liste); $i++) {
	if (!is_writable($liste[$i])) {
		$directories_checkup_messages .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_INSTALL_DIRECTORY_NOK'], $liste[$i])))->fetch();
		$error = 1;
	} else {
		$directories_checkup_messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_INSTALL_DIRECTORY_OK'], $liste[$i])))->fetch();
	}
}

$files_checkup_messages = '';
$liste = array("../lib/setup/info.inc.php", "../sitemap.xml", "../urllist.txt");
for($i = 0; $i < count($liste); $i++) {
	if (!is_writable($liste[$i])) {
		$files_checkup_messages .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_INSTALL_FILE_NOK'], $liste[$i])))->fetch();
		$error = 1;
	} else {
		$files_checkup_messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_INSTALL_FILE_OK'], $liste[$i])))->fetch();
	}
}
// Check tables
$sql = "SHOW TABLES FROM `" . word_real_escape_string($_SESSION['session_install_choixbase']) . "` LIKE 'peel_%'";
$result = query($sql);
$tables_checkup_messages = '';
if (num_rows($result) > 0) {
	while ($row = fetch_row($result)) {
		$tables_checkup_messages .= $row[0] . "<br />";
	}
	$tables_checkup_messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_EXPLAIN_RENAME_TABLES']))->fetch();
	$error = 1;
} else {
	$tables_checkup_messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_INSTALL_DATABASE_OK_PREFIX'], $_SESSION['session_install_choixbase'])))->fetch();
}
$tpl = $GLOBALS['tplEngine']->createTemplate('installation_verifdroits.tpl');
$tpl->assign('step_title', $GLOBALS['DOC_TITLE']);
$tpl->assign('configuration_url', 'configuration.php');
$tpl->assign('choixbase_value', $_SESSION['session_install_choixbase']);
$tpl->assign('directories_checkup_messages', $directories_checkup_messages);
$tpl->assign('files_checkup_messages', $files_checkup_messages);
$tpl->assign('tables_checkup_messages', $tables_checkup_messages);
$tpl->assign('error', $error);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_REFRESH', $GLOBALS['STR_REFRESH']);
$tpl->assign('STR_CONTINUE', $GLOBALS['STR_CONTINUE']);
$tpl->assign('STR_ADMIN_INSTALL_CHECK_ACCESS_RIGHTS', $GLOBALS['STR_ADMIN_INSTALL_CHECK_ACCESS_RIGHTS']);
$tpl->assign('STR_ADMIN_INSTALL_RIGHTS_OK', $GLOBALS['STR_ADMIN_INSTALL_RIGHTS_OK']);
$tpl->assign('STR_ADMIN_INSTALL_RIGHTS_NOK', $GLOBALS['STR_ADMIN_INSTALL_RIGHTS_NOK']);
$tpl->assign('STR_ADMIN_INSTALL_EXISTING_TABLES', $GLOBALS['STR_ADMIN_INSTALL_EXISTING_TABLES']);
$tpl->assign('STR_ADMIN_INSTALL_EXPLAIN_RENAME_TABLES', $GLOBALS['STR_ADMIN_INSTALL_EXPLAIN_RENAME_TABLES']);
$tpl->assign('STR_ADMIN_INSTALL_STEP_5_LINK_EXPLAIN', $GLOBALS['STR_ADMIN_INSTALL_STEP_5_LINK_EXPLAIN']);
$tpl->assign('STR_ADMIN_INSTALL_CONTINUE_WITH_ERRORS_BUTTON', $GLOBALS['STR_ADMIN_INSTALL_CONTINUE_WITH_ERRORS_BUTTON']);
$output = $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


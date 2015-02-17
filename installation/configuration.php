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
// $Id: configuration.php 44077 2015-02-17 10:20:38Z sdelaporte $
define('IN_INSTALLATION', 5);
include("../configuration.inc.php");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_INSTALL_STEP5_TITLE'];
$frm_error = $_GET;
$output = '';
$messages = '';
unset($_SESSION['session_install_finished']);
unset($_SESSION['session_sql_output']);
unset($_SESSION['session_sql_filepos']);

if (!isset($_SESSION['session_peel_sql'])) {
	$error_msg = execute_sql("peel.sql", null, true);

	$site_data['site_id'] = 1;
	$site_data['enable_jquery'] = 1;
	$messages .= create_or_update_site($site_data, false, 'insere', vb($_SESSION['session_install_langs']));
	$_SESSION['session_peel_sql'] = true;
	$messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_SQL_FILE_EXECUTED']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': installation/peel.sql'))->fetch();
	if(!empty($error_msg)) {
		$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
	}
}
if (!isset($_SESSION['session_peel_sql_premium']) && file_exists("peel_premium.sql")) {
	$error_msg = execute_sql("peel_premium.sql", null, true);
	$_SESSION['session_peel_sql_premium'] = true;
	$messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_SQL_FILE_EXECUTED']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': installation/peel_premium.sql'))->fetch();
	if(!empty($error_msg)) {
		$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
	}
}
// Exécution de l'installation des modules
$modules_dir = $GLOBALS['dirroot'] . "/modules";
if ($handle = opendir($modules_dir)) {
	while ($file = readdir($handle)) {
		if ($file != "." && $file != ".." && is_dir($modules_dir . '/' . $file)) {
			if (file_exists($modules_dir . '/' . $file . '/peel_' . $file . '.sql')) {
				// Exécution du SQL d'installation d'un module
				$error_msg = execute_sql($modules_dir . '/' . $file . '/peel_' . $file . '.sql', null, true);
				$messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_SQL_FILE_EXECUTED']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': ' . $modules_dir . '/' . $file . '/peel_' . $file . '.sql'))->fetch();
				if(!empty($error_msg)) {
					$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
				}
			}
		}
	}
}

if (file_exists("info.inc.src.php")) {
	$fic = file_get_contents("info.inc.src.php");
} else {
	$messages .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_FILE_MISSING']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': installation/info.inc.src.php'))->fetch();
}
$form_messages = '';
if (!empty($frm_error['error_mail'])) {
	$form_messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_EMAIL']))->fetch();
}
if (!empty($frm_error['error_pseudo'])) {
	$form_messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_PSEUDO']))->fetch();
}
if (!empty($frm_error['error_motdepasse'])) {
	$form_messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_PASSWORD']))->fetch();
}

/*
// Gestion de la récupération des anciennes données de configuration
// A activer si vous voulez utiliser $old_config_file_content dans l'affichage utilisateur
if (file_exists("../lib/setup/info.inc.php")) {
	$old_config_file_content=file_get_contents("../lib/setup/info.inc.src");
}
*/

$fic = preg_replace("/votre_serveur_mysql/", $_SESSION['session_install_serveur'], $fic);
$fic = preg_replace("/votre_utilisateur_mysql/", $_SESSION['session_install_utilisateur'], $fic);
$fic = preg_replace("/votre_motdepasse_mysql/", $_SESSION['session_install_motdepasse'], $fic);
$fic = preg_replace("/bdd_mysql/", $_SESSION['session_install_choixbase'], $fic);

$fp = String::fopen_utf8($GLOBALS['dirroot'] . "/lib/setup/info.inc.php", "wb");
if($fp !== false) {
	fputs($fp, $fic);
	fclose($fp);
} else {
	$form_messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_INSTALL_FILE_NOK'], '/lib/setup/info.inc.php')))->fetch();
}
$tpl = $GLOBALS['tplEngine']->createTemplate('installation_configuration.tpl');
$tpl->assign('step_title', $GLOBALS['DOC_TITLE']);
$tpl->assign('next_step_url', 'fin.php');
$tpl->assign('messages', $messages);
$tpl->assign('form_messages', $form_messages);
$tpl->assign('STR_MANDATORY', $GLOBALS['STR_MANDATORY']);
$tpl->assign('STR_PASSWORD', $GLOBALS['STR_PASSWORD']);
$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
$tpl->assign('STR_CONTINUE', $GLOBALS['STR_CONTINUE']);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_INSTALL_ADMIN_EMAIL', $GLOBALS['STR_ADMIN_INSTALL_ADMIN_EMAIL']);

$output .= $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

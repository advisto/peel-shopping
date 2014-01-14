<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fin.php 39495 2014-01-14 11:08:09Z sdelaporte $
define('IN_INSTALLATION', 6);
include("../configuration.inc.php");

$DOC_TITLE = $GLOBALS['STR_ADMIN_INSTALL_STEP6_TITLE'];

if(empty($_SESSION['session_install_finished'])) {
	if (empty($_POST['email'])) {
		$error[] = 'error_mail=1';
	}
	if (empty($_POST['motdepasse'])) {
		$error[] = 'error_motdepasse=1';
	}
	if (empty($_POST['pseudo'])) {
		$error[] = 'error_pseudo=1';
	}
	if (!empty($error)) {
		redirect_and_die("configuration.php?" . implode('&', $error));
	}

	unset($_SESSION['session_peel_sql']);
	unset($_SESSION['session_peel_sql_premium']);

	$new_user_infos = array('priv' => 'admin',
		'email' => $_POST['email'],
		'pseudo' => $_POST['pseudo'],
		'prenom' => $_POST['prenom'],
		'nom_famille' => $_POST['nom'],
		'societe' => '',
		'telephone' => $_POST['telephone'],
		'adresse' => $_POST['adresse'],
		'code_postal' => $_POST['code_postal'],
		'ville' => $_POST['ville'],
		'pays' => '1',
		'mot_passe' => $_POST['motdepasse']);
	$id_utilisateur = insere_utilisateur($new_user_infos, false, true, false);

	set_configuration_variable(array('technical_code' => 'email_commande', 'string' => $_POST['email'], 'type' => 'string'), true);
	set_configuration_variable(array('technical_code' => 'email_webmaster', 'string' => $_POST['email'], 'type' => 'string'), true);
	set_configuration_variable(array('technical_code' => 'email_client', 'string' => $_POST['email'], 'type' => 'string'), true);
	set_configuration_variable(array('technical_code' => 'admin_force_ssl', 'string' => vn($_SESSION['session_install_admin_force_ssl']), 'type' => 'string'), true);
	set_configuration_variable(array('technical_code' => 'display_errors_for_ips', 'string' => $_SERVER['REMOTE_ADDR'], 'type' => 'string'), true);
}
$_SESSION['session_install_wwwroot'] = "";
$_SESSION['session_install_admin_force_ssl'] = "";
$_SESSION['session_install_serveur'] = "";
$_SESSION['session_install_utilisateur'] = "";
$_SESSION['session_install_motdepasse'] = "";
$_SESSION['session_install_choixbase'] = "";
$_SESSION['session_install_langs'] = "";
$_SESSION['session_install_finished'] = true;

$tpl = $GLOBALS['tplEngine']->createTemplate('installation_fin.tpl');
$tpl->assign('step_title', $DOC_TITLE);
$tpl->assign('email', vb($_POST['email']));
$tpl->assign('motdepasse', vb($_POST['motdepasse']));
$tpl->assign('pseudo', vb($_POST['pseudo']));
$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
$tpl->assign('STR_PASSWORD', $GLOBALS['STR_PASSWORD']);
$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_INSTALL_NOW_INSTALLED', $GLOBALS['STR_ADMIN_INSTALL_NOW_INSTALLED']);
$tpl->assign('STR_ADMIN_INSTALL_YOU_CAN_LOGIN_ADMIN', $GLOBALS['STR_ADMIN_INSTALL_YOU_CAN_LOGIN_ADMIN']);
$tpl->assign('STR_ADMIN_INSTALL_ADMIN_LINK_INFOS', $GLOBALS['STR_ADMIN_INSTALL_ADMIN_LINK_INFOS']);
$tpl->assign('STR_ADMIN_INSTALL_FINISHED_INFOS', $GLOBALS['STR_ADMIN_INSTALL_FINISHED_INFOS']);
$tpl->assign('STR_ADMIN_INSTALL_FINISHED_INFOS_DELETE_INSTALL', $GLOBALS['STR_ADMIN_INSTALL_FINISHED_INFOS_DELETE_INSTALL']);
$tpl->assign('STR_ADMIN_INSTALL_FINISHED_INFOS_RENAME_ADMIN', $GLOBALS['STR_ADMIN_INSTALL_FINISHED_INFOS_RENAME_ADMIN']);
$tpl->assign('STR_ADMIN_INSTALL_FINISHED_INFOS_PHP_ERRORS_DISPLAY', $GLOBALS['STR_ADMIN_INSTALL_FINISHED_INFOS_PHP_ERRORS_DISPLAY']);
$tpl->assign('STR_ADMIN_INSTALL_FINISHED_INFOS_UTF8_WARNING', $GLOBALS['STR_ADMIN_INSTALL_FINISHED_INFOS_UTF8_WARNING']);
$tpl->assign('STR_ADMIN_INSTALL_FINISH_BUTTON', $GLOBALS['STR_ADMIN_INSTALL_FINISH_BUTTON']);
$output = $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

?>
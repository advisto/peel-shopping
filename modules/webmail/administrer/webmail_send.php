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
// $Id: webmail_send.php 55792 2018-01-17 11:49:45Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");

if (!check_if_module_active('webmail')) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die(get_url('/'));
}

necessite_identification();
necessite_priv("admin_users_contact_form,admin_users,admin_finance");

$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_ADMIN_UTILISATEURS_SEND_EMAIL_TITLE"];

$form_error_object = new FormError();

if (!empty($_REQUEST['id_webmail'])) {
	$_POST['id_webmail'] = $_REQUEST['id_webmail'];
}
if (!empty($_REQUEST['id_utilisateur'])) {
	$_POST['id_utilisateur'] = $_REQUEST['id_utilisateur'];
}

if (!empty($_REQUEST['user_ids'])) {
	$_POST['user_ids'] = $_REQUEST['user_ids'];
}

if (!isset($_GET['email_all_hash'])) {
	$_GET['email_all_hash'] = '';
} else {
	$_GET['email_all_hash'] = $_GET['email_all_hash'];
}

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
switch (vb($_REQUEST['mode'])) {
	case 'send_mail':
		// Inutile de vérifier ce champ pour un envoi multiple
		if (empty($_SESSION['request_from_send_email_all'][$_GET['email_all_hash']])) {
			$form_error_object->valide_form($_POST, array('destination_mail' => $GLOBALS['STR_ERR_EMAIL']));
		}
		if (!verify_token($_SERVER['PHP_SELF'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			echo send_mail_admin($_POST);
			affiche_form_send_mail($_POST, false);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_form_send_mail($_POST, false, $form_error_object);
		}
		break;
	default:
		affiche_form_send_mail($_REQUEST, false);
		break;
}
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


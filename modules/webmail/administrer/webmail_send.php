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
// $Id: webmail_send.php 35067 2013-02-08 14:21:55Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users");

include($GLOBALS['dirroot'] . "/modules/webmail/administrer/fonctions.php");

$DOC_TITLE = $GLOBALS["STR_ADMIN_UTILISATEURS_SEND_EMAIL_TITLE"];

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

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");
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
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

?>
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
// $Id: oubli_mot_passe.php 39495 2014-01-14 11:08:09Z sdelaporte $
define('IN_GET_PASSWORD', true);

include("../configuration.inc.php");
include("../lib/fonctions/display_user_forms.php");

$page_name = 'oubli_mot_passe';
// Le formulaire a été soumis, vérifie si l'identification est ok
$frm = $_POST;
$form_error_object = new FormError();
$output = '';
$mode = 'filing_email';

if (!empty($_POST['token'])) {
	if (!verify_token('oubli_mot_passe', 120, false)) {
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	// vérification de la présence de l'email dans le formulaire de demande de renouvellement.
	if (empty($_POST['email'])) {
		$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_VIDE']);
	}
}
// cas de réception de l'utilisateur via le lien fourni dans l'email de demande de renouvellement de mot de passe. Les informations sont envoyé en GET.
if (!empty($_GET['hash']) && !empty($_GET['time']) && !empty($_GET['email']) && empty($frm)) {
	$qid = query("SELECT mot_passe, id_utilisateur
			FROM peel_utilisateurs
			WHERE email = '" . nohtml_real_escape_string($_GET["email"]) . "'");
	$utilisateur = fetch_assoc($qid);
	$new_hash = sha256($_GET["email"] . $_GET['time'] . $utilisateur['id_utilisateur'] . $utilisateur['mot_passe']);
	if (($_GET['hash'] == $new_hash)) {
		if ($_GET['time'] + (3600 * 24) > time()) {
			$mode = 'renew_password';
		} else {
			$noticemsg = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_TIME']))->fetch();
		}
	} else {
		$noticemsg = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_HASH']))->fetch();
	}
} elseif (!empty($_POST['email'])) {
	if (a_priv('demo')) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => array('message' => $GLOBALS['STR_DEMO_RIGHTS_LIMITED'])))->fetch();
		die();
	}
	$form_error_object->valide_form($frm, array('email' => $GLOBALS['STR_ERR_EMAIL']));

	if (!$form_error_object->has_error('email')) {
		$frm['email'] = trim($frm['email']);
		if (!EmailOK($frm['email'])) {
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
		} elseif ((num_rows(query("SELECT 1
				FROM peel_utilisateurs
				WHERE email = '" . nohtml_real_escape_string($frm["email"]) . "' AND priv!='newsletter' ")) == 0)) {
			$form_error_object->add('email', $GLOBALS['STR_ERR_NOEMAIL']);
		}
	}
	if (!$form_error_object->count()) {
		initialise_mot_passe($_POST["email"]);
		$noticemsg = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_PASSWORD_EMAIL']))->fetch();
	}
} elseif ((!empty($_POST['password_once']) && !empty($_POST['password_twice'])) && !empty($_GET['email'])) {
	$password_once = trim($_POST['password_once']);
	$password_twice = trim($_POST['password_twice']);
	$email = trim($_GET['email']);
	if ($password_twice == $password_once) {
		query("UPDATE peel_utilisateurs
			SET mot_passe='" . get_user_password_hash($password_once) . "'
			WHERE email='" . nohtml_real_escape_string($email) . "'");
		$noticemsg = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_RECOVER_PASSWORD_OK']))->fetch();
	} else {
		$mode = 'renew_password';
		$form_error_object->add('mismatch_password', $GLOBALS['STR_ERR_MISMATCH_PASSWORD']);
	}
	// Vérification de la présence des deux mots de passe dans le formulaire de renouvellement de mot de passe.
} elseif ((empty($_POST['password_once']) || empty($_POST['password_twice'])) && !empty($_GET['email'])) {
	$mode = 'renew_password';
	$form_error_object->add('empty_field', $GLOBALS['STR_ERR_NEWPASS']);
}

if (empty($noticemsg)) {
	if ($form_error_object->has_error('token')) {
		$output .= $form_error_object->text('token');
	}
	$output .= get_recover_password_form($frm, $form_error_object, $mode);
} else {
	$output .= $GLOBALS['tplEngine']->createTemplate('recover_password_form.tpl', array('message' => $noticemsg, 'get_password' => $GLOBALS['STR_GET_PASSWORD']))->fetch();
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
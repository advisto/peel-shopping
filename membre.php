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
// $Id: membre.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_ACCES_ACCOUNT', true);
include("configuration.inc.php");

if (est_identifie()) {
	redirect_and_die(get_account_url(false, false));
}

$GLOBALS['page_name'] = 'membre';
$GLOBALS['DOC_TITLE'] =  $GLOBALS['STR_ACCES_ACCOUNT'];

$form_error_object = new FormError();
$frm = array();
$output = '';

/* Le formulaire a été soumis, vérification des paramètres de connexion */
if (!empty($_POST)) {
	$_POST['email'] = trim(vb($_POST['email']));
	$_POST['mot_passe'] = trim(vb($_POST['mot_passe']));
	// On ne garde que l'email pour préremplir le formulaire si une erreur intervient
	$frm['email'] = $_POST['email'];
	if (!verify_token('membre.php', 120, false)) {
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	$form_error_object->valide_form($_POST,
		array('mot_passe' => $GLOBALS['STR_ERR_PASSWORD_EMPTY'],
			'email' => $GLOBALS['STR_ERR_EMAIL_VIDE']));
	$output .= call_module_hook('user_login_check_form', array('email' => vb($_POST['email'])), 'string');
	
	if (!$form_error_object->count()) {
		$utilisateur = user_login_now($_POST['email'], $_POST['mot_passe']);
		if ($utilisateur) {
			if (!empty($_SESSION['session_redirect_after_login']) && strpos($_SESSION['session_redirect_after_login'], $GLOBALS['wwwroot']) === 0) {
				// Pour éviter que des spammeurs n'utilisent referer, on vérifie que l'URL de redirection contient wwwroot
				$goto = $_SESSION['session_redirect_after_login'];
				unset($_SESSION['session_redirect_after_login']);
			} elseif ($_SESSION['session_caddie']->count_products() > 0) {
				$goto = get_url('caddie_affichage');
			} else {
				$goto = get_account_url(false, false);
			}
			redirect_and_die($goto);
		} else {
			$form_error_object->add('email', $GLOBALS['STR_ERR_BAD_EMAIL_OR_PASSWORD']);
		}
	}
}

if (!empty($_GET['error'])) {
	if ($_GET['error'] == 'admin_rights') {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_NO_RIGHTS_TO_ACCESS_ADMIN']))->fetch();
	} elseif ($_GET['error'] == 'login_rights') {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_INSERT_LOGIN_AND_PASSWORD']))->fetch();
	}
}
if ($form_error_object->has_error('token')) {
	$output .= $form_error_object->text('token');
}
$output .= '
' . get_access_account_form($frm, $form_error_object);

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

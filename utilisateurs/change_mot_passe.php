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
// $Id: change_mot_passe.php 55332 2017-12-01 10:44:06Z sdelaporte $
include("../configuration.inc.php");
include($GLOBALS['dirroot']."/lib/fonctions/display_user_forms.php");

necessite_identification();

$frm = $_POST;
$form_error_object = new FormError();
$output = '';
$noticemsg = '';
// Le formulaire a été soumis, vérifie si les paramètres de connexion sont corrects
if (!empty($_POST)) {
	if (a_priv('demo')) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
		die();
	}
	$form_error_object->valide_form($frm,
		array('ancien_mot_passe' => $GLOBALS['STR_ERR_OLDPASS'],
			'nouveau_mot_passe' => sprintf($GLOBALS['STR_ERR_NEWPASS'], vb($GLOBALS['site_parameters']['password_length_required'], 8)),
			'nouveau_mot_passe2' => $GLOBALS['STR_ERR_NEWPASS_CONFIRM']), array('nouveau_mot_passe' => vn($GLOBALS['site_parameters']['password_length_required'], 8)), array('nouveau_mot_passe' => 'check_password_format'));
	if (!$form_error_object->has_error('ancien_mot_passe') && !verifier_authentification(null, $frm["ancien_mot_passe"], $_SESSION['session_utilisateur']['id_utilisateur'])) {
		$form_error_object->add('ancien_mot_passe', $GLOBALS['STR_ERR_OLDPASS_VALID']);
	}
	if (!$form_error_object->has_error('nouveau_mot_passe') && !$form_error_object->has_error('nouveau_mot_passe2') && $frm["nouveau_mot_passe"] != $frm["nouveau_mot_passe2"]) {
		$form_error_object->add('nouveau_mot_passe', $GLOBALS['STR_ERR_TWOPASS']);
	}
	if (!verify_token('change_password', 120, false)) {
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	if (!$form_error_object->count()) {
		maj_mot_passe($_SESSION['session_utilisateur']['id_utilisateur'], $frm["nouveau_mot_passe"]);
		$noticemsg = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_PASSWORD_CHANGE'], 'text' => $GLOBALS['STR_BACK_HOME']))->fetch();
	}
}

$output .= get_change_password_form($frm, $form_error_object, $noticemsg);

define('IN_CHANGE_PASSWORD', true);
$GLOBALS['page_name'] = 'change_mot_passe';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_CHANGE_PASSWORD'];

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


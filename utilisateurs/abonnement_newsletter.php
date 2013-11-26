<?php
// This file should be in UTF8 without BOM - Accents examples : éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0,  which is subject to an    |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+

include("../configuration.inc.php");

define('IN_NEWSLETTER_US', true);
$page_name = 'newsletter';

$form_error_object = new FormError();

if (!empty($_POST['email'])) {
	$frm['email'] = $_POST['email'];
	$frm['newsletter'] = 1;
	$frm['priv'] = 'newsletter';
	$qid_user = array();
	$update = false;

	if (!verify_token('get_simple_newsletter', 120, false)) {
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	} else {
		if (!EmailOK($frm['email'])) {
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
		} elseif ((num_rows(query("SELECT 1
			FROM peel_utilisateurs
			WHERE email = '" . nohtml_real_escape_string($frm['email']) . "'")) > 0)) {
			// On met à jour son compte
			$update = true;
			query("UPDATE peel_utilisateurs
				SET newsletter = '" . intval($frm['newsletter']) . "'
				WHERE email = '" . nohtml_real_escape_string($frm['email']) . "'");
		}

		if (!$form_error_object->count() && $update == false) {
			$user_id = insere_utilisateur($frm, false, false);
			if (empty($user_id)) { // insertion échoué
				$form_error_object->add('notif', $GLOBALS['STR_BAD_INSCRIPTION']);
			}
		}
	}
} else {
	$form_error_object->add('notif', $GLOBALS['STR_ERR_FORM']);
}

include($GLOBALS['repertoire_modele'] . "/haut.php");

$tpl = $GLOBALS['tplEngine']->createTemplate('abonnement_newsletter.tpl');
$tpl->assign('STR_NEWSLETTER_TITLE', $GLOBALS['STR_NEWSLETTER_TITLE']);
if ($form_error_object->count()) {
	$tpl->assign('errors', array('token' => $form_error_object->text('token'),
			'email' => $form_error_object->text('email'),
			'notif' => $form_error_object->text('notif'),
			));
} else {
	$tpl->assign('newsletter_subscribe_txt', $GLOBALS['STR_REQUEST_OK']);
}
echo $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
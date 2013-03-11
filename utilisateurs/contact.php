<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: contact.php 35805 2013-03-10 20:43:50Z gboussin $
include("../configuration.inc.php");
include("../lib/fonctions/display_user_forms.php");

if (is_photodesk_module_active()) {
	include($GLOBALS['fonctionsphotodesk']);
}

$page_name = 'contact';

$frm = $_POST;
$form_error_object = new FormError();

if (!empty($_POST)) {

	if (!empty($frm['phone'])) {
		// Formulaire de demande de rappel par téléphone
		// Non implémenté par défaut
		$frm['nom'] = $frm['phone'];
		$frm['telephone'] = $frm['phone'];
		$frm['sujet'] = $GLOBALS["STR_CALL_BACK_EMAIL"]; // Variable de langue à définir
	} else {
		// Le formulaire a été soumis, on essaie de créer un nouveau compte d'utilisateur
		if (is_advistofr_module_active()) {
			$form_error_object->valide_form($frm,
				array('nom' => $GLOBALS['STR_ERR_NAME'],
					'email' => $GLOBALS['STR_ERR_EMAIL'],
					'texte' => $GLOBALS['STR_ERR_MESSAGE'],
					'token' => ''));
		} else {
			$form_error_object->valide_form($frm,
				array('nom' => $GLOBALS['STR_ERR_NAME'],
					'prenom' => $GLOBALS['STR_ERR_FIRSTNAME'],
					'telephone' => $GLOBALS['STR_ERR_TEL'],
					'email' => $GLOBALS['STR_ERR_EMAIL'],
					'texte' => $GLOBALS['STR_ERR_MESSAGE'],
					'sujet' => $GLOBALS['STR_ERR_SUBJECT'],
					'token' => ''));
		}
		if (!$form_error_object->has_error('email')) {
			$frm['email'] = trim($frm['email']);
			if (!EmailOK($frm['email'])) {
				// si il y a un email on teste l'email
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
			}
		}
		if (!$form_error_object->has_error('commande_id') && vb($frm['sujet']) == $GLOBALS['STR_CONTACT_SELECT3'] && empty($frm['commande_id'])) {
			$form_error_object->add('commande_id', $GLOBALS['STR_ERR_ORDER_NUMBER']);
		}
		if (is_captcha_module_active() && !is_advistofr_module_active()) {
			if (empty($frm['code'])) {
				// Pas de tentative de déchiffrement, on laisse le captcha
				$form_error_object->add('code', $GLOBALS['STR_EMPTY_FIELD']);
			} else {
				if (!check_captcha($frm['code'], $frm['code_id'])) {
					$form_error_object->add('code', $GLOBALS['STR_CODE_INVALID']);
					// Code mal déchiffré, on en donne un autre
					delete_captcha(vb($frm['code_id']));
					unset($frm['code']);
				}
			}
		}
	}
	if (!verify_token('user_contact', 60, false)) {
		// Important : évite spam de la part de robots simples qui appellent en POST la validation de formulaire
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	if (!$form_error_object->count()) {
		if (is_captcha_module_active ()) {
			// Code OK on peut effacer le code
			delete_captcha(vb($frm['code_id']));
		}
		if (empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['REQUEST_METHOD'] != "POST") {
			// Protection du formulaire contre les robots
			die();
		}
		// Limitation du nombre de messages envoyés dans une session
		if (empty($_SESSION['session_form_contact_sent'])) {
			$_SESSION['session_form_contact_sent'] = 0;
		}
		if ($_SESSION['session_form_contact_sent'] < 10) {
			insere_ticket($frm);
			$_SESSION['session_form_contact_sent']++;
			$frm['is_ok'] = true;
		}
		if (!is_advistofr_module_active()) {
			include($GLOBALS['repertoire_modele'] . "/haut.php");
			// Si le module webmail est activé, on insere dans la table webmail la requete user
			echo get_contact_success($frm);
			include($GLOBALS['repertoire_modele'] . "/bas.php");
			die();
		}
	}
}

define('IN_CONTACT', true);
// $form_error_object = new FormError();
include($GLOBALS['repertoire_modele'] . "/haut.php");

if (!empty($noticemsg)) {
	echo $noticemsg;
}

echo get_contact_form($frm, $form_error_object);

include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
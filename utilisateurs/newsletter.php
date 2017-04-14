<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2017 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.5, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: newsletter.php 53200 2017-03-20 11:19:46Z sdelaporte $
define('IN_NEWSLETTER', true);

include("../configuration.inc.php");

$GLOBALS['page_name'] = 'newsletter';

$output = '';
$form_error_object = new FormError();
if (vb($_GET['mode']) == 'inscription') {
	$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_NEWSLETTER_TITLE'];
	if (!verify_token('get_simple_newsletter', 120, false) && empty($_POST['disable_token'])) {
		// Vérification du token pour empecher les inscriptions de robots
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	if (empty($_POST['email']) || !EmailOK($_POST['email'])) {
		// Vérification du format de l'email
		$form_error_object->add('notif', $GLOBALS['STR_ERR_EMAIL_BAD']);
	}
	$output .= newsletter_validation($_POST, $form_error_object);
} elseif (vb($_GET['mode']) == 'desinscription') {
	// Les inscrits à la newsletter n'ont pas de compte valide, il ne peuvent pas se connecter.
	$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_DESINSCRIPTION_NEWSLETTER'];
	$output .= desinscription_newsletter(vb($_GET['email']));
} else {
	redirect_and_die(get_url('membre'));
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: newsletter.php 64741 2020-10-21 13:48:51Z sdelaporte $
define('IN_NEWSLETTER', true);

include("../configuration.inc.php");

$GLOBALS['page_name'] = 'newsletter';
if(!empty($_GET['email']) && $_GET['email'] == '[EMAIL]') {
	if(vb($_GET['mode']) == 'subscribe_newsletter') {
		redirect_and_die('/utilisateurs/newsletter.php?mode=inscription');
	} else {
		unset($_GET['email']);
	}
}

$output = '';
$form_error_object = new FormError();
if (vb($_GET['mode']) == 'inscription') {
	$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_NEWSLETTER_TITLE'];
	if (!verify_token('get_simple_newsletter', 120, true, true, 5)) { 
		// Vérification du token pour empêcher les inscriptions de robots
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	if (empty($_POST['email']) || !EmailOK($_POST['email'])) {
		// Vérification du format de l'email
		$form_error_object->add('notif', $GLOBALS['STR_ERR_EMAIL_BAD']);
	}
	$output .= newsletter_validation($_POST, $form_error_object);
} elseif (vb($_GET['mode']) == 'desinscription' && (empty($_GET['email']) || !EmailOK($_GET['email']))) {
	// Formulaire de désinscription et traitement de ce formulaire
	$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_DESINSCRIPTION_NEWSLETTER'];
	$frm = array();
	if(isset($_GET['email']) && !isset($_POST['email'])) {
		$_POST['email'] = $_GET['email'];
	}
	if (isset($_POST['email'])) {
		if (empty($_POST['email'])) {
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_VIDE']);
			$output = newsletter_desinscription_form($frm, $form_error_object);
		} else {
			$_POST['email'] = trim(vb($_POST['email']));
			$frm['email'] = $_POST['email'];
			if (!EmailOK($_POST['email'])) {
				// si il y a un email on teste l'email
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
				$output = newsletter_desinscription_form($frm, $form_error_object);
			}
			if (!$form_error_object->count()) {
				$output = desinscription_newsletter($_POST['email']);
			}
		}
	} else {
		$output = newsletter_desinscription_form($frm, $form_error_object);
	}
	// afficher un menu de login membre simple
	$output = '<div id="contact">' . $output . '</div>';
} elseif (vb($_GET['mode']) == 'desinscription' && !empty($_GET['email'])) {
	// Les inscrits à la newsletter n'ont pas de compte valide, il ne peuvent pas se connecter.
	$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_DESINSCRIPTION_NEWSLETTER'];
	// Demande bien prise en compte de notre base d'emailing;
	$output .= desinscription_newsletter(vb($_GET['email']));
} elseif(vb($_GET['mode']) == 'subscribe_newsletter' && !empty($_GET['email'])) {
	$est_inscrit = est_inscrit_newsletter($_GET['email']);
	$newsletter_validation_date = get_mysql_date_from_user_input(time());
	$sql = "UPDATE peel_utilisateurs
		SET newsletter='1', newsletter_validation_date='" . nohtml_real_escape_string($newsletter_validation_date) . "'
		WHERE email = '" . nohtml_real_escape_string($_GET['email']) . "'";
	$query = query($sql);
	$user_info = get_user_information($_GET['email']);
	
	// Si l'inscription à la newsletter se fait depuis le header (et que le client n'est pas déjà inscrit), on crée un code promo
	if(!empty($_GET['from_header']) && !$est_inscrit) {
		$promotional_code_array['nom'] = create_random_new_code_promo_newsletter();
		$promotional_code_array['remise_percent'] = 10;
		$promotional_code_array['on_type'] = 1;
		$promotional_code_array['etat'] = 1;
		$promotional_code_array['id_utilisateur'] = $user_info['id_utilisateur'];
		// Validité de un mois jusqu'à minuit
		$promotional_code_array["date_fin"] = get_formatted_date(date("Y-m-d", mktime(0, 0, 0, date('m')+1, date('d')+1, date('Y'))));
		insere_code_promo($promotional_code_array);

		// création des tags qui seront remplacé dans l'email
		$custom_template_tags['TYPE'] = $GLOBALS["STR_TO_NEWSLETTER"];
		$custom_template_tags['CODE'] = $promotional_code_array['nom'];
		$custom_template_tags['DATE_FIN'] = get_formatted_date($promotional_code_array['date_fin']);
		send_email($_GET['email'], '', '', 'newsletter_send_code_promo', $custom_template_tags);
	}

	$message = $GLOBALS["STR_PARAMETERS_SAVED"];
	if ($user_info['priv'] != 'newsletter') {
		$message .= $GLOBALS["STR_PARAMETERS_COMPLEMENT"];
	}
	$output .= '<br /><br />'.$GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $message))->fetch();
} elseif(vb($_GET['mode']) == 'subscribe_commercial' && !empty($_GET['email'])) {
	$commercial_validation_date = get_mysql_date_from_user_input(time());
	$sql = "UPDATE peel_utilisateurs
		SET commercial = '1', commercial_validation_date='".nohtml_real_escape_string(vb($commercial_validation_date))."'
		WHERE email = '".nohtml_real_escape_string($_GET['email'])."'";
	$query = query($sql);
	
	$user_info = get_user_information($_GET['email']);
	$message = $GLOBALS["STR_PARAMETERS_SAVED"];
	if ($user_info['priv'] != 'newsletter') {
		$message .= $GLOBALS["STR_PARAMETERS_COMPLEMENT"];
	}
	$output .= '<br /><br />'.$GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $message))->fetch();
} else {
	redirect_and_die(get_url('membre'));	
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


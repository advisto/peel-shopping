<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: change_params.php 38682 2013-11-13 11:35:48Z gboussin $
include("../configuration.inc.php");
necessite_identification();

include("../lib/fonctions/display_user_forms.php");

$form_error_object = new FormError();

if (!empty($_POST)) {
	if (a_priv('demo')) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_DEMO_RIGHTS_LIMITED']))->fetch();
		die();
	}
	/* Le formulaire a été soumis, vérifie si les infos de l'utilisateur sont correctes */
	$frm = $_POST;
	if (is_abonnement_module_active() && is_user_verified($_SESSION['session_utilisateur']['id_utilisateur'])) {
		$disabled_verified_fields = true;
		$original_frm = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
		foreach(array('email', 'telephone', 'portable', 'fax', 'prenom', 'nom_famille', 'societe', 'siret', 'adresse', 'code_postal', 'ville', 'pays', 'intracom_for_billing') as $this_key) {
			$frm[$this_key] = vb($original_frm[$this_key]);
		}
	}
	foreach($GLOBALS['site_parameters']['user_mandatory_fields'] as $this_key => $this_text_key) {
		$form_error_names[$this_key] = $GLOBALS[$this_text_key];
	}
	$form_error_object->valide_form($frm, $form_error_names);
	if ((num_rows(query("SELECT 1
			FROM peel_utilisateurs
			WHERE id_utilisateur!='" . intval($frm['id_utilisateur']) . "' AND email = '" . nohtml_real_escape_string($frm['email']) . "'")) > 0)) {
		$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_STILL']);
	}
	if (isset($frm['pseudo']) && (num_rows(query("SELECT 1
			FROM peel_utilisateurs
			WHERE id_utilisateur!='" . intval($frm['id_utilisateur']) . "' AND pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "'")) > 0)) {
		$form_error_object->add('pseudo', $GLOBALS['STR_ERR_NICKNAME_STILL']);
	}
	if (!verify_token('change_params', 120, false)) {
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	// Si nous sommes en France, nous avons renseigner le numéro $GLOBALS['STR_SIREN'], et cela nécessite un contrôle
	if (isset($frm['siret']) && vb($frm['pays']) == 1 && !preg_match("#([0-9]){9,14}#", str_replace(array(' ', '.'), '', $frm['siret']))) {
		$form_error_object->add('siret', $GLOBALS['STR_ERR_SIREN']);
	}
	if (empty($frm['lang']) && isset($frm['lang'])) {
		// Ce champ n'est défini que si le site est en plusieurs langues
		$form_error_object->add('lang', $GLOBALS['STR_EMPTY_FIELD']);
	}
	if (is_destockplus_module_active() || is_algomtl_module_active()) {
		// Le champ societe est rendu obligatoire
		if (empty($frm['societe'])) {
			$form_error_object->add('societe', $GLOBALS['STR_ERR_SOCIETY']);
		}
		// Le champ type est obligatoire
		if (empty($frm['type'])) {
			$form_error_object->add('type', $GLOBALS['STR_ERR_YOU_ARE']);
		}
		// Le type d'activité est obligatoire
		if (empty($frm['activity'])) {
			$form_error_object->add('activity', $GLOBALS['STR_ERR_ACTIVITY']);
		}
	}
	if (is_annonce_module_active()) {
		// Le choix d'une categorie favorite est obligatoire
		if (!empty($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) && (empty($frm['id_categories']) || count($frm['id_categories']) == 0)) {
			$form_error_object->add('favorite_category_error', $GLOBALS['STR_ERR_FIRST_CHOICE']);
		} elseif (empty($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) && empty($frm['id_cat_1'])) {
			$form_error_object->add('id_cat_1', $GLOBALS['STR_ERR_FIRST_CHOICE']);
		}
		$add_pseudo_error = (isset($frm['pseudo']) && (empty($frm['pseudo']) || searchKeywordFiltersInLogin($frm['pseudo']) || String::strpos($frm['pseudo'], '@') !== false));
		$add_mail_error = (empty($frm['email']) || searchKeywordFiltersInMail($frm['email'])) ;
	} else {
		$add_pseudo_error = (empty($frm['pseudo']) || String::strpos($frm['pseudo'], '@') !== false);
		$add_mail_error = empty($frm['email']);
	}
	if ($add_pseudo_error) {
		$form_error_object->add('pseudo', $GLOBALS['STR_ERR_PSEUDO']);
	}
	if ($add_mail_error) {
		$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL']);
	} elseif (!EmailOK($frm['email'])) {
		// si il y a un email on teste l'email
		$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
	}

	if (!$form_error_object->count()) {
		maj_utilisateur($frm, true);
		$noticemsg = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MSG_CHANGE_PARAMS'], 'list_content' => $GLOBALS['STR_CHANGE_PARAMS_OK']))->fetch();
	}
} else {
	$frm = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
}

define('IN_CHANGE_PARAMS', true);
$page_name = 'change_params';

include($GLOBALS['repertoire_modele'] . "/haut.php");

if (empty($noticemsg)) {
	echo get_user_change_params_form($frm, $form_error_object);
} else {
	echo $noticemsg;
}

include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
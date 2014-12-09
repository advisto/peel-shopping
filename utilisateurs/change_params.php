<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: change_params.php 43040 2014-10-29 13:36:21Z sdelaporte $
include("../configuration.inc.php");
necessite_identification();

define('IN_CHANGE_PARAMS', true);
$GLOBALS['page_name'] = 'change_params';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_CHANGE_PARAMS'];

include("../lib/fonctions/display_user_forms.php");

$form_error_object = new FormError();
// Dans un premier temps on stocke dans $mandatory_array les champs obligatoires indiqués dans $GLOBALS['site_parameters']['user_mandatory_fields'].
// Dans un second temps on ajoute à cette variable les champs obligatoires qui doivent être vérifiés si des modules ou variables de configurations sont présents.
// On supprime les conditions sur les modules et variables de configuration dans le foreach puisque celle-ci sont faites lors de la construction de $mandatory_fields.
$mandatory_fields = array();
if(isset($GLOBALS['site_parameters']['user_mandatory_fields'])) {
	foreach($GLOBALS['site_parameters']['user_mandatory_fields'] as $key => $value) {
		if(check_if_module_active('annonces')) {
			if($key == 'pseudo') {
				continue;
			}
		} elseif($key == 'code') {
			// pas de controle par captcha sur la page de changement de paramètre.
			continue;
		}
		$mandatory_fields[$key] = $value;
	}
}
if(!empty($GLOBALS['site_parameters']['add_b2b_form_inputs'])) {
	$mandatory_fields['societe'] = 'STR_ERR_SOCIETY';
	$mandatory_fields['type'] = 'STR_ERR_YOU_ARE';
	$mandatory_fields['activity'] = 'STR_ERR_ACTIVITY';
	$mandatory_fields['siret'] = 'STR_ERR_SIREN';
}
if(check_if_module_active('annonces')) {
	if(!empty($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories'])) {
		$mandatory_fields['id_categories'] = 'STR_ERR_FIRST_CHOICE';
	} else {
		$mandatory_fields['id_cat_1'] = 'STR_ERR_FIRST_CHOICE';
	}
}

if (!empty($_POST)) {
	if (a_priv('demo')) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_DEMO_RIGHTS_LIMITED']))->fetch();
		die();
	}
	/* Le formulaire a été soumis, vérifie si les infos de l'utilisateur sont correctes */
	$frm = $_POST;
	if (check_if_module_active('abonnement') && is_user_verified($_SESSION['session_utilisateur']['id_utilisateur'])) {
		$disabled_verified_fields = true;
		$original_frm = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
		foreach(array('email', 'telephone', 'portable', 'fax', 'prenom', 'nom_famille', 'societe', 'siret', 'adresse', 'code_postal', 'ville', 'pays', 'intracom_for_billing') as $this_key) {
			$frm[$this_key] = vb($original_frm[$this_key]);
		}
	}
	foreach($mandatory_fields as $this_key => $this_text_key) {
		if($this_key == 'email') {
			if ((num_rows(query("SELECT 1
					FROM peel_utilisateurs
					WHERE id_utilisateur!='" . intval($frm['id_utilisateur']) . "' AND email = '" . nohtml_real_escape_string($frm['email']) . "' AND " . get_filter_site_cond('utilisateurs'))) > 0)) {
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_STILL']);
			}
		} elseif($this_key == 'pseudo') {
			if (isset($frm['pseudo']) && (num_rows(query("SELECT 1
					FROM peel_utilisateurs
					WHERE id_utilisateur!='" . intval($frm['id_utilisateur']) . "' AND pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "' AND " . get_filter_site_cond('utilisateurs'))) > 0)) {
				$form_error_object->add('pseudo', $GLOBALS['STR_ERR_NICKNAME_STILL']);
			}
		} elseif($this_key == 'token') {
			if (!verify_token('change_params', 120, false)) {
				$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
			}
		} elseif($this_key == 'siret') {
			// Si nous sommes en France, nous avons renseigner le numéro $GLOBALS['STR_SIREN'], et cela nécessite un contrôle
			if (isset($frm['siret']) && vb($frm['pays']) == 1 && !preg_match("#([0-9]){9,14}#", str_replace(array(' ', '.'), '', $frm['siret'])) && in_array('siret', array_keys($GLOBALS['site_parameters']['user_mandatory_fields']))) {
				$form_error_object->add('siret', $GLOBALS['STR_ERR_SIREN']);
			}
		} elseif($this_key == 'lang') {
			if (empty($frm['lang']) && isset($frm['lang'])) {
				// Ce champ n'est défini que si le site est en plusieurs langues
				$form_error_object->add('lang', $GLOBALS['STR_EMPTY_FIELD']);
			}
		} elseif($this_key == 'societe') {
			// Le champ societe est rendu obligatoire
			if (empty($frm['societe'])) {
				$form_error_object->add('societe', $GLOBALS['STR_ERR_SOCIETY']);
			}
		} elseif($this_key == 'type') {
			// Le champ type est obligatoire
			if (empty($frm['type'])) {
				$form_error_object->add('type', $GLOBALS['STR_ERR_YOU_ARE']);
			}
		} elseif($this_key == 'activity') {
			// Le type d'activité est obligatoire
			if (empty($frm['activity'])) {
				$form_error_object->add('activity', $GLOBALS['STR_ERR_ACTIVITY']);
			}
		} elseif ($this_key == 'id_categories') {
			if(empty($frm['id_categories']) || count($frm['id_categories']) == 0) {
				$form_error_object->add('favorite_category_error', $GLOBALS['STR_ERR_FIRST_CHOICE']);
			}
		} elseif ($this_key == 'id_cat_1') {
			if(empty($frm['id_cat_1'])) {
				$form_error_object->add('id_cat_1', $GLOBALS['STR_ERR_FIRST_CHOICE']);
			}
		} elseif($this_key == 'pseudo') {
			if (check_if_module_active('annonces')) {
				$add_pseudo_error = (isset($frm['pseudo']) && (empty($frm['pseudo']) || searchKeywordFiltersInLogin($frm['pseudo']) || String::strpos($frm['pseudo'], '@') !== false));
			} else {
				$add_pseudo_error = (empty($frm['pseudo']) || String::strpos($frm['pseudo'], '@') !== false);
			}
			if ($add_pseudo_error) {
				$form_error_object->add('pseudo', $GLOBALS['STR_ERR_PSEUDO']);
			}
		} elseif($this_key == 'email') {
			if (check_if_module_active('annonces')) {
				$add_mail_error = (empty($frm['email']) || searchKeywordFiltersInMail($frm['email'])) ;
			} else {
				$add_mail_error = empty($frm['email']);
			}if ($add_mail_error) {
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL']);
			} elseif (!EmailOK($frm['email'])) {
				// si il y a un email on teste l'email
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
			}
		}
		$form_error_names[$this_key] = $GLOBALS[$this_text_key];
	}
	$form_error_object->valide_form($frm, $form_error_names);
	if (!$form_error_object->count()) {
		maj_utilisateur($frm, true);
		$noticemsg = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MSG_CHANGE_PARAMS'], 'list_content' => $GLOBALS['STR_CHANGE_PARAMS_OK']))->fetch();
	}
} else {
	$frm = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
}

include($GLOBALS['repertoire_modele'] . "/haut.php");

if (empty($noticemsg)) {
	echo get_user_change_params_form($frm, $form_error_object);
} else {
	echo $noticemsg;
}

include($GLOBALS['repertoire_modele'] . "/bas.php");


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: enregistrement.php 37156 2013-06-05 12:42:24Z sdelaporte $
include("../configuration.inc.php");
include("../lib/fonctions/display_user_forms.php");

if (est_identifie()) {
	redirect_and_die($GLOBALS['wwwroot'] . "/utilisateurs/change_params.php");
}

$page_name = 'enregistrement';
// initialisation des variables
$frm = $_POST;
$form_error_object = new FormError();

if (is_socolissimo_module_active()) {
	// Securité SO Colissimo pour bien s'assurer que le process de commande sera cohérent
	unset($_SESSION['session_commande']);
}

if (!empty($frm)) {
	foreach($GLOBALS['site_parameters']['user_mandatory_fields'] as $this_key => $this_text_key) {
		$form_error_names[$this_key] = $GLOBALS[$this_text_key];
	}
	$form_error_names['mot_passe'] = $GLOBALS['STR_ERR_PASSWORD'];
	$form_error_object->valide_form($frm, $form_error_names);
	if (is_destockplus_module_active() || is_algomtl_module_active()) {
		// Le champ societe est rendu obligatoire
		if (empty($frm['societe'])) {
			$form_error_object->add('societe', $GLOBALS['STR_ERR_SOCIETY']);
		}
		// Le champ type est rendu obligatoire
		if (empty($frm['type'])) {
			$form_error_object->add('type', $GLOBALS['STR_ERR_YOU_ARE']);
		}
		// Le type d'activité est obligatoire
		if (empty($frm['activity'])) {
			$form_error_object->add('activity', $GLOBALS['STR_ERR_ACTIVITY']);
		}
	}
	// Si nous sommes en France, nous avons renseigner le numéros $GLOBALS['STR_SIREN'], nécéssite un contrôle
	if (isset($frm['siret']) && vb($frm['pays']) == 1 && !preg_match("#([0-9]){9,14}#", str_replace(array(' ', '.'), '', $frm['siret']))) {
		$form_error_object->add('siret', $GLOBALS['STR_ERR_SIREN']);
	}
	if (is_annonce_module_active()) {
		if (empty($frm['cgv_confirm'])) {
			$form_error_object->add('cgv_confirm', $GLOBALS['STR_ERR_CGV_CONFIRM']);
		}
		if (vb($frm['mot_passe_confirm']) != vb($frm['mot_passe'])) { // on doit confirmer le mot de passe
			$form_error_object->add('mot_passe_confirm', $GLOBALS['STR_ERR_PASS_CONFIRM']);
		}
		if (empty($frm['mot_passe_confirm'])) {
			$form_error_object->add('mot_passe_confirm', $GLOBALS['STR_ERR_PASSWORD_CONFIRM']);
		}
		if (!empty($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) && empty($frm['id_categories']) || count($frm['id_categories']) == 0) {
			$form_error_object->add('favorite_category_error', $GLOBALS['STR_ERR_FIRST_CHOICE']);
		} elseif (empty($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) && empty($frm['id_cat_1'])) {
			$form_error_object->add('id_cat_1', $GLOBALS['STR_ERR_FIRST_CHOICE']);
		}
		$add_pseudo_error = (empty($frm['pseudo']) || searchKeywordFiltersInLogin($frm['pseudo']) || String::strpos($frm['pseudo'], '@') !== false) ;
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
	}
	if (is_captcha_module_active()) {
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

	if (!verify_token('get_user_register_form', 60, false)) {
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	if (!$form_error_object->has_error('email')) {
		$frm['email'] = trim($frm['email']);
		if (!EmailOK($frm['email'])) {
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
		} elseif ((num_rows(query("SELECT 1
			FROM peel_utilisateurs
			WHERE email = '" . nohtml_real_escape_string($frm['email']) . "' AND priv!='load' AND priv!='newsletter'")) > 0)) {
			// Test de l'unicité de l'email, sauf pour les utilisateurs n'étant pas inscrit via le téléchargement.
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_STILL']);
		}
	}
	if ((num_rows(query("SELECT 1
		FROM peel_utilisateurs
		WHERE pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "'")) > 0)) {
		$form_error_object->add('pseudo', $GLOBALS['STR_ERR_NICKNAME_STILL']);
	}
	if (!$form_error_object->count()) {
		if (is_captcha_module_active ()) {
			// Code OK on peut effacer le code
			delete_captcha($frm['code_id']);
		}
		$user_id = insere_utilisateur($frm, false, true, true);
		user_login_now($frm['email'], $frm['mot_passe']);

		if ($_SESSION['session_caddie']->count_products() > 0) {
			if (empty($_SESSION['session_caddie']->zoneId) || empty($_SESSION['session_caddie']->typeId)) {
				include($GLOBALS['repertoire_modele'] . "/haut.php");
				echo get_user_register_success($frm);
				include($GLOBALS['repertoire_modele'] . "/bas.php");
			} else {
				if (is_socolissimo_module_active()) {
					// Pour SO Colissimo, si on s'inscrit "en cours de commande", on force le passage vers le caddie de nouveau, sinon le passage par l'interface de SO Colissimo serait zappée ---> Commande incomplète en BDD.
					redirect_and_die($GLOBALS['wwwroot'] . "/achat/caddie_affichage.php");
				} else {
					redirect_and_die($GLOBALS['wwwroot'] . "/achat/achat_maintenant.php");
				}
			}
		} else {
			include($GLOBALS['repertoire_modele'] . "/haut.php");
			echo get_user_register_success($frm);
			include($GLOBALS['repertoire_modele'] . "/bas.php");
		}
		die();
	}
}
// si on a tenté sans succès de se connecter via un site extérieur et que la connexion a réussi mais qu'aucun compte sur le site n'a été trouvé,
// alors on préremplit les champs d'inscription avec les données du site extérieur
if (is_facebook_connect_module_active() && !empty($_SESSION['session_utilisateur']['fb_user_info'])) {
	init_register_form_with_facebook_infos($frm);
}
if (is_sign_in_twitter_module_active() && !empty($_SESSION['session_utilisateur']['tw_user_info'])) {
	init_register_form_with_twinfos($frm);
}
if (is_openid_module_active() && !empty($_SESSION['session_utilisateur']['openid_user_info'])) {
	init_register_form_with_openid_infos($frm);
}

define('IN_REGISTER', true);
include($GLOBALS['repertoire_modele'] . "/haut.php");

if ($form_error_object->count()) {
	echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_FORM']))->fetch();
}
if (!empty($noticemsg)) {
	echo $noticemsg;
}
if ($form_error_object->has_error('token')) {
	echo $form_error_object->text('token');
}
echo get_user_register_form($frm, $form_error_object);

include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
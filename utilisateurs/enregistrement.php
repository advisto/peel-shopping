<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: enregistrement.php 44077 2015-02-17 10:20:38Z sdelaporte $
include("../configuration.inc.php");
include("../lib/fonctions/display_user_forms.php");

define('IN_REGISTER', true);
if (est_identifie()) {
	if (!empty($_GET['devis']) && !empty($GLOBALS['site_parameters']['create_user_when_ask_for_quote']) && check_if_module_active('devis')) {
		// Création d'une commande de devis en base de données pour un utilisateur loggué n'ayant pas le droit de voir les prix
		$output = create_devis_order($frm);
		include($GLOBALS['repertoire_modele'] . "/haut.php");
		echo $output;
		include($GLOBALS['repertoire_modele'] . "/bas.php");
		die();
	} else {
		redirect_and_die($GLOBALS['wwwroot'] . "/utilisateurs/change_params.php");
	}
}

$GLOBALS['page_name'] = 'enregistrement';
$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_OPEN_ACCOUNT"];

// initialisation des variables
$frm = $_POST;
if(isset($frm['email'])) {
	$frm['email'] = trim($frm['email']);
}
$form_error_object = new FormError();
// Dans un premier temps on stocke dans $mandatory_fields les champs obligatoires indiqués dans $GLOBALS['site_parameters']['user_mandatory_fields'].
$mandatory_fields = array();
if(isset($GLOBALS['site_parameters']['user_mandatory_fields'])) {
	$mandatory_fields = $GLOBALS['site_parameters']['user_mandatory_fields'];
}
// Dans un second temps on ajoute à cette variable les champs obligatoires qui doivent être vérifiés dans tous les cas, ou si des modules ou variables de configurations sont présents.
$mandatory_fields['mot_passe'] = 'STR_ERR_PASSWORD';
$mandatory_fields['email'] = 'STR_ERR_EMAIL';
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
	$mandatory_fields['cgv_confirm'] = 'STR_ERR_CGV';
	$mandatory_fields['mot_passe_confirm'] = 'STR_ERR_PASS_CONFIRM';
}
if(is_captcha_module_active()) {
	$mandatory_fields['code'] = 'STR_EMPTY_FIELD';
}
foreach($mandatory_fields as $key => $value) {
	// Transformation des valeurs du tableau avec les variables de langue du même nom
	if (!empty($GLOBALS[$value])) {
		$mandatory_fields[$key] = $GLOBALS[$value];
	}
}
if (is_socolissimo_module_active()) {
	// Securité SO Colissimo pour bien s'assurer que le process de commande sera cohérent
	unset($_SESSION['session_commande']);
}
if (!empty($frm)) {
	// D'abord on génère une erreur pour tous les champs obligatoires qui sont vides
	$form_error_object->valide_form($frm, $mandatory_fields);
	
	// On traite ensuite les champs avec des règles plus compliquées
	if (!empty($frm['siret']) && vb($frm['pays']) == 1 && !preg_match("#([0-9]){9,14}#", str_replace(array(' ', '.'), '', $frm['siret']))) {
		// Si nous sommes en France, nous avons renseigné le numéro $GLOBALS['STR_SIREN'], cela nécessite un contrôle de la valeur rentrée par l'utilisateur
		$form_error_object->add('siret', $GLOBALS['STR_ERR_SIREN']);
	}
	if (!empty($frm['mot_passe_confirm']) && vb($frm['mot_passe_confirm']) != vb($frm['mot_passe'])) {
		$form_error_object->add('mot_passe_confirm', $GLOBALS['STR_ERR_PASS_CONFIRM']);
	}
	if(empty($GLOBALS['site_parameters']['pseudo_is_not_used']) && !empty($frm['pseudo'])) {
		$add_pseudo_error = (String::strpos($frm['pseudo'], '@') !== false);
		if (function_exists('searchKeywordFiltersInLogin')) {
			$add_pseudo_error = ($add_pseudo_error || searchKeywordFiltersInLogin($frm['pseudo'])) ;
		}
		if ($add_pseudo_error) {
			$form_error_object->add('pseudo', $GLOBALS['STR_ERR_PSEUDO']);
		} elseif ((num_rows(query("SELECT 1
				FROM peel_utilisateurs
				WHERE pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "' AND " . get_filter_site_cond('utilisateurs') . "")) > 0)) {
			$form_error_object->add('pseudo', $GLOBALS['STR_ERR_NICKNAME_STILL']);
		}
	}
	if(!empty($frm['email'])) {
		if (function_exists('searchKeywordFiltersInMail')) {
			$add_mail_error = searchKeywordFiltersInMail($frm['email']);
			if ($add_mail_error) {
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL']);
			}
		}
		if (!$form_error_object->has_error('email')) {
			if (!EmailOK($frm['email'])) {
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
			} elseif ((num_rows(query("SELECT 1
				FROM peel_utilisateurs
				WHERE email = '" . nohtml_real_escape_string($frm['email']) . "' AND priv NOT IN ('" . implode("','", $GLOBALS['disable_login_by_privilege']) . "') AND " . get_filter_site_cond('utilisateurs'))) > 0)) {
				// Test de l'unicité de l'email, sauf pour les utilisateurs n'étant pas inscrit via le téléchargement.
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_STILL']);
			}
		}
	}
	if (!empty($frm['code']) && is_captcha_module_active()) {
		if (!check_captcha($frm['code'], $frm['code_id'])) {
			$form_error_object->add('code', $GLOBALS['STR_CODE_INVALID']);
			// Code mal déchiffré par l'utilisateur, on en donne un autre
			delete_captcha(vb($frm['code_id']));
			unset($frm['code']);
		}
	}
	if(!empty($frm['token'])) {
		if (!verify_token('get_user_register_form', 120, false)) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
	}
	if(!empty($frm['email_confirm']) && $frm['email_confirm'] != vb($frm['email'])) {
		// On envoie une demande de confirmation d'email
		$form_error_object->add('email_confirm', $GLOBALS['STR_ERR_MISMATCH_EMAIL']);
	}
	
	if (!$form_error_object->count()) {
		// Le formulaire envoyé est apparemment OK, on le traite
		if (is_captcha_module_active()) {
			// Code OK on peut effacer le code
			delete_captcha($frm['code_id']);
		}
		if(is_user_bot()) {
			// Protection du formulaire contre les robots
			die();
		}
		$user_id = insere_utilisateur($frm, false, true, true);
		$utilisateur = user_login_now($frm['email'], $frm['mot_passe']);

		if(!empty($_GET['devis']) && !empty($GLOBALS['site_parameters']['create_user_when_ask_for_quote']) && check_if_module_active('devis')) {
			$output = create_devis_order($frm);
		} else {
			$output = get_user_register_success($frm);
		}
		if (!empty($GLOBALS['site_parameters']['redirect_user_after_login_by_priv'][$utilisateur['priv']])) {
			// Redirection vers une url administrable après la connexion réussie d'un utilisateur.
			redirect_and_die($GLOBALS['site_parameters']['redirect_user_after_login_by_priv'][$utilisateur['priv']]);
		} elseif ($_SESSION['session_caddie']->count_products() > 0) {
			if (empty($_SESSION['session_caddie']->zoneId) || empty($_SESSION['session_caddie']->typeId)) {
				include($GLOBALS['repertoire_modele'] . "/haut.php");
				echo $output;
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
			echo $output;
			include($GLOBALS['repertoire_modele'] . "/bas.php");
		}
		die();
	}
}
// Si on a tenté sans succès de se connecter via un site extérieur et que la connexion a réussi mais qu'aucun compte sur le site n'a été trouvé,
// alors on préremplit les champs d'inscription avec les données du site extérieur
if (is_facebook_connect_module_active() && !empty($_SESSION['session_utilisateur']['fb_user_info'])) {
	init_register_form_with_facebook_infos($frm);
}
if (is_sign_in_twitter_module_active() && !empty($_SESSION['session_utilisateur']['tw_user_info'])) {
	init_register_form_with_twinfos($frm);
}
if (check_if_module_active('openid') && !empty($_SESSION['session_utilisateur']['openid_user_info'])) {
	init_register_form_with_openid_infos($frm);
}
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

echo get_user_register_form($frm, $form_error_object, !empty($_GET['devis']) && !empty($GLOBALS['site_parameters']['create_user_when_ask_for_quote']) && check_if_module_active('devis'));

include($GLOBALS['repertoire_modele'] . "/bas.php");


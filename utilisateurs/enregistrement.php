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
// $Id: enregistrement.php 43105 2014-11-04 17:09:59Z sdelaporte $
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
$form_error_object = new FormError();
// Dans un premier temps on stocke dans $mandatory_array les champs obligatoires indiqués dans $GLOBALS['site_parameters']['user_mandatory_fields'].
// Dans un second temps on ajoute à cette variable les champs obligatoires qui doivent être vérifiés si des modules ou variables de configurations sont présents.
// On supprime les conditions sur les modules et variables de configuration dans le foreach puisque celle-ci sont faites lors de la construction de $mandatory_fields.
$mandatory_fields = array();
if(isset($GLOBALS['site_parameters']['user_mandatory_fields'])) {
	$mandatory_fields = $GLOBALS['site_parameters']['user_mandatory_fields'];
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
	$mandatory_fields['cgv_confirm'] = 'STR_ERR_CGV';
	$mandatory_fields['mot_passe_confirm'] = 'STR_ERR_PASS_CONFIRM';
}
if(is_captcha_module_active()) {
	$mandatory_fields['code'] = 'STR_EMPTY_FIELD';
}

if (is_socolissimo_module_active()) {
	// Securité SO Colissimo pour bien s'assurer que le process de commande sera cohérent
	unset($_SESSION['session_commande']);
}
if (!empty($frm)) {
	foreach($mandatory_fields as $this_key => $this_text_key) {
		$form_error_names['mot_passe'] = $GLOBALS['STR_ERR_PASSWORD'];
		if($this_key == 'societe' && !empty($GLOBALS['site_parameters']['add_b2b_form_inputs'])) {
			// Le champ société est rendu obligatoire
			if (empty($frm['societe'])) {
				$form_error_object->add('societe', $GLOBALS['STR_ERR_SOCIETY']);
			}
		} elseif($this_key == 'type' && !empty($GLOBALS['site_parameters']['add_b2b_form_inputs'])) {
			// Le champ type est rendu obligatoire
			if (empty($frm['type'])) {
				$form_error_object->add('type', $GLOBALS['STR_ERR_YOU_ARE']);
			}
		} elseif($this_key == 'activity' && !empty($GLOBALS['site_parameters']['add_b2b_form_inputs'])) {
			// Le type d'activité est obligatoire
			if (empty($frm['activity'])) {
				$form_error_object->add('activity', $GLOBALS['STR_ERR_ACTIVITY']);
			}
		} elseif($this_key == 'siret') {
			// Si nous sommes en France, nous avons renseigner le numéro $GLOBALS['STR_SIREN'], nécéssite un contrôle
			if (isset($frm['siret']) && vb($frm['pays']) == 1 && !preg_match("#([0-9]){9,14}#", str_replace(array(' ', '.'), '', $frm['siret'])) && in_array('siret', array_keys($GLOBALS['site_parameters']['user_mandatory_fields']))) {
				$form_error_object->add('siret', $GLOBALS['STR_ERR_SIREN']);
			}
		} elseif($this_key == 'cgv_confirm' && check_if_module_active('annonces')) {
			if (empty($frm['cgv_confirm'])) {
				$form_error_object->add('cgv_confirm', $GLOBALS['STR_ERR_CGV_CONFIRM']);
			}
		} elseif($this_key == 'mot_passe_confirm' && check_if_module_active('annonces')) {
			// on doit confirmer le mot de passe
			if (vb($frm['mot_passe_confirm']) != vb($frm['mot_passe'])) {
				$form_error_object->add('mot_passe_confirm', $GLOBALS['STR_ERR_PASS_CONFIRM']);
			}
			if (empty($frm['mot_passe_confirm'])) {
				$form_error_object->add('mot_passe_confirm', $GLOBALS['STR_ERR_PASSWORD_CONFIRM']);
			}
		} elseif($this_key == 'id_categories' && check_if_module_active('annonces')) {
			if(empty($frm['id_categories']) || count($frm['id_categories']) == 0) {
				$form_error_object->add('favorite_category_error', $GLOBALS['STR_ERR_FIRST_CHOICE']);
			}
		} elseif ($this_key == 'id_cat_1' && check_if_module_active('annonces')) {
			if(empty($frm['id_cat_1'])) {
				$form_error_object->add('id_cat_1', $GLOBALS['STR_ERR_FIRST_CHOICE']);
			}
		} elseif($this_key == 'pseudo') {
			if (check_if_module_active('annonces')) {
				$add_pseudo_error = (empty($frm['pseudo']) || searchKeywordFiltersInLogin($frm['pseudo']) || String::strpos($frm['pseudo'], '@') !== false) ;
			} else {
				$add_pseudo_error = (empty($frm['pseudo']) || String::strpos($frm['pseudo'], '@') !== false);
			}
			if ($add_pseudo_error) {
				$form_error_object->add('pseudo', $GLOBALS['STR_ERR_PSEUDO']);
			}
			if ((num_rows(query("SELECT 1
				FROM peel_utilisateurs
				WHERE pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "' AND " . get_filter_site_cond('utilisateurs') . "")) > 0)) {
				$form_error_object->add('pseudo', $GLOBALS['STR_ERR_NICKNAME_STILL']);
			}
		} elseif($this_key == 'email') {
			if (check_if_module_active('annonces')) {
				$add_mail_error = (empty($frm['email']) || searchKeywordFiltersInMail($frm['email'])) ;
			} else {
				$add_mail_error = empty($frm['email']);
			}
			if ($add_mail_error) {
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL']);
			}
		} elseif ($this_key == 'code') {
			if (is_captcha_module_active()) {
				if (empty($frm['code'])) {
					// Pas de tentative de déchiffrement, on laisse le captcha
					$form_error_object->add('code', $GLOBALS['STR_EMPTY_FIELD']);
				} else {
					if (!check_captcha($frm['code'], $frm['code_id'])) {
						$form_error_object->add('code', $GLOBALS['STR_CODE_INVALID']);
						// Code mal déchiffré par l'utilisateur, on en donne un autre
						delete_captcha(vb($frm['code_id']));
						unset($frm['code']);
					}
				}
			} else {
				// Le controle du code est indiqué dans la configuration, mais le module est désactivé.
				continue;
			}
		} elseif($this_key == 'token') {
			if (!verify_token('get_user_register_form', 120, false)) {
				$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
			}
		} elseif($this_key == 'email') {
			if (!$form_error_object->has_error('email')) {
				$frm['email'] = trim($frm['email']);
				if (!EmailOK($frm['email'])) {
					$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
				} elseif ((num_rows(query("SELECT 1
					FROM peel_utilisateurs
					WHERE email = '" . nohtml_real_escape_string($frm['email']) . "' AND priv!='load' AND priv!='newsletter' AND " . get_filter_site_cond('utilisateurs'))) > 0)) {
					// Test de l'unicité de l'email, sauf pour les utilisateurs n'étant pas inscrit via le téléchargement.
					$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_STILL']);
				}
			}
		} elseif (!empty($frm['email_confirm']) && $frm['email_confirm'] != vb($frm['email'])) {
			// On envoie une demande de confirmation d'email
			$form_error_object->add('email_confirm', $GLOBALS['STR_ERR_MISMATCH_EMAIL']);
		}
		$form_error_names[$this_key] = $GLOBALS[$this_text_key];
	}
	$form_error_object->valide_form($frm, $form_error_names);

	if (!$form_error_object->count()) {
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


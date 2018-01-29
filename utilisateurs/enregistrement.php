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
// $Id: enregistrement.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_REGISTER', true);

include("../configuration.inc.php");
include($GLOBALS['dirroot']."/lib/fonctions/display_user_forms.php");

$GLOBALS['allow_fineuploader_on_page'] = true;
if (est_identifie()) {
	if (!empty($_GET['devis']) && !empty($GLOBALS['site_parameters']['create_user_when_ask_for_quote']) && check_if_module_active('devis')) {
		// Création d'une commande de devis en base de données pour un utilisateur loggué n'ayant pas le droit de voir les prix
		$output = Devis::create_devis_order($frm);
		include($GLOBALS['repertoire_modele'] . "/haut.php");
		echo $output;
		include($GLOBALS['repertoire_modele'] . "/bas.php");
		die();
	} else {
		redirect_and_die(get_url("/utilisateurs/change_params.php"));
	}
}

$GLOBALS['page_name'] = 'enregistrement';
$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_OPEN_ACCOUNT"];

// initialisation des variables
$frm = $_POST;
if(isset($frm['email'])) {
	$frm['email'] = trim($frm['email']);
}

if (!empty($_GET['mode']) && $_GET['mode'] == 'register_validation' && !empty($_GET['email']) && !empty($_GET['hash'])) {
	$sql = "SELECT * 
		FROM peel_utilisateurs
		WHERE email = '" . nohtml_real_escape_string($_GET['email'])."'";
	$query = query($sql);
	if($result = fetch_assoc($query)) {
		if ($_GET['hash'] == get_registration_validation_hash($result)) {
			// L'utilisateur existe, et on a confirmé la demande en vérifiant le hash
			// => On active le compte. Si l'utilisateur est inscrit à la newlsetter et/ou aux offres commerciales, on met à jour ces informations également
			$set_sql = "etat = 1";
			if ($result['newsletter'] == 1) {
				$set_sql .= ', newsletter_validation_date="' . get_mysql_date_from_user_input(time()) . '"';
			}
			if ($result['commercial'] == 1) {
				$set_sql .= ', commercial_validation_date="' . get_mysql_date_from_user_input(time()) . '"';
			}

			query('UPDATE peel_utilisateurs 
				SET '.$set_sql.'
				WHERE id_utilisateur = ' . intval($result['id_utilisateur']));
			// => on connecte l'utilisateur
			user_login_now($_GET['email'], '', false);
			$_SESSION['session_display_popup']['message_text'] = $GLOBALS['STR_ACCOUNT_ACTIVATED'];
			// puis on redirige vers le page de compte
			redirect_and_die(get_url('compte'));
		}
	}
}
$form_error_object = new FormError();
// Dans un premier temps on stocke dans $mandatory_fields les champs obligatoires indiqués dans $GLOBALS['site_parameters']['user_mandatory_fields'].
$mandatory_fields = array();
if(isset($GLOBALS['site_parameters']['user_mandatory_fields'])) {
	$mandatory_fields = $GLOBALS['site_parameters']['user_mandatory_fields'];
}
if (!empty($_POST['user_type'])) {
	// Chargement des champs obligatoires pour un profil d'utilisateur
	foreach(vb($GLOBALS['site_parameters']['user_'.$_POST['user_type'].'_mandatory_fields'], array()) as $key => $value) {
		$mandatory_fields[$key] = $value;
	}
	if (!empty($mandatory_fields['naissance_company']) && !empty($mandatory_fields['naissance'])) {
		unset($mandatory_fields['naissance']);
	}
}
// Dans un second temps on ajoute à cette variable les champs obligatoires qui doivent être vérifiés dans tous les cas, ou si des modules ou variables de configurations sont présents.
$mandatory_fields['mot_passe'] = sprintf($GLOBALS['STR_ERR_PASSWORD'], vn($GLOBALS['site_parameters']['password_length_required'], 8));
$mandatory_fields['email'] = 'STR_ERR_EMAIL';
if(!empty($GLOBALS['site_parameters']['add_b2b_form_inputs'])) {
	$mandatory_fields['societe'] = 'STR_ERR_SOCIETY';
	$mandatory_fields['type'] = 'STR_ERR_YOU_ARE';
	$mandatory_fields['activity'] = 'STR_ERR_ACTIVITY';
	$mandatory_fields['siret'] = 'STR_ERR_SIREN';
}
if(!empty($frm['user_type']) && $frm['user_type'] == 'company') {
	$mandatory_fields['societe'] = 'STR_ERR_SOCIETY';
	$mandatory_fields['siret'] = 'STR_ERR_SIREN';

}
if(check_if_module_active('annonces')) {
	if(vb($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) == 'checkbox') {
		$mandatory_fields['id_categories'] = 'STR_ERR_FIRST_CHOICE';
	} elseif (vb($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) == 'select') {
		$mandatory_fields['id_cat_1'] = 'STR_ERR_FIRST_CHOICE';
	}
	$mandatory_fields['cgv_confirm'] = 'STR_ERR_CGV';
	$mandatory_fields['mot_passe_confirm'] = 'STR_ERR_PASS_CONFIRM';
}
if(check_if_module_active('captcha')) {
	$mandatory_fields['code'] = 'STR_EMPTY_FIELD';
}
if(empty($GLOBALS['site_parameters']['pseudo_is_not_used']) && empty($GLOBALS['site_parameters']['pseudo_is_optionnal'])) {
	$mandatory_fields['pseudo'] = 'STR_EMPTY_FIELD';
}
		
foreach($mandatory_fields as $key => $value) {
	// Transformation des valeurs du tableau avec les variables de langue du même nom
	if (strpos($value, 'STR_') === 0 && !empty($GLOBALS[$value])) {
		$mandatory_fields[$key] = $GLOBALS[$value];
	}
}
if (check_if_module_active('socolissimo')) {
	// Securité SO Colissimo pour bien s'assurer que le process de commande sera cohérent
	unset($_SESSION['session_commande']);
}
if (!empty($frm)) {
	// D'abord on génère une erreur pour tous les champs obligatoires qui sont vides
	$form_error_object->valide_form($frm, $mandatory_fields, array('mot_passe' => vn($GLOBALS['site_parameters']['password_length_required'], 8)), array('mot_passe' => 'check_password_format', 'portable' => 'phoneOk'));
	if (!empty($frm['url'])) {
		$is_url = (StringMb::strpos($frm['url'],'://') !=false );
		if (empty($is_url)) {
			$form_error_object->add('url', $GLOBALS['STR_ERR_URL']);
		}		
	}
	// On traite ensuite les champs avec des règles plus compliquées
	if (!empty($frm['siret']) && vb($frm['pays']) == 1 && !preg_match("#([0-9]){9,14}#", str_replace(array(' ', '.'), '', $frm['siret']))) {
		// Si nous sommes en France, nous avons renseigné le numéro $GLOBALS['STR_SIREN'], cela nécessite un contrôle de la valeur rentrée par l'utilisateur
		$form_error_object->add('siret', $GLOBALS['STR_ERR_SIREN']);
	}
	if (!empty($frm['mot_passe_confirm']) && vb($frm['mot_passe_confirm']) != vb($frm['mot_passe'])) {
		$form_error_object->add('mot_passe_confirm', $GLOBALS['STR_ERR_PASS_CONFIRM']);
	}
	if(!empty($mandatory_fields['pseudo']) && !empty($frm['pseudo'])) {
		$add_pseudo_error = (StringMb::strpos($frm['pseudo'], '@') !== false);
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
	if (!empty($frm['code']) && check_if_module_active('captcha')) {
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
	if (!empty($GLOBALS['site_parameters']['user_tva_intracom_validation_on_registration_page']) && check_if_module_active('vatlayer') && !empty($frm['intracom_for_billing']) && !vatlayer_check_vat($frm['intracom_for_billing'])) {
		$form_error_object->add('intracom_for_billing', $GLOBALS['STR_MODULE_VATLAYER_ERR_INTRACOM']);
	}
	if (!$form_error_object->count()) {
		$frm['logo'] = upload('logo', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['logo']));
		if (!empty($frm['dream_societe_kbis'])) {
			$frm['dream_societe_kbis'] = upload('dream_societe_kbis', false, 'dream_societe_kbis', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['logo']));
		}
		if (!empty($frm['naissance_company'])) {
			$frm['naissance'] = $frm['naissance_company'];
		}
		// Le formulaire envoyé est apparemment OK, on le traite
		if (check_if_module_active('captcha')) {
			// Code OK on peut effacer le code
			delete_captcha($frm['code_id']);
		}
		if(is_user_bot()) {
			// Protection du formulaire contre les robots
			die();
		}
		if (empty($GLOBALS['site_parameters']['user_double_optin_registration_disable'])) {
			// Par défaut le compte n'est pas validé. L'utilisateur va recevoir un email (user_double_optin_registration) qui lui demandera d'activer son compte en cliquant sur un lien
			$frm['etat'] = 0;
		}
		// Enregistrement de l'utilisateur dans la base de données.
		$id_utilisateur = insere_utilisateur($frm, false, !empty($GLOBALS['site_parameters']['user_register_send_password_by_email']), true);
		if (empty($GLOBALS['site_parameters']['user_double_optin_registration_disable'])) {
			// on récupère les informations sur l'utilisateur qui vient d'être enregistré en BDD pour pouvoir créer le hash qui sécurisera la validation du compte
			$sql = "SELECT * 
				FROM peel_utilisateurs
				WHERE id_utilisateur = '" . nohtml_real_escape_string($id_utilisateur)."'";
			$query = query($sql);
			$result = fetch_assoc($query);

			// On créer un tableau de correspondance entre les intitulés des champs et leurs noms en BDD
			$field_name_mapping = array('email'=>$GLOBALS['STR_EMAIL'], 'pseudo'=>$GLOBALS['STR_PSEUDO'], 'civilite'=>$GLOBALS['STR_GENDER'], 'nom_famille'=>$GLOBALS['STR_NAME'], 'prenom'=>$GLOBALS['STR_FIRST_NAME'], 'societe'=>$GLOBALS['STR_SOCIETE'], 'siret'=>$GLOBALS['STR_COMPANY_IDENTIFICATION'], 'intracom_for_billing'=>$GLOBALS['STR_INTRACOM_FORM'], 'url'=>$GLOBALS['STR_WEBSITE'], 'type'=>$GLOBALS['STR_YOU_ARE'], 'activity'=>$GLOBALS['STR_ACTIVITY'], 'fonction'=>$GLOBALS['STR_FONCTION'], 'naissance'=>$GLOBALS['STR_NAISSANCE'], 'telephone'=>$GLOBALS['STR_TELEPHONE'], 'portable'=>$GLOBALS['STR_PORTABLE'], 'fax'=>$GLOBALS['STR_FAX'], 'adresse'=>$GLOBALS['STR_ADDRESS'], 'code_postal'=>$GLOBALS['STR_ZIP'], 'ville'=>$GLOBALS['STR_TOWN'], 'pays'=>$GLOBALS['STR_COUNTRY'], 'id_cat_1'=>$GLOBALS['STR_FIRST_CHOICE'], 'id_cat_2'=>$GLOBALS['STR_SECOND_CHOICE'], 'id_cat_3'=>$GLOBALS['STR_THIRD_CHOICE'], 'origin'=>$GLOBALS['STR_USER_ORIGIN'], 'logo'=>$GLOBALS['STR_LOGO'], 'newsletter'=>$GLOBALS['STR_NEWSLETTER_YES'], 'commercial'=>$GLOBALS['STR_COMMERCIAL_YES']); 

			// On créer une variable qui contiendra les informations sur le compte à valider et qui servira dans l'email de validation
			$custom_template_tags['FIELDS'] = '';

			foreach ($field_name_mapping as $field_name => $value) {
				if (!empty($frm[$field_name]) && $frm[$field_name] != "0000-00-00") {
					if ($field_name == 'naissance') {
						$frm[$field_name] = get_formatted_date($frm[$field_name]);	
					} elseif ($field_name == 'origin') {
						$frm[$field_name] = $GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $frm[$field_name]];
					} elseif ($field_name == 'pays') {
						$frm[$field_name] = get_country_name($frm[$field_name]);
					} elseif (($field_name == 'commercial' || $field_name == 'newsletter') && $frm[$field_name] == 1) {
						$frm[$field_name] = $GLOBALS['STR_YES'];
					}
					$custom_template_tags['FIELDS'] .= $value.': '.$frm[$field_name].'<br />';
				}
			}
			// Dans le lien on ajoute un hash pour s'assurer que c'est bien l'utilisateur propriétaire du compte qui fait la validation.
			$custom_template_tags['VALIDATION_LINK'] = $GLOBALS['wwwroot'].'/utilisateurs/enregistrement.php?mode=register_validation&email='.$frm['email'].'&hash='.get_registration_validation_hash($result);
			// Envoi de l'email à l'utilisateur l'invitant à valider l'ouverture de son compte. Cet email reprend les informations du formulaire, notamment l'inscription à la newsletter et aux offres commerciales.
			send_email($frm['email'],'','','user_double_optin_registration', $custom_template_tags);
		} else {
			// Pas de validation de compte, donc on connecte l'utilisateur à son compte immédiatement après l'enregistrement
			$utilisateur = user_login_now($frm['email'], $frm['mot_passe']);
		}

		if(!empty($_GET['devis']) && !empty($GLOBALS['site_parameters']['create_user_when_ask_for_quote']) && check_if_module_active('devis')) {
			$output = Devis::create_devis_order($frm);
		} else {
			$output = get_user_register_success($frm);
		}
		if (!empty($GLOBALS['site_parameters']['redirect_user_after_register_by_priv'][vb($utilisateur['priv'])])) {
			// Redirection vers une url administrable après la connexion réussie d'un utilisateur.
			redirect_and_die($GLOBALS['site_parameters']['redirect_user_after_register_by_priv'][vb($utilisateur['priv'])]);
		} elseif ($_SESSION['session_caddie']->count_products() > 0) {
			if (empty($_SESSION['session_caddie']->zoneId) || empty($_SESSION['session_caddie']->typeId)) {
				include($GLOBALS['repertoire_modele'] . "/haut.php");
				echo $output;
				include($GLOBALS['repertoire_modele'] . "/bas.php");
			} else {
				if (check_if_module_active('socolissimo')) {
					// Pour SO Colissimo, si on s'inscrit "en cours de commande", on force le passage vers le caddie de nouveau, sinon le passage par l'interface de SO Colissimo serait zappée ---> Commande incomplète en BDD.
					redirect_and_die(get_url('caddie_affichage'));
				} else {
					redirect_and_die(get_url('achat_maintenant'));
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

// Si on a tenté sans succès de se connecter via un site extérieur et que la connexion a réussi
// alors on préremplit les champs d'inscription avec les données du site extérieur
$hook_result = call_module_hook('account_create', $frm, 'array');
$frm = array_merge_recursive_distinct($frm, vb($hook_result, array()));

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

echo get_user_register_form($frm, $form_error_object, !empty($_GET['devis']) && !empty($GLOBALS['site_parameters']['create_user_when_ask_for_quote']) && check_if_module_active('devis'), false, null, $mandatory_fields);

include($GLOBALS['repertoire_modele'] . "/bas.php");


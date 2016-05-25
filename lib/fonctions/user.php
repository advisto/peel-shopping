<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: user.php 49979 2016-05-23 12:29:53Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Retourne true si l'utilisateur est identifié
 *
 * @return boolean
 */
function est_identifie()
{
	return isset($_SESSION) && isset($_SESSION['session_utilisateur']) && !empty($_SESSION['session_utilisateur']['id_utilisateur']);
}

/**
 * Renvoie true si l'utilisateur de la session a le privilège $requested_priv ou un droit supérieur
 * Des droits peuvent être combinés :
 * - OU : de type droit1,droit2 : l'un des deux droits suffit
 * - ET : de type droit1+droit2 : les deux droits sont nécessaires
 * La virgule est prioritaire par rapport au + : droit1+droit2,droit3 : droits 1 et 2 nécessaire, ou bien droit3 seulement suffit
 * Si on demande des droits de type admin*, n'importe quel admin, admin_products, etc. a le droit d'accès
 * Si on demande un droit de type admin_xxx, alors un utilisateur de type "admin" a le droit également d'accéder (administrateur superglobal)
 *
 * @param string $requested_priv
 * @param boolean $demo_allowed
 * @param boolean $site_configuration_modification
 * @param integer $user_id
 * @return boolean
 */
function a_priv($requested_priv, $demo_allowed = false, $site_configuration_modification = false, $user_id = null)
{
	if(empty($user_id)) {
		if (isset($_SESSION) && isset($_SESSION['session_utilisateur']) && !empty($_SESSION['session_utilisateur']['priv'])) {
			$user_priv = $_SESSION['session_utilisateur']['priv'];
		}
	} else {
		$user_infos = get_user_information($user_id);
		$user_priv = vb($user_infos['priv']);
	}
	if (!empty($user_priv) && is_string($user_priv)) {
		 if($site_configuration_modification && !empty($GLOBALS['site_parameters']['admin_configuration_only_by_user_ids']) && !in_array(vn($_SESSION['session_utilisateur']['id_utilisateur']), $GLOBALS['site_parameters']['admin_configuration_only_by_user_ids'])) {
			// Des droits de modification de la configuration du site sont nécessaires et définis par une variable de configuration qui ne comprends pas dans sa liste l'id de l'utilisateur
			return false;
		 }
		 if (strpos($requested_priv, ',') !== false) {
			// On autorise plusieurs droits différents => récursif
			$requested_priv_array = explode(',', $requested_priv);
			$allowed = false;
			foreach($requested_priv_array as $this_requested_priv) {
				if (a_priv(trim($this_requested_priv), $demo_allowed)) {
					$allowed = true;
				}
			}
			return $allowed;
		} elseif (strpos($requested_priv, '+') !== false) {
			// On demande plusieurs droits => récursif
			$requested_priv_array = explode('+', $requested_priv);
			$not_allowed = false;
			foreach($requested_priv_array as $this_requested_priv) {
				if (!a_priv(trim($this_requested_priv), $demo_allowed)) {
					$not_allowed = true;
				}
			}
			return !$not_allowed;
		} else {
			$user_priv_array = explode('+', $user_priv);
			foreach($user_priv_array as $this_user_priv) {
				if (substr($requested_priv, 0, 5) == 'admin' && $this_user_priv == 'admin') {
					// admin est un administrateur global qui a tous les droits de type admin*
					return true;
				} elseif ($demo_allowed && $this_user_priv == 'demo') {
					// On a les droits demo qui sont autorisés
					return true;
				}
				if (strpos($requested_priv, '*') !== false) {
					// Pour admin*, tout droit du type admin ou admin_.... est autorisé
					if (strpos($requested_priv, substr($this_user_priv, 0, strpos($requested_priv, '*'))) === 0) {
						return true;
					}
				} elseif ($this_user_priv == $requested_priv) {
					return true;
				}
			}
			return false;
		}
	} else {
		return null;
	}
}

/**
 * Ajout d'un utilisateur
 *
 * @param array $frm Array with all fields data Array with all fields data
 * @param boolean $password_already_encoded
 * @param boolean $send_user_confirmation
 * @param boolean $warn_admin_if_template_active
 * @param boolean $skip_existing_account_tests
 * @param boolean $create_password_on_behalf_of_user
 * @return integer New user id
 */
function insere_utilisateur(&$frm, $password_already_encoded = false, $send_user_confirmation = false, $warn_admin_if_template_active = true, $skip_existing_account_tests = false, $create_password_on_behalf_of_user = false)
{
	$sql_condition_array = array();
	// Si un compte a un privilège ci-dessous, et qu'un utilisateur veut créer un nouveau compte avec le même email, alors il est remplacé automatiquement par le nouveau compte
	// Sinon, tout autre compte empêche la création d'un compte avec le même email, et la fonction renvoie l'id du compte déjà existant.
	$user_low_priviledges_array = vb($GLOBALS['site_parameters']['user_low_priviledges_array'], array('load', 'newsletter'));
	// L'inscription de certains utilisateurs  nécessite d'être validée manuellement par l'administrateur. Le compte est désactivé en attendant la validation manuelle du compte.
	$manual_validation_registration = vb($GLOBALS['site_parameters']['manual_validation_registration'], array());

	if (!empty($frm['priv'])) {
		if(is_array($frm['priv'])) {
			$frm['priv'] = implode('+', $frm['priv']);
		}
	} else {
		if(!empty($GLOBALS['site_parameters']['user_creation_default_profile'])) {
			// ce paramètre permet de définir un privilège utilisateur dès l'inscription en front-office, ou lorsqu'aucun privilège n'est demandé explicitement via l'administration
			$allowed_profil = array();
			$sql = "SELECT priv
				FROM peel_profil
				WHERE priv NOT LIKE 'admin%' AND " . get_filter_site_cond('profil') . "";
			$query = query($sql);
			while($result = fetch_assoc($query)) {
				// Création du tableau des privilèges provenant de la BDD, sans les privilèges admin pour éviter la création non controllée d'administrateur sur la boutique
				$allowed_profil[] = $result['priv'];
			}
			if (in_array($GLOBALS['site_parameters']['user_creation_default_profile'], $allowed_profil)) {
				$frm['priv'] = $GLOBALS['site_parameters']['user_creation_default_profile'];
			} else {
				// privilège par défaut
				$frm['priv'] = 'util';
			}
		} else {
			// privilège par défaut
			$frm['priv'] = 'util';
		}
	}
	if (!empty($frm['email'])) {
		$sql_condition_array[] = "email='" . word_real_escape_string(vb($frm['email'])) . "'";
	}
	if (empty($GLOBALS['site_parameters']['pseudo_is_not_used']) && !empty($frm['pseudo'])) {
		$sql_condition_array[] = "pseudo='" . nohtml_real_escape_string(vb($frm['pseudo'])) . "'";
	}
	if (!empty($sql_condition_array)) {
		if (!empty($frm['priv']) && !in_array($frm['priv'], $user_low_priviledges_array)) {
			// On veut créer un vrai compte
			// Si cet utilisateur est déjà inscrit pour un téléchargement ou une newsletter, il faut supprimer l'enregistrement correspondant à son email pour permettre la création du nouveau compte
			$sql = "SELECT id_utilisateur, priv
				FROM peel_utilisateurs
				WHERE (" . implode(' OR ', $sql_condition_array).") AND priv IN ('" . implode("', '", real_escape_string($user_low_priviledges_array)) . "') AND " . get_filter_site_cond('utilisateurs') . '';
			$query = query($sql);
			if ($user_already_exists_infos = fetch_assoc($query)) {
				query("DELETE FROM peel_utilisateurs
					WHERE id_utilisateur='" . intval($user_already_exists_infos['id_utilisateur']) . "' AND " . get_filter_site_cond('utilisateurs') . "");
			}
		}
		if (!$skip_existing_account_tests) {
			// On veut créer un compte normal, ou même newsletter ou load : n'importe quel type de compte
			// On teste si l'utilisateur existe déjà (si ses droits étaient inférieurs, le compte existant déjà vient d'être supprimé ci-dessus)
			$sql = "SELECT id_utilisateur
				FROM peel_utilisateurs
				WHERE (" . implode(' OR ', $sql_condition_array).") AND " . get_filter_site_cond('utilisateurs') . "";
			$query = query($sql);
			if ($user_already_exists_infos = fetch_assoc($query)) {
				// L'utilisateur existe déjà, on donne son id
				return $user_already_exists_infos['id_utilisateur'];
			}
		}
	}
	
	// Préparation de la création du compte utilisateur
	if (!empty($frm['mot_passe']) && $password_already_encoded) {
		$password_hash = trim($frm['mot_passe']);
	} elseif (!empty($frm['mot_passe'])) {
		$password_hash = get_user_password_hash(trim($frm['mot_passe']));
	} elseif(empty($frm['mot_passe']) && (!empty($GLOBALS['site_parameters']['register_during_order_process']) || !empty($create_password_on_behalf_of_user))) {
		// Création d'un utilisateur lors du process de commande. Le mot de passe est envoyé à l'utilisateur^en fin de fonction
		$frm['mot_passe'] = MDP();
		$password_hash = get_user_password_hash($frm['mot_passe']);
	} else {
		// On crée un utilisateur qui ne pourra pas se connecter avant de demander un mot de passe
		$password_hash = get_user_password_hash(MDP());
	}
	foreach(explode('+', $frm['priv']) as $this_priv) {
		if (in_array($this_priv, $manual_validation_registration)) {
			// L'état d'activation par défaut lors de l'inscription d'un utilisateur dépend du statut. Pour les utilisateurs revendeur en attente, ou les affiliés en attente on ne souhaite pas qu'ils puissent se connecter à leur compte pendant le délai de validation. L'utilisateur est informé de ce fonctionnement via l'email qui a pour code technique "send_mail_for_account_creation_" . $priv (cf. à la fin de cette fonction) 
			$frm['etat'] = 0;
		}
	}
	if (!isset($frm['etat'])) {
		// Par sécurité on fait ce test. On ne peut pas laisser le champs etat sans valeur.
		$frm['etat'] = 1;
	}
	if(defined('IN_REGISTER')) {
		$form_usage = 'user';
	} elseif(check_if_module_active('reseller') && defined('IN_RETAILER')) {
		$form_usage = 'reseller';
	}
	if(!empty($form_usage)) {
		handle_specific_fields($frm, $form_usage);
	}
	
	if(empty($frm['lang'])) {
		$frm['lang'] = $_SESSION['session_langue'];
	}
	if(!isset($frm['site_id'])) {
		$frm['site_id'] = vb($GLOBALS['site_parameters']['user_create_default_site_id'], $GLOBALS['site_id']);
	}
	if(!defined('PEEL_ADMIN')) {
		if(!empty($GLOBALS['site_parameters']['devise_force_user_choices']) && check_if_module_active('devises')) {
			$frm['devise'] = $_SESSION['session_devise']['code'];
		}
		if(!empty($GLOBALS['site_parameters']['site_country_forced_by_user']) && !empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			if(in_array(strval(vb($frm['pays'])), $GLOBALS['site_parameters']['site_country_allowed_array'])) {
				$frm['site_country'] = $frm['pays'];
			} else {
				$frm['site_country'] = $_SESSION['session_site_country'];
			}
		}
	}
	$frm['date_insert'] = vb($frm['date_insert'], date('Y-m-d H:i:s', time()));
	$frm['date_update'] = vb($frm['date_update'], date('Y-m-d H:i:s', time()));
	$frm['email'] = trim($frm['email']);
	$frm['format'] = vb($from['format'], vb($GLOBALS['site_parameters']['email_sending_format_default'], 'html'));
	$frm['seg_who'] = vb($frm['seg_who'], 'no_info');
	$frm['seg_want'] = vb($frm['seg_want'], 'no_info');
	$frm['seg_think'] = vb($frm['seg_think'], 'no_info');
	$frm['seg_followed'] = vb($frm['seg_followed'], 'no_info');
	$frm['seg_buy'] = vb($frm['seg_buy'], 'no_info');
	$frm['address_bill_default'] = 'original_address';
	$frm['address_ship_default'] = 'original_address';
	if(!empty($_SESSION['session_parameters'])) {
		// Paramètres divers tels que des informations sur les réseaux sociaux connectés pour lesquels on va garder souvenir de l'id
		$frm['parameters'] = serialize($_SESSION['session_parameters']);
	}
	$frm = call_module_hook('user_create_or_update_pre', $frm, 'array', true);
		
	$user_table_fields_types = get_table_field_types('peel_utilisateurs');
	foreach($user_table_fields_types as $this_field => $this_type) {
		if(isset($frm['specific_field_sql_set'][$this_field])) {
			$sql_fields_array[$this_field] = $frm['specific_field_sql_set'][$this_field];
			continue;
		} elseif($this_field == 'mot_passe' && isset($password_hash)) {
			// $frm['mot_passe'] contient le mot de passe en clair, pour éventuel envoi par email
			// Ici il faut utiliser $password_hash
			$sql_fields_array[$this_field] = word_real_escape_string($this_field) . "='" . nohtml_real_escape_string($password_hash) . "'";
			continue;
		} elseif(!isset($frm[$this_field])) {
			continue;
		}
		if(is_array($frm[$this_field])) {
			$frm[$this_field] = implode(',', $frm[$this_field]);
		}
		if(in_array($this_field, array('intracom_for_billing'))) {
			$frm[$this_field] = strtoupper($frm[$this_field]);
		} elseif(String::strpos($this_type, 'int(')!==false) {
			$frm[$this_field] = intval($frm[$this_field]);
		} elseif(String::strpos($this_type, 'float(')!==false) {
			$frm[$this_field] = floatval($frm[$this_field]);
		} elseif($this_type == 'date' || $this_type == 'datetime') {
			$frm[$this_field] = get_mysql_date_from_user_input($frm[$this_field]);
		}
		if(in_array($this_field, array('description', 'parameters'))) {
			$sql_fields_array[$this_field] = word_real_escape_string($this_field) . "='" . real_escape_string($frm[$this_field]) . "'";
		} else {
			$sql_fields_array[$this_field] = word_real_escape_string($this_field) . "='" . nohtml_real_escape_string($frm[$this_field]) . "'";
		}
	}
	$sql = "INSERT INTO peel_utilisateurs SET
			" . implode(',', $sql_fields_array) . "";
	query($sql);
	
	$frm['id'] = insert_id();
	$code_client = "CLT" . date("Y") . $frm['id'];

	query("UPDATE peel_utilisateurs
		SET code_client = '" . nohtml_real_escape_string($code_client) . "'
		WHERE id_utilisateur = '" . intval($frm['id']) . "' AND " . get_filter_site_cond('utilisateurs') . "");

	if ($send_user_confirmation) {
		// Envoi de l'email qui contient le mot de passe qui a été demandé ou créé automatiquement suivant que $frm['mot_passe'] était vide ou déjà rempli
		send_mail_for_account_creation($frm['email'], $frm['mot_passe'], vb($frm['priv']));
	}
	if ($warn_admin_if_template_active) {
		// Prévenir l'administrateur d'une création d'utilisateur
		$custom_template_tags['PRIV'] = get_profil(vb($frm['priv']), 'name');
		$custom_template_tags['CIVILITE'] = vb($frm['civilite']);
		$custom_template_tags['PRENOM'] = $frm['prenom'];
		$custom_template_tags['NOM_FAMILLE'] = $frm['nom_famille'];
		$custom_template_tags['EMAIL'] = $frm['email'];
		$custom_template_tags['DATE'] = get_formatted_date(time(), 'short', 'long');
		$custom_template_tags['SOCIETE'] = $frm['societe'];
		$custom_template_tags['TELEPHONE'] = $frm['telephone'];
		$custom_template_tags['ADMIN_URL'] = $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $frm['id'] . '&start=0';

		if ($frm['priv'] == 'stop') {
			$template_technical_code = 'warn_admin_reve_subscription';
		} else {
			$template_technical_code = 'warn_admin_user_subscription';
		}
		send_email($GLOBALS['support_sav_client'], '', '', $template_technical_code, $custom_template_tags, null, $GLOBALS['support_sav_client']);
	}
	call_module_hook('user_create', $frm);
	return $frm['id'];
}

/**
 * Mise à jour d'un enregistrement d'utilisateur
 *
 * @param array $frm Array with all fields data
 * @param boolean $update_current_session
 * @return
 */
function maj_utilisateur(&$frm, $update_current_session = false)
{
	if (empty($frm['id_utilisateur'])) {
		return false;
	}
	if (isset($frm['priv']) && is_array($frm['priv'])) {
		$frm['priv'] = implode('+', $frm['priv']);
	}
	if(!$update_current_session && !a_priv('admin', false, true)) {
		if(a_priv('admin*', false, false, $frm['id_utilisateur'])) {
			// L'utilisateur qu'on veut modifier est un administrateur et l'utilisateur loggué n'a pas le droit de le faire
			return false;
		} elseif(!empty($frm['priv']) && String::strpos($frm['priv'], 'admin') === 0) {
			unset($frm['priv']);
		}
	}
	if(check_if_module_active('reseller') && vb($frm['priv']) == 'reve') {
		$form_usage = 'reseller';
	}else{
		$form_usage = 'user';
	}
	handle_specific_fields($frm, $form_usage);

	if(empty($frm['lang'])) {
		$frm['lang'] = $_SESSION['session_langue'];
	}
	if(!isset($frm['site_id'])) {
		// Si site_id n'est pas défini dans le formulaire, le site_id défini pour l'utilisateur est celui du site en cours de consultation, sauf pour les administrateurs multisite.
		$this_user = get_user_information($frm['id_utilisateur']);
		if (empty($this_user) || $this_user['site_id']>0) {
			// si l'utilisateur est multisite, le champ site_id resera à 0 avec la fonction vn()
			$frm['site_id'] = $GLOBALS['site_id'];
		}
	}
	$frm['site_id'] = get_site_id_sql_set_value(vn($frm['site_id']));
	$frm['format'] = vb($from['format'], vb($GLOBALS['site_parameters']['email_sending_format_default'], 'html'));
	$frm['date_update'] = date('Y-m-d H:i:s', time());
	if(!empty($frm['email'])) {
		// Réinitilisation des informations de bounces d'email
		$frm['email_bounce'] = '';
		$frm['email'] = trim($frm['email']);
	}
	if(check_if_module_active('annonces') && !defined('IN_PEEL_ADMIN')) {
		// MAJ du pseudo interdite pour les sites d'annonces en front office, pour éviter problèmes de traçabilité d'utilisateurs
		unset($frm['pseudo']);
	}
	if(check_if_module_active('maps')) {
		// Initialisation des données de hash de l'adresse pour savoir qu'il faudra géocoder l'adresse à nouveau
		$frm['address_hash'] = '';
	}
	$frm = call_module_hook('user_create_or_update_pre', $frm, 'array', true);

	$user_table_fields_types = get_table_field_types('peel_utilisateurs');
	foreach($user_table_fields_types as $this_field => $this_type) {
		if(isset($frm['specific_field_sql_set'][$this_field])) {
			$sql_fields_array[$this_field] = $frm['specific_field_sql_set'][$this_field];
			continue;
		} elseif(!isset($frm[$this_field])) {
			if ($this_field == 'newsletter' || $this_field == 'commercial') {
				// Ce sont les deux champs en checkbox du formulaire. Si pas coché, alors il faut mettre 0 en base de données.
				$frm[$this_field] = 0;
			} else {
				continue;
			}
		}
		if(is_array($frm[$this_field])) {
			$frm[$this_field] = implode(',', $frm[$this_field]);
		}
		if(in_array($this_field, array('intracom_for_billing'))) {
			$frm[$this_field] = strtoupper($frm[$this_field]);
		} elseif(String::strpos($this_type, 'int(')!==false) {
			$frm[$this_field] = intval($frm[$this_field]);
		} elseif(String::strpos($this_type, 'float(')!==false) {
			$frm[$this_field] = floatval($frm[$this_field]);
		} elseif($this_type == 'date' || $this_type == 'datetime') {
			$frm[$this_field] = get_mysql_date_from_user_input($frm[$this_field]);
		}
		if(in_array($this_field, array('description', 'parameters'))) {
			$sql_fields_array[$this_field] = word_real_escape_string($this_field) . "='" . real_escape_string($frm[$this_field])."'";
		} else {
			$sql_fields_array[$this_field] = word_real_escape_string($this_field) . "='" . nohtml_real_escape_string($frm[$this_field])."'";
		}
	}
	$sql = "UPDATE peel_utilisateurs SET
			" . implode(',', $sql_fields_array) . "
		WHERE id_utilisateur = '" . intval($frm['id_utilisateur']) . "'";
	query($sql);
		
	if ($update_current_session) {
		// Mise à jour de la session en cours
		$sql = "SELECT *
			FROM peel_utilisateurs
			WHERE id_utilisateur = '" . intval($frm['id_utilisateur']) . "' AND " . get_filter_site_cond('utilisateurs') . "";
		$qid = query($sql);
		if($user_infos = fetch_assoc($qid)) {
			$_SESSION['session_utilisateur']['pays'] = $user_infos['pays'];
			$_SESSION['session_utilisateur']['civilite'] = vb($user_infos['civilite']);
			$_SESSION['session_utilisateur']['prenom'] = $user_infos['prenom'];
			$_SESSION['session_utilisateur']['pseudo'] = $user_infos['pseudo'];
			$_SESSION['session_utilisateur']['nom_famille'] = $user_infos['nom_famille'];
			$_SESSION['session_utilisateur']['societe'] = $user_infos['societe'];
			$_SESSION['session_utilisateur']['intracom_for_billing'] = String::strtoupper(vb($user_infos['intracom_for_billing']));
			$_SESSION['session_utilisateur']['telephone'] = $user_infos['telephone'];
			$_SESSION['session_utilisateur']['fax'] = $user_infos['fax'];
			$_SESSION['session_utilisateur']['portable'] = $user_infos['portable'];
			$_SESSION['session_utilisateur']['adresse'] = $user_infos['adresse'];
			$_SESSION['session_utilisateur']['code_postal'] = $user_infos['code_postal'];
			$_SESSION['session_utilisateur']['ville'] = $user_infos['ville'];
			$_SESSION['session_utilisateur']['newsletter'] = intval(vn($user_infos['newsletter']));
			$_SESSION['session_utilisateur']['commercial'] = intval(vn($user_infos['commercial']));
			$_SESSION['session_utilisateur']['format'] = vb($GLOBALS['site_parameters']['email_sending_format_default'], 'html');
		}
	}
	if (!empty($frm['email'])) {
		if (check_if_module_active('bounces')) {
			include_once($GLOBALS['dirroot'] . "/modules/bounces/bounces_driver.php");
			resolve_bounce($frm['id_utilisateur'], $frm['email']);
		}
	}
	if (isset($frm['comments'])) {
		create_or_update_comments($frm);
	}
	call_module_hook('user_update', $frm);
	if (affected_rows()) {
		return true;
	} else {
		return false;
	}
}

/**
 * efface_utilisateur()
 *
 * @param integer $id_utilisateur
 * @return
 */
function efface_utilisateur($id_utilisateur)
{
	query("DELETE FROM peel_utilisateurs
		WHERE id_utilisateur = '" . intval($id_utilisateur) . "' AND " . get_filter_site_cond('utilisateurs') . "");
	call_module_hook('user_delete', array('id' => $id_utilisateur));
}

/**
 * Initialise le renouvellement de mot de passe
 *
 * @param string $email
 * @return boolean Success
 */
function initialise_mot_passe($email)
{
	// Chargement des infos de l'utilisateur
	$qid = query("SELECT id_utilisateur, mot_passe
		FROM peel_utilisateurs
		WHERE email='" . nohtml_real_escape_string($email) . "' AND " . get_filter_site_cond('utilisateurs') . "");

	if ($utilisateur = fetch_assoc($qid)) {
		$timestamp = time();
		$hash = sha256($email . $timestamp . $utilisateur['id_utilisateur'] . $utilisateur['mot_passe']);

		$custom_template_tags['LINK'] = get_url('/utilisateurs/oubli_mot_passe.php', array('hash' => $hash, 'time' => $timestamp, 'email' => $email));
		$custom_template_tags['SITE'] = $GLOBALS['site'];
		$result = send_email($email, '', '', 'initialise_mot_passe', $custom_template_tags, null, $GLOBALS['support_sav_client']);
	} else {
		$result = null;
	}
	return $result;
}

/**
 * Enregistre le nouveau mot de passe
 *
 * @param integer $user_id
 * @param string $nouveau_mot_passe
 * @return boolean Success
 */
function maj_mot_passe($user_id, $nouveau_mot_passe)
{
	query("UPDATE peel_utilisateurs
		SET mot_passe = '" . get_user_password_hash($nouveau_mot_passe) . "', date_update='" . date('Y-m-d H:i:s', time()) . "'
		WHERE id_utilisateur = '" . intval($user_id) . "' AND " . get_filter_site_cond('utilisateurs') . "");
	if (affected_rows()) {
		return true;
	} else {
		return false;
	}
}

/**
 * user_login_now()
 *
 * @param string $email_or_pseudo
 * @param string $mot_passe
 * @param boolean $check_password
 * @param boolean $password_given_as_first_password_hash
 * @param integer $password_length_if_given_as_first_password_hash
 * @param array $check_parameters_array
 * @return
 */
function user_login_now($email_or_pseudo, $mot_passe, $check_password = true, $password_given_as_first_password_hash = false, $password_length_if_given_as_first_password_hash = null, $check_parameters_array = null)
{
	if (empty($_SESSION['session_login_tried'])) {
		$_SESSION['session_login_tried'] = 0;
	}
	$_SESSION['session_login_tried']++;
	if ($_SESSION['session_login_tried'] < 30) {
		// Limitation à 30 tentatives de login dans la même session
		$utilisateur = verifier_authentification(trim($email_or_pseudo), trim($mot_passe), null, $check_password, $password_given_as_first_password_hash, $password_length_if_given_as_first_password_hash, $check_parameters_array);
		if ($utilisateur) {
			// On force la prochaine mise à jour des informations du compte utilisateur
			unset($_SESSION['session_update_account']);
			
			$_SESSION['session_utilisateur'] = $utilisateur;
			// si $_SESSION['session_utilisateur']['parameters'] n'est pas vide, il a déjà été désérialisé dans verifier_authentification() ci-dessus
			$_SESSION['session_ip'] = vb($_SERVER['REMOTE_ADDR']);
			$_SESSION['session_url'] = $_SERVER['HTTP_HOST'];
			if (!empty($_SESSION['session_caddie'])) {
				$_SESSION['session_caddie']->update(get_current_user_promotion_percentage());
			}
			if (!empty($_SESSION['session_utilisateur']['pays'])) {
				// Enregistrer la zone d'expedition de l'utilisateur
				$sqlUserZone = 'SELECT zone
					FROM peel_pays
					WHERE id="' . intval($_SESSION['session_utilisateur']['pays']) . '" AND ' . get_filter_site_cond('pays') . '
					LIMIT 1';
				$resUserZone = query($sqlUserZone);
				if ($Zone = fetch_assoc($resUserZone)) {
					$_SESSION['session_utilisateur']['zoneId'] = $Zone['zone'];
				}
			}
			if(a_priv('admin') && count(get_all_sites_name_array(false, false, true)) == 1) {
				// Si l'utilisateur est un administrateur et qu'un seul site est configuré, on lui attribue les droits pour toutes les sites (0 et 1 dans ce cas)
				$_SESSION['session_utilisateur']['site_id'] = 0;
			}
			if (!empty($_SESSION['session_utilisateur']['devise'])) {
				set_current_devise($_SESSION['session_utilisateur']['devise']);
			}
			if (!empty($_SESSION['session_utilisateur']['site_country'])) {
				// Définition de session_site_country à partir de l'information de la table utilisateurs, et non pas à partir de la géolocalisation IP
				$_SESSION['session_site_country'] = intval($_SESSION['session_utilisateur']['site_country']);
			}
			// On enregistre la connexion de l'utilisateur
			if (!empty($_SESSION['session_utilisateur']['pseudo'])) {
				$user_pseudo = $_SESSION['session_utilisateur']['pseudo'];
			} else {
				$user_pseudo = $_SESSION['session_utilisateur']['email'];
			}
			if (a_priv('admin*')) {
				// On met à jour les appels de clients dont l'heure de cloture n'est pas précisée
				// NB : On pourrait faire cela par cron plutôt qu'ici, mais sans cron c'est plus facilement gérable
				updateTelContactNotClosed();
				// On avertit le contact boutique du login d'un administrateur
				$custom_template_tags['USER'] = $user_pseudo;
				$custom_template_tags['REVERSE_DNS'] = gethostbyaddr(vb($_SERVER['REMOTE_ADDR']));
				send_email($GLOBALS['support_sav_client'], '', '', 'admin_login', $custom_template_tags, null, $GLOBALS['support'], true, false, true, $GLOBALS['support']);
			}
			
			query('INSERT INTO peel_utilisateur_connexions (user_id, user_login, user_ip, date, site_id)
				VALUES (' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . ', "' . nohtml_real_escape_string($user_pseudo) . '", "' . ip2long(ipget()) . '", "' . date('Y-m-d H:i:s', time()) . '",  "' . nohtml_real_escape_string(get_site_id_sql_set_value($GLOBALS['site_id'])) . '")');

			$_SESSION['session_login_tried'] = 0;
			
			$hook_result = call_module_hook('user_login_now', array(), 'array');
			$_SESSION['session_utilisateur'] = array_merge_recursive_distinct($_SESSION['session_utilisateur'], vb($hook_result, array()));
		}
		if (!empty($GLOBALS['site_parameters']['redirect_user_after_login_by_priv'][$utilisateur['priv']])) {
			// Redirection vers une url administrable après la connexion réussie d'un utilisateur.
			redirect_and_die($GLOBALS['site_parameters']['redirect_user_after_login_by_priv'][$utilisateur['priv']]);
		} else {
			return $utilisateur;
		}
	} else {
		return null;
	}
}

/**
 * On renvoie un tableau contenant les informations utilisateur si l'email et le mot de passe sont bons.
 * Sinon on renvoie false.
 *
 * @param string $email_or_pseudo
 * @param string $mot_passe
 * @param integer $user_id
 * @param boolean $check_password
 * @param boolean $password_given_as_first_password_hash
 * @param integer $password_length_if_given_as_first_password_hash
 * @param array $check_parameters_array
 * @return
 */
function verifier_authentification($email_or_pseudo, $mot_passe, $user_id = null, $check_password = true, $password_given_as_first_password_hash = false, $password_length_if_given_as_first_password_hash = null, $check_parameters_array = null)
{
	$get_table_field_names = get_table_field_names('peel_configuration');
	if (!in_array('site_id', $get_table_field_names)) {
		$GLOBALS['site_parameters']['multisite_disable'] = true;
		$skip_state_test = true;
	}
	
	$sql = "SELECT *
		FROM peel_utilisateurs
		WHERE " . (empty($skip_state_test)? "etat=1 AND":"") . " " . get_filter_site_cond('utilisateurs') . " AND priv NOT IN ('".implode("','", $GLOBALS['disable_login_by_privilege'])."')";
	if (!empty($email_or_pseudo)) {
		$sql .= " AND (email='" . nohtml_real_escape_string($email_or_pseudo) . "' OR pseudo ='" . nohtml_real_escape_string($email_or_pseudo) . "')";
	} elseif (!empty($user_id)) {
		$sql .= " AND id_utilisateur='" . intval($user_id) . "'";
	} elseif(!empty($check_parameters_array)) {
		foreach($check_parameters_array as $this_key => $this_value) {
			$sql .= " AND parameters LIKE '%\"" . nohtml_real_escape_string($this_key) . "\"%' AND parameters LIKE '%\"" . nohtml_real_escape_string($this_value) . "\"%'";
		}
	} else {
		// Pas de recherche sur login ou id ou paramètre autre => pas de login autorisé
		return false;
	}
	$sql .= " LIMIT 1";
	$qid = query($sql);
	if ($user_infos = fetch_assoc($qid)) {
		if(!empty($user_infos['parameters'])) {
			$user_infos['parameters'] = @unserialize($user_infos['parameters']);
		} else {
			$user_infos['parameters'] = array();
		}
		$password_check = (!$check_password || get_user_password_hash($mot_passe, $user_infos['mot_passe'], $password_given_as_first_password_hash, $password_length_if_given_as_first_password_hash));
		if(empty($check_parameters_array)) {
			$parameters_check = true;
		} elseif(empty($user_infos['parameters'])) {
			$parameters_check = false;
		} else {
			// On vérifie des paramètres du type twitter_id => user_id;
			$parameters_check = true;
			foreach($check_parameters_array as $this_key => $this_value) {
				if($parameters[$this_key] != $this_value) {
					$parameters_check = false;
					break;
				}
			}
		}
		if ($password_check && $parameters_check) {
			if(!$check_password && empty($GLOBALS['site_parameters']['login_allow_low_security_admin']) && String::strpos($user_infos['priv'], 'admin') === 0) {
				// Utilisateur avec droits d'administration, loggué via processus simplifié => on désactive les droits d'administration
				$user_infos['priv'] = 'util';
			}
			return $user_infos;
		} else {
			// Mot de passe ou paramètre erroné => login refusé
			return false;
		}
	} else {
		// utilisateur non trouvé
		return false;
	}
}

/**
 * get_user_password_hash()
 *
 * @param string $password
 * @param string $tested_hash
 * @param boolean $password_given_as_first_password_hash
 * @param integer $password_length_if_given_as_first_password_hash
 * @return
 */
function get_user_password_hash($password, $tested_hash = null, $password_given_as_first_password_hash = false, $password_length_if_given_as_first_password_hash = null)
{
	if ($tested_hash == md5($password)) {
		// Pour des raisons de compatibilité avec les données d'anciens sites PEEL dont version < 6.0, on teste aussi si le md5 fonctionne
		// En termes de sécurité, cette compatibilité n'induit pas de faille particulière sur les nouveaux comptes, mais bien évidemment
		// continuer à utiliser d'anciens mots de passe encodés ne permet pas de bénéficier de la sécurité accrue dans les version >=6.0.
		return $tested_hash;
	}
	if (!$password_given_as_first_password_hash) {
		// Création d'un premier hash du mot de passe
		$first_password_hash = sha256(vb($GLOBALS['site_parameters']['sha256_encoding_salt']) . $password);
		// set where salt will appear in hash
		$salt_start = String::strlen($password);
	} else {
		$first_password_hash = $password;
		$salt_start = $password_length_if_given_as_first_password_hash;
	}
	// if no salt given create random one
	if ($tested_hash == null) {
		$salt_hash = String::substr(sha256(vb($GLOBALS['site_parameters']['sha256_encoding_salt']) . uniqid(mt_rand(), true)), 0, 6);
	} else {
		$salt_hash = String::substr($tested_hash, 0, 6);
	}
	// add salt into text hash at pass length position and hash it
	if ($salt_start > 0 && $salt_start < String::strlen($salt_hash)) {
		$first_password_hash_start = String::substr($first_password_hash, 0, $salt_start);
		$first_password_hash_end = String::substr($first_password_hash, $salt_start, strlen($salt_hash));
		$hash_rough = sha256(vb($GLOBALS['site_parameters']['sha256_encoding_salt']) . $first_password_hash_end . $salt_hash . $first_password_hash_start);
	} elseif ($salt_start > (strlen($salt_hash) - 1)) {
		$hash_rough = sha256(vb($GLOBALS['site_parameters']['sha256_encoding_salt']) . $first_password_hash . $salt_hash);
	} else {
		$hash_rough = sha256(vb($GLOBALS['site_parameters']['sha256_encoding_salt']) . $salt_hash . $first_password_hash);
	}
	// put salt at front of hash
	$password_hash = $salt_hash . String::substr($hash_rough, 0, 26);
	if (empty($tested_hash) || $tested_hash == $password_hash) {
		return $password_hash;
	} else {
		return false;
	}
}

/**
 * Envoi d'email lors de la création d'un utilisateur
 *
 * @param string $email
 * @param string $mot_passe
 * @param string $priv
 * @return
 */
function send_mail_for_account_creation($email, $mot_passe, $priv)
{
	$custom_template_tags['EMAIL'] = $email;
	$custom_template_tags['MOT_PASSE'] = $mot_passe;
	
	$hook_result = call_module_hook('send_mail_for_account_creation_custom_template_tags', array('email' => $email, 'priv' => $priv, 'mot_passe' => $mot_passe, 'custom_template_tags' => $custom_template_tags), 'array');
	$custom_template_tags = array_merge_recursive_distinct($custom_template_tags, $hook_result);

	// Template d'email spécifique pour le profil d'utilisateur. Cet email est envoyé en plus de l'email d'inscription. L'email d'inscription contient les identifiants du compte.
	$template_infos = getTextAndTitleFromEmailTemplateLang('send_mail_for_account_creation_'.$priv, $_SESSION['session_langue']);
	
	if (!empty($template_infos) && (!empty($template_infos['subject']) || !empty($template_infos['text']))) {
		// Le template d'email existe, il faut l'utiliser
		send_email($email, $template_infos['subject'], $template_infos['text'], "", $custom_template_tags, null, $GLOBALS['support_sav_client']);
	}

	// Par ailleurs on utilise le template de création de compte standard.
	$result = send_email($email, "", "", 'send_mail_for_account_creation', $custom_template_tags, null, $GLOBALS['support_sav_client']);
	return $result;
}

/**
 * getUsername()
 *
 * @param integer $user_id
 * @return
 */
function getUsername($user_id)
{
	if ($user_infos = get_user_information($user_id)) {
		return $user_infos['prenom'] . ' ' . $user_infos['nom_famille'];
	} else {
		return null;
	}
}

/**
 * Chargement des détails de l'utilisateur
 *
 * @param integer $user_id Si vide, alors on renvoie les informations de l'utilisateur connecté
 * @param boolean $get_full_infos
 * @param boolean $skip_cache
 * @return
 */
function get_user_information($user_id = null, $get_full_infos = false, $skip_cache = false)
{
	static $result_array;
	$sql_cond = '';
	if ($user_id === null && est_identifie()) {
		$user_id = $_SESSION['session_utilisateur']['id_utilisateur'];
	} elseif (est_identifie() && a_priv('demo')) {
		// Pas les droits pour voir les informations sur les administrateurs et les revendeurs
		$sql_cond .= " AND priv NOT LIKE '%admin%' AND priv NOT LIKE '%reve%'";
	}
	$cache_id = md5($user_id.$sql_cond.($get_full_infos?'full':''));
	if (!empty($user_id)) {
		if (!isset($result_array[$cache_id]) || $skip_cache) {
			$qid = query("SELECT *
				FROM peel_utilisateurs
				WHERE id_utilisateur = '" . intval($user_id) . "' AND " . get_filter_site_cond('utilisateurs') . "" . $sql_cond);
			if($result_array[$cache_id] = fetch_assoc($qid)) {
				// L'utilisateur a été trouvé, on complète les informations
				$result_array[$cache_id]['parameters'] = unserialize(vb($result_array[$cache_id]['parameters']));
				if(!empty($result_array[$cache_id]) && $get_full_infos) {
					$hook_result = call_module_hook('user_get_information_full', array('id' => $user_id, 'etat' => $result_array[$cache_id]['etat'], 'user_infos' => $result_array[$cache_id]), 'array');
					$result_array[$cache_id] = array_merge_recursive_distinct($result_array[$cache_id], $hook_result);
				}
			}
		}
		return $result_array[$cache_id];
	} else {
		return null;
	}
}

/**
 * Calcule la réduction générale applicable à un utilisateur et garde la valeur en session pour accélérer en cas de formule complexe
 *
 * @return
 */
function get_current_user_promotion_percentage($user_info = null)
{
	if (empty($user_info)) {
		$user_info = vb($_SESSION['session_utilisateur']);
	}
	if(!isset($_SESSION['session_utilisateur']['calculated_promotion_percentage'])) {
		$hook_result_percent = call_module_hook('user_promotion_percentage', vb($user_info, array()), 'max');
		$user_specific_discount = vn($user_info['remise_percent']);
		if(!empty($GLOBALS['site_parameters']['group_and_user_discount_cumulate_disable'])) {
			$_SESSION['session_utilisateur']['calculated_promotion_percentage'] = max($user_specific_discount, $hook_result_percent);
		} else {
			$_SESSION['session_utilisateur']['calculated_promotion_percentage'] = (1 - (1 - $user_specific_discount / 100) * (1 - $hook_result_percent / 100)) * 100;
		}
	}
	return $_SESSION['session_utilisateur']['calculated_promotion_percentage'];
}

/**
 * is_user_tva_intracom_for_no_vat()
 *
 * @param mixed $user_id
 * @return
 */
function is_user_tva_intracom_for_no_vat($user_id = null)
{
	if (empty($user_id) && est_identifie()) {
		$user_id = $_SESSION['session_utilisateur']['id_utilisateur'];
	}
	if (!empty($user_id)) {
		if ($user_infos = get_user_information($user_id)) {
			// Pas de vérification trop stricte du numéro de TVA intracommunautaire pour éviter les problèmes liés à des formats différents
			if (!empty($GLOBALS['site_parameters']['pays_exoneration_tva']) && String::strlen($GLOBALS['site_parameters']['pays_exoneration_tva'])==2 && !empty($user_infos['intracom_for_billing']) && !is_numeric(String::substr($user_infos['intracom_for_billing'], 0, 2)) && String::substr(String::strtoupper($user_infos['intracom_for_billing']), 0, 2) != $GLOBALS['site_parameters']['pays_exoneration_tva'] && String::strlen($user_infos['intracom_for_billing']) >= 7 && String::strlen(str_replace(' ', '', $user_infos['intracom_for_billing'])) <= 14) {
				// Utilisateur avec un n° de TVA intracom, en Europe mais pas dans le pays de référence de la boutique dont le code ISO sur 2 chiffres est dans "pays_exoneration_tva"
				return true;
			}
		}
	}
	return false;
}

/**
 * get_priv_options()
 *
 * @param integer $selected
 * @param string $mode
 * @param string $search_mode
 * @return
 */
function get_priv_options($selected, $mode = 'output', $search_mode = false)
{
	$output = '';
	$tpl_options = array();
	$in_wait_priv = 0;
	if(empty($selected) && !$search_mode) {
		// Sélection du privilège du l'utilisateur. Si le privilège de l'utilisateur n'est pas défini dans la table, le privilège 'util' est présélectionné
		$selected = array('util');
	} elseif(!is_array($selected)) {
		// Des profils avec privilèges multiples : ces privilèges sont séparés par  des + en base de données
		$selected = explode('+', $selected);
	}
	$resProfil = query("SELECT *, name_".$_SESSION['session_langue']." AS name
		FROM peel_profil
		WHERE " . get_filter_site_cond('profil') . " 
		ORDER BY name ASC");
	while ($Profil = fetch_assoc($resProfil)) {
		$priv_name = $Profil['name'];
		if (defined('IN_PEEL_ADMIN') && isset($_SESSION['session_admin_multisite']) && $_SESSION['session_admin_multisite'] === 0) {
			// L'administrateur multisite consulte la liste des privilèges existants. Dans ce cas tous les privilèges de tous les sites sont affichées, et on affiche le nom du site à coté du nom du privilège pour éviter des erreurs d'administration.
			if(!isset($all_sites_name_array)) {
				$all_sites_name_array = get_all_sites_name_array(true);
			}
			if(!empty($all_sites_name_array[$Profil['site_id']])) {
				$priv_name = '[' . $all_sites_name_array[$Profil['site_id']] . '] ' . $priv_name;
			}
		}
		$tpl_options[$Profil['priv']] = array(
			'value' => $Profil['priv'],
			'issel' => in_array($Profil['priv'], $selected),
			'name' => $priv_name
		);
		if(String::strpos($Profil['priv'], 'stop') !== false) {
			$in_wait_priv++;
		}
	}
	if($search_mode && $in_wait_priv>1) {
		// Ajout d'un choix pour voir tous les utilisateurs en attente de validation de leurs droits
		$tpl_options = array_merge(array(array(
				'value' => 'stop%',
				'issel' => in_array('stop%', $selected),
				'name' => $GLOBALS["STR_ADMIN_OFFLINE"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $GLOBALS["STR_ADMIN_ALL"]
			)), $tpl_options);
	}
	if($mode == 'output') {
		if(!empty($tpl_options)) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('priv_options.tpl');
			$tpl->assign('options', $tpl_options);
			$output .= $tpl->fetch();
		}
		return $output;
	} else {
		return $tpl_options;
	}
}

/**
 * Fonction de recherche d'id utilisateur par l'email
 *
 * @param mixed $email
 * @return
 */
function get_user_id_from_email($email)
{
	// si la valeur est un bien un email, ont recherche l'id a partir de cet email
	if (EmailOK($email)) {
		$q = query('SELECT id_utilisateur
			FROM peel_utilisateurs
			WHERE email = "' . word_real_escape_string($email) . '" AND ' . get_filter_site_cond('utilisateurs') . '');
		if ($user = fetch_assoc($q)) {
			return $user['id_utilisateur'];
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Renvoie une trace du navigateur utilisé par un utilisateur pour faciliter les rapprochements entre comptes pour des personnes changeant tout le temps d'IP
 *
 * @return
 */
function getUserAgentHash()
{
	// Returns an int on 4 bytes
	if (!empty($_SERVER['HTTP_USER_AGENT'])) {
		return base_convert(String::substr(md5($_SERVER['HTTP_USER_AGENT']), 9, 8), 16, 10);
	} else {
		return 0;
	}
}

/**
 * get_trader_select_options()
 *
 * @param mixed $selected_trader_name Name of the trader preselected
 * @param mixed $selected_trader_id Id of the trader preselected
 * @param mixed $option_value defaults 'name'  It defines wether the option value has to be the trader id or ther trader name
 * @param boolean $is_admin_mode
 * @param boolean $display_inactive_trader
 * @return
 */
function get_trader_select_options($selected_trader_name = null, $selected_trader_id = null, $option_value = 'name', $is_admin_mode = false, $display_inactive_trader = false)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('trader_select_options.tpl');
	$tpl->assign('STR_NOT_ATTRIBUED', $GLOBALS['STR_NOT_ATTRIBUED']);
	$tpl->assign('is_admin_mode', $is_admin_mode);
	$sql_condition = '';
	if (empty($selected_trader_name) && empty($selected_trader_id)) {
		$selected_country_id = 0;
	}
	if (!$display_inactive_trader) {
		$sql_condition .= ' AND u.etat = "1"';
	}
	$sql_trader = 'SELECT u.id_utilisateur, u.nom_famille, u.prenom
		FROM peel_utilisateurs u
		WHERE CONCAT("+",u.priv,"+") LIKE "%+admin%" AND ' . get_filter_site_cond('utilisateurs', 'u') . ' ' . $sql_condition . '
		ORDER BY u.id_utilisateur';
	$res_trader = query($sql_trader);
	$tpl_options = array();
	while ($tab_trader = fetch_assoc($res_trader)) {
		if ($option_value == 'name') {
			$value = $tab_trader['prenom'] . ' ' . $tab_trader['nom_famille'];
		} elseif ($option_value == 'id') {
			$value = $tab_trader['id_utilisateur'];
		}
		$trader_name = $tab_trader['prenom'] . ' ' . $tab_trader['nom_famille'];
		$tpl_options[] = array(
			'value' => $value,
			'issel' => (vb($selected_trader_name) == $trader_name || vb($selected_trader_id) == $tab_trader['id_utilisateur']),
			'name' => $trader_name
		);
	}
	$tpl->assign('options', $tpl_options);
	return $tpl->fetch();
}

/**
 * Met à jour les informations de l'utilisateur connecté, telles que pour de la messagerie interne, des calculs de monnaie interne, etc.
 *
 * @return
 */
function account_update() {
	if ((!isset($_SESSION['session_update_account']) || $_SESSION['session_update_account'] < time()) || defined('IN_MESSAGING')) {
		// On met à jour les informations du compte toutes les minutes
		call_module_hook('account_update', array());
		$_SESSION['session_update_account'] = time() + vb($GLOBALS['site_parameters']['account_update_interval'], 60);
	}
}

/**
 * Retourne le menu déroulant avec la lsite des adresses disponibles par utilisateur
 *
 * @param mixed $id_utilisateur
 * @param mixed $address_type
 * @param integer $selected
 * @param boolean $add_manage_choice
 * @param boolean $css_style
 * @return
 */
function get_personal_address_form($id_utilisateur, $address_type = 'bill', $selected = null, $add_manage_choice = true, $css_style = null) {
	if(empty($id_utilisateur)) {
		return false;
	} else {
		$sql = 'SELECT id, nom
			FROM peel_adresses
			WHERE id_utilisateur="' . intval($id_utilisateur) . '" AND address_type IN ("","'.real_escape_string($address_type).'")';
		$q = query($sql);
		$output = '
		<select class="form-control" onchange="if(this.value){this.form.submit()}" name="personal_address_' . String::str_form_value($address_type) . '" style="' . $css_style. '">
			<option value="">' . $GLOBALS['STR_ADDRESS'] . '....</option>
			<option value="original_address"' . frmvalide($selected == 'original_address', ' selected="selected"') . '>' . $GLOBALS['STR_DEFAULT_ADDRESS'] . '</option>';
		while($result = fetch_assoc($q)) {
			$output .= '
			<option value="' . intval($result['id']) . '"' . frmvalide($selected == $result['id'], ' selected="selected"') . '>' . $result['nom'] . '</option>';
		}
		if($add_manage_choice) {
			$output .= '
			<option value="">-----</option>
			<option value="manage">' . $GLOBALS['STR_ADDRESS_TEXT'] . '</option>';
		}
		$output .= '
		</select>
';

		return $output;
	}
}

/**
 * Met à jour l'adresse en base de données
 *
 * @param class $frm
 * @return
 */
function insert_or_update_address($frm) {
	if(empty($_SESSION['session_utilisateur']['id_utilisateur'])) {
		return false;
	}
	if(!empty($frm['portable'])) {
		$tel = $frm['portable'];
	} elseif(!empty($frm['contact1'])) {
		$tel = $frm['contact1'];
	} else {
		$tel = null;
	}
	$set_sql = "civilite = '" . nohtml_real_escape_string(vb($frm['civilite'])) . "'
			, email = '" . nohtml_real_escape_string($frm['email']) . "'
			, nom = '" . nohtml_real_escape_string($frm['name_adresse']) . "'
			, prenom = '" . nohtml_real_escape_string($frm['prenom']) . "'
			, nom_famille = '" . nohtml_real_escape_string($frm['nom_famille']) . "'
			, societe = '" . nohtml_real_escape_string($frm['societe']) . "'
			, telephone = '" . nohtml_real_escape_string($tel) . "'
			, portable = '" . nohtml_real_escape_string($frm['portable']) . "'
			, adresse = '" . nohtml_real_escape_string($frm['adresse']) . "'
			, code_postal = '" . nohtml_real_escape_string($frm['code_postal']) . "'
			, ville = '" . nohtml_real_escape_string($frm['ville']) . "'
			".(isset($frm['address_type'])?", address_type = '" . nohtml_real_escape_string($frm['address_type']) . "'":'')."
			, pays = '" . intval($frm['pays']) . "'
			, id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'";
	if (!empty($frm['id'])) {
		return query("UPDATE peel_adresses SET
			".$set_sql."
			WHERE id = '" . intval(vn($frm['id'])) . "'");
	} else {
		return query("INSERT INTO peel_adresses SET 
		".$set_sql."");
	}
}

/**
 * Calcule l'état de complétude du compte utilisateur
 *
 * @param array $user_infos
 * @return
 */
function user_account_completion($user_infos) {
	$account_fields = vb($GLOBALS['site_parameters']['user_account_completion_fields'], array('nom_famille', 'prenom', 'adresse', 'code_postal', 'ville', 'pays'));
	$weight = 0;
	$total_weight = 0;
	
	$hook_result = call_module_hook('user_account_completion_fields', array(), 'array');
	$account_fields = array_merge_recursive_distinct($account_fields, vb($hook_result, array()));
	
	$account_fields = array_merge_recursive_distinct($account_fields, array_keys(vb($GLOBALS['site_parameters']['user_mandatory_fields'], array())));
	$account_fields = array_unique($account_fields);

	foreach($account_fields as $this_field) {
		if(!empty($user_infos[$this_field])) {
			$weight += 1;
		}
		if(isset($user_infos[$this_field])) {
			$total_weight += 1;
		}
	}
	if($total_weight) {
		$percentage = round($weight / $total_weight *100) . '%';
	} else {
		$percentage = '';
	}
	return $percentage;
}

/**
 * Affiche une vignette de présentation d'un profil
 *
 * @param array $user_infos
 * @param integer $ad_id
 * @param string $additional_text
 * @return
 */
function get_profile_bloc($user_infos, $ad_id = null, $additional_text = null, $message_id = null) {
	if (!empty($user_infos['user_public_profil_url'])) {
		$url = $user_infos['user_public_profil_url'];
	} elseif(function_exists('get_vote_profil_url')) {
		$url = get_vote_profil_url($user_infos['id_utilisateur'], $user_infos['pseudo']);
	} elseif(function_exists('get_user_profil_link')) {
		$url = get_user_profil_link($user_infos['id_utilisateur']);
	} else {
		$url = '#';
	}
	if (!empty($ad_id)) {
		// Si présente : id de l'annonce relative à l'utilisateur, dans le cas où on affiche tous les profils par annonce
		$url .= '?ad_related='.$ad_id.'&user_related='.$user_infos['id_utilisateur'];
	}
	if (!empty($message_id)) {
		// Si présente : id du message relatif à la candidateur d'un utilisateur, dans le cas où on affiche tous les profils par annonce
		$url .= '&msgid='.$message_id;
	}
	if(empty($user_infos['logo'])) {
		$user_infos['logo'] = $GLOBALS['site_parameters']['default_picture'];
	}
	if (!empty($ad_id)) {
		$query_ads_related = query('SELECT user_id
			FROM peel_ads_related
			WHERE ad_id="' . intval($ad_id) . '" AND user_id='.intval($user_infos['id_utilisateur']).'
			LIMIT 1');
		if ($result=fetch_assoc($query_ads_related)) {
			$additional_text .= '
			<h4>'. $GLOBALS["STR_RECIPIENT_CHOSEN"] .'</h4>';
		}
	}
	$output = '
	<div class="user' . String::substr($user_infos['civilite'], 0, 1) . '">
		<table>
			<tr>
				<td class="center"><h3><a href="' . $url . '">' . (function_exists('get_user_picto')?get_user_picto($user_infos['civilite'],$user_infos['priv']):'') . (!empty($user_infos['pseudo'])?$user_infos['pseudo']:$user_infos['prenom'].' '.$user_infos['nom_famille']) . '</a></h3></td>
			</tr>
			<tr>
				<td class="center">
					<div class="img_link"><a href="' . $url . '"><img src="'. thumbs($user_infos['logo'], 150, 150, 'fit', null, null, true, true) . '" alt="" /></a></div>
					' . (empty($GLOBALS['site_parameters']['groups_advanced_user_age_show_disable'])?'<div>' . userAgeFormat($user_infos['naissance']) . '</div>':'') . '
					' . $additional_text . '
				</td>
			</tr>
			<tr>
				<td class="center">
					<a class="btn btn-primary" href="' . $url . '">'.$GLOBALS['STR_MORE_DETAILS'].'</a>
				</td>
			</tr>
		</table>
	</div>
';
	$output = '
<div class="col-sm-4 col-md-3 col-lg-3">
	<div class="get_profile_user_bloc">
		' . $output . '
	</div>
</div>
';
	return $output;
}
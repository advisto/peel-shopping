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
// $Id: user.php 43244 2014-11-17 17:03:01Z sdelaporte $
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
 * get_profil_select_options()
 *
 * @param mixed $selected_priv
 * @return
 */
function get_profil_select_options($selected_priv = null)
{
	$output = '';
	$sql_profil = "SELECT id, name_".$_SESSION['session_langue']." AS name, priv
		FROM peel_profil
		WHERE " . get_filter_site_cond('profil', null, defined('IN_PEEL_ADMIN')) . "
		ORDER BY name DESC";
	$res_profil = query($sql_profil);
	while ($tab_profil = fetch_assoc($res_profil)) {
		$output .= '<option value="' . String::str_form_value($tab_profil['priv']) . '" ' . frmvalide($selected_priv == $tab_profil['priv'], ' selected="selected"') . '>' . $tab_profil['name'] . '</option>';
	}
	return $output;
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
	if (!empty($user_priv)) {
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
 * @return integer New user id
 */
function insere_utilisateur(&$frm, $password_already_encoded = false, $send_user_confirmation = false, $warn_admin_if_template_active = true, $skip_existing_account_tests = false)
{
	$sql_condition_array = array();
	if (!empty($frm['priv'])) {
		if(is_array($frm['priv'])) {
			$frm['priv'] = implode('+', $frm['priv']);
		}
	} else {
		if(!empty($GLOBALS['site_parameters']['user_creation_default_profile'])) {
			// ce paramètre permet de définir depuis le back office un privilège utilisateur dès l'inscription
			$allowed_profil = array();
			$query = query("SELECT priv FROM peel_profil WHERE priv NOT LIKE 'admin%' AND " . get_filter_site_cond('profil', null, defined('IN_PEEL_ADMIN')) . "");
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
			$frm['priv'] = 'util';
		}
	}
	if (!empty($frm['email'])) {
		$sql_condition_array[] = "email='" . word_real_escape_string(vb($frm['email'])) . "'";
	}
	if (empty($GLOBALS['site_parameters']['pseudo_is_not_used']) && !empty($frm['pseudo'])) {
		$sql_condition_array[] = "pseudo='" . nohtml_real_escape_string(vb($frm['pseudo'])) . "'";
	}
	if (!$skip_existing_account_tests && !empty($sql_condition_array)) {
		// On teste si l'utilisateur existe déjà
		$sql = "SELECT id_utilisateur
			FROM peel_utilisateurs
			WHERE (" . implode(' OR ', $sql_condition_array).") AND " . get_filter_site_cond('utilisateurs') . "";
		$result = query($sql);
		if ($user_already_exists_infos = fetch_assoc($result)) {
			// L'utilisateur existe déjà, on donne son id
			return $user_already_exists_infos['id_utilisateur'];
		}
	}
	if (!isset($frm['remise_percent'])) {
		$remise_percent = 0;
	} else {
		$remise_percent = (float)$frm['remise_percent'];
	}
	if (!empty($frm['mot_passe']) && $password_already_encoded) {
		$password_hash = trim($frm['mot_passe']);
	} elseif (!empty($frm['mot_passe'])) {
		$password_hash = get_user_password_hash(trim($frm['mot_passe']));
	} elseif(empty($frm['mot_passe']) && !empty($GLOBALS['site_parameters']['register_during_order_process'])) {
		// Création d'un utilisateur lors du process de commande. Le mot de passe est envoyé à l'utilisateur
		$frm['mot_passe'] = MDP();
		$password_hash = get_user_password_hash($frm['mot_passe']);
	} else {
		// On crée un utilisateur qui ne pourra pas se connecter avant de demander un mot de passe
		$password_hash = get_user_password_hash(MDP());
	}
	if (!empty($frm['date_insert'])) {
		$date_insert = $frm['date_insert'];
	} else {
		$date_insert = date('Y-m-d H:i:s', time());
	}
	if (!empty($frm['date_update'])) {
		$date_update = $frm['date_update'];
	} else {
		$date_update = date('Y-m-d H:i:s', time());
	}
	if (isset($frm['points'])) {
		$points = $frm['points'];
	} else {
		$points = 0;
	}	
	if (!isset($frm['etat'])) {
		$frm['etat'] = 1;
	}
	if (!$skip_existing_account_tests) {
		if (!empty($frm['priv']) && ($frm['priv'] != 'load' && $frm['priv'] != 'newsletter')) {
			// Si cet utilisateur est déjà inscrit pour un téléchargement, il faut supprimer l'enregistrement correspondant à son email pour permettre la création du compte
			$sql = 'SELECT id_utilisateur, priv
				FROM peel_utilisateurs
				WHERE email="' . word_real_escape_string($frm['email']) . '" AND ' . get_filter_site_cond('utilisateurs') . '';
			$result = query($sql);
			$user_already_exists_infos = fetch_assoc($result);
			if (!empty($user_already_exists_infos) && ($user_already_exists_infos['priv'] == 'load' || $user_already_exists_infos['priv'] == 'newsletter')) {
				query("DELETE FROM peel_utilisateurs
					WHERE id_utilisateur='" . intval($user_already_exists_infos['id_utilisateur']) . "' AND " . get_filter_site_cond('utilisateurs') . "");
			}
		}
	}
	if(!empty($GLOBALS['site_parameters']['user_specific_field_titles']) && defined('IN_REGISTER')) {
		$specific_fields_titles = $GLOBALS['site_parameters']['user_specific_field_titles'];
		$specific_field_types = $GLOBALS['site_parameters']['user_specific_field_types'];
	} elseif(!empty($GLOBALS['site_parameters']['reseller_specific_field_titles']) && is_reseller_module_active() && defined('IN_RETAILER')) {
		$specific_fields_titles = $GLOBALS['site_parameters']['reseller_specific_field_titles'];
		$specific_field_types = $GLOBALS['site_parameters']['reseller_specific_field_types'];
	}
	if (!empty($specific_fields_titles)) {
		// Paramètre lié à la fonction get_specific_field_infos.
		// récupération des champs de la BDD, pour éviter les erreurs de mise à jour du à une erreur d'administration de user_specific_field_titles, et ne pas mettre les champs type separator dans la requête SQL, et tout autre intru qui ferais échoué la requete.
		$this_table_fields_names = get_table_field_names('peel_utilisateurs');
		foreach($specific_fields_titles as $this_field => $this_title) {
			if (!in_array($this_field, $this_table_fields_names)) {
				// Champ pas présent en BDD, on ne l'ajoute pas à la requête SQL.
				continue;
			}
			if ((defined('IN_REGISTER') || defined('IN_RETAILER')) && !empty($GLOBALS['site_parameters']['disable_user_specific_field_on_register_page']) && in_array($this_field, $GLOBALS['site_parameters']['disable_user_specific_field_on_register_page'])) {
				// Ne pas prendre en compte les champs absents de la page d'enregistrement
				continue;
			}
			if ($specific_field_types[$this_field] == 'datepicker') {
				$frm[$this_field] = get_mysql_date_from_user_input($frm[$this_field]);
			}
			if ($specific_field_types[$this_field] == 'upload') {
				$frm[$this_field] = upload('logo', false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm[$this_field]));
			}
			if (isset($frm[$this_field])) {
				if (is_array($frm[$this_field])) {
					// Si $frm[$this_field] est un tableau, il faut le convertir en chaine de caractères pour le stockage en BDD
					$frm[$this_field] = implode(',', $frm[$this_field]);
				}
				$user_specific_field[] = word_real_escape_string($this_field);
				$user_specific_field_values[] = nohtml_real_escape_string($frm[$this_field]);
			}
		}
	}
	if(empty($frm['lang'])) {
		$frm['lang'] = $_SESSION['session_langue'];
	}	
	if(!isset($frm['site_id'])) {
		$frm['site_id'] = $GLOBALS['site_id'];
	}
	if(!defined('PEEL_ADMIN')) {
		if(!empty($GLOBALS['site_parameters']['devise_force_user_choices']) && is_devises_module_active()) {
			$frm['devise'] = $_SESSION['session_devise'];
		}
		if(!empty($GLOBALS['site_parameters']['site_country_forced_by_user']) && !empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			if(in_array(strval(vb($frm['pays'])), $GLOBALS['site_parameters']['site_country_allowed_array'])) {
				$frm['site_country'] = $frm['pays'];
			} else {
				$frm['site_country'] = $_SESSION['session_site_country'];
			}
		}
	}
	$sql = "INSERT INTO peel_utilisateurs (
		date_insert
		, date_update
		, email
		, mot_passe
		, priv
		, civilite
		, prenom
		, pseudo
		, nom_famille
		, telephone
		, fax
		, portable
		, adresse
		, code_postal
		, ville
		, pays
		, newsletter
		, commercial
		, remise_percent
		, points
		, format
		, societe
		, intracom_for_billing
		, siret
		, ape
		, code_banque
		, code_guichet
		, numero_compte
		, cle_rib
		, domiciliation
		, iban
		, bic
		, url
		, description
		, avoir
		, naissance
		, id_groupe
		, origin
		, origin_other
		, lang
		, on_vacances
		, on_vacances_date
		, promo
		" . (!empty($frm['id_categories'])?', id_categories':'') . "
		" . (!empty($frm['id_cat_1'])?', id_cat_1':'') . "
		" . (!empty($frm['id_cat_2'])?', id_cat_2':'') . "
		" . (!empty($frm['id_cat_3'])?', id_cat_3':'') . "
		, activity
		, seg_who
		, seg_want
		, seg_think
		, seg_followed
		, seg_buy
		, project_product_proposed
		, project_date_forecasted
		, commercial_contact_id
		, type
		, on_client_module
		, on_photodesk
		, site_id
		, fonction
		, etat
		" . (!empty($frm['id_utilisateur'])?', id_utilisateur':'') . "
		" . (!empty($frm['control_plus'])?', control_plus':'') . "
		" . (!empty($frm['note_administrateur'])?', note_administrateur':'') . "
		" . (!in_array('logo', vb($user_specific_field, array()))?', logo':'') . "
		" . (isset($frm['devise'])? ", devise":'') . "
		" . (isset($frm['site_country'])? ", site_country":'') . "
		, description_document
		" . (!empty($frm['document'])? ", document " : "") . "
		" . (!empty($user_specific_field)? ',' . implode(',', $user_specific_field) : "") . "
	) VALUES (
		'" . nohtml_real_escape_string($date_insert) . "'
		, '" . nohtml_real_escape_string($date_update) . "'
		, '" . nohtml_real_escape_string(trim($frm['email'])) . "'
		, '" . nohtml_real_escape_string($password_hash) . "'
		, '" . nohtml_real_escape_string(vb($frm['priv'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['civilite'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['prenom'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['pseudo'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['nom_famille'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['telephone'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['fax'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['portable'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['adresse'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['code_postal'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['ville'])) . "'
		, '" . intval(vn($frm['pays'])) . "'
		, '" . intval(vn($frm['newsletter'])) . "'
		, '" . intval(vn($frm['commercial'])) . "'
		, '" . nohtml_real_escape_string(vb($remise_percent)) . "'
		, '" . intval(vb($points)) . "'
		, '" . nohtml_real_escape_string(vb($GLOBALS['site_parameters']['email_sending_format_default'], 'html')) . "'
		, '" . nohtml_real_escape_string(vb($frm['societe'])) . "'
		, '" . nohtml_real_escape_string(String::strtoupper(vb($frm['intracom_for_billing']))) . "'
		, '" . nohtml_real_escape_string(vb($frm['siret'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['ape'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['code_banque'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['code_guichet'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['numero_compte'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['cle_rib'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['domiciliation'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['iban'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['bic'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['url'])) . "'
		, '" . real_escape_string(vb($frm['description'])) . "'
		, '" . nohtml_real_escape_string(vn($frm['avoir'])) . "'
		, '" . nohtml_real_escape_string(get_mysql_date_from_user_input(vb($frm['naissance']))) . "'
		, '" . nohtml_real_escape_string(vn($frm['id_groupe'])) . "'
		, '" . nohtml_real_escape_string(vn($frm['origin'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['origin_other'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['lang'])) . "'
		, '" . intval(vn($frm['on_vacances'])) . "'
		, '" . nohtml_real_escape_string(get_mysql_date_from_user_input(vb($frm['on_vacances_date']))) . "'
		, '" . nohtml_real_escape_string(vb($frm['promo_code'])) . "'
		" . (!empty($frm['id_categories'])? ",'" . implode("','", $frm['id_categories']) : "") . "
		" . (!empty($frm['id_cat_1'])? ', ' . intval(vn($frm['id_cat_1'])):'') . "
		" . (!empty($frm['id_cat_2'])? ', ' . intval(vn($frm['id_cat_2'])):'') . "
		" . (!empty($frm['id_cat_3'])? ', ' . intval(vn($frm['id_cat_3'])):'') . "
		, '" . nohtml_real_escape_string(vb($frm['activity'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['seg_who'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['seg_want'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['seg_think'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['seg_followed'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['seg_buy'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['project_product_proposed'])) . "'
		, '" . nohtml_real_escape_string(get_mysql_date_from_user_input(vb($frm['project_date_forecasted']))) . "'
		, '" . intval(vn($frm['commercial_contact_id'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['type'])) . "'
		, '" . intval(vn($frm['on_client_module'])) . "'
		, '" . intval(vn($frm['on_photodesk'])) . "'
		, '" . intval(vn($frm['site_id'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['fonction'])) . "'
		, '" . intval($frm['etat']) . "'
		" . (!empty($frm['id_utilisateur'])?', ' . intval($frm['id_utilisateur']):'') . "
		" . (!empty($frm['control_plus'])?', ' . intval($frm['control_plus']):'') . "
		" . (!empty($frm['note_administrateur'])?', ' . intval($frm['note_administrateur']):'') . "
		" . (!in_array('logo', vb($user_specific_field, array()))?', "' . nohtml_real_escape_string(vb($frm['logo'])).'"':'') . "
		" . (isset($frm['devise'])? ", '" . nohtml_real_escape_string($frm['devise']) . "'" : "") . "
		" . (isset($frm['site_country'])? ", '" . nohtml_real_escape_string($frm['site_country']) . "'" : "") . "
		, '" . nohtml_real_escape_string(vb($frm['description_document'])) . "'
		" . (!empty($frm['document'])? ", '" . nohtml_real_escape_string(vb($frm['document'])) . "'" : "") . "
		" . (!empty($user_specific_field_values)? ", '" . implode("','", $user_specific_field_values) . "'" : "") . "
		)";
	$qid = query($sql);

	$clientid = insert_id();
	if (check_if_module_active('wanewsletter')) {
		insere_wa_utilisateur($frm);
	}
	$code_client = "CLT" . date("Y") . $clientid;

	query("UPDATE peel_utilisateurs
		SET code_client = '" . nohtml_real_escape_string($code_client) . "'
		WHERE id_utilisateur = '" . intval($clientid) . "' AND " . get_filter_site_cond('utilisateurs') . "");

	if ($send_user_confirmation) {
		// envoi de l'email de réinitialisation du mot de passe
		send_mail_for_account_creation($frm['email'], $frm['mot_passe']);
	}
	if ($warn_admin_if_template_active) {
		// Prévenir l'administrateur d'une création d'utilisateur
		$qid = query("SELECT name_".$_SESSION['session_langue']." AS name
			FROM `peel_profil`
			WHERE priv = '" . nohtml_real_escape_string(vb($frm['priv'])) . "' AND " . get_filter_site_cond('profil', null, defined('IN_PEEL_ADMIN')) . "
			LIMIT 0 , 30");
		$qid = fetch_assoc($qid);
		$custom_template_tags['PRIV'] = $qid['name'];
		$custom_template_tags['CIVILITE'] = $frm['civilite'];
		$custom_template_tags['PRENOM'] = $frm['prenom'];
		$custom_template_tags['NOM_FAMILLE'] = $frm['nom_famille'];
		$custom_template_tags['EMAIL'] = $frm['email'];
		$custom_template_tags['DATE'] = get_formatted_date(time(), 'short', 'long');
		$custom_template_tags['SOCIETE'] = $frm['societe'];
		$custom_template_tags['TELEPHONE'] = $frm['telephone'];
		$custom_template_tags['ADMIN_URL'] = $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $clientid . '&start=0';

		if ($frm['priv'] == 'stop') {
			$template_technical_code = 'warn_admin_reve_subscription';
		} else {
			$template_technical_code = 'warn_admin_user_subscription';
		}
		send_email($GLOBALS['support_sav_client'], '', '', $template_technical_code, $custom_template_tags, null, $GLOBALS['support_sav_client']);
	}

	if (check_if_module_active('groups_advanced')) {
		init_default_user_groups($clientid);
	}
	return $clientid;
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
	if (isset($frm['priv'])) {
		if(is_array($frm['priv'])) {
			$priv = implode('+', $frm['priv']);
		} else {
			$priv = $frm['priv'];
		}
	}
	if(!$update_current_session && !a_priv('admin', false, true)) {
		if(a_priv('admin*', false, false, $frm['id_utilisateur'])) {
			// L'utilisateur qu'on veut modifier est un administrateur et l'utilisateur loggué n'a pas le droit de le faire
			return false;
		} elseif(!empty($priv) && String::strpos($priv, 'admin') === 0) {
			unset($priv);
		}
	}
	if (!empty($GLOBALS['site_parameters']['user_specific_field_titles'])) {
		// récupération des champs de la BDD, pour éviter les erreurs de mise à jour du à une erreur d'administration de user_specific_field_titles, et ne pas mettre les champs type separator dans la requête SQL, et tout autre intru qui ferais échoué la requete.
		$this_table_fields_names = get_table_field_names('peel_utilisateurs');
		// Paramètre lié à la fonction get_specific_field_infos.
		foreach($GLOBALS['site_parameters']['user_specific_field_titles'] as $this_field => $this_title) {
			if (!in_array($this_field, $this_table_fields_names)) {
				// Champ pas présent en BDD, on ne l'ajoute pas à la requête SQL.
				continue;
			}
			if (defined('IN_CHANGE_PARAMS') && !empty($GLOBALS['site_parameters']['disable_user_specific_field_on_change_params_page']) && in_array($this_field, $GLOBALS['site_parameters']['disable_user_specific_field_on_change_params_page'])) {
				// Ne pas prendre en compte les champs absents de la page de modification de paramètre.
				continue;
			}
			if (is_array($frm[$this_field])) {
				// Si $frm[$this_field] est un tableau, il faut le convertir en chaine de caractères pour le stockage en BDD
				$frm[$this_field] = implode(',', $frm[$this_field]);
			}
			if ($GLOBALS['site_parameters']['user_specific_field_types'][$this_field] == 'datepicker') {
				$frm[$this_field] = get_mysql_date_from_user_input($frm[$this_field]);
			}
			$user_specific_field[] = $this_field . ' = "' . nohtml_real_escape_string($frm[$this_field]) . '"';
		}
	}
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
	// MAJ du pseudo interdite pour les sites d'annonces en front office, pour éviter problèmes de traçabilité d'utilisateurs
	$sql = "UPDATE peel_utilisateurs SET
			civilite = '" . nohtml_real_escape_string(vb($frm['civilite'])) . "'
			, prenom = '" . nohtml_real_escape_string($frm['prenom']) . "'
			" . (isset($frm['pseudo']) && (!check_if_module_active('annonces') || (check_if_module_active('annonces') && !defined('IN_PEEL_ADMIN')))?", pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "'":"") . "
			, nom_famille = '" . nohtml_real_escape_string($frm['nom_famille']) . "'
			" . (isset($frm['societe'])?", societe = '" . nohtml_real_escape_string($frm['societe']) . "'":"") . "
			, intracom_for_billing  = '" . nohtml_real_escape_string(String::strtoupper(vb($frm['intracom_for_billing']))) . "'
			" . (isset($frm['telephone'])?", telephone = '" . nohtml_real_escape_string(vb($frm['telephone'])) . "'":"") . "
			" . (isset($frm['fax'])?", fax = '" . nohtml_real_escape_string(vb($frm['fax'])) . "'":"") . "
			" . (isset($frm['portable'])?", portable = '" . nohtml_real_escape_string(vb($frm['portable'])) . "'":"") . "
			" . (isset($frm['adresse'])?", adresse = '" . nohtml_real_escape_string($frm['adresse']) . "'":"") . "
			" . (isset($frm['code_postal'])?", code_postal = '" . nohtml_real_escape_string($frm['code_postal']) . "'":"") . "
			, ville = '" . nohtml_real_escape_string($frm['ville']) . "'
			, pays = '" . intval($frm['pays']) . "'
			, newsletter = '" . intval(vn($frm['newsletter'])) . "'
			, commercial = '" . intval(vn($frm['commercial'])) . "'
			, format = '" . nohtml_real_escape_string(vb($GLOBALS['site_parameters']['email_sending_format_default'], 'html')) . "'
			, date_update = '" . date('Y-m-d H:i:s', time()) . "'
			" . (!empty($frm['email'])?", email = '" . nohtml_real_escape_string($frm['email']) . "'":"") . "
			" . (!empty($frm['email'])?", email_bounce = ''":"") . "
			" . (!empty($frm['activity'])?", activity = '" . nohtml_real_escape_string($frm['activity']) . "'":"") . "
			" . (isset($frm['etat'])?", etat = '" . nohtml_real_escape_string($frm['etat']) . "'":"") . "
			" . (isset($frm['type'])?", type = '" . nohtml_real_escape_string($frm['type']) . "'":"") . "
			" . (isset($frm['note_administrateur'])?", note_administrateur = '" . intval($frm['note_administrateur']) . "'":"") . "
			" . (isset($frm['control_plus'])?", control_plus = '" . intval($frm['control_plus']) . "'":"") . "
			" . (isset($frm['fonction'])?", fonction = '" . nohtml_real_escape_string($frm['fonction']) . "'":"") . "
			" . (isset($frm['code_client'])?", code_client = '" . nohtml_real_escape_string($frm['code_client']) . "'":"") . "
			" . (isset($priv)?", priv = '" . nohtml_real_escape_string($priv) . "'":"") . "
			" . (isset($frm['remise_percent'])?", remise_percent = '" . nohtml_real_escape_string(floatval($frm['remise_percent'])) . "'":"") . "
			" . (isset($frm['points'])?", points = '" . intval($frm['points']) . "'":"") . "
			" . (isset($frm['siret'])?", siret = '" . nohtml_real_escape_string($frm['siret']) . "'":"") . "
			" . (isset($frm['ape'])?", ape = '" . nohtml_real_escape_string($frm['ape']) . "'":"") . "
			" . (isset($frm['code_banque'])?", code_banque = '" . nohtml_real_escape_string($frm['code_banque']) . "'":"") . "
			" . (isset($frm['code_guichet'])?", code_guichet = '" . nohtml_real_escape_string($frm['code_guichet']) . "'":"") . "
			" . (isset($frm['numero_compte'])?", numero_compte = '" . nohtml_real_escape_string($frm['numero_compte']) . "'":"") . "
			" . (isset($frm['cle_rib'])?", cle_rib = '" . nohtml_real_escape_string($frm['cle_rib']) . "'":"") . "
			" . (isset($frm['domiciliation'])?", domiciliation = '" . nohtml_real_escape_string($frm['domiciliation']) . "'":"") . "
			" . (isset($frm['iban'])?", iban = '" . nohtml_real_escape_string($frm['iban']) . "'":"") . "
			" . (isset($frm['bic'])?", bic = '" . nohtml_real_escape_string($frm['bic']) . "'":"") . "
			" . (isset($frm['url'])?", url = '" . nohtml_real_escape_string($frm['url']) . "'":"") . "
			" . (isset($frm['description'])?", description = '" . real_escape_string($frm['description']) . "'":"") . "
			" . (isset($frm['avoir'])?", avoir = '" . nohtml_real_escape_string(vn($frm['avoir'])) . "'":"") . "
			" . (isset($frm['naissance'])?", naissance = '" . nohtml_real_escape_string(get_mysql_date_from_user_input(vb($frm['naissance']))) . "'":"") . "
			" . (isset($frm['on_vacances_date'])?", on_vacances_date = '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['on_vacances_date'])) . "'":"") . "
			, on_vacances = '" . intval(vn($frm['on_vacances'])) . "'
			" . (isset($frm['id_groupe'])?", id_groupe = '" . intval(vn($frm['id_groupe'])) . "'":"") . "
            , origin = '" . nohtml_real_escape_string(vb($frm['origin'])) . "'
            , origin_other = '" . nohtml_real_escape_string(vb($frm['origin_other'])) . "'
			, lang = '" . nohtml_real_escape_string(vb($frm['lang'])) . "'
			, project_budget_ht = '" . nohtml_real_escape_string(vb($frm['project_budget_ht'])) . "'
			, project_chances_estimated = '" . nohtml_real_escape_string(vb($frm['project_chances_estimated'])) . "'
			" . (isset($frm['type'])?", type = '" . nohtml_real_escape_string(vb($frm['type'])) . "'":"") . "
            , seg_who = '" . nohtml_real_escape_string(vb($frm['seg_who'])) . "'
			, seg_want = '" . nohtml_real_escape_string(vb($frm['seg_want'])) . "'
			, seg_think = '" . nohtml_real_escape_string(vb($frm['seg_think'])) . "'
			, seg_followed = '" . nohtml_real_escape_string(vb($frm['seg_followed'])) . "'
			, seg_buy = '" . nohtml_real_escape_string(vb($frm['seg_buy'])) . "'
			, project_product_proposed = '" . nohtml_real_escape_string(vb($frm['project_product_proposed'])) . "'
			, project_date_forecasted = '" . nohtml_real_escape_string(get_mysql_date_from_user_input(vb($frm['project_date_forecasted']))) . "'
			" . (isset($frm['url'])?", url = '" . nohtml_real_escape_string(vb($frm['url'])) . "'":"") . "
			" . (isset($frm['id_categories'])?", id_categories = '" . implode(',', nohtml_real_escape_string($frm['id_categories'])) . "'":"") . "
			" . (isset($frm['id_cat_1'])?", id_cat_1 = '" . nohtml_real_escape_string(vb($frm['id_cat_1'])) . "'":"") . "
			" . (isset($frm['id_cat_2'])?", id_cat_2 = '" . nohtml_real_escape_string(vb($frm['id_cat_2'])) . "'":"") . "
			" . (isset($frm['id_cat_3'])?", id_cat_3 = '" . nohtml_real_escape_string(vb($frm['id_cat_3'])) . "'":"") . "
			" . (isset($frm['commercial_contact_id'])?", commercial_contact_id = '" . intval(vn($frm['commercial_contact_id'])) . "'":"") . "
			, on_client_module = '" . intval(vn($frm['on_client_module'])) . "'
			, on_photodesk = '" . intval(vn($frm['on_photodesk'])) . "'
			, site_id = '" . intval(vn($frm['site_id'])) . "'
			, description_document =  '" . nohtml_real_escape_string(vb($frm['description_document'])) . "'
			" . (!empty($frm['document'])? ", document =  '" . nohtml_real_escape_string($frm['document']) . "'" : "") . "
			" . (!empty($frm['logo'])? ", logo =  '" . nohtml_real_escape_string($frm['logo']) . "'" : "") . "
			" . (isset($frm['devise'])? ", devise =  '" . nohtml_real_escape_string($frm['devise']) . "'" : "") . "
			" . (isset($frm['site_country'])? ", site_country =  '" . nohtml_real_escape_string($frm['site_country']) . "'" : "") . "
			" . (check_if_module_active('maps')?", address_hash = ''" : "") . "
			" . (!empty($user_specific_field)? "," . implode(',', $user_specific_field) : "") . "
		WHERE id_utilisateur = '" . intval($frm['id_utilisateur']) . "'";
		query($sql);
		
	if ($update_current_session) {
		// Mise à jour de la session en cours
		$requete = "SELECT *
			FROM peel_utilisateurs
			WHERE id_utilisateur = '" . intval($frm['id_utilisateur']) . "' AND " . get_filter_site_cond('utilisateurs') . "";
		$qid = query($requete);
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
		if (file_exists($GLOBALS['dirroot'] . "/modules/bounces/bounces_driver.php")) {
			include_once($GLOBALS['dirroot'] . "/modules/bounces/bounces_driver.php");
			resolve_bounce($frm['id_utilisateur'], $frm['email']);
		}
	}
	if (check_if_module_active('wanewsletter')) {
		maj_wa_utilisateur($frm);
	}
	if (check_if_module_active('vitrine')) {
		create_or_update_vitrine($frm);
	}

	if (!empty($frm['comments'])) {
		create_or_update_comments($frm);
	}
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
	if (check_if_module_active('vitrine')) {
		$frm['id_utilisateur'] = $id_utilisateur;
		delete_vitrine_admin($frm);
	}
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

		$custom_template_tags['LINK'] = $GLOBALS['wwwroot'] . '/utilisateurs/oubli_mot_passe.php?hash=' . $hash . '&time=' . $timestamp . '&email=' . $email;
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
 * @param mixed $password_length_if_given_as_first_password_hash
 * @return
 */
function user_login_now($email_or_pseudo, $mot_passe, $check_password = true, $password_given_as_first_password_hash = false, $password_length_if_given_as_first_password_hash = null)
{
	if (empty($_SESSION['session_login_tried'])) {
		$_SESSION['session_login_tried'] = 0;
	}
	$_SESSION['session_login_tried']++;
	if ($_SESSION['session_login_tried'] < 30) {
		// Limitation à 30 tentatives de login dans la même session
		$utilisateur = verifier_authentification(trim($email_or_pseudo), trim($mot_passe), null, $check_password, $password_given_as_first_password_hash, $password_length_if_given_as_first_password_hash);
		if ($utilisateur) {
			$_SESSION['session_utilisateur'] = $utilisateur;
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
			if (!empty($_SESSION['session_utilisateur']['devise'])) {
				set_current_devise($_SESSION['session_utilisateur']['devise']);
			}
			if (!empty($_SESSION['session_utilisateur']['site_country'])) {
				$_SESSION['session_site_country'] = intval($_SESSION['session_utilisateur']['site_country']);
			}
			if (a_priv('admin*')) {
				// On met à jour les appels de clients dont l'heure de cloture n'est pas précisée
				// NB : On pourrait faire cela par cron plutôt qu'ici, mais sans cron c'est plus facilement gérable
				updateTelContactNotClosed();
				// On avertit le contact boutique du login d'un administrateur
				$custom_template_tags['USER'] = $email_or_pseudo;
				$custom_template_tags['REVERSE_DNS'] = gethostbyaddr(vb($_SERVER['REMOTE_ADDR']));
				send_email($GLOBALS['support_sav_client'], '', '', 'admin_login', $custom_template_tags, null, $GLOBALS['support'], true, false, true, $GLOBALS['support']);
			}
			// On enregistre la connexion de l'utilisateur
			if (!empty($_SESSION['session_utilisateur']['pseudo'])) {
				$user_pseudo = $_SESSION['session_utilisateur']['pseudo'];
			} else {
				$user_pseudo = $_SESSION['session_utilisateur']['email'];
			}

			query('INSERT INTO peel_utilisateur_connexions (user_id, user_login, user_ip, date, site_id)
				VALUES (' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . ', "' . nohtml_real_escape_string($user_pseudo) . '", "' . ip2long(ipget()) . '", "' . date('Y-m-d H:i:s', time()) . '",  "' . intval($GLOBALS['site_id']) . '")');

			$_SESSION['session_login_tried'] = 0;
		}
		return $utilisateur;
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
 * @param mixed $password_length_if_given_as_first_password_hash
 * @return
 */
function verifier_authentification($email_or_pseudo, $mot_passe, $user_id = null, $check_password = true, $password_given_as_first_password_hash = false, $password_length_if_given_as_first_password_hash = null)
{
	$requete = "SELECT *
		FROM peel_utilisateurs
		WHERE etat=1 AND " . get_filter_site_cond('utilisateurs') . " AND priv!='newsletter' AND ";
	if (!empty($email_or_pseudo)) {
		$requete .= "(email='" . nohtml_real_escape_string($email_or_pseudo) . "' OR pseudo ='" . nohtml_real_escape_string($email_or_pseudo) . "')";
	} else {
		$requete .= "id_utilisateur='" . intval($user_id) . "'";
	}
	$qid = query($requete);
	$user_infos = fetch_assoc($qid);
	if (!empty($user_infos) && (!$check_password || get_user_password_hash($mot_passe, $user_infos['mot_passe'], $password_given_as_first_password_hash, $password_length_if_given_as_first_password_hash))) {
		if(!$check_password && String::strpos($user_infos['priv'], 'admin') === 0) {
			// Utilisateur avec droits d'administration, loggué via processus simplifié => on désactive les droits d'administration
			$user_infos['priv'] = 'util';
		}
		return $user_infos;
	} else {
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
 * @return
 */
function send_mail_for_account_creation($email, $mot_passe)
{
	$custom_template_tags['EMAIL'] = $email;
	$custom_template_tags['MOT_PASSE'] = $mot_passe;
	$result = send_email($email, '', '', 'send_mail_for_account_creation', $custom_template_tags, null, $GLOBALS['support_sav_client']);
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
 * @return
 */
function get_user_information($user_id = null, $get_full_infos = false)
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
		if (!isset($result_array[$cache_id])) {
			$qid = query("SELECT *
				FROM peel_utilisateurs
				WHERE id_utilisateur = '" . intval($user_id) . "' AND " . get_filter_site_cond('utilisateurs', null, defined('IN_PEEL_ADMIN')) . "" . $sql_cond);
			$result_array[$cache_id] = fetch_assoc($qid);
			if($get_full_infos && check_if_module_active('annonces')) {
				if($result_array[$cache_id]['etat']) {
					$sqlCount = 'SELECT COUNT(*) AS this_count
						FROM peel_lot_vente plv
						WHERE id_personne = "' . intval($user_id) . '" AND enligne="OK"';
					$resCount = query($sqlCount);
					if ($Count = fetch_assoc($resCount)) {
						$result_array[$cache_id]['active_ads_count'] = $Count['this_count'];
					}
				} else {
					$result_array[$cache_id]['active_ads_count'] = 0;
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
function get_current_user_promotion_percentage()
{
	if(empty($_SESSION['session_utilisateur']['calculated_promotion_percentage'])) {
		$percent_remise_groupe = 0;
		if (!empty($_SESSION['session_utilisateur']) && !empty($_SESSION['session_utilisateur']['id_groupe']) && check_if_module_active('groups')) {
			// Gestion des remises par groupes d'utilisateurs
			$sqlGroupe = "SELECT remise
				FROM peel_groupes
				WHERE id = '" . intval(vn($_SESSION['session_utilisateur']['id_groupe'])) . "' AND  " . get_filter_site_cond('groupes') . "";
			$resGroupe = query($sqlGroupe);
			if ($Groupe = fetch_object($resGroupe)) {
				$percent_remise_groupe = $Groupe->remise;
			}
		}
		$user_specific_discount = vn($_SESSION['session_utilisateur']['remise_percent']);
		if(!empty($GLOBALS['site_parameters']['group_and_user_discount_cumulate_disable'])) {
			$_SESSION['session_utilisateur']['calculated_promotion_percentage'] = max($user_specific_discount, $percent_remise_groupe);
		} else {
			$_SESSION['session_utilisateur']['calculated_promotion_percentage'] = (1 - (1 - $user_specific_discount / 100) * (1 - $percent_remise_groupe / 100)) * 100;
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
			if (!empty($GLOBALS['site_parameters']['pays_exoneration_tva']) && String::strlen($GLOBALS['site_parameters']['pays_exoneration_tva'])==2 && !is_numeric(String::substr($user_infos['intracom_for_billing'], 0, 2)) && String::substr(String::strtoupper($user_infos['intracom_for_billing']), 0, 2) != $GLOBALS['site_parameters']['pays_exoneration_tva'] && String::strlen($user_infos['intracom_for_billing']) >= 7 && String::strlen(str_replace(' ', '', $user_infos['intracom_for_billing'])) <= 14) {
				// Utilisateur avec un n° de TVA intracom, en Europe mais pas en France
				return true;
			}
		}
	}
	return false;
}

/**
 * get_priv_options()
 *
 * @param integer $preselectionne
 * @param boolean $return_mode
 * @return
 */
function get_priv_options($preselectionne, $return_mode = false)
{
	$output = '';
	$resProfil = query("SELECT *, name_".$_SESSION['session_langue']." AS name
		FROM peel_profil
		WHERE " . get_filter_site_cond('profil', null, true) . "");
	$tpl = $GLOBALS['tplEngine']->createTemplate('priv_options.tpl');
	$tpl_options = array();
	if (num_rows($resProfil)) {
		while ($Profil = fetch_assoc($resProfil)) {
			$tpl_options[] = array(
				'value' => $Profil['priv'],
				'issel' => ($Profil['priv'] == $preselectionne),
				'name' => $Profil['name']
			);
		}
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();

	if ($return_mode) {
		return $output;
	} else {
		echo $output;
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
	$sql_trader = 'SELECT u.id_utilisateur, u.nom_famille , u.prenom
		FROM peel_utilisateurs u
		WHERE u.priv LIKE "admin%" AND ' . get_filter_site_cond('utilisateurs', 'u') . ' ' . $sql_condition . '
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


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: display_user_forms.php 39495 2014-01-14 11:08:09Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

if (!function_exists('get_specific_field_infos')) {
	/**
	 * get_specific_field_infos()
	 * Permet de définir de nouveaux champs dans le formulaire d'inscription/modification d'utilisateur depuis le back office (page "variable de configuration").
	 *
	 * @param array $frm Array with all fields data
	 * @param class $form_error_object
	 * @return
	 *
	 */
	function get_specific_field_infos($frm, $form_error_object = null) {
		$specific_fields = array();
		if(!empty($GLOBALS['site_parameters']['user_specific_field_titles'])) {
			foreach($GLOBALS['site_parameters']['user_specific_field_titles'] as $this_field => $this_title) {
				unset($tpl_options);
				if(!empty($GLOBALS['site_parameters']['user_specific_field_values'][$this_field])) {
					$this_field_values = explode(',', $GLOBALS['site_parameters']['user_specific_field_values'][$this_field]);
					$this_field_names = explode(',', $GLOBALS['site_parameters']['user_specific_field_names'][$this_field]);
					$field_type = $GLOBALS['site_parameters']['user_specific_field_types'][$this_field];
	
					if ($field_type == 'checkbox') {
						if (!empty($frm[$this_field])) {
							if (is_array($frm[$this_field])) {
								// Si $frm vient directement du formulaire, les valeurs pour les checkbox sont sous forme de tableau.
								$frm_this_field_values_array = $frm[$this_field];
							} else {
								// pour les checkbox, $frm[$this_field] peux contenir plusieurs valeurs séparées par des virgules si les données viennent de la BDD
								$frm_this_field_values_array = explode(',', $frm[$this_field]);
							}
						}
					} else {
						// Pour les autres champ, $frm[$this_field] contient une valeur unique.
						$frm_this_field_values_array = array(vb($frm[$this_field]));
					}
					foreach($this_field_values as $this_key => $this_value) {
							$tpl_options[] = array('value' => $this_value,
									'issel' => in_array($this_value, $frm_this_field_values_array),
									'name' => $this_field_names[$this_key]
								);
					}
					$specific_fields[] = array('options' => $tpl_options,
							'field_type' => $field_type,
							'field_name' => $this_field,
							'field_title' => $this_title,
							'mandatory_fields' => (!empty($GLOBALS['site_parameters']['user_mandatory_fields'][$this_field])),
							'error_text' => (!empty($form_error_object)?$form_error_object->text($this_field):''),
							'STR_CHOOSE' => $GLOBALS['STR_CHOOSE']
						);
				}
			}
		}
		return $specific_fields;
	}
}

if (!function_exists('get_user_change_params_form')) {
	/**
	 * get_user_change_params_form()
	 *
	 * @param array $frm Array with all fields data
	 * @param class $form_error_object
	 * @return
	 */
	function get_user_change_params_form(&$frm, &$form_error_object)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('user_change_params_form.tpl');
		if ($form_error_object->has_error('token')) {
			$tpl->assign('token_error', $form_error_object->text('token'));
		}
		$tpl->assign('action', get_current_url(false));
		if (is_abonnement_module_active()) {
			$tpl->assign('verified_account_info', get_verified_account_info());
			$tpl->assign('content_rows_info', (is_user_verified($_SESSION['session_utilisateur']['id_utilisateur'])?'disabled="disabled"':''));
		} else {
			$tpl->assign('content_rows_info', '');
		}
		if(String::substr(vb($frm['email_bounce']), 0, 2)=='5.' || empty($frm['email'])){
			// Email vide ou ayant généré une erreur
			$email_form='';
			$domain=explode('@', vb($frm['email']));
			$email_explain= sprintf($GLOBALS['STR_EMAIL_BOUNCE_REPLACE'], vb($domain[1]), vb($frm['email_bounce']), vb($frm['email']));
		}else{
			$email_form=vb($frm['email']);
		}
		$tpl->assign('email', $email_form);
		$tpl->assign('email_explain', vb($email_explain));
		$tpl->assign('email_error', $form_error_object->text('email'));
		$tpl->assign('civilite_mlle_issel', (vb($frm['civilite']) == "Mlle"));
		$tpl->assign('civilite_mme_issel', (vb($frm['civilite']) == "Mme"));
		$tpl->assign('civilite_m_issel', (vb($frm['civilite']) == "M."));
		$tpl->assign('gender_error', $form_error_object->text('civilite'));
		$tpl->assign('pseudo', (isset($frm['pseudo'])?vb($frm['pseudo']):$_SESSION['session_utilisateur']['pseudo']));
		$tpl->assign('pseudo_error', $form_error_object->text('pseudo'));
		$tpl->assign('first_name', vb($frm['prenom']));
		$tpl->assign('first_name_error', $form_error_object->text('prenom'));
		$tpl->assign('name', vb($frm['nom_famille']));
		$tpl->assign('name_error', $form_error_object->text('nom_famille'));
		$tpl->assign('societe', vb($frm['societe']));
		$tpl->assign('societe_error', $form_error_object->text('societe'));
		
		// si le module d'annonce est activé, on renseigne le num d'identification de la société
		// On mentionne le champ obligatoire - en fait on le vérifiera uniquement pour la France
		$siret_txt = $GLOBALS['STR_COMPANY_IDENTIFICATION'] . ' <span class="etoile">*</span>';
		$tpl->assign('siret_txt', $siret_txt);
		$tpl->assign('intracom_form', vb($frm['intracom_for_billing']));
		$tpl->assign('intracom_form_error', $form_error_object->text('intracom_for_billing'));
		$tpl->assign('telephone', vb($frm['telephone']));
		$tpl->assign('telephone_error', $form_error_object->text('telephone'));
		$tpl->assign('portable', vb($frm['portable']));
		$tpl->assign('fax', vb($frm['fax']));
		$tpl->assign('url', vb($frm['url']));
		$tpl->assign('type', vb($frm['type']));
		$tpl->assign('type_error', $form_error_object->text('type'));
		$tpl->assign('activity', vb($frm['activity']));
		$tpl->assign('activity_error', $form_error_object->text('activity'));
		if (vb($frm['naissance']) != "0000-00-00") {
			// Si la date de naissance est définie, on l'affiche et on permet de changer leur date de naissance
			$tpl->assign('birthday_edit', true);
		} else {
			// Décommentez la ligne suivante si vous voulez informer l'utilisateur de demander à l'administrateur pour mettre sa date de naissance
			// $tpl->assign('birthday_show', true);
			// $tpl->assign('birthday_contact_admin', true);
		}
		$tpl->assign('fonction', vb($frm['fonction']));
		$tpl->assign('fonction_error', $form_error_object->text('fonction'));
		if (is_annonce_module_active()) {
			// si le module d'annonce est activé, on confirme le mot de passe
			$tpl->assign('STR_PASSWORD_CONFIRMATION', $GLOBALS['STR_PASSWORD_CONFIRMATION']);
			$tpl->assign('password_confirmation_error', $form_error_object->text('mot_passe_confirm'));
			// si le module d'annonce est activé, on renseigne le num d'identification de la société
			$tpl->assign('siret', vb($frm['siret']));
			$tpl->assign('siret_error', $form_error_object->text('siret'));
			// si le module d'annonce est activé, on renseigne le fax
			$tpl->assign('fax', vb($frm['user_fax']));
			// si le module d'annonce est activé, on renseigne le code promo et catégories préférées
			$tpl->assign('promo_code', vb($frm['promo_code']));
			if (!empty($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) && $GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories'] == 'checkbox') {
				$tpl->assign('favorite_category', get_announcement_select_options(null, vb($frm['id_categories']), 'id', false, false, 'checkbox', 'id_categories'));	
				$tpl->assign('favorite_category_error', $form_error_object->text('favorite_category_error'));
			} else {
				$tpl->assign('id_cat_1_error', $form_error_object->text('id_cat_1'));
				$tpl->assign('id_cat_2_error', $form_error_object->text('id_cat_2'));
				$tpl->assign('id_cat_3_error', $form_error_object->text('id_cat_3'));
				$tpl->assign('favorite_category_1', get_announcement_select_options(null, vb($frm['id_cat_1']), 'id'));
				$tpl->assign('favorite_category_2', get_announcement_select_options(null, vb($frm['id_cat_2']), 'id'));
				$tpl->assign('favorite_category_3', get_announcement_select_options(null, vb($frm['id_cat_3']), 'id'));
			}
			$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
			$tpl->assign('STR_PROMO_CODE', $GLOBALS['STR_PROMO_CODE']);
			$tpl->assign('STR_ANNOUNCEMENT_INDICATION', $GLOBALS['STR_ANNOUNCEMENT_INDICATION']);
			$tpl->assign('STR_FIRST_CHOICE', $GLOBALS['STR_FIRST_CHOICE']);
			$tpl->assign('STR_SECOND_CHOICE', $GLOBALS['STR_SECOND_CHOICE']);
			$tpl->assign('STR_THIRD_CHOICE', $GLOBALS['STR_THIRD_CHOICE']);
		}
		$tpl->assign('naissance', get_formatted_date(vb($frm['naissance'])));
		$tpl->assign('naissance_error', $form_error_object->text('naissance'));
		$tpl->assign('adresse', vb($frm['adresse']));
		$tpl->assign('adresse_error', $form_error_object->text('adresse'));
		$tpl->assign('zip', vb($frm['code_postal']));
		$tpl->assign('zip_error', $form_error_object->text('code_postal'));
		$tpl->assign('town', vb($frm['ville']));
		$tpl->assign('town_error', $form_error_object->text('ville'));
		$tpl->assign('country_options', get_country_select_options(null, $_SESSION['session_utilisateur']['pays'], 'id'));
		$tpl_origin_options = array();
		$i = 1;
		while (isset($GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i])) {
			$tpl_origin_options[] = array('value' => $i,
				'issel' => vb($frm['origin']) == $i,
				'name' => $GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i]
				);
			$i++;
		}
		$tpl->assign('origin_infos', array('options' => $tpl_origin_options,
			'is_origin_other_activated' => in_array(vb($frm['origin']), $GLOBALS['origin_other_ids']),
			'origin_other_ids_for_javascript' => 'new Array('.implode(',', $GLOBALS['origin_other_ids']).')',
			'origin_other' => vb($frm['origin_other']),
			'error_text' => $form_error_object->text('origin'),
			'STR_CHOOSE' => $GLOBALS['STR_CHOOSE']
			));

		$tpl->assign('specific_fields', get_specific_field_infos($frm, $form_error_object));



		// Select permettant de paramétrer la langue par défaut du compte lors de l'envoi d'email
		$sqlLng = "SELECT lang, nom_".$_SESSION['session_langue']." AS nom_lang
			FROM peel_langues
			WHERE etat = '1'" . (!empty($_GET['langue'])?" OR lang='" . word_real_escape_string($_GET['langue']) . "'":'') . "
			" . (!empty($GLOBALS['site_parameters']['language_for_contacts'])? " AND lang IN ('".implode("','", $GLOBALS['site_parameters']['language_for_contacts'])."')" : '' ) . "
			GROUP BY lang
			ORDER BY position";
		$resLng = query($sqlLng);
		$language_for_automatic_emails_options = array();
		$language_for_automatic_emails_selected = null;
		
		if (!empty($frm['lang'])) {
			while ($lng = fetch_assoc($resLng)) {
				$language_for_automatic_emails_options[vb($lng['lang'])] = vb($lng['nom_lang']);
				if ($lng['lang'] == $frm['lang']) {
					$language_for_automatic_emails_selected = vb($lng['lang']);
				}
			}
		}
		$tpl->assign('language_for_automatic_emails_options', $language_for_automatic_emails_options);
		$tpl->assign('language_for_automatic_emails_selected', $language_for_automatic_emails_selected);
		$tpl->assign('newsletter_issel', (!isset($frm['newsletter']) || !empty($frm['newsletter'])));
		$tpl->assign('commercial_issel', (!isset($frm['commercial']) || $frm['commercial']));
		$tpl->assign('token', get_form_token_input('change_params'));
		$tpl->assign('id_utilisateur', $_SESSION['session_utilisateur']['id_utilisateur']);
		$tpl->assign('is_annonce_module_active', is_annonce_module_active());
		$tpl->assign('add_b2b_form_inputs', !empty($GLOBALS['site_parameters']['add_b2b_form_inputs']));
		$tpl->assign('cnil_txt', String::textEncode($GLOBALS['STR_CNIL']));
		$tpl->assign('STR_CHANGE', $GLOBALS['STR_CHANGE']);
		$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
		$tpl->assign('STR_LEADER', $GLOBALS['STR_LEADER']);
		$tpl->assign('STR_MANAGER', $GLOBALS['STR_MANAGER']);
		$tpl->assign('STR_EMPLOYEE', $GLOBALS['STR_EMPLOYEE']);
		$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
		$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
		$tpl->assign('STR_PROMO_CODE', $GLOBALS['STR_PROMO_CODE']);
		$tpl->assign('STR_FONCTION', $GLOBALS['STR_FONCTION']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_CHANGE_PARAMS', $GLOBALS['STR_CHANGE_PARAMS']);
		$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
		$tpl->assign('STR_GENDER', $GLOBALS['STR_GENDER']);
		$tpl->assign('STR_MLLE', $GLOBALS['STR_MLLE']);
		$tpl->assign('STR_MME', $GLOBALS['STR_MME']);
		$tpl->assign('STR_M', $GLOBALS['STR_M']);
		$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
		$tpl->assign('STR_STRONG_PSEUDO_NOTIFICATION', $GLOBALS['STR_STRONG_PSEUDO_NOTIFICATION']);
		$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
		$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
		$tpl->assign('STR_SOCIETE', $GLOBALS['STR_SOCIETE']);
		$tpl->assign('STR_INTRACOM_FORM', $GLOBALS['STR_INTRACOM_FORM']);
		$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
		$tpl->assign('STR_PORTABLE', $GLOBALS['STR_PORTABLE']);
		$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
		$tpl->assign('STR_NAISSANCE', $GLOBALS['STR_NAISSANCE']);
		$tpl->assign('STR_ERR_BIRTHDAY1', $GLOBALS['STR_ERR_BIRTHDAY1']);
		$tpl->assign('STR_ERR_BIRTHDAY2', $GLOBALS['STR_ERR_BIRTHDAY2']);
		$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
		$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
		$tpl->assign('STR_USER_ORIGIN', $GLOBALS['STR_USER_ORIGIN']);
		$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
		$tpl->assign('STR_LANGUAGE_FOR_AUTOMATIC_EMAILS', $GLOBALS['STR_LANGUAGE_FOR_AUTOMATIC_EMAILS']);
		$tpl->assign('STR_NEWSLETTER_YES', $GLOBALS['STR_NEWSLETTER_YES']);
		$tpl->assign('STR_COMMERCIAL_YES', $GLOBALS['STR_COMMERCIAL_YES']);
		$tpl->assign('STR_ACTIVITY', $GLOBALS['STR_ACTIVITY']);
		$tpl->assign('STR_YOU_ARE', $GLOBALS['STR_YOU_ARE']);
		$tpl->assign('STR_BUYERS', $GLOBALS['STR_BUYERS']);
		$tpl->assign('STR_IMPORTERS_EXPORTERS', $GLOBALS['STR_IMPORTERS_EXPORTERS']);
		$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
		$tpl->assign('STR_PURCHASING_MANAGER', $GLOBALS['STR_PURCHASING_MANAGER']);
		$tpl->assign('STR_WORD_SELLERS', $GLOBALS['STR_WORD_SELLERS']);
		$tpl->assign('STR_WHOLESALER', $GLOBALS['STR_WHOLESALER']);
		$tpl->assign('STR_HALF_WHOLESALER', $GLOBALS['STR_HALF_WHOLESALER']);
		$tpl->assign('STR_RETAILERS', $GLOBALS['STR_RETAILERS']);
		$tpl->assign('STR_PUNCTUAL', $GLOBALS['STR_PUNCTUAL']);
		$tpl->assign('STR_RECURRENT', $GLOBALS['STR_RECURRENT']);
		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('get_user_register_form')) {
	/**
	 * get_user_register_form()
	 *
	 * @param array $frm Array with all fields data
	 * @param class $form_error_object
	 * @return
	 */
	function get_user_register_form(&$frm, &$form_error_object)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('user_register_form.tpl');
		$tpl->assign('is_annonce_module_active', is_annonce_module_active());
		$tpl->assign('add_b2b_form_inputs', !empty($GLOBALS['site_parameters']['add_b2b_form_inputs']));
		$tpl->assign('is_societe_mandatory', !empty($GLOBALS['site_parameters']['add_b2b_form_inputs']));
		$tpl->assign('action', get_current_url(false));
		$tpl->assign('email', vb($frm['email']));
		$tpl->assign('email_error', $form_error_object->text('email'));
		$tpl->assign('pseudo', vb($frm['pseudo']));
		$tpl->assign('pseudo_error', $form_error_object->text('pseudo'));
		$tpl->assign('password_error', $form_error_object->text('mot_passe'));
		$tpl->assign('civilite_mlle_issel', (vb($frm['civilite']) == "Mlle"));
		$tpl->assign('civilite_mme_issel', (vb($frm['civilite']) == "Mme"));
		$tpl->assign('civilite_m_issel', (vb($frm['civilite']) == "M."));
		$tpl->assign('gender_error', $form_error_object->text('civilite'));
		$tpl->assign('first_name', vb($frm['prenom']));
		$tpl->assign('first_name_error', $form_error_object->text('prenom'));
		$tpl->assign('name', vb($frm['nom_famille']));
		$tpl->assign('name_error', $form_error_object->text('nom_famille'));
		$tpl->assign('societe', vb($frm['societe']));
		$tpl->assign('societe_error', $form_error_object->text('societe'));
		$tpl->assign('intracom_form', vb($frm['intracom_for_billing']));
		$tpl->assign('intracom_form_error', $form_error_object->text('intracom_for_billing'));
		$tpl->assign('naissance', get_formatted_date(vb($frm['naissance'])));
		$tpl->assign('telephone', vb($frm['telephone']));
		$tpl->assign('telephone_error', $form_error_object->text('telephone'));
		$tpl->assign('portable', vb($frm['portable']));
		$tpl->assign('adresse', vb($frm['adresse']));
		$tpl->assign('adresse_error', $form_error_object->text('adresse'));
		$tpl->assign('zip', vb($frm['code_postal']));
		$tpl->assign('zip_error', $form_error_object->text('code_postal'));
		$tpl->assign('town', vb($frm['ville']));
		$tpl->assign('town_error', $form_error_object->text('ville'));
		$tpl->assign('fonction', vb($frm['fonction']));
		$tpl->assign('fonction_error', $form_error_object->text('fonction'));
		$tpl->assign('country_options', get_country_select_options(null, vb($frm['pays']), 'id'));
		$tpl->assign('type_error', $form_error_object->text('type'));
		$tpl->assign('activity_error', $form_error_object->text('activity'));
		$tpl->assign('type', vb($frm['type']));
		$tpl->assign('activity', vb($frm['activity']));
		$tpl->assign('url', vb($frm['url']));
		if (is_annonce_module_active()) {
			// si le module d'annonce est activé, on confirme le mot de passe
			$tpl->assign('STR_PASSWORD_CONFIRMATION', $GLOBALS['STR_PASSWORD_CONFIRMATION']);
			$tpl->assign('password_confirmation_error', $form_error_object->text('mot_passe_confirm'));
			// si le module d'annonce est activé, on renseigne le num d'identification de la société
			$tpl->assign('siret', vb($frm['siret']));
			$tpl->assign('siret_error', $form_error_object->text('siret'));
			// On mentionne le champ obligatoire - en fait on le vérifiera uniquement pour la France
			$siret_txt = $GLOBALS['STR_COMPANY_IDENTIFICATION'] . ' <span class="etoile">*</span>';
			$tpl->assign('siret_txt', $siret_txt);
			// si le module d'annonce est activé, on renseigne le fax
			$tpl->assign('fax', vb($frm['user_fax']));
			// si le module d'annonce est activé, on renseigne le site web, code promo et catégories promo
			$tpl->assign('promo_code', vb($frm['promo_code']));
			if (!empty($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) && $GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories'] == 'checkbox') {
				$tpl->assign('favorite_category', get_announcement_select_options(null, vb($frm['id_categories']), 'id', false, false, 'checkbox', 'id_categories'));	
				$tpl->assign('favorite_category_error', $form_error_object->text('favorite_category_error'));
			} else {
				$tpl->assign('id_cat_1_error', $form_error_object->text('id_cat_1'));
				$tpl->assign('id_cat_2_error', $form_error_object->text('id_cat_2'));
				$tpl->assign('id_cat_3_error', $form_error_object->text('id_cat_3'));
				$tpl->assign('favorite_category_1', get_announcement_select_options(null, vb($frm['id_cat_1']), 'id'));
				$tpl->assign('favorite_category_2', get_announcement_select_options(null, vb($frm['id_cat_2']), 'id'));
				$tpl->assign('favorite_category_3', get_announcement_select_options(null, vb($frm['id_cat_3']), 'id'));
			}
			$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
			$tpl->assign('STR_PROMO_CODE', $GLOBALS['STR_PROMO_CODE']);
			$tpl->assign('STR_ANNOUNCEMENT_INDICATION', $GLOBALS['STR_ANNOUNCEMENT_INDICATION']);
			$tpl->assign('STR_FIRST_CHOICE', $GLOBALS['STR_FIRST_CHOICE']);
			$tpl->assign('STR_SECOND_CHOICE', $GLOBALS['STR_SECOND_CHOICE']);
			$tpl->assign('STR_THIRD_CHOICE', $GLOBALS['STR_THIRD_CHOICE']);
			$tpl->assign('STR_LANGUAGE_FOR_AUTOMATIC_EMAILS', $GLOBALS['STR_LANGUAGE_FOR_AUTOMATIC_EMAILS']);
		}
		$tpl_origin_options = array();
		$i = 1;
		while (isset($GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i])) {
			$tpl_origin_options[] = array('value' => $i,
				'issel' => vb($frm['origin']) == $i,
				'name' => $GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i]
				);
			$i++;
		}
		$tpl->assign('origin_infos', array('options' => $tpl_origin_options,
			'is_origin_other_activated' => in_array(vb($frm['origin']), $GLOBALS['origin_other_ids']),
			'origin_other_ids_for_javascript' => 'new Array('.implode(',', $GLOBALS['origin_other_ids']).')',
			'origin_other' => vb($frm['origin_other']),
			'error_text' => $form_error_object->text('origin'),
			'STR_CHOOSE' => $GLOBALS['STR_CHOOSE']
			));
		$tpl->assign('specific_fields', get_specific_field_infos($frm, $form_error_object));
		if (is_captcha_module_active()) {
			// L'appel à get_captcha_inside_form($frm) réinitialise la valeur de $frm['code'] si le code donné n'est pas bon, en même temps que générer nouvelle image
			$tpl->assign('captcha', array(
				'validation_code_txt' => $GLOBALS['STR_VALIDATION_CODE'],
				'inside_form' => get_captcha_inside_form($frm),
				'validation_code_copy_txt' => $GLOBALS['STR_VALIDATION_CODE_COPY'],
				'error' => $form_error_object->text('code'),
				'value' => vb($frm['code'])
			));
		}
		if (is_annonce_module_active()) { 
			// si le module d'annonce est activé, on confirme les cgv
			$tpl->assign('cgv_issel', !empty($frm['cgv_confirm']));
			$tpl->assign('STR_CGV_YES', $GLOBALS['STR_CGV_YES']);
			$tpl->assign('cgv_yes_error', $form_error_object->text('cgv_confirm'));
		}
		$tpl->assign('newsletter_issel', (!isset($frm['newsletter']) || !empty($frm['newsletter'])));
		$tpl->assign('newsletter_option_selected', vb($frm['newsletter_format']));
		$tpl->assign('commercial_issel', (!isset($frm['commercial']) || $frm['commercial']));
		$tpl->assign('cnil_txt', String::textEncode($GLOBALS['STR_CNIL']));
		$tpl->assign('token', get_form_token_input('get_user_register_form', true));
		$tpl->assign('js_password_control', js_password_control('mot_passe'));
		$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
		$tpl->assign('STR_LEADER', $GLOBALS['STR_LEADER']);
		$tpl->assign('STR_MANAGER', $GLOBALS['STR_MANAGER']);
		$tpl->assign('STR_EMPLOYEE', $GLOBALS['STR_EMPLOYEE']);
		$tpl->assign('STR_FIRST_REGISTER_TITLE', $GLOBALS['STR_FIRST_REGISTER_TITLE']);
		$tpl->assign('STR_FIRST_REGISTER_TEXT', $GLOBALS['STR_FIRST_REGISTER_TEXT']);
		$tpl->assign('STR_OPEN_ACCOUNT', $GLOBALS['STR_OPEN_ACCOUNT']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
		$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
		$tpl->assign('STR_STRONG_PSEUDO_NOTIFICATION', $GLOBALS['STR_STRONG_PSEUDO_NOTIFICATION']);
		$tpl->assign('STR_PASSWORD', $GLOBALS['STR_PASSWORD']);
		$tpl->assign('STR_PASSWORD_SECURITY', $GLOBALS['STR_PASSWORD_SECURITY']);
		$tpl->assign('STR_STRONG_PASSWORD_NOTIFICATION', $GLOBALS['STR_STRONG_PASSWORD_NOTIFICATION']);
		$tpl->assign('STR_GENDER', $GLOBALS['STR_GENDER']);
		$tpl->assign('STR_MLLE', $GLOBALS['STR_MLLE']);
		$tpl->assign('STR_MME', $GLOBALS['STR_MME']);
		$tpl->assign('STR_M', $GLOBALS['STR_M']);
		$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
		$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
		$tpl->assign('STR_SOCIETE', $GLOBALS['STR_SOCIETE']);
		$tpl->assign('STR_INTRACOM_FORM', $GLOBALS['STR_INTRACOM_FORM']);
		$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
		$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
		$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
		$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
		$tpl->assign('STR_PORTABLE', $GLOBALS['STR_PORTABLE']);
		$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
		$tpl->assign('STR_NAISSANCE', $GLOBALS['STR_NAISSANCE']);
		$tpl->assign('STR_MANDATORY', $GLOBALS['STR_MANDATORY']);
		$tpl->assign('STR_USER_ORIGIN', $GLOBALS['STR_USER_ORIGIN']);
		$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
		$tpl->assign('STR_NEWSLETTER_YES', $GLOBALS['STR_NEWSLETTER_YES']);
		$tpl->assign('STR_COMMERCIAL_YES', $GLOBALS['STR_COMMERCIAL_YES']);
		$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
		$tpl->assign('STR_FONCTION', $GLOBALS['STR_FONCTION']);
		$tpl->assign('STR_ACTIVITY', $GLOBALS['STR_ACTIVITY']);
		$tpl->assign('STR_YOU_ARE', $GLOBALS['STR_YOU_ARE']);
		$tpl->assign('STR_BUYERS', $GLOBALS['STR_BUYERS']);
		$tpl->assign('STR_IMPORTERS_EXPORTERS', $GLOBALS['STR_IMPORTERS_EXPORTERS']);
		$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
		$tpl->assign('STR_PURCHASING_MANAGER', $GLOBALS['STR_PURCHASING_MANAGER']);
		$tpl->assign('STR_WORD_SELLERS', $GLOBALS['STR_WORD_SELLERS']);
		$tpl->assign('STR_WHOLESALER', $GLOBALS['STR_WHOLESALER']);
		$tpl->assign('STR_HALF_WHOLESALER', $GLOBALS['STR_HALF_WHOLESALER']);
		$tpl->assign('STR_RETAILERS', $GLOBALS['STR_RETAILERS']);
		$tpl->assign('STR_PUNCTUAL', $GLOBALS['STR_PUNCTUAL']);
		$tpl->assign('STR_RECURRENT', $GLOBALS['STR_RECURRENT']);
		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('get_user_register_success')) {
	/**
	 * get_user_register_success()
	 *
	 * @param array $frm Array with all fields data
	 * @return
	 */
	function get_user_register_success(&$frm)
	{
		$output = '
<h1 class="page_title">' . $GLOBALS['STR_HELLO'] . ' ' . String::html_entity_decode_if_needed($frm['prenom']) . '</h1>';
		if ($frm['priv']=='stop') {
			$output .= '<p>' . nl2br($GLOBALS['STR_MODULE_PREMIUM_MSG_RETAILER']) . '</p>';
		} else  {
			$output .= '
<p>' . nl2br(String::textEncode($GLOBALS['STR_LOGIN_OK'])) . '</p>';
		}
		$output .= '
<p>' . $GLOBALS['STR_EMAIL'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': <b>' . $frm['email'] . '</b></p>
<p>' . $GLOBALS['STR_PASSWORD'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': <b>' . $frm['mot_passe'] . '</b></p>
';
		return $output;
	}
}

if (!function_exists('get_change_password_form')) {
	/**
	 * get_change_password_form()
	 *
	 * @param array $frm
	 * @param class $form_error_object
	 * @param string $noticemsg
	 * @return
	 */
	function get_change_password_form(&$frm, &$form_error_object, $noticemsg = null)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('change_password_form.tpl');
		$tpl->assign('change_password', $GLOBALS['STR_CHANGE_PASSWORD']);
		if ($form_error_object->has_error('token')) {
			$tpl->assign('token_error', $form_error_object->text('token'));
		}
		$tpl->assign('noticemsg', $noticemsg);
		$tpl->assign('action', get_current_url(false));
		$tpl->assign('old_password', vb($frm['ancien_mot_passe']));
		$tpl->assign('old_password_error', $form_error_object->text('ancien_mot_passe'));
		$tpl->assign('old_password_error2', $form_error_object->text('ancien_mot_passe2'));
		$tpl->assign('new_password', vb($frm['nouveau_mot_passe']));
		$tpl->assign('new_password_error', $form_error_object->text('nouveau_mot_passe'));
		$tpl->assign('new_password_confirm', vb($frm['nouveau_mot_passe2']));
		$tpl->assign('new_password_confirm_error', $form_error_object->text('nouveau_mot_passe2'));
		$tpl->assign('token', get_form_token_input('change_password'));
		$tpl->assign('js_password_control', js_password_control('nouveau_mot_passe'));
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_OLD_PASSWORD', $GLOBALS['STR_OLD_PASSWORD']);
		$tpl->assign('STR_NEW_PASSWORD', $GLOBALS['STR_NEW_PASSWORD']);
		$tpl->assign('STR_STRONG_PASSWORD_NOTIFICATION', $GLOBALS['STR_STRONG_PASSWORD_NOTIFICATION']);
		$tpl->assign('STR_NEW_PASSWORD_CONFIRM', $GLOBALS['STR_NEW_PASSWORD_CONFIRM']);
		$tpl->assign('STR_CHANGE', $GLOBALS['STR_CHANGE']);
		$tpl->assign('STR_EMPTY_FIELDS', $GLOBALS['STR_EMPTY_FIELDS']);
		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('get_recover_password_form')) {
	/**
	 * get_recover_password_form()
	 *
	 * @param array $frm Array with all fields data
	 * @param class $form_error_object
	 * @return
	 */

	function get_recover_password_form(&$frm, &$form_error_object, $mode = 'filing_email')
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('recover_password_form.tpl');
		$tpl->assign('get_password', $GLOBALS['STR_GET_PASSWORD']);
		$tpl->assign('action', $_SERVER['REQUEST_URI']);
		$tpl->assign('STR_SEND', $GLOBALS['STR_SEND']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('token', get_form_token_input('oubli_mot_passe', true));
		$tpl->assign('login_href', $GLOBALS['wwwroot'] . '/membre.php');
		$tpl->assign('login_txt', $GLOBALS['STR_LOGIN']);
		$tpl->assign('home_href', $GLOBALS['wwwroot']);
		$tpl->assign('home_txt', $GLOBALS['STR_HOME']);
		$tpl->assign('js_password_control', js_password_control('rec_password_once'));
		if ($mode == 'filing_email') {
			$tpl->assign('email', array(
				'msg_insert' => $GLOBALS['STR_INSERT_EMAIL'],
				'label' => $GLOBALS['STR_EMAIL'] . $GLOBALS['STR_BEFORE_TWO_POINTS'],
				'value' => vb($frm['email']),
				'error' => $form_error_object->text('email')
			));
		}elseif ($mode == 'renew_password') {
			$tpl->assign('pass', array(
				'empty_field_error' => $form_error_object->text('empty_field'),
				'mismatch_password_error' => $form_error_object->text('mismatch_password'),
				'msg_insert_new_password' => $GLOBALS['STR_INSERT_NEW_PASSWORD'],
				'STR_NEW_PASSWORD' => $GLOBALS['STR_NEW_PASSWORD'],
				'STR_BEFORE_TWO_POINTS' => $GLOBALS['STR_BEFORE_TWO_POINTS'],
				'password_once' => vb($frm['password_once']),
				'password_once_error' => $form_error_object->text('password_once'),
				'STR_STRONG_PASSWORD_NOTIFICATION' => $GLOBALS['STR_STRONG_PASSWORD_NOTIFICATION'],
				'STR_NEW_PASSWORD_CONFIRM' => $GLOBALS['STR_NEW_PASSWORD_CONFIRM'],
				'password_twice' => vb($frm['password_twice']),
				'password_twice_error' => $form_error_object->text('password_twice'),
			));
		}
		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('get_access_account_form')) {
	/**
	 * get_access_account_form()
	 *
	 * @param array $frm Array with all fields data
	 * @param class $form_error_object
	 * @param string $forced_new_client_area_html
	 * @return
	 */
	function get_access_account_form(&$frm, &$form_error_object, $forced_new_client_area_html = null)
	{
		$output = '';
		if(empty($forced_new_client_area_html)){
			$forced_new_client_area_html = '' . nl2br($GLOBALS['STR_MSG_NEW_CUSTOMER']) . '<br />';
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('access_account_form.tpl');
		$tpl->assign('acces_account_txt', $GLOBALS['STR_ACCES_ACCOUNT']);
		$tpl->assign('new_customer', $GLOBALS['STR_NEW_CUSTOMER']);
		$tpl->assign('msg_new_customer', $forced_new_client_area_html);
		$tpl->assign('still_customer', $GLOBALS['STR_STILL_CUSTOMER']);
		$tpl->assign('msg_still_customer', $GLOBALS['STR_MSG_STILL_CUSTOMER']);
		$tpl->assign('pass_perdu_txt', $GLOBALS['STR_PASS_PERDU']);
		$tpl->assign('pass_perdu_href', $GLOBALS['wwwroot'] . '/utilisateurs/oubli_mot_passe.php');
		$tpl->assign('email_or_pseudo', $GLOBALS['STR_EMAIL_OR_PSEUDO'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('email', vb($frm['email']));
		$tpl->assign('email_error', $form_error_object->text('email'));
		$tpl->assign('STR_PASSWORD', $GLOBALS['STR_PASSWORD'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('password', vb($frm['mot_passe']));
		$tpl->assign('password_error', $form_error_object->text('mot_passe'));
		$tpl->assign('token', get_form_token_input('membre.php', true));
		$tpl->assign('login_txt', $GLOBALS['STR_LOGIN']);
		if (function_exists('get_social_icone')) {
			$tpl->assign('social_icone', get_social_icone());
		}
		$social = array('is_any' => false);
		if (is_facebook_connect_module_active()) {
			$social['is_any'] = true;
			$social['facebook'] = get_facebook_connect_btn();
		}
		if (is_sign_in_twitter_module_active()) {
			$social['is_any'] = true;
			$social['twitter'] = get_sign_in_twitter_btn();
		}
		if (is_openid_module_active()) {
			$social['is_any'] = true;
			$social['openid'] = get_openid_btn();
		}
		$tpl->assign('social', $social);
		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('get_contact_form')) {
	/**
	 * get_contact_form()
	 *
	 * @param array $frm Array with all fields data
	 * @param class $form_error_object
	 * @return
	 */
	function get_contact_form(&$frm, &$form_error_object)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('contact_form.tpl');
		$tpl->assign('short_form', !empty($GLOBALS['site_parameters']['contact_form_short_mode']));
		if ($form_error_object->has_error('token')) {
			$tpl->assign('token_error', $form_error_object->text('token'));
		}
		if (!empty($frm['is_ok'])) {
			$tpl->assign('success_msg', $GLOBALS['STR_TICKET_OK']);
		}
		$tpl->assign('contact_info', (!function_exists('get_details_societe') ? affiche_contenu_html("contact_page", true) : get_details_societe()));
		$tpl->assign('action', get_current_url(false).(!empty($GLOBALS['main_div_id'])?'?ctx='.$GLOBALS['main_div_id']:''));
		$tpl->assign('extra_field', get_contact_extra_field());
		$tpl->assign('sujet_options', array(
			'' => $GLOBALS['STR_CONTACT_LB'],
			$GLOBALS['STR_CONTACT_SELECT1'] => $GLOBALS['STR_CONTACT_SELECT1'],
			$GLOBALS['STR_CONTACT_SELECT2'] => $GLOBALS['STR_CONTACT_SELECT2'],
			$GLOBALS['STR_CONTACT_SELECT3'] => $GLOBALS['STR_CONTACT_SELECT3']
		));
		$tpl->assign('sujet_options_selected', vb($frm['sujet']));
		$tpl->assign('sujet_error', $form_error_object->text('sujet'));
		
		$tpl->assign('commande_id', vb($frm['commande_id']));
		$tpl->assign('commande_error', $form_error_object->text('commande_id'));
		$tpl->assign('email_value', vb($frm['email']));
		$tpl->assign('email_error', $form_error_object->text('email'));
		$tpl->assign('name_value', vb($frm['nom']));
		$tpl->assign('name_error', $form_error_object->text('nom'));
		
		$tpl->assign('societe_value', vb($frm['societe']));
		$tpl->assign('societe_error', $form_error_object->text('societe'));
		$tpl->assign('first_name_value', vb($frm['prenom']));
		$tpl->assign('first_name_error', $form_error_object->text('prenom'));
		$tpl->assign('address_value', vb($frm['adresse']));
		$tpl->assign('zip_value', vb($frm['code_postal']));
		$tpl->assign('town_value', vb($frm['ville']));
		$tpl->assign('country_value', vb($frm['pays']));
		$tpl->assign('telephone_value', vb($frm['telephone']));
		$tpl->assign('telephone_error', $form_error_object->text('telephone'));
		$tpl->assign('texte_value', vb($frm['texte']));
		$tpl->assign('texte_error', $form_error_object->text('texte'));
		$tpl->assign('STR_DISPO', $GLOBALS['STR_DISPO']);
		
		if (is_captcha_module_active()) {
			// L'appel à get_captcha_inside_form($frm) réinitialise la valeur de $frm['code'] si le code donné n'est pas bon, en même temps que générer nouvelle image
			$tpl->assign('captcha', array(
				'validation_code_txt' => $GLOBALS['STR_VALIDATION_CODE'],
				'inside_form' => get_captcha_inside_form($frm),
				'validation_code_copy_txt' => $GLOBALS['STR_VALIDATION_CODE_COPY'],
				'error' => $form_error_object->text('code'),
				'value' => vb($frm['code'])
			));
		}
		$tpl->assign('align', (!empty($GLOBALS['site_parameters']['contact_form_align']) ? $GLOBALS['site_parameters']['contact_form_align'] : 'left'));
		$tpl->assign('token', get_form_token_input('user_contact'));
		$tpl->assign('href', get_current_url(false));
		$tpl->assign('STR_SEND', $GLOBALS['STR_SEND']);
		$tpl->assign('cnil_txt', String::textEncode($GLOBALS['STR_CNIL']));
		$tpl->assign('STR_CONTACT', $GLOBALS['STR_CONTACT']);
		$tpl->assign('STR_CONTACT_INTRO', $GLOBALS['STR_CONTACT_INTRO']);
		$tpl->assign('STR_CONTACT_SUBJECT', $GLOBALS['STR_CONTACT_SUBJECT']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ORDER_NUMBER', $GLOBALS['STR_ORDER_NUMBER']);
		$tpl->assign('STR_REQUIRED_ORDER_NUMBER', $GLOBALS['STR_REQUIRED_ORDER_NUMBER']);
		$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
		$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
		$tpl->assign('STR_SOCIETE', $GLOBALS['STR_SOCIETE']);
		$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
		$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
		$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
		$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
		$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
		$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
		$tpl->assign('STR_TEXT', $GLOBALS['STR_TEXT']);
		$tpl->assign('STR_DAY_AM', $GLOBALS['STR_DAY_AM']);
		$tpl->assign('STR_DAY_PM', $GLOBALS['STR_DAY_PM']);
		$tpl->assign('STR_MANDATORY', $GLOBALS['STR_MANDATORY']);
		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('get_contact_success')) {
	/**
	 * get_contact_success()
	 *
	 * @param array $frm Array with all fields data
	 * @return
	 */
	function get_contact_success(&$frm)
	{
		$output = '
<h1 class="page_title">' . $GLOBALS['STR_CONTACT'] . '</h1>
<div class="page_content">
	<p>' . nl2br($GLOBALS['STR_TICKET_OK']) . '</p>
</div>
';
		return $output;
	}
}

if (!function_exists('get_contact_extra_field')) {
	/**
	 *
	 * @return
	 */
	function get_contact_extra_field()
	{
		return '';
	}
}

if (!function_exists('js_password_control')) {
	/**
	 * le javascript de contrôle du niveau de mot de passe
	 *
	 * @return
	 */
	function js_password_control($field_id)
	{
		$GLOBALS['js_ready_content_array'][] = '
		set_password_image_level("' . filtre_javascript($field_id, true, false, true) . '","' . $GLOBALS['repertoire_images'] . '","' . filtre_javascript('pwd_level_image', true, false, true) . '",' . (!empty($GLOBALS['site_parameters']['bootstrap_enabled'])?'true':'false') . ');
';
	}
}

?>
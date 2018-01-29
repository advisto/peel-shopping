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
// $Id: display_user_forms.php 55727 2018-01-12 14:11:33Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}


if (!function_exists('get_user_change_params_form')) {
	/**
	 * get_user_change_params_form()
	 *
	 * @param array $frm Array with all fields data
	 * @param class $form_error_object
	 * @param array $mandatory_fields
	 * @return
	 */
	function get_user_change_params_form(&$frm, &$form_error_object, $mandatory_fields)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('user_change_params_form.tpl');
		if ($form_error_object->has_error('token')) {
			$tpl->assign('token_error', $form_error_object->text('token'));
		}
		$tpl->assign('action', get_current_url(false));
		$tpl->assign('content_rows_info', '');
		$tpl->assign('mandatory_fields', $mandatory_fields);
		if(StringMb::substr(vb($frm['email_bounce']), 0, 2)=='5.' || (!empty($GLOBALS['site_parameters']['user_change_mandatory_fields']['email']) && empty($frm['email']))) {
			// Email vide ou ayant généré une erreur
			$email_form='';
			$domain=explode('@', vb($frm['email']));
			$email_explain= sprintf($GLOBALS['STR_EMAIL_BOUNCE_REPLACE'], vb($domain[1]), vb($frm['email_bounce']), vb($frm['email']));
		} elseif(empty($GLOBALS['site_parameters']['user_change_mandatory_fields']['email']) && empty($frm['email'])) {
			$email_form=$_SESSION['session_utilisateur']['email'];
		} else {
			$email_form=vb($frm['email']);
		}
		$tpl->assign('email', $email_form);
		$tpl->assign('email_explain', vb($email_explain));
		$tpl->assign('email_error', $form_error_object->text('email'));
		$tpl->assign('civilite_mlle_issel', (vb($frm['civilite']) == "Mlle"));
		$tpl->assign('civilite_mme_issel', (vb($frm['civilite']) == "Mme"));
		$tpl->assign('civilite_m_issel', (vb($frm['civilite']) == "M."));
		$tpl->assign('gender_error', $form_error_object->text('civilite'));
		if (empty($GLOBALS['site_parameters']['pseudo_is_not_used'])) {
			$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
			$tpl->assign('pseudo', (isset($frm['pseudo'])?vb($frm['pseudo']):$_SESSION['session_utilisateur']['pseudo']));
			$tpl->assign('pseudo_error', $form_error_object->text('pseudo'));
		}
		$tpl->assign('first_name', vb($frm['prenom']));
		$tpl->assign('first_name_error', $form_error_object->text('prenom'));
		$tpl->assign('name', vb($frm['nom_famille']));
		$tpl->assign('name_error', $form_error_object->text('nom_famille'));
		$tpl->assign('societe', vb($frm['societe']));
		$tpl->assign('societe_error', $form_error_object->text('societe'));
		
		// On mentionne le champ obligatoire - en fait on le vérifiera uniquement pour la France
		$tpl->assign('siret_txt', $GLOBALS['STR_COMPANY_IDENTIFICATION'] . ' <span class="etoile">*</span>');
		$tpl->assign('siret', vb($frm['siret']));
		$tpl->assign('siret_error', $form_error_object->text('siret'));
		$tpl->assign('intracom_form', vb($frm['intracom_for_billing']));
		$tpl->assign('intracom_form_error', $form_error_object->text('intracom_for_billing'));
		$tpl->assign('telephone', vb($frm['telephone']));
		$tpl->assign('telephone_error', $form_error_object->text('telephone'));
		$tpl->assign('portable', vb($frm['portable']));
		$tpl->assign('portable_error', $form_error_object->text('portable'));
		$tpl->assign('fax', vb($frm['fax'])); // La variable est renseignée mais par défaut dans le template Smarty, l'affichage du fax est désactivé car plus beaucoup utilisé de nos jours
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
		$tpl->assign('is_fonction_mandatory', in_array('fonction', array_keys($GLOBALS['site_parameters']['user_mandatory_fields'])));
		$tpl->assign('fonction_options', get_user_job_options(vb($frm['fonction'])));
		$tpl->assign('fonction_error', $form_error_object->text('fonction'));
		if (!empty($GLOBALS['site_parameters']['user_fields_enable_code_promo'])) {
			$tpl->assign('promo_code', vb($frm['promo_code']));
			$tpl->assign('STR_PROMO_CODE', $GLOBALS['STR_PROMO_CODE']);
		}
		$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
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
		if (!empty($GLOBALS['site_parameters']['user_origin_multiple']) && !empty($frm['origin']) && !is_array($frm['origin'])) {
			$frm['origin'] = get_array_from_string($frm['origin']);
		}
		while (isset($GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i])) {
			if (!empty($GLOBALS['site_parameters']['user_origin_multiple']) && !empty($frm['origin'])) {
				$issel = in_array($i, $frm['origin']);
			} else {
				$issel = (vb($frm['origin']) == $i);
			}
			$tpl_origin_options[] = array('value' => $i,
				'issel' => $issel,
				'name' => $GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i]
				);
			$i++;
		}
		$tpl->assign('origin_infos', array('options' => $tpl_origin_options,
			'is_origin_other_activated' => in_array(vb($frm['origin']), $GLOBALS['origin_other_ids']),
			'origin_other_ids_for_javascript' => 'new Array('.implode(',', $GLOBALS['origin_other_ids']).')',
			'origin_other' => vb($frm['origin_other']),
			'error_text' => $form_error_object->text('origin'),
			'STR_CHOOSE' => $GLOBALS['STR_CHOOSE'],
			'user_origin_multiple' => vb($GLOBALS['site_parameters']['user_origin_multiple'])
			));

		$tpl->assign('enable_display_only_user_specific_field', !empty($GLOBALS['site_parameters']['enable_display_only_user_specific_field']));
		$tpl->assign('specific_fields', get_specific_field_infos($frm, $form_error_object, 'user'));

		// Select pour paramétrer la langue par défaut du compte lors de l'envoi d'email
		$sqlLng = "SELECT lang, nom_".$_SESSION['session_langue']." AS nom_lang
			FROM peel_langues
			WHERE (etat = '1'" . (!empty($_GET['langue'])?" OR lang='" . word_real_escape_string($_GET['langue']) . "'":'') . ") AND " . get_filter_site_cond('langues') . "" . (!empty($GLOBALS['site_parameters']['language_for_contacts'])? " AND lang IN ('".implode("','", $GLOBALS['site_parameters']['language_for_contacts'])."')" : '' ) . "
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
		if (!empty($GLOBALS['site_parameters']['user_front_office_logo_edit'])) {
			$tpl->assign('STR_LOGO', $GLOBALS['STR_LOGO']);
			$tpl->assign('STR_PHOTO', $GLOBALS['STR_PHOTO']);
			if (!empty($frm["logo"])) {
				$tpl->assign('logo', get_uploaded_file_infos("logo", $frm["logo"], get_current_url(false) . '?mode=supprfile&id=' . vb($_SESSION['session_utilisateur']['id_utilisateur']) . '&file=logo'));
			}
		}
		$tpl->assign('language_for_automatic_emails_options', $language_for_automatic_emails_options);
		$tpl->assign('language_for_automatic_emails_selected', $language_for_automatic_emails_selected);
		$tpl->assign('newsletter_issel', (!empty($frm['newsletter'])));
		$tpl->assign('commercial_issel', (!empty($frm['commercial'])));
		$tpl->assign('token', get_form_token_input('change_params'));
		$tpl->assign('id_utilisateur', $_SESSION['session_utilisateur']['id_utilisateur']);
		$tpl->assign('is_annonce_module_active', check_if_module_active('annonces'));
		$tpl->assign('add_b2b_form_inputs', !empty($GLOBALS['site_parameters']['add_b2b_form_inputs']));
		$tpl->assign('cnil_txt', StringMb::textEncode($GLOBALS['STR_CNIL']));
		$tpl->assign('STR_CHANGE', $GLOBALS['STR_CHANGE']);
		$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
		$tpl->assign('STR_LEADER', $GLOBALS['STR_LEADER']);
		$tpl->assign('STR_MANAGER', $GLOBALS['STR_MANAGER']);
		$tpl->assign('STR_EMPLOYEE', $GLOBALS['STR_EMPLOYEE']);
		$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
		$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
		$tpl->assign('STR_FONCTION', $GLOBALS['STR_FONCTION']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_CHANGE_PARAMS', $GLOBALS['STR_CHANGE_PARAMS']);
		$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
		$tpl->assign('STR_GENDER', $GLOBALS['STR_GENDER']);
		$tpl->assign('STR_MLLE', $GLOBALS['STR_MLLE']);
		$tpl->assign('STR_MME', $GLOBALS['STR_MME']);
		$tpl->assign('STR_M', $GLOBALS['STR_M']);
		$tpl->assign('STR_STRONG_PSEUDO_NOTIFICATION', $GLOBALS['STR_STRONG_PSEUDO_NOTIFICATION']);
		$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
		$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
		$tpl->assign('STR_SOCIETE', $GLOBALS['STR_SOCIETE']);
		$tpl->assign('STR_INTRACOM_FORM', $GLOBALS['STR_INTRACOM_FORM']);
		$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
		$tpl->assign('STR_PORTABLE', $GLOBALS['STR_PORTABLE']);
		$tpl->assign('form_placeholder_portable', vb($GLOBALS['site_parameters']['form_placeholder_portable']));
		$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
		$tpl->assign('STR_NAISSANCE', $GLOBALS['STR_NAISSANCE']);
		$tpl->assign('STR_ERR_BIRTHDAY1', $GLOBALS['STR_ERR_BIRTHDAY1']);
		$tpl->assign('STR_ERR_BIRTHDAY2', $GLOBALS['STR_ERR_BIRTHDAY2']);
		$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
		$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
		$tpl->assign('STR_USER_ORIGIN', $GLOBALS['STR_USER_ORIGIN']);
		$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
		$tpl->assign('STR_LANGUAGE_FOR_AUTOMATIC_EMAILS', $GLOBALS['STR_LANGUAGE_FOR_AUTOMATIC_EMAILS']);
		$tpl->assign('STR_NEWSLETTER_YES', (!empty($GLOBALS['STR_NEWSLETTER_YES'])?$GLOBALS['STR_NEWSLETTER_YES']:''));
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
		$tpl->assign('hook_output', call_module_hook('user_change_params_form_additional_part', array('frm' => $frm, 'form_error_object' => $form_error_object), 'string'));
		$hook_result = call_module_hook('user_change_params_form_template_data', array('frm' => $frm, 'form_error_object' => $form_error_object), 'array');
		foreach($hook_result as $this_key => $this_value) {
			$tpl->assign($this_key, $this_value);
		}
		
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
	 * @param boolean $for_quote
	 * @param boolean $short_register_form
	 * @param boolean $url_account_register
	 * @param boolean $mandatory_fields
	 * @return
	 */
	function get_user_register_form(&$frm, &$form_error_object, $for_quote = false, $short_register_form = false, $url_account_register = null, $mandatory_fields = null)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('user_register_form.tpl');
		// for_home => Séparé en 3 blocs
		if(empty($url_account_register)) {
			$url_account_register = get_current_url(true);
		}
		$tpl->assign('mandatory_fields', $mandatory_fields);
		$tpl->assign('short_register_form', $short_register_form);
		$tpl->assign('is_annonce_module_active', check_if_module_active('annonces'));
		$tpl->assign('add_b2b_form_inputs', !empty($GLOBALS['site_parameters']['add_b2b_form_inputs']));
		$tpl->assign('action', $url_account_register);
		$tpl->assign('email', vb($frm['email']));
		$tpl->assign('email_error', $form_error_object->text('email'));
		if(empty($GLOBALS['site_parameters']['pseudo_is_not_used'])) {
			$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
			$tpl->assign('pseudo', vb($frm['pseudo']));
			$tpl->assign('pseudo_error', $form_error_object->text('pseudo'));
		}
		$tpl->assign('mot_passe', vb($frm['mot_passe']));
		$tpl->assign('mot_passe_confirm', vb($frm['mot_passe_confirm']));
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
		$tpl->assign('fax', vb($frm['fax'])); // La variable est renseignée mais par défaut dans le template Smarty, l'affichage du fax est désactivé car plus beaucoup utilisé de nos jours
		$tpl->assign('portable', vb($frm['portable']));
		$tpl->assign('portable_error', $form_error_object->text('portable'));
		$tpl->assign('adresse', vb($frm['adresse']));
		$tpl->assign('adresse_error', $form_error_object->text('adresse'));
		$tpl->assign('zip', vb($frm['code_postal']));
		$tpl->assign('zip_error', $form_error_object->text('code_postal'));
		$tpl->assign('town', vb($frm['ville']));
		$tpl->assign('town_error', $form_error_object->text('ville'));
		$tpl->assign('is_fonction_mandatory', in_array('fonction', array_keys($GLOBALS['site_parameters']['user_mandatory_fields'])));
		$tpl->assign('fonction_options', get_user_job_options(vb($frm['fonction'])));
		$tpl->assign('fonction_error', $form_error_object->text('fonction'));
		$tpl->assign('country_options', get_country_select_options(null, vb($frm['pays']), 'id'));
		$tpl->assign('type_error', $form_error_object->text('type'));
		$tpl->assign('activity_error', $form_error_object->text('activity'));
		$tpl->assign('type', vb($frm['type']));
		$tpl->assign('activity', vb($frm['activity']));
		$tpl->assign('url', vb($frm['url']));
		// On mentionne le champ si obligatoire - en fait on le vérifiera uniquement pour la France
		$tpl->assign('siret', vb($frm['siret']));
		$tpl->assign('siret_error', $form_error_object->text('siret'));
		$tpl->assign('siret_txt', $GLOBALS['STR_COMPANY_IDENTIFICATION']);
		if (!empty($GLOBALS['site_parameters']['user_fields_enable_code_promo'])) {
			$tpl->assign('promo_code', vb($frm['promo_code']));
			$tpl->assign('STR_PROMO_CODE', $GLOBALS['STR_PROMO_CODE']);
		}
		$tpl_origin_options = array();
		$i = 1;
		if (!empty($GLOBALS['site_parameters']['user_origin_multiple']) && !empty($frm['origin']) && !is_array($frm['origin'])) {
			$frm['origin'] = get_array_from_string($frm['origin']);
		}
		while (isset($GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i])) {
			if (!empty($GLOBALS['site_parameters']['user_origin_multiple']) && !empty($frm['origin'])) {
				$issel = in_array($i, $frm['origin']);
			} else {
				$issel = (vb($frm['origin']) == $i);
			}
			$tpl_origin_options[] = array('value' => $i,
				'issel' => $issel,
				'name' => $GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i]
				);
			$i++;
		}
		$tpl->assign('origin_infos', array('options' => $tpl_origin_options,
			'is_origin_other_activated' => in_array(vb($frm['origin']), $GLOBALS['origin_other_ids']),
			'origin_other_ids_for_javascript' => 'new Array('.implode(',', $GLOBALS['origin_other_ids']).')',
			'origin_other' => vb($frm['origin_other']),
			'error_text' => $form_error_object->text('origin'),
			'STR_CHOOSE' => $GLOBALS['STR_CHOOSE'],
			'user_origin_multiple' => vb($GLOBALS['site_parameters']['user_origin_multiple'])
			));

		$tpl->assign('enable_display_only_user_specific_field', !empty($GLOBALS['site_parameters']['enable_display_only_user_specific_field']));
		$tpl->assign('specific_fields', get_specific_field_infos($frm, $form_error_object, 'user'));
		if (check_if_module_active('captcha')) {
			// L'appel à get_captcha_inside_form($frm) réinitialise la valeur de $frm['code'] si le code donné n'est pas bon, en même temps que générer nouvelle image
			$tpl->assign('captcha', array(
				'validation_code_txt' => $GLOBALS['STR_VALIDATION_CODE'],
				'inside_form' => get_captcha_inside_form($frm),
				'validation_code_copy_txt' => $GLOBALS['STR_VALIDATION_CODE_COPY'],
				'error' => $form_error_object->text('code'),
				'value' => vb($frm['code'])
			));
		}
		
		// Select permettant de paramétrer la langue par défaut du compte lors de l'envoi d'email
		$sqlLng = "SELECT lang, nom_".$_SESSION['session_langue']." AS nom_lang
			FROM peel_langues
			WHERE " . get_filter_site_cond('langues') . " AND (etat = '1'" . (!empty($_GET['langue'])?" OR lang='" . word_real_escape_string($_GET['langue']) . "'":'') . ")
			" . (!empty($GLOBALS['site_parameters']['language_for_contacts'])? " AND lang IN ('".implode("','", $GLOBALS['site_parameters']['language_for_contacts'])."')" : '' ) . "
			GROUP BY lang
			ORDER BY position";
		$resLng = query($sqlLng);
		$language_for_automatic_emails_options = array();
		$language_for_automatic_emails_selected = null;
		while ($lng = fetch_assoc($resLng)) {
			$language_for_automatic_emails_options[vb($lng['lang'])] = vb($lng['nom_lang']);
			if ($lng['lang'] == $_SESSION['session_langue']) {
				$language_for_automatic_emails_selected = vb($lng['lang']);
			}
		}
		if (!empty($GLOBALS['site_parameters']['user_front_office_logo_edit'])) {
			$tpl->assign('STR_LOGO', $GLOBALS['STR_LOGO']);
			$tpl->assign('STR_PHOTO', $GLOBALS['STR_PHOTO']);
			if (!empty($frm["logo"])) {
				$tpl->assign('logo', get_uploaded_file_infos("logo", $frm["logo"], get_current_url(false) . '?mode=supprfile&id=' . vb($_SESSION['session_utilisateur']['id_utilisateur']) . '&file=logo'));
			}
		}
		$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
		$tpl->assign('language_for_automatic_emails_options', $language_for_automatic_emails_options);
		$tpl->assign('language_for_automatic_emails_selected', $language_for_automatic_emails_selected);
		$tpl->assign('STR_LANGUAGE_FOR_AUTOMATIC_EMAILS', $GLOBALS['STR_LANGUAGE_FOR_AUTOMATIC_EMAILS']);
		$tpl->assign('newsletter_issel', (!empty($frm['newsletter'])));
		$tpl->assign('newsletter_option_selected', vb($frm['newsletter_format']));
		$tpl->assign('commercial_issel', (!empty($frm['commercial'])));
		$tpl->assign('cnil_txt', StringMb::textEncode($GLOBALS['STR_CNIL']));
		$tpl->assign('token', get_form_token_input('get_user_register_form', true));
		$tpl->assign('js_password_control', js_password_control('mot_passe'));
		$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
		$tpl->assign('STR_LEADER', $GLOBALS['STR_LEADER']);
		$tpl->assign('STR_MANAGER', $GLOBALS['STR_MANAGER']);
		$tpl->assign('STR_EMPLOYEE', $GLOBALS['STR_EMPLOYEE']);
		if($for_quote && check_if_module_active('devis')) {
			$tpl->assign('STR_FIRST_REGISTER_TITLE', $GLOBALS['STR_DEVIS']);
			$tpl->assign('STR_FIRST_REGISTER_TEXT', $GLOBALS['STR_MODULE_DEVIS_CONSEIL']);
			$tpl->assign('submit_text', $GLOBALS['STR_SEND']);
		} else {
			$tpl->assign('STR_FIRST_REGISTER_TITLE', $GLOBALS['STR_FIRST_REGISTER_TITLE']);
			$tpl->assign('STR_FIRST_REGISTER_TEXT', $GLOBALS['STR_FIRST_REGISTER_TEXT']);
			$tpl->assign('STR_OPEN_ACCOUNT', $GLOBALS['STR_OPEN_ACCOUNT']);
			$tpl->assign('submit_text', $GLOBALS['STR_OPEN_ACCOUNT']);
		}
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
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
		$tpl->assign('form_placeholder_portable', vb($GLOBALS['site_parameters']['form_placeholder_portable']));
		$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
		$tpl->assign('STR_NAISSANCE', $GLOBALS['STR_NAISSANCE']);
		$tpl->assign('STR_MANDATORY', $GLOBALS['STR_MANDATORY']);
		$tpl->assign('STR_USER_ORIGIN', $GLOBALS['STR_USER_ORIGIN']);
		$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
		$tpl->assign('STR_NEWSLETTER_YES', (!empty($GLOBALS['STR_NEWSLETTER_YES'])?$GLOBALS['STR_NEWSLETTER_YES']:''));
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
		$tpl->assign('STR_COPY_VERIFICATION_CODE', $GLOBALS['STR_COPY_VERIFICATION_CODE']);
		$tpl->assign('hook_output', call_module_hook('user_register_form_additional_part', array('frm' => $frm, 'form_error_object' => $form_error_object), 'string'));
		$hook_result = call_module_hook('user_register_form_template_data', array('frm' => $frm, 'form_error_object' => $form_error_object), 'array');
		foreach($hook_result as $this_key => $this_value) {
			$tpl->assign($this_key, $this_value);
		}

		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('get_user_register_success')) {
	/**
	 * get_user_register_success()
	 *
	 * @param array $frm Array with all fields data
	 * @param array $mode Array with all fields data
	 * @return
	 */
	function get_user_register_success(&$frm, $mode = null)
	{
		$output = '
<h1 property="name" class="page_title">' . $GLOBALS['STR_HELLO'] . ' ' . StringMb::html_entity_decode_if_needed($frm['prenom']) . '</h1>';
		if ($mode=='retailer') {
			$output .= '<p>' . StringMb::nl2br_if_needed($GLOBALS['STR_MODULE_PREMIUM_MSG_RETAILER']) . '</p>';
		} else  {
			$output .= '<p>';
			$output .= StringMb::nl2br_if_needed($GLOBALS['STR_LOGIN_OK']);
			if (empty($GLOBALS['site_parameters']['user_double_optin_registration_disable'])) {
				$output .= StringMb::nl2br_if_needed(sprintf($GLOBALS["STR_LOGIN_OK2"], $frm['email']));
			} else {
				$output .= StringMb::nl2br_if_needed(sprintf($GLOBALS['STR_LOGIN_OK3'], get_url('account'), get_url('account'), get_url('catalog'), get_url('catalog')));
			}
			$output .= '</p>';
			$output .= affiche_contenu_html('register_success', true);
		}
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
		$tpl->assign('login_href', get_url('membre'));
		$tpl->assign('login_txt', $GLOBALS['STR_LOGIN']);
		$tpl->assign('home_href', get_url('/'));
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
	 * @param boolean $skip_title
	 * @return
	 */
	function get_access_account_form(&$frm, &$form_error_object, $forced_new_client_area_html = null, $skip_title = false)
	{
		$output = '';
		if(empty($forced_new_client_area_html)){
			$forced_new_client_area_html = '' . StringMb::nl2br_if_needed($GLOBALS['STR_MSG_NEW_CUSTOMER']) . '<br />';
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('access_account_form.tpl');
		if(!$skip_title) {
			$tpl->assign('acces_account_txt', $GLOBALS['STR_ACCES_ACCOUNT']);
		} else {
			$tpl->assign('acces_account_txt', null);
		}
		$tpl->assign('new_customer', $GLOBALS['STR_NEW_CUSTOMER']);
		$tpl->assign('msg_new_customer', $forced_new_client_area_html);
		$tpl->assign('still_customer', $GLOBALS['STR_STILL_CUSTOMER']);
		$tpl->assign('msg_still_customer', $GLOBALS['STR_MSG_STILL_CUSTOMER']);
		$tpl->assign('pass_perdu_txt', $GLOBALS['STR_PASS_PERDU']);
		$tpl->assign('pass_perdu_href', get_url('/utilisateurs/oubli_mot_passe.php'));
		if (empty($GLOBALS['site_parameters']['pseudo_is_not_used'])) {
			$tpl->assign('email_or_pseudo', $GLOBALS['STR_EMAIL_OR_PSEUDO'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
		} else {
			$tpl->assign('email_or_pseudo', $GLOBALS['STR_EMAIL'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
		}
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
		$social = call_module_hook('social_login_buttons', array(), 'array');
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
	 * @param boolean $no_introduction
	 * @return
	 */
	function get_contact_form(&$frm, &$form_error_object, $skip_introduction_text = false)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('contact_form.tpl');
		$tpl->assign('skip_introduction_text', $skip_introduction_text);
		$tpl->assign('short_form', !empty($GLOBALS['site_parameters']['contact_form_short_mode']));
		$tpl->assign('site_name', $GLOBALS['site_parameters']['nom_'.$_SESSION['session_langue']]);
		if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
			$tpl->assign('ssl_image_src', $GLOBALS['repertoire_images'] . '/security.png');
		}
		if ($form_error_object->has_error('token')) {
			$tpl->assign('token_error', $form_error_object->text('token'));
		}
		if (!empty($frm['is_ok'])) {
			$tpl->assign('success_msg', $GLOBALS['STR_TICKET_OK']);
		}
		$tpl->assign('contact_info', affiche_contenu_html("contact_page", true) . (function_exists('get_details_societe')?get_details_societe():''));
		if (empty($frm['recipient'])) {
			$tpl->assign('action', get_current_url(false).(!empty($GLOBALS['main_div_id'])?'?ctx='.$GLOBALS['main_div_id']:''));
		} else {
			$tpl->assign('action', get_current_url(true).(!empty($GLOBALS['main_div_id'])?'?ctx='.$GLOBALS['main_div_id']:''));
		}
		$tpl->assign('extra_field', get_contact_extra_field());		
		$sujet_options = array(
			'' => $GLOBALS['STR_CONTACT_LB']);
		for($i=1; isset($GLOBALS['STR_CONTACT_SELECT'.$i]); $i++) {
			if(!empty($GLOBALS['STR_CONTACT_SELECT'.$i])) {
				$sujet_options[$GLOBALS['STR_CONTACT_SELECT'.$i]]=$GLOBALS['STR_CONTACT_SELECT'.$i];
				if (!empty($_GET['subject']) && $_GET['subject'] == $i) {
					$frm['sujet'] = $GLOBALS['STR_CONTACT_SELECT'.$i];
				}
			}
		}
		$tpl->assign('site_configured_selected', vb($_GET['site_id']));
		$tpl->assign('sujet_options', $sujet_options);
		$tpl->assign('sujet_options_selected', vb($frm['sujet']));
		$tpl->assign('sujet_error', $form_error_object->text('sujet'));
		
		$tpl->assign('commande_id', vb($frm['commande_id']));
		$tpl->assign('commande_error', $form_error_object->text('commande_id'));
		$tpl->assign('email_value', vb($frm['email']));
		$tpl->assign('email_error', $form_error_object->text('email'));
		$tpl->assign('name_value', vb($frm['nom']));
		$tpl->assign('name_error', $form_error_object->text('nom'));
		$tpl->assign('product_info_id', vb($frm['product_info_id']));
		
		$tpl->assign('societe_value', vb($frm['societe']));
		$tpl->assign('societe_error', $form_error_object->text('societe'));
		$tpl->assign('first_name_value', vb($frm['prenom']));
		$tpl->assign('first_name_error', $form_error_object->text('prenom'));
		$tpl->assign('address_value', vb($frm['adresse']));
		$tpl->assign('address_error', $form_error_object->text('adresse'));
		$tpl->assign('zip_value', vb($frm['code_postal']));
		$tpl->assign('town_value', vb($frm['ville']));
		$tpl->assign('country_value', vb($frm['pays']));
		$tpl->assign('telephone_value', vb($frm['telephone']));
		$tpl->assign('telephone_error', $form_error_object->text('telephone'));
		$tpl->assign('texte_value', vb($frm['texte']));
		$tpl->assign('texte_error', $form_error_object->text('texte'));
		$tpl->assign('STR_DISPO', $GLOBALS['STR_DISPO']);
		$tpl->assign('contact_page_map_display', vb($frm['contact_page_map_display']));
		$tpl->assign('mail_title', vb($frm['mail_title']));
		$tpl->assign('meta_title', vb($frm['meta_title']));
		$tpl->assign('meta_description', vb($frm['meta_description']));
		if (!empty($GLOBALS['site_parameters']['hidden_fields_list'])) {
			$frm['hidden_fields_list'] = $GLOBALS['site_parameters']['hidden_fields_list'];
		}
		//On vérifie si il y a des champs à mettre en hidden
		if (!empty($frm['hidden_fields_list'])) {
			$fields_list_array = explode(",", $frm['hidden_fields_list']);
			foreach($fields_list_array as $field) {
				$tpl->assign('hidden_'.trim($field), true);
			}
		}
		
		if (!empty($GLOBALS['site_parameters']['user_contact_file_upload'])) {
			$GLOBALS['allow_fineuploader_on_page'] = true;
			$uploaded_file_tpl = $GLOBALS['tplEngine']->createTemplate('uploaded_file.tpl');
			$file_infos = get_uploaded_file_infos('file', vb($frm['file']), 'javascript:reinit_upload_field("file", "[DIV_ID]");');
			$uploaded_file_tpl->assign('f', $file_infos);
			$uploaded_file_tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
			$uploaded_file_tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$this_upload_html = $uploaded_file_tpl->fetch();
									
			$tpl->assign('user_contact_file_upload', $GLOBALS['site_parameters']['user_contact_file_upload']);
			$tpl->assign('STR_FILE', $GLOBALS['STR_FILE']);
			$tpl->assign('this_upload_html', $this_upload_html);
		}
		if (!empty($GLOBALS['site_parameters']['site_configured_array'])) {
			$tpl->assign('site_configured_array', vb($GLOBALS['site_parameters']['site_configured_for_display_array'], $GLOBALS['site_parameters']['site_configured_array']));
			$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
		}
		if (check_if_module_active('captcha')) {
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
		$tpl->assign('cnil_txt', StringMb::textEncode($GLOBALS['STR_CNIL']));
		if(!empty($frm['product_info_id'])) {
			$tpl->assign('STR_CONTACT', $GLOBALS['STR_CONTACT_INTRO_PRODUCT_INFO']);
			$tpl->assign('STR_CONTACT_INTRO', '');
		} else {
			$tpl->assign('STR_CONTACT', $GLOBALS['STR_CONTACT']);
			$tpl->assign('STR_CONTACT_INTRO', $GLOBALS['STR_CONTACT_INTRO']);
		}
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
		$tpl->assign('STR_PROFESSION', $GLOBALS['STR_PROFESSION']);
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
<h1 property="name" class="page_title">' . $GLOBALS['STR_CONTACT'] . '</h1>
<div class="page_content">
	<div class="alert alert-success">' . StringMb::nl2br_if_needed($GLOBALS['STR_TICKET_OK']) . '</div>
</div>
' . vb($GLOBALS['site_parameters']['contact_form_success_tag']);
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
		set_password_image_level("' . filtre_javascript($field_id, true, false, true, true, false) . '","' . $GLOBALS['repertoire_images'] . '","' . filtre_javascript('pwd_level_image', true, false, true, true, false) . '",' . (!empty($GLOBALS['site_parameters']['bootstrap_enabled'])?'true':'false') . ');
';
	}
}

if (!function_exists('get_address_form')) {
	/**
	 * Affiche le formulaire de création d'adresse
	 *
	 * @param array $frm
	 * @return
	 */
	function get_address_form($frm = null, $user_id = null, $in_admin = false)
	{
		$output = '';
		if((defined('IN_PEEL_ADMIN') && empty($user_id)) || (!defined('IN_PEEL_ADMIN') && empty($_SESSION['session_utilisateur']['id_utilisateur']))) {
			return false;
		}
		$output .= '
		<form class="entryform form-inline" method="post" action="'.get_current_url(false).'">
			<fieldset>
';
		if (!empty($frm['id'])) {
			$output .= '
				<input type="hidden" name="id" value="'.vn($frm['id']).'">
				<input type="hidden" name="mode" value="update_address">';
		} else {
			$output .= '
				<input type="hidden" name="mode" value="insert_address">';
		}
		if (!empty($user_id)) {
			$output .= '
				<input type="hidden" name="id_utilisateur" value="'.vn($user_id).'">';
		} else {
			$output .= '
				<input type="hidden" name="mode" value="insert_address">';
		}
		$options = '';
		if ($in_admin || !empty($GLOBALS['site_parameters']['mode_transport'])) {
			// Si le mode de transport est défini, ou dans le cas de l'administration des adresses (l'admin doit avoir tous les choix)
			$options .= ' <option value="" ' . frmvalide(vb($frm['address_type']) == "", ' selected="selected"') . '>' . $GLOBALS['STR_INVOICE_ADDRESS']  . ' / ' .  $GLOBALS['STR_SHIP_ADDRESS']  . '</option>
						  <option value="bill" ' . frmvalide(vb($frm['address_type']) == "bill", ' selected="selected"') . '>' . $GLOBALS['STR_INVOICE_ADDRESS']  . '</option>
						  <option value="ship" ' . frmvalide(vb($frm['address_type']) == "ship", ' selected="selected"') . '>' .  $GLOBALS['STR_SHIP_ADDRESS']  . '</option>
';
		} else {
			$options .= '<option value="bill" ' . frmvalide(vb($frm['address_type']) == "bill", ' selected="selected"') . '>' . $GLOBALS['STR_INVOICE_ADDRESS']  . '</option>';
		}
		if (!empty($GLOBALS['site_parameters']['ads_specific_address'])) {
			$options .= '<option value="ad" ' . frmvalide(vb($frm['address_type']) == "ad", ' selected="selected"') . '>' . $GLOBALS['STR_MODULE_ANNONCES_AD']  . '</option>';
		}
		$output .= '
				<div class="enregistrement">
					<span class="enregistrementgauche"><label for="name_adresse">' .$GLOBALS['STR_NAME']  . ' ' . StringMb::strtoupper($GLOBALS['STR_ADDRESS']) . ' '.(!$in_admin?'<span class="etoile">*</span>':'').'' . $GLOBALS['STR_BEFORE_TWO_POINTS']  . ':</label></span>
					<span class="enregistrementdroite"><input type="text" class="form-control" id="name_adresse" name="nom" value="'.StringMb::str_form_value(StringMb::html_entity_decode_if_needed(vb($frm['nom']))).'" '.(!$in_admin?'required="required"':'').' /></span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label>' . $GLOBALS['STR_TYPE']  .$GLOBALS['STR_BEFORE_TWO_POINTS']  . ':</label></span>
					<span class="enregistrementdroite">
						<select name="address_type" class="form-control">
							'.$options.'
						</select>
					</span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label>' . $GLOBALS['STR_GENDER']  .$GLOBALS['STR_BEFORE_TWO_POINTS']  . ':</label></span>
					<span class="enregistrementdroite">
						<input type="radio" name="civilite" value="Mlle" ' . frmvalide(vb($frm['civilite']) == "Mlle") . ' /> ' . $GLOBALS['STR_MLLE'] . ' &nbsp;
						<input type="radio" name="civilite" value="Mme" ' . frmvalide(vb($frm['civilite']) == "Mme") . ' /> ' . $GLOBALS['STR_MME'] . ' &nbsp;
						<input type="radio" name="civilite" value="M." ' . frmvalide(vb($frm['civilite']) == "M.") . ' /> ' . $GLOBALS['STR_M'] . '
					</span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label for="nom_famille">' . $GLOBALS['STR_NAME'] . ' '.(!$in_admin?'<span class="etoile">*</span>':'').'' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</label></span>
					<span class="enregistrementdroite"><input type="text" class="form-control" id="nom_famille" name="nom_famille" value="'.StringMb::str_form_value(StringMb::html_entity_decode_if_needed(vb($frm['nom_famille']))).'" '.(!$in_admin?'required="required"':'').' /></span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label for="prenom">' . $GLOBALS['STR_FIRST_NAME'] . ' '.(!$in_admin?'<span class="etoile">*</span>':'').'' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</label></span>
					<span class="enregistrementdroite"><input type="text" class="form-control" id="prenom" name="prenom" value="'.StringMb::str_form_value(StringMb::html_entity_decode_if_needed(vb($frm['prenom']))).'" '.(!$in_admin?'required="required"':'').' /><span class="notice"></span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label for="email">' . $GLOBALS['STR_EMAIL'] . ' '.(!$in_admin?'<span class="etoile">*</span>':'').'' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</label></span>
					<span class="enregistrementdroite"><input type="email" class="form-control" id="email" name="email" value="'.StringMb::str_form_value(StringMb::html_entity_decode_if_needed(vb($frm['email']))).'" '.(!$in_admin?'required="required"':'').' autocapitalize="none" /><span class="notice"></span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label for="societe">' . $GLOBALS['STR_SOCIETE'] . $GLOBALS['STR_BEFORE_TWO_POINTS']  . ':</label></span>
					<span class="enregistrementdroite"><input type="text" class="form-control" id="societe" name="societe" value="'.StringMb::str_form_value(StringMb::html_entity_decode_if_needed(vb($frm['societe']))).'"  /></span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label for="adresse">' . $GLOBALS['STR_ADDRESS'] . ' '.(!$in_admin?'<span class="etoile">*</span>':'').'' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</label></span>
					<span class="enregistrementdroite"><input type="text" class="form-control" id="adresse" name="adresse" value="'.StringMb::str_form_value(StringMb::html_entity_decode_if_needed(vb($frm['adresse']))).'" '.(!$in_admin?'required="required"':'').' /></span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label for="code_postal">' . $GLOBALS['STR_ZIP'] . ' '.(!$in_admin?'<span class="etoile">*</span>':'').'' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</label></span>
					<span class="enregistrementdroite"><input type="text" class="form-control" id="code_postal" name="code_postal" value="'.StringMb::str_form_value(StringMb::html_entity_decode_if_needed(vb($frm['code_postal']))).'" '.(!$in_admin?'required="required"':'').' /></span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label for="ville">' . $GLOBALS['STR_TOWN'] . ' '.(!$in_admin?'<span class="etoile">*</span>':'').'' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</label></span>
					<span class="enregistrementdroite"><input type="text" class="form-control" id="ville" name="ville" value="'.StringMb::str_form_value(StringMb::html_entity_decode_if_needed(vb($frm['ville']))).'" '.(!$in_admin?'required="required"':'').' /></span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label for="pays">' . $GLOBALS['STR_COUNTRY'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</label></span>
					<span class="enregistrementdroite">
						<select id="pays" name="pays" class="form-control">
							' . get_country_select_options(null, vb($frm['pays']), 'id') . '
						</select>
					</span>
				</div>
				<div class="enregistrement">
					<span class="enregistrementgauche"><label for="portable">' . $GLOBALS['STR_TELEPHONE'] . ' '.(!$in_admin?'<span class="etoile">*</span>':'').'' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</label></span>
					<span class="enregistrementdroite"><input type="text" class="form-control" id="portable" name="portable" value="'.StringMb::str_form_value(StringMb::html_entity_decode_if_needed(vb($frm['portable']))).'" placeholder="'.StringMb::str_form_value(vb($GLOBALS['site_parameters']['form_placeholder_portable'])).'" '.(!$in_admin?'required="required"':'').' /></span>
				</div>
				<p class="center" style="margin-top:10px"><input class="btn btn-primary btn-lg" type="submit" value="' . StringMb::str_form_value($GLOBALS["STR_VALIDATE"]) . '" /></p>
			</fieldset>
			<p><span class="form_mandatory">(*) ' . $GLOBALS['STR_MANDATORY'] . '</span></p>
		</form>
';		
		return $output;
	}
}

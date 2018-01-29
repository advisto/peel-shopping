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
// $Id: fonctions.php 55792 2018-01-17 11:49:45Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Renvoie les éléments de menu affichables
 *
 * @param array $params
 * @return
 */
function webmail_hook_admin_menu_items($params) {
	$result = array();
	if (a_priv('admin_users,admin_users_contact_form,admin_finance', true)) {
		$result['menu_items']['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/webmail_send.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND"];
		$result['menu_items']['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/list_mails_send.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_SENT_EMAILS"];
	}
	if (a_priv('admin_users,admin_users_contact_form,admin_finance,admin_operations,admin_productsline', true)) {
		$result['menu_items']['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/list_mails.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_LIST_EMAILS"];
	}
	return $result;
}

/**
 * Fonction affichant la liste d'emails sur le compte utilisateur
 *
 * @param array $params
 * @return
 */
function webmail_hook_admin_user_edit_more_infos_html($params) {
	// Emails venant de l'utilisateur et adressés au site à partir du formulaire de contact du site
	$output = '';

	// Emails venant de l'utilisateur et adressés au site à partir du formulaire de contact du site
	$sql_formulaire = 'SELECT *
		FROM peel_webmail
		WHERE id_user="' . intval(vn($params['id_utilisateur'])) . '" AND ' . get_filter_site_cond('webmail', null, true) . '
		ORDER BY date DESC, heure DESC';
	$Links_formulaire = new Multipage($sql_formulaire, 'affiche_liste_mail_annonce', '*');
	$HeaderTitlesArray = array($GLOBALS['STR_ADMIN_TITLE'], $GLOBALS['STR_MESSAGE'], $GLOBALS['STR_DATE'], $GLOBALS['STR_ADMIN_INFO']);
	$Links_formulaire->HeaderTitlesArray = $HeaderTitlesArray;
	$results_array = $Links_formulaire->Query();

	$output .= '
			<table class="full_width">
				<tr>
					<td class="entete" colspan="2">'.$GLOBALS['STR_MODULE_WEBMAIL_ADMIN_LIST_IN_SITE_CONTACT_FORM'].'</td>
				</tr>
				<tr>
					<td colspan="2">
						<table id="tablesForm" style="width:100%;">
						' . $Links_formulaire->getHeaderRow();
	$i = 0;
	if (empty($results_array)) {
		$output .= '<tr><td colspan="4" class="center"><b>'.$GLOBALS['STR_MODULE_WEBMAIL_ADMIN_NO_EMAIL_FOUND'].'</b></td></tr>';
	} else {
		foreach($results_array as $formulaire) {
			$output .= tr_rollover($i, true) . '
								<td class="center">' . vb($formulaire['titre']) . '</td>
								<td class="center">' . vb($formulaire['message']) . '</td>
								<td class="center">' . vb($formulaire['date']) . ' ' . vb($formulaire['heure']) . '</td>
								<td class="center">' . vb($formulaire['ip']) . '</td>
							</tr>';
			$i++;
		}
	}
	$output .= '
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">' . $Links_formulaire->GetMultipage() . '</td>
				</tr>
			</table>';
	return $output;
}

/**
 * Affiche le formulaire d'envoi d'email
 *
 * @param array $frm Array with all fields data
 * @param boolean $return_mode
 * @return
 */
function affiche_form_send_mail($frm, $return_mode = false, &$form_error_object = null)
{
	$output = '';
	// Si $id_account existe, alors on envoie un email à un utilisateur connu.
	if (!empty($frm['id_utilisateur'])) {
		$row_account =  get_user_information(vn($frm['id_utilisateur']));
		$user_id = $row_account['id_utilisateur'];
		$user_email = $row_account['email'];
		$user_gender = $row_account['civilite'];
		$user_name = $row_account['nom_famille'];
		$user_first_name = $row_account['prenom'];
		$user_login = $row_account['pseudo'];
		$user_password = $row_account['mot_passe'];
	} elseif (!empty($frm['id_webmail'])) {
		// On répond à un email envoyé par un utilisateur
		$q = query("SELECT *
			FROM peel_webmail
			WHERE id='" . intval(vn($frm['id_webmail'])) . "' AND " . get_filter_site_cond('webmail', null, true) . "");
		$row_mail = fetch_assoc($q);
		// On met à jour ensuite pour marquer l'email comme lu
		if ($row_mail['read'] == 'NO') {
			query("UPDATE `peel_webmail`
				SET `read` = 'READ'
				WHERE `id`='" . intval($frm['id_webmail']) . "' AND " . get_filter_site_cond('webmail', null, true) . "");
		}

		$user_id = $row_mail['id_user'];
		$user_email = $row_mail['email'];
		$user_name = $row_mail['nom'] ;
		$user_first_name = $row_mail['prenom'] ;

		if (!empty($user_id)) {
			// on récupère les infos persos dans la BDD si l'utilisateur était loggué
			$row_account = get_user_information($user_id);
			$user_gender = $row_account['civilite'];
			$user_login = $row_account['pseudo'];
			$user_password = $row_account['mot_passe'];
		}
	} elseif (!empty($frm['user_ids'])) {
		$q = 'SELECT email
			FROM peel_utilisateurs
			WHERE id_utilisateur IN("' . implode('","', real_escape_string($frm['user_ids'])) . '") AND ' . get_filter_site_cond('utilisateurs') . '';
		$query = query($q);
		while ($users_email = fetch_assoc($query)) {
			$users_email_array[] = $users_email['email'];
		}
		$user_email = implode(';', $users_email_array);
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/webmailAdmin_form.tpl');
	$GLOBALS['js_ready_content_array'][] = '
	$("#signature_template_options").change(function () {
		form_template_content_add("signature_template_options", "signature", "message", "' . $GLOBALS['wwwroot'] . '");
	}).change();
	$("#template").change(function () {
		form_template_content_add("template", "message", "message", "' . $GLOBALS['wwwroot'] . '");
		form_template_content_add("template", "subject", "title", "' . $GLOBALS['wwwroot'] . '");
		form_template_content_add("template", "lang", "lang", "' . $GLOBALS['wwwroot'] . '");
	}).change();
';
	if (!empty($frm['id_utilisateur'])) {
		$tpl->assign('edit_href', $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . intval(vn($frm['id_utilisateur'])));
	}
	$tpl->assign('id_utilisateur', intval(vn($frm['id_utilisateur'])));
	$tpl->assign('user_gender', vb($user_gender));
	$tpl->assign('user_name', vb($user_name));
	$tpl->assign('user_first_name', vb($user_first_name));
	$tpl->assign('user_email', vb($user_email));
	$tpl->assign('my_email', $_SESSION['session_utilisateur']['email']);
	
	if (!empty($frm['id_utilisateur'])) {
		$tpl->assign('by_lang_href', '\'' . get_current_url(false) . '?' . (!empty($_GET['email_all_hash'])?'email_all_hash=' . $_GET['email_all_hash'] . '&':'') . (!empty($_GET['id_cat'])?'id_cat=' . $_GET['id_cat'] . '&':'') . 'lang_mail=\'+lang_mail+\'&id_utilisateur=' . intval(vn($frm['id_utilisateur'])) . '\'');
		$tpl->assign('ctl_href', '\'' . get_current_url(false) . '?' . (!empty($_GET['email_all_hash'])?'email_all_hash=' . $_GET['email_all_hash'] . '&':'') . (!empty($_GET['lang_mail'])?'lang_mail=' . $_GET['lang_mail'] . '&':'') . 'id_cat=\'+cat_id+\'&id_utilisateur=' . intval(vn($frm['id_utilisateur'])) . '\'');
	} elseif (!empty($frm['id_webmail'])) {
		$tpl->assign('by_lang_href', '\'' . get_current_url(false) . '?' . (!empty($_GET['email_all_hash'])?'email_all_hash=' . $_GET['email_all_hash'] . '&':'') . (!empty($_GET['id_cat'])?'id_cat=' . $_GET['id_cat'] . '&':'') . 'lang_mail=\'+lang_mail+\'&id_webmail=' . intval(vn($frm['id_webmail'])) . '\'');
		$tpl->assign('ctl_href', '\'' . get_current_url(false) . '?' . (!empty($_GET['email_all_hash'])?'email_all_hash=' . $_GET['email_all_hash'] . '&':'') . (!empty($_GET['lang_mail'])?'lang_mail=' . $_GET['lang_mail'] . '&':'') . 'id_cat=\'+cat_id+\'&id_webmail=' . intval(vn($frm['id_webmail'])) . '\'');
	} elseif (!empty($frm['user_ids'])) {
		$user_ids = '';
		$i = 0;
		foreach ($frm['user_ids'] as $user_id) {
			$i++;
			$user_ids .= '&user_ids[]=' . $user_id;
		}
		$tpl->assign('by_lang_href', '\'' . get_current_url(false) . '?' . (!empty($_GET['email_all_hash'])?'email_all_hash=' . $_GET['email_all_hash'] . '&':'') . (!empty($_GET['id_cat'])?'id_cat=' . $_GET['id_cat'] . '&':'') . 'lang_mail=\'+lang_mail+\'' . $user_ids . '\'');
		$tpl->assign('ctl_href', '\'' . get_current_url(false) . '?' . (!empty($_GET['email_all_hash'])?'email_all_hash=' . $_GET['email_all_hash'] . '&':'') . (!empty($_GET['lang_mail'])?'lang_mail=' . $_GET['lang_mail'] . '&':'') . 'id_cat=\'+cat_id+\'' . $user_ids . '\'');
	} else {
		$tpl->assign('by_lang_href', '\'' . get_current_url(false) . '?' . (!empty($_GET['email_all_hash'])?'email_all_hash=' . $_GET['email_all_hash'] . '&':'') . (!empty($_GET['id_cat'])?'id_cat=' . $_GET['id_cat'] . '&':'') . 'lang_mail=\'+lang_mail');
		$tpl->assign('ctl_href', '\'' . get_current_url(false) . '?' . (!empty($_GET['email_all_hash'])?'email_all_hash=' . $_GET['email_all_hash'] . '&':'') . (!empty($_GET['lang_mail'])?'lang_mail=' . $_GET['lang_mail'] . '&':'') . 'id_cat=\'+cat_id');
	}
	if (!empty($row_mail)) {
		$tpl->assign('row_mail', array(
			'email' => $row_mail['email'],
			'titre' => $row_mail['titre'],
			'message' => $row_mail['message']
		));
	}
	$tpl->assign('action', get_current_url(true));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF']));
	$tpl->assign('email_templates_href', $GLOBALS['administrer_url'] . '/email-templates.php');
	$tpl->assign('email_templates_admin_href', $GLOBALS['administrer_url'] . '/administrer/email-templates.php');
	$tpl_options = array();
	$sql = 'SELECT tc.id, tc.name_' . $_SESSION['session_langue'] . ' AS name, tc.site_id
		FROM peel_email_template_cat tc
		INNER JOIN peel_email_template t ON t.id_cat=tc.id AND t.active="TRUE" AND ' . get_filter_site_cond('email_template', 't') . '
		WHERE ' . get_filter_site_cond('email_template_cat', 'tc') . '
		GROUP BY tc.id
		ORDER BY name';
	$query = query($sql);
	while ($row_categories = fetch_assoc($query)) {
		$tpl_options[] = array(
			'value' => intval(vn($row_categories['id'])),
			'issel' => !empty($frm['id_cat']) && $frm['id_cat'] == $row_categories['id'],
			'name' => get_site_info($row_categories) . vb($row_categories['name'])
		);
	}
	$tpl->assign('options', $tpl_options);
	$tpl->assign('email_template_options', get_email_template_options('id', vn($frm['id_cat']), vb($frm['lang_mail'])));
	
	// Sélection de la signature par défaut (support_clientèle)
	$q = query('SELECT id 
		FROM peel_email_template
		WHERE ' . get_filter_site_cond('email_template', null) . ' AND lang="' . nohtml_real_escape_string(!empty($frm['lang_mail'])? $frm['lang_mail']:$_SESSION['session_langue']) . '" AND technical_code = "signature_support"');
	if($default_signature = fetch_assoc($q)) {
		$default_signature_id = $default_signature['id'];	
	} else {
		// Il n'y a pas forcement de résultat pour la requête
		$default_signature_id = null;
	}
	
	$tpl->assign('signature_template_options', get_email_template_options('id', null, $frm['lang_mail'], $default_signature_id, true));
	$langs_array = $GLOBALS['admin_lang_codes'];
	foreach ($langs_array as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'issel' => !empty($frm['lang_mail']) && $frm['lang_mail'] == $lng
			);
		if(!empty($frm['lang_mail']) && $frm['lang_mail'] == $lng) {
			$tpl->assign('selected_lang', $lng);
		}
	}
	$tpl->assign('langs', $tpl_langs);
	if (!empty($_GET['email_all_hash']) && !empty($_SESSION['count_from_send_email_all'][$_GET['email_all_hash']])) {
		$tpl->assign('is_multidestinataire', true);
		$tpl->assign('multidestinataire_txt', sprintf('%s destinataire(s) sélectionné(s)', $_SESSION['count_from_send_email_all'][$_GET['email_all_hash']]));
	} elseif (empty($frm['id_utilisateur'])) {
		$tpl->assign('is_multidestinataire', false);
		$tpl->assign('is_destinataire', true);
		$tpl->assign('destination_mail_error', (!empty($form_error_object) ? $form_error_object->text('destination_mail') : ''));
	} else {
		$tpl->assign('is_multidestinataire', false);
		$tpl->assign('is_destinataire', false);
		$tpl->assign('user_id', intval(vn($user_id)));
	}
	$tpl->assign('nom_famille', vb($_SESSION['session_utilisateur']['nom_famille']));
	$tpl->assign('prenom', vb($_SESSION['session_utilisateur']['prenom']));
	$tpl->assign('site', vb($GLOBALS['site']));
	$tpl->assign('HTTP_HOST', $GLOBALS['_SERVER']['HTTP_HOST']);
	$tpl->assign('sites_href', $GLOBALS['administrer_url'] . '/sites.php');
	if((isset($_SESSION['request_from_send_email_all'][$_GET['email_all_hash']]))){
		$tpl->assign('count_email_all_hash', $_SESSION['count_from_send_email_all'][$_GET['email_all_hash']]);
		$tpl->assign('request_email_all_hash', $_SESSION['request_from_send_email_all'][$_GET['email_all_hash']]);
		$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SEND_EMAIL_TO_N_USERS', sprintf($GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SEND_EMAIL_TO_N_USERS'], $_SESSION['count_from_send_email_all'][$_GET['email_all_hash']]));
	}
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_CLIENT_INFORMATION', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_CLIENT_INFORMATION']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_EDIT_USER', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_EDIT_USER']);
	$tpl->assign('STR_GENDER', $GLOBALS['STR_GENDER']);
	$tpl->assign('STR_UNAVAILABLE', $GLOBALS['STR_UNAVAILABLE']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
	$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
	$tpl->assign('STR_UNAVAILABLE', $GLOBALS['STR_UNAVAILABLE']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_ANSWER_EMAIL_SENT_BY', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_ANSWER_EMAIL_SENT_BY']);
	$tpl->assign('STR_ADMIN_SUBJECT', $GLOBALS['STR_ADMIN_SUBJECT']);
	$tpl->assign('STR_MESSAGE', $GLOBALS['STR_MESSAGE']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_ADD_NAME', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_ADD_NAME']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_EMAIL_TEMPLATES', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_EMAIL_TEMPLATES']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_EXPLAIN_TEMPLATES', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_EXPLAIN_TEMPLATES']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_EXPLAIN_TAGS', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_EXPLAIN_TAGS']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_CHOOSE_LANG', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_CHOOSE_LANG']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_CHOOSE_CATEGORY', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_CHOOSE_CATEGORY']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_CHOSSE_TEMPLATE', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_CHOSSE_TEMPLATE']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_FORM_TITLE', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_FORM_TITLE']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_EMAIL_FIELD', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_EMAIL_FIELD']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_RECIPIENTS', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_RECIPIENTS']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_RECIPIENT_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_RECIPIENT_EMAIL']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_RECIPIENT_EMAIL_EXPLAIN', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_RECIPIENT_EMAIL_EXPLAIN']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_NONE', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_NONE']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_UNDEFINED_SERVICE', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_UNDEFINED_SERVICE']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_CLIENT_SERVICE', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_CLIENT_SERVICE']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_SALES', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_SALES']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_ACCOUNTING', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_ACCOUNTING']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_SEO', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_SEO']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_TECHNICAL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_TECHNICAL']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_COMMUNICATION', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_COMMUNICATION']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_MARKETING', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_MARKETING']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_DIRECTION', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_DIRECTION']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_EXTERNAL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_EXTERNAL']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SENDER_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SENDER_EMAIL']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_ORDER_MANAGEMENT_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_ORDER_MANAGEMENT_EMAIL']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_CLIENT_SERVICE_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_CLIENT_SERVICE_EMAIL']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_EMAIL_EXPLAIN', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_EMAIL_EXPLAIN']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_SEND_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_SEND_EMAIL']);
	$output .= $tpl->fetch();

	if ($return_mode) {
		return $output;
	} elseif (!empty($output)) {
		echo $output;
	} else {
		return false;
	}
}

/**
 * Fonction de traitement d'envoi d'email
 *
 * @param array $frm Array with all fields data
 * @return
 */
function send_mail_admin($frm)
{
	$output = '';
	$custom_template_tags = array();
	if (!empty($frm)) {
		if (empty($frm['destination_mail']) && empty($_SESSION['count_from_send_email_all'][$_GET['email_all_hash']])) {
			return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS["STR_MODULE_WEBMAIL_ADMIN_ERR_SENT_NO_EMAIL"]))->fetch();
		}
		if (!empty($frm['id_webmail'])) {
			query("UPDATE `peel_webmail`
				SET `read`='SEND'
				WHERE `id`='" . intval($frm['id_webmail']) . "' AND `read`='READ' AND " . get_filter_site_cond('webmail', null, true) . "");
		}
		$mail_subject = vb($frm['subject']);
		// Utilisation de \r\n. Ce sera transformé si nécessaire en <br /> par la fonction send_email
		$mail_content = vb($frm['message']) . "\r\n" . vb($frm['signature']);
		$template_id = vn($frm['template']);
		// ATTENTION : $frm['email_from'] n'est PAS un email, c'est un type d'email souhaité : email_webmaster, email_commande, email_client, ou my_email
		if (!empty($frm['email_from']) && !empty($GLOBALS['site_parameters'][$frm['email_from']])) {
			// On envoie à partir de l'email choisi parmi ceux configurés dans la gestion du site
			$email_from = $GLOBALS['site_parameters'][$frm['email_from']];
		} elseif(!empty($frm['email_from']) && $frm['email_from'] == 'my_email') {
			// Utilisation de l'email du compte administrateur
			$email_from = $_SESSION['session_utilisateur']['email'];
		} else {
			$email_from = $GLOBALS['site_parameters']['email_webmaster'];
		}
		if(!empty($template_id)) {
			// Le texte tapé par l'administrateur est issu d'un modèle d'email
			$this_data = 'template_' . vn($template_id);
			// Donc on ne stocke pas le message réellement envoyé pour ne pas saturer la BDD
			// (le message est issu d'un template, donc le contenu est proche du modèle original)
			// NB : On pourrait faire des tests complémentaires en utilisant $template_infos = getTextAndTitleFromEmailTemplateLang(null, $frm['lang'], $template_id);
			$this_comment = '';
		} else {
			$this_data = 'NO_TEMPLATE';
			// On stocke le message car il n'est pas issu d'un template
			$this_comment = $frm['message'];
		}
		if (!empty($frm['submit_send_email_all'])) {
			// $custom_template_tags ne contient pas d'info utilisateur car on envoie à potentiellement N personnes
			program_cron_email($_SESSION['request_from_send_email_all'][$_GET['email_all_hash']], $mail_content, $mail_subject, $_SESSION['session_utilisateur']['email'], null, $frm['lang'], $custom_template_tags);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS["STR_MODULE_WEBMAIL_ADMIN_MSG_WILL_SEND_BY_CRON_OK"]))->fetch();
			unset($_SESSION['request_from_send_email_all'][$_GET['email_all_hash']]);
			// on va envoyer plus tard par cron
			$send_now = false;
			tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'SEND_EMAIL', $this_data, $this_comment, '', $_SESSION['count_from_send_email_all'][$_GET['email_all_hash']] . ' destinataires');
		} else {
			$destination_mail_array = explode(';', $frm['destination_mail']);
			foreach($destination_mail_array as $this_destination_mail) {
				// Récupération des données en fonction d'une adresse email pour les envois directs.
				$sql = 'SELECT *
					FROM peel_utilisateurs
					WHERE email="' . nohtml_real_escape_string($this_destination_mail) . '" AND ' . get_filter_site_cond('utilisateurs') . '';
				$query_user = query($sql);
				$user_infos = fetch_assoc($query_user);
				$user_template_tags = array();
				if (!empty($user_infos)) {
					$user_template_tags['CIVILITE'] = $user_infos['civilite'];
					$user_template_tags['PRENOM'] = $user_infos['prenom'];
					$user_template_tags['NOM_FAMILLE'] = $user_infos['nom_famille'];
					$user_template_tags['PSEUDO'] = $user_infos['pseudo'];
					$user_template_tags['SOCIETE'] = $user_infos['societe'];
					$user_template_tags['TELEPHONE'] = $user_infos['telephone'];
					$user_template_tags['EMAIL'] = $user_infos['email'];
					if (count($destination_mail_array) == 1) {
						// On va récupérer l'identité du seul utilisateur pour logguer l'action ci-dessous
						$frm['id_utilisateur'] = $user_infos['id_utilisateur'];
					}
				}
				$mail_sended = send_email($this_destination_mail, $mail_subject, $mail_content, $template_id, array_merge($custom_template_tags, $user_template_tags), null, $email_from, true, false, true, null, null);
				if ($mail_sended) {
					$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_WEBMAIL_ADMIN_MSG_SENT_OK'], $this_destination_mail)))->fetch();
				} else {
					$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_WEBMAIL_ADMIN_ERR_SENT'], $this_destination_mail)))->fetch();
				}
			}
			tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'SEND_EMAIL', $this_data, $this_comment, $frm['destination_mail'], $frm['destination_mail']);
		}
	}
	return $output;
}

/**
 * Affiche la liste des emails envoyer
 *
 * @param array $recherche Array with all fields data
 * @param boolean $return_mode
 * @return
 */
function affiche_list_send_mail($recherche, $return_mode = false)
{
	$output = '';
	$sql_cond = array();
	if (!empty($recherche)) {
		if (!empty($recherche['date'])) {
			$sql_cond[] = 'AND ac.date LIKE "' . nohtml_real_escape_string(get_mysql_date_from_user_input(vb($recherche['date']))) . '%"';
		}
		if (!empty($recherche['admin'])) {
			$sql_cond[] = 'AND ac.id_user = "' . nohtml_real_escape_string(vb($recherche['admin'])) . '"';
		}
		if (!empty($recherche['template'])) {
			$sql_cond[] = 'AND ac.data LIKE "%' . nohtml_real_escape_string(vb($recherche['template'])) . '%"';
		}
	}
	$sql = 'SELECT ac.*, u_admins.nom_famille AS admin_nom, u_admins.prenom AS admin_prenom, u_users.nom_famille AS user_nom, u_users.prenom AS user_prenom, u_users.pseudo AS user_login
		FROM peel_admins_actions ac
		INNER JOIN peel_utilisateurs u_admins ON u_admins.id_utilisateur = ac.id_user AND ' . get_filter_site_cond('utilisateurs', 'u_admins') . '
		LEFT JOIN peel_utilisateurs u_users ON u_users.id_utilisateur = ac.id_membre AND ' . get_filter_site_cond('utilisateurs', 'u_users') . '
		WHERE ac.action="SEND_EMAIL" ' . (!empty($sql_cond)? implode(' ', $sql_cond):'') . ' AND ' . get_filter_site_cond('admins_actions', 'ac') . '';

	$Links = new Multipage($sql, 'affiche_liste_send_email');
	$HeaderTitlesArray = array("admin_nom" => $GLOBALS["STR_ADMIN_ADMINISTRATOR"], 'date' => $GLOBALS['STR_DATE'], $GLOBALS["STR_MODULE_WEBMAIL_ADMIN_EMAIL_SENT"], 'data' => $GLOBALS["STR_ADMIN_EMAIL_TEMPLATE"], 'user_nom' => $GLOBALS["STR_ADMIN_USER"]);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = "ac.date";
	$Links->SortDefault = "DESC";
	$results_array = $Links->Query();

	$output .= '
		<form method="post" action=""' . get_current_url(false) . '"">
			<table class="affiche_list_send_mail">
				<tr>
					<td class="entete" colspan="2">'.$GLOBALS['STR_MODULE_WEBMAIL_ADMIN_LIST_TITLE'].'</td>
				</tr>
				<tr>
					<th>'.$GLOBALS['STR_ADMIN_DATE'].'' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
					<td>
						<input type="text" name="date" class="form-control datepicker" value="' . StringMb::str_form_value(vb(($recherche['date']))) . '" style="width:110px" />
						<input type="hidden" name="mode" value="search" />
					</td>
				</tr>
				<tr>
					<th>' . $GLOBALS['STR_ADMIN_ADMINISTRATOR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
					<td>
						<select name="admin" class="form-control">
							<option value="">' . $GLOBALS['STR_CHOOSE'] . '...</option>';
	// Requete récupérant la liste des admin disponibles sur le site
	$sql = 'SELECT id_utilisateur, nom_famille , prenom
		FROM peel_utilisateurs
		WHERE CONCAT("+",priv,"+") LIKE "%+admin%" AND ' . get_filter_site_cond('utilisateurs') . '
		ORDER BY id_utilisateur ASC';
	$query_admins = query($sql);
	while ($admins_array = fetch_assoc($query_admins)) {
		$output .= '
							<option value="' . intval(vn($admins_array['id_utilisateur'])) . '" ' . frmvalide(!empty($recherche['admin']) && $recherche['admin'] == $admins_array['id_utilisateur'], ' selected="selected"') . '>' . vb($admins_array['prenom']) . ' ' . vb($admins_array['nom_famille']) . '</option>';
	}
	$output .= '
						</select>
					</td>
				</tr>
				<tr>
					<th>' .$GLOBALS['STR_ADMIN_EMAIL_TEMPLATE']. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
					<td>
						<select name="template" id="template" class="form-control">
							' . get_email_template_options('id') . '
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="center">
						<input type="submit" value="'.$GLOBALS['STR_SEARCH'].'" class="btn btn-primary" />
					</td>
				</tr>
			</table>
		</form>
		<table class="main_table">
			<tr>
				<td>
					<div class="table-responsive">
						<table class="table">
							' . $Links->getHeaderRow();
	$i = 0;
	if (empty($results_array)) {
		$output .= '
							<tr><td colspan="5" class="left"><div class="alert alert-warning">'.$GLOBALS['STR_MODULE_WEBMAIL_ADMIN_NO_EMAIL_SENT_FOUND'].'</div></td></tr>';
	} else {
		foreach($results_array as $result) {
			$template_infos = getTextAndTitleFromEmailTemplateLang(null, null, $result['data']);
			$send_email_list = explode(',', $result['raison']);
			$nb_send_email_list = count($send_email_list);
			if ($nb_send_email_list > 1) {
				$multi_send_texte = '<i style="color:red;">Envoi multiple</i><br />';
				// Récupération du nombre d'envoi, qui est indiqué au début de la chaine de caractère dans le cas d'envoi multiple
			} else {
				$multi_send_texte = '';
			}
			$output .= tr_rollover($i, true) . '
								<td class="center">
									' . $result["admin_prenom"] . ' ' . $result["admin_nom"] . '
								</td>
								<td class="center">
									' . get_formatted_date(vb($result['date']), 'short', true) . '
								</td>
								<td class="center">';
			// Si un template a été envoyé, alors on récupère le contenu de ce template
			if (!empty($template_infos) && $result['remarque'] == $template_infos['text']) {
				$email_sended_infos = '<b>Template</b> : <br />' . $multi_send_texte . $template_infos["name"] . ($template_infos['text'] != StringMb::strip_tags($template_infos['text'])?' (HTML)':'');
			} else {
				$email_sended_infos = $multi_send_texte . $result['remarque'];
			}
			$output .= $email_sended_infos . '
								</td>
								<td class="center">';
			if (!empty($template_infos)) {
				$output .= $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_BASE_TEMPLATE_USED'] . ' ' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':<br />' . $template_infos['technical_code'] . ' - ' . StringMb::strtoupper($template_infos['lang']);
			} else {
				$output .= $result["data"];
			}

			$output .= '
								</td>
								<td class="center">';
			// Si il y un id_membre nous affichons le lien de la fiche utilisateur
			if ($nb_send_email_list > 1) {
				$output .= sprintf($GLOBALS['STR_MODULE_WEBMAIL_ADMIN_EMAIL_SENT_TO_N_CLIENTS'], $nb_send_email_list);
			} elseif (!empty($result['id_membre'])) {
				$output .= '<b><a href="' . $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . intval($result['id_membre']) . '">' . $result["user_prenom"] . ' ' . $result["user_nom"] . '</a></b><br />' . $result["user_login"];
			} else {
				$output .= (!empty($result['raison'])?$result['raison']:$GLOBALS['STR_INFORMATION_NOT_AVAILABLE']);
			}
			$output .= '
								</td>
							</tr>';
		}
	}
	$output .= '
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>' . $Links->GetMultipage() . '</td>
			</tr>
		</table>';
	if ($return_mode) {
		return $output;
	} elseif (!empty($output)) {
		echo $output;
	} else {
		return false;
	}
}

/**
 * Affiche la affiche la liste des emails reçus
 *
 * @param array $recherche Array with all fields data
 * @param boolean $return_mode
 * @return
 */
function affiche_list_received_mail($recherche, $return_mode = false, $return = 'full_html')
{
	$output = '';
	$sql_cond = array();
	if (!empty($recherche)) {
		// Recherche par date
		if (!empty($recherche['date']) && $recherche['date'] != 'any') {
			$sql_cond[] = ' w.date="' . nohtml_real_escape_string($recherche['date']) . '" ';
		}
		// Recherche par nom
		if (!empty($recherche['nom'])) {
			$sql_cond[] = ' w.nom LIKE "%' . nohtml_real_escape_string($recherche['nom']) . '%" ';
		}
		// Recherche par email
		if (!empty($recherche['email'])) {
			$sql_cond[] = ' w.email= "' . nohtml_real_escape_string($recherche['email']) . '" ';
		}
		// Recherche par email
		if (!empty($recherche['sujet'])) {
			$sql_cond[] = ' w.titre= "' . nohtml_real_escape_string($recherche['sujet']) . '" ';
		}
	}
	$sql = "SELECT w.*, u.pseudo AS login
		FROM peel_webmail w
		LEFT JOIN peel_utilisateurs u ON u.id_utilisateur = w.id_user AND " . get_filter_site_cond('utilisateurs', 'u') . "
		WHERE " . get_filter_site_cond('webmail', 'w', true) . "  " . (!empty($sql_cond)?' AND ' . implode(' AND ', $sql_cond):'') . "";
	$Links = new Multipage($sql, 'affiche_liste_send_email');
	$HeaderTitlesArray = array(' ', 'titre' => $GLOBALS['STR_ADMIN_TITLE'], 'nom' => $GLOBALS['STR_LAST_NAME'].'/'.$GLOBALS['STR_FIRST_NAME'].'/'.$GLOBALS['STR_DATE'], 'message' => $GLOBALS['STR_MESSAGE'], 'ip' => 'IP', 'site_id' => $GLOBALS["STR_ADMIN_WEBSITE"], 'update_datetime' => $GLOBALS["STR_VALIDATION_DATE"], 'read' => $GLOBALS["STR_STATUS"]);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = "w.Date, w.Heure";
	$Links->SortDefault = "DESC";
	$results_array = $Links->Query();

	$output .= '
<form method="post" action=""' . get_current_url(false) . '"">
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">'.$GLOBALS['STR_MODULE_WEBMAIL_ADMIN_RECEIVED_LIST_TITLE'].'</td>
		</tr>
		<tr>
			<td colspan="2">
				&nbsp;
				<input type="hidden" name="mode" value="search" />
			</td>
		</tr>
		<tr>
			<th>' . $GLOBALS['STR_DATE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
			<td>
				<select name="date" class="form-control">';
	// Affiche le select avec les differentes date d'email, ainsi qu'avec le signalement lu ou pas
	$row_affdate = query("SELECT count(*) AS this_count, `read`, `date`
		FROM peel_webmail
		WHERE " . get_filter_site_cond('webmail', null, true) . " 
		GROUP BY `date`, `read`
		ORDER BY `date` DESC ");
	while ($row_row_affdate = fetch_assoc($row_affdate)) {
		if (empty($messages_count[$row_row_affdate['date']])) {
			$messages_count[$row_row_affdate['date']] = 0;
			$messages_not_read[$row_row_affdate['date']] = 0;
		}
		$messages_count[$row_row_affdate['date']] += $row_row_affdate['this_count'];
		if ($row_row_affdate['read'] == 'NO') {
			$messages_not_read[$row_row_affdate['date']] += $row_row_affdate['this_count'];
		}
	}

	$i = 0;
	if (!empty($messages_count)) {
		foreach ($messages_count AS $this_date => $this_messages_count) {
			$jour = StringMb::substr($this_date, 8, 2);
			$mois = StringMb::substr($this_date, 5, 2);
			$annee = StringMb::substr($this_date, 0, 4);
			if ($messages_not_read[$this_date] > 0) {
				$style = ' style="color:#FF0000"';
			} else {
				$style = '';
			}
			if (empty($i)) {
				// C'est la première date : c'est possiblement la date du jour (sinon c'est qu'il n'y a pas de message aujourd'hui
				if (/*date('Y-m-d') == $this_date ||*/$messages_not_read[$this_date] > 0) {
					$style = ' style="color:#FF0000"';
				} else {
					$style = '';
				}
				$output .= '
							<option value="' . date('Y-m-d') . '" ' . frmvalide(!empty($recherche['date']) && $recherche['date'] == date('Y-m-d'), ' selected="selected"') . ' ' . $style . '>'.$GLOBALS['strToday'].'</option>
							<option value="any" ' . frmvalide(!empty($recherche['date']) && $recherche['date'] == 'any', ' selected="selected"') . '>'.$GLOBALS["STR_ALL"].'</option>';

				/* <option value="any_not_read"' . ((!empty($_GET['date'])) && ($_GET['date'] == 'any_not_read')?' selected="selected"':'') . '>'.$GLOBALS["STR_ALL"].'</option>'; */
			}
			$output .= '<option value="' . StringMb::str_form_value(vb($this_date)) . '" ' . frmvalide(!empty($recherche['date']) && $recherche['date'] == $this_date , ' selected="selected"') . ' ' . $style . '>' . $jour . "/" . $mois . "/" . $annee . ' ' . $GLOBALS['day_of_week'][date('w', strtotime($this_date))];
			if ($this_date == date('Y-m-d')) {
				$output .= ' ('.$GLOBALS['strToday'].')';
			} elseif ($this_date == date('Y-m-d', time() - 24 * 3600)) {
				$output .= ' ('.$GLOBALS['strYesterday'].')';
			}
			if ($this_messages_count - $messages_not_read[$this_date] > 0) {
				$output .= ' ' . ($this_messages_count - $messages_not_read[$this_date]) . ' lu';
				if (($this_messages_count - $messages_not_read[$this_date]) > 1) {
					$output .= 's';
				}
			}
			if ($messages_not_read[$this_date] > 0) {
				if ($this_messages_count - $messages_not_read[$this_date] > 0) {
					$output .= ' +';
				}
				$output .= ' ' . $messages_not_read[$this_date] . ' A LIRE';
			}
			$output .= '</option>
					';
			$i++;
		}
	}
	$output .= '
				</select>
			</td>
		</tr>
		<tr>
			<th>' . $GLOBALS['STR_ADMIN_NAME'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
			<td><input class="form-control" type="text" name="nom" value="' . StringMb::str_form_value(vb($recherche['nom'])) . '" /></td>
		</tr>
		<tr>
			<th>' . $GLOBALS['STR_EMAIL'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
			<td><input class="form-control" type="email" name="email" value="' . StringMb::str_form_value(vb($recherche['email'])) . '" autocapitalize="none" /></td>
		</tr>
		<tr>
			<th>' . $GLOBALS['STR_CONTACT_SUBJECT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
			<td>
				<select name="sujet" class="form-control">';
		$sujet_options = array(
			'' => $GLOBALS['STR_CONTACT_LB']);
				$output .= '
				<option value="">' . $GLOBALS['STR_CHOOSE'] . '</option>';
		for($i=1; isset($GLOBALS['STR_CONTACT_SELECT'.$i]); $i++) {
			if(!empty($GLOBALS['STR_CONTACT_SELECT'.$i])) {
				$output .= '
				<option '.(frmvalide($GLOBALS['STR_CONTACT_SELECT'.$i]==vb($_POST['sujet']), ' selected="selected"')).' value="' . StringMb::str_form_value($GLOBALS['STR_CONTACT_SELECT'.$i]) . '">' . $GLOBALS['STR_CONTACT_SELECT'.$i] . '</option>';
			}
		}
	$output .= '
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" value="'.$GLOBALS['STR_SEARCH'].'" class="btn btn-primary" />
			</td>
		</tr>
	</table>
</form>
<form method="post" action=""' . get_current_url(false) . '"">
	<div class="table-responsive" style="margin-top:10px">
		<input type="hidden" name="mode" value="change_state_mail" />';
$output_array = '
		<table id="tablesForm" class="table">
			' . $Links->getHeaderRow();
	$i = 0;
	if (empty($results_array)) {
		$output_array .= '<tr><td colspan="6" class="center"><b>'.$GLOBALS['STR_MODULE_WEBMAIL_ADMIN_NO_EMAIL_FOUND'].'</b></td></tr>';
	} else {
		$read_title_array = array('NO' => $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_TO_ANSWER'], 'READ' => $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_READ'], 'SEND' => $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_ANSWERED'], 'TREATED' => $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_TREATED']);
		
		foreach($results_array as $message) {
			$output_array .= tr_rollover($i, true) . '
				<td class="center" style="width:5px;">
					<input name="form_delete[]" type="checkbox" value="' . intval(vn($message['id'])) . '" id="cbx_' . intval(vn($message['id'])) . '" />
				</td>
				<td class="center" style="width:15%">
					<b>' . StringMb::strtoupper(vb($message['titre'])) . '</b><br /><span style="color:' . ($message['read'] == 'NO'?'Red':($message['read'] == 'SEND'?'Green':'Black')) . '">[' . $read_title_array[$message['read']] . ']</span><br /><br /><a style="' . ($message['read'] == 'NO'?'font-size:13px; color:Red':($message['read'] == 'SEND'?'color:Green':'color:Black')) . '" href="' . $GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/webmail_send.php?id_webmail=' . intval(vn($message['id'])) . '">' . sprintf(($message['read'] == 'SEND'?$GLOBALS["STR_MODULE_WEBMAIL_ADMIN_ANSWER_AGAIN"]:$GLOBALS["STR_MODULE_WEBMAIL_ADMIN_ANSWER_TO"]), $message['email']) . '</a>
				</td>
				<td class="center" style="width:15%">
					'.$GLOBALS["STR_ADMIN_NAME"].$GLOBALS['STR_BEFORE_TWO_POINTS'].': <b>' . ucfirst(vb($message['nom'])) . '</b><br />'.$GLOBALS["STR_FIRST_NAME"].$GLOBALS['STR_BEFORE_TWO_POINTS'].': <b>' . ucfirst(vb($message['prenom'])) . '</b><br />'.$GLOBALS["STR_TELEPHONE"].$GLOBALS['STR_BEFORE_TWO_POINTS'].': <b>' . vb($message['telephone']) . '</b><br />'.$GLOBALS["STR_DATE"].$GLOBALS['STR_BEFORE_TWO_POINTS'].': <b>' . vb($message['date']) . ' ' . vb($message['heure']) . '</b><br />' . (intval(vn($message['id_user'])) != 0? '<a href="' . $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . intval(vn($message['id_user'])) . '" style="color:Grey;">'.$GLOBALS["STR_CUSTOMER"].' # ' . intval(vn($message['id_user'])) . '<br />'.$GLOBALS["STR_ADMIN_LOGIN"].' : <b>' . vb($message['login']) . '</b>':'') . '
				</td>
				<td class="center" style="width:35%">
					<font color="' . (($message['read'] == 'NO')?'Red':'Black') . '">' . StringMb::nl2br_if_needed(StringMb::str_shorten_words(StringMb::strip_tags(trim($message['message'])), 60, ' ', true)) . '</font>' . ($message['id_user'] == 0 && $message['read'] == 'SEND'?'<p><a href="'. $GLOBALS['administrer_url'] . '/list_admin_actions.php?action_cat=SEND_EMAIL&search=' . vb($message['email']) . '&type=1"> ' . sprintf($GLOBALS["STR_MODULE_WEBMAIL_ADMIN_SEE_SENT_MESSAGES"], vb($message['email'])) . '</a></p>':'') . '' . (!empty($message['file'])?'<a href="' . get_url_from_uploaded_filename($message['file']) . '" target="message_file"><img src="' . $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($message['file'], 50, 50, 'fit') . '" /></a>':'') . '
				</td>
				<td class="center" style="width:15%">
				' . vb($message['ip']) . '
				</td>
				<td class="center" style="width:15%">
				' . get_site_name($message['site_id']) . '
				</td>
				<td class="center" style="width:15%">
				' . get_formatted_date($message['update_datetime'], 'short', 'long') . '
				</td>
				<td class="center" style="width:15%">';
				if ($message['read'] == 'NO') {
					// Le message n'est pas traité, on affiche un pastille blanche avec un lien pour changer en statut traité;
					$output_array .= '
					<a href="'.get_current_url(false).'?mode=change_state_mail&etat=TREATED&id=' . intval(vn($message['id'])) . '">
						<img src="' . $GLOBALS['administrer_url'] . '/images/puce-orange.gif" />
					</a>';
				} else {
					// Le message est traité.
					$output_array .= '
					<a href="'.get_current_url(false).'?mode=change_state_mail&etat=NO&id=' . intval(vn($message['id'])) . '">
						<img src="' . $GLOBALS['administrer_url'] . '/images/puce-verte.gif" />
					</a>';
				}
					$output_array .= '
				</td>
			</tr>';
			$i++;
		}
	}
	$output_array .= '
		</table>';
	if ($return == 'html_array') {
		return $output_array;
	}
	$output .= $output_array;
	$output .= '
	</div>
	<div class="center">
		<input type="button" value="'.$GLOBALS["STR_ADMIN_CHECK_ALL"].'" onclick="if (markAllRows(\'tablesForm\')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
		<input type="button" value="'.$GLOBALS["STR_ADMIN_UNCHECK_ALL"].'" onclick="if (unMarkAllRows(\'tablesForm\')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
		<input type="submit" value="'.$GLOBALS["STR_MODULE_WEBMAIL_ADMIN_MARK_AS_READ"].'" class="btn btn-primary" name="mail_is_read" />
		<input type="submit" value="'.$GLOBALS["STR_MODULE_WEBMAIL_ADMIN_MARK_AS_NOT_READ"].'" class="btn btn-primary" name="mail_is_not_read" />
		<input type="submit" value="'.$GLOBALS["STR_DELETE"].'" class="btn btn-primary" name="mail_delete" />
		<a href="'.get_current_url(false).'?mode=export&sujet='.vb($_POST['sujet']).'" class="btn btn-primary" >'.$GLOBALS["STR_ADMIN_EXPORT"].'</a>
	</div>
	<div class="center">' . $Links->GetMultipage() . '</div>
</form>';
	if ($return_mode) {
		return $output;
	} elseif (!empty($output)) {
		echo $output;
	} else {
		return false;
	}
}

/**
 * Fonction permettant de mettre à jour l'état d'un courrier lu ou non
 *
 * @param array $frm Array with all fields data
 * @return
 */
function update_state_mail($frm)
{
	$output = '';
	if (!empty($frm) && !empty($frm['form_delete'])) {
		if (!empty($frm['mail_is_not_read'])) {
			foreach($_POST['form_delete'] as $this_post => $this_value) {
				query('UPDATE `peel_webmail`
					SET `read`="NO"
					WHERE `id`="' . intval(vn($this_value)) . '" AND `read`!="NO" AND ' . get_filter_site_cond('webmail', null, true));
				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_WEBMAIL_ADMIN_MSG_STATUS_NOT_READ_OK"], intval(vn($this_value)))))->fetch();
			}
		} elseif (!empty($frm['mail_is_read'])) {
			foreach($_POST['form_delete'] as $this_post => $this_value) {
				query('UPDATE `peel_webmail`
					SET `read`="READ"
					WHERE `id`="' . intval(vn($this_value)) . '" AND `read`="NO" AND ' . get_filter_site_cond('webmail', null, true));
				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_WEBMAIL_ADMIN_MSG_STATUS_READ_OK"], intval(vn($this_value)))))->fetch();
			}
		} elseif (!empty($frm['mail_delete'])) {
			foreach($_POST['form_delete'] as $this_post => $this_value) {
				query('DELETE FROM `peel_webmail`
				WHERE `id`="' . intval(vn($this_value)) . '"');
				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_WEBMAIL_ADMIN_MSG_DELETE_OK"], intval(vn($this_value)))))->fetch();
			}
		}
	} elseif(!empty($frm['etat']) && !empty($frm['id'])) {
		query('UPDATE `peel_webmail`
			SET `read`="'.nohtml_real_escape_string($frm['etat']).'", update_datetime = "' . nohtml_real_escape_string(date('Y-m-d H:i:s', time())) . '"
			WHERE `id`="' . intval(vn($frm['id'])) . '" AND ' . get_filter_site_cond('webmail', null, true));
	}
	return $output;
}

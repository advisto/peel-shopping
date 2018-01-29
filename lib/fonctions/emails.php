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
// $Id: emails.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Envoi d'un email à un utilisateur
 *
 * @param string $to It can be a single email address or a list of addresses separated by coma or semicolon
 * @param string $mail_subject Sujet de l'email sous forme de texte (pas de HTML admis)
 * @param string $mail_content Contenu de l'email sous forme de texte ou de HTML
 * @param mixed $template_technical_code Si présent, alors le modèle d'email correspondant est chargé et fournit le sujet et le corps de l'emails (si vides dans les paramètres ci-avant). Si $template_technical_code est un tableau, on teste les code techniques dans l'ordre et on utilise le premier trouvé
 * @param array $template_tags Tableau de tags du type [TAG] : array("TAGNAME"=>tagvalue)
 * @param string $format Format de l'envoi : "html" ou "text"
 * @param mixed $sender Tableau d'informations de l'envoyeur, ou email apparaissant comme envoyeur. Sender email. If null, $GLOBALS['support'] is used instead
 * @param boolean $html_add_structure Ajoute les headers HTML au contenu envoyé
 * @param boolean $html_correct_conformity Corrige la validité du HTML
 * @param boolean $html_convert_url_to_links Convertit les URL du texte en balises A
 * @param string $reply_to Email de destinataires en copie
 * @param array $attached_files_infos_array contient le nom des fichiers à joindre, le chemin et le type-mime de chacun d'entre eux.
 * @param string $lang 
 * @param array $additional_infos_array contient des informations additionnelles pour d'éventuels hooks 
 * @param array $attachment_not_sent_by_email pour que la pièce jointe ne soit pas envoyé par email, mais pourra toujours être utilisée par une fonction hookable
 * @param boolean $filter_html_to_be_safe A activer si message envoyé par un utilisateur
 * @return
 */
function send_email($to, $mail_subject = '', $mail_content = '', $template_technical_code = null, $template_tags = null, $format = null, $sender = null, $html_add_structure = true, $html_correct_conformity = false, $html_convert_url_to_links = true, $reply_to = null, $attached_files_infos_array = null, $lang = null, $additional_infos_array = array(), $attachment_not_sent_by_email = false, $filter_html_to_be_safe = false)
{
	$emails_force_delivery_technical_codes = array('new_message', 'warn_message_filtered', 'initialise_mot_passe', 'ifu_cerfa2561volet1', 'retenues_fiscales', 'edi_cerfa2561');
	if($to == $GLOBALS['support'] || $to == $GLOBALS['support_sav_client'] || $to == $GLOBALS['support_commande']) {
		$for_admin_email = true;
	}

	if(!empty($GLOBALS['site_parameters']['email_webmaster_by_technical_code']) && !empty($GLOBALS['site_parameters']['email_webmaster_by_technical_code'][$template_technical_code])) {
		if(!empty($for_admin_email)) {
			$to = $GLOBALS['site_parameters']['email_webmaster_by_technical_code'][$template_technical_code];
		}
	}
	// Suivant les hébergements, on peut remplacer \r\n par \n
	$eol = "\r\n";
	// $eol = PHP_EOL;
	// $eol = "\n";
	if (empty($format)) {
		$format = vb($GLOBALS['site_parameters']['email_sending_format_default'], 'html');
	}
	if (defined('IN_PEEL_ADMIN') && a_priv('demo')) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_DEMO_EMAILS_DEACTIVATED']))->fetch();
		return false;
	}
	if(is_array($sender)) {
		$from = $sender['email'];
	} else {
		$from = $sender;
	}
	if (empty($from) && !empty($GLOBALS['support'])) {
		$from = $GLOBALS['support'];
	}
	$recipient_array = explode(',', str_replace(';', ',', $to));
	// On complète ci-dessous les tags avec les informations relatives à la première adresse email à laquelle on veut envoyer l'email
	// En effet, la fonction send_mail ne peut pas servir à envoyer des emails avec des tags différents pour chaque destinataire, ceci doit être géré par ailleurs dans une boucle appelant send_email
	// comme par exemple pour l'envoi de newsletter ou pour l'envoi d'emails par cron
	// Si ici on a plusieurs destinataires, le second et suivants reçoivent une copie de l'email envoyé au premier destinataire
	$query = query('SELECT *
		FROM peel_utilisateurs
		WHERE email="' . real_escape_string(current($recipient_array)) . '"
		LIMIT 1');
	if($result = fetch_assoc($query)) {
		foreach(array('civilite' => 'GENDER', 'nom_famille' => 'NOM_FAMILLE', 'prenom' => 'PRENOM', 'pseudo' => 'PSEUDO') as $database_key => $tag_key) {
			if(!isset($template_tags[$tag_key]) && isset($result[$database_key])) {
				$template_tags[$tag_key] = $result[$database_key];
			}
		}
		if(empty($for_admin_email) && empty($lang) && !empty($result['lang'])) {
			// Email pour un utilisateur et non pas un administrateur => on veut utiliser par défaut la langue de l'utilisateur
			$lang = $result['lang'];
		}
	}
	if(empty($lang) || !in_array($lang, $GLOBALS['admin_lang_codes'])){
		$lang = $_SESSION['session_langue'];
	}
	$used_template_technical_code = null;
	$mail_content_without_signature = $mail_content;
	if (!empty($template_technical_code)) {
		// Si on demande plusieurs codes de modèle d'email dans un tableau, alors on prend en priorité le premier si trouvé, sinon le second (qui peut par exemple être un modèle plus générique)
		if(!is_array($template_technical_code)) {
			$template_technical_codes_array = array($template_technical_code);
		} else {
			$template_technical_codes_array = $template_technical_code;
		}
		foreach($template_technical_codes_array as $this_template_technical_code) {
			$template_infos = getTextAndTitleFromEmailTemplateLang($this_template_technical_code, $lang);
			if (!empty($template_infos)) {
				// Si l'on envoie un email avec un sujet, un message et un template d'email, le template n'est pas prioritaire.
				// Ce fonctionnement est utile au module webmail lors de l'envoi d'email depuis le site.
				if (empty($mail_subject)) {
					$mail_subject = $template_infos['subject'];
				}
				if (empty($mail_content)) {
					$mail_content = $template_infos['text'];
				}
				if (!empty($mail_content)) {
					if (!empty($template_infos["default_signature_code"])) {
						// Récupération du technical code de la signature associé au template
						$signature = $template_infos["default_signature_code"];
					} else {
						$signature = 'signature';
					}
					$mail_content_without_signature = $mail_content;
					$signature_infos = getTextAndTitleFromEmailTemplateLang($signature, $lang);
					if (!empty($signature_infos)) {
						$signature_text = $signature_infos['text'];
						$mail_content .= $signature_infos['text'];
					}
				}
				$used_template_technical_code = $this_template_technical_code;
				break;
			}
		}
	}
	if (empty($mail_subject) && empty($mail_content)) {
		return false;
	}
	$mail_header = "Content-Transfer-Encoding: 8bit" . $eol;
	$mail_header .= "MIME-Version: 1.0" . $eol;
	if (!empty($from)) {
		// Au cas où $from ait plusieurs adresses emails (variable support par exemple)
		if ($from == $GLOBALS['support'] && !empty($GLOBALS['site_parameters']['nom_expediteur'])) {
			$email_name_rules = array("\r" => '', "\n" => '', "\t" => '', '"' => "'", ',' => '', '<' => '[', '>' => ']');
			$nom_expediteur = strtr($GLOBALS['site_parameters']['nom_expediteur'], $email_name_rules);
		} else {
			$nom_expediteur = '';
		}
		$temp = explode(',', str_replace(';', ',', $from));
		$from = trim($temp[0]);
		// création du header de l'email
		if (!empty($nom_expediteur)) {
			$mail_header .= 'From: "' . $nom_expediteur . '" <' . $from . '>' . $eol;
		} else {
			$mail_header .= 'From: ' . $from . $eol;
		}
		if (!empty($reply_to)) {
			// Au cas où $reply_to ait plusieurs adresses emails (variable support par exemple)
			$reply_to_array = explode(',', str_replace(';', ',', $reply_to));
			$reply_to = trim($reply_to_array[0]);
			$mail_header .= "Reply-To: " . $reply_to . "" . $eol;
		} else {
			$mail_header .= "Reply-To: " . $from . "" . $eol;
		}
		$mail_header .= "Return-Path:" . $from . "" . $eol;
	}
	if ($format != "text") {
		// On traite le modèle d'email avant de remplacer les tags car le modèle est peut-être en texte brut contrairement aux tags
		// ATTENTION ne pas utiliser StringMb::strip_tags car sinon les remplacements d'espaces divers altèreraient la validité du test ci-dessous
		if (strip_tags($mail_content) != $mail_content) {
			// On passe le contenu de l'email en HTML si ce n'est pas déjà le cas
			// Par exemple si on a mis des balises <b> ou <u> dans email sans mettre de <br /> nulle part, on rajoute <br /> en fin de ligne
			$mail_content = StringMb::nl2br_if_needed($mail_content);
		} else {
			// Email de texte qu'on va envoyer en HTML, et pour avoir une source d'email lisible on garde le \n à la fin
			// NB : il faut faire le replace en 2 fois pour éviter que le \n après le <br /> soit à nouveau remplacé !
			$mail_content = str_replace(array("\n"), "<br />\n", str_replace(array("\r\n", "\r"), array("\n", "\n"), $mail_content));
		}
	}
	// Traitement des tags dans les templates. Même si $template_tags est vide il faut le faire pour gérer les tags génériques
	// NB : Si on veut du HTML avec $format='html', le contenu de ces tags est converti par template_tags_replace en HTML
	$mail_content = template_tags_replace($mail_content, $template_tags, false, $format, $lang);
	$mail_content_without_signature = template_tags_replace($mail_content_without_signature, $template_tags, false, $format, $lang);
	$mail_subject = template_tags_replace(StringMb::strip_tags($mail_subject), $template_tags, false, 'text', $lang);
	if ($format == "text") {
		// On force le format en texte sans HTML
		$mail_content = trim(StringMb::html_entity_decode(StringMb::strip_tags($mail_content)));
		if (empty($attached_files_infos_array)) {
			// Pas de fichier attaché : on n'a pas besoin de déclarer des sections MIME
			$mail_header .= "Content-Type: text/plain; charset=" . GENERAL_ENCODING . "" . $eol;
		}
		$mail_body = $mail_content;
	} else {
		// Dans tous les cas, si les & ne sont pas encodés, on les encode. 
		// Sinon, problème avec certaines messageries qui croient que dans une URL, &currency est une entité pour laquelle il manque le point virgule
		// Si les & sont déjà encodés en &amp; la fonction suivante va les garder 
		$mail_content = StringMb::htmlentities($mail_content, null, GENERAL_ENCODING, false, true, false);
		if ($html_correct_conformity) {
			// On corrige le HTML si demandé
			$mail_content = StringMb::getCleanHTML($mail_content, null, true, true, true, null, $filter_html_to_be_safe);
		}
		if (empty($attached_files_infos_array)) {
			// Pas de fichier attaché : on n'a pas besoin de déclarer des sections MIME
			$mail_header .= "Content-Type: text/html; charset=" . GENERAL_ENCODING . "" . $eol;
		}
		// On transforme les liens [link=] ... [/link] en balises HTML <a>
		$mail_content = linkFormat($mail_content);
		if ($html_convert_url_to_links) {
			// On rend cliquables les URL qui étaient bruts
			$mail_content = url2Link($mail_content);
		}
		if (StringMb::strpos(StringMb::strtolower($mail_content), '<body') === false && StringMb::strpos($mail_content, '<!DOCTYPE') === false) {
			$mail_body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=' . GENERAL_ENCODING . '">
	<title>' . $mail_subject . '</title>
</head>
<body>
' . $mail_content . '
</body>
</html>';
		} else {
			$mail_body = $mail_content;
		}
	}
	// Ajout de fichiers attachés
	if (!empty($attached_files_infos_array) && !$attachment_not_sent_by_email) {
		$main_document_attached = $attached_files_infos_array['path_file_attachment'][0] . $attached_files_infos_array['name'][0]; 
		$mime_boundary_main = md5(uniqid() . 'iéhf|ao5225izah%0g'.mt_rand(1,1000000));
		$mime_boundary_html_or_plain = md5(mt_rand(1,1000000).'iéhf|ao5225izah%0g' . uniqid());
		// multipart/alternative
		$mail_header .= "Content-Type: multipart/mixed; boundary=\"" . $mime_boundary_main . "\"" . "" . $eol;

		$msg = "--" . $mime_boundary_main . "" . $eol;
		$msg .= "Content-Type: multipart/alternative; boundary=\"" . $mime_boundary_html_or_plain . "\"" . $eol . $eol;

		$msg .= "--" . $mime_boundary_html_or_plain . "" . $eol;
		$msg .= "Content-Type: text/plain; charset=" . GENERAL_ENCODING . "" . $eol;
		$msg .= "Content-Transfer-Encoding: 8bit" . "" . $eol . $eol;
		$msg .= trim(StringMb::strip_tags($mail_body)) . $eol;
		if ($format == "html") {
			// SI on envoie en HTML, on met en texte brut d'abord, et en HTML ensuite
			$msg .= "--" . $mime_boundary_html_or_plain . "" . $eol;
			$msg .= "Content-Type: text/html; charset=" . GENERAL_ENCODING . "" . $eol;
			$msg .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
			$msg .= $mail_body . $eol;
		}
		$msg .= "--".$mime_boundary_html_or_plain."--" . "" . $eol . $eol;
		for($j = 0;$j < count($attached_files_infos_array['name']); $j++) {
			if(!empty($attached_files_infos_array['content'][$j])) {
				$fichier = $attached_files_infos_array['content'][$j];
			} else {
				$fichier = file_get_contents($attached_files_infos_array['path_file_attachment'][$j] . $attached_files_infos_array['name'][$j]);
			}
			$msg .= "--" . $mime_boundary_main . "" . $eol;
			$msg .= "Content-Type: " . $attached_files_infos_array['type-mime'][$j] . "; name=\"" . $attached_files_infos_array['name'][$j] . "\"" . "" . $eol;
			$msg .= "Content-Transfer-Encoding: base64" . "" . $eol;
			$msg .= "Content-Disposition: attachment; filename=\"" . $attached_files_infos_array['name'][$j] . "\"" . "" . $eol . $eol;
			$msg .= chunk_split(base64_encode($fichier)) . "" . $eol . $eol;
		}
		$msg .= "--" . $mime_boundary_main . "--" . "" . $eol . $eol;
		$mail_body = $msg;
	}
	// Filtrage du message si la configuration l'exige
	if (!empty($GLOBALS['site_parameters']['filter_user_message_with_contact_information']) && !in_array($template_technical_code, $emails_force_delivery_technical_codes)) {
		if(!defined('IN_PEEL_ADMIN') && !empty($additional_infos_array['allow_content_filter'])) {
			if(PhoneIn($mail_subject) || MailIn($mail_subject) || PhoneIn($mail_content) || MailIn($mail_content)) {
				$custom_template_tags_warn = array();
				$custom_template_tags_warn['SUBJECT'] = $mail_subject;
				$custom_template_tags_warn['MESSAGE'] = $mail_content;
				$custom_template_tags_warn['DETECTED'] = (PhoneIn($mail_subject) || PhoneIn($mail_content)?'phone':'email');
				$custom_template_tags_warn['AUTHOR_ADMIN_URL'] = $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . vb($additional_infos_array['id_utilisateur']) . '&start=0';
		
				// $additional_infos_array n'est pas utilisé ci-dessous, et donc le filtrage de l'admin à l'administrateur ne sera pas fait, donc pas de problème de récursivité infinie
				send_email($GLOBALS['support_sav_client'], '', '', 'warn_message_filtered', $custom_template_tags_warn, null, $GLOBALS['support'], true, false, true, $GLOBALS['support']);
				return false;
			}
		}
	}
	$result = false;
	$i = 0;
	foreach($recipient_array as $this_email) {
		$this_email = trim($this_email);
		// Gestion d'actions complémentaires via un hook, par exemple messagerie interne
		if(is_array($sender)) {
			$params = $sender;
		} else {
			$params = array();
		}
		// On ajoute vb($_POST)); dans ce tableau pour passer un maximum d'info aux hook. Notamment les valeurs des champs upload $_POST['upload_multiple'].
		$params = array_merge($params, array('recipient_array' => array($this_email), 'from' => $from, 'mail_subject' => $mail_subject, 'mail_content' => $mail_content, 'technical_code' => $used_template_technical_code, 'document' => vb($main_document_attached)), $additional_infos_array, vb($_POST));
		if(empty($params['id_expediteur']) && !empty($from)) {
			if(!empty($params['id_utilisateur'])) {
				$params['id_expediteur'] = $params['id_utilisateur'];
			} else {
				$query = query('SELECT id_utilisateur
					FROM peel_utilisateurs
					WHERE email="' . real_escape_string($from) . '"');
				if($user_result = fetch_assoc($query)) {
					$params['id_expediteur'] = $user_result['id_utilisateur'];
				}
			}
		}
		unset($GLOBALS['send_email_just_warn_new_message']);
		unset($GLOBALS['skip_send_this_email']);
		if(!in_array($template_technical_code, $emails_force_delivery_technical_codes)) {
			call_module_hook('send_email', $params);
			// Pendant le hook ci-dessus, peuvent être activés :
			// - $GLOBALS['send_email_just_warn_new_message'] pour savoir si on va réellement envoyer l'email ou un email qui dit seulement qu'on a un nouveau message
			// - $GLOBALS['skip_send_this_email'] pour savoir si on veut envoyer un email ou pas du tout
		}
		if(!empty($GLOBALS['skip_send_this_email'])) {
			continue;
		}
		// Envoi de l'email si adapté
		if(empty($used_template_technical_code) || !in_array($used_template_technical_code, vb($GLOBALS['site_parameters']['send_email_technical_codes_no_email'], array()))) {
			if (empty($this_email) || $i > 10) {
				// Limitation à 10 destinataires en même temps par sécurité
				trigger_error('Email "' . $this_email . '" vide ou boucle sur plus de 10 emails', E_USER_NOTICE);
				continue;
			}
			if(!empty($GLOBALS['send_email_just_warn_new_message']) && !in_array($template_technical_code, $emails_force_delivery_technical_codes) && empty($GLOBALS['send_notification_disable'])) {
				// Un message interne vient d'être créé en base de données et donc le contenu de l'email envoyé est à changer : on envoie par email le template 'new_message' au destinataire et non pas le texte prévu
				// send_notification_disable : Dans certains cas, on va définir la global send_notification_disable plus haut dans le code pour ne pas envoyer de notification pour un email précis (qui n'a pas forcement de code technique). Ca sert pour les newsletter par exemple.
				if(!empty($params['id_expediteur'])) {
					$sender_infos = get_user_information($params['id_expediteur']);
				} else {
					$sender_infos = array();
				}
				$custom_template_tags_warn = array();
				// Dans l'email new_message, on remplit les tags avec les informations sur la personne qui est à l'origine de l'email(l'expéditeur).
				// Donc on remplit les tags SENDER_ avec les données de l'expéditeur : $sender_infos
				$custom_template_tags_warn['SENDER_PSEUDO'] = vb($sender_infos['pseudo']);
				$custom_template_tags_warn['SENDER_NOM_FAMILLE'] = vb($sender_infos['nom_famille']);
				$custom_template_tags_warn['SENDER_PRENOM'] = vb($sender_infos['prenom']);
				$custom_template_tags_warn['SENDER_SOCIETE'] = vb($sender_infos['societe']);
				$custom_template_tags_warn['SENDER_ADRESSE'] = vb($sender_infos['adresse']);
				$custom_template_tags_warn['SENDER_TELEPHONE'] = vb($sender_infos['telephone']);
				if (!empty($GLOBALS['site_parameters']['email_new_message_sender_is_support_email'])) {
					$custom_template_tags_warn['SENDER_EMAIL'] = $GLOBALS['support'];
				} else {
					$custom_template_tags_warn['SENDER_EMAIL'] = vb($from, vb($sender_infos['email']));
				}
				$custom_template_tags_warn['SENDER_DISPO'] = vb($sender_infos['dispo']);
				$custom_template_tags_warn['SUJET'] = $mail_subject;
				$mail_content_without_signature = str_replace(array("\r\n", "\r", "<br />"), "\n", $mail_content_without_signature);
				$content_email_array = explode("\n",$mail_content_without_signature);
				$mail_content = "";
				foreach($content_email_array as $this_line){
					$mail_content .= trim(StringMb::html_entity_decode(StringMb::strip_tags($this_line))) . "<br />";
					if (StringMb::strlen($mail_content)>40) {
						break;
					}
				}
				$custom_template_tags_warn['TEXTE'] =  $mail_content .'<br /><a href="'.get_url('/modules/messaging/messaging.php').'">'. $GLOBALS['STR_MODULE_DREAMTAKEOFF_MESSAGING_MORE_DETAIL'] . '</a>';
				// On désactive le paramètre d'envoi d'un nouveau message 
				if (!empty($GLOBALS['site_parameters']['email_new_message_sender_is_support_email'])) {
					$sender = $GLOBALS['support'];
					$reply_to = $GLOBALS['support'];
				}
				$result = send_email($to, '', '', 'new_message', $custom_template_tags_warn, $format, $sender, $html_add_structure, $html_correct_conformity, $html_convert_url_to_links, $reply_to, null, $lang, $additional_infos_array, true);	
				continue;
			}
			if(!empty($sender['status']) && $sender['status'] == 'FILTERED') {
				continue;
			}
			if (((strpos($GLOBALS['wwwroot'], '://localhost')===false && strpos($GLOBALS['wwwroot'], '://127.0.0.1')===false) || !empty($GLOBALS['site_parameters']['localhost_send_email_active'])) && !empty($GLOBALS['site_parameters']['send_email_active'])) {
				if(EmailOK($this_email)){
					if (StringMb::strtolower(GENERAL_ENCODING) != 'iso-8859-1') {
						$result = mail($this_email, '=?' . StringMb::strtoupper(GENERAL_ENCODING) . '?B?' . base64_encode($mail_subject) . '?=', $mail_body, $mail_header);
					} else {
						$result = mail($this_email, $mail_subject, $mail_body, $mail_header);
					}
					if(!empty($GLOBALS['site_parameters']['trigger_user_notice_email_sent']) && empty($GLOBALS['display_errors'])) {
						trigger_error('Email sent to ' . $this_email . ' : ' . $mail_subject, E_USER_NOTICE);
					}
				}else{
					trigger_error('Email invalide : ' . $this_email, E_USER_NOTICE);
				}
			} else {
				if(!IN_INSTALLATION) {
					// On n'affiche le message de désactivation des envois qu'à l'extérieur de l'installation
					$GLOBALS['notification_output_array'][] = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_EMAIL_SENDING_DEACTIVATED'], $mail_subject)))->fetch();
				}
			}
		}
		$i++;
	}
	return $result;
}

/**
 * Vérification du format d'adresse email trouvée sur http://www.phpinfo.net/?p=trucs&rub=astuces
 *
 * @param string $email
 * @param string $email_bounce
 * @return
 */
function EmailOK($email, $email_bounce = null)
{
	if(StringMb::substr($email_bounce, 0, 2) === '5.' || empty($email)) {
		return false;
	}
	// return(preg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+' . '@' . '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email));
	return(preg_match('/^[[:alnum:]]*((\.|_|-)[[:alnum:]]+)*@[[:alnum:]]*((\.|-)[[:alnum:]]+)*(\.[[:alpha:]]{2,})/i', $email));
}

/**
 * getTextAndTitleFromEmailTemplateLang()
 *
 * @param mixed $template_technical_code
 * @param mixed $template_lang
 * @param integer $template_technical_id
 * @return
 */
function getTextAndTitleFromEmailTemplateLang($template_technical_code, $template_lang, $template_technical_id = null)
{
	// Dans le cas de la newsletter, le titre ne doit pas être celui du template, mais le titre renseigné dans la liste des newsletters.
	$sql = 'SELECT *
		FROM peel_email_template
		WHERE active="TRUE"	AND ' . get_filter_site_cond('email_template', null);
	if(!empty($template_technical_id)) {
		$sql .= ' AND id="' . intval($template_technical_id) . '"';
	} else {
		$sql .= ' AND technical_code="' . nohtml_real_escape_string($template_technical_code) . '" AND (lang="' . word_real_escape_string($template_lang) . '" OR lang="")';
	}
	$sql .= 'LIMIT 1';
	$query_template = query($sql);
	if ($this_template = fetch_assoc($query_template)) {
		if(!empty($this_template['lang'])) {
			$this_lang = $this_template['lang'];
		} elseif(!empty($template_lang)) {
			$this_lang = $template_lang;
		} else {
			$this_lang = $_SESSION['session_langue'];
		}
		if ($this_template['technical_code'] != 'layout') {
			$generic_layout_infos = getTextAndTitleFromEmailTemplateLang('layout', $this_lang, null);
			if(!empty($generic_layout_infos['text'])) {
				// Lors de la fusion des templates, on passe en HTML les sauts de ligne si nécessaire pour chaque template
				$this_template['text'] = str_replace('[TEMPLATE]', StringMb::nl2br_if_needed($this_template['text']), StringMb::nl2br_if_needed($generic_layout_infos['text']));
			}
		}
		if (StringMb::strpos($this_template['text'], '[NEWSLETTER]') !== false) {
			// Le template contient une newsletter, on donne au template le sujet de la newsletter
			$news_infos = get_last_newsletter(null, $this_lang);
			if(!empty($news_infos['sujet_' . $this_lang])) {
				$this_template['subject'] = $news_infos['sujet_' . $this_lang];
			}
			// NB : Le contenu du tag [NEWSLETTER] sera remplacé comme tous les autres tags plus tard lors de l'appel à la fonction template_tags_replace
		}
		return $this_template;
	} else {
		return null;
	}
}

/**
 * Récupère les informations de la newsletter de l'id demandée, ou de la dernière newsletter dans une langue donnée. Cela sert lorsqu'on veut envoyer un email contenant le tag [NEWSLETTER] sans aucune information
 *
 * @param integer $id
 * @param string $lang
 * @return
 */
function get_last_newsletter($id = null, $lang = null) {
	if(!empty($id)) {
		$sql_cond_array[] = "id='".intval($id)."'";
	}
	if(!empty($lang) && empty($id)) {
		// On cherche une newsletter non vide pour la langue cherchée
		$sql_cond_array[] = "(sujet_".$lang."!='' OR message_".$lang."!='')";
	}
	if(empty($sql_cond_array)) {
		$sql_cond_array[] = 1;
	}
	$sql = "SELECT id, date, format, template_technical_code, statut, sujet_".$lang.", message_".$lang."
		FROM peel_newsletter
		WHERE " . implode(' AND ', $sql_cond_array) . " AND " . get_filter_site_cond('newsletter') . "
		ORDER BY id DESC
		LIMIT 1";
	$res = query($sql);
	return fetch_assoc($res);
}

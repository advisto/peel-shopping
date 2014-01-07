<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: emails.php 39443 2014-01-06 16:44:24Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Envoi d'un email à un utilisateur
 *
 * @param string $to It can be a single email address or a list of addresses separated by coma or semicolon
 * @param string $mail_subject Sujet de l'email sous forme de texte (pas de HTML admis)
 * @param string $mail_content Contenu de l'email sous forme de texte ou de HTML
 * @param mixed $template_technical_code Si présent, alors le modèle d'email correspondant est chargé et fournit le sujet et le corps de l'emails (si vides dans les paramètres ci-avant)
 * @param array $template_tags Tableau de tags du type [TAG] : array("TAGNAME"=>tagvalue)
 * @param string $format Format de l'envoi : "html" ou "text"
 * @param string $from Email apparaissant comme envoyeur. Sender email. If null, $GLOBALS['support'] is used instead
 * @param boolean $html_add_structure Ajoute les headers HTML au contenu envoyé
 * @param boolean $html_correct_conformity Corrige la validité du HTML
 * @param boolean $html_convert_url_to_links Convertit les URL du texte en balises A
 * @param string $reply_to Email de destinataires en copie
 * @param array $attached_files_infos_array contient le nom des fichiers à joindre, le chemin et le type-mime de chacun d'entre eux.
 * @param array $lang 
 * @return
 */
function send_email($to, $mail_subject = '', $mail_content = '', $template_technical_code = null, $template_tags = null, $format = 'html', $from = null, $html_add_structure = true, $html_correct_conformity = false, $html_convert_url_to_links = true, $reply_to = null, $attached_files_infos_array = null, $lang = null)
{
	// Suivant les hébergements, on peut remplacer \r\n par \n
	$eol = "\r\n";
	// $eol = PHP_EOL;
	// $eol = "\n";
	if(empty($lang) || !in_array($lang, $GLOBALS['admin_lang_codes'])){
		$lang = $_SESSION['session_langue'];
	}
	if (defined('IN_PEEL_ADMIN') && a_priv('demo')) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_DEMO_EMAILS_DEACTIVATED']))->fetch();
		return false;
	}
	if (empty($from) && !empty($GLOBALS['support'])) {
		$from = $GLOBALS['support'];
	}
	if (!empty($template_technical_code)) {
		$template_infos = getTextAndTitleFromEmailTemplateLang($template_technical_code, $lang);
		if (!empty($template_infos)) {
			// Si l'on envoi un email avec un sujet, un message et un template d'email, le template n'est pas prioritaire.
			// Ce fonctionnement est utile au module webmail lors de l'envoi d'email depuis la boutique.
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
				$signature_infos = getTextAndTitleFromEmailTemplateLang($signature, $lang);
				if (!empty($signature_infos)) {
					$mail_content .= $signature_infos['text'];
				}
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
		if ($from == $GLOBALS['support']) {
			$nom_expediteur = trim(vb($GLOBALS['site_parameters']['nom_expediteur']));
		} else {
			$nom_expediteur = '';
		}
		$from_array = explode(',', str_replace(';', ',', $from));
		$from = trim($from_array[0]);
		// création du header de l'email
		if (!empty($nom_expediteur)) {
			$mail_header .= "From: " . $nom_expediteur . ' <' . $from . '>' . $eol;
		} else {
			$mail_header .= "From: " . $from . $eol;
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
	if ($format == "text") {
		// On force le format en texte sans HTML
		$mail_content = String::strip_tags($mail_content);
	} else {
		// On passe le contenu de l'email en HTML si ce n'est pas déjà le cas
		if (String::strip_tags($mail_content) != $mail_content) {
			// Par exemple si on a mis des balises <b> ou <u> dans email sans mettre de <br /> nulle part, on rajoute <br /> en fin de ligne
			$mail_content = String::nl2br_if_needed($mail_content);
			if ($html_correct_conformity) {
				// On corrige le HTML si nécessaire
				$mail_content = String::getCleanHTML($mail_content, null, true, true, true, null, null);
			}
		} else {
			// Email de texte qu'on va envoyer en HTML, et pour avoir une source d'email lisible on garde le \n à la fin
			// NB : il faut faire le replace en 2 fois pour éviter que le \n après le <br /> soit à nouveau remplacé !
			$mail_content = str_replace(array("\n"), "<br />\n", str_replace(array("\r\n", "\r"), "\n", $mail_content));
		}
	}
	// Traitement des tags dans les templates. Même si $template_tags est vide il faut le faire pour gérer les tags génériques
	$mail_content = template_tags_replace($mail_content, $template_tags, false, $format, $lang);
	$mail_subject = template_tags_replace(String::strip_tags($mail_subject), $template_tags, false, 'text', $lang);
	if ($format == "text") {
		if (empty($attached_files_infos_array)) {
			// Pas de fichier attaché : on n'a pas besoin de déclarer des sections MIME
			$mail_header .= "Content-Type: text/plain; charset=" . GENERAL_ENCODING . "" . $eol;
		}
	} else {
		if (empty($attached_files_infos_array)) {
			// Pas de fichier attaché : on n'a pas besoin de déclarer des sections MIME
			$mail_header .= "Content-Type: text/html; charset=" . GENERAL_ENCODING . "" . $eol;
		}
		// On transforme les liens [link=] ... [/link] en balises HTML <a>
		$mail_content = linkFormat($mail_content);
		if ($html_convert_url_to_links && String::strpos($mail_content, '<a ') === false && String::strpos($mail_content, '<img ') === false) {
			// On rend cliquables les URL qui étaient bruts
			$mail_content = url2Link($mail_content);
		}
		if (String::strpos(String::strtolower($mail_content), '<body') === false && String::strpos($mail_content, '<!DOCTYPE') === false) {
			$mail_content = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=' . GENERAL_ENCODING . '">
	<title>' . $mail_subject . '</title>
</head>
<body>
' . $mail_content . '
</body>
</html>';
		}
		// Ajout de fichiers attachés
		if (!empty($attached_files_infos_array)) {
			$mime_boundary_main = md5(uniqid() . 'iéhf|ao5225izah%0g'.mt_rand(1,1000000));
			$mime_boundary_html_or_plain = md5(mt_rand(1,1000000).'iéhf|ao5225izah%0g' . uniqid());
			// multipart/alternative
			$mail_header .= "Content-Type: multipart/mixed; boundary=\"" . $mime_boundary_main . "\"" . "" . $eol;

			$msg = "--" . $mime_boundary_main . "" . $eol;
			$msg .= "Content-Type: multipart/alternative; boundary=\"" . $mime_boundary_html_or_plain . "\"" . $eol . $eol;

			$msg .= "--" . $mime_boundary_html_or_plain . "" . $eol;
			$msg .= "Content-Type: text/plain; charset=" . GENERAL_ENCODING . "" . $eol;
			$msg .= "Content-Transfer-Encoding: 8bit" . "" . $eol . $eol;
			$msg .= trim(String::strip_tags($mail_content)) . $eol;
			$msg .= "--" . $mime_boundary_html_or_plain . "" . $eol;
			$msg .= "Content-Type: text/html; charset=" . GENERAL_ENCODING . "" . $eol;
			$msg .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
			$msg .= $mail_content . $eol;
			$msg .= "--".$mime_boundary_html_or_plain."--" . "" . $eol . $eol;
			for($j = 0;$j < count($attached_files_infos_array['name']); $j++) {
				$fichier = file_get_contents($attached_files_infos_array['path_file_attachment'][$j] . $attached_files_infos_array['name'][$j]);
				$msg .= "--" . $mime_boundary_main . "" . $eol;
				$msg .= "Content-Type: " . $attached_files_infos_array['type-mime'][$j] . "; name=\"" . $attached_files_infos_array['name'][$j] . "\"" . "" . $eol;
				$msg .= "Content-Transfer-Encoding: base64" . "" . $eol;
				$msg .= "Content-Disposition: attachment; filename=\"" . $attached_files_infos_array['name'][$j] . "\"" . "" . $eol . $eol;
				$msg .= chunk_split(base64_encode($fichier)) . "" . $eol . $eol;
			}
			$msg .= "--" . $mime_boundary_main . "--" . "" . $eol . $eol;
			$mail_content = $msg;
		}
	}
	$to_array = explode(',', str_replace(';', ',', $to));
	$result = false;
	$i = 0;
	foreach($to_array as $this_email) {
		$this_email = trim($this_email);
		if (empty($this_email) || $i > 10) {
			// Limitation à 10 destinataires en même temps par sécurité
			continue;
		}
		if (strpos($GLOBALS['wwwroot'], '://localhost')===false && strpos($GLOBALS['wwwroot'], '://127.0.0.1')===false && !empty($GLOBALS['site_parameters']['send_email_active'])) {
			if(EmailOK($this_email)){
				if (String::strtolower(GENERAL_ENCODING) != 'iso-8859-1') {
					$result = mail($this_email, '=?' . String::strtoupper(GENERAL_ENCODING) . '?B?' . base64_encode($mail_subject) . '?=', $mail_content, $mail_header);
				} else {
					$result = mail($this_email, $mail_subject, $mail_content, $mail_header);
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
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_EMAIL_SENDING_DEACTIVATED'], $mail_subject)))->fetch();;
			}
		}
		$i++;
	}
	return $result;
}

/**
 * Vérification du format d'adresse email trouvée sur http://www.phpinfo.net/?p=trucs&rub=astuces
 *
 * @param mixed $email
 * @return
 */
function EmailOK($email)
{
	// return(preg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+' . '@' . '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email));
	return(preg_match('/^[[:alnum:]]*((\.|_|-)[[:alnum:]]+)*@[[:alnum:]]*((\.|-)[[:alnum:]]+)*(\.[[:alpha:]]{2,})/i', $email));
}

/**
 * Création d'un tableau contenant la correspondance entre nom des tags et valeur à utiliser
 *
 * @param mixed $user_id
 * @param integer $order_id
 * @return
 */
function prepare_email_tags($user_id, $order_id)
{
	if (!empty($user_id)) {
		$sql_additional_fields = '';
		$q = 'SELECT *' . $sql_additional_fields . '
			FROM peel_users
			WHERE id_utilisateur="' . intval($user_id) . '"';
		$result = query($q);
		if ($row_account = fetch_assoc($result)) {
			foreach($row_account as $key => $value) {
				if ($key != 'mot_passe') {
					$template_tags[String::strtoupper($key)] = $value;
				}
			}
		}
	}
	if (!empty($order_id)) {
		$q = 'SELECT o.code_facture
			FROM peel_commandes o
			WHERE o.id="' . intval($order_id) . '"';
		$result_orders = query($q);
		$row_orders = fetch_assoc($result_orders);
		$template_tags['ORDER'] = '';
		$template_tags['ORDER_LINK'] = '[link="' . $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . urlencode($row_orders['code_facture']) . '&amp;mode=facture"]Facture n°' . urlencode($_POST['form_order']) . '[/link]';
	}
	return $template_tags;
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
		WHERE active="TRUE"	';
	if(!empty($template_technical_id)) {
		$sql .= 'AND id="' . intval($template_technical_id) . '"';
	} else {
		$sql .= 'AND technical_code="' . nohtml_real_escape_string($template_technical_code) . '" AND (lang="' . word_real_escape_string($template_lang) . '" OR lang="")';
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
				$this_template['text'] = str_replace('[TEMPLATE]', String::nl2br_if_needed($this_template['text']), String::nl2br_if_needed($generic_layout_infos['text']));
			}
		}
		if (String::strpos($this_template['text'], '[NEWSLETTER]') !== false) {
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
	if(!empty($lang)) {
		$sql_cond_array[] = "(lang='' OR lang='" . word_real_escape_string($lang)  . "')";
	}
	if(empty($sql_cond_array)) {
		$sql_cond_array[] = 1;
	}
	$sql = "SELECT *
		FROM peel_newsletter
		WHERE " . implode(' AND ', $sql_cond_array) . "
		ORDER BY id DESC
		LIMIT 1";
	$res = query($sql);
	return fetch_assoc($res);
}
?>
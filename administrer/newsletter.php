<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: newsletter.php 36232 2013-04-05 13:16:01Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv('admin_content');

if (is_webmail_module_active()) {
	include($GLOBALS['dirroot'] . "/modules/webmail/administrer/fonctions.php");
}
$DOC_TITLE = $GLOBALS['STR_ADMIN_NEWSLETTERS_TITLE'];
include("modeles/haut.php");

$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_newsletter($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_newsletter($_GET['id'], $frm);
		break;

	case "suppr" :
		supprime_newsletter($_GET['id']);
		affiche_liste_newsletter();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_newsletter($_POST);
			affiche_liste_newsletter();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_newsletter($frm);
		}
		break;

	case "send" :
		if (!verify_token($_SERVER['PHP_SELF'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$limit = 1; // nombre de messages envoyés pas boucles.
			if (!isset($debut)) {
				$debut = 0;
			} else {
				$debut = intval($_GET['debut']);
			}
			$id = intval($_GET['id']);
			send_newsletter($id, $debut, $limit, !empty($_GET['test']));
		} elseif ($form_error_object->has_error('token')) {
			echo $form_error_object->text('token');
		}
		affiche_liste_newsletter();
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_newsletter($_POST['id'], $_POST);
			affiche_liste_newsletter();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_newsletter($_POST['id'], $frm);
		}
		break;

	default :
		affiche_liste_newsletter();
		break;
}

include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un newsletter
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_newsletter(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm['sujet'] = "";
		$frm['message'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['format'] = "html";
	$frm['titre_bouton'] = $GLOBALS["STR_ADMIN_NEWSLETTERS_CREATE"];

	affiche_formulaire_newsletter($frm);
}

/**
 * Affiche le formulaire de modification pour le newsletter sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_newsletter($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_newsletter
			WHERE id = " . intval($id));
		$frm = fetch_assoc($qid);
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_newsletter($frm);
}

/**
 * affiche_formulaire_newsletter()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_newsletter(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_newsletter.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('template_technical_code_options', get_email_template_options('technical_code', null, null, vb($frm['template_technical_code'])));

	$tpl_langs = array();
	foreach ($GLOBALS['lang_codes'] as $this_lang) {
		$tpl_langs[] = array('lng' => $this_lang,
			'sujet' => vb($frm['sujet_' . $this_lang]),
			'message_te' => getTextEditor('message_' . $this_lang, 760, 500, vb($frm['message_' . $this_lang]))
			);
	}
	$tpl->assign('langs', $tpl_langs);

	$tpl->assign('titre_bouton', $frm['titre_bouton']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_FORM_TITLE', $GLOBALS['STR_ADMIN_NEWSLETTERS_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_WARNING', $GLOBALS['STR_ADMIN_NEWSLETTERS_WARNING']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE', $GLOBALS['STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE']);
	$tpl->assign('STR_ADMIN_SUBJECT', $GLOBALS['STR_ADMIN_SUBJECT']);
	$tpl->assign('STR_MESSAGE', $GLOBALS['STR_MESSAGE']);
	echo $tpl->fetch();
}

/**
 * Supprime la newsletter spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_newsletter($id)
{
	$qid = query("SELECT *
		FROM peel_newsletter
		WHERE id = " . intval($id));
	$n = fetch_assoc($qid);

	/* Efface le newsletter */
	query("DELETE FROM peel_newsletter WHERE id=" . intval($id));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_NEWSLETTERS_MSG_NEWSLETTER_DELETED'], $n['sujet_' . $_SESSION['session_langue']])))->fetch();
}

/**
 * Ajoute le newsletter dans la table newsletter
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_newsletter($frm)
{
	$req = "INSERT INTO peel_newsletter (";
	// Insertion de la nouvelle news en fonction des langues définit sur le site
	foreach($GLOBALS['lang_codes'] as $this_lang) {
		$req .= "sujet_" . $this_lang . ", message_" . $this_lang . ",";
	}
	$req .= "date, format, statut, template_technical_code) VALUES (";
	foreach($GLOBALS['lang_codes'] as $this_lang) {
		$req .= "'" . nohtml_real_escape_string($frm['sujet_' . $this_lang]) . "','" . real_escape_string($frm['message_' . $this_lang]) . "',";
	}
	$req .= " '" . date('Y-m-d H:i:s', time()) . "', 'html', 'envoi nok', '" . nohtml_real_escape_string($frm['template_technical_code']) . "')";

	$qid = query($req);
}

/**
 * Met à jour le newsletter $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_newsletter($id, $frm)
{
	$req = "UPDATE peel_newsletter SET ";
	// Maj d'une news en fonction des langues définit sur le site
	foreach($GLOBALS['lang_codes'] as $this_lang) {
		$req .= "sujet_" . $this_lang . " = '" . nohtml_real_escape_string($frm['sujet_' . $this_lang]) . "', message_" . $this_lang . " = '" . real_escape_string($frm['message_' . $this_lang]) . "',";
	}
	$req .= " format = 'html', date = '" . date('Y-m-d H:i:s', time()) . "', template_technical_code= '" . nohtml_real_escape_string($frm['template_technical_code']) . "'  WHERE id = '" . intval($id) . "'";
	$qid = query($req);
}

/**
 * Récupération des données de la newsletter
 *
 * @param integer $id
 * @param mixed $debut
 * @param mixed $limit
 * @param mixed $subscribers_number
 * @return
 */
function send_newsletter($id, $debut, $limit, $test = false)
{
	$sql_n = "SELECT *
		FROM peel_newsletter
		WHERE id = '" . intval($id) . "'";
	$res_n = query($sql_n);
	$news_infos = fetch_assoc($res_n);

	$format = $news_infos['format'];
	// Récupération technical_code du template associé à la newsletter
	$template_technical_code = $news_infos['template_technical_code'];
	// Stockage des messages et sujets, selon les langues disponibles sur le site
	foreach($GLOBALS['lang_codes'] as $this_lang) {
		// Ajout des Custom template tag de la newsletter en fonction de la langue
		$custom_template_tags[$this_lang] = null;
		if (!empty($news_infos['message_' . $this_lang])) {
			// Récupération du template email associé à la newsletter en fonction des langues disponible
			if (!empty($template_technical_code)) {
				$template_infos = getTextAndTitleFromEmailTemplateLang($template_technical_code, $this_lang);
				$message[$this_lang] = $template_infos['text'];
				$custom_template_tags[$this_lang]['STR_NEWSLETTER'] = $news_infos['message_' . $this_lang];
			} else {
				$message[$this_lang] = $news_infos['message_' . $this_lang];
			}
			// Le sujet de la newsletter est prioritaire sur celui du template
			$sujet[$this_lang] = $news_infos['sujet_' . $this_lang];
		}
	}
	/* Récupération de la liste des emails */
	if (!empty($message)) {
		foreach(array_keys($message) as $this_lang) {
			if (!$test) {
				$sql_cond = "newsletter='1' AND etat='1' AND email_bounce NOT LIKE '5.%' AND email!='' AND (lang='" . nohtml_real_escape_string($this_lang) . "' OR lang='')";
			} else {
				$sql_cond = "priv LIKE '%admin%' AND etat='1' AND email_bounce NOT LIKE '5.%' AND email!=''";
				$sujet[$this_lang] .= ' [envoyé aux administrateurs seulement]';
			}
			$sql_u = "SELECT *
				FROM peel_utilisateurs u
				WHERE " . $sql_cond;
			// Le SQL suivant va permettre de récupérer des données utilisateurs pouvant servir dans des TAGS
			// => il faut mettre tous les champs de la table utilisateurs, à appeler u
			if (is_crons_module_active()) {
				// Envoi de la newsletter dans la langue définie par l'utilisateur lors de son inscription ou modification de ces paramètres
				// Les emails seront envoyés a posteriori avec un cron
				// Si nous avons des tags à remplacer dans le contenue
				$message[$this_lang] = template_tags_replace($message[$this_lang], $custom_template_tags[$this_lang], true);
				program_cron_email($sql_u, $message[$this_lang], $sujet[$this_lang], $_SESSION['session_utilisateur']['email'], null, $this_lang);
				query("UPDATE peel_newsletter
					SET statut='envoi ok', date_envoi='" . date('Y-m-d H:i:s', time()) . "'
					WHERE id='" . intval($news_infos['id']) . "'");
				$newsletter_name_info = $id . ' (' . $this_lang . ') "' . $sujet[$this_lang] . '"';
				if (!$test) {
					$message = $GLOBALS['STR_ADMIN_NEWSLETTERS_MSG_SEND_SUBSCRIBERS'];
				} else {
					$message = $GLOBALS['STR_ADMIN_NEWSLETTERS_MSG_SEND_ADMINISTRATORS'];
				}
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($message, $newsletter_name_info)))->fetch();
			} else {
				$sql_u .= "
					LIMIT " . intval($debut) . "," . intval($limit);
				$res_u = query($sql_u);
				// Envoi de la newsletter dans la langue définie par l'utilisateur lors de son inscription ou modification de ces paramètres
				$i = 0;
				while ($row = fetch_assoc($res_u)) {
					if (send_email($row['email'], $sujet[$this_lang], $message[$this_lang], '', $custom_template_tags[$this_lang], $format, $GLOBALS['support'])) {
						$result = 'OK';
					} else {
						$result = 'NOK';
					}
					if (!$test) {
						$fc = String::fopen_utf8("sending.log", "ab");
						$w = fwrite ($fc, "[" . $row['email'] . "]\t\t\t " . $result . "\n");
						fclose($fc);
					}
					$i++;
				}
				if ($i >= $limit && $debut + $i < 250) {
					// On continue à envoyer la newsletter
					sleep(1);
					send_newsletter($id, $debut + $i, min($limit, 250 - ($debut + $i)), $test);
				} else {
					query("UPDATE peel_newsletter
						SET statut='envoi ok', date_envoi='" . date('Y-m-d H:i:s', time()) . "'
						WHERE id='" . intval($news_infos['id']) . "'");
					echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_NEWSLETTERS_MSG_SENT_OK'], $id, $sujet[$this_lang], $debut + $i)))->fetch();
				}
			}
		}
	}
}

/**
 * affiche_liste_newsletter()
 *
 * @return
 */
function affiche_liste_newsletter()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_newsletter.tpl');
	$tpl->assign('is_crons_module_active', is_crons_module_active());
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('mail_src', $GLOBALS['administrer_url'] . '/images/mail.gif');

	$result = query("SELECT *
		FROM peel_newsletter
		ORDER BY date DESC");
	if (!(num_rows($result) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($result)) {
			$this_langs_array = array();
			$titre = $ligne['sujet_' . $_SESSION['session_langue']];
			foreach($GLOBALS['lang_codes'] as $this_lang) {
				// Ajout des Custom template tag de la newsletter en fonction de la langue
				if (!empty($ligne['message_' . $this_lang])) {
					$this_langs_array[] = $this_lang;
				}
				if (empty($titre)) {
					$titre = $ligne['sujet_' . $this_lang];
				}
			}
			$titre = '[' . String::strtoupper(implode(",", $this_langs_array)) . '] ' . $titre;
			$sql_u = "SELECT email
				FROM peel_utilisateurs
				WHERE newsletter = '1' AND etat='1' AND email_bounce NOT LIKE '5.%' AND email!='' AND lang IN ('" . implode("','", $this_langs_array) . "')";
			$res_u = query($sql_u);
			$subscribers_number = num_rows($res_u);
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'sujet' => $ligne['sujet_' . $_SESSION['session_langue']],
				'statut' => $titre,
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'date' => get_formatted_date($ligne['date']),
				'subscribers_number' => $subscribers_number,
				'format' => $ligne['format'],
				'statut' => $ligne['statut'],
				'date_envoi' => $ligne['date_envoi'],
				'mail_href' => get_current_url(false) . '?mode=send&id=' . $ligne['id'] . '&format=' . $ligne['format'] . '&token=' . get_form_token_input($_SERVER['PHP_SELF'], true, false),
				'test_href' => get_current_url(false) . '?mode=send&id=' . $ligne['id'] . '&format=' . $ligne['format'] . '&test=test&token=' . get_form_token_input($_SERVER['PHP_SELF'], true, false)
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_ADMIN_NEWSLETTERS_TITLE', $GLOBALS['STR_ADMIN_NEWSLETTERS_TITLE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_CRON_ACTIVATED_EXPLAIN', $GLOBALS['STR_ADMIN_NEWSLETTERS_CRON_ACTIVATED_EXPLAIN']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_CRON_DEACTIVATED_EXPLAIN', $GLOBALS['STR_ADMIN_NEWSLETTERS_CRON_DEACTIVATED_EXPLAIN']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_CREATE', $GLOBALS['STR_ADMIN_NEWSLETTERS_CREATE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_CREATION_DATE', $GLOBALS['STR_ADMIN_CREATION_DATE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_SUBSCRIBERS_NUMBER', $GLOBALS['STR_ADMIN_NEWSLETTERS_SUBSCRIBERS_NUMBER']);
	$tpl->assign('STR_ADMIN_FORMAT', $GLOBALS['STR_ADMIN_FORMAT']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_LAST_SENDING', $GLOBALS['STR_ADMIN_NEWSLETTERS_LAST_SENDING']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_SEND_TO_USERS', $GLOBALS['STR_ADMIN_NEWSLETTERS_SEND_TO_USERS']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_SENDING_TEST', $GLOBALS['STR_ADMIN_NEWSLETTERS_SENDING_TEST']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_UPDATE', $GLOBALS['STR_ADMIN_NEWSLETTERS_UPDATE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_SEND_ALL_USERS', $GLOBALS['STR_ADMIN_NEWSLETTERS_SEND_ALL_USERS']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_NOTHING_FOUND', $GLOBALS['STR_ADMIN_NEWSLETTERS_NOTHING_FOUND']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_TEST_SENDING_TO_ADMINISTRATORS', $GLOBALS['STR_ADMIN_NEWSLETTERS_TEST_SENDING_TO_ADMINISTRATORS']);
	echo $tpl->fetch();
}

?>
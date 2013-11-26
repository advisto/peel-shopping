<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: utilisateurs.php 38734 2013-11-15 19:47:31Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users");

if (is_parrainage_module_active()) {
	include($GLOBALS['dirroot'] . "/modules/parrainage/administrer/fonctions.php");
}
if (is_webmail_module_active()) {
	include($GLOBALS['dirroot'] . "/modules/webmail/administrer/fonctions.php");
}
$DOC_TITLE = $GLOBALS['STR_ADMIN_UTILISATEURS_TITLE'];
/* Initialisation des variables */
$id = intval(vn($_REQUEST['id']));
$id_utilisateur = intval(vn($_REQUEST['id_utilisateur']));
$frm = $_POST;
$form_error_object = new FormError();
$output = '';

$priv = vb($_GET['priv']);
$cle = trim(vb($_GET['cle']));

if (!empty($_POST['print_all_bill'])) {
	include("../lib/class/Invoice.php");
	$invoice_pdf = new Invoice('P', 'mm', 'A4');
	$user_id = vb($_POST['user_id']);
	if (!verify_token($_SERVER['PHP_SELF'] . $user_id)) {
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	if (!$form_error_object->count()) {
		$is_pdf_generated = $invoice_pdf->FillDocument(null, null, null, null, null, $user_id, null, 'standard', false);
		if($is_pdf_generated) {
			die();
		}
	} else {
		if ($form_error_object->has_error('token')) {
			$output .= $form_error_object->text('token');
		}
	}
}

switch (vb($_REQUEST['mode'])) {
	case "modif_etat" :
		if (isset($_GET['etat']) && !empty($_GET['id'])) {
			if ($_GET['etat'] == 1) {
				$etat = 0 ;
			} else {
				$etat = 1 ;
			}
			query('UPDATE peel_utilisateurs
				SET etat="' . intval($etat) . '"
				WHERE id_utilisateur="' . intval($_GET['id']) . '"');
			$annonce_active = false;
			if (is_annonce_module_active()) {
				update_state_ads($_GET['id'], $etat);
				$annonce_active = true;
			}
		}
		if($etat == 1) {
			$message = $GLOBALS['STR_ADMIN_UTILISATEURS_MSG_ACTIVATED_OK'] . ($annonce_active?' - ' . $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_ADS_ALSO_ACTIVATED'] : '');
		} else {
			$message = $GLOBALS['STR_ADMIN_UTILISATEURS_MSG_DEACTIVATED_OK'] . ($annonce_active?' - ' . $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_ADS_ALSO_DEACTIVATED'] : '');
		}
		$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($message, vb($utilisateur['email']))))->fetch();
		$output .= afficher_liste_utilisateurs($priv, $cle);
		break;
		
	case "ajout" :
		$output .= afficher_formulaire_ajout_utilisateur();
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;

	case "suppr" :
		$utilisateur = get_user_information($id_utilisateur);
		$output .= efface_utilisateur($id_utilisateur);
		$annonce_active = false;
		if (is_annonce_module_active()) {
			$output .= delete_user_ads($id_utilisateur);
			$annonce_active = true;
		}
		$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_DELETED_OK'] . ($annonce_active?' - ' . $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_ADS_ALSO_DEACTIVATED'] : ''), $utilisateur['email'])))->fetch();
		$output .= afficher_liste_utilisateurs($priv, $cle);
		break;

	case "supprlogo" :
		$output .= supprime_logo($id_utilisateur);
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;

	case "insere" :
		$form_error_object->valide_form($frm,
			array('email' => 'Vous devez insérez un email'));
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $id_utilisateur)) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!EmailOk($frm['email'])) {
			// si il y a un email on teste l'email
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
		} elseif ((num_rows(query("SELECT 1
			FROM peel_utilisateurs
			WHERE email = '" . nohtml_real_escape_string($frm['email']) . "'")) > 0)) {
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_STILL']);
		}
		if ((num_rows(query("SELECT 1
			FROM peel_utilisateurs
			WHERE pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "'")) > 0)) {
			$form_error_object->add('pseudo', $GLOBALS['STR_ERR_NICKNAME_STILL']);
		}
		if (!$form_error_object->count()) {
			$frm['logo'] = upload('logo', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['logo']));
			$frm['document'] = upload('document', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['document']));
			$frm['mot_passe'] = (!empty($frm['mot_passe']))?$frm['mot_passe']:MDP();
			if (insere_utilisateur($frm, false, false, false)) {
				$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_CREATED_OK'], vb($frm['email']), $frm['mot_passe'])))->fetch();
			}
			// Envoi de l'e-mail
			if (isset($frm['notify'])) {
				$output .= send_mail_for_account_creation(vb($frm['email']), vb($frm['mot_passe']));
			}
			$output .= afficher_liste_utilisateurs($priv, $cle);
		} else {
			if ($form_error_object->has_error('token')) {
				$output .=  $form_error_object->text('token');
			} elseif ($form_error_object->has_error('email')) {
				$output .=  $form_error_object->text('email');
			}
			if ($form_error_object->has_error('pseudo')) {
				$output .=  $form_error_object->text('pseudo');
			}
			$output .= afficher_formulaire_ajout_utilisateur();
		}
		break;

	case "maj" :
		$form_error_object->valide_form($frm,
			array('email' => 'Vous devez insérez un email'));
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $id_utilisateur)) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!EmailOk($frm['email'])) {
			// si il y a un email on teste l'email
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
		}
		if (!empty($frm['pseudo']) && (num_rows(query("SELECT 1
			FROM peel_utilisateurs
			WHERE id_utilisateur!='" . intval($frm['id_utilisateur']) . "' AND pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "'")) > 0)) {
			$form_error_object->add('pseudo', $GLOBALS['STR_ERR_NICKNAME_STILL']);
		}
		if (!$form_error_object->count()) {
			$frm['logo'] = upload('logo', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['logo']));
			$frm['document'] = upload('document', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['document']));
			// Suppression de l'ancien fichier
			if (!empty($frm['old_document']) && $frm['document'] != $frm['old_document']) {
				delete_uploaded_file_and_thumbs($frm['old_document']);
			}
			maj_utilisateur($frm, false);
			$output .= tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'EDIT_PROFIL', 'Compte : ' . vb($frm['email']));
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_UPDATED_OK'], vb($frm['email']))))->fetch();
			$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		} else {
			if ($form_error_object->has_error('token')) {
				$output .=  $form_error_object->text('token');
			} elseif ($form_error_object->has_error('email')) {
				$output .=  $form_error_object->text('email');
			}
			if ($form_error_object->has_error('pseudo')) {
				$output .=  $form_error_object->text('pseudo');
			}
			$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		}
		break;

	case "liste" :
		$output .= afficher_liste_utilisateurs($priv, $cle);
		break;

	case "cheque" :
		if (is_module_gift_checks_active()) {
			include($fonctionsgiftcheck);
			// L'administrateur a validé l'envoi d'un chèque cadeau à l'utilisateur
			cree_cheque_cadeau_client(vn($id_utilisateur), "CHQ", $GLOBALS['site_parameters']['avoir'], 2);
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_GIFT_CHECK_SENT']))->fetch();
		}
		$output .= afficher_liste_utilisateurs($priv, $cle);
		break;

	case "init_mdp" :
		$output .= initialise_mot_passe($_REQUEST['email']);
		$qid = query("SELECT email
			FROM peel_utilisateurs
			WHERE email = '" . nohtml_real_escape_string($_REQUEST['email']) . "'");
		if ($user = fetch_object($qid)) {
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_NEW_PASSWORD_SENT'], vb($user->email))))->fetch();
		}
		$output .= afficher_liste_utilisateurs($priv, $cle);

		break;

	case "enligne_liste_annonce" :
	case "update_list_annonce" :
		if (is_annonce_module_active()) {
			$output .=  annonce_manipulation($form_error_object, 'users');
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "phone_call" :
		if ((!empty($_POST['phone_emitted_submit']) || !empty($_GET['phone_emitted_submit']))) {
			tracert_history_admin(intval($_REQUEST['id_utilisateur']), 'PHONE_EMITTED', 'NOT_ENDED_CALL', nohtml_real_escape_string((!empty($_POST['form_phone_comment'])?$_POST['form_phone_comment']:'')));
			if (!empty($_GET['callee']) && is_phone_cti_module_active()) {
				$query = query('SELECT telephone, pays
					FROM peel_utilisateurs
					WHERE id_utilisateur="' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '" AND telephone!=""');
				if ($admin_infos = fetch_assoc($query)) {
					// Déclenchement de l'appel
					$makecall = file('https://ssl.keyyo.com/makecall.html?ACCOUNT=' . getCleanInternationalTelephone($admin_infos['telephone'], $admin_infos['pays'], true) . '&CALLEE=' . $_GET['callee'] . '&CALLEE_NAME=' . $_GET['callee_name']);
					if (!empty($makecall)) {
						$output .=$GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_CALL_INITIATED_KEYYO'], getCleanInternationalTelephone($admin_infos['telephone'], $admin_infos['pays'], true), $_GET['callee_name'], $_GET['callee'], implode(' - ', $makecall))))->fetch();
					}
				}
			}
		}
		if (!empty($_POST['phone_received_submit'])) {
			tracert_history_admin(intval($_REQUEST['id_utilisateur']), 'PHONE_RECEIVED', 'NOT_ENDED_CALL', nohtml_real_escape_string((!empty($_POST['form_phone_comment'])?$_POST['form_phone_comment']:'')));
		}
		if (!empty($_POST['turn_off_phone'])) {
			// On ne peut pas utiliser tracert_history_admin car action SQL trop particulière
			$q = query('UPDATE peel_admins_actions
				SET raison="",
					remarque="' . nohtml_real_escape_string($_POST['form_phone_comment']) . '",
					data="' . date('Y-m-d H:i:s', time()) . '"
				WHERE id_user="' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '" AND id_membre="' . intval($_REQUEST['id_utilisateur']) . '" AND ((action = "PHONE_EMITTED") OR (action = "PHONE_RECEIVED")) AND data="NOT_ENDED_CALL"');
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "event_comment":
		if (!empty($_POST['form_event_comment'])) {
			// On n'enregistre que les événements avec du texte
			tracert_history_admin(intval($_REQUEST['id_utilisateur']), 'EVENT', '', (!empty($_POST['form_event_comment'])?$_POST['form_event_comment']:''));
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "add_credit_gold" :
		// Ajoute un credit gold
		if (!empty($_POST['id_utilisateur']) && !empty($_POST['add_gold_ad']) && is_annonce_module_active()) {
			$output .= add_credit_gold_user ($_POST['id_utilisateur'], $_POST['add_gold_ad']);
			tracert_history_admin($_POST['id_utilisateur'], 'CREATE_ORDER', 'Ajout de credit gold');
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "suppr_credit_gold" :
		// Supprime le credit gold
		if (!empty($_GET['id_utilisateur']) && !empty($_GET['id_gold']) && is_annonce_module_active()) {
			$output .= suppr_credit_gold_user ($_GET['id_utilisateur'], $_GET['id_gold']);
			tracert_history_admin($_GET['id_utilisateur'], 'SUP_ORDER', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_GOLD_CREDIT_DELETED'] . ' ' . intval(vn($_GET['id_gold'])));
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "maj_abo_platinum":
		// Mise à jour de l'abonnement platinium si le module abonnement existe
		if (!empty($_POST['id_utilisateur']) && is_abonnement_module_active()) {
			$output .= maj_abonnement_admin($_POST);
			tracert_history_admin($_POST['id_utilisateur'], 'EDIT_ORDER', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MSG_PLATINUM_UPDATED_OK']);
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "maj_abo_diamond":
		// Mise à jour de l'abonnement diamond si le module abonnement existe
		if (!empty($_POST['id_utilisateur']) && is_abonnement_module_active()) {
			$output .= maj_abonnement_admin($_POST);
			tracert_history_admin($_POST['id_utilisateur'], 'EDIT_ORDER', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MSG_DIAMOND_UPDATED_OK']);
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "convert_abo":
		// Convertion d'un abonement en un autre si le module abonnement existe
		if (!empty($_POST['id_utilisateur']) && is_abonnement_module_active()) {
			if (!empty($_POST['convert_diamond_to_platinum'])) {
				$output .= userConvertSubscription($_POST['id_utilisateur'], 'diamond', 'platinum');
				tracert_history_admin($_POST['id_utilisateur'], 'EDIT_ORDER', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MSG_DIAMOND_CONVERTED_TO_PLATINUM_OK']);
			} elseif (!empty($_POST['convert_platinum_to_diamond'])) {
				$output .= userConvertSubscription($_POST['id_utilisateur'], 'platinum', 'diamond');
				tracert_history_admin($_POST['id_utilisateur'], 'EDIT_ORDER', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MSG_PLATINUM_CONVERTED_TO_DIAMOND_OK']);
			}
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;

	case "add_contact_planified":
		// Ajout d'une planification de contact
		if (!empty($_POST['form_edit_contact_user_id']) && is_commerciale_module_active()) {
			$output .= create_or_update_contact_planified($_POST);
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "update_contact_planified":
		// Mise à jour d'une planification de contact
		if (!empty($_POST['form_edit_contact_planified_id']) && is_commerciale_module_active()) {
			create_or_update_contact_planified($_POST);
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "suppr_contact_planified":
		// Supression d'une planification de contact
		if (!empty($_POST['form_delete_admins_contacts']) && is_commerciale_module_active()) {
			foreach($_POST['form_delete_admins_contacts'] as $form_edit_contact_planified_id) {
				$output .= delete_contact_planified($form_edit_contact_planified_id);
			}
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	case "search" :
		// recupération des informations client du critère de recherche sous forme de tableau, afin de les envoyés en paramètre dans la fonction tracert.
		$user_info = array();
		if (!empty($_GET['client_info'])) {
			$user_info [] = 'Prénom / Nom : ' . $_GET['client_info'];
		}
		if (!empty($_GET['email'])) {
			$user_info [] = 'Email : ' . $_GET['email'];
		}
		if (!empty($_GET['pays'])) {
			$user_info [] = 'Pays : ' . $_GET['pays'];
		}
		if (!empty($_GET['societe'])) {
			$user_info [] = 'Société : ' . $_GET['societe'];
		}
		if (!empty($_GET['origin'])) {
			$user_info [] = 'Origin : ' . $_GET['origin'];
		}
		if (!empty($_GET['tel'])) {
			$user_info [] = 'Tel : ' . $_GET['tel'];
		}
		if (!empty($_GET['fax'])) {
			$user_info [] = 'Fax : ' . $_GET['fax'];
		}
		if (!empty($_GET['date_insert_to'])) {
			$user_info [] = 'Date inscription : ' . nohtml_real_escape_string(date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_insert_to']))));
		}
		if (!empty($_GET['seg_who'])) {
			$user_info [] = 'Seg_who : ' . $_GET['seg_who'];
		}
		if (!empty($_GET['seg_buy'])) {
			$user_info [] = 'seg_buy : ' . $_GET['seg_buy'];
		}
		if (!empty($_GET['seg_want'])) {
			$user_info [] = 'seg_want : ' . $_GET['seg_want'];
		}
		if (!empty($_GET['seg_think'])) {
			$user_info [] = 'seg_think : ' . $_GET['seg_think'];
		}
		if (!empty($_GET['seg_followed'])) {
			$user_info [] = 'seg_followed : ' . $_GET['seg_followed'];
		}
		if (!empty($_GET['type'])) {
			$user_info [] = 'type : ' . $_GET['type'];
		}
		if (!empty($_GET['control_plus'])) {
			$user_info [] = 'control_plus : ' . $_GET['control_plus'];
		}
		if (!empty($_GET['fonction'])) {
			$user_info [] = 'fonction : ' . $_GET['fonction'];
		}
		if (!empty($_GET['site_on'])) {
			$user_info [] = 'site_on : ' . $_GET['site_on'];
		}
		if (!empty($_GET['id_cat'])) {
			$user_info [] = 'id_cat : ' . $_GET['id_cat'];
		}
		if (!empty($_GET['activity'])) {
			$user_info [] = 'activity : ' . $_GET['activity'];
		}
		tracert_history_admin(0, 'SEARCH_USER', implode(' | ', $user_info));
		$output .= afficher_liste_utilisateurs($priv, $cle, $_GET);
		break;
		
	case "phone":
		if (!empty($_POST)) {
			if (!empty($_POST['phone_emitted_submit'])) {
				tracert_history_admin($_POST['id_utilisateur'], 'PHONE_EMITTED', 'NOT_ENDED_CALL', $_POST['form_phone_comment']);
				$output .= affiche_formulaire_modif_utilisateur($_POST['id_utilisateur']);
			} elseif (!empty($_POST['phone_received_submit'])) {
				tracert_history_admin($_POST['id_utilisateur'], 'PHONE_RECEIVED', 'NOT_ENDED_CALL', $_POST['form_phone_comment']);
				$output .= affiche_formulaire_modif_utilisateur($_POST['id_utilisateur']);
			} elseif (!empty($_POST['turn_off_phone'])) {
				$q = query('UPDATE peel_admins_actions
						SET raison="",
							remarque="' . nohtml_real_escape_string($_POST['form_phone_comment']) . '",
							data="' . date('Y-m-d H:i:s', time()) . '"
						WHERE id_user="' . $_SESSION['session_utilisateur']['id_utilisateur'] . '" AND id_membre="' . $_POST['id_utilisateur'] . '" AND ((action = "PHONE_EMITTED") OR (action = "PHONE_RECEIVED")) AND data="NOT_ENDED_CALL"
						');
				$output .= affiche_formulaire_modif_utilisateur($_POST['id_utilisateur']);
			}
		}
		break;
		
	default :
		if (!empty($_GET['commercial_contact_id']) && is_commerciale_module_active()) {
			$output .= afficher_liste_utilisateurs($priv, $cle, null, 'date_insert', $_GET['commercial_contact_id']);
		} else {
			$output .= afficher_liste_utilisateurs($priv, $cle);
			if (is_chart_module_active() && empty($_GET['page'])) {
				if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
					include($GLOBALS['dirroot'] . '/modules/chart/flot.php');
				} else {
					include($GLOBALS['dirroot'] . '/modules/chart/open_flash_chart_object.php');
				}
				if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
					$output .=  '<div class="center">' . get_flot_chart('100%', 300, $GLOBALS['administrer_url'] . '/chart-data.php?type=users-count&date1=' . date('Y-m-d', time()-3600 * 24 * 90) . '&date2=' . date('Y-m-d', time()) . '&width=1000', 'line', $GLOBALS['wwwroot'] . '/modules/chart/', 'date_format_veryshort') . '</div>';
				} else {
					$output .=  '<div class="center">' . open_flash_chart_object_str('100%', 300, $GLOBALS['administrer_url'] . '/chart-data.php?type=users-count&date1=' . date('Y-m-d', time()-3600 * 24 * 90) . '&date2=' . date('Y-m-d', time()) . '&width=1000', true, $GLOBALS['wwwroot'] . '/modules/chart/') . '</div>';
				}
			}
		}
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vide pour ajouter un nouvel utilisateur
 * Initialise les valeurs par défauts pour un formulaire vide
 *
 * @return
 */
function afficher_formulaire_ajout_utilisateur()
{
	if(empty($frm)) {
		$frm = array();
		$frm['pseudo'] = "";
		$frm['commercial_contact_id'] = "";
		$frm['mot_passe'] = "";
		$frm['id_groupe'] = "";
		$frm['email'] = "";
		$frm['civilite'] = "";
		$frm['prenom'] = "";
		$frm['nom_famille'] = "";
		$frm['email'] = "";
		$frm['telephone'] = "";
		$frm['fax'] = "";
		$frm['portable'] = "";
		$frm['adresse'] = "";
		$frm['code_postal'] = "";
		$frm['ville'] = "";
		$frm['pays'] = "";
		$frm['societe'] = "";
		$frm['intracom_for_billing'] = "";
		$frm['siret'] = "";
		$frm['ape'] = "";
		$frm['remise_percent'] = "0";
		$frm['remise_valeur'] = "0";
		$frm['naissance'] = "";
		$frm['points'] = "0";
		$frm['on_vacances'] = "0";
		$frm['on_vacances_date'] = "";
		$frm['pays'] = vn($GLOBALS['site_parameters']['default_country_id']);
		$frm['priv'] = 'util';
		$frm['format'] = "";
		$frm['siret'] = "";
		$frm['ape'] = "";
		$frm['code_banque'] = "";
		$frm['code_guiche'] = "";
		$frm['numero_compte'] = "";
		$frm['cle_rib'] = "";
		$frm['domiciliation'] = "";
		$frm['iban'] = "";
		$frm['bic'] = "";
		$frm['url'] = "";
		$frm['description'] = "";
		$frm['date_insert'] = "";
		$frm['date_update'] = "";
		$frm['avoir'] = 0;
		$frm['newsletter'] = "1";
		$frm['commercial'] = "1";
		$frm['comments'] = "";
		$frm['seg_who'] = 'no_info';
		$frm['seg_buy'] = 'no_info';
		$frm['seg_want'] = 'no_info';
		$frm['seg_think'] = 'no_info';
		$frm['seg_followed'] = 'no_info';
		$frm['logo'] = '';
		$frm['on_client_module'] = 0;
		$frm['description_document'] = "";
		$frm['document'] = "";
	}
	$frm['id_utilisateur'] = "";
	$frm['nouveau_mode'] = "insere";
	$frm['titre_soumet'] = $GLOBALS['STR_ADMIN_UTILISATEURS_CREATE'];

	return afficher_formulaire_utilisateur($frm);
}

/**
 * Affiche un formulaire vide pour modifier un utilisateur
 *
 * @param integer $id_utilisateur
 * @return
 */
function affiche_formulaire_modif_utilisateur($id_utilisateur)
{
	$output = '';
	$frm = get_user_information($id_utilisateur);
	$qcomments = query("SELECT comments
		FROM peel_admins_comments
		WHERE id_user = '" . intval($id_utilisateur) . "'");
	$comments = fetch_assoc($qcomments);
	// Recupération de la date de la dernière connexion de l'utilisateur
	$qlast_date = query("SELECT date, user_ip
		FROM peel_utilisateur_connexions
		WHERE user_id = '" . intval($id_utilisateur) . "'
		ORDER BY id DESC");
	$last_date = fetch_assoc($qlast_date);
	if (!empty($last_date['date'])) {
		$frm['last_date'] = $last_date['date'];
	}
	if (!empty($last_date['user_ip'])) {
		$frm['user_ip'] = long2ip($last_date['user_ip']);
	}
	if (!empty($comments['comments'])) {
		$frm['comments'] = $comments['comments'];
	}
	if (!empty($frm)) {
		$frm['nouveau_mode'] = "maj";
		$frm['titre_soumet'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		$output .= afficher_formulaire_utilisateur($frm);
	} else {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_NOT_FOUND']))->fetch();
	}
	return $output;
}

/**
 * Affiche un formulaire de gestion d'utilisateur
 *
 * @param array $frm
 * @return
 */
function afficher_formulaire_utilisateur(&$frm)
{
	$output = '';
	$GLOBALS['multipage_avoid_redirect_if_page_over_limit'] = true;
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_utilisateur_form.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=' . (isset($_GET['start']) ? $_GET['start'] : 0));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vn($frm['id_utilisateur']))));
	$tpl->assign('mode', vb($frm['nouveau_mode']));
	$tpl->assign('id_utilisateur', vb($frm['id_utilisateur']));
	$tpl->assign('remise_valeur', vb($frm['remise_valeur']));
	$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
	$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
	$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);

	if (!empty($frm['date_insert'])) {
		$tpl->assign('date_insert', get_formatted_date($frm['date_insert']));
	}
	if (!empty($frm['last_date'])) {
		$tpl->assign('last_date', get_formatted_date($frm['last_date']));
	}
	$tpl->assign('user_ip', vb($frm['user_ip']));

	if (!empty($frm['date_update'])) {
		$tpl->assign('date_update', get_formatted_date($frm['date_update']));
	}
	$tpl->assign('is_id_utilisateur', !empty($frm['id_utilisateur']));
	$tpl->assign('email', (!a_priv('demo')?vb($frm['email']):'private [demo]'));
	$tpl->assign('pseudo', (!a_priv('demo')?vb($frm['pseudo']):'private [demo]'));

	$resPriv = query("SELECT *, name_".$_SESSION['session_langue']." AS name
		FROM peel_profil
		ORDER BY name");
	if (num_rows($resPriv)) {
		$user_priv_array = explode('+', vb($frm['priv']));
		// Sélection du privilège du l'utilisateur. Si le privilège de l'utilisateur n'est pas défini dans la table, le privilège 'util' est présélectionné
		$res_user_priv = query("SELECT name_".$_SESSION['session_langue']." AS name
			FROM peel_profil
			WHERE priv IN ('" . implode("','", real_escape_string($user_priv_array)) . "')");
		$user_priv = fetch_assoc($res_user_priv);
		while ($Priv = fetch_assoc($resPriv)) {
			$tpl_priv_options[] = array('value' => $Priv['priv'],
				'issel' => (!empty($user_priv['name']) ?  in_array($Priv['priv'], $user_priv_array) : $Priv['priv'] == 'util'),
				'name' => $Priv['name']
				);
		}
		$tpl->assign('priv_options', $tpl_priv_options);
	}

	$tpl->assign('commercial_contact_id', vb($frm['commercial_contact_id']));

	$tpl_util_options = array();
	$q = query('SELECT id_utilisateur, pseudo, email, etat, commercial_contact_id
		FROM peel_utilisateurs
		WHERE priv LIKE "admin%" AND pseudo!=""');
	while ($result = fetch_assoc($q)) {
		$tpl_util_options[] = array('value' => $result['id_utilisateur'],
			'issel' => vb($frm['commercial_contact_id']) == $result['id_utilisateur'],
			'name' => (!a_priv('demo')?(!empty($result['pseudo'])?$result['pseudo']:$result['email']):'private [demo]')
			);
	}
	$tpl->assign('util_options', $tpl_util_options);

	$tpl->assign('is_annonce_module_active', is_annonce_module_active());
	$tpl->assign('is_modif_mode', vb($_REQUEST['mode']) == "modif");
	$tpl->assign('mot_passe', vb($frm['mot_passe']));
	$tpl->assign('control_plus', vb($frm['control_plus']));
	$tpl->assign('note_administrateur', vb($frm['note_administrateur']));
	$tpl->assign('activity', vb($frm['activity']));

	$tpl->assign('is_groups_module_active', is_groups_module_active());
	if (is_groups_module_active()) {
		$resGroupe = query("SELECT *
		FROM peel_groupes
		ORDER BY nom");
		if (num_rows($resGroupe)) {
			$tpl_groupes_options = array();
			while ($Groupe = fetch_assoc($resGroupe)) {
				$tpl_groupes_options[] = array('value' => $Groupe['id'],
					'issel' => vb($frm['id_groupe']) == $Groupe['id'],
					'name' => $Groupe['nom'],
					'remise' => $Groupe['remise']
					);
			}
			$tpl->assign('groupes_options', $tpl_groupes_options);
		}
	}

	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
	$tpl->assign('telephone_calllink', (is_phone_cti_module_active() && !empty($frm['id_utilisateur']) ? getCallLink(vb($frm['id_utilisateur']), vb($frm['telephone']), vb($frm['nom_famille']), vb($frm['pays'])) : ''));
	$tpl->assign('portable_calllink', (is_phone_cti_module_active() && !empty($frm['id_utilisateur']) ? getCallLink(vb($frm['id_utilisateur']), vb($frm['portable']), vb($frm['nom_famille']), vb($frm['pays'])) : ''));
	$tpl->assign('country_select_options', get_country_select_options(null, vb($frm['pays']), 'id', true));

	$tpl->assign('specific_fields', get_specific_field_infos($frm));
	$tpl->assign('code_client', vb($frm['code_client']));
	$tpl->assign('societe', vb($frm['societe']));
	$tpl->assign('civilite', vb($frm['civilite']));
	$tpl->assign('prenom', vb($frm['prenom']));
	$tpl->assign('nom_famille', vb($frm['nom_famille']));
	$tpl->assign('telephone', vb($frm['telephone']));
	$tpl->assign('fax', vb($frm['fax']));
	$tpl->assign('portable', vb($frm['portable']));
	$tpl->assign('adresse', vb($frm['adresse']));
	$tpl->assign('code_postal', vb($frm['code_postal']));
	$tpl->assign('ville', vb($frm['ville']));
	$tpl->assign('naissance', get_formatted_date(vb($frm['naissance'])));
	$tpl->assign('remise_percent', vb($frm['remise_percent']));
	$tpl->assign('avoir', vb($frm['avoir']));
	$tpl->assign('points', vb($frm['points']));
	$tpl->assign('is_module_vacances_active', is_module_vacances_active());
	if (is_module_vacances_active()) {
		$tpl->assign('vacances_type', get_vacances_type());
	} else {
		$tpl->assign('vacances_type', '');
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
			'error_text' => '',
			'STR_CHOOSE' => $GLOBALS['STR_CHOOSE']
		));

	$tpl->assign('STR_LANGUAGE_FOR_AUTOMATIC_EMAILS', $GLOBALS['STR_LANGUAGE_FOR_AUTOMATIC_EMAILS']);

	$tpl_langues = array();
	$resLng = query("SELECT *, nom_" . $_SESSION['session_langue'] . " AS nom_lang
		FROM peel_langues
		WHERE etat = '1'" . (!empty($_GET['langue']) ? " OR lang='" . word_real_escape_string($_GET['langue']) . "'" : '') . "
		GROUP BY lang
		ORDER BY position");
	while ($lng = fetch_assoc($resLng)) {
		$tpl_langues[] = array('value' => vb($lng['lang']),
			'issel' => ($lng['lang'] == vb($frm['lang'])),
			'name' => vb($lng['lang'])
			);
		$i++;
	}
	$tpl->assign('langues', $tpl_langues);

	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	if (!empty($frm['logo'])) {
		$tpl->assign('logo_src', $GLOBALS['repertoire_upload'] . '/' . $frm['logo']);
		$tpl->assign('logo_del_href', get_current_url(false) . '?mode=supprlogo&id_utilisateur=' . vn($frm['id_utilisateur']));
	}

	$tpl->assign('is_clients_module_active', is_clients_module_active());
	$tpl->assign('issel_on_client_module', !isset($frm['on_client_module']) || !empty($frm['on_client_module']));
	$tpl->assign('is_photodesk_module_active', is_photodesk_module_active());
	$tpl->assign('issel_on_photodesk_module', !isset($frm['on_photodesk']) || !empty($frm['on_photodesk']));
	$tpl->assign('gift_check_link', is_module_gift_checks_active() && !empty($frm['id_utilisateur']));

	$tpl->assign('issel_newsletter', !isset($frm['newsletter']) || !empty($frm['newsletter']));
	$tpl->assign('issel_commercial', !isset($frm['commercial']) || $frm['commercial']);

	$tpl->assign('is_module_gift_checks_active', is_module_gift_checks_active());
	$tpl->assign('mail_src', $GLOBALS['wwwroot_in_admin'] . '/images/mail.gif');
	if (is_module_gift_checks_active() && !empty($frm['id_utilisateur'])) {
		$tpl->assign('gift_checks_href', get_current_url(false) . '?mode=cheque&id_utilisateur=' . $frm['id_utilisateur']);
		$tpl->assign('gift_checks_prix', fprix($GLOBALS['site_parameters']['avoir'], true, $GLOBALS['site_parameters']['code'], false));
	}
	if (is_telechargement_module_active()) {
		include($GLOBALS['dirroot'] . "/modules/telechargement/administrer/fonctions.php");
		include($GLOBALS['dirroot'] . "/modules/telechargement/lang/" . $_SESSION['session_langue'] . ".php");
		$tpl->assign('download_files', affiche_liste_telechargement($frm['id_utilisateur']));
	}
	$tpl->assign('is_annonce_module_active', is_annonce_module_active());
	$tpl->assign('is_destockplus_module_active', is_destockplus_module_active());
	$tpl->assign('is_algomtl_module_active', is_algomtl_module_active());
	$tpl->assign('fonction', vb($frm['fonction']));
	$tpl->assign('type', vb($frm['type']));
	$tpl->assign('client_note', intval(getClientNote($frm)));
	$tpl->assign('seg_who', formSelect('seg_who', tab_who(), vb($frm['seg_who'])));
	$tpl->assign('seg_buy', formSelect('seg_buy', tab_buy(), vb($frm['seg_buy'])));
	$tpl->assign('seg_want', formSelect('seg_want', tab_want(), vb($frm['seg_want'])));
	$tpl->assign('seg_think', formSelect('seg_think', tab_think(), vb($frm['seg_think'])));
	$tpl->assign('seg_followed', formSelect('seg_followed', tab_followed(), vb($frm['seg_followed'])));

	$tpl->assign('is_vitrine_module_active', is_vitrine_module_active());
	if (is_vitrine_module_active() && !empty($frm['id_utilisateur'])) {
		$tpl->assign('vitrine_admin', affiche_vitrine_admin($frm['id_utilisateur']));
	}

	$tpl->assign('is_abonnement_module_active', is_abonnement_module_active());
	if (is_abonnement_module_active() && !empty($frm['id_utilisateur'])) {
		$tpl->assign('abonnement_admin', affiche_abonnement_admin($frm['id_utilisateur'], true));
		$tpl->assign('STR_MODULE_ABONNEMENT_ADMIN_MANAGE_SUBSCRIPTIONS', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MANAGE_SUBSCRIPTIONS']);
	}

	if (is_annonce_module_active() && !empty($frm['pseudo'])) {
		$recherche['login'] = $frm['pseudo'];
		$tpl->assign('add_credit_gold_user', affiche_add_credit_gold_user($frm['id_utilisateur'], true));
		$tpl->assign('liste_annonces_admin', affiche_liste_annonces_admin($recherche, false, $frm['id_utilisateur']));
	}

	$tpl->assign('is_commerciale_module_active', is_commerciale_module_active());
	if (is_commerciale_module_active() && !empty($frm['id_utilisateur'])) {
		$tpl->assign('form_contact_user', affiche_form_contact_user($frm['id_utilisateur'], true));
	}

	if (vb($_REQUEST['mode']) != "ajout") { // si c'est l'édition
		$tpl->assign('phone_event', affiche_phone_event($frm['id_utilisateur']));
	}

	$tpl->assign('is_webmail_module_active', is_webmail_module_active());
	if (is_webmail_module_active() && !empty($frm['id_utilisateur'])) {
		$tpl->assign('list_user_mail', list_user_mail($frm['id_utilisateur'], true));
	}
	if (!empty($frm['user_ip'])) {
		// Insertion du module de géoip permettant de définir en fonction de la dernière ip le lieu où s'est connecté la personne dernièrement
		if (file_exists($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php')) {
			include($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php');
			$geoIP = new geoIP();
			$country_detected = $geoIP->geoIPCountryIDByAddr($frm['user_ip']);
			$geoIP->geoIPClose();
			if (!empty($country_detected)) {
				$query = query("SELECT pays_" . $_SESSION['session_langue'] . "
					FROM peel_pays
					WHERE id='" . intval(vn($country_detected)) . "'");
				$result = fetch_assoc($query);
				$country_name = vb($result['pays_' . $_SESSION['session_langue']]);
			}
		}
	}
	$tpl->assign('country_name', vb($country_name));
	$tpl->assign('on_vacances_date', vb($frm['on_vacances_date']));

	$tpl->assign('siret', vb($frm['siret']));
	$tpl->assign('intracom_for_billing', vb($frm['intracom_for_billing']));
	$tpl->assign('ape', vb($frm['ape']));
	$tpl->assign('url', vb($frm['url']));
	$tpl->assign('description', vb($frm['description']));
	$tpl->assign('code_banque', vb($frm['code_banque']));
	$tpl->assign('code_guichet', vb($frm['code_guichet']));
	$tpl->assign('numero_compte', vb($frm['numero_compte']));
	$tpl->assign('cle_rib', vb($frm['cle_rib']));
	$tpl->assign('domiciliation', vb($frm['domiciliation']));
	$tpl->assign('bic', vb($frm['bic']));
	$tpl->assign('iban', vb($frm['iban']));
	$tpl->assign('origin_other', vb($frm['origin_other']));
	$tpl->assign('project_budget_ht', vb($frm['project_budget_ht']));
	$tpl->assign('project_chances_estimated', vb($frm['project_chances_estimated']));
	$tpl->assign('comments', vb($frm['comments']));
	$tpl->assign('project_product_proposed', vb($frm['project_product_proposed']));
	$tpl->assign('project_date_forecasted', get_formatted_date(vb($frm['project_date_forecasted'])));
	$tpl->assign('etat', vb($frm['etat']));
	$tpl->assign('on_vacances', vb($frm['on_vacances']));
	$tpl->assign('titre_soumet', vb($frm['titre_soumet']));
	if (is_annonce_module_active()) {
		if (!empty($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) && $GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories'] == 'checkbox') {
			$tpl->assign('favorite_category', get_announcement_select_options(null, vb($frm['id_categories']), 'id', false, false, 'checkbox', 'id_categories'));	
		} else {
			$tpl->assign('favorite_category_1', get_announcement_select_options(null, vb($frm['id_cat_1']), 'id'));
			$tpl->assign('favorite_category_2', get_announcement_select_options(null, vb($frm['id_cat_2']), 'id'));
			$tpl->assign('favorite_category_3', get_announcement_select_options(null, vb($frm['id_cat_3']), 'id'));
		}
	}
	$tpl->assign('STR_MLLE', $GLOBALS['STR_MLLE']);
	$tpl->assign('STR_MME', $GLOBALS['STR_MME']);
	$tpl->assign('STR_M', $GLOBALS['STR_M']);
	$tpl->assign('STR_NAISSANCE', $GLOBALS['STR_NAISSANCE']);
	$tpl->assign('STR_LEADER', $GLOBALS['STR_LEADER']);
	$tpl->assign('STR_MANAGER', $GLOBALS['STR_MANAGER']);
	$tpl->assign('STR_EMPLOYEE', $GLOBALS['STR_EMPLOYEE']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_BUYERS', $GLOBALS['STR_BUYERS']);
	$tpl->assign('STR_IMPORTERS_EXPORTERS', $GLOBALS['STR_IMPORTERS_EXPORTERS']);
	$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
	$tpl->assign('STR_PURCHASING_MANAGER', $GLOBALS['STR_PURCHASING_MANAGER']);
	$tpl->assign('STR_WORD_SELLERS', $GLOBALS['STR_WORD_SELLERS']);
	$tpl->assign('STR_WHOLESALER', $GLOBALS['STR_WHOLESALER']);
	$tpl->assign('STR_HALF_WHOLESALER', $GLOBALS['STR_HALF_WHOLESALER']);
	$tpl->assign('STR_RETAILERS', $GLOBALS['STR_RETAILERS']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_EDIT_TITLE', $GLOBALS['STR_ADMIN_UTILISATEURS_EDIT_TITLE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_EMAIL', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_EMAIL']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE_ORDER_TO_THIS_USER', $GLOBALS['STR_ADMIN_UTILISATEURS_CREATE_ORDER_TO_THIS_USER']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE_GIFT_CHECK', sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_CREATE_GIFT_CHECK'], fprix($GLOBALS['site_parameters']['avoir'], true, $GLOBALS['site_parameters']['code'], false)));
	$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE_GIFT_CHECK_CONFIRM', $GLOBALS['STR_ADMIN_UTILISATEURS_CREATE_GIFT_CHECK_CONFIRM']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_SOCIETE_COM', $GLOBALS['STR_ADMIN_UTILISATEURS_SOCIETE_COM']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_INFOGREFFE', $GLOBALS['STR_ADMIN_UTILISATEURS_INFOGREFFE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_EDIT_TITLE', $GLOBALS['STR_ADMIN_UTILISATEURS_EDIT_TITLE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_UPDATE_EXPLAIN', $GLOBALS['STR_ADMIN_UTILISATEURS_UPDATE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_REGISTRATION_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_REGISTRATION_DATE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_CONNECTION', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_CONNECTION']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_IP', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_IP']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_UPDATE', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_UPDATE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_ADMIN_NOTE', $GLOBALS['STR_ADMIN_UTILISATEURS_ADMIN_NOTE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_MODERATION_MORE_STRICT', $GLOBALS['STR_ADMIN_UTILISATEURS_MODERATION_MORE_STRICT']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
	$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
	$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
	$tpl->assign('STR_ADMIN_PRIVILEGE', $GLOBALS['STR_ADMIN_PRIVILEGE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_ACCOUNT_MANAGER', $GLOBALS['STR_ADMIN_UTILISATEURS_ACCOUNT_MANAGER']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_NO_ACCOUNT_MANAGER', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_ACCOUNT_MANAGER']);
	$tpl->assign('STR_ADMIN_GROUP', $GLOBALS['STR_ADMIN_GROUP']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_CLIENT_CODE', $GLOBALS['STR_ADMIN_UTILISATEURS_CLIENT_CODE']);
	$tpl->assign('STR_COMPANY', $GLOBALS['STR_COMPANY']);
	$tpl->assign('STR_ACTIVITY', $GLOBALS['STR_ACTIVITY']);
	$tpl->assign('STR_PUNCTUAL', $GLOBALS['STR_PUNCTUAL']);
	$tpl->assign('STR_RECURRENT', $GLOBALS['STR_RECURRENT']);
	$tpl->assign('STR_GENDER', $GLOBALS['STR_GENDER']);
	$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
	$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
	$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
	$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
	$tpl->assign('STR_PORTABLE', $GLOBALS['STR_PORTABLE']);
	$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
	$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
	$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
	$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
	$tpl->assign('STR_NAISSANCE', $GLOBALS['STR_NAISSANCE']);
	$tpl->assign('STR_ADMIN_CODES_PROMOS_PERCENT', $GLOBALS['STR_ADMIN_CODES_PROMOS_PERCENT']);
	$tpl->assign('STR_AVOIR', $GLOBALS['STR_AVOIR']);
	$tpl->assign('STR_GIFT_POINTS', $GLOBALS['STR_GIFT_POINTS']);
	$tpl->assign('STR_GIFT_POINTS', $GLOBALS['STR_GIFT_POINTS']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_ON_HOLIDAY_SUPPLIER', $GLOBALS['STR_ADMIN_UTILISATEURS_ON_HOLIDAY_SUPPLIER']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_SUPPLIER_RETURN_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_SUPPLIER_RETURN_DATE']);
	$tpl->assign('STR_SIREN', $GLOBALS['STR_SIREN']);
	$tpl->assign('STR_VAT_INTRACOM', $GLOBALS['STR_VAT_INTRACOM']);
	$tpl->assign('STR_MODULE_PREMIUM_APE', $GLOBALS['STR_MODULE_PREMIUM_APE']);
	$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_WEBSITE_DESCRIPTION', $GLOBALS['STR_ADMIN_UTILISATEURS_WEBSITE_DESCRIPTION']);
	$tpl->assign('STR_BANK_ACCOUNT_CODE', $GLOBALS['STR_BANK_ACCOUNT_CODE']);
	$tpl->assign('STR_BANK_ACCOUNT_COUNTER', $GLOBALS['STR_BANK_ACCOUNT_COUNTER']);
	$tpl->assign('STR_BANK_ACCOUNT_NUMBER', $GLOBALS['STR_BANK_ACCOUNT_NUMBER']);
	$tpl->assign('STR_BANK_ACCOUNT_RIB', $GLOBALS['STR_BANK_ACCOUNT_RIB']);
	$tpl->assign('STR_BANK_ACCOUNT_DOMICILIATION', $GLOBALS['STR_BANK_ACCOUNT_DOMICILIATION']);
	$tpl->assign('STR_SWIFT', $GLOBALS['STR_SWIFT']);
	$tpl->assign('STR_IBAN', $GLOBALS['STR_IBAN']);
	$tpl->assign('STR_ORIGIN', $GLOBALS['STR_ORIGIN']);
	$tpl->assign('STR_ADMIN_GIVE_DETAIL', $GLOBALS['STR_ADMIN_GIVE_DETAIL']);
	$tpl->assign('STR_ADMIN_CHOOSE_FAVORITE_CATEGORIES', $GLOBALS['STR_ADMIN_CHOOSE_FAVORITE_CATEGORIES']);
	$tpl->assign('STR_FIRST_CHOICE', $GLOBALS['STR_FIRST_CHOICE']);
	$tpl->assign('STR_SECOND_CHOICE', $GLOBALS['STR_SECOND_CHOICE']);
	$tpl->assign('STR_THIRD_CHOICE', $GLOBALS['STR_THIRD_CHOICE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_CLIENT_BUDGET', $GLOBALS['STR_ADMIN_UTILISATEURS_CLIENT_BUDGET']);
	$tpl->assign('STR_HT', $GLOBALS['STR_HT']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_CLIENT_PROJECT_CHANCES', $GLOBALS['STR_ADMIN_UTILISATEURS_CLIENT_PROJECT_CHANCES']);
	$tpl->assign('STR_COMMENTS', $GLOBALS['STR_COMMENTS']);
	$tpl->assign('STR_ADMIN_DELETE_LOGO', $GLOBALS['STR_ADMIN_DELETE_LOGO']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_PROJECT_PRODUCT_PROPOSED', $GLOBALS['STR_ADMIN_UTILISATEURS_PROJECT_PRODUCT_PROPOSED']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_PROJECT_DATE_FORECASTED', $GLOBALS['STR_ADMIN_UTILISATEURS_PROJECT_DATE_FORECASTED']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_PROJECT_DESCRIPTION_DISPLAY', $GLOBALS['STR_ADMIN_UTILISATEURS_PROJECT_DESCRIPTION_DISPLAY']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_DISPLAY_IMAGE_IN_PHOTODESK', $GLOBALS['STR_ADMIN_UTILISATEURS_DISPLAY_IMAGE_IN_PHOTODESK']);
	$tpl->assign('STR_NEWSLETTER', $GLOBALS['STR_NEWSLETTER']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_NEWSLETTER_CHECKBOX', $GLOBALS['STR_ADMIN_UTILISATEURS_NEWSLETTER_CHECKBOX']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_COMMERCIAL', $GLOBALS['STR_ADMIN_UTILISATEURS_COMMERCIAL']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_COMMERCIAL_CHECKBOX', $GLOBALS['STR_ADMIN_UTILISATEURS_COMMERCIAL_CHECKBOX']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD']);
	$tpl->assign('STR_ADMIN_COMMANDER_CLIENT_INFORMATION', $GLOBALS['STR_ADMIN_COMMANDER_CLIENT_INFORMATION']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_CLIENT_TYPE', $GLOBALS['STR_ADMIN_UTILISATEURS_CLIENT_TYPE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_WHO', $GLOBALS['STR_ADMIN_UTILISATEURS_WHO']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_BUY', $GLOBALS['STR_ADMIN_UTILISATEURS_BUY']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_WANTS', $GLOBALS['STR_ADMIN_UTILISATEURS_WANTS']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_THINKS', $GLOBALS['STR_ADMIN_UTILISATEURS_THINKS']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_BY', $GLOBALS['STR_ADMIN_UTILISATEURS_FOLLOWED_BY']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_JOB', $GLOBALS['STR_ADMIN_UTILISATEURS_JOB']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_SEGMENTATION_TOTAL', $GLOBALS['STR_ADMIN_UTILISATEURS_SEGMENTATION_TOTAL']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_ADD_CONTACT_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_ADD_CONTACT_DATE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_MANAGE_CALLS', $GLOBALS['STR_ADMIN_UTILISATEURS_MANAGE_CALLS']);
	$tpl->assign('STR_LOGO', $GLOBALS['STR_LOGO']);
	$tpl->assign('STR_NONE', $GLOBALS['STR_NONE']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	$output = $tpl->fetch();

	if (!empty($frm['id_utilisateur'])) {
		$columns = 8;
		if (is_parrainage_module_active()) {
			$columns++;
		}

		$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_utilisateur_form_isutil.tpl');
		
		$tpl2->assign('action', get_current_url(false) . '?id_utilisateur=' . intval($_REQUEST['id_utilisateur']) . '&mode=modif');
		$tpl2->assign('pseudo', (!empty($frm['pseudo'])?$frm['pseudo']:vb($frm['email'])));
		$tpl2->assign('event_comment', (!empty($_POST['event_comment']) ? $_POST['event_comment'] : ''));
		$tpl2->assign('affiche_recherche_connexion_user',  affiche_recherche_connexion_user(array('user_id' => $frm['id_utilisateur']), false));
		if (is_annonce_module_active()) {
			$tpl2->assign('affiche_liste_abus', affiche_liste_abus(array('annonceur'=>$frm['pseudo']), true, false));
		}
		$tpl2->assign('actions_moderations_user', affiche_actions_moderations_user($frm['id_utilisateur']));
		$tpl2->assign('columns', $columns);
		$tpl2->assign('mini_liste_commande_src', $GLOBALS['administrer_url'] . '/images/mini_liste_commande.gif');
		$tpl2->assign('is_parrainage_module_active', is_parrainage_module_active());
		$tpl2->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
		$tpl2->assign('printer_src', $GLOBALS['wwwroot_in_admin'] . '/images/t_printer.gif');
		$tpl2->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
		$tpl2->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);

		$query = query("SELECT c.*, GROUP_CONCAT(ca.nom_produit SEPARATOR '<br />') AS ordered_products
			FROM peel_commandes c
			LEFT JOIN peel_commandes_articles ca ON ca.commande_id=c.id
			WHERE c.id_utilisateur = '" . intval($frm['id_utilisateur']) . "'
			GROUP BY c.id
			ORDER BY c.id DESC");
		if (num_rows($query) > 0) {
			$tpl_results = array();

			$total_ttc = $total_ht = 0;
			$i = 0;
			while ($order_infos = fetch_object($query)) {
				$total_ttc += $order_infos->montant;
				$total_ht += $order_infos->montant_ht;
				$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
					'modif_href' => $GLOBALS['administrer_url'] . '/commander.php?mode=modif&commandeid=' . $order_infos->id,
					'print_href' => $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?mode=details&code_facture=' . $order_infos->code_facture,
					'drop_href' => $GLOBALS['administrer_url'] . '/commander.php?mode=suppr&id=' . $order_infos->id,
					'id' => $order_infos->id,
					'date' => get_formatted_date($order_infos->o_timestamp),
					'prix' => fprix($order_infos->montant, true, $order_infos->devise, true, $order_infos->currency_rate),
					'recuperer_avoir_commande' => is_parrainage_module_active() ? fprix(recuperer_avoir_commande($order_infos->id), true, $order_infos->devise, true, $order_infos->currency_rate) : '',
					'payment_name' => get_payment_name($order_infos->paiement),
					'payment_status_name' => get_payment_status_name($order_infos->id_statut_paiement),
					'delivery_status_name' => get_delivery_status_name($order_infos->id_statut_livraison),
					'ordered_products' => String::str_shorten($order_infos->ordered_products, 200)
					);
				$i++;
			}
			$tpl2->assign('results', $tpl_results);
			$tpl2->assign('action2', $_SERVER['REQUEST_URI']);
			$tpl2->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['id_utilisateur']));
			$tpl2->assign('user_id', intval(vn($frm['id_utilisateur'])));
		}
		$tpl2->assign('STR_ADMIN_UTILISATEURS_ADD_EVENT_REGARDING', $GLOBALS['STR_ADMIN_UTILISATEURS_ADD_EVENT_REGARDING']);
		$tpl2->assign('STR_ADMIN_UTILISATEURS_EVENT_DESCRIPTION', $GLOBALS['STR_ADMIN_UTILISATEURS_EVENT_DESCRIPTION']);
		$tpl2->assign('STR_ADMIN_UTILISATEURS_SAVE_EVENT', $GLOBALS['STR_ADMIN_UTILISATEURS_SAVE_EVENT']);
		$tpl2->assign('STR_ADMIN_UTILISATEURS_ACTIONS_ON_THIS_ACCOUNT', $GLOBALS['STR_ADMIN_UTILISATEURS_ACTIONS_ON_THIS_ACCOUNT']);
		$tpl2->assign('STR_ADMIN_UTILISATEURS_ORDERS_LIST', $GLOBALS['STR_ADMIN_UTILISATEURS_ORDERS_LIST']);
		$tpl2->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
		$tpl2->assign('STR_ORDER_NAME', $GLOBALS['STR_ORDER_NAME']);
		$tpl2->assign('STR_DATE', $GLOBALS['STR_DATE']);
		$tpl2->assign('STR_TOTAL', $GLOBALS['STR_TOTAL']);
		$tpl2->assign('STR_TTC', $GLOBALS['STR_TTC']);
		$tpl2->assign('STR_AVOIR', $GLOBALS['STR_AVOIR']);
		$tpl2->assign('STR_ADMIN_UTILISATEURS_PRODUCTS_ORDERED', $GLOBALS['STR_ADMIN_UTILISATEURS_PRODUCTS_ORDERED']);
		$tpl2->assign('STR_PAYMENT', $GLOBALS['STR_PAYMENT']);
		$tpl2->assign('STR_DELIVERY', $GLOBALS['STR_DELIVERY']);
		$tpl2->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
		$tpl2->assign('STR_PRINT', $GLOBALS['STR_PRINT']);
		$tpl2->assign('STR_ADMIN_UTILISATEURS_PRINT_ALL_BILLS', $GLOBALS['STR_ADMIN_UTILISATEURS_PRINT_ALL_BILLS']);
		$tpl2->assign('STR_ADMIN_UTILISATEURS_NO_ORDER_FOUND', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_ORDER_FOUND']);
		$output .= $tpl2->fetch();
	}
	return $output;
}

/**
 * Affiche la liste des utilisateurs en fonction des critères de recherche
 * Un certain nombre de champs de recherche permettent de cherche sur plusieurs colonnes, ce qui permet de simplifier l'interface
 *
 * @param mixed $priv
 * @param mixed $cle
 * @param array $frm
 * @param string $order
 * @param boolean $allow_message_no_result
 * @return
 */
function afficher_liste_utilisateurs($priv, $cle, $frm = null, $order = 'date_insert', $allow_message_no_result = false)
{
	$output = '';
	$sql_inner_array = array();
	$sql_having_array = array();
	$sql_columns_array = array('u.*');
	$sql_where_array = array('1');
	$sql_group_by = '';
	$sql_having = '';
	$sql = "";
	/* Recherche de base */
	if (!empty($frm['client_info'])) {
		$sql_where_array[] = '(u.nom_famille LIKE "%' . nohtml_real_escape_string(trim($frm['client_info'])) . '%" OR u.prenom LIKE "%' . nohtml_real_escape_string(trim($frm['client_info'])) . '%")';
	}
	if (!empty($frm['email']) && is_numeric(trim($frm['email']))) {
		// Recherche sur une id - si par exemple on cherche 22, on ne veut pas récupérer les emails contenant 22 => on ne cherche que sur l'id
		$sql_where_array[] = 'u.id_utilisateur = "' . intval($frm['email']) . '"';
	} elseif (!empty($frm['email'])) {
		$sql_where_array[] = '(u.email LIKE "%' . nohtml_real_escape_string(trim($frm['email'])) . '%" OR u.pseudo LIKE "%' . nohtml_real_escape_string(trim($frm['email'])) . '%")';
	}
	if (!empty($frm['societe'])) {
		$sql_where_array[] = '(u.societe LIKE "%' . nohtml_real_escape_string(trim($frm['societe'])) . '%" OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(u.siret,")",""),"(",""), ".",""), "-",""), " ","") LIKE "%' . nohtml_real_escape_string(str_replace(array('(', ')', '.', '-', ' '), '', trim($frm['societe']))) . '%" OR u.url LIKE "%' . nohtml_real_escape_string(trim($frm['societe'])) . '%")';
	}
	if (!empty($frm['ville_cp'])) {
		$sql_where_array[] = '(u.ville LIKE "%' . nohtml_real_escape_string(trim($frm['ville_cp'])) . '%" OR u.code_postal LIKE "' . nohtml_real_escape_string(trim($frm['ville_cp'])) . '%")';
	}
	if (a_priv('demo')) {
		$sql_where_array[] = "u.priv NOT LIKE ('" . nohtml_real_escape_string('admin') . "%')";
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_NO_ADMIN_RIGHT_TO_LIST']))->fetch();
	}	
	$basic_search_where_count = count($sql_where_array);
	/* Recherche avancée */
	if (!empty($frm['type'])) {
		$sql_where_array[] = 'u.type = "' . nohtml_real_escape_string($frm['type']) . '"';
	}
	if (isset($frm['control_plus']) && $frm['control_plus'] != '') {
		$sql_where_array[] = 'u.control_plus = "' . intval($frm['control_plus']) . '"';
	}
	if (!empty($frm['fonction'])) {
		$sql_where_array[] = 'u.fonction = "' . nohtml_real_escape_string($frm['fonction']) . '"';
	}
	if (isset($frm['site_on']) && $frm['site_on'] != '') {
		$sql_where_array[] = 'u.url ' . (!empty($frm['site_on'])?' <> ""':' = ""');
	}
	if (!empty($frm['id_cat'])) {
			$sql_where_array[] = '(u.id_cat_1 = "' . nohtml_real_escape_string($frm['id_cat']) . '" OR u.id_cat_2 = "' . nohtml_real_escape_string($frm['id_cat']) . '" OR u.id_cat_3 = "' . nohtml_real_escape_string($frm['id_cat']) . '")';
	}
	if (!empty($frm['id_categories'])) {
		$this_categories_where_array = array();
		foreach($frm['id_categories'] as $this_categories) {
			$this_categories_where_array[] = 'CONCAT(",",u.id_categories,",") LIKE "%,'.$this_categories.',%"';
		}
		$sql_where_array[] = '('.implode(' OR ', $this_categories_where_array).')';
	}

	if (!empty($frm['tel'])) {
		// On recherche sans les caractères séparateurs
		$frm['tel'] = str_replace(array('(', ')', '.', '-', ' '), '', trim($frm['tel']));
		$sql_where_array[] = '(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(u.telephone,")",""),"(",""), ".",""), "-",""), " ","") LIKE "%' . nohtml_real_escape_string($frm['tel']) . '%" OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(u.portable,")",""),"(",""), ".",""), "-",""), " ","") LIKE "%' . nohtml_real_escape_string($frm['tel']) . '%" OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(u.fax,")",""),"(",""), ".",""), "-",""), " ","") LIKE "%' . nohtml_real_escape_string($frm['tel']) . '%")';
	}
	if (!empty($frm['origin'])) {
		$sql_where_array[] = 'u.origin="' . intval($frm['origin']) . '"';
	}
	if (!empty($frm['commercial'])) {
		$sql_where_array[] = 'u.commercial_contact_id = "' . intval($frm['commercial']) . '"';
	}
	if (!empty($frm['pays'])) {
		$sql_where_array[] = 'u.pays="' . nohtml_real_escape_string($frm['pays']) . '"';
	}
	if (!empty($frm['continent']) && is_array($frm['continent'])) {
		if (in_array(5, $frm['continent'])) {
			// On considère l'antarctique comme faisant partie de l'océanie
			$frm['continent'][] = 6;
		}
		$sql_where_array[] = 'pays.continent_id IN ("' . implode('","', nohtml_real_escape_string($frm['continent'])) . '")';
		$sql_inner_array['peel_pays'] = 'INNER JOIN peel_pays pays ON pays.id=u.pays';
	}
	if (!empty($frm['seg_who']) && $frm['seg_who'] != '0') {
		$sql_where_array[] = 'u.seg_who = "' . nohtml_real_escape_string($frm['seg_who']) . '"';
	}
	if (!empty($frm['seg_buy'])) {
		$sql_where_array[] = 'u.seg_buy = "' . nohtml_real_escape_string($frm['seg_buy']) . '"';
	}
	if (!empty($frm['seg_want'])) {
		$sql_where_array[] = 'u.seg_want = "' . nohtml_real_escape_string($frm['seg_want']) . '"';
	}
	if (!empty($frm['seg_think'])) {
		$sql_where_array[] = 'u.seg_think = "' . nohtml_real_escape_string($frm['seg_think']) . '"';
	}
	if (!empty($frm['seg_followed'])) {
		$sql_where_array[] = 'u.seg_followed = "' . nohtml_real_escape_string($frm['seg_followed']) . '"';
	}
	if (!empty($frm['raison'])) {
		$sql_inner_array['peel_admins_contacts_planified'] = 'INNER JOIN peel_admins_contacts_planified pacp ON pacp.user_id=u.id_utilisateur';
		$sql_where_array[] = 'pacp.reason="' . nohtml_real_escape_string(vb($frm['raison'])) . '"';
	}
	if (isset($frm['etat']) && $frm['etat'] != '') {
		$sql_where_array[] = 'u.etat="' . nohtml_real_escape_string($frm['etat']) . '"';
	}
	if (isset($frm['newsletter']) && $frm['newsletter'] != '') {
		$sql_where_array[] = 'u.newsletter="' . nohtml_real_escape_string($frm['newsletter']) . '"';
	}
	if (isset($frm['offre_commercial']) && $frm['offre_commercial'] != '') {
		$sql_where_array[] = 'u.commercial="' . nohtml_real_escape_string($frm['offre_commercial']) . '"';
	}
	if (!empty($frm['priv'])) {
		$sql_where_array[] = 'u.priv="' . nohtml_real_escape_string(vb($frm['priv'])) . '"';
	}
	if (!empty($frm['valid'])) {
		$sql_where_array[] = 'u.valid ="' . nohtml_real_escape_string($frm['valid']) . '"';
	}
	if (!empty($frm['activity'])) {
		$sql_where_array[] = 'u.activity ="' . nohtml_real_escape_string($frm['activity']) . '"';
	}
	if (!empty($frm['user_lang'])) {
		$sql_where_array[] = 'u.lang ="' . nohtml_real_escape_string($frm['user_lang']) . '"';
	}
	if (!empty($frm['list_produit'])) {
		// On récupère d'abord l'id produit pour éviter de surcharger la requête SQL générale par des jointures diverses
		$product_id = get_product_id_by_name($frm['list_produit'], true);
		if(!empty($product_id)) {
			$sql_inner_array['peel_commandes_articles'] = 'INNER JOIN peel_commandes_articles pca ON pca.commande_id= c.id';
			$sql_where_array[] = 'pca.produit_id="' . nohtml_real_escape_string($product_id) . '"';
			$sql_columns_array[] = 'SUM(pca.quantite) AS this_quantite_sum';
			if (!empty($frm['nombre_produit']) && $frm['nombre_produit'] != "no_info") {
				if ($frm['nombre_produit'] == -1) {
					$sql_having_array[] = 'this_quantite_sum=0';
				} else {
					$sql_having_array[] = 'this_quantite_sum>="' . intval($frm['nombre_produit']) . '"';
				}
			} else {
				// Par défaut : produit acheté une fois au moins
				$sql_having_array[] = 'this_quantite_sum>0';
			}
		} else {
			$sql_where_array[] = '0';
		}
	}

	if (!empty($frm['abonne'])) {
		if (in_array($frm['abonne'], array('any', 'no', 'never', 'earlier'))) {
			if ($frm['abonne'] == 'any') { // Tous abonnements confondus
				$sql_where_array[] = '(u.platinum_status="YES" OR u.diamond_status="YES")';
			} elseif ($frm['abonne'] == 'no') {
				$sql_where_array[] = 'u.platinum_until<"' . time() . '" AND u.diamond_until<"' . time() . '"';
			} elseif ($frm['abonne'] == 'never') { // Jamais été abonné
				$sql_where_array[] = 'u.platinum_status="NO" AND u.diamond_status="NO"';
			} elseif ($frm['abonne'] == 'earlier') { // Pas abonné actuellement mais l'a déjà été
				$sql_where_array[] = '(u.platinum_until BETWEEN 1 AND "' . time() . '" OR u.diamond_until BETWEEN 1 AND "' . time() . '")';
			}
		}
		if ($frm['abonne'] == 'platinum_until') { // Platinum
			$sql_where_array[] = 'u.platinum_status="YES" AND u.platinum_until!=0';
		} elseif ($frm['abonne'] == 'diamond_until') { // Diamond
			$sql_where_array[] = 'u.diamond_until!=0 AND u.diamond_status="YES" ';
		}
	}
	if (is_annonce_module_active()) {
		if (!empty($frm['list_annonce']) && $frm['list_annonce'] != '0') {
			$sql_inner_array['peel_lot_vente'] = 'INNER JOIN peel_lot_vente plv ON plv.id_personne=u.id_utilisateur';
			$sql_inner_array['peel_categories_annonces'] = 'INNER JOIN peel_categories_annonces pcan ON pcan.id=plv.id_categorie';
			$sql_where_array[] = 'plv.id_categorie="' . nohtml_real_escape_string($frm['list_annonce']) . '"';
		}
		if (!empty($frm['annonces_contiennent'])) {
			$sql_inner_array['peel_lot_vente'] = 'INNER JOIN peel_lot_vente plv ON plv.id_personne=u.id_utilisateur';
			foreach ($GLOBALS['admin_lang_codes'] as $lng) {
				$sql_where_array[] = 'plv.description_' . word_real_escape_string($lng) . ' LIKE "%' . nohtml_real_escape_string($frm['annonces_contiennent']) . '%"';
			}
		}
		if (!empty($frm['with_gold_ad'])) {
			$sql_inner_array['peel_gold_ads'] = 'INNER JOIN peel_gold_ads pga ON pga.user_id=u.id_utilisateur';
			$sql_where_array[] = 'pga.actif="' . nohtml_real_escape_string($frm['with_gold_ad']) . '"';
		}
	}
	foreach(array('ads_count' => 'ads_count', 'date_last_paiement' => 'date_last_paiement', 'date_derniere_connexion' => 'date', 'date_insert' => 'u.date_insert', 'date_statut_commande' => 'c.o_timestamp', 'date_contact_prevu' => 'pacp.timestamp') as $this_get => $this_sql_field) {
		if (!empty($frm[$this_get])) {
			if (substr($this_get, 0, 5) == 'date_') {				
				if(vb($frm[$this_get . '_input1'])=='') {
					continue;
				}
				if(vb($frm[$this_get . '_input1'])=='') {
					continue;
				}
				$first_value = get_mysql_date_from_user_input($frm[$this_get . '_input1']);
				if ($frm[$this_get] == '1') {
					// Une valeur cherchée uniquement : le X
					$last_value = $first_value . ' 23:59:59';
				} elseif ($frm[$this_get] == '2') {
					// Si "a partir de...", on va recupérer tous les utilisateurs
					$last_value = '2030-12-31 23:59:59';
				} elseif ($frm[$this_get] == '3' || $frm[$this_get] == '5' || $frm[$this_get] == '6' || $frm[$this_get] == '7') {
					// Entre le jour X et le jour Y
					$last_value = str_replace('0000-00-00', '2030-12-31', get_mysql_date_from_user_input($frm[$this_get . '_input2']));
					if ((!empty($frm['actual_time'])) && ($frm['actual_time'] == 1)) {
						$last_value .= ' ' . date('H:i:s', (time()));
						// $output .=$last_value;
					} else {
						$last_value .= ' 23:59:59';
					}
				} elseif ($frm[$this_get] == '4') {
					 // Avant le
					 $last_value =  str_replace('0000-00-00', '2030-12-31', $first_value);
					 $first_value = '0000-00-00 00:00:00';
				} else {
					$output .=$GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CASE_NOT_FORECASTED'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': %s', $frm[$this_get])))->fetch();
				}
			} else {
				$first_value = vb($frm[$this_get . '_input1']);
				$last_value = vb($frm[$this_get . '_input2']);
			}
			$this_cond_temp_expression = word_real_escape_string($this_sql_field) . '>="' . nohtml_real_escape_string($first_value) . '"';
			if ($last_value != '2030-12-31 23:59:59') {
				// On ne passe jamais ici normalement car on ne serait pas dans le cas "à partir du" - mais on laisse pour sécurité
				$this_cond_temp_expression .= ' AND ' . word_real_escape_string($this_sql_field) . '<"' . nohtml_real_escape_string($last_value) . '"';
			}
			// if ($this_get == 'next_contact_date') {
			// $sql_where_array[] = 'u.' . $users_table_fields['users_' . str_replace('date', 'timestamp', $this_get)] . '>="' . strtotime($first_value) . '" AND u.' . $users_table_fields['users_' . str_replace('date', 'timestamp', $this_get)] . '<"' . strtotime($last_value) . '"';
			if ($this_get == 'date_derniere_connexion') {
				// Champ pas dans la table peel_utilisateurs mais calculée à partir d'un MAX(uc.date) venant de peel_utilisateur_connexions
				// ATTENTION : il y a eu des problèmes avec les jointures générées si on faisait une jonture normale (requête durant 200s en juin 2009 !)
				// =>il vaut 1000 fois mieux avoir une sous-requête qui trouve d'abord la liste des utilisateurs connectés dans la plage de dates recherchée, et après on fait jointure avec INNER JOIN
				if ($frm[$this_get] == '2') {
					// dernière connexion à partir du X
					// Cas plus optimisé que les autres => pas de jointure LEFT JOIN en plus comme le cas d'après
					// Pas besoin d'ajouter une sql_cond_array, c'est le INNER JOIN qui suffit
					$sql_inner_array['peel_utilisateur_connexions'] = 'INNER JOIN (SELECT user_id, user_login, user_ip, MAX(date) AS date FROM peel_utilisateur_connexions WHERE ' . $this_cond_temp_expression . ' GROUP BY user_id) uc ON uc.user_id=u.id_utilisateur
';
				} else {
					// Dernière connexion avant le "last_date" et après first_date (first_date étant peut être égal à 0000-00-00 00:00:00 si $frm[$this_get] == '4')
					$sql_inner_array['peel_utilisateur_connexions'] = 'INNER JOIN (SELECT * FROM peel_utilisateur_connexions WHERE ' . $this_cond_temp_expression . ' GROUP BY user_id) uc ON uc.user_id=u.id_utilisateur
						LEFT JOIN peel_utilisateur_connexions uc2 ON uc2.user_id=u.id_utilisateur AND uc2.date>"' . nohtml_real_escape_string($last_value) . '"
';
					$sql_where_array[] = 'uc2.date IS NULL';
					// Pour accélérer les requêtes et éviter des recherches inutiles dans uc, on met une condition sur la date d'inscription
					$sql_where_array[] = 'u.date_insert<="' . nohtml_real_escape_string($last_value) . '"';
				}
			} elseif ($this_get == 'date_last_paiement') {
				// Utilisation de la date de paiement pour appliquer le filtre "Date de dernier paiement :"
				$sql_columns_array[] = 'MAX(c.a_timestamp) AS date_last_paiement';
				$sql_where_array[] = 'id_statut_paiement IN ("2","3")';
				$sql_having_array[] = $this_cond_temp_expression;
			} elseif ($this_get == 'date_statut_commande') {
				if ($frm['date_statut_commande'] == '5') {
					// Pas de commande entre x et y (payée ou non)
					$sql_where_array[] = 'u.id_utilisateur NOT IN (
						SELECT c2.id_utilisateur
						FROM peel_commandes c2
						WHERE ' . str_replace('c.', 'c2.', $this_cond_temp_expression) . ')';
				} elseif ($frm['date_statut_commande'] == '6') {
					// Commande non payée
					$sql_where_array[] = $this_cond_temp_expression;
				} else {
					// Commande payée entre x et y
					$sql_where_array[] = str_replace('o_timestamp', 'a_timestamp', $this_cond_temp_expression);
				}
			} elseif ($this_get == 'date_contact_prevu') {
				$sql_inner_array['peel_admins_contacts_planified'] = 'INNER JOIN peel_admins_contacts_planified pacp ON pacp.user_id=u.id_utilisateur';
				$timestamp_planified_contact_1 = mktime(0, 0, 0, intval(String::substr($frm[$this_get . '_input1'], 3, 2)),
					intval(String::substr($frm[$this_get . '_input1'], 0, 2)),
					intval(String::substr($frm[$this_get . '_input1'], 6, 4)));

				$timestamp_planified_contact_2 = mktime(0, 0, 0, intval(String::substr($frm[$this_get . '_input2'], 3, 2)),
					intval(String::substr($frm[$this_get . '_input2'], 0, 2)),
					intval(String::substr($frm[$this_get . '_input2'], 6, 4)));
				// Date de contact égal à
				if ($frm[$this_get] == 1) {
					$sql_where_array[] = 'pacp.timestamp = "' . nohtml_real_escape_string($timestamp_planified_contact_1) . '"';
					// Date de contact à partir de
				} elseif ($frm[$this_get] == 2) {
					$sql_where_array[] = 'pacp.timestamp > "' . nohtml_real_escape_string($timestamp_planified_contact_1) . '"';
					// Date de contact à entre le
				} elseif ($frm[$this_get] == 3) {
					$sql_where_array[] = 'pacp.timestamp BETWEEN "' . nohtml_real_escape_string($timestamp_planified_contact_1) . '" AND "' . nohtml_real_escape_string($timestamp_planified_contact_2) . '"';
					// Date de contact avant le
				} elseif ($frm[$this_get] == 4) {
					$sql_where_array[] = 'pacp.timestamp < "' . nohtml_real_escape_string($timestamp_planified_contact_1) . '"';
				}
			} elseif (!empty($frm['ads_count'])) {
				$sql_inner_array['peel_lot_vente'] = 'INNER JOIN peel_lot_vente plv ON plv.id_personne=u.id_utilisateur';
				$sql_columns_array[] = 'COUNT(plv.ref) AS ads_count';
				// Nombre d'annonce égal à
				if (intval($frm['ads_count']) == 1) {
					$sql_having_array[] = 'ads_count = "' . nohtml_real_escape_string($first_value) . '"';
					// Nombre d'annonce supérieur à
				} elseif (intval($frm['ads_count']) == 2) {
					$sql_having_array[] = 'ads_count > "' . nohtml_real_escape_string($first_value) . '"';
					// Nombre d'annonce comprise entre
				} elseif (intval($frm['ads_count']) == 3) {
					$sql_having_array[] = 'ads_count BETWEEN "' . nohtml_real_escape_string($first_value) . '" AND "' . nohtml_real_escape_string($last_value) . '"';
					// Nombre d'annonce inférieur à
				} elseif (intval($frm['ads_count']) == 4) {
					$sql_having_array[] = 'ads_count < "' . nohtml_real_escape_string($first_value) . '"';
				}
			} else {
				$sql_where_array[] = $this_cond_temp_expression;
			}
		}
	}
	if (!empty($cle)) {
		$sql_where_array[] = "(u.code_client LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.email LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.ville LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.nom_famille LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.code_postal LIKE '%" . nohtml_real_escape_string($cle) . "%') ";
	}
	if (!empty($priv) && $priv == "newsletter") {
		$sql_where_array[] = "u.newsletter = '1'";
	} elseif (!empty($priv)) {
		$sql_where_array[] = "u.priv = '" . nohtml_real_escape_string($priv) . "'";
	}
	$sql = "SELECT " . implode(', ', $sql_columns_array) . ", p.name_".$_SESSION['session_langue']." AS profil_name, SUM(".(display_prices_with_taxes_active()?'montant':'montant_ht').") AS total_ordered, COUNT(c.id) AS count_ordered
		FROM peel_utilisateurs u
		LEFT JOIN peel_profil p ON p.priv=u.priv
		LEFT JOIN peel_commandes c ON c.id_utilisateur=u.id_utilisateur AND c.id_statut_paiement NOT IN (6)
		" . implode(' ', $sql_inner_array) . "
		WHERE " . implode(' AND ', $sql_where_array) . '
		GROUP BY u.id_utilisateur
		';
	if (!empty($sql_having_array)) {
		$sql .= '
		HAVING (' . implode(') AND (', $sql_having_array) . ') ';
	}
	$Links = new Multipage($sql, 'utilisateurs');
	$HeaderTitlesArray = array($GLOBALS["STR_ADMIN_ADMIN_ACTIONS_ACTIONS"], 'code_client' => $GLOBALS["STR_ADMIN_PRIVILEGE"].' / '.$GLOBALS["STR_ADMIN_UTILISATEURS_CLIENT_CODE"], 'nom_famille' => $GLOBALS["STR_FIRST_NAME"].' / '.$GLOBALS["STR_LAST_NAME"].'<br />'.$GLOBALS["STR_EMAIL"]);
	$HeaderTitlesArray[] = $GLOBALS["STR_TELEPHONE"];
	if (is_groups_module_active()) {
		$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_GROUP"];
	}
	$HeaderTitlesArray['date_insert'] = $GLOBALS["STR_ADMIN_UTILISATEURS_REGISTRATION_DATE"];
	$HeaderTitlesArray['total_ordered'] = $GLOBALS["STR_ADMIN_INDEX_ORDERS_LIST"];
	$HeaderTitlesArray['remise_percent'] = $GLOBALS["STR_ADMIN_DISCOUNT"];
	$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_UTILISATEURS_WAITING_CREDIT"];
	$HeaderTitlesArray['points'] = $GLOBALS['STR_GIFT_POINTS'];
	if (is_parrainage_module_active()) {
		$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_UTILISATEURS_SPONSORED_ORDERS"];
		$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_UTILISATEURS_HAS_SPONSOR"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':';
	}
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = $order;
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();
	if(empty($results_array) && $allow_message_no_result) {
		$output .=$GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_SEARCH_NO_RESULT']))->fetch();
	} else {
		$select_search_array['date_insert'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"].' ' . $GLOBALS["STR_ADMIN_DATE_ON"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"].' '.$GLOBALS["STR_ADMIN_DATE_AFTER"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"].' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"] . ' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"]. $GLOBALS['STR_BEFORE_TWO_POINTS'].':');
		$select_search_array['date_last_paiement'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' ' . $GLOBALS["STR_ADMIN_DATE_ON"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' '.$GLOBALS["STR_ADMIN_DATE_AFTER"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' '.$GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
		$select_search_array['date_statut_commande'] = array(5 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_NO_ORDER"].' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 6 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_ORDER_NOT_PAID"] . ' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 7 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_ORDER_PAID"].' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
		$select_search_array['date_derniere_connexion'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' ' . $GLOBALS["STR_ADMIN_DATE_ON"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' ' .$GLOBALS["STR_ADMIN_DATE_AFTER"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
		$select_search_array['date_contact_prevu'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' ' . $GLOBALS["STR_ADMIN_DATE_ON"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' ' .$GLOBALS["STR_ADMIN_DATE_AFTER"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
		$select_search_array['ads_count'] = array(1 => $GLOBALS["STR_ADMIN_COMPARE_EQUALS"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_MORE_THAN"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_COMPARE_BETWEEN"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_LESS_THAN"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
		$select_search_array['abonne'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NEVER"], 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NOT_NOW"], 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NOT_NOW_BUT_EARLIER"], 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_ALL"]);
		$select_search_array['nombre_produit'] = tab_followed_nombre_produit();
		
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_utilisateur_liste.tpl');
		$GLOBALS['js_ready_content_array'][] = '
			display_input2_element("search_date_insert");
			display_input2_element("search_date_last_paiement");
			display_input2_element("search_date_statut_commande");
			display_input2_element("search_date_contact_prevu");
			display_input2_element("search_date_derniere_connexion");
			display_input2_element("search_ads_count");

			$("#search_details").on("hide.bs.collapse", function () {
				$("#search_icon").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
				// $("#search_col").removeClass("col-md-12").removeClass("col-sm-12").addClass("col-md-9").addClass("col-sm-4");
			});
			$("#search_details").on("show.bs.collapse", function () {
				$("#search_icon").removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-down");
				// $("#search_col").removeClass("col-md-9").removeClass("col-sm-4").addClass("col-md-12").addClass("col-sm-12");
			});
		';

		$tpl->assign('action', get_current_url(false));
		$tpl->assign('profil_select_options', get_profil_select_options(vb($_GET['priv'])));
		$tpl->assign('newsletter_options', formSelect('newsletter', tab_followed_newsletter(), vb($_GET['newsletter'])));
		$tpl->assign('offre_commercial_options', formSelect('offre_commercial', tab_followed_newsletter(), vb($_GET['offre_commercial'])));
		$tpl->assign('is_advanced_search', (count($sql_where_array) - $basic_search_where_count) > 0);

		// sélection des commerciaux
		$comm_query = query('SELECT u.id_utilisateur, u.prenom, u.nom_famille
			FROM peel_utilisateurs u2
			INNER JOIN peel_utilisateurs u ON u.id_utilisateur = u2.commercial_contact_id
			WHERE u2.commercial_contact_id != 0
			GROUP BY u2.commercial_contact_id');
		$tpl_comm_opts = array();
		while ($commercial = fetch_assoc($comm_query)) {
			$tpl_comm_opts[] = array('value' => $commercial["id_utilisateur"],
				'issel' => String::str_form_value(vb($_GET['commercial'])) == $commercial["id_utilisateur"],
				'prenom' => vb($commercial["prenom"]),
				'nom_famille' => vb($commercial["nom_famille"])
				);
		}
		$tpl->assign('commercial_options', $tpl_comm_opts);

		$tpl->assign('country_select_options', get_country_select_options(null, vb($_GET['pays']), 'id', true, null, false));

		$tpl_langs = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$tpl_langs[] = array('value' => $lng,
				'issel' => (vb($_GET['user_lang']) == $lng),
				'name' => $lng
				);
		}
		$tpl->assign('langs', $tpl_langs);

		// sélection des continents
		$query_continent = query("SELECT id, name_" . $_SESSION['session_langue'] . " AS name
			FROM peel_continents
			ORDER BY name_".$_SESSION['session_langue']);
		$tpl_continent_inps = array();
		while ($continent = fetch_assoc($query_continent)) {
			$tpl_continent_inps[] = array('value' => $continent['id'],
				'issel' => !empty($_GET['continent']) && is_array($_GET['continent']) && in_array($continent['id'], $_GET['continent']),
				'name' => $continent['name']
				);
		}
		$tpl->assign('continent_inputs', $tpl_continent_inps);

		$tpl_date_insert_opts = array();
		foreach ($select_search_array['date_insert'] as $index => $item) {
			$tpl_date_insert_opts[] = array('value' => $index,
				'issel' => String::str_form_value(vb($_GET['date_insert'])) == $index,
				'name' => $item
				);
		}
		$tpl->assign('date_insert_options', $tpl_date_insert_opts);

		$tpl_date_last_paiement_opts = array();
		foreach ($select_search_array['date_last_paiement'] as $index => $item) {
			$tpl_date_last_paiement_opts[] = array('value' => $index,
				'issel' => String::str_form_value(vb($_GET['date_last_paiement'])) == $index,
				'name' => $item
				);
		}
		$tpl->assign('date_last_paiement_options', $tpl_date_last_paiement_opts);

		$tpl_date_statut_commande_opts = array();
		foreach ($select_search_array['date_statut_commande'] as $index => $item) {
			$tpl_date_statut_commande_opts[] = array('value' => $index,
				'issel' => String::str_form_value(vb($_GET['date_statut_commande'])) == $index,
				'name' => $item
				);
		}
		$tpl->assign('date_statut_commande_options', $tpl_date_statut_commande_opts);

		$tpl_user_origin_opts = array();
		$i = 1;
		while (isset($GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i])) {
			$tpl_user_origin_opts[] = array('value' => $i,
				'issel' => String::str_form_value(vb($_GET['origin'])) == $i,
				'name' => $GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i]
				);
			$i++;
		}
		$tpl->assign('user_origin_options', $tpl_user_origin_opts);

		$tpl->assign('ville_cp', vb($_GET['ville_cp']));
		$tpl->assign('seg_who', formSelect('seg_who', tab_who(), vb($_GET['seg_who'])));
		$tpl->assign('seg_buy', formSelect('seg_buy', tab_buy(), vb($_GET['seg_buy'])));
		$tpl->assign('seg_want', formSelect('seg_want', tab_want(), vb($_GET['seg_want'])));
		$tpl->assign('seg_think', formSelect('seg_think', tab_think(), vb($_GET['seg_think'])));
		$tpl->assign('seg_followed', formSelect('seg_followed', tab_followed(), vb($_GET['seg_followed'])));

		$tpl->assign('is_destockplus_module_active', is_destockplus_module_active());
		$tpl->assign('is_abonnement_module_active', is_abonnement_module_active());
		if (is_abonnement_module_active()) {
			$tpl->assign('abonne', formSelect('abonne', tab_followed_abonne(), vb($_GET['abonne'])));
		}

		$tpl_produits_opts = array();
		$prod_query = query('SELECT id, nom_' . $_SESSION['session_langue'] . '
			FROM peel_produits
			ORDER BY nom_' . $_SESSION['session_langue'] .'
			LIMIT 200');
		while ($this_product = fetch_assoc($prod_query)) {
			$tpl_produits_opts[] = array('value' => $this_product['id'],
				'issel' => String::str_form_value(vb($_GET['list_produit'])) == $this_product['id'],
				'name' => $this_product['nom_' . $_SESSION['session_langue']],
				'id' => $this_product['id']
				);
		}
		$tpl->assign('produits_options', $tpl_produits_opts);
		$tpl->assign('nombre_produit', formSelect('nombre_produit', tab_followed_nombre_produit(), vb($_GET['nombre_produit'])));
		$tpl->assign('is_annonce_module_active', is_annonce_module_active());
		if (is_annonce_module_active()) {
			$tpl_ads_opts = array();
			foreach ($select_search_array['ads_count'] as $index => $item) {
				$tpl_ads_opts[] = array('value' => $index,
					'issel' => (vb($_GET['ads_count']) == $index),
					'name' => $item
					);
			}
			$tpl->assign('ads_options', $tpl_ads_opts);

			$tpl_annonces_opts = array();
			$ad_categories = get_ad_categories();
			foreach ($ad_categories as $this_category_id => $this_category_name) {
				$tpl_annonces_opts[] = array('value' => $this_category_id,
					'issel' => (vb($_GET['list_annonce']) == $this_category_id),
					'name' => $this_category_name
					);
			}
			$tpl->assign('annonces_options', $tpl_annonces_opts);
		}

		$tpl_date_contact_prevu_opts = array();
		foreach ($select_search_array['date_contact_prevu'] as $index => $item) {
			$tpl_date_contact_prevu_opts[] = array('value' => $index,
				'issel' => (vn($_GET['date_contact_prevu']) == $index),
				'name' => $item
				);
		}
		$tpl->assign('date_contact_prevu_options', $tpl_date_contact_prevu_opts);
		$tpl->assign('raison', formSelect('raison', tab_followed_reason(), vb($_GET['raison'])));

		$tpl_date_derniere_connexion_opts = array();
		foreach ($select_search_array['date_derniere_connexion'] as $index => $item) {
			$tpl_date_derniere_connexion_opts[] = array('value' => $index,
				'issel' => (vb($_GET['date_derniere_connexion']) == $index),
				'name' => $item
				);
		}
		$tpl->assign('date_derniere_connexion_options', $tpl_date_derniere_connexion_opts);
		$tpl->assign('count_HeaderTitlesArray', count($HeaderTitlesArray));
		$tpl->assign('nbRecord', vn($Links->nbRecord));
		$tpl->assign('is_client_info', isset($_GET['client_info']));
		$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
		$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);
		$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
		$tpl->assign('priv', $priv);
		$tpl->assign('cle', $cle);
		$tpl->assign('link_multipage', $Links->GetMultipage());
		$tpl->assign('link_HeaderRow', $Links->getHeaderRow());
		$tpl->assign('is_not_demo', !a_priv('demo'));
		$tpl->assign('is_groups_module_active', is_groups_module_active());
		$tpl->assign('is_parrainage_module_active', is_parrainage_module_active());

		if (!empty($results_array)) {
			$tpl_results = array();
			$i = 0;
			foreach ($results_array as $user) {
				$phone_output_array = array();
				if (!empty($user['telephone']) && is_phone_cti_module_active()) {
					$phone_output_array[] = getCallLink($user['id_utilisateur'], String::str_shorten_words($user['telephone'], 16, ' '), $user['email'], $user['pays'], true);
				} elseif (!empty($user['telephone'])) {
					$phone_output_array[] = $user['telephone'];
				}
				if (!empty($user['portable']) && is_phone_cti_module_active()) {
					$phone_output_array[] = getCallLink($user['id_utilisateur'], String::str_shorten_words($user['portable'], 16, ' '), $user['email'], $user['pays'], true);
				} elseif (!empty($user['portable'])) {
					$phone_output_array[] = $user['portable'];
				}

				$tpl_annonces_count = null;
				if (is_annonce_module_active()) { // si le module d'annonce est activé
					$annonces_count = query('SELECT count(*) AS nb
						FROM peel_lot_vente
						WHERE id_personne = ' . intval($user['id_utilisateur']));
					$annonces_count = fetch_assoc($annonces_count);
					$tpl_annonces_count = vn($annonces_count["nb"]);
				}

				$tpl_group_nom = null;
				$tpl_group_remise = null;
				if (is_groups_module_active()) {
					$sqlG = "SELECT *
						FROM peel_groupes
						WHERE id = '" . intval($user['id_groupe']) . "'";
					$resG = query($sqlG);
					if ($G = fetch_object($resG)) {
						$tpl_group_nom = $G->nom;
						$tpl_group_remise = $G->remise;
					}
				}

				$tpl_calculer_avoir_client_prix = null;
				$tpl_compter_nb_commandes_parrainees = null;
				$tpl_recuperer_parrain = null;
				if (is_parrainage_module_active()) {
					$tpl_compter_nb_commandes_parrainees = compter_nb_commandes_parrainees($user['id_utilisateur']);
					$tpl_recuperer_parrain = recuperer_parrain($user['id_utilisateur']);
				}

				$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
					'id_utilisateur' => $user['id_utilisateur'],
					'email' => vb($user['email']),
					'drop_href' => get_current_url(false) . '?mode=suppr&id_utilisateur=' . $user['id_utilisateur'],
					'init_href' => get_current_url(false) . '?mode=init_mdp&email=' . $user['email'],
					'edit_href' => get_current_url(false) . '?mode=modif&id_utilisateur=' . $user['id_utilisateur'] . '&start=' . (isset($_GET['start']) ? $_GET['start'] : 0),
					'etat' => $user['etat'],
					'modif_etat_href' => get_current_url(false) . '?mode=modif_etat&id=' . $user['id_utilisateur'] . '&etat=' . $user['etat'],
					'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($user['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
					'profil_name' => $user['profil_name'],
					'code_client' => $user['code_client'],
					'pseudo' => $user['pseudo'],
					'annonces_count' => $tpl_annonces_count,
					'prenom' => $user['prenom'],
					'nom_famille' => $user['nom_famille'],
					'societe' => $user['societe'],
					'siret_length' => String::strlen($user['siret']),
					'siret' => $user['siret'],
					'code_postal' => $user['code_postal'],
					'ville' => $user['ville'],
					'country_name' => get_country_name($user['pays']),
					'phone_output' => implode(' / ', $phone_output_array),
					'group_nom' => $tpl_group_nom,
					'group_remise' => $tpl_group_remise,
					'date_insert' => get_formatted_date($user['date_insert'], 'short', true),
					'remise_percent' => round($user['remise_percent'], 2),
					'avoir_prix' => fprix($user['avoir'], true, $GLOBALS['site_parameters']['code'], false),
					'points' => $user['points'],
					'total_ordered' => fprix($user['total_ordered'], true),
					'count_ordered' => $user['count_ordered'],
					'compter_nb_commandes_parrainees' => $tpl_compter_nb_commandes_parrainees,
					'recuperer_parrain' => $tpl_recuperer_parrain
					);

				$i++;
			}
			$tpl->assign('results', $tpl_results);
		}

		$tpl->assign('email', vb($_GET['email']));
		$tpl->assign('client_info', vb($_GET['client_info']));
		$tpl->assign('societe', vb($_GET['societe']));
		$tpl->assign('tel', vb($_GET['tel']));
		$tpl->assign('date_insert_input1', vb($_GET['date_insert_input1']));
		$tpl->assign('date_insert_input2', vb($_GET['date_insert_input2']));
		$tpl->assign('date_last_paiement_input1', vb($_GET['date_last_paiement_input1']));
		$tpl->assign('date_last_paiement_input2', vb($_GET['date_last_paiement_input2']));
		$tpl->assign('date_statut_commande_input1', vb($_GET['date_statut_commande_input1']));
		$tpl->assign('date_statut_commande_input2', vb($_GET['date_statut_commande_input2']));
		$tpl->assign('list_produit', vb($_GET['list_produit']));
		$tpl->assign('etat', vb($_GET['etat']));
		$tpl->assign('ads_count_input1', vb($_GET['ads_count_input1']));
		$tpl->assign('ads_count_input2', vb($_GET['ads_count_input2']));
		$tpl->assign('annonces_contiennent', vb($_GET['annonces_contiennent']));
		$tpl->assign('date_contact_prevu_input1', vb($_GET['date_contact_prevu_input1']));
		$tpl->assign('date_contact_prevu_input2', vb($_GET['date_contact_prevu_input2']));
		$tpl->assign('date_derniere_connexion_input1', vb($_GET['date_derniere_connexion_input1']));
		$tpl->assign('date_derniere_connexion_input2', vb($_GET['date_derniere_connexion_input2']));
		$tpl->assign('with_gold_ad', vn($_GET['with_gold_ad']));
		$tpl->assign('type', vb($_GET['type']));
		$tpl->assign('fonction', vb($_GET['fonction']));
		$tpl->assign('site_on', vb($_GET['site_on']));
		$tpl->assign('is_crons_module_active', is_crons_module_active());
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_AND', $GLOBALS['STR_AND']);
		$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
		$tpl->assign('STR_BUYERS', $GLOBALS['STR_BUYERS']);
		$tpl->assign('STR_IMPORTERS_EXPORTERS', $GLOBALS['STR_IMPORTERS_EXPORTERS']);
		$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
		$tpl->assign('STR_PURCHASING_MANAGER', $GLOBALS['STR_PURCHASING_MANAGER']);
		$tpl->assign('STR_WORD_SELLERS', $GLOBALS['STR_WORD_SELLERS']);
		$tpl->assign('STR_WHOLESALER', $GLOBALS['STR_WHOLESALER']);
		$tpl->assign('STR_HALF_WHOLESALER', $GLOBALS['STR_HALF_WHOLESALER']);
		$tpl->assign('STR_RETAILERS', $GLOBALS['STR_RETAILERS']);
		$tpl->assign('STR_ADMIN_CHOOSE_SEARCH_CRITERIA', $GLOBALS['STR_ADMIN_CHOOSE_SEARCH_CRITERIA']);
		$tpl->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
		$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
		$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
		$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
		$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
		$tpl->assign('STR_COMPANY', $GLOBALS['STR_COMPANY']);
		$tpl->assign('STR_SIREN', $GLOBALS['STR_SIREN']);
		$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
		$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
		$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_PROFILE_TYPE', $GLOBALS['STR_ADMIN_UTILISATEURS_PROFILE_TYPE']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_NEWSLETTER_SUBSCRIBER', $GLOBALS['STR_ADMIN_UTILISATEURS_NEWSLETTER_SUBSCRIBER']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_COMMERCIAL_OFFERS', $GLOBALS['STR_ADMIN_UTILISATEURS_COMMERCIAL_OFFERS']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_MANAGED_BY', $GLOBALS['STR_ADMIN_UTILISATEURS_MANAGED_BY']);
		$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
		$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_MANDATORY_FOR_MULTIPLE_SEND', $GLOBALS['STR_ADMIN_UTILISATEURS_MANDATORY_FOR_MULTIPLE_SEND']);
		$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
		$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_WHO', $GLOBALS['STR_ADMIN_UTILISATEURS_WHO']);
		$tpl->assign('STR_TYPE', $GLOBALS['STR_TYPE']);
		$tpl->assign('STR_FONCTION', $GLOBALS['STR_FONCTION']);
		$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
		$tpl->assign('STR_BUYERS', $GLOBALS['STR_BUYERS']);
		$tpl->assign('STR_IMPORTERS_EXPORTERS', $GLOBALS['STR_IMPORTERS_EXPORTERS']);
		$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
		$tpl->assign('STR_PURCHASING_MANAGER', $GLOBALS['STR_PURCHASING_MANAGER']);
		$tpl->assign('STR_WORD_SELLERS', $GLOBALS['STR_WORD_SELLERS']);
		$tpl->assign('STR_WHOLESALER', $GLOBALS['STR_WHOLESALER']);
		$tpl->assign('STR_HALF_WHOLESALER', $GLOBALS['STR_HALF_WHOLESALER']);
		$tpl->assign('STR_RETAILERS', $GLOBALS['STR_RETAILERS']);
		$tpl->assign('STR_LEADER', $GLOBALS['STR_LEADER']);
		$tpl->assign('STR_MANAGER', $GLOBALS['STR_MANAGER']);
		$tpl->assign('STR_EMPLOYEE', $GLOBALS['STR_EMPLOYEE']);
		$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
		$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_BUY', $GLOBALS['STR_ADMIN_UTILISATEURS_BUY']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_WANTS', $GLOBALS['STR_ADMIN_UTILISATEURS_WANTS']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_THINKS', $GLOBALS['STR_ADMIN_UTILISATEURS_THINKS']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_BY', $GLOBALS['STR_ADMIN_UTILISATEURS_FOLLOWED_BY']);
		$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
		$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
		$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
		$tpl->assign('STR_CONTINENT', $GLOBALS['STR_CONTINENT']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_ORDER_STATUS_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_ORDER_STATUS_DATE']);
		$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
		$tpl->assign('STR_ADMIN_CHECK_ALL', $GLOBALS['STR_ADMIN_CHECK_ALL']);
		$tpl->assign('STR_ADMIN_UNCHECK_ALL', $GLOBALS['STR_ADMIN_UNCHECK_ALL']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_EMAIL_TO_SELECTED_USERS', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_EMAIL_TO_SELECTED_USERS']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_REGISTRATION_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_REGISTRATION_DATE']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_PAYMENT_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_PAYMENT_DATE']);
		$tpl->assign('STR_ADMIN_DATE_BETWEEN_AND', $GLOBALS['STR_ADMIN_DATE_BETWEEN_AND']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_PAYMENT_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_PAYMENT_DATE']);
		$tpl->assign('STR_ORIGIN', $GLOBALS['STR_ORIGIN']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_SUBSCRIBER', $GLOBALS['STR_ADMIN_UTILISATEURS_SUBSCRIBER']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_PRODUCT_BOUGHT_AND_QUANTITY', $GLOBALS['STR_ADMIN_UTILISATEURS_PRODUCT_BOUGHT_AND_QUANTITY']);
		if (is_annonce_module_active()) {
			if (!empty($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) && $GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories'] == 'checkbox') {
				$tpl->assign('favorite_category', get_announcement_select_options(null, vb($_GET['id_categories']), 'id', false, false, 'checkbox', 'id_categories'));	
			} else {
				$tpl->assign('favorite_category_1', get_announcement_select_options(null, vb($_GET['id_cat_1']), 'id'));
				$tpl->assign('favorite_category_2', get_announcement_select_options(null, vb($_GET['id_cat_2']), 'id'));
				$tpl->assign('favorite_category_3', get_announcement_select_options(null, vb($_GET['id_cat_3']), 'id'));
			}
			$tpl->assign('STR_ADMIN_CHOOSE_FAVORITE_CATEGORIES', $GLOBALS['STR_ADMIN_CHOOSE_FAVORITE_CATEGORIES']);
			$tpl->assign('STR_FIRST_CHOICE', $GLOBALS['STR_FIRST_CHOICE']);
			$tpl->assign('STR_SECOND_CHOICE', $GLOBALS['STR_SECOND_CHOICE']);
			$tpl->assign('STR_THIRD_CHOICE', $GLOBALS['STR_THIRD_CHOICE']);
			$tpl->assign('STR_MODULE_ANNONCES_ADMIN_USER_WITH_GOLD', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_USER_WITH_GOLD']);
			$tpl->assign('STR_MODULE_ANNONCES_ADMIN_USER_ADS_COUNT', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_USER_ADS_COUNT']);
			$tpl->assign('STR_MODULE_ANNONCES_ADMIN_ADS_AVAILABLE', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_ADS_AVAILABLE']);
			$tpl->assign('STR_MODULE_ANNONCES_ADMIN_ADS_CONTAIN', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_ADS_CONTAIN']);
			$tpl->assign('STR_MODULE_ANNONCES_AD', $GLOBALS['STR_MODULE_ANNONCES_AD']);
		}
		$tpl->assign('STR_ADMIN_UTILISATEURS_CONTACT_FORECASTED_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_CONTACT_FORECASTED_DATE']);
		$tpl->assign('STR_ADMIN_DATE_BETWEEN_AND', $GLOBALS['STR_ADMIN_DATE_BETWEEN_AND']);
		$tpl->assign('STR_ADMIN_REASON', $GLOBALS['STR_ADMIN_REASON']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_LOGIN_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_LOGIN_DATE']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_USERS_COUNT', $GLOBALS['STR_ADMIN_UTILISATEURS_USERS_COUNT']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_ORDERS_LIST', $GLOBALS['STR_ADMIN_UTILISATEURS_ORDERS_LIST']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE_ORDER', $GLOBALS['STR_ADMIN_UTILISATEURS_CREATE_ORDER']);
		$tpl->assign('STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_SUBTITLE', $GLOBALS['STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_SUBTITLE']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_LIST_EXPLAIN', $GLOBALS['STR_ADMIN_UTILISATEURS_LIST_EXPLAIN']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE', $GLOBALS['STR_ADMIN_UTILISATEURS_CREATE']);
		$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_EXCEL_EXPORT', $GLOBALS['STR_ADMIN_UTILISATEURS_EXCEL_EXPORT']);
		$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD_CONFIRM', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD_CONFIRM']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_UPDATE', $GLOBALS['STR_ADMIN_UTILISATEURS_UPDATE']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_DEACTIVATE_USER', $GLOBALS['STR_ADMIN_UTILISATEURS_DEACTIVATE_USER']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_UPDATE_STATUS', $GLOBALS['STR_ADMIN_UTILISATEURS_UPDATE_STATUS']);
		$tpl->assign('STR_NUMBER', $GLOBALS['STR_NUMBER']);
		$tpl->assign('STR_SIRET', $GLOBALS['STR_SIRET']);
		$tpl->assign('STR_NONE', $GLOBALS['STR_NONE']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_NO_SUPPLIER_FOUND', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_SUPPLIER_FOUND']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_FILER_EXPLAIN', $GLOBALS['STR_ADMIN_UTILISATEURS_FILER_EXPLAIN']);
		$tpl->assign('STR_ORDER_FORM', $GLOBALS['STR_ORDER_FORM']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_GIFT_CHECK', $GLOBALS['STR_ADMIN_UTILISATEURS_GIFT_CHECK']);
		$tpl->assign('STR_MORE_DETAILS', $GLOBALS['STR_MORE_DETAILS']);

		if (is_crons_module_active() && is_webmail_module_active()) {
			$tpl->assign('send_email_all_form', get_send_email_all_form($Links, $sql));
		}

		$output .=$tpl->fetch();
	}
	return $output;
}


/**
 *
 * @param mixed $frm
 * @return
 */
function create_or_update_comments($frm)
{
	$q = query('SELECT comments
		FROM peel_admins_comments
		WHERE id_user="' . intval($frm['id_utilisateur']) . '"');
	if ($existing_comments = fetch_assoc($q)) {
		if (nohtml_real_escape_string($existing_comments['comments']) != $frm['comments']) {
			// Commentaire pout cet utilisateur existe déjà mais a été modifié
			// => on met à jour l'admin (on ne se souvient que du dernier admin qui a édité le message)
			query('UPDATE peel_admins_comments
				SET comments="' . nohtml_real_escape_string($frm['comments']) . '", admin_id="' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '", timestamp="' . time() . '"
				WHERE id_user="' . intval($frm['id_utilisateur']) . '"');
		}
	} else {
		query('INSERT INTO peel_admins_comments
			SET comments="' . nohtml_real_escape_string($frm['comments']) . '", admin_id="' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '", timestamp="' . time() . '", id_user="' . intval($frm['id_utilisateur']) . '"');
	}
}

/**
 * supprime_logo()
 *
 * @param integer $id
 * @return
 */
function supprime_logo ($id)
{
	$output = '';
	$sql = "SELECT logo
		FROM peel_utilisateurs
		WHERE id_utilisateur='" . intval($id) . "'";
	$res = query($sql);
	if ($logo_info = fetch_assoc($res)) {
		query("UPDATE peel_utilisateurs
			SET logo = ''
			WHERE id_utilisateur='" . intval($id) . "'");

		if (!empty($logo_info) && delete_uploaded_file_and_thumbs($logo_info['logo'])) {
			$output .=$GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_LOGO_DELETED']))->fetch();
		}
	}
	return $output;
}

?>
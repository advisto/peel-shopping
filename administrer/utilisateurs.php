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
// $Id: utilisateurs.php 36232 2013-04-05 13:16:01Z gboussin $
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
		die();
	} else {
		include("modeles/haut.php");
		if ($form_error_object->has_error('token')) {
			echo $form_error_object->text('token');
		}
	}
} else {
	include("modeles/haut.php");
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
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($message, vb($utilisateur['email']))))->fetch();
		afficher_liste_utilisateurs($priv, $cle);
		break;
	case "ajout" :
		afficher_formulaire_ajout_utilisateur();
		break;

	case "modif" :
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;

	case "suppr" :
		$utilisateur = get_user_information($id_utilisateur);
		efface_utilisateur($id_utilisateur);
		$annonce_active = false;
		if (is_annonce_module_active()) {
			delete_user_ads($id_utilisateur);
			$annonce_active = true;
		}
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_DELETED_OK'] . ($annonce_active?' - ' . $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_ADS_ALSO_DEACTIVATED'] : ''), $utilisateur['email'])))->fetch();
		afficher_liste_utilisateurs($priv, $cle);
		break;

	case "supprlogo" :
		supprime_logo($id_utilisateur);

		affiche_formulaire_modif_utilisateur($id_utilisateur);
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
			$frm['logo'] = upload('logo', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
			$frm['document'] = upload('document', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
			$frm['mot_passe'] = (!empty($frm['mot_passe']))?$frm['mot_passe']:MDP();
			if (insere_utilisateur($frm, false, false, false)) {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_CREATED_OK'], vb($frm['email']), $frm['mot_passe'])))->fetch();
			}
			// Envoi de l'e-mail
			if (isset($frm['notify'])) {
				send_mail_for_account_creation(vb($frm['email']), vb($frm['mot_passe']));
			}
			afficher_liste_utilisateurs($priv, $cle);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			} elseif ($form_error_object->has_error('email')) {
				echo $form_error_object->text('email');
			}
			if ($form_error_object->has_error('pseudo')) {
				echo $form_error_object->text('pseudo');
			}
			afficher_formulaire_ajout_utilisateur();
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
			$frm['logo'] = upload('logo', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
			$frm['document'] = upload('document', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
			// Suppression de l'ancien fichier
			if (!empty($frm['old_document']) && $frm['document'] != $frm['old_document']) {
				delete_uploaded_file_and_thumbs($frm['old_document']);
			}
			maj_utilisateur($frm, false);
			tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'EDIT_PROFIL', 'Compte : ' . vb($frm['email']));
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_UPDATED_OK'], vb($frm['email']))))->fetch();
			affiche_formulaire_modif_utilisateur($id_utilisateur);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			} elseif ($form_error_object->has_error('email')) {
				echo $form_error_object->text('email');
			}
			if ($form_error_object->has_error('pseudo')) {
				echo $form_error_object->text('pseudo');
			}
			affiche_formulaire_modif_utilisateur($id_utilisateur);
		}
		break;

	case "liste" :
		afficher_liste_utilisateurs($priv, $cle);
		break;

	case "cheque" :
		if (is_module_gift_checks_active()) {
			include($fonctionsgiftcheck);
			// L'administrateur a validé l'envoi d'un chèque cadeau à l'utilisateur
			cree_cheque_cadeau_client(vn($id_utilisateur), "CHQ", $GLOBALS['site_parameters']['avoir'], 2);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_GIFT_CHECK_SENT']))->fetch();
		}
		afficher_liste_utilisateurs($priv, $cle);
		break;

	case "init_mdp" :
		initialise_mot_passe($_REQUEST['email']);

		$qid = query("SELECT email
			FROM peel_utilisateurs
			WHERE email = '" . nohtml_real_escape_string($_REQUEST['email']) . "'");
		if ($user = fetch_object($qid)) {
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_NEW_PASSWORD_SENT'], vb($user->email))))->fetch();
		}
		afficher_liste_utilisateurs($priv, $cle);

		break;

	case "enligne_liste_annonce" :
		if (!empty($_GET['ref']) && is_annonce_module_active()) {
			query("UPDATE peel_lot_vente
				SET enligne='" . nohtml_real_escape_string($_GET['enligne']) . "'
				WHERE ref='" . intval($_GET['ref']) . "'");
		}
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_STATUS_UPDATED'], intval($_GET['ref']))))->fetch();
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;

	case "update_list_annonce" :
		// Changer de catégorie les annonces sélectionées
		if (!empty($_POST['change_cat']) && is_annonce_module_active()) {
			if (!empty($_POST['annonce_ref'])) {
				foreach($_POST['annonce_ref'] as $ref_annonce) {
					// On vérifie que l'annonce ne soit pas une gold, car la gold utilise la table peel_gold_ads
					if (!is_gold_ad(intval(vn($ref_annonce)), false)) {
						query('UPDATE peel_lot_vente
							SET id_categorie="' . vn($_POST['change_cat_select']) . '"
							WHERE ref="' . intval(vn($ref_annonce)) . '" AND gold="0"');
						tracert_history_admin($_POST['annonce_ref'], 'EDIT_AD', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_AD_CATEGORY_UPDATED'] .' ' . intval(vn($_POST['annonce_ref'])));
					}
				}
			} else {
				$form_error_object->add('erreurselection', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_NO_AD_SELECTED']);
			}
			if ($form_error_object->count()) {
				if ($form_error_object->has_error('erreurselection')) {
					echo $form_error_object->text('erreurselection');
				}
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_AD_CATEGORY_UPDATED_FOR_SELECTION']))->fetch();
			}
		}
		// Publier les annonces sélectionnés
		if (!empty($_POST['publish']) && is_annonce_module_active()) {
			if (!empty($_POST['annonce_ref'])) {
				foreach($_POST['annonce_ref'] as $ref_annonce) {
					publish_annonce($ref_annonce);
					tracert_history_admin($_POST['annonce_ref'], 'EDIT_AD', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_AD_STATUS_UPDATED'] . ' ' . intval(vn($_POST['annonce_ref'])));
				}
			} else {
				$form_error_object->add('erreurselection', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_NO_AD_SELECTED']);
			}
			if ($form_error_object->count()) {
				if ($form_error_object->has_error('erreurselection')) {
					echo $form_error_object->text('erreurselection');
				}
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_AD_STATUS_UPDATED_FOR_SELECTION']))->fetch();
			}
		}
		// Supprimer les annonce selectioner
		if (!empty($_POST['deleteAd_up']) && is_annonce_module_active()) {
			if (!empty($_POST['annonce_ref'])) {
				foreach ($_POST['annonce_ref'] as $ref_annonce) {
					query("DELETE FROM peel_lot_vente
						WHERE ref ='" . intval($ref_annonce) . "'");
					query("DELETE FROM peel_ads_stats
						WHERE id ='" . intval($ref_annonce) . "'");
					// Si nous avons une annonce gold, il est nécessaire de vider aussi la table peel_gods_add qui référence les différentes catégories de l'annonce rattachée, ainsi que le temp de l'annonce.
					query("DELETE FROM peel_gold_ads
						WHERE ad_id ='" . intval($ref_annonce) . "'");
					// Supression des fichiers de l'annonce
					supprime_fichier_annonce(intval($ref_annonce));
					// Tracert de la suppression
					tracert_history_admin(intval(vn($_POST['annonce_ref'])), 'SUP_AD', $GLOBALS['STR_MODULE_ANNONCES_AD'] . ' ' . intval(vn($_POST['annonce_ref'])));
				}
			} else {
				$form_error_object->add('erreurselection', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_NO_AD_SELECTED']);
			}
			if ($form_error_object->count()) {
				if ($form_error_object->has_error('erreurselection')) {
					echo $form_error_object->text('erreurselection');
				}
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_AD_STATUS_DELETED_FOR_SELECTION']))->fetch();
			}
		}
		// Conversion en annonce GOLD
		if (!empty($_POST['update_to_gold'])) {
			if (!empty($_POST['annonce_ref']) && !empty($_POST['expiration_date'])) {
				foreach ($_POST['annonce_ref'] as $ref_annonce) {
					$query = "SELECT id_personne,id_categorie
						FROM peel_lot_vente
						WHERE ref ='" . intval(vn($ref_annonce)) . "' AND enligne = 'OK' AND gold = '0'";
					$qannonce = query($query);
					$annonce = fetch_assoc($qannonce);
					// Si l'annonce est en ligne et n'est pas déjà gold, alors on la convertit en GOLD
					if (!empty($annonce)) {
						// Passage de l'etat de l'annonce dans peel_lot_vente en gold = 1
						query("UPDATE peel_lot_vente
							SET gold='1'
							WHERE ref='" . intval(vn($ref_annonce)) . "'");
						$date = get_mysql_date_from_user_input($_POST['expiration_date'] . ' ' . date($GLOBALS['time_basic_format_long']));
						// Vérification si l'annonce existait déjà en gold ou non
						$query_gold = "SELECT *
							FROM peel_gold_ads
							WHERE ad_id='" . intval(vn($ref_annonce)) . "'";
						$qannonce_gold = query($query_gold);
						if ($annonce_gold = fetch_assoc($qannonce_gold)) {
							// Si l'annonce gold existe, mise à jour de la date d'expiration et de son état
							query("UPDATE peel_gold_ads
								SET actif='1', expiration_date='" . nohtml_real_escape_string($date) . "'
								WHERE ad_id='" . intval(vn($ref_annonce)) . "'");
						} else {
							// Sinon insertion dans la table peel_gold_ads de l'annonce
							$insert = "INSERT INTO peel_gold_ads
									(ad_id,user_id, expiration_date, actif, categories_list, `update`)
								VALUES('" . intval(vn($ref_annonce)) . "',
									'" . intval(vn($annonce['id_personne'])) . "',
									'" . nohtml_real_escape_string($date) . "',
									'1',
									'" . nohtml_real_escape_string(str_pad($annonce['id_categorie'], 2, "0", STR_PAD_LEFT)) . "'
									IF(u.diamond_status='YES', '".intval($GLOBALS["ads_gold_update_credit_for_period_per_subscription"]["diamond"])."', IF(u.platinum_status='YES', '".intval($GLOBALS["ads_gold_update_credit_for_period_per_subscription"]["platinum"])."', '".intval($GLOBALS["ads_gold_update_credit_for_period_per_subscription"]["free"])."')))";
							query($insert);
						}
					}
				}
			} elseif (empty($_POST['annonce_ref'])) {
				$form_error_object->add('erreurselection', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_NO_AD_SELECTED']);
			} else {
				$form_error_object->add('erreurselection', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_ERR_EMPTY_EXPIRATION_DATE']);
			}
			if ($form_error_object->count()) {
				if ($form_error_object->has_error('erreurselection')) {
					echo $form_error_object->text('erreurselection');
				}
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_MSG_CONVERTED_TO_GOLD_FOR_SELECTION']))->fetch();
			}
		}
		affiche_formulaire_modif_utilisateur($id_utilisateur);
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
						echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_CALL_INITIATED_KEYYO'], getCleanInternationalTelephone($admin_infos['telephone'], $admin_infos['pays'], true), $_GET['callee_name'], $_GET['callee'], implode(' - ', $makecall)))->fetch();
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
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	case "event_comment":
		if (!empty($_POST['form_event_comment'])) {
			// On n'enregistre que les événements avec du texte
			tracert_history_admin(intval($_REQUEST['id_utilisateur']), 'EVENT', '', (!empty($_POST['form_event_comment'])?$_POST['form_event_comment']:''));
		}
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	// Ajoute un credit gold
	case "add_credit_gold" :
		if (!empty($_POST['id_utilisateur']) && !empty($_POST['add_gold_ad']) && is_annonce_module_active()) {
			add_credit_gold_user ($_POST['id_utilisateur'], $_POST['add_gold_ad']);
			tracert_history_admin($_POST['id_utilisateur'], 'CREATE_ORDER', 'Ajout de credit gold');
		}
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	// Supprime le credit gold
	case "suppr_credit_gold" :
		if (!empty($_GET['id_utilisateur']) && !empty($_GET['id_gold']) && is_annonce_module_active()) {
			suppr_credit_gold_user ($_GET['id_utilisateur'], $_GET['id_gold']);
			tracert_history_admin($_GET['id_utilisateur'], 'SUP_ORDER', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_GOLD_CREDIT_DELETED'] . ' ' . intval(vn($_GET['id_gold'])));
		}
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	// Mise à jour de l'abonnement platinium si le module abonnement existe
	case "maj_abo_platinum":
		if (!empty($_POST['id_utilisateur']) && is_abonnement_module_active()) {
			maj_abonnement_admin($_POST);
			tracert_history_admin($_POST['id_utilisateur'], 'EDIT_ORDER', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MSG_PLATINUM_UPDATED_OK']);
		}
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	// Mise à jour de l'abonnement diamond si le module abonnement existe
	case "maj_abo_diamond":
		if (!empty($_POST['id_utilisateur']) && is_abonnement_module_active()) {
			maj_abonnement_admin($_POST);
			tracert_history_admin($_POST['id_utilisateur'], 'EDIT_ORDER', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MSG_DIAMOND_UPDATED_OK']);
		}
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	// Convertion d'un abonement en un autre si le module abonnement existe
	case "convert_abo":
		if (!empty($_POST['id_utilisateur']) && is_abonnement_module_active()) {
			if (!empty($_POST['convert_diamond_to_platinum'])) {
				userConvertSubscription($_POST['id_utilisateur'], 'diamond', 'platinum');
				tracert_history_admin($_POST['id_utilisateur'], 'EDIT_ORDER', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MSG_DIAMOND_CONVERTED_TO_PLATINUM_OK']);
			} elseif (!empty($_POST['convert_platinum_to_diamond'])) {
				userConvertSubscription($_POST['id_utilisateur'], 'platinum', 'diamond');
				tracert_history_admin($_POST['id_utilisateur'], 'EDIT_ORDER', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MSG_PLATINUM_CONVERTED_TO_DIAMOND_OK']);
			}
		}
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	// Ajout d'une planification de contact
	case "add_contact_planified":
		if (!empty($_POST['form_edit_contact_user_id']) && is_commerciale_module_active()) {
			create_or_update_contact_planified($_POST);
		}
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	// Mise à jour d'une planification de contact
	case "update_contact_planified":
		if (!empty($_POST['form_edit_contact_planified_id']) && is_commerciale_module_active()) {
			create_or_update_contact_planified($_POST);
		}
		affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	// Supression d'une planification de contact
	case "suppr_contact_planified":
		if (!empty($_POST['form_delete_admins_contacts']) && is_commerciale_module_active()) {
			foreach($_POST['form_delete_admins_contacts'] as $form_edit_contact_planified_id) {
				delete_contact_planified($form_edit_contact_planified_id);
			}
		}
		affiche_formulaire_modif_utilisateur($id_utilisateur);
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
		affiche_recherche_utilisateurs($_GET, $priv, $cle);
		break;
	case "phone" :
		if (!empty($_POST)) {
			if (!empty($_POST['phone_emitted_submit'])) {
				tracert_history_admin($_POST['id_utilisateur'], 'PHONE_EMITTED', 'NOT_ENDED_CALL', $_POST['form_phone_comment']);
				affiche_formulaire_modif_utilisateur($_POST['id_utilisateur']);
			} elseif (!empty($_POST['phone_received_submit'])) {
				tracert_history_admin($_POST['id_utilisateur'], 'PHONE_RECEIVED', 'NOT_ENDED_CALL', $_POST['form_phone_comment']);
				affiche_formulaire_modif_utilisateur($_POST['id_utilisateur']);
			} elseif (!empty($_POST['turn_off_phone'])) {
				$q = query('UPDATE peel_admins_actions
						SET raison="",
							remarque="' . nohtml_real_escape_string($_POST['form_phone_comment']) . '",
							data="' . date('Y-m-d H:i:s', time()) . '"
						WHERE id_user="' . $_SESSION['session_utilisateur']['id_utilisateur'] . '" AND id_membre="' . $_POST['id_utilisateur'] . '" AND ((action = "PHONE_EMITTED") OR (action = "PHONE_RECEIVED")) AND data="NOT_ENDED_CALL"
						');
				affiche_formulaire_modif_utilisateur($_POST['id_utilisateur']);
			}
		}
		break;
	default :
		if (!empty($_GET['commercial_contact_id']) && is_commerciale_module_active()) {
			afficher_liste_utilisateurs($priv, $cle, null, 'date_insert', $_GET['commercial_contact_id']);
		} else {
			afficher_liste_utilisateurs($priv, $cle);
			if (is_chart_module_active() && empty($_GET['page'])) {
				include($GLOBALS['dirroot'] . '/modules/chart/open_flash_chart_object.php');
				echo '<div class="center">' . open_flash_chart_object_str(1000, 300, $GLOBALS['administrer_url'] . '/chart-data.php?type=users-count&date1=' . date('Y-m-d', time()-3600 * 24 * 90) . '&date2=' . date('Y-m-d', time()) . '&width=1000', true, $GLOBALS['wwwroot'] . '/modules/chart/') . '</div>';
			}
		}
		break;
}

include("modeles/bas.php");

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

	include("modeles/utilisateur_form.php");
}

/**
 * Affiche un formulaire vide pour modifier un utilisateur
 *
 * @param integer $id_utilisateur
 * @return
 */
function affiche_formulaire_modif_utilisateur($id_utilisateur)
{
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
		include("modeles/utilisateur_form.php");
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_NOT_FOUND']))->fetch();
		return false;
	}
}

/**
 * Lit toutes les catégories de la base et les affiche dans un tableau
 *
 * @param mixed $priv
 * @param mixed $cle
 * @param mixed $sql
 * @param string $order
 * @param integer $commercial_contact_id
 * @return
 */
function afficher_liste_utilisateurs($priv, $cle, $sql = null, $order = 'date_insert')
{
	$nb = 2;
	$sql_cond = '1';
	if (empty($sql)) {
		if (!empty($cle)) {
			$sql_cond .= " AND (u.code_client LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.email LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.ville LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.nom_famille LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.code_postal LIKE '%" . nohtml_real_escape_string($cle) . "%') ";
		}
		if (!empty($priv) && $priv == "newsletter") {
			$sql_cond .= " AND u.newsletter = '1'";
		} elseif (!empty($priv)) {
			$sql_cond .= " AND u.priv = '" . nohtml_real_escape_string($priv) . "' ";
		}
		if (a_priv('demo')) {
			$sql_cond .= " AND u.priv NOT IN ('" . nohtml_real_escape_string('admin') . "') ";
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_NO_ADMIN_RIGHT_TO_LIST']))->fetch();
		}
		$sql = "SELECT u.*, p.name_".$_SESSION['session_langue']." AS profil_name
			FROM peel_utilisateurs u
			LEFT JOIN peel_profil p ON p.priv=u.priv
			WHERE " . $sql_cond . "";
	}

	$Links = new Multipage($sql, 'utilisateurs');
	$Links->OrderDefault = $order;
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();
	include("modeles/utilisateur_liste.php");
}

/**
 * Affiche la liste des utilisateurs en fonction des critères de recherche
 * Un certain nombre de champs de recherche permettent de cherche sur plusieurs colonnes, ce qui permet de simplifier l'interface
 *
 * @param mixed $frm
 * @param mixed $priv
 * @param mixed $cle
 * @return
 */
function affiche_recherche_utilisateurs($frm, $priv, $cle)
{
	$sql_inner_array = array();
	$sql_having_array = array();
	$sql_columns_array = array('u.*');
	$sql_where_array = array('1');
	$sql_group_by = '';
	$sql_having = '';
	$sql = "";
	if (!empty($frm['client_info'])) {
		$sql_where_array[] = '(u.nom_famille LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%" OR u.prenom LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%")';
	}
	if (!empty($frm['email']) && is_numeric($frm['email'])) {
		// Recherche sur une id - si par exemple on cherche 22, on ne veut pas récupérer les emails contenant 22 => on ne cherche que sur l'id
		$sql_where_array[] = 'u.id_utilisateur = "' . intval($frm['email']) . '"';
	} elseif (!empty($frm['email'])) {
		$sql_where_array[] = '(u.email LIKE "%' . nohtml_real_escape_string($frm['email']) . '%" OR u.pseudo LIKE "%' . nohtml_real_escape_string($frm['email']) . '%")';
	}
	if (!empty($frm['societe'])) {
		$sql_where_array[] = '(u.societe LIKE "%' . nohtml_real_escape_string($frm['societe']) . '%" OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(u.siret,")",""),"(",""), ".",""), "-",""), " ","") LIKE "%' . nohtml_real_escape_string(str_replace(array('(', ')', '.', '-', ' '), '', trim($frm['societe']))) . '%" OR u.url LIKE "%' . nohtml_real_escape_string($frm['societe']) . '%")';
	}

	if (!empty($frm['ville_cp'])) {
		$sql_where_array[] = '(u.ville LIKE "%' . nohtml_real_escape_string($frm['ville_cp']) . '%" OR u.code_postal LIKE "' . nohtml_real_escape_string($frm['ville_cp']) . '%")';
	}
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
		$sql_where_array[] = 'p.continent_id IN ("' . implode('","', nohtml_real_escape_string($frm['continent'])) . '")';
		$sql_inner_array['peel_pays'] = 'INNER JOIN peel_pays p ON p.id=u.pays';
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
		$sql_inner_array['peel_commandes'] = 'INNER JOIN peel_commandes c ON c.id_utilisateur=u.id_utilisateur';
		$sql_inner_array['peel_commandes_articles'] = 'INNER JOIN peel_commandes_articles pca ON pca.commande_id= c.id';

		$sql_where_array[] = 'pca.produit_id="' . nohtml_real_escape_string($frm['list_produit']) . '"';
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
			foreach ($GLOBALS['lang_codes'] as $lng) {
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
				$first_value = get_mysql_date_from_user_input($frm[$this_get . '_input1']);
				if ($frm[$this_get] == '4') {
					// Avant le
					// Date vide ou incomprise : on ne met pas de borne supérieure
					$first_value = '2030-12-31';
				}
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
						// echo $last_value;
					} else {
						$last_value .= ' 23:59:59';
					}
				} elseif ($frm[$this_get] == '4') {
					// Avant le
					$last_value = $first_value;
					$first_value = '0000-00-00 00:00:00';
				} else {
					echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CASE_NOT_FORECASTED'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': %s', $frm[$this_get])))->fetch();
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
				$sql_columns_array[] = 'MAX(c.o_timestamp) AS date_last_paiement';
				$sql_inner_array['peel_commandes'] = 'INNER JOIN peel_commandes c ON c.id_utilisateur=u.id_utilisateur AND id_statut_paiement IN ("2","3")';
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
					$sql_inner_array['peel_commandes'] = 'INNER JOIN peel_commandes c ON c.id_utilisateur=u.id_utilisateur';
				} else {
					// Commande payée entre x et y
					$sql_where_array[] = str_replace('o_timestamp', 'a_timestamp', $this_cond_temp_expression);
					$sql_inner_array['peel_commandes'] = 'INNER JOIN peel_commandes c ON c.id_utilisateur=u.id_utilisateur';
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
				$sql_columns_array[] = 'u.id_utilisateur';
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

	$sql = "SELECT DISTINCT " . implode(', ', $sql_columns_array) . ", pro.name_".$_SESSION['session_langue']." AS profil_name
		FROM peel_utilisateurs u
		" . implode(' ', $sql_inner_array) . "
		LEFT JOIN peel_profil pro ON pro.priv=u.priv
		WHERE " . implode(' AND ', $sql_where_array) . '
		';
	if (!empty($sql_having_array)) {
		$sql .= '
		GROUP BY u.id_utilisateur
		HAVING (' . implode(') AND (', $sql_having_array) . ') ';
	}
	if (!empty($sql)) {
		// Charge la liste
		afficher_liste_utilisateurs($priv, $cle, $sql);
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_SEARCH_NO_RESULT']))->fetch();
	}
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
	$sql = "SELECT logo
		FROM peel_utilisateurs
		WHERE id_utilisateur='" . intval($id) . "'";
	$res = query($sql);
	if ($logo_info = fetch_assoc($res)) {
		query("UPDATE peel_utilisateurs
			SET logo = ''
			WHERE id_utilisateur='" . intval($id) . "'");

		if (!empty($logo_info) && delete_uploaded_file_and_thumbs($logo_info['logo'])) {
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_LOGO_DELETED']))->fetch();
		}
	}
}

?>
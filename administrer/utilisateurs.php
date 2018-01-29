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
// $Id: utilisateurs.php 55928 2018-01-26 17:31:15Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users,admin_finance,admin_operations,admin_productsline");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_UTILISATEURS_TITLE'];
/* Initialisation des variables */
$id = intval(vn($_REQUEST['id']));
$id_utilisateur = intval(vn($_REQUEST['id_utilisateur']));
$frm = $_POST;
$form_error_object = new FormError();
$output = '';

$priv = vb($_GET['priv']);
$cle = trim(vb($_GET['cle']));

if (!empty($_POST['print_all_bill'])) {
	include($GLOBALS['dirroot']."/lib/class/Invoice.php");
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
	case 'insert_address':
		if(insert_or_update_address($_POST, false, $id_utilisateur)) {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_YOUR_NEW_ADDRESS_CREATE']))->fetch();
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERROR_OCCURRED']))->fetch();
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case 'create_new_address':
	$output .= '<h1>'.$GLOBALS['STR_REGISTER_ORDER_ADDRESS'].'</h1>
		' . get_address_form(null, $id_utilisateur, true);
		break;
	case 'suppr_address':
		// supprimer l'adresse dans PEEL_ADRESSES
		$output .= delete_address($_GET['id'], $id_utilisateur);
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;

	case 'modif_address':
		// modifier l'adresse dans PEEL_ADRESSES
		$q = query('SELECT *
			FROM peel_adresses
			WHERE id = "' . intval($_GET['id']) . '"');
		if($result = fetch_assoc($q)) {
			$output .= get_address_form($result, $id_utilisateur, true);
		}
		break;

	case 'update_address':
		insert_or_update_address($_POST, false, $id_utilisateur);
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_YOUR_UPDATE_ADDRESS_CREATE']))->fetch();
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;

	case "update_newsletter" :
		if (isset($_GET['new_newsletter_value']) && !empty($_GET['user_id'])) {
			query('UPDATE peel_utilisateurs
				SET newsletter="' . intval($_GET['new_newsletter_value']) . '"
				WHERE id_utilisateur="' . intval($_GET['user_id']) . '" AND ' . get_filter_site_cond('utilisateurs', null, true));
		}
		$output .= afficher_liste_utilisateurs($priv, $cle, $_GET);
		break;

	case "modif_etat" :
		if (isset($_GET['etat']) && !empty($_GET['id'])) {
			$user_infos = get_user_information($_GET['id']);
			if(!empty($user_infos)) {
				if(!a_priv('admin*', false, false, $_GET['id']) || (a_priv('admin', false, true) || (!a_priv('admin', false, false, $id_utilisateur) && (a_priv('admin_finance', false, true) || a_priv('admin_operations', false, true) || a_priv('admin_productsline', false, true))))) {
					// L'utilisateur qu'on veut modifier n'est pas un administrateur, ou alors l'utilisateur loggué a le droit de le modifier
					// NB : il faut être administrateur général pour avoir le droit de modifier les autres administrateurs.
					if ($_GET['etat'] == 1) {
						$etat = 0 ;
					} else {
						$etat = 1 ;
					}
					query('UPDATE peel_utilisateurs
						SET etat="' . intval($etat) . '"
						WHERE id_utilisateur="' . intval($_GET['id']) . '" AND ' . get_filter_site_cond('utilisateurs', null, true));
					$annonce_active = false;
					if (check_if_module_active('annonces')) {
						update_state_ads($_GET['id'], $etat);
						$annonce_active = true;
					}
					if($etat == 1) {
						tracert_history_admin(intval(vn($_GET['id'])), 'EDIT_PROFIL', sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_ACTIVATED_OK'], $user_infos['email']));
						$message = $GLOBALS['STR_ADMIN_UTILISATEURS_MSG_ACTIVATED_OK'] . ($annonce_active?' - ' . $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_ADS_ALSO_ACTIVATED'] : '');
					} else {
						tracert_history_admin(intval(vn($_GET['id'])), 'EDIT_PROFIL', sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_DEACTIVATED_OK'], $user_infos['email']));
						$message = $GLOBALS['STR_ADMIN_UTILISATEURS_MSG_DEACTIVATED_OK'] . ($annonce_active?' - ' . $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_ADS_ALSO_DEACTIVATED'] : '');
					}
					$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($message, $user_infos['email'])))->fetch();
				}
				if (!empty($GLOBALS['site_parameters']['validating_registration_by_admin'])) {
					// Vérification de l'état actuel de l'utilisateur. On envoie un email lors d'un changement d'état uniquement.
					if($user_infos['etat'] != $etat && $etat == 1) {
						// activation de l'utilisateur, on avertit l'utilisateur de l'activation de son compte
						send_email($user_infos['email'],'','','validating_registration_by_admin');
					}
				}
				query('UPDATE peel_utilisateurs
					SET etat="' . intval($etat) . '"
					WHERE id_utilisateur="' . intval($_GET['id']) . '" AND ' . get_filter_site_cond('utilisateurs', null, true));
				$annonce_active = false;
				if (check_if_module_active('annonces')) {
					update_state_ads($_GET['id'], $etat);
					$annonce_active = true;
				}
			} else {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
			}
		}
		$output .= afficher_liste_utilisateurs($priv, $cle);
		break;

	case "registration_refused" :
		// Refus de la candidature pour le profil, on envoie un email
		$qcomments = query("SELECT comments
			FROM peel_admins_comments
			WHERE id_user = '" . intval($id_utilisateur) . "'");
		if (($comments = fetch_assoc($qcomments)) && !empty($_GET['priv'])) {
			if(a_priv('admin*', false, false, $id_utilisateur) && !a_priv('admin', false, true)) {
				// L'utilisateur qu'on veut modifier n'est pas un administrateur est un administrateur et, l'utilisateur loggé n'a pas le droit de le modifier car il n'est pas admin générale(par exemple admin_util qui n'est pas suffisant comme droit)
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
			} else {
				// Envoi d'un email de refus du statut
				$custom_template_tags['COMMENTS'] = $comments['comments']; 
				$all_priv_infos = get_priv_options(null, 'array');
				$custom_template_tags['PRIV'] = $all_priv_infos[$_GET['priv']]['name']; 
				$user_infos = get_user_information($id_utilisateur);
				send_email($user_infos['email'], '', '', 'registration_refused', $custom_template_tags);
				
				// refus du statut
				$sql = "UPDATE peel_utilisateurs
					SET priv=REPLACE(priv, '" . real_escape_string($_GET['priv']) . "', '" . real_escape_string(str_replace('stop_', 'refused_', $_GET['priv'])) . "')
					WHERE id_utilisateur = '" . intval($id_utilisateur) . "'";
				query($sql);

				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_EMAIL_SENT']))->fetch();
			}
		} else {
			// Le commentaire de l'administrateur est obligatoire. On affiche le formulaire à l'emplacement du champ à remplir
			redirect_and_die($GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur='.$id_utilisateur.'#comments');
		}
		break;

	case "registration_validated" :
		// validation de la candidature pour le profil, on envoie un email
		if (!empty($_GET['priv'])) {
			if(a_priv('admin*', false, false, $id_utilisateur) && !a_priv('admin', false, true)) {
				// L'utilisateur qu'on veut modifier n'est pas un administrateur est un administrateur et, l'utilisateur loggé n'a pas le droit de le modifier car il n'est pas admin générale(par exemple admin_util qui n'est pas suffisant comme droit)
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
			} else {
				$user_infos = get_user_information($id_utilisateur);
				// Envoi d'un email de validation du statut

				if(!empty($product_reference_inscription)) {

				} else {
					if ($_GET['priv'] == 'stop_porteur') {
						$profil_subscription = 'util_porteur';
					} elseif ($_GET['priv'] == 'stop_contributeur') {
						$profil_subscription = 'util_contributeur';
					}elseif ($_GET['priv'] == 'stop_lender') {
						$profil_subscription = 'util_lender';
					}
					$sql = "SELECT priv
						FROM peel_utilisateurs
						WHERE id_utilisateur = " . intval($_GET['id_utilisateur']);
					$query = query($sql);
					$result = fetch_assoc($query);
					$new_priv[] = $profil_subscription;
					$current_priv_array = explode('+', $result['priv']);
					foreach($current_priv_array as $this_priv) {
						// On garde les autres droits déjà existants
						if(!in_array($this_priv, $new_priv) && $_GET['priv']!=$this_priv) {
							$new_priv[] = $this_priv;
						}
					}
					$priv_list = implode('+', $new_priv);
					$sql = "UPDATE peel_utilisateurs
						SET priv='" . real_escape_string($priv_list) . "'
						WHERE id_utilisateur = '" . intval($_GET['id_utilisateur']) . "'";
					query($sql);
					$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_REQUEST_OK']))->fetch();
				}
			}
		}
		break;
		
	case "ajout" :
		$output .= afficher_formulaire_ajout_utilisateur();
		break;

	case "modif" :
		if(!a_priv('admin*', false, false, $id_utilisateur) || (a_priv('admin', false, true) || (!a_priv('admin', false, false, $id_utilisateur) && (a_priv('admin_finance', false, true) || a_priv('admin_operations', false, true) || a_priv('admin_productsline', false, true))))) {
			// L'utilisateur qu'on veut modifier n'est pas un administrateur, ou alors l'utilisateur loggué a pas le droit de le modifier
			$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
		}
		break;

	case "suppr" :
		if(!a_priv('admin*', false, false, $id_utilisateur) || (a_priv('admin', false, true) || (!a_priv('admin', false, false, $id_utilisateur) && (a_priv('admin_finance', false, true) || a_priv('admin_operations', false, true) || a_priv('admin_productsline', false, true))))) {
			// L'utilisateur qu'on veut modifier n'est pas un administrateur, ou alors l'utilisateur loggué a pas le droit de le modifier
			$user_infos = get_user_information($id_utilisateur);
			if(!empty($user_infos)) {
				$output .= efface_utilisateur($id_utilisateur);
				$annonce_active = false;
				if (check_if_module_active('annonces')) {
					$output .= delete_user_ads($id_utilisateur);
					$annonce_active = true;
				}
				$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_DELETED_OK'] . ($annonce_active?' - ' . $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_ADS_ALSO_DEACTIVATED'] : ''), $user_infos['email'])))->fetch();
			}
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
		}
		$output .= afficher_liste_utilisateurs($priv, $cle);
		break;

	case "supprlogo" :
		if(!a_priv('admin*', false, false, $id_utilisateur) || (a_priv('admin', false, true) || (!a_priv('admin', false, false, $id_utilisateur) && (a_priv('admin_finance', false, true) || a_priv('admin_operations', false, true) || a_priv('admin_productsline', false, true))))) {
			// L'utilisateur qu'on veut modifier n'est pas un administrateur, ou alors l'utilisateur loggué a pas le droit de le modifier
			$output .= supprime_logo($id_utilisateur);
			$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
		}
		break;

	case "supprfile" :
		if(!a_priv('admin*', false, false, $id_utilisateur) || (a_priv('admin', false, true) || (!a_priv('admin', false, false, $id_utilisateur) && (a_priv('admin_finance', false, true) || a_priv('admin_operations', false, true) || a_priv('admin_productsline', false, true))))) {
			// L'utilisateur qu'on veut modifier n'est pas un administrateur, ou alors l'utilisateur loggué a pas le droit de le modifier
			$output .= delete_uploaded_file_and_thumbs($_GET['bic']);
			$sql = "UPDATE peel_utilisateurs
				SET bic=''
				WHERE id_utilisateur = " . intval($id_utilisateur);
			query($sql);
			$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
		}
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
			WHERE email = '" . nohtml_real_escape_string($frm['email']) . "' AND " . get_filter_site_cond('utilisateurs', null, true) . "")) > 0)) {
			// Test d'unicité de l'email. On ne veut pas plusieurs comptes avec le même email en base de données
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_STILL']);
		}
		if (empty($GLOBALS['site_parameters']['pseudo_is_not_used']) && (num_rows(query("SELECT 1
			FROM peel_utilisateurs
			WHERE pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "' AND " . get_filter_site_cond('utilisateurs', null, true) . "")) > 0)) {
			$form_error_object->add('pseudo', $GLOBALS['STR_ERR_NICKNAME_STILL']);
		}
		if (!$form_error_object->count()) {
			$frm['logo'] = upload('logo', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['logo']));
			$frm['document'] = upload('document', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['document']));
			$frm['mot_passe'] = (!empty($frm['mot_passe']))?$frm['mot_passe']:MDP();
			$new_user_result = insere_utilisateur($frm, false, false, false);
			if(is_numeric($new_user_result)) {
				// Nouvel utilisateur créé
				$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_CREATED_OK'], vb($frm['email']))))->fetch();
				// Envoi de l'e-mail de création de l'utilisateur avec le mot de passe
				if (isset($frm['notify'])) {
					send_mail_for_account_creation(vb($frm['email']), vb($frm['mot_passe']), vb($frm['priv']));
				}
			}
			$output .= afficher_liste_utilisateurs($priv, $cle);
		} else {
			if ($form_error_object->has_error('token')) {
				$output .=  $form_error_object->text('token');
			} elseif ($form_error_object->has_error('email')) {
				$output .=  $form_error_object->text('email');
			}
			if (empty($GLOBALS['site_parameters']['pseudo_is_not_used']) && $form_error_object->has_error('pseudo')) {
				$output .= $form_error_object->text('pseudo');
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
		} elseif ((num_rows(query("SELECT 1
			FROM peel_utilisateurs
			WHERE email = '" . nohtml_real_escape_string($frm['email']) . "' AND " . get_filter_site_cond('utilisateurs', null, true) . " AND id_utilisateur!='" . intval($frm['id_utilisateur']) . "'")) > 0)) {
			// Test d'unicité de l'email. On ne veut pas plusieurs comptes avec le même email en base de données
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_STILL']);
		}
		if(!empty($frm['id_offre'])) {
			foreach($frm['id_offre'] as $id_offre){
				$frm_offre = array('id_offre' => $id_offre, 'id_utilisateur' => $frm['id_utilisateur']);
				insere_assoc_offre($frm_offre);
			}
		}
		if (empty($GLOBALS['site_parameters']['pseudo_is_not_used']) && !empty($frm['pseudo']) && (num_rows(query("SELECT 1
			FROM peel_utilisateurs
			WHERE id_utilisateur!='" . intval($frm['id_utilisateur']) . "' AND pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "' AND " . get_filter_site_cond('utilisateurs', null, true) . "")) > 0)) {
			$form_error_object->add('pseudo', $GLOBALS['STR_ERR_NICKNAME_STILL']);
		}
		// Vérification de l'état actuel de l'utilisateur. On envoie un email lors d'un changement d'état uniquement.
		$user_infos = get_user_information($frm['id_utilisateur']);
		if (!empty($user_infos)) {
			if($user_infos['etat'] != $frm['etat'] && $frm['etat'] == 1) {
				tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'EDIT_PROFIL', sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_ACTIVATED_OK'], $user_infos['email']));
				if(!empty($GLOBALS['site_parameters']['validating_registration_by_admin'])) {
					// activation de l'utilisateur, on avertit l'utilisateur de l'activation de son compte
					send_email($user_infos['email'],'','','validating_registration_by_admin');
				}
			} elseif ($user_infos['etat'] != $frm['etat'] && $frm['etat'] == 0) {
				tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'EDIT_PROFIL', sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_MSG_DEACTIVATED_OK'], $user_infos['email']));
			}

		}

		if (!$form_error_object->count()) {
			$frm['logo'] = upload('logo', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['logo']));
			$frm['document'] = upload('document', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['document']));
			$frm['residence_abroad_document'] = upload('residence_abroad_document', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['residence_abroad_document']));
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
			if (empty($GLOBALS['site_parameters']['pseudo_is_not_used']) && $form_error_object->has_error('pseudo')) {
				$output .= $form_error_object->text('pseudo');
			}
			$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		}
		break;

	case "liste" :
		$output .= afficher_liste_utilisateurs($priv, $cle);
		break;

	case "cheque" :
		if (check_if_module_active('gift_check')) {
			// L'administrateur a validé l'envoi d'un chèque cadeau à l'utilisateur
			cree_cheque_cadeau_client(vn($id_utilisateur), "CHQ", $GLOBALS['site_parameters']['avoir'], 2);
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_GIFT_CHECK_SENT']))->fetch();
		}
		$output .= afficher_liste_utilisateurs($priv, $cle);
		break;

	case "init_mdp" :
		if(!a_priv('admin*', false, false, $id_utilisateur) || (a_priv('admin', false, true) || (!a_priv('admin', false, false, $id_utilisateur) && (a_priv('admin_finance', false, true) || a_priv('admin_operations', false, true) || a_priv('admin_productsline', false, true))))) {
			// L'utilisateur qu'on veut modifier n'est pas un administrateur, ou alors l'utilisateur loggué a pas le droit de le modifier. initialise_mot_passe retourne un boolean
			initialise_mot_passe($_REQUEST['email']);
			$qid = query("SELECT email
				FROM peel_utilisateurs
				WHERE email = '" . nohtml_real_escape_string($_REQUEST['email']) . "' AND " . get_filter_site_cond('utilisateurs', null, true) . "");
			if ($user = fetch_object($qid)) {
				$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_NEW_PASSWORD_SENT'], vb($user->email))))->fetch();
			}
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
		}
		$output .= afficher_liste_utilisateurs($priv, $cle);

		break;

	case "enligne_liste_annonce" :
	case "update_list_annonce" :
		if (check_if_module_active('annonces')) {
			$output .=  annonce_manipulation($form_error_object, 'users');
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "phone_call" :
		if ((!empty($_POST['phone_emitted_submit']) || !empty($_GET['phone_emitted_submit']))) {
			tracert_history_admin(intval($_REQUEST['id_utilisateur']), 'PHONE_EMITTED', 'NOT_ENDED_CALL', nohtml_real_escape_string((!empty($_POST['form_phone_comment'])?$_POST['form_phone_comment']:'')));
			if (!empty($_GET['callee']) && check_if_module_active('phone_cti')) {
				$query = query('SELECT telephone, pays
					FROM peel_utilisateurs
					WHERE id_utilisateur="' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '" AND telephone!="" AND ' . get_filter_site_cond('utilisateurs', null, true) . '');
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
				WHERE id_user="' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '" AND id_membre="' . intval($_REQUEST['id_utilisateur']) . '" AND ((action = "PHONE_EMITTED") OR (action = "PHONE_RECEIVED")) AND data="NOT_ENDED_CALL" AND ' . get_filter_site_cond('admins_actions', null, true) . '');
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
		// Ajoute un crédit gold
		if (!empty($_POST['id_utilisateur']) && !empty($_POST['add_gold_ad']) && check_if_module_active('annonces')) {
			$output .= add_credit_gold_user ($_POST['id_utilisateur'], $_POST['add_gold_ad']);
			tracert_history_admin($_POST['id_utilisateur'], 'CREATE_ORDER', 'Ajout de credit gold');
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "suppr_credit_gold" :
		// Supprime le crédit gold
		if (!empty($_GET['id_utilisateur']) && !empty($_GET['id_gold']) && check_if_module_active('annonces')) {
			$output .= suppr_credit_gold_user ($_GET['id_utilisateur'], $_GET['id_gold']);
			tracert_history_admin($_GET['id_utilisateur'], 'SUP_ORDER', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_UTILISATEURS_GOLD_CREDIT_DELETED'] . ' ' . intval(vn($_GET['id_gold'])));
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "maj_abo_platinum":
		// Mise à jour de l'abonnement platinium si le module abonnement existe
		if (!empty($_POST['id_utilisateur']) && check_if_module_active('abonnement')) {
			$output .= maj_abonnement_admin($_POST);
			tracert_history_admin($_POST['id_utilisateur'], 'EDIT_ORDER', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MSG_PLATINUM_UPDATED_OK']);
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "maj_abo_diamond":
		// Mise à jour de l'abonnement diamond si le module abonnement existe
		if (!empty($_POST['id_utilisateur']) && check_if_module_active('abonnement')) {
			$output .= maj_abonnement_admin($_POST);
			tracert_history_admin($_POST['id_utilisateur'], 'EDIT_ORDER', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MSG_DIAMOND_UPDATED_OK']);
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "convert_abo":
		// Conversion d'un abonnement en un autre si le module abonnement existe
		if (!empty($_POST['id_utilisateur']) && check_if_module_active('abonnement')) {
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
		if (!empty($_POST['form_edit_contact_user_id']) && check_if_module_active('commerciale')) {
			$output .= create_or_update_contact_planified($_POST);
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "update_contact_planified":
		// Mise à jour d'une planification de contact
		if (!empty($_POST['form_edit_contact_planified_id']) && check_if_module_active('commerciale')) {
			$output .= create_or_update_contact_planified($_POST);
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
		
	case "suppr_contact_planified":
		// Supression d'une planification de contact
		if (!empty($_POST['form_delete_admins_contacts']) && check_if_module_active('commerciale')) {
			foreach($_POST['form_delete_admins_contacts'] as $form_edit_contact_planified_id) {
				$output .= delete_contact_planified($form_edit_contact_planified_id);
			}
		}
		$output .= affiche_formulaire_modif_utilisateur($id_utilisateur);
		break;
	case "search" :
		// recupération des informations client du critère de recherche sous forme de tableau, afin de les envoyer en paramètre dans la fonction tracert.
		$user_infos = array();
		if (!empty($_GET['client_info'])) {
			$user_infos [] = StringMb::ucfirst($GLOBALS["STR_FIRST_NAME"]) . ' / ' . $GLOBALS["STR_LAST_NAME"] . ' : ' . $_GET['client_info'];
		}
		if (!empty($_GET['email'])) {
			$user_infos [] = StringMb::ucfirst($GLOBALS["STR_EMAIL"]) . ' : ' . $_GET['email'];
		}
		if (!empty($_GET['pays'])) {
			$user_infos [] = StringMb::ucfirst($GLOBALS["STR_COUNTRY"]) . ' : ' . $_GET['pays'];
		}
		if (!empty($_GET['societe'])) {
			$user_infos [] = StringMb::ucfirst($GLOBALS["STR_COMPANY"]) . ' : ' . $_GET['societe'];
		}
		if (!empty($_GET['origin'])) {
			$user_infos [] = StringMb::ucfirst($GLOBALS["STR_ORIGIN"]) . ' : ' . $_GET['origin'];
		}
		if (!empty($_GET['tel'])) {
			$user_infos [] = StringMb::ucfirst($GLOBALS["STR_SHORT_TEL"]) . ' : ' . $_GET['tel'];
		}
		if (!empty($_GET['fax'])) {
			$user_infos [] = StringMb::ucfirst($GLOBALS["STR_SHORT_FAX"]) . ' : ' . $_GET['fax'];
		}
		if (!empty($_GET['date_insert_to'])) {
			$user_infos [] = 'Date inscription : ' . nohtml_real_escape_string(date('Y-m-d', strtotime(str_replace('/', '-', $_GET['date_insert_to']))));
		}
		if (!empty($_GET['seg_who'])) {
			$user_infos [] = 'Seg_who : ' . $_GET['seg_who'];
		}
		if (!empty($_GET['seg_buy'])) {
			$user_infos [] = 'seg_buy : ' . $_GET['seg_buy'];
		}
		if (!empty($_GET['seg_want'])) {
			$user_infos [] = 'seg_want : ' . $_GET['seg_want'];
		}
		if (!empty($_GET['seg_think'])) {
			$user_infos [] = 'seg_think : ' . $_GET['seg_think'];
		}
		if (!empty($_GET['seg_followed'])) {
			$user_infos [] = 'seg_followed : ' . $_GET['seg_followed'];
		}
		if (!empty($_GET['type'])) {
			$user_infos [] = 'type : ' . $_GET['type'];
		}
		if (!empty($_GET['control_plus'])) {
			$user_infos [] = 'control_plus : ' . $_GET['control_plus'];
		}
		if (!empty($_GET['fonction'])) {
			$user_infos [] = 'fonction : ' . $_GET['fonction'];
		}
		if (!empty($_GET['site_on'])) {
			$user_infos [] = 'site_on : ' . $_GET['site_on'];
		}
		if (!empty($_GET['id_cat'])) {
			$user_infos [] = 'id_cat : ' . $_GET['id_cat'];
		}
		if (!empty($_GET['activity'])) {
			$user_infos [] = 'activity : ' . $_GET['activity'];
		}
		tracert_history_admin(0, 'SEARCH_USER', implode(' | ', $user_infos));
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
						WHERE id_user="' . $_SESSION['session_utilisateur']['id_utilisateur'] . '" AND id_membre="' . $_POST['id_utilisateur'] . '" AND ((action = "PHONE_EMITTED") OR (action = "PHONE_RECEIVED")) AND data="NOT_ENDED_CALL" AND ' . get_filter_site_cond('admins_actions', null, true) . '
						');
				$output .= affiche_formulaire_modif_utilisateur($_POST['id_utilisateur']);
			}
		}
		break;
	case 'send_email_alert':
		call_module_hook('user_send_email_alert_admin', array('id_utilisateur'=>$_GET['id_utilisateur']));
		$output .= affiche_formulaire_modif_utilisateur($_GET['id_utilisateur']);
		break;
	default :
		if (!empty($_GET['commercial_contact_id']) && check_if_module_active('commerciale')) {
			$output .= afficher_liste_utilisateurs($priv, $cle, null, 'date_insert', $_GET['commercial_contact_id']);
		} else {
			$output .= afficher_liste_utilisateurs($priv, $cle);
			if (check_if_module_active('chart', 'open-flash-chart.php') && empty($_GET['page'])) {
				if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
					include($GLOBALS['dirroot'] . '/modules/chart/flot.php');
				} else {
					include($GLOBALS['dirroot'] . '/modules/chart/open_flash_chart_object.php');
				}

				if(empty($_SESSION['session_admin_multisite']) || $_SESSION['session_admin_multisite'] != $GLOBALS['site_id']) {
					$this_wwwroot =  get_site_wwwroot($_SESSION['session_admin_multisite'], $_SESSION['session_langue']);
				} else {
					$this_wwwroot =  $GLOBALS['wwwroot'];
				}
				if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
					$output .=  '<div class="center">' . get_flot_chart('100%', 300, $GLOBALS['administrer_url'] . '/chart-data.php?type=users-count&date1=' . date('Y-m-d', time()-3600 * 24 * 90) . '&date2=' . date('Y-m-d', time()) . '&width=1000', 'line', $this_wwwroot . '/modules/chart/', 'date_format_veryshort') . '</div>';
				} else {
					$output .=  '<div class="center">' . open_flash_chart_object_str('100%', 300, $GLOBALS['administrer_url'] . '/chart-data.php?type=users-count&date1=' . date('Y-m-d', time()-3600 * 24 * 90) . '&date2=' . date('Y-m-d', time()) . '&width=1000', true, $this_wwwroot . '/modules/chart/') . '</div>';
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
	$frm = get_user_information($id_utilisateur, true, true);
	if (!empty($frm)) {
		$qcomments = query("SELECT comments
			FROM peel_admins_comments
			WHERE id_user = '" . intval($id_utilisateur) . "'");
		$comments = fetch_assoc($qcomments);
		// Recupération de la date de la dernière connexion de l'utilisateur
		$qlast_date = query("SELECT date, user_ip
			FROM peel_utilisateur_connexions
			WHERE user_id = '" . intval($id_utilisateur) . "' AND " . get_filter_site_cond('utilisateur_connexions', null, true) . "
			ORDER BY id DESC
			LIMIT 1");
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
	$frm = call_module_hook('user_edit_form_data', $frm, 'array', true);

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_utilisateur_form.tpl');
	
	$tpl->assign('hook_actions', call_module_hook('user_edit_actions', array('id_utilisateur' => vb($frm['id_utilisateur'])), 'string'));
	$tpl->assign('action', get_current_url(true));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vn($frm['id_utilisateur']))));
	$tpl->assign('mode', vb($frm['nouveau_mode']));
	$tpl->assign('id_utilisateur', vb($frm['id_utilisateur']));
	$tpl->assign('site_id_select_options', get_site_id_select_options(isset($frm['site_id'])?$frm['site_id']:null));
	$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
	$tpl->assign('remise_valeur', vb($frm['remise_valeur']));
	$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
	$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);
	// user_site_id permet de désactiver le champ select pour éviter les erreur d'administration. Le select est désactivé avec disabled="disabled", la valeur de site_id est transmis via un champ hidden.
	$tpl->assign('disable_user_siteweb',isset($frm['site_id']) && get_site_id_sql_set_value($frm['site_id']) === 0);
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
	if(check_if_module_active('bounces')) {
		include($GLOBALS['dirroot'] . '/modules/bounces/rfc1893.error.codes.php');
		$temp = explode('.', vb($frm['email_bounce']));
		if(!empty($temp[0]) && !empty($status_code_classes[$temp[0]])) {
			$email_infos_array[] = vb($status_code_classes[$temp[0]]['title']);
		}
		$email_infos_array[] = vb($frm['email_bounce']); 
		if(isset($temp[1]) && isset($temp[2]) && !empty($status_code_subclasses[$temp[1].'.'.$temp[2]])) {
			$email_infos_array[] = vb($status_code_subclasses[$temp[1].'.'.$temp[2]]['title'], $frm['email_bounce']);
			$email_infos_array[] = vb($status_code_subclasses[$temp[1].'.'.$temp[2]]['descr'], $frm['email_bounce']);
		}
		$email_infos = implode(' - ', $email_infos_array);
	} else {
		$email_infos = vb($frm['email_bounce']);
	}
	if(!empty($frm['email']) && !EmailOK($frm['email'], vb($frm['email_bounce']))) {
		if(empty($email_infos)) {
			$email_infos = "Emails rejected";
		}
		$email_infos = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $email_infos))->fetch();
	}
	$tpl->assign('email_infos', $email_infos);
	$tpl->assign('pseudo', (!a_priv('demo')?vb($frm['pseudo']):'private [demo]'));

	$tpl->assign('priv_options', get_priv_options(vb($frm['priv'])));
	$tpl->assign('commercial_contact_id', vb($frm['commercial_contact_id']));
	if (!empty($frm['priv']) && function_exists('specific_profile_admin_form')) {
		$tpl->assign('specific_profile_results', specific_profile_admin_form($frm));
	}

	$tpl_util_options = array();
	$q = query('SELECT id_utilisateur, pseudo, email, etat, commercial_contact_id
		FROM peel_utilisateurs
		WHERE CONCAT("+",priv,"+") LIKE "%+admin%" AND pseudo!="" AND ' . get_filter_site_cond('utilisateurs', null, true) . '');
	while ($result = fetch_assoc($q)) {
		$tpl_util_options[] = array('value' => $result['id_utilisateur'],
			'issel' => vb($frm['commercial_contact_id']) == $result['id_utilisateur'],
			'name' => (!a_priv('demo')?(!empty($result['pseudo'])?$result['pseudo']:$result['email']):'private [demo]')
			);
	}
	$tpl->assign('util_options', $tpl_util_options);
	if (check_if_module_active('sauvegarde_recherche')) {
		$tpl->assign('user_alerts', display_ads_search_list($frm['id_utilisateur'], false));
	}
	$tpl->assign('is_annonce_module_active', check_if_module_active('annonces'));
	$tpl->assign('is_modif_mode', vb($_REQUEST['mode']) == "modif");
	$tpl->assign('mot_passe', vb($frm['mot_passe']));
	$tpl->assign('control_plus', vb($frm['control_plus']));
	$tpl->assign('note_administrateur', vb($frm['note_administrateur']));
	$tpl->assign('activity', vb($frm['activity']));
	if (function_exists("afficher_offres_utilisateur")) {
		$afficher_offres_utilisateur = afficher_offres_utilisateur($frm['id_utilisateur'], true);
		$tpl->assign('display_offres', $afficher_offres_utilisateur['output']);
		$tpl->assign('nb_offres',  $afficher_offres_utilisateur['nb_offres']);
	}
	$tpl->assign('is_groups_module_active', check_if_module_active('groups'));
	if (check_if_module_active('groups')) {
		$resGroupe = query("SELECT *
			FROM peel_groupes
			WHERE " . get_filter_site_cond('groupes') . "
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
		$tpl->assign('STR_ADMIN_GROUP', $GLOBALS['STR_ADMIN_GROUP']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED']);
	}

	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
	$tpl->assign('telephone_calllink', (check_if_module_active('phone_cti') && !empty($frm['id_utilisateur']) ? getCallLink(vb($frm['id_utilisateur']), vb($frm['telephone']), vb($frm['nom_famille']), vb($frm['pays'])) : ''));
	$tpl->assign('portable_calllink', (check_if_module_active('phone_cti') && !empty($frm['id_utilisateur']) ? getCallLink(vb($frm['id_utilisateur']), vb($frm['portable']), vb($frm['nom_famille']), vb($frm['pays'])) : ''));
	$tpl->assign('country_select_options', get_country_select_options(null, vb($frm['pays']), 'id', true));

	$tpl->assign('specific_fields', get_specific_field_infos($frm, null, 'user'));
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
	$tpl->assign('is_module_vacances_active', check_if_module_active('vacances'));
	if (check_if_module_active('vacances')) {
		$tpl->assign('vacances_type', get_vacances_type());
	} else {
		$tpl->assign('vacances_type', '');
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
			'issel' =>$issel,
			'name' => $GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i]
			);
		$i++;
	}
	$tpl->assign('origin_infos', array('options' => $tpl_origin_options,
			'is_origin_other_activated' => in_array(vb($frm['origin']), $GLOBALS['origin_other_ids']),
			'origin_other_ids_for_javascript' => 'new Array('.implode(',', $GLOBALS['origin_other_ids']).')',
			'origin_other' => vb($frm['origin_other']),
			'error_text' => '',
			'STR_CHOOSE' => $GLOBALS['STR_CHOOSE'],
			'user_origin_multiple' => vb($GLOBALS['site_parameters']['user_origin_multiple'])
		));

	$tpl->assign('STR_LANGUAGE_FOR_AUTOMATIC_EMAILS', $GLOBALS['STR_LANGUAGE_FOR_AUTOMATIC_EMAILS']);

	$tpl_langues = array();
	if (empty($frm['lang'])) {
		$frm['lang'] = $_SESSION['session_langue'];
	}
	$resLng = query("SELECT *, nom_" . $_SESSION['session_langue'] . " AS nom_lang
		FROM peel_langues
		WHERE (etat='1'" . (!empty($_GET['langue']) ? " OR lang='" . word_real_escape_string($_GET['langue']) . "'" : '') . ") AND " . get_filter_site_cond('langues') . "
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
	
	$tpl->assign('logo_src', thumbs(vb($frm['logo']), 200, 200, 'fit', null, null, true, true));
	$tpl->assign('logo_del_href', get_current_url(false) . '?mode=supprlogo&id_utilisateur=' . vn($frm['id_utilisateur']));
	
	$tpl->assign('is_clients_module_active', check_if_module_active('clients'));
	$tpl->assign('issel_on_client_module', !isset($frm['on_client_module']) || !empty($frm['on_client_module']));
	$tpl->assign('is_photodesk_module_active', check_if_module_active('photodesk'));
	$tpl->assign('issel_on_photodesk_module', !isset($frm['on_photodesk']) || !empty($frm['on_photodesk']));
	$tpl->assign('gift_check_link', check_if_module_active('gift_check') && !empty($frm['id_utilisateur']));

	$tpl->assign('order_history_for_user_disable', !empty($GLOBALS['site_parameters']['order_history_for_user_disable']));
	$tpl->assign('issel_access_history', !isset($frm['access_history']) || !empty($frm['access_history']));
	$tpl->assign('issel_newsletter', !isset($frm['newsletter']) || !empty($frm['newsletter']));
	$tpl->assign('issel_commercial', !isset($frm['commercial']) || $frm['commercial']);

	$tpl->assign('is_module_gift_checks_active', check_if_module_active('gift_check'));
	$tpl->assign('mail_src', $GLOBALS['wwwroot_in_admin'] . '/images/mail.gif');
	if (check_if_module_active('gift_check') && !empty($frm['id_utilisateur'])) {
		$tpl->assign('gift_checks_href', get_current_url(false) . '?mode=cheque&id_utilisateur=' . $frm['id_utilisateur']);
		$tpl->assign('gift_checks_prix', fprix($GLOBALS['site_parameters']['avoir'], true, $GLOBALS['site_parameters']['code'], false));
	}
	if (check_if_module_active('telechargement')) {
		$tpl->assign('download_files', affiche_liste_telechargement($frm['id_utilisateur']));
	}
	$tpl->assign('is_annonce_module_active', check_if_module_active('annonces'));
	$tpl->assign('user_admin_note_edit_forbidden', !empty($GLOBALS['site_parameters']['user_admin_note_edit_forbidden']));
	$tpl->assign('add_b2b_form_inputs', !empty($GLOBALS['site_parameters']['add_b2b_form_inputs']));
	$tpl->assign('fonction_options', get_user_job_options(vb($frm['fonction'])));
	$tpl->assign('type', vb($frm['type']));
	$tpl->assign('client_note', intval(getClientNote($frm)));
	$tpl->assign('seg_who', formSelect('seg_who', tab_who(), vb($frm['seg_who'])));
	$tpl->assign('seg_buy', formSelect('seg_buy', tab_buy(), vb($frm['seg_buy'])));
	$tpl->assign('seg_want', formSelect('seg_want', tab_want(), vb($frm['seg_want'])));
	$tpl->assign('seg_think', formSelect('seg_think', tab_think(), vb($frm['seg_think'])));
	$tpl->assign('seg_followed', formSelect('seg_followed', tab_followed(), vb($frm['seg_followed'])));

	$tpl->assign('is_vitrine_module_active', check_if_module_active('vitrine'));
	if (check_if_module_active('vitrine') && !empty($frm['id_utilisateur'])) {
		$tpl->assign('vitrine_admin', affiche_vitrine_admin($frm['id_utilisateur']));
	}

	$tpl->assign('is_abonnement_module_active', check_if_module_active('abonnement'));
	if (check_if_module_active('abonnement') && !empty($frm['id_utilisateur'])) {
		$tpl->assign('abonnement_admin', affiche_abonnement_admin($frm['id_utilisateur'], true));
		$tpl->assign('STR_MODULE_ABONNEMENT_ADMIN_MANAGE_SUBSCRIPTIONS', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MANAGE_SUBSCRIPTIONS']);
	}

	if (check_if_module_active('annonces') && !empty($frm['pseudo'])) {
		$recherche['login'] = $frm['pseudo'];
		$tpl->assign('liste_annonces_admin', affiche_liste_annonces_admin($recherche, false, $frm['id_utilisateur']));
		if(empty($GLOBALS['site_parameters']['ad_gold_ad_disable'])) {
			$tpl->assign('add_credit_gold_user', affiche_add_credit_gold_user($frm['id_utilisateur'], true));
		}
	}

	$tpl->assign('is_commerciale_module_active', check_if_module_active('commerciale'));
	if (check_if_module_active('commerciale') && !empty($frm['id_utilisateur'])) {
		$tpl->assign('form_contact_user', affiche_form_contact_user($frm['id_utilisateur'], true));
	}

	if (vb($_REQUEST['mode']) != "ajout") { // si c'est l'édition
		$tpl->assign('phone_event', affiche_phone_event($frm['id_utilisateur']));
	}

	$tpl->assign('is_webmail_module_active', check_if_module_active('webmail'));
	if (!empty($frm['id_utilisateur'])) {
		$tpl->assign('more_infos_html', call_module_hook('admin_user_edit_more_infos_html', array('id_utilisateur' => $frm['id_utilisateur']), 'string'));
		$tpl->assign('more_infos_html_top_list_link', call_module_hook('admin_user_edit_more_infos_html_top_list_link', array('id_utilisateur' => $frm['id_utilisateur']), 'string'));
		
	}
	if (!empty($frm['user_ip'])) {
		// Insertion du module de géoip permettant de définir en fonction de la dernière ip le lieu où s'est connecté la personne dernièrement
		if (check_if_module_active('geoip')) {
			if (!class_exists('geoIP')) {
				include($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php');
			}
			$geoIP = new geoIP();
			$this_site_country = $geoIP->geoIPCountryIDByAddr($frm['user_ip']);
			$geoIP->geoIPClose();
		
			$query = query("SELECT pays_" . $_SESSION['session_langue'] . "
				FROM peel_pays
				WHERE id='" . intval($this_site_country) . "' AND " .  get_filter_site_cond('pays') . "");
			$result = fetch_assoc($query);
			$country_name = vb($result['pays_' . $_SESSION['session_langue']]);
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
	if (!empty($GLOBALS['site_parameters']['user_bic_is_file']) || is_file($GLOBALS['uploaddir'].'/'.$frm['bic'])) {
		$tpl->assign('bic', get_uploaded_file_infos('bic', vb($frm['bic']), get_current_url(false) . '?id_utilisateur=' . vb($frm['id_utilisateur']) . '&mode=supprfile&bic='.$frm['bic']));
		$tpl->assign('bic_file', true);
	} else {
		$tpl->assign('bic_file', false);
		$tpl->assign('bic', vb($frm['bic']));
	}
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
	$tpl->assign('pseudo_is_not_used', !empty($GLOBALS['site_parameters']['pseudo_is_not_used']));
	if (check_if_module_active('annonces')) {
		if (vb($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) == 'checkbox') {
			$tpl->assign('id_categories', get_ad_select_options(null, vb($frm['id_categories']), 'id', false, false, 'checkbox', 'id_categories'));	
		} elseif (vb($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) == 'select') {
			$tpl->assign('id_cat_1', get_ad_select_options(null, vb($frm['id_cat_1']), 'id'));
			$tpl->assign('id_cat_2', get_ad_select_options(null, vb($frm['id_cat_2']), 'id'));
			$tpl->assign('id_cat_3', get_ad_select_options(null, vb($frm['id_cat_3']), 'id'));
		}
	}
	$tpl->assign('is_devises_module_active', check_if_module_active('devises'));
	if (!empty($GLOBALS['site_parameters']['devise_force_user_choices']) && check_if_module_active('devises')) {
		// Gestion du fait de pouvoir forcer un utilisateur a utiliser une devise lorsqu'il est loggué et aucune autre
		// Si vous activez cette fonction avec création d'une variable devise_force_user_choices dans la table de configuration, 
		// alors vous devez créer un champ devise dans peel_utilisateur int(11)
		$tpl_devises_options = array();
		$res_devise = query("SELECT p.id, p.code, p.devise
			FROM peel_devises p
			WHERE etat='1' AND " . get_filter_site_cond('devises', 'p') . "");
		while ($tab_devise = fetch_assoc($res_devise)) {
			$tpl_devises_options[] = array('value' => $tab_devise['id'],
				'issel' => $tab_devise['id'] == vb($frm['devise']),
				'name' => $tab_devise['devise']
				);
		}
		$tpl->assign('STR_ALL', $GLOBALS['STR_ALL']);
		$tpl->assign('STR_DEVISE', $GLOBALS['STR_DEVISE']);
		$tpl->assign('devises_options', $tpl_devises_options);
	}
	if(!empty($GLOBALS['site_parameters']['site_country_forced_by_user']) && !empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
		$tpl->assign('site_country_select_options', get_country_select_options(null, vb($frm['site_country']), 'id', false, null, false, null, vb($GLOBALS['site_parameters']['site_country_allowed_array'], null)));
	}
	if(!empty($GLOBALS['site_parameters']['user_offers_table_enable'])) {
		$tpl->assign('STR_OFFER_NAME', vb($GLOBALS['STR_OFFER_NAME']));
		$tpl->assign('STR_SEARCH_OFFERT_STARTING_WITH', vb($GLOBALS['STR_SEARCH_OFFERT_STARTING_WITH']));
	}

	if (!empty($GLOBALS['site_parameters']['profil_enable_display_statistic']) && function_exists('get_user_stat_by_site')) {
		$output_get_user_stat_by_site = get_user_stat_by_site($frm['id_utilisateur'], true);
		$tpl->assign('get_user_stat_by_site', $output_get_user_stat_by_site);
	}
	$tpl->assign('get_address_list', get_address_list($frm['id_utilisateur'], true));
	$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
	$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
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
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
	$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
	$tpl->assign('STR_ADMIN_PRIVILEGE', $GLOBALS['STR_ADMIN_PRIVILEGE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_ACCOUNT_MANAGER', $GLOBALS['STR_ADMIN_UTILISATEURS_ACCOUNT_MANAGER']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_NO_ACCOUNT_MANAGER', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_ACCOUNT_MANAGER']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_CLIENT_CODE', $GLOBALS['STR_ADMIN_UTILISATEURS_CLIENT_CODE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_CLIENT_CODE_HELP', $GLOBALS['STR_ADMIN_UTILISATEURS_CLIENT_CODE_HELP']);
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
	$tpl->assign('form_placeholder_portable', vb($GLOBALS['site_parameters']['form_placeholder_portable']));
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
	$tpl->assign('STR_ADMIN_UTILISATEURS_ACCESS_HISTORY', $GLOBALS['STR_ADMIN_UTILISATEURS_ACCESS_HISTORY']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_ACCESS_HISTORY_CHECKBOX', $GLOBALS['STR_ADMIN_UTILISATEURS_ACCESS_HISTORY_CHECKBOX']);
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
	$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
	$tpl->assign('STR_ADMIN_ADDRESS_CLIENT', $GLOBALS['STR_ADMIN_ADDRESS_CLIENT']);
	$tpl->assign('hook_output', call_module_hook('user_admin_form_additional_part', array('frm' => $frm), 'string'));
	$output = $tpl->fetch();

	if (!empty($frm['id_utilisateur'])) {
		$columns = 8;
		if (check_if_module_active('parrainage')) {
			$columns++;
		}

		$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_utilisateur_form_isutil.tpl');
		
		$tpl2->assign('action', get_current_url(false) . '?id_utilisateur=' . intval($_REQUEST['id_utilisateur']) . '&mode=modif');
		$tpl2->assign('pseudo', (!empty($frm['pseudo'])?$frm['pseudo']:vb($frm['email'])));
		$tpl2->assign('event_comment', (!empty($_POST['event_comment']) ? $_POST['event_comment'] : ''));
		$tpl2->assign('affiche_recherche_connexion_user',  affiche_recherche_connexion_user(array('user_id' => $frm['id_utilisateur']), false));
		if (check_if_module_active('annonces')) {
			$tpl2->assign('affiche_liste_abus', affiche_liste_abus(array('annonceur'=>$frm['pseudo']), true, false));
		}
		$tpl2->assign('actions_moderations_user', affiche_actions_moderations_user($frm['id_utilisateur']));
		$tpl2->assign('columns', $columns);
		$tpl2->assign('mini_liste_commande_src', $GLOBALS['administrer_url'] . '/images/mini_liste_commande.gif');
		$tpl2->assign('is_parrainage_module_active', check_if_module_active('parrainage'));
		$tpl2->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
		$tpl2->assign('printer_src', $GLOBALS['wwwroot_in_admin'] . '/images/t_printer.gif');
		$tpl2->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
		$tpl2->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);

		$query = query("SELECT c.*, GROUP_CONCAT(ca.nom_produit SEPARATOR '<br />') AS ordered_products
			FROM peel_commandes c
			LEFT JOIN peel_commandes_articles ca ON ca.commande_id=c.id AND " . get_filter_site_cond('commandes_articles', 'ca', true) . "
			WHERE c.id_utilisateur = '" . intval($frm['id_utilisateur']) . "' AND " . get_filter_site_cond('commandes', 'c', true) . "
			GROUP BY c.id
			ORDER BY c.id DESC");
		if (num_rows($query) > 0) {
			$tpl_results = array();

			$total_ttc = $total_ht = 0;
			$i = 0;
			if(empty($_SESSION['session_admin_multisite']) || $_SESSION['session_admin_multisite'] != $GLOBALS['site_id']) {
				$this_wwwroot =  get_site_wwwroot($_SESSION['session_admin_multisite'], $_SESSION['session_langue']);
			} else {
				$this_wwwroot =  $GLOBALS['wwwroot'];
			}
			while ($order_infos = fetch_object($query)) {
				$total_ttc += $order_infos->montant;
				$total_ht += $order_infos->montant_ht;
				if (display_prices_with_taxes_in_admin()) {
					$montant_displayed = $order_infos->montant;
				} else {
					$montant_displayed = $order_infos->montant_ht;
				}
				$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
					'modif_href' => $GLOBALS['administrer_url'] . '/commander.php?mode=modif&commandeid=' . $order_infos->id,
					'print_href' => $this_wwwroot . '/factures/commande_pdf.php?mode=bdc&code_facture=' . $order_infos->code_facture,
					'drop_href' => $GLOBALS['administrer_url'] . '/commander.php?mode=suppr&id=' . $order_infos->id,
					'id' => $order_infos->order_id,
					'date' => get_formatted_date($order_infos->o_timestamp),
					'prix' => fprix($montant_displayed, true, $order_infos->devise, true, $order_infos->currency_rate),
					'recuperer_avoir_commande' => check_if_module_active('parrainage') ? fprix(recuperer_avoir_commande($order_infos->id), true, $order_infos->devise, true, $order_infos->currency_rate) : '',
					'payment_name' => get_payment_name($order_infos->paiement),
					'payment_status_name' => get_payment_status_name($order_infos->id_statut_paiement),
					'delivery_status_name' => get_delivery_status_name($order_infos->id_statut_livraison),
					'ordered_products' => StringMb::str_shorten($order_infos->ordered_products, 200)
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
		$tpl2->assign('ttc_ht', (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));
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
		if ($existing_comments['comments'] != $frm['comments']) {
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
		WHERE id_utilisateur='" . intval($id) . "' AND " . get_filter_site_cond('utilisateurs', null, true) . "";
	$res = query($sql);
	if ($logo_info = fetch_assoc($res)) {
		query("UPDATE peel_utilisateurs
			SET logo = ''
			WHERE id_utilisateur='" . intval($id) . "' AND " . get_filter_site_cond('utilisateurs', null, true) . "");

		if (!empty($logo_info) && delete_uploaded_file_and_thumbs($logo_info['logo'])) {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_LOGO_DELETED']))->fetch();
		}
	}
	return $output;
}


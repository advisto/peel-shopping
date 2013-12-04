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
// $Id: contact.php 39095 2013-12-01 20:24:10Z gboussin $
include("../configuration.inc.php");
include("../lib/fonctions/display_user_forms.php");

if (is_photodesk_module_active()) {
	include($GLOBALS['fonctionsphotodesk']);
}

$page_name = 'contact';

$frm = $_POST;
$form_error_object = new FormError();

if (!empty($_POST) && empty($_GET['prodid'])) {
	if (!empty($frm['phone'])) {
		// Formulaire de demande de rappel par téléphone
		// Non implémenté par défaut
		$frm['nom'] = $frm['phone'];
		$frm['telephone'] = $frm['phone'];
		$frm['sujet'] = $GLOBALS["STR_CALL_BACK_EMAIL"]; // Variable de langue à définir
	} else {
		// Le formulaire a été soumis, on essaie de créer un nouveau compte d'utilisateur
		if (!empty($GLOBALS['site_parameters']['contact_form_short_mode'])) {
			$form_error_object->valide_form($frm,
				array('nom' => $GLOBALS['STR_ERR_NAME'],
					'email' => $GLOBALS['STR_ERR_EMAIL'],
					'texte' => $GLOBALS['STR_ERR_MESSAGE'],
					'token' => ''));
		} else {
			$form_error_object->valide_form($frm,
				array('nom' => $GLOBALS['STR_ERR_NAME'],
					'prenom' => $GLOBALS['STR_ERR_FIRSTNAME'],
					'telephone' => $GLOBALS['STR_ERR_TEL'],
					'email' => $GLOBALS['STR_ERR_EMAIL'],
					'texte' => $GLOBALS['STR_ERR_MESSAGE'],
					'sujet' => $GLOBALS['STR_ERR_SUBJECT'],
					'token' => ''));
		}
		if (!$form_error_object->has_error('email')) {
			$frm['email'] = trim($frm['email']);
			if (!EmailOK($frm['email'])) {
				// si il y a un email on teste l'email
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
			}
		}
		if (!$form_error_object->has_error('commande_id') && vb($frm['sujet']) == $GLOBALS['STR_CONTACT_SELECT3'] && empty($frm['commande_id'])) {
			$form_error_object->add('commande_id', $GLOBALS['STR_ERR_ORDER_NUMBER']);
		}
		if (is_captcha_module_active()) {
			if (empty($frm['code'])) {
				// Pas de tentative de déchiffrement, on laisse le captcha
				$form_error_object->add('code', $GLOBALS['STR_EMPTY_FIELD']);
			} else {
				if (!check_captcha($frm['code'], $frm['code_id'])) {
					$form_error_object->add('code', $GLOBALS['STR_CODE_INVALID']);
					// Code mal déchiffré, on en donne un autre
					delete_captcha(vb($frm['code_id']));
					unset($frm['code']);
				}
			}
		}
	}
	if (!verify_token('user_contact', 120, false)) {
		// Important : évite spam de la part de robots simples qui appellent en POST la validation de formulaire
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	if (!$form_error_object->count()) {
		if (is_captcha_module_active ()) {
			// Code OK on peut effacer le code
			delete_captcha(vb($frm['code_id']));
		}
		if (empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['REQUEST_METHOD'] != "POST") {
			// Protection du formulaire contre les robots
			die();
		}
		// Limitation du nombre de messages envoyés dans une session
		if (empty($_SESSION['session_form_contact_sent'])) {
			$_SESSION['session_form_contact_sent'] = 0;
		}
		if ($_SESSION['session_form_contact_sent'] < 10) {
			insere_ticket($frm);
			$_SESSION['session_form_contact_sent']++;
			$frm['is_ok'] = true;
		}
		include($GLOBALS['repertoire_modele'] . "/haut.php");
		// Si le module webmail est activé, on insère dans la table webmail la requête user
		echo get_contact_success($frm);
		include($GLOBALS['repertoire_modele'] . "/bas.php");
	}
} elseif (!empty($_GET['prodid'])) {
	$product_object = new Product($_GET['prodid'], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
	$attribut_list = get_attribut_list_from_post_data($product_object, $_POST);
	if (!empty($_POST['critere'])) {
		// Affichage des combinaisons de couleur et taille dans un unique select
		$criteres = explode("|", $_POST['critere']);
		$couleur_id = intval(vn($criteres[0]));
		$taille_id = intval(vn($criteres[1]));
	} else {
		$couleur_id = intval(vn($_POST['couleur']));
		$taille_id = intval(vn($_POST['taille']));
	}
	// On enregistre la taille pour revenir sur la bonne valeur du select
	$_SESSION['session_taille_id'] = $taille_id;
	// On enregistre le message à afficher si la quantité demandée est trop élevée par rapport au stock disponnible
	$product_object->set_configuration($couleur_id, $taille_id, $attribut_list, is_reseller_module_active() && is_reseller());
	
	$color = $product_object->get_color();
	$size = $product_object->get_size();
	
	$frm['texte'] = $GLOBALS['STR_PRODUCT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " .$product_object->name.
		(!empty($color)?"\r\n" . $GLOBALS['STR_COLOR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $color :'' ). 
		(!empty($size)?"\r\n" . $GLOBALS['STR_SIZE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $size :'' ). 
		(!empty($product_object->configuration_attributs_list) ? "\r\n" .  str_replace('<br />', "\r\n", $product_object->configuration_attributs_description) : '');
}

define('IN_CONTACT', true);
// $form_error_object = new FormError();
include($GLOBALS['repertoire_modele'] . "/haut.php");

if (!empty($noticemsg)) {
	echo $noticemsg;
}
if(empty($_POST) && est_identifie()) {
	$frm['email'] = vb($_SESSION['session_utilisateur']['email']);
	$frm['telephone'] = vb($_SESSION['session_utilisateur']['telephone']);
	$frm['nom'] = vb($_SESSION['session_utilisateur']['nom_famille']);
	$frm['prenom'] = vb($_SESSION['session_utilisateur']['prenom']);
	$frm['societe'] = vb($_SESSION['session_utilisateur']['societe']);
	$frm['adresse'] = vb($_SESSION['session_utilisateur']['adresse']);
	$frm['ville'] = vb($_SESSION['session_utilisateur']['ville']);
	$frm['code_postal'] = vb($_SESSION['session_utilisateur']['code_postal']);
	if(isset($_SESSION['session_utilisateur']['pays'])) {
		$frm['pays'] = get_country_name($_SESSION['session_utilisateur']['pays']);
	}
}
echo get_contact_form($frm, $form_error_object);

include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
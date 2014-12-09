<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: achat_maintenant.php 43038 2014-10-29 12:10:28Z sdelaporte $
include("../configuration.inc.php");
if (empty($GLOBALS['site_parameters']['unsubscribe_order_process'])) {
	necessite_identification();
	if(!a_priv('util') && !a_priv('reve') && !a_priv('admin*') && !a_priv('demo')) {
		// gestion de droits utilisateurs non autorisés pour passer une commande
		redirect_and_die($GLOBALS['wwwroot'] . "/achat/caddie_affichage.php");
	}
}
include("../lib/fonctions/display_caddie.php");

if (is_socolissimo_module_active() && !empty($_REQUEST) && !empty($_REQUEST['PUDOFOID']) && !empty($_REQUEST['CEEMAIL']) && !empty($_REQUEST['SIGNATURE']) && !empty($_REQUEST['ORDERID'])) {
	// On veut vérifier s'il y a eu passage par la page SO de SoColissimo
	put_session_commande_from_so_page();
	$_SESSION['session_caddie']->update();
} elseif (!empty($_POST)) {
	put_session_commande($_POST);
	if (!isset($form_error_object)) {
		$form_error_object = new FormError();
	}
	$check_fields = array('nom1' => $GLOBALS['STR_ERR_NAME'],
		'prenom1' => $GLOBALS['STR_ERR_FIRSTNAME'],
		'contact1' => $GLOBALS['STR_ERR_TEL'],
		'email1' => $GLOBALS['STR_ERR_EMAIL'],
		'adresse1' => $GLOBALS['STR_ERR_ADDRESS'],
		'code_postal1' => $GLOBALS['STR_ERR_ZIP'],
		'ville1' => $GLOBALS['STR_ERR_TOWN'],
		'cgv' => $GLOBALS['STR_ERR_CGV']);
	// Le moyen de paiement n'est pas sélectionnable si la commande est égal à 0
	if ($_SESSION['session_caddie']->total > 0) {
		$check_fields['payment_technical_code'] = $GLOBALS['STR_ERR_PAYMENT'];
	}
	if (!empty($GLOBALS['site_parameters']['mode_transport']) && is_delivery_address_necessary_for_delivery_type(vn($_SESSION['session_caddie']->typeId)) && (!is_socolissimo_module_active() || empty($_SESSION['session_commande']['is_socolissimo_order']))) {
		// Si l'on vient de So Colissimo, on ne veut pas, sur /achat/achat_maintenant.php, revérifier les infos de livraison
		$check_fields['nom2'] = $GLOBALS['STR_ERR_NAME'];
		$check_fields['prenom2'] = $GLOBALS['STR_ERR_FIRSTNAME'];
		$check_fields['contact2'] = $GLOBALS['STR_ERR_TEL'];
		$check_fields['email2'] = $GLOBALS['STR_ERR_EMAIL'];
		$check_fields['adresse2'] = $GLOBALS['STR_ERR_ADDRESS'];
		$check_fields['code_postal2'] = $GLOBALS['STR_ERR_ZIP'];
		$check_fields['ville2'] = $GLOBALS['STR_ERR_TOWN'];
		$q_check_country_to_zone = query('SELECT zone
			FROM peel_pays
			WHERE pays_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string(vb($_SESSION['session_commande']['pays2'])) . '" AND ' . get_filter_site_cond('pays'));
		if ($r_check_country_to_zone = fetch_assoc($q_check_country_to_zone)) {
			if ($r_check_country_to_zone['zone'] != $_SESSION['session_caddie']->zoneId) {
				$form_error_object->add('pays2', $GLOBALS['STR_ERR_INFO_NEEDED_TO_CADDIE']);
			}
		}
	}
	$form_error_object->valide_form($_SESSION['session_commande'], $check_fields);
	$_SESSION['session_caddie']->set_paiement($_POST['payment_technical_code']);
	$_SESSION['session_caddie']->update();

	if (!$form_error_object->count()) {
		// Pas d'erreur dans le formulaire de commande.
		if (!empty($GLOBALS['site_parameters']['register_during_order_process']) && !empty($_POST['register_during_order_process'])) {
			// Création du compte en fonction du paramètrage et du souhait de l'utilisateur.
			// Conversion des données fourni dans le formulaire d'adresse de facturation avant de transmettre à la fonction de création d'utilisateur
			$frm['societe'] = vb($_SESSION['session_commande']['societe1']);
			$frm['nom_famille'] = vb($_SESSION['session_commande']['nom1']);
			$frm['prenom'] = vb($_SESSION['session_commande']['prenom1']);
			$frm['telephone'] = vb($_SESSION['session_commande']['contact1']);
			$frm['adresse'] = vb($_SESSION['session_commande']['adresse1']);
			$frm['code_postal'] = vb($_SESSION['session_commande']['code_postal1']);
			$frm['ville'] = vb($_SESSION['session_commande']['ville1']);
			$frm['pays'] = vb($_SESSION['session_commande']['pays1']);
			$frm['email'] = vb($_SESSION['session_commande']['email1']);
			insere_utilisateur($frm, false, true);
			user_login_now($frm['email'], '', false);
		}
		define("IN_STEP2", true);
		$GLOBALS['DOC_TITLE'] =  $GLOBALS['STR_STEP2'];
	}
} elseif (is_socolissimo_module_active() && !empty($_SESSION['session_commande']['is_socolissimo_order'])) {
	if (!PEEL_SOCOLISSIMO_IFRAME && empty($_REQUEST['PUDOFOID']) && empty($_SESSION['session_commande']['client2'])) {
		// On a le module So Colissimo activé, et la commande est liée auprocess SoColissimo ---> On ne veut accéder à achat_maintenant que si on vient de la page SO, pour être certain que cette dernière est bien incluse dans le process de commande
		redirect_and_die($GLOBALS['wwwroot'] . "/achat/caddie_affichage.php");
	}
}
// Adresse de Facturation :
if (empty($GLOBALS['site_parameters']['unsubscribe_order_process']) || est_identifie()) {
	$utilisateur = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
}
// Pour un mode de livraison rattaché ou non à SoColissimo : elle est préremplie en STEP 1 avec les infos du compte utilisateur
// En STEP 2, on la récupere après traitement du formulaire dans $_SESSION['session_commande']
if (!empty($_SESSION['session_commande']['nom1'])) {
	// Test sur $_SESSION['session_commande']['client1'] et non sur $_SESSION['session_commande'] car si So Colissimo est actif sur le site, pour  un mode non relié à So Colissimo, on aura $_SESSION['session_commande']['is_socolissimo_order'] rempli
	$frm['societe1'] = vb($_SESSION['session_commande']['societe1']);
	$frm['nom1'] = vb($_SESSION['session_commande']['nom1']);
	$frm['prenom1'] = vb($_SESSION['session_commande']['prenom1']);
	$frm['contact1'] = vb($_SESSION['session_commande']['contact1']);
	$frm['adresse1'] = vb($_SESSION['session_commande']['adresse1']);
	$frm['code_postal1'] = vb($_SESSION['session_commande']['code_postal1']);
	$frm['ville1'] = vb($_SESSION['session_commande']['ville1']);
	$frm['pays1'] = vb($_SESSION['session_commande']['pays1']);
	$frm['email1'] = vb($_SESSION['session_commande']['email1']);
} else {
	// Si unsubscribe_order_process est défini, $utilisateur est vide.
	$frm['societe1'] = vb($utilisateur['societe']);
	$frm['nom1'] = vb($utilisateur['nom_famille']);
	$frm['prenom1'] = vb($utilisateur['prenom']);
	$frm['email1'] = vb($utilisateur['email']);
	$frm['contact1'] = vb($utilisateur['telephone']);
	$frm['adresse1'] = vb($utilisateur['adresse']);
	$frm['code_postal1'] = vb($utilisateur['code_postal']);
	$frm['ville1'] = vb($utilisateur['ville']);
	if (!empty($utilisateur['pays'])) {
		$pays_id = $utilisateur['pays'];
	} else {
		$pays_id = vn($GLOBALS['site_parameters']['default_country_id']);
	}
	$frm['pays1'] = get_country_name($pays_id);
}
if (!empty($GLOBALS['site_parameters']['mode_transport']) && is_delivery_address_necessary_for_delivery_type(vn($_SESSION['session_caddie']->typeId))) {
	// Adresse de Livraison :
	// - Pour un mode de livraison non rattaché à SO Colissimo : elle est préremplie en STEP 1 avec les infos du compte utilisateur
	// - Pour un mode de livraison rattaché à SO Colissimo, la page SO vient avant STEP 1, et on a donc déjà saisi et validé l'adresse de livraison. ---> les infos sont dans $_SESSION['session_commande']
	if (!empty($_SESSION['session_commande']['nom1']) || !empty($_SESSION['session_commande']['is_socolissimo_order'])) {
		$frm['societe2'] = vb($_SESSION['session_commande']['societe2']);
		$frm['nom2'] = vb($_SESSION['session_commande']['nom2']);
		$frm['prenom2'] = vb($_SESSION['session_commande']['prenom2']);
		$frm['contact2'] = vb($_SESSION['session_commande']['contact2']);
		$frm['adresse2'] = vb($_SESSION['session_commande']['adresse2']);
		$frm['code_postal2'] = vb($_SESSION['session_commande']['code_postal2']);
		$frm['ville2'] = vb($_SESSION['session_commande']['ville2']);
		$frm['pays2'] = vb($_SESSION['session_commande']['pays2']);
		$frm['email2'] = vb($_SESSION['session_commande']['email2']);

		$frm['commentaires'] = vb($_SESSION['session_commande']['commentaires']);
		$frm['cgv'] = vb($_SESSION['session_commande']['cgv']);
	} else {
		// Si unsubscribe_order_process est définir, $utilisateur est vide.
		$frm['societe2'] = vb($utilisateur['societe']);
		$frm['nom2'] = vb($utilisateur['nom_famille']);
		$frm['prenom2'] = vb($utilisateur['prenom']);
		$frm['email2'] = vb($utilisateur['email']);
		$frm['contact2'] = vb($utilisateur['telephone']);
		$frm['adresse2'] = vb($utilisateur['adresse']);
		$frm['code_postal2'] = vb($utilisateur['code_postal']);
		$frm['ville2'] = vb($utilisateur['ville']);
		if (!empty($utilisateur['pays'])) {
			$pays_id = $utilisateur['pays'];
		} else {
			$pays_id = vn($GLOBALS['site_parameters']['default_country_id']);
		}
		$frm['pays2'] = get_country_name($pays_id);

		$frm['commentaires'] = vb($_POST['commentaires']);
		$frm['cgv'] = vb($_POST['cgv']);
	}
}
if (!empty($_SESSION['session_commande']['nom1'])) {
	$frm['commande_interne'] = vb($_SESSION['session_commande']['commande_interne']);
	$frm['commentaires'] = $_SESSION['session_commande']['commentaires'];
	$frm['cgv'] = vb($_SESSION['session_commande']['cgv']);
} else {
	$frm['commande_interne'] = vb($_POST['commande_interne']);
	$frm['commentaires'] = vb($_POST['commentaires']);
	$frm['cgv'] = vb($_POST['cgv']);
}
$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['achat_maintenant_page_columns_count'];
if (!empty($GLOBALS['site_parameters']['mode_transport']) && (empty($_SESSION['session_caddie']->zoneId) || empty($_SESSION['session_caddie']->typeId))) {
	define('IN_CADDIE', true);
	$GLOBALS['DOC_TITLE'] =  $GLOBALS['STR_CADDIE'];
	include($GLOBALS['repertoire_modele'] . "/haut.php");
	$tpl = $GLOBALS['tplEngine']->createTemplate('global_error.tpl');
	$tpl->assign('message', $GLOBALS['STR_ERR_INFO_NEEDED_TO_CADDIE']);
	$tpl->assign('link', array('href' => $GLOBALS['wwwroot'] . '/achat/',
			'value' => $GLOBALS['STR_ORDER_PROCESS_CONTINUE']
			));
	echo $tpl->fetch();
} else {
	if (!defined('IN_STEP2')) {
		define("IN_STEP1", true);
		$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_STEP1'];
	}
	if (!empty($GLOBALS['site_parameters']['short_order_process'])) {
		if ($_SESSION['session_caddie']->count_products() > 0) {
			include($GLOBALS['repertoire_modele'] . "/haut.php");
			// Fin du process de commande, si le paramètre short_order_process est actif. Ce paramètre implique l'absence de paiement et de validation des CGV => Utile pour des demandes de devis
			$utilisateur = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
			$frm['societe1'] = $utilisateur['societe'];
			$frm['nom1'] = $utilisateur['nom_famille'];
			$frm['prenom1'] = $utilisateur['prenom'];
			$frm['email1'] = $utilisateur['email'];
			$frm['contact1'] = $utilisateur['telephone'];
			$frm['adresse1'] = $utilisateur['adresse'];
			$frm['code_postal1'] = $utilisateur['code_postal'];
			$frm['ville1'] = $utilisateur['ville'];
			$frm['pays1'] = get_country_name($utilisateur['pays']);
			put_session_commande($frm);
			$commandeid = $_SESSION['session_caddie']->save_in_database($_SESSION['session_commande']);

			if (!empty($_COOKIE[$GLOBALS['caddie_cookie_name']])) {
				// Il faut supprimer le cookie qui contient les produits du panier, sinon le caddie est automatiquement rechargé dans init().
				unset($_COOKIE[$GLOBALS['caddie_cookie_name']]);
			}
			/* Le caddie est réinitialisé pour ne pas laisser le client passer une deuxième commande en soumettant une deuxième fois le formulaire */
			$_SESSION['session_caddie']->init();
			unset($_SESSION['session_commande']);
			affiche_contenu_html('short_order_process');
			get_order_step3($commandeid);
		} else {
			redirect_and_die($GLOBALS['wwwroot'] . "/");
		}
	} elseif (!defined('IN_STEP2')) {
		include($GLOBALS['repertoire_modele'] . "/haut.php");
		if (is_socolissimo_module_active() && !empty($_SESSION['session_commande']['is_socolissimo_order']) && PEEL_SOCOLISSIMO_IFRAME && empty($_REQUEST['PUDOFOID']) && empty($_SESSION['session_commande']['client2'])) {
			// On a le module So Colissimo activé, et la commande est liée auprocess SoColissimo
			// On est en mode iframe pour SO Colissimo
			echo '<iframe id="SOLivraison" name="SOLivraison" width="100%" height="800" src="' . $GLOBALS['wwwroot'] . '/modules/socolissimo/iframe.php"></iframe>';
		} else {
			if (!isset($form_error_object)) {
				$form_error_object = new FormError();
			}
			echo get_order_step1($frm, $form_error_object, $GLOBALS['site_parameters']['mode_transport']);
		}
	} else {
		include($GLOBALS['repertoire_modele'] . "/haut.php");
		echo get_order_step2($frm, $GLOBALS['site_parameters']['mode_transport']);
	}
}

include($GLOBALS['repertoire_modele'] . "/bas.php");


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fin_commande.php 44077 2015-02-17 10:20:38Z sdelaporte $

include("../configuration.inc.php");
if (empty($GLOBALS['site_parameters']['unsubscribe_order_process'])) {
	necessite_identification();
}

include("../lib/fonctions/display_caddie.php");

define("IN_STEP3", true);
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_STEP3'];

// Test pour empêcher d'arriver ici par hasard ou en tapant l'url
if ($_SESSION['session_caddie']->count_products() == 0 || empty($_SESSION['session_commande'])) {
	redirect_and_die($GLOBALS['wwwroot'] . "/");
}

if (is_icirelais_module_active() && !empty($_SESSION['session_commande']['is_icirelais_order'])) {
	if (!empty($_POST['icirelais_id'])) {
		put_session_commande_infos_from_icirelais($_POST['icirelais_id']);
	} else {
		redirect_and_die($GLOBALS['wwwroot'] . "/achat/achat_maintenant.php");
	}
}

if(is_tnt_module_active() && !empty($_POST['relais_tnt'])){
	try {
		$GLOBALS['web_service_tnt']->put_session_commande_infos_from_tnt($_POST['relais_tnt']);
	} catch (SoapFault $ex) {
		// var_dump($ex->faultcode, $ex->faultstring, $ex->detail);
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_TNT_ERREUR_WEBSERVICE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '.$ex->faultstring))->fetch();
	}
}
/* Création de la commande dans la base, autorise alors le paiement
 * et informe le client que la commande est ok
 */
// La commande est créée en BDD pour que la confirmation du paiement de serveur à serveur
// puisse bien trouver la commande, ou pour les modes de paiements intervenant plus tard
$commandeid = $_SESSION['session_caddie']->save_in_database($_SESSION['session_commande']);
if(is_tnt_module_active() && !empty($_POST['relais_tnt'])){
	try {
		$GLOBALS['web_service_tnt']->expeditionCreation($commandeid, $_POST['relais_tnt']);
	} catch (SoapFault $ex) {
		// var_dump($ex->faultcode, $ex->faultstring, $ex->detail);
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_TNT_ERREUR_WEBSERVICE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '.$ex->faultstring))->fetch();
	}
}
$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['fin_commande_page_columns_count'];
$result = query("SELECT *
	FROM peel_commandes
	WHERE id='" . intval($commandeid) . "' AND " . get_filter_site_cond('commandes') . "");
$com = fetch_object($result);

switch ($com->paiement) {
	// In $com->payment_technical_code is stored the "technical_code" found in peel_paiement
	case 'check':
	case 'transfer':
	case 'pickup':
	case 'delivery':
	case 'cash':
	case 'mandate':
		// On avertit l'utilisateur et l'administrateur uniquement pour les modes de paiement non instantanés
		email_commande($commandeid);

		if (!empty($_COOKIE[$GLOBALS['caddie_cookie_name']])) {
			// Il faut supprimer le cookie qui contient les produits du panier, sinon le caddie est automatiquement rechargé dans init().
			unset($_COOKIE[$GLOBALS['caddie_cookie_name']]);
		}
		// Le caddie est réinitialisé pour ne pas laisser le client passer une deuxième commande en soumettant une deuxième fois le formulaire
		$_SESSION['session_caddie']->init();
		unset($_SESSION['session_commande']);
		
		if (check_if_module_active('ariane_panier')) {
			close_ariane_panier_session();
		}

		break;

	default :
		break;
}

include($GLOBALS['repertoire_modele'] . "/haut.php");

get_order_step3($commandeid);

include($GLOBALS['repertoire_modele'] . "/bas.php");


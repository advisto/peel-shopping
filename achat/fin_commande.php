<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fin_commande.php 64741 2020-10-21 13:48:51Z sdelaporte $

include("../configuration.inc.php");
if (empty($GLOBALS['site_parameters']['unsubscribe_order_process'])) {
	necessite_identification();
}

include($GLOBALS['dirroot']."/lib/fonctions/display_caddie.php");

define("IN_STEP3", true);
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_STEP3'];
$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['fin_commande_page_columns_count'];
$output = '';

// Test pour empêcher d'arriver ici par hasard ou en tapant l'url
if ($_SESSION['session_caddie']->count_products() == 0 || empty($_SESSION['session_commande'])) {
	redirect_and_die(get_url('/'));
}

$output .= call_module_hook('cart_order_step3_before_save', array('user_id' => $_SESSION['session_utilisateur']['id_utilisateur']), 'string');
// Pour savoir si il y a au moins un moyen de paiement configuré.
$payment_select = get_payment_select($_SESSION['session_caddie']->payment_technical_code, false, false, null, null, vb($_SESSION['session_caddie']->payment_multiple));
// pour savoir si il faut afficher les méthodes de paiement ou non. On créer la variable avant de vider éventuellement le panier
$total_caddie = $_SESSION['session_caddie']->total;

if (empty($_GET['paypal_action'])) {
	/* Création de la commande dans la base, autorise alors le paiement
	 * et informe le client que la commande est ok
	 */
	// La commande est créée en BDD pour que la confirmation du paiement de serveur à serveur
	// puisse bien trouver la commande, ou pour les modes de paiements intervenant plus tard
	$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['fin_commande_page_columns_count'];
	if (empty($_POST['order_id'])) {
		// order_id est envoyé en POST dans le cas d'un paiement avec Authorize. On ne veut pas créer une deuxième fois la commande lorsque l'on soumet le formulaire pour payer
    
		$commandeid = $_SESSION['session_caddie']->save_in_database($_SESSION['session_commande']);
		$output .= call_module_hook('cart_order_step3_after_save', array('order_id' => $commandeid, 'user_id' => $_SESSION['session_utilisateur']['id_utilisateur'], 'frm' => vb($_POST)), 'string');
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
		case 'order_form':
		case '':
			// On avertit l'utilisateur et l'administrateur uniquement pour les modes de paiement non instantanés.
			if (empty($GLOBALS['site_parameters']['send_order_email_after_payement'])) {
				email_commande($commandeid);
			}
			// Le caddie est réinitialisé pour ne pas laisser le client passer une deuxième commande en soumettant une deuxième fois le formulaire
			$_SESSION['session_caddie']->init();
			unset($_SESSION['session_commande']);
			if(est_identifie() && !empty($GLOBALS['site_parameters']['save_cart_auto_enable'])) {
				query("DELETE FROM peel_save_cart WHERE products_list_name='00panier' AND id_utilisateur='".intval($_SESSION['session_utilisateur']['id_utilisateur'])."'");
			}
			if (check_if_module_active('ariane_panier')) {
				close_ariane_panier_session();
			}
			break;

		default :
			break;
		}
	} else {
		$commandeid = $_POST['order_id'];
	}
} else {
	$commandeid = $_GET['order_id'];
}
$output .= get_order_step3($commandeid, ($total_caddie > 0 && !empty($payment_select)));

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


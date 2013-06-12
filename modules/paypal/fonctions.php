<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 36927 2013-05-23 16:15:39Z gboussin $
if (!defined('PAYPAL_SANDBOX')) {
	// Mettre à true pour faire des tests avec des comptes Sandbox
	define('PAYPAL_SANDBOX', false);
}

/**
 * Génère le formulaire de paiement Paypal
 *
 * @param integer $order_id
 * @param string $lang
 * @param mixed $amount
 * @param string $currency_code
 * @param string $user_email
 * @param integer $payment_times
 * @param string $sTexteLibre
 * @param integer $user_id
 * @param string $user_country_bill
 * @return
 */
function getPaypalForm($order_id, $lang, $amount, $currency_code, $user_email, $payment_times = 1, $sTexteLibre = '', $user_id, $prenom_ship, $nom_ship, $adresse_ship, $zip_ship, $ville_ship, $pays_ship, $telephone_ship, $prenom_bill = null, $nom_bill = null, $adresse_bill = null, $zip_bill = null, $ville_bill = null, $pays_bill = null, $telephone_bill = null)
{
	if (PAYPAL_SANDBOX) {
		$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	} else {
		$url = 'https://www.paypal.com/cgi-bin/webscr';
	}
	// La déclaration no_shipping empêche la validation par Paypal des adresses
	// alors que la documentation indique que c'est simplement censé empêcher l'utilisateur de changer l'adresse quand il arrive sur paypal
	// et éviter ainsi d'avoir une information en BDD différente de celle modifiée en dernier par l'utilisateur
	// => évitez d'utiliser	<input type="hidden" name="no_shipping" value="1" />
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/paypal_form.tpl');
	$tpl->assign('url', $url);
	$tpl->assign('charset', GENERAL_ENCODING);
	$tpl->assign('business', vb($GLOBALS['site_parameters']['email_paypal']));
	$tpl->assign('item_name', $GLOBALS['site'] . ' '.$GLOBALS["STR_ORDER_NAME"].' ' . $order_id);
	$tpl->assign('item_number', intval($order_id));
	$tpl->assign('amount', round($amount, 2));
	$tpl->assign('first_name', str_replace(array("\n", "\r", "\r\n"), "", $prenom_ship));
	$tpl->assign('last_name', str_replace(array("\n", "\r", "\r\n"), "", $nom_ship));
	$tpl->assign('address1', String::substr(str_replace(array("\n", "\r", "\r\n"), "", $adresse_ship), 0, 100));
	$tpl->assign('address2', String::substr(str_replace(array("\n", "\r", "\r\n"), "", $adresse_ship), 100, 100));
	$tpl->assign('zip', str_replace(array("\n", "\r", "\r\n"), "", $zip_ship));
	$tpl->assign('city', str_replace(array("\n", "\r", "\r\n"), "", $ville_ship));
	$tpl->assign('country', String::strtoupper(String::substr(get_country_iso_2_letter_code(trim($pays_ship)), 0, 2)));
	$tpl->assign('prenom_bill', str_replace(array("\n", "\r", "\r\n"), "", $prenom_bill));
	$tpl->assign('nom_bill', str_replace(array("\n", "\r", "\r\n"), "", $nom_bill));
	$tpl->assign('adresse1_bill', String::substr(str_replace(array("\n", "\r", "\r\n"), "", $adresse_bill), 0, 100));
	$tpl->assign('adresse2_bill', String::substr(str_replace(array("\n", "\r", "\r\n"), "", $adresse_bill), 100, 100));
	$tpl->assign('zip_bill', str_replace(array("\n", "\r", "\r\n"), "", $zip_bill));
	$tpl->assign('ville_bill', str_replace(array("\n", "\r", "\r\n"), "", $ville_bill));
	$tpl->assign('pays_bill', String::strtoupper(String::substr(get_country_iso_2_letter_code(trim($pays_bill)), 0, 2)));
	$tpl->assign('return', $GLOBALS['wwwroot'] . '/modules/paypal/ok.php?id=' . $order_id);
	$tpl->assign('cancel_return', $GLOBALS['wwwroot'] . '/modules/paypal/nok.php?id=' . $order_id);
	$tpl->assign('notify_url', $GLOBALS['wwwroot'] . '/modules/paypal/ipn.php');
	$tpl->assign('currency_code', $currency_code);
	$tpl->assign('lc', $lang);
	$tpl->assign('email', $user_email);
	$tpl->assign('paypal_bouton_src', $GLOBALS['STR_MODULE_PAYPAL_BOUTON']);
	$tpl->assign('paypal_button_alt', $GLOBALS['STR_MODULE_PAYPAL_BUTTON_ALT']);

	$form = $tpl->fetch();
	return $form;
}

?>
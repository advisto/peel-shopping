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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $

/**
 * getMoneyBookersForm()
 *
 * @param integer $order_id
 * @param string $lang
 * @param integer $user_id
 * @param string $user_email
 * @param mixed $amount
 * @param string $currency
 * @param string $user_firstname
 * @param string $user_familyname
 * @param string $user_address
 * @param string $user_zip
 * @param string $user_town
 * @param string $user_country_code
 * @return
 */
function getMoneyBookersForm($pay_to_email, $order_id, $lang, $user_id, $user_email,  $amount, $currency, $user_firstname,  $user_familyname,  $user_address, $user_zip, $user_town,  $user_country_name, $total_tva, $payment_methods)
{
    $output = '';
    if (!empty($_SESSION['session_moneybookers_try'])) {
        $_SESSION['session_moneybookers_try']++;
    } else {
        $_SESSION['session_moneybookers_try'] = '1';
    }
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/moneybookers_form.tpl');
	$tpl->assign('pay_to_email', $pay_to_email);
	$tpl->assign('order_id', $order_id);
	$tpl->assign('try', $_SESSION['session_moneybookers_try']);
	$tpl->assign('return_url', get_url('/modules/moneybookers/ok.php'));
	$tpl->assign('cancel_url', get_url('/modules/moneybookers/nok.php'));
	$tpl->assign('status_url', get_url('/modules/moneybookers/ipn.php'));
	$tpl->assign('lang', $lang);
	$tpl->assign('user_id', $user_id);
	$tpl->assign('user_email', $user_email);
	$tpl->assign('STR_TOTAL_HT', $GLOBALS['STR_TOTAL_HT']);
	$tpl->assign('amount2', round($amount - $total_tva, 2));
	$tpl->assign('STR_TAXE', $GLOBALS['STR_TAXE']);
	$tpl->assign('amount3', round($total_tva, 2));
	$tpl->assign('amount', round($amount, 2));
	$tpl->assign('currency', $currency);
	$tpl->assign('firstname', $user_firstname);
	$tpl->assign('lastname', $user_familyname);
	$tpl->assign('address', $user_address);
	$tpl->assign('postal_code', $user_zip);
	$tpl->assign('city', $user_town);
	$tpl->assign('country', get_country_iso_3_letter_code($user_country_name));
	$tpl->assign('recipient_description', $GLOBALS['site']);
	$tpl->assign('payment_methods', $payment_methods);
	$tpl->assign('is_hide_login', strpos($payment_methods, 'WLT') === false);
	$tpl->assign('STR_MODULE_MONEYBOOKERS_SUBMIT_BUTTON', $GLOBALS['STR_MODULE_MONEYBOOKERS_SUBMIT_BUTTON']);
	$output .= $tpl->fetch();
    return $output;
}

function getErrorText($error_id)
{
    $error_array = array('1' => 'Referred',
        '2' => 'Invalid Merchant Number',
        '3' => 'Pick-up card',
        '4' => 'Authorisation Declined',
        '5' => 'Other Error',
        '6' => 'CVV is mandatory, but not set or invalid',
        '7' => 'Approved authorisation, honour with identification',
        '8' => 'Delayed Processing',
        '9' => 'Invalid Transaction',
        '10' => 'Invalid Currency',
        '11' => 'Invalid Amount/Available Limit Exceeded/Amount too high',
        '12' => 'Invalid credit card or bank account ',
        '13' => 'Invalid Card Issuer',
        '14' => 'Annulation by client',
        '15' => 'Duplicate transaction',
        '16' => 'Acquirer Error',
        '17' => 'Reversal not processed, matching authorisation not found',
        '18' => 'File Transfer not available/unsuccessful',
        '19' => 'Reference number error',
        '20' => 'Access Denied',
        '21' => 'File Transfer failed',
        '22' => 'Format Error',
        '23' => 'Unknown Acquirer',
        '24' => 'Card expired',
        '25' => 'Fraud Suspicion',
        '26' => 'Security code expired',
        '27' => 'Requested function not available',
        '28' => 'Lost/Stolen card',
        '29' => 'Stolen card, Pick up',
        '30' => 'Duplicate Authorisation',
        '31' => 'Limit Exceeded',
        '32' => 'Invalid Security Code',
        '33' => 'Unknown or Invalid Card/Bank account',
        '34' => 'Illegal Transaction',
        '35' => 'Transaction Not Permitted',
        '36' => 'Card blocked in local blacklist',
        '37' => 'Restricted card/bank account',
        '38' => 'Security Rules Violation',
        '39' => 'The transaction amount of the referencing transaction is higher than the transaction amount of the original transaction',
        '40' => 'Transaction frequency limit exceeded, override is possible',
        '41' => 'Incorrect usage count in the Authorisation System exceeded',
        '42' => 'Card blocked',
        '43' => 'Rejected by Credit Card Issuer',
        '44' => 'Card Issuing Bank or Network is not available',
        '45' => 'The card type is not processed by the authorisation centre / Authorisation System has determined incorrect Routing',
        '47' => 'Processing temporarily not possible',
        '48' => 'Security Breach',
        '49' => 'Date / time not plausible, trace-no. not increasing',
        '50' => 'Error in PAC encryption detected',
        '51' => 'System Error',
        '52' => 'MB Denied - potential fraud',
        '53' => 'Mobile verification failed',
        '54' => 'Failed due to internal security restrictions',
        '55' => 'Communication or verification problem',
        '56' => '3D verification failed',
        '57' => 'AVS check failed',
        '58' => 'Invalid bank code',
        '59' => 'Invalid account code',
        '60' => 'Card not authorised',
        '61' => 'No credit worthiness',
        '62' => 'Communication error',
        '63' => 'Transaction not allowed for cardholder',
        '64' => 'Invalid Data in Request',
        '65' => 'Blocked bank code',
        '66' => 'CVV2/CVC2 Failure',
        '99' => 'General error');
    if (isset($error_array[$error_id])) {
        return $error_array[$error_id];
    } else {
        return 'Erreur inconnue';
    }
}


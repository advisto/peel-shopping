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
// $Id: ipn.php 55332 2017-12-01 10:44:06Z sdelaporte $
include("../../configuration.inc.php");


if (empty($_POST)) {
    die();
}

$checknumber=strtoupper(md5(vb($_POST['merchant_id']).vb($_POST['transaction_id']).strtoupper(md5($GLOBALS['site_parameters']['secret_word'])).vb($_POST['amount']).vb($_POST['currency']).vb($_POST['status'])));

if ($checknumber == vb($_POST['md5sig'])) {
	//responce is sent By Moneybookers
	$status = vn($_POST['status']);
	$transaction_id = vn($_POST['transaction_id']);
	$amount = vn($_POST['amount']);
	$pay_to_email = vn($_POST['pay_to_email']);
	if ($pay_to_email == $GLOBALS['site_parameters']['email_moneybookers']) {
		$q = query('SELECT *
				FROM peel_commandes
				WHERE id="' . intval($transaction_id) . '" AND ' . get_filter_site_cond('commandes') . '
				LIMIT 1');
		if ($r = fetch_assoc($q)) {
			if (round($r['montant'], 2) == round($amount, 2)) {
				switch ($status) {
					case '-2' :
						// failed
						$update_status = 'cancelled';
						break;

					case '2' :
						// processed
						$update_status = 'completed';
						break;

					case '0' :
						// pending
						$update_status = 'pending';
						break;

					case '-1' :
						// cancelled
						$update_status = 'cancelled';
						break;

					default :
						$update_status = 'pending';
						break;
				}
				if(in_array($update_status, array('being_checked', 'completed'))) {
					$data = $_POST;
					$data['MONTANT_CREDIT'] = $amount;
					accounting_insert_transaction($transaction_id, 'moneybookers', $_POST);
				}
				if(in_array($update_status, array('completed'))) {
					email_commande($transaction_id);
				}
				update_order_payment_status($transaction_id, $update_status, true, null, null,false, 'moneybookers');
			} else {
				send_email($GLOBALS['support'], 'Alerte : Montant de la transaction CB ' . $transaction_id . ' altéré', str_replace(",", "", fprix($r['montant'])) . ' = ' . $amount);
			}
		}
	}
} else {
	//responce is NOT sent By Moneybookers
	send_email($GLOBALS['support'], 'Alerte : problème sur transaction CB commande  ' . $transaction_id . '', 'Les informations Moneybookers semblent incorrectes ' . "\n\n" . print_r($_REQUEST, true));
}

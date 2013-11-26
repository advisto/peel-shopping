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
// $Id: ipn.php 38682 2013-11-13 11:35:48Z gboussin $
define('DISABLE_INPUT_ENCODING_CONVERT', true);
include("../../configuration.inc.php");
include($fonctionspaypal);
// Ce fichier est appelé directement pas Paypal après chaque transaction, échouée ou fructueuse
if (empty($_POST)) {
	die();
}

if (PAYPAL_SANDBOX) {
	$paypal_domain = 'www.sandbox.paypal.com';
} else {
	$paypal_domain = 'ipnpb.paypal.com';
}
// send_email($GLOBALS['support'], 'INFOS - commande '.$_POST['item_number'], 'Les informations techniques sont : ' . "\n\n" . print_r($_REQUEST, true));
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
	$req .= "&" . $key . "=" . urlencode($value);
}
$q = query('SELECT id, montant
	FROM peel_commandes
	WHERE id="' . intval($_POST['item_number']) . '"
	LIMIT 1');
if ($r = fetch_assoc($q)) {
	if (round($r['montant'] * 100) == round($_POST['mc_gross'] * 100)) {
		// post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
		$header .= "Host: " . $paypal_domain . ":443\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . String::strlen($req) . "\r\n";
		$header .= "Connection: close\r\n\r\n";
		$fp = fsockopen ('ssl://' . $paypal_domain, 443, $errno, $errstr, 30);
		if (!$fp) {
			$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
			$header .= "Host: " . str_replace('ipnpb', 'www', $paypal_domain) . "\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: " . String::strlen($req) . "\r\n";
			$header .= "Connection: close\r\n\r\n";
			// On essaie sans SSL si l'hébergement ne le permet pas
			$fp = fsockopen (str_replace('ipnpb', 'www', $paypal_domain), 80, $errno, $errstr, 30);
		}
		if (!$fp) {
			// HTTP ERROR
			send_email($GLOBALS['support'], 'Problème d\'échange de données Paypal IPN - commande ' . $r['id'], 'Un paiement n\'a pas pu être pris en compte pour des raisons techniques : ' . $errno . ' - ' . $errstr . '. L\'IP du serveur qui a voulu confirmer une transaction est : ' . $_SERVER['REMOTE_ADDR']);
		} else {
			$item_name = vb($_POST['item_name']);
			$item_number = intval($_POST['item_number']);
			$payment_status = vb($_POST['payment_status']);
			$payment_amount = vb($_POST['mc_gross']);
			$payment_currency = vb($_POST['mc_currency']);
			// $txn_id = $_POST['txn_id'];
			// $receiver_email = $_POST['receiver_email'];
			// $payer_email = $_POST['payer_email'];
			// $pending_reason = $_POST['pending_reason'];
			// $txn_type = $_POST['txn_type'];
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				// $res vaut d'abord des entêtes HTTP, puis VERIFIED ou INVALID, puis d'autres entêtes HTTP pour fermer connexion
				if (strcmp(trim(strip_tags($res)), "VERIFIED") == 0) {
					if ($payment_status == "Completed") {
						$peel_status = 3;
						email_commande($item_number);
					} elseif ($payment_status == "Pending") {
						$peel_status = 2;
					} elseif ($payment_status == "Failed") {
						$peel_status = 6;
					} elseif ($payment_status == "Denied") {
						$peel_status = 6;
					} elseif ($payment_status == "Refunded") {
						$peel_status = 9;
					} else {
						send_email($support, 'Problème d\'échange de données Paypal IPN - commande ' . $r['id'], 'Un paiement a été passé "en cours de vérification" dans votre boutique car Paypal n\'a pas confirmé ou infirmé le paiement.' . "\n\n" . ' Réponse par Paypal : ' . $res . "\n\n" . 'Les informations techniques sont : ' . "\n\npayment_status : " . $payment_status . "\n\n" . print_r($_REQUEST, true));
					}
				} elseif (strcmp(trim(strip_tags($res)), "INVALID") == 0) {
					$peel_status = 2;
					send_email($GLOBALS['support'], 'Problème d\'échange de données Paypal IPN - commande ' . $r['id'], 'Un paiement a été passé "en cours de vérification" dans votre boutique car Paypal n\'a pas confirmé ou infirmé le paiement.' . "\n\n" . ' Réponse par Paypal : ' . $res . "\n\n" . 'Les informations techniques sont : ' . "\n\n" . print_r($_REQUEST, true));
				}
				if (!empty($peel_status)) {
					update_order_payment_status($item_number, $peel_status, true, null, null, false, 'paypal');
					unset($peel_status);
				}
			}
			fclose ($fp);
		}
	} else {
		send_email($GLOBALS['support'], 'Alerte : Montant altéré de la transaction Paypal - commande ' . intval($_POST['item_number']) . '', round($r['montant'] * 100) . ' = ' . round($_POST['mc_gross'] * 100));
	}
} else {
	send_email($GLOBALS['support'], 'Alerte : problème sur transaction Paypal commande non trouvée ' . intval($_POST['item_number']) . '', 'Les informations Paypal semblent incorrectes ' . "\n\n" . print_r($_REQUEST, true));
}

?>
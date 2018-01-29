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

// Procédure pour envoyer un nouvel appel à IPN depuis le compte paypal :
// - Se connecter au compte paypal
// - Se rendre sur la page "préférence" (lien en haut à droite du header)> "Paramètres du compte".
// - Menu "Mes ventes"
// - Dans l'encart "Obtenir des paiements et gérer mes risques", cliquer sur "Mettre à jour" au niveau de la ligne "Notifications instantanées de paiement"
// - Sur la page qui s'affiche, cliquer sur "Choisir mes paramètres IPN"
// - Choisir "Recevoir les messages IPN (activé)", et mettre l'url du fichier IPN dans le champ "URL de notification".
// - Cliquer sur "enregistrer mes paramètres"
// - Sur la page qui s'affiche, cliquer sur le lien "Historique des notifications instantanées de paiement" dans le texte (url https://www.paypal.com/fr/cgi-bin/webscr?cmd=_display-ipns-history)
// La liste des appels à IPN s'affiche, il faut cocher une checkbox et cliquer sur le bouton "Renvoyer les éléments sélectionnés". Attention, le renvoi de l'appel n'est pas immédiat.


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
if (!empty($_POST['item_number'])) {
	$item_number = intval($_POST['item_number']);
} elseif (!empty($_POST['custom'])) {
	// si paypal intégrale évolution.
	$item_number = intval($_POST['custom']);
}
$q = query('SELECT id, montant, devise, currency_rate
	FROM peel_commandes
	WHERE id="' . intval($item_number) . '" AND ' . get_filter_site_cond('commandes') . '
	LIMIT 1');
if ($r = fetch_assoc($q)) {
	if (round(fprix($r['montant'], false, $r['devise'], true, $r['currency_rate'], false, false) * 100) == round($_POST['mc_gross'] * 100)) {
		// post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
		$header .= "Host: " . $paypal_domain . ":443\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . StringMb::strlen($req) . "\r\n";
		$header .= "Connection: close\r\n\r\n";
		$fp = fsockopen ('ssl://' . $paypal_domain, 443, $errno, $errstr, 30);
		if (!$fp) {
			$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
			$header .= "Host: " . str_replace('ipnpb', 'www', $paypal_domain) . "\r\n";
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$header .= "Content-Length: " . StringMb::strlen($req) . "\r\n";
			$header .= "Connection: close\r\n\r\n";
			// On essaie sans SSL si l'hébergement ne le permet pas
			$fp = fsockopen (str_replace('ipnpb', 'www', $paypal_domain), 80, $errno, $errstr, 30);
		}
		if (!$fp) {
			// HTTP ERROR
			send_email($GLOBALS['support'], 'Problème d\'échange de données Paypal IPN - commande ' . $r['id'], 'Un paiement n\'a pas pu être pris en compte pour des raisons techniques : ' . $errno . ' - ' . $errstr . '. L\'IP du serveur qui a voulu confirmer une transaction est : ' . $_SERVER['REMOTE_ADDR']);
		} else {
			$item_name = vb($_POST['item_name']);
			$payment_status = vb($_POST['payment_status']);
			$payment_amount = vb($_POST['mc_gross']);
			$payment_currency = vb($_POST['mc_currency']);
			// $txn_id = $_POST['txn_id'];
			// $receiver_email = $_POST['receiver_email'];
			// $payer_email = $_POST['payer_email'];
			// $pending_reason = $_POST['pending_reason'];
			// $txn_type = $_POST['txn_type'];
			fputs ($fp, $header . $req);
			while (!StringMb::feof($fp)) {
				$res = fgets ($fp, 1024);
				// $res vaut d'abord des entêtes HTTP, puis VERIFIED ou INVALID, puis d'autres entêtes HTTP pour fermer connexion
				if (strcmp(trim(strip_tags($res)), "VERIFIED") == 0) {
					if ($payment_status == "Completed") {
						$update_status = 'completed';
						if(empty($GLOBALS['site_parameters']['send_order_email_after_payement'])) {
							// Si send_order_email_after_payement alors l'email est envoyé par update_order_payment_status
							email_commande($item_number);
						}
					} elseif ($payment_status == "Pending") {
						$update_status = 'being_checked';
					} elseif ($payment_status == "Failed") {
						$update_status = 'cancelled';
					} elseif ($payment_status == "Denied") {
						$update_status = 'cancelled';
					} elseif ($payment_status == "Refunded") {
						$update_status = 'refunded';
					} else {
						send_email($GLOBALS['support'], 'Problème d\'échange de données Paypal IPN - commande ' . $r['id'], 'Un paiement a été passé "en cours de vérification" sur votre site car Paypal n\'a pas confirmé ou infirmé le paiement.' . "\n\n" . ' Réponse par Paypal : ' . $res . "\n\n" . 'Les informations techniques sont : ' . "\n\npayment_status : " . $payment_status . "\n\n" . print_r($_REQUEST, true));
					}
				} elseif (strcmp(trim(strip_tags($res)), "INVALID") == 0) {
					$update_status = 'being_checked';
					send_email($GLOBALS['support'], 'Problème d\'échange de données Paypal IPN - commande ' . $r['id'], 'Un paiement a été passé "en cours de vérification" sur votre site car Paypal n\'a pas confirmé ou infirmé le paiement.' . "\n\n" . ' Réponse par Paypal : ' . $res . "\n\n" . 'Les informations techniques sont : ' . "\n\n" . print_r($_REQUEST, true));
				}
				if (!empty($update_status)) {
					if(!empty($GLOBALS['site_parameters']['billing_as_transaction_receipt']) && in_array($update_status, array('being_checked', 'completed'))) {
						accounting_insert_transaction($r['id'], 'paypal', array('ORDER_ID' => $r['id'], 'MONTANT_CREDIT' => $payment_amount, 'CURRENCY_CODE' => $payment_currency));
					}
					update_order_payment_status($item_number, $update_status, true, null, null, false, 'paypal');
					unset($update_status);
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


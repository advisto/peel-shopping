<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: nok.php 66961 2021-05-24 13:26:45Z sdelaporte $
include("../../configuration.inc.php");

if (empty($_GET['id'])) {
	die();
}
$transaction_id = intval(vb($_GET['id']));

if (defined('PEEL_VERSION') && PEEL_VERSION >= 6) {
	// il faut inclure le fichier sur les versions >= 6. Pour les versions inférieurs, la fonction est défini dans le module compatibility
	include($GLOBALS['dirroot'] . "/lib/fonctions/display_caddie.php");
}
if (!empty($_SESSION['session_utilisateur']['id_utilisateur'])) {
	$session_utilisateur_id = $_SESSION['session_utilisateur']['id_utilisateur'];
} elseif (!defined('PEEL_VERSION') && !empty($_SESSION['utilisateur']['id_utilisateur'])) {
	$session_utilisateur_id = $_SESSION['utilisateur']['id_utilisateur'];
} else {
	$session_utilisateur_id = null;
}
// Ce fichier est appelé par l'utilisateur qui provient de Paypal et pour qui le paiement ne s'est apparemment pas fait.
// Décommenter la ligne suivante si on veut que l'utilisateur soit déconnecté automatiquement si son paiement a échoué
// session_destroy();
// On annule la commande au cas où la banque ne nous contacte pas pour le faire
// Pour ce faire, on recherche la dernière commande passée par le client dans les 2 dernières heures, et on l'annule
// Sinon, il suffirait d'appeler ce fichier en précisant le n° de commande qu'on veut annuler => ça serait une faille de sécurité
if (!empty($session_utilisateur_id) || (!empty($_SESSION['session_last_bill_viewed']) && $_SESSION['session_last_bill_viewed'] == $transaction_id)) {
	// Sécurité : On vérifie que l'utilisateur est bien l'auteur de cette commande
	$sql = 'SELECT id
		FROM peel_commandes
		WHERE o_timestamp >= "' . date('Y-m-d H:i:s', (time())-7200) . '" AND id="' . intval($transaction_id) . '" AND ' . get_filter_site_cond('commandes') . '';
	if (!empty($session_utilisateur_id)) {
		$sql .= ' AND id_utilisateur = "' . intval($session_utilisateur_id) . '"';
	}
	$sql .= ' LIMIT 1';
	$q = query($sql);
	if ($r = fetch_assoc($q)) {
		// On n'autorise pas l'annulation d'une commande déjà payée pour éviter les problèmes
		// avec un éventuel utilisateur qui revient en arrière dans son navigateur et clique sur annuler
		$GLOBALS['output_create_or_update_order'] = update_order_payment_status($r['id'], false, false);
	}
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
affichage_fin_cb(vb($r['id']), false);
include($GLOBALS['repertoire_modele'] . "/bas.php");


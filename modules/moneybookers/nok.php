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
// $Id: nok.php 55332 2017-12-01 10:44:06Z sdelaporte $
//

include("../../configuration.inc.php");

if(defined('PEEL_VERSION') && PEEL_VERSION >= 6) {
	// il faut inclure le fichier sur les versions >= 6. Pour les versions inférieurs, la fonction est défini dans le module compatibility
	include($GLOBALS['dirroot']."/lib/fonctions/display_caddie.php");
}
if(!empty($_SESSION['session_utilisateur']['id_utilisateur'])) {
	$session_utilisateur_id = $_SESSION['session_utilisateur']['id_utilisateur'];
} elseif (!defined('PEEL_VERSION') &&  !empty($_SESSION['utilisateur']['id_utilisateur'])){
	$session_utilisateur_id = $_SESSION['utilisateur']['id_utilisateur'];
} else {
	$session_utilisateur_id = null;
}

// Ce fichier est appelé par l'utilisateur qui provient de la banque et pour qui le paiement ne s'est apparemment pas fait.
// Cette page est normalement purement informative pour le client, et n'a qu'un titre indicatif.
// Ce n'est pas ici que se trouve la validation ou non du paiement, mais dans le script qui est appelé directement par la banque et dans lequel le traitement est sécurisé

// Décommenter la ligne suivante si on veut que l'utilisateur soit déconnecté automatiquement si son paiement a échoué
// session_destroy();

// On annule la commande au cas où la banque ne nous contacte pas pour le faire
// Pour ce faire, on recherche la dernière commande passée par le client dans la demi-heure, et on l'annule
// Sinon, il suffirait d'appeler ce fichier en précisant le n° de commande qu'on veut annuler => ça serait une faille de sécurité

if(!empty($session_utilisateur_id) || !empty($_SESSION['session_last_bill_viewed'])){
	// Sécurité : On vérifie que l'utilisateur est bien l'auteur de cette commande
	$sql = 'SELECT id
		FROM peel_commandes
		WHERE o_timestamp >= "' . date('Y-m-d H:i:s', (time())-1800) . '" AND ' . get_filter_site_cond('commandes') . '
		LIMIT 1';
	if(!empty($session_utilisateur_id)){
		$sql .= ' AND id_utilisateur = "' . intval($session_utilisateur_id) . '"';
	}else{
		$sql .= ' AND id="' . intval($_SESSION['session_last_bill_viewed']) . '"';
	}
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


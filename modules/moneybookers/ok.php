<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: ok.php 39443 2014-01-06 16:44:24Z sdelaporte $
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
} else{
	$session_utilisateur_id = null;
}

// Ce fichier est appelé par l'utilisateur qui revient de la banque et pour qui le paiement s'est apparemment bien passé (on n'en a ici aucune preuve).
// Cette page est purement informative pour le client, et n'a qu'un titre indicatif.
// Ce n'est pas ici que se trouve la validation du paiement, mais dans le script qui est appelé directement par la banque et dans lequel le traitement est sécurisé

// Le caddie est réinitialisé pour ne pas laisser le client passer une deuxième commande en soumettant une deuxième fois le formulaire
$_SESSION['session_caddie']->init();
unset($_SESSION['session_commande']);

// Décommenter la ligne suivante si on veut que l'utilisateur soit déconnecté automatiquement si son paiement a échoué
// session_destroy();

if(!empty($session_utilisateur_id) || !empty($_SESSION['session_last_bill_viewed'])){
	$sql = 'SELECT id
		FROM peel_commandes
		WHERE o_timestamp >= "' . date('Y-m-d H:i:s', (time())-1800) . '"';
	if(!empty($session_utilisateur_id)){
		$sql .= ' AND id_utilisateur = "' . intval($session_utilisateur_id) . '"';
	}else{
		$sql .= ' AND id="' . intval($_SESSION['session_last_bill_viewed']) . '"';
	}
	$sql .= '
		LIMIT 1';
	$q = query($sql);
	$r = fetch_assoc($q);
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
affichage_fin_cb(vb($r['id']), true);
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: historique_commandes.php 36927 2013-05-23 16:15:39Z gboussin $
include("../configuration.inc.php");
necessite_identification();

include("../lib/fonctions/display_caddie.php");

$DOC_TITLE = $GLOBALS['STR_ORDER_HISTORY'];
define("IN_ORDER_HISTORY", true);

$page_name = 'historique_commandes';

$output = '';
switch (vb($_REQUEST['mode'])) {
	case "details" :
		$sql = "SELECT *
			FROM peel_commandes
			WHERE id = '" . intval($_GET['id']) . "' AND id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND o_timestamp = '" . nohtml_real_escape_string(vb($_GET['timestamp'])) . "'";
		$qid_commande = query($sql);
		if (num_rows($qid_commande) > 0) {
			// On a bien rentré une URL qui est complète pour voir cette commande
			$output .= affiche_resume_commande(intval($_GET['id']), true, true);
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('global_error.tpl');
			$tpl->assign('message', $GLOBALS['STR_AUTH_DENIAL']);
			$output .= $tpl->fetch();
		}
		break;

	default :
		$order = "o_timestamp";
		$sort = "DESC";
		$output .= affiche_liste_commandes($order, $sort);
		break;
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
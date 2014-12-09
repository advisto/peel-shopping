<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: ajouter.php 43037 2014-10-29 12:01:40Z sdelaporte $
//

include("../../configuration.inc.php");

if (!is_module_pensebete_active()) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die($GLOBALS['wwwroot']."/");
}

if (!est_identifie()) {
	// User not logged in ==> we redirect to login page.
	$_SESSION['session_redirect_after_login'] = get_current_url(true);
	redirect_and_die($GLOBALS['wwwroot'] . '/membre.php');
}

define('IN_PENSE_BETE', true);

include($GLOBALS['repertoire_modele'] . "/haut.php");
switch (vb($_GET['mode'])) {
	case "ajout" :
		if (!empty($_GET['adsid'])) {
			$item_id = intval($_GET['adsid']);
			$type = 'annonce';
		} elseif (!empty($_GET['prodid'])) {
			$item_id = intval($_GET['prodid']);
			$type = 'produit';
		}
		insere_pense(vn($item_id), $type);
		break;
}
include($GLOBALS['repertoire_modele'] . "/bas.php");


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
// $Id: ajouter.php 55332 2017-12-01 10:44:06Z sdelaporte $
//

include("../../configuration.inc.php");

if (!check_if_module_active('pensebete')) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die(get_url('/'));
}
necessite_identification();

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
		echo insere_pense(vn($item_id), $type);
		break;
}
include($GLOBALS['repertoire_modele'] . "/bas.php");


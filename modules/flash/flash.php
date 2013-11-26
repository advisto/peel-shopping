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
// $Id: flash.php 38682 2013-11-13 11:35:48Z gboussin $

include("../../configuration.inc.php");

if (!is_flash_sell_module_active() || !is_flash_active_on_site()) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die($GLOBALS['wwwroot'] . "/");
}

define("IN_FLASH", true);
$page_name = 'flash';
$output = affiche_produits(null, 1, "flash", $GLOBALS['site_parameters']['nb_produit_page'], 'general', true);

include($GLOBALS['repertoire_modele'] . "/haut.php");

echo $output;

include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: suggest.php 48447 2016-01-11 08:40:08Z sdelaporte $
include("../configuration.inc.php");

$prix_min = - $_SESSION['session_caddie']->total;
define("IN_SUGGEST", true);
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_OUR_SUGGEST'];

$output = affiche_produits($prix_min, 1, "suggest", $GLOBALS['site_parameters']['nb_produit_page'], 'general', true);

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


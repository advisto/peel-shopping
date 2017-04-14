<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2017 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.5, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: promotions.php 53200 2017-03-20 11:19:46Z sdelaporte $
include("../configuration.inc.php");

define("IN_PROMOTIONS", true);
$GLOBALS['page_name'] = 'promotions';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_PROMOTIONS'];

$output = affiche_produits(null, 1, "promotion", vn($GLOBALS['site_parameters']['nb_produit_page']), 'general', true);

include($GLOBALS['repertoire_modele'] . "/haut.php");

echo $output;

include($GLOBALS['repertoire_modele'] . "/bas.php");


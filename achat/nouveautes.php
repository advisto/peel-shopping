<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: nouveautes.php 61970 2019-11-20 15:48:40Z sdelaporte $
include("../configuration.inc.php");

define("IN_NOUVEAUTES", true);
$GLOBALS['page_name'] = 'nouveautes';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_NOUVEAUTES'];

$output = affiche_produits(null, 1, "nouveaute", vn($GLOBALS['site_parameters']['nb_produit_page']), 'general', true);

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


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
// $Id: plan_acces.php 43037 2014-10-29 12:01:40Z sdelaporte $

include("configuration.inc.php");

define('IN_PLAN_ACCES', true);
$GLOBALS['page_name'] = 'access_plan';

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo print_access_plan();
include($GLOBALS['repertoire_modele'] . "/bas.php");


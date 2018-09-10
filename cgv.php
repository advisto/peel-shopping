<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: cgv.php 57719 2018-08-14 10:15:25Z sdelaporte $

include("configuration.inc.php");

define('IN_CGV', true);
$GLOBALS['page_name'] = 'cgv';
$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_CGV"];

include($GLOBALS['repertoire_modele'] . "/haut.php");
print_cgv();
include($GLOBALS['repertoire_modele'] . "/bas.php");


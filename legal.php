<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: legal.php 46935 2015-09-18 08:49:48Z gboussin $

include("configuration.inc.php");

define('IN_INFO_LEGALE', true);
$GLOBALS['page_name'] = 'legal';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_LEGAL_INFORMATION'];

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo print_legal(true);
include($GLOBALS['repertoire_modele'] . "/bas.php");


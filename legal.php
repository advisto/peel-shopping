<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: legal.php 35064 2013-02-08 14:16:40Z gboussin $

include("configuration.inc.php");

define('IN_INFO_LEGALE', true);
$page_name = 'legal';

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo print_legal(true);
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
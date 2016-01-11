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
// $Id: index.php 48447 2016-01-11 08:40:08Z sdelaporte $
include("../configuration.inc.php");

$GLOBALS['page_name'] = 'lexique';
define('IN_LEXIQUE', true);

include($GLOBALS['repertoire_modele'] . "/haut.php");

if (!isset($_GET['lettre']) && !isset($_GET['id'])) {
	print_lexique('');
	print_liste_mot('');
} elseif (isset($_GET['lettre']) && !isset($_GET['id'])) {
	print_lexique($_GET['lettre']);
	print_liste_mot($_GET['lettre']);
} elseif (isset($_GET['lettre']) && isset($_GET['id'])) {
	print_lexique($_GET['lettre']);
	print_definition($_GET['id']);
}

include($GLOBALS['repertoire_modele'] . "/bas.php");


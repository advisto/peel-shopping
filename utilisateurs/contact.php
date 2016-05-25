<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: contact.php 49979 2016-05-23 12:29:53Z sdelaporte $
define('IN_CONTACT', true);

include("../configuration.inc.php");

$GLOBALS['page_name'] = 'contact';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_CONTACT_US'];
$output = '';
$output .= handle_contact_form($_POST);

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

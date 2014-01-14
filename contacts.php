<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: contacts.php 39495 2014-01-14 11:08:09Z sdelaporte $

include("configuration.inc.php");

define('IN_CONTACT_US', true);
$page_name = 'contacts';

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo print_contact();
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
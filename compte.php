<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: compte.php 35805 2013-03-10 20:43:50Z gboussin $

include("configuration.inc.php");

if (is_module_profile_active()) {
	include($GLOBALS['fonctionsprofile']);
}

if (!est_identifie()) {
	$_SESSION['session_redirect_after_login'] = get_current_url(true);
	redirect_and_die($GLOBALS['wwwroot'] . '/membre.php');
}

define('IN_COMPTE', true);
$page_name = 'compte';

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo print_compte(true);
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
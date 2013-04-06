<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: newsletter.php 36232 2013-04-05 13:16:01Z gboussin $
include("../configuration.inc.php");
$output = '';
if (vb($_GET['mode']) == 'inscription') {
	$output = newsletter_validation($_POST);
} elseif (vb($_GET['mode']) == 'desinscription' && est_identifie()) {
	$output = desinscription_newsletter(vb($_GET['email']));
} else {
	redirect_and_die($GLOBALS['wwwroot'] . '/membre.php');
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
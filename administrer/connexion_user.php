<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: connexion_user.php 37904 2013-08-27 21:19:26Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users,admin_moderation");

$DOC_TITLE = $GLOBALS['STR_ADMIN_CONNEXION_USER_TITLE'];
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");

$form_error_object = new FormError();

switch (vb($_GET['mode'])) {
	// Recherche
	case "recherche" :
	default :
		echo affiche_recherche_connexion_user($_GET);
		break;
}

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

?>
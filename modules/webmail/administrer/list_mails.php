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
// $Id: list_mails.php 37904 2013-08-27 21:19:26Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users");

include($GLOBALS['dirroot'] . "/modules/webmail/administrer/fonctions.php");

$DOC_TITLE = $GLOBALS["STR_MODULE_WEBMAIL_ADMIN_RECEIVED_LIST_TITLE"];

$form_error_object = new FormError();
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");
switch (vb($_REQUEST['mode'])) {
	case "search":
		if (!empty($_POST)) {
			affiche_list_receveid_mail($_POST, false);
		}
		break;

	case "change_state_mail":
		if (!empty($_POST)) {
			update_state_mail($_POST);
		}
		affiche_list_receveid_mail(false);
		break;
	default:
		affiche_list_receveid_mail(false);
		break;
}

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

?>
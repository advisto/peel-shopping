<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: list_mails.php 47592 2015-10-30 16:40:22Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users");

$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_MODULE_WEBMAIL_ADMIN_RECEIVED_LIST_TITLE"];

$form_error_object = new FormError();
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
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

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


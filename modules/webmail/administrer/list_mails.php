<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: list_mails.php 55792 2018-01-17 11:49:45Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users,admin_users_contact_form,admin_finance,admin_operations,admin_productsline");

$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_MODULE_WEBMAIL_ADMIN_RECEIVED_LIST_TITLE"];

$form_error_object = new FormError();
$output = '';
switch (vb($_REQUEST['mode'])) {
	case "search":
		if (!empty($_POST)) {
			$output .= affiche_list_received_mail($_POST, true);
		}
		break;

	case "export":
		if(function_exists('get_csv_export_from_html_table')) {
			$export = affiche_list_received_mail($_GET, true, 'html_array');
			get_csv_export_from_html_table($export);
		}
		break;

	case "change_state_mail":
		$frm = array_merge($_POST, $_GET);
		$output .= update_state_mail($frm);
		$output .= affiche_list_received_mail(false, true);
		break;
	default:
		$output .= affiche_list_received_mail(false, true);
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


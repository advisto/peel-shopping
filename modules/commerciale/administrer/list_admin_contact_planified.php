<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: list_admin_contact_planified.php 36232 2013-04-05 13:16:01Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users");


$DOC_TITLE = $GLOBALS["STR_MODULE_COMMERCIAL_ADMIN_PLANIFIED_TITLE"];

$form_error_object = new FormError();

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");
switch (vb($_REQUEST['mode'])) {
	// Filtre de recherche
	case 'search':
		if (!empty($_GET)) {
			affiche_list_admin_contact($_GET, false);
		}
		break;
	// Modification de l'état d'une personne à contacter
	case 'modif_etat':
		if (!empty($_GET['id']) && !empty($_GET['etat'])) {
			update_state_contact($_GET);
		}
		affiche_list_admin_contact(false);
		break;
	case 'suppr':
		if (!empty($_POST['form_delete'])) {
			foreach($_POST['form_delete'] as $id) {
				delete_contact($id);
			}
		}
		affiche_list_admin_contact(false);
		break;
	default:
		affiche_list_admin_contact(false);
		break;
}
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

?>
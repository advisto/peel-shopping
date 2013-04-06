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
// $Id: nom_attributs.php 36232 2013-04-05 13:16:01Z gboussin $

define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");


include($GLOBALS['dirroot'] . "/modules/attributs/administrer/fonctions.php");

$DOC_TITLE = $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_TITLE"];
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");

$start = intval(vn($_GET['start'])); // Détermine la variable start (début de page)
$frm = $_POST;

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_nom_attribut($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_nom_attribut($_GET['id'], $frm);
		break;

	case "suppr" :
		supprime_nom_attribut($_GET['id']);
		affiche_liste_nom_attribut($start);
		break;

	case "insere" :
		insere_nom_attribut($_POST);
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_MSG_CREATED_OK"] , vb($_POST['nom_' . $_SESSION["session_langue"]]))))->fetch();
		affiche_liste_nom_attribut($start);
		break;

	case "maj" :
		maj_nom_attribut($_POST['id'], $_POST);
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_MSG_UPDATED_OK"], vn($_POST['id']))))->fetch();
		affiche_liste_nom_attribut($start);
		break;

	default :
		affiche_liste_nom_attribut($start);
		break;
}

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

?>
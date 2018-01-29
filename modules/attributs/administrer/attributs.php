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
// $Id: attributs.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_TITLE"];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$start = intval(vn($_GET['start'])); // Détermine la variable start (début de page)
$form_error_object = new FormError();
$frm = $_POST;

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_attribut($frm, $form_error_object);
		break;

	case "modif" :
		affiche_formulaire_modif_attribut($frm, $form_error_object);
		break;

	case "suppr" :
		supprime_attribut();
		affiche_liste_attribut($frm);
		break;

	case "supprfile" :
		supprime_attribut_image(vn($_GET['id']), $_GET['file']);
		affiche_formulaire_modif_attribut($frm, $form_error_object);
		break;

	case "insere" :
		$frm['image'] = upload('image', false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image']));
		insere_attribut($_GET['attid'], $frm);
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_MSG_OPTION_CREATED_OK"], vb($_POST['descriptif_' . $_SESSION["session_langue"]]))))->fetch();
		affiche_liste_attribut($frm);
		break;

	case "maj" :
		$frm['image'] = upload('image', false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image']));
		maj_attribut($_GET['attid'], $frm);
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_MSG_OPTION_UPDATED_OK"], vn($_POST['id']))))->fetch();
		affiche_formulaire_liste_attribut($_REQUEST['id'], $frm);
		break;

	case "liste" :
	default :
		affiche_liste_attribut($frm);
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


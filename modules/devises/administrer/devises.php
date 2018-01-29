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
// $Id: devises.php 55332 2017-12-01 10:44:06Z sdelaporte $
//

define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage,admin_finance");

$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DEVISES"];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$start = intval(vn($_GET['start'])); // Détermine la variable start (début de page)
$frm=$_POST;

switch (vb($_REQUEST['mode'])) {
	case "update_rates" :
		echo update_currencies_rates(vb($GLOBALS['site_parameters']['code']));
		affiche_liste_devise($start, $frm);
		break;

	case "ajout" :
		affiche_formulaire_ajout_devise($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_devise($_GET['id'], $frm);
		break;

	case "suppr" :
		supprime_devise($_GET['id']);
		affiche_liste_devise($start, $frm);
		break;

	case "insere" :
		insere_devise($_POST);
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_DEVISES_ADMIN_MSG_CREATED_OK"], vn($_POST['devise']))))->fetch();
		affiche_liste_devise($start, $frm);
		break;

	case "maj" :
		maj_devise($_POST['id'], $_POST);
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_DEVISES_ADMIN_MSG_UPDATED_OK"], vn($_POST['id']))))->fetch();
		affiche_liste_devise($start, $frm);
		break;

	default :
		affiche_liste_devise($start, $frm);
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

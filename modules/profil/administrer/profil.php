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
// $Id: profil.php 55332 2017-12-01 10:44:06Z sdelaporte $
//

define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_content");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_MODULE_PROFIL_ADMIN_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$start = intval(vn($_GET['start'])); // Détermine la variable start (début de page)
$form_error_object = new FormError();
$frm = $_POST;

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_profil($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_profil($_GET['id'], $frm);
		break;

    case "supprfile" :
		supprime_fichier_profil(vn($_GET['id']), $_GET['file']);
        affiche_formulaire_modif_profil(vn($_GET['id']), $frm);
        break;

	case "suppr" :
		supprime_profil($_GET['id']);
		affiche_liste_profil($start);
		break;

	case "insere" :
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['document_'.$lng] = upload('document_'.$lng, false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['document_'.$lng]));
		}
		insere_profil($frm);
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_PROFIL_ADMIN_MSG_CREATED_OK'], vb($frm['name']))))->fetch();
		affiche_liste_profil($start);
		break;

	case "maj" :
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['document_'.$lng] = upload('document_'.$lng, false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['document_'.$lng]));
		}
		maj_profil($frm['id'], $frm);
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_PROFIL_ADMIN_MSG_UPDATED_OK'], vn($_POST['id']))))->fetch();
		affiche_liste_profil($start);
		break;

	default :
		affiche_liste_profil($start);
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


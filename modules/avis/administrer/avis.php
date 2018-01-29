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
// $Id: avis.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_webmastering,admin_finance,admin_operations,admin_productsline");

$GLOBALS['DOC_TITLE'] = "Gérer les avis des internautes";
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$start = intval(vn($_GET['start'])); // Détermine la variable start (début de page)

$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "modif" :
		affiche_formulaire_modif_avis($_REQUEST['id'], $frm, $form_error_object);
		break;

	case "ajout" :
		formulaire_ajout_avis($frm, $form_error_object, $_GET['type']);
		break;

	case "insere_avis" :
		$form_error_object->valide_form($frm,
			array('avis' => $GLOBALS['STR_DONT_FORGET_COMMENT'],
				'produit' => $GLOBALS['STR_MODULE_AVIS_ADMIN_PLEASE_SELECT_PRODUCT'],
				'note' => $GLOBALS['STR_DONT_FORGET_NOTE']));
		if (!$form_error_object->count()) {
			$produit = explode("~", $frm["produit"]);
			if (!empty($produit) && !empty($produit[0])) {
				$frm['reference_id'] = $produit[0];
				$frm['titre'] = $produit[1];
				ajout_avis($frm);
				affiche_liste_avis();
			} else {
				echo $form_error_object->text($GLOBALS['STR_MODULE_AVIS_ADMIN_ERR_NOT_ADDED']);
				// affiche_liste_avis();
			}
		} else {
			formulaire_ajout_avis($frm, $form_error_object);
		}
		break;

	case "suppr" :
		supprime_avis($_REQUEST['id']);
		affiche_liste_avis();
		break;

	case "maj" :
		maj_avis($_POST);
		tracert_history_admin(0, 'EDIT_VOTE', 'Edition vote ' . intval(vn($_POST['id'])));
		affiche_liste_avis();
		break;

	default :
		affiche_liste_avis();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


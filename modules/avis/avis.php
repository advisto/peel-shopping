<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: avis.php 43037 2014-10-29 12:01:40Z sdelaporte $
include("../../configuration.inc.php");

if (!is_module_avis_active()) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die($GLOBALS['wwwroot'] . "/");
}

if (!est_identifie()) {
	// User not logged in ==> we redirect to login page.
	$_SESSION['session_redirect_after_login'] = get_current_url(true);
	redirect_and_die($GLOBALS['wwwroot'] . '/membre.php');
}

define('IN_DONNEZ_AVIS', true);
include($GLOBALS['repertoire_modele'] . "/haut.php");

$frm = $_POST;
// Recuperation de la langue session
$frm['langue'] = $_SESSION['session_langue'];
$form_error_object = new FormError();

if (is_module_avis_active() && (!empty($_GET['prodid']) || !empty($_GET['ref']))) {
	// On charge les fonctions d'avis
	include($fonctionsavis);
	if (!empty($_GET['prodid'])) {
		$id = $_GET['prodid'];
		$type = 'produit';
	} elseif ($_GET['ref']) {
		$id = $_GET['ref'];
		$type = 'annonce';
	}
	switch (vb($_REQUEST['mode'])) {
		case "insere" :
			$form_error_object->valide_form($frm, array('avis' => $GLOBALS['STR_DONT_FORGET_COMMENT']));
			if(empty($GLOBALS['site_parameters']['module_avis_no_notation'])) {
				$form_error_object->valide_form($frm,
					array('note' => $GLOBALS['STR_DONT_FORGET_NOTE']));
			}
			if (!$form_error_object->count()) {
				echo insere_avis($frm);
			} else {
				echo formulaire_avis(vb($id), $frm, $form_error_object, $type);
			}
			break;

		default :
			if (!empty($_GET['prodid'])) {
				$id = $_GET['prodid'];
				$type = 'produit';
			} elseif ($_GET['ref']) {
				$id = $_GET['ref'];
				$type = 'annonce';
			}
			echo formulaire_avis(vb($id), $frm, $form_error_object, $type);
			break;
	}
}

include($GLOBALS['repertoire_modele'] . "/bas.php");


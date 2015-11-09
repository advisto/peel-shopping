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
// $Id: avis.php 47592 2015-10-30 16:40:22Z sdelaporte $
if (defined('IN_PEEL')) {
	return;
}
include("../../configuration.inc.php");

if (!check_if_module_active('avis')) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die(get_url('/'));
}

if (!est_identifie()) {
	// User not logged in ==> we redirect to login page.
	$_SESSION['session_redirect_after_login'] = get_current_url(true);
	redirect_and_die(get_url('membre'));
}

define('IN_DONNEZ_AVIS', true);
include($GLOBALS['repertoire_modele'] . "/haut.php");

$frm = $_POST;
// Recuperation de la langue session
$frm['langue'] = $_SESSION['session_langue'];
$form_error_object = new FormError();

if (!empty($_GET['prodid'])) {
	$id = $_GET['prodid'];
	$type = 'produit';
} elseif (!empty($_GET['ref'])) {
	$id = $_GET['ref'];
	$annonce_object = new Annonce($id);
	$type = 'annonce';
} elseif (!empty($_GET['id'])) {
	$id = $_GET['id'];
	$annonce_object = new Annonce($id);
	$type = 'annonce';
} else {
	$id = null;
}
$ad_owner_opinion = false;
if (vb($type) == 'annonce' && $annonce_object->id_utilisateur == vn($_SESSION['session_utilisateur']['id_utilisateur'])) {
	$ad_owner_opinion = true;
}
if (check_if_module_active('avis') && !empty($id)) {
	// On charge les fonctions d'avis
	switch (vb($_REQUEST['mode'])) {
		case "insere" :
			$form_error_object->valide_form($frm, array('avis' => $GLOBALS['STR_DONT_FORGET_COMMENT']));
			if(empty($GLOBALS['site_parameters']['module_avis_no_notation']) && !$ad_owner_opinion) {
				$form_error_object->valide_form($frm,
					array('note' => $GLOBALS['STR_DONT_FORGET_NOTE']));
			}
			if (!$form_error_object->count()) {
				echo insere_avis($frm, $ad_owner_opinion);
			} else {
				echo formulaire_avis($id, $frm, $form_error_object, $type, $ad_owner_opinion);
			}
			break;

		case "edit" :
			if(!empty($GLOBALS['site_parameters']['edit_avis_by_owner']) && !empty($_GET['id'])) {
				$query = query("SELECT a.*,".(!empty($_GET['type']) && $_GET['type']=='annonce' ?'ref':'id_produit')." as item_id
					FROM peel_avis a
					WHERE a.id = '" . intval($_GET['id']) . "' AND a.etat = '1' AND a.id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'");
					$frm = fetch_assoc($query);
					echo formulaire_avis($frm['item_id'], $frm, $form_error_object, $_GET['type'], true);
			}
			break;

		case "suppr" :
			echo delete_avis($_GET['id']);
			break;

		default :
			echo formulaire_avis($id, $frm, $form_error_object, $type, $ad_owner_opinion);
			break;
	}
}

include($GLOBALS['repertoire_modele'] . "/bas.php");
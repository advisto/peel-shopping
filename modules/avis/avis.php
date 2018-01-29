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
if (defined('IN_PEEL')) {
	return;
}
include("../../configuration.inc.php");

if (!check_if_module_active('avis')) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die(get_url('/'));
}
necessite_identification();

define('IN_DONNEZ_AVIS', true);

$output = '';
$frm = $_POST;
// Recuperation de la langue session
$frm['langue'] = $_SESSION['session_langue'];
$form_error_object = new FormError();
$mode = vb($_REQUEST['mode'], 'avis');

if (!empty($_GET['prodid'])) {
	$reference_id = $_GET['prodid'];
	$type = 'produit';
} elseif (!empty($_GET['ref'])) {
	$reference_id = $_GET['ref'];
	$annonce_object = new Annonce($reference_id);
	$type = 'annonce';
} elseif (!empty($_GET['id'])) {
	$id = $_GET['id'];
	$sql_avis = "SELECT ref, id_produit, id_utilisateur
		FROM peel_avis
		WHERE id='".intval($id)."'";
	$query_avis = query($sql_avis);
	if($result_avis = fetch_assoc($query_avis)) {
		if(!empty($result_avis['ref'])) {
			$annonce_object = new Annonce($result_avis['ref']);
		} else {
			$reference_id = $result_avis['id_produit'];
		}
	} else {
		redirect_and_die(get_url('/'));
	}
} else {
	$id = null;
}
if (!empty($reference_id) || !empty($id)) {
	// On charge les fonctions d'avis
	switch (vb($_GET['mode'])) {
		case "edit" :
			if(!empty($id)) {
				$sql_cond = "a.id='" . intval($_GET['id']) . "' AND a.etat='1'";
				if(!empty($GLOBALS['site_parameters']['allow_edit_and_suppr_avis_by_owner'])) {
					// Auteur de l'avis (si autorisé par la configuration du site)
					$sql_cond_array[] = "a.id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'";
				}
				if(!empty($annonce_object) && !empty($GLOBALS['site_parameters']['edit_avis_by_owner'])) {
					// Auteur de l'annonce (si autorisé par la configuration du site)
					$sql_cond_array[] = "a.id_utilisateur = '" . intval($annonce_object->id_utilisateur) . "'";
				}
				if(!empty($sql_cond_array)) {
					$sql_cond .= " AND (". implode(" OR ", $sql_cond_array) . ")";
				}
				$sql = "SELECT a.*, IF(ref>0,ref,id_produit) AS reference_id, IF(ref>0,'annonce','produit') AS type, IF(note>-99,'avis','news') AS mode
					FROM peel_avis a
					WHERE " . $sql_cond;
				$query = query($sql);
				
				if($result = fetch_assoc($query)) {
					if(!empty($_POST)) {
						$frm['type'] = $result['type'];
						$frm['mode'] = $result['mode'];
						$frm['id'] = $result['id'];
						$form_error_object->valide_form($frm, array('avis' => $GLOBALS['STR_DONT_FORGET_COMMENT']));
						if(empty($GLOBALS['site_parameters']['module_avis_no_notation']) && intval(vn($frm['note']))!=-99) {
							$form_error_object->valide_form($frm,
								array('note' => $GLOBALS['STR_DONT_FORGET_NOTE']));
						}
						if (!$form_error_object->count()) {
							$output .= insere_avis($frm);
						} else {
							$output .= formulaire_avis($result['reference_id'], $frm, $form_error_object, $result['type'], $result['mode'], null, vn($_GET['campaign_id']));
						}
					} else {
						$output .= formulaire_avis($result['reference_id'], $result, $form_error_object, $result['type'], $result['mode'], null, vn($_GET['campaign_id']));
					}
				}
			}
			break;

		case "suppr" :
			$output .= delete_avis($_GET['id']);
			break;

		default :
			// mode news ou avis
			if(!empty($_POST)) {
				$form_error_object->valide_form($frm, array('avis' => $GLOBALS['STR_DONT_FORGET_COMMENT']));
				if(empty($GLOBALS['site_parameters']['module_avis_no_notation']) && intval(vn($frm['note']))!=-99) {
					$form_error_object->valide_form($frm,
						array('note' => $GLOBALS['STR_DONT_FORGET_NOTE']));
				}
				if (!$form_error_object->count()) {
					$frm['mode'] = $mode;
					$output .= insere_avis($frm);
				} else {
					$output .= formulaire_avis($reference_id, $frm, $form_error_object, $type, $mode, null, vn($_GET['campaign_id']));
				}
			} else {
				$output .= formulaire_avis($reference_id, $frm, $form_error_object, $type, $mode, null, vn($_GET['campaign_id']));
			}
			break;
	}
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");
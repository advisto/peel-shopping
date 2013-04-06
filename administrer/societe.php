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
// $Id: societe.php 36232 2013-04-05 13:16:01Z gboussin $

define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$DOC_TITLE = $GLOBALS['STR_ADMIN_SOCIETE_TITLE'];
$frm = $_POST;
$form_error_object = new FormError();

include("modeles/haut.php");

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		afficher_formulaire_ajout_societe();
		break;

	case "modif" :
		affiche_formulaire_modif_societe(intval($_GET['id']), $frm);
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_societe($frm);
			affiche_formulaire_modif_societe(1, $frm);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_societe(intval($frm['id']), $frm);
		}
		break;

	default :
		affiche_formulaire_modif_societe(1, $frm);
		break;
}

include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vide pour ajouter un nouvel societe
 * Initialise les valeurs par défauts pour un formulaire vide
 *
 * @return
 */
function afficher_formulaire_ajout_societe()
{
	if(empty($frm)) {
		$frm['societe'] = "";
		$frm['nom_' . $_SESSION['session_langue']] = "";
		$frm['prenom'] = "";
		$frm['adresse1'] = "";
		$frm['adresse2'] = "";
		$frm['telephone'] = "";
		$frm['telephone2'] = "";
		$frm['fax'] = "";
		$frm['fax2'] = "";
		$frm['code_postal'] = "";
		$frm['code_postal2'] = "";
		$frm['ville'] = "";
		$frm['ville2'] = "";
		$frm['pays'] = "";
		$frm['pays2'] = "";
		$frm['siren'] = "";
		$frm['tvaintra'] = "";
		$frm['code_banque'] = "";
		$frm['code_guichet'] = "";
		$frm['numero_compte'] = "";
		$frm['cle_rib'] = "";
		$frm['titulaire'] = "";
		$frm['numero_compte'] = "";
		$frm['cnil'] = "";
		$frm['iban'] = "";
		$frm['swift'] = "";
		$frm['fax'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['titre_soumet'] = $GLOBALS['STR_ADMIN_SOCIETE_UPDATE'];

	include("modeles/societe_form.php");
}

/**
 * Affiche un formulaire vide pour modifier un societe
 *
 * @param integer $id
 * @param array $frm
 * @return
 */
function affiche_formulaire_modif_societe($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Récupère les informations de la societe */
		$qid = query("SELECT *
			FROM peel_societe
			WHERE id = '" . intval($id) . "'");
		if (num_rows($qid) > 0) {
			$frm = fetch_assoc($qid);
		} else {
			$frm = array();
		}
	}

	$frm['nouveau_mode'] = "maj";
	$frm['titre_soumet'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	include("modeles/societe_form.php");
}

/**
 * maj_societe()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function maj_societe($frm)
{
	query("UPDATE  peel_societe SET
		 societe = '" . nohtml_real_escape_string($frm['societe']) . "'
		, prenom = '" . nohtml_real_escape_string($frm['prenom']) . "'
		, nom = '" . nohtml_real_escape_string($frm['nom']) . "'
		, tel = '" . nohtml_real_escape_string($frm['tel']) . "'
		, fax = '" . nohtml_real_escape_string($frm['fax']) . "'
		, tel2 = '" . nohtml_real_escape_string($frm['tel2']) . "'
		, fax2 = '" . nohtml_real_escape_string($frm['fax2']) . "'
		, email = '" . nohtml_real_escape_string($frm['email']) . "'
		, adresse = '" . nohtml_real_escape_string($frm['adresse']) . "'
		, adresse2 = '" . nohtml_real_escape_string($frm['adresse2']) . "'
		, code_postal = '" . nohtml_real_escape_string($frm['code_postal']) . "'
		, ville = '" . nohtml_real_escape_string($frm['ville']) . "'
		, pays = '" . nohtml_real_escape_string($frm['pays']) . "'
		, code_postal2 = '" . nohtml_real_escape_string($frm['code_postal2']) . "'
		, ville2 = '" . nohtml_real_escape_string($frm['ville2']) . "'
		, pays2 = '" . nohtml_real_escape_string($frm['pays2']) . "'
		, siren = '" . nohtml_real_escape_string($frm['siren']) . "'
		, tvaintra = '" . nohtml_real_escape_string($frm['tvaintra']) . "'
		, siteweb = '" . nohtml_real_escape_string($frm['siteweb']) . "'
		, code_banque = '" . nohtml_real_escape_string($frm['code_banque']) . "'
		, code_guichet = '" . nohtml_real_escape_string($frm['code_guichet']) . "'
		, numero_compte = '" . nohtml_real_escape_string($frm['numero_compte']) . "'
		, cle_rib = '" . nohtml_real_escape_string($frm['cle_rib']) . "'
		, titulaire = '" . nohtml_real_escape_string(String::strtoupper($frm['titulaire'])) . "'
		, domiciliation = '" . nohtml_real_escape_string(String::strtoupper($frm['domiciliation'])) . "'
		, cnil = '" . nohtml_real_escape_string($frm['cnil']) . "'
		, iban = '" . nohtml_real_escape_string($frm['iban']) . "'
		, swift= '" . nohtml_real_escape_string($frm['swift']) . "'
	WHERE id = '" . intval($frm['id']) . "'");
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_SOCIETE_MSG_UPDATED_OK']))->fetch();
}

?>
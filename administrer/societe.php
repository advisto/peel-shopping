<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: societe.php 38682 2013-11-13 11:35:48Z gboussin $

define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$DOC_TITLE = $GLOBALS['STR_ADMIN_SOCIETE_TITLE'];
$frm = $_POST;
$form_error_object = new FormError();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

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

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vide pour ajouter une nouvelle société
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

	affiche_formulaire_societe($frm);
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

	affiche_formulaire_societe($frm);
}


/**
 * Affiche le formulaire pour modifier un societe
 *
 * @param array $frm
 * @return
 */
function affiche_formulaire_societe(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_societe_form.tpl');
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vb($frm['id']))));
	$tpl->assign('mode', vb($frm['nouveau_mode']));
	$tpl->assign('id', intval(vb($frm['id'])));
	$tpl->assign('societe', vb($frm['societe']));
	$tpl->assign('prenom', vb($frm['prenom']));
	$tpl->assign('nom', vb($frm['nom']));
	$tpl->assign('email', vb($frm['email']));
	$tpl->assign('siteweb', vb($frm['siteweb']));
	$tpl->assign('tel', vb($frm['tel']));
	$tpl->assign('fax', vb($frm['fax']));
	$tpl->assign('siren', vb($frm['siren']));
	$tpl->assign('tvaintra', vb($frm['tvaintra']));
	$tpl->assign('cnil', vb($frm['cnil']));
	$tpl->assign('adresse', vb($frm['adresse']));
	$tpl->assign('code_postal', vb($frm['code_postal']));
	$tpl->assign('ville', vb($frm['ville']));
	$tpl->assign('pays', vb($frm['pays']));
	$tpl->assign('code_banque', vb($frm['code_banque']));
	$tpl->assign('code_guichet', vb($frm['code_guichet']));
	$tpl->assign('numero_compte', vb($frm['numero_compte']));
	$tpl->assign('cle_rib', vb($frm['cle_rib']));
	$tpl->assign('iban', vb($frm['iban']));
	$tpl->assign('swift', vb($frm['swift']));
	$tpl->assign('titulaire', vb($frm['titulaire']));
	$tpl->assign('domiciliation', vb($frm['domiciliation']));
	$tpl->assign('pays2', vb($frm['pays2']));
	$tpl->assign('ville2', vb($frm['ville2']));
	$tpl->assign('adresse2', vb($frm['adresse2']));
	$tpl->assign('code_postal2', vb($frm['code_postal2']));
	$tpl->assign('tel2', vb($frm['tel2']));
	$tpl->assign('fax2', vb($frm['fax2']));
	$tpl->assign('titre_soumet', vb($frm['titre_soumet']));
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_SOCIETE_FORM_COMPANY_PARAMETERS', $GLOBALS['STR_ADMIN_SOCIETE_FORM_COMPANY_PARAMETERS']);
	$tpl->assign('STR_ADMIN_SOCIETE_FORM_EXPLAIN', $GLOBALS['STR_ADMIN_SOCIETE_FORM_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SOCIETE_FORM_SECOND_ADDRESS', $GLOBALS['STR_ADMIN_SOCIETE_FORM_SECOND_ADDRESS']);
	$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
	$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
	$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
	$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
	$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
	$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
	$tpl->assign('STR_COMPANY', $GLOBALS['STR_COMPANY']);
	$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
	$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
	$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
	$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
	$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
	$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
	$tpl->assign('STR_SIREN', $GLOBALS['STR_SIREN']);
	$tpl->assign('STR_VAT_INTRACOM', $GLOBALS['STR_VAT_INTRACOM']);
	$tpl->assign('STR_CNIL_NUMBER', $GLOBALS['STR_CNIL_NUMBER']);
	$tpl->assign('STR_BANK_ACCOUNT_CODE', $GLOBALS['STR_BANK_ACCOUNT_CODE']);
	$tpl->assign('STR_BANK_ACCOUNT_RIB', $GLOBALS['STR_BANK_ACCOUNT_RIB']);
	$tpl->assign('STR_BANK_ACCOUNT_COUNTER', $GLOBALS['STR_BANK_ACCOUNT_COUNTER']);
	$tpl->assign('STR_BANK_ACCOUNT_NUMBER', $GLOBALS['STR_BANK_ACCOUNT_NUMBER']);
	$tpl->assign('STR_IBAN', $GLOBALS['STR_IBAN']);
	$tpl->assign('STR_SWIFT', $GLOBALS['STR_SWIFT']);
	$tpl->assign('STR_ACCOUNT_MASTER', $GLOBALS['STR_ACCOUNT_MASTER']);
	$tpl->assign('STR_BANK_ACCOUNT_DOMICILIATION', $GLOBALS['STR_BANK_ACCOUNT_DOMICILIATION']);
	echo $tpl->fetch();
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


/**
 * Affiche la liste de sociétés (CODE INUTILISE - A ADAPTER SI ON VEUT L'UTILISER)
 *
 * @param array $frm Array with all fields data
 * @return
 *
function liste_societe($frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_societe_liste.tpl');

	$tpl_results = array();
	while ($r = fetch_object($qid)) {
		$tpl_results[] = array('href' => get_current_url(false) . '?mode=modif&id=' . $r->id,
			'societe' => $r->societe,
			'email' => $r->email
			);
	}
	$tpl->assign('results', $tpl_results);
	$tpl1->assign('STR_ADMIN_SOCIETE_LIST_TITLE', $GLOBALS['STR_ADMIN_SOCIETE_LIST_TITLE']);
	$tpl1->assign('STR_COMPANY', $GLOBALS['STR_COMPANY']);
	$tpl1->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
	$tpl1->assign('STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND', $GLOBALS['STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND']);
	echo $tpl->fetch();
}
*/
?>
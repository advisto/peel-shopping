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
// $Id: societe.php 55332 2017-12-01 10:44:06Z sdelaporte $

define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$GLOBALS['DOC_TITLE'] = (empty($_GET['type'])?$GLOBALS['STR_ADMIN_SOCIETE_TITLE']:$GLOBALS['STR_ADMIN_DISTRIBUTORS_TITLE']);
$frm = $_POST;
$form_error_object = new FormError();

if (!isset($_REQUEST['mode']) && !empty($_SESSION['session_admin_multisite']) && empty($_GET['type'])) {
	
	$all_sites_name_array = get_all_sites_name_array();
	if (count($all_sites_name_array) == 1) {
		// Si il y a qu'un site configuré, on affiche directement la page de modification du site numéro défini par $_SESSION['session_admin_multisite'], sinon le site 1
		if(isset($_SESSION['session_admin_multisite'])) {
			redirect_and_die(get_current_url(false) . "?mode=modif&site_id=" . $_SESSION['session_admin_multisite']);
		} else {
			redirect_and_die(get_current_url(false) . "?mode=modif&id=1");
		}
	}
}
$output = '';
switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		$output .= afficher_formulaire_ajout_societe();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= insere_societe($frm);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CONFIGURATION_MSG_CREATED'], vb($frm['nom_' . $_SESSION["session_langue"]]))))->fetch();
			$output .= liste_societe($frm, vb($_GET['type']));
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= afficher_formulaire_ajout_societe($frm);
		}
		break;

	case "modif" :
		if (!empty($_GET['site_id'])) {
			$query = query("SELECT id
				FROM peel_societe 
				WHERE site_id = " . intval($_GET['site_id']));
			if($result = fetch_assoc($query)) {
				$id = $result['id'];
			}
		} elseif(!empty($_GET['id'])) {
			$id = $_GET['id'];
		}
		$output .= affiche_formulaire_modif_societe(intval($id), $frm);
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= maj_societe($frm);
			$output .= affiche_formulaire_modif_societe(1, $frm);
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_modif_societe(intval($frm['id']), $frm);
		}
		break;

	case "suppr" :
		$sql = "DELETE FROM peel_societe 
			WHERE id = " . intval($_GET['id']);
		query($sql);
		$output .= liste_societe($frm, vb($_GET['type']));
		break;
		
	case "supprfile" :
		supprime_fichier_societe(vn($_REQUEST['id']), $_GET['file'], vn($_REQUEST['lang']));
		$output .= affiche_formulaire_modif_societe(intval($frm['id']), $frm);
		break;
		
	default :
		$output .= liste_societe($frm, vb($_GET['type']));
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
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
		$frm['societe_type'] = "";
		$frm['id_marque'] = "";
		$frm['site_id'] = "";
		$frm['site_country'] =  vb($GLOBALS['site_parameters']['site_country_allowed_array'], array());
	}
	$frm['nouveau_mode'] = "insere";
	$frm['titre_soumet'] = $GLOBALS['STR_ADMIN_SOCIETE_UPDATE'];

	return affiche_formulaire_societe($frm);
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
	if(empty($frm)) {
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Récupère les informations de la societe */
		$qid = query("SELECT *
			FROM peel_societe
			WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('societe', null, true) . "");
		if ($frm = fetch_assoc($qid)) {
			$frm['site_country'] = explode(',', vb($frm['site_country']));
			$frm['id_marques'] = explode(',', vb($frm['id_marques']));
		} else {
			$frm = array();
		}
	}

	if (!empty($frm)) {
		$frm['nouveau_mode'] = "maj";
		$frm['titre_soumet'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		return affiche_formulaire_societe($frm);
	} else {
		redirect_and_die(get_current_url(false).'?mode=ajout');
	}
}

/**
 * Affiche le formulaire pour modifier un societe
 *
 * @param array $frm
 * @return
 */
function affiche_formulaire_societe(&$frm)
{
	$tpl_image = $GLOBALS['tplEngine']->createTemplate('uploaded_file.tpl');
	// On ne supprime pas l'image si on clique sur effacer, car l'image vient peut-être d'une duplication
	$file_infos = get_uploaded_file_infos('logo', vb($frm['logo']),'javascript:reinit_upload_field("logo","[DIV_ID]");');
	$tpl_image->assign('f', $file_infos);
	$tpl_image->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl_image->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	// $tpl_image->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
	$this_upload_html = $tpl_image->fetch();
	
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_societe_form.tpl');
	$tpl->assign('action', get_current_url(false).(empty($_GET['type'])?"":"?mode=modif&type=distributor&id=".intval(vb($frm['id']))));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vb($frm['id']))));
	$tpl->assign('STR_IMAGE', $GLOBALS['STR_IMAGE']);
	$tpl->assign('image', $this_upload_html);
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
	$tpl->assign('societe_type', vb($frm['societe_type']));
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('site_country_checkboxes', get_site_country_checkboxes(vb($frm['site_country'], array())));
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
	}
	$tpl->assign('STR_ADMIN_SOCIETE_TYPE', $GLOBALS['STR_ADMIN_SOCIETE_TYPE']);
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
	
	if(!empty($_GET['type'])) {
		//On récupère les marques pour pouvoir les associer à une adresse de société
		$tpl_marques_options = array();
		$select = query("SELECT id, nom_" . $_SESSION['session_langue'] . ", etat
		   FROM peel_marques
		   WHERE " . get_filter_site_cond('marques', null, true) . "
		   ORDER BY position, nom_" . $_SESSION['session_langue'] . " ASC");
		while ($nom = fetch_assoc($select)) {
			$tpl_marques_options[] = array('value' => intval($nom['id']),
				'issel' => in_array($nom['id'], vb($frm['id_marques'])),
				'name' => $nom['nom_' . $_SESSION['session_langue']] . (empty($nom['etat'])?' ('.$GLOBALS["STR_ADMIN_DEACTIVATED"].')':'')
				);
		}
		$tpl->assign('marques_options', $tpl_marques_options);
		$tpl->assign('distributor', vb($_GET['type']));
		$tpl->assign('STR_ADMIN_PRODUITS_CHOOSE_BRAND', $GLOBALS['STR_ADMIN_DISTRIBUTOR_PRODUITS_CHOOSE_BRAND']);
		
		//on récupére les pays pour le select multiple des distributeurs
		$sql_pays = 'SELECT id, pays_' . $_SESSION['session_langue'] . ', etat
			FROM peel_pays
			WHERE 1 AND ' . get_filter_site_cond('pays') . '
			ORDER BY position, pays_' . $_SESSION['session_langue'];

		$res_pays = query($sql_pays);
		while ($country = fetch_assoc($res_pays)) {
			$tpl_country_options[] = array('value' => intval($country['id']),
				'issel' => in_array($country['id'], vb($frm['site_country'])),
				'name' => $country['pays_' . $_SESSION['session_langue']] . (empty($country['etat'])?' ('.$GLOBALS["STR_ADMIN_DEACTIVATED"].')':'')
				);
		}
		$tpl->assign('tpl_country_options', $tpl_country_options);
	}
	return  $tpl->fetch();
}

/**
 * maj_societe()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function maj_societe($frm)
{ 
	// get_filter_site_cond('configuration') => Ne pas utiliser le paramètre $use_admin_rights, il savoir si des informations sur la société relative au site $frm['site_id'] existe. Les droits de l'administrateur qui fait la demande ne sont pas à prendre en compte.
	$frm['image'] = upload('logo', false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image_']));
	$sql_select = "SELECT *
		FROM peel_societe
		WHERE " . get_filter_site_cond('societe', null, false, $frm['site_id'], true) . "
		LIMIT 1";
	$query = query($sql_select);
	if ($r = fetch_object($query)) {
		$update = true;
	} else {
		$update = false;
	}
	$sql = "SET
			 societe = '" . nohtml_real_escape_string(vb($frm['societe'])) . "'
			, prenom = '" . nohtml_real_escape_string(vb($frm['prenom'])) . "'
			, nom = '" . nohtml_real_escape_string(vb($frm['nom'])) . "'
			, tel = '" . nohtml_real_escape_string(vb($frm['tel'])) . "'
			, fax = '" . nohtml_real_escape_string(vb($frm['fax'])) . "'
			, tel2 = '" . nohtml_real_escape_string(vb($frm['tel2'])) . "'
			, fax2 = '" . nohtml_real_escape_string(vb($frm['fax2'])) . "'
			, email = '" . nohtml_real_escape_string(vb($frm['email'])) . "'
			, adresse = '" . nohtml_real_escape_string(vb($frm['adresse'])) . "'
			, adresse2 = '" . nohtml_real_escape_string(vb($frm['adresse2'])) . "'
			, code_postal = '" . nohtml_real_escape_string(vb($frm['code_postal'])) . "'
			, ville = '" . nohtml_real_escape_string(vb($frm['ville'])) . "'
			, pays = '" . nohtml_real_escape_string(vb($frm['pays'])) . "'
			, code_postal2 = '" . nohtml_real_escape_string(vb($frm['code_postal2'])) . "'
			, ville2 = '" . nohtml_real_escape_string(vb($frm['ville2'])) . "'
			, pays2 = '" . nohtml_real_escape_string(vb($frm['pays2'])) . "'
			, siren = '" . nohtml_real_escape_string(vb($frm['siren'])) . "'
			, tvaintra = '" . nohtml_real_escape_string(vb($frm['tvaintra'])) . "'
			, siteweb = '" . nohtml_real_escape_string(vb($frm['siteweb'])) . "'
			, code_banque = '" . nohtml_real_escape_string(vb($frm['code_banque'])) . "'
			, code_guichet = '" . nohtml_real_escape_string(vb($frm['code_guichet'])) . "'
			, numero_compte = '" . nohtml_real_escape_string(vb($frm['numero_compte'])) . "'
			, cle_rib = '" . nohtml_real_escape_string(vb($frm['cle_rib'])) . "'
			, titulaire = '" . nohtml_real_escape_string(StringMb::strtoupper(vb($frm['titulaire']))) . "'
			, domiciliation = '" . nohtml_real_escape_string(StringMb::strtoupper(vb($frm['domiciliation']))) . "'
			, cnil = '" . nohtml_real_escape_string(vb($frm['cnil'])) . "'
			, iban = '" . nohtml_real_escape_string(vb($frm['iban'])) . "'
			, swift = '" . nohtml_real_escape_string(vb($frm['swift'])) . "'
			, logo = '" . nohtml_real_escape_string(vb($frm['image'])) . "'
			, id_marques = '" . nohtml_real_escape_string(implode(',',vb($frm['id_marques'], array()))) . "'
			, societe_type = '" . nohtml_real_escape_string($frm['societe_type']) . "'
			, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= "
			, site_country = '" . real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'
		";
	}
	if($update) {
		$sql = "UPDATE peel_societe " . $sql . "
			WHERE id = '" . intval($frm['id']) . "' AND " . get_filter_site_cond('societe', null, true);
	} else {
		// A FAIRE : Revérifier les droits sur site_id par l'administration avant de faire l'insertion
		$sql = "INSERT INTO peel_societe " . $sql . "";
	}
	query($sql);
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_SOCIETE_MSG_UPDATED_OK']))->fetch();
}/**
 * insere_societe()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_societe($frm)
{ 
	$sql = "INSERT INTO peel_societe SET
			 societe = '" . nohtml_real_escape_string(vb($frm['societe'])) . "'
			, prenom = '" . nohtml_real_escape_string(vb($frm['prenom'])) . "'
			, nom = '" . nohtml_real_escape_string(vb($frm['nom'])) . "'
			, tel = '" . nohtml_real_escape_string(vb($frm['tel'])) . "'
			, fax = '" . nohtml_real_escape_string(vb($frm['fax'])) . "'
			, tel2 = '" . nohtml_real_escape_string(vb($frm['tel2'])) . "'
			, fax2 = '" . nohtml_real_escape_string(vb($frm['fax2'])) . "'
			, email = '" . nohtml_real_escape_string(vb($frm['email'])) . "'
			, adresse = '" . nohtml_real_escape_string(vb($frm['adresse'])) . "'
			, adresse2 = '" . nohtml_real_escape_string(vb($frm['adresse2'])) . "'
			, code_postal = '" . nohtml_real_escape_string(vb($frm['code_postal'])) . "'
			, ville = '" . nohtml_real_escape_string(vb($frm['ville'])) . "'
			, pays = '" . nohtml_real_escape_string(vb($frm['pays'])) . "'
			, code_postal2 = '" . nohtml_real_escape_string(vb($frm['code_postal2'])) . "'
			, ville2 = '" . nohtml_real_escape_string(vb($frm['ville2'])) . "'
			, pays2 = '" . nohtml_real_escape_string(vb($frm['pays2'])) . "'
			, siren = '" . nohtml_real_escape_string(vb($frm['siren'])) . "'
			, tvaintra = '" . nohtml_real_escape_string(vb($frm['tvaintra'])) . "'
			, siteweb = '" . nohtml_real_escape_string(vb($frm['siteweb'])) . "'
			, code_banque = '" . nohtml_real_escape_string(vb($frm['code_banque'])) . "'
			, code_guichet = '" . nohtml_real_escape_string(vb($frm['code_guichet'])) . "'
			, numero_compte = '" . nohtml_real_escape_string(vb($frm['numero_compte'])) . "'
			, cle_rib = '" . nohtml_real_escape_string(vb($frm['cle_rib'])) . "'
			, titulaire = '" . nohtml_real_escape_string(StringMb::strtoupper(vb($frm['titulaire']))) . "'
			, domiciliation = '" . nohtml_real_escape_string(StringMb::strtoupper(vb($frm['domiciliation']))) . "'
			, cnil = '" . nohtml_real_escape_string(vb($frm['cnil'])) . "'
			, iban = '" . nohtml_real_escape_string(vb($frm['iban'])) . "'
			, swift = '" . nohtml_real_escape_string(vb($frm['swift'])) . "'
			, id_marques = '" . nohtml_real_escape_string(implode(',',vb($frm['id_marques'], array()))) . "'
			, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= "
			, site_country = '" . real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'
		";
	}
	query($sql);
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_SOCIETE_MSG_UPDATED_OK']))->fetch();
}

/**
 * Affiche la liste de sociétés (CODE INUTILISE - A ADAPTER SI ON VEUT L'UTILISER)
 *
 * @param array $frm Array with all fields data
 * @return
 */
function liste_societe($frm, $type = 0)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_societe_liste.tpl');

	$tpl_results = array();
	if(empty($type)) {
		$where = " AND id_marques = 0";
	} else {
		$where = " AND id_marques > 0";
	}
	$query = query("SELECT *
		FROM peel_societe
		WHERE " . get_filter_site_cond('societe', null, true) . $where ." 
		ORDER BY id DESC");
	while ($r = fetch_object($query)) {
		$site_country_array = array();
		if (!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			if(StringMb::strlen($r->site_country)>0) {
				foreach(explode(',', $r->site_country) as $this_id) {
					$site_country_array[] = ($this_id == 0? $GLOBALS['STR_OTHER']:get_country_name($this_id));
				}
			}
		} 
		
		$tpl_results[] = array(
			'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $r->id . (empty($_GET['type'])?"":"&type=distributor") ,
			'modif_href' => get_current_url(false) . '?mode=modif&id=' . $r->id . (empty($_GET['type'])?"":"&type=distributor"),
			'societe' => $r->societe,
			'email' => $r->email,
			'site_name' => get_site_name($r->site_id),
			'site_country' => implode(', ', $site_country_array)
			);
	}
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout'.(empty($_GET['type'])?"":"&type=distributor"));
	$tpl->assign('results', $tpl_results);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_SOCIETE_LIST_TITLE', (empty($_GET['type'])?$GLOBALS['STR_ADMIN_SOCIETE_LIST_TITLE']:$GLOBALS['STR_ADMIN_DISTRIBUTORS_LIST_TITLE']));
	$tpl->assign('STR_COMPANY', $GLOBALS['STR_COMPANY']);
	$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
	$tpl->assign('STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND', $GLOBALS['STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND']);
	$tpl->assign('STR_ADMIN_ADD', $GLOBALS['STR_ADMIN_ADD']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
	}
	return $tpl->fetch();
}

/**
 * Supprime l'image de la cociété spécifiée par $id
 *
 * @param integer $id
 * @param mixed $file
 * @param string $lang
 * @return
 */
function supprime_fichier_societe($id, $file, $lang)
{
	/* Charge les infos du produit. */
	switch ($file) {
		case "logo":
			$sql = "SELECT logo
				FROM peel_societe
				WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('societe', null, true) . "";
			$res = query($sql);
			$file = fetch_assoc($res);
			query("UPDATE peel_societe
				SET logo = ''
				WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('societe', null, true) . "");
			break;
	}
	delete_uploaded_file_and_thumbs($file['logo']);
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_FILE_DELETED'], $file['image_' . $lang])))->fetch();
}

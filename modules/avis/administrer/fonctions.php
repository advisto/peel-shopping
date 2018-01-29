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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Renvoie les éléments de menu affichables
 *
 * @param array $params
 * @return
 */
function avis_hook_admin_menu_items($params) {
	$results = array();
	if (a_priv('admin_webmastering,admin_finance,admin_operations,admin_productsline', true)) {
		$results['menu_items']['webmastering_marketing'][$GLOBALS['wwwroot_in_admin'] . '/modules/avis/administrer/avis.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_OPINIONS"];
	}
	return $results;
}

/**
 * Affiche le formulaire de modification de avis
 *
 * @param integer $id
 * @return
 */
function affiche_formulaire_modif_avis($id, &$frm, &$form_error_object)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les infos de l'avis. */
		$qid = query("SELECT *
			FROM peel_avis
			WHERE id = " . intval($id));
		$frm = fetch_assoc($qid);
		if(empty($frm['pseudo']) && !empty($frm['id_utilisateur'])) {
			if ($user_infos = get_user_information($frm['id_utilisateur'])) {
				$frm['pseudo'] = $user_infos['pseudo'];
			}
		}
	}
	$frm["nouveau_mode"] = "maj";
	$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	if (!empty($frm['id_produit'])) {
		affiche_formulaire_avis($frm, $form_error_object, 'produit');
	} elseif (!empty($frm['ref'])) {
		affiche_formulaire_avis($frm, $form_error_object, 'annonce');
	}
}

/**
 * Supprime l'avis spécifiée par $id
 *
 * @param integer $id
 * @return
 */
function supprime_avis($id)
{
	/* Réaffecte toutes les sous-avis de cet avis à l'avis parente */
	query("DELETE FROM peel_avis WHERE id = " . intval($id));
	$message = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MODULE_AVIS_ADMIN_MSG_DELETED_OK']))->fetch();

	echo $message;
}

/**
 * affiche_liste_avis()
 *
 * @return
 */
function affiche_liste_avis()
{
	$sql = "SELECT *
		FROM peel_avis";
	
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avisAdmin_liste.tpl');

	$Links = new Multipage($sql, 'avis');
	$HeaderTitlesArray = array($GLOBALS["STR_ADMIN_ACTION"], 'id' => $GLOBALS["STR_REFERENCE"], 'nom_produit' => $GLOBALS["STR_ADMIN_SUBJECT"], 'note' => $GLOBALS["STR_ADMIN_NOTE"], 'datestamp' => $GLOBALS["STR_DATE"], 'date_validation' => $GLOBALS["STR_VALIDATION_DATE"], 'etat' => $GLOBALS["STR_STATUS"], 'email' => $GLOBALS["STR_BY"], $GLOBALS["STR_ADMIN_WEBSITE"]);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = 'datestamp';
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();

	$tpl->assign('is_annonce_module_active', check_if_module_active('annonces'));
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_prod_href', get_current_url(false) . '?mode=ajout&type=produit');
	$tpl->assign('add_annonce_href', get_current_url(false) . '?mode=ajout&type=annonce');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl->assign('star_src', get_url('/images/star1.gif'));

	$tpl_results = array();
	if (!empty($results_array)) {
		$i = 0;
		foreach ($results_array as $this_result) {
			$site_id = null;
			$reference_url = null;
			if (!empty($this_result['id_produit'])) {
				$product_object = new Product($this_result['id_produit'], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
				if(!empty($product_object->id)) {
					$site_id = $product_object->site_id;
					$reference_url = $product_object->get_product_url();
				}
				unset($product_object);
			} elseif (!empty($this_result['ref'])) {
				$GLOBALS['site_parameters']['multisite_disable'] = true;
				$annonce_object = new Annonce($this_result['ref']);
				$GLOBALS['site_parameters']['multisite_disable'] = false;
				if(!empty($annonce_object->ref)) {
					$site_id = vb($annonce_object->site_id);
					$reference_url = $annonce_object->get_annonce_url();
				}
				unset($annonce_object);
			}
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $this_result['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $this_result['id'],
				'reference' => $this_result['id'],
				'nom' => $this_result['nom_produit'],
				'reference_url' => $reference_url,
				'note' => $this_result['note'],
				'type' => ($this_result['note']>-99?$GLOBALS['STR_POSTED_OPINIONS']:$GLOBALS["STR_MODULE_AVIS_POSTED_NEWS"]),
				'date' => get_formatted_date($this_result['datestamp'], 'short', 'long'),
				'date_validation' => get_formatted_date($this_result['date_validation'], 'short', 'long'),
				'etat_onclick' => 'change_status("avis", "' . $this_result['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($this_result['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				'util_href' => $GLOBALS['administrer_url'] . '/utilisateurs.php?id_utilisateur=' . $this_result['id_utilisateur'] . '&mode=modif',
				'prenom' => $this_result['prenom'],
				'email' => $this_result['email'],
				'site_name' => get_site_name($site_id)
				);
			$i++;
		}
	}
	$tpl->assign('results', $tpl_results);
	$tpl->assign('links_header_row', $Links->getHeaderRow());
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_MODULE_AVIS_ADMIN_LIST', $GLOBALS['STR_MODULE_AVIS_ADMIN_LIST']);
	$tpl->assign('STR_MODULE_AVIS_ADMIN_ADD_ON_PRODUCT', $GLOBALS['STR_MODULE_AVIS_ADMIN_ADD_ON_PRODUCT']);
	$tpl->assign('STR_MODULE_AVIS_ADMIN_ADD_ON_AD', $GLOBALS['STR_MODULE_AVIS_ADMIN_ADD_ON_AD']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_REFERENCE', $GLOBALS['STR_REFERENCE']);
	$tpl->assign('STR_ADMIN_SUBJECT', $GLOBALS['STR_ADMIN_SUBJECT']);
	$tpl->assign('STR_ADMIN_NOTE', $GLOBALS['STR_ADMIN_NOTE']);
	$tpl->assign('STR_DATE', $GLOBALS['STR_DATE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_BY', $GLOBALS['STR_BY']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_MODULE_AVIS_ADMIN_UPDATE', $GLOBALS['STR_MODULE_AVIS_ADMIN_UPDATE']);
	$tpl->assign('STR_MODULE_AVIS_ADMIN_NOTHING_FOUND', $GLOBALS['STR_MODULE_AVIS_ADMIN_NOTHING_FOUND']);
	echo $tpl->fetch();
}

/**
 * affiche_formulaire_avis()
 *
 * @return
 */
function affiche_formulaire_avis(&$frm, &$form_error_object, $type)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avisAdmin_formulaire.tpl');
	$tpl->assign('star_src', get_url('/images/star1.gif'));
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('type', $type);
	$tpl->assign('modif_href', $GLOBALS['administrer_url'] . '/utilisateurs.php?id_utilisateur=' . $frm['id_utilisateur'] . '&mode=modif');
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('nom_produit', $frm['nom_produit']);
	$tpl->assign('email', $frm['email']);
	$tpl->assign('pseudo', $frm['pseudo']);
	$tpl->assign('etat', $frm['etat']);
	$tpl->assign('avis', $frm['avis']);
	$tpl->assign('note', $frm['note']);
	$tpl->assign('note_max', $GLOBALS['site_parameters']['rating_max_value']);
	$tpl->assign('titre_soumet', $frm['titre_soumet']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_MODULE_AVIS_ADMIN_FORM_TITLE', $GLOBALS['STR_MODULE_AVIS_ADMIN_FORM_TITLE']);
	$tpl->assign('STR_BY', $GLOBALS['STR_BY']);
	$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_AVIS', $GLOBALS['STR_ADMIN_AVIS']);
	$tpl->assign('STR_ADMIN_NOTE', $GLOBALS['STR_ADMIN_NOTE']);
	$tpl->assign('STR_MODULE_AVIS_POSTED_NEWS', $GLOBALS['STR_MODULE_AVIS_POSTED_NEWS']);
	echo $tpl->fetch();
}

/**
 * formulaire_ajout_avis()
 *
 * @return
 */
function formulaire_ajout_avis(&$frm, &$form_error_object, $type)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avisAdmin_formulaire_ajout.tpl');
	$tpl->assign('star_src', get_url('/images/star1.gif'));
	$tpl->assign('type', $type);
	$tpl->assign('action', get_current_url(true));
	$tpl->assign('prenom', vb($_SESSION['session_utilisateur']['prenom']));
	$tpl->assign('nom_famille', vb($_SESSION['session_utilisateur']['nom_famille']));
	$tpl->assign('email', vb($_SESSION['session_utilisateur']['email']));
	$tpl->assign('pseudo', vb($frm['pseudo']));
	$tpl->assign('avis', vb($frm['avis']));
	$tpl->assign('note', vb($frm['note']));
	$tpl->assign('error_avis', $form_error_object->text('avis'));
	$tpl->assign('error_note', $form_error_object->text('note'));
	$tpl->assign('id_utilisateur', intval($_SESSION['session_utilisateur']['id_utilisateur']));
	$tpl->assign('langue', $_SESSION['session_langue']);
	$tpl->assign('note_max', $GLOBALS['site_parameters']['rating_max_value']);
	if ($type == 'produit') { 
		$tpl->assign('is_product_select_list', product_select_list() != "");
		if(product_select_list() != "") {
			$tpl->assign('product_error', $form_error_object->text('produit'));
			$tpl->assign('product_select_list', product_select_list(vb($frm["produit"])));
		}
		$tpl->assign('STR_MODULE_AVIS_ADMIN_NO_PRODUCT_FOUND', $GLOBALS['STR_MODULE_AVIS_ADMIN_NO_PRODUCT_FOUND']);
	} elseif ($type == 'annonce') {
		$tpl->assign('is_annonce_select_list', annonce_select_list() != "");
		if(annonce_select_list() != "") {
			$tpl->assign('annonce_error', $form_error_object->text('annonce'));
			$tpl->assign('annonce_select_list', annonce_select_list(vb($frm["produit"])));
		}
		$tpl->assign('STR_MODULE_ANNONCES_AD', $GLOBALS['STR_MODULE_ANNONCES_AD']);
		$tpl->assign('STR_MODULE_ANNONCES_ADMIN_NO_AD_FOUND', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_NO_AD_FOUND']);
	}
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_YOU_ARE', $GLOBALS['STR_YOU_ARE']);
	$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
	$tpl->assign('STR_YOUR_OPINION', $GLOBALS['STR_YOUR_OPINION']);
	$tpl->assign('STR_REMINDING_CHAR', $GLOBALS['STR_REMINDING_CHAR']);
	$tpl->assign('STR_YOUR_NOTE', $GLOBALS['STR_YOUR_NOTE']);
	$tpl->assign('STR_SEND', $GLOBALS['STR_SEND']);
	$tpl->assign('STR_MODULE_AVIS_ADMIN_FORM_TITLE', $GLOBALS['STR_MODULE_AVIS_ADMIN_FORM_TITLE']);
	$tpl->assign('STR_MODULE_AVIS_ADMIN_GIVE_OPINION', $GLOBALS['STR_MODULE_AVIS_ADMIN_GIVE_OPINION']);
	$tpl->assign('STR_BY', $GLOBALS['STR_BY']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_AVIS', $GLOBALS['STR_ADMIN_AVIS']);
	$tpl->assign('STR_ADMIN_NOTE', $GLOBALS['STR_ADMIN_NOTE']);
	$tpl->assign('STR_PRODUCT', $GLOBALS['STR_PRODUCT']);
	$tpl->assign('STR_MODULE_AVIS_POSTED_NEWS', $GLOBALS['STR_MODULE_AVIS_POSTED_NEWS']);
	
	echo $tpl->fetch();
}

/*
 * Construction du select des produits
 */

function product_select_list($default = null)
{
	$output = "";
	$sql = "SELECT id,nom_" . $_SESSION['session_langue'] . " AS nom 
		FROM peel_produits
		WHERE " . get_filter_site_cond('produits', null, true) . "
		LIMIT 1000";
	$qid = query($sql);
	if (num_rows($qid) > 0) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avisAdmin_product_select_list.tpl');
		$tpl_options = array();
		while ($result = fetch_assoc($qid)) {
			$value = $result["id"] . "~" . $result["nom"];
			$tpl_options[] = array('value' => $value,
				'issel' => vb($default) == $value,
				'name' => $result["nom"]
				);
		}
		$tpl->assign('options', $tpl_options);
		$tpl->assign('select_product_txt', $GLOBALS['STR_MODULE_AVIS_ADMIN_SELECT_PRODUCT']);
		$output .= $tpl->fetch();
	}
	return $output;
}

/*
 * Construction du select des annonces
 */
function annonce_select_list($default = null)
{
	$output = "";
	$sql = "SELECT ref, titre_".$_SESSION['session_langue']." AS titre
		FROM peel_lot_vente
		WHERE " . get_filter_site_cond('lot_vente', null, true) . "
		LIMIT 1000";
	$qid = query($sql);
	if (num_rows($qid) > 0) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avisAdmin_product_select_list.tpl');
		$tpl_options = array();
		while ($result = fetch_assoc($qid)) {
			$value = $result["ref"] . "~" . $result["titre"];
			$tpl_options[] = array('value' => $value,
				'issel' => vb($default) == $value,
				'name' => $result["titre"]
				);
		}
		$tpl->assign('options', $tpl_options);
		$tpl->assign('select_product_txt', $GLOBALS['STR_MODULE_AVIS_ADMIN_SELECT_AD']);
		$output .= $tpl->fetch();
	}

	return $output;
}

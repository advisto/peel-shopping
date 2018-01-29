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
// $Id: marques.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_MARQUES_TITLE'];
$frm = $_POST;
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_marque($frm, $form_error_object);
		break;

	case "modif" :
		affiche_formulaire_modif_marque($_REQUEST['id'], $frm, $form_error_object);
		break;

	case "suppr" :
		supprime_marque($_REQUEST['id']);
		affiche_formulaire_liste_marque($_REQUEST['id'], $frm);
		break;

	case "supprfile" :
		supprime_fichier_marque(vn($_REQUEST['id']), $_GET['file']);
		affiche_formulaire_modif_marque(vn($_REQUEST['id']), $frm, $form_error_object);
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$_POST['image'] = upload('image', false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($_POST['image']));
			insere_sous_marque($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_MARQUES_BRAND_CREATED'], vb($frm['nom_' . $_SESSION["session_langue"]]))))->fetch();
			affiche_formulaire_liste_marque($_REQUEST['id'], $frm);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_marque($frm, $form_error_object);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$_POST['image'] = upload('image', false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($_POST['image']));
			maj_marque($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_MARQUES_BRAND_UPDATED'], vn($_POST['id']))))->fetch();
			affiche_formulaire_liste_marque($_REQUEST['id'], $frm);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_marque(vn($_REQUEST['id']), $frm, $form_error_object);
		}
		break;

	default :
		affiche_formulaire_liste_marque($_REQUEST['id'] = 0, $frm);
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */
/**
 * Affiche un formulaire de marques vide
 *
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_ajout_marque(&$frm, &$form_error_object)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['placeholder_' . $lng] = "Marque langue $lng";
			$frm['description_' . $lng] = "";

			/* gestion des meta */
			$frm['meta_titre_' . $lng] = "";
			$frm['meta_key_' . $lng] = "";
			$frm['meta_desc_' . $lng] = "";
		}
		/* gestion des promotions sur les marques */
		if (check_if_module_active('marques_promotion')) {
			$frm["promotion_devises"] = "";
			$frm["promotion_percent"] = "";
		}
		$frm["etat"] = "";
		$frm['position'] = "";
	}
	$frm["nouveau_mode"] = "insere";
	$frm["id"] = "";
	$frm["image"] = "";
	$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_MARQUES_ADD_BRAND'];
	$frm['site_id'] = "";

	affiche_formulaire_marque($frm, $form_error_object);
}

/**
 * Affiche le formulaire de modification de marques.
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_modif_marque($id, &$frm, &$form_error_object)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les infos de la marques. */
		$qid = query("SELECT *
			FROM peel_marques
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('marques', null, true));
		if ($frm = fetch_assoc($qid)) {
			if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
				$frm['site_country'] = explode(',', vb($frm['site_country']));
			}
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_MARQUES_NO_BRAND_FOUND']))->fetch();
			return false;
		}
	}
	$frm["nouveau_mode"] = "maj";
	$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
	/* Affiche la liste des marques, en présélectionnant la marques choisie. */
	affiche_formulaire_marque($frm, $form_error_object);
}

/**
 * Supprime la marques spécifiée par $id, et déplace tous les produits sous
 * cette marques au parent immédiat.
 *
 * @param integer $id
 * @return
 */
function supprime_marque($id)
{
	/* Trouve le parent de cette marques */
	$qid = query("SELECT nom_" . $_SESSION['session_langue'] . " AS name 
		FROM peel_marques 
		WHERE id = " . intval($id) . " AND " . get_filter_site_cond('marques', null, true));
	if ($this_brand = fetch_assoc($qid)) {
		/* efface cette marque */
		query("DELETE FROM peel_marques WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('marques', null, true));
		$message = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_MARQUES_MSG_BRAND_DELETED_OK'], StringMb::html_entity_decode_if_needed($this_brand['name']))))->fetch();
		echo $message;
	}
}

/**
 * insere_sous_marque()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_sous_marque(&$frm)
{
	// Remplit les contenus vides
	$frm = fill_other_language_content($frm);
	
	$sql = "INSERT INTO peel_marques (
		image
		, site_id
		, etat
		, position
		, date_insere
		, date_maj";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= ", site_country
		";
	}	
	if(!empty($GLOBALS['site_parameters']['admin_save_name_modify_or_create_content'])) {
		$sql .= "
		, nom_insere
		, nom_maj";
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . ", description_" . $lng;
		$sql .= ", meta_titre_" . $lng;
		$sql .= ", meta_key_" . $lng;
		$sql .= ", meta_desc_" . $lng;
	}
	$sql .= ", promotion_devises, promotion_percent
	) VALUES (
		'" . nohtml_real_escape_string($frm['image']) . "'
		, '" . nohtml_real_escape_string(get_site_id_sql_set_value(vb($frm['site_id']))) . "'
		, '" . intval(vn($frm['etat'])) . "'
		, '" . intval($frm['position']) . "'	
		, '" . date('Y-m-d H:i:s', time()) . "'	
		, '" . date('Y-m-d H:i:s', time()) . "'";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= ", '" . real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'
		";
	}
	if(!empty($GLOBALS['site_parameters']['admin_save_name_modify_or_create_content'])) {
		$sql .= "
		, '" . nohtml_real_escape_string($_SESSION['session_utilisateur']['prenom'] . ' ' . $_SESSION['session_utilisateur']['nom_famille']) . "'
		, '" . nohtml_real_escape_string($_SESSION['session_utilisateur']['prenom'] . ' ' . $_SESSION['session_utilisateur']['nom_famille']) . "'";
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
		$sql .= ", '" . real_escape_string($frm['description_' . $lng]) . "'";
		$sql .= ", '" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'";
		$sql .= ", '" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'";
		$sql .= ", '" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'";
	}
	$sql .= ",'" . floatval(get_float_from_user_input(vn($frm['promotion_devises']))) . "'
	,'" . floatval(get_float_from_user_input(vn($frm['promotion_percent']))) . "'
	)";

	$qid = query($sql);
}

/**
 * maj_marque()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function maj_marque(&$frm)
{
	if (vn($frm['promotion_devises']) > 0 || vn($frm['promotion_percent']) > 0) {
		// Va afficher tous les produits de la marque sur la page Promotion du site.
		$on_promo = 1;
	} else {
		$on_promo = 0;
	}
	
	// Remplit les contenus vides
	$frm = fill_other_language_content($frm);
	
	$product_field_names = get_table_field_names('peel_produits');
	if(in_array('on_promo', $product_field_names)) {
		$sql_promo = "UPDATE peel_produits 
			SET on_promo='".intval($on_promo)."' 
			WHERE id_marque='" . intval($_POST['id']) . "' AND " . get_filter_site_cond('produits', null, true) . "";
		if(empty($on_promo) && in_array('promotion', $product_field_names)) {
			// On ne retire on_promo que si le produit lui-même n'a pas de promotion
			$sql_promo .= " AND promotion=0";
		}
		query($sql_promo);
	}
	// On met à jour tous les droits par pays des produits liés à cette marque
	if(!empty($_REQUEST['update_product_countries_submit']) && !empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql_site_country = "UPDATE peel_produits 
			SET site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "', site_country = '" . real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'
			WHERE id_marque='" . intval($_POST['id']) . "' AND " . get_filter_site_cond('produits', null, true) . "";
		query($sql_site_country);
		
		if(function_exists('brand_article_association_rebuild')) {
			brand_article_association_rebuild();
	}
	}
	$sql = "UPDATE peel_marques
		SET image = '" . nohtml_real_escape_string($frm['image']) . "'
		, date_maj = '" . date('Y-m-d H:i:s', time()) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . "='" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
		$sql .= ", description_" . $lng . "='" . real_escape_string($frm['description_' . $lng]) . "'";
		$sql .= ", meta_titre_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'";
		$sql .= ", meta_key_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'";
		$sql .= ", meta_desc_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'";
	}
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= ", site_country = '" . real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'
		";
	}
	if(!empty($GLOBALS['site_parameters']['admin_save_name_modify_or_create_content'])) {
		$sql .= "
		, nom_maj = '" . nohtml_real_escape_string($_SESSION['session_utilisateur']['prenom'] . ' ' . $_SESSION['session_utilisateur']['nom_famille']) . "'";
	}
	$sql .= ", etat = '" . vn($frm['etat']) . "'
			, position = '" . intval($frm['position']) . "'
			, promotion_devises = '" . floatval(vn($frm['promotion_devises'])) . "'
			, promotion_percent = '" . floatval(vn($frm['promotion_percent'])) . "'
			, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		WHERE id = '" . intval($_POST['id']) . "' AND " . get_filter_site_cond('marques', null, true);
	$qid = query($sql);
}

/**
 * Affiche un formulaire de marque vide
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_liste_marque($id, &$frm)
{
	/* Valeurs par défaut */
	$frm = array();
	$frm["nouveau_mode"] = "insere";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$frm['nom_' . $lng] = "";
		$frm['description_' . $lng] = "";
	}

	$frm["image"] = "";
	$frm["etat"] = "";
	$frm['position'] = "";
	$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_MARQUES_ADD_BRAND'];

	// Affiche la liste des marques, en présélectionnant la marque choisie.
	affiche_liste_marque($frm);
}

/**
 * affiche_liste_marque()
 *
 * @return
 */
function affiche_liste_marque(&$frm)
{
	$sql = "SELECT m.*
		FROM peel_marques m
		WHERE " . get_filter_site_cond('marques', 'm', true) . "";
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_marque.tpl');

	$Links = new Multipage($sql, 'marques');
	$HeaderTitlesArray = array($GLOBALS["STR_ADMIN_ACTION"], 'id' => $GLOBALS["STR_ADMIN_ID"], 'image' => $GLOBALS["STR_IMAGE"], 'nom_' . $_SESSION['session_langue'] => $GLOBALS["STR_BRAND"], 'position' => $GLOBALS["STR_ADMIN_POSITION"], 'etat' => $GLOBALS["STR_STATUS"], 'site_id' => $GLOBALS["STR_ADMIN_WEBSITE"]);
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
		$HeaderTitlesArray['site_country'] = $GLOBALS["STR_ADMIN_SITE_COUNTRY"];
	}
	$Links->OrderDefault =  vb($GLOBALS['site_parameters']['brand_in_admin_sort_list'], 'position') ;
	$Links->SortDefault = "ASC";
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$results_array = $Links->Query();

	$tpl->assign('href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');

	if (!empty($results_array)) {
		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $this_brand) {
			$tmpLigne = array('tr_rollover' => tr_rollover($i, true),
				'nom' => $this_brand['nom_' . $_SESSION['session_langue']],
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $this_brand['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $this_brand['id'],
				'id' => $this_brand['id'],
				'img_src' => thumbs($this_brand['image'], 80, 50, 'fit', null, null, true, true),
				'position' => $this_brand['position'],
				'site_name' => get_site_name($this_brand['site_id']),
				'etat_onclick' => 'change_status("marques", "' . $this_brand['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($this_brand['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif')
				);
			if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
				$tmpLigne['site_country'] = get_country_name($this_brand['site_country']);
			}
			$tpl_results[] = $tmpLigne;
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('links_header_row', $Links->getHeaderRow());
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_MARQUES_TITLE', $GLOBALS['STR_ADMIN_MARQUES_TITLE']);
	$tpl->assign('STR_ADMIN_MARQUES_ADD_BRAND', $GLOBALS['STR_ADMIN_MARQUES_ADD_BRAND']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
	$tpl->assign('STR_IMAGE', $GLOBALS['STR_IMAGE']);
	$tpl->assign('STR_BRAND', $GLOBALS['STR_BRAND']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_MARQUES_UPDATE', $GLOBALS['STR_ADMIN_MARQUES_UPDATE']);
	$tpl->assign('STR_ADMIN_MARQUES_NOTHING_FOUND', $GLOBALS['STR_ADMIN_MARQUES_NOTHING_FOUND']);
	echo $tpl->fetch();
}

/**
 * affiche_formulaire_marque()
 *
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_marque(&$frm, &$form_error_object)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_marque.tpl');
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('position', vb($frm["position"]));
	$tpl->assign('etat', vb($frm["etat"]));

	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'error' => $form_error_object->text('nom_' . $lng),
			'nom' => vb($frm['nom_' . $lng]),
			'placeholder' => vb($frm['placeholder_' . $lng]),
			'description_te' => getTextEditor('description_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['description_' . $lng]))),
			'meta_titre' => $frm['meta_titre_' . $lng],
			'meta_key' => $frm['meta_key_' . $lng],
			'meta_desc' => $frm['meta_desc_' . $lng],
			);
	}
	$tpl->assign('langs', $tpl_langs);

	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	if (!empty($frm["image"])) {
		$tpl->assign('image', get_uploaded_file_infos('image', $frm['image'], get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=image'));
	}
	$tpl->assign('is_marque_promotion_module_active', check_if_module_active('marques_promotion'));
	if (check_if_module_active('marques_promotion')) {
		$tpl->assign('promotion_devises', $frm["promotion_devises"]);
		$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
		$tpl->assign('promotion_percent', $frm["promotion_percent"]);
	}
	$tpl->assign('titre_soumet', $frm["titre_soumet"]);
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('site_country_checkboxes', get_site_country_checkboxes(vb($frm['site_country'], array())));
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
	}
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_MARQUES_FORM_TITLE', $GLOBALS['STR_ADMIN_MARQUES_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_DESCRIPTION', $GLOBALS['STR_ADMIN_DESCRIPTION']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_META_TITLE', $GLOBALS['STR_ADMIN_META_TITLE']);
	$tpl->assign('STR_ADMIN_META_KEYWORDS', $GLOBALS['STR_ADMIN_META_KEYWORDS']);
	$tpl->assign('STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN', $GLOBALS['STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_META_DESCRIPTION', $GLOBALS['STR_ADMIN_META_DESCRIPTION']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_IMAGE', $GLOBALS['STR_IMAGE']);
	$tpl->assign('STR_ADMIN_FILE_NAME', $GLOBALS['STR_ADMIN_FILE_NAME']);
	$tpl->assign('STR_ADMIN_DELETE_IMAGE', $GLOBALS['STR_ADMIN_DELETE_IMAGE']);
	$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_ADMIN_MARQUES_DISCOUNT_ON_BRAND', $GLOBALS['STR_ADMIN_MARQUES_DISCOUNT_ON_BRAND']);
	echo $tpl->fetch();
}

/**
 * Supprime le produit spécifié par $id. Il faut supprimer le produit
 * puis les entrées correspondantes de la table produits_marques.
 *
 * @param integer $id
 * @param string $file
 * @return
 */
function supprime_fichier_marque($id, $file)
{
	// Charge les infos du produit.
	switch ($file) {
		case "image":
			$sql = "SELECT image 
				FROM peel_marques 
				WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('marques', null, true);
			$res = query($sql);
			$file = fetch_assoc($res);
			query("UPDATE peel_marques 
				SET image = '' 
				WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('marques', null, true));
			break;
	}
	delete_uploaded_file_and_thumbs($file['image']);
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_FILE_DELETED'], $file['image'])))->fetch();
}


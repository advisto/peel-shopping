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
// $Id: categories.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv('admin_products,admin_finance');

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_CATEGORIES_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		if (!empty($_POST['on_child'])) {
			// On veut mettre à jour les catégories filles
			update_category_sons_promotions($_REQUEST['id'], $_POST['promotion_devises'], $_POST['promotion_percent']);
		}
		affiche_formulaire_ajout_produits_categorie(vn($_REQUEST['id']), $frm);
		break;

	case "modif" :
		affiche_formulaire_modif_produits_categorie($_REQUEST['id'], $frm);
		break;

	case "suppr" :
		supprime_produits_categorie($_REQUEST['id']);
		affiche_formulaire_liste_produits_categorie($_REQUEST['id']);
		break;

	case "supprfile" :
		supprime_fichier_categorie(vn($_REQUEST['id']), $_GET['file'], vn($_REQUEST['lang']));
		affiche_formulaire_modif_produits_categorie(vn($_REQUEST['id']), $frm);
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_categorie($_POST);
			affiche_formulaire_liste_produits_categorie(0);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_produits_categorie(vn($_REQUEST['id']), $frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_produits_categorie($_REQUEST['id'], $_POST);
			if (!empty($_POST['on_child'])) {
				// On veut mettre à jour les catégories filles
				update_category_sons_promotions($_REQUEST['id'], $_POST['promotion_devises'], $_POST['promotion_percent']);
			}
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_MSG_CHANGES_OK'], $_POST['id'])))->fetch();
			affiche_formulaire_liste_produits_categorie($_REQUEST['id']);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_produits_categorie($_REQUEST['id'], $frm);
		}
		break;

	case "modif_etat" :
		if (isset($_GET['position']) && !empty($_GET['id'])) {
			$qid = query("SELECT *
				FROM peel_categories
				WHERE id = " . intval($_GET['id'])." AND " . get_filter_site_cond('categories', null, true) . "");
			if ($result = fetch_assoc($qid)) {
				// On intervertit les positions si une autre catégorie a la même position
				$qid = query('UPDATE peel_categories
					SET position="' . intval($result['position']) . '"
					WHERE parent_id="' . intval($result['parent_id']) . '" AND position="' . intval($_GET['position']) . '" AND ' . get_filter_site_cond('categories', null, true) . '');
			}
			query('UPDATE peel_categories
				SET position="' . intval($_GET['position']) . '"
				WHERE id="' . intval($_GET['id']) . '" AND ' . get_filter_site_cond('categories', null, true) . '');
		}
		affiche_formulaire_liste_produits_categorie($_REQUEST['id']);
		break;

	default :
		affiche_formulaire_liste_produits_categorie(0);
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Met à jour les promotions des catégories filles d'une categorie par récursivité
 *
 * @param integer $parent_id
 * @param float $promotion_devises
 * @param float $promotion_percent
 * @return
 */
function update_category_sons_promotions($parent_id, $promotion_devises = 0, $promotion_percent = 0)
{
	$sql = "SELECT c.id AS categorie_id
		FROM peel_categories c
		WHERE c.parent_id='" . intval($parent_id) . "' AND " . get_filter_site_cond('categories', 'c', true) . "
		ORDER BY c.id ASC";
	$qid = query($sql);

	while ($cat = fetch_assoc($qid)) {
		$sql = "UPDATE peel_categories
			SET promotion_devises='" . floatval(get_float_from_user_input($promotion_devises)) . "', promotion_percent='" . floatval(get_float_from_user_input($promotion_percent)) . "'
			WHERE id=" . intval($cat['categorie_id']." AND " . get_filter_site_cond('categories', null, true) . "");
		query($sql);
		update_category_sons_promotions($cat['categorie_id'], $promotion_devises, $promotion_percent);
	}
}

/**
 * affiche_arbo_categorie()
 *
 * @param mixed $sortie
 * @param mixed $selectionne
 * @param integer $parent_id
 * @param string $indent
 * @param integer $first_line
 * @param integer $depth
 * @return
 */
function affiche_arbo_categorie(&$sortie, $selectionne, $parent_id = 0, $indent = "", $first_line = 0, $depth = 1)
{
	static $tpl;

	$sql = "SELECT c.id, c.reference, c.nom_" . $_SESSION['session_langue'] . ", c.etat, c.site_id, c.position, c.nb, c.image_" . $_SESSION['session_langue'] . "";
	if (check_if_module_active('category_promotion')) {
		$sql .= ", c.promotion_devises, c.promotion_percent";
	}
	$sql .= ' FROM peel_categories c
		WHERE c.parent_id = "' . intval($parent_id) . '" AND ' . get_filter_site_cond('categories', 'c', true) . '
		ORDER BY c.position' . (!empty($GLOBALS['site_parameters']['category_primary_order_by'])? ", c." . $GLOBALS['site_parameters']['category_primary_order_by']  : '') . '';

	$qid = query($sql);
	while ($cat = fetch_assoc($qid)) {
		if(empty($tpl)){
			$tpl = $GLOBALS['tplEngine']->createTemplate('admin_arbo_categorie.tpl');
		}
		if ($cat['image_' . $_SESSION['session_langue']] != "") {
			$tpl->assign('image', array('src' => thumbs($cat['image_' . $_SESSION['session_langue']], 80, 50, 'fit', null, null, true, true),
					'name' => $cat['image_' . $_SESSION['session_langue']]
					));
		}else{
			$tpl->assign('image', null);
		}
		$tpl->assign('tr_rollover', tr_rollover($first_line, true));
		$tpl->assign('ajout_cat_href', get_current_url(false) . "?mode=ajout&id=" . $cat['id']);
		$tpl->assign('ajout_cat_src', $GLOBALS['administrer_url'] . '/images/rubrique-24.gif');
		$tpl->assign('ajout_prod_href', $GLOBALS['administrer_url'] . '/produits.php?mode=ajout&categories=' . $cat['id']);
		$tpl->assign('ajout_prod_src', $GLOBALS['administrer_url'] . '/images/prod-cat-24.gif');
		$tpl->assign('sup_cat_href', get_current_url(false) . "?mode=suppr&id=" . $cat['id']);
		$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
		$tpl->assign('cat_id', $cat['id']);
		$tpl->assign('indent', $indent);
		$tpl->assign('modif_href', get_current_url(false) . "?mode=modif&id=" . $cat['id']);
		$tpl->assign('cat_nom', (!empty($cat['nom_' . $_SESSION['session_langue']])?$cat['nom_' . $_SESSION['session_langue']]:'['.$cat['id'].']'));
		$tpl->assign('nb', $cat['nb']);
		$tpl->assign('site_name', get_site_name($cat['site_id']));
		if (check_if_module_active('category_promotion')) {
			$tpl->assign('promotion', array('percent' => number_format($cat['promotion_percent'], 2),
					'prix' => fprix($cat['promotion_devises'], true, $GLOBALS['site_parameters']['code'], false)
					));
		}else{
			$tpl->assign('promotion', null);
		}

		$tpl->assign('depth', $depth);
		$tpl->assign('up_src', $GLOBALS['administrer_url'] . '/images/up.gif');
		$tpl->assign('desc_src', $GLOBALS['administrer_url'] . '/images/desc.gif');
		$tpl->assign('cat_position', $cat['position']);
		if ($cat['position'] > 1) {
			$tpl->assign('up_href', get_current_url(false) . '?mode=modif_etat&id=' . $cat['id'] . '&position=' . ($cat['position'] - 1));
		}else{
			$tpl->assign('up_href', null);
		}
		$tpl->assign('desc_href', get_current_url(false) . '?mode=modif_etat&id=' . $cat['id'] . '&position=' . ($cat['position'] + 1));
		$tpl->assign('etat_onclick', 'change_status("categories", "' . $cat['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")');
		$tpl->assign('modif_src', $GLOBALS['administrer_url'] . '/images/' . (empty($cat['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'));
		$tpl->assign('STR_ADMIN_LEVEL', $GLOBALS['STR_ADMIN_LEVEL']);
		$tpl->assign('STR_NUMBER', $GLOBALS['STR_NUMBER']);
		$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
		$tpl->assign('STR_ADMIN_CATEGORIES_ADD_SUBCATEGORY', $GLOBALS['STR_ADMIN_CATEGORIES_ADD_SUBCATEGORY']);
		$tpl->assign('STR_ADMIN_CATEGORIES_ADD_PRODUCT', $GLOBALS['STR_ADMIN_CATEGORIES_ADD_PRODUCT']);
		$tpl->assign('STR_ADMIN_CATEGORIES_DELETE_CATEGORY', $GLOBALS['STR_ADMIN_CATEGORIES_DELETE_CATEGORY']);
		$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
		if(isset($_GET['nb'])){
			if(intval($cat['nb']) == intval($_GET['nb'])) {
				$sortie .= $tpl->fetch();
			}
		} else {
			$sortie .= $tpl->fetch();
		}
		$first_line++;
		if ($cat['id'] != $parent_id) {
			$first_line = affiche_arbo_categorie($sortie, $selectionne, $cat['id'], $indent . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $first_line, $depth + 1);
		}
	}
	return $first_line;
}

/**
 * affiche_formulaire_ajout_produits_categorie()
 *
 * @param integer $id
 * @param array $frm
 * @return
 */
function affiche_formulaire_ajout_produits_categorie($id, &$frm)
{
	if(empty($frm)) {
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
			$frm['description_' . $lng] = "";
			$frm['meta_titre_' . $lng] = "";
			$frm['meta_key_' . $lng] = "";
			$frm['meta_desc_' . $lng] = "";
			$frm['header_html_' . $lng] = "";
			$frm['image_' . $lng] = "";
			$frm['sentence_displayed_on_product_' . $lng] = "";
		}
		if (!empty($GLOBALS['site_parameters']['categorie_weight_enable'])) {
			$frm['poids'] = 0;
		}
		$frm['position'] = 0;
		$frm['etat'] = "0";
		$frm['on_special'] = "";
		$frm['technical_code'] = "";
		$frm['on_carrousel'] = "";
		$frm['allow_show_all_sons_products'] = "";
		$frm['background_menu'] = $frm['background_color'] = "#";
		$frm['type_affichage'] = 0;
		if (check_if_module_active('category_promotion')) {
			$frm['promotion_devises'] = 0;
			$frm['promotion_percent'] = 0;
			$frm['on_child'] = 0;
		}
	}
	$frm['parent_id'] = $id;
	$frm['site_id'] = '';
	$frm['site_country'] =  vb($GLOBALS['site_parameters']['site_country_allowed_array'], array());
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_CATEGORIES_FORM_ADD_BUTTON'];

	affiche_formulaire_produits_categorie($frm);
}

/**
 * affiche_formulaire_modif_produits_categorie()
 *
 * @param integer $id
 * @param array $frm
 * @return
 */
function affiche_formulaire_modif_produits_categorie($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		$qid = query("SELECT *
			FROM peel_categories
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('categories', null, true) . "");
		if ($frm = fetch_assoc($qid)) {
			$frm['site_country'] = explode(',', vb($frm['site_country']));
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CATEGORIES_ERR_NOT_FOUND'], $id)))->fetch();
			return false;
		}
	}
	$frm["nouveau_mode"] = "maj";
	$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_CATEGORIES_FORM_MODIFY'];

	affiche_formulaire_produits_categorie($frm);
}

/**
 * supprime_produits_categorie()
 *
 * @param integer $id
 * @return
 */
function supprime_produits_categorie($id)
{
	$qid = query("SELECT cat.nom_" . $_SESSION['session_langue'] . " AS category_name, cat.parent_id, parent_id.nom_" . $_SESSION['session_langue'] . " AS parent_category_name
		FROM peel_categories cat
		LEFT JOIN peel_categories parent_id ON parent_id.id = cat.parent_id
		WHERE cat.id = " . intval($id) . " AND " . get_filter_site_cond('categories', 'cat', true) . "");
	if ($cat = fetch_assoc($qid)) {
		query("UPDATE peel_produits_categories
			SET categorie_id = '" . intval($cat["parent_id"]) . "'
			WHERE categorie_id = '" . intval($id) . "'");
		query("UPDATE peel_categories
			SET parent_id = '" . $cat["parent_id"] . "'
			WHERE parent_id = '" . intval($id) . "' AND " . get_filter_site_cond('categories', null, true) . "");
		$message = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CATEGORIES_MSG_DELETED'], StringMb::html_entity_decode_if_needed($cat['category_name']), StringMb::html_entity_decode_if_needed($cat["parent_category_name"]))))->fetch();
	} else {
		$message = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CATEGORIES_ERR_NOT_FOUND'], $id)))->fetch();
	}
	query("DELETE FROM peel_categories WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('categories', null, true) . "");
	echo $message;
}

/**
 * insere_categorie()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_categorie(&$frm)
{
	if (!empty($frm['nom_' . $_SESSION['session_langue']])) {
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['image_' . $lng] = upload('image_' . $lng, false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image_' . $lng]));
			$frm['image_header_' . $lng] = upload('image_header_' . $lng, false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image_header_' . $lng]));
		}
		
		// Remplit les contenus vides
		$frm = fill_other_language_content($frm, 'categories');
	
		$sql = 'INSERT INTO peel_categories (parent_id
			, etat
			, on_special
			, technical_code
			, date_insere
			, date_maj';
		if(!empty($GLOBALS['site_parameters']['admin_save_name_modify_or_create_content'])) {
			$sql .= "
			, nom_insere
			, nom_maj";
		}
		$sql .= '
			, on_carrousel
			, allow_show_all_sons_products
			, position
			, type_affichage
			, background_menu
			, background_color
			, site_id';
		if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			$sql .= ", site_country
			";
		}
		if(!empty($GLOBALS['site_parameters']['categorie_weight_enable'])) {
			$sql .= ", poids
			";
		}
		if (check_if_module_active('category_promotion')) {
			$sql .= ', promotion_devises
			, promotion_percent
			, on_child';
		}
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$sql .= "
			, alpha_" . $lng . "
			, nom_" . $lng . "
			, description_" . $lng . '
			, meta_titre_' . $lng . '
			, meta_key_' . $lng . '
			, meta_desc_' . $lng . '
			, image_' . $lng . '
			, image_header_' . $lng . '
			, header_html_' . $lng . ' 
			, sentence_displayed_on_product_' . $lng;
		}
		$sql .= ") VALUES (" . intval($frm['parent_id']) . "
			, '" . intval($frm['etat']) . "'
			, '" . intval(vn($frm['on_special'])) . "'
			, '" . nohtml_real_escape_string(vb($frm['technical_code'])) . "'
			, '" . date('Y-m-d H:i:s', time()) . "'
			, '" . date('Y-m-d H:i:s', time()) . "'";
		if(!empty($GLOBALS['site_parameters']['admin_save_name_modify_or_create_content'])) {
			$sql .= "
			, '" . nohtml_real_escape_string($_SESSION['session_utilisateur']['prenom'] . ' ' . $_SESSION['session_utilisateur']['nom_famille']) . "'
			, '" . nohtml_real_escape_string($_SESSION['session_utilisateur']['prenom'] . ' ' . $_SESSION['session_utilisateur']['nom_famille']) . "'";
		}
		$sql .= "
			, '" . intval(vn($frm['on_carrousel'])) . "'
			, '" . intval(vn($frm['allow_show_all_sons_products'])) . "'
			, '" . intval($frm['position']) . "'
			, '" . intval($frm['type_affichage']) . "'
			, '" . nohtml_real_escape_string($frm['background_menu']) . "'
			, '" . nohtml_real_escape_string($frm['background_color']) . "'
			, '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
		if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			$sql .= ", '" . nohtml_real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'
			";
		}
		if(!empty($GLOBALS['site_parameters']['categorie_weight_enable'])) {
			$sql .= ", '" . nohtml_real_escape_string($frm['poids']) . "'";
		}
		if (check_if_module_active('category_promotion')) {
			$sql .= ", '" . floatval(get_float_from_user_input($frm['promotion_devises'])) . "'
			, '" . floatval(get_float_from_user_input($frm['promotion_percent'])) . "'
			, '" . intval($frm['on_child']) . "'";
		}
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$sql .= "
			, '" . nohtml_real_escape_string(StringMb::substr(StringMb::strtoupper($frm['nom_' . $lng]), 0, 1)) . "'
			, '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'
			, '" . real_escape_string($frm['description_' . $lng]) . "'
			, '" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'
			, '" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'
			, '" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'
			, '" . nohtml_real_escape_string($frm['image_' . $lng]) . "'
			, '" . nohtml_real_escape_string($frm['image_header_' . $lng]) . "'
			, '" . real_escape_string($frm['header_html_' . $lng]) . "'
			, '" . real_escape_string(vb($frm['sentence_displayed_on_product_' . $lng])) . "'";
		}
		$sql .= ')';
		query($sql);
		$categorie_id = @insert_id();
		if (!empty($categorie_id)) {
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CATEGORIES_MSG_CREATED_OK'], $categorie_id)))->fetch();
		}
		// Suppression des caches de catégories
		$this_cache_object = new Cache(null, array('group' => 'categories'));
		$this_cache_object->delete_cache_file(true);
		unset($this_cache_object);
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_CHOOSE_TITLE']))->fetch();
	}
}

/**
 * maj_produits_categorie()
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_produits_categorie($id, $frm)
{
	if (empty($frm['parent_id']) || $frm['parent_id'] == $id) {
		$frm['parent_id'] = 0;
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$frm['image_' . $lng] = upload('image_' . $lng, false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image_' . $lng]));
		$frm['image_header_' . $lng] = upload('image_header_' . $lng, false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image_header_' . $lng]));
	}
	
	// Remplit les contenus vides
	$frm = fill_other_language_content($frm, 'categories');
	
	$sql = "UPDATE peel_categories
		SET parent_id = '" . intval($frm['parent_id']) . "'
		, etat = '" . intval($frm['etat']) . "'
		, position = '" . intval($frm['position']) . "'
		, on_special = '" . intval(vn($frm['on_special'])) . "'
		, technical_code = '" . nohtml_real_escape_string(vb($frm['technical_code'])) . "'
		, date_maj = '" . date('Y-m-d H:i:s', time()) . "'";
		if(!empty($GLOBALS['site_parameters']['admin_save_name_modify_or_create_content'])) {
			$sql .= "
		, nom_maj = '" . nohtml_real_escape_string($_SESSION['session_utilisateur']['prenom'] . ' ' . $_SESSION['session_utilisateur']['nom_famille']) . "'";
		}
		$sql .= "
		, on_carrousel = '" . intval(vn($frm['on_carrousel'])) . "'
		, allow_show_all_sons_products = '" . intval(vn($frm['allow_show_all_sons_products'])) . "'
		, type_affichage = '" . intval($frm['type_affichage']) . "'
		, background_menu = '" . nohtml_real_escape_string($frm['background_menu']) . "'
		, background_color = '" . nohtml_real_escape_string($frm['background_color']) . "'
		, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= "
		, site_country = '" . nohtml_real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'
		";
	}
	if(!empty($GLOBALS['site_parameters']['categorie_weight_enable'])) {
		$sql .= "
		, poids = '" . nohtml_real_escape_string($frm['poids']) . "'";
	}
	if (check_if_module_active('category_promotion')) {
		$sql .= ", promotion_devises = '" . nohtml_real_escape_string($frm['promotion_devises']) . "'
		, promotion_percent = '" . nohtml_real_escape_string($frm['promotion_percent']) . "'
		, on_child = '" . intval($frm['on_child']) . "'";
	}

	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", alpha_" . $lng . "='" . nohtml_real_escape_string(StringMb::substr(strtoupper($frm['nom_' . $lng]), 0, 1)) . "'
		, nom_" . $lng . "='" . real_escape_string($frm['nom_' . $lng]) . "'
		, image_header_" . $lng . "='" . real_escape_string($frm['image_header_' . $lng]) . "'
		, image_" . $lng . "='" . real_escape_string($frm['image_' . $lng]) . "'
		, description_" . $lng . "='" . real_escape_string($frm['description_' . $lng]) . "'
		, meta_titre_" . $lng . "='" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'
		, meta_key_" . $lng . "='" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'
		, meta_desc_" . $lng . "='" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'
		, header_html_" . $lng . " = '" . real_escape_string($frm['header_html_' . $lng]) . "'
		, sentence_displayed_on_product_" . $lng . " = '" . real_escape_string(vb($frm['sentence_displayed_on_product_' . $lng])) . "'";
	}
	$sql .= " WHERE id=" . intval($id). " AND " . get_filter_site_cond('categories', null, true) . "";

	query($sql);
	// Suppression des caches de catégories
	$this_cache_object = new Cache(null, array('group' => 'categories'));
	$this_cache_object->delete_cache_file(true);
	unset($this_cache_object);
}

/**
 * Affiche un formulaire de catégorie vide
 *
 * @param integer $id
 * @return
 */
function affiche_formulaire_liste_produits_categorie($id)
{
	$frm["parent_id"] = $id;

	affiche_liste_produits_categorie($frm["parent_id"]);
}

/**
 * affiche_liste_produits_categorie()
 *
 * @param mixed $parent_id
 * @return
 */
function affiche_liste_produits_categorie($parent_id)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_categorie.tpl');
	$tpl->assign('is_category_promotion_module_active', check_if_module_active('category_promotion'));
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('cat_src', $GLOBALS['administrer_url'] . '/images/rubrique-24.gif');
	$tpl->assign('prod_src', $GLOBALS['administrer_url'] . '/images/prod-cat-24.gif');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	affiche_arbo_categorie($categorie_output, $parent_id);
	$tpl->assign('categorie_options', $categorie_output);
	$tpl->assign('STR_ADMIN_CATEGORIES_LIST_TITLE', $GLOBALS['STR_ADMIN_CATEGORIES_LIST_TITLE']);
	$tpl->assign('STR_ADMIN_CATEGORIES_CREATE', $GLOBALS['STR_ADMIN_CATEGORIES_CREATE']);
	$tpl->assign('STR_ADMIN_CATEGORIES_ADD_SUBCATEGORY', $GLOBALS['STR_ADMIN_CATEGORIES_ADD_SUBCATEGORY']);
	$tpl->assign('STR_ADMIN_CATEGORIES_ADD_PRODUCT', $GLOBALS['STR_ADMIN_CATEGORIES_ADD_PRODUCT']);
	$tpl->assign('STR_ADMIN_CATEGORIES_DELETE_CATEGORY', $GLOBALS['STR_ADMIN_CATEGORIES_DELETE_CATEGORY']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
	$tpl->assign('STR_IMAGE', $GLOBALS['STR_IMAGE']);
	$tpl->assign('STR_ADMIN_CATEGORIES', $GLOBALS['STR_ADMIN_CATEGORIES']);
	$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
	$tpl->assign('STR_PROMOTION', $GLOBALS['STR_PROMOTION']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	echo $tpl->fetch();
}

/**
 * affiche_formulaire_produits_categorie()
 *
 * @param array $frm
 * @return
 */
function affiche_formulaire_produits_categorie(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_categorie.tpl');
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('nom', $frm['nom_' . $_SESSION['session_langue']]);
	if ($frm['nouveau_mode'] == "maj") {
		$tpl->assign('cat_href', get_product_category_url($frm['id'], $frm['nom_' . $_SESSION['session_langue']]), false, false, vb($frm['site_id']));
	}
	$tpl->assign('issel_parent_zero', vb($frm['parent_id']) == 0);
	$tpl->assign('categorie_options', get_categories_output(null, 'categories', $frm['parent_id'], 'option', '&nbsp;&nbsp;', null, null, true, 80));
	$tpl->assign('is_on_special', !empty($frm['on_special']));
	$tpl->assign('technical_code', vb($frm["technical_code"]));
	$tpl->assign('is_carrousel_module_active', check_if_module_active('carrousel'));
	$tpl->assign('is_on_carrousel', !empty($frm['on_carrousel']));
	$tpl->assign('allow_show_all_sons_products', !empty($frm['allow_show_all_sons_products']));
	$tpl->assign('position', $frm['position']);
	if(!empty($GLOBALS['site_parameters']['categorie_weight_enable'])) {
		$tpl->assign('poids', $frm['poids']);
	}
	$tpl->assign('etat', $frm['etat']);
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('site_country_checkboxes', get_site_country_checkboxes(vb($frm['site_country'], array())));
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
	}
	$tpl->assign('type_affichage', $frm['type_affichage']);
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');

	$tpl_langs = array();
	$tpl->assign('is_lot_module_active', check_if_module_active('lot'));
	if (check_if_module_active('lot')) {
		if (vb($frm['nouveau_mode']) == "maj") {
			$tpl->assign('lot_explanation_table', get_lot_explanation_table(null, $frm['id']));
			$tpl->assign('lot_href', $GLOBALS['wwwroot_in_admin'] . '/modules/lot/administrer/lot.php?cat_id=' . vb($frm['id']));
			if (num_rows(query("SELECT 1 FROM peel_quantites WHERE cat_id='" . intval($frm['id']) . "' AND " . get_filter_site_cond('quantites'))) > 0) {
				$tpl->assign('lot_supprime_href', $GLOBALS['wwwroot_in_admin'] . '/modules/lot/administrer/lot.php?cat_id=' . vb($frm['id']) . '&mode=supprime');
			}
		}
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => $frm['nom_' . $lng],
			'description_te' => getTextEditor('description_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['description_' . $lng]))),
			'meta_titre' => $frm['meta_titre_' . $lng],
			'meta_key' => $frm['meta_key_' . $lng],
			'meta_desc' => $frm['meta_desc_' . $lng],
			'header_html' => vb($frm['header_html_' . $lng]),
			'sentence_displayed_on_product' => vb($frm['sentence_displayed_on_product_' . $lng]),
			'image' => get_uploaded_file_infos('image_' . $lng, vb($frm['image_' . $lng]), get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=image&lang=' . $lng),
			'image_header' => get_uploaded_file_infos('image_header_' . $lng, vb($frm['image_header_' . $lng]), get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=image_header&lang=' . $lng),
			);
	}
	$tpl->assign('langs', $tpl_langs);

	$tpl->assign('enable_categorie_sentence_displayed_on_product', vb($GLOBALS['site_parameters']['enable_categorie_sentence_displayed_on_product']));
	$tpl->assign('is_category_promotion_module_active', check_if_module_active('category_promotion'));
	$tpl->assign('promotion_devises', vb($frm["promotion_devises"]));
	$tpl->assign('site_symbole', vb($GLOBALS['site_parameters']['symbole']));
	$tpl->assign('promotion_percent', vb($frm["promotion_percent"]));
	$tpl->assign('on_child', vb($frm["on_child"]));
	$tpl->assign('background_color', $frm["background_color"]);
	$tpl->assign('background_menu', $frm["background_menu"]);
	$tpl->assign('titre_soumet', $frm["titre_soumet"]);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_AT_ROOT', $GLOBALS['STR_ADMIN_AT_ROOT']);
	$tpl->assign('STR_ADMIN_CATEGORIES_FORM_MODIFY', $GLOBALS['STR_ADMIN_CATEGORIES_FORM_MODIFY']);
	$tpl->assign('STR_ADMIN_SEE_RESULT_IN_REAL', $GLOBALS['STR_ADMIN_SEE_RESULT_IN_REAL']);
	$tpl->assign('STR_ADMIN_CATEGORIES_FORM_ADD_BUTTON', $GLOBALS['STR_ADMIN_CATEGORIES_FORM_ADD_BUTTON']);
	$tpl->assign('STR_ADMIN_CATEGORIES_PARENT', $GLOBALS['STR_ADMIN_CATEGORIES_PARENT']);
	$tpl->assign('STR_ADMIN_DISPLAY_ON_HOMEPAGE', $GLOBALS['STR_ADMIN_DISPLAY_ON_HOMEPAGE']);
	$tpl->assign('STR_ADMIN_CATEGORIES_DISPLAY_IN_CARROUSEL', $GLOBALS['STR_ADMIN_CATEGORIES_DISPLAY_IN_CARROUSEL']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_ADMIN_IN_COLUMNS', $GLOBALS['STR_ADMIN_IN_COLUMNS']);
	$tpl->assign('STR_ADMIN_IN_LINES', $GLOBALS['STR_ADMIN_IN_LINES']);
	$tpl->assign('STR_ADMIN_CATEGORIES_DISPLAY_MODE', $GLOBALS['STR_ADMIN_CATEGORIES_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_DESCRIPTION', $GLOBALS['STR_ADMIN_DESCRIPTION']);
	$tpl->assign('STR_ADMIN_HEADER_HTML_TEXT', $GLOBALS['STR_ADMIN_HEADER_HTML_TEXT']);
	$tpl->assign('STR_IMAGE', $GLOBALS['STR_IMAGE']);
	$tpl->assign('STR_IMAGE_HEADER', $GLOBALS['STR_IMAGE_HEADER']);
	$tpl->assign('STR_ADMIN_CUSTOMIZE_APPEARANCE', $GLOBALS['STR_ADMIN_CUSTOMIZE_APPEARANCE']);
	$tpl->assign('STR_ADMIN_BACKGROUND_COLOR', $GLOBALS['STR_ADMIN_BACKGROUND_COLOR']);
	$tpl->assign('STR_ADMIN_BACKGROUND_COLOR_FOR_MENU', $GLOBALS['STR_ADMIN_BACKGROUND_COLOR_FOR_MENU']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_META_TITLE', $GLOBALS['STR_ADMIN_META_TITLE']);
	$tpl->assign('STR_ADMIN_META_KEYWORDS', $GLOBALS['STR_ADMIN_META_KEYWORDS']);
	$tpl->assign('STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN', $GLOBALS['STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_META_DESCRIPTION', $GLOBALS['STR_ADMIN_META_DESCRIPTION']);
	$tpl->assign('STR_ADMIN_META_TITLE', $GLOBALS['STR_ADMIN_META_TITLE']);
	$tpl->assign('STR_ADMIN_META_KEYWORDS', $GLOBALS['STR_ADMIN_META_KEYWORDS']);
	$tpl->assign('STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN', $GLOBALS['STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_META_DESCRIPTION', $GLOBALS['STR_ADMIN_META_DESCRIPTION']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_CATEGORIES_DISCOUNT_IN_CATEGORY', $GLOBALS['STR_ADMIN_CATEGORIES_DISCOUNT_IN_CATEGORY']);
	$tpl->assign('STR_ADMIN_CATEGORIES_LOT_PRICE', $GLOBALS['STR_ADMIN_CATEGORIES_LOT_PRICE']);
	$tpl->assign('STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE', $GLOBALS['STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE']);
	$tpl->assign('STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	$tpl->assign('STR_ADMIN_CATEGORIES_DISCOUNT_APPLY_TO_SONS', $GLOBALS['STR_ADMIN_CATEGORIES_DISCOUNT_APPLY_TO_SONS']);
	$tpl->assign('STR_ADMIN_CATEGORIES_DISCOUNT_APPLY_TO_SONS_EXPLAIN', $GLOBALS['STR_ADMIN_CATEGORIES_DISCOUNT_APPLY_TO_SONS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_DELETE_IMAGE', $GLOBALS['STR_ADMIN_DELETE_IMAGE']);
	$tpl->assign('STR_ADMIN_FILE_NAME', $GLOBALS['STR_ADMIN_FILE_NAME']);
	$tpl->assign('STR_ADMIN_SENTENCE_DISPLAYED_ON_PRODUCT', $GLOBALS['STR_ADMIN_SENTENCE_DISPLAYED_ON_PRODUCT']);
	$tpl->assign('STR_ADMIN_PRODUITS_WEIGHT', $GLOBALS['STR_ADMIN_PRODUITS_WEIGHT']);
	echo $tpl->fetch();
}

/**
 * Supprime l'image de la catégorie spécifiée par $id
 *
 * @param integer $id
 * @param mixed $file
 * @param string $lang
 * @return
 */
function supprime_fichier_categorie($id, $file, $lang)
{
	/* Charge les infos du produit. */
	switch ($file) {
		case "image":
			$sql = "SELECT image_" . word_real_escape_string($lang) . "
				FROM peel_categories
				WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('categories', null, true) . "";
			$res = query($sql);
			$file = fetch_assoc($res);
			query("UPDATE peel_categories
				SET image_" . word_real_escape_string($lang) . " = ''
				WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('categories', null, true) . "");
			delete_uploaded_file_and_thumbs($file['image_' . $lang]);
			break;
	
		case "image_header":
			$sql = "SELECT image_header_" . word_real_escape_string($lang) . "
				FROM peel_categories
				WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('categories', null, true) . "";
			$res = query($sql);
			$file = fetch_assoc($res);
			query("UPDATE peel_categories
				SET image_header_" . word_real_escape_string($lang) . " = ''
				WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('categories', null, true) . "");
			delete_uploaded_file_and_thumbs($file['image_header_' . $lang]);
			break;
	}
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_FILE_DELETED'], $file['image_' . $lang])))->fetch();
}


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 35805 2013-03-10 20:43:50Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Affiche un formulaire vierge pour ajouter une bannière
 *
 * @param integer $categorie_id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_banniere($categorie_id = 0, &$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm["reference"] = "";
		$frm["nom"] = "";
		$frm["description"] = "";
		$frm["image"] = "";
		$frm["etat"] = "";
		$frm["lien"] = "";
		$frm["hit"] = "";
		$frm["vue"] = "";
		$frm["lang"] = "";
		$frm["target"] = "";
		$frm["tag_html"] = "";
		$frm['id_categorie'] = "";
		$frm['extra_javascript'] = "";
		$frm['width'] = "";
		$frm['height'] = "";
		$frm["annonce_number"] = "";
		$frm["on_home_page"] = "";
		$frm["on_first_page_category"] = "";
		$frm["on_other_page_category"] = "";
		$frm["on_ad_page_details"] = "";
		$frm["on_other_page"] = "";
		$frm["pages_allowed"] = "";
	}
	$frm["titre_bouton"] = $GLOBALS["STR_MODULE_BANNER_ADMIN_ADD_BUTTON"];
	$frm["nouveau_mode"] = "insere";
	affiche_formulaire_banniere($frm);
}

/**
 * affiche_formulaire_modif_banniere()
 *
 * Affiche le formulaire de modification pour le bannière sélectionnée
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_banniere($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations de la bannière */
		$qid = query("SELECT *
			FROM peel_banniere
			WHERE id = " . intval($id) . "");
		$frm = fetch_assoc($qid);
	}
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_banniere($frm);
}

/**
 * affiche_formulaire_banniere()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_banniere(&$frm)
{
	if ($frm['nouveau_mode'] != 'insere') {
		$title = $GLOBALS['STR_MODULE_BANNER_ADMIN_UPDATE'];
	} else {
		$title = $GLOBALS['STR_MODULE_BANNER_ADMIN_CREATE'];
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/bannerAdmin_formulaire_banniere.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('title', $title);
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($GLOBALS['id']));
	$tpl->assign('etat', vb($frm["etat"]));
	$tpl->assign('description', vb($frm["description"]));
	$tpl->assign('lien', vb($frm["lien"]));
	$tpl->assign('extra_javascript', vb($frm["extra_javascript"]));
	$tpl->assign('tag_html', vb($frm["tag_html"]));
	$tpl->assign('target', vb($frm["target"]));
	$tpl->assign('position', vb($frm["position"]));
	$tpl->assign('rang', vb($frm["rang"]));
	$tpl->assign('banner_help', get_banner_help());
	$tpl->assign('cette_page_href', $GLOBALS['administrer_url'] . '/sites.php');
	$tpl->assign('STR_MODULE_BANNER_ADMIN_PLACE_EXPLAIN', sprintf($GLOBALS['STR_MODULE_BANNER_ADMIN_PLACE_EXPLAIN'], $GLOBALS['administrer_url'] . '/sites.php'));
	$tpl->assign('is_annonce_module_active', is_annonce_module_active());
	$tpl->assign('date_debut', get_formatted_date(vb($frm["date_debut"])));
	$tpl->assign('date_fin', get_formatted_date(vb($frm["date_fin"])));
	$tpl->assign('on_other_page_category', vb($frm["on_other_page_category"]));
	$tpl->assign('on_home_page', vb($frm["on_home_page"]));
	$tpl->assign('on_other_page', vb($frm["on_other_page"]));
	$tpl->assign('on_search_engine_page', vb($frm["on_search_engine_page"]));
	$tpl->assign('on_first_page_category', vb($frm["on_first_page_category"]));

	if (is_annonce_module_active()) {
		$tpl->assign('on_ad_page_details', vb($frm["on_ad_page_details"]));
		$tpl->assign('list_id', vb($frm["list_id"]));
		$tpl->assign('annonce_number', vb($frm["annonce_number"]));
		$tpl->assign('pages_allowed', vb($frm["pages_allowed"]));
		$tpl->assign('STR_MODULE_ANNONCES_DESCRIPTION', $GLOBALS['STR_MODULE_ANNONCES_DESCRIPTION']);
		// Charge les informations sur les catégorie annonces lorsque le module est activée.
		$qid = query("SELECT id, nom_" . $_SESSION['session_langue'] . "
			FROM peel_categories_annonces");
	} else {
		// Charge les informations sur les catégorie présentes en base de donnée.
		$qid = query("SELECT id, nom_" . $_SESSION['session_langue'] . "
			FROM peel_categories");
	} 
	$tpl_cat_opts = array();
	while ($cat = fetch_assoc($qid)) {
		$tpl_cat_opts[] = array(
			'value' => intval($cat['id']),
			'issel' => vb($frm["id_categorie"]) == $cat['id'],
			'name' => $cat['nom_' . $_SESSION['session_langue']]
		);
	}
	$tpl->assign('cat_options', $tpl_cat_opts);
	$tpl->assign('conf_site_href', $GLOBALS['administrer_url'] . '/sites.php?mode=modif&id=1');
	$tpl->assign('appearance', vb($frm["appearance"]));
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');

	if (!empty($frm["image"])) {
		$extension = @pathinfo($frm['image'], PATHINFO_EXTENSION);
		$tpl_image = array(
			'nom' => $frm["image"],
			'drop_href' => get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=image'
		);
		if ($extension == 'swf') {
			$tpl_image['swf'] = getFlashBannerHTML($GLOBALS['repertoire_upload'] . '/' . $frm['image'], 300, 300);
		} else {
			$tpl_image['src'] = $GLOBALS['repertoire_upload'] . '/' . $frm["image"];
		}
		$tpl->assign('image', $tpl_image);
	}
	$tpl->assign('width', vb($frm["width"]));
	$tpl->assign('height', vb($frm["height"]));
	$tpl->assign('lang', vb($frm["lang"]));
	$tpl->assign('titre_bouton', vb($frm["titre_bouton"]));
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_LINK', $GLOBALS['STR_ADMIN_LINK']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_EXTRA_JAVASCRIPT', $GLOBALS['STR_MODULE_BANNER_ADMIN_EXTRA_JAVASCRIPT']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_TAG_HTML', $GLOBALS['STR_MODULE_BANNER_ADMIN_TAG_HTML']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_TAG_HTML_EXPLAIN', $GLOBALS['STR_MODULE_BANNER_ADMIN_TAG_HTML_EXPLAIN']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_TARGET', $GLOBALS['STR_MODULE_BANNER_ADMIN_TARGET']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_TARGET_SELF', $GLOBALS['STR_MODULE_BANNER_ADMIN_TARGET_SELF']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_TARGET_BLANK', $GLOBALS['STR_MODULE_BANNER_ADMIN_TARGET_BLANK']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_TARGET_TOP', $GLOBALS['STR_MODULE_BANNER_ADMIN_TARGET_TOP']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_TARGET_PARENT', $GLOBALS['STR_MODULE_BANNER_ADMIN_TARGET_PARENT']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_PLACE', $GLOBALS['STR_MODULE_BANNER_ADMIN_PLACE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_PLACE_EXPLAIN', $GLOBALS['STR_MODULE_BANNER_ADMIN_PLACE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_POSITION_EXPLAIN', $GLOBALS['STR_MODULE_BANNER_ADMIN_POSITION_EXPLAIN']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_AD_PLACE', $GLOBALS['STR_MODULE_BANNER_ADMIN_AD_PLACE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_AD_ID', $GLOBALS['STR_MODULE_BANNER_ADMIN_AD_ID']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_ODD_EVEN_ALL', $GLOBALS['STR_MODULE_BANNER_ADMIN_ODD_EVEN_ALL']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_ODD_EVEN_ODD', $GLOBALS['STR_MODULE_BANNER_ADMIN_ODD_EVEN_ODD']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_ODD_EVEN_EVEN', $GLOBALS['STR_MODULE_BANNER_ADMIN_ODD_EVEN_EVEN']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_START_PUBLICATION_DATE', $GLOBALS['STR_MODULE_BANNER_ADMIN_START_PUBLICATION_DATE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_END_PUBLICATION_DATE', $GLOBALS['STR_MODULE_BANNER_ADMIN_END_PUBLICATION_DATE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_DATES_EXPLAIN', $GLOBALS['STR_MODULE_BANNER_ADMIN_DATES_EXPLAIN']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_SPACE_EXPLAIN', $GLOBALS['STR_MODULE_BANNER_ADMIN_SPACE_EXPLAIN']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_ON_AD_PAGE_DETAILS', $GLOBALS['STR_MODULE_BANNER_ADMIN_ON_AD_PAGE_DETAILS']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_ON_FIRST_PAGE_CATEGORY', $GLOBALS['STR_MODULE_BANNER_ADMIN_ON_FIRST_PAGE_CATEGORY']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_ON_OTHER_PAGE_CATEGORY', $GLOBALS['STR_MODULE_BANNER_ADMIN_ON_OTHER_PAGE_CATEGORY']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_ON_HOME_PAGE', $GLOBALS['STR_MODULE_BANNER_ADMIN_ON_HOME_PAGE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_ON_OTHER_PAGE', $GLOBALS['STR_MODULE_BANNER_ADMIN_ON_OTHER_PAGE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_ON_SEARCH_ENGINE_PAGE', $GLOBALS['STR_MODULE_BANNER_ADMIN_ON_SEARCH_ENGINE_PAGE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_IMAGE_OR_FLASH', $GLOBALS['STR_MODULE_BANNER_ADMIN_IMAGE_OR_FLASH']);
	$tpl->assign('STR_ADMIN_FILE_NAME', $GLOBALS['STR_ADMIN_FILE_NAME']);
	$tpl->assign('STR_ADMIN_DELETE_IMAGE', $GLOBALS['STR_ADMIN_DELETE_IMAGE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_WIDTH', $GLOBALS['STR_MODULE_BANNER_ADMIN_WIDTH']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_HEIGHT', $GLOBALS['STR_MODULE_BANNER_ADMIN_HEIGHT']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_SIZE_EXPLAIN', $GLOBALS['STR_MODULE_BANNER_ADMIN_SIZE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_ADMIN_DESCRIPTION', $GLOBALS['STR_ADMIN_DESCRIPTION']);
	echo $tpl->fetch();
}

/**
 * supprime_banniere()
 *
 * Supprime la banniere spécifiée par $id
 *
 * @param integer $id
 * @return
 */
function supprime_banniere($id)
{
	/* Charge les infos de la commande. */
	$qid = query("SELECT description
		FROM peel_banniere
		WHERE id = " . intval($id) . "");
	$prod = fetch_assoc($qid);

	/* Efface la banniere */
	query("DELETE FROM peel_banniere WHERE id='" . intval($id) . "'");
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_BANNER_ADMIN_MSG_DELETED_OK"], $prod['description'])))->fetch();
}

/**
 * insere_banniere()
 *
 * @param string $img
 * @param array $frm Array with all fields data
 * @return
 */
function insere_banniere(&$frm)
{
	/*ajoute le banniere dans la table banniere */
	if (empty($frm['etat'])) {
		$frm['etat'] = 0;
	}
	if (!empty($frm['image']) || !empty($frm['tag_html'])) {
		$sql = "INSERT INTO peel_banniere (
			description
			, image
			, date_debut
			, date_fin
			, etat";
		if (is_annonce_module_active()) {
			$sql .= "
			, pages_allowed
			, list_id
			, annonce_number
			, on_ad_page_details";
		}
		$sql .= "
			, on_first_page_category
			, on_other_page_category
			, on_search_engine_page
			, position
			, lien
			, lang
			, target
			, tag_html
			, extra_javascript
			, id_categorie
			, width
			, height
			, rang
			, on_home_page
			, on_other_page
			, keywords
		) VALUES (
			'" . nohtml_real_escape_string($frm['description']) . "'
			, '" . nohtml_real_escape_string($frm['image']) . "'
			, '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['date_debut'])) . "'
			, '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['date_fin'])) . "'
			, '" . nohtml_real_escape_string($frm['etat']) . "'";
		if (is_annonce_module_active()) {
			$sql .= "
			, '" . nohtml_real_escape_string(vb($frm['pages_allowed'])) . "'
			, '" . nohtml_real_escape_string(vn($frm['list_id'])) . "'
			, '" . intval(vn($frm['annonce_number'])) . "'
			, '" . intval(vn($frm['on_ad_page_details'])) . "'";
		}
		$sql .= "
			, '" . intval(vn($frm['on_first_page_category'])) . "'
			, '" . intval(vn($frm['on_other_page_category'])) . "'
			, '" . intval(vn($frm['on_search_engine_page'])) . "'
			, '" . nohtml_real_escape_string($frm['position']) . "'
			, '" . nohtml_real_escape_string($frm['lien']) . "'
			, '" . nohtml_real_escape_string($frm['lang']) . "'
			, '" . nohtml_real_escape_string($frm['target']) . "'
			, '" . real_escape_string($frm['tag_html']) . "'
			, '" . real_escape_string($frm['extra_javascript']) . "'
			, '" . intval(vn($frm['id_categorie'])) . "'
			, '" . intval(vn($frm['width'])) . "'
			, '" . intval(vn($frm['height'])) . "'
			, '" . intval(vn($frm['rang'])) . "'
			, '" . intval(vn($frm['on_home_page'])) . "'
			, '" . intval(vn($frm['on_other_page'])) . "'
			, '" . nohtml_real_escape_string(vb($frm['keywords'])) . "'
		)";
		$qid = query($sql);
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_BANNER_ADMIN_MSG_OK"], vb($_POST['description']))))->fetch();
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS["STR_MODULE_BANNER_ADMIN_ERROR_INSERTED"]))->fetch();
	}
}

/**
 * Met à jour la bannière $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_banniere($id, &$frm)
{
	/* Met à jour la table banniere */
	$sql = 'UPDATE peel_banniere SET
		description = "' . nohtml_real_escape_string($frm['description']) . '"
		, date_debut = "' . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['date_debut'])) . '"
		, date_fin = "' . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['date_fin'])) . '"
		, image = "' . nohtml_real_escape_string($frm['image']) . '"
		, etat = "' . nohtml_real_escape_string($frm['etat']) . '"';
	if (is_annonce_module_active()) {
		$sql .= '
		, annonce_number = "' . intval(vn($frm['annonce_number'])) . '"
		, pages_allowed = "' . nohtml_real_escape_string(vb($frm['pages_allowed'])) . '"
		, list_id = "' . nohtml_real_escape_string(vn($frm['list_id'])) . '"
		, on_ad_page_details = "' . intval(vn($frm['on_ad_page_details'])) . '"';
	}
	$sql .= '
		, on_other_page_category = "' . intval(vn($frm['on_other_page_category'])) . '"
		, on_first_page_category = "' . intval(vn($frm['on_first_page_category'])) . '"
		, on_search_engine_page = "' . intval(vn($frm['on_search_engine_page'])) . '"
		, position = "' . nohtml_real_escape_string($frm['position']) . '"
		, lien = "' . nohtml_real_escape_string($frm['lien']) . '"
		, lang = "' . nohtml_real_escape_string($frm['lang']) . '"
		, on_home_page = "' . intval(vn($frm['on_home_page'])) . '"
		, on_other_page = "' . intval(vn($frm['on_other_page'])) . '"
		, target = "' . nohtml_real_escape_string($frm['target']) . '"
		, tag_html = "' . real_escape_string($frm['tag_html']) . '"
		, extra_javascript  = "' . real_escape_string($frm['extra_javascript']) . '"
		, id_categorie  = "' . intval(vn($frm['id_categorie'])) . '"
		, width = "' . intval(vn($frm['width'])) . '"
		, height = "' . intval(vn($frm['height'])) . '"
		, rang = "' . intval(vn($frm['rang'])) . '"
		, keywords = "' . nohtml_real_escape_string(vb($frm['keywords'])) . '"
		WHERE id = "' . intval($id) . '"';
	if (query($sql)) {
		$ouptut = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_BANNER_ADMIN_MSG_UPDATED_OK"], $id)))->fetch();
	} else {
		$ouptut = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS["STR_MODULE_BANNER_ADMIN_ERR_UPDATED"]))->fetch();
	}
	return $ouptut;
}

/**
 * affiche_liste_banniere()
 *
 * @return
 */
function affiche_liste_banniere($inner = '', $cond = '')
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/bannerAdmin_liste.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$sql = "SELECT *
		FROM peel_banniere pb " . $inner . "
		WHERE 1
		" . $cond . "
		ORDER BY date_debut DESC";
	$Links = new Multipage($sql, 'utilisateurs');
	$results_array = $Links->Query();
	if (!empty($results_array)) {
		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $ligne) {
			$extension = @pathinfo($ligne['image'], PATHINFO_EXTENSION);
			$tpl_swf = null;
			$tpl_src = null;
			if ($extension == 'swf') {
				$tpl_swf = getFlashBannerHTML($GLOBALS['repertoire_upload'] . '/' . $ligne['image'], 150, 150);
			} elseif (!empty($ligne['image'])) {
				$tpl_src = $GLOBALS['repertoire_upload'] . '/' . $ligne['image'];
			}
			$tpl_results[] = array(
				'tr_rollover' => tr_rollover($i, true),
				'description' => $ligne['description'],
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'position' => $ligne['position'],
				'rang' => $ligne['rang'],
				'swf' => $tpl_swf,
				'src' => $tpl_src,
				'date_debut' => get_formatted_date($ligne['date_debut']),
				'date_fin' => get_formatted_date($ligne['date_fin']),
				'hit' => $ligne['hit'],
				'vue' => $ligne['vue'],
				'lang' => $ligne['lang'],
				'etat_onclick' => 'change_status("banner", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'modif_etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif')
			);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('STR_MODULE_BANNER_ADMIN_LIST_TITLE', $GLOBALS['STR_MODULE_BANNER_ADMIN_LIST_TITLE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_CREATE', $GLOBALS['STR_MODULE_BANNER_ADMIN_CREATE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_PLACE', $GLOBALS['STR_MODULE_BANNER_ADMIN_PLACE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_IMAGE', $GLOBALS['STR_ADMIN_IMAGE']);
	$tpl->assign('STR_ADMIN_BEGIN_DATE', $GLOBALS['STR_ADMIN_BEGIN_DATE']);
	$tpl->assign('STR_ADMIN_END_DATE', $GLOBALS['STR_ADMIN_END_DATE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_HIT', $GLOBALS['STR_MODULE_BANNER_ADMIN_HIT']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_VIEWED', $GLOBALS['STR_MODULE_BANNER_ADMIN_VIEWED']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_UPDATE', $GLOBALS['STR_MODULE_BANNER_ADMIN_UPDATE']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_NOTHING_FOUND', $GLOBALS['STR_MODULE_BANNER_ADMIN_NOTHING_FOUND']);
	echo $tpl->fetch();
}

/**
 * delete_banner_image()
 *
 * Supprime le produit spécifié par $id. Il faut supprimer le produit
 *
 * @param integer $id
 * @param mixed $file
 * @return
 */
function delete_banner_image($id, $file)
{
	/* Charge les infos du produit. */
	switch ($file) {
		case "image" :
			$sql = "SELECT image
				FROM peel_banniere
				WHERE id = '" . intval($id) . "'";
			$res = query($sql);
			$file = fetch_assoc($res);
			query("UPDATE peel_banniere
				SET image = ''
				WHERE id = '" . intval($id) . "'");
			break;
	}
	delete_uploaded_file_and_thumbs($file['image']);
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_RUBRIQUES_MSG_DELETED_OK'], $file['image'])))->fetch();
}

/**
 * affiche_filtre_banner
 *
 * Supprime le produit spécifié par $id. Il faut supprimer le produit
 *
 * @param array $frm
 * @return
 */
function affiche_filtre_banner($frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/bannerAdmin_filtre.tpl');
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('is_annonce_module_active', is_annonce_module_active());
	$tpl_options = array();
	if (is_annonce_module_active()) {
		$sql_annonce_categorie = query('SELECT id,nom_' . $_SESSION['session_langue'] . '
			FROM peel_categories_annonces pca
			WHERE etat=1
			ORDER BY nom_' . $_SESSION['session_langue'] . '');
		while ($this_categorie = fetch_assoc($sql_annonce_categorie)) {
			$tpl_options[] = array(
				'value' => $this_categorie['id'],
				'issel' => intval(vb($frm['filter_categorie_banniere'])) == $this_categorie['id'],
				'name' => vb($this_categorie['nom_' . $_SESSION['session_langue'] . ''])
			);
		}
	} else {
		$sql_annonce_categorie = query('SELECT id,nom_' . $_SESSION['session_langue'] . '
			FROM peel_categories pc
			WHERE etat=1
			ORDER BY nom_' . $_SESSION['session_langue']);
		while ($this_categorie = fetch_assoc($sql_annonce_categorie)) {
			$tpl_options[] = array(
				'value' => $this_categorie['id'],
				'issel' => vb($frm['filter_categorie_banniere']) == $this_categorie['id'],
				'name' => vb($this_categorie['nom_' . $_SESSION['session_langue'] . ''])
			);
		}
	}
	$tpl->assign('options', $tpl_options);
	$tpl->assign('filter_lang', vb($frm['filter_lang']));
	$tpl->assign('filter_date_debut', vb($frm['filter_date_debut']));
	$tpl->assign('filter_date_fin', vb($frm['filter_date_fin']));
	$tpl->assign('filter_description', vb($frm['filter_description']));
	$tpl->assign('filter_categorie_banniere', vb($frm['filter_categorie_banniere']));
	$tpl->assign('filter_etat', vb($frm['filter_etat']));
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_ADMIN_CHOOSE_SEARCH_CRITERIA', $GLOBALS['STR_ADMIN_CHOOSE_SEARCH_CRITERIA']);
	$tpl->assign('STR_ADMIN_SEARCH_IN_TITLE', $GLOBALS['STR_ADMIN_SEARCH_IN_TITLE']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_ADMIN_BEGIN_DATE', $GLOBALS['STR_ADMIN_BEGIN_DATE']);
	$tpl->assign('STR_ADMIN_END_DATE', $GLOBALS['STR_ADMIN_END_DATE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_MODULE_BANNER_ADMIN_ALL', $GLOBALS['STR_MODULE_BANNER_ADMIN_ALL']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
	echo $tpl->fetch();
}

?>
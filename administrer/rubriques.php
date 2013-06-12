<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rubriques.php 37040 2013-05-30 13:17:16Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv('admin_content');

$DOC_TITLE = $GLOBALS['STR_ADMIN_RUBRIQUES_TITLE'];
include("modeles/haut.php");

$form_error_object = new FormError();
$frm = $_POST;

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_rubrique(vn($_REQUEST['id']), $frm);
		break;

	case "modif" :
		affiche_formulaire_modif_rubrique(vn($_REQUEST['id']), $frm);
		break;

	case "suppr" :
		supprime_rubrique(vn($_REQUEST['id']));
		affiche_formulaire_liste_rubrique(vn($_REQUEST['id']));
		break;

	case "supprfile" :
		supprime_fichier_rubrique(vn($_REQUEST['id']), $_GET['file']);
		affiche_formulaire_modif_rubrique(vn($_REQUEST['id']), $frm);
		break;

	case "insere" :
		$form_error_object->valide_form($frm,
			array('nom_' . $_SESSION['session_langue'] => $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_ERR_NO_TITLE']));
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$frm['image'] = upload('image', false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image']));
			insere_sous_rubrique($frm);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PRODUITS_ACHETES_MSG_CREATED_OK'], vb($_POST['nom_' . $_SESSION['session_langue']]))))->fetch();
			affiche_formulaire_liste_rubrique(vn($_REQUEST['id']));
		} else {
			echo $form_error_object->text();
			affiche_formulaire_ajout_rubrique(vn($_REQUEST['id']), $frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$frm['image'] = upload('image', false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image']));
			maj_rubrique(vn($_REQUEST['id']), $frm);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PRODUITS_ACHETES_MSG_UPDATED_OK'], vn($_REQUEST['id']))))->fetch();
			affiche_formulaire_liste_rubrique(vn($_REQUEST['id']));
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_rubrique($frm['id'], $frm);
		}
		break;

	case "modif_etat" :
		if (isset($_GET['position']) && !empty($_GET['id'])) {
			$qid = query("SELECT *
				FROM peel_rubriques
				WHERE id = " . intval($_GET['id']));
			if ($result = fetch_assoc($qid)) {
				// On intervertit les positions si une autre catégorie a la même position
				$qid = query('UPDATE peel_rubriques
					SET position="' . intval($result['position']) . '"
					WHERE parent_id="' . intval($result['parent_id']) . '" AND position="' . intval($_GET['position']) . '"');
			}
			query('UPDATE peel_rubriques
				SET position="' . intval($_GET['position']) . '"
				WHERE id="' . intval($_GET['id']) . '"');
		}
		affiche_formulaire_liste_rubrique(vn($_REQUEST['id']));
		break;

	default :
		affiche_formulaire_liste_rubrique(vn($_REQUEST['id']));
		break;
}

include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * affiche_arbo_rubrique()
 *
 * @param mixed $sortie
 * @param mixed $selectionne
 * @param integer $parent_id
 * @param string $indent
 * @return
 */
function affiche_arbo_rubrique(&$sortie, $selectionne, $parent_id = 0, $indent = "", $first_line = 0, $depth = 1)
{
	$sql = 'SELECT r.id, r.nom_' . $_SESSION['session_langue'] . ', r.etat, r.position, r.image, r.articles_review, r.technical_code
		FROM peel_rubriques r
		WHERE r.parent_id = "' . intval($parent_id) . '"
		ORDER BY r.position';
	$qid = query($sql);

	while ($rub = fetch_assoc($qid)) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_arbo_rubrique.tpl');
		$tpl->assign('image', $rub['image']);
		$tpl->assign('image_src', $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($rub['image'], 80, 50, 'fit'));
		$tpl->assign('tr_rollover', tr_rollover($first_line, true));
		$tpl->assign('ajout_rub_href', get_current_url(false) . '?mode=ajout&id=' . $rub['id']);
		$tpl->assign('rubrique_src', $GLOBALS['administrer_url'] . '/images/rubrique-24.gif');
		$tpl->assign('ajout_art_href', $GLOBALS['administrer_url'] . '/articles.php?mode=ajout&rubrique_id=' . $rub['id']);
		$tpl->assign('prod_cat_src', $GLOBALS['administrer_url'] . '/images/prod-cat-24.gif');
		$tpl->assign('nom', $rub['nom_' . $_SESSION['session_langue']]);
		$tpl->assign('sup_href', get_current_url(false) . '?mode=suppr&id=' . $rub['id']);
		$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
		$tpl->assign('indent', $indent);
		$tpl->assign('modif_href', get_current_url(false) . '?mode=modif&id=' . $rub['id']);
		$tpl->assign('sites', get_all_site_names());
		$tpl->assign('depth', $depth);
		$tpl->assign('position', $rub['position']);
		$tpl->assign('up_href', get_current_url(false) . '?mode=modif_etat&id=' . $rub['id'] . '&position=' . ($rub['position'] - 1));
		$tpl->assign('up_src', $GLOBALS['administrer_url'] . '/images/up.gif');
		$tpl->assign('desc_href', get_current_url(false) . '?mode=modif_etat&id=' . $rub['id'] . '&position=' . ($rub['position'] + 1));
		$tpl->assign('desc_src', $GLOBALS['administrer_url'] . '/images/desc.gif');
		$tpl->assign('etat_onclick', 'change_status("rubriques", "' . $rub['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")');
		$tpl->assign('etat_src', $GLOBALS['administrer_url'] . '/images/' . (empty($rub['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'));
		$tpl->assign('STR_ADMIN_RUBRIQUES_ADD_SUBCATEGORY', $GLOBALS['STR_ADMIN_RUBRIQUES_ADD_SUBCATEGORY']);
		$tpl->assign('STR_ADMIN_RUBRIQUES_ADD_ARTICLE', $GLOBALS['STR_ADMIN_RUBRIQUES_ADD_ARTICLE']);
		$tpl->assign('STR_ADMIN_RUBRIQUES_DELETE_CATEGORY', $GLOBALS['STR_ADMIN_RUBRIQUES_DELETE_CATEGORY']);
		$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
		$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
		$tpl->assign('STR_ADMIN_LEVEL', $GLOBALS['STR_ADMIN_LEVEL']);
		$tpl->assign('STR_NUMBER', $GLOBALS['STR_NUMBER']);
		$sortie .= $tpl->fetch();
		$first_line++;
		if ($rub['id'] != $parent_id) {
			affiche_arbo_rubrique($sortie, $selectionne, $rub['id'], $indent . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $first_line, $depth + 1);
		}
	}
}

/**
 * Affiche un formulaire de rubrique vide
 *
 * @param integer $id
 * @param array $frm
 * @return
 */
function affiche_formulaire_ajout_rubrique($id, &$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
			$frm['description_' . $lng] = "";
			$frm['meta_titre_' . $lng] = "";
			$frm['meta_key_' . $lng] = "";
			$frm['meta_desc_' . $lng] = "";
		}
		$frm['etat'] = "";
		$frm['technical_code'] = "";
		$frm['image'] = "";
		$frm['position'] = 0;
		$frm['articles_review'] = "";
	}
	$frm["parent_id"] = $id;
	$frm["nouveau_mode"] = "insere";
	$frm['id'] = "";
	$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_RUBRIQUES_CREATE'];
	/* Affiche la liste des rubriques, en présélectionnant la rubrique choisie. */
	construit_arbo_rubrique($GLOBALS['rubrique_options'], $frm["parent_id"]);
	affiche_formulaire_rubrique($frm);
}

/**
 * Affiche le formulaire de modification de rubrique
 *
 * @param integer $id
 * @param array $frm
 * @return
 */
function affiche_formulaire_modif_rubrique($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les infos de la rubrique. */
		$qid = query("SELECT *
			FROM peel_rubriques
			WHERE id = '" . intval($id) . "'");
		if ($frm = fetch_assoc($qid)) {
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_RUBRIQUES_ERR_NOT_FOUND']))->fetch();
			return false;
		}
	}
	$frm["nouveau_mode"] = "maj";
	$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	/* Affiche la liste des rubriques, en présélectionnant la rubrique choisie. */
	construit_arbo_rubrique($GLOBALS['rubrique_options'], $frm["parent_id"]);
	affiche_formulaire_rubrique($frm);
}

/**
 * Supprime la rubrique spécifiée par ve($_REQUEST['id']), et déplace tous les produits sous
 * cette rubrique au parent immédiat
 *
 * @param integer $id
 * @return
 */
function supprime_rubrique($id)
{
	$sql = "SELECT nom_" . $_SESSION['session_langue'] . " AS nom
		FROM peel_rubriques 
		WHERE id = '" . intval($id) . "'";
	$query = query($sql);
	$current_rub = fetch_assoc($query);
	/* Trouve le parent de cette rubrique */
	$qid = query("SELECT rub.nom_" . $_SESSION['session_langue'] . ", rub.parent_id, parent.nom_" . $_SESSION['session_langue'] . " AS parent
		FROM peel_rubriques rub
		INNER JOIN peel_rubriques parent ON parent.id = rub.parent_id
		WHERE rub.id = '" . intval($id) . "'");

	if ($rub = fetch_assoc($qid)) {
		/* Réaffecte tous les produits de cette rubrique à la rubrique parente */
		$qid = query("UPDATE peel_articles_rubriques
			SET rubrique_id = " . intval($rub['parent_id']) . "
			WHERE rubrique_id = '" . intval($id) . "'");
		/* Réaffecte toutes les sous-rubriques de cette rubrique à la rubrique parente */
		$qid = query("UPDATE peel_rubriques
			SET parent_id = " . intval($rub['parent_id']) . "
			WHERE parent_id = '" . intval($id) . "'");
	} else {
		/* Réaffecte tous les produits de cette rubrique à la rubrique parente */
		$qid = query("UPDATE peel_articles_rubriques
			SET rubrique_id = '0'
			WHERE rubrique_id = '" . intval($id) . "'");
		/* Réaffecte toutes les sous-rubriques de cette rubrique à la rubrique parente */
		$qid = query("UPDATE peel_rubriques
			SET parent_id = '0'
			WHERE parent_id = '" . intval($id) . "'");
	}
	query("DELETE FROM peel_rubriques WHERE id = '" . intval($id) . "'");

	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_RUBRIQUES_MSG_CREATED_OK'], $current_rub['nom'])))->fetch();
}

/**
 * insere_sous_rubrique()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_sous_rubrique($frm)
{
	$sql = 'INSERT INTO peel_rubriques (
		parent_id
		, image
		, etat
		, technical_code
		, position
		, articles_review';
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= "
		, nom_" . $lng . "
		, description_" . $lng . '
		, meta_titre_' . $lng . '
		, meta_key_' . $lng . '
		, meta_desc_' . $lng;
	}
	$sql .= "
	) VALUES (
		" . intval($frm['parent_id']) . "
		, '" . nohtml_real_escape_string($frm['image']) . "'
		,'" . intval($frm['etat']) . "'
		,'" . nohtml_real_escape_string(vb($frm['technical_code'])) . "'
		,'" . intval($frm['position']) . "'
		,'" . nohtml_real_escape_string($frm['articles_review']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= "
		, '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'
		, '" . real_escape_string($frm['description_' . $lng]) . "'
		,'" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'
		,'" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'
		,'" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'";
	}
	$sql .= ')';
	query($sql);
}

/**
 * maj_rubrique()
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_rubrique($id, $frm)
{
	if ($frm['parent_id'] == $id) {
		$parent_id = 0;
	} else {
		$parent_id = $frm['parent_id'];
	}
	$sql = "UPDATE peel_rubriques
		SET parent_id = '" . intval($parent_id) . "'";

	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ",nom_" . $lng . " = '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'
		, description_" . $lng . " = '" . real_escape_string($frm['description_' . $lng]) . "'
		, meta_titre_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'
		, meta_key_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'
		, meta_desc_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'";
	}

	$sql .= ", image = '" . nohtml_real_escape_string($frm['image']) . "'
		, etat = '" . intval($frm['etat']) . "'
		, technical_code = '" . nohtml_real_escape_string($frm['technical_code']) . "'
		, position = '" . intval($frm['position']) . "'
		, articles_review = '" . nohtml_real_escape_string($frm['articles_review']) . "'
		WHERE id = '" . intval($id) . "'";

	query($sql);
}

/**
 * affiche_formulaire_liste_rubrique()
 *
 * @param integer $id
 * @return
 */
function affiche_formulaire_liste_rubrique($id)
{
	$frm["parent_id"] = $id;

	affiche_arbo_rubrique($GLOBALS['rubrique_options'], $frm["parent_id"]);
	affiche_liste_rubrique($frm["parent_id"]);
}

/**
 * affiche_liste_rubrique()
 *
 * @param mixed $parent_id
 * @return
 */
function affiche_liste_rubrique($parent_id)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_rubrique.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('ajout_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('rubrique_src', $GLOBALS['administrer_url'] . '/images/rubrique-24.gif');
	$tpl->assign('prod_cat_src', $GLOBALS['administrer_url'] . '/images/prod-cat-24.gif');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('rubrique_options', $GLOBALS['rubrique_options']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_LIST_TITLE', $GLOBALS['STR_ADMIN_RUBRIQUES_LIST_TITLE']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_ADD', $GLOBALS['STR_ADMIN_RUBRIQUES_ADD']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_ADD_SUBCATEGORY', $GLOBALS['STR_ADMIN_RUBRIQUES_ADD_SUBCATEGORY']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_ADD_ARTICLE', $GLOBALS['STR_ADMIN_RUBRIQUES_ADD_ARTICLE']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_DELETE_CATEGORY', $GLOBALS['STR_ADMIN_RUBRIQUES_DELETE_CATEGORY']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_POSITION_EXPLAIN', $GLOBALS['STR_ADMIN_RUBRIQUES_POSITION_EXPLAIN']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_IMAGE', $GLOBALS['STR_ADMIN_IMAGE']);
	$tpl->assign('STR_ADMIN_RUBRIQUE', $GLOBALS["STR_ADMIN_RUBRIQUE"]);
	$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	echo $tpl->fetch();
}

/**
 * affiche_formulaire_rubrique()
 *
 * @return
 */
function affiche_formulaire_rubrique(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_rubrique.tpl');
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vb($frm['id']))));
	$tpl->assign('mode', vb($frm['nouveau_mode']));
	$tpl->assign('id', intval(vb($frm['id'])));
	$tpl->assign('getmode', vb($_GET['mode']));
	$tpl->assign('nom', $frm['nom_' . $_SESSION['session_langue']]);
	$tpl->assign('category_href', get_content_category_url($frm['id'], $frm['nom_' . $_SESSION['session_langue']]));
	$tpl->assign('empty_parent_id', empty($frm['parent_id']));
	$tpl->assign('rubrique_options', $GLOBALS['rubrique_options']);
	$tpl->assign('etat', vb($frm['etat']));
	$tpl->assign('position', vb($frm['position']));
	$tpl->assign('articles_review', vb($frm['articles_review']));
	$tpl->assign('technical_code', vb($frm['technical_code']));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => vb($frm['nom_' . $lng]),
			'description_te' => getTextEditor('description_' . $lng, 760, 500, String::html_entity_decode_if_needed(vb($frm['description_' . $lng]))),
			'meta_key' => vb($frm['meta_key_' . $lng]),
			'meta_desc' => vb($frm['meta_desc_' . $lng]),
			'meta_titre' => vb($frm['meta_titre_' . $lng]),
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	if (!empty($frm["image"])) {
		$tpl->assign('image', array('src' => $GLOBALS['repertoire_upload'] . '/' . $frm["image"],
				'name' => $frm["image"],
				'sup_href' => get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=image'
				));
	}
	$tpl->assign('titre_soumet', $frm["titre_soumet"]);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_UPDATE', $GLOBALS['STR_ADMIN_RUBRIQUES_UPDATE']);
	$tpl->assign('STR_ADMIN_SEE_RESULT_IN_REAL', $GLOBALS['STR_ADMIN_SEE_RESULT_IN_REAL']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_ADD', $GLOBALS['STR_ADMIN_RUBRIQUES_ADD']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_PARENT', $GLOBALS['STR_ADMIN_RUBRIQUES_PARENT']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_AT_ROOT', $GLOBALS['STR_ADMIN_AT_ROOT']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_DISPLAY_MODE', $GLOBALS['STR_ADMIN_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_DISPLAY_SUMMARIES', $GLOBALS['STR_ADMIN_RUBRIQUES_DISPLAY_SUMMARIES']);
	$tpl->assign('STR_ADMIN_RUBRIQUES_DISPLAY_NO_SUMMARY', $GLOBALS['STR_ADMIN_RUBRIQUES_DISPLAY_NO_SUMMARY']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_DESCRIPTION', $GLOBALS['STR_ADMIN_DESCRIPTION']);
	$tpl->assign('STR_ADMIN_META_TITLE', $GLOBALS['STR_ADMIN_META_TITLE']);
	$tpl->assign('STR_ADMIN_META_KEYWORDS', $GLOBALS['STR_ADMIN_META_KEYWORDS']);
	$tpl->assign('STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN', $GLOBALS['STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_META_DESCRIPTION', $GLOBALS['STR_ADMIN_META_DESCRIPTION']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_FILE_NAME', $GLOBALS['STR_ADMIN_FILE_NAME']);
	$tpl->assign('STR_ADMIN_DELETE_IMAGE', $GLOBALS['STR_ADMIN_DELETE_IMAGE']);
	echo $tpl->fetch();
}

/**
 * Supprime l'image de la rubrique spécifiée par $id
 *
 * @param integer $id
 * @param mixed $file
 * @return
 */
function supprime_fichier_rubrique($id, $file)
{
	/* Charge les infos du produit. */
	switch ($file) {
		case "image":
			$sql = "SELECT image 
				FROM peel_rubriques 
				WHERE id = '" . intval($id) . "'";
			$res = query($sql);
			$file = fetch_assoc($res);
			query("UPDATE peel_rubriques 
				SET image = '' 
				WHERE id = '" . intval($id) . "'");
			break;
	}
	delete_uploaded_file_and_thumbs($file['image']);
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_RUBRIQUES_MSG_DELETED_OK'], $file['image'])))->fetch();
}

?>
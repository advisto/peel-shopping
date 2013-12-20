<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: marques.php 39392 2013-12-20 11:08:42Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$DOC_TITLE = $GLOBALS['STR_ADMIN_MARQUES_TITLE'];
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
			$frm['nom_' . $lng] = "Marque langue $lng";
			$frm['description_' . $lng] = "";

			/* gestion des meta */
			$frm['meta_titre_' . $lng] = "";
			$frm['meta_key_' . $lng] = "";
			$frm['meta_desc_' . $lng] = "";
		}
		/* gestion des promotions sur les marques */
		if (is_marque_promotion_module_active()) {
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
			WHERE id = " . intval($id));
		if ($frm = fetch_assoc($qid)) {
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
	$qid = query("SELECT nom_" . $_SESSION['session_langue'] . " as name 
		FROM peel_marques 
		WHERE id = " . intval($id));
	if ($this_brand = fetch_assoc($qid)) {
		/* efface cette marque */
		query("DELETE FROM peel_marques WHERE id = '" . intval($id) . "'");
		$message = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_MARQUES_MSG_BRAND_DELETED_OK'], String::html_entity_decode_if_needed($this_brand['name'])))->fetch();
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
	$sql = "INSERT INTO peel_marques (
		image
		, etat
		, position";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . ", description_" . $lng;
		$sql .= ", meta_titre_" . $lng;
		$sql .= ", meta_key_" . $lng;
		$sql .= ", meta_desc_" . $lng;
	}
	$sql .= ", promotion_devises, promotion_percent
	) VALUES (
		'" . nohtml_real_escape_string($frm['image']) . "'
		,'" . intval(vn($frm['etat'])) . "'
		,'" . intval($frm['position']) . "'";
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
	$promo = "UPDATE peel_produits 
		SET on_promo='".intval($on_promo)."' 
		WHERE id_marque='" . intval($_POST['id']) . "'";
	query($promo);

	$sql = "UPDATE peel_marques
		SET image = '" . nohtml_real_escape_string($frm['image']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . "='" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
		$sql .= ", description_" . $lng . "='" . real_escape_string($frm['description_' . $lng]) . "'";
		$sql .= ", meta_titre_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'";
		$sql .= ", meta_key_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'";
		$sql .= ", meta_desc_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'";
	}
	$sql .= ", etat = '" . vn($frm['etat']) . "'
		, position = '" . intval($frm['position']) . "'
		, promotion_devises = '" . floatval(vn($frm['promotion_devises'])) . "'
		, promotion_percent = '" . floatval(vn($frm['promotion_percent'])) . "'
	WHERE id = '" . intval($_POST['id']) . "'";
	$qid = query($sql);
}

/**
 * Affiche un formulaire de marques vide
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

	/* Affiche la liste des marques, en présélectionnant la marque choisie. */
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
		ORDER BY position";
	$Links = new Multipage($sql, 'marques');
	$results_array = $Links->Query();

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_marque.tpl');
	$tpl->assign('href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');

	if (!empty($results_array)) {
		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $this_brand) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'nom' => $this_brand['nom_' . $_SESSION['session_langue']],
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $this_brand['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $this_brand['id'],
				'id' => $this_brand['id'],
				'img_src' => (!empty($this_brand['image']) ? $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($this_brand['image'], 80, 50, 'fit') : null),
				'position' => $this_brand['position'],
				'etat_onclick' => 'change_status("marques", "' . $this_brand['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($this_brand['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('STR_ADMIN_MARQUES_TITLE', $GLOBALS['STR_ADMIN_MARQUES_TITLE']);
	$tpl->assign('STR_ADMIN_MARQUES_ADD_BRAND', $GLOBALS['STR_ADMIN_MARQUES_ADD_BRAND']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
	$tpl->assign('STR_ADMIN_IMAGE', $GLOBALS['STR_ADMIN_IMAGE']);
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
			'description_te' => getTextEditor('description_' . $lng, '100%', 500, String::html_entity_decode_if_needed(vb($frm['description_' . $lng]))),
			'meta_titre' => $frm['meta_titre_' . $lng],
			'meta_key' => $frm['meta_key_' . $lng],
			'meta_desc' => $frm['meta_desc_' . $lng],
			);
	}
	$tpl->assign('langs', $tpl_langs);

	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	if (!empty($frm["image"])) {
		$tpl->assign('image', array('src' => $GLOBALS['repertoire_upload'] . '/' . $frm["image"],
				'nom' => $frm["image"],
				'drop_href' => get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=image',
				));
	}
	$tpl->assign('is_marque_promotion_module_active', is_marque_promotion_module_active());
	if (is_marque_promotion_module_active()) {
		$tpl->assign('promotion_devises', $frm["promotion_devises"]);
		$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
		$tpl->assign('promotion_percent', $frm["promotion_percent"]);
	}
	$tpl->assign('titre_soumet', $frm["titre_soumet"]);
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
	$tpl->assign('STR_ADMIN_IMAGE', $GLOBALS['STR_ADMIN_IMAGE']);
	$tpl->assign('STR_ADMIN_FILE_NAME', $GLOBALS['STR_ADMIN_FILE_NAME']);
	$tpl->assign('STR_ADMIN_DELETE_IMAGE', $GLOBALS['STR_ADMIN_DELETE_IMAGE']);
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
	/* Charge les infos du produit. */
	switch ($file) {
		case "image":
			$sql = "SELECT image 
				FROM peel_marques 
				WHERE id = '" . intval($id) . "'";
			$res = query($sql);
			$file = fetch_assoc($res);
			query("UPDATE peel_marques 
				SET image = '' 
				WHERE id = '" . intval($id) . "'");
			break;
	}
	delete_uploaded_file_and_thumbs($file['image']);
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_FILE_DELETED'], $file['image'])))->fetch();
}

?>
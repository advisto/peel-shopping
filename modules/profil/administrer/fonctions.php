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
function profil_hook_admin_menu_items($params) {
	$result['menu_items']['manage_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/profil/administrer/profil.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_PROFIL"];
	return $result;
}

/**
 * Affiche un formulaire vierge pour ajouter un profil
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_profil(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();	
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['name_' . $lng] = "";
			$frm['description_document_' . $lng] = "";
			$frm['document_' . $lng] = "";
		}
		$frm['priv'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_MODULE_PROFIL_ADMIN_CREATE'];
	affiche_formulaire_profil($frm);
}

/**
 * Affiche le formulaire de modification pour le profil sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_profil($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_profil
			WHERE id = " . intval($id) . " AND  " . get_filter_site_cond('profil', null, true) . "");
		$frm = fetch_assoc($qid);
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
	affiche_formulaire_profil($frm);
}

/**
 * affiche_formulaire_profil()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_profil(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/profilAdmin_formulaire_profil.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'name' => $frm['name_' . $lng],
			'description_document' => vb($frm['description_document_' . $lng]),
			'document' => get_uploaded_file_infos('document_' . $lng, vb($frm['document_' . $lng]), get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=document_' . $lng)
			);
	}
	$tpl->assign('langs', $tpl_langs);

	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('priv', $frm["priv"]);
	$tpl->assign('document_delete_icon_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('titre_bouton', $frm["titre_bouton"]);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_TITLE', $GLOBALS['STR_MODULE_PROFIL_ADMIN_TITLE']);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_EXPLAIN', $GLOBALS['STR_MODULE_PROFIL_ADMIN_EXPLAIN']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_DESCRIPTION', $GLOBALS['STR_ADMIN_DESCRIPTION']);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_DESCRIPTION_EXPLAIN', $GLOBALS['STR_MODULE_PROFIL_ADMIN_DESCRIPTION_EXPLAIN']);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_UPLOAD_DOCUMENT', $GLOBALS['STR_MODULE_PROFIL_ADMIN_UPLOAD_DOCUMENT']);
	$tpl->assign('STR_FILE', $GLOBALS['STR_FILE']);
	$tpl->assign('STR_ADMIN_FILE_NAME', $GLOBALS['STR_ADMIN_FILE_NAME']);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_ABBREVIATE', $GLOBALS['STR_MODULE_PROFIL_ADMIN_ABBREVIATE']);
	$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
	echo $tpl->fetch();
}

/**
 * ajoute le profil dans la table profil
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_profil(&$frm)
{
	$sql = "INSERT INTO peel_profil (
		priv
		, site_id";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= "
		, name_" . $lng."
		, document_" . $lng."
		, description_document_" . $lng."
		";
	}
	$sql .= "
	) VALUES (
		'" . nohtml_real_escape_string($frm['priv']) . "'
		, '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= "
		, '" . nohtml_real_escape_string(vb($frm['name_' . $lng])) . "'
		, '" . nohtml_real_escape_string(vb($frm['document_' . $lng])) . "'
		, '" . real_escape_string(vb($frm['description_document_' . $lng])) . "'
		";
	}
	$sql .= "
	)";
	$qid = query($sql);
}

/**
 * Met à jour le profil $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_profil($id, &$frm)
{
	$sql = "UPDATE peel_profil SET
		priv = '" . nohtml_real_escape_string($frm['priv']) . "'
		, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= "
		, name_" . $lng." = '" . nohtml_real_escape_string($frm['name_' . $lng]) . "'
		, document_" . $lng." = '" . nohtml_real_escape_string(vb($frm['document_' . $lng])) . "'
		, description_document_" . $lng." = '" . real_escape_string(vb($frm['description_document_' . $lng])) . "'
		";
	}
	$sql .= "
		WHERE id = '" . intval($id) . "'";
	query($sql);
}

/**
 * affiche_liste_profil()
 *
 * @param integer $start
 * @return
 */
function affiche_liste_profil($start)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/profilAdmin_liste.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');

	$query = query("SELECT id, name_".$_SESSION['session_langue']." AS name, priv, site_id
		FROM peel_profil
		WHERE " . get_filter_site_cond('profil', null, true) . "
		ORDER BY name ASC");
	if (!(num_rows($query) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			$tpl_results[] = array(
				'tr_rollover' => tr_rollover($i, true),
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'name' => $ligne['name'],
				'priv' => $ligne['priv'],
				'site_name' => get_site_name($ligne['site_id'])
			);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_TITLE', $GLOBALS['STR_MODULE_PROFIL_ADMIN_TITLE']);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_CREATE', $GLOBALS['STR_MODULE_PROFIL_ADMIN_CREATE']);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_LIST_EXPLAIN', $GLOBALS['STR_MODULE_PROFIL_ADMIN_LIST_EXPLAIN']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_PROFIL', $GLOBALS["STR_ADMIN_PROFIL"]);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_ABBREVIATE', $GLOBALS['STR_MODULE_PROFIL_ADMIN_ABBREVIATE']);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_UPDATE', $GLOBALS['STR_MODULE_PROFIL_ADMIN_UPDATE']);
	$tpl->assign('STR_MODULE_PROFIL_ADMIN_NOTHING_FOUND', $GLOBALS['STR_MODULE_PROFIL_ADMIN_NOTHING_FOUND']);
	echo $tpl->fetch();
}

/**
 * Supprime le fichier lié au produit spécifié par $id, au nom de file.
 *
 * @param integer $id
 * @param mixed $file
 * @return
 // */
function supprime_fichier_profil($id, $file) {
	if(StringMb::substr($file, 0, StringMb::strlen('document_'))=='document_') {
		$sql = "SELECT " . word_real_escape_string($file) . "
			FROM peel_profil
			WHERE id='" . intval($id) . "' AND  " . get_filter_site_cond('profil', null, true) . "";
		$res = query($sql);
		if ($file_infos = fetch_assoc($res)) {
			query("UPDATE peel_profil
				SET `" . word_real_escape_string($file) . "`=''
				WHERE id='" . intval($id) . "'  AND  " . get_filter_site_cond('profil', null, true));
		}
	}
	if (!empty($file_infos) && delete_uploaded_file_and_thumbs($file_infos[$file])) {
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_RUBRIQUES_MSG_DELETED_OK'], $file_infos[$file])))->fetch();
	}
}


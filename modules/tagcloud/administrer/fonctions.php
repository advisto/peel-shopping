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
function tagcloud_hook_admin_menu_items($params) {
	$result['menu_items']['content_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/tagcloud/administrer/tagcloud.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TAGCLOUD"];
	return $result;
}

/**
 * Affiche un formulaire vierge pour ajouter une recherche
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_recherche(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm['tag_name'] = "";
		$frm['nbsearch'] = "";
	}
	$frm['id'] = "";
	$frm['nouveau_mode'] = "insere";
	$frm['lang'] = $_SESSION['session_langue'];
	$frm["titre"] = $GLOBALS['STR_MODULE_TAGCLOUD_ADMIN_ADD_SEARCH'];
	$frm['titre_bouton'] = $GLOBALS['STR_MODULE_TAGCLOUD_ADMIN_CREATE'];

	affiche_formulaire_recherche($frm);
}

/**
 * Affiche le formulaire de modification pour la recherche sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_recherche($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations de la recherche */
		$qid = query("SELECT *
			FROM peel_tag_cloud
			WHERE id=" . intval($id) . " AND " . get_filter_site_cond('tag_cloud', null, true));
		$frm = fetch_assoc($qid);
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre"] = $GLOBALS['STR_MODULE_TAGCLOUD_ADMIN_MODIFY_THIS_TAG'];
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_recherche($frm);
}

/**
 * affiche_formulaire_recherche()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_recherche(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/tagcloudAdmin_formulaire_recherche.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('tag_name', $frm["tag_name"]);
	$tpl->assign('nbsearch', $frm["nbsearch"]);
	$tpl->assign('titre_bouton', $frm["titre_bouton"]);
	$tpl->assign('titre', $frm["titre"]);
	$tpl_options = array();
	$sql = "SELECT lang, nom_" . $_SESSION['session_langue'] . " AS name
		FROM peel_langues
		WHERE " . get_filter_site_cond('langues', null, true);
	$query = query($sql);
	while ($lang = fetch_object($query)) {
		$tpl_options[] = array(
			'value' => $lang->lang,
			'issel' => $frm["lang"] == $lang->lang,
			'name' => $lang->name
		);
	}
	$tpl->assign('options', $tpl_options);
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_MODULE_TAGCLOUD_ADMIN_TAG_NAME', $GLOBALS['STR_MODULE_TAGCLOUD_ADMIN_TAG_NAME']);
	$tpl->assign('STR_MODULE_TAGCLOUD_ADMIN_SEARCHES_COUNT', $GLOBALS['STR_MODULE_TAGCLOUD_ADMIN_SEARCHES_COUNT']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	echo $tpl->fetch();
}

/**
 * Efface la recherche
 *
 * @param integer $id
 * @return
 */
function supprime_recherche($id)
{
	query("DELETE FROM peel_tag_cloud
		WHERE id=" . intval($id) . " AND " . get_filter_site_cond('tag_cloud', null, true));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MODULE_TAGCLOUD_ADMIN_MSG_SEARCH_DELETED_OK']))->fetch();
}

/**
 * Ajoute la zone dans la table zone
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_recherche($frm)
{
	query("INSERT INTO peel_tag_cloud (
			tag_name
			, lang
			, nbsearch
			, site_id
		) VALUES (
			'" . nohtml_real_escape_string($frm['tag_name']) . "'
			,'" . nohtml_real_escape_string($frm['lang']) . "'
			,'" . nohtml_real_escape_string($frm['nbsearch']) . "'
			,'" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		)");
}

/**
 * Met à jour la recherche $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_recherche($id, $frm)
{
	query("UPDATE peel_tag_cloud
		SET	tag_name = '" . nohtml_real_escape_string($frm['tag_name']) . "',
			lang = '" . nohtml_real_escape_string($frm['lang']) . "',
			nbsearch = '" . nohtml_real_escape_string($frm['nbsearch']) . "', 
			site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		WHERE id = " . intval($id) . " AND " . get_filter_site_cond('tag_cloud', null, true));
}

/**
 * affiche_liste_recherche()
 *
 * @param integer $start
 * @return
 */
function affiche_liste_recherche($start)
{
	$sql = "SELECT *
		FROM peel_tag_cloud
		WHERE " . get_filter_site_cond('tag_cloud', null, true) . "
		ORDER BY nbsearch DESC";
	$Links = new Multipage($sql, 'tagcloud');
	$results_array = $Links->query();

	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/tagcloudAdmin_liste_recherche.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	
	if (!empty($results_array)) {
		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $ligne) {
			$tpl_results[] = array(
				'tr_rollover' => tr_rollover($i, true),
				'tag_name' => $ligne['tag_name'],
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'lang' => $ligne['lang'],
				'nbsearch' => $ligne['nbsearch'],
			);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('STR_MODULE_TAGCLOUD_ADMIN_LIST_TITLE', $GLOBALS["STR_MODULE_TAGCLOUD_ADMIN_LIST_TITLE"]);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS["STR_ADMIN_DELETE_WARNING"]);
	$tpl->assign('STR_MODULE_TAGCLOUD_ADMIN_NOTHING_FOUND', $GLOBALS["STR_MODULE_TAGCLOUD_ADMIN_NOTHING_FOUND"]);
	$tpl->assign('STR_MODULE_TAGCLOUD_ADMIN_ADD_SEARCH', $GLOBALS["STR_MODULE_TAGCLOUD_ADMIN_ADD_SEARCH"]);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS["STR_ADMIN_ACTION"]);
	$tpl->assign('STR_MODULE_TAGCLOUD_ADMIN_TAG_NAME', $GLOBALS["STR_MODULE_TAGCLOUD_ADMIN_TAG_NAME"]);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS["STR_ADMIN_LANGUAGE"]);
	$tpl->assign('STR_MODULE_TAGCLOUD_ADMIN_SEARCH_COUNT', $GLOBALS["STR_MODULE_TAGCLOUD_ADMIN_SEARCH_COUNT"]);
	$tpl->assign('STR_DELETE', $GLOBALS["STR_DELETE"]);
	$tpl->assign('STR_MODULE_TAGCLOUD_ADMIN_MODIFY_THIS_TAG', $GLOBALS["STR_MODULE_TAGCLOUD_ADMIN_MODIFY_THIS_TAG"]);
	echo $tpl->fetch();
}


/**
 * get_tag_cloud()
 *
 * @param integer $id
 * @return
 */
function get_tag_cloud($id)
{
	$r_tag = query("SELECT *
		FROM peel_tag_cloud
		WHERE id = '".intval($id)."' AND " . get_filter_site_cond('tag_cloud', null, true));
	if ( !empty($r_tag) )  {
		$tag = fetch_assoc($r_tag);
	} else {
		$tag = null;
	}
	return $tag;
}
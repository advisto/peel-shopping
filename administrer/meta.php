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
// $Id: meta.php 55332 2017-12-01 10:44:06Z sdelaporte $

define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_content,admin_webmastering,admin_communication");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_META_PAGE_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$id = vb($_REQUEST['id']);

$form_error_object = new FormError();
$frm = $_POST;

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_meta($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_meta($id, $frm);
		break;

	case "suppr" :
		supprime_meta($id);
		affiche_liste_meta();
		break;

	case "insere" :
	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_meta($_POST['id'], $_POST);
			affiche_liste_meta();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			if(!empty($_GET['id'])) {
				affiche_formulaire_modif_meta($_GET['id'], $frm);
			} else {
				affiche_formulaire_ajout_meta($frm);
			}
		}
		break;

	default :
		affiche_liste_meta();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * affiche_formulaire_modif_meta()
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_meta($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		$qid = query("SELECT *
			FROM peel_meta
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('meta', null, true));
		$frm = fetch_assoc($qid);
	}
	if (!empty($frm)) {
		$frm["nouveau_mode"] = "maj";
		$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		affiche_formulaire_meta($frm);
	} else {
		redirect_and_die(get_current_url(false).'?mode=ajout');
	}
}

/**
 * Affiche un formulaire vierge pour ajouter une information de meta
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_meta(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm['technical_code'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['site_id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_ADD'];

	affiche_formulaire_meta($frm);
}

/**
 * affiche_formulaire_meta()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_meta(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_meta.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'meta_titre' => vb($frm['meta_titre_' . $lng]),
			'meta_key' => vb($frm['meta_key_' . $lng]),
			'meta_desc' => vb($frm['meta_desc_' . $lng]),
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('technical_code', vb($frm['technical_code']));
	$tpl->assign('titre_bouton', $frm['titre_bouton']);
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_OR', $GLOBALS['STR_OR']);
	$tpl->assign('STR_ADMIN_META_PAGE_TITLE', $GLOBALS['STR_ADMIN_META_PAGE_TITLE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_META_TITLE', $GLOBALS['STR_ADMIN_META_TITLE']);
	$tpl->assign('STR_ADMIN_META_KEYWORDS', $GLOBALS['STR_ADMIN_META_KEYWORDS']);
	$tpl->assign('STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN', $GLOBALS['STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_META_DESCRIPTION', $GLOBALS['STR_ADMIN_META_DESCRIPTION']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	echo $tpl->fetch();
}

/**
 * Supprime le meta spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_meta($id)
{
	/* Efface le meta */
	query("DELETE FROM peel_meta WHERE id = " . intval($id) . " AND " . get_filter_site_cond('meta', null, true));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_META_META_DELETED'], $id)))->fetch();
}

/**
 * Met à jour le meta $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_meta($id, $frm)
{
	if(empty($id)) {
		$sql = 'INSERT INTO';
	} else {
		$sql = 'UPDATE';
	}
	$sql .= ' peel_meta SET
			technical_code = "' . nohtml_real_escape_string($frm['technical_code']) . '"
			, site_id = "' . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . '"
			';
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= '
			, meta_titre_' . $lng . ' = "' . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . '"
			, meta_key_' . $lng . ' = "' . nohtml_real_escape_string($frm['meta_key_' . $lng]) . '"
			, meta_desc_' . $lng . ' = "' . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . '"';
	}
	if(!empty($id)) {
		$sql .= '
		WHERE id = ' . intval($id) . "  AND " . get_filter_site_cond('meta', null, true);
	}
	/* Met à jour la table meta */
	$qid = query($sql);
}

/**
 * affiche_liste_meta()
 *
 * @return
 */
function affiche_liste_meta()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_meta.tpl');

	$anchor = '';
	$query = query("SELECT * 
		FROM peel_meta 
		WHERE " . get_filter_site_cond('meta', null, true));
	if (!(num_rows($query) == 0)) {
		$tpl_results = array();
		while ($ligne = fetch_assoc($query)) {
			// On génère le lien vers les métas ici :
			// - si le titre est vide, on se sert de la description, sinon des mots clés
			$meta_titre = StringMb::html_entity_decode_if_needed(trim($ligne['meta_titre_' . $_SESSION['session_langue']]));
			$meta_desc = StringMb::html_entity_decode_if_needed(trim($ligne['meta_desc_' . $_SESSION['session_langue']]));
			$meta_key = StringMb::html_entity_decode_if_needed(trim($ligne['meta_key_' . $_SESSION['session_langue']]));
			$anchor= '';
			if (!empty($meta_titre)) {
				$anchor = $meta_titre;
			} elseif (!empty($meta_desc)) {
				$anchor = $meta_desc;
			} elseif (!empty($meta_key)) {
				$meta_key_array = explode(',', $meta_key);
				for ($i = 0; $i < 4; $i++) {
					$anchor .= $meta_key_array[$i] . ' ';
				}
				$anchor .= ' ...';
			}
			if(empty($anchor)) {
				$anchor .= '['.$ligne['id'].']';
			}
			$tpl_results[] = array('href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'technical_code' => $ligne['technical_code'],
				'anchor' => $anchor,
				'site_name' => get_site_name($ligne['site_id']),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id']
				);
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_ADD', $GLOBALS['STR_ADMIN_ADD']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_META_PAGE_TITLE', $GLOBALS['STR_ADMIN_META_PAGE_TITLE']);
	$tpl->assign('STR_ADMIN_META_UPDATE', $GLOBALS['STR_ADMIN_META_UPDATE']);
	$tpl->assign('STR_ADMIN_META_EMPTY_EXPLAIN', $GLOBALS['STR_ADMIN_META_EMPTY_EXPLAIN']);
	echo $tpl->fetch();
}


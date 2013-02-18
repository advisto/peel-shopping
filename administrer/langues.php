<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: langues.php 35064 2013-02-08 14:16:40Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$DOC_TITLE = $GLOBALS['STR_ADMIN_LANGUES_TITLE'];
include("modeles/haut.php");

$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_langue($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_langue($_GET['id'], $frm);
		break;

	case "suppr" :
		supprime_langue($_GET['id']);
		affiche_liste_langue();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			echo insere_langue($_POST);
			affiche_liste_langue();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_langue($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_langue($_POST['id'], $_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_LANGUES_MSG_UPDATE_OK'], vn($_POST['id']))))->fetch();
			affiche_liste_langue();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_langue($_POST['id'], $frm);
		}
		break;

	case "repair" :
		foreach($GLOBALS['lang_codes'] as $this_lang) {
			echo insere_langue(array('lang' => $this_lang), true, vb($_GET['full']));
		}
		affiche_liste_langue();
		break;

	default :
		affiche_liste_langue();
		break;
}

include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un langue
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_langue(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		foreach ($GLOBALS['lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
		}
		$frm['flag'] = "";
		$frm['etat'] = "";
		$frm['url_rewriting'] = "";
		$frm['position'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['lang'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_LANGUES_ADD_LANGUAGE'];

	affiche_formulaire_langue($frm);
}

/**
 * Affiche le formulaire de modification pour la langue sélectionnée
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_langue($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		// Charge les informations du produit
		$qid = query("SELECT *
			FROM peel_langues
			WHERE id = " . intval($id));
		if ($frm = fetch_assoc($qid)) {
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_LANGUES_ERR_LANGUAGE_NOT_FOUND']))->fetch();
			return false;
		}
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
	affiche_formulaire_langue($frm);
}

/**
 * affiche_formulaire_langue()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_langue(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_langue.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl_langs = array();
	foreach ($GLOBALS['lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => $frm['nom_' . $lng]
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('is_modif', vb($_REQUEST['mode']) == 'modif');
	$tpl->assign('lang', $frm["lang"]);
	$tpl->assign('flag', $frm["flag"]);
	$tpl->assign('etat', $frm["etat"]);
	$tpl->assign('position', $frm["position"]);
	$tpl->assign('url_rewriting', $frm["url_rewriting"]);
	$tpl->assign('titre_bouton', $frm["titre_bouton"]);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_LANGUES_ADD_OR_MODIFY_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUES_ADD_OR_MODIFY_LANGUAGE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_LANGUES_FORMAT', $GLOBALS['STR_ADMIN_LANGUES_FORMAT']);
	$tpl->assign('STR_ADMIN_LANGUES_CODE_ISO_EXPLAIN', $GLOBALS['STR_ADMIN_LANGUES_CODE_ISO_EXPLAIN']);
	$tpl->assign('STR_ADMIN_LANGUES_FLAG_PATH', $GLOBALS['STR_ADMIN_LANGUES_FLAG_PATH']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_LANGUES_URL_REWRITING', $GLOBALS['STR_ADMIN_LANGUES_URL_REWRITING']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	echo $tpl->fetch();
}

/**
 * Supprime la langue spécifié par $id. Il faut supprimer la langue
 *
 * @param integer $id
 * @return
 */
function supprime_langue($id)
{
	$qid = query("SELECT nom_" . $_SESSION['session_langue'] . " AS nom
		FROM peel_langues
		WHERE id = " . intval($id));
	$l = fetch_assoc($qid);

	/* Efface la langue */
	query("DELETE FROM peel_langues
		WHERE id = " . intval($id));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_LANGUES_MSG_LANGUAGE_DELETED'], $l['nom'])))->fetch();
}

/**
 * Met à jour la langue $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_langue($id, $frm)
{
	if (empty($frm['lang']) || String::strlen($frm['lang']) != 2) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_LANGUES_FORMAT_EXPLAIN']))->fetch();
		return false;
	}
	/* Met à jour la table langue */

	$sql = "UPDATE peel_langues
		SET lang = '" . nohtml_real_escape_string(String::strtolower($frm['lang'])) . "'";
	foreach ($GLOBALS['lang_codes'] as $lng) {
		$sql .= "
		, nom_" . $lng . "= '" . nohtml_real_escape_string($frm['nom_' . $lng]) ."'";
	}
	$sql .= "
		, flag = '" . nohtml_real_escape_string($frm['flag']) . "'
		, etat = '" . intval(vb($frm['etat'])) . "'
		, url_rewriting = '" . nohtml_real_escape_string($frm['url_rewriting']) . "'
		, position = '" . intval($frm['position']) . "'
		WHERE id = '" . intval($id) . "'";

	query($sql);
}

/**
 * affiche_liste_langue()
 *
 * @return
 */
function affiche_liste_langue()
{
	if (is_annonce_module_active()) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_LANGUES_WARNING']))->fetch();
	}

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_langue.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');

	$result = query("SELECT *
		FROM peel_langues
		ORDER BY position ASC");
	if (!(num_rows($result) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($result)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'nom' => (!empty($ligne['nom_' . $_SESSION['session_langue']])?$ligne['nom_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'lang' => $ligne['lang'],
				'flag_src' => (!empty($ligne['flag']) ? ((String::strpos($ligne['flag'], '/') !== false) ? $ligne['flag'] : $GLOBALS['wwwroot_in_admin'] . '/lib/flag/' . $ligne['flag']) : null),
				'url_rewriting' => $ligne['url_rewriting'],
				'position' => $ligne['position'],
				'etat_onclick' => 'change_status("langues", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_ADMIN_LANGUES_TITLE', $GLOBALS['STR_ADMIN_LANGUES_TITLE']);
	$tpl->assign('STR_ADMIN_LANGUES_ADD_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUES_ADD_LANGUAGE']);
	$tpl->assign('STR_WARNING', $GLOBALS['STR_WARNING']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_LANGUES_EXPLAIN1', $GLOBALS['STR_ADMIN_LANGUES_EXPLAIN1']);
	$tpl->assign('STR_ADMIN_LANGUES_EXPLAIN2', $GLOBALS['STR_ADMIN_LANGUES_EXPLAIN2']);
	$tpl->assign('STR_ADMIN_LANGUES_EXPLAIN3', $GLOBALS['STR_ADMIN_LANGUES_EXPLAIN3']);
	$tpl->assign('STR_ADMIN_LANGUES_NOTHING_FOUND', $GLOBALS['STR_ADMIN_LANGUES_NOTHING_FOUND']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_ADMIN_LANGUES_EXTENSION', $GLOBALS['STR_ADMIN_LANGUES_EXTENSION']);
	$tpl->assign('STR_ADMIN_FLAG', $GLOBALS['STR_ADMIN_FLAG']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_LANGUES_UPDATE', $GLOBALS['STR_ADMIN_LANGUES_UPDATE']);
	$tpl->assign('STR_ADMIN_LANGUES_NOTHING_FOUND', $GLOBALS['STR_ADMIN_LANGUES_NOTHING_FOUND']);
	$tpl->assign('STR_ADMIN_URL_REWRITING', $GLOBALS['STR_ADMIN_URL_REWRITING']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_LANGUES_REPAIR_LINK', $GLOBALS['STR_ADMIN_LANGUES_REPAIR_LINK']);
	echo $tpl->fetch();
}

?>
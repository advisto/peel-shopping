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
// $Id: couleurs.php 39392 2013-12-20 11:08:42Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$DOC_TITLE = $GLOBALS['STR_ADMIN_COULEURS_COLORS_TITLE'];

$output = '';
$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		$output .= affiche_formulaire_ajout_couleur($frm);
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_couleur($_GET['id'], $frm);
		break;

	case "suppr" :
		$output .= supprime_couleur($_GET['id']);
		$output .= affiche_liste_couleur();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= insere_couleur($_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_COULEURS_MSG_COLOR_CREATED'], vb($frm['nom_' . $_SESSION["session_langue"]]))))->fetch();
			$output .= affiche_liste_couleur();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_ajout_couleur($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= maj_couleur($_POST['id'], $_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_COULEURS_MSG_COLOR_UPDATED'], vn($_POST['id']))))->fetch();
			$output .= affiche_liste_couleur();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_modif_couleur($_GET['id'], $frm);
		}
		break;

	default :
		$output .= affiche_liste_couleur();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter une couleur
 *
 * @return
 */
function affiche_formulaire_ajout_couleur(&$frm)
{
	if(empty($frm)) {
		$frm['position'] = 0;
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
		}
	}
	/* Default value*/
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_COULEURS_ADD_COLOR_BUTTON'];

	return affiche_formulaire_couleur($frm);
}

/**
 * Affiche le formulaire de modification pour la couleur sélectionnée
 *
 * @param integer $id
 * @return
 */
function affiche_formulaire_modif_couleur($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		$qid = query("SELECT *
			FROM peel_couleurs
			WHERE id = " . intval($id));
		if ($frm = fetch_assoc($qid)) {
		} else {
			return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_COULEURS_ERR_COLOR_NOT_FOUND']))->fetch();
		}
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
	return affiche_formulaire_couleur($frm);
}

/**
 * affiche_formulaire_couleur()
 *
 * @return
 */
function affiche_formulaire_couleur(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_couleur.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => $frm['nom_' . $lng]
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('position', $frm["position"]);
	$tpl->assign('titre_bouton', $frm["titre_bouton"]);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	return $tpl->fetch();
}

/**
 * Supprime la couleur spécifié par $id. Il faut supprimer la couleur
 * puis les entrées correspondantes de la table couleur_categories
 *
 * @param integer $id
 * @return
 */
function supprime_couleur($id)
{
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_COULEURS_MSG_COLOR_DELETED'], get_color_name($id))))->fetch();
	/* Efface la couleur */
	query("DELETE FROM peel_couleurs WHERE id = '" . intval($id) . "'");
	/* Efface cette couleur de la table produits_couleur */
	query("DELETE FROM peel_produits_couleurs WHERE couleur_id = '" . intval($id) . "'");
	/* Supprime le stock correspondant */
	if (is_stock_advanced_module_active()) {
		query("DELETE FROM peel_stocks WHERE couleur_id = '" . intval($id) . "'");
	}
}

/**
 * Ajoute la couleur dans la table couleur
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_couleur($frm)
{
	$sql = "INSERT INTO peel_couleurs (
			position
			";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng;
	}
	$sql .= "
	) VALUES (
		'" . intval($frm['position']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= ")";

	query($sql);
}

/**
 * Met à jour le couleur $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_couleur($id, $frm)
{
	/* Met à jour la table couleur */
	$sql = "UPDATE peel_couleurs
			SET position = '" . intval($frm['position']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . "='" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= "WHERE id = '" . intval($id) . "'";
	query($sql);
}

/**
 * affiche_liste_couleur()
 *
 * @return
 */
function affiche_liste_couleur()
{
	$sql = "SELECT c.*
		FROM peel_couleurs c
		ORDER BY c.position ASC, c.nom_" . $_SESSION['session_langue'] . " ASC";
	$result = query($sql);

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_couleur.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	if (!(num_rows($result) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($result)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, null, null, 'sortable_'.$ligne['id']),
				'nom' => (!empty($ligne['nom_' . $_SESSION['session_langue']])?$ligne['nom_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'position' => $ligne['position']
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$GLOBALS['sortable_rpc'] = 'rpc_positions.php?mode=couleurs';
	$tpl->assign('STR_ADMIN_COULEURS_COLORS_TITLE', $GLOBALS['STR_ADMIN_COULEURS_COLORS_TITLE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_ADMIN_COULEURS_MODIFY_COLOR', $GLOBALS['STR_ADMIN_COULEURS_MODIFY_COLOR']);
	$tpl->assign('STR_ADMIN_COULEURS_NO_COLOR_FOUND', $GLOBALS['STR_ADMIN_COULEURS_NO_COLOR_FOUND']);
	$tpl->assign('STR_ADMIN_COULEURS_ADD_COLOR_BUTTON', $GLOBALS['STR_ADMIN_COULEURS_ADD_COLOR_BUTTON']);
	$tpl->assign('STR_ADMIN_COULEURS_LIST_EXPLAIN', $GLOBALS['STR_ADMIN_COULEURS_LIST_EXPLAIN']);
	return $tpl->fetch();
}

?>
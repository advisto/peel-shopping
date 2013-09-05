<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: types.php 37904 2013-08-27 21:19:26Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$DOC_TITLE = $GLOBALS["STR_ADMIN_TYPES_TITLE"];

$output = '';
$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		$output .= affiche_formulaire_ajout_type($frm);
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_type($_GET['id'], $frm);
		break;

	case "suppr" :
		$output .= supprime_type($_GET['id']);
		$output .= affiche_liste_type();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= insere_type($_POST);
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_TYPES_MSG_CREATED_OK'], vb($_POST['nom_' . $_SESSION["session_langue"]]))))->fetch();
			$output .= affiche_liste_type();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .=  $form_error_object->text('token');
			}
			$output .= affiche_formulaire_ajout_type($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$output .= maj_type($_POST['id'], $_POST);
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_TYPES_MSG_UPDATED_OK'], vn($_POST['id']))))->fetch();
			$output .= affiche_liste_type();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			$output .= affiche_formulaire_modif_type($_GET['id'], $frm);
		}
		break;

	default :
		$output .= affiche_liste_type();
		break;
}
include("modeles/haut.php");
echo $output;
include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un type
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_type(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm['position'] = 0;
		$frm['etat'] = 0;
		$frm['without_delivery_address'] = 0;
		$frm['is_socolissimo'] = 0;
		$frm['is_icirelais'] = 0;
		$frm['tnt_threshold'] = 0;
		$frm['is_tnt'] = 0;
		$frm['fianet_type_transporteur'] = 0;
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
		}
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_TYPES_CREATE'];

	return affiche_formulaire_type($frm);
}

/**
 * Affiche le formulaire de modification pour le type sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_type($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du type */
		$qid = query("SELECT *
			FROM peel_types
			WHERE id = " . intval($id) . "");
		$frm = fetch_assoc($qid);
	}
	$frm['id'] = $id;

	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	return affiche_formulaire_type($frm);
}

/**
 * affiche_formulaire_type()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_type(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_type.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', vb($frm['nouveau_mode']));
	$tpl->assign('id', intval(vb($frm['id'])));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => $frm['nom_' . $lng]
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('position', $frm['position']);
	$tpl->assign('etat', vb($frm['etat']));
	$tpl->assign('without_delivery_address', $frm['without_delivery_address']);
	$tpl->assign('is_socolissimo_module_active', is_socolissimo_module_active());
	$tpl->assign('is_socolissimo', $frm['is_socolissimo']);
	$tpl->assign('is_icirelais_module_active', is_icirelais_module_active());
	$tpl->assign('is_icirelais', $frm['is_icirelais']);
	$tpl->assign('is_fianet_module_active', is_fianet_module_active());
	$tpl->assign('is_tnt_module_active', is_tnt_module_active());
	$tpl->assign('tnt_threshold', vb($frm['tnt_threshold']));
	$tpl->assign('is_tnt', vb($frm['is_tnt']));
	$tpl->assign('fianet_type_transporteur', vb($frm['fianet_type_transporteur']));
	$tpl->assign('titre_bouton', $frm['titre_bouton']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_TYPES_FORM_TITLE', $GLOBALS['STR_ADMIN_TYPES_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_SHIP_ADDRESS', $GLOBALS['STR_SHIP_ADDRESS']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_ADMIN_TYPES_NO_DELIVERY', $GLOBALS['STR_ADMIN_TYPES_NO_DELIVERY']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	$tpl->assign('STR_ADMIN_TYPES_LINK_TO_ICIRELAIS', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_ICIRELAIS']);
	$tpl->assign('STR_ADMIN_TYPES_TNT', $GLOBALS['STR_ADMIN_TYPES_TNT']);
	$tpl->assign('STR_ADMIN_TYPES_LINK_TO_TNT', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_TNT']);
	$tpl->assign('STR_ADMIN_TYPES_TNT_DESTINATION', $GLOBALS['STR_ADMIN_TYPES_TNT_DESTINATION']);
	$tpl->assign('STR_ADMIN_TYPES_TNT_HOME', $GLOBALS['STR_ADMIN_TYPES_TNT_HOME']);
	$tpl->assign('STR_ADMIN_TYPES_TNT_DELIVERY_POINT', $GLOBALS['STR_ADMIN_TYPES_TNT_DELIVERY_POINT']);
	$tpl->assign('STR_ADMIN_TYPES_KWIXO', $GLOBALS['STR_ADMIN_TYPES_KWIXO']);
	$tpl->assign('STR_ADMIN_TYPES_LINK_TO_KWIXO', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_KWIXO']);
	$tpl->assign('STR_ADMIN_TYPES_LINK_TO_KWIXO_EXPLAIN', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_KWIXO_EXPLAIN']);
	return $tpl->fetch();
}

/**
 * Supprime le type spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_type($id)
{
	/* Efface le type */
	query("DELETE FROM peel_types 
		WHERE id=" . intval($id));
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_TYPES_MSG_DELETED_OK'], get_delivery_type_name($id))))->fetch();
}

/**
 * Ajoute le type dans la table type
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_type($frm)
{
	$sql = "INSERT INTO peel_types (position
		, without_delivery_address, etat";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng;
	}
	if (is_socolissimo_module_active()) {
		$sql .= ", is_socolissimo";
	}
	if (is_icirelais_module_active()) {
		$sql .= ", is_icirelais";
	}
	if (is_fianet_module_active()) {
		$sql .= ", fianet_type_transporteur";
	}
	if(is_tnt_module_active()){
		$sql .= ", is_tnt";
		$sql .= ", tnt_threshold";
	}
	$sql .= "
	) VALUES ('" . intval($frm['position']) . "'
		, '" . intval($frm['without_delivery_address']) . "'
		, '" . intval($frm['etat']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	if (is_socolissimo_module_active()) {
		$sql .= ", '" . intval($frm['is_socolissimo']) . "'";
	}
	if (is_icirelais_module_active()) {
		$sql .= ", '" . intval($frm['is_icirelais']) . "'";
	}
	if (is_fianet_module_active()) {
		$sql .= ", '" . intval($frm['fianet_type_transporteur']) . "'";
	}
	if(is_tnt_module_active()){
		$sql .= ", '" . intval($frm['is_tnt']) . "'";
		$sql .= ", '" . intval($frm['tnt_threshold']) . "'";
	}
	$sql .= ")";

	query($sql);
}

/**
 * Met à jour le type $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_type($id, $frm)
{
	$sql = "UPDATE peel_types SET position = '" . nohtml_real_escape_string($frm['position']) . "'
		, without_delivery_address='" . intval($frm['without_delivery_address']) . "'
		, etat='" . intval(vn($frm['etat'])) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . " = '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	if (is_socolissimo_module_active()) {
		$sql .= ", is_socolissimo = '" . intval($frm['is_socolissimo']) . "'";
	}
	if (is_icirelais_module_active()) {
		$sql .= ", is_icirelais = '" . intval($frm['is_icirelais']) . "'";
	}
	if (is_fianet_module_active()) {
		$sql .= ", fianet_type_transporteur = '" . intval($frm['fianet_type_transporteur']) . "'";
	}
	if(is_tnt_module_active()){
		$sql .= ", is_tnt = '".intval($frm['is_tnt'])."'";
		$sql .= ", tnt_threshold = '".intval($frm['tnt_threshold'])."'";
	}
	$sql .= " WHERE id = '" . intval($id) . "'";
	query($sql);
}

/**
 * affiche_liste_type()
 *
 * @return
 */
function affiche_liste_type()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_type.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');

	$result = query("SELECT id, nom_" . $_SESSION['session_langue'] . ", position, etat
		FROM peel_types t
		ORDER BY t.position");
	if (!(num_rows($result) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($result)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, null, null, 'sortable_'.$ligne['id']),
				'nom' => (!empty($ligne['nom_' . $_SESSION['session_langue']])?$ligne['nom_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'modif_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'etat_onclick' => 'change_status("types", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				'position' => $ligne['position']
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$GLOBALS['sortable_rpc'] = 'rpc_positions.php?mode=types';
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_TYPES_TITLE', $GLOBALS['STR_ADMIN_TYPES_TITLE']);
	$tpl->assign('STR_ADMIN_TYPES_EXPLAIN', $GLOBALS['STR_ADMIN_TYPES_EXPLAIN']);
	$tpl->assign('STR_ADMIN_TYPES_CREATE', $GLOBALS['STR_ADMIN_TYPES_CREATE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_SHIPPING_TYPE', $GLOBALS['STR_SHIPPING_TYPE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_TYPES_UPDATE', $GLOBALS['STR_ADMIN_TYPES_UPDATE']);
	$tpl->assign('STR_ADMIN_TYPES_NOTHING_FOUND', $GLOBALS['STR_ADMIN_TYPES_NOTHING_FOUND']);
	return $tpl->fetch();
}

?>
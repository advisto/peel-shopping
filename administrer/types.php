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
// $Id: types.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_ADMIN_TYPES_TITLE"];

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
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_modif_type($_GET['id'], $frm);
		}
		break;

	default :
		$output .= affiche_liste_type();
		break;
}
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

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
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('types', null, true) . "");
		$frm = fetch_assoc($qid);
	}
	if (!empty($frm)) {
		$frm['id'] = $id;
		$frm["nouveau_mode"] = "maj";
		$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		return affiche_formulaire_type($frm);
	} else {
		redirect_and_die(get_current_url(false).'?mode=ajout');
	}
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
	$tpl->assign('on_franco_amount', vb($frm['on_franco_amount']));
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
	$tpl->assign('is_socolissimo_module_active', check_if_module_active('socolissimo'));
	if (check_if_module_active('socolissimo')) {
		$tpl->assign('is_socolissimo', $frm['is_socolissimo']);
	}
	$tpl->assign('is_icirelais_module_active', check_if_module_active('icirelais'));
	if (check_if_module_active('icirelais')) {
		$tpl->assign('is_icirelais', $frm['is_icirelais']);
	}
	$tpl->assign('is_kiala_module_active', check_if_module_active('kiala'));
	if(check_if_module_active('kiala')) {
		$tpl->assign('is_kiala', vb($frm['is_kiala']));
		$tpl->assign('STR_ADMIN_TYPES_LINK_TO_KIALA', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_KIALA']);
	}
	$tpl->assign('is_fianet_module_active', check_if_module_active('fianet'));
	$tpl->assign('is_tnt_module_active', check_if_module_active('tnt'));
	$tpl->assign('is_ups_module_active', check_if_module_active('ups'));
	$tpl->assign('tnt_threshold', vb($frm['tnt_threshold']));
	$tpl->assign('is_tnt', vb($frm['is_tnt']));
	$tpl->assign('is_ups', vb($frm['is_ups']));
	$tpl->assign('fianet_type_transporteur', vb($frm['fianet_type_transporteur']));
	$tpl->assign('titre_bouton', $frm['titre_bouton']);
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_TYPES_LINK_TO_SOCOLISSIMO', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_SOCOLISSIMO']);
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
	$tpl->assign('STR_ADMIN_TYPES_LINK_TO_ICIRELAIS', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_ICIRELAIS']);
	$tpl->assign('STR_ADMIN_TYPES_TNT', $GLOBALS['STR_ADMIN_TYPES_TNT']);
	$tpl->assign('STR_ADMIN_TYPES_LINK_TO_TNT', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_TNT']);
	$tpl->assign('STR_ADMIN_TYPES_TNT_DESTINATION', $GLOBALS['STR_ADMIN_TYPES_TNT_DESTINATION']);
	$tpl->assign('STR_ADMIN_TYPES_TNT_HOME', $GLOBALS['STR_ADMIN_TYPES_TNT_HOME']);
	$tpl->assign('STR_ADMIN_TYPES_TNT_DELIVERY_POINT', $GLOBALS['STR_ADMIN_TYPES_TNT_DELIVERY_POINT']);
	$tpl->assign('STR_ADMIN_TYPES_KWIXO', $GLOBALS['STR_ADMIN_TYPES_KWIXO']);
	$tpl->assign('STR_ADMIN_TYPES_LINK_TO_KWIXO', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_KWIXO']);
	$tpl->assign('STR_ADMIN_TYPES_LINK_TO_KWIXO_EXPLAIN', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_KWIXO_EXPLAIN']);
	$tpl->assign('STR_ADMIN_ZONES_FRANCO_LIMIT_AMOUNT', $GLOBALS['STR_ADMIN_ZONES_FRANCO_LIMIT_AMOUNT']);
	if (check_if_module_active('ups')) {
		$tpl->assign('STR_ADMIN_TYPES_LINK_TO_UPS', $GLOBALS['STR_ADMIN_TYPES_LINK_TO_UPS']);
	}
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
		WHERE id=" . intval($id) . " AND " . get_filter_site_cond('types', null, true));
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
	$sql = "INSERT INTO peel_types (position, on_franco_amount, site_id
		, without_delivery_address, etat";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng;
	}
	if (check_if_module_active('socolissimo')) {
		$sql .= ", is_socolissimo";
	}
	if (check_if_module_active('icirelais')) {
		$sql .= ", is_icirelais";
	}
	if (check_if_module_active('ups')) {
		$sql .= ", is_ups";
	}
	if (check_if_module_active('fianet')) {
		$sql .= ", fianet_type_transporteur";
	}
	if (check_if_module_active('tnt')){
		$sql .= ", is_tnt";
		$sql .= ", tnt_threshold";
	}
	if (check_if_module_active('kiala')) {
		$sql .= ", is_kiala";
	}
	$sql .= "
	) VALUES ('" . intval($frm['position']) . "', '" . nohtml_real_escape_string($frm['on_franco_amount']) . "', '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		, '" . intval($frm['without_delivery_address']) . "'
		, '" . intval($frm['etat']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	if (check_if_module_active('socolissimo')) {
		$sql .= ", '" . intval($frm['is_socolissimo']) . "'";
	}
	if (check_if_module_active('icirelais')) {
		$sql .= ", '" . intval($frm['is_icirelais']) . "'";
	}
	if (check_if_module_active('ups')) {
		$sql .= ", '" . intval($frm['is_ups']) . "'";
	}
	if (check_if_module_active('fianet')) {
		$sql .= ", '" . intval($frm['fianet_type_transporteur']) . "'";
	}
	if (check_if_module_active('tnt')){
		$sql .= ", '" . intval($frm['is_tnt']) . "'";
		$sql .= ", '" . intval($frm['tnt_threshold']) . "'";
	}
	if (check_if_module_active('kiala')) {
		$sql .= ", '" . intval($frm['is_kiala']) . "'";
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
		, on_franco_amount = '" . nohtml_real_escape_string($frm['on_franco_amount']) . "'
		, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		, without_delivery_address='" . intval($frm['without_delivery_address']) . "'
		, etat='" . intval(vn($frm['etat'])) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng . " = '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	if (check_if_module_active('socolissimo')) {
		$sql .= ", is_socolissimo = '" . intval($frm['is_socolissimo']) . "'";
	}
	if (check_if_module_active('icirelais')) {
		$sql .= ", is_icirelais = '" . intval(vn($frm['is_icirelais'])) . "'";
	}
	if (check_if_module_active('fianet')) {
		$sql .= ", fianet_type_transporteur = '" . intval($frm['fianet_type_transporteur']) . "'";
	}
	if (check_if_module_active('ups')) {
		$sql .= ", is_ups = '" . intval($frm['is_ups']) . "'";
	}
	if (check_if_module_active('kiala')) {
		$sql .= ", is_kiala = '" . intval($frm['is_kiala']) . "'";
	}
	if(check_if_module_active('tnt')){
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

	$query = query("SELECT id, nom_" . $_SESSION['session_langue'] . ", position, etat, site_id
		FROM peel_types t
		WHERE " . get_filter_site_cond('types', 't', true) . "
		ORDER BY t.position");
	if (!(num_rows($query) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, null, null, 'sortable_'.$ligne['id']),
				'nom' => (!empty($ligne['nom_' . $_SESSION['session_langue']])?$ligne['nom_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'modif_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'etat_onclick' => 'change_status("types", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				'position' => $ligne['position'],
				'site_name' => get_site_name($ligne['site_id'])
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$GLOBALS['sortable_rpc'] = 'rpc_positions.php?mode=types';
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
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


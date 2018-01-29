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
// $Id: pays.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_PAYS_TITLE'];

$frm = $_POST;
$form_error_object = new FormError();
$output = '';

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		$output .= affiche_formulaire_ajout_pays($frm);
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_pays($_GET['id'], $frm);
		break;

	case "suppr" :
		$output .= supprime_pays($_GET['id']);
		$output .= affiche_liste_pays();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$_POST['image'] = upload('image', false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($_POST['image']));
			$output .= insere_pays($_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PAYS_MSG_CREATED_OK'], vb($_POST['pays_' . $_SESSION["session_langue"]]))))->fetch();
			$output .= affiche_liste_pays();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_ajout_pays($frm);
		}
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$_POST['image'] = upload('image', false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($_POST['image']));
			$output .= maj_pays($_POST['id'], $_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PAYS_MSG_UPDATED_OK'], vn($_POST['id']))))->fetch();
			$output .= affiche_liste_pays();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_modif_pays(null, $frm);
		}
		break;

	default :
		$output .= affiche_liste_pays();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un pays
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_pays(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['pays_' . $lng] = "";
		}
		$frm['etat'] = "";
		$frm['zone'] = "";
		$frm['lang'] = "";
		$frm['iso'] = "";
		$frm['iso3'] = "";
		$frm['prices_decimal_separator'] = "";
		$frm['prices_thousands_separator'] = "";
		$frm['iso_num'] = "";
		$frm['position'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_ADD'];

	return affiche_formulaire_pays($frm);
}

/**
 * Affiche le formulaire de modification pour le pays sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_pays($id, &$frm)
{
	$output = '';
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du pays */
		$qid = query("SELECT *
			FROM peel_pays
			WHERE id = " . intval($id) . ' AND ' . get_filter_site_cond('pays', null, true));
		if ($frm = fetch_assoc($qid)) {
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_PAYS_ERR_NOT_FOUND']))->fetch();
		}
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
	$output .= affiche_formulaire_pays($frm);
	return $output;
}

/**
 * affiche_formulaire_pays()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_pays(&$frm)
{
	$output = '';
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_pays.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('iso', $frm["iso"]);
	$tpl->assign('iso3', $frm["iso3"]);
	$tpl->assign('prices_decimal_separator', $frm["prices_decimal_separator"]);
	$tpl->assign('prices_thousands_separator', $frm["prices_thousands_separator"]);
	$tpl->assign('iso_num', $frm["iso_num"]);
	$tpl->assign('etat', $frm["etat"]);
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'pays' => $frm['pays_' . $lng],
			);
	}
	$tpl->assign('langs', $tpl_langs);

	$tpl_options = array();
	$sql_zone = "SELECT id, nom_" . $_SESSION['session_langue'] . "
		FROM peel_zones
		WHERE " . get_filter_site_cond('zones') . "
		ORDER BY nom_" . $_SESSION['session_langue'];
	$res_zone = query($sql_zone);
	
	// Quand on est en mode multisite, la zone est aussi en mode multizone en SET.
	// On doit donc passer en array pour pouvoir effectuer les selected dans le select multiple
	while ($tab_zone = fetch_assoc($res_zone)) {
		$tpl_options[] = array('value' => intval($tab_zone['id']),
			'issel' => (!empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id'])?in_array($tab_zone['id'], explode(',', $frm['zone'])):vb($frm['zone']) == $tab_zone['id']),
			'name' => $tab_zone['nom_' . $_SESSION['session_langue']]
			);
	}
	if (check_if_module_active('departements')) {
		// Si le module département est actif, les zones d'expédition sont gérées par département. Cette option permet d'associer les départements à un pays, pour permettre le bon fonctionement du process de commande.
		$tpl_options[] = array('value' => '-1',
			'issel' => vb($frm['zone']) == '-1',
			'name' => $GLOBALS['STR_MODULE_DEPARTEMENT_USE_DEPARTEMENT_AS_ZONE_EXPEDITION']
			);
	}
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
	$tpl->assign('options', $tpl_options);
	$tpl->assign('position', $frm["position"]);
	$tpl->assign('titre_bouton', $frm["titre_bouton"]);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_PAYS_ADD_COUNTRY', $GLOBALS['STR_ADMIN_PAYS_ADD_COUNTRY']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
	$tpl->assign('STR_ADMIN_PAYS_ISO_CODES_HEADER', $GLOBALS['STR_ADMIN_PAYS_ISO_CODES_HEADER']);
	$tpl->assign('STR_ADMIN_PAYS_ISO_2', $GLOBALS['STR_ADMIN_PAYS_ISO_2']);
	$tpl->assign('STR_ADMIN_PAYS_ISO_3', $GLOBALS['STR_ADMIN_PAYS_ISO_3']);
	$tpl->assign('STR_ADMIN_PAYS_ISO_NUMERIC', $GLOBALS['STR_ADMIN_PAYS_ISO_NUMERIC']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	$tpl->assign('STR_SHIPPING_ZONE', $GLOBALS['STR_SHIPPING_ZONE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_SEPARATOR_PRICE', $GLOBALS['STR_ADMIN_SEPARATOR_PRICE']);
	$tpl->assign('STR_ADMIN_DECIMAL_SEPARATOR_PRICE', $GLOBALS['STR_ADMIN_DECIMAL_SEPARATOR_PRICE']);
	$tpl->assign('STR_ADMIN_THOUSANDS_SEPARATOR_PRICE', $GLOBALS['STR_ADMIN_THOUSANDS_SEPARATOR_PRICE']);
	
	$output .= $tpl->fetch();
	return $output;
}

/**
 * Supprime le pays spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_pays($id)
{
	$qid = query("SELECT pays_" . $_SESSION['session_langue'] . "
		FROM peel_pays
		WHERE id = " . intval($id) . ' AND ' . get_filter_site_cond('pays', null, true));
	$p = fetch_assoc($qid);

	/* Efface le pays */
	query("DELETE FROM peel_pays
		WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('pays', null, true));
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PAYS_MSG_DELETED_OK'], $p['pays_' . $_SESSION['session_langue']])))->fetch();
}

/**
 * Ajoute le pays dans la table pays
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_pays(&$frm)
{
	$sql = "INSERT INTO peel_pays (
		zone
		, etat
		, iso
		, iso3
		, prices_decimal_separator
		, prices_thousands_separator
		, iso_num
		, position
		, site_id";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", pays_" . $lng;
	}
	$sql .= "
	) VALUES (
		'" . nohtml_real_escape_string(get_zone_id_sql_set_value($frm['zone'])) . "'
		, '" . intval(vb($frm['etat'])) . "'
		, '" . nohtml_real_escape_string($frm['iso']) . "'
		, '" . nohtml_real_escape_string($frm['iso3']) . "'
		, '" . nohtml_real_escape_string($frm['prices_decimal_separator']) . "'
		, '" . nohtml_real_escape_string($frm['prices_thousands_separator']) . "'
		, '" . intval($frm['iso_num']) . "'
		, '" . intval($frm['position']) . "'
		, '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['pays_' . $lng]) . "'";
	}
	$sql .= "
	)";

	query($sql);
}

/**
 * Met à jour le pays $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_pays($id, $frm)
{
	$sql = "UPDATE peel_pays
		SET zone = '" . nohtml_real_escape_string(get_zone_id_sql_set_value(vn($frm['zone']))) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= " , pays_" . $lng . " = '" . nohtml_real_escape_string($frm['pays_' . $lng]) . "'";
	}
	$sql .= "
			, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
			, iso = '" . nohtml_real_escape_string($frm['iso']) . "'
			, iso3 = '" . nohtml_real_escape_string($frm['iso3']) . "'
			, prices_decimal_separator = '" . nohtml_real_escape_string($frm['prices_decimal_separator']) . "'
			, prices_thousands_separator = '" . nohtml_real_escape_string($frm['prices_thousands_separator']) . "'
			, iso_num = '" . intval($frm['iso_num']) . "'
			, etat = '" . intval(vb($frm['etat'])) . "'
			, position = '" . intval($frm['position']) . "'
		WHERE id = '" . intval($id) . "'";
	query($sql);
}

/**
 * affiche_liste_pays()
 *
 * @return
 */
function affiche_liste_pays()
{
	if (isset($_POST['etat']) && isset($_POST['zones'])) {
		if ($_POST['etat'] == 1) {
			$etat = 1;
		} else {
			$etat = 0;
		}
		$sql = "UPDATE peel_pays
			SET etat='" . intval($etat) . "'
			WHERE " . (!empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id'])?'FIND_IN_SET("' . intval($_POST['zones']) . '", zone)':'zone = "' . intval($_POST['zones']) . '"') . " AND " . get_filter_site_cond('pays', null, true);			
		query($sql);
	}

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_pays.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('action', get_current_url(false));

	$tpl_options = array();
	$sql_zone = "SELECT id, nom_" . $_SESSION['session_langue'] . "
		FROM peel_zones
		WHERE " . get_filter_site_cond('zones') . "
		ORDER BY nom_" . $_SESSION['session_langue'];
	$res_zone = query($sql_zone);
	while ($result = fetch_assoc($res_zone)) {
		$tpl_options[] = array('value' => intval($result['id']),
			'name' => $result['nom_' . $_SESSION['session_langue']]
			);
	}
	$tpl->assign('options', $tpl_options);

	$query = query("SELECT p.*, z.nom_" . $_SESSION['session_langue'] . " AS zone_name
		FROM peel_pays p
		LEFT JOIN peel_zones z ON z.id=p.zone AND " . get_filter_site_cond('zones', 'z') . "
		WHERE " . get_filter_site_cond('pays', "p", true) . "
		ORDER BY p.position ASC, p.pays_" . $_SESSION['session_langue'] . " ASC");

	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	if (!(num_rows($query) == 0)) {
		$tpl_results = array();
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			// On récupère les noms des zones lorsque le site est en multisite par une nouvelle requête
			if (!empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id'])) {
				$zones_array = array();
				$sql_name_zone = query("SELECT z.nom_" . $_SESSION['session_langue'] . " AS zone_name, z.id
						FROM peel_zones z
						WHERE FIND_IN_SET (z.id, '" . nohtml_real_escape_string($ligne['zone']) . "') AND " . get_filter_site_cond('peel_zones', "z", true));
				while ($result = fetch_assoc($sql_name_zone)) {
					$zones_array[] = StringMb::html_entity_decode_if_needed($result['zone_name']);
				}
				$zone =  real_escape_string(implode(', ',$zones_array));	
			} else {
				$zone = StringMb::html_entity_decode_if_needed($ligne['zone_name']);
			}
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, null, null, 'sortable_'.$ligne['id']),
				'nom' => $ligne['pays_' . $_SESSION['session_langue']],
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'pays' => $ligne['pays_' . $_SESSION['session_langue']],
				'flag' => getFlag($ligne['iso'], $ligne['pays_' . $_SESSION['session_langue']]),
				'zone' => vb($zone),
				'position' => $ligne['position'],
				'etat_onclick' => 'change_status("countries", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				'site_name' => get_site_name($ligne['site_id'])
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$GLOBALS['sortable_rpc'] = 'rpc_positions.php?mode=countries';
	
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_PAYS_LIST_EXPLAIN', $GLOBALS['STR_ADMIN_PAYS_LIST_EXPLAIN']);
	$tpl->assign('STR_ADMIN_PAYS_TITLE', $GLOBALS['STR_ADMIN_PAYS_TITLE']);
	$tpl->assign('STR_ADMIN_PAYS_CREATE', $GLOBALS['STR_ADMIN_PAYS_CREATE']);
	$tpl->assign('STR_ADMIN_PAYS_ZONE_UPDATE_LABEL', $GLOBALS['STR_ADMIN_PAYS_ZONE_UPDATE_LABEL']);
	$tpl->assign('STR_ADMIN_ACTIVATE', $GLOBALS['STR_ADMIN_ACTIVATE']);
	$tpl->assign('STR_ADMIN_DEACTIVATE', $GLOBALS['STR_ADMIN_DEACTIVATE']);
	$tpl->assign('STR_VALIDATE', $GLOBALS['STR_VALIDATE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
	$tpl->assign('STR_ADMIN_MENU_MANAGE_ZONES', $GLOBALS['STR_ADMIN_MENU_MANAGE_ZONES']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_PAYS_MODIFY', $GLOBALS['STR_ADMIN_PAYS_MODIFY']);
	$tpl->assign('STR_ADMIN_PAYS_NOTHING_FOUND', $GLOBALS['STR_ADMIN_PAYS_NOTHING_FOUND']);
	return $tpl->fetch();
}

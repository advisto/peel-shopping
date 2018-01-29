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
function attributs_hook_admin_menu_items($params) {
	$result['menu_items']['products_attributes'][$GLOBALS['wwwroot_in_admin'] . '/modules/attributs/administrer/nom_attributs.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_ATTRIBUTES"];
	$result['menu_items']['products_attributes'][$GLOBALS['wwwroot_in_admin'] . '/modules/attributs/administrer/attributs.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_OPTIONS"];
	return $result;
}

/**
 * Génération d'informations pour l'export de produits
 *
 * @param array $params
 * @return
 */
function attributs_hook_export_products_get_configuration_array(&$params) {
	$result = array();
	$sql_n = "SELECT *
		FROM peel_nom_attributs
		WHERE " . get_filter_site_cond('nom_attributs') . "
		ORDER BY id";
	$nom_attrib = query($sql_n);
	while ($this_attribut = fetch_assoc($nom_attrib)) {
		// On prépare des informations qui seront utilisées pour chaque ligne par la suite
		$GLOBALS['attribut_infos_array'][$this_attribut['id']] = $this_attribut;
		$result['product_field_names'][] = $this_attribut['nom_' . $_SESSION['session_langue']] . '#' . $this_attribut['id'];
	}
	return $result;
}

/**
 * Génération d'informations pour l'export de produits
 *
 * @param array $params
 * @return
 */
function attributs_hook_export_products_get_line_infos_array(&$params) {
	$result = array();
	// Récupération des valeurs pour les attributs
	$query_produits_attributs = query("SELECT ppa.nom_attribut_id, pa.id, pa.descriptif_" . $_SESSION['session_langue'] . " AS descriptif
		FROM peel_produits_attributs ppa
		LEFT JOIN peel_attributs pa ON pa.id=ppa.attribut_id AND pa.id_nom_attribut=ppa.nom_attribut_id AND " . get_filter_site_cond('attributs', 'pa')."
		WHERE produit_id='" . intval($params['id']) . "'");
	while ($this_attribut = fetch_assoc($query_produits_attributs)) {
		if (!empty($attribut_infos_array[$this_attribut['nom_attribut_id']])) {
			if ($attribut_infos_array[$this_attribut['nom_attribut_id']]['upload'] == 1) {
				// L'attribut concerné est un champ d'upload => on exporte cette info, sans notion d'id d'attribut car il n'y en a pas
				$this_value = '0#__upload';
			} elseif ($attribut_infos_array[$this_attribut['nom_attribut_id']]['texte_libre'] == 1) {
				// L'attribut concerné est un champ de texte libre => on exporte cette info, sans notion d'id d'attribut car il n'y en a pas
				$this_value = '0#__texte_libre';
			} else {
				$this_value = $this_attribut['id'] . '#' . $this_attribut['descriptif'];
			}
			$result[$GLOBALS['attribut_infos_array'][$this_attribut['nom_attribut_id']]['nom_' . $_SESSION['session_langue']] . '#' . $this_attribut['nom_attribut_id']][] = $this_value;
		}
	}
	return $result;
}

/**
 * Affiche un formulaire vierge pour ajouter un attribut
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_nom_attribut(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm['texte_libre'] = 0;
		$frm['upload'] = 0;
		$frm['mandatory'] = 0;
		$frm['technical_code'] = "";
		$frm['show_description'] = 1;
		$frm['disable_reductions'] = 0;
		if (empty($frm['type_affichage_attribut'])) {
			// On préremplit par une référence à la configuration générale du site
			// NB : On ne préremplit pas par la valeur par défaut utilisée sur le site pour permettre changement général plus facile si on n'a pas de spécificité par produit
			$frm['type_affichage_attribut'] = 3;
		}
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
		}
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS["STR_SUBMIT"];
	affiche_formulaire_nom_attribut($frm);
}

/**
 * Affiche le formulaire de modification pour l'attribut sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_nom_attribut($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_nom_attributs
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('nom_attributs', null, true) . "");
		$frm = fetch_assoc($qid);
	}
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "maj";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_nom_attribut($frm);
}

/**
 * affiche_formulaire_nom_attribut()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_nom_attribut(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/attributsAdmin_formulaire_nom.tpl');
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('etat', vb($frm['etat']));
	$tpl->assign('texte_libre', vb($frm['texte_libre']));
	$tpl->assign('upload', vb($frm['upload']));
	$tpl->assign('titre_bouton', vb($frm["titre_bouton"]));
	$tpl->assign('type_affichage_attribut', vn($frm["type_affichage_attribut"]));
	$tpl->assign('technical_code', vb($frm["technical_code"]));
	$tpl->assign('show_description', vb($frm["show_description"]));
	$tpl->assign('disable_reductions', vn($frm["disable_reductions"]));
	$tpl->assign('mandatory', vn($frm["mandatory"]));
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('code' => $lng,
			'value' => $frm['nom_' . $lng]
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
	$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_UPDATE_TITLE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_UPDATE_TITLE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	$tpl->assign('STR_TYPE', $GLOBALS['STR_TYPE']);
	$tpl->assign('STR_ADMIN_MANDATORY', $GLOBALS['STR_ADMIN_MANDATORY']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_PARAMETERS', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_PARAMETERS']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_OPTIONS_LIST_ATTRIBUTE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_OPTIONS_LIST_ATTRIBUTE']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_FREE_TEXT_ATTRIBUTE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_FREE_TEXT_ATTRIBUTE']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_UPLOAD_ATTRIBUTE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_UPLOAD_ATTRIBUTE']);
	$tpl->assign('STR_ADMIN_ATTRIBUT_STYLE_LINK', $GLOBALS['STR_ADMIN_ATTRIBUT_STYLE_LINK']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_DISPLAY_MODE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_DISPLAY_MODE']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_SELECT_MENU', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_SELECT_MENU']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_RADIO_BUTTONS', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_RADIO_BUTTONS']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_CHECKBOX', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_CHECKBOX']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_DEFAULT_DISPLAY_MODE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_DEFAULT_DISPLAY_MODE']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_NO_PROMOTION_OPTION_ATTRIBUT', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_NO_PROMOTION_OPTION_ATTRIBUT']);
	echo $tpl->fetch();
}

/**
 * Supprime l'attribut spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_nom_attribut($id)
{
	$qid = query("SELECT nom_" . $_SESSION['session_langue'] . "
		FROM peel_nom_attributs
		WHERE id = " . intval($id) . " AND " . get_filter_site_cond('nom_attributs', null, true));
	$col = fetch_assoc($qid);
	query("DELETE FROM peel_produits_attributs WHERE nom_attribut_id  = '" . intval($id) . "'");
	query("DELETE FROM peel_nom_attributs WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('nom_attributs', null, true));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_MSG_DELETED_OK'], StringMb::html_entity_decode_if_needed($col['nom_' . $_SESSION['session_langue']]))))->fetch();
}

/**
 * Ajoute l'attribut dans la table des attributs
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_nom_attribut($frm)
{
	$frm['texte_libre'] = 0;
	$frm['upload'] = 0;
		
	if (vn($frm['attribut_type']) == 1) {
		$frm['texte_libre'] = 1;
	} elseif (vn($frm['attribut_type']) == 2) {
		$frm['upload'] = 1;
	} 
	$sql = "INSERT INTO peel_nom_attributs (
			etat
			, site_id
			, mandatory
			";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . $lng;
	}
	$sql .= ", texte_libre, upload, technical_code, type_affichage_attribut, show_description, disable_reductions
	) VALUES ('" . intval($frm['etat']) . "'
			, '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
			, '" . intval($frm['mandatory']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= ", '" . intval($frm['texte_libre']) . "', '" . intval($frm['upload']) . "', '" . nohtml_real_escape_string($frm['technical_code']) . "', '" . nohtml_real_escape_string($frm['type_affichage_attribut']) . "', '" . nohtml_real_escape_string($frm['show_description']) . "', '" . nohtml_real_escape_string(vn($frm['disable_reductions'])) . "')";

	query($sql);
}

/**
 * Met à jour l'attribut $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_nom_attribut($id, $frm)
{
	$frm['texte_libre'] = 0;
	$frm['upload'] = 0;
		
	if (vn($frm['attribut_type']) == 1) {
		$frm['texte_libre'] = 1;
	} elseif (vn($frm['attribut_type']) == 2) {
		$frm['upload'] = 1;
	}
	$sql = "UPDATE peel_nom_attributs 
		SET etat='" . intval($frm['etat']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", nom_" . nohtml_real_escape_string($lng) . "='" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
	}
	$sql .= ", site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
			 , mandatory = '" . intval($frm['mandatory']) . "'
			 , texte_libre ='" . intval($frm['texte_libre']) . "'
			 , upload ='" . intval(vn($frm['upload'])) . "'
			 , technical_code ='" . nohtml_real_escape_string($frm['technical_code']) . "'
			 , type_affichage_attribut ='" . nohtml_real_escape_string($frm['type_affichage_attribut']) . "'
			 , show_description ='" . nohtml_real_escape_string($frm['show_description']) . "'
			 , disable_reductions ='" . nohtml_real_escape_string(vn($frm['disable_reductions'])) . "'
			WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('nom_attributs', null, true);
	query($sql);
	// Si le nom de l'attribut est un texte libre alors on retire toutes ces options :
	if (!empty($frm['texte_libre'])) {
		$sql = "SELECT DISTINCT produit_id 
			FROM peel_produits_attributs 
			WHERE nom_attribut_id = '" . intval($id) . "'";
		$query = query($sql);
		$sql = "DELETE FROM peel_attributs WHERE id_nom_attribut = '" . intval($id) . "'";
		query($sql);
		$sql = "DELETE FROM peel_produits_attributs WHERE nom_attribut_id = '" . intval($id) . "'";
		query($sql);
		while ($produit = fetch_assoc($query)) {
			$sql = query("INSERT INTO peel_produits_attributs (`produit_id`,`nom_attribut_id`,`attribut_id`) VALUES ('" . intval($produit['produit_id']) . "','" . intval($id) . "','0');");
		}
	}
}

/**
 * affiche_liste_nom_attribut()
 *
 * @param integer $start
 * @return
 */
function affiche_liste_nom_attribut($start)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/attributsAdmin_liste_nom.tpl');
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$query = query("SELECT id, nom_" . $_SESSION['session_langue'] . ", etat, texte_libre, upload, show_description, disable_reductions, site_id
		FROM peel_nom_attributs 
		WHERE " . get_filter_site_cond('nom_attributs', null, true) . "
		ORDER BY nom_" . $_SESSION['session_langue'] . "");
	$nr = num_rows($query);
	$tpl->assign('num_results', $nr);
	$tpl_results = array();
	if ($nr != 0) {
		$i = 0;
		while ($ligne = fetch_assoc($query)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'nom' => (!empty($ligne['nom_' . $_SESSION['session_langue']])?$ligne['nom_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'texte_libre' => $ligne['texte_libre'],
				'texte_libre_href' => $GLOBALS['wwwroot_in_admin'] . '/modules/attributs/administrer/attributs.php?mode=liste&attid=' . $ligne['id'],
				'upload' => $ligne['upload'],
				'etat_onclick' => 'change_status("attributs", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				'site_name' => get_site_name($ligne['site_id'])
				);
			$i++;
		}
	}
	$tpl->assign('results', $tpl_results);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_TITLE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_TITLE']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_EXPLAIN', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_EXPLAIN']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_CREATE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_CREATE']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_NOTHING_FOUND', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_NOTHING_FOUND']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_TYPE', $GLOBALS['STR_TYPE']);
	$tpl->assign('STR_ADMIN_CONFIRM_JAVASCRIPT', $GLOBALS['STR_ADMIN_CONFIRM_JAVASCRIPT']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_HANDLE_OPTIONS', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_HANDLE_OPTIONS']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_UPLOAD_FIELD', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_UPLOAD_FIELD']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_CUSTOM_TEXT', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_CUSTOM_TEXT']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_UPDATE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_UPDATE']);
	$tpl->assign('STR_ADMIN_UPDATE', $GLOBALS['STR_ADMIN_UPDATE']);
	echo $tpl->fetch();
}

/**
 * Fonctions d'attributs.php
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_attribut(&$frm, &$form_error_object)
{
	if(empty($frm)) {
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['descriptif_' . $lng] = "";
		}
		$frm["image"] = "";
		$frm["prix"] = 0;
		$frm['mandatory'] = 0;
		$frm['position'] = 0;
	}
	$frm['nouveau_mode'] = "insere";
	$frm["titre_soumet"] = $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_CREATE_THIS_OPTION"];
	affiche_formulaire_attribut($frm, $form_error_object);
}

/**
 * Charge les infos de la attributs
 *
 * @return
 */
function affiche_formulaire_modif_attribut(&$frm, &$form_error_object)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		$qid = query("SELECT *
			FROM peel_attributs
			WHERE id='" . intval($_GET['id']) . "' AND " . get_filter_site_cond('attributs', null, true));
		$frm = fetch_assoc($qid);
	}
	$frm["nouveau_mode"] = "maj";
	$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_attribut($frm, $form_error_object);
}

/**
 * supprime_attribut()
 *
 * @return
 */
function supprime_attribut()
{
	$id = intval($_GET['id']);

	$qid = query("SELECT descriptif_" . $_SESSION['session_langue'] . " AS descriptif
		FROM peel_attributs
		WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('attributs', null, true));

	if ($bd = fetch_assoc($qid)) {
		query("DELETE FROM peel_attributs WHERE id='" . intval($id) . "'");
		query("DELETE FROM peel_produits_attributs WHERE nom_attribut_id='" . intval($id) . "'");
		$message = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_MSG_OPTION_DELETED_OK"], StringMb::html_entity_decode_if_needed($bd['descriptif']))))->fetch();
		echo $message;
	}
}

/**
 * insere_attribut()
 *
 * @param integer $id
 * @param array $frm
 * @return
 */
function insere_attribut($id, $frm)
{
	$prix = get_float_from_user_input($frm['prix']);
	$prix_revendeur = get_float_from_user_input($frm['prix_revendeur']);
	$sql = "INSERT INTO peel_attributs (id_nom_attribut, image, prix, prix_revendeur, position, mandatory, site_id";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", descriptif_" . $lng;
	}
	$sql .= ") VALUES ('" . intval($id) . "', '" . nohtml_real_escape_string($frm['image']) . "', '" . nohtml_real_escape_string($prix) . "', '" . nohtml_real_escape_string($prix_revendeur) . "', '" . intval($frm['position']) . "', '" . intval($frm['mandatory']) . "', '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . nohtml_real_escape_string($frm['descriptif_' . $lng]) . "'";
	}
	$sql .= ")";
	$qid = query($sql);
}

/**
 * maj_attribut()
 *
 * @param integer $id
 * @param array $frm
 * @return
 */
function maj_attribut($id, $frm)
{
	$prix = get_float_from_user_input($frm['prix']);
	$prix_revendeur = get_float_from_user_input($frm['prix_revendeur']);
	$sql = "UPDATE peel_attributs
		SET id_nom_attribut = '" . intval($id) . "'
		 ,  image = '" . nohtml_real_escape_string($frm['image']) . "'
		 ,  prix = '" . nohtml_real_escape_string($prix) . "'
		 ,  prix_revendeur = '" . nohtml_real_escape_string($prix_revendeur) . "'
		 ,  mandatory = '" . intval($frm['mandatory']) . "'
		 ,  site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		 ,  position = '" . intval($frm['position']) . "'";
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", descriptif_" . $lng . " = '" . nohtml_real_escape_string($_POST['descriptif_' . $lng]) . "'";
	}
	$sql .= " WHERE id = '" . intval($_POST['id']) . "' AND " . get_filter_site_cond('attributs', null, true)."";
	$qid = query($sql);
}

/**
 * Affiche un formulaire de attributs vide
 *
 * @param integer $id
 * @return
 */
function affiche_formulaire_liste_attribut($id, &$frm)
{
	/* Valeurs par défaut */
	$frm = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$frm['nom_' . $lng] = "";
	}
	$frm["nouveau_mode"] = "insere";
	$frm["image"] = "";
	$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_ADD'];
	/* Affiche la liste des attributs, en présélectionnant la attributs choisie. */
	affiche_liste_attribut($frm);
}

/**
 * affiche_liste_attribut()
 *
 * @return
 */
function affiche_liste_attribut($frm)
{
	affiche_choix_nom_attribut();
	$sql = "SELECT id, nom_" . $_SESSION['session_langue'] . " AS nom
		FROM peel_nom_attributs
		WHERE id='" . intval(vn($_GET['attid'])) . "' AND texte_libre = 0 AND upload = 0 AND " . get_filter_site_cond('nom_attributs', null, true) . "";
	$q = query($sql);
	if ($nom_att = fetch_object($q)) {
		if (trim($nom_att->nom) == '') {
			$nom_att->nom = '[' . $nom_att->id . ']';
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/attributsAdmin_liste.tpl');
		$tpl->assign('nom', $nom_att->nom);
		$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
		$tpl->assign('add_href', get_current_url(false) . '?mode=ajout&attid=' . $_GET['attid']);
		$sql = "SELECT *
			FROM peel_attributs
			WHERE id_nom_attribut = '" . intval($_GET['attid']) . "' AND " . get_filter_site_cond('attributs', null, true)."
			ORDER BY descriptif_" . $_SESSION['session_langue'];
		$res = query($sql);
		$nr = num_rows($res);
		$tpl->assign('num_results', $nr);
		$tpl_results = array();
		if ($nr != 0) {
			$i = 0;
			while ($DescAtt = fetch_assoc($res)) {
				$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
					'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $DescAtt['id'] . '&attid=' . $_GET['attid'],
					'drop_src' => $GLOBALS['administrer_url'] . '/images/b_drop.png',
					'edit_href' => get_current_url(false) . '?mode=modif&id=' . $DescAtt['id'] . '&attid=' . $_GET['attid'],
					'descriptif' => $DescAtt['descriptif_' . $_SESSION['session_langue']],
					'prix' => fprix($DescAtt['prix'], true, $GLOBALS['site_parameters']['code'], false),
					'img_src' => (!empty($DescAtt['image']) ? thumbs($DescAtt['image'], 100, 100, 'fit', null, null, true, true) : ''),
					'site_name' => get_site_name($DescAtt['site_id'])
					);
				$i++;
			}
		}
		$tpl->assign('results', $tpl_results);
		$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
		$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
		$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTE_OPTIONS_LIST', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTE_OPTIONS_LIST']);
		$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTE_OPTIONS_LIST_EXPLAIN', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTE_OPTIONS_LIST_EXPLAIN']);
		$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_CREATE_OPTION', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_CREATE_OPTION']);
		$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
		$tpl->assign('STR_LIST', $GLOBALS['STR_LIST']);
		$tpl->assign('STR_PRICE', $GLOBALS['STR_PRICE']);
		$tpl->assign('STR_PHOTO', $GLOBALS['STR_PHOTO']);
		$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_NO_OPTION_DEFINED', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_NO_OPTION_DEFINED']);
		$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
		$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
		$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
		echo $tpl->fetch();
	}
}

/**
 * affiche_formulaire_attribut()
 *
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_attribut(&$frm, &$form_error_object)
{
	$res = query("SELECT nom_" . $_SESSION['session_langue'] . " AS nom
		FROM peel_nom_attributs
		WHERE id = '" . intval($_GET['attid']) . "' AND " . get_filter_site_cond('nom_attributs', null, true) . "");
	if ($nom_att = fetch_object($res)) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/attributsAdmin_formulaire.tpl');
		$tpl->assign('action', get_current_url(false) . '?attid=' . $_GET['attid']);
		$tpl->assign('mode', $frm["nouveau_mode"]);
		$tpl->assign('mandatory', vn($frm["mandatory"]));
		$tpl->assign('id', vn($_GET['id']));
		$tpl->assign('nom', $nom_att->nom);
		$tpl_langs = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$tpl_langs[] = array('code' => $lng,
				'descriptif' => vn($frm['descriptif_' . $lng]),
				'error' => $form_error_object->text('descriptif_' . $lng)
				);
		}
		$tpl->assign('langs', $tpl_langs);

		if (!empty($frm["image"])) {
			$tpl->assign('image', get_uploaded_file_infos('image', $frm['image'], get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=image&attid=' . vb($_GET['attid'])));
		}	
		$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
		$tpl->assign('prix', fprix(vn($frm['prix']), false, $GLOBALS['site_parameters']['code'], false));
		$tpl->assign('prix_revendeur', fprix(vn($frm['prix_revendeur']), false, $GLOBALS['site_parameters']['code'], false));
		$tpl->assign('symbole', $GLOBALS['site_parameters']['symbole']);
		$tpl->assign('position', intval($frm['position']));
		$tpl->assign('titre_soumet', $frm["titre_soumet"]);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
		$tpl->assign('STR_HT', $GLOBALS['STR_HT']);
		$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
		$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_CREATE_OPTION', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_CREATE_OPTION']);
		$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
		$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
		$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
		$tpl->assign('STR_IMAGE', $GLOBALS['STR_IMAGE']);
		$tpl->assign('STR_ADMIN_FILE_NAME', $GLOBALS['STR_ADMIN_FILE_NAME']);
		$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_OVERCOST', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_OVERCOST']);
		$tpl->assign('STR_ADMIN_DELETE_IMAGE', $GLOBALS['STR_ADMIN_DELETE_IMAGE']);
		$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_OVERCOST_RESELLER', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_OVERCOST_RESELLER']);
		$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_OVERCOST', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_OVERCOST']);
		$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
		$tpl->assign('STR_ADMIN_MANDATORY', $GLOBALS['STR_ADMIN_MANDATORY']);
		$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
		$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
		$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
		echo $tpl->fetch();
	}
}

/**
 * Supprime le produit spécifié par $id. Il faut supprimer le produit
 * puis les entrées correspondantes de la table produits_attributs
 *
 * @param integer $id
 * @param mixed $file
 * @return
 */
function supprime_attribut_image($id, $file)
{
	/* Charge les infos du produit. */

	switch ($file) {
		case "image" :
			$sql = "SELECT image 
				FROM peel_attributs 
				WHERE id = '" . intval($id) . "'";
			$res = query($sql);
			$file = fetch_assoc($res);
			query("UPDATE peel_attributs 
				SET image = '' 
				WHERE id = '" . intval($id) . "'");
			break;
	}
	delete_uploaded_file_and_thumbs($file['image']);
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_RUBRIQUES_MSG_DELETED_OK'], $file['image'])))->fetch();
}

/**
 * affiche_choix_nom_attribut()
 *
 * @return
 */
function affiche_choix_nom_attribut()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/attributsAdmin_choix_nom.tpl');
	$sql = "SELECT id, nom_" . $_SESSION['session_langue'] . "
		FROM peel_nom_attributs
		WHERE texte_libre = 0 AND upload = 0 AND " . get_filter_site_cond('nom_attributs', null, true) . "
		ORDER BY nom_" . $_SESSION['session_langue'];
	$res = query($sql);
	$tpl_options = array();
	while ($att = fetch_assoc($res)) {
		if (trim($att['nom_' . $_SESSION['session_langue']]) == '') {
			$att['nom_' . $_SESSION['session_langue']] = '[' . $att['id'] . ']';
		}
		$tpl_options[] = array('value' => intval($att['id']),
			'issel' => $att['id'] == vb($_GET['attid']),
			'name' => $att['nom_' . $_SESSION['session_langue']]
			);
	}
	$tpl->assign('options', $tpl_options);
	$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_SEARCH_TITLE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_SEARCH_TITLE']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_SEARCH_CHOOSE_ATTRIBUTE', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_SEARCH_CHOOSE_ATTRIBUTE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_SEARCH_UPDATE_LIST', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_SEARCH_UPDATE_LIST']);
	echo $tpl->fetch();
}

/**
 * affiche_liste_attributs_by_id()
 *
 * @param integer $id
 * @return
 */
function affiche_liste_attributs_by_id($id)
{
	$product_object = new Product($id, null, false, null, true, !check_if_module_active('micro_entreprise'));

	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/attributsAdmin_liste_by_id.tpl');
	$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);
	$tpl->assign('product_id', $id);
	$tpl->assign('product_name', $product_object->name);
	$tpl->assign('product_revenir_href', $GLOBALS['administrer_url'] . '/produits.php?mode=modif&id=' . $id);
	$tpl->assign('product_liste_revenir_href', $GLOBALS['administrer_url'] . '/produits.php');
	$tpl->assign('action', get_current_url(false) . '?id=' . $id);

	$all_attributs_array = get_possible_attributs(null, 'rough', false, false);
	$product_attributs_array = $product_object->get_possible_attributs('rough', false, 0, false, false, false, false, false, false);
	$tpl_results = array();

	if (!empty($all_attributs_array)) {
		// On affiche la liste des attributs
		$i = 0;
		foreach ($all_attributs_array as $this_nom_attribut_id => $this_attribut_values_array) {
			$tpl_sub_res = array();
			foreach ($this_attribut_values_array as $this_attribut_id => $this_attribut_infos) {
				if(check_if_module_active('reseller') && is_reseller()) {
					$montant = $this_attribut_infos['prix_revendeur'];
				} else {
					$montant = $this_attribut_infos['prix'];
				}
				if (display_prices_with_taxes_in_admin()) {
					$montant_displayed = $montant;
				} else {
					$montant_displayed = $montant / (1 + $product_object->tva / 100);
				}
				if (!empty($this_attribut_id) || $this_attribut_id === 0) {
					if(trim(StringMb::strip_tags($this_attribut_infos['descriptif']))=='') {
						$this_attribut_infos['descriptif'] = '['.$this_attribut_id.'] ';
					}
					$tpl_sub_res[] = array('value' => intval($this_attribut_id),
						'issel' => !empty($product_attributs_array[$this_nom_attribut_id]) && !empty($product_attributs_array[$this_nom_attribut_id][$this_attribut_id]),
						'desc' => StringMb::strip_tags($this_attribut_infos['descriptif']),
						'prix' => fprix($montant_displayed, true, $GLOBALS['site_parameters']['code'], false)
						);
				}
			}
			// NB : $this_attribut_infos est encore défini après la boucle foreach ci-dessus, donc on peut l'utiliser
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'nom' => $this_attribut_infos['nom'],
				'id' => $this_nom_attribut_id,
				'texte_libre' => $this_attribut_infos['texte_libre'],
				'upload' => $this_attribut_infos['upload'],
				'issel' => !empty($product_attributs_array[$this_nom_attribut_id]),
				'sub_res' => $tpl_sub_res
				);
			$i++;
		}
	}
	$tpl->assign('results', $tpl_results);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_TITLE', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_TITLE"]);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_BACK_TO_PRODUCT', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_BACK_TO_PRODUCT"]);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_BACK_TO_PRODUCTS_LIST', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_BACK_TO_PRODUCTS_LIST"]);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_EXPLAIN_SELECT', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_EXPLAIN_SELECT"]);
	$tpl->assign('STR_ADMIN_ATTRIBUTE', $GLOBALS["STR_ADMIN_ATTRIBUTE"]);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTIONS_ASSOCIATED', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTIONS_ASSOCIATED"]);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_OVERCOST', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_OVERCOST"] );
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_ASSOCIATE_ATTRIBUTE', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_ASSOCIATE_ATTRIBUTE"]);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_ADD_UPLOAD', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_ADD_UPLOAD"]);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_FREE_TEXT', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_FREE_TEXT"]);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_NO_OPTION', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_NO_OPTION"]);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_LIST_MANAGE_LINK', $GLOBALS["STR_MODULE_ATTRIBUTS_ADMIN_LIST_MANAGE_LINK"]);
	$tpl->assign('ttc_ht', (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));
	echo $tpl->fetch();
}

/**
 * Retourne un tableau contenant la liste des noms des attributs disponibles sur le site.
 *
 * @param string $lang
 * @return
 */
function get_attributs_names($lang)
{
	$output_array = array();
	$q = query('SELECT descriptif_' . word_real_escape_string($lang) . ' as nom
		FROM peel_attributs
		WHERE ' . get_filter_site_cond('attributs', null, true));
	while ($result = fetch_assoc($q)) {
		$output_array[] = $result['nom'];
	}
	return $output_array;
}


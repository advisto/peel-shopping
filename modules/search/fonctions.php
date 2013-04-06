<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 36232 2013-04-05 13:16:01Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * get_advanced_search_script()
 *
 * @return
 */
function get_advanced_search_script() {
	$output = '
	<script><!--//--><![CDATA[//><!--
		function gotobrand(ident){
			document.location="' . $GLOBALS['wwwroot'] . '/achat/marque.php?id="+ident;
		}
		function gotocategorie(ident){
			document.location="' . $GLOBALS['wwwroot'] . '/achat/?catid="+ident;
		}
	//--><!]]></script>';

	return $output;
}

/**
 * affiche_select_marque()
 *
 * param boolean $return_mode
 * @return
 */
function affiche_select_marque($return_mode = false) {
	$output = '';
	$query = query("SELECT id, nom_" . $_SESSION['session_langue'] . " AS marque
		FROM peel_marques
		WHERE etat=1
		ORDER BY position ASC, nom_" . $_SESSION['session_langue'] . " ASC");
	if (num_rows($query) > 0) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/search_select_marque.tpl');
		$tpl->assign('STR_SEARCH_BRAND', $GLOBALS['STR_SEARCH_BRAND']);
		$tpl_options = array();
		while ($brand = fetch_assoc($query)) {
			$tpl_id = null;
			if (is_module_url_rewriting_active()) {
				$tpl_id = rewriting_urlencode($brand['marque']);
			}
			$tpl_options[] = array(
				'id' => $tpl_id,
				'value' => intval($brand['id']),
				'name' => String::str_shorten($brand['marque'], 50)
			);
		}
		$tpl->assign('options', $tpl_options);
		$output = $tpl->fetch();
	}
	if ($return_mode) {
		return $output;
	} else {
		echo $output;
	}
}

/*
 * Affichage des champs select selon le type passé en paramètres (critère par défaut dans peel)
 *
 * @param string $categorie
 * @param array $attribute
 * @return
 */

function display_select_attribute($categorie, $attribute) {
	$output = '';
	// si la requete necessite une autre table pour le controle de l'utilisation de l'attribut
	if (!empty($attribute['join'])) {
		$sql = 'SELECT DISTINCT a.`id`, a.`nom_' . $_SESSION['session_langue'] . '` AS `nom`
			FROM `peel_' . word_real_escape_string($attribute['table']) . '` a
			INNER JOIN  `peel_' . word_real_escape_string($attribute['join']) . '` b ON (a.`id` = b.`' . word_real_escape_string($attribute['join_id']) . '`) ';
	} else {
		$sql = 'SELECT DISTINCT `' . word_real_escape_string($attribute['join_id']) . '` AS `id`, `' . word_real_escape_string($attribute['join_id']) . '` AS `nom`
			FROM `peel_' . word_real_escape_string($attribute['table']) . '`';
	}
	$result = query($sql);
	$option = '';
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/search_select_attribute.tpl');
	$tpl->assign('categorie', $categorie);
	$tpl->assign('label', $attribute['label']);
	$tpl_options = array();
	while ($attrib = fetch_assoc($result)) {
		$tpl_options[] = array(
			'value' => $attrib['id'],
			'issel' => vb($_GET[$categorie]) == $attrib['id'],
			'name' => $attrib['nom']
		);
	}
	$tpl->assign('options', $tpl_options);
	$output = $tpl->fetch();
	return $output;
}

/*
 * Affichage des attributs crées via l'administration du site
 * A FAIRE : Cette fonction est à fusionner avec display_form_part du modules attributs
 *
 * @param array $selected_attributes
 * @param string $technical_code identifiant unique d'un attribut
 * @param boolean $show_all
 * @return
 */
function display_custom_attribute($selected_attributes=null, $technical_code = null, $show_all = false) {
	$output = '';
	if(!empty($technical_code)) {
		$sql_technical_code_condition = 'a.technical_code ="' . real_escape_string($technical_code) . '"';
	} else {
		// On ne prend que les choix multiples
		$sql_technical_code_condition = 'a.`texte_libre`=0 ';
	}
	$sql = 'SELECT DISTINCT o.`id`, o.`id_nom_attribut`, a.`nom_' . $_SESSION['session_langue'] . '` AS `attribut`, o.`descriptif_' . $_SESSION['session_langue'] . '` AS `nom`
		FROM `peel_nom_attributs`  a
		LEFT JOIN `peel_attributs` o ON a.`id` = o.`id_nom_attribut`
		'.(!$show_all? 'INNER JOIN `peel_produits_attributs` pa ON o.`id` = pa.`attribut_id`':'').'
		WHERE '.$sql_technical_code_condition.' AND a.`etat`=1 AND a.technical_code NOT IN ("duration", "categorie_number")';
	$result = query($sql);
	while ($this_attribute = fetch_assoc($result)) {
		$tpl_attrs[$this_attribute['id_nom_attribut']]['name'] = $this_attribute['attribut'];
		if(!empty($this_attribute['id'])) {
			$tpl_attrs[$this_attribute['id_nom_attribut']]['options'][] = array(
				'value' => intval($this_attribute['id']),
				'issel'	=> (!empty($selected_attributes) && is_array($selected_attributes) && vb($selected_attributes[$this_attribute['id_nom_attribut']]) == $this_attribute['id']),
				'name' => $this_attribute['nom']
			);
		}
	}
	if(!empty($tpl_attrs)) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/search_custom_attribute.tpl');
		$tpl->assign('select_attrib_txt', $GLOBALS['STR_MODULE_SEARCH_SELECT_ATTRIB']);
		$tpl->assign('attributes', $tpl_attrs);
		$output .= $tpl->fetch();
	}
	return $output;
}

?>
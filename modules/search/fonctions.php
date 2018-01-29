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
 * Affiche les résultats de recherche
 *
 * @param array $params
 * @return
 */
function search_hook_search_complementary($params) {
	if(empty($params['terms']) || vn($params['page'])>1) {
		return null;
	}
	// Recherche dans les conditions générales
	$i = 0;
	$results = array();
	$results_array = array();
	$fields[] = 'titre_' . $_SESSION['session_langue'];
	$fields[] = 'texte_' . $_SESSION['session_langue'];
	$sql = "SELECT *
		FROM peel_cgv
		WHERE " . build_terms_clause($params['terms'], $fields, $params['match']) . " AND " . get_filter_site_cond('cgv') . "
		ORDER BY date_maj DESC, id DESC
		LIMIT ". vn($GLOBALS['site_parameters']['cgv_search_results_max'], 20);
	$query = query($sql);
	while ($result = fetch_assoc($query)) {
		$url = get_url('/cgv.php');
		// on supprime le HTML du contenu
		$nom = StringMb::strip_tags(StringMb::html_entity_decode_if_needed($result['titre_' . $_SESSION['session_langue']]));
		$description = StringMb::strip_tags(StringMb::html_entity_decode_if_needed($result['texte_' . $_SESSION['session_langue']]));
		// on coupe le texte si trop long
		$nom = StringMb::str_shorten($nom, $params['taille_texte_affiche'], '', '...', $params['taille_texte_affiche']-20);
		$description = StringMb::str_shorten($description, $params['taille_texte_affiche'], '', '...', $params['taille_texte_affiche']-20);
		// on fait une recherche sur le texte sans accent avec les mots de l'utilisateur,
		// si qqchose est trouvé, highlight_found_text l'ajoute dans le tableau  $GLOBALS['found_words_array'][]
		$nom = highlight_found_text($nom, $params['terms'], $GLOBALS['found_words_array']);
		$description = highlight_found_text($description, $params['terms'], $GLOBALS['found_words_array']);
		// affichage
		$i++;
		$results_array[] = array('num' => $i,
			'id' => $result['id'],
			'name' => $nom,
			'href' => $url,
			'description' => $description
			);
	}
	if(!empty($results_array)) {
		$results['cgv'] = array('results' => $results_array, 'title' => $GLOBALS['STR_CGV'], 'no_result' => null);
	}
	return $results;
}

/**
 * Ajout de données pour le formulaire du moteur de recherche
 *
 * @param array $params
 * @return On renvoie un tableau sous la forme [variable smarty] => [contenu]
 */
function search_hook_search_form_template_data(&$params) {
	// Affichage des champs de recherche liés au module de recherche avancée
	$results = array();

	if (!check_if_module_active('annonces') || (check_if_module_active('annonces') && !empty($GLOBALS['site_parameters']['search_in_product_and_ads']))) {
		// tableau regroupant les caractéristiques des attributs fixes dans peel
		$search_attribute_tab = array('marque' => array('table' => 'marques', 'join' => 'produits', 'join_id' => 'id_marque', 'label' => $GLOBALS['STR_BRAND_LB']),
			'couleur' => array('table' => 'couleurs', 'join' => 'produits_couleurs', 'join_id' => 'couleur_id', 'label' => $GLOBALS['STR_COLOR_LB']),
			'taille' => array('table' => 'tailles', 'join' => 'produits_tailles', 'join_id' => 'taille_id', 'label' => $GLOBALS['STR_TALL_LB'])
		);

		// on construit la liste des catégories
		if(empty($GLOBALS['select_categorie'])) {
			// Si plusieurs formulaires de recherche sont présents sur la même page, on garde en mémoire $GLOBALS['select_categorie']
			$GLOBALS['parent_categorie'] = vn($params['frm']["categorie"]); // catégorie sélectionnée
			$GLOBALS['select_categorie'] = get_categories_output(null, 'categories', vn($_GET["categorie"]), vb($GLOBALS['site_parameters']['search_form_category_display_mode'], 'option'), '&nbsp;&nbsp;', null, null, false, vb($GLOBALS['site_parameters']['search_form_category_text_length_max'], 40));
		}
		$results['select_categorie'] = $GLOBALS['select_categorie'];
		$results['STR_CAT_LB'] = $GLOBALS['STR_CAT_LB'];

		$tpl_f_select_attributes = array();
		// affichage des attributs fixes
		foreach ($search_attribute_tab as $index => $attribute) {
			if (!in_array($index, vb($GLOBALS['site_parameters']['search_attribute_tab_displayed_in_search_form_disabled'], array()))) {
				$tpl_f_select_attributes[] = display_select_attribute($index, $attribute);
			}
		}
		$results['select_attributes'] = $tpl_f_select_attributes;
		// affichage des attributs variables
		if (!empty($GLOBALS['site_parameters']['custom_attribut_displayed_in_search_form_'.$params['display']])) {
			$technical_code = $GLOBALS['site_parameters']['custom_attribut_displayed_in_search_form_'.$params['display']];
		} elseif (!empty($GLOBALS['site_parameters']['custom_attribut_displayed_in_search_form'])) {
			$technical_code = $GLOBALS['site_parameters']['custom_attribut_displayed_in_search_form'];
		}
		if (empty($GLOBALS['site_parameters']['custom_attribut_displayed_in_search_form_disabled'])) {
			$results['custom_attribute'] = display_custom_attribute(vb($params['frm']['custom_attribut']), vb($technical_code), true);
		} else {
			$results['custom_attribute'] = null;
		}
	}
	if(check_if_module_active('annonces') && $params['display'] != 'module_products') {
		$tpl_f_cat_ann_opts = array();
		$ad_categories = get_ad_categories();
		foreach ($ad_categories as $this_category_id => $this_category_name) {
			$tpl_f_cat_ann_opts[] = array('value' => $this_category_id,
				'issel' => (!empty($_GET['cat_select']) && ($_GET['cat_select'] == $this_category_id || (is_array($_GET['cat_select']) && in_array($this_category_id, $_GET['cat_select'])))),
				'name' => $this_category_name
				);
		}
		// Possibilités : option ou checkbox
		$results['search_form_category_display_mode'] = vb($GLOBALS['site_parameters']['search_form_category_display_mode'], 'option');
		$results['cat_ann_opts'] = $tpl_f_cat_ann_opts;
		$results['display_city_zip'] = empty($GLOBALS['site_parameters']['disable_city_zip_input_on_search_form']);
		// Définit le type d'annonce détail,gros, etc.. Cependant il y a deux filtres sur Destockplus, d'où le test sur les deux get
		if (!empty($params['frm']['cat_statut_detail'])) {
			$type_detail = $params['frm']['cat_statut_detail'];
		} elseif (!empty($params['frm']['cat_detail'])) {
			$type_detail = $params['frm']['cat_detail'];
		}
		if (!empty($params['frm']['cat_statut_detail'])) {
			$type_statut = $params['frm']['cat_statut_detail'];
		} elseif (!empty($params['frm']['cat_statut'])) {
			$type_statut = $params['frm']['cat_statut'];
		}
		$results['cat_detail'] = vb($type_detail);
		$results['cat_statut'] = vb($type_statut);
		if (count($GLOBALS['lang_codes'])>1) {
			$results['ad_lang_select'] = get_lang_ads_choose(false);
		}
		$results['city_zip'] = vb($params['frm']['city_zip']);
		$country_allowed_ids = get_ads_country_used();
		if (empty($GLOBALS['site_parameters']['disable_country_input_on_search_form'])) {
			$results['country'] = get_country_select_options(vb($params['frm']['country']), null, 'name', false, null, false, null, $country_allowed_ids);
		}
		if (empty($GLOBALS['site_parameters']['disable_continent_input_on_search_form'])) {
			$tpl_continent_inps = array();
			unset($sql);
			if(!empty($GLOBALS['site_parameters']['ads_filter_countries_in_search'])) {
				if(!empty($country_allowed_ids)) {
					$sql = "SELECT c.id, c.name_" . $_SESSION['session_langue'] . " AS name
						FROM peel_continents c
						INNER JOIN peel_pays p ON p.continent_id=c.id AND p.id IN ('" . implode("','", real_escape_string($country_allowed_ids)) . "') AND " . get_filter_site_cond('pays', 'p') . "
						WHERE " . get_filter_site_cond('continents', 'c') . "
						GROUP BY c.id
						ORDER BY c.name_".$_SESSION['session_langue'] . "";
				}
			} else {
				$sql = "SELECT id, name_" . $_SESSION['session_langue'] . " AS name
					FROM peel_continents
					WHERE " . get_filter_site_cond('continents') . "
					ORDER BY name_".$_SESSION['session_langue'];
			}
			if(!empty($sql)) {
				$query_continent = query($sql);
				while ($continent = fetch_assoc($query_continent)) {
					$tpl_continent_inps[] = array('value' => $continent['id'],
						'issel' => !empty($params['frm']['continent']) && is_array($params['frm']['continent']) && in_array($continent['id'], $params['frm']['continent']),
						'name' => $continent['name']
						);
				}
			}
			$results['continent_inputs'] = $tpl_continent_inps;
		}
		if (!empty($GLOBALS['site_parameters']['ads_search_date_end_past'])) {
			$results['date_end_future'] = (!empty($params['frm']['date_end']) && in_array('future', $params['frm']['date_end']));
			$results['date_end_past'] = (!empty($params['frm']['date_end']) && in_array('past', $params['frm']['date_end']));
			$results['STR_MODULE_ANNONCES_DATE_END_FUTURE'] = $GLOBALS['STR_MODULE_ANNONCES_DATE_END_FUTURE'];
			$results['STR_MODULE_ANNONCES_DATE_END_PAST'] = $GLOBALS['STR_MODULE_ANNONCES_DATE_END_PAST'];
			$results['date'] = vb($params['frm']['date']);
			$results['STR_DATE'] = $GLOBALS['STR_DATE'];
		}
		$results['user_verified_status_disable'] = !empty($GLOBALS['site_parameters']['user_verified_status_disable']);
		$results['ads_contain_lot_sizes'] = $GLOBALS['site_parameters']['ads_contain_lot_sizes'];
		$results['STR_TYPE'] = $GLOBALS['STR_TYPE'];
		$results['STR_MODULE_ANNONCES_AD_CATEGORY'] = $GLOBALS['STR_MODULE_ANNONCES_AD_CATEGORY'];
		$results['STR_MODULE_ANNONCES_OFFER_GROS'] = $GLOBALS['STR_MODULE_ANNONCES_OFFER_GROS'];
		$results['STR_MODULE_ANNONCES_OFFER_DEMIGROS'] = $GLOBALS['STR_MODULE_ANNONCES_OFFER_DEMIGROS'];
		$results['STR_MODULE_ANNONCES_OFFER_DETAIL'] = $GLOBALS['STR_MODULE_ANNONCES_OFFER_DETAIL'];
		$results['STR_STATUS'] = $GLOBALS['STR_STATUS'];
		$results['STR_MODULE_ANNONCES_ALT_VERIFIED_ADS'] = $GLOBALS['STR_MODULE_ANNONCES_ALT_VERIFIED_ADS'];
		$results['STR_MODULE_ANNONCES_NOT_VERIFIED_ADS'] = $GLOBALS['STR_MODULE_ANNONCES_NOT_VERIFIED_ADS'];
		$results['STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD'] = $GLOBALS['STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD'];		
	}
	return $results;
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
	if(empty($GLOBALS['site_parameters']['affiche_select_marque_disable'])) {
		$query = query("SELECT id, nom_" . $_SESSION['session_langue'] . " AS marque
			FROM peel_marques
			WHERE etat=1 AND " . get_filter_site_cond('marques') . "
			ORDER BY position ASC, nom_" . $_SESSION['session_langue'] . " ASC");
		if (num_rows($query) > 0) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('modules/search_select_marque.tpl');
			$tpl->assign('STR_SEARCH_BRAND', $GLOBALS['STR_SEARCH_BRAND']);
			$tpl_options = array();
			while ($brand = fetch_assoc($query)) {
				$tpl_id = null;
				if (check_if_module_active('url_rewriting')) {
					$tpl_id = rewriting_urlencode($brand['marque']);
				}
				$tpl_options[] = array(
					'id' => $tpl_id,
					'value' => intval($brand['id']),
					'name' => StringMb::str_shorten($brand['marque'], 50)
				);
			}
			$tpl->assign('options', $tpl_options);
			$output .= $tpl->fetch();
		}
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
	// si la requête nécessite une autre table pour le contrôle de l'utilisation de l'attribut
	if (!empty($attribute['join'])) {
		$sql = 'SELECT DISTINCT a.`id`, a.`nom_' . $_SESSION['session_langue'] . '` AS `nom`
			FROM `peel_' . word_real_escape_string($attribute['table']) . '` a
			INNER JOIN  `peel_' . word_real_escape_string($attribute['join']) . '` b ON (a.`id` = b.`' . word_real_escape_string($attribute['join_id']) . '`) ';
	} else {
		$sql = 'SELECT DISTINCT `' . word_real_escape_string($attribute['join_id']) . '` AS `id`, `' . word_real_escape_string($attribute['join_id']) . '` AS `nom`
			FROM `peel_' . word_real_escape_string($attribute['table']) . '`';
	}
	$query = query($sql);
	$option = '';
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/search_select_attribute.tpl');
	$tpl->assign('categorie', $categorie);
	$tpl->assign('label', $attribute['label']);
	$tpl_options = array();
	while ($attrib = fetch_assoc($query)) {
		$tpl_options[] = array(
			'value' => $attrib['id'],
			'issel' => vb($_GET[$categorie]) == $attrib['id'],
			'name' => $attrib['nom']
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	return $output;
}

/*
 * Affichage des attributs crées via l'administration du site
 * A FAIRE : L'affichage des champs est à séparer et à fusionner avec affiche_attributs_form_part
 *
 * @param array $selected_attributes
 * @param string $technical_code identifiant unique d'un attribut
 * @param boolean $show_all
 * @return
 */
function display_custom_attribute($selected_attributes = null, $technical_code = null, $show_all = false) {
	$output = '';
	if(!empty($technical_code)) {
		if (is_array($technical_code)) {
			$sql_technical_code_condition = 'a.technical_code IN ("' . implode('","', real_escape_string($technical_code)) . '")';
		} else {
			$sql_technical_code_condition = 'a.technical_code ="' . real_escape_string($technical_code) . '"';
		}
	} else {
		// On ne prend que les choix multiples
		$sql_technical_code_condition = 'a.`texte_libre`=0 AND upload=0';
	}
	$sql = 'SELECT DISTINCT o.`id`, a.`id` AS `id_nom_attribut`, a.`nom_' . $_SESSION['session_langue'] . '` AS `attribut`, o.`descriptif_' . $_SESSION['session_langue'] . '` AS `nom`
		FROM `peel_nom_attributs` a
		LEFT JOIN `peel_attributs` o ON a.`id` = o.`id_nom_attribut` AND ' . get_filter_site_cond('attributs', 'o') . ' 
		'.(!$show_all? 'INNER JOIN `peel_produits_attributs` pa ON o.`id` = pa.`attribut_id`':'').'
		WHERE '.$sql_technical_code_condition.' AND a.`etat`=1 AND a.technical_code NOT IN ("duration", "categorie_number")  AND ' . get_filter_site_cond('nom_attributs', 'a');
	$query = query($sql);
	while ($this_attribute = fetch_assoc($query)) {
		$tpl_attrs[$this_attribute['id_nom_attribut']]['name'] = $this_attribute['attribut'];
		$tpl_attrs[$this_attribute['id_nom_attribut']]['value'] = vb($selected_attributes[$this_attribute['id_nom_attribut']]);
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
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('select_attrib_txt', $GLOBALS['STR_MODULE_SEARCH_SELECT_ATTRIB']);
		$tpl->assign('attributes', $tpl_attrs);
		$output .= $tpl->fetch();
	}
	return $output;
}

/**
 * Comparer l'ordre de deux arguments pour l'ordre des thématiques de résultats
 *
 * @param string $arg1
 * @param string $arg2
 * @return int Positif si $field1 est avant $field2
 * @access public
 */
function resultsTypeCompareArgsOrder($arg1, $arg2)
{
	if(!empty($GLOBALS['site_parameters']['search_complementary_found_sort_array'])) {
		foreach($GLOBALS['site_parameters']['search_complementary_found_sort_array'] as $this_key => $this_value) {
			if(is_numeric($this_key)) {
				$order[$this_key] = $this_value;
			} else {
				$order[$this_value] = $this_key;
			}
		}
	} elseif(!empty($GLOBALS['site_parameters']['search_complementary_found_sort_by_count'])) {
		if(empty($GLOBALS['search_complementary_results_array'][$arg1])) {
			$order[$arg1] = 0;
		} else {
			$order[$arg1] = -count($GLOBALS['search_complementary_results_array'][$arg1]['results']);
		}
		if(empty($GLOBALS['search_complementary_results_array'][$arg2])) {
			$order[$arg2] = 0;
		} else {
			$order[$arg2] = -count($GLOBALS['search_complementary_results_array'][$arg2]['results']);
		}
	} else {
		return 0;
	}
	// echo $arg1,$arg2;
	if (!isset($order[$arg1]) || !isset($order[$arg2]) || $order[$arg1] > $order[$arg2]) {
		return 1;
	} else {
		return -1;
	}
}

<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: search.php 47592 2015-10-30 16:40:22Z sdelaporte $
if (!empty($_GET['type']) && $_GET['type'] == 'error404') {
	if (substr($_SERVER['REQUEST_URI'], 0, 1) == '/' && substr($_SERVER['REQUEST_URI'], 3, 1) == '/' && substr($_SERVER['REQUEST_URI'], 1, 2) != 'js') {
		// On a une langue dans l'URL en tant que premier répertoire
		// On la récupère ici, car le .htaccess n'a pas pu la récupérer pour gérer la 404
		// NB : en revanche, pour les sous-domaines différents pour une langue donnée, ça se fait déjà en PHP dans le process normal, donc pas besoin de gérer ici
		$_GET['langue'] = substr($_SERVER['REQUEST_URI'], 1, 2);
	}
	define('IN_404_ERROR_PAGE', true);
	$GLOBALS['page_name'] = 'error404';
	if(in_array(substr($_SERVER['REQUEST_URI'],-4), array('.css', '.png', '.jpg', '.gif', '.txt', '.xml'))) {
		// Fichier CSS demandé : aucun contenu envoyé
		// En effet, si le dossier n'existe pas, on peut arriver ici malgré le .htaccess <FilesMatch "\.(gif|jpe?g|png|ico|xml|gz|zip|txt|js|css)$">	ErrorDocument 404 default </FilesMatch>
		die();
	}
}
if (defined('PEEL_PREFETCH')) {
	call_module_hook('configuration_end', array());
} else {
	include("configuration.inc.php");
}

if (!empty($_GET['type']) && $_GET['type'] == 'unset_quick_add_product_from_search_page') {
	unset($_SESSION['session_search_product_list']);
}
if (!empty($_GET['type']) && $_GET['type'] == 'quick_add_product_from_search_page' && !empty($GLOBALS['site_parameters']['quick_add_product_from_search_page'])) {
	necessite_identification();
	$quick_add_product_from_search_page = true;
	if (!empty($_GET['prodid']) && !empty($_GET['quantite'])) {
		$_SESSION['session_search_product_list'][$_GET['prodid']] = $_GET['quantite'];
	}
}
if (!empty($_GET['type']) && $_GET['type'] == 'error404') {
	// On va présenter des résultats par défaut => on met comme URL de référence search.php pour le multipage, sinon cela créerait des liens vers des pages inexistantes
	$_SERVER['REQUEST_URI'] = $GLOBALS['apparent_folder'] . 'search.php';
}
if (check_if_module_active('annonces') && String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/' . $GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'] . '-' . String::rawurlencode(vn($_GET['page'])) . '-' . vb($_GET['search']) . '.html') || String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/produits/' . vb($_GET['search']) . '.html') || String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/produits/' . vb($_GET['search']) . '-' . vn($_GET['page']) . '.html')) {
	// Pour éviter des problèmes avec les + dans l'URL, on a dans le .htaccess une règle avec l'option B qui permet de garder encodé search et est à décoder via String::rawurldecode
	$_GET['search'] = String::rawurldecode(vb($_GET['search']));
	if (strpos($_GET['search'], '/') !== false) {
		// Depuis octobre 2012, plus de possibilité d'avoir des / dans les URL de recherches qui sont réécrites et où search provient du coeur de l'URL
		// Ca permet d'éviter un bug avec apache et les %2F en dehors du GET
		redirect_and_die('/', true);
	}
}
foreach(array('/produits/', '/'.$GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'].'-', '/supplier-research-', '/recherche-fournisseur-', '/busqueda-proveedor-') as $this_url_rewriting_main_expression) {
	if(!empty($_GET['page']) && String::strpos(get_current_url(true, true), 'page=') !== false && check_if_module_active('annonces') && String::strpos(get_current_url(false, true), $this_url_rewriting_main_expression) !== false) {
		// Numéro de page en ?page=... alors que URL rewriting contient déjà numéro de page
		// String::strpos(get_current_url(false, true), '-' . vn($_GET['page']) . '-') === false permettrait de savoir si la valeur dans l'URL rewriting est différente que valeur en GET, mais peu importe, de toutes façons on redirige
		redirect_and_die(get_current_url(false), true);
	}elseif(!empty($_GET['search']) && String::strpos(get_current_url(true, true), 'search=') !== false && check_if_module_active('annonces') && String::strpos(get_current_url(false, true), $this_url_rewriting_main_expression) !== false) {
		// Numéro de page en ?search=... alors que URL rewriting contient déjà la recherche
		// String::strpos(get_current_url(false, true), '-' . vn($_GET['search']) . '.html') === false permettrait de savoir si la valeur dans l'URL rewriting est différente que valeur en GET, mais peu importe, de toutes façons on redirige
		redirect_and_die(get_current_url(false), true);
	}
}
define('IN_SEARCH', true);

if (check_if_module_active('annonces')) {
	get_lang_ads_post_manage($_GET);
	if (check_if_module_active('maps')) {
		include_once($GLOBALS['fonctionsmap']);
	}
}
// Si vous mettez plusieurs multipage sur la page, on ne doit pas considérer que chacun connait le nombre de pages
// => dans ce cas, repassez à false avant le dernier Multipage et pas avant
$GLOBALS['multipage_avoid_redirect_if_page_over_limit'] = true;
if (!empty($GLOBALS['site_parameters']['twenga_ads_account_url'])) {
	$GLOBALS['integrate_twenga_ads'] = true;
}

$output = '';
$output_result = '';
$match = vb($_GET['match']);
$search = vb($_GET['search']);
$real_search = '';
$GLOBALS['meta_title'] = '';
$GLOBALS['meta_description'] = '';
$GLOBALS['meta_keywords'] = '';

if(!empty($_GET['latitude'])){
	$_SESSION['session_latitude'] = floatval($_GET['latitude']);
}
if(!empty($_GET['longitude'])){
	$_SESSION['session_longitude'] = floatval($_GET['longitude']);
}
if (empty($search)) {
	$search = '';
	$terms = array();
} elseif (String::strlen($search) < 3) {
	if (get_current_url(false) != get_current_url(true)) {
		// On n'autorise pas de recherche sur un seul caractère ou 2 caractères
		// On prend une recherche vide à la place, mais en gardant la recherche sur les critères complémentaires
		redirect_and_die(get_current_url(true, false, array('search', 'match')), true);
	}
	$search = '';
	$terms = array();
} else {
	if (!empty($_GET['page']) && $_GET['page'] > 10 && String::strpos($_SERVER['HTTP_USER_AGENT'], 'bingbot') !== false) {
		// Si des données sont en GET (pas URL proprement réécrite sans aucun GET) : on n'autorise pas de recherche sur les pages > 10 de la part de bing car crawl trop agressif
		if (get_current_url(false) != get_current_url(true)) {
			redirect_and_die(get_current_url(false), true);
		}
	}
	$search = trim(String::html_entity_decode($search));
	if ($search == $GLOBALS['STR_ENTER_KEY']) {
		$search = '';
	}
	$real_search = $search;
	$GLOBALS['meta_title'] = String::ucfirst($real_search);
	$GLOBALS['meta_description'] = String::ucfirst($real_search) . ' - ';
	$GLOBALS['meta_keywords'] = $real_search;
	$terms = build_search_terms($real_search, $match);
}
if (!empty($_GET['country'])) {
	$GLOBALS['meta_title'] .= ' ' . String::ucfirst($_GET['country']);
	$GLOBALS['meta_description'] = String::ucfirst($_GET['country']) . ' ' . $GLOBALS['meta_description'];
	$GLOBALS['meta_keywords'] .= ' ' . String::strtolower($_GET['country']);
}
// tableau regroupant les caractéristiques des attributs fixes dans peel
$search_attribute_tab = array('marque' => array('table' => 'marques', 'join' => 'produits', 'join_id' => 'id_marque', 'label' => $GLOBALS['STR_BRAND_LB']),
	'couleur' => array('table' => 'couleurs', 'join' => 'produits_couleurs', 'join_id' => 'couleur_id', 'label' => $GLOBALS['STR_COLOR_LB']),
	'taille' => array('table' => 'tailles', 'join' => 'produits_tailles', 'join_id' => 'taille_id', 'label' => $GLOBALS['STR_TALL_LB'])
	);
if (empty($GLOBALS['site_parameters']['disable_search_form_on_search_page'])) {
	$output_form = get_search_form($_GET, $search, $match, $real_search, "full", !empty($quick_add_product_from_search_page));
} else {
	$output_form = '';
}


// initialisation pour les recherches
$i = 1;
$resultat_produit = true;
$found_words_array = array();
$taille_texte_affiche = 400;

$tpl_r = $GLOBALS['tplEngine']->createTemplate('search_result.tpl');
$tpl_r->assign('STR_SEARCH_PRODUCT', $GLOBALS['STR_SEARCH_PRODUCT']);
$tpl_r->assign('STR_SEARCH_RESULT_PRODUCT', $GLOBALS['STR_SEARCH_RESULT_PRODUCT']);
$tpl_r->assign('STR_SEARCH_NO_RESULT_PRODUCT', $GLOBALS['STR_SEARCH_NO_RESULT_PRODUCT']);
$tpl_r->assign('STR_RESULT_SEARCH', $GLOBALS['STR_RESULT_SEARCH']);
$tpl_r->assign('STR_SEARCH_RESULT_ARTICLE', $GLOBALS['STR_SEARCH_RESULT_ARTICLE']);
$tpl_r->assign('STR_SEARCH_NO_RESULT_ARTICLE', $GLOBALS['STR_SEARCH_NO_RESULT_ARTICLE']);
$tpl_r->assign('STR_SEARCH_RESULT_BRAND', $GLOBALS['STR_SEARCH_RESULT_BRAND']);
$tpl_r->assign('STR_SEARCH_NO_RESULT_BRAND', $GLOBALS['STR_SEARCH_NO_RESULT_BRAND']);
$tpl_r->assign('is_annonce_module_active', check_if_module_active('annonces'));
$tpl_r->assign('search_in_product_and_ads', !empty($GLOBALS['site_parameters']['search_in_product_and_ads']));
$tpl_r->assign('page', vn($_GET['page']));
$tpl_r->assign('search', $real_search);
if (check_if_module_active('sauvegarde_recherche')) {
	$tpl_r->assign('display_save_search_button', display_save_search_button($_GET));
}
if (!check_if_module_active('annonces') || (check_if_module_active('annonces') && !empty($GLOBALS['site_parameters']['search_in_product_and_ads']))) {
	// recherche dans les produits : on teste d'abord si il existe des produits affichables
	$sql = "SELECT id
		FROM peel_produits p
		WHERE p.etat = '1' AND p.nom_" . $_SESSION['session_langue'] . " != '' AND " . get_filter_site_cond('produits', 'p') . "
		LIMIT 1";
	$query = query($sql);
	if (fetch_assoc($query)) {
		$launch_product_search = true;
	}
}
if(!empty($launch_product_search)) {
	// recherche dans les produits
	$additional_sql_inner = '';
	$additional_sql_cond_array = array();
	$additional_sql_having = '';
	if (check_if_module_active('search')) {
		// on construit les conditions supplementaires de recherche
		if (!empty($_GET['taille'])) {
			$additional_sql_inner .= ' INNER JOIN peel_produits_tailles pt ON p.id=pt.produit_id';
			$additional_sql_cond_array[] = 'pt.taille_id= "' . intval($_GET['taille']) . '"';
		}
		if (!empty($_GET['categorie'])) {
			$additional_sql_cond_array[] = 'pc.categorie_id IN ("' . implode('","', get_category_tree_and_itself(intval($_GET['categorie']), 'sons', 'categories')) . '")';
		}
		if (!empty($_GET['couleur'])) {
			$additional_sql_inner .= ' INNER JOIN peel_produits_couleurs pco ON p.id=pco.produit_id';
			$additional_sql_cond_array[] = 'pco.couleur_id="' . intval($_GET['couleur']) . '"';
		}
		if (!empty($_GET['marque'])) {
			$additional_sql_cond_array[] = 'p.id_marque="' . nohtml_real_escape_string($_GET['marque']) . '"';
		}
		if (!empty($_GET['date_flash'])) {
			$additional_sql_cond_array[] = 'p.on_flash="1" AND p.flash_start LIKE "' . get_mysql_date_from_user_input(nohtml_real_escape_string($_GET['date_flash'])) . '%"';
		}
		if (!empty($_GET['custom_attribut']) && is_array($_GET['custom_attribut'])) {
			foreach($_GET['custom_attribut'] as $this_attribut_id) {
				if (!empty($this_attribut_id) && is_numeric($this_attribut_id)) {
					$attributs_array[intval($this_attribut_id)] = true;
				}
			}
		}
		if (!empty($_GET['custom_nom_attribut']) && is_array($_GET['custom_nom_attribut'])) {
			foreach($_GET['custom_nom_attribut'] as $this_nom_attribut_id) {
				if (!empty($this_nom_attribut_id) && is_numeric($this_nom_attribut_id)) {
					$nom_attributs_array[intval($this_nom_attribut_id)] = true;
				}
			}
		}
		if (!empty($attributs_array) || !empty($nom_attributs_array)) {
			$additional_sql_inner .= ' INNER JOIN peel_produits_attributs pat ON p.id=pat.produit_id';
			if (!empty($attributs_array)) {
				$additional_sql_cond_array[] = 'pat.attribut_id IN (' . nohtml_real_escape_string(implode(',', array_keys($attributs_array))) . ')';
				if(count($attributs_array)>1) {
					$having_cond_array[] = 'COUNT(DISTINCT pat.attribut_id)>=' . count($attributs_array);
				}
			}
			if (!empty($nom_attributs_array)) {
				$additional_sql_cond_array[] = 'pat.nom_attribut_id IN (' . nohtml_real_escape_string(implode(',', array_keys($nom_attributs_array))) . ')';
				if(count($attributs_array)>1) {
					$having_cond_array[] = 'COUNT(DISTINCT pat.nom_attribut_id)>=' . count($nom_attributs_array);
				}
			}
			// On veut que le produit ait tous les attributs cherchés : on fait la jointure pour trouver les différentes lignes concernées,
			// et ensuite on doit vérifier que le nombre de lignes trouvées correspond bien à la recherche
			if(!empty($having_cond_array)) {
				$additional_sql_having .= 'HAVING '.implode(' AND ', $having_cond_array);
			}
		}
	}
	if (count($terms) > 0 || !empty($additional_sql_cond_array)) {
		// SQL lié à la recherche textuelle
		unset($fields);
		$fields[] = 'p.nom_' . (!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']);
		$fields[] = 'p.descriptif_' . $_SESSION['session_langue'];
		$fields[] = 'p.description_' . (!empty($GLOBALS['site_parameters']['product_description_forced_lang'])?$GLOBALS['site_parameters']['product_description_forced_lang']:$_SESSION['session_langue']);
		$fields[] = 'p.tab1_html_' . $_SESSION['session_langue'];
		$fields[] = 'p.tab1_title_' . $_SESSION['session_langue'];
		$fields[] = 'p.tab2_html_' . $_SESSION['session_langue'];
		$fields[] = 'p.tab2_title_' . $_SESSION['session_langue'];
		$fields[] = 'p.tab3_html_' . $_SESSION['session_langue'];
		$fields[] = 'p.tab3_title_' . $_SESSION['session_langue'];
		$fields[] = 'p.tab4_html_' . $_SESSION['session_langue'];
		$fields[] = 'p.tab4_title_' . $_SESSION['session_langue'];
		$fields[] = 'p.tab5_html_' . $_SESSION['session_langue'];
		$fields[] = 'p.tab5_title_' . $_SESSION['session_langue'];
		$fields[] = 'p.reference';
		$fields[] = 'c.nom_' . $_SESSION['session_langue'];
		if (count($terms) > 0) {
			$additional_sql_cond_array[] = build_terms_clause($terms, $fields, $match);
		}
		if (!empty($additional_sql_cond_array)) {
			$additional_sql_cond = '(' . implode(') AND (', array_unique($additional_sql_cond_array)) . ')';
		} else {
			$additional_sql_cond = '';
		}
		$result_affichage_produit = affiche_produits(null, 2, 'search', $GLOBALS['site_parameters']['nb_produit_page'], 'column', true, 0, 3, true, true, $additional_sql_inner, $additional_sql_cond, $additional_sql_having);
		if(!empty($GLOBALS['products_found']) && String::strlen($real_search)>=4) {
			$found_words_array[] = $real_search;
		}
		$tpl_r->assign('result_affichage_produit', $result_affichage_produit);
	}
}
if (check_if_module_active('annonces')) {
	// On fait la recherche dans le module d'annonces si il est présent
	$additional_sql_cond_array = array();
	$additional_sql_inner = '';
	$categorie_annonce = null;
	$tpl_r->assign('STR_MODULE_ANNONCES_SEARCH_RESULT_ADS', $GLOBALS['STR_MODULE_ANNONCES_SEARCH_RESULT_ADS']);
	$tpl_r->assign('STR_MODULE_ANNONCES_SEARCH_NO_RESULT_ADS', $GLOBALS['STR_MODULE_ANNONCES_SEARCH_NO_RESULT_ADS']);
	if (!empty($_GET['cat_select']) && !is_array($_GET['cat_select'])) {
		$categorie_annonce = intval($_GET['cat_select']);
	}
	// Si la catégorie est unique, elle est traitée séparément de get_ad_search_sql
	$sql_cond = get_ad_search_sql($_GET, empty($categorie_annonce));
	if (!empty($GLOBALS['site_parameters']['ads_search_page_display_mode']) && $GLOBALS['site_parameters']['ads_search_page_display_mode'] == 'column') {
		$boostrap_column_sizes_array = array('sm' => 4, 'md' => 4, 'lg' => 4);
	} else {
		$boostrap_column_sizes_array = array('sm' => 12, 'md' => 12, 'lg' => 12);
	}
	$res_affiche_annonces = affiche_annonces($categorie_annonce, $sql_cond, null, 'search', $GLOBALS['site_parameters']['ads_per_page'], vb($GLOBALS['site_parameters']['ads_search_page_display_mode'], 'line'), true, null, 4, false, true, false, $boostrap_column_sizes_array, 0, '', '', false);
	if(!empty($GLOBALS['ads_found']) && String::strlen($real_search)>=4) {
		$found_words_array[] = $real_search;
	}
	if (vn($_GET['page'])<=1) {
		// On transmet le GET, en étant compatible avec les tableaux en GET (=> por ne pas avoir besoin de reconstruire la chaine à partir de GET)
		$xml_call_args = String::substr(get_current_url(true),String::strlen(get_current_url(false))+1);
		if(!empty($_GET['search']) && strpos($xml_call_args, 'search=')===false) {
			// Gestion des pages avec URL rewriting
			if(!empty($xml_call_args)) {
				$xml_call_args .= '&';
			}
			$xml_call_args .= 'search='.$_GET['search'];
		}
		if(!empty($GLOBALS['ads_found']) && check_if_module_active('maps')) {
			$res_affiche_annonces = getUserMap(null, $xml_call_args, 0, false, false) . $res_affiche_annonces;
		}
	}
	$tpl_r->assign('res_affiche_annonces', $res_affiche_annonces);
}

if (vn($_GET['page'])<=1 && count($terms) > 0) {
	// On ne recherche dans les articles & marques que si l'on a renseigné le champs texte
	// Affichage sur la première page uniquement (pas de multipage)
	$tpl_r->assign('are_terms', true);
	$tpl_arts_found = array();
	// Recherche dans les rubriques : on teste d'abord si il existe des rubriques affichables
	$sql = "SELECT id
		FROM peel_rubriques r
		WHERE r.etat = '1' AND r.nom_" . $_SESSION['session_langue'] . " != '' AND " . get_filter_site_cond('rubriques', 'r') . "
		LIMIT 1";
	$query = query($sql);
	if (fetch_assoc($query)) {
		$launch_content_category_search = true;
	}
	if(!empty($launch_content_category_search)) {
		// Recherche dans les rubriques
		unset($fields);
		$fields[] = 'r.nom_' . $_SESSION['session_langue'];
		$fields[] = 'r.description_' . $_SESSION['session_langue'];
		$sql = build_sql_content_category($terms, $fields, $match);
		$result = query($sql);
		while ($rub = fetch_assoc($result)) {
			$titre = $rub['nom_' . $_SESSION['session_langue']];
			// on supprime le HTML du contenu
			$texte = String::strip_tags(String::html_entity_decode_if_needed($rub['description_' . $_SESSION['session_langue']]));
			// si trop long, on coupe
			$texte = String::str_shorten($texte, $taille_texte_affiche, '', '...', $taille_texte_affiche-20);
			// on fait une recherche sur le texte sans accent avec les mots de l'utilisateur
			$texte = highlight_found_text($texte, $terms, $found_words_array);
			$titre = highlight_found_text($titre, $terms, $found_words_array);
			// affichage
			$tpl_arts_found[] = array('num' => $i,
				'category_href' => get_content_category_url($rub['id'], $rub['nom_' . $_SESSION['session_langue']]),
				'rubrique' => $rub['nom_' . $_SESSION['session_langue']],
				'content_href' => null,
				'titre' => $titre,
				'texte' => $texte
				);
			$i++;
		}
	}
	// Recherche dans les articles : on teste d'abord si il existe des articles affichables
	$sql = "SELECT id
		FROM peel_articles a
		WHERE  " . get_filter_site_cond('articles', 'a') . " AND a.etat = '1' AND (a.chapo_" . $_SESSION['session_langue'] . " != '' || a.texte_" . $_SESSION['session_langue'] . " != '')
		LIMIT 1";
	$query = query($sql);
	if (fetch_assoc($query)) {
		$launch_article_search = true;
	}
	if(!empty($launch_article_search)) {
		// recherche dans les articles
		unset($fields);
		$fields[] = 'a.surtitre_' . $_SESSION['session_langue'];
		$fields[] = 'a.titre_' . $_SESSION['session_langue'];
		$fields[] = 'a.texte_' . $_SESSION['session_langue'];
		$fields[] = 'a.chapo_' . $_SESSION['session_langue'];
		$sql = build_sql_articles($terms, $fields, $match);
		$result = query($sql);
		while ($art = fetch_assoc($result)) {
			$surtitre = $art['surtitre_' . $_SESSION['session_langue']];
			$titre = $art['titre_' . $_SESSION['session_langue']];
			// on supprime le HTML du contenu
			$texte = String::strip_tags(String::html_entity_decode_if_needed($art['texte_' . $_SESSION['session_langue']]));
			$chapo = String::strip_tags(String::html_entity_decode_if_needed($art['chapo_' . $_SESSION['session_langue']]));
			// si trop long, on coupe
			$texte = String::str_shorten($texte, $taille_texte_affiche, '', '...', $taille_texte_affiche-20);
			$chapo = String::str_shorten($chapo, $taille_texte_affiche, '', '...', $taille_texte_affiche-20);
			// on fait une recherche sur le texte sans accent avec les mots de l'utilisateur
			$texte = highlight_found_text($texte, $terms, $found_words_array);
			$titre = highlight_found_text($titre, $terms, $found_words_array);
			// certains champ ne sont pas affichés, mais on teste pour savoir si le mot se trouve dedans pour l'ajouter au tag_cloud
			// on ajoute dans le tableau  $found_words_array[]
			highlight_found_text($surtitre, $terms, $found_words_array);
			highlight_found_text($chapo, $terms, $found_words_array);
			// affichage
			$tpl_arts_found[] = array('num' => $i,
				'category_href' => get_content_category_url($art['rubrique_id'], $art['rubrique']),
				'rubrique' => $art['rubrique'],
				'content_href' => get_content_url($art['id'], $titre, $art['rubrique_id'], $art['rubrique']),
				'titre' => $titre,
				'texte' => $texte
				);
			$i++;
		}
		$tpl_r->assign('arts_found', $tpl_arts_found);
	}
	// Recherche dans les marques : on teste d'abord si il existe des marques affichables
	$sql = "SELECT id
		FROM peel_marques m
		WHERE m.etat = '1' AND m.nom_" . $_SESSION['session_langue'] . " != '' AND " . get_filter_site_cond('marques', 'm')  . "
		LIMIT 1";
	$query = query($sql);
	if (fetch_assoc($query)) {
		$launch_article_search = true;
	}
	if(!empty($launch_brand_search)) {
		// Recherche dans les marques
		$tpl_brands_found = array();
		$i = 1;
		unset($fields);
		$fields[] = 'm.nom_' . $_SESSION['session_langue'];
		$fields[] = 'm.description_' . $_SESSION['session_langue'];
		$sql = build_sql_marques($terms, $fields, $match);
		$result = query($sql);
		while ($marque = fetch_assoc($result)) {
			$nom = $marque['nom_' . $_SESSION['session_langue']];
			$urlbrand = get_url('/achat/marque.php', array('id' => $marque['id']));
			// on supprime le HTML du contenu
			$description = String::strip_tags(String::html_entity_decode_if_needed($marque['description_' . $_SESSION['session_langue']]));
			// on coupe le texte si trop long
			$description = String::str_shorten($description, $taille_texte_affiche, '', '...', $taille_texte_affiche-20);
			// on fait une recherche sur le texte sans accent avec les mots de l'utilisateur,
			// si qqchose est trouvé, highlight_found_text l'ajoute dans le tableau  $found_words_array[]
			$description = highlight_found_text($description, $terms, $found_words_array);
			$nom = highlight_found_text($nom, $terms, $found_words_array);
			// affichage
			$tpl_brands_found[] = array('num' => $i,
				'href' => $urlbrand,
				'nom' => $nom,
				'description' => $description
				);
			$i++;
		}
		$tpl_r->assign('brands_found', $tpl_brands_found);
	}
	// On sait quel mot recherché correspond à un mot existant sur une première page de recherche, on l'ajoute dans peel_tag_cloud
	if (vn($_GET['page'])<=1 && !empty($found_words_array) && check_if_module_active('tagcloud')) {
		$keywords_array = get_keywords_from_text($found_words_array, 5, 20, false, true, null);
		foreach ($keywords_array as $this_keyword) {
			// On ne stocke que les expressions globales, et les mots suffisamment longs pour éviter tous les mots d'articulation
			sql_tagcloud($this_keyword);
		}
	}
}
$result = $tpl_r->fetch();
$tpl = $GLOBALS['tplEngine']->createTemplate('search.tpl');
if (!empty($_GET['type']) && $_GET['type'] == 'error404') {
	$content = affiche_contenu_html('error404', true);
	if(!$GLOBALS['affiche_contenu_html_last_found']) {
		// Pas de contenu défini dans la langue de la page, on force un message pour indiquer l'erreur
		$content = '<h1>Error 404</h1><br />';
	}
	$tpl->assign('content', $content);
	
}
$tpl->assign('quick_add_product_from_search_page', !empty($quick_add_product_from_search_page));
$tpl->assign('form', $output_form);
$tpl->assign('search', $real_search);
$tpl->assign('result', vb($result));
$tpl->assign('page', vn($_GET['page']));
$tpl->assign('STR_SEARCH_HELP', $GLOBALS['STR_SEARCH_HELP']);
$tpl->assign('STR_SEARCH_PRODUCT', $GLOBALS['STR_SEARCH_PRODUCT']);
$output .= $tpl->fetch();

include($GLOBALS['repertoire_modele'] . '/haut.php');
echo $output;
include($GLOBALS['repertoire_modele'] . '/bas.php');

/* FONCTIONS */

/**
 * build_sql_content_category()
 *
 * @param mixed $terms
 * @param mixed $fields
 * @param mixed $match_method
 * @return
 */
function build_sql_content_category($terms, $fields, $match_method)
{
	$requete = 'SELECT r.id';
	foreach ($fields as $value) {
		$requete .= ', ' . real_escape_string($value) . ' ';
	}
	$requete .= '
		FROM peel_rubriques r
		WHERE r.etat = "1" AND ' . build_terms_clause($terms, $fields, $match_method) . ' AND ' . get_filter_site_cond('rubriques', 'r') . '
		ORDER BY r.position ASC, r.id DESC
		LIMIT 100';
	return $requete;
}

/**
 * build_sql_articles()
 *
 * @param mixed $terms
 * @param mixed $fields
 * @param mixed $match_method
 * @return
 */
function build_sql_articles($terms, $fields, $match_method)
{
	$requete = 'SELECT a.id, r.id AS rubrique_id, r.nom_' . $_SESSION['session_langue'] . ' AS rubrique ';
	foreach ($fields as $value) {
		$requete .= ', ' . real_escape_string($value) . ' ';
	}
	$requete .= '
		FROM peel_articles a
		INNER JOIN peel_articles_rubriques ar ON ar.article_id = a.id
		INNER JOIN peel_rubriques r ON r.id = ar.rubrique_id AND r.technical_code NOT IN ("nosearch", "other", "iphone_content") AND ' . get_filter_site_cond('rubriques', 'r') . '
		WHERE a.etat = "1" AND ' . get_filter_site_cond('articles', 'a') . ' AND a.technical_code NOT IN ("nosearch") AND ' . build_terms_clause($terms, $fields, $match_method) . '
		GROUP BY a.id
		ORDER BY a.position ASC, a.id DESC
		LIMIT 100';
	return $requete;
}

/**
 * build_sql_marques()
 *
 * @param mixed $terms
 * @param mixed $fields
 * @param mixed $match_method
 * @return
 */
function build_sql_marques($terms, $fields, $match_method)
{
	$requete = 'SELECT m.id ';
	foreach ($fields as $value) {
		$requete .= ', ' . real_escape_string($value) . ' ';
	}
	$requete .= '
		FROM peel_marques m
		WHERE m.etat = "1" AND ' . get_filter_site_cond('marques', 'm') . ' AND ' . build_terms_clause($terms, $fields, $match_method) . '
		ORDER BY m.id DESC
		LIMIT 100';
	return $requete;
}


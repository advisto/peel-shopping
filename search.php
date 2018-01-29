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
// $Id: search.php 55332 2017-12-01 10:44:06Z sdelaporte $
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

call_module_hook('search_pre', array());
$launch_search = true;

if (!empty($GLOBALS['site_parameters']['search_default_display_show_no_result']) && empty($_GET)) {
	$launch_search = false;
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
if (check_if_module_active('annonces') && StringMb::rawurldecode(get_current_url(false, true)) == StringMb::rawurldecode('/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/' . $GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'] . '-' . StringMb::rawurlencode(vn($_GET['page'])) . '-' . vb($_GET['search']) . '.html') || StringMb::rawurldecode(get_current_url(false, true)) == StringMb::rawurldecode('/produits/' . vb($_GET['search']) . '.html') || StringMb::rawurldecode(get_current_url(false, true)) == StringMb::rawurldecode('/produits/' . vb($_GET['search']) . '-' . vn($_GET['page']) . '.html')) {
	// Pour éviter des problèmes avec les + dans l'URL, on a dans le .htaccess une règle avec l'option B qui permet de garder encodé search et est à décoder via StringMb::rawurldecode
	$_GET['search'] = StringMb::rawurldecode(vb($_GET['search']));
	if (strpos($_GET['search'], '/') !== false) {
		// Depuis octobre 2012, plus de possibilité d'avoir des / dans les URL de recherches qui sont réécrites et où search provient du coeur de l'URL
		// Ca permet d'éviter un bug avec apache et les %2F en dehors du GET
		redirect_and_die('/', true);
	}
}
foreach(array('/produits/', '/'.$GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'].'-', '/supplier-research-', '/recherche-fournisseur-', '/busqueda-proveedor-') as $this_url_rewriting_main_expression) {
	if(!empty($_GET['page']) && StringMb::strpos(get_current_url(true, true), 'page=') !== false && check_if_module_active('annonces') && StringMb::strpos(get_current_url(false, true), $this_url_rewriting_main_expression) !== false) {
		// Numéro de page en ?page=... alors que URL rewriting contient déjà numéro de page
		// StringMb::strpos(get_current_url(false, true), '-' . vn($_GET['page']) . '-') === false permettrait de savoir si la valeur dans l'URL rewriting est différente que valeur en GET, mais peu importe, de toutes façons on redirige
		redirect_and_die(get_current_url(false), true);
	}elseif(!empty($_GET['search']) && StringMb::strpos(get_current_url(true, true), 'search=') !== false && check_if_module_active('annonces') && StringMb::strpos(get_current_url(false, true), $this_url_rewriting_main_expression) !== false) {
		// Numéro de page en ?search=... alors que URL rewriting contient déjà la recherche
		// StringMb::strpos(get_current_url(false, true), '-' . vn($_GET['search']) . '.html') === false permettrait de savoir si la valeur dans l'URL rewriting est différente que valeur en GET, mais peu importe, de toutes façons on redirige
		redirect_and_die(get_current_url(false), true);
	}
}
define('IN_SEARCH', true);

if (check_if_module_active('annonces')) {
	get_lang_ads_post_manage($_GET);
}
// Si vous mettez plusieurs multipage sur la page, on ne doit pas considérer que chacun connait le nombre de pages
// => dans ce cas, repassez à false avant le dernier Multipage et pas avant
$GLOBALS['multipage_avoid_redirect_if_page_over_limit'] = true;
if (!empty($GLOBALS['site_parameters']['twenga_ads_account_url']) || !empty($GLOBALS['site_parameters']['twenga_ads_script'])) {
	$GLOBALS['integrate_twenga_ads'] = true;
}

$output = '';
$output_result = '';
$match = vb($_GET['match'], 1);
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
} elseif (StringMb::strlen($search) < 3) {
	if (get_current_url(false) != get_current_url(true)) {
		// On n'autorise pas de recherche sur un seul caractère ou 2 caractères
		// On prend une recherche vide à la place, mais en gardant la recherche sur les critères complémentaires
		redirect_and_die(get_current_url(true, false, array('search', 'match')), true);
	}
	$search = '';
	$terms = array();
} else {
	if (!empty($_GET['page']) && $_GET['page'] > 10 && StringMb::strpos($_SERVER['HTTP_USER_AGENT'], 'bingbot') !== false) {
		// Si des données sont en GET (pas URL proprement réécrite sans aucun GET) : on n'autorise pas de recherche sur les pages > 10 de la part de bing car crawl trop agressif
		if (get_current_url(false) != get_current_url(true)) {
			redirect_and_die(get_current_url(false), true);
		}
	}
	$search = trim(StringMb::html_entity_decode($search));
	if ($search == $GLOBALS['STR_ENTER_KEY']) {
		$search = '';
	}
	$real_search = $search;
	$terms = build_search_terms($real_search, $match);
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

$tpl = $GLOBALS['tplEngine']->createTemplate('search.tpl');

if($launch_search) {
	// initialisation pour les recherches
	$i = 1;
	$resultat_produit = true;
	$GLOBALS['found_words_array'] = array();
	$taille_texte_affiche = 400;
	$GLOBALS['search_text_array'] = array();
	if(!empty($real_search)) {
		$GLOBALS['search_text_array']['search'] = StringMb::ucfirst($real_search);
	}
	
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
	if (!empty($_GET['date_end']) && !empty($GLOBALS['STR_AT_LEAST_ONE_CAMPAIGN'])) {
		$tpl_r->assign('STR_AT_LEAST_ONE_CAMPAIGN', vb($GLOBALS['STR_AT_LEAST_ONE_CAMPAIGN']));
	}
	if (check_if_module_active('sauvegarde_recherche')) {
		$tpl_r->assign('display_save_search_button', display_save_search_button($_GET));
	}
	if (!check_if_module_active('annonces') || (check_if_module_active('annonces') && !empty($GLOBALS['site_parameters']['search_in_product_and_ads'])) && (empty($GLOBALS['site_parameters']['search_types_array']) || in_array('products', $GLOBALS['site_parameters']['search_types_array']))) {
		// recherche dans les produits : on teste d'abord si il existe des produits affichables
		$result_affichage_produit = search_products($_GET, $terms, $match, $real_search);
		if($result_affichage_produit !== null) {
			$tpl_r->assign('result_affichage_produit', $result_affichage_produit);
			$tpl_r->assign('products_found', $GLOBALS['products_found']);
			$tpl_r->assign('STR_PRODUCTS', $GLOBALS['STR_PRODUCTS']);
		}
	}
	if (check_if_module_active('annonces') && (empty($GLOBALS['site_parameters']['search_types_array']) || in_array('ads', $GLOBALS['site_parameters']['search_types_array']))) {
		// Recherche dans les annonces
		$res_affiche_annonces = get_ad_search_results($_GET, vb($_GET['cat_select']), $real_search);
		$tpl_r->assign('res_affiche_annonces', $res_affiche_annonces);
		$tpl_r->assign('ads_found', $GLOBALS['ads_found']);
		$tpl_r->assign('STR_MODULE_ANNONCES_ADS', $GLOBALS['STR_MODULE_ANNONCES_ADS']);
		$tpl_r->assign('STR_MODULE_ANNONCES_SEARCH_RESULT_ADS', $GLOBALS['STR_MODULE_ANNONCES_SEARCH_RESULT_ADS']);
		$tpl_r->assign('STR_MODULE_ANNONCES_SEARCH_NO_RESULT_ADS', $GLOBALS['STR_MODULE_ANNONCES_SEARCH_NO_RESULT_ADS']);
	}

	if (vn($_GET['page'])<=1 && count($terms) > 0) {
		// On ne recherche dans les articles & marques que si l'on a renseigné le champs texte
		// Affichage sur la première page uniquement (pas de multipage)
		$tpl_r->assign('are_terms', true);

		if(empty($GLOBALS['site_parameters']['search_types_array']) || in_array('content', $GLOBALS['site_parameters']['search_types_array'])) {
			// Recherche dans les rubriques et les articles
			$content_categories_array = search_content_categories($terms, $match, $taille_texte_affiche);
			$content_array = search_articles($terms, $match, $taille_texte_affiche);
			$tpl_articles_found = array_merge_recursive_distinct($content_categories_array, $content_array);
			if($tpl_articles_found !== null) {
				$tpl_r->assign('arts_found', $tpl_articles_found);
				$tpl_r->assign('STR_ARTICLES', $GLOBALS['STR_ARTICLES']);
			}
		}
		if(empty($GLOBALS['site_parameters']['search_types_array']) || in_array('brands', $GLOBALS['site_parameters']['search_types_array'])) {
			// Recherche dans les marques
			$tpl_brands_found = search_brands($terms, $match, $taille_texte_affiche);
			if($tpl_brands_found !== null) {
				$tpl_r->assign('brands_found', $tpl_brands_found);
				$tpl_r->assign('STR_BRANDS', $GLOBALS['STR_BRANDS']);
			}
		}
	}
	// Résultats du hook : à renvoyer sous le format 'XXX(modulename)' => array('results' => $results_found, 'title' => $GLOBALS['STR_XXX_TITLE'], 'no_result' => null)
	$GLOBALS['search_complementary_results_array'] = call_module_hook('search_complementary', array('frm' => $_GET, 'match' => $match, 'real_search' => $real_search, 'terms' => $terms, 'taille_texte_affiche' => $taille_texte_affiche, 'mode' => 'search', 'page' => vn($_GET['page'])), 'array');
	if(!empty($GLOBALS['site_parameters']['search_complementary_found_sort_array'])) {
		// Tri des thématiques de résultats si défini
		// Le tableau search_complementary_found_sort_array peut être sous la forme 'type' => N, ...  ou simplement 'type1', 'type2', ...
		uksort($GLOBALS['search_complementary_results_array'], 'resultsTypeCompareArgsOrder');
	}elseif(!empty($GLOBALS['site_parameters']['search_complementary_found_sort_by_count'])) {
		// Tri des thématiques de résultats par nombre de résultats décroissant
		uksort($GLOBALS['search_complementary_results_array'], 'resultsTypeCompareArgsOrder');
	}
	$tpl_r->assign('search_complementary_results_array', $GLOBALS['search_complementary_results_array']);
	$tpl_r->assign('STR_RESULTS', $GLOBALS['STR_RESULTS']);

	if (vn($_GET['page'])<=1 && count($terms) > 0 && !empty($GLOBALS['found_words_array']) && check_if_module_active('tagcloud')) {
		// On sait quel mot recherché correspond à un mot existant sur une première page de recherche, on l'ajoute dans peel_tag_cloud
		$keywords_array = get_keywords_from_text($GLOBALS['found_words_array'], 5, 20, false, true, null);
		foreach ($keywords_array as $this_keyword) {
			// On ne stocke que les expressions globales, et les mots suffisamment longs pour éviter tous les mots d'articulation
			sql_tagcloud($this_keyword);
		}
	}
	$search_text = implode(' - ', $GLOBALS['search_text_array']);
	$tpl_r->assign('search', $search_text);
	$result = $tpl_r->fetch();
	$GLOBALS['meta_title'] = $search_text;
	$GLOBALS['meta_description'] = $search_text;
	$GLOBALS['meta_keywords'] = StringMb::strtolower(str_replace(' - ', ' ', $search_text));
} else {
	$content = affiche_contenu_html('search_default_top', true);
	$content .= call_module_hook('search_default_display', null, 'string');
	$tpl->assign('content', $content);
}

if (!empty($_GET['type']) && $_GET['type'] == 'error404') {
	$content = affiche_contenu_html('error404', true);
	if(!$GLOBALS['affiche_contenu_html_last_found']) {
		// NB : on ne teste pas ci-dessus si $content est vide, car on peut vouloir que la zone HTML soit vide et qu'on veuille garder ce vide, sans passer ici
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
 * search_content_categories_sql()
 *
 * @param mixed $terms
 * @param mixed $fields
 * @param mixed $match_method
 * @return
 */
function search_content_categories_sql($terms, $fields, $match_method)
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
 * Recherche dans les rubriques : on teste d'abord si il existe des rubriques affichables
 *
 * @param mixed $terms
 * @param mixed $match
 * @param mixed $taille_texte_affiche
 * @return
 */
function search_content_categories($terms, $match, $taille_texte_affiche) {
	$tpl_arts_found = array();
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
		$sql = search_content_categories_sql($terms, $fields, $match);
		$query = query($sql);
		$i = 0;
		while ($rub = fetch_assoc($query)) {
			$titre = $rub['nom_' . $_SESSION['session_langue']];
			// on supprime le HTML du contenu
			$texte = StringMb::strip_tags(StringMb::html_entity_decode_if_needed($rub['description_' . $_SESSION['session_langue']]));
			// si trop long, on coupe
			$texte = StringMb::str_shorten($texte, $taille_texte_affiche, '', '...', $taille_texte_affiche-20);
			// on fait une recherche sur le texte sans accent avec les mots de l'utilisateur
			$texte = highlight_found_text($texte, $terms, $GLOBALS['found_words_array']);
			$titre = highlight_found_text($titre, $terms, $GLOBALS['found_words_array']);
			// affichage
			$i++;
			$tpl_arts_found[] = array('num' => $i,
				'category_href' => get_content_category_url($rub['id'], $rub['nom_' . $_SESSION['session_langue']]),
				'rubrique' => $rub['nom_' . $_SESSION['session_langue']],
				'content_href' => null,
				'titre' => $titre,
				'texte' => $texte
				);
		}
	}
	return $tpl_arts_found;
}

/**
 * search_articles_sql()
 *
 * @param mixed $terms
 * @param mixed $fields
 * @param mixed $match_method
 * @return
 */
function search_articles_sql($terms, $fields, $match_method)
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
 * 	Recherche dans les articles : on teste d'abord si il existe des articles affichables
 *
 * @param mixed $terms
 * @param mixed $match
 * @param mixed $taille_texte_affiche
 * @return
 */
function search_articles($terms, $match, $taille_texte_affiche) {
	$tpl_arts_found = array();
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
		$sql = search_articles_sql($terms, $fields, $match);
		$query = query($sql);
		$i = 0;
		while ($art = fetch_assoc($query)) {
			$surtitre = $art['surtitre_' . $_SESSION['session_langue']];
			$titre = $art['titre_' . $_SESSION['session_langue']];
			// on supprime le HTML du contenu
			$texte = StringMb::strip_tags(StringMb::html_entity_decode_if_needed($art['texte_' . $_SESSION['session_langue']]));
			$chapo = StringMb::strip_tags(StringMb::html_entity_decode_if_needed($art['chapo_' . $_SESSION['session_langue']]));
			// si trop long, on coupe
			$texte = StringMb::str_shorten($texte, $taille_texte_affiche, '', '...', $taille_texte_affiche-20);
			$chapo = StringMb::str_shorten($chapo, $taille_texte_affiche, '', '...', $taille_texte_affiche-20);
			// on fait une recherche sur le texte sans accent avec les mots de l'utilisateur
			$texte = highlight_found_text($texte, $terms, $GLOBALS['found_words_array']);
			$titre = highlight_found_text($titre, $terms, $GLOBALS['found_words_array']);
			// certains champ ne sont pas affichés, mais on teste pour savoir si le mot se trouve dedans pour l'ajouter au tag_cloud
			// on ajoute dans le tableau  $GLOBALS['found_words_array'][]
			highlight_found_text($surtitre, $terms, $GLOBALS['found_words_array']);
			highlight_found_text($chapo, $terms, $GLOBALS['found_words_array']);
			// affichage
			$i++;
			$tpl_arts_found[] = array('num' => $i,
				'category_href' => get_content_category_url($art['rubrique_id'], $art['rubrique']),
				'rubrique' => $art['rubrique'],
				'content_href' => get_content_url($art['id'], $titre, $art['rubrique_id'], $art['rubrique']),
				'titre' => $titre,
				'texte' => $texte
				);
		}
	}
	return $tpl_arts_found;
}

/**
 * search_brands_sql()
 *
 * @param mixed $terms
 * @param mixed $fields
 * @param mixed $match_method
 * @return
 */
function search_brands_sql($terms, $fields, $match_method)
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

/**
 * search_brands()
 *
 * @param mixed $terms
 * @param mixed $match
 * @param mixed $taille_texte_affiche
 * @return
 */
function search_brands($terms, $match, $taille_texte_affiche) {
	// Recherche dans les marques : on teste d'abord si il existe des marques affichables
	$tpl_brands_found = null;
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
		$i = 0;
		unset($fields);
		$fields[] = 'm.nom_' . $_SESSION['session_langue'];
		$fields[] = 'm.description_' . $_SESSION['session_langue'];
		$sql = search_brands_sql($terms, $fields, $match);
		$query = query($sql);
		while ($marque = fetch_assoc($query)) {
			$nom = $marque['nom_' . $_SESSION['session_langue']];
			$urlbrand = get_url('/achat/marque.php', array('id' => $marque['id']));
			// on supprime le HTML du contenu
			$description = StringMb::strip_tags(StringMb::html_entity_decode_if_needed($marque['description_' . $_SESSION['session_langue']]));
			// on coupe le texte si trop long
			$description = StringMb::str_shorten($description, $taille_texte_affiche, '', '...', $taille_texte_affiche-20);
			// on fait une recherche sur le texte sans accent avec les mots de l'utilisateur,
			// si qqchose est trouvé, highlight_found_text l'ajoute dans le tableau  $GLOBALS['found_words_array'][]
			$description = highlight_found_text($description, $terms, $GLOBALS['found_words_array']);
			$nom = highlight_found_text($nom, $terms, $GLOBALS['found_words_array']);
			// affichage
			$i++;
			$tpl_brands_found[] = array('num' => $i,
				'href' => $urlbrand,
				'nom' => $nom,
				'description' => $description
				);
		}
	}
	return $tpl_brands_found;
}

/**
 * search_products()
 *
 * @param array $frm
 * @param array $terms
 * @param string $match
 * @param string $real_search
 * @return
 */
function search_products($frm, $terms, $match, $real_search) {
	$result_affichage_produit = null;
	$sql = "SELECT id
		FROM peel_produits p
		WHERE p.etat = '1' AND p.nom_" . $_SESSION['session_langue'] . " != '' AND " . get_filter_site_cond('produits', 'p') . "
		LIMIT 1";
	$query = query($sql);
	if (fetch_assoc($query)) {
		$launch_product_search = true;
	}
	if(!empty($launch_product_search)) {
		// recherche dans les produits
		$additional_sql_inner = '';
		$additional_sql_cond_array = array();
		$additional_sql_having = '';
		if (check_if_module_active('search')) {
			// on construit les conditions supplémentaires de recherche
			if (!empty($frm['taille'])) {
				$additional_sql_inner .= ' INNER JOIN peel_produits_tailles pt ON p.id=pt.produit_id';
				$additional_sql_cond_array[] = 'pt.taille_id="' . intval($frm['taille']) . '"';
				$this_size_name = get_size_name($frm['categorie']);
				if (!empty($this_size_name)) {
					$GLOBALS['search_text_array']['size'] = StringMb::ucfirst($this_size_name);
				}
			}
			if (!empty($frm['categorie'])) {
				$additional_sql_cond_array[] = 'pc.categorie_id IN ("' . implode('","', get_category_tree_and_itself(intval($frm['categorie']), 'sons', 'categories')) . '")';
				$this_category_name = get_category_name($frm['categorie']);
				if (!empty($this_category_name)) {
					$GLOBALS['search_text_array']['categorie'] = StringMb::ucfirst($this_category_name);
				}
			}
			if (!empty($frm['couleur'])) {
				$additional_sql_inner .= ' INNER JOIN peel_produits_couleurs pco ON p.id=pco.produit_id';
				$additional_sql_cond_array[] = 'pco.couleur_id="' . intval($frm['couleur']) . '"';
				$this_color_name = get_color_name($frm['couleur']);
				if (!empty($this_color_name)) {
					$GLOBALS['search_text_array']['color'] = StringMb::ucfirst($this_color_name);
				}
			}
			if (!empty($frm['marque'])) {
				$additional_sql_cond_array[] = 'p.id_marque="' . nohtml_real_escape_string($frm['marque']) . '"';
			}
			if (!empty($frm['date_flash'])) {
				$additional_sql_cond_array[] = 'p.on_flash="1" AND p.flash_start LIKE "' . get_mysql_date_from_user_input($frm['date_flash']) . '%"';
			}
			if (!empty($frm['custom_attribut']) && is_array($frm['custom_attribut'])) {
				foreach($frm['custom_attribut'] as $this_attribut_id) {
					if (!empty($this_attribut_id) && is_numeric($this_attribut_id)) {
						$attributs_array[intval($this_attribut_id)] = true;
					}
				}
			}
			if (!empty($frm['custom_nom_attribut']) && is_array($frm['custom_nom_attribut'])) {
				foreach($frm['custom_nom_attribut'] as $this_nom_attribut_id) {
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
			$result_affichage_produit = affiche_produits(null, 2, 'search', $GLOBALS['site_parameters']['nb_produit_page'], 'column', true, 0, vn($GLOBALS['site_parameters']['search_pages_nb_column'],3), true, true, $additional_sql_inner, $additional_sql_cond, $additional_sql_having);
			if(!empty($GLOBALS['products_found']) && StringMb::strlen($real_search)>=4) {
				$GLOBALS['found_words_array'][] = $real_search;
			}
		}
	}
	return $result_affichage_produit;
}

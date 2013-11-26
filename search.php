<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: search.php 39023 2013-11-26 00:43:48Z gboussin $
if (!empty($_GET['type']) && $_GET['type'] == 'error404') {
	if (substr($_SERVER['REQUEST_URI'], 0, 1) == '/' && substr($_SERVER['REQUEST_URI'], 3, 1) == '/' && substr($_SERVER['REQUEST_URI'], 1, 2) != 'js') {
		// On a une langue dans l'URL en tant que premier répertoire
		// On la récupère ici, car le .htaccess n'a pas pu la récupérer pour gérer la 404
		// NB : en revanche, pour les sous-domaines différents pour une langue donnée, ça se fait déjà en PHP dans le process normal, donc pas besoin de gérer ici
		$_GET['langue'] = substr($_SERVER['REQUEST_URI'], 1, 2);
	}
	define('IN_404_ERROR_PAGE', true);
	$page_name = 'error404';
	if(in_array(substr($_SERVER['REQUEST_URI'],-4), array('.css', '.png', '.jpg', '.gif', '.txt', '.xml'))) {
		// Fichier CSS demandé : aucun contenu envoyé
		// En effet, si le dossier n'existe pas, on peut arriver ici malgré le .htaccess <FilesMatch "\.(gif|jpe?g|png|ico|xml|gz|zip|txt|js|css)$">	ErrorDocument 404 default </FilesMatch>
		die();
	}
}
include("configuration.inc.php");
if (!empty($_GET['type']) && $_GET['type'] == 'error404') {
	// On va présenter des résultats par défaut => on met comme URL de référence search.php pour le multipage, sinon cela créerait des liens vers des pages inexistantes
	$_SERVER['REQUEST_URI'] = $GLOBALS['apparent_folder'] . 'search.php';
}
if (is_annonce_module_active() && String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/' . $GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'] . '-' . String::rawurlencode(vn($_GET['page'])) . '-' . vb($_GET['search']) . '.html') || String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/produits/' . vb($_GET['search']) . '.html') || String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/produits/' . vb($_GET['search']) . '-' . vn($_GET['page']) . '.html')) {
	// Pour éviter des problèmes avec les + dans l'URL, on a dans le .htaccess une règle avec l'option B qui permet de garder encodé search et est à décoder via String::rawurldecode
	$_GET['search'] = String::rawurldecode(vb($_GET['search']));
	if (strpos($_GET['search'], '/') !== false) {
		// Depuis octobre 2012, plus de possibilité d'avoir des / dans les URL de recherches qui sont réécrites et où search provient du coeur de l'URL
		// Ca permet d'éviter un bug avec apache et les %2F en dehors du GET
		redirect_and_die('/', true);
	}
}
foreach(array('/produits/', '/'.$GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'].'-', '/supplier-research-', '/recherche-fournisseur-', '/busqueda-proveedor-') as $this_url_rewriting_main_expression) {
	if(!empty($_GET['page']) && String::strpos(get_current_url(true, true), 'page=') !== false && is_annonce_module_active() && String::strpos(get_current_url(false, true), $this_url_rewriting_main_expression) !== false) {
		// Numéro de page en ?page=... alors que URL rewriting contient déjà numéro de page
		// String::strpos(get_current_url(false, true), '-' . vn($_GET['page']) . '-') === false permettrait de savoir si la valeur dans l'URL rewriting est différente que valeur en GET, mais peu importe, de toutes façons on redirige
		redirect_and_die(get_current_url(false), true);
	}elseif(!empty($_GET['search']) && String::strpos(get_current_url(true, true), 'search=') !== false && is_annonce_module_active() && String::strpos(get_current_url(false, true), $this_url_rewriting_main_expression) !== false) {
		// Numéro de page en ?search=... alors que URL rewriting contient déjà la recherche
		// String::strpos(get_current_url(false, true), '-' . vn($_GET['search']) . '.html') === false permettrait de savoir si la valeur dans l'URL rewriting est différente que valeur en GET, mais peu importe, de toutes façons on redirige
		redirect_and_die(get_current_url(false), true);
	}
}
define('IN_SEARCH', true);

if (is_annonce_module_active()) {
	if (is_map_module_active()) {
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

$tpl_f = $GLOBALS['tplEngine']->createTemplate('search_form.tpl');
$tpl_f->assign('STR_SEARCH_PRODUCT', $GLOBALS['STR_SEARCH_PRODUCT']);
$tpl_f->assign('action', $GLOBALS['wwwroot']. '/search.php');
$tpl_f->assign('value', $search);
$tpl_f->assign('match', $match);
$tpl_f->assign('search', String::strtoupper($real_search));
$tpl_f->assign('prix_min', vb($_GET['prix_min']));
$tpl_f->assign('prix_max', vb($_GET['prix_max']));
$tpl_f->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
$tpl_f->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
$tpl_f->assign('STR_ENTER_KEY', $GLOBALS['STR_ENTER_KEY']);
$tpl_f->assign('STR_SEARCH_ALL_WORDS', $GLOBALS['STR_SEARCH_ALL_WORDS']);
$tpl_f->assign('STR_SEARCH_ANY_WORDS', $GLOBALS['STR_SEARCH_ANY_WORDS']);
$tpl_f->assign('STR_SEARCH_EXACT_SENTENCE', $GLOBALS['STR_SEARCH_EXACT_SENTENCE']);
$tpl_f->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
$tpl_f->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
$tpl_f->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
$tpl_f->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl_f->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
$tpl_f->assign('is_advanced_search_active', is_advanced_search_active());
$tpl_f->assign('is_annonce_module_active', is_annonce_module_active());
if (is_advanced_search_active()) {
	if (!is_annonce_module_active()) {
		// on construit la liste des catégories
		if(empty($GLOBALS['select_categorie'])) {
			$GLOBALS['parent_categorie'] = vn($_GET["categorie"]); // catégorie sélectionnée
			$GLOBALS['select_categorie'] = '';
			construit_arbo_categorie($GLOBALS['select_categorie'], $GLOBALS['parent_categorie'], 0); // on construit les options du select des catégories
		}
		$tpl_f->assign('select_categorie', $GLOBALS['select_categorie']);
		$tpl_f->assign('STR_CAT_LB', $GLOBALS['STR_CAT_LB']);

		$tpl_f_select_attributes = array();
		// affichage des attributs fixes
		foreach ($search_attribute_tab as $index => $attribute) {
			$tpl_f_select_attributes[] = display_select_attribute($index, $attribute);
		}
		$tpl_f->assign('select_attributes', $tpl_f_select_attributes);
		// affichage des attributs variables
		$tpl_f->assign('custom_attribute', display_custom_attribute(vb($_GET['custom_attribut'])));
	}else {
		$tpl_f_cat_ann_opts = array();
		// affichage de la recherche pour le module d'annonce
		$sql_cat = "SELECT *
			FROM peel_categories_annonces
			ORDER BY nom_" . $_SESSION['session_langue'];
		$query_cat = query($sql_cat);
		while ($cat_list = fetch_assoc($query_cat)) {
			$tpl_f_cat_ann_opts[] = array('value' => $cat_list['id'],
				'issel' => (!empty($_GET['cat_select']) && $_GET['cat_select'] == $cat_list['id']),
				'name' => $cat_list['nom_' . $_SESSION['session_langue']]
				);
		}
		$tpl_f->assign('cat_ann_opts', $tpl_f_cat_ann_opts);
		// Définit le type d'annonce détail,gros, etc.. Cependant il y a deux filtres sur destockplus, d'où le test sur les deux get
		if (!empty($_GET['cat_statut_detail'])) {
			$type_detail = $_GET['cat_statut_detail'];
		} elseif (!empty($_GET['cat_detail'])) {
			$type_detail = $_GET['cat_detail'];
		}
		if (!empty($_GET['cat_statut_detail'])) {
			$type_statut = $_GET['cat_statut_detail'];
		} elseif (!empty($_GET['cat_statut'])) {
			$type_statut = $_GET['cat_statut'];
		}
		$tpl_f->assign('cat_detail', vb($type_detail));
		$tpl_f->assign('cat_statut', vb($type_statut));
		if (count($GLOBALS['lang_names'])>1) {
			$tpl_f->assign('ad_lang_select', get_lang_ads_choose(false));
		}
		$tpl_f->assign('city_zip', vb($_GET['city_zip']));
		$tpl_f->assign('country', get_country_select_options(vb($_GET['country']), null, 'name', false, null, false, null));
		$query_continent = query("SELECT id, name_" . $_SESSION['session_langue'] . " AS name
			FROM peel_continents
			ORDER BY name_".$_SESSION['session_langue']);
		$tpl_continent_inps = array();
		while ($continent = fetch_assoc($query_continent)) {
			$tpl_continent_inps[] = array('value' => $continent['id'],
				'issel' => !empty($_GET['continent']) && is_array($_GET['continent']) && in_array($continent['id'], $_GET['continent']),
				'name' => $continent['name']
				);
		}
		$tpl_f->assign('continent_inputs', $tpl_continent_inps);
		if (is_map_module_active()) {
			if(empty($_SESSION['session_latitude'])){
				$get_position_text = '<a href="javascript:load_position()">'.$GLOBALS['STR_GET_MY_POSITION'].'</a>';
				$get_position_script = getPositionFromUser('document.getElementById("load_position").innerHTML="<span style=\"color:green\">Position OK</span>"; document.getElementById("latitude").value=latitude; document.getElementById("longitude").value=longitude;');
			}else{
				// On connait déjà la position
				$get_position_text = '<span style="color:green">Position OK</span>';
				$get_position_script = '';
			}
			$tpl_f->assign('near_position', sprintf($GLOBALS['STR_NEAR_POSITION_INPUT'], '<input type="text" id="near_position" name="near_position" size="6" value="' . String::str_form_value(vb($_GET['near_position'])) . '" />').' <input type="hidden" id="latitude" name="latitude" value="'.vb($_SESSION['session_latitude']).'" /><input type="hidden" id="longitude" name="longitude" value="'.vb($_SESSION['session_longitude']).'" /> - <span id="load_position">'.$get_position_text.'</span>'. $get_position_script);
		}
		$tpl_f->assign('ads_contain_lot_sizes', $GLOBALS['site_parameters']['ads_contain_lot_sizes']);
		$tpl_f->assign('STR_TYPE', $GLOBALS['STR_TYPE']);
		$tpl_f->assign('STR_MODULE_ANNONCES_AD_CATEGORY', $GLOBALS['STR_MODULE_ANNONCES_AD_CATEGORY']);
		$tpl_f->assign('STR_MODULE_ANNONCES_OFFER_GROS', $GLOBALS['STR_MODULE_ANNONCES_OFFER_GROS']);
		$tpl_f->assign('STR_MODULE_ANNONCES_OFFER_DEMIGROS', $GLOBALS['STR_MODULE_ANNONCES_OFFER_DEMIGROS']);
		$tpl_f->assign('STR_MODULE_ANNONCES_OFFER_DETAIL', $GLOBALS['STR_MODULE_ANNONCES_OFFER_DETAIL']);
		$tpl_f->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
		$tpl_f->assign('STR_MODULE_ANNONCES_ALT_VERIFIED_ADS', $GLOBALS['STR_MODULE_ANNONCES_ALT_VERIFIED_ADS']);
		$tpl_f->assign('STR_MODULE_ANNONCES_NOT_VERIFIED_ADS', $GLOBALS['STR_MODULE_ANNONCES_NOT_VERIFIED_ADS']);
		$tpl_f->assign('STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD', $GLOBALS['STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD']);
	}
}
$output_form = $tpl_f->fetch();

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
$tpl_r->assign('is_annonce_module_active', is_annonce_module_active());
$tpl_r->assign('page', vn($_GET['page']));
$tpl_r->assign('search', $real_search);

if (!is_annonce_module_active()) {
	// recherche dans les produits
	$additional_sql_inner = '';
	$additional_sql_cond_array = array();
	$additional_sql_having = '';
	if (is_advanced_search_active ()) {
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
		if (!empty($_GET['custom_attribut']) && is_array($_GET['custom_attribut'])) {
			foreach($_GET['custom_attribut'] as $this_attribut_id) {
				if (!empty($this_attribut_id)) {
					$attributs_array[$this_attribut_id] = true;
				}
			}
		}
		if (!empty($_GET['custom_nom_attribut']) && is_array($_GET['custom_nom_attribut'])) {
			foreach($_GET['custom_nom_attribut'] as $this_nom_attribut_id) {
				if (!empty($this_nom_attribut_id)) {
					$nom_attributs_array[$this_nom_attribut_id] = true;
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
		$fields[] = 'p.nom_' . $_SESSION['session_langue'];
		$fields[] = 'p.descriptif_' . $_SESSION['session_langue'];
		$fields[] = 'p.description_' . $_SESSION['session_langue'];
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
if (is_annonce_module_active()) {
	// On fait la recherche dans le module d'annonce si il est présent
	$additional_sql_cond_array = array();
	$additional_sql_inner = '';
	$categorie_annonce = '';
	$tpl_r->assign('STR_MODULE_ANNONCES_SEARCH_RESULT_ADS', $GLOBALS['STR_MODULE_ANNONCES_SEARCH_RESULT_ADS']);
	$tpl_r->assign('STR_MODULE_ANNONCES_SEARCH_NO_RESULT_ADS', $GLOBALS['STR_MODULE_ANNONCES_SEARCH_NO_RESULT_ADS']);
	if (!empty($_GET['cat_select'])) {
		$categorie_annonce = intval($_GET['cat_select']);
	}
	$res_affiche_annonces = affiche_annonces($categorie_annonce, get_ad_search_sql($_GET, false), null, 'search', $GLOBALS['site_parameters']['ads_per_page'], 'line', true, null, 4, false, true, false, false, 0, '', '', false);
	if(!empty($GLOBALS['ads_found']) && String::strlen($real_search)>=4) {
		$found_words_array[] = $real_search;
	}
	if(is_user_alerts_module_active()) {
		// Sauvegarde de la recherche
		$res_affiche_annonces .= display_save_search_button($_GET);
	}
	if (vn($_GET['page'])<=1) {
		// On transmet le GET, en étant compatible avec les tableaux en GET (=> ne pas reconstruire chaine à partir de GET)
		$xml_call_args = String::substr(get_current_url(true),String::strlen(get_current_url(false))+1);
		if(!empty($_GET['search']) && strpos($xml_call_args, 'search=')===false) {
			// Gestion des pages avec URL rewriting
			if(!empty($xml_call_args)) {
				$xml_call_args .= '&';
			}
			$xml_call_args .= 'search='.$_GET['search'];
		}
		if(!empty($GLOBALS['ads_found']) && is_map_module_active()) {
			$res_affiche_annonces = getUserMap(null, $xml_call_args, 0, false, false) . $res_affiche_annonces;
		}
	}
	$tpl_r->assign('res_affiche_annonces', $res_affiche_annonces);
}

if (vn($_GET['page'])<=1 && count($terms) > 0) {
	// on ne recherche dans les articles & marques que si l'on a renseigné le champs texte
	// Affichage sur la première page uniquement (pas de multipage)
	// recherche dans les articles
	$tpl_r->assign('are_terms', true);
	$tpl_arts_found = array();
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
		$texte = String::str_shorten($texte, $taille_texte_affiche, '', '...');
		$chapo = String::str_shorten($chapo, $taille_texte_affiche, '', '...');
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
	if ($i != 1) {
		// on réinitialise la valeur de $i pour la suite.
		$i = 1;
	}
	// recherche dans les marques
	$tpl_brands_found = array();
	unset($fields);
	$fields[] = 'm.nom_' . $_SESSION['session_langue'];
	$fields[] = 'm.description_' . $_SESSION['session_langue'];
	$sql = build_sql_marques($terms, $fields, $match);
	$result = query($sql);
	while ($marque = fetch_assoc($result)) {
		$nom = $marque['nom_' . $_SESSION['session_langue']];
		$urlbrand = $GLOBALS['wwwroot'] . '/achat/marque.php?id=' . $marque['id'];
		// on supprime le HTML du contenu
		$description = String::strip_tags(String::html_entity_decode_if_needed($marque['description_' . $_SESSION['session_langue']]));
		// on coupe le texte si trop long
		$description = String::str_shorten($description, $taille_texte_affiche, '', '...');
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
	// On sait quel mot recherché correspond à un mot existant sur une première page de recherche, on l'ajoute dans peel_tag_cloud
	if (vn($_GET['page'])<=1 && !empty($found_words_array) && is_module_tagcloud_active()) {
		$keywords_array = get_keywords_from_text($found_words_array, 5, 20, false, true, null);
		foreach ($keywords_array as $this_keyword) {
			// On ne stocke que les expressions globales, et les mots suffisamment longs pour éviter tous les mots d'articulation
			sql_tagcloud($this_keyword);
		}
	}
	$tpl_r->assign('brands_found', $tpl_brands_found);
}
$result = $tpl_r->fetch();
$tpl = $GLOBALS['tplEngine']->createTemplate('search.tpl');
if (!empty($_GET['type']) && $_GET['type'] == 'error404') {
	$tpl->assign('content', affiche_contenu_html('error404', true));
}
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
		$requete .= ', ' . $value . ' ';
	}
	$requete .= '
		FROM peel_articles a
		INNER JOIN peel_articles_rubriques ar ON ar.article_id = a.id
		INNER JOIN peel_rubriques r ON r.id = ar.rubrique_id AND r.technical_code NOT IN ("other", "iphone_content")
		WHERE a.etat = "1" AND ' . build_terms_clause($terms, $fields, $match_method) . '
		GROUP BY a.id
		ORDER BY a.id DESC
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
		$requete .= ', ' . $value . ' ';
	} //verifier avec ce que j'ai supprimé
	$requete .= '
		FROM peel_marques m
		WHERE m.etat = "1" AND ' . build_terms_clause($terms, $fields, $match_method) . '
		ORDER BY m.id DESC
		LIMIT 100';
	return $requete;
}

?>
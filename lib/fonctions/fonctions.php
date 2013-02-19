<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	|
// +----------------------------------------------------------------------+
// $Id: fonctions.php 35393 2013-02-19 17:59:28Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Création d'un mot de passe crypté
 *
 * @return
 */
function unique_id()
{
	mt_srand(microtime_float() * 1000000);
	return substr(sha256($GLOBALS['site_parameters']['sha256_encoding_salt'] . mt_rand(0, 9999999)), 0, 32);
}

/**
 * Fonction utilisée pour générer un mot aléatoire
 * (sert par exemple pour le renommage des fichiers images, mot de passe utilisateur, ...)
 *
 * @param integer $chrs Fixe le nombre de caractères
 * @return
 */
function MDP($chrs = 8)
{
	$pwd = "";
	mt_srand(microtime_float() * 1000000);
	while (String::strlen($pwd) < $chrs) {
		$chr = chr(mt_rand(0, 255));
		// on évite les 1, i, I, o, O et 0
		if (preg_match("/^[a-hj-km-np-zA-HJ-KM-NP-Z2-9]$/i", $chr)) {
			$pwd = $pwd . $chr;
		}
	}
	return $pwd;
}

/**
 * Génère un token en session qui permettra ensuite de vérifier l'authenticité de la requête de l'utilisateur
 *
 * @param string $name
 * @param boolean $use_existing_token
 * @return
 */
function generate_token($name = 'general', $use_existing_token = true)
{
	if ($use_existing_token && !empty($_SESSION['token_' . $name])) {
		$_SESSION['token_time_' . $name] = time();
	} else {
		srand(microtime_float() * 1000000);
		$_SESSION['token_' . $name] = md5(uniqid(mt_rand()));
		$_SESSION['token_time_' . $name] = time();
	}
	$_SESSION['token_referer_' . $name] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
		$_SESSION['token_referer_' . $name] = str_replace('http://', 'https://', $_SESSION['token_referer_' . $name]);
	}
	return $_SESSION['token_' . $name];
}

/**
 * get_form_token_input()
 *
 * @param string $name
 * @param boolean $use_existing_token
 * @param boolean $return_as_input_form
 * @return
 */
function get_form_token_input($name = 'general', $use_existing_token = true, $return_as_input_form = true)
{
	$token = generate_token($name, $use_existing_token);
	if ($return_as_input_form) {
		return '<input type="hidden" name="token" value="' . String::str_form_value($token) . '" />';
	} else {
		return $token;
	}
}

/**
 * Vérification de la validité d'un token
 * Par défaut, un token est valide 1h, et utilisable 1 seule fois.
 * Les tokens rajoutent de la sécurité face aux CSRF, et par ailleurs empêchent l'utilisateur de valider N fois par erreur un même formulaire
 * Inconvénient si on affecte un nom de token par formulaire : si l'utilisateur ouvre un même formulaire dans plusieurs onglets, seul le formulaire ouvert en dernier est utilisable. Cela évite certains comportement indésirables de spammeurs.
 * => si on veut éviter cela, il faut générer un nom lors de chaque création de formulaire.
 *
 * @param string $name
 * @param mixed $delay_in_minutes Validity in minutes
 * @param mixed $check_referer_if_set_by_server
 * @param mixed $cancel_token
 * @return
 */
function verify_token($name = 'general', $delay_in_minutes = 60, $check_referer_if_set_by_server = true, $cancel_token = true)
{
	if (!empty($_POST['token'])) {
		$user_token = $_POST['token'];
	} elseif (!empty($_GET['token'])) {
		$user_token = $_GET['token'];
	}

	$result = false;
	if (isset($_SESSION['token_' . $name]) && isset($_SESSION['token_time_' . $name]) && !empty($user_token)) {
		if ($_SESSION['token_' . $name] == $user_token && $_SESSION['token_time_' . $name] + $delay_in_minutes * 60 >= time()) {
			if (!$check_referer_if_set_by_server || !isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == $_SESSION['token_referer_' . $name]) {
				$result = true;
			}
		}
	}
	if ($cancel_token) {
		unset($_SESSION['token_' . $name], $_SESSION['token_time_' . $name], $_SESSION['token_referer_' . $name]);
	}
	return $result;
}

/**
 * Récupère la liste des fils d'une catégorie à partir d'une liste ordonnée
 *
 * @param mixed $all_parents_with_ordered_direct_sons_array
 * @param integer $catid
 * @param array $ids_array Liste à compléter
 * @return $ids_array Liste complétée
 */
function get_sons_cat($all_parents_with_ordered_direct_sons_array, $catid, $ids_array = array())
{
	foreach ($all_parents_with_ordered_direct_sons_array[$catid] as $son_catid) {
		$ids_array[] = $son_catid;
		if (!empty($all_parents_with_ordered_direct_sons_array[$son_catid])) {
			$ids_array = get_sons_cat($all_parents_with_ordered_direct_sons_array, $son_catid, $ids_array);
		}
	}
	return $ids_array;
}

/**
 * calcul_nbprod_parcat()
 *
 * @param integer $catid
 * @param mixed $all_parents_with_ordered_direct_sons_array
 * @return
 */
function calcul_nbprod_parcat($catid, $all_parents_with_ordered_direct_sons_array)
{
	$cache_id = 'calcul_nbprod_parcat_' . $catid . '_' . $GLOBALS['site_parameters']['category_count_method'];
	if (!empty($all_parents_with_ordered_direct_sons_array) && !empty($all_parents_with_ordered_direct_sons_array[$catid])) {
		$cache_id .= '_' . md5(serialize($all_parents_with_ordered_direct_sons_array[$catid]));
	}
	$this_cache_object = new Cache($cache_id, array('group' => 'data'));
	if ($this_cache_object->testTime(900, true)) {
		$results_count = $this_cache_object->get();
	} else {
		if ($GLOBALS['site_parameters']['category_count_method'] == 'global' && !empty($all_parents_with_ordered_direct_sons_array) && !empty($all_parents_with_ordered_direct_sons_array[$catid])) {
			// En mode global, on compte le nombre d'annonce des catégories et sous-catégories
			$ids_array = get_sons_cat($all_parents_with_ordered_direct_sons_array, $catid);
		} else {
			// Dans ce cas, on compte uniquement le nombre d'annonces de categories
			$ids_array = array($catid);
		}
		$sql = "SELECT COUNT(*) AS this_count
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON pc.produit_id = p.id
			WHERE pc.categorie_id IN (" . nohtml_real_escape_string(implode(',', $ids_array)) . ") AND p.etat='1'";
		$query = query($sql);
		if ($this_count = fetch_assoc($query)) {
			$results_count = $this_count['this_count'];
		} else {
			$results_count = null;
		}
		$this_cache_object->save($results_count);
	}
	unset($this_cache_object);
	return $results_count;
}

/**
 * calcul_nbrub()
 *
 * @param integer $rub
 * @return
 */
function calcul_nbarti_parrub($rub)
{
	$resCount = query("SELECT COUNT(*) AS this_count
		FROM peel_articles p
		INNER JOIN peel_articles_rubriques pa ON pa.article_id = p.id
		WHERE pa.rubrique_id  = '" . intval($rub) . "' AND p.etat='1'");
	$count = fetch_assoc($resCount);
	return $count['this_count'];
}

/**
 * fprix formatte le prix donné en le convertissant si nécessaire au préalable et en ajoutant éventuellement la mention de la devise
 * Le prix donné est a priori dans la devise de session de l'utilisateur, sauf mention contraire dans $currency_code_or_default
 *
 * @param float $price
 * @param boolean $display_currency If true,
 * @param string $currency_code_or_default If null, then $_SESSION['session_devise']['code'] is used
 * @param boolean $convertion_needed_into_currency
 * @param float $currency_rate
 * @param boolean $display_iso_currency_code
 * @param boolean $format
 * @param string $format_separator
 * @param boolean $add_rdfa_properties 
 * @return
 */
function fprix($price, $display_currency = false, $currency_code_or_default = null, $convertion_needed_into_currency = true, $currency_rate = null, $display_iso_currency_code = false, $format = true, $format_separator = ',', $add_rdfa_properties = false)
{
	static $currency_infos_by_code;
	$prices_precision = 2;
	if (!empty($currency_code_or_default)) {
		if (!isset($currency_infos_by_code[$currency_code_or_default])) {
			// Si on a récupéré une le symbole d'un devise alors on va chercher le taux de conversion associé
			$req = "SELECT code, conversion, symbole, symbole_place
				FROM peel_devises
				WHERE code = '" . nohtml_real_escape_string($currency_code_or_default) . "'";
			$res = query($req);
			$currency_infos_by_code[$currency_code_or_default] = fetch_assoc($res);
		}
		if (!empty($currency_infos_by_code[$currency_code_or_default])) {
			$currency_code = $currency_infos_by_code[$currency_code_or_default]['code'];
			$currency_symbole = String::html_entity_decode(str_replace('&euro;', '€', $currency_infos_by_code[$currency_code_or_default]['symbole']));
			$currency_rate_item = $currency_infos_by_code[$currency_code_or_default]['conversion'];
			$symbole_place = $currency_infos_by_code[$currency_code_or_default]['symbole_place'];
		}
	}
	if (empty($currency_symbole)) {
		// Par défaut ou si on ne recupère aucun symbole de devise alors on utilise le symbole et le taux de conversion de session
		$currency_code = $_SESSION['session_devise']['code'];
		$currency_symbole = $_SESSION['session_devise']['symbole'];
		$currency_rate_item = $_SESSION['session_devise']['conversion'];
		$symbole_place = $_SESSION['session_devise']['symbole_place'];
	}
	if (!empty($currency_rate)) {
		// Si on veut forcer le taux de change, alors on l'applique à la place de celui qu'on a récupéré en BDD ou en session
		$currency_rate_item = $currency_rate;
	}
	if (!empty($convertion_needed_into_currency)) {
		// Par defaut, on effectue une conversion du montant
		$price_displayed = $price * $currency_rate_item;
	} else {
		// Sinon on affiche le prix sans aucune conversion
		$price_displayed = $price;
	}
	if (round($price_displayed, $prices_precision) == 0 && $price_displayed < 0) {
		// On veut éviter que le résultat affiché soit -0,00 => on force à un réel 0
		$price_displayed = 0;
	}
	if ($format) {
		// On formatte le prix pour l'affichage
		// Seul les float sont admis dans la fonction number_format():
		if (is_numeric($price_displayed)) {
			$price_displayed = number_format($price_displayed, $prices_precision, $format_separator, ' ');
		}
		if($add_rdfa_properties) {
			$price_displayed = '<span property="price">'.$price_displayed.'</span>';
		}
		if ($display_iso_currency_code) {
			if($add_rdfa_properties) {
				$currency_code = '<span property="priceCurrency">'.$currency_code.'</span>';
			}
			$price_displayed .= ' ' . $currency_code;
		} elseif ($display_currency) {
			// Si on veut afficher le symbole de la devise (Par défaut, on affiche uniquement le montant)
			if ($symbole_place == 1) {
				$price_displayed .= $GLOBALS['STR_BEFORE_TWO_POINTS'] . $currency_symbole;
			} else {
				$price_displayed = $currency_symbole . ' ' . $price_displayed;
			}
			/*if($add_rdfa_properties) {
				$price_displayed .= ' <span class="hidden" property="priceCurrency">'.$currency_code.'</span>';
			}*/
		}
	}
	return $price_displayed;
}

/**
 * charge_article()
 *
 * @param integer $id
 * @param boolean $show_all_etat_if_admin
 * @return
 */
function charge_article($id, $show_all_etat_if_admin = true)
{
	$qid = query("SELECT
			 a.id
			,a.surtitre_" . $_SESSION['session_langue'] . " AS surtitre
			,a.titre_" . $_SESSION['session_langue'] . " AS titre
			,a.date_insere
			,a.texte_" . $_SESSION['session_langue'] . " AS texte
			,a.chapo_" . $_SESSION['session_langue'] . " AS chapo
			,a.image1
			,a.etat
			,a.on_special
			,ar.rubrique_id
		FROM peel_articles a
		INNER JOIN peel_articles_rubriques ar ON a.id = ar.article_id
		WHERE a.id='" . intval($id) . "' " . ($show_all_etat_if_admin && a_priv("admin_content", false) ? '' : 'AND a.etat = "1"') . "");
	return fetch_assoc($qid);
}

/**
 * Retourne la remise d'un code promotionnel (en % dans le cas d'une remise en pourcentage ou dans le format imposer par fprix pour une remise en Euros)
 *
 * @param float $remise_valeur
 * @param float $remise_percent
 * @param boolean $with_taxes
 * @return
 */
function get_discount_text($remise_valeur, $remise_percent, $with_taxes)
{
	$remise_displayed = array();
	$remise_valeur = floatval($remise_valeur);
	$remise_percent = floatval($remise_percent);
	if (!empty($remise_valeur)) {
		$remise_displayed[] = fprix($remise_valeur, true, $GLOBALS['site_parameters']['code'], false);
	}
	if (!empty($remise_percent)) {
		$remise_displayed[] = sprintf("%0.2f", $remise_percent) . '% ' . ($with_taxes ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']);
	}

	return implode(' - ', $remise_displayed);
}

/**
 * get_tag_analytics()
 *
 * @return
 */
function get_tag_analytics()
{
	if (!empty($GLOBALS['site_parameters']['tag_analytics'])) {
		return $GLOBALS['site_parameters']['tag_analytics'];
	} else {
		return false;
	}
}

/**
 * Détecte si le client est un robot 'autorisé'
 *
 * @param array $ip IP public sous la forme 127000000000
 * @return boolean oui ou non robot
 */
function isSearchBot($ip)
{
	// Pour pouvoir comparer les chaines de caractères avec le résultat de getPublicAndPrivateIP() au format : 127000000001,
	// mettre les IP avec des 0 pour compléter si <100 ou <10 !!
	$good_bots = array('193.218.115.006', '195.101.094.   ', '204.095.098.   ', '209.249.067.1  ', '209.073.164.050', '210.059.144.149',
		'212.127.141.180', '213.073.184.0  ', '216.239.046.0  ', '216.243.113.001', '216.039.048.164', '216.039.048.058',
		'216.039.048.082', '216.039.050.   ', '217.205.060.225', '218.145.025.   ', ' 62.119.021.157', ' 62.212.117.198',
		' 64.241.242.177', ' 64.241.243.065', ' 64.068.082.0  ', ' 64.068.084.0  ', ' 64.068.085.0  ', ' 65.214.036.   ',
		' 65.214.038.010', ' 65.054.188.   ', ' 66.196.072.   ', ' 66.196.072.   ', ' 66.196.090.   ', ' 66.237.060.022');
	foreach ($good_bots as $bot_ip) {
		if (String::strpos($ip, str_replace(array(' ', '.00', '.0'), array('', '.', '.'), $bot_ip)) !== false) {
			return true;
		}
	}
	return false;
}

/**
 * Retourne les modules sous forme de tableau
 *
 * @param mixed $only_active
 * @param mixed $location
 * @param mixed $technical_code
 * @param mixed $force_update_cache_information
 * @return array Liste des modules
 */
function get_modules_array($only_active = false, $location = null, $technical_code = null, $force_update_cache_information = false)
{
	static $modules_array;
	$static_hash = '';
	if ($only_active) {
		$static_hash .= 'only_active';
	}
	$static_hash .= $location . '_' . $technical_code . '_' . vb($GLOBALS['page_columns_count']);
	if (!isset($modules_array[$static_hash]) || $force_update_cache_information) {
		$modules = array();
		$sql = 'SELECT *
			FROM peel_modules
			WHERE ' . ($location == 'header' && vn($GLOBALS['page_columns_count']) == 2 ?'(':'') . '(1' . ($technical_code ? ' AND technical_code="' . nohtml_real_escape_string($technical_code) . '"' : '') . ($location ? ' AND location="' . nohtml_real_escape_string($location) . '" AND technical_code!="ariane"' : '') . ')' . ($location == 'header' && vn($GLOBALS['page_columns_count']) == 2 ? ' OR (technical_code="caddie" AND location="right")' : '') . ($location == 'header' && vn($GLOBALS['page_columns_count']) == 2 ?')':'') . ($only_active ? ' AND etat="1"' : '') . '
			ORDER BY position, id';

		$query = query($sql);
		while ($this_module = fetch_assoc($query)) {
			// Traitement spécifique
			if (vn($GLOBALS['page_columns_count']) == 2 && $this_module['technical_code'] == 'caddie') {
				if ($this_module['location'] == 'right') {
					// On déplace le module de droite vers le haut pour l'afficher quand même
					if (empty($location) || $location == 'header') {
						$this_module['location'] = 'header';
						$this_module['display_mode'] = '';
					} else {
						continue;
					}
				}
			}
			// On prend cet élément éventuellement modifié
			// Si le module est définit à être afficher uniquement sur la home, alors on vérifie si on est sur la page Home, sinon on ne l'affiche pas.
			if (empty($this_module['in_home']) || (!empty($this_module['in_home']) && (defined('IN_HOME') || defined('IN_PEEL_ADMIN')))) {
				$modules[$this_module['id']] = $this_module;
			}
		}
		$modules_array[$static_hash] = $modules;
	}
	// Traitement spécifique
	return $modules_array[$static_hash];
}

/**
 * Récupère le contenu HTML des modules en fonction des contraintes données en paramètre
 * Il est possible d'autoriser la mise en cache de modules, en indiquant la durée de vie du cache dans $allowing_cache_modules_technical_codes en début de fonction
 * Attention : ne mettre en cache que des modules qui ne font que générer du texte pour mettre dans $this_module_output, et rien d'autre
 *
 * @param string $location
 * @param boolean $return_mode
 * @param string $technical_code
 * @param integer $id_categorie
 * @param integer $this_annonce_number
 * @param boolean $return_array_with_raw_information
 * @param array $criterias
 * @return
 */
function get_modules($location, $return_mode = false, $technical_code = null, $id_categorie = null, $this_annonce_number = 0, $return_array_with_raw_information = false, $criterias = null )
{	
	if (empty($criterias)) {
		$criterias = $_GET;
	}
	// Ne pas mettre upsell dans la liste ci-après car un cache est déhjà mis en place à l'intérieur du module
	$allowing_cache_modules_technical_codes = array('annonces' => 900);
	$output = '';
	$output_array = array();
	$modules_array = get_modules_array(true, $location, $technical_code);

	$i = 0;
	foreach ($modules_array as $this_module) {
		$load_module = true;
		$this_block_style = '';
		$this_module_output = '';
		$extra_catalogue_condition = true;
		if (!empty($cat_id)) {
			$extra_catalogue_condition = extra_catalogue_condition();
		}
		if (!empty($allowing_cache_modules_technical_codes[$this_module['technical_code']]) && !a_priv('admin*')) {
			$cache_id = $this_module['technical_code'] . '_' . $_SESSION['session_langue'] . '_' . vn($criterias['catid']);
			$this_module_output_cache_object = new Cache($cache_id, array('group' => 'html_block'));
			if ($this_module_output_cache_object->testTime($allowing_cache_modules_technical_codes[$this_module['technical_code']], true)) {
				$this_module_output = $this_module_output_cache_object->get();
				$load_module = false;
			}
		}
		if ($load_module) {
			if ($this_module['technical_code'] == 'catalogue' && !empty($extra_catalogue_condition)) {
				$this_module_output = affiche_menu_catalogue($this_module['location'], true, true);
			} elseif ($this_module['technical_code'] == 'tagcloud' && is_module_tagcloud_active()) {
				$this_module_output = affiche_tagcloud(true);
			} elseif ($this_module['technical_code'] == 'search') {
				if(!empty($this_module['technical_code'])){
					$this_module_output = affiche_menu_recherche(true, $this_module['location']);
				}
			} elseif ($this_module['technical_code'] == 'guide') {
				$this_module_output = affiche_guide($this_module['location'], true, true);
			} elseif ($this_module['technical_code'] == 'guide_simplified') {
				$this_module_output = affiche_guide($this_module['location'], true, false);
			} elseif ($this_module['technical_code'] == 'caddie') {
				// Le caddie est affiché en mode condensé si dans le header, ou détaillé sinon
				$this_module_output = affiche_mini_caddie($this_module['location'] != 'header', true);
			} elseif ($this_module['technical_code'] == 'account') {
				$this_module_output = affiche_compte(true);
			} elseif ($this_module['technical_code'] == 'best_seller') {
				if (is_best_seller_module_active ()) {
					$this_module_output = affiche_best_seller_produit_colonne(true);
				}
			} elseif ($this_module['technical_code'] == 'brand') {
				// affiche du block marque
				$this_module_output = affiche_brand($this_module['location'], true);
			} elseif ($this_module['technical_code'] == 'last_views') {
				if (is_last_views_module_active ()) {
					$this_module_output = affiche_last_views(true);
				}
			} elseif ($this_module['technical_code'] == 'quick_access') {
				if (function_exists('get_quick_access')) {
					$this_module_output = get_quick_access($this_module['location'], true);
				}
			} elseif ($this_module['technical_code'] == 'news') {
				if (is_rollover_module_active ()) {
					$items_html_array = get_on_rollover_products_html();
					if (vn($GLOBALS['site_parameters']['type_rollover']) == 1) {
						$this_module_output = affiche_menu_deroulant_1('scrollerdiv_' . $this_module['technical_code'], $items_html_array);
					} elseif (vn($GLOBALS['site_parameters']['type_rollover']) == 2) {
						$this_module_output = affiche_menu_deroulant_2('scrollerdiv_' . $this_module['technical_code'], $items_html_array);
					}
				}
			} elseif (String::substr($this_module['technical_code'], 0, String::strlen('advertising')) == 'advertising' && is_module_banner_active()) {
				// Exemple : advertising5 affiche la publicité en position 5
				// Définition du type de page pour ne sélectionner que les bannières adéquates
				if (defined('IN_HOME')) {
					$page_type = 'home_page';
				} elseif ((defined('IN_CATALOGUE') || defined('IN_CATALOGUE_ANNONCE')) && (empty($criterias['page']) || $criterias['page'] === '0' || $criterias['page'] == 1)) {
					$page_type = 'first_page_category';
				} elseif ((defined('IN_CATALOGUE') || defined('IN_CATALOGUE_ANNONCE')) && $criterias['page'] > 1) {
					$page_type = 'other_page_category';
				} elseif (defined('IN_CATALOGUE_ANNONCE_DETAILS')) {
					$page_type = 'ad_page_details';
				} elseif (defined('IN_SEARCH')) {
					$page_type = 'search_engine_page';
				} else {
					$page_type = 'other_page';
				}
				if (!empty($keywords_array)) {
					$sql_cond .= ' AND ' . build_terms_clause($keywords_array, array('keywords'), 2);
				}
				$this_module_output = affiche_banner(String::substr($this_module['technical_code'], strlen('advertising')), true, (isset($criterias['page'])?$criterias['page']:null), $id_categorie, $this_annonce_number, $page_type, (isset($criterias['search'])?explode(' ', $criterias['search']):null), $_SESSION['session_langue'], $return_array_with_raw_information, (isset($criterias['ref'])?$criterias['ref']:null));
			} elseif ($this_module['technical_code'] == 'menu') {
				$this_block_style = ' ';
				foreach ($modules_array as $this_module2) {
					if (!is_peelfr_module_active() && $this_module2['technical_code'] == 'caddie' && $this_module['location'] == 'header') {
						$this_block_style = ' style="width:80%"';
					}
				}
				$this_module_output = get_menu(vb($GLOBALS['main_div_id']));
			} elseif ($this_module['technical_code'] == 'ariane') {
				$this_module_output = affiche_ariane(true);
			} elseif ($this_module['technical_code'] == 'paiement_secu') {
				$this_module_output = get_modules_paiement_secu();
			}elseif ($this_module['technical_code'] == 'newsletter_in_column') {
				// $this_module_output = get_newsletter_in_column();
			} elseif ($this_module['technical_code'] == 'subscribe_newsletter') {
				$this_module_output = get_newsletter_form($this_module['location'], true);
			} elseif ($this_module['technical_code'] == 'contact') {
				$this_module_output = get_contact_sideblock($this_module['location'], true);
			} elseif ($this_module['technical_code'] == 'annonces' && is_annonce_module_active()) {
				$this_module_output = affiche_menu_annonce($this_module['location'], true, true);
			} elseif ($this_module['technical_code'] == 'become_verified' && is_abonnement_module_active()) {
				$this_module_output = get_verified_sideblock_link($this_module['location'], true);
			} elseif ($this_module['technical_code'] == 'upsell' && is_abonnement_module_active()) {
				$this_module_output = getVerifiedAdsList();
			} elseif ($this_module['technical_code'] == 'search_by_list' && is_annonce_module_active()) {
				$this_module_output = get_annonces_in_box('search_by_list', $this_module['location'], true);
				$this_module['sliding_mode'] = false;
			} elseif ($this_module['technical_code'] == 'product_new') {
				if (is_annonce_module_active()) {
					$this_module_output = get_annonces_in_box('last', $this_module['location'], true);
					$this_module['sliding_mode'] = false;
				} else {
					$this_module_output = get_product_new_list($this_module['location'], true);
				}
			} elseif ($this_module['technical_code'] == 'last_forum_posts' && is_module_forum_active()) {
				$this_module_output = getForumLastMessages($_SESSION['session_langue']);
			}
			if (!empty($this_module_output_cache_object)) {
				// Si le module est mis en cache, on sauvegarde son contenu
				$this_module_output_cache_object->save($this_module_output);
			}
		}
		unset($this_module_output_cache_object);

		if (!empty($this_module_output)) {
			if (!empty($return_array_with_raw_information)) {
				$output_array[] = $this_module_output;
			} else {
				if ($this_module['display_mode'] == 'sideblocktitle' && $this_module['location'] != 'header' && $this_module['location'] != 'footer' && $this_module['location'] != 'middle') {
					$output .= affiche_sideblocktitle(vb($this_module['title_' . $_SESSION['session_langue']]), $this_module_output, $this_module['display_mode'] . '_' . $this_module['technical_code'], true);
				} elseif ($this_module['display_mode'] == 'sideblock' && $this_module['location'] != 'header' && $this_module['location'] != 'footer' && $this_module['location'] != 'middle') {
					$output .= affiche_sideblock(vb($this_module['title_' . $_SESSION['session_langue']]), $this_module_output, $this_module['display_mode'] . '_' . $this_module['technical_code'], true);
				} else {
					if (!empty($this_module['sliding_mode'])) {
						$output .= affiche_block($this_module['display_mode'], $this_module['location'], $this_module['technical_code'], vb($this_module['title_' . $_SESSION['session_langue']]), $this_module_output, $this_module['display_mode'] . '_' . $this_module['technical_code'], $this_block_style, true, true);
					} else {
						$output .= '<div class="' . $this_module['display_mode'] . ' ' . $this_module['location'] . '_basicblock ' . $this_module['location'] . '_' . $this_module['technical_code'] . '  ' . $this_module['technical_code'] . '_' . $_SESSION['session_langue'] . '"' . $this_block_style . '>' . $this_module_output . '</div>';
					}
				}
			}
		}
		if ($i % 2 == 0 && $location == 'center_middle_home') {
			$output .= '<p style="clear:both;"></p>';
			// Ne pas incrementé le compteur pour les bannières qui prennent toutes la largeur du contenu
			if ($this_module['display_mode'] != 'banner_up') {
				$i++;
			}
		}
	}

	if ($return_array_with_raw_information) {
		return $output_array;
	} elseif ($return_mode) {
		return $output;
	} elseif (!empty($output)) {
		echo $output;
	} else {
		return false;
	}
}

/**
 * insere_ticket()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_ticket(&$frm)
{
	if (is_webmail_module_active()) {
		save_mail_db($frm);
	}

	if (!empty($GLOBALS['support_sav_client'])) {
		unset($custom_template_tags);
		$custom_template_tags['DATE'] = get_formatted_date(null, 'short', 'long');
		$custom_template_tags['NOM_FAMILLE'] = vb($frm['nom']);
		$custom_template_tags['SOCIETE'] = vb($frm['societe']);
		$custom_template_tags['TELEPHONE'] = vb($frm['telephone']);
		$custom_template_tags['EMAIL'] = vb($frm['email']);
		$custom_template_tags['DISPO'] = vb($frm['dispo']);
		$custom_template_tags['TEXTE'] = vb($frm['texte']);
		$custom_template_tags['SUJET'] = ((!empty($frm['commande_id'])) ? "[" . $GLOBALS['STR_ORDER_NAME'] . " " . $frm['commande_id'] . "] " : "") . vb($frm['sujet']);
		$custom_template_tags['PRENOM'] = vb($frm['prenom']);
		if (empty($_SESSION['session_form_insere_ticket_sent'])) {
			$_SESSION['session_form_insere_ticket_sent'] = 0;
		}
		if ($_SESSION['session_form_insere_ticket_sent'] < 10) {
			// Limitation pour éviter spam : Un utilisateur peut envoyer 10 fois un email de contact par session
			send_email($GLOBALS['support_sav_client'], '', '', 'insere_ticket', $custom_template_tags, 'html', $GLOBALS['support'], true, false, false, vb($frm['email']));
		}
	}
}

/**
 * Renvoie le HTML d'un tag corespondant à l'URL du fichier flash transmis en paramètre
 *
 * @param mixed $url
 * @param integer $width
 * @param integer $height
 * @param integer $mode_transparent
 * @return
 */
function getFlashBannerHTML($url, $width = 680, $height = 250, $mode_transparent = false)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('FlashBannerHTML.tpl');
	$tpl->assign('url', $url);
	$tpl->assign('width', $width);
	$tpl->assign('height', $height);
	$tpl->assign('mode_transparent', $mode_transparent);
	return $tpl->fetch();
}

/**
 * Renvoie le nom d'un pays
 *
 * @param integer $id
 * @return
 */
function get_country_name($id)
{
	$sql = 'SELECT pays_' . $_SESSION['session_langue'] . '
		FROM peel_pays
		WHERE id=' . intval($id);
	$q = query($sql);
	if ($result = fetch_assoc($q)) {
		return String::html_entity_decode_if_needed($result['pays_' . $_SESSION['session_langue']]);
	} else {
		return false;
	}
}

/**
 * Renvoie le nom d'une catégorie de produits
 *
 * @param integer $id
 * @return
 */
function get_category_name($id)
{
	$sql = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS name
		FROM peel_categories
		WHERE id="' . intval($id) . '"';
	$q = query($sql);
	if ($result = fetch_assoc($q)) {
		return String::html_entity_decode_if_needed($result['name']);
	} else {
		return false;
	}
}

/**
 * get_category_tree_and_itself()
 *
 * @param mixed $id_or_ids_array
 * @param string $mode
 * @return
 */
function get_category_tree_and_itself($id_or_ids_array, $mode = 'sons')
{
	static $result_array;
	if (is_array($id_or_ids_array)) {
		$ids_list = implode(',', $id_or_ids_array);
	} else {
		$ids_list = $id_or_ids_array;
	}
	if (empty($result_array[$ids_list])) {
		if (is_array($id_or_ids_array)) {
			$result_array[$ids_list] = $id_or_ids_array;
		} else {
			$result_array[$ids_list][] = $id_or_ids_array;
		}
		if ($mode == 'sons') {
			$select_field = ' c.id';
			$condition_field = ' c.parent_id';
		} elseif ($mode == 'parents') {
			$select_field = ' c.parent_id as id';
			$condition_field = ' c.id';
		} else {
			return false;
		}

		$sql = 'SELECT ' . $select_field . '
			FROM peel_categories c
			WHERE ' . $condition_field . ' IN ("' . str_replace(',', '","', nohtml_real_escape_string($ids_list)) . '")
			ORDER BY c.position';

		$qid = query($sql);
		while ($cat = fetch_assoc($qid)) {
			$result_array[$ids_list] = array_merge($result_array[$ids_list], get_category_tree_and_itself($cat['id'], $mode));
		}
	}
	return $result_array[$ids_list];
}

/**
 * get_country_select_options()
 *
 * @param string $selected_country_name Name of the country preselected
 * @param integer $selected_country_id Id of the country preselected
 * @param string $option_value defaults 'name'  It defines wether the option value has to be the country id or ther country name
 * @param boolean $display_inactive_country
 * @param integer $allowed_zone_id
 * @param boolean $preselect_shop_country_if_none_selected
 * @param string $selected_country_lang
 * @return
 */
function get_country_select_options($selected_country_name = null, $selected_country_id = null, $option_value = 'name', $display_inactive_country = false, $allowed_zone_id = null, $preselect_shop_country_if_none_selected = true, $selected_country_lang = null)
{
	$output = '';
	$sql_condition = '';
	if ($preselect_shop_country_if_none_selected && empty($selected_country_name) && empty($selected_country_id)) {
		$selected_country_id = vn($GLOBALS['site_parameters']['default_country_id']);
	}
	if (empty($selected_country_lang)) {
		$sql_select_add_fields = '';
		$selected_country_lang = $_SESSION['session_langue'];
	} else {
		$sql_select_add_fields = ', pays_' . $selected_country_lang;
	}
	if (!$display_inactive_country) {
		$sql_condition .= ' AND etat = "1"';
	}
	if (!empty($allowed_zone_id)) {
		$sql_condition .= ' AND zone = "' . intval($allowed_zone_id) . '"';
	}
	$sql_pays = 'SELECT id, pays_' . $_SESSION['session_langue'] . ' ' . $sql_select_add_fields . '
		FROM peel_pays
		WHERE 1 ' . $sql_condition . '
		ORDER BY position, pays_' . $_SESSION['session_langue'];

	$res_pays = query($sql_pays);
	$tpl = $GLOBALS['tplEngine']->createTemplate('country_select_options.tpl');
	$tpl_options = array();
	while ($tab_pays = fetch_assoc($res_pays)) {
		if ($option_value == 'name') {
			$value = $tab_pays['pays_' . $selected_country_lang];
		} elseif ($option_value == 'id') {
			$value = $tab_pays['id'];
		}
		$tpl_options[] = array(
			'value' => $value,
			'name' => $tab_pays['pays_' . $_SESSION['session_langue']],
			'issel' => (vb($selected_country_name) == $tab_pays['pays_' . $selected_country_lang] || vb($selected_country_id) == $tab_pays['id'])
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * get_delivery_type_options()
 *
 * @param string $selected_delivery_type_id_or_name Id or name of the type preselected
 * @return
 */
function get_delivery_type_options($selected_delivery_type_id_or_name = null)
{
	$output = '';
	$sql_type = "SELECT id, nom_" . $_SESSION['session_langue'] . "
		FROM peel_types
		WHERE etat = 1 AND (nom_" . $_SESSION['session_langue'] . "!=''".(!empty($selected_delivery_type_id_or_name)?" OR id='" . real_escape_string($selected_delivery_type_id_or_name) . "'":"").")
		ORDER BY position ASC, nom_" . $_SESSION['session_langue'] . " ASC";
	$res_type = query($sql_type);

	$tpl = $GLOBALS['tplEngine']->createTemplate('delivery_type_options.tpl');
	$tpl_options = array();
	while ($tab_type = fetch_assoc($res_type)) {
		$tpl_options[] = array(
			'value' => intval($tab_type['id']),
			'name' => $tab_type['nom_' . $_SESSION['session_langue']],
			'issel' => ($tab_type['id'] == $selected_delivery_type_id_or_name || $tab_type['nom_' . $_SESSION['session_langue']] === $selected_delivery_type_id_or_name)
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * get_delivery_status_options()
 *
 * @param integer $selected_status_id Id of the status preselected
 * @return
 */
function get_delivery_status_options($selected_status_id = null)
{
	$output = '';
	$sql_statut = "SELECT id, nom_" . $_SESSION['session_langue'] . "
		FROM peel_statut_livraison
		ORDER BY position ASC, nom_" . $_SESSION['session_langue'] . " ASC";
	$res_statut = query($sql_statut);

	while ($s = fetch_assoc($res_statut)) {
		$output .= '<option value="' . intval($s['id']) . '" ' . frmvalide($s['id'] == $selected_status_id, ' selected="selected"') . '>' . String::html_entity_decode_if_needed($s['nom_' . $_SESSION['session_langue']]) . '</option>';
	}
	return $output;
}

/**
 * is_delivery_address_necessary_for_delivery_type()
 *
 * @param integer $selected_delivery_type_id Id of the type preselected
 * @return
 */
function is_delivery_address_necessary_for_delivery_type($selected_delivery_type_id = null)
{
	$sql_type = "SELECT without_delivery_address
		FROM peel_types
		WHERE id='" . intval($selected_delivery_type_id) . "'";
	$res_type = query($sql_type);

	if ($type = fetch_assoc($res_type)) {
		return (!$type['without_delivery_address']);
	} else {
		return null;
	}
}

/**
 * get_payment_status_options()
 *
 * @param integer $selected_status_id Id of the status preselected
 * @return
 */
function get_payment_status_options($selected_status_id = null)
{
	$output = '';
	$sql_statut = "SELECT id, nom_" . $_SESSION['session_langue'] . "
		FROM peel_statut_paiement
		ORDER BY position ASC, nom_" . $_SESSION['session_langue'] . " ASC";
	$res_statut = query($sql_statut);

	$tpl = $GLOBALS['tplEngine']->createTemplate('payment_status_options.tpl');
	$tpl_options = array();
	while ($s = fetch_assoc($res_statut)) {
		$tpl_options[] = array(
			'value' => intval($s['id']),
			'name' => $s['nom_' . $_SESSION['session_langue']],
			'issel' => ($s['id'] == $selected_status_id)
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * Traitement du moyen de paiement
 *
 * @param array $frm
 * @return
 */
function set_paiement(&$frm)
{
	if (!empty($frm['payment_technical_code'])) {
		$sql = "SELECT nom_" . $_SESSION['session_langue'] . " as paiement, tarif, tarif_percent, tva
			FROM peel_paiement
			WHERE technical_code = '" . nohtml_real_escape_string($frm['payment_technical_code']) . "'";
		$query = query($sql);
		if ($obj = fetch_object($query)) {
			$frm['tarif_paiement_ht'] = $frm['sub_total_ht'] * ($obj->tarif_percent / 100) + $obj->tarif;
			$frm['tarif_paiement'] = $frm['sub_total'] * ($obj->tarif_percent / 100) + $obj->tarif;
			$frm['tva_tarif_paiement'] = $frm['tarif_paiement'] - $frm['tarif_paiement_ht'];
		}
	} else {
		$frm['tarif_paiement'] = 0;
		$frm['tarif_paiement_ht'] = 0;
		$frm['tva_tarif_paiement'] = 0;
	}
}

/**
 * get_payment_select()
 *
 * @param mixed $selected_payment_technical_code
 * @param boolean $show_selected_even_if_not_available
 * @return
 */
function get_payment_select($selected_payment_technical_code = null, $show_selected_even_if_not_available = false)
{
	$output = '';
	$payment_complement_informations = '';

	if (is_payment_by_product_module_active ()) {
		$res_paiement = select_payment_by_product();
	} else {
		$sql_paiement = 'SELECT p.*
			FROM peel_paiement p
			WHERE 1
			ORDER BY p.position';
		$res_paiement = query($sql_paiement);
	}
	while ($tab_paiement = fetch_assoc($res_paiement)) {
		if((empty($tab_paiement['etat']) || empty($tab_paiement['nom_' . $_SESSION['session_langue']])) && (!$show_selected_even_if_not_available || $tab_paiement['technical_code'] != $selected_payment_technical_code)){
			// On ne prend que les moyens de paiement actifs, ou ceux qui ont pour code technique $selected_payment_technical_code si $show_selected_even_if_not_available = true
			// Dans les autres cas, on passe au suivant
			continue;
		}
		if (($tab_paiement['technical_code'] == 'kwixo' && ($_SESSION['session_caddie']->zone_technical_code != 'france' && $_SESSION['session_caddie']->zone_technical_code != 'DOM')) || $tab_paiement['technical_code'] == 'kwixo_credit' && ($_SESSION['session_caddie']->montant >= 150 && $_SESSION['session_caddie']->montant <= 4000)) {
			// Fianet n'autorise que les paiement en france et DOM
			// Le paiement à crédit FIANET est possible entre 150 et 4000 €
			continue;
		}
		if (($tab_paiement['technical_code'] != 'paypal' || !empty($GLOBALS['site_parameters']['email_paypal'])) && ($tab_paiement['technical_code'] != 'moneybookers' || !empty($GLOBALS['site_parameters']['email_moneybookers']))) {
			if (String::strpos($tab_paiement['technical_code'], 'kwixo') !== false) {
				if ($tab_paiement['technical_code'] == 'kwixo_credit') {
					// Popup popuprnp3x et popuprnp1xrnp défini dans ' . $GLOBALS['wwwroot'] . '/modules/fianet/lib/js/fianet.js
					$payment_complement_informations .= '<a onclick="popuprnp3x();return false;" href="#" title="kwixo3x">';
				} elseif($tab_paiement['technical_code'] == 'kwixo') {
					$payment_complement_informations .= '<a onclick="popuprnp1xrnp();return false;" href="#" title="kwixo1x">';
				}
				if(file_exists($GLOBALS['dirroot'].'/modules/fianet/images/' . $tab_paiement['technical_code'] .'_mini.png')) {
					$payment_complement_informations .= '
			<img src="'.$GLOBALS['wwwroot'].'/modules/fianet/images/' . $tab_paiement['technical_code'] .'_mini.png" alt="'.String::str_form_value($tab_paiement['nom_' . $_SESSION['session_langue']]).'" />
';
				}
				$payment_complement_informations .= $GLOBALS['STR_MORE_DETAILS'] . '</a></td>';
			}
			$tpl = $GLOBALS['tplEngine']->createTemplate('payment_select.tpl');
			$tpl->assign('technical_code', $tab_paiement['technical_code']);
			$tpl->assign('nom', $tab_paiement['nom_' . $_SESSION['session_langue']]);
			$tpl->assign('issel', (vn($selected_payment_technical_code) == $tab_paiement['technical_code'] || num_rows($res_paiement) == 1));
			if ($tab_paiement['tarif'] != 0) {
				$tpl->assign('fprix_tarif', fprix($tab_paiement['tarif'], true));
			}
			if ($tab_paiement['tarif_percent'] != 0) {
				$tpl->assign('tarif_percent', $tab_paiement['tarif_percent']);
			}
			$tpl->assign('isempty_moneybookers_payment_methods', empty($_SESSION['session_commande']['moneybookers_payment_methods']));
			$tpl->assign('moneybookers_payment_methods', vb($_SESSION['session_commande']['moneybookers_payment_methods']));
			$tpl->assign('isempty_email_moneybookers', empty($GLOBALS['site_parameters']['email_moneybookers']));
			if (!empty($payment_complement_informations)) {
				$tpl->assign('payment_complement_informations', $payment_complement_informations);
			}
			$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('STR_TRANSFER', $GLOBALS['STR_TRANSFER']);
			$output .= $tpl->fetch();
		}
	}
	return $output;
}

/**
 * get_vat_select_options()
 *
 * @param mixed $selected_vat
 * @param mixed $approximative_amount_selected
 * @return
 */
function get_vat_select_options($selected_vat = null, $approximative_amount_selected = false)
{
	$output = '';
	$sql_paiement = 'SELECT id, tva
		FROM peel_tva
		ORDER BY tva DESC';
	$res_paiement = query($sql_paiement);
	
	$tpl = $GLOBALS['tplEngine']->createTemplate('vat_select_options.tpl');
	$tpl_options = array();
	while ($tab_paiement = fetch_assoc($res_paiement)) {
		if ($approximative_amount_selected) {
			// Pour éviter problèmes d'arrondis sur la TVA calculée à partir de la BDD, on regarde si elle vaut la valeur dans le select à 1% près
			$is_selected = (abs(floatval($selected_vat) - floatval($tab_paiement['tva'])) * 100 <= abs($tab_paiement['tva']));
		} else {
			$is_selected = (floatval($selected_vat) == floatval($tab_paiement['tva']));
		}
		$tpl_options[] = array(
			'value' => $tab_paiement['tva'],
			'name' => $tab_paiement['tva'],
			'issel' => $is_selected
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * Redirige vers l'URL demandée et arrête le programme
 *
 * @param string $url
 * @param boolean $permanent_redirection
 * @return
 */
function redirect_and_die($url, $permanent_redirection = false)
{
	header("Location: " . $url);
	if ($permanent_redirection) {
		header("HTTP/1.1 301 Moved Permanently");
	}
	header('Connection: close');
	die();
}

/**
 * Cette fonction vérifie si l'utilisateur a les privilèges de $priv.  Sinon, on affiche
 * l'écran informant que les privilèges sont insuffisants et on arrête le traitement.
 * Exemple : Si on demande admin+compta, il faut que l'utilisateur soit admin+compta au minimum
 * Voir aussi les explication de la fonction a_priv()
 *
 * @param string $priv
 * @param boolean $demo_allowed
 * @return
 */
function necessite_priv($priv, $demo_allowed = true)
{
	if (!a_priv($priv, $demo_allowed)) {
		if(String::strpos(get_current_url(true),'chart-data.php')===false){
			$_SESSION['session_redirect_after_login'] = get_current_url(true);
		}
		redirect_and_die($GLOBALS['wwwroot'] . '/membre.php?error=admin_rights');
	}
}

/**
 * Si l'utilisateur n'est pas connecté à un compte, on affiche une page d'identification et arrête le script.
 *
 * @return
 */
function necessite_identification()
{
	if (!est_identifie()) {
		$_SESSION['session_redirect_after_login'] = get_current_url(true);
		redirect_and_die($GLOBALS['wwwroot'] . '/membre.php?error=login_rights');
	}
}

/**
 * On identifie la langue utilisée, et on redirige si cette langue n'est pas activée
 *
 * @return Langue identifiée automatiquement pour l'utilisateur
 */
function get_identified_lang()
{
	if (!empty($_GET['langue']) && String::strlen($_GET['langue']) == 2) {
		$return_lang = String::strtolower(trim($_GET['langue']));
	} elseif (empty($_SESSION['session_langue']) || empty($GLOBALS['get_lang_rewrited_wwwroot'][$_SESSION['session_langue']]) || $GLOBALS['get_lang_rewrited_wwwroot'][$_SESSION['session_langue']] != $GLOBALS['detected_wwwroot']) {
		// Au cas où on doit définir la langue à partir de l'URL, donc si on n'est pas dans une logique de langue par repértoire
		// mais de langue par domaine ou sous-domaine, on prend la première trouvée (celle qui a la variable "position" la plus faible)
		// NB : On veut pouvoir détecter des URL du type http://xxxxx.en.domain.com pour des sous-domaines
		foreach ($GLOBALS['lang_codes'] as $this_lang) {
			if ((!empty($GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]) && $GLOBALS['get_lang_rewrited_wwwroot'][$this_lang] == $GLOBALS['detected_wwwroot']) || (!empty($GLOBALS['get_lang_rewrited_wwwroot']) && !in_array($GLOBALS['detected_wwwroot'], $GLOBALS['get_lang_rewrited_wwwroot']) && substr_count(str_replace(array('http://', 'https://', 'www.'), '', $GLOBALS['detected_wwwroot']), '.') <= 1 + substr_count(str_replace(array('http://', 'https://', 'www.'), '', $GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]), '.') && strpos(str_replace(array('http://', 'https://', 'www.'), '', $GLOBALS['detected_wwwroot']), str_replace(array('http://', 'https://', 'www.'), '', $GLOBALS['get_lang_rewrited_wwwroot'][$this_lang])) !== false)) {
				$return_lang = $this_lang;
				break;
			}
		}
	}
	if (empty($return_lang) && !empty($_SESSION['session_langue'])) {
		// On n'a pas trouvé la langue par défaut proposée (fr) ou on n'a pas trouvé une et une seule langue qui corresponde à l'URL => on force la langue à partir de l'URL
		// La langue de session est applicable dans tous les autres cas
		$return_lang = $_SESSION['session_langue'];
	} elseif (empty($return_lang) && !empty($GLOBALS['lang_codes'])) {
		// Par défaut on prend la langue du navigateur
		$temp = explode(',', vb($_SERVER['HTTP_ACCEPT_LANGUAGE']));
		$return_lang = String::strtolower(substr(trim($temp[0]), 0, 2));
		if(!in_array($return_lang, $GLOBALS['lang_codes'])) {
			// A défaut on prend l'anglais
			$return_lang = 'en';
		}
		if(!in_array($return_lang, $GLOBALS['lang_codes'])) {
			// Si on ne trouve aucune langue, on prendra la première langue du site trouvée par défaut
			$return_lang = $GLOBALS['lang_codes'][0];
		}
	} elseif (empty($return_lang)) {
		$return_lang = null;
	}
	if (!in_array($return_lang, $GLOBALS['lang_codes']) || empty($GLOBALS['lang_etat'][$return_lang]) || !file_exists($GLOBALS['dirroot'] . "/lib/lang/" . $return_lang . ".php")) {
		// The language asked in the URL is not available
		// We redirect to the equivalent page in the default language
		foreach ($GLOBALS['lang_codes'] as $this_new_lang) {
			if ($this_new_lang != $return_lang && !empty($GLOBALS['lang_etat'][$this_new_lang]) && get_current_url() != get_current_url_in_other_language($this_new_lang)) {
				redirect_and_die(get_current_url_in_other_language($this_new_lang));
			}
		}
	}
	return $return_lang;
}

/**
 * Ce module de gestion des URL dans d'autres langues doit être compatible avec l'URL Rewriting si activé
 * il faut partir de REQUEST_URI et non pas de PHP_SELF
 *
 * @param mixed $this_lang
 * @return
 */
function get_current_url_in_other_language($this_lang)
{
	$this_url_lang = $_SERVER['REQUEST_URI'];
	if (!empty($_GET['langue'])) {
		$original_lang = $_GET['langue'];
		$this_url_lang = str_replace(array('&langue=' . $_GET['langue'], '?langue=' . $_GET['langue'], 'langue=' . $_GET['langue']), '', $this_url_lang);
	} elseif (!empty($_SESSION['session_langue'])) {
		$original_lang = $_SESSION['session_langue'];
	} else {
		$original_lang = $this_lang;
	}

	$original_lang = strtolower($original_lang);

	if (!is_module_url_rewriting_active() || count($GLOBALS['langs_array_by_wwwroot'][$GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]]) > 1) {
		// Comme le chemin pour une page dans cete langue n'est pas spécifique, alors on doit préciser la langue quand on veut changer de page
		// Il ne faut pas compter les GET pour savoir si on rajoute ? ou &, car les GET peuvent venir du décodage de l'URL Rewriting => il faut regarder uniquement REQUEST_URI
		if (String::strpos($this_url_lang, '?') === false) {
			$this_url_lang .= '?';
		} else {
			$this_url_lang .= '&';
		}
		$this_url_lang .= 'langue=' . $this_lang;
	}
	if (defined('IN_404_ERROR_PAGE')) {
		// Si on est sur une URL qui n'existe pas, il ne faut pas créer de liens dans d'autres langues vers cette URL
		$this_url_lang = '/';
	}
	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
		$this_url_lang = 'https://' . $_SERVER['HTTP_HOST'] . $this_url_lang;
	} else {
		$this_url_lang = 'http://' . $_SERVER['HTTP_HOST'] . $this_url_lang;
	}
	$this_url_lang = str_replace($GLOBALS['wwwroot'], $GLOBALS['wwwroot_main'], $this_url_lang);
	if (!empty($GLOBALS['get_lang_rewrited_wwwroot'][$original_lang])) {
		$this_url_lang = str_replace($GLOBALS['get_lang_rewrited_wwwroot'][$original_lang], $GLOBALS['wwwroot_main'], $this_url_lang);
	} else {
		$this_url_lang = str_replace($GLOBALS['wwwroot_main'] . '/' . $original_lang, $GLOBALS['wwwroot_main'], $this_url_lang);
	}
	if (defined('USER_SUBDOMAIN') && $GLOBALS['detected_wwwroot'] != $GLOBALS['wwwroot']) {
		// URL du type http://xxxx.domain.com/x.html  => on veut retiter les xxx et les remettre après
		$subdomain_array = explode('.', $_SERVER['HTTP_HOST']);
		$subdomain = $subdomain_array[0];
		$this_url_lang = str_replace(array('http://' . $_SERVER['HTTP_HOST'], 'https://' . $_SERVER['HTTP_HOST']), $GLOBALS['wwwroot_main'], $this_url_lang);
	}
	if (!empty($GLOBALS['get_lang_rewrited_wwwroot'][$original_lang])) {
		$this_url_lang = str_replace($GLOBALS['wwwroot_main'], $GLOBALS['get_lang_rewrited_wwwroot'][$this_lang], $this_url_lang);
	}
	if (!empty($subdomain) && $subdomain != 'www' && $subdomain != $original_lang) {
		// On remet le sous-domaine si nécessaire
		$this_url_lang = str_replace(array('://', 'www.'), array('://' . $subdomain . '.', ''), $this_url_lang);
	}
	return $this_url_lang;
}

/**
 * get_current_url()
 *
 * @param boolean $with_get
 * @param boolean $get_short_url
 * @param string $take_away_get_args_array
 * @return
 */
function get_current_url($with_get = true, $get_short_url = false, $take_away_get_args_array = null)
{
	$url = '';
	if (!$get_short_url) {
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
			$url .= 'https://';
		} else {
			$url .= 'http://';
		}
		$url .= $_SERVER['HTTP_HOST'];
	}
	$url .= $_SERVER['REQUEST_URI'];
	if (!$with_get && String::strpos($url, '?') !== false) {
		$url = String::substr($url, 0, String::strpos($url, '?'));
	} elseif(!empty($take_away_get_args_array)) {
		foreach($take_away_get_args_array as $key) {
			$url = str_replace(urlencode($key).'='.urlencode(vb($_GET[$key])), '', $url);
			$url = str_replace(array('?&', '&&'), array('?','&'), $url);
	}
		if (String::substr($url, - 1) == '?' || String::substr($url, - 1) == '&') {
			$url = String::substr($url, 0, String::strlen($url) - 1);
		}
	}
	return $url;
}


/**
 * get_current_generic_url()
 *
 * @return
 */
function get_current_generic_url()
{
	static $uri_array;
	$params = $_GET;
	$key = md5(serialize($params));
	if (empty($uri_array[$key])) {
		$queryString = array();
		$uri = get_current_url(false);
		$excluded_get[] = 'page';
		$excluded_get[] = 'nombre';
		$excluded_get[] = 'update';
		if (is_module_url_rewriting_active()) {
			// Si le module d'URL Rewriting est activé, ces données GET sont déjà comprises dans les URL
			// et doivent donc être exclues ici
			if (strpos($_SERVER['PHP_SELF'], 'lire/index.php') !== false) {
				$excluded_get[] = 'rubid';
			} elseif (strpos($_SERVER['PHP_SELF'], 'lire/article_details.php') !== false) {
				$excluded_get[] = 'id';
				$excluded_get[] = 'rubid';
			} elseif (strpos($_SERVER['PHP_SELF'], 'achat/index.php') !== false) {
				$excluded_get[] = 'catid';
			} elseif (strpos($_SERVER['PHP_SELF'], 'achat/produit_details.php') !== false) {
				$excluded_get[] = 'id';
				$excluded_get[] = 'catid';
			}
			if (is_annonce_module_active() && !empty($_GET['catid']) && defined('IN_CATALOGUE_ANNONCE')) {
				// Page de catégorie d'annonces
				$excluded_get[] = 'catid';
				$uri = str_replace('-' . String::rawurlencode(vn($_GET['page'])) . '-' . String::rawurlencode(vn($_GET['catid'])) . '.html', '-[PAGE]-' . String::rawurlencode(vn($_GET['catid'])) . '.html', $uri);
			} elseif (is_vitrine_module_active() && get_current_url(false, true) == '/' . vn($_GET['page']) . '.html') {
				// Page de boutique
				$uri = str_replace('/' . String::rawurlencode(vn($_GET['page'])) . '.html', '/[PAGE]' . '.html', $uri);
			} elseif (is_vitrine_module_active() && get_current_url(false, true) == '/') {
				// Page de boutique : accueil
				$uri .= '[PAGE].html';
			} elseif (is_vitrine_module_active() && String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/' . $GLOBALS['STR_MODULE_ANNONCES_URL_LIST_SHOWCASE'] . '-' . String::rawurlencode(vn($_GET['page'])) . '.html')) {
				// Page de liste des vitrines
				$uri = str_replace('-' . String::rawurlencode(vn($_GET['page'])) . '.html', '-[PAGE].html', $uri);
			} elseif (is_vitrine_module_active() && strpos(get_current_url(false, true), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-')===0) {
				// Page de boutique non verified
				$excluded_get[] = 'bt';
				$uri = str_replace('/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-'.String::rawurlencode(vn($_GET['page'])), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'', $uri);
				$uri = str_replace('/'.URL_VITRINE, '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-[PAGE]', $uri);
			} elseif (is_vitrine_module_active() && strpos(get_current_url(false, true), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-'.String::rawurlencode(vn($_GET['page'])) . '-')===0) {
				// Page de boutique non verified
				$excluded_get[] = 'bt';
				$uri = str_replace('/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-'.String::rawurlencode(vn($_GET['page'])), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-[PAGE]', $uri);
			} elseif (is_annonce_module_active() && (String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/' . $GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'] . '-' . String::rawurlencode(vn($_GET['page'])) . '-' . String::rawurlencode(vb($_GET['search'])) . '.html') || String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/' . $GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'] . '-' . String::rawurlencode(vn($_GET['page'])) . '-' . String::rawurlencode(urlencode(vb($_GET['search']))) . '.html'))) {
				$excluded_get[] = 'search';
				// Si l'URL contient un +, pas encodé ou encodé en %2B, il faut le gérer quoiqu'il arrive => on a deux possibilités dans le str_replace
				// La troisième est là pour couvrir des URL du type : /acheter/recherche-fournisseur-54-pc+.html?search=pc+
				// Rappel : dans une URL réécrite, la partie en dehors du GET est gérée du type String::rawurlencode (et + vaut normalement %2B), et la partie GET est gérée par urlencode (et espace vaut +)
				$uri = str_replace(array('-' . String::rawurlencode(vn($_GET['page'])) . '-' . String::rawurlencode(vb($_GET['search'])) . '.html', '-' . vn($_GET['page']) . '-' . vb($_GET['search']) . '.html', '-' . vn($_GET['page']) . '-' . urlencode(vb($_GET['search'])) . '.html'), '-[PAGE]-' . String::rawurlencode(vb($_GET['search'])) . '.html', $uri);
			} elseif (String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/produits/' . String::rawurlencode(vb($_GET['search'])) . '.html')) {
				$excluded_get[] = 'search';
			} elseif (String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/produits/' . String::rawurlencode(vb($_GET['search'])) . '-' . String::rawurlencode(vn($_GET['page'])) . '.html')) {
				$excluded_get[] = 'search';
				$uri = str_replace('-' . String::rawurlencode(vn($_GET['page'])) . '.html', '-[PAGE].html', $uri);
			} elseif (is_annonce_module_active() && !empty($_GET['country']) && strpos(get_current_url(false, true),'-' . String::rawurlencode(vb($_GET['country'])) . '.html') !== false) {
				// Page de liste des vitrines
				$excluded_get[] = 'country';
			}
			// Compatibilité anciennes URL
			$excluded_get[] = 'subdomain';
			$excluded_get[] = 'pageNum_rs1';
		}
		foreach ($params as $key => $value) {
			if (!in_array($key, $excluded_get) && (!empty($value) || $value==='0')) {
				if(is_array($value)){
					foreach($value as $this_value){
						$queryString[] = $key . '[]=' . urlencode($this_value);
					}
				}else{
					$queryString[] = $key . '=' . urlencode($value);
				}
			}
		}
		if (count($queryString) > 0) {
			$uri .= '?' . implode('&', $queryString);
		}
		$uri_array[$key] = $uri;
	}
	return $uri_array[$key];
}
	
/**
 * Renvoie le nom d'une taille
 *
 * @param integer $size_id
 * @return
 */
function get_size_name($size_id)
{
	$sql = 'SELECT nom_' . $_SESSION['session_langue'] . '
		FROM peel_tailles
		WHERE id ="' . intval($size_id) . '"';
	$query = query($sql);

	if ($size = fetch_assoc($query)) {
		$return = $size['nom_' . $_SESSION['session_langue']];
	} else {
		$return = "";
	}

	return $return;
}

/**
 * Renvoie le nom d'une couleur
 *
 * @param integer $color_id
 * @return
 */
function get_color_name($color_id)
{
	$sql = "SELECT c.*
		FROM peel_couleurs c
		WHERE c.id = '" . intval(vn($color_id)) . "'";
	$query = query($sql);

	if ($this_color = fetch_assoc($query)) {
		$couleur = $this_color['nom_' . $_SESSION['session_langue']];
	} else {
		$couleur = "";
	}
	return $couleur;
}

/**
 * On charge les variables de langue, en complétant éventuellement avec la langue de référence
 *
 * @param string $lang
 * @param array $load_default_lang_files_before_main_lang_array
 * @param boolean $general_setup
 * @param boolean $load_modules_files
 * @param boolean $load_general_admin_files
 * @param boolean $exclude_empty_string Si à true, alors on ne tient pas compte des chaines de caractères vides => permet de garder la valeur déjà chargée dans load_default_lang_files_before_main_lang_array, mais c'est un peu plus lent
 * @param boolean $skip_load_files
 * @return
 */
function set_lang_configuration_and_texts($lang, $load_default_lang_files_before_main_lang_array = null, $general_setup = true, $load_modules_files = true, $load_general_admin_files = true, $exclude_empty_string = true, $skip_load_files = false)
{
	if($general_setup) {
		$GLOBALS['wwwroot'] = get_lang_rewrited_wwwroot($lang);
		// Maintenant que wwwroot est défini définitivement, on peut définir les derniers répertoires
		if (!empty($GLOBALS['site_parameters']['admin_force_ssl'])) {
			$GLOBALS['wwwroot_in_admin'] = str_replace('http://', 'https://', $GLOBALS['wwwroot']);
		} else {
			$GLOBALS['wwwroot_in_admin'] = $GLOBALS['wwwroot'];
		}
		$GLOBALS['administrer_url'] = $GLOBALS['wwwroot_in_admin'] . "/" . vb($GLOBALS['site_parameters']['backoffice_directory_name']);
		$GLOBALS['repertoire_css'] = $GLOBALS['wwwroot'] . "/modeles/" . vb($GLOBALS['site_parameters']['template_directory']) . "/css";
		$GLOBALS['repertoire_images'] = $GLOBALS['wwwroot'] . "/modeles/" . vb($GLOBALS['site_parameters']['template_directory']) . "/images";
		$GLOBALS['repertoire_upload'] = $GLOBALS['wwwroot'] . "/upload";
		$GLOBALS['repertoire_mp3'] = $GLOBALS['wwwroot'] . "/mp3";
		$GLOBALS['repertoire_mp3_extrait'] = $GLOBALS['wwwroot'] . "/mp3_extrait";
		// Paramétrage des formats de date pour les fonctions strftime()
		setlocale(LC_TIME, String::strtolower($lang) . '_' . String::strtoupper($lang) . '.UTF8', String::strtolower($lang) . '.UTF8', String::strtolower($lang) . '_' . String::strtoupper($lang), String::strtolower($lang));
		// Déclaration du nom de la boutique
		$GLOBALS['site'] = vb($GLOBALS['site_parameters']['nom_' . $lang]);
	}
	if(!$skip_load_files) {
		if(!empty($load_default_lang_files_before_main_lang_array)){
			$successive_loads = $load_default_lang_files_before_main_lang_array;
		}
		$successive_loads[] = $lang;
		foreach($successive_loads as $this_lang) {
			if($exclude_empty_string && count($successive_loads)>1 && $this_lang != $successive_loads[0]){
				foreach($GLOBALS as $this_global => $this_value) {
					// On ne copie pas GLOBALS simplement car sinon ça fait une copie par référence. Or on veut les valeurs et surtout pas les références
					if($this_value !== '' && substr($this_global, 0, 4) == 'STR_') {
						// On récupère les variables de langue non vides
						$temp_globals[$this_global] = $this_value;
					}
				}
			}
			if($general_setup) {
				if(file_exists($GLOBALS['dirroot'] . "/lib/lang/datetime_" . $this_lang . ".php")) {
					include($GLOBALS['dirroot'] . "/lib/lang/datetime_" . $this_lang . ".php");
				}
				if(file_exists($GLOBALS['dirroot'] . "/lib/lang/" . $this_lang . ".php")) {
					include($GLOBALS['dirroot'] . "/lib/lang/" . $this_lang . ".php");
				}
				if(file_exists($GLOBALS['dirroot'] . "/lib/lang/meta_" . $this_lang . ".php")) {
					include($GLOBALS['dirroot'] . "/lib/lang/meta_" . $this_lang . ".php");
				}
			}
			if($load_general_admin_files) {
				if(file_exists($GLOBALS['dirroot'] . "/lib/lang/admin_" . $this_lang . ".php")) {
					include($GLOBALS['dirroot'] . "/lib/lang/admin_" . $this_lang . ".php");
				}
			}
			if($load_modules_files && !empty($GLOBALS['modules_lang_directory_array'])){
				// Les variables de langue dans les modules sont plus prioritaires que celles de lib/lang/
				// => la surcharge des valeurs par défaut est possible
				foreach($GLOBALS['modules_lang_directory_array'] as $this_directory) {
					if(file_exists($GLOBALS['dirroot'] . $this_directory . $this_lang . ".php")) {
						include($GLOBALS['dirroot'] . $this_directory . $this_lang . ".php");
					}
				}
			}
			if($exclude_empty_string && count($successive_loads)>1 && $this_lang != $successive_loads[0]) {
				foreach($GLOBALS as $this_global => $this_value) {
					// Le test ci-dessous doit être très rapide, car on a plus de mille passages ici à chaque fois
					// Rappel : si un test renvoie false, la suite && ... n'est pas exécuté => commencer tests par le plus discrimant et le plus rapide
					if($this_value === '' && substr($this_global, 0, 4) == 'STR_' && !empty($temp_globals[$this_global]) && $this_global != 'STR_BEFORE_TWO_POINTS') {
						// On récupère les variables de langue non vides
						$GLOBALS[$this_global] = $temp_globals[$this_global];
					}
				}
			}
			// Chargement des variables de langue venant de la BDD
			// On charge d'abord les variables de langue s'appliquant à toutes les langues (lang='') puis celles spécifiques à la langue donnée
			if (!IN_INSTALLATION && empty($GLOBALS['installation_folder_active'])) {
				load_site_parameters($this_lang);
			}
		}
		foreach($GLOBALS as $this_global => $this_value) {
			if(substr($this_global, 0, 4) == 'STR_') {
				if(!empty($GLOBALS['site_parameters']['replace_words_in_lang_files'])) {
					// Remplacement de mots clés par des versions personnalisées pour le site
					foreach($GLOBALS['site_parameters']['replace_words_in_lang_files'] as $replaced=>$new) {
						if(strpos($this_value, $replaced)!==false) {
							$GLOBALS[$this_global] = str_replace($replaced, $new, $this_value);
						}
					}
				}
				// Préparation d'un tableau de variables de langue pour Smarty => facilite intégrations graphiques de pouvoir intégrer facilement les textes directement
				$GLOBALS['LANG'][$this_global] = $this_value;
			}
		}
		// Gestion des ids correspondant à contact commercial et autres
		// A modifier manuellement suivant configuration du site - Par défaut:  choix 5 et 7
		$i = 0;
		while (isset($GLOBALS['STR_USER_ORIGIN_OPTIONS_' . ($i+1)])) {
			$i++;
		}
		$GLOBALS['origin_other_ids'] = array($i - 2, $i);
	}
}

/**
 * On charge les variables de configuration
 *
 * @param string $lang
 * @return
 */
function load_site_parameters($lang = null)
{
	// Initialisation des paramètres de la boutique
	$sql = "SELECT *
		FROM peel_configuration
		WHERE etat='1' AND ";
	if(empty($lang)) {
		$sql .= "lang='' AND technical_code NOT LIKE 'STR_%'";
	} else {
		$sql .= "(lang = '" . real_escape_string($lang) . "' OR lang='') AND etat='1'
			ORDER BY IF(lang='', 0, 1) ASC, technical_code ASC";
	}
	// Chargement des paramètres de configuration (PEEL 7+)
	$query = query($sql);
	while($result = fetch_assoc($query)) {
		// On surcharge les valeurs par défaut définies plus haut dans ce fichier par celles trouvées en base de données
		if($result['type'] == 'boolean'){
			if($result['string'] == 'true'){
				$result['string'] = true;
			} elseif($result['string'] == 'false'){
				$result['string'] = false;
			}
		} elseif($result['type'] == 'array'){
			// Chaine du type : "key" => "value", 'key' => value, ...
			$result['string'] = get_array_from_string($result['string']);
		} elseif($result['type'] == 'integer'){
			$result['string'] = intval($result['string']);
		} elseif($result['type'] == 'float'){
			$result['string'] = floatval($result['string']);
		} elseif($result['type'] == 'string'){
			$result['string'] = str_replace(array('{$GLOBALS[\'repertoire_images\']}', '{$GLOBALS[\'wwwroot\']}', '{$GLOBALS[\'dirroot\']}', ), array(vb($GLOBALS['repertoire_images']), $GLOBALS['wwwroot'], $GLOBALS['dirroot']), $result['string']);
		} else {
			$result['string'] = unserialize($result['string']);
		}

		if(String::substr($result['technical_code'], 0, 4)== 'STR_') {
			// Variable de langue
			$GLOBALS[$result['technical_code']] = $result['string'];
		} else {
			if(String::strlen($result['technical_code'])== 7 && String::substr($result['technical_code'], 0, 5) == 'logo_' && strpos($result['string'], '://') === false && !empty($result['string'])) {
				// Ajout de wwwroot si nécessaire
				if(substr($result['string'], 0, 1) != '/') {
					$result['string'] = $GLOBALS['wwwroot'] . '/' . $result['string'];
				} else {
					$result['string'] = $GLOBALS['wwwroot'] . $result['string'];
				}
			}
			// On surcharge les valeurs par défaut définies plus haut dans ce fichier par celles trouvées en base de données
			$GLOBALS['site_parameters'][$result['technical_code']] = $result['string'];
		}
	}
	$query_devises = query("SELECT pd.devise, pd.conversion, pd.symbole, pd.symbole_place, pd.code
		FROM peel_devises pd
		WHERE pd.id = '".vb($GLOBALS['site_parameters']['devise_defaut'])."'");
	if($result_devises = fetch_assoc($query_devises)) {
		// On ajoute aux valeurs par défaut définies plus haut dans ce fichier par celles trouvées dans peel_devises
		// Si elles existent déjà (ce qui serait inattendu), on n'y touche pas
		foreach($result_devises as $this_key => $this_value) {
			if(!isset($GLOBALS['site_parameters'][$this_key])) {
				$GLOBALS['site_parameters'][$this_key] = $this_value;
			}
		}
	}
}

/**
 * microtime_float()
 *
 * @return
 */
function microtime_float()
{
	return array_sum(explode(' ', microtime()));
}

/**
 * Ajoute les erreurs dans le tableau key -> Nom du champ, valeur -> Texte de l'erreur (optionnel)
 *
 * @param mixed $tab
 * @param mixed $name
 * @param integer $text
 * @return
 */
function FormErrorPush(&$tab, $name, $text = 0)
{
	$tab[$name] = ($text) ? $text : '';
}

/**
 * params_affiche_produits()
 *
 * @param mixed $condition_value1
 * @param mixed $unused
 * @param mixed $type
 * @param mixed $nb_par_page
 * @param string $mode
 * @param integer $reference_id
 * @param mixed $nb_colonnes
 * @param mixed $always_show_multipage_footer
 * @param mixed $additional_sql_inner
 * @param mixed $additional_sql_cond
 * @param mixed $additionnal_sql_having
 * @return
 */
function params_affiche_produits($condition_value1, $unused, $type, $nb_par_page, $mode = 'general', $reference_id = 0, $nb_colonnes, $always_show_multipage_footer = true, $additional_sql_inner = null, $additional_sql_cond = null, $additionnal_sql_having = null)
{
	$sql_cond_array = array();
	$titre = '';
	$limit = '';
	$affiche_filtre = '';
	$sql_inner = '';
	$sup = '';
	$params_list = array();
	if (vn($GLOBALS['site_parameters']['category_order_on_catalog']) == '1' || $type == 'save_cart') {
		if ($nb_colonnes > 2)
			$nb_colonnes--;
	}
	if ($nb_par_page % $nb_colonnes > 0) {
		$nb_par_page = $nb_par_page + ($nb_colonnes - ($nb_par_page % $nb_colonnes));
	}
	if ($type == 'associated_product') {
		$params_list['small_width'] = 160;
		$params_list['small_height'] = 160;
	} else {
		$params_list['small_width'] = vn($GLOBALS['site_parameters']['small_width']);
		$params_list['small_height'] = vn($GLOBALS['site_parameters']['small_height']);
	}
	$params_list['cartridge_product_css_class'] = 'product_per_line_' . $nb_colonnes;
	$params_list['nb_colonnes'] = $nb_colonnes;
	if ($type == 'category' && function_exists('is_special_menu_items') && is_special_menu_items($condition_value1)) {
		$mode = 'line';
		$sup = 'associated_product';
		$params_list['small_width'] = 150;
		$params_list['small_height'] = 125;
		if ($condition_value1 == 1) { // On affiche le module à la carte
			$params_list['qid_carte'] = query('SELECT c.id, c.parent_id, c.nom_' . $_SESSION['session_langue'] . ' AS nom , c.description_' . $_SESSION['session_langue'] . ' AS description , c.image_' . $_SESSION['session_langue'] . ' AS image
				FROM peel_categories c
				WHERE c.etat = "1" AND c.parent_id = "1"
				ORDER BY c.position ASC, nom ASC');

			$params_list['qid_prix_carte'] = query('SELECT MIN(prix) AS prix_cat, tva
				FROM peel_produits p
				INNER JOIN peel_produits_categories pc ON pc.produit_id = p.id
				INNER JOIN peel_categories c ON pc.categorie_id = c.id
				WHERE c.etat = "1" AND pc.categorie_id = "4"');
		}
	}
	$display_multipage_template_name = null;
	if ($type == 'catalogue') {
		$sql_cond_array[] = "p.id_marque='" . intval($condition_value1) . "'";
	} elseif ($type == 'nouveaute') {
		$sql_cond_array[] = "p.on_new='1'";
		$params_list['affiche_filtre'] = affiche_filtre(null, true);
		$titre = $GLOBALS['STR_NOUVEAUTES'];
	} elseif ($type == 'promotion') {
		// Si l'option est activée dans les paramètres de la boutique
		if (vn($GLOBALS['site_parameters']['auto_promo']) == 1) {
			// Si une promotion est appliquée au produit
			$this_sql_cond = "p.promotion>0";
			// Si le module flash est actif
			if (is_flash_sell_module_active() && is_flash_active_on_site()) {
				$this_sql_cond .= " OR p.on_flash='1' AND '" . date('Y-m-d H:i:s', time()) . "' BETWEEN p.flash_start AND p.flash_end";
			}
			// Si le module Promotions par marque est actif
			if (is_marque_promotion_module_active()) {
				$sql_inner .= " LEFT JOIN peel_marques pm ON pm.id = p.id_marque";
				$this_sql_cond .= " OR pm.promotion_percent>0 OR pm.promotion_devises >0 ";
			}
			// Si le module Promotions par catégorie est actif
			if (is_category_promotion_module_active()) {
				$this_sql_cond .= " OR c.promotion_percent>0 OR c.promotion_devises>0";
			}
			$sql_cond_array[] = $this_sql_cond;
		} else {
			$sql_cond_array[] = "p.on_promo='1'";
		}
		$titre = $GLOBALS['STR_PROMOTIONS'];
	} elseif ($type == 'special') {
		$sql_cond_array[] = "p.on_special='1'";
		$titre = $GLOBALS['STR_SPECIAL'];
		$display_multipage_template_name = 'light';
	} elseif ($type == 'suggest') {
		$sql_cond_array[] = "p.prix>='" . nohtml_real_escape_string($condition_value1) . "'";
		$titre = $GLOBALS['STR_OUR_SUGGEST'];
	} elseif ($type == 'top') {
		$sql_cond_array[] = "p.on_top='1'";
		$titre = $GLOBALS['STR_TOP'];
	} elseif ($type == 'category') {
		$params_list['affiche_filtre'] = affiche_filtre($condition_value1, true);
		if (vb($GLOBALS['site_parameters']['category_count_method']) == 'global') {
			$catid_array = get_category_tree_and_itself($condition_value1, 'sons');
		} else {
			$catid_array = array($condition_value1);
		}
		$sql_cond_array[] = "pc.categorie_id IN ('" . implode("','", real_escape_string($catid_array)) . "')";
		$titre = $GLOBALS['STR_LIST_PRODUCT'];
	} elseif ($type == 'flash') {
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d H:i:s', time()) . "' BETWEEN p.flash_start AND p.flash_end";
		$titre = $GLOBALS['STR_FLASH'];
	} elseif ($type == 'check') {
		$sql_cond_array[] = "p.on_check='1'";
		$titre = $GLOBALS['STR_CHEQUE_CADEAU'];
	} elseif ($type == 'associated_product') {
		$infos = array();
		$commande_id_array = array();
		// On vérifie si la case remontée de produit a été cochée et qu'un nombre de produits à afficher a bien été saisi
		$sql = query('SELECT on_ref_produit, nb_ref_produits
			FROM peel_produits
			WHERE id = ' . intval(vn($reference_id)));
		$infos = fetch_assoc($sql);
		if (!empty($infos)) {
			// Récupération des id des commandes dont le produit fait partie
			$sql = 'SELECT commande_id
				FROM peel_commandes_articles
				WHERE produit_id = "' . intval($reference_id) . '"';
			$q = query($sql);
			while ($result = fetch_assoc($q)) {
				$commande_id_array[] = $result['commande_id'];
			}
			// Si la case a bien été cochée et qu'un nombre a été saisi et que le produit affiché a déjà été commandé
			if ($infos['on_ref_produit'] == 1 && $infos['nb_ref_produits'] > 0 && count($commande_id_array) > 0) {
				$sql_inner .= " INNER JOIN peel_commandes_articles pca ON pca.produit_id = p.id";
				$sql_cond_array[] = "pca.commande_id IN ('" . implode("','", nohtml_real_escape_string($commande_id_array)) . "')";
				$sql_cond_array[] = "p.id!=" . intval($reference_id);
				$limit = "LIMIT 0, " . intval($infos['nb_ref_produits']);
			} else { 
				// Dans le cas contraire, on affiche les références produit associées
				$sql_cond_array[] = "pr.produit_id = '" . intval($reference_id) . "'";
				$sql_inner .= " INNER JOIN peel_produits_references pr ON p.id = pr.reference_id";
			}
		}
		$titre = $GLOBALS['STR_ASSOCIATED_PRODUCT'];
	} elseif ($type == 'save_cart') {
		$sql_inner .= " INNER JOIN peel_save_cart sc ON sc.produit_id = p.id ";
		$sql_cond_array[] = "sc.id_utilisateur = '" . intval($condition_value1) . "'";
	}
	if (empty($GLOBALS['site_parameters']['allow_command_product_ongift'])) {
		$sql_cond_array[] = 'p.on_gift = "0"';
	}
	if (!empty($additional_sql_cond)) {
		// On ajoute les conditions supplémentaires
		$sql_cond_array[] = $additional_sql_cond;
	}
	if (!empty($additional_sql_inner)) {
		// On ajoute les jointures supplémentaires
		$sql_inner .= $additional_sql_inner;
	}
	$sql = 'SELECT p.*, c.id AS categorie_id, c.nom_' . $_SESSION['session_langue'] . ' AS categorie ';
	if (($type == 'save_cart')) {
		$sql .= ', sc.id as save_cart_id, sc.couleur_id as saved_couleur_id, sc.taille_id as saved_taille_id, sc.id_attribut as saved_attributs_list, sc.quantite as saved_quantity ';
	}
	$sql .= '
		FROM peel_produits p
		INNER JOIN peel_produits_categories pc ON pc.produit_id = p.id
		INNER JOIN peel_categories c ON pc.categorie_id = c.id
		' . $sql_inner . '
		WHERE p.etat = "1" AND p.nom_' . $_SESSION['session_langue'] . ' != ""';
	if (!empty($sql_cond_array)) {
		$sql .= ' AND (' . implode(') AND (', array_unique($sql_cond_array)) . ')';
	}

	if ($type != 'save_cart') {
		$sql .= ' GROUP BY p.id';
	}
	if (!empty($additionnal_sql_having)) {
		$sql .= ' ' . $additionnal_sql_having;
	}
	$sql .= '
		ORDER BY ';
	if ($type == 'save_cart') {
		$sql .= ' save_cart_id DESC, ';
	}
	$sql .= ' p.`';
	if (isset($_GET['tri'])) {
		if (!in_array($_GET['tri'], array('nom_' . $_SESSION['session_langue'], 'prix'))) {
			$_GET['tri'] = 'nom_' . $_SESSION['session_langue'];
		}
		$sql .= word_real_escape_string($_GET['tri']) . '` ' ;
	} else {
		$sql .= 'position` ' ;
	}
	if (isset($_GET['sort'])) {
		$sql .= word_real_escape_string($_GET['sort']);
	} else {
		$sql .= 'ASC';
	}
	$sql .= ', p.id DESC ' . $limit;
	if ($type == 'special') {
		$Links = new Multipage($sql, 'home', $nb_par_page, 7, 0, $always_show_multipage_footer);
	} elseif ($type == 'associated_product') {
		$Links = new Multipage($sql, 'affiche_produits_reference', '*', 7, 0, $always_show_multipage_footer);
	} else {
		$Links = new Multipage($sql, 'affiche_produits', $nb_par_page, 7, 0, $always_show_multipage_footer, $display_multipage_template_name);
	}
	$params_list['nb_colonnes'] = $nb_colonnes;
	$params_list['Links'] = $Links;
	$params_list['titre'] = $titre;
	$params_list['mode'] = $mode;

	return $params_list;
}

if (!function_exists('ipGet')) {
	/**
	 * Cette fonction permet de retourner l'IP d'un utilisateur qu'on suppose être vraie. Attention, n'utiliser que pour évaluation du spam ou autres logs
	 * En effet, un utilisateur pourrait simuler HTTP_X_FORWARDED_FOR ou HTTP_CLIENT_IP plus facilement que REMOTE_ADDR
	 * ipGet a plus de chance d'être la vraie IP car on dépasse ainsi le REMOTE_ADDR de proxies, mais d'un autre côté on peut se faire berner
	 *
	 * @return
	 */
	function ipGet()
	{
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && isPublicIP($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && isPublicIP($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = 'undefined';
		}
		if (strpos($ip, ',') !== false) {
			$ip_array = explode(',', $ip);
			$ip = $ip_array[0];
		}
		return $ip;
	}
}

if (!function_exists('isPublicIP')) {
	/**
	 * Cette fonction permet de retourner si une adresse IP est publique
	 *
	 * @param mixed $ip_to_test
	 * @return
	 */
	function isPublicIP($ip_to_test)
	{
		if (preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}$/', $ip_to_test)) {
			$ip_as_array = explode('.', $ip_to_test);
			if (count($ip_as_array) == 4 && !empty($ip_as_array[3])) {
				// Référence :  http://www.faqs.org/rfcs/rfc3330.html pour les ip privées en complément à RFC 1918
				$test1 = $ip_as_array[0] != 10 && $ip_as_array[0] != 127; // Test no Loopback
				$test2 = $ip_as_array[0] != 172 || $ip_as_array[1] < 16 || $ip_as_array[1] > 31; // Test no Private-Use Networks
				$test3 = $ip_as_array[0] != 192 || $ip_as_array[1] != 168; // Test no Private-Use Networks
				$test4 = $ip_as_array[0] != 192 || $ip_as_array[1] != 88 || $ip_as_array[2] != 99; // Test no 6to4 Relay Anycast
				$test5 = $ip_as_array[0] != 169 || $ip_as_array[1] != 254; // Test no Link Local
				if ($test1 && $test2 && $test3 && $test4 && $test5) {
					// IP non privée
					return true;
				}
			}
		}
		return false;
	}
}

/**
 * Renvoie si le visiteur est un robot ou non. Cette fonction n'a pas pour vocation à être exhaustive mais à couvrir les cas les plus courants
 *
 * @return
 */
function is_user_bot()
{
	static $result;
	if(!isset($result)){
		// Premier test rapide sur user_agent
		$result = (!empty($_SERVER['HTTP_USER_AGENT']) && (String::strpos(String::strtolower($_SERVER['HTTP_USER_AGENT']), 'mediapartners-google') !== false || String::strpos(String::strtolower($_SERVER['HTTP_USER_AGENT']), 'googlebot') !== false || String::strpos(String::strtolower($_SERVER['HTTP_USER_AGENT']), 'slurp') !== false || String::strpos(String::strtolower($_SERVER['HTTP_USER_AGENT']), 'bingbot') !== false || String::strpos(String::strtolower($_SERVER['HTTP_USER_AGENT']), 'voilabot') !== false));
		if(!$result) {
			// Second test un peu plus lent sur IP
			// Cette liste d'IP n'est pas exhaustive et reprséente des IP de moteurs de recherche
			$ip = vb($_SERVER['REMOTE_ADDR']);
			$good_bots = array('62.119.21.157',
				'62.212.117.198',
				'64.4.8.', '64.62.0.', '64.68.82.', '64.68.84.', '64.68.85.',
				'64.241.242.177', '64.241.243.65',
				'65.54.164.', '65.54.165.', '65.54.188.', '65.55.208.', '65.55.209.', '65.55.213.',
				'65.55.214.', '65.55.215.', '65.55.233.', '65.55.246.',
				'65.214.36.', '65.214.38.10', '65.214.44.',
				'66.154.103.146', '66.196.72.', '66.196.90.',
				'66.237.60.22',
				'66.249.64.', '66.249.65.', '66.249.66.', '66.249.67.', '66.249.68.', '66.249.69.',
				'66.249.70.', '66.249.71.', '66.249.72.', '66.249.73.',
				'72.30.61.', '72.30.110.', '72.30.133.', '72.30.179.', '72.30.215.', '72.30.216.', '72.30.226.',
				'72.30.215.', '72.30.216.', '72.30.226.', '72.30.252.',
				'74.6.19.', '74.6.20.', '74.6.24.', '74.6.25.', '74.6.27.', '74.6.28.',
				'74.6.64.', '74.6.65.', '74.6.66.', '74.6.67.', '74.6.68.', '74.6.69.', '74.6.70.',
				'74.6.71.', '74.6.72.', '74.6.73.', '74.6.74.', '74.6.85.', '74.6.86.', '74.6.87.',
				'172.207.68.',
				'193.218.115.6',
				'195.93.102.', '195.101.94.',
				'204.95.98.',
				'207.46.89.', '207.68.146.', '207.68.157.',
				'209.249.67.1  ', '209.73.164.50',
				'210.59.144.149',
				'212.127.141.180', '212.78.206.',
				'213.73.184.',
				'216.39.48.58', '216.39.48.82', '216.39.48.164', '216.39.50.', '216.239.46.', '216.243.113.1',
				'217.205.60.225',
				'218.145.25.');
			foreach($good_bots as $bot_ip) {
				if (strpos($ip, $bot_ip) === 0) {
					$result = true;
					break;
				}
			}
		}
	}
	return $result;
}

/**
 * get_xml_value()
 *
 * @param mixed $filename
 * @param mixed $filter_string
 * @return
 */
function get_xml_value($filename, $filter_string)
{
	$output = '';
	$forum_output = '';
	$valid_titre = array();

	$data = file_get_contents($filename);
	$parser = xml_parser_create();
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, $data, $values, $tags);
	xml_parser_free($parser);
	// loop through the structures
	$filter_array = explode('|', $filter_string);
	foreach ($tags['title'] as $tag_key => $value_key) {
		$titles_array[$tag_key] = $values[$value_key]['value'];
	}
	foreach ($tags['link'] as $tag_key => $value_key) {
		$links_array[$tag_key] = $values[$value_key]['value'];
	}
	if (!empty($titles_array)) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('xml_value.tpl');
		$tpl_links = array();
		
		$i = 0;
		foreach ($titles_array as $key => $this_title) 		{
			$skip_this = false;
			foreach ($filter_array as $this_filter) {
				if (stripos($this_title, $this_filter) !== false) {
					$skip_this = true;
				}
			}
			if ($key == 0 || $skip_this) {
				// Le premier title est celui de l'ensemble, mais pas du premier message => on saute
				// On saute également les titres qui contiennent des mots à problèmes
				continue;
			}
			$tpl_links[] = array(
				'href' => $links_array[$key],
				'label' => $titles_array[$key]
			);
			$i++;
			if ($i >= 8) {
				break;
			}
		}
		$tpl->assign('links', $tpl_links);
		$output = $tpl->fetch();
	}
	return $output;
}

/**
 * is_tva_intracom_outside_france()
 *
 * @param integer $id_user
 * @return
 */
function is_tva_intracom_outside_france($id_user)
{
	$sql = "SELECT intracom_for_billing, pays
		FROM peel_utilisateurs
		WHERE id_utilisateur = '" . intval($id_user) . "' ";
	$q = query($sql);
	if ($result = fetch_object($q)) {
		if (String::substr(String::strtoupper($result->intracom_for_billing), 0, 2) != 'FR' && !is_numeric(String::substr(String::strtoupper($result->intracom_for_billing), 0, 2)) && String::strlen($result->intracom_for_billing) >= 4 && $result->pays != 1) {
			// Utilisateur avec un n° de TVA intracom, en Europe mais pas en France
			return true;
		}
	}
	return false;
}

/**
 * Filtre une chaine de caractères
 *
 * @param mixed $string
 * @return
 */
function clean_str($string)
{
	$string = preg_replace('/[^a-z0-9]/', "-", utf8_decode(String::convert_accents(String::strtolower($string))));
	$string = preg_replace('/[-]{2,}/', "-", $string);
	$output = urlencode($string);
	return $output;
}

/**
 * Fonction de contrôle pour l'upload de fichier
 *
 * @param mixed $file_infos
 * @param string $file_kind
 * @return
 */
function get_upload_errors_text($file_infos, $file_kind = 'image')
{
	$error = "";
	if (empty($file_kind)) {
		$file_kind = 'any';
	}
	$extension = String::strtolower(pathinfo($file_infos['name'], PATHINFO_EXTENSION));
	if ($file_infos == "none") {
		$error = "Vous n'avez rien uploadé.";
	} elseif (!empty($file_infos['error'])) {
		// Si fichier a essayé d'être téléchargé
		$error = 'Problème lors du transfert du fichier - Veuillez réessayer';
	} elseif ($file_infos['size'] > $GLOBALS['site_parameters']['uploaded_file_max_size']) {
		$error = 'Le fichier envoyé est trop lourd (limite : ' . round($GLOBALS['site_parameters']['uploaded_file_max_size'] / 1024) . ' ko)';
	} elseif (!is_uploaded_file($file_infos['tmp_name'])) {
		$error = 'Problème lors du transfert du fichier - Veuillez réessayer';
	} elseif ($GLOBALS['site_parameters']['check_allowed_types'] && !isset($GLOBALS['site_parameters']['allowed_types'][$file_infos['type']])) {
		// Vérification du type de fichier uploadé
		$error = "Le type de fichier (" . $file_infos['type'] . ") que vous essayez d'uploader n'est pas autorisé, vous ne pouvez télécharger des fichiers que du type :";
	} elseif (!in_array($extension, $GLOBALS['site_parameters']['extensions_valides_'.$file_kind])) {
		// Vérification de l'extension de fichier uploadé
		$error = "Le type de votre fichier n'est pas valide";
	} elseif (!empty($GLOBALS['site_parameters']['extensions_valides_image']) && in_array($extension, $GLOBALS['site_parameters']['extensions_valides_image'])) {
		// Quand on passe ici, Bonne extension d'un fichier qui est une image
		/*
		// SECTION DESACTIVEE car les grandes images sont redimensionnées
		// A ACTIVER SI ON VEUT EMPECHER UPLOAD D'IMAGE NECESSITANT UN REDIMENSIONNEMENT
		list($width, $height, $type, $attr) = getimagesize($file_infos['tmp_name']);
		if ($width > $GLOBALS['site_parameters']['image_max_width']) {
			$error .= "Votre image ne devrait pas être plus large que " . $GLOBALS['site_parameters']['image_max_width'] . " pixels";
		}
		if ($height > $GLOBALS['site_parameters']['image_max_height']) {
			// NE PAS ACTIVER car les grandes images sont redimensionnées
			$error .= "Votre image ne devrait pas être plus haute que " . $GLOBALS['site_parameters']['image_max_height'] . " pixels";
		}*/
	}
	if (!empty($error)) {
		//On a un problème à afficher
		$tpl = $GLOBALS['tplEngine']->createTemplate('upload_errors_text.tpl');
		$tpl->assign('allowed_types', $GLOBALS['site_parameters']['allowed_types']);
		$tpl->assign('msg', $error);
		return $tpl->fetch();
	} else {
		// Pas d'affichage de problème
		return false;
	}
}

/**
 * Fonction d'upload de fichiers
 *
 * @param mixed $field_name
 * @param mixed $rename_file
 * @param mixed $file_kind
 * @param mixed $image_max_width
 * @param mixed $image_max_height
 * @param mixed $path
 * @param mixed $new_file_name_without_extension
 * @return
 */
function upload($field_name, $rename_file = true, $file_kind = null, $image_max_width = null, $image_max_height = null, $path = null, $new_file_name_without_extension = null)
{
	if (is_array($field_name)) {
		// Compatibilité anciennes versions PEEL < 7.0
		if (!empty($field_name)) {
			// $field_name contient $_FILES[$field_name]
			$file_infos = $field_name;
		} else {
			// Rien à télécharger
			return null;
		}
	} elseif (!empty($_REQUEST[$field_name])) {
		// Fichier déjà existant et téléchargé par le passé => on renvoie son nom
		if(strpos($_REQUEST[$field_name], '/cache') === 0) {
			// Fichier temporaire stocké dans le cache, on le déplace dans upload/
			// Si il est déjà déplacé (car on revalide une seconde fois le formulaire, on s'arrange pour que ça marche aussi
			if(file_exists($GLOBALS['dirroot'].$_REQUEST[$field_name])) {
				rename($GLOBALS['dirroot'].$_REQUEST[$field_name], $GLOBALS['uploaddir'].'/'.basename($_REQUEST[$field_name]));
			}
			if(file_exists($GLOBALS['uploaddir'].'/'.basename($_REQUEST[$field_name]))) {
				$_REQUEST[$field_name] = basename($_REQUEST[$field_name]);
			} else {
				return null;
			}
		}
		return $_REQUEST[$field_name];
	} elseif (empty($_FILES[$field_name]['name'])) {
		// Rien à télécharger, ni de fichier existant
		return null;
	} else {
		// On procède à un téléchargement
		$file_infos = $_FILES[$field_name];
		// Teste la validité du téléchargement
		$error = get_upload_errors_text($_FILES[$field_name], $file_kind); 
	}
	if (empty($error) && !empty($file_infos['name'])) {
		// Upload OK
		if (empty($path)) {
			$path = $GLOBALS['uploaddir'] . '/';
		}
		// Extension du fichier téléchargé
		$extension = String::strtolower(pathinfo($file_infos['name'], PATHINFO_EXTENSION));
		if (empty($new_file_name_without_extension)) {
			// Si aucun nom forcé, on en crée un
			$new_file_name_without_extension = format_filename_base(vb($file_infos['name']), $rename_file);
		}
		$the_new_file_name = $new_file_name_without_extension . '.' . $extension;
		$tpl = $GLOBALS['tplEngine']->createTemplate('global_error.tpl');

		if (move_uploaded_file($file_infos['tmp_name'], $path . $the_new_file_name)) {
			// Le fichier est maintenant dans le répertoire des téléchargements
			//  @chmod ($path . '/' . $the_new_file_name, 0644);
			if (!empty($GLOBALS['site_parameters']['extensions_valides_image']) && in_array($extension, $GLOBALS['site_parameters']['extensions_valides_image']) && !empty($image_max_width) && !empty($image_max_height)) {
				// Les fichiers image sont convertis en jpg uniquement si nécessaire - sinon on garde le fichier d'origine
				$the_new_jpg_name = $new_file_name_without_extension . '.jpg';
				// On charge l'image, et si sa taille est supérieure à $destinationW ou $destinationH, ou si elle fait plus de $GLOBALS['site_parameters']['filesize_limit_keep_origin_file'] octets, on doit la régénèrer (sinon on la garde telle qu'elle était)
				// Si on est dans le cas où on la regénère, on la convertit en JPEG à qualité $GLOBALS['site_parameters']['jpeg_quality'] % (par défaut dans PHP c'est 75%, et dans PEEL on utilise 88% par défaut) et on la sauvegarde sous son nouveau nom
				$result = image_resize($path . '/' . $the_new_file_name, $path . '/' . $the_new_jpg_name, $image_max_width, $image_max_height, false, true, $GLOBALS['site_parameters']['filesize_limit_keep_origin_file'], $GLOBALS['site_parameters']['jpeg_quality']);
				if (!empty($result)) {
					return basename($result);
				} else {
					$tpl->assign('message', $GLOBALS['STR_ERROR_DECOD_PICTURE']);
					$error = $tpl->fetch();
				}
			} else {
				return $the_new_file_name;
			}
		} else {
			$tpl->assign('message', $GLOBALS['STR_ERROR_SOMETHING_PICTURE'] . $GLOBALS['uploaddir']);
			$error = $tpl->fetch();
		}
	}
	if (!empty($error)) {
		$GLOBALS['error_text_to_display'] = $error;
		return false;
	}
}

/**
 * A partir d'un nom de fichier, on génère un nouveau nom unique pour éviter d'utiliser un nom déjà existant potentiellement sur le disque dur (par sécurité)
 *
 * @param string $original_name
 * @param boolean $rename_file
 * @return
 */
function format_filename_base($original_name, $rename_file = true) {
	if ($rename_file || (function_exists('is_filtered') && is_filtered($original_name))) {
		$new_file_name_without_extension = strftime("%d%m%y_%H%M%S") . "_PEEL_" . MDP(8);
	} else {
		$extension = String::strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
		$modified_old_name_without_extension = preg_replace('/([^.a-z0-9]+)/i', '-', String::strtolower(String::convert_accents(str_replace(array('%2520', '%20', ';', ',', ' ', '^', '$', '#', '<', '>', '[', ']', '{', '}', '(', ')', "'", '"'), array('-', '-', '-', '-', '-', '-', '-', '', '', '', '', '', '', '', '', '', '', ''), basename($original_name, '.' . $extension)))));
		$new_file_name_without_extension = String::substr(str_replace(array('-----', '----', '---', '--'), array('-', '-', '-', '-'), $modified_old_name_without_extension), 0, 23) . '-' . MDP(8);
	}
	return $new_file_name_without_extension;
}

/**
 * delete_uploaded_file_and_thumbs()
 *
 * @param string $filename
 * @return
 */
function delete_uploaded_file_and_thumbs($filename)
{
	// Protection : ne pas prendre autre chose qu'un nom de fichier
	$filename = str_replace(array('/', '.htaccess'), '', $filename);
	$extension = @pathinfo($filename , PATHINFO_EXTENSION);
	$nom = @basename($filename, '.' . $extension);
	$thumbs_array = @glob($GLOBALS['uploaddir'] . '/' . $nom . "-????.jpg");
	if (!empty($thumbs_array)) {
		foreach ($thumbs_array as $this_thumb) {
			unlink($this_thumb);
		}
	}
	return @unlink($GLOBALS['uploaddir'] . '/' . $filename);
}

/**
 * Envoie les entêtes HTTP puis le contenu pris dans un fichier ou dans l'argument $file_content_given si celui-ci n'est pas vide
 * Remarque : La taille du fichier ne sera envoyée que si le serveur ne compresse pas systématiquement tout le contenu généré par PHP via zlib. Dans le cas contraire, le destinataire n'aura connaissance de la taille du fichier qu'une fois le téléchargement terminé
 *
 * @param string $filename_with_realpath
 * @param boolean $serve_download_with_php
 * @param mixed $file_content_given Le contenu peut être donné dans une variable ce qui désactive la lecture du fichier sur le disque
 * @return
 */

function http_download_and_die($filename_with_realpath, $serve_download_with_php = true, $file_content_given = null)
{
	if (!$serve_download_with_php) {
		// redirection vers le fichier à télécharger
		redirect_and_die(str_replace($GLOBALS['dirroot'], $GLOBALS['wwwroot'], $filename_with_realpath));
	} else {
		switch (strrchr(basename($filename_with_realpath), ".")) {
			case ".gz":
				$type = "application/x-gzip";
				break;
			case ".tgz":
				$type = "application/x-gzip";
				break;
			case ".zip":
				$type = "application/zip";
				break;
			case ".pdf":
				$type = "application/pdf";
				break;
			case ".png":
				$type = "image/png";
				break;
			case ".gif":
				$type = "image/gif";
				break;
			case ".jpg":
				$type = "image/jpeg";
				break;
			case ".txt":
				$type = "text/plain";
				break;
			case ".htm":
				$type = "text/html";
				break;
			case ".html":
				$type = "text/html";
				break;
			case ".mp3":
				$type = "audio/mpeg";
				break;
			case ".ogg":
				$type = "audio/ogg";
				break;
			case ".wav":
				$type = "audio/wav";
				break;
			case ".wma":
				$type = "audio/x-ms-wma";
				break;
			default:
				$type = "application/octet-stream";
				break;
		}
		if (!empty($file_content_given)) {
			$content_length = strlen($file_content_given);
		} else {
			$content_length = filesize($filename_with_realpath);
		}
		header('Content-Description: File Transfer');
		header("Pragma: no-cache");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		// force download dialog
		header('Content-Type: application/force-download');
		header('Content-Type: application/octet-stream', false);
		header('Content-Type: application/download', false);
		header("Content-Type: " . $type . "", false);
		header("Content-disposition: attachment; filename=\"" . rawurlencode(basename($filename_with_realpath)) . "\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . intval($content_length));
		if (!empty($file_content_given)) {
			echo $file_content_given;
		} else {
			readfile($filename_with_realpath);
		}
		die();
	}
}

/**
 * Récupère le nom de domaine du site sans http:// et sans sous-domaine
 *
 * @param boolean $return_only_domains
 * @return
 */
function get_site_domain($return_only_domains = false)
{
	$temp = explode('.', $_SERVER["HTTP_HOST"]);
	if(count($temp)>1 && (count($temp)!=4 || !is_numeric(str_replace('.','',$_SERVER["HTTP_HOST"])))) {
		// Ce n'est pas une IP, ni localhost ou un nom de machine => c'est un domaine avec potentiellement un (sous-)sous-domaine
		return $temp[count($temp)-2].'.'.$temp[count($temp)-1];
	} elseif(!$return_only_domains) {
		return $_SERVER["HTTP_HOST"];
	} else {
		return false;
	}
}

/**
 * get_parents_cat_list()
 *
 * @param int $catid
 * @param array $preselectionne
 * @return
 */
function get_children_cat_list($catid, $preselectionne = array(), $destination = 'categories')
{
	$preselectionne = array();
	if (!in_array($catid, $preselectionne)) {
		$preselectionne[] = $catid;
	}
	if ($destination == 'categories') {
		$table = 'peel_categories';
	} elseif($destination == 'rubriques') {
		$table = 'peel_rubriques';
	} else {
		return false;
	}
	$sql = 'SELECT t.id, t.nom_' . $_SESSION['session_langue'] . ', t.parent_id
		FROM '.$table.' t
		WHERE t.parent_id = "' . intval($catid) . '"
		ORDER BY t.position';
	$qid = query($sql);
	while ($cat = fetch_assoc($qid)) {
		if (is_array($preselectionne) && !in_array($cat['id'], $preselectionne)) {
			$preselectionne[] = $cat['id'];
		}
	}
	return $preselectionne;
}

/**
 * Découpe la chaine recherchée en éléments distincts suivant le mode $match_method
 * Valeurs de $match_method :
 *            1 => Tous les mots
 *            2 => n'importe quel mots
 *            3 => phrase exacte
 *
 * @param string $search
 * @param integer $match_method
 * @return Tableau des termes cherchés
 */
function build_search_terms($search, $match_method)
{
	$terms = array();
	$quote_terms = array();

	/* Si c'est une phrase exacte */
	if (!empty($search)) {
		if ($match_method == 3) {
			$terms[] = $search;
		} else {
			/* Si ce n'est pas une phrase exacte, on découpe la chaine */
			if (strstr($search, '"')) {
				// first pull out all the double quoted strings (e.g. '"iMac DV" or -"iMac DV"')
				preg_match_all('/-*".*?"/', $search, $matched_terms_array);
				$search = preg_replace('/-*".*?"/', '', $search);
				$quote_terms = preg_replace('/"/', '', $matched_terms_array[0]);
			}
			// finally pull out the rest words in the string
			$terms = preg_split("/\s+/", $search, 0, PREG_SPLIT_NO_EMPTY);
		}
	}
	// merge them all together and return
	// array_unique() Takes an input array and returns a new array without duplicate values.
	return array_unique(array_merge($terms, $quote_terms));
}

/**
 * builds the sql statement's where clause
 * this will build the sql based on the given information
 * Valeurs de $match_method :
 *            1 => Tous les mots
 *            2 => n'importe quel mots
 *            3 => phrase exacte
 *
 * @param mixed $terms
 * @param mixed $fields
 * @param integer $match_method
 * @return
 */
function build_terms_clause($terms, $fields, $match_method)
{
	if (empty($terms) || empty($fields)) {
		return ' 1 ';
	}
	if ($match_method == 2) {
		$compare_type = 'OR';
	} else {
		$compare_type = 'AND';
	}
	// construction de la requete
	$conditions_array = array();
	foreach ($terms as $term) {
		// si on a un - devant, alors on ne veut pas du mot
		if (String::substr($term, 0, 1) == '-') {
			// on enleve le '-' qu'on convertir en NOT
			$term = String::substr($term, 1);
			$notmod = ' NOT ';
		} else {
			$notmod = '';
		}
		$this_term_conditions_array = array();
		foreach ($fields as $val) {
			if ($term !== '') {
				$this_term_conditions_array[] = word_real_escape_string($val) . ' ' . word_real_escape_string($notmod) . ' LIKE "%' . nohtml_real_escape_string($term) . '%"';
			} else {
				$this_term_conditions_array[] = word_real_escape_string($val) . ' ' . word_real_escape_string($notmod) . ' LIKE ""';
			}
		}
		$conditions_array[] = '(' . implode(' OR ', $this_term_conditions_array) . ')';
	}
	$where_clause = '( ' . implode(' ' . $compare_type . ' ', $conditions_array) . ' )';
	return $where_clause;
}

/**
 * updateTelContactNotClosed()
 *
 * @return
 */
function updateTelContactNotClosed()
{
	query('UPDATE peel_admins_actions
		SET remarque="Clotûre a posteriori car pas de fin déclarée par l\'administrateur",
			data=DATE_ADD(date, INTERVAL 10 MINUTE)
		WHERE ((action = "PHONE_EMITTED") OR (action = "PHONE_RECEIVED")) AND data="NOT_ENDED_CALL"
		');
}

if (!function_exists('desinscription_newsletter')) {
	/**
	 * desinscription_newsletter()
	 *
	 * @param mixed $mail
	 * @return
	 */
	function desinscription_newsletter($mail)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('desinscription_newsletter.tpl');
		$q = query('SELECT id_utilisateur
			FROM peel_utilisateurs
			WHERE email = "' . nohtml_real_escape_string($mail) . '"');
		if ($data = fetch_assoc($q)) {
			query('UPDATE peel_utilisateurs
				SET newsletter = "0"
				WHERE id_utilisateur=' . intval($data['id_utilisateur']));
			$tpl->assign('msg', $GLOBALS['STR_DESINSCRIPTION_NEWSLETTER_OK']);
		} else {
			$tpl->assign('msg', $GLOBALS['STR_EMAIL_ABSENT']);
		}
		$tpl->assign('STR_DESINSCRIPTION_NEWSLETTER', $GLOBALS['STR_DESINSCRIPTION_NEWSLETTER']);
		return $tpl->fetch();
	}
}

/**
 * Fonction à appeler à la fin de la génération d'une page, afin d'exécuter certaines requêtes SQL qui n'avaient pas besoin d'être exécutées avant, ce qui permet d'accélérer la génération de la page
 *
 * @return
 */
function close_page_generation()
{
	$output = '';
	if (is_module_banner_active()) {
		update_viewed_banners();
	}
	if (is_annonce_module_active()) {
		update_viewed_ads();
	}
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_stock_advanced_module_active()) {
		// Traitement des stocks périmés
		efface_stock_perime();
	}
	// Si on veut forcer à chaque chargement de page la mise à jour des droits des utilisateurs, décommenter la suite
	/*
	if(est_identifie()) {
		$q=query('SELECT priv
			FROM peel_utilisateurs
			WHERE id_utilisateur = "'.intval($_SESSION['session_utilisateur']['id_utilisateur']).'"');
		$result = fetch_assoc($q);
		if($result['priv'] != 'admin') {
			$_SESSION['session_utilisateur']['priv']='util';
		}
	}
	*/
	// Evite de devoir lancer un cron pour optimisations et nettoyages divers
	// On fait des tests séparés pour ne pas tout lancer d'un coup, mais répartir au mieux
	$GLOBALS['contentMail'] = '';
	if (mt_rand(1, 10000) == 5000 && !is_crons_module_active()) {
		optimize_Tables();
	}
	if (mt_rand(1, 10000) == 5000 && !is_crons_module_active()) {
		clean_utilisateur_connexions();
	}
	if (mt_rand(1, 10000) == 5000 && !is_crons_module_active()) {
		clean_admins_actions();
	}
	if (mt_rand(1, 10000) == 5000 && !is_crons_module_active()) {
		clean_Cache();
	}
	if (mt_rand(1, 10000) == 5000 && !is_crons_module_active() && is_captcha_module_active()) {
		clean_security_codes();
	}
	if (defined('PEEL_DEBUG') && PEEL_DEBUG == true) {
		// Affichage des infos de pseudo cron remplies par les fonctions ci-dessus
		$output .= $GLOBALS['contentMail'];
	}
	db_close();
	return $output;
}

/**
 * optimize_Tables()
 *
 * @return
 */
function optimize_Tables()
{
	$GLOBALS['contentMail'] .= 'Optimisation des tables : ';
	$tables = '';
	$q = query('SHOW TABLES FROM ' . $GLOBALS['nom_de_la_base']);
	while ($row = fetch_row($q)) {
		query('OPTIMIZE TABLE `' . $row[0] . '`');
		sleep(2);
	}
	$GLOBALS['contentMail'] .= 'Ok' . "\r\n\r\n";
}

/**
 * Suppression des anciens fichiers de cache
 *
 * @param integer $days_max
 * @return
 */
function clean_Cache($days_max = 15)
{
	$dir = $GLOBALS['dirroot'] . '/' . $GLOBALS['site_parameters']['cache_folder'] . '/';
	$i = 0;
	if ($handle = opendir($dir)) {
		while ($file = readdir($handle)) {
			// On supprime les anciens fichiers de plus de 15 jours
			if ($file != '.' && $file != '..' && $file[0] != '.' && filemtime($dir . $file) < time() - (3600 * 24 * $days_max)) {
				@unlink($dir . $file);
				$i++;
			}
		}
	}
	if(!empty($GLOBALS['contentMail'])) {
		$GLOBALS['contentMail'] .= 'Suppression des fichiers de plus de ' . $days_max . ' jours dans le dossier ' . $dir . '/ : ';
		$GLOBALS['contentMail'] .= 'Ok - ' . $i . ' fichiers supprimés' . "\r\n\r\n";
	}
}

/**
 * Suppression des anciennes infos de connexion utilisateurs
 *
 * @param integer $days_max
 * @return
 */
function clean_utilisateur_connexions($days_max = 730)
{
	$max_reduce_size = false;
	if ($max_reduce_size) {
		$days_max2 = 365 + 30;
		// Archivage peel_utilisateur_connexions sur 1 an pour les utilisateurs qui viennent encore, ou 2 ans pour les autres
		$GLOBALS['contentMail'] .= 'Suppression des infos de connexion utilisateurs de plus de ' . $days_max2 . ' jours dans la BDD : ';
		$q = query('SELECT *, MAX(date) AS max_date, MIN(date) AS min_date
			FROM peel_utilisateur_connexions uc
			GROUP BY uc.user_id
			HAVING max_date>"' . date('Y-m-d H:i:s', time() - $days_max2 * 24 * 3600) . '"  AND min_date<"' . date('Y-m-d H:i:s', time() - $days_max2 * 24 * 3600) . '"');
		$users_ids_array = array();
		while ($result = fetch_assoc($q)) {
			$users_ids_array[] = $result['user_id'];
		}
		if (!empty($users_ids_array)) {
			query('DELETE FROM peel_utilisateur_connexions
				WHERE date<="' . date('Y-m-d H:i:s', time() - $days_max2 * 24 * 3600) . '" AND user_id IN ("' . implode('","', nohtml_real_escape_string($users_ids_array)) . '")');
		}
		$GLOBALS['contentMail'] .= 'Ok - ' . count($users_ids_array) . ' utilisateurs concernés' . "\r\n\r\n";
		sleep(1);
	}
	$GLOBALS['contentMail'] .= 'Suppression des infos de connexion utilisateurs de plus de ' . $days_max . ' jours dans la BDD : ';
	// On supprime tout ce qui dépasse 2 ans
	query('DELETE FROM peel_utilisateur_connexions
		WHERE date<="' . date('Y-m-d H:i:s', time() - $days_max * 24 * 3600) . '"');
	$GLOBALS['contentMail'] .= 'Ok - ' . affected_rows() . ' lignes effacées' . "\r\n\r\n";
}

/**
 * 'Suppression des anciennes actions administrateur
 *
 * @param integer $days_max
 * @return
 */
function clean_admins_actions($days_max = 730)
{
	$GLOBALS['contentMail'] .= 'Suppression des actions administrateur de plus de ' . $days_max . ' jours dans la BDD : ';
	// On supprime tout ce qui dépasse 2 ans
	query('DELETE FROM peel_admins_actions
		WHERE date<="' . date('Y-m-d H:i:s', time() - $days_max * 24 * 3600) . '"');
	$GLOBALS['contentMail'] .= 'Ok - ' . affected_rows() . ' lignes effacées' . "\r\n\r\n";
}

/**
 * formSelect()
 *
 * @param mixed $name
 * @param mixed $tab
 * @param mixed $preselected_value
 * @param integer $addOne
 * @param integer $get
 * @return
 */
function formSelect ($name, $tab, $preselected_value = null, $addOne = 0, $get = 0)
{
	$o = '';
	foreach ($tab as $k => $v) {
		$k = ($addOne) ? $k + 1 : $k;

		if (!empty($preselected_value)) {
			$s = (($preselected_value == $k)) ? (' selected="selected"') : ('');
		} elseif (!$get) {
			$s = (isset($_POST['form_' . $name . '']) && $_POST['form_' . $name . ''] == $k) ? (' selected="selected"') : ('');
		} else {
			$s = (isset($_GET[$name]) && urldecode($_GET['' . $name . '']) == $k) ? ' selected="selected"' : '';
		}
		$o .= '<option value="' . String::str_form_value($k) . '" ' . $s . '>' . $v . '</option>' . "\n";
	}
	return $o;
}

/**
 * getTextEditor()
 *
 * @param mixed $instance_name
 * @param mixed $width
 * @param mixed $height
 * @param mixed $default_text Texte qui doit être édité, qui contient du HTML qui n'est pas encodé sous forme d'entités
 * @param string $default_path
 * @param string $type_html_editor // Permet de forcer le type d'editeur de texte sans passer par la variable Globals
 * @return string HTML généré
 */
function getTextEditor($instance_name, $width, $height, $default_text, $default_path = '../', $type_html_editor = 0)
{
	$output = '';
	if (is_numeric($width)) {
		$width .= 'px';
		$cols = $width / 12;
	} else {
		$cols = 50;
	}
	if (!empty($type_html_editor)) {
		// Editeur choisi en paramètre de la fonction
		$this_html_editor = vn($type_html_editor);
	} else {
		// Editeur sélectionné depuis la configuration du site
		$this_html_editor = vn($GLOBALS['site_parameters']['html_editor']);
	}
	if ($this_html_editor == '1') {
		// Editeur nicEditor
		if(empty($GLOBALS['html_editor_loaded'])) {
			$GLOBALS['html_editor_loaded'] = true;
			$output .= '
<script src="' . $GLOBALS['wwwroot'] . '/lib/nicEditor/nicEdit.js"></script>
';
		}
		$output .= '
<script><!--//--><![CDATA[//><!--
bkLib.onDomLoaded(function() {
	new nicEditor({iconsPath : \'' . $GLOBALS['wwwroot'] . '/lib/nicEditor/nicEditorIcons.gif\',fullPanel : true}).panelInstance(\'' . $instance_name . '\');
});
//--><!]]></script>
<textarea name="' . $instance_name . '" id="' . $instance_name . '" style="width:' . $width . '; height:' . $height . 'px" rows="' . ($height / 12) . '" cols="' . $cols . '">' . String::htmlentities($default_text) . '</textarea>
';
	} elseif ($this_html_editor == '0') {
		$default_text = String::nl2br_if_needed($default_text);
		// Editeur FCKeditor
		include_once($GLOBALS['dirroot'] . "/lib/FCKeditor/fckeditor.php");
		$oFCKeditor = new FCKeditor($instance_name);
		$oFCKeditor->BasePath = $default_path . 'lib/FCKeditor/';
		$oFCKeditor->Value = String::htmlspecialchars_decode($default_text, ENT_QUOTES);
		$oFCKeditor->Height = $height;
		$oFCKeditor->Width = $width;
		$output .= $oFCKeditor->CreateHtml();
	} elseif ($this_html_editor == '3') {
		$default_text = String::nl2br_if_needed($default_text);
		// Editeur FCKeditor
		include_once($GLOBALS['dirroot'] . "/lib/ckeditor/ckeditor.php");
		$config = array('width' => $width, 'height' => $height);
		$CKEditor = new CKEditor();
		$CKEditor->basePath = $default_path . 'lib/ckeditor/';
		$CKEditor->returnOutput = true;
		$output .= $CKEditor->editor($instance_name, String::htmlspecialchars_decode($default_text, ENT_QUOTES), $config);
	} elseif ($this_html_editor == '4') {
		$default_text = String::nl2br_if_needed($default_text);
		// Editeur TinyMCE
		include_once($GLOBALS['dirroot'] . "/lib/ckeditor/ckeditor.php");
		if(empty($GLOBALS['html_editor_loaded'])) {
			$GLOBALS['html_editor_loaded'] = true;
			$css_files = array();
			if(!empty($GLOBALS['site_parameters']['css'])) {
				foreach (explode(',', $GLOBALS['site_parameters']['css']) as $this_css_filename) {
					$css_files[] = $GLOBALS['repertoire_css'] . '/' . trim($this_css_filename); // .'?'.time()
				}
			}
			$output .= '
<script src="' . $GLOBALS['wwwroot'] . '/lib/tiny_mce/jquery.tinymce.js"></script>
<script><!--//--><![CDATA[//><!--
(function($) {
   $(document).ready(function() {
		$("textarea.tinymce").tinymce({
			// Location of TinyMCE script
			script_url : "' . $GLOBALS['wwwroot'] . '/lib/tiny_mce/tiny_mce.js",

			// General options
			theme : "advanced",
			plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "'.implode(',', $css_files).'",
		})
	});
})(jQuery);
//--><!]]></script>
';
		}
		$output .= '
			<textarea class="tinymce" name="' . $instance_name . '" id="' . $instance_name . '" style="width:' . $width . 'px; height:' . $height . 'px" rows="' . ($height / 12) . '" cols="' . ($width / 12) . '">' . String::htmlentities($default_text) . '</textarea>
';
	} else {
		// Champ textarea de base
		$output .= '
			<textarea name="' . $instance_name . '" id="' . $instance_name . '" style="width:' . $width . 'px; height:' . $height . 'px" rows="' . ($height / 12) . '" cols="' . ($width / 12) . '">' . String::htmlentities($default_text) . '</textarea>
';
	}
	return $output;
}

/**
 * Ajoute la zone HTML dans la table peel_configuration
 *
 * @param array $frm Array with all fields data
 * @return
 */
function set_configuration_variable($frm, $update_if_technical_code_exists = false)
{
	if(!isset($frm['etat'])) {
		$frm['etat'] = 1;
	}
	if(!isset($frm['type'])) {
		$frm['type'] = 'string';
	}
	if($update_if_technical_code_exists && !empty($frm['technical_code'])) {
		$qid = query("SELECT id
			FROM peel_configuration
			WHERE technical_code = '" . real_escape_string($frm['technical_code']) . "'");
		$select = fetch_assoc($qid);
		if ($select) {
			update_configuration_variable($frm['technical_code'], $frm);
			// Elément déjà existant, qu'on a mis à jour
			return true;
		}
	}
	$sql = "INSERT INTO peel_configuration (etat, technical_code, type, string, last_update, origin, lang, `explain`)
		VALUES ('" . intval($frm['etat']) . "', '" . nohtml_real_escape_string($frm['technical_code']) . "', '" . real_escape_string($frm['type']) . "', '" . real_escape_string(vb($frm['string'])) . "', '" . date('Y-m-d H:i:s', time()) . "', '" . nohtml_real_escape_string(vb($frm['origin'])) . "', '" . nohtml_real_escape_string(vb($frm['lang'])) . "', '" . nohtml_real_escape_string(vb($frm['explain'])) . "')";
	// MAJ pour la page en cours de génération
	$GLOBALS['site_parameters'][$frm['technical_code']] = vb($frm['string']);
	return query($sql);
}

/**
 * update_configuration_variable()
 *
 * @param integer $id_or_technical_code
 * @param array $frm Array with all fields data
 * @return
 */
function update_configuration_variable($id_or_technical_code, $frm)
{
	$sql = "UPDATE peel_configuration
		SET etat = '" . intval($frm['etat']) . "'
			, technical_code = '" . nohtml_real_escape_string($frm['technical_code']) . "'
			".(isset($frm['type'])?", type = '" . nohtml_real_escape_string($frm['type']) . "'":"")."
			".(isset($frm['string'])?", string = '" . real_escape_string($frm['string']) . "'":"")."
			, last_update = '" . date('Y-m-d H:i:s', time()) . "'
			".(isset($frm['origin'])?", origin = '" . nohtml_real_escape_string($frm['origin']) . "'":"")."
			".(isset($frm['lang'])?", lang = '" . nohtml_real_escape_string($frm['lang']) . "'":"")."
			".(isset($frm['explain'])?", `explain` = '" . nohtml_real_escape_string($frm['explain']) . "'":"")."
		WHERE ";
	if(is_numeric($id_or_technical_code)) {
		$sql .= "id = '" . intval($id_or_technical_code) . "'";
	} else {
		$sql .= "technical_code = '" . real_escape_string($id_or_technical_code) . "'";
	}
	// MAJ pour la page en cours de génération
	$GLOBALS['site_parameters'][$frm['technical_code']] = vb($frm['string']);
	return query($sql);
}

/**
 * get_minified_src()
 *
 * @param array $files_array
 * @param string $files_type
 * @param integer $lifetime
 * @return
 */
function get_minified_src($files_array, $files_type = 'css', $lifetime = 3600) {
	if($files_type == 'js') {
		// Pour des raisons de compatibilité, on n'applique pas de minified sur les fichiers contenant ces chaines de caractères
		$excluded_files = array('jquery', 'prototype.js', 'controls.js', 'effects.js');
	}
	foreach($files_array as $this_key => $this_file) {
		unset($skip);
		if(!empty($excluded_files)) {
			foreach($excluded_files as $this_excluded_file) {
				if(strpos($this_file, $this_excluded_file) !==false) {
					$skip = true;
				}
			}
		}
		if(empty($skip)) {
			$files_to_minify_array[] = $this_file;
			unset($files_array[$this_key]);
		}
	}
	$cache_id = md5(implode(',', $files_array));
	$file_name = $files_type . '_minified_' . substr($cache_id, 0, 8).'.'.$files_type;
	$minified_doc_root = $GLOBALS['dirroot'] . "/cache/";
	$file_path = $minified_doc_root . $file_name;
	if (file_exists($file_path) === false || (($filemtime = @filemtime($file_path)) < time() - $lifetime) || (!empty($_GET['update']) && $_GET['update'] == 1)) {
		if(!empty($_GET['update']) && $_GET['update'] == 1) {
			$generate = true;
		} elseif(!empty($filemtime)) {
			foreach($files_to_minify_array as $this_key => $this_file) {
				// On regarde les fichiers à fusionner pour voir si ils ont changé depuis la création du cache
				$this_mtime = @filemtime(str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], $this_file));
				if(empty($this_mtime) && !file_exists(str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], $this_file))) {
					// Le fichier n'existe pas, on ne tient donc pas compte de ce fichier
					unset($files_to_minify_array[$this_key]);
				}
				if($this_mtime < $filemtime) {
					// Fichier minified pas à jour
					$generate = true;
				}
			}
		} else {
			// Fichier absent
			$generate = true;
		}
		if(!empty($generate)) {
			$output = '';
			if($files_type == 'css') {
				require_once($GLOBALS['dirroot'] . '/lib/class/Minify_CSS.php');
			}
			foreach($files_to_minify_array as $this_key=> $this_file) {
				if($files_type == 'css') {
					$symlinks = array();
					$docroot = $GLOBALS['dirroot'];
					if(String::strlen($GLOBALS['apparent_folder'])>1) {
						$docroot = substr($docroot, 0, String::strlen($docroot) - String::strlen($GLOBALS['apparent_folder']) + 1);
					}
					$options = array('currentDir' => str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], dirname($this_file)), 'docRoot' => $docroot, 'symlinks' => $symlinks);
					$output .= "\n\n\n".Minify_CSS::minify(file_get_contents(str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], $this_file)), $options);
				} elseif($files_type == 'js') {
					$output .= "\n\n\n".file_get_contents(str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], $this_file));
				} else {
					$output .= "\n\n\n".file_get_contents(str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], $this_file));
				}
			}
			if(!empty($output)) {
				if($files_type == 'js') {
					/* remove comments  : bug sur apache Windows => désactivé */
					//$output = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $output);
					/* remove tabs, spaces, newlines, etc. */
					$output = str_replace(array('    ','   ','  '), ' ', $output);
					/* remove other spaces before/after ) */
					$output = preg_replace(array('(( )+\))','(\)( )+)'), ')', $output);
				}
				$fp = String::fopen_utf8($file_path, 'wb');
				@flock($fp, LOCK_EX);
				// On utilise strlen et non pas String::strlen car on veut le nombre d'octets et non pas de caractères
				$write_result = fwrite($fp, $output, strlen($output));
				@flock($fp, LOCK_UN);
				fclose($fp);
			}
			if(!$write_result) {
				return false;
			}
		} else {
			// On valide le fichier pour une nouvelle période de durée $lifetime
			touch($file_path);
		}
	}
	$files_array[] = str_replace($GLOBALS['dirroot'], $GLOBALS['wwwroot'], $file_path);
	return $files_array;
}

?>
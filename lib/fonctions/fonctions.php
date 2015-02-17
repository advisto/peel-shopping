<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	|
// +----------------------------------------------------------------------+
// $Id: fonctions.php 44077 2015-02-17 10:20:38Z sdelaporte $
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
	$cache_id = 'calcul_nbprod_parcat_' . $catid . '_' . $GLOBALS['site_parameters']['category_count_method'] . '_' . $GLOBALS['site_id'];
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
			WHERE pc.categorie_id IN (" . nohtml_real_escape_string(implode(',', $ids_array)) . ") AND p.etat='1' AND " . get_filter_site_cond('produits', 'p') . "";
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
		WHERE pa.rubrique_id  = '" . intval($rub) . "' AND p.etat='1' AND " . get_filter_site_cond('articles', 'p') . "");
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
 * @param boolean $round_even_if_no_format 
 * @return
 */
function fprix($price, $display_currency = false, $currency_code_or_default = null, $convertion_needed_into_currency = true, $currency_rate = null, $display_iso_currency_code = false, $format = true, $format_separator = ',', $add_rdfa_properties = false, $round_even_if_no_format = false)
{
	static $currency_infos_by_code;
	if(!empty($GLOBALS['site_parameters']['price_hide_if_not_loggued']) && !defined('IN_IPN') && (!est_identifie() || (!a_priv('util') && !a_priv('admin*') && !a_priv('reve')))) {
		return '-';
	}
	if(isset($GLOBALS['site_parameters']['prices_precision'])) {
		$prices_precision = $GLOBALS['site_parameters']['prices_precision'];
	} else {
		$prices_precision = 2;
	}
	if (!empty($currency_code_or_default)) {
		if (!isset($currency_infos_by_code[$currency_code_or_default])) {
			// Si on a demandé le prix dans le code ISO d'une devise, alors on va chercher le taux de conversion associé
			$req = "SELECT code, conversion, symbole, symbole_place
				FROM peel_devises
				WHERE code = '" . nohtml_real_escape_string($currency_code_or_default) . "' AND " . get_filter_site_cond('devises') . "";
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
		// Par défaut, on effectue une conversion du montant
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
		// Seuls les float sont admis dans la fonction number_format():
		if (is_numeric($price_displayed)) {
			if(!empty($GLOBALS['site_parameters']['prices_show_rounded_if_possible']) && round($price_displayed) == round($price_displayed, $prices_precision)) {
				$prices_precision = 0;
			}
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
	} elseif($round_even_if_no_format) {
		$price_displayed = round($price_displayed, $prices_precision);
	}
	return $price_displayed;
}

/**
 * Récupère le taux de change avec l'euro d'une devise à partir de son code à 3 lettres
 *
 * @param string $currency Code de la devise à 3 lettres
 * @return array tableau sous la forme [nom] => devise
 * @access public
 */
function get_currency_rate($currency)
{
	if (!empty($currency) && !is_numeric($currency)) {
		$_SESSION['session_curr']['EUR'] = 1;
		$_SESSION['session_curr']['FRF'] = 6.55957;
		if (isset($_SESSION['session_curr'][$currency]) && is_numeric($_SESSION['session_curr'][$currency])) {
			$rate = $_SESSION['session_curr'][$currency];
		} else {
			$sql_query = "
				SELECT `conversion`
				FROM `peel_devises`
				WHERE `code`='" . nohtml_real_escape_string($currency) . "' AND " . get_filter_site_cond('devises') . "
				LIMIT 1";
			// echo $sql_query;
			query($sql_query);
			while ($result = fetch_object()) {
				$rate = $result->conversion;
				$_SESSION['session_curr'][$currency] = $rate;
			}
			if (count($_SESSION['session_curr']) > 30) {
				// On retire le premier élément du tableau si on a atteint la limite de taille du tableau
				// NB : current() ne peut focntionner car il ne retourne pas de référence mais une copie
				unset($_SESSION['session_curr'][key($_SESSION['session_curr'])]);
			}
		}
	}
	if (isset($rate) && is_numeric($rate)) {
		return $rate;
	} else {
		return null;
	}
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
		WHERE a.id='" . intval($id) . "' AND " . get_filter_site_cond('articles', 'a') . " " . ($show_all_etat_if_admin && a_priv("admin_content", false) ? '' : "AND a.etat = '1' AND (a.titre_" . $_SESSION['session_langue'] . "!='' OR a.texte_" . $_SESSION['session_langue'] . "!='' OR a.chapo_" . $_SESSION['session_langue'] . "!='' OR a.surtitre_" . $_SESSION['session_langue'] . "!='')") . "");
	return fetch_assoc($qid);
}

/**
 * Retourne la remise d'un code promotionnel (en % dans le cas d'une remise en pourcentage ou dans le format imposer par fprix pour une remise en Euros)
 *
 * @param float $remise_valeur
 * @param float $remise_percent
 * @param boolean $is_remise_valeur_including_taxe
 * @return
 */
function get_discount_text($remise_valeur, $remise_percent, $is_remise_valeur_including_taxe)
{
	$remise_displayed = array();
	$remise_valeur = floatval($remise_valeur);
	$remise_percent = floatval($remise_percent);
	if (!empty($remise_valeur)) {
		$remise_displayed[] = fprix($remise_valeur, true, $GLOBALS['site_parameters']['code'], false);
	}
	if (!empty($remise_percent)) {
		$remise_displayed[] = sprintf("%0.2f", $remise_percent) . '% ' . ($is_remise_valeur_including_taxe ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']);
	}

	return implode(' / ', $remise_displayed);
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
 * Retourne les modules sous forme de tableau
 *
 * @param mixed $only_active
 * @param mixed $location
 * @param mixed $technical_code
 * @param mixed $force_update_cache_information
 * @return array Liste des modules
 */
function get_modules_array($only_active = false, $location = null, $technical_code = null, $force_update_cache_information = false, $force_site_id = null)
{
	static $modules_array;
	$static_hash = '';
	if ($only_active) {
		$static_hash .= 'only_active';
	}
	// On ajoute wwwroot dans le hash, en cas de configuration multisite certaine données sont différente en fonction du site.
	$static_hash .= $location . '_' . $technical_code . '_' . vb($GLOBALS['page_columns_count']). '_' . vb($GLOBALS['wwwroot']);
	if (!isset($modules_array[$static_hash]) || $force_update_cache_information) {
		$modules = array();
		// defined('IN_PEEL_ADMIN') pour get_filter_site_cond : Cette fonction est appelée en front office pour l'affichage des modules mais aussi en back office pour l'administration des modules. 
		// Pour l'édition des modules on exclut (ou pas) les éléments publiques en fonction de la configuration de l'administrateur.
		$sql = 'SELECT *
			FROM peel_modules
			WHERE ' . get_filter_site_cond('modules', null, defined('IN_PEEL_ADMIN')) . ' AND ' . ($location == 'header' && vn($GLOBALS['page_columns_count']) == 2 ?'(':'') . '(1' . ($technical_code ? ' AND technical_code="' . nohtml_real_escape_string($technical_code) . '"' : '') . ($location ? ' AND location="' . nohtml_real_escape_string($location) . '"' : '') . ')' . ($location == 'header' && vn($GLOBALS['page_columns_count']) == 2 ? ' OR (technical_code="caddie" AND location="below_middle")' : '') . ($location == 'header' && vn($GLOBALS['page_columns_count']) == 2 ?')':'') . ($only_active ? ' AND etat="1"' : '') . ($force_site_id ? ' AND site_id="'.intval($force_site_id).'"' : '') . '
			ORDER BY position, id';

		$query = query($sql);
		while ($this_module = fetch_assoc($query)) {
			// Traitement spécifique
			if (vn($GLOBALS['page_columns_count']) == 2 && $this_module['technical_code'] == 'caddie') {
				if ($this_module['location'] == 'below_middle') {
					// On déplace le module de droite vers le haut pour l'afficher quand même
					if ((empty($location) || $location == 'header') && empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
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
	// Ne pas mettre upsell dans la liste ci-après car un cache est déjà mis en place à l'intérieur du module
	$allowing_cache_modules_technical_codes = array('annonces' => 4500, 'tagcloud' => 120);
	// Pour annonces, si module de crons activé, le cron toutes les heures regénère les fichiers de cache pour éviter que ce soit un utilisateur qui le déclenche
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
			$cache_id = $this_module['technical_code'] . '_' . $_SESSION['session_langue'] . '_' . vn($criterias['catid']) . '_' . $GLOBALS['site_id'] . '_' . vn($_SESSION['session_admin_multisite']);
			$this_module_output_cache_object = new Cache($cache_id, array('group' => 'html_block'));
			if ($this_module_output_cache_object->testTime($allowing_cache_modules_technical_codes[$this_module['technical_code']], true)) {
				$this_module_output = $this_module_output_cache_object->get();
				$load_module = false;
			}
		}
		if ($load_module) {
			if ($this_module['technical_code'] == 'catalogue' && !empty($extra_catalogue_condition)) {
				if (function_exists('affiche_menu_catalogue')) {
					// Test sur la présence de affiche_menu_catalogue qui est l'ancienne fonction pour l'affichage du catalogue. L'utilisation est permise ici pour des raions de compatibilité avec d'ancien template, dans le cas où la fonction est défini dans display_custom.php
					$this_module_output = affiche_menu_catalogue($this_module['location'], true, true);
				} else {
					$this_module_output = get_categories_output($this_module['location'], 'categories', vn($_GET['catid']), 'list', null);
				}
				$tpl = $GLOBALS['tplEngine']->createTemplate('menu_catalogue.tpl');
				$tpl->assign('menu', $this_module_output);
				$tpl->assign('add_ul_if_result', true);
				$this_module_output = $tpl->fetch();
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
				$this_module_output = affiche_compte(true, $this_module['location']);
			} elseif ($this_module['technical_code'] == 'best_seller') {
				if (check_if_module_active('best_seller')) {
					$this_module_output = affiche_best_seller_produit_colonne(true, $this_module['location']);
				}
			} elseif ($this_module['technical_code'] == 'brand') {
				// affiche du block marque
				$this_module_output = get_brand_link_html(null, true, true, $this_module['location']);
			} elseif ($this_module['technical_code'] == 'last_views') {
				if (is_last_views_module_active()) {
					$this_module_output = affiche_last_views($this_module['location']);
				}
			} elseif ($this_module['technical_code'] == 'quick_access') {
				if (function_exists('get_quick_access')) {
					$this_module_output = get_quick_access($this_module['location'], true);
				}
			} elseif ($this_module['technical_code'] == 'news' || $this_module['technical_code'] == 'articles_rollover') {
				if (is_rollover_module_active()) {
					if($this_module['technical_code'] == 'news') {
						$items_html_array = get_on_rollover_products_html();
					} else {
						$items_html_array = get_on_rollover_articles_html();
					}
					if (vn($GLOBALS['site_parameters']['type_rollover']) == 1) {
						$this_module_output = affiche_menu_deroulant_1('scrollerdiv_' . $this_module['technical_code'], $items_html_array);
					} elseif (vn($GLOBALS['site_parameters']['type_rollover']) == 2) {
						$this_module_output = affiche_menu_deroulant_2('scrollerdiv_' . $this_module['technical_code'], $items_html_array);
					}
				}
			} elseif (String::substr($this_module['technical_code'], 0, String::strlen('advertising')) == 'advertising' && is_module_banner_active()) {
				/* Explication du fonctionnement des bannières publicitaires */
				
				// A NE PAS CONFONDRE
				// Location	: Correspond à l'emplacement 'physique' de la fonction get_module sur la page (left, top, right, etc ...). Cette valeur est administrable dans la page d'administration des modules, via des boutons radios qui représente chaque emplacement prévu dans le site. Ce paramètre est utilisé par get_module seulement.
				// Espace : C'est un emplacement publicitaire, qui est administrable en back office. Ce chiffre permet de faire le lien entre le module de la table peel_modules et la bannière pub. Dans peel_modules, le numéro de l'espace pour la bannière pub est concaténé avec code technique du module dans le champ technical_code.
				// ATTENTION => l'espace est stocké dans le champ POSITION de peel_banniere ! Il faudra refondre le module pour rendre tout ça cohérent.
				// Position : La position d'une bannière est défini par le champ 'rang' de peel_banniere. Cela permet de trier les bannières entre elles dans un même espace publicitaire.

				// ROLE DE GET_MODULE POUR L'AFFICHAGE DES PUB
				// Défini l'emplacement sur la page où la publicité s'affichera. La fonction récupère dans un premier temps les modules dont la 'location' choisi par l'administrateur en back office est égal au paramètre location get_module
				// Pour chaque résultat, un technical_code indique la fonction associé au module. Pour les pubs ce technical_code commence par advertising

				// UTILITE DE LA VALEUR TECHNICAL_CODE DE PEEL_MODULES
				// => cette valeur est une association de deux valeurs : 
					// - advertising, qui indique que le module doit afficher une pub
					// - l'espace, qui permet de faire le lien avec la bannière publicitaire lié au module.

				// ROLE DE AFFICHE_BANNER
				// affiche_banner rècupère l'espace publicitaire qui a été fourni par le technical_code du module. La fonction retourne la(les) bannière(s) associée(s) à cet espace par l'administrateur en back office, en plus des critères de dates, de contrainte sur les pages, etc....
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
				} elseif (defined('IN_AD_CREATION')) {
					$page_type = 'ad_creation_page';
				} else {
					$page_type = 'other_page';
				}
				if (!empty($keywords_array)) {
					$sql_cond .= ' AND ' . build_terms_clause($keywords_array, array('keywords'), 2);
				}
				$this_module_output = affiche_banner(String::substr($this_module['technical_code'], strlen('advertising')), true, (isset($criterias['page'])?$criterias['page']:null), $id_categorie, $this_annonce_number, $page_type, (isset($criterias['search'])?explode(' ', $criterias['search']):null), $_SESSION['session_langue'], $return_array_with_raw_information, (isset($criterias['ref'])?$criterias['ref']:null), vn($GLOBALS['page_related_to_user_id']));
			} elseif ($this_module['technical_code'] == 'menu') {
				$this_block_style = ' ';
				foreach ($modules_array as $this_module2) {
					if ($this_module2['technical_code'] == 'caddie' && $this_module['location'] == 'header' && empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
						$this_block_style = ' style="width:80%"';
					}
				}
				$this_module_output = get_menu(vb($GLOBALS['main_div_id']));
			} elseif ($this_module['technical_code'] == 'ariane') {
				$this_module_output = affiche_ariane(true);
			} elseif ($this_module['technical_code'] == 'paiement_secu') {
				$this_module_output = get_modules_paiement_secu();
			} elseif ($this_module['technical_code'] == 'newsletter_in_column') {
				// $this_module_output = get_newsletter_in_column();
			} elseif ($this_module['technical_code'] == 'subscribe_newsletter') {
				$this_module_output = get_newsletter_form($this_module['location'], true);
			} elseif ($this_module['technical_code'] == 'contact') {
				$this_module_output = get_contact_sideblock($this_module['location'], true);
			} elseif ($this_module['technical_code'] == 'annonces' && check_if_module_active('annonces')) {
				$this_module_output = affiche_menu_annonce($this_module['location'], true, true);
			} elseif ($this_module['technical_code'] == 'become_verified' && check_if_module_active('abonnement')) {
				$this_module_output = get_verified_sideblock_link($this_module['location'], true);
			} elseif ($this_module['technical_code'] == 'upsell' && check_if_module_active('abonnement')) {
				$this_module_output = getVerifiedAdsList();
			} elseif ($this_module['technical_code'] == 'search_by_list' && check_if_module_active('annonces')) {
				$this_module_output = get_annonces_in_box('search_by_list', $this_module['location'], true);
				$this_module['sliding_mode'] = false;
			} elseif ($this_module['technical_code'] == 'product_new') {
				if (check_if_module_active('annonces')) {
					$this_module_output = get_annonces_in_box('last', $this_module['location'], true);
					$this_module['sliding_mode'] = false;
				} else {
					$this_module_output = get_product_new_list($this_module['location'], true);
				}
			} elseif ($this_module['technical_code'] == 'last_forum_posts' && is_module_forum_active()) {
				$this_module_output = getForumLastMessages($_SESSION['session_langue']);
			} elseif ($this_module['technical_code'] == 'new_members_list' && check_if_module_active('groups_advanced')) {
				$this_module_output = get_new_members_list();
			} elseif ($this_module['technical_code'] == 'birthday_members_list' && check_if_module_active('groups_advanced')) {
				$this_module_output = get_birthday_members_list();
			} elseif ($this_module['technical_code'] == 'agenda_datepicker' && check_if_module_active('agenda')) {
				$this_module_output = display_agenda();
			} elseif ($this_module['technical_code'] == 'get_search_product_form' ) {
				$this_module_output = get_search_form($_GET, vb($_GET['search']), vb($_GET['match']), null, 'module_products');
			} elseif ($this_module['technical_code'] == 'get_search_ads_form' ) {
				$this_module_output = get_search_form($_GET, vb($_GET['search']), vb($_GET['match']), null, 'module_ads');
			} elseif ($this_module['technical_code'] == 'get_search_member_form' && check_if_module_active('groups_advanced')) {
				$this_module_output = get_search_user_form("module");
			} elseif ($this_module['technical_code'] == 'next_product_flash' && check_if_module_active('flash')) {
				$this_module_output = get_next_product_flash();
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
						$this_class = $this_module['display_mode'] . ' ' . $this_module['location'] . '_basicblock ' . $this_module['location'] . '_' . $this_module['technical_code'] . '  ' . $this_module['technical_code'] . '_' . $_SESSION['session_langue'];
						if (($this_module['display_mode'] == 'sideblocktitle' || $this_module['display_mode'] == 'sideblock') && $this_module['location'] == 'footer') {
							$extra_class = true;
						} else {
							$extra_class = false;
						}
						if($this_module['location'] == 'footer') {
							$output .= affiche_block($this_module['display_mode'], $this_module['location'], $this_module['technical_code'], vb($this_module['title_' . $_SESSION['session_langue']]), $this_module_output, $this_class, $this_block_style, true, true, true, vb($extra_class));
						} else {
							$output .= affiche_block($this_module['display_mode'], $this_module['location'], $this_module['technical_code'], vb($this_module['title_' . $_SESSION['session_langue']]), $this_module_output, $this_class, $this_block_style, true, true, true);
						}
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
	if (check_if_module_active('webmail')) {
		save_mail_db($frm);
	}

	if (!empty($GLOBALS['support_sav_client'])) {
		unset($custom_template_tags);
		$custom_template_tags['DATE'] = get_formatted_date(time(), 'short', 'long');
		$custom_template_tags['NOM_FAMILLE'] = vb($frm['nom']);
		$custom_template_tags['SOCIETE'] = vb($frm['societe']);
		$custom_template_tags['TELEPHONE'] = vb($frm['telephone']);
		$custom_template_tags['ADRESSE'] = vb($frm['adresse']) . ' ' . vb($frm['code_postal']) . ' ' . vb($frm['ville']) . ' ' . vb($frm['pays']);
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
			if (String::strpos(vb($frm['sujet']), '/24') !== false && !empty($GLOBALS['site_parameters']['email_emergency'])) {
				$recipient_email = $GLOBALS['site_parameters']['email_emergency'];
			} else {
				$recipient_email = $GLOBALS['support_sav_client'];
			}
			send_email($recipient_email, '', '', 'insere_ticket', $custom_template_tags, null, $GLOBALS['support'], true, false, false, vb($frm['email']));
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
		WHERE id=' . intval($id) . " AND " .  get_filter_site_cond('pays');
	$q = query($sql);
	if ($result = fetch_assoc($q)) {
		return String::html_entity_decode_if_needed($result['pays_' . $_SESSION['session_langue']]);
	} else {
		return false;
	}
}

/**
 * get_country_id()
 *
 * @param mixed $country_name
 * @return
 */
function get_country_id($country_name)
{
	$sql = 'SELECT id
		FROM peel_pays
		WHERE pays_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($country_id_or_name) . '" AND ' .  get_filter_site_cond('pays') . '
		LIMIT 1';
	$query = query($sql);
	if ($obj = fetch_object($query)) {
		$result = $obj->id;
	}
	if (!empty($result)) {
		return $result;
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
		FROM peel_categories c
		WHERE id="' . intval($id) . '" AND ' . get_filter_site_cond('categories', 'c') . '';
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
 * @param string $table_to_use
 * @return
 */
function get_category_tree_and_itself($id_or_ids_array, $mode = 'sons', $table_to_use = 'categories')
{
	static $result_array;
	static $first_depth_results_array;
	if(empty($first_depth_results_array[$mode][$table_to_use])) {
		if ($mode == 'sons') {
			$select_field = 'id';
			$condition_field = 'parent_id';
		} elseif ($mode == 'parents') {
			$select_field = 'parent_id AS id';
			$condition_field = 'id';
		} else {
			// erreur de paramétrage
			return false;
		}
		$site_cond = "";
		if ($table_to_use == 'rubriques') {
			$table = 'peel_rubriques';
			$site_cond .= " AND " . get_filter_site_cond($table_to_use) . "";
		} elseif ($table_to_use == 'categories') {
			$table = 'peel_categories';
			$site_cond .= " AND " . get_filter_site_cond($table_to_use) . "";
		} elseif ($table_to_use == 'annonces') {
			$table = 'peel_categories_annonces';
		} else {
			// erreur de paramétrage
			return false;
		}
		$sql = 'SELECT id, parent_id
			FROM ' . $table . '
			WHERE etat=1 '.$site_cond.'
			ORDER BY position ASC';
		$qid = query($sql);
		while ($cat = fetch_assoc($qid)) {
			$first_depth_results_array[$mode][$table_to_use][$cat[$condition_field]][] = $cat[$select_field];
		}
	}
	if (is_array($id_or_ids_array)) {
		$ids_list = implode(',', $id_or_ids_array);
	} else {
		$ids_list = $id_or_ids_array;
	}
	if (empty($result_array[$mode][$table_to_use][$ids_list])) {
		if (is_array($id_or_ids_array)) {
			$result_array[$mode][$table_to_use][$ids_list] = $id_or_ids_array;
		} else {
			$result_array[$mode][$table_to_use][$ids_list][] = $id_or_ids_array;
		}
		foreach(explode(',', $ids_list) as $this_condition_id) {
			if(!empty($first_depth_results_array[$mode][$table_to_use][$this_condition_id])) {
				foreach($first_depth_results_array[$mode][$table_to_use][$this_condition_id] as $this_found_id) {
					$result_array[$mode][$table_to_use][$ids_list] = array_merge($result_array[$mode][$table_to_use][$ids_list], get_category_tree_and_itself($this_found_id, $mode, $table_to_use));
				}
			}
		}
	}
	return $result_array[$mode][$table_to_use][$ids_list];
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
 * @param array $allowed_ids
 * @return
 */
function get_country_select_options($selected_country_name = null, $selected_country_id = null, $option_value = 'name', $display_inactive_country = false, $allowed_zone_id = null, $preselect_shop_country_if_none_selected = true, $selected_country_lang = null, $allowed_ids = null)
{
	$output = '';
	$sql_condition = '';
	if ($preselect_shop_country_if_none_selected && empty($selected_country_name) && empty($selected_country_id)) {
		if(!empty($_SESSION['session_country_detected'])) {
			$selected_country_id = vn($_SESSION['session_country_detected']);
		} else {
			$selected_country_id = vn($GLOBALS['site_parameters']['default_country_id']);
		}
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
	if (!empty($allowed_ids)) {
		$sql_condition .= ' AND id IN ("' . implode('","', real_escape_string($allowed_ids)) . '")';
	}
	$sql_pays = 'SELECT id, pays_' . $_SESSION['session_langue'] . ' ' . $sql_select_add_fields . '
		FROM peel_pays
		WHERE 1 ' . $sql_condition . '  AND ' . get_filter_site_cond('pays') . '
		ORDER BY position, pays_' . $_SESSION['session_langue'];

	$res_pays = query($sql_pays);
	$tpl = $GLOBALS['tplEngine']->createTemplate('select_options.tpl');
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
		WHERE etat = 1 AND " . get_filter_site_cond('types') . " AND (nom_" . $_SESSION['session_langue'] . "!=''".(!empty($selected_delivery_type_id_or_name)?" OR id='" . real_escape_string($selected_delivery_type_id_or_name) . "'":"").")
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
 * is_delivery_address_necessary_for_delivery_type()
 *
 * @param integer $selected_delivery_type_id Id of the type preselected
 * @return
 */
function is_delivery_address_necessary_for_delivery_type($selected_delivery_type_id = null)
{
	$sql_type = "SELECT without_delivery_address
		FROM peel_types
		WHERE id='" . intval($selected_delivery_type_id) . "' AND " . get_filter_site_cond('types') . "";
	$res_type = query($sql_type);

	if ($type = fetch_assoc($res_type)) {
		return (!$type['without_delivery_address']);
	} else {
		return null;
	}
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
			WHERE technical_code = '" . nohtml_real_escape_string($frm['payment_technical_code']) . "' AND " .  get_filter_site_cond('paiement') . "";
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
	$where = 'WHERE ' .  get_filter_site_cond('paiement', 'p') . ' AND (totalmin<=' . floatval($_SESSION['session_caddie']->total) . ' OR totalmin=0) AND (totalmax>=' . floatval($_SESSION['session_caddie']->total) . ' OR totalmax=0)';

	if (is_payment_by_product_module_active()) {
		$where = payment_by_product_condition();
	}
	
	$sql_paiement = 'SELECT p.*
		FROM peel_paiement p
		' . $where . '
		ORDER BY p.position';
	$res_paiement = query($sql_paiement);
	$results_count = num_rows($res_paiement);
	while ($tab_paiement = fetch_assoc($res_paiement)) {
		$payment_complement_informations = '';
		if((empty($tab_paiement['etat']) || empty($tab_paiement['nom_' . $_SESSION['session_langue']])) && (!$show_selected_even_if_not_available || $tab_paiement['technical_code'] != $selected_payment_technical_code)){
			// On ne prend que les moyens de paiement actifs, ou ceux qui ont pour code technique $selected_payment_technical_code si $show_selected_even_if_not_available = true
			// Dans les autres cas, on passe au suivant
			continue;
		}
		if (($tab_paiement['technical_code'] == 'kwixo' && ($_SESSION['session_caddie']->zone_technical_code != 'france_mainland' && $_SESSION['session_caddie']->zone_technical_code != 'france_and_overseas')) || $tab_paiement['technical_code'] == 'kwixo_credit' && ($_SESSION['session_caddie']->montant >= 150 && $_SESSION['session_caddie']->montant <= 4000)) {
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
			$tpl->assign('issel', (vb($selected_payment_technical_code) == $tab_paiement['technical_code'] || $results_count == 1));
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
 * Chargement du chargement des scripts.
 * Possibilités de la mise en chargement des fichiers javascripts dans le code : $GLOBALS['xxx'][] = 'filename.js'  avec xxx dans la liste suivante :
 * - 'js_files' : sera minifié (si autorisé par $minify = true) avec les autres fichiers du tableau, dans l'ordre des clés du tableau + chargé en asynchrone (si autorisé par $async = true) 
 * - 'js_files_pageonly' : sera minifié (si autorisé par $minify = true) si autorisé avec les autres fichiers du tableau, dans l'ordre des clés du tableau (si autorisé par $async = true)
 *   => cela permet d'avoir un minified général pour le site, et un minified pour la page => évite de recharger deux fois ce qui est général au site
 * - 'js_files_nominify' : le fichier ne sera pas fusionné avec d'autres, mais pourra être chargé en asynchrone (si autorisé par $async = true) 
 * - 'js_files_noasync' : sera minifié (si autorisé par $minify = true) avec les autres fichiers du tableau, dans l'ordre des clés du tableau, mais pas chargé en asynchrone
 * - 'js_files_nominify_noasync' : ne sera ni minifié, ni chargé en asyncrone = balise script normale sans modification
 * NB : Pour le chargement asynchrone des javascripts, contrairement à ce qui est proposé par Google, on n'attend pas le onload mais le DOM loaded de sorte qu'une iframe qui tarde à charger n'empêche pas le chargement des scripts
 *
 * @param boolean $async
 * @param boolean $minify
 * @param boolean $output_only_script_loading
 * @param array $js_filenames_array
 * @return
 */
function get_javascript_output($async = false, $minify = false, $output_only_script_loading = false, $js_filenames_array = array('js_files', 'js_files_pageonly', 'js_files_nominify', 'js_files_noasync', 'js_files_nominify_noasync'))
{
	static $already_loaded = false;
	if($already_loaded) {
		// Si on affiche du javascript en haut de page, et qu'il y a quand même des scripts pour le bas de page, on ne minify pas le bas de page
		$minify = false;	
	}
	$already_loaded = true;
	$js_content = '';
	$output = '';
	if(!empty($GLOBALS['site_parameters']['load_site_specific_js_files'])) {
		if(!empty($GLOBALS['js_files'])) {
			ksort($GLOBALS['js_files']);
		}
		$GLOBALS['js_files'] = array_merge(vb($GLOBALS['js_files'], array()), $GLOBALS['site_parameters']['load_site_specific_js_files']);
	}
	if(!empty($GLOBALS['site_parameters']['load_site_specific_js_content_array'])) {
		$GLOBALS['js_content_array'] = array_merge(vb($GLOBALS['js_content_array'], array()), $GLOBALS['site_parameters']['load_site_specific_js_content_array']);
	}
	if(!empty($GLOBALS['site_parameters']['load_site_specific_js_ready_content_array'])) {
		$GLOBALS['js_ready_content_array'] = array_merge(vb($GLOBALS['js_ready_content_array'], array()), $GLOBALS['site_parameters']['load_site_specific_js_ready_content_array']);
	}
	foreach($js_filenames_array as $this_js_array_name) {
		if(!empty($GLOBALS[$this_js_array_name])) {
			ksort($GLOBALS[$this_js_array_name]);
			if(count($GLOBALS[$this_js_array_name])>1 && $minify && String::strpos($this_js_array_name, 'nominify') === false) {
				$GLOBALS[$this_js_array_name] = get_minified_src($GLOBALS[$this_js_array_name], 'js', 10800);
			}elseif(!empty($_GET['update']) && $_GET['update'] == 1) {
				foreach($GLOBALS[$this_js_array_name] as $this_key => $this_js_file) {
					$GLOBALS[$this_js_array_name][$this_key] = $this_js_file . (String::strpos($this_js_file, '?')!==false?'&':'?') . time();
				}
			}
		}
	}
	if(!empty($GLOBALS['js_ready_content_array'])) {
		if(!$async) {
			$GLOBALS['js_content_array'][] = '
	(function($) {
		$(document).ready(function() {
			' . implode("\n", $GLOBALS['js_ready_content_array']) . '
		});
	})(jQuery);
	';
		} else {
			// On a déjà attendu le DOM loaded avant, pas besoin de réattendre le ready (qui se produit au onload dans Firefox)
			$GLOBALS['js_content_array'][] = '
	(function($) {
			' . implode("\n", $GLOBALS['js_ready_content_array']) . '
	})(jQuery);
	';
		}
	}
	if(!empty($GLOBALS['js_content_array'])) {
		$js_content .= implode("\n", $GLOBALS['js_content_array']);
	}
	if(!$async) {
		$noasync_js_filenames_array = $js_filenames_array;
	} else {
		foreach($js_filenames_array as $this_key => $this_js_array_name) {
			if(!empty($GLOBALS[$this_js_array_name]) && String::strpos($this_js_array_name, 'noasync') !== false) {
				$noasync_js_filenames_array[] = $this_js_array_name;
				unset($js_filenames_array[$this_key]);
			}
		}
	}
	if(!empty($noasync_js_filenames_array)) {
		foreach($noasync_js_filenames_array as $this_js_array_name) {
			if(!empty($GLOBALS[$this_js_array_name])) {
				ksort($GLOBALS[$this_js_array_name]);
				foreach($GLOBALS[$this_js_array_name] as $js_href) {
					$output .= '<script src="' . String::str_form_value($js_href) . '"></script>
';
				}
			}
			if($output_only_script_loading) {
				$GLOBALS[$this_js_array_name] = array();
			}
		}
	}
	if(!$async) {
		if($output_only_script_loading) {
			return $output;
		}
	}
	if($async) {
		krsort($js_filenames_array);
		foreach($js_filenames_array as $this_js_array_name) {
			if(!empty($GLOBALS[$this_js_array_name])) {
				krsort($GLOBALS[$this_js_array_name]);
				foreach($GLOBALS[$this_js_array_name] as $this_filename) {
					// On appelle le javascript de manière récursive, si plusieurs fichiers doivent être chargés avant l'exécution du script en ligne
					if(String::substr($this_filename, 0, 2) == '//') {
						// Gestion des chemins de fichiers http/https automatiques
						if(strpos($GLOBALS['wwwroot'], 'https') === 0) {
							$this_filename = 'https:'.$this_filename;
						} else {
							$this_filename = 'http:'.$this_filename;
						}
					}
					$js_content = '
		loadScript("'.String::html_entity_decode($this_filename).'", function(){
				'.$js_content.'
			});
';
				}
			}
		}
		$js_content = '
	function loadScript(url,callback){
		var script = document.createElement("script");
		if(typeof document.attachEvent === "object"){
			// IE<=8
			script.onreadystatechange = function(){
				//once the script is loaded, run the callback
				if (script.readyState === "loaded" || script.readyState=="complete"){
					script.onreadystatechange = null;
					if (callback){callback()};
				};
			};  
		} else {
			// All other browsers
			script.onload = function(){
				//once the script is loaded, run the callback
				script.onload = null;
				if (callback){callback()};
			};
		};
		script.src = url;
		document.getElementsByTagName("head")[0].appendChild(script);
	};
	function downloadJSAtOnload() {
		if(async_launched) {
			return false;
		}
		async_launched = true;
		' . $js_content . '
	}
	// Different browsers
	var async_launched = false;
	if(document.addEventListener) document.addEventListener("DOMContentLoaded", downloadJSAtOnload, false);
	else if (window.addEventListener) window.addEventListener("load", downloadJSAtOnload, false);
	else if (window.attachEvent) window.attachEvent("onload", downloadJSAtOnload);
	else window.onload = downloadJSAtOnload;
	// Si onload trop retardé par chargement d\'un site extérieur
	setTimeout(downloadJSAtOnload, 10000);
	';
	}
	if(!empty($js_content)) {
		$output .= '
		<script><!--//--><![CDATA[//><!--
			' . $js_content . '
		//--><!]]></script>
';
	}
	foreach($js_filenames_array as $this_js_array_name) {
		$GLOBALS[$this_js_array_name] = array();
	}
	$GLOBALS['js_content_array'] = array();
	$GLOBALS['js_ready_content_array'] = array();
	return $output;
}


/**
 * get_datepicker_javascript()
 *
 * @return
 */
function get_datepicker_javascript()
{
	$datepicker_format = str_replace(array('%d','%m','%Y','%y'), array('dd','mm','yy','y'), $GLOBALS['date_format_short']);
	$output = '
	$(".datepicker").datepicker({                    
		dateFormat: "' . $datepicker_format . '",
		changeMonth: true,
		changeYear: true,
		yearRange: "1902:2037",
		beforeShow: function() {
			setTimeout(function(){
				$(".ui-datepicker").css("z-index", 9999999);
			}, 0);
		}
	});
	$(".datepicker").attr("placeholder","'.str_replace(array('d', 'm', 'y'), array(String::substr(String::strtolower($GLOBALS['strDays']), 0, 1), String::substr(String::strtolower($GLOBALS['strMonths']), 0, 1), String::substr(String::strtolower($GLOBALS['strYears']), 0, 1)), str_replace('y', 'yy', $datepicker_format)).'");
';
	return $output;
}

/**
 * get_css_files_to_load()
 *
 * @param boolean $minify
 * @return
 */
function get_css_files_to_load($minify = false)
{
	ksort($GLOBALS['css_files']);
	$GLOBALS['css_files'] = array_unique($GLOBALS['css_files']);
	if($minify) {
		$GLOBALS['css_files'] = get_minified_src($GLOBALS['css_files'], 'css', 10800);
		ksort($GLOBALS['css_files']);
	} elseif(!empty($_GET['update']) && $_GET['update'] == 1) {
		foreach($GLOBALS['css_files'] as $this_key => $this_css_file) {
			$GLOBALS['css_files'][$this_key] = $this_css_file . (String::strpos($this_css_file, '?')!==false?'&':'?') . time();
		}
	}
	$temp = $GLOBALS['css_files'];
	$GLOBALS['css_files'] = array();
	return $temp;
}

/**
 * Envoie les headers avant l'envoi du HTML
 *
 * @param string $page_encoding
 * @return
 */
function output_general_http_header($page_encoding = null) {
	if(empty($page_encoding)) {
		$page_encoding = GENERAL_ENCODING;
	}
	header('Content-type: text/html; charset=' . $page_encoding);
	if (!empty($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')!== false) {
		// Demande à IE de ne pas se mettre dans un mode de compatibilité => permet de bénéficier des dernières avancées de la version utilisée
		header('X-UA-Compatible: IE=edge,chrome=1');
	}
}

/**
 * Redirige vers l'URL demandée et arrête le programme
 *
 * @param string $url
 * @param boolean $permanent_redirection
 * @param boolean $avoid_loop
 * @return
 */
function redirect_and_die($url, $permanent_redirection = false, $avoid_loop = false)
{
	if($avoid_loop && empty($_POST) && $url == get_current_url(true, false)) {
		return false;
	}
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
 * @param boolean $configuration_modification
 * @return
 */
function necessite_priv($priv, $demo_allowed = true, $configuration_modification = false)
{
	if (!a_priv($priv, $demo_allowed, $configuration_modification)) {
		if(String::strpos(get_current_url(true),'chart-data.php')===false){
			$_SESSION['session_redirect_after_login'] = get_current_url(true);
		}
		if(String::strpos($priv, 'admin') === 0 && a_priv("admin*")) {
			redirect_and_die($GLOBALS['administrer_url'] . '/?error=admin_rights');
		} elseif (est_identifie()) {
			redirect_and_die($GLOBALS['wwwroot'] . '/compte.php?error=admin_rights');
		} else {
			redirect_and_die($GLOBALS['wwwroot'] . '/membre.php?error=admin_rights');
		}
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
 * Pour qu'une langue xx soit autorisée, il est nécessaire d'avoir créé un fichier lib/lang/xx.php
 *
 * @param array $langs_array
 * @return Langue identifiée automatiquement pour l'utilisateur
 */
function get_identified_lang($langs_array = array())
{
	if (!empty($_GET['langue']) && String::strlen($_GET['langue']) == 2) {
		$return_lang = String::strtolower(trim($_GET['langue']));
	} elseif (empty($_SESSION['session_langue']) || empty($GLOBALS['get_lang_rewrited_wwwroot'][$_SESSION['session_langue']]) || $GLOBALS['get_lang_rewrited_wwwroot'][$_SESSION['session_langue']] != $GLOBALS['detected_wwwroot']) {
		// Au cas où on doit définir la langue à partir de l'URL, donc si on n'est pas dans une logique de langue par repértoire
		// mais de langue par domaine ou sous-domaine, on prend la première trouvée (celle qui a la variable "position" la plus faible)
		// NB : On veut pouvoir détecter des URL du type http://xxxxx.en.domain.com pour des sous-domaines
		foreach ($langs_array as $this_lang) {
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
	} elseif (empty($return_lang) && !empty($langs_array)) {
		// Par défaut on prend la langue du navigateur
		$temp = explode(',', vb($_SERVER['HTTP_ACCEPT_LANGUAGE']));
		$return_lang = String::strtolower(String::substr(trim($temp[0]), 0, 2));
		if(!in_array($return_lang, $langs_array)) {
			// A défaut on prend l'anglais
			$return_lang = 'en';
		}
		if(!in_array($return_lang, $langs_array)) {
			// Si on ne trouve aucune langue, on prendra la première langue du site trouvée par défaut
			$return_lang = $langs_array[0];
		}
	} elseif (empty($return_lang)) {
		// Aucune langue configurée : on force l'anglais pour éviter langue vide
		$return_lang = 'en';
	}
	$return_lang = check_language($return_lang, $langs_array);
	return String::substr($return_lang, 0, 2);
}

/**
 * Vérification de l'existance de la langue, et redirection si nécessaire ou nouvelle langue définie
 *
 * @param string $this_lang
 * @param array $langs_array
 * @return
 */
function check_language($this_lang, $langs_array)
{
	if (!in_array($this_lang, $langs_array) || empty($GLOBALS['lang_etat'][$this_lang]) || !file_exists($GLOBALS['dirroot'] . "/lib/lang/" . $this_lang . ".php")) {
		// The language asked in the URL is not available
		// We redirect to the equivalent page in the default language
		foreach ($langs_array as $this_new_lang) {
			if ($this_new_lang != $this_lang && !empty($GLOBALS['lang_etat'][$this_new_lang]) && get_current_url() != get_current_url_in_other_language($this_new_lang)) {
				redirect_and_die(get_current_url_in_other_language($this_new_lang));
			}
		}
		// No redirection already done => there was a problem in lang detection => we force a correct language
		$this_lang = current($langs_array);
	}
	return $this_lang;
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
		$this_url_lang = str_replace(array('&langue=' . $_GET['langue'], '?langue=' . $_GET['langue'].'&', '?langue=' . $_GET['langue'], 'langue=' . $_GET['langue']), array('', '?', '', ''), $this_url_lang);
	} elseif (!empty($_SESSION['session_langue'])) {
		$original_lang = $_SESSION['session_langue'];
	} else {
		$original_lang = $this_lang;
	}

	$original_lang = strtolower($original_lang);

	if (!is_module_url_rewriting_active() || (!empty($GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]) && !empty($GLOBALS['langs_array_by_wwwroot'][$GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]]) && count($GLOBALS['langs_array_by_wwwroot'][$GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]]) > 1)) {
		// Comme le chemin pour une page dans cette langue n'est pas spécifique, alors on doit préciser la langue quand on veut changer de page
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
	if (!empty($GLOBALS['get_lang_rewrited_wwwroot'][$this_lang])) {
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
	if(defined('IN_CRON')) {
		// get_current_url ne doit pas être utilisée dans un cron, aucune URL n'appelle la page dans ce cas.
		return null;
	}
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
		// On évite par exemple  les problèmes de parenthèses encodées par PHP mais pas par apache
		$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
		foreach($take_away_get_args_array as $key) {
			$url = str_replace(array(urlencode($key).'='.urlencode(vb($_GET[$key])), urlencode($key).'='.str_replace($entities, $replacements, urlencode(vb($_GET[$key]))), $key.'='.vb($_GET[$key])), '', $url);
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
	$cache_key = md5(serialize($params));
	if (empty($uri_array[$cache_key])) {
		$queryString = array();
		$uri = get_current_url(false);
		$excluded_get[] = 'page';
		$excluded_get[] = 'multipage';
		$excluded_get[] = 'nombre';
		$excluded_get[] = 'update';
		if(!empty($params['type']) && $params['type']=='error404') {
			$excluded_get[] = 'type';
		}
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
			if (check_if_module_active('annonces') && !empty($_GET['catid']) && defined('IN_CATALOGUE_ANNONCE')) {
				// Page de catégorie d'annonces
				$excluded_get[] = 'catid';
				$uri = str_replace('-' . String::rawurlencode(vn($_GET['page'])) . '-' . String::rawurlencode(vn($_GET['catid'])) . '.html', '-[PAGE]-' . String::rawurlencode(vn($_GET['catid'])) . '.html', $uri);
			} elseif (check_if_module_active('vitrine') && get_current_url(false, true) == '/' . vn($_GET['page']) . '.html') {
				// Page de boutique
				$uri = str_replace('/' . String::rawurlencode(vn($_GET['page'])) . '.html', '/[PAGE]' . '.html', $uri);
			} elseif (check_if_module_active('vitrine') && get_current_url(false, true) == '/') {
				// Page de boutique : accueil
				$uri .= '[PAGE].html';
			} elseif (check_if_module_active('vitrine') && String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/' . $GLOBALS['STR_MODULE_ANNONCES_URL_LIST_SHOWCASE'] . '-' . String::rawurlencode(vn($_GET['page'])) . '.html')) {
				// Page de liste des vitrines
				$uri = str_replace('-' . String::rawurlencode(vn($_GET['page'])) . '.html', '-[PAGE].html', $uri);
			} elseif (check_if_module_active('vitrine') && strpos(get_current_url(false, true), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-')===0) {
				// Page de boutique non verified
				$excluded_get[] = 'bt';
				$uri = str_replace('/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-'.String::rawurlencode(vn($_GET['page'])), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'', $uri);
				// On fait le remplacement en deux étapes pour bien capter les URL STR_MODULE_ANNONCES_URL_VITRINE-... et STR_MODULE_ANNONCES_URL_VITRINE tout court
				$uri = str_replace('/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'], '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-[PAGE]', $uri);
			} elseif (check_if_module_active('vitrine') && strpos(get_current_url(false, true), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-'.String::rawurlencode(vn($_GET['page'])) . '-')===0) {
				// Page de boutique non verified
				$excluded_get[] = 'bt';
				$uri = str_replace('/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-'.String::rawurlencode(vn($_GET['page'])), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-[PAGE]', $uri);
			} elseif (String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/produits/' . String::rawurlencode(vb($_GET['search'])) . '.html')) {
				$excluded_get[] = 'search';
				$uri = str_replace('.html', '-[PAGE].html', $uri);
			} elseif (String::rawurldecode(get_current_url(false, true)) == String::rawurldecode('/produits/' . String::rawurlencode(vb($_GET['search'])) . '-' . String::rawurlencode(vn($_GET['page'])) . '.html')) {
				$excluded_get[] = 'search';
				$uri = str_replace('-' . String::rawurlencode(vn($_GET['page'])) . '.html', '-[PAGE].html', $uri);
			} elseif (check_if_module_active('annonces') && !empty($_GET['country']) && strpos(get_current_url(false, true),'-' . String::rawurlencode(vb($_GET['country'])) . '.html') !== false) {
				// Page de liste des vitrines
				$excluded_get[] = 'country';
			}
			if (check_if_module_active('annonces')) {
				foreach(array('/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/'.$GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'].'-', '/kopen/supplier-research-', '/kaufen/supplier-research-', '/buy/supplier-research-', '/buy/supplier-research-', '/acheter/recherche-fournisseur-', '/comprar/busqueda-proveedor-') as $this_url_rewriting_main_expression) {
					if(    String::rawurldecode(get_current_url(false, true)) == String::rawurldecode($this_url_rewriting_main_expression . String::rawurlencode(vn($_GET['page'])) . '-' . String::rawurlencode(vb($_GET['search'])) . '.html')
						|| String::rawurldecode(get_current_url(false, true)) == String::rawurldecode($this_url_rewriting_main_expression . String::rawurlencode(vn($_GET['page'])) . '-' . String::rawurlencode(urlencode(vb($_GET['search']))) . '.html')) {
						$excluded_get[] = 'search';
						// Si l'URL contient un +, pas encodé ou encodé en %2B, il faut le gérer quoiqu'il arrive => on a deux possibilités dans le str_replace
						// La troisième est là pour couvrir des URL du type : /acheter/recherche-fournisseur-54-pc+.html?search=pc+
						// Rappel : dans une URL réécrite, la partie en dehors du GET est gérée du type String::rawurlencode (et + vaut normalement %2B), et la partie GET est gérée par urlencode (et espace vaut +)
						$uri = str_replace($this_url_rewriting_main_expression, '/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/'.$GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'].'-', str_replace(array('-' . String::rawurlencode(vn($_GET['page'])) . '-' . String::rawurlencode(vb($_GET['search'])) . '.html', '-' . vn($_GET['page']) . '-' . vb($_GET['search']) . '.html', '-' . vn($_GET['page']) . '-' . urlencode(vb($_GET['search'])) . '.html'), '-[PAGE]-' . String::rawurlencode(vb($_GET['search'])) . '.html', $uri));
						break;
					}
				}
			}
			// Compatibilité anciennes URL
			$excluded_get[] = 'subdomain';
			$excluded_get[] = 'pageNum_rs1';
		}
		foreach ($params as $key => $value) {
			if (!in_array($key, $excluded_get) && (!empty($value) || $value==='0')) {
				if(is_array($value)){
					foreach($value as $this_key => $this_value){
						$queryString[] = $key . '[' . $this_key . ']=' . urlencode($this_value);
					}
				}else{
					$queryString[] = $key . '=' . urlencode($value);
				}
			}
		}
		if (count($queryString) > 0) {
			$uri .= '?' . implode('&', $queryString);
		}
		$uri_array[$cache_key] = $uri;
	}
	return $uri_array[$cache_key];
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
		WHERE id ="' . intval($size_id) . '" AND ' . get_filter_site_cond('tailles');
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
		WHERE c.id = '" . intval(vn($color_id)) . "' AND " .  get_filter_site_cond('couleurs', 'c');
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
	if (empty($lang) && !empty($GLOBALS['lang_codes'])) {
		// pas de langue passé en paramètre. Il faut récupérer la langue principal du site. $GLOBALS['lang_codes'] est rempli selon les contraintes du site en cours, et dans le bon ordre. On peux prendre le premier éléments de ce tableau.
		$lang = $GLOBALS['lang_codes'][0];
	}
	if($general_setup) {
		// On redéfinit wwwroot proprement à partir du wwwroot théorique et des règles d'URL rewriting.
		$GLOBALS['wwwroot'] = get_lang_rewrited_wwwroot($lang);
		if(!empty($GLOBALS['site_parameters']['avoid_lang_folders_in_minified_css']) && !empty($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']]) && strpos($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']], '//') === false && strpos($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']], '.') === false) {
			// Si on veut dans le minify avoir des liens vers les fichiers sans les dossiers de langue
			$GLOBALS['apparent_folder'] = $GLOBALS['apparent_folder_main'] . $GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']];
		}
		// Maintenant que wwwroot est paramétré définitivement, on peut définir les derniers répertoires
		if (!empty($GLOBALS['site_parameters']['admin_force_ssl']) || (defined('IN_PEEL_ADMIN') && (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'))) {
			// L'administrateur consulte la page en https dans l'administration => Les liens dans la page doivent être en https également.
			$GLOBALS['wwwroot_in_admin'] = str_replace('http://', 'https://', $GLOBALS['wwwroot']);
		} else {
			$GLOBALS['wwwroot_in_admin'] = $GLOBALS['wwwroot'];
		}
		$GLOBALS['administrer_url'] = $GLOBALS['wwwroot_in_admin'] . "/" . vb($GLOBALS['site_parameters']['backoffice_directory_name']);
		// Attention : $GLOBALS['repertoire_modele'] est avec dirroot et non pas wwwroot, n'est pas forcément encore défini quand on passe ici, il ne faut donc pas l'utiliser
		$GLOBALS['repertoire_css'] = get_wwwroot_cdn('repertoire_css') . "/modeles/" . vb($GLOBALS['site_parameters']['template_directory']) . "/css";
		$GLOBALS['repertoire_images'] = get_wwwroot_cdn('repertoire_images') . "/modeles/" . vb($GLOBALS['site_parameters']['template_directory']) . "/images";
		$GLOBALS['repertoire_upload'] = get_wwwroot_cdn('repertoire_upload') . "/upload";
		$GLOBALS['repertoire_mp3'] = get_wwwroot_cdn('repertoire_mp3') . "/mp3";
		$GLOBALS['repertoire_mp3_extrait'] = get_wwwroot_cdn('repertoire_mp3_extrait') . "/mp3_extrait";
		// Paramétrage des formats de date pour les fonctions strftime()
		$main_langs = array('en' => 'en_US', 'fr' => 'fr_FR', 'de' => 'de_DE', 'es' => 'es_ES', 'it' => 'it_IT', 'pt' => 'pt_PT', 'ar' => 'ar_SA', 'el' => 'el', 'fi' => 'fi',
			'hu' => 'hu', 'bg' => 'bg', 'zh' => 'zh_cn', 'no' => 'no_no');
		if (empty($main_langs[$lang])) {
			$main_langs[$lang] = String::strtolower($lang) . '_' . String::strtoupper($lang);
		}
		// Gestion de setlocale sous windows : pour gérer les langues sous windows il faut mettre la langue sous format 3 lettres ou l'écrire en anglais
		// Voir http://www.w3schools.com/vbscript/func_setlocale.asp
		// lang_TERRITORY.codeset
		// - language is an ISO 639 language code
		// - territory is an ISO 3166 country code
		// - codeset is a character set or encoding identifier like ISO-8859-1 or UTF-8
		$variations_langs = array('en' => 'english', 'fr' => 'french', 'de' => 'german', 'es' => 'spanish', 'pt' => 'portuguese', 'it' => 'italian', 'zh' => 'chinese-simplified',
			'ja' => 'japanese', 'ru' => 'russian', 'nl' => 'dutch');
		$variations_langs2 = array('zh' => 'chi');
		setlocale(LC_TIME, $main_langs[$lang] . '.UTF8', String::strtolower($lang) . '.UTF8', vb($variations_langs[$lang], String::strtolower($lang)) . '.utf8', vb($variations_langs2[$lang], String::strtolower($lang)) . '.utf8', $main_langs[$lang], String::strtolower($lang), vb($variations_langs[$lang], String::strtolower($lang)), vb($variations_langs2[$lang], String::strtolower($lang)));
		// Déclaration du nom de la boutique
		$GLOBALS['site'] = vb($GLOBALS['site_parameters']['nom_' . $lang]);
	}
	if(!$skip_load_files) {
		if(!empty($load_default_lang_files_before_main_lang_array)){
			$successive_loads = $load_default_lang_files_before_main_lang_array;
		}
		$successive_loads[] = $lang;
		foreach(array_unique($successive_loads) as $this_lang) {
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
			if(IN_INSTALLATION || !empty($GLOBALS['installation_folder_active'])) {
				include($GLOBALS['dirroot'] . "/lib/lang/admin_install_" . $this_lang . ".php");
			}
			if($load_modules_files && !empty($GLOBALS['modules_lang_directory_array'])){
				// Les variables de langue dans les modules sont plus prioritaires que celles de lib/lang/
				// => la surcharge des valeurs par défaut est possible
				ksort($GLOBALS['modules_lang_directory_array']);
				foreach($GLOBALS['modules_lang_directory_array'] as $this_directory) {
					if(String::strpos($this_directory, $GLOBALS['dirroot']) === false) {
						$this_directory = $GLOBALS['dirroot'] . $this_directory;
					}
					if(file_exists($this_directory . $this_lang . ".php")) {
						include($this_directory . $this_lang . ".php");
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
			if ($general_setup && !IN_INSTALLATION && empty($GLOBALS['installation_folder_active'])) {
				load_site_parameters($this_lang, true);
			}
		}
		foreach($GLOBALS as $this_global => $this_value) {
			if(substr($this_global, 0, 4) == 'STR_') {
				if(!empty($GLOBALS['site_parameters']['replace_words_in_lang_files'])) {
					// Remplacement de mots clés par des versions personnalisées pour le site
					foreach($GLOBALS['site_parameters']['replace_words_in_lang_files'] as $replaced=>$new) {
						if(strpos($this_value, $replaced) !== false) {
							$this_value = str_replace($replaced, $new, $this_value);
							$GLOBALS[$this_global] = $this_value;
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
		if(!isset($GLOBALS['site_parameters']['user_mandatory_fields'])) {
			$GLOBALS['site_parameters']['user_mandatory_fields'] = array(
				'prenom' => 'STR_ERR_FIRSTNAME',
				'nom_famille' => 'STR_ERR_NAME',
				'adresse' => 'STR_ERR_ADDRESS',
				'code_postal' => 'STR_ERR_ZIP',
				'ville' => 'STR_ERR_TOWN',
				'pays' => 'STR_ERR_COUNTRY',
				'telephone' => 'STR_ERR_TEL',
				'email' => 'STR_ERR_EMAIL',
				'pseudo' => 'STR_ERR_PSEUDO',
				'token' => 'STR_INVALID_TOKEN');
			if(check_if_module_active('annonces')) {
				if(!empty($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories'])) {
					$GLOBALS['site_parameters']['user_mandatory_fields']['id_categories'] = 'STR_ERR_FIRST_CHOICE';
				} else {
					$GLOBALS['site_parameters']['user_mandatory_fields']['id_cat_1']  = 'STR_ERR_FIRST_CHOICE';
				}
				$GLOBALS['site_parameters']['user_mandatory_fields']['cgv_confirm'] = 'STR_ERR_CGV';
				$GLOBALS['site_parameters']['user_mandatory_fields']['mot_passe_confirm'] = 'STR_ERR_PASS_CONFIRM';
			}
			if(!empty($GLOBALS['site_parameters']['add_b2b_form_inputs'])) {
				$GLOBALS['site_parameters']['user_mandatory_fields']['societe'] = 'STR_ERR_SOCIETY';
				$GLOBALS['site_parameters']['user_mandatory_fields']['type'] = 'STR_ERR_YOU_ARE';
				$GLOBALS['site_parameters']['user_mandatory_fields']['activity'] = 'STR_ERR_ACTIVITY';
				$GLOBALS['site_parameters']['user_mandatory_fields']['siret'] = 'STR_ERR_SIREN';
			}
		}
		if (preg_match('/msie 6./i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/msie 7./i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/msie 8./i', $_SERVER['HTTP_USER_AGENT'])) {
			$GLOBALS['site_parameters']['used_uploader'] = 'html';
		}
		if (preg_match('/msie 6./i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/msie 7./i', $_SERVER['HTTP_USER_AGENT'])) {
			// NB : aucun support de IE6 ou 7, mais ça permet tout de même d'accéder à la homepage de l'administration sans bug javascript
			$GLOBALS['site_parameters']['chart_product'] = 'flash';
		}
	}
}

/**
 * On charge les variables de listes de langues
 *
 * @param string $site_id
 * @return
 */
function load_active_languages_list($site_id = null)
{
	// loaded_once ne sert que pour l'installation.
	static $loaded_once;
	if (!IN_INSTALLATION && empty($GLOBALS['installation_folder_active'])) {
		unset($GLOBALS['lang_codes'], $GLOBALS['admin_lang_codes'], $GLOBALS['lang_flags'], $GLOBALS['lang_names'], $GLOBALS['lang_etat'], $GLOBALS['lang_url_rewriting'], $GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'], $GLOBALS['get_lang_rewrited_wwwroot'], $GLOBALS['langs_array_by_wwwroot']);
		$select = '';
		if(!empty($site_id)) {
			$sql_or_array[] = get_filter_site_cond('langues');
			if(defined('IN_PEEL_ADMIN')) {
				// On récupère la liste des langues administrables
				$sql_or_array[] = get_filter_site_cond('langues', null, true);
				// Si une langue est sélectionnée du fait des droits sur le site administré, elle ne doit pas pour autant être autorisée en etat=1 => on recalcule la variable etat
				// Par ailleurs une langue qui est active sur le site en cours d'utilisation ne doit pas se retrouver dans la liste des langues administrables (du fait qu'on administre à ce moment-là un autre site)
				$select .= ", ".(!empty($site_id)?"IF(etat=1 AND NOT (" . get_filter_site_cond('langues') . "), -1, etat)":"etat") . " AS etat, IF(NOT (".get_filter_site_cond('langues', null, true). "), 1, 0) AS admin_disallowed";
			}
			if(empty($sql_or_array)) {
				$sql_or_array[] = "1";
			}
		} else {
			$sql_or_array[] = 1;
		}
		$sqlLng = "SELECT * ".$select."
			FROM peel_langues
			WHERE (" . implode(" OR ", $sql_or_array) . ")";
		if (!empty($GLOBALS['site_parameters']['restricted_languages_array'])) {
			// La requête va chercher toutes les langues disponibles selon la configuration en BDD. Il est possible avec restricted_languages_array de restreindre les langues retournées par la requête.
			// C'est pratique par exemple dans le cas d'un site multisite, et qu'il est nécessaire de configurer des langues pour certains sites. Il faut dans ce cas associer les langues pour 'Tous les sites' sur la page d'administration des langues, et définir la variable restricted_languages_array pour chaque site.
			$sqlLng .= " AND (lang IN ('" . implode("','", $GLOBALS['site_parameters']['restricted_languages_array']) . "'))";
		}
		$sqlLng .= "
			ORDER BY IF(etat = '1'". (!empty($_GET['langue'])?" OR lang='" . word_real_escape_string($_GET['langue']) . "'":'') . ", 1, 0) DESC, position ASC";
		$resLng = query($sqlLng);
		while ($lng = fetch_assoc($resLng)) {
			if($lng['etat'] == 1 || (!empty($_GET['langue']) && $lng['lang'] == $_GET['langue'])) {
				$GLOBALS['lang_codes'][] = $lng['lang'];
				$GLOBALS['admin_lang_codes'][] = $lng['lang'];
				if(empty($lng['admin_disallowed'])) {
					$GLOBALS['admin_lang_codes_with_modify_rights'][] = $lng['lang'];
				}
			} elseif($lng['etat'] == -1) {
				// Langue administrable mais pas en production
				$GLOBALS['admin_lang_codes'][] = $lng['lang'];
				if(empty($lng['admin_disallowed'])) {
					$GLOBALS['admin_lang_codes_with_modify_rights'][] = $lng['lang'];
				}
			}
			if(!isset($GLOBALS['lang_etat'][$lng['lang']])) {
				$GLOBALS['lang_flags'][$lng['lang']] = $lng['flag'];
				$GLOBALS['lang_names'][$lng['lang']] = $lng["nom_" . $lng['lang']];
				$GLOBALS['lang_etat'][$lng['lang']] = $lng['etat'];
				$GLOBALS['lang_url_rewriting'][$lng['lang']] = $lng["url_rewriting"];
			}
			if(!empty($lng["load_default_lang_files_before_main_lang"])) {
				$GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$lng['lang']] = explode(',', $lng["load_default_lang_files_before_main_lang"]);
			}
		}
		if(empty($GLOBALS['lang_codes'])){
			// Si on n'a pas trouvé au moins une langue, on prend les langues même inactives
			$GLOBALS['lang_codes'] = array_keys($GLOBALS['lang_etat']);
		}
		if(empty($GLOBALS['admin_lang_codes'])){
			// Si on n'a pas trouvé au moins une langue, on prend les langues même inactives
			$GLOBALS['admin_lang_codes'] = $GLOBALS['lang_codes'];
		}
		$GLOBALS['lang_codes'] = array_unique($GLOBALS['lang_codes']);
		$GLOBALS['admin_lang_codes'] = array_unique($GLOBALS['admin_lang_codes']);
		if(!empty($GLOBALS['admin_lang_codes_with_modify_rights'])) {
			$GLOBALS['admin_lang_codes_with_modify_rights'] = array_unique($GLOBALS['admin_lang_codes_with_modify_rights']);
		}
		// Initialisation de la SESSION langue
		foreach($GLOBALS['lang_codes'] as $this_lang) {
			$GLOBALS['get_lang_rewrited_wwwroot'][$this_lang] = get_lang_rewrited_wwwroot($this_lang);
			$GLOBALS['langs_array_by_wwwroot'][$GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]][] = $this_lang;
		}
	} elseif(empty($loaded_once)) {
		// Récupération des langues possibles pour l'installation
		$lang_dir = $GLOBALS['dirroot'] . "/lib/lang";
		if ($handle = opendir($lang_dir)) {
			while ($file = readdir($handle)) {
				if ($file != "." && $file != ".." && is_file($lang_dir . '/' . $file) && strtolower($file) == $file) {
					if (substr($file, 0, strlen('admin_install_')) == 'admin_install_' && substr($file, strlen('admin_install_')+2) == '.php') {
						$lng = substr($file, strlen('admin_install_'), 2);
						// Fichier du type admin_install_xx.php pour l'interface d'installation
						$GLOBALS['lang_codes'][] = $lng;
						if(!empty($GLOBALS['langs_flags_correspondance'][$lng])){
							$GLOBALS['lang_flags'][$lng] = $GLOBALS['langs_flags_correspondance'][$lng];
						} else {
							$GLOBALS['lang_flags'][$lng] = '/images/'.$lng.'.png';
						}
						if(file_exists($GLOBALS['dirroot'] . '/lib/lang/database_langues_'.$lng.'.php')) {
							include($GLOBALS['dirroot'] . '/lib/lang/database_langues_'.$lng.'.php');
						} else {
							include($GLOBALS['dirroot'] . '/lib/lang/database_langues_en.php');
						}
						if(!empty($peel_langues['nom']) && !empty($peel_langues['nom'][$lng])) {
							// Variable locale et non pas globale pour peel_langues car issue de @include($GLOBALS['dirroot'] . '/lib/lang/database_langues_'.$_SESSION['session_langue'].'.php');
							$GLOBALS['lang_names'][$lng] = $peel_langues['nom'][$lng];
						} else {
							$GLOBALS['lang_names'][$lng] = $lng;
						}
						$GLOBALS['admin_lang_codes'][$GLOBALS['lang_names'][$lng]] = $lng;
						$GLOBALS['lang_etat'][$lng] = 1;
						$GLOBALS['lang_url_rewriting'][$lng] = '';
					}elseif (substr($file, 2) == '.php') {
						$lng = substr($file, 0, 2);
						// Fichier du type xx.php pour savoir quelles langues on peut installer pour le site
						$GLOBALS['available_languages'][] = $lng;
					}
				}
			}
		}
		ksort($GLOBALS['admin_lang_codes']);
	}
	foreach($GLOBALS['lang_codes'] as $this_lang) {
		// Ajout des gros drapeaux
		if(file_exists($GLOBALS['dirroot'] . '/images/'.$this_lang.'_large.png')) {
			$GLOBALS['lang_flags_big'][$this_lang] = vb($GLOBALS['wwwroot']) . '/images/'.$this_lang.'_large.png';
		}
	}
	$loaded_once = true;
}

/**
 * Renvoyer un CDN si défini, ou à défaut wwwroot
 *
 * @param string $subject
 * @return
 */
function get_wwwroot_cdn($subject) {
	if(!empty($GLOBALS['site_parameters']['cdn_specific_domains_array']) && !empty($GLOBALS['site_parameters']['cdn_specific_domains_array'][$subject])) {
		return $GLOBALS['site_parameters']['cdn_specific_domains_array'][$subject];
	} else	if(!empty($GLOBALS['site_parameters']['cdn_generic_domain'])) {
		return $GLOBALS['site_parameters']['cdn_generic_domain'];
	} else {
		return $GLOBALS['wwwroot'];
	}
}

/**
 * On charge les variables de configuration
 * On récupère d'abord les données valables pour tous les sites, puis on surcharge avec les données valables pour le site concerné par la page demandée par l'utilisateur qui ont donc priorité
 * Et dans chacun de ces cadres, on prend d'abord les données valables pour toutes les langues, qu'on surcharge avec les données de la langue demandée
 *
 * @param string $lang
 * @param boolean $skip_loading_currency_infos
 * @param integer $forced_site_id
 * @return
 */
function load_site_parameters($lang = null, $skip_loading_currency_infos = false, $forced_site_id = null)
{
	if(empty($lang)) {
		// On récupère l'id du site si on est en multisite
		$sql = "SELECT c.site_id, c.string
			FROM peel_configuration c
			LEFT JOIN peel_langues l ON l.etat = '1' AND l.url_rewriting LIKE '%.%' AND " . get_filter_site_cond('langues', 'l', false, $forced_site_id) . "
			WHERE c.technical_code='wwwroot' AND ";
			if ($forced_site_id === null) {
				$sql .= "(c.string='".real_escape_string($GLOBALS['wwwroot'])."' OR REPLACE(c.string,'www.','')='".real_escape_string($GLOBALS['wwwroot'])."' OR (l.url_rewriting LIKE '%.%' AND (REPLACE(c.string,'www.',l.url_rewriting)='".real_escape_string($GLOBALS['wwwroot'])."' OR l.url_rewriting='".real_escape_string($GLOBALS['wwwroot'])."')))";
			} else {
				$sql .= get_filter_site_cond('configuration', 'c', false, $forced_site_id);
			}
			$sql .= "
			ORDER BY IF(c.technical_code='wwwroot',1,0) DESC, l.position ASC
			LIMIT 1";
		$query = query($sql);
		while ($result = fetch_assoc($query)) {
			// forced_site_id ne doit pas être utilisé pour définir $GLOBALS['site_id']. C'est la configuration du site qui doit déterminer cette valeur.
			$GLOBALS['site_id'] = $result['site_id'];
			$GLOBALS['wwwroot_main'] = $result['string'];
		}
		// Si aucun site n'a été trouvé en base de données correspondant à l'URL, c'est peut-être que wwwroot n'est pas défini dans la table de configuration
		// Dans ce cas on se contente de la version de wwwroot calculée dans configuration.inc.php : celle définie dans info.inc.php si pas vide, ou de wwwwroot_detected à défaut
		// On fait le test uniquement si forced_site_id n'est pas égal à 0. Si on cherche uniquement les variables de configuration général (site_id=0), la contrainte sur wwwroot ne nous intéresse pas.
		if($forced_site_id!==0 && empty($GLOBALS['site_id'])) {
			// Si la configuration multisite n'est pas trouvée
			$sql = "SELECT count(*) AS this_count
				FROM peel_configuration c
				WHERE c.technical_code='wwwroot'";
			$query = query($sql);
			$result = fetch_assoc($query);
			// this_count_wwwroot contient le nombre total de site configuré
			$this_count_wwwroot = $result['this_count'];
			
			// Il faut définir $GLOBALS['site_id']
			if ($this_count_wwwroot <=1) {
				// Si un seul ou zéro wwwroot est défini, alors la valeur de site_id est 1.
				$GLOBALS['site_id'] = 1;
			} elseif ($this_count_wwwroot > 1) {
				// plusieurs wwwroot défini dans la BDD, il faut choisir le site_id par défaut dans ce cas.
				$sql = "SELECT string
					FROM peel_configuration c
					WHERE c.technical_code='site_id_showed_by_default_if_domain_not_found'";
				$query = query($sql);
				if($result = fetch_assoc($query)) {
					$GLOBALS['site_id'] = $result['string'];
				} else {
					// il y a plusieurs sites configurés et site_id_showed_by_default_if_domain_not_found n'est pas trouvé, donc on ne peux pas choisir le site_id du site. Impossible de continuer.
					die('Site configuration not detected');
				}
			}

			// Il faut définir $GLOBALS['wwwroot_main'] 
			if ($this_count_wwwroot > 0) {
				// A ce stade $GLOBALS['site_id'] est défini et wwwroot est défini, on va chercher le wwwroot associé.
				$sql = "SELECT string
					FROM peel_configuration c
					WHERE c.technical_code='wwwroot' AND site_id='".intval($GLOBALS['site_id'])."'";
				$query = query($sql);
				if($result = fetch_assoc($query)) {
					$GLOBALS['wwwroot_main'] = $result['string'];
				} else {
					// Il y a bien un wwwroot défini dans la base de donnée, mais pas pour ce site.
					die('Site configuration not detected');
				}
			} else {
				// pas de wwwroot dans la BDD, wwwroot sera detected_wwwroot qui est défini dans configuration.inc.php.
			}
		}
		if (!defined('IN_PEEL_ADMIN') && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
			// En front office, les pages sont appelées en https => on force https dans wwwroot_main pour que toutes les ressources chargées par le site soient en https, sinon le contenu est bloqué par certain navigateur.
			$GLOBALS['wwwroot_main'] = str_replace('http://', 'https://', $GLOBALS['wwwroot_main']);
		}
	}
	// Initialisation des paramètres du site
	$sql = "SELECT *
		FROM peel_configuration
		WHERE etat='1' AND ";
	if ($forced_site_id === null) {
		$sql .= get_filter_site_cond('configuration') . " AND ";
	} else {
		$sql .= get_filter_site_cond('configuration', null, false, $forced_site_id) . " AND ";
	}
	if(empty($lang)) {
		$sql .= "lang='' AND technical_code NOT LIKE 'STR_%'";
	} else {
		$sql .= "(lang = '" . real_escape_string($lang) . "' OR lang='')";
	}
	$sql .= "
		ORDER BY IF(site_id=0, 0, 1) ASC, IF(lang='', 0, 1) ASC, technical_code ASC";
	// Chargement des paramètres de configuration (PEEL 7+)
	$query = query($sql);
	while($result = fetch_assoc($query)) {
		// On surcharge les valeurs par défaut définies plus haut dans ce fichier par celles trouvées en base de données
		if(strpos($result['technical_code'], 'chmod') !== false) {
			if($result['type'] == 'integer') {
				$result['type'] = 'octal';
			}
			if(strpos($result['technical_code'], 'file') !== false) {
				// Filtre sur file pour ne modifier que les fichier et pas les dossier. Exemple de technical code utilisé : chmod_new_files.
				// Pour la sécurité des fichiers, il faut interdire les chmod qui rendent un fichier exécutable :
				$result['string'] = str_replace(array('1','3','5','7'), array('0','2','4','6'), $result['string']);
			}
		}
		if($result['type'] == 'boolean'){
			if(in_array(String::strtolower($result['string']), array('true', 'yes', '1'))){
				$result['string'] = true;
			} elseif(in_array(String::strtolower($result['string']), array('false', 'no', '0'))){
				$result['string'] = false;
			}
		} elseif($result['type'] == 'array'){
			// Chaine du type : "key" => "value", 'key' => value, ...
			$result['string'] = get_array_from_string($result['string']);
		} elseif($result['type'] == 'integer'){
			$result['string'] = intval($result['string']);
		} elseif($result['type'] == 'float'){
			$result['string'] = floatval($result['string']);
		} elseif($result['type'] == 'octal') {
			$result['string'] = octdec(intval($result['string']));
		} elseif($result['type'] == 'string' || (empty($result['type']) && String::strpos($result['string'], ':')===false)){
			$result['string'] = str_replace(array('{$GLOBALS[\'repertoire_images\']}', '{$GLOBALS[\'wwwroot\']}', '{$GLOBALS[\'dirroot\']}', ), array(vb($GLOBALS['repertoire_images']), vb($GLOBALS['wwwroot']), $GLOBALS['dirroot']), $result['string']);
		} else {
			$result['string'] = unserialize($result['string']);
		}

		if(String::substr($result['technical_code'], 0, 4)== 'STR_') {
			// Variable de langue
			$GLOBALS[$result['technical_code']] = $result['string'];
		} else {
			if(String::strlen($result['technical_code'])== 7 && String::substr($result['technical_code'], 0, 5) == 'logo_' && strpos($result['string'], '//') === false && !empty($result['string'])) {
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
	if(!$skip_loading_currency_infos) {
		$query_devises = query("SELECT pd.devise, pd.conversion, pd.symbole, pd.symbole_place, pd.code
			FROM peel_devises pd
			WHERE pd.id = '".intval(vb($GLOBALS['site_parameters']['devise_defaut']))."' AND " . get_filter_site_cond('devises', 'pd') . "");
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
	if ((defined('IN_PEEL_ADMIN') || IN_INSTALLATION) && !empty($GLOBALS['site_parameters']['template_directory_forced_in_admin'])) {
		$GLOBALS['site_parameters']['template_directory'] = $GLOBALS['site_parameters']['template_directory_forced_in_admin'];
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
 * @param string $use_index_sql
 * @return
 */
function params_affiche_produits($condition_value1, $unused, $type, $nb_par_page, $mode = 'general', $reference_id = 0, $nb_colonnes, $always_show_multipage_footer = true, $additional_sql_inner = null, $additional_sql_cond = null, $additionnal_sql_having = null, $use_index_sql = null)
{
	$sql_cond_array = array();
	$titre = '';
	$affiche_filtre = '';
	$sql_inner = '';
	$params_list = array();
	if (!empty($nb_colonnes) && ($nb_par_page % $nb_colonnes > 0)) {
		$nb_par_page = $nb_par_page + ($nb_colonnes - ($nb_par_page % $nb_colonnes));
	}
	if ($type == 'associated_product') {
		$params_list['small_width'] = 160;
		$params_list['small_height'] = 160;
	} else {
		$params_list['small_width'] = vn($GLOBALS['site_parameters']['small_width']);
		$params_list['small_height'] = vn($GLOBALS['site_parameters']['small_height']);
	}
	$params_list['cartridge_product_css_class'] = 'item-column product_per_line_' . $nb_colonnes;
	if ($type == 'category' && function_exists('is_special_menu_items') && is_special_menu_items($condition_value1)) {
		if ($condition_value1 == 1) { 
			// On affiche le module à la carte
			$params_list['qid_carte'] = query('SELECT c.id, c.parent_id, c.nom_' . $_SESSION['session_langue'] . ' AS nom, c.description_' . $_SESSION['session_langue'] . ' AS description , c.image_' . $_SESSION['session_langue'] . ' AS image
				FROM peel_categories c
				WHERE c.etat = "1" AND c.parent_id = "1" AND ' . get_filter_site_cond('categories', 'c') . '
				ORDER BY c.position ASC, nom ASC');

			$params_list['qid_prix_carte'] = query('SELECT MIN(prix) AS prix_cat, tva
				FROM peel_produits p
				' . (!empty($GLOBALS['site_parameters']['allow_products_without_category']) ? 'LEFT' : 'INNER') . ' JOIN peel_produits_categories pc ON pc.produit_id = p.id
				' . (!empty($GLOBALS['site_parameters']['allow_products_without_category']) ? 'LEFT' : 'INNER') . ' JOIN peel_categories c ON pc.categorie_id = c.id AND ' . get_filter_site_cond('categories', 'c') . '
				WHERE c.etat = "1" AND pc.categorie_id = "4" AND ' . get_filter_site_cond('produits', 'p') . '');
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
		// Si l'option est activée dans les paramètres du site
		if (vn($GLOBALS['site_parameters']['auto_promo']) == 1) {
			// Si une promotion est appliquée au produit
			$this_sql_cond = "p.promotion>0";
			// Si le module flash est actif
			if (check_if_module_active('flash') && is_flash_active_on_site()) {
				$this_sql_cond .= " OR p.on_flash='1' AND '" . date('Y-m-d H:i:s', time()) . "' BETWEEN p.flash_start AND p.flash_end";
			}
			// Si le module Promotions par marque est actif
			if (check_if_module_active('marques_promotion')) {
				$sql_inner .= " LEFT JOIN peel_marques pm ON pm.id = p.id_marque AND " . get_filter_site_cond('marques', 'pm');
				$this_sql_cond .= " OR pm.promotion_percent>0 OR pm.promotion_devises >0 ";
			}
			// Si le module Promotions par catégorie est actif
			if (check_if_module_active('category_promotion')) {
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
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d', time()) . "' BETWEEN p.flash_start AND p.flash_end";
		$titre = $GLOBALS['STR_FLASH'];
	} elseif ($type == 'flash_passed') {
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d', time()) . "' > flash_end";
		$titre = $GLOBALS['STR_FLASH_PASSED'];
	} elseif ($type == 'user_flash_passed') {
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d', time()) . "' > flash_end AND id_utilisateur = " . intval($reference_id);
		$titre = $GLOBALS['STR_FLASH_PASSED'];
	} elseif ($type == 'coming_product_flash') {
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d', time()) . "' < flash_start";
		$titre = $GLOBALS['STR_COMING_PRODUCT_FLASH'];
	} elseif ($type == 'user_coming_product_flash') {
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d', time()) . "' <= flash_start AND id_utilisateur = " . intval($reference_id);
		$titre = $GLOBALS['STR_COMING_PRODUCT_FLASH'];
	} elseif ($type == 'check') {
		$sql_cond_array[] = "p.on_check='1'";
		$titre = $GLOBALS['STR_CHEQUE_CADEAU'];
	} elseif ($type == 'associated_product') {
		$nb_par_page = '*';
		$infos = array();
		$commande_id_array = array();
		// On vérifie si la case remontée de produit a été cochée et qu'un nombre de produits à afficher a bien été saisi
		$sql = query('SELECT on_ref_produit, nb_ref_produits
			FROM peel_produits
			WHERE id = ' . intval(vn($reference_id))." AND " . get_filter_site_cond('produits') . "");
		$infos = fetch_assoc($sql);
		if (!empty($infos)) {
			// Récupération des id des commandes dont le produit fait partie
			$sql = 'SELECT commande_id
				FROM peel_commandes_articles
				WHERE produit_id = "' . intval($reference_id) . '"  AND  ' . get_filter_site_cond('commandes_articles');
			$q = query($sql);
			while ($result = fetch_assoc($q)) {
				$commande_id_array[] = $result['commande_id'];
			}
			// Si la case a bien été cochée et qu'un nombre a été saisi et que le produit affiché a déjà été commandé
			if ($infos['on_ref_produit'] == 1 && $infos['nb_ref_produits'] > 0 && count($commande_id_array) > 0) {
				$sql_inner .= " INNER JOIN peel_commandes_articles pca ON pca.produit_id = p.id AND " . get_filter_site_cond('commandes_articles', 'pca') . "";
				$sql_cond_array[] = "pca.commande_id IN ('" . implode("','", nohtml_real_escape_string($commande_id_array)) . "')";
				$sql_cond_array[] = "p.id!=" . intval($reference_id);
				$nb_par_page = intval($infos['nb_ref_produits']);
			} else { 
				// Dans le cas contraire, on affiche les références produit associées
				$sql_cond_array[] = "pr.produit_id = '" . intval($reference_id) . "'";
				if(empty($GLOBALS['site_parameters']['product_references_display_limit']) && empty($GLOBALS['site_parameters']['product_references_order_by'])) {
					$sql_inner .= " INNER JOIN peel_produits_references pr ON p.id = pr.reference_id";
				} else {
					$sql_inner .= " INNER JOIN (SELECT * FROM peel_produits_references WHERE produit_id='" . intval($reference_id) . "' ORDER BY ".real_escape_string(vb($GLOBALS['site_parameters']['product_references_order_by'], 'reference_id ASC'))." LIMIT ".intval(vn($GLOBALS['site_parameters']['product_references_display_limit'], 10)).") pr ON p.id = pr.reference_id";
				}
			}
		}
		$titre = $GLOBALS['STR_ASSOCIATED_PRODUCT'];
	} elseif ($type == 'save_cart') {
		$sql_inner .= " INNER JOIN peel_save_cart sc ON sc.produit_id = p.id ";
		$sql_cond_array[] = "sc.id_utilisateur = '" . intval($condition_value1) . "'";
	} elseif ($type == 'convert_gift_points') {
		$titre = $GLOBALS['STR_VOIR_LISTE_CADEAU'];
		$sql_cond_array[] = "p.on_gift=1 AND on_gift_points<='".intval($_SESSION['session_utilisateur']['points'])."'";
	} elseif ($type == 'show_draft') {
		$titre = $GLOBALS['STR_MODULE_CREATE_PRODUCT_IN_FRONT_OFFICE_SORTIE_SAVE_DRAFT'];
		$params_list['show_draft'] = true;
		$sql_cond_array[] = "p.etat=0 AND id_utilisateur = '".intval($_SESSION['session_utilisateur']['id_utilisateur'])."'";
	}
	if (empty($GLOBALS['site_parameters']['allow_command_product_ongift']) && $type != 'convert_gift_points' && is_gifts_module_active()) {
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
	// Les chèques cadeaux ne sont pas associé à une catégorie.
	$join_categories = ($type != 'check' && (empty($GLOBALS['site_parameters']['allow_products_without_category']) || String::strpos(implode('', $sql_cond_array), 'c.') !== false));

	$sql = 'SELECT p.*' . ($join_categories?', c.id AS categorie_id, c.nom_' . $_SESSION['session_langue'] . ' AS categorie':'');
	if (($type == 'save_cart')) {
		$sql .= ', sc.id as save_cart_id, sc.couleur_id as saved_couleur_id, sc.taille_id as saved_taille_id, sc.id_attribut as saved_attributs_list, sc.quantite as saved_quantity ';
	}
	$sql_main = '
		FROM peel_produits p '.$use_index_sql.'
		' . ($join_categories?(!empty($GLOBALS['site_parameters']['allow_products_without_category']) ? 'LEFT' : 'INNER') . ' JOIN peel_produits_categories pc ON pc.produit_id = p.id':'') . '
		';
	$sql_main2 = ($join_categories?(!empty($GLOBALS['site_parameters']['allow_products_without_category']) ? 'LEFT' : 'INNER') . ' JOIN peel_categories c ON pc.categorie_id = c.id AND c.etat=1 AND ' . get_filter_site_cond('categories', 'c'):'');
	$sql_main3 =  '
		' . $sql_inner . '
		WHERE '.($type != 'show_draft'?'p.etat = "1" AND ':'').' ' . get_filter_site_cond('produits', 'p') . ' AND p.nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']).' != ""';

	if (!empty($sql_cond_array)) {
		$sql_main3 .= ' AND (' . implode(') AND (', array_unique($sql_cond_array)) . ')';
	}
	$sql .= $sql_main . $sql_main2 . $sql_main3;
	if ($type != 'save_cart') {
		if($join_categories && empty($GLOBALS['site_parameters']['allow_products_multiple_results_if_multiple_categories'])) {
			$sql .= ' GROUP BY p.id';
			$sql_manual_count = 'SELECT COUNT(DISTINCT p.id) AS rows_count ' . $sql_main . $sql_main3;
		} else {
			$sql_manual_count = 'SELECT COUNT(*) AS rows_count ' . $sql_main. $sql_main3;
		}
	} else {
		$sql .= ' GROUP BY save_cart_id';
		$sql_manual_count = 'SELECT COUNT(DISTINCT p.save_cart_id) AS rows_count ' . $sql_main. $sql_main3;
	}
	if (!empty($additionnal_sql_having)) {
		$sql .= ' ' . $additionnal_sql_having;
	}
	$GLOBALS['multipage_avoid_redirect_if_page_over_limit'] = true;
	if ($type == 'special') {
		$Links = new Multipage($sql, 'home', $nb_par_page, 7, 0, $always_show_multipage_footer);
	} elseif ($type == 'associated_product') {
		$Links = new Multipage($sql, 'affiche_produits_reference', $nb_par_page, 7, 0, $always_show_multipage_footer);
	} else {
		$Links = new Multipage($sql, 'affiche_produits', $nb_par_page, 7, 0, $always_show_multipage_footer, $display_multipage_template_name);
	}
	if (!empty($_GET['tri']) && !in_array($_GET['tri'], array('nom_' . $_SESSION['session_langue'], 'prix'))) {
		// Filtrage des colonnes de tri possibles
		$_GET['tri'] = 'p.nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']);
	}
	if(!empty($GLOBALS['site_parameters']['sql_count_avoid_found_rows'])) {
		$Links->sql_count = $sql_manual_count;
	}
	$Links->order_get_variable = vb($GLOBALS['order_get_variable'], 'tri');
	$Links->sort_get_variable = 'sort';
	$Links->OrderDefault = 'position';
	$Links->SortDefault = 'ASC';
	
	//$Links->forced_second_order_by_string = 'p.id DESC';
	$Links->forced_before_first_order_by_string = null;
	if ($type == 'category' && vb($GLOBALS['site_parameters']['category_count_method']) == 'global' && $join_categories && !empty($condition_value1)) {
		$Links->forced_before_first_order_by_string .= 'IF(pc.categorie_id="'.intval($condition_value1).'", 1, 0) DESC';
	}
	if ($type == 'save_cart') {
		if(!empty($Links->forced_before_first_order_by_string)) {
			$Links->forced_before_first_order_by_string .= ', ';
		}
		$Links->forced_before_first_order_by_string .= 'save_cart_id DESC';
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
 * @param mixed $ip
 * @return
 */
function is_user_bot($ip = null, $user_agent = null)
{
	static $result;
	if($ip === null) {
		$ip = vb($_SERVER['REMOTE_ADDR']);
	}
	if($user_agent === null) {
		$user_agent = vb($_SERVER['HTTP_USER_AGENT']);
	}
	if(!isset($result)){
		// Premier test rapide sur user_agent
		$result = false;
		$lower_user_agent = String::strtolower($user_agent);
		if(!empty($user_agent)) {
			foreach(array('mediapartners-google', 'googlebot', 'google page speed', 'feedfetcher', 'slurp', 'bingbot', 'msnbot', 'voilabot', 'baiduspider', 'genieo', 'sindup', 'ahrefsbot', 'yandex', 'spider', 'robot', '/bot', 'crawler', 'netvibes') as $this_name) {
				$result = $result || String::strpos($lower_user_agent, $this_name) !== false;
			}
		}
		if(!$result) {
			// Second test un peu plus lent sur IP
			// Cette liste d'IP n'est pas exhaustive et reprséente des IP de moteurs de recherche
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

	$data = String::file_get_contents_utf8($filename);
	$parser = xml_parser_create();
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, $data, $values, $tags);
	xml_parser_free($parser);
	// loop through the structures
	$filter_array = explode('|', $filter_string);
	if(!empty($tags['title'])) {
		foreach ($tags['title'] as $tag_key => $value_key) {
			$titles_array[$tag_key] = $values[$value_key]['value'];
		}
	}
	if(!empty($tags['link'])) {
		foreach ($tags['link'] as $tag_key => $value_key) {
			$links_array[$tag_key] = $values[$value_key]['value'];
		}
	}
	if (!empty($titles_array)) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('xml_value.tpl');
		$tpl_links = array();
		
		$i = 0;
		foreach ($titles_array as $key => $this_title) {
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
		$output .= $tpl->fetch();
	}
	return $output;
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
		$error = $GLOBALS["STR_UPLOAD_ERROR_YOU_UPLOAD_NOTHING"];
	} elseif (!empty($file_infos['error'])) {
		// Si fichier a essayé d'être téléchargé
		$error = $GLOBALS["STR_UPLOAD_ERROR_DURING_TRANSFER"];
	} elseif ($file_infos['size'] > $GLOBALS['site_parameters']['uploaded_file_max_size']) {
		$error = sprintf($GLOBALS["STR_UPLOAD_ERROR_FILE_IS_TOO_BIG"], round($GLOBALS['site_parameters']['uploaded_file_max_size'] / 1024));
	} elseif (!is_uploaded_file($file_infos['tmp_name'])) {
		$error = $GLOBALS["STR_UPLOAD_ERROR_DURING_TRANSFER"];
	} elseif ($GLOBALS['site_parameters']['check_allowed_types'] && !isset($GLOBALS['site_parameters']['allowed_types'][$file_infos['type']])) {
		// Vérification du type de fichier uploadé
		$error = sprintf($GLOBALS["STR_UPLOAD_ERROR_FILE_NOT_ALLOWED"], $file_infos['type']);
	} elseif (!in_array($extension, $GLOBALS['site_parameters']['extensions_valides_'.$file_kind])) {
		// Vérification de l'extension de fichier uploadé
		$error = $GLOBALS["STR_UPLOAD_ERROR_FILE_TYPE_NOT_VALID"];
	} elseif (!empty($GLOBALS['site_parameters']['extensions_valides_image']) && in_array($extension, $GLOBALS['site_parameters']['extensions_valides_image'])) {
		// Quand on passe ici, Bonne extension d'un fichier qui est une image
		if (!empty($GLOBALS['site_parameters']['upload_oversized_images_forbidden'])) {
			// SECTION DESACTIVEE PAR DEFAUT car les grandes images sont habituellement redimensionnées
			// A ACTIVER avec la variable upload_oversized_images_forbidden à true SI ON VEUT EMPECHER UPLOAD D'IMAGE NECESSITANT UN REDIMENSIONNEMENT
			list($width, $height, $type, $attr) = getimagesize($file_infos['tmp_name']);
			if ($width > $GLOBALS['site_parameters']['image_max_width']) {
				$error .= sprintf($GLOBALS["STR_UPLOAD_ERROR_IMAGE_MUST_NOT_BE_LARGER_THAN"], $GLOBALS['site_parameters']['image_max_width']);
			}
			if ($height > $GLOBALS['site_parameters']['image_max_height']) {
				// NE PAS ACTIVER car les grandes images sont redimensionnées
				$error .= sprintf($GLOBALS["STR_UPLOAD_ERROR_IMAGE_MUST_NOT_BE_HIGHER_THAN"], $GLOBALS['site_parameters']['image_max_height']);
			}
		}
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
function upload($field_name, $rename_file = true, $file_kind = null, $image_max_width = null, $image_max_height = null, $path = null, $new_file_name_without_extension = null, $default_return_value = null)
{
	if (empty($path)) {
		$path = $GLOBALS['uploaddir'] . '/';
	}
	if (is_array($field_name)) {
		// Compatibilité anciennes versions PEEL < 7.0
		if (!empty($field_name)) {
			// $field_name contient $_FILES[$field_name]
			$file_infos = $field_name;
		} else {
			// Rien à télécharger
			return $default_return_value;
		}
	} elseif (!empty($_REQUEST[$field_name])) {
		// Fichier déjà existant et téléchargé par le passé
		if(strpos($_REQUEST[$field_name], '/' . $GLOBALS['site_parameters']['cache_folder'] . '/') === 0) {
			// Le fichier a été chargé en cache : on va le déplacer et le changer de nom
			$file_infos['name'] = basename($_REQUEST[$field_name]);
		} else {
			// Le fichier est à sa place, on renvoie simplement le nom déjà existant
			return $_REQUEST[$field_name];
		}
	} elseif (empty($_FILES[$field_name]['name'])) {
		// Rien à télécharger, ni de fichier existant
		return $default_return_value;
	} else {
		// On procède à un téléchargement
		$file_infos = $_FILES[$field_name];
		// Teste la validité du téléchargement
		$error = get_upload_errors_text($_FILES[$field_name], $file_kind); 
	}
	if (empty($error) && !empty($file_infos['name'])) {
		// Upload OK
		// Extension du fichier téléchargé
		$extension = String::strtolower(pathinfo($file_infos['name'], PATHINFO_EXTENSION));
		if (empty($new_file_name_without_extension)) {
			// Si aucun nom forcé, on en crée un
			$new_file_name_without_extension = format_filename_base(vb($file_infos['name']), $rename_file);
		}
		$the_new_file_name = $new_file_name_without_extension . '.' . $extension;
		if(!isset($file_infos['tmp_name'])) {
			// Fichier temporaire stocké dans le cache, on va le déplacer dans le répertoire $path avec le bon nom
			// Si il est déjà déplacé (car on revalide une seconde fois le formulaire), on s'arrange pour que ça marche aussi - dans ce cas on ne passe pas dans le test qui suit
			if($GLOBALS['dirroot'] . $_REQUEST[$field_name] != $path . $the_new_file_name && file_exists($GLOBALS['dirroot'] . $_REQUEST[$field_name])) {
				rename($GLOBALS['dirroot'] . $_REQUEST[$field_name], $path . $the_new_file_name);
			}
			if(!file_exists($path . $the_new_file_name)) {
				// Fichier inexistant ou échec du déplacement => on annule le souvenir de ce fichier
				return $default_return_value;
			}
			return $the_new_file_name;
		} elseif (move_uploaded_file($file_infos['tmp_name'], $path . $the_new_file_name)) {
			// Le fichier est maintenant dans le répertoire des téléchargements
			if(!empty($GLOBALS['site_parameters']['chmod_new_files'])) {
				@chmod ($path . $the_new_file_name, $GLOBALS['site_parameters']['chmod_new_files']);
			}
			if (!empty($GLOBALS['site_parameters']['extensions_valides_image']) && in_array($extension, $GLOBALS['site_parameters']['extensions_valides_image']) && !empty($image_max_width) && !empty($image_max_height)) {
				// Les fichiers image sont convertis en jpg uniquement si nécessaire - sinon on garde le fichier d'origine
				$the_new_jpg_name = $new_file_name_without_extension . '.jpg';
				// On charge l'image, et si sa taille est supérieure à $destinationW ou $destinationH, ou si elle fait plus de $GLOBALS['site_parameters']['filesize_limit_keep_origin_file'] octets, on doit la régénèrer (sinon on la garde telle qu'elle était)
				// Si on est dans le cas où on la regénère, on la convertit en JPEG à qualité $GLOBALS['site_parameters']['jpeg_quality'] % (par défaut dans PHP c'est 75%, et dans PEEL on utilise 88% par défaut) et on la sauvegarde sous son nouveau nom
				$result = image_resize($path . $the_new_file_name, $path . $the_new_jpg_name, $image_max_width, $image_max_height, false, true, $GLOBALS['site_parameters']['filesize_limit_keep_origin_file'], $GLOBALS['site_parameters']['jpeg_quality']);
				if (!empty($result)) {
					return basename($result);
				} else {
					$tpl = $GLOBALS['tplEngine']->createTemplate('global_error.tpl');
					$tpl->assign('message', $GLOBALS['STR_ERROR_DECOD_PICTURE']);
					$error = $tpl->fetch();
				}
			} else {
				return $the_new_file_name;
			}
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('global_error.tpl');
			$tpl->assign('message', $GLOBALS['STR_ERROR_SOMETHING_PICTURE'] . ' ' .$path);
			$error = $tpl->fetch();
		}
	}
	if (!empty($error)) {
		$GLOBALS['error_text_to_display'] = $error;
		return $default_return_value;
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
	if (a_priv('demo')) {
		return false;
	}
	// Protection : ne pas prendre autre chose qu'un nom de fichier
	$filename = str_replace(array('/', '.htaccess'), '', $filename);
	$extension = @pathinfo($filename , PATHINFO_EXTENSION);
	$nom = @basename($filename, '.' . $extension);
	$thumbs_array = @glob($GLOBALS['uploaddir'] . '/thumbs/' . $nom . "-????.jpg");
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
 * Si vous utilisez php-fpm, la durée des downloads de fichiers venant de votre site sera limitée par request_terminate_timeout et max_execution_time.
 * À lire : http://www.php.net/manual/fr/install.fpm.configuration.php
 *
 * @param string $filename_with_realpath
 * @param boolean $serve_download_with_php
 * @param string $file_content_given Le contenu peut être donné dans une variable ce qui désactive la lecture du fichier sur le disque
 * @param string $file_name_given Optionnel : nom du fichier vu par la personne qui télécharge. A défaut, nom du fichier sur le serveur.
 * @param boolean $force_download
 * @return
 */

function http_download_and_die($filename_with_realpath, $serve_download_with_php = true, $file_content_given = null, $file_name_given = null, $force_download = true)
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
		@ignore_user_abort(true);
		@set_time_limit(0);
		if (!empty($file_content_given)) {
			$content_length = strlen($file_content_given);
		} else {
			$content_length = filesize($filename_with_realpath);
		}
		if(empty($file_name_given)) {
			$file_name_given = basename($filename_with_realpath);
		}
		if($force_download) {
			header('Content-Description: File Transfer');
		}
		header("Pragma: no-cache");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		// force download dialog
		if($force_download) {
			header('Content-Type: application/force-download');
			header('Content-Type: application/octet-stream', false);
			header('Content-Type: application/download', false);
			$content_disposition = 'attachment';
		} else {
			$content_disposition = 'inline';
		}
		header("Content-Type: " . $type . "", false);
		header("Content-disposition: " . $content_disposition . "; filename=\"" . rawurlencode($file_name_given) . "\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . intval($content_length));
		ob_clean();
		flush();
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
function get_site_domain($return_only_domains = false, $domain = null, $strip_subdomain = true)
{
	if(strpos($GLOBALS['wwwroot'], '://127.0.0.1')!==false) {
		return $_SERVER["HTTP_HOST"];
	} elseif (empty($domain)) {
		$domain = $_SERVER["HTTP_HOST"];
	} else {
		$domain = str_replace(array('http://', 'https://', '://', '//'), '', $domain);
		$temp = explode('/', $domain, 2);
		$domain = $temp[0];
	}
	$temp = explode('.', $domain);
	if(count($temp)>1 && (count($temp)!=4 || !is_numeric(str_replace('.','',$domain)))) {
		// Ce n'est pas une IP, ni localhost ou un nom de machine => c'est un domaine avec potentiellement un (sous-)sous-domaine
		if(in_array($temp[count($temp)-2], array('com', 'org', 'co'))) {
			// Domaine en .co.uk, .com.sb, .org.uk, etc.
			$temp[count($temp)-2] = $temp[count($temp)-2] . '.' . $temp[count($temp)-1];
			unset($temp[count($temp)-1]);
		}
		if ($strip_subdomain) {
			return $temp[count($temp)-2].'.'.$temp[count($temp)-1];
		} else {
			return $temp[count($temp)-3].'.'.$temp[count($temp)-2].'.'.$temp[count($temp)-1];
		}
	} elseif(!$return_only_domains) {
		return $domain;
	} else {
		return false;
	}
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
			$notmod = 'NOT';
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
		// Si notmod est actif, il faut utiliser AND pour que l'exclusion s'applique
		$conditions_array[] = '(' . implode((empty($notmod)?' OR ':' AND '), $this_term_conditions_array) . ')';
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
		WHERE ((action = "PHONE_EMITTED") OR (action = "PHONE_RECEIVED")) AND data="NOT_ENDED_CALL" AND ' . get_filter_site_cond('admins_actions') . '
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
			WHERE email = "' . nohtml_real_escape_string($mail) . '" AND ' . get_filter_site_cond('utilisateurs') . '');
		if ($data = fetch_assoc($q)) {
			query('UPDATE peel_utilisateurs
				SET newsletter = "0"
				WHERE id_utilisateur=' . intval($data['id_utilisateur']) . ' AND ' . get_filter_site_cond('utilisateurs') . '');
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
 * boolean $html_page
 * @return
 */
function close_page_generation($html_page = true)
{
	$output = '';

	if (is_module_banner_active()) {
		update_viewed_banners();
	}
	if (check_if_module_active('annonces')) {
		update_viewed_ads();
	}
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && check_if_module_active('stock_advanced')) {
		// Traitement des stocks périmés
		efface_stock_perime();
	}
	if(!empty($GLOBALS['site_parameters']['force_systematic_user_session_reload']) && est_identifie()) {
		// Si on veut forcer à chaque chargement de page la mise à jour des droits des utilisateurs
		$q = query('SELECT priv
			FROM peel_utilisateurs
			WHERE id_utilisateur = "'.intval($_SESSION['session_utilisateur']['id_utilisateur']).'" AND ' . get_filter_site_cond('utilisateurs'));
		if($result = fetch_assoc($q)) {
			$_SESSION['session_utilisateur']['priv'] = $result['priv'];
		} else {
			unset($_SESSION['session_utilisateur']);
		}
	}
	// Evite de devoir lancer un cron pour optimisations et nettoyages divers
	// On fait des tests séparés pour ne pas tout lancer d'un coup, mais répartir au mieux
	$GLOBALS['contentMail'] = '';
	if (mt_rand(1, 10000) == 5000 && !check_if_module_active('crons')) {
		optimize_Tables();
	}
	if (mt_rand(1, 10000) == 5000 && !check_if_module_active('crons')) {
		clean_utilisateur_connexions();
	}
	if (mt_rand(1, 10000) == 5000 && !check_if_module_active('crons')) {
		clean_admins_actions();
	}
	if (mt_rand(1, 10000) == 5000 && !check_if_module_active('crons')) {
		clean_Cache();
	}
	if (mt_rand(1, 10000) == 5000 && !check_if_module_active('crons') && is_captcha_module_active()) {
		clean_security_codes();
	}
	if ($html_page && defined('PEEL_DEBUG') && PEEL_DEBUG == true) {
		// Affichage des infos de pseudo cron remplies par les fonctions ci-dessus
		$output .= $GLOBALS['contentMail'];
	}
	db_close();
	
	if(!$html_page && !empty($GLOBALS['site_parameters']['google_analytics_site_code_for_nohtml_pages']) && !is_user_bot()) {
		// On appelle directement Google analytics pour déclarer qu'une page a été vue, car la page n'est pas en HTML et donc on ne peut faire exécuter par le client du javascript ou faire appeler une image d'un pixel
		$var_cookie=10000000+(hexdec(session_id())%89999999); // rand(10000000,99999999);//random cookie number
		$var_random=rand(1000000000,2147483647); //number under 2147483647
		$var_today=time(); //today
		$visitorId = '0x' . substr (md5(vb($_SERVER['REMOTE_ADDR'])),0,16);
		$var_uservar='-'; //enter your own user defined variable
		if(!empty($_COOKIE['__utma'])) {
			$utma = $_COOKIE['__utma'];
			$temp = explode('.', $utma);
			$var_cookie = $temp[0];
		} else {
			$utma = ''.$var_cookie.'.'.$var_random.'.'.$var_today.'.'.$var_today.'.'.$var_today.'.2';
		}
		if(!empty($_COOKIE['__utmb'])) {
			$utmb = $_COOKIE['__utmb'];
		} else {
			$utmb = $var_cookie;
		}
		if(!empty($_COOKIE['__utmc'])) {
			$utmc = $_COOKIE['__utmc'];
		} else {
			$utmc = $var_cookie;
		}
		if(!empty($_COOKIE['__utmz'])) {
			$utmz = $_COOKIE['__utmz'];
		} else {
			$utmz = ''.$var_cookie.'.'.$var_today.'.2.2.utmccn%3D(direct)%7Cutmcsr%3D(direct)%7Cutmcmd%3D(none)';
		}
		if(!empty($_COOKIE['__utmv'])) {
			$utmv = $_COOKIE['__utmv'];
		} else {
			$utmv = $var_cookie.'.'.$var_uservar;
		}
		$utm_get[] = "utmwv=" . urlencode(isset($_COOKIE['__utmwv'])?$_COOKIE['__utmwv']:'1');
		$utm_get[] = "utmn=" . urlencode(rand(1000000000,9999999999));
		$utm_get[] = "utmhn=" . urlencode(get_site_domain(true));
		$utm_get[] = "utmr=" . urlencode(vb($_SERVER['HTTP_REFERER']));
		$utm_get[] = "utmp=" . urlencode($_SERVER['REQUEST_URI']);
		$utm_get[] = "utmac=" . urlencode(str_replace('UA-', 'MO-', $GLOBALS['site_parameters']['google_analytics_site_code_for_nohtml_pages']));
		$utm_get[] = "utmcc=__utma%3D" . urlencode($utma) . '%3B%2B__utmb%3D' . urlencode($utmb) . '%3B%2B__utmc%3D' . urlencode($utmc) . '%3B%2B__utmz%3D' . $utmz . '%3B%2B__utmv%3D' . urlencode($utmv) . "%3B";
		$utm_get[] = "utmvid=" . urlencode(isset($_COOKIE['__utmvid'])?$_COOKIE['__utmvid']:$visitorId);
		$utm_get[] = "utmip=" . urlencode(substr(vb($_SERVER["REMOTE_ADDR"]), 0, strrpos(vb($_SERVER["REMOTE_ADDR"]), '.')).'.0');
		$utm_get[] = "utmsr=" . urlencode(isset($_COOKIE['__utmsr'])?$_COOKIE['__utmsr']:'-');
		$utm_get[] = "utmsc=" . urlencode(isset($_COOKIE['__utmsc'])?$_COOKIE['__utmsc']:'-');
		$utm_get[] = "utmul=" . urlencode(isset($_COOKIE['__utmul'])?$_COOKIE['__utmul']:'-');
		$utm_get[] = "utmje=" . urlencode(isset($_COOKIE['__utmje'])?$_COOKIE['__utmje']:'0');
		$utm_get[] = "utmfl=" . urlencode(isset($_COOKIE['__utmfl'])?$_COOKIE['__utmfl']:'-');
		$utm_get[] = "guid=on";
		$utm_get[] = "utmdt=" . urlencode(isset($GLOBALS['meta_title'])?$GLOBALS['meta_title']:'-');
		$handle = fopen ('http://www.google-analytics.com/__utm.gif?'.implode('&', $utm_get), "r");
		$test = fgets($handle);
		fclose($handle);
	}
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
 * @param string $filename_beginning
 * @return
 */
function clean_Cache($days_max = 15, $filename_beginning = null)
{
	$files_deleted = nettoyer_dir($GLOBALS['dirroot'] . '/' . $GLOBALS['site_parameters']['cache_folder'], 3600 * 24 * $days_max, $filename_beginning);
	if(!empty($GLOBALS['contentMail'])) {
		$GLOBALS['contentMail'] .= 'Suppression des fichiers de plus de ' . $days_max . ' jours dans le dossier ' . $dir . '/ : ';
		$GLOBALS['contentMail'] .= 'Ok - ' . $files_deleted . ' fichiers supprimés' . "\r\n\r\n";
	}
}

/**
 * Effacement des fichiers trouvés répondant aux critères en argument, en effaçant récusivement le contenu des dossiers mais sans effacer les dossiers eux-mêmes
 *
 * @param string $dir
 * @param integer $older_than_seconds
 * @param string $filename_beginning
 * @param string $create_files_array_found_instead_of_delete
 */
function nettoyer_dir($dir, $older_than_seconds = 3, $filename_beginning = null, $create_files_array_found_instead_of_delete = false)
{
	if (a_priv('demo')) {
		return false;
	}
	$files_deleted = 0;
	if(String::substr($dir, -1) == '/') {
		$dir = String::substr($dir, 0, String::strlen($dir) - 1);
	}
	if ($dir != $GLOBALS['dirroot'] && is_dir($dir) && ($dossier = opendir($dir))) {
		while (false !== ($file = readdir($dossier))) {
			if ($file != '.' && $file != '..' && $file[0] != '.' && filemtime($dir . '/' . $file) < time() - $older_than_seconds && is_file($dir . '/' . $file) && (empty($filename_beginning) || strpos($file, $filename_beginning) === 0)) {
				// On efface les fichiers vieux de plus de $older_than_seconds secondes et qui ne sont pas des .htaccess
				if($create_files_array_found_instead_of_delete) {
					$GLOBALS['files_found_in_folder'][] = $file;
				} else {
					unlink($dir . '/' . $file);
				}
				$files_deleted++;
			} elseif ($file != '.' && $file != '..' && is_dir($dir . '/' . $file)) {
				// On efface récursivement le contenu des sous-dossiers
				$files_deleted += nettoyer_dir($dir . '/' . $file, $older_than_seconds, $filename_beginning);
			}
		}
	}
	return $files_deleted;
}

/**
 * Suppression des anciennes infos de connexion utilisateurs
 * Fonction associée à une notion de nettoyage automatisé de données, comme une fonction technique de cron. L'application de get_filter_site_cond est hors sujet pour cette fonction.
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
 * Suppression des anciennes actions administrateur
 * Fonction associée à une notion de nettoyage automatisé de données, comme une fonction technique de cron. L'application de get_filter_site_cond est hors sujet pour cette fonction.
 *
 * @param integer $days_max
 * @return
 */
function clean_admins_actions($days_max = 1460)
{
	$GLOBALS['contentMail'] .= 'Suppression des actions administrateur de plus de ' . $days_max . ' jours dans la BDD : ';
	// On supprime tout ce qui dépasse 4 ans
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
 * @param integer $compter_char_max
 * @param string $placeholder
 * @return string HTML généré
 */
function getTextEditor($instance_name, $width, $height, $default_text, $default_path = '../', $type_html_editor = 0, $compter_char_max = 255, $placeholder = '')
{
	$output = '';
	if (is_numeric($width)) {
		$width_css = $width . 'px';
		$cols = $width / 12;
	} else {
		$width_css = $width;
		$cols = 50;
	}
	if (!empty($type_html_editor)) {
		// Editeur choisi en paramètre de la fonction
		$this_html_editor = vb($type_html_editor);
	} else {
		// Editeur sélectionné depuis la configuration du site
		$this_html_editor = vn($GLOBALS['site_parameters']['html_editor']);
	}
	if ($this_html_editor == '1') {
		// Editeur nicEditor
		if(empty($GLOBALS['html_editor_loaded'])) {
			$GLOBALS['html_editor_loaded'] = true;
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot'] . '/lib/nicEditor/nicEdit.js';
		}
		$GLOBALS['js_ready_content_array'][] = '
bkLib.onDomLoaded(function() {
	new nicEditor({iconsPath : \'' . $GLOBALS['wwwroot'] . '/lib/nicEditor/nicEditorIcons.gif\',fullPanel : true}).panelInstance(\'' . $instance_name . '\');
});
';
		$output .= '
<div style="width:' . $width . '; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
	<div style="min-width:200px">
		<textarea name="' . $instance_name . '" id="' . $instance_name . '" style="width:' . $width_css . '; height:' . $height . 'px" rows="' . ($height / 12) . '" cols="' . $cols . '">' . String::htmlentities($default_text) . '</textarea>
	</div>
</div>
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
		$output .= '
<div style="width:' . $width . '; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
	<div style="min-width:550px">
		'.$oFCKeditor->CreateHtml().'
	</div>
</div>
';
	} elseif ($this_html_editor == '3') {
		$default_text = String::nl2br_if_needed($default_text);
		// Editeur CKeditor
		include_once($GLOBALS['dirroot'] . "/lib/ckeditor/ckeditor.php");
		$config = array('width' => $width, 'height' => $height);
		$CKEditor = new CKEditor($GLOBALS['wwwroot'] . '/lib/ckeditor/');
		$CKEditor->returnOutput = true;
		$output .= '
<div style="width:' . $width . '; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
	<div style="min-width:270px">
		'.$CKEditor->editor($instance_name, String::htmlspecialchars_decode($default_text, ENT_QUOTES), $config).'
	</div>
</div>
';
	} elseif ($this_html_editor == '4') {
		$default_text = String::nl2br_if_needed($default_text);
		// Editeur TinyMCE
		if(empty($GLOBALS['html_editor_loaded'])) {
			$GLOBALS['html_editor_loaded'] = true;
			$css_files = array();
			if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
				$css_files[] = $GLOBALS['wwwroot'] . '/lib/css/bootstrap.css';
			}
			if(!empty($GLOBALS['site_parameters']['css'])) {
				foreach (get_array_from_string($GLOBALS['site_parameters']['css']) as $this_css_filename) {
					if(file_exists($GLOBALS['repertoire_modele'] . '/css/' . trim($this_css_filename))) {
						$css_files[] = $GLOBALS['repertoire_css'] . '/' . trim($this_css_filename); // .'?'.time()
					}
				}
			}
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot'] . '/lib/tiny_mce/jquery.tinymce.js';
			$GLOBALS['js_ready_content_array'][] = '
		$("textarea.tinymce").tinymce({
			// Location of TinyMCE script
			script_url : "' . $GLOBALS['wwwroot'] . '/lib/tiny_mce/tiny_mce.js",

			// General options
			theme : "advanced",
			plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
			entity_encoding : "raw",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			width: \''.$width.'\',
			height: \''.$height.'\',

			// Example content CSS (should be your site CSS)
			content_css : "'.implode(',', $css_files).'",
		});
';
		}
		$output .= '
<div style="width:' . $width . '; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
	<div style="min-width:600px">
		<textarea class="tinymce" name="' . $instance_name . '" id="' . $instance_name . '" style="width:' . $width_css . '; height:' . $height . 'px" rows="' . ($height / 12) . '" cols="' . $cols . '">' . String::htmlentities($default_text) . '</textarea>
	</div>
</div>
';
	} elseif($this_html_editor == '5') {
		// Champ textarea de base + Compteur de caractères
		$output .= '
			<textarea class="form-control" placeholder="'. $placeholder.'" name="' . $instance_name . '" cols="' . $cols . '" rows="' . ($height / 12) . '" onfocus="Compter(this,'.$compter_char_max.',compteur, true)" onkeypress="Compter(this,'.$compter_char_max.',compteur, true)" onkeyup="Compter(this,'.$compter_char_max.',compteur, true)" onblur="Compter(this,'.$compter_char_max.',compteur, true)">' . String::htmlentities($default_text) . '</textarea><br />
			<div class="compteur_contener"><span style="margin:5px;">'.$GLOBALS['STR_REMINDING_CHAR'].'</span><input class="form-control compteur" type="number" name="compteur" size="4" onfocus="blur()" value="0" /></div>
';
	} else {
		// Champ textarea de base
		$output .= '
			<textarea name="' . $instance_name . '" id="' . $instance_name . '" style="width:' . $width_css . '; height:' . $height . 'px" rows="' . ($height / 12) . '" cols="' . $cols . '">' . String::htmlentities($default_text) . '</textarea>
';
	}
	return $output;
}

/**
 * Ajoute la zone HTML dans la table peel_configuration
 *
 * @param array $frm Array with all fields data
 * @param boolean $update_if_technical_code_exists
 * @param boolean $allow_create
 * @return
 */
function set_configuration_variable($frm, $update_if_technical_code_exists = false, $allow_create = true)
{
	if(!isset($frm['etat'])) {
		$frm['etat'] = 1;
	}
	if(!isset($frm['site_id'])) {
		if(defined('IN_PEEL_ADMIN') && isset($_SESSION['session_admin_multisite'])) {
			// Par défaut, s'applique au site en cours d'utilisation
			$frm['site_id'] = $_SESSION['session_admin_multisite'];
		} else {
			$frm['site_id'] = $GLOBALS['site_id'];
		}
	}
	if($update_if_technical_code_exists && !empty($frm['technical_code'])) {
		// On récupère la ligne si elle existe pour ce site_id et pas un autre
		$sql = "SELECT id
			FROM peel_configuration
			WHERE technical_code = '" . real_escape_string($frm['technical_code']) . "' AND " . get_filter_site_cond('configuration', null, false, vb($frm['site_id']), true);
		$qid = query($sql); 
		if ($select = fetch_assoc($qid)) {
			// Elément déjà existant, qu'on met à jour
			update_configuration_variable($select['id'], $frm);
			return true;
		}
	}
	if($allow_create) {
		// La création d'un nouveau paramètre n'est pas souhaité à chaque fois, afin d'éviter des doublons.
		if(in_array($frm['string'], array('true', 'false'))) {
			$frm['type'] = 'boolean';
		} elseif(!isset($frm['type'])) {
			$frm['type'] = 'string';
		}
		$sql = "INSERT INTO peel_configuration (etat, technical_code, type, string, last_update, origin, lang, `explain`, `site_id`)
			VALUES ('" . intval($frm['etat']) . "', '" . nohtml_real_escape_string($frm['technical_code']) . "', '" . real_escape_string($frm['type']) . "', '" . real_escape_string(vb($frm['string'])) . "', '" . date('Y-m-d H:i:s', time()) . "', '" . nohtml_real_escape_string(vb($frm['origin'])) . "', '" . nohtml_real_escape_string(vb($frm['lang'])) . "', '" . nohtml_real_escape_string(vb($frm['explain'])) . "', '" . nohtml_real_escape_string(vb($frm['site_id'])) . "')";
		// MAJ pour la page en cours de génération
		$GLOBALS['site_parameters'][$frm['technical_code']] = vb($frm['string']);
		return query($sql);
	} else {
		return null;
	}
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
			".(isset($frm['site_id'])?", `site_id` = '" . intval($frm['site_id']) . "'":"")."
		WHERE ";
	if(is_numeric($id_or_technical_code)) {
		$sql .= "id = '" . intval($id_or_technical_code) . "'";
	} else {
		$sql .= "technical_code = '" . real_escape_string($id_or_technical_code) . "'";
	}
	$sql .= " AND " . get_filter_site_cond('configuration', null, true);
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
	//if(a_priv('admin*')) { var_dump($files_array); }
	if($files_type == 'css') {
		// NB : Si on utilise un CDN pour repertoire_css, ce CDN va être utilisé pour les autres dossiers qui contiennent des CSS également
		$this_wwwroot = get_wwwroot_cdn('repertoire_css');
	} else {
		$this_wwwroot = $GLOBALS['wwwroot'];
	}
	if($files_type == 'js') {
		// Pour des raisons de compatibilité, on n'applique pas de minified sur les fichiers contenant ces chaines de caractères
		$excluded_files = array('prototype.js', 'controls.js', 'effects.js');
		$included_files = array('datepicker');
	} elseif($files_type == 'css') {
		if(!empty($GLOBALS['site_parameters']['minify_css_exclude_array'])) {
			$excluded_files = $GLOBALS['site_parameters']['minify_css_exclude_array'];
		}
	}
	$original_files_array = $files_array;
	foreach($files_array as $this_key => $this_file) {
		if(String::substr($this_file, 0, 2) == '//') {
			// Gestion des chemins de fichiers http/https automatiques
			$this_file = 'http:' . $this_file;
		} elseif(String::strpos($this_file, '//') === false && String::substr($this_file, 0, 1) == '/') {
			// Chemin absolu
			$this_file = $this_wwwroot . $this_file;
		} elseif(String::strpos($this_file, '//') === false) {
			// Chemin relatif car le nom du fichier ne commence pas par / => par défaut on considère qu'il est dans le répertoire CSS dans modeles/
			$this_file = $GLOBALS['repertoire_css'] . '/' . $this_file;
		} 
		if($GLOBALS['wwwroot'] != $this_wwwroot) {
			$this_file = str_replace($GLOBALS['wwwroot'], $this_wwwroot, $this_file);
		}
		$files_array[$this_key] = $this_file = String::html_entity_decode($this_file);
		unset($skip);
		if(!empty($excluded_files)) {
			foreach($excluded_files as $this_excluded_file) {
				if(strpos($this_file, $this_excluded_file) !== false) {
					$avoid_skip = false;
					if(!empty($included_files)) {
						foreach($included_files as $this_included_file) {
							if(strpos($this_file, $this_included_file) !== false) {
								$avoid_skip = true;
							}
						}
					}
					if(!$avoid_skip) {
						$skip = true;
						break;
					}
				}
			}
		}
		if(empty($skip)) {
			$files_to_minify_array[$this_key] = $this_file;
			unset($files_array[$this_key]);
		}
	}
	if(!empty($_GET['update']) && $_GET['update'] == 1 && empty($GLOBALS['already_updated_minify_id_increment'])) {
		$GLOBALS['site_parameters']['minify_id_increment'] = intval($GLOBALS['site_parameters']['minify_id_increment'])+1;
		$GLOBALS['already_updated_minify_id_increment'] = true;
	}
	$cache_id = md5(implode(',', $files_to_minify_array) . ','. vb($GLOBALS['site_parameters']['minify_id_increment']));
	$file_name = $files_type . '_minified_' . substr($cache_id, 0, 8).'.'.$files_type;
	$minified_doc_root = $GLOBALS['dirroot'] . '/'.$GLOBALS['site_parameters']['cache_folder'].'/';
	$file_path = $minified_doc_root . $file_name;
	if (file_exists($file_path) === false || (($filemtime = @filemtime($file_path)) < time() - $lifetime) || (!empty($_GET['update']) && $_GET['update'] == 1)) {
		if(!empty($_GET['update']) && $_GET['update'] == 1) {
			$generate = true;
		} elseif(!empty($filemtime)) {
			foreach($files_to_minify_array as $this_key => $this_file) {
				// On regarde les fichiers à fusionner pour voir si ils ont changé depuis la dernière création du fichier de cache
				if(strpos($this_file, '//') === false || strpos($this_file, $GLOBALS['wwwroot']) !== false) {
					// On ne peut faire le test si fichier récent ou pas que si il est en local
					$this_local_path = str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], $this_file);
					$this_mtime = @filemtime($this_local_path);
					if(empty($this_mtime) && !file_exists($this_local_path)) {
						// Le fichier est en local et n'existe pas, on ne tient donc pas compte de ce fichier
						unset($files_to_minify_array[$this_key]);
					}
					if($this_mtime < $filemtime) {
						// Fichier minified pas à jour
						$generate = true;
					}
				} elseif(strpos($this_file, $this_wwwroot) !== false) {
					// Fichier sur cdn peut-être modifié => fichier minified peut-être pas à jour, et est donc à regénérer
					$generate = true;
				} elseif(strpos($this_file, 'googleapis') !== false) {
					if($filemtime < time() - min($lifetime*24, 3600*24)) {
						// Fichier sur googleapis peut-être modifié => fichier minified peut-être pas à jour, et est donc à regénérer
						$generate = true;
					}
				} else {
					if($filemtime < time() - min($lifetime*24, 3600*24)) {
						// Fichier externe de CSS peut-être modifié => fichier minified peut-être pas à jour, et est donc à regénérer
						$generate = true;
					}
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
			} elseif($files_type == 'js') {
				if(!version_compare(PHP_VERSION, '5.3.0', '<')) {
					// JShrink non compatible si PHP < 5.3 => on désactive la minification
					require_once($GLOBALS['dirroot'] . '/lib/class/JShrink.php');
				}
			}
			foreach($files_to_minify_array as $this_key => $this_file) {
				if($files_type == 'css') {
					$symlinks = array();
					$docroot = $GLOBALS['dirroot'];
					if(String::strlen($GLOBALS['apparent_folder'])>1 && String::strpos($this_wwwroot, String::substr($GLOBALS['apparent_folder'], 0, String::strlen($GLOBALS['apparent_folder']) - 1)) !== false) {
						$this_http_main_path = String::substr($this_wwwroot, 0, String::strlen($this_wwwroot) - String::strlen($GLOBALS['apparent_folder']) + 1);
						if(empty($GLOBALS['site_parameters']['avoid_lang_folders_in_minified_css']) && !empty($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']]) && strpos($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']], '//') === false && strpos($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']], '.') === false) {
							// On n'a pas choisi d'activer l'option pour avoir des liens vers les fichiers sans les dossiers de langue
							// Donc il faut corriger le chemin qu'on vient de retravailler
							$this_http_main_path = String::substr($this_http_main_path, 0, String::strlen($this_http_main_path) - String::strlen($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']]));
						}
					} else {
						$this_http_main_path = $this_wwwroot;
					}
					$options = array('currentDir' => str_replace($this_http_main_path, $GLOBALS['dirroot'], dirname($this_file)), 'docRoot' => $docroot, 'symlinks' => $symlinks);
					$css_content = String::file_get_contents_utf8(str_replace($this_wwwroot, $GLOBALS['dirroot'], $this_file));
					if(!empty($css_content) && strlen(trim($css_content))>5) {
						if(String::strpos(str_replace('@import url(http://fonts.googleapis.com', '', $css_content), '@import')!==false) {
							// On rajoute à la liste des exclusions le fichier concerné si il y a @import dedans pour éviter les dysfonctionnements, sauf si c'est l'import d'une font externe
							$GLOBALS['site_parameters']['minify_css_exclude_array'][] = $this_file;
							set_configuration_variable(array('technical_code' => 'minify_css_exclude_array', 'string' => get_string_from_array($GLOBALS['site_parameters']['minify_css_exclude_array']), 'type' => 'array', 'origin' => 'auto '.date('Y-m-d'), 'site_id' => $GLOBALS['site_id']), true);
							continue;
						}
						if(strlen($css_content)/max(1,substr_count($css_content, "\n"))>50) {
							// Si le fichier semble déjà être minified, on ne cherche pas à compresser davantage => gain de temps et limite les risques d'altération du fichier
							// Néanmoins on appelle quand même la classe minify qui va corriger les chemins des URL appelées dans le fichier
							$options['do_compress'] = false;
						}
						$output .= "\n\n\n".Minify_CSS::minify($css_content, $options);
					} else {
						trigger_error('Minify CSS impossible - File load problem : '.str_replace($this_wwwroot, $GLOBALS['dirroot'], $this_file), E_USER_NOTICE);
						if(file_exists($file_path)) {
							// Le fichier minified attendu existe déjà => même si il est périmé, on le laisse tel qu'il est
							// On annule donc le travail déjà fait et on sort en gardant le fichier existant
							$output = null;
							$write_result = true;
							break; 
						} else {
							// Le fichier minified n'existe pas, et on a un problème => on ne va pas du tout faire de minify
							// Si on voulait néanmoins écrire le fichier minified partiel, il faudrait repartir de manière récursive. Et à chaque appel de page, ça aurait lieu => pas une bonne idée pour les ressources serveur
							// Donc on renvoie la liste des fichiers CSS complète sans altération
							return $original_files_array;
						}
					}
				} elseif($files_type == 'js') {
					$js_content = String::file_get_contents_utf8(str_replace($this_wwwroot, $GLOBALS['dirroot'], $this_file));
					if(!empty($js_content) && strlen(trim($js_content))>5) {
						// Le fichier n'est pas vide, on le prend
						if(strlen($js_content)/max(1,substr_count($js_content, "\n"))>50 || version_compare(PHP_VERSION, '5.3.0', '<')) {
							// NB : Si le fichier semble déjà être minified, on ne cherche pas à compresser davantage => gain de temps et limite les risques d'altération du fichier
							$output .= "\n\n\n" . $js_content;
						}else {
							$output .= "\n\n\n" . Minifier::minify($js_content);
						}
					}
				} else {
					$output .= "\n\n\n".String::file_get_contents_utf8(str_replace($this_wwwroot, $GLOBALS['dirroot'], $this_file));
				}
			}
			if(!empty($output)) {
				$output = trim($output);
				$fp = String::fopen_utf8($file_path, 'wb');
				@flock($fp, LOCK_EX);
				// On utilise strlen et non pas String::strlen car on veut le nombre d'octets et non pas de caractères
				$write_result = fwrite($fp, $output, strlen($output));
				@flock($fp, LOCK_UN);
				fclose($fp);
				set_configuration_variable(array('technical_code' => 'minify_id_increment', 'string' => $GLOBALS['site_parameters']['minify_id_increment'], 'type' => 'integer', 'origin' => 'auto '.date('Y-m-d'), 'site_id' => $GLOBALS['site_id']), true);
			}
			if(!$write_result) {
				return $files_array;
			}
		} else {
			// On valide le fichier pour une nouvelle période de durée $lifetime
			touch($file_path);
		}
	}
	// On remet le fichier CSS minifié en position dans le tableau de tous les CSS à charger, à la position du premier fichier qui a été minifié (pour respecter au mieux les priorités)
	$files_array[key($files_to_minify_array)] = str_replace($GLOBALS['dirroot'], $this_wwwroot, $file_path);
	return $files_array;
}

/**
 * get_load_facebook_sdk_script()
 *
 * @param string $facebook_api_id
 * @return
 */
function get_load_facebook_sdk_script($facebook_api_id = null) {
	static $sdk_loaded;
	$output = '';
	if(empty($sdk_loaded)) {
		$sdk_loaded = true;
		$output .= '
<div id="fb-root"></div>
';
		$GLOBALS['js_content_array']['facebook_sdk'] = '
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1'.(!empty($facebook_api_id)?'&appId='.$facebook_api_id:'').'";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));';
	}
	return $output;
}

/**
 * get_quick_search_results()
 *
 * @param string $search
 * @param integer $maxRows
 * @param boolean $active
 * @param integer $search_category
 * @return
 */
function get_quick_search_results($search, $maxRows, $active_only = false, $search_category = null, $mode = 'products') {
	$queries_results_array = array();
	if($mode=='products'){
		$sql_additional_cond = '';
		$sql_additional_join = '';
		if(!empty($GLOBALS['site_parameters']['quick_search_results_main_search_field'])) {
			$name_field = $GLOBALS['site_parameters']['quick_search_results_main_search_field'];
		} else {
			$name_field = "nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])."";
		}
		// Pour optimiser, on segmente la recherche en plusieurs requêtes courtes
		if($active_only) {
			$sql_additional_cond .= " AND etat='1'";
		}
		if(is_numeric($search)) {
			$queries_sql_array[] = "SELECT p.*, p." . word_real_escape_string($name_field) . " AS nom
				FROM peel_produits p
				WHERE p.id='" . nohtml_real_escape_string($search) . "' AND " . get_filter_site_cond('produits', 'p') . "" . $sql_additional_cond . "
				LIMIT 1";
		}
		if(!empty($search_category)) {
			$sql_additional_cond .= " AND pc.categorie_id IN ('" . implode("','", get_category_tree_and_itself(intval($search_category), 'sons', 'categories')) . "')";
			$sql_additional_join .= 'INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id';
		}
		$queries_sql_array[] = "SELECT p.*, p." . word_real_escape_string($name_field) . " AS nom
			FROM peel_produits p
			".$sql_additional_join."
			WHERE p." . word_real_escape_string($name_field) . " LIKE '" . nohtml_real_escape_string($search) . "%' AND " . get_filter_site_cond('produits', 'p') . "" . $sql_additional_cond . "
			ORDER BY p." . word_real_escape_string($name_field) . " ASC";
		$queries_sql_array[] = "SELECT p.*, p." . word_real_escape_string($name_field) . " AS nom
			FROM peel_produits p
			".$sql_additional_join."
			WHERE p.reference LIKE '" . nohtml_real_escape_string($search) . "%' AND p." . word_real_escape_string($name_field) . "!='' AND " . get_filter_site_cond('produits', 'p') . "" . $sql_additional_cond . "
			ORDER BY IF(p.reference LIKE '" . nohtml_real_escape_string($search) . "',1,0) DESC, p." . word_real_escape_string($name_field) . " ASC";
		if(empty($GLOBALS['site_parameters']['autocomplete_fast_partial_search'])) {
			$queries_sql_array[] = "SELECT p.*, p." . word_real_escape_string($name_field) . " AS nom
				FROM peel_produits p
				".$sql_additional_join."
				WHERE (p." . word_real_escape_string($name_field) . " LIKE '%" . nohtml_real_escape_string($search) . "%' OR (p.reference LIKE '%" . nohtml_real_escape_string($search) . "%' AND p." . word_real_escape_string($name_field) . "!=''))" . $sql_additional_cond . " AND " . get_filter_site_cond('produits', 'p') . "
				ORDER BY p." . word_real_escape_string($name_field) . " ASC";
		}
		foreach($queries_sql_array as $this_query_sql) {
			if(String::strpos($this_query_sql, 'LIMIT') === false) {
				$this_query_sql .= ' LIMIT '.intval($maxRows);
			}
			$query = query($this_query_sql);
			while ($result = fetch_object($query)) {
				$queries_results_array[$result->id] = $result;
			}
			if(count($queries_results_array) >= $maxRows) {
				break;
			}
		}
	} elseif($mode=='offers') {
		if(!empty($GLOBALS['site_parameters']['user_offers_table_enable'])) {
			$sql = 'SELECT *
				FROM peel_offres
				WHERE num_offre LIKE "'.nohtml_real_escape_string($search).'%"
				LIMIT '.$maxRows;
			$query = query($sql);
			while ($result = fetch_object($query)) {
				$queries_results_array[$result->id] = $result;
			}
		}
	} elseif($mode=='offer_add_user') {
		$sql = 'SELECT id_utilisateur, prenom, nom_famille, societe, laboratoire, email, ville
			FROM peel_utilisateurs
			WHERE prenom LIKE "%'.nohtml_real_escape_string($search).'%" OR nom_famille LIKE "%'.nohtml_real_escape_string($search).'%" AND ' . get_filter_site_cond('utilisateurs') . '
			LIMIT '.$maxRows;
		$query = query($sql);
		while ($result = fetch_object($query)) {
			$queries_results_array[$result->id_utilisateur] = $result;
		}
	}
	return $queries_results_array;
}

/**
 * Retourne la condition SQL permettant de filtrer les données pour une table
 *
 * @param string $table_technical_code	Nom de la table sans prefix.
 * @param string $table_alias Alias de la table
 * @param boolean $use_admin_rights Ne renvoyer que les éléments qu'on peut éditer avec les droits d'administrateur en cours
 * @param integer $specific_site_id	Id du site concerné
 * @param boolean $exclude_public_items	Exclue les résultats concernant la configuration générique
 * @param boolean $admin_force_multisite_if_allowed
 * @return
 */
function get_filter_site_cond($table_technical_code, $table_alias = null, $use_admin_rights = false, $specific_site_id = null, $exclude_public_items = false, $admin_force_multisite_if_allowed = false) {
	if($table_technical_code == '') {
		// Pour certaine table, le champ qui contient l'id du site n'est pas site_id, mais id_ecom
		$field = 'id_ecom';
	} else {
		// Cas général
		$field = 'site_id';
	}
	if(in_array($table_technical_code, array('continents'))) {
		// Cette table n'est pas multisite.
		return 1;
	}
	if(!empty($GLOBALS['site_parameters']['multisite_disable'])) {
		// Désactivation du multisite pour accélérer les requêtes sur un site isolé
		return 1;
	}
	if(!empty($table_alias)) {
		// Utilise l'alias de la table comme préfix. L'alias de la table est défini dans la requête
		$prefix = word_real_escape_string($table_alias) . '.';
	} elseif($table_alias === null) {
		$prefix = 'peel_' . word_real_escape_string($table_technical_code) . '.';
	} else {
		// table sans prefix, dans la cas d'une interconnexion avec des scripts spécifiques qui ne respectent pas cette norme
		$prefix = '';
	}
	if ($specific_site_id !== null) {
		// Utilise le site_id spécifié en paramètre, utile pour manipuler des données qui ne concernent pas le site qui exécute la page. $specific_site_id peut être égal à 0.
		$site_id = $specific_site_id;
	} else {
		if(empty($GLOBALS['site_id']) && defined('IN_CRON') && $use_admin_rights === false && $exclude_public_items === false) {
			// $GLOBALS['site_id'] est vide, et on ne cherche pas à avoir des droits d'administration particuliers
			// On est dans un cron, on veut gérer tous les sites en même temps sans avoir à charger la configuration de chaque site
			return 1;
		}
		// Utilise l'id du site en cours de consultation (cas général). $GLOBALS['site_id'] est défini au chargement du site
		$site_id = vn($GLOBALS['site_id']);
	}
	if (defined('IN_PEEL_ADMIN')) {
		if(isset($_SESSION['session_utilisateur']['site_id']) && $_SESSION['session_utilisateur']['site_id'] == 0) {
			// L'administrateur a les droits sur tous les sites
			if($admin_force_multisite_if_allowed) {
				// Lorsque l'ensemble des sites est concerné par la requête et pas seulement un site, ou que la configuration général, aucun filtre sur site_id ne doit être ajouté.
				return 1;
			} elseif(isset($_SESSION['session_admin_multisite']) && $specific_site_id === null) {
				if(intval($_SESSION['session_admin_multisite'])==0) {
					// on administre tous les sites en même temps
					return 1;
				} else {
					// on administre un autre site, d'après la préférence de l'administrateur mise en session
					$site_id = intval($_SESSION['session_admin_multisite']);
					if($use_admin_rights) {
						$exclude_public_items = true;
					}
				}
			}
		} elseif($specific_site_id === null) {
			// L'administrateur ne peut administrer que son propre site
			if($use_admin_rights) {
				$exclude_public_items = true;
			}
			if(((!est_identifie() || !isset($_SESSION['session_utilisateur']['site_id'])) && $site_id != vn($GLOBALS['site_id'])) || (est_identifie() && $_SESSION['session_utilisateur']['site_id'] != $site_id && $_SESSION['session_utilisateur']['site_id'] != 0)) {
				// problème de droit : sécurité, on empêche l'administrateur d'agir sur un site qui ne le concerne pas. Si l'administrateur est associé à site_id = 0, il peux administrer tous les sites, donc cette contrainte ne s'applique pas
				return 0;
			}
		}
	} elseif(!empty($site_id) && !empty($_SESSION['session_utilisateur']['site_id']) && $site_id != $_SESSION['session_utilisateur']['site_id'] && $site_id != vn($GLOBALS['site_id'])) {
		// Protection sur les site_id autorisés : sécurité, on empêche l'utilisateur d'agir sur un site qui ne le concerne pas
		return 0;
	} elseif(isset($_SESSION['session_site_country']) && !empty($GLOBALS['site_parameters']['site_country_allowed_array']) && in_array($table_technical_code, array('articles', 'marques', 'html', 'produits', 'vignettes_carrousels'))) {
		// Gestion de l'affichage de contenu spécifique en fonction du pays du visiteur. Cette fonction nécessite une mise en place spécifique en SQL et n'est pas standard.
		// Si pas dans un contexte d'administration de la donnée : ajout de la condition de le pays du visiteur 
		$cond_array[] = "FIND_IN_SET('" . real_escape_string($_SESSION['session_site_country']) . "', " . $prefix . "site_country)";
	}

	if($exclude_public_items) {
		// la requête concerne un seul site, sans tenir compte de la configuration global.
		$cond_array[] = $prefix.word_real_escape_string($field)."=".intval($site_id);
	} else {
		// Concerne un site, ou tous les sites
		$cond_array[] = $prefix.word_real_escape_string($field)." IN (0," . intval($site_id) . ")";
	}
	return implode(' AND ', $cond_array);
}

/**
 *
 * @param string $selected_fonction_name Name of the user job preselected
 * @return
 */
function get_user_job_options($selected_fonction_name = null)
{
	$output = '';
	if (!empty($GLOBALS['site_parameters']['user_job_array'])) {
		$tpl_options = array();
		foreach($GLOBALS['site_parameters']['user_job_array'] as $this_job_code=>$this_job) {
			if(String::substr($this_job, 0, 4)== 'STR_' && !empty($GLOBALS[$this_job])) {
				// Si le nom est une variable de langue, il faut utiliser cette variable.
				$this_job = $GLOBALS[$this_job];
			}
			$output .= '<option value="'.String::str_form_value($this_job_code).'" ' . frmvalide($selected_fonction_name == $this_job_code, ' selected="selected"') . '>'.$this_job.'</option>';
		}
	}
	return $output;
}

/**
 *
 * @param integer $selected_values Id preselected
 * @return
 */
function get_generic_options($values_array, $selected_values = null)
{
	$output = '';
	$tpl = $GLOBALS['tplEngine']->createTemplate('generic_options.tpl');
	$tpl_options = array();
	foreach ($values_array as $tab_type) {
		$tpl_options[] = array(
			'value' => String::str_form_value($tab_type['id']),
			'name' => $tab_type['name'],
			'issel' => ($tab_type['id'] == $selected_values)
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	return $output;
}

/**
 *
 * @param integer $site_id
 * @return
 */
function get_site_wwwroot($site_id)
{
	$output = $GLOBALS['wwwroot'];
	if(!empty($site_id)) {
		$query = query('SELECT string 
			FROM peel_configuration 
			WHERE technical_code = "wwwroot" AND ' . get_filter_site_cond('configuration', null, false, $site_id) . ' AND etat=1');
		$result = fetch_assoc($query);
		if(!empty($result['string'])) {
			$output = $result['string'];
		}
	}
	return $output;
}

/**
 * Gère l'affichage du formulaire de contact, avec les erreurs et le message de confirmation d'envoi
 *
 * @param array $frm
 * @param boolean $skip_introduction_text
 * @return
 */
function handle_contact_form($frm, $skip_introduction_text = false) {
	include($GLOBALS['dirroot'] . "/lib/fonctions/display_user_forms.php");
	if (check_if_module_active('photodesk')) {
		include($GLOBALS['fonctionsphotodesk']);
	}
	$output = '';
	$form_error_object = new FormError();

	if (!empty($frm) && empty($_GET['prodid'])) {
		if (!empty($frm['phone'])) {
			// Formulaire de demande de rappel par téléphone
			// Non implémenté par défaut
			$frm['nom'] = $frm['phone'];
			$frm['telephone'] = $frm['phone'];
			$frm['sujet'] = $GLOBALS["STR_CALL_BACK_EMAIL"]; // Variable de langue à définir
		} else {
			// Le formulaire a été soumis, on essaie de créer un nouveau compte d'utilisateur
			if (!empty($GLOBALS['site_parameters']['contact_form_short_mode'])) {
				$form_error_object->valide_form($frm,
					array('nom' => $GLOBALS['STR_ERR_NAME'],
						'email' => $GLOBALS['STR_ERR_EMAIL'],
						'texte' => $GLOBALS['STR_ERR_MESSAGE'],
						'token' => ''));
			} else {
				$form_error_object->valide_form($frm,
					array('nom' => $GLOBALS['STR_ERR_NAME'],
						'telephone' => $GLOBALS['STR_ERR_TEL'],
						'email' => $GLOBALS['STR_ERR_EMAIL'],
						'texte' => $GLOBALS['STR_ERR_MESSAGE'],
						'sujet' => $GLOBALS['STR_ERR_SUBJECT'],
						'token' => ''));
			}
			if (!$form_error_object->has_error('email')) {
				$frm['email'] = trim($frm['email']);
				if (!EmailOK($frm['email'])) {
					// si il y a un email on teste l'email
					$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
				}
			}
			if (isset($frm['commande_id']) && !$form_error_object->has_error('commande_id') && vb($frm['sujet']) == $GLOBALS['STR_CONTACT_SELECT3'] && empty($frm['commande_id'])) {
				$form_error_object->add('commande_id', $GLOBALS['STR_ERR_ORDER_NUMBER']);
			}
			if (is_captcha_module_active()) {
				if (empty($frm['code'])) {
					// Pas de tentative de déchiffrement, on laisse le captcha
					$form_error_object->add('code', $GLOBALS['STR_EMPTY_FIELD']);
				} else {
					if (!check_captcha($frm['code'], $frm['code_id'])) {
						$form_error_object->add('code', $GLOBALS['STR_CODE_INVALID']);
						// Code mal déchiffré, on en donne un autre
						delete_captcha(vb($frm['code_id']));
						unset($frm['code']);
					}
				}
			}
		}
		if (!verify_token('user_contact', 120, false)) {
			// Important : évite spam de la part de robots simples qui appellent en POST la validation de formulaire
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			if (is_captcha_module_active()) {
				// Code OK on peut effacer le code
				delete_captcha(vb($frm['code_id']));
			}
			if (empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['REQUEST_METHOD'] != "POST") {
				// Protection du formulaire contre les robots
				die();
			}
			// Limitation du nombre de messages envoyés dans une session
			if (empty($_SESSION['session_form_contact_sent'])) {
				$_SESSION['session_form_contact_sent'] = 0;
			}
			if ($_SESSION['session_form_contact_sent'] < 10) {
				insere_ticket($frm);
				$_SESSION['session_form_contact_sent']++;
				$frm['is_ok'] = true;
			}
			// Si le module webmail est activé, on insère dans la table webmail la requête user
			$output .= get_contact_success($frm);
			$form_validated = true;
		}
	} elseif (!empty($_GET['prodid'])) {
		$product_object = new Product($_GET['prodid'], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
		$attribut_list = get_attribut_list_from_post_data($product_object, $frm);
		if (!empty($frm['critere'])) {
			// Affichage des combinaisons de couleur et taille dans un unique select
			$criteres = explode("|", $frm['critere']);
			$couleur_id = intval(vn($criteres[0]));
			$taille_id = intval(vn($criteres[1]));
		} else {
			$couleur_id = intval(vn($frm['couleur']));
			$taille_id = intval(vn($frm['taille']));
		}
		// On enregistre la taille pour revenir sur la bonne valeur du select
		$_SESSION['session_taille_id'] = $taille_id;
		// On enregistre le message à afficher si la quantité demandée est trop élevée par rapport au stock disponnible
		$product_object->set_configuration($couleur_id, $taille_id, $attribut_list, is_reseller_module_active() && is_reseller());

		$color = $product_object->get_color();
		$size = $product_object->get_size();

		$frm['texte'] = $GLOBALS['STR_PRODUCT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " .$product_object->name.
			(!empty($color)?"\r\n" . $GLOBALS['STR_COLOR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $color :'' ). 
			(!empty($size)?"\r\n" . $GLOBALS['STR_SIZE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $size :'' ). 
			(!empty($product_object->configuration_attributs_list) ? "\r\n" .  str_replace('<br />', "\r\n", $product_object->configuration_attributs_description) : '');
	}

	if(empty($form_validated)) {
		if (!empty($noticemsg)) {
			$output .= $noticemsg;
		}
		if(empty($frm) && est_identifie()) {
			$frm['email'] = vb($_SESSION['session_utilisateur']['email']);
			$frm['telephone'] = vb($_SESSION['session_utilisateur']['telephone']);
			$frm['nom'] = vb($_SESSION['session_utilisateur']['nom_famille']);
			$frm['prenom'] = vb($_SESSION['session_utilisateur']['prenom']);
			$frm['societe'] = vb($_SESSION['session_utilisateur']['societe']);
			$frm['adresse'] = vb($_SESSION['session_utilisateur']['adresse']);
			$frm['ville'] = vb($_SESSION['session_utilisateur']['ville']);
			$frm['code_postal'] = vb($_SESSION['session_utilisateur']['code_postal']);
			if(isset($_SESSION['session_utilisateur']['pays'])) {
				$frm['pays'] = get_country_name($_SESSION['session_utilisateur']['pays']);
			}
		}
		$output .= get_contact_form($frm, $form_error_object, $skip_introduction_text);
	}
	return $output;
}

/**
 * Créer un code promo pour l'administration et la création de chèque cadeaux lors du paiement d'une commande.
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_code_promo($frm)
{
	$qid = query("SELECT *
		FROM peel_codes_promos
		WHERE nom = '" . nohtml_real_escape_string(String::strtoupper($frm['nom'])) . "' AND " . get_filter_site_cond('codes_promos') . "");
	if ($result = fetch_assoc($qid)) {
		return false;
	}
	if (empty($frm["date_debut"])) {
		$frm["date_debut"] = get_formatted_date(time());
	} 
	if (empty($frm["date_fin"])) {
		$frm["date_fin"] = get_formatted_date(date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') + 30, date('Y'))));
	}
	if (empty($frm['on_type'])) {
		$frm['on_type'] = 2;
	}
	if (empty($frm['source'])) {
		$frm['source'] = 'CHQ';
	}
	if (!isset($frm['nombre_prevue'])) {
		$frm['nombre_prevue'] = 1;
	}
	if (!isset($frm['nb_used_per_client'])) {
		$frm['nb_used_per_client'] = 1;
	}

	$sql = "INSERT INTO peel_codes_promos (
		nom
		, date_debut
		, date_fin
		, remise_percent
		, remise_valeur
		, email_ami
		, email_acheteur
		, on_type
		, on_check
		, montant_min
		, etat
		, source
		, id_utilisateur
		, site_id
		, id_categorie
		, nombre_prevue
		, nb_used_per_client
		, product_filter
		, cat_not_apply_code_promo
	) VALUES (
		'" . nohtml_real_escape_string(String::strtoupper(vb($frm['nom']))) . "'
		, '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm["date_debut"])) . "'
		, '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm["date_fin"])) . "'
		, '" . (vb($frm['on_type']) == 1 ? floatval(get_float_from_user_input(vn($frm['remise_percent']))) : '0') . "'
		, '" . (vb($frm['on_type']) == 2 ? floatval(get_float_from_user_input(vn($frm['remise_valeur']))) : '0') . "'
		, '" . nohtml_real_escape_string(vb($frm['email_ami'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['email_acheteur'])) . "'
		, '" . intval($frm['on_type']) . "'
		, '" . intval(vb($frm['on_check'])) . "'
		, '" . floatval(get_float_from_user_input(vb($frm['montant_min']))) . "'
		, '" . intval(vn($frm['etat'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['source'])) . "'
		, '" . intval(vb($frm['id_utilisateur'])) . "'
		, '" . intval(vn($frm['site_id'])) . "'
		, '" . intval(vb($frm['id_categorie'])) . "'
		, '" . intval(vb($frm['nombre_prevue'])) . "'
		, '" . intval(vb($frm['nb_used_per_client'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['product_filter'])) . "'
		, '" . get_string_from_array(nohtml_real_escape_string(vb($frm['cat_not_apply_code_promo']))) . "'
		)";
	query($sql);
	return insert_id();
}


/**
 * Retourne le taux de TVA le plus élevé, que l'on considère comme le taux de tva par défaut. Cette valeur est utilisée notamment pour la création de commande ou d'attribut.
 *
 * @return
 */
function get_default_vat()
{
	$query = query("SELECT max(tva) as default_vat
		FROM peel_tva 
		WHERE " . get_filter_site_cond('tva'));
	if ($result = fetch_assoc($query)) {
		return $result['default_vat'];
	} else {
		// table TVA vide.
		return null;
	}
}
if (!function_exists('get_specific_field_infos')) {
	/**
	 * Permet de définir de nouveaux champs dans le formulaire d'inscription / modification d'utilisateur depuis le back office (page "variables de configuration").
	 *
	 * @param array $frm Array with all fields data
	 * @param class $form_error_object
	 * @return
	 *
	 */
	function get_specific_field_infos($frm, $reseller_form = false, $form_error_object = null, $form_usage="user") {
		$specific_fields = array();
		$one_possible_value_field_type_array = array('hidden','text','datepicker','textarea','upload','password');
		
		if ($form_usage=="user") {
			$mandatory_fields = vb($GLOBALS['site_parameters']['user_mandatory_fields'], array());
			if (!empty($reseller_form) && is_reseller_module_active()) {
				// Champ spécifique pour le formulaire revendeur
				$specific_field_titles = vb($GLOBALS['site_parameters']['reseller_specific_field_titles']);
				$specific_field_types = vb($GLOBALS['site_parameters']['reseller_specific_field_types']);
				$specific_field_names = vb($GLOBALS['site_parameters']['reseller_specific_field_names']);
				$specific_field_values = vb($GLOBALS['site_parameters']['reseller_specific_field_values']);
				$specific_field_positions = vb($GLOBALS['site_parameters']['reseller_specific_field_positions']);
			} else {
				$specific_field_titles = vb($GLOBALS['site_parameters']['user_specific_field_titles']);
				$specific_field_types = vb($GLOBALS['site_parameters']['user_specific_field_types']);
				$specific_field_names = vb($GLOBALS['site_parameters']['user_specific_field_names']);
				$specific_field_values = vb($GLOBALS['site_parameters']['user_specific_field_values']);
				$specific_field_positions = vb($GLOBALS['site_parameters']['user_specific_field_positions']);
			}
		} elseif ($form_usage=="order") {
			$mandatory_fields = vb($GLOBALS['site_parameters']['order_mandatory_fields'], array());
			$specific_field_titles = vb($GLOBALS['site_parameters']['order_specific_field_titles']);
			$specific_field_types = vb($GLOBALS['site_parameters']['order_specific_field_types']);
			$specific_field_names = vb($GLOBALS['site_parameters']['order_specific_field_names']);
			$specific_field_values = vb($GLOBALS['site_parameters']['order_specific_field_values']);
			$specific_field_positions = vb($GLOBALS['site_parameters']['order_specific_field_positions']);
		} else {
			return false;
		}
		if(!empty($specific_field_titles)) {
			foreach($specific_field_titles as $this_field => $this_title) {
				unset($tpl_options);
				if(String::substr($this_title, 0, 4)== 'STR_') {
					// Le titre est une variabe de langue
					$this_title = $GLOBALS[$this_title];
				}
				if (defined('IN_CHANGE_PARAMS') && !empty($GLOBALS['site_parameters']['disable_user_specific_field_on_change_params_page']) &&  in_array($this_field, $GLOBALS['site_parameters']['disable_user_specific_field_on_change_params_page'])) {
					// permet d'avoir des champs spécifiques qui seront utilisé lors de l'inscription, et ne pas les afficher sur la page de changement de paramètres
					continue;
				}
				$this_position = vb($specific_field_positions[$this_field]);
				$field_type = vb($specific_field_types[$this_field], 'text');


				if(in_array($specific_field_types[$this_field], $one_possible_value_field_type_array)) {
					// Le champ paramétré fait partie des champs valide. Ca évite les erreurs de saisie, et d'avoir un affichage incohérent.
					if (empty($specific_field_values[$this_field]) && in_array($specific_field_types[$this_field], array('radio','hidden','checkbox'))) {
						// La valeur est obligatoire pour un champ hidden, checkbox ou radio.
						continue;
					}
					$this_field_values = explode(',', $specific_field_values[$this_field]);
					$this_field_names = explode(',', $specific_field_names[$this_field]);

					if ($field_type == 'checkbox') {
						if (!empty($frm[$this_field])) {
							if (is_array($frm[$this_field])) {
								// Si $frm vient directement du formulaire, les valeurs pour les checkbox sont sous forme de tableau.
								$frm_this_field_values_array = $frm[$this_field];
							} else {
								// pour les checkbox, $frm[$this_field] peux contenir plusieurs valeurs séparées par des virgules si les données viennent de la BDD
								$frm_this_field_values_array = explode(',', $frm[$this_field]);
							}
						}
					} else {
						// Pour les autres champ, $frm[$this_field] contient une valeur unique.
						$frm_this_field_values_array = array(vb($frm[$this_field]));
					}
					foreach($this_field_values as $this_key => $this_value) {
						if(String::substr($this_value, 0, 4)== 'STR_') {
							// Variable de langue
							$this_value = $GLOBALS[$this_value];
						}
						if (in_array($field_type, $one_possible_value_field_type_array) && !empty($frm_this_field_values_array[0])) {
							// Pour récuperer la valeur d'un champ text. la valeur du formulaire $frm_this_field_values_array a priorité sur la valeur prédéfini en back office.
							$this_value = $frm_this_field_values_array[0];
						}
						$tpl_options[] = array('value' => $this_value,
								'issel' => in_array($this_value, $frm_this_field_values_array),
								'name' => vb($this_field_names[$this_key])
							);
					}
					$specific_fields[] = array('options' => $tpl_options,
							'field_type' => $field_type,
							'field_name' => $this_field,
							'field_title' => $this_title,
							'field_value' => vb($frm[$this_field]),
							'field_position' => $this_position,
							'mandatory_fields' => (!empty($mandatory_fields[$this_field])),
							'error_text' => (!empty($form_error_object)?$form_error_object->text($this_field):''),
							'STR_CHOOSE' => $GLOBALS['STR_CHOOSE']
						);
				}
			}
		}
		return $specific_fields;
	}
}

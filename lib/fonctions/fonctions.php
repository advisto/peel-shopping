<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	|
// +----------------------------------------------------------------------+
// $Id: fonctions.php 55772 2018-01-16 17:06:37Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * display_prices_with_taxes_active()
 *
 * @return
 */
function display_prices_with_taxes_active() {
    if (vn($GLOBALS['site_parameters']['display_prices_with_taxes']) == '0' || (check_if_module_active('reseller') && is_reseller() && !empty($GLOBALS['site_parameters']['force_display_reseller_prices_without_taxes']))) {
        return false;
    } else {
        return true;
    }
}

/**
 * Création d'un mot de passe crypté
 *
 * @return
 */
function unique_id()
{
	mt_srand(intval(microtime_float() * 1000000));
	return substr(sha256($GLOBALS['site_parameters']['sha256_encoding_salt'] . mt_rand(0, 9999999)), 0, 32);
}

/**
 * Fonction utilisée pour générer un mot aléatoire
 * (sert par exemple pour le renommage des fichiers images, mot de passe utilisateur, ...)
 *
 * @param integer $chrs Fixe le nombre de caractères
 * @return
 */
function MDP($chrs = 8, $data_user_info_array = array())
{
	$pwd = "";
	if (!empty($GLOBALS['site_parameters']['user_generate_custom_password']) && !empty($data_user_info_array)) {
		// le password est la concaténation de:
		// o première lettre du prénom
		// o dernière lettre du prénom
		// o première lettre du nom
		// o dernière lettre du nom
		// o année sous format aa
		// o mois sous format mm
		// o jour sous format jj
		// o heure de création sous format hh
		if(!empty($data_user_info_array['prenom'])) {
			$pwd .= StringMb::substr($data_user_info_array['prenom'], 0 ,1).StringMb::substr($data_user_info_array['prenom'], -1, 1);
		}
		if(!empty($data_user_info_array['nom_famille'])) {
			$pwd .= StringMb::substr($data_user_info_array['nom_famille'], 0 ,1).StringMb::substr($data_user_info_array['nom_famille'], -1, 1);
		}
		$pwd .= date('y').date('m').date('d').date('H');
	} else {
		mt_srand(intval(microtime_float() * 1000000));
		while (StringMb::strlen($pwd) < $chrs) {
			$chr = chr(mt_rand(0, 255));
			// on évite les 1, i, I, o, O et 0
			if (preg_match("/^[a-hj-km-np-zA-HJ-KM-NP-Z2-9]$/i", $chr)) {
				$pwd = $pwd . $chr;
			}
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
		srand(intval(microtime_float() * 1000000));
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
		return '<input type="hidden" name="token" value="' . StringMb::str_form_value($token) . '" />';
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
 * @param mixed $minimum_wait_in_seconds_before_use
 * @return
 */
function verify_token($name = 'general', $delay_in_minutes = 60, $check_referer_if_set_by_server = true, $cancel_token = true, $minimum_wait_in_seconds_before_use = 0)
{
	if (!empty($_POST['token'])) {
		$user_token = $_POST['token'];
	} elseif (!empty($_GET['token'])) {
		$user_token = $_GET['token'];
	}

	$result = false;
	if (isset($_SESSION['token_' . $name]) && isset($_SESSION['token_time_' . $name]) && !empty($user_token)) {
		if ($_SESSION['token_' . $name] == $user_token && $_SESSION['token_time_' . $name] + $delay_in_minutes * 60 >= time() && $_SESSION['token_time_' . $name] + $minimum_wait_in_seconds_before_use <= time()) {
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
 * @param boolean $get_parents_instead_of_sons
 * @return $ids_array Liste complétée
 */
function get_sons_cat($all_parents_with_ordered_direct_sons_array, $catid, $ids_array = array(), $get_parents_instead_of_sons = false)
{
	// On met une protection au cas où une catégorie parente ait un enfant direct ou indirect qui est son propre parent
	static $studied_cats_array;
	$hash = md5(serialize($all_parents_with_ordered_direct_sons_array));
	if(!count($ids_array)) {
		// Si on relance la fonction avec la même liste $all_parents_with_ordered_direct_sons_array
		unset($studied_cats_array[$hash]);
	}
	if(empty($studied_cats_array[$hash][$catid])) {
		$studied_cats_array[$hash][$catid] = true;
		if(!$get_parents_instead_of_sons) {
			if(!empty($all_parents_with_ordered_direct_sons_array[$catid])) {
				foreach ($all_parents_with_ordered_direct_sons_array[$catid] as $son_catid) {
					$ids_array[] = $son_catid;
					if (!empty($all_parents_with_ordered_direct_sons_array[$son_catid])) {
						$ids_array = get_sons_cat($all_parents_with_ordered_direct_sons_array, $son_catid, $ids_array);
					}
				}
			}
		} else {
			foreach ($all_parents_with_ordered_direct_sons_array as $this_parent_studied_id) {
				if ($this_parent_studied_id != $catid && in_array($catid, $all_parents_with_ordered_direct_sons_array[$this_parent_studied_id])) {
					// On a trouvé un parent
					$ids_array[] = $this_parent_studied_id;
					$ids_array = get_sons_cat($all_parents_with_ordered_direct_sons_array, $this_parent_studied_id, $ids_array, true);
				}
					
			}
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
			// En mode global, on compte le nombre d'annonces des catégories et sous-catégories
			$ids_array[] = $catid;
			$ids_array = get_sons_cat($all_parents_with_ordered_direct_sons_array, $catid);
		}
		if(empty($ids_array)) {
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
 * @param string $force_format_separator
 * @param boolean $add_rdfa_properties 
 * @param boolean $round_even_if_no_format 
 * @return
 */
function fprix($price, $display_currency = false, $currency_code_or_default = null, $convertion_needed_into_currency = true, $currency_rate = null, $display_iso_currency_code = false, $format = true, $force_format_separator = null, $add_rdfa_properties = false, $round_even_if_no_format = false)
{
	static $currency_infos_by_code;
	if(!empty($GLOBALS['site_parameters']['price_hide_if_not_loggued']) && !defined('IN_IPN') && (!est_identifie() || (!a_priv('util*') && !a_priv('admin*') && !a_priv('reve*')) || a_priv('*refused') || a_priv('*wait'))) {
		return '-';
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
			$currency_symbole = StringMb::html_entity_decode(str_replace('&euro;', '€', $currency_infos_by_code[$currency_code_or_default]['symbole']));
			$currency_rate_item = $currency_infos_by_code[$currency_code_or_default]['conversion'];
			$symbole_place = $currency_infos_by_code[$currency_code_or_default]['symbole_place'];
		}
	}
	if (empty($currency_symbole) && !empty($_SESSION['session_devise'])) {
		// Par défaut ou si on ne recupère aucun symbole de devise alors on utilise le symbole et le taux de conversion de session
		$currency_code = $_SESSION['session_devise']['code'];
		$currency_symbole = $_SESSION['session_devise']['symbole'];
		$currency_rate_item = $_SESSION['session_devise']['conversion'];
		$symbole_place = $_SESSION['session_devise']['symbole_place'];
	} else {
		// valeur par défaut que l'on retrouve dans configuration.inc.php.
		$currency_code = 'EUR';
		$currency_symbole = ' €';
		$currency_rate_item = 1;
		$symbole_place = 1;
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
	if(!empty($GLOBALS['site_parameters']['prices_precision']) && is_array($GLOBALS['site_parameters']['prices_precision']) && isset($GLOBALS['site_parameters']['prices_precision'][$currency_code])) {
		$prices_precision = $GLOBALS['site_parameters']['prices_precision'][$currency_code];
	} else {
		$prices_precision = vb($GLOBALS['site_parameters']['prices_precision'], 2);
	}
	if($force_format_separator) {
		$prices_decimal_separator = $force_format_separator;
	} else {
		if(!empty($GLOBALS['site_parameters']['prices_decimal_separator']) && is_array($GLOBALS['site_parameters']['prices_decimal_separator']) && isset($GLOBALS['site_parameters']['prices_decimal_separator'][$currency_code])) {
			$prices_decimal_separator = $GLOBALS['site_parameters']['prices_decimal_separator'][$currency_code];
		} elseif (!empty($_SESSION['session_devise']['decimal_separator'])) {
			$prices_decimal_separator = $_SESSION['session_devise']['decimal_separator'];
		} else {
			$prices_decimal_separator = vb($GLOBALS['site_parameters']['prices_decimal_separator'], ',');
		}
	}
	if(!empty($GLOBALS['site_parameters']['prices_thousands_separator']) && is_array($GLOBALS['site_parameters']['prices_thousands_separator']) && isset($GLOBALS['site_parameters']['prices_thousands_separator'][$currency_code])) {
		$prices_thousands_separator = $GLOBALS['site_parameters']['prices_thousands_separator'][$currency_code];
	} elseif (!empty($_SESSION['session_devise']['thousands_separator'])) {
		$prices_thousands_separator = $_SESSION['session_devise']['thousands_separator'];
	} else {
		$prices_thousands_separator = vb($GLOBALS['site_parameters']['prices_thousands_separator'], ' ');
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
			$price_displayed = number_format($price_displayed, $prices_precision, $prices_decimal_separator, $prices_thousands_separator);
		}
		if($add_rdfa_properties) {
			$price_displayed = '<span property="price" content="'.number_format(str_replace(array(',', ' '), array('.',''), $price_displayed), $prices_precision, '.', '') . '">'.$price_displayed.'</span>';
		}
		if ($display_iso_currency_code) {
			if($add_rdfa_properties) {
				$currency_code = '<span property="priceCurrency">'.$currency_code.'</span>';
			}
			$price_displayed .= ' ' . $currency_code;
		} elseif ($display_currency) {
			// Si on veut afficher le symbole de la devise (Par défaut, on affiche uniquement le montant)
			if($add_rdfa_properties) {
				$currency_symbole = '<span property="priceCurrency" content="'.$currency_code.'">'.$currency_symbole.'</span>';
			}
			if ($symbole_place == 1) {
				$price_displayed .= $GLOBALS['STR_BEFORE_TWO_POINTS'] . $currency_symbole;
			} else {
				$price_displayed = $currency_symbole . ' ' . $price_displayed;
			}
		}
	} elseif($round_even_if_no_format) {
		$price_displayed = round($price_displayed, $prices_precision);
	} else {
		// Etant donné le fonctionnement du stockage des float, il peut y avoir des nombres qui apparaissent en tant que X.49999 au lieu de X.50000 par exemple
		// Donc même si on ne formatte pas, on veut afficher le prix corrigé de ce défaut (sinon, on n'aurait pas fait appel à la fonction fprix si on ne voulait pas un minimum de traitement)
		if(abs($price_displayed-round($price_displayed, $prices_precision))<0.0001 == $price_displayed) {
			$price_displayed = number_format($price_displayed, $prices_precision, '.', '');
		}
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
 * @param string $devise
 * @return
 */
function get_discount_text($remise_valeur, $remise_percent, $is_remise_valeur_including_taxe, $devise = null)
{
	if (empty($devise)) {
		$devise = $GLOBALS['site_parameters']['code'];
	}
	$remise_displayed = array();
	$remise_valeur = floatval($remise_valeur);
	$remise_percent = floatval($remise_percent);
	if (!empty($remise_valeur)) {
		$remise_displayed[] = fprix($remise_valeur, true, $devise);
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
		if (!empty($GLOBALS['site_parameters']['no_display_tag_analytics_for_ip'])) {
			foreach(explode(',', str_replace(array(' ', ';'), array(',', ','), $GLOBALS['site_parameters']['no_display_tag_analytics_for_ip'])) as $this_ip_part) {
				if (!empty($this_ip_part) && ($this_ip_part == '*' || strpos($_SERVER['REMOTE_ADDR'], $this_ip_part) === 0)) {
					// IP utilisée détectée comme commençant par une IP listée dans no_display_tag_analytics_for_ip
					return false;
				}
			}
		}
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
 * @param integer $specific_site_id	Id du site concerné
 * @param boolean $exclude_public_items	Exclue les résultats concernant la configuration générique
 * @param boolean $allow_in_home_items
 * @return array Liste des modules
 */
function get_modules_array($only_active = false, $location = null, $technical_code = null, $force_update_cache_information = false, $specific_site_id = null, $exclude_public_items = false, $allow_in_home_items = true)
{
	static $modules_array;
	$static_hash = '';
	if ($only_active) {
		$static_hash .= 'only_active';
	}
	// On ajoute wwwroot dans le hash, en cas de configuration multisite certaines données sont différentes en fonction du site.
	$static_hash .= $location . '_' . $technical_code . '_' . vb($GLOBALS['page_columns_count']). '_' . vb($GLOBALS['wwwroot']);
	if (!isset($modules_array[$static_hash]) || $force_update_cache_information) {
		$modules = array();
		// defined('IN_PEEL_ADMIN') pour get_filter_site_cond : Cette fonction est appelée en front office pour l'affichage des modules mais aussi en back office pour l'administration des modules. 
		// Pour l'édition des modules on exclut (ou pas) les éléments publiques en fonction de la configuration de l'administrateur.
		$sql = 'SELECT *
			FROM peel_modules
			WHERE ' . get_filter_site_cond('modules', null, defined('IN_PEEL_ADMIN'), $specific_site_id, $exclude_public_items) . ' AND ' . ($location == 'header' && vn($GLOBALS['page_columns_count']) == 2 ?'(':'') . '(1' . ($technical_code ? ' AND technical_code="' . nohtml_real_escape_string($technical_code) . '"' : '') . ($location ? ' AND location="' . nohtml_real_escape_string($location) . '"' : '') . ')' . ($location == 'header' && vn($GLOBALS['page_columns_count']) == 2 ? ' OR (technical_code="caddie" AND location="below_middle")' : '') . ($location == 'header' && vn($GLOBALS['page_columns_count']) == 2 ?')':'') . ($only_active ? ' AND etat="1"' : '') . '
			ORDER BY position, id';
		$query = query($sql);
		while ($this_module = fetch_assoc($query)) {
			if(!empty($GLOBALS['site_parameters']['modules_disabled_array']) && in_array($this_module['technical_code'], $GLOBALS['site_parameters']['modules_disabled_array'])) {
				// Permet de désactiver un modèle dans un contexte particulier que ne permet pas la table peel_modules
				continue;
			}
			if(!empty($GLOBALS['site_parameters']['modules_only_if_constant_defined']) && !empty($GLOBALS['site_parameters']['modules_only_if_constant_defined'][$this_module['technical_code']])) {
				$ok = false;
				foreach(explode(',', $GLOBALS['site_parameters']['modules_only_if_constant_defined'][$this_module['technical_code']]) as $this_constant) {
					if(defined(trim($this_constant))) {
						$ok = true;
						break;
					}
				}
				if(empty($ok)) {
					continue;
				}
				unset($ok);
			}
			// Traitement spécifique
			if ($this_module['location'] == 'below_middle' && vn($GLOBALS['page_columns_count']) == 2 && $this_module['technical_code'] == 'caddie') {
				// On déplace le module de droite vers le haut pour l'afficher quand même
				if ((empty($location) || $location == 'header') && empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
					$this_module['location'] = 'header';
					$this_module['display_mode'] = '';
				} else {
					continue;
				}
			} else {
				$this_module['location'] = str_replace(array('left_annonce', 'right_annonce'), array('left', 'right'), $this_module['location']);
			}
			// On prend cet élément éventuellement modifié
			// Si le module est défini pour être afficher uniquement sur la home, alors on vérifie si on est sur la page Home, sinon on ne l'affiche pas. Dans l'administration on veut toujours afficher le module pour pouvoir l'administrer.
			if (empty($this_module['in_home']) || (!empty($this_module['in_home']) && $allow_in_home_items)) {
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
 * Il est possible d'autoriser la mise en cache de modules, en indiquant la durée de vie du cache dans $modules_cache_allowed_technical_codes en début de fonction
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
function get_modules($location, $return_mode = false, $technical_code = null, $id_categorie = null, $this_annonce_number = 0, $return_array_with_raw_information = false, $criterias = null)
{	
	if (empty($criterias)) {
		$criterias = $_GET;
	}
	// Ne pas mettre upsell dans la liste ci-après car un cache est déjà mis en place à l'intérieur du module
	$modules_cache_allowed_technical_codes = vb($GLOBALS['site_parameters']['modules_cache_allowed_technical_codes'], array('annonces' => 4500, 'tagcloud' => 120));
	// Pour annonces, si module de crons activé, le cron toutes les heures regénère les fichiers de cache pour éviter que ce soit un utilisateur qui le déclenche
	$output = '';
	$output_array = array();
	$modules_array = get_modules_array(true, $location, $technical_code, true, null, false, defined('IN_HOME'));

	$i = 0;
	foreach ($modules_array as $this_module) {
		$load_module = true;
		$this_block_style = '';
		$this_module_output = '';
		unset($width_class);
		if (!empty($id_categorie)) {
			$display_catalog_allowed = extra_catalogue_condition($id_categorie);
		} else {
			$display_catalog_allowed = true;
		}
		if (!empty($modules_cache_allowed_technical_codes[$this_module['technical_code']]) && !a_priv('admin*')) {
			$cache_id = $this_module['technical_code'] . '_' . $_SESSION['session_langue'] . '_' . vn($criterias['catid']) . '_' . vn($criterias['rubid']) . '_' . $GLOBALS['site_id'] . '_' . vn($_SESSION['session_admin_multisite']);
			if($this_module['technical_code'] == 'menu' && !empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
				// Le menu n'est pas généré de la même façon tout le temps, il faut préciser le contexte
				if((StringMb::strpos(StringMb::strtolower(vb($_SERVER['HTTP_USER_AGENT'])),'android') !== false || (StringMb::strpos(StringMb::strtolower(vb($_SERVER['HTTP_USER_AGENT'])),'windows') !== false && StringMb::strpos(StringMb::strtolower(vb($_SERVER['HTTP_USER_AGENT'])),'mobile') !== false))) {
					$cache_id .= '_avoidlinkswhenhover';
				}
				if(!empty($_POST)) {
					$cache_id .= '_withpost';
				}
			}
			if($this_module['technical_code'] == 'menu' && empty($criterias['catid']) && empty($criterias['rubid'])) {
				// Si la page en cours n'est pas une catégorie ou rubrique, alors on ne peut pas utiliser le cache car le hightlight du menu serait erroné
				unset($cache_id);
			} else {
				$this_module_output_cache_object = new Cache($cache_id, array('group' => 'html_block'));
				if ($this_module_output_cache_object->testTime($modules_cache_allowed_technical_codes[$this_module['technical_code']], true)) {
					$this_module_output = $this_module_output_cache_object->get();
					$load_module = false;
				}
			}
		}
		if ($load_module) {
			if ($this_module['technical_code'] == 'catalogue') {
				if($display_catalog_allowed) {
					if (function_exists('affiche_menu_catalogue')) {
						// Test sur la présence de affiche_menu_catalogue qui est l'ancienne fonction pour l'affichage du catalogue. L'utilisation est permise ici pour des raions de compatibilité avec d'anciens templates, dans le cas où la fonction est définie dans display_custom.php
						$this_module_output = affiche_menu_catalogue($this_module['location'], true, true);
					} else {
						$this_module_output = get_categories_output($this_module['location'], 'categories', vn($_GET['catid']), ($this_module['location']=='footer'?'div':''), null);
					}
					$tpl = $GLOBALS['tplEngine']->createTemplate('menu_catalogue.tpl');
					$tpl->assign('menu', $this_module_output);
					$tpl->assign('add_ul_if_result', true);
					$this_module_output = $tpl->fetch();
				}
			} elseif ($this_module['technical_code'] == 'tagcloud' && check_if_module_active('tagcloud')) {
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
				if (empty($GLOBALS['site_parameters']['cart_display_mini_caddie_if_not_empty']) || (!empty($GLOBALS['site_parameters']['cart_display_mini_caddie_if_not_empty']) && $_SESSION['session_caddie']->count_products() > 0)) {
					$this_module_output = affiche_mini_caddie($this_module['location'] != 'header', true);
				}
			} elseif ($this_module['technical_code'] == 'account') {
				$this_module_output = affiche_compte(true, $this_module['location']);
			} elseif ($this_module['technical_code'] == 'best_seller') {
				if (check_if_module_active('best_seller')) {
					$this_module_output = affiche_best_seller_produit_colonne(true, $this_module['location']);
					$width_class = ' col-md-12';
				}
			} elseif ($this_module['technical_code'] == 'brand') {
				// affiche du block marque
				$this_module_output = get_brand_link_html(null, true, true, $this_module['location']);
			} elseif ($this_module['technical_code'] == 'last_views') {
				if (check_if_module_active('last_views')) {
					$this_module_output = affiche_last_views($this_module['location']);
				}
			} elseif ($this_module['technical_code'] == 'quick_access') {
				if (function_exists('get_quick_access')) {
					$this_module_output = get_quick_access($this_module['location'], true);
				}
			} elseif ($this_module['technical_code'] == 'news' || $this_module['technical_code'] == 'articles_rollover') {
				if (check_if_module_active('menus')) {
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
			} elseif (StringMb::substr($this_module['technical_code'], 0, StringMb::strlen('advertising')) == 'advertising' && check_if_module_active('banner')) {
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

				if (defined('IN_ACCES_ACCOUNT') && in_array($this_module['location'], array('left', 'right'))) {
					// pour ne pas surcharger la page de login qui est courte
					continue;
				} 
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
				$this_module_output = affiche_banner(StringMb::substr($this_module['technical_code'], strlen('advertising')), true, (isset($criterias['page'])?$criterias['page']:null), $id_categorie, $this_annonce_number, $page_type, (isset($criterias['search'])?explode(' ', $criterias['search']):null), $_SESSION['session_langue'], $return_array_with_raw_information, (isset($criterias['ref'])?$criterias['ref']:null), vn($GLOBALS['page_related_to_user_id']));
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
				$this_module_output = get_newsletter_form();
			} elseif ($this_module['technical_code'] == 'contact') {
				$this_module_output = get_contact_sideblock($this_module['location'], true);
			} elseif ($this_module['technical_code'] == 'annonces' && check_if_module_active('annonces')) {
				$this_module_output = affiche_menu_annonce($this_module['location'], true, true);
			} elseif ($this_module['technical_code'] == 'become_verified' && check_if_module_active('abonnement')) {
				$this_module_output = get_verified_sideblock_link($this_module['location'], true);
			} elseif ($this_module['technical_code'] == 'upsell' && check_if_module_active('abonnement')) {
				$this_module_output = getVerifiedAdsList(vn($GLOBALS['site_parameters']['verified_ads_list_nb_picture_max'], 12), vn($GLOBALS['site_parameters']['verified_ads_list_picture_max_width'], 130), vn($GLOBALS['site_parameters']['verified_ads_list_picture_max_height'], 100));
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
			} elseif ($this_module['technical_code'] == 'last_forum_posts' && check_if_module_active('forum')) {
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
			} elseif ($this_module['technical_code'] == 'references' && check_if_module_active('carrousel')) {
				$this_module_output = Carrousel::display('references', true);
			} elseif ($this_module['technical_code'] == 'partenaires' && check_if_module_active('carrousel')) {
				$this_module_output = Carrousel::display('partenaires', true);
			} elseif ($this_module['technical_code'] == 'listecadeau' && check_if_module_active('listecadeau')) {
				$this_module_output = bloc_liste_cadeau();
			} elseif ($this_module['technical_code'] == 'forum_rss') {
				$this_module_output = get_xml_value($GLOBALS['site_parameters']['forum_rss_url'], $GLOBALS['site_parameters']['forum_rss_filter_string'], 9, 90);
			} elseif (function_exists('get_'.$this_module['technical_code'].'_module')) {
				$this_module_output = call_user_func('get_'.$this_module['technical_code'].'_module');
			}
			if (!empty($this_module_output_cache_object)) {
				// Si le module est mis en cache, on sauvegarde son contenu
				$this_module_output_cache_object->save($this_module_output);
			}
		}
		unset($this_module_output_cache_object);

		if (!empty($this_module_output)) {
			// On remplace d'éventuels tags par leurs valeurs (exemple : [WWWROOT])
			$this_module_output = template_tags_replace($this_module_output);
			if (!empty($return_array_with_raw_information)) {
				$output_array[] = $this_module_output;
			} else {
				$block_class = (!empty($this_module['display_mode'])?$this_module['display_mode'] . '_':'') . $this_module['technical_code'];
				if(!empty($GLOBALS['site_parameters']['modules_block_class_by_technical_code_array']) && !empty($GLOBALS['site_parameters']['modules_block_class_by_technical_code_array'][$this_module['technical_code']])) {
					$block_class .= ' ' . $GLOBALS['site_parameters']['modules_block_class_by_technical_code_array'][$this_module['technical_code']];
				} elseif(!empty($GLOBALS['site_parameters']['modules_block_class_by_display_mode_array']) && !empty($GLOBALS['site_parameters']['modules_block_class_by_display_mode_array'][$this_module['display_mode']])) {
					$block_class .= ' ' . $GLOBALS['site_parameters']['modules_block_class_by_display_mode_array'][$this_module['display_mode']];
				} elseif(in_array($this_module['location'], vb($GLOBALS['site_parameters']['modules_no_class_location_array'], array('left', 'right', 'left_annonce', 'right_annonce')))) {
					$block_class .= '';
				} elseif(isset($width_class)) {
					$block_class .= ' ' . $width_class;
				} elseif($this_module['location'] != 'header' && (($this_module['location'] == 'center_middle_home' || $this_module['location'] == 'below_middle') || ($this_module['location'] != 'center_middle_home' && $this_module['location'] != 'below_middle' && StringMb::substr($this_module['technical_code'], 0, StringMb::strlen('advertising')) != 'advertising'))) {
					$block_class .= ' col-md-4';
				}
				if ($this_module['display_mode'] == 'sideblocktitle' && $this_module['location'] != 'header' && $this_module['location'] != 'footer' && $this_module['location'] != 'middle') {
					$output .= affiche_sideblocktitle(vb($this_module['title_' . $_SESSION['session_langue']]), $this_module_output, $block_class, true);
				} elseif ($this_module['display_mode'] == 'sideblock' && $this_module['location'] != 'header' && $this_module['location'] != 'footer' && $this_module['location'] != 'middle') {
					$output .= affiche_sideblock(vb($this_module['title_' . $_SESSION['session_langue']]), $this_module_output, $block_class, true);
				} else {
					if (!empty($this_module['sliding_mode'])) {
						$output .= affiche_block($this_module['display_mode'], $this_module['location'], $this_module['technical_code'], vb($this_module['title_' . $_SESSION['session_langue']]), $this_module_output, $this_module['display_mode'] . '_' . $this_module['technical_code'], $this_block_style, true, true);
					} else {
						$block_class .= ' ' . $this_module['display_mode'] . ' ' . $this_module['location'] . '_basicblock ' . $this_module['location'] . '_' . $this_module['technical_code'] . '  ' . $this_module['technical_code'] . '_' . $_SESSION['session_langue'];
						if (($this_module['display_mode'] == 'sideblocktitle' || $this_module['display_mode'] == 'sideblock') && $this_module['location'] == 'footer') {
							$extra_class = true;
						} else {
							$extra_class = false;
						}
						if($this_module['technical_code'] == 'best_seller') {
							$block_class .= ' ' . 'col-md-12';
						}
						$output .= affiche_block($this_module['display_mode'], $this_module['location'], $this_module['technical_code'], vb($this_module['title_' . $_SESSION['session_langue']]), $this_module_output, $block_class, $this_block_style, true, true, true, vb($extra_class));
					}
				}
			}
		}
		if ($location == 'center_middle_home' && StringMb::substr($this_module['technical_code'], 0, StringMb::strlen('advertising')) != 'advertising') {
			if($i % 2 == 0) {
				$output .= '<div class="clearfix"></div>';
			}
			// Ne pas incrementer le compteur pour les bannières qui prennent toutes la largeur du contenu
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
 * Envoi un message de contact au support du site
 * Limitation pour éviter spam : utiliser session_form_contact_sent AVANT d'appeler cette fonction
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_ticket(&$frm)
{
	$frm['file'] = upload('file', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
	if (check_if_module_active('webmail')) {
		save_mail_db($frm);
	}
	if ((StringMb::strpos(vb($frm['sujet']), '/24') !== false || StringMb::strpos(vb($frm['sujet']), '24/') !== false) && !empty($GLOBALS['site_parameters']['email_emergency'])) {
		$recipient_email = $GLOBALS['site_parameters']['email_emergency'];
	} elseif (!empty($GLOBALS['site_parameters']['contact_email_by_subject']) && array_key_exists(vb($frm['sujet']), $GLOBALS['site_parameters']['contact_email_by_subject'])) {
		// Un destinataire différent par sujet du formulaire de contact
		$recipient_email = $GLOBALS['site_parameters']['contact_email_by_subject'][vb($frm['sujet'])];
	} elseif (!empty($GLOBALS['site_parameters']['contact_email_by_site']) && in_array(vb($frm['site_id']), array_keys($GLOBALS['site_parameters']['contact_email_by_site']))) {
		// Un destinataire différent par site indiqué du formulaire de contact si multisite et $GLOBALS['site_parameters']['site_configured_array'] défini
		$recipient_email = $GLOBALS['site_parameters']['contact_email_by_site'][vb($frm['site_id'])];
	} else {
		$recipient_email = $GLOBALS['support_sav_client'];
	}

	if (!empty($recipient_email)) {
		unset($custom_template_tags);
		$custom_template_tags['DATE'] = get_formatted_date(time(), 'short', 'long');
		if(!empty($GLOBALS['site_parameters']['site_configured_array']) && !empty($frm['site_id'])) {
			$custom_template_tags['SITE_SELECTED'] = vb($GLOBALS['site_parameters']['site_configured_array'][$frm['site_id']]);
		} else {
			$custom_template_tags['SITE_SELECTED'] = '';
		}
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
		if ($frm['sujet'] == vb($GLOBALS['STR_CONTACT_SELECT10']) && file_exists($GLOBALS['dirroot'].'/modules/funding/administrer/wallet_money_out.php')) {
			// Si $GLOBALS['STR_CONTACT_SELECT10'] on ajoute le lien vers modules/funding/wallet_money_out.php, et des informations complémentaires.
			$custom_template_tags['TEXTE'] .= '
			<br />
			<br />
			'.$GLOBALS['STR_USER_ID'].$GLOBALS['STR_BEFORE_TWO_POINTS'] .': ' . vn($_SESSION['session_utilisateur']['id_utilisateur']).'<br />
			'.$GLOBALS["STR_PSEUDO"].$GLOBALS['STR_BEFORE_TWO_POINTS'] .': ' . vb($_SESSION['session_utilisateur']['pseudo']) . '<br />
			<br />
			<a href="'.get_url('modules/funding/administrer/wallet_money_out.php').'">'.get_url('modules/funding/administrer/wallet_money_out.php').'</a>';
		}
		if ($_SESSION['session_form_insere_ticket_sent'] < 10) {
			// Limitation pour éviter spam : Un utilisateur peut envoyer 10 fois un email de contact par session
			send_email($frm['recipient'], '', '', 'insere_ticket', $custom_template_tags, 'html', $GLOBALS['support'], true, false, false, vb($frm['email']));
			$_SESSION['session_form_insere_ticket_sent']++;
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
	if(strpos($id, ',') !== false) {
		$site_country_array = array();
		foreach(explode(',', $id) as $this_id) {
			$site_country_array[] = get_country_name($this_id);
		}
		return implode(', ', $site_country_array);
	} elseif($id === null || $id === '') {
		return null;
	}
	if(!is_numeric($id)) {
		return $id;
	} elseif($id == 0) {
		return $GLOBALS['STR_WORLD'];
	} else {
		$sql = 'SELECT pays_' . $_SESSION['session_langue'] . '
			FROM peel_pays
			WHERE id="' . intval($id) . '"';
		$q = query($sql);
		if ($result = fetch_assoc($q)) {
			return StringMb::html_entity_decode_if_needed($result['pays_' . $_SESSION['session_langue']]);
		} else {
			return false;
		}
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
	if(!empty($country_name)) {
		$sql = 'SELECT id
			FROM peel_pays
			WHERE (pays_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($country_name) . '" OR iso="' . nohtml_real_escape_string($country_name) . '" OR iso3="' . nohtml_real_escape_string($country_name) . '") AND ' .  get_filter_site_cond('pays') . '
			LIMIT 1';
		$query = query($sql);
		if ($obj = fetch_object($query)) {
			$result = $obj->id;
		}
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
 * @param integer $show_parent_level
 * @return
 */
function get_category_name($id, $show_parent_level = 0)
{
	$sql = 'SELECT id, nom_' . $_SESSION['session_langue'] . ' AS name, parent_id
		FROM peel_categories c
		WHERE id="' . intval($id) . '" AND ' . get_filter_site_cond('categories', 'c', defined('IN_PEEL_ADMIN')) . '';
	$q = query($sql);
	if ($result = fetch_assoc($q)) {
		$name = StringMb::html_entity_decode_if_needed($result['name']);
		if($show_parent_level && !empty($result['parent_id'])) {
			$name = get_category_name($result['parent_id'], $show_parent_level-1) . ' > ' . $name;
		}
		return $name;
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
			$select_field = 'parent_id';
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
		if (!defined('IN_PEEL_ADMIN')) {
			// En front-office : pays de la consultation du site
			$selected_country_id = vn($_SESSION['session_site_country']);
		} else {
			// En back-office : on ne veut pas que la configuration de l'administration affecte les présélections
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
		if (check_if_module_active('departements') && empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id'])) {
			// Si le module département est actif, les zones sont gérées en fonction du département, et pas du pays. 
			// Pour déterminer à quel pays correspond les départements, la zone de ce pays a pour valeur -1.
			$sql_condition .= ' AND zone = "-1"';
		} else {
			$sql_condition .= (!empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id'])?' AND FIND_IN_SET(' . intval($allowed_zone_id) . ', zone)':' AND zone = "' . intval($allowed_zone_id) . '"');
		}
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
			'issel' => (StringMb::strtolower(vb($selected_country_name)) == StringMb::strtolower($tab_pays['pays_' . $selected_country_lang]) || vb($selected_country_id) == $tab_pays['id'])
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * Retourne un tableau des noms des pays
 *
 * @param boolean $admin_force_multisite_if_allowed
 * @param boolean $exclude_public_items
 * @return
 */
function get_all_site_countries_array($admin_force_multisite_if_allowed = false, $exclude_public_items = false, $show_only_site_country_allowed_array = false) {
	$sites_name_array = array();
	$sql_where = '';
	if($show_only_site_country_allowed_array) {
		$sql_where .= ' AND id IN ("' . implode('","', vb($GLOBALS['site_parameters']['site_country_allowed_array'])) . '")';
	}
	$sql_pays = 'SELECT id, pays_' . $_SESSION['session_langue'] . ' AS name
		FROM peel_pays
		WHERE etat=1 ' . $sql_where . ' AND ' . get_filter_site_cond('pays', null, false, null, $exclude_public_items, $admin_force_multisite_if_allowed) . '
		ORDER BY position, pays_' . $_SESSION['session_langue'];
	$res_pays = query($sql_pays);
	while($result = fetch_assoc($res_pays)) {
		$sites_name_array[$result['id']] = $result['name'];
	}
	return $sites_name_array;
}

/**
 * Fonction permettant de récupérer les noms des pays, sous forme de liste séparée par des virgules. 
 * Cette liste sera exploitée ensuite par get_specific_field_infos pour générer les noms des options dans un champ select, via le tag [FUNCTION=get_tag_function_countries_values_list] qui est remplacé par template_tags_replace.
 *
 * @return
 */
function get_tag_function_countries_values_list($params = array()) {
	$result_array = get_all_site_countries_array();
	if(vb($params['mode'], 'id') == 'id') {
		return implode(',', array_keys($result_array));
	} else {
		return implode(',', $result_array);
	}
}

/**
 *
 *
 * @return
 */
function get_tag_function_countries_titles_list() {
	return get_tag_function_countries_values_list(array('mode' => 'name'));
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
	$sql_type = "SELECT id, nom_" . $_SESSION['session_langue'] . ", site_id
		FROM peel_types
		WHERE etat = 1 AND " . get_filter_site_cond('types') . " AND (nom_" . $_SESSION['session_langue'] . "!=''".(!empty($selected_delivery_type_id_or_name) && is_numeric($selected_delivery_type_id_or_name) ?" OR id='" . real_escape_string($selected_delivery_type_id_or_name) . "'":"").")
		ORDER BY position ASC, nom_" . $_SESSION['session_langue'] . " ASC";
	$res_type = query($sql_type);

	$tpl = $GLOBALS['tplEngine']->createTemplate('delivery_type_options.tpl');
	$tpl_options = array();
	while ($tab_type = fetch_assoc($res_type)) {
		$tpl_options[] = array(
			'value' => intval($tab_type['id']),
			'name' => get_site_info($tab_type).$tab_type['nom_' . $_SESSION['session_langue']],
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
		$sql = "SELECT nom_" . $_SESSION['session_langue'] . " AS paiement, tarif, tarif_percent, tva
			FROM peel_paiement
			WHERE technical_code='" . nohtml_real_escape_string($frm['payment_technical_code']) . "' AND " .  get_filter_site_cond('paiement') . "";
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
 * @param boolean $show_site_info_if_needed
 * @param object $form_error_object
 * @param integer $specific_site_id
 * @return
 */
function get_payment_select($selected_payment_technical_code = null, $show_selected_even_if_not_available = false, $show_site_info_if_needed = false, $form_error_object = null, $specific_site_id = null, $payment_multiple = null, $display_none_option = false)
{
	$output = '';
	$sql_cond = '';
	$excluded_payment_array = call_module_hook('payment_select_excluded_ids', array(), 'array');
	if(!is_array($selected_payment_technical_code) && !empty($selected_payment_technical_code)) {
		$selected_payment_technical_code = explode(',', $selected_payment_technical_code);
	}
	if (empty($selected_payment_technical_code)) {
		$selected_payment_technical_code = array();
	}
	if (!defined('IN_PEEL_ADMIN') && !empty($_SESSION['session_caddie']->zoneId)) {
        // On va recherche si il y a une zone associée à un moyen de paiement.
        $sql = "SELECT payment_technical_code
            FROM peel_zones
            WHERE payment_technical_code!='' AND id = " . intval($_SESSION['session_caddie']->zoneId);
        $query = query($sql);
        if ($result = fetch_assoc($query)) {
            // Donc pour cette zone, il y a un paiement configuré. On souhaite que ce soit uniquement ce paiement qui s'affiche.
            $sql_cond .= " AND p.technical_code = '" . nohtml_real_escape_string($result['payment_technical_code']) . "'";
        }
		// On gère des listes sur le modèle codetechnique1,!codetechnique2,... avec le ! qui permet d'exclure un type de paiement

		foreach($selected_payment_technical_code as $this_type) {
			if(StringMb::substr($this_type, 0, 1) == '!') {
				$types_excluded_array[] = StringMb::substr($this_type, 1);
			} else {
				$types_allowed_array[] = $this_type;
			}
		}
		if (!empty($excluded_payment_array))  {
			$sql_cond .= " AND p.id NOT IN(".implode(',',real_escape_string($excluded_payment_array)).")";
		}
    }
	if (!empty($payment_multiple) && empty($sql_cond))  {
		// On choisit d'afficher que les paiements qui correspondent à un paiement en plusieurs fois. Les paiements en plusieurs fois possèdent #1 ou #3 par exemple à la fin du code technique.
		// Ce paramétrage est possible uniquement si on ne veut pas d'un paiement associé à la zone
		$sql_cond .= " AND p.technical_code LIKE('%#" . intval($payment_multiple) . "')";
	}
	if (!empty($excluded_payment_array))  {
		$sql_cond .= " AND p.id NOT IN(".implode(',',real_escape_string($excluded_payment_array)).")";
	}
	$sql_paiement = 'SELECT p.*
		FROM peel_paiement p
		WHERE ' .  get_filter_site_cond('paiement', 'p', false, $specific_site_id) . ' AND (totalmin<=' . floatval($_SESSION['session_caddie']->total) . ' OR totalmin=0) AND (totalmax>=' . floatval($_SESSION['session_caddie']->total) . ' OR totalmax=0) ' . $sql_cond . '
		GROUP BY technical_code, nom_' . $_SESSION['session_langue'] . ', site_id
		ORDER BY p.position';
	$res_paiement = query($sql_paiement);
	while ($tab_paiement = fetch_assoc($res_paiement)) {
		if((empty($tab_paiement['etat']) || empty($tab_paiement['nom_' . $_SESSION['session_langue']])) && (!$show_selected_even_if_not_available || !in_array($tab_paiement['technical_code'], $types_allowed_array) || in_array($tab_paiement['technical_code'], $types_excluded_array))){
			// On ne prend que les moyens de paiement actifs, ou ceux qui ont un code technique autorisé par $selected_payment_technical_code si $show_selected_even_if_not_available = true
			// Dans les autres cas, on passe au suivant
			continue;
		}
		if (($tab_paiement['technical_code'] == 'kwixo' && ($_SESSION['session_caddie']->zone_technical_code != 'france_mainland' && $_SESSION['session_caddie']->zone_technical_code != 'france_and_overseas')) || $tab_paiement['technical_code'] == 'kwixo_credit' && ($_SESSION['session_caddie']->montant >= 150 && $_SESSION['session_caddie']->montant <= 4000)) {
			// Fianet n'autorise que les paiement en france et DOM
			// Le paiement à crédit FIANET est possible entre 150 et 4000 €
			continue;
		}
		if (!defined('IN_PEEL_ADMIN') && !empty($GLOBALS['site_parameters']['payment_disable_display_on_payment_select_page']) && in_array($tab_paiement['technical_code'], $GLOBALS['site_parameters']['payment_disable_display_on_payment_select_page'])) {
			// On ne veut pas afficher ce moyen de paiement en front office. Il sera disponible uniquement dans l'administration.
			continue;
		}
		if (($tab_paiement['technical_code'] != 'paypal' || !empty($GLOBALS['site_parameters']['email_paypal'])) && ($tab_paiement['technical_code'] != 'moneybookers' || !empty($GLOBALS['site_parameters']['email_moneybookers']))) {
			// Paypal et moneybookers ne sont actifs que si email du compte est configuré
			$results_array[] = $tab_paiement;
		}
	}
	if (!empty($display_none_option)) {
		$results_array[] = array("technical_code"=>'', 'nom_' . $_SESSION['session_langue']=>$GLOBALS['STR_NONE'], 'tarif'=>0, 'tarif_percent'=>0);
	}
	if(!empty($results_array)) {
		foreach($results_array as $tab_paiement) {
			$payment_complement_informations = '';
			if (StringMb::strpos($tab_paiement['technical_code'], 'kwixo') !== false) {
				if ($tab_paiement['technical_code'] == 'kwixo_credit') {
					// Popup popuprnp3x et popuprnp1xrnp défini dans ' . $GLOBALS['wwwroot'] . '/modules/fianet/lib/js/fianet.js
					$payment_complement_informations .= '<a onclick="popuprnp3x();return false;" href="#" title="kwixo3x">';
				} elseif($tab_paiement['technical_code'] == 'kwixo') {
					$payment_complement_informations .= '<a onclick="popuprnp1xrnp();return false;" href="#" title="kwixo1x">';
				}
				if(file_exists($GLOBALS['dirroot'].'/modules/fianet/images/' . $tab_paiement['technical_code'] .'_mini.png')) {
					$payment_complement_informations .= '
			<img src="'.$GLOBALS['wwwroot'].'/modules/fianet/images/' . $tab_paiement['technical_code'] .'_mini.png" alt="'.StringMb::str_form_value($tab_paiement['nom_' . $_SESSION['session_langue']]).'" />
	';
				}
				$payment_complement_informations .= $GLOBALS['STR_MORE_DETAILS'] . '</a></td>';
			}
			$tpl = $GLOBALS['tplEngine']->createTemplate('payment_select.tpl');
			if (!empty($GLOBALS['site_parameters']['payment_alert_text_'.$tab_paiement['technical_code']])) {
				$payment_alert_text = 'bootbox.alert("' .$GLOBALS['site_parameters']['payment_alert_text_'.$tab_paiement['technical_code']] . '");';
				$tpl->assign('payment_alert_text', $payment_alert_text);
			}
			$tpl->assign('technical_code', $tab_paiement['technical_code']);
			$tpl->assign('nom', ($show_site_info_if_needed?get_site_info($tab_paiement):'') . $tab_paiement['nom_' . $_SESSION['session_langue']]);
			$tpl->assign('issel', (in_array($tab_paiement['technical_code'], $selected_payment_technical_code) || count($results_array) == 1));
			if ($tab_paiement['tarif'] != 0) {
				$tpl->assign('fprix_tarif', fprix($tab_paiement['tarif'], true));
			}
			if ($tab_paiement['tarif_percent'] != 0) {
				$tpl->assign('tarif_percent', $tab_paiement['tarif_percent']);
			}
			$tpl->assign('isempty_moneybookers_payment_methods', empty($_SESSION['session_commande']['moneybookers_payment_methods']));
			$tpl->assign('moneybookers_payment_methods', vb($_SESSION['session_commande']['moneybookers_payment_methods']));
			$tpl->assign('moneybookers_active', ($tab_paiement['technical_code'] == 'moneybookers'));
			if (!empty($payment_complement_informations)) {
				$tpl->assign('payment_complement_informations', $payment_complement_informations);
			}
			if(!empty($form_error_object)) {
				$tpl->assign('order_form_payment_methods_error', $form_error_object->text('order_form_payment_methods'));
			}
			$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('STR_TRANSFER', $GLOBALS['STR_TRANSFER']);
			$tpl->assign('STR_ORDER_FORM', $GLOBALS['STR_ORDER_FORM']);
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
			if(count($GLOBALS[$this_js_array_name])>1 && $minify && StringMb::strpos($this_js_array_name, 'nominify') === false) {
				$GLOBALS[$this_js_array_name] = get_minified_src($GLOBALS[$this_js_array_name], 'js', 10800);
			}elseif(!empty($_GET['update']) && $_GET['update'] == 1) {
				foreach($GLOBALS[$this_js_array_name] as $this_key => $this_js_file) {
					$GLOBALS[$this_js_array_name][$this_key] = $this_js_file . (StringMb::strpos($this_js_file, '?')!==false?'&':'?') . time();
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
			if(!empty($GLOBALS[$this_js_array_name]) && StringMb::strpos($this_js_array_name, 'noasync') !== false) {
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
					$output .= '<script src="' . StringMb::str_form_value($js_href) . '"></script>
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
					if(StringMb::substr($this_filename, 0, 2) == '//') {
						// Gestion des chemins de fichiers http/https automatiques
						if(strpos($GLOBALS['wwwroot'], 'https') === 0) {
							$this_filename = 'https:'.$this_filename;
						} else {
							$this_filename = 'http:'.$this_filename;
						}
					}
					$js_content = '
		loadScript("'.StringMb::html_entity_decode($this_filename).'", function(){
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
	if (check_if_module_active('filthypillow')) {
		$output = get_filthypillow_javascript();
	} else {
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
		$(".datepicker").attr("placeholder","'.str_replace(array('d', 'm', 'y'), array(StringMb::substr(StringMb::strtolower($GLOBALS['strDays']), 0, 1), StringMb::substr(StringMb::strtolower($GLOBALS['strMonths']), 0, 1), StringMb::substr(StringMb::strtolower($GLOBALS['strYears']), 0, 1)), str_replace('y', 'yy', $datepicker_format)).'");';
		
		if(!empty($_SERVER['HTTP_USER_AGENT']) && (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad'))) {
			// Quand on rentre la date on ne veut pas avoir le clavier qui s'affiche car on se sert du datepicker
			$GLOBALS['js_ready_content_array'][] = '
				$(".datepicker").prop("readonly", true);
				$(".datepicker").css("background-color", "white");
	';
		}
		if(!empty($GLOBALS['site_parameters']['load_timepicker']) || !empty($GLOBALS['load_timepicker'])) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-ui-timepicker-addon.js';
			if(file_exists($GLOBALS['dirroot'] . '/lib/js/jquery-ui-timepicker-'.$_SESSION['session_langue'].'.js')) {
				// Configuration pour une langue donnée
				$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-ui-timepicker-'.$_SESSION['session_langue'].'.js';
			}
			$datepicker_time_format = str_replace(array('h','%H','%M','%S'), array(":",'HH','mm','ss'), $GLOBALS['time_format_long']);
			$GLOBALS['js_ready_content_array'][] = '
				load_timepicker = true;
				$(".datetimepicker").datetimepicker({
					dateFormat: "'.$datepicker_format.'",
					changeMonth: true,
					changeYear: true,
					showTimePicker: true,
					showSecond: true,
					timeFormat: "'.$datepicker_time_format.'",
					yearRange: "2012:2070"
				});
				$(".datetimepicker").attr("placeholder","'.str_replace(array('HH', 'MM', 'ss', 'd', 'm', 'y', "'"), array('00', '00', '00', StringMb::substr(StringMb::strtolower($GLOBALS['strDays']), 0, 1), StringMb::substr(StringMb::strtolower($GLOBALS['strMonths']), 0, 1), StringMb::substr(StringMb::strtolower($GLOBALS['strYears']), 0, 1), ""), str_replace('y', 'yy', $datepicker_format . ' ' . str_replace('mm', 'MM', $datepicker_time_format))).'");
	';
			if(!empty($_SERVER['HTTP_USER_AGENT']) && (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad'))) {
				// Quand on rentre la date on ne veut pas avoir le clavier qui s'affiche car on se sert du datepicker
				$GLOBALS['js_ready_content_array'][] = '
				$(".datetimepicker").prop("readonly", true);
				$(".datetimepicker").css("background-color", "white");
	';
			}
		}
	}
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
			$GLOBALS['css_files'][$this_key] = $this_css_file . (StringMb::strpos($this_css_file, '?')!==false?'&':'?') . time();
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
 * @param integer $cache_duration_in_seconds
 * @return
 */
function output_general_http_header($page_encoding = null, $cache_duration_in_seconds = null) {
	if(empty($page_encoding)) {
		$page_encoding = GENERAL_ENCODING;
	}
	header('Content-type: text/html; charset=' . $page_encoding);
	if (!empty($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')!== false) {
		// Demande à IE de ne pas se mettre dans un mode de compatibilité => permet de bénéficier des dernières avancées de la version utilisée
		header('X-UA-Compatible: IE=edge,chrome=1');
	}
	if(!empty($cache_duration_in_seconds)) {
		header('Pragma: public');
		header('Cache-Control: public, max-age=' . $cache_duration_in_seconds . ', must-revalidate');
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
		header(StringMb::substr(vb($_SERVER['SERVER_PROTOCOL'], 'HTTP/1.0'), 0 , 10) . " 301 Moved Permanently");
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
		if(StringMb::strpos(get_current_url(true),'chart-data.php')===false){
			$_SESSION['session_redirect_after_login'] = get_current_url(true);
		}
		if(StringMb::strpos($priv, 'admin') === 0 && a_priv("admin*")) {
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
 * User not logged in ==> we redirect to login page.
 * 
 * @return
 */
function necessite_identification()
{
	if (!est_identifie()) {
		$_SESSION['session_redirect_after_login'] = get_current_url(true);
		redirect_and_die(get_url('membre', array('error' => 'login_rights')));
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
	if (!empty($_GET['langue']) && StringMb::strlen($_GET['langue']) == 2) {
		$return_lang = StringMb::strtolower(trim($_GET['langue']));
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
		$return_lang = StringMb::strtolower(StringMb::substr(trim($temp[0]), 0, 2));
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
	return StringMb::substr($return_lang, 0, 2);
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

	if (!check_if_module_active('url_rewriting') || (!empty($GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]) && !empty($GLOBALS['langs_array_by_wwwroot'][$GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]]) && count($GLOBALS['langs_array_by_wwwroot'][$GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]]) > 1)) {
		// Comme le chemin pour une page dans cette langue n'est pas spécifique, alors on doit préciser la langue quand on veut changer de page
		// Il ne faut pas compter les GET pour savoir si on rajoute ? ou &, car les GET peuvent venir du décodage de l'URL Rewriting => il faut regarder uniquement REQUEST_URI
		if (StringMb::strpos($this_url_lang, '?') === false) {
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
	$subdomain_separator = '.';
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
		if(strpos($this_url_lang, $this_lang . '.') !== false) {
			$subdomain_separator = '--';
		}
		// On remet le sous-domaine si nécessaire
		$this_url_lang = str_replace(array('://', 'www.'), array('://' . $subdomain . $subdomain_separator, ''), $this_url_lang);
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
	if (!$with_get && StringMb::strpos($url, '?') !== false) {
		$url = StringMb::substr($url, 0, StringMb::strpos($url, '?'));
	} elseif(!empty($take_away_get_args_array)) {
		// On évite par exemple  les problèmes de parenthèses encodées par PHP mais pas par apache
		$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
		foreach($take_away_get_args_array as $key) {
			if(isset($_GET[$key])) {
				$this_value = $_GET[$key];
				if(is_array($this_value)) {
					continue;
				}
				$url = str_replace(array(urlencode($key).'='.urlencode($this_value), urlencode($key).'='.str_replace($entities, $replacements, urlencode($this_value)), $key.'='.$this_value), '', $url);
				$url = str_replace(array('?&', '&&'), array('?','&'), $url);
			}
		}
		if (StringMb::substr($url, - 1) == '?' || StringMb::substr($url, - 1) == '&') {
			$url = StringMb::substr($url, 0, StringMb::strlen($url) - 1);
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
		$excluded_get[] = 'update_thumbs';
		$excluded_get[] = 'brand';
		if(!empty($params['type']) && $params['type']=='error404') {
			$excluded_get[] = 'type';
		}
		if (check_if_module_active('url_rewriting')) {
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
				$uri = str_replace('-' . StringMb::rawurlencode(vn($_GET['page'])) . '-' . StringMb::rawurlencode(vn($_GET['catid'])) . '.html', '-[PAGE]-' . StringMb::rawurlencode(vn($_GET['catid'])) . '.html', $uri);
			} elseif (check_if_module_active('vitrine') && get_current_url(false, true) == '/' . vn($_GET['page']) . '.html') {
				// Page de boutique
				$uri = str_replace('/' . StringMb::rawurlencode(vn($_GET['page'])) . '.html', '/[PAGE]' . '.html', $uri);
			} elseif (check_if_module_active('vitrine') && get_current_url(false, true) == '/') {
				// Page de boutique : accueil
				$uri .= '[PAGE].html';
			} elseif (check_if_module_active('vitrine') && StringMb::rawurldecode(get_current_url(false, true)) == StringMb::rawurldecode('/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/' . $GLOBALS['STR_MODULE_ANNONCES_URL_LIST_SHOWCASE'] . '-' . StringMb::rawurlencode(vn($_GET['page'])) . '.html')) {
				// Page de liste des vitrines
				$uri = str_replace('-' . StringMb::rawurlencode(vn($_GET['page'])) . '.html', '-[PAGE].html', $uri);
			} elseif (check_if_module_active('vitrine') && strpos(get_current_url(false, true), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-')===0) {
				// Page de boutique non verified
				$excluded_get[] = 'bt';
				$uri = str_replace('/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-'.StringMb::rawurlencode(vn($_GET['page'])), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'', $uri);
				// On fait le remplacement en deux étapes pour bien capter les URL STR_MODULE_ANNONCES_URL_VITRINE-... et STR_MODULE_ANNONCES_URL_VITRINE tout court
				$uri = str_replace('/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'], '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-[PAGE]', $uri);
			} elseif (check_if_module_active('vitrine') && strpos(get_current_url(false, true), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-'.StringMb::rawurlencode(vn($_GET['page'])) . '-')===0) {
				// Page de boutique non verified
				$excluded_get[] = 'bt';
				$uri = str_replace('/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-'.StringMb::rawurlencode(vn($_GET['page'])), '/'.$GLOBALS['STR_MODULE_ANNONCES_URL_VITRINE'].'-[PAGE]', $uri);
			} elseif (StringMb::rawurldecode(get_current_url(false, true)) == StringMb::rawurldecode('/produits/' . StringMb::rawurlencode(vb($_GET['search'])) . '.html')) {
				$excluded_get[] = 'search';
				$uri = str_replace('.html', '-[PAGE].html', $uri);
			} elseif (StringMb::rawurldecode(get_current_url(false, true)) == StringMb::rawurldecode('/produits/' . StringMb::rawurlencode(vb($_GET['search'])) . '-' . StringMb::rawurlencode(vn($_GET['page'])) . '.html')) {
				$excluded_get[] = 'search';
				$uri = str_replace('-' . StringMb::rawurlencode(vn($_GET['page'])) . '.html', '-[PAGE].html', $uri);
			} elseif (check_if_module_active('annonces') && !empty($_GET['country']) && strpos(get_current_url(false, true),'-' . StringMb::rawurlencode(vb($_GET['country'])) . '.html') !== false) {
				// Page de liste des vitrines
				$excluded_get[] = 'country';
			}
			if (check_if_module_active('annonces')) {
				foreach(array('/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/'.$GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'].'-', '/kopen/supplier-research-', '/kaufen/supplier-research-', '/buy/supplier-research-', '/buy/supplier-research-', '/acheter/recherche-fournisseur-', '/comprar/busqueda-proveedor-') as $this_url_rewriting_main_expression) {
					if(    StringMb::rawurldecode(get_current_url(false, true)) == StringMb::rawurldecode($this_url_rewriting_main_expression . StringMb::rawurlencode(vn($_GET['page'])) . '-' . StringMb::rawurlencode(vb($_GET['search'])) . '.html')
						|| StringMb::rawurldecode(get_current_url(false, true)) == StringMb::rawurldecode($this_url_rewriting_main_expression . StringMb::rawurlencode(vn($_GET['page'])) . '-' . StringMb::rawurlencode(urlencode(vb($_GET['search']))) . '.html')) {
						$excluded_get[] = 'search';
						// Si l'URL contient un +, pas encodé ou encodé en %2B, il faut le gérer quoiqu'il arrive => on a deux possibilités dans le str_replace
						// La troisième est là pour couvrir des URL du type : /acheter/recherche-fournisseur-54-pc+.html?search=pc+
						// Rappel : dans une URL réécrite, la partie en dehors du GET est gérée du type StringMb::rawurlencode (et + vaut normalement %2B), et la partie GET est gérée par urlencode (et espace vaut +)
						$uri = str_replace($this_url_rewriting_main_expression, '/' . $GLOBALS['STR_MODULE_ANNONCES_URL_BUY'] . '/'.$GLOBALS['STR_MODULE_PREMIUM_URL_ADS_BY_KEYWORD'].'-', str_replace(array('-' . StringMb::rawurlencode(vn($_GET['page'])) . '-' . StringMb::rawurlencode(vb($_GET['search'])) . '.html', '-' . vn($_GET['page']) . '-' . vb($_GET['search']) . '.html', '-' . vn($_GET['page']) . '-' . urlencode(vb($_GET['search'])) . '.html'), '-[PAGE]-' . StringMb::rawurlencode(vb($_GET['search'])) . '.html', $uri));
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
		// pas de langue passé en paramètre. Il faut récupérer la langue principal du site. $GLOBALS['lang_codes'] est rempli selon les contraintes du site en cours, et dans le bon ordre. On peut prendre le premier éléments de ce tableau.
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
			$main_langs[$lang] = StringMb::strtolower($lang) . '_' . StringMb::strtoupper($lang);
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
		setlocale(LC_TIME, $main_langs[$lang] . '.UTF8', StringMb::strtolower($lang) . '.UTF8', vb($variations_langs[$lang], StringMb::strtolower($lang)) . '.utf8', vb($variations_langs2[$lang], StringMb::strtolower($lang)) . '.utf8', $main_langs[$lang], StringMb::strtolower($lang), vb($variations_langs[$lang], StringMb::strtolower($lang)), vb($variations_langs2[$lang], StringMb::strtolower($lang)));
		// Déclaration du nom de la boutique
		$GLOBALS['site'] = vb($GLOBALS['site_parameters']['nom_' . $lang]);
		if (empty($GLOBALS['site'])) {
			$GLOBALS['site'] = $GLOBALS['wwwroot'];
		}
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
			if($load_modules_files && !empty($GLOBALS['modules_lang_folders_to_load_array'])){
				// Les variables de langue dans les modules sont plus prioritaires que celles de lib/lang/
				// => la surcharge des valeurs STR_XXX par défaut est possible
				$default_lang_if_file_not_found = 'en';
				ksort($GLOBALS['modules_lang_folders_to_load_array']);
				foreach($GLOBALS['modules_lang_folders_to_load_array'] as $this_directory) {
					if(StringMb::strpos($this_directory, $GLOBALS['dirroot']) === false) {
						$this_directory = $GLOBALS['dirroot'] . $this_directory;
					}
					if(file_exists($this_directory . $this_lang . ".php")) {
						include($this_directory . $this_lang . ".php");
					} elseif(file_exists($this_directory . $default_lang_if_file_not_found . ".php")) {
						include($this_directory . $default_lang_if_file_not_found . ".php");
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
				if(strpos($this_global, '_URL_') === false) {
					$this_config = 'replace_words_in_lang_files';
				} else {
					$this_config = 'replace_url_words_in_lang_files';
				}
				// Possibilité de ne remplacer du texte que dans l'administration ou que dans le front office
				$suffixes_array = array('');
				if(defined('IN_PEEL_ADMIN')) {
					$suffixes_array[] = '_in_back_office';
				} else {
					$suffixes_array[] = '_in_front_office';
				}
				foreach($suffixes_array as $this_suffix) {
					if(!empty($GLOBALS['site_parameters'][$this_config . $this_suffix]) && is_array($GLOBALS['site_parameters'][$this_config . $this_suffix])) {
						// Remplacement de mots clés par des versions personnalisées pour le site
						foreach($GLOBALS['site_parameters'][$this_config . $this_suffix] as $replaced => $new) {
							if(!is_array($this_value) && strpos($this_value, $replaced) !== false) {
								$GLOBALS['before_replace_words_in_lang_files'][$this_global] = $this_value;
								$this_value = str_replace($replaced, $new, $this_value);
								$this_value = str_replace('/'.$new, '/'.$replaced, $this_value);
								$GLOBALS[$this_global] = $this_value;
							}
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
				if(vb($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) == 'checkbox') {
					$GLOBALS['site_parameters']['user_mandatory_fields']['id_categories'] = 'STR_ERR_FIRST_CHOICE';
				} elseif (vb($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) == 'select') {
					$GLOBALS['site_parameters']['user_mandatory_fields']['id_cat_1']  = 'STR_ERR_FIRST_CHOICE';
				}
				if (defined('IN_REGISTER')) {
					$GLOBALS['site_parameters']['user_mandatory_fields']['cgv_confirm'] = 'STR_ERR_CGV';
				}
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
			// NB : Fineuploader est désactivé pour IE <=8
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
			// D'abord on récupère les langues actives
			$sql_or_array[] = get_filter_site_cond('langues');
			if(defined('IN_PEEL_ADMIN')) {
				// On récupère la liste des langues administrables (ce sera avec un OR par rapport à la condition ci-dessus des langues actives)
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
		$GLOBALS['lang_etat'] = array();
		while ($lng = fetch_assoc($resLng)) {
			if($lng['etat'] == 1) {
				$GLOBALS['lang_codes'][] = $lng['lang'];
				$GLOBALS['admin_lang_codes'][] = $lng['lang'];
				if(empty($lng['admin_disallowed'])) {
					$GLOBALS['admin_lang_codes_with_modify_rights'][] = $lng['lang'];
				}
			} elseif($lng['etat'] == -1 || (!empty($_GET['langue']) && $lng['lang'] == $_GET['langue'])) {
				// Langue administrable mais pas en production
				if(defined('IN_PEEL_ADMIN')) {
					$GLOBALS['lang_codes'][] = $lng['lang'];
				}
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
			if(!empty($lng['load_default_lang_files_before_main_lang'])) {
				$GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$lng['lang']] = explode(',', $lng['load_default_lang_files_before_main_lang']);
			}
		}
		if(empty($GLOBALS['lang_codes'])){
			// Si on n'a pas trouvé au moins une langue, on prend les langues même inactives
			$GLOBALS['lang_codes'] = array_keys($GLOBALS['lang_etat']);
		}
		if(empty($GLOBALS['lang_codes'])){
			// Si on n'a toujours pas trouvé au moins une langue, on renseigne l'anglais par défaut
			$GLOBALS['lang_flags']['en'] = '/images/en.png';
			$GLOBALS['lang_names']['en'] = 'English';
			$GLOBALS['lang_etat']['en'] = 1;
			$GLOBALS['lang_url_rewriting']['en'] = '';
			$GLOBALS['lang_codes'][] = 'en';
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
			closedir($handle);
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
 * Renvoyer le répertoire racine apparent de l'URL courante
 *
 * @return
 */
function get_apparent_folder() {
	$formatted_dirroot = str_replace('\\', '/', $GLOBALS['dirroot']);
	$file_called_real_path = str_replace('\\', '/', @realpath('./'));
	if (!empty($_SERVER['SCRIPT_FILENAME']) && (empty($file_called_real_path) || (strpos($file_called_real_path, $formatted_dirroot) === false && strpos(str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])), $formatted_dirroot) !== false))) {
		// On gère les cas d'incohérences entre realpath et SCRIPT_FILENAME chez certains hébergeurs
		$file_called_real_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME']));
	}
	if (strpos($file_called_real_path, 'public_html') === false && strpos($formatted_dirroot, 'public_html') !== false) {
		$file_called_real_path = str_replace('private_html/', 'public_html/', $file_called_real_path);
	}
	if (!empty($file_called_real_path) && strpos($file_called_real_path, $formatted_dirroot) !== false) {
		// Cas normal
		if ($formatted_dirroot == $file_called_real_path) {
			$peel_subfolder = '';
		} else {
			// CAS PARTICULIER : Sur 1&1 par exemple quand on est en multidomaine, le début de dirroot est /kunden/ alors que sinon il n'y a pas /kunden
			// Pour être le plus compatible possible, on commence donc par strpos($file_called_real_path, $formatted_dirroot)
			$peel_subfolder = substr($file_called_real_path, strpos($file_called_real_path, $formatted_dirroot) + strlen($formatted_dirroot));
		}
	} else {
		// Au cas où __FILE__ et SCRIPT_FILENAME ne seraient pas cohérents à cause d'alias de dossiers sur l'hébergement
		// Dans ce cas on considère arbitrairement qu'il n'y a pas de sous-dossier à prendre
		$peel_subfolder = '';
	}
	$file_called_relative_path = str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
	$apparent_folder = substr($file_called_relative_path, 0, strlen($file_called_relative_path) - strlen($peel_subfolder));
	if (empty($apparent_folder) || substr($apparent_folder, strlen($apparent_folder) - 1) != '/') {
		$apparent_folder .= '/';
	}
	if (substr($apparent_folder, 0, 1) != '/') {
		// Protection contre des requêtes de hackers du type GET http://xxxxx/  qui ne commencent anormalement pas par / et qui pourraient permettre d'inclure l'URL dans wwwroot
		$apparent_folder = '/' . $apparent_folder;
	}
	return $apparent_folder;
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
 * Définit les paramètres de base de PHP
 *
 * @return
 */
function handle_php_default_setup() {
	if (strval(floatval('1000.1')) != '1000.1') {
		// Homogénéisation des configurations serveur : avoir toujours une manipulation interne des décimales sous forme de point (évite notamment des problèmes lors d'insertions de float en SQL)
		@setlocale(LC_NUMERIC, 'C');
	}
	@ini_set('scream.enabled', false); // Désactivation de scream qui altère le fonctionnement normal de error_reporting
	@ini_set('default_socket_timeout', 4); // Eviter de bloquer sur la récupération d'une information venant d'un serveur extérieur
	@ini_set('display_errors', DISPLAY_ERRORS_DURING_INIT); // Cette valeur est ensuite modifiée quand on accède à la base de données suivant la configuration du site
	@ini_set("gd.jpeg_ignore_warning", 1); // Ignore les alertes créées par la fonction jpeg2wbmp() et la fonction imagecreatefromjpeg()

	// Configuration de l'affichage des var_dump. -1 => Supression de la limite des résultats retournés : http://xdebug.org/docs/display
	@ini_set('xdebug.var_display_max_depth','-1');
	@ini_set('xdebug.var_display_max_children','-1');
	@ini_set('xdebug.var_display_max_data','-1');
	// Sur 1and1 par exemple les fonctions ci-dessous ne sont pas fonctionnelles, c'est donc via les ini_set que ça marche ci-après
	if (function_exists('mb_internal_encoding')) {
		@mb_internal_encoding(GENERAL_ENCODING);
	}
	if (function_exists('mb_detect_order')) {
		@mb_detect_order(GENERAL_ENCODING);
	}
	if (function_exists('mb_http_input')) {
		@mb_http_input(GENERAL_ENCODING);
	}
	if (function_exists('mb_http_output')) {
		@mb_http_output(GENERAL_ENCODING);
	}
	// En PHP >= 5.6 les ini_set ci-dessous ne sont plus fonctionnels, il faut passer par default_charset ci-après
	@ini_set('mbstring.internal_encoding', GENERAL_ENCODING);
	@ini_set('mbstring.detect_order', GENERAL_ENCODING);
	@ini_set('mbstring.http_input', GENERAL_ENCODING);
	@ini_set('mbstring.http_output', GENERAL_ENCODING);
	@ini_set('mbstring.http_output', GENERAL_ENCODING);
	// Spécial PHP >= 5.6
	@ini_set('default_charset', GENERAL_ENCODING);

	// la fonction date_default_timezone_set existe depuis PHP 5.1.0
	if (version_compare(PHP_VERSION, '5.1.0', '>=')) {
		// Supprimer les warnings dans certains cas de configuration serveur en version PHP >= 5.3
		@date_default_timezone_set(@date_default_timezone_get());
	}
	if (function_exists('set_magic_quotes_runtime')) {
		@set_magic_quotes_runtime(0);
	}
}

/**
 * Empêche les effets de register_globals
 *
 * @param boolean $templates_force_compile
 * @return
 */
function handle_register_globals() {
	if(!empty($_SERVER['RAW_HTTP_COOKIE'])){
		// On complète les informations de cookie si le serveur envoie les cookies encryptés, et donc refuse les cookies provenant du navigateur par sécurité
		foreach(explode(';', $_SERVER['RAW_HTTP_COOKIE']) as $this_cookie){
			if(strpos($this_cookie, '=') !== false){
				list($key,$value) = explode('=', $this_cookie, 2);
				$key = rawurldecode(trim($key));
				if(!array_key_exists($key, $_COOKIE)){
					$_COOKIE[$key] = rawurldecode(trim($value));
				}
			}
		}
	}
	if (!function_exists('ini_get') || @ini_get('register_globals')) {
		// Code à laisser absolument en début de fichier
		// Protection si register_globals est à ON
		foreach (array('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES') as $array_name) {
			foreach ($$array_name as $key => $value) {
				if (isset($GLOBALS[$key])) {
					unset($GLOBALS[$key]);
				}
				if (isset($$key)) {
					// Au cas où pour d'anciennes versions de PHP
					unset($$key);
				}
			}
			unset($key);
			unset($value);
		}
	}
}

/**
 * Appelle le moteur de template pour définir $GLOBALS['tplEngine']
 *
 * @param boolean $templates_force_compile
 * @return
 */
function handle_template_engine_init($templates_force_compile = false) {
	// Chargement du moteur de template : Smarty ou Twig
	include($GLOBALS['dirroot'] . "/lib/templateEngines/EngineTpl.php");
	/* @var $GLOBALS['tplEngine'] EngineTpl */
	if(!in_array(vb($GLOBALS['site_parameters']['template_engine']), array('smarty', 'twig'))) {
		$GLOBALS['site_parameters']['template_engine'] = 'smarty';
	}
	if($GLOBALS['site_parameters']['template_engine'] == 'twig') {
		require $GLOBALS['dirroot'] . '/lib/templateEngines/twig/Autoloader.php';
		Twig_Autoloader::register();
	}
	$GLOBALS['tplEngine'] = EngineTpl::create($GLOBALS['site_parameters']['template_engine'], $GLOBALS['repertoire_modele'] . '/' . $GLOBALS['site_parameters']['template_engine'] . '/', $templates_force_compile, defined('DEBUG_TEMPLATES') && DEBUG_TEMPLATES);
}

/**
 * Affiche ou non le fait que le site est suspendu en empêchant l'accès au site avec erreur HTTP 503
 *
 * @return
 */
function handle_site_suspended() {
	if(!empty($GLOBALS['site_parameters']['site_suspended'])) {
		if (!empty($GLOBALS['site_parameters']['site_suspended_excluded_ips'])) {
			if($GLOBALS['site_parameters']['site_suspended_excluded_ips'] == 'display_errors_for_ips') {
				$GLOBALS['site_parameters']['site_suspended_excluded_ips'] = $GLOBALS['site_parameters']['display_errors_for_ips'];
			}
			foreach(explode(',', str_replace(array(' ', ';'), array(',', ','), $GLOBALS['site_parameters']['site_suspended_excluded_ips'])) as $this_ip_part) {
				if (!empty($this_ip_part) && ($this_ip_part == '*' || strpos($_SERVER['REMOTE_ADDR'], $this_ip_part) === 0)) {
					$allow_access_if_suspended = true;
					break;
				}
			}
		}
		if(a_priv('admin*', true) || a_priv('util_maintenance', true) || (!empty($GLOBALS['site_parameters']['site_suspended_allow_user_not_admin']) && est_identifie())) {
			// Utilisateur loggué en tant qu'admin, ou utilisateur simple et site l'autorisant
			// Ou utilisateur avec le statut spécifique "Utilisateur maintenance".
			// NB : par défaut on n'autorise pas la création de compte, juste le login sur /membre.php
			$allow_access_if_suspended = true;
		}
		if(!empty($GLOBALS['site_parameters']['site_suspended_allow_user_register']) && defined('IN_REGISTER')) {
			// Page de création de compte autorisée
			$allow_access_if_suspended = true;
		}
		if (!IN_INSTALLATION && !defined('IN_QRCODE') && !defined('IN_PATHFILE') && !defined('IN_IPN') && !defined('IN_CRON') && !defined('IN_PEEL_ADMIN') && !defined('IN_ACCES_ACCOUNT') && !defined('IN_GET_PASSWORD') && !defined('IN_FINE_UPLOADER') && !a_priv('admin*', true) && empty($allow_access_if_suspended)) {
			if (!empty($GLOBALS['site_parameters']['site_suspended_with_redirection_to_home'])) {
				if (!defined('IN_REGISTER') && !defined('IN_ACCES_ACCOUNT') && !defined('IN_NEWSLETTER')) {
					// Redirection vers la home, sauf si on est sur l'une de ces 4 pages : Home (pour éviter un redirection en boucle), enregistrement (pour permettre de créer un compte), membre (pour permettre aux admins de se connecter) et newsletter (pour permettre l'inscription à la newsletter)
					// On fait une redirection temporaire, le mode maintenance n'est pas définitif. Le deuxième paramètre de redirect_and_die $permanent_redirection a pour valeur par défaut false.
					if ($GLOBALS['wwwroot'] != $GLOBALS['site_parameters']['site_suspended_with_redirection_to_home']) {
						redirect_and_die($GLOBALS['site_parameters']['site_suspended_with_redirection_to_home']);
					} elseif(!defined('IN_HOME')) {
						redirect_and_die(get_url('/'));
					}
				}
			} else {
				header(StringMb::substr(vb($_SERVER['SERVER_PROTOCOL'], 'HTTP/1.0'), 0 , 10) . " 503 Service Unavailable", true, 503);
				header('Status: 503 Service Unavailable');
				header('Retry-After: 7200');
				echo '<div style="text-align:center; font-size:20px; font-family: Arial,Helvetica Neue,Helvetica,sans-serif; "><br /><br /><b>' . $GLOBALS['STR_UPDATE_WEBSITE'] . '</b><br /><br /><img src="' . $GLOBALS['wwwroot'] . '/modeles/' . $GLOBALS['site_parameters']['template_directory'] . '/images/site_under_work.jpg" style="max-width:100%" /><br /><br />' . $GLOBALS['STR_THANKS_UNDERSTANDING'] . '</div>';
				die();
			}
		}
	}
	return null;
}

/**
 * Gère les redirections définies dans $GLOBALS['site_parameters']['redirections']
 *
 * @return
 */
function handle_setup_redirections($url, $mode = 'redirect') {
	// Redirections définies par variables de configuration
	if (!empty($GLOBALS['site_parameters']['redirections'])) {
		// Si on veut en multisite alerter des chargements pour tel ou tel site_id et qu'on recharge $GLOBALS['site_parameters'] ça pose des problèmes si on utilise une variable static pour savoir si on est passé ou non dans le traitement de template_tags_replace. Donc on regarde systématiquement si on a besoin ou non de remplacer des tags
		if(strpos(serialize($GLOBALS['site_parameters']['redirections']), '[')!==false) {
			$GLOBALS['site_parameters']['redirections'] = template_tags_replace($GLOBALS['site_parameters']['redirections'], array(), defined('SKIP_SET_LANG'), null, null, true);
		}
		// Format : URL_from => URL_to  OU  URL_from => URL_to,301  
		// Si pas de slash à la fin d'un domaine, alors ça concerne tout le domaine - sinon juste une page
		foreach($GLOBALS['site_parameters']['redirections'] as $url_from => $url_to) {
			$temp = explode(',', $url_to, 2);
			if(StringMb::substr($url_from, -1) == '*') {
				$url_from = StringMb::substr($url_from, 0, StringMb::strlen($url_from)-1);
				$redirect_all_sub = true;
			} else {
				$redirect_all_sub = false;
			}
			if(!empty($temp[0]) && !empty($url_from) && $temp[0] != $url_from) {
				if (StringMb::strpos($url, $url_from) === 0) {
					if($redirect_all_sub) {
						$new_url = $temp[0];
					} else {
						$new_url = str_replace($url_from, $temp[0], $url);
					}
					if($new_url != $url) {
						if($mode == 'redirect') {
							redirect_and_die($new_url, intval(vb($temp[1], 301)) == 301);
						} else {
							$url = $new_url;
						}
					}
				}
			}
		}
	}
	return $url; 
}


/**
 * Gère les sessions PHP et des protections contre des vols de session
 *
 * @return
 */
function handle_sessions() {
	// Paramétrage des sessions
	// Pour permettre d'avoir à la fois des cookies de session valides pour N sous-domaines, et à la fois
	// permettre que plusieurs boutiques PEEL puissent tourner dans des sous-domaines différents, on prend
	// un nom de cookie de session différent pour chaque installation de PEEL.
	$GLOBALS['session_cookie_name'] = vb($GLOBALS['site_parameters']['session_cookie_basename']) . substr(md5(vb($GLOBALS['site_parameters']['session_cookie_unique_part'], $GLOBALS['wwwroot_main'])), 0, 8);
	$user_agent_salt = vb($GLOBALS['site_parameters']['session_user_agent_salt'], 'GcFsD5EOvgSvQFtL4nIy');
	if (!empty($GLOBALS['site_parameters']['sessions_duration'])) {
		@ini_set('session.gc_maxlifetime', 60 * $GLOBALS['site_parameters']['sessions_duration']);
		@ini_set('session.cache_expire', $GLOBALS['site_parameters']['sessions_duration']);
	}
	@ini_set('session.use_cookies', '1');
	@ini_set('session.use_only_cookies', '1'); // évite les attaques avec session id dans l'URL
	@ini_set('session.use_trans_sid', '0'); // empêche la propagation des SESSION_ID dans les URL
	@ini_set('session.hash_function', '1'); // Hash avec SHA-1 et non pas MD5
	@ini_set('url_rewriter.tags', '');
	@ini_set('session.name', $GLOBALS['session_cookie_name']);
	if (!empty($GLOBALS['site_parameters']['session_save_path'])) {
		@ini_set('session.save_path', $GLOBALS['site_parameters']['session_save_path']);
	}
	if (vb($GLOBALS['site_parameters']['force_sessions_for_subdomains']) && get_site_domain(true) && strpos($GLOBALS['wwwroot'], '://127.0.0.1') === false && strpos($GLOBALS['wwwroot'], '://localhost') === false) {
		// On ne passe pas ici si l'URL est à la base d'IP et non pas de domaine
		@ini_set('session.cookie_domain', '.' . get_site_domain());
	}

	session_start();
	if (!isset($_SESSION['session_initiated']) && isset($_COOKIE[$GLOBALS['session_cookie_name']])) {
		// Protection contre les fixations de session : l'utilisateur déclare qu'il possède une session alors que le serveur ne la connait pas
		// => il ne faut pas prendre l'identifiant de session proposé par l'utilisateur
		@session_regenerate_id();
	}
	$_SESSION['session_initiated'] = true;
	// Protection contre les vols de sessions
	// On ne se base pas sur les IP car certains FAI ne permettent pas de naviguer avec une seule IP (AOL,...)
	// Lorsqu'on installe un plugin sur un navigateur ou qu'on le met à jour, le HTTP_USER_AGENT est modifié, mais on redémarre le navigateur
	// Donc cette protection est bien compatible avec tout type de user_agents
	if (!isset($_SERVER['HTTP_USER_AGENT'])) {
		$_SERVER['HTTP_USER_AGENT'] = '';
	}
	if (empty($GLOBALS['site_parameters']['disable_session_user_agent_check']) && isset($_SESSION['session_user_agent'])) {
		if ($_SESSION['session_user_agent'] != sha1($user_agent_salt . $_SERVER['HTTP_USER_AGENT'])) {
			// On suppose qu'il y a vol de session => on la désactive
			session_unset();
			session_destroy();
			@session_regenerate_id(true);
			// On redémarre une nouvelle session après une redirection
			session_start();
			// On prend le nouveau user_agent comme la référence pour cette session
			$_SESSION['session_user_agent'] = sha1($user_agent_salt . $_SERVER['HTTP_USER_AGENT']);
			$_SESSION['session_initiated'] = true;
		}
	} else {
		$_SESSION['session_user_agent'] = sha1($user_agent_salt . $_SERVER['HTTP_USER_AGENT']);
	}
	// Initialisation de SESSION si nécessaire
	if (!isset($_SESSION)) {
		$_SESSION = array();
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
	$parameter_needing_full_rights_prefix = array('multisite_');
	if (!IN_INSTALLATION) {
		if(empty($lang)) {
			// On récupère l'id du site si on est en multisite
			$sql = "SELECT c.site_id, c.string
				FROM peel_configuration c
				LEFT JOIN peel_langues l ON l.etat = '1' AND l.url_rewriting LIKE '%.%' AND " . get_filter_site_cond('langues', 'l', false, $forced_site_id) . "
				WHERE c.technical_code='wwwroot' AND ";
			if ($forced_site_id === null) {
				if(empty($GLOBALS['site_parameters']['multisite_http_https_different_site_id_allow'])) {
					$this_wwwroot_array[] = str_replace('http://', 'https://', $GLOBALS['wwwroot']);
					$this_wwwroot_array[] = str_replace('https://', 'http://', $GLOBALS['wwwroot']);
				} else {
					$this_wwwroot_array[] = $GLOBALS['wwwroot'];
				}
				foreach($this_wwwroot_array as $this_wwwroot) {
					$sql_cond_array[] = "c.string='".real_escape_string($this_wwwroot)."' OR REPLACE(c.string,'www.','')='".real_escape_string($this_wwwroot)."' OR (l.url_rewriting LIKE '%.%' AND (REPLACE(c.string,'www.',l.url_rewriting)='".real_escape_string($this_wwwroot)."' OR l.url_rewriting='".real_escape_string($this_wwwroot)."'))";
				}
				$sql .= "(" . implode(' OR ', $sql_cond_array) . ")";
			} else {
				$sql .= get_filter_site_cond('configuration', 'c', false, $forced_site_id);
			}
			$sql .= "
				ORDER BY IF(c.technical_code='wwwroot',1,0) DESC, l.position ASC
				LIMIT 1";
			$query = query($sql);
			if (empty($query)) {
				// La requête SQL a echoué, on considère que l'on est dans un contexte de migration
				$GLOBALS['database_wrong_version'] = true;
			}
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
					// On met une condition sur site_id='0' pour éviter qu'un site ne s'arroge ce droit indûment
					// Attention si site_id est un SET, il faut obligatoirement les guillemets pour que ça marche
					$sql = "SELECT string
						FROM peel_configuration c
						WHERE c.technical_code='site_id_showed_by_default_if_domain_not_found' AND site_id='0'";
					$query = query($sql);
					if($result = fetch_assoc($query)) {
						$GLOBALS['site_id'] = $result['string'];
					} else {
						// il y a plusieurs sites configurés et site_id_showed_by_default_if_domain_not_found n'est pas trouvé, donc on ne peut pas choisir le site_id du site. Impossible de continuer.
						die('Site configuration not detected');
					}
				}

				// Il faut définir $GLOBALS['wwwroot_main'] 
				if ($this_count_wwwroot > 0) {
					// A ce stade $GLOBALS['site_id'] est défini et wwwroot est défini, on va chercher le wwwroot associé.
					$sql = "SELECT string
						FROM peel_configuration c
						WHERE c.technical_code='wwwroot' AND ".get_filter_site_cond('configuration', 'c')."
						ORDER BY site_id DESC
						LIMIT 1";
					$query = query($sql);
					if($result = fetch_assoc($query)) {
						$GLOBALS['wwwroot_main'] = $result['string'];
					} else {
						// Il y a bien un wwwroot défini dans la base de données, mais pas pour ce site.
						die('Site configuration not detected');
					}
				} else {
					// pas de wwwroot dans la BDD, wwwroot sera detected_wwwroot qui est défini dans configuration.inc.php.
				}
			}
			if (!defined('IN_PEEL_ADMIN') && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
				// En front office, les pages sont appelées en https => on force https dans wwwroot_main pour que toutes les ressources chargées par le site soient en https, sinon le contenu est bloqué par certains navigateurs.
				$GLOBALS['wwwroot_main'] = str_replace('http://', 'https://', $GLOBALS['wwwroot_main']);
			}
		}
		// Initialisation des paramètres du site
		$sql = "SELECT *
			FROM peel_configuration
			WHERE etat='1' AND ";
		if ($forced_site_id === null) {
			$sql .= get_filter_site_cond('configuration', null, false, $GLOBALS['site_id']) . " AND ";
		} else {
			$sql .= get_filter_site_cond('configuration', null, false, $forced_site_id) . " AND ";
		}
		if(empty($lang)) {
			$sql .= "lang='' AND technical_code NOT LIKE 'STR_%'";
		} else {
			$sql .= "(lang='" . real_escape_string($lang) . "' OR lang='')";
		}
		$sql .= "
			ORDER BY IF(site_id='0', 0, 1) ASC, IF(lang='', 0, 1) ASC, technical_code ASC";
		// Chargement des paramètres de configuration (PEEL 7+)
		$query = query($sql);
		while($result = fetch_assoc($query)) {
			$skip_parameter = false;
			foreach($parameter_needing_full_rights_prefix as $this_parameter_begin) {
				if(strpos($result['technical_code'], $this_parameter_begin) === 0 && !empty($result['site_id'])) {
					$skip_parameter = true;
				}
			}
			if($skip_parameter) {
				continue;
			}
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
				if(in_array(StringMb::strtolower($result['string']), array('true', 'yes', '1'))){
					$result['string'] = true;
				} elseif(in_array(StringMb::strtolower($result['string']), array('false', 'no', '0'))){
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
			} elseif($result['type'] == 'string' || (empty($result['type']) && StringMb::strpos($result['string'], ':')===false)){
				$result['string'] = str_replace(array('{$GLOBALS[\'repertoire_images\']}', '{$GLOBALS[\'wwwroot\']}', '{$GLOBALS[\'dirroot\']}', ), array(vb($GLOBALS['repertoire_images']), vb($GLOBALS['wwwroot']), $GLOBALS['dirroot']), $result['string']);
			} elseif(!empty($result['string'])) {
				$result['string'] = @unserialize($result['string']);
			}

			if(StringMb::substr($result['technical_code'], 0, 4)== 'STR_') {
				// Variable de langue
				$GLOBALS[$result['technical_code']] = $result['string'];
			} else {
				if(StringMb::strlen($result['technical_code'])== 7 && StringMb::substr($result['technical_code'], 0, 5) == 'logo_' && strpos($result['string'], '//') === false && !empty($result['string'])) {
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
	if(!empty($GLOBALS['site_parameters']['mysql_sql_mode_force'])) {
		// Eviter les problèmes sur MySQL 5 sous Windows
		query("SET @@session.sql_mode='" . vb($GLOBALS['site_parameters']['mysql_sql_mode_force'], 'MYSQL40') . "'");
	}
	if(empty($GLOBALS['site_parameters']['peel_database_version'])) {
		// La version de la base de données est inférieur à 8. On est dans un contexte de migration, il faut forcer le dossier modele à peel9.
		// Les dossiers modeles des versions plus anciennes utilisent des fonctions is_module_XXXX_active qui ne sont plus définies depuis la version 8.
		$GLOBALS['site_parameters']['template_directory'] = 'peel9';
	} else {
		// On prend un dossier de template par défaut si pas défini ou inexistant
		if(!isset($GLOBALS['site_parameters']['template_directory']) || !file_exists($GLOBALS['dirroot'] . "/modeles/" . $GLOBALS['site_parameters']['template_directory'])) {
			$modeles_dir = $GLOBALS['dirroot'] . "/modeles";
			if ($handle = opendir($modeles_dir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && is_dir($modeles_dir . '/' . $file)) {
						if(empty($GLOBALS['repertoire_modele']) || substr($GLOBALS['repertoire_modele'], 0, 4)!='peel') {
							// On prend de préférence un répertoire de nom différent de peelXXX
							$GLOBALS['site_parameters']['template_directory'] = $file;
						}
					}
				}
				closedir($handle);
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
 * @param string $use_index_sql
 * @param string $avoid_pagination_calculation
 * @return
 */
function params_affiche_produits($condition_value1, $unused, $type, $nb_par_page, $mode = 'general', $reference_id = 0, $nb_colonnes, $always_show_multipage_footer = true, $additional_sql_inner = null, $additional_sql_cond = null, $additionnal_sql_having = null, $use_index_sql = null, $avoid_pagination_calculation = false)
{
	if (!empty($GLOBALS['site_parameters']['params_affiche_produits_to_connect'])) {
		if(!est_identifie() && ((!empty($GLOBALS['site_parameters']['params_affiche_produits_to_connect_in_home']) && !defined('IN_HOME')) || (!empty($GLOBALS['site_parameters']['params_affiche_produits_to_connect_in_search']) && !defined('IN_SEARCH')) || (!empty($GLOBALS['site_parameters']['params_affiche_produits_to_connect_in_new']) && !defined('IN_NEW')
		))) {
			redirect_and_die(get_account_url(false, false));
		}
	}
	$sql_cond_array = array();
	$titre = '';
	$affiche_filtre = '';
	$sql_inner = '';
	$params_list = array();
	if (!empty($nb_colonnes) && ($nb_par_page % $nb_colonnes > 0)) {
		$nb_par_page = $nb_par_page + ($nb_colonnes - ($nb_par_page % $nb_colonnes));
	}
	$params_list['small_width'] = vn($GLOBALS['site_parameters']['small_width']);
	$params_list['small_height'] = vn($GLOBALS['site_parameters']['small_height']);
	$params_list['cartridge_product_css_class'] = 'item-column product_per_line_' . $nb_colonnes;
	$display_multipage_template_name = null;
	// On veut éviter de faire une jointure avec la table catégories si ce n'est pas nécessaire dans des cas particuliers.
	// Si on n'autorise pas les produits sans catégorie, on force la jointure avec la table de catégories lors de la recherche
	// On la force aussi si on veut plusieurs résultats quand il y a plusieurs catégories pour un produit
	$join_categories = (empty($GLOBALS['site_parameters']['allow_products_without_category']) || !empty($GLOBALS['site_parameters']['allow_products_multiple_results_if_multiple_categories']));
	if ($type == 'catalogue' || $type == 'brand') {
		$sql_cond_array[] = "p.id_marque='" . intval($condition_value1) . "'";
	} elseif ($type == 'nouveaute') {
		$sql_cond_array[] = "p.on_new='1'";
		$params_list['affiche_filtre'] = affiche_filtre(null, true);
		$titre = $GLOBALS['STR_NOUVEAUTES'];
	} elseif ($type == 'promotion') {
		// Si l'option est activée dans les paramètres du site
		if (vn($GLOBALS['site_parameters']['auto_promo']) == 1) {
			// Si une promotion est appliquée au produit
			$this_sql_cond = "p.promotion>0 OR p.prix_promo>0";
			// Si le module flash est actif
			if (is_flash_active_on_site()) {
				$this_sql_cond .= " OR (p.on_flash='1' AND '" . date('Y-m-d H:i:s', time()) . "' BETWEEN p.flash_start AND p.flash_end)";
			}
			// Si le module Promotions par marque est actif
			if (check_if_module_active('marques_promotion')) {
				$sql_inner .= " LEFT JOIN peel_marques pm ON pm.id = p.id_marque AND " . get_filter_site_cond('marques', 'pm');
				$this_sql_cond .= " OR pm.promotion_percent>0 OR pm.promotion_devises>0 ";
			}
			// Si le module Promotions par catégorie est actif
			if (check_if_module_active('category_promotion')) {
				$this_sql_cond .= " OR c.promotion_percent>0 OR c.promotion_devises>0";
				$join_categories = true;
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
		if(!is_user_bot()) {
			$ids_array = get_category_tree_and_itself($condition_value1, 'parents', 'categories');
			// On regarde si on autorise l'affichage des produits des catégories filles pour cette catégorie
			$sql = 'SELECT allow_show_all_sons_products
				FROM peel_categories
				WHERE id IN ("'.implode('","', real_escape_string($ids_array)).'")';
			$qid = query($sql);
			if ($cat = fetch_assoc($qid)) {
				if($cat['allow_show_all_sons_products']) {
					$GLOBALS['allow_show_all_sons_products'] = true;
					if(!empty($_GET['sons'])) {
						$GLOBALS['site_parameters']['category_count_method'] = 'global';
					}
				}
			}
		}
		if (vb($GLOBALS['site_parameters']['category_count_method']) == 'global') {
			$catid_array = get_category_tree_and_itself($condition_value1, 'sons');
		} else {
			$catid_array = array($condition_value1);
		}
		$sql_cond_array[] = "pc.categorie_id IN ('" . implode("','", real_escape_string($catid_array)) . "')";
		$join_categories = true;
		$titre = $GLOBALS['STR_LIST_PRODUCT'];
	} elseif ($type == 'flash') {
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d H:i:s', time()) . "' BETWEEN p.flash_start AND p.flash_end";
		$titre = $GLOBALS['STR_FLASH'];
	} elseif ($type == 'flash_passed') {
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d H:i:s', time()) . "' > flash_end";
		$titre = $GLOBALS['STR_FLASH_PASSED'];
	} elseif ($type == 'user_flash_passed') {
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d H:i:s', time()) . "' > flash_end AND id_utilisateur = " . intval($reference_id);
		$titre = $GLOBALS['STR_FLASH_PASSED'];
	} elseif ($type == 'coming_product_flash') {
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d H:i:s', time()) . "' < flash_start";
		$titre = $GLOBALS['STR_COMING_PRODUCT_FLASH'];
	} elseif ($type == 'user_coming_product_flash') {
		$sql_cond_array[] = "p.on_flash='1' AND '" . date('Y-m-d H:i:s', time()) . "' <= flash_start AND id_utilisateur = " . intval($reference_id);
		$titre = $GLOBALS['STR_COMING_PRODUCT_FLASH'];
	} elseif ($type == 'check') {
		$sql_cond_array[] = "p.on_check='1'";
		// Les chèques cadeaux ne sont pas associés à une catégorie.
		$join_categories = false;
		$titre = $GLOBALS['STR_CHEQUE_CADEAU'];
	} elseif ($type == 'associated_product') {
		$nb_par_page = '*';
		$infos = array();
		$commande_id_array = array();
		// On vérifie si la case remontée de produit a été cochée et qu'un nombre de produits à afficher a bien été saisi
		$product_fields = get_table_field_names('peel_produits', null, false, array("on_ref_produit", "nb_ref_produits"));
		if(!empty($product_fields)) {
			//Aut
			$sql = query("SELECT " . implode(', ', $product_fields) . "
				FROM peel_produits
				WHERE id = " . intval(vn($reference_id))." AND " . get_filter_site_cond('produits') . "");
			$infos = fetch_assoc($sql);
			if (!empty($infos) && $infos['on_ref_produit'] == 1 && $infos['nb_ref_produits'] > 0) {
				// Récupération des id des commandes dont le produit fait partie
				$sql = 'SELECT commande_id
					FROM peel_commandes_articles
					WHERE produit_id = "' . intval($reference_id) . '"  AND  ' . get_filter_site_cond('commandes_articles');
				$q = query($sql);
				while ($result = fetch_assoc($q)) {
					$commande_id_array[] = $result['commande_id'];
				}
			}
		}
		if (!empty($commande_id_array) && count($commande_id_array) > 0) {
			// Gestion de l'association automatique des références produits en fonction des anciennes commandes
			// Si la case a bien été cochée et qu'un nombre a été saisi et que le produit affiché a déjà été commandé
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
		$titre = $GLOBALS['STR_ASSOCIATED_PRODUCT'];
	} elseif ($type == 'save_cart') {
		$sql_inner .= " INNER JOIN peel_save_cart sc ON sc.produit_id = p.id ";
		$sql_cond_array[] = "sc.id_utilisateur = '" . intval($condition_value1) . "'";
		if (!empty($GLOBALS['site_parameters']['quick_add_product_from_search_page'])) {
			// On ne veut pas voir dans la liste des produits sauvegardés les produits qui ont été enregistrés pour la liste de produits "quick_search"
			$sql_cond_array[] = "sc.products_list_name = ''";
		}
	} elseif ($type == 'convert_gift_points') {
		$titre = $GLOBALS['STR_VOIR_LISTE_CADEAU'];
		$sql_cond_array[] = "p.on_gift=1 AND on_gift_points<='".intval($_SESSION['session_utilisateur']['points'])."'";
	} elseif ($type == 'show_draft') {
		$titre = $GLOBALS['STR_MODULE_CREATE_PRODUCT_IN_FRONT_OFFICE_SORTIE_SAVE_DRAFT'];
		$params_list['show_draft'] = true;
		$sql_cond_array[] = "p.etat=0 AND id_utilisateur = '".intval($_SESSION['session_utilisateur']['id_utilisateur'])."'";
	}
	if (empty($GLOBALS['site_parameters']['allow_command_product_ongift']) && $type != 'convert_gift_points' && check_if_module_active('gifts')) {
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
	if(StringMb::strpos(implode('', $sql_cond_array), 'c.') !== false) {
		// Sécurité si une condition SQL a été faite sur la table catégories
		$join_categories = true;
	}
	$join_categories_for_count = $join_categories;
	// Par défaut, products_list_sql_join_categories_default n'est pas défini et on force alors systématiquement une jointure avec la table de catégories.
	// Cela permet de faire une seule requête SQL et ensuite aucune lors de l'affichage, alors que sinon on doit aller chercher la catégorie pour chaque produit (fait automatiquement par $product_object->get_product_url() )
	// Néanmoins, si la base de données est très grosse, la jointure avec les catégories lors de la recherche n'est pas souhaitable, dans ce cas mettez la variable de configuration products_list_sql_join_categories_default à false, et on évitera la jointure quand elle n'est pas nécessaire à la recherche
	$join_categories = (vb($GLOBALS['site_parameters']['products_list_sql_join_categories_default'], $type!="check") || $join_categories);
	
	$sql = "SELECT p.*, p.nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." AS name " . ($join_categories?', IF(c.id IS NOT NULL, c.id, 0) AS categorie_id, IF(c.nom_' . $_SESSION['session_langue'] . ' IS NOT NULL, c.nom_' . $_SESSION['session_langue'] . ', 0) AS categorie':'');
	if (($type == 'save_cart')) {
		$sql .= ', sc.id as save_cart_id, sc.couleur_id as saved_couleur_id, sc.taille_id as saved_taille_id, sc.id_attribut as saved_attributs_list, sc.quantite as saved_quantity ';
	}
	$sql_main = '
		FROM peel_produits p '.$use_index_sql.'
		' ;
	$sql_join_categories = ($join_categories?(!empty($GLOBALS['site_parameters']['allow_products_without_category']) ? 'LEFT' : 'INNER') . ' JOIN peel_produits_categories pc ON pc.produit_id = p.id':'') . '
		';
	$sql_main2 = ($join_categories?(!empty($GLOBALS['site_parameters']['allow_products_without_category']) ? 'LEFT' : 'INNER') . ' JOIN peel_categories c ON pc.categorie_id = c.id AND c.etat=1 AND ' . get_filter_site_cond('categories', 'c'):'');
	$sql_main3 =  '
		' . $sql_inner . "
		WHERE " . (!empty($GLOBALS['allow_discontinued'])?"(p.etat='1' OR p.nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." LIKE 'Discontinued%')":($type != 'show_draft'?"p.etat='1'":'1')) . " AND " . get_filter_site_cond('produits', 'p') . ' AND p.nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']).' != ""';
	if (!empty($sql_cond_array)) {
		$sql_main3 .= ' AND (' . implode(') AND (', array_unique($sql_cond_array)) . ')';
	}
	$sql .= $sql_main . $sql_join_categories . $sql_main2 . $sql_main3;
	if ($type != 'save_cart') {
		if($join_categories && empty($GLOBALS['site_parameters']['allow_products_multiple_results_if_multiple_categories'])) {
			$sql .= ' GROUP BY p.id';
		}
		if($join_categories_for_count && empty($GLOBALS['site_parameters']['allow_products_multiple_results_if_multiple_categories'])) {
			$sql_manual_count = 'SELECT COUNT(DISTINCT p.id) AS rows_count ' . $sql_main . $sql_join_categories . $sql_main3;
		} elseif($join_categories_for_count) {
			$sql_manual_count = 'SELECT COUNT(*) AS rows_count ' . $sql_main. $sql_join_categories . $sql_main3;
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
	$GLOBALS['multipage_avoid_redirect_if_page_over_limit'] = !defined('IN_SEARCH');
	if ($type == 'special') {
		$Links = new Multipage($sql, 'home', $nb_par_page, 7, 0, $always_show_multipage_footer);
	} elseif ($type == 'associated_product') {
		$Links = new Multipage($sql, 'affiche_produits_reference', $nb_par_page, 7, 0, $always_show_multipage_footer);
	} else {
		$Links = new Multipage($sql, 'affiche_produits', $nb_par_page, 7, 0, $always_show_multipage_footer, $display_multipage_template_name, 1, null, false, $avoid_pagination_calculation);
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
	$Links->OrderDefault = vb($GLOBALS['site_parameters']['product_list_order_default'], 'position');
	$Links->SortDefault = vb($GLOBALS['site_parameters']['product_list_sort_default'], 'ASC');
	
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
	if (!empty($GLOBALS['site_parameters']['products_list_' . $type . '_forced_order'])) {
		// On montre les produits de la catégorie sélectionnée d'abord, avant ceux des catégories filles
		$Links->forced_order_by_string = 'p.' . word_real_escape_string($GLOBALS['site_parameters']['products_list_' . $type . '_forced_order']) . ' ' . word_real_escape_string(vb($GLOBALS['site_parameters']['products_list_' . $type . '_forced_sort'], 'ASC')) . '';
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
		$lower_user_agent = StringMb::strtolower($user_agent);
		if(!empty($user_agent)) {
			foreach(array('mediapartners-google', 'googlebot', 'google page speed', 'feedfetcher', 'slurp', 'bingbot', 'msnbot', 'voilabot', 'baiduspider', 'genieo', 'sindup', 'ahrefsbot', 'yandex', 'spider', 'robot', '/bot', 'crawler', 'netvibes') as $this_name) {
				$result = $result || StringMb::strpos($lower_user_agent, $this_name) !== false;
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
 * @param string $filename
 * @param string $filter_string
 * @param integer $items_count_max
 * @param integer $line_length_max
 * @return
 */
function get_xml_value($filename, $filter_string, $items_count_max = 8, $line_length_max = 50)
{
	$output = '';
	$forum_output = '';
	$valid_titre = array();

	$data = StringMb::file_get_contents_utf8($filename);
	$parser = xml_parser_create();
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, $data, $values, $tags);
	xml_parser_free($parser);
	// loop through the structures
	$filter_array = explode('|', $filter_string);
	if(!empty($tags['title'])) {
		foreach ($tags['title'] as $tag_key => $value_key) {
			if($tag_key == 0) {
				// Le premier title est celui de l'ensemble, mais pas du premier message => on saute
				continue;
			}
			// Il y a un décalage de 1 dans les clés pour les dates de publication et les titres
			// On retire ce qui est épinglé en haut d'un flux, pour avoir les nouveautés : si on voit que la date repart à la hausse, on efface ce qui a été généré
			if(!empty($last_time) && strtotime($values[$tags['pubDate'][$tag_key-1]]['value'])>$last_time) {
				unset($titles_array);
			}
			$titles_array[$tag_key] = $values[$value_key]['value'];
			if(!empty($tags['pubDate'])) {
				$last_time = strtotime($values[$tags['pubDate'][$tag_key-1]]['value']);
			}
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
			if ($skip_this) {
				// On saute les titres qui contiennent des mots à problèmes
				continue;
			}
			$tpl_links[] = array(
				'href' => $links_array[$key],
				'label' => $titles_array[$key]
			);
			$i++;
			if ($i >= $items_count_max) {
				break;
			}
		}
		$tpl->assign('links', $tpl_links);
		$tpl->assign('line_length_max', $line_length_max);
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
	$string = preg_replace('/[^a-z0-9]/', "-", utf8_decode(StringMb::convert_accents(StringMb::strtolower($string))));
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
	$extension = StringMb::strtolower(pathinfo($file_infos['name'], PATHINFO_EXTENSION));
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
			// Le fichier a été chargé en cache : on va le déplacer et garder le nom
			$file_infos['name'] = basename($_REQUEST[$field_name]);
			$format_filename_base_disabled = true;
		} elseif(strpos($_REQUEST[$field_name], ';base64,') !== false) {
			// Le fichier est encodé en base64
			$file_infos['name'] = substr(MDP(), 0, 12).'.png';
			$file_infos['base64'] = true;
		} else {
			// Le fichier est à sa place, on renvoie simplement le nom déjà existant
			$file_existing = $_REQUEST[$field_name];
			$GLOBALS['uploaded_file_already_existing'][$file_existing] = $file_existing;
			return $file_existing;
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
		// Dans les cas ci-dessous, le fichier va être manipulé pour être mis à son emplacement officiel
		// Extension du fichier téléchargé
		$extension = StringMb::strtolower(pathinfo($file_infos['name'], PATHINFO_EXTENSION));
		if (empty($new_file_name_without_extension)) {
			if(!empty($format_filename_base_disabled)) {
				$the_new_file_name = $file_infos['name'];
			} else {
				// Si aucun nom forcé, on en crée un
				$the_new_file_name = format_filename_base(vb($file_infos['name']), $rename_file). '.' . $extension;
			}
			$new_file_name_without_extension = StringMb::strtolower(pathinfo($the_new_file_name, PATHINFO_FILENAME));
		} else {
			$the_new_file_name = $new_file_name_without_extension . '.' . $extension;
		}
		if(StringMb::strlen($the_new_file_name)>=3) {
			if (!empty($file_infos['base64'])) {
				// Décodage d'une image envoyée en base64
				$fp = fopen($path . $the_new_file_name, "wb"); 
				$data = explode(',', $_REQUEST[$field_name]);
				fwrite($fp, base64_decode($data[1])); 
				fclose($fp); 
				if(!empty($GLOBALS['site_parameters']['chmod_new_files'])) {
					@chmod ($path . $the_new_file_name, $GLOBALS['site_parameters']['chmod_new_files']);
				}
				return $the_new_file_name; 
			} elseif(!isset($file_infos['tmp_name'])) {
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
		} else {
			unset($GLOBALS['uploaded_file_new'][$the_new_file_name]);
			$tpl = $GLOBALS['tplEngine']->createTemplate('global_error.tpl');
			$tpl->assign('message', 'Bad path: ' .$path);
			$error = $tpl->fetch();
		}
	}
	if (!empty($error)) {
		$GLOBALS['notification_output_array'][] = $error;
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
		$new_file_name_without_extension = strftime("%d%m%y_%H%M%S") . "_PEEL_" . MDP(4);
	} else {
		$extension = StringMb::strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
		$modified_old_name_without_extension = preg_replace('/([^.a-z0-9]+)/i', '-', StringMb::convert_accents(str_replace(array('%2520', '%20', ';', ',', ' ', '^', '$', '#', '<', '>', '[', ']', '{', '}', '(', ')', "'", '"'), array('-', '-', '-', '-', '-', '-', '-', '', '', '', '', '', '', '', '', '', '', ''), basename(StringMb::strtolower($original_name), '.' . $extension))));
		$new_file_name_without_extension = StringMb::strtolower(StringMb::substr(str_replace(array('-----', '----', '---', '--'), array('-', '-', '-', '-'), $modified_old_name_without_extension), 0, 33) . '-' . MDP(6));
	}
	return $new_file_name_without_extension;
}

/**
 * delete_uploaded_file_and_thumbs()
 *
 * @param string $filename
 * @param boolean $return_message
 * @return
 */
function delete_uploaded_file_and_thumbs($filename, $return_message = false)
{
	if (a_priv('demo') || empty($filename)) {
		return false;
	}
	if(!empty($filename)) {
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
	} else {
		return null;
	}
	$result = @unlink($GLOBALS['uploaddir'] . '/' . $filename);
	if($return_message) {
		if ($result) {
			$output = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $filename . ' ' . $GLOBALS["STR_HAS_BEEN_DELETED"]))->fetch();
		} else {
			$output = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $filename . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $GLOBALS["STR_FOPEN_FAILED"]))->fetch();
		}
		return $output;
	} else {
		return $result;
	}
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
		if($force_download) {
			// force download dialog
			// Un hébergement peut éventuellement faire une erreur 500 si exécution des 3 lignes suivantes, mais problème jamais reproduit ailleurs
			if(!empty($GLOBALS['site_parameters']['donwload_headers_force_download_skip'])) {
				header('Content-Type: application/force-download');
				header('Content-Type: application/octet-stream', false);
				header('Content-Type: application/download', false);
			}
			$content_disposition = 'attachment';
		} else {
			$content_disposition = 'inline';
		}
		header("Content-Type: " . $type . "", false);
		if(strpos(vb($_SERVER['HTTP_USER_AGENT']), "MSIE") !== false) {
			header("Content-disposition: " . $content_disposition . "; filename=\"" . StringMb::rawurlencode(convert_encoding($file_name_given, 'ISO-8859-1')) . "\"");
		} else {
			// attwithfn2231ws3 marche avec tous les navigateurs sauf IE 
			header("Content-disposition: " . $content_disposition . "; filename*=UTF-8''" . StringMb::rawurlencode($file_name_given));
		}
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . intval($content_length));
		if (ob_get_length()) {
			// checks if there's a non empty string in the buffer => Avoid Notice: ob_clean(): failed to delete buffer.
			ob_clean();
		}
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
 * get_url_from_uploaded_filename()
 *
 * @param string $filename May contain a path or not
 * @return
 */
function get_url_from_uploaded_filename($filename)
{
	if(strpos($filename, '//') !== false) {
		$this_url = $filename;
	} elseif(strpos($filename, '/'.$GLOBALS['site_parameters']['cache_folder']) === 0) {
		$this_url = $GLOBALS['wwwroot'] . $filename;
	} elseif(!empty($filename)) {
		$this_url = $GLOBALS['repertoire_upload'] . '/' . StringMb::rawurlencode($filename);
	} else {
		$this_url = null;
	}
	return $this_url;
}

/**
 * get_file_type()
 *
 * @param string $filename May contain a path or not
 * @return
 */
function get_file_type($filename)
{
	// Spécifiquement ici, il faut utiliser substr et non pas StringMb::substr à cause de strrpos qui n'est pas multibyte
	$ext = StringMb::strtolower(substr($filename, strrpos($filename, ".") + 1));
	$tmp = explode('?', $ext);
	$ext = $tmp[0];
	if(in_array($ext, array('gif', 'jpg', 'jpeg', 'png'))) {
		$ext = 'image';
	} elseif((empty($ext) || StringMb::strlen($ext)>4) && strpos($filename, '//') !== false && strpos($filename, $GLOBALS['wwwroot']) === false) {
		// Image accessible en HTTP via une URL qui ne contient pas d'extension ou alors une extension suivi de GET (et donc par exemple *.php?image=52242)
		$a = @getimagesize($filename);
		if (!empty($a)) {
			$image_type = $a[2];
			if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP))) {
				$ext = 'image';
			}
		}
	}
	return $ext;
}

/**
 * Renvoie une image correspondant au type de fichier
 *
 * @param boolean $filename
 * @param integer $width
 * @param integer $height
 * @return
 */
function get_document_image_html($filename, $width = 100, $height = 100)
{
	if(!empty($filename)) {
		return '<a href="' . get_url_from_uploaded_filename($filename) . '" onclick="return(window.open(this.href)?false:true);"><img src="' . thumbs($filename, $width, $height, 'fit', null, null, true, true) . '" alt="" style="max-width: ' . $width . 'px; max-height: ' . $height . 'px" /></a>';
	}
}

/**
 * Remplit un tableau d'informations pour le template d'upload HTML
 *
 * @param string $file Nom de fichier, avec ou sans chemin
 * @return
 */
function get_uploaded_file_infos($field_name, $file, $delete_url, $logo_width = 100, $logo_height = 100, $read_only = false, $init_javascript = false)
{
	if(empty($field_name)) {
		return array();
	}
	$file_type = get_file_type($file);
	$div_id = str_replace(array('[', ']'), '', $field_name) . '_' . substr(md5(rand()),0,6);
	$class = '';
	if(!empty($_SESSION['apply_crop_after_upload'][$field_name])) {
		$class .= ' crop';
	}
	$result = array('name' => basename($file),
		'div_id' => $div_id,
		'form_name' => $field_name,
		'form_value' => $file,
		'drop_src' => $GLOBALS['administrer_url'] . '/images/b_drop.png',
		'drop_href' => str_replace(array('[DIV_ID]'),array($div_id), $delete_url),
		'url' => (!empty($file)?get_url_from_uploaded_filename($file):null),
		'type' => $file_type,
		'crop' => !empty($_SESSION['apply_crop_after_upload'][$field_name]),
		'class' => $class,
		'STR_DELETE_THIS_FILE' => $GLOBALS['STR_DELETE_THIS_FILE'],
		'read_only' => $read_only,
		'download_picture' => vb($GLOBALS['site_parameters']['fineuploader_download_picture'])
		);
	if($file_type != 'image') {
		$result['file_logo_src'] = thumbs($file, $logo_width, $logo_height, 'fit', null, null, true, true);
	} else {
		if(!empty($_SESSION['apply_crop_after_upload'][$field_name])) {
			$GLOBALS['js_ready_content_array'][] = '
		$(function() {
			$("#'.$div_id.' > img").cropper({
				autoCropArea: 0.35,
				built: function () {
						croppedimage=$(this).cropper("getCroppedCanvas").toDataURL();
						$(".img_cropped").attr("src", croppedimage);
						$(".input_cropped").val(croppedimage);
					},
				cropend: function () {
						croppedimage=$(this).cropper("getCroppedCanvas").toDataURL();
						$(".img_cropped").attr("src", croppedimage);
						$(".input_cropped").val(croppedimage);
					}
			});
			
		});
';
		}
	}
	$GLOBALS['uploaded_file_div_id_last'] = $div_id;
	if($init_javascript) {
		$GLOBALS['js_ready_content_array'][] = 'init_fineuploader($("#'.$div_id.'"));';
	}
	return $result;
}

/**
 * Récupère le nom de domaine du site sans http://
 *
 * @param boolean $return_only_domains
 * @param string $domain
 * @param boolean $strip_subdomain
 * @return
 */
function get_site_domain($return_only_domains = false, $domain = null, $strip_subdomain = true)
{
	if (!empty($domain)) {
		$domain = str_replace(array('http://', 'https://', '://', '//'), '', $domain);
		$temp = explode('/', $domain, 2);
		$domain = $temp[0];
	} elseif(strpos($GLOBALS['wwwroot'], '://127.0.0.1')!==false) {
		return $_SERVER["HTTP_HOST"];
	} else {
		$domain = $_SERVER["HTTP_HOST"];
	}
	$temp = explode('.', $domain);
	if(count($temp)>1 && (count($temp)!=4 || !is_numeric(str_replace('.','',$domain)))) {
		// Ce n'est pas une IP, ni localhost ou un nom de machine => c'est un domaine avec potentiellement un (sous-)sous-domaine
		if(in_array($temp[count($temp)-2], array('com', 'org', 'co'))) {
			// Domaine en .co.uk, .com.sb, .org.uk, etc.
			$temp[count($temp)-2] = $temp[count($temp)-2] . '.' . $temp[count($temp)-1];
			unset($temp[count($temp)-1]);
		}
		if ($strip_subdomain || !isset($temp[count($temp)-3])) {
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
		if (StringMb::substr($term, 0, 1) == '-') {
			// on enleve le '-' qu'on convertir en NOT
			$term = StringMb::substr($term, 1);
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
				SET newsletter = "0", commercial = "0", newsletter_validation_date="0000-00-00", commercial_validation_date="0000-00-00"
				WHERE id_utilisateur=' . intval($data['id_utilisateur']) . ' AND ' . get_filter_site_cond('utilisateurs') . '');
			$tpl->assign('msg', $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_DESINSCRIPTION_NEWSLETTER_OK']))->fetch());
		} else {
			$tpl->assign('msg', $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_EMAIL_ABSENT']))->fetch());
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
	// Evite de devoir lancer un cron pour optimisations et nettoyages divers
	// On fait des tests séparés pour ne pas tout lancer d'un coup, mais répartir au mieux
	$GLOBALS['contentMail'] = '';
	call_module_hook('close_page_generation', array());

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
	if(!check_if_module_active('crons')) {
		if (mt_rand(1, 10000) == 5000) {
			optimize_Tables();
		}
		if (mt_rand(1, 10000) == 5000) {
			clean_utilisateur_connexions();
		}
		if (mt_rand(1, 10000) == 5000) {
			clean_admins_actions();
		}
		if (mt_rand(1, 10000) == 5000) {
			clean_Cache();
		}
	}
	if ($html_page && defined('PEEL_DEBUG') && PEEL_DEBUG) {
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
		if($handle !== false) {
			$test = fgets($handle);
			fclose($handle);
		}
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
	if(!empty($GLOBALS['site_parameters']['optimize_tables_skip'])) {
		return false;
	}
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
		$GLOBALS['contentMail'] .= 'Suppression des fichiers de plus de ' . $days_max . ' jours dans le dossier ' . $GLOBALS['dirroot'] . '/' . $GLOBALS['site_parameters']['cache_folder'] . '/ : ';
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
	if(StringMb::substr($dir, -1) == '/') {
		$dir = StringMb::substr($dir, 0, StringMb::strlen($dir) - 1);
	}
	if ($dir != $GLOBALS['dirroot'] && is_dir($dir) && ($dossier = opendir($dir))) {
		while (false !== ($file = readdir($dossier))) {
			if(!empty($file)) {
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
		$o .= '<option value="' . StringMb::str_form_value($k) . '" ' . $s . '>' . $v . '</option>' . "\n";
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
 * @param integer $compter_char_max_if_enabled
 * @param string $placeholder
 * @return string HTML généré
 */
function getTextEditor($instance_name, $width, $height, $default_text, $default_path = null, $type_html_editor = 0, $compter_char_max_if_enabled = 255, $placeholder = '')
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
	// Pour les éditeurs WISIWYG, définition des CSS qui sont ajoutés pour l'édition si c'est possible techniquement
	$css_files = array();
	if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
		$css_files[] = get_url('/lib/css/bootstrap.css');
	}
	if(!empty($GLOBALS['site_parameters']['css'])) {
		foreach (get_array_from_string($GLOBALS['site_parameters']['css']) as $this_css_filename) {
			if(file_exists($GLOBALS['repertoire_modele'] . '/css/' . trim($this_css_filename))) {
				$css_files[] = $GLOBALS['repertoire_css'] . '/' . trim($this_css_filename); // .'?'.time()
			}
		}
	}
	if ($this_html_editor == '1') {
		// Editeur nicEditor
		if(empty($GLOBALS['html_editor_loaded'])) {
			$GLOBALS['html_editor_loaded'] = true;
			$GLOBALS['js_files_pageonly'][] = get_url('/lib/nicEditor/nicEdit.js');
		}
		$GLOBALS['js_ready_content_array'][] = '
bkLib.onDomLoaded(function() {
	new nicEditor({iconsPath : \'' . $GLOBALS['wwwroot'] . '/lib/nicEditor/nicEditorIcons.gif\',fullPanel : true, maxHeight:' . $height . ', externalCSS: \''.current($css_files).'\'}).panelInstance(\'' . $instance_name . '\');
});
';
		$output .= '
<div style="width:' . $width_css . '; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
	<div style="min-width:200px">
		<textarea name="' . $instance_name . '" id="' . $instance_name . '" style="width:' . $width_css . '; height:' . $height . 'px" rows="' . ($height / 12) . '" cols="' . $cols . '">' . StringMb::htmlentities($default_text) . '</textarea>
	</div>
</div>
';
	} elseif ($this_html_editor == '0') {
		$default_text = StringMb::nl2br_if_needed($default_text);
		// Editeur FCKeditor
		include_once($GLOBALS['dirroot'] . "/lib/FCKeditor/fckeditor.php");
		$oFCKeditor = new FCKeditor($instance_name);
		if(empty($default_path)) {
			$default_path = get_url('/');
		}
		$oFCKeditor->BasePath = $default_path . 'lib/FCKeditor/';
		$oFCKeditor->Value = StringMb::htmlspecialchars_decode($default_text, ENT_QUOTES);
		$oFCKeditor->Height = $height;
		$oFCKeditor->Width = $width;
		$oFCKeditor->Config = array('EditorAreaCSS' => implode(',', $css_files));
		$output .= '
<div style="width:' . $width_css . '; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
	<div style="min-width:550px">
		'.$oFCKeditor->CreateHtml().'
	</div>
</div>
';
	} elseif ($this_html_editor == '3') {
		$default_text = StringMb::nl2br_if_needed($default_text);
		// Editeur CKeditor
		// La hauteur demandée sera celle de la zone éditable - donc la hauteur totale sera bien supérieure
		if(empty($GLOBALS['html_editor_loaded'])) {
			$GLOBALS['html_editor_loaded'] = true;
			$GLOBALS['js_files_pageonly'][] = get_url('/lib/ckeditor/ckeditor.js');
			$GLOBALS['js_ready_content_array'][] = "
				CKEDITOR.plugins.add( 'imageuploader', {
					init: function( editor ) {
						editor.config.filebrowserBrowseUrl='" . get_url('/lib/ckeditor/plugins/imageuploader/imgbrowser.php') . "';
						editor.config.filebrowserUploadUrl='" . get_url('/upload/') . "';
					}
				});";
		}

		$GLOBALS['js_ready_content_array'][] = '
			CKEDITOR.replace( "' . $instance_name . '" );';
		// Configuration entities pour éviter la conversion des accents en entités HTML
		// Format pour CSS : config.contentsCss = [ '/css/mysitestyles.css', '/css/anotherfile.css' ];
		$ckeditor_config_js = '
			config.entities=false;
			config.entities_latin=false;
			config.contentsCss=["'.implode('", "', $css_files).'"];
		';
		
		if (empty($GLOBALS['site_parameters']['ckeditor_upload_image_enable'])) {
			// Le module imageuploader permet l'upload d'image depuis l'éditeur HTML. Il ajoute un onglet "Téléverser" dans la fenêtre de gestion des images. 
			// Mais ce module ne fonctionne pas, lorsque l'on souhaite uplaoder une image, un message "Forbidden You don't have permission to access /upload/ on this server."
			// Donc la gestion des images est désactivée par défaut pour cet éditeur.
			$ckeditor_config_js .= "
			config.removeButtons = 'Image';";
		}
		$GLOBALS['js_ready_content_array'][] = "
		CKEDITOR.editorConfig = function( config ) {
			" . $ckeditor_config_js . "
		};";
		$output .= '
		<textarea class="ckeditor" name="' . $instance_name . '" id="' . $instance_name . '" style="width:' . $width_css . '; height:' . $height . 'px" rows="' . ($height / 12) . '" cols="' . $cols . '">' . StringMb::htmlentities($default_text) . '</textarea>	

';
	} elseif ($this_html_editor == '4') {
		$default_text = StringMb::nl2br_if_needed($default_text);
		// Editeur TinyMCE
		if(empty($GLOBALS['html_editor_loaded'])) {
			$GLOBALS['html_editor_loaded'] = true;
			$GLOBALS['js_files_pageonly'][] = get_url('/lib/tiny_mce/jquery.tinymce.js');
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
<div style="width:' . (is_numeric($width)?$width.'px':$width_css) . '; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
	<div style="min-width:600px">
		<textarea class="tinymce" name="' . $instance_name . '" id="' . $instance_name . '" style="width:' . $width_css . '; height:' . $height . 'px" rows="' . ($height / 12) . '" cols="' . $cols . '">' . StringMb::htmlentities($default_text) . '</textarea>
	</div>
</div>
';
	} elseif($this_html_editor == '5') {
		// Champ textarea de base + Compteur de caractères
		$output .= '
			<textarea class="form-control" placeholder="'. $placeholder.'" name="' . $instance_name . '" cols="' . $cols . '" rows="' . ($height / 12) . '" onfocus="Compter(this,'.$compter_char_max_if_enabled.',compteur, true)" onkeypress="Compter(this,'.$compter_char_max_if_enabled.',compteur, true)" onkeyup="Compter(this,'.$compter_char_max_if_enabled.',compteur, true)" onblur="Compter(this,'.$compter_char_max_if_enabled.',compteur, true)">' . StringMb::htmlentities($default_text) . '</textarea><br />
			<div class="compteur_contener"><span style="margin:5px;">'.$GLOBALS['STR_REMINDING_CHAR'].'</span><input class="form-control compteur" type="number" name="compteur" size="4" onfocus="blur()" value="0" /></div>
';
	} else {
		// Champ textarea de base
		$output .= '
			<textarea name="' . $instance_name . '" id="' . $instance_name . '" style="width:' . $width_css . '; height:' . $height . 'px" rows="' . ($height / 12) . '" cols="' . $cols . '">' . StringMb::htmlentities($default_text) . '</textarea>
';
	}
	return $output;
}

/**
 * Récupère une valeur dans la table peel_configuration dans le cas où on veut une information potentiellement indépendante du site sur lequel on est
 *
 * @param string $technical_code
 * @param integer $site_id
 * @param string $lang
 * @return
 */
function get_configuration_variable($technical_code, $site_id, $lang)
{
	// On récupère la ligne si elle existe pour ce site_id et pas un autre
	$sql = "SELECT string
		FROM peel_configuration
		WHERE technical_code='" . real_escape_string($technical_code) . "' AND (lang='" . real_escape_string($lang) . "' OR lang='') AND " . get_filter_site_cond('configuration', null, false, vb($site_id));
	$qid = query($sql); 
	if ($select = fetch_assoc($qid)) {
		// Elément déjà existant, qu'on met à jour
		return $select['string'];
	} else {
		return null;
	}
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
		// La création d'un nouveau paramètre n'est pas souhaitée à chaque fois, afin d'éviter des doublons.
		if(in_array($frm['string'], array('true', 'false'))) {
			$frm['type'] = 'boolean';
		} elseif(!isset($frm['type'])) {
			$frm['type'] = 'string';
		}
		if(is_array($frm['string'])) {
			$frm['string'] = get_string_from_array($frm['string']);
			$frm['type'] = 'array';
		}
		// On cherche d'abord si cette configuration demandée est la même qu'une configuration publique
		$sql_items[] = "etat = '" . intval($frm['etat']) . "'";
		$sql_items[] = "technical_code = '" . nohtml_real_escape_string($frm['technical_code']) . "'";
		$sql_items[] = "string = '" . real_escape_string($frm['string']) . "'";
		$sql_items[] = "lang = '" . nohtml_real_escape_string(vb($frm['lang'])) . "'";
		$sql = "SELECT id
			FROM peel_configuration
			WHERE " . implode(' AND ', $sql_items) . " AND " . get_filter_site_cond('configuration', null, false, 0, true);
		$qid = query($sql); 
		if (!fetch_assoc($qid)) {
			$sql_items[] = "`type` = '" . nohtml_real_escape_string($frm['type']) . "'";
			$sql_items[] = "`last_update` = '" . date('Y-m-d H:i:s', time()) . "'";
			$sql_items[] = "`origin` = '" . nohtml_real_escape_string(vb($frm['origin'])) . "'";
			$sql_items[] = "`explain` = '" . nohtml_real_escape_string(vb($frm['explain'])) . "'";
			// MAJ pour la page en cours de génération
			if($frm['type'] == 'array') {
				$GLOBALS['site_parameters'][$frm['technical_code']] = get_array_from_string($frm['string']);
			} else {
				$GLOBALS['site_parameters'][$frm['technical_code']] = $frm['string'];
			}
			// MAJ en BDD
			$sql = "INSERT INTO peel_configuration
				SET " . implode(', ', $sql_items) . ", `site_id` = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'";
			return query($sql);
		}
	} else {
		return null;
	}
}

/**
 * update_configuration_variable()
 *
 * @param integer $id_or_technical_code
 * @param array $frm Array with all fields data
 * @param boolean $delete
 * @return
 */
function update_configuration_variable($id_or_technical_code, $frm, $delete = false)
{
	if(isset($frm['string']) && is_array($frm['string'])) {
		$frm['string'] = get_string_from_array($frm['string']);
	}
	if($delete) {
		// MAJ pour la page en cours de génération
		unset($GLOBALS['site_parameters'][$frm['technical_code']]);
		// Modification en BDD
		$sql = "DELETE FROM peel_configuration
			WHERE ";
	} else {
		// MAJ pour la page en cours de génération
		if(isset($frm['string'])) {
			if(vb($frm['type']) == 'array') {
				$GLOBALS['site_parameters'][$frm['technical_code']] = get_array_from_string($frm['string']);
			} else {
				$GLOBALS['site_parameters'][$frm['technical_code']] = $frm['string'];
			}
		}
		// Modification en BDD
		$sql = "UPDATE peel_configuration
			SET etat = '" . intval($frm['etat']) . "'
				, technical_code = '" . nohtml_real_escape_string($frm['technical_code']) . "'
				".(isset($frm['type'])?", type = '" . nohtml_real_escape_string($frm['type']) . "'":"")."
				".(isset($frm['string'])?", string = '" . real_escape_string($frm['string']) . "'":"")."
				, last_update = '" . date('Y-m-d H:i:s', time()) . "'
				".(isset($frm['origin'])?", origin = '" . nohtml_real_escape_string($frm['origin']) . "'":"")."
				".(isset($frm['lang'])?", lang = '" . nohtml_real_escape_string($frm['lang']) . "'":"")."
				".(isset($frm['explain'])?", `explain` = '" . nohtml_real_escape_string($frm['explain']) . "'":"")."
				".(isset($frm['site_id'])?", `site_id` = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'":"")."
			WHERE ";
	}
	if(is_numeric($id_or_technical_code)) {
		$sql .= "id = '" . intval($id_or_technical_code) . "'";
	} else {
		$sql .= "technical_code = '" . real_escape_string($id_or_technical_code) . "'";
	}
	$sql .= " AND " . get_filter_site_cond('configuration', null, true, vb($frm['site_id'], null));
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
		$excluded_files = vb($GLOBALS['site_parameters']['minify_js_exclude_array'], array());
		$excluded_files[] = 'prototype.js';
		$excluded_files[] = 'controls.js';
		$excluded_files[] = 'effects.js';
		$included_files = array('datepicker');
	} elseif($files_type == 'css') {
		if(!empty($GLOBALS['site_parameters']['minify_css_exclude_array'])) {
			$excluded_files = $GLOBALS['site_parameters']['minify_css_exclude_array'];
		}
	}
	$original_files_array = $files_array;
	foreach($files_array as $this_key => $this_file) {
		if(StringMb::substr($this_file, 0, 2) == '//') {
			// Gestion des chemins de fichiers http/https automatiques
			$this_file = 'http:' . $this_file;
		} elseif(StringMb::strpos($this_file, '//') === false && StringMb::substr($this_file, 0, 1) == '/') {
			// Chemin absolu
			$this_file = $this_wwwroot . $this_file;
		} elseif(StringMb::strpos($this_file, '//') === false) {
			// Chemin relatif car le nom du fichier ne commence pas par / => par défaut on considère qu'il est dans le répertoire CSS dans modeles/
			$this_file = $GLOBALS['repertoire_css'] . '/' . $this_file;
		} 
		if($GLOBALS['wwwroot_in_admin'] != $GLOBALS['wwwroot']) {
			// Nécessaire si appel en https de l'administration d'un site configuré en http
			$this_file = str_replace($GLOBALS['wwwroot_in_admin'], $GLOBALS['wwwroot'], $this_file);
		}
		if($GLOBALS['wwwroot'] != $this_wwwroot) {
			$this_file = str_replace($GLOBALS['wwwroot'], $this_wwwroot, $this_file);
		}
		$files_array[$this_key] = $this_file = StringMb::html_entity_decode($this_file);
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
	$file_name = $files_type . '_minified_' . substr($cache_id, 0, 16).'.'.$files_type;
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
					if(StringMb::strlen($GLOBALS['apparent_folder'])>1 && StringMb::strpos($this_wwwroot, StringMb::substr($GLOBALS['apparent_folder'], 0, StringMb::strlen($GLOBALS['apparent_folder']) - 1)) !== false) {
						$this_http_main_path = StringMb::substr($this_wwwroot, 0, StringMb::strlen($this_wwwroot) - StringMb::strlen($GLOBALS['apparent_folder']) + 1);
						if(empty($GLOBALS['site_parameters']['avoid_lang_folders_in_minified_css']) && !empty($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']]) && strpos($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']], '//') === false && strpos($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']], '.') === false) {
							// On n'a pas choisi d'activer l'option pour avoir des liens vers les fichiers sans les dossiers de langue
							// Donc il faut corriger le chemin qu'on vient de retravailler
							$this_http_main_path = StringMb::substr($this_http_main_path, 0, StringMb::strlen($this_http_main_path) - StringMb::strlen($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']]));
						}
					} else {
						$this_http_main_path = $this_wwwroot;
					}
					$options = array('currentDir' => str_replace($this_http_main_path, $GLOBALS['dirroot'], dirname($this_file)), 'docRoot' => $docroot, 'symlinks' => $symlinks);
					$css_content = StringMb::file_get_contents_utf8(str_replace($this_wwwroot, $GLOBALS['dirroot'], $this_file));
					if(!empty($css_content) && strlen(trim($css_content))>5) {
						if(StringMb::strpos(str_replace('@import url(http://fonts.googleapis.com', '', $css_content), '@import')!==false) {
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
					$js_content = StringMb::file_get_contents_utf8(str_replace($this_wwwroot, $GLOBALS['dirroot'], $this_file));
					if(!empty($js_content) && strlen(trim($js_content))>5) {
						// Le fichier n'est pas vide, on le prend
						if(strlen($js_content)/max(1,substr_count($js_content, "\n"))>50 || version_compare(PHP_VERSION, '5.3.0', '<')) {
							// NB : Si le fichier semble déjà être minified, on ne cherche pas à compresser davantage => gain de temps et limite les risques d'altération du fichier
						}else {
							$js_minified = Minifier::minify($js_content);
							if(!empty($js_minified) && strlen(trim($js_minified))>5) {
								// Protection au cas où Minifier::minify renvoie un contenu vide (peut arriver exceptionnellement)
								$js_content = $js_minified;
							}
						}
						$output .= "\n\n\n" . $js_content;
					}
				} else {
					$output .= "\n\n\n".StringMb::file_get_contents_utf8(str_replace($this_wwwroot, $GLOBALS['dirroot'], $this_file));
				}
			}
			if(!empty($output)) {
				$output = trim($output);
				$fp = StringMb::fopen_utf8($file_path, 'wb');
				@flock($fp, LOCK_EX);
				// On utilise strlen et non pas StringMb::strlen car on veut le nombre d'octets et non pas de caractères
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
			$product_table_field_names = get_table_field_names('peel_produits');
			$sql_where = "(p.id='" . nohtml_real_escape_string($search) . "'";
			if (in_array('ean_code', $product_table_field_names)) {
				$sql_where .= " OR ean_code = '" . nohtml_real_escape_string($search) . "'";
			}
			$sql_where .= ") AND " . get_filter_site_cond('produits', 'p') . "" . $sql_additional_cond;
			$queries_sql_array[] = "SELECT p.*, p." . word_real_escape_string($name_field) . " AS nom
				FROM peel_produits p
				WHERE " . $sql_where . "
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
				WHERE (ean_code = '" . nohtml_real_escape_string($search) . "' OR p." . word_real_escape_string($name_field) . " LIKE '%" . nohtml_real_escape_string($search) . "%' OR (p.reference LIKE '%" . nohtml_real_escape_string($search) . "%' AND p." . word_real_escape_string($name_field) . "!=''))" . $sql_additional_cond . " AND " . get_filter_site_cond('produits', 'p') . "
				ORDER BY p." . word_real_escape_string($name_field) . " ASC";
		}
		foreach($queries_sql_array as $this_query_sql) {
			if(StringMb::strpos($this_query_sql, 'LIMIT') === false) {
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
	} elseif($mode=='categories') {
		$sql = 'SELECT id, nom_' . $_SESSION['session_langue'] . ' AS name
			FROM peel_categories
			WHERE nom_' . $_SESSION['session_langue'] . ' LIKE "%'.nohtml_real_escape_string($search).'%" AND ' . get_filter_site_cond('categories', null, defined('IN_PEEL_ADMIN')) . '
			LIMIT '.$maxRows;
		$query = query($sql);
		while ($result = fetch_object($query)) {
			$result->name = get_category_name($result->id, 10);
			$queries_results_array[$result->id] = $result;
		}
	} elseif($mode=='offers') {
		if(!empty($GLOBALS['site_parameters']['user_offers_table_enable'])) {
			$sql = 'SELECT *
				FROM peel_offres
				WHERE ' . get_filter_site_cond('offres') . ' AND num_offre LIKE "'.nohtml_real_escape_string($search).'%"
				GROUP BY num_offre
				LIMIT '.$maxRows;
			$query = query($sql);
			while ($result = fetch_object($query)) {
				$queries_results_array[$result->id] = $result;
			}
		}
	} elseif($mode=='offer_add_user') {
		$sql = 'SELECT *
			FROM peel_utilisateurs
			WHERE (prenom LIKE "%'.nohtml_real_escape_string($search).'%" OR nom_famille LIKE "%'.nohtml_real_escape_string($search).'%") AND ' . get_filter_site_cond('utilisateurs') . '
			LIMIT '.intval($maxRows);
		$query = query($sql);
		while ($result = fetch_object($query)) {
			$queries_results_array[$result->id_utilisateur] = $result;
		}
	}
	$hook_result = call_module_hook('quick_search_results', array('search' => $search, 'maxRows' => $maxRows, 'active_only' => $active_only, 'search_category' => $search_category, 'mode' => $mode), 'array');
	$queries_results_array = array_merge_recursive_distinct($queries_results_array, $hook_result);
	
	return $queries_results_array;
}

/**
 * Retourne la condition SQL permettant de filtrer les données pour une table
 *
 * @param string $table_technical_code	Nom de la table sans prefix.
 * @param string $table_alias Alias de la table
 * @param boolean $use_strict_rights_if_in_admin Ne renvoyer que les éléments qu'on peut éditer avec les droits d'administrateur en cours
 * @param integer $specific_site_id	Id du site concerné
 * @param boolean $exclude_public_items	Exclue les résultats concernant la configuration générique
 * @param boolean $admin_force_multisite_if_allowed
 * @return
 */
function get_filter_site_cond($table_technical_code, $table_alias = null, $use_strict_rights_if_in_admin = false, $specific_site_id = null, $exclude_public_items = false, $admin_force_multisite_if_allowed = false) {
	if($table_technical_code == '') {
		// Pour certaine table, le champ qui contient l'id du site n'est pas site_id, mais id_ecom
		$field = 'id_ecom';
	} else {
		// Cas général
		$field = 'site_id';
	}
	if(is_array($specific_site_id)) {
		$specific_site_id = current($specific_site_id);
	}
	if(in_array($table_technical_code, array('continents', 'partenaires', 'partenaires_views', 'partenaires_clicks', 'adresses'))) {
		// Cette table n'est pas multisite.
		return 1;
	}
	if(empty($specific_site_id) && (!empty($GLOBALS['site_parameters']['multisite_disable']) || !empty($GLOBALS['site_parameters']['multisite_disable_' . $table_technical_code]))) {
		// Désactivation du multisite pour accélérer les requêtes sur un site isolé. On vérifie si specific_site_id est bien vide, dans le cas contraire si specific_site_id est précisé, c'est que l'on veut récupérer les informations d'un site en particulier, et cela indépendamment de la configuration générale.
		return 1;
	} elseif(defined('IN_IPN') && !empty($GLOBALS['site_parameters']['multisite_disable_in_ipn']) && $table_technical_code!='configuration') {
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
		if(!empty($GLOBALS['multisite_disable_if_no_specific_site_id'])) {
			// Pour faire des requêtes multisites de manière générale sur une page, mais sans casser les requêtes spécifiant $specific_site_id pour par exemple générer des URL vers le bon site
			return 1;
		} elseif(empty($GLOBALS['site_id']) && defined('IN_CRON') && $use_strict_rights_if_in_admin === false && $exclude_public_items === false) {
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
				// Lorsque l'ensemble des sites est concerné par la requête et pas seulement un site, ou que la configuration générale l'impose, aucun filtre sur site_id ne doit être ajouté.
				return 1;
			} elseif(isset($_SESSION['session_admin_multisite']) && empty($GLOBALS['admin_multisite_disable']) && $specific_site_id === null) {
				if(intval($_SESSION['session_admin_multisite'])==0) {
					// on administre tous les sites en même temps
					return 1;
				} else {
					// on administre un autre site, d'après la préférence de l'administrateur mise en session
					$site_id = intval($_SESSION['session_admin_multisite']);
					if($use_strict_rights_if_in_admin) {
						$exclude_public_items = true;
					}
				}
			}
		} elseif($specific_site_id === null) {
			// L'administrateur ne peut administrer que son propre site
			if($use_strict_rights_if_in_admin) {
				$exclude_public_items = true;
			}
			if(((!est_identifie() || !isset($_SESSION['session_utilisateur']['site_id'])) && $site_id != vn($GLOBALS['site_id'])) || (est_identifie() && $_SESSION['session_utilisateur']['site_id'] != $site_id && $_SESSION['session_utilisateur']['site_id'] != 0)) {
				// problème de droit : sécurité, on empêche l'administrateur d'agir sur un site qui ne le concerne pas. Si l'administrateur est associé à site_id = 0, il peut administrer tous les sites, donc cette contrainte ne s'applique pas
				return 0;
			}
		}
	} elseif(!empty($site_id) && !empty($_SESSION['session_utilisateur']['site_id']) && $site_id != $_SESSION['session_utilisateur']['site_id'] && $site_id != vn($GLOBALS['site_id']) && empty($GLOBALS['site_parameters']['multisite_disable_utilisateurs'])) {
		// Protection sur les site_id autorisés : sécurité, on empêche l'utilisateur d'agir sur un site qui ne le concerne pas
		return 0;
	} elseif(isset($_SESSION['session_site_country']) && !empty($GLOBALS['site_parameters']['site_country_allowed_array']) && in_array($table_technical_code, array('articles', 'categories', 'cgv', 'marques', 'html', 'offres', 'produits', 'societe', 'tva', 'vignettes_carrousels')) && ($table_technical_code != 'categories' || empty($GLOBALS['site_parameters']['categories_disabled_show_to_admin_in_front']) || !a_priv("admin_products", false))) {
		// Gestion de l'affichage de contenu spécifique en fonction du pays du visiteur. Cette fonction nécessite une mise en place spécifique en SQL et n'est pas standard.
		// Si pas dans un contexte d'administration de la donnée : ajout de la condition de le pays du visiteur 
		$cond_array[] = "FIND_IN_SET('" . real_escape_string($_SESSION['session_site_country']) . "', " . $prefix . "site_country)";
	}
	// On théorie on devrait ajouter un test sur les tables qui ont le champs site_id en SET, mais FIND_IN_SET fonctionne également avec les champs INT.
	$use_set = (!empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']) || (!empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id_by_table']) && !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id_by_table']['peel_' . $table_technical_code])));
	if($exclude_public_items) {
		// La requête concerne un seul site, sans tenir compte de la configuration globale.
		if(!$use_set) {
			$cond_array[] = $prefix.word_real_escape_string($field)."='".intval($site_id)."'";	
		} else {
			$cond_array[] = "FIND_IN_SET('" . intval($site_id) . "', " . $prefix.word_real_escape_string($field) . ")";
		}
	} else {
		if(!$use_set) {
			// Concerne un site, ou tous les sites
			$cond_array[] = $prefix.word_real_escape_string($field)." IN ('0','" . intval($site_id) . "')";
		} else {
			$cond_array[] = "(FIND_IN_SET('0', " . $prefix.word_real_escape_string($field) . ") OR FIND_IN_SET('" . intval($site_id) . "', " . $prefix.word_real_escape_string($field) . "))";
		}
	}
	return implode(' AND ', $cond_array);
}

/**
 * Retourne la valeur SQL d'un champ INT ou SET suivant que ce soit un entier ou un tableau
 *
 * @param mixed $site_ids
 * @return
 */
function get_site_id_sql_set_value($site_ids) {
	$output = '';
	if(!is_array($site_ids)) {
		$site_ids = array($site_ids);
	}
	$output = "" . implode(",", $site_ids) . "";
	return $output;
}

/**
 * Retourne la valeur SQL d'un champ INT ou SET suivant que ce soit un entier ou un tableau
 *
 * @param mixed $zone_ids
 * @return
 */
function get_zone_id_sql_set_value($zone_ids) {
	$output = '';
	if(!is_array($zone_ids)) {
		$zone_ids = array($zone_ids);
	}
	$output = "" . implode(",", $zone_ids) . "";
	return $output;
}

/**
 * Retourne le nom d'un ou de plusieurs sites à partir de l'id
 *
 * @param mixed $site_ids
 * @param boolean $skip_rights_check
 * @return
 */
function get_site_name($site_ids, $skip_rights_check = false) {
	static $all_sites_name_array;
	$output_array = array();
	if(!is_array($site_ids)) {
		$site_ids = explode(',', $site_ids);
	}
	foreach($site_ids as $this_site_id) {
		if($this_site_id == 0) {
			$output_array[] = vb($GLOBALS['STR_ADMIN_ALL_SITES']);
		} else {
			if(!isset($all_sites_name_array)) {
				$all_sites_name_array = get_all_sites_name_array(false, false, !empty($GLOBALS['site_parameters']['multisite_get_all_site_names_always_allow']));
			}
			if(isset($all_sites_name_array[$this_site_id])) {
				$output_array[] = $all_sites_name_array[$this_site_id];
			}
		}
	}
	return implode(', ', $output_array);
}

/**
 * Retourne un tableau des noms des sites configurés en fonction des droits de l'administrateur
 *
 * @param boolean $admin_force_multisite_if_allowed
 * @param boolean $allow_null_site_id
 * @param boolean $skip_rights_check
 * @return
 */
function get_all_sites_name_array($admin_force_multisite_if_allowed = false, $allow_null_site_id = false, $skip_rights_check = false) {
	static $all_sites_name_array_by_sql;
	// site_id>0 est utile pour ne pas lister les sites avec site_id = 0 qui est en théorie impossible en dehors d'une erreur d'administration
	// Sélection des site_id qui existe en base de données.
	$sql = 'SELECT site_id
		FROM peel_configuration
		WHERE ' . (!$allow_null_site_id?'site_id!="0" ':'') . ' ' . (!$skip_rights_check?'AND '.get_filter_site_cond('configuration', null, true, null, false, $admin_force_multisite_if_allowed):'') . '
		GROUP BY site_id';
	if(!isset($all_sites_name_array_by_sql[md5($sql)])) {
		$all_sites_name_array_by_sql[md5($sql)] = array();
		$query = query($sql);
		while($result = fetch_assoc($query)) {
			// Sélection du nom du site.
			$sql_name = 'SELECT string
				FROM peel_configuration
				WHERE site_id="' . nohtml_real_escape_string(get_site_id_sql_set_value($result['site_id'])).'" AND technical_code="nom_' . $_SESSION['session_langue'] . '"
				LIMIT 1';
			$query_name = query($sql_name);
			$result_name = fetch_assoc($query_name);
			if (!empty($result_name['string'])) {
				$all_sites_name_array_by_sql[md5($sql)][$result['site_id']] = $result_name['string'];
			} else {
				// Le nom du site n'a pas été trouvé. On récupère le wwwroot pour ce site afin d'afficher quand même une valeur.
				$query_wwwroot = query('SELECT string
					FROM peel_configuration
					WHERE site_id="' . nohtml_real_escape_string(get_site_id_sql_set_value($result['site_id'])).'" AND technical_code="wwwroot"
					LIMIT 1');
				if($result_wwwroot = fetch_assoc($query_wwwroot)) {
					// Si il y a un résultat. Dans le cas contraire, le site_id trouvé par la requête au début de la fonction n'est pas associé à un site. Cela peut être dû à plusieurs id ensemble (ex : 1,2,3) dans le cas où le site est configuré pour avoir les champs site_id en SET.
					$all_sites_name_array_by_sql[md5($sql)][$result['site_id']] = str_replace(array('http://' ,'https://'), '', $result_wwwroot['string']);
				}
			}
		}
	}
	return $all_sites_name_array_by_sql[md5($sql)];
}

/**
 *
 * @param string $selected_fonction_name Name of the user job preselected
 * @return
 */
function get_user_job_options($selected_fonction_name = null)
{
	$output = '';
	if (!empty($GLOBALS['site_parameters']['user_job_popover_message_enabled'])) {
		$GLOBALS['js_ready_content_array'][] = '
		$("#fonction").on("change", function () {
		   $("#message_option").text($(this).find(":selected").data("description"));
		   document.getElementById("popover").setAttribute("class", "popover fade right in");
		  if ($("#message_option").html() == "" || $("#message_option").html() == '.$GLOBALS['STR_CHOOSE'].'){
			document.getElementById("popover").setAttribute("class", "hidden");
		  }
		});'
		;
	}
	if (!empty($GLOBALS['site_parameters']['user_job_array'])) {
		$tpl_options = array();
		$user_job_array = $GLOBALS['site_parameters']['user_job_array'];
		if(!empty($selected_fonction_name) && empty($user_job_array[$selected_fonction_name])){
			$user_job_array[$selected_fonction_name] = $selected_fonction_name;
		}
		foreach($user_job_array as $this_job_code=>$this_job) {
			if(StringMb::substr($this_job, 0, 4)== 'STR_' && !empty($GLOBALS[$this_job])) {
				// Si le nom est une variable de langue, il faut utiliser cette variable.
				$this_job = $GLOBALS[$this_job];
				if (!empty($GLOBALS['site_parameters']['user_job_popover_message_enabled'])) {
					$this_data_content = $GLOBALS['site_parameters']['user_job_data_content_'.$this_job_code];
				}
			}
			$output .= '<option data-description="' . vb($this_data_content) . '" value="'.StringMb::str_form_value($this_job_code).'" ' . frmvalide($selected_fonction_name == $this_job_code, ' selected="selected"') . '>'.$this_job.'</option>';
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
	foreach ($values_array as $key => $value) {
		$tpl_options[] = array(
			'value' => StringMb::str_form_value($key),
			'name' => $value,
			'issel' => ($key == $selected_values)
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * Renvoie l'URL d'un site donné
 * 
 * @param integer $site_id
 * @param string $lang
 * @return
 */
function get_site_wwwroot($site_id, $lang = null)
{
	static $output;
	if (is_array($site_id)) {
		// Si site_id est un tableau, on veut le convertir en chaine de caractère pour générer l'id de cache qui servira à ne pas exécuter à nouveau la requête. Par contre il faut laisser $site_id tel quel,get_filter_site_cond gère si site_id est un tableau 
		$site_id_cache_id = get_string_from_array($site_id);
	} else {
		$site_id_cache_id = $site_id;
	}
	if(empty($output[$site_id_cache_id.'_'.$lang])) {
		if(!empty($site_id)) {
			$sql = "SELECT c.string, l.url_rewriting
				FROM peel_configuration c
				LEFT JOIN peel_langues l ON l.lang='" . real_escape_string($lang) . "' AND " . get_filter_site_cond('langues', 'l', false, $site_id) . "
				WHERE c.technical_code = 'wwwroot' AND " . get_filter_site_cond('configuration', 'c', false, $site_id) . " AND c.etat=1
				LIMIT 1";
			$query = query($sql);
			$result = fetch_assoc($query);
		}
		if(!empty($result)) {
			$output[$site_id_cache_id.'_'.$lang] = get_lang_rewrited_wwwroot($lang, $result['string'], $result['url_rewriting']);
		} else {
			$output[$site_id_cache_id.'_'.$lang] = get_lang_rewrited_wwwroot($lang);
		}
	}
	return $output[$site_id_cache_id.'_'.$lang];
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
		$frm['recipient'] = $GLOBALS['support_sav_client'];
		if (!empty($frm['phone'])) {
			// Si $frm['phone'] est défini (attention : phone, et non pas telephone), c'est qu'on arrive du formulaire de demande de rappel par téléphone (call_back_form)
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
				$mandatory_fields = array('nom' => $GLOBALS['STR_ERR_NAME'],
						'telephone' => $GLOBALS['STR_ERR_TEL'],
						'email' => $GLOBALS['STR_ERR_EMAIL'],
						'texte' => $GLOBALS['STR_ERR_MESSAGE'],
						'token' => '');
				if (!empty($GLOBALS['STR_CONTACT_SUBJECT'])) {
					$mandatory_fields['sujet'] = $GLOBALS['STR_ERR_SUBJECT'];
				}
				$module_hook_mandatory_fields = call_module_hook('handle_contact_form_mandatory_fields', array(), 'array');
				if (!empty($module_hook_mandatory_fields)) {
					foreach($module_hook_mandatory_fields as $this_field=>$error_text) {
						$mandatory_fields[$this_field] = $error_text;
					}
				}
				foreach ($_GET as $this_item => $this_value) {
					$fields_list_array = explode(",", vb($GLOBALS['site_parameters']['contact_page_prefill_'.$this_item]['hidden_fields_list']));
					foreach(array_keys($mandatory_fields) as $this_field) {
						if (in_array($this_field, $fields_list_array)) {
							// le champ est masqué donc on le supprime des champs obligatoires
							unset($mandatory_fields[$this_field]);
						}
					}
				}
				$form_error_object->valide_form($frm,
					$mandatory_fields);
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
			if (check_if_module_active('captcha')) {
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
		if (!verify_token('user_contact', 120, false, true, 5)) {
			// Le délai de 5s permet d'éviter du spam de la part de robots simples qui chargent le token et appellent en POST la validation de formulaire
			// Ce délai ne doit pas être trop long pour un utilisateur qui revalide son formulaire déjà tout rempli suite à premier envoi incomplet
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		// Si on arrive du formulaire de demande de rappel par téléphone (call_back_form), $frm['phone'] est défini, et on ne fait pas de tests sur form_error_object
		if (!$form_error_object->count() || !empty($frm['phone'])) {
			if(empty($frm['phone'])){
				foreach ($_GET as $this_item => $this_value) {
					// On récupère uniquement l'information "recipient" de la variable de configuration "contact_page_prefill_XXXXX" car le formulaire prérempli a été soumis et on ne veut pas modifier les éventuelles modifications des champs
					if (!empty($GLOBALS['site_parameters']['contact_page_prefill_'.$this_item]['recipient'])) {
						$frm['recipient'] = $GLOBALS['site_parameters']['contact_page_prefill_'.$this_item]['recipient'];
					}
					// On récupère les champs dédié à l'email de réponse automatique.
					if (!empty($GLOBALS['site_parameters']['contact_page_prefill_'.$this_item]['reponse_sujet'])) {
						$reponse_sujet = $GLOBALS['site_parameters']['contact_page_prefill_'.$this_item]['reponse_sujet'];
					}
					if (!empty($GLOBALS['site_parameters']['contact_page_prefill_'.$this_item]['reponse_texte'])) {
						$reponse_texte = $GLOBALS['site_parameters']['contact_page_prefill_'.$this_item]['reponse_texte'];
					}
					if (!empty($reponse_sujet) && !empty($reponse_texte) && !empty($frm['email'])) {
						send_email($frm['email'],$reponse_sujet,$reponse_texte);
					}
				}
				if (check_if_module_active('captcha')) {
					// Code OK on peut effacer le code
					delete_captcha(vb($frm['code_id']));
				}
				if(empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['REQUEST_METHOD'] != "POST" || is_user_bot()) {
					// Protection du formulaire contre les robots
					die();
				}
				// Limitation du nombre de messages envoyés dans une session
				if (empty($_SESSION['session_form_contact_sent'])) {
					$_SESSION['session_form_contact_sent'] = 0;
				}
				if ($_SESSION['session_form_contact_sent'] < vb($GLOBALS['site_parameters']['contact_form_max_sent_by_session'], 8)) {
					if($_SESSION['session_form_contact_sent']>0) {
						sleep($_SESSION['session_form_contact_sent']);
					}
					$mail_spam_points = floor($_SESSION['session_form_contact_sent']*10/vb($GLOBALS['site_parameters']['contact_form_max_sent_by_session'], 8));
					if (check_if_module_active('spam')) {
						$mail_spam_points += getSpamPoints($frm['texte'], $frm['email']);
					}
					if($mail_spam_points <= vb($GLOBALS['site_parameters']['contact_form_max_spam_points_allowed'], 20)) {
						insere_ticket($frm);
					}
					$_SESSION['session_form_contact_sent']++;
				}
				// Même si la limite d'envois autorisés est atteinte, on dit que c'est OK à l'utilisateur pour que le spammeur ne se rende pas compte qu'il est découvert
				$frm['is_ok'] = true;
			} else {
				insere_ticket($frm);
			}
			// Si le module webmail est activé, on insère dans la table webmail la requête user
			$output .= get_contact_success($frm);
			$form_validated = true;
		}
	} elseif (!empty($_GET['prodid'])) {
		$product_object = new Product($_GET['prodid'], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
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
		$product_object->set_configuration($couleur_id, $taille_id, $attribut_list, check_if_module_active('reseller') && is_reseller());

		$color = $product_object->get_color();
		$size = $product_object->get_size();

		$frm['texte'] = $GLOBALS['STR_PRODUCT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " .$product_object->name.
			(!empty($color)?"\r\n" . $GLOBALS['STR_COLOR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $color :'' ). 
			(!empty($size)?"\r\n" . $GLOBALS['STR_SIZE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $size :'' ). 
			(!empty($product_object->configuration_attributs_list) ? "\r\n" .  str_replace('<br />', "\r\n", $product_object->configuration_attributs_description) : '');

	} elseif (!empty($_GET['product_info_id'])) {
		$product_object = new Product($_GET['product_info_id'], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
		if(!empty($product_object->id) && empty($frm['texte'])) {
			$frm['texte'] = sprintf($GLOBALS['STR_CONTACT_FORM_TEXT_FROM_PRODUCT'], $product_object->reference);
			$frm['sujet'] = $GLOBALS['STR_CONTACT_SELECT1'];
			$frm['product_info_id'] = $_GET['product_info_id'];
		}
	}

	if(empty($form_validated)) {
		if (!empty($noticemsg)) {
			$output .= $noticemsg;
		}
		// Par sécurité, on force par défaut ici le destinataire du message de contact. On pourra toujours le changer après si on veut
		if (!empty($_GET)) {
			foreach ($_GET as $this_item => $this_value) {
				if (!empty($GLOBALS['site_parameters']['contact_page_prefill_'.$this_item])) {
					if (!empty($_POST)) {
						// On récupère uniquement l'information "recipient" de la variable de configuration "contact_page_prefill_XXXXX" car le formulaire prérempli a été soumis et on ne veut pas modifier les éventuelles modifications des champs
						if (!empty($GLOBALS['site_parameters']['contact_page_prefill_'.$this_item]['recipient'])) {
							$frm['recipient'] = $GLOBALS['site_parameters']['contact_page_prefill_'.$this_item]['recipient'];
						}
						foreach ($GLOBALS['site_parameters']['contact_page_prefill_'.$this_item] as $field_name => $field_value) {
							// On récupère le formulaire configuré.
							if (!empty($_POST[$field_name])) {
								// L'utilisateur a peut-être modifié une valeur dans le champ, donc on prend en priorité les données qui viennent du formulaire.
								$frm[$field_name] = $_POST[$field_name];
							} else {
								// Sinon on prend la valeur par défaut du champ (utile pour récuéprer hidden_fields_list par exemple).
								$frm[$field_name] = $field_value;
							}
						}
					} else {
						// Préremplissage des champs du formulaire
						foreach ($GLOBALS['site_parameters']['contact_page_prefill_'.$this_item] as $field_name => $field_value) {
							if ($field_name == 'meta_title') {
								$GLOBALS['meta_title'] = $field_value;
							} elseif ($field_name == 'meta_description') {
								$GLOBALS['meta_description'] = $field_value;
							}
							// On renseigne les informations de session utilisateur si la valeur du champs est vide
							if (empty($field_value)) {
								// Dans le cas pour le nom de la famille, le champs différent on fait donc un test et on concatène avec '_famille'
								if ($field_name == 'nom') {
									$frm[$field_name] = vb($_SESSION['session_utilisateur'][$field_name.'_famille']);
								} else {
									$frm[$field_name] = vb($_SESSION['session_utilisateur'][$field_name]);
								}
							} else {
								$frm[$field_name] = $field_value;
							}
						}
					}
					$frm['contact_page_map_display'] = true;
				}
			}
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
		WHERE nom = '" . nohtml_real_escape_string(StringMb::strtoupper($frm['nom'])) . "' AND " . get_filter_site_cond('codes_promos') . "");
	if ($result = fetch_assoc($qid)) {
		return false;
	}
	if (empty($frm["date_debut"])) {
		$frm["date_debut"] = get_formatted_date(time());
	} 
	if (empty($frm["date_fin"])) {
		// Par défaut: validité de un mois jusqu'à minuit
		// Exemple : si on est le 2 février => valide jusqu'au 2 mars à minuit
		$frm["date_fin"] = get_formatted_date(date("Y-m-d", mktime(0, 0, 0, date('m')+1, date('d')+1, date('Y'))));
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
	if (empty($frm['site_id'])) {
		$frm['site_id'] = $GLOBALS['site_id'];
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
		, promo_code_combinable
	) VALUES (
		'" . nohtml_real_escape_string(StringMb::strtoupper(vb($frm['nom']))) . "'
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
		, '" . nohtml_real_escape_string(get_site_id_sql_set_value(vn($frm['site_id'], $GLOBALS['site_id']))) . "'
		, '" . intval(vb($frm['id_categorie'])) . "'
		, '" . intval(vb($frm['nombre_prevue'])) . "'
		, '" . intval(vb($frm['nb_used_per_client'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['product_filter'])) . "'
		, '" . nohtml_real_escape_string(get_string_from_array(vb($frm['cat_not_apply_code_promo']), true)) . "'
		, '" . nohtml_real_escape_string(vb($frm['promo_code_combinable'])) . "'
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

/**
 * Permet de définir de nouveaux champs dans le formulaire d'inscription / modification d'utilisateur depuis le back office (page "variables de configuration")
 * 
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @param string $form_usage
 * @param integer $step
 * @param boolean $fill_with_user_session
 * @param boolean $get_search_fields_only
 * @param boolean $get_edit_fields_only
 * @param boolean $text_editor_avoid_loading
 * @param integer $fill_with_user_id
 * @return Renvoie un tableau dans l'ordre de présence dans specific_field_titles. Si des champs manquent dans $specific_field_titles mais sont présents dans specific_field_types, ils sont ajoutés à la fin du tableau
 *
 */
function get_specific_field_infos($frm, $form_error_object = null, $form_usage = "user", $step = null, $fill_with_user_session = false, $get_search_fields_only = false, $get_edit_fields_only = false, $text_editor_avoid_loading = false, $fill_with_user_id = null, $custom_template_tags = array()) {
	// Etape 1 : Récupération des configurations
	$specific_fields = array();
	$field_types_with_single_value_array = array('datepicker', 'hidden', 'password', 'separator', 'text', 'textarea', 'html', 'upload');
	
	if(!empty($fill_with_user_id)) {
		// On veut compléter $frm avec les informations d'un utilisateur en base de données
		$user_infos = get_user_information($fill_with_user_id);
	} elseif($fill_with_user_session && est_identifie()) {
		// On veut compléter $frm avec les informations de session d'un utilisateur
		$user_infos = $_SESSION['session_utilisateur'];
	}
		
	$specific_field_prefix = $form_usage;
	if (in_array($form_usage, array('reseller', 'user'))) {
		$mandatory_field_prefix = 'user';
	} else {
		$mandatory_field_prefix = $specific_field_prefix;
	}

	if(!empty($GLOBALS['site_parameters'][$mandatory_field_prefix . '_' . vb($user_infos['user_type']) . '_mandatory_fields'])) {
		// Si il y a une configuration spécifique pour les champs obligatoires
		$mandatory_fields = template_tags_replace(vb($GLOBALS['site_parameters'][$mandatory_field_prefix  . '_' . $user_infos['user_type'] . '_mandatory_fields'], array()), $custom_template_tags);
	} else {
		// Sinon on prennd la configuration par défaut
		$mandatory_fields = template_tags_replace(vb($GLOBALS['site_parameters'][$mandatory_field_prefix . '_mandatory_fields'], array()), $custom_template_tags);
	}

	foreach(array('readonly', 'style', 'titles', 'types', 'names', 'class', 'id', 'values', 'positions', 'steps', 'search', 'types', 'javascript', 'placeholder', 'multiple') as $this_info_name) {
		$this_var_name = 'specific_field_' . $this_info_name;

		if(vb($user_infos['user_type']) == 'company') {
			// Si l'utilisateur est une personne moral
			$specific_field_user_type_prefix = $specific_field_prefix . '_company';
		} else {
			// Sinon c'est une personne physique
			$specific_field_user_type_prefix = $specific_field_prefix . '_person';
		}
		if (!empty($GLOBALS['site_parameters'][$specific_field_user_type_prefix . '_specific_field_' . $this_info_name])) {
			// On test si il existe une variable qui utilise le type d'utilisateur dans le nom; Si oui on prend le contenu de cette variable.
			$$this_var_name = template_tags_replace(vb($GLOBALS['site_parameters'][$specific_field_user_type_prefix . '_specific_field_' . $this_info_name], array()), $custom_template_tags);
		} else {
			if ($specific_field_prefix == 'ad_admin' && empty($GLOBALS['site_parameters'][$specific_field_prefix . '_specific_field_' . $this_info_name])) {
				// Si le champ spécifique pour l'administrateur n'existe pas, on prend la configuration pour le front office qui s'appliquera dans ce cas en back office également.
				$specific_field_prefix = 'ad';
			}
			$$this_var_name = template_tags_replace(vb($GLOBALS['site_parameters'][$specific_field_prefix . '_specific_field_' . $this_info_name], array()), $custom_template_tags);
		}
	}
	$specific_field_exclude = template_tags_replace(vb($GLOBALS['site_parameters'][$specific_field_prefix . '_specific_field_'.(!defined('IN_PEEL_ADMIN')?'front':'back').'_exclude'], array()), $custom_template_tags);
	$specific_field_include = template_tags_replace(vb($GLOBALS['site_parameters'][$specific_field_prefix . '_specific_field_'.(!defined('IN_PEEL_ADMIN')?'front':'back').'_include']), $custom_template_tags);
	if($get_edit_fields_only) {
		$specific_field_exclude = array_merge($specific_field_exclude, vb($GLOBALS['site_parameters'][$specific_field_prefix . '_specific_field_'.(!defined('IN_PEEL_ADMIN')?'front':'back').'_edit_exclude'], array()));
	}
	// Valeurs par défaut
	if (defined('IN_PEEL_ADMIN')) {
		$default_values = vb($GLOBALS['site_parameters']['specific_field_default_values_in_admin'], array());
	} else {
		$default_values = vb($GLOBALS['site_parameters']['specific_field_default_values'], array());
	}
	if(!empty($specific_field_steps)){
		$specific_field_merged_list = array();
		// L'ordre des champs est de préférence celui donné par steps si il est défini.
		foreach($specific_field_steps as $this_fields_list) {
			$this_fields_array = get_array_from_string($this_fields_list);
			foreach($this_fields_array as $this_key => $this_field) {
				if(empty($specific_field_types[$this_field]) || (empty($specific_field_titles[$this_field]) && StringMb::substr($this_field, -3, 1) == '_' && StringMb::substr($this_field, -2) != $_SESSION['session_langue'])) {
					// Champ sans type défini, ou sans titre et pour une autre langue
					// => ne doit pas être ajouté automatiquement si pas dans titles
					unset($this_fields_array[$this_key]);
				}
			}
			$specific_field_merged_list = array_merge($specific_field_merged_list, $this_fields_array);
		}
	} else {
		// Si steps n'est pas défini, on prend l'ordre de titles
		$specific_field_merged_list = array_keys($specific_field_titles);
	}
	$specific_field_merged_list = array_unique($specific_field_merged_list);

	if(!empty($specific_field_merged_list)) {
		foreach($specific_field_merged_list as $this_field) {
			// Etape 2 : Récupération d'éventuelle valeur préremplie stockée dans $frm, venant de BDD ou d'un formulaire
			// (on doit le faire avant d'étudier upload_multiple)
			if (!isset($frm[$this_field])) {
				if (StringMb::strpos($this_field,'[') !==false && !empty($frm[StringMb::substr($this_field, 0, strpos($this_field,'['))])) {
					// La valeur de ce champ est configuré comme un tableau dans la base de donnée, donc on récupère la valeur du champ du formulaire à partir du nom du champ de la BDD, auquel on enlève [] ou [X]
					$this_value = '';
					if (is_array($frm[StringMb::substr($this_field, 0, strpos($this_field,'['))])) {
						$this_value .= $frm[StringMb::substr($this_field, 0, strpos($this_field,'['))][0];
					} else {
						$this_value = $frm[StringMb::substr($this_field, 0, strpos($this_field,'['))];
					}
					$frm[$this_field] = $this_value;
				} elseif(isset($default_values[$this_field])) {
					// La valeur est vide, on regarde si une valeur par défaut est remplie.
					$frm[$this_field] = $default_values[$this_field];
				} elseif(!empty($user_infos) && isset($user_infos[$this_field])) {
					// Remplissage complémentaire des informations avec des données utilisateur
					$frm[$this_field] = $user_infos[$this_field];
				}
			}
			if(!empty($specific_field_multiple[$this_field])) {
				// Plusieurs champs sont à générer en fonction d'un modèle
				// $this_field n'a pas de suffixe numérique, on l'ajoute en l'incrémentant tant qu'on trouve des informations dans $frm
				// Au minimum on génère un champ avec suffixe 1, qui remplace la configuration par défaut du champ
				$new_field_name = $this_field;
				for($i = 1;isset($frm[$this_field . $i]) || $i==1; $i++) {
					$new_field_name = $this_field . $i;
					$new_specific_field_merged_list[] = $new_field_name;
					$specific_field_values[$new_field_name] = $frm[$new_field_name];
					$specific_field_types[$new_field_name] =  vb($specific_field_types[$this_field]);
					$specific_field_positions[$new_field_name] = vb($specific_field_positions[$this_field]);
					$specific_field_titles[$new_field_name] = vb($specific_field_titles[$this_field]);
					$specific_field_javascript[$new_field_name] = vb($specific_field_javascript[$this_field]);
					$mandatory_fields[$new_field_name] = vb($mandatory_fields[$this_field]);
					if (!$get_search_fields_only && is_numeric($step) && !empty($specific_field_steps[$step]) && in_array($this_field, get_array_from_string($specific_field_steps[$step]))) {
						$specific_field_steps[$step] .= ','.$new_field_name;
					}
				}
				unset($specific_field_types[$this_field]);
			}
			if(vb($specific_field_types[$this_field]) == 'upload_multiple') {
				// Gestion de N champs upload stockés dans peel_telechargement
				// On va décliner 1 champ multiple configuré en N champs réels
				if(!empty($specific_field_names[$this_field])) {
					$upload_multiple_values = vb($frm[$specific_field_names[$this_field]]);
					$new_generic_field_name = $specific_field_names[$this_field];
				} else {
					$upload_multiple_values = vb($frm[$this_field]);
					$new_generic_field_name = $this_field;
				}
				if(!is_array($upload_multiple_values)) {
					$upload_multiple_values = array($upload_multiple_values);
				}
				if(!in_array('', $upload_multiple_values)) {
					$upload_multiple_values[] = '';
				}
				if(!empty($upload_multiple_values)) {
					$i = 1;
					foreach($upload_multiple_values as $this_value) {
						// Ce nom doit contenir la chaine "upload_multiple" pour que le fichier fineuploader reconnaisse que c'est un upload_multiple qui est demandé
						$new_field_name = $new_generic_field_name . '_openarray_'.$i.'_closearray_';
						$new_specific_field_merged_list[] = $new_field_name;
						$specific_field_values[$new_field_name] = $this_value;
						$specific_field_types[$new_field_name] = 'upload';
						$specific_field_positions[$new_field_name] = vb($specific_field_positions[$this_field]);
						$specific_field_titles[$new_field_name] = vb($specific_field_titles[$this_field]);
						$specific_field_javascript[$new_field_name] = vb($specific_field_javascript[$this_field]);
						$mandatory_fields[$new_field_name] = vb($mandatory_fields[$this_field]);
						if (!$get_search_fields_only && is_numeric($step) && !empty($specific_field_steps[$step]) && in_array($this_field, get_array_from_string($specific_field_steps[$step]))) {
							$specific_field_steps[$step] .= ','.$new_field_name;
						}
						
						// gestion des messages d'erreurs pour l'upload multiples
						if (is_object($form_error_object) && $form_error_object->has_error($new_generic_field_name)) {
							// Si le champ a une erreur, on va créer l'affichage de l'erreur avec le nouveau nom du champ
							$form_error_object->add($new_field_name, $form_error_object->text($new_generic_field_name, true));
						}

						$i++;
					}
				}
			} else {
				$new_specific_field_merged_list[] = $this_field;
			}
		}
		$specific_field_merged_list = $new_specific_field_merged_list;
		foreach($specific_field_merged_list as $this_field) {
			// Etape 3 : Initialisation
			unset($tpl_options);
			unset($this_value);
			$readonly = false;
			$frm_this_field_values_array = array();
			$this_title = vb($specific_field_titles[$this_field]);
			if(StringMb::substr($this_title, 0, 4)== 'STR_') {
				// Le titre est une variable de langue
				$this_title = $GLOBALS[$this_title];
			}
			$this_id = vb($specific_field_id[$this_field]);
			if($get_search_fields_only && !empty($specific_field_search_types[$this_field])) {
				// Pour la recherche on veut par exemple un select alors qu'on veut une checkbox pour rentrer l'information
				$specific_field_types[$this_field] = $specific_field_search_types[$this_field];
			}
			$temp = explode(',', vb($specific_field_types[$this_field], 'text'), 2);
			$field_type = $temp[0];
			$field_maxlength = vb($temp[1]);
			$field_javascript = vb($specific_field_javascript[$this_field]);
			if (in_array($this_field, $specific_field_readonly)) {
				// La valeur du champ ne doit pas être modifié. On va utiliser l'attribut HTML readonly pour empêcher l'utilisateur de modifier la valeur du champ. Avec readonly la valeur affichée est envoyée en POST, à l'inverse de disabled qui n'envoi pas la valeur dans le formulaire.
				$readonly = true;
			}
			if (in_array($this_field, $specific_field_multiple)) {
				$multiple = true;
			}
			// Etape 4 : Gestion de l'exclusion de certains champs de la boucle
			// Les noms des différents champs dans l'étape sont séparés par des virgules
			if (!$get_search_fields_only && is_numeric($step) && !empty($specific_field_steps[$step]) && !in_array($this_field, get_array_from_string($specific_field_steps[$step]))) {
				// Si le formulaire est segmenté par étape, $specific_field_steps[$step] contient le tableau des champs qui sont configurés pour être affichés à l'étape $step. Si le champ n'est pas trouvé dans ce tableau, on passe au champ suivant.
				continue;
			} elseif($get_search_fields_only && (!empty($specific_field_search) && !in_array($this_field, $specific_field_search))) {
				// Champ absent des formulaires de recherche
				continue;
			} elseif(!$get_search_fields_only && ((!empty($specific_field_include) && !in_array($this_field, $specific_field_include)) || in_array($this_field, $specific_field_exclude))) {
				// Champ configuré pour ne pas apparaître en front ou en back office
				// Si *_include est non vide, alors il faut que le champ soit inclus dedans pour s'afficher
				// Par ailleurs il faut que le champ ne soit pas dans *_exclude pour s'afficher
				continue;
			} elseif (defined('IN_CHANGE_PARAMS') && !empty($GLOBALS['site_parameters']['disable_user_specific_field_on_change_params_page']) && in_array($this_field, $GLOBALS['site_parameters']['disable_user_specific_field_on_change_params_page'])) {
				// Permet d'avoir des champs spécifiques qui seront utilisés lors de l'inscription, et ne pas les afficher sur la page de changement de paramètres
				continue;
			} elseif (empty($specific_field_values[$this_field]) && in_array($field_type, array('radio','hidden','checkbox'))) {
				// La valeur est obligatoire pour un champ hidden, checkbox ou radio, et elle est absente pour le champ étudié
				continue;
			}
						
			// Etape 5 : Gestion du format de la valeur du champ
			if (isset($frm[$this_field])) {
				if (is_array($frm[$this_field])) {
					// Si $frm vient directement du formulaire, les valeurs pour les checkbox sont déjà sous forme de tableau.
					$frm_this_field_values_array = vb($frm[$this_field]);
				} elseif ($field_type == 'checkbox' || $field_type == 'select' ) {
					// pour les checkbox, $frm[$this_field] peut contenir plusieurs valeurs séparées par des virgules si les données viennent de la BDD
					// pour les select aussi, dans le cas de select multiple.
					$frm_this_field_values_array = get_array_from_string($frm[$this_field]);
				} else {
					$frm_this_field_values_array = array($frm[$this_field]);
				}
			}
			
			// Etape 6 : Génération du tableau de propriétés relatives au champ
			$this_field_infos = array('field_type' => $field_type,
					'field_id' => (!empty($this_id)?$this_id:$this_field),
					'field_class' => vb($specific_field_class[$this_field]),
					'field_name' => $this_field,
					'field_title' => $this_title,
					'field_position' => vb($specific_field_positions[$this_field]),
					'field_maxlength' => $field_maxlength,
					'field_style' => vb($specific_field_style[$this_field]),
					'field_placeholder' => vb($specific_field_placeholder[$this_field]),
					'javascript' => $field_javascript,
					'readonly' => !empty($readonly),
					'multiple' => !empty($multiple),
					'mandatory' => (!empty($mandatory_fields[$this_field])),
					'error_text' => (is_object($form_error_object)?$form_error_object->text($this_field):''),
					'STR_CHOOSE' => $GLOBALS['STR_CHOOSE']
				);
				
			if (in_array($field_type, $field_types_with_single_value_array) || empty($field_type) || empty($specific_field_names[$this_field])) {
				if (!empty($frm_this_field_values_array[0]) && !in_array($this_field, vb($GLOBALS['site_parameters']['specific_field_form_display_default_values'], array()))) {
					// Pour récupérer la valeur d'un champ text, la valeur du formulaire $frm_this_field_values_array a priorité sur la valeur prédéfinie dans $specific_field_values
					$this_value = $frm_this_field_values_array[0];
				} else {
					$this_value = template_tags_replace(vb($specific_field_values[$this_field]));
				}
				if($field_type == 'html' && !$text_editor_avoid_loading) {
					$this_field_infos['text_editor_html'] = getTextEditor($this_field, '100%', 300, $this_value, null, 3);
				} elseif($field_type == 'upload') {
					if(!empty($this_value)) {
						// $delete_link = get_current_url(false) . '?mode=suppr&field=' . $this_field . '&file=' . $this_value;
						$delete_link = 'javascript:reinit_upload_field("'.$this_field.'", "[DIV_ID]");';
						$this_field_infos['upload_infos'] = get_uploaded_file_infos($this_field, $this_value, $delete_link, 100, 100);
					}
				} else {
					if ($field_type == 'datepicker') {
						$this_value = get_formatted_date($this_value);
					}
					$this_field_infos['field_value'] = $this_value;
				}

			} else {
				// Champ de type checkbox, radio ou select
				$text_field_values_array = array();
				$this_field_names = get_array_from_string(template_tags_replace(vb($specific_field_names[$this_field])));
				// Préparation du tableau de valeurs
				$this_field_values = get_array_from_string(template_tags_replace($specific_field_values[$this_field]));
				foreach($this_field_values as $this_key => $this_value) {
					if(StringMb::substr($this_value, 0, 4)== 'STR_') {
						// Variable de langue
						$this_field_values[$this_key] = $GLOBALS[$this_value];
					} elseif($this_field=="date_end" && $field_type == 'select') {
						// Nombre de jours présents dans le select, à convertir en une date à venir
						$this_field_values[$this_key] = get_formatted_date(time()+3600*24*$this_value);
					}
				}
				foreach($this_field_values as $this_key => $this_value) {
					if ($this_field=="funding_eligibility" && vn($frm_this_field_values_array[0]) == 1) {
						$this_field_infos['field_style'] = "color:#FF0000;font-weight:bold;";
					}
					if ($this_field=="ad_closed") {
						// Pour clôturer une annonce on passe la date d'insertion à 0. Cet état est différent de enligne.
						$issel = (vb($frm['date_insertion']) == "0000-00-00 00:00:00");
					} else {
						$issel = in_array(trim($this_value), $frm_this_field_values_array);
					}
					if($issel) {
						// Nom de chaque option sélectionnée
						$text_field_values_array[] = trim(StringMb::strip_tags($this_field_names[$this_key]));
					}
					$this_field_infos['options'][trim($this_value)] = array('value' => trim($this_value),
							'issel' => $issel,
							'name' => trim($this_field_names[$this_key]),
							'br' => (StringMb::substr($this_field_names[$this_key], -6) == '<br />')
						);
				}
				$this_field_infos['field_values'] = $text_field_values_array;
				$this_field_infos['field_value'] = implode(', ', $text_field_values_array);
			}
			$specific_fields[$this_field] = $this_field_infos;
		}
	}
	return $specific_fields;
}

/**
 * Traite la réception de champs spécifiques venant d'un formulaire, et l'identification de tous les champs du formulaire qui sont relatifs à une adresse pour remplir $frm['adresses_fields_array']
 *
 * @param array $frm Array with all fields data
 * @param string $form_usage
 * @return
 */
function handle_specific_fields(&$frm, $form_usage = 'user') {
	$table_correspondance = array('user_util_client_person' => 'utilisateurs','user_util_client_company' => 'utilisateurs','user_util_agent_person' => 'utilisateurs','user_util_agent_company' => 'utilisateurs','user_util_contributeur_person' => 'utilisateurs','user_util_contributeur_company' => 'utilisateurs','user_util_expert_person' => 'utilisateurs','user_util_expert_company' => 'utilisateurs','user_util_porteur_person' => 'utilisateurs', 'user_util_porteur_company' => 'utilisateurs', 'user' => 'utilisateurs', 'order' => 'commandes', 'ad' => 'lot_vente', 'ad_admin' => 'lot_vente', 'partner' => 'partenaires', 'user_util_lender_person' => 'utilisateurs', 'user_kyc_person' => 'utilisateurs', 'user_kyc_company' => 'utilisateurs');
	$addresses_potential_fields_array = array('societe', 'prenom', 'nom', 'adresse', 'code_postal', 'ville', 'pays', 'email', 'contact');
	if(empty($table_correspondance[$form_usage])) {
		return null;
	}
	$specific_field_titles = vb($GLOBALS['site_parameters'][$form_usage . '_specific_field_titles'], array());
	$specific_field_types = vb($GLOBALS['site_parameters'][$form_usage . '_specific_field_types'], array());
	$specific_field_steps = vb($GLOBALS['site_parameters'][$form_usage . '_specific_field_steps'], array());

	if ($form_usage == 'ad_admin' && empty($specific_field_titles)) {
		// Il n'y a pas de champ spécifique pour les formulaires d'annonces en back office.
		// Donc on reprend la configuration du formulaire du front.
		$specific_field_titles = vb($GLOBALS['site_parameters']['ad_specific_field_titles'], array());
		$specific_field_types = vb($GLOBALS['site_parameters']['ad_specific_field_types'], array());
		$specific_field_steps = vb($GLOBALS['site_parameters']['ad_specific_field_steps'], array());
	}
	// Récupération des champs de la BDD, pour éviter les erreurs de mise à jour du à une erreur d'administration de user_specific_field_titles, et ne pas mettre les champs type separator dans la requête SQL, et tout autre intru qui ferais échoué la requete.
	$this_table_field_types = get_table_field_types('peel_' . $table_correspondance[$form_usage]);
	$this_table_field_names = get_table_field_names('peel_' . $table_correspondance[$form_usage]);
	
	if(!empty($specific_field_steps)){
		$specific_field_merged_list = array();
		// L'ordre des champs est de préférence celui donné par steps si il est défini.
		foreach($specific_field_steps as $this_fields_list) {
			$this_fields_array = get_array_from_string($this_fields_list);
			foreach($this_fields_array as $this_key => $this_field) {
				if(empty($specific_field_types[$this_field]) || (empty($specific_field_titles[$this_field]) && StringMb::substr($this_field, -3, 1) == '_' && StringMb::substr($this_field, -2) != $_SESSION['session_langue'])) {
					// Champ sans type défini, ou sans titre et pour une autre langue
					// => ne doit pas être ajouté automatiquement si pas dans titles
					unset($this_fields_array[$this_key]);
				}
			}
			$specific_field_merged_list = array_merge($specific_field_merged_list, $this_fields_array);
		}
	} else {
		// Si steps n'est pas défini, on prend l'ordre de titles
		$specific_field_merged_list = array_keys($specific_field_titles);
	}
	$specific_field_merged_list = array_unique($specific_field_merged_list);
	
	foreach($specific_field_merged_list as $this_field) {
		if(!isset($frm[$this_field]) && in_array(vb($specific_field_types[$this_field]), array('checkbox'))) {
			// Si aucune checkbox n'est cochée, $frm[$this_field] n'est pas défini => on doit considérer qu'il est vide
			$frm[$this_field] = '';
		}
		// On identifie tous les champs d'adresse relatifs à cette table
		if(in_array($this_field, $addresses_potential_fields_array)) {
			// Contenu tel que celui de la table utilisateurs, ou autre table avec noms de champs sans suffixe
			$frm['adresses_fields_array'][$this_field] = $this_field;
		} elseif ((StringMb::substr($this_field,-5) == '_ship' ||  StringMb::substr($this_field,-5) == '_bill')) {
			// contenu de la table de commande
			$frm['adresses_fields_array'][StringMb::substr($this_field, 0, -5)] = StringMb::substr($this_field, 0, -5);
		}
		// On traite ci-dessous tous les champs spécifiques
		if ($form_usage == 'user' && (defined('IN_REGISTER') || defined('IN_RETAILER')) && !empty($GLOBALS['site_parameters']['disable_user_specific_field_on_register_page']) && in_array($this_field, $GLOBALS['site_parameters']['disable_user_specific_field_on_register_page'])) {
			// Ne pas prendre en compte les champs absents de la page d'enregistrement
			continue;
		} elseif (vb($specific_field_types[$this_field]) == 'upload') {
			$frm[$this_field] = upload($this_field, false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm[$this_field]));
			if (StringMb::strpos(vb($_POST[$this_field]), '/cache/') === 0) {
				// Comme il y a /cache/ dans le POST, cela veut dire que l'utilisateur a uploadé un nouveau fichier pour ce champ. Dans le cas contraire on a juste le nom du document, sans /cache/.
				// Donc on rempli une variable qui indique quel fichier a été mis à jour par l'utilisateur.
				$_SESSION['document_updated_by_user'][$this_field] = $frm[$this_field];
			}
		}
		if (isset($frm[$this_field]) && in_array($this_field, $this_table_field_names)) {
			// Champ présent dans le formulaire et présent en base de données
			if (vb($specific_field_types[$this_field]) == 'datepicker' || strpos(vb($this_table_field_types[$this_field]), 'date') === 0) {
				// Champ date ou datetime
				$frm[$this_field] = get_mysql_date_from_user_input($frm[$this_field]);
			} elseif (is_array($frm[$this_field])) {
				// Si $frm[$this_field] est un tableau, il faut le convertir en chaine de caractères pour le stockage en BDD
				$frm[$this_field] = implode(',', $frm[$this_field]);
			} elseif(!is_array($frm[$this_field]) && (strpos(vb($this_table_field_types[$this_field]), 'int(') !== false || strpos(vb($this_table_field_types[$this_field]), 'float(') !== false)) {
				$frm[$this_field] = get_float_from_user_input($frm[$this_field]);
				if(strpos(vb($this_table_field_types[$this_field]), 'int(') !== false) {
					$frm[$this_field] = round($frm[$this_field]);
				}
			}
		
			$frm['specific_field_values'][$this_field] = $frm[$this_field];
			if(strpos($this_field, 'tag') !== false || strpos($this_field, 'html') !== false || strpos($this_field, 'description') !== false) {
				// HTML autorisé qu'on corrige et on filtre
				$frm[$this_field] = StringMb::getCleanHTML($frm[$this_field], null, true, true, true, null, vb($GLOBALS['site_parameters']['handle_specific_field_clean_html_safe_mode'], false));
				$frm['specific_field_sql_set'][$this_field] = word_real_escape_string($this_field) . '="' . real_escape_string($frm[$this_field]) . '"';
			} else {
				$frm['specific_field_sql_set'][$this_field] = word_real_escape_string($this_field) . '="' . nohtml_real_escape_string($frm[$this_field]) . '"';
			}
		}
	}
}

/**
 * Traite les champs spécifiques venant d'un formulaire de recherche
 *
 * @param array $frm Array with all fields data
 * @param string $form_usage
 * @param string $prefix
 * @return
 */
function get_specific_fields_search_cond(&$frm, $form_usage = 'user', $prefix = null) {
	$result = array();
	$table_correspondance = array('user' => 'utilisateurs', 'order' => 'commandes', 'ad' => 'lot_vente', 'partner' => 'partenaires');
	if(empty($table_correspondance[$form_usage])) {
		return null;
	}
	$specific_field_titles = vb($GLOBALS['site_parameters'][$form_usage . '_specific_field_titles'], array());
	$specific_field_types = vb($GLOBALS['site_parameters'][$form_usage . '_specific_field_types'], array());
	$specific_field_search = vb($GLOBALS['site_parameters'][$form_usage . '_specific_field_search']);
			
	// Récupération des champs de la BDD, pour éviter les erreurs de mise à jour du à une erreur d'administration de user_specific_field_titles, et ne pas mettre les champs type separator dans la requête SQL, et tout autre intru qui ferais échoué la requete.
	$this_table_field_types = get_table_field_types('peel_' . $table_correspondance[$form_usage]);
	$this_table_field_names = get_table_field_names('peel_' . $table_correspondance[$form_usage]);
	foreach($this_table_field_names as $this_field) {
		// On identifie tous les champs qui sont des specific fields à traiter
		if(empty($specific_field_search) || !in_array($this_field, $specific_field_search)) {
			// Champ absent des formulaires de recherche
			// Soit c'est un specific_field sur lequel on ne fait pas de recherche, soit on le traite par la suite en dehors de cette fonction
			continue;
		}
		if (isset($frm[$this_field])) {
			if (vb($specific_field_types[$this_field]) == 'datepicker') {
				$result[$this_field] = get_mysql_date_from_user_input($frm[$this_field]);
			} elseif(!is_array($frm[$this_field]) && (strpos(vb($this_table_field_types[$this_field]), 'int(') !== false || strpos(vb($this_table_field_types[$this_field]), 'float(') !== false)) {
				$frm[$this_field] = get_float_from_user_input($frm[$this_field]);
				if(strpos(vb($this_table_field_types[$this_field]), 'int(') !== false) {
					$frm[$this_field] = round($frm[$this_field]);
				}
			}
			$result['specific_field_values'][$this_field] = $frm[$this_field];
			if (is_array($frm[$this_field])) {
				$this_sql_cond_array = array();
				foreach($frm[$this_field] as $this_value) {
					$this_sql_cond_array[] = 'FIND_IN_SET("' . nohtml_real_escape_string($this_value) . '", '.$prefix.word_real_escape_string($this_field) . ')';
				}
				$result['specific_field_sql_set'][$this_field] = '('.implode(' OR ', $this_sql_cond_array).')';
			} else {
				$result['specific_field_sql_set'][$this_field] = $prefix.word_real_escape_string($this_field) . ' LIKE "' . nohtml_real_escape_string($frm[$this_field]) . '"';
			}
		}
	}
	return $result;
}

/**
 * Traiter l'affichage de champs spécifiques
 *
 * @param array $specific_field_infos_array Array with all fields data from get_specific_field_infos
 * @param string $display_mode
 * @param boolean $disabled
 * @param boolean $text_only
 * @return
 */
function display_specific_field_form($specific_field_infos_array, $display_mode = 'table', $disabled = false, $text_only = false) {
	$output='';
	$tpl = $GLOBALS['tplEngine']->createTemplate('specific_field.tpl');
	foreach($specific_field_infos_array as $specific_fields) {
		if(!empty($specific_fields['upload_infos'])) {
			// Pour les uploads d'images, on force le mode $read_only si on veut les champs disabled ou si on veut juste un output texte sans input
			$specific_fields['upload_infos']['read_only'] = ($disabled || $text_only);
		}
		if(!empty($specific_fields['error_text'])) {
			$error_on_page = true;
		}
		$tpl->assign('disabled', $disabled);
		$tpl->assign('text_only', $text_only);
		$tpl->assign('f', $specific_fields);
		$mandatory_text = (!empty($specific_fields['mandatory']) ? ' <span class="etoile">*</span>':'');
		if(!empty($specific_fields['field_title']) || !in_array($specific_fields['field_type'], array('hidden', 'separator', 'textarea', 'html'))) {
			if($display_mode == 'div') {
				$output .= '
<div class="row '.(!empty($specific_fields['field_class'])?$specific_fields['field_class']:'').'" style="margin-bottom:10px '.(!empty($specific_fields['field_style'])?';'.$specific_fields['field_style']:'').'">
	<div class="col-sm-4 col-md-5 col-lg-4">'.(!empty($specific_fields['field_title'])?$specific_fields['field_title'].''. $mandatory_text . $GLOBALS['STR_BEFORE_TWO_POINTS_HTML'] .':':'') .'</div>
	<div class="col-sm-8 col-md-7 col-lg-8">' . $tpl->fetch() . vb($specific_fields['error_text']) . '</div>
</div>
';
			} else {
				$output .= '
			<tr>
				<td>'.(!empty($specific_fields['field_title'])?$specific_fields['field_title'].''. $mandatory_text . $GLOBALS['STR_BEFORE_TWO_POINTS_HTML'] . ':':'') . '</td>
				<td>' . $tpl->fetch() . vb($specific_fields['error_text']) . '</td>
			</tr>';
			}
		} else {
			if($display_mode == 'div') {
				$output .= '
<div style="margin-bottom:10px">
	' . $tpl->fetch() . $mandatory_text . vb($specific_fields['error_text']) . '
</div>';
			} else {
				$output .= '
			<tr>
				<td></td>
				<td>' . $tpl->fetch() . $mandatory_text . vb($specific_fields['error_text']) . '</td>
			</tr>';
			}
		}
	}
	if(!empty($error_on_page)) {
		$output = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_FORM']))->fetch() . $output;
	}
	return $output;
}

/**
 * Gère l'ajout de fichiers multiples dans la table peel_telechargement
 * 
 * @param array $frm
 * @param string $source
 * @param string $upload_multiple_fieldname
 * @return
 */
function handle_upload_use_specific_table($frm, $source = '', $upload_multiple_fieldname = 'upload_multiple')
{
	if(!empty($frm[$upload_multiple_fieldname])) {
		$GLOBALS['uploaded_file_already_existing'] = array();
		$GLOBALS['uploaded_file_new'] = array();
		if (!empty($frm['ref'])) {
			$annonce_object = new Annonce($frm['ref']);
		}
		$i = 1;
		if (!empty($frm['messaging_attached_document'])) {
			// Si un document a déjà été uploadé, on peut le passer comme pièce jointe à un message en l'ajoutant dans le POST['messaging_attached_document']
			$GLOBALS['uploaded_file_new'][] = $frm['messaging_attached_document'];
		} else {
			foreach($frm[$upload_multiple_fieldname] as $this_cache_file) {
				$_REQUEST[$upload_multiple_fieldname.'_'.$i] = $this_cache_file;
				$uploaded_files[] = upload($upload_multiple_fieldname.'_'.$i, false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, basename($this_cache_file));
				$i++;
			}
		}
		if (!empty($frm['ref'])) {
			// On supprime tous les fichiers de peel_telechargement liés à $source et qui ne sont plus dans ce qui a été géré par la boucle sur upload ci-dessus
			$annonce_object->delete_pictures(false, $source, $GLOBALS['uploaded_file_already_existing']);
		} else {
			$sql = "SELECT *
				FROM peel_telechargement
				WHERE source='" . real_escape_string($source) . "'";
			$query = query($sql);
			while ($result = fetch_assoc($query)) {
				if (!in_array($result['url'], $GLOBALS['uploaded_file_already_existing'])) {
					// Suppression du fichier sur le disque
					delete_uploaded_file_and_thumbs($result['url']);
					$sql = "DELETE FROM peel_telechargement
						WHERE id='". intval($result['id']) . "'";
					query($sql);
				}
			}
		}
		// On insère uniquement les nouveaux fichiers réellement nouvellement uploadés gérés par a boucle sur upload ci-dessus
		foreach($GLOBALS['uploaded_file_new'] as $this_uploaded_file) {
			$sql = "INSERT INTO peel_telechargement
				SET url='" . nohtml_real_escape_string($this_uploaded_file) . "',
					date='" . date('Y-m-d H:i:s', time()) . "',
					ad_id='" . intval(vn($frm['ref'])) . "',
					source='" . real_escape_string($source) . "'
				ON DUPLICATE KEY UPDATE date=GREATEST(date,'" . date('Y-m-d H:i:s', time()) . "')";
			query($sql);
		}
		if (!empty($frm['ref'])) {
			unset($annonce_object);
		}
	}
}

/**
 * Définit les variables javascript nécessaires pour initialiser fineuploader
 * 
 * @return
 */
 function init_fineuploader_interface() {
	if(defined('IN_PEEL_ADMIN')) {
		$wwwroot = $GLOBALS['wwwroot_in_admin'];
	} else {
		$wwwroot = $GLOBALS['wwwroot'];
	}
	$GLOBALS['js_files'][] = $wwwroot . '/lib/js/jquery-fineuploader.js';
	$GLOBALS['css_files'][] = $wwwroot . '/lib/css/fineuploader.css';
	$GLOBALS['js_content_array'][] = '
window.init_fineuploader = function(object) {
	data_name = object.data("name");
	if(data_name) {
		data_name = data_name.replace(/_openarray_/g, "[").replace(/_closearray_/g, "]");
	}
	object.fineUploader({
		multiple: false,
		request: {
				endpoint: "' . $wwwroot . '/fine_uploader.php?origin=' . urlencode($_SERVER['SCRIPT_FILENAME']) . '",
				inputName: data_name
			},
		failedUploadTextDisplay: {
				mode: "custom",
				maxChars: 100
			},
		text: {
			uploadButton: "' . StringMb::str_form_value($GLOBALS["STR_UPLOAD"]) . '",
			cancelButton: "' . StringMb::str_form_value($GLOBALS["STR_CANCEL"]) . '",
			failUpload: "' . StringMb::str_form_value($GLOBALS["STR_FTP_GET_FAILED"]) . '",
			formatProgress: "{percent}% ' . StringMb::str_form_value($GLOBALS["STR_OUT_OF"]) . ' {total_size}"
		}
	}).on("complete", function(event, id, fileName, responseJSON) {
		if (responseJSON.success) {
			object.replaceWith(responseJSON.html);
		}
	});
};
window.reinit_upload_field = function(input_name, input_id) {
	if(!input_id) {
		input_id=input_name;
	}
	$("#"+input_id).replaceWith("<div class=\"uploader\" id=\""+input_id+"\" data-name=\""+input_name+"\"></div>");
	init_fineuploader($("#"+input_id));
};
';
	$GLOBALS['js_ready_content_array'][] = '
$("input[type=file]").each(function () {
	$(this).replaceWith("<div class=\"uploader\" id=\""+$(this).attr("name")+"\" data-name=\""+$(this).attr("name")+"\"></div>");
});
$(".uploader").each(function () {
	init_fineuploader($(this));
});
';	
}

/**
 * Import d'un produit : mise à jour ou création du produit
 *
 * @param array $field_values Array with all fields data
 * @param array $columns_skipped
 * @param array $product_field_names Names of colums authorized (this array is not mandatory)
 * @param array $specific_fields_array
 * @param boolean $admin_mode
 * @return
 */
function create_or_update_product($field_values, $columns_skipped = array(), $product_field_names = array(), $specific_fields_array = array(), $admin_mode = false, $categories_created_activate = true) {
	$output = '';
	if(!isset($GLOBALS['nbprod_update'])) {
		$GLOBALS['nbprod_update'] = 0;
		$GLOBALS['nbprod_update_null'] = 0;
		$GLOBALS['nbprod_categorie_insert'] = 0;
	}
	if (!isset($GLOBALS['nbprod_insert'])) {
		$GLOBALS['nbprod_insert'] = 0;
	}
	if (isset($field_values['site_id'])) {
		$site_id = $field_values['site_id'];
	} else {
		$site_id = $GLOBALS['site_id'];
	}
	// Gestion des champs impactant $field_values (transformation d'un nom en id par exemple)
	if (!empty($field_values['id_marque'])) {
		if(!is_array($field_values['id_marque'])) {
			$field_values['id_marque'] = array($field_values['id_marque']);
		}
		$this_brand_id_array = array();
		foreach($field_values['id_marque'] as $this_key => $this_field_value) {
			if(StringMb::strlen($this_field_value)>0) {
				// La marque n'est pas vide - il faut que l'import soit compatible avec des noms de marque pouvant être des nombres
				// Par défaut on considère qu'une marque donnée est une id de marque, sinon on gère comme si c'était un nom si pas trouvée et non numérique
				$q = query('SELECT id
					FROM peel_marques
					WHERE id=' . intval($this_field_value) . " AND " . get_filter_site_cond('marques'));
				if ($brand = fetch_assoc($q)) {
					// Marque existante
					$this_brand_id_array[] = $brand['id'];
				} else {
					$sql_select_brand = 'SELECT id 
						FROM peel_marques
						WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_field_value).'" AND ' . get_filter_site_cond('marques');
					$query_brand = query($sql_select_brand);
					if($brand = fetch_assoc($query_brand)){
						$this_brand_id_array[] = $brand['id'];
					}elseif(!empty($this_field_value) && !is_numeric($this_field_value)) {
						// Marque inexistante, on l'insère en base de données.
						$q = query('INSERT INTO peel_marques
							SET nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($this_field_value) . '", etat="1", site_id="' . nohtml_real_escape_string(get_site_id_sql_set_value($site_id)) . '"');
						$this_brand_id_array[] = insert_id();
						if($admin_mode) {
							$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_BRAND_CREATED'], $GLOBALS['line_number'], $field_values['id_marque'])))->fetch();
						}
					}
				}
			}
		}
		// On ne peut stocker qu'un seul id de marque, même si un tableau est fourni. Aritraitement on ne retient que la dernière .
		$field_values['id_marque'] = end($this_brand_id_array);
	}
	// Génération du SQL à partir de $field_values. product_field_names peut contenir des inforamtions non vérifié, provenant d'un formulaire par exemple. Il faut s'assurer qu'il n'y a pas de champ utilisé qui n'existe pas réélement dans la bas de 
	$product_table_field_names = get_table_field_names('peel_produits');
	foreach($field_values as $this_field_name => $this_value) {
		if (!empty($this_field_name) && !in_array($this_field_name, $columns_skipped)) {
			// On ne souhaite pas inclure les champs spécifiques ou non reconnus dans la requête SQL des produits. Mais il ne faut pas supprimer les champs specifiques de $field_values puisque l'on s'en sert après
			if((empty($product_field_names) || in_array($this_field_name, $product_field_names)) && !in_array($this_field_name, array('id', 'Categorie', 'categorie_id')) && in_array($this_field_name, $product_table_field_names)) {
				// On ne tient compte que des colonnes présentes dans la table produits pour sql_fields, les autres champs sont traités séparément
				if(StringMb::strpos($this_field_name, 'nom_') === 0 && StringMb::strlen($this_field_name) == 6 && !empty($GLOBALS['site_parameters']['product_name_forced_lang']) && $this_field_name != 'nom_' . $GLOBALS['site_parameters']['product_name_forced_lang']) {
					// Colonne nom_XX n'existant pas sur ce site, on ne traite pas le champ
					continue;
				}
				if(StringMb::strpos($this_field_name, 'description_') === 0 && StringMb::strlen($this_field_name) == 14 && !empty($GLOBALS['site_parameters']['product_description_forced_lang']) && $this_field_name != 'description_' . $GLOBALS['site_parameters']['product_description_forced_lang']) {
					// Colonne description_XX n'existant pas sur ce site, on ne traite pas le champ
					continue;
				}
				$set_sql_fields[$this_field_name] = word_real_escape_string($this_field_name) . "='" . real_escape_string($this_value) . "'";
			}
		} elseif(empty($specific_fields_array[$this_field_name])) {
			// Ce champ ne sera pas utilisé, on le supprime
			unset($field_values[$this_field_name]);
		}
	}
	if (!empty($field_values['id'])) {
		// On a spécifié une id Produit, donc on essaie de faire un UPDATE
		if (!empty($set_sql_fields)) {
			$sql = "UPDATE peel_produits
				SET " . implode(', ', $set_sql_fields) . "
				WHERE id='" . intval($field_values['id']) . "' AND " . get_filter_site_cond('produits', null, true) . "";
			query($sql);
			if (affected_rows()) {
				$product_id = $field_values['id'];
				$GLOBALS['nbprod_update']++;
				if($admin_mode) {
					$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_LINE_UPDATED'], $GLOBALS['line_number'], $product_id)))->fetch();
				} else {
					$output = true;
				}
			} 
		}
		if (!isset($product_id)) {
			// On vérifie si le produit existe déjà (et donc n'a pas été modifié) ou si il est à créer
			$q = query("SELECT id
				FROM peel_produits
				WHERE id='" . intval($field_values['id']) . "' AND " . get_filter_site_cond('produits', null, true));
			if ($product = fetch_assoc($q)) {
				// Produit existe, et n'avait donc pas été modifié
				$GLOBALS['nbprod_update_null']++;
				$product_id = $field_values['id'];
			} else {
				// Produit inexistant : on va exécuter l'INSERT INTO plus loin en imposant l'id
				$set_sql_fields['id'] = "id='" . intval($field_values['id']) . "'";
			}
		}
	}
	if (!isset($product_id) && !empty($set_sql_fields)) {
		// Produit pas encore existant et $set_sql_fields est forcément non vide ici
		$sql = "INSERT INTO peel_produits
			SET " . implode(', ', $set_sql_fields);
		query($sql);
		$product_id = insert_id();
		$GLOBALS['nbprod_insert']++;
		if($admin_mode) {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_LINE_CREATED'], $GLOBALS['line_number'], $product_id)))->fetch();
		} else {
			$output = true;
		}
	} elseif(!isset($product_id)) {
		if($admin_mode) {
			$output .= 'Problem empty product_id';
		}
		return false;
	}
	// Gestion des champs nécessitant d'écrire dans d'autres tables en connaissant $product_id
	foreach($field_values as $this_field_name => $this_field_value) {
		if($admin_mode && $this_field_name == $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS']) {
			// Gestion de la couleur
			query('DELETE FROM peel_produits_couleurs 
				WHERE produit_id="' . intval($product_id) . '"');
			$this_list_color = explode(",", $this_field_value);
			foreach($this_list_color as $this_value){
				if(StringMb::strlen($this_value)>0) {
					if(!is_numeric($this_value)) {
						$sql_select_color = 'SELECT * 
							FROM peel_couleurs
							WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_value).'" AND ' .  get_filter_site_cond('couleurs');
						$query_color = query($sql_select_color);
						if($color = fetch_assoc($query_color)){
							$this_value = $color['id'];
						}else{
							$sql_insert_color = 'INSERT INTO peel_couleurs (nom_'.$_SESSION['session_langue'].', site_id) 
								VALUES ("'.real_escape_string($this_value).'", "'.nohtml_real_escape_string(get_site_id_sql_set_value($site_id)).'")';
							query($sql_insert_color);
							$this_value = insert_id();
						}
					}
					$sql_select_product_color = 'SELECT * 
						FROM peel_produits_couleurs 
						WHERE produit_id = "' . intval($product_id) . '" AND couleur_id = "' . intval($this_value) . '"';
					$query_select_product_color = query($sql_select_product_color);
					if(!fetch_assoc($query_select_product_color)){
						$sql_match_product_color = 'INSERT INTO peel_produits_couleurs(produit_id,couleur_id) 
							VALUES ("' . intval($product_id) . '","' . intval($this_value) . '")';
						query($sql_match_product_color);
					}
				}
			}
		} elseif($admin_mode && $this_field_name == $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES']){
			// Gestion de la taille
			query('DELETE FROM peel_produits_tailles 
				WHERE produit_id="' . intval($product_id) . '"');
			$this_list_size = explode(",", $this_field_value);
			foreach($this_list_size as $this_value){
				$this_list_size_and_price = explode("§", $this_value);
				$size_name = $this_list_size_and_price[0];
				if(StringMb::strlen($size_name)>0) {
					$size_price = vn($this_list_size_and_price[1]);
					$size_price_reseller = vn($this_list_size_and_price[2]);
					// On ne fait pas de test is_numeric ou pas sur les tailles pour savoir si on parle d'id ou de nom, car une taille peut être un nombre !
					// Donc obligatoirement, on considère qu'une taille est rentrée par son nom
					$sql_size = 'SELECT * 
						FROM peel_tailles 
						WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($size_name).'"  AND ' . get_filter_site_cond('tailles');
					$query_size = query($sql_size);
					if($size = fetch_assoc($query_size)){
						if(isset($this_list_size_and_price[1]) && get_float_from_user_input($size_price) != $size['prix']){
							query('UPDATE peel_tailles 
								SET prix = "'.real_escape_string(get_float_from_user_input($size_price)).'" 
								WHERE id="'.intval($size['id']).'" AND ' . get_filter_site_cond('tailles'));
						}
						if(isset($this_list_size_and_price[2]) && get_float_from_user_input($size_price_reseller) != $size['prix_revendeur']){
							query('UPDATE peel_tailles 
								SET prix_revendeur = "'.real_escape_string(get_float_from_user_input($size_price_reseller)).'" 
								WHERE id="'.intval($size['id']).'" AND ' . get_filter_site_cond('tailles'));
						}
						$this_size_id = $size['id'];
					}else{
						$sql_insert_size = 'INSERT INTO peel_tailles (nom_'.$_SESSION['session_langue'].', prix, prix_revendeur, site_id) 
							VALUES ("'.real_escape_string($size_name).'", "'.floatval(get_float_from_user_input(vn($size_price))).'", "'.floatval(get_float_from_user_input(vn($size_price_reseller))).'", "'. nohtml_real_escape_string(get_site_id_sql_set_value($site_id)).'")';
						query($sql_insert_size);
						$this_size_id = insert_id();
					}
					$select_size_product = 'SELECT * 
						FROM peel_produits_tailles 
						WHERE produit_id = "' . intval($product_id) . '" AND taille_id = "' . intval($this_size_id) . '"';
					$query_size_product = query($select_size_product);
					if(!fetch_assoc($query_size_product)){
						$sql_match_product_size = 'INSERT INTO peel_produits_tailles (produit_id, taille_id) 
							VALUES ("' . intval($product_id) . '", "' . intval($this_size_id) . '")';
						query($sql_match_product_size);
					}
				}
			}
		} elseif (strpos($this_field_name, "§") !== false) {
			// Gestion des prix par lots : tarifs dégressifs
			// Nom du champs
			$this_bulk_discount = explode("§", $this_field_name);
			$this_quantity = $this_bulk_discount[0];
			$this_price_standard = $this_bulk_discount[1];
			$this_price_reseller = $this_bulk_discount[2];
			// Valeur du champs
			if(!empty($this_field_value)){
				$this_package_price = explode("§", $this_field_value);
				$quantity = $this_package_price[0];
				$price_standard = $this_package_price[1];
				$price_reseller = $this_package_price[2];
				if (check_if_module_active('lot')) {
					$sql_prix_lot = 'SELECT * 
						FROM peel_quantites 
						WHERE produit_id="' . intval($product_id) . '" AND quantite = "' . intval($quantity) . '" AND ' . get_filter_site_cond('quantites');
					$query_prix_lot = query($sql_prix_lot);
					if(fetch_assoc($query_prix_lot)){
						$sql_update = 'UPDATE peel_quantites 
							SET quantite = "'.intval($quantity).'"';
						if(isset($this_price_standard) && isset($price_standard)) {
							$sql_update.= ', prix ="'.nohtml_real_escape_string($price_standard).'"';
						}
						if(isset($this_price_reseller) && isset($price_reseller)) {
							$sql_update.= ', prix_revendeur ="'.nohtml_real_escape_string($price_reseller).'"';
						}
						$sql_update.= ', site_id = "'.nohtml_real_escape_string(get_site_id_sql_set_value($site_id)).'"
							WHERE produit_id="' . intval($product_id) . '" AND quantite = "'.intval($quantity).'"';
						query($sql_update);
						if($admin_mode) {
							$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_TARIF_UPDATED'], vb($price_standard), vb($price_reseller), vb($quantity), $product_id)))->fetch();
						}
					} else {
						if(isset($quantity) && $quantity > 0) {
							$q = 'INSERT INTO peel_quantites 
								SET produit_id="' . intval($product_id) . '"';	
							$q.= ', quantite ="'.intval($quantity).'" 
								, site_id = "'.nohtml_real_escape_string(get_site_id_sql_set_value($site_id)).'"';
							if(isset($this_price_standard) && isset($price_standard)){
								$q.= ', prix ="'.nohtml_real_escape_string($price_standard).'"';
							}
							if(isset($this_price_reseller) && isset($price_reseller)){
								$q.= ', prix_revendeur ="'.nohtml_real_escape_string($price_reseller).'"';
							}
							query($q);
							if($admin_mode) {
								$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_TARIF_CREATED'], vb($price_standard), vb($price_reseller), vb($quantity), $product_id)))->fetch();
							}
						}
					}
				}
			}
		} elseif (strpos($this_field_name, "#") !== false && check_if_module_active('attributs')) {
			// Gestion des attributs
			if($admin_mode) {
				$output .= attributes_create_or_update($this_field_name, $this_field_value, $product_id, $site_id, $admin_mode);
			}
		}
	}	
	// Gestion de la catégorie
	unset($this_categories_array);
	if (!empty($field_values['categorie_id']) && !is_numeric($field_values['categorie_id']) && empty($field_values['Categorie'])) {
		// Compatibilité avec anciens champs appelés categorie_id et contenant des noms de catégories
		$field_values['Categorie'] = $field_values['categorie_id'];
		unset($field_values['categorie_id']);
	}
	if (!empty($field_values['Categorie'])) {
		// Ce champ contient une liste de catégories séparées par des virgules
		foreach(explode(',', $field_values['Categorie']) as $this_category) {
			if (is_numeric($this_category)) {
				// le champ Categorie est un id
				$this_categorie_id = intval($this_category);
			} else {
				// le champ Categorie n'est pas un nombre, on tente une recherche dans la BDD sur le nom de la catégorie.
				$q = query('SELECT id
					FROM peel_categories
					WHERE nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($this_category) . '" AND ' . get_filter_site_cond('categories') . '');
				// Catégorie existante, ou le champ Categorie du fichier n'est ni un ID, ni le nom de la catégorie
				if ($categorie = fetch_assoc($q)) {
					$this_categorie_id = $categorie['id'];
				} else {
					if(!empty($field_values['section_catalogue'])) {
						$q = query('INSERT INTO peel_categories
							SET nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($field_values['section_catalogue']) . '", etat="'.(!empty($categories_created_activate)?1:0).'"');
						$categorie_parent_id = insert_id();
					}
					$sql = 'INSERT INTO peel_categories
						SET nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($this_category) . '", etat="'.(!empty($categories_created_activate)?1:0).'", site_id = "' . nohtml_real_escape_string(get_site_id_sql_set_value($site_id)) . '"';
					// Catégorie inexistante : on l'insère en base de données
					if(!empty($categorie_parent_id)) {
						$sql .= ', parent_id="' . intval($categorie_parent_id) . '"';
						unset($categorie_parent_id);
					}
					$q = query($sql);
					$this_categorie_id = insert_id();
					if($admin_mode) {
						$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_CATEGORY_CREATED'], $this_category, $this_categorie_id)))->fetch();
					}
				}
			}
			$this_categories_array[] = $this_categorie_id;
		}
	}
	if (!empty($field_values['categorie_id'])) {
		// On a déjà testé plus haut si categorie_id était numérique ou non, et si pas numérique on l'a supprimé
		// donc là il est forcément numérique
		if (get_category_name($field_values['categorie_id']) !== false) {
			$this_categories_array[] = $field_values['categorie_id'];
		} else {
			if($admin_mode) {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_REFERENCE_DOES_NOT_EXIST'], $field_values['categorie_id'])))->fetch();
			}
		}
	}
	if (!empty($this_categories_array)) {
		if(empty($GLOBALS['site_parameters']['products_import_reset_category_associations_disable'])) {
			// Suppression des anciennes associations entre les produits et les catégories, pour insérer celles du fichier.
			query('DELETE FROM peel_produits_categories
				WHERE produit_id="' . intval($product_id) . '"');
		}
		foreach($this_categories_array as $this_categorie_id) {
			if (!empty($this_categorie_id)) {
				// Vérification que l'association entre les produits, les catégories de produits
				$q = query('SELECT produit_id, categorie_id
					FROM peel_produits_categories
					WHERE produit_id="' . intval($product_id) . '" AND categorie_id="' . intval($this_categorie_id) . '"');
				if (!num_rows($q)) {
					query('INSERT INTO peel_produits_categories
						SET produit_id="' . intval($product_id) . '",
							categorie_id="' . intval($this_categorie_id) . '"');
					$GLOBALS['nbprod_categorie_insert']++;
				}
			}
		}
	}
	// Gestion des stocks
	// Doit être fait à la fin car on doit déjà avoir les couleurs et tailles bien rentrées en base de données
	if(!empty($field_values["Stock"]) && $admin_mode && check_if_module_active('stock_advanced')) {
		// Format stock ou stock§color§size, et les combinaisons sont séparées par ,
		$this_list_stock = explode(",", $field_values["Stock"]);
		$stock_frm = array();
		foreach($this_list_stock as $this_id => $this_value){
			$this_list_infos = explode("§", $this_value);
			$stock_frm["id"][$this_id] = $product_id;
			$stock_frm["stock"][$this_id] = $this_list_infos[0];
			$this_value = vb($this_list_infos[1]);
			if(is_numeric($this_value)) {
				$stock_frm["couleur_id"][$this_id] = $this_value;
			} elseif(!empty($this_value) && !is_numeric($this_value)) {
				$sql_select_color = 'SELECT * 
					FROM peel_couleurs
					WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_value).'" AND ' . get_filter_site_cond('couleurs');
				$query_color = query($sql_select_color);
				if($color = fetch_assoc($query_color)){
					$stock_frm["couleur_id"][$this_id] = $color['id'];
				}
			}
			if(!empty($this_list_infos[2])) {
				// Taille donnée forcément par son nom
				$sql_size = 'SELECT * 
					FROM peel_tailles 
					WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_list_infos[2]).'"  AND ' . get_filter_site_cond('tailles');
				$query_size = query($sql_size);
				if($size = fetch_assoc($query_size)){
					$stock_frm["taille_id"][$this_id] = $size['id'];
				}
			}
		}
		$output .= insere_stock_produit($stock_frm);
	}
	if (check_if_module_active('stock_advanced') && !empty($field_values['on_stock']) && $field_values['on_stock'] == 1) {
		// pas d'output sur cette fonction. Elle ne fait que des manipulation en BDD
		insert_product_in_stock_table_if_not_exist($product_id, 1);
	}
	return $output;
}

/**
 * Comparer l'ordre de deux tableaux qui contiennent un élément "position" pour trier des tableaux de données
 *
 * @param array $arg1
 * @param array $arg2
 * @return
 */
function data_position_sort($arg1, $arg2) {
	$pos1 = vb($arg1['position'], 999999);
	$pos2 = vb($arg2['position'], 999999);
	if ($pos1 < $pos2) {
		return -1;
	} elseif ($pos1 == $pos2) {
		return 0; 
	} else {
		return 1; 
	}
}

/**
 * Récupére le formatage des sépareurs de prix selon le pays du site
 * @return
 */
function set_session_site_country($country_id) {
	// En fonction du pays du site, on stocke les préférences d'affichage de la devise dans session_devise. Cette préférence est moins prioritaire que les tableaux $GLOBALS['site_parameters']['prices_decimal_separator'] et $GLOBALS['site_parameters']['prices_thousands_separator']
	$sql_pays = 'SELECT * 
		FROM peel_pays 
		WHERE id = ' . vn($country_id);
	$query_pays = query($sql_pays);
	if($pays = fetch_assoc($query_pays)){
		$_SESSION['session_site_country'] = $pays['id'];
		$_SESSION['session_devise']['decimal_separator'] = $pays['prices_decimal_separator'];
		$_SESSION['session_devise']['thousands_separator'] = $pays['prices_thousands_separator'];
		return true;
	} else {
		$_SESSION['session_devise']['decimal_separator'] = null;
		$_SESSION['session_devise']['thousands_separator'] = null;
		$_SESSION['session_site_country'] = 0;
		return false;
	}
}

/**
 * Récupère la quantité de produit pour ajouter au panier un produit complémentaire
 *
 * @param intval $reference_id
 * @param intval $produit_id
 * @return
 */
function get_quantity_product_reference($reference_id, $produit_id)
{
	$output = '';
	$sql = "SELECT p.quantity
		FROM peel_produits_references p
		WHERE p.reference_id='" . intval($reference_id) . "' AND p.produit_id='" . intval($produit_id) . "'";
	$query = query($sql);
	if ($product = fetch_assoc($query)) {
		$output = $product['quantity'];
	}
	return $output;
}


















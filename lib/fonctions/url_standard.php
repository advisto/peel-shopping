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
// $Id: url_standard.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

if (!function_exists('get_product_url')) {
	/**
	 * get_product_url()
	 *
	 * @param integer $id
	 * @param string $title
	 * @param integer $rub_id
	 * @param string $rub_name
	 * @return
	 */
	function get_product_url($id, $name=null, $category_id=null, $category_name=null, $add_get_suffixe = false, $html_encode = false)
	{
		$url_prod = get_url("/achat/produit_details.php", array('id' => intval($id)));
		if ($add_get_suffixe) {
			if ($html_encode) {
				$url_prod .= '&amp;';
			} else {
				$url_prod .= '&';
			}
		}
		return $url_prod;
	}
}

if (!function_exists('get_product_category_url')) {
	/**
	 * get_product_category_url()
	 *
	 * @param mixed $id
	 * @param mixed $name
	 * @param mixed $add_get_suffixe
	 * @param mixed $html_encode
	 * @return
	 */
	function get_product_category_url($id = null, $name = null, $add_get_suffixe = false, $html_encode = false)
	{
		$get_array = array();
		if (!empty($id)) {
			$get_array['catid'] = intval($id);
		}
		$url_cat = get_url('/achat/', $get_array, null);
		if ($add_get_suffixe) {
			if (empty($id)) {
				$url_cat .= '?';
			} elseif ($html_encode) {
				$url_cat .= '&amp;';
			} else {
				$url_cat .= '&';
			}
		}
		return $url_cat;
	}
}

if (!function_exists('get_content_url')) {
	/**
	 * get_content_url()
	 *
	 * @param integer $id
	 * @param string $title
	 * @param integer $rub_id
	 * @param string $rub_name
	 * @return
	 */						
	function get_content_url($id, $title = null, $category_id = null, $category_name = null, $add_get_suffixe = false, $html_encode = false)
	{
		$url_art = get_url("/lire/article_details.php", array('rubid' => $id));
		if ($add_get_suffixe) {
			if ($html_encode) {
				$url_art .= '&amp;';
			} else {
				$url_art .= '&';
			}
		}
		return $url_art;
	}
}

if (!function_exists('get_content_category_url')) {
	/**
	 * get_content_category_url()
	 *
	 * @param integer $id
	 * @param mixed $title
	 * @param mixed $rub_id
	 * @param mixed $rub_name
	 * @return
	 */
	function get_content_category_url($id = null, $name = null, $add_get_suffixe = false, $html_encode = false)
	{
		if(!empty($id)) {
			$url_rub = get_url('/lire/', array('rubid' => intval($id)));
			if ($add_get_suffixe) {
				if ($html_encode) {
					$url_rub .= '&amp;';
				} else {
					$url_rub .= '&';
				}
			}
		}else{
			$url_rub = get_url('/lire/');
			if ($add_get_suffixe) {
				$url_rub .= '?';
			}
		}
		return $url_rub;
	}
}

if (!function_exists('get_lang_rewrited_wwwroot')) {
	/**
	 * get_lang_rewrited_wwwroot()
	 *
	 * @param string $this_lang
	 * @param string $this_wwwroot
	 * @param string $this_rewriting
	 * @return
	 */
	function get_lang_rewrited_wwwroot($this_lang, $this_wwwroot = null, $this_rewriting = null)
	{
		return vb($this_wwwroot, $GLOBALS['wwwroot_main']);
	}
}

if (!function_exists('get_map_site_url')) {
	/**
	 * get_map_site_url()
	 *
	 * @param boolean $add_get_suffixe
	 * @param boolean $html_encode
	 * @return
	 */

	function get_map_site_url($add_get_suffixe = false, $html_encode = false)
	{
		$url_map_site_url = get_url('sitemap');

		if ($add_get_suffixe) {
			$url_map_site_url .= '?';

			return $url_map_site_url;
		}
	}
}

if (!function_exists('get_contact_url')) {
	/**
	 * get_contact_url()
	 *
	 * @param boolean $add_get_suffixe
	 * @param boolean $html_encode
	 * @return
	 */
	function get_contact_url($add_get_suffixe = false, $html_encode = false)
	{
		$url_contact_url = get_url('/utilisateurs/contact.php');

		if ($add_get_suffixe) {
			$url_contact_url .= '?';
		}
		return $url_contact_url;
	}
}

if (!function_exists('get_account_url')) {
	/**
	 * get_account_url()
	 *
	 * @return
	 */
	function get_account_url($add_get_suffixe = false, $html_encode = false, $force_logged_in_status = null)
	{
		if($force_logged_in_status || ($force_logged_in_status === null && est_identifie())){
			$url_account_url = get_url('compte');
		} else {
			$url_account_url = get_url('membre');
		}
		if ($add_get_suffixe) {
			$url_account_url .= '?';
		}

		return $url_account_url;
	}
}

if (!function_exists('get_account_register_url')) {
	/**
	 * get_account_register_url
	 *
	 * @param mixed $add_get_suffixe
	 * @param mixed $html_encode
	 * @return
	 */
	function get_account_register_url($add_get_suffixe = false, $html_encode = false)
	{
		$url_account_register = get_url('/utilisateurs/enregistrement.php');
		if ($add_get_suffixe) {
			$url_account_register .= '?';
		}
		return $url_account_register;
	}
}

if (!function_exists('get_cgv_url')) {
	/**
	 * get_cgv_url
	 *
	 * @param mixed $html_encode
	 * @return
	 */
	function get_cgv_url($html_encode = false)
	{
		$url_cgv = get_url('cgv');
		return $url_cgv;
	}
}

if (!function_exists('get_tell_friends_url')) {
	/**
	 * get_tell_friends_url
	 *
	 * @param mixed $html_encode
	 * @return
	 */
	function get_tell_friends_url($html_encode = false)
	{
		$url_tell_friends = get_url('/modules/direaunami/direaunami.php');
		return $url_tell_friends;
	}
}

if (!function_exists('get_url')) {
	/**
	 * Renvoie l'URL réécrite et optimisée
	 * 
	 * @param string $uri Page souhaitée (chemin du fichier php)
	 * @param array $get_array tableau GET
	 * @param string $lang langue 
	 * @param intger $forced_site_id 
	 * @return string URL tenant compte des redirections
	 * @access public
	 */
	function get_url($uri, $get_array = array(), $lang = null, $forced_site_id = null) {
		$uri_by_technical_code = array('catalog' => '/achat/index.php', 'account' => '/compte.php', 'caddie_affichage' => '/achat/caddie_affichage.php', 'achat_maintenant' => '/achat/achat_maintenant.php');
		if(!empty($uri_by_technical_code[$uri])){
			$uri = $uri_by_technical_code[$uri];
		}
		if(empty($lang)) {
			$lang = $_SESSION['session_langue'];
		}
		if(empty($get_array)) {
			$get_array = array();
		}
		if(StringMb::strpos($uri, '/') === false && StringMb::strpos($uri, '.') === false) {
			$uri .= '.php';
		}
		if($uri == '/achat/marque.php' && !empty($get_array['id'])) {
			// On récupère les informations manquantes
			$sql = "SELECT id, nom_" . $_SESSION['session_langue'] . " AS marque
				FROM peel_marques p
				WHERE p.id='" . intval($get_array['id']) . "'";
			$query = query($sql);
			if($result = fetch_assoc($query)){
				$uri = '/' . rewriting_urlencode($GLOBALS['STR_BRAND']) . '/' . StringMb::ucfirst(rewriting_urlencode($result['marque']));
				unset($get_array['id']);
			}
		}
		if(!empty($GLOBALS['site_parameters']['url_use_convertHrefUri']) && function_exists('convertHrefUri') && empty($forced_site_id)) {
			// URL rewirting spécifique, expérimental => désactivé par défaut
			$href_array = $get_array;
			$href_array['script_filename'] = $uri;
			$uri = convertHrefUri(null, $href_array, $lang);
		} else {
			if(StringMb::substr($uri, 0, 1) !== '/') {
				$uri = '/' . $uri;
			}
			if (count($get_array) > 0) {
				foreach ($get_array as $key => $value) {
					$queryString[] = $key . '=' . urlencode($value);
				}
				if(StringMb::strpos($uri, '?') !== false) {
					$uri .= '&';
				} else {
					$uri .= '?';
				}
				$uri .= implode('&', $queryString);
			}
		}
		if(StringMb::strpos($uri, '://') === false) {
			$uri = get_site_wwwroot($forced_site_id, $lang) . $uri;
		}
		$url = handle_setup_redirections($uri, 'value');
		return $url;
	}
}


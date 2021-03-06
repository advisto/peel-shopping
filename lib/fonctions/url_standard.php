<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: url_standard.php 66961 2021-05-24 13:26:45Z sdelaporte $
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
	function get_contact_url($add_get_suffixe = false, $html_encode = false, $site_id = null)
	{
		if (!empty($_GET['page_offline']) && !empty($GLOBALS['site_parameters']['url_rewriting_schema_array']['page_contact'])) {
			// On génère la page pour le site hors ligne, donc il faut formater le nom des fichiers pour ce mode
			// Le schéma de l'url est stocké dans le paramètre url_rewriting_schema_array. Ce paramètre est un tableau qui contient en index le code technique et en valeur le schéma de l'url. Le schéma contient des tags que l'on remplace par les bonnes valeures.
			$custom_template_tags['SITE_ID'] = $GLOBALS['site_id'];
			$custom_template_tags['LANGUE'] = $_SESSION['session_langue'];
			$custom_template_tags['PAGE'] = '';
			$url_contact_url = template_tags_replace($GLOBALS['site_parameters']['url_rewriting_schema_array']['page_contact'], $custom_template_tags, true);
		} else {
			$url_contact_url = get_url('/utilisateurs/contact.php', array(), null, $site_id);
		}
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
		if (!empty($_GET['page_offline'])) {
			// Dans le site offline, les fichiers JS et CSS sont dans un sous dossier à la racine du site offline.
			// Il faut donc adapter les liens vers ces fichiers dans ce mode.
			if(StringMB::strpos($uri,'.css')) {
				return 'css/'.basename($uri);
			} elseif(StringMB::strpos($uri,'.js')) {
				return 'js/'.basename($uri);
			} elseif($uri == '/') {
				return 'index.html';
			} elseif($uri == 'catalog' || $uri == '/achat/') {
				return 'achat_index.html';
			}elseif($uri == 'cgv') {
				return 'cgv.html';
			}
		}
		$uri_by_technical_code = array('catalog' => '/achat/index.php', 'account' => '/compte.php', 'caddie_affichage' => '/achat/caddie_affichage.php', 'achat_maintenant' => '/achat/achat_maintenant.php');
		if(!empty($uri_by_technical_code[$uri])){
			$uri = $uri_by_technical_code[$uri];
		}
		if(!empty($GLOBALS['site_parameters']['uri_by_technical_code']) && !empty($GLOBALS['site_parameters']['uri_by_technical_code'][$uri])){
			$uri = $GLOBALS['site_parameters']['uri_by_technical_code'][$uri];
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
			if (!empty($_GET['page_offline']) && !empty($GLOBALS['site_parameters']['url_rewriting_schema_array']['brand'])) {
				// On génère la page pour le site hors ligne, donc il faut formater le nom des fichiers pour ce mode
				// Le schéma de l'url est stocké dans le paramètre url_rewriting_schema_array. Ce paramètre est un tableau qui contient en index le code technique et en valeur le schéma de l'url. Le schéma contient des tags que l'on remplace par les bonnes valeures.
				$custom_template_tags['SITE_ID'] = $GLOBALS['site_id'];
				$custom_template_tags['LANGUE'] = $_SESSION['session_langue'];
				$custom_template_tags['ID'] = $get_array['id'];
				$custom_template_tags['PAGE'] = vn($get_array['page']);

				return template_tags_replace($GLOBALS['site_parameters']['url_rewriting_schema_array']['brand'], $custom_template_tags, true);
			} else {
				// On récupère les informations manquantes
				$sql = "SELECT id, nom_" . $_SESSION['session_langue'] . " AS marque
					FROM peel_marques p
					WHERE p.id='" . intval($get_array['id']) . "'";
				$query = query($sql);
				if($result = fetch_assoc($query)){
					//get_default_content remplace le contenu par la langue par défaut si les conditions sont réunies
					if (!empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
						$result = get_default_content($result, intval($get_array['id']), 'marques');
					}
					if (function_exists('rewriting_urlencode_marque')) {
						$marque = rewriting_urlencode_marque($result['marque']);
					} else {
						$marque = rewriting_urlencode($result['marque']);
					}
					$uri = '/' . rewriting_urlencode($GLOBALS['STR_BRAND']) . '/' . StringMb::ucfirst($marque);
					unset($get_array['id']);
				}
			}			
		}
		if(!empty($GLOBALS['site_parameters']['url_use_convertHrefUri']) && function_exists('convertHrefUri') && empty($forced_site_id)) {
			// URL rewirting spécifique, expérimental => désactivé par défaut
			$href_array = $get_array;
			$href_array['script_filename'] = $uri;
			$uri = convertHrefUri(null, $href_array, $lang);
		} else {
			if(StringMb::substr($uri, 0, 1) !== '/' && StringMb::strpos($uri, '://') === false) {
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


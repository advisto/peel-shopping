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
// $Id: url_standard.php 39095 2013-12-01 20:24:10Z gboussin $
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
		$url_prod = $GLOBALS['wwwroot'] . "/achat/produit_details.php?id=" . intval($id);
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
		$url_cat = $GLOBALS['wwwroot'] . '/achat/';
		if (!empty($id)) {
			$url_cat .= '?catid=' . intval($id);
		}
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
								
	function get_content_url($id, $title=null, $category_id=null, $category_name=null, $add_get_suffixe = false, $html_encode = false)
	{
		$url_art = $GLOBALS['wwwroot'] . "/lire/article_details.php?rubid=" . $id;
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
			$url_rub = $GLOBALS['wwwroot'] . '/lire/?rubid=' . intval($id);
			if ($add_get_suffixe) {
				if ($html_encode) {
					$url_rub .= '&amp;';
				} else {
					$url_rub .= '&';
				}
			}
		}else{
			$url_rub = $GLOBALS['wwwroot'] . '/lire/';
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
	 * @param mixed $this_lang
	 * @return
	 */
	function get_lang_rewrited_wwwroot($this_lang)
	{
		return $GLOBALS['wwwroot_main'];
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
		$url_map_site_url = $GLOBALS['wwwroot'] . '/sitemap.php';

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
		$url_contact_url = $GLOBALS['wwwroot'] . '/utilisateurs/contact.php';

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
	function get_account_url($add_get_suffixe = false, $html_encode = false)
	{
		$url_account_url = $GLOBALS['wwwroot'] . '/compte.php';
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
		$url_account_register = $GLOBALS['wwwroot'] . "/utilisateurs/enregistrement.php";
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
		$url_cgv = $GLOBALS['wwwroot'] . '/cgv.php';
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
		$url_tell_friends = $GLOBALS['wwwroot'] . '/modules/direaunami/direaunami.php';
		return $url_tell_friends;
	}
}

if (!function_exists('get_search_url')) {
	/**
	 * get_search_url
	 *
	 * @return
	 */
	function get_search_url()
	{
		$url = $GLOBALS['wwwroot'] . '/search.php';
		return $url;
	}
}

?>
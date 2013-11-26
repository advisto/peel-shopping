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
// $Id: fonctions.php 38799 2013-11-18 15:10:10Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

if (!function_exists('affiche_rss')) {
	/**
	 * affiche_rss()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_rss($return_mode = false)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/rss_func.tpl');
		if (is_annonce_module_active()) {
			$tpl->assign('href', $GLOBALS['wwwroot'] . '/modules/annonces/rss.php');
			$tpl->assign('rss_new_window', false);
		} else {
			$tpl->assign('href', $GLOBALS['wwwroot'] . '/modules/rss/rss.php?critere=on_promo');
			$tpl->assign('rss_new_window', true);
		}
		$tpl->assign('src', $GLOBALS['repertoire_images'] . '/rss.png');

		if (!empty($GLOBALS['site_parameters']['facebook_page_link'])) {
			$tpl->assign('fb_href', $GLOBALS['site_parameters']['facebook_page_link']);
			$tpl->assign('fb_src', $GLOBALS['repertoire_images'] . '/facebook.png');
		}
		if (!empty($GLOBALS['site_parameters']['twitter_page_link'])) {
			$tpl->assign('twitter_href', $GLOBALS['site_parameters']['twitter_page_link']);
			$tpl->assign('twitter_src', $GLOBALS['repertoire_images'] . '/twitter.png');
		}
		if (!empty($GLOBALS['site_parameters']['googleplus_page_link'])) {
			$tpl->assign('googleplus_href', $GLOBALS['site_parameters']['googleplus_page_link']);
			$tpl->assign('googleplus_src', $GLOBALS['repertoire_images'] . '/googleplus.png');
		}
		$output .= $tpl->fetch();
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

/**
 * echo_rss_and_die()
 *
 * @param integer $category_id
 * @param integer $seller_id
 * @return
 */
function echo_rss_and_die($category_id = null, $seller_id = null) {
	if (!empty($_GET['encoding'])) {
		$page_encoding = $_GET['encoding'];
	} else {
		$page_encoding = 'utf-8';
	}

	if (empty($_GET['limit'])) {
		$limit = 10;
	} else {
		$limit = min(intval($_GET['limit']), 500);
	}
	if(empty($GLOBALS['meta_title'])){
		if (!is_annonce_module_active()) {
			$GLOBALS['meta_title'] = $GLOBALS['STR_RSS_TITLE'];
		} else {
			// Si le module annonce existe alors, on n'affiche ces dernières annonces
			$ad_categories = get_ad_categories();
			if (!empty($category_id)) {
				$Cat4XML = vb($ad_categories[$category_id]);
			} else {
				$Cat4XML = "" ;
			}
			$GLOBALS['meta_title'] = $GLOBALS['STR_MODULE_RSS_META_RSS'] . ' ' . $Cat4XML;
		}
	}
	if(empty($GLOBALS['meta_description'])){
		if (is_annonce_module_active()) {
			$GLOBALS['meta_description'] = $GLOBALS['STR_MODULE_ANNONCES_LAST_ADS_PUBLISHED'];
		} else {
			$GLOBALS['meta_description'] = $GLOBALS['STR_MODULE_RSS_DESCRIPTION'];
		}
	}
	header('Content-type: application/rss+xml; charset=' . $page_encoding);
	// En-tête
	$output = '';
	$image_xml = '';
	// Recherche du logo du site
	if (!empty($GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']]) && $GLOBALS['site_parameters']['on_logo'] == 1) {
		$image_thumb = thumbs($GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']], 144, 144, 'fit');
		$size_array = @getimagesize($GLOBALS['uploaddir'] . '/thumbs/' . $image_thumb);
		$image_xml .= '
		<image>
			<url>' . String::htmlentities($GLOBALS['repertoire_upload'] . '/thumbs/' . $image_thumb, ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</url>
			<title>' . String::htmlentities($GLOBALS['meta_title'], ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</title>
			<link>' . String::htmlentities($GLOBALS['wwwroot'], ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</link>
			<width>' . vn($size_array[0]) . '</width>
			<height>' . vn($size_array[1]) . '</height>
			<description>' . $GLOBALS['meta_description'] .'</description>
		</image>';
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/rss.tpl');
	$tpl->assign('page_encoding', $page_encoding);
	$tpl->assign('wwwroot', String::htmlentities($GLOBALS['wwwroot'], ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('link', String::htmlentities(get_current_url(true), ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('image_xml', String::htmlentities($image_xml, ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('STR_RSS_TITLE', String::htmlentities($GLOBALS['meta_title'], ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('STR_MODULE_RSS_DESCRIPTION', String::htmlentities($GLOBALS['meta_description'], ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('language', String::htmlentities($_SESSION['session_langue'], ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('pubDate', String::htmlentities(gmdate("r"), ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('generator', String::htmlentities('Advisto RSS Generator 2.1', ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl_items = array();
	if (!is_annonce_module_active()) {
		// Récupération et affichage des données
		$sql = "SELECT p.id, p.prix, p.tva, p.nom_" . $_SESSION['session_langue'] . " AS nom, p.date_maj, p.description_" . $_SESSION['session_langue'] . " AS description, p.promotion, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
			INNER JOIN peel_categories c ON c.id = pc.categorie_id
			WHERE p.etat='1' " . (!empty($category_id)?" AND pc.categorie_id='" . intval($category_id) . "'":"") . "
			GROUP BY p.id
			ORDER BY p.date_maj DESC, p.id DESC
			LIMIT " . intval($limit);
		$result = query($sql);
		while ($prod = fetch_assoc($result)) {
			$product_object = new Product($prod['id'], $prod, false, null, true, !is_micro_entreprise_module_active());
			$desc_rss = trim(str_replace(array("    ", "   ", "  ", " \r", " \n", "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n", "\r\n\r\n\r\n", "\r\n\r\n", "\n\n\n\n\n\n", "\n\n\n", "\n\n"), array(" ", " ", " ", "\r", "\n", "\r\n", "\r\n", "\r\n", "\n", "\n", "\n"), strip_tags(String::html_entity_decode_if_needed(String::htmlspecialchars_decode($product_object->description, ENT_QUOTES)))));
			$promotion_rss = $product_object->get_all_promotions_percentage(false, 0, true);
			$dateRFC = gmdate("r", strtotime($product_object->date_maj));
			$tpl_items[] = array('title' => String::htmlentities($product_object->name . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $product_object->get_final_price(0, display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), true), ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'promotion_rss' => String::htmlentities($promotion_rss, ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'guid' => String::htmlentities($product_object->get_product_url(), ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'pubDate' => String::htmlentities($dateRFC, ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'description' => String::htmlentities($desc_rss, ENT_COMPAT, GENERAL_ENCODING, false, true, true));
			unset($product_object);
		}
	} else {
		// Définit les limites des annonces à afficher
		$sql_cond = "enligne = 'OK' AND `date_insertion`<'" . date('Y-m-d H:i:s', time()) . "'";
		if (!empty($category_id)) {
			$sql_cond .= " AND id_categorie = " . intval($category_id);
		}
		if (!empty($seller_id)) {
			$sql_cond .= " AND id_personne = " . intval($seller_id);
		}
		$sql = "SELECT *
			FROM peel_lot_vente
			WHERE " . $sql_cond . "
			ORDER BY date_insertion DESC
			LIMIT 10";
		$rs = query($sql);
		// Génération des derniéres annonces
		while ($row_rs = fetch_assoc($rs)) {
			$IDP = $row_rs['id_personne'];
			if (empty($category_id) && !empty($row_rs['categorie_id'])) {
				// On n'affiche la catégorie dans le titre que si on n'a pas demandé cette catégorie explicitement
				$category_text = ucfirst($ad_categories[$row_rs['categorie_id']]) . ' - ';
			}
			$dateRFC = gmdate("r", strtotime($row_rs['date_insertion']));
			$annonce_object = new Annonce($row_rs['ref'], null, false, true);
			$desc_rss = trim(str_replace(array("    ", "   ", "  ", " \r", " \n", "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n", "\r\n\r\n\r\n", "\r\n\r\n", "\n\n\n\n\n\n", "\n\n\n", "\n\n"), array(" ", " ", " ", "\r", "\n", "\r\n", "\r\n", "\r\n", "\n", "\n", "\n"), String::strip_tags(String::html_entity_decode_if_needed(String::htmlspecialchars_decode($annonce_object->description, ENT_QUOTES)))));
			$promotion_rss = '';
			$tpl_items[] = array('title' => String::htmlentities(vb($category_text) . String::str_shorten_words(String::str_shorten(String::ucfirst($annonce_object->titre), 120, '', '...', 100), 40), ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'promotion_rss' => $promotion_rss,
				'guid' => String::htmlentities($annonce_object->get_annonce_url(), ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'pubDate' => String::htmlentities($dateRFC, ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'description' => String::htmlentities(String::str_shorten(String::strip_tags(trim($desc_rss)), 250), ENT_COMPAT, GENERAL_ENCODING, false, true, true));
			unset($annonce_object);
		}
	}
	// Fin d'affichage
	$tpl->assign('items', $tpl_items);
	$output .= $tpl->fetch();

	$output = str_replace(array('&euro;'), array('&#8364;'), $output);
	echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);

	// Si on veut activer tracking Analytics de cette page : 
	// il faut renseigner $GLOBALS['site_parameters']['google_analytics_site_code_for_nohtml_pages'] via la page de configuration de variables de l'administration
	close_page_generation(false);
	die();
}
?>
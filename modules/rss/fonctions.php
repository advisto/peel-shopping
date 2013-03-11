<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 35805 2013-03-10 20:43:50Z gboussin $
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
		$tpl->assign('href', $GLOBALS['wwwroot'] . '/modules/rss/rss.php?critere=on_promo');
		$tpl->assign('src', $GLOBALS['wwwroot'] . '/icones/rss.png');

		if (!empty($GLOBALS['site_parameters']['facebook_page_link'])) {
			$tpl->assign('fb_href', $GLOBALS['site_parameters']['facebook_page_link']);
			$tpl->assign('fb_src', $GLOBALS['wwwroot'] . '/icones/facebook.png');
		}
		// <a style="margin-right:5px;" href="https://twitter.com/#!/..." onclick="return(window.open(this.href)?false:true);"><img src="' . $GLOBALS['wwwroot'] . '/icones/logo_twitter.png" alt="twitter" style="vertical-align:top;" title="twitter" /></a>
		// <a style="margin-right:5px;" href="https://plus.google.com/b/..../stream" onclick="return(window.open(this.href)?false:true);"><img src="' . $GLOBALS['wwwroot'] . '/icones/Google-1.png" alt="google+" style="vertical-align:top;" title="google+" /></a>
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
		$GLOBALS['meta_description'] = $GLOBALS['STR_MODULE_RSS_DESCRIPTION'];
	}
	header('Content-type: application/rss+xml; charset=' . $page_encoding);
	// En-tête
	$output = '<' . '?xml version="1.0" encoding="' . $page_encoding . '" ?' . '>
	<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
		<channel>
			<title>' . String::htmlentities($GLOBALS['meta_title'], ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</title>
			<link>' . String::htmlentities($GLOBALS['wwwroot'], ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</link>
			<description>' . String::htmlentities($GLOBALS['meta_description'], ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</description>
			<language>' . String::htmlentities($_SESSION['session_langue'], ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</language>
			<pubDate>' . gmdate("r") . '</pubDate>
			<copyright>&#169; 2004-' . date('Y') . ' Advisto SAS</copyright>
			<generator>Advisto RSS Generator 2.1</generator>
			<atom:link href="' . String::htmlentities(get_current_url(true), ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '" rel="self" type="application/rss+xml" />
	';
	// Recherche du logo du site
	if (!empty($GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']]) && $GLOBALS['site_parameters']['on_logo'] == 1) {
		$image_thumb = thumbs($GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']], 144, 144, 'fit');
		$size_array = @getimagesize($GLOBALS['uploaddir'] . '/thumbs/' . $image_thumb);
		if (is_annonce_module_active()) {
			$image_description = $GLOBALS['STR_MODULE_ANNONCES_LAST_ADS_PUBLISHED'];
		} else {
			$image_description = '';
		}
		$output .= '
		<image>
			<url>' . String::htmlentities($GLOBALS['repertoire_upload'] . '/thumbs/' . $image_thumb, ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</url>
			<title>' . String::htmlentities($GLOBALS['meta_title'], ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</title>
			<link>' . String::htmlentities($GLOBALS['wwwroot'], ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</link>
			<width>' . String::htmlentities(vn($size_array[0]), ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</width>
			<height>' . String::htmlentities(vn($size_array[1]), ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</height>
			<description>' . String::htmlentities($image_description, ENT_COMPAT, GENERAL_ENCODING, false, true, true).'</description>
		</image>';
	}
	if (!is_annonce_module_active()) {
		// Récupération et affichage des données
		$sql = "SELECT p.id, p.prix, p.tva, p.nom_" . $_SESSION['session_langue'] . " AS nom, p.date_maj, p.description_" . $_SESSION['session_langue'] . " AS description, p.promotion, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
			INNER JOIN peel_categories c ON c.id = pc.categorie_id
			WHERE p.etat='1' " . (!empty($cat)?" AND pc.categorie_id='" . intval($cat) . "'":"") . "
			GROUP BY p.id
			LIMIT " . intval($limit);
		$result = query($sql);
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/rss.tpl');
		$tpl->assign('page_encoding', $page_encoding);
		$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
		$tpl->assign('STR_RSS_TITLE', $GLOBALS['STR_RSS_TITLE']);
		$tpl->assign('STR_MODULE_RSS_DESCRIPTION', $GLOBALS['STR_MODULE_RSS_DESCRIPTION']);
		$tpl_items = array();
		while ($prod = fetch_assoc($result)) {
			$product_object = new Product($prod['id'], $prod, false, null, true, !is_micro_entreprise_module_active());
			$desc_rss = trim(str_replace(array("    ", "   ", "  ", " \r", " \n", "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n", "\r\n\r\n\r\n", "\r\n\r\n", "\n\n\n\n\n\n", "\n\n\n", "\n\n"), array(" ", " ", " ", "\r", "\n", "\r\n", "\r\n", "\r\n", "\n", "\n", "\n"), strip_tags(String::html_entity_decode_if_needed(String::htmlspecialchars_decode($product_object->description, ENT_QUOTES)))));
			$promotion_rss = $product_object->get_all_promotions_percentage(false, 0, true);
			$tpl_items[] = array('title' => $product_object->name . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $product_object->get_final_price(0, display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), true),
				'promotion_rss' => $promotion_rss,
				'guid' => $product_object->get_product_url(),
				'pubDate' => gmdate('D, d M Y H:i:s', strtotime($product_object->date_maj)),
				'description' => $desc_rss);
			unset($product_object);
		}
		// Fin d'affichage
		$tpl->assign('items', $tpl_items);
		$output .= $tpl->fetch();
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
			$output .= '
			<item>
				<title>' . String::htmlentities(vb($category_text) . String::str_shorten_words(String::str_shorten(String::ucfirst($annonce_object->titre), 120, '', '...', 100), 40), ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</title>
				<link>' . String::htmlentities($annonce_object->get_annonce_url(), ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</link>
				<description>' . String::htmlentities(String::str_shorten(String::strip_tags(trim($desc_rss)), 250), ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</description>
				<pubDate>' . String::htmlentities($dateRFC, ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</pubDate>
				<guid>' . String::htmlentities($annonce_object->get_annonce_url(), ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</guid>
			</item>';
			unset($annonce_object);
		}
	}
	// Fin d'affichage
	$output .= '
		</channel>
	</rss>
	';

	$output = str_replace(array('&euro;'), array('&#8364;'), $output);
	echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
	die();
}
?>
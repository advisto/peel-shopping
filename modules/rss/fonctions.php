<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 46935 2015-09-18 08:49:48Z gboussin $
if (!defined('IN_PEEL')) {
	die();
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
		if (!check_if_module_active('annonces')) {
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
		if (check_if_module_active('annonces')) {
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
		$size_array = @getimagesize($GLOBALS['uploaddir'] . '/thumbs/' . String::rawurldecode($image_thumb));
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
	if (!check_if_module_active('annonces')) {
		// Récupération et affichage des données
		$sql = "SELECT p.id, p.prix, p.tva, p.nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." AS nom, p.date_maj, p.description_" . (!empty($GLOBALS['site_parameters']['product_description_forced_lang'])?$GLOBALS['site_parameters']['product_description_forced_lang']:$_SESSION['session_langue']) . " AS description, p.promotion, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
			INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
			WHERE p.etat='1' AND " . get_filter_site_cond('produits', 'p') . " " . (!empty($category_id)?" AND pc.categorie_id='" . intval($category_id) . "'":"") . "
			GROUP BY p.id
			ORDER BY p.date_maj DESC, p.id DESC
			LIMIT " . intval($limit);
		$result = query($sql);
		while ($prod = fetch_assoc($result)) {
			$product_object = new Product($prod['id'], $prod, false, null, true, !check_if_module_active('micro_entreprise'));
			$desc_rss = trim(str_replace(array("    ", "   ", "  ", " \r", " \n", "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n", "\r\n\r\n\r\n", "\r\n\r\n", "\n\n\n\n\n\n", "\n\n\n", "\n\n"), array(" ", " ", " ", "\r", "\n", "\r\n", "\r\n", "\r\n", "\n", "\n", "\n"), strip_tags(String::html_entity_decode_if_needed(String::htmlspecialchars_decode($product_object->description, ENT_QUOTES)))));
			$promotion_rss = $product_object->get_all_promotions_percentage(false, 0, true);
			$dateRFC = gmdate("r", strtotime($product_object->date_maj));
			if ($product_object->on_estimate) {
				$product_affiche_prix = display_on_estimate_information(true);
			} elseif ($product_object->on_gift) {
				$product_affiche_prix = $product_object->on_gift_points . ' ' . $GLOBALS['STR_GIFT_POINTS'];
			} else {
				$product_affiche_prix = $product_object->get_final_price(0, display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), true);
			}
			$this_item = array('title' => String::htmlentities($product_object->name . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $product_affiche_prix, ENT_COMPAT, GENERAL_ENCODING, false, true, true),				'promotion_rss' => String::htmlentities($promotion_rss, ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'guid' => String::htmlentities($product_object->get_product_url(), ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'pubDate' => String::htmlentities($dateRFC, ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'description' => String::htmlentities($desc_rss, ENT_COMPAT, GENERAL_ENCODING, false, true, true));
			$imagename = $product_object->get_product_main_picture();
			if(!empty($imagename)) {
				$this_thumb = thumbs($imagename, $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit');
				if (!empty($this_thumb)) {
					$image_infos = getimagesize($GLOBALS['uploaddir'] . '/thumbs/' . String::rawurldecode($this_thumb));
					$this_item['image']['length'] = filesize($GLOBALS['uploaddir'] . '/thumbs/' . String::rawurldecode($this_thumb));
					$this_item['image']['url'] = $GLOBALS['repertoire_upload'] . '/thumbs/' . $this_thumb;
					$this_item['image']['mime'] = $image_infos['mime'];
				}
			}
			$tpl_items[] = $this_item;
			unset($product_object);
		}
	} else {
		// Définit les limites des annonces à afficher
		// date_insertion NOT LIKE '0000%' : la date d'insertion est vide si l'affichage de l'annonce a été désactivé par le propriétaire de l'annonce
		$sql_cond = "enligne='OK' " . (!empty($GLOBALS['site_parameters']['extra_ad_database_fields_array']) && in_array('date_end', $GLOBALS['site_parameters']['extra_ad_database_fields_array']) ?" AND (date_end LIKE '0000%' OR date_end>'" . date('Y-m-d H:i:00', time()) . "')":'') . " AND (date_insertion NOT LIKE '0000%' AND date_insertion<'" . date('Y-m-d H:i:00', time() + 60) . "')";
		if (!empty($category_id)) {
			$sql_cond .= " AND id_categorie=" . intval($category_id);
		}
		if (!empty($seller_id)) {
			$sql_cond .= " AND id_personne=" . intval($seller_id);
		}
		$sql = "SELECT *
			FROM peel_lot_vente
			WHERE " . $sql_cond . " AND " . get_filter_site_cond('lot_vente') . "
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
			$desc_rss = trim(str_replace(array("    ", "   ", "  ", " \r", " \n", "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n", "\r\n\r\n\r\n", "\r\n\r\n", "\n\n\n\n\n\n", "\n\n\n", "\n\n"), array(" ", " ", " ", "\r", "\n", "\r\n", "\r\n", "\r\n", "\n", "\n", "\n"), String::strip_tags(String::html_entity_decode_if_needed(String::htmlspecialchars_decode($annonce_object->get_description(), ENT_QUOTES)))));
			$promotion_rss = '';
			$this_item = array('title' => String::htmlentities(vb($category_text) . String::str_shorten_words(String::str_shorten(String::ucfirst($annonce_object->get_titre()), 120, '', '...', 100), 40), ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'promotion_rss' => $promotion_rss,
				'guid' => String::htmlentities($annonce_object->get_annonce_url(), ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'pubDate' => String::htmlentities($dateRFC, ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'description' => String::htmlentities(String::str_shorten(String::strip_tags(trim($desc_rss)), 250), ENT_COMPAT, GENERAL_ENCODING, false, true, true));
			$image_url = $annonce_object->get_annonce_picture(true, $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height']);
			if(!empty($image_url)) {
				$image_infos = getimagesize(String::rawurldecode(str_replace($GLOBALS['repertoire_upload'], $GLOBALS['uploaddir'], $image_url)));
				$this_item['image']['length'] = filesize(String::rawurldecode(str_replace($GLOBALS['repertoire_upload'], $GLOBALS['uploaddir'], $image_url)));
				$this_item['image']['url'] = $image_url;
				$this_item['image']['mime'] = $image_infos['mime'];
			}
			$tpl_items[] = $this_item;
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

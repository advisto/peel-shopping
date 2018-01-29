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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $
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
		$limit = vb($GLOBALS['site_parameters']['rss_default_items_count'], 15);
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
		$image_pathinfo = pathinfo($GLOBALS['site_parameters']['logo_' . $_SESSION['session_langue']]);
		$dirname = str_replace($GLOBALS['wwwroot'],$GLOBALS['dirroot'], $image_pathinfo['dirname']);
		$image_thumb = thumbs($image_pathinfo['basename'], 144, 144, 'fit', $dirname);
		$size_array = @getimagesize($GLOBALS['uploaddir'] . '/thumbs/' . StringMb::rawurldecode($image_thumb));
		if (!empty($image_thumb)) {
			$image_xml .= '
			<image>
				<url>' . StringMb::htmlentities($GLOBALS['repertoire_upload'] . '/thumbs/' . $image_thumb, ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</url>
				<title>' . StringMb::htmlentities($GLOBALS['meta_title'], ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</title>
				<link>' . StringMb::htmlentities($GLOBALS['wwwroot'], ENT_COMPAT, GENERAL_ENCODING, false, true, true) . '</link>
				<width>' . vn($size_array[0]) . '</width>
				<height>' . vn($size_array[1]) . '</height>
				<description>' . $GLOBALS['meta_description'] .'</description>
			</image>';
		}
	}
	$dateRFC = gmdate('D, d M Y H:i:s').' GMT';
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/rss.tpl');
	$tpl->assign('page_encoding', $page_encoding);
	$tpl->assign('wwwroot', StringMb::htmlentities($GLOBALS['wwwroot'], ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('link', StringMb::htmlentities(get_current_url(true), ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('image_xml', StringMb::htmlentities($image_xml, ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('STR_RSS_TITLE', StringMb::htmlentities($GLOBALS['meta_title'], ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('STR_MODULE_RSS_DESCRIPTION', StringMb::htmlentities($GLOBALS['meta_description'], ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('language', StringMb::htmlentities($_SESSION['session_langue'], ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('pubDate', StringMb::htmlentities($dateRFC, ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl->assign('generator', StringMb::htmlentities('Advisto RSS Generator 2.1', ENT_COMPAT, GENERAL_ENCODING, false, true, true));
	$tpl_items = array();
	if (!check_if_module_active('annonces')) {
		// Récupération et affichage des données
		if ($GLOBALS['site_id'] == vn($GLOBALS['site_parameters']['main_site_id'])) {
			// On affiche tous les produits sur le site principal si il existe un site principal
			$category_cond = "";
			$product_cond = "";
		} else {
			// Par défaut, on affiche uniquement les produits du site consulté
			$category_cond = " AND " . get_filter_site_cond('categories', 'c') . "";
			$product_cond = " AND " . get_filter_site_cond('produits', 'p') . "";
		}
		$sql = "SELECT p.*, p.nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." AS nom, p.description_" . (!empty($GLOBALS['site_parameters']['product_description_forced_lang'])?$GLOBALS['site_parameters']['product_description_forced_lang']:$_SESSION['session_langue']) . " AS description, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
			INNER JOIN peel_categories c ON c.id = pc.categorie_id" . $category_cond . "
			WHERE p.etat='1'" . $product_cond . " " . (!empty($category_id)?" AND pc.categorie_id='" . intval($category_id) . "'":"") . "
			GROUP BY p.id
			ORDER BY p.date_maj DESC, p.id DESC
			LIMIT " . intval($limit);
		$query = query($sql);
		while ($prod = fetch_assoc($query)) {
			if (!isset($last_site_id) || (isset($last_site_id) && $prod['site_id'] != $last_site_id)) {
				// Premier passage ou changement de site_id (les résultats sont triés par site_id)
				$GLOBALS['site_id'] = $prod['site_id'];
				load_site_parameters(null, false, $GLOBALS['site_id']);
			}
			$product_object = new Product($prod['id'], $prod, false, null, true, !check_if_module_active('micro_entreprise'));
			$desc_rss = trim(str_replace(array("    ", "   ", "  ", " \r", " \n", "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n", "\r\n\r\n\r\n", "\r\n\r\n", "\n\n\n\n\n\n", "\n\n\n", "\n\n"), array(" ", " ", " ", "\r", "\n", "\r\n", "\r\n", "\r\n", "\n", "\n", "\n"), strip_tags(StringMb::html_entity_decode_if_needed(StringMb::htmlspecialchars_decode($product_object->description, ENT_QUOTES)))));
			$promotion_rss = $product_object->get_all_promotions_percentage(false, 0, true);
			$dateRFC = gmdate('D, d M Y H:i:s', strtotime($product_object->date_maj)).' GMT';
			if ($product_object->on_estimate) {
				$product_affiche_prix = display_on_estimate_information(true);
			} elseif ($product_object->on_gift) {
				$product_affiche_prix = $product_object->on_gift_points . ' ' . $GLOBALS['STR_GIFT_POINTS'];
			} else {
				$product_affiche_prix = $product_object->get_final_price(0, display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), true);
			}
			$this_item = array('title' => StringMb::htmlentities($product_object->name . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $product_affiche_prix, ENT_COMPAT, GENERAL_ENCODING, false, true, true),				'promotion_rss' => StringMb::htmlentities($promotion_rss, ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'guid' => StringMb::htmlentities($product_object->get_product_url(), ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'pubDate' => StringMb::htmlentities($dateRFC, ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'description' => StringMb::htmlentities($desc_rss, ENT_COMPAT, GENERAL_ENCODING, false, true, true));
			$imagename = $product_object->get_product_main_picture();
			if(!empty($imagename)) {
				$this_thumb = thumbs($imagename, $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit');
				if (!empty($this_thumb)) {
					$image_infos = getimagesize($GLOBALS['uploaddir'] . '/thumbs/' . StringMb::rawurldecode($this_thumb));
					$this_item['image']['length'] = filesize($GLOBALS['uploaddir'] . '/thumbs/' . StringMb::rawurldecode($this_thumb));
					$this_item['image']['url'] = $GLOBALS['repertoire_upload'] . '/thumbs/' . $this_thumb;
					$this_item['image']['mime'] = $image_infos['mime'];
				}
			}
			$tpl_items[] = $this_item;
			unset($product_object);
			$last_site_id = $prod['site_id'];
		}
	} else {
		// Définit les limites des annonces à afficher
		// date_insertion NOT LIKE '0000%' : la date d'insertion est vide si l'affichage de l'annonce a été désactivé par le propriétaire de l'annonce
		$sql_cond = "enligne='OK' " . (!empty($GLOBALS['site_parameters']['extra_ad_database_fields_array']) && in_array('date_end', $GLOBALS['site_parameters']['extra_ad_database_fields_array']) ?" AND (date_end LIKE '0000%' OR date_end>='" . date('Y-m-d 00:00:00', time()) . "')":'') . " AND (date_insertion NOT LIKE '0000%' AND date_insertion<'" . date('Y-m-d H:i:00', time() + 60) . "')";
		if (!empty($category_id)) {
			$sql_cond .= " AND id_categorie=" . intval($category_id);
		}
		if (!empty($seller_id)) {
			$sql_cond .= " AND id_personne=" . intval($seller_id);
		}
		if ($GLOBALS['site_id'] != vn($GLOBALS['site_parameters']['main_site_id'])) { 
			// On affiche toutes les annonces sur le site principal
			$sql_cond .= " AND " . get_filter_site_cond('lot_vente') . "";
		}
		$sql = "SELECT *
			FROM peel_lot_vente
			WHERE " . $sql_cond . "
			ORDER BY date_insertion DESC
			LIMIT ". intval($limit);
		$rs = query($sql);
		// Génération des derniéres annonces
		while ($row_rs = fetch_assoc($rs)) {
			if (!isset($last_site_id) || (isset($last_site_id) && $row_rs['site_id'] != $last_site_id)) {
				// Premier passage ou changement de site_id (les résultats sont triés par site_id)
				$GLOBALS['site_id'] = $row_rs['site_id'];
				load_site_parameters(null, false, $GLOBALS['site_id']);
			}
			$IDP = $row_rs['id_personne'];
			if (empty($category_id) && !empty($row_rs['categorie_id'])) {
				// On n'affiche la catégorie dans le titre que si on n'a pas demandé cette catégorie explicitement
				$category_text = ucfirst($ad_categories[$row_rs['categorie_id']]) . ' - ';
			}
			$dateRFC = gmdate('D, d M Y H:i:s', strtotime($row_rs['date_insertion'])).' GMT';
			$annonce_object = new Annonce($row_rs['ref'], null, false, true);
			$desc_rss = trim(str_replace(array("    ", "   ", "  ", " \r", " \n", "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n", "\r\n\r\n\r\n", "\r\n\r\n", "\n\n\n\n\n\n", "\n\n\n", "\n\n"), array(" ", " ", " ", "\r", "\n", "\r\n", "\r\n", "\r\n", "\n", "\n", "\n"), StringMb::strip_tags(StringMb::html_entity_decode_if_needed(StringMb::htmlspecialchars_decode($annonce_object->get_description(), ENT_QUOTES)))));
			$promotion_rss = '';
			$this_item = array('title' => StringMb::htmlentities(vb($category_text) . StringMb::str_shorten_words(StringMb::str_shorten(StringMb::ucfirst($annonce_object->get_titre()), 120, '', '...', 100), 40), ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'promotion_rss' => $promotion_rss,
				'guid' => StringMb::htmlentities($annonce_object->get_annonce_url(), ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'pubDate' => StringMb::htmlentities($dateRFC, ENT_COMPAT, GENERAL_ENCODING, false, true, true),
				'description' => StringMb::htmlentities(StringMb::str_shorten(StringMb::strip_tags(trim($desc_rss)), 250), ENT_COMPAT, GENERAL_ENCODING, false, true, true));
			$image_url = $annonce_object->get_annonce_picture(true, $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height']);
			if(!empty($image_url)) {
				$image_file = $annonce_object->get_annonce_picture(true, $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], true);
				$image_infos = getimagesize($image_file);
				$this_item['image']['length'] = filesize($image_file);
				$this_item['image']['url'] = $image_url;
				$this_item['image']['mime'] = $image_infos['mime'];
			}
			$tpl_items[] = $this_item;
			unset($annonce_object);
			$last_site_id = $row_rs['site_id'];
		}
	}
	// Fin d'affichage
	$tpl->assign('items', $tpl_items);
	$output .= $tpl->fetch();

	$output = str_replace(array('&euro;'), array('&#8364;'), $output);
	echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);

	// Si on veut activer tracking Analytics de cette page : 
	// il faut renseigner $GLOBALS['site_parameters']['google_analytics_site_code_for_nohtml_pages'] via la page de configuration de variables de l'administration
	close_page_generation(false);
	die();
}

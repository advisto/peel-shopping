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
 * affiche_menu_deroulant_1()
 *
 * @param mixed $div_id
 * @param array $items_html_array
 * @return
 */
function affiche_menu_deroulant_1($div_id, $items_html_array)
{
	$output = '';
	$fcontent = array();
	if (!empty($items_html_array) && count($items_html_array) == 1) {
		$output .= current($items_html_array);
	} elseif (!empty($items_html_array)) {
		$i = 0;
		foreach ($items_html_array as $this_item_html) {
			$fcontent[] = 'scrollercontent[\'' . $div_id . '\'][' . ($i++) . ']=\'' . str_replace(array('   ', '  ', "\t"), ' ', filtre_javascript($this_item_html, true, true, false)) . '\';' . "\r\n";
		}
		$output .= '
<script><!--//--><![CDATA[//><!--
	' . (empty($GLOBALS['scroller_1_already_initialized']) ? '
	var index = new Array();
	var scrollercontent=new Array();
	' : '') . '
	index[\'' . $div_id . '\']=0;
	scrollercontent[\'' . $div_id . '\']=new Array();
	' . implode('', $fcontent) . '
//--><!]]></script>
';
		$GLOBALS['js_ready_content_array'][] = '
	changecontent(\'' . $div_id . '\');
';
		if (empty($GLOBALS['scroller_1_already_initialized'])) {
			$GLOBALS['js_files_pageonly'][] = get_url('/modules/menus/scroller.js');
			$GLOBALS['scroller_1_already_initialized'] = true;
		}
		$output .= '
<div id="' . $div_id . '"></div>
';
	}
	return $output;
}

/**
 * affiche_menu_deroulant_2()
 *
 * @param mixed $div_id
 * @param array $items_html_array
 * @return
 */
function affiche_menu_deroulant_2($div_id, $items_html_array)
{
	$output = '';
	$pausecontent = array();

	$items_html_array = (isset($items_html_array)) ? $items_html_array : get_on_rollover_products_html();
	if (!empty($items_html_array) && count($items_html_array) == 1) {
		$output .= current($items_html_array);
	} elseif (!empty($items_html_array)) {
		$i = 0;
		foreach ($items_html_array as $this_item_html) {
			$pausecontent[] = $div_id . '_content[' . ($i++) . ']=\'' . str_replace(array('   ', '  ', "\t"), ' ', filtre_javascript($this_item_html, true, true, false)) . '\';' . "\r\n";
		}
		if (empty($GLOBALS['scroller_2_already_initialized'])) {
			$GLOBALS['js_files_pageonly'][] = get_url('/modules/menus/pausescroller.js');
			$GLOBALS['scroller_2_already_initialized'] = true;
		}
		$GLOBALS['js_content_array'][] = '
var ' . $div_id . '_content=new Array();
' . implode('', $pausecontent) . '
';
		$GLOBALS['js_ready_content_array'][] = '
new pausescroller(' . $div_id . '_content, "' . $div_id . '", 3000);
';
		$output .= '
<div id="' . $div_id . '" class="' . $div_id . '_class pausescroller_container"></div>
';
	}
	return $output;
}

/**
 *
 * @return
 */
function get_on_rollover_products_html()
{
	$items = array();
	$sql = "SELECT p.*, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
		FROM peel_produits p
		INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
		INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
		WHERE p.on_rollover = '1' AND " . get_filter_site_cond('produits', 'p') . " AND p.nom_" . (!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']) . " != '' AND c.nom_" . $_SESSION['session_langue'] . " != '' AND p.etat='1'  ".(empty($GLOBALS['site_parameters']['allow_command_product_ongift'])?" AND p.on_gift != '1'":'')."
		GROUP BY p.id
		ORDER BY p.date_insere DESC
		LIMIT 20";
	$query = query($sql);
	$i = 0;
	while ($prod = fetch_assoc($query)) {
		$product_object = new Product($prod['id'], $prod, true, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
		$product_html = get_product_in_container_html($product_object, $GLOBALS['site_parameters']['only_show_products_with_picture_in_containers']);
		unset($product_object);
		if (!empty($product_html)) {
			$items[] = $product_html;
		}
	}
	return $items;
}
/**
 *
 * @return
 */
function get_on_rollover_articles_html()
{
	$items = array();
	$sql = "SELECT a.on_reseller, a.titre_" . $_SESSION['session_langue'] . " as name, a.image1 as image, a.date_insere, a.etat, a.id, a.on_special, r.id AS rubrique_id, r.nom_" . $_SESSION['session_langue'] . " AS rubrique
		FROM peel_articles a
		INNER JOIN peel_articles_rubriques ar ON a.id = ar.article_id
		INNER JOIN peel_rubriques r ON r.id = ar.rubrique_id AND " . get_filter_site_cond('rubriques', 'r') . "
		WHERE a.on_rollover = '1' AND a.titre_" . $_SESSION['session_langue'] . " != '' AND r.nom_" . $_SESSION['session_langue'] . " != '' AND a.etat='1' AND " . get_filter_site_cond('articles', 'a') . "
		GROUP BY a.id
		ORDER BY a.date_insere DESC
		LIMIT 20";
	$query = query($sql);
	$i = 0;
	while ($article = fetch_assoc($query)) {
		if ((!a_priv("admin_product") && !a_priv("reve")) && $article['on_reseller'] == 1) {
			continue;
		} else {
			$article_html = get_articles_in_container_html($article, $GLOBALS['site_parameters']['only_show_articles_with_picture_in_containers']);
			if (!empty($article_html)) {
				$items[] = $article_html;
			}
		}
	}
	return $items;
}


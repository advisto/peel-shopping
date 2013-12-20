<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 39392 2013-12-20 11:08:42Z gboussin $
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
		if (empty($GLOBALS['scroller_1_already_initialized'])) {
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot'] . '/modules/menus/scroller.js';
			$GLOBALS['js_content_array'][] = '
	var delay = 3000; //set delay between message change (in miliseconds)
	var maxsteps=20; // number of steps to take to change from start color to endcolor
	var stepdelay=25; // time in miliseconds of a single step
	var startcolor= new Array(255,255,255); // start color (red, green, blue)
	var endcolor=new Array(0,0,0); // end color (red, green, blue)
	var begintag=\'<div>\';
	var closetag=\'</div>\';
	var fwidth=\'160px\'; //set scroller width
	var fheight=\'170px\'; //set scroller height
	var fadelinks=0;  //should links inside scroller content also fade like text? 0 for no, 1 for yes.
	var fadecounter=new Array();
	var scrollercontent=new Array();
	var index_max = new Array();
	var index = new Array();
';
			$GLOBALS['scroller_1_already_initialized'] = true;
		}
		$GLOBALS['js_content_array'][] = '
	index[\'' . $div_id . '\'] = 0;
	index_max[\'' . $div_id . '\'] =' . $i . ';
	scrollercontent[\'' . $div_id . '\']=new Array();
	' . implode('', $fcontent) . '
';
		$GLOBALS['js_ready_content_array'][] = '
	changecontent(\'' . $div_id . '\');
';
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
			$GLOBALS['js_files_pageonly'][] = $GLOBALS['wwwroot'] . '/modules/menus/pausescroller.js';
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
 * get_on_rollover_products_html()
 *
 * @return
 */
function get_on_rollover_products_html()
{
	$items = array();
	$sql = "SELECT p.*, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
		FROM peel_produits p
		INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
		INNER JOIN peel_categories c ON c.id = pc.categorie_id
		WHERE p.on_rollover = '1' AND p.nom_" . $_SESSION['session_langue'] . " != '' AND c.nom_" . $_SESSION['session_langue'] . " != '' AND p.etat='1'  ".(empty($GLOBALS['site_parameters']['allow_command_product_ongift'])?" AND p.on_gift != '1'":'')."
		GROUP BY p.id
		ORDER BY p.date_insere DESC
		LIMIT 20";
	$query = query($sql);
	$i = 0;
	while ($prod = fetch_assoc($query)) {
		$product_object = new Product($prod['id'], $prod, true, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
		$product_html = get_product_in_container_html($product_object, $GLOBALS['site_parameters']['only_show_products_with_picture_in_containers']);
		unset($product_object);
		if (!empty($product_html)) {
			$items[] = $product_html;
		}
	}
	return $items;
}

?>
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

/**
 * get_cart_popup_script()
 *
 * @return
 */
function get_cart_popup_script()
{
	$output = '';
	// Gestion de l'interstitiel de publicité
	$output .= '
	<script src="' . $GLOBALS['wwwroot'] . '/lib/js/interstitiel.js"></script>
';
	return $output;
}

if (!function_exists('get_cart_popup_div')) {
	/**
	 * get_cart_popup_div()
	 *
	 * @return
	 */
	function get_cart_popup_div()
	{
		$output = '';
		$tpl_content = $GLOBALS['tplEngine']->createTemplate('modules/cart_popup_content.tpl');
		$tpl_content->assign('STR_MODULE_CART_POPUP_CART_POPUP_PRODUCT_ADDED', $GLOBALS['STR_MODULE_CART_POPUP_CART_POPUP_PRODUCT_ADDED']);
		$tpl_content->assign('STR_CADDIE', $GLOBALS['STR_CADDIE']);
		$tpl_content->assign('STR_QUANTITY', $GLOBALS['STR_QUANTITY']);
		$tpl_content->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl_content->assign('STR_AMOUNT', $GLOBALS['STR_AMOUNT']);
		$tpl_content->assign('STR_TTC', $GLOBALS['STR_TTC']);
		$tpl_content->assign('STR_HT', $GLOBALS['STR_HT']);
		$tpl_content->assign('STR_SHOPPING', $GLOBALS['STR_SHOPPING']);
		$tpl_content->assign('count_products', $_SESSION['session_caddie']->count_products());
		$tpl_content->assign('display_prices_with_taxes_active', display_prices_with_taxes_active());
		$tpl_content->assign('total', fprix($_SESSION['session_caddie']->total, true));
		$tpl_content->assign('total_ht', fprix($_SESSION['session_caddie']->total_ht, true));
		$tpl_content->assign('caddie_href', $GLOBALS['wwwroot'] . '/achat/caddie_affichage.php');
		$tpl_content->assign('header_src', $GLOBALS['repertoire_images'].'/popup_cart_top1_'.$_SESSION['session_langue'].'.png');

		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/cart_popup_div.tpl');
		$tpl->assign('width', $GLOBALS['site_parameters']['popup_width']);
		$tpl->assign('height', $GLOBALS['site_parameters']['popup_height']);
		$tpl->assign('html_js_var', str_replace(array("\n", "\r"), '', str_replace('"', '\"', $tpl_content->fetch())));

		$output .= $tpl->fetch();

		return $output;
	}
}
?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 43037 2014-10-29 12:01:40Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

if (!function_exists('get_cart_popup_script')) {
	/**
	 * get_cart_popup_script()
	 *
	 * @return
	 */
	function get_cart_popup_script()
	{
		$output = '';
		return $output;
	}
}

if (!function_exists('get_cart_popup_div')) {
	/**
	 * get_cart_popup_div() Fonction qui crée le tpl de la popup s'affichant après l'ajout au panier
	 * params : l'id du produit ajouter au panier permet de connaitre le type d'affichage de la popup,
	 * tous les produits n'ont pas le même affichage.
	 * @return null
	 */
	function get_cart_popup_div($product_added_id = null)
	{
		$tpl_content = $GLOBALS['tplEngine']->createTemplate('modules/cart_popup_content.tpl');
		$tpl_content->assign('STR_MODULE_CART_POPUP_PRODUCT_ADDED', $GLOBALS['STR_MODULE_CART_POPUP_PRODUCT_ADDED']);
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
		$tpl_content->assign('header_src', $GLOBALS['repertoire_images'].'/popup_cart_top1_'.$_SESSION['session_langue'].'.png');

		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/cart_popup_div.tpl');		
		$tpl->assign('header_src', $GLOBALS['repertoire_images'].'/popup_cart_top1_'.$_SESSION['session_langue'].'.png');		
		$tpl->assign('count_products', $_SESSION['session_caddie']->count_products());
		$tpl->assign('width', $GLOBALS['site_parameters']['popup_width']);
		$tpl->assign('height', $GLOBALS['site_parameters']['popup_height']);
		$tpl->assign('html_var', $tpl_content->fetch());
		$tpl->assign('STR_MODULE_CART_POPUP_PRODUCT_ADDED', $GLOBALS['STR_MODULE_CART_POPUP_PRODUCT_ADDED']);
		$tpl->assign('STR_QUANTITY', $GLOBALS['STR_QUANTITY']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_AMOUNT', $GLOBALS['STR_AMOUNT']);
		$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
		$tpl->assign('STR_HT', $GLOBALS['STR_HT']);
		
		$product_object = new Product($product_added_id);
		if(!empty($GLOBALS['site_parameters']['display_link_after_product_added_in_cart_popup'][$product_object->technical_code])){
			// Ajout d'un 3ème bouton sur la page de popup du panier. Le lien est configurable depuis les paramètres généraux du site et activable produit par produit
			$tpl->assign('product_technical_code', $product_object->technical_code);
			$tpl->assign('link', $GLOBALS['site_parameters']['display_link_after_product_added_in_cart_popup'][$product_object->technical_code]);
			$tpl->assign('label', $GLOBALS['site_parameters']['label_link_after_product_added_in_cart_popup'][$product_object->technical_code]);
		}
			// On verifie si le technical code du produit correspond à un affichage spécial contenu dans "bootbox_dialog_buttons_main_label"
		if(!empty($GLOBALS['site_parameters']['bootbox_dialog_buttons_main_label'][$product_object->technical_code])) {
			// Configuration possible du nom du bouton "Votre panier"
			$tpl->assign('main_label', $GLOBALS['site_parameters']['bootbox_dialog_buttons_main_label'][$product_object->technical_code]);
		} else {
			$tpl->assign('main_label', $GLOBALS['STR_CADDIE']);
		}
		// On verifie si le technical code du produit correspond à un affichage spécial contenu dans "bootbox_dialog_buttons_main_link"
		if(!empty($GLOBALS['site_parameters']['bootbox_dialog_buttons_main_link'][$product_object->technical_code])) {
			// Configuration possible du lien du bouton "Votre panier"
			$tpl->assign('main_href', $GLOBALS['site_parameters']['bootbox_dialog_buttons_main_link'][$product_object->technical_code]);
		} else {
			$tpl->assign('main_href', $GLOBALS['wwwroot'] . '/achat/caddie_affichage.php');
		}
		
		// On verifie si le technical code du produit correspond à un affichage spécial contenu dans "bootbox_dialog_buttons_success_link"
		if(!empty($GLOBALS['site_parameters']['bootbox_dialog_buttons_success_link'][$product_object->technical_code])) {
			// Configuration possible du lien du bouton "Continuer mes achats"
			$tpl->assign('success_href', $GLOBALS['site_parameters']['bootbox_dialog_buttons_success_link'][$product_object->technical_code]);
		}
		
		// On verifie si le technical code du produit correspond à un affichage spécial contenu dans "bootbox_dialog_buttons_success_label"
		if(!empty($GLOBALS['site_parameters']['bootbox_dialog_buttons_success_label'][$product_object->technical_code])) {
			// Configuration possible du nom du bouton "Continuer mes achats"
			$tpl->assign('success_label', $GLOBALS['site_parameters']['bootbox_dialog_buttons_success_label'][$product_object->technical_code]);
		} else {
			$tpl->assign('success_label', $GLOBALS['STR_SHOPPING']);
		}

		// On ne met pas le script en HTML directement, mais on laisse le CMS optimiser sa gestion
		$GLOBALS['js_ready_content_array'][] = $tpl->fetch();
		return null;
	}
}

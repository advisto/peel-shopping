<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: index.php 39162 2013-12-04 10:37:44Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin*");

if (file_exists($GLOBALS['dirroot'] . '/' . $GLOBALS['site_parameters']['backoffice_directory_name'] . '/install.php')) {
	redirect_and_die($GLOBALS['administrer_url'] . '/install.php');
}

if (is_chart_module_active()) {
	if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
		include($GLOBALS['dirroot'] . '/modules/chart/flot.php');
	} else {
		include($GLOBALS['dirroot'] . '/modules/chart/open_flash_chart_object.php');
	}
}

$DOC_TITLE = $GLOBALS['STR_ADMIN_INDEX_TITLE'];
$output = '';

$q = query("SELECT COUNT(*) AS this_count
	FROM peel_produits p ");
$products_count_object = fetch_object($q);

$q = query("SELECT COUNT(*) AS this_count
	FROM peel_utilisateurs u");
$users_count_object = fetch_object($q);

$q = query("SELECT COUNT(*) AS this_count
	FROM peel_commandes
	WHERE id_ecom = '" . intval($GLOBALS['site_parameters']['id']) . "'");
$orders_count_object = fetch_object($q);

$q = query("SELECT COUNT(*) AS this_count
	FROM peel_commandes
	WHERE id_statut_paiement IN ('2','3') AND id_statut_livraison <> '3' AND id_ecom = '" . intval($GLOBALS['site_parameters']['id']) . "'");
$paid_orders_to_deliver_count_object = fetch_object($q);

$q = query("SELECT COUNT(*) AS this_count
	FROM peel_commandes
	WHERE id_statut_paiement IN ('2','3') AND id_statut_livraison = '3' AND id_ecom = '" . intval($GLOBALS['site_parameters']['id']) . "'");
$paid_orders_delivered_count_object = fetch_object($q);

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_index.tpl');
if (is_phone_cti_module_active() && file_exists($GLOBALS['dirroot'] . '/modules/multisite/include.php')) {
	require_once($dirroot . '/modules/multisite/include.php');
	require_once($dirroot . '/modules/multisite/fonctions.php');
	require_once($dirroot . '/modules/accounting/administrer/fonctions.php');
	$tpl->assign('KeyyoCalls', getKeyyoCalls(true));
}
$tpl->assign('orders', (a_priv('admin_sales', true) ? backoffice_home_block('orders', 'green', true) : ''));
$tpl->assign('sales', (a_priv('admin_sales', true) ? backoffice_home_block('sales', 'blue', true) : ''));
$tpl->assign('products', (a_priv('admin_products', true) ? backoffice_home_block('products', 'orange', true) : ''));
$tpl->assign('delivery', (a_priv('admin_sales', true) ? backoffice_home_block('delivery', 'purple', true) : ''));
$tpl->assign('users', (a_priv('admin_users', true) ? backoffice_home_block('users', 'red', true) : ''));
$tpl->assign('peel', backoffice_home_block('peel', 'black', true));
$tpl->assign('data_lang', get_data_lang());
$tpl->assign('sortie_href', $GLOBALS['wwwroot'] . '/sortie.php');
$tpl->assign('example_href', $GLOBALS['wwwroot'] . '/import/exemple_prod.csv');
$tpl->assign('STR_ADMIN_INDEX_SECURITY_WARNING', $GLOBALS['STR_ADMIN_INDEX_SECURITY_WARNING']);
$output .= $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * backoffice_home_block()
 *
 * @param mixed $content_code
 * @param mixed $title_bg_color
 * @param boolean $return_mode
 * @return
 */
function backoffice_home_block($content_code, $title_bg_color, $return_mode = false)
{
	$block_content = get_home_block_content($content_code);

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_backoffice_home_block.tpl');
	$tpl->assign('bg_src', $GLOBALS['repertoire_images'] .'/'. get_block_header_image(String::strtolower($title_bg_color)));
	$tpl->assign('title_bg_color', $title_bg_color);
	$tpl->assign('link', $block_content['link']);
	$tpl->assign('title', $block_content['title']);
	$tpl->assign('logo', $block_content['logo']);
	$tpl->assign('description1', vb($block_content['description1']));
	$tpl->assign('description2', vb($block_content['description2']));

	if ($return_mode) {
		return $tpl->fetch();
	} else {
		echo $tpl->fetch();
	}
}

/**
 * get_block_header_image()
 *
 * @param mixed $title_bg_color
 * @return
 */
function get_block_header_image($title_bg_color)
{
	if (!in_array($title_bg_color, array('green', 'blue', 'orange', 'purple', 'red'))) {
		$title_bg_color = 'gray';
	}
	return 'block_header_' . $title_bg_color . '.jpg';
}

/**
 * get_home_block_content()
 *
 * @param mixed $content_code
 * @return
 */
function get_home_block_content($content_code)
{
	$block_content = array();
	switch ($content_code) {
		case 'orders':
			$block_content['title'] = $GLOBALS['STR_ADMIN_INDEX_ORDERS_LIST'];
			$block_content['logo'] = $GLOBALS['repertoire_images'] . '/orders.jpg';
			$block_content['link'] = $GLOBALS['administrer_url'] . '/commander.php';
			
			$tpl1 = $GLOBALS['tplEngine']->createTemplate('admin_home_orders_desc1.tpl');
			$tpl1->assign('src', $GLOBALS['repertoire_images'] . '/arrow_right.jpg');
			$tpl1->assign('link', $block_content['link']);
			$tpl1->assign('STR_ADMIN_INDEX_ORDERS_DESC1', $GLOBALS['STR_ADMIN_INDEX_ORDERS_DESC1']);
			$tpl1->assign('STR_ADMIN_INDEX_SHOW_ORDERS', $GLOBALS['STR_ADMIN_INDEX_SHOW_ORDERS']);
			$block_content['description1'] = $tpl1->fetch();

			$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_home_orders_desc2.tpl');
			$tpl_results = array();
			$qid = query("SELECT *
				FROM peel_commandes
				WHERE id_ecom = '" . intval($GLOBALS['site_parameters']['id']) . "'
				ORDER BY id DESC
				LIMIT 0,5");
			$i = 0;

			while ($r = fetch_object($qid)) {
				if (is_fianet_sac_module_active()) {
					require_once($GLOBALS['fonctionsfianet_sac']);
					// Même si la fonction get_sac_status permet de passer un tableau d'id de commande en paramètre, l'appel de la fonction ce fait ici pour des raisons
					// de simplicité pour le moment. Une amélioration possible est d'appeler la fonction avant le while.
					$get_sac_status = get_sac_status($r->id, false);
					$this_sac_status = $get_sac_status[$order['order_id']];
				}
				if (display_prices_with_taxes_in_admin()) {
					$montant_displayed = $r->montant;
				} else {
					$montant_displayed = $r->montant_ht;
				}
				$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, 'cursor:pointer', 'document.location=\'' . $GLOBALS['administrer_url'] . '/commander.php?mode=modif&amp;commandeid=' . $r->id . '\''),
					'id' => $r->id,
					'nom_bill' => $r->nom_bill,
					'date' => get_formatted_date($r->o_timestamp, 'short', true),
					'prix' => str_replace(' ', '&nbsp;', fprix($montant_displayed, true, $r->devise, true, $r->currency_rate)) . '<br />' . String::str_shorten(get_payment_name($r->paiement), 30, '', '...'),
					'statut_paiement' => get_payment_status_name($r->id_statut_paiement),
					'this_sac_status' => vb($this_sac_status)
					);
				$i++;
			}
			$tpl2->assign('results', $tpl_results);
			$tpl2->assign('ttc_ht', (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));
			$tpl2->assign('is_fianet_sac_module_active', is_fianet_sac_module_active());
			$tpl2->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl2->assign('STR_ADMIN_INDEX_ORDERS_DESC2', $GLOBALS['STR_ADMIN_INDEX_ORDERS_DESC2']);
			$tpl2->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
			$tpl2->assign('STR_DATE', $GLOBALS['STR_DATE']);
			$tpl2->assign('STR_TOTAL', $GLOBALS['STR_TOTAL']);
			$tpl2->assign('STR_PAYMENT', $GLOBALS['STR_PAYMENT']);
			$tpl2->assign('STR_ADMIN_INDEX_FIANET_VALIDATION', $GLOBALS['STR_ADMIN_INDEX_FIANET_VALIDATION']);
			$tpl2->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
			$block_content['description2'] = $tpl2->fetch();
			break;

		case 'sales':
			$block_content['title'] = $GLOBALS['STR_ADMIN_INDEX_SALES_REPORT'];
			$block_content['logo'] = $GLOBALS['repertoire_images'] . '/sales.jpg';
			$block_content['link'] = $GLOBALS['administrer_url'] . '/ventes.php';
			
			$tpl1 = $GLOBALS['tplEngine']->createTemplate('admin_home_sales_desc1.tpl');
			$tpl1->assign('src', $GLOBALS['repertoire_images'] . '/arrow_right.jpg');
			$tpl1->assign('link', $block_content['link']);
			$tpl1->assign('STR_ADMIN_INDEX_SALES_DESC1', $GLOBALS["STR_ADMIN_INDEX_SALES_DESC1"]);
			$tpl1->assign('STR_ADMIN_INDEX_SALES_LINK', $GLOBALS["STR_ADMIN_INDEX_SALES_LINK"]);
			$block_content['description1'] = $tpl1->fetch();

			if (is_chart_module_active()) {
				$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_home_sales_desc2.tpl');
				if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
					$tpl2->assign('flash_chart', get_flot_chart('100%', 190, $GLOBALS['administrer_url'] . '/chart-data.php?type=sales&date1=' . date('Y-m-d', time() - 3600 * 24 * 30) . '&date2=' . date('Y-m-d', time()) . '&width=288', 'bar', $GLOBALS['wwwroot'] . '/modules/chart/', 'date_format_veryshort'));
				} else {
					$tpl2->assign('flash_chart', open_flash_chart_object_str('100%', 190, $GLOBALS['administrer_url'] . '/chart-data.php?type=sales&date1=' . date('Y-m-d', time() - 3600 * 24 * 30) . '&date2=' . date('Y-m-d', time()) . '&width=288', true, $GLOBALS['wwwroot'] . '/modules/chart/'));
				}
				$tpl2->assign('STR_ADMIN_INDEX_LAST_SALES', $GLOBALS['STR_ADMIN_INDEX_LAST_SALES']);
				$tpl2->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
				$block_content['description2'] = $tpl2->fetch();
			}
			break;

		case 'products':
			$block_content['title'] = $GLOBALS['STR_ADMIN_INDEX_PRODUCTS_REPORT'];
			$block_content['logo'] = $GLOBALS['repertoire_images'] . '/products.jpg';
			$block_content['link'] = $GLOBALS['administrer_url'] . '/produits.php';
			
			$tpl1 = $GLOBALS['tplEngine']->createTemplate('admin_home_products_desc1.tpl');
			$tpl1->assign('src', $GLOBALS['repertoire_images'] . '/arrow_right.jpg');
			$tpl1->assign('link', $block_content['link']);
			$tpl1->assign('STR_ADMIN_INDEX_PRODUCTS_REPORT', $GLOBALS["STR_ADMIN_INDEX_PRODUCTS_REPORT"]);
			$tpl1->assign('STR_ADMIN_INDEX_PRODUCTS_DESC1', $GLOBALS["STR_ADMIN_INDEX_PRODUCTS_DESC1"]);
			$block_content['description1'] = $tpl1->fetch();
			if (is_chart_module_active()) {
				$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_home_products_desc2.tpl');
				if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
					$tpl2->assign('flash_chart', get_flot_chart('100%', 190, $GLOBALS['administrer_url'] . '/chart-data.php?type=product-categories&date1=' . date('Y-m-d', time() - 3600 * 24 * 7) . '&date2=' . date('Y-m-d', time()) . '&width=288', 'pie', $GLOBALS['wwwroot'] . '/modules/chart/'));
				} else {
					$tpl2->assign('flash_chart', open_flash_chart_object_str('100%', 190, $GLOBALS['administrer_url'] . '/chart-data.php?type=product-categories&date1=' . date('Y-m-d', time() - 3600 * 24 * 7) . '&date2=' . date('Y-m-d', time()) . '&width=288', true, $GLOBALS['wwwroot'] . '/modules/chart/'));
				}
				$tpl2->assign('STR_ADMIN_PRODUCTS_CATEGORY', $GLOBALS['STR_ADMIN_PRODUCTS_CATEGORY']);
				$tpl2->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
				$block_content['description2'] = $tpl2->fetch();
			}
			break;

		case 'delivery':
			$block_content['title'] = $GLOBALS['STR_ADMIN_INDEX_DELIVERY_REPORT'];
			$block_content['logo'] = $GLOBALS['repertoire_images'] . '/delivery.jpg';
			$block_content['link'] = $GLOBALS['administrer_url'] . '/livraisons.php';
			
			$tpl1 = $GLOBALS['tplEngine']->createTemplate('admin_home_delivery_desc1.tpl');
			$tpl1->assign('src', $GLOBALS['repertoire_images'] . '/arrow_right.jpg');
			$tpl1->assign('link', $block_content['link']);
			$tpl1->assign('STR_ADMIN_INDEX_DELIVERY_DESC1', $GLOBALS['STR_ADMIN_INDEX_DELIVERY_DESC1']);
			$tpl1->assign('STR_ADMIN_INDEX_GENERATE_REPORT', $GLOBALS['STR_ADMIN_INDEX_GENERATE_REPORT']);
			$block_content['description1'] = $tpl1->fetch();

			$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_home_delivery_desc2.tpl');
			$tpl_results = array();
			$qid = query("SELECT *
				FROM peel_commandes
				WHERE id_ecom = '" . intval($GLOBALS['site_parameters']['id']) . "' AND id_statut_paiement IN ('2','3') AND id_statut_livraison!=3
				ORDER BY id DESC
				LIMIT 0,5");
			$i = 0;
			while ($r = fetch_object($qid)) {
				$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, 'cursor:pointer', 'document.location=\'' . $GLOBALS['administrer_url'] . '/commander.php?mode=modif&amp;commandeid=' . $r->id . '\''),
					'id' => $r->id,
					'nom_bill' => $r->nom_bill,
					'date' => get_formatted_date($r->o_timestamp, 'short', true),
					'prix' => str_replace(' ', '&nbsp;', fprix($r->montant, true, $r->devise, true, $r->currency_rate)) . '<br />' . String::str_shorten(get_payment_name($r->paiement), 30, '', '...'),
					'statut_paiement' => get_delivery_status_name($r->id_statut_livraison)
					);
				$i++;
			}
			$tpl2->assign('results', $tpl_results);
			$tpl2->assign('STR_ADMIN_INDEX_DELIVERY_DESC2', $GLOBALS['STR_ADMIN_INDEX_DELIVERY_DESC2']);
			$tpl2->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl2->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
			$tpl2->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
			$tpl2->assign('STR_DATE', $GLOBALS['STR_DATE']);
			$tpl2->assign('STR_TOTAL', $GLOBALS['STR_TOTAL']);
			$tpl2->assign('STR_TTC', $GLOBALS['STR_TTC']);
			$tpl2->assign('STR_DELIVERY', $GLOBALS['STR_DELIVERY']);
			$block_content['description2'] = $tpl2->fetch();
			break;

		case 'users':
			$block_content['title'] = $GLOBALS['STR_ADMIN_INDEX_USERS_LIST'];
			$block_content['logo'] = $GLOBALS['repertoire_images'] . '/users.jpg';
			$block_content['link'] = $GLOBALS['administrer_url'] . '/utilisateurs.php';
			$tpl1 = $GLOBALS['tplEngine']->createTemplate('admin_home_users_desc1.tpl');
			$tpl1->assign('src', $GLOBALS['repertoire_images'] . '/arrow_right.jpg');
			$tpl1->assign('link', $block_content['link']);
			$tpl1->assign('STR_ADMIN_INDEX_USERS_DESC1', $GLOBALS["STR_ADMIN_INDEX_USERS_DESC1"]);
			$tpl1->assign('STR_ADMIN_INDEX_USERS_LINK', $GLOBALS["STR_ADMIN_INDEX_USERS_LINK"]);
			$block_content['description1'] = $tpl1->fetch();
			if (is_chart_module_active()) {
				$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_home_users_desc2.tpl');
				if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
					$tpl2->assign('flash_chart', get_flot_chart('100%', 170, $GLOBALS['administrer_url'] . '/chart-data.php?type=users-count&date1=' . date('Y-m-d', time() - 3600 * 24 * 30) . '&date2=' . date('Y-m-d', time()) . '&width=288', 'line', $GLOBALS['wwwroot'] . '/modules/chart/', 'date_format_veryshort'));
				} else {
					$tpl2->assign('flash_chart', open_flash_chart_object_str('100%', 170, $GLOBALS['administrer_url'] . '/chart-data.php?type=users-count&date1=' . date('Y-m-d', time() - 3600 * 24 * 30) . '&date2=' . date('Y-m-d', time()) . '&width=288', true, $GLOBALS['wwwroot'] . '/modules/chart/'));
				}
				$tpl2->assign('STR_ADMIN_INDEX_LAST_USERS', $GLOBALS['STR_ADMIN_INDEX_LAST_USERS']);
				$tpl2->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
				$block_content['description2'] = $tpl2->fetch();
			}
			break;

		case 'peel':
			$block_content['title'] = $GLOBALS['STR_ADMIN_INDEX_PEEL_BLOCK_TITLE'];
			$block_content['logo'] = '';
			$block_content['link'] = 'https://www.peel.fr/';
			$tpl1 = $GLOBALS['tplEngine']->createTemplate('admin_home_peel_desc1.tpl');
			$tpl1->assign('src', $GLOBALS['repertoire_images'] . '/arrow_right.jpg');
			$tpl1->assign('STR_ADMIN_INDEX_PEEL_DESC1', $GLOBALS['STR_ADMIN_INDEX_PEEL_DESC1']);
			$tpl1->assign('STR_ADMIN_INDEX_PEEL_LAST_OFFERS', $GLOBALS['STR_ADMIN_INDEX_PEEL_LAST_OFFERS']);
			$tpl1->assign('last_offers_href', 'https://www.peel.fr/solution-e-commerce-1/peel-premium-1.html');
			$tpl1->assign('STR_ADMIN_INDEX_CUSTOM_MODULES', $GLOBALS['STR_ADMIN_INDEX_CUSTOM_MODULES']);
			$tpl1->assign('custom_modules_href', 'https://www.peel.fr/achat/modules-a-la-carte-4.html');
			$tpl1->assign('STR_ADMIN_CONTACT_US', $GLOBALS['STR_ADMIN_CONTACT_US']);
			$tpl1->assign('contact_us_href', 'https://www.peel.fr/utilisateurs/contact.php');
			$block_content['description1'] = $tpl1->fetch();

			$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_home_peel_desc2.tpl');
			$tpl2->assign('link', $block_content['link']);
			$tpl2->assign('src', $GLOBALS['repertoire_images'] . '/peel.jpg');
			$block_content['description2'] = $tpl2->fetch();
			break;
		default:
			$block_content = null;
			break;
	}
	return $block_content;
}

?>
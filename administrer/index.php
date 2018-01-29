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
// $Id: index.php 55371 2017-12-04 14:43:39Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin*");

if (file_exists($GLOBALS['dirroot'] . '/' . $GLOBALS['site_parameters']['backoffice_directory_name'] . '/install.php')) {
	redirect_and_die($GLOBALS['administrer_url'] . '/install.php');
}

if (check_if_module_active('chart', 'open-flash-chart.php')) {
	if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
		include($GLOBALS['dirroot'] . '/modules/chart/flot.php');
	} else {
		include($GLOBALS['dirroot'] . '/modules/chart/open_flash_chart_object.php');
	}
}

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_INDEX_TITLE'] . ' ' . $GLOBALS['site'];
$output = '';

if (isset($_POST['admin_multisite'])){
	// Détermine le site à administrer. Cette valeur en session est utilisée dans la fonction get_filter_site_cond. $_POST['admin_multisite'] est éventuellement égal à 0.
	if($_SESSION['session_utilisateur']['site_id'] == 0 || $_POST['admin_multisite'] == $_SESSION['session_utilisateur']['site_id']) {
		$_SESSION['session_admin_multisite'] = intval($_POST['admin_multisite']);
	}
}
$q = query("SELECT COUNT(*) AS this_count
	FROM peel_produits p
	WHERE " . get_filter_site_cond('produits', 'p') . "");
$products_count_object = fetch_object($q);

$q = query("SELECT COUNT(*) AS this_count
	FROM peel_utilisateurs u
	WHERE " . get_filter_site_cond('utilisateurs', 'u') . "");
$users_count_object = fetch_object($q);

$q = query("SELECT COUNT(*) AS this_count
	FROM peel_commandes
	WHERE " . get_filter_site_cond('commandes', null, true) . "");
$orders_count_object = fetch_object($q);

$q = query("SELECT COUNT(*) AS this_count
	FROM peel_commandes c
	LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
	LEFT JOIN peel_statut_livraison sl ON sl.id=c.id_statut_livraison AND " . get_filter_site_cond('statut_livraison', 'sl') . "
	WHERE sp.technical_code IN ('being_checked','completed') AND sl.technical_code <> 'dispatched' AND " . get_filter_site_cond('commandes', 'c', true) . "");
$paid_orders_to_deliver_count_object = fetch_object($q);

$q = query("SELECT COUNT(*) AS this_count
	FROM peel_commandes c
	LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
	LEFT JOIN peel_statut_livraison sl ON sl.id=c.id_statut_livraison AND " . get_filter_site_cond('statut_livraison', 'sl') . "
	WHERE sp.technical_code IN ('being_checked','completed') AND sl.technical_code = 'dispatched' AND " . get_filter_site_cond('commandes', 'c', true) . "");
$paid_orders_delivered_count_object = fetch_object($q);

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_index.tpl');

$tpl->assign('site_id_select_options', get_site_id_select_options(vb($_SESSION['session_admin_multisite'])));
$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
	
if (check_if_module_active('phone_cti')) {
	// Il y a deux types de sites avec le module phone_cti :
	// - les sites qui historisent les appels (dans la table peel_calls) 
	// - les sites avec la seule fonctionnalité de mise en forme des numéros de téléphone sur les fiches utilisateurs. Ces sites n'ont pas la table peel_calls, mais le module phone_cti est quand même actif.
	$listTables = listTables();
	if (in_array('peel_calls', $listTables)) {
		$block_content = array();
		$block_content['title'] = 'Liste des Appels';
		$block_content['logo'] = '';
		$block_content['link'] = $GLOBALS['wwwroot_in_admin'] . '/modules/phone_cti/administrer/list_calls.php';
		$block_content['description1'] = getKeyyoCalls(true);
		$block_content['description2'] = '';
		$tpl->assign('KeyyoCalls', backoffice_home_block($block_content, 'red', true));
	}
}
$delivery = '';
if(a_priv('admin_sales', true)) {
	$home_modules['orders'] = backoffice_home_block('orders', 'green', true);
	$home_modules['sales'] = backoffice_home_block('sales', 'blue', true);
}
if(a_priv('admin_products', true)) {
	$home_modules['products'] = backoffice_home_block('products', 'orange', true);
}
if(a_priv('admin_sales', true)) {
	if(!empty($GLOBALS['site_parameters']['mode_transport'])) {
		$home_modules['delivery'] = backoffice_home_block('delivery', 'purple', true);	
	}	
}
if(a_priv('admin_users', true)) {
	$home_modules['users'] = backoffice_home_block('users', 'red', true);
}
$home_modules['peel'] = backoffice_home_block('peel', 'black', true);
// On transmet les blocs par défaut en paramètre du hook, pour pouvoir les retirer ou en rajouter d'autres, ou même changer l'ordre dans le tableau

$home_modules = call_module_hook('get_admin_home_block', $home_modules, 'array', true);
if (!empty($GLOBALS['site_parameters']['admin_home_block_display_disable'])) {
	foreach ($GLOBALS['site_parameters']['admin_home_block_display_disable'] as $this_home_block) {
		unset($home_modules[$this_home_block]);
	}
}
if(vb($GLOBALS['site_parameters']['peel_database_version']) != PEEL_VERSION) {
	$tpl->assign('version_update_link', $GLOBALS['administrer_url'] . '/update.php');
	$tpl->assign('STR_ADMIN_UPDATE_VERSION_INVITE', $GLOBALS['STR_ADMIN_UPDATE_VERSION_INVITE']);
}
$tpl->assign('all_sites_name_array', get_all_sites_name_array(true));
$tpl->assign('home_modules', $home_modules);
$tpl->assign('link', $GLOBALS['administrer_url'] . '/sites.php');
$tpl->assign('data_lang', get_data_lang());
$tpl->assign('current_url', get_current_url());
$tpl->assign('sortie_href', $GLOBALS['wwwroot_in_admin'] . '/sortie.php');
$tpl->assign('example_href', $GLOBALS['wwwroot_in_admin'] . '/import/exemple_prod.csv');
$tpl->assign('STR_ADMIN_INDEX_SECURITY_WARNING', $GLOBALS['STR_ADMIN_INDEX_SECURITY_WARNING']);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_CHOOSE_SITE_TO_MODIFY', $GLOBALS['STR_ADMIN_CHOOSE_SITE_TO_MODIFY']);
$output .= $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
if (vb($_GET['error']) == 'admin_rights') {
	echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_NO_RIGHTS_TO_ACCESS_ADMIN']))->fetch();
}
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * backoffice_home_block()
 *
 * @param mixed $block_content
 * @param mixed $title_bg_color
 * @param boolean $return_mode
 * @return
 */
function backoffice_home_block($block_content, $title_bg_color, $return_mode = false)
{
	if(!is_array($block_content)) {
		$block_content = get_home_block_content($block_content);
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_backoffice_home_block.tpl');
	$tpl->assign('bg_src', $GLOBALS['repertoire_images'] .'/'. get_block_header_image(StringMb::strtolower($title_bg_color)));
	$tpl->assign('title_bg_color', $title_bg_color);
	$tpl->assign('link', vb($block_content['link']));
	$tpl->assign('title', vb($block_content['title']));
	$tpl->assign('logo', vb($block_content['logo']));
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
				WHERE " . get_filter_site_cond('commandes', null, true) . "
				ORDER BY id DESC
				LIMIT 0,5");
			$i = 0;

			while ($r = fetch_object($qid)) {
				if (check_if_module_active('fianet')) {
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
					'id' => $r->order_id,
					'nom_bill' => $r->nom_bill,
					'date' => get_formatted_date($r->o_timestamp, 'short', true),
					'prix' => str_replace(' ', '&nbsp;', fprix($montant_displayed, true, $r->devise, true, $r->currency_rate)) . '<br />' . StringMb::str_shorten(get_payment_name($r->paiement), 30, '', '...'),
					'statut_paiement' => get_payment_status_name($r->id_statut_paiement),
					'this_sac_status' => vb($this_sac_status)
					);
				$i++;
			}
			$tpl2->assign('results', $tpl_results);
			$tpl2->assign('ttc_ht', (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));
			$tpl2->assign('is_fianet_sac_module_active', check_if_module_active('fianet_sac'));
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

			if (check_if_module_active('chart', 'open-flash-chart.php')) {
				$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_home_sales_desc2.tpl');
				if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
					$tpl2->assign('flash_chart', get_flot_chart('100%', 190, $GLOBALS['administrer_url'] . '/chart-data.php?type=sales&date1=' . date('Y-m-d', time() - 3600 * 24 * 30) . '&date2=' . date('Y-m-d', time()) . '&width=288', 'bar', $GLOBALS['wwwroot_in_admin'] . '/modules/chart/', 'date_format_veryshort'));
				} else {
					$tpl2->assign('flash_chart', open_flash_chart_object_str('100%', 190, $GLOBALS['administrer_url'] . '/chart-data.php?type=sales&date1=' . date('Y-m-d', time() - 3600 * 24 * 30) . '&date2=' . date('Y-m-d', time()) . '&width=288', true, $GLOBALS['wwwroot_in_admin'] . '/modules/chart/'));
				}
				$tpl2->assign('STR_ADMIN_INDEX_LAST_SALES', $GLOBALS['STR_ADMIN_INDEX_LAST_SALES']);
				$tpl2->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
				$block_content['description2'] = $tpl2->fetch();
			}
			break;

		case 'products':
			if(!empty($GLOBALS['site_parameters']['admin_home_skip_product_categories_stats'])) {
				break;
			}
			$block_content['title'] = $GLOBALS['STR_ADMIN_INDEX_PRODUCTS_REPORT'];
			$block_content['logo'] = $GLOBALS['repertoire_images'] . '/products.jpg';
			$block_content['link'] = $GLOBALS['administrer_url'] . '/produits.php';
			
			$tpl1 = $GLOBALS['tplEngine']->createTemplate('admin_home_products_desc1.tpl');
			$tpl1->assign('src', $GLOBALS['repertoire_images'] . '/arrow_right.jpg');
			$tpl1->assign('link', $block_content['link']);
			$tpl1->assign('STR_ADMIN_INDEX_PRODUCTS_REPORT', $GLOBALS["STR_ADMIN_INDEX_PRODUCTS_REPORT"]);
			$tpl1->assign('STR_ADMIN_INDEX_PRODUCTS_DESC1', $GLOBALS["STR_ADMIN_INDEX_PRODUCTS_DESC1"]);
			$block_content['description1'] = $tpl1->fetch();
			if (check_if_module_active('chart', 'open-flash-chart.php')) {
				$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_home_products_desc2.tpl');
				if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
					$tpl2->assign('flash_chart', get_flot_chart('100%', 190, $GLOBALS['administrer_url'] . '/chart-data.php?type=product-categories&date1=' . date('Y-m-d', time() - 3600 * 24 * 7) . '&date2=' . date('Y-m-d', time()) . '&width=288', 'pie', $GLOBALS['wwwroot_in_admin'] . '/modules/chart/'));
				} else {
					$tpl2->assign('flash_chart', open_flash_chart_object_str('100%', 190, $GLOBALS['administrer_url'] . '/chart-data.php?type=product-categories&date1=' . date('Y-m-d', time() - 3600 * 24 * 7) . '&date2=' . date('Y-m-d', time()) . '&width=288', true, $GLOBALS['wwwroot_in_admin'] . '/modules/chart/'));
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
			$qid = query("SELECT c.*
				FROM peel_commandes c
				LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
				LEFT JOIN peel_statut_livraison sl ON sl.id=c.id_statut_livraison AND " . get_filter_site_cond('statut_livraison', 'sl') . "
				WHERE " . get_filter_site_cond('commandes', 'c', true) . " AND sp.technical_code IN ('being_checked','completed') AND sl.technical_code!='dispatched'
				ORDER BY c.id DESC
				LIMIT 0,5");
			$i = 0;
			while ($r = fetch_object($qid)) {
				$tpl_results[] = array('tr_rollover' => tr_rollover($i, true, 'cursor:pointer', 'document.location=\'' . $GLOBALS['administrer_url'] . '/commander.php?mode=modif&amp;commandeid=' . $r->id . '\''),
					'id' => $r->id,
					'nom_bill' => $r->nom_bill,
					'date' => get_formatted_date($r->o_timestamp, 'short', true),
					'prix' => str_replace(' ', '&nbsp;', fprix($r->montant, true, $r->devise, true, $r->currency_rate)) . '<br />' . StringMb::str_shorten(get_payment_name($r->paiement), 30, '', '...'),
					'statut_livraison' => get_delivery_status_name($r->id_statut_livraison)
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
			if (check_if_module_active('chart', 'open-flash-chart.php')) {
				$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_home_users_desc2.tpl');
				if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
					$tpl2->assign('flash_chart', get_flot_chart('100%', 170, $GLOBALS['administrer_url'] . '/chart-data.php?type=users-count&date1=' . date('Y-m-d', time() - 3600 * 24 * 30) . '&date2=' . date('Y-m-d', time()) . '&width=288', 'line', $GLOBALS['wwwroot_in_admin'] . '/modules/chart/', 'date_format_veryshort'));
				} else {
					$tpl2->assign('flash_chart', open_flash_chart_object_str('100%', 170, $GLOBALS['administrer_url'] . '/chart-data.php?type=users-count&date1=' . date('Y-m-d', time() - 3600 * 24 * 30) . '&date2=' . date('Y-m-d', time()) . '&width=288', true, $GLOBALS['wwwroot_in_admin'] . '/modules/chart/'));
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


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
// $Id: commande_liste.php 35805 2013-03-10 20:43:50Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_commande_liste.tpl');
$tpl->assign('links_nbRecord', vn($Links->nbRecord));
$tpl->assign('action', get_current_url(false));
$tpl->assign('id', vb($_GET['id']));
$tpl->assign('client_info', vb($_GET['client_info']));
$tpl->assign('searchProd', vb($_GET['searchProd']));
$tpl->assign('payment_status_options', get_payment_status_options(vb($_GET['statut_paiement'])));
$tpl->assign('delivery_status_options', get_delivery_status_options(vb($_GET['statut_livraison'])));

$tpl->assign('action2', get_current_url(false) . '?mode=maj_statut');
$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF']));
$tpl->assign('is_fianet_sac_module_active', is_fianet_sac_module_active());

if (!empty($results_array)) {
	$tpl_results = array();

	$HeaderTitlesArray = array($GLOBALS['STR_ADMIN_ACTION'], 'id' => $GLOBALS['STR_ADMIN_ID'], 'numero' => $GLOBALS["STR_ADMIN_COMMANDER_BILL_NUMBER"], 'o_timestamp' => $GLOBALS['STR_DATE'], 'montant' => $GLOBALS['STR_TOTAL'] . ' ' . (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']), $GLOBALS['STR_AVOIR'], 'id_utilisateur' => $GLOBALS['STR_CUSTOMER'], $GLOBALS['STR_PAYMENT'], $GLOBALS['STR_PAYMENT'], 'id_statut_paiement' => $GLOBALS['STR_PAYMENT'], 'id_statut_livraison' => $GLOBALS['STR_DELIVERY']);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$tpl->assign('update_src', $GLOBALS['wwwroot_in_admin'] . '/images/update-on.png');
	$tpl->assign('links_header_row', $Links->getHeaderRow());

	$i = 0;
	foreach ($results_array as $order) {
		$this_sac_status = null;
		if (is_fianet_sac_module_active()) {
			require_once($GLOBALS['fonctionsfianet_sac']);
			// Même si la fonction get_sac_status permet de passer un tableau d'id de commande en paramètre, l'appel de la fonction ce fait ici pour des raisons 
			// de simplicité pour le moment. Une amélioration possible est d'appeler la fonction avant le foreach. Il faut pour cela récupérer 
			// les id de commandes du tableau $results_array.
			$get_sac_status = get_sac_status($order['order_id'], vb($_POST['fianet_sac_update_status']));
			$this_sac_status = $get_sac_status[$order['order_id']];
		}
		if ($affiliated_user = get_user_information($order['id_utilisateur'])) {
			$modifUser = '' . (!checkUserInfo($order, $affiliated_user) ? '<img src="' . $GLOBALS['wwwroot_in_admin'] . '/images/update-on.png" alt="update-on.png" />' : '') . '<a href="utilisateurs.php?mode=modif&id_utilisateur=' . $affiliated_user['id_utilisateur'] . '">' . $affiliated_user['civilite'] . ' ' . $affiliated_user['prenom'] . ' ' . $affiliated_user['nom_famille'] . ' <br />' . $affiliated_user['societe'] . '</a>';
		} else {
			$modifUser = $order['prenom_bill'] . ' ' . $order['nom_bill'] . ' ' . $order['societe_bill'];
			if (!a_priv('demo') && !empty($order['id_utilisateur'])) {
				// Si l'utilisateur est avec droits de démo, les utilisateurs admin ne sont pas trouvés, ce qui ne veut pas dire qu'ils sont supprimés
				$modifUser .= '<br />(supprimé depuis)';
			}
		}
		if(trim(strip_tags($modifUser)) == '') {
			$modifUser = $order['email'];
		}
		if (display_prices_with_taxes_in_admin()) {
			$montant_displayed = $order['montant'];
		} else {
			$montant_displayed = $order['montant_ht'];
		}
		$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
			'order_id' => $order['order_id'],
			'numero' => $order['numero'],
			'date' => get_formatted_date($order['o_timestamp']),
			'montant_prix' => fprix($montant_displayed, true, $order['devise'], true, $order['currency_rate']),
			'avoir_prix' => fprix($order['avoir'], true, $order['devise'], true, $order['currency_rate']),
			'modifUser' => $modifUser,
			'payment_name' => get_payment_name($order['paiement']),
			'payment_status_name' => get_payment_status_name($order['id_statut_paiement']),
			'delivery_status_name' => get_delivery_status_name($order['id_statut_livraison']),
			'this_sac_status' => $this_sac_status
			);
		$i++;
	}
	$tpl->assign('results', $tpl_results);

	$tpl->assign('payment_status_options2', get_payment_status_options());
	$tpl->assign('delivery_status_options2', get_delivery_status_options());
	$tpl->assign('links_multipage', $Links->GetMultipage());
}
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_CHECK_ALL', $GLOBALS['STR_ADMIN_CHECK_ALL']);
$tpl->assign('STR_ADMIN_UNCHECK_ALL', $GLOBALS['STR_ADMIN_UNCHECK_ALL']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDERS_FOUND_COUNT', $GLOBALS['STR_ADMIN_COMMANDER_ORDERS_FOUND_COUNT']);
$tpl->assign('STR_ORDER_NUMBER', $GLOBALS['STR_ORDER_NUMBER']);
$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDERED_PRODUCT', $GLOBALS['STR_ADMIN_COMMANDER_ORDERED_PRODUCT']);
$tpl->assign('STR_ORDER_STATUT_PAIEMENT', $GLOBALS['STR_ORDER_STATUT_PAIEMENT']);
$tpl->assign('STR_ORDER_STATUT_LIVRAISON', $GLOBALS['STR_ORDER_STATUT_LIVRAISON']);
$tpl->assign('STR_ADMIN_ALL_ORDERS', $GLOBALS['STR_ADMIN_ALL_ORDERS']);
$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
$tpl->assign('STR_ADMIN_COMMANDER_FIANET_UPDATE', $GLOBALS['STR_ADMIN_COMMANDER_FIANET_UPDATE']);
$tpl->assign('STR_ADMIN_COMMANDER_CLIENT_UPDATED_ICON_EXPLAIN', $GLOBALS['STR_ADMIN_COMMANDER_CLIENT_UPDATED_ICON_EXPLAIN']);
$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
$tpl->assign('STR_ADMIN_CHECK_ALL', $GLOBALS['STR_ADMIN_CHECK_ALL']);
$tpl->assign('STR_ADMIN_UNCHECK_ALL', $GLOBALS['STR_ADMIN_UNCHECK_ALL']);
$tpl->assign('STR_ADMIN_COMMANDER_UPDATED_STATUS_FOR_SELECTION', $GLOBALS['STR_ADMIN_COMMANDER_UPDATED_STATUS_FOR_SELECTION']);
$tpl->assign('STR_ADMIN_COMMANDER_NO_ORDER_FOUND', $GLOBALS['STR_ADMIN_COMMANDER_NO_ORDER_FOUND']);

echo $tpl->fetch();

?>
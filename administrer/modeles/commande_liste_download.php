<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: commande_liste_download.php 36232 2013-04-05 13:16:01Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}
$tpl = $GLOBALS['tplEngine']->createTemplate('admin_commande_liste_download.tpl');
$sql = "SELECT *
	FROM peel_download
	WHERE etat = '0' AND on_delete = '0'";
$res = query($sql);
$tpl->assign('is_error', (bool)num_rows($res));
if (!empty($results_array)) {
	$tpl->assign('current_url', get_current_url(false));
	$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('mail_src', $GLOBALS['administrer_url'] . '/images/mail.gif');
	$HeaderTitlesArray = array($GLOBALS['STR_ADMIN_ACTION'], 'id' => $GLOBALS['STR_ORDER'], $GLOBALS["STR_ADMIN_PRODUCT_NAME"], 'email' => $GLOBALS['STR_EMAIL'], 'o_timestamp' => $GLOBALS['STR_DATE'], 'paiement' => $GLOBALS['STR_PAYMENT'], $GLOBALS['STR_DELIVERY'], $GLOBALS['STR_ADMIN_COMMANDER_SEND_DOWNLOAD_LINK'], $GLOBALS['STR_DOWNLOAD'] . '  / (nb)');
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$tpl->assign('links_header_row', $Links->getHeaderRow());
	$tpl_results = array();
	$i = 0;
	foreach ($results_array as $order) {
		// A activer si vous voulez pouvoir supprimer des commandes - il faut dans ce cas aussi gérer le traitement de suppression
		$allow_delete_order = false;
		$delete_confirm_txt = $GLOBALS['STR_ADMIN_DELETE_WARNING'];
		if($allow_delete_order && is_stock_advanced_module_active()){
			$delete_confirm_txt .= ' ' . $GLOBALS['STR_ADMIN_COMMANDER_STOCK_RECREDIT'];
		}
		$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
			'modif_href' => get_current_url(false) . '?mode=details&id=' . $order['id'],
			'delete_confirm_txt' => $delete_confirm_txt,
			'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $order['id'],
			'id' => $order['id'],
			'nom_produit' => $order['nom_produit'],
			'email' => $order['email'],
			'date' => get_formatted_date($order['o_timestamp']),
			'payment_name' => get_payment_name($order['paiement']),
			'payment_status_name' => get_payment_status_name($order['id_statut_paiement']),
			'delivery_status_name' => get_delivery_status_name($order['id_statut_livraison']),
			'statut_envoi' => $order['statut_envoi'],
			'nb_envoi' => $order['nb_envoi'],
			'date_download' => get_formatted_date($order['date_download'], 'short', 'long'),
			'nb_download' => $order['nb_download'],
			'allow_delete_order' => $allow_delete_order
			);
		$i++;
	}
	$tpl->assign('results', $tpl_results);
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_COMMANDER_DOWNLOADS_LIST_TITLE', $GLOBALS['STR_ADMIN_COMMANDER_DOWNLOADS_LIST_TITLE']);
	$tpl->assign('STR_ADMIN_COMMANDER_WARNING_ALREADY_DOWNLOADED', $GLOBALS['STR_ADMIN_COMMANDER_WARNING_ALREADY_DOWNLOADED']);
	$tpl->assign('STR_ADMIN_COMMANDER_ALREADY_DOWNLOADED_DELETE_LINK_TEXT', $GLOBALS['STR_ADMIN_COMMANDER_ALREADY_DOWNLOADED_DELETE_LINK_TEXT']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_DELIVERY', $GLOBALS['STR_DELIVERY']);
	$tpl->assign('STR_ADMIN_COMMANDER_SEND_DOWNLOAD_LINK', $GLOBALS['STR_ADMIN_COMMANDER_SEND_DOWNLOAD_LINK']);
	$tpl->assign('STR_DOWNLOAD', $GLOBALS['STR_DOWNLOAD']);
	$tpl->assign('STR_ADMIN_COMMANDER_NO_DOWLOAD_ORDER_FOUND', $GLOBALS['STR_ADMIN_COMMANDER_NO_DOWLOAD_ORDER_FOUND']);
}
$tpl->assign('STR_ADMIN_COMMANDER_DOWNLOADS_LIST_TITLE', $GLOBALS['STR_ADMIN_COMMANDER_DOWNLOADS_LIST_TITLE']);
$tpl->assign('STR_ADMIN_COMMANDER_NO_DOWLOAD_ORDER_FOUND', $GLOBALS['STR_ADMIN_COMMANDER_NO_DOWLOAD_ORDER_FOUND']);

echo $tpl->fetch();

?>
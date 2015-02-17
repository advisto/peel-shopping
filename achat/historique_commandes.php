<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: historique_commandes.php 44077 2015-02-17 10:20:38Z sdelaporte $
include("../configuration.inc.php");
necessite_identification();

include("../lib/fonctions/display_caddie.php");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ORDER_HISTORY'];

define("IN_ORDER_HISTORY", true);
$GLOBALS['page_name'] = 'historique_commandes';

$output = '';
switch (vb($_REQUEST['mode'])) {
	case "details" :
		$sql = "SELECT c.*, sp.technical_code AS statut_paiement
			FROM peel_commandes c
			LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
			WHERE c.id = '" . intval($_GET['id']) . "' AND c.id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND c.o_timestamp = '" . nohtml_real_escape_string(vb($_GET['timestamp'])) . "' AND " . get_filter_site_cond('commandes', 'c') . "";
		$qid_commande = query($sql);
		if ($this_order = fetch_assoc($qid_commande)) {
			// On a bien rentré une URL qui est complète pour voir cette commande
			if(!empty($GLOBALS['site_parameters']['payment_status_forbid_payment'])) {
				$payment_status_forbid_payment = $GLOBALS['site_parameters']['payment_status_forbid_payment'];
			} else {
				$payment_status_forbid_payment = array('being_checked', 'completed', 'cancelled');
			}
			if(is_numeric(key($payment_status_forbid_payment))) {
				$allow_status_change = !in_array($this_order['id_statut_paiement'], $payment_status_forbid_payment);
			} else {
				$allow_status_change = !in_array($this_order['statut_paiement'], $payment_status_forbid_payment);
			}
			$output .= affiche_resume_commande(intval($_GET['id']), true, true, $allow_status_change);
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('global_error.tpl');
			$tpl->assign('message', $GLOBALS['STR_AUTH_DENIAL']);
			$output .= $tpl->fetch();
		}
		break;

	case "product_ordered_history" :
		// Récupération des produits des commandes réglées par l'utilisateur
		$sql = "SELECT ca.nom_produit, ca.produit_id , ca.quantite, c.o_timestamp, c.numero, c.id
			FROM peel_commandes_articles ca
			INNER JOIN peel_commandes c ON ca.commande_id = c.id AND " . get_filter_site_cond('commandes', 'c') . "
			LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
			WHERE id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND sp.technical_code = 'completed' AND " . get_filter_site_cond('commandes_articles', 'ca') . "";
		$Links = new Multipage($sql, 'affiche_product_ordered_history');
		$Links->OrderDefault = "o_timestamp";
		$Links->SortDefault = "DESC";
		$HeaderTitlesArray = array('nom_produit' => $GLOBALS['STR_PRODUCT_NAME'], 'quantite' => $GLOBALS['STR_QUANTITY'], 'o_timestamp' => $GLOBALS['STR_DATE'], 'numero' => $GLOBALS['STR_ORDER_NUMBER']);
		$Links->HeaderTitlesArray = $HeaderTitlesArray;
		$results_array = $Links->Query();

		$tpl = $GLOBALS['tplEngine']->createTemplate('products_ordered_history.tpl');
		if (empty($results_array)) {
			$tpl->assign('STR_NO_ORDER', $GLOBALS['STR_NO_ORDER']);
		} else {
			foreach($results_array as $this_products_ordered_history) {
				$product_object = new Product($this_products_ordered_history['produit_id']);
				$tmpProd = array(
					'nom_produit' => $this_products_ordered_history['nom_produit'],
					'href_produit' => $product_object->get_product_url(),
					'quantite' => $this_products_ordered_history['quantite'],
					'o_timestamp' => $this_products_ordered_history['o_timestamp'],
					'numero' => $this_products_ordered_history['numero']
				);
				$products[] = $tmpProd;
			}
			$tpl->assign('products', $products);
		}
		$tpl->assign('links_header_row', $Links->getHeaderRow());
		$tpl->assign('links_multipage', $Links->GetMultipage());
		
		$tpl->assign('STR_PRODUCTS_PURCHASED_LIST', $GLOBALS['STR_PRODUCTS_PURCHASED_LIST']);
		$output .= $tpl->fetch();
		break;

	default :
		$order = "o_timestamp";
		$sort = "DESC";
		$output .= affiche_liste_commandes($order, $sort);
		break;
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


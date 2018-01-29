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
// $Id: historique_commandes.php 55332 2017-12-01 10:44:06Z sdelaporte $
include("../configuration.inc.php");
necessite_identification();
if (!empty($GLOBALS['site_parameters']['order_history_for_user_disable']) && empty($_SESSION['session_utilisateur']['access_history'])) {
    // On a activé la possibilité de désactiver l'accès à l'historique de commande. Donc cet utilisateur n'a pas les droits pour accéder à cette page, on le redirige vers la home.
    redirect_and_die('/');
}

include($GLOBALS['dirroot']."/lib/fonctions/display_caddie.php");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ORDER_HISTORY'];

define("IN_ORDER_HISTORY", true);
$GLOBALS['page_name'] = 'historique_commandes';

$output = '';
$output .= call_module_hook('orders_history', array('mode' => vb($_REQUEST['mode']),'ca_id'=>vb($_GET['ca_id']),'capsule_id'=>vb($_GET['capsule_id']),'code_facture'=>vb($_GET['code_facture'])), 'string');
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
			foreach($payment_status_forbid_payment as $this_statut) {
				if (is_numeric($this_statut)) {
					$numeric_value=true;
					break;
				}
			}
			if(!empty($numeric_value)) {
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
		$tpl = $GLOBALS['tplEngine']->createTemplate('products_ordered_history.tpl');
		$sql = "SELECT ca.id AS ca_id, ca.nom_produit, ca.reference, ca.produit_id , ca.quantite, p.nb_view, c.o_timestamp, c.order_id, c.id, c.email, ca.attributs_list, c.nom_bill, p.technical_code
			FROM peel_commandes_articles ca
			INNER JOIN peel_commandes c ON ca.commande_id = c.id AND " . get_filter_site_cond('commandes', 'c') . "
			LEFT JOIN peel_produits p ON p.id = ca.produit_id AND " . get_filter_site_cond('produits', 'p') . "
			LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
			WHERE sp.technical_code = 'completed' AND " . get_filter_site_cond('commandes_articles', 'ca') . "";

		$hook_result = call_module_hook('ordered_product_list_pre', array('products', vb($_GET['products'])), 'array');
		if(!empty($hook_result)) {
			$order_list_type = $hook_result['order_list_type'];
			// Retourne tous les produits de type [donné par le hook] que l'utilisateur connecté a commandés.
			$sql .= $hook_result['sql'];
			$tpl->assign('STR_PRODUCTS_PURCHASED_LIST', $hook_result['STR_PRODUCTS_PURCHASED_LIST']);
			$HeaderTitlesArray = $hook_result['HeaderTitlesArray']; 
		} else {
			$order_list_type = 'order';
			$sql .= " AND c.id_utilisateur='" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'";
			$tpl->assign('STR_PRODUCTS_PURCHASED_LIST', $GLOBALS['STR_PRODUCTS_PURCHASED_LIST']);
			$HeaderTitlesArray = array('nom_produit' => $GLOBALS['STR_PRODUCT_NAME'], 'quantite' => $GLOBALS['STR_QUANTITY'], 'o_timestamp' => $GLOBALS['STR_DATE'], 'order_id' => $GLOBALS['STR_ORDER_NUMBER'],'' );
		}
		$Links = new Multipage($sql, 'affiche_product_ordered_history');
		$Links->OrderDefault = "o_timestamp";
		$Links->SortDefault = "DESC";
		$Links->HeaderTitlesArray = $HeaderTitlesArray;
		$results_array = $Links->Query();

		if (empty($results_array)) {
			$tpl->assign('STR_NO_ORDER', $GLOBALS['STR_NO_ORDER']);
		} else {
			foreach($results_array as $this_products_ordered_history) {
				$product_object = new Product($this_products_ordered_history['produit_id']);
				$tmpProd = call_module_hook('ordered_product_list', array('product_object' => $product_object, 'this_products_ordered_history' => $this_products_ordered_history), 'array');
				if (empty($tmpProd)) {
					// Valeur par défaut si aucune donnée par le hook
					$tmpProd = array(
						'nom_produit' => $this_products_ordered_history['nom_produit'],
						'href_produit' => $product_object->get_product_url(),
						'quantite' => $this_products_ordered_history['quantite'],
						'o_timestamp' => $this_products_ordered_history['o_timestamp'],
						'numero' => $this_products_ordered_history['order_id']
					);
				}
				$products[] = $tmpProd;
			}
			$tpl->assign('products', $products);
		}
		$tpl->assign('links_header_row', $Links->getHeaderRow());
		$tpl->assign('links_multipage', $Links->GetMultipage());
		
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


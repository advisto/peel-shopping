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
// $Id: commander.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
if(!empty($_GET['mode']) && $_GET['mode']=='ajout') {
	necessite_priv("admin_sales,admin_operations");
} else {
	necessite_priv("admin_sales,admin_finance,admin_operations");
}

if (check_if_module_active('fianet_sac')) {
	require_once($GLOBALS['fonctionsfianet_sac']);
}

$form_error_object = new FormError();
$frm = $_POST;
$GLOBALS['sortable_rpc'] = 'rpc_positions.php?mode=order';
$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_ADMIN_INDEX_ORDERS_LIST"];
$output = '';

if (!empty($_GET['commandeid'])) {
	$frm['commandeid'] = $_GET['commandeid'];
}

$output .= call_module_hook('order_admin', array('mode' => vb($_REQUEST['mode'])), 'string');
if (vb($frm['export_pdf'])) {
	if(check_if_module_active('facture_advanced', 'administrer/genere_pdf.php')) {
		if (!empty($frm)) {
			// Génération du PDF à la volée
			$ids_array = Array();
			for ($i = 0;$i < count($frm['id']);$i++) {
				if (!empty($frm['change_statut' . $frm['id'][$i]])) {
					$ids_array[] = $frm['id'][$i];
				}
			}
			include($GLOBALS['dirroot']."/lib/class/Invoice.php");
			$invoice_pdf = new Invoice('P', 'mm', 'A4');
			$is_pdf_generated = $invoice_pdf->FillDocument(null, null, null, null, null, null, null, 'facture', false, null, null, null, null, $ids_array);
			unset($invoice_pdf);
			if($is_pdf_generated) {
				die();
			} else {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_SEARCH_NO_RESULT']))->fetch();
			}
		}
	}
}
switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_ADMIN_COMMANDER_CREATE"];
		// Affiche le formulaire d'ajout de commande à partir d'un utilisateur
		if (!empty($_GET['id_utilisateur'])) {
			$user_id = intval($_GET['id_utilisateur']);
		} else {
			$user_id = 0;
		}
		$output .= affiche_details_commande(null, $_GET['mode'], $user_id);
		break;
		
	case "modif" :
		// Affiche le formulaire de la commande à modifier
		$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE"];
		if (!empty($_POST['bdc_code_facture']) && !empty($_POST['bdc_sendclient'])) {
			sendclient($_POST['bdc_id'], 'html', 'bdc', vb($_POST['bdc_partial']));
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_MSG_PURCHASE_ORDER_SENT_BY_EMAIL_OK']))->fetch();
		} elseif (!empty($_POST)) {
			$save_commande = true;
			for ($i = 1; $i <= 1000; $i++) {
				// $i <= 1000 : C'est le moyen le plus simple de traiter le formulaire, car les ids dans le formulaire ne se suivent par forcement, l'administrateur peut avoir supprimer le produit 1, ce qui fait que $frm["id1"] n'existe pas
				if (!empty($frm["id" . $i])) {
					// On vérifie si la commande ne comporte pas de produit avec une quantité inférieure au minimum requis
					$product_object = new Product($frm["id" . $i]);
					if (vn($product_object->quantity_min_order) > 1 && $frm["q" . $i] < $product_object->quantity_min_order) {
						$save_commande = false;
					}
					unset($product_object);
				}
			}
			if ($save_commande) {
				// Ajout d'une commande en db + affichage du détail de la commande
				$order_id = save_commande_in_database($frm);
				if (!empty($frm['commandeid'])) {
					$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_ORDER_UPDATED'] . (check_if_module_active('stock_advanced') ? ' ' . $GLOBALS['STR_ADMIN_COMMANDER_AND_STOCKS_UPDATED'] : '')))->fetch();
					$output .= affiche_details_commande($frm['commandeid'], $_GET['mode'], null);
				} else {
					$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_ORDER_CREATED'] . ' - <a href="' . $GLOBALS['administrer_url'] . '/commander.php?mode=modif&amp;commandeid=' . $order_id . '">' . $GLOBALS['STR_ADMIN_COMMANDER_LINK_ORDER_SUMMARY'] . '</a>'))->fetch();
				}
				if (empty($frm['id'])) {
					tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'CREATE_ORDER', intval(vn($frm['id_utilisateur'])));
				} else {
					tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'EDIT_ORDER', $GLOBALS['STR_ADMIN_USER'] . ' : ' . intval(vn($frm['id_utilisateur'])) . ', '.$GLOBALS['STR_ORDER_NAME'].' : ' . intval(vn($frm['id'])));
				}
			} else {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ORDER_MIN'].$GLOBALS['STR_REQUIRED_VALIDATE_ORDER']))->fetch();
				$output .= affiche_details_commande(null, 'ajout');
			}
		}
		// Si il n'y a pas de POST, cela signifie que l'on veut uniquement afficher le détail de la commande à modifier.
		if (empty($_POST)) {
			$output .= affiche_details_commande($_GET['commandeid'], $_GET['mode'], null);
		}
		break;
		
	case "maj_statut" :
		// Met à jour le statut du paiement et de l'envoi de la commande, lorsque l'on est en liste de commandes
		if (!empty($frm)) {
			if ((isset($frm['statut_paiement']) && is_numeric($frm['statut_paiement'])) || (isset($frm['statut_livraison']) && is_numeric($frm['statut_livraison']))) {
				for ($i = 0;$i < count($frm['id']);$i++) {
					if (!empty($frm['change_statut' . $frm['id'][$i]])) {
						// Retourne un message de confirmation d'envoi d'email en cas d'expédition.
						$output .= update_order_payment_status($frm['id'][$i], vb($frm['statut_paiement']), true, vb($frm['statut_livraison']));
					}
				}
				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_ORDER_STATUS_UPDATED']))->fetch();
			}
		}
		// Affiche la liste des commandes
		$output .= affiche_liste_commandes_admin($_GET);
		break;

	case "parrain" :
		query('UPDATE peel_utilisateurs
			SET avoir = avoir+' . nohtml_real_escape_string(vn($_POST['avoir'])) . '
			WHERE id_utilisateur = "' . intval(vn($_POST['id_parrain'])) . '" AND ' . get_filter_site_cond('utilisateurs') . '');
		$custom_template_tags['AVOIR'] = fprix(vn($_POST['avoir']), true, $GLOBALS['site_parameters']['code'], false);
		send_email($_POST['email_parrain'], '', '', 'commande_parrain_avoir', $custom_template_tags, null, $GLOBALS['support']);

		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_COMMANDER_MSG_AVOIR_SENT_BY_EMAIL_OK'], $custom_template_tags['AVOIR'], $_POST['email_parrain'])))->fetch();
		$output .= affiche_details_commande(intval($_POST['id']), $_POST['mode'], null);
		break;
	
	case "sendfacturepdf" :
		// Envoie par email la facture PDF de la commande à l'utilisateur
		$output .= send_facture_pdf_commandes($_GET);
		// Affichage des commandes en liste
		$output .= affiche_details_commande($_GET['id'], 'modif', null);
		break;
	
	case "download" :
	case "efface_download" :
	case "send_download" :
		// affichage des commandes avec des produits à télécharger
		// géré via un hook, fonction download_hook_order_admin
	break;
	
	case "export" :
		if(function_exists('get_csv_export_from_html_table')) {
			$export = affiche_liste_commandes_admin($_GET, 'html_array');
			get_csv_export_from_html_table($export);
		}
	break;

	case "update_transactions_table" :
	if (check_if_module_active('transactions')) {
		$output .= edit_transaction_table($_POST, $_GET['commandeid']);
	}
	$output .= affiche_details_commande($_GET['commandeid'], 'modif', null);
	break;
		
	case "mode_reglement" :
	if (check_if_module_active('transactions') && !empty($_POST['suppr_reglement'])) {
		// Suppression de l'ancien enregistrement pour mettre le nouveau.
		query("DELETE FROM peel_transactions WHERE id=".intval($_POST['suppr_reglement']));
		$output .= affiche_details_commande($_GET['commandeid'], $_GET['mode'], null);
	}
	break;

	case "recherche" :
	default :
		// Par défaut, affichage la liste des commandes
		$output .= affiche_liste_commandes_admin($_GET);
		break;
}
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


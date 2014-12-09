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
// $Id: commander.php 43040 2014-10-29 13:36:21Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales");

if (check_if_module_active('fianet_sac')) {
	require_once($GLOBALS['fonctionsfianet_sac']);
}

$form_error_object = new FormError();
$frm = $_POST;
$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_ADMIN_INDEX_ORDERS_LIST"];
$output = '';

if (!empty($_GET['commandeid'])) {
	$frm['commandeid'] = $_GET['commandeid'];
}
switch (vb($_REQUEST['mode'])) {
    case "duplicate" :
		$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_ADMIN_COMMANDER_CREATE"];
        if (check_if_module_active('duplicate') && isset($_GET['id'])) {
            include($fonctionsduplicate);
            duplicate_order(intval($_GET['id']));
			unset($_GET['id']);
        }
		$output .= affiche_liste_commandes_admin();
		break;

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
	// Affiche le formulaire de la commande à modifier
	case "modif" :
		$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE"];
		if (!empty($_POST['bdc_code_facture']) && !empty($_POST['bdc_sendclient'])) {
			sendclient($_POST['bdc_id'], 'html', 'bdc', $_POST['bdc_partial']);
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_MSG_PURCHASE_ORDER_SENT_BY_EMAIL_OK']))->fetch();
		} elseif (!empty($_POST)) {
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
		}
		// Si il n'y a pas de POST, cela signifie que l'on veut uniquement afficher le détail de la commande a modifier.
		if (empty($_POST)) {
			$output .= affiche_details_commande($_GET['commandeid'], $_GET['mode'], null);
		}
		break;

	case "affi" :
		if (is_affiliate_module_active()) {
			include($GLOBALS['dirroot'] . "/modules/affiliation/administrer/fonctions.php");
			$output .= affiche_liste_commandes_admin(array('affi'=>1));
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_AFFILIATION_MODULE_MISSING']))->fetch();
			$output .= affiche_liste_commandes_admin();
		}
		break;
	// Met à jour le statut du paiement et de l'envoi de la commande, lorsque l'on est en liste de commande
	case "maj_statut" :
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
		$output .= affiche_liste_commandes_admin();
		break;

	case "parrain" :
		query('UPDATE peel_utilisateurs
			SET avoir = avoir+' . nohtml_real_escape_string(vn($_POST['avoir'])) . '
			WHERE id_utilisateur = "' . intval(vn($_POST['id_parrain'])) . '" AND ' . get_filter_site_cond('utilisateurs', null, true) . '');
		$custom_template_tags['AVOIR'] = fprix(vn($_POST['avoir']), true, $GLOBALS['site_parameters']['code'], false);
		send_email($_POST['email_parrain'], '', '', 'commande_parrain_avoir', $custom_template_tags, null, $GLOBALS['support']);

		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_COMMANDER_MSG_AVOIR_SENT_BY_EMAIL_OK'], $custom_template_tags['AVOIR'], $_POST['email_parrain'])))->fetch();
		$output .= affiche_details_commande(intval($_POST['id']), $_POST['mode'], null);
		break;
	// Envoie par email la facture pdf de la commande à l'utilisateur
	case "sendfacturepdf" :
		$output .= send_facture_pdf_commandes($_GET);
		// Affichage des commandes en liste
		$output .= affiche_details_commande($_GET['id'], 'modif', null);
		break;
	// Recherche de commande
	case "recherche" :
		$output .= affiche_liste_commandes_admin($_GET);
		break;
	// commande de produit en téléchargement
	case "download" :
		$output .= affiche_liste_commandes_download();
		break;
	// efface les fichiers en téléchargement
	case "efface_download" :
		if (check_if_module_active('download')) {
			$output .=  efface_download();
			$output .= affiche_liste_commandes_download();
		}
		break;
	// envoi le lien de téléchargement par email
	case "send_download" :
		if (check_if_module_active('download')) {
			$output .=  efface_download();
			send_mail_product_download(vn($_GET['commandeid']));
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MAIL_SENDED'] . " " . $_GET['email']))->fetch();
			$output .= affiche_liste_commandes_download();
		}
		break;
	// Par défaut, affichage la liste des commandes
	default :
		$output .= affiche_liste_commandes_admin();
		break;
}
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: commander.php 37236 2013-06-11 19:10:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales");

if (is_fianet_sac_module_active()) {
	require_once($GLOBALS['fonctionsfianet_sac']);
}
$DOC_TITLE = $GLOBALS["STR_ADMIN_COMMANDER_CREATE"];
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");

$form_error_object = new FormError();
$frm = $_POST;

if (!empty($_GET['commandeid'])) {
	$frm['commandeid'] = $_GET['commandeid'];
}
switch (vb($_REQUEST['mode'])) {
    case "duplicate" :
        if (is_duplicate_module_active() && isset($_GET['id'])) {
            include($fonctionsduplicate);
            duplicate_order(intval($_GET['id']));
        }
		affiche_liste_commandes_admin();
		break;

	case "ajout" :
		// Affiche le formulaire d'ajout de commande à partir d'un utilisateur
		if (!empty($_GET['id_utilisateur'])) {
			$user_id = intval($_GET['id_utilisateur']);
		} else {
			$user_id = 0;
		}
		affiche_details_commande(null, $_GET['mode'], $user_id);
		break;
	// Affiche le formulaire de la commande à modifier
	case "modif" :
		if (!empty($_POST['bdc_code_facture']) && !empty($_POST['bdc_sendclient'])) {
			sendclient($_POST['bdc_id'], 'html', 'bdc', $_POST['bdc_partial']);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_MSG_PURCHASE_ORDER_SENT_BY_EMAIL_OK']))->fetch();
		} elseif (!empty($_POST)) {	
			// Ajout d'une commande en db + affichage du détail de la commande
			$order_id = save_commande_in_database($frm);
			if (!empty($frm['commandeid'])) {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_ORDER_UPDATED'] . (is_stock_advanced_module_active() ? ' ' . $GLOBALS['STR_ADMIN_COMMANDER_AND_STOCKS_UPDATED'] : '')))->fetch();
				affiche_details_commande($frm['commandeid'], $_GET['mode'], null);
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_ORDER_CREATED'] . ' - <a href="' . $GLOBALS['administrer_url'] . '/commander.php?mode=modif&amp;commandeid=' . $order_id . '">' . $GLOBALS['STR_ADMIN_COMMANDER_LINK_ORDER_SUMMARY'] . '</a>'))->fetch();
			}
			if (empty($frm['id'])) {
				tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'CREATE_ORDER', intval(vn($frm['id_utilisateur'])));
			} else {
				tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'EDIT_ORDER', $GLOBALS['STR_ADMIN_USER'] . ' : ' . intval(vn($frm['id_utilisateur'])) . ', '.$GLOBALS['STR_ORDER'].' : ' . intval(vn($frm['id'])));
			}

		}
		// Si il n'y a pas de POST, cela signifie que l'on veut uniquement afficher le détail de la commande a modifier.
		if (empty($_POST)) {
			affiche_details_commande($_GET['commandeid'], $_GET['mode'], null);
		}
		break;

	case "affi" :
		if (is_affiliate_module_active()) {
			include($GLOBALS['dirroot'] . "/modules/affiliation/administrer/fonctions.php");
			affiche_liste_commandes_affilies();
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_AFFILIATION_MODULE_MISSING']))->fetch();
			affiche_liste_commandes_admin();
		}
		break;
	// Met à jour le statut du paiement et de l'envoi de la commande, lorsque l'on est en liste de commande
	case "maj_statut" :
		if (!empty($frm)) {
			if ((isset($frm['statut_paiement']) && is_numeric($frm['statut_paiement'])) || (isset($frm['statut_livraison']) && is_numeric($frm['statut_livraison']))) {
				for ($i = 0;$i < count($frm['id']);$i++) {
					if (!empty($frm['change_statut' . $frm['id'][$i]])) {
						update_order_payment_status($frm['id'][$i], vb($frm['statut_paiement']), true, vb($frm['statut_livraison']));
					}
				}
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_ORDER_STATUS_UPDATED']))->fetch();
			}
		}
		// Affiche la liste des commandes
		affiche_liste_commandes_admin();
		break;

	case "parrain" :
		query('UPDATE peel_utilisateurs
			SET avoir = avoir+' . nohtml_real_escape_string(vn($_POST['avoir'])) . '
			WHERE id_utilisateur = "' . intval(vn($_POST['id_parrain'])) . '"');
		$custom_template_tags['AVOIR'] = fprix(vn($_POST['avoir']), true, $GLOBALS['site_parameters']['code'], false);
		send_email($_POST['email_parrain'], '', '', 'commande_parrain_avoir', $custom_template_tags, 'html', $GLOBALS['support']);

		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_COMMANDER_MSG_AVOIR_SENT_BY_EMAIL_OK'], $custom_template_tags['AVOIR'], $_POST['email_parrain'])))->fetch();
		affiche_details_commande(intval($_POST['id']), $_POST['mode'], null);
		break;
	// Envoie par email la facture pdf de la commande à l'utilisateur
	case "sendfacturepdf" :
		send_facture_pdf_commandes($_GET);
		// Affichage des commandes en liste
		affiche_details_commande($_GET['id'], 'modif', null);
		break;
	// Recherche de commande
	case "recherche" :
		affiche_recherche_commandes($_GET);
		break;
	// commande de produit en téléchargement
	case "download" :
		affiche_liste_commandes_download();
		break;
	// efface les fichiers en téléchargement
	case "efface_download" :
		if (is_download_module_active()) {
			echo efface_download();
			affiche_liste_commandes_download();
		}
		break;
	// envoi le lien de téléchargement par email
	case "send_download" :
		if (is_download_module_active()) {
			echo efface_download();
			send_mail_product_download(vn($_GET['commandeid']));
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MAIL_SENDED'] . " " . $_GET['email']))->fetch();
			affiche_liste_commandes_download();
		}
		break;
	// Par défaut, affichage la liste des commandes
	default :
		affiche_liste_commandes_admin();
		break;
}

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

?>
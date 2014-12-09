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
// $Id: caddie_ajout.php 43040 2014-10-29 13:36:21Z sdelaporte $
include("../configuration.inc.php");

$attributs_array_upload = array();

if (!isset($_COOKIE[$session_cookie_name]) && function_exists('ini_set')) {
	// L'utilisateur a chargé la page sans avoir déclaré dans sa requête HTTP de cookie de session.
	// Donc c'est que ce n'est pas un utilisateur avec les sessions qui fonctionnent, ou c'est un robot qui appelle cette page
	redirect_and_die($GLOBALS['wwwroot'] . "/cookie.php");
} elseif (!empty($_POST) && !empty($_GET)) {
	if(!is_array($_POST['qte'])) {
		// Dans la grande majorité des cas on ajoute les produits un par un, donc qte n'est pas un tableau.
		$_POST['qte'] = array($_POST['qte']);
	}
	unset($_SESSION['session_display_popup']);
	unset($_SESSION['session_show_caddie_popup']);

	$_SESSION['session_display_popup']['error_text'] = '';
	foreach ($_POST['qte'] as $i => $qte) {
		if (!empty($_GET['checkid'])) {
			// Le produit est un chèque cadeau
			$id = intval(trim($_GET['checkid']));
			$email_check = vb($_POST['email_check' . vb($_GET['checkid'])]);
			$quantite = "1";
		} elseif (!empty($_POST['produit_id'][$i])) {
			$id = intval(trim($_POST['produit_id'][$i]));
		} elseif (isset($_GET['prodid']) && !empty($_GET['prodid'])) {
			$email_check = '';
			$id = intval(trim($_GET['prodid']));
			$quantite = max(0, get_float_from_user_input($qte));
		} elseif (!empty($_POST['reference'][$i])) {
			$query = query("SELECT id 
				FROM peel_produits
				WHERE etat=1 AND reference = '" . nohtml_real_escape_string($_POST['reference'][$i]) . "' AND " . get_filter_site_cond('produits'));
			$result = fetch_assoc($query);
			$id = $result['id'];
		}
		if(empty($GLOBALS['site_parameters']['allow_float_quantity'])) {
			$quantite = intval($quantite);
		}
		$product_object = new Product($id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
		$listcadeaux_owner = null;
		if (!empty($_POST['listcadeaux_owner']) && is_giftlist_module_active()) {
			$listcadeaux_owner = $_POST['listcadeaux_owner'];
		}
		$attributs_array = array();
		if (check_if_module_active('attributs')) {
			// L'appel à get_attribut_list_from_post_data remplit également $_SESSION["session_display_popup"] en cas d'erreur de téléchargement
			$attribut_list = get_attribut_list_from_post_data($product_object, $_POST);
			if (!empty($_SESSION["session_display_popup"]["upload_error_text"])) {
				if (!empty($_SERVER['HTTP_REFERER'])) {
					redirect_and_die($_SERVER['HTTP_REFERER'] . "");
				} else {
					redirect_and_die($GLOBALS['wwwroot'] . "/");
				}
			} else {
				// on supprime la variable de session gérant le téléchargement de l'image
				unset($_SESSION["session_display_popup"]); 
			}
		}

		if (!empty($_POST['critere']) || !empty($_POST['critere_' . $i])) {
			// Affichage des combinaisons de couleur et taille dans un unique select
			if (!empty($_POST['critere_' . $i])) {
				$criteres = explode("|", $_POST['critere_' . $i]);
			} else {
				$criteres = explode("|", $_POST['critere']);
			}
			$couleur_id = intval(vn($criteres[0]));
			$taille_id = intval(vn($criteres[1]));
		} else {
			if (!empty($_POST['couleur_' . $i]) || !empty($_POST['taille_' . $i]) ) {
				$couleur_id = intval(vn($_POST['couleur_' . $i]));
				$taille_id = intval(vn($_POST['taille_' . $i]));
			} else {
				$couleur_id = intval(vn($_POST['couleur']));
				$taille_id = intval(vn($_POST['taille']));
			}
		}
		// On enregistre la taille pour revenir sur la bonne valeur du select
		$_SESSION['session_taille_id'] = $taille_id;
		// On enregistre le message à afficher si la quantité demandée est trop élevée par rapport au stock disponnible
		$product_object->set_configuration($couleur_id, $taille_id, vb($attribut_list), is_reseller_module_active() && is_reseller());

		if ($product_object->on_check == 0 || !empty($email_check)) {
			$can_add_to_cart = true; // possibilité d'ajouter au panier
			// Contrôle de la présence des attributs ayant un mandatory==1 - tableau d'erreurs rempli par l'appel à get_attribut_list_from_post_data() ci-dessus
			if (!empty($GLOBALS['error_attribut_mandatory'])) {
				// on n'ajoute pas au panier
				$can_add_to_cart = false;
				// le tableau $GLOBALS['error_attribut_mandatory'] contient le nom des attributs qui devraient être renseignés mais qui sont vides.
				foreach($GLOBALS['error_attribut_mandatory'] as $missed_attribut) {
					$_SESSION['session_display_popup']['error_text'] .= sprintf($GLOBALS['STR_MISSED_ATTRIBUT_MANDATORY'], $missed_attribut)."\n";
				}
			}
			// Contrôle si produit est sur devis ou non
			if ($product_object->on_estimate && !check_if_module_active('devis')) {
				// on n'ajoute pas au panier
				$can_add_to_cart = false;
				if(!est_identifie()) {
					$_SESSION['session_display_popup']['error_text'] .= $GLOBALS['STR_PLEASE_LOGIN'];
				} else{
					$_SESSION['session_display_popup']['error_text'] .= $GLOBALS['STR_CONTACT_US'];
				}
			}
			// Gestion de l'ajout au caddie
			if ($can_add_to_cart) {
				// Pas de problème => on ajoute le produit
				$added_quantity = $_SESSION['session_caddie']->add_product($product_object, $quantite, $email_check, $listcadeaux_owner);
				if (!empty($added_quantity) && check_if_module_active('cart_popup')) {
					$_SESSION['session_show_caddie_popup'] = true;
					unset($_SESSION['session_taille_id']);
				}
			} else {
				$added_quantity = 0;
			}
			if ($added_quantity < $quantite && empty($_SESSION['session_display_popup']['error_text'])) {
				// La quantité à ajouter est égale au maximum de la quantité commandable
				$_SESSION['session_display_popup']['error_text'] = $GLOBALS['STR_QUANTITY_INSUFFICIENT'] . "\n";
				if ($added_quantity == 0) {
					// Aucun produit ajouté au caddie
					$_SESSION['session_display_popup']['error_text'] .= $GLOBALS['STR_ZERO_PRODUCT_ADD'];
				} elseif ($added_quantity == 1) {
					// un seul produit ajouté
					$_SESSION['session_display_popup']['error_text'] .= $added_quantity . ' ' . $GLOBALS['STR_QUANTITY_PRODUCT_ADD'];
				} else {
					// plus de un produit ajoutés au caddie
					$_SESSION['session_display_popup']['error_text'] .= $added_quantity . ' ' . $GLOBALS['STR_QUANTITY_PRODUCTS_ADD'];
				}
			}
		}
		$_SESSION['session_caddie']->update();
	}
	// Préparation de l'affichage d'une popup de confirmation de mise dans le panier
} elseif (check_if_module_active('annonces') && !empty($_GET) && (vb($_GET['referer']) == 'gold' || vb($_GET['referer']) == 'verified')) {
	$id = intval(trim(vn($_GET['prodid'])));
	$product_object = new Product($id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
	$added_quantity = $_SESSION['session_caddie']->add_product($product_object, 1, '', '');
	$_SESSION['session_caddie']->update();
	redirect_and_die($GLOBALS['wwwroot'] . "/achat/caddie_affichage.php");
}

if (!empty($_SERVER['HTTP_REFERER'])) {
	redirect_and_die($_SERVER['HTTP_REFERER'] . "");
} else {
	redirect_and_die($GLOBALS['wwwroot'] . "/");
}


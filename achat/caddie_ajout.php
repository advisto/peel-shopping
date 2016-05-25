<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: caddie_ajout.php 49979 2016-05-23 12:29:53Z sdelaporte $
include("../configuration.inc.php");

$attributs_array_upload = array();

call_module_hook('cart_product_add', array('user_id' => vn($_SESSION['session_utilisateur']['id_utilisateur'])));
if (!isset($_COOKIE[$session_cookie_name]) && function_exists('ini_set')) {
	// L'utilisateur a chargé la page sans avoir déclaré dans sa requête HTTP de cookie de session.
	// Donc c'est que ce n'est pas un utilisateur avec les sessions qui fonctionnent, ou c'est un robot qui appelle cette page
	redirect_and_die(get_url('cookie'));
} elseif (!empty($_POST) && !empty($_GET)) {
	if(!is_array($_POST['qte'])) {
		// Dans la grande majorité des cas on ajoute les produits un par un, donc qte n'est pas un tableau.
		$_POST['qte'] = array($_POST['qte']);
	}
	unset($_SESSION['session_display_popup']);
	unset($_SESSION['session_show_caddie_popup']);

	$_SESSION['session_display_popup']['error_text'] = '';
	foreach ($_POST['qte'] as $i => $qte) {
		$quantite = max(0, get_float_from_user_input($qte));
		$data_check = null;
		if (!empty($_GET['checkid'])) {
			// Le produit est un chèque cadeau
			$id = intval(trim($_GET['checkid']));
			$data_check['email_check'] =  vb($_POST['email_check' . vb($_GET['checkid'])]);
			$data_check['nom_check'] =  vb($_POST['nom' . vb($_GET['checkid'])]);
			$data_check['prenom_check'] =  vb($_POST['prenom' . vb($_GET['checkid'])]);
						
			if (!empty($GLOBALS['site_parameters']['user_register_during_giftcheck_order_process']) && !empty($_POST['sender_email_check' . vb($_GET['checkid'])]) && !empty($_POST['sender_prenom' . vb($_GET['checkid'])]) && !empty($_POST['sender_nom' . vb($_GET['checkid'])])) {
				// On utilise les informations rempli lors de l'ajout au panier d'un cheque cadeaux. Dans ce cas on enregistre la personne offrant le cadeaux.
				// On s'assure d'avoir au moins le nom, le prénom et l'email du créateur du chèque pour lui créer un compte.
				$frm['email'] = $_POST['sender_email_check' . vb($_GET['checkid'])];
				$frm['prenom'] = $_POST['sender_prenom' . vb($_GET['checkid'])];
				$frm['nom_famille'] = $_POST['sender_nom' . vb($_GET['checkid'])];
				insere_utilisateur($frm, false, true, true, false, true);
				user_login_now($frm['email'], '', false);
			}
			$quantite = "1";
		} elseif (!empty($_POST['produit_id'][$i])) {
			$id = intval(trim($_POST['produit_id'][$i]));
		} elseif (isset($_GET['prodid']) && !empty($_GET['prodid'])) {
			$id = intval(trim($_GET['prodid']));
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
		
		if ((!empty($_GET['from']) && $_GET['from'] == 'search_page') && ((isset($_POST['save_product_list']) && check_if_module_active('cart_preservation')) || (isset($_POST['export_pdf']) && check_if_module_active('facture_advanced', 'administrer/genere_pdf.php')))) {
			// Sauvegarde via le module de conservation de panier de la liste de produit envoyée.
			// Constitution du tableau de donnée compatible avec la fonction save_cart (voir plus bas)
			$articles_array[$i]['product_id'] = $id;
			$articles_array[$i]['quantite'] = $quantite;
			$articles_array[$i]['couleurId'] = null;
			$articles_array[$i]['tailleId'] = null;
			$articles_array[$i]['attribut'] = null;
			$articles_array[$i]['id_attribut'] = null;
			$articles_array[$i]['products_list_name'] = vb($_POST['products_list_name']);
		} elseif(isset($_POST['save_product_list_in_reminder']) && !empty($_GET['from']) && $_GET['from'] == 'search_page' && check_if_module_active('pensebete')) {
			// Insertion du produit dans le pense-bête
			insere_pense($id, 'produit');
			// produit suivant.
			continue;
		} else {
			$product_object = new Product($id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
			// Hook qui permet d'ajouter ou modifier des infos dans POST
			call_module_hook('cart_product_added_before_attribut_treatment', array('quantite' => $quantite, 'user_id' => vn($_SESSION['session_utilisateur']['id_utilisateur']), 'product_object' => $product_object));

			$listcadeaux_owner = null;
			if (!empty($_POST['listcadeaux_owner']) && check_if_module_active('listecadeau')) {
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
						redirect_and_die(get_url('/'));
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
			$product_object->set_configuration($couleur_id, $taille_id, vb($attribut_list), check_if_module_active('reseller') && is_reseller());

			if ($product_object->on_check == 0 || !empty($data_check)) {
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
					} else {
						$_SESSION['session_display_popup']['error_text'] .= $GLOBALS['STR_CONTACT_US'];
					}
				}
				// Gestion de l'ajout au caddie
				if ($can_add_to_cart) {
					// Pas de problème => on ajoute le produit
					$added_quantity = $_SESSION['session_caddie']->add_product($product_object, $quantite, $data_check, $listcadeaux_owner, vb($_GET['reference']));
					// Préparation par exemple de l'affichage d'une popup de confirmation de mise dans le panier dans le module cart_popup
					call_module_hook('cart_product_added', array('quantite' => $added_quantity, 'user_id' => $_SESSION['session_utilisateur']['id_utilisateur'], 'product_object' => $product_object));
					unset($_SESSION['session_taille_id']);
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
	}
	if (isset($_POST['save_product_list']) && !empty($articles_array)) {
		// Sauvgarde de la liste de produit
		save_cart($articles_array);
		// Les produits ont été sauvegardés, l'utilisateur est redirigé vers la page des produits sauvegardés
		redirect_and_die(get_url('/compte.php'));
	} elseif(isset($_POST['save_product_list_in_reminder']) && !empty($_GET['from']) && $_GET['from'] == 'search_page' && check_if_module_active('pensebete')){
		// Les produits ont été ajoutés au pense-bête, l'utilisateur est redirigé vers la page pour voir le résultat.
		redirect_and_die(get_url('/modules/pensebete/voir.php'));
	} elseif(isset($_POST['export_pdf']) && !empty($articles_array)){
		$_SESSION['export_pdf_products_info_array'] = $articles_array;
		redirect_and_die($GLOBALS['wwwroot'] . '/modules/facture_advanced/genere_pdf.php?export_products_list_in_pdf_file=search_page');
	}
}

if (!empty($_SERVER['HTTP_REFERER'])) {
	redirect_and_die($_SERVER['HTTP_REFERER'] . "");
} else {
	redirect_and_die(get_url('/'));
}


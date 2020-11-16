<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: caddie_ajout.php 64741 2020-10-21 13:48:51Z sdelaporte $
include("../configuration.inc.php");

$attributs_array_upload = array();

if(!empty($GLOBALS['site_parameters']['save_cart_auto_enable'])) {
	necessite_identification();
}
call_module_hook('cart_product_add', array('user_id' => vn($_SESSION['session_utilisateur']['id_utilisateur'])));
if (!isset($_COOKIE[$session_cookie_name]) && function_exists('ini_set')) {
	// L'utilisateur a chargé la page sans avoir déclaré dans sa requête HTTP de cookie de session.
	// Donc c'est que ce n'est pas un utilisateur avec les sessions qui fonctionnent, ou c'est un robot qui appelle cette page
	redirect_and_die(get_url('cookie'));
} elseif (!empty($_POST) && !empty($_GET['from']) && $_GET['from'] == 'search_page' && isset($_POST['add_cart'])) {
	foreach($_SESSION['session_search_product_list'] as $produit_id=>$quantite) {
		$product_object = new Product($produit_id);
		$_SESSION['session_caddie']->add_product($product_object, $quantite);
		$_SESSION['session_show_caddie_popup'] = true;
		unset($product_object);
	}
	$_SESSION['session_caddie']->update();
	if(est_identifie() && !empty($GLOBALS['site_parameters']['save_cart_auto_enable'])) {
		preserve_cart();
	}
} elseif (!empty($_POST) && !empty($_GET)) {
	if(!is_array($_POST['qte'])) {
		// Dans la grande majorité des cas on ajoute les produits un par un, donc qte n'est pas un tableau.
		$_POST['qte'] = array($_POST['qte']);
	}
	unset($_SESSION['session_display_popup']);
	unset($_SESSION['session_show_caddie_popup']);

	if (!empty($_GET['is_quote'])) {
		// Si le produit est que sur devis, on va créer un nouvelle session caddie juste pour le devis.
		// Cette session caddie dédiée au devis sert exclusivement sur cette page. On ajoute le produit concerné dans cette nouvelle session est on créer la commande directement. Ensuite la session est supprimée à la fin de ce fichier.
		// De cette manière on peux utiliser les fonctions standard de création de commande juste pour un produit, sans modifier le panier déjà existant avec session_caddie et session_commande
		if (!isset($_SESSION['session_caddie_quote']) || empty($_SESSION['session_caddie_quote'])) {
			$_SESSION['session_caddie_quote'] = new Caddie(get_current_user_promotion_percentage());
		}
	}
		
	$_SESSION['session_display_popup']['error_text'] = '';
	foreach ($_POST['qte'] as $i => $qte) {
		$product_infos['on_check'] = 0;
		$quantite = max(0, get_float_from_user_input($qte));
		$data_check = null;
		if (!empty($_GET['checkid'])) {
			$product_infos['on_check'] = 1;
			// Le produit est un chèque cadeau
			$id = intval(trim($_GET['checkid']));
			$data_check['email_check'] =  vb($_POST['email_check' . vb($_GET['checkid'])]);
			$data_check['nom_check'] =  vb($_POST['nom' . vb($_GET['checkid'])]);
			$data_check['prenom_check'] =  vb($_POST['prenom' . vb($_GET['checkid'])]);
						
			if (!empty($GLOBALS['site_parameters']['user_register_during_giftcheck_order_process']) && !empty($_POST['sender_email_check' . vb($_GET['checkid'])]) && !empty($_POST['sender_prenom' . vb($_GET['checkid'])]) && !empty($_POST['sender_nom' . vb($_GET['checkid'])])) {
				// On utilise les informations rempli lors de l'ajout au panier d'un cheque cadeaux. Dans ce cas on enregistre la personne offrant le cadeaux.
				// On s'assure d'avoir au moins le nom, le prénom et l'email du créateur du chèque pour lui créer un compte.
				$frm = array();
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
		$product_object = new Product($id, $product_infos, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
		if (empty($product_object->id)) {
			// le produit n'est pas trouvé, on ne fait rien de plus
			if (!empty($_SERVER['HTTP_REFERER'])) {
				redirect_and_die($_SERVER['HTTP_REFERER'] . "");
			} else {
				redirect_and_die(get_url('/'));
			}
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
			// Hook qui permet d'ajouter ou modifier des infos dans POST
			call_module_hook('cart_product_added_before_attribut_treatment', array('quantite' => $quantite, 'user_id' => vn($_SESSION['session_utilisateur']['id_utilisateur']), 'product_object' => $product_object));

			$listcadeaux_owner = null;
			if (!empty($_POST['listcadeaux_owner']) && check_if_module_active('listecadeau')) {
				$listcadeaux_owner = $_POST['listcadeaux_owner'];
			}
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
					unset($_SESSION["session_display_popup"]["upload_error_text"]); 
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
				if (vn($product_object->quantity_min_order) > 1 && $quantite < $product_object->quantity_min_order) {
					// on n'ajoute pas au panier
					$can_add_to_cart = false;
					$_SESSION['session_display_popup']['error_text'] .= $GLOBALS['STR_ORDER_MIN'].' '.$product_object->quantity_min_order;
				}
				
				$product_available_for_user = call_module_hook('check_if_product_available_for_user', array('product_object' => $product_object), 'array');
				// si pas de résultat product_available_for_user a pour valeur true, cf fonction call_module_hook
				if (!empty($GLOBALS['site_parameters']['user_offers_table_enable']) && empty($product_available_for_user['result']) && empty($product_available_for_user['is_quote'])) {
					$order_only_if_offer_users = true;
					$can_add_to_cart = false;
				}
				if (function_exists('product_surface_add_to_cart')) {
					$product_surface_add_to_cart = product_surface_add_to_cart($_POST);
					if ($product_surface_add_to_cart !== true) {
						$can_add_to_cart = false;
						$_SESSION['session_display_popup']['error_text'] .= $product_surface_add_to_cart;
					}
				}
				// Gestion de l'ajout au caddie
				if ($can_add_to_cart) {
					// Pas de problème => on ajoute le produit
					$hook_result = call_module_hook('add_cart_complementary_data_array', vb($_GET), 'array');
					// $hook_result contient de nouvelle valeurs pour caddie_ajout; On utilise un hook pour filtrer les données du GET
					if (!empty($_GET['is_quote'])) {
						// On ajoute le produit sur devis dans la session caddie dédié.
						$added_quantity = $_SESSION['session_caddie_quote']->add_product($product_object, $quantite, $data_check, $listcadeaux_owner, $hook_result);
					} else {
						$added_quantity = $_SESSION['session_caddie']->add_product($product_object, $quantite, $data_check, $listcadeaux_owner, $hook_result);
					}
					
					// Préparation par exemple de l'affichage d'une popup de confirmation de mise dans le panier dans le module cart_popup
					call_module_hook('cart_product_added', array('quantite' => $added_quantity, 'user_id' => $_SESSION['session_utilisateur']['id_utilisateur'], 'product_object' => $product_object));
					unset($_SESSION['session_taille_id']);

					if(est_identifie() && !empty($GLOBALS['site_parameters']['save_cart_auto_enable'])) {
						$sql = "SELECT *
							FROM peel_save_cart 
							WHERE id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'
								AND produit_id = '" . intval($product_object->id) . "'
								AND nom_produit = '" . nohtml_real_escape_string($product_object->name) . "'
								AND couleur_id = '" . nohtml_real_escape_string($couleur_id) . "'
								AND taille_id = '" . nohtml_real_escape_string($taille_id) . "'
								AND products_list_name = '00panier'";
						$query = query($sql);

						if($result = fetch_assoc($query)) {
							$sql = "UPDATE peel_save_cart SET
								quantite = quantite+'" . nohtml_real_escape_string($added_quantity) . "'
								WHERE id = ".intval($result['id'])."";
						} else {
							$sql = "INSERT INTO peel_save_cart SET
								id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "',
								produit_id = '" . intval($product_object->id) . "',
								quantite = '" . nohtml_real_escape_string($quantite) . "',
								nom_produit = '" . nohtml_real_escape_string($product_object->name) . "',
								couleur_id = '" . nohtml_real_escape_string($couleur_id) . "',
								taille_id = '" . nohtml_real_escape_string($taille_id) . "',
								products_list_name = '00panier'";
						}
						query($sql);
					}
				} else {
					$added_quantity = 0;
				}
				
				unset($product_object);
				
				if ($added_quantity < $quantite && empty($_SESSION['session_display_popup']['error_text'])) {
					// La quantité à ajouter est égale au maximum de la quantité commandable
					if (!empty($order_only_if_offer_users)) {
						$_SESSION['session_display_popup']['error_text'] = $GLOBALS['STR_PRODUCT_NOT_AVAILABLE_CONTACT_SELL_SERVICE'] . "\n";
					} else {
						$_SESSION['session_display_popup']['error_text'] = $GLOBALS['STR_QUANTITY_INSUFFICIENT'] . "\n";
					}
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
			
			if (!empty($GLOBALS['product_in_caddie_cookie']) && !empty($GLOBALS['site_parameters']['save_caddie_in_cookie']) && count($GLOBALS['product_in_caddie_cookie'])<=25) {
				// on crée le cookie avec 1 an de vie
				unset($_COOKIE[$GLOBALS['caddie_cookie_name']]);
				// Un cookie ne peut faire que 4Ko. Donc le nombre de produit à retenir dans le cookie est d'environ 25 produits.
				// On pourrait compresser le contenu dans le cookies en utilisant base64_encode(gzcompress(serialize($GLOBALS['product_in_caddie_cookie']))) mais il reste un problème de gestion des caractères =, il faudrait faire de la bidouille pour contourner le problème, donc on ne fait rien.
				if($GLOBALS['site_parameters']['force_sessions_for_subdomains']){
					@setcookie($GLOBALS['caddie_cookie_name'], serialize($GLOBALS['product_in_caddie_cookie']), time() + 365 * 24 * 60 * 60, '/', '.'.get_site_domain());
				} else {
					@setcookie($GLOBALS['caddie_cookie_name'], serialize($GLOBALS['product_in_caddie_cookie']), time() + 365 * 24 * 60 * 60, '/');
				}
			}
		}
	}
	if (isset($_POST['print_label']) && !empty($articles_array)) {
		// Affichage des produits pour l'impression d'étiquette.
		print_label($articles_array);
	} elseif (isset($_POST['save_product_list']) && !empty($articles_array)) {
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
if (!empty($_GET['is_quote'])) {
	$frm['email'] = $_POST['email'];
	// Le produit est sur devis et à ce stade il est contenu dans la nouvelle session session_caddie_quote.
	// On créer le devis en BDD :
	unset($_SESSION['session_form_contact_sent']);
	unset($_SESSION["session_display_popup"]);
	unset($_SESSION['session_show_caddie_popup']);
	$create_devis_order_output = Devis::create_devis_order($frm, 'punchout_quote');
	if(!empty($_SESSION['session_caddie_quote']->commande_id)) {
		// On supprime la session de caddie spécial pour le devis
		unset($_SESSION['session_caddie_quote']);
		// On supprime de la session les données spécifique au devis
		unset($_SESSION['session_quote']);

		include($GLOBALS['repertoire_modele'] . "/haut.php");
		echo $create_devis_order_output;
		include($GLOBALS['repertoire_modele'] . "/bas.php");
		die();
	}
}
if (!empty($_SERVER['HTTP_REFERER'])) {
	redirect_and_die($_SERVER['HTTP_REFERER'] . "");
} else {
	redirect_and_die(get_url('/'));
}


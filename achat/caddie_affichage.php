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
// $Id: caddie_affichage.php 55332 2017-12-01 10:44:06Z sdelaporte $
include("../configuration.inc.php");
include($GLOBALS['dirroot']."/lib/fonctions/display_caddie.php");

define('IN_CADDIE', true);
$GLOBALS['DOC_TITLE'] =  $GLOBALS['STR_CADDIE'];
$form_error_object = new FormError();
$cart_measurement_max_reached = vb($_GET['cart_measurement_max_reached']);

if (isset($_POST['pays_zone'])) {
	$_SESSION['session_caddie']->set_zone($_POST['pays_zone']);
}
// On ne met volontairement pas ici de else, pour gérer les cas où set_zone n'a pas fonctionné à cause d'un POST trafiqué, ou d'une erreur quelconque
if (empty($_SESSION['session_caddie']->zoneId) && !empty($_SESSION['session_utilisateur']['zoneId'])) {
	$_SESSION['session_caddie']->set_zone($_SESSION['session_utilisateur']['zoneId']);
}
// On ne met volontairement pas ici de else
if (empty($_SESSION['session_caddie']->zoneId) && !empty($GLOBALS['site_parameters']['default_delivery_zone_id'])) {
	// Force le zone au chargement du panier, si aucun choix n'a été fait avant.
	$_SESSION['session_caddie']->set_zone($GLOBALS['site_parameters']['default_delivery_zone_id']);
} elseif (!empty($_SESSION['session_utilisateur']['zoneId'])) {
	// L'utilisateur vient d'arriver sur la page de caddie : on présélectionne la zone liée à l'adresse de son compte
	$_SESSION['session_caddie']->set_zone($_SESSION['session_utilisateur']['zoneId']);
	$_SESSION['session_caddie']->update();
}
if (isset($_POST['type'])) {
	$typeId = intval($_POST['type']);
	$_SESSION['session_caddie']->set_type($typeId);
	if (!empty($GLOBALS['site_parameters']['redirect_user_after_delivery_type_section'][$_SESSION['session_caddie']->typeId])) {
		// Ne pas utiliser directement la variable $typeId, mais bien ce qui est défini par la classe Caddie. $typeId correspond au choix de l'utilisateur en front donc cette information est moins fiable qu ce qui est traité par la classe Caddie.
		// Le tableau redirect_user_after_delivery_type_section se compose de l'id du type de livraison en index et du lien en valeur
		redirect_and_die($GLOBALS['site_parameters']['redirect_user_after_delivery_type_section'][$_SESSION['session_caddie']->typeId]);
	}
} elseif (empty($_SESSION['session_caddie']->typeId) && !empty($GLOBALS['site_parameters']['default_delivery_type_id'])) {
	// Force le type au chargement du panier, si aucun choix n'a été fait avant.
	$_SESSION['session_caddie']->set_type($GLOBALS['site_parameters']['default_delivery_type_id']);
}
if (!empty($_POST['payment_multiple'])) {
	$_SESSION['session_caddie']->payment_multiple = $_POST['payment_multiple'];
}
if (!empty($_POST['code_promo'])) {
	// L'utilisateur envoie son code promo pour qu'il soit pris en compte dans le panier. update_code_promo permet d'appliquer ou non le code promo.
	$_POST['code_promo'] = StringMb::strtoupper(trim($_POST['code_promo']));
	$_SESSION['session_caddie']->update_code_promo($_POST['code_promo']);
	$_SESSION['session_caddie']->update();
}
if (!empty($_GET['code_promo']) && $_GET['code_promo'] == 'delete') {
	// L'utilisateur souhaite supprimer le code promo de son panier. Il clique sur le lien de suppression présent dans le panier.
	$_SESSION['session_caddie']->update_code_promo('');
	$_SESSION['session_caddie']->update();
}
$form_error_object = new FormError();
$short_order_process = false;
if (!empty($GLOBALS['site_parameters']['cart_measurement_max_quotation']) && is_tnt_module_active() && !empty($_SESSION['session_caddie']->typeId) && $GLOBALS['web_service_tnt']->is_type_linked_to_tnt(vn($_SESSION['session_caddie']->typeId))) {
	$cart_measurement_max_array = get_cart_measurement_max($_SESSION['session_caddie']->articles, $_SESSION['session_caddie']->id_attribut);
	if ($cart_measurement_max_array > $GLOBALS['site_parameters']['tnt_treshold']) {
		// Le produit le plus grand du panier dépasse la taille maximal autorisé pour le transporteur choisi (TNT)
		// Il faut afficher un message spécifique dans ce cas
		 // "Le calcul des frais de port vous sera envoyé par devis"
		$cart_measurement_max_reached = true;
		$form_error_object->add('type', $GLOBALS['STR_DELIVERY_COST_QUOTE']);
	}
}

call_module_hook('show_caddie_pre', array('user'=>vb($_SESSION['session_utilisateur']),'frm'=>$_POST, 'form_error_object'=>$form_error_object));

if (isset($_POST['func'])) {
	$mode = $_POST['func'];
} else {
	$mode = vb($_GET['func']);
}

if ($mode) {
	switch ($mode) {
		case "enleve" :
			$_SESSION['session_caddie']->delete_line(intval(vb($_GET['ligne'])));
			$_SESSION['session_caddie']->update();
			if ($_SESSION['session_caddie']->count_products() == 0) {
				// Suite à la suppression du produit, il n'y a plus de produit dans le caddie. Donc on réinitialise le caddie pour remettre à 0 tous les totaux;
				$_SESSION['session_caddie']->init();
			}
			redirect_and_die(get_current_url(false));

		case "vide" :
			if (!empty($_COOKIE[$GLOBALS['caddie_cookie_name']])) {
				// Il faut supprimer le cookie qui contient les produits du panier, sinon le caddie est automatiquement rechargé dans init().
				unset($_COOKIE[$GLOBALS['caddie_cookie_name']]);
			}
			$_SESSION['session_caddie']->init();
			break;
			
		case "force_update" :
			$_SESSION['session_caddie']->update();
			break;
			
		case "recalc" :
		case "commande" :
		default :
			// change_lines_data : mise à jour de chaque ligne du panier à partir des valeurs du formulaire du panier. Si le module de stock est installé, le recalcul de la quantité disponible pour le produit est fait à partir des valeurs de peel_stock_temp (voir fonction reservation_stock_temp)
			$_SESSION['session_caddie']->change_lines_data($_POST);
			if($mode!='recalc') {
				if (!empty($GLOBALS['site_parameters']['mode_transport'])) {
					// Frais de port calculés à partir du poids total ou du montant total d'une commande
					if (!empty($GLOBALS['site_parameters']['short_order_proces_if_cart_measurement_max_reached']) && !empty($cart_measurement_max_reached)) {
						$redirect_next_step = true;
						$short_order_process = true;
					} elseif (empty($_POST['pays_zone'])) {
						$form_error_object->add('pays_zone', $GLOBALS['STR_ERR_ZONE']);
					} elseif (empty($_POST['type'])) {
						$form_error_object->add('type', $GLOBALS['STR_ERR_TYPE']);
					} elseif (num_rows(query("SELECT 1 FROM peel_tarifs WHERE type='" . intval($_SESSION['session_caddie']->typeId) . "' AND zone='" . intval($_SESSION['session_caddie']->zoneId) . "' AND " . get_filter_site_cond('tarifs') . "")) == 0) {
						// Ici on teste la cohérence entre le type et la zone
						$form_error_object->add('type', $GLOBALS['STR_ERR_TYPE']);
					} elseif ($GLOBALS['site_parameters']['minimal_amount_to_order'] > $_SESSION['session_caddie']->total_produit) {
						// Ici on reteste le montant minimum d'achat 
						$form_error_object->add('minimum_error', $GLOBALS['STR_MINIMUM_PURCHASE_OF'].fprix($GLOBALS['site_parameters']['minimal_amount_to_order'], true).$GLOBALS['STR_REQUIRED_VALIDATE_ORDER']);
					} elseif(!count($_SESSION['session_caddie']->message_caddie) && empty($form_error_object->error)) {
						$redirect_next_step = true;
					} elseif(check_if_module_active('tnt') && $GLOBALS['web_service_tnt']->is_type_linked_to_tnt(vn($_SESSION['session_caddie']->typeId)) && $form_error_object->text('type') !='') {
						// Dans le cas du module TNT, si on a une erreur sur le type de livraison on passe quand même à l'étape suivante
						$redirect_next_step = true;
						// Par contre on active le mode de commande court, la commande se transforme en devis (pas de paiement)
						$short_order_process = true;
					} 
				} elseif(empty($form_error_object->error)) {
					// Pas de frais de port (c'est la configuration pour tout le site)
					$redirect_next_step = true;
				}
			}
			break;
	}
}
if (!empty($redirect_next_step)) {
	if (!est_identifie() && empty($GLOBALS['site_parameters']['unsubscribe_order_process'])) {
		necessite_identification();
	} else {
		call_module_hook('show_caddie_next_step_pre', array());
		if (!empty($short_order_process)) {
			// On active le process de commande court
			redirect_and_die(get_url('achat_maintenant'), array('short_order_process' => $short_order_process));
		} else {
			redirect_and_die(get_url('achat_maintenant'));
		}
	}
}

$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['caddie_affichage_page_columns_count'];
include($GLOBALS['repertoire_modele'] . "/haut.php");

echo get_caddie_content_html($form_error_object, vn($GLOBALS['site_parameters']['mode_transport']));

include($GLOBALS['repertoire_modele'] . "/bas.php");


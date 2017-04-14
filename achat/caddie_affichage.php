<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2017 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.5, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: caddie_affichage.php 53200 2017-03-20 11:19:46Z sdelaporte $
include("../configuration.inc.php");
include($GLOBALS['dirroot']."/lib/fonctions/display_caddie.php");

define('IN_CADDIE', true);
$GLOBALS['DOC_TITLE'] =  $GLOBALS['STR_CADDIE'];

if (isset($_POST['pays_zone'])) {
	$_SESSION['session_caddie']->set_zone($_POST['pays_zone']);
} elseif (empty($_SESSION['session_caddie']->zoneId) && !empty($GLOBALS['site_parameters']['default_delivery_zone_id'])) {
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
					if (empty($_POST['pays_zone'])) {
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
		redirect_and_die(get_url('achat_maintenant'));
	}
}

$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['caddie_affichage_page_columns_count'];
include($GLOBALS['repertoire_modele'] . "/haut.php");

echo get_caddie_content_html($form_error_object, vn($GLOBALS['site_parameters']['mode_transport']));

include($GLOBALS['repertoire_modele'] . "/bas.php");


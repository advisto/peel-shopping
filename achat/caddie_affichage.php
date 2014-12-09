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
// $Id: caddie_affichage.php 43038 2014-10-29 12:10:28Z sdelaporte $
include("../configuration.inc.php");
include("../lib/fonctions/display_caddie.php");

define('IN_CADDIE', true);
$GLOBALS['DOC_TITLE'] =  $GLOBALS['STR_CADDIE'];

if (isset($_POST['pays_zone'])) {
	$_SESSION['session_caddie']->set_zone($_POST['pays_zone']);
	$_SESSION['session_caddie']->update();
}
if (isset($_POST['type'])) {
	$typeId = intval($_POST['type']);
	$_SESSION['session_caddie']->set_type($typeId);
	$_SESSION['session_caddie']->update();
	if (!empty($GLOBALS['site_parameters']['redirect_user_after_delivery_type_section'][$_SESSION['session_caddie']->typeId])) {
		// Ne pas utiliser directement la variable $typeId, mais bien ce qui est défini par la classe Caddie. $typeId correspond au choix de l'utilisateur en front donc cette information est moins fiable qu ce qui est traité par la classe Caddie.
		// Le tableau redirect_user_after_delivery_type_section se compose de l'id du type de livraison en index et du lien en valeur
		redirect_and_die($GLOBALS['site_parameters']['redirect_user_after_delivery_type_section'][$_SESSION['session_caddie']->typeId]);
	}
}
if (!empty($_POST['code_promo'])) {
	$_POST['code_promo'] = String::strtoupper(trim($_POST['code_promo']));
	$_SESSION['session_caddie']->update_code_promo($_POST['code_promo']);
	$_SESSION['session_caddie']->update();
}
if (!empty($_GET['code_promo']) && $_GET['code_promo'] == 'delete') {
	$_SESSION['session_caddie']->update_code_promo('');
	$_SESSION['session_caddie']->update();
}
if (is_socolissimo_module_active()) {
	$_SESSION['session_commande']['is_socolissimo_order'] = (!empty($_SESSION['session_caddie']) && !empty($_SESSION['session_caddie']->typeId) && is_type_linked_to_socolissimo($_SESSION['session_caddie']->typeId));
	if (!empty($_SESSION['session_commande']['is_socolissimo_order'])) {
		// Vérification que le service So Colissimo est en fonctionnement, sinon on ne l'utilisera pas
		$_SESSION['session_commande']['is_socolissimo_order'] = checkSoColissimoService();
	}
	if (!empty($_SESSION['session_commande']['is_socolissimo_order'])) {
		// Comme on peut refaire plusieurs fois le cheminement Caddie / Page SoColissimo, on veut forcer la réinitialisation des frais de port, pour éviter que se cumulent les surcoût RDV à domicile
		unset ($_SESSION['session_commande']['delivery_dyforwardingcharges']);
		$_SESSION['session_caddie']->update();
	}
}

if (is_icirelais_module_active()) {
	$_SESSION['session_commande']['is_icirelais_order'] = (!empty($_SESSION['session_caddie']) && !empty($_SESSION['session_caddie']->typeId) && is_type_linked_to_icirelais($_SESSION['session_caddie']->typeId));
}

$form_error_object = new FormError();

if (isset($_POST['func'])) {
	$mode = $_POST['func'];
} else {
	$mode = vb($_GET['func']);
}

if ($mode) {
	switch ($mode) {
		case "enleve" :
			$_SESSION['session_caddie']->delete_line(intval(vb($_GET['ligne'])));
			if (count($_SESSION['session_caddie']->articles) == 0) {
				// plus de produit dans le panier.
				$_SESSION['session_caddie']->init();
			} else {
				$_SESSION['session_caddie']->update();
			}
			redirect_and_die(get_current_url(false));

		case "vide" :
			if (!empty($_COOKIE[$GLOBALS['caddie_cookie_name']])) {
				// Il faut supprimer le cookie qui contient les produits du panier, sinon le caddie est automatiquement rechargé dans init().
				unset($_COOKIE[$GLOBALS['caddie_cookie_name']]);
			}
			$_SESSION['session_caddie']->init();
			unset($_SESSION['session_commande']);
			break;

		case "recalc" :
		case "commande" :
		default :
			$_SESSION['session_caddie']->change_lines_data($_POST);
			if($mode!='recalc') {
				if (!empty($GLOBALS['site_parameters']['mode_transport'])) {
					// Frais de port calculés à partir du poids total ou du montant total d'une commande
					if (empty($_SESSION['session_caddie']->zoneId)) {
						$form_error_object->add('pays_zone', $GLOBALS['STR_ERR_ZONE']);
					} elseif (empty($_SESSION['session_caddie']->typeId)) {
						$form_error_object->add('type', $GLOBALS['STR_ERR_TYPE']);
					} elseif (num_rows(query("SELECT 1 FROM peel_tarifs WHERE type='" . intval($_SESSION['session_caddie']->typeId) . "' AND zone = '" . intval($_SESSION['session_caddie']->zoneId) . "' AND " . get_filter_site_cond('tarifs') . "")) == 0) {
						// Ici on teste la cohérence entre le type et la zone
						$form_error_object->add('type', $GLOBALS['STR_ERR_TYPE']);
					} elseif(!count($_SESSION['session_caddie']->message_caddie)) {
						$redirect_next_step = true;
					}
				} else {
					// Pas de frais de port (c'est la configuration pour tout le site)
					$redirect_next_step = true;
				}
			}
			break;
	}
}

if (!empty($redirect_next_step)) {
	if (!est_identifie() && empty($GLOBALS['site_parameters']['unsubscribe_order_process'])) {
		$_SESSION['session_redirect_after_login'] = get_current_url(true);
		redirect_and_die($GLOBALS['wwwroot'] . '/membre.php');
	} elseif (is_socolissimo_module_active() && !empty($_SESSION['session_commande']['is_socolissimo_order']) && !PEEL_SOCOLISSIMO_IFRAME) {
		$output = getSoColissimoForm($_SESSION['session_utilisateur'], $_SESSION['session_caddie'], true);
		// Le formulaire SO Colissimo doit être envoyé en ISO 8859
		output_light_html_page($output, '', null, 'iso-8859-1');
		die();
	} else {
		redirect_and_die($GLOBALS['wwwroot'] . "/achat/achat_maintenant.php");
	}
}

$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['caddie_affichage_page_columns_count'];
include($GLOBALS['repertoire_modele'] . "/haut.php");

echo get_caddie_content_html($form_error_object, vn($GLOBALS['site_parameters']['mode_transport']));

include($GLOBALS['repertoire_modele'] . "/bas.php");


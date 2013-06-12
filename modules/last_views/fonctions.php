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
// $Id: fonctions.php 37236 2013-06-11 19:10:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}
if (!empty($GLOBALS['site_parameters']['nb_last_views'])) {
	// nombre de derniers produits vus à afficher
	$GLOBALS['nb_last_views'] = intval($GLOBALS['site_parameters']['nb_last_views']);
} else {
	// nombre par défaut
	$GLOBALS['nb_last_views'] = 5;
}
/**
 * Fonction ajoutant les produits consultés dans la liste du client
 *
 * @param integer $product_id
 * @return
 */
function add_product_to_last_views_cookie($product_id)
{
	if (isset($_COOKIE['last_views'])) {
		$tab_last_views = unserialize($_COOKIE['last_views']);
	} else {
		$tab_last_views = array();
	}
	if (!in_array($product_id, $tab_last_views)) {
		// on ajoute le produit à la liste
		$tab_last_views[] = $product_id;
		if (count($tab_last_views) > $GLOBALS['nb_last_views']) {
			// si on a dépassé la taille de la réserve, on supprime le premier produit
			$tab_last_views = array_reverse($tab_last_views);
			array_pop($tab_last_views);
			$tab_last_views = array_reverse($tab_last_views);
		}
		// on crée le cookie avec 1 an de vie
		if($GLOBALS['site_parameters']['force_sessions_for_subdomains']){
			@setcookie('last_views', serialize($tab_last_views), time() + 365 * 24 * 60 * 60, '/', '.'.get_site_domain());
		} else {
			@setcookie('last_views', serialize($tab_last_views), time() + 365 * 24 * 60 * 60, '/');
		}
	}
}

/**
 * Affiche la liste des produits déjà consultés par le client en cours
 *
 * @return
 */
function affiche_last_views()
{
	$output = '';
	$products_html_array = array();
	if (!empty($_COOKIE['last_views'])) {
		$this_tab_last_views = unserialize($_COOKIE['last_views']);
		$tab_last_views =array();
		// On reforme le tableau, cela permet de mettre à jour le nombre de produit sauvegardé dans le cookie, si la configuration du site a changé.
		foreach ($this_tab_last_views as $product_id) {
			if($GLOBALS['nb_last_views'] == count($tab_last_views)  ) {
				break;
			}
			$tab_last_views[] = $product_id;
		}
		
		for ($i = count($tab_last_views) - 1; $i >= 0; $i--) {
			$product_object = new Product($tab_last_views[$i], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
			$product_html = get_product_in_container_html($product_object, true);
			if (!empty($product_html) && $product_object->on_gift == 0) {
				// si le produit existe et est activé (en ligne)
				$products_html_array[] = get_product_in_container_html($product_object, true);
			} else {
				unset($tab_last_views[$i]); // on supprime une fois ce produit de la liste
				// et on met à jour la liste dans le cookie
				if($GLOBALS['site_parameters']['force_sessions_for_subdomains']){
					@setcookie('last_views', serialize($tab_last_views), time() + 365 * 24 * 60 * 60, '/', '.'.get_site_domain());
				} else {
					@setcookie('last_views', serialize($tab_last_views), time() + 365 * 24 * 60 * 60, '/');
				}
			}
			unset($product_object);
		}
	}
	if (is_rollover_module_active ()) {
		if (vn($GLOBALS['site_parameters']['type_rollover']) == 1) {
			$output .= affiche_menu_deroulant_1('scrollerdiv_last_views', $products_html_array);
		} elseif (vn($GLOBALS['site_parameters']['type_rollover']) == 2) {
			$output .= affiche_menu_deroulant_2('scrollerdiv_last_views', $products_html_array);
		}
	} else {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/last_views.tpl');
		$tpl->assign('STR_MODULE_LAST_VIEWS_NO_LAST_VIEWS', $GLOBALS['STR_MODULE_LAST_VIEWS_NO_LAST_VIEWS']);
		$tpl->assign('products', $products_html_array);
		$output = $tpl->fetch();
	}
	return $output;
}

?>
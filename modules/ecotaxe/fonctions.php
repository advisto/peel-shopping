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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

/**
 * Renvoie les éléments de menu affichables
 *
 * @param array $params
 * @return
 */
function ecotaxe_hook_admin_menu_items($params) {
	$result['menu_items']['manage_payments'][$GLOBALS['administrer_url'] . '/ecotaxes.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_EXOTAXE"];
	return $result;
}

/**
 * Chargement des informations produit manquantes si nécessaire
 *
 * @param array $params
 * @return
 */
function ecotaxe_hook_product_init_post(&$params) {
	if (!empty($params['product_infos']) && isset($params['product_infos']['ecotaxe_ht']) && isset($params['product_infos']['ecotaxe_ttc'])) {
		$params['this']->ecotaxe_ht = $params['product_infos']['ecotaxe_ht'];
		$params['this']->ecotaxe_ttc = $params['product_infos']['ecotaxe_ttc'];
	} else {
		if (!empty($params['this']->id_ecotaxe)) {
			$eco = get_ecotax_object($params['this']->id_ecotaxe);
		}
		if (!empty($eco)) {
			if ($eco->coefficient >0 ) {
				// On a défini un coefficient pour cette taxe. On prend la valeur en pourcentage prioritairement.
				// Calcul de l'écotaxe à partir du poids du produit.
				$params['this']->ecotaxe_ht = $eco->coefficient * (($params['this']->poids)/1000);
				$params['this']->ecotaxe_ttc = $eco->coefficient * (($params['this']->poids)/1000) * (1+$params['this']->tva/100);
			} else {
				// Si coefficient est vide, on prend les valeurs fixes
				$params['this']->ecotaxe_ht = $eco->prix_ht;
				$params['this']->ecotaxe_ttc = $eco->prix_ttc;
			}
		}
	}
}

/**
 * get_ecotax_object()
 *
 * @param mixed $id
 * @return object
 */
function get_ecotax_object($id) {
	static $eco;
	$cache_id = $id;
	if (!isset($eco[$cache_id])) {
		$query = query('SELECT prix_ht, prix_ttc,coefficient
			FROM peel_ecotaxes
			WHERE id = "' . intval($id) . '" AND ' . get_filter_site_cond('ecotaxes'));
		$eco[$cache_id] = fetch_object($query);
	}
	return $eco[$cache_id];
}

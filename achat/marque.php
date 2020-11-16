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
// $Id: marque.php 64741 2020-10-21 13:48:51Z sdelaporte $
define('IN_SEARCH_BRAND', true);
include("../configuration.inc.php");
if((!empty($GLOBALS['site_parameters']['price_hide_if_not_loggued']) && (!est_identifie() || (!a_priv('util*') && !a_priv('admin*') && !a_priv('reve*')) || a_priv('*refused') || a_priv('*wait'))) || !empty($GLOBALS['site_parameters']['brand_hide'])) {
	if(empty($_GET['brand'])) {
		redirect_and_die($GLOBALS['wwwroot']);
	}
}
$GLOBALS['page_name'] = 'marque';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_BRAND'];

if(!empty($_GET['brand']) && empty($_GET['id_cat']) && empty($_GET['id'])) {
	$sql = "SELECT id
		FROM peel_marques
		WHERE nom_" . $_SESSION['session_langue'] . " LIKE '" . real_escape_string(str_replace('-', '_', $_GET['brand'])) . "' AND " . get_filter_site_cond('marques');
	$query = query($sql);
	if ($result = fetch_assoc($query)) {
		$id_marque = $result['id'];
		// On défini le GET['id'] pour permettre la récupération du nom de la marque dans la fonction affiche_meta
		$_GET['id'] = $id_marque;
	} else {
		if (!empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
			// Récupération de la langue par défaut
			$main_content_lang = $GLOBALS['site_parameters']['main_content_lang'];
			$sql = "SELECT id
					FROM peel_marques
					WHERE nom_" . $main_content_lang . " LIKE '" . real_escape_string(str_replace('-', '_', $_GET['brand'])) . "' AND " . get_filter_site_cond('marques');
			$query = query($sql);
			if ($result = fetch_assoc($query)) {
				$id_marque = $result['id'];
				// On défini le GET['id'] pour permettre la récupération du nom de la marque dans la fonction affiche_meta
				$_GET['id'] = $id_marque;
			} else {
				redirect_and_die(get_url('/achat/marque.php'), true);
			}
		} else {
			redirect_and_die(get_url('/achat/marque.php'), true);
		}
	}
} else {
	$id_marque = vn($_GET['id']);
	$id_categorie = vn($_GET['id_cat']);
	if(!empty($id_marque) && empty($id_categorie)) {
		redirect_and_die(get_url('/achat/marque.php', array('id' => $id_marque)), true);
	}
}
$output = '';

if(!empty($id_marque)) {
	// Variable de configuration pour afficher uniquement les catégories liées à la marque
	if (empty($GLOBALS['site_parameters']['display_brand_category']) || (!empty($GLOBALS['site_parameters']['hide_brand_category_by_id_brand']) && (in_array($id_marque, $GLOBALS['site_parameters']['hide_brand_category_by_id_brand'])))) {
		// Affichage d'une marque avec ses produits
		$output .= get_brand_description_html($id_marque, true, false) . '<div class="clearfix"></div>' . affiche_produits($id_marque, 2, 'brand', $GLOBALS['site_parameters']['nb_produit_page'], 'general', true);
	} else {
		// Affichage des catégories liées à la marque
		if(empty($id_categorie)) {
			$output .= get_brand_description_html($id_marque, true, false) . '<div class="clearfix"></div>' . get_products_list_brief_html(vb($GLOBALS['site_parameters']['index_id_category_brand']), true, 'category', $id_marque);
		} else {		
			$hook_result = call_module_hook('index_form_template_data', array(), 'array');
			$search ='';
			foreach($hook_result as $this_key => $this_value) {
				if($this_key == 'tableau_recherche_avancee'){
					$search = $this_value;
				}
			}
			//  Nous ne souhaitons pas afficher la recherche avancée dans la marque ni la description de la marque dans les sous-catégories de la marque
			// $output .= get_brand_description_html($id_marque, true, false) . '<div class="clearfix"></div>' .  get_products_list_brief_html($id_categorie, true, 'category', $id_marque) . $search ;
			$output .= get_brand_title($id_marque, true, false) . get_products_list_brief_html($id_categorie, true, 'category', $id_marque);
		}
	}
} else {
	// Affichage de toutes les marques disponibles sur le site
	$output .= get_brand_description_html(null, true, true);
}
include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


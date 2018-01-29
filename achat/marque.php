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
// $Id: marque.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_SEARCH_BRAND', true);
include("../configuration.inc.php");
if((!empty($GLOBALS['site_parameters']['price_hide_if_not_loggued']) && (!est_identifie() || (!a_priv('util*') && !a_priv('admin*') && !a_priv('reve*')) || a_priv('*refused') || a_priv('*wait'))) || !empty($GLOBALS['site_parameters']['brand_hide'])) {
	if(empty($_GET['brand'])) {
		redirect_and_die($GLOBALS['wwwroot']);
	}
}
$GLOBALS['page_name'] = 'marque';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_BRAND'];

if(!empty($_GET['brand'])) {
	$sql = "SELECT id
		FROM peel_marques
		WHERE nom_" . $_SESSION['session_langue'] . " LIKE '" . real_escape_string(str_replace('-', '_', $_GET['brand'])) . "' AND " . get_filter_site_cond('marques');
	$query = query($sql);
	if ($result = fetch_assoc($query)) {
		$id_marque = $result['id'];
		// On défini le GET['id'] pour permettre la récupération du nom de la marque dans la fonction affiche_meta
		$_GET['id'] = $id_marque;
	}
} else {
	$id_marque = vn($_GET['id']);
	if(!empty($id_marque)) {
		redirect_and_die(get_url('/achat/marque.php', array('id' => $id_marque)), true);
	}
}
$output = '';

if(!empty($id_marque)) {
	// Affichage d'une marque avec ses produits
	$output .= get_brand_description_html($id_marque, true, false) . '<div class="clearfix"></div>' . affiche_produits($id_marque, 2, 'brand', $GLOBALS['site_parameters']['nb_produit_page'], 'general', true);
} else {
	// Affichage de toutes les marques disponibles sur le site
	$output .= get_brand_description_html(null, true, true);
}
include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


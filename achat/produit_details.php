<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produit_details.php 67579 2021-07-12 14:55:44Z jlesergent $
define('IN_CATALOGUE_PRODUIT', true);

include("../configuration.inc.php");
$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['product_details_page_columns_count'];
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_PRODUCT'];
$GLOBALS['page_name'] = 'product';

$output = '';
$product_infos = array();
$form_error_object = new FormError();

if (function_exists('set_product_id')) {
	$product_id = set_product_id($_GET);
	if (!empty($product_id)) {
		$_GET['id'] = $product_id;
	}
}
if (empty($_GET['id'])) {
	// Si aucun produit n'est spécifié, retour à la page d'accueil
	redirect_and_die(get_url('/'), true);
} else {
	$_GET['id'] = intval($_GET['id']);
}

// On récupère l'url précédement visitée dans $_SESSION['product_details_previous_page_url'] pour que lors de l'appel à get_cart_popup_div(), on puisse rediriger vers cette url avec le bouton "Continuer mes achats". 
if(!empty($GLOBALS['site_parameters']['cart_popup_previous_page_success_href']) && !empty($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] != get_current_url(true)) ) {
	$_SESSION['product_details_previous_page_url'] = $_SERVER['HTTP_REFERER'];
}


if(!empty($GLOBALS['site_parameters']['allow_multiple_product_url_with_category'])) {
	// Autorisation de plusieurs urls pour ce produit dans le cas où il est associé à plusieurs catégories. Cette configuration est désactivée par défaut
	// Si le produit est bien associé à $_GET['catid']
	$query = query("SELECT 1
		FROM peel_produits_categories pc 
		WHERE pc.produit_id ='" . intval($_GET['id']) . "' AND pc.categorie_id=" . intval(vn($_GET['catid'])));

	if (num_rows($query)>0) {
		// le produit appartient à la catégorie demandée dans l'url => on spécifie l'id et le nom de la catégorie pour la classe Product
		$product_infos['categorie_id'] = intval(vn($_GET['catid']));
		$product_infos['categorie'] = get_category_name($product_infos['categorie_id']);
	}
}

$product_infos = get_product_infos($_GET['id']);
$product_object = new Product($_GET['id'], $product_infos, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
if (!empty($_GET['step']) && check_if_module_active('attributs')) {
	call_module_hook('attribut_step', array('product_object' => $product_object, 'frm'=> $_POST, 'step'=> $_GET['step']));
	$_SESSION['session_attributs_step'][$_GET['step']] = get_attribut_list_from_post_data($product_object, $_POST);
}
$url = $product_object->get_product_url();
if (empty($product_object->id) || ((!a_priv("admin_product") && !a_priv("reve")) && $product_object->on_reseller == 1) || $product_object->technical_code == "over_cost") {
	// Si aucun produit n'est trouvé, retour à la page d'accueil
	redirect_and_die(get_url('/'), true);
}
if (check_if_module_active('url_rewriting')) {
	if (StringMb::strpos($_SERVER['REQUEST_URI'], 'id=') !== false) {
		if (empty($url)) {
			$url = get_url('/');
		}
		redirect_and_die($url, true);
	} elseif (!empty($url) && $url != get_current_url(false)) {
		// L'URL sans le get n'est pas comme elle est censée être => on redirige avec une 301
		$theoretical_current_url = $url;
		redirect_and_die($theoretical_current_url, true);
	} elseif (!empty($product_infos['categorie_id']) && empty($url)) {
		// si l'url n'a pas été calculé par la class Product
		// Si un produit n'a pas de catégorie associé (ce qui n'est pas un cas normal) la class Product ne peut pas trouver le produit. Il faut pouvoir consulter un produit même si il n'est pas associé à une catégorie, ce qui peut être utile dans des cas spécifiques.
		// Si l'url n'a pas été calculée par la class Product pour une raison quelconque
		redirect_and_die(get_url('/'));
	}
} else {
	$_GET['catid'] = $product_object->categorie_id;
}

$output .= call_module_hook('product_details_show', array('id' => intval($_GET['id']), 'product_object' => $product_object), 'string');

// Gestion des erreurs de téléchargement des fichiers (cas d'attribut d'upload)
if (!empty($_SESSION["session_display_popup"]["upload_error_text"])) {
	$form_error_object->add('upload_option_error', $_SESSION["session_display_popup"]["upload_error_text"]);
	// On vient d'afficher le message d'alerte de problème de téléchargement d'image, donc on le désactive pour l'avenir
	unset($_SESSION["session_display_popup"]["upload_error_text"]);
}

$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['product_details_page_columns_count'];

if ($form_error_object->count() > 0) {
	foreach ($form_error_object->error as $key => $error) {
		if ($key === "confirm_ok") {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $error))->fetch();
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $error))->fetch();
		}
	}
}
if (isset($_POST['submit_suggest_comment_1'])){
    if(!empty($_POST["comment_details"]) && isset($_POST["comment_details"])){
        $r=query("SELECT id_marque 
			FROM peel_produits 
			WHERE id=".intval(vn($_GET["id"]))); 
        $row=$r->fetch_assoc(); set_suggestion_commentaire_details($_POST['comment_details'],vb($_GET["catid"]),$row["id_marque"],$_SESSION['session_utilisateur']);        
    }
	
}
$output .= get_produit_details_html($product_object, intval(vb($_GET['cId'])), 50, 60, vn($_GET['product_ordered']));
if ((isset($product_object->reference) && !empty($product_object->reference)) || (isset($product_object->name) && !empty($product_object->name))) $GLOBALS['DOC_TITLE'] = $product_object->reference . " | " . $product_object->name;

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


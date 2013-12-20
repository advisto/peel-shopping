<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produit_details.php 39392 2013-12-20 11:08:42Z gboussin $
include("../configuration.inc.php");

$output = '';
if (empty($_GET['id'])) {
	// Si aucun produit n'est spécifié, retour à la page d'accueil
	redirect_and_die($GLOBALS['wwwroot'] . "/", true);
}
$product_infos['categorie_id'] = intval(vn($_GET['catid']));
$product_infos['categorie'] = get_category_name($product_infos['categorie_id']) ;
$product_object = new Product($_GET['id'], $product_infos, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
$url = $product_object->get_product_url();
if (empty($product_object->id)) {
	// Si aucun produit n'est trouvé, retour à la page d'accueil
	redirect_and_die($GLOBALS['wwwroot'] . "/", true);
}
if (is_module_url_rewriting_active() && String::strpos($_SERVER['REQUEST_URI'], 'id=') !== false) {
	if (empty($url)) {
		$url = $GLOBALS['wwwroot'] . "/";
	}
	redirect_and_die($url, true);
}
if (is_module_url_rewriting_active ()) {
	if (!empty($url) && $url != get_current_url(false)) {
		// L'URL sans le get n'est pas comme elle est censée être => on redirige avec une 301
		$theoretical_current_url = $url;
		redirect_and_die($theoretical_current_url, true);
	} elseif (empty($url)) {
		// Si l'url n'a pas été calculée par la class Product pour une raison quelconque
		redirect_and_die($GLOBALS['wwwroot'] . "/");
	}
} else {
	$_GET['catid'] = $product_object->categorie_id;
}

if (is_last_views_module_active ()) {
	add_product_to_last_views_cookie(intval($_GET['id'])); // on actualiste la liste des produits visités
}
// Insertion de la demande d'infos de stock
$form_error_object = new FormError();
if ((isset($_POST["validate"]))) { // si on valide le formulaire d'info stock
	$form_error_object->valide_form($_POST,
		array('email' => $GLOBALS['STR_ERR_EMAIL']));
	if (!$form_error_object->has_error('email')) {
		$_POST['email'] = trim($_POST['email']);
		if (!EmailOK($_POST['email'])) {
			// si il y a un email on teste l'email
			$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
		}
	}
	if (!$form_error_object->count()) {
		if (insere_alerte($_POST)) {
			$form_error_object->add('confirm_ok', $GLOBALS['STR_REQUEST_OK']);
		} else {
			$form_error_object->add('confirm_ko', $GLOBALS['STR_ERR_EMAIL_BAD']);
		}
	}
}

// Gestion des erreurs de téléchargement des fichiers (cas d'attribut d'upload)
if (!empty($_SESSION["session_display_popup"]["upload_error_text"])) {
	$form_error_object->add('upload_option_error', $_SESSION["session_display_popup"]["upload_error_text"]);
	// On vient d'afficher le message d'alerte de problème de téléchargement d'image, donc on le désactive pour l'avenir
	unset($_SESSION["session_display_popup"]["upload_error_text"]);
}

define('IN_CATALOGUE_PRODUIT', true);
$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['product_details_page_columns_count'];

if ($form_error_object->count() > 0) {
	foreach ($form_error_object->error as $key => $error) {
		if ($key == "confirm_ok") {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $error))->fetch();
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $error))->fetch();
		}
	}
}
$output .= get_produit_details_html(intval($_GET['id']), intval(vb($_GET['cId'])));

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
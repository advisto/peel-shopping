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
// $Id: index.php 66961 2021-05-24 13:26:45Z sdelaporte $
include("../configuration.inc.php");

if (isset($_GET['catid']) && empty($_GET['catid'])) {
	// URL invalide
	redirect_and_die(get_url('/'), true);
}
if(isset($_POST["submit_suggest_comment_details_1"])){   
    if(isset($_POST["comment_details"]) && !empty($_POST["comment_details"])){      set_suggestion_commentaire_details(vb($_POST["comment_details"]),vb($_GET["catid"]),0,$_SESSION['session_utilisateur']);       
    }
}
if (empty($_GET['catid']) && !empty($GLOBALS['site_parameters']['disallow_main_category']) && empty($_GET['convert_gift_points'])) {
	// Si pas autorisé de voir /lire/ , retour à la page d'accueil avec redirection 301
	// Si convert_gift_points est rempli, alors la page qui s'affiche est celle des produits cadeaux et pas la page qui liste toutes les catégories, donc le paramètre disallow_main_category ne doit pas s'appliquer si convert_gift_points
	redirect_and_die(get_url('/'), true);
}

$catid = intval(vn($_GET['catid']));
define("IN_CATALOGUE", true);
$GLOBALS['page_name'] = 'catalogue';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_CATALOG'];
if(!empty($GLOBALS['site_parameters']['user_session_mandatory_info_in_catalog_pages']) && empty($_SESSION['session_utilisateur'][$GLOBALS['site_parameters']['user_session_mandatory_info_in_catalog']])) {
	// Une information indispensable n'est pas  présente sur en session
	redirect_and_die($GLOBALS['site_parameters']['url_if_user_session_mandatory_info_is_missing_in_catalog_pages']);
}
if (!empty($catid)) {
	// On n'affiche que si la catégorie active, ou si on a les droits d'administration
	$sql = "SELECT c.nom_" . $_SESSION['session_langue'] . " AS nom, c.technical_code
		FROM peel_categories c
		WHERE c.id='" . intval($catid) . "' AND " . get_filter_site_cond('categories', 'c') . "" . (!a_priv("admin_products", false)?' AND c.etat = "1"':'');
	$cat_query = query($sql);
	if ($cat = fetch_assoc($cat_query)) {
		//get_default_content remplace le contenu par la langue par défaut si les conditions sont réunis
		if (!empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
			$cat = get_default_content($cat, intval($catid), 'categories');
		}
		if (check_if_module_active('url_rewriting') && get_product_category_url($catid, $cat['nom']) != get_current_url(false) && empty($_GET['page_offline'])) {
			// L'URL sans le get n'est pas comme elle est censée être => on redirige avec une 301
			$theoretical_current_url = (!empty($_GET['page'])?get_product_category_url($catid, $cat['nom'], true, true) . 'page=' . intval($_GET['page']):get_product_category_url($catid, $cat['nom']));
			redirect_and_die($theoretical_current_url, true);
		}
	} else {
		// Catégorie pas trouvée ou désactivée
		redirect_and_die(get_url('/'));
	}
}
$form_error_object = new FormError();
// Gestion des erreurs de téléchargement des fichiers (cas d'attribut d'upload)
if (!empty($_SESSION["session_display_popup"]["upload_error_text"])) {	
	$form_error_object->add('upload_option_error', $_SESSION["session_display_popup"]["upload_error_text"]);
	// On vient d'afficher le message d'alerte de problème de téléchargement d'image, donc on le désactive pour l'avenir
	unset($_SESSION["session_display_popup"]["upload_error_text"]);
}
$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['achat_index_page_columns_count'];

$output = affiche_contenu_html('header_product_list', true);

// Une redirection est susceptible d'être faite dans la fonction get_products_list_brief_html, il faut donc l'appeler avant include($GLOBALS['repertoire_modele'] . "/haut.php").
if(!empty($_GET['qr_code_display_products']) && !empty($GLOBALS['site_parameters']['display_suspended_products'])){
	$output = get_products_list_brief_html($catid, empty($_GET['convert_gift_points']) && empty($GLOBALS['site_parameters']['subcategories_display_disable']), 'online_and_suspended_products');
} else {
	$output = get_products_list_brief_html($catid, empty($_GET['convert_gift_points']) && empty($GLOBALS['site_parameters']['subcategories_display_disable']), (empty($_GET['convert_gift_points'])?'category':'convert_gift_points'));
}
include($GLOBALS['repertoire_modele'] . "/haut.php");
if ($form_error_object->count() > 0) {
	foreach ($form_error_object->error as $key => $message) {
		if ($key === "confirm_ok") {
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $message))->fetch();
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $message))->fetch();
		}
	}
}
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


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
// $Id: index.php 37032 2013-05-29 22:21:19Z gboussin $
include("../configuration.inc.php");

if (isset($_GET['catid']) && empty($_GET['catid'])) {
	redirect_and_die($GLOBALS['wwwroot'] . "/");
}

$catid = intval(vn($_GET['catid']));
define("IN_CATALOGUE", true);
$page_name = 'catalogue';

if (!empty($catid)) {
	// On n'affiche que si la catégorie active, ou si on a les droits d'administration
	$sql = "SELECT c.nom_" . $_SESSION['session_langue'] . " AS nom, c.technical_code
		FROM peel_categories c
		WHERE c.id='" . intval($catid) . "'" . (!a_priv("admin_products", false)?' AND c.etat = "1"':'');
	$cat_query = query($sql);
	if ($cat = fetch_assoc($cat_query)) {
		if (is_module_url_rewriting_active() && get_product_category_url($catid, $cat['nom']) != get_current_url(false)) {
			// L'URL sans le get n'est pas comme elle est censée être => on redirige avec une 301
			$theoretical_current_url = (!empty($_GET['page'])?get_product_category_url($catid, $cat['nom'], true, true) . 'page=' . intval($_GET['page']):get_product_category_url($catid, $cat['nom']));
			redirect_and_die($theoretical_current_url, true);
		}
	} else {
		// Catégorie pas trouvée ou désactivée
		redirect_and_die($GLOBALS['wwwroot'] . "/");
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
$output = get_products_list_brief_html($catid, empty($_GET['convert_gift_points']), (empty($_GET['convert_gift_points'])?'category':'convert_gift_points'));

include($GLOBALS['repertoire_modele'] . "/haut.php");
if ($form_error_object->count() > 0) {
	foreach ($form_error_object->error as $key => $message) {
		if ($key == "confirm_ok") {
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $message))->fetch();
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $message))->fetch();
		}
	}
}
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
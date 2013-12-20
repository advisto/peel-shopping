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
// $Id: sitemap.php 39392 2013-12-20 11:08:42Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_webmastering");

if (!empty($_GET['encoding'])) {
	$file_encoding = $_GET['encoding'];
} else {
	$file_encoding = 'utf-8';
}
$DOC_TITLE = $GLOBALS['STR_ADMIN_SITEMAP_TITLE'];
$form_error_object = new FormError();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_sitemap.tpl');
$tpl->assign('href', $GLOBALS['wwwroot'] . '/sitemap.xml');
$tpl->assign('STR_ADMIN_SITEMAP_TITLE', $GLOBALS['STR_ADMIN_SITEMAP_TITLE']);
$tpl->assign('STR_ADMIN_SITEMAP_OPEN', $GLOBALS['STR_ADMIN_SITEMAP_OPEN']);
echo $tpl->fetch();


switch (vb($_REQUEST['mode'])) {
	case "lire" :
		if (!verify_token($_SERVER['PHP_SELF'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			// Création d'un fichier sitemap.xml par sous-domaine et domaine. Le fichier sitemap sera appeler en front office via le fichier php get_sitemap.php
			// Les urls des boutiques dans un sous-dossier ne sont pas correctement généré.
			$langs_array_by_subdomain = array();
			foreach($GLOBALS['langs_array_by_wwwroot'] as $this_wwwroot=>$this_lang_array) {
				// Création du tableau langue par sous domaine
				$langs_array_by_subdomain[get_site_domain(false, $this_wwwroot, false)][] = $this_lang_array[0];
			}
			// Format du tableau
			// $langs_array_by_subdomain = array(domain1 => array('lng1', 'lng2', 'lng3'), domain2 => array('lng4'), domain3 => array('lng5','lng6'));
			foreach ($langs_array_by_subdomain as $this_domain=>$this_lang_array) {
				// Création des fichiers sitemap.
				create_google_sitemap($this_domain, $this_lang_array, $file_encoding);
			}
		} elseif ($form_error_object->has_error('token')) {
			echo $form_error_object->text('token');
		}
		form2xml();
		break;

	default :
		form2xml();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/* FONCTIONS */

/**
 *
 * @param string $this_wwwroot
 * @param string $this_wwwroot_lang_array
 * @param string $file_encoding
 * @return
 */
function create_google_sitemap($this_wwwroot, $this_wwwroot_lang_array, $file_encoding)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_google_sitemap.tpl');
	$tpl->assign('GENERAL_ENCODING', GENERAL_ENCODING);
	$sitemap = '';
	$tpl_products = array();
	$account_register_url_array = array();
	$product_category_url_array = array();
	$content_category_url_array = array();
	$account_url_array = array();
	$wwwroot_array = array();
	$tpl->assign('date', date("Y-m-d"));
	foreach($this_wwwroot_lang_array as $this_lang) {
		// Modification de l'environnement de langue
		set_lang_configuration_and_texts($this_lang, vb($GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$this_lang]), true, false, !empty($GLOBALS['load_admin_lang']), true, defined('SKIP_SET_LANG'));

		// génération des liens
		$select = "SELECT p.id AS produit_id, c.id AS categorie_id, p.nom_" . $this_lang . " AS name, c.nom_" . $this_lang . " AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
			INNER JOIN peel_categories c ON c.id = pc.categorie_id
			WHERE p.etat=1";
		$req = query($select);
		while ($row = fetch_assoc($req)) {
			$product_object = new Product($row['produit_id'], $row, true, null, true, !is_micro_entreprise_module_active());
			$tpl_products[] = $product_object->get_product_url();
			unset($product_object);
		}
		$account_register_url_array[] = get_account_register_url();
		$product_category_url_array[] = get_product_category_url();
		$content_category_url_array[] = get_content_category_url();
		$account_url_array[] = get_account_url();
		$wwwroot_array[] = $GLOBALS['wwwroot'];
	}
	$tpl->assign('account_register_url_array', $account_register_url_array);
	$tpl->assign('product_category_url_array', $product_category_url_array);
	$tpl->assign('content_category_url_array', $content_category_url_array);
	$tpl->assign('account_url_array', $account_url_array);
	$tpl->assign('wwwroot_array', $wwwroot_array);
	$tpl->assign('products', $tpl_products);
	$sitemap = $tpl->fetch();
	// Création du fichier. Ce fichier sera lu par le fichier php /get_sitemap.xml. Une règle de réécriture dans le htaccess rend cet appel transparent pour le client.
	$xml_filename = $GLOBALS['dirroot'] . "/sitemap_" . substr(md5($this_wwwroot), 0, 4) . ".xml";
	$create_xml = String::fopen_utf8($xml_filename, "wb");
	fwrite($create_xml, String::convert_encoding($sitemap, $file_encoding, GENERAL_ENCODING));
	fclose($create_xml);

	// rétablissement de la langue du back office pour l'affichage du message de confirmation
	set_lang_configuration_and_texts($_SESSION['session_langue'], vb($GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$_SESSION['session_langue']]), true, false, !empty($GLOBALS['load_admin_lang']), true, defined('SKIP_SET_LANG'));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_SITEMAP_MSG_CREATED_OK']))->fetch();
	echo '<p>'.$GLOBALS['STR_ADMIN_SITEMAP_CREATED_REPORT'].'<br /><br />' . nl2br($select) . '</p>';
}

/**
 * form2xml()
 *
 * @return
 */
function form2xml()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_sitemap_form2xml.tpl');
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF']));
	$tpl->assign('STR_ADMIN_SITEMAP_CREATE_BUTTON', $GLOBALS['STR_ADMIN_SITEMAP_CREATE_BUTTON']);
	echo $tpl->fetch();
}

?>
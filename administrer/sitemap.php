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
// $Id: sitemap.php 36927 2013-05-23 16:15:39Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_webmastering");

if (!empty($_GET['encoding'])) {
	$file_encoding = $_GET['encoding'];
} else {
	$file_encoding = 'utf-8';
}
$DOC_TITLE = "";
$form_error_object = new FormError();

include("modeles/haut.php");

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_sitemap.tpl');
$tpl->assign('href', $GLOBALS['wwwroot'] . '/sitemap.xml');
$tpl->assign('STR_ADMIN_SITEMAP_TITLE', $GLOBALS['STR_ADMIN_SITEMAP_TITLE']);
$tpl->assign('STR_ADMIN_SITEMAP_OPEN', $GLOBALS['STR_ADMIN_SITEMAP_OPEN']);
echo $tpl->fetch();

$xml_filename = $GLOBALS['dirroot'] . "/sitemap.xml";

switch (vb($_REQUEST['mode'])) {
	case "lire" :
		if (!verify_token($_SERVER['PHP_SELF'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			create_google_sitemap($xml_filename, $file_encoding);
		} elseif ($form_error_object->has_error('token')) {
			echo $form_error_object->text('token');
		}
		form2xml();
		break;

	default :
		form2xml();
		break;
}

include("modeles/bas.php");

/* FONCTIONS */

/**
 * create_google_sitemap()
 *
 * @param string $xml_filename
 * @param string $file_encoding
 * @return
 */
function create_google_sitemap($xml_filename, $file_encoding)
{
	$page_encoding = GENERAL_ENCODING;

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_google_sitemap.tpl');
	$tpl->assign('GENERAL_ENCODING', GENERAL_ENCODING);
	$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
	$tpl->assign('date', date("Y-m-d"));
	$tpl->assign('url_enregistrement', get_account_register_url(false, false));

	$tpl_products = array();
	$select = "SELECT p.id AS produit_id, c.id AS categorie_id, p.nom_" . $_SESSION['session_langue'] . " AS name, c.nom_" . $_SESSION['session_langue'] . " AS categorie
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
	$tpl->assign('products', $tpl_products);
	$sitemap = $tpl->fetch();

	$create_xml = String::fopen_utf8($xml_filename, "wb");
	fwrite($create_xml, String::convert_encoding($sitemap, $file_encoding, GENERAL_ENCODING));
	fclose($create_xml);
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
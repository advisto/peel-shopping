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
// $Id: urllist.php 36927 2013-05-23 16:15:39Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_webmastering");

if (!empty($_GET['encoding'])) {
	$file_encoding = $_GET['encoding'];
} else {
	$file_encoding = 'utf-8';
}
$DOC_TITLE = $GLOBALS['STR_ADMIN_URLLIST_TITLE'];
$form_error_object = new FormError();

include("modeles/haut.php");
$tpl = $GLOBALS['tplEngine']->createTemplate('admin_urllist_table.tpl');
$tpl->assign('STR_ADMIN_URLLIST_CREATE_TITLE', $GLOBALS['STR_ADMIN_URLLIST_CREATE_TITLE']);
echo $tpl->fetch();

$xml_file = $GLOBALS['dirroot'] . "/urllist.txt";

switch (vb($_REQUEST['mode'])) {
	case "lire" :
		if (!verify_token($_SERVER['PHP_SELF'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			create_yahoo_sitemap($xml_file, $file_encoding);
			$tpl = $GLOBALS['tplEngine']->createTemplate('admin_urllist_ul.tpl');
			$tpl->assign('urllist_href', $GLOBALS['wwwroot'] . '/urllist.txt');
			$tpl->assign('STR_ADMIN_URLLIST_READ_SITEMAP', $GLOBALS['STR_ADMIN_URLLIST_READ_SITEMAP']);
			echo $tpl->fetch();
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
 * create_yahoo_sitemap()
 *
 * @param string $xml_file
 * @param string $file_encoding
 * @return
 */
function create_yahoo_sitemap($xml_file, $file_encoding)
{
	$sitemap = '';

	$sitemap .= $GLOBALS['wwwroot'] . "\r\n";
	$sitemap .= $GLOBALS['wwwroot'] . "/achat/\r\n";
	$sitemap .= $GLOBALS['wwwroot'] . "/lire/\r\n";
	$sitemap .= $GLOBALS['wwwroot'] . "/membre.php\r\n";
	$sitemap .= $GLOBALS['wwwroot'] . "/compte.php\r\n";

	$select = "SELECT p.id AS produit_id, c.id AS categorie_id, p.nom_" . $_SESSION['session_langue'] . " as produit, c.nom_" . $_SESSION['session_langue'] . " AS categorie
		FROM peel_produits p, peel_produits_categories pc, peel_categories c
		WHERE p.id = pc.produit_id AND c.id = pc.categorie_id AND p.etat=1";
	$req = query($select);
	while ($row = fetch_assoc($req)) {
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);

		$texte1 = strtr($row['produit'], $trans_tbl);
		$texte1 = str_replace("&", "", $texte1);

		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);

		$texte2 = strtr($row['categorie'], $trans_tbl);
		$texte2 = str_replace("&", "", $texte2);

		$sitemap .= get_product_url($row['produit_id'], $row['produit'], $row['categorie_id'], $row['categorie']) . "\r\n";
	}
	$create_xml = String::fopen_utf8($xml_file, "wb");
	fwrite($create_xml, String::convert_encoding($sitemap, $file_encoding, GENERAL_ENCODING));
	fclose($create_xml);
}

/**
 * form2xml()
 *
 * @return
 */
function form2xml()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_urllist_form2xml.tpl');
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF']));
	$tpl->assign('STR_ADMIN_URLLIST_GENERATE_SITEMAP', $GLOBALS['STR_ADMIN_URLLIST_GENERATE_SITEMAP']);
	echo $tpl->fetch();
}

?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: urllist.php 39495 2014-01-14 11:08:09Z sdelaporte $
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

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
$tpl = $GLOBALS['tplEngine']->createTemplate('admin_urllist_table.tpl');
$tpl->assign('STR_ADMIN_URLLIST_CREATE_TITLE', $GLOBALS['STR_ADMIN_URLLIST_CREATE_TITLE']);
echo $tpl->fetch();


switch (vb($_REQUEST['mode'])) {
	case "lire" :
		if (!verify_token($_SERVER['PHP_SELF'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$langs_array_by_subdomain = array();
			foreach($GLOBALS['langs_array_by_wwwroot'] as $this_wwwroot=>$this_lang_array) {
				// Création du tableau langue par sous domaine
				$langs_array_by_subdomain[get_site_domain(false, $this_wwwroot, false)][] = $this_lang_array[0];
			}
			// Format du tableau
			// $langs_array_by_subdomain = array(domain1 => array('lng1', 'lng2', 'lng3'), domain2 => array('lng4'), domain3 => array('lng5','lng6'));
			foreach ($langs_array_by_subdomain as $this_domain=>$this_lang_array) {
				// Création des fichiers sitemap.
				create_yahoo_sitemap($this_domain, $this_lang_array, $file_encoding);
			}

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

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/* FONCTIONS */

/**
 * create_yahoo_sitemap()
 *
 * @param string $xml_file
 * @param string $file_encoding
 * @return
 */
function create_yahoo_sitemap($this_wwwroot, $this_wwwroot_lang_array, $file_encoding)
{
	$sitemap = '';
	foreach($this_wwwroot_lang_array as $this_lang) {
		// Modification de l'environnement de langue
		set_lang_configuration_and_texts($this_lang, vb($GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$this_lang]), true, false, !empty($GLOBALS['load_admin_lang']), true, defined('SKIP_SET_LANG'));
		
		$sitemap .= $GLOBALS['wwwroot'] . "\r\n";
		$sitemap .= $GLOBALS['wwwroot'] . "/membre.php\r\n";
		$sitemap .= get_product_category_url() . "\r\n";
		$sitemap .= get_content_category_url() . "\r\n";
		$sitemap .= get_account_register_url() . "\r\n";
		$sitemap .= get_account_url() . "\r\n";
		
		$select = "SELECT p.id AS produit_id, c.id AS categorie_id, p.nom_" . $this_lang . " as produit, c.nom_" . $this_lang . " AS categorie
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
	}
	
	// Création du fichier. Ce fichier sera lu par le fichier php /get_sitemap.xml. Une règle de réécriture dans le htaccess rend cet appel transparent pour le client.
	$txt_filename = $GLOBALS['dirroot'] . "/urllist_" . substr(md5($this_wwwroot), 0, 4) . ".txt";

	$create_txt = String::fopen_utf8($txt_filename, "wb");
	fwrite($create_txt, String::convert_encoding($sitemap, $file_encoding, GENERAL_ENCODING));
	fclose($create_txt);
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
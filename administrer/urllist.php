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
// $Id: urllist.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_webmastering");

if (!empty($_GET['encoding'])) {
	$file_encoding = $_GET['encoding'];
} else {
	$file_encoding = 'utf-8';
}
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_URLLIST_TITLE'];
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
	$current_lang = $_SESSION['session_langue'];
	foreach($this_wwwroot_lang_array as $this_lang) {
		// Modification de l'environnement de langue
		$_SESSION['session_langue'] = $this_lang;
		set_lang_configuration_and_texts($this_lang, vb($GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$this_lang]), true, false, !empty($GLOBALS['load_admin_lang']), true, defined('SKIP_SET_LANG'));
		
		$sitemap .= $GLOBALS['wwwroot'] . "\r\n";
		$sitemap .= get_url('membre') . "\r\n";
		$sitemap .= get_product_category_url() . "\r\n";
		// génération des liens pour les categories 
		$sql = "SELECT c.id, c.nom_" .$_SESSION['session_langue']. " AS nom
			FROM peel_categories c
			WHERE c.etat=1 AND " . get_filter_site_cond('categories', 'c');
		$query = query($sql);
		while ($result = fetch_assoc($query)) {
			$sitemap .= get_product_category_url($result['id'], $result['nom']) . "\r\n";
		}
		$sitemap .= get_content_category_url() . "\r\n";
		$sitemap .= get_account_register_url() . "\r\n";
		$sitemap .= get_account_url() . "\r\n";
		
		$sql = "SELECT p.id AS produit_id, c.id AS categorie_id, p.nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$this_lang)." as produit, c.nom_" . $this_lang . " AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
			INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
			WHERE p.etat=1 AND " . get_filter_site_cond('produits', 'p') . "";
		$query = query($sql);
		while ($result = fetch_assoc($query)) {
			$trans_tbl = get_html_translation_table(HTML_ENTITIES);
			$trans_tbl = array_flip($trans_tbl);

			$texte1 = strtr($result['produit'], $trans_tbl);
			$texte1 = str_replace("&", "", $texte1);

			$trans_tbl = get_html_translation_table(HTML_ENTITIES);
			$trans_tbl = array_flip($trans_tbl);

			$texte2 = strtr($result['categorie'], $trans_tbl);
			$texte2 = str_replace("&", "", $texte2);

			$sitemap .= get_product_url($result['produit_id'], $result['produit'], $result['categorie_id'], $result['categorie']) . "\r\n";
		}
	}
	// rétablissement de la langue du back office pour l'affichage du message de confirmation
	$_SESSION['session_langue'] = $current_lang;
	set_lang_configuration_and_texts($_SESSION['session_langue'], vb($GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$_SESSION['session_langue']]), true, false, !empty($GLOBALS['load_admin_lang']), true, defined('SKIP_SET_LANG'));
	
	// Création du fichier. Ce fichier sera lu par le fichier php /get_sitemap.xml. Une règle de réécriture dans le htaccess rend cet appel transparent pour le client.
	$txt_filename = $GLOBALS['dirroot'] . "/urllist_" . substr(md5($this_wwwroot), 0, 4) . ".txt";

	$create_txt = StringMb::fopen_utf8($txt_filename, "wb");
	fwrite($create_txt, StringMb::convert_encoding($sitemap, $file_encoding, GENERAL_ENCODING));
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


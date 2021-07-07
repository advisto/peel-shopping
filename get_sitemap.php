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
// $Id: get_sitemap.php 66961 2021-05-24 13:26:45Z sdelaporte $

define('LOAD_NO_OPTIONAL_MODULE', true);
include("configuration.inc.php");
// Yahoo
$urrlist_filename = $GLOBALS['dirroot'] . "/urllist_" . substr(md5(get_site_domain(false, $GLOBALS['wwwroot'], false)), 0, 4) . ".txt";
// Google
$sitemap_filename = $GLOBALS['dirroot'] . "/sitemap_" . substr(md5(get_site_domain(false, $GLOBALS['wwwroot'], false)), 0, 4) . ".xml";
// Ascreen
$ascreen_filename = $GLOBALS['dirroot'] . "/ascreen_" . substr(md5(get_site_domain(false, $GLOBALS['wwwroot'], false)), 0, 4) . ".jpg";

// retourne le fichier sitemap correspondant au sous-domaine/domaine utilisé.
if (!empty($_GET['file']) && $_GET['file'] == 'sitemap' && file_exists($sitemap_filename)) {
	$filename = $sitemap_filename;
	$content_type = 'application/xml';
} elseif (!empty($_GET['file']) && $_GET['file'] == 'urllist' && file_exists($urrlist_filename)) {
	$filename = $urrlist_filename;
	$content_type = 'text/plain';
}  elseif (!empty($_GET['file']) && $_GET['file'] == 'ascreen') {
	if(file_exists($ascreen_filename)) {
		$filename = $ascreen_filename;
	} elseif(!empty($GLOBALS['site_parameters']['ascreen_relative_path']) && in_array(substr($GLOBALS['site_parameters']['ascreen_relative_path'], -4), array('.jpg', '.jpeg')) && file_exists($GLOBALS['dirroot'] . $GLOBALS['site_parameters']['ascreen_relative_path'])) {
		// Protection sur le fait qu'on transmette bien un jpeg et rien d'autre (au cas où ascreen_relative_path serait corrompu)
		$filename = $GLOBALS['dirroot'] . $GLOBALS['site_parameters']['ascreen_relative_path'];
	} elseif(file_exists($GLOBALS['dirroot'] . "/ascreen.jpg")) {
		$filename = $GLOBALS['dirroot'] . "/ascreen.jpg";
	} 
	$cache_duration_in_seconds = 4*3600;
	header("Content-Type: text/jpg");
	header("Expires: 0");
	header('Cache-Control: public, max-age=' . $cache_duration_in_seconds . ', must-revalidate');
	header("Content-disposition: filename=" . $filename);
	die();
}else {
	// appel incorrect ou fichier inexistant
	header("HTTP/1.0 404 Not Found");
	die();
}

output_xml_http_export_header($_GET['file'], 'utf-8', $content_type, 3600);
echo StringMb::file_get_contents_utf8($filename);

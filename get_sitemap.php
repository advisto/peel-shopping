<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: get_sitemap.php 50572 2016-07-07 12:43:52Z sdelaporte $

define('LOAD_NO_OPTIONAL_MODULE', true);
include("configuration.inc.php");
// Yahoo
$urrlist_filename = $GLOBALS['dirroot'] . "/urllist_" . substr(md5(get_site_domain(false, $GLOBALS['wwwroot'], false)), 0, 4) . ".txt";
// Google
$sitemap_filename = $GLOBALS['dirroot'] . "/sitemap_" . substr(md5(get_site_domain(false, $GLOBALS['wwwroot'], false)), 0, 4) . ".xml";

// retourne le fichier sitemap correspondant au sous-domaine/domaine utilisé.
if (!empty($_GET['file']) && $_GET['file'] == 'sitemap' && file_exists($sitemap_filename)) {
	$filename = $sitemap_filename;
	$content_type = 'application/xml';
} elseif (!empty($_GET['file']) && $_GET['file'] == 'urllist' && file_exists($urrlist_filename)) {
	$filename = $urrlist_filename;
	$content_type = 'text/plain';
} else {
	// appel incorrect ou fichier inexistant
	die();
}

output_xml_http_export_header($_GET['file'], 'utf-8', $content_type, 3600);
echo String::file_get_contents_utf8($filename);

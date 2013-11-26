<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: get_sitemap.php 39030 2013-11-26 08:59:31Z gboussin $

define('LOAD_NO_OPTIONAL_MODULE', true);
include("configuration.inc.php");
// Yahoo
$urrlist_filename = $GLOBALS['dirroot'] . "/urllist_" . substr(md5(get_site_domain(false, $GLOBALS['wwwroot'], false)), 0, 4) . ".txt";
// Google
$sitemap_filename = $GLOBALS['dirroot'] . "/sitemap_" . substr(md5(get_site_domain(false, $GLOBALS['wwwroot'], false)), 0, 4) . ".xml";

// retourne le fichier sitemap correspondant au sous-domaine/domaine utilisé.
if (!empty($_GET['file']) && $_GET['file'] == 'sitemap' && file_exists($sitemap_filename)) {
	$filename = $sitemap_filename;
} elseif (!empty($_GET['file']) && $_GET['file'] == 'urllist' && file_exists($urrlist_filename)) {
	$filename = $urrlist_filename;
} else {
	// appel incorrect ou fichier inexistant
	die();
}

output_xml_http_export_header($_GET['file'], 'utf-8', 'application/xml');
echo file_get_contents($filename);
?>
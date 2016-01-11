<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: prefetch.php 48447 2016-01-11 08:40:08Z sdelaporte $
define('PEEL_PREFETCH', true);
include("configuration.inc.php");

$short_url = $_SERVER['REQUEST_URI'];

if(empty($GLOBALS['site_parameters']['enable_prefetch'])) {
	// Le prefetch est désactivé. Pourtant on arrive ici car le .htaccess a la règle qui fait la correspondance entre tous les .htm et ce fichier prefetch.php
	if (empty($_GET) && strpos($short_url, '..') === false && file_exists($GLOBALS['dirroot'] . $short_url)) {
		// On cherche la page .htm demandée au cas où elle existe
		require_once($GLOBALS['dirroot'] . $short_url);
		die();
	}
	// Sinon on redirige vers la page d'accueil
	redirect_and_die(get_url('/'));
}

// Faire 301 de anciennes pages vers nouvelles pages
// A FAIRE

	
// On va chercher à quoi peut correspondre la page .htm demandée
if (String::strpos($short_url, '?') !== false) {
	$short_url = String::substr($short_url, 0, String::strpos($short_url, '?'));
}
if (String::strpos($short_url, '/') === 0) {
	$short_url = String::substr($short_url, 1);
}
// Etape 1 : Recherche dans les rubriques de contenu
$sql = "SELECT r.*
	FROM peel_rubriques r
	WHERE " . get_filter_site_cond('rubriques', 'r') . " AND (r.technical_code='".real_escape_string($short_url)."' OR r.technical_code='/".real_escape_string($short_url)."')
	LIMIT 1";
$query = query($sql);
if ($result = fetch_assoc($query)) {
	$_GET['rubid'] = $result['id'];
	include($GLOBALS['dirroot'] . '/lire/index.php');
	die();
}

// Etape 2 : Recherche dans les articles de contenu
$sql = "SELECT a.*
	FROM peel_articles a
	WHERE " . get_filter_site_cond('articles', 'a') . " AND (a.technical_code='".real_escape_string($short_url)."' OR a.technical_code='/".real_escape_string($short_url)."')
	LIMIT 1";
$query = query($sql);
if ($result = fetch_assoc($query)) {
	$_GET['id'] = $result['id'];
	include($GLOBALS['dirroot'] . '/lire/article_details.php');
	die();
}

if(function_exists('convertHrefUri')) {
	// Décodage d'URL
	$href = convertHrefUri($_SERVER['REQUEST_URI']);
	if(String::strpos($href['script_filename'], '.php') && file_exists($GLOBALS['dirroot'] . '/' . $href['script_filename'])) {
		$script_filename = $href['script_filename'];
		unset($href['script_filename']);
		$_GET = $href;
		unset($href);
		require_once($GLOBALS['dirroot'] . '/' . $script_filename);
		die();
	}
}

/*
// Annonces Advisto.com
$href = convertHrefUri($_SERVER['REQUEST_URI'], null, $_SESSION['session_langue']);

if (!empty($href) && !empty($href[0])) {
	// On réécrit le nom du script dans la variable $_SERVER
	$file_name = $href[0];
	$_SERVER['SCRIPT_NAME'] = '/' . $href[0];
	if(isset($_GET['test_ads'])){
		$test_ads=$_GET['test_ads'];
	}
	$_GET = $href;
	unset($_GET[0]);
	unset($href);
	if(isset($test_ads)){
		$_GET['test_ads']=$test_ads;
	}
	if (!empty($_GET['page']) && (is_numeric($_GET['page']) || strpos($_GET['page'], 'rss') === 0)) { // || !empty($_GET['logout'])
		if ($file_name == 'form_save.php') {
			require_once($GLOBALS['dirroot'] . '/modules/advistocom/form_save.php');
		} elseif (file_exists('files/' . $file_name)) {
			require_once($GLOBALS['dirroot'] . 'files/' . $file_name);
			die();
		}
	}
}
*/

echo 'nothing found';
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: prefetch.php 44077 2015-02-17 10:20:38Z sdelaporte $
define('PEEL_PREFETCH', true);
include("configuration.inc.php");

if(empty($GLOBALS['site_parameters']['enable_prefetch'])) {
	redirect_and_die($GLOBALS['wwwroot'] . '/');
}
$url = $_SERVER['REQUEST_URI'];
if (String::strpos($url, '?') !== false) {
	$url = String::substr($url, 0, String::strpos($url, '?'));
}
if (String::strpos($url, '/') === 0) {
	$url = String::substr($url, 1);
}
$sql = "SELECT r.*
	FROM peel_rubriques r
	WHERE " . get_filter_site_cond('rubriques', 'r') . " AND (r.technical_code='".real_escape_string($url)."' OR r.technical_code='/".real_escape_string($url)."')
	LIMIT 1";
$query = query($sql);
if ($result = fetch_assoc($query)) {
	$_GET['rubid'] = $result['id'];
	include($GLOBALS['dirroot'] . '/lire/index.php');
	die();
}

$sql = "SELECT a.*
	FROM peel_articles a
	WHERE " . get_filter_site_cond('articles', 'a') . " AND (a.technical_code='".real_escape_string($url)."' OR a.technical_code='/".real_escape_string($url)."')
	LIMIT 1";
$query = query($sql);
if ($result = fetch_assoc($query)) {
	$_GET['id'] = $result['id'];
	include($GLOBALS['dirroot'] . '/lire/article_details.php');
	die();
}
/* Annonces Advisto.com */
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
			$starter_loaded = true;
			require_once($GLOBALS['dirroot'] . 'files/' . $file_name);
			die();
		}
	}
	// Faire 301 de anciennes pages vers nouvelles pages
	// A FAIRE
}

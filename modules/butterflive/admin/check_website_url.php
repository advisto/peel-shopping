<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) Butterflive - en collaboration avec contact@peel.fr    |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// $Id: check_website_url.php 35062 2013-02-08 14:13:26Z gboussin $

define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
require_once("../include/add_json_functions.php");
require_once("config.php");
necessite_identification();
necessite_priv("admin_manage");


//Get key
$res = query("SELECT value FROM peel_butterflive WHERE param = 'key'");
$resultat = fetch_row($res);
$key = $resultat[0];

$request_url = BUTTERFLIVE_WEBSITE_URL."remote/key_info2?key=".urlencode($key);
$resultat = json_decode(file_get_contents($request_url));

//Legacy
//$url = str_replace("/admin/check_website_url.php", "/tracker", get_current_url(false));

$url = $_SERVER['HTTP_HOST'];

$is_authorized_url = false;
if (isset($resultat->urls) && is_array($resultat->urls)) {
	foreach ($resultat->urls as $authorized_url)
	{
		if (substr_count(rtrim($authorized_url, '/'),$url ) != 0)
		{
			$is_authorized_url = true;
		}
	}
}

if ($is_authorized_url)
{
	echo json_encode(array('status'=>'OK','text'=>'La configuration des autorisations dans Butterflive est correcte.'));
}
else
{
	echo json_encode(array('status'=>'KO','text'=>"Attention! Votre site n'est pas autorisé dans votre compte Butterflive. <a href=\"".str_replace("check_website_url.php", "activate_site.php", get_current_url(false))."\">Cliquez ici pour activer ce site.</a>"));
}

?>
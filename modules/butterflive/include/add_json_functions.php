<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) Butterflive - en collaboration avec contact@peel.fr    |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// $Id: add_json_functions.php 36232 2013-04-05 13:16:01Z gboussin $
// Adds json_decode and json_encode for PHP <5.2
if (!function_exists('json_decode')) {
	function json_decode($content, $assoc = false)
	{
		require_once '../include/JSON.php';
		if ($assoc) {
			$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		} else {
			$json = new Services_JSON();
		}
		return $json->decode($content);
	}
}

if (!function_exists('json_encode')) {
	function json_encode($content)
	{
		require_once '../include/JSON.php';
		$json = new Services_JSON;

		return $json->encode($content);
	}
}

?>
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
// $Id: bannerHit.php 55332 2017-12-01 10:44:06Z sdelaporte $
//
if (!isset($_GET['id'])) {
	die();
}
define('LOAD_NO_OPTIONAL_MODULE', true);
include('../../configuration.inc.php');

if (!check_if_module_active('banner')) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die(get_url('/'));
}
$sql = "UPDATE peel_banniere
	SET hit = hit+1
	WHERE id='" . intval($_GET['id']) . "' AND " . get_filter_site_cond('banniere');
query($sql);
$sql = 'SELECT lien
	FROM peel_banniere
	WHERE id="' . intval($_GET['id']) . '"  AND ' . get_filter_site_cond('banniere');
$q = query($sql);
if (($result = fetch_assoc($q)) && !empty($result['lien'])) {
	redirect_and_die(trim($result['lien']));
}

<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: bannerHit.php 47592 2015-10-30 16:40:22Z sdelaporte $
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

query("UPDATE peel_banniere SET hit = hit+1 WHERE id='" . intval($_GET['id']) . "' AND " . get_filter_site_cond('banniere'));
$sql = 'SELECT lien
	FROM peel_banniere
	WHERE id="' . intval($_GET['id']) . '"  AND ' . get_filter_site_cond('banniere');
$q = query($sql);
if (($result = fetch_assoc($q)) && !empty($result['lien'])) {
	redirect_and_die(trim($result['lien']));
}

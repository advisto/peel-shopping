<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rss.php 46935 2015-09-18 08:49:48Z gboussin $
include("../../configuration.inc.php");

if (!check_if_module_active('rss')) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die(get_url('/'));
}
if (!empty($_GET['cat'])) {
	$category_id = intval($_GET['cat']);
}elseif (!empty($_GET['CatIDS'])) {
	$category_id = intval($_GET['CatIDS']);
} else {
	$category_id = null;
}

echo_rss_and_die($category_id);


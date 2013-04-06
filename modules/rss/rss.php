<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rss.php 36232 2013-04-05 13:16:01Z gboussin $
include("../../configuration.inc.php");

if (!is_module_rss_active()) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die($GLOBALS['wwwroot'] . "/");
}
if (!empty($_GET['cat'])) {
	$category_id = intval($_GET['cat']);
}elseif (!empty($_GET['CatIDS'])) {
	$category_id = intval($_GET['CatIDS']);
} else {
	$category_id = null;
}

echo_rss_and_die($category_id);

?>
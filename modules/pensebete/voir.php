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
// $Id: voir.php 36232 2013-04-05 13:16:01Z gboussin $

include("../../configuration.inc.php");

if (!is_module_pensebete_active()) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die($GLOBALS['wwwroot']."/");
}

necessite_identification();

define('IN_PENSE_BETE', true);
if(! empty($_GET['mode'])){
	if($_GET['mode']== 'delete'){
		if(!empty($_GET['id'])){
			$sql_delete= 'DELETE FROM peel_pensebete WHERE id='.intval($_GET['id']);
			query($sql_delete);
		}
	}
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
if(is_annonce_module_active()) {
	display_ads_in_reminder();
} else {
	display_product_in_reminder();
}
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
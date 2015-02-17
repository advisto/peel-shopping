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
// $Id: voir.php 44077 2015-02-17 10:20:38Z sdelaporte $

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
			$sql_delete= 'DELETE FROM peel_pensebete WHERE id='.intval($_GET['id']). (!a_priv('admin*', false)?' AND id_utilisateur="' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '"':'');
			query($sql_delete);
		}
	}
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
if(check_if_module_active('annonces')) {
	display_ads_in_reminder();
	echo '<br />';
}
display_product_in_reminder();
include($GLOBALS['repertoire_modele'] . "/bas.php");


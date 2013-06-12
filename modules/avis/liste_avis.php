<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: liste_avis.php 36927 2013-05-23 16:15:39Z gboussin $

include("../../configuration.inc.php");

if (!is_module_avis_active()) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die($GLOBALS['wwwroot'] . "/");
}

define('IN_TOUS_LES_AVIS', true);

include($GLOBALS['repertoire_modele'] . "/haut.php");

if (isset($_GET['prodid'])) {
	if (is_module_avis_active() && !empty($_GET['prodid']) && is_numeric($_GET['prodid'])) {
		// On charge les fonctions d'avis
		include($fonctionsavis);
		render_avis_public_list($_GET['prodid'], 'produit');
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('modules/avis_liste_notice.tpl', array('msg' => $GLOBALS['STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT']))->fetch();
	}
} elseif (isset($_GET['ref'])) {
	// Avis pour annonces
	if (is_module_avis_active() && !empty($_GET['ref']) && is_numeric($_GET['ref'])) {
		// On charge les fonctions d'avis
		include($fonctionsavis);
		render_avis_public_list($_GET['ref'], 'annonce');
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('modules/avis_liste_notice.tpl', array('msg' => $GLOBALS['STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD']))->fetch();
	}
}

include($GLOBALS['repertoire_modele'] . "/bas.php");

?>
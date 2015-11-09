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
// $Id: liste_avis.php 47592 2015-10-30 16:40:22Z sdelaporte $

include("../../configuration.inc.php");

if (!check_if_module_active('avis')) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die(get_url('/'));
}

define('IN_TOUS_LES_AVIS', true);

include($GLOBALS['repertoire_modele'] . "/haut.php");

if (isset($_GET['prodid'])) {
	if (check_if_module_active('avis') && !empty($_GET['prodid']) && is_numeric($_GET['prodid'])) {
		// On charge les fonctions d'avis
		echo render_avis_public_list($_GET['prodid'], 'produit', vb($_GET['display_specific_note']));
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('modules/avis_liste_notice.tpl', array('msg' => $GLOBALS['STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT']))->fetch();
	}
} elseif (isset($_GET['ref'])) {
	// Avis pour annonces
	if (check_if_module_active('avis') && !empty($_GET['ref']) && is_numeric($_GET['ref'])) {
		// On charge les fonctions d'avis
		echo render_avis_public_list($_GET['ref'], 'annonce', vb($_GET['display_specific_note']));
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('modules/avis_liste_notice.tpl', array('msg' => $GLOBALS['STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD']))->fetch();
	}
}

include($GLOBALS['repertoire_modele'] . "/bas.php");


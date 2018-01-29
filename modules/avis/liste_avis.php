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
// $Id: liste_avis.php 55332 2017-12-01 10:44:06Z sdelaporte $

include("../../configuration.inc.php");

if (!check_if_module_active('avis')) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die(get_url('/'));
}

define('IN_TOUS_LES_AVIS', true);
$output = '';

if (isset($_GET['prodid'])) {
	if (!empty($_GET['prodid']) && is_numeric($_GET['prodid'])) {
		// On charge les fonctions d'avis
		$output .= render_avis_public_list($_GET['prodid'], 'produit', vb($_GET['display_specific_note']), false, 'avis', 'h1');
	} else {
		$output .= $GLOBALS['tplEngine']->createTemplate('modules/avis_liste_notice.tpl', array('msg' => $GLOBALS['STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT']))->fetch();
	}
} elseif (isset($_GET['ref'])) {
	// Avis pour annonces
	if (!empty($_GET['ref']) && is_numeric($_GET['ref'])) {
		// On charge les fonctions d'avis
		$output .= render_avis_public_list($_GET['ref'], 'annonce', vb($_GET['display_specific_note']), false, 'avis', 'h1');
	} else {
		$output .= $GLOBALS['tplEngine']->createTemplate('modules/avis_liste_notice.tpl', array('msg' => $GLOBALS['STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD']))->fetch();
	}
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");


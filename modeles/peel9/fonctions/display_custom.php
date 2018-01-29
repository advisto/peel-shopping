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
// $Id: display_custom.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

// Ce fichier est chargé avant les fichiers display.php et display-xxxxx.php qui sont dans /lib/fonctions/
// Vous pouvez ici implémenter 1 ou plusieurs versions personnalisées des fonctions qui sont normalement déclarées dans ces fichiers de fonctions display.
// Ce sont les versions déclarées ici qui seront alors chargées, et non pas les versions standard de ces fonctions.
// Lors de l'installation ou la mise à jour du code de votre site, ceci vous permet d'avoir vos spécificités bien isolées du reste du code
//
// This file is loaded before the files display.php and display-xxxxx.php which are located in /lib/fonctions/
// You can implement hereunder one or more customized versions of the functions that are normally reported in these display function files.
// The versions located here are the ones that will be oaded, and not the standard versions of these functions.
// When installing or updating the code of your PEEL eshop, this allows you to have your specific functions well isolated from the rest of the code

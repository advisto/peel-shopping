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
// $Id: info.inc.src.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}
// Ce fichier a été généré lors de l'installation de PEEL.
// En cas de migration de serveur ou configuration spécifique, vous pouvez modifier dans ce fichier manuellement vos variables de configuration
$GLOBALS['serveur_mysql'] = "votre_serveur_mysql";
$GLOBALS['utilisateur_mysql'] = "votre_utilisateur_mysql";
$GLOBALS['mot_de_passe_mysql'] = "votre_motdepasse_mysql";
$GLOBALS['nom_de_la_base'] = "bdd_mysql";
//  $GLOBALS['wwwroot'] n'est plus défini dans ce fichier depuis la 7.2 de PEEL, mais dans la table peel_configuration.

// Configuration complémentaire
$GLOBALS['display_warning_if_connection_problem'] = true;


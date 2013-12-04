<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: info.inc.src.php 38682 2013-11-13 11:35:48Z gboussin $

if (!defined('IN_PEEL')) {
	die();
}
// Ce fichier a été généré lors de l'installation de PEEL.
// En cas de migration de serveur ou configuration sépcifique, vous pouvez modifier dans ce fichier manuellement vos variables de configuration
$GLOBALS['serveur_mysql'] = "localhost";
$GLOBALS['utilisateur_mysql'] = "root";
$GLOBALS['mot_de_passe_mysql'] = "";
$GLOBALS['nom_de_la_base'] = "trunk";
// Indiquez dans $GLOBALS['wwwroot'] l'URL de base de votre site, sans mettre de / à la fin. par exemple : $GLOBALS['wwwroot'] = "http://www.example.com";  ou $GLOBALS['wwwroot'] = "http://www.example.com/repertoiredemaboutique";
$GLOBALS['wwwroot'] = "http://localhost:8080/trunk";
// Configuration complémentaire
$GLOBALS['display_warning_if_connection_problem'] = true;

?>
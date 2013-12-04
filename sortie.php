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
// $Id: sortie.php 39162 2013-12-04 10:37:44Z gboussin $

define('LOAD_NO_OPTIONAL_MODULE', true);
define('FORCE_STOCK_MANAGER', true);
define('IN_ACCES_ACCOUNT', true);

include("configuration.inc.php");
// Désaffecte la variable de session $_SESSION['session_utilisateur'] pour déconnecter l'utilisateur.
unset($_SESSION['session_utilisateur']);
// On ne détruit pas toutes les variables pour garder le cas échéant par exemple des variables de session
// de limitation de spam ou autres variables d'historique récent d'actions utilisateur
unset($_SESSION['session_commande']);
unset($_SESSION['session_download_rights']);
$_SESSION['session_caddie']->init();

redirect_and_die($GLOBALS['wwwroot'] . "/");

?>
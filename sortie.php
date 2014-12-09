<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: sortie.php 43037 2014-10-29 12:01:40Z sdelaporte $

define('LOAD_NO_OPTIONAL_MODULE', true);
define('FORCE_STOCK_MANAGER', true);
define('IN_ACCES_ACCOUNT', true);

// INFO : Si on vient de se déconnecter de Facebook via le module facebook_connect, on arrive ensuite ici pour se déconnecter également de PEEL - dans ce cas, GET[mode]='facebook'

include("configuration.inc.php");
// Désaffecte la variable de session $_SESSION['session_utilisateur'] pour déconnecter l'utilisateur.
unset($_SESSION['session_utilisateur']);
// Désaffecte la variable session_admin_multisite
unset($_SESSION['session_admin_multisite']);
// On ne détruit pas toutes les variables pour garder le cas échéant par exemple des variables de session
// de limitation de spam, le cookie conserve les produits dans le panier ou autres variables d'historique récent d'actions utilisateur
unset($_SESSION['session_commande']);
unset($_SESSION['session_download_rights']);
unset($_SESSION['session_form']);
$_SESSION['session_caddie']->init();
// On vient de se déconnecter volontairement, on ne veut donc pas se reconnecter automatiquement via Facebook - si on le veut, ce devra être une action manuelle
$_SESSION['disable_facebook_autologin'] = true;

redirect_and_die($GLOBALS['wwwroot'] . "/");


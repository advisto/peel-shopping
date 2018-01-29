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
// $Id: sortie.php 55332 2017-12-01 10:44:06Z sdelaporte $

define('IN_ACCES_ACCOUNT', true);

// INFO : Si on vient de se déconnecter de Facebook via le module facebook_connect, on arrive ensuite ici pour se déconnecter également de PEEL - dans ce cas, GET[mode]='facebook'

include("configuration.inc.php");

user_logout();

redirect_and_die(get_url('/'));


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
// +----------------------------------------------------------------------+
// $Id: database_modules_ro.php 55325 2017-11-30 10:47:17Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_modules["title"] = array(
  "catalogue" => "Catalog",
  "tagcloud" => "Cele mai căutate",
  "search" => "Căutare",
  "guide" => "Informaţii",
  "caddie" => "Coşul dvs.",
  "account" => "Contul meu",
  "best_seller" => "Cele mai vândute",
  "news" => "La una",
  "advertising" => "Publicitate aleatoare",
  "menu" => "Menu",
  "ariane" => "Breadcrumb",
  "advertising1" => "Spaţiu publicitar 1",
  "advertising2" => "Spaţiu publicitar 2",
  "advertising3" => "Spaţiu publicitar 3",
  "advertising4" => "Spaţiu publicitar 4",
  "advertising5" => "Spaţiu publicitar 5",
  "last_views" => "Recent consultate",
  "brand" => "Mărci",
  "paiement_secu" => "Plată securizată",
  "articles_rollover" => "Best articles",
  "subscribe_newsletter" => "Subscribe newsletter"
);

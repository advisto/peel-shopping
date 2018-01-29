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
// $Id: database_modules_fr.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_modules["title"] = array(
  "catalogue" => "Catalogue",
  "tagcloud" => "Les plus recherchés",
  "search" => "Rechercher",
  "guide" => "Informations",
  "caddie" => "Votre panier",
  "account" => "Mon compte",
  "best_seller" => "Meilleures ventes",
  "news" => "A la une",
  "advertising" => "Publicité au hasard",
  "menu" => "Menu",
  "ariane" => "Fil d'ariane",
  "advertising1" => "Publicité espace 1",
  "advertising2" => "Publicité espace 2",
  "advertising3" => "Publicité espace 3",
  "advertising4" => "Publicité espace 4",
  "advertising5" => "Publicité espace 5",
  "last_views" => "Récemment consultés",
  "brand" => "Marques",
  "paiement_secu" => "Paiement sécurisé",
  "articles_rollover" => "Articles à la une",
  "subscribe_newsletter" => "inscription newsletter"
);


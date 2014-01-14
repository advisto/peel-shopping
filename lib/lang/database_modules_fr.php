<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_modules_fr.php 39495 2014-01-14 11:08:09Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_modules["title"] = array(
  "1" => "Catalogue",
  "2" => "Les plus recherchés",
  "3" => "Rechercher",
  "4" => "Informations",
  "5" => "Votre panier",
  "6" => "Mon compte",
  "7" => "Meilleures ventes",
  "8" => "A la une",
  "9" => "Publicité au hasard",
  "10" => "Menu",
  "11" => "Fil d'ariane",
  "12" => "Publicité espace 1",
  "13" => "Publicité espace 2",
  "14" => "Publicité espace 3",
  "15" => "Publicité espace 4",
  "16" => "Publicité espace 5",
  "17" => "Récemment consultés",
  "18" => "Marques",
  "19" => "Paiement sécurisé"
);

?>
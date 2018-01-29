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
// $Id: database_import_field_nl.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_import_field["texte"] = array(
   "id" => "Unieke code voor het product om een bestaand product bij te werken. Als id is opgegeven en gevonden in de database, wordt het product bijgewerkt",
   "categorie_id" => "De naam of referentie categorie (zet 0 als deze nog niet bestaat) Link is in de <a href=\"categories.php\">llist van categorieën</a> - ID-kolom. Categorie wordt automatisch aangemaakt als deze nog niet bestaat en dat is zijn naam",
   "Categorie" => "Andere mogelijke kolomnaam categorie_id",
   "id_marque" => "De aanduiding of het kenmerk van het merk. De referentie is in de <a href=\"marques.php\">lijst met merken</a> - ID-kolom. Het merk wordt aangemaakt als het nog niet bestaat",
   "reference" => "Product referentie",
   "nom_fr" => "Naam in het Frans",
   "descriptif_fr" => "Korte productbeschrijving in het Frans",
   "description_fr" => "Productbeschrijving in het Frans",
   "nom_en" => "Naam in het Frans",
   "descriptif_en" => "Korte productbeschrijving in het Engels",
   "description_en" => "Productbeschrijving in het Engels",
   "prix" => "Retailprijs incl. BTW",
   "prix_revendeur" => "Wederverkoper prijs incl. BTW",
   "prix_achat" => "Aankoopprijs incl. BTW",
   "tva" => "BTW-tarief in procenten",
   "promotion" => "Korting",
   "poids" => "Gewicht (in gram)",
   "points" => "Geschenkpunten voor uw bestelling",
   "image1" => "Afbeelding 1 : hoofdafbeelding",
   "image2" => "Afbeelding 2",
   "image3" => "Afbeelding 3",
   "image4" => "Afbeelding 4",
   "image5" => "Afbeelding 5",
   "image6" => "Afbeelding 6",
   "image7" => "Afbeelding 7",
   "image8" => "Afbeelding 8",
   "image9" => "Afbeelding 9",
   "image10" => "Afbeelding 10",
   "on_stock" => "Voorraadbeheer (1 = ja, 0 = nee)",
   "etat" => "Status (1 = online, 0 = in afwachting)"
);


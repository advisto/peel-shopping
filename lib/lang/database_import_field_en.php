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
// $Id: database_import_field_en.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_import_field["texte"] = array(
   "id" => "Unique identifier for the product to update an existing product. If id is specified and found in the database, the product is updated",
   "categorie_id" => "The name or reference category (put 0 if it does not exist.) Link is in the <a href=\"categories.php\">llist of categories</a>  - ID column. Category will be created automatically if it does not exist and that is his name",
   "Categorie" => "Other possible column name category_id",
   "id_marque" => "The name or reference of the brand. The reference is in the <a href=\"marques.php\">list of brands</a> - ID column. The brand will be created if it does not exist ",
   "reference" => "Product Reference",
   "nom_fr" => "Name in French",
   "descriptif_fr" => "Product short description in French",
   "description_fr" => "Product description in French",
   "nom_en" => "Name in English",
   "descriptif_en" => "Product short description in English",
   "description_en" => "Product description in English",
   "prix" => "Public price incl. VAT",
   "prix_revendeur" => "Reseller price incl. VAT",
   "prix_achat" => "Purchase price incl. VAT",
   "tva" => "VAT rate in percent",
   "promotion" => "Promotion",
   "poids" => "Weight (in grams)",
   "points" => "Gift Points",
   "image1" => "Image 1 : main picture",
   "image2" => "Image 2",
   "image3" => "Image 3",
   "image4" => "Image 4",
   "image5" => "Image 5",
   "image6" => "Image 6",
   "image7" => "Image 7",
   "image8" => "Image 8",
   "image9" => "Image 9",
   "image10" => "Image 10",
   "on_stock" => "Inventory Management (1 = yes, 0 = no)",
   "etat" => "Status (1 = online, 0 = pending)"
);


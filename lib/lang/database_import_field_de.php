<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_import_field_de.php 55325 2017-11-30 10:47:17Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_import_field["texte"] = array(
   "id" => "Einmaliger Code zur Aktualisierung eines bestehenden Produkts. Wenn die ID angegeben und in der Datenbank gefunden wurde, wird das Produkt aktualisiert.",
   "categorie_id" => "Der Name oder die Referenz der Kategorie (auf 0 setzen, falls noch nicht existent). Die Referenz findet sich in der <a href=\"categories.php\">Kategorienliste - Identifikationsspalte</a>. Die Kategorie wird automatisch erzeugt, wenn sie noch nicht besteht und dies ihr Name ist",
   "Categorie" => "Anderer möglicher Spaltenname für Kategorie_id",
   "id_marque" => "Name oder Referenz der Marke. Die Referenz findet sich in der <a href=\"marques.php\">Markenliste</a> - Identifikationsspalte. Die Marke wird erzeugt, wenn sie noch nicht besteht.",
   "reference" => "Produktreferenz",
   "nom_fr" => "Name auf französisch",
   "descriptif_fr" => "Kurze Produktbeschreibung auf französisch",
   "description_fr" => "Produktbeschreibung auf französisch",
   "nom_en" => "Name auf englisch",
   "descriptif_en" => "Kurze Produktbeschreibung auf englisch",
   "description_en" => "Produktbeschreibung auf englisch",
   "prix" => "Einzelhandelspreis inkl. MwSt.",
   "prix_revendeur" => "Wiederverkaufspreis inkl. MwSt.",
   "prix_achat" => "Kaufpreis inkl. MwSt.",
   "tva" => "Mehrwertsteuersatz",
   "promotion" => "Sonderangebot",
   "poids" => "Gewicht in g",
   "points" => "Treuepunkte",
   "image1" => "Bild 1: Hauptabbildung",
   "image2" => "Bild 2",
   "image3" => "Bild 3",
   "image4" => "Bild 4",
   "image5" => "Bild 5",
   "image6" => "Bild 6",
   "image7" => "Bild 7",
   "image8" => "Bild 8",
   "image9" => "Bild 9",
   "image10" => "Bild 10",
   "on_stock" => "Vorratsverwaltung (1 = ja, 0 = nein)",
   "etat" => "Status (1 = online, 0 = wartend)"
);


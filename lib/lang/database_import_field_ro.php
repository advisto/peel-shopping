<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
// +----------------------------------------------------------------------+
// $Id: database_import_field_ro.php 46935 2015-09-18 08:49:48Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_import_field["texte"] = array(
   "id" => "Identificatorul unic al produsului permite actualizarea unui produs existent. Dacă ID ul este precizat şi găsit în baza de date, produsul este actualizat.",
   "categorie_id" => "Numele sau categoria refertă (pune 0 dacă nu există). Referinţa este găsită la <a href=\"categories.php\"> lista de categorii - coloana Identificator</a>. Categoria va fi creată automat dacă nu existentă şi care  este numele său",
   "Categorie" => "Alt nume de coloană posibil pentru categorie_id",
   "id_marque" => "Nume sau referinţa de Marcă. Referinţa este în <a href=\"marques.php\"> lista de mărci</a> - coloana Identificator. Marca  va fi creată dacă nu existentă",
   "reference" => "Referinţă produs",
   "nom_fr" => "Nume în limba franceză",
   "descriptif_fr" => "Descriptiv produs în limba franceză",
   "description_fr" => "Descriere produs în limba franceză",
   "nom_en" => "NUme în limba engleză",
   "descriptif_en" => "Descriptiv produs în limba engleză",
   "description_en" => "Descriere produs în limba engleză",
   "prix" => "Preţ vânzare public TTC",
   "prix_revendeur" => "Preţ vânzare revănzător TTC",
   "prix_achat" => "Preţ de achiziţie TTC",
   "tva" => "Taxa TVA în procente",
   "promotion" => "Promoţii",
   "poids" => "Poinds ( în grame )",
   "points" => "Puncte cadou",
   "image1" => "Imaginea 1 : Imaginea principală",
   "image2" => "Imaginea 2",
   "image3" => "Imaginea 3",
   "image4" => "Imaginea 4",
   "image5" => "Imaginea 5",
   "image6" => "Imaginea 6",
   "image7" => "Imaginea 7",
   "image8" => "Imaginea 8",
   "image9" => "Imaginea 9",
   "image10" => "Imaginea 10",
   "on_stock" => "Gestiune stoc ( 1= da, O = nu )",
   "etat" => "Statut ( 1 = la vanzare, O = în aşteptare )"
);


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
// $Id: database_import_field_ru.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_import_field["texte"] = array(
   "id" => "Identifiant unique du produit permettant de mettre à jour un produit existant. Si l'id est précisée et trouvée en base de données, le produit est mis à jour",
   "categorie_id" => "Le nom ou la référence catégorie (mettre 0 si elle n'existe pas). La référence se trouve dans la <a href=\"categories.php\">liste des catégories - colonne Identifiant</a>. La catégorie sera créée automatiquement si elle est inexistante et que c'est son nom ",
   "Categorie" => "Autre nom de colonne possible pour categorie_id",
   "id_marque" => "Le nom ou la référence de la Marque. La référence se trouve dans la <a href=\"marques.php\">liste des marques</a> - colonne Identifiant. La marque sera créée si elle est inexistante",
   "reference" => "Référence du produit",
   "nom_fr" => "Nom en langue française",
   "descriptif_fr" => "Descriptif du produit en langue française",
   "description_fr" => "Description du produit en langue française",
   "nom_en" => "Nom en langue anglaise",
   "descriptif_en" => "Descriptif du produit en langue anglaise",
   "description_en" => "Description du produit en langue anglaise",
   "prix" => "Prix de vente public TTC",
   "prix_revendeur" => "Prix de vente revendeur TTC",
   "prix_achat" => "Prix d'achat TTC",
   "tva" => "Taux de TVA en pourcents",
   "promotion" => "Promotion",
   "poids" => "Poids (en grammes)",
   "points" => "Points cadeaux",
   "image1" => "Image 1 : image principale",
   "image2" => "Image 2",
   "image3" => "Image 3",
   "image4" => "Image 4",
   "image5" => "Image 5",
   "image6" => "Image 6",
   "image7" => "Image 7",
   "image8" => "Image 8",
   "image9" => "Image 9",
   "image10" => "Image 10",
   "on_stock" => "Gestion du stock (1 = oui, O = non)",
   "etat" => "Etat (1 = en ligne, O = en attente)"
);


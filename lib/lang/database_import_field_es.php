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
// $Id: database_import_field_es.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_import_field["texte"] = array(
   "id" => "Identificador único del producto para actualizar un producto existente. Si se especifica id y se encuentra en la base de datos, el producto se actualiza",
   "categorie_id" => "El nombre o la categoría de referencia (ponga 0 si no existe) está en la <a href=\"categories.php\">lista de categorías</a> - Columna ID. La categoría se creará automáticamente si no existe y que es su nombre",
   "Categorie" => "Otro nombre de columna posible category_id",
   "id_marque" => "El nombre o la referencia de la marca, la referencia está en la <a href=\"marques.php\">lista de las marcas</a> - Columna ID. La marca se crea si no existe.",
   "reference" => "Número de producto",
   "nom_fr" => "Nombre en francés",
   "descriptif_fr" => "Producto descripción en francés",
   "description_fr" => "Producto Descripción idioma francés",
   "nom_en" => "Nombre en Inglés",
   "descriptif_en" => "Descripción del producto en Inglés",
   "description_en" => "Descripción del producto Inglés",
   "prix" => "Precio TTC",
   "prix_revendeur" => "Precio distribuidor incl",
   "prix_achat" => "Purchase Price incl",
   "tva" => "Tipo del IVA en porcentaje",
   "promotion" => "Promoción",
   "poids" => "Peso (gramos)",
   "points" => "Puntos de regalo",
   "image1" => "Imagen 1: Imagen principal",
   "image2" => "Imagen 2",
   "image3" => "Imagen 3",
   "image4" => "Imagen 4",
   "image5" => "Imagen 5",
   "image6" => "Imagen 6",
   "image7" => "Imagen 7",
   "image8" => "Imagen 8",
   "image9" => "Imagen 9",
   "image10" => "Imagen 10",
   "on_stock" => "Manejo de Inventario (1 = sí, O = no)",
   "etat" => "Estado (1 = línea = O en espera)"
);


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_modules_es.php 37904 2013-08-27 21:19:26Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_modules["title"] = array(
  "1" => "Catalogo",
  "2" => "Los más buscados",
  "3" => "Busqueda",
  "4" => "Informationes",
  "5" => "Su carrito",
  "6" => "Mi cuenta",
  "7" => "Más vendidos",
  "8" => "¿Qué hay de nuevo?",
  "9" => "Publicidad al azar",
  "10" => "Menú",
  "11" => "Migaja de pan",
  "12" => "Espacios publicitarios 1",
  "13" => "Espacios publicitarios 2",
  "14" => "Espacios publicitarios 3",
  "15" => "Espacios publicitarios 4",
  "16" => "Espacios publicitarios 5",
  "17" => "Visto recientemente",
  "18" => "Marcas",
  "19" => "Pago seguro"
);

?>
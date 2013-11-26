<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
// +----------------------------------------------------------------------+
// $Id: database_modules_ro.php 38682 2013-11-13 11:35:48Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_modules["title"] = array(
  "1" => "Catalog",
  "2" => "Cele mai căutate",
  "3" => "Căutare",
  "4" => "Informaţii",
  "5" => "Coşul dvs.",
  "6" => "Contul meu",
  "7" => "Cele mai vândute",
  "8" => "La una",
  "9" => "Publicitate aleatoare",
  "10" => "Menu",
  "11" => "Breadcrumb",
  "12" => "Spaţiu publicitar 1",
  "13" => "Spaţiu publicitar 2",
  "14" => "Spaţiu publicitar 3",
  "15" => "Spaţiu publicitar 4",
  "16" => "Spaţiu publicitar 5",
  "17" => "Recent consultate",
  "18" => "Mărci",
  "19" => "Plată securizată"
);

?>
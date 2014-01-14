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
// $Id: database_modules_ru.php 39495 2014-01-14 11:08:09Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_modules["title"] = array(
  "1" => "Catalog",
  "2" => "Top search",
  "3" => "Search",
  "4" => "Information",
  "5" => "Your cart",
  "6" => "My account",
  "7" => "Best Selling",
  "8" => "News",
  "9" => "Advertising random",
  "10" => "Menu",
  "11" => "Breadcrumb",
  "12" => "Advertising location 1",
  "13" => "Advertising location 2",
  "14" => "Advertising location 3",
  "15" => "Advertising location 4",
  "16" => "Advertising location 5",
  "17" => "Last views",
  "18" => "Brands",
  "19" => "Secure payment"
);

?>
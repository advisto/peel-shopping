<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_modules_de.php 39162 2013-12-04 10:37:44Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_modules["title"] = array(
  "1" => "Katalog",
  "2" => "Am häufigsten gesucht",
  "3" => "Suchen",
  "4" => "Informationen",
  "5" => "Ihr Warenkorb",
  "6" => "Mein Konto",
  "7" => "Beste Verkäufe",
  "8" => "Neuigkeiten",
  "9" => "Willkürliche Werbung",
  "10" => "Menü",
  "11" => "Ariadnefaden",
  "12" => "Anzeige Platz 1",
  "13" => "Anzeige Platz 2",
  "14" => "Anzeige Platz 3",
  "15" => "Anzeige Platz 4",
  "16" => "Anzeige Platz 5",
  "17" => "Zuletzt angesehen",
  "18" => "Marken",
  "19" => "Gesicherte Bezahlung"
);

?>
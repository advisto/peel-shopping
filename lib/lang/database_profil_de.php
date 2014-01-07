<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_profil_de.php 39443 2014-01-06 16:44:24Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_profil["name"] = array(
  "1" => "Kunde",
  "2" => "Verwalter allgemein",
  "3" => "Wiederverkäufer",
  "4" => "Wiederverkäufer - wartend",
  "5" => "Partner",
  "6" => "Partner - wartend",
  "7" => "Anbieter",
  "8" => "Rundbriefabonnent",
  "9" => "Zertifizierter Wiederverkäufer",
  "10" => "Verwalter Inhalt",
  "11" => "Verwalter Verkauf",
  "12" => "Verwalter Artikel",
  "13" => "Verwalter Webmastering",
  "14" => "Verwalter Moderation"
);

?>
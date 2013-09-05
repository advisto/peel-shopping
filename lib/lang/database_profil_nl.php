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
// $Id: database_profil_nl.php 37904 2013-08-27 21:19:26Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_profil["name"] = array(
  "1" => "Klant",
  "2" => "Global Administrator",
  "3" => "Wederverkoper",
  "4" => "Wederverkoper - wachten",
  "5" => "Partner",
  "6" => "Partner - wachten",
  "7" => "Aanbieder",
  "8" => "Nieuwsbrief abonnee",
  "9" => "Gecertificeerde wederverkoper",
  "10" => "Inhoud Administrator",
  "11" => "Verkoop Administrator ",
  "12" => "Producten Administrator",
  "13" => "Webmastering Administrator",
  "14" => "Moderatie Administrator"
);

?>
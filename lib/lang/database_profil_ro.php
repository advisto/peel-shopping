<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
// +----------------------------------------------------------------------+
// $Id: database_profil_ro.php 37904 2013-08-27 21:19:26Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_profil["name"] = array(
  "1" => "Client",
  "2" => "Administrator Global",
  "3" => "Revânzător",
  "4" => "Revânzător - aşteptare",
  "5" => "Afiliat",
  "6" => "Afiliat - aşteptare",
  "7" => "Furnizor",
  "8" => "Inscris la Newsletter",
  "9" => "Revânzător certificat",
  "10" => "Administrator Conţinut",
  "11" => "Administrator Vânzări",
  "12" => "Administrator Produse",
  "13" => "Administrator Webmaster",
  "14" => "Administrator Moderator"
);

?>
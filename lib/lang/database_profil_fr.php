<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_profil_fr.php 35064 2013-02-08 14:16:40Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_profil["name"] = array(
  "1" => "Client",
  "2" => "Administrateur Global",
  "3" => "Revendeur",
  "4" => "Revendeur - attente",
  "5" => "Affilié",
  "6" => "Affilié - attente",
  "7" => "Fournisseur",
  "8" => "Inscrit à la newsletter",
  "9" => "Revendeur certified",
  "10" => "Administrateur Contenu",
  "11" => "Administrateur Ventes",
  "12" => "Administrateur Produits",
  "13" => "Administrateur Webmastering",
  "14" => "Administrateur Modération"
);

?>
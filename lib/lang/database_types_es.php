<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2017 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.5, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_types_es.php 53200 2017-03-20 11:19:46Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_types["nom"] = array(
  "colissimo_without_signature" => "Colissimo Expert contra la firma",
  "colissimo_expert_international" => "Colissimo Expert internacional",
  "chronopost" => "Chronopost",
  "pickup" => "Recoger en tienda",
  "ups" => "UPS",
  "dhl" => "DHL",
  "fedex" => "FedEx"
);


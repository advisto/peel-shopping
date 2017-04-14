<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.5, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
// +----------------------------------------------------------------------+
// $Id: database_paiement_ro.php 53198 2017-03-20 11:07:12Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_paiement["nom"] = array(
  "check" => "Cec",
  "paypal" => "Paypal : Visa, Mastercard, cont Paypal",
  "transfer" => "Virament bancar",
  "moneybookers" => "Moneybookers : Visa, Mastercard, Carte-Bleue, Virement, Moneybookers portofel electronic",
  "pickup" => "Plata la ridicare pe loc",
  "delivery" => "Plata după livrare",
  "cash" => "Numerar",
  "mandate" => "Western Union"
);


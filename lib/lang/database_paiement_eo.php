<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_paiement_eo.php 66961 2021-05-24 13:26:45Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_paiement["nom"] = array(
  "check" => "Ĉeko",
  "paypal" => "PayPal: Visa, Mastercard, PayPal-konto",
  "transfer" => "Bank-ĝiro",
  "moneybookers" => "Moneybookers : Visa, Mastercard, Carte-Bleue, Ĝiro, Moneybookers ret-monujo",
  "pickup" => "Pago je surloka liverado",
  "delivery" => "Pago je livero",
  "cash" => "Komisia mono",
  "mandate" => "Western Union / Komisia mono"
);


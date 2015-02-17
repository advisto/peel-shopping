<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_profil_nl.php 44077 2015-02-17 10:20:38Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_profil["name"] = array(
	"util" => "Klant",
	"admin" => "Global Administrator",
	"reve" => "Wederverkoper",
	"stop" => "Wederverkoper - wachten",
	"affi" => "Partner",
	"stand" => "Partner - wachten",
	"supplier" => "Aanbieder",
	"newsletter" => "Nieuwsbrief abonnee",
	"admin_content" => "Inhoud Administrator",
	"admin_sales" => "Verkoop Administrator",
	"admin_products" => "Producten Administrator",
	"admin_webmastering" => "Webmastering Administrator",
	"admin_moderation" => "Moderatie Administrator"
);


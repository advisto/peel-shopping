<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_profil_de.php 44072 2015-02-17 09:53:24Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_profil["name"] = array(
	"util" => "Kunde",
	"admin" => "Verwalter allgemein",
	"reve" => "Wiederverkäufer",
	"stop" => "Wiederverkäufer - wartend",
	"affi" => "Partner",
	"stand" => "Partner - wartend",
	"supplier" => "Anbieter",
	"newsletter" => "Rundbriefabonnent",
	"admin_content" => "Verwalter Inhalt",
	"admin_sales" => "Verwalter Verkauf",
	"admin_products" => "Verwalter Artikel",
	"admin_webmastering" => "Verwalter Webmastering",
	"admin_moderation" => "Verwalter Moderation"
);

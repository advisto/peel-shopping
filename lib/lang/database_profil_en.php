<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_profil_en.php 43037 2014-10-29 12:01:40Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_profil["name"] = array(
	"util" => "Client",
	"admin" => "Global Administrator",
	"reve" => "Reseller",
	"stop" => "Reseller - waiting",
	"affi" => "Affiliate",
	"stand" => "Affiliate - waiting",
	"supplier" => "Provider",
	"newsletter" => "Newsletter subscriber",
	"reve_certif" => "Reseller certified",
	"admin_content" => "Content Administrator",
	"admin_sales" => "Sales Administrator",
	"admin_products" => "Products Administrator",
	"admin_webmastering" => "Webmastering Administrator",
	"admin_moderation" => "Moderation Administrator"
);


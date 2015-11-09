<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
// +----------------------------------------------------------------------+
// $Id: database_profil_ro.php 47592 2015-10-30 16:40:22Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_profil["name"] = array(
	"util" => "Client",
	"admin" => "Administrator Global",
	"reve" => "Revânzător",
	"stop" => "Revânzător - aşteptare",
	"affi" => "Afiliat",
	"stand" => "Afiliat - aşteptare",
	"supplier" => "Furnizor",
	"newsletter" => "Inscris la Newsletter",
	"admin_content" => "Administrator Conţinut",
	"admin_sales" => "Administrator Vânzări",
	"admin_products" => "Administrator Produse",
	"admin_webmastering" => "Administrator Webmaster",
	"admin_moderation" => "Administrator Moderator"
);


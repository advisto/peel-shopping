<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_profil_eo.php 61970 2019-11-20 15:48:40Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_profil["name"] = array(
	"util" => "Kliento",
	"admin" => "Retejestro",
	"reve" => "Revendisto",
	"stop" => "Revendisto atendanta",
	"affi" => "Partnero",
	"stand" => "Partnero atendanta",
	"supplier" => "Provizisto",
	"newsletter" => "Abonanta la novaĵ-bultenon",
	"admin_content" => "Estro pri enhavo",
	"admin_sales" => "Estro pri vendado",
	"admin_products" => "Estro pri varoj",
	"admin_webmastering" => "Estro pri retmastrumado",
	"admin_users" => "Users Administrator",
	"admin_manage" => "Configuration Administrator",
	"admin_moderation" => "Estro pri ret-etiketo"
);

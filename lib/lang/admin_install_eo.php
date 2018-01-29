<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
// +----------------------------------------------------------------------+
// $Id: admin_install_eo.php 55928 2018-01-26 17:31:15Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

$GLOBALS["STR_ADMIN_INSTALL_STEP1_TITLE"] = "ŜTUPO 1 el 6: instalo de PEEL Shopping";
$GLOBALS["STR_ADMIN_INSTALL_STEP2_TITLE"] = "ŜTUPO 2 el 6: Konektiĝo al datumbazo";
$GLOBALS["STR_ADMIN_INSTALL_STEP3_TITLE"] = "ŜTUPO 3 el 6: Elekto pri datumbazo";
$GLOBALS["STR_ADMIN_INSTALL_STEP4_TITLE"] = "ŜTUPO 4 el 6: Kontrolo pri rajtoj";
$GLOBALS["STR_ADMIN_INSTALL_STEP5_TITLE"] = "ŜTUPO 5 el 6: Agordo de la reteja administranta konto ";
$GLOBALS["STR_ADMIN_INSTALL_STEP6_TITLE"] = "ŜTUPO 6 el 6: Fino de la instalo";
$GLOBALS["STR_ADMIN_INSTALL_WELCOME"] = "Bonvenon ĉe la instalilo de PEEL.";
$GLOBALS["STR_ADMIN_INSTALL_WELCOME_INTRO"] = "Ni gvidos vin laŭ la instala proceso de la aplikaĵo en vian sistemon.";
$GLOBALS["STR_ADMIN_INSTALL_VERIFY_SERVER_CONFIGURATION"] = "Kontrolo de la servilo:";
$GLOBALS["STR_ADMIN_INSTALL_PHP_VERSION"] = "PHP-versio:";
$GLOBALS["STR_ADMIN_INSTALL_MBSTRING"] = "'mbstring'-kromprogramo por multbajta signoj el ekster-eŭropaj klavaroj:";
$GLOBALS["STR_ADMIN_INSTALL_UTF8"] = "UTF-8-disponeco:";
$GLOBALS["STR_ADMIN_INSTALL_ALLOW_URL_FOPEN"] = "Direktivo 'allow_url_fopen' aktiva ĉe 'php.ini' por malfermi eksternajn paĝojn:";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_1"] = "Ni tuj instalos la bezonatajn informojn en datumbazon.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_2"] = "Pro tio, necesas peti diversajn informojn de vi.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_3"] = "Necesas ricevi de la gastanto de via retejo la identigajn informojn.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_4"] = "Evitu uzi la radik-konton 'root', kaj por plia sekureco, favoru ĉe 'mySQL' pasvorton fortikan kaj malsaman ol tiu ĉe SSH";
$GLOBALS["STR_ADMIN_INSTALL_ERROR_CONNEXION"] = "Eraro! Bonvolu kontroli la elektitajn lingvojn kaj la datumbazajn konektajn informojn";
$GLOBALS["STR_ADMIN_INSTALL_CHOOSE_WEBSITE_TYPE"] = "Elektu la tipon de retejo vi volas instali";
$GLOBALS["STR_ADMIN_INSTALL_EXPLAIN_SSL"] = "Averto: eblas indiki \"HTTPS\"-adreson, cele al uzi la sekuran \"SSL\"-ciferigon, nur kaze ke via retregiono posedas validan SSL-atestilon agordita ĉe via gastiganto";
$GLOBALS["STR_ADMIN_INSTALL_URL_STORE"] = "URL de la retejo:";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN"] = "Trudi uzon de \"SSL\"-ciferigon por administrado:";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_NO"] = "Ne trudi";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_YES"] = "Trudi \"SSL\"-ciferigon (pli sekura, sed necesas funkciantan \"HTTPS\"-adreson en la retregiono)";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_EXPLAIN"] = "Por trudi uzon de \"HTTPS\"-adreson por administrado, unue kontrolu ke almenaŭ unu paĝon funkcias pere de \"HTTPS\"";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SERVER"] = "MySQL-servilo:";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SERVER_EXPLAIN"] = "(ekz.: \"localhost\" aŭ la nomo de \"SQL\"-servilo, kaze interalie de gastigo komuna)";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_USERNAME"] = "Nomo de la uzanto";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC"] = "Por instali PEEL, ni konsilas uzi datumbazon dediĉita. Dum ĉiuj tabeloj havos prefikson \"peel_\", tamen eblas uzi datumbazon kun ekzistantaj tabloj.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE"] = "Se via datumbazo ne jam ekzistas, aŭ kreŭ ĝin, aŭ kontaktu vian gastiganton.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SELECT"] = "Bonvolu elekti la datumbazon uzenda por via PEEL-retejo:";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL"] = "ATENTU: se ĉi datumbazo jam havas tabelojn prefikistaj de \"peel_\", forigu ilin antaŭ ol daŭrigi";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_NO_ACCESS"] = "Vi ne povas atingi ĉi datumbazon.";
$GLOBALS["STR_ADMIN_INSTALL_DIRECTORY_OK"] = "La dosierujon %s ja atingeblas por skribi";
$GLOBALS["STR_ADMIN_INSTALL_DIRECTORY_NOK"] = "La dosierujon %s ne atingeblas por skribi =&gt; Agordu la skrib-rajton";
$GLOBALS["STR_ADMIN_INSTALL_FILE_OK"] = "La dosieron %s ja atingeblas por skribado";
$GLOBALS["STR_ADMIN_INSTALL_FILE_NOK"] = "La dosieron %s ne atingeblas por skribi =&gt; Agordu la skrib-rajton";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_OK_PREFIX"] = "La datumbazo %s ne jam enhavas PEEL-tabelon (en ordo!).";
$GLOBALS["STR_ADMIN_INSTALL_CHECK_ACCESS_RIGHTS"] = "Tuj kontroliĝos certaj rajtoj je dosieroj kaj dosierujoj";
$GLOBALS["STR_ADMIN_INSTALL_STEP_5_LINK_EXPLAIN"] = "Notu: venontan ŝtupon 5 el 6 estos kreitaj la datum-strukturo, kio povas daŭri kelkajn sekundojn";
$GLOBALS["STR_ADMIN_INSTALL_RIGHTS_OK"] = "La rajtoj ĝustas";
$GLOBALS["STR_ADMIN_INSTALL_RIGHTS_NOK"] = "Bonvolu korekti antaŭ ol daŭrigi";
$GLOBALS["STR_ADMIN_INSTALL_CONTINUE_WITH_ERRORS_BUTTON"] = "Daŭrigi malgraŭ erarojn";
$GLOBALS["STR_ADMIN_INSTALL_EXPLAIN_RENAME_TABLES"] = "Se vi daŭras, la jam ekzistantaj tabeloj ne foriĝos, kaj se la datum-strukturo malsimilas tion, kio atendas la instalilo, eraroj povas okazi. Plie, la aldonitaj datumbazoj povas krei duoblaĵojn evitendaj. NEPRE alinomu aŭ forigu la jam ekzistantaj tabeloj.";
$GLOBALS["STR_ADMIN_INSTALL_EXISTING_TABLES"] = "PEEL-tabeloj jam ekzistantaj:";
$GLOBALS["STR_ADMIN_INSTALL_ADMIN_EMAIL"] = "Retpoŝtadreso de la administranta konto";
$GLOBALS["STR_ADMIN_INSTALL_SQL_FILE_EXECUTED"] = "SQL-dosiero lanĉita";
$GLOBALS["STR_ADMIN_INSTALL_FILE_MISSING"] = "Eraro pro mankanta dosiero";
$GLOBALS["STR_ADMIN_INSTALL_FINISH_BUTTON"] = "Fini la instalon";
$GLOBALS["STR_ADMIN_INSTALL_NOW_INSTALLED"] = "PEEL Shopping instaliĝis.";
$GLOBALS["STR_ADMIN_INSTALL_YOU_CAN_LOGIN_ADMIN"] = "Eblas nun viziti la administran paĝon laŭ la sekvaj agordoj:";
$GLOBALS["STR_ADMIN_INSTALL_ADMIN_LINK_INFOS"] = "Post ensaluto, klaku \"Mia Konto\" > \"Administrado\".";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS"] = "Rimarkoj rilate sekurecon ĉe via retejo:";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_DELETE_INSTALL"] = "DEVIGE forigu la instalan dosierujon antaŭ ol komenci labori";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_RENAME_ADMIN"] = "TRE REKOMENDAS NI, por la sekureco de via retejo, alinomi la dosieron \"administrer\", pere de FTP-programo, al nomo malfacile trovebla, kaj same nomi la variablon {$GLOBALS['site_parameters']['backoffice_directory_name']} de la agorda dosiero \"configuration.inc.php\".";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_PHP_ERRORS_DISPLAY"] = "Via retejo agordiĝis por afiŝi la PHP-erarojn nur el via IP-adreso, t.e. {$_SERVER['REMOTE_ADDR']}. Eblas aliigi tiun agordaĵon en la administrejo.";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_UTF8_WARNING"] = "Notu: se vi deziras aliigi la PHP-kodon de via retejo, atentu dum redakto de paĝoj pri uzado de redaktilo kongrua kun UTF-8-signaro (kiu ne aldonas nevideblajn signojn ene de dosieroj). Kaze de dubo, ni konsilas la senpage libere el-interrete alŝuteblan programon \"Notepad++\".";
$GLOBALS["STR_ADMIN_INSTALL_LANGUAGE_CHOOSE"] = "Elektu la instalotajn lingvojn:";
$GLOBALS["STR_ADMIN_INSTALL_FILL_DB"] = "";
$GLOBALS["STR_ADMIN_INSTALL_FILL_DB_EXPLANATION"] = "";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_PHP5"] = "Necesas aktivigi la PHP5-programon en via retejo: redaktu la dosieron \".htaccess\" situanta en la rediko de la retejo, kaj malkomenti la liniojn rilataj al via gastigado, forigante la signojn '#' komence de linio, aŭ kontaktu la gastiganton. Eblas gastigi la retejon rekte ĉe PEEL - kontaktu PEEL ĉe contact@peel.fr aŭ telefone al +33 1 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_MBSTRING"] = "Necesos aliigi mane la kodigon de la retejo - kontaktu PEEL ĉe contact@peel.fr aŭ telefone al +33 1 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_UTF8"] = "Necesos aliigi mane la kodigon de la retejo - kontaktu PEEL ĉe contact@peel.fr aŭ telefone al +33 1 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_URL_FOPEN"] = "Ĉio devus en orde funkcii, krom modulo kies funkciado ne eblos.";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_SHOP"] = "e-komerco retejo";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_SHOWCASE"] = "storefront";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_AD"] = "Ad Site (nur se la modulo ĉeestas. Vi povas ordigi ĉi tiun modulon de ĉi <a onclick=\"return(window.open(this.href)?false:true);\" href=\"https://www.peel-shopping.com/various-128/module-annonces-installation-52.html\">tiu paĝo</a>)";

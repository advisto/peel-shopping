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
// $Id: admin_install_ro.php 55928 2018-01-26 17:31:15Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

$GLOBALS["STR_ADMIN_INSTALL_STEP1_TITLE"] = "ETAPA 1 / 6 : Instalare PEEL Shopping";
$GLOBALS["STR_ADMIN_INSTALL_STEP2_TITLE"] = "ETAPE 2 / 6 : Conectare  baza de date ";
$GLOBALS["STR_ADMIN_INSTALL_STEP3_TITLE"] = "ETAPE 3 / 6 : Alege baza";
$GLOBALS["STR_ADMIN_INSTALL_STEP4_TITLE"] = "ETAPE 4 / 6 : Verificare drepturi";
$GLOBALS["STR_ADMIN_INSTALL_STEP5_TITLE"] = "ETAPE 5 / 6 : Configurare cont administrator de site";
$GLOBALS["STR_ADMIN_INSTALL_STEP6_TITLE"] = "ETAPE 6 / 6 : Final instalare";
$GLOBALS["STR_ADMIN_INSTALL_WELCOME"] = "Bine ati venit la instalarea programului PEEL";
$GLOBALS["STR_ADMIN_INSTALL_WELCOME_INTRO"] = "Noi vă va vom ghida dealungul procesului de  instalare a aplicaţiei pe sistemul dvs.";
$GLOBALS["STR_ADMIN_INSTALL_VERIFY_SERVER_CONFIGURATION"] = "Verificare server:";
$GLOBALS["STR_ADMIN_INSTALL_PHP_VERSION"] = "Versiune PHP:";
$GLOBALS["STR_ADMIN_INSTALL_MBSTRING"] = "Extensia mbstring:";
$GLOBALS["STR_ADMIN_INSTALL_UTF8"] = "UTF-8 disponibil :";
$GLOBALS["STR_ADMIN_INSTALL_ALLOW_URL_FOPEN"] = "Directiva allow_url_fopen activată în php.ini :";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_1"] = "Vom instala informaţiile necesare în baza de date .";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_2"] = "Vom cere pentru aceasta diferite informaţii .";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_3"] = "Trebuie să obţineţi de isp dvs. identificatorii  MySQL .";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_4"] = "";
$GLOBALS["STR_ADMIN_INSTALL_ERROR_CONNEXION"] = "";
$GLOBALS["STR_ADMIN_INSTALL_CHOOSE_WEBSITE_TYPE"] = "";
$GLOBALS["STR_ADMIN_INSTALL_EXPLAIN_SSL"] = "";
$GLOBALS["STR_ADMIN_INSTALL_URL_STORE"] = "URL site:";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN"] = "Forţaţi utilizarea criptării SSL pentru administrare : ";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_NO"] = "Nu forţaţi";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_YES"] = "";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_EXPLAIN"] = "";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SERVER"] = "Server MySQL :";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SERVER_EXPLAIN"] = "";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_USERNAME"] = "Nume utilizator";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC"] = "";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE"] = "";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SELECT"] = "";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL"] = "";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_NO_ACCESS"] = "Nu aveţi acces la această bază";
$GLOBALS["STR_ADMIN_INSTALL_DIRECTORY_OK"] = "Directorul %s este accesibil pentru scriere";
$GLOBALS["STR_ADMIN_INSTALL_DIRECTORY_NOK"] = "";
$GLOBALS["STR_ADMIN_INSTALL_FILE_OK"] = "Fisierul  %s este accesibil pentru scriere";
$GLOBALS["STR_ADMIN_INSTALL_FILE_NOK"] = "";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_OK_PREFIX"] = "Baza %s nu conţine tabele PEEL ( este perfect ).";
$GLOBALS["STR_ADMIN_INSTALL_CHECK_ACCESS_RIGHTS"] = "Vom verifica câteva drepturi pe fişiere şi directoare";
$GLOBALS["STR_ADMIN_INSTALL_STEP_5_LINK_EXPLAIN"] = "";
$GLOBALS["STR_ADMIN_INSTALL_RIGHTS_OK"] = "Drepturile nu sunt corecte";
$GLOBALS["STR_ADMIN_INSTALL_RIGHTS_NOK"] = "";
$GLOBALS["STR_ADMIN_INSTALL_CONTINUE_WITH_ERRORS_BUTTON"] = "";
$GLOBALS["STR_ADMIN_INSTALL_EXPLAIN_RENAME_TABLES"] = "";
$GLOBALS["STR_ADMIN_INSTALL_EXISTING_TABLES"] = "Tabele PEEL deja existente :";
$GLOBALS["STR_ADMIN_INSTALL_ADMIN_EMAIL"] = "";
$GLOBALS["STR_ADMIN_INSTALL_SQL_FILE_EXECUTED"] = "Fi;ier SQL executat";
$GLOBALS["STR_ADMIN_INSTALL_FILE_MISSING"] = "";
$GLOBALS["STR_ADMIN_INSTALL_FINISH_BUTTON"] = "";
$GLOBALS["STR_ADMIN_INSTALL_NOW_INSTALLED"] = "";
$GLOBALS["STR_ADMIN_INSTALL_YOU_CAN_LOGIN_ADMIN"] = "";
$GLOBALS["STR_ADMIN_INSTALL_ADMIN_LINK_INFOS"] = "";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS"] = "";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_DELETE_INSTALL"] = "";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_RENAME_ADMIN"] = "";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_PHP_ERRORS_DISPLAY"] = "";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_UTF8_WARNING"] = "";
$GLOBALS["STR_ADMIN_INSTALL_LANGUAGE_CHOOSE"] = "";
$GLOBALS["STR_ADMIN_INSTALL_FILL_DB"] = "";
$GLOBALS["STR_ADMIN_INSTALL_FILL_DB_EXPLANATION"] = "";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_PHP5"] = "";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_MBSTRING"] = "";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_UTF8"] = "";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_URL_FOPEN"] = "";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_SHOP"] = "";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_SHOWCASE"] = "";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_AD"] = "";


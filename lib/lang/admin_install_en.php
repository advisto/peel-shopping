<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_install_en.php 55928 2018-01-26 17:31:15Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

$GLOBALS["STR_ADMIN_INSTALL_STEP1_TITLE"] = "STEP 1/6: Installing PEEL Shopping";
$GLOBALS["STR_ADMIN_INSTALL_STEP2_TITLE"] = "STEP 2/6: Connecting to the database";
$GLOBALS["STR_ADMIN_INSTALL_STEP3_TITLE"] = "STEP 3/6: Selecting the database";
$GLOBALS["STR_ADMIN_INSTALL_STEP4_TITLE"] = "STEP 4/6: Checking rights";
$GLOBALS["STR_ADMIN_INSTALL_STEP5_TITLE"] = "STEP 5/6: Configuring the administrator account store";
$GLOBALS["STR_ADMIN_INSTALL_STEP6_TITLE"] = "STEP 6/6: Completing the installation";
$GLOBALS["STR_ADMIN_INSTALL_WELCOME"] = "Welcome in the setup program of the CMS open source software PEEL Shopping.";
$GLOBALS["STR_ADMIN_INSTALL_WELCOME_INTRO"] = "We will guide you throughout this process to install the application on your system.";
$GLOBALS["STR_ADMIN_INSTALL_VERIFY_SERVER_CONFIGURATION"] = "Checking the server:";
$GLOBALS["STR_ADMIN_INSTALL_PHP_VERSION"] = "PHP Version:";
$GLOBALS["STR_ADMIN_INSTALL_MBSTRING"] = "Extension mbstring:";
$GLOBALS["STR_ADMIN_INSTALL_UTF8"] = "UTF-8 available:";
$GLOBALS["STR_ADMIN_INSTALL_ALLOW_URL_FOPEN"] = "Directive allow_url_fopen enabled in php.ini:";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_1"] = "We will install the necessary information in the database.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_2"] = "We're going to ask you for configuration information.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_3"] = "You need to get from your hosting MySQL identifiers.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_4"] = "Avoid using root, and prefer a mysql password which is robust and different from your SSH password for more security";
$GLOBALS["STR_ADMIN_INSTALL_ERROR_CONNEXION"] = "Error! Please check your selected languages and that your configuration information is complete";
$GLOBALS["STR_ADMIN_INSTALL_CHOOSE_WEBSITE_TYPE"] = "Choose the type of site you want to install";
$GLOBALS["STR_ADMIN_INSTALL_EXPLAIN_SSL"] = "Information: You can specify a URL to use https SSL encryption only if your domain has a valid SSL certificate configured on your hosting.";
$GLOBALS["STR_ADMIN_INSTALL_URL_STORE"] = "Site main URL:";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN"] = "Force the use of SSL for the administration:";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_NO"] = "Do not force";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_YES"] = "Force SSL (more secure, but https must be functional for the field)";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_EXPLAIN"] = "If you want to force the use of https for administration, check here first a page in HTTPS works";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SERVER"] = "MySQL";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SERVER_EXPLAIN"] = "(example: localhost or server name when SQL shared hosting in particular)";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_USERNAME"] = "Username";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC"] = "To install PEEL we recommend that you use a database devoted solely to PEEL. Nevertheless, as all the tables have the prefix \"peel_\", it is possible to use a database that already other tables. ";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE"] = "If your database is not already created, create, or contact your hosting provider.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SELECT"] = "Please choose your database that will store your PEEL:";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL"] = "CAUTION: If the database already contains tables \"peel_\", delete them before proceeding";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_NO_ACCESS"] = "You do not have access to the database";
$GLOBALS["STR_ADMIN_INSTALL_DIRECTORY_OK"] = "The directory %s is writable";
$GLOBALS["STR_ADMIN_INSTALL_DIRECTORY_NOK"] = "The directory %s is not writable => Add writable";
$GLOBALS["STR_ADMIN_INSTALL_FILE_OK"] = "The file %s is writable";
$GLOBALS["STR_ADMIN_INSTALL_FILE_NOK"] = "File %s is not writable => Add writable";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_OK_PREFIX"] = "The database %s does not contain table PEEL (it's perfect).";
$GLOBALS["STR_ADMIN_INSTALL_CHECK_ACCESS_RIGHTS"] = "Checking access rights on files and directories";
$GLOBALS["STR_ADMIN_INSTALL_STEP_5_LINK_EXPLAIN"] = "NB: The next step 5/6 will create your data structure and may take a few seconds";
$GLOBALS["STR_ADMIN_INSTALL_RIGHTS_OK"] = "The access rights seem to be correct";
$GLOBALS["STR_ADMIN_INSTALL_RIGHTS_NOK"] = "Please correct the errors before continuing";
$GLOBALS["STR_ADMIN_INSTALL_CONTINUE_WITH_ERRORS_BUTTON"] = "Continue despite errors";
$GLOBALS["STR_ADMIN_INSTALL_EXPLAIN_RENAME_TABLES"] = "If you continue, the existing tables will not be deleted, but if the data structure is not expected that this will create errors. Moreover, data bases will be added, and potentially creating duplicates. You MUST rename or delete existing tables.";
$GLOBALS["STR_ADMIN_INSTALL_EXISTING_TABLES"] = "PEEL existing tables:";
$GLOBALS["STR_ADMIN_INSTALL_ADMIN_EMAIL"] = "Email Administrator Account";
$GLOBALS["STR_ADMIN_INSTALL_SQL_FILE_EXECUTED"] = "File SQL executed";
$GLOBALS["STR_ADMIN_INSTALL_FILE_MISSING"] = "Error missing file";
$GLOBALS["STR_ADMIN_INSTALL_FINISH_BUTTON"] = "Completing the installation";
$GLOBALS["STR_ADMIN_INSTALL_NOW_INSTALLED"] = "PEEL Shopping is now installed.";
$GLOBALS["STR_ADMIN_INSTALL_YOU_CAN_LOGIN_ADMIN"] = "You can now connect to the management interface using the following parameters:";
$GLOBALS["STR_ADMIN_INSTALL_ADMIN_LINK_INFOS"] = "Once logged in as an administrator, you can go to \"My Account\" > \"Administration\".";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS"] = "Notes on the security of your site:";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_DELETE_INSTALL"] = "REQUIRED: Delete the installation directory to start working";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_RENAME_ADMIN"] = "STRONGLY RECOMMENDED: For the security of your site, rename the administration folder => In the \"Configuration variables\" administration page, change the variable \"backoffice_directory_name\" from \"{$GLOBALS['site_parameters']['backoffice_directory_name']}\" to a new name, and then rename the directory accordingly using FTP.";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_PHP_ERRORS_DISPLAY"] = "Your site has been configured to display PHP errors for your IP, namely {$_SERVER['REMOTE_ADDR']}. This parameter can be changed in the administration.";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_UTF8_WARNING"] = "NB: If you want to work on the PHP code on your site, be careful when you edit your files using an editor that supports UTF-8 well and does not add BOM (invisible characters) in the header files. If in doubt, use Notepad++ which is a free download on the Internet.";
$GLOBALS["STR_ADMIN_INSTALL_LANGUAGE_CHOOSE"] = "Select the languages ​​to install:";
$GLOBALS["STR_ADMIN_INSTALL_FILL_DB"] = "Fill the database";
$GLOBALS["STR_ADMIN_INSTALL_FILL_DB_EXPLANATION"] = "You can choose to install your site from scratch, or to use the demo content to prefill the database. This content will allow you to discover all the features offered by PEEL. Categories, products, content topics will automatically be added to your site and you will be able edit, delete and create new content.";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_PHP5"] = "You must enable PHP >= 5.2 on your site: edit the file. Htaccess at the root of the site to enable the lines corresponding to your accommodation by removing the # at the beginning of the line, or contact your hosting - For hosting by PEEL, contact PEEL: contact@peel.fr or +33 (0) 1 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_MBSTRING"] = "You will need to manually change the encoding of the site - contact PEEL: contact@peel.fr or +33 (0) 1 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_UTF8"] = "You will need to manually change the encoding of the site - contact PEEL: contact@peel.fr or +33 (0) 1 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_URL_FOPEN"] = "Operation will be normal except that some external module may not function";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_SHOP"] = "E-commerce website";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_SHOWCASE"] = "Showcase site";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_AD"] = "Ad Site (Only if the module is present. You can order this module from <a onclick=\"return(window.open(this.href)?false:true);\" href=\"https://www.peel-shopping.com/various-128/module-annonces-installation-52.html\">this page</a>)";



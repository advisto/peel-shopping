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
// $Id: admin_install_fr.php 55928 2018-01-26 17:31:15Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

$GLOBALS["STR_ADMIN_INSTALL_STEP1_TITLE"] = "ETAPE 1 / 6 : Installation de PEEL Shopping";
$GLOBALS["STR_ADMIN_INSTALL_STEP2_TITLE"] = "ETAPE 2 / 6 : Connexion à la base de données";
$GLOBALS["STR_ADMIN_INSTALL_STEP3_TITLE"] = "ETAPE 3 / 6 : Choix de la base";
$GLOBALS["STR_ADMIN_INSTALL_STEP4_TITLE"] = "ETAPE 4 / 6 : Vérification des droits";
$GLOBALS["STR_ADMIN_INSTALL_STEP5_TITLE"] = "ETAPE 5 / 6 : Configuration du compte administrateur du site";
$GLOBALS["STR_ADMIN_INSTALL_STEP6_TITLE"] = "ETAPE 6 / 6 : Fin de l'installation";
$GLOBALS["STR_ADMIN_INSTALL_WELCOME"] = "Bienvenue au sein du programme d'Installation de PEEL.";
$GLOBALS["STR_ADMIN_INSTALL_WELCOME_INTRO"] = "Nous allons vous guider tout au long de ce processus afin d'installer l'application sur votre système.";
$GLOBALS["STR_ADMIN_INSTALL_VERIFY_SERVER_CONFIGURATION"] = "Vérification du serveur:";
$GLOBALS["STR_ADMIN_INSTALL_PHP_VERSION"] = "Version de PHP :";
$GLOBALS["STR_ADMIN_INSTALL_MBSTRING"] = "Extension mbstring :";
$GLOBALS["STR_ADMIN_INSTALL_UTF8"] = "UTF-8 disponible :";
$GLOBALS["STR_ADMIN_INSTALL_ALLOW_URL_FOPEN"] = "Directive allow_url_fopen activée dans le php.ini :";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_1"] = "Nous allons installer les informations nécessaires en base de données.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_2"] = "Nous allons pour cela vous demander des informations de configuration.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_3"] = "Vous devez obtenir auprès de votre hébergeur des identifiants MySQL.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_4"] = "Evitez l'utilisation de root, et préférez un mot de passe mysql qui sont robuste et différent de votre mot de passe SSH pour plus de sécurité";
$GLOBALS["STR_ADMIN_INSTALL_ERROR_CONNEXION"] = "Erreur ! Veuillez vérifier les langues sélectionnées et que vos informations de configuration sont complètes";
$GLOBALS["STR_ADMIN_INSTALL_CHOOSE_WEBSITE_TYPE"] = "Choisissez le type de site que vous souhaitez installer";
$GLOBALS["STR_ADMIN_INSTALL_EXPLAIN_SSL"] = "Information : Vous pouvez indiquer une URL en https pour utiliser le chiffrement SSL seulement si votre domaine a un certificat SSL valide configuré sur votre hébergement.";
$GLOBALS["STR_ADMIN_INSTALL_URL_STORE"] = "URL du site :";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN"] = "Forcer l'utilisation du chiffrement SSL pour l'administration :";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_NO"] = "Ne pas forcer";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_YES"] = "Forcer le SSL (plus sécurisé, mais https doit être fonctionnel pour le domaine)";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_EXPLAIN"] = "Si vous voulez forcer l'utilisation de https pour l'administration, vérifiez d'abord ici qu'une page en HTTPS fonctionne";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SERVER"] = "Serveur MySQL :";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SERVER_EXPLAIN"] = "(exemple : localhost, ou nom du serveur SQL en cas d'hébergement mutualisé notamment)";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_USERNAME"] = "Nom d'utilisateur";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC"] = "Pour installer PEEL nous vous conseillons d'utiliser une base de données consacrée uniquement à PEEL. Néanmoins, comme les tables auront toutes le préfixe \"peel_\", il est possible d'utiliser une base ayant déjà d'autres tables.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE"] = "Si votre base de données n'est pas déjà créée, créez la ou contactez votre hébergeur.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SELECT"] = "Veuillez choisir votre base de données qui servira pour votre site internet PEEL :";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL"] = "ATTENTION : Si cette base contient déjà des tables \"peel_\", supprimez les avant de continuer";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_NO_ACCESS"] = "Vous n'avez pas accès à cette base";
$GLOBALS["STR_ADMIN_INSTALL_DIRECTORY_OK"] = "Le répertoire %s est bien accessible en écriture";
$GLOBALS["STR_ADMIN_INSTALL_DIRECTORY_NOK"] = "Le répertoire %s n'est pas accessible en écriture =&gt; Ajoutez des droits en écriture";
$GLOBALS["STR_ADMIN_INSTALL_FILE_OK"] = "Le fichier %s est bien accessible en écriture";
$GLOBALS["STR_ADMIN_INSTALL_FILE_NOK"] = "Le fichier %s n'est pas accessible en écriture =&gt; Ajoutez des droits en écriture";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_OK_PREFIX"] = "La base %s ne contient pas de table PEEL (c'est parfait).";
$GLOBALS["STR_ADMIN_INSTALL_CHECK_ACCESS_RIGHTS"] = "Vérification des droits d'accès sur les fichiers et les répertoires";
$GLOBALS["STR_ADMIN_INSTALL_STEP_5_LINK_EXPLAIN"] = "NB : La prochaine étape 5/6 va créer votre structure de données et peut prendre quelques secondes";
$GLOBALS["STR_ADMIN_INSTALL_RIGHTS_OK"] = "Les droits d'accès semblent tous corrects";
$GLOBALS["STR_ADMIN_INSTALL_RIGHTS_NOK"] = "Veuillez corriger les erreurs avant de continuer";
$GLOBALS["STR_ADMIN_INSTALL_CONTINUE_WITH_ERRORS_BUTTON"] = "Continuer malgré les erreurs";
$GLOBALS["STR_ADMIN_INSTALL_EXPLAIN_RENAME_TABLES"] = "Si vous continuez, les tables déjà existantes ne vont pas être effacées, mais si la structure de données n'est pas celle attendue, cela va créer des erreurs. Par ailleurs, les données de bases vont y être ajoutées, et créer potentiellement des doublons. Vous DEVEZ renommer ou supprimer les tables déjà existantes.";
$GLOBALS["STR_ADMIN_INSTALL_EXISTING_TABLES"] = "Tables PEEL déjà existantes :";
$GLOBALS["STR_ADMIN_INSTALL_ADMIN_EMAIL"] = "Email du compte administrateur";
$GLOBALS["STR_ADMIN_INSTALL_SQL_FILE_EXECUTED"] = "Fichier SQL exécuté";
$GLOBALS["STR_ADMIN_INSTALL_FILE_MISSING"] = "Erreur fichier manquant";
$GLOBALS["STR_ADMIN_INSTALL_FINISH_BUTTON"] = "Terminer l'installation";
$GLOBALS["STR_ADMIN_INSTALL_NOW_INSTALLED"] = "PEEL Shopping est maintenant installé.";
$GLOBALS["STR_ADMIN_INSTALL_YOU_CAN_LOGIN_ADMIN"] = "Vous pouvez maintenant vous connecter sur l'interface d'administration en utilisant les paramètres suivants :";
$GLOBALS["STR_ADMIN_INSTALL_ADMIN_LINK_INFOS"] = "Une fois identifié en tant qu'administrateur, vous pourrez cliquer sur \"Mon compte\" > \"Administrer\".";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS"] = "Remarques relatives à la sécurité de votre site :";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_DELETE_INSTALL"] = "OBLIGATOIRE : supprimez le répertoire installation pour commencer à travailler";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_RENAME_ADMIN"] = "FORTEMENT RECOMMANDE : Pour la sécurité de votre site, renommez le nom du répertoire d'administration => Dans la page de gestion des \"variables de configuration\", changez la variable {$GLOBALS['site_parameters']['backoffice_directory_name']} par un nom difficile à deviner, puis changez le nom du dossier en FTP juste après.";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_PHP_ERRORS_DISPLAY"] = "Votre site a été configuré pour n'afficher les erreurs PHP que pour votre IP, à savoir {$_SERVER['REMOTE_ADDR']}. Ce paramètre est modifiable dans l'administration.";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_UTF8_WARNING"] = "NB : Si vous souhaitez intervenir sur le code PHP de votre site, faites attention lorsque vous modifiez vos fichiers d'utiliser un éditeur qui gère bien l'UTF-8 et ne rajoute pas de BOM (caractères invisibles) en entête des fichiers. En cas de doute, utilisez Notepad++ qui est téléchargeable gratuitement sur Internet.";
$GLOBALS["STR_ADMIN_INSTALL_LANGUAGE_CHOOSE"] = "Choisissez les langues à installer :";
$GLOBALS["STR_ADMIN_INSTALL_FILL_DB"] = "Préremplir la base de données";
$GLOBALS["STR_ADMIN_INSTALL_FILL_DB_EXPLANATION"] = "Vous pouvez choisir d'installer votre site sans y mettre de contenu par défaut, ou d'utiliser le contenu de démonstration pour préremplir votre base de données. Ce contenu vous permettra de découvrir toutes les fonctionnalités offertes par PEEL. Des catégories, des produits, des rubriques de contenu seront automatiquement ajoutés à votre site et vous pourrez les modifier, les supprimer et créer de nouveaux contenus.";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_PHP5"] = "Il vous faut activer PHP >= 5.2 sur votre site : éditez le fichier .htaccess à la racine du site pour activer les lignes correspondant à votre hébergement en retirant le # en début de ligne, ou contactez votre hébergeur - Pour avoir un hébergement chez PEEL, contactez PEEL : contact@peel.fr ou 01 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_MBSTRING"] = "Il vous faudra modifier manuellement l'encodage du site - contactez PEEL : contact@peel.fr ou 01 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_UTF8"] = "Il vous faudra modifier manuellement l'encodage du site - contactez PEEL : contact@peel.fr ou 01 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_URL_FOPEN"] = "Le fonctionnement sera normal hormis certains modules qui pourraient ne pas fonctionner";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_SHOP"] = "Site e-commerce";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_SHOWCASE"] = "Site vitrine";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_AD"] = "Site d'annonce (uniquement si le module est présent. Vous pouvez commander ce module depuis <a onclick=\"return(window.open(this.href)?false:true);\" href=\"https://www.peel.fr/divers-128/module-annonces-installation-52.html\">cette page</a>)";


// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an     |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/   |
// +----------------------------------------------------------------------+
// $Id: README.txt 55332 2017-12-01 10:44:06Z sdelaporte $

English: 

- http://www.peel-shopping.com/

Français :

- Présentation : https://www.peel.fr/
- Documentation : http://doc.peel.fr/ et http://wiki.peel.fr/
- Forum : http://forum.peel.fr/

INSTALLATION DE PEEL SHOPPING
=============================

CE QU'IL VOUS FAUT
--------------------

- Les fichiers de code de PEEL SHOPPING
- Un hébergement web Payant ou gratuit (avec PHP >=5.1.2 et MySQL >= 4.0, et apache ou IIS).
- Un client FTP (exemple : Filezilla).


PROCEDURE D'INSTALLATION DE PEEL SHOPPING
-------------------------------------------

1. **Les fichiers de code de PEEL SHOPPING**
Télécharger le script PEEL SHOPPING : https://www.peel.fr/modules/telechargement/telecharger.php?id=7 => cliquez en bas sur le bouton télécharger
Dézippez le sur votre ordinateur.

2. **Un hébergement web avec PHP et MySQL** payant (chez PEEL par exemple) ou gratuit (chez Free par exemple).
Connectez-vous à votre hébergement web et accédez à votre base de données MySQL.
Votre base de données MySql est protégée par un ***nom d'utilisateur*** et un ***mot de passe***, procurez-les vous (dans les infos de votre compte d'hébergement web) 
pour vous connecter à votre base de données MySQL. NB : vous aurez besoin de ces renseignements plus tard lors de l'installation.
Créez une nouvelle base de données en lui donnant le ***nom de base*** que vous souhaitez, dans mon exemple elle s'appelle nomdemabase.
Il se peut que vous ne pourrez pas créer une nouvelle base de données et/ou qu'un nom de base de données y soit existante et/ou imposé,
ce n'est pas grave, l'essentiel est de connaître ce nom car vous en aurez besoin plus tard lors de l'installation.

3. **Mettez vos fichiers en ligne** avec votre client FTP (exemple : Filezilla).
Envoyez tous les fichiers du dossier PEEL avec votre FTP sur votre hébergement web.

4. **Lancez l'installation** qui se lance toute seule lorsque vous vous connectez à votre site.
Si le démarrage de la procédure d'installation vous donne une erreur 404, entrez manuellement dans votre navigateur l'URL http://votredomaine/[repertoire_utilisé_pour_uploader_peel]/installation/ pour lancer l'installation.
Plus tard, pour vous connecter et administrer votre site, utilisez les identifiants que vous aurez indiqué lors de l'installation.

5. **Supprimez le répertoire d'installation**
Renommez également en FTP le répertoire d'administration avec le nom que vous voulez et changez dans configuration.inc.php le nom du répertoire d'administration :
$backoffice_directory_name='administrer';
=> remplacez administrer par le nom que vous avez choisi

6. **FIN** : Rendez vous sur votre page d'accueil de votre site et si tout c'est bien déroulé, elle devrait s'afficher correctement


EN CAS DE PROBLEME DE FONCTIONNEMENT DE L'INSTALLATION
--------------------------------------------------------

* Il se peut que votre hébergement n'ait pas PHP5 activé.
Vous avez dans le fichier .htaccess (dans le répertoire racine de l'application) des explications de configuration en fonction de chaque hébergeur :

# CONFIGURATION : Configuration éventuellement nécessaire chez certains hébergeurs si votre hébergement n'est pas déjà en PHP5
#
# - Chez OVH, activez les lignes suivantes :
# SetEnv PHP_VER 5
# SetEnv REGISTER_GLOBALS 0
# et activez aussi plus loin dans le fichier la ligne RewriteBase / ou /monrepertoiredesite/
#
# - Chez 1&1, activez la ligne suivante :
# AddType x-mapp-php5 .php
# ou la ligne suivante :
# AddHandler x-mapp-php5 .php
# et activez aussi plus loin dans le fichier la ligne RewriteBase / ou /monrepertoiredesite/
#
# - Chez Free, activez la ligne suivante :
# php 1
#
# - Chez Aquaray, activez la ligne suivante :
# AddHandler php5 .php
#
# - Chez Lycos, activez la ligne suivante :
# AddHandler application/x-httpd-php5 .php
#
# - Chez Web1 / nFrance, activez la ligne suivante :
# AddHandler php-fastcgi5 .php
#
# - Chez Online.net, activez la ligne suivante :
# AddType application/x-httpd-php5 .php
#
# - Chez eXtend, activez la ligne suivante :
# SetEnv DEFAULT_PHP_VERSION 5
#
# - Chez Nuxit, activez les lignes suivantes :
# AddHandler x-httpd-php5 .php
# AddType application/x-httpd-php5 .php
# et activez aussi plus loin dans le fichier la ligne RewriteBase / ou /monrepertoiredesite/
#
# - Chez Produweb, activez les lignes suivantes :
# SetEnv DEFAULT_PHP_VERSION 5
#
# - Chez Infomaniak, activez PHP5 dans votre espace client et activez les lignes suivantes :
# php_flag allow_url_fopen On
# php_flag allow_url_include On
# php_flag register_globals Off
#
# - Chez Planet-work, activez la ligne suivante :
# AddHandler application/x-httpd-php5 .php
#
# - Chez Netissime, activez la ligne suivante :
# php_flag register_globals Off
#
# - Chez Amen, activez PHP5 dans votre espace client catégorie Langage supportés
#
# - Chez Godaddy, activez PHP5 dans votre espace client catégorie Languages

* Si vous avez des problèmes d'URL Rewriting :

# Sur certains hébergements (OVH, Amen, ...) il faut parfois configurer le RewriteBase :
# Attention : si vous n'êtes pas à la racine de votre domaine, indiquez /monrepertoiredesite/
# RewriteBase /
# ...ou... :
# RewriteBase /monrepertoiredesite/


INSTALLATION MANUELLE (UNIQUEMENT SI L'INSTALLATION ECHOUE)
-------------------------------------------------------------

Pour faire l'installation, comme indiqué ci-dessus en étape 4, il vous suffit de lancer le site avec http://votredomaine/installation et de remplir CHAQUE champ

Néanmoins, voici la démarche à suivre si l'installation échoue :

1. Assurez-vous que le répertoires /lib/templateEngines/smarty/compile est accessible en écriture par le serveur web, sinon Smarty ne pourra pas afficher l'installation
2. Installez la base mysql à partir du fichier /installation/peel.sql via phpMyAdmin par exemple
3. Renommer le fichier /lib/setup/info.inc.src en lib/setup/info.inc.php
4. Renseigner les variables du fichier /lib/setup/info.inc.php
5. Supprimer le répertoire /installation

**HABITUELLEMENT INUTILE** : 

Droits des fichiers si cas particuliers d'hébergements :

1. Mettre le fichier /lib/setup/info.inc.php en mode CHMOD 660 via votre client FTP.
2. Mettre les répertoire /upload et /comparateur en mode CHMOD 775 via votre client FTP.
3. Mettre les fichiers /administrer/peel_produits.xml, /administrer/peel_produits.csv et /administrer/sending.log en mode 660 via votre client FTP.
4. Mettre les fichiers sitemap.xml et urllist.txt en mode 660 via votre client FTP.

Pour les utilisateurs d'Easyphp, il faut renseigner le serveur smtp à utiliser pour l'envoi des messages,
puis éditer le fichier de configuration de PHP (Menu "Configuration/PHP") et rechercher la chaine "SMTP".
Précisez le SMTP a utiliser au lieu de 'localhost' (typiquement : celui de votre FAI), et indiquez une adresse sendmail_from valide.


MISE A JOUR A PARTIR D'UNE VERSION ANTERIEURE
-----------------------------------------------

Chaque nouvelle version comprend des modifications structurelles de la base de données par rapport à toutes les autres versions.

Vous avez des tutoriaux de migration sur le forum : http://forum.peel.fr/

Pensez à ne pas effacer les données du répertoire /upload qui contient l'ensemble de vos photos

Nous pouvons réimporter toutes les données des logiciels suivants (merci de nous fournir les fichiers SQL d'origine : structure + données) :

- PEEL SHOPPING versions antérieures
- Prestashop
- Magento
- OS Commerce toute version
- Boutikone toute version
- Micrologiciel toute version
- Powerboutique
- Autre logiciel : vous pouvez nous faire parvenir un fichiez SQL comprenant structure & données afin de procéder à la transcription de votre logiciel vers PEEL

Contactez-nous sur https://www.peel.fr/ ou au 01 75 43 67 97.


AJOUT D'UNE LANGUE
--------------------

Le système est livré en français, en anglais et en espagnol, ainsi qu'avec des langues partiellement implémentées (les phrases manquantes sont alors en anglais).
PEEL Shopping peut aussi fonctionner avec toute autre langue si les fichiers de langue correspondant sont mis dans le répertoire de langue /lib/lang/ .

Pour faire fonctionner une langue, il faut :

1. Traduire le fichier /lib/lang/fr.php dans la langue de votre choix (exemple création d'un fichier es.php contenant toutes les variables de langue traduite en espagnol)
2. Créer un fichier équivalent à /lib/lang/datetime_fr.php dans la langue de votre choix (exemple création d'un fichier datetime_es.php contenant tous les formats de dates en espagnol)
3. Créer la langue dans l'interface d'administration : Administration > Configuration > Configuration > Gestion des langues > Ajouter une langue
   Pour info, Des modèles de drapeaux sont à disposition dans le dossier /lib/flag/. Si toutefois vous voulez ajouter votre propre drapeau, envoyez le par FTP sur votre serveur et indiquez l'URL complète de l'image dans le champ correspondant. Si juste un nom d'image est précisé, il prendra par défaut le dossier /lib/flag/ .

Les traducteurs peuvent procéder aux traductions sur https://www.transifex.com/projects/p/peel-shopping/ sur lequel tous les fichiers de langue de PEEL Shopping et de ses modules sont indiqués.

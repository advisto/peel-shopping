// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an     |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: http://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/   |
// +----------------------------------------------------------------------+
// $Id: README.txt 37904 2013-08-27 21:19:26Z gboussin $
//

Documentation : http://www.peel.fr/
Forum : http://forum.peel.fr/

* INSTALLATION D'UN NOUVEAU SITE
--------------------------------

Il vous suffit de lancer le site avec http://votre_url/installation et de remplir CHAQUE champ

Néanmoins, voici la démarche à suivre si l'installation échoue :

0. Assurez-vous que le répertoires /lib/templateEngines/smarty/compile est accessible en écriture par le serveur web, sinon Smarty ne pourra pas afficher l'installation
1. Installez la base mysql à partir du fichier /installation/peel.sql via phpMyAdmin par exemple
2. Renommer le fichier /lib/setup/info.inc.src en lib/setup/info.inc.php
3. Renseigner les variables du fichier /lib/setup/info.inc.php
4. Supprimer le répertoire /installation

HABITUELLEMENT INUTILE : Droits des fichiers si cas particuliers d'hébergements :
1. Mettre le fichier /lib/setup/info.inc.php en mode CHMOD 660 via votre client FTP.
2. Mettre les répertoire /upload et /comparateur en mode CHMOD 775 via votre client FTP.
3. Mettre les fichiers /administrer/peel_produits.xml, /administrer/peel_produits.csv et /administrer/sending.log en mode 660 via votre client FTP.
4. Mettre les fichiers sitemap.xml et urllist.txt en mode 660 via votre client FTP.

Pour les utilisateurs d'Easyphp, il faut renseigner le serveur smtp à utiliser pour l'envoi des messages,
puis éditer le fichier de configuration de PHP (Menu "Configuration/PHP") et rechercher la chaine "SMTP".
Précisez le SMTP a utiliser au lieu de 'localhost' (typiquement : celui de votre FAI), et indiquez une adresse sendmail_from valide.

* MISE A JOUR A PARTIR D'UNE VERSION ANTERIEURE
-----------------------------------------------

Chaque nouvelle version comprend des modifications structurelles de la base de données par rapport à toutes les autres versions.

Vous avez des tutoriaux de migration sur le forum : http://forum.peel.fr/

Pensez à ne pas effacer les données du répertoire /upload qui contient l'ensemble de vos photos

Nous pouvons réimporter toutes les données des logiciels suivants (merci de nous fournir les fichiers SQL d'origine : structure + données)
- PEEL SHOPPING
- Prestashop
- Magento
- OS Commerce toute version
- Boutikone toute version
- Micrologiciel toute version
- Powerboutique
- Autre logiciel : vous pouvez nous faire parvenir un fichiez SQL comprenant structure & données afin de procéder à la transcription de votre logiciel vers PEEL

Contactez-nous sur http://www.peel.fr/ ou au 01 75 43 67 97.

* AJOUT D'UNE LANGUE
--------------------

Le système est livré en français et en anglais, mais peut aussi fonctionner avec toutes les autres langues.

Pour faire fonctionner une langue, il faut
1/ Traduire le fichier /lib/lang/fr.php dans la langue de votre choix (exemple création d'un fichier es.php contenant toutes les variables de langue traduite en espagnol)
2/ Créer un fichier équivalent à /lib/lang/datetime_fr.php dans la langue de votre choix (exemple création d'un fichier datetime_es.php contenant tous les formats de dates en espagnol)
3/ Créer la langue dans l'interface d'administration : Mon compte > Configuration de la boutique > Gérer les langues > Ajouter une langue
   Pour info, Des modèles de drapeaux sont à disposition dans le dossier /lib/flag/. Si toutefois vous voulez ajouter votre propre drapeau, envoyez le par FTP sur votre serveur et indiquez l'URL complète de l'image dans le champ correspondant. Si juste un nom d'image est précisé, il prendra par défaut le dossier /lib/flag/ .

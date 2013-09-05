<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_ecotaxes_fr.php 37904 2013-08-27 21:19:26Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_ecotaxes['nom'] = array(
  "1" => "Réfrigérateur, combiné réfrigérateur-congélateur, Congélateur, Cave à vins, Climatiseur, Autres appareils à fluide frigorigène",
  "2" => "Lave-linge, Sèche-linge, Lave-vaisselle , lave-vaisselle combiné à un autre appareil ne produisant pas de froid (lave-vaisselle/table de cuisson, …, Cuisinière, Four encastrable, four-vapeur, Essoreuse à linge",
  "3" => "Table de cuisson, plaque chauffante électrique, Hotte, groupe filtrant, Four à micro-ondes, micro-ondes multicuisson, tiroir chauffe-plat, Radiateur à accumulation",
  "4" => "Purificateur, Déshumidificateur, Autres équipements pour la ventilation, l'extraction d'air > 5 kg",
  "5" => "Autres équipements pour la ventilation, l'extraction d'air < 5 kg",
  "6" => "Autres équipements pour la ventilation, l'extraction d'air < 500 g",
  "7" => "Chauffe-eau à accumulation, ballon, cumulus",
  "8" => "Panneau rayonnant fixe ou mobile, Panneau radiant, Convecteur ou radiateur électrique fixe ou mobile, Radiateur bain d'huile, Sèche serviette, Couverture électrique, Chauffe-eau instantané, Cheminée électrique, Autres gros appareils pour chauffer les pièces, les lits et les sièges > 5 kg",
  "9" => "Autres gros appareils pour chauffer les pièces, les lits et les sièges < 5 kg",
  "10" => "Autres gros appareils pour chauffer les pièces, les lits et les sièges < 500 g",
  "11" => "Aspirateur traineau, aspirateur eau et poussières, robot, balais, Cireuse, Nettoyeur vapeur, Centrale vapeur, table à repasser active, robot et presse de repassage, Machine à laver portable à agitateur / pulsateur, Mini-four",
  "12" => "Appareil à électrolyse, Appareil à fondue, Appareil à friser / défriser, Appareil à raclette, Appareil de balnéothérapie, Appareil de luminothérapie, Appareil de manucure et pédicure, Appareil de massage, Appareil d'électromusculation, Appareil lampe infrarouge, Appareil pour la beauté des cheveux, Aspirateur rechargeable et aspirateur à main, Balance de cuisine, Blender, Bouilloire, Brosse à dents, Brosse soufflante, Cafetière, Centrifugeuse, Chauffe biberon, Chauffe plat, Chocolatière, Combiné dentaire, Couteau électrique, Crêpière, Croque Monsieur, Cuit vapeur /mijoteur/cuiseur, Détacheur, Epilateur électrique et cire, Equipement pour la beauté du visage, Fabrique à glaçons /sodas, Fer à repasser, Friteuse, Gaufrier, Grille pain, Grille viande, Hachoir, Hydropulseur, Lampe solaire, Machine expresso, Four à pain, Miroir lumineux, Mixeur, Mixeur, mixeur cuiseur/vapeur, Moulin à café, Moulin électrique, Ouvre-boite, Pèse personne, Presse agrume, Rafraîchisseur de boissons, Rasoir, Robot, Saucier, Sauna facial, Sèche cheveux, Sorbetière, Stérilisateur, Théière, Tondeuse à cheveux, …, Trancheuse, Tueur d'insectes, Yaourtière",
  "13" => "Thermomètre, Montre, horloge, réveil, chronomètre",
  "14" => "Moniteur avec écran de taille supérieure à 32 pouces",
  "15" => "Moniteur avec écran de taille supérieure à 20 pouces et inférieure ou égale à 32 pouces",
  "16" => "Moniteur avec écran de taille inférieure ou égale à 20 pouces",
  "17" => "Ordinateur individuel, unité centrale",
  "18" => "Ordinateur portable",
  "19" => "Imprimante (hors imprimante exclusivement photo, Scanner, Télécopieur",
  "20" => "PC de poche, Assistant personnel, Calculatrice, Dictaphone, Téléphone avec ou sans fil, Répondeur, Interphone, Talkie walkie, GPS, Modem, Routeur, WI-FI, routeur d'appel, Appareil externe de stockage de données (disque dur externe, lecteur disquette externe, …, Graveur CD/DVD externe, Décodeur, transcodeur, Clé USB, Petits périphériques : webcam, souris, clavier, haut parleur pour ordinateur, casque, microphone",
  "21" => "Téléphone cellulaire et accessoires",
  "22" => "Poste de télévision avec écran de taille supérieure à 32 pouces et autres grands écrans",
  "23" => "Poste de télévision avec écran de taille supérieure à 20 pouces et inférieure ou égale à 32 pouces",
  "24" => "Poste de télévision avec écran de taille inférieure ou égale à 20 pouces",
  "25" => "Chaîne hi-fi, micro/mini (audio home systems, tous élements intégrés, Amplificateur, ampli home-cinema, ampli-tuner",
  "26" => "Magnétoscope, Lecteur CD, DVD, DIVX, K7, Enregistreur DVD, Platine Disques, Tuner, Disque dur multimédia de salon, Lecteur Karaoké, Vidéoprojecteur, Instrument de musique, Enceinte, caisson, Table de mixage, equalizer",
  "27" => "Télécommande, Set top box, Casque (audio, TV, HIFI, Camescope, caméra numérique, Magnétophone/dictaphone, Baladeur CD, MD, MP3, audio-video, disque dur, solid state, Microphone, Poste de radio, radio K7 et CD portable, transistor, radio portable, Radio-réveil, Appareil - photo, Imprimante photo, Autres accessoires audio/vidéo",
  "28" => "Burineur, décapeur, décolleuse, défonceuse, foreuse, lime, meuleuse, perceuse, perforateur, ponceuse, rabot, scie, rainureuse, Coupe-bordures, taille-haies, tronçonneuse, Autre outillage électroportatif, Fer à souder, Pompe, fontaine avec pompe, Chargeur de batteries, Machine à coudre",
  "29" => "Tondeuse, Nettoyeur Haute Pression, Broyeur de végétaux, Outillage stationnaire, Compresseur",
  "30" => "Jouets et équipements d'un poids inférieur ou égal à 500 grammes",
  "31" => "Jouets et équipements d'un poids supérieur à 500 grammes et inférieur ou égal à 10 kg",
  "32" => "Jouets et équipements d'un poids supérieur à 10 kg",
  "33" => "Appareils pour détecter, prévenir, surveiller, traiter, soulager les maladies, les blessures ou les incapacités, d'un poids supérieur à 5 kg",
  "34" => "Appareils pour détecter, prévenir, surveiller, traiter, soulager les maladies, les blessures ou les incapacités, d'un poids inférieur à 5 kg",
  "35" => "Equipements de mesure, de contrôle et de surveillance sans écran",
  "36" => "Autres équipements de contrôle et de surveillance avec écran",
  "37" => "Distributeurs automatiques"
);

?>
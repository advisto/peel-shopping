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
// $Id: database_ecotaxes_fr.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_ecotaxes['nom'] = array(
  "1.1" => "Réfrigérateur, combiné réfrigérateur-congélateur, Congélateur, Cave à vins, Climatiseur, Autres appareils à fluide frigorigène",
  "1.2" => "Lave-linge, Sèche-linge, Lave-vaisselle , lave-vaisselle combiné à un autre appareil ne produisant pas de froid (lave-vaisselle/table de cuisson, …, Cuisinière, Four encastrable, four-vapeur, Essoreuse à linge",
  "1.3" => "Table de cuisson, plaque chauffante électrique, Hotte, groupe filtrant, Four à micro-ondes, micro-ondes multicuisson, tiroir chauffe-plat, Radiateur à accumulation",
  "1.4" => "Purificateur, Déshumidificateur, Autres équipements pour la ventilation, l'extraction d'air > 5 kg",
  "1.5" => "Autres équipements pour la ventilation, l'extraction d'air < 5 kg",
  "1.6" => "Autres équipements pour la ventilation, l'extraction d'air < 500 g",
  "1.7" => "Chauffe-eau à accumulation, ballon, cumulus",
  "1.8" => "Panneau rayonnant fixe ou mobile, Panneau radiant, Convecteur ou radiateur électrique fixe ou mobile, Radiateur bain d'huile, Sèche serviette, Couverture électrique, Chauffe-eau instantané, Cheminée électrique, Autres gros appareils pour chauffer les pièces, les lits et les sièges > 5 kg",
  "1.9" => "Autres gros appareils pour chauffer les pièces, les lits et les sièges < 5 kg",
  "1.10" => "Autres gros appareils pour chauffer les pièces, les lits et les sièges < 500 g",
  "2.1" => "Aspirateur traineau, aspirateur eau et poussières, robot, balais, Cireuse, Nettoyeur vapeur, Centrale vapeur, table à repasser active, robot et presse de repassage, Machine à laver portable à agitateur / pulsateur, Mini-four",
  "2.2" => "Appareil à électrolyse, Appareil à fondue, Appareil à friser / défriser, Appareil à raclette, Appareil de balnéothérapie, Appareil de luminothérapie, Appareil de manucure et pédicure, Appareil de massage, Appareil d'électromusculation, Appareil lampe infrarouge, Appareil pour la beauté des cheveux, Aspirateur rechargeable et aspirateur à main, Balance de cuisine, Blender, Bouilloire, Brosse à dents, Brosse soufflante, Cafetière, Centrifugeuse, Chauffe biberon, Chauffe plat, Chocolatière, Combiné dentaire, Couteau électrique, Crêpière, Croque Monsieur, Cuit vapeur /mijoteur/cuiseur, Détacheur, Epilateur électrique et cire, Equipement pour la beauté du visage, Fabrique à glaçons /sodas, Fer à repasser, Friteuse, Gaufrier, Grille pain, Grille viande, Hachoir, Hydropulseur, Lampe solaire, Machine expresso, Four à pain, Miroir lumineux, Mixeur, Mixeur, mixeur cuiseur/vapeur, Moulin à café, Moulin électrique, Ouvre-boite, Pèse personne, Presse agrume, Rafraîchisseur de boissons, Rasoir, Robot, Saucier, Sauna facial, Sèche cheveux, Sorbetière, Stérilisateur, Théière, Tondeuse à cheveux, …, Trancheuse, Tueur d'insectes, Yaourtière",
  "2.3" => "Thermomètre, Montre, horloge, réveil, chronomètre",
  "3.1" => "Moniteur avec écran de taille supérieure à 32 pouces",
  "3.2" => "Moniteur avec écran de taille supérieure à 20 pouces et inférieure ou égale à 32 pouces",
  "3.3" => "Moniteur avec écran de taille inférieure ou égale à 20 pouces",
  "3.4" => "Ordinateur individuel, unité centrale",
  "3.5" => "Ordinateur portable",
  "3.6" => "Imprimante (hors imprimante exclusivement photo, Scanner, Télécopieur",
  "3.7" => "PC de poche, Assistant personnel, Calculatrice, Dictaphone, Téléphone avec ou sans fil, Répondeur, Interphone, Talkie walkie, GPS, Modem, Routeur, WI-FI, routeur d'appel, Appareil externe de stockage de données (disque dur externe, lecteur disquette externe, …, Graveur CD/DVD externe, Décodeur, transcodeur, Clé USB, Petits périphériques : webcam, souris, clavier, haut parleur pour ordinateur, casque, microphone",
  "3.8" => "Téléphone cellulaire et accessoires",
  "4.1" => "Poste de télévision avec écran de taille supérieure à 32 pouces et autres grands écrans",
  "4.2" => "Poste de télévision avec écran de taille supérieure à 20 pouces et inférieure ou égale à 32 pouces",
  "4.3" => "Poste de télévision avec écran de taille inférieure ou égale à 20 pouces",
  "4.4" => "Chaîne hi-fi, micro/mini (audio home systems, tous élements intégrés, Amplificateur, ampli home-cinema, ampli-tuner",
  "4.5" => "Magnétoscope, Lecteur CD, DVD, DIVX, K7, Enregistreur DVD, Platine Disques, Tuner, Disque dur multimédia de salon, Lecteur Karaoké, Vidéoprojecteur, Instrument de musique, Enceinte, caisson, Table de mixage, equalizer",
  "4.6" => "Télécommande, Set top box, Casque (audio, TV, HIFI, Camescope, caméra numérique, Magnétophone/dictaphone, Baladeur CD, MD, MP3, audio-video, disque dur, solid state, Microphone, Poste de radio, radio K7 et CD portable, transistor, radio portable, Radio-réveil, Appareil - photo, Imprimante photo, Autres accessoires audio/vidéo",
  "6.1" => "Burineur, décapeur, décolleuse, défonceuse, foreuse, lime, meuleuse, perceuse, perforateur, ponceuse, rabot, scie, rainureuse, Coupe-bordures, taille-haies, tronçonneuse, Autre outillage électroportatif, Fer à souder, Pompe, fontaine avec pompe, Chargeur de batteries, Machine à coudre",
  "6.2" => "Tondeuse, Nettoyeur Haute Pression, Broyeur de végétaux, Outillage stationnaire, Compresseur",
  "7.1" => "Jouets et équipements d'un poids inférieur ou égal à 500 grammes",
  "7.2" => "Jouets et équipements d'un poids supérieur à 500 grammes et inférieur ou égal à 10 kg",
  "7.3" => "Jouets et équipements d'un poids supérieur à 10 kg",
  "8.1" => "Appareils pour détecter, prévenir, surveiller, traiter, soulager les maladies, les blessures ou les incapacités, d'un poids supérieur à 5 kg",
  "8.2" => "Appareils pour détecter, prévenir, surveiller, traiter, soulager les maladies, les blessures ou les incapacités, d'un poids inférieur à 5 kg",
  "9.1" => "Equipements de mesure, de contrôle et de surveillance sans écran",
  "9.2" => "Autres équipements de contrôle et de surveillance avec écran",
  "10.0" => "Distributeurs automatiques"
);


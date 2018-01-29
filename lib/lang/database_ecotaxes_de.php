<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_ecotaxes_de.php 55325 2017-11-30 10:47:17Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_ecotaxes['nom'] = array(
  "1.1" => "Kühlschrank, kombinierter Kühlschrank mit Gefrierfach, Gefrierschrank, Weinkeller, Klimaanlage, andere Geräte mit flüssigem Kältemittel",
  "1.2" => "Waschmaschine, Trockner, Geschirrspüler, Geschirrspüler in Kombination mit anderem Gerät, das keine Kälte erzeugt (Geschirrspüler/Kochmulde, …, Herd, Einbauherd, Dampfofen, Schleuder)",
  "1.3" => "Kochmulde, elektrische Kochplatte, Dunstabzugshaube, Filtereinheit, Mikrowellengerät, Multifunktionsmikrowelle, Warmhaltelade, Boiler",
  "1.4" => "Luftreiniger, Luftentfeuchter, Andere Anlagen für Lüftung, Extraktion von Luft > 5 kg",
  "1.5" => "Andere Anlagen für Lüftung, Extraktion von Luft > 5 kg",
  "1.6" => "Andere Anlagen für Lüftung, Extraktion von Luft > 500 g",
  "1.7" => "Boiler, Ball, Boiler",
  "1.8" => "Radiator fest oder mobil, Heizstrahler, Konvektor oder elektrischer Heizkörper fest oder mobil, Ölradiator Badezimmer, Handtuchtrockner, Heizdecke, Warmwasserheizung, elektrische Heizung, Andere große Geräte zur Wohnraumbeheizung, Betten und Sitzmöbel > 5 kg",
  "1.9" => "Andere große Geräte zur Wohnraumbeheizung, Betten und Sitzmöbel > 5 kg",
  "1.10" => "Andere große Geräte zur Wohnraumbeheizung, Betten und Sitzmöbel > 500 g",
  "2.1" => "Staubsauger, Staub- und Wassersauger, Roboter, Bürsten, Bohnermaschine, Dampfreiniger, Dampfbügeleisen, Bügelroboter, Mobile Waschmaschine mit Schleuder/Pulsator, Miniofen",
  "2.2" => "Elektrolysegerät, Fondue-Gerät, Lockenwickler/Glätteisen, Raclette-Gerät, Balneotherapie-Gerät, Lichttherapie-Gerät, Maniküre- und Pediküre-Gerät, Massage-Gerät, Elektromuskulationsgerät, Infrarot-Gerät, Geräte für die Schönheit der Haare, Akku- und Handstaubsauger, Küchenwaage, Blender, Wasserkessel, Zahnbürste, Bürstenventilator, Kaffeebereiter, Zentrifuge, Flaschenwärmer, Warmhalteplatte, Kakaokanne, Zahnpflegekombination, Elektrisches Messer, Crêpe-Pfanne, Sandwichtoaster, Dämpfer / Bratpfanne / Ofen, Detacheur, Epiliergerät elektrisch und Wachs, Gesichtspflege-Zubehör, Eis- und Sodamaschine, Bügeleisen, Fritteuse, Waffeleisen, Brottoaster, Fleischgrill, Fleischwolf, Munddusche, Solarlampe, Espressomaschine, Brotofen, Leuchtspiegel, Mixer, Blender, Mixer Ofen/Dampf, Kaffeemühle, Elektrische Mühle, Dosenöffner, Personenwaage, Obstpresse, Getränkekühler, Rasierer, Roboter, Saucier, Gesichtssauna, Föhn, Eismaschine, Sterilisator, Teekanne, Haarschneidemaschine, ..., Furniermessermaschine, Insektenvertilger, Joghurtbereiter",
  "2.3" => "Thermometer, Uhr, Wecker, Stoppuhr",
  "3.1" => "Bildschirm größer als 32 Zoll",
  "3.2" => "Bildschirm mit mehr als 20 und weniger als oder gleich 32 Zoll",
  "3.3" => "Bildschirm mit weniger als oder gleich 20 Zoll",
  "3.4" => "PC, CPU",
  "3.5" => " Notebook",
  "3.6" => "Drucker (außer Fotodrucker, Scanner, Fax)",
  "3.7" => "Pocket PC, PDA, Taschenrechner, Diktiergerät, Drahtloses oder drahtgebundenes Telefon, Anrufbeantworter, Sprechanlage, Walkie-Talkie, GPS, Modem, Routeur, Wifi, Anrufrouter, Externes Speichermedium (externe Festplatte, ..., externer CD/DVD-Brenner, Dekoder, Transcoder, USB-Stift, kleine Peripherie: Webcam, Maus, Tastatur, Computerlautsprecher, Kopfhörer, Mikrofon)",
  "3.8" => "Handy-Zubehör",
  "4.1" => "Fernseher größer als 32 Zoll und andere große Bildschirme",
  "4.2" => "Fernseher größer als 20 Zoll und kleiner als oder gleich 32 Zoll",
  "4.3" => "Fernseher kleiner als oder gleich 20 Zoll",
  "4.4" => "Hifi-Anlage, Mikro/Mini (Audio-Heimanlagen, alle integrierten Elemente, Verstärker, Heimkino-Empfänger, Radioempfänger)",
  "4.5" => "Videorekorder, CD-Spieler, DVD, DIVX, K7, DVD-Rekorder, Platin-CD, Tuner, Festplatte für Heim-Multimedia-Anlage, Karaoke-Spieler, Videoprojektor, Instrument, Lautsprecher, Subwoofer, Mischpult, Equalizer",
  "4.6" => "Fernbedienung, Set-Top-Box, Kopfhörer (Audio, TV, HIFI, Camcorder, digitale Kamera, Rekorder/Diktiergerät, tragbarer CD-, MD-, MP3-Spieler, Audio-Video, Festplatte, Solid state disc, Mikrofon, Radio, Radio K7 und tragbarer CD-Spieler, Kofferradio, tragbares Radio, Radiowecker, Fotoapparat, Fotodrucker, Audio-/Video-Zubehör",
  "6.1" => "Zerkleinerer, Entroster, Shredder, Brecher, Bohrer, Feile, Schleifmaschine, Bohrmaschine, Stanzer, Bimsmaschine, Hobel, Säge, Zusammensetzmaschine, Randbeschneider, Heckenschere, Ablängsäge, Andere Elektrowerkzeuge, Lötkolben, Pumpe, Brunnen mit Pumpe, Akkuladegerät, Nähmaschine",
  "6.2" => "Schermaschine, Hochdruckreiniger, Shredder, stationäre Geräte, Kompressor",
  "7.1" => "Spielzeug und Geräte mit einem Gewicht von weniger als oder gleich 500 g",
  "7.2" => "Spielzeug und Geräte mit einem Gewicht von mehr als 500 g und weniger als oder gleich 10 kg",
  "7.3" => "Spielzeug und Geräte mit einem Gewicht von mehr als 10 kg",
  "8.1" => "Geräte zum Feststellen, Vorbeugen, Verfolgen, Behandeln, Erleichtern von Krankheiten, Verletzungen oder Behinderungen mit einem Gewicht von mehr als 5 kg",
  "8.2" => "Geräte zum Feststellen, Vorbeugen, Verfolgen, Behandeln, Erleichtern von Krankheiten, Verletzungen oder Behinderungen mit einem Gewicht von weniger als 5 kg",
  "9.1" => "Geräte für Messung, Kontrolle und Überwachung ohne Anzeige",
  "9.2" => "Andere Geräte für Kontrolle und Überwachung mit Anzeige",
  "10.0" => "Verkaufsautomaten"
);


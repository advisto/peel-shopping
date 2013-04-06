<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_ecotaxes_nl.php 36232 2013-04-05 13:16:01Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_ecotaxes['nom'] = array(
  "1" => "Koelkast, Koelkast met vriesvak, Vriezer, Wijnkelder, Airconditioning, Andere Apparaten met koelmiddel",
  "2" => "Wasmachine, droger, afwasmachine, afwasmachine in combinatie met een ander apparaat produceert geen koude (Kooktoestel, ..., Kachel, ingebouwde oven, stoomoven, centrifuge-machines)",
  "3" => "Kookplaat, elektrische kookplaat, afzuigkap, filter unit, Magnetron, Magnetron multilevel, Warmhoudlade, Boiler",
  "4" => "Purifier, Dehumidifier, Other equipment for ventilation, Air extraction > 5 kg",
  "5" => "Zuiveringsinstallatie, Luchtontvochtiger, andere toestellen voor ventilatie, luchtafzuiging> 5 kg",
  "6" => "Andere uitrusting voor ventilatie, luchtafzuiging <500 g",
  "7" => "Boilers, Bal, Cumulus",
  "8" => "Radiator vast of mobiel, radiatorpaneel, convector of electrische kachel vast of mobiel, Oliekachel, handdoekdroger, Elektrische deken, tankless boilers, elektrische haard, Andere grote apparaten voor de verwarming van kamers, bedden en zitmeubelen > 5 kg",
  "9" => "Andere grote apparaten voor de verwarming van kamers, bedden en zitmeubelen < 5 kg",
  "10" => "Andere grote apparaten voor de verwarming van kamers, bedden en zitmeubelen < 500 g",
  "11" => "Stofzuiger, stofzuiger stof en water, robot, borstels, glans, Stoomreiniger, Stoom strijkijzers, strijkende actieve robot en druk strijken, mobiele wasmachine met centrifuge / pulsator, Mini oven",
  "12" => "Elektrolyse apparatuur, Fondue apparatuur, Elektrisch krullen / strekken, Vloerwisser, therapie apparaat, lichttherapie-apparaat, apparaat voor manicure en pedicure, massage apparaat, apparaat voor electromusculation, Camera infraroodlamp, Apparaten voor de schoonheid van het haar, stofzuiger, oplaadbare handstofzuiger, keuken schaal, mixer, waterkoker, tandenborstel, borstel ventilator, koffiezetapparaat, sappers, flessenwarmer, warmhoud plaat, Chocolade fontein, tandheelkundige combi, elektrisch mes, pannenkoekenpan, tosti ijzer, Stomer / braadpan / fornuis, epileer apparaat electrisch en was, gezichtsverzorgingsproducten, ijs- en frisdrankmachine, Strijkijzer, Friteuse, Waffelmaker, broodrooster, vleesgrill, hakmachine, waterkoker, Solarlamp, espressomachine, broodoven, spiegel met verlichting, blender, mixer, mixer fornuis / stoom, koffiemolen, electrische molen, flesopener, Weegschaal, fruitpers, drank koeler, scheerapparaat, robot, Saucier, Gezicht sauna, haardroger, Sorbet, Sterilisator, Theepot, haartrimmer, ..., snijmachine, insectenverdelger, Yoghurt",
  "13" => "Thermometer, Horloge, klok, wekker, stopwatch",
  "14" => "Beeldscherm met een grootte van meer dan 32 inch",
  "15" => "Beeldscherm met een grootte van meer dan 20 inch en kleiner dan of gelijk aan 32 inch",
  "16" => "Beeldscherm met een grootte kleiner dan of gelijk aan 20 inch",
  "17" => "Personal Computer, CPU",
  "18" => "Laptop",
  "19" => "Printer (Fotoprinter, Scanner, Fax)",
  "20" => "Pocket PC, PDA, rekenmachine, voice recorder, telefoon wel of niet draadloos, Voicemail, Intercom, Walkie Talkie, GPS, Modem, Router, WiFi, call-router, extern gegevensopslagapparaat (externe harde schijf, externe floppy drive, ..., CD / dvd-station, decoder, transcoder, USB, kleine apparaten: webcam, muis, toetsenbord, luidsprekers voor computer, hoofdtelefoon, microfoon",
  "21" => "GSM accessoires",
  "22" => "Televisiescherm groter dan 32 inch en andere grote schermen",
  "23" => "Televisiescherm met een grootte van meer dan 20 inch en kleiner dan of gelijk aan 32 inch",
  "24" => "Televisiescherm met een grootte van minder dan of gelijk aan 20 inch",
  "25" => "Stereo, micro / mini (home audio-systemen, alle elementen geïntegreerd, versterker, home theater receiver, ontvanger)",
  "26" => "Videorecorder, CD-speler, DVD, DIVX, K7, DVD-recorder, Platinum disc, Tuner, HDD Multimedia harde schijf, Karaoke-speler, projector, Muziekinstrument, luidspreker, subwoofer, mixer, equalizer",
  "27" => "Afstandsbediening, set-top box, koptelefoon (audio, TV, stereo, camcorder, digitale camera, recorder / dictafoon, Portable CD, MD, MP3, audio-video, harde schijf, solid state, Microfoon, Radio, Radio K7 en CD draagbare transistor draagbare radio, wekkerradio, Fotocamera, fotoprinter, Accessoires Audio / Video)",
  "28" => "Versnipperaar, stripper, stripper, router, boor, bestand, slijpmachine, boormachine, boormachine, schuurmachine, schaafmachine, zaag, schrijnwerker, Trimmer, trimmers, kettingzagen, Andere elektrisch gereedschap, soldeerbout, Pomp, fontein met pomp acculader, naaimachine",
  "29" => "Grasmaaier, hogedrukreiniger, hakselaar, stationaire machines, compressor",
  "30" => "Speelgoed en apparatuur met een gewicht van minder dan of gelijk aan 500 gram",
  "31" => "Speelgoed en apparatuur met een gewicht van meer dan 500 gram en minder dan of gelijk aan 10 kg",
  "32" => "Speelgoed en apparatuur met een gewicht van meer 10 kg",
  "33" => "Apparaten voor het opsporen, voorkomen, volgen, behandelen, verlichten van ziekten, verwondingen of handicaps, met een gewicht van meer dan 5 kg",
  "34" => "Apparaten voor het opsporen, voorkomen, volgen, behandelen, verlichten van ziekten, verwondingen of handicaps, met een gewicht van minder dan 5 kg",
  "35" => "Apparatuur voor meting, controle en bewaking zonder display",
  "36" => "Andere apparatuur voor controle en bewaking met display",
  "37" => "verkoopautomaten"
);

?>
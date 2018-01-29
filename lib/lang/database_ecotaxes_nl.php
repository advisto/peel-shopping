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
// $Id: database_ecotaxes_nl.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_ecotaxes['nom'] = array(
  "1.1" => "Koelkast, Koelkast met vriesvak, Vriezer, Wijnkelder, Airconditioning, Andere Apparaten met koelmiddel",
  "1.2" => "Wasmachine, droger, afwasmachine, afwasmachine in combinatie met een ander apparaat produceert geen koude (Kooktoestel, ..., Kachel, ingebouwde oven, stoomoven, centrifuge-machines)",
  "1.3" => "Kookplaat, elektrische kookplaat, afzuigkap, filter unit, Magnetron, Magnetron multilevel, Warmhoudlade, Boiler",
  "1.4" => "Purifier, Dehumidifier, Other equipment for ventilation, Air extraction > 5 kg",
  "1.5" => "Zuiveringsinstallatie, Luchtontvochtiger, andere toestellen voor ventilatie, luchtafzuiging> 5 kg",
  "1.6" => "Andere uitrusting voor ventilatie, luchtafzuiging <500 g",
  "1.7" => "Boilers, Bal, Cumulus",
  "1.8" => "Radiator vast of mobiel, radiatorpaneel, convector of electrische kachel vast of mobiel, Oliekachel, handdoekdroger, Elektrische deken, tankless boilers, elektrische haard, Andere grote apparaten voor de verwarming van kamers, bedden en zitmeubelen > 5 kg",
  "1.9" => "Andere grote apparaten voor de verwarming van kamers, bedden en zitmeubelen < 5 kg",
  "1.10" => "Andere grote apparaten voor de verwarming van kamers, bedden en zitmeubelen < 500 g",
  "2.1" => "Stofzuiger, stofzuiger stof en water, robot, borstels, glans, Stoomreiniger, Stoom strijkijzers, strijkende actieve robot en druk strijken, mobiele wasmachine met centrifuge / pulsator, Mini oven",
  "2.2" => "Elektrolyse apparatuur, Fondue apparatuur, Elektrisch krullen / strekken, Vloerwisser, therapie apparaat, lichttherapie-apparaat, apparaat voor manicure en pedicure, massage apparaat, apparaat voor electromusculation, Camera infraroodlamp, Apparaten voor de schoonheid van het haar, stofzuiger, oplaadbare handstofzuiger, keuken schaal, mixer, waterkoker, tandenborstel, borstel ventilator, koffiezetapparaat, sappers, flessenwarmer, warmhoud plaat, Chocolade fontein, tandheelkundige combi, elektrisch mes, pannenkoekenpan, tosti ijzer, Stomer / braadpan / fornuis, epileer apparaat electrisch en was, gezichtsverzorgingsproducten, ijs- en frisdrankmachine, Strijkijzer, Friteuse, Waffelmaker, broodrooster, vleesgrill, hakmachine, waterkoker, Solarlamp, espressomachine, broodoven, spiegel met verlichting, blender, mixer, mixer fornuis / stoom, koffiemolen, electrische molen, flesopener, Weegschaal, fruitpers, drank koeler, scheerapparaat, robot, Saucier, Gezicht sauna, haardroger, Sorbet, Sterilisator, Theepot, haartrimmer, ..., snijmachine, insectenverdelger, Yoghurt",
  "2.3" => "Thermometer, Horloge, klok, wekker, stopwatch",
  "3.1" => "Beeldscherm met een grootte van meer dan 32 inch",
  "3.2" => "Beeldscherm met een grootte van meer dan 20 inch en kleiner dan of gelijk aan 32 inch",
  "3.3" => "Beeldscherm met een grootte kleiner dan of gelijk aan 20 inch",
  "3.4" => "Personal Computer, CPU",
  "3.5" => "Laptop",
  "3.6" => "Printer (Fotoprinter, Scanner, Fax)",
  "3.7" => "Pocket PC, PDA, rekenmachine, voice recorder, telefoon wel of niet draadloos, Voicemail, Intercom, Walkie Talkie, GPS, Modem, Router, WiFi, call-router, extern gegevensopslagapparaat (externe harde schijf, externe floppy drive, ..., CD / dvd-station, decoder, transcoder, USB, kleine apparaten: webcam, muis, toetsenbord, luidsprekers voor computer, hoofdtelefoon, microfoon",
  "3.8" => "GSM accessoires",
  "4.1" => "Televisiescherm groter dan 32 inch en andere grote schermen",
  "4.2" => "Televisiescherm met een grootte van meer dan 20 inch en kleiner dan of gelijk aan 32 inch",
  "4.3" => "Televisiescherm met een grootte van minder dan of gelijk aan 20 inch",
  "4.4" => "Stereo, micro / mini (home audio-systemen, alle elementen geïntegreerd, versterker, home theater receiver, ontvanger)",
  "4.5" => "Videorecorder, CD-speler, DVD, DIVX, K7, DVD-recorder, Platinum disc, Tuner, HDD Multimedia harde schijf, Karaoke-speler, projector, Muziekinstrument, luidspreker, subwoofer, mixer, equalizer",
  "4.6" => "Afstandsbediening, set-top box, koptelefoon (audio, TV, stereo, camcorder, digitale camera, recorder / dictafoon, Portable CD, MD, MP3, audio-video, harde schijf, solid state, Microfoon, Radio, Radio K7 en CD draagbare transistor draagbare radio, wekkerradio, Fotocamera, fotoprinter, Accessoires Audio / Video)",
  "6.1" => "Versnipperaar, stripper, stripper, router, boor, bestand, slijpmachine, boormachine, boormachine, schuurmachine, schaafmachine, zaag, schrijnwerker, Trimmer, trimmers, kettingzagen, Andere elektrisch gereedschap, soldeerbout, Pomp, fontein met pomp acculader, naaimachine",
  "6.2" => "Grasmaaier, hogedrukreiniger, hakselaar, stationaire machines, compressor",
  "7.1" => "Speelgoed en apparatuur met een gewicht van minder dan of gelijk aan 500 gram",
  "7.2" => "Speelgoed en apparatuur met een gewicht van meer dan 500 gram en minder dan of gelijk aan 10 kg",
  "7.3" => "Speelgoed en apparatuur met een gewicht van meer 10 kg",
  "8.1" => "Apparaten voor het opsporen, voorkomen, volgen, behandelen, verlichten van ziekten, verwondingen of handicaps, met een gewicht van meer dan 5 kg",
  "8.2" => "Apparaten voor het opsporen, voorkomen, volgen, behandelen, verlichten van ziekten, verwondingen of handicaps, met een gewicht van minder dan 5 kg",
  "9.1" => "Apparatuur voor meting, controle en bewaking zonder display",
  "9.2" => "Andere apparatuur voor controle en bewaking met display",
  "10.0" => "verkoopautomaten"
);


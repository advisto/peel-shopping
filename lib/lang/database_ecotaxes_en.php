<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_ecotaxes_en.php 38682 2013-11-13 11:35:48Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_ecotaxes['nom'] = array(
  "1" => "Fridge, Fridge-freezer, Freezer, Wine Cellar, Air Conditioning, Other Devices refrigerant",
  "2" => "Washer, Dryer, Dishwasher, Dishwasher combined with another device does not produce cold (Cooking device, ..., Stove, Built-in oven, four-Vapour, centrifuge machines)",
  "3" => "Hob, Electric hob, Hood, Filter unit, Microwave oven, Microwave mutilevel, Warming drawer, Storage heater",
  "4" => "Purifier, Dehumidifier, Other equipment for ventilation, Air extraction > 5 kg",
  "5" => "Other equipment for ventilation, Air extraction < 5 kg",
  "6" => "Other equipment for ventilation, Air extraction < 500 g",
  "7" => "Boiler storage, Balloon, Cumulus",
  "8" => "Control radiating fixed or mobile panel radiant convector heater or fixed or mobile, Radiator oil bath towel dryer, Electric Blanket, Tankless Water Heaters, Electric Fireplace, Other large appliances for heating rooms, beds and seats > 5 kg",
  "9" => "Other large appliances for heating rooms, Beds and seats < 5 kg",
  "10" => "Other large appliances for heating rooms, Beds and seats < 500 g",
  "11" => "Vacuum cleaner, wet and dry vacuum cleaner, robot, brushes, shine, Steam Cleaner, Steam irons, ironing active robot and press ironing, washing machine agitator portable / pulsator, Mini-oven",
  "12" => "Electrolysis apparatus, Fondue, Electric curling / straightening, Raclette, device therapy, Light Therapy Device, Device for manicure and pedicure, massage apparatus, device electromusculation, Camera infrared lamp, Apparatus for the beauty of hair, vacuum cleaner, rechargeable hand vac, kitchen scale, blender, kettle, toothbrush, brush fan, Coffee, Juice, Bottle warmer, heater flat Chocolatière combo, dental, electric knife, crepe, Croque Monsieur, Steamer / Simmer / cooker, Stain, and Wax Epilator, Beauty Equipment for facial Factory Ice / sodas, Iron, Fryer, Waffle Maker, Toaster, grill meat grinder, Water Jet, Solar Lamp, espresso machine, bread oven, mirror light, blender, mixer, blender cooker / steam, coffee grinder, mill electric opener, box, Scales, Fruit squeezer, Cooler beverages, Razor, Robot, Saucier, Facial Sauna, hairdryer, Sorbet, Sterilizer, Teapot, hair clipper, ..., slicer, insect killer, Yoghurt",
  "13" => "Thermometer, Watch, clock, alarm clock, stopwatch",
  "14" => "Monitor with a screen size greater than 32 inches",
  "15" => "Monitor screen with a size greater than 20 inches and less than or equal to 32 inches",
  "16" => "Monitor screen with a size of less than or equal to 20 inches",
  "17" => "Personal Computer, CPU",
  "18" => "Laptop",
  "19" => "Printer (printer only off photo, Scanner, Fax",
  "20" => "Pocket PC, PDA, Calculator, Voice recorder, telephone or wireless, Voicemail, Intercom, Walkie Talkie, GPS, Modem, Router, WiFi, call router, external storage device data (external hard drive, external floppy drive, ..., CD / DVD drive, decoder, transcoder, USB, Small Devices: webcam, mouse, keyboard, speaker for computer, headphone, microphone",
  "21" => "Cell Phone Accessories",
  "22" => "My television screen larger than 32 inches and other large screens",
  "23" => "My TV screen size greater than 20 inches and less than or equal to 32 inches",
  "24" => "My TV screen size exceeding 20 inch",
  "25" => "Stereo, micro / mini (home audio systems, all elements integrated amplifier, home theater receiver, receiver",
  "26" => "VCR, CD, DVD, DIVX, K7, DVD Recorder, Platinum Disc, Tuner, HDD multimedia lounge, Karaoke Player, Projector, Musical Instrument, speaker, subwoofer, mixer, equalizer",
  "27" => "Remote Control, Set top box, helmet (audio, TV, stereo, camcorder, digital camera, recorder / dictaphone, Portable CD, MD, MP3, audio-video, hard drive, solid state, Microphone, Computer radio, portable CD and K7 radio, transistor, portable radio, Clock Radio, Camera - photo, photo printer, Accessories Audio / Video)",
  "28" => "Chipper, stripper, stripper, router, drill, file, grinder, drill, drill, sander, planer, saw, joiner, edgers, trimmers, chainsaws, Other Power Tools, Soldering Iron, pump, fountain pump, Battery charger, Sewing Machine",
  "29" => "mower, pressure washer, chipper shredder, Tools stationary compressor",
  "30" => "Toys and equipment weighing less than or equal to 500 grams",
  "31" => "Toys and equipment weighing more than 500 grams and less than or equal to 10 kg",
  "32" => "Toys and equipment weighing more than 10 kg",
  "33" => "Apparatus for detecting, preventing, monitoring, treating, alleviating illness, injury or disability, weighing more than 5 kg",
  "34" => "Apparatus for detecting, preventing, monitoring, treating, alleviating illness, injury or disability, weighing less than 5 kg",
  "35" => "Equipment for measurement, Control and monitoring headless",
  "36" => "Other equipment monitoring and control screen",
  "37" => "Vending Machines"
);

?>
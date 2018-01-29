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
// $Id: database_ecotaxes_en.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_ecotaxes['nom'] = array(
  "1.1" => "Fridge, Fridge-freezer, Freezer, Wine Cellar, Air Conditioning, Other Devices refrigerant",
  "1.2" => "Washer, Dryer, Dishwasher, Dishwasher combined with another device does not produce cold (Cooking device, ..., Stove, Built-in oven, four-Vapour, centrifuge machines)",
  "1.3" => "Hob, Electric hob, Hood, Filter unit, Microwave oven, Microwave mutilevel, Warming drawer, Storage heater",
  "1.4" => "Purifier, Dehumidifier, Other equipment for ventilation, Air extraction > 5 kg",
  "1.5" => "Other equipment for ventilation, Air extraction < 5 kg",
  "1.6" => "Other equipment for ventilation, Air extraction < 500 g",
  "1.7" => "Boiler storage, Balloon, Cumulus",
  "1.8" => "Control radiating fixed or mobile panel radiant convector heater or fixed or mobile, Radiator oil bath towel dryer, Electric Blanket, Tankless Water Heaters, Electric Fireplace, Other large appliances for heating rooms, beds and seats > 5 kg",
  "1.9" => "Other large appliances for heating rooms, Beds and seats < 5 kg",
  "1.10" => "Other large appliances for heating rooms, Beds and seats < 500 g",
  "2.1" => "Vacuum cleaner, wet and dry vacuum cleaner, robot, brushes, shine, Steam Cleaner, Steam irons, ironing active robot and press ironing, washing machine agitator portable / pulsator, Mini-oven",
  "2.2" => "Electrolysis apparatus, Fondue, Electric curling / straightening, Raclette, device therapy, Light Therapy Device, Device for manicure and pedicure, massage apparatus, device electromusculation, Camera infrared lamp, Apparatus for the beauty of hair, vacuum cleaner, rechargeable hand vac, kitchen scale, blender, kettle, toothbrush, brush fan, Coffee, Juice, Bottle warmer, heater flat Chocolatière combo, dental, electric knife, crepe, Croque Monsieur, Steamer / Simmer / cooker, Stain, and Wax Epilator, Beauty Equipment for facial Factory Ice / sodas, Iron, Fryer, Waffle Maker, Toaster, grill meat grinder, Water Jet, Solar Lamp, espresso machine, bread oven, mirror light, blender, mixer, blender cooker / steam, coffee grinder, mill electric opener, box, Scales, Fruit squeezer, Cooler beverages, Razor, Robot, Saucier, Facial Sauna, hairdryer, Sorbet, Sterilizer, Teapot, hair clipper, ..., slicer, insect killer, Yoghurt",
  "2.3" => "Thermometer, Watch, clock, alarm clock, stopwatch",
  "3.1" => "Monitor with a screen size greater than 32 inches",
  "3.2" => "Monitor screen with a size greater than 20 inches and less than or equal to 32 inches",
  "3.3" => "Monitor screen with a size of less than or equal to 20 inches",
  "3.4" => "Personal Computer, CPU",
  "3.5" => "Laptop",
  "3.6" => "Printer (printer only off photo, Scanner, Fax",
  "3.7" => "Pocket PC, PDA, Calculator, Voice recorder, telephone or wireless, Voicemail, Intercom, Walkie Talkie, GPS, Modem, Router, WiFi, call router, external storage device data (external hard drive, external floppy drive, ..., CD / DVD drive, decoder, transcoder, USB, Small Devices: webcam, mouse, keyboard, speaker for computer, headphone, microphone",
  "3.8" => "Cell Phone Accessories",
  "4.1" => "My television screen larger than 32 inches and other large screens",
  "4.2" => "My TV screen size greater than 20 inches and less than or equal to 32 inches",
  "4.3" => "My TV screen size exceeding 20 inch",
  "4.4" => "Stereo, micro / mini (home audio systems, all elements integrated amplifier, home theater receiver, receiver",
  "4.5" => "VCR, CD, DVD, DIVX, K7, DVD Recorder, Platinum Disc, Tuner, HDD multimedia lounge, Karaoke Player, Projector, Musical Instrument, speaker, subwoofer, mixer, equalizer",
  "4.6" => "Remote Control, Set top box, helmet (audio, TV, stereo, camcorder, digital camera, recorder / dictaphone, Portable CD, MD, MP3, audio-video, hard drive, solid state, Microphone, Computer radio, portable CD and K7 radio, transistor, portable radio, Clock Radio, Camera - photo, photo printer, Accessories Audio / Video)",
  "6.1" => "Chipper, stripper, stripper, router, drill, file, grinder, drill, drill, sander, planer, saw, joiner, edgers, trimmers, chainsaws, Other Power Tools, Soldering Iron, pump, fountain pump, Battery charger, Sewing Machine",
  "6.2" => "mower, pressure washer, chipper shredder, Tools stationary compressor",
  "7.1" => "Toys and equipment weighing less than or equal to 500 grams",
  "7.2" => "Toys and equipment weighing more than 500 grams and less than or equal to 10 kg",
  "7.3" => "Toys and equipment weighing more than 10 kg",
  "8.1" => "Apparatus for detecting, preventing, monitoring, treating, alleviating illness, injury or disability, weighing more than 5 kg",
  "8.2" => "Apparatus for detecting, preventing, monitoring, treating, alleviating illness, injury or disability, weighing less than 5 kg",
  "9.1" => "Equipment for measurement, Control and monitoring headless",
  "9.2" => "Other equipment monitoring and control screen",
  "10.0" => "Vending Machines"
);


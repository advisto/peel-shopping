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
// $Id: database_ecotaxes_es.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_ecotaxes['nom'] = array(
  "1.1" => "Frigorífico, congelador y frigorífico, congelador, Bodega, Aire acondicionado, frigorífico Otros dispositivos",
  "1.2" => "Lavadora, secadora, lavavajillas, lavavajillas combinado con otro dispositivo no produce frío (cocina lave-vaisselle/table, ..., Estufa, Horno encastrado, cuatro de vapor, centrifugadoras)",
  "1.3" => "Vitrocerámica, horno eléctrico, campana extractora, unidad de filtro, horno de microondas, microondas multinivel, cajón calentador, calentador de almacenamiento",
  "1.4" => "Purificador, Deshumidificador, Otros equipos para ventilación, extracción de aire > 5 kg",
  "1.5" => "Otros equipos de ventilación, extracción de aire < 5 kg",
  "1.6" => "Otros equipos para ventilación, extracción de aire < 500 g",
  "1.7" => "El almacenamiento de la caldera, bola cúmulo",
  "1.8" => "Control panel radiante fijo o móvil calentador radiante convector o fijo o móvil, aceite Radiador secador de toallas de baño, manta eléctrica, calentadores de agua sin tanque, chimenea eléctrica, Otros grandes aparatos utilizados para calentar habitaciones, camas y asientos > 5 kg",
  "1.9" => "Otros electrodomésticos grandes para calentar habitaciones, camas y asientos <5 kg",
  "1.10" => "Otros electrodomésticos grandes para calentar habitaciones, camas y asientos <500 g",
  "2.1" => "Aspirador, aspirador mojado y seco, robot, cepillos, brillo, limpiador de vapor, planchas de vapor, robot plancha y tabla de planchar activo prensa, agitador lavadora portátil / pulsador, Mini-horno",
  "2.2" => "Aparato de electrólisis, Fondue, se encrespa eléctrico / enderezado, Raclette, terapia dispositivo, Dispositivo de Fototerapia, Dispositivo para la manicura y pedicura, aparatos de masaje, electromusculation dispositivo, lámpara de infrarrojos Cámara , Aparato para la belleza del cabello, aspiradora, aspiradora de mano recargable, balanza de cocina, cepillo de dientes licuadora, hervidor de agua, ventilador cepillo, café, jugo, calienta biberones, combo calentador plano Chocolatiere, cuchillo dental, eléctrico, crepe, Croque Monsieur, Vaporera / Simmer / cocina, Teñido, y Depiladora cera, equipo de la belleza de la fábrica de hielo facial / refrescos, plancha, freidora, waflera, tostadora, molino parilla, irrigador, Lámpara Solar , máquina de café, horno de pan, la luz espejo, licuadora, batidora, licuadora olla / vapor, molinillo de café, molino eléctrico abridor, caja, Balanzas, exprimidor de frutas, bebidas frías, Razor, Robot, Saucier, Sauna Facial, secador de pelo, Sorbete, Esterilizador, Tetera, cortadora de cabello, ..., máquina de cortar, exterminador de insectos, Yogur",
  "2.3" => "Termómetro, reloj, reloj, reloj alarma, cronómetro",
  "3.1" => "Monitor con un tamaño de pantalla superior a 32 pulgadas",
  "3.2" => "Pantalla del monitor con un tamaño superior a 20 pulgadas y menor o igual a 32 pulgadas",
  "3.3" => "Pantalla del monitor con un tamaño de menos de o igual a 20 pulgadas",
  "3.4" => "Personal Computer, CPU",
  "3.5" => "Laptop",
  "3.6" => "Impresoras (sólo la impresora de fotos, escáner y fax)",
  "3.7" => "Pocket PC, PDA, calculadora, grabadora de voz, teléfono o móvil, correo de voz, Intercom, walkie talkie, GPS, Modem, Router, WiFi, el enrutador de llamada, el dispositivo de almacenamiento externo datos (disco duro externo, unidad de disquete externa, ..., CD / DVD, decodificador, transcodificador, USB, dispositivos pequeños: webcam, ratón, teclado, altavoces para ordenador, salida de auriculares, entrada de micrófono",
  "3.8" => "Accesorios para teléfonos móviles",
  "4.1" => "Mi pantalla de televisión de más de 32 pulgadas y otros grandes pantallas",
  "4.2" => "Pulgadas Mi tamaño de la pantalla de televisión mayor que 20 y menor o igual a 32 pulgadas",
  "4.3" => "El tamaño de mi pantalla de TV o igual a 20 pulgadas",
  "4.4" => "Stereo, micro / mini (sistemas de audio para el hogar, todos los elementos integrados amplificador, receptor de cine en casa, el receptor",
  "4.5" => "VCR, CD, DVD, DIVX, K7, Grabador DVD, Disco de Platino, Tuner, salón HDD multimedia, Karaoke Player, proyector, instrumentos musicales, altavoces, subwoofer, mezclador, ecualizador",
  "4.6" => "Control Remoto, Set top box, casco (audio, TV, equipo de música, cámara de vídeo, cámara digital, grabadora / dictáfono, CD portátil, MD, MP3, audio-video, disco duro de estado sólido, Micrófono, Ordenador radio, reproductor portátil de CD y radio K7, transistor, radio portátil, Radio Reloj, cámara - foto, impresora fotográfica, Accesorios Audio / Video",
  "6.1" => "Chipper, stripper, stripper, router, taladro, archivo, amoladora, taladro, taladro, lijadora, cepillo, sierra, carpintero, bordeadoras, podadoras, sierras de cadena, Otras herramientas de energía, Soldador, bomba, bomba de la fuente, cargador de batería, Máquina de coser",
  "6.2" => "Cortadora de césped, lavado a presión, para patios, compresor Herramientas estacionario",
  "7.1" => "Los juguetes y equipo de peso inferior o igual a 500 gramos",
  "7.2" => "Juguetes y equipos con un peso de 500 gramos y menor o igual a 10 kg",
  "7.3" => "Juguetes y equipos que pesen más de 10 kg",
  "8.1" => "Aparato para detectar, prevenir, supervisar, tratar o aliviar enfermedades, lesiones o discapacidades, con un peso de más de 5 kg",
  "8.2" => "Aparato para detectar, prevenir, supervisar, tratar o aliviar enfermedades, lesiones o discapacidades, que pesa menos de 5 kg",
  "9.1" => "Equipos de medida, control y vigilancia sin cabeza",
  "9.2" => "Otros equipos de vigilancia y control de la pantalla",
  "10.0" => "Máquinas expendedoras"
);


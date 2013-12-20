<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_ecotaxes_es.php 39392 2013-12-20 11:08:42Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_ecotaxes['nom'] = array(
  "1" => "Frigorífico, congelador y frigorífico, congelador, Bodega, Aire acondicionado, frigorífico Otros dispositivos",
  "2" => "Lavadora, secadora, lavavajillas, lavavajillas combinado con otro dispositivo no produce frío (cocina lave-vaisselle/table, ..., Estufa, Horno encastrado, cuatro de vapor, centrifugadoras)",
  "3" => "Vitrocerámica, horno eléctrico, campana extractora, unidad de filtro, horno de microondas, microondas multinivel, cajón calentador, calentador de almacenamiento",
  "4" => "Purificador, Deshumidificador, Otros equipos para ventilación, extracción de aire > 5 kg",
  "5" => "Otros equipos de ventilación, extracción de aire < 5 kg",
  "6" => "Otros equipos para ventilación, extracción de aire < 500 g",
  "7" => "El almacenamiento de la caldera, bola cúmulo",
  "8" => "Control panel radiante fijo o móvil calentador radiante convector o fijo o móvil, aceite Radiador secador de toallas de baño, manta eléctrica, calentadores de agua sin tanque, chimenea eléctrica, Otros grandes aparatos utilizados para calentar habitaciones, camas y asientos > 5 kg",
  "9" => "Otros electrodomésticos grandes para calentar habitaciones, camas y asientos <5 kg",
  "10" => "Otros electrodomésticos grandes para calentar habitaciones, camas y asientos <500 g",
  "11" => "Aspirador, aspirador mojado y seco, robot, cepillos, brillo, limpiador de vapor, planchas de vapor, robot plancha y tabla de planchar activo prensa, agitador lavadora portátil / pulsador, Mini-horno",
  "12" => "Aparato de electrólisis, Fondue, se encrespa eléctrico / enderezado, Raclette, terapia dispositivo, Dispositivo de Fototerapia, Dispositivo para la manicura y pedicura, aparatos de masaje, electromusculation dispositivo, lámpara de infrarrojos Cámara , Aparato para la belleza del cabello, aspiradora, aspiradora de mano recargable, balanza de cocina, cepillo de dientes licuadora, hervidor de agua, ventilador cepillo, café, jugo, calienta biberones, combo calentador plano Chocolatiere, cuchillo dental, eléctrico, crepe, Croque Monsieur, Vaporera / Simmer / cocina, Teñido, y Depiladora cera, equipo de la belleza de la fábrica de hielo facial / refrescos, plancha, freidora, waflera, tostadora, molino parilla, irrigador, Lámpara Solar , máquina de café, horno de pan, la luz espejo, licuadora, batidora, licuadora olla / vapor, molinillo de café, molino eléctrico abridor, caja, Balanzas, exprimidor de frutas, bebidas frías, Razor, Robot, Saucier, Sauna Facial, secador de pelo, Sorbete, Esterilizador, Tetera, cortadora de cabello, ..., máquina de cortar, exterminador de insectos, Yogur",
  "13" => "Termómetro, reloj, reloj, reloj alarma, cronómetro",
  "14" => "Monitor con un tamaño de pantalla superior a 32 pulgadas",
  "15" => "Pantalla del monitor con un tamaño superior a 20 pulgadas y menor o igual a 32 pulgadas",
  "16" => "Pantalla del monitor con un tamaño de menos de o igual a 20 pulgadas",
  "17" => "Personal Computer, CPU",
  "18" => "Laptop",
  "19" => "Impresoras (sólo la impresora de fotos, escáner y fax)",
  "20" => "Pocket PC, PDA, calculadora, grabadora de voz, teléfono o móvil, correo de voz, Intercom, walkie talkie, GPS, Modem, Router, WiFi, el enrutador de llamada, el dispositivo de almacenamiento externo datos (disco duro externo, unidad de disquete externa, ..., CD / DVD, decodificador, transcodificador, USB, dispositivos pequeños: webcam, ratón, teclado, altavoces para ordenador, salida de auriculares, entrada de micrófono",
  "21" => "Accesorios para teléfonos móviles",
  "22" => "Mi pantalla de televisión de más de 32 pulgadas y otros grandes pantallas",
  "23" => "Pulgadas Mi tamaño de la pantalla de televisión mayor que 20 y menor o igual a 32 pulgadas",
  "24" => "El tamaño de mi pantalla de TV o igual a 20 pulgadas",
  "25" => "Stereo, micro / mini (sistemas de audio para el hogar, todos los elementos integrados amplificador, receptor de cine en casa, el receptor",
  "26" => "VCR, CD, DVD, DIVX, K7, Grabador DVD, Disco de Platino, Tuner, salón HDD multimedia, Karaoke Player, proyector, instrumentos musicales, altavoces, subwoofer, mezclador, ecualizador",
  "27" => "Control Remoto, Set top box, casco (audio, TV, equipo de música, cámara de vídeo, cámara digital, grabadora / dictáfono, CD portátil, MD, MP3, audio-video, disco duro de estado sólido, Micrófono, Ordenador radio, reproductor portátil de CD y radio K7, transistor, radio portátil, Radio Reloj, cámara - foto, impresora fotográfica, Accesorios Audio / Video",
  "28" => "Chipper, stripper, stripper, router, taladro, archivo, amoladora, taladro, taladro, lijadora, cepillo, sierra, carpintero, bordeadoras, podadoras, sierras de cadena, Otras herramientas de energía, Soldador, bomba, bomba de la fuente, cargador de batería, Máquina de coser",
  "29" => "Cortadora de césped, lavado a presión, para patios, compresor Herramientas estacionario",
  "30" => "Los juguetes y equipo de peso inferior o igual a 500 gramos",
  "31" => "Juguetes y equipos con un peso de 500 gramos y menor o igual a 10 kg",
  "32" => "Juguetes y equipos que pesen más de 10 kg",
  "33" => "Aparato para detectar, prevenir, supervisar, tratar o aliviar enfermedades, lesiones o discapacidades, con un peso de más de 5 kg",
  "34" => "Aparato para detectar, prevenir, supervisar, tratar o aliviar enfermedades, lesiones o discapacidades, que pesa menos de 5 kg",
  "35" => "Equipos de medida, control y vigilancia sin cabeza",
  "36" => "Otros equipos de vigilancia y control de la pantalla",
  "37" => "Máquinas expendedoras"
);

?>
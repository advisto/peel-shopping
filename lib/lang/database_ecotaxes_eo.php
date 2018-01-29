<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
// +----------------------------------------------------------------------+
// $Id: database_ecotaxes_eo.php 55325 2017-11-30 10:47:17Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_ecotaxes['nom'] = array(
  "1.1" => "Fridujo, kombinaĵo de fridujo kaj frostujo, frostujo, vinkelo, klimatizilo, aliaj aparatoj kun malvarmiga likvo",
  "1.2" => "Lavmaŝino, tol-sekigilo, teler-lavilo, kombinaĵo de teler-lavilo kun aliaa aparato ne produktante malvarmon (teler-lavilo k tabul-kuirilo, ..., fornelo, mur-stovo, vapor-stovo, tol-sekigilo",
  "1.3" => "Fornelo, elektra-kuirplato, ventolilo, filtro-ventolilo, mikroondo-forno, plurkuira mikroondo-forno, plad-varmiga tirkesto, akumul-radiatoro",
  "1.4" => "Aer-purigilo, aer-sekigilo, aliaj ventolaj aparatoj, aer-eltiro > 5 kg",
  "1.5" => "Aliaj ventolaj aparatoj, aer-eltiro < 5 kg",
  "1.6" => "Aliaj ventolaj aparatoj, aer-eltiro < 500 g",
  "1.7" => "Akumula akvo-varmigilo, varmakva rezervujo, varmakva rezervujo tipo \"Cumulus\"",
  "1.8" => "Radiada varmig-panelo fiksa aŭ movebla, radiada varmig-panelo, elektra konvekta aŭ radiatora varmigilo fiksa aŭ movebla, oleo-radiatoro, bantuko-sekigilo, elektra kovrilo, tuj-akvovarmigilo, elektra kameno, aliaj grandaj ĉambro-, lito- aŭ seĝo-varmigiloj > 5 kg",
  "1.9" => "Aliaj grandaj ĉambro-, lito- aŭ seĝo-varmigiloj < 5 kg",
  "1.10" => "Aliaj grandaj ĉambro-, lito- aŭ seĝo-varmigiloj < 500 g",
  "2.1" => "Trena-polvosuĉilo, akvo- aŭ polvo-suĉilo, purigo-roboto, balailo, vaksimaŝino, vapor-purigilo, vapor-purigilego, aktiva glado-stablo, glad-roboto kaj glada prem-stablo, portebla lavmaŝino kun agit- aŭ puls-miskilo, forneto",
  "2.2" => "Elektroliza aparato, fondua aparato, friz- aŭ malfriz-aparato, fandfromaĝ-aparato, bankuraca aparato, lumkuraca aparato, manflega kaj piedflega aparato, masaĝa aparato, elektromuskoliga aparato, infraruĝa lampo, aparato por beleco de hararo, reŝarĝebla kaj mana polvosuĉilo, kuireja pesilo, potmiksilo, boligilo, dentobroso, blovbroso, kafmaŝino, centrofuĝilo, suĉbotela varmigilo, plada varmigilo, ĉokoladujo, elektra dentobroso, elektra tranĉilo, krespilo, sandviĉilo, elektra vaporkuirilo /  varmetakuirilo / premomarmito, senmakulilo, elektra aŭ vaksa senharigilo, aparatoj por beleco de vizaĝo, glacikubilo, sodakvilo, gladilo, fritilo, vaflilo, panrostilo, viandrostilo, haketilo, akvopulsilo, sun-lampo, espreso-maŝino, panforno, lamp-spegulo, misksilo, potmiksilo, miks-vapor-kuirilo, kafmuelilo, elektra muelilo, ladskatol-malfermilo, personpesilo, citruspremilo, trinkaĵ-malvarmigilo, razilo, kuireja ilo, saŭcujo, vizaĝa saŭnilo, har-sekigilo, glaciaĵilo, sterilizilo, teujo, har-tondilo, ..., tranĉ-aparato, insekto-mortigilo, jogurtilo",
  "2.3" => "Termometro, brak-horloĝo, horloĝo, vekhorloĝo, kronometro",
  "3.1" => "Komputil-ekrano pli ol 32 colojn oblikve longa",
  "3.2" => "Komputil-ekrano pli ol 20 k malpli ol 32 colojn oblikve longa",
  "3.3" => "Komputil-ekrano malpli ol 20 colojn oblikve longa",
  "3.4" => "Persona komputilo, ĉefprocesoro",
  "3.5" => "Portabla komputilo",
  "3.6" => "Printilo (krom printilo nur fota, skanilo, faksilo",
  "3.7" => "Poŝkomputilo, kalkulilo, voĉregistrilo, drata aŭ sendrata telefono, respondil-registrilo, pordtelefono, radiotelefono, GPS-birilo, modemo, enkursigilo, sendrata reto, voka enkursigilo, eksterna datum-memorilo (eksterna malmola disko, eksterna disketilo, ..., eksterna KD- aŭ DvD-lumdiska registrilo, malkodilo, transkodilo, USB-memorilo, komputilaj aparatetoj: retkamerao, muso, klavaro, komputila laŭtparolilo, aŭdilo, mikrofono",
  "3.8" => "Poŝtelefono kaj akcesoraĵoj",
  "4.1" => "Televidilo kun ekrano pli ol 32 colojn oblikve longa, kaj aliaj grandaj ekranoj",
  "4.2" => "Televidilo kun ekrano pli ol 20 k malpli ol 32 colojn oblikve longa",
  "4.3" => "Televidilo kun ekrano malpli ol 20 colojn oblikve longa",
  "4.4" => "Altfidela muzikilo, mikro-/mini- (hejm-muzikilo, tut-integrita muzikilo, amplifilo, hejm-kineja amplifilo, amplifa-agordilo",
  "4.5" => "Magnetofono, KD-/DVD-/DivX-/kased-legilo, DVD-lumdisko-registrilo, disko-platino, agordilo, plurpera salona malmola-disko, karaokeo-legilo, komputila projekciilo, muzik-instrumento, resonskatolo, muzika ludmiksilo, muzika ludegaligilo",
  "4.6" => "Teleŝaltilo, televida adapto-skatolo, aŭdilo (sona, televida, altfidela, videoregistrilo, bitkamerao, magnetofono/voĉregistrilo, portebla lumdiskilo, minidiskilo, MP3-ludilo, malmola-disko, solidstata memorilo, mikrofono, radioludilo, radio-kasedo-lumdisko-ludilo, transistoro, portabla radioludilo, radio-vekilo, fotoaparato, foto-printilo, aliaj aŭd-videaj aparatoj",
  "6.1" => "Elektra ĉizilo, varmaer-blovilo, frezmaŝino, borturo, fajlilo, muelmaŝino, drilo, batdrilo, polurilo, elektra rabotilo, elektra segilo, foldilo, rand-stucilo, heĝ-stucilo, motorsegilo, alia portebla elektra ilaro, lutilo, pumpo, pump-fontano, bateri-ŝarĝilo, kudromaŝino",
  "6.2" => "Tondaparato, altprema purigilo, kreskaĵa pistilo, senmova ilaro, kunpremilo",
  "7.1" => "Ludiloj kaj akcesoraĵoj malpli ol 500 gramojn pezaj",
  "7.2" => "Ludiloj kaj akcesoraĵoj pli ol 500 gramojn kaj malpli ol 10 kg-jn pezaj",
  "7.3" => "Ludiloj kaj akcesoraĵoj pli ol 10 kg-jn pezaj",
  "8.1" => "Aparatoj por lokalizi, preventi, gvati, kuraci, maldolorigi malsanojn, vundojn aŭ handikapojn, pli ol 5 kg-jn pezaj",
  "8.2" => "Aparatoj por lokalizi, preventi, gvati, kuraci, maldolorigi malsanojn, vundojn aŭ handikapojn, malpli ol 5 kg-jn pezaj",
  "9.1" => "Senekranaj mezuriloj, kontroliloj kaj gvatiloj",
  "9.2" => "Aliaj ekranaj kontroliloj k gvatiloj ",
  "10.0" => "Aŭtomatoj"
);


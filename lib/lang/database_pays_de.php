<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_pays_de.php 37904 2013-08-27 21:19:26Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_pays["pays"] = array(
  "1" => "Frankreich",
  "2" => "Afghanistan",
  "3" => "Südafrika",
  "4" => "Albanien",
  "5" => "Algerien",
  "6" => "Deutschland",
  "7" => "Saudi-Arabien",
  "8" => "Argentinien",
  "9" => "Australien",
  "10" => "Österreich",
  "11" => "Belgien",
  "12" => "Brasilien",
  "13" => "Bulgarien",
  "14" => "Kanada",
  "15" => "Chile",
  "16" => "China (Volksrepublik)",
  "17" => "Kolumbien",
  "18" => "Südkorea",
  "19" => "Costa Rica",
  "20" => "Kroatien",
  "21" => "Dänemark",
  "22" => "Ägypten",
  "23" => "Vereinigte Arabische Emirate",
  "24" => "Ecuador",
  "25" => "USA",
  "26" => "El Salvador",
  "27" => "Spanien",
  "28" => "Finnland",
  "29" => "Griechenland",
  "30" => "Hong Kong",
  "31" => "Ungarn",
  "32" => "Indien",
  "33" => "Indonesien",
  "34" => "Irland",
  "35" => "Israel",
  "36" => "Italien",
  "37" => "Japan",
  "38" => "Jordanien",
  "39" => "Lebanon",
  "40" => "Malaysia",
  "41" => "Marokko",
  "42" => "Mexiko",
  "43" => "Norwegen",
  "44" => "New Zealand",
  "45" => "Peru",
  "46" => "Pakistan",
  "47" => "Niederlande",
  "48" => "Philippinen",
  "49" => "Polen",
  "50" => "Puerto Rico",
  "51" => "Portugal",
  "52" => "Tschechische Republik",
  "53" => "Rumänien",
  "54" => "Großbritannien",
  "55" => "Russland",
  "56" => "Singapur",
  "57" => "Schweden",
  "58" => "Schweiz",
  "59" => "Taiwan",
  "60" => "Thailand",
  "61" => "Türkei",
  "62" => "Ukraine",
  "63" => "Venezuela",
  "64" => "Serbien",
  "65" => "Samoa",
  "66" => "Andorra",
  "67" => "Angola",
  "68" => "Anguilla",
  "69" => "Antarktica",
  "70" => "Antigua und Barbuda",
  "71" => "Armenien",
  "72" => "Aruba",
  "73" => "Aserbaidschan",
  "74" => "Bahamas",
  "75" => "Bahrain",
  "76" => "Bangladesch",
  "77" => "Weißrussland",
  "78" => "Belize",
  "79" => "Benin",
  "80" => "Bermudas (die)",
  "81" => "Bhutan",
  "82" => "Bolivien",
  "83" => "Bosnien und Herzegowina",
  "84" => "Botswana",
  "85" => "Norwegen - Bouvet (Insel)",
  "86" => "Indischen Ozean Brit. Terr. - Diego Garcia",
  "87" => "Jungferninseln",
  "88" => "Brunei",
  "89" => "Burkina Faso",
  "90" => "Burundi",
  "91" => "Kambodscha",
  "92" => "Kamerun",
  "93" => "Cape Verde",
  "94" => "Cayman (Inseln)",
  "95" => "Zentralafrikanische Republik",
  "96" => "Tschad",
  "97" => "Australien - Weihnachtsinsel",
  "98" => "Australien - Cocos (Keeling) (Inseln)",
  "99" => "Komoren",
  "100" => "Kongo",
  "101" => "Cook (Inseln)",
  "102" => "Kuba",
  "103" => "Zypern",
  "104" => "Djibouti",
  "105" => "Dominica",
  "106" => "Dominikanische Republik",
  "107" => "Osttimor",
  "108" => "Äquatorialguinea",
  "109" => "Erythr",
  "110" => "Estland",
  "111" => "Äthiopien",
  "112" => "Falklandinseln",
  "113" => "Färöer (die)",
  "114" => "Fidschiinseln",
  "115" => "Französisch-Guayana",
  "116" => "Französisch-Polynesien",
  "117" => "Terres Australes et Antarctiques Françaises",
  "118" => "Gabon",
  "119" => "Gambia",
  "120" => "Georgien",
  "121" => "Ghana",
  "122" => "Gibraltar",
  "123" => "Grönland",
  "124" => "Grenada",
  "125" => "Frankreich - Guadeloupe",
  "126" => "Guam",
  "127" => "Guatemala",
  "128" => "Guin",
  "129" => "Guinaea-Bissau",
  "131" => "Haiti",
  "132" => "Australien - Heard- und McDonald-Inseln",
  "133" => "Honduras",
  "134" => "Island",
  "135" => "Iran",
  "136" => "Irak",
  "137" => "Elfenbeinküste",
  "138" => "Jamaika",
  "139" => "Kasachstan",
  "140" => "Kenia",
  "141" => "Kiribati",
  "142" => "Südkorea (Rep.)",
  "143" => "Kuwait",
  "144" => "Kirgisistan",
  "145" => "Laos",
  "146" => "Lettland",
  "147" => "Lesotho",
  "148" => "Liberia",
  "149" => "Libyen",
  "150" => "Liechtenstein",
  "151" => "Litauen",
  "152" => "Luxemburg",
  "153" => "Macao",
  "154" => "Mazedonien",
  "155" => "Madagascar",
  "156" => "Malawi",
  "157" => "Malediven (Inseln)",
  "158" => "Mali",
  "159" => "Malta",
  "160" => "Marshall (Inseln)",
  "161" => "Frankreich - Martinique",
  "162" => "Mauretanien",
  "163" => "Maurice",
  "164" => "Frankreich - Mayotte",
  "165" => "Mikronesien (Föderierte Staaten von)",
  "166" => "Moldawien",
  "167" => "Monaco",
  "168" => "Mongolei",
  "169" => "Montserrat",
  "170" => "Mosambik",
  "171" => "Myanmar",
  "172" => "Namibia",
  "173" => "Nauru",
  "174" => "Nepal",
  "176" => "Frankreich - Neukaledonien",
  "177" => "Nicaragua",
  "178" => "Niger",
  "179" => "Nigeria",
  "180" => "Niue",
  "181" => "Australien - Norfolk (Insel)",
  "182" => "Northern Mariana Islands (Inseln)",
  "183" => "Oman",
  "184" => "Palau",
  "185" => "Panama",
  "186" => "Papua-Neuguinea",
  "187" => "Paraguay",
  "188" => "Pitcairn (Insel)",
  "189" => "Qatar",
  "190" => "Frankreich - Reunion",
  "191" => "Ruanda",
  "192" => "Südgeorgien und die Süd-Sandwichinseln",
  "193" => "Saint Kitts und Nevis",
  "194" => "Saint Lucia",
  "195" => "Saint Vincent und die Grenadinen",
  "196" => "Samoa",
  "197" => "San Marino (Rep.)",
  "198" => "Sao Tome und Principe (Republik)",
  "199" => "Senegal",
  "200" => "Seychellen",
  "201" => "Sierra Leone",
  "202" => "Slowakei",
  "203" => "Slowenien",
  "204" => "Somalia",
  "205" => "Sri Lanka",
  "206" => "St. Helena",
  "207" => "Frankreich - Saint-Pierre und Miquelon",
  "208" => "Sudan",
  "209" => "Suriname",
  "210" => "Norwegen - Svalbard und Jan Mayen (Inseln)",
  "211" => "Swasiland",
  "212" => "Syrien",
  "213" => "Tadschikistan",
  "214" => "Tanzania",
  "215" => "Togo",
  "216" => "Neuseeland - Tokelau",
  "217" => "Tonga",
  "218" => "Trinidad und Tobago",
  "219" => "Tunisia",
  "220" => "Turkmenistan",
  "221" => "Turks and Caicos (Inseln)",
  "222" => "Tuvalu",
  "223" => "USA: Minor Outlying Islands",
  "224" => "Uganda",
  "225" => "Uruguay",
  "226" => "Usbekistan",
  "227" => "Vanuatu",
  "228" => "Vatikan (Staat)",
  "229" => "Vietnam",
  "230" => "American Virgin (Inseln)",
  "231" => "Frankreich - Wallis und Futuna",
  "232" => "Western Sahara",
  "233" => "Jemen",
  "234" => "Kongo (Dem. Rep.).",
  "235" => "Zambia",
  "236" => "Zimbabwe",
  "237" => "Barbados",
  "238" => "Montenegro"
);

?>
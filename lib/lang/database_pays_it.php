<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_pays_it.php 39495 2014-01-14 11:08:09Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_pays["pays"] = array(
  "1" => "Francia",
  "2" => "Afghanistan",
  "3" => "Sud Africa",
  "4" => "Albania",
  "5" => "Algeria",
  "6" => "Germania",
  "7" => "Arabia Saudita",
  "8" => "Argentina",
  "9" => "Australia",
  "10" => "Austria",
  "11" => "Belgio",
  "12" => "Brasile",
  "13" => "Bulgaria",
  "14" => "Canada",
  "15" => "Cile",
  "16" => "La Cina (Rep. Popolare).",
  "17" => "Colombia",
  "18" => "Corea del Sud",
  "19" => "Costa Rica",
  "20" => "Croazia",
  "21" => "Danimarca",
  "22" => "Egitto",
  "23" => "Emirati Arabi Uniti",
  "24" => "Ecuador",
  "25" => "Stati Uniti",
  "26" => "El Salvador",
  "27" => "Spagna",
  "28" => "Finlandia",
  "29" => "Grecia",
  "30" => "Hong Kong",
  "31" => "Ungheria",
  "32" => "India",
  "33" => "Indonesia",
  "34" => "Irlanda",
  "35" => "Israele",
  "36" => "Italia",
  "37" => "Giappone",
  "38" => "Jordan",
  "39" => "Libano",
  "40" => "Italia",
  "41" => "Marocco",
  "42" => "Messico",
  "43" => "Norvegia",
  "44" => "Nuova Zelanda",
  "45" => "Perù",
  "46" => "Pakistan",
  "47" => "Paesi Bassi",
  "48" => "Filippine",
  "49" => "Polonia",
  "50" => "Puerto Rico",
  "51" => "Portogallo",
  "52" => "Ceca (Rep.)",
  "53" => "Romania",
  "54" => "Regno Unito (UK)",
  "55" => "Russia",
  "56" => "Singapore",
  "57" => "Svezia",
  "58" => "Svizzera",
  "59" => "Taiwan",
  "60" => "Thailandia",
  "61" => "Turchia",
  "62" => "Ucraina",
  "63" => "Venezuela",
  "64" => "Serbia",
  "65" => "Samoa",
  "66" => "Andorra",
  "67" => "Angola",
  "68" => "Anguilla",
  "69" => "Antartide",
  "70" => "Antigua e Barbuda",
  "71" => "Armenia",
  "72" => "Aruba",
  "73" => "Azerbaijan",
  "74" => "Bahamas",
  "75" => "Bahrain",
  "76" => "Bangladesh",
  "77" => "Bielorussia",
  "78" => "Belize",
  "79" => "Benin",
  "80" => "Bermuda (The)",
  "81" => "Bhutan",
  "82" => "Bolivia",
  "83" => "Bosnia-Erzegovina",
  "84" => "Botswana",
  "85" => "La Norvegia - Bouvet (Isola)",
  "86" => ".. Terr. Brit Oceano Indiano - Diego Garcia",
  "87" => "Isole Vergini Britanniche ()",
  "88" => "Brunei",
  "89" => "Burkina Faso",
  "90" => "Burundi",
  "91" => "Cambogia",
  "92" => "Camerun",
  "93" => "Capo Verde",
  "94" => "Cayman (Isole)",
  "95" => "Central (Rep.)",
  "96" => "Chad",
  "97" => "Australia - Christmas (Isola)",
  "98" => "Australia - Cocos (Keeling) ()",
  "99" => "Comore",
  "100" => "Congo",
  "101" => "Cook (Isole)",
  "102" => "Cuba",
  "103" => "Cipro",
  "104" => "Gibuti",
  "105" => "Dominica",
  "106" => "Dominicana (Rep.)",
  "107" => "Timor Est",
  "108" => "Guinea Equatoriale",
  "109" => "Erythr",
  "110" => "Estonia",
  "111" => "Etiopia",
  "112" => "Isole Falkland (Malvinas)",
  "113" => "Isole Faroe ()",
  "114" => "Fiji (Repubblica di)",
  "115" => "Francia - Guyana",
  "116" => "Francia - Polinesia",
  "117" => "Francia - Territori del Sud",
  "118" => "Gabon",
  "119" => "Gambia",
  "120" => "Georgia",
  "121" => "Ghana",
  "122" => "Gibilterra",
  "123" => "Groenlandia",
  "124" => "Grenada",
  "125" => "Francia - Guadalupa",
  "126" => "Guam",
  "127" => "Guatemala",
  "128" => "Guin",
  "129" => "Guinea-Bissau",
  "131" => "Haiti",
  "132" => "L'Australia - Heard e McDonald (Isole)",
  "133" => "Honduras",
  "134" => "Islanda",
  "135" => "Iran",
  "136" => "Iraq",
  "137" => "Costa d'Avorio",
  "138" => "Giamaica",
  "139" => "Kazakhstan",
  "140" => "Kenya",
  "141" => "Kiribati",
  "142" => "La Corea (Rep. di) (Sud)",
  "143" => "Kuwait",
  "144" => "Kirghizistan",
  "145" => "Laos",
  "146" => "Lettonia",
  "147" => "Lesotho",
  "148" => "Liberia",
  "149" => "Libia",
  "150" => "Liechtenstein",
  "151" => "Lituania",
  "152" => "Lussemburgo",
  "153" => "Macao",
  "154" => "Macedonia",
  "155" => "Madagascar",
  "156" => "Malawi",
  "157" => "Maldive (Isole)",
  "158" => "Mali",
  "159" => "Malta",
  "160" => "Marshall (Isole)",
  "161" => "Francia - Martinica",
  "162" => "Mauritania",
  "163" => "Maurice",
  "164" => "Francia - Mayotte",
  "165" => "Micronesia (Stati Federati di)",
  "166" => "Moldova",
  "167" => "Monaco",
  "168" => "Mongolia",
  "169" => "Montserrat",
  "170" => "Mozambico",
  "171" => "Myanmar",
  "172" => "Namibia",
  "173" => "Nauru",
  "174" => "Nepal",
  "176" => "La Francia - Nuova Caledonia",
  "177" => "Nicaragua",
  "178" => "Niger",
  "179" => "Nigeria",
  "180" => "Niue",
  "181" => "Australia - Norfolk (Isola)",
  "182" => "Isole Marianne Settentrionali ()",
  "183" => "Oman",
  "184" => "Palau",
  "185" => "Panama",
  "186" => "Papua Nuova Guinea",
  "187" => "Paraguay",
  "188" => "Pitcairn (isola)",
  "189" => "Qatar",
  "190" => "Francia - Reunion",
  "191" => "Rwanda",
  "192" => "Georgia del Sud e isole Sandwich del Sud ()",
  "193" => "Saint Kitts e Nevis",
  "194" => "Santa Lucia",
  "195" => "Saint Vincent e Grenadine",
  "196" => "Samoa",
  "197" => "San Marino (Rep. di)",
  "198" => "Sao Tome e Principe (Repubblica di)",
  "199" => "Senegal",
  "200" => "Seychelles",
  "201" => "Sierra Leone",
  "202" => "Slovacchia",
  "203" => "Slovenia",
  "204" => "Somalia",
  "205" => "Sri Lanka",
  "206" => "Sant'Elena",
  "207" => "La Francia - Saint Pierre e Miquelon",
  "208" => "Sudan",
  "209" => "Suriname",
  "210" => "La Norvegia - Svalbard e Jan Mayen (Isola)",
  "211" => "Swaziland",
  "212" => "Siria",
  "213" => "Tagikistan",
  "214" => "Tanzania",
  "215" => "Togo",
  "216" => "Nuova Zelanda - Tokelau",
  "217" => "Tonga",
  "218" => "Trinidad e Tobago",
  "219" => "Tunisia",
  "220" => "Turkmenistan",
  "221" => "Turks e Caicos (Isole)",
  "222" => "Tuvalu",
  "223" => "USA: Isole Minori",
  "224" => "Uganda",
  "225" => "Uruguay",
  "226" => "Uzbekistan",
  "227" => "Vanuatu",
  "228" => "Città del Vaticano (Stato)",
  "229" => "Vietnam",
  "230" => "American Virgin (Isole)",
  "231" => "Francia - Wallis e Futuna",
  "232" => "Sahara Occidentale",
  "233" => "Yemen",
  "234" => "Congo Zaire (Rep. Dem.).",
  "235" => "Zambia",
  "236" => "Zimbabwe",
  "237" => "Barbados",
  "238" => "Montenegro",
);

?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_pays_it.php 61970 2019-11-20 15:48:40Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_pays["pays"] = array(
  "FRA" => "Francia",
  "AFG" => "Afghanistan",
  "ZAF" => "Sud Africa",
  "ALB" => "Albania",
  "DZA" => "Algeria",
  "DEU" => "Germania",
  "SAU" => "Arabia Saudita",
  "ARG" => "Argentina",
  "AUS" => "Australia",
  "AUT" => "Austria",
  "BEL" => "Belgio",
  "BRA" => "Brasile",
  "BGR" => "Bulgaria",
  "CAN" => "Canada",
  "CHL" => "Cile",
  "CHN" => "La Cina (Rep. Popolare).",
  "COL" => "Colombia",
  "KOR" => "Corea del Sud",
  "CRI" => "Costa Rica",
  "HRV" => "Croazia",
  "DNK" => "Danimarca",
  "EGY" => "Egitto",
  "ARE" => "Emirati Arabi Uniti",
  "ECU" => "Ecuador",
  "USA" => "Stati Uniti",
  "SLV" => "El Salvador",
  "ESP" => "Spagna",
  "FIN" => "Finlandia",
  "GRC" => "Grecia",
  "HKG" => "Hong Kong",
  "HUN" => "Ungheria",
  "IND" => "India",
  "IDN" => "Indonesia",
  "IRL" => "Irlanda",
  "ISR" => "Israele",
  "ITA" => "Italia",
  "JPN" => "Giappone",
  "JOR" => "Jordan",
  "LBN" => "Libano",
  "MYS" => "Italia",
  "MAR" => "Marocco",
  "MEX" => "Messico",
  "NOK" => "Norvegia",
  "NZL" => "Nuova Zelanda",
  "PER" => "Perù",
  "PAK" => "Pakistan",
  "NLD" => "Paesi Bassi",
  "PHL" => "Filippine",
  "POL" => "Polonia",
  "PRI" => "Puerto Rico",
  "PRT" => "Portogallo",
  "CZE" => "Ceca (Rep.)",
  "ROU" => "Romania",
  "GBR" => "Regno Unito (UK)",
  "RUS" => "Russia",
  "SGP" => "Singapore",
  "SWE" => "Svezia",
  "CHE" => "Svizzera",
  "TWN" => "Taiwan",
  "THA" => "Thailandia",
  "TUR" => "Turchia",
  "UKR" => "Ucraina",
  "VEN" => "Venezuela",
  "SRB" => "Serbia",
  "AND" => "Andorra",
  "AGO" => "Angola",
  "AIA" => "Anguilla",
  "ATA" => "Antartide",
  "ATG" => "Antigua e Barbuda",
  "ARM" => "Armenia",
  "ABW" => "Aruba",
  "AZE" => "Azerbaijan",
  "BHS" => "Bahamas",
  "BHR" => "Bahrain",
  "BGD" => "Bangladesh",
  "BLR" => "Bielorussia",
  "BLZ" => "Belize",
  "BEN" => "Benin",
  "BMU" => "Bermuda (The)",
  "BTN" => "Bhutan",
  "BOL" => "Bolivia",
  "BIH" => "Bosnia-Erzegovina",
  "BWA" => "Botswana",
  "BVT" => "La Norvegia - Bouvet (Isola)",
  "IOT" => ".. Terr. Brit Oceano Indiano - Diego Garcia",
  "VGB" => "Isole Vergini Britanniche ()",
  "BRN" => "Brunei",
  "BFA" => "Burkina Faso",
  "BDI" => "Burundi",
  "KHM" => "Cambogia",
  "CMR" => "Camerun",
  "CPV" => "Capo Verde",
  "CYM" => "Cayman (Isole)",
  "CAF" => "Central (Rep.)",
  "TCD" => "Chad",
  "CXR" => "Australia - Christmas (Isola)",
  "CCK" => "Australia - Cocos (Keeling) ()",
  "COM" => "Comore",
  "COG" => "Congo",
  "COK" => "Cook (Isole)",
  "CUB" => "Cuba",
  "CYP" => "Cipro",
  "DJI" => "Gibuti",
  "DMA" => "Dominica",
  "DOM" => "Dominicana (Rep.)",
  "TLS" => "Timor Est",
  "GNQ" => "Guinea Equatoriale",
  "ERI" => "Erythr",
  "EST" => "Estonia",
  "ETH" => "Etiopia",
  "FLK" => "Isole Falkland (Malvinas)",
  "FRO" => "Isole Faroe ()",
  "FJI" => "Fiji (Repubblica di)",
  "GUF" => "Francia - Guyana",
  "PYF" => "Francia - Polinesia",
  "ATF" => "Francia - Territori del Sud",
  "GAB" => "Gabon",
  "GMB" => "Gambia",
  "GEO" => "Georgia",
  "GHA" => "Ghana",
  "GIB" => "Gibilterra",
  "GRL" => "Groenlandia",
  "GRD" => "Grenada",
  "GLP" => "Francia - Guadalupa",
  "GUM" => "Guam",
  "GTM" => "Guatemala",
  "GIN" => "Guin",
  "GNB" => "Guinea-Bissau",
  "HTI" => "Haiti",
  "HMD" => "L'Australia - Heard e McDonald (Isole)",
  "HND" => "Honduras",
  "ISL" => "Islanda",
  "IRN" => "Iran",
  "IRQ" => "Iraq",
  "CIV" => "Costa d'Avorio",
  "JAM" => "Giamaica",
  "KAZ" => "Kazakhstan",
  "KEN" => "Kenya",
  "KIR" => "Kiribati",
  "KWT" => "Kuwait",
  "KGZ" => "Kirghizistan",
  "LAO" => "Laos",
  "LVA" => "Lettonia",
  "LSO" => "Lesotho",
  "LBR" => "Liberia",
  "LBY" => "Libia",
  "LIE" => "Liechtenstein",
  "LTU" => "Lituania",
  "LUX" => "Lussemburgo",
  "MAC" => "Macao",
  "MKD" => "Macedonia",
  "MDG" => "Madagascar",
  "MWI" => "Malawi",
  "MDV" => "Maldive (Isole)",
  "MLI" => "Mali",
  "MLT" => "Malta",
  "MHL" => "Marshall (Isole)",
  "MTQ" => "Francia - Martinica",
  "MRT" => "Mauritania",
  "MUS" => "Maurice",
  "MYT" => "Francia - Mayotte",
  "FSM" => "Micronesia (Stati Federati di)",
  "MDA" => "Moldova",
  "MCO" => "Monaco",
  "MNG" => "Mongolia",
  "MSR" => "Montserrat",
  "MOZ" => "Mozambico",
  "MMR" => "Myanmar",
  "NAM" => "Namibia",
  "NRU" => "Nauru",
  "NPL" => "Nepal",
  "NCL" => "La Francia - Nuova Caledonia",
  "NIC" => "Nicaragua",
  "NER" => "Niger",
  "NGA" => "Nigeria",
  "NIU" => "Niue",
  "NFK" => "Australia - Norfolk (Isola)",
  "MNP" => "Isole Marianne Settentrionali ()",
  "OMN" => "Oman",
  "PLW" => "Palau",
  "PAN" => "Panama",
  "PNG" => "Papua Nuova Guinea",
  "PRY" => "Paraguay",
  "PCN" => "Pitcairn (isola)",
  "QAT" => "Qatar",
  "REU" => "Francia - Reunion",
  "RWA" => "Rwanda",
  "SGS" => "Georgia del Sud e isole Sandwich del Sud ()",
  "KNA" => "Saint Kitts e Nevis",
  "LCA" => "Santa Lucia",
  "VCT" => "Saint Vincent e Grenadine",
  "WSM" => "Samoa",
  "SMR" => "San Marino (Rep. di)",
  "STP" => "Sao Tome e Principe (Repubblica di)",
  "SEN" => "Senegal",
  "SYC" => "Seychelles",
  "SLE" => "Sierra Leone",
  "SVK" => "Slovacchia",
  "SVN" => "Slovenia",
  "SOM" => "Somalia",
  "LKA" => "Sri Lanka",
  "SHN" => "Sant'Elena",
  "SPM" => "La Francia - Saint Pierre e Miquelon",
  "SDN" => "Sudan",
  "SUR" => "Suriname",
  "SJM" => "La Norvegia - Svalbard e Jan Mayen (Isola)",
  "SWZ" => "Swaziland",
  "SYR" => "Siria",
  "TJK" => "Tagikistan",
  "TZA" => "Tanzania",
  "TGO" => "Togo",
  "TKL" => "Nuova Zelanda - Tokelau",
  "TON" => "Tonga",
  "TTO" => "Trinidad e Tobago",
  "TUN" => "Tunisia",
  "TKM" => "Turkmenistan",
  "TCA" => "Turks e Caicos (Isole)",
  "TUV" => "Tuvalu",
  "UMI" => "USA: Isole Minori",
  "UGA" => "Uganda",
  "URY" => "Uruguay",
  "UZB" => "Uzbekistan",
  "VUT" => "Vanuatu",
  "VAT" => "Città del Vaticano (Stato)",
  "VNM" => "Vietnam",
  "VIR" => "American Virgin (Isole)",
  "WLF" => "Francia - Wallis e Futuna",
  "ESH" => "Sahara Occidentale",
  "YEM" => "Yemen",
  "COD" => "Congo Zaire (Rep. Dem.).",
  "ZMB" => "Zambia",
  "ZWE" => "Zimbabwe",
  "BRB" => "Barbados",
  "MNE" => "Montenegro"
);


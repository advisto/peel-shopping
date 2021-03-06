<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_pays_es.php 66961 2021-05-24 13:26:45Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_pays["pays"] = array(
  "FRA" => "Francia",
  "AFG" => "Afganistán",
  "ZAF" => "Sudáfrica, República de",
  "ALB" => "Albania",
  "DZA" => "Argelia",
  "DEU" => "Alemania",
  "SAU" => "Arabia Saudí",
  "ARG" => "Argentina",
  "AUS" => "Australia",
  "AUT" => "Austria",
  "BEL" => "Bélgica",
  "BRA" => "Brasil",
  "BGR" => "Bulgaria",
  "CAN" => "Canadá",
  "CHL" => "Chile",
  "CHN" => "China",
  "COL" => "Colombia",
  "KOR" => "Korea del Sur",
  "CRI" => "Costa Rica",
  "HRV" => "Croacia",
  "DNK" => "Dinamarca",
  "EGY" => "Egipto",
  "ARE" => "Emiratos Árabes Unidos",
  "ECU" => "Ecuador",
  "USA" => "Estados Unidos (EE.UU",
  "SLV" => "El Salvador",
  "ESP" => "España",
  "FIN" => "Finlandia",
  "GRC" => "Grecia",
  "HKG" => "Hong Kong",
  "HUN" => "Hungría",
  "IND" => "India",
  "IDN" => "Indonesia",
  "IRL" => "Irlanda",
  "ISR" => "Israel",
  "ITA" => "Italia",
  "JPN" => "Japón",
  "JOR" => "Jordania",
  "LBN" => "Líbano",
  "MYS" => "Malasia",
  "MAR" => "Marruecos",
  "MEX" => "México",
  "NOK" => "Noruega - Bouvet Isla",
  "NZL" => "Nueva Zelanda",
  "PER" => "Perú",
  "PAK" => "Pakistán",
  "NLD" => "Países Bajos",
  "PHL" => "Filipinas",
  "POL" => "Polonia",
  "PRI" => "Puerto Rico",
  "PRT" => "Portugal - Madeira",
  "CZE" => "Checa (República",
  "ROU" => "Rumanía",
  "GBR" => "Reino Unido",
  "RUS" => "Rusia",
  "SGP" => "Singapur",
  "SWE" => "Suecia",
  "CHE" => "Suiza",
  "TWN" => "Taiwán",
  "THA" => "Thailandia",
  "TUR" => "Turquía",
  "UKR" => "Ucrania",
  "VEN" => "Venezuela",
  "SRB" => "Serbia",
  "AND" => "Andorra",
  "AGO" => "Angola",
  "AIA" => "Anguilla",
  "ATA" => "Antártida",
  "ATG" => "Antigua y Barbuda",
  "ARM" => "Armenia",
  "ABW" => "Aruba",
  "AZE" => "Azerbaiyán",
  "BHS" => "Bahamas",
  "BHR" => "Bahrain",
  "BGD" => "Bangladesh",
  "BLR" => "Bielorusia",
  "BLZ" => "Belice",
  "BEN" => "Benín",
  "BMU" => "Bermuda",
  "BTN" => "Bután",
  "BOL" => "Bolívia",
  "BIH" => "Bosnia-Herzegovina",
  "BWA" => "Botswana",
  "BVT" => "Noruega - Bouvet Isla",
  "IOT" => "Terr. Británico del Oceano Índico - Diego Garcia",
  "VGB" => "Vírgenes Británicas (Islas",
  "BRN" => "Brunei",
  "BFA" => "Burkina Faso",
  "BDI" => "Burundi",
  "KHM" => "Camboya",
  "CMR" => "Camerún",
  "CPV" => "Cabo Verde",
  "CYM" => "Caimán Islas",
  "CAF" => "Centroafricana (República",
  "TCD" => "Chad",
  "CXR" => "Australia - Christmas Islas",
  "CCK" => "Australia - Cocos (Keeling Islands",
  "COM" => "Comoras",
  "COG" => "Congo",
  "COK" => "Cook Islas",
  "CUB" => "Cuba",
  "CYP" => "Chipre",
  "DJI" => "Yibouti",
  "DMA" => "Dominica",
  "DOM" => "Dominicana República",
  "TLS" => "Timor Oriental",
  "GNQ" => "Guinea Equatorial",
  "ERI" => "Eritrea",
  "EST" => "Estonia",
  "ETH" => "Etiopía",
  "FLK" => "Malvinas (Islas",
  "FRO" => "Feroe (Islas",
  "FJI" => "Fiji",
  "GUF" => "Francia - Guayana",
  "PYF" => "Francia - Polinesia",
  "ATF" => "Francia - Southern Territories",
  "GAB" => "Gabón",
  "GMB" => "Gambia",
  "GEO" => "Georgia",
  "GHA" => "Ghana",
  "GIB" => "Gibraltar",
  "GRL" => "Groenlandia",
  "GRD" => "Grenada",
  "GLP" => "Francia - Guadalupe",
  "GUM" => "Guam",
  "GTM" => "Guatemala",
  "GIN" => "Guinea",
  "GNB" => "Guinea Bissau",
  "HTI" => "Haití",
  "HMD" => "Australia - Heard y McDonald (Islas",
  "HND" => "Honduras",
  "ISL" => "Islandia",
  "IRN" => "Irán",
  "IRQ" => "Irak",
  "CIV" => "Costa de Marfil",
  "JAM" => "Jamaica",
  "KAZ" => "Kazajstán",
  "KEN" => "Kenya",
  "KIR" => "Kiribati",
  "KWT" => "Kuwait",
  "KGZ" => "Kirguistán",
  "LAO" => "Laos (People's Democratic Republic of",
  "LVA" => "Letonia",
  "LSO" => "Lesotho",
  "LBR" => "Liberia",
  "LBY" => "Libia",
  "LIE" => "Liechtenstein",
  "LTU" => "Lituania",
  "LUX" => "Luxemburgo",
  "MAC" => "Macao",
  "MKD" => "Macedonia",
  "MDG" => "Madagascar",
  "MWI" => "Malawi",
  "MDV" => "Maldivas",
  "MLI" => "Mali",
  "MLT" => "Malta",
  "MHL" => "Marshall (Islas",
  "MTQ" => "Francia - Martinica",
  "MRT" => "Mauritania",
  "MUS" => "Mauricio",
  "MYT" => "Francia - Mayotte",
  "FSM" => "Micronesia",
  "MDA" => "Moldavia",
  "MCO" => "Mónaco",
  "MNG" => "Mongolia",
  "MSR" => "Montserrat",
  "MOZ" => "Mozambique",
  "MMR" => "Myanmar",
  "NAM" => "Namibia",
  "NRU" => "Nauru",
  "NPL" => "Nepal",
  "NCL" => "Nueva Caledonia",
  "NIC" => "Nicaragua",
  "NER" => "Niger",
  "NGA" => "Nigeria",
  "NIU" => "Niue",
  "NFK" => "Australia - Islas Norfolk",
  "MNP" => "Marianas del Norte (Islas",
  "OMN" => "Omán",
  "PLW" => "Palau",
  "PAN" => "Panamá",
  "PNG" => "Papúa Nueva Guinea",
  "PRY" => "Paraguay",
  "PCN" => "Islas Pitcairn",
  "QAT" => "Qatar",
  "REU" => "Francia - Reunión",
  "RWA" => "Ruanda",
  "SGS" => "Georgia del Sr y Sandwich del Sur (Islas",
  "KNA" => "San Cristóbal y Nieves",
  "LCA" => "Santa Lucía",
  "VCT" => "San Vicente y las Granadinas",
  "WSM" => "Samoa",
  "SMR" => "San Marino",
  "STP" => "Sao Tomé y Príncipe",
  "SEN" => "Senegal",
  "SYC" => "Seychelles",
  "SLE" => "Sierra Leone",
  "SVK" => "Eslovaquia",
  "SVN" => "Eslovenia",
  "SOM" => "Somalia",
  "LKA" => "Sri Lanka",
  "SHN" => "Santa Helena",
  "SPM" => "Francia - San Pedro y Miquelón",
  "SDN" => "Sudán",
  "SUR" => "Surinam",
  "SJM" => "Noruega - Svalbard and Jan Mayen",
  "SWZ" => "Swazilandia",
  "SYR" => "Syria",
  "TJK" => "Tadjikistán",
  "TZA" => "Tanzania",
  "TGO" => "Togo",
  "TKL" => "Nueva Zelanda - Tokelau",
  "TON" => "Tonga",
  "TTO" => "Trinidad y Tobago",
  "TUN" => "Túnez",
  "TKM" => "Turkmenistán",
  "TCA" => "Turcos y Caicos",
  "TUV" => "Tuvalu",
  "UMI" => "Estados Unidos (EE.UU: Minor Outlying Islands",
  "UGA" => "Uganda",
  "URY" => "Uruguay",
  "UZB" => "Uzbekistán",
  "VUT" => "Vanuatu",
  "VAT" => "Vatican",
  "VNM" => "Vietnam",
  "VIR" => "United States Virgin Islands",
  "WLF" => "Francia - Wallis y Futuna",
  "ESH" => "Sáhara Occidental",
  "YEM" => "Yemen",
  "COD" => "Zaire",
  "ZMB" => "Zambia",
  "ZWE" => "Zimbabwe",
  "BRB" => "Barbados",
  "MNE" => "Montenegro"
);


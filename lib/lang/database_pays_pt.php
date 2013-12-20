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
// $Id: database_pays_pt.php 39392 2013-12-20 11:08:42Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_pays["pays"] = array("1" => "França",
  "2" => "Afeganistão",
  "3" => "África do Sul",
  "4" => "Albânia",
  "5" => "Argélia",
  "6" => "Alemanha",
  "7" => "Arábia Saudita",
  "8" => "Argentina",
  "9" => "Austrália",
  "10" => "Áustria",
  "11" => "Bélgica",
  "12" => "Brasil",
  "13" => "Bulgária",
  "14" => "Canadá",
  "15" => "Chile",
  "16" => "China (República Popular).",
  "17" => "Colômbia",
  "18" => "Coreia do Sul",
  "19" => "Costa Rica",
  "20" => "Croácia",
  "21" => "Dinamarca",
  "22" => "Egito",
  "23" => "Emirados Árabes Unidos",
  "24" => "Equador",
  "25" => "Estados Unidos",
  "26" => "El Salvador",
  "27" => "Espanha",
  "28" => "Finlândia",
  "29" => "Grécia",
  "30" => "Hong Kong",
  "31" => "Hungria",
  "32" => "Índia",
  "33" => "Indonésia",
  "34" => "Irlanda",
  "35" => "Israel",
  "36" => "Itália",
  "37" => "Japão",
  "38" => "Jordan",
  "39" => "Líbano",
  "40" => "Malásia",
  "41" => "Marrocos",
  "42" => "México",
  "43" => "Noruega",
  "44" => "Nova Zelândia",
  "45" => "Peru",
  "46" => "Paquistão",
  "47" => "Países Baixos",
  "48" => "Filipinas",
  "49" => "Polônia",
  "50" => "Puerto Rico",
  "51" => "Portugal",
  "52" => "Tcheca (Rep.)",
  "53" => "Roménia",
  "54" => "Reino Unido (UK)",
  "55" => "Rússia",
  "56" => "Singapura",
  "57" => "Suécia",
  "58" => "Suíça",
  "59" => "Taiwan",
  "60" => "Tailândia",
  "61" => "Turquia",
  "62" => "Ucrânia",
  "63" => "Venezuela",
  "64" => "Sérvia",
  "65" => "Samoa",
  "66" => "Andorra",
  "67" => "Angola",
  "68" => "Anguilla",
  "69" => "Antarctica",
  "70" => "Antígua e Barbuda",
  "71" => "Armênia",
  "72" => "Aruba",
  "73" => "Azerbaijão",
  "74" => "Bahamas",
  "75" => "Bahrain",
  "76" => "Bangladesh",
  "77" => "Belarus",
  "78" => "Belize",
  "79" => "Benin",
  "80" => "Bermuda (A)",
  "81" => "Butão",
  "82" => "Bolívia",
  "83" => "Bósnia e Herzegovina",
  "84" => "Botswana",
  "85" => "Noruega - Bouvet (Ilha)",
  "86" => ".. Terr Brit Oceano Índico - Diego Garcia",
  "87" => "Ilhas Virgens Britânicas ()",
  "88" => "Brunei",
  "89" => "Burkina Faso",
  "90" => "Burundi",
  "91" => "Cambodia",
  "92" => "Camarões",
  "93" => "Cabo Verde",
  "94" => "Cayman (Ilhas)",
  "95" => "Central (Rep.)",
  "96" => "Chad",
  "97" => "Austrália - Natal (Ilha)",
  "98" => "Austrália - Ilhas Cocos (Keeling) ()",
  "99" => "Comores",
  "100" => "Congo",
  "101" => "Cook (Ilhas)",
  "102" => "Cuba",
  "103" => "Chipre",
  "104" => "Djibouti",
  "105" => "Dominica",
  "106" => "Dominicana (Rep.)",
  "107" => "Timor Leste",
  "108" => "Guiné Equatorial",
  "109" => "Erythr",
  "110" => "Estónia",
  "111" => "Etiópia",
  "112" => "Ilhas Falkland (Malvinas)",
  "113" => "Ilhas Faroe ()",
  "114" => "Fiji (República da)",
  "115" => "França - Guiana",
  "116" => "França - Polinésia",
  "117" => "França - Territórios do Sul",
  "118" => "Gabão",
  "119" => "Gâmbia",
  "120" => "Georgia",
  "121" => "Gana",
  "122" => "Gibraltar",
  "123" => "Gronelândia",
  "124" => "Granada",
  "125" => "A França - Guadalupe",
  "126" => "Guam",
  "127" => "Guatemala",
  "128" => "Guin",
  "129" => "Guiné-Bissau",
  "131" => "Haiti",
  "132" => "Austrália - Heard e McDonald (Ilhas)",
  "133" => "Honduras",
  "134" => "Islândia",
  "135" => "Irã",
  "136" => "Iraque",
  "137" => "Côte d'Ivoire",
  "138" => "Jamaica",
  "139" => "Cazaquistão",
  "140" => "Quênia",
  "141" => "Kiribati",
  "142" => "Coreia (Rep. da) (do Sul)",
  "143" => "Kuwait",
  "144" => "Quirguistão",
  "145" => "Laos",
  "146" => "Letónia",
  "147" => "Lesoto",
  "148" => "Libéria",
  "149" => "Líbia",
  "150" => "Liechtenstein",
  "151" => "Lituânia",
  "152" => "Luxemburgo",
  "153" => "Macau",
  "154" => "Macedónia",
  "155" => "Madagascar",
  "156" => "Malawi",
  "157" => "Maldivas (Ilhas)",
  "158" => "Mali",
  "159" => "Malta",
  "160" => "Marshall (Ilhas)",
  "161" => "France - Martinica",
  "162" => "Mauritânia",
  "163" => "Maurice",
  "164" => "França - Mayotte",
  "165" => "Micronésia (Estados Federados da)",
  "166" => "Moldávia",
  "167" => "Monaco",
  "168" => "Mongólia",
  "169" => "Montserrat",
  "170" => "Moçambique",
  "171" => "Mianmar",
  "172" => "Namíbia",
  "173" => "Nauru",
  "174" => "Nepal",
  "176" => "França - Nova Caledônia",
  "177" => "Nicarágua",
  "178" => "Níger",
  "179" => "Nigéria",
  "180" => "Niue",
  "181" => "Austrália - Norfolk (Ilha)",
  "182" => "Ilhas Marianas do Norte ()",
  "183" => "Omã",
  "184" => "Palau",
  "185" => "Panama",
  "186" => "Papua Nova Guiné",
  "187" => "Paraguai",
  "188" => "Pitcairn (Ilha)",
  "189" => "Qatar",
  "190" => "França - Reunion",
  "191" => "Ruanda",
  "192" => "Geórgia do Sul e Sandwich do Sul ()",
  "193" => "São Cristóvão e Nevis",
  "194" => "Santa Lúcia",
  "195" => "São Vicente e Granadinas",
  "196" => "Samoa",
  "197" => "San Marino (Rep.)",
  "198" => "São Tomé e Príncipe (República da)",
  "199" => "Senegal",
  "200" => "Seychelles",
  "201" => "Serra Leoa",
  "202" => "Eslováquia",
  "203" => "Eslovénia",
  "204" => "Somália",
  "205" => "Sri Lanka",
  "206" => "Santa Helena",
  "207" => "França - Saint Pierre e Miquelon",
  "208" => "Sudão",
  "209" => "Suriname",
  "210" => "Noruega - Svalbard e Jan Mayen (Ilha)",
  "211" => "Suazilândia",
  "212" => "Síria",
  "213" => "Tajiquistão",
  "214" => "Tanzânia",
  "215" => "Togo",
  "216" => "Nova Zelândia - Tokelau",
  "217" => "Tonga",
  "218" => "Trinidad e Tobago",
  "219" => "Tunísia",
  "220" => "Turcomenistão",
  "221" => "Turks e Caicos (Ilhas)",
  "222" => "Tuvalu",
  "223" => "EUA: Ilhas Menores Distantes",
  "224" => "Uganda",
  "225" => "Uruguai",
  "226" => "Usbequistão",
  "227" => "Vanuatu",
  "228" => "Cidade do Vaticano (Estado)",
  "229" => "Vietnam",
  "230" => "Virgens Americanas (Ilhas)",
  "231" => "França - Wallis e Futuna",
  "232" => "Sara Ocidental",
  "233" => "Yemen",
  "234" => "Congo Zaire (Rep. Dem.).",
  "235" => "Zâmbia",
  "236" => "Zimbabwe",
  "237" => "Barbados",
  "238" => "Montenegro",
);

?>
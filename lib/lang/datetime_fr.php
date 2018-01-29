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
// $Id: datetime_fr.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

$GLOBALS['date_format_nice'] = '%A %d %B %Y';
$GLOBALS['date_format_long'] = '%A %d %B %Y';
$GLOBALS['date_format_standard'] = '%d %B %Y';
$GLOBALS['date_format_short'] = '%d/%m/%Y';
$GLOBALS['date_format_veryshort'] = '%d/%m';
$GLOBALS['date_basic_format_short'] = 'd/m/Y';
$GLOBALS['date_mysql_format_full'] = '%d-%m-%Y %H:%i:%s';
$GLOBALS['time_format_long'] = '%Hh%M:%S';
$GLOBALS['time_format_short'] = '%Hh%M';
$GLOBALS['time_basic_format_long'] = 'H:i:s';
$GLOBALS['time_basic_format_short'] = 'H:i';
$GLOBALS['strTimeDateSeparator'] = 'à'; // can be void
$GLOBALS['months_names'] = array(
	"1" => 'janvier', 
	"2" => 'février', 
	"3" => 'mars', 
	"4" => 'avril', 
	"5" => 'mai', 
	"6" => 'juin', 
	"7" => 'juillet', 
	"8" => 'août', 
	"9" => 'septembre', 
	"10" => 'octobre', 
	"11" => 'novembre', 
	"12" => 'décembre');
// URL hereunder : please without any accents
$GLOBALS['months_url_names'] = array(
	"1" => 'janvier', 
	"2" => 'fevrier', 
	"3" => 'mars', 
	"4" => 'avril', 
	"5" => 'mai', 
	"6" => 'juin', 
	"7" => 'juillet', 
	"8" => 'aout', 
	"9" => 'septembre', 
	"10" => 'octobre', 
	"11" => 'novembre', 
	"12" => 'decembre');
$GLOBALS['strToday'] = 'Aujourd\'hui';
$GLOBALS['strStarting'] = 'de';
$GLOBALS['strTill'] = 'à';
$GLOBALS['strTomorrow'] ='Demain';
$GLOBALS['strYesterday'] ='Hier';
$GLOBALS['strTwoDaysAgo'] ='Avant Hier';
$GLOBALS['strOnThe'] ='Le';
$GLOBALS['strStartingOn']='du';
$GLOBALS['strTillDay']='au';
$GLOBALS['day_of_week'] = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
$GLOBALS['strShortDays']='j';
$GLOBALS['strShortHours']='h';
$GLOBALS['strShortMinutes']='min';
$GLOBALS['strShortSecs']='s';
$GLOBALS['strYears'] = 'ans';
$GLOBALS['strMonths'] = 'mois';
$GLOBALS['strWeeks'] = 'semaine(s)';
$GLOBALS['strHours'] = 'heure(s)';
$GLOBALS['strDays'] = 'jour(s)';
$GLOBALS['strYears'] = 'an(s)';

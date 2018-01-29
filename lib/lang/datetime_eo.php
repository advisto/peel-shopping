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
// $Id: datetime_eo.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

$GLOBALS['date_format_nice'] = '%A, %B %d, %Y';
$GLOBALS['date_format_long'] = '%A, %B %d, %Y';
$GLOBALS['date_format_standard'] = '%B %d, %Y';
$GLOBALS['date_format_short'] = '%m-%d-%Y';
$GLOBALS['date_format_veryshort'] = '%m-%d';
$GLOBALS['date_basic_format_short'] = 'm-d-Y';
$GLOBALS['date_mysql_format_full'] = '%m-%d-%Y %H:%i:%s';
$GLOBALS['time_format_long'] = '%Hh%M:%S';
$GLOBALS['time_format_short'] = '%Hh%M';
$GLOBALS['time_basic_format_long'] = 'H:i:s';
$GLOBALS['time_basic_format_short'] = 'H:i';
$GLOBALS['strTimeDateSeparator'] = 'at'; // can be void
$GLOBALS['months_names'] = array ('', 'januaro', 'februaro', 'marto', 'aprilo', 'majo', 'junio​​', 'Julio', 'aŭgusto', 'septembro', 'oktobro', 'novembro', 'decembro');
$GLOBALS['months_url_names'] = array ('', 'januaro', 'february', 'marŝas', 'april', 'may', 'junio​​', 'july', 'augusta', 'september', 'oktobro', 'novembro', 'decembro');
$GLOBALS['strToday'] = 'Today';
$GLOBALS['strStarting'] = 'de';
$GLOBALS['strTill'] = 'ĝis';
$GLOBALS['strTomorrow'] = 'Morgaŭ';
$GLOBALS['strYesterday'] = 'Hieraŭ';
$GLOBALS['strTwoDaysAgo'] = 'Du tagoj';
$GLOBALS['strOnThe'] = 'Por';
$GLOBALS['strStartingOn'] = 'startanta';
$GLOBALS['strTillDay'] = 'ĝis';
$GLOBALS['day_of_week'] = array ('dimanĉo', 'lundo', 'mardon', 'merkredo', 'ĵaŭdo', 'vendredo', 'sabato');
$GLOBALS['strShortDays'] = 'd';
$GLOBALS['strShortHours'] = 'h';
$GLOBALS['strShortMinutes'] = 'min';
$GLOBALS['strShortSecs'] = 's';
$GLOBALS['strMonths'] = 'monato(j)';
$GLOBALS['strWeeks'] = 'semajno(j)';
$GLOBALS['strHours'] = 'horo(j)';
$GLOBALS['strDays'] = 'tago(j)';
$GLOBALS['strYears'] = 'jaro(j)';

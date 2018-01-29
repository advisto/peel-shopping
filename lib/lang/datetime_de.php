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
// $Id: datetime_de.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

$GLOBALS['date_format_nice'] = '%A %d %B %Y';
$GLOBALS['date_format_long'] = '%A %d %B %Y';
$GLOBALS['date_format_standard'] = '%d %B %Y';
$GLOBALS['date_format_short'] = '%d-%m-%Y';
$GLOBALS['date_basic_format_short'] = 'd/m/Y';
$GLOBALS['date_mysql_format_full'] = '%d/%m/%Y %H:%i:%s';
$GLOBALS['time_format_long'] = '%Hh%M:%S';
$GLOBALS['time_format_short'] = '%Hh%M';
$GLOBALS['time_basic_format_long'] = 'H:i:s';
$GLOBALS['time_basic_format_short'] = 'H:i';
$GLOBALS['strTimeDateSeparator'] = 'am'; // can be void
$GLOBALS['months_names'] = array('','Januar', 'Februar', 'März', 'April', 'May', 'Juny', 'July', 'August', 'September', 'Oktober', 'November', 'December');
$globals['months_url_names'] = array('', 'januar', 'februar', 'marz', 'april', 'may', 'juny', 'july', 'august', 'september', 'oktober', 'november', 'december');
$GLOBALS['strToday'] = 'Heute';
$GLOBALS['strStarting'] = 'von';
$GLOBALS['strTill'] = 'nach';
$GLOBALS['strTomorrow'] ='Nachmittag';
$GLOBALS['strYesterday'] ='Gestern';
$GLOBALS['strTwoDaysAgo'] ='Bevor Gestern';
$GLOBALS['strOnThe'] ='Der';
$GLOBALS['strStartingOn']='von';
$GLOBALS['strTillDay']='nach';
$GLOBALS['day_of_week'] = array('Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag');
$GLOBALS['strShortDays']='j';
$GLOBALS['strShortHours']='h';
$GLOBALS['strShortMinutes']='min';
$GLOBALS['strShortSecs']='s';
$GLOBALS['strMonths'] = 'Monat';
$GLOBALS['strWeeks'] = 'Woche(n)';
$GLOBALS['strHours'] = 'Stunde(n)';
$GLOBALS['strDays'] = 'Tag(e)';
$GLOBALS['strYears'] = 'Jahr(e)';

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
// $Id: datetime_en.php 55332 2017-12-01 10:44:06Z sdelaporte $

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
$GLOBALS['months_names'] = array('','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$GLOBALS['months_url_names'] = array('', 'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
$GLOBALS['strToday'] = 'Today';
$GLOBALS['strStarting'] = 'from';
$GLOBALS['strTill'] = 'until';
$GLOBALS['strTomorrow'] ='Tomorrow';
$GLOBALS['strYesterday'] ='Yesterday';
$GLOBALS['strTwoDaysAgo'] ='Two Days Ago';
$GLOBALS['strOnThe'] ='On the';
$GLOBALS['strStartingOn']='starting';
$GLOBALS['strTillDay']='till';
$GLOBALS['day_of_week'] = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
$GLOBALS['strShortDays']='d';
$GLOBALS['strShortHours']='h';
$GLOBALS['strShortMinutes']='min';
$GLOBALS['strShortSecs']='s';
$GLOBALS['strMonths'] = 'month(s)';
$GLOBALS['strWeeks'] = 'week(s)';
$GLOBALS['strHours'] = 'hour(s)';
$GLOBALS['strDays'] = 'day(s)';
$GLOBALS['strYears'] = 'year(s)';

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
// $Id: datetime_es.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

$GLOBALS['date_format_nice'] = '%A %d %B %Y';
$GLOBALS['date_format_long'] = '%A %d %B %Y';
$GLOBALS['date_format_standard'] = '%d %B %Y';
$GLOBALS['date_format_short'] = '%d-%m-%Y';
$GLOBALS['date_format_veryshort'] = '%d-%m';
$GLOBALS['date_basic_format_short'] = 'd/m/Y';
$GLOBALS['date_mysql_format_full'] = '%d/%m/%Y %H:%i:%s';
$GLOBALS['time_format_long'] = '%Hh%M:%S';
$GLOBALS['time_format_short'] = '%Hh%M';
$GLOBALS['time_basic_format_long'] = 'H:i:s';
$GLOBALS['time_basic_format_short'] = 'H:i';
$GLOBALS['strTimeDateSeparator'] = 'a'; // can be void
$GLOBALS['months_names'] = array('', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre','noviembre ','diciembre');
$GLOBALS['months_url_names'] = array('', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre','noviembre ','diciembre');
$GLOBALS['strToday'] = 'Hoy';
$GLOBALS['strStarting'] = 'de';
$GLOBALS['strTill'] = 'à';
$GLOBALS['strTomorrow'] ='Mañana';
$GLOBALS['strYesterday'] ='Ayer';
$GLOBALS['strTwoDaysAgo'] ='Antes de ayer';
$GLOBALS['strOnThe'] ='El';
$GLOBALS['strStartingOn']='de';
$GLOBALS['strTillDay']='a';
$GLOBALS['day_of_week'] = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
$GLOBALS['strShortDays']='d';
$GLOBALS['strShortHours']='h';
$GLOBALS['strShortMinutes']='min';
$GLOBALS['strShortSecs']='s';
$GLOBALS['strMonths'] = 'mese(s)';
$GLOBALS['strWeeks'] = 'semana(s)';
$GLOBALS['strHours'] = 'hora(s)';
$GLOBALS['strDays'] = 'dia(s)';
$GLOBALS['strYears'] = 'año(s)';

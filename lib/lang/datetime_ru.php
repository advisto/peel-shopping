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
// $Id: datetime_ru.php 39392 2013-12-20 11:08:42Z gboussin $

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
$GLOBALS['strTimeDateSeparator'] = 'Ю'; // can be void
$GLOBALS['months_names'] = array('','января', 'февраля', 'марта', 'апреля', 'Май', 'Июнь', 'июля', 'августа', 'Сентябрь', 'Октябрь', 'ноября', 'декабрь');
$GLOBALS['months_url_names'] = array('', 'января', 'февраля', 'марта', 'апреля', 'Май', 'Июнь', 'июля', 'августа', 'Сентябрь', 'Октябрь', 'ноября', 'декабрь');
$GLOBALS['strToday'] = 'Сегодня';
$GLOBALS['strStarting'] = 'из';
$GLOBALS['strTill'] = 'Ю';
$GLOBALS['strTomorrow'] ='Завтра';
$GLOBALS['strYesterday'] ='Вчера';
$GLOBALS['strTwoDaysAgo'] ='Позавчера';
$GLOBALS['strOnThe'] ='Le';
$GLOBALS['strStartingOn']='du';
$GLOBALS['strTillDay']='au';
$GLOBALS['day_of_week'] = array('воскресенье', 'Понедельник', 'вторник', 'среда', 'Четверг', 'Пятница', 'суббота');
?>
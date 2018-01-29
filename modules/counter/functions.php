<?php
// This file should be in UTF8 without BOM - Accents examples: йик
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: functions.php 55438 2017-12-08 14:26:54Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}
$GLOBALS['js_files'][] = get_url('/modules/counter/js/waypoints.min.js');
$GLOBALS['js_files'][] = get_url('/modules/counter/js/jquery.counterup.min.js');

$GLOBALS['js_ready_content_array'][] = " 
           $('.counter').counterUp({
                delay: 10,
                time: 1000
            });";
			
/**
 * Affiche un counter
 *
 * @return
 */		
function get_timer () {
	return "<div class='counter'></div>";
}
?>
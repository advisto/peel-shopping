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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

/**
 * Renvoie les éléments de menu affichables
 *
 * @param array $params
 * @return
 */
function kekoli_hook_admin_menu_items($params) {
	$result['menu_items']['sales_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/kekoli/administrer/kekoli.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_EXPORT"];
	return $result;
}

/**
 * Conversion de valeur de availability_of_carrier pour le champ DateClotureBordereau
 *
 * @param date    $cmd_date 
 * @param integer $availability_of_carrier 
 * @return
 */
function convertToDateClotureBordereau($cmd_date, $availability_of_carrier)
{
	$cmd_date_in_seconde = strtotime($cmd_date);
	$nb_days_in_second   = $availability_of_carrier * 24 * 60 * 60 ;
		
	return date('d/m/Y', $cmd_date_in_seconde + $nb_days_in_second);
}

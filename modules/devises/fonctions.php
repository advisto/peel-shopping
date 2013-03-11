<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 35805 2013-03-10 20:43:50Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * set_current_devise()
 *
 * @param mixed $new_currency_id
 * @return
 */
function set_current_devise($new_currency_id)
{
	if (!empty($new_currency_id)) {
		$resDevise = query("SELECT *
			FROM peel_devises
			WHERE etat='1' AND id='" . intval($new_currency_id) . "'");
		if ($Devise = fetch_object($resDevise)) {
			$_SESSION['session_devise']['symbole'] = String::html_entity_decode_if_needed($Devise->symbole);
			$_SESSION['session_devise']['symbole_place'] = $Devise->symbole_place;
			$_SESSION['session_devise']['conversion'] = $Devise->conversion;
			$_SESSION['session_devise']['code'] = $Devise->code;
		}
	}
}

/**
 * affiche_module_devise()
 *
 * @param boolean $return_mode
 * @return
 */
function affiche_module_devise($return_mode = false)
{
	$output = '';
	$resDevise = query("SELECT *
		FROM peel_devises
		WHERE etat='1'
		ORDER BY devise");
	$url_part = str_replace(array('?devise=' . vb($_GET['devise']), '&devise=' . vb($_GET['devise'])), array('', ''), $_SERVER['REQUEST_URI']);
	if (String::strpos($url_part, '?') === false) {
		$url_part .= '?devise=';
	} else {
		$url_part .= '&devise=';
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/devises.tpl');
	$tpl->assign('STR_MODULE_DEVISES_CHOISIR_DEVISE', $GLOBALS['STR_MODULE_DEVISES_CHOISIR_DEVISE']);
	$tpl->assign('url_part', $url_part);
	$tpl_options = array();
	while ($Devise = fetch_assoc($resDevise)) {
		$tpl_options[] = array(
			'value' => intval($Devise['id']),
			'issel' => $Devise['code'] == $_SESSION['session_devise']['code'],
			'name' => $Devise['devise']
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	if ($return_mode) {
		return $output;
	} else {
		echo $output;
	}
}

?>
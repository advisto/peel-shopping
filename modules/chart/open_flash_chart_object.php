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
// $Id: open_flash_chart_object.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * open_flash_chart_object_str()
 *
 * @param mixed $width
 * @param mixed $height
 * @param mixed $url
 * @param mixed $use_swfobject
 * @param string $base
 * @return
 */
function open_flash_chart_object_str($width, $height, $url, $use_swfobject = true, $base = '')
{
	if(empty($base)){
		$base = $GLOBALS['wwwroot'] . '/modules/chart/';
	}
	// return the HTML as a string

	return _ofc($width, $height, $url, $use_swfobject, $base);
}

/**
 * open_flash_chart_object()
 *
 * @param mixed $width
 * @param mixed $height
 * @param mixed $url
 * @param mixed $use_swfobject
 * @param string $base
 * @return
 */
function open_flash_chart_object($width, $height, $url, $use_swfobject = true, $base = '')
{
	if(empty($base)){
		$base = $GLOBALS['wwwroot'] . '/modules/chart/';
	}
	// stream the HTML into the page

	echo _ofc($width, $height, $url, $use_swfobject, $base);
}

/**
 * _ofc()
 *
 * @param mixed $width
 * @param mixed $height
 * @param mixed $url
 * @param mixed $use_swfobject
 * @param mixed $base
 * @return
 */
function _ofc($width, $height, $url, $use_swfobject, $base)
{
	// I think we may use swfobject for all browsers,
	// not JUST for IE...

	// $ie = strstr(getenv('HTTP_USER_AGENT'), 'MSIE');

	// escape the & and stuff:
	$url = urlencode($url);

	// output buffer
	$out = array();

	// check for http or https:
	if (isset ($_SERVER['HTTPS'])) {
		if (strtoupper ($_SERVER['HTTPS']) == 'ON') {
			$protocol = 'https';
		} else {
			$protocol = 'http';
		}
	} else {
		$protocol = 'http';
	}

	// if there are more than one charts on the
	// page, give each a different ID

	global $open_flash_chart_seqno;
	$obj_id = 'chart';
	$div_name = 'flashcontent';
	// $out[] = '<script src="'. $base .'js/ofc.js"></script>';
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/chart_ofc.tpl');
	
	if (!isset($open_flash_chart_seqno)) {
		$open_flash_chart_seqno = 1;
		$tpl->assign('swfobject_src', get_url('/lib/js/swfobject.js'));
	} else {
		$open_flash_chart_seqno++;
		$obj_id .= '_' . $open_flash_chart_seqno;
		$div_name .= '_' . $open_flash_chart_seqno;
	}
	
	$tpl->assign('use_swfobject', $use_swfobject);
	$tpl->assign('div_name', $div_name);
	$tpl->assign('base', $base);
	$tpl->assign('obj_id', $obj_id);
	$tpl->assign('width', $width);
	$tpl->assign('height', $height);
	$tpl->assign('url', $url);
	$tpl->assign('protocol', $protocol);
	return $tpl->fetch();
}


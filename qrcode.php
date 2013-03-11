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
// $Id: qrcode.php 35805 2013-03-10 20:43:50Z gboussin $
if(!empty($_GET['path'])) {
	define('LOAD_NO_OPTIONAL_MODULE', true);
	define('SKIP_SET_LANG', true);
	// On transmet uniquement un chemin local pour éviter que d'autres sites utilisent cette génération pour n'importe quel QR Code
	include("configuration.inc.php");

	$qrcode = $GLOBALS['wwwroot'].$_GET['path'];
	$cache_id = md5($qrcode);
	$lifetime = 3600*24*30;
	$this_cache_object = new Cache($cache_id, array('group' => 'qrcode'));
	if ($this_cache_object->testTime($lifetime, true)) {
		$output = $this_cache_object->get();
	} else {
		$output = '';
		require_once($GLOBALS['dirroot'] . '/lib/class/pdf/2dbarcodes.php');
		$barcodeobj = new TCPDF2DBarcode($qrcode, 'QRCODE,M');
		ob_start();
		$barcodeobj->getBarcodePNG(3, 3, array(0,0,0));
		$output .= ob_get_contents();
		ob_end_clean();
		$this_cache_object->save($output);
	}
	header('Content-Type: image/png');
	$this_cache_object->echo_headers($lifetime);
	unset($this_cache_object);
	echo $output;
}
?>
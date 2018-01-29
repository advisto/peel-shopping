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
// $Id: qrcode.php 55332 2017-12-01 10:44:06Z sdelaporte $
if(!empty($_GET['path']) || !empty($_GET['barcode'])) {
	define('LOAD_NO_OPTIONAL_MODULE', true);
	define('SKIP_SET_LANG', true);
	define('IN_QRCODE', true);
	// On transmet uniquement un chemin local pour éviter que d'autres sites utilisent cette génération pour n'importe quel QR Code
	include("configuration.inc.php");

	if (!empty($_GET['barcode'])) {
		$data = $_GET['barcode'];
	} else {
		$data = $GLOBALS['wwwroot'].$_GET['path'];
	}
	$cache_id = md5($data);
	$lifetime = 3600*24*30;
	$this_cache_object = new Cache($cache_id, array('group' => 'qrcode'));
	if ($this_cache_object->testTime($lifetime, true)) {
		$output = $this_cache_object->get();
	} else {
		$output = '';
		if (!empty($_GET['barcode'])) {
			require_once($GLOBALS['dirroot'] . '/lib/class/pdf/barcodes.php');
			$barcodeobj = new TCPDFBarcode($data, vb($GLOBALS['site_parameters']['type_of_barcode'], 'EAN13'));
			ob_start();
			$barcodeobj->getBarcodePNG(1.4, 60, array(0,0,0));
		} else {
			require_once($GLOBALS['dirroot'] . '/lib/class/pdf/tcpdf_barcodes_2d.php');
			$barcodeobj = new TCPDF2DBarcode($data, 'QRCODE,M');
			ob_start();
			$barcodeobj->getBarcodePNG(3, 3, array(0,0,0));
		}
		$output .= ob_get_contents();
		ob_end_clean();
		$this_cache_object->save($output);
	}
	header('Content-Type: image/png');
	$this_cache_object->echo_headers($lifetime);
	unset($this_cache_object);
	echo $output;
}

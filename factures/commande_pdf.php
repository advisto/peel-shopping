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
// $Id: commande_pdf.php 55332 2017-12-01 10:44:06Z sdelaporte $
include("../configuration.inc.php");

define('IN_INVOICE_PDF', true);
if (!empty($_GET['code_facture']) && !empty($_GET['mode']) && in_array($_GET['mode'], array('standard', 'facture', 'bdc', 'proforma', 'devis')) && !is_user_bot()) {
	$mode = $_GET['mode'];
	$code_facture = $_GET['code_facture'];

	include($GLOBALS['dirroot']."/lib/class/Invoice.php");
	$invoice_object = new Invoice('P', 'mm', 'A4');
	$is_pdf_generated = $invoice_object->FillDocument($code_facture, null, null, null, null, null, null, $mode, false);
	if (!$is_pdf_generated) {
		include($GLOBALS['repertoire_modele'] . '/haut.php');
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_SEARCH_NO_RESULT']))->fetch();
		include($GLOBALS['repertoire_modele'] . '/bas.php');
	}
} else {
	redirect_and_die(get_url('/'), true);
}


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: commande_pdf.php 38682 2013-11-13 11:35:48Z gboussin $
include("../configuration.inc.php");

if (!empty($_GET['code_facture']) && !empty($_GET['mode']) && !is_user_bot()) {
	$mode = $_GET['mode'];
	$code_facture = $_GET['code_facture'];

	include("../lib/class/Invoice.php");
	$invoice_object = new Invoice('P', 'mm', 'A4');
	$is_pdf_generated = $invoice_object->FillDocument($code_facture, null, null, null, null, null, null, $mode, false);
	if (!$is_pdf_generated) {
		include($GLOBALS['repertoire_modele'] . '/haut.php');
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_SEARCH_NO_RESULT']))->fetch();
		include($GLOBALS['repertoire_modele'] . '/bas.php');
	}
} else {
	redirect_and_die($GLOBALS['wwwroot'] . '/', true);
}

?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: commande_pdf.php 64741 2020-10-21 13:48:51Z sdelaporte $
include("../configuration.inc.php");

define('IN_INVOICE_PDF', true);
// ce fichier génère un document PDF
if (!empty($_GET['code_facture']) && !empty($_GET['mode']) && in_array($_GET['mode'], array('standard', 'facture', 'bdc', 'proforma', 'devis', 'bill_prepare', 'bill_edit', 'quote_prepare')) && !is_user_bot()) {
	// Le mode demandé existe bien, on peut générer le fichier
	// récupération des variables en GET, qui sont à passer ensuite à la fonction qui génère le PDF
	$mode = $_GET['mode'];
	$code_facture = $_GET['code_facture'];

	include($GLOBALS['dirroot']."/lib/class/Invoice.php");
	$invoice_object = new Invoice('P', 'mm', 'A4');
	// Génération du document, la fonction FillDocument récupère les données à afficher, et les affiches dans un document PDF
	$is_pdf_generated = $invoice_object->FillDocument($code_facture, null, null, null, null, null, null, $mode, false);
	if (!$is_pdf_generated) {
		if(function_exists('t2web_database_connect')) {
			t2web_database_connect();
		}
		// Une erreur est survenue, on affiche un message d'erreur
		include($GLOBALS['repertoire_modele'] . '/haut.php');
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_SEARCH_NO_RESULT']))->fetch();
		include($GLOBALS['repertoire_modele'] . '/bas.php');
	}
} else {
	// problème de paramètrage, on redirige vers l'accueil
	redirect_and_die(get_url('/'), true);
}


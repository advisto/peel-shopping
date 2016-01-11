<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: export_clients.php 48447 2016-01-11 08:40:08Z sdelaporte $

define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_webmastering");

if (!empty($_GET['encoding'])) {
	$page_encoding = $_GET['encoding'];
} elseif (!empty($GLOBALS['site_parameters']['export_encoding'])) {
	$page_encoding = $GLOBALS['site_parameters']['export_encoding'];
} else {
	$page_encoding = 'utf-8';
}
$output = '';
$cle = trim(vb($_GET['cle']));
$priv = trim(vb($_GET['priv']));


$filename = "export_clients_" . str_replace('/', '-', date($GLOBALS['date_basic_format_short'])) . ".csv";
// On ne veut pas polluer le fichier exporté par un quelconque message d'erreur
@ini_set('display_errors', 0);
output_csv_http_export_header($filename, 'csv', $page_encoding);
if (!empty($_GET['export']) && $_GET['export'] == 'search_user') {
	$sqlC = afficher_liste_utilisateurs($priv, $cle, $_GET, 'date_insert', false, true);
} else {
	$sqlC = "SELECT u.*
		FROM peel_utilisateurs u
		WHERE " . get_filter_site_cond('utilisateurs', 'u', true) . "";
	if (!empty($_GET['priv'])) {
		$sqlC .= " AND u.priv = '" . nohtml_real_escape_string($_GET['priv']) . "'";
	}
	if (!empty($cle)) {
		$sqlC .= " AND (u.code_client LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.email LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.ville LIKE  '%" . nohtml_real_escape_string($cle) . "%' OR u.nom_famille LIKE  '%" . nohtml_real_escape_string($cle) . "%' OR u.code_postal LIKE  '%" . nohtml_real_escape_string($cle) . "%')";
	}
}
$output .= "Email\tNom\tPrénom\tSociété\tAdresse\tCode postal\tVille\tTéléphone\r\n";

$resC = query($sqlC);

while ($C = fetch_assoc($resC)) {
	$output .= filtre_csv($C['email']) . "\t";
	$output .= filtre_csv($C['nom_famille']) . "\t";
	$output .= filtre_csv($C['prenom']) . "\t";
	$output .= filtre_csv($C['societe']) . "\t";
	$output .= filtre_csv($C['adresse']) . "\t";
	$output .= filtre_csv($C['code_postal']) . "\t";
	$output .= filtre_csv($C['ville']) . "\t";
	$output .= filtre_csv($C['telephone']) . "\r\n";
}

echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);


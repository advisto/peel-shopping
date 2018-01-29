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
// $Id: export_livraisons.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales,admin_webmastering");

if (!empty($_GET['encoding'])) {
	$page_encoding = $_GET['encoding'];
} elseif (!empty($GLOBALS['site_parameters']['export_encoding'])) {
	$page_encoding = $GLOBALS['site_parameters']['export_encoding'];
} else {
	$page_encoding = 'utf-8';
}
$output = '';
$filename = "export_livraisons_" . str_replace('/', '-', date($GLOBALS['date_basic_format_short'])) . ".csv";
// On ne veut pas polluer le fichier exporté par un quelconque message d'erreur
@ini_set('display_errors', 0);
output_csv_http_export_header($filename, 'csv', $page_encoding);

if (empty($_GET["dateadded1"]) || empty($_GET["dateadded2"])) {
	die();
}
if (!empty($_GET["id_statut_livraison"])) {
	$extra_sql = "AND id_statut_livraison = '" . intval($_GET["id_statut_livraison"]) . "'";
} else {
	$extra_sql = "";
}
$sqlC = "SELECT *
	FROM peel_commandes
	WHERE o_timestamp>='" . nohtml_real_escape_string($_GET["dateadded1"]) . "' AND o_timestamp<='" . nohtml_real_escape_string($_GET["dateadded2"]) . "' AND " . get_filter_site_cond('commandes', null, true) . " " . $extra_sql . "
	ORDER BY o_timestamp";

$output .= "Nom\tPrénom\tSociété\tAdresse\tCode postal\tVille\tEtages\tPays\tPoids\tArticle\tQuantité\tTransport\tCommande\tDate\r\n";

$resC = query($sqlC);
$i = 0;

while ($C = fetch_assoc($resC)) {
	$i = 0;
	$numero = $C['id'];
	$date_vente = get_formatted_date($C['o_timestamp'], 'short', 'long');

	$resCA = query("SELECT *
		FROM peel_commandes_articles
		WHERE commande_id = '" . intval($C['id']) . "'  AND " . get_filter_site_cond('commandes_articles', null, true) . "");
	while ($CA = fetch_assoc($resCA)) {
		if ($CA['quantite'] != 0) {
			$output .= filtre_csv($C['nom_ship']) . "\t";
			$output .= filtre_csv($C['prenom_ship']) . "\t";
			$output .= filtre_csv($C['societe_ship']) . "\t";
			$output .= filtre_csv($C['adresse_ship']) . "\t";
			$output .= filtre_csv($C['zip_ship']) . "\t";
			$output .= filtre_csv($C['ville_ship']) . "\t";
			$output .= filtre_csv($C['commentaires']) . "\t";
			$output .= filtre_csv($C['pays_ship']) . "\t";
			$output .= filtre_csv($CA['quantite'] * $CA['poids']) . "\t";
			$output .= filtre_csv($CA['reference'] . " - " . $CA['nom_produit']) . "\t";
			$output .= filtre_csv($CA['quantite']) . "\t";
			$output .= filtre_csv($C['transport']) . "\t" . filtre_csv($C['id']) . "\t";
			$output .= filtre_csv($date_vente);
			$output .= "\r\n";
			$i++;
		}
	}
}

echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);


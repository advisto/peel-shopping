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
// $Id: export_kekoli.php 55332 2017-12-01 10:44:06Z sdelaporte $

define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales,admin_webmastering");



if(!empty($_GET['encoding'])){
	$page_encoding=$_GET['encoding'];
}elseif(!empty($GLOBALS['site_parameters']['export_encoding'])){
	$page_encoding=$GLOBALS['site_parameters']['export_encoding'];
}else{
	$page_encoding='utf-8';
}
$output = '';
$filename="export_kekoli_" . str_replace('/','-',date($GLOBALS['date_basic_format_short'])) . ".csv";
// On ne veut pas polluer le fichier exporté par un quelconque message d'erreur
@ini_set('display_errors', 0);
output_csv_http_export_header($filename, 'csv', $page_encoding);

if (empty($_GET["dateadded1"]) || empty($_GET["dateadded2"])) {
	die();
}
if (isset($_GET['id_statut_livraison']) && is_numeric($_GET['id_statut_livraison'])) {
	$extra1_sql = "AND id_statut_livraison = '" . intval($_GET["id_statut_livraison"]) . "'";
} else {
	$extra1_sql = "";
}
if (isset($_GET['id_statut_paiement']) && is_numeric($_GET['id_statut_paiement'])) {
	$extra2_sql = "AND id_statut_paiement = '" . intval($_GET["id_statut_paiement"]) . "'";
} else {
	$extra2_sql = "";
}
$sqlC = "SELECT *
	FROM peel_commandes c
	WHERE " . get_filter_site_cond('commandes', 'c', true) . " AND type != '4' AND o_timestamp>='" . nohtml_real_escape_string($_GET["dateadded1"]) . "' AND o_timestamp<='" . nohtml_real_escape_string($_GET["dateadded2"]) . "' " . $extra1_sql . " " . $extra2_sql . "
	ORDER BY o_timestamp";

$output .= "NumeroColis;ReferenceExpedition;NomDestinataire;Commune;CodePostal;CodePays;EmailDestinataire;DateClotureBordereau;Adresse1;Adresse2;Adresse3;Adresse4\r\n";

$resC = query($sqlC);

while ($C = fetch_assoc($resC)) {
	$i = 0;
	$numero = $C['id'];
	$date_vente = get_formatted_date($C['o_timestamp'], 'short', 'long');
	$nom_acheteur = $C['nom_ship'];
	$prenom_acheteur = $C['prenom_ship'];
	$societe = $C['societe_ship'];
	$adresse = $C['adresse_ship'];
	$ville = $C['ville_ship'];
	$pays = $C['pays_ship'];

	$code_postal = $C['zip_ship'];
	$etage = $C['commentaires'];


	//On va chercher le nom du transport
	$sql_cond_types = '';
	$sql_cond_pays = '';
	 foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql_cond_types .= ' OR nom_'.$lng.' = "' . nohtml_real_escape_string($C['type']) . '" ';
		$sql_cond_pays .= ' OR pays_'.$lng.' = "' . nohtml_real_escape_string($C['pays_bill']) . '" ';
	 }
	$resType = query("SELECT nom_".$_SESSION['session_langue']." AS nom
		FROM peel_types
		WHERE (id='" . intval($C['type'])."' ". $sql_cond_types .") AND " . get_filter_site_cond('types'));
	$type = fetch_assoc($resType);

	//On va chercher le code iso du pays
	$resPays = query("SELECT iso
		FROM peel_pays
		WHERE " . get_filter_site_cond('pays') . " AND (1 ".$sql_cond_pays.")");
	$pays = fetch_assoc($resPays);

	$NumeroColis          = $C['id'];
	$ReferenceExpedition  = $GLOBALS['site_parameters']['nom_'.$_SESSION['session_langue']].$type['nom'];
	$NomDestinataire      = $C['nom_ship'];
	$Commune              = $C['ville_ship'];
	$CodePostal           = $C['zip_ship'];
	$CodePays             = $pays['iso'];
	$EmailDestinataire    = $C['email_ship'];
	$DateClotureBordereau = convertToDateClotureBordereau($C['o_timestamp'], $GLOBALS['site_parameters']['availability_of_carrier']);
	$Adresse1             = $C['adresse_ship'];
	$Adresse2             = "";
	$Adresse3             = "";
	$Adresse4             = "";
	$Transporteur         = $type['nom'];

	$output .= filtre_csv($NumeroColis) . ";";
	$output .= filtre_csv($ReferenceExpedition) . ";";
	$output .= filtre_csv($NomDestinataire) . ";";
	$output .= filtre_csv($Commune) . ";";
	$output .= filtre_csv($CodePostal) . ";";
	$output .= filtre_csv($CodePays) . ";";
	$output .= filtre_csv($EmailDestinataire) . ";";
	$output .= filtre_csv($DateClotureBordereau) . ";";
	$output .= filtre_csv($Adresse1) . ";";
	$output .= filtre_csv($Adresse2) . ";";
	$output .= filtre_csv($Adresse3) . ";";
	$output .= filtre_csv($Adresse4) . ";";

	$output .= "\r\n";
}

echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);


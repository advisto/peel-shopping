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
// $Id: chart-data.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
define('IN_CHART_DATA', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin*");

require($GLOBALS['dirroot'] . '/modules/chart/advisto-chart.php');

foreach(array('date1', 'date2', 'type', 'renewals', 'width') as $item) {
	if (isset($_GET[$item])) {
		$$item = $_GET[$item];
	} else {
		$$item = null;
	}
}

$data = array();
$all_graph_type = '';
$graph_type = array();
$colors = array();
$legend_font_size = 12;
$font_size = 12;

if(vb($GLOBALS['site_parameters']['chart_product']) == 'flot') {
	$date_format = 'timestamp1000';
} elseif (strtotime($date2) - strtotime($date1) < 6300 * 24 * 30 * 6) {
	$date_format = 'veryshort';
} else {
	$date_format = 'short';
}
if (strlen($date2) == '10') {
	$date2 .= ' 23:59:00';
}
if ($type == 'users-by-age' && a_priv('admin_users', true)) {
	/**
	 * * - de 18 ans (Il ne devrait pas y en avoir, mais le graph est à usage interne, donc bon..)
	 * 18 à 25 ans
	 * 25 à 35 ans
	 * 35 à 45 ans
	 * 45 et plus
	 */
	$sql_age_formula = 'IF(date_naissance>"' . date('Y-m-d 12:00:00', round(time() - 365.25 * 18 * 24 * 3600)) . '", 18,IF(date_naissance BETWEEN "' . date('Y-m-d 12:00:00', round(time() - 365.25 * 25 * 24 * 3600)) . '" AND "' . date('Y-m-d 12:00:00', round(time() - 365.25 * 18 * 24 * 3600)) . '", 25,IF(date_naissance BETWEEN "' . date('Y-m-d 12:00:00', round(time() - 365.25 * 35 * 24 * 3600)) . '" AND "' . date('Y-m-d 12:00:00', round(time() - 365.25 * 25 * 24 * 3600)) . '", 35,IF(date_naissance BETWEEN "' . date('Y-m-d 12:00:00', round(time() - 365.25 * 45 * 24 * 3600)) . '" AND "' . date('Y-m-d 12:00:00', round(time() - 365.25 * 35 * 24 * 3600)) . '", 45,IF(date_naissance<"' . date('Y-m-d 12:00:00', round(time() - 365.25 * 45 * 24 * 3600)) . '", 50,0)))))';
	$type_names = array(18 => '- de 18 ans', 25 => '18 à 25 ans', 35 => '25 à 35 ans', 45 => '35 à 45 ans', 50 => '45 ans et +');
	$sex_names = array('M.' => 'H', 'Mlle' => 'F', 'Mme' => 'F');
	$res = query('SELECT count(*) AS this_count, civilite, ' . $sql_age_formula . ' AS tranche
        FROM peel_utilisateurs
        WHERE ' . get_filter_site_cond('utilisateurs') . ' ' . (!empty($date1)?(' AND date_insert BETWEEN "' . nohtml_real_escape_string($date1) . '" AND "' . nohtml_real_escape_string($date2) . '"'):'') . '
        GROUP BY civilite, ' . $sql_age_formula);
	$data['Tous'] = array();
	while ($row = fetch_assoc($res)) {
		$data[$sex_names[$row['sexe']]][($type_names[$row['tranche']])] = $row['this_count'];
		if (empty($data['Tous'][($type_names[$row['tranche']])])) {
			$data['Tous'][($type_names[$row['tranche']])] = 0;
		}
		$data['Tous'][($type_names[$row['tranche']])] += $row['this_count'];
	}
	$graph_type['Tous'] = 'bar';
	$graph_type['H'] = 'bar';
	$graph_type['F'] = 'bar';
	$title = $GLOBALS['STR_ADMIN_CHART_DATA_AGE_TITLE'] . ' ' . (!empty($date1)?' ' . $GLOBALS['strStartingOn'] . ' ' . get_formatted_date($date1) . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($date2):'');
} elseif ($type == 'forums-count' && a_priv('admin_content', true)) {
	$res = query('SELECT count(*) AS this_count, date
		FROM peel_forums a
		WHERE 1 ' . (!empty($date1)?(' AND date BETWEEN "' . nohtml_real_escape_string($date1) . '" AND "' . nohtml_real_escape_string($date2) . '"'):'') . '
        GROUP BY TO_DAYS(a.date)');
	$total = 0;
	while ($row = fetch_assoc($res)) {
		$data['Messages'][get_formatted_date($row['date'], $date_format)] = $row['this_count'];
		$total += $row['this_count'];
	}
	$title = sprintf($GLOBALS['STR_ADMIN_CHART_DATA_MESSAGES_NUMBER_TITLE'], intval($total)) . ' ' . (!empty($date1)?$GLOBALS['STR_ADMIN_PUBLISHED'] . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($date1) . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($date2):'');
} elseif ($type == 'forums-categories' && a_priv('admin_content', true)) {
	$res = query('SELECT count(*) AS this_count, c.nom
		FROM peel_forums a
		INNER JOIN peel_forums_cat c ON c.id=a.id_cat
		WHERE 1 ' . (!empty($date1)?(' AND date BETWEEN "' . nohtml_real_escape_string($date1) . '" AND "' . nohtml_real_escape_string($date2) . '"'):'') . '
        GROUP BY a.id_cat');
	$total = 0;
	while ($row = fetch_assoc($res)) {
		$data[StringMb::substr($row['nom'], 0, 20)] = $row['this_count'];
		$total += $row['this_count'];
	}
	$all_graph_type = 'pie';
	$legend_font_size = 9;
	$title = sprintf($GLOBALS['STR_ADMIN_CHART_DATA_MESSAGES_CATEGORIES_TITLE'], intval($total)) . ' ' . (!empty($date1)?$GLOBALS['STR_ADMIN_PUBLISHED'] . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($date1) . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($date2):'');
} elseif ($type == 'users-count' && a_priv('admin_users', true)) {
	$data[$GLOBALS["STR_ADMIN_INSCRIPTIONS"]] = array();
	if (empty($graph_type[$GLOBALS["STR_ADMIN_INSCRIPTIONS"]]) || $graph_type[$GLOBALS["STR_ADMIN_INSCRIPTIONS"]] != 'dot') {
		if (!empty($date1)) {
			$t2 = strtotime($date2);
			for($t = strtotime($date1);$t <= $t2;$t += 3600 * 24) {
				$data[$GLOBALS["STR_ADMIN_INSCRIPTIONS"]][get_formatted_date(date('Y-m-d', $t), $date_format)] = 0;
			}
		}
	}
	$res = query('SELECT count(*) AS this_count, date_insert AS date_inscription, 1 AS actif
        FROM peel_utilisateurs
        WHERE ' . get_filter_site_cond('utilisateurs') . ' ' . (!empty($date1)?(' AND date_insert BETWEEN "' . nohtml_real_escape_string($date1) . '" AND "' . nohtml_real_escape_string($date2) . '"'):'') . '
        GROUP BY TO_DAYS(date_insert)');
	// On déclare pour définir l'ordre d'affichage dans le flash
	while ($row = fetch_assoc($res)) {
		if (empty($data[$GLOBALS["STR_ADMIN_INSCRIPTIONS"]][get_formatted_date(StringMb::substr($row['date_inscription'], 0, 10), $date_format)])) {
			$data[$GLOBALS["STR_ADMIN_INSCRIPTIONS"]][get_formatted_date(StringMb::substr($row['date_inscription'], 0, 10), $date_format)] = 0;
		}
		$data[$GLOBALS["STR_ADMIN_INSCRIPTIONS"]][get_formatted_date(StringMb::substr($row['date_inscription'], 0, 10), $date_format)] += $row['this_count'];
	}
	$colors[$GLOBALS["STR_ADMIN_INSCRIPTIONS"]] = '0000FF';
	$title = (!empty($date1)?'' . $GLOBALS['strStartingOn'] . ' ' . get_formatted_date($date1) . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($date2):'');
} elseif ($type == 'product-categories' && a_priv('admin_products', true)) {
	$res = query('SELECT count(*) AS this_count, c.nom_'.$_SESSION['session_langue'].' AS nom_categorie
		FROM peel_produits p
		INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
		INNER JOIN peel_categories c ON c.id=pc.categorie_id AND ' . get_filter_site_cond('categories', 'c') . '
		WHERE ' . get_filter_site_cond('produits', 'p') . '
        GROUP BY c.id');
	while ($row = fetch_assoc($res)) {
		$data[StringMb::substr($row['nom_categorie'], 0, 20)] = $row['this_count'];
	}
	$all_graph_type = 'pie';
	$legend_font_size = 9;
	// $title = 'Catégories des produits';
} elseif ($type == 'users-by-sex' && a_priv('admin_users', true)) {
	$res = query('SELECT count(*) AS this_count, civilite
        FROM peel_utilisateurs
        WHERE ' . get_filter_site_cond('utilisateurs') . '' . (!empty($date1)?(' AND (date BETWEEN "' . nohtml_real_escape_string($date1) . '" AND "' . nohtml_real_escape_string($date2) . '")'):'') . '
        GROUP BY civilite');
	while ($row = fetch_assoc($res)) {
		if ($row['civilite'] == 'M.') {
			$type_name = 'H';
		} else {
			$type_name = 'F';
		}
		$data[$type_name] = $row['this_count'];
	}
	$all_graph_type = 'pie';
	$title = $GLOBALS['STR_ADMIN_CHART_DATA_USERS_BY_SEX'] . ' ' . (!empty($date1)?' ' . $GLOBALS['strStartingOn'] . ' ' . get_formatted_date($date1) . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($date2):'');
} elseif ($type == 'users-by-country' && a_priv('admin_users', true)) {
	$res = query('SELECT count(*) AS this_count, c.nom_'.$_SESSION['session_langue'].' AS country_name
		FROM `peel_utilisateurs` a
		INNER JOIN peel_pays c ON c.id = a.pays AND ' . get_filter_site_cond('pays', 'c')  . '
		WHERE ' . get_filter_site_cond('utilisateurs', 'a') . '' . (!empty($date1)?(' AND (a.date_insert BETWEEN "' . nohtml_real_escape_string($date1) . '" AND "' . nohtml_real_escape_string($date2) . '")'):'') . '
		GROUP BY a.pays');
	while ($row = fetch_assoc($res)) {
		$data[$row['country_name']] = $row['this_count'];
	}
	$all_graph_type = 'pie';
	$title = $GLOBALS['STR_ADMIN_CHART_DATA_USERS_BY_COUNTRY'] . (!empty($date1)?' ' . $GLOBALS['strStartingOn'] . ' ' . get_formatted_date($date1) . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($date2):'');
} elseif ($type == 'sales' && a_priv('admin_sales', true)) {
	$data['CA'] = array();
	$graph_type['CA'] = 'bar';
	if (empty($graph_type['CA']) || $graph_type['CA'] != 'dot') {
		if (!empty($date1)) {
			$t2 = strtotime($date2);
			for($t = strtotime($date1);$t <= $t2;$t += 3600 * 24) {
				$data['CA'][get_formatted_date(date('Y-m-d', $t), $date_format)] = 0;
			}
		}
	}
	$res = query('SELECT SUM(c.montant_ht) AS this_total, c.a_timestamp
		FROM peel_commandes c
		LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND ' . get_filter_site_cond('statut_paiement', 'sp') . '
		WHERE sp.technical_code IN ("being_checked","completed") AND ' . get_filter_site_cond('commandes', 'c') . ' ' . (!empty($date1)?(' AND c.a_timestamp BETWEEN "' . nohtml_real_escape_string($date1) . '" AND "' . nohtml_real_escape_string($date2) . '"'):'') . '
        GROUP BY TO_DAYS(c.a_timestamp)');
	while ($row = fetch_assoc($res)) {
		if (empty($data['CA'][get_formatted_date($row['a_timestamp'], $date_format)])) {
			$data['CA'][get_formatted_date($row['a_timestamp'], $date_format)] = 0;
		}
		$data['CA'][get_formatted_date($row['a_timestamp'], $date_format)] += round($row['this_total'], 2);
	}
	$title = '' . (!empty($date1)?' ' . $GLOBALS['strStartingOn'] . ' ' . get_formatted_date($date1) . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($date2):'');
}
echo advistoChart($data, vb($title), $all_graph_type, $graph_type, $colors, $legend_font_size, $width);


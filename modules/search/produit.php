<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produit.php 36232 2013-04-05 13:16:01Z gboussin $

define('LOAD_NO_OPTIONAL_MODULE', true);
include('../../configuration.inc.php');

if (!is_advanced_search_active()) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die($GLOBALS['wwwroot']."/");
}
// Javascript
$page_encoding='utf-8';
header('Content-type: text/html; charset='.$page_encoding);
$output='';

if (!empty($_POST['search'])) {
	$rqProdSearch = "SELECT p.*, c.id as categorie_id, c.nom_" . $_SESSION['session_langue'] . " as categorie
		FROM peel_produits p
		INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
		INNER JOIN peel_categories c ON c.id = pc.categorie_id
		WHERE p.etat = '1'
		   AND (p.nom_" . $_SESSION['session_langue'] . " LIKE '%" . nohtml_real_escape_string(trim($_POST['search'])) . "%'
			   OR p.reference LIKE '%" . nohtml_real_escape_string(trim($_POST['search'])) . "%'
			   OR p.descriptif_" . $_SESSION['session_langue'] . " LIKE '%" . nohtml_real_escape_string(trim($_POST['search'])) . "%'
			   OR p.description_" . $_SESSION['session_langue'] . " LIKE '%" . nohtml_real_escape_string(trim($_POST['search'])) . "%')
		GROUP BY p.id
		ORDER BY IF(p.nom_" . $_SESSION['session_langue'] . " LIKE '" . nohtml_real_escape_string(trim($_POST['search'])) . "%',1,0) DESC, p.nom_" . $_SESSION['session_langue'] . " ASC
		LIMIT 0,10";
	$rsProdSearch = query($rqProdSearch);
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/search_produit.tpl');
	$tpl->assign('STR_AUCUN_RESULTAT', $GLOBALS['STR_AUCUN_RESULTAT']);
	$tpl_results = array();
	if (num_rows($rsProdSearch) > 0) {
		while ($prodSearch = fetch_assoc($rsProdSearch)) {
			$tpl_results[] = array(
				'urlprod' => get_product_url($prodSearch['id'], $prodSearch["nom_" . $_SESSION['session_langue']], $prodSearch['categorie_id'], $prodSearch['categorie']),
				'name' => $prodSearch['nom_' . $_SESSION['session_langue']]
			);
		}
	}
	$tpl->assign('results', $tpl_results);
	$output .= $tpl->fetch();
}
echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
?>
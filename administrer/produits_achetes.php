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
// $Id: produits_achetes.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales,admin_webmastering,admin_finance,admin_operations");


$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

if (isset($_GET['id'])) {
	affiche_liste_clients_par_produit($_GET['id']);
} else {
	affiche_best_sell_products();
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * affiche_best_sell_products()
 *
 * @return
 */
function affiche_best_sell_products()
{
	include($GLOBALS['dirroot']."/lib/class/ProductsBought.php");
	$sql = ProductsBought::_sql_de_base(null, null, true);
	
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_best_sell_products.tpl');
	
	$Links = new Multipage($sql, 'admin_best_sell_products');
	$HeaderTitlesArray = array('nom_produit' => $GLOBALS["STR_PRODUCT"], $GLOBALS["STR_ADMIN_PRODUITS_ACHETES_COUNT_IN_PREFERED"], 'quantite_totale' => $GLOBALS["STR_QUANTITY"], 'montant_total' => $GLOBALS["STR_AMOUNT"]);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = 'quantite_totale';
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();

	$tpl_prods = array();
	$i = 0;

	foreach ($results_array as $result) {
		$produit = new ProductsBought((object)$result);
		$req = query("SELECT COUNT(*) as nombre
			FROM peel_pensebete
			WHERE id_produit='" . intval($produit->produit_id) . "'");
		$pense_bete_nb = fetch_object($req);
		if (!isset($pense_bete_nb->nombre)) {
			$pense_bete_nb->nombre = 0;
		}
		$tpl_prods[] = array('tr_rollover' => tr_rollover($i, true),
			'lien' => $produit->lien,
			'nombre' => $pense_bete_nb->nombre,
			'quantite_totale' => $produit->quantite_totale,
			'prix' => fprix($produit->montant_total, true, $GLOBALS['site_parameters']['code'], false)
			);
		$i++;
		unset($produit);
	}
	$tpl->assign('prods', $tpl_prods);
	$tpl->assign('links_header_row', $Links->getHeaderRow());
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('STR_ADMIN_PRODUITS_ACHETES_MOST_WANTED', $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_MOST_WANTED']);
	$tpl->assign('STR_PRODUCT', $GLOBALS['STR_PRODUCT']);
	$tpl->assign('STR_ADMIN_PRODUITS_ACHETES_COUNT_IN_PREFERED', $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_COUNT_IN_PREFERED']);
	$tpl->assign('STR_QUANTITY', $GLOBALS['STR_QUANTITY']);
	$tpl->assign('STR_AMOUNT', $GLOBALS['STR_AMOUNT']);
	echo $tpl->fetch();
}


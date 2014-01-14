<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produits_achetes.php 39495 2014-01-14 11:08:09Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_sales,admin_webmastering");

include("../lib/class/ProductsBought.php");

$DOC_TITLE = $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_TITLE'];
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
 * @param integer $limit
 * @return
 */
function affiche_best_sell_products($limit = 500)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_best_sell_products.tpl');
	$tpl_prods = array();
	$i = 0;
	foreach (ProductsBought::find_all() as $produit) {
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
	}
	$tpl->assign('prods', $tpl_prods);
	$tpl->assign('STR_ADMIN_PRODUITS_ACHETES_MOST_WANTED', $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_MOST_WANTED']);
	$tpl->assign('STR_PRODUCT', $GLOBALS['STR_PRODUCT']);
	$tpl->assign('STR_ADMIN_PRODUITS_ACHETES_COUNT_IN_PREFERED', $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_COUNT_IN_PREFERED']);
	$tpl->assign('STR_QUANTITY', $GLOBALS['STR_QUANTITY']);
	$tpl->assign('STR_AMOUNT', $GLOBALS['STR_AMOUNT']);
	echo $tpl->fetch();
}

/**
 * affiche_liste_clients_par_produit()
 *
 * @param integer $id
 * @return
 */
function affiche_liste_clients_par_produit($id)
{
	$produit = ProductsBought::find($id);
	if (!empty($produit)) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_clients_par_produit.tpl');
		$tpl->assign('nom', $produit->nom);
		$tpl_clients = array();
		$i = 0;
		$c = "#E8E8E8";
		foreach ($produit->clients() as $client) {
			if ($c == "#E8E8E8") {
				$c = "#F6F6EB";
			} else {
				$c = "#E8E8E8";
			}
			$tpl_clients[] = array('tr_rollover' => tr_rollover($i, true),
				'href' => $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $client->id_utilisateur,
				'nom_famille' => $client->nom_famille,
				'prenom' => $client->prenom,
				'adresse' => $client->adresse,
				'code_postal' => $client->code_postal,
				'ville' => $client->ville,
				'email' => $client->email,
				'telephone' => $client->telephone,
				'total_quantite' => $client->total_quantite,
				'prix' => fprix($client->total_paye, true, $GLOBALS['site_parameters']['code'], false)
				);
			$i++;
		}
		$tpl->assign('clients', $tpl_clients);
		$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
		$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
		$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
		$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
		$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
		$tpl->assign('STR_QUANTITY_SHORT', $GLOBALS['STR_QUANTITY_SHORT']);
		$tpl->assign('STR_TOTAL_AMOUNT', $GLOBALS['STR_TOTAL_AMOUNT']);
		$tpl->assign('STR_ADMIN_PRODUITS_ACHETES_LIST_TITLE', $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_LIST_TITLE']);
 		echo $tpl->fetch();
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_ERR_ID_NOT_FOUND']))->fetch();
	}
}

?>
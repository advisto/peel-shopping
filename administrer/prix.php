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
// $Id: prix.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_PRIX_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

affiche_liste_prix(vb($_GET['catid']));

switch (vb($_REQUEST['mode'])) {
	case "modifier" :
		if (!verify_token($_SERVER['PHP_SELF'] . vb($_GET['catid']))) {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_INVALID_TOKEN']))->fetch();
		} elseif (!empty($_POST['id'])) {
			foreach($_POST['id'] as $i => $prodid) {
				// $product_object = new Product($prodid, null, false, null, true, !check_if_module_active('micro_entreprise'));
				$prix = get_float_from_user_input($_POST['prix'][$i]);
				// $prix_ht = get_float_from_user_input($_POST['prix'][$i]) / (1 + $product_object->tva / 100);
				$prix_revendeur = get_float_from_user_input($_POST['prix_revendeur'][$i]);
				$product_fields[] = "prix = '" . nohtml_real_escape_string($prix) . "'";
				$product_fields[] = "promotion = '" . nohtml_real_escape_string(get_float_from_user_input($_POST['promotion'][$i])) . "'";
				$product_fields[] = "prix_revendeur = '" . nohtml_real_escape_string($prix_revendeur) . "'";
				$product_fields[] = "prix_achat = '" . nohtml_real_escape_string(get_float_from_user_input($_POST['prix_achat'][$i])) . "'";

				$product_fields = get_table_field_names('peel_produits', null, false, $product_fields);
				query("UPDATE peel_produits
					SET	" . implode(', ', $product_fields) . "
					WHERE id = '" . intval($prodid) . "' AND " . get_filter_site_cond('produits', null, true) . "");
				// unset($product_object);
				
				
			}
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_PRIX_MSG_UPDATED_OK']))->fetch();
		}
		affiche_formulaire_modif_prix(vb($_GET['catid']));
		break;

	case "modif" :
	default :
		affiche_formulaire_modif_prix(vb($_GET['catid']));
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * affiche_formulaire_modif_prix()
 *
 * @param integer $catid
 * @return
 */
function affiche_formulaire_modif_prix($catid)
{	
	$product_fields = array('p.id', 'p.prix', 'p.nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']).' AS nom','p.prix_revendeur','p.prix_achat','p.promotion');
	$product_fields = get_table_field_names('peel_produits', null, false, $product_fields);
	$sql = "SELECT
	" . implode(', ', $product_fields) . "
		FROM peel_produits_categories pc
		INNER JOIN peel_produits p ON pc.produit_id = p.id
		WHERE pc.categorie_id='" . intval($catid) . "' AND " . get_filter_site_cond('produits', 'p', true) . "
		ORDER BY prix DESC";
	$resProd = query($sql);

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_modif_prix.tpl');
	$tpl->assign('action', get_current_url(false) . '?mode=modif&catid=' . $_GET['catid']);
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $catid));
	$tpl->assign('category_name', get_category_name($catid));
	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);

	if (num_rows($resProd)) {
		$tpl_results = array();
		$i = 0;
		while ($prod = fetch_assoc($resProd)) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'id' => intval($prod['id']),
				'modif_href' => 'produits.php?mode=modif&id=' . $prod['id'],
				'nom' => $prod['nom'],
				'prix' => number_format($prod['prix'], 2, '.', ''),
				'prix_revendeur' => number_format(vn($prod['prix_revendeur']), 2, '.', ''),
				'prix_achat' => number_format(vn($prod['prix_achat']), 2, '.', ''),
				'promotion' => number_format(vn($prod['promotion']), 2, '.', '')
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_ADMIN_PRIX_FORM_TITLE', $GLOBALS['STR_ADMIN_PRIX_FORM_TITLE']);
	$tpl->assign('STR_PRODUCT', $GLOBALS['STR_PRODUCT']);
	$tpl->assign('STR_ADMIN_PRIX_PUBLIC_PRICE', $GLOBALS['STR_ADMIN_PRIX_PUBLIC_PRICE']);
	$tpl->assign('STR_ADMIN_RESELLER_PRICE', $GLOBALS['STR_ADMIN_RESELLER_PRICE']);
	$tpl->assign('STR_ADMIN_PRIX_PURCHASE_PRICE', $GLOBALS['STR_ADMIN_PRIX_PURCHASE_PRICE']);
	$tpl->assign('STR_REMISE', $GLOBALS['STR_REMISE']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_ADMIN_PRIX_NO_PRODUCT_FOUND', $GLOBALS['STR_ADMIN_PRIX_NO_PRODUCT_FOUND']);
	$tpl->assign('STR_ADMIN_PRIX_UPDATE', $GLOBALS['STR_ADMIN_PRIX_UPDATE']);
	echo $tpl->fetch();
}

/**
 * affiche_liste_prix()
 *
 * @param mixed $categorie_id
 * @return
 */
function affiche_liste_prix($categorie_id)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_prix.tpl');
	$tpl->assign('categorie_options', get_categories_output(null, 'categories',  $categorie_id, 'option', '&nbsp;&nbsp;', null, null, true, 80));
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_PRIX_TITLE', $GLOBALS['STR_ADMIN_PRIX_TITLE']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	echo $tpl->fetch();
}


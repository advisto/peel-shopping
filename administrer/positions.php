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
// $Id: positions.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_POSITIONS_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

affiche_liste_position(vb($_GET['catid']));

switch (vb($_REQUEST['mode'])) {
	case "positionner" :
		if (!verify_token($_SERVER['PHP_SELF'] . $_GET['catid'])) {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_INVALID_TOKEN']))->fetch();
		} elseif (!empty($_POST['id'])) {
			foreach($_POST['id'] as $i => $prodid) {
				query("UPDATE peel_produits
					SET position = '" . intval($_POST['position'][$i]) . "'
					WHERE id = '" . intval($prodid) . "' AND " . get_filter_site_cond('produits', null, true) . "");
			}
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_POSITIONS_MSG_UPDATED_OK']))->fetch();
		}
		affiche_formulaire_modif_position($_POST['catid']);
		break;

	case "modif" :
	default :
		affiche_formulaire_modif_position(vb($_GET['catid']));
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * affiche_formulaire_modif_position()
 *
 * @param integer $catid
 * @return
 */
function affiche_formulaire_modif_position($catid)
{
	if (empty($catid)) {
		return false;
	}
	$sql = "SELECT p.*
		FROM peel_produits p
		INNER JOIN peel_produits_categories pc ON pc.categorie_id = '" . intval($catid) . "'
		WHERE pc.produit_id = p.id AND " . get_filter_site_cond('produits', 'p', true) . "
		ORDER BY position";
	$resProd = query($sql);

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_modif_position.tpl');
	$tpl->assign('action', get_current_url(false) . '?mode=modif&catid=' . $_GET['catid']);
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $catid));
	$tpl->assign('catid', intval($catid));
	$tpl->assign('category_name', get_category_name($catid));

	$tpl_results = array();
	$i = 0;
	while ($prod = fetch_assoc($resProd)) {
		$product_object = new Product($prod['id'], $prod, true, null, true, !check_if_module_active('micro_entreprise'));
		$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
			'value' => intval($product_object->id),
			'modif_href' => 'produits.php?mode=modif&id=' . $product_object->id,
			'name' => $product_object->name,
			'prix' => fprix($product_object->prix, true, $GLOBALS['site_parameters']['code'], false),
			'position' => $product_object->position
			);
		unset($product_object);
		$i++;
	}
	$tpl->assign('results', $tpl_results);
	$tpl->assign('STR_ADMIN_POSITIONS_FORM_EXPLAIN', $GLOBALS['STR_ADMIN_POSITIONS_FORM_EXPLAIN']);
	$tpl->assign('STR_PRODUCT', $GLOBALS['STR_PRODUCT']);
	$tpl->assign('STR_PRICE', $GLOBALS['STR_PRICE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_POSITIONS_POSITION_PRODUCTS', $GLOBALS['STR_ADMIN_POSITIONS_POSITION_PRODUCTS']);
	echo $tpl->fetch();
}

function maj_position($id, $frm, $img)
{
}

/**
 * affiche_liste_position()
 *
 * @param mixed $categorie_id
 * @return
 */
function affiche_liste_position($categorie_id)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_position.tpl');
	$tpl->assign('categorie_options', get_categories_output(null, 'categories',  $categorie_id, 'option', '&nbsp;&nbsp;', null, null, true, 80));
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_POSITIONS_LIST_TITLE', $GLOBALS['STR_ADMIN_POSITIONS_LIST_TITLE']);
	$tpl->assign('STR_ADMIN_POSITIONS_LIST_EXPLAIN', $GLOBALS['STR_ADMIN_POSITIONS_LIST_EXPLAIN']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	echo $tpl->fetch();
}


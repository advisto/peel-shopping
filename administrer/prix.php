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
// $Id: prix.php 36232 2013-04-05 13:16:01Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$DOC_TITLE = $GLOBALS['STR_ADMIN_PRIX_TITLE'];
include("modeles/haut.php");

affiche_liste_prix(vb($_GET['catid']));

switch (vb($_REQUEST['mode'])) {
	case "modifier" :
		if (!verify_token($_SERVER['PHP_SELF'] . vb($_GET['catid']))) {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_INVALID_TOKEN']))->fetch();
		} elseif (!empty($_POST['id'])) {
			foreach($_POST['id'] as $i => $prodid) {
				// $product_object = new Product($prodid, null, false, null, true, !is_micro_entreprise_module_active());
				$prix = get_float_from_user_input($_POST['prix'][$i]);
				// $prix_ht = get_float_from_user_input($_POST['prix'][$i]) / (1 + $product_object->tva / 100);
				$prix_revendeur = get_float_from_user_input($_POST['prix_revendeur'][$i]);
				query("UPDATE peel_produits
					SET prix = '" . nohtml_real_escape_string($prix) . "', prix_revendeur = '" . nohtml_real_escape_string($prix_revendeur) . "', prix_achat = '" . nohtml_real_escape_string(get_float_from_user_input($_POST['prix_achat'][$i])) . "', promotion = '" . nohtml_real_escape_string(get_float_from_user_input($_POST['promotion'][$i])) . "'
					WHERE id = '" . intval($prodid) . "'");
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

include("modeles/bas.php");

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
	$sql = "SELECT p.id, p.prix, p.nom_" . $_SESSION['session_langue'] . " AS nom, p.prix_revendeur, p.prix_achat, p.promotion
		FROM peel_produits_categories pc
		INNER JOIN peel_produits p ON pc.produit_id = p.id
		WHERE pc.categorie_id='" . intval($catid) . "'
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
				'prix_revendeur' => number_format($prod['prix_revendeur'], 2, '.', ''),
				'prix_achat' => number_format($prod['prix_achat'], 2, '.', ''),
				'promotion' => number_format($prod['promotion'], 2, '.', '')
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
	construit_arbo_categorie($categorie_options, $categorie_id);
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_prix.tpl');
	$tpl->assign('categorie_options', $categorie_options);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_PRIX_TITLE', $GLOBALS['STR_ADMIN_PRIX_TITLE']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	echo $tpl->fetch();
}

?>
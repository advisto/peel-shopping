<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 38682 2013-11-13 11:35:48Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 *
 * @param mixed $prodid
 * @return
 */
function insere_pense($item_id = null, $type = null)
{
	if (empty($item_id) || empty($type)) {
		return false;
	}
	$url_prod = vb($_SERVER['HTTP_REFERER']);
	if ($type == 'produit') {
		$product_object = new Product($item_id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
		$this_url = $product_object->get_product_url();
		$this_name = $product_object->name;
		$this_item = $GLOBALS['STR_THE_PRODUCT'];
		$back_to_item = $GLOBALS['STR_BACK_TO_PRODUCT'];
		$this_field = 'id_produit';
	} elseif ($type == 'annonce') {
		$annonce_object = new Annonce($item_id);
		$this_url = $annonce_object->get_annonce_url();
		$this_name = $annonce_object->titre;
		$this_item = $GLOBALS['STR_MODULE_ANNONCES_THE_AD'];
		$back_to_item = $GLOBALS['STR_MODULE_ANNONCES_BACK_TO_ADS'];
		$this_field = 'id_annonce';
	}
	$query = query("SELECT 1
		FROM peel_pensebete
		WHERE id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND " . $this_field . " = '" . intval($item_id) . "'");
	if (num_rows($query) == 0) {
		$sql = "INSERT INTO peel_pensebete (
			" . word_real_escape_string($this_field) . "
			, id_utilisateur
			, date_insertion
		) VALUES (
			'" . intval($item_id) . "'
			, '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'
			, '" . date('Y-m-d H:i:s', time()) . "')";
		query($sql);
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/pensebete_insere.tpl');
	$tpl->assign('item', $this_item);
	$tpl->assign('name', $this_name);
	$tpl->assign('account_url', get_account_url(false, false));
	$tpl->assign('url', $this_url);
	$tpl->assign('back_to_item', $back_to_item);
	$tpl->assign('STR_COMPTE', $GLOBALS['STR_COMPTE']);
	$tpl->assign('STR_AJOUT_PENSE_BETE', $GLOBALS['STR_AJOUT_PENSE_BETE']);
	$tpl->assign('STR_MODULE_PENSEBETE_HAS_BEEN_ADD_REMINDER', $GLOBALS['STR_MODULE_PENSEBETE_HAS_BEEN_ADD_REMINDER']);
	$tpl->assign('STR_MODULE_PENSEBETE_YOUR_REMINDER_ON_RUB', $GLOBALS['STR_MODULE_PENSEBETE_YOUR_REMINDER_ON_RUB']);
	$tpl->assign('STR_MODULE_PENSEBETE_OF_OUR_ONLINE_SHOP', $GLOBALS['STR_MODULE_PENSEBETE_OF_OUR_ONLINE_SHOP']);
	echo $tpl->fetch();
}

/**
 *
 * @return
 */
function display_product_in_reminder()
{
	$sql = "SELECT pb.id as id_pense_bete, p.id, p.reference, p.nom_" . $_SESSION['session_langue'] . " AS name, p.image1, p.prix * (1-p.promotion/100) as prix, p.promotion, c.id as categorie_id, c.nom_" . $_SESSION['session_langue'] . " as categorie
		FROM peel_produits p
		INNER JOIN peel_pensebete pb ON (pb.id_produit = p.id)
		INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
		INNER JOIN peel_categories c ON c.id = pc.categorie_id
		WHERE pb.id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'
		GROUP BY p.id";
	$query = query($sql);
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/pensebete_display.tpl');
	$tpl->assign('del_src', $GLOBALS['wwwroot'] . '/images/suppression.png');
	$tpl->assign('STR_MODULE_PENSEBETE_PENSE_BETE_PRODUIT', $GLOBALS['STR_MODULE_PENSEBETE_PENSE_BETE_PRODUIT']);
	$tpl->assign('STR_MODULE_PENSEBETE_NO_PRODUCT_IN_REMINDER', $GLOBALS['STR_MODULE_PENSEBETE_NO_PRODUCT_IN_REMINDER']);
	$tpl->assign('STR_TABLE_SUMMARY_CADDIE', $GLOBALS['STR_TABLE_SUMMARY_CADDIE']);
	$tpl->assign('STR_PRODUCT', $GLOBALS['STR_PRODUCT']);
	$tpl->assign('STR_REMISE', $GLOBALS['STR_REMISE']);
	$tpl->assign('STR_UNIT_PRICE', $GLOBALS['STR_UNIT_PRICE']);
	$tpl->assign('STR_DELETE_PROD_CART', $GLOBALS['STR_DELETE_PROD_CART']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	
	if (num_rows($query) > 0) {
		$tpl->assign('are_prods', true);
		$tpl_prods = array();
		while ($prod = fetch_assoc($query)) {
			$product_object = new Product($prod['id'], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
			$prod_image = $product_object->get_product_main_picture($product_object->id, $product_object->default_color_id);
			$urlprod = get_product_url($prod['id'], $prod['name'], $prod['categorie_id'], $prod['categorie']);
			
			$tpl_img = null;
			if (!empty($prod_image)) {
				$tpl_img = $GLOBALS['repertoire_upload'] . '/' . $prod_image;
			}
			$tpl_promo = null;
			if($prod['promotion'] > 0) {
				$tpl_promo = fprix($prod['promotion']);
			}
			$tpl_prods[] = array(
				'del_href' => $GLOBALS['wwwroot'] . '/modules/pensebete/voir.php?mode=delete&id=' . $prod['id_pense_bete'],
				'img' => $tpl_img,
				'urlprod' => $urlprod,
				'name' => $prod['name'],
				'promotion' => $tpl_promo,
				'prix' => fprix($prod['prix'], true)
			);
		}
		$tpl->assign('prods', $tpl_prods);
	}else{
		$tpl->assign('are_prods', false);
	}
	echo $tpl->fetch();
}

?>
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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Affiche la liste des catégories qui sont spéciales
 *
 * @param boolean $return_mode
 * @param string $location
 * @param integer $nb_col_sm
 * @param integer $nb_col_md
 * @return
 */
function affiche_best_seller_produit_colonne($return_mode = false, $location = null, $nb_col_sm = 3, $nb_col_md = 4)
{
	$output = '';
	if (vb($GLOBALS['site_parameters']['act_on_top']) == '1') {
		$requete = "SELECT p.*, SUM(pca.quantite) AS quantite, c.nom_" . $_SESSION['session_langue'] . " AS categorie
			FROM peel_commandes_articles pca
			INNER JOIN peel_commandes pc ON pca.commande_id = pc.id AND " . get_filter_site_cond('commandes', 'pc') . "
			INNER JOIN peel_statut_paiement sp ON sp.id=pc.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
			INNER JOIN peel_produits p ON pca.produit_id = p.id AND " . get_filter_site_cond('produits', 'p') . "
			INNER JOIN peel_categories c ON pca.categorie_id = c.id AND " . get_filter_site_cond('categories', 'c') . "
			WHERE " . get_filter_site_cond('commandes_articles', 'pca', true) . " AND p.nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." != '' AND p.etat='1' AND sp.technical_code IN ('being_checked','completed') " . (!empty($GLOBALS['site_parameters']['best_seller_produit_date'])?' AND pc.a_timestamp > "' . $GLOBALS['site_parameters']['best_seller_produit_date'] .'" ':' ') . "
			GROUP BY pca.produit_id
			ORDER BY quantite DESC
			LIMIT 0, " . intval(vn($GLOBALS['site_parameters']['nb_on_top']));
	} else {
		$requete = "SELECT p.*, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
			INNER JOIN peel_categories c ON c.id=pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
			WHERE p.nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." != '' AND p.etat='1' AND p.on_top='1' AND " .get_filter_site_cond('produits', 'p') . "
			GROUP BY p.id
			ORDER BY RAND()
			LIMIT 0, " . intval(vn($GLOBALS['site_parameters']['nb_on_top']));
	}
	$qid = query($requete);
	$numrows = num_rows($qid);
	if ($numrows > 0) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/best_seller_produit_colonne.tpl');
		$tpl_products = array();
		$i = 0;
		while ($prod = fetch_assoc($qid)) {
			if ((!a_priv("admin_product") && !a_priv("reve")) && $prod['on_reseller'] == 1) {
				continue;
			} else {
				// Faire attention que dans $prod on a bien les noms de colonnes correspondant à ce qui est nécessaire dans la classe product
				$product_object = new Product($prod['id'], $prod, true, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
				if (empty($GLOBALS['site_parameters']['module_best_sellers_return_result_as_link'])) {
					$this_product_in_container_html = get_product_in_container_html($product_object, $GLOBALS['site_parameters']['only_show_products_with_picture_in_containers']);
				} else {
					$this_product_in_container_html = '<a href="' . $product_object->get_product_url() . '">' . $product_object->name . '</a>';
				}
				if (!empty($this_product_in_container_html)) {
					$tpl_products[] = array('html' => $this_product_in_container_html,
						'i' => $i+1);
					$i++;
				}
			}
		}
		$tpl->assign('module_best_sellers_return_result_as_link', !empty($GLOBALS['site_parameters']['module_best_sellers_return_result_as_link']));
		$tpl->assign('nb_col_sm', $nb_col_sm);
		$tpl->assign('nb_col_xs', 1);
		$tpl->assign('nb_col_md', $nb_col_md);
		$tpl->assign('products', $tpl_products);
		$tpl->assign('STR_TOP', $GLOBALS['STR_TOP']);
		$output .= $tpl->fetch();
	}
	if ($return_mode) {
		return $output;
	} else {
		echo $output;
	}
}


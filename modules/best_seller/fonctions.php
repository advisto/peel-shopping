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
// $Id: fonctions.php 39002 2013-11-25 16:38:09Z gboussin $
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
			INNER JOIN peel_commandes pc ON pca.commande_id = pc.id
			INNER JOIN peel_produits p ON pca.produit_id = p.id
			INNER JOIN peel_categories c ON pca.categorie_id = c.id
			WHERE p.nom_" . $_SESSION['session_langue'] . " != '' AND p.etat='1' AND pc.id_statut_paiement IN (2,3)
			GROUP BY pca.produit_id
			ORDER BY quantite DESC
			LIMIT 0, " . intval(vn($GLOBALS['site_parameters']['nb_on_top']));
	} else {
		$requete = "SELECT p.*, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
			INNER JOIN peel_categories c ON c.id=pc.categorie_id
			WHERE p.nom_" . $_SESSION['session_langue'] . " != '' AND p.etat='1' AND p.on_top='1'
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
			// Faire attention que dans $prod on a bien les noms de colonnes correspondant à ce qui est nécessaire dans la classe product
			$product_object = new Product($prod['id'], $prod, true, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
			$this_product_in_container_html = get_product_in_container_html($product_object, $GLOBALS['site_parameters']['only_show_products_with_picture_in_containers']);
			if (!empty($this_product_in_container_html)) {
				$tpl_products[] = array('html' => $this_product_in_container_html,
					'i' => $i+1);
				$i++;
			}
		}
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

?>
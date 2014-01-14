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
// $Id: fonctions.php 39495 2014-01-14 11:08:09Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * show_preview_next()
 * @param integer $product_id
 * @param integer $product_position
 * @param mixed $prev_next
 * @param integer $category_id
 * @return
 */
function show_preview_next($product_id, $product_position, $prev_next, $category_id)
{
	$output = '';
	$sql_cond='';
	if($GLOBALS['site_parameters']['in_category']==0) {
		// Recherche des catégories fille de chaque catégorie trouvée précédemment + suppression des doublons + supression de la categorie 0
		$descending_category_array = array_unique(get_category_tree_and_itself(0, 'sons'));
		$sql_cond = 'ppc.categorie_id IN ("' . implode('","', $descending_category_array) . '")';
	} else {
		$sql_cond = 'ppc.categorie_id="'. intval($category_id) . '"';
    }
	// ATTENTION : dans params_affiche_produits on affiche par défaut avec pp.position ASC, pp.id DESC
	// Donc les tris sur id et position sont inversés
	// Par ailleurs, on fait attention à la compatibilité si plusieurs produits ont la même position
	if($prev_next=='prev') {
		$sql_cond .= " AND (pp.position<" . intval(vn($product_position)) . " OR (pp.position=" . intval(vn($product_position)) . " AND pp.id>" . intval($product_id) . "))";
		$sql_order = "pp.position DESC, pp.id ASC";
	} elseif($prev_next=='next') {
		$sql_cond .= " AND (pp.position>" . intval(vn($product_position)) . " OR (pp.position=" . intval(vn($product_position)) . " AND pp.id<" . intval($product_id) . "))";
		$sql_order = "pp.position ASC, pp.id DESC";
	}
	$sql_cond .= " AND pp.etat = 1 AND pp.on_gift = 0";
	$sql = "SELECT ppc.produit_id AS id, ppc.categorie_id as idC, pp.nom_". $_SESSION['session_langue'] . " AS nom, pp.position
		FROM peel_produits pp
		INNER JOIN peel_produits_categories ppc ON ppc.produit_id = pp.id
		INNER JOIN peel_categories pc ON ppc.categorie_id = pc.id AND pc.etat = 1
		WHERE ".$sql_cond."
		ORDER BY ".$sql_order."
		LIMIT 1";
	$query = query($sql);
	if($result = fetch_assoc($query)) {
		if($prev_next=='prev') {
			$tpl = $GLOBALS['tplEngine']->createTemplate('modules/precedent_suivant_prev.tpl');
			$tpl->assign('STR_PREV', $GLOBALS['STR_PREV']);
		} elseif($prev_next=='next') {
			$tpl = $GLOBALS['tplEngine']->createTemplate('modules/precedent_suivant_next.tpl');
			$tpl->assign('STR_NEXT', $GLOBALS['STR_NEXT']);
		}
		$tpl->assign('href', get_product_url($result['id'], $result['nom'], $result['idC'], get_name_category($result['idC'])));
		$output .= $tpl->fetch();
	}
	return $output;
}

/*
*@ integer idcat
*@ return
*/
function get_name_category($idcat){
	//On recupère le nom de la catégorie correspondant au produit actuel
	$output="";
	$sql='SELECT nom_'. $_SESSION['session_langue'] . ' AS nom
		FROM peel_categories
		WHERE id = "' . intval(vn($idcat)) . '"';
	$result = query($sql);
	if($data = fetch_assoc($result)){
		$output = $data['nom'];
	}
	return $output;
}
?>
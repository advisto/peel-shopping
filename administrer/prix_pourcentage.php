<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: prix_pourcentage.php 36927 2013-05-23 16:15:39Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$DOC_TITLE = $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_TITLE'];
include("modeles/haut.php");

if (!empty($_POST['submit']) && !empty($_POST['operation']) && !empty($_POST['percent_prod']) && is_numeric($_POST['percent_prod']) && !empty($_POST['for_price'])) {
	if (!verify_token($_SERVER['PHP_SELF'])) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_INVALID_TOKEN']))->fetch();
	} else {
		if ($_POST['operation'] == 'minus') {
			$operation_symbol = '-';
		} else {
			$operation_symbol = '+';
		}
		$percent_prod_to_apply = get_float_from_user_input($_POST['percent_prod']) / 100;

		switch ($_POST['for_price']) {
			case "all" :
				$sql_set = "prix = prix * (1 " . $operation_symbol . " " . floatval($percent_prod_to_apply) . "), prix_revendeur = prix_revendeur * (1 " . $operation_symbol . " " . floatval($percent_prod_to_apply) . ")";
				break;

			case "1" :
				$sql_set = "prix = prix * (1 " . $operation_symbol . " " . floatval($percent_prod_to_apply) . ")";
				break;

			case "2" :
				$sql_set = "prix_revendeur = prix_revendeur * (1 " . $operation_symbol . " " . floatval($percent_prod_to_apply) . ")";
				break;

			default:
				$sql_set = "prix = prix * (1 " . $operation_symbol . " " . floatval($percent_prod_to_apply) . "), prix_revendeur = prix_revendeur * (1 " . $operation_symbol . " " . floatval($percent_prod_to_apply) . ")";
				break;
		}
		if (!empty($_POST['categories'])) {
			if (!in_array('all', $_POST['categories'])) {
				$sql_where = "id IN (SELECT produit_id FROM peel_produits_categories WHERE categorie_id IN (" . nohtml_real_escape_string(implode(',', get_category_tree_and_itself($_POST['categories'], 'sons'))) . "))";
			} else {
				$sql_where = '1';
			}
			query ('UPDATE peel_produits
				SET  ' . $sql_set . '
				WHERE ' . $sql_where);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_MSG_UPDATE_OK']))->fetch();
		} elseif (!empty($_POST['produits'])) {
			if (!in_array('all', $_POST['produits'])) {
				$sql_where = 'WHERE id IN ("' . implode('","', nohtml_real_escape_string($_POST['produits'])) . '")';
			} else {
				$sql_where = ' WHERE 1';
			}
			query('UPDATE peel_produits
				SET ' . $sql_set . '
				' . $sql_where);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_MSG_UPDATE_OK']))->fetch();
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_CHOOSE_ITEM']))->fetch();
		}
	}
} else {
	if (isset($_POST['submit'])) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_FORM']))->fetch();
	}
}
$tpl = $GLOBALS['tplEngine']->createTemplate('admin_prix_pourcentage.tpl');
$tpl->assign('action', get_current_url(false));
$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF']));
$tpl_cats_options = array();
$tpl_cats_options[] = array('value' => 'all',
	'issel' => !empty($_POST['categories']) && in_array('all', vb($_POST['categories'])),
	'name' => String::strtoupper($GLOBALS["STR_ADMIN_ALL_CATEGORIES"])
	);
$q_select_cats = query('SELECT id, nom_' . $_SESSION['session_langue'] . '
	FROM peel_categories
	WHERE etat = "1"
	ORDER BY nom_' . $_SESSION['session_langue'] . '');
while ($r_select_cats = fetch_assoc($q_select_cats)) {
	$tpl_cats_options[] = array('value' => intval($r_select_cats['id']),
		'issel' => !empty($_POST['categories']) && in_array($r_select_cats['id'], vb($_POST['categories'])),
		'name' => (!empty($r_select_cats['nom_' . $_SESSION['session_langue']])?$r_select_cats['nom_' . $_SESSION['session_langue']]:'['.$r_select_cats['id'].']')
		);
}
$tpl->assign('cats_options', $tpl_cats_options);

$tpl_products_options = array();
$tpl_products_options[] = array('value' => 'all',
	'issel' => !empty($_POST['produits']) && in_array('all', vb($_POST['produits'])),
	'name' => String::strtoupper($GLOBALS["STR_ADMIN_ALL_PRODUCTS"])
	);
$q_select_products = query('SELECT id, nom_' . $_SESSION['session_langue'] . '
	FROM peel_produits
	WHERE etat = "1"
	ORDER BY nom_' . $_SESSION['session_langue'] . '');
while ($r_select_products = fetch_assoc($q_select_products)) {
	$tpl_products_options[] = array('value' => intval($r_select_products['id']),
		'issel' => !empty($_POST['produits']) && in_array($r_select_products['id'], vb($_POST['produits'])),
		'name' => (!empty($r_select_products['nom_' . $_SESSION['session_langue']])?$r_select_products['nom_' . $_SESSION['session_langue']]:'['.$r_select_products['id'].']')
		);
}
$tpl->assign('products_options', $tpl_products_options);
$tpl->assign('for_price', vb($_POST['for_price']));
$tpl->assign('percent_prod', vb($_POST['percent_prod']));
$tpl->assign('operation', vb($_POST['operation']));
$tpl->assign('STR_VALIDATE', $GLOBALS['STR_VALIDATE']);
$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
$tpl->assign('STR_OR', $GLOBALS['STR_OR']);
$tpl->assign('STR_ADMIN_PRIX_POURCENTAGE_TITLE', $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_TITLE']);
$tpl->assign('STR_ADMIN_PRIX_POURCENTAGE_EXPLAIN', $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_EXPLAIN']);
$tpl->assign('STR_ADMIN_PRIX_POURCENTAGE_CHOOSE_CATEGORY', $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_CHOOSE_CATEGORY']);
$tpl->assign('STR_ADMIN_PRIX_POURCENTAGE_CHOOSE_PRODUCT', $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_CHOOSE_PRODUCT']);
$tpl->assign('STR_ADMIN_PRIX_POURCENTAGE_USERS_RELATED', $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_USERS_RELATED']);
$tpl->assign('STR_ADMIN_PRIX_POURCENTAGE_ENTER_PERCENTAGE', $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_ENTER_PERCENTAGE']);
$tpl->assign('STR_ADMIN_ALL', $GLOBALS['STR_ADMIN_ALL']);
$tpl->assign('STR_ADMIN_PRIX_POURCENTAGE_CLIENTS_ONLY', $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_CLIENTS_ONLY']);
$tpl->assign('STR_ADMIN_PRIX_POURCENTAGE_RESELLERS_ONLY', $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_RESELLERS_ONLY']);
$tpl->assign('STR_ADMIN_PRIX_POURCENTAGE_LOWER', $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_LOWER']);
$tpl->assign('STR_ADMIN_PRIX_POURCENTAGE_RAISE', $GLOBALS['STR_ADMIN_PRIX_POURCENTAGE_RAISE']);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
echo $tpl->fetch();

include("modeles/bas.php");

?>
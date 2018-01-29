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
// $Id: produits_attributs.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_LIST_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$product_id = vn($_GET['id']);

switch (vb($_REQUEST['mode'])) {
	case "delete" :
		// Suppression de l'association entre le produit et les attributs.
		$sql = query("DELETE FROM peel_produits_attributs WHERE produit_id = '" . intval($product_id) . "'");
		affiche_liste_attributs_by_id(vb($_GET['id']));
		break;

	default:
		if (!empty($_POST)) {
			$product_object = new Product($product_id, null, false, null, true, !check_if_module_active('micro_entreprise'));
			if(!empty($product_object->id)) {
				// On supprime les attributs existants
				query("DELETE FROM peel_produits_attributs
					WHERE produit_id = '" . intval($product_id) . "'");
				foreach ($_POST as $this_key => $this_value) {
					if (StringMb::strpos($this_key, 'attribut_id_') === 0) {
						$temp = explode('_', $this_key);
						$this_nom_attribut_id = intval($temp[2]);
						if (!empty($this_nom_attribut_id)) {
							foreach ($this_value as $this_attribut_id) {
								$sql = "INSERT INTO peel_produits_attributs (produit_id, nom_attribut_id, attribut_id)
									VALUES ('" . intval($product_id) . "','" . intval($this_nom_attribut_id) . "','" . intval($this_attribut_id) . "')";
								query($sql);
							}
						}
					}
				}
				$GLOBALS['product_possible_attribute_cache_disable'][$product_id] = true;
			}
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_MSG_UPDATE_OK']))->fetch();
		}
		affiche_liste_attributs_by_id($product_id);
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


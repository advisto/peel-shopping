<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produits_attributs.php 66961 2021-05-24 13:26:45Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_white_label,admin_products");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_LIST_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$product_id = vn($_GET['id']);

switch (vb($_REQUEST['mode'])) {
	case "delete" :
		// Suppression de l'association entre le produit et les attributs.
		$sql = query("DELETE FROM peel_produits_attributs WHERE produit_id = '" . intval($product_id) . "'");
		affiche_liste_attributs_by_id(vb($_GET['id']));
		break;
		
	case "edit_price" :
		foreach ($_POST as $this_key => $this_value) {
			if (StringMb::strpos($this_key, 'attribut_prix_id_') === 0) {
				$temp = explode('_', $this_key);
				$this_attribut_id = intval($temp[3]);
				if (!empty($this_attribut_id)) {
					$sql = "UPDATE peel_attributs SET prix='" . nohtml_real_escape_string($_POST['attribut_prix_id_'.$this_attribut_id]) . "', etat = '" . intval(vn($_POST['attribut_actif_'.$this_attribut_id])) . "', descriptif_" . $_SESSION['session_langue'] . " = '" . nohtml_real_escape_string(vn($_POST['attribut_descriptif_id_'.$this_attribut_id])) . "'
					WHERE id='".intval($this_attribut_id). "'";
					query($sql);
				}
			}
			if (StringMb::strpos($this_key, 'attribut_description_id_') === 0) {
				$temp = explode('_', $this_key);
				$this_attribut_id = intval($temp[3]);
				if (!empty($this_attribut_id)) {
					$sql = "UPDATE peel_attributs SET description_" . $_SESSION['session_langue'] . " ='" . nohtml_real_escape_string($_POST['attribut_description_id_'.$this_attribut_id]) . "'
					WHERE id='".intval($this_attribut_id). "'";
					query($sql);
				}
			}
			if (StringMb::strpos($this_key, 'nom_attribut_actif_') === 0) {
				$temp = explode('_', $this_key);
				$this_nom_attribut_id = intval($temp[3]);
				if (!empty($this_nom_attribut_id)) {
					$sql = "UPDATE peel_nom_attributs SET etat ='" . intval($_POST['nom_attribut_actif_'.$this_nom_attribut_id]) . "'
					WHERE id='".intval($this_nom_attribut_id). "'";
					query($sql);
				}
			}
		}
		edit_attribut_price_per_product($_GET['id']);
		break;
		
	case "add_attributs" :
		if (!empty($_POST['nom_attribut_id'])) {
			$this_nom_attribut_id = intval($_POST['nom_attribut_id']);
			$sql = "INSERT INTO 
				peel_attributs SET descriptif_" . $_SESSION['session_langue'] . " ='" . nohtml_real_escape_string($_POST['attribut_descriptif']) . "', 
				prix='" . nohtml_real_escape_string($_POST['attribut_prix']) . "' , 
				".(!empty($_POST['attribut_description'])?"description_" . $_SESSION['session_langue'] . " ='" . nohtml_real_escape_string($_POST['attribut_description']) . "' ,":"")." 
				id_nom_attribut = '" . intval($this_nom_attribut_id) . "'";
			query($sql);
			$attributs_id = insert_id();				
			$sql = "INSERT INTO peel_produits_attributs SET nom_attribut_id ='". intval($this_nom_attribut_id) . "' , attribut_id = '". intval($attributs_id) . "', produit_id = '". intval($_GET['id']) . "'";
			query($sql);
		}
		edit_attribut_price_per_product($_GET['id']);
		break;
		
	case "add_nom_attributs" :
		if(!empty($_POST)) {
			$sql = "INSERT INTO 
			peel_nom_attributs SET nom_" . $_SESSION['session_langue'] . " = '" . nohtml_real_escape_string($_POST['nom_attributs']) . "'".(!empty($GLOBALS['site_parameters']['special_price_by_group_attribut'])?' ,technical_code = "'.nohtml_real_escape_string($GLOBALS['site_parameters']['special_price_by_group_attribut']).'"':'').", etat = 1, type_affichage_attribut = 3, show_description = 1";
			query($sql);
			$nom_attributs_id = insert_id();				
			$sql = "INSERT INTO peel_produits_attributs SET nom_attribut_id ='". intval($nom_attributs_id) . "' , produit_id = '". intval($_GET['id']) . "'";
			query($sql);
		}
		edit_attribut_price_per_product($_GET['id']);
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


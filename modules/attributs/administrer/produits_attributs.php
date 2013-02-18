<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produits_attributs.php 35067 2013-02-08 14:21:55Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

include($GLOBALS['dirroot'] . "/modules/attributs/administrer/fonctions.php");

$DOC_TITLE = $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_LIST_TITLE'];
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");

switch (vb($_REQUEST['mode'])) {
	case "associe" :
		if (!empty($_POST)) {
			$product_id = vn($_GET['id']);
			$product_object = new Product($product_id, null, false, null, true, !is_micro_entreprise_module_active());
			if(!empty($product_object->id)) {
				// On supprime les attributs existants
				query("DELETE FROM peel_produits_attributs
					WHERE produit_id = '" . intval($product_id) . "'");
				foreach ($_POST as $this_key => $this_value) {
					if (String::strpos($this_key, 'attribut_id_') === 0) {
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
			}
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_MSG_UPDATE_OK']))->fetch();
		}
		affiche_liste_attributs_by_id(vb($_GET['id']));
		break;

	default :
		affiche_liste_attributs_by_id(vb($_GET['id']));
		break;
}

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

?>
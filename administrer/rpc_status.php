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
// $Id: rpc_status.php 38682 2013-11-13 11:35:48Z gboussin $
define('IN_PEEL_ADMIN', true);
define('IN_RPC', true);
define('LOAD_NO_OPTIONAL_MODULE', true);
define('SKIP_SET_LANG', true);
include("../configuration.inc.php");

if (!empty($_GET['encoding'])) {
	$page_encoding = $_GET['encoding'];
} else {
	$page_encoding = 'utf-8';
}
$output = '';

if (!est_identifie() || empty($_POST)) {
	$output .= 'nok';
} else {
	output_general_http_header($page_encoding);
	// On fait les tests de droits une bonne fois pour toutes
	if(vb($_POST['mode']) == 'langues') {
		$new_status = ($_POST['current_status']+2)%3-1;
	} else {
		$new_status = 1-$_POST['current_status'];
	}
	$new_status_sql_value = $new_status;
	if(vb($_POST['mode']) == 'countries' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_pays
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'types' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_types
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'tailles' && a_priv("admin_products")) {
		$sql = "UPDATE peel_tailles
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'couleurs' && a_priv("admin_products")) {
		$sql = "UPDATE peel_couleurs
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'paiement' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_paiement
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'contact' && a_priv("admin_manage")) {
		if(empty($new_status_sql_value)) {
			$new_status_sql_value = "FALSE";
		}else{
			$new_status_sql_value = "TRUE";
		}
		$sql = "UPDATE peel_admins_contacts_planified
			SET actif='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'devises' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_devises
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'banner' && a_priv("admin_content")) {
		$sql = "UPDATE peel_banniere
			SET etat='%s'
			WHERE id='%s'";
		// Suppression des caches de bannières
		$this_cache_object = new Cache(null, array('group' => 'affiche_banner_data'));
		$this_cache_object->delete_cache_file(true);
		unset($this_cache_object);
	}elseif(vb($_POST['mode']) == 'avis' && a_priv("admin_webmastering")) {
		$sql = "UPDATE peel_avis
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'attributs' && a_priv("admin_products")) {
		$sql = "UPDATE peel_nom_attributs
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'articles' && a_priv("admin_content")) {
		$sql = "UPDATE peel_articles
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'rubriques' && a_priv("admin_content")) {
		$sql = "UPDATE peel_rubriques
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'produits' && a_priv("admin_products")) {
		$sql = "UPDATE peel_produits
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'utilisateurs' && a_priv("admin_users")) {
		/*
		// Pour la page de liste d'utilisateurs, on n'utilise pas le jquery pour gérer des points plus complexes (désactivation d'annonces ou autres) et mettre des messages spécifiques
		$sql = "UPDATE peel_utilisateurs
			SET etat='%s'
			WHERE id='%s'";
		*/
	}elseif(vb($_POST['mode']) == 'marques' && a_priv("admin_products")) {
		$sql = "UPDATE peel_marques
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'langues' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_langues
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'html' && a_priv("admin_content")) {
		$sql = "UPDATE peel_html
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'email-templates' && a_priv("admin_content")) {
		if(empty($new_status_sql_value)) {
			$new_status_sql_value = "FALSE";
		}else{
			$new_status_sql_value = "TRUE";
		}
		$sql = "UPDATE peel_email_template
			SET active='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'codes_promos' && a_priv("admin_sales,admin_users")) {
		$sql = "UPDATE peel_codes_promos
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'categories' && a_priv("admin_products")) {
		$sql = "UPDATE peel_categories
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'lexique' && a_priv("admin_products")) {
		$sql = "UPDATE peel_lexique
			SET etat='%s'
			WHERE id='%s'";
	}elseif(vb($_POST['mode']) == 'configuration' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_configuration
			SET etat='%s'
			WHERE id='%s'";
	} elseif(vb($_POST['mode']) == 'abus' && a_priv("admin_moderation")) {
		$new_status_sql_value = nohtml_real_escape_string($_POST['value']);
		$sql = "UPDATE peel_abus_comment
			SET status='%s', id_admin='".intval($_SESSION['session_utilisateur']['id_utilisateur'])."', status_change_date='".date('Y-m-d H:i:s')."'
			WHERE id='%s'";
	} else {
		die('nok2');
	}
	// On met à jour les positions en fonction de la liste reçue en POST
	query(sprintf($sql, $new_status_sql_value, intval($_POST['id'])));
	$output .= $new_status;
}
echo String::convert_encoding($output, $page_encoding, GENERAL_ENCODING);

?>
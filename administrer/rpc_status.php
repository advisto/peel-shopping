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
// $Id: rpc_status.php 55415 2017-12-06 14:10:40Z sdelaporte $
define('IN_PEEL_ADMIN', true);
define('IN_RPC', true);
include("../configuration.inc.php");

if (!empty($_GET['encoding'])) {
	$page_encoding = $_GET['encoding'];
} else {
	$page_encoding = 'utf-8';
}
output_general_http_header($page_encoding);
$output = '';

if (!est_identifie() || empty($_POST)) {
	$output .= 'nok';
} elseif(vb($_POST['mode']) == 'delivery_status' && !empty($GLOBALS['site_parameters']['statut_livraison_picto'][$_POST['new_status']])) {
	query("UPDATE peel_commandes
		SET id_statut_livraison ='" . intval($_POST['new_status']) . "'
		WHERE id='" . intval($_POST['id']) . "' AND " . get_filter_site_cond('commandes', null, true));
	$output .=  $GLOBALS['administrer_url'] . '/images/' . $GLOBALS['site_parameters']['statut_livraison_picto'][$_POST['new_status']];
} else {
	$mode = vb($_POST['mode']);
	// On fait les tests de droits une bonne fois pour toutes
	$new_status_sql_value = '';
	if (isset($_POST['current_status'])) {
		if($mode == 'langues') {
			$new_status = (vn($_POST['current_status'])+2)%3-1;
		} else {
			$new_status = 1-vn($_POST['current_status']);
		}
		$new_status_sql_value = $new_status;
	}
	if($mode == 'countries' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_pays
			SET etat='%s'
			WHERE id='%s' AND " .  get_filter_site_cond('pays', null, true) . "";
	}elseif($mode == 'types' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_types
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('types', null, true) . "";
	}elseif($mode == 'tailles' && a_priv("admin_products")) {
		$sql = "UPDATE peel_tailles
			SET etat='%s'
			WHERE id='%s' AND " .  get_filter_site_cond('tailles', null, true) . "";
	}elseif($mode == 'couleurs' && a_priv("admin_products")) {
		$sql = "UPDATE peel_couleurs
			SET etat='%s'
			WHERE id='%s' AND " .  get_filter_site_cond('couleurs', null, true) . "";
	}elseif($mode == 'paiement' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_paiement
			SET etat='%s'
			WHERE id='%s' AND " .  get_filter_site_cond('paiement', null, true) . "";
	}elseif($mode == 'contact' && a_priv("admin_manage")) {
		if(empty($new_status_sql_value)) {
			$new_status_sql_value = "FALSE";
		}else{
			$new_status_sql_value = "TRUE";
		}
		$sql = "UPDATE peel_admins_contacts_planified
			SET actif='%s'
			WHERE id='%s'";
	}elseif($mode == 'devises' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_devises
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('devises', null, true) . "";
	}elseif($mode == 'banner' && a_priv("admin_content")) {
		$sql = "UPDATE peel_banniere
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('banniere', null, true);
	}elseif($mode == 'avis' && a_priv("admin_webmastering")) {
		$sql = "UPDATE peel_avis
			SET etat='%s'" . ($new_status==1 ? ", date_validation=IF(YEAR(date_validation)>0,date_validation,'" . nohtml_real_escape_string(date('Y-m-d H:i:s', time())) . "')": "") . "
			WHERE id='%s'";
	}elseif($mode == 'attributs' && a_priv("admin_products")) {
		$sql = "UPDATE peel_nom_attributs
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('nom_attributs', null, true) . "";
	}elseif($mode == 'articles' && a_priv("admin_content")) {
		$sql = "UPDATE peel_articles
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('articles', null, true) . "";
	}elseif($mode == 'rubriques' && a_priv("admin_content")) {
		$sql = "UPDATE peel_rubriques
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('rubriques', null, true) . "";
	}elseif($mode == 'produits' && a_priv("admin_products")) {
		$sql = "UPDATE peel_produits
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('produits', null, true) . "";
	}elseif($mode == 'utilisateurs' && a_priv("admin_users")) {
		/*
		// Pour la page de liste d'utilisateurs, on n'utilise pas le jquery pour gérer des points plus complexes (désactivation d'annonces ou autres) et mettre des messages spécifiques
		$sql = "UPDATE peel_utilisateurs
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('utilisateurs', null, true) . "";
		*/
	}elseif($mode == 'marques' && a_priv("admin_products")) {
		$sql = "UPDATE peel_marques
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('marques', null, true);
	}elseif($mode == 'langues' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_langues
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('langues', null, true) . "";
	}elseif($mode == 'html' && a_priv("admin_content")) {
		$sql = "UPDATE peel_html
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('html', null, true) . "";
	}elseif($mode == 'email-templates' && a_priv("admin_content")) {
		if(empty($new_status_sql_value)) {
			$new_status_sql_value = "FALSE";
		}else{
			$new_status_sql_value = "TRUE";
		}
		$sql = "UPDATE peel_email_template
			SET active='%s'
			WHERE id='%s' AND " . get_filter_site_cond('email_template', null, true) . "";
	}elseif($mode == 'codes_promos' && a_priv("admin_sales,admin_users")) {
		$sql = "UPDATE peel_codes_promos
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('codes_promos', null, true) . "";
	}elseif($mode == 'categories' && a_priv("admin_products")) {
		$sql = "UPDATE peel_categories
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('categories', null, true) . "";
	}elseif($mode == 'lexique' && a_priv("admin_products")) {
		$sql = "UPDATE peel_lexique
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('lexique', null, true) . "";
	}elseif($mode == 'configuration' && a_priv("admin_manage")) {
		$sql = "UPDATE peel_configuration
			SET etat='%s'
			WHERE id='%s' AND " . get_filter_site_cond('configuration', null, true) . "";
	} elseif($mode == 'abus' && a_priv("admin_moderation")) {
		$new_status_sql_value = nohtml_real_escape_string($_POST['value']);
		$sql = "UPDATE peel_abus_comment
			SET status='%s', id_admin='".intval($_SESSION['session_utilisateur']['id_utilisateur'])."', status_change_date='".date('Y-m-d H:i:s')."'
			WHERE id='%s'";
	} elseif(function_exists('rpc_status_'.$mode)) {
		$function_name = 'rpc_status_'.$mode;
		$new_status = $function_name($_POST);
	}
	if(empty($function_name) && !empty($sql)) {
		// On met à jour les positions en fonction de la liste reçue en POST
		query(sprintf($sql, $new_status_sql_value, intval($_POST['id'])));
	}
	call_module_hook('rpc_status', array('new_status' => $new_status, 'current_status' => vb($_POST['current_status']), 'id' => intval($_POST['id']), 'mode' => $mode));
	if(isset($new_status)) {
		$output .= $new_status;
	}
}
echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);


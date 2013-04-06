<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: utilisateur_liste.php 36232 2013-04-05 13:16:01Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

$HeaderTitlesArray = array($GLOBALS["STR_ADMIN_ADMIN_ACTIONS_ACTIONS"], 'code_client' => $GLOBALS["STR_ADMIN_PRIVILEGE"].' / '.$GLOBALS["STR_ADMIN_UTILISATEURS_CLIENT_CODE"], 'nom_famille' => $GLOBALS["STR_FIRST_NAME"].' / '.$GLOBALS["STR_LAST_NAME"].'<br />'.$GLOBALS["STR_EMAIL"]);
$HeaderTitlesArray[] = $GLOBALS["STR_TELEPHONE"];
if (is_groups_module_active()) {
	$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_GROUP"];
}
$HeaderTitlesArray['date_insert'] = $GLOBALS["STR_ADMIN_UTILISATEURS_REGISTRATION_DATE"];
$HeaderTitlesArray['remise_percent'] = $GLOBALS["STR_ADMIN_DISCOUNT"];
$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_UTILISATEURS_RECEIVED_CREDIT"];
if (is_parrainage_module_active()) {
	$HeaderTitlesArray['avoir'] = $GLOBALS["STR_ADMIN_UTILISATEURS_WAITING_CREDIT"];
}
$HeaderTitlesArray['points'] = $GLOBALS['STR_GIFT_POINTS'];
if (is_parrainage_module_active()) {
	$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_UTILISATEURS_SPONSORED_ORDERS"];
	$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_UTILISATEURS_HAS_SPONSOR"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':';
}
$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_SUBTITLE"];
$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_UTILISATEURS_CREATE_ORDER"];
$Links->HeaderTitlesArray = $HeaderTitlesArray;
$select_search_array['date_insert'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"].' ' . $GLOBALS["STR_ADMIN_DATE_ON"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"].' '.$GLOBALS["STR_ADMIN_DATE_AFTER"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"].' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"] . ' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"]. $GLOBALS['STR_BEFORE_TWO_POINTS'].':');
$select_search_array['date_last_paiement'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' ' . $GLOBALS["STR_ADMIN_DATE_ON"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' '.$GLOBALS["STR_ADMIN_DATE_AFTER"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' '.$GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
$select_search_array['date_statut_commande'] = array(5 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_NO_ORDER"].' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 6 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_ORDER_NOT_PAID"] . ' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 7 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_ORDER_PAID"].' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
$select_search_array['date_derniere_connexion'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' ' . $GLOBALS["STR_ADMIN_DATE_ON"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' ' .$GLOBALS["STR_ADMIN_DATE_AFTER"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
$select_search_array['date_contact_prevu'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' ' . $GLOBALS["STR_ADMIN_DATE_ON"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' ' .$GLOBALS["STR_ADMIN_DATE_AFTER"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
$select_search_array['ads_count'] = array(1 => $GLOBALS["STR_ADMIN_COMPARE_EQUALS"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_MORE_THAN"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_COMPARE_BETWEEN"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_LESS_THAN"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
$select_search_array['abonne'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NEVER"], 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NOT_NOW"], 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NOT_NOW_BUT_EARLIER"], 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_ALL"]);
$select_search_array['nombre_produit'] = tab_followed_nombre_produit();
$tpl = $GLOBALS['tplEngine']->createTemplate('admin_utilisateur_liste.tpl');
$tpl->assign('action', get_current_url(false));
$tpl->assign('profil_select_options', get_profil_select_options(vb($_GET['priv'])));
$tpl->assign('newsletter_options', formSelect('newsletter', tab_followed_newsletter(), vb($_GET['newsletter'])));
$tpl->assign('offre_commercial_options', formSelect('offre_commercial', tab_followed_newsletter(), vb($_GET['offre_commercial'])));

// sélection des commerciaux
$comm_query = query('SELECT u.id_utilisateur, u.prenom, u.nom_famille
	FROM peel_utilisateurs u2
	INNER JOIN peel_utilisateurs u ON u.id_utilisateur = u2.commercial_contact_id
	WHERE u2.commercial_contact_id != 0
	GROUP BY u2.commercial_contact_id');
$tpl_comm_opts = array();
while ($commercial = fetch_assoc($comm_query)) {
	$tpl_comm_opts[] = array('value' => $commercial["id_utilisateur"],
		'issel' => String::str_form_value(vb($_GET['commercial'])) == $commercial["id_utilisateur"],
		'prenom' => vb($commercial["prenom"]),
		'nom_famille' => vb($commercial["nom_famille"])
		);
}
$tpl->assign('commercial_options', $tpl_comm_opts);

$tpl->assign('country_select_options', get_country_select_options(null, vb($_GET['pays']), 'id', true, null, false));

$tpl_langs = array();
foreach ($GLOBALS['lang_codes'] as $lng) {
	$tpl_langs[] = array('value' => $lng,
		'issel' => (vb($_GET['user_lang']) == $lng),
		'name' => $lng
		);
}
$tpl->assign('langs', $tpl_langs);

// sélection des continents
$query_continent = query("SELECT id, name_" . $_SESSION['session_langue'] . " AS name
	FROM peel_continents
	ORDER BY name_".$_SESSION['session_langue']);
$tpl_continent_inps = array();
while ($continent = fetch_assoc($query_continent)) {
	$tpl_continent_inps[] = array('value' => $continent['id'],
		'issel' => !empty($_GET['continent']) && is_array($_GET['continent']) && in_array($continent['id'], $_GET['continent']),
		'name' => $continent['name']
		);
}
$tpl->assign('continent_inputs', $tpl_continent_inps);

$tpl_date_insert_opts = array();
foreach ($select_search_array['date_insert'] as $index => $item) {
	$tpl_date_insert_opts[] = array('value' => $index,
		'issel' => String::str_form_value(vb($_GET['date_insert'])) == $index,
		'name' => $item
		);
}
$tpl->assign('date_insert_options', $tpl_date_insert_opts);

$tpl_date_last_paiement_opts = array();
foreach ($select_search_array['date_last_paiement'] as $index => $item) {
	$tpl_date_last_paiement_opts[] = array('value' => $index,
		'issel' => String::str_form_value(vb($_GET['date_last_paiement'])) == $index,
		'name' => $item
		);
}
$tpl->assign('date_last_paiement_options', $tpl_date_last_paiement_opts);

$tpl_date_statut_commande_opts = array();
foreach ($select_search_array['date_statut_commande'] as $index => $item) {
	$tpl_date_statut_commande_opts[] = array('value' => $index,
		'issel' => String::str_form_value(vb($_GET['date_statut_commande'])) == $index,
		'name' => $item
		);
}
$tpl->assign('date_statut_commande_options', $tpl_date_statut_commande_opts);

$tpl_user_origin_opts = array();
$i = 1;
while (isset($GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i])) {
	$tpl_user_origin_opts[] = array('value' => $i,
		'issel' => String::str_form_value(vb($_GET['origin'])) == $i,
		'name' => $GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i]
		);
	$i++;
}
$tpl->assign('user_origin_options', $tpl_user_origin_opts);

$tpl->assign('ville_cp', vb($_GET['ville_cp']));
$tpl->assign('seg_who', formSelect('seg_who', tab_who(), vb($_GET['seg_who'])));
$tpl->assign('seg_buy', formSelect('seg_buy', tab_buy(), vb($_GET['seg_buy'])));
$tpl->assign('seg_want', formSelect('seg_want', tab_want(), vb($_GET['seg_want'])));
$tpl->assign('seg_think', formSelect('seg_think', tab_think(), vb($_GET['seg_think'])));
$tpl->assign('seg_followed', formSelect('seg_followed', tab_followed(), vb($_GET['seg_followed'])));

$tpl->assign('is_destockplus_module_active', is_destockplus_module_active());
$tpl->assign('is_abonnement_module_active', is_abonnement_module_active());
if (is_abonnement_module_active()) {
	$tpl->assign('abonne', formSelect('abonne', tab_followed_abonne(), vb($_GET['abonne'])));
}

$tpl_produits_opts = array();
$prod_query = query('SELECT id, nom_' . $_SESSION['session_langue'] . '
	FROM peel_produits
	ORDER BY nom_' . $_SESSION['session_langue']);
while ($this_product = fetch_assoc($prod_query)) {
	$tpl_produits_opts[] = array('value' => $this_product['id'],
		'issel' => String::str_form_value(vb($_GET['list_produit'])) == $this_product['id'],
		'name' => $this_product['nom_' . $_SESSION['session_langue']],
		'id' => $this_product['id']
		);
}
$tpl->assign('produits_options', $tpl_produits_opts);
$tpl->assign('nombre_produit', formSelect('nombre_produit', tab_followed_nombre_produit(), vb($_GET['nombre_produit'])));
$tpl->assign('is_annonce_module_active', is_annonce_module_active());
if (is_annonce_module_active()) {
	$tpl_ads_opts = array();
	foreach ($select_search_array['ads_count'] as $index => $item) {
		$tpl_ads_opts[] = array('value' => $index,
			'issel' => (vb($_GET['ads_count']) == $index),
			'name' => $item
			);
	}
	$tpl->assign('ads_options', $tpl_ads_opts);

	$tpl_annonces_opts = array();
	$ad_categories = get_ad_categories();
	foreach ($ad_categories as $this_category_id => $this_category_name) {
		$tpl_annonces_opts[] = array('value' => $this_category_id,
			'issel' => (vb($_GET['list_annonce']) == $this_category_id),
			'name' => $this_category_name
			);
	}
	$tpl->assign('annonces_options', $tpl_annonces_opts);
}

$tpl_date_contact_prevu_opts = array();
foreach ($select_search_array['date_contact_prevu'] as $index => $item) {
	$tpl_date_contact_prevu_opts[] = array('value' => $index,
		'issel' => (vn($_GET['date_contact_prevu']) == $index),
		'name' => $item
		);
}
$tpl->assign('date_contact_prevu_options', $tpl_date_contact_prevu_opts);
$tpl->assign('raison', formSelect('raison', tab_followed_reason(), vb($_GET['raison'])));

$tpl_date_derniere_connexion_opts = array();
foreach ($select_search_array['date_derniere_connexion'] as $index => $item) {
	$tpl_date_derniere_connexion_opts[] = array('value' => $index,
		'issel' => (vb($_GET['date_derniere_connexion']) == $index),
		'name' => $item
		);
}
$tpl->assign('date_derniere_connexion_options', $tpl_date_derniere_connexion_opts);
$tpl->assign('count_HeaderTitlesArray', count($HeaderTitlesArray));
$tpl->assign('nbRecord', vn($Links->nbRecord));
$tpl->assign('is_client_info', isset($_GET['client_info']));
$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);
$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
$tpl->assign('priv', $priv);
$tpl->assign('cle', $cle);
$tpl->assign('link_multipage', $Links->GetMultipage());
$tpl->assign('link_HeaderRow', $Links->getHeaderRow());
$tpl->assign('is_not_demo', !a_priv('demo'));
$tpl->assign('is_groups_module_active', is_groups_module_active());
$tpl->assign('is_parrainage_module_active', is_parrainage_module_active());

if (!empty($results_array)) {
	$tpl_results = array();
	$i = 0;
	foreach ($results_array as $user) {
		$phone_output_array = array();
		if (!empty($user['telephone']) && is_phone_cti_module_active()) {
			$phone_output_array[] = getCallLink($user['id_utilisateur'], String::str_shorten_words($user['telephone'], 16, ' '), $user['email'], $user['pays'], true);
		} elseif (!empty($user['telephone'])) {
			$phone_output_array[] = $user['telephone'];
		}
		if (!empty($user['portable']) && is_phone_cti_module_active()) {
			$phone_output_array[] = getCallLink($user['id_utilisateur'], String::str_shorten_words($user['portable'], 16, ' '), $user['email'], $user['pays'], true);
		} elseif (!empty($user['portable'])) {
			$phone_output_array[] = $user['portable'];
		}

		$tpl_annonces_count = null;
		if (is_annonce_module_active()) { // si le module d'annonce est activé
			$annonces_count = query('SELECT count(*) AS nb
				FROM peel_lot_vente
				WHERE id_personne = ' . intval($user['id_utilisateur']));
			$annonces_count = fetch_assoc($annonces_count);
			$tpl_annonces_count = vn($annonces_count["nb"]);
		}

		$tpl_group_nom = null;
		$tpl_group_remise = null;
		if (is_groups_module_active()) {
			$sqlG = "SELECT *
				FROM peel_groupes
				WHERE id = '" . intval($user['id_groupe']) . "'";
			$resG = query($sqlG);
			if ($G = fetch_object($resG)) {
				$tpl_group_nom = $G->nom;
				$tpl_group_remise = $G->remise;
			}
		}

		$tpl_calculer_avoir_client_prix = null;
		$tpl_compter_nb_commandes_parrainees = null;
		$tpl_recuperer_parrain = null;
		if (is_parrainage_module_active()) {
			$tpl_calculer_avoir_client_prix = fprix(calculer_avoir_client($user['id_utilisateur']), true, $GLOBALS['site_parameters']['code'], false);
			$tpl_compter_nb_commandes_parrainees = compter_nb_commandes_parrainees($user['id_utilisateur']);
			$tpl_recuperer_parrain = recuperer_parrain($user['id_utilisateur']);
		}

		$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
			'id_utilisateur' => $user['id_utilisateur'],
			'email' => vb($user['email']),
			'drop_href' => get_current_url(false) . '?mode=suppr&id_utilisateur=' . $user['id_utilisateur'],
			'init_href' => get_current_url(false) . '?mode=init_mdp&email=' . $user['email'],
			'edit_href' => get_current_url(false) . '?mode=modif&id_utilisateur=' . $user['id_utilisateur'] . '&start=' . (isset($_GET['start']) ? $_GET['start'] : 0),
			'etat' => $user['etat'],
			'modif_etat_href' => get_current_url(false) . '?mode=modif_etat&id=' . $user['id_utilisateur'] . '&etat=' . $user['etat'],
			'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($user['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
			'profil_name' => $user['profil_name'],
			'code_client' => $user['code_client'],
			'pseudo' => $user['pseudo'],
			'annonces_count' => $tpl_annonces_count,
			'prenom' => $user['prenom'],
			'nom_famille' => $user['nom_famille'],
			'societe' => $user['societe'],
			'siret_length' => String::strlen($user['siret']),
			'siret' => $user['siret'],
			'code_postal' => $user['code_postal'],
			'ville' => $user['ville'],
			'country_name' => get_country_name($user['pays']),
			'phone_output' => implode(' / ', $phone_output_array),
			'group_nom' => $tpl_group_nom,
			'group_remise' => $tpl_group_remise,
			'date_insert' => get_formatted_date($user['date_insert'], 'short', true),
			'remise_percent' => round($user['remise_percent'], 2),
			'avoir_prix' => fprix($user['avoir'], true, $GLOBALS['site_parameters']['code'], false),
			'points' => $user['points'],
			'calculer_avoir_client_prix' => $tpl_calculer_avoir_client_prix,
			'compter_nb_commandes_parrainees' => $tpl_compter_nb_commandes_parrainees,
			'recuperer_parrain' => $tpl_recuperer_parrain
			);

		$i++;
	}
	$tpl->assign('results', $tpl_results);
}

$tpl->assign('email', vb($_GET['email']));
$tpl->assign('client_info', vb($_GET['client_info']));
$tpl->assign('societe', vb($_GET['societe']));
$tpl->assign('tel', vb($_GET['tel']));
$tpl->assign('date_insert_input1', vb($_GET['date_insert_input1']));
$tpl->assign('date_insert_input2', vb($_GET['date_insert_input2']));
$tpl->assign('date_last_paiement_input1', vb($_GET['date_last_paiement_input1']));
$tpl->assign('date_last_paiement_input2', vb($_GET['date_last_paiement_input2']));
$tpl->assign('date_statut_commande_input1', vb($_GET['date_statut_commande_input1']));
$tpl->assign('date_statut_commande_input2', vb($_GET['date_statut_commande_input2']));
$tpl->assign('etat', vb($_GET['etat']));
$tpl->assign('ads_count_input1', vb($_GET['ads_count_input1']));
$tpl->assign('ads_count_input2', vb($_GET['ads_count_input2']));
$tpl->assign('annonces_contiennent', vb($_GET['annonces_contiennent']));
$tpl->assign('date_contact_prevu_input1', vb($_GET['date_contact_prevu_input1']));
$tpl->assign('date_contact_prevu_input2', vb($_GET['date_contact_prevu_input2']));
$tpl->assign('date_derniere_connexion_input1', vb($_GET['date_derniere_connexion_input1']));
$tpl->assign('date_derniere_connexion_input2', vb($_GET['date_derniere_connexion_input2']));
$tpl->assign('with_gold_ad', vn($_GET['with_gold_ad']));
$tpl->assign('type', vb($_GET['type']));
$tpl->assign('fonction', vb($_GET['fonction']));
$tpl->assign('site_on', vb($_GET['site_on']));
$tpl->assign('is_crons_module_active', is_crons_module_active());
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_AND', $GLOBALS['STR_AND']);
$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
$tpl->assign('STR_BUYERS', $GLOBALS['STR_BUYERS']);
$tpl->assign('STR_IMPORTERS_EXPORTERS', $GLOBALS['STR_IMPORTERS_EXPORTERS']);
$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
$tpl->assign('STR_PURCHASING_MANAGER', $GLOBALS['STR_PURCHASING_MANAGER']);
$tpl->assign('STR_WORD_SELLERS', $GLOBALS['STR_WORD_SELLERS']);
$tpl->assign('STR_WHOLESALER', $GLOBALS['STR_WHOLESALER']);
$tpl->assign('STR_HALF_WHOLESALER', $GLOBALS['STR_HALF_WHOLESALER']);
$tpl->assign('STR_RETAILERS', $GLOBALS['STR_RETAILERS']);
$tpl->assign('STR_ADMIN_CHOOSE_SEARCH_CRITERIA', $GLOBALS['STR_ADMIN_CHOOSE_SEARCH_CRITERIA']);
$tpl->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
$tpl->assign('STR_COMPANY', $GLOBALS['STR_COMPANY']);
$tpl->assign('STR_SIREN', $GLOBALS['STR_SIREN']);
$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
$tpl->assign('STR_ADMIN_UTILISATEURS_PROFILE_TYPE', $GLOBALS['STR_ADMIN_UTILISATEURS_PROFILE_TYPE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_NEWSLETTER_SUBSCRIBER', $GLOBALS['STR_ADMIN_UTILISATEURS_NEWSLETTER_SUBSCRIBER']);
$tpl->assign('STR_ADMIN_UTILISATEURS_COMMERCIAL_OFFERS', $GLOBALS['STR_ADMIN_UTILISATEURS_COMMERCIAL_OFFERS']);
$tpl->assign('STR_ADMIN_UTILISATEURS_MANAGED_BY', $GLOBALS['STR_ADMIN_UTILISATEURS_MANAGED_BY']);
$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_MANDATORY_FOR_MULTIPLE_SEND', $GLOBALS['STR_ADMIN_UTILISATEURS_MANDATORY_FOR_MULTIPLE_SEND']);
$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
$tpl->assign('STR_ADMIN_UTILISATEURS_WHO', $GLOBALS['STR_ADMIN_UTILISATEURS_WHO']);
$tpl->assign('STR_TYPE', $GLOBALS['STR_TYPE']);
$tpl->assign('STR_FONCTION', $GLOBALS['STR_FONCTION']);
$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
$tpl->assign('STR_BUYERS', $GLOBALS['STR_BUYERS']);
$tpl->assign('STR_IMPORTERS_EXPORTERS', $GLOBALS['STR_IMPORTERS_EXPORTERS']);
$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
$tpl->assign('STR_PURCHASING_MANAGER', $GLOBALS['STR_PURCHASING_MANAGER']);
$tpl->assign('STR_WORD_SELLERS', $GLOBALS['STR_WORD_SELLERS']);
$tpl->assign('STR_WHOLESALER', $GLOBALS['STR_WHOLESALER']);
$tpl->assign('STR_HALF_WHOLESALER', $GLOBALS['STR_HALF_WHOLESALER']);
$tpl->assign('STR_RETAILERS', $GLOBALS['STR_RETAILERS']);
$tpl->assign('STR_LEADER', $GLOBALS['STR_LEADER']);
$tpl->assign('STR_MANAGER', $GLOBALS['STR_MANAGER']);
$tpl->assign('STR_EMPLOYEE', $GLOBALS['STR_EMPLOYEE']);
$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
$tpl->assign('STR_ADMIN_UTILISATEURS_BUY', $GLOBALS['STR_ADMIN_UTILISATEURS_BUY']);
$tpl->assign('STR_ADMIN_UTILISATEURS_WANTS', $GLOBALS['STR_ADMIN_UTILISATEURS_WANTS']);
$tpl->assign('STR_ADMIN_UTILISATEURS_THINKS', $GLOBALS['STR_ADMIN_UTILISATEURS_THINKS']);
$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_BY', $GLOBALS['STR_ADMIN_UTILISATEURS_FOLLOWED_BY']);
$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
$tpl->assign('STR_ADMIN_UTILISATEURS_ORDER_STATUS_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_ORDER_STATUS_DATE']);
$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
$tpl->assign('STR_ADMIN_CHECK_ALL', $GLOBALS['STR_ADMIN_CHECK_ALL']);
$tpl->assign('STR_ADMIN_UNCHECK_ALL', $GLOBALS['STR_ADMIN_UNCHECK_ALL']);
$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_EMAIL_TO_SELECTED_USERS', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_EMAIL_TO_SELECTED_USERS']);
$tpl->assign('STR_ADMIN_UTILISATEURS_REGISTRATION_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_REGISTRATION_DATE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_PAYMENT_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_PAYMENT_DATE']);
$tpl->assign('STR_ADMIN_DATE_BETWEEN_AND', $GLOBALS['STR_ADMIN_DATE_BETWEEN_AND']);
$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_PAYMENT_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_PAYMENT_DATE']);
$tpl->assign('STR_ORIGIN', $GLOBALS['STR_ORIGIN']);
$tpl->assign('STR_ADMIN_UTILISATEURS_SUBSCRIBER', $GLOBALS['STR_ADMIN_UTILISATEURS_SUBSCRIBER']);
$tpl->assign('STR_ADMIN_UTILISATEURS_PRODUCT_BOUGHT_AND_QUANTITY', $GLOBALS['STR_ADMIN_UTILISATEURS_PRODUCT_BOUGHT_AND_QUANTITY']);
if (is_annonce_module_active()) {
	$tpl->assign('STR_MODULE_ANNONCES_ADMIN_USER_WITH_GOLD', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_USER_WITH_GOLD']);
	$tpl->assign('STR_MODULE_ANNONCES_ADMIN_USER_ADS_COUNT', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_USER_ADS_COUNT']);
	$tpl->assign('STR_MODULE_ANNONCES_ADMIN_ADS_AVAILABLE', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_ADS_AVAILABLE']);
	$tpl->assign('STR_MODULE_ANNONCES_ADMIN_ADS_CONTAIN', $GLOBALS['STR_MODULE_ANNONCES_ADMIN_ADS_CONTAIN']);
	$tpl->assign('STR_MODULE_ANNONCES_AD', $GLOBALS['STR_MODULE_ANNONCES_AD']);
}
$tpl->assign('STR_ADMIN_UTILISATEURS_CONTACT_FORECASTED_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_CONTACT_FORECASTED_DATE']);
$tpl->assign('STR_ADMIN_DATE_BETWEEN_AND', $GLOBALS['STR_ADMIN_DATE_BETWEEN_AND']);
$tpl->assign('STR_ADMIN_REASON', $GLOBALS['STR_ADMIN_REASON']);
$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_LOGIN_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_LOGIN_DATE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_USERS_COUNT', $GLOBALS['STR_ADMIN_UTILISATEURS_USERS_COUNT']);
$tpl->assign('STR_ADMIN_UTILISATEURS_LIST_EXPLAIN', $GLOBALS['STR_ADMIN_UTILISATEURS_LIST_EXPLAIN']);
$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD']);
$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE', $GLOBALS['STR_ADMIN_UTILISATEURS_CREATE']);
$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
$tpl->assign('STR_ADMIN_UTILISATEURS_EXCEL_EXPORT', $GLOBALS['STR_ADMIN_UTILISATEURS_EXCEL_EXPORT']);
$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD_CONFIRM', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD_CONFIRM']);
$tpl->assign('STR_ADMIN_UTILISATEURS_UPDATE', $GLOBALS['STR_ADMIN_UTILISATEURS_UPDATE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_DEACTIVATE_USER', $GLOBALS['STR_ADMIN_UTILISATEURS_DEACTIVATE_USER']);
$tpl->assign('STR_ADMIN_UTILISATEURS_UPDATE_STATUS', $GLOBALS['STR_ADMIN_UTILISATEURS_UPDATE_STATUS']);
$tpl->assign('STR_NUMBER', $GLOBALS['STR_NUMBER']);
$tpl->assign('STR_SIRET', $GLOBALS['STR_SIRET']);
$tpl->assign('STR_NONE', $GLOBALS['STR_NONE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_NO_SUPPLIER_FOUND', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_SUPPLIER_FOUND']);
$tpl->assign('STR_ADMIN_UTILISATEURS_FILER_EXPLAIN', $GLOBALS['STR_ADMIN_UTILISATEURS_FILER_EXPLAIN']);
$tpl->assign('STR_ORDER_FORM', $GLOBALS['STR_ORDER_FORM']);
$tpl->assign('STR_ADMIN_UTILISATEURS_GIFT_CHECK', $GLOBALS['STR_ADMIN_UTILISATEURS_GIFT_CHECK']);

if (is_crons_module_active() && is_webmail_module_active()) {
	$tpl->assign('send_email_all_form', get_send_email_all_form($Links, $sql));
}

echo $tpl->fetch();

?>
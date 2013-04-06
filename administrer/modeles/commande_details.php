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
// $Id: commande_details.php 36261 2013-04-06 11:18:12Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

$is_order_modification_allowed = is_order_modification_allowed(vb($commande['o_timestamp']));

if (!empty($user_id)) {
	// Dans le cas ou l'on crée une commande, on initialise à partir des donnée de l'utilisateur. Sinon on recupère les informations de l'utilsateur par la commande
	$user_array = get_user_information($user_id);
	// Répétition pour les différente adresse de l'utilisateur
	for($i = 0;$i < 2;$i++) {
		if ($i == 0) {
			$state = 'bill';
		} else {
			$state = 'ship';
		}
		$commande['societe_' . $state] = vb($user_array['societe']);
		$commande['nom_' . $state] = vb($user_array['nom_famille']);
		$commande['prenom_' . $state] = vb($user_array['prenom']);
		$commande['email_' . $state] = vb($user_array['email']);
		$commande['telephone_' . $state] = vb($user_array['telephone']);
		$commande['adresse_' . $state] = vb($user_array['adresse']);
		$commande['zip_' . $state] = vb($user_array['code_postal']);
		$commande['ville_' . $state] = vb($user_array['ville']);
		$commande['pays_' . $state] = vn(get_country_name($user_array['pays']));
	}
	$commande['id_utilisateur'] = vn($user_id);
	$commande['intracom_for_billing'] = vb($user_array['intracom_for_billing']);
	// La TVA est-elle applicable pour cet utilisateur.
	$sqlPays = 'SELECT p.id, p.pays_' . $_SESSION['session_langue'] . ' as pays, p.zone, z.tva, z.on_franco
		FROM peel_pays p
		LEFT JOIN peel_zones z ON z.id=p.zone
		WHERE p.etat = "1" AND p.id ="' . nohtml_real_escape_string($user_array['pays']) . '"
		LIMIT 1';
	$query = query($sqlPays);
	if ($result = fetch_assoc($query)) {
		$user_vat = $result['tva'];
	} else {
		$user_vat = 1;
	}
	$commande['zone_tva'] = ($user_vat && !is_user_tva_intracom_for_no_vat($user_id) && !is_micro_entreprise_module_active());
} elseif (!empty($id)) {
	$commande['payment_technical_code'] = vb($commande['paiement']);
	if (strpos($commande['paiement'], ' ') !== false) {
		// ADAPTATION POUR TABLES ANCIENNES avec paiement qui contient nom et pas technical_code
		$sql = 'SELECT technical_code
			FROM peel_paiement
			WHERE nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($commande['paiement']) . '"
			LIMIT 1';
		$query = query($sql);
		if ($result = fetch_assoc($query)) {
			$commande['payment_technical_code'] = $result['technical_code'];
		}
	}
	if ($commande['cout_transport_ht'] > 0) {
		$commande['tva_transport'] = vn(round(($commande['tva_cout_transport'] / $commande['cout_transport_ht'] * 100), 2));
	} else {
		$commande['tva_transport'] = null;
	}
} else {
	// Nouvelle commande : valeurs par défaut
	$commande['pays_bill'] = vb(get_country_name(vn($GLOBALS['site_parameters']['default_country_id'])));
	$commande['pays_ship'] = vb(get_country_name(vn($GLOBALS['site_parameters']['default_country_id'])));
	$commande['zone_tva'] = 1;
}
if (!empty($commande['numero'])) {
	// On reprend le numéro de la BDD, et on va pouvoir l'éditer si on veut
	$numero = $commande['numero'];
} elseif (!empty($GLOBALS['site_parameters']['admin_fill_empty_bill_number_by_number_format'])) {
	$numero = vb($GLOBALS['site_parameters']['format_numero_facture']);
} else {
	$numero = null;
}
if (empty($commande['devise'])) {
	$commande['devise'] = $GLOBALS['site_parameters']['code'];
}
$tpl = $GLOBALS['tplEngine']->createTemplate('admin_commande_details.tpl');
$tpl->assign('action_name', $action);
$tpl->assign('id', vn($id));
$tpl->assign('is_order_modification_allowed', $is_order_modification_allowed);

$tpl->assign('pdf_src', $GLOBALS['wwwroot_in_admin'] . '/images/view_pdf.gif');
if ($action != "insere" && $action != "ajout") {
	$tpl->assign('facture_pdf_href', $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=facture');
	$tpl->assign('sendfacture_pdf_href', $GLOBALS['administrer_url'] . '/commander.php?mode=sendfacturepdf&id=' . vn($commande['id']) . '&code_facture=' . vb($commande['code_facture']) . '&bill_type=facture');
	$tpl->assign('proforma_pdf_href', $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=proforma');
	$tpl->assign('sendproforma_pdf_href', $GLOBALS['administrer_url'] . '/commander.php?mode=sendfacturepdf&id=' . vn($commande['id']) . '&code_facture=' . vb($commande['code_facture']) . '&bill_type=proforma');
	$tpl->assign('devis_pdf_href', $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=devis');
	$tpl->assign('senddevis_pdf_href', $GLOBALS['administrer_url'] . '/commander.php?mode=sendfacturepdf&id=' . vn($commande['id']) . '&code_facture=' . vb($commande['code_facture']) . '&bill_type=devis');
	$tpl->assign('bdc_pdf_href', $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=bdc');

	$tpl->assign('is_module_factures_html_active', is_module_factures_html_active());
	if (is_module_factures_html_active()) {
		$tpl->assign('facture_html_href', $GLOBALS['wwwroot'] . '/modules/factures/commande_html.php?code_facture=' . vb($commande['code_facture']) . '&mode=facture');
		$tpl->assign('bdc_action', $GLOBALS['administrer_url'] . '/commander.php?mode=modif&commandeid=' . vn($commande['id']));
		$tpl->assign('bdc_code_facture', vb($commande['code_facture']));
		$tpl->assign('bdc_id', vn($commande['id']));
		$tpl->assign('bdc_partial', fprix(vn($commande['montant']), false, vb($commande['devise']), true, vn($commande['currency_rate']), false, false, ',', false, true));
		$tpl->assign('bdc_devise', vb($commande['devise']));
		$tpl->assign('partial_amount_link_js', $GLOBALS['wwwroot'] . '/modules/factures/commande_html.php?code_facture=' . vb($commande['code_facture']) . '&mode=bdc&currency_rate=' . vn($commande['currency_rate']) . '&partial=');
		$tpl->assign('partial_amount_link_href', $GLOBALS['wwwroot'] . '/modules/factures/commande_html.php?code_facture=' . vb($commande['code_facture']) . '&mode=bdc&partial=' .get_float_from_user_input(fprix(vn($commande['montant']), false, $GLOBALS['site_parameters']['code'], false, $commande['currency_rate'], false, false)) );
		$tpl->assign('partial_amount_link_target', 'facture' . $commande['code_facture']);
	}
	if (is_tnt_module_active()) {
		$q_type = query('SELECT * 
			FROM peel_types 
			WHERE is_tnt="1" AND nom_' . $commande['lang'] . ' = "' . nohtml_real_escape_string($commande['type']) . '"');
		$result = fetch_assoc($q_type);
		if (!empty($result)) {
			$tpl->assign('etiquette_tnt', '<b>ETIQUETTE TNT : </b><a target="_blank" href="' . $GLOBALS['wwwroot'] . '/modules/tnt/administrer/etiquette.php?order_id='.$commande['id'] .'">Imprimer l\'étiquette tnt (ouvre une nouvelle fenêtre)</a>');
		}
	}
	$tpl->assign('action', get_current_url(false) . '?mode=modif&commandeid=' . vn($id));
	$tpl->assign('ecom_nom', $GLOBALS['site']);
	$tpl->assign('date_facture', (empty($date_facture) ? "" : vb($date_facture)));
	$tpl->assign('intracom_for_billing', vb($commande['intracom_for_billing']));
	$tpl->assign('commande_date', vb(get_formatted_date(vb($commande['o_timestamp']))));
	$tpl->assign('email_href', $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . vn($commande['id_utilisateur']));
	$tpl->assign('email', vb($commande['email']));
} else {
	$tpl->assign('action', get_current_url(false) . '?mode=modif&commandeid=' . vn($id));
}

$tpl->assign('numero', $numero);
$tpl->assign('delivery_tracking', vb($commande['delivery_tracking']));
$tpl->assign('is_icirelais_module_active', is_icirelais_module_active());
$tpl->assign('is_tnt_module_active', is_tnt_module_active());
if (is_icirelais_module_active()) {
	$tpl->assign('STR_MODULE_ICIRELAIS_TRACKING_URL', STR_MODULE_ICIRELAIS_TRACKING_URL);
	$tpl->assign('STR_MODULE_ICIRELAIS_COMMENT_TRACKING', STR_MODULE_ICIRELAIS_COMMENT_TRACKING);
	$tpl->assign('STR_MODULE_ICIRELAIS_ERROR_TRACKING', STR_MODULE_ICIRELAIS_ERROR_TRACKING);
	$tpl->assign('STR_MODULE_ICIRELAIS_CREATE_TRACKING', STR_MODULE_ICIRELAIS_CREATE_TRACKING);
}

if((!empty($id) && $commande['montant'] > 0) || empty($id)) {
	$tpl->assign('payment_select', get_payment_select(vb($commande['payment_technical_code'])));
}

$tpl->assign('payment_status_options', vb(get_payment_status_options(vn($commande['id_statut_paiement']))));
$tpl->assign('delivery_status_options', vb(get_delivery_status_options(vn($commande['id_statut_livraison']))));

$tpl->assign('devise', vb($commande['devise']));
$tpl->assign('mode_transport', vn($GLOBALS['site_parameters']['mode_transport']));
if (!empty($GLOBALS['site_parameters']['mode_transport'])) {
	$tpl->assign('delivery_type_options', get_delivery_type_options(vb($commande['type'])));
	$tpl->assign('vat_select_options', get_vat_select_options(vb($commande['tva_transport']), true));
} else {
	$tpl->assign('tva_transport', vb($commande['tva_transport']));
	$tpl->assign('type_transport', vb($commande['type_transport']));
}
$tpl->assign('cout_transport', fprix(vn($commande['cout_transport']), false, vb($commande['devise']), true, vn($commande['currency_rate']), false));
$tpl->assign('tva_transport', fprix(vn($commande['tva_transport']), false, vb($commande['devise']), true, vn($commande['currency_rate']), false));
$tpl->assign('transport', vb($commande['transport']));

$tpl->assign('is_devises_module_active', is_devises_module_active());
if (is_devises_module_active()) {
	$tpl_devises_options = array();
	$res_devise = query("SELECT p.code
		FROM peel_devises p
		WHERE etat='1'");
	while ($tab_devise = fetch_assoc($res_devise)) {
		$tpl_devises_options[] = array('value' => $tab_devise['code'],
			'issel' => $tab_devise['code'] == vb($commande['devise']),
			'name' => $tab_devise['code']
			);
	}
	$tpl->assign('devises_options', $tpl_devises_options);
}

$tpl->assign('small_order_overcost_amount', fprix(vn($commande['small_order_overcost_amount']), false, vb($commande['devise']), true, vn($commande['currency_rate']), false));
$tpl->assign('tva_small_order_overcost', fprix(vn($commande['tva_small_order_overcost']), false, vb($commande['devise']), true, vn($commande['currency_rate']), false));
$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
$tpl->assign('currency_rate', vn($commande['currency_rate']));
$tpl->assign('montant_displayed_prix', fprix($montant_displayed, true, vb($commande['devise']), true, vn($commande['currency_rate'])));
$tpl->assign('ttc_ht', (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));

if (!empty($commande['total_remise']) && $commande['total_remise'] > 0) {
	$tpl->assign('total_remise_prix', fprix((display_prices_with_taxes_in_admin()?$commande['total_remise']:$commande['total_remise_ht']), true, vb($commande['devise']), true, vn($commande['currency_rate'])));
}
$tpl->assign('avoir_prix', fprix(vn($commande['avoir']), false, vb($commande['devise']), true, vn($commande['currency_rate'])));

if (!empty($commande['affilie']) && $commande['affilie'] == 1) {
	$affiliated_user = get_user_information($commande['id_affilie']);
	$tpl->assign('is_affilie', true);
	$tpl->assign('affilie_prix', fprix($commande['montant_affilie'], true, vb($commande['devise']), true, vn($commande['currency_rate'])));
	$tpl->assign('statut_affilie', $commande['statut_affilie']);
	$tpl->assign('affilie_href', $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $affiliated_user['id_utilisateur']);
	$tpl->assign('affilie_email', $affiliated_user['email']);
} else {
	$tpl->assign('is_affilie', false);
}

$tpl->assign('total_points', vn($commande['total_points']));
$tpl->assign('points_etat', vn($commande['points_etat']));
$tpl->assign('commentaires', vb($commande['commentaires']));

$tpl_client_infos = array();
for ($i = 1; $i < 3; $i++) {
	if ($i == 1) {
		$value = 'bill';
	} else {
		$value = 'ship';
	}
	$tpl_client_infos[] = array('value' => $value,
		'i' => $i,
		'societe' => vb($commande['societe_' . $value]),
		'nom' => vb($commande['nom_' . $value]),
		'prenom' => vb($commande['prenom_' . $value]),
		'email' => vb($commande['email_' . $value]),
		'telephone' => vb($commande['telephone_' . $value]),
		'adresse' => vb($commande['adresse_' . $value]),
		'zip' => vb($commande['zip_' . $value]),
		'ville' => vb($commande['ville_' . $value]),
		'country_select_options' => get_country_select_options(vb($commande['pays_' . $value], null, 'name', false, null, true, vb($commande['lang'])))
		);
}
$tpl->assign('client_infos', $tpl_client_infos);

$tpl_order_lines = array();
if (!empty($id)) {
	if(!empty($GLOBALS['site_parameters']['order_article_order_by']) && $GLOBALS['site_parameters']['order_article_order_by'] == 'name') {
		$order_by = 'oi.nom_produit ASC';
	} else {
		$order_by = 'oi.id ASC';
	}
	$result_requete = query("SELECT
		oi.reference AS ref
		, oi.nom_produit AS nom
		, oi.prix AS purchase_prix
		, oi.prix_ht AS purchase_prix_ht
		, oi.prix_cat
		, oi.prix_cat_ht
		, oi.quantite
		, oi.tva
		, oi.tva_percent
		, oi.produit_id AS id
		, oi.nom_attribut
		, oi.total_prix_attribut
		, oi.couleur
		, oi.taille
		, oi.couleur_id
		, oi.taille_id
		, oi.remise
		, oi.remise_ht
		, oi.percent_remise_produit AS percent
		, oi.on_download
	FROM peel_commandes_articles oi
	WHERE commande_id = '" . intval($id) . "'
	ORDER BY ".$order_by);
	$nb_produits = num_rows($result_requete);
} else {
	$nb_produits = 0;
}
$i = 1;
if (!empty($result_requete)) {
	while ($line_data = fetch_assoc($result_requete)) {
		$product_object = new Product($line_data['id'], null, false, null, true, !is_micro_entreprise_module_active());
		// Code pour recupérer select des tailles
		$possible_sizes = $product_object->get_possible_sizes();
		// traitement particulier pour le prix. L'utilisation de la fonction vb() n'est pas approprié car il faut permettre l'insertion de produit au montant égal à zero (pour offir.)
		$line_data['prix_cat'] = round($line_data['prix_cat'] * vn($commande['currency_rate']), 5);
		$line_data['prix_cat_ht'] = round($line_data['prix_cat_ht'] * vn($commande['currency_rate']), 5);
		$line_data['purchase_prix'] = round($line_data['purchase_prix'] * vn($commande['currency_rate']), 5);
		$line_data['purchase_prix_ht'] = round($line_data['purchase_prix_ht'] * vn($commande['currency_rate']), 5);
		$line_data['remise'] = round($line_data['remise'] * vn($commande['currency_rate']), 5);
		$line_data['remise_ht'] = round($line_data['remise_ht'] * vn($commande['currency_rate']), 5);
		if (!empty($line_data['taille']) && !in_array($line_data['taille'], $possible_sizes)) {
			$possible_sizes[$line_data['taille']] = $line_data['taille'];
		}
		$size_options_html = '';
		if (!empty($possible_sizes)) {
			foreach ($possible_sizes as $this_size_id => $this_size_name) {
				$size_options_html .= '<option value="' . intval($this_size_id) . '" ' . frmvalide($this_size_name == $line_data['taille'], ' selected="selected"') . '>' . $this_size_name . '</option>';
			}
		}
		$possible_colors = $product_object->get_possible_colors();
		if (!empty($line_data['couleur']) && !in_array($line_data['couleur'], $possible_colors)) {
			$possible_colors[$line_data['couleur']] = $line_data['couleur'];
		}
		$color_options_html = '';
		if (!empty($possible_colors)) {
			foreach ($possible_colors as $this_color_id => $this_color_name) {
				$color_options_html .= '<option value="' . intval($this_color_id) . '" ' . frmvalide($this_color_name == $line_data['couleur'], ' selected="selected"') . '>' . $this_color_name . '</option>';
			}
		}
		$tva_options_html = get_vat_select_options($line_data['tva_percent']);
		// print_r($line_data); die();
		$tpl_order_lines[] = get_order_line($line_data, $color_options_html, $size_options_html, $tva_options_html, $i);
		$i++;
		unset($product_object);
	}
}
$tpl->assign('order_lines', $tpl_order_lines);

$tpl->assign('avoir', fprix(vn($commande['avoir']), false, vb($commande['devise']), true, vn($commande['currency_rate']), false));
$tpl->assign('code_promo', vb($commande['code_promo']));
$tpl->assign('percent_code_promo', vn($commande['percent_code_promo']));
$tpl->assign('valeur_code_promo', vn($commande['valeur_code_promo']));

$tpl->assign('form_token', get_form_token_input('commander.php?mode=' . $action . '&commandeid=' . $id));
$tpl->assign('id_utilisateur', vb($commande['id_utilisateur']));
$tpl->assign('nb_produits', $nb_produits);

$tpl->assign('get_mode', $_GET['mode']);

$tpl->assign('order_line_js', get_order_line(array('id' => '[id]', 'ref' => '[ref]', 'nom' => '[nom]', 'quantite' => '[quantite]', 'remise' => '[remise]', 'remise_ht' => '[remise_ht]', 'percent' => '[percent]', 'purchase_prix' => '[purchase_prix]', 'purchase_prix_ht' => '[purchase_prix_ht]', 'tva_percent' => '[tva_percent]', 'prix_cat' => '[prix_cat]', 'prix_cat_ht' => '[prix_cat_ht]'), '[color_options_html]', '[size_options_html]', '[tva_options_html]', '[i]'), true, true, false);

$tpl->assign('site_avoir', $GLOBALS['site_parameters']['avoir']);
if (is_parrainage_module_active()) {
	// Si le client a été parrainé
	if (vb($commande['parrain']) == "parrain") {
		$Client = get_user_information($commande['id_parrain']);
		$tpl->assign('parrainage_form', array('action' => get_current_url(false),
				'id' => intval($commande['id']),
				'id_parrain' => intval($commande['id_parrain']),
				'email' => $Client['email'],
				'href' => $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $commande['id_parrain']
				));
	}
}
$tpl->assign('is_fianet_sac_module_active', is_fianet_sac_module_active());
if(is_fianet_sac_module_active()) {
	require_once($GLOBALS['fonctionsfianet_sac']);
	$tpl->assign('fianet_analyse_commandes', get_sac_order_link($id));
}
$tpl->assign('is_order_modification_allowed', $is_order_modification_allowed);
$tpl->assign('zone_tva', vb($commande['zone_tva']));
$tpl->assign('default_vat_select_options', get_vat_select_options('19.6'));
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_COMMANDER_WARNING_EDITION_NOT_ALLOWED', $GLOBALS['STR_ADMIN_COMMANDER_WARNING_EDITION_NOT_ALLOWED']);
$tpl->assign('STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE', $GLOBALS['STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE']);
$tpl->assign('STR_INVOICE', $GLOBALS['STR_INVOICE']);
$tpl->assign('STR_PROFORMA', $GLOBALS['STR_PROFORMA']);
$tpl->assign('STR_QUOTATION', $GLOBALS['STR_QUOTATION']);
$tpl->assign('STR_ORDER_FORM', $GLOBALS['STR_ORDER_FORM']);
$tpl->assign('STR_ADMIN_SEND_TO_CLIENT_BY_EMAIL', $GLOBALS['STR_ADMIN_SEND_TO_CLIENT_BY_EMAIL']);
$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
$tpl->assign('STR_BY', $GLOBALS['STR_BY']);
$tpl->assign('STR_ORDER_STATUT_PAIEMENT', $GLOBALS['STR_ORDER_STATUT_PAIEMENT']);
$tpl->assign('STR_ORDER_STATUT_LIVRAISON', $GLOBALS['STR_ORDER_STATUT_LIVRAISON']);
$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
$tpl->assign('STR_ADMIN_INCLUDING_VAT', $GLOBALS['STR_ADMIN_INCLUDING_VAT']);
$tpl->assign('STR_ADMIN_USED_CURRENCY', $GLOBALS['STR_ADMIN_USED_CURRENCY']);
$tpl->assign('STR_COMMENTS', $GLOBALS['STR_COMMENTS']);
$tpl->assign('STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH', $GLOBALS['STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH']);
$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
$tpl->assign('STR_ADMIN_ADD_EMPTY_LINE', $GLOBALS['STR_ADMIN_ADD_EMPTY_LINE']);
$tpl->assign('STR_PAYMENT_MEAN', $GLOBALS['STR_PAYMENT_MEAN']);
$tpl->assign('STR_SHIPPING_TYPE', $GLOBALS['STR_SHIPPING_TYPE']);
$tpl->assign('STR_SHIPPING_COST', $GLOBALS['STR_SHIPPING_COST']);
$tpl->assign('STR_GIFT_POINTS', $GLOBALS['STR_GIFT_POINTS']);
$tpl->assign('STR_INVOICE_ADDRESS', $GLOBALS['STR_INVOICE_ADDRESS']);
$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
$tpl->assign('STR_SOCIETE', $GLOBALS['STR_SOCIETE']);
$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDERED_PRODUCTS_LIST', $GLOBALS['STR_ADMIN_COMMANDER_ORDERED_PRODUCTS_LIST']);
$tpl->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
$tpl->assign('STR_REFERENCE', $GLOBALS['STR_REFERENCE']);
$tpl->assign('STR_SIZE', $GLOBALS['STR_SIZE']);
$tpl->assign('STR_COLOR', $GLOBALS['STR_COLOR']);
$tpl->assign('STR_QUANTITY_SHORT', $GLOBALS['STR_QUANTITY_SHORT']);
$tpl->assign('STR_ADMIN_COMMANDER_PRODUCT_LISTED_PRICE', $GLOBALS['STR_ADMIN_COMMANDER_PRODUCT_LISTED_PRICE']);
$tpl->assign('STR_REMISE', $GLOBALS['STR_REMISE']);
$tpl->assign('STR_UNIT_PRICE', $GLOBALS['STR_UNIT_PRICE']);
$tpl->assign('STR_ADMIN_CUSTOM_ATTRIBUTES', $GLOBALS['STR_ADMIN_CUSTOM_ATTRIBUTES']);
$tpl->assign('STR_ADMIN_VAT_PERCENTAGE', $GLOBALS['STR_ADMIN_VAT_PERCENTAGE']);
$tpl->assign('STR_ADMIN_COMMANDER_ADD_PRODUCTS_TO_ORDER', $GLOBALS['STR_ADMIN_COMMANDER_ADD_PRODUCTS_TO_ORDER']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDER_EMITTED_BY_GODCHILD', $GLOBALS['STR_ADMIN_COMMANDER_ORDER_EMITTED_BY_GODCHILD']);
$tpl->assign('STR_ADMIN_COMMANDER_THANK_SPONSOR_WITH_CREDIT_OF', $GLOBALS['STR_ADMIN_COMMANDER_THANK_SPONSOR_WITH_CREDIT_OF']);
$tpl->assign('STR_ADMIN_COMMANDER_THANK_SPONSOR_WITH_CREDIT_EXPLAIN', $GLOBALS['STR_ADMIN_COMMANDER_THANK_SPONSOR_WITH_CREDIT_EXPLAIN']);
$tpl->assign('STR_ADMIN_COMMANDER_GIVE_CREDIT', $GLOBALS['STR_ADMIN_COMMANDER_GIVE_CREDIT']);
$tpl->assign('STR_ADMIN_COMMANDER_SHIPPING_ADDRESS', $GLOBALS['STR_ADMIN_COMMANDER_SHIPPING_ADDRESS']);
$tpl->assign('STR_ADMIN_COMMANDER_MSG_PURCHASE_ORDER_SENT_BY_EMAIL_OK', $GLOBALS['STR_ADMIN_COMMANDER_MSG_PURCHASE_ORDER_SENT_BY_EMAIL_OK']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDER_UPDATED', $GLOBALS['STR_ADMIN_COMMANDER_ORDER_UPDATED']);
$tpl->assign('STR_ADMIN_COMMANDER_AND_STOCKS_UPDATED', $GLOBALS['STR_ADMIN_COMMANDER_AND_STOCKS_UPDATED']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDER_CREATED', $GLOBALS['STR_ADMIN_COMMANDER_ORDER_CREATED']);
$tpl->assign('STR_ADMIN_COMMANDER_LINK_ORDER_SUMMARY', $GLOBALS['STR_ADMIN_COMMANDER_LINK_ORDER_SUMMARY']);
$tpl->assign('STR_ADMIN_COMMANDER_AFFILIATION_MODULE_MISSING', $GLOBALS['STR_ADMIN_COMMANDER_AFFILIATION_MODULE_MISSING']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDER_STATUS_UPDATED', $GLOBALS['STR_ADMIN_COMMANDER_ORDER_STATUS_UPDATED']);
$tpl->assign('STR_ADMIN_COMMANDER_MSG_AVOIR_SENT_BY_EMAIL_OK', $GLOBALS['STR_ADMIN_COMMANDER_MSG_AVOIR_SENT_BY_EMAIL_OK']);
$tpl->assign('STR_ADMIN_COMMANDER_WARNING_EDITION_NOT_ALLOWED', $GLOBALS['STR_ADMIN_COMMANDER_WARNING_EDITION_NOT_ALLOWED']);
$tpl->assign('STR_ADMIN_COMMANDER_OPEN_IN_BROWSER', $GLOBALS['STR_ADMIN_COMMANDER_OPEN_IN_BROWSER']);
$tpl->assign('STR_ADMIN_COMMANDER_SEND_BY_EMAIL_CONFIRM', $GLOBALS['STR_ADMIN_COMMANDER_SEND_BY_EMAIL_CONFIRM']);
$tpl->assign('STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE', $GLOBALS['STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE']);
$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL']);
$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL_CONFIRM', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL_CONFIRM']);
$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL']);
$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL_CONFIRM', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL_CONFIRM']);
$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL']);
$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL_CONFIRM', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL_CONFIRM']);
$tpl->assign('STR_ADMIN_COMMANDER_WITH_PARTIAL_AMOUNT', $GLOBALS['STR_ADMIN_COMMANDER_WITH_PARTIAL_AMOUNT']);
$tpl->assign('STR_ADMIN_COMMANDER_FIANET_FUNCTIONS', $GLOBALS['STR_ADMIN_COMMANDER_FIANET_FUNCTIONS']);
$tpl->assign('STR_ADMIN_COMMANDER_INFORMATION_ON_THIS_ORDER', $GLOBALS['STR_ADMIN_COMMANDER_INFORMATION_ON_THIS_ORDER']);
$tpl->assign('STR_ORDER_NUMBER', $GLOBALS['STR_ORDER_NUMBER']);
$tpl->assign('STR_ADMIN_COMMANDER_PAYMENT_DATE', $GLOBALS['STR_ADMIN_COMMANDER_PAYMENT_DATE']);
$tpl->assign('STR_ADMIN_COMMANDER_VAT_INTRACOM', $GLOBALS['STR_ADMIN_COMMANDER_VAT_INTRACOM']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDER_DATE', $GLOBALS['STR_ADMIN_COMMANDER_ORDER_DATE']);
$tpl->assign('STR_ADMIN_COMMANDER_BILL_NUMBER', $GLOBALS['STR_ADMIN_COMMANDER_BILL_NUMBER']);
$tpl->assign('STR_ADMIN_COMMANDER_BILL_NUMBER_EXPLAIN', $GLOBALS['STR_ADMIN_COMMANDER_BILL_NUMBER_EXPLAIN']);
$tpl->assign('STR_ADMIN_COMMANDER_TRACKING_NUMBER', $GLOBALS['STR_ADMIN_COMMANDER_TRACKING_NUMBER']);
$tpl->assign('STR_ADMIN_COMMANDER_PAYMENT_MEAN_EXPLAIN', $GLOBALS['STR_ADMIN_COMMANDER_PAYMENT_MEAN_EXPLAIN']);
$tpl->assign('STR_ADMIN_COMMANDER_SHIPPING_COST_EXPLAIN', $GLOBALS['STR_ADMIN_COMMANDER_SHIPPING_COST_EXPLAIN']);
$tpl->assign('STR_ADMIN_COMMANDER_SMALL_ORDERS_OVERCOST', $GLOBALS['STR_ADMIN_COMMANDER_SMALL_ORDERS_OVERCOST']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDER_TOTAL', $GLOBALS['STR_ADMIN_COMMANDER_ORDER_TOTAL']);
$tpl->assign('STR_ADMIN_COMMANDER_INCLUDING_DISCOUNT', $GLOBALS['STR_ADMIN_COMMANDER_INCLUDING_DISCOUNT']);
$tpl->assign('STR_ADMIN_COMMANDER_COUPON_USED', $GLOBALS['STR_ADMIN_COMMANDER_COUPON_USED']);
$tpl->assign('STR_ADMIN_COMMANDER_INCLUDING_CREDIT_NOTE', $GLOBALS['STR_ADMIN_COMMANDER_INCLUDING_CREDIT_NOTE']);
$tpl->assign('STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION', $GLOBALS['STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION']);
$tpl->assign('STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS', $GLOBALS['STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS']);
$tpl->assign('STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS_TO_COME', $GLOBALS['STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS_TO_COME']);
$tpl->assign('STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS_DONE', $GLOBALS['STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS_DONE']);
$tpl->assign('STR_ADMIN_COMMANDER_AFFILIATE_RELATED_TO_ORDER', $GLOBALS['STR_ADMIN_COMMANDER_AFFILIATE_RELATED_TO_ORDER']);
$tpl->assign('STR_ADMIN_COMMANDER_GIFT_POINTS', $GLOBALS['STR_ADMIN_COMMANDER_GIFT_POINTS']);
$tpl->assign('STR_ADMIN_COMMANDER_NOT_ATTRIBUTED', $GLOBALS['STR_ADMIN_COMMANDER_NOT_ATTRIBUTED']);
$tpl->assign('STR_ADMIN_COMMANDER_ATTRIBUTED', $GLOBALS['STR_ADMIN_COMMANDER_ATTRIBUTED']);
$tpl->assign('STR_ADMIN_COMMANDER_CANCELED', $GLOBALS['STR_ADMIN_COMMANDER_CANCELED']);
$tpl->assign('STR_ADMIN_COMMANDER_CLIENT_INFORMATION', $GLOBALS['STR_ADMIN_COMMANDER_CLIENT_INFORMATION']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDER_AUTHOR_EMAIL', $GLOBALS['STR_ADMIN_COMMANDER_ORDER_AUTHOR_EMAIL']);
$tpl->assign('STR_ADMIN_COMMANDER_BILL_ADDRESS_EXPLAIN', $GLOBALS['STR_ADMIN_COMMANDER_BILL_ADDRESS_EXPLAIN']);
$tpl->assign('STR_ADMIN_COMMANDER_SHIPPING_ADDRESS', $GLOBALS['STR_ADMIN_COMMANDER_SHIPPING_ADDRESS']);
$tpl->assign('STR_ADMIN_COMMANDER_ORDERED_PRODUCTS_LIST', $GLOBALS['STR_ADMIN_COMMANDER_ORDERED_PRODUCTS_LIST']);
$tpl->assign('STR_ADMIN_COMMANDER_PRICES_MUST_BE_IN_ORDER_CURRENCY', $GLOBALS['STR_ADMIN_COMMANDER_PRICES_MUST_BE_IN_ORDER_CURRENCY']);
$tpl->assign('STR_ADMIN_COMMANDER_PRODUCT_NAME', $GLOBALS['STR_ADMIN_COMMANDER_PRODUCT_NAME']);
$tpl->assign('STR_ADMIN_COMMANDER_CURRENCY_EXCHANGE_USED', sprintf($GLOBALS['STR_ADMIN_COMMANDER_CURRENCY_EXCHANGE_USED'], $GLOBALS['site_parameters']['symbole']));
$tpl->assign('STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER', $GLOBALS["STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER"]);
$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE_ORDER', $GLOBALS["STR_ADMIN_UTILISATEURS_CREATE_ORDER"]);
$tpl->assign('STR_ADMIN_FORM_SAVE_CHANGES', $GLOBALS["STR_ADMIN_FORM_SAVE_CHANGES"]);

echo $tpl->fetch();

?>
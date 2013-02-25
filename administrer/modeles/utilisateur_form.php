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
// $Id: utilisateur_form.php 35480 2013-02-23 15:51:54Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_utilisateur_form.tpl');
$tpl->assign('action', get_current_url(false) . '?start=' . (isset($_GET['start']) ? $_GET['start'] : 0));
$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vn($frm['id_utilisateur']))));
$tpl->assign('mode', vb($frm['nouveau_mode']));
$tpl->assign('id_utilisateur', vb($frm['id_utilisateur']));
$tpl->assign('remise_valeur', vb($frm['remise_valeur']));
$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);

if (!empty($frm['date_insert'])) {
	$tpl->assign('date_insert', get_formatted_date($frm['date_insert']));
}
if (!empty($frm['last_date'])) {
	$tpl->assign('last_date', get_formatted_date($frm['last_date']));
}
$tpl->assign('user_ip', vb($frm['user_ip']));

if (!empty($frm['date_update'])) {
	$tpl->assign('date_update', get_formatted_date($frm['date_update']));
}
$tpl->assign('is_id_utilisateur', !empty($frm['id_utilisateur']));
$tpl->assign('email', (!a_priv('demo')?vb($frm['email']):'private [demo]'));
$tpl->assign('pseudo', (!a_priv('demo')?vb($frm['pseudo']):'private [demo]'));

$resPriv = query("SELECT *, name_".$_SESSION['session_langue']." AS name
	FROM peel_profil
	ORDER BY name");
if (num_rows($resPriv)) {
	$user_priv_array = explode('+', $frm['priv']);
	// Sélection du privilège du l'utilisateur. Si le privilège de l'utilisateur n'est pas défini dans la table, le privilège 'util' est présélectionné
	$res_user_priv = query("SELECT name_".$_SESSION['session_langue']." AS name
		FROM peel_profil
		WHERE priv IN ('" . implode("','", real_escape_string($user_priv_array)) . "')");
	$user_priv = fetch_assoc($res_user_priv);
	while ($Priv = fetch_assoc($resPriv)) {
		$tpl_priv_options[] = array('value' => $Priv['priv'],
			'issel' => (!empty($user_priv['name']) ?  in_array($Priv['priv'], $user_priv_array) : $Priv['priv'] == 'util'),
			'name' => $Priv['name']
			);
	}
	$tpl->assign('priv_options', $tpl_priv_options);
}

$tpl->assign('commercial_contact_id', $frm['commercial_contact_id']);

$tpl_util_options = array();
$q = query('SELECT id_utilisateur, pseudo, email, etat, commercial_contact_id
	FROM peel_utilisateurs
	WHERE priv LIKE "admin%" AND pseudo!=""');
while ($result = fetch_assoc($q)) {
	$tpl_util_options[] = array('value' => $result['id_utilisateur'],
		'issel' => vb($frm['commercial_contact_id']) == $result['id_utilisateur'],
		'name' => (!a_priv('demo')?(!empty($result['pseudo'])?$result['pseudo']:$result['email']):'private [demo]')
		);
}
$tpl->assign('util_options', $tpl_util_options);

$tpl->assign('is_annonce_module_active', is_annonce_module_active());
$tpl->assign('is_modif_mode', vb($_REQUEST['mode']) == "modif");
$tpl->assign('mot_passe', vb($frm['mot_passe']));
$tpl->assign('control_plus', vb($frm['control_plus']));
$tpl->assign('note_administrateur', vb($frm['note_administrateur']));
$tpl->assign('activity', vb($frm['activity']));

$tpl->assign('is_groups_module_active', is_groups_module_active());
if (is_groups_module_active()) {
	$resGroupe = query("SELECT *
	FROM peel_groupes
	ORDER BY nom");
	if (num_rows($resGroupe)) {
		$tpl_groupes_options = array();
		while ($Groupe = fetch_assoc($resGroupe)) {
			$tpl_groupes_options[] = array('value' => $Groupe['id'],
				'issel' => vb($frm['id_groupe']) == $Groupe['id'],
				'name' => $Groupe['nom'],
				'remise' => $Groupe['remise']
				);
		}
		$tpl->assign('groupes_options', $tpl_groupes_options);
	}
}

$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
$tpl->assign('telephone_calllink', (is_phone_cti_module_active() && !empty($frm['id_utilisateur']) ? getCallLink(vb($frm['id_utilisateur']), vb($frm['telephone']), vb($frm['nom_famille']), vb($frm['pays'])) : ''));
$tpl->assign('portable_calllink', (is_phone_cti_module_active() && !empty($frm['id_utilisateur']) ? getCallLink(vb($frm['id_utilisateur']), vb($frm['portable']), vb($frm['nom_famille']), vb($frm['pays'])) : ''));
$tpl->assign('country_select_options', get_country_select_options(null, vb($frm['pays']), 'id', true));

$tpl->assign('code_client', vb($frm['code_client']));
$tpl->assign('societe', vb($frm['societe']));
$tpl->assign('civilite', vb($frm['civilite']));
$tpl->assign('prenom', vb($frm['prenom']));
$tpl->assign('nom_famille', vb($frm['nom_famille']));
$tpl->assign('telephone', vb($frm['telephone']));
$tpl->assign('fax', vb($frm['fax']));
$tpl->assign('portable', vb($frm['portable']));
$tpl->assign('adresse', vb($frm['adresse']));
$tpl->assign('code_postal', vb($frm['code_postal']));
$tpl->assign('ville', vb($frm['ville']));
$tpl->assign('naissance', get_formatted_date(vb($frm['naissance'])));
$tpl->assign('remise_percent', vb($frm['remise_percent']));
$tpl->assign('avoir', vb($frm['avoir']));
$tpl->assign('points', vb($frm['points']));
$tpl->assign('is_module_vacances_active', is_module_vacances_active());
if (is_module_vacances_active()) {
	$tpl->assign('vacances_type', get_vacances_type());
} else {
	$tpl->assign('vacances_type', '');
}
$tpl_origin_options = array();
$i = 1;
while (isset($GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i])) {
	$tpl_origin_options[] = array('value' => $i,
		'issel' => vb($frm['origin']) == $i,
		'name' => $GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i]
		);
	$i++;
}
$tpl->assign('origin_infos', array('options' => $tpl_origin_options,
		'is_origin_other_activated' => in_array(vb($frm['origin']), $GLOBALS['origin_other_ids']),
		'origin_other_ids_for_javascript' => 'new Array('.implode(',', $GLOBALS['origin_other_ids']).')',
		'origin_other' => vb($frm['origin_other']),
		'error_text' => '',
		'STR_CHOOSE' => $GLOBALS['STR_CHOOSE']
	));

$tpl->assign('STR_LANGUAGE_FOR_AUTOMATIC_EMAILS', $GLOBALS['STR_LANGUAGE_FOR_AUTOMATIC_EMAILS']);

$tpl_langues = array();
$resLng = query("SELECT *, nom_" . $_SESSION['session_langue'] . " AS nom_lang
	FROM peel_langues
	WHERE etat = '1'" . (!empty($_GET['langue']) ? " OR lang='" . word_real_escape_string($_GET['langue']) . "'" : '') . "
	GROUP BY lang
	ORDER BY position");
while ($lng = fetch_assoc($resLng)) {
	$tpl_langues[] = array('value' => vb($lng['lang']),
		'issel' => ($lng['lang'] == vb($frm['lang'])),
		'name' => vb($lng['lang'])
		);
	$i++;
}
$tpl->assign('langues', $tpl_langues);

$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
if (!empty($frm['logo'])) {
	$tpl->assign('logo_src', $GLOBALS['repertoire_upload'] . '/' . $frm['logo']);
	$tpl->assign('logo_del_href', get_current_url(false) . '?mode=supprlogo&id_utilisateur=' . vn($frm['id_utilisateur']));
}

$tpl->assign('is_clients_module_active', is_clients_module_active());
$tpl->assign('issel_on_client_module', !isset($frm['on_client_module']) || !empty($frm['on_client_module']));
$tpl->assign('is_photodesk_module_active', is_photodesk_module_active());
$tpl->assign('issel_on_photodesk_module', !isset($frm['on_photodesk']) || !empty($frm['on_photodesk']));
$tpl->assign('gift_check_link', is_module_gift_checks_active() && !empty($frm['id_utilisateur']));

$tpl->assign('issel_newsletter', !isset($frm['newsletter']) || !empty($frm['newsletter']));
$tpl->assign('issel_commercial', !isset($frm['commercial']) || $frm['commercial']);

$tpl->assign('is_module_gift_checks_active', is_module_gift_checks_active());
$tpl->assign('mail_src', $GLOBALS['wwwroot_in_admin'] . '/images/mail.gif');
if (is_module_gift_checks_active() && !empty($frm['id_utilisateur'])) {
	$tpl->assign('gift_checks_href', get_current_url(false) . '?mode=cheque&id_utilisateur=' . $frm['id_utilisateur']);
	$tpl->assign('gift_checks_prix', fprix($GLOBALS['site_parameters']['avoir'], true, $GLOBALS['site_parameters']['code'], false));
}
if (is_telechargement_module_active()) {
	include($GLOBALS['dirroot'] . "/modules/telechargement/administrer/fonctions.php");
	include($GLOBALS['dirroot'] . "/modules/telechargement/lang/" . $_SESSION['session_langue'] . ".php");
	$tpl->assign('download_files', affiche_liste_telechargement($frm['id_utilisateur']));
}
$tpl->assign('is_annonce_module_active', is_annonce_module_active());
$tpl->assign('is_destockplus_module_active', is_destockplus_module_active());
$tpl->assign('is_algomtl_module_active', is_algomtl_module_active());
$tpl->assign('fonction', vb($frm['fonction']));
$tpl->assign('type', vb($frm['type']));
$tpl->assign('client_note', intval(getClientNote($frm)));
$tpl->assign('seg_who', formSelect('seg_who', tab_Who(), vb($frm['seg_who'])));
$tpl->assign('seg_buy', formSelect('seg_buy', tab_buy(), vb($frm['seg_buy'])));
$tpl->assign('seg_want', formSelect('seg_want', tab_want(), vb($frm['seg_want'])));
$tpl->assign('seg_think', formSelect('seg_think', tab_think(), vb($frm['seg_think'])));
$tpl->assign('seg_followed', formSelect('seg_followed', tab_followed(), vb($frm['seg_followed'])));

$tpl->assign('is_vitrine_module_active', is_vitrine_module_active());
if (is_vitrine_module_active() && !empty($frm['id_utilisateur'])) {
	$tpl->assign('vitrine_admin', affiche_vitrine_admin($frm['id_utilisateur']));
}

$tpl->assign('is_abonnement_module_active', is_abonnement_module_active());
if (is_abonnement_module_active() && !empty($frm['id_utilisateur'])) {
	$tpl->assign('abonnement_admin', affiche_abonnement_admin($frm['id_utilisateur'], true));
	$tpl->assign('STR_MODULE_ABONNEMENT_ADMIN_MANAGE_SUBSCRIPTIONS', $GLOBALS['STR_MODULE_ABONNEMENT_ADMIN_MANAGE_SUBSCRIPTIONS']);
}

if (is_annonce_module_active() && !empty($frm['pseudo'])) {
	$recherche['login'] = $frm['pseudo'];
	$tpl->assign('add_credit_gold_user', affiche_add_credit_gold_user($frm['id_utilisateur'], true));
	$tpl->assign('liste_annonces_admin', affiche_liste_annonces_admin($recherche, false, $frm['id_utilisateur']));
}

$tpl->assign('is_commerciale_module_active', is_commerciale_module_active());
if (is_commerciale_module_active() && !empty($frm['id_utilisateur'])) {
	$tpl->assign('form_contact_user', affiche_form_contact_user($frm['id_utilisateur'], true));
}

if (vb($_REQUEST['mode']) != "ajout") { // si c'est l'édition
	$tpl->assign('phone_event', affiche_phone_event($frm['id_utilisateur']));
}

$tpl->assign('is_webmail_module_active', is_webmail_module_active());
if (is_webmail_module_active() && !empty($frm['id_utilisateur'])) {
	$tpl->assign('list_user_mail', list_user_mail($frm['id_utilisateur'], true));
}
if (!empty($frm['user_ip'])) {
	// Insertion du module de géoip permettant de définir en fonction de la dernière ip le lieu où s'est connecté la personne dernièrement
	if (file_exists($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php')) {
		include($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php');
		$geoIP = new geoIP();
		$country_detected = $geoIP->geoIPCountryIDByAddr($frm['user_ip']);
		$geoIP->geoIPClose();
		if (!empty($country_detected)) {
			$query = query("SELECT pays_" . $_SESSION['session_langue'] . "
				FROM peel_pays
				WHERE id='" . intval(vn($country_detected)) . "'");
			$result = fetch_assoc($query);
			$country_name = vb($result['pays_' . $_SESSION['session_langue']]);
		}
	}
}
$tpl->assign('country_name', vb($country_name));
$tpl->assign('on_vacances_date', vb($frm['on_vacances_date']));

$tpl->assign('siret', vb($frm['siret']));
$tpl->assign('intracom_for_billing', vb($frm['intracom_for_billing']));
$tpl->assign('ape', vb($frm['ape']));
$tpl->assign('url', vb($frm['url']));
$tpl->assign('description', vb($frm['description']));
$tpl->assign('code_banque', vb($frm['code_banque']));
$tpl->assign('code_guichet', vb($frm['code_guichet']));
$tpl->assign('numero_compte', vb($frm['numero_compte']));
$tpl->assign('cle_rib', vb($frm['cle_rib']));
$tpl->assign('domiciliation', vb($frm['domiciliation']));
$tpl->assign('bic', vb($frm['bic']));
$tpl->assign('iban', vb($frm['iban']));
$tpl->assign('origin_other', vb($frm['origin_other']));
$tpl->assign('project_budget_ht', vb($frm['project_budget_ht']));
$tpl->assign('project_chances_estimated', vb($frm['project_chances_estimated']));
$tpl->assign('comments', vb($frm['comments']));
$tpl->assign('description', vb($frm['description']));
$tpl->assign('project_product_proposed', vb($frm['project_product_proposed']));
$tpl->assign('project_date_forecasted', get_formatted_date(vb($frm['project_date_forecasted'])));
$tpl->assign('etat', vb($frm['etat']));
$tpl->assign('on_vacances', $frm['on_vacances']);
$tpl->assign('titre_soumet', $frm['titre_soumet']);
if (is_annonce_module_active()) {
	$tpl->assign('favorite_category_1', get_announcement_select_options(null, vb($frm['id_cat_1']), 'id'));
	$tpl->assign('favorite_category_2', get_announcement_select_options(null, vb($frm['id_cat_2']), 'id'));
	$tpl->assign('favorite_category_3', get_announcement_select_options(null, vb($frm['id_cat_3']), 'id'));
}
$tpl->assign('STR_MLLE', $GLOBALS['STR_MLLE']);
$tpl->assign('STR_MME', $GLOBALS['STR_MME']);
$tpl->assign('STR_M', $GLOBALS['STR_M']);
$tpl->assign('STR_NAISSANCE', $GLOBALS['STR_NAISSANCE']);
$tpl->assign('STR_LEADER', $GLOBALS['STR_LEADER']);
$tpl->assign('STR_MANAGER', $GLOBALS['STR_MANAGER']);
$tpl->assign('STR_EMPLOYEE', $GLOBALS['STR_EMPLOYEE']);
$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_BUYERS', $GLOBALS['STR_BUYERS']);
$tpl->assign('STR_IMPORTERS_EXPORTERS', $GLOBALS['STR_IMPORTERS_EXPORTERS']);
$tpl->assign('STR_COMMERCIAL_AGENT', $GLOBALS['STR_COMMERCIAL_AGENT']);
$tpl->assign('STR_PURCHASING_MANAGER', $GLOBALS['STR_PURCHASING_MANAGER']);
$tpl->assign('STR_WORD_SELLERS', $GLOBALS['STR_WORD_SELLERS']);
$tpl->assign('STR_WHOLESALER', $GLOBALS['STR_WHOLESALER']);
$tpl->assign('STR_HALF_WHOLESALER', $GLOBALS['STR_HALF_WHOLESALER']);
$tpl->assign('STR_RETAILERS', $GLOBALS['STR_RETAILERS']);
$tpl->assign('STR_ADMIN_UTILISATEURS_EDIT_TITLE', $GLOBALS['STR_ADMIN_UTILISATEURS_EDIT_TITLE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_EMAIL', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_EMAIL']);
$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE_ORDER_TO_THIS_USER', $GLOBALS['STR_ADMIN_UTILISATEURS_CREATE_ORDER_TO_THIS_USER']);
$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE_GIFT_CHECK', sprintf($GLOBALS['STR_ADMIN_UTILISATEURS_CREATE_GIFT_CHECK'], fprix($GLOBALS['site_parameters']['avoir'], true, $GLOBALS['site_parameters']['code'], false)));
$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE_GIFT_CHECK_CONFIRM', $GLOBALS['STR_ADMIN_UTILISATEURS_CREATE_GIFT_CHECK_CONFIRM']);
$tpl->assign('STR_ADMIN_UTILISATEURS_SOCIETE_COM', $GLOBALS['STR_ADMIN_UTILISATEURS_SOCIETE_COM']);
$tpl->assign('STR_ADMIN_UTILISATEURS_INFOGREFFE', $GLOBALS['STR_ADMIN_UTILISATEURS_INFOGREFFE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_EDIT_TITLE', $GLOBALS['STR_ADMIN_UTILISATEURS_EDIT_TITLE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_UPDATE_EXPLAIN', $GLOBALS['STR_ADMIN_UTILISATEURS_UPDATE_EXPLAIN']);
$tpl->assign('STR_ADMIN_UTILISATEURS_REGISTRATION_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_REGISTRATION_DATE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_CONNECTION', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_CONNECTION']);
$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_IP', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_IP']);
$tpl->assign('STR_ADMIN_UTILISATEURS_LAST_UPDATE', $GLOBALS['STR_ADMIN_UTILISATEURS_LAST_UPDATE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_ADMIN_NOTE', $GLOBALS['STR_ADMIN_UTILISATEURS_ADMIN_NOTE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_MODERATION_MORE_STRICT', $GLOBALS['STR_ADMIN_UTILISATEURS_MODERATION_MORE_STRICT']);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
$tpl->assign('STR_ADMIN_PRIVILEGE', $GLOBALS['STR_ADMIN_PRIVILEGE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_ACCOUNT_MANAGER', $GLOBALS['STR_ADMIN_UTILISATEURS_ACCOUNT_MANAGER']);
$tpl->assign('STR_ADMIN_UTILISATEURS_NO_ACCOUNT_MANAGER', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_ACCOUNT_MANAGER']);
$tpl->assign('STR_ADMIN_GROUP', $GLOBALS['STR_ADMIN_GROUP']);
$tpl->assign('STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED']);
$tpl->assign('STR_ADMIN_UTILISATEURS_CLIENT_CODE', $GLOBALS['STR_ADMIN_UTILISATEURS_CLIENT_CODE']);
$tpl->assign('STR_COMPANY', $GLOBALS['STR_COMPANY']);
$tpl->assign('STR_ACTIVITY', $GLOBALS['STR_ACTIVITY']);
$tpl->assign('STR_PUNCTUAL', $GLOBALS['STR_PUNCTUAL']);
$tpl->assign('STR_RECURRENT', $GLOBALS['STR_RECURRENT']);
$tpl->assign('STR_GENDER', $GLOBALS['STR_GENDER']);
$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
$tpl->assign('STR_PORTABLE', $GLOBALS['STR_PORTABLE']);
$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
$tpl->assign('STR_NAISSANCE', $GLOBALS['STR_NAISSANCE']);
$tpl->assign('STR_ADMIN_CODES_PROMOS_PERCENT', $GLOBALS['STR_ADMIN_CODES_PROMOS_PERCENT']);
$tpl->assign('STR_AVOIR', $GLOBALS['STR_AVOIR']);
$tpl->assign('STR_GIFT_POINTS', $GLOBALS['STR_GIFT_POINTS']);
$tpl->assign('STR_GIFT_POINTS', $GLOBALS['STR_GIFT_POINTS']);
$tpl->assign('STR_ADMIN_UTILISATEURS_ON_HOLIDAY_SUPPLIER', $GLOBALS['STR_ADMIN_UTILISATEURS_ON_HOLIDAY_SUPPLIER']);
$tpl->assign('STR_ADMIN_UTILISATEURS_SUPPLIER_RETURN_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_SUPPLIER_RETURN_DATE']);
$tpl->assign('STR_SIREN', $GLOBALS['STR_SIREN']);
$tpl->assign('STR_VAT_INTRACOM', $GLOBALS['STR_VAT_INTRACOM']);
$tpl->assign('STR_MODULE_PREMIUM_APE', $GLOBALS['STR_MODULE_PREMIUM_APE']);
$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_WEBSITE_DESCRIPTION', $GLOBALS['STR_ADMIN_UTILISATEURS_WEBSITE_DESCRIPTION']);
$tpl->assign('STR_BANK_ACCOUNT_CODE', $GLOBALS['STR_BANK_ACCOUNT_CODE']);
$tpl->assign('STR_BANK_ACCOUNT_COUNTER', $GLOBALS['STR_BANK_ACCOUNT_COUNTER']);
$tpl->assign('STR_BANK_ACCOUNT_NUMBER', $GLOBALS['STR_BANK_ACCOUNT_NUMBER']);
$tpl->assign('STR_BANK_ACCOUNT_RIB', $GLOBALS['STR_BANK_ACCOUNT_RIB']);
$tpl->assign('STR_BANK_ACCOUNT_DOMICILIATION', $GLOBALS['STR_BANK_ACCOUNT_DOMICILIATION']);
$tpl->assign('STR_SWIFT', $GLOBALS['STR_SWIFT']);
$tpl->assign('STR_IBAN', $GLOBALS['STR_IBAN']);
$tpl->assign('STR_ORIGIN', $GLOBALS['STR_ORIGIN']);
$tpl->assign('STR_ADMIN_GIVE_DETAIL', $GLOBALS['STR_ADMIN_GIVE_DETAIL']);
$tpl->assign('STR_ADMIN_CHOOSE_FAVORITE_CATEGORIES', $GLOBALS['STR_ADMIN_CHOOSE_FAVORITE_CATEGORIES']);
$tpl->assign('STR_FIRST_CHOICE', $GLOBALS['STR_FIRST_CHOICE']);
$tpl->assign('STR_SECOND_CHOICE', $GLOBALS['STR_SECOND_CHOICE']);
$tpl->assign('STR_THIRD_CHOICE', $GLOBALS['STR_THIRD_CHOICE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_CLIENT_BUDGET', $GLOBALS['STR_ADMIN_UTILISATEURS_CLIENT_BUDGET']);
$tpl->assign('STR_HT', $GLOBALS['STR_HT']);
$tpl->assign('STR_ADMIN_UTILISATEURS_CLIENT_PROJECT_CHANCES', $GLOBALS['STR_ADMIN_UTILISATEURS_CLIENT_PROJECT_CHANCES']);
$tpl->assign('STR_COMMENTS', $GLOBALS['STR_COMMENTS']);
$tpl->assign('STR_ADMIN_DESCRIPTION', $GLOBALS['STR_ADMIN_DESCRIPTION']);
$tpl->assign('STR_ADMIN_DELETE_LOGO', $GLOBALS['STR_ADMIN_DELETE_LOGO']);
$tpl->assign('STR_ADMIN_UTILISATEURS_PROJECT_PRODUCT_PROPOSED', $GLOBALS['STR_ADMIN_UTILISATEURS_PROJECT_PRODUCT_PROPOSED']);
$tpl->assign('STR_ADMIN_UTILISATEURS_PROJECT_DATE_FORECASTED', $GLOBALS['STR_ADMIN_UTILISATEURS_PROJECT_DATE_FORECASTED']);
$tpl->assign('STR_ADMIN_UTILISATEURS_PROJECT_DESCRIPTION_DISPLAY', $GLOBALS['STR_ADMIN_UTILISATEURS_PROJECT_DESCRIPTION_DISPLAY']);
$tpl->assign('STR_ADMIN_UTILISATEURS_DISPLAY_IMAGE_IN_PHOTODESK', $GLOBALS['STR_ADMIN_UTILISATEURS_DISPLAY_IMAGE_IN_PHOTODESK']);
$tpl->assign('STR_NEWSLETTER', $GLOBALS['STR_NEWSLETTER']);
$tpl->assign('STR_ADMIN_UTILISATEURS_NEWSLETTER_CHECKBOX', $GLOBALS['STR_ADMIN_UTILISATEURS_NEWSLETTER_CHECKBOX']);
$tpl->assign('STR_ADMIN_UTILISATEURS_COMMERCIAL', $GLOBALS['STR_ADMIN_UTILISATEURS_COMMERCIAL']);
$tpl->assign('STR_ADMIN_UTILISATEURS_COMMERCIAL_CHECKBOX', $GLOBALS['STR_ADMIN_UTILISATEURS_COMMERCIAL_CHECKBOX']);
$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD']);
$tpl->assign('STR_ADMIN_COMMANDER_CLIENT_INFORMATION', $GLOBALS['STR_ADMIN_COMMANDER_CLIENT_INFORMATION']);
$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
$tpl->assign('STR_ADMIN_UTILISATEURS_CLIENT_TYPE', $GLOBALS['STR_ADMIN_UTILISATEURS_CLIENT_TYPE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_WHO', $GLOBALS['STR_ADMIN_UTILISATEURS_WHO']);
$tpl->assign('STR_ADMIN_UTILISATEURS_BUY', $GLOBALS['STR_ADMIN_UTILISATEURS_BUY']);
$tpl->assign('STR_ADMIN_UTILISATEURS_WANTS', $GLOBALS['STR_ADMIN_UTILISATEURS_WANTS']);
$tpl->assign('STR_ADMIN_UTILISATEURS_THINKS', $GLOBALS['STR_ADMIN_UTILISATEURS_THINKS']);
$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_BY', $GLOBALS['STR_ADMIN_UTILISATEURS_FOLLOWED_BY']);
$tpl->assign('STR_ADMIN_UTILISATEURS_JOB', $GLOBALS['STR_ADMIN_UTILISATEURS_JOB']);
$tpl->assign('STR_ADMIN_UTILISATEURS_SEGMENTATION_TOTAL', $GLOBALS['STR_ADMIN_UTILISATEURS_SEGMENTATION_TOTAL']);
$tpl->assign('STR_ADMIN_UTILISATEURS_ADD_CONTACT_DATE', $GLOBALS['STR_ADMIN_UTILISATEURS_ADD_CONTACT_DATE']);
$tpl->assign('STR_ADMIN_UTILISATEURS_MANAGE_CALLS', $GLOBALS['STR_ADMIN_UTILISATEURS_MANAGE_CALLS']);
$tpl->assign('STR_LOGO', $GLOBALS['STR_LOGO']);
$tpl->assign('STR_NONE', $GLOBALS['STR_NONE']);
$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
echo $tpl->fetch();

if (!empty($frm['id_utilisateur'])) {
	$columns = 8;
	if (is_parrainage_module_active()) {
		$columns++;
	}

	$tpl2 = $GLOBALS['tplEngine']->createTemplate('admin_utilisateur_form_isutil.tpl');
	$tpl2->assign('action', get_current_url(false) . '?id_utilisateur=' . intval($_REQUEST['id_utilisateur']) . '&mode=modif');
	$tpl2->assign('pseudo', (!empty($frm['pseudo'])?$frm['pseudo']:vb($frm['email'])));
	$tpl2->assign('event_comment', (!empty($_POST['event_comment']) ? $_POST['event_comment'] : ''));
	$tpl2->assign('actions_moderations_user', affiche_actions_moderations_user($frm['id_utilisateur']));
	$tpl2->assign('columns', $columns);
	$tpl2->assign('mini_liste_commande_src', $GLOBALS['administrer_url'] . '/images/mini_liste_commande.gif');
	$tpl2->assign('is_parrainage_module_active', is_parrainage_module_active());
	$tpl2->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl2->assign('printer_src', $GLOBALS['wwwroot_in_admin'] . '/images/t_printer.gif');
	$tpl2->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl2->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);

	$query = query("SELECT c.*, GROUP_CONCAT(ca.nom_produit SEPARATOR '<br />') AS ordered_products
		FROM peel_commandes c
		LEFT JOIN peel_commandes_articles ca ON ca.commande_id=c.id
		WHERE c.id_utilisateur = '" . intval($frm['id_utilisateur']) . "'
		GROUP BY c.id
		ORDER BY c.id DESC");
	if (num_rows($query) > 0) {
		$tpl_results = array();

		$total_ttc = $total_ht = 0;
		$i = 0;
		while ($order_infos = fetch_object($query)) {
			$total_ttc += $order_infos->montant;
			$total_ht += $order_infos->montant_ht;
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'modif_href' => $GLOBALS['administrer_url'] . '/commander.php?mode=modif&commandeid=' . $order_infos->id,
				'print_href' => $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?mode=details&code_facture=' . $order_infos->code_facture,
				'drop_href' => $GLOBALS['administrer_url'] . '/commander.php?mode=suppr&id=' . $order_infos->id,
				'id' => $order_infos->id,
				'date' => get_formatted_date($order_infos->o_timestamp),
				'prix' => fprix($order_infos->montant, true, $order_infos->devise, true, $order_infos->currency_rate),
				'recuperer_avoir_commande' => is_parrainage_module_active() ? fprix(recuperer_avoir_commande($order_infos->id), true, $order_infos->devise, true, $order_infos->currency_rate) : '',
				'payment_name' => get_payment_name($order_infos->paiement),
				'payment_status_name' => get_payment_status_name($order_infos->id_statut_paiement),
				'delivery_status_name' => get_delivery_status_name($order_infos->id_statut_livraison),
				'ordered_products' => String::str_shorten($order_infos->ordered_products, 200)
				);
			$i++;
		}
		$tpl2->assign('results', $tpl_results);
		$tpl2->assign('action2', htmlspecialchars($_SERVER['REQUEST_URI']));
		$tpl2->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['id_utilisateur']));
		$tpl2->assign('user_id', intval(vn($frm['id_utilisateur'])));
	}
	$tpl2->assign('STR_ADMIN_UTILISATEURS_ADD_EVENT_REGARDING', $GLOBALS['STR_ADMIN_UTILISATEURS_ADD_EVENT_REGARDING']);
	$tpl2->assign('STR_ADMIN_UTILISATEURS_EVENT_DESCRIPTION', $GLOBALS['STR_ADMIN_UTILISATEURS_EVENT_DESCRIPTION']);
	$tpl2->assign('STR_ADMIN_UTILISATEURS_SAVE_EVENT', $GLOBALS['STR_ADMIN_UTILISATEURS_SAVE_EVENT']);
	$tpl2->assign('STR_ADMIN_UTILISATEURS_ACTIONS_ON_THIS_ACCOUNT', $GLOBALS['STR_ADMIN_UTILISATEURS_ACTIONS_ON_THIS_ACCOUNT']);
	$tpl2->assign('STR_ADMIN_UTILISATEURS_ORDERS_LIST', $GLOBALS['STR_ADMIN_UTILISATEURS_ORDERS_LIST']);
	$tpl2->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl2->assign('STR_ORDER', $GLOBALS['STR_ORDER']);
	$tpl2->assign('STR_DATE', $GLOBALS['STR_DATE']);
	$tpl2->assign('STR_TOTAL', $GLOBALS['STR_TOTAL']);
	$tpl2->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl2->assign('STR_AVOIR', $GLOBALS['STR_AVOIR']);
	$tpl2->assign('STR_ADMIN_UTILISATEURS_PRODUCTS_ORDERED', $GLOBALS['STR_ADMIN_UTILISATEURS_PRODUCTS_ORDERED']);
	$tpl2->assign('STR_PAYMENT', $GLOBALS['STR_PAYMENT']);
	$tpl2->assign('STR_DELIVERY', $GLOBALS['STR_DELIVERY']);
	$tpl2->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
	$tpl2->assign('STR_PRINT', $GLOBALS['STR_PRINT']);
	$tpl2->assign('STR_ADMIN_UTILISATEURS_PRINT_ALL_BILLS', $GLOBALS['STR_ADMIN_UTILISATEURS_PRINT_ALL_BILLS']);
	$tpl2->assign('STR_ADMIN_UTILISATEURS_NO_ORDER_FOUND', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_ORDER_FOUND']);
	echo $tpl2->fetch();
}

?>
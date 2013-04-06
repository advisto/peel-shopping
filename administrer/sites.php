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
// $Id: sites.php 36232 2013-04-05 13:16:01Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$DOC_TITLE = $GLOBALS['STR_ADMIN_SITES_TITLE'];
$output = '';
if (!isset($_REQUEST['mode'])) {
	$all_site_names = get_all_site_names();
	if (count($all_site_names) == 1) {
		redirect_and_die($_SERVER["PHP_SELF"] . "?mode=modif&id=1");
	}
}

$frm = $_POST;
$form_error_object = new FormError();

if (!empty($frm['logo']) && strpos($frm['logo'], 'http') === false) {
	if (String::substr($frm['logo'], 0, 1) != '/') {
		$frm['logo'] = '/' . $frm['logo'];
	}
	$frm['logo'] = $GLOBALS['wwwroot'] . $frm['logo'];
}

switch (vb($_GET['mode'])) {
	case "ajout" :
		$frm['favicon'] = upload('favicon', false, 'image_or_ico', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
		$frm['default_picture'] = upload('default_picture', false, 'image_or_ico', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
		$output .= affiche_formulaire_ajout_site($frm);
		break;

	case "modif" :
		if (!empty($frm)) {
			$frm['favicon'] = upload('favicon', false, 'image_or_ico', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
			$frm['default_picture'] = upload('default_picture', false, 'image_or_ico', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
			
			if (!verify_token($_SERVER['PHP_SELF'] . vb($_GET['mode']) . vb($_GET['id']))) {
				$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
			}
			$form_error_object->valide_form($frm,
				array('email_webmaster' => $GLOBALS['STR_ADMIN_SITES_ERR_EMPTY_EMAIL'] . ' "'.$GLOBALS["STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL"].'".'));
			if (!$form_error_object->count()) {
				create_or_update_site($_GET['id'], $frm);
				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_SITES_MSG_UPDATED_OK']))->fetch();
				$output .= affiche_liste_site();
			} else {
				foreach ($form_error_object->error as $name => $text) {
					$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $text))->fetch();
				}
				$output .= affiche_formulaire_modif_site($_GET['id'], $frm);
			}
		} else {
			$output .= affiche_formulaire_modif_site($_GET['id'], $frm);
		}
		break;
	/*
	case "suppr" :
		$output .= supprime_site($_GET['id']);
		$output .= affiche_liste_site();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . vb($_GET['mode']) . vb($_GET['id']))) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			create_or_update_site(null, $frm);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_SITES_MSG_INSERTED_OK']))->fetch();
			$output .= affiche_liste_site();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			}
			$output .= affiche_formulaire_ajout_statut($frm);
		}
		break;
	*/
	case "supprfavicon" :
		supprime_favicon(vn($_GET['id']), $_GET['favicon']);
		$output .= affiche_formulaire_modif_site($_GET['id'], $frm);
		break;

	case "supprdefault_picture" :
		supprime_default_picture(vn($_GET['id']), $_GET['default_picture']);
		$output .= affiche_formulaire_modif_site($_GET['id'], $frm);
		break;

	default :
		$output .= affiche_liste_site();
		break;
}

include("modeles/haut.php");
echo $output;
include("modeles/bas.php");

/**
 * FONCTIONS
 */

/**
 * UNUSED : Affiche un formulaire vierge pour ajouter un site
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_site(&$frm)
{
	// Default values
	$urlsite = 'http://' . $_SERVER['HTTP_HOST'];
	$urlscript = dirname($_SERVER['PHP_SELF']);
	$url = ($urlscript == '/') ? trim($urlsite) : trim($urlsite . $urlscript);

	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['default_country_id'] = vn($GLOBALS['site_parameters']['default_country_id']);
	foreach($GLOBALS['lang_codes'] as $lng) {
		$frm['nom_' . $lng] = "";
		$frm['logo_' . $lng] = 1;
	}
	$frm['pays_exoneration_tva'] = "";
	$frm['css'] = "";
	$frm['template_directory'] = "";
	$frm['template_multipage'] = "";
	$frm['url'] = str_replace(array("/administrer", '/' . $GLOBALS['site_parameters']['backoffice_directory_name']), "", $url);
	$frm['on_logo'] = 0;
	$frm['favicon'] = "";
	$frm['timemax'] = 1800;
	$frm['seuil'] = 5;
	$frm['seuil_total'] = 100;
	$frm['seuil_total_reve'] = 100;
	$frm['module_retail'] = 1;
	$frm['module_affilie'] = 1;
	$frm['commission_affilie'] = 5;
	$frm['module_lot'] = 1;
	$frm['module_parrain'] = 1;
	$frm['module_cadeau'] = 1;
	$frm['module_devise'] = 1;
	$frm['devise_defaut'] = 1;
	$frm['module_nuage'] = 1;
	$frm['module_flash'] = 1;
	$frm['module_cart_preservation'] = 1;
	$frm['module_pub'] = 1;
	$frm['module_faq'] = 1;
	$frm['module_vacances'] = 1;
	$frm['module_vacances_type'] = 0;
	$frm['facebook_connect'] = 0;
	$frm['fb_appid'] = "";
	$frm['fb_secret'] = "";
	$frm['fb_baseurl'] = "";

	foreach ($GLOBALS['lang_codes'] as $lng) {
		$frm['module_vacances_client_msg_' . $lng] = "";
	}

	$frm['module_precedent_suivant'] = 1;
	$frm['in_category'] = 1;
	$frm['module_forum'] = 1;
	$frm['module_conditionnement'] = 1;
	$frm['module_giftlist'] = 1;
	$frm['module_rss'] = 1;
	$frm['module_ecotaxe'] = 1;
	$frm['module_url_rewriting'] = 1;
	$frm['module_entreprise'] = 0;
	$frm['display_prices_with_taxes'] = 1;
	$frm['display_prices_with_taxes_in_admin'] = 1;
	$frm['html_editor'] = 1;
	$frm['avoir'] = 10;
	$frm['email_paypal'] = "";
	$frm['email_commande'] = "";
	$frm['email_webmaster'] = "";
	$frm['nom_expediteur'] = "";
	$frm['email_client'] = "";
	$frm['sips'] = "";
	$frm['spplus'] = "";
	$frm['systempay_payment_count'] = "";
	$frm['systempay_payment_period'] = "";
	$frm['systempay_cle_test'] = "";
	$frm['systempay_cle_prod'] = "";
	$frm['systempay_test_mode'] = "";
	$frm['systempay_code_societe'] = "";
	$frm['paybox_cgi'] = "";
	$frm['paybox_site'] = "";
	$frm['paybox_rang'] = "";
	$frm['paybox_identifiant'] = "";
	$frm['email_moneybookers'] = "";
	$frm['secret_word'] = "";
	$frm['module_rollover'] = 1;
	$frm['type_rollover'] = 1;
	$frm['logo_affiliation'] = "";
	$frm['small_order_overcost_limit'] = "";
	$frm['small_order_overcost_amount'] = "";
	$frm['small_order_overcost_tva_percent'] = "";
	$frm['minimal_amount_to_order'] = "";
	$frm['mode_transport'] = 1;
	$frm['titre_bouton'] = "Ajouter un site";
	$frm['format_numero_facture'] = "";
	$frm['module_socolissimo'] = 1;
	$frm['module_icirelais'] = 1;
	$frm['module_autosend'] = 0;
	$frm['module_autosend_delay'] = 5;
	$frm['fb_admins'] = '';
	$frm['facebook_page_link'] = '';
	$frm['socolissimo_foid'] = "";
	$frm['socolissimo_sha1_key'] = "";
	$frm['socolissimo_urlok'] = "";
	$frm['socolissimo_urlko'] = "";
	$frm['socolissimo_preparationtime'] = "";
	$frm['socolissimo_forwardingcharges'] = "";
	$frm['socolissimo_firstorder'] = "";
	$frm['socolissimo_pointrelais'] = "";
	$frm['tag_analytics'] = "";
	$frm['availability_of_carrier'] = 0;
	$frm['allow_add_product_with_no_stock_in_cart'] = "";
	$frm['zoom'] = "";
	$frm['enable_prototype'] = "";
	$frm['enable_jquery'] = "";
	$frm['send_email_active'] = 1;
	$frm['display_errors_for_ips'] = "*";
	$frm['display_nb_product'] = "*";
	$frm['module_tnt'] = 0;
	$frm['tnt_username'] = 0;
	$frm['tnt_password'] = 0;
	$frm['tnt_account_number'] = 0;
	$frm['expedition_delay'] = 0;
	$frm['expedition_delay'] = 0;
	
	// attribut pour l'image par défaut
	$frm['default_picture'] = "";

	return affiche_formulaire_site($frm);
}

/**
 * Affiche le formulaire de modification pour le site sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_site($id, &$frm)
{
	if (empty($frm)) {
		// Charge les informations du produit
		$frm = $GLOBALS['site_parameters'];
	}
	$frm_modules = get_modules_array(false, null, null, true);
	$frm['id'] = $id;
	$frm["nouveau_mode"] = "modif";
	$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	return affiche_formulaire_site($frm, $frm_modules);
}

/**
 * affiche_formulaire_site()
 *
 * @param array $frm Array with all fields data
 * @param mixed $frm_modules
 * @return
 */
function affiche_formulaire_site(&$frm, $frm_modules)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_site.tpl');

	$tpl_zones = array();
	$qid = query("SELECT *
		FROM peel_zones
		WHERE on_franco=1");
	while ($result = fetch_assoc($qid)) {
		$tpl_zones[] = array('href' => $GLOBALS['administrer_url'] . '/zones.php?mode=modif&id=' . $result['id'],
			'nom' => $result['nom_' . $_SESSION['session_langue']]
			);
		$zones_franco_port[] = '<a href="' . $GLOBALS['administrer_url'] . '/zones.php?mode=modif&id=' . $result['id'] . '">' . $result['nom_' . $_SESSION['session_langue']] . '</a>';
	}
	$tpl->assign('zones', $tpl_zones);
	$tpl->assign('zones_href', $GLOBALS['administrer_url'] . '/zones.php');

	$tpl->assign('action', get_current_url(false) . '?mode=' . String::str_form_value($frm["nouveau_mode"]) . '&id=' . intval($frm['id']));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('site_suspended', vb($frm['site_suspended']));

	$tpl->assign('membre_admin_href', $GLOBALS['wwwroot_in_admin'] . '/membre.php');

	$tpl_langs = array();
	foreach ($GLOBALS['lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => $frm['nom_' . $lng],
			'logo' => $frm['logo_' . $lng],
			'module_vacances_value' => (!empty($frm['module_vacances_client_msg_' . $lng]) ? String::html_entity_decode_if_needed(vb($frm['module_vacances_client_msg_' . $lng])) : ""),
			);
	}
	$tpl->assign('langs', $tpl_langs);

	$tpl->assign('country_select_options', get_country_select_options(null, $frm['default_country_id'], 'id'));

	if ($handle = opendir($GLOBALS['dirroot'] . "/modeles")) {
		$tpl_directory_options = array();
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != ".svn") {
				$tpl_directory_options[] = array('value' => $file,
					'issel' => $file == $frm['template_directory'],
					);
			}
		}
		$tpl->assign('directory_options', $tpl_directory_options);
	}

	$tpl->assign('template_multipage', $frm['template_multipage']);
	$tpl->assign('css', $frm['css']);
	$tpl->assign('on_logo', $frm['on_logo']);
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');

	if (!empty($frm["favicon"])) {
		$tpl->assign('favicon', array('src' => $GLOBALS['repertoire_upload'] . '/' . $frm["favicon"],
				'favicon' => vb($frm['favicon']),
				'sup_href' => get_current_url(false) . '?mode=supprfavicon&id=' . vb($frm['id']) . '&favicon=' . String::str_form_value(vb($frm["favicon"]))
				));
	}

	$tpl->assign('zoom', vb($frm['zoom']));
	$tpl->assign('enable_prototype', !empty($frm['enable_prototype']));
	$tpl->assign('enable_jquery', !empty($frm['enable_jquery']));
	$tpl->assign('export_encoding', $frm['export_encoding']);
	$tpl->assign('module_autosend', $frm['module_autosend']);
	$tpl->assign('module_autosend_delay', $frm['module_autosend_delay']);
	$tpl->assign('category_count_method', $frm['category_count_method']);
	$tpl->assign('popup_width', vb($frm['popup_width']));
	$tpl->assign('popup_height', vb($frm['popup_height']));
	$tpl->assign('admin_force_ssl', vb($frm['admin_force_ssl']));
	$tpl->assign('membre_href', str_replace('http://', 'https://', $GLOBALS['wwwroot'] . '/membre.php'));

	$tpl->assign('display_nb_product', vb($frm['display_nb_product']));
	$tpl->assign('small_width', vb($frm['small_width']));
	$tpl->assign('small_height', vb($frm['small_height']));
	$tpl->assign('medium_width', vb($frm['medium_width']));
	$tpl->assign('medium_height', vb($frm['medium_height']));
	$tpl->assign('module_filtre', vb($frm['module_filtre']));
	$tpl->assign('category_order_on_catalog', vb($frm['category_order_on_catalog']));
	$tpl->assign('type_affichage_attribut', vb($frm['type_affichage_attribut']));
	$tpl->assign('anim_prod', vb($frm['anim_prod']));

	$tpl->assign('sessions_duration', $frm['sessions_duration']);
	$tpl->assign('nb_produit_page', $frm['nb_produit_page']);

	$tpl->assign('is_best_seller_module_active', is_best_seller_module_active());
	$tpl->assign('promotions_href', $GLOBALS['wwwroot_in_admin'] . '/achat/promotions.php');
	$tpl->assign('is_stock_advanced_module_active', is_stock_advanced_module_active());
	$tpl->assign('is_fonctionsvacances', file_exists($GLOBALS['fonctionsvacances']));

	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);

	$tpl->assign('default_picture_delete_url', get_current_url(false) . '?mode=supprdefault_picture&id=' . vb($frm['id']) . '&default_picture=' . vb($frm["default_picture"]));
	$tpl->assign('default_picture_delete_icon_url', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('default_picture', vb($frm["default_picture"]));
	$tpl->assign('default_picture_url',  $GLOBALS['repertoire_upload'] . '/' . vb($frm["default_picture"]));

	$tpl->assign('is_fonctionsdevises', file_exists($GLOBALS['fonctionsdevises']));
	$tpl->assign('devises_href', $GLOBALS['wwwroot_in_admin'] . '/modules/devises/administrer/devises.php');
	$tpl_devices_options = array();
	if (file_exists($GLOBALS['fonctionsdevises'])) {
		$req = "SELECT *
		FROM peel_devises
		WHERE etat = '1'";
		$res = query($req);
		while ($tab_devise = fetch_assoc($res)) {
			$tpl_devices_options[] = array('value' => intval($tab_devise['id']),
				'issel' => $frm['devise_defaut'] == $tab_devise['id'],
				'name' => $tab_devise['devise']
				);
		}
	}
	$tpl->assign('devices_options', $tpl_devices_options);

	$tpl->assign('is_module_banner_active', is_module_banner_active());
	$tpl->assign('is_vitrine_module_active', is_vitrine_module_active());
	$tpl->assign('is_annonce_module_active', is_annonce_module_active());
	$tpl->assign('is_iphone_ads_module_active', is_iphone_ads_module_active());
	$tpl_modules = array();
	$i = 0;
	foreach ($frm_modules as $this_module_infos) {
		$tpl_modules[] = array('tr_rollover' => tr_rollover($i, true),
			'title' => $this_module_infos['title_' . $_SESSION['session_langue']],
			'id' => $this_module_infos['id'],
			'display_mode' => $this_module_infos['display_mode'],
			'location' => $this_module_infos['location'],
			'etat' => $this_module_infos['etat'],
			'in_home' => $this_module_infos['in_home'],
			'position' => $this_module_infos['position'],
			'is_left_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane')),
			'is_right_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane')),
			'is_footer_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'guide')),
			'is_header_off' => in_array($this_module_infos['technical_code'], array('advertising1', 'advertising2', 'advertising3', 'advertising4', 'advertising5')),
			'is_top_middle_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane')),
			'is_center_middle_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane')),
			'is_center_middle_home_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane')),
			'is_bottom_middle_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'guide')),
			'is_top_vitrine_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'guide')),
			'is_bottom_vitrine_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'guide')),
			'is_annonce_place_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'guide')),
			'is_iphone_place_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'guide')),
			);
		$i++;
	}
	$tpl->assign('modules', $tpl_modules);

	$tpl->assign('is_fonctionstagcloud', file_exists($GLOBALS['fonctionstagcloud']));
	$tpl->assign('is_flash_sell_module_active', is_flash_sell_module_active());
	$tpl->assign('is_fonctionsbanner', file_exists($GLOBALS['fonctionsbanner']));
	$tpl->assign('is_fonctionsmenus', file_exists($GLOBALS['fonctionsmenus']));
	$tpl->assign('is_fonctionsrss', file_exists($GLOBALS['fonctionsrss']));
	$tpl->assign('is_fonctionsavis', file_exists($GLOBALS['fonctionsavis']));
	$tpl->assign('is_fonctionsprecedentsuivant', file_exists($GLOBALS['fonctionsprecedentsuivant']));
	$tpl->assign('is_fonctionsgooglefriendconnect', file_exists($GLOBALS['fonctionsgooglefriendconnect']));
	$tpl->assign('is_fonctionssignintwitter', file_exists($GLOBALS['fonctionssignintwitter']));
	$tpl->assign('is_fonctionscartpreservation', file_exists($GLOBALS['fonctionscartpreservation']));
	$tpl->assign('is_fonctionsreseller', file_exists($GLOBALS['fonctionsreseller']));
	$tpl->assign('is_fonctionsaffiliate', file_exists($GLOBALS['fonctionsaffiliate']));
	$tpl->assign('is_fonctionslot', file_exists($GLOBALS['fonctionslot']));
	$tpl->assign('is_fonctionsparrain', file_exists($GLOBALS['fonctionsparrain']));
	$tpl->assign('is_fonctionsgiftcheck', file_exists($GLOBALS['fonctionsgiftcheck']));
	$tpl->assign('is_fonctionsfaq', file_exists($GLOBALS['fonctionsfaq']));
	$tpl->assign('is_rewritefile', file_exists($GLOBALS['rewritefile']));
	$tpl->assign('is_fonctionsmicro', file_exists($GLOBALS['fonctionsmicro']));
	$tpl->assign('is_fonctionsbirthday', file_exists($GLOBALS['fonctionsbirthday']));
	$tpl->assign('is_fonctionscatpromotions', file_exists($GLOBALS['fonctionscatpromotions']));
	$tpl->assign('is_fonctionsmarquepromotions', file_exists($GLOBALS['fonctionsmarquepromotions']));
	$tpl->assign('is_fonctionscomparateur', file_exists($GLOBALS['fonctionscomparateur']));
	$tpl->assign('is_fonctionsstock_advanced', file_exists($GLOBALS['fonctionsstock_advanced']));
	$tpl->assign('is_fonctionsforum', file_exists($GLOBALS['fonctionsforum']));
	$tpl->assign('is_fonctionsgiftlist', file_exists($GLOBALS['fonctionsgiftlist']));
	$tpl->assign('is_fonctionssocolissimo', file_exists($GLOBALS['fonctionssocolissimo']));
	$tpl->assign('is_fonctionsexpeditor', file_exists($GLOBALS['fonctionsexpeditor']));
	$tpl->assign('is_fonctionsicirelais', file_exists($GLOBALS['fonctionsicirelais']));
	$tpl->assign('is_fonctionstnt', file_exists($GLOBALS['fonctionstnt']));
	$tpl->assign('is_fonctionsatos', file_exists($GLOBALS['fonctionsatos']));
	$tpl->assign('is_fonctionsspplus', file_exists($GLOBALS['fonctionsspplus']));
	$tpl->assign('is_fonctionspaybox', file_exists($GLOBALS['fonctionspaybox']));
	$tpl->assign('is_fonctionssystempay', file_exists($GLOBALS['fonctionssystempay']));
	$tpl->assign('is_fonctionspartenaires', file_exists($GLOBALS['fonctionspartenaires']));
	$tpl->assign('is_fonctionsfacebook', file_exists($GLOBALS['fonctionsfacebook']));
	$tpl->assign('is_fonctionfacebookconnect', file_exists($GLOBALS['fonctionfacebookconnect']));

	$tpl->assign('nb_on_top', vb($frm['nb_on_top']));
	$tpl->assign('nb_last_views', vb($frm['nb_last_views']));
	$tpl->assign('global_remise_percent', vb($frm['global_remise_percent']));
	$tpl->assign('pays_exoneration_tva', vb($frm['pays_exoneration_tva']));
	$tpl->assign('timemax', vb($frm['timemax']));
	$tpl->assign('seuil', vb($frm['seuil']));
	$tpl->assign('quotation_delay', vb($frm['quotation_delay']));
	$tpl->assign('email_webmaster', vb($frm['email_webmaster']));
	$tpl->assign('nom_expediteur', vb($frm['nom_expediteur']));
	$tpl->assign('email_commande', vb($frm['email_commande']));
	$tpl->assign('email_client', vb($frm['email_client']));
	$tpl->assign('email_paypal', vb($frm['email_paypal']));
	$tpl->assign('email_moneybookers', vb($frm['email_moneybookers']));
	$tpl->assign('secret_word', vb($frm['secret_word']));
	$tpl->assign('availability_of_carrier', vb($frm['availability_of_carrier']));
	$tpl->assign('tag_analytics', vb($frm['tag_analytics']));
	$tpl->assign('googlefriendconnect', vb($frm['googlefriendconnect']));
	$tpl->assign('googlefriendconnect_site_id', vb($frm['googlefriendconnect_site_id']));
	$tpl->assign('sign_in_twitter', vb($frm['sign_in_twitter']));
	$tpl->assign('twitter_consumer_key', vb($frm['twitter_consumer_key']));
	$tpl->assign('twitter_consumer_secret', vb($frm['twitter_consumer_secret']));
	$tpl->assign('twitter_oauth_callback', vb($frm['twitter_oauth_callback']));
	$tpl->assign('commission_affilie', vb($frm['commission_affilie']));
	$tpl->assign('logo_affiliation', vb($frm['logo_affiliation']));
	$tpl->assign('avoir', vb($frm['avoir']));
	$tpl->assign('module_url_rewriting', vn($frm['module_url_rewriting']));
	$tpl->assign('sips', vb($frm['sips']));
	$tpl->assign('spplus', vb($frm['spplus']));
	$tpl->assign('systempay_payment_count', vb($frm['systempay_payment_count']));
	$tpl->assign('systempay_payment_period', vb($frm['systempay_payment_period']));
	$tpl->assign('systempay_cle_test', vb($frm['systempay_cle_test']));
	$tpl->assign('systempay_cle_prod', vb($frm['systempay_cle_prod']));
	$tpl->assign('systempay_test_mode', vb($frm['systempay_test_mode']));
	$tpl->assign('systempay_code_societe', vb($frm['systempay_code_societe']));
	$tpl->assign('paybox_cgi', vb($frm['paybox_cgi']));
	$tpl->assign('paybox_site', vb($frm['paybox_site']));
	$tpl->assign('paybox_rang', vb($frm['paybox_rang']));
	$tpl->assign('paybox_identifiant', vb($frm['paybox_identifiant']));
	$tpl->assign('fb_admins', vb($frm['fb_admins']));
	$tpl->assign('facebook_page_link', vb($frm['facebook_page_link']));
	$tpl->assign('facebook_connect', vb($frm['facebook_connect']));
	$tpl->assign('fb_appid', vb($frm['fb_appid']));
	$tpl->assign('fb_secret', vb($frm['fb_secret']));
	$tpl->assign('fb_baseurl', vb($frm['fb_baseurl']));
	$tpl->assign('display_errors_for_ips', vb($frm['display_errors_for_ips']));
	$tpl->assign('titre_bouton', vb($frm['titre_bouton']));

	$tpl->assign('allow_add_product_with_no_stock_in_cart', vb($frm['allow_add_product_with_no_stock_in_cart']));
	$tpl->assign('format_numero_facture', vb($frm['format_numero_facture']));
	$tpl->assign('small_order_overcost_limit', vb($frm['small_order_overcost_limit']));
	$tpl->assign('small_order_overcost_amount', vb($frm['small_order_overcost_amount']));
	$tpl->assign('small_order_overcost_tva_percent', vb($frm['small_order_overcost_tva_percent']));
	$tpl->assign('minimal_amount_to_order', vb($frm['minimal_amount_to_order']));
	$tpl->assign('seuil_total', vb($frm['seuil_total']));
	$tpl->assign('seuil_total_reve', vb($frm['seuil_total_reve']));
	$tpl->assign('nb_product', vb($frm['nb_product']));
	$tpl->assign('socolissimo_foid', vb($frm['socolissimo_foid']));
	$tpl->assign('socolissimo_sha1_key', vb($frm['socolissimo_sha1_key']));
	$tpl->assign('socolissimo_urlko', vb($frm['socolissimo_urlko']));
	$tpl->assign('socolissimo_firstorder', vb($frm['socolissimo_firstorder']));
	$tpl->assign('socolissimo_pointrelais', vb($frm['socolissimo_pointrelais']));
	$tpl->assign('partner_count_method', vb($frm['partner_count_method']));
	$tpl->assign('tnt_username', vn($frm['tnt_username']));
	$tpl->assign('tnt_password', vn($frm['tnt_password']));
	$tpl->assign('tnt_account_number', vn($frm['tnt_account_number']));
	$tpl->assign('expedition_delay', vn($frm['expedition_delay']));

	$tpl->assign('act_on_top', vn($frm['act_on_top']));
	$tpl->assign('auto_promo', vn($frm['auto_promo']));
	$tpl->assign('mode_transport', vn($frm['mode_transport']));
	$tpl->assign('module_ecotaxe', vn($frm['module_ecotaxe']));
	$tpl->assign('display_prices_with_taxes', vn($frm['display_prices_with_taxes']));
	$tpl->assign('display_prices_with_taxes_in_admin', vn($frm['display_prices_with_taxes_in_admin']));
	$tpl->assign('module_devise', vn($frm['module_devise']));
	$tpl->assign('html_editor', vn($frm['html_editor']));
	$tpl->assign('send_email_active', vn($frm['send_email_active']));
	$tpl->assign('module_nuage', vn($frm['module_nuage']));
	$tpl->assign('module_flash', vn($frm['module_flash']));
	$tpl->assign('module_pub', vn($frm['module_pub']));
	$tpl->assign('module_rollover', vn($frm['module_rollover']));
	$tpl->assign('type_rollover', vn($frm['type_rollover']));
	$tpl->assign('module_rss', vn($frm['module_rss']));
	$tpl->assign('module_avis', vn($frm['module_avis']));
	$tpl->assign('module_captcha', vn($frm['module_captcha']));
	$tpl->assign('module_precedent_suivant', vn($frm['module_precedent_suivant']));
	$tpl->assign('in_category', vn($frm['in_category']));
	$tpl->assign('module_cart_preservation', vn($frm['module_cart_preservation']));
	$tpl->assign('module_retail', vn($frm['module_retail']));
	$tpl->assign('module_affilie', vn($frm['module_affilie']));
	$tpl->assign('module_lot', vn($frm['module_lot']));
	$tpl->assign('module_parrain', vn($frm['module_parrain']));
	$tpl->assign('module_cadeau', vn($frm['module_cadeau']));
	$tpl->assign('module_faq', vn($frm['module_faq']));
	$tpl->assign('module_entreprise', vn($frm['module_entreprise']));
	$tpl->assign('module_forum', vn($frm['module_forum']));
	$tpl->assign('module_giftlist', vn($frm['module_giftlist']));
	$tpl->assign('module_socolissimo', vn($frm['module_socolissimo']));
	$tpl->assign('module_icirelais', vn($frm['module_icirelais']));
	$tpl->assign('module_tnt', vn($frm['module_tnt']));
	$tpl->assign('keep_old_orders_intact', vn($frm['keep_old_orders_intact']));
	$tpl->assign('fonctionsconditionnement', file_exists($GLOBALS['fonctionsconditionnement']));
	$tpl->assign('module_conditionnement', vn($frm['module_conditionnement']));
	$tpl->assign('payment_status_decrement_stock', vn($frm['payment_status_decrement_stock']));
	$tpl->assign('keep_old_orders_intact_date', (empty($frm['keep_old_orders_intact_date']) && intval(vn($frm['keep_old_orders_intact']))>1?get_formatted_date(vb($frm['keep_old_orders_intact'])) : vb($frm['keep_old_orders_intact_date'])));
	$tpl->assign('STR_ADMIN_SITES_PREMIUM_MODULE', $GLOBALS['STR_ADMIN_SITES_PREMIUM_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_CAPTCHA_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_CAPTCHA_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_HT', $GLOBALS['STR_HT']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_UPDATE_WEBSITE', $GLOBALS['STR_UPDATE_WEBSITE']);
	$tpl->assign('STR_ADMIN_SITES_TITLE', $GLOBALS['STR_ADMIN_SITES_TITLE']);
	$tpl->assign('STR_ADMIN_SITES_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_GENERAL_PARAMETERS', $GLOBALS['STR_ADMIN_SITES_GENERAL_PARAMETERS']);
	$tpl->assign('STR_ADMIN_SITES_SITE_ACTIVATION', $GLOBALS['STR_ADMIN_SITES_SITE_ACTIVATION']);
	$tpl->assign('STR_ADMIN_SITES_SITE_ACTIVATED', $GLOBALS['STR_ADMIN_SITES_SITE_ACTIVATED']);
	$tpl->assign('STR_ADMIN_SITES_SITE_SUSPENDED', $GLOBALS['STR_ADMIN_SITES_SITE_SUSPENDED']);
	$tpl->assign('STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN2', $GLOBALS['STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN2']);
	$tpl->assign('STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN3', $GLOBALS['STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN3']);
	$tpl->assign('STR_ADMIN_SITES_SITE_NAME', $GLOBALS['STR_ADMIN_SITES_SITE_NAME']);
	$tpl->assign('STR_ADMIN_SITES_SITE_COUNTRY_PRESELECTED', $GLOBALS['STR_ADMIN_SITES_SITE_COUNTRY_PRESELECTED']);
	$tpl->assign('STR_ADMIN_SITES_TEMPLATE_USED', $GLOBALS['STR_ADMIN_SITES_TEMPLATE_USED']);
	$tpl->assign('STR_ADMIN_SITES_PAGE_LINKS_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_PAGE_LINKS_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_SITES_DISPLAY', $GLOBALS['STR_ADMIN_SITES_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_CSS_FILES', $GLOBALS['STR_ADMIN_SITES_CSS_FILES']);
	$tpl->assign('STR_ADMIN_SITES_CSS_FILES_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_CSS_FILES_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_LOGO_URL', $GLOBALS['STR_ADMIN_SITES_LOGO_URL']);
	$tpl->assign('STR_ADMIN_SITES_LOGO_HEADER_DISPLAY', $GLOBALS['STR_ADMIN_SITES_LOGO_HEADER_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_FAVICON', $GLOBALS['STR_ADMIN_SITES_FAVICON']);
	$tpl->assign('STR_ADMIN_DELETE_THIS_FILE', $GLOBALS['STR_ADMIN_DELETE_THIS_FILE']);
	$tpl->assign('STR_ADMIN_SITES_ZOOM_SELECTION', $GLOBALS['STR_ADMIN_SITES_ZOOM_SELECTION']);
	$tpl->assign('STR_ADMIN_SITES_JQZOOM', $GLOBALS['STR_ADMIN_SITES_JQZOOM']);
	$tpl->assign('STR_ADMIN_SITES_CLOUD_ZOOM', $GLOBALS['STR_ADMIN_SITES_CLOUD_ZOOM']);
	$tpl->assign('STR_ADMIN_SITES_LIGHTBOX', $GLOBALS['STR_ADMIN_SITES_LIGHTBOX']);
	$tpl->assign('STR_NONE', $GLOBALS['STR_NONE']);
	$tpl->assign('STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION', $GLOBALS['STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION']);
	$tpl->assign('STR_ADMIN_SITES_JAVASCRIPT_AJAX_ACTIVATE', $GLOBALS['STR_ADMIN_SITES_JAVASCRIPT_AJAX_ACTIVATE']);
	$tpl->assign('STR_ADMIN_SITES_JAVASCRIPT_JQUERY_ACTIVATE', $GLOBALS['STR_ADMIN_SITES_JAVASCRIPT_JQUERY_ACTIVATE']);
	$tpl->assign('STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING', $GLOBALS['STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING']);
	$tpl->assign('STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_UTF8', $GLOBALS['STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_UTF8']);
	$tpl->assign('STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_ISO', $GLOBALS['STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_ISO']);
	$tpl->assign('STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION', $GLOBALS['STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	$tpl->assign('STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS', $GLOBALS['STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS']);
	$tpl->assign('STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_CATEGORY_COUNT_METHOD', $GLOBALS['STR_ADMIN_SITES_CATEGORY_COUNT_METHOD']);
	$tpl->assign('STR_ADMIN_SITES_CATEGORY_COUNT_INDIVIDUAL', $GLOBALS['STR_ADMIN_SITES_CATEGORY_COUNT_INDIVIDUAL']);
	$tpl->assign('STR_ADMIN_SITES_CATEGORY_COUNT_GLOBAL', $GLOBALS['STR_ADMIN_SITES_CATEGORY_COUNT_GLOBAL']);
	$tpl->assign('STR_ADMIN_SITES_CART_POPUP_SIZE', $GLOBALS['STR_ADMIN_SITES_CART_POPUP_SIZE']);
	$tpl->assign('STR_ADMIN_SITES_CART_POPUP_SIZE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_CART_POPUP_SIZE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SECURITY', $GLOBALS['STR_ADMIN_SITES_SECURITY']);
	$tpl->assign('STR_ADMIN_SITES_ADMIN_FORCE_SSL', $GLOBALS['STR_ADMIN_SITES_ADMIN_FORCE_SSL']);
	$tpl->assign('STR_ADMIN_SITES_ADMIN_FORCE_SSL_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_ADMIN_FORCE_SSL_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_HTTPS_TEST', $GLOBALS['STR_ADMIN_SITES_HTTPS_TEST']);
	$tpl->assign('STR_ADMIN_SITES_SESSIONS_DURATION', $GLOBALS['STR_ADMIN_SITES_SESSIONS_DURATION']);
	$tpl->assign('STR_MINUTES', $GLOBALS['strShortMinutes']);
	$tpl->assign('STR_ADMIN_ACTIVATE', $GLOBALS['STR_ADMIN_ACTIVATE']);
	$tpl->assign('STR_ADMIN_DEACTIVATE', $GLOBALS['STR_ADMIN_DEACTIVATE']);
	$tpl->assign('STR_ADMIN_SITES_SESSIONS_DURATION_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SESSIONS_DURATION_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP', $GLOBALS['STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCTS_DISPLAY', $GLOBALS['STR_ADMIN_SITES_PRODUCTS_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCTS_COUNT_IN_MENU', $GLOBALS['STR_ADMIN_SITES_PRODUCTS_COUNT_IN_MENU']);
	$tpl->assign('STR_ADMIN_SITES_THUMBS_SIZE', $GLOBALS['STR_ADMIN_SITES_THUMBS_SIZE']);
	$tpl->assign('STR_ADMIN_SITES_IMAGES_SIZE', $GLOBALS['STR_ADMIN_SITES_IMAGES_SIZE']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCTS_FILTER_DISPLAY', $GLOBALS['STR_ADMIN_SITES_PRODUCTS_FILTER_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_ALLOW_ADD_PRODUCT_IN_LIST_PAGES', $GLOBALS['STR_ADMIN_SITES_ALLOW_ADD_PRODUCT_IN_LIST_PAGES']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY', $GLOBALS['STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_SELECT_MENU', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_SELECT_MENU']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_RADIO_BUTTONS', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_RADIO_BUTTONS']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_CHECKBOX', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_CHECKBOX']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCTS_PER_PAGE', $GLOBALS['STR_ADMIN_SITES_PRODUCTS_PER_PAGE']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCTS_PER_PAGE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_PRODUCTS_PER_PAGE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_ADD_TO_CART_ANIMATION', $GLOBALS['STR_ADMIN_SITES_ADD_TO_CART_ANIMATION']);
	$tpl->assign('STR_ADMIN_SITES_DEFAULT_PRODUCT_PAGE', $GLOBALS['STR_ADMIN_SITES_DEFAULT_PRODUCT_PAGE']);
	$tpl->assign('STR_ADMIN_DELETE_THIS_FILE', $GLOBALS['STR_ADMIN_DELETE_THIS_FILE']);
	$tpl->assign('STR_ADMIN_SITES_TOP_SALES_CONFIGURATION', $GLOBALS['STR_ADMIN_SITES_TOP_SALES_CONFIGURATION']);
	$tpl->assign('STR_ADMIN_SITES_AUTO_TOP_SALES', $GLOBALS['STR_ADMIN_SITES_AUTO_TOP_SALES']);
	$tpl->assign('STR_ADMIN_SITES_CONFIGURED_TOP_SALES', $GLOBALS['STR_ADMIN_SITES_CONFIGURED_TOP_SALES']);
	$tpl->assign('STR_ADMIN_SITES_TOP_SALES_MAX_PRODUCTS', $GLOBALS['STR_ADMIN_SITES_TOP_SALES_MAX_PRODUCTS']);
	$tpl->assign('STR_ADMIN_SITES_LAST_VISITS_MAX_PRODUCTS', $GLOBALS['STR_ADMIN_SITES_LAST_VISITS_MAX_PRODUCTS']);
	$tpl->assign('STR_ADMIN_SITES_AUTO_PROMOTIONS', $GLOBALS['STR_ADMIN_SITES_AUTO_PROMOTIONS']);
	$tpl->assign('STR_ADMIN_SITES_CONFIGURED_PROMOTIONS', $GLOBALS['STR_ADMIN_SITES_CONFIGURED_PROMOTIONS']);
	$tpl->assign('STR_ADMIN_SITES_GLOBAL_DISCOUNT_PERCENTAGE', $GLOBALS['STR_ADMIN_SITES_GLOBAL_DISCOUNT_PERCENTAGE']);
	$tpl->assign('STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS', $GLOBALS['STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS']);
	$tpl->assign('STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY', $GLOBALS['STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY']);
	$tpl->assign('STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_SHORT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_SHORT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_BILLING_HEADER', $GLOBALS['STR_ADMIN_SITES_BILLING_HEADER']);
	$tpl->assign('STR_ADMIN_SITES_BILLING_NUMBER_FORMAT', $GLOBALS['STR_ADMIN_SITES_BILLING_NUMBER_FORMAT']);
	$tpl->assign('STR_ADMIN_SITES_BILLING_NUMBER_FORMAT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_BILLING_NUMBER_FORMAT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_HEADER', $GLOBALS['STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_HEADER']);
	$tpl->assign('STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS', $GLOBALS['STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS']);
	$tpl->assign('STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SMALL_ORDERS', $GLOBALS['STR_ADMIN_SITES_SMALL_ORDERS']);
	$tpl->assign('STR_ADMIN_SITES_SMALL_ORDERS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SMALL_ORDERS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SMALL_ORDERS_LIMIT', $GLOBALS['STR_ADMIN_SITES_SMALL_ORDERS_LIMIT']);
	$tpl->assign('STR_ADMIN_SITES_SMALL_ORDERS_AMOUNT', $GLOBALS['STR_ADMIN_SITES_SMALL_ORDERS_AMOUNT']);
	$tpl->assign('STR_ADMIN_SITES_SMALL_ORDERS_VAT', $GLOBALS['STR_ADMIN_SITES_SMALL_ORDERS_VAT']);
	$tpl->assign('STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED', $GLOBALS['STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED']);
	$tpl->assign('STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_ORDERS_UPDATING_LIMITATION', $GLOBALS['STR_ADMIN_SITES_ORDERS_UPDATING_LIMITATION']);
	$tpl->assign('STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBID', $GLOBALS['STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBID']);
	$tpl->assign('STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_LAST_YEAR', $GLOBALS['STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_LAST_YEAR']);
	$tpl->assign('STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE', $GLOBALS['STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE']);
	$tpl->assign('STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_HEADER', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_HEADER']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_RESELLER_FRANCO_LIMIT', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_RESELLER_FRANCO_LIMIT']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_METHOD', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_METHOD']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_NONE', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_NONE']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_GENERAL', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_GENERAL']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_METHOD_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_METHOD_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT', $GLOBALS['STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART', $GLOBALS['STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_FRANCO_NO_ZONE', $GLOBALS['STR_ADMIN_SITES_DELIVERY_FRANCO_NO_ZONE']);
	$tpl->assign('STR_ADMIN_SITES_VAT_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_VAT_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_SITES_VAT_DISPLAY_MODE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_VAT_DISPLAY_MODE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_VAT_DISPLAY_MODE_IN_ADMIN', $GLOBALS['STR_ADMIN_SITES_VAT_DISPLAY_MODE_IN_ADMIN']);
	$tpl->assign('STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP_EXPLAIN',  $GLOBALS['STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_MODULE', $GLOBALS['STR_ADMIN_SITES_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_ECOTAX_MODULE', $GLOBALS['STR_ADMIN_SITES_ECOTAX_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_VAT_DISPLAY_MODE_HEADER', $GLOBALS['STR_ADMIN_SITES_VAT_DISPLAY_MODE_HEADER']);
	$tpl->assign('STR_ADMIN_SITES_CURRENCIES_MODULE', $GLOBALS['STR_ADMIN_SITES_CURRENCIES_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY', $GLOBALS['STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DEFAULT_CURRENCY', $GLOBALS['STR_ADMIN_SITES_DEFAULT_CURRENCY']);
	$tpl->assign('STR_ADMIN_SITES_DEFAULT_CURRENCY_WARNING', $GLOBALS['STR_ADMIN_SITES_DEFAULT_CURRENCY_WARNING']);
	$tpl->assign('STR_ADMIN_SITES_CURRENCIES_LINK', $GLOBALS['STR_ADMIN_SITES_CURRENCIES_LINK']);
	$tpl->assign('STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE', $GLOBALS['STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_TEXT_EDITOR', $GLOBALS['STR_ADMIN_SITES_TEXT_EDITOR']);
	$tpl->assign('STR_ADMIN_SITES_TEXT_EDITOR_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_TEXT_EDITOR_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_TEXT_EDITOR_FCKEDITOR', $GLOBALS['STR_ADMIN_SITES_TEXT_EDITOR_FCKEDITOR']);
	$tpl->assign('STR_ADMIN_SITES_TEXT_EDITOR_CKEDITOR', $GLOBALS['STR_ADMIN_SITES_TEXT_EDITOR_CKEDITOR']);
	$tpl->assign('STR_ADMIN_SITES_TEXT_EDITOR_NICEDITOR', $GLOBALS['STR_ADMIN_SITES_TEXT_EDITOR_NICEDITOR']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_CONFIGURATION', $GLOBALS['STR_ADMIN_SITES_EMAIL_CONFIGURATION']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_SENDING_ALLOWED', $GLOBALS['STR_ADMIN_SITES_EMAIL_SENDING_ALLOWED']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_SENDING_DEACTIVATE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_EMAIL_SENDING_DEACTIVATE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_CONFIGURATION_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_EMAIL_CONFIGURATION_EXPLAIN']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL']);
	$tpl->assign('STR_MODULE_PREMIUM_MANDATORY_EMAIL', $GLOBALS['STR_MODULE_PREMIUM_MANDATORY_EMAIL']);
	$tpl->assign('STR_ADMIN_SITES_SUPPORT_SENDER_NAME', $GLOBALS['STR_ADMIN_SITES_SUPPORT_SENDER_NAME']);
	$tpl->assign('STR_ADMIN_SITES_SUPPORT_SENDER_NAME_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SUPPORT_SENDER_NAME_EXPLAIN']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_ORDER_MANAGEMENT_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_ORDER_MANAGEMENT_EMAIL']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_CLIENT_SERVICE_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_CLIENT_SERVICE_EMAIL']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_MODULES_POSITIONS', $GLOBALS['STR_ADMIN_SITES_MODULES_POSITIONS']);
	$tpl->assign('STR_ADMIN_SITES_MODULES_POSITIONS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_MODULES_POSITIONS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_DISPLAY_MODE', $GLOBALS['STR_ADMIN_DISPLAY_MODE']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_ADMIN_PLACE', $GLOBALS['STR_ADMIN_PLACE']);
	$tpl->assign('STR_ADMIN_SITES_LEFT', $GLOBALS['STR_ADMIN_SITES_LEFT']);
	$tpl->assign('STR_ADMIN_SITES_RIGHT', $GLOBALS['STR_ADMIN_SITES_RIGHT']);
	$tpl->assign('STR_ADMIN_SITES_BOTTOM', $GLOBALS['STR_ADMIN_SITES_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_TOP', $GLOBALS['STR_ADMIN_SITES_TOP']);
	$tpl->assign('STR_ADMIN_SITES_CENTER_TOP', $GLOBALS['STR_ADMIN_SITES_CENTER_TOP']);
	$tpl->assign('STR_ADMIN_SITES_CENTER_MIDDLE', $GLOBALS['STR_ADMIN_SITES_CENTER_MIDDLE']);
	$tpl->assign('STR_ADMIN_SITES_CENTER_MIDDLE_HOME', $GLOBALS['STR_ADMIN_SITES_CENTER_MIDDLE_HOME']);
	$tpl->assign('STR_ADMIN_SITES_CENTER_BOTTOM', $GLOBALS['STR_ADMIN_SITES_CENTER_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_USER_SHOPS_TOP', $GLOBALS['STR_ADMIN_SITES_USER_SHOPS_TOP']);
	$tpl->assign('STR_ADMIN_SITES_USER_SHOPS_BOTTOM', $GLOBALS['STR_ADMIN_SITES_USER_SHOPS_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_SPONSOR', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_SPONSOR']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_AD_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_AD_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_AD_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_AD_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_MIDDLE', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_MIDDLE']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_LEFT', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_LEFT']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_RIGHT', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_RIGHT']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_HOME', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_HOME']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ADS_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ADS_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ADS_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ADS_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_AD_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_AD_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_ON_HOMEPAGE_ONLY', $GLOBALS['STR_ADMIN_SITES_ON_HOMEPAGE_ONLY']);
	$tpl->assign('STR_ADMIN_SITES_PAYPAL_MODULE', $GLOBALS['STR_ADMIN_SITES_PAYPAL_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_PAYPAL_EMAIL', $GLOBALS['STR_ADMIN_SITES_PAYPAL_EMAIL']);
	$tpl->assign('STR_ADMIN_SITES_MONEYBOOKERS_MODULE', $GLOBALS['STR_ADMIN_SITES_MONEYBOOKERS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_MONEYBOOKERS_EMAIL', $GLOBALS['STR_ADMIN_SITES_MONEYBOOKERS_EMAIL']);
	$tpl->assign('STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD', $GLOBALS['STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD']);
	$tpl->assign('STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_KEKOLI_MODULE', $GLOBALS['STR_ADMIN_SITES_KEKOLI_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_CARRIER_DELAY', $GLOBALS['STR_ADMIN_SITES_DELIVERY_CARRIER_DELAY']);
	$tpl->assign('STR_ADMIN_SITES_ANALYTICS_TAG', $GLOBALS['STR_ADMIN_SITES_ANALYTICS_TAG']);
	$tpl->assign('STR_ADMIN_SITES_ANALYTICS_TAG_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_ANALYTICS_TAG_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_TAG_CLOUD_MODULE', $GLOBALS['STR_ADMIN_SITES_TAG_CLOUD_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE', $GLOBALS['STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_FLASH_SALES_MODULE', $GLOBALS['STR_ADMIN_SITES_FLASH_SALES_MODULE']);
	$tpl->assign('STR_ADMIN_CONTACT_PEEL_FOR_MODULE', $GLOBALS['STR_ADMIN_CONTACT_PEEL_FOR_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_ADVERTISING', $GLOBALS['STR_ADMIN_SITES_ADVERTISING']);
	$tpl->assign('STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE', $GLOBALS['STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_HEADER', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_HEADER']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_DISPLAY', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_DISPLAY_REPLACE', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_DISPLAY_REPLACE']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_DISPLAY_SCROLLING', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_DISPLAY_SCROLLING']);
	$tpl->assign('STR_ADMIN_SITES_RSS_MODULE', $GLOBALS['STR_ADMIN_SITES_RSS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_OPINIONS_MODULE', $GLOBALS['STR_ADMIN_SITES_OPINIONS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_CAPTCHA_ACTIVATION', $GLOBALS['STR_ADMIN_SITES_CAPTCHA_ACTIVATION']);
	$tpl->assign('STR_ADMIN_SITES_CAPTCHA_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_CAPTCHA_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_DIRECT_PARENT', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_DIRECT_PARENT']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_ALL_PARENTS', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_ALL_PARENTS']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_MODULE', $GLOBALS['STR_ADMIN_SITES_STOCKS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT', $GLOBALS['STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT']);
	$tpl->assign('STR_ADMIN_SITES_ALLOW_ORDERS_WITHOUT_STOCKS', $GLOBALS['STR_ADMIN_SITES_ALLOW_ORDERS_WITHOUT_STOCKS']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_BOOKING_SECONDS', $GLOBALS['STR_ADMIN_SITES_STOCKS_BOOKING_SECONDS']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_BOOKING_DEFAULT', $GLOBALS['STR_ADMIN_SITES_STOCKS_BOOKING_DEFAULT']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_BOOKING_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_STOCKS_BOOKING_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_LIMIT_ALERT', $GLOBALS['STR_ADMIN_SITES_STOCKS_LIMIT_ALERT']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS', $GLOBALS['STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_CART_SAVE_MODULE', $GLOBALS['STR_ADMIN_SITES_CART_SAVE_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_RESELLER_MANAGE', $GLOBALS['STR_ADMIN_SITES_RESELLER_MANAGE']);
	$tpl->assign('STR_ADMIN_SITES_RESELLER_MANAGE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_RESELLER_MANAGE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_AFFILIATION_MODULE', $GLOBALS['STR_ADMIN_SITES_AFFILIATION_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_AFFILIATION_COMMISSION', $GLOBALS['STR_ADMIN_SITES_AFFILIATION_COMMISSION']);
	$tpl->assign('STR_ADMIN_SITES_AFFILIATION_LOGO', $GLOBALS['STR_ADMIN_SITES_AFFILIATION_LOGO']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCT_LOTS_MODULE', $GLOBALS['STR_ADMIN_SITES_PRODUCT_LOTS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_SPONSOR_MODULE', $GLOBALS['STR_ADMIN_SITES_SPONSOR_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_SPONSOR_COMMISSION', $GLOBALS['STR_ADMIN_SITES_SPONSOR_COMMISSION']);
	$tpl->assign('STR_ADMIN_SITES_GIFT_CHECKS_MODULE', $GLOBALS['STR_ADMIN_SITES_GIFT_CHECKS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_FAQ_MODULE', $GLOBALS['STR_ADMIN_SITES_FAQ_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_URL_REWRITING_MODULE', $GLOBALS['STR_ADMIN_SITES_URL_REWRITING_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_MICROBUSINESS_MODULE', $GLOBALS['STR_ADMIN_SITES_MICROBUSINESS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_MICROBUSINESS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_MICROBUSINESS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_BIRTHDAY_MODULE', $GLOBALS['STR_ADMIN_SITES_BIRTHDAY_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT', $GLOBALS['STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT']);
	$tpl->assign('STR_ADMIN_SITES_CATEGORIES_PROMOTION', $GLOBALS['STR_ADMIN_SITES_CATEGORIES_PROMOTION']);
	$tpl->assign('STR_ADMIN_SITES_TRADEMARK_PROMOTION', $GLOBALS['STR_ADMIN_SITES_TRADEMARK_PROMOTION']);
	$tpl->assign('STR_ADMIN_SITES_COMPARATOR_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_COMPARATOR_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCT_CONDITIONING_MODULE', $GLOBALS['STR_ADMIN_SITES_PRODUCT_CONDITIONING_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_GOOGLE_FRIENDS_CONNECT_MODULE', $GLOBALS['STR_ADMIN_SITES_GOOGLE_FRIENDS_CONNECT_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_GOOGLE_FRIENDS_CONNECT_SITE_ID', $GLOBALS['STR_ADMIN_SITES_GOOGLE_FRIENDS_CONNECT_SITE_ID']);
	$tpl->assign('STR_ADMIN_SITES_TWITTER_SIGN_IN', $GLOBALS['STR_ADMIN_SITES_TWITTER_SIGN_IN']);
	$tpl->assign('STR_ADMIN_SITES_TWITTER_CONSUMER_KEY', $GLOBALS['STR_ADMIN_SITES_TWITTER_CONSUMER_KEY']);
	$tpl->assign('STR_ADMIN_SITES_TWITTER_CONSUMER_SECRET', $GLOBALS['STR_ADMIN_SITES_TWITTER_CONSUMER_SECRET']);
	$tpl->assign('STR_ADMIN_SITES_TWITTER_OAUTH_CALLBACK', $GLOBALS['STR_ADMIN_SITES_TWITTER_OAUTH_CALLBACK']);
	$tpl->assign('STR_ADMIN_SITES_VACANCY_MODULE', $GLOBALS['STR_ADMIN_SITES_VACANCY_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_VACANCY_MODULE_TYPE', $GLOBALS['STR_ADMIN_SITES_VACANCY_MODULE_TYPE']);
	$tpl->assign('STR_ADMIN_SITES_VACANCY_MODULE_TYPE_ADMIN', $GLOBALS['STR_ADMIN_SITES_VACANCY_MODULE_TYPE_ADMIN']);
	$tpl->assign('STR_ADMIN_SITES_VACANCY_MODULE_TYPE_SUPPLIER', $GLOBALS['STR_ADMIN_SITES_VACANCY_MODULE_TYPE_SUPPLIER']);
	$tpl->assign('STR_ADMIN_SITES_VACANCY_ADMIN_MESSAGE', $GLOBALS['STR_ADMIN_SITES_VACANCY_ADMIN_MESSAGE']);
	$tpl->assign('STR_ADMIN_SITES_FORUM_MODULE', $GLOBALS['STR_ADMIN_SITES_FORUM_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_GIFTS_LIST', $GLOBALS['STR_ADMIN_SITES_GIFTS_LIST']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_MODULE', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_FOID', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_FOID']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_SHA1_KEY', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_SHA1_KEY']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_URL_KO', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_URL_KO']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_FORWARDINGCHARGES', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_FORWARDINGCHARGES']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_EXPEDITOR_MODULE', $GLOBALS['STR_ADMIN_SITES_EXPEDITOR_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_ICI_RELAIS_MODULE', $GLOBALS['STR_ADMIN_SITES_ICI_RELAIS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_TNT_MODULE', $GLOBALS['STR_ADMIN_SITES_TNT_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_TNT_USERNAME', $GLOBALS['STR_ADMIN_SITES_TNT_USERNAME']);
	$tpl->assign('STR_ADMIN_SITES_TNT_PASSWORD', $GLOBALS['STR_ADMIN_SITES_TNT_PASSWORD']);
	$tpl->assign('STR_ADMIN_SITES_TNT_ACCOUNT_NUMBER', $GLOBALS['STR_ADMIN_SITES_TNT_ACCOUNT_NUMBER']);
	$tpl->assign('STR_ADMIN_SITES_TNT_EXPEDITION_DELAY', $GLOBALS['STR_ADMIN_SITES_TNT_EXPEDITION_DELAY']);
	$tpl->assign('STR_ADMIN_SITES_SIPS_MODULE', $GLOBALS['STR_ADMIN_SITES_SIPS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_SIPS_CERTIFICATE', $GLOBALS['STR_ADMIN_SITES_SIPS_CERTIFICATE']);
	$tpl->assign('STR_ADMIN_SITES_SIPS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SIPS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SPPLUS_MODULE', $GLOBALS['STR_ADMIN_SITES_SPPLUS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_SPPLUS_EXTERNAL_URL', $GLOBALS['STR_ADMIN_SITES_SPPLUS_EXTERNAL_URL']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_MODULE', $GLOBALS['STR_ADMIN_SITES_PAYBOX_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_CGI', $GLOBALS['STR_ADMIN_SITES_PAYBOX_CGI']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_SITE', $GLOBALS['STR_ADMIN_SITES_PAYBOX_SITE']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_RANG', $GLOBALS['STR_ADMIN_SITES_PAYBOX_RANG']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_ID', $GLOBALS['STR_ADMIN_SITES_PAYBOX_ID']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_TEST_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_PAYBOX_TEST_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_CERTIFICATE', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_CERTIFICATE']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_TEST', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_TEST']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_ID', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_ID']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_OCCURENCES', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_OCCURENCES']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_DAYS_BETWEEN_OCCURENCES', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_DAYS_BETWEEN_OCCURENCES']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_TEST_MODE', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_TEST_MODE']);
	$tpl->assign('STR_ADMIN_SITES_PARTNERS_MODULE', $GLOBALS['STR_ADMIN_SITES_PARTNERS_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_PARTNERS_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_PARTNERS_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_SITES_PARTNERS_INDIVIDUAL', $GLOBALS['STR_ADMIN_SITES_PARTNERS_INDIVIDUAL']);
	$tpl->assign('STR_ADMIN_SITES_PARTNERS_GLOBAL', $GLOBALS['STR_ADMIN_SITES_PARTNERS_GLOBAL']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_MODULE', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_ADMIN', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_ADMIN']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_PAGE_LINK', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_PAGE_LINK']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_CONNECT', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_CONNECT']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_APPID', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_APPID']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_SECRET', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_SECRET']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_BASEURL', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_BASEURL']);
	return $tpl->fetch();
}

/**
 * Supprime le site spécifié par $id
 *
 * @param integer $id
 * @return
 *
function supprime_site($id)
{
	$qid = query("SELECT string AS nom
		FROM peel_configuration
		WHERE technical_code=nom_" . $_SESSION['session_langue'] . " AND site_id=" . intval($id));
	$col = fetch_assoc($qid);

	// Efface le site
	query("DELETE FROM peel_configuration WHERE site_id='" . intval($id) . "'");

	// Efface ce site de la table produits_site
	query("DELETE FROM peel_commandes WHERE ecom_id='" . intval($id) . "'");
	query("DELETE FROM peel_commandes_cadeaux WHERE site_id='" . intval($id) . "'");
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_SITES_MSG_DELETED_OK'], String::html_entity_decode_if_needed($col['nom']))))->fetch();
}

/**
 * create_or_update_site()
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function create_or_update_site($id, $frm)
{
	// Définition de devise_defaut ici car ne doit pas être égale à 0
	if (empty($frm['devise_defaut'])) {
		$frm['devise_defaut'] = 1;
	}
	if (intval(vn($frm['keep_old_orders_intact']))>1 && empty($frm['keep_old_orders_intact_date'])) {
		// Par défaut : date du jour
		$frm['keep_old_orders_intact_date'] = get_formatted_date();
		$frm['keep_old_orders_intact'] = (intval(vn($frm['keep_old_orders_intact']))>1? strtotime(get_mysql_date_from_user_input($frm['keep_old_orders_intact_date'])) : intval(vn($frm['keep_old_orders_intact'])));
	}
	if(isset($frm['template_directory']) && !file_exists($GLOBALS['dirroot'] . "/modeles/" . vb($frm['template_directory']))) {
		unset($frm['template_directory']);
	}
	// Traitement des checkbox pour mettre valeur dans $frm si pas coché	
	foreach(array('enable_prototype', 'enable_jquery') as $this_key) {
		$frm[$this_key] = vn($frm[$this_key]);
	}
	// Met à jour la table de configuration
	foreach($frm as $this_key => $this_value) {
		if(!in_array($this_key, array('token', 'keep_old_orders_intact_date'))) {
			foreach(array('module_', 'display_mode_', 'etat_', 'position_', 'home_') as $this_begin) {
				if(String::substr($this_key, 0, String::strlen($this_begin)) == $this_begin && is_numeric(String::substr($this_key, String::strlen($this_begin)))) {
					$skip = true;
				}
			}
			if(empty($skip)) {
				set_configuration_variable(array('technical_code' => $this_key, 'string' => $this_value, 'origin' => 'sites.php'), true);
			}
			unset($skip);
		}
	}

	$modules = get_modules_array();
	foreach(array_keys($modules) as $key) {
		$sql = "UPDATE peel_modules
			SET location='" . nohtml_real_escape_string(vb($frm['module_' . $key])) . "'
				, display_mode='" . nohtml_real_escape_string($frm['display_mode_' . $key]) . "'
				, position='" . intval($frm['position_' . $key]) . "'
				, etat=" . (empty($frm['etat_' . $key]) ? 0 : 1) . "
				, in_home=" . (empty($frm['home_' . $key]) ? 0 : 1) . "
			WHERE id='" . intval($key) . "'";
		query($sql);
	}
	return true;
}

/**
 * affiche_liste_site()
 *
 * @return
 */
function affiche_liste_site()
{
	return affiche_formulaire_modif_site(1, $GLOBALS['frm']);
	/*
	$output .= '
<table class="main_table">
	<tr>
		<td class="entete" colspan="4">' . $GLOBALS['STR_ADMIN_SITES_LIST_TITLE'] . '</td>
	</tr>';
	$all_site_names = get_all_site_names();
	if (count($all_site_names) == 0) {
		$output .= '<tr><td><b>' . $GLOBALS['STR_ADMIN_SITES_LIST_NOTHING_FOUND'] . '</b></td></tr>';
	} else {
		$output .= '
	<tr>
		<td class="menu" width="50">' . $GLOBALS['STR_ADMIN_ACTION'] . '}</td>
		<td class="menu" width="80">' . $GLOBALS['STR_ADMIN_ID'] . '</td>
		<td class="menu">' . $GLOBALS['STR_ADMIN_SITES_SITE_NAME'] . '</td>
		<td class="menu">' . $GLOBALS['STR_MODULE_PREMIUM_URL_WEBSITE'] . '</td>
	</tr>';
		$i = 0;
		foreach ($all_site_names as $id => $nom) {
			$output .= tr_rollover($i, true) . '
		<td class="center"><a title="' . $GLOBALS['STR_ADMIN_SITES_LIST_MODIFY'] . '" href="' . get_current_url(false) . '?mode=modif&id=' . $id . '"><img src="' . $GLOBALS['administrer_url'] . '/images/b_edit.png" alt="' . $GLOBALS['STR_ADMIN_SITES_LIST_MODIFY'] . '" /></a></td>
		<td class="label center">' . $id . '</td>
		<td style="padding-left:10px">' . $nom . '</td>
		<td class="left" style="padding-left:10px">' . vb($url) . '</td>
	</tr>';
			$i++;
		}
		$output .= "
</table>";
	}
	return $output; */
}

/**
 * Supprime une favicon
 *
 * @param integer $id
 * @param mixed $file
 * @return
 */
function supprime_favicon($id, $file)
{
	set_configuration_variable(array('technical_code' => 'favicon', 'string' => '', 'origin' => 'sites.php'), true);
	return delete_uploaded_file_and_thumbs($file);
}

/**
 * Supprime l'image par défaut utilisée sur le site
 *
 * @param integer $id
 * @param string $file
 * @return
 */
function supprime_default_picture($id, $file)
{
	set_configuration_variable(array('technical_code' => 'default_picture', 'string' => '', 'origin' => 'sites.php'), true);
	return delete_uploaded_file_and_thumbs($file);
}

?>
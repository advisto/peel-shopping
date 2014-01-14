<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: modules_handler.php 39495 2014-01-14 11:08:09Z sdelaporte $

if (!defined('IN_PEEL')) {
    die();
}

/**
 * is_module_url_rewriting_active()
 *
 * @return
 */
function is_module_url_rewriting_active() {
    if (vn($GLOBALS['site_parameters']['module_url_rewriting']) == 1 && file_exists($GLOBALS['rewritefile'])) {
        // Module présent ET sélectionné
        return true;
    } else {
        // Module absent, ou non sélectionné
        return false;
    }
}

/**
 * is_module_ecotaxe_active()
 *
 * @return
 */
function is_module_ecotaxe_active() {
    if (vn($GLOBALS['site_parameters']['module_ecotaxe']) == 1 && file_exists($GLOBALS['dirroot'] . "/modules/ecotaxe/fonctions.php")) {
        // Module présent ET sélectionné
        return true;
    } else {
        // Module absent, ou non sélectionné
        return false;
    }
}

/**
 * is_rollover_module_active()
 *
 * @return
 */
function is_rollover_module_active() {
    if (vn($GLOBALS['site_parameters']['module_rollover']) == 1 && file_exists($GLOBALS['fonctionsmenus'])) {
        // Module présent ET sélectionné
        return true;
    } else {
        // Module absent, ou non sélectionné
        return false;
    }
}

/**
 * is_captcha_module_active()
 *
 * @return
 */
function is_captcha_module_active() {
    if (vn($GLOBALS['site_parameters']['module_captcha']) == 1 && file_exists($GLOBALS['fonctionscaptcha'])) {
        // Module présent ET sélectionné
        return true;
    } else {
        // Module absent, ou non sélectionné
        return false;
    }
}

/**
 * is_best_seller_module_active()
 *
 * @return
 */
function is_best_seller_module_active() {
    return file_exists($GLOBALS['fonctionsbestseller']);
}

/**
 * is_module_precedent_suivant_active()
 *
 * @return
 */
function is_module_precedent_suivant_active() {
    if (vn($GLOBALS['site_parameters']['module_precedent_suivant']) == '1' && file_exists($GLOBALS['fonctionsprecedentsuivant'])) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_module_wanewsletter_active()
 *
 * @return
 */
function is_module_wanewsletter_active() {
    return (!IN_INSTALLATION && file_exists($GLOBALS['fonctionswanewsletter']));
}

/**
 * is_module_blog_active()
 *
 * @return
 */
function is_module_blog_active() {
    return file_exists($GLOBALS['fonctionsblog']);
}

/**
 * is_module_gift_checks_active()
 *
 * @return
 */
function is_module_gift_checks_active() {
    if (vn($GLOBALS['site_parameters']['module_cadeau']) == 1 && file_exists($GLOBALS['fonctionscheck'])) {
        // Module présent ET sélectionné
        return true;
    } else {
        // Module absent, ou non sélectionné
        return false;
    }
}

/**
 * is_module_tagcloud_active()
 *
 * @return
 */
function is_module_tagcloud_active() {
    if (vn($GLOBALS['site_parameters']['module_nuage']) == 1 && file_exists($GLOBALS['fonctionstagcloud'])) {
        // Module présent ET sélectionné
        return true;
    } else {
        // Module absent, ou non sélectionné
        return false;
    }
}

/**
 * is_module_banner_active()
 *
 * @return
 */
function is_module_banner_active() {
    if (vn($GLOBALS['site_parameters']['module_pub']) == 1 && file_exists($GLOBALS['fonctionsbanner'])) {
        // Module présent ET sélectionné
        return true;
    } else {
        // Module absent, ou non sélectionné
        return false;
    }
}

/**
 * is_module_avis_active()
 *
 * @return
 */
function is_module_avis_active() {
    if (vn($GLOBALS['site_parameters']['module_avis']) == 1 && file_exists($GLOBALS['fonctionsavis'])) {
        // Module présent ET sélectionné
        return true;
    } else {
        // Module absent, ou non sélectionné
        return false;
    }
}

/**
 * is_module_rss_active()
 *
 * @return
 */
function is_module_rss_active() {
    if (vn($GLOBALS['site_parameters']['module_rss']) == '1' && file_exists($GLOBALS['fonctionsrss'])) {
        // Module présent ET sélectionné
        return true;
    } else {
        // Module absent, ou non sélectionné
        return false;
    }
}

/**
 * is_module_pensebete_active()
 *
 * @return
 */
function is_module_pensebete_active() {
    if (file_exists($GLOBALS['fonctionspensebete'])) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_cart_popup_module_active()
 *
 * @return
 */
function is_cart_popup_module_active() {
    if (file_exists($GLOBALS['fonctionscartpoup'])) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_module_direaunami_active()
 *
 * @return
 */
function is_module_direaunami_active() {
    if (file_exists($GLOBALS['dirroot'] . "/modules/direaunami/direaunami.php")) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_module_faq_active()
 *
 * @return
 */
function is_module_faq_active() {
    if (vn($GLOBALS['site_parameters']['module_faq']) == '1' && file_exists($GLOBALS['dirroot'] . "/modules/faq/faq.php")) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_module_comparateur_active()
 *
 * @return
 */
function is_module_comparateur_active() {
    return file_exists($GLOBALS['fonctionscomparateur']);
}

/**
 * is_module_genere_pdf_active()
 *
 * @return
 */
function is_module_genere_pdf_active() {
    return file_exists($GLOBALS['fonctionsgenerepdf']);
}

/**
 * is_module_factures_html_active()
 *
 * @return
 */
function is_module_factures_html_active() {
    return file_exists($GLOBALS['dirroot'] . '/modules/factures/commande_html.php');
}

/**
 * is_module_export_clients_active()
 *
 * @return
 */
function is_module_export_clients_active() {
    return file_exists($GLOBALS['dirroot'] . "/modules/export/administrer/export_clients.php");
}

/**
 * is_module_export_ventes_active()
 *
 * @return
 */
function is_module_export_ventes_active() {
    return file_exists($GLOBALS['dirroot'] . "/modules/export/administrer/export_ventes.php");
}

/**
 * is_module_export_livraisons_active()
 *
 * @return
 */
function is_module_export_livraisons_active() {
    return file_exists($GLOBALS['dirroot'] . "/modules/export/administrer/export_livraisons.php");
}

/**
 * is_module_profile_active()
 *
 * @return
 */
function is_module_profile_active() {
    return file_exists($GLOBALS['fonctionsprofile']);
}

/**
 * is_module_picking_active()
 *
 * @return
 */
function is_module_picking_active() {
    return file_exists($GLOBALS['dirroot'] . "/modules/picking/administrer/picking.php");
}

/**
 * is_module_marge_active()
 *
 * @return
 */
function is_module_marge_active() {
    return file_exists($GLOBALS['dirroot'] . "/modules/marges/administrer/marges.php");
}

/**
 * is_module_forum_active()
 *
 * @return
 */
function is_module_forum_active() {
    if (vn($GLOBALS['site_parameters']['module_forum']) == '1' && file_exists($GLOBALS['dirroot'] . "/modules/forum/lang/".$_SESSION['session_langue'].'.php') && (empty($GLOBALS['site_parameters']['forum_allowed_langs_array']) || in_array($_SESSION['session_langue'], $GLOBALS['site_parameters']['forum_allowed_langs_array']))) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_giftlist_module_active()
 *
 * @return
 */
function is_giftlist_module_active() {
    if (vn($GLOBALS['site_parameters']['module_giftlist']) == '1' && file_exists($GLOBALS['dirroot'] . "/modules/listecadeau/fonctions.php")) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_advanced_search_active()
 *
 * @return
 */
function is_advanced_search_active() {
    return file_exists($GLOBALS['fonctionssearch']);
}

/**
 * is_flash_sell_module_active()
 *
 * @return
 */
function is_flash_sell_module_active() {
    return file_exists($GLOBALS['dirroot'] . "/modules/flash/flash.php");
}

/**
 * is_stock_advanced_module_active()
 *
 * @return
 */
function is_stock_advanced_module_active() {
    return file_exists($GLOBALS['fonctionsstock_advanced']);
}

/**
 * is_lexique_module_active()
 *
 * @return
 */
function is_lexique_module_active() {
    return file_exists($GLOBALS['fonctionslexique']);
}

/**
 * is_attributes_module_active()
 *
 * @return
 */
function is_attributes_module_active() {
    return file_exists($GLOBALS['fonctionsattributs']);
}

/**
 * is_category_promotion_module_active()
 *
 * @return
 */
function is_category_promotion_module_active() {
    return file_exists($GLOBALS['fonctionscatpromotions']);
}

/**
 * is_marque_promotion_module_active()
 *
 * @return
 */
function is_marque_promotion_module_active() {
    return file_exists($GLOBALS['fonctionsmarquepromotions']);
}

/**
 * is_lot_module_active()
 *
 * @return
 */
function is_lot_module_active() {
    return (vn($GLOBALS['site_parameters']['module_lot']) == 1 && file_exists($GLOBALS['fonctionslot']));
}

/**
 * is_birthday_module_active()
 *
 * @return
 */
function is_birthday_module_active() {
    return file_exists($GLOBALS['fonctionsbirthday']);
}

/**
 * is_gifts_module_active()
 *
 * @return
 */
function is_gifts_module_active() {
    return file_exists($GLOBALS['fonctionsgift']);
}

/**
 * is_good_clients_module_active()
 *
 * @return
 */
function is_good_clients_module_active() {
    return file_exists($GLOBALS['fonctionsgoodclients']);
}

/**
 * is_thumbs_module_active()
 *
 * @return
 */
function is_thumbs_module_active() {
    return file_exists($GLOBALS['fonctionsthumbs']);
}

/**
 * is_devises_module_active()
 *
 * @return
 */
function is_devises_module_active() {
    return (vn($GLOBALS['site_parameters']['module_devise']) == 1 && file_exists($GLOBALS['fonctionsdevises']));
}

/**
 * display_prices_with_taxes_active()
 *
 * @return
 */
function display_prices_with_taxes_active() {
    if (vn($GLOBALS['site_parameters']['display_prices_with_taxes']) == '0' || (is_reseller_module_active() && is_reseller() && !empty($GLOBALS['site_parameters']['force_display_reseller_prices_without_taxes']))) {
        return false;
    } else {
        return true;
    }
}

/**
 * is_download_module_active()
 *
 * @return
 */
function is_download_module_active() {
    return file_exists($GLOBALS['fonctionsdownload']);
}

/**
 * is_groups_module_active()
 *
 * @return
 */
function is_groups_module_active() {
    return file_exists($GLOBALS['fonctionsgroups']);
}

/**
 * is_parrainage_module_active()
 *
 * @return
 */
function is_parrainage_module_active() {
    return (vb($GLOBALS['site_parameters']['module_parrain']) == 1 && file_exists($GLOBALS['fonctionsparrain']));
}

/**
 * is_reseller_module_active()
 *
 * @return
 */
function is_reseller_module_active() {
    return (vb($GLOBALS['site_parameters']['module_retail']) == 1 && file_exists($GLOBALS['fonctionsreseller']));
}

/**
 * is_affiliate_module_active()
 *
 * @return
 */
function is_affiliate_module_active() {
    return (vn($GLOBALS['site_parameters']['module_affilie']) == 1 && file_exists($GLOBALS['fonctionsaffiliate']));
}

/**
 * is_stats_module_active()
 *
 * @return
 */
function is_stats_module_active() {
    return file_exists($GLOBALS['fonctionsstats']);
}

/**
 * is_expeditor_module_active()
 *
 * @return
 */
function is_expeditor_module_active() {
    return file_exists($GLOBALS['fonctionsexpeditor']);
}

/**
 * is_duplicate_module_active()
 *
 * @return
 */
function is_duplicate_module_active() {
    return file_exists($GLOBALS['fonctionsduplicate']);
}

/**
 * is_welcome_ad_module_active()
 *
 * @return
 */
function is_welcome_ad_module_active() {
    return file_exists($GLOBALS['fonctionswelcomead']);
}

/**
 * is_micro_entreprise_module_active()
 *
 * @return
 */
function is_micro_entreprise_module_active() {
    return (vn($GLOBALS['site_parameters']['module_entreprise']) == 1 && file_exists($GLOBALS['fonctionsmicro']));
}

/**
 * display_prices_with_taxes_in_admin()
 *
 * @return
 */
function display_prices_with_taxes_in_admin() {
    if (vn($GLOBALS['site_parameters']['display_prices_with_taxes_in_admin']) == '0') {
        return false;
    } else {
        return true;
    }
}

/**
 * is_chart_module_active()
 *
 * @return
 */
function is_chart_module_active() {
    return file_exists($GLOBALS['fonctionschart']);
}

/**
 * is_socolissimo_module_active()
 *
 * @return
 */
function is_socolissimo_module_active() {
    return (vn($GLOBALS['site_parameters']['module_socolissimo']) == 1 && file_exists($GLOBALS['fonctionssocolissimo']));
}

/**
 * is_icirelais_module_active()
 *
 * @return
 */
function is_icirelais_module_active() {
    return (vn($GLOBALS['site_parameters']['module_icirelais']) == 1 && file_exists($GLOBALS['fonctionsicirelais']));
}

/**
 * is_payment_by_product_module_active()
 *
 * @return
 */
function is_payment_by_product_module_active() {
    if (file_exists($GLOBALS['fonctionspaymentbyproduct'])) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_page_perso_module_active()
 *
 * @return
 */
function is_espace_perso_module_active() {
    if (file_exists($GLOBALS['fonctionspageperso'])) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_butterflive_module_active()
 *
 * @return
 */
function is_butterflive_module_active() {
    if (file_exists($GLOBALS['fonctionsbutterflive'])) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_payback_module_active()
 *
 * @return
 */
function is_payback_module_active() {
    if (file_exists($GLOBALS['fonctionspayback'])) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_autosend_module_active()
 *
 * @return
 */
function is_autosend_module_active() {
    return (vn($GLOBALS['site_parameters']['module_autosend']) == 1);
}

/**
 * is_user_alerts_module_active()
 *
 * @return
 */
function is_user_alerts_module_active() {
    return file_exists($GLOBALS['fonctionsuser_alerts']);
}

/**
 * is_annonce_module_active()
 *
 * @return
 */
function is_annonce_module_active() {
    return file_exists($GLOBALS['fonctionsannonces']);
}

/**
 * is_abonnement_module_active()
 *
 * @return
 */
function is_abonnement_module_active() {
    return file_exists($GLOBALS['fonctionsabonnement']);
}

/**
 * is_last_views_module_active()
 *
 * @return
 */
function is_last_views_module_active() {
    return file_exists($GLOBALS['fonctionslastviews']);
}

/**
 * is_devis_module_active()
 *
 * @return
 */
function is_devis_module_active(){
	 return file_exists($GLOBALS['fonctionsdevis']);
}

/**
 * is_telechargement_module_active()
 *
 * @return
 */
function is_telechargement_module_active(){
	 return file_exists($GLOBALS['fonctionstelechargement']);
}

/**
 * is_references_module_active()
 *
 * @return
 */
function is_references_module_active(){
	 return file_exists($GLOBALS['fonctionsreferences']);
}

/**
 * is_partenaires_module_active()
 *
 * @return
 */
function is_partenaires_module_active(){
	 return file_exists($GLOBALS['fonctionspartenaires']);
}

/**
 * is_reseller_map_module_active()
 *
 * @return
 */
function is_reseller_map_module_active(){
	 return file_exists($GLOBALS['fonctionsresellermap']);
}

/**
 * is_map_module_active()
 *
 * @return
 */
function is_map_module_active(){
	 return file_exists($GLOBALS['fonctionsmap']);
}

/**
 * is_carrousel_module_active()
 *
 * @return
 */
function is_carrousel_module_active() {
    return file_exists($GLOBALS['fonctionscarrousel']);
}

/**
 * is_facebook_module_active()
 *
 * @return
 */
function is_facebook_module_active() {
    return file_exists($GLOBALS['fonctionsfacebook']);
}

/**
 * is_module_vacances_active()
 *
 * @return
 */
function is_module_vacances_active() {
    if (vn($GLOBALS['site_parameters']['module_vacances']) == '1' && file_exists($GLOBALS['fonctionsvacances'])) {
        // Module présent
        return true;
    } else {
        // Module absent
        return false;
    }
}

/**
 * is_cart_preservation_module_active()
 *
 * @return
 */
function is_cart_preservation_module_active() {
    return (vn($GLOBALS['site_parameters']['module_cart_preservation']) == 1 && file_exists($GLOBALS['fonctionscartpreservation']));
}

/**
 * is_vitrine_module_active()
 *
 * @return
 */
function is_vitrine_module_active() {
    return file_exists($GLOBALS['fonctionsvitrine']);
}

/**
 * is_commerciale_module_active()
 *
 * @return
 */
function is_commerciale_module_active() {
    return file_exists($GLOBALS['fonctionscommerciale']);
}

/**
 * is_webmail_module_active()
 *
 * @return
 */
function is_webmail_module_active() {
	return file_exists($GLOBALS['fonctionswebmail']);
}

/**
 * is_module_vacances_active()
 *
 * @return
 */
function is_crons_module_active() {
	return file_exists($GLOBALS['fonctionscrons']);
}

/**
 * is_kekoli_module_active()
 *
 * @return
 */
function is_kekoli_module_active() {
    return file_exists($GLOBALS['fonctionskekoli']);
}

/**
 * is_module_ariane_panier_active()
 *
 * @return
 */
function is_module_ariane_panier_active() {
    return file_exists($GLOBALS['fonctionsarianepanier']);
}

/**
 * is_module_rue_du_commerce()
 *
 * @return
 */
function is_module_rue_du_commerce() {
    return file_exists($GLOBALS['fonctionsrueducommerce']);
}

/**
 * is_facebook_connect_module_active()
 *
 * @return
 */
function is_facebook_connect_module_active() {
    return (vn($GLOBALS['site_parameters']['facebook_connect']) == 1 && file_exists($GLOBALS['fonctionfacebookconnect']));
}
/**
 * is_clients_module_active()
 *
 * @return
 */
function is_clients_module_active() {
    return (file_exists($GLOBALS['fonctionsclients']));
}
/**
 * is_photodesk_module_active()
 *
 * @return
 */
function is_photodesk_module_active() {
    return (file_exists($GLOBALS['fonctionsphotodesk']));
}
/**
 * is_phone_cti_module_active()
 *
 * @return
 */
function is_phone_cti_module_active() {
    return file_exists($GLOBALS['fonctionsphonecti']);
}

/**
 * is_openid_module_active()
 *
 * @return
 */
function is_openid_module_active() {
    return file_exists($GLOBALS['fonctionsopenid']);
}

/**
 * is_googlefriendconnect_module_active()
 *
 * @return
 */
function is_googlefriendconnect_module_active() {
    return (vn($GLOBALS['site_parameters']['googlefriendconnect']) == 1 && file_exists($GLOBALS['fonctionsgooglefriendconnect']));
}

/**
 * is_sign_in_twitter_module_active()
 *
 * @return
 */
function is_sign_in_twitter_module_active() {
    return (vn($GLOBALS['site_parameters']['sign_in_twitter']) == 1 && file_exists($GLOBALS['fonctionssignintwitter']));
}

/**
 * is_relance_avance_module_active()
 *
 * @return
 */
function is_relance_avance_module_active() {
    return file_exists($GLOBALS['fonctionrelance_avance']);
}

/**
 * is_spam_module_active()
 *
 * @return
 */
function is_spam_module_active() {
    return file_exists($GLOBALS['fonctionsspam']);
}

/**
 * is_accounting_module_active()
 *
 * @return
 */
function is_accounting_module_active() {
    return file_exists($GLOBALS['dirroot'] . '/modules/accounting/administrer/fonctions.php');
}

/**
 * is_fianet_module_active()
 *
 * @return
 */
function is_fianet_module_active() {
    return file_exists($GLOBALS['fonctionsfianet']);
}

/**
 * is_fianet_sac_module_active()
 *
 * @return
 */
function is_fianet_sac_module_active() {
    return file_exists($GLOBALS['fonctionsfianet_sac']);
}

/**
 * is_iphone_ads_module_active()
 *
 * @return
 */
function is_iphone_ads_module_active() {
    return file_exists($GLOBALS['dirroot'] . '/modules/iphone-ads/ads.php');
}

/**
 * Ce module permet de gérer les stocks et les quantités des produits de manière individualisée
 * alors que les produits sont rentrés avec un conditionnement déterminé
 *
 * @return
 */
function is_conditionnement_module_active() {
    if (vn($GLOBALS['site_parameters']['module_conditionnement']) == 1 && file_exists($GLOBALS['fonctionsconditionnement'])) {
        // Module présent ET sélectionné
        return true;
    } else {
        // Module absent, ou non sélectionné
        return false;
    }
}

/**
 * is_tnt_module_active()
 *
 * @return
 */
function is_tnt_module_active()
{
	return (vn($GLOBALS['site_parameters']['module_tnt']) == 1 && file_exists($GLOBALS['fonctionstnt']));
}

/**
 * is_nexway_module_active()
 *
 * @return
 */
function is_nexway_module_active() {
    return file_exists($GLOBALS['dirroot'] . '/modules/nexway/fonctions.php');
}


?>
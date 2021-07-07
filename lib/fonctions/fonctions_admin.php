<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	|
// +----------------------------------------------------------------------+
// $Id: fonctions_admin.php 67425 2021-06-28 12:27:13Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
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
 * get_admin_menu()
 *
 * @return
 */
function get_admin_menu()
{
	if (IN_INSTALLATION) {
		$GLOBALS['main_menu_items']['install'] = array($GLOBALS['wwwroot'] . '/installation/' => $GLOBALS['STR_INSTALLATION']);
		$GLOBALS['menu_items']['install'][get_url('/installation/index.php')] = $GLOBALS['STR_ADMIN_INSTALL_STEP1_TITLE'];
		$GLOBALS['menu_items']['install'][get_url('/installation/bdd.php')] = $GLOBALS['STR_ADMIN_INSTALL_STEP2_TITLE'];
		$GLOBALS['menu_items']['install'][get_url('/installation/choixbase.php')] = $GLOBALS['STR_ADMIN_INSTALL_STEP3_TITLE'];
		$GLOBALS['menu_items']['install'][get_url('/installation/verifdroits.php')] = $GLOBALS['STR_ADMIN_INSTALL_STEP4_TITLE'];
		$GLOBALS['menu_items']['install'][get_url('/installation/configuration.php')] = $GLOBALS['STR_ADMIN_INSTALL_STEP5_TITLE'];
		$GLOBALS['menu_items']['install'][get_url('/installation/fin.php')] = $GLOBALS['STR_ADMIN_INSTALL_STEP6_TITLE'];
	} else {
		$GLOBALS['main_menu_items']['home'] = array($GLOBALS['administrer_url'] . '/' => $GLOBALS["STR_ADMIN_MENU_HOME_TITLE"]);
		$GLOBALS['menu_items']['home'][$GLOBALS['administrer_url'] . '/'] = $GLOBALS["STR_ADMIN_MENU_HOME_BACK"];
		$GLOBALS['menu_items']['home'][$GLOBALS['wwwroot'] . '/'] = $GLOBALS["STR_ADMIN_MENU_HOME_FRONT"];
		if (a_priv('admin_white_label,admin_manage,admin_communication,admin_finance', true)) {
			if (a_priv('admin_white_label,admin_manage', true)) {
				$this_url = $GLOBALS['administrer_url'] . '/sites.php';
			} else {
				$this_url = '#';
			}
			$GLOBALS['main_menu_items']['manage'] = array($this_url => $GLOBALS["STR_ADMIN_MENU_MANAGE_TITLE"]);
		}
		if (a_priv('admin_manage', true)) {
			$GLOBALS['menu_items']['manage']['manage_general'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_TITLE"];
			if (a_priv('admin_manage', true, true)) {
				$GLOBALS['menu_items']['manage_general'][$GLOBALS['administrer_url'] . '/sites.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_SITES"];
				$GLOBALS['menu_items']['manage_general'][$GLOBALS['administrer_url'] . '/configuration.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_CONFIGURATION"];
			}
			$GLOBALS['menu_items']['manage_general'][$GLOBALS['administrer_url'] . '/societe.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_SOCIETE"];
			$GLOBALS['menu_items']['manage_general'][$GLOBALS['administrer_url'] . '/langues.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_LANGUES"];
			$GLOBALS['menu_items']['manage_general'][$GLOBALS['administrer_url'] . '/clean_folders.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_CLEAN_FOLDERS"];
		}
		if (a_priv('admin_manage,admin_finance', true) && vb($GLOBALS['site_parameters']['website_type']) != 'showcase') {
			// Dans le cas d'un site vitrine, on ne souhaite pas afficher le lien vers l'administration des moyens de paiement.
			$GLOBALS['menu_items']['manage']['manage_payments'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_PAYMENT_AND_TAXES"];
			$GLOBALS['menu_items']['manage_payments'][$GLOBALS['administrer_url'] . '/paiement.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_PAYMENT"];
			$GLOBALS['menu_items']['manage_payments'][$GLOBALS['administrer_url'] . '/tva.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_TVA"];
			$GLOBALS['menu_items']['manage_payments'][$GLOBALS['administrer_url'] . '/statut_paiement.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PAYMENT_STATUS"];
		}
		if (a_priv('admin_manage,admin_finance', true) && vb($GLOBALS['site_parameters']['website_type']) != 'showcase') {
			// Dans le cas d'un site vitrine, on ne souhaite pas afficher le lien vers l'administration des modes de transport.
			$GLOBALS['menu_items']['manage']['manage_delivery'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DELIVERY_HEADER"];
		}
		if (a_priv('admin_manage', true)) {
			$GLOBALS['menu_items']['manage_delivery'][$GLOBALS['administrer_url'] . '/pays.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_COUNTRIES"];
			$GLOBALS['menu_items']['manage_delivery'][$GLOBALS['administrer_url'] . '/zones.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_ZONES"];
			$GLOBALS['menu_items']['manage_delivery'][$GLOBALS['administrer_url'] . '/types.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DELIVERY"];
		}
		if (a_priv('admin_manage,admin_finance', true)) {
			$GLOBALS['menu_items']['manage_delivery'][$GLOBALS['administrer_url'] . '/tarifs.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DELIVERY_COST"];
		}
		if (a_priv('admin_manage', true)) {
			$GLOBALS['menu_items']['manage_delivery'][$GLOBALS['administrer_url'] . '/statut_livraison.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_STATUS"];
		}
		if(a_priv('admin_white_label,admin_manage,admin_content,admin_communication,admin_finance', true) && vb($GLOBALS['site_parameters']['website_type']) != 'showcase') {
			// Dans le cas d'un site vitrine, on ne souhaite pas afficher le lien vers l'administration des emails.
			$GLOBALS['menu_items']['manage']['manage_emails'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_EMAILS_HEADER"];	
			$GLOBALS['menu_items']['manage_emails'][$GLOBALS['administrer_url'] . '/email-templates.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_EMAIL"];
		}
		if (a_priv('admin_white_label,admin_manage', true)) {
			if (check_if_module_active('bounces', 'bounce_driver.php')) {
				$GLOBALS['menu_items']['manage_emails'][$GLOBALS['wwwroot_in_admin'] . '/modules/bounces/administrer/bad_mails.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_BOUNCE"];
			}
		}
		if (a_priv('admin_white_label,admin_sales,admin_users,admin_content,admin_communication,admin_finance,admin_operations,admin_productsline,admin_funding', true)) {
			// Menu des utilisateurs
			$GLOBALS['main_menu_items']['users'] = array($GLOBALS['administrer_url'] . '/utilisateurs.php' => $GLOBALS["STR_ADMIN_MENU_USERS_USERS"]);
		}
		if (a_priv('admin_white_label,admin_users,admin_finance,admin_operations,admin_productsline', true)) {
			$GLOBALS['menu_items']['users']['users_general'] = $GLOBALS["STR_ADMIN_MENU_USERS_USERS"];
			$GLOBALS['menu_items']['users_general'][$GLOBALS['administrer_url'] . '/utilisateurs.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_USERS_LIST"];
		}
		if (a_priv('admin_white_label,admin_users', true)) {
			$GLOBALS['menu_items']['users_general'][$GLOBALS['administrer_url'] . '/utilisateurs.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_USERS_USER_CREATE"];
		if (a_priv('admin_users', true)) {
			$GLOBALS['menu_items']['users_general'][$GLOBALS['administrer_url'] . '/utilisateurs.php?mode=liste&priv=supplier'] = $GLOBALS["STR_ADMIN_MENU_USERS_SUPPLIERS_LIST"];
		}
		}
		if (check_if_module_active('pages_stats', 'administrer/pages_stats.php') && !empty($GLOBALS['site_parameters']['page_stats_module_tracert_ip_page_consultation_enable'])) {
			$GLOBALS['menu_items']['users_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/pages_stats/administrer/pages_stats.php'] = $GLOBALS["STR_ADMIN_SITE_ACCESS_STATISTICS"];
		}
		if (a_priv('admin_white_label,admin_sales,admin_users,admin_content,admin_communication,admin_finance,admin_operations,admin_productsline', true)) {
			$GLOBALS['menu_items']['users']['users_retaining'] = $GLOBALS["STR_ADMIN_MENU_USERS_RETAINING"];
		}
		if (a_priv('admin_white_label,admin_users,admin_content,admin_communication,admin_finance', true)) {
			$GLOBALS['menu_items']['users_retaining'][$GLOBALS['administrer_url'] . '/newsletter.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_NEWSLETTER"];
		}
		if (a_priv('admin_white_label,admin_sales,admin_users,admin_operations,admin_productsline', true)) {
			$GLOBALS['menu_items']['users_retaining'][$GLOBALS['administrer_url'] . '/codes_promos.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_CODE_PROMO"];
		}
		if (a_priv('admin_white_label,admin_users,admin_finance,admin_operations,admin_productsline', true)) {
			if (check_if_module_active('good_clients', 'administrer/bons_clients.php')) {
				$GLOBALS['menu_items']['users_retaining'][$GLOBALS['wwwroot_in_admin'] . '/modules/good_clients/administrer/bons_clients.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_BEST_CLIENTS"];
			}
		}
		if (a_priv('admin_white_label,admin_users,admin_operations,admin_productsline', true)) {
			if (check_if_module_active('birthday', 'administrer/bons_anniversaires.php')) {
				$GLOBALS['menu_items']['users_retaining'][$GLOBALS['wwwroot_in_admin'] . '/modules/birthday/administrer/bons_anniversaires.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_BIRTHDAY"];
			}
		}
		if (a_priv('admin_white_label,admin_users_contact_form,admin_users,admin_finance,admin_operations,admin_productsline,admin_funding', true)) {
			// On affichera le menu relation client uniquement si $GLOBALS['menu_items']['users_sales'] n'est pas vide
			$GLOBALS['menu_items']['users']['users_sales'] = $GLOBALS["STR_ADMIN_MENU_USERS_SALES_MANAGEMENT"];
			if (!empty($GLOBALS['site_parameters']['enable_admin_specific_form'])) {
				$GLOBALS['menu_items']['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/administrer/specific_form.php'] = 'Enregistrement spécifique';
			}
		}
		if (a_priv('admin_white_label,admin_users', true)) {
			if (file_exists($GLOBALS['dirroot'] . '/modules/maps_users/administrer/map_google_search.php')) {
				$GLOBALS['menu_items']['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/maps_users/administrer/map_google_search.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_USERS_MAP"];
			}
		}
		if (a_priv('admin_white_label,admin_users', true)) {
			if (check_if_module_active('offres')) {
				$GLOBALS['menu_items']['users']['users_offre'] = $GLOBALS["STR_ADMIN_OFFRES"];
				$GLOBALS['menu_items']['users_offre'][get_url('/modules/offres/administrer/offres.php')] = $GLOBALS["STR_ADMIN_ADMIN_OFFRES_ALL_LIST"];
				$GLOBALS['menu_items']['users_offre'][get_url('/modules/offres/administrer/list_utilisateurs_offres.php')] = $GLOBALS["STR_ADMIN_LIST_UTILISATEURS_TITLE"];
			}
		}
		if (a_priv('admin_products,admin_finance', true)) {
			if (a_priv('admin_white_label,admin_products', true)) {
				$this_url = $GLOBALS['administrer_url'] . '/produits.php';
			} else {
				$this_url = '#';
			}
			$GLOBALS['main_menu_items']['products'] = array($this_url => $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS"]);
		}
		if (a_priv('admin_products', true)) {
			$GLOBALS['menu_items']['products']['products_general'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS"];
			$GLOBALS['menu_items']['products_general'][$GLOBALS['administrer_url'] . '/produits.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS_LIST"];
			$GLOBALS['menu_items']['products_general'][$GLOBALS['administrer_url'] . '/produits.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCT_ADD"];
			$GLOBALS['menu_items']['products_general'][$GLOBALS['administrer_url'] . '/positions.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS_ORDER"];
			$GLOBALS['menu_items']['products_general'][$GLOBALS['administrer_url'] . '/prix.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRICE_UPDATE"];
			$GLOBALS['menu_items']['products_general'][$GLOBALS['administrer_url'] . '/prix_pourcentage.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRICE_UPDATE_BY_PERCENTAGES"];
		}
		if (a_priv('admin_products,admin_finance', true)) {
			$GLOBALS['menu_items']['products']['products_categories'] = $GLOBALS["STR_ADMIN_CATEGORIES"];
			$GLOBALS['menu_items']['products_categories'][$GLOBALS['administrer_url'] . '/categories.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_CATEGORIES_LIST"];
			$GLOBALS['menu_items']['products_categories'][$GLOBALS['administrer_url'] . '/categories.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_CATEGORY_ADD"];
		}
		if (a_priv('admin_products', true)) {
			$GLOBALS['menu_items']['products_categories'][$GLOBALS['administrer_url'] . '/marques.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_BRAND_LIST"];
			$GLOBALS['menu_items']['products']['products_attributes'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_ATTRIBUTES_HEADER"];
			$GLOBALS['menu_items']['products_attributes'][$GLOBALS['administrer_url'] . '/couleurs.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_COLORS"];
			$GLOBALS['menu_items']['products_attributes'][$GLOBALS['administrer_url'] . '/tailles.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_SIZES"];
		}
		if (a_priv('admin_white_label,admin_sales,admin_finance,admin_operations', true)) {
			// Menu des ventes
			if (a_priv('admin_white_label,admin_sales,admin_finance,admin_operations', true)) {
				$this_url = $GLOBALS['administrer_url'] . '/commander.php';
			} else {
				$this_url = '#';
			}
			$GLOBALS['main_menu_items']['sales'] = array($this_url => $GLOBALS["STR_ADMIN_MENU_SALES_SALES_TITLE"]);
		}
		if (a_priv('admin_white_label,admin_sales,admin_finance,admin_operations', true)) {
			$GLOBALS['menu_items']['sales']['sales_general'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_HEADER"];
			$GLOBALS['menu_items']['sales_general'][$GLOBALS['administrer_url'] . '/commander.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_ORDERS"];
		}
		if (a_priv('admin_white_label,admin_sales,admin_operations', true)) {
			$GLOBALS['menu_items']['sales_general'][$GLOBALS['administrer_url'] . '/commander.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_SALES_ORDER_CREATION"];
		}
		if (a_priv('admin_white_label,admin_sales', true)) {
			if (check_if_module_active('export')) {
				$GLOBALS['menu_items']['sales_general'][$GLOBALS['administrer_url'] . '/ventes.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_REPORT_HEADER"];
			} else {
				$GLOBALS['menu_items']['sales_general'][$GLOBALS['administrer_url'] . '/ventes.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_REPORT"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/hosting/administrer/hosting.php')) {
				$GLOBALS['menu_items']['sales']['sales_hosting'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_HOSTING_HEADER"];
				$GLOBALS['menu_items']['sales_hosting'][$GLOBALS['wwwroot_in_admin'] . '/modules/hosting/administrer/hosting.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_HOSTING"];
			}
		}
		if (a_priv('admin_white_label,admin_sales,admin_finance,admin_operations', true)) {
			// On affichera le menu relation client uniquement si $GLOBALS['menu_items']['users_sales'] n'est pas vide
			$GLOBALS['menu_items']['sales']['sales_accounting'] = $GLOBALS["STR_ADMIN_MENU_SALES_ACCOUNTING_HEADER"];
			if (check_if_module_active('statistiques')) {
				$GLOBALS['menu_items']['sales_accounting'][$GLOBALS['wwwroot_in_admin'] . '/modules/statistiques/administrer/statcommande.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_STAT"];
			}
			if (check_if_module_active('marges')) {
				$GLOBALS['menu_items']['sales_accounting'][$GLOBALS['wwwroot_in_admin'] . '/modules/marges/administrer/marges.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_MARGINS"];
			}
			if (check_if_module_active('facture_advanced', 'administrer/genere_pdf.php')) {
				$GLOBALS['menu_items']['sales_accounting'][$GLOBALS['wwwroot_in_admin'] . '/modules/facture_advanced/administrer/genere_pdf.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PDF_BILLS"];
			}
		}
		if (a_priv('admin_white_label,admin_sales', true)) {
			$GLOBALS['menu_items']['sales']['sales_delivery'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_HEADER"];
			if (check_if_module_active('export')) {
				$GLOBALS['menu_items']['sales_delivery'][$GLOBALS['administrer_url'] . '/livraisons.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_EXPORT"];
			} else {
				$GLOBALS['menu_items']['sales_delivery'][$GLOBALS['administrer_url'] . '/livraisons.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_REPORT"];
			}
			if (a_priv('admin_sales', true)) {
			if (check_if_module_active('picking')) {
				$GLOBALS['menu_items']['sales_delivery'][$GLOBALS['wwwroot_in_admin'] . '/modules/picking/administrer/picking.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PICKING_LIST"];
			}
			}
			if (check_if_module_active('exaprint')) {
				$GLOBALS['menu_items']['sales_delivery'][$GLOBALS['wwwroot_in_admin'] . '/modules/exaprint/administrer/exaprint.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_EXAPRINT"];
			}
		}
		if (a_priv('admin_white_label,admin_content,admin_communication,admin_finance,admin_productsline', true)) {
			if (a_priv('admin_white_label,admin_content,admin_communication,admin_finance', true)) {
				$this_url = $GLOBALS['administrer_url'] . '/articles.php';
			} else {
				$this_url = '#';
			}
			$GLOBALS['main_menu_items']['content'] = array($this_url => $GLOBALS["STR_ADMIN_MENU_CONTENT_TITLE"]);
		}
		if (a_priv('admin_white_label,admin_content,admin_communication,admin_finance', true)) {
			$GLOBALS['menu_items']['content']['content_articles'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_ARTICLES_HEADER"];
			$GLOBALS['menu_items']['content_articles'][$GLOBALS['administrer_url'] . '/articles.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_ARTICLES_LIST"];
			$GLOBALS['menu_items']['content_articles'][$GLOBALS['administrer_url'] . '/articles.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_ARTICLE_ADD"];
			$GLOBALS['menu_items']['content_articles'][$GLOBALS['administrer_url'] . '/rubriques.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_CATEGORIES_LIST"];
			$GLOBALS['menu_items']['content_articles'][$GLOBALS['administrer_url'] . '/rubriques.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_CATEGORY_ADD"];
		}
		if (a_priv('admin_white_label,admin_users,admin_content,admin_webmastering,admin_communication,admin_finance', true)) {
			$GLOBALS['menu_items']['content']['content_general'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_HTML_HEADER"];
		}
		if (a_priv('admin_white_label,admin_content,admin_communication', true)) {
			$url_cgv = get_cgv_url(false);
			$GLOBALS['menu_items']['content_general'][$GLOBALS['administrer_url'] . '/cgv.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TERMS"];
		}
		if (a_priv('admin_white_label,admin_content', true)) {
			if (file_exists($GLOBALS['dirroot'] . '/modules/cgu-template/administrer/cgu-update.php')) {
				$GLOBALS['menu_items']['content_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/cgu-template/administrer/cgu-update.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TERMS_TEMPLATES"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/cgu-template/administrer/cgu.php')) {
				$GLOBALS['menu_items']['content_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/cgu-template/administrer/cgu.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TERMS_GENERATE"];
			}
		}
		if (a_priv('admin_white_label,admin_content,admin_communication', true)) {
			$GLOBALS['menu_items']['content_general'][$GLOBALS['administrer_url'] . '/legal.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_LEGAL"];
			$GLOBALS['menu_items']['content_general'][$GLOBALS['administrer_url'] . '/plan.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_GOOGLEMAP"];
		}
		if (a_priv('admin_white_label,admin_users,admin_content,admin_webmastering', true)) {
			$GLOBALS['menu_items']['content_general'][$GLOBALS['administrer_url'] . '/contacts.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_CONTACTS"];
		}
		if (a_priv('admin_white_label,admin_content,admin_communication,admin_finance,admin_productsline', true)) {
			$GLOBALS['menu_items']['content']['content_various'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_VARIOUS_HEADER"];
		}
		if (a_priv('admin_white_label,admin_content,admin_communication,admin_finance', true)) {   
			$GLOBALS['menu_items']['content_various'][$GLOBALS['administrer_url'] . '/html.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_HTML"];
		}
		if (a_priv('admin_white_label,admin*', true)) {
			if (a_priv('admin_white_label,admin_webmastering,admin_finance,admin_operations', true)) {
				$this_url = $GLOBALS['administrer_url'] . '/produits_achetes.php';
			} else {
				$this_url = '#';
			}
			$GLOBALS['main_menu_items']['webmastering'] = array($this_url => $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_TITLE"]);
		}
		if (a_priv('admin_white_label,admin_moderation', true)) {
			$GLOBALS['menu_items']['webmastering']['moderation_various'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_TITLE"];
			$GLOBALS['menu_items']['moderation_various'][$GLOBALS['administrer_url'] . '/list_admin_actions.php?action_cat=PHONE'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_PHONE_CALLS"];
			$GLOBALS['menu_items']['moderation_various'][$GLOBALS['administrer_url'] . '/list_admin_actions.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_ADMIN_ACTIONS"];
			$GLOBALS['menu_items']['moderation_various'][$GLOBALS['administrer_url'] . '/connexion_user.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_USER_CONNEXIONS"];
		}
		if (a_priv('admin_white_label,admin_webmastering,admin_finance,admin_operations', true)) {
			// Menu de webmastering
			$GLOBALS['menu_items']['webmastering']['webmastering_marketing'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_MARKETING"];
			$GLOBALS['menu_items']['webmastering_marketing'][$GLOBALS['administrer_url'] . '/produits_achetes.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_BEST_PRODUCTS"];
		}
		if (a_priv('admin_white_label,admin_moderation,admin_communication,admin_webmastering', true)) {
			$GLOBALS['menu_items']['webmastering']['webmastering_seo'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_SEO_HEADER"];
		}
		if (a_priv('admin_white_label,admin_moderation', true)) {
			$GLOBALS['menu_items']['webmastering_marketing'][$GLOBALS['administrer_url'] . '/import.php?type=produits'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_IMPORT_PRODUCTS"];
			if (file_exists($GLOBALS['dirroot'] . '/modules/import')) {
				$GLOBALS['menu_items']['webmastering_marketing'][$GLOBALS['administrer_url'] . '/import.php?type=clients'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_CLIENTS_IMPORT"];
			}
			if (check_if_module_active('export')) {
				$GLOBALS['menu_items']['webmastering_marketing'][$GLOBALS['wwwroot_in_admin'] . '/modules/export/administrer/export_clients.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_CLIENTS_EXPORT"];
			}
			if (check_if_module_active('expeditor')) {
				$GLOBALS['menu_items']['webmastering_marketing'][$GLOBALS['wwwroot_in_admin'] . '/modules/expeditor/administrer/expeditor.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_EXPEDITOR"];
			}
			if (check_if_module_active('comparateur')) {
				$GLOBALS['menu_items']['webmastering_seo'][$GLOBALS['wwwroot_in_admin'] . '/modules/comparateur/administrer/mysql2comparateur.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_COMPARATORS"];
			}
			if (empty($_SESSION['session_admin_multisite']) || $_SESSION['session_admin_multisite']==$GLOBALS['site_id']) {
				// Possibilité de générer le sitemap uniquement pour le domaine en cours d'utilisation, et pas pour le site administré.
				$GLOBALS['menu_items']['webmastering_seo'][$GLOBALS['administrer_url'] . '/sitemap.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_SITEMAP"];
				$GLOBALS['menu_items']['webmastering_seo'][$GLOBALS['administrer_url'] . '/urllist.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_SITEMAP_URLLIST"];
			}
		}
		if (a_priv('admin_white_label,admin_content,admin_webmastering,admin_communication', true)) {
			$GLOBALS['menu_items']['webmastering_seo'][$GLOBALS['administrer_url'] . '/meta.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_META"];
		}
		if (a_priv('admin_white_label,admin_moderation', true)) {
			if (file_exists($GLOBALS['dirroot'] . '/modules/projects_management/administrer/projects.php')) {
				$GLOBALS['menu_items']['webmastering']['webmastering_projects'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_MANAGEMENT"];
				$GLOBALS['menu_items']['webmastering_projects'][$GLOBALS['wwwroot_in_admin'] . '/modules/projects_management/administrer/projects.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_TASKS"];
				$GLOBALS['menu_items']['webmastering_projects'][$GLOBALS['wwwroot_in_admin'] . '/modules/projects_management/administrer/project-custom-orders.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_SOLD"];
				$GLOBALS['menu_items']['webmastering_projects'][$GLOBALS['wwwroot_in_admin'] . '/modules/projects_management/administrer/project-events.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_CONTENT"];
			}
		}
		$GLOBALS['menu_items']['webmastering']['webmastering_various'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_VARIOUS_HEADER"];
		$GLOBALS['menu_items']['webmastering_various'][$GLOBALS['administrer_url'] . '/import.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_IMPORT"];
		if (check_if_module_active('export')) {
			$GLOBALS['menu_items']['webmastering_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/export/administrer/export.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_EXPORT"];
		}
		if (check_if_module_active('calc')) {
			$GLOBALS['menu_items']['webmastering_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/calc/calc.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_CALC"];
		}
		$hook_result = call_module_hook('admin_menu_items', array(), 'array');
		
		$GLOBALS['main_menu_items'] = array_merge_recursive_distinct($GLOBALS['main_menu_items'], vb($hook_result['main_menu_items'], array()));
		$GLOBALS['menu_items'] = array_merge_recursive_distinct($GLOBALS['menu_items'], vb($hook_result['menu_items'], array()));
		if(!empty($GLOBALS['site_parameters']['admin_menu_items_additional_menus_array']) && !empty($GLOBALS['site_parameters']['admin_menu_items_additional_titles_array'])) {
			foreach($GLOBALS['site_parameters']['admin_menu_items_additional_menus_array'] as $this_type => $this_url_list) {
				foreach(explode(',', str_replace(' ', '', $this_url_list)) as $this_url) {
					if(!empty($GLOBALS['site_parameters']['admin_menu_items_additional_titles_array'][$this_url])) {
						if(!empty($GLOBALS['menu_items'][$this_type])) {
							$this_title = $GLOBALS['site_parameters']['admin_menu_items_additional_titles_array'][$this_url];
							if(StringMb::strpos($this_url, '//') === false) {
								$this_url = $GLOBALS['wwwroot_in_admin'] . $this_url;
							}
							// Si le tableau existe, c'est que les droits d'accès de l'utilisateur ont déjà été vérifiés
							$GLOBALS['menu_items'][$this_type][$this_url] = $this_title;
						}
					}
				}
			}
		}
		if (empty($GLOBALS['menu_items']['users_sales'])) {
			// On affiche le menu relation client uniquement si $GLOBALS['menu_items']['users_sales'] n'est pas vide
			unset($GLOBALS['menu_items']['users']['users_sales']);
		}
		if (empty($GLOBALS['menu_items']['sales_accounting'])) {
			// On affiche le menu comptabilité uniquement si $GLOBALS['menu_items']['sales_accounting'] n'est pas vide
			unset($GLOBALS['menu_items']['sales']['sales_accounting']);
		}
	}
	$current_url = get_current_url(false);
	$current_url_full = get_current_url(true);

	$output = '
';
	$i = 0;
	// Il faut remplacer les tags dans la configuration admin_menu_items_excluded pour remplacer par exemple ADMINISTRER_URL
	$GLOBALS['site_parameters']['admin_menu_items_excluded'] = template_tags_replace(vb($GLOBALS['site_parameters']['admin_menu_items_excluded']));
	foreach ($GLOBALS['main_menu_items'] as $this_main_item => $this_main_array) {
		if (!empty($GLOBALS['site_parameters']['admin_menu_items_excluded']) && in_array($this_main_item, $GLOBALS['site_parameters']['admin_menu_items_excluded'])) {
			// On ne souhaite pas cet élément dans le menu, donc on passe au suivant
			continue;
		}
		if (!empty($GLOBALS['menu_items'][$this_main_item]) && is_array($GLOBALS['menu_items'][$this_main_item])) {
			foreach(array_keys($GLOBALS['menu_items'][$this_main_item]) as $this_key) {
				$current_menu = (!empty($GLOBALS['menu_items'][$this_key][$current_url_full]));
				$full_match = true;
				if ($current_menu === false && !empty($GLOBALS['menu_items'][$this_key])) {
					$current_menu = (!empty($GLOBALS['menu_items'][$this_key][$current_url]));
					$full_match = false;
				}
				if(!empty($current_menu)) {
					break;
				}
			}
		} else {
			$current_menu = (!empty($GLOBALS['menu_items'][$this_main_item][$current_url_full]));
			$full_match = true;
			if ($current_menu === false && !empty($GLOBALS['menu_items'][$this_main_item])) {
				$current_menu = (!empty($GLOBALS['menu_items'][$this_main_item][$current_url]));
				$full_match = false;
			}
		}
		foreach ($this_main_array as $this_main_url => $this_main_title) {
			$main_class = array();
			$main_attributes = array();
			if ($current_menu !== false || !empty($this_main_array[$current_url]) || !empty($this_main_array[$current_url_full])) {
				$main_class[] = 'active';
			}
			if (!empty($GLOBALS['menu_items'][$this_main_item])) {
				$main_class[] = 'dropdown-toggle';
				$main_attributes[] = 'role="button" data-toggle="dropdown"';
				$this_main_url = '#';
			}
			if ($this_main_item == 'home') {
				$this_main_text = '<a id="menu_label_'.$this_main_item.'" title="' . $GLOBALS['STR_HOME'] . '" href="' . htmlspecialchars($this_main_url) . '" class="' . implode(' ', $main_class) . '" ' . implode(' ', $main_attributes) . '><span class="glyphicon glyphicon-home"></span></a>';
			} else {
				if (!empty($this_main_url) && !is_numeric($this_main_url)) {
					$this_main_text = '<a id="menu_label_'.$this_main_item.'" href="' . htmlspecialchars($this_main_url) . '" class="' . implode(' ', $main_class) . '" ' . implode(' ', $main_attributes) . '>' . $this_main_title . (!empty($GLOBALS['menu_items'][$this_main_item])?'<b class="caret"></b>':'') . '</a>';
				} else {
					$this_main_text = '<a id="menu_label_'.$this_main_item.'" href="#">' . $this_main_title . '</a>';
				}
			}
			if (!empty($GLOBALS['menu_items'][$this_main_item])) {
				$this_main_text .= '<ul class="sousMenu dropdown-menu" role="menu" aria-labelledby="menu_label_'.$this_main_item.'">
';
				foreach ($GLOBALS['menu_items'][$this_main_item] as $this_url => $this_submenu) {
					if (!empty($GLOBALS['site_parameters']['admin_menu_items_excluded']) && in_array($this_url, $GLOBALS['site_parameters']['admin_menu_items_excluded'])) {
						// On ne souhaite pas cet élément dans le menu, donc on passe au suivant
						continue;
					}
					if (!empty($GLOBALS['menu_items'][$this_url]) && is_array($GLOBALS['menu_items'][$this_url])) {
						$this_main_text .= '<li class="dropdown-submenu">
							<a id="menu_'.substr(md5($this_url . $this_submenu),0,8).'" href="#" class="dropdown-toggle">' . StringMb::strtoupper($this_submenu) . '</a>
							<ul class="sousMenu dropdown-menu" role="menu" aria-labelledby="menu_'.substr(md5($this_url . $this_submenu),0,8).'">
';
						foreach ($GLOBALS['menu_items'][$this_url] as $this_url => $this_title) {
							if (!empty($GLOBALS['site_parameters']['admin_menu_items_excluded']) && in_array($this_url, $GLOBALS['site_parameters']['admin_menu_items_excluded'])) {
								// On ne souhaite pas cet élément dans le menu, donc on passe au suivant
								continue;
							}
							if (($current_url == $this_url && !$full_match) || $current_url_full == $this_url) {
								$class = ' class="active"';
							} else {
								$class = '';
							}
							if (!empty($this_url) && !is_numeric($this_url)) {
								// var_dump($this_title, $this_url);
								$this_text = '<a title="' . StringMb::str_form_value($this_title) . '" href="' . htmlspecialchars($this_url) . '"' . $class . '>' . $this_title . '</a>';
							} else {
								$this_text = '<a href="#"' . $main_class . '>' . $this_title . '</a>';
							}
							$this_main_text .= '<li>' . $this_text . '</li>';
						}
						$this_main_text .= '
		</ul>
	</li>';
					} else {
						$this_title = $this_submenu;
						if (($current_url == $this_url && !$full_match) || $current_url_full == $this_url) {
							$class = ' class="active"';
						} elseif ($this_url == $GLOBALS['wwwroot_in_admin'] . '/modules/calc/calc.php') {
							$class = ' onclick="return(window.open(this.href)?false:true);"';
						} else {
							$class = '';
						}
						if (!empty($this_url) && !is_numeric($this_url)) {
							$this_text = '<a title="' . $this_title . '" href="' . htmlspecialchars($this_url) . '"' . $class . '>' . $this_title . '</a>';
						} else {
							$this_text = '<a href="#"' . $main_class . '>' . $this_title . '</a>';
						}
						$this_main_text .= '<li>' . $this_text . '</li>';
					}
				}
				$this_main_text .= '
</ul>';
			}
			$output .= '
	<li class="menu_main_item menu_' . $this_main_item . ' dropdown">' . $this_main_text . '</li>
';
		}
		$i++;
	}
	$output .= '
';
	return $output;
}

/**
 * Fonctions pour certains fichiers d'administration
 *
 * @return
 */
function affiche_nb_connexions()
{
	$nb = 0;
	$annee = date("Y");
	$mois = date("m");
	$jour = date("d");
	$ladate = $annee . '-' . $mois . '-' . $jour;
	$sql = "SELECT SUM(nb) AS nbp
		FROM peel_nb_connexions_lien
		WHERE la_date='" . nohtml_real_escape_string($ladate) . "'";
	$query = query($sql);
	$select = fetch_assoc($query);
	$rep = $GLOBALS['STR_ADMIN_VISITS_TODAY']." : <span class='stat_data'>" . $select['nbp'] . "</span>";
	$s = query("SELECT SUM(nb) AS nbp
		FROM peel_nb_connexions_lien
		WHERE la_date LIKE '" . nohtml_real_escape_string($annee . '-' . $mois) . "%'");
	while ($select = fetch_assoc($s)) {
		$nb = $nb + $select['nbp'];
	}
	$rep .= ", ".$GLOBALS['STR_ADMIN_VISITS_THIS_MONTH']." : <span class='stat_data'>" . $nb . "</span>";
	$nb = 0;
	$s = query("SELECT SUM(nb) AS nbp
		FROM peel_nb_connexions_lien
		WHERE la_date LIKE '" . nohtml_real_escape_string($annee) . "-%'");
	while ($select = fetch_assoc($s)) {
		$nb = $nb + $select['nbp'];
	}
	$rep .= ", ".$GLOBALS['STR_ADMIN_VISITS_THIS_YEAR']." : <span class='stat_data'>" . $nb . "</span>.";
	return $rep;
}

/**
 * is_one_product_valid()
 *
 * @param mixed $req
 * @return
 */
function is_one_product_valid($req)
{
	if (!empty($req['p1'])) {
		$max_size = count($req['p1']);
		for ($i = 1; $i <= $max_size; $i++) {
			if ($req['l' . $i] != null && $req['l' . $i] != "" && floatval($req['q' . $i]) > 0 && floatval($req['p' . $i]) > 0)
				return true;
		}
	}
	return false;
}

/**
 * is_one_command_product_valid()
 *
 * @param mixed $req
 * @return
 */
function is_one_command_product_valid($req)
{
	$max_size = count($req['produit']);
	for ($i = 0; $i < $max_size; $i++) {
		if ($req['produit'][$i] != null && $req['produit'][$i] != "" && floatval($req['quantite'][$i]) > 0 && floatval($req['prix'][$i]) > 0) {
			return true;
		}
	}
	return false;
}

/**
 * sendclient()
 *
 * @param integer $commandeid
 * @param string $prefered_mode
 * @param string $mode
 * @param string $partial
 * @return
 */
function sendclient($commandeid, $prefered_mode = 'html', $mode = 'bdc', $partial = '')
{
	$sql = "SELECT *
		FROM peel_commandes
		WHERE id = '" . intval($commandeid) . "' AND " . get_filter_site_cond('commandes', null) . "";
	$query = query($sql);
	$commande = fetch_assoc($query);

	$custom_template_tags['ORDER_ID'] = $commande['order_id'];
	$custom_template_tags['MODE'] = $mode;
	if ($prefered_mode == 'html' && check_if_module_active('factures', 'commande_html.php')) {
		if(!empty($partial)) {
			$custom_template_tags['AMOUNT'] = $partial;
		} else {
			$custom_template_tags['AMOUNT'] = fprix(vn($commande['montant']), false, vb($commande['devise']), true, vn($commande['currency_rate']), false, false, null, false, true);
		}
		$template_technical_code = 'send_client_order_html';
		$custom_template_tags['URL_FACTURE'] = get_site_wwwroot($commande['site_id'], $_SESSION['session_langue']) . '/modules/factures/commande_html.php?currency_rate=' . vn($commande['currency_rate']) . '&code_facture=' . urlencode($commande['code_facture']) . '&partial=' . urlencode($partial) . '&mode=' . $mode;
	} else {
		$template_technical_code = 'send_client_order_pdf';
		$custom_template_tags['URL_FACTURE'] = get_site_wwwroot($commande['site_id'], $_SESSION['session_langue']) . '/factures/commande_pdf.php?code_facture=' . urlencode($commande['code_facture']) . '&mode=' . $mode;
	}
	send_email($commande['email'], '', '', $template_technical_code, $custom_template_tags, null, $GLOBALS['support_commande']);
}

/**
 * send_avis_expedition()
 *
 * @param integer $commandeid
 * @param integer $delivery_tracking
 * @return
 */
function send_avis_expedition($commandeid, $delivery_tracking, $email_template = null)
{
	$resCom = query("SELECT c.*, sp.technical_code AS statut_paiement". (check_if_module_active('tnt')?', t.is_tnt':'') . "
		FROM peel_commandes c
		LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
		". (check_if_module_active('tnt')?'LEFT JOIN peel_types t ON t.id=c.typeId AND ' . get_filter_site_cond('types', 't') . '':'') . "
		WHERE c.id='" . intval($commandeid) . "' AND " . get_filter_site_cond('commandes', 'c') . "");
	$commande = fetch_object($resCom);
	$user = get_user_information($commande->id_utilisateur);
	if (!empty($commande->is_tnt) && check_if_module_active('tnt')) {
		$delivery_tracking = '';
		$sql = query("SELECT ca.tnt_parcel_number, ca.tnt_tracking_url
			FROM peel_commandes_articles ca
			WHERE ca.commande_id='" . intval($commandeid) . "'");
		while ($result = fetch_assoc($sql)) {
			$delivery_tracking .= $GLOBALS["STR_NUMBER"].$result['tnt_parcel_number'] . " " . $result['tnt_tracking_url'] . "\n";
		}
	}
	$order_infos = get_order_infos_array($commande);

	$custom_template_tags['ORDER_ID'] = $commande->order_id;
	$custom_template_tags['TYPE'] = $commande->type;
	$custom_template_tags['COLIS'] = $delivery_tracking;
	$custom_template_tags['NOM_FAMILLE'] = $commande->nom_bill;
	$custom_template_tags['PRENOM'] = $commande->prenom_bill;
	$custom_template_tags['CLIENT_INFOS_SHIP'] = $order_infos['client_infos_ship'];
	$custom_template_tags['COUT_TRANSPORT'] = fprix($commande->cout_transport, true) . " " . $GLOBALS['STR_TTC'];

	$custom_template_tags['SHIPPED_ITEMS'] = '';
	
	$custom_template_tags['CIVILITE'] = $user['civilite'];
    $custom_template_tags['PAIEMENT'] = get_payment_name($commande->paiement);
	
	$product_infos_array = get_product_infos_array_in_order($commandeid, $commande->devise, $commande->currency_rate);
	foreach ($product_infos_array as $this_ordered_product) {
		$custom_template_tags['SHIPPED_ITEMS'] .= $this_ordered_product["product_text"] . "\n";
		$custom_template_tags['SHIPPED_ITEMS'] .= $GLOBALS['STR_QUANTITY'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $this_ordered_product["quantite"] . "\n";
		$custom_template_tags['SHIPPED_ITEMS'] .= $GLOBALS['STR_PRICE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . fprix($this_ordered_product["total_prix"], true) . ' ' . $GLOBALS['STR_TTC'] . "\n\n";
	}
	if (empty($email_template)) {
		$email_template = 'send_avis_expedition';
	}
	$result = send_email($commande->email, '', '', $email_template, $custom_template_tags, null, $GLOBALS['support_commande']);
	if($result) {
		return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_DELIVERY_EMAIL_SENT'], $commande->email)))->fetch();
	} else {
		return null;
	}
}

/**
 * ftp_download()
 *
 * @param mixed $host
 * @param mixed $user
 * @param mixed $password
 * @param mixed $directory
 * @param mixed $remote_filename
 * @param mixed $local_filename
 * @return
 */
function ftp_download($host, $user, $password, $directory, $remote_filename, $local_filename)
{
	if (empty($host) || empty($user)) {
		return $GLOBALS['STR_FTP_CONNECT_FAILED'];
	}
	// FTP Connection
	$connection = ftp_connect($host, 0, 20);
	if (!$connection) {
		return $GLOBALS['STR_FTP_CONNECT_FAILED'];
	}
	// FTP login
	if (!ftp_login($connection, $user, $password)) {
		return $GLOBALS['STR_FTP_AUTHENTIFICATION_FAILED'];
	}
	// FTP Passive mode active (in case of firewall)
	ftp_pasv($connection, true);
	// FTP change directory
	if (!ftp_chdir($connection, $directory)) {
		return $GLOBALS['STR_FTP_CHDIR_FAILED'];
	}
	// Create a file for the compressed file
	if (!($handle = StringMb::fopen_utf8($GLOBALS['uploaddir'] . '/' . $local_filename, 'wb'))) {
		return $GLOBALS['STR_FOPEN_FAILED'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . $GLOBALS['uploaddir'] . '/' . $local_filename;
	}
	// Get the compressed file in the temporary file
	if (!ftp_fget($connection, $handle, $remote_filename, FTP_BINARY, 0)) {
		return $GLOBALS['STR_FTP_GET_FAILED'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . $remote_filename;
	}

	fclose($handle);
	ftp_close($connection);

	return true;
}

/**
 * file_uncompress()
 *
 * @param mixed $source_filename
 * @param mixed $destination_filename
 * @return
 */
function file_uncompress($source_filename, $destination_filename)
{
	// Open the compresses file
	if (!$zp = gzopen($GLOBALS['uploaddir'] . '/' . $source_filename, 'r')) {
		return $GLOBALS['STR_GZOPEN_FAILED'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . $GLOBALS['uploaddir'] . '/' . $source_filename;
	}
	// Open the local file
	if (!($handle2 = StringMb::fopen_utf8($GLOBALS['uploaddir'] . '/' . $destination_filename, 'wb'))) {
		return $GLOBALS['STR_FOPEN_FAILED'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . $GLOBALS['uploaddir'] . '/' . $destination_filename;
	}
	// read the compress temporary file and write it in an uncompressed one
	if (!fwrite($handle2, gzread($zp, 9999999))) {
		return $GLOBALS['STR_FWRITE_FAILED'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . $GLOBALS['uploaddir'] . '/' . $destination_filename;
	}

	fclose($handle2);
	gzclose($zp);

	return true;
}

/**
 * Fonction permettant de connaître l'id d'un produit
 *
 * @param string $name
 * @param boolean $large_search
 * @return
 */
function get_product_id_by_name($name, $large_search = false)
{
	// Si plusieurs produits existent avec un même nom, on prend celui qui est actif et mis à jour le plus récemment
	// La collation UTF8 permet de trouver avec = la valeur sans tenir compte des majuscules
	$sql = 'SELECT id
		FROM peel_produits
		WHERE (nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']).'="' . nohtml_real_escape_string(trim($name)) . '"' . (trim($name)!=$name ? ' OR nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']).'="' . nohtml_real_escape_string(StringMb::strtolower($name)) . '"' : '') . ') AND ' . get_filter_site_cond('produits', null) . '
		ORDER BY etat DESC, date_maj DESC
		LIMIT 1';
	$q = query($sql);
	if ($result = fetch_assoc($q)) {
		return $result['id'];
	} else {
		if($large_search) {
			$sql = 'SELECT id
				FROM peel_produits
				WHERE nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']).' LIKE "%' . nohtml_real_escape_string(StringMb::strtolower(trim($name))) . '%" AND ' . get_filter_site_cond('produits', null) . '
				ORDER BY IF(nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']).' LIKE "' . nohtml_real_escape_string(StringMb::strtolower(trim($name))) . '%",1,0) DESC, etat DESC, date_maj DESC
				LIMIT 1';
			$q = query($sql);
		}
		if ($result = fetch_assoc($q)) {
			return $result['id'];
		} else {
			return false;
		}
	}
}

/**
 * Vérification s'il y a eu une mise à jour des données de l'acheteur
 *
 * @param array $array_order_infos
 * @param array $array_user_infos
 * @return
 */
function checkUserInfo($array_order_infos, $array_user_infos)
{
	return (($array_user_infos['nom_famille'] == $array_order_infos['nom_bill']) &&
		($array_user_infos['prenom'] == $array_order_infos['prenom_bill']) &&
		($array_user_infos['code_postal'] == $array_order_infos['zip_bill']) &&
		($array_user_infos['ville'] == $array_order_infos['ville_bill']) &&
		($array_user_infos['telephone'] == $array_order_infos['telephone_bill']));
}

/**
 * execute_sql()
 *
 * @param string $file_path
 * @param integer $max_sql_lines_at_once
 * @param boolean $disable_echo
 * @param integer $site_id
 * @param integer $regular_display
 * @param string $sql
 * @param boolean $replace_tags
 * @return
 */
function execute_sql($file_path, $max_sql_lines_at_once = 10000, $disable_echo = false, $site_id = 0, $regular_display = 100, $sql = null, $replace_tags = true)
{
	$output = '';
	// Ce tag est utilisé dans le fichier create_new_site.sql.
	$custom_template_tags["SITE_ID"] = $site_id;
	if($max_sql_lines_at_once === null) {
		$_SESSION['session_sql_output'] = '';
		if(!empty($file_path)) {
			$sql .= str_replace("\r\n", "\n", StringMb::file_get_contents_utf8($file_path));
		}
		$sql = str_replace("\r", "\n", $sql);
		// Toutes les lignes comprenant du SQL doivent se finir par ; sans aucun commentaire, sinon ça ne marchera pas
		while (StringMb::strpos($sql, '; ') !== false) {
			$sql = str_replace("; ", ";", $sql);
		}
		$sql = str_replace(";\r", ";\n", $sql);
		// On supprime d'abord les commentaires
		$tab = explode("\n", $sql);
		$n = count($tab);
		for ($i = 0; $i < $n; $i++) {
			if ($tab[$i] == "" || StringMb::substr(trim($tab[$i]), 0, 1) == '#' || StringMb::substr(trim($tab[$i]), 0, 2) == '--') {
				// Cette ligne est un commentaire
				unset($tab[$i]);
			}
		}
		$sql = implode("\n", $tab);
		// On exécute les commandes SQL
		$tab = explode(";\n", $sql);
		ob_start();
		for ($i = 0; $i < count($tab); $i++) {
			// Remplacement des tags dans la ligne.
			if($replace_tags) {
				// 3ème argument $replace_only_custom_tags à true. On ne veut pas remplacer tous les tags, seulement site_id. En effet des tags comme CLOSE_MAIN_CONTAINER ou REOPEN_MAIN_CONTAINER qui sont présent dans le SQL d'installation ne doivent pas être remplacée. Ces tags sont fait pour être remplacé lors de la lecture des informations, pas lors de l'insertion en BDD. La valeur de ces tags dépend de la page sur laquelle ils sont lu
				$tab[$i] = template_tags_replace($tab[$i], $custom_template_tags, true);
			}
			if(StringMb::strpos($tab[$i], 'DELETE') === 0 || StringMb::strpos($tab[$i], 'DROP TABLE') === 0 || (StringMb::strpos($tab[$i], 'ALTER TABLE') === 0 && StringMb::strpos($tab[$i], 'DROP INDEX') !== false && StringMb::strpos($tab[$i], 'ADD INDEX') === false)) {
				// On veut supprimer un élément, donc si cet élément ne peut pas être supprimé ce n'est probablement pas grave, on ne veut pas de message d'erreur
				$silent_if_error = true;
			} else {
				$silent_if_error = false;
			}
			query($tab[$i], false, null, $silent_if_error);
		}
		$output .= ob_get_contents();
		ob_end_clean();
		$_SESSION['session_sql_output'] .= $output;
		return $output;
	} else {
		// Affichage immédiat
		@ini_set("zlib.output_compression", 0);  // off
		ob_implicit_flush(true);
		
		$handle = StringMb::fopen_utf8($file_path, 'r');
		if (!empty($_SESSION['session_sql_filepos']) && !isset($_GET['init'])) {
			// Si la dernière exécution de ce modèle s'est mal terminée : on continue là
			// où on en était resté
			fseek($handle, $_SESSION['session_sql_filepos']);
			$output .= '<p>OK : ' . $_SESSION['session_sql_ok'] . ' - NOK : ' . $_SESSION['session_sql_nok'] . ' - DROP TABLE : ' . $_SESSION['session_sql_drop'] . ' - CREATE TABLE : ' . $_SESSION['session_sql_create'] . '</p>';
		} else {
			$_SESSION['session_sql_ok'] = 0;
			$_SESSION['session_sql_nok'] = 0;
			$_SESSION['session_sql_create'] = 0;
			$_SESSION['session_sql_drop'] = 0;
			$_SESSION['session_sql_output'] = '';
		}
		$i = 0;
		$sql_query='';
		while (!StringMb::feof($handle) && $i < $max_sql_lines_at_once) {
			$row = fgets($handle, 16384);
			$i++;
			if (StringMb::strlen($row) > 1 && StringMb::strpos(trim($row), '#') !== 0 && StringMb::substr(trim($row), 0, 2) !== '--') {
				if (StringMb::strpos($row, '; ') !== false || StringMb::strpos($row, ";\r") !== false || StringMb::strpos($row, ";\n") !== false || StringMb::strpos($row, ";\t") !== false || StringMb::substr($row, -1) == ";") {
					// Remplacement des tags dans la ligne.
					if($replace_tags) {
						$sql_query .= template_tags_replace($row, $custom_template_tags);
					} else {
						$sql_query .= $row;
					}
					ob_start();
					$query = query($sql_query);
					$_SESSION['session_sql_output'] .= ob_get_contents();
					ob_end_clean();

					if (!$query) {
						if (StringMb::strpos($sql_query, 'CREATE TABLE') !== false) {
							$_SESSION['session_sql_create']++;
						} elseif (StringMb::strpos($sql_query, 'DROP TABLE') !== false) {
							$_SESSION['session_sql_drop']++;
						} else {
							if (!$disable_echo && empty($no_output)) {
								echo 'NOT OK : ' . $sql_query . '<br />';
							}
							$_SESSION['session_sql_nok']++;
						}
						$_SESSION['session_sql_filepos'] = ftell($handle);
					} else {
						// On met à jour après l'exécution de la requête
						// Dans le pire des cas on quitte juste ici => on aura fait une execution de SQL
						// qui n'aura pas été vue par $_SESSION['session_sql_filepos']
						$_SESSION['session_sql_filepos'] = ftell($handle);
						$_SESSION['session_sql_ok']++;
					}
					$sql_query = '';
				} else {
					$sql_query .= $row;
				}
			}
			if (!$disable_echo && $_SESSION['session_sql_ok'] % $regular_display == 0 && $_SESSION['session_sql_ok'] > 0) {
				$output .= 'Processing... OK : ' . $_SESSION['session_sql_ok'] . ' SQL position : ' . vn($_SESSION['session_sql_filepos']) . '<br />';
				if (empty($no_output)) {
					echo $output;
					flush();
					ob_flush();
				}
				$output = '';
			}
		}
		if ($_SESSION['session_sql_ok'] % $regular_display != 0) {
			$output .= 'Processing... OK : ' . $_SESSION['session_sql_ok'] . ' SQL position : ' . vn($_SESSION['session_sql_filepos']) . '<br />';
		}
		if (!$disable_echo && empty($no_output)) {
			echo $output;
			flush();
			ob_flush();
		}
		fclose($handle);
		if($i == $max_sql_lines_at_once){
			echo '<meta http-equiv="refresh" content="1; url=' . get_current_url(false). '?confirm=ok&lines_per_page='.vb($_GET['lines_per_page'], 10000).'"></meta>';
		} elseif (!$disable_echo) {
			echo '<div class="alert alert-success">FINISHED</div>';
			unset($_SESSION['session_sql_filepos']);
			echo '<p>Affichage des erreurs éventuelles lors de l\'exécution de toutes les pages : '.vb($_SESSION['session_sql_output']).'</p>';
		}
	}
	return vb($_SESSION['session_sql_output']);
}

/**
 * get_data_lang()
 *
 * @return
 */
function get_data_lang()
{
	$get_options = '';
	foreach ($_GET as $this_item => $this_value) {
		if($this_item != 'langue') {
			$get_options .= '<input type="hidden" name="' . $this_item . '" value="' . StringMb::str_form_value($this_value) . '" />';
		}
	}
	$lang_select = '
<form id="langue" method="get" action="' . StringMb::str_form_value(get_current_url(false)) . '" class="entryform form-inline">
	<div>'.$GLOBALS["STR_ADMIN_LANGUAGE"] . $GLOBALS["STR_BEFORE_TWO_POINTS"].':
		' . $get_options . '<select name="langue" class="form-control" onchange="document.getElementById(\'langue\').submit()" style="width:200px;">
			<option value="">' . $GLOBALS['STR_CHOOSE'] . '...</option>
';
	foreach ($GLOBALS['lang_names'] as $this_lang => $this_lang_name) {
		$lang_select .= '<option value="' . $this_lang . '" ' . frmvalide($_SESSION['session_langue'] == $this_lang, ' selected="selected"') . '>' . $this_lang_name . '</option>';
	}

	$i = 0;
	$lang_select .= '
		</select>
	</div>
</form>
';
	return $lang_select;
}


/**
 * Envoi d'un code promo déjà existant à un utilisateur
 *
 * @param integer $id_utilisateur
 * @param integer $id_codepromo
 * @return
 */
function envoie_client_code_promo($id_utilisateur, $id_codepromo)
{
	if (!empty($id_codepromo) && !empty($id_utilisateur)) {
		// on envoi un email à la personne demandée
		$sql = "SELECT pcp.*, pc.nom_" . $_SESSION['session_langue'] . " AS nom_cat, DATE_FORMAT(date_fin, '%d/%m/%Y') AS date_fin, nombre_prevue, nb_used_per_client
			FROM peel_codes_promos pcp
			LEFT JOIN peel_categories pc ON pc.id=pcp.id_categorie AND " . get_filter_site_cond('categories', 'pc') . "
			WHERE pcp.id = '" . intval($id_codepromo) . "' AND " . get_filter_site_cond('codes_promos', 'pcp') . "";
			
		
		$query = query($sql);
		$cp = fetch_assoc($query);
		$la_date = date("Y-m-d");

		if (!empty($id_utilisateur)) {
			$remise = get_discount_text($cp['remise_valeur'], $cp['remise_percent'], true);
			$user_infos = get_user_information($id_utilisateur);
			$email = $user_infos['email'];

			$requete = query("SELECT 1
				FROM peel_utilisateurs_codes_promos
				WHERE id_utilisateur = '" . intval($id_utilisateur) . "' AND id_code_promo = '" . intval($id_codepromo) . "'");
			if ((num_rows($requete) == 0) || (num_rows($requete) > 0 && !empty($GLOBALS['site_parameters']['disable_limitation_promotional_code_sending']))) {
				// si le code n'a pas déjà été associé à l'utilisateur : on veut se souvenir qu'on lui a envoyé
				// ATTENTION : cette table peel_utilisateurs_codes_promos est pour la gestion commerciale, et n'a pas pour vocation à lister tous les usages de tous les codes promos
				query("INSERT INTO peel_utilisateurs_codes_promos (id_utilisateur, id_code_promo, nom_code, la_date, utilise, valeur)
					VALUES ('" . intval($id_utilisateur) . "', '" . intval($cp['id']) . "', '" . nohtml_real_escape_string($cp['nom']) . "', '" . nohtml_real_escape_string($la_date) . "','0', '" . nohtml_real_escape_string($remise) . "')");
				// on récupère les informations nom prenom civilite et email du client
				unset($custom_template_tags);
				$custom_template_tags['CIVILITE'] = $user_infos['civilite'];
				$custom_template_tags['PRENOM'] = $user_infos['prenom'];
				$custom_template_tags['NOM_FAMILLE'] = $user_infos['nom_famille'];
				$custom_template_tags['CIVILITE'] = $user_infos['civilite'];
				$custom_template_tags['NOM_CODE_PROMO'] = $cp['nom'];
				$custom_template_tags['REMISE'] = $remise;
				// On récupère le nombre d'utilisations possibles
				if ($cp['nb_used_per_client'] > 0 && $cp['nombre_prevue'] > 0) {
					$nb_used_possible = min($cp['nb_used_per_client'], $cp['nombre_prevue']);
				} elseif ($cp['nb_used_per_client'] > 0) {
					$nb_used_possible = $cp['nb_used_per_client'];
				} elseif ($cp['nb_used_per_client'] > 0) {
					$nb_used_possible = $cp['nombre_prevue'];
				} else {
					$nb_used_possible = '-';
				}
				$custom_template_tags['NB_USED_POSSIBLE'] = $nb_used_possible;
				if (!empty($cp['nom_cat'])) {
					$custom_template_tags['REMISE'] .= $GLOBALS['STR_ON_CATEGORY'] . ' ' . $cp['nom_cat'];
				}
				$custom_template_tags['DATE_FIN'] = $cp['date_fin'];
				$custom_template_tags['MONTANT_MIN'] = fprix($cp['montant_min'],true);

				send_email($email, '', '', 'envoie_client_code_promo', $custom_template_tags, null, $GLOBALS['support_sav_client']);
				return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_ADMIN_CODES_PROMOS_MSG_SENT_OK"], $cp['nom'], $user_infos['civilite'] . ' ' . $user_infos['prenom'] . ' ' . $user_infos['nom_famille'], $email)))->fetch();
			} else {
				return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS["STR_ADMIN_CODES_PROMOS_ERR_ALREADY_SENT"], $email . ' (' . $user_infos['prenom'] . ' ' . $user_infos['nom_famille'] . ')', $cp['nom'])))->fetch();
			}
		}
	}
	return false;
}

/**
 * Affiche la liste des commandes
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_liste_commandes_admin($frm = null, $return = 'full_html')
{
	$output = '';
	$sql_inner = '';
	$sql_cond = '';
	$sql = "";
		
	// On va utiliser sur cette page spécifiquement les icônes Font Awesome 
	$GLOBALS['css_files']['font-awesome'] = get_url('/lib/css/font-awesome.min.css');
	// On exclue ce fichier de la minification car usage ponctuel
	$GLOBALS['site_parameters']['minify_css_exclude_array'][] = 'font-awesome.min.css';
		
	if(!empty($frm)) {
		if (!empty($frm['client_info'])) {
			$sql_cond .= ' AND (c.email LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"
				OR u.email LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"
				OR u.societe LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"
				OR u.nom_famille LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"
				OR u.prenom LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"';
			foreach(array('_bill', '_ship') as $this_item) {
				$sql_cond .= ' OR c.societe'.$this_item.' LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"';
				$sql_cond .= ' OR c.email'.$this_item.' LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"';
				$sql_cond .= ' OR c.nom'.$this_item.' LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"';
				$sql_cond .= ' OR c.prenom'.$this_item.' LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"';
			}
			$sql_cond .= ')';
			$sql_inner .= ' INNER JOIN peel_utilisateurs u ON c.id_utilisateur=u.id_utilisateur AND ' . get_filter_site_cond('utilisateurs', 'u') . '';
		}
		if (!empty($frm['searchProd'])) {
			$sql_cond .= ' AND ca.nom_produit LIKE "%' . nohtml_real_escape_string(StringMb::strtolower(trim($frm['searchProd']))) . '%"';
			$sql_inner .= ' INNER JOIN peel_commandes_articles ca ON ca.commande_id=c.id  AND ' . get_filter_site_cond('commandes_articles', 'ca', true);
		}
		if (isset($frm['statut_paiement']) && is_numeric($frm['statut_paiement'])) {
			$sql_cond .= ' AND c.id_statut_paiement="' . nohtml_real_escape_string($frm['statut_paiement']) . '"';
		}
		if (isset($frm['statut_livraison']) && is_numeric($frm['statut_livraison'])) {
			$sql_cond .= ' AND c.id_statut_livraison="' . nohtml_real_escape_string($frm['statut_livraison']) . '"';
		}
		if (!empty($frm['id'])) {
			if(!empty($GLOBALS['site_parameters']['order_list_use_order_id'])) {
				$this_order_id_field = "order_id";
			} else {
				$this_order_id_field = "id";
			}
			$sql_cond .= ' AND (c.'.$this_order_id_field.'="' . intval($frm['id']) . '" OR c.numero="' . nohtml_real_escape_string($frm['id']) . '")';
		}
		if (!empty($frm['affi'])) {
			$sql_cond .= ' AND affilie = "1"';
		}
	}
	$sql = "SELECT c.*
		FROM peel_commandes c " . $sql_inner . "
		WHERE " . get_filter_site_cond('commandes', 'c', true) . "  " . $sql_cond . "";
	if(!empty($sql_inner)){
		$sql .="
		GROUP BY c.id";
	}
	$Links = new Multipage($sql, 'affiche_liste_commandes_admin', ($return == 'html_array'?'*':50));
	if ($return == 'html_array') {
		$HeaderTitlesArray = array(
			'id' => $GLOBALS['STR_ADMIN_ID'],
			'numero' => $GLOBALS["STR_ADMIN_COMMANDER_BILL_NUMBER"],
			'o_timestamp' => $GLOBALS['STR_DATE'],
			'montant' => $GLOBALS['STR_TOTAL'] . ' ' . (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']),
			$GLOBALS['STR_AVOIR'],
			'id_utilisateur' => $GLOBALS['STR_CUSTOMER'],
			'id_statut_paiement' => $GLOBALS['STR_PAYMENT'],
			'id_statut_livraison' => $GLOBALS['STR_DELIVERY'],
			'site_id' => $GLOBALS['STR_ADMIN_WEBSITE']
		);
	} else {
		$HeaderTitlesArray = array(
			$GLOBALS['STR_ADMIN_ACTION'],
			'id' => $GLOBALS['STR_ADMIN_ID'],
			'numero' => $GLOBALS["STR_ADMIN_COMMANDER_BILL_NUMBER"],
			'o_timestamp' => $GLOBALS['STR_DATE'],
			'montant' => $GLOBALS['STR_TOTAL'] . ' ' . (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']),
			$GLOBALS['STR_AVOIR'],
			'id_utilisateur' => $GLOBALS['STR_CUSTOMER'],
			$GLOBALS['STR_PAYMENT'],
			'id_statut_paiement' => $GLOBALS['STR_PAYMENT'],
			'id_statut_livraison' => $GLOBALS['STR_DELIVERY'],
			'site_id' => $GLOBALS['STR_ADMIN_WEBSITE']
		);
	}
	if(!empty($GLOBALS['site_parameters']['admin_order_list_display_delivery_mode_column'])) {
		$HeaderTitlesArray['type'] = $GLOBALS['STR_ADMIN_MENU_MANAGE_DELIVERY'];
	}
	$HeaderTitlesArray['suspect'] = $GLOBALS['STR_ORDER_SUSPECT'];
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault =  vb($GLOBALS['site_parameters']['liste_commandes_admin_order_default'],'o_timestamp');
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();
	if (empty($results_array)) {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_NO_RESULT']))->fetch();
	}
	// Affichage des commandes en liste
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_commande_liste.tpl');
	$tpl->assign('links_nbRecord', vn($Links->nbRecord));
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('id', vb($_GET['id']));
	$tpl->assign('client_info', vb($_GET['client_info']));
	$tpl->assign('searchProd', vb($_GET['searchProd']));
	$tpl->assign('payment_status_options', get_payment_status_options(vb($_GET['statut_paiement'])));
	$tpl->assign('delivery_status_options', get_delivery_status_options(vb($_GET['statut_livraison'])));

	$tpl->assign('action2', get_current_url(false) . '?mode=maj_statut');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF']));
	$tpl->assign('is_fianet_sac_module_active', check_if_module_active('fianet_sac'));
	$tpl->assign('is_duplicate_module_active', check_if_module_active('duplicate'));
	$tpl->assign('is_module_genere_pdf_active', check_if_module_active('facture_advanced', 'administrer/genere_pdf.php'));
	if (!empty($results_array)) {
		$tpl_results = array();
		
		$tpl->assign('update_src', $GLOBALS['wwwroot_in_admin'] . '/images/update-on.png');
		$tpl->assign('links_header_row', $Links->getHeaderRow());

		$i = 0;
		foreach ($results_array as $order) {
			$this_sac_status = null;
			if (check_if_module_active('fianet')) {
				// Même si la fonction get_sac_status permet de passer un tableau d'id de commande en paramètre, l'appel de la fonction ce fait ici pour des raisons 
				// de simplicité pour le moment. Une amélioration possible est d'appeler la fonction avant le foreach. Il faut pour cela récupérer 
				// les id de commandes du tableau $results_array.
				$get_sac_status = get_sac_status($order['id'], vb($_POST['fianet_sac_update_status']));
				$this_sac_status = $get_sac_status[$order['id']];
			}
			if ($affiliated_user = get_user_information($order['id_utilisateur'])) {
				$modifUser = $affiliated_user['civilite'] . ' ' . $affiliated_user['prenom'] . ' ' . $affiliated_user['nom_famille'] . ' <br />' . $affiliated_user['societe'];
				if(trim(strip_tags($modifUser)) == '') {
					$modifUser = $order['email'];
				}
				$modifUser = (!checkUserInfo($order, $affiliated_user) ? '<img src="' . $GLOBALS['wwwroot_in_admin'] . '/images/update-on.png" alt="update-on.png" />' : '') . '<a href="utilisateurs.php?mode=modif&id_utilisateur=' . $affiliated_user['id_utilisateur'] . '">' . $modifUser . '</a>';
			} else {
				$modifUser = $order['prenom_bill'] . ' ' . $order['nom_bill'] . ' ' . $order['societe_bill'];
				if(trim(strip_tags($modifUser)) == '') {
					$modifUser = $order['email'];
				}
				if (!a_priv('demo') && !empty($order['id_utilisateur'])) {
					// Si l'utilisateur est avec droits de démo, les utilisateurs admin ne sont pas trouvés, ce qui ne veut pas dire qu'ils sont supprimés
					$modifUser .= '<br />(supprimé depuis)';
				}
			}
			if (display_prices_with_taxes_in_admin()) {
				$montant_displayed = $order['montant'];
			} else {
				$montant_displayed = $order['montant_ht'];
			}
		
			$picto_delivery_status_array = array();
			if (!empty($GLOBALS['site_parameters']['statut_livraison_picto'])) {
				foreach ($GLOBALS['site_parameters']['statut_livraison_picto'] as $this_status_id => $this_picto) {
					if ($this_status_id == $order['id_statut_livraison']) {
						$etat_src = $GLOBALS['administrer_url'] . '/images/' . $this_picto;
					} else {
						$etat_src = $GLOBALS['administrer_url'] . '/images/puce-blanche.gif';
					}
					$picto_delivery_status_array[$this_status_id] = array("etat_src" => $etat_src, 'etat_onclick' => 'change_status("delivery_status", "' . $order['id'] . '", this, "' . $GLOBALS['administrer_url'] . '", "'. $this_status_id . '")'); 
				}
			}
			$tpl_array= array('tr_rollover' => tr_rollover($i, true),
				'id' => $order['id'],
				'order_id' => $order['order_id'],
				'numero' => $order['numero'],
				'date' => get_formatted_date($order['o_timestamp']),
				'suspect' => $order['suspect'],
				'montant_prix' => fprix($montant_displayed, true, $order['devise'], true, $order['currency_rate']),
				'avoir_prix' => fprix($order['avoir'], true, $order['devise'], true, $order['currency_rate']),
				'modifUser' => $modifUser,
				'payment_name' => get_payment_name($order['paiement']),
				'payment_status_name' => get_payment_status_name($order['id_statut_paiement']),
				'delivery_status_name' => get_delivery_status_name($order['id_statut_livraison']),
				'dup_href' => get_current_url(false) . '?mode=duplicate&id=' . $order['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1),
				'dup_src' => $GLOBALS['administrer_url'] . '/images/duplicate.png',
				'this_sac_status' => $this_sac_status,
				'picto_delivery_status_array'=>  $picto_delivery_status_array,
				'site_name' => get_site_name($order['site_id'])
				);
			if(!empty($GLOBALS['site_parameters']['admin_order_list_display_delivery_mode_column'])) {
				 $tpl_array['type'] = $order['type'];
			}
			$tpl_results[] = $tpl_array;
			$i++;
		}
		$tpl_results = call_module_hook('affiche_liste_commandes_admin', $tpl_results, 'array', true);
		$tpl->assign('results', $tpl_results);

		$tpl->assign('payment_status_options2', get_payment_status_options());
		$tpl->assign('delivery_status_options2', get_delivery_status_options());
		$tpl->assign('links_multipage', $Links->GetMultipage());
	}
	if(function_exists('get_csv_export_from_html_table')) {
		$tpl->assign('get_csv_export_from_html_table', true);
		$tpl->assign('STR_ADMIN_EXPORT',$GLOBALS['STR_ADMIN_EXPORT']);
		$tpl->assign('get_current_url', get_current_url(false));
	}
	$tpl->assign('return', $return);
	$tpl->assign('STR_ADMIN_LIVRAISONS_EXCEL_EXPORT', $GLOBALS['STR_ADMIN_LIVRAISONS_EXCEL_EXPORT']);
	$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_CHECK_ALL', $GLOBALS['STR_ADMIN_CHECK_ALL']);
	$tpl->assign('STR_ADMIN_UNCHECK_ALL', $GLOBALS['STR_ADMIN_UNCHECK_ALL']);
	$tpl->assign('STR_ADMIN_COMMANDER_ORDERS_FOUND_COUNT', $GLOBALS['STR_ADMIN_COMMANDER_ORDERS_FOUND_COUNT']);
	$tpl->assign('STR_ORDER_NUMBER', $GLOBALS['STR_ORDER_NUMBER']);
	$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
	$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
	$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
	$tpl->assign('STR_ADMIN_COMMANDER_ORDERED_PRODUCT', $GLOBALS['STR_ADMIN_COMMANDER_ORDERED_PRODUCT']);
	$tpl->assign('STR_ORDER_STATUT_PAIEMENT', $GLOBALS['STR_ORDER_STATUT_PAIEMENT']);
	$tpl->assign('STR_ORDER_STATUT_LIVRAISON', $GLOBALS['STR_ORDER_STATUT_LIVRAISON']);
	$tpl->assign('STR_ADMIN_ALL_ORDERS', $GLOBALS['STR_ADMIN_ALL_ORDERS']);
	$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
	$tpl->assign('STR_ADMIN_COMMANDER_FIANET_UPDATE', $GLOBALS['STR_ADMIN_COMMANDER_FIANET_UPDATE']);
	$tpl->assign('STR_ADMIN_ORDER_DUPLICATE', $GLOBALS['STR_ADMIN_ORDER_DUPLICATE']);
	$tpl->assign('STR_ADMIN_ORDER_DUPLICATE_WARNING', $GLOBALS['STR_ADMIN_ORDER_DUPLICATE_WARNING']);
	$tpl->assign('STR_ADMIN_COMMANDER_CLIENT_UPDATED_ICON_EXPLAIN', $GLOBALS['STR_ADMIN_COMMANDER_CLIENT_UPDATED_ICON_EXPLAIN']);
	$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
	$tpl->assign('STR_ADMIN_CHECK_ALL', $GLOBALS['STR_ADMIN_CHECK_ALL']);
	$tpl->assign('STR_ADMIN_UNCHECK_ALL', $GLOBALS['STR_ADMIN_UNCHECK_ALL']);
	$tpl->assign('STR_ADMIN_COMMANDER_UPDATED_STATUS_FOR_SELECTION', $GLOBALS['STR_ADMIN_COMMANDER_UPDATED_STATUS_FOR_SELECTION']);
	$tpl->assign('STR_ADMIN_COMMANDER_NO_ORDER_FOUND',  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_NO_ORDER_FOUND']))->fetch());
	$tpl->assign('STR_NOTA_BENE', $GLOBALS['STR_NOTA_BENE']);
	$tpl->assign('STR_MODULE_FACTURES_ADMIN_TITLE', $GLOBALS['STR_MODULE_FACTURES_ADMIN_TITLE']);
	$tpl->assign('STR_ORDER_SUSPECT', $GLOBALS['STR_ORDER_SUSPECT']);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * Charge les détails d'une commande et les affiche
 *
 * @param integer $id
 * @param string $action Du type 'insere' ou 'ajout'
 * @param integer $user_id
 * @param string $page
 * @param object $form_error_object
 * @param array $commande_frm
 * @return
 */
function affiche_details_commande($id, $action, $user_id = 0, $page = 'commander', $form_error_object = null, $commande_frm = null)
{
	$output = '';
	$commande = array();
	if (empty($form_error_object)) {
		$form_error_object = new FormError();
	}
	$hook_result = call_module_hook('affiche_details_commande_begin', array('order_id' => $id, 'bill_mode' => $page), 'array');
	if (!empty($hook_result['js_content_array'])) {	
		$GLOBALS['js_content_array'][] = $hook_result['js_content_array'];
	}
	if(!empty($hook_result['commande'])) {
		$commande = $hook_result['commande'];
	}
	if(!empty($id) && empty($commande) && empty($hook_result['order_not_found'])) {
		$sql = "SELECT *
			FROM peel_commandes
			WHERE " . get_filter_site_cond('commandes', null, true) . " AND id = '" . intval($id) . "'";	
		$qid_commande = query($sql);
		$commande = fetch_assoc($qid_commande);		
		// on créer la variable $commande_data pour retrouver ce qui est exactement en base de données. Utile pour savoir si un numéro de commande est défini
		$commande_data = $commande;	
	}
	if (!empty($commande_frm)) {
		// on reprend les infos du formulaire, que l'on complète avec ce qui vient de la base de données.
		// array_merge : Si les tableaux d'entrées ont des clés en commun, alors, la valeur finale pour cette clé écrasera la précédente.
		if ($page == 'quote_prepare') {
			if (empty($commande_frm['Num_Devis']) && !empty($commande['Num_Devis'])) {
				// cas particulier pour les numéros de devis, on veut dans tous les cas la valeur qui vient de la base de données si le champ n'a pas été rempli. En effet le champ Num_Devis est rempli automatiquement et mis en BDD si le champ text est vide
				$commande_frm['Num_Devis'] = $commande['Num_Devis'];
			}
			if (empty($commande_frm['Libelle']) && !empty($commande['Libelle'])) {
				// cas particulier pour les libellé de devis, on veut dans tous les cas la valeur qui vient de la base de données si le champ n'a pas été rempli. En effet le champ Libelle est rempli automatiquement et mis en BDD si le champ text est vide
				$commande_frm['Libelle'] = $commande['Libelle'];
			}
		}
		$commande = array_merge($commande, $commande_frm);
	}
	if (!empty($commande) || $action == 'insere' || $action == 'ajout') {
		// Si nous somme en mode modif, alors on cherche les details de la commande
		if ($action != 'insere' && $action != 'ajout') {
			$date_facture = get_formatted_date(vb($commande['a_timestamp']));
			// f_datetime est la date d'émission de la facture, insérée dans la BDD automatiquement au moment de l'insertion du numéro de facture, sinon par l'administrateur en back office.
			$f_datetime = get_formatted_date(vb($commande['f_datetime']));
			// e_datetime est la date d'expédition de la commande, insérée dans la BDD automatiquement au moment du changement du statut de livraison de la facture, sinon par l'administrateur en back office.
			$e_datetime = get_formatted_date(vb($commande['e_datetime']));

			if (display_prices_with_taxes_in_admin ()) {
				$montant_displayed = vn($commande['montant']);
			} else {
				$montant_displayed = vn($commande['montant_ht']);
			}
		} else {
			// $date_facture = Date du jour
			$date_facture = get_formatted_date(time());
			$montant_displayed = 0;
		}
		$is_order_modification_allowed = is_order_modification_allowed(vb($commande['o_timestamp']));

		if (!empty($user_id)) {
			// Dans le cas où on crée une commande, on initialise à partir des données de l'utilisateur. Sinon on recupère les informations de l'utilsateur par la commande
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
				$commande['pays_' . $state] = get_country_name(vn($user_array['pays']));
				if (!empty($GLOBALS['site_parameters']['user_specific_field_titles'])) {
					$user_table_fields_names = get_table_field_names('peel_utilisateurs');
					$order_table_fields_names = get_table_field_names('peel_commandes');
					foreach($GLOBALS['site_parameters']['user_specific_field_titles'] as $this_field => $this_title) {
						if (((StringMb::substr($this_field, -5) == '_bill') || (StringMb::substr($this_field, -5) == '_ship')) && in_array($this_field, $user_table_fields_names) && in_array($this_field, $order_table_fields_names)) {
							// On a ajouté dans la table utilisateurs un champ qui concerne l'adresse de livraison ou de facturation => Il faut préremplir les champs du formulaire d'adresse avec ces infos.
							$commande[$this_field] = vb($user_array[$this_field]);
						}
					}
				}
			}
			$commande['id_utilisateur'] = vn($user_id);
			// La TVA est-elle applicable pour cet utilisateur ?
			// D'abord on regarde si la zone de l'utilisateur est concernée par l'application de la TVA
			$sqlPays = 'SELECT p.id, p.pays_' . $_SESSION['session_langue'] . ' as pays, p.zone, z.tva, z.on_franco
				FROM peel_pays p
				LEFT JOIN peel_zones z ON z.id=p.zone AND ' . get_filter_site_cond('zones', 'z') . '
				WHERE p.etat = "1" AND p.id ="' . nohtml_real_escape_string($user_array['pays']) . '" AND ' . get_filter_site_cond('pays', 'p') . '
				LIMIT 1';
			$query = query($sqlPays);
			if ($result = fetch_assoc($query)) {
				$user_vat = $result['tva'];
			} else {
				$user_vat = 1;
			}
			// Ensuite on vérifie que l'utilisateur n'a pas rentré un n° de TVA intracom qui l'exonèrerait, et que la boutique n'est pas en statut micro entreprise
			$commande['zone_tva'] = ($user_vat && !is_user_tva_intracom_for_no_vat($user_id) && !check_if_module_active('micro_entreprise'));
		} elseif($page == 'commander') {
			if (!empty($id)) {
				$commande['payment_technical_code'] = vb($commande['paiement']);
				if (strpos($commande['paiement'], ' ') !== false) {
					// ADAPTATION POUR TABLES ANCIENNES avec paiement qui contient nom et pas technical_code
					$sql = 'SELECT technical_code
						FROM peel_paiement
						WHERE nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($commande['paiement']) . '" AND ' .  get_filter_site_cond('paiement') . '
						LIMIT 1';
					$query = query($sql);
					if ($result = fetch_assoc($query)) {
						$commande['payment_technical_code'] = $result['technical_code'];
					}
				}
				if (vn($commande['cout_transport_ht']) > 0) {
					$commande['tva_transport'] = round((vn($commande['tva_cout_transport']) / $commande['cout_transport_ht'] * 100), 2);
				} else {
					$commande['tva_transport'] = null;
				}
			} else {
				// Nouvelle commande : valeurs par défaut
				$commande['pays_bill'] = get_country_name(vn($GLOBALS['site_parameters']['default_country_id']));
				$commande['pays_ship'] = get_country_name(vn($GLOBALS['site_parameters']['default_country_id']));
				$commande['zone_tva'] = 1;
			}
		}
		// $commande['num_tva'] : Numéro de TVA intracom rentré par l'utilisateur avec l'adresse de facturation
		$commande['intracom_for_billing'] = vb($commande['num_tva'], vb($user_array['intracom_for_billing']));
		if (!empty($commande['numero'])) {
			// On reprend le numéro de la BDD, et on va pouvoir l'éditer si on veut
			$numero = $commande['numero'];
		} elseif (!empty($GLOBALS['site_parameters']['admin_fill_empty_bill_number_by_number_format'])) {
			if (!empty($GLOBALS['site_parameters']['disable_get_configuration_by_site_id'])) {
				// Si on ne gère pas les configurations spécifiquement pour un site.
				$numero = vb($GLOBALS['site_parameters']['format_numero_facture']);
			} else {
				// NB : le format de numéro de facture peut dépendre de chaque site
				$numero = get_configuration_variable('format_numero_facture', vn($commande['site_id']), $_SESSION['session_langue']);
			}
		} else {
			$numero = null;
		}
		if (empty($commande['devise']) && $page == 'commander') {
			$commande['devise'] = $GLOBALS['site_parameters']['code'];
		}
		if (!empty($commande['zone_tva'])) {
			$default_vat = get_default_vat();
		} else {
			// pas de TVA
			$default_vat = 0;
		}
		if (in_array($page, array('bill_edit', 'bill_prepare', 'quote_prepare'))) {
			$edit_mode = $page;
		} else {
			$edit_mode = 'modif';
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_commande_details.tpl');
		$tpl->assign('action', get_current_url(false) . '?mode=' . $edit_mode . '&commandeid=' . vn($id));
		$tpl->assign('rpc_path', $GLOBALS['wwwroot'] . '/' . $GLOBALS['site_parameters']['backoffice_directory_name'] . '/rpc.php');
		$tpl->assign('tva_options_html', get_vat_select_options('20.00'));
		$tpl->assign('this_page', $page);
		$tpl->assign('action_name', $action);
		$tpl->assign('id', vn($id));
		$tpl->assign('order_id', vn($commande['order_id']));
		$tpl->assign('order_suspect', vn($commande['suspect']));
		if (!empty($commande['document'])) {
			$tpl->assign('url_document', get_url_from_uploaded_filename($commande['document']));
		}
		if (!empty($commande['document1'])) {
			$tpl->assign('url_document1', get_url_from_uploaded_filename($commande['document1']));
		}
		if (!empty($commande['document2'])) {
			$tpl->assign('url_document2', get_url_from_uploaded_filename($commande['document2']));
		}
		if (!empty($commande['document3'])) {
			$tpl->assign('url_document3', get_url_from_uploaded_filename($commande['document3']));
		}
		$tpl->assign('site_id_select_options', get_site_id_select_options(vb($commande['site_id']), null, null, true));
		$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']) || (!empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id_by_table']) && vb($GLOBALS['site_parameters']['multisite_using_array_for_site_id_by_table']['peel_commandes'])));
		$tpl->assign('internal_order_enable', vn($GLOBALS['site_parameters']['internal_order_enable']));
		
		$order_detail_fields_disable = array();
		if ($page != 'commander') {
			// On ne veut pas que cette variable soit active en back office. Si besoin il faudra créer une autre variable spécifiquement pour le back office
			// format de order_detail_fields_disable : 'site_id' => 'true','payment_select' => 'true','statut_paiement' => 'true', etc ...
			if(!empty($GLOBALS['site_parameters'][$page.'_order_detail_fields_disable'])) {
				$order_detail_fields_disable = $GLOBALS['site_parameters'][$page.'_order_detail_fields_disable'];
			} elseif (!empty($GLOBALS['site_parameters']['order_detail_fields_disable'])) {
				$order_detail_fields_disable = $GLOBALS['site_parameters']['order_detail_fields_disable'];
			}
			$tpl->assign('order_detail_fields_disable', $order_detail_fields_disable);
		}
		
		$tpl->assign('information_on_this_order_disabled', vb($GLOBALS['site_parameters']['information_on_this_order_disabled']));
		$tpl->assign('is_order_modification_allowed', $is_order_modification_allowed);
		$tpl->assign('order_specific_field_titles', !empty($GLOBALS['site_parameters']['order_specific_field_titles']));
		$tpl->assign('STR_ADMIN_MENU_CONTENT_VARIOUS_HEADER', $GLOBALS['STR_ADMIN_MENU_CONTENT_VARIOUS_HEADER']);
		if(!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
			$order_specific_fields = array();
			foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_key => $this_value) {
				$order_specific_fields[] = array('title' => $this_value, 'name' => $this_key, 'value' => vb($frm[$this_key]), 'type' => vb($GLOBALS['site_parameters']['order_specific_field_types'][$this_key], 'text'));
			}
			$tpl->assign('order_table_additionnal_fields_array', $order_specific_fields);
		}
		$tpl->assign('is_kiala_module_active', check_if_module_active('kiala'));
		if (check_if_module_active('kiala')) {
			$tpl->assign('shortkpid',vb($commande['shortkpid']));
			$tpl->assign('STR_MODULE_KIALA_TRACKING_ID', $GLOBALS['STR_MODULE_KIALA_TRACKING_ID']);
		}
		$tpl->assign('is_ups_module_active', check_if_module_active('ups'));
		if (check_if_module_active('ups')) {
			$tpl->assign('appuId',vb($commande['appuId']));
			$tpl->assign('STR_MODULE_UPS_TRACKING_ID', $GLOBALS['STR_MODULE_UPS_TRACKING_ID']);
		}

		$tpl->assign('pdf_src', $GLOBALS['wwwroot_in_admin'] . '/images/view_pdf.gif');
		if ($action != "insere" && $action != "ajout") {
			$hook_result = call_module_hook('affiche_details_commande_order_pdf_link_array', array('commande' => $commande, 'bill_mode' => $page), 'array');

			if (!empty($hook_result)) {
				// Les liens issus du hook sont prioritaires sur le lien par défaut;
				$default_order_pdf_link_array = $hook_result;
			} else {
				$default_order_pdf_link_array = array(
					'facture_pdf_href' => get_site_wwwroot($commande['site_id'], $_SESSION['session_langue']) . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=facture',
					'sendfacture_pdf_href' => $GLOBALS['administrer_url'] . '/commander.php?mode=sendfacturepdf&id=' . vn($commande['id']) . '&code_facture=' . vb($commande['code_facture']) . '&bill_type=facture',
					'proforma_pdf_href' => get_site_wwwroot($commande['site_id'], $_SESSION['session_langue']) . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=proforma',
					'sendproforma_pdf_href' => $GLOBALS['administrer_url'] . '/commander.php?mode=sendfacturepdf&id=' . vn($commande['id']) . '&code_facture=' . vb($commande['code_facture']) . '&bill_type=proforma',
					'devis_pdf_href' => get_site_wwwroot($commande['site_id'], $_SESSION['session_langue']) . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=devis',
					'senddevis_pdf_href' => $GLOBALS['administrer_url'] . '/commander.php?mode=sendfacturepdf&id=' . vn($commande['id']) . '&code_facture=' . vb($commande['code_facture']) . '&bill_type=devis',
					'bdc_pdf_href' => get_site_wwwroot($commande['site_id'], $_SESSION['session_langue']) . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=bdc'
				);
			}

			foreach($default_order_pdf_link_array as $this_tpl_var=>$this_link) {
				$tpl->assign($this_tpl_var, $this_link);
			}
			if (empty($hook_result['allow_display_invoice_link'])) {
				// si le hook n'a pas éjà défini cette variable
				$tpl->assign('allow_display_invoice_link', !empty($commande_data['numero']));
			}
			if (empty($hook_result['bill_anchor'])) {
				// si le hook n'a pas éjà défini cette variable
				$tpl->assign('bill_anchor', $GLOBALS['STR_PROFORMA']);				
			}
			if (empty($hook_result['bill_send_pdf_anchor'])) {
				// si le hook n'a pas éjà défini cette variable
				$tpl->assign('bill_send_pdf_anchor', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL']);
			}
			if (empty($hook_result['bill_send_pdf_anchor_confirm'])) {
				// si le hook n'a pas déjà défini cette variable
				$tpl->assign('bill_send_pdf_anchor_confirm', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL_CONFIRM']);
			}
			$tpl->assign('is_duplicate_module_active', check_if_module_active('duplicate'));
			$tpl->assign('dup_href', get_current_url(false) . '?mode=duplicate&id=' . vn($commande['id']));
			$tpl->assign('dup_src', $GLOBALS['administrer_url'] . '/images/duplicate.png');
			$tpl->assign('STR_ADMIN_ORDER_DUPLICATE', $GLOBALS['STR_ADMIN_ORDER_DUPLICATE']);
			$tpl->assign('STR_ADMIN_ORDER_DUPLICATE_WARNING', $GLOBALS['STR_ADMIN_ORDER_DUPLICATE_WARNING']);
						

			// Si le paramètre bill_redirect_html_to_pdf est actif et que la commande a un numéro de facture, on ne génère pas le lien vers la facture HTML.
			$tpl->assign('is_module_factures_html_active', check_if_module_active('factures', 'commande_html.php') && (empty($GLOBALS['site_parameters']['bill_redirect_html_to_pdf']) || (!empty($GLOBALS['site_parameters']['bill_redirect_html_to_pdf']) && empty($commande['numero']))));
			if (check_if_module_active('factures', 'commande_html.php') && empty($hook_result['bill_bdc_information_disable'])) {
				$tpl->assign('facture_html_href', get_site_wwwroot($commande['site_id'], $_SESSION['session_langue']) . '/modules/factures/commande_html.php?code_facture=' . vb($commande['code_facture']) . '&mode=facture');
				$tpl->assign('bdc_action', $GLOBALS['administrer_url'] . '/commander.php?mode=modif&commandeid=' . vn($commande['id']));
				$tpl->assign('bdc_code_facture', vb($commande['code_facture']));
				$tpl->assign('bdc_id', vn($commande['id']));
				$tpl->assign('bdc_partial', fprix(vn($commande['montant']), false, vb($commande['devise']), true, vn($commande['currency_rate']), false, false, null, false, true));
				$tpl->assign('bdc_devise', vb($commande['devise']));
				$tpl->assign('partial_amount_link_js', get_site_wwwroot($commande['site_id'], $_SESSION['session_langue']) . '/modules/factures/commande_html.php?currency_rate=' . vn($commande['currency_rate']) . '&code_facture=' . vb($commande['code_facture']) . '&mode=bdc&partial=');
				$tpl->assign('partial_amount_link_href', get_site_wwwroot($commande['site_id'], $_SESSION['session_langue']) . '/modules/factures/commande_html.php?code_facture=' . vb($commande['code_facture']) . '&mode=bdc&partial=' .get_float_from_user_input(fprix(vn($commande['montant']), false, vb($commande['devise']), false, $commande['currency_rate'], false, false)));
				$tpl->assign('partial_amount_link_target', 'facture' . $commande['code_facture']);
			}

			if (!empty($commande) && check_if_module_active('tnt')) {
				$q_type = query('SELECT * 
					FROM peel_types
					WHERE is_tnt="1" AND ' . get_filter_site_cond('types') . ' AND id = "' . nohtml_real_escape_string($commande['typeId']) . '"');
				if ($result = fetch_assoc($q_type)) {
					$tpl->assign('etiquette_tnt', '<b>ETIQUETTE TNT : </b><a target="_blank" href="' . $GLOBALS['wwwroot'] . '/modules/tnt/administrer/etiquette.php?order_id='.$commande['id'] .'">Imprimer l\'étiquette tnt (ouvre une nouvelle fenêtre)</a>');
					//Dans le cas d'une mise à jour d'une commande créée en B.O on génére l'url de tracking TNT
					$q_tnt_tracking = query('SELECT tnt_parcel_number, tnt_tracking_url
						FROM peel_commandes_articles
						WHERE commande_id="' . intval($commande['id']) . '"');
					$result_tnt_tracking = fetch_assoc($q_tnt_tracking);
					if (!empty($result_tnt_tracking)) {
						//On vérifie si l'url de tracking a déjà été généré
						if (empty($result_tnt_tracking['tnt_parcel_number']) && empty($result_tnt_tracking['tnt_tracking_url'])) {
							$tpl->assign('trackingCreation', trackingCreation($commande['id']));
						}
					}
				} else {
					$tpl->assign('etiquette_tnt', '<b>ETIQUETTE TNT : </b> Ce n\'est pas une commande liée au type de livraison TNT');
				}
			}

			$tpl->assign('date_facture', (empty($date_facture) ? "" : vb($date_facture)));
			$tpl->assign('e_datetime', (empty($e_datetime) ? "" : vb($e_datetime)));
			$tpl->assign('f_datetime', (empty($f_datetime) ? "" : vb($f_datetime)));
			$tpl->assign('intracom_for_billing', vb($commande['intracom_for_billing']));
			$tpl->assign('commande_date', get_formatted_date(vb($commande['o_timestamp'])));
			$tpl->assign('email_href', $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . vn($commande['id_utilisateur']));
			$tpl->assign('email', vb($commande['email']));
		}
		

		$tpl->assign('numero', $numero);

		if (!empty($commande['marketplace_orderid'])) {
			$tpl->assign('marketplace_orderid', vb($commande['marketplace_orderid']));
			$tpl->assign('STR_ADMIN_MARKETPLACE_ORDER_ID', $GLOBALS['STR_ADMIN_MARKETPLACE_ORDER_ID']);
		}

		$tpl->assign('delivery_tracking', vb($commande['delivery_tracking']));
		$tpl->assign('is_icirelais_module_active', check_if_module_active('icirelais'));
		$tpl->assign('delivery_locationid', vb($commande['delivery_locationid']));
		$tpl->assign('is_tnt_module_active', check_if_module_active('tnt'));
		if (check_if_module_active('icirelais')) {
			$tpl->assign('icirelais', array(
				'src' => get_url('/modules/icirelais/js/icirelais.js'),
				'value' => vb($commande['delivery_tracking'])
			));
			$tpl->assign('STR_MODULE_ICIRELAIS_CONFIGURATION_TRACKING_URL_TITLE', $GLOBALS['STR_MODULE_ICIRELAIS_CONFIGURATION_TRACKING_URL_TITLE']);
			$tpl->assign('MODULE_ICIRELAIS_SETUP_TRACKING_URL', MODULE_ICIRELAIS_SETUP_TRACKING_URL);
			$tpl->assign('STR_MODULE_ICIRELAIS_COMMENT_TRACKING', $GLOBALS['STR_MODULE_ICIRELAIS_COMMENT_TRACKING']);
			$tpl->assign('STR_MODULE_ICIRELAIS_ERROR_TRACKING', $GLOBALS['STR_MODULE_ICIRELAIS_ERROR_TRACKING']);
			$tpl->assign('STR_MODULE_ICIRELAIS_CREATE_TRACKING', $GLOBALS['STR_MODULE_ICIRELAIS_CREATE_TRACKING']);
		}
		if(((!empty($id) && vn($commande['montant']) > 0) || empty($id)) && empty($order_detail_fields_disable['payment_select'])) {
			$tpl->assign('payment_select', get_payment_select(vb($commande['payment_technical_code']), false, true, null, vb($commande['site_id'])));
		}
		if (empty($order_detail_fields_disable['statut_paiement'])) {
			$tpl->assign('payment_status_options', get_payment_status_options(vn($commande['id_statut_paiement'])));
		}
		if(!empty($GLOBALS['site_parameters']['mode_transport'])) {
			$tpl->assign('delivery_status_options', get_delivery_status_options(vn($commande['id_statut_livraison'])));
		}
		$tpl->assign('devise', vb($commande['devise']));
		$tpl->assign('mode_transport', vn($GLOBALS['site_parameters']['mode_transport']));
		$tpl->assign('STR_SHIP_TYPE_CHOOSE', $GLOBALS["STR_SHIP_TYPE_CHOOSE"]);
		if (!empty($GLOBALS['site_parameters']['mode_transport'])) {
			$tpl->assign('delivery_type_options', get_delivery_type_options(vb($commande['typeId'])));
			$tpl->assign('vat_select_options', get_vat_select_options(vb($commande['tva_transport']), true, true));
		} else {
			$tpl->assign('tva_transport', vb($commande['tva_transport']));
			$tpl->assign('type_transport', vb($commande['type_transport']));
		}
		if(isset($commande['cout_transport'])) {
			// Test sur if isset pour ne pas afficher une valeur dans le champ lors de la création d'une commmande. 
			// => ça force le calcul automatique des frais de port
			$tpl->assign('cout_transport', fprix(get_float_from_user_input(vn($commande['cout_transport'])), false, vb($commande['devise']), true, vn($commande['currency_rate']), false));
			$tpl->assign('tva_transport', fprix(get_float_from_user_input(vn($commande['tva_transport'])), false, vb($commande['devise']), true, vn($commande['currency_rate']), false));
		} else {
			$tpl->assign('cout_transport', '');
			$tpl->assign('tva_transport', '');
		}
		$tpl->assign('transport', vb($commande['transport']));
	
		if (empty($order_detail_fields_disable['devise'])) {
			$tpl->assign('is_devises_module_active', check_if_module_active('devises'));
			if (check_if_module_active('devises')) {
				$tpl_devises_options = array();
				$res_devise = query("SELECT p.code
					FROM peel_devises p
					WHERE etat='1' AND " . get_filter_site_cond('devises', 'p') . "");
				while ($tab_devise = fetch_assoc($res_devise)) {
					$tpl_devises_options[] = array('value' => $tab_devise['code'],
						'issel' => $tab_devise['code'] == vb($commande['devise']),
						'name' => $tab_devise['code']
						);
				}
				$tpl->assign('devises_options', $tpl_devises_options);
			}
		} else {
			$tpl->assign('is_devises_module_active', false);
		}

		$tpl->assign('small_order_overcost_amount', fprix(get_float_from_user_input(vn($commande['small_order_overcost_amount'])), false, vb($commande['devise']), true, vn($commande['currency_rate']), false));
		$tpl->assign('tva_small_order_overcost', fprix(get_float_from_user_input(vn($commande['tva_small_order_overcost'])), false, vb($commande['devise']), true, vn($commande['currency_rate']), false));
		$tpl->assign('site_symbole', vb($GLOBALS['site_parameters']['symbole']));
		$tpl->assign('currency_rate', vn($commande['currency_rate']));
		$tpl->assign('montant_displayed_prix', fprix($montant_displayed, true, vb($commande['devise']), true, vn($commande['currency_rate'])));
		$tpl->assign('ttc_ht', (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));
		$tpl->assign('ht', $GLOBALS['STR_HT']);
		$tpl->assign('ttc', $GLOBALS['STR_TTC']);
		$tpl->assign('montant', fprix(vn($commande['montant']), true, vb($commande['devise']), true, vn($commande['currency_rate'])));
		$tpl->assign('amount_ht', fprix(vn($commande['montant_ht']), true, vb($commande['devise']), true, vn($commande['currency_rate'])));
		$tpl->assign('amount_tva', fprix(vn($commande['montant'])-vn($commande['montant_ht']), true, vb($commande['devise']), true, vn($commande['currency_rate'])));

		if (!empty($commande['total_remise']) && $commande['total_remise'] > 0) {
			$tpl->assign('total_remise_prix', fprix((display_prices_with_taxes_in_admin()?$commande['total_remise']:$commande['total_remise_ht']), true, vb($commande['devise']), true, vn($commande['currency_rate'])));
		}
		$tpl->assign('avoir_prix', fprix(get_float_from_user_input(vn($commande['avoir'])), false, vb($commande['devise']), true, vn($commande['currency_rate'])));

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


		$tpl->assign('is_gifts_module_active', check_if_module_active('gifts'));
		if (check_if_module_active('gifts')) {
			$tpl->assign('total_points', vn($commande['total_points']));
			$tpl->assign('points_etat', vn($commande['points_etat']));
		}
		$tpl->assign('commande_interne', vb($commande['commande_interne']));
		if (!empty($commande['commentaires']) && !empty($GLOBALS['site_parameters']['order_alert_comment_enabled'])) {
			$GLOBALS['js_ready_content_array'][] = '$(window).on("load",function(){bootbox.alert("' .filtre_javascript($GLOBALS['site_parameters']['order_alert_text_comment'] . vb($commande['commentaires']), true, false, true, true, false). '");});';
		}
		$tpl->assign('commentaires', vb($commande['commentaires']));
		$tpl->assign('commentaires_admin', getTextEditor('commentaires_admin', '100%', 500, StringMb::html_entity_decode_if_needed(vb($commande['commentaires_admin']))));
		// on passe $page dans le tableau $commande pour permettre la gestion de champs spécifiques (via get_specific_field_infos) pour un type de commande donné.
		$specific_fields = array();
		if ((empty($GLOBALS['site_parameters']['order_specific_field_disable_in_admin']) && $page == 'commander') || $page != 'commander') {
			$commande['bill_mode'] = $page;
			$specific_fields = get_specific_field_infos($commande, $form_error_object, 'order');
		}
		$tpl->assign('specific_fields', $specific_fields);
		$assign_array = call_module_hook('specific_field_form_part', array('specific_field_infos_array'=>$specific_fields, 'order_infos'=>$commande), 'array');
		$tpl->assign('specific_field_form_part', display_specific_field_form($specific_fields, 'div', false, false, 'admin_commande_details_specific_field_form_part.tpl', $assign_array));
		
		if (empty($order_detail_fields_disable['client_address'])) {
			$tpl_client_infos = array();
			for ($i = 1; $i < 3; $i++) {
				if ($i == 1) {
					$value = (empty($GLOBALS['site_parameters']['order_ship_before_bill'])?'bill':'ship');
				} else {
					$value = (empty($GLOBALS['site_parameters']['order_ship_before_bill'])?'ship':'bill');
				}
				if($value == 'ship' && empty($GLOBALS['site_parameters']['mode_transport'])) {
					continue;
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
					'country_select_options' => get_country_select_options(vb($commande['pays_' . $value]), null, 'name', false, null, true, vb($commande['lang']))
					);
			}
			$tpl->assign('client_infos', $tpl_client_infos);
		}

		$tpl_order_lines = array();
		// Le hook retourne un tableau de variable permettant de modifier les fonctions de récupération et d'affichage des produits commandés.
		$hook_result = call_module_hook('order_lines_parameters', array('order_id' => $id, 'bill_mode' => $page), 'array');
		if (!empty($hook_result['order_line_parameters_for_js'])) {
			$order_line_parameters_for_js = $hook_result['order_line_parameters_for_js'];
			$tpl->assign('hook_order_line_html_head', $hook_result['order_line_html_head']);
			$tpl->assign('order_lines_responsive', $hook_result['order_lines_responsive']);
		}
		if ($form_error_object->count() > 0) {
			// sdelaporte  13/12/2019 12:01:37	62215
			// Remplir la facture avec les infos du formulaire en cas d'échèc de l'envoi
			$nb_produits = $commande['nb_produits'];
			
			if (!empty($hook_result['order_line_parameters_for_js'])) {
				for($i=1; $i<=$nb_produits; $i++) {
					$line_data = array();
					foreach(array_keys($hook_result['order_line_parameters_for_js']) as $this_key) {
						$line_data[$this_key] = vb($commande[$this_key.$i]);
					}
					$tpl_order_lines[] = get_order_line($line_data, null, null, null, $i, $page, $commande);
				}
			}
		} elseif (!empty($id)) {
			if (empty($hook_result['order_lines_sql'])) {
				// Si aucun hook ne gère la récupération des lignes de commandes, on prends les valeurs PEEL par défaut
				if(!empty($GLOBALS['site_parameters']['order_article_order_by']) && $GLOBALS['site_parameters']['order_article_order_by'] == 'name') {
					$order_by = 'oi.nom_produit ASC';
				} elseif(!empty($GLOBALS['site_parameters']['order_article_order_by']) && $GLOBALS['site_parameters']['order_article_order_by'] == 'reference') {
					$order_by = 'oi.reference ASC';
				} else {
					$order_by = 'oi.id ASC';
				}
				$sql = "SELECT
					oi.reference AS ref
					, oi.nom_produit AS nom
					, oi.prix AS purchase_prix
					, oi.prix_ht AS purchase_prix_ht
					, oi.prix_cat
					, oi.prix_cat_ht
					, oi.quantite
					, oi.poids
					, oi.tva
					, oi.tva_percent
					, oi.produit_id AS id
					, oi.attributs_list
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
					, oi.email_check ";
				if( check_if_module_active('listecadeau') ) {
					$sql .= ", oi.listcadeaux_owner ";
				}
				if( check_if_module_active('tnt') ) {
					$sql .= ", oi.tnt_parcel_number ";
					$sql .= ", oi.tnt_tracking_url ";
				}
				$sql .= "FROM peel_commandes_articles oi
					WHERE commande_id = '" . intval($id) . "' AND " . get_filter_site_cond('commandes_articles', 'oi', true) . "
					ORDER BY ".$order_by;
			} else {
				// SQL de sélection de produit qui vient du hook, pour gérer la liste de produits sur une autre table que peel_commandes_articles.
				$sql = $hook_result['order_lines_sql'];
			}
			$query = query($sql);
			$nb_produits = num_rows($query);
		} else {
			$nb_produits = 0;
		}
		$i = 1;
		if (!empty($query)) {
			while ($line_data = fetch_assoc($query)) {
				// var_dump($line_data);
				$size_options_html = '';
				$color_options_html = '';
				if (!empty($line_data['id'])) {
					$product_object = new Product($line_data['id'], null, false, null, true, !check_if_module_active('micro_entreprise'));
				}
				if(!empty($product_object)) {
					// traitement particulier pour le prix. L'utilisation de la fonction vb() n'est pas appropriée car il faut permettre l'insertion de produit au montant égal à zéro (pour offrir.)
					$line_data['image'] = $product_object->get_product_main_picture();
					$line_data['image_thumbs'] = StringMb::str_form_value(thumbs($product_object->get_product_main_picture(), 50, 50, 'fit', null, null, true, true));
					$line_data['prix_cat'] = round($line_data['prix_cat'] * vn($commande['currency_rate']), 5);
					$line_data['prix_cat_ht'] = round($line_data['prix_cat_ht'] * vn($commande['currency_rate']), 5);
					$line_data['purchase_prix'] = round($line_data['purchase_prix'] * vn($commande['currency_rate']), 5);
					$line_data['purchase_prix_ht'] = round($line_data['purchase_prix_ht'] * vn($commande['currency_rate']), 5);
					$line_data['remise'] = round($line_data['remise'] * vn($commande['currency_rate']), 5);
					$line_data['remise_ht'] = round($line_data['remise_ht'] * vn($commande['currency_rate']), 5);
					
					// Code pour recupérer select des tailles
					$possible_sizes = $product_object->get_possible_sizes();				
					if (!empty($line_data['taille']) && !in_array($line_data['taille'], $possible_sizes)) {
						$possible_sizes[$line_data['taille_id']] = $line_data['taille'];
					}
					if (!empty($possible_sizes)) {
						foreach ($possible_sizes as $this_size_id => $this_size_name) {
							$size_options_html .= '<option value="' . intval($this_size_id) . '" ' . frmvalide($this_size_name == $line_data['taille'], ' selected="selected"') . '>' . $this_size_name . '</option>';
						}
					}
					$possible_colors = $product_object->get_possible_colors();
					if (!empty($line_data['couleur']) && !in_array($line_data['couleur'], $possible_colors)) {
						$possible_colors[$line_data['couleur_id']] = $line_data['couleur'];
					}
					if (!empty($possible_colors)) {
						foreach ($possible_colors as $this_color_id => $this_color_name) {
							$color_options_html .= '<option value="' . intval($this_color_id) . '" ' . frmvalide($this_color_name == $line_data['couleur'], ' selected="selected"') . '>' . $this_color_name . '</option>';
						}
					}
				}
				$line_data['i'] = $i;
				$tva_options_html = get_vat_select_options(vb($line_data['tva_percent']));
				$tpl_order_lines[] = get_order_line($line_data, $color_options_html, $size_options_html, $tva_options_html, $i, $page, $commande);
				$i++;
				if(!empty($product_object)) {
					unset($product_object);
				}
			}
		}


		if(!empty($GLOBALS['site_parameters']['order_adresse_difference_color']) && ($commande['zip_bill'] != vb($commande['zip_ship']) || $commande['adresse_bill'] != vb($commande['adresse_ship']) || $commande['ville_bill'] != vb($commande['ville_ship']))) {
			$tpl->assign('order_adresse_difference_color', true);
		}
		$tpl->assign('order_lines', $tpl_order_lines);

		$tpl->assign('avoir', fprix(get_float_from_user_input(vn($commande['avoir'])), false, vb($commande['devise']), true, vn($commande['currency_rate']), false));
		$tpl->assign('lang', vb($commande['lang']));
		$tpl->assign('code_promo', vb($commande['code_promo']));
		$tpl->assign('percent_code_promo', vn($commande['percent_code_promo']));
		$tpl->assign('valeur_code_promo', vn($commande['valeur_code_promo']));

		$tpl->assign('form_token', get_form_token_input('commander.php?mode=' . $action . '&commandeid=' . $id));
		$tpl->assign('id_utilisateur', vb($commande['id_utilisateur']));
		$tpl->assign('nb_produits', $nb_produits);

		$tpl->assign('get_mode', $_GET['mode']);

		if (!empty($order_line_parameters_for_js)) {
			// Le paramétrage de la fonction get_order_line vient du hook
			// Cela permet de gérer un formulaire de produit différent de celui par défaut.
			$order_lines_parameters_for_js_array = $order_line_parameters_for_js;
		} else {
			// cas standard
			$order_lines_parameters_for_js_array = array('id' => '[id]', 'ref' => '[ref]', 'nom' => '[nom]', 'image_thumbs' => '[image_thumbs]', 'image' => '[image_large]', 'quantite' => '[quantite]', 'remise' => '[remise]', 'remise_ht' => '[remise_ht]', 'percent' => '[percent]', 'purchase_prix' => '[purchase_prix]', 'purchase_prix_ht' => '[purchase_prix_ht]', 'tva_percent' => '[tva_percent]', 'prix_cat' => '[prix_cat]', 'prix_cat_ht' => '[prix_cat_ht]');
		}
		$GLOBALS['js_content_array'][] = "new_order_line_html='".filtre_javascript(get_order_line($order_lines_parameters_for_js_array, '[color_options_html]', '[size_options_html]', '[tva_options_html]', '[i]', $page, $commande), true, true, false, true, false)."';";

		$tpl->assign('site_avoir', $GLOBALS['site_parameters']['avoir']);
		if (check_if_module_active('parrainage')) {
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
		$tpl->assign('is_fianet_sac_module_active', check_if_module_active('fianet_sac'));
		if(check_if_module_active('fianet_sac')) {
			require_once($GLOBALS['fonctionsfianet_sac']);
			$tpl->assign('fianet_analyse_commandes', get_sac_order_link($id));
		}
		if ($page == 'quote_prepare') {
			$create_or_update_title = $GLOBALS['STR_ADMIN_COMMANDER_QUOTE_CREATE_OR_UPDATE_TITLE'];
		} else {
			$create_or_update_title = $GLOBALS['STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE'];
		}
		$tpl->assign('is_order_modification_allowed', $is_order_modification_allowed);
		$tpl->assign('zone_tva', vb($commande['zone_tva']));
		$tpl->assign('default_vat_select_options', get_vat_select_options($default_vat));
		$tpl->assign('STR_IMAGE', $GLOBALS['STR_IMAGE']);
		$tpl->assign('STR_ADMIN_TECHNICAL_ORDER_NUMBER', $GLOBALS['STR_ADMIN_TECHNICAL_ORDER_NUMBER']);
		$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_AUTOCOMPLETE_ORDER_ADRESSES', $GLOBALS['STR_ADMIN_AUTOCOMPLETE_ORDER_ADRESSES']);
		$tpl->assign('STR_ADMIN_COMMANDER_WARNING_EDITION_NOT_ALLOWED', $GLOBALS['STR_ADMIN_COMMANDER_WARNING_EDITION_NOT_ALLOWED']);
		$tpl->assign('STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE', $create_or_update_title);
		$tpl->assign('STR_INVOICE', $GLOBALS['STR_INVOICE']);
		$tpl->assign('STR_ADMIN_CREATE_BILL_NUMBER_BEFORE', $GLOBALS['STR_ADMIN_CREATE_BILL_NUMBER_BEFORE']);
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
		$tpl->assign('STR_ADMIN_COMMENTS', $GLOBALS['STR_ADMIN_COMMENTS']);
		$tpl->assign('STR_COMMENTS', $GLOBALS['STR_COMMENTS']);
		$tpl->assign('STR_REFERENCE_IF_KNOWN', $GLOBALS['STR_REFERENCE_IF_KNOWN']);
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
		$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL']);
		$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL_CONFIRM', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL_CONFIRM']);
		$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL']);
		$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL_CONFIRM', $GLOBALS['STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL_CONFIRM']);
		$tpl->assign('STR_ADMIN_COMMANDER_WITH_PARTIAL_AMOUNT', $GLOBALS['STR_ADMIN_COMMANDER_WITH_PARTIAL_AMOUNT']);
		$tpl->assign('STR_ADMIN_COMMANDER_FIANET_FUNCTIONS', $GLOBALS['STR_ADMIN_COMMANDER_FIANET_FUNCTIONS']);
		$tpl->assign('STR_ADMIN_COMMANDER_INFORMATION_ON_THIS_ORDER', $GLOBALS['STR_ADMIN_COMMANDER_INFORMATION_ON_THIS_ORDER']);
		$tpl->assign('STR_ORDER_NUMBER', $GLOBALS['STR_ORDER_NUMBER']);
		$tpl->assign('STR_ADMIN_COMMANDER_PAYMENT_DATE', $GLOBALS['STR_ADMIN_COMMANDER_PAYMENT_DATE']);
		$tpl->assign('STR_ADMIN_COMMANDER_DELIVERY_DATE', $GLOBALS['STR_ADMIN_COMMANDER_DELIVERY_DATE']);
		$tpl->assign('STR_ADMIN_COMMANDER_INVOICE_DATE', $GLOBALS['STR_ADMIN_COMMANDER_INVOICE_DATE']);
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
		$tpl->assign('STR_ORDER_SUSPECT', $GLOBALS['STR_ORDER_SUSPECT']);
		$tpl->assign('STR_ADMIN_TOTAL_TTC_ALL_INCLUDE', $GLOBALS["STR_ADMIN_TOTAL_TTC_ALL_INCLUDE"]);
		$tpl->assign('STR_ADMIN_TOTAL_HT_ALL_INCLUDE', $GLOBALS["STR_ADMIN_TOTAL_HT_ALL_INCLUDE"]);
		$tpl->assign('STR_ADMIN_INCLUDING_VAT', $GLOBALS["STR_ADMIN_INCLUDING_VAT"]);
		$tpl->assign('STR_PROFORMA', $GLOBALS["STR_PROFORMA"]);
		$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL', $GLOBALS["STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL"]);
		$tpl->assign('STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL_CONFIRM', $GLOBALS["STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL_CONFIRM"]);
		
		// Hook pour ajouter des variables smarty à utiliser dans le fichier admin_commande_details.tpl
		$hook_result = call_module_hook('affiche_details_commande_tpl', array('order_infos' => vb($commande), 'order_lines' => $tpl_order_lines, 'bill_mode' => $page), 'array');
		foreach($hook_result as $this_key => $this_value) {
			$tpl->assign($this_key, $this_value);
		}
		$output .= call_module_hook('affiche_details_commande', array('order_infos' => vb($commande), 'order_lines' => $tpl_order_lines), 'string');
		$output .= $tpl->fetch();
	} elseif (!empty($id)) {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS["STR_ADMIN_COMMANDER_NO_ORDER_WITH_ID_FOUND"], $id)))->fetch();
	}
	return $output;
}


/**
 * Permet d'envoyer à l'utilisateur sa facture au format pdf par email
 *
 * @param array $frm Array with all fields data
 * @return
 */
function send_facture_pdf_commandes($frm)
{
	$output = '';
	if (!empty($frm)) {
		$sql = "SELECT email
			FROM peel_commandes
			WHERE id = '" . intval($frm['id']) . "' AND " . get_filter_site_cond('commandes', null) . "";
		$query = query($sql);
		$result = fetch_assoc($query);
		if (!empty($result['email'])) {
			if (vb($_REQUEST['mode']) != 'sendfacturepdf') {
				sendclient($frm['id'], 'html');
			} else {
				sendclient($frm['id'], 'pdf', $frm['bill_type']);
			}
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_MSG_ORDER_SENT_OK'], intval($frm['id']), $result['email'])))->fetch();
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ERR_NO_EMAIL_KNOWN_FOR_ORDER'], intval($frm['id']))))->fetch();
		}
	} else {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ERR_NO_EMAIL_KNOWN_FOR_ORDER'], intval($frm['id']))))->fetch();
	}
	return $output;
}


/**
 * Crée ou modifie en base de données une commande et les produits commandés qui y sont associés
 * ATTENTION : Les données venant de l'utilisateur n'ont pas été retraitées avant l'appel à cet fonction
 *
 * @param array $frm Array with all fields data
 * @return
 */
function save_commande_in_database($frm)
{
	// Création des variables
	$total_produit = 0;
	$total_produit_ht = 0;
	$total_remise = 0;
	$total_remise_ht = 0;
	$articles = array();
	$frm['total_ecotaxe_ttc'] = 0;
	$frm['total_ecotaxe_ht'] = 0;
	$frm['total_poids'] = 0;
	$frm['total_points'] = 0;
	if (!isset($frm['delivery_tracking'])) {
		$frm['delivery_tracking'] = null;
	}
	if (empty($frm['societe2']) && empty($frm['nom2']) && empty($frm['prenom2'])) {
		// On ne remplit automatiquement la société et le nom que si vraiment l'ensemble de l'adresse de livraison n'était pas définie
		// On remplit ces champs même pour un mode de livraison ne nécessitant pas d'adresse, c'est utile pour savoir à qui est destiné le colis.
		$frm['societe2'] = vb($frm['societe1']);
		$frm['nom2'] = vb($frm['nom1']);
		$frm['prenom2'] = vb($frm['prenom1']);
	}
	if (!empty($frm['bill_mode']) && $frm['bill_mode']!='commander') {
		$form_usage = $frm['bill_mode'];
	} else {
		$form_usage = 'order';
	}
	handle_specific_fields($frm, $form_usage);

	// Le code de préremplissage des informations de facturation est géré par la fonction create_or_update_order, et uniquement à cet endroit.
	if (empty($frm['nb_produits'])) {
		$frm['nb_produits'] = 5;
	}
	if (empty($frm['lang'])) {
		$frm['lang'] = $_SESSION['session_langue'];
	}
	if (!isset($frm['site_id'])) {
		// Site id absent pour cette commande, il ne faut pas avoir de valeur vide ou à 0 pour une commande, elle est forcement associée à un site.
		if (!empty($_SESSION['session_admin_multisite'])) {
			// L'administrateur a choisi un site à administrer spécifiquement
			$frm['site_id'] = $_SESSION['session_admin_multisite'];
		} else {
			// on utilise l'id du site courant
			$frm['site_id'] = $GLOBALS['site_id'];
		}
	}

	if (empty($frm['commandeid'])) {
		if (!empty($frm['email1'])) {
			// On crée une nouvelle commande
			$sql = "SELECT id_utilisateur, email
				FROM peel_utilisateurs
				WHERE email = '" . nohtml_real_escape_string($frm['email1']) . "' AND " . get_filter_site_cond('utilisateurs', null) . "";
			$result = query($sql);
		}
		if (!empty($result) && $u = fetch_object($result)) {
			$frm['email'] = $u->email;
			$frm['id_utilisateur'] = $u->id_utilisateur;
		} elseif(!empty($frm['email1'])) {
			// Création de l'utilisateur si on ne le trouve pas uniquement si l'email est renseigné
			$frm['email'] = vb($frm['email1']);
			$new_user_infos = array('priv' => 'util',
				'email' => vb($frm['email1']),
				'mot_passe' => vb($frm['mot_passe']),
				'prenom' => vb($frm['prenom1']),
				'nom' => vb($frm['nom1']),
				'societe' => vb($frm['societe1']),
				'telephone' => vb($frm['contact1']),
				'adresse' => vb($frm['adresse1']),
				'code_postal' => vb($frm['code_postal1']),
				'ville' => vb($frm['ville1']),
				'pays' => vb($frm['pays1']),
				'newsletter' => 1,
				'commercial' => 1);
			$new_user_result = insere_utilisateur($new_user_infos, false, false, false);
			if(is_numeric($new_user_result)) {
				// Nouvelle id créée
				$frm['id_utilisateur'] = $new_user_result;
			} elseif(is_array($new_user_result)) {
				// Ancien utilisateur trouvé - pas cohérent car on a déjà cherché l'utilisateur ci-dessus, mais sécurité quand même de traiter ce cas
				$frm['id_utilisateur'] = $new_user_result['id_utilisateur'];
			} else {
				// Echec de création de l'utilisateur
				$frm['id_utilisateur'] = 0;
			}
		} else {
			// commande sans utilisateur associé.
			$frm['id_utilisateur'] = 0;
		}
	} else {
		if(in_array('peel_commandes', listTables())) {
			// Recherche d'information sur la commande avant modification
			$query = query('SELECT email, zone, zone_franco, zone_tva, pays_ship AS pays
				FROM peel_commandes
				WHERE ' . get_filter_site_cond('commandes') . ' AND id = ' . intval(vn($frm['commandeid'])));
			$existing_order_infos = fetch_assoc($query);
			// informations liées à la zone récupérées de peel_commandes déjà existant pour éviter toute discordance par rapport aux données déjà sauvegardées si plusieurs zones sont rattachées au même pays et au même site
			$frm['zoneId'] = $existing_order_infos['zone'];
			$frm['zoneFranco'] = $existing_order_infos['zone_franco'];
			$frm['apply_vat'] = $existing_order_infos['zone_tva'];
			$frm['pays'] = $existing_order_infos['pays'];
		}
		
		if((!empty($GLOBALS['site_parameters']['autocomplete_order_adresses_with_account_info_if_order_email_change']) && $existing_order_infos['email'] != $frm['email']) || !empty($frm['autocomplete_order_adresses_with_account_info'])) {
			// L'auteur de la commande a changé. On change les informations relative à l'utilisateur de cette commande.
			// Utile pour modifier une commande après une duplication de commande (module duplicate du module premium.)
			$query = query('SELECT societe, prenom, nom_famille AS nom, adresse, code_postal AS zip, ville, pays, email, telephone
				FROM peel_utilisateurs
				WHERE email = "' . nohtml_real_escape_string($frm['email']) . '" AND ' . get_filter_site_cond('utilisateurs'));
			if($result = fetch_assoc($query)) {
				if (!empty($frm['adresses_fields_array'])) {
					// $frm['adresses_fields_array'] est défini dans handle_specific_fields. Il n'est pas rempli dans le cas où il n'y a aucun champ spécifique concernant les adresses d'utilisateurs (se terminant par _ship ou _bill)
					foreach($frm['adresses_fields_array'] as $this_item) {
						if ($this_item == 'telephone') {
							$this_frm_item = 'contact';
						} elseif ($this_item == 'zip') {
							$this_frm_item = 'code_postal';
						} else {
							$this_frm_item = $this_item;
						}
						if ($this_frm_item == 'pays') {
							$this_result = get_country_name($result['pays']);
						} else {
							$this_result = $result[$this_item];
						}
						$frm[$this_frm_item . '1'] = $this_result;
						$frm[$this_frm_item . '2'] = $this_result;
					}
				} else {
					foreach($result as $key => $data) {
						if ($key === 'telephone') {
							$this_frm_item = 'contact';
						} elseif ($key === 'zip') {
							$this_frm_item = 'code_postal';
						} else {
							$this_frm_item = $key;
						}
						if ($this_frm_item === 'pays') {
							$this_result = get_country_name($result['pays']);
						} else {
							$this_result = $result[$key];
						}
						$frm[$this_frm_item . '1'] = $this_result;
						$frm[$this_frm_item . '2'] = $this_result;
					}
				}
			}
		}
	}
	// Calcul des coûts et insertion de la commande
	if ((empty($frm['currency_rate']) || empty($frm['devise']))) {
		if (isset($frm['devise']) && $frm['devise'] != $GLOBALS['site_parameters']['code']) {
			// Si la devise de la commande n'est pas celle de la boutique, alors on récupère le taux de change de la devise
			$res = query("SELECT p.conversion
				FROM peel_devises p
				WHERE p.code = '" . nohtml_real_escape_string($frm['devise']) . "' AND " . get_filter_site_cond('devises', 'p') . "");
		}
		if (!empty($res) && $tab = fetch_assoc($res)) {
			$frm['currency_rate'] = $tab['conversion'];
		} else {
			// Valeur par défaut de la devise
			$frm['devise'] = $GLOBALS['site_parameters']['code'];
			$frm['currency_rate'] = $GLOBALS['site_parameters']['conversion'];
		}
	}
	
	if(empty($frm['zoneId'])) {
		if (check_if_module_active('departements')) {
			$zone = departements_get_zone_id($frm['code_postal2']);	
			if(!empty($zone['zone'])) {
				$frm['zoneId'] = $zone['zone'];
				$frm['pays'] = 'France';
				$frm['apply_vat'] = true;
			}
		} elseif(empty($GLOBALS['site_parameters']['mode_transport'])) {
			// Si pas de livraison on fixe des valeurs par défaut.
			$frm['zoneId'] = false;
			$frm['apply_vat'] = true;
			$frm['zoneFranco'] = '';
		} elseif(!empty($frm['pays2'])) {
			// On récupère les informations sur les zones
			$sqlPays = 'SELECT p.id, p.pays_' . $frm['lang'] . ' as pays, z.id AS zone, z.tva, z.on_franco
				FROM peel_pays p
				' . (!empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id'])?'
				INNER JOIN peel_zones z ON FIND_IN_SET(z.id,p.zone) AND ' . get_filter_site_cond('zones', 'z') . '':'
				INNER JOIN peel_zones z ON z.id=p.zone AND ' . get_filter_site_cond('zones', 'z') . '') . '
				WHERE p.etat = "1" AND p.pays_' . $frm['lang'] . '="' . nohtml_real_escape_string($frm['pays2']) . '"  AND ' . get_filter_site_cond('pays', 'p') . '
				ORDER BY z.on_franco DESC, z.tva ASC
				LIMIT 1';
			$query = query($sqlPays);
			if ($result = fetch_assoc($query)) {
				$frm['pays'] = $result['pays'];
				$frm['zoneId'] = $result['zone'];
				if(!isset($frm['apply_vat'])){
					// Si $frm['apply_vat'] est déjà défini, alors on garde la valeur qui a priorité sur la configuration du pays en BDD
					$frm['apply_vat'] = ($result['tva'] && !is_user_tva_intracom_for_no_vat($frm['id_utilisateur']) && !check_if_module_active('micro_entreprise'));
				}
				$frm['zoneFranco'] = $result['on_franco'];
			} else {
				$frm['zoneId'] = false;
				$frm['pays'] = '';
				if(!isset($frm['apply_vat'])){
					// Si $frm['apply_vat'] est déjà défini, alors on garde la valeur qui a priorité sur la configuration du pays en BDD
					$frm['apply_vat'] = false;
				}
				$frm['zoneFranco'] = '';
			}
		}
	}
	// L'ordre des produits a peut-être été modifié par l'administrateur, donc on prend les produits dans l'ordre du POST. Le tableau product_order_array sert à faire un mappage de l'ordre des produits tel qu'affichée sur la page de détail de commade et les numéros de ligne. product_order_array contient les numéros des lignes de produit, mais dans l'ordre que l'admin a choisi.
	$product_order_array = array();
	foreach($frm as $key => $data) {
		if (StringMb::substr($key, 0, 3) == "ref" && is_numeric(StringMb::substr($key, 3))) {
			$product_order_array[]=StringMb::substr($key, 3);
		}
	}
	// On calcul les totaux de produits
	foreach ($product_order_array as $i) {
		if (isset($frm["p" . $i]) && isset($frm["q" . $i]) && isset($frm["t" . $i])) {

			if(!empty($frm["id" . $i])) {
				$this_article['product_id'] = $frm["id" . $i];
			} else {
				$this_article['product_id'] = get_product_id_by_name(vb($frm["l" . $i]));
			}
			$product_object = new Product($this_article['product_id'], null, false, null, true, !check_if_module_active('micro_entreprise'));

			if (check_if_module_active('conditionnement') && !empty($frm["cdt" . $i])) {
				// Les produits sont conditionnés sous forme de lot, mais lorsque ce module est activé
				// on souhaite gérer les quantités et les stocks par produits individuels
				$real_stock_used = $frm["cdt" . $i] * $frm["q" . $i];
			} else {
				// Cas général de gestion des quantités
				$real_stock_used = intval($frm["q" . $i]);
			}

			if (!empty($GLOBALS['site_parameters']['ordered_product_automatic_price_calculation']) && in_array($frm['ref'.$i], $GLOBALS['site_parameters']['ordered_product_automatic_price_calculation'])) {
				// Dans ce mode on récupère le prix du produit avec get_final_price, qui permet notamment le calcul automatique des prix par lot.
				// On force la valeur de frm["p" . $i] avec le résultat de get_final_price.
				$frm["p" . $i] = $product_object->get_final_price(0, true, false, false, false, $real_stock_used);
				// La quantité a déjà été appliqué dans get_final_price
				$real_stock_used = 1; 
			}
			if (display_prices_with_taxes_in_admin ()) {
				$total_produit += get_float_from_user_input($frm["p" . $i], $frm['currency_rate']) * get_float_from_user_input($real_stock_used);
				$total_produit_ht += get_float_from_user_input($frm["p" . $i], $frm['currency_rate']) * get_float_from_user_input($real_stock_used) / (1 + get_float_from_user_input($frm["t" . $i]) / 100);
				// Remise en EUR
				$total_remise += get_float_from_user_input(vn($frm["remis" . $i]), $frm['currency_rate']) * get_float_from_user_input($real_stock_used);
				$total_remise_ht += get_float_from_user_input(vn($frm["remis" . $i]), $frm['currency_rate']) * get_float_from_user_input($real_stock_used) / (1 + get_float_from_user_input($frm["t" . $i]) / 100);
			} else {
				$total_produit += get_float_from_user_input($frm["p" . $i], $frm['currency_rate']) * get_float_from_user_input($real_stock_used) * (1 + get_float_from_user_input($frm["t" . $i]) / 100);
				$total_produit_ht += get_float_from_user_input($frm["p" . $i], $frm['currency_rate']) * get_float_from_user_input($real_stock_used);
				// Remise en EUR
				$total_remise += get_float_from_user_input(vn($frm["remis" . $i]), $frm['currency_rate']) * get_float_from_user_input($real_stock_used) * (1 + get_float_from_user_input($frm["t" . $i]) / 100);
				$total_remise_ht += get_float_from_user_input(vn($frm["remis" . $i]), $frm['currency_rate']) * get_float_from_user_input($real_stock_used);
			}
			if(!$frm['apply_vat']){
				// Pas de TVA applicable pour cette commande. Maintenant qu'on a bien calculé les HT, on fait en sorte que les TTC soient égaux aux HT
				$total_produit = $total_produit_ht;
				$total_remise = $total_remise_ht;
			}
		}
	}
	// Insertion des produits commandés
	$total_ttc = 0;

	foreach ($product_order_array as $i) {
		if (!isset($frm["l" . $i]) || empty($frm["q" . $i])) {
			continue;
		}
		// Récupère les variables dans le formulaire
		$nom = $frm["l" . $i];
		$quantite = get_float_from_user_input($frm["q" . $i]);
		if (empty($quantite)) {
			continue;
		}
		
		if (check_if_module_active('conditionnement') && !empty($frm["cdt" . $i])) {
			// Les produits sont conditionnés sous forme de lot, mais lorsque ce module est activé
			// on souhaite gérer les quantités et les stocks par produits individuels
			$real_stock_used = get_float_from_user_input($frm["cdt" . $i]) * $quantite;
		} else {
			// Cas général de gestion des quantités
			$real_stock_used = $quantite;
		}
		if (!empty($GLOBALS['site_parameters']['ordered_product_automatic_price_calculation']) && in_array($frm['ref'.$i], $GLOBALS['site_parameters']['ordered_product_automatic_price_calculation'])) {
			// Dans ce mode on récupère le prix du produit avec get_final_price, qui permet notamment le calcul automatique des prix par lot.
			// On force la valeur de frm["p" . $i] avec le résultat de get_final_price.
			$frm["p" . $i] = $product_object->get_final_price(0, true, false, false, false, $real_stock_used);
			// La quantité a déjà été appliqué dans get_final_price
			$real_stock_used = 1; 
		}

		if (display_prices_with_taxes_in_admin ()) {
			$prix_cat = get_float_from_user_input(vn($frm["p_cat" . $i]), $frm['currency_rate']);
			$prix_cat_ht = $prix_cat / (1 + vn($frm["t" . $i]) / 100);
			// Calcul remise en EUR
			$remise = get_float_from_user_input(vn($frm["remis" . $i]), $frm['currency_rate']);
			$remise_ht = $remise / (1 + $frm["t" . $i] / 100);
			// On charge la valeur du prix sans utiliser le calcul du prix après toutes les remises
			$prix = get_float_from_user_input($frm["p" . $i], $frm['currency_rate']);
			$prix_ht = $prix / (1 + vn($frm["t" . $i])/ 100);
		} else {
			$prix_cat_ht = get_float_from_user_input(vn($frm["p_cat" . $i]), $frm['currency_rate']);
			$prix_cat = $prix_cat_ht * (1 + vn($frm["t" . $i]) / 100);
			// Calcul remise en EUR
			$remise_ht = get_float_from_user_input(vn($frm["remis" . $i]), $frm['currency_rate']);
			$remise = $remise_ht * (1 + vn($frm["t" . $i]) / 100);
			// On charge la valeur du prix sans utiliser le calcul du prix après toutes les remises
			$prix_ht = get_float_from_user_input($frm["p" . $i], $frm['currency_rate']);
			$prix = $prix_ht * (1 + vn($frm["t" . $i]) / 100);
		}
		if(!$frm['apply_vat']){
			// Pas de TVA applicable pour cette commande. Maintenant qu'on a bien calculé les HT, on fait en sorte que les TTC soient égaux aux HT
			$frm["t" . $i] = 0;
			$prix_cat = $prix_cat_ht;
			$prix = $prix_ht;
			$remise = $remise_ht;
		}
		// Calcul remise en %
		$remise_percent = get_float_from_user_input(vn($frm["perc" . $i]));

		$total_prix = $prix * $real_stock_used;
		$total_prix_ht = $prix_ht * $real_stock_used;
		$tva = $total_prix - $total_prix_ht;
		// Lie la commande au produit
		if(!empty($frm["id" . $i])) {
			$this_article['product_id'] = $frm["id" . $i];
		} else {
			$this_article['product_id'] = get_product_id_by_name($nom);
		}
		$product_object = new Product($this_article['product_id'], null, false, null, true, !check_if_module_active('micro_entreprise'));
		$this_article['product_name'] = $nom;
		$this_article['quantite'] = $quantite;
		if (!empty($frm['color_' . $i]) && !is_numeric($frm['color_' . $i])) {
			$this_article['couleurId'] = 0;
			$this_article['couleur'] = vn($frm['color_' . $i]);
		} else {
			$this_article['couleurId'] = vn($frm['color_' . $i]);
		}
		if (!empty($frm['size_' . $i]) && !is_numeric($frm['size_' . $i])) {
			$this_article['tailleId'] = 0;
			$this_article['taille'] = vn($frm['size_' . $i]);
		} else {
			$this_article['tailleId'] = vn($frm['size_' . $i]);
		}
		$this_article['giftlist_owners'] = vn($frm['giftlist_owners_' . $i]);
		$this_article['data_check']['email_check'] = vb($frm['email_check_' . $i]);

		$this_article['prix'] = $prix;
		$this_article['prix_ht'] = $prix_ht;
		$this_article['prix_cat'] = $prix_cat;
		$this_article['prix_cat_ht'] = $prix_cat_ht;
		$this_article['total_prix'] = $total_prix;
		$this_article['total_prix_ht'] = $total_prix_ht;
		$this_article['tva_percent'] = $frm["t" . $i];
		$this_article['tva'] = $tva;
		$this_article['reference'] = vb($frm["ref" . $i]);
		$this_article['percent_remise_produit'] = vn($remise_percent);
		$this_article['remise'] = $remise * $quantite;
		$this_article['remise_ht'] = $remise_ht * $quantite;
		if (check_if_module_active('ecotaxe')) {
			$this_article['ecotaxe_ttc'] = 0;
			$this_article['ecotaxe_ht'] = 0;
			$product_ecotaxe_infos_query = query("SELECT e.*
				FROM peel_ecotaxes e
				INNER JOIN peel_produits p ON e.id = p.id_ecotaxe AND " . get_filter_site_cond('produits', 'p') . "
				WHERE p.id='" . intval($this_article['product_id']) . "' AND " . get_filter_site_cond('ecotaxes', 'e') . "");
			if ($product_ecotaxe_infos = fetch_assoc($product_ecotaxe_infos_query)) {
				if ($product_ecotaxe_infos['coefficient'] >0 ) {
					$this_article['ecotaxe_ttc'] = $product_ecotaxe_infos['coefficient'] * ($product_object->poids) * (1+$product_object->tva/100);
					$this_article['ecotaxe_ht'] = $product_ecotaxe_infos['coefficient'] * ($product_object->poids);
				} elseif (!empty($product_ecotaxe_infos['id'])) {
					$this_article['ecotaxe_ttc'] = $product_ecotaxe_infos['prix_ttc'];
					$this_article['ecotaxe_ht'] = $product_ecotaxe_infos['prix_ht'];
				}
				// Valeurs globales pour l'ensemble des produits
				$frm['total_ecotaxe_ht'] += $this_article['ecotaxe_ht'];
				$frm['total_ecotaxe_ttc'] += $this_article['ecotaxe_ttc'];
			}
		}
		$this_article['etat_stock'] = $product_object->on_stock;
		$this_article['on_information'] = $product_object->on_information;
		$this_article['scale_stock'] = $product_object->scale_stock;

		if (check_if_module_active('tnt')) {
			$this_article['tnt_parcel_number'] = vn($frm['tnt_parcel_number_' . $i]);
			$this_article['tnt_tracking_url'] = $frm['tnt_tracking_url_' . $i];
		}
		$this_article['nom_attribut'] = vn($frm['nom_attribut_' . $i]);
		$this_article['id_attribut'] = vn($frm['attributs_list_' . $i]);
		$this_article['total_prix_attribut'] = vn($frm['total_prix_attribut_' . $i]);
		$this_article['id_attribut'] = vb($frm['attributs_list_' . $i]);
		if ($tva > 0) {
			// recupération du prix des attributs en ht pour utiliser dans le calcul de option_ht
			$total_prix_attribut_ht = $this_article['total_prix_attribut'] / (1 + $tva / 100); 
		} else {
			$total_prix_attribut_ht = $this_article['total_prix_attribut'];
		}
		
		// Informations supplémentaires (non modifiable dans la mofification de la commande)
		$this_article['delai_stock'] = $product_object->delai_stock;

		$product_object->set_configuration($this_article['couleurId'], $this_article['tailleId'], $this_article['id_attribut'], check_if_module_active('reseller') && is_reseller()); // on fixe les options
		$this_article['poids'] = ($product_object->poids + $product_object->configuration_overweight) * $this_article['quantite'];
		if (empty($this_article['poids']) && !empty($frm['poids_' . $i])) {
			$this_article['poids'] = $frm['poids_' . $i];
		}
		$frm['total_poids'] += $this_article['poids'];
		$this_article['option'] = $product_object->format_prices($product_object->configuration_size_price_ht + $product_object->configuration_total_original_price_attributs_ht, $frm['apply_vat'], false, false, false) + $this_article['total_prix_attribut'];
		$this_article['option_ht'] = $product_object->format_prices($product_object->configuration_size_price_ht + $product_object->configuration_total_original_price_attributs_ht, false, false, false, false) + $total_prix_attribut_ht;

		$this_article['option'] = round($this_article['option'], 2); //On doit arrondir les valeurs tarifaires officielles
		$this_article['option_ht'] = round($this_article['option_ht'], 2); //On doit arrondir les valeurs tarifaires officielles
		$this_article['points'] = $product_object->points * $this_article['quantite'];
		$frm['total_points'] += $this_article['points'];
		/*
		  Non renseignés :
		  $this_article['giftlist_owners'] = ;
		  $this_article['email_check'] = ;
		 */
		if (!empty($this_article['product_id']) || !empty($this_article['product_name']) || !empty($this_article['reference']) || !empty($this_article['prix'])) {
			// Article vide
			$articles[$i] = $this_article;
		}
	}
	// On récupère les frais de port
	if (!empty($GLOBALS['site_parameters']['mode_transport']) && (!isset($frm['cout_transport']) || $frm['cout_transport'] == '')) {
		// Calcul du coût du transport
		$delivery_cost_infos = get_delivery_cost_infos($frm['total_poids'], $total_produit, vb($frm['type_transport']), $frm['zoneId'], $frm['nb_produits']);
		if ($delivery_cost_infos !== false) {
			$shipping_costs['tva_percent'] = ($frm['apply_vat'] ? $delivery_cost_infos['tva'] : 0);
			$cout_transport_ht = $delivery_cost_infos['cost_ht'];
		} else {
			// Pas de port trouvé pour ce poids et ce total
			$shipping_costs['tva_percent'] = 0;
			$cout_transport_ht = 0;
		}
		$cout_transport = vn($cout_transport_ht) * (1 + vn($shipping_costs['tva_percent']) / 100);
	} else {
		// Récupération des données du formulaire
		$cout_transport = get_float_from_user_input(vn($frm['cout_transport']), $frm['currency_rate']);
		$cout_transport_ht = vn($cout_transport) / (1 + get_float_from_user_input(vn($frm['tva_transport'])) / 100);
	}
	// On récupère le type de transport
	if (!empty($frm['type_transport'])) {
		$frm['type'] = get_delivery_type_name(vb($frm['type_transport']));
		$frm['typeId'] = $frm['type_transport'];
	}
	if(check_if_module_active('reseller') && is_reseller()) {
		$threshold_to_use = $GLOBALS['site_parameters']['minimal_amount_to_order_reve'];
	} else {
		$threshold_to_use = $GLOBALS['site_parameters']['minimal_amount_to_order'];
	}
    if (!isset($frm['small_order_overcost_amount']) || (isset($frm['small_order_overcost_amount']) && $frm['small_order_overcost_amount'] == '')) {
        if($total_produit < $GLOBALS['site_parameters']['small_order_overcost_limit'] && $total_produit >= $threshold_to_use) {
            $small_order_overcost_amount = $GLOBALS['site_parameters']['small_order_overcost_amount'];
        } else {
            $small_order_overcost_amount = 0;
        }
    } else {
		$small_order_overcost_amount = get_float_from_user_input(vn($frm['small_order_overcost_amount']), $frm['currency_rate']);
    }
	$tva_small_order_overcost = get_float_from_user_input(vn($frm['tva_small_order_overcost']), $frm['currency_rate']);
	if (!empty($frm['avoir'])) {
		// L'avoir est limité au total de la commande.
		// L'avoir est uniquement sur le TTC et n'affecte pas le calcul de la TVA
		$avoir = max(0, min(get_float_from_user_input(vn($frm['avoir']), $frm['currency_rate']), vn($total_produit) + vn($cout_transport) + vn($small_order_overcost_amount)));
	} else {
		$avoir = 0;
	}
	// Calcul du sous total pour pouvoir appliquer le coût du paiement en pourcentage
	$frm['sub_total'] = vn($total_produit) - vn($avoir) + vn($cout_transport) +  vn($small_order_overcost_amount);
	$frm['sub_total_ht'] = vn($total_produit_ht) + vn($cout_transport_ht) + (vn($small_order_overcost_amount) - vn($tva_small_order_overcost));
	// On recupère le coût de paiement. La fonction set_paiement définit les variables : $frm['tarif_paiement'], $frm['tarif_paiement_ht'] et $frm['tva_tarif_paiement']
	set_paiement($frm);
	$montant =  $frm['sub_total'] + vn($frm['tarif_paiement']);
	$montant_ht =  $frm['sub_total_ht'] + vn($frm['tarif_paiement_ht']);
	$total_tva = (vn($montant) + vn($avoir)) - vn($montant_ht);
	
	// Stockage des informations dans $frm pour envoi ensuite à create_or_update_order
	$frm['small_order_overcost_amount'] = $small_order_overcost_amount;
	$frm['tva_small_order_overcost'] = $tva_small_order_overcost;
	$frm['montant'] = $montant;
	$frm['montant_ht'] = $montant_ht;
	$frm['total_tva'] = $total_tva;
	$frm['total_produit'] = $total_produit;
	$frm['total_produit_ht'] = $total_produit_ht;
	$frm['tva_total_produit'] = $total_produit - $total_produit_ht;
	$frm['total_remise'] = $total_remise;
	$frm['total_remise_ht'] = $total_remise_ht;
	$frm['tva_total_remise'] = $total_remise - $total_remise_ht;
	$frm['cout_transport'] = vn($cout_transport);
	$frm['cout_transport_ht'] = vn($cout_transport_ht);
	$frm['tva_cout_transport'] = vn($cout_transport) - vn($cout_transport_ht);
	$frm['avoir'] = $avoir;
		
	$hook_output = call_module_hook('get_order_data', array('articles' => $articles, 'frm' => $frm), 'array');
	if (!empty($hook_output)) {
		// si le hook retourne un résultat, on remplace les variables calculées plus haut par le résultat du hook
		$frm = $hook_output['frm'];
		$articles = $hook_output['articles'];
	}
	// On crée la commande ou on la met à jour si elle existe déjà
	$order_id = create_or_update_order($frm, $articles);
	return $order_id;
}

/**
 * Crée ou modifie en base de données une commande et les produits commandés qui y sont associés
 *
 * @param array $line_data
 * @param string $color_options_html
 * @param string $size_options_html
 * @param string $tva_options_html
 * @param integer $i
 * @param string $page
 * @param array $commande
 * @return
 */
function get_order_line($line_data, $color_options_html, $size_options_html, $tva_options_html, $i, $page, &$commande)
{
	if (empty($size_options_html)) {
		$size_options_html = '<option value="">-</option>';
	}
	if (empty($color_options_html)) {
		$color_options_html = '<option value="">-</option>';
	}
	if (empty($tva_options_html)) {
		$tva_options_html = '<option value="">-</option>';
	}
	if (!is_numeric($i)) {
		// $i peut contenir le tag [i] qui est automatiquement remplacé lors de l'ajout dynamique de la ligne
		$line_style_id = 1;
	} else {
		$line_style_id = $i;
	}
	$hook_output = call_module_hook('get_order_line_html', array('line_style_id' => $line_style_id, 'i' => $i, 'line_data' => $line_data, 'page' => $page, 'commande' => &$commande), 'string');
	if (!empty($hook_output)) {
		$output = $hook_output;
	} else {
		if (display_prices_with_taxes_in_admin()) {
			$prix_cat_displayed = $line_data['prix_cat'];
			$purchase_prix_displayed = $line_data['purchase_prix'];
			$unit_fixed_remise_displayed = $line_data['remise'];
		} else {
			$prix_cat_displayed = $line_data['prix_cat_ht'];
			$purchase_prix_displayed = $line_data['purchase_prix_ht'];
			$unit_fixed_remise_displayed = $line_data['remise_ht'];
		}
		// Si nous sommes en mode édition de la commande et nous souhaitons réafficher les données sur les produits
		if (is_numeric($purchase_prix_displayed)) {
			// Prix tout taxe avant remise = prix remisé + remise
			// On détermine le montant de la remise fixe en euro($remise_fixed). Pour cela on déduit le montant de la remise % du montant de la remise globale (limite a deux chiffre apres la virgule)
			$unit_fixed_remise_displayed = round($unit_fixed_remise_displayed / $line_data['quantite'],2);
		}
		if (check_if_module_active('attributs')) {
			$attribute_display = str_replace("\n", '<br />', display_option_image(vb($line_data['nom_attribut']), true));
		}

		$output = tr_rollover($line_style_id, true, null, null, 'sortable_'.$i) .'
					<td>
						<img src="' . $GLOBALS['administrer_url'] . '/images/b_drop.png" alt="'.StringMb::str_form_value($GLOBALS['STR_DELETE']) . '" onclick="bootbox.confirm(\''.filtre_javascript($GLOBALS["STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM"], true, true, true) .'\', function(result) {if(result) {admin_delete_products_list_line(' . $i . ', \'order\');}}); return false;" title="' . StringMb::str_form_value($GLOBALS["STR_ADMIN_PRODUCT_ORDERED_DELETE"]) . '" style="cursor:pointer;max-width: none;" />
						<input name="email_check_' . $i . '" type="hidden" value="' . StringMb::str_form_value(vb($line_data['email_check'])) . '" />
						<input name="giftlist_owners_' . $i . '" type="hidden" value="' . StringMb::str_form_value(vb($line_data['listcadeaux_owner'])) . '" />
						<input name="nom_attribut_' . $i . '" type="hidden" value="' . StringMb::str_form_value(vb($line_data['nom_attribut'])) . '" />
						<input name="attributs_list_' . $i . '" type="hidden" value="' . StringMb::str_form_value(vb($line_data['attributs_list'])) . '" />
						<input name="poids_' . $i . '" type="hidden" value="' . StringMb::str_form_value(vb($line_data['poids'])) . '" />';
		if (check_if_module_active('tnt')) {
			$output .= '
						<input name="tnt_parcel_number_' . $i . '" type="hidden" value="' . StringMb::str_form_value(vb($line_data['tnt_parcel_number'])) . '" />
						<input name="tnt_tracking_url_' . $i . '" type="hidden" value="' . StringMb::str_form_value(vb($line_data['tnt_tracking_url'])) . '" />';
		}
		$output .= '
					</td>
					<td>
						<input class="form-control" name="id' . $i . '" style="width:100%" type="number" value="' . StringMb::str_form_value(vb($line_data['id'])) . '" />
					</td>
					<td>
						<input class="form-control" id="ref' . $i . '" name="ref' . $i . '" style="width:100%" type="text" value="' . StringMb::str_form_value(vb($line_data['ref'])) . '" />
					</td>
					<td>
						<input class="form-control" type="text" id="l' . $i . '" name="l' . $i . '" style="width:100%" value="' . StringMb::str_form_value($line_data['nom']) . '" />' . (isset($line_data['on_download'])?($line_data['on_download'] == 1?'<br /><a href="' . get_current_url(false) . '?mode=download">'.$GLOBALS["STR_ADMIN_PRODUITS_NUMERIC_PRODUCT_SEND"].'</a>':''):'') . '
					</td>
					<td id="s' . $i . '" class="center"><select style="width:64px" name="size_' . $i . '" class="form-control">' . $size_options_html . '</select></td>
					<td id="c' . $i . '" class="center"><select style="width:64px" name="color_' . $i . '" class="form-control">' . $color_options_html . '</select></td>
					<td><input class="form-control" type="number" name="q' . $i . '" style="width:100%" value="' . StringMb::str_form_value($line_data['quantite']) . '" id="q' . $i . '" /></td>
					<td><input class="form-control" type="text" name="p_cat' . $i . '" style="width:100%" value="' . StringMb::str_form_value($prix_cat_displayed) . '" id="p_cat' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'percentage\');" /></td>
					<td><input class="form-control" type="text" name="remis' . $i . '" style="width:100%" value="' . StringMb::str_form_value($unit_fixed_remise_displayed) . '" id="remis' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'amount\');" /></td>
					<td><input class="form-control" type="text" name="perc' . $i . '" style="width:100%" value="' . StringMb::str_form_value($line_data['percent']) . '" id="perc' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'percentage\');" /></td>
					<td><input class="form-control" '.((!empty($GLOBALS['site_parameters']['ordered_product_automatic_price_calculation']) && in_array($line_data['ref'], $GLOBALS['site_parameters']['ordered_product_automatic_price_calculation']))?'readonly="readonly"':'').' type="text" name="p' . $i . '" style="width:100%" value="' . StringMb::str_form_value($purchase_prix_displayed) . '" id="p' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'final\');" /></td>
					<td id="t' . $i . '">
						<select name="t' . $i . '" class="form-control">' . $tva_options_html . '</select>
					</td>
					<td> ' . vb($attribute_display) . ' </td>';
		$output .= '
					<td>';
		if (!empty($line_data['image'])) {
			$output .= '
						<a target="_image" href="' . StringMb::str_form_value($GLOBALS['repertoire_upload'].'/'.$line_data['image']) . '"><img src="' . $line_data['image_thumbs'] . '" alt="'.StringMb::str_form_value($line_data['nom']) . '" /></a>';
		}
		$output .= '
						</td>
					</tr>
';
	}

	return $output;
}

/**
 * Affichages des actions des modérateurs et administrateurs sur cet utilisateur
 *
 * @return
 */
function affiche_actions_moderations_user($user_id)
{
	$countResultats = 0;
	$width = array();
	$width['date'] = 12;
	$width['login'] = 8;
	$width['action'] = 10;
	$width['raison'] = 5;
	$width['remarque'] = 25;
	$width['data'] = 25;
	$output = '';
	$q = query('SELECT a.id_user, a.id_membre, a.action, a.data, a.raison, a.remarque, a.date, u.id_utilisateur, u.pseudo, u.email
		FROM peel_admins_actions a
		LEFT JOIN peel_utilisateurs u ON u.id_utilisateur=a.id_user AND ' . get_filter_site_cond('utilisateurs', 'u') . '
		WHERE a.id_membre="' . intval($user_id) . '" AND ' . get_filter_site_cond('admins_actions', 'a') . '
		ORDER BY a.date DESC
		LIMIT 500');
	while ($res = fetch_assoc($q)) {
		if ($countResultats == 0) {
			$output .= '
			<table style="background-color:#FFFFFF; border:1px; width:100%">
				<tr>
					<th class="menu" style="width:' . $width['date'] . '%;">'.$GLOBALS['STR_DATE'].'</th>
					<th class="menu" style="width:' . $width['login'] . '%;">'.$GLOBALS['STR_BY'].'</th>
					<th class="menu" style="width:' . $width['action'] . '%;">'.$GLOBALS['STR_ADMIN_ACTION'].'</th>
					<th class="menu" style="width:' . $width['data'] . '%;">'.$GLOBALS['STR_ADMIN_ADMIN_ACTIONS_DATA'].'</th>
					<th class="menu" style="width:' . $width['raison'] . '%;">'.$GLOBALS['STR_ADMIN_REASON'].'</th>
					<th class="menu" style="width:' . $width['remarque'] . '%;">'.$GLOBALS['STR_COMMENTS'].'</th>
				</tr>';
		}

		$texte = StringMb::nl2br_if_needed($res['remarque']);

		if ($res['data'] != "" && $res['action'] == 'SEND_EMAIL') {
			// Si un template a été envoyé, alors on récupère le contenu de ce template
			$data = explode('_', $res['data']);
			if (count($data) == 2) {
				$template_id = $data[1];
				if (is_numeric($template_id)) {
					$result_template = query('SELECT name
						FROM peel_email_template
						WHERE id="' . intval($template_id) . '" AND ' . get_filter_site_cond('email_template', null) . '
						LIMIT 1');
					$template_text = fetch_assoc($result_template);
					$res['data'] = '<b>'.$GLOBALS['STR_ADMIN_EMAIL_TEMPLATE'].'</b> : <br />' . (strpos($res['remarque'], $GLOBALS['STR_ADMIN_MUTIPLE_SENDING']) !== false?'<i style="color:red;">'.$GLOBALS['STR_ADMIN_MUTIPLE_SENDING'].'</i><br />':'') . $template_text["name"];
				}
			}
		}

		$output .= '
				<tr' . (($countResultats % 2) ? '' : ' class="line"') . '>
					<td class="center">' . strftime('%d/%m/%Y %H:%M:%S', strtotime($res['date'])) . '</td>
					<td class="center">' . (!empty($res['pseudo'])?$res['pseudo']:$res['email']) . '</td>
					<td class="center">' . $res['action'] . '</td>
					<td class="center">' . $res['data'] . '</td>
					<td class="center">' . $res['raison'] . '</td>
					<td class="justify">' . htmlspecialchars($res['remarque']) . '</td>
				</tr>';
		$countResultats++;
	}
	if ($countResultats > 0) {
		$output .= '</table>';
	} else {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_NO_ADMIN_ACTION_FOUND_FOR_THIS_USER']))->fetch();
	}
	return $output;
}

/**
 * Inscrit les différentes actions administrateur
 * Liste des $actions possibles :
 * 	'SEARCH_USER', 'ADD_FILTER', 'EDIT_FILTER', 'DEL_FILTER', 'EDIT_AD', 'SUP_AD', 'EDIT_VOTE', 'SUP_DETAILS',
 * 	'EDIT_PROFIL', 'SUP_FORUM', 'ACTIVATE_COMPTE', 'NOTES_RECUES', 'NOTES_DONNEES', 'NOTE_PROFIL', 'AUTRE',
 * 	'SEND_EMAIL', 'CREATE_ORDER', 'EDIT_ORDER', 'SUP_ORDER', 'PHONE_EMITTED', 'EVENT', 'PHONE_RECEIVED'
 *
 * @param mixed $member_id
 * @param mixed $action
 * @param mixed $data
 * @param mixed $remarque
 * @param mixed $raison
 * @return
 */
function tracert_history_admin($member_id, $action, $data, $remarque = null, $raison = null)
{
	query('INSERT INTO peel_admins_actions(id_user, action, id_membre, data, remarque, raison, date, site_id)
		VALUES("' . intval(vn($_SESSION['session_utilisateur']['id_utilisateur'])) . '", "' . nohtml_real_escape_string($action) . '", "' . intval(vn($member_id)) . '", "' . nohtml_real_escape_string($data) . '", "' . nohtml_real_escape_string($remarque) . '", "' . nohtml_real_escape_string($raison) . '", "' . date('Y-m-d H:i:s', time()) . '", "' . nohtml_real_escape_string(get_site_id_sql_set_value(vb($GLOBALS['site_id']))) . '")');
}

/**
 * Affiche en liste les connexions
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_recherche_connexion_user($frm = null, $display_search_form = true)
{
	$sql_inner = '';
	$sql_cond = '';
	if (!empty($frm)) {
		if (!empty($frm['client_info'])) {
			if (empty($GLOBALS['site_parameters']['pseudo_is_not_used'])) {
				$sql_cond_array[] = 'u.pseudo LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"';
			}
			$sql_cond_array[] = 'u.email LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"';
			$sql_cond_array[] = 'u.societe LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"';
			$sql_cond_array[] = 'u.nom_famille LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"';
			$sql_cond_array[] = 'u.prenom LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"';
			$sql_cond .= ' AND ('.implode(' OR ', $sql_cond_array).')';
			unset($sql_cond_array);
			$sql_inner .= ' INNER JOIN peel_utilisateurs u ON c.user_id=u.id_utilisateur AND ' . get_filter_site_cond('utilisateurs', 'u') . '';
		}
		if (!empty($frm['user_ip'])) {
			$sql_cond .= ' AND CONCAT(FLOOR(c.user_ip/(256*256*256)), ".", (FLOOR(c.user_ip/(256*256)))%256, ".", (FLOOR(c.user_ip/256))%256, ".", c.user_ip%256) LIKE "%' . nohtml_real_escape_string($frm['user_ip']) . '%"';
		}
		if (!empty($frm['user_id'])) {
			$sql_cond .= ' AND c.user_id="' . nohtml_real_escape_string($frm['user_id']) . '"';
		}
		if (!empty($frm['date'])) {
			$sql_cond .= ' AND c.date LIKE "%' . nohtml_real_escape_string(date('Y-m-d', strtotime(str_replace('/', '-', $frm['date'])))) . '%"';
		}
	}
	$sql = "SELECT c.*
		FROM peel_utilisateur_connexions c 
		" . $sql_inner . "
		WHERE 1 " . $sql_cond . " AND " . get_filter_site_cond('utilisateur_connexions', 'c') . "";

	$Links = new Multipage($sql, 'affiche_liste_connexion_user');
	$HeaderTitlesArray = array('id' => $GLOBALS["STR_ADMIN_ID"], 'date' => $GLOBALS['STR_DATE'], 'user_ip' => $GLOBALS["STR_ADMIN_REMOTE_ADDR"]);
	if (check_if_module_active('geoip')) {
		if (!class_exists('geoIP')) {
			include($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php');
		}
		$geoIP = new geoIP();
		$HeaderTitlesArray[] = $GLOBALS['STR_COUNTRY']. '-IP';
		$HeaderTitlesArray[] = $GLOBALS['STR_COUNTRY'];
	}
	if(check_if_module_active('annonces')) {
		$HeaderTitlesArray[] = $GLOBALS["STR_MODULE_ANNONCES_ADS"];
	}
	$HeaderTitlesArray['user_login'] = $GLOBALS["STR_ADMIN_LOGIN"];
	$HeaderTitlesArray['user_id'] = $GLOBALS["STR_ADMIN_USER"];
	$HeaderTitlesArray['site_id'] = $GLOBALS["STR_ADMIN_WEBSITE"];
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = 'id';
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();
	if (!empty($results_array)) {
		// Affichage des connexions en liste
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_connexion_user_liste.tpl');
		$tpl->assign('action', get_current_url(false));
		$tpl->assign('date', vb($_GET['date']));
		$tpl->assign('user_ip', vb($_GET['user_ip']));
		$tpl->assign('client_info', vb($_GET['client_info']));
		$tpl->assign('user_id', vb($_GET['user_id']));
		$tpl->assign('action_maj', get_current_url(false) . '?mode=maj_statut');
		$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF']));
		$tpl->assign('display_search_form', $display_search_form);

		if (!empty($results_array)) {
			$tpl_results = array();
			$tpl->assign('links_header_row', $Links->getHeaderRow());

			$i = 0;
			foreach ($results_array as $connexion) {
				// L'utilisateur a peut-être été supprimé, donc current_user peut valoir null.
				$current_user = get_user_information($connexion['user_id'], true);
				$tpl_result = array('id' => $connexion['id'],
					'date' => get_formatted_date($connexion['date'], 'short', true),
					'ip' => (!a_priv('demo') ? long2ip(intval($connexion['user_ip'])): '0.0.0.0 [demo]'),
					'site_id' => get_site_name($connexion['site_id']),
					'user_id' => (!a_priv('demo')?'<a href="' . $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&amp;id_utilisateur=' . intval($connexion['user_id']) . '">'.$connexion['user_id'].'</a>':'private [demo]'),
					'prenom' => vb($current_user['prenom']),
					'nom_famille' => vb($current_user['nom_famille']),
					'user_login_displayed' => (!a_priv('demo')?(vb($current_user['etat'])?'<a href="' . $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&amp;id_utilisateur=' . intval($connexion['user_id']) . '">'.$connexion['user_login'].'</a>':'<span style="color:#AAAAAA">'.$connexion['user_login'].'</span>'):'private [demo]')
					);
				$rollover_style = null;
				if(!empty($geoIP)) {
					$country_id = $geoIP->geoIPCountryIDByAddr(long2ip(intval($connexion['user_ip'])));
					foreach(array('country_ip' => $country_id, 'country_account' => vn($current_user['pays'])) as $this_key => $this_value) {
						$sql = 'SELECT iso, pays_' . $_SESSION['session_langue'] . '
							FROM peel_pays
							WHERE id="' . intval($this_value) . '" AND ' . get_filter_site_cond('pays') . '
							LIMIT 1';
						$query = query($sql);
						if ($result = fetch_assoc($query)) {
							$tpl_result[$this_key] = getFlag($result['iso'], $result['pays_' . $_SESSION['session_langue']]);
						} else {
							$tpl_result[$this_key] = '?';
						}
					}
					if($country_id != $current_user['pays']) {
						$rollover_style = 'background-color:#FFAAAA';
					}
				}
				if(check_if_module_active('annonces')) {
					// On utilise vb ici, puisque active_ads_count n'est pas obligatoirement défini, dans le cas où l'utilisateur a été supprimé de la base de données.
					$tpl_result['active_ads_count'] = vn($current_user['active_ads_count']);
				}
				$tpl_result['tr_rollover'] = tr_rollover($i, true, $rollover_style);
				$tpl_results[] = $tpl_result;
				$i++;
			}
			if (!empty($geoIP)) {
				$geoIP->geoIPClose();
			}
			$tpl->assign('results', $tpl_results);
			$tpl->assign('links_multipage', $Links->GetMultipage());
		}
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_CONNEXION_USER_TITLE', $GLOBALS['STR_ADMIN_CONNEXION_USER_TITLE']);
		$tpl->assign('STR_ADMIN_DATE', $GLOBALS['STR_ADMIN_DATE']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_REMOTE_ADDR', $GLOBALS['STR_ADMIN_REMOTE_ADDR']);
		$tpl->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
		$tpl->assign('STR_ADMIN_USER', $GLOBALS['STR_ADMIN_USER']);
		$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
		$tpl->assign('STR_ADMIN_CONNEXION_NOTHING_FOUND', $GLOBALS['STR_ADMIN_CONNEXION_NOTHING_FOUND']);
		return $tpl->fetch();
	} else {
		return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_NO_RESULT']))->fetch();
	}
}

/**
 * Affichages des actions des modérateurs et administrateurs sur cet utilisateur
 *
 * @return
 */
function affiche_phone_event($user_id)
{
	$countResultats = 0;
	$width = array();
	$width['date'] = 12;
	$width['login'] = 8;
	$width['action'] = 10;
	$width['raison'] = 5;
	$width['remarque'] = 25;
	$width['data'] = 25;
	$output = '';
	$q = query('SELECT paa.*,u.pseudo AS pseudo_membre
		FROM peel_admins_actions paa
		LEFT JOIN peel_utilisateurs u ON u.id_utilisateur= ' . intval($user_id) . ' AND ' . get_filter_site_cond('utilisateurs', 'u') . '
		WHERE paa.id_user= ' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . ' AND paa.id_membre = ' . intval($user_id) . ' AND ((paa.action = "PHONE_EMITTED") OR (paa.action = "PHONE_RECEIVED")) AND paa.data="NOT_ENDED_CALL" AND ' . get_filter_site_cond('admins_actions', 'paa') . '
		ORDER BY paa.date DESC
		LIMIT 1');
	$res = fetch_assoc($q);
	$output = '
	<form class="entryform form-inline" method="post" id="phone" action="' . get_current_url(false) . '#tab4" >
		<input type="hidden" name="mode" value="phone_call" />
		<input type="hidden" name="id_utilisateur" value="' . intval($user_id) . '" />';
	if (!empty($res)) {
		// warning : phone call not ended;
		$output .= '
			<hr /><h2 id="phone_section" style="color:green">' . sprintf(($res['action'] == 'PHONE_EMITTED'?$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_STARTED_EMITTED"]:$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_STARTED_RECEIVED"]), vb($res['pseudo_membre'])) . ' : '.$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_STARTED_ON"].' ' . get_formatted_date($res['date'], 'short', true) . '</h2>
			<br />
			<center>
				<table class="full_width">
					<tr>
						<th>'.$GLOBALS["STR_COMMENTS"].'</th>
						<td class="center">
							<textarea class="form-control" name="form_phone_comment" rows="5" cols="50" id="phone_comment" >' . (!empty($res['remarque'])?vb($res['remarque']):'') . '</textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="center">&nbsp;</td>
					</tr>
					<tr>
						<td></td>
						<td class="center"><input name="turn_off_phone" type="submit" value="'.$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_CLOSE"].'" class="btn btn-primary" /></td>
					</tr>
				</table>
			</center>';
	} else {
		$output .= '
				<center>
					<table >
						<tr>
							<th>'.$GLOBALS["STR_COMMENTS"].'</th>
						</tr>
						<tr>
							<td class="center">
								<textarea class="form-control" name="form_phone_comment" rows="5" cols="50" id="phone_comment" >' . (!empty($_POST['phone_comment'])?$_POST['phone_comment']:'') . '</textarea>
							</td>
						</tr>
					</table>
					<br />
					<table>
						<tr>
							<td class="center" style="width:50%;">
								<table class="full_width">
									<tr>
										<td class="center"><input name="phone_emitted_submit" type="submit" value="'.$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_INITIATE"].'" class="btn btn-primary" /></td>
									</tr>
								</table>
							</td>
							<td class="center" style="width:50%;">
								<table class="full_width">
									<tr>
										<td class="center"><input name="phone_received_submit" type="submit" value="'.$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_RECEIVED_INITIATE"].'" class="btn btn-primary" /></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</center>
					<br />';
	}
	$output .= '
	</form>';
	return $output;
}

/**
 * Renvoie la note d'un client en fonction de l'intérêt qu'on souhaite lui porter
 *
 * @param integer $user_infos
 * @return Points
 */
function getClientNote(&$user_infos)
{
	// $tabseg = array('seg_buy', 'seg_want', 'seg_think', 'seg_followed', 'seg_who');
	$tabSeg['seg_buy'] = tab_buy_seg();
	$tabSeg['seg_want'] = tab_want_seg();
	$tabSeg['seg_who'] = tab_who_seg();
	$tabSeg['seg_followed'] = tab_followed_seg();
	$tabSeg['seg_think'] = tab_think_seg();
	$points = 0;
	foreach(array('seg_buy', 'seg_want', 'seg_think', 'seg_followed', 'seg_who') as $this_field) {
		if (!isset($user_infos[$this_field]) || !isset($tabSeg[$this_field][$user_infos[$this_field]])) {
			return null;
		} elseif ($tabSeg[$this_field][$user_infos[$this_field]] == 0) {
			// Tout 0 implique que le résultat vaut 0
			return 0;
		} else {
			$points += $tabSeg[$this_field][$user_infos[$this_field]];
		}
	}
	return $points;
}

/**
 *
 * @return
 */
function tab_who()
{
	return array('independant' => $GLOBALS["STR_ADMIN_UTILISATEURS_WHO_INDEPENDANT"],
		'partner' => $GLOBALS["STR_ADMIN_UTILISATEURS_WHO_PARTNER"],
		'company_small' => $GLOBALS["STR_ADMIN_UTILISATEURS_WHO_COMPANY_SMALL"],
		'company_medium' => $GLOBALS["STR_ADMIN_UTILISATEURS_WHO_COMPANY_MEDIUM"],
		'company_big' => $GLOBALS["STR_ADMIN_UTILISATEURS_WHO_COMPANY_BIG"],
		'person' => $GLOBALS["STR_ADMIN_UTILISATEURS_WHO_PERSON"],
		'no_info' => $GLOBALS["STR_ADMIN_UTILISATEURS_WHO_NO_INFO"]);
}

/**
 *
 * @return
 */
function tab_who_seg()
{
	return array('independant' => 2,
		'partner' => 5,
		'company_small' => 4,
		'company_medium' => 5,
		'company_big' => 3,
		'person' => 3,
		'no_info' => 3);
}
/**
 *
 * @return
 */
function tab_buy()
{
	return array('one_old' => $GLOBALS["STR_ADMIN_UTILISATEURS_BUY_ONE_OLD"],
		'one_recent' => $GLOBALS["STR_ADMIN_UTILISATEURS_BUY_ONE_RECENT"],
		'multi_old' => $GLOBALS["STR_ADMIN_UTILISATEURS_BUY_MULTI_OLD"],
		'multi_recent' => $GLOBALS["STR_ADMIN_UTILISATEURS_BUY_MULTI_RECENT"],
		'no_info' => $GLOBALS["STR_ADMIN_UTILISATEURS_BUY_NO_INFO"]);
}

/**
 *
 * @return
 */
function tab_buy_seg()
{
	return array('no' => 16,
		'one_old' => 20,
		'one_recent' => 20,
		'multi_old' => 18,
		'multi_recent' => 18,
		'no_info' => 18);
}

/**
 *
 * @return
 */
function tab_want()
{
	return array('min_contact' => $GLOBALS["STR_ADMIN_UTILISATEURS_WANTS_MIN_CONTACT"],
		'max_contact' => $GLOBALS["STR_ADMIN_UTILISATEURS_WANTS_MAX_CONTACT"],
		'no_matter' => $GLOBALS["STR_ADMIN_UTILISATEURS_WANTS_NO_MATTER"],
		'no_info' => $GLOBALS["STR_ADMIN_UTILISATEURS_WANTS_NO_INFO"]);
}

/**
 *
 * @return
 */
function tab_want_seg()
{
	return array('min_contact' => 0,
		'max_contact' => 25,
		'no_matter' => 10,
		'no_info' => 15);
}

/**
 *
 * @return
 */
function tab_think()
{
	return array('never_budget' => $GLOBALS["STR_ADMIN_UTILISATEURS_THINKS_NEVER_BUDGET"],
		'no_budget' => $GLOBALS["STR_ADMIN_UTILISATEURS_THINKS_NO_BUDGET"],
		'unsatisfied' => $GLOBALS["STR_ADMIN_UTILISATEURS_THINKS_UNSATISFIED"],
		'satisfied' => $GLOBALS["STR_ADMIN_UTILISATEURS_THINKS_SATISFIED"],
		'not_interested' => $GLOBALS["STR_ADMIN_UTILISATEURS_THINKS_NOT_INTERESTED"],
		'interested' => $GLOBALS["STR_ADMIN_UTILISATEURS_THINKS_INTERESTED"],
		'newbie' => $GLOBALS["STR_ADMIN_UTILISATEURS_THINKS_NEWBIE"],
		'no_matter' => $GLOBALS["STR_ADMIN_UTILISATEURS_THINKS_NO_MATTER"],
		'no_info' => $GLOBALS["STR_ADMIN_UTILISATEURS_THINKS_NO_INFO"]
		);
}

/**
 *
 * @return
 */
function tab_think_seg()
{
	return array('never_budget' => 4,
		'no_budget' => 6,
		'unsatisfied' => 6,
		'satisfied' => 6,
		'not_interested' => 0,
		'interested' => 20,
		'newbie' => 8,
		'no_matter' => 0,
		'no_info' => 20);
}

/**
 *
 * @return
 */
function tab_followed()
{
	return array('no' => $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_NO"],
		'poor' => $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_POOR"],
		'correct' => $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_CORRECT"],
		'no_info' => $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_NO_INFO"]
		);
}

/**
 *
 * @return
 */
function tab_followed_seg()
{
	return array('no' => 6,
		'poor' => 4,
		'correct' => 3,
		'no_info' => 4);
}

/**
 *
 * @return
 */
function tab_followed_newsletter()
{
	return array('2' => $GLOBALS["STR_YES_NOT_VALIDATE"],
		'1' => $GLOBALS["STR_YES_VALIDATE"],
		'0' => $GLOBALS["STR_NO"]);
}

/**
 *
 * @return
 */
function tab_ad_new_user()
{
	return array('YES' => 'Compte normal YES',
		'PROSP' => 'Compte PROSPECT',
		'AGENT' => 'Compte AGENT',
		'NO' => 'Compte non valide NO');
}

/**
 *
 * @return
 */
function tab_followed_abonne()
{
	return array('never' => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NEVER"],
		'no' => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NOT_NOW"],
		'earlier' => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NOT_NOW_BUT_EARLIER"],
		'any' => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_ALL"],
		'platinum_until' => $GLOBALS["STR_MODULE_ABONNEMENT_PLATINUM"],
		'diamond_until' => $GLOBALS["STR_MODULE_ABONNEMENT_DIAMOND"]);
}

/**
 *
 * @return
 */
function tab_followed_nombre_produit()
{
	$result = array('-1' => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_PRODUCTS_NEVER_BOUGHT"]);
	for($i=1; $i<=12; $i++) {
		$result[$i] = sprintf($GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_PRODUCTS_AT_LEAST_N"], $i);
	}
	return $result;
}

/**
 *
 * @return
 */
function tab_followed_reason()
{
	return array('' => $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INDIFFERENT"],
		'interesting_profile' => $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTING_PROFILE"],
		'interested_by_product' => $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTED_BY_PRODUCT"],
		'payment_expected' => $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_PAYMENT_EXPECTED"],
		'follow_up' => $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_FOLLOW_UP"]);
}

/**
 * insere_langue()
 *
 * @param array $frm Array with all fields data
 * @param boolean $try_alter_table_even_if_modules_not_active
 * @param mixed $force_update_database_lang_content 
 * @param boolean $update_index 
 * @return
 */
function insere_langue($frm, $try_alter_table_even_if_modules_not_active = true, $force_update_database_lang_content = false, $update_index = true)
{
	$output = '';
	$new_lang = StringMb::strtolower($frm['lang']);
	if (empty($new_lang) || StringMb::strlen($new_lang) != 2) {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS["STR_ADMIN_LANGUES_ERR_LANGUAGE_TWO_CHARS"]))->fetch();
		return $output;
	}
	$sql = "SELECT * 
		FROM peel_langues 
		WHERE lang='" . word_real_escape_string($new_lang) . "'";
	if (num_rows(query($sql))) {
		// La langue existe déjà : on se met automatiquement en mode réparation des tables pour créer d'éventuelles colonnes manquantes
		$repair = true;
		// Par ailleurs on fera une insertion des infos de la langue spécifiquement au site demandé si nécessaire
	} else {
		$repair = false;
	}

	unset($query_alter_table);
	// On prépare ci-dessous la liste des modifications de base de données relatives à une langue donnée
	// A FAIRE EN CAS DE NOUVELLES TABLES : Ajouter les ALTER TABLE à la suite pour ajouter les champs de langues dans les différentes tables souhaitées.
	$query_alter_table[] = 'ALTER TABLE `peel_access_map` ADD `text_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('affiliation')) {
		$query_alter_table[] = 'ALTER TABLE `peel_affiliation` ADD `titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_affiliation` ADD `texte_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	}
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('attributs')) {
		$query_alter_table[] = 'ALTER TABLE `peel_attributs` ADD `descriptif_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_attributs` ADD `description_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	}
	$query_alter_table[] = 'ALTER TABLE `peel_articles` ADD `surtitre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_articles` ADD `titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_articles` ADD `texte_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_articles` ADD `chapo_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_articles` ADD `meta_titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_articles` ADD `meta_key_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_articles` ADD `meta_desc_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	if(!in_array('titre_'.$new_lang, get_table_index('peel_articles', null, true))) {
		$query_alter_table[] = 'ALTER TABLE `peel_articles` ADD INDEX (`titre_' . word_real_escape_string($new_lang) . '`)';
	}
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `meta_titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `meta_key_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `meta_desc_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `header_html_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `image_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `image_header_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `alpha_' . word_real_escape_string($new_lang) . '` CHAR( 1 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `sentence_displayed_on_product_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `nom_court_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	if(!in_array('nom_'.$new_lang, get_table_index('peel_categories', null, true))) {
		$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD INDEX (`nom_' . word_real_escape_string($new_lang) . '`)';
	}
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('annonces')) {
		$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD `alpha_' . word_real_escape_string($new_lang) . '` CHAR( 1 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD `nom_' . word_real_escape_string($new_lang) . '`  VARCHAR( 100 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD `image_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD `meta_titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD `meta_desc_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD `meta_key_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD `header_html_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD `presentation_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD `presentation2_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		if(!in_array('nom_'.$new_lang, get_table_index('peel_categories_annonces', null, true))) {
			$query_alter_table[] = 'ALTER TABLE `peel_categories_annonces` ADD INDEX (`nom_' . word_real_escape_string($new_lang) . '`)';
		}
	}
	$query_alter_table[] = 'ALTER TABLE `peel_cgv` ADD `titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_cgv` ADD `texte_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_contacts` ADD `titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_contacts` ADD `texte_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_couleurs` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_continents` ADD `name_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_ecotaxes` ADD `nom_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_email_template_cat` ADD `name_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('stock_advanced')) {
		$query_alter_table[] = 'ALTER TABLE `peel_etatstock` ADD `nom_' . word_real_escape_string($new_lang) . '`  VARCHAR( 255 ) NOT NULL DEFAULT ""';
	}
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('faq')) {
		$query_alter_table[] = 'ALTER TABLE `peel_faq` ADD `question_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_faq` ADD `answer_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_faq_categories` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_faq_categories` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	}
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('annonces')) {
		$query_alter_table[] = 'ALTER TABLE `peel_gold_ads` ADD `text_intro_' . word_real_escape_string($new_lang) . '` VARCHAR( 80 ) NOT NULL DEFAULT ""';
	}
	$query_alter_table[] = 'ALTER TABLE `peel_import_field` ADD `texte_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_langues` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_legal` ADD `titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_legal` ADD `texte_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('lexique')) {
		$query_alter_table[] = 'ALTER TABLE `peel_lexique` ADD `word_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_lexique` ADD `definition_' . word_real_escape_string($new_lang) . '` mediumtext NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_lexique` ADD `meta_title_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_lexique` ADD `meta_definition_' . word_real_escape_string($new_lang) . '` mediumtext NOT NULL';
	}
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('annonces')) {
		$query_alter_table[] = 'ALTER TABLE `peel_lot_vente` ADD `titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_lot_vente` ADD `description_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
		if (!empty($update_index)) {
			// update_index : On ne fait pas la mise à jour de l'index dans le cas ou les champs de langues pour le module n'existe pas du tout.
			// Parce qu'ici on génère l'index pour tous les champs en une seule fois, alors que lors de l'installation de PEEL avec le module annonces il faut d'abord avoir exécuter insere_langue pour toutes les langues avant de mettre à jour l'index search_fulltext.
			if(in_array('search_fulltext', get_table_index('peel_lot_vente', null, true))) {
				// On regénère l'index FULLTEXT sur le colonnes des langues actives
				// Attention, cette commande prendra du temps si la table est de taille importante
				$query_alter_table[] = 'DROP INDEX `search_fulltext` ON peel_lot_vente';
			}
			unset($index_array);
			foreach ($GLOBALS['admin_lang_codes'] as $lng) {
				$index_array[]='titre_'.$lng;
				$index_array[]='description_'.$lng;
			}
			$query_alter_table[] = 'ALTER TABLE `peel_lot_vente` ADD FULLTEXT KEY `search_fulltext` ('.implode(',', real_escape_string($index_array)).')';
		}
	}
	$query_alter_table[] = 'ALTER TABLE `peel_marques` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_marques` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_marques` ADD `meta_titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_marques` ADD `meta_key_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_marques` ADD `meta_desc_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_meta` ADD `meta_titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_meta` ADD `meta_key_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_meta` ADD `meta_desc_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_modules` ADD `title_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('attributs')) {
		$query_alter_table[] = 'ALTER TABLE `peel_nom_attributs` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	}
	$query_alter_table[] = 'ALTER TABLE `peel_paiement` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('parrainage')) {
		$query_alter_table[] = 'ALTER TABLE `peel_parrain` ADD `titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_parrain` ADD `texte_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	}
	$query_alter_table[] = 'ALTER TABLE `peel_pays` ADD `pays_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	if(!in_array('pays_'.$new_lang, get_table_index('peel_pays', null, true))) {
		$query_alter_table[] = 'ALTER TABLE `peel_pays` ADD INDEX (`pays_' . word_real_escape_string($new_lang) . '`)';
	}
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `descriptif_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `meta_titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `meta_key_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `meta_desc_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab1_html_' . word_real_escape_string($new_lang) . '` mediumtext NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab2_html_' . word_real_escape_string($new_lang) . '` mediumtext NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab3_html_' . word_real_escape_string($new_lang) . '` mediumtext NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab4_html_' . word_real_escape_string($new_lang) . '` mediumtext NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab5_html_' . word_real_escape_string($new_lang) . '` mediumtext NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab6_html_' . word_real_escape_string($new_lang) . '` mediumtext NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab1_title_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab2_title_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab3_title_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab4_title_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab5_title_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `tab6_title_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	if(!in_array('nom_'.$new_lang, get_table_index('peel_produits', null, true))) {
		// Index sur 2 lettres seulement pour éviter de prendre trop de mémoire si bcp de produits
		$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD INDEX (`nom_' . word_real_escape_string($new_lang) . '` (2))';
	}
	$query_alter_table[] = 'ALTER TABLE `peel_profil` ADD `name_' . word_real_escape_string($new_lang) . '` VARCHAR( 100 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_profil` ADD `description_document_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_profil` ADD `document_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_rubriques` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_rubriques` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_rubriques` ADD `meta_titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_rubriques` ADD `meta_key_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	$query_alter_table[] = 'ALTER TABLE `peel_rubriques` ADD `meta_desc_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	if(!in_array('nom_'.$new_lang, get_table_index('peel_rubriques', null, true))) {
		$query_alter_table[] = 'ALTER TABLE `peel_rubriques` ADD INDEX (`nom_' . word_real_escape_string($new_lang) . '`)';
	}
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('vacances')) {
		set_configuration_variable(array('technical_code' => 'module_vacances_client_msg_' . word_real_escape_string($new_lang) . '', 'type' => 'string', 'string' => '', 'site_id' => vn($frm['site_id'])), true);
	}
	$query_alter_table[] = 'ALTER TABLE `peel_statut_paiement` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_statut_livraison` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_tailles` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_types` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_zones` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	// Ajout de la gestion des langues pour le contenu des newsletters qui sont géré en fonction de la langue définie par l'utilisateur
	$query_alter_table[] = 'ALTER TABLE `peel_newsletter` ADD `sujet_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_newsletter` ADD `message_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';

	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('references')) {
		$query_alter_table[] = 'ALTER TABLE `peel_references_categories` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_references_categories` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_references` ADD `descriptif_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	}
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('partenaires')) {
		$query_alter_table[] = 'ALTER TABLE `peel_partenaires_categories` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_partenaires_categories` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	}
	// Ajout des langues au module vitrine
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('vitrine')) {
		$query_alter_table[] = 'ALTER TABLE `peel_vitrine_grossiste` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_vitrine_grossiste` ADD `presentation_' . word_real_escape_string($new_lang) . '` text NOT NULL';
	}
	// Ajout des langues au module vitrine
	if ($try_alter_table_even_if_modules_not_active || check_if_module_active('agenda')) {
		$query_alter_table[] = 'ALTER TABLE `peel_agenda` ADD `title_event_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_agenda` ADD `description_' . word_real_escape_string($new_lang) . '` text NOT NULL';
	}
	// Ajout des langues au module carrousel
	if (check_if_module_active('carrousel')) {
		$query_alter_table[] = 'ALTER TABLE `peel_carrousels` ADD `langue_' . word_real_escape_string($new_lang) . '` tinyint(1) NOT NULL DEFAULT "0"';
		$query_alter_table[] = 'ALTER TABLE `peel_vignettes_carrousels` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_vignettes_carrousels` ADD `descriptif_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_vignettes_carrousels` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_vignettes_carrousels` ADD `image1_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_vignettes_carrousels` ADD `image2_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_vignettes_carrousels` ADD `image3_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_vignettes_carrousels` ADD `lien1_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_vignettes_carrousels` ADD `lien2_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_vignettes_carrousels` ADD `lien3_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	}
	$created = 0;
	foreach ($query_alter_table as $this_alter_table) {
		// Si une colonne existe déjà, on n'affiche pas de message d'erreur si on est en mode réparation, sinon un message d'erreur va s'afficher mais de toutes façons on ne s'arrête pas
		$result = query($this_alter_table, false, null, true);
		if($result) {
			//var_dump($this_alter_table);
			$created++;
		}
	}
	$sql = "SELECT *
		FROM peel_langues
		WHERE lang = '" . word_real_escape_string($new_lang) . "' AND " . get_filter_site_cond('langues', null, false, vb($frm['site_id']), true) . "";
	$query = query($sql);
	if(!fetch_assoc($query)) {
		// La langue n'existe pas pour le site_id donné, on la crée en BDD
		if(empty($frm['flag'])) {
			if(!empty($GLOBALS['langs_flags_correspondance'][$new_lang])){
				$frm['flag'] = $GLOBALS['langs_flags_correspondance'][$new_lang];
			} else {
				$frm['flag'] = '/images/'.$new_lang.'.png';
			}
		}
		if(!isset($frm['etat'])) {
			$frm['etat'] = 1;
		}
		$sql = "INSERT INTO peel_langues (
				lang
				, site_id
				, flag
				, etat
				, url_rewriting
				, position";
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			if(!empty($frm['nom_' . $lng])) {
				$sql .= ", nom_" . $lng;
			}
		}
		$sql .= "
			) VALUES (
				'" . word_real_escape_string($new_lang) . "'
				, '" . nohtml_real_escape_string(get_site_id_sql_set_value(vb($frm['site_id']))) . "'
				, '" . nohtml_real_escape_string(vb($frm['flag'])) . "'
				, '" . intval(vb($frm['etat'])) . "'
				, '" . nohtml_real_escape_string(vb($frm['url_rewriting'])) . "'
				, '" . intval(vb($frm['position'])) . "'";
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			if(!empty($frm['nom_' . $lng])) {
				$sql .= ", '" . nohtml_real_escape_string(vb($frm['nom_' . $lng])) . "'";
			}
		}
		$sql .= "
			)";

		query($sql);
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_LANGUES_MSG_LANGUAGE_CREATED'], (!empty($GLOBALS['lang_names'][$new_lang])?'"' . $GLOBALS['lang_names'][$new_lang] . '"':'"' . $new_lang . '"'))))->fetch();
	}
	if(!$repair || $force_update_database_lang_content) {
		// Import des données relatives à la langue créée
		$database_import_content = array(array('continents' => 'name'), array('pays' => 'pays'), array('ecotaxes' => 'nom'), array('email_template_cat' => 'name'), array('email_template' => 'name'), array('email_template' => 'subject'), array('email_template' => 'text'), array('etatstock' => 'nom'), array('langues' => 'nom'), array('import_field' => 'texte'), array('modules' => 'title'), array('paiement' => 'nom'), array('profil' => 'name'), array('statut_livraison' => 'nom'), array('statut_paiement' => 'nom'), array('types' => 'nom'), array('zones' => 'nom'));
		if(!is_bool($force_update_database_lang_content) && !is_array($force_update_database_lang_content) && StringMb::strlen($force_update_database_lang_content)>1) {
			$force_update_database_lang_content = array($force_update_database_lang_content);
		}
	} else {
		// Par défaut, on rétablit le nom des langues qui seraient vides
		$database_import_content = array(array('langues' => 'nom'));
	}
	if(!empty($database_import_content)) {
		foreach($database_import_content as $this_field_prefix_array) {
			foreach($this_field_prefix_array as $this_table_short_name => $this_field_prefix) {
				if(is_array($force_update_database_lang_content) && !in_array($this_table_short_name, $force_update_database_lang_content)) {
					// On ne veut construire les contenus que pour les tables listées dans $force_update_database_lang_content
					continue;
				}
				$table_field_names = get_table_field_names('peel_' . $this_table_short_name, null, true);
				if(!empty($table_field_names)) {
					// Si la langue n'est pas trouvée, on prend par défaut en priorité anglais, sinon français
					$languages_for_files_tried = array($new_lang, 'en', 'fr');
					foreach($languages_for_files_tried as $tried_file_lang) {
						$database_file_name = $GLOBALS['dirroot'] . '/lib/lang/database_'.$this_table_short_name.'_'.$this_field_prefix.'_'.$tried_file_lang.'.php';
						if(file_exists($database_file_name)){
							break;
						} else {
							$database_file_name = $GLOBALS['dirroot'] . '/lib/lang/database_'.$this_table_short_name.'_'.$tried_file_lang.'.php';
							if(file_exists($database_file_name)){
								break;
							}
						}
					}
					if(file_exists($database_file_name)) {
						include ($database_file_name);
						$array_name = 'peel_'.$this_table_short_name;
						unset($this_array);
						if(isset($$array_name)){
							$this_array = $$array_name;
						}
						if(!empty($this_array) && !empty($this_array[$this_field_prefix])){
							foreach($this_array[$this_field_prefix] as $this_reference => $this_value) {
								unset($sql_set_array);
								unset($sql_line_array);
								if(in_array($this_table_short_name, array('langues'))) {
									$reference_column = 'lang';
								} elseif(in_array($this_table_short_name, array('import_field'))) {
									$reference_column = 'champs';
								} elseif(in_array($this_table_short_name, array('pays'))) {
									$reference_column = 'iso3';
								} elseif(in_array($this_table_short_name, array('profil'))) {
									$reference_column = 'priv';
								} elseif(in_array($this_table_short_name, array('etatstock'))) {
									$reference_column = 'valeur';
								}  elseif(in_array($this_table_short_name, array('ecotaxes'))) {
									$reference_column = 'code';
								} elseif(is_numeric($this_reference)) {
									// Il faut laisser ce contrôle à la fin. is_numeric retourne true lors du test sur le champ 'code' de la table ecotaxes. 
									$reference_column = 'id';
								} else {
									$reference_column = 'technical_code';
								}
								if(in_array('lang', $table_field_names) && !in_array($this_table_short_name, array('langues', 'pays'))){
									// La table a une colonne lang => on a une ligne par langue
									$column_name = $this_field_prefix;
									$sql_line_array[] = "lang='".real_escape_string($new_lang)."'";
								} else {
									// La table a une colonne par langue
									$column_name = $this_field_prefix."_".$new_lang;
								}
								if(in_array($this_table_short_name, array('langues'))) {
									$sql_set_array[] = word_real_escape_string($column_name)."=IF(".word_real_escape_string($column_name)."!='',".word_real_escape_string($column_name).",'".real_escape_string($this_value)."')";
								} else {
									$sql_set_array[] = word_real_escape_string($column_name)."='".real_escape_string($this_value)."'";
								}
								$sql_line_array[$reference_column] = word_real_escape_string($reference_column)."='".real_escape_string($this_reference)."'";
								if(in_array('position', $table_field_names) && $reference_column == 'id' && !in_array($this_table_short_name, array('pays', 'modules'))){
									// La table a une colonne lang => on a une ligne par langue
									$sql_set_array[] = "position=id";
								}
								// On cherche à mettre à jour la ligne pour remplir la nouvelle colonne de langue
								$sql = "SELECT 1
									FROM peel_".word_real_escape_string($this_table_short_name)."
									WHERE ".implode(' AND ', $sql_line_array) . " AND " . get_filter_site_cond($this_table_short_name, null, true) . " AND site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) ."'";
								$query = query($sql);
								if(fetch_assoc($query)) {
									$sql = "UPDATE peel_".word_real_escape_string($this_table_short_name)."
										SET ".implode(', ', $sql_set_array)."
										WHERE ".implode(' AND ', $sql_line_array) . " AND " . get_filter_site_cond($this_table_short_name, null, true) . " AND site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id']))."'";
									query($sql);
								}elseif(!in_array($this_table_short_name, array('langues')) && !empty($this_value)){
									if(in_array('etat', $table_field_names)){
										// La table a une colonne etat => on a active la ligne
										$sql_line_array[] = "etat='1'";
									}
									if($column_name == $this_field_prefix) {
										unset($sql_line_array['id']);
									}
									$sql_set_array[] = "site_id='".nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id']))."'";
									// On crée la ligne si elle est manquante
									$sql = "INSERT INTO peel_".word_real_escape_string($this_table_short_name)."
										SET ".implode(', ', $sql_set_array);
									if(!empty($sql_line_array)){
										$sql .= ", ".implode(', ', $sql_line_array);
									}
									query($sql, false, null, true);
								}
							}
							$imported_texts[] = $this_table_short_name . ' ('.$this_field_prefix.')';
						} else {
							$not_imported_texts[] = $this_table_short_name . ' ('.$this_field_prefix.')';
						}
					} else {
						$not_imported_texts[] = $this_table_short_name . ' ('.$this_field_prefix.')';
					}
				}
			}
		}
		$sql = "SELECT url_rewriting
			FROM peel_langues
			WHERE url_rewriting='' AND lang!='" . real_escape_string($new_lang)."' AND etat=1 AND " . get_filter_site_cond('langues', null, true) . " AND site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
			LIMIT 1";
		$query = query($sql);
		if(fetch_assoc($query)) {
			// Il y a déjà d'autres langues avec url_rewriting='' => on dit par défaut que cette langue est accessible dans le répertoire xx/ si pas d'autre règle existante
			$sql = "UPDATE peel_langues
				SET url_rewriting='".real_escape_string($new_lang)."/'
				WHERE lang='" . real_escape_string($new_lang)."' AND url_rewriting='' AND " . get_filter_site_cond('langues', null, true) . " AND site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id']))."'";
			query($sql);
		}
		if(!empty($imported_texts)){
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => StringMb::strtoupper($new_lang) . ' - ' . $GLOBALS["STR_ADMIN_LANGUES_MSG_CONTENT_IMPORTED"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '.implode(', ', $imported_texts)))->fetch();
		}
		if(!empty($not_imported_texts)){
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => StringMb::strtoupper($new_lang) . ' - ' . $GLOBALS["STR_ADMIN_LANGUES_ERR_CONTENT_NOT_IMPORTED"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . implode(', ', $not_imported_texts)))->fetch();
		}
	} else {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS["STR_ADMIN_LANGUES_ERR_LANGUAGE_ALREADY_INSTALLED"], $new_lang, $created)))->fetch();
	}
	return $output;
}

if (!function_exists('get_admin_date_filter_form')) {
	/**
	 * get_admin_date_filter_form()
	 *
	 * @param mixed $form_title
	 * @param mixed $information_select_html
	 * @param mixed $submit_html
	 * @return
	 */
	function get_admin_date_filter_form($form_title, $information_select_html, $submit_html=null, $only_information_select_html_displayed = false, $standalone_form = true)
	{
		$output = '';
		$nowDay = date('d');
		$nowMonth = date('m');
		$nowYear = date('Y');
		if(isset($_POST['jour1'])) {
			$frm = &$_POST;
		} else {
			$frm = &$_GET;
		}
		// Génération de la liste des années de 2004 à maintenant
		for ($i = 2004; $i <= date('Y'); $i++) {
			$years1[]=$i;
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_date_filter_form.tpl');
		$tpl->assign('only_information_select_html_displayed', $only_information_select_html_displayed);
		$tpl->assign('action', get_current_url(false));
		$tpl->assign('standalone_form', $standalone_form);
		$tpl->assign('form_title', $form_title);
		$tpl->assign('date', get_formatted_date(time()));
		$tpl->assign('information_select_html', $information_select_html);
		if($submit_html !== null) {
			$tpl->assign('submit_html', $submit_html);
		}
		$days_options = array();
		for ($c = 1; $c <= 31; $c++) {
			$days_options[] = array(
				'value' => $c,
				'name' => $c,
				'issel' => ((isset($frm['jour1']) && $c == $frm['jour1']) || (!isset($frm['jour1']) && $c == $nowDay))
			);
		}
		$tpl->assign('days_options', $days_options);
		
		$months_options = array();
		foreach ($GLOBALS['months_names'] as $this_month_number => $this_month) {
			if(!empty($this_month)) {
				$months_options[] = array(
					'value' => $this_month_number,
					'name' => StringMb::ucfirst($this_month),
					'issel' => ((isset($frm['mois1']) && $this_month_number == $frm['mois1']) || (!isset($frm['mois1']) && $this_month_number == date('m', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')))))
				);
			}
		}
		$tpl->assign('months_options', $months_options);
		
		$years_options = array();
		for ($x3 = 0; $x3 <= count($years1) - 1; $x3++) {
			$years_options[] = array(
				'value' => $years1[$x3],
				'name' => $years1[$x3],
				'issel' => ((isset($frm['an1']) && $years1[$x3] == $frm['an1']) || (!isset($frm['an1']) && $years1[$x3] == date('Y', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')))))
			);
		}
		$tpl->assign('years_options', $years_options);
		
		$days2_options = array();
		for ($c = 1; $c <= 31; $c++) {
			$days2_options[] = array(
				'value' => $c,
				'name' => $c,
				'issel' => ((isset($frm['jour2']) && $c == $frm['jour2']) || (!isset($frm['jour2']) && $c == $nowDay))
			);
		}
		$tpl->assign('days2_options', $days2_options);
		
		$months2_options = array();
		foreach ($GLOBALS['months_names'] as $this_month_number => $this_month) {
			if(!empty($this_month)) {
				$months2_options[] = array(
					'value' => $this_month_number,
					'name' => StringMb::ucfirst($this_month),
					'issel' => ((isset($frm['mois2']) && $this_month_number == $frm['mois2']) || (!isset($frm['mois2']) && $this_month_number == $nowMonth))
				);
			}
		}
		$tpl->assign('months2_options', $months2_options);
		
		$years2_options = array();
		for ($x = 0; $x <= count($years1) - 1; $x++) {
			$years2_options[] = array(
				'value' => $years1[$x],
				'name' => $years1[$x],
				'issel' => ((isset($frm['an2']) && $years1[$x] == $frm['an2']) || (!isset($frm['an2']) && $years1[$x] == $nowYear))
			);
		}
		$order_date_field_array = array($GLOBALS['STR_ADMIN_PAIEMENT_DATE']=>'a_timestamp',$GLOBALS['STR_ADMIN_ORDER_CREATION_DATE']=>'o_timestamp',$GLOBALS['STR_ADMIN_COMMANDER_INVOICE_DATE']=>'f_datetime',$GLOBALS['STR_EXPEDITION_DATE']=>'e_datetime');
		foreach($order_date_field_array as $name => $this_field) {
			$order_date_field_options[] = array(
					'value' => $this_field,
					'name' => $name,
					'issel' => ((isset($frm['order_date_field_filter']) && $this_field == $frm['order_date_field_filter']))
				);
		}
		$tpl->assign('order_date_field_options', $order_date_field_options);
		$tpl->assign('years2_options', $years2_options);
		$tpl->assign('from_date_txt', $GLOBALS['strStartingOn']);
		$tpl->assign('until_date_txt', $GLOBALS['strTillDay']);
		$tpl->assign('STR_ADMIN_ORDER_DATE_FIELD_FILTER', $GLOBALS['STR_ADMIN_ORDER_DATE_FIELD_FILTER']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_TODAY_DATE', $GLOBALS['STR_ADMIN_TODAY_DATE']);
		$tpl->assign('STR_ADMIN_DISPLAY_RESULTS', $GLOBALS['STR_ADMIN_DISPLAY_RESULTS']);
		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('check_admin_date_data')) {
	/**
	 * check_admin_date_data()
	 *
	 * @param mixed $form_data
	 * @return
	 */
	function check_admin_date_data(&$form_data)
	{
		$output = '';
		if (!empty($form_data['an1'])) {
			if (!checkdate(intval(str_pad($form_data['mois1'], 2, 0, STR_PAD_LEFT)), intval($form_data['jour1']), intval($form_data['an1']))) {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $form_data['jour1'] . '-' . str_pad($form_data['mois1'], 2, 0, STR_PAD_LEFT) . '-' . $form_data['an1'] . ' => '.$GLOBALS["STR_ERR_DATE_BAD"]))->fetch();
			} elseif (!checkdate(intval(str_pad($form_data['mois2'], 2, 0, STR_PAD_LEFT)), intval($form_data['jour2']), intval($form_data['an2']))) {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $form_data['jour2'] . '-' . str_pad($form_data['mois2'], 2, 0, STR_PAD_LEFT) . '-' . $form_data['an2'] . ' => '.$GLOBALS["STR_ERR_DATE_BAD"]))->fetch();
			} else {
				$dateAdded1 = $form_data['an1'] . '-' . str_pad($form_data['mois1'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($form_data['jour1'], 2, 0, STR_PAD_LEFT) . " 00:00:00";
				$dateAdded2 = $form_data['an2'] . '-' . str_pad($form_data['mois2'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($form_data['jour2'], 2, 0, STR_PAD_LEFT) . " 23:59:59";
				if ($dateAdded2 < $dateAdded1) {
					$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['strStartingOn'] . ' ' . get_formatted_date($dateAdded1) . '&nbsp;' . $GLOBALS['strTillDay'] . '  ' . get_formatted_date($dateAdded2) . ' => ' . $GLOBALS["STR_ADMIN_DATE1_DATE2_INCOHERENT"]))->fetch();
				}
			}
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_EMPTY_FIELD']))->fetch();
		}
		if(!empty($output)) {
			return $output;
		} else {
			return false;
		}
	}
}

if (!function_exists('affiche_liste_produits')) {
	/**
	 * affiche_liste_produits()
	 *
	 * @param array $frm Array with all fields data ($_GET or $_POST)
	 * @return
	 */
	function affiche_liste_produits($frm)
	{		
		$categorie_options = get_categories_output(null, 'categories', vb($frm['cat_search']), 'option', '&nbsp;&nbsp;', null, null, true);
		$supplier_options = get_supplier_output();
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_produits.tpl');
		if (empty($categorie_options)) {
			$tpl->assign('is_empty', true);
			$tpl->assign('href', $GLOBALS['administrer_url'] . '/categories.php?mode=ajout');
		} else {
			$tpl->assign('is_empty', false);
			$tpl->assign('site_parameters_prices', vb($GLOBALS['site_parameters']['edit_prices_on_products_list']));
			$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
			$tpl->assign('action', get_current_url(false) . '?page=' . (!empty($frm['page']) ? $frm['page'] : 1) . '&mode=recherche');
			$tpl->assign('categorie_options', $categorie_options);
			$tpl->assign('supplier_options', $supplier_options);
			$tpl->assign('cat_search_zero_issel', (vb($frm['cat_search']) == '0'));
			$tpl->assign('home_search_one_issel', (vb($frm['home_search']) == 1));
			$tpl->assign('home_search_zero_issel', (vb($frm['home_search']) === "0"));
			$tpl->assign('new_search_one_issel', (vb($frm['new_search']) == 1));
			$tpl->assign('new_search_zero_issel', (vb($frm['new_search']) === "0"));
			$tpl->assign('promo_search_one_issel', (vb($frm['promo_search']) == 1));
			$tpl->assign('promo_search_zero_issel', (vb($frm['promo_search']) === "0"));
			
			$tpl->assign('is_best_seller_module_active', check_if_module_active('best_seller'));
			$tpl->assign('top_search_one_issel', (vb($frm['top_search']) == 1));
			$tpl->assign('top_search_zero_issel', (vb($frm['top_search']) === "0"));
			
			$tpl->assign('is_gifts_module_active', check_if_module_active('gifts'));
			$tpl->assign('on_gift_one_issel', (vb($frm['on_gift']) == 1));
			$tpl->assign('on_gift_zero_issel', (vb($frm['on_gift']) === "0"));

			$tpl->assign('etat_one_issel', (vn($frm['etat']) == 1));
			$tpl->assign('etat_zero_issel', (vn($frm['etat']) === "0"));
			
			$tpl->assign('blank_src', get_url('/images/blank.gif'));
			$tpl->assign('STR_PHOTO_NOT_AVAILABLE_ALT', $GLOBALS['STR_PHOTO_NOT_AVAILABLE_ALT']);
			$tpl->assign('STR_ADMIN_UPDATE_WARNING', $GLOBALS['STR_ADMIN_UPDATE_WARNING']);
			$tpl->assign('STR_ADMIN_APPLY_CHANGES', $GLOBALS['STR_ADMIN_APPLY_CHANGES']);
			if(!empty($GLOBALS['site_parameters']['default_picture'])) {
				$tpl->assign('photo_not_available_src', thumbs($GLOBALS['site_parameters']['default_picture'], 80, 50, 'fit', null, null, true, true));
			}
			$sql = get_admin_products_search_sql($frm);
			$Links = new Multipage($sql, 'affiche_liste_produits');
			$HeaderTitlesArray = array($GLOBALS['STR_ADMIN_ACTION'], 'reference' => $GLOBALS['STR_REFERENCE'], $GLOBALS['STR_CATEGORY'], $GLOBALS['STR_WEBSITE'], ('nom_' . $_SESSION['session_langue']) => $GLOBALS['STR_ADMIN_NAME'], 'prix' => $GLOBALS['STR_PRICE'] . ' ' . $GLOBALS['site_parameters']['symbole'] . ' ' . (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']), 'etat' => $GLOBALS['STR_STATUS']);
			if (check_if_module_active('stock_advanced')) {
				$HeaderTitlesArray['on_stock'] = $GLOBALS['STR_STOCK'];
			}
			if (check_if_module_active('gifts')) {
				$HeaderTitlesArray['points'] = $GLOBALS['STR_GIFT_POINTS'];
				$tpl->assign('STR_MODULE_GIFTS_ADMIN_GIFT', $GLOBALS['STR_MODULE_GIFTS_ADMIN_GIFT']);
			}
			$HeaderTitlesArray['date_maj'] = $GLOBALS['STR_ADMIN_UPDATED_DATE'];
			$HeaderTitlesArray[] = $GLOBALS['STR_ADMIN_SUPPLIER'];
			$HeaderTitlesArray[] = $GLOBALS['STR_PHOTO'];
			$HeaderTitlesArray['nb_view'] = $GLOBALS['STR_ADMIN_PRODUITS_VIEWS_COUNT'];
			$Links->HeaderTitlesArray = $HeaderTitlesArray;
			$Links->OrderDefault = vb($GLOBALS['site_parameters']['liste_produits_order_default'],"position, nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']).", prix");
			$Links->SortDefault = vb($GLOBALS['site_parameters']['liste_produits_sort_default'], "ASC");
			$results_array = $Links->Query();
			
			$tpl->assign('nombre_produits', $Links->nbRecord);
			$tpl->assign('ajout_produits_href', $GLOBALS['administrer_url'] . '/produits.php?mode=ajout');
			$tpl->assign('is_duplicate_module_active', check_if_module_active('duplicate'));
			$tpl->assign('is_stock_advanced_module_active', check_if_module_active('stock_advanced'));
			$tpl->assign('is_gifts_module_active', check_if_module_active('gifts'));
			
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_ON_OUR_SELECTION', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_ON_OUR_SELECTION']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_OFF_OUR_SELECTION', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_OFF_OUR_SELECTION']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_ON_NEW', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_ON_NEW']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_OFF_NEW', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_OFF_NEW']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_ON_PROMO', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_ON_PROMO']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_OFF_PROMO', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_OFF_PROMO']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_ON_BEST_SELLERS', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_ON_BEST_SELLERS']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_OFF_BEST_SELLERS', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_OFF_BEST_SELLERS']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_ON_RECOMMENDED_PRODUCTS', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_ON_RECOMMENDED_PRODUCTS']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_ON_TOP', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_ON_TOP']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_ON_ETAT', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_ON_ETAT']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_OFF_ETAT', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_OFF_ETAT']);
			$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE_SUPPR_PRODUCT', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE_SUPPR_PRODUCT']);

			$lignes = array();
			if (!empty($results_array)) {
				$i = 0;
				$tpl->assign('HeaderRow', $Links->getHeaderRow());
				foreach ($results_array as $ligne) {
					$product_object = new Product($ligne['id'], $ligne, true, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
					$drop_href = get_current_url(true, false, array('nombre','multipage','mode','id','page'));
					if (strpos($drop_href, '?') === false) {
						$drop_href .= '?';
					} else {
						$drop_href .= '&';	
					}
					$drop_href .= 'mode=suppr&id=' . $ligne['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1);
					
					// On affiche un formulaire avec les prix de chaque ligne qu'on peut changer - dans ce formulaire, on ne formatte pas les prix pour garder des prix avec plusieurs décimales additionnelles (utilisé dans des cas complexes)
					$tmpLigne = array('tr_rollover' => tr_rollover($i, true),
						'drop_confirm' =>  $GLOBALS["STR_ADMIN_DELETE_WARNING"],
						'id' => $ligne['id'],
						'name' => (!empty($product_object->name)?$product_object->name:'['.$ligne['id'].']'),
						'drop_href' => $drop_href,
						'drop_src' => $GLOBALS['administrer_url'] . '/images/b_drop.png',
						'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'] . '&page=' . (!empty($frm['page']) ? $frm['page'] : 1),
						'edit_src' => $GLOBALS['administrer_url'] . '/images/b_edit.png',
						'dup_href' => get_current_url(false) . '?mode=duplicate&id=' . $ligne['id'] . '&page=' . (!empty($frm['page']) ? $frm['page'] : 1),
						'dup_src' => $GLOBALS['administrer_url'] . '/images/duplicate.png',
						'reference' => $ligne['reference'],
						'cats' => array(),
						'site_name' => get_site_name($ligne['site_id']),
						'modify_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'] . '&page=' . (!empty($frm['page']) ? $frm['page'] : 1),
						'modify_label' => $product_object->name . (vn($ligne['on_gift']) == 1 ? "&nbsp;(cadeau)" : ""),
						'prix' => fprix((display_prices_with_taxes_in_admin() ? $ligne['prix'] : $ligne['prix'] / (1 + $ligne['tva'] / 100)), false, $GLOBALS['site_parameters']['code'], false, null, false, false),
						'prix_suf' => (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']),
						'etat_onclick' => 'change_status("produits", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
						'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
						'date' => get_formatted_date($ligne['date_maj']),
						'product_name' => $product_object->name,
						'nb_view' => vn($ligne['nb_view']),
					);
					if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
						$tmpLigne['site_country'] = get_country_name($ligne['site_country']);
					}
					$sqlCAT = "SELECT c.id, c.nom_" . $_SESSION['session_langue'] . ", c2.nom_" . $_SESSION['session_langue'] . " AS parent_nom_" . $_SESSION['session_langue'] . "
						FROM peel_produits_categories pc
						INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
						LEFT JOIN peel_categories c2 ON c2.id=c.parent_id AND " . get_filter_site_cond('categories', 'c2') . "
						WHERE pc.produit_id = " . intval($ligne['id']);
					$resCAT = query($sqlCAT);
					if (num_rows($resCAT) > 0) {
						while ($cat = fetch_assoc($resCAT)) {
							$tmpLigne['cats'][] = array(
								'parent_nom' => $cat['parent_nom_' . $_SESSION['session_langue']],
								'nom' => $cat['nom_' . $_SESSION['session_langue']]
							);
						}
					}
					$tmpLigne['site_id'] = $ligne['site_id'];
					if (check_if_module_active('stock_advanced')) {
						if (vn($ligne['on_stock']) == 1) {
							$tmpLigne['stock_href'] = get_current_url(false) . '?mode=stock&id=' . $ligne['id'];
							$tmpLigne['stock_src'] = $GLOBALS['administrer_url'] . '/images/stock.gif';
						}
					}
					if (check_if_module_active('gifts')) {
						$tmpLigne['points'] = $ligne['points'];
					}
					if (!empty($ligne['id_utilisateur']) && ($user_infos = get_user_information($ligne['id_utilisateur']))) {
						$tmpLigne['utilisateur_href'] = $GLOBALS['administrer_url'] . "/utilisateurs.php?mode=modif&id_utilisateur=" . $user_infos['id_utilisateur'];
						$tmpLigne['societe'] = $user_infos['societe'];
					}
					//Récupération de l'image principale du produit
					$main_product_picture = $product_object->get_product_main_picture();
					//Si l'image principale est trouvée
					if (!empty($main_product_picture)) {
						$tmpLigne['product_src'] = thumbs($main_product_picture, 80, 50, 'fit', null, null, true, true);
					} 
					$i++;
					$lignes[] = $tmpLigne;
				}
			}
			$tpl->assign('lignes', $lignes);
			$tpl->assign('Multipage', $Links->GetMultipage());
			
			$tpl_marques_options = array();
			$select = query("SELECT id, nom_" . $_SESSION['session_langue'] . ", etat
			   FROM peel_marques
			   WHERE " . get_filter_site_cond('marques') . "
			   ORDER BY position, nom_" . $_SESSION['session_langue'] . " ASC");
			while ($nom = fetch_assoc($select)) {
				$tpl_marques_options[] = array('value' => intval($nom['id']),
					'issel' => $nom['id'] == vb($frm['brand_search']),
					'name' => $nom['nom_' . $_SESSION['session_langue']] . (empty($nom['etat'])?' ('.$GLOBALS["STR_ADMIN_DEACTIVATED"].')':'')
					);
			}
			$tpl->assign('marques_options', $tpl_marques_options);
		}
		$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
		if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
		}
		$tpl->assign('STR_ADMIN_SUPPLIER', $GLOBALS['STR_ADMIN_SUPPLIER']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_PRODUITS_CREATE_CATEGORY_FIRST', $GLOBALS['STR_ADMIN_PRODUITS_CREATE_CATEGORY_FIRST']);
		$tpl->assign('STR_ADMIN_SEARCH_CRITERIA', $GLOBALS['STR_ADMIN_SEARCH_CRITERIA']);
		$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
		$tpl->assign('STR_ADMIN_ALL_CATEGORIES', $GLOBALS['STR_ADMIN_ALL_CATEGORIES']);
		$tpl->assign('STR_ADMIN_PRODUITS_NO_CATEGORY_RELATED', $GLOBALS['STR_ADMIN_PRODUITS_NO_CATEGORY_RELATED']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_PRODUCT_IN', $GLOBALS['STR_ADMIN_PRODUITS_IS_PRODUCT_IN']);
		$tpl->assign('STR_ADMIN_OUR_SELECTION', $GLOBALS['STR_ADMIN_OUR_SELECTION']);
		$tpl->assign('STR_ADMIN_ANY', $GLOBALS['STR_ADMIN_ANY']);
		$tpl->assign('STR_NOUVEAUTES', $GLOBALS['STR_NOUVEAUTES']);
		$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
		$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
		$tpl->assign('STR_PROMOTION', $GLOBALS['STR_PROMOTION']);
		$tpl->assign('STR_TOP', $GLOBALS['STR_TOP']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_PRODUCT', $GLOBALS['STR_ADMIN_PRODUITS_IS_PRODUCT']);
		$tpl->assign('STR_REFERENCE', $GLOBALS['STR_REFERENCE']);
		$tpl->assign('STR_ADMIN_PRODUCT_NAME', $GLOBALS['STR_ADMIN_PRODUCT_NAME']);
		$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
		$tpl->assign('STR_ADMIN_PRODUITS_PRODUCTS_LIST', $GLOBALS['STR_ADMIN_PRODUITS_PRODUCTS_LIST']);
		$tpl->assign('STR_ADMIN_PRODUITS_PRODUCTS_COUNT', $GLOBALS['STR_ADMIN_PRODUITS_PRODUCTS_COUNT']);
		$tpl->assign('STR_ADMIN_CATEGORIES_ADD_PRODUCT', $GLOBALS['STR_ADMIN_CATEGORIES_ADD_PRODUCT']);
		$tpl->assign('STR_NOTA_BENE', $GLOBALS['STR_NOTA_BENE']);
		$tpl->assign('STR_ADMIN_PRODUITS_DUPLICATE_WARNING', $GLOBALS['STR_ADMIN_PRODUITS_DUPLICATE_WARNING']);
		$tpl->assign('STR_ADMIN_PRODUITS_NOTHING_FOUND', $GLOBALS['STR_ADMIN_PRODUITS_NOTHING_FOUND']);
		$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
		$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
		$tpl->assign('STR_ADMIN_PRODUITS_DUPLICATE', $GLOBALS['STR_ADMIN_PRODUITS_DUPLICATE']);
		$tpl->assign('STR_ADMIN_PRODUITS_UPDATE', $GLOBALS['STR_ADMIN_PRODUITS_UPDATE']);
		$tpl->assign('STR_PHOTO_NOT_AVAILABLE_ALT', $GLOBALS['STR_PHOTO_NOT_AVAILABLE_ALT']);
		$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_STOCKS', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_STOCKS']);
		$tpl->assign('STR_ADMIN_DELETE_ALL_RESULTS', sprintf($GLOBALS['STR_ADMIN_DELETE_ALL_RESULTS'], vn($Links->nbRecord)));
		$delete_all_href = get_current_url(true, false, array('mode'));
		$tpl->assign('delete_all_href', $delete_all_href.(strpos($delete_all_href, '?')!==false?'&':'?') . 'mode=delete_results');
		$modification_multiple_href = get_current_url(true, false, array('mode'));
		$tpl->assign('modification_multiple_href', $modification_multiple_href.(strpos($modification_multiple_href, '?')!==false?'&':'?') . 'mode=modification_multiple');
		$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
		$tpl->assign('STR_BRAND', $GLOBALS['STR_BRAND']);
		$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
		$tpl->assign('STR_ADMIN_CHECK_ALL', $GLOBALS['STR_ADMIN_CHECK_ALL']);
		$tpl->assign('STR_ADMIN_UNCHECK_ALL', $GLOBALS['STR_ADMIN_UNCHECK_ALL']);
		$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
		return $tpl->fetch();
	}
}

/**
 * get_admin_products_search_sql()
 *
 * @param array $frm Array with all fields data
 * @param boolean $delete
 * @param boolean $get_only_product_with_images
 * @return
 */
function get_admin_products_search_sql($frm, $delete = false, $get_only_product_with_images = false) {
	// Construction de la clause WHERE
	$table = "peel_produits p";

	$where = get_filter_site_cond('produits', 'p', true);
	if((!empty($frm['mode']) && $frm['mode'] != "maj") || empty($frm['mode'])) {
		// En cas de mise à jour de produit, $frm contient des index qui correspondent aux champs mis à jour. Il ne faut pas prendre en compte ces champs pour faire une recherche, ce n'est pas la demande de l'utilisateur.
		if($get_only_product_with_images) {
			$where .= " AND (image1!='' OR image2!='' OR image3!='' OR image4!='' OR image5!='' OR image6!='' OR image7!='' OR image8!='' OR image9!='' OR image10!='')";
		}
		if (isset($frm['reference_search']) && !empty($frm['reference_search'])) {
			$where .= (empty($GLOBALS['site_parameters']['admin_products_search_reference_like'])?  " AND p.reference = '" . nohtml_real_escape_string($frm['reference_search']) . "'" : " AND p.reference LIKE '%" . nohtml_real_escape_string($frm['reference_search']) . "%'");
		}
		if (isset($frm['name_search']) && !empty($frm['name_search'])) {
			if(!empty($GLOBALS['site_parameters']['quick_search_results_main_search_field'])) {
				$name_field_array = $GLOBALS['site_parameters']['quick_search_results_main_search_field'];
			} else {
				$name_field_array = "nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])."";
			}
			if (!is_array($name_field_array)) {
				$name_field_array = array($name_field_array);
			}
			$where_array = array();
			foreach($name_field_array as $name_field) {
				$where_array[] = $name_field . " LIKE '%" . nohtml_real_escape_string($frm['name_search']) . "%'";
			}
			$where .= " AND (" .implode(' OR ', $where_array).")";
		}
		if (!empty($frm['brand_search'])) {
			$where .= " AND p.id_marque = '" . nohtml_real_escape_string($frm['brand_search']) . "'";
		}
		if (isset($frm['home_search']) && $frm['home_search'] != "null") {
			$where .= " AND p.on_special = '" . nohtml_real_escape_string($frm['home_search']) . "'";
		}
		if (isset($frm['new_search']) && $frm['new_search'] != "null") {
			$where .= " AND p.on_new = '" . nohtml_real_escape_string($frm['new_search']) . "'";
		}
		if (isset($frm['promo_search']) && $frm['promo_search'] != "null") {
			$where .= " AND p.on_promo = '" . nohtml_real_escape_string($frm['promo_search']) . "'";
		}
		if (isset($frm['top_search']) && $frm['top_search'] != "null" && check_if_module_active('best_seller')) {
			$where .= " AND p.on_top = '" . nohtml_real_escape_string($frm['top_search']) . "'";
		}
		if (isset($frm['on_gift']) && $frm['on_gift'] != "null" && check_if_module_active('gifts')) {
			$where .= " AND p.on_gift = '" . nohtml_real_escape_string($frm['on_gift']) . "'";
		}
		if (isset($frm['etat']) && $frm['etat'] != "null") {
			$where .= " AND p.etat = '" . nohtml_real_escape_string($frm['etat']) . "'";
		}
		if (isset($frm['cat_search']) && $frm['cat_search'] === '0') {
			// recherche des produits sans association
			$where .= " AND (pc.categorie_id IS NULL OR pc.categorie_id=0)";
		} elseif (isset($frm['cat_search']) && is_numeric($frm['cat_search'])) {
			$children_cat_list = get_children_cat_list(vn($frm['cat_search']));
			$where .= " AND pc.categorie_id IN (" . implode(',', $children_cat_list) . ")";
		}
		if (isset($frm['product_site_id']) && is_numeric($frm['product_site_id'])) {
			$where .= " AND p.site_id = '" . nohtml_real_escape_string($frm['product_site_id']) . "'";
		}
		if ((isset($frm['cat_search']) && is_numeric($frm['cat_search'])) || !empty($delete)) {
			$where .= "";
			$table .= "
				LEFT JOIN peel_produits_categories AS pc ON p.id = pc.produit_id";
		}
		if(!empty($delete)) {
			$alias = "p,pc,pr,pco,pt";
			// Pour effacer le contenu des autres tables, on ne fait pas de boucle sur chaque id sinon ce serait trop long
			$where .= "";
			$table .= "
				LEFT JOIN peel_produits_references AS pr ON p.id = pr.produit_id";
			$where .= "";
			$table .= "
				LEFT JOIN peel_produits_couleurs AS pco ON p.id = pco.produit_id";
			$where .= "";
			$table .= "
				LEFT JOIN peel_produits_tailles AS pt ON p.id = pt.produit_id";
			if (check_if_module_active('stock_advanced')) {
				$alias .= ",ps";
				$where .= "";
				$table .= "
				LEFT JOIN peel_stocks AS ps ON p.id = ps.produit_id";
			}
		}
	}
	// ne pas mettre de word_real_escape_string ou nohtml_real_escape_string sur $table puisque cette variable contient plusieurs mots, et des sauts de lignes
	$sql = ($delete ?"DELETE " . $alias :"SELECT p.*") . "
		FROM " . $table . "
		WHERE " . $where;
	$sql .= ($delete ?"":" GROUP BY p.id");
	return $sql;
}

if (!function_exists('affiche_liste_produits_acommander')) {
	/**
	 * affiche_liste_produits_acommander()
	 *
	 * @return
	 */
	function affiche_liste_produits_acommander()
	{
		$preselectionne = null;

		$sql = "SELECT p.id, oi.nom_produit as nom, oi.couleur, oi.taille, oi.delai_stock, oi.commande_id, oi.order_stock
			FROM peel_commandes_articles oi
			INNER JOIN peel_produits p ON oi.produit_id = p.id AND " . get_filter_site_cond('produits', 'p') . "
			WHERE oi.order_stock>0 AND  " . get_filter_site_cond('commandes_articles', 'oi', true);
		$Links = new Multipage($sql, 'affiche_liste_produits_acommander');
		$Links->OrderDefault = "position, nom_" . $_SESSION['session_langue'] . ", prix";
		$Links->SortDefault = "ASC";
		$results_array = $Links->Query();

		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_produits_acommander.tpl');
		if (empty($results_array)) {
			$tpl->assign('is_empty', true);
		} else {
			$tpl->assign('is_empty', false);
			$products = array();
			foreach ($results_array as $this_product) {
				$products[] = array(
					'stock_href' => $GLOBALS['administrer_url'] . '/produits.php?mode=stock&id=' . $this_product['id'],
					'stock_src' => $GLOBALS['administrer_url'] . '/images/stock.gif',
					'modif_href' => $GLOBALS['administrer_url'] . '/produits.php?mode=modif&id=' . $this_product['id'],
					'nom' => $this_product['nom'],
					'couleur' => vb($this_product['couleur']),
					'taille' => vb($this_product['taille']),
					'delai_stock' => get_formatted_duration((intval($this_product['delai_stock']) * 24 * 3600), false, 'month'),
					'order_stock' => $this_product['order_stock'],
					'commander_href' => 'commander.php?mode=modif&id=' . $this_product['commande_id'],
					'commande_id' => $this_product['commande_id'],
				);
			}
			$tpl->assign('products', $products);
			$tpl->assign('Multipage', $Links->GetMultipage());
		}
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_PRODUITS_NO_PRODUCT_TO_ORDER', $GLOBALS['STR_ADMIN_PRODUITS_NO_PRODUCT_TO_ORDER']);
		$tpl->assign('STR_ADMIN_PRODUITS_LIST_TO_ORDER_TITLE', $GLOBALS['STR_ADMIN_PRODUITS_LIST_TO_ORDER_TITLE']);
		$tpl->assign('STR_ADMIN_PRODUITS_TO_ORDER', $GLOBALS['STR_ADMIN_PRODUITS_TO_ORDER']);
		$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
		$tpl->assign('STR_PRODUCT', $GLOBALS['STR_PRODUCT']);
		$tpl->assign('STR_ADMIN_PRODUITS_TO_ORDER', $GLOBALS['STR_ADMIN_PRODUITS_TO_ORDER']);
		$tpl->assign('STR_ADMIN_PRODUITS_ORDER_DETAIL', $GLOBALS['STR_ADMIN_PRODUITS_ORDER_DETAIL']);
		$tpl->assign('STR_COLOR', $GLOBALS['STR_COLOR']);
		$tpl->assign('STR_SIZE', $GLOBALS['STR_SIZE']);
		$tpl->assign('STR_ADMIN_PRODUITS_SUPPLY_FORECASTED', $GLOBALS['STR_ADMIN_PRODUITS_SUPPLY_FORECASTED']);
		$tpl->assign('STR_ORDER_NAME', $GLOBALS['STR_ORDER_NAME']);
		return $tpl->fetch();
	}
}

if (!function_exists('affiche_liste_articles')) {
	/**
	 * affiche_liste_articles()
	 *
	 * @param array $frm Array with all fields data
	 * @return
	 */
	function affiche_liste_articles($frm)
	{
		if (isset($_POST['cat_search'])) {
			$rubrique_id = $_POST['cat_search'];
		} else {
			$rubrique_id = 'null';
		}
		$frm['rubriques'] = array($rubrique_id);

		// Construction de la clause WHERE
		$where = "WHERE " . get_filter_site_cond('articles', 'a', true) . "";
		$inner = "";
		if (isset($frm['etat'])) {
			if ($frm['etat'] != "null") {
				$where .= " AND a.etat = '" . intval($frm['etat']) . "'";
			}
		}
		if (!empty($frm['text_in_title'])) {
			$where .= " AND a.titre_" . $_SESSION['session_langue'] . " LIKE '%" . nohtml_real_escape_string($frm['text_in_title']) . "%'";
		}
		if (!empty($frm['text_in_article'])) {
			$where .= " AND (a.texte_" . $_SESSION['session_langue'] . " LIKE '%" . real_escape_string($frm['text_in_article']) . "%' OR a.chapo_" . $_SESSION['session_langue'] . " LIKE '%" . real_escape_string($frm['text_in_article']) . "%')";
		}
		if (isset($frm['homepage'])) {
			if ($frm['homepage'] != "null") {
				$where .= " AND a.on_special = '" . nohtml_real_escape_string($frm['homepage']) . "'";
			}
		}
		if (isset($frm['cat_search']) || isset($_GET['catid'])) {
			if (vn($frm['cat_search']) != "null") {
				$inner .= "
				LEFT JOIN peel_articles_rubriques ar ON ar.article_id = a.id
				LEFT JOIN peel_rubriques r ON ar.rubrique_id = r.id AND " . get_filter_site_cond('rubriques', 'r') . "";
				if ($frm['cat_search'] === '0') {
					// recherche des articles sans association
					$rubrique_condition = ' ar.rubrique_id IS NULL OR ar.rubrique_id=0';
				} else {
					$rubrique_condition = ' ar.rubrique_id IN (' . implode(',', get_children_cat_list(vn($frm['cat_search']), array(), 'rubriques')) . ')';
				}
				$where .= ' AND '.$rubrique_condition;
			}
		}
		$sql = "SELECT a.id, a.titre_" . $_SESSION['session_langue'] . ", a.etat, a.site_id";
		if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			$sql .= ", a.site_country";
		}
		$sql .= " FROM peel_articles a
			" . $inner . "
			" . $where . "";
		
		$tpl = $GLOBALS['tplEngine']->createTemplate('liste_articles.tpl');

		$Links = new Multipage($sql, 'affiche_liste_articles');
		$HeaderTitlesArray = array($GLOBALS["STR_ADMIN_ACTION"], $GLOBALS["STR_ADMIN_RUBRIQUE"], 'titre_' . $_SESSION['session_langue'] => $GLOBALS["STR_ADMIN_TITLE"], 'site_id' => $GLOBALS["STR_ADMIN_WEBSITE"], 'etat' => $GLOBALS["STR_STATUS"]);
		if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
			$HeaderTitlesArray['site_country'] = $GLOBALS["STR_ADMIN_SITE_COUNTRY"];
		}
		$Links->HeaderTitlesArray = $HeaderTitlesArray;
		$Links->OrderDefault = 'a.id';
		$Links->SortDefault = "DESC";
		$results_array = $Links->Query();

		$tpl->assign('action', get_current_url(false) . '?start=0&mode=recherche');
		$tpl->assign('rubrique_options', get_categories_output(null, 'rubriques', vb($frm['rubriques'])));
		$tpl->assign('text_in_title', vb($_POST['text_in_title']));
		$tpl->assign('text_in_article', vb($_POST['text_in_article']));
		$tpl->assign('cat_search', vb($_GET['cat_search']));
		$tpl->assign('ajout_href', get_current_url(false) . '?mode=ajout');
		$tpl->assign('links_header_row', $Links->getHeaderRow());
		$tpl->assign('links_multipage', $Links->GetMultipage());
		if (empty($results_array)) {
			$tpl->assign('is_empty', true);
			$tpl->assign('langue', $_SESSION['session_langue']);
		} else {
			$tpl->assign('is_empty', false);
			$lignes = array();
			$i = 0;
			foreach ($results_array as $ligne) {
				$tmpLigne = array(
					'tr_rollover' => tr_rollover($i, true),
					'titre' => (!empty($ligne['titre_' . $_SESSION['session_langue']])?StringMb::html_entity_decode_if_needed($ligne['titre_' . $_SESSION['session_langue']]):'[' . $ligne['id'] . ']'),
					'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1),
					'drop_src' => $GLOBALS['administrer_url'] . '/images/b_drop.png',
					'rubs' => array(),
					'modif_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
					'site_name' => get_site_name($ligne['site_id']),
					'etat_onclick' => 'change_status("articles", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
					'modif_etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				);
				if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
					$tmpLigne['site_country']= get_country_name($ligne['site_country']);
				}
				$sql = "SELECT r.id, r.nom_" . $_SESSION['session_langue'] . ", r2.nom_" . $_SESSION['session_langue'] . " AS parent_nom_" . $_SESSION['session_langue'] . "
					FROM peel_articles_rubriques pr
					LEFT JOIN peel_rubriques r ON r.id = pr.rubrique_id AND " . get_filter_site_cond('rubriques', 'r') . "
					LEFT JOIN peel_rubriques r2 ON r2.id=r.parent_id AND " . get_filter_site_cond('rubriques', 'r2') . "
					WHERE pr.article_id = " . intval($ligne['id']);
				$query = query($sql);
				if (num_rows($query) > 0) {
					while ($this_rub = fetch_assoc($query)) {
						if (!empty($this_rub['id'])) {
							$tmpLigne['rubs'][] = array(
								'parent_nom' => $this_rub['parent_nom_' . $_SESSION['session_langue']],
								'nom' => $this_rub['nom_' . $_SESSION['session_langue']]
							);
						} else {
							$tmpLigne['rubs'][] = null;
						}
					}
				} 
				$tmpLigne['site_id'] = $ligne['site_id'];
				$i++;
				$lignes[] = $tmpLigne;
			}
			$tpl->assign('lignes', $lignes);
		}
		$tpl->assign('STR_ADMIN_CHOOSE_SEARCH_CRITERIA', $GLOBALS['STR_ADMIN_CHOOSE_SEARCH_CRITERIA']);
		$tpl->assign('STR_ADMIN_SEARCH_CRITERIA', $GLOBALS['STR_ADMIN_SEARCH_CRITERIA']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_RUBRIQUE', $GLOBALS['STR_ADMIN_RUBRIQUE']);
		$tpl->assign('STR_ADMIN_RUBRIQUES_ALL', $GLOBALS['STR_ADMIN_RUBRIQUES_ALL']);
		$tpl->assign('STR_ADMIN_RUBRIQUES_NONE_RELATED', $GLOBALS['STR_ADMIN_RUBRIQUES_NONE_RELATED']);
		$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
		$tpl->assign('STR_ADMIN_ARTICLES_ALL', $GLOBALS['STR_ADMIN_ARTICLES_ALL']);
		$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
		$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
		$tpl->assign('STR_ADMIN_SEARCH_IN_TITLE', $GLOBALS['STR_ADMIN_SEARCH_IN_TITLE']);
		$tpl->assign('STR_ADMIN_SEARCH_IN_ARTICLE', $GLOBALS['STR_ADMIN_SEARCH_IN_ARTICLE']);
		$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
		$tpl->assign('STR_ADMIN_ARTICLES_ARTICLES_LIST', $GLOBALS['STR_ADMIN_ARTICLES_ARTICLES_LIST']);
		$tpl->assign('STR_ADMIN_ARTICLES_FORM_ADD', $GLOBALS['STR_ADMIN_ARTICLES_FORM_ADD']);
		$tpl->assign('STR_ADMIN_ARTICLES_NOTHING_FOUND_FOR_LANG', $GLOBALS['STR_ADMIN_ARTICLES_NOTHING_FOUND_FOR_LANG']);
		$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
		$tpl->assign('STR_ADMIN_RUBRIQUE', $GLOBALS['STR_ADMIN_RUBRIQUE']);
		$tpl->assign('STR_ADMIN_TITLE', $GLOBALS['STR_ADMIN_TITLE']);
		$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
		$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
		$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
		$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
		$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
		$tpl->assign('STR_ADMIN_ARTICLES_FORM_MODIFY', $GLOBALS['STR_ADMIN_ARTICLES_FORM_MODIFY']);
		echo $tpl->fetch();
	}
}

/**
 * Fonction affichant la liste d'emails sur le compte utilisateur
 *
 * @return
 */
function get_email_template_options($option_id_nature = 'id', $category_id = null, $lang = null, $value_select = null, $get_signature = null)
{
	$output = '
	<option value="">'.$GLOBALS['STR_CHOOSE'].'...</option>';
	// Récupération des template email en fonction de la catégorie
	$result = query('SELECT id, technical_code, name, lang, site_id
		FROM peel_email_template
		WHERE active = "TRUE" AND ' . get_filter_site_cond('email_template', null) . ' ' . (!empty($get_signature)?' AND technical_code LIKE "signature%"':'') . (!empty($category_id)?' AND id_cat="' . intval($category_id) . '"':'') . (!empty($lang)?' AND (lang="' . vb($lang) . '" OR lang="")':'') . '
		ORDER BY technical_code, lang, name');
	while ($row_template = fetch_assoc($result)) {
		if ($option_id_nature == 'id') {
			$this_value = vn($row_template['id']);
			if (!empty($value_select)) {
				$this_select = frmvalide(vn($value_select) == $this_value, 'selected="selected"');
			}
		} elseif ($option_id_nature == 'technical_code') {
			$this_value = vb($row_template['technical_code']);
			if (!empty($value_select)) {
				$this_select = frmvalide(vb($value_select) == $this_value, 'selected="selected"');
			}
		}
		$output .= '
	<option value="' . $this_value . '" ' . (!empty($this_select)?$this_select:'') . '>' . get_site_info($row_template) . '[' . StringMb::strtoupper(vb($row_template['lang'])) . '] - ' . StringMb::str_form_value(vb($row_template['name'])) . '</option>';
	}
	return $output;
}

/**
 * Créer les options pour le select qui liste les noms de sites configurés en back office.
 *
 * @param integer $selected_site_id
 * @param boolean $selected_site_name
 * @param string $display_first_option
 * @return
 */
function get_site_id_select_options($selected_site_id = null, $selected_site_name = null, $display_first_option = null, $select_current_site_id_by_default = false, $allow_empty_selected = false) {
	$tpl = $GLOBALS['tplEngine']->createTemplate('select_options.tpl');
	$tpl_options = array();
	if(!empty($GLOBALS['site_parameters']['multisite_disable'])) {
		// Désactivation du multisite : par défaut on prend site_id=1
		$tpl_options[] = array(
			'value' => 1,
			'name' => $GLOBALS['site'],
			'issel' => true
		);
	} else {
		$all_sites_name_array = get_all_sites_name_array(true);
		if(is_array($selected_site_id) && count($selected_site_id) == 1) {
			$selected_site_id = current($selected_site_id);
		}
		if(empty($display_first_option)) {
			// Si on ne donne pas la première option en paramètre, alors on regarde si l'utilisateur est multisite. Si oui, on affiche l'option "Tous les sites". 
			// => Il faut afficher cette option même si il y a qu'un seul site installé, si l'administrateur multisite modifie sa fiche dans l'administration, il faut que "Tous les sites" soit sélectionné.
			$display_first_option = (empty($_SESSION['session_utilisateur']['site_id'])?'STR_ADMIN_ALL_SITES':false);
		}
		if ($allow_empty_selected) {
			if (!empty($display_first_option)) {
				$tpl_options[] = array(
					'value' => '',
					'name' => $GLOBALS['STR_WEBSITE_NONE'],
					'issel' => (($selected_site_id === '' || $selected_site_id === null)?$GLOBALS['site_id']:'')
				);
			}
		} elseif ($selected_site_id === '' || $selected_site_id === null) {
			// le site_id passé en paramètre est vide. Pour présélectioner la bonne option du select il faut utiliser soit le site sélectionné par l'admin, soit le site_id correspondant au site consulté.
			if($select_current_site_id_by_default && empty($_SESSION['session_admin_multisite'])) {
				// On ne souhaite pas avoir zéro sélectionné dans le select => On prend l'id du site par défaut (défini par le nom de domaine du site)
				$selected_site_id = $GLOBALS['site_id'];
			} elseif (isset($_SESSION['session_admin_multisite'])) {
				// On prend l'id de site de l'admin, il peut être égal à 0
				$selected_site_id = $_SESSION['session_admin_multisite'];
			}
		}
		if(!is_array($selected_site_name)) {
			$selected_site_name = explode(',', $selected_site_name);
		}
		if(!is_array($selected_site_id)) {
			$selected_site_id = explode(',', $selected_site_id);
		}
		if (!empty($display_first_option) && (StringMb::substr($display_first_option, 0, 4) == 'STR_') && !empty($GLOBALS[$display_first_option])) {
			// Si l'admin peut administrer tous les sites, il faut mettre une option supplémentaire pour pouvoir accéder au contenu de tous les sites.
			if ($display_first_option == 'STR_ADMIN_ALL_SITES') {
				$value = 0;
			} else {
				$value = '';
			}
			// la première option est ajoutée au tableau $all_sites_name_array qui contient les sites configurés.
			$all_sites_name_array = array($value=>$GLOBALS[$display_first_option]) + $all_sites_name_array;
		}
		foreach($all_sites_name_array as $site_id=>$site_name) {
			// Récupération des infos qui seront utilisées par les options
			$site_selected = (($selected_site_name != array(null) && in_array($site_name, $selected_site_name)) || ($selected_site_id != array(null) && in_array($site_id, $selected_site_id)));
			$tpl_options[] = array(
				'value' => $site_id,
				'name' => $site_name,
				'issel' => $site_selected
			);
		}
	}
	// La variable contient le tableau des données, un foreach dans select_options exploitera ces infos dans le fichier SMARTY
	$tpl->assign('options', $tpl_options);

	// Les options sont générées
	$output = $tpl->fetch();

	// Envoi des résultats
	return $output;
}

/**
 * Fonction permettant de récupérer les noms des catégories d'annonces, sous forme de liste séparée par des virgules. Cette liste sera exploitée ensuite par get_specific_field_infos pour générer les noms des options dans un champ select, via le tag [FUNCTION=get_tag_function_site_options_values_list] qui est remplacé par template_tags_replace.
 *
 * @return
 */
function get_tag_function_site_options_values_list($params = array()) {
	$result_array = get_all_sites_name_array();
	if(vb($params['mode'], 'id') == 'id') {
		return implode(',', array_keys($result_array));
	} else {
		foreach ($result_array as $this_key => $this_value) {
			$result_array[$this_key] = str_replace(',', ' ', $this_value);
		}
		return implode(',', $result_array);
	}
}

/**
 *
 *
 * @return
 */
function get_tag_function_site_options_titles_list() {
	return get_tag_function_site_options_values_list(array('mode' => 'name'));
}

/**
 * Retourne un tableau des urls des sites configurés en fonction des droits de l'administrateur. La valeur de wwwroot sera utilisé pour définir la variable GLOBAL['wwwroot'].
 * wwwroot est l'URL de base de votre site, sans mettre de / à la fin. Par exemple : wwwroot = "http://www.example.com";  ou wwwroot = "http://www.example.com/monrepertoiredesite";
 *
 * @return
 */
function get_sites_wwwroot_array() {
	$site_id_array = array();
	$q = query('SELECT site_id, string
		FROM peel_configuration
		WHERE technical_code="wwwroot" AND ' . get_filter_site_cond('configuration', null, true) . '
		ORDER BY string ASC');
	while($result = fetch_assoc($q)) {
		$site_id_array[$result['site_id']] = $result['string'];
	}
	return $site_id_array;
}

/**
 * Créer les options pour le select qui liste les noms de pays de visiteurs configurés
 *
 * @param integer $selected_site_country
 * @param string $field_name
 * @return
 */
function get_site_country_checkboxes($selected_site_country_array = null, $field_name = 'site_country') {
	if(empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		return null;
	}
	// Création du template SMARTY
	$tpl = $GLOBALS['tplEngine']->createTemplate('checkboxes.tpl');
	$tpl_options = array();
	if ($selected_site_country_array === null) {
		$selected_site_country_array = $GLOBALS['site_parameters']['site_country_allowed_array'];
	}
	if (!is_array($selected_site_country_array) && !empty($selected_site_country_array)) {
		$selected_site_country_array = explode(',', $selected_site_country_array);
	}
	foreach($GLOBALS['site_parameters']['site_country_allowed_array'] as $site_country_id) {
		// Récupération des infos qui seront utilisée par les options
		$tpl_options[] = array(
			'name' => $field_name,
			'value' => $site_country_id,
			'text' => get_country_name($site_country_id),
			'issel' => in_array($site_country_id, $selected_site_country_array)
		);
	}
	// La variable contient le tableau des données, un foreach dans select_options exploitera ces infos dans le fichier SMARTY
	$tpl->assign('options', $tpl_options);
	// Les options sont générées
	$output = $tpl->fetch();

	// Envoi des résultats
	return $output;
}

/**
 * Retourne les contenus remplis si vide
 *
 * @param array $frm
 * @return
 */
function fill_other_language_content($frm, $mode=null){
		
	if (!empty($GLOBALS['site_parameters']['admin_data_copy_if_empty_by_language_array']) && !empty($mode)) {
		if ($mode == "articles") {
			$this_form_fields = array('titre_', 'chapo_', 'texte_');
		} elseif ($mode == "categories") {
			$this_form_fields = array('nom_', 'description_');
		} elseif ($mode == "rubriques") {
			$this_form_fields = array('description_', 'nom_');
		}
		if (!empty($this_form_fields)) {
			// Le paramètre pour remplir le contenu d'une langue à partir d'une autre 
			// admin_data_copy_if_empty_by_language_array => array "cible"=>"source"
			foreach ($GLOBALS['admin_lang_codes'] as $lng) {
				// On boucle sur les langues
				foreach ($this_form_fields as $this_field) {
					// On boucle sur les champs.
					$temp = trim(strip_tags($frm[$this_field.$lng]));
					if (empty($temp) && !empty($GLOBALS['site_parameters']['admin_data_copy_if_empty_by_language_array'][$lng])) {
						// Le champ n'a pas été rempli par l'utilisateur.
						// Et on souhaite remplir cette langue par une autre.
						// => on prend la valeur de la langue source.
						$frm[$this_field.$lng] = $frm[$this_field.$GLOBALS['site_parameters']['admin_data_copy_if_empty_by_language_array'][$lng]];
					}
				}
			}
		}
	}
	
	if(!empty($GLOBALS['site_parameters']['field_auto_complete_with_main_content_lang']) && is_array($GLOBALS['site_parameters']['field_auto_complete_with_main_content_lang'])) {
		foreach ($GLOBALS['site_parameters']['field_auto_complete_with_main_content_lang'] as $this_field) {
			// Recherche du contenu principal
			$temp = trim(strip_tags(vb($frm[$this_field . '_' . $GLOBALS['site_parameters']['main_content_lang']])));
			if(!empty($temp)) {
				// La langue principale n'est pas vide
				$main_content = $GLOBALS['site_parameters']['main_content_lang'];
               // return $GLOBALS['site_parameters']['main_content_lang'];
			}
			if (empty($main_content)) {
				// Recherche de contenu par langue, si la langue principale est vide
				foreach ($GLOBALS['admin_lang_codes'] as $lng) {
					$temp = trim(strip_tags(vb($frm[$this_field . '_' . $lng])));
					if(!empty($temp)) {
						// un contenu dans une langue a été trouvé.
						$main_content = $lng;
						// on sort de la boucle
						break;
					}
				}
			}
			if (!empty($main_content)) {
                
				// On a un contenu principal, il faut compléter les champs vides avec
				foreach ($GLOBALS['admin_lang_codes'] as $lng) {
					$temp = trim(strip_tags(vb($frm[$this_field . '_' . $lng])));
					if (empty($temp)) {
						$frm[$this_field . '_' . $lng] = vb($frm[$this_field . '_' . $main_content]);
					}
				}
			}
		}
	}
	return $frm;
}

/**
 * get_delivery_status_options()
 *
 * @param integer $selected_status_id Id of the status preselected
 * @return
 */
function get_delivery_status_options($selected_status_id = null)
{
	$output = '';
	$sql_statut = "SELECT id, nom_" . $_SESSION['session_langue'] . ", site_id
		FROM peel_statut_livraison
		WHERE " . get_filter_site_cond('statut_livraison', null) . "
		ORDER BY position ASC, nom_" . $_SESSION['session_langue'] . " ASC";
	$res_statut = query($sql_statut);

	while ($s = fetch_assoc($res_statut)) {
		$output .= '<option value="' . intval($s['id']) . '" ' . frmvalide($s['id'] == $selected_status_id, ' selected="selected"') . '>' . StringMb::html_entity_decode_if_needed(get_site_info($s).$s['nom_' . $_SESSION['session_langue']]) . '</option>';
	}
	return $output;
}

/**
 * get_payment_status_options()
 *
 * @param integer $selected_status_id Id of the status preselected
 * @return
 */
function get_payment_status_options($selected_status_id = null)
{
	$output = '';
	$sql_statut = "SELECT id, nom_" . $_SESSION['session_langue'] . ", site_id
		FROM peel_statut_paiement
		WHERE " . get_filter_site_cond('statut_paiement', null) . "
		ORDER BY position ASC, nom_" . $_SESSION['session_langue'] . " ASC";
	$res_statut = query($sql_statut);

	$tpl = $GLOBALS['tplEngine']->createTemplate('payment_status_options.tpl');
	$tpl_options = array();
	while ($s = fetch_assoc($res_statut)) {
		$tpl_options[] = array(
			'value' => intval($s['id']),
			'name' => get_site_info($s).$s['nom_' . $_SESSION['session_langue']],
			'issel' => ($s['id'] == $selected_status_id)
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * get_vat_select_options()
 *
 * @param mixed $selected_vat
 * @param boolean $approximative_amount_selected
 * @param boolean $option_vat_choice_null
 * @return
 */
function get_vat_select_options($selected_vat = null, $approximative_amount_selected = false, $option_vat_choice_null = false, $excluded_vat_array = array())
{
	$output = '';
	
	$tpl = $GLOBALS['tplEngine']->createTemplate('select_options.tpl');
	$tpl_options = array();
	if (empty($selected_vat)) {
		$selected_vat = get_default_vat();
	}
	$hook_result = call_module_hook('vat_select_options', array('selected_vat' => $selected_vat, 'approximative_amount_selected' => $approximative_amount_selected), 'array');

	if (empty($hook_result['hook_done'])) {
		$sql_paiement = 'SELECT id, tva, site_id
			FROM peel_tva
			WHERE ' . get_filter_site_cond('tva').  '
			ORDER BY tva DESC';
		$res_paiement = query($sql_paiement);
		while ($vat = fetch_assoc($res_paiement)) {
			$vats_array[] = $vat;
		}
	} else {
		$vats_array = $hook_result['vats_array'];
	}
	if (empty($vats_array)) {
		return null;
	}
	foreach ($vats_array as $this_vat_array) {
		if (!empty($this_vat_array['value'])) {
			// le hook peut transmettre une valeur et un nom différent
			$value = $this_vat_array['value'];
		} else {
			$value = $this_vat_array['tva'];
		}
		if (!empty($excluded_vat_array) && in_array($value, $excluded_vat_array)) {
			// on ne veut pas de ce taux de TVA
			continue;
		}
		if ($approximative_amount_selected) {
			// Pour éviter problèmes d'arrondis sur la TVA calculée à partir de la BDD, on regarde si elle vaut la valeur dans le select à 0,1% près
			$is_selected = (abs(floatval($selected_vat) - floatval($value)) * 1000 <= abs($value));
		} elseif(is_numeric($value)) {
			$is_selected = (floatval($selected_vat) == floatval($value));
		} else {
			$is_selected = ($selected_vat == $value);
		}
		
		if($is_selected) {
			$selected_vat_found = true;
		}
		if(!$option_vat_choice_null) {
			$tpl_options[] = array(
				'value' => $value,
				'name' => $this_vat_array['tva'] . ' ' . get_site_info($this_vat_array),
				'issel' => $is_selected
			);
		} else {
			if($is_selected && $this_vat_array['tva'] == 0) {
				$tpl_options[] = array(
					'value' => $value,
					'name' => '----',
					'issel' => $is_selected
				);
			} else {
				$tpl_options[] = array(
					'value' => $value,
					'name' => $this_vat_array['tva'] . ' ' . get_site_info($this_vat_array),
					'issel' => $is_selected
				);
			}
		}
	}
	if(!empty($selected_vat) && (StringMb::strpos($selected_vat, '[') === false || StringMb::strpos($selected_vat, ']') === false) && empty($selected_vat_found)) {
		// Valeur cherchée non trouvée (par exemple valeur en base de données qui n'est plus disponible dans les choix de TVA autorisés) : on la rajoute à la liste du select
		// On s'assure que ce ne soit pas un tag qui soit passé en paramètre. ça arrive lors de l'ajout d'une ligne vide dans le formulaire de création de facture.
		$tpl_options[] = array(
			'value' => $selected_vat,
			'name' => $selected_vat . ' [' . $GLOBALS["STR_ADMIN_DEACTIVATED"] . ']',
			'issel' => true
		);
	}
	$tpl->assign('options', $tpl_options);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * get_children_cat_list()
 *
 * @param int $catid
 * @param array $preselectionne
 * @return
 */
function get_children_cat_list($catid, $preselectionne = array(), $destination = 'categories')
{
	$preselectionne = array();
	if (!in_array($catid, $preselectionne)) {
		$preselectionne[] = $catid;
	}
	$site_cond = "";
	if ($destination == 'categories') {
		$table = 'peel_categories';
		$site_cond .= " AND " . get_filter_site_cond($destination, 't') . "";
	} elseif($destination == 'rubriques') {
		$table = 'peel_rubriques';
		$site_cond .= " AND " . get_filter_site_cond($destination, 't') . "";
	} else {
		return false;
	}
	$sql = 'SELECT t.id, t.nom_' . $_SESSION['session_langue'] . ', t.parent_id
		FROM '.word_real_escape_string($table).' t
		WHERE t.parent_id = "' . intval($catid) . '" ' . $site_cond . '
		ORDER BY t.position';
	$qid = query($sql);
	while ($cat = fetch_assoc($qid)) {
		if (is_array($preselectionne) && !in_array($cat['id'], $preselectionne)) {
			$preselectionne[] = $cat['id'];
		}
	}
	return $preselectionne;
}

/**
 * Créer ou mets à jour les paramètres du site $frm['site_id']
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @param boolean $update_module update table peel_modules if needed.
 * @param boolean $mode 'modif' or 'insere' mode
 * @return
 */
function create_or_update_site($frm, $update_module = true, $mode, $available_langs_array = null)
{
	if (isset($frm['site_id'])) {
		// site_id est renseigné, les paramètres seront associés à cet id. site_id peut avoir zéro pour valeur
		$site_id = $frm['site_id'];
	} else {
		// Configuration générale
		$site_id = 0;
	}
	$output = '';
	if ($mode == 'insere') {
		// Création du contenu et de la configuration spécifique au nouveau site.
		$output .= execute_sql($GLOBALS['dirroot'] . "/lib/sql/create_new_site.sql", null, true, $site_id);
	}
	if(!empty($frm['wwwroot']) && StringMb::substr($frm['wwwroot'], -1) === '/') {
		$frm['wwwroot'] = StringMb::substr($frm['wwwroot'], 0, StringMb::strlen($frm['wwwroot']) - 1);
	}
	if (intval(vn($frm['keep_old_orders_intact']))>1 && empty($frm['keep_old_orders_intact_date'])) {
		// Par défaut : date du jour
		$frm['keep_old_orders_intact_date'] = get_formatted_date(time());
		$frm['keep_old_orders_intact'] = (intval(vn($frm['keep_old_orders_intact']))>1? strtotime(get_mysql_date_from_user_input($frm['keep_old_orders_intact_date'])) : intval(vn($frm['keep_old_orders_intact'])));
	}
	if(isset($frm['template_directory']) && !file_exists($GLOBALS['dirroot'] . "/modeles/" . vb($frm['template_directory']))) {
		unset($frm['template_directory']);
	}
	// Traitement des checkbox pour mettre valeur dans $frm si pas coché	
	foreach(array('enable_prototype', 'enable_jquery') as $this_key) {
		$frm[$this_key] = vn($frm[$this_key]);
	}
	ob_start();
	// Met à jour la table de configuration
	foreach($frm as $this_key => $this_value) {
		if(!in_array($this_key, array('token', 'site_id'))) {
			// A prioiri aucune raison d'exclure keep_old_orders_intact_date
			foreach(array('module_', 'display_mode_', 'etat_', 'position_', 'home_', 'install') as $this_begin) {
				if(StringMb::substr($this_key, 0, StringMb::strlen($this_begin)) == $this_begin && is_numeric(StringMb::substr($this_key, StringMb::strlen($this_begin)))) {
					// On ne traite pas ici les données qui concernent le contenu de peel_modules
					$skip = true;
				}
			}
			if(empty($skip)) {
				// Insertion (ou mise à jour) dans la BDD
				$configuration_variable_array = array('technical_code' => $this_key, 'string' => $this_value, 'site_id' => $site_id, 'origin' => 'sites.php');
				if(StringMb::substr($this_key, 0, StringMb::strlen('module_')) == 'module_') {
					$configuration_variable_array['type'] = 'integer';
				}
				$allow_html = true;
				// Github 16/12/2018 : XSS report on nom_en
				// ANSWER : PEEL is a multisite ecommerce. It is designed to allow one administrator to handle multiple websites. Fore example a general presentation website, and various eshops related. It is not designed to give administration rights to people with whom your are not confident. An administrator can configure environment variables, and setup multiples things that can execute javascript.
				// In this regard, this XSS is not a problem in itself. However, it is trus that it is not clean to allow HTML inside "Site Name EN" and we will change real_escape_string in the code into nohtml_real_escape_string in the database save of this information.
				// => Hereunder, protection on some variables to have something cleaner.
				if((StringMb::substr($this_key, 0, StringMb::strlen('nom_')) == 'nom_' && StringMb::strlen($this_key) == 6) || in_array($this_key, array('template_directory', 'template_multipage', 'favicon', 'pays_exoneration_tva'))) {
					$allow_html = false;
				}
				set_configuration_variable($configuration_variable_array, true, true, $allow_html);
			}
			unset($skip);
		}
	}

	if (!empty($update_module)) {
		// MAJ des modules, dont l'interface de configuration est sur la même page que les paramètres du site.
		$modules = get_modules_array(false, null, null, true, $site_id, true);
		foreach(array_keys($modules) as $key) {
			$sql = "UPDATE peel_modules
				SET location='" . nohtml_real_escape_string(vb($frm['module_' . $key])) . "'
					, display_mode='" . nohtml_real_escape_string($frm['display_mode_' . $key]) . "'
					, position='" . intval($frm['position_' . $key]) . "'
					, etat='" . (empty($frm['etat_' . $key]) ? 0 : 1) . "'
					, in_home='" . (empty($frm['home_' . $key]) ? 0 : 1) . "'
					, site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($site_id)) . "'
				WHERE id='" . intval($key) . "' AND " . get_filter_site_cond('modules');
			query($sql);
		}
	}
	$output .= ob_get_contents();
	ob_end_clean();

	if ($mode == 'insere') {
		// Définition du pays par défaut pour le nouveau site.
		// Le pays par défaut est la france (anciennement id=1). Le pays france est le premier a être inséré en BDD (via le fichier create_new_site.sql) il possède donc le plus petit id de la liste des pays pour un site.
		$query = query("SELECT id
			FROM peel_pays
			WHERE " . get_filter_site_cond('pays', null, false, $site_id) . " AND site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($site_id)) . "'
			ORDER BY id ASC
			LIMIT 1");
		$result = fetch_assoc($query);
		set_configuration_variable(array('technical_code' => 'default_country_id', 'string' => $result['id'], 'site_id' => $site_id, 'origin' => 'sites.php'), true);

		// Définition de devise_defaut pour le nouveau site. Lors d'une mise à jour la devise par défaut est défini dans le formulaire.
		$query = query("SELECT id
			FROM peel_devises
			WHERE id=".intval(vn($frm['devise_defaut']))." AND site_id='".nohtml_real_escape_string(get_site_id_sql_set_value($site_id))."' AND " . get_filter_site_cond('devises', null, false, $site_id));
		$result = fetch_assoc($query);
		if(empty($result['id'])) {
			// La devise par défaut configurée n'est pas trouvé dans la table des devises pour ce site => il faut définir une devise
			$query = query('SELECT id
				FROM peel_devises
				WHERE code="EUR" AND ' . get_filter_site_cond('devises', null, false, $site_id) . " AND site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($site_id))."'");
			if($result = fetch_assoc($query)) {
				// Définition de la devise EUR pour le site nouvellement créé. 
				set_configuration_variable(array('technical_code' => 'devise_defaut', 'string' => $result['id'], 'site_id' => $site_id, 'origin' => 'sites.php'), true);
			}
		}

		if(!empty($available_langs_array)) {
			// Il faut mettre à jour les enregistrements créés précédement avec le contenu multilingue pour le site concerné.
			if(in_array($_SESSION['session_langue'], $available_langs_array)) {
				// Si la langue utilisée lors de l'installation est installée pour le front-office, on la crée en premier => url_rewriting sera donc vide
				unset($temp);
				$temp[] = $_SESSION['session_langue'];
				foreach($available_langs_array as $this_lang) {
					if($this_lang != $_SESSION['session_langue']) {
						$temp[] = $this_lang;
					}
				}
				$available_langs_array = $temp;
			}
			if(!empty($error_msg)) {
				$output .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
			}
			foreach($available_langs_array as $this_lang){
				// Insertion des données en base de données pour chaque langue installée
				ob_start();
				// ne pas mettre $frm, qui contient toutes les données du formulaire de création de site notamment nom_fr qui rentre en conflit avec le nom de la langue elle même
				$output .= insere_langue(array('lang' => $this_lang, 'site_id' => $site_id), true, true);
				// Ajout d'un logo par défaut en front-office pour chaque langue
				$error_msg = ob_get_contents();
				ob_end_clean();
				if(!empty($error_msg)) {
					$output .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
				}
			}
			if(count($available_langs_array)>1) {
				foreach($available_langs_array  as $this_lang) {
					// Gestion des noms de langue si plusieurs langues ont été installées
					ob_start();
					$output .= insere_langue(array('lang' => $this_lang, 'site_id' => $site_id), true, false);
					$error_msg = ob_get_contents();
					ob_end_clean();
					if(!empty($error_msg)) {
						$output .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
					}
				}
			}
		}
		// Traitement spécifique pour le remplissage des zones lors de la création du site 
		// Création du tableau de correspondance entre les zones et les pays pour faire le lien entre les données qui ont été rentrée lors de l'exécution de create_new_site.sql et les pays qu'il faut maintenant modifier
		$zone_to_coutry = array('france_mainland' => array('FRA'), 'europe' => array('DEU', 'AUT', 'BEL', 'BGR', 'DNK', 'ESP', 'FIN', 'GRC', 'HUN', 'IRL', 'ITA', 'NLD', 'POL', 'PRT', 'CZE', 'ROU', 'GBR', 'SWE', 'CYP', 'EST', 'LVA', 'LTU', 'LUX', 'MLT', 'SVK', 'SVN'), 'france_and_overseas' => array('GUF', 'PYF', 'ATF', 'GLP', 'MTQ', 'MYT', 'NCL', 'REU', 'SPM', 'WLF'), 'world' => array('AFG', 'ZAF', 'ALB', 'DZA', 'SAU', 'ARG', 'AUS', 'BRA', 'CAN', 'CHL', 'CHN', 'COL', 'KOR', 'CRI', 'HRV', 'EGY', 'ARE', 'ECU', 'USA', 'SLV', 'HKG', 'IND', 'IDN', 'ISR', 'JPN', 'JOR', 'LBN', 'MYS', 'MAR', 'MEX', 'NOK', 'NZL', 'PER', 'PAK', 'PHL', 'PRI', 'RUS', 'SGP', 'CHE', 'TWN', 'THA', 'TUR', 'UKR', 'VEN', 'SRB', 'WSM', 'AND', 'AGO', 'AIA', 'ATA', 'ATG', 'ARM', 'ABW', 'AZE', 'BHS', 'BHR', 'BGD', 'BLR', 'BLZ', 'BEN', 'BMU', 'BTN', 'BOL', 'BIH', 'BWA', 'BVT', 'IOT', 'VGB', 'BRN', 'BFA', 'BDI', 'KHM', 'CMR', 'CPV', 'CYM', 'CAF', 'TCD', 'CXR', 'CCK', 'COM', 'COG', 'COK', 'CUB', 'DJI', 'DMA', 'DOM', 'TLS', 'GNQ', 'ERI', 'ETH', 'FLK', 'FRO', 'FJI', 'GAB', 'GMB', 'GEO', 'GHA', 'GIB', 'GRL', 'GRD', 'GUM', 'GTM', 'GIN', 'GNB', 'HTI', 'HMD', 'HND', 'ISL', 'IRN', 'IRQ', 'CIV', 'JAM', 'KAZ', 'KEN', 'KIR', 'KOR', 'KWT', 'KGZ', 'LAO', 'LSO', 'LBR', 'LBY', 'LIE', 'MAC', 'MKD', 'MDG', 'MWI', 'MDV', 'MLI', 'MHL', 'MRT', 'MUS', 'FSM', 'MDA', 'MCO', 'MNG', 'MSR', 'MOZ', 'MMR', 'NAM', 'NRU', 'NPL', 'NIC', 'NER', 'NGA', 'NIU', 'NFK', 'MNP', 'OMN', 'PLW', 'PAN', 'PNG', 'PRY', 'PCN', 'QAT', 'RWA', 'SGS', 'KNA', 'LCA', 'VCT', 'WSM', 'SMR', 'STP', 'SEN', 'SYC', 'SLE', 'SOM', 'LKA', 'SHN', 'SDN', 'SUR', 'SJM', 'SWZ', 'SYR', 'TJK', 'TZA', 'TGO', 'TKL', 'TON', 'TTO', 'TUN', 'TKM', 'TCA', 'TUV', 'UMI', 'UGA', 'URY', 'UZB', 'VUT', 'VAT', 'VNM', 'VIR', 'ESH', 'YEM', 'COD', 'ZMB', 'ZWE', 'BRB', 'MNE'));
		$query = query('SELECT *
			FROM peel_zones
			WHERE technical_code!="" AND ' . get_filter_site_cond('zones', null, false, $site_id));
		$zones_availables = array();
		while($result = fetch_assoc($query)) {
			$zones_availables[$result['id']] = $zone_to_coutry[$result['technical_code']];
			if ($result['technical_code'] == 'france_mainland') {
				// On a besoin de connaître l'id de la zone pour la france pour gérer les tarifs
				$france_zone_id = $result['id'];
			}
		}
		foreach ($zones_availables as $this_zone_id => $this_zone_country_array_by_technical_code) {
			query('UPDATE peel_pays
				SET zone = ' . nohtml_real_escape_string(get_zone_id_sql_set_value(intval($this_zone_id))) . '
				WHERE iso3 IN ("'.implode('","',nohtml_real_escape_string($this_zone_country_array_by_technical_code)).'") AND ' . get_filter_site_cond('pays', null, false, $site_id));
			
			if ($france_zone_id == $this_zone_id) {
				// Il faut completer les tarifs créés pour le nouveau site avec la zone. Les types de transport rempli par défaut ne concerne que la zone france et les modes de livraison colissimo et retrait en boutique
				// tarif > 0 correspond à colissimo. Si tarif = 0 c'est le type pickup.
				$query = query('SELECT * 
					FROM peel_types
					WHERE (technical_code="colissimo_without_signature" OR technical_code="pickup") AND ' . get_filter_site_cond('types', null, false, $site_id));
				while ($result = fetch_assoc($query)) {
					query('UPDATE peel_tarifs 
						SET `zone`=' . intval($france_zone_id) . ', `type`="' . nohtml_real_escape_string($result['id']).'"
						WHERE ' . ($result['technical_code'] == "pickup"?'tarif = 0.00':'tarif > 0') . ' AND ' . get_filter_site_cond('tarifs', null, false, $site_id));
				}
			}
		}
		// Les templates d'emails par défaut ont été insérés avec l'id de catégorie par défaut (1), il faut mettre à jour cette id de catégorie. Les emails installés par défaut sont tous dans la catégories automatic_sending.
		$query = query('SELECT id 
			FROM peel_email_template_cat
			WHERE technical_code="automatic_sending" AND ' . get_filter_site_cond('email_template_cat', null, false, $site_id));
		while ($result = fetch_assoc($query)) {
			query('UPDATE peel_email_template 
				SET `id_cat`=' . intval($result['id']) . '
				WHERE ' . get_filter_site_cond('email_template', null, false, $site_id));
		}
	}
	return $output;
}

/**
 * get_site_info()
 *
 * @param array $array
 * @return
 */
function get_site_info($array) {
	$output = '';
	if (isset($_SESSION['session_admin_multisite']) && $_SESSION['session_admin_multisite'] === 0 && empty($GLOBALS['site_parameters']['site_name_display_disable'])) {
		// L'administrateur multisite consulte la liste des éléments existants. 
		// Dans ce cas tous les éléments de tous les sites sont affichés, et on affiche le nom du site à coté du nom de la TVA pour éviter des erreurs d'administration.
		$all_sites_name_array = get_all_sites_name_array(true);
		if(!empty($array['site_id']) && count($all_sites_name_array) > 1) {
			$output .= '[' . vb($all_sites_name_array[$array['site_id']]) . '] ';
		}
	}
	return $output;
}

/**
 * affiche_liste_clients_par_produit()
 *
 * @param integer $id
 * @return
 */
function affiche_liste_clients_par_produit($id, $array_output_mode=false)
{
	include($GLOBALS['dirroot']."/lib/class/ProductsBought.php");
	$produit = ProductsBought::find($id);
	if (!empty($produit)) {
		$clients_array = array();
		$i = 0;
		$c = "#E8E8E8";
		foreach ($produit->clients() as $client) {
			if ($c == "#E8E8E8") {
				$c = "#F6F6EB";
			} else {
				$c = "#E8E8E8";
			}
			$clients_array[] = array('tr_rollover' => tr_rollover($i, true),
				'href' => $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $client->id_utilisateur,
				'nom_famille' => $client->nom_famille,
				'prenom' => $client->prenom,
				'adresse' => $client->adresse,
				'code_postal' => $client->code_postal,
				'ville' => $client->ville,
				'email' => $client->email,
				'telephone' => $client->telephone,
				'total_quantite' => $client->total_quantite,
				'prix' => fprix($client->total_paye, true, $GLOBALS['site_parameters']['code'], false)
				);
			$i++;
		}
		if (!empty($array_output_mode)) {
			return $clients_array;
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_clients_par_produit.tpl');
			$tpl->assign('nom', $produit->nom);
			$tpl->assign('is_module_export_ventes_active', check_if_module_active('export'));
			$tpl->assign('export_href', $GLOBALS['wwwroot_in_admin'] . '/modules/export/administrer/export.php?type=ventes_clients_par_produit&id=' . $id);
			$tpl->assign('excel_src', $GLOBALS['administrer_url'] . '/images/excel.jpg');
			$tpl->assign('clients', $clients_array);
			$tpl->assign('STR_LAST_NAME', $GLOBALS['STR_LAST_NAME']);
			$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
			$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
			$tpl->assign('STR_ADMIN_MENU_WEBMASTERING_CLIENTS_EXPORT', $GLOBALS['STR_ADMIN_MENU_WEBMASTERING_CLIENTS_EXPORT']);
			$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
			$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
			$tpl->assign('STR_QUANTITY_SHORT', $GLOBALS['STR_QUANTITY_SHORT']);
			$tpl->assign('STR_TOTAL_AMOUNT', $GLOBALS['STR_TOTAL_AMOUNT']);
			$tpl->assign('STR_ADMIN_PRODUITS_ACHETES_LIST_TITLE', $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_LIST_TITLE']);
			echo $tpl->fetch();
		}
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_PRODUITS_ACHETES_ERR_ID_NOT_FOUND']))->fetch();
	}
}

/**
 * Affiche la liste des utilisateurs en fonction des critères de recherche
 * Un certain nombre de champs de recherche permettent de cherche sur plusieurs colonnes, ce qui permet de simplifier l'interface
 *
 * @param mixed $priv
 * @param mixed $cle
 * @param array $frm
 * @param string $order
 * @param boolean $allow_message_no_result
 * @param boolean $return_sql_request_without_display Permet de retourner les critères SQL de la requête qui sert à l'affichage. Seul les critères SQL sont retournés dans un tableau par la fonction, il n'y pas d'affichage.
 * @return
 */
function afficher_liste_utilisateurs($priv, $cle, $frm = null, $order = 'date_insert', $allow_message_no_result = false, $return_sql_request_without_display = false)
{
	$output = '';
	$sql_inner_array = array();
	$sql_having_array = array();
	$sql_columns_array = array('u.*');
	$sql_where_array = array("" . get_filter_site_cond('utilisateurs', 'u', true) . "");
	$sql_group_by = '';
	$sql_having = '';
	$sql = "";
	/* Recherche de base */
	if (!empty($frm['client_info'])) {
		$sql_where_array[] = '(u.nom_famille LIKE "%' . nohtml_real_escape_string(trim($frm['client_info'])) . '%" OR u.prenom LIKE "%' . nohtml_real_escape_string(trim($frm['client_info'])) . '%")';
	}
	if (!empty($frm['email']) && is_numeric(trim($frm['email']))) {
		// Recherche sur une id - si par exemple on cherche 22, on ne veut pas récupérer les emails contenant 22 => on ne cherche que sur l'id
		$sql_where_array[] = 'u.id_utilisateur = "' . intval($frm['email']) . '"';
	} elseif (!empty($frm['email'])) {
		$sql_where_array[] = '(u.email LIKE "%' . nohtml_real_escape_string(trim($frm['email'])) . '%" OR u.pseudo LIKE "%' . nohtml_real_escape_string(trim($frm['email'])) . '%" OR u.ip LIKE "%' . nohtml_real_escape_string(trim($frm['email'])) . '%" )';
	}
	if (!empty($frm['societe'])) {
		$sql_where_array[] = '(u.societe LIKE "%' . nohtml_real_escape_string(trim($frm['societe'])) . '%" OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(u.siret,")",""),"(",""), ".",""), "-",""), " ","") LIKE "%' . nohtml_real_escape_string(str_replace(array('(', ')', '.', '-', ' '), '', trim($frm['societe']))) . '%" OR u.url LIKE "%' . nohtml_real_escape_string(trim($frm['societe'])) . '%")';
	}
	if (!empty($frm['ville_cp'])) {
		$sql_where_array[] = '(u.ville LIKE "%' . nohtml_real_escape_string(trim($frm['ville_cp'])) . '%" OR ' . get_zip_cond($frm['ville_cp'], 'u', false) . ')';
	}
	if (a_priv('demo')) {
		// priv ne doit pas contenir de droit qui commence par "admin"
		$sql_where_array[] = "CONCAT('+',u.priv,'+') NOT LIKE ('%+" . nohtml_real_escape_string('admin') . "%')";
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_UTILISATEURS_NO_ADMIN_RIGHT_TO_LIST']))->fetch();
	}	
	$basic_search_where_count = count($sql_where_array);
	/* Recherche avancée */
	if (!empty($frm['type'])) {
		$sql_where_array[] = 'u.type = "' . nohtml_real_escape_string($frm['type']) . '"';
	}
	if (isset($frm['control_plus']) && $frm['control_plus'] != '') {
		$sql_where_array[] = 'u.control_plus = "' . intval($frm['control_plus']) . '"';
	}
	if (!empty($frm['fonction'])) {
		$sql_where_array[] = 'u.fonction = "' . nohtml_real_escape_string($frm['fonction']) . '"';
	}
	if (isset($frm['site_on']) && $frm['site_on'] != '') {
		$sql_where_array[] = 'u.url ' . (!empty($frm['site_on'])?' <> ""':' = ""');
	}
	if (!empty($frm['id_cat'])) {
			$sql_where_array[] = '(u.id_cat_1 = "' . nohtml_real_escape_string($frm['id_cat']) . '" OR u.id_cat_2 = "' . nohtml_real_escape_string($frm['id_cat']) . '" OR u.id_cat_3 = "' . nohtml_real_escape_string($frm['id_cat']) . '")';
	}
	if (!empty($frm['id_categories'])) {
		$this_categories_where_array = array();
		foreach($frm['id_categories'] as $this_categories) {
			$this_categories_where_array[] = 'CONCAT(",",u.id_categories,",") LIKE "%,'.$this_categories.',%"';
		}
		$sql_where_array[] = '('.implode(' OR ', $this_categories_where_array).')';
	}

	if (!empty($frm['tel'])) {
		// On recherche sans les caractères séparateurs
		$frm['tel'] = str_replace(array('(', ')', '.', '-', ' '), '', trim($frm['tel']));
		$sql_where_array[] = '(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(u.telephone,")",""),"(",""), ".",""), "-",""), " ","") LIKE "%' . nohtml_real_escape_string($frm['tel']) . '%" OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(u.portable,")",""),"(",""), ".",""), "-",""), " ","") LIKE "%' . nohtml_real_escape_string($frm['tel']) . '%" OR REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(u.fax,")",""),"(",""), ".",""), "-",""), " ","") LIKE "%' . nohtml_real_escape_string($frm['tel']) . '%")';
	}
	if (!empty($frm['origin'])) {
		$sql_where_array[] = 'u.origin LIKE "%' . intval($frm['origin']) . '%"';
	}
	if (!empty($frm['commercial'])) {
		$sql_where_array[] = 'u.commercial_contact_id = "' . intval($frm['commercial']) . '"';
	}
	if (!empty($frm['pays'])) {
		$sql_where_array[] = 'u.pays="' . nohtml_real_escape_string($frm['pays']) . '"';
	}
	if (!empty($frm['continent']) && is_array($frm['continent'])) {
		if (in_array(5, $frm['continent'])) {
			// On considère l'antarctique comme faisant partie de l'océanie
			$frm['continent'][] = 6;
		}
		$sql_where_array[] = 'pays.continent_id IN ("' . implode('","', nohtml_real_escape_string($frm['continent'])) . '")';
		$sql_inner_array['peel_pays'] = 'INNER JOIN peel_pays pays ON pays.id=u.pays AND  ' .  get_filter_site_cond('pays', 'pays');
	}
	if (!empty($frm['seg_who']) && $frm['seg_who'] != '0') {
		$sql_where_array[] = 'u.seg_who = "' . nohtml_real_escape_string($frm['seg_who']) . '"';
	}
	if (!empty($frm['seg_buy'])) {
		$sql_where_array[] = 'u.seg_buy = "' . nohtml_real_escape_string($frm['seg_buy']) . '"';
	}
	if (!empty($frm['seg_want'])) {
		$sql_where_array[] = 'u.seg_want = "' . nohtml_real_escape_string($frm['seg_want']) . '"';
	}
	if (!empty($frm['seg_think'])) {
		$sql_where_array[] = 'u.seg_think = "' . nohtml_real_escape_string($frm['seg_think']) . '"';
	}
	if (!empty($frm['seg_followed'])) {
		$sql_where_array[] = 'u.seg_followed = "' . nohtml_real_escape_string($frm['seg_followed']) . '"';
	}
	if (!empty($frm['raison'])) {
		$sql_inner_array['peel_admins_contacts_planified'] = 'INNER JOIN peel_admins_contacts_planified pacp ON pacp.user_id=u.id_utilisateur';
		$sql_where_array[] = 'pacp.reason="' . nohtml_real_escape_string(vb($frm['raison'])) . '"';
	}
	if (isset($frm['etat']) && $frm['etat'] != '') {
		$sql_where_array[] = 'u.etat="' . nohtml_real_escape_string($frm['etat']) . '"';
	}
	if (isset($frm['newsletter']) && $frm['newsletter'] != '') {
		// Oui validé (1), Oui non validé (2), Non (0)
		if ($frm['newsletter'] == 0) {
			$sql_where_array[] = 'u.newsletter="0"';
		} elseif ($frm['newsletter'] == 1) {
			$sql_where_array[] = 'u.newsletter="1" AND newsletter_validation_date !="0000-00-00 00:00:00"';
		}  elseif ($frm['newsletter'] == 2) {
			$sql_where_array[] = 'u.newsletter="1" AND newsletter_validation_date ="0000-00-00 00:00:00"';
		}
	}
	if (isset($frm['offre_commercial']) && $frm['offre_commercial'] != '') {
		// Oui validé (1), Oui non validé (2), Non (0)
		if ($frm['offre_commercial'] == 0) {
			$sql_where_array[] = 'u.commercial="0"';
		} elseif ($frm['offre_commercial'] == 1) {
			$sql_where_array[] = 'u.commercial="1" AND commercial_validation_date != "0000-00-00 00:00:00"';
		}  elseif ($frm['offre_commercial'] == 2) {
			$sql_where_array[] = 'u.commercial="1" AND commercial_validation_date = "0000-00-00 00:00:00"';
		}
	}
	if (!empty($frm['group'])) {
		$sql_where_array[] = 'u.id_groupe="' . nohtml_real_escape_string(vb($frm['group'])) . '"';
	}
	if (!empty($frm['valid'])) {
		$sql_where_array[] = 'u.valid ="' . nohtml_real_escape_string($frm['valid']) . '"';
	}
	if (!empty($frm['activity'])) {
		$sql_where_array[] = 'u.activity ="' . nohtml_real_escape_string($frm['activity']) . '"';
	}
	if (!empty($frm['user_lang'])) {
		$sql_where_array[] = 'u.lang ="' . nohtml_real_escape_string($frm['user_lang']) . '"';
	}
	if (!empty($frm['list_produit'])) {
		// On récupère d'abord l'id produit pour éviter de surcharger la requête SQL générale par des jointures diverses
		$product_id = get_product_id_by_name($frm['list_produit'], true);
		if(!empty($product_id)) {
			$sql_inner_array['peel_commandes_articles'] = 'INNER JOIN peel_commandes_articles pca ON pca.commande_id= c.id AND ' . get_filter_site_cond('commandes_articles', 'pca', true);
			$sql_where_array[] = 'pca.produit_id="' . nohtml_real_escape_string($product_id) . '"';
			$sql_columns_array[] = 'SUM(pca.quantite) AS this_quantite_sum';
			if (!empty($frm['nombre_produit']) && $frm['nombre_produit'] != "no_info") {
				if ($frm['nombre_produit'] == -1) {
					$sql_having_array[] = 'this_quantite_sum=0';
				} else {
					$sql_having_array[] = 'this_quantite_sum>="' . intval($frm['nombre_produit']) . '"';
				}
			} else {
				// Par défaut : produit acheté une fois au moins
				$sql_having_array[] = 'this_quantite_sum>0';
			}
		} else {
			$sql_where_array[] = '0';
		}
	}

	if (!empty($frm['abonne'])) {
		if (in_array($frm['abonne'], array('any', 'no', 'never', 'earlier'))) {
			if ($frm['abonne'] == 'any') { // Tous abonnements confondus
				$sql_where_array[] = '(u.platinum_status="YES" OR u.diamond_status="YES")';
			} elseif ($frm['abonne'] == 'no') {
				$sql_where_array[] = 'u.platinum_until<"' . time() . '" AND u.diamond_until<"' . time() . '"';
			} elseif ($frm['abonne'] == 'never') { // Jamais été abonné
				$sql_where_array[] = 'u.platinum_status="NO" AND u.diamond_status="NO"';
			} elseif ($frm['abonne'] == 'earlier') { // Pas abonné actuellement mais l'a déjà été
				$sql_where_array[] = '(u.platinum_until BETWEEN 1 AND "' . time() . '" OR u.diamond_until BETWEEN 1 AND "' . time() . '")';
			}
		}
		if ($frm['abonne'] == 'platinum_until') { // Platinum
			$sql_where_array[] = 'u.platinum_status="YES" AND u.platinum_until!=0';
		} elseif ($frm['abonne'] == 'diamond_until') { // Diamond
			$sql_where_array[] = 'u.diamond_until!=0 AND u.diamond_status="YES" ';
		}
	}
	if (check_if_module_active('annonces')) {
		if (!empty($frm['list_annonce']) && $frm['list_annonce'] != '0') {
			$sql_inner_array['peel_lot_vente'] = 'INNER JOIN peel_lot_vente plv ON plv.id_personne=u.id_utilisateur';
			$sql_inner_array['peel_categories_annonces'] = 'INNER JOIN peel_categories_annonces pcan ON pcan.id=plv.id_categorie';
			$sql_where_array[] = 'plv.id_categorie="' . nohtml_real_escape_string($frm['list_annonce']) . '"';
		}
		if (!empty($frm['annonces_contiennent'])) {
			$sql_inner_array['peel_lot_vente'] = 'INNER JOIN peel_lot_vente plv ON plv.id_personne=u.id_utilisateur';
			foreach ($GLOBALS['admin_lang_codes'] as $lng) {
				$sql_where_array[] = 'plv.description_' . word_real_escape_string($lng) . ' LIKE "%' . nohtml_real_escape_string($frm['annonces_contiennent']) . '%"';
			}
		}
		if (!empty($frm['with_gold_ad'])) {
			$sql_inner_array['peel_gold_ads'] = 'INNER JOIN peel_gold_ads pga ON pga.user_id=u.id_utilisateur';
			$sql_where_array[] = 'pga.actif="' . nohtml_real_escape_string($frm['with_gold_ad']) . '"';
		}
	}
	foreach(array('ads_count' => 'ads_count', 'date_last_paiement' => 'cdlt.a_timestamp', 'date_derniere_connexion' => 'date', 'date_insert' => 'u.date_insert', 'date_statut_commande' => 'c.o_timestamp', 'date_contact_prevu' => 'pacp.timestamp') as $this_get => $this_sql_field) {
		if (!empty($frm[$this_get])) {
			if (substr($this_get, 0, 5) == 'date_') {				
				if(vb($frm[$this_get . '_input1'])=='') {
					continue;
				}
				$first_value = get_mysql_date_from_user_input($frm[$this_get . '_input1']);
				if ($frm[$this_get] == '1') {
					// Une valeur cherchée uniquement : le X
					$last_value = $first_value . ' 23:59:59';
				} elseif ($frm[$this_get] == '2') {
					// Si "à partir de...", on va recupérer tous les utilisateurs
					$last_value = '2030-12-31 23:59:59';
				} elseif ($frm[$this_get] == '3' || $frm[$this_get] == '5' || $frm[$this_get] == '6' || $frm[$this_get] == '7') {
					// Entre le jour X et le jour Y
					$last_value = str_replace('0000-00-00', '2030-12-31', get_mysql_date_from_user_input($frm[$this_get . '_input2']));
					if ((!empty($frm['actual_time'])) && ($frm['actual_time'] == 1)) {
						$last_value .= ' ' . date('H:i:s', (time()));
						// $output .=$last_value;
					} else {
						$last_value .= ' 23:59:59';
					}
				} elseif ($frm[$this_get] == '4') {
					 // Avant le
					 $last_value =  str_replace('0000-00-00', '2030-12-31', $first_value);
					 $first_value = '0000-00-00 00:00:00';
				} else {
					$output .=$GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CASE_NOT_FORECASTED'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': %s', $frm[$this_get])))->fetch();
				}
			} else {
				$first_value = vb($frm[$this_get . '_input1']);
				$last_value = vb($frm[$this_get . '_input2']);
			}
			$this_cond_temp_expression = $this_sql_field . '>="' . nohtml_real_escape_string($first_value) . '"';
			if ($last_value != '2030-12-31 23:59:59') {
				// On ne passe jamais ici normalement car on ne serait pas dans le cas "à partir du" - mais on laisse pour sécurité
				$this_cond_temp_expression .= ' AND ' . $this_sql_field . '<"' . nohtml_real_escape_string($last_value) . '"';
			}
			// if ($this_get == 'next_contact_date') {
			// $sql_where_array[] = 'u.' . $users_table_fields['users_' . str_replace('date', 'timestamp', $this_get)] . '>="' . strtotime($first_value) . '" AND u.' . $users_table_fields['users_' . str_replace('date', 'timestamp', $this_get)] . '<"' . strtotime($last_value) . '"';
			if ($this_get == 'date_derniere_connexion') {
				// Champ pas dans la table peel_utilisateurs mais calculée à partir d'un MAX(uc.date) venant de peel_utilisateur_connexions
				// ATTENTION : il y a eu des problèmes avec les jointures générées si on faisait une jonture normale (requête durant 200s en juin 2009 !)
				// =>il vaut 1000 fois mieux avoir une sous-requête qui trouve d'abord la liste des utilisateurs connectés dans la plage de dates recherchée, et après on fait jointure avec INNER JOIN
				if ($frm[$this_get] == '2') {
					// dernière connexion à partir du X
					// Cas plus optimisé que les autres => pas de jointure LEFT JOIN en plus comme le cas d'après
					// Pas besoin d'ajouter une sql_cond_array, c'est le INNER JOIN qui suffit
					$sql_inner_array['peel_utilisateur_connexions'] = 'INNER JOIN (SELECT user_id, user_login, user_ip, MAX(date) AS date FROM peel_utilisateur_connexions WHERE ' . $this_cond_temp_expression . ' AND ' . get_filter_site_cond('utilisateur_connexions', null, true) . ' GROUP BY user_id) uc ON uc.user_id=u.id_utilisateur
';
				} else {
					// Dernière connexion avant le "last_date" et après first_date (first_date étant peut être égal à 0000-00-00 00:00:00 si $frm[$this_get] == '4')
					$sql_inner_array['peel_utilisateur_connexions'] = 'INNER JOIN (SELECT * FROM peel_utilisateur_connexions WHERE ' . $this_cond_temp_expression . ' AND ' . get_filter_site_cond('utilisateur_connexions', null, true) . ' GROUP BY user_id) uc ON uc.user_id=u.id_utilisateur
						LEFT JOIN peel_utilisateur_connexions uc2 ON uc2.user_id=u.id_utilisateur AND uc2.date>"' . nohtml_real_escape_string($last_value) . '" AND ' . get_filter_site_cond('utilisateur_connexions', 'uc2', true) . '
';
					$sql_where_array[] = 'uc2.date IS NULL';
					// Pour accélérer les requêtes et éviter des recherches inutiles dans uc, on met une condition sur la date d'inscription
					$sql_where_array[] = 'u.date_insert<="' . nohtml_real_escape_string($last_value) . '"';
				}
			} elseif ($this_get == 'date_last_paiement') {
				// Utilisation de la date de paiement pour appliquer le filtre "Date de dernier paiement :"
				$sql_where_array[] = 'u.id_utilisateur IN (
						SELECT cdlt.id_utilisateur
						FROM peel_commandes cdlt
						INNER JOIN peel_statut_paiement spdlt ON spdlt.id=cdlt.id_statut_paiement AND ' . get_filter_site_cond('statut_paiement', 'spdlt') . ' AND spdlt.technical_code IN ("being_checked","completed")
						WHERE ' . get_filter_site_cond('commandes', 'cdlt', true) . '
						AND ' . $this_cond_temp_expression . '
						HAVING MAX(' . $this_cond_temp_expression . '))';
			} elseif ($this_get == 'date_statut_commande') {
				if ($frm['date_statut_commande'] == '5') {
					// Pas de commande entre x et y (payée ou non)
					$sql_where_array[] = 'u.id_utilisateur NOT IN (
						SELECT c2.id_utilisateur
						FROM peel_commandes c2
						WHERE ' . get_filter_site_cond('commandes', 'c2', true) . ' AND ' . $this_cond_temp_expression . ')';
				} elseif ($frm['date_statut_commande'] == '6') {
					// Commande non payée
					$sql_where_array[] = $this_cond_temp_expression;
				} else {
					// Commande payée entre x et y
					$sql_where_array[] = str_replace('o_timestamp', 'a_timestamp', $this_cond_temp_expression);
				}
			} elseif ($this_get == 'date_contact_prevu') {
				$sql_inner_array['peel_admins_contacts_planified'] = 'INNER JOIN peel_admins_contacts_planified pacp ON pacp.user_id=u.id_utilisateur';
				$timestamp1 = strtotime(get_mysql_date_from_user_input($frm[$this_get . '_input1']));
				$timestamp_planified_contact_1 = mktime(0, 0, 0, date('m', $timestamp1), date('d', $timestamp1), date('Y', $timestamp1));
				$timestamp2 = strtotime(get_mysql_date_from_user_input($frm[$this_get . '_input2']));
				$timestamp_planified_contact_2 = mktime(0, 0, 0, date('m', $timestamp2), date('d', $timestamp2), date('Y', $timestamp2));
				// Date de contact égal à
				if ($frm[$this_get] == 1) {
					$sql_where_array[] = 'pacp.timestamp = "' . nohtml_real_escape_string($timestamp_planified_contact_1) . '"';
					// Date de contact à partir de
				} elseif ($frm[$this_get] == 2) {
					$sql_where_array[] = 'pacp.timestamp > "' . nohtml_real_escape_string($timestamp_planified_contact_1) . '"';
					// Date de contact à entre le
				} elseif ($frm[$this_get] == 3) {
					$sql_where_array[] = 'pacp.timestamp BETWEEN "' . nohtml_real_escape_string($timestamp_planified_contact_1) . '" AND "' . nohtml_real_escape_string($timestamp_planified_contact_2) . '"';
					// Date de contact avant le
				} elseif ($frm[$this_get] == 4) {
					$sql_where_array[] = 'pacp.timestamp < "' . nohtml_real_escape_string($timestamp_planified_contact_1) . '"';
				}
			} elseif ($this_get == 'ads_count') {
				$sql_inner_array['peel_lot_vente'] = 'INNER JOIN peel_lot_vente plv ON plv.id_personne=u.id_utilisateur';
				$sql_columns_array[] = 'COUNT(plv.ref) AS ads_count';
				// Nombre d'annonce égal à
				if (intval($frm['ads_count']) == 1) {
					$sql_having_array[] = 'ads_count = "' . nohtml_real_escape_string($first_value) . '"';
					// Nombre d'annonce supérieur à
				} elseif (intval($frm['ads_count']) == 2) {
					$sql_having_array[] = 'ads_count > "' . nohtml_real_escape_string($first_value) . '"';
					// Nombre d'annonce comprise entre
				} elseif (intval($frm['ads_count']) == 3) {
					$sql_having_array[] = 'ads_count BETWEEN "' . nohtml_real_escape_string($first_value) . '" AND "' . nohtml_real_escape_string($last_value) . '"';
					// Nombre d'annonce inférieur à
				} elseif (intval($frm['ads_count']) == 4) {
					$sql_having_array[] = 'ads_count < "' . nohtml_real_escape_string($first_value) . '"';
				}
			} else {
				$sql_where_array[] = $this_cond_temp_expression;
			}
		}
	}
	if (!empty($frm['site_connexion'])) {
		$sql_inner_array['site_connexion'] = 'LEFT JOIN peel_utilisateur_connexions puc ON puc.user_id=u.id_utilisateur';
		$sql_where_array[] = 'puc.site_id="' . nohtml_real_escape_string($frm['site_connexion']) . '"';
	}
	if (!empty($cle)) {
		$sql_where_array[] = "(u.code_client LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.email LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.ville LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.nom_famille LIKE '%" . nohtml_real_escape_string($cle) . "%' OR " . get_zip_cond($cle, 'u', false) . ") ";
	}
	if (!empty($priv) && $priv == "newsletter") {
		$sql_where_array[] = "(CONCAT('+',u.priv,'+') LIKE '%+" . nohtml_real_escape_string($priv) . "+%' OR u.newsletter = '1')";
	} elseif (!empty($priv)) {
		$sql_where_array[] = "CONCAT('+',u.priv,'+') LIKE '%+" . nohtml_real_escape_string($priv) . "+%'";
	}
	
	$hook_output = call_module_hook('user_admin_list_sql_cond', array('frm'=>$_GET), 'array');
	if(!empty($hook_output)) {
		$sql_where_array = array_merge($sql_where_array, $hook_output);
	}
	$sql = "SELECT " . implode(', ', $sql_columns_array) . ", p.name_".$_SESSION['session_langue']." AS profil_name, SUM(".(display_prices_with_taxes_active()?'montant':'montant_ht').") AS total_ordered, COUNT(c.id) AS count_ordered
		FROM peel_utilisateurs u
		LEFT JOIN peel_profil p ON p.priv=u.priv AND " . get_filter_site_cond('profil', 'p') . "
		LEFT JOIN peel_commandes c ON c.id_utilisateur=u.id_utilisateur AND " . get_filter_site_cond('commandes', 'c', true) . " 
		" . implode(' ', $sql_inner_array) . "
		WHERE  " . implode(' AND ', $sql_where_array) . '
		GROUP BY u.id_utilisateur';

	if (!empty($sql_having_array)) {
		$sql .= '
		HAVING (' . implode(') AND (', $sql_having_array) . ') ';
	}
	//if(isset($_GET['debug'])) echo $sql;
	if (!empty($return_sql_request_without_display)) {
		return $sql;
	}
	$Links = new Multipage($sql, 'utilisateurs');
	$HeaderTitlesArray = array($GLOBALS["STR_ADMIN_ADMIN_ACTIONS_ACTIONS"], 'code_client' => $GLOBALS["STR_ADMIN_PRIVILEGE"].' / '.$GLOBALS["STR_ADMIN_UTILISATEURS_CLIENT_CODE"], 'nom_famille' => $GLOBALS["STR_FIRST_NAME"].' / '.$GLOBALS["STR_LAST_NAME"].'<br />'.$GLOBALS["STR_EMAIL"]);
	$HeaderTitlesArray[] = $GLOBALS["STR_TELEPHONE"];
	if (check_if_module_active('groups')) {
		$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_GROUP"];
	}
	$HeaderTitlesArray['date_insert'] = $GLOBALS["STR_ADMIN_UTILISATEURS_REGISTRATION_DATE"];
	$HeaderTitlesArray['total_ordered'] = $GLOBALS["STR_ADMIN_INDEX_ORDERS_LIST"];
	$HeaderTitlesArray['remise_percent'] = $GLOBALS["STR_ADMIN_DISCOUNT"];
	$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_UTILISATEURS_WAITING_CREDIT"];
	$HeaderTitlesArray['points'] = $GLOBALS['STR_GIFT_POINTS'];
	if (check_if_module_active('parrainage')) {
		$HeaderTitlesArray[] = $GLOBALS["STR_ADMIN_UTILISATEURS_HAS_SPONSOR"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':';
	}
	$HeaderTitlesArray['site_id'] = $GLOBALS['STR_ADMIN_SITES_SITE_NAME'];
	$HeaderTitlesArray['ip'] = $GLOBALS['STR_ADMIN_IP'];

	// Ce hook permet de définir une nouvelle liste de titre pour le tableau HTML de liste d'utilisateur en fonction du contexte, et de donner le fichier tpl en relation avec cette liste
	$hook_output = call_module_hook('user_admin_list_before', array('frm' => $_GET), 'array');
	if (!empty($hook_output['HeaderTitlesArray'])) {
		$HeaderTitlesArray = $hook_output['HeaderTitlesArray'];
	}
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = $order;
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();
	if(empty($results_array) && $allow_message_no_result) {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_SEARCH_NO_RESULT']))->fetch();
	} else {
		$select_search_array['date_insert'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"].' ' . $GLOBALS["STR_ADMIN_DATE_ON"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"].' '.$GLOBALS["STR_ADMIN_DATE_AFTER"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"].' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SUBSCRIBED"] . ' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"]. $GLOBALS['STR_BEFORE_TWO_POINTS'].':');
		$select_search_array['date_last_paiement'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' ' . $GLOBALS["STR_ADMIN_DATE_ON"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' '.$GLOBALS["STR_ADMIN_DATE_AFTER"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' '.$GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_PAYMENT"].' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
		$select_search_array['date_statut_commande'] = array(5 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_NO_ORDER"].' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 6 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_ORDER_NOT_PAID"] . ' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 7 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_ORDER_PAID"].' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
		$select_search_array['date_derniere_connexion'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' ' . $GLOBALS["STR_ADMIN_DATE_ON"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' ' .$GLOBALS["STR_ADMIN_DATE_AFTER"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_LAST_LOGIN"] . ' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
		$select_search_array['date_contact_prevu'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' ' . $GLOBALS["STR_ADMIN_DATE_ON"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' ' .$GLOBALS["STR_ADMIN_DATE_AFTER"]. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' ' . $GLOBALS["STR_ADMIN_DATE_BETWEEN_START"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_CONTACT_FORECASTED"] . ' '.$GLOBALS["STR_ADMIN_DATE_BEFORE"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
		$select_search_array['ads_count'] = array(1 => $GLOBALS["STR_ADMIN_COMPARE_EQUALS"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 2 => $GLOBALS["STR_ADMIN_MORE_THAN"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 3 => $GLOBALS["STR_ADMIN_COMPARE_BETWEEN"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' , 4 => $GLOBALS["STR_ADMIN_LESS_THAN"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
		$select_search_array['abonne'] = array(1 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NEVER"], 2 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NOT_NOW"], 3 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_NOT_NOW_BUT_EARLIER"], 4 => $GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_SUBSCRIPTION_ALL"]);
		$select_search_array['nombre_produit'] = tab_followed_nombre_produit();
		if (!empty($hook_output['new_admin_utilisateur_liste_tpl_file'])) {
			$file_name = $hook_output['new_admin_utilisateur_liste_tpl_file'];
		} else {
			$file_name = 'admin_utilisateur_liste.tpl';
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate($file_name);
		if (check_if_module_active('groups')) {
			$resGroupe = query("SELECT *
				FROM peel_groupes
				WHERE " . get_filter_site_cond('groupes') . "
				ORDER BY nom");
			if (num_rows($resGroupe)) {
				$tpl_groupes_options_utilisateurs = array();
				while ($Groupe = fetch_assoc($resGroupe)) {
					$tpl_groupes_options_utilisateurs[] = array('value' => $Groupe['id'],
						'issel' => vb($frm['id_groupe']) == $Groupe['id'],
						'name' => $Groupe['nom'],
						'remise' => $Groupe['remise']
						);
				}
				$tpl->assign('groupes_options_utilisateurs', $tpl_groupes_options_utilisateurs);
			}
			$tpl->assign('STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED']);
         }
		$tpl->assign('STR_ADMIN_MODIFICATION_MULTIPLE', $GLOBALS['STR_ADMIN_MODIFICATION_MULTIPLE']);
		$tpl->assign('STR_ADMIN_ASSIGN', $GLOBALS['STR_ADMIN_ASSIGN']);
		$tpl->assign('STR_ADMIN_UNASSIGN', $GLOBALS['STR_ADMIN_UNASSIGN']);
		$tpl->assign('STR_ADMIN_ASSIGN_UNASSIGN_USERS_DO_NOT_HAVE_GROUP', $GLOBALS['STR_ADMIN_ASSIGN_UNASSIGN_USERS_DO_NOT_HAVE_GROUP']);
		
		$GLOBALS['js_ready_content_array'][] = '
			display_input2_element("search_date_insert");
			display_input2_element("search_date_last_paiement");
			display_input2_element("search_date_statut_commande");
			display_input2_element("search_date_contact_prevu");
			display_input2_element("search_date_derniere_connexion");
			display_input2_element("search_ads_count");

			$("#search_details").on("hide.bs.collapse", function () {
				$("#search_icon").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
				// $("#search_col").removeClass("col-md-12").removeClass("col-sm-12").addClass("col-md-9").addClass("col-sm-4");
			});
			$("#search_details").on("show.bs.collapse", function () {
				$("#search_icon").removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-down");
				// $("#search_col").removeClass("col-md-9").removeClass("col-sm-4").addClass("col-md-12").addClass("col-sm-12");
			});
';

		$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
		$tpl->assign('action', get_current_url(false));
		$tpl->assign('profil_select_options', get_priv_options(vb($_GET['priv']), 'output', true));
		$tpl->assign('newsletter_options', formSelect('newsletter', tab_followed_newsletter(), vb($_GET['newsletter'])));
		$tpl->assign('offre_commercial_options', formSelect('offre_commercial', tab_followed_newsletter(), vb($_GET['offre_commercial'])));
		$tpl->assign('is_advanced_search', (count($sql_where_array) - $basic_search_where_count) > 0);
		$tpl->assign('STR_ADMIN_IP', $GLOBALS['STR_ADMIN_IP']);

		// sélection des commerciaux
		$comm_query = query('SELECT u.id_utilisateur, u.prenom, u.nom_famille
			FROM peel_utilisateurs u2
			INNER JOIN peel_utilisateurs u ON u.id_utilisateur = u2.commercial_contact_id AND ' . get_filter_site_cond('utilisateurs', 'u', true) . '
			WHERE u2.commercial_contact_id != 0 AND ' . get_filter_site_cond('utilisateurs', 'u2', true) . '
			GROUP BY u2.commercial_contact_id');
		$tpl_comm_opts = array();
		while ($commercial = fetch_assoc($comm_query)) {
			$tpl_comm_opts[] = array('value' => $commercial["id_utilisateur"],
				'issel' => StringMb::str_form_value(vb($_GET['commercial'])) == $commercial["id_utilisateur"],
				'prenom' => vb($commercial["prenom"]),
				'nom_famille' => vb($commercial["nom_famille"])
				);
		}
		$tpl->assign('commercial_options', $tpl_comm_opts);

		$tpl->assign('country_select_options', get_country_select_options(null, vb($_GET['pays']), 'id', true, null, false));

		$tpl_langs = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$tpl_langs[] = array('value' => $lng,
				'issel' => (vb($_GET['user_lang']) == $lng),
				'name' => $lng
				);
		}
		$tpl->assign('langs', $tpl_langs);

		// sélection des continents
		$query_continent = query("SELECT id, name_" . $_SESSION['session_langue'] . " AS name
			FROM peel_continents
			WHERE " . get_filter_site_cond('continents') . "
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
				'issel' => StringMb::str_form_value(vb($_GET['date_insert'])) == $index,
				'name' => $item
				);
		}
		$tpl->assign('date_insert_options', $tpl_date_insert_opts);

		$tpl_date_last_paiement_opts = array();
		foreach ($select_search_array['date_last_paiement'] as $index => $item) {
			$tpl_date_last_paiement_opts[] = array('value' => $index,
				'issel' => StringMb::str_form_value(vb($_GET['date_last_paiement'])) == $index,
				'name' => $item
				);
		}
		$tpl->assign('date_last_paiement_options', $tpl_date_last_paiement_opts);

		$tpl_date_statut_commande_opts = array();
		foreach ($select_search_array['date_statut_commande'] as $index => $item) {
			$tpl_date_statut_commande_opts[] = array('value' => $index,
				'issel' => StringMb::str_form_value(vb($_GET['date_statut_commande'])) == $index,
				'name' => $item
				);
		}
		$tpl->assign('date_statut_commande_options', $tpl_date_statut_commande_opts);

		$tpl_user_origin_opts = array();
		$i = 1;
		while (isset($GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i])) {
			$tpl_user_origin_opts[] = array('value' => $i,
				'issel' => StringMb::str_form_value(vb($_GET['origin'])) == $i,
				'name' => $GLOBALS['STR_USER_ORIGIN_OPTIONS_' . $i]
				);
			$i++;
		}
		$tpl->assign('user_origin_options', $tpl_user_origin_opts);

		$tpl->assign('is_groups_module_active', check_if_module_active('groups'));
		if (check_if_module_active('groups')) {
			$resGroupe = query("SELECT *
				FROM peel_groupes
				WHERE " . get_filter_site_cond('groupes') . "
				ORDER BY nom");
			if (num_rows($resGroupe)) {
				$tpl_groupes_options = array();
				while ($Groupe = fetch_assoc($resGroupe)) {
					$tpl_groupes_options[] = array('value' => $Groupe['id'],
						'issel' => vb($frm['id_groupe']) == $Groupe['id'],
						'name' => $Groupe['nom']
						);
				}
				$tpl->assign('groupes_options', $tpl_groupes_options);
			}
			$tpl->assign('STR_ADMIN_GROUP', $GLOBALS['STR_ADMIN_GROUP']);
			$tpl->assign('STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED']);
		}

		$tpl->assign('ville_cp', vb($_GET['ville_cp']));
		$tpl->assign('seg_who', formSelect('seg_who', tab_who(), vb($_GET['seg_who'])));
		$tpl->assign('seg_buy', formSelect('seg_buy', tab_buy(), vb($_GET['seg_buy'])));
		$tpl->assign('seg_want', formSelect('seg_want', tab_want(), vb($_GET['seg_want'])));
		$tpl->assign('seg_think', formSelect('seg_think', tab_think(), vb($_GET['seg_think'])));
		$tpl->assign('seg_followed', formSelect('seg_followed', tab_followed(), vb($_GET['seg_followed'])));

		$tpl->assign('add_b2b_form_inputs', !empty($GLOBALS['site_parameters']['add_b2b_form_inputs']));
		$tpl->assign('is_abonnement_module_active', check_if_module_active('abonnement'));
		if (check_if_module_active('abonnement')) {
			$tpl->assign('abonne', formSelect('abonne', tab_followed_abonne(), vb($_GET['abonne'])));
		}

		$tpl_produits_opts = array();
		$prod_query = query("SELECT id, nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." AS name
			FROM peel_produits
			WHERE " . get_filter_site_cond('produits') . "
			ORDER BY name
			LIMIT 200");
		while ($this_product = fetch_assoc($prod_query)) {
			$tpl_produits_opts[] = array('value' => $this_product['id'],
				'issel' => StringMb::str_form_value(vb($_GET['list_produit'])) == $this_product['id'],
				'name' => $this_product['name'],
				'id' => $this_product['id']
				);
		}
		$tpl->assign('produits_options', $tpl_produits_opts);
		$tpl->assign('nombre_produit', formSelect('nombre_produit', tab_followed_nombre_produit(), vb($_GET['nombre_produit'])));
		$tpl->assign('is_annonce_module_active', check_if_module_active('annonces'));
		if (check_if_module_active('annonces')) {
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
		$tpl->assign('site_id_select_options', get_site_id_select_options(isset($_GET['site_connexion'])?vb($_GET['site_connexion']):null));
		$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
		$tpl->assign('count_HeaderTitlesArray', count($HeaderTitlesArray));
		$tpl->assign('nbRecord', vn($Links->nbRecord));
		$tpl->assign('is_client_info', isset($_GET['client_info']));
		$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
		$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);
		$tpl->assign('priv', $priv);
		$tpl->assign('cle', $cle);
		$tpl->assign('link_multipage', $Links->GetMultipage());
		$tpl->assign('link_HeaderRow', $Links->getHeaderRow());
		$tpl->assign('is_not_demo', !a_priv('demo'));
		$tpl->assign('is_groups_module_active', check_if_module_active('groups'));
		$tpl->assign('is_parrainage_module_active', check_if_module_active('parrainage'));

		if (!empty($results_array)) {
			$tpl_results = array();
			$i = 0;
			foreach ($results_array as $user) {
				$phone_output_array = array();
				if (!empty($user['telephone']) && check_if_module_active('phone_cti')) {
					$phone_output_array[] = getCallLink($user['id_utilisateur'], StringMb::str_shorten_words($user['telephone'], 16, ' '), $user['email'], $user['pays'], true);
				} elseif (!empty($user['telephone'])) {
					$phone_output_array[] = $user['telephone'];
				}
				if (!empty($user['portable']) && check_if_module_active('phone_cti')) {
					$phone_output_array[] = getCallLink($user['id_utilisateur'], StringMb::str_shorten_words($user['portable'], 16, ' '), $user['email'], $user['pays'], true);
				} elseif (!empty($user['portable'])) {
					$phone_output_array[] = $user['portable'];
				}

				$tpl_annonces_count = null;
				if (check_if_module_active('annonces')) { // si le module d'annonce est activé
					$annonces_count = query('SELECT count(*) AS nb
						FROM peel_lot_vente
						WHERE id_personne = ' . intval($user['id_utilisateur']));
					$annonces_count = fetch_assoc($annonces_count);
					$tpl_annonces_count = vn($annonces_count["nb"]);
				}

				$tpl_group_nom = null;
				$tpl_group_remise = null;
				if (check_if_module_active('groups')) {
					$sqlG = "SELECT *
						FROM peel_groupes
						WHERE id = '" . intval($user['id_groupe']) . "' AND  " . get_filter_site_cond('groupes') . "";
					$resG = query($sqlG);
					if ($G = fetch_object($resG)) {
						$tpl_group_nom = $G->nom;
						$tpl_group_remise = $G->remise;
					}
				}

				$tpl_calculer_avoir_client_prix = null;
				$tpl_compter_nb_commandes_parrainees = null;
				$tpl_recuperer_parrain = null;
				if (check_if_module_active('parrainage')) {
					$tpl_compter_nb_commandes_parrainees = compter_nb_commandes_parrainees($user['id_utilisateur']);
					$tpl_recuperer_parrain = recuperer_parrain($user['id_utilisateur']);
				}
				if (!EmailOK(vb($user['email']), vb($user['email_bounce']))) {
					$email_infos = '<span style="color: red"><span class="glyphicon glyphicon-warning-sign"></span> ' . "Emails rejected" . '</span>';
				} else {
					$email_infos = '';
				}
				$tpl_results[$i] = array('tr_rollover' => tr_rollover($i, true),
					'id_utilisateur' => $user['id_utilisateur'],
					'email' => vb($user['email']),
					'email_infos' => vb($email_infos),
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
					'siret_length' => StringMb::strlen($user['siret']),
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
					'total_ordered' => fprix($user['total_ordered'], true),
					'count_ordered' => $user['count_ordered'],
					'compter_nb_commandes_parrainees' => $tpl_compter_nb_commandes_parrainees,
					'recuperer_parrain' => $tpl_recuperer_parrain,
					'site_name' => get_site_name($user['site_id']),
					'ip' => $user['ip']
					);
					$hook_result = call_module_hook('user_admin_list_tpl_results', array('frm' => $_GET, 'user' => $user), 'array');

					if (!empty($hook_result)) {
						$tpl_results[$i] = array_merge($tpl_results[$i], $hook_result);
					}
					
				$i++;
			}
			$tpl->assign('results', $tpl_results);
		}
		$export_client_href = $GLOBALS['wwwroot'] . '/modules/export/administrer/export.php?type=clients&export=search_user';
		if (!empty($_GET['mode']) && $_GET['mode'] == 'search') {
			foreach($_GET as $key => $value) {
				if ($key!='mode') {
					if (is_array($value)) {
						foreach($value as $this_value) {
							$export_client_href .= "&amp;".$key.'[]='.$this_value;
						}
					} else {
						$export_client_href .= "&amp;".$key.'='.$value;
					}
				}
			}
		}
		$tpl->assign('export_client_href', $export_client_href);
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
		$tpl->assign('list_produit', vb($_GET['list_produit']));
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
		$tpl->assign('fonction_options', get_user_job_options(vb($_GET['fonction'])));
		$tpl->assign('site_on', vb($_GET['site_on']));
		$tpl->assign('is_crons_module_active', check_if_module_active('crons'));
		$tpl->assign('pseudo_is_not_used', !empty($GLOBALS['site_parameters']['pseudo_is_not_used']));
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
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
		$tpl->assign('STR_CONTINENT', $GLOBALS['STR_CONTINENT']);
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
		$tpl->assign('STR_DELETE_SELECTION', $GLOBALS['STR_DELETE_SELECTION']);
		if (check_if_module_active('annonces')) {
			if (vb($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) == 'checkbox') {
				$tpl->assign('id_categories', get_ad_select_options(null, vb($_GET['id_categories']), 'id', false, false, 'checkbox', 'id_categories'));	
			} elseif (vb($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) == 'select') {
				$tpl->assign('id_cat_1', get_ad_select_options(null, vb($_GET['id_cat_1']), 'id'));
				$tpl->assign('id_cat_2', get_ad_select_options(null, vb($_GET['id_cat_2']), 'id'));
				$tpl->assign('id_cat_3', get_ad_select_options(null, vb($_GET['id_cat_3']), 'id'));
			}
			$tpl->assign('STR_ADMIN_CHOOSE_FAVORITE_CATEGORIES', $GLOBALS['STR_ADMIN_CHOOSE_FAVORITE_CATEGORIES']);
			$tpl->assign('STR_FIRST_CHOICE', $GLOBALS['STR_FIRST_CHOICE']);
			$tpl->assign('STR_SECOND_CHOICE', $GLOBALS['STR_SECOND_CHOICE']);
			$tpl->assign('STR_THIRD_CHOICE', $GLOBALS['STR_THIRD_CHOICE']);
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
		$tpl->assign('STR_ADMIN_UTILISATEURS_ORDERS_LIST', $GLOBALS['STR_ADMIN_UTILISATEURS_ORDERS_LIST']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_CREATE_ORDER', $GLOBALS['STR_ADMIN_UTILISATEURS_CREATE_ORDER']);
		$tpl->assign('STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_SUBTITLE', $GLOBALS['STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_SUBTITLE']);
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
		$tpl->assign('STR_ADMIN_MENU_WEBMASTERING_CLIENTS_EXPORT', $GLOBALS['STR_ADMIN_MENU_WEBMASTERING_CLIENTS_EXPORT']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_NO_SUPPLIER_FOUND', $GLOBALS['STR_ADMIN_UTILISATEURS_NO_SUPPLIER_FOUND']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_FILER_EXPLAIN', $GLOBALS['STR_ADMIN_UTILISATEURS_FILER_EXPLAIN']);
		$tpl->assign('STR_ORDER_FORM', $GLOBALS['STR_ORDER_FORM']);
		$tpl->assign('STR_ADMIN_UTILISATEURS_GIFT_CHECK', $GLOBALS['STR_ADMIN_UTILISATEURS_GIFT_CHECK']);
		$tpl->assign('STR_MORE_DETAILS', $GLOBALS['STR_MORE_DETAILS']);

		if (check_if_module_active('crons') && check_if_module_active('webmail')) {
			$tpl->assign('send_email_all_form', get_send_email_all_form($Links, $sql));
		}
		$hook_result = call_module_hook('admin_user_list_template_data', array('frm' => $_GET), 'array');
		foreach($hook_result as $this_key => $this_value) {
			$tpl->assign($this_key, $this_value);
		}
		$output .=$tpl->fetch();
	}
	return $output;
}

/**
 * Charge le code des modules non installés et prépare des informations pour une installation potentielle
 *
 * @return
 */
function preload_modules()
{
	// Identification et chargement des modules détectés 
	$GLOBALS['premium_modules_array'] = array('affiliation', 'birthday', 'cart_preservation', 'category_promotion', 'comparateur', 'download', 'duplicate', 
			'facture_advanced', 'faq', 'gift_check', 'gifts', 'good_clients', 'groups', 'lexique', 'lot', 'marges', 'marques_promotion', 'micro_entreprise',
			'parrainage', 'picking', 'reseller', 'statistiques', 'stock_advanced', 'url_rewriting', 'welcome_ad');
	$GLOBALS['modules_light_default_names'] = array('expeditor' => $GLOBALS['STR_ADMIN_SITES_EXPEDITOR_MODULE'], 'facebook' => $GLOBALS['STR_ADMIN_SITES_FACEBOOK_MODULE'],
			'icirelais' => $GLOBALS['STR_ADMIN_SITES_ICI_RELAIS_MODULE'], 'sips' => $GLOBALS['STR_ADMIN_SITES_SIPS_MODULE'],
			'spplus' => $GLOBALS['STR_ADMIN_SITES_SPPLUS_MODULE'], 'paybox' => $GLOBALS['STR_ADMIN_SITES_PAYBOX_MODULE'],
			'systempay' => $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY'], 'partenaires' => $GLOBALS['STR_ADMIN_SITES_PARTNERS_MODULE'],
			'ecotaxe' => $GLOBALS['STR_ADMIN_SITES_ECOTAX_MODULE'], 'devises' => $GLOBALS['STR_ADMIN_SITES_CURRENCIES_MODULE'],
			'paypal' => $GLOBALS['STR_ADMIN_SITES_PAYPAL_MODULE'], 'moneybookers' => $GLOBALS['STR_ADMIN_SITES_MONEYBOOKERS_MODULE'],
			'kekoli' => $GLOBALS['STR_ADMIN_SITES_KEKOLI_MODULE'], 'tag_cloud' => $GLOBALS['STR_ADMIN_SITES_TAG_CLOUD_MODULE'],
			'flash' => $GLOBALS['STR_ADMIN_SITES_FLASH_SALES_MODULE'], 'rss' => $GLOBALS['STR_ADMIN_SITES_RSS_MODULE'],
			'avis' => $GLOBALS['STR_ADMIN_SITES_OPINIONS_MODULE'], 'stock_advanced' => $GLOBALS['STR_ADMIN_SITES_STOCKS_MODULE'],
			'cart_preservation' => $GLOBALS['STR_ADMIN_SITES_CART_SAVE_MODULE'], 'affiliation' => $GLOBALS['STR_ADMIN_SITES_AFFILIATION_MODULE'],
			'lots' => $GLOBALS['STR_ADMIN_SITES_PRODUCT_LOTS_MODULE'], 'parrainage' => $GLOBALS['STR_ADMIN_SITES_SPONSOR_MODULE'],
			'url_rewriting' => $GLOBALS['STR_ADMIN_SITES_URL_REWRITING_MODULE'], 'micro_entreprise' => $GLOBALS['STR_ADMIN_SITES_MICROBUSINESS_MODULE'],
			'birthday' => $GLOBALS['STR_ADMIN_SITES_BIRTHDAY_MODULE'], 'faq' => $GLOBALS['STR_ADMIN_SITES_FAQ_MODULE'],
			'category_promotion' => $GLOBALS['STR_ADMIN_SITES_CATEGORIES_PROMOTION'], 'marques_promotion' => $GLOBALS['STR_ADMIN_SITES_TRADEMARK_PROMOTION'],
			'conditionnement' => $GLOBALS['STR_ADMIN_SITES_PRODUCT_CONDITIONING_MODULE'], 'friends_connect' => $GLOBALS['STR_ADMIN_SITES_GOOGLE_FRIENDS_CONNECT_MODULE'],
			'vacances' => $GLOBALS['STR_ADMIN_SITES_VACANCY_MODULE'], 'forum' => $GLOBALS['STR_ADMIN_SITES_FORUM_MODULE'],
			'gifts_list' => $GLOBALS['STR_ADMIN_SITES_GIFTS_LIST'], 'so_colissimo' => $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_MODULE'],
			'vatlayer' => $GLOBALS['STR_ADMIN_SITES_VATLAYER_MODULE'],
			);

	$modules_dir = $GLOBALS['dirroot'] . "/modules";
	if ($handle = opendir($modules_dir)) {
		while ($file = readdir($handle)) {
			if ($file != "." && $file != ".." && is_dir($modules_dir . '/' . $file)) {
				$GLOBALS['modules_on_disk'][$file] = $modules_dir . '/' . $file;
			}
		}
		closedir($handle);
	}
	foreach($GLOBALS['modules_on_disk'] as $this_module => $folder_path) {
		unset($file_path);
		if(!empty($GLOBALS['site_parameters']['modules_front_office_functions_files_array'][$this_module])) {
			$GLOBALS['modules_on_disk_infos'][$this_module]['installed'] = true;
			foreach(explode(',', $GLOBALS['site_parameters']['modules_front_office_functions_files_array'][$this_module]) as $this_file) {
				$file_path = $GLOBALS['dirroot'] . $this_file;
				if(StringMb::strpos($file_path, '.php') !== false && !in_array($this_module, array('url_rewriting')) && !in_array($this_file, vb($GLOBALS['modules_loaded_functions'], array())) && (empty($GLOBALS['site_parameters']['modules_no_library_load_array']) || !in_array($this_module, $GLOBALS['site_parameters']['modules_no_library_load_array']))) {
					// Fichier pas déjà chargé car module non activé => là on charge le fichier pour savoir si une classe est dedans
					include($file_path);
				}
			}
		} else {
			// Module pas installé et inconnu 
			foreach(array('fonctions.php', 'functions.php', StringMb::ucfirst($this_module) . '.php', 'functions/emails.php') as $this_filename) {
				if(!in_array(StringMb::strtolower(str_replace($GLOBALS['dirroot'], '', $folder_path . '/' . $this_filename)), array('/modules/fpdi/fpdi.php', '/modules/calc/calc.php', '/modules/crons/crons.php')) && file_exists($folder_path . '/' . $this_filename)) {
					// Fichier de classe ou de fonctions du module
					$file_path = $folder_path . '/' . $this_filename;
					if(!in_array(str_replace($GLOBALS['dirroot'], '', $file_path), vb($GLOBALS['site_parameters']['load_site_specific_files_before_others'], array())) && !in_array(str_replace($GLOBALS['dirroot'], '', $file_path), vb($GLOBALS['site_parameters']['load_site_specific_files_after_others'], array()))) {
						@include($file_path);
					}
					if($this_filename != StringMb::ucfirst($this_module) . '.php' || (class_exists(StringMb::ucfirst($this_module) && method_exists(StringMb::ucfirst($this_module), 'check_install')))) {
						// Soit il s'agit d'un fichier de fonctions, soit d'un fichier de classe
						// Mais par exemple modules/calc/calc.php qui n'est pas un fichier de classe ne doit pas être installé
						$GLOBALS['modules_on_disk_infos'][$this_module]['to_install'] = $file_path;
					}
					break;
				}
			}
		}
		if(empty($file_path)) {
			// Si rien de trouvé dans les fichiers standards, on cherche un fichier PHP quelconque pour connaître la version en regardant dans le code plus loin
			$file_path = $folder_path . '/';
			$temp = explode(',', $file_path);
			$file_path = $temp[0];
			foreach(array($file_path, $file_path . 'administrer', $file_path . 'admin') as $this_folder) {
				if(StringMb::strpos($this_folder, '.php') === false && file_exists($this_folder)) {
					if ($handle = opendir($this_folder)) {
						while ($file = readdir($handle)) {
							if ($file != "." && $file != ".." && StringMb::strpos($file, '.php') !== false) {
								$file_path = $this_folder . '/' . $file;
								break;
							}
						}
						closedir($handle);
					}
				}
			}
		}
		if(!empty($file_path)) {
			$GLOBALS['modules_on_disk_infos'][$this_module]['file_path'] = $file_path;
		}
	}
}

/**
 * Programmation de l'envoi de la newsletter
 *
 * @param integer $id
 * @param mixed $debut
 * @param mixed $limit
 * @param boolean $test
 * @param mixed $id_utilisateur
 * @param mixed $sql_select_users
 * @return
 */
function send_newsletter($id, $debut, $limit, $test = false, $id_utilisateur=null, $sql_select_users=null)
{
	$sql_n = "SELECT *
		FROM peel_newsletter
		WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('newsletter', null, true);
	$res_n = query($sql_n);
	$news_infos = fetch_assoc($res_n);

	$format = $news_infos['format'];
	// Récupération du technical_code du template associé à la newsletter
	$template_technical_code = $news_infos['template_technical_code'];
	// Stockage des messages et sujets, selon les langues disponibles sur le site
	foreach($GLOBALS['admin_lang_codes'] as $this_lang) {
		// Ajout des Custom template tag de la newsletter en fonction de la langue
		$custom_template_tags[$this_lang] = null;
		if (!empty($news_infos['product_ids'])) {
			if (function_exists('affiche_produits_newsletter')) {
				$custom_template_tags[$this_lang]['PRODUCT_LIST'] = affiche_produits_newsletter($news_infos['product_ids'], null, 'newsletter', '*', 'column', true, 0, 3);
			} else {
				$custom_template_tags[$this_lang]['PRODUCT_LIST'] = affiche_produits($news_infos['product_ids'], null, 'newsletter', 20, 'column', true, 0, 3);
			}
		}
		if (!empty($news_infos['message_' . $this_lang])) {
			// Récupération du template email associé à la newsletter en fonction des langues disponibles
			if (!empty($template_technical_code)) {
				// On a un modèle, qui contient un tag NEWSLETTER : on récupère son HTML, et on remplace [NEWSLETTER] par le texte de la newsletter
				$template_infos = getTextAndTitleFromEmailTemplateLang($template_technical_code, $this_lang);
				if (!empty($template_infos['image_haut'])) {
					$custom_template_tags[$this_lang]['BANNIERE_HAUT'] = $GLOBALS['repertoire_upload'].'/'.$template_infos['image_haut'];
				}
				if (!empty($template_infos['image_bas'])) {
					$custom_template_tags[$this_lang]['BANNIERE_BAS'] = $GLOBALS['repertoire_upload'].'/'.$template_infos['image_bas'];
				}
				$message[$this_lang] = $template_infos['text'];
				$custom_template_tags[$this_lang]['NEWSLETTER'] = $news_infos['message_' . $this_lang];
			} else {
				$message[$this_lang] = $news_infos['message_' . $this_lang];
			}
			// Le sujet de la newsletter est prioritaire sur celui du template
			$sujet[$this_lang] = $news_infos['sujet_' . $this_lang];
		}
	}

	// Récupération de la liste des emails
	if (!empty($message)) {
		foreach(array_keys($message) as $this_lang) {
			if (!empty($sql_select_users) && check_if_module_active('crons')) {
				// uniquement dans le cas d'une programmation par cron, on peux prendre en compte le SQL en paramètre. Dans le cas où cette fonction est exécutée sans cron, il faudra gérer le paramètrage de LIMIT dans la requête pour prendre en compte le paramètre sql_select_users.
				$sql_u = $sql_select_users;
			} else {
				$sql_cond = "etat='1' AND email_bounce NOT LIKE '5.%' AND email!='' AND " . get_filter_site_cond('utilisateurs', 'u') . " AND ";
				if (!$test) {
					$sql_cond .= "newsletter='1' AND (lang='" . nohtml_real_escape_string($this_lang) . "' OR lang='')";
					if(!empty($GLOBALS['site_parameters']['newsletter_and_commercial_double_optin_validation'])) {
						$sql_cond .= " AND newsletter_validation_date NOT LIKE '0000-00-00%'";
					}
				} else {
					$sql_cond .= "priv LIKE '%admin%'";
					$sujet[$this_lang] .= ' [envoyé aux administrateurs seulement]';
				}
				$sql_u = "SELECT *
					FROM peel_utilisateurs u
					WHERE  " . $sql_cond . " " . (!empty($id_utilisateur)? " AND id_utilisateur = " . intval($id_utilisateur).' ':'');	
			}
			// Le SQL suivant va permettre de récupérer des données utilisateurs pouvant servir dans des TAGS
			// => il faut mettre tous les champs de la table utilisateurs
			if (check_if_module_active('crons')) {
				// Envoi de la newsletter dans la langue définie par l'utilisateur lors de son inscription ou modification de ces paramètres
				// Les emails seront envoyés a posteriori avec un cron
				// Si nous avons des tags à remplacer dans le contenue
				$message[$this_lang] = template_tags_replace($message[$this_lang], $custom_template_tags[$this_lang], true, null, $this_lang);
				program_cron_email($sql_u, $message[$this_lang], $sujet[$this_lang], $_SESSION['session_utilisateur']['email'], null, $this_lang);
				query("UPDATE peel_newsletter
					SET statut='envoi ok', date_envoi='" . date('Y-m-d H:i:s', time()) . "'
					WHERE id='" . intval($news_infos['id']) . "' AND " . get_filter_site_cond('newsletter', null, true));
				$newsletter_name_info = $id . ' (' . $this_lang . ') "' . $sujet[$this_lang] . '"';
				if (!$test) {
					$output = $GLOBALS['STR_ADMIN_NEWSLETTERS_MSG_SEND_SUBSCRIBERS'];
				} else {
					$output = $GLOBALS['STR_ADMIN_NEWSLETTERS_MSG_SEND_ADMINISTRATORS'];
				}
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($output, $newsletter_name_info)))->fetch();
			} else {
				// On envoi la newsletter, il faut envoyer l'email directement et pas une notification. Cette variable global sera utilisée dans la fonction send_email.
				$GLOBALS['send_notification_disable'] = true;
				$sql_u .= "
					LIMIT " . intval($debut) . "," . intval($limit);
				$res_u = query($sql_u);
				// Envoi de la newsletter dans la langue définie par l'utilisateur lors de son inscription ou modification de ces paramètres
				$i = 0;
				while ($row = fetch_assoc($res_u)) {
					if (send_email($row['email'], $sujet[$this_lang], $message[$this_lang], '', $custom_template_tags[$this_lang], $format, $GLOBALS['support'])) {
						$result = 'OK';
					} else {
						$result = 'NOK';
					}
					if (!$test) {
						$fc = StringMb::fopen_utf8("sending.log", "ab");
						$w = fwrite ($fc, "[" . $row['email'] . "]\t\t\t " . $result . "\n");
						fclose($fc);
					}
					$i++;
				}

				if ($i >= $limit && $debut + $i < 250) {
					// Si le nombre de personne a qui la newsletter vient d'être envoyé ($i) est supérieur ou égale au nombre $limit, ça veut dire qu'il y a encore des utilisateurs qui doivent recevoir la newsletter
					// => On continue à envoyer la newsletter
					sleep(1);
					send_newsletter($id, $debut + $i, min($limit, 250 - ($debut + $i)), $test, $id_utilisateur);
				} else {
					query("UPDATE peel_newsletter
						SET statut='envoi ok', date_envoi='" . date('Y-m-d H:i:s', time()) . "'
						WHERE id='" . intval($news_infos['id']) . "' AND " . get_filter_site_cond('newsletter', null, true));
					echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_NEWSLETTERS_MSG_SENT_OK'], $id, $sujet[$this_lang], $debut + $i)))->fetch();
				}
			}
		}
	}
}

/**
 *
 * Liste les founisseurs
 *
 * @return
*/
function get_supplier_output()
{
	$output = array();
	$query = query("SELECT id_utilisateur, societe, priv, prenom, nom_famille
		FROM peel_utilisateurs
		WHERE priv = 'supplier' AND " . get_filter_site_cond('utilisateurs') . "
		ORDER BY societe ASC");
	while($result = fetch_assoc($query)) {
		$output[] = array("id_utilisateur" => $result['id_utilisateur'], 'societe' => $result['societe'], 'priv' => $result['priv'], 'prenom' => $result['prenom'], 'nom_famille' => $result['nom_famille']);
	}
	return $output;
}

/**
 * Récupère les informations d'initialisation du formulaire d'import ou d'export
 *
 * @param string $import_or_export
 * @param array $params
 * @return
*/
function import_export_init($import_or_export, &$params) {
	$output = '';
	$GLOBALS['database_fields_default_by_type_array'] = array();
	
	if(!isset($params['type'])) {
		if(!empty($_POST['type'])) {
			$params['type'] = $_POST['type'];
		} elseif(!empty($_GET['type'])) {
			$params['type'] = $_GET['type'];
		} else {
			$params['type'] = '';
		}
	}
	
	if($import_or_export == 'import') {
		// Récupération des valeurs par défaut des colonnes (input sur chaque ligne de correspondance), qui permet d'éviter d'avoir besoin d'associer avec une colonne du fichier importé
		foreach($_POST as $this_key => $this_value) {
			if(strpos($this_key, 'default_') === 0) {
				$params['defaults'][$this_key] = $this_value;
			}
		}
	} else {
		foreach(array('page_bottom', 'report_header', 'report_footer', 'data_details', 'data_subtotals', 'data_total') as $this_key) {
			if(isset($_POST[$this_key])) {
				$params[$this_key] = $_POST[$this_key];
			}
		}
	}
	
	if(!isset($GLOBALS['database_import_export_table_by_type_array'])) {
		$GLOBALS['database_import_export_table_by_type_array'] = array();
	}
	
	// On a maintenant une liste générique par défaut dans PEEL, et on complète cette liste via un hook
	$params['import_or_export'] = $import_or_export;
	// il faut laisser l'appel du hook à cet endroit car dans le hook sont défini des variables globales qui servent à générer du javascript
	$hook_result = call_module_hook('import_export_init', $params, 'array');
	// Création si nécessaire de la table peel_import_field (nécessaire sur architectures particulières, pas dans le cas général)

	if(empty($hook_result['cancel_default_types'])) {
		// Liste des tables à importer ou exporter
		$GLOBALS['database_user_rights_by_type_array']['peel_utilisateurs'] = 'admin_users,admin_webmastering';
		$GLOBALS['database_user_rights_by_type_array']['peel_produits'] = 'admin_products,admin_sales,admin_webmastering';

		$GLOBALS['database_import_export_type_names_array'] = array('peel_utilisateurs' => 'Utilisateurs', 'peel_produits' => 'Produits');

		$GLOBALS['database_field_titles_by_table_array']['peel_utilisateurs'] = array('email' => 'Email', 'nom_famille' => 'Nom', 'prenom' => 'Prénom', 'societe' => 'Société', 'adresse' => 'Adresse', 'code_postal' => 'Code postal', 'ville' => 'Ville', 'telephone' => 'Téléphone');

		// La colonne stock dans peel_produits ne sert pas, donc l'exporter induit en confusion
		$GLOBALS['database_field_excluded_by_type_array']['peel_produits'] = array('stock');

		// Configuration de l'ajout de colonnes
		if($import_or_export == 'import') {
			$GLOBALS['database_field_additional_by_type_array']['peel_produits'] = array('Sizes' => $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'], 'Colors' => $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'], 'Category' => $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY'], 'Stock' => 'Stock', 'Categorie' => 'Categorie', 'categorie_id' => 'categorie_id');
		} else {
			$GLOBALS['database_field_additional_by_type_array']['peel_produits'] = array('Listed_price_including_vat' => $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_INCLUDING_VAT'], 'Listed_price_excluding_vat' => $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_EXCLUDING_VAT'], 'Sizes' => $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'], 'Colors' => $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'], 'Brand' => $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_BRAND'], 'Associated_products' => $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS'], 'Category' => $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY'], 'Stock' => 'Stock', 'Categorie' => 'Categorie', 'categorie_id' => 'categorie_id');
		}
		
		if($import_or_export == 'export') {
			// Définition de types spécifiques
			$GLOBALS['database_user_rights_by_type_array']['livraisons'] = 'admin_sales,admin_webmastering';
			$GLOBALS['database_user_rights_by_type_array']['ventes'] = 'admin_sales,admin_webmastering';
			$GLOBALS['database_import_export_type_names_array']['livraisons'] = 'Livraisons';
			$GLOBALS['database_import_export_type_names_array']['ventes'] = 'Ventes';

			$GLOBALS['database_footer_by_type_array']['ventes'] = true;

			$GLOBALS['database_import_export_table_by_type_array']['livraisons'] = 'peel_commandes';
			$GLOBALS['database_mode_by_type_array']['livraisons'] = 'virtual';
			$GLOBALS['database_field_titles_by_type_array']['livraisons'] = array('nom_ship' => 'Nom', 'prenom_ship' => 'Prénom', 'societe_ship' => 'Société', 'adresse_ship' => 'Adresse', 'zip_ship' => 'Code postal', 'ville_ship' => 'Ville', 'commentaires' => 'Etages', 'pays_ship' => 'Pays', 'poids_calc' => 'Poids', 'article_calc' => 'Article', 'quantite' => 'Quantité', 'transport' => 'Transport', 'id' => 'Commande', 'o_timestamp' => 'Date');

			$GLOBALS['database_import_export_table_by_type_array']['one_line_per_product'] = 'peel_commandes_articles';
			$GLOBALS['database_import_export_type_names_array']['one_line_per_product'] = 'Ventes avec une ligne par produit';
			$GLOBALS['database_mode_by_type_array']['one_line_per_product'] = 'virtual';
			$GLOBALS['database_user_rights_by_type_array']['one_line_per_product'] = 'admin_products,admin_sales,admin_webmastering';
			$GLOBALS['database_field_titles_by_type_array']['one_line_per_product'] = array('product_name' => 'Produit', 'quantity_by_product' => 'Quantite'); // Mode simplifié : une ligne par produit commandé

			$GLOBALS['database_import_export_table_by_type_array']['one_line_per_order'] = 'peel_commandes';
			$GLOBALS['database_import_export_type_names_array']['one_line_per_order'] = 'Ventes avec une ligne par commande';
			$GLOBALS['database_mode_by_type_array']['one_line_per_order'] = 'virtual';
			$GLOBALS['database_user_rights_by_type_array']['one_line_per_order'] = 'admin_products,admin_sales,admin_webmastering';
			$GLOBALS['database_field_titles_by_type_array']['one_line_per_order'] = array('id' => 'Numéro commande', 'numero' => 'Numéro de facture', 'o_timestamp' => 'Date de vente', 'nom_bill' => 'Nom de l\'acheteur', 'societe_bill' => 'Société', 'adresse_bill' => 'Adresse', 'ville_bill' => 'Ville', 'zip_bill' => 'Code postal', 'pays_bill' => 'Pays', 'montant_ht' => 'Total HT', 'tva_percent' => 'Taux TVA', 'montant_avec_avoir' => 'Total TTC', 'avoir' => 'Avoir client', 'montant' => 'Net à payer', 'cout_transport_ht' => 'Frais port HT', 'tva_cout_transport' => 'TVA Frais de port', 'cout_transport' => 'Frais port TTC', 'tarif_paiement_ht' => 'Tarif paiement HT', 'tva_tarif_paiement' => 'TVA Tarif paiement', 'tarif_paiement' => 'Tarif paiement', 'paiement' => 'Mode de paiement', 'total_produit_ht' => 'Total HT des produits', 'tva_total_produit' => 'TVA des produits', 'total_produit' => 'Total des produits');

			$GLOBALS['database_import_export_table_by_type_array']['ventes'] = 'peel_commandes';
			$GLOBALS['database_mode_by_type_array']['ventes'] = 'virtual';
			$GLOBALS['database_field_titles_by_type_array']['ventes'] = array('id' => 'Numéro commande', 'numero' => 'Numéro de facture', 'o_timestamp' => 'Date de vente', 'nom_bill' => 'Nom de l\'acheteur', 'societe_bill' => 'Société', 'adresse_bill' => 'Adresse', 'ville_bill' => 'Ville', 'zip_bill' => 'Code postal', 'pays_bill' => 'Pays', 'nom_produit' => 'Article', 'quantite' => 'Quantité', 'prix_ht' => 'Prix unitaire HT', 'montant_ht' => 'Total HT', 'tva_percent' => 'Taux TVA', 'total_tva' => 'TVA', 'montant_avec_avoir' => 'Total TTC', 'cout_transport_ht' => 'Frais port HT', 'tva_cout_transport' => 'TVA Frais de port', 'cout_transport' => 'Frais port TTC', 'tarif_paiement' => 'Tarif paiement HT', 'tva_tarif_paiement' => 'TVA Tarif paiement', 'tarif_paiement_ht' => 'Tarif paiement', 'paiement' => 'Mode de paiement');

			$GLOBALS['database_import_export_table_by_type_array']['ventes_chronopost'] = 'peel_commandes';
			$GLOBALS['database_import_export_type_names_array']['ventes_chronopost'] = 'Ventes au format Chronopost';
			$GLOBALS['database_mode_by_type_array']['ventes_chronopost'] = 'virtual';
			$GLOBALS['database_display_forced_header_in_export_by_type_array']['ventes_chronopost'] = 0;
			$GLOBALS['database_display_forced_separator_in_export_by_type_array']['ventes_chronopost'] = ';';
			$GLOBALS['database_user_rights_by_type_array']['ventes_chronopost'] = 'admin_products,admin_sales,admin_webmastering';
			$GLOBALS['database_field_titles_by_type_array']['ventes_chronopost'] = array('unused1', 'societe', 'nom', 'prenom', 'adresse', 'unused2', 'unused3', 'unused4', 'zip', 'ville', 'pays', 'telephone', 'email', 'id', 'unused5', 'unused6', 'export_default_product', 'contract_number', 'sub_account_contract_number', 'unused7', 'unused8', 'M', 'unused9', 'unused10', 'unused11', 'total_poids', 'unused12', 'unused13', 'unused14', 'unused15', 'unused16', 'delivery_date', 'unused17', 'unused18', 'unused19', 'unused20', 'unused21', 'unused22', 'unused23', 'unused24');

			if(!empty($GLOBALS['site_parameters']['cegid_order_export'])) {
				$GLOBALS['database_import_export_table_by_type_array']['ventes_cegid'] = 'peel_commandes';
				$GLOBALS['database_import_export_type_names_array']['ventes_cegid'] = 'Ventes au format Cegid';
				$GLOBALS['database_mode_by_type_array']['ventes_cegid'] = 'virtual';
				$GLOBALS['database_display_forced_separator_in_export_by_type_array']['ventes_cegid'] = ';';
				$GLOBALS['database_user_rights_by_type_array']['ventes_cegid'] = 'admin_products,admin_sales,admin_webmastering';
				$GLOBALS['database_field_titles_by_type_array']['ventes_cegid'] = array('journal' => 'Journal', 'date' => 'Date', 'general' => 'Général', 'auxiliaire' => 'Auxiliaire', 'reference' => 'Référence', 'libelle' => 'Libellé', 'credit' => 'Crédit', 'debit' => 'Débit');
			}
			$GLOBALS['database_import_export_table_by_type_array']['ventes_clients_par_produit'] = 'peel_commandes|peel_utilisateurs';
			$GLOBALS['database_import_export_type_names_array']['ventes_clients_par_produit'] = 'Clients avec produits achetés';
			$GLOBALS['database_mode_by_type_array']['ventes_clients_par_produit'] = 'virtual';
			$GLOBALS['database_user_rights_by_type_array']['ventes_clients_par_produit'] = 'admin_products,admin_sales,admin_webmastering';
			$GLOBALS['database_field_titles_by_type_array']['ventes_clients_par_produit'] = array('nom_famille' => $GLOBALS['STR_LAST_NAME'], 'prenom' => $GLOBALS['STR_FIRST_NAME'], 'adresse' => $GLOBALS['STR_ADDRESS'], 'ville' => $GLOBALS['STR_TOWN'], 'email' => $GLOBALS['STR_EMAIL'], 'telephone' => $GLOBALS['STR_TELEPHONE']);

			$GLOBALS['database_import_export_table_by_type_array']['formatted_produits'] = 'peel_commandes|peel_produits';
			$GLOBALS['database_import_export_type_names_array']['formatted_produits'] = 'Produits avec informations formattées';
			$GLOBALS['database_mode_by_type_array']['formatted_produits'] = 'virtual';
			$GLOBALS['database_user_rights_by_type_array']['formatted_produits'] = 'admin_products,admin_sales,admin_webmastering';
			$GLOBALS['database_field_titles_by_type_array']['formatted_produits'] = array('nom' => $GLOBALS["STR_PRODUCT_NAME"], 'descriptif' => $GLOBALS["STR_ADMIN_SHORT_DESCRIPTION"], 'formatted_prix' => $GLOBALS["STR_PDF_PRIX_HT"], 'image1' => $GLOBALS["STR_IMAGE"], 'formatted_colors' => $GLOBALS["STR_ADMIN_EXPORT_PRODUCTS_COLORS"], 'formatted_sizes' => $GLOBALS["STR_ADMIN_EXPORT_PRODUCTS_SIZES"]);
		}
	}

	if($import_or_export == 'export') {
		// Gestion historiquement spécifique de la table produits
		$hook_result = call_module_hook('export_products_get_configuration_array', array(), 'array');
		$GLOBALS['database_field_additional_by_type_array']['peel_produits'] = array_merge_recursive_distinct(vb($GLOBALS['database_field_additional_by_type_array']['peel_produits'], array()), vb($hook_result['product_field_names'], array()));
	}
	
	// Hook générique
	$hook_result = call_module_hook('import_export_get_configuration_array', array('import_or_export' => $import_or_export), 'array');
	$GLOBALS['database_field_additional_by_type_array'] = array_merge_recursive_distinct(vb($GLOBALS['database_field_additional_by_type_array'], array()), vb($hook_result['field_names_by_table_array'], array()));

	// Configuration des droits pour pouvoir procéder à l'import ou à l'export
	foreach(array_keys($GLOBALS['database_import_export_type_names_array']) as $this_type) {
		if(!empty($GLOBALS['database_user_rights_by_type_array'][$this_type]) && !a_priv($GLOBALS['database_user_rights_by_type_array'][$this_type])) {
			// On ne garde que les types d'export auxquels on a le droit d'accès pour toutes les tables concernées par le $type
			unset($GLOBALS['database_import_export_type_names_array'][$this_type]);
			continue;
		}
		// On complète $GLOBALS['database_field_titles_by_type_array']
		foreach (vb($GLOBALS['database_field_titles_by_type_array'][$this_type], array()) as $this_field => $this_title) {
			// On corrige le format si on a mis des titres sans nom de champ : on passe au format nom => titre
			if(is_numeric($this_field)) {
				unset($GLOBALS['database_field_titles_by_type_array'][$this_type][$this_field]);
				$GLOBALS['database_field_titles_by_type_array'][$this_type][$this_title] = $this_title;
			}
		}
		if(!empty($GLOBALS['database_fields_by_type_array'][$this_type])) {
			if(!is_array($GLOBALS['database_fields_by_type_array'][$this_type])) {
				$GLOBALS['database_fields_by_type_array'][$this_type] = get_array_from_string($GLOBALS['database_fields_by_type_array'][$this_type]);
			}
		}
		if(empty($GLOBALS['database_import_export_table_by_type_array']) || empty($GLOBALS['database_import_export_table_by_type_array'][$this_type])) {
			// La table n'est pas définie pour un type non virtuel, donc on considère que le type est le nom de la table
			$GLOBALS['database_import_export_table_by_type_array'][$this_type] = $this_type;
		} 
		if(empty($GLOBALS['database_field_infos_by_type_array'][$this_type])) {
			$GLOBALS['database_field_infos_by_type_array'][$this_type] = array();
		}
		if(empty($GLOBALS['database_mode_by_type_array'][$this_type]) || $GLOBALS['database_mode_by_type_array'][$this_type] != 'virtual') {
			// ICI : le type correspond à 1 ou plusieurs tables réelles
			// Si on veut un type avec un nom complètement différent d'une table, ou avoir un lien vers plusieurs tables table1|table2, il faut une correspondance entre type et table(s)
			// NB : Le type sert notamment pour des noms en javascript, donc il ne doit pas contenir |
			// Les tables sont dans $GLOBALS['database_import_export_table_by_type_array'][$this_type];
			if(empty($GLOBALS['database_fields_by_type_array'][$this_type])) {
				// Si on n'a pas défini explicitement la liste des champs correspondant à un type donné : on génère la liste à partir de la structure de la table (ou des tables) correspondant au type
				$GLOBALS['database_fields_by_type_array'][$this_type] = array();
				foreach(explode('|', $GLOBALS['database_import_export_table_by_type_array'][$this_type]) as $this_table) {
					if(function_exists('get_ordered_table_fields')) {
						// On récupère les noms des champs de la table avec les informations, et les champs joints
						$field_infos_array = get_ordered_table_fields($this_table, $import_or_export, 'simple', false, false);
						// On ne trie pas ces champs //sort($database_field_names);
						$GLOBALS['database_fields_by_type_array'][$this_type] = array_merge_recursive_distinct($GLOBALS['database_fields_by_type_array'][$this_type], array_keys($field_infos_array));
						$GLOBALS['database_field_infos_by_type_array'][$this_type] = array_merge_recursive_distinct($GLOBALS['database_field_infos_by_type_array'][$this_type], $field_infos_array);
					} else {
						// Sans la gestion automatique des jointures (get_ordered_table_fields), on fait plus basiquement
						$database_field_names = get_table_field_names($this_table);
						$GLOBALS['database_fields_by_type_array'][$this_type] = array_merge_recursive_distinct($GLOBALS['database_fields_by_type_array'][$this_type], $database_field_names);
						$GLOBALS['database_field_infos_by_type_array'][$this_type] = array_merge_recursive_distinct($GLOBALS['database_field_infos_by_type_array'][$this_type], get_table_field_types($this_table));
					}	
				}
			}
		} else {
			// Type correspondant à une gestion spécifique, et pas à une table de BDD
			if(empty($GLOBALS['database_fields_by_type_array'][$this_type]) && !empty($GLOBALS['database_field_titles_by_type_array'][$this_type])) {
				$GLOBALS['database_fields_by_type_array'][$this_type] = array_keys($GLOBALS['database_field_titles_by_type_array'][$this_type]);
			}
		}
		if(!empty($GLOBALS['database_fields_by_type_array'][$this_type])) {
			// On retire les colonnes non désirées
			foreach($GLOBALS['database_fields_by_type_array'][$this_type] as $this_key => $this_field) {
				if ((function_exists('get_field_skipped') && get_field_skipped($this_field, $this_type, $import_or_export)) || in_array($this_field, vb($GLOBALS['database_field_excluded_by_type_array'][$this_type], array()))) {
					unset($GLOBALS['database_fields_by_type_array'][$this_type][$this_key]);
				}	
			}
		}
		// On rajoute ensuite des colonnes additionnelles (calculées ou générées spécifiquement en PHP)
		foreach (vb($GLOBALS['database_field_additional_by_type_array'][$this_type], array()) as $this_field => $this_title) {
			if(is_numeric($this_field)) {
				// On n'a pas le couple champ => titre, mais le nom du champ
				$this_field = $this_title;
				$this_title = get_field_title($this_field, $this_type);
			}
			$GLOBALS['database_fields_by_type_array'][$this_type][$this_field] = $this_title;
		}
		if(empty($GLOBALS['database_fields_default_by_type_array'][$this_type])) {
			// Permet l'export sans interface : on exporte toutes les colonnes
			$GLOBALS['database_fields_default_by_type_array'][$this_type] = array(); // vb($GLOBALS['database_fields_by_type_array'][$this_type]);
		} elseif(!is_array($GLOBALS['database_fields_default_by_type_array'][$this_type])) {
			$GLOBALS['database_fields_default_by_type_array'][$this_type] = get_array_from_string($GLOBALS['database_fields_default_by_type_array'][$this_type]);
		}
	}
	// Filtre pour l'export des commandes pour certains types d'exports qui peuvent être virtuel
	if (!empty($_POST['an1'])) {
		$frm = $_POST;
	} elseif(!empty($_GET['an1'])) {
		$frm = $_GET;
	} else {
		$frm = null;
	}
	if (!empty($frm)) {
		if (!empty($frm['dateadded1'])) {
			$params['dateadded1'] = $frm['dateadded1'];
			$params['dateadded2'] = $frm['dateadded2'];
			$params['date_field'] = $frm['date_field'];
		} else {
			$check_admin_date_data = check_admin_date_data($frm);
			if (empty($check_admin_date_data)) {
				$params['dateadded1'] = $frm['an1'] . '-' . str_pad($frm['mois1'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($frm['jour1'], 2, 0, STR_PAD_LEFT) . " 00:00:00";
				$params['dateadded2'] = $frm['an2'] . '-' . str_pad($frm['mois2'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($frm['jour2'], 2, 0, STR_PAD_LEFT) . " 23:59:59";
				$params['date_field'] = $frm['order_date_field_filter'];
			} else {
				$output .= $check_admin_date_data;
			}
		}
		$params['id_statut_paiement'] = vn($frm['statut']);
	}
	if(!empty($_POST['import_file']) && file_exists($GLOBALS['uploaddir'] . '/' . $_POST['import_file'])) {
		$params['import_file'] = $_POST['import_file'];
	}
	if (isset($GLOBALS['database_display_forced_header_in_export_by_type_array'][vb($params['type'])])) {
		// On utilise en priorité la configuration pour le type demandé, si cette configuration existe
		$params['header'] = $GLOBALS['database_display_forced_header_in_export_by_type_array'][vb($params['type'])];
	} else {
		if(!empty($_POST['header'])) {
			$params['header'] = $_POST['header'];
		} elseif(!isset($params['header'])) {
			$params['header'] = true;
		}
	}
	if(!empty($_POST['footer'])) {
		$params['footer'] = $_POST['footer'];
	} elseif(!isset($params['footer'])) {
		$params['footer'] = true;
	}
	if(!empty($_POST['mode'])) {
		// ne pas utiliser GET['mode'], qui est utilisé par data.php du module temps.
		$params['mode'] = $_POST['mode'];
	} elseif(empty($params['mode'])) {
		$params['mode'] = '';
	}
	if (!empty($_POST['data_encoding'])) {
		$params['data_encoding'] = $_POST['data_encoding'];
	} elseif (!empty($_GET['data_encoding'])) {
		$params['data_encoding'] = $_GET['data_encoding'];
	} 
	if (empty($params['data_encoding'])) {
		if($import_or_export == 'import') {
			// Import avec encodage en mode automatique : 
			// on va tout faire fonctionner en utf-8 et assurer compatibilité avec fichier iso
			$params['data_encoding'] = '';
		} elseif (!empty($GLOBALS['site_parameters']['export_encoding'])) {
			$params['data_encoding'] = $GLOBALS['site_parameters']['export_encoding'];
		} else {
			$params['data_encoding'] = 'utf-8';
		}
	}
	if (!empty($GLOBALS['database_display_forced_separator_in_export_by_type_array'][vb($params['type'])])) {
		$params['separator'] = $GLOBALS['database_display_forced_separator_in_export_by_type_array'][vb($params['type'])];
	} elseif(!empty($_POST['separator'])) {
		// Priorité aux données du formulaire envoyé
		$params['separator'] = vb($_POST['separator']);
	} elseif(!empty($_GET['separator'])) {
		$params['separator'] = vb($_GET['separator']);
	} elseif($import_or_export == 'import') {
		// On autorise '' pour l'import pour la détection automatique du séparateur
		$params['separator'] = '';
	} else {
		// Dans les divers cas d'export : Tabulation par défaut
		$params['separator'] = "\t";
	} 
	if (vb($params['separator']) == '\t') {
		$params['separator'] = "\t";
	}
	if(!empty($_POST['correspondance'])) {
		$params['correspondance'] = $_POST['correspondance'];
	}
	if(!empty($params['correspondance'])) {
		// Si on avait passé en paramètre les associations colonnes / champs, c'est moins prioritaire que la correspondance définie par l'utilisateur
		$params['ordered_fields_selected'] = array(); 
		// Sélection des colonnes souhaitées par l'utilisateur
		// => Décodage du champ "correspondance" créé en javascript
		//    $params['ordered_fields_selected'] est créé au format : champ de bdd => titre de colonne du fichier uploadé
		foreach(explode('&', $params['correspondance']) as $val) {
			if(!empty($val)) {
				if($import_or_export == 'import') {
					// ordered_fields_selected va contenir la correspondance entre champs en BDD et colonnes du fichier
					$this_corresp = explode('=', $val);
					$params['ordered_fields_selected'][$this_corresp[0]] = StringMb::rawurldecode(vb($this_corresp[1]));
				} else {
					// ordered_fields_selected est fait pour contenir la liste des champs à exporter dans l'ordre
					$params['ordered_fields_selected'][] = $val;
				}
			}
		}
	}
	if($import_or_export == 'import') {
		// Import 
		if(!empty($params['type']) && !empty($GLOBALS['database_fields_by_type_array'][$params['type']])) {
			// Pour l'import spécifiquement, on gère les valeurs par défaut des champs (des inputs mis sur chaque ligne)
			foreach($GLOBALS['database_fields_by_type_array'][$params['type']] as $this_field) {
				if(empty($params['ordered_fields_selected'][$this_field]) && isset($params['defaults']['default_'.$params['type'].'_'.$this_field]) && $params['defaults']['default_'.$params['type'].'_'.$this_field] !== '') {
					// On ajoute cette colonne pour l'import
					$params['ordered_fields_selected'][$this_field] = true;
				}
			}
		}
	} else {
		// Export et rapports
	}
	
	// Gestion du CSS et JS
	$this_js_content = '';
	
	if($import_or_export != 'report') {
		// $import_or_export == 'report' est exclu, et correspond aux tableaux croisés dynamiques
		// Les autres cas sont import ou global_report (pour rapports HTML) ou export_xxxx

		// CSS spécifique (en dehors de admin.css volontairement pour compatibilité avec cas particuliers appelant l'import / export en front-office)
		$GLOBALS['header_css_output_array'][] = '
.contains_draggable tr td {
	height: 200px;
	width: 200px;
	padding: 5px;
	border: 1px solid black;
}
.fields_table tr td {
	height: 32px;
	padding: 2px;
	border: 1px solid black;
}
.fields_table .form-control {
	height: 26px;
}
.field_draggable {
	display:block;
	color: #ffffff;
	background-color: #009900;
	margin: 3px;
	padding-left: 3px;
	border: 1px outset #999999;
}';
		// JQuery pour gestion du Drag and drop
		$contains_draggable_js = ($import_or_export == 'import'?'.contains_draggable':'#"+div_id+" .contains_draggable');
		$this_js_content .= '
window.table_by_type_array = $.parseJSON(\''.filtre_javascript(json_encode($GLOBALS['database_import_export_table_by_type_array']), true, true, false, true, false).'\');
window.drag_and_drop_fields = function(contains_draggable, draggable, droppable) {';
	if ($import_or_export != 'import') {
		// suppression de la possibilité de double cliquer sur l'élément pour le placer dans la colonne de droite sur le formulaire d'import
		$this_js_content .= '
    $(contains_draggable).on("dblclick", draggable, function (event) {
		  $(this).detach().appendTo($(droppable));	
		});';
	}
	$this_js_content .= '
	$(droppable).on("dblclick", draggable, function (event) {
		  $(this).detach().appendTo($(contains_draggable));	
		});
	$(draggable).draggable({
		stack: "span",
		start: function( event ) { $(this).css("backgroundColor", "#dd7700"); }
		});
	$(contains_draggable+", "+droppable).droppable({
		activate: function( event, ui ) {},
		dragenter: function( event, ui ) { event.preventDefault(); },
		dragover: function( event, ui ) { event.preventDefault(); },
		drop: function( event, ui ) {	
			event.preventDefault();
			drop_pos = ui.offset.top - $(this).offset().top;
			var id_dropped = ui.draggable;
			if(ui.draggable && ui.draggable.length > 0) {
				' . ($import_or_export == 'import'?'
				$(this).children(draggable).detach().appendTo($(contains_draggable));
				ui.draggable.detach().appendTo($(this));
				':'   
				last_pos = 0;
				$(this).children(draggable).each(function() {
					if(drop_pos && ui.draggable.attr("id")!=$(this).attr("id")) {
						if(last_pos==0) { parent_pos = $(this).position().top; }
						var pos = $(this).position().top;
						if(drop_pos<=0 || (drop_pos<pos-parent_pos && drop_pos>last_pos-parent_pos)) { ui.draggable.detach().insertBefore($(this)); drop_pos=0; return false; }
						last_pos = pos;
					}
				});
				if(drop_pos) {
					ui.draggable.detach().appendTo($(this));
				}
				') . '
				ui.draggable.css("top", 0).css("left", 0); 
				ui.draggable.css("backgroundColor", "#009900");
			}
		}
   });
}

window.move_draggable_fields = function(origin, destination, mode="basic") {
	if(mode=="basic") { 
		$(origin+" .field_draggable").detach().appendTo($(destination)); 
	} else {
		// Association si nom de champ correspond
		$(origin+" .field_draggable").each(function( index, element_to_move ) {
			cell = $(mode+$.escapeSelector("_"+$(this).attr("id").replace("filecol_","")));
			if(cell.length) {
				$(this).detach().appendTo(cell);
			} else {
				$(destination).each(function( index_dest, element_dest ) {
					if($(element_dest).prev().prev().text().replace(" *","") == $(element_to_move).text()) {
						$(element_to_move).detach().appendTo($(element_dest));
						return true;
					}
				});
			}
		});
	}
}
';
		if($import_or_export == 'import') {
			// Affiche la div de correspondance des champs en fonction du type d\'import sélectionné
			// NB : correspondance_type est un champ hidden utilisé pour gérer quand on revient en arrière dans le formulaire, pour se souvenir du type utilisé pour une correspondance donnée sans que le changement à l'instant t du type ne nous gêne
			$this_js_content .= '
window.imported_file = null;
window.change_import_type = function() {
	import_type = $("#import_export_type").val();
	data_encoding = $("select[name=data_encoding]").val();
	$(".div_hidden_by_default").hide();
	$(".div_hidden_by_default input").prop("disabled", true);
	$(".container_drop_draggable").empty();
	$(".field_draggable").remove();';
			if (!empty($_POST['correspondance'])) {
				$this_js_content .= '
	$("#correspondance").val("'.$_POST['correspondance'].'");';
			}
			$this_js_content .= '
	$.ajax({
		url: \'' . get_current_url() . '\',
		dataType : \'json\',
		type : \'POST\',
		data : {
			import_export_type : import_type,
			mode: \'get_type_infos\',
			ajax: true
		},
		success: function( data ) {
			if(data["select_rules"]) $("#load_rule").remove(); $("#load_rule_container").append(data["select_rules"]);
		}
	});
	if(window.imported_file != $("input[name=import_file]").val()) {
		window.imported_file = $("input[name=import_file]").val();
		if(window.imported_file) { 
			if(window.imported_file.substr(0,1)!="/") {
				file="' . $GLOBALS['repertoire_upload'] . '/' . '"+window.imported_file;
			} else {
				file="' . $GLOBALS['wwwroot'] . '"+window.imported_file;
			}';
			if (!empty($GLOBALS['site_parameters']['iso_encoded_url_file_for_ajax'])) {
				// on demande au serveur de donner le fichier en précisant l'encodage
				// iso_encoded_url_file_for_ajax utilise l'information d'encodage demandé par l'utilisateur : data_encoding (qui peut être vide pour détection automatique)
				$this_js_content .= '
				if(data_encoding!="utf-8") {
					file = ' . $GLOBALS['site_parameters']['iso_encoded_url_file_for_ajax'] . '
				}';
			}
			$this_js_content .= '			
			$.ajax({
				url : file,
				type : "get",
				async: false,
				success : function(data) {
					if(data.length) {
						var lines = data.split("\r\n").join("\n").split("\r").join("\n").split("\n");
						var separator = $("#separator").val();
						if (separator == "") {
							if (lines[0].split("\t").length > 1) {
								window.cols_array = lines[0].split("\t");
							} else if (lines[0].split(";").length > 1) {
								window.cols_array = lines[0].split(";");
							} else if (lines[0].split(",").length > 1) {
								window.cols_array = lines[0].split(",");
							} else {
								window.cols_array = lines[0].split("\t");
							}
						} else if (separator == "\\\t") {
							window.cols_array = lines[0].split("\t");
						} else {
							window.cols_array = lines[0].split(separator);
						}
					}
				}
			 });
		}
	}
				if (import_type.length) {';
			if (!empty($GLOBALS['display_save_rules'])) {
				$this_js_content .= '	
				$("#fields_rules").show();';
			}

			$this_js_content .= '	
		$("#div_correspondance").show();
		$("#div_correspondance_explain").hide();
		// Affiche div correspondant au type sélectionnée
		div_id = "fields_"+import_type;
		$("#"+div_id).show();
		$("#"+div_id+" input").prop("disabled", false);
		// Récupère la première ligne du fichier CSV
		// Crée des span pour chaque colonne du CSV téléchargé 
		$.each(cols_array, function(key, field) {
			if(field.substring(0,1)==\'"\' && field.substring(field.length-1)==\'"\') {
				field = field.substring(1,field.length-1);
			}
			field = field.trim();
			cols_array[key]=field;
			//split("/").join("_").split(" ").join("_") suppression des slash, des parenthèses et des guillemets et des espaces dans le id de champ sinon ça pose problème lors du chargement de la règle sauvegardée (erreur JS)
			$("' . $contains_draggable_js . '").append("<span class=\'field_draggable\' id=\'filecol_"+field.replace(".", "\\\.").split("\'").join("_").split(")").join("_").split("(").join("_").split("/").join("_").split(" ").join("_").split("&").join("_").split("=").join("_")+"\' draggable=\'true\'>"+field+"<br /></span>");
		});
		drag_and_drop_fields("' . $contains_draggable_js . '", ".field_draggable", "#"+div_id+" .container_drop_draggable");
		if($("#correspondance_type").val()==import_type) {
			corresp=$("#correspondance").val();
			if(corresp) {
				load_fields_correspondance(corresp, null);
			}
		}
		// Active tout élément complémentaire du formulaire
		$(".div_for_type_"+import_type).show();
	} else {
		$("#div_correspondance").hide();
		$("#div_correspondance_explain").show();
	}	
}';		
			$GLOBALS['js_fineuploader_complete'] = '
if($("input[name=import_file]").val()) {
	$("#import_export_type").prop("disabled", false);
	change_import_type();
} else {
	$("#import_export_type").val("");
	$("#import_export_type").prop("disabled", true);
}
';
		} else {
			// EXPORT
			// Affiche la div de sélection des champs en fonction du type d\'export sélectionné
			// on ne réinitialise pas les associations avec $(".contains_draggable .field_draggable").detach().appendTo($(".container_drop_draggable"));	
			if (empty($GLOBALS['export_domains_for_compta']) || empty($params['domain']) || !in_array($params['domain'], $GLOBALS['export_domains_for_compta'])) {
				// Affichage des champs de choix de colonne, de choix d'encodage, de séparateur et d'ajout d'une ligne header dans le fichier d'export
				$this_js_content .= '
				$("#export_columns_form").show();
				$("#data_encoding_form").show();
				$("#separator_form").show();
				$("#header_form").show();
				';
			}
			// NB : dans display_new_select pas besoin de faire : $("#new_"+mode+"_"+this_type+"_"+id+" select").val(""); car les valeurs sont initialisées par ailleurs avec $(".div_hidden_by_default select").val("");
			$this_js_content .= '
window.display_new_select = function(id, this_type, mode) {
	$("#new_"+mode+"_"+this_type+"_"+id).show();
}
';

			$this_js_content .= '
window.change_export_type = function(first_load) {
	export_type = $("#import_export_type").val();
	$(".div_hidden_by_default").hide();';
			if (empty($GLOBALS['export_domains_for_compta']) || empty($params['domain']) || !in_array($params['domain'], $GLOBALS['export_domains_for_compta'])) {
		// Affichage des champs de choix de colonne, de choix d'encodage, de séparateur et d'ajout d'une ligne header dans le fichier d'export
				$this_js_content .= '
	$("#export_columns_form").show();
	$("#data_encoding_form").show();
	$("#separator_form").show();
	$("#header_form").show();
';
			}
			$this_js_content .= '
	if (export_type.length) {
		if(export_type == "peel_produits") { 
			$("#form_produits").show(); 
		}
		if(export_type == "livraisons" || export_type == "ventes" || export_type == "one_line_per_product" || export_type == "one_line_per_order" || export_type == "ventes_chronopost" || export_type == "ventes_cegid") {
			$("#date_filter_form").show(); 
		}
		$("#main_group_by").has("#group_by_"+export_type).show();';
			if (!empty($GLOBALS['display_save_rules']) && (empty($GLOBALS['export_domains_for_compta']) || empty($params['domain']) || !in_array($params['domain'], $GLOBALS['export_domains_for_compta']))) {
				$this_js_content .= '
		$("#fields_rules").show();
';
			}
			$this_js_content .= '
		$("#div_correspondance").show();
		$("#div_correspondance_explain").hide();
		// Affiche div correspondant au type sélectionné
		div_id = "fields_"+export_type;
		$("#"+div_id).show();
		drag_and_drop_fields("' . $contains_draggable_js . '", ".field_draggable", "#"+div_id+" .container_drop_draggable");
		' . vb($params['change_export_type_js']) . '
		// Active tout élément complémentaire du formulaire
		$(".div_for_all_types").show();
		$(".div_for_type_"+export_type).show();

		$.ajax({
			url: \'' . get_current_url() . '\',
			dataType : \'json\',
			type : \'POST\',
			data : {
				import_export_type : export_type,
				mode: \'get_type_infos\',
				ajax: true
			},
			success: function( data ) {
				if(data["select_rules"]) $("#load_rule").remove(); $("#load_rule_container").append(data["select_rules"]);
				$(".new_order_by_group_by_select").hide();
				$(".div_hidden_by_default select").val("");
				if(data.group_default) {
					for(i=1;i<=data.group_default.length;i++) {
						$("#new_group_by_"+export_type+"_"+i).show();
						$("#new_group_by_"+export_type+"_"+i+" select").val(data.group_default[i-1]);
					}
					if(i>=1 && i<=5) display_new_select(i,export_type,"group_by");
				}
				if(data.order_default) {
					for(i=1;i<=data.order_default.length;i++) {
						$("#new_order_by_"+export_type+"_"+i).show();
						$("#new_order_by_"+export_type+"_"+i+" select").val(data.order_default[i-1]);
					}
					if(i>=1 && i<=5) display_new_select(i,export_type,"order_by");
				}
				if(!data.show_details_default) {
					$("#show_details_checkbox").prop("checked", false);
				} else {
					$("#show_details_checkbox").prop("checked", true);				
				}
				if(!data.show_date_field_default) {
					$("#date").hide();
				} else {
					$("#date").show();
				}
				if(data.max_subtotals_level_default) {
					$("#max_subtotals_level_select").val(data.max_subtotals_level_default);
				}	
				if(data.export_type_explanation) {
					$("#export_type_explanation").html(data.export_type_explanation);
				} else {
					$("#export_type_explanation").html("");
				}
				if(!data.skip_empty_totals_fields) {
					$("#skip_empty_totals").hide();
					$("#skip_empty_totals_checkbox").prop("checked", false);
				} else {
					$("#skip_empty_totals").show();
					$("#skip_empty_totals_checkbox").prop("checked", true);	
				}
				if (typeof first_load !=="undefined" && first_load === true) {
					load_rules("last_submit"); // Chargement de la dernière version validée du formulaire
				}
				$("div").filter(":visible").children("input:not(.keep_disabled), select:not(.keep_disabled), textarea:not(.keep_disabled)").prop("disabled", false);
			}
		});
	} else {
		$("#div_correspondance").hide();
		$("#div_correspondance_explain").show();
	}
}';		
		}
		// Gestion des choix des champs à importer ou exporter
		$this_js_content .= '
window.cols_array = [];
window.load_fields_correspondance = function(source, params) {
	if(source) {
		var type = $("#import_export_type").val();
		div_id = "fields_"+type;
		$("#"+div_id+" .container_drop_draggable .field_draggable").detach().appendTo($("' . $contains_draggable_js . '"));
		$.each(source.split("&"), function(this_key,this_col_corresp) {
			temp=this_col_corresp.split("=");
			field=temp[0];
			filecol=decodeURIComponent(temp[1]);
			' . ($import_or_export == 'import'?'
			$("' . $contains_draggable_js . ' #filecol_"+filecol.replace(".", "\\\.").split("\'").join("_").split(")").join("_").split("(").join("_").split("/").join("_").split(" ").join("_").split("&").join("_").split("=").join("_")+".field_draggable").detach().appendTo($("#"+div_id+" #fields_"+type+"_"+field.replace(".", "\\\.").split("/").join("_")+".container_drop_draggable"));
				':'
			$("' . $contains_draggable_js . ' #filecol_"+field.replace(".", "\\\.").split("\'").join("_").split(")").join("_").split("(").join("_").split("/").join("_").split(" ").join("_").split("&").join("_").split("=").join("_")+".field_draggable").detach().appendTo($("#"+div_id+" .container_drop_draggable"));
				') . '
		});	
		if(params) {
			' . ($import_or_export == 'import'?'':'
			if (typeof params["order_by_value"]!=="undefined" && params["order_by_value"] != null && params["order_by_value"].length) {
				$("select[name=\'order_by[]\']").val("");
				var i = 1;
				$.each(params["order_by_value"].split("&"), function(this_key,this_value) {
					$("#new_order_by_"+type+"_"+i).show();
					$("#new_order_by_"+type+"_"+i+" select").val(this_value);
					i++;
				});
				if(i<=5) display_new_select(i,type,"order_by");
			}
			if (typeof params["group_by_value"]!=="undefined" && params["group_by_value"] != null && params["group_by_value"].length) {
				$("select[name=\'group_by[]\']").val("");
				var i = 1;
				$.each(params["group_by_value"].split("&"), function(this_key,this_value) {
					$("#new_group_by_"+type+"_"+i).show();
					$("#new_group_by_"+type+"_"+i+" select").val(this_value);
					i++;
				});
				if(i<=5) display_new_select(i,type,"group_by");
			}
		') . '
	';
		if (empty($GLOBALS['export_domains_for_compta']) || empty($params['domain']) || !in_array($params['domain'], $GLOBALS['export_domains_for_compta'])) {
			// NB : il n'y a pas de datatables dans l'export en compta.
			// On met en variable globale les lignes sélectionnées, elles serviront dans les initComplete par la suite
			$this_js_content .= '
				if (typeof selected_datatable_lines === "function") { selected_datatable_lines(null, params["selected_lines"]); }
				window.loaded_selected_lines = params["selected_lines"];';

			if (!empty($params['queryBuilder_table_id'])) {
				// Attention : lors des encodages en JSON (par le navigateur puis par le serveur) rules en tant que tableau vide n'est pas encodé, il est juste supprimé
				// et si on a le tableau des règles qui ne contient pas rules: [] il faut le définir sinon querybuilder va générer une erreur
				// => on rajoute cet élément vide si nécessaire
				$this_js_content .= '
			if(!params["filter_rules"]["rules"]) params["filter_rules"]["rules"] = [];
			$("#' . $params['queryBuilder_table_id'] . '_builder").queryBuilder("setRules", params["filter_rules"]);
			' . querybuilder_open_div_js($params['queryBuilder_table_id']) . '
			';
			}
			
		}
		if($import_or_export == 'import') {
			// console.log(temp);
			$this_js_content .= '
			if(params["default_fields"]) {
				$.each(params["default_fields"].split("&"), function(this_key,this_col_corresp) {
					temp=this_col_corresp.split("=");
					field=temp[0];
					filevalue=temp[1];
					$("#"+field.replace(".", "\\\.").split("\'").join("_").split(")").join("_").split("(").join("_").split("/").join("_").split(" ").join("_").split("&").join("_").split("=").join("_")).val(filevalue);
				});
			}';
		} else {
			// Export. Appelé dans le cadre de handle_export
			if (!empty($params['additional_input_ids_array'])) {
				// dans additional_input_ids_array on va stocker la liste de champs à mettre à jour dynamiquement
				foreach($params['additional_input_ids_array'] as $this_input => $this_field) {
					if ($this_field == 'header' || $this_field == 'show_details' || $this_field == 'skip_empty_totals' || $this_field == 'page_break') {
						// champ checkbox
						$this_js_content .= '
			if (params["'.$this_field.'"] === null) {
				$("#'.$this_input.'").prop("checked", false);
			} else {
				$("#'.$this_input.'").prop("checked", true);				
			}';
					} else {
						$this_js_content .= '
			$("#'.$this_input.'").val(params["'.$this_field.'"]);';
					}
				}
			}
		}
		$this_js_content .= '
		}
	}
}
window.set_correspondance = function() {
	var correspondance_array = [];
	var default_fields_array = [];
' . ($import_or_export == 'import'?'
	$("#fields_"+$("#import_export_type").val()+" .container_drop_draggable").find(".field_draggable").each(function( index, element ) {
		var db_col;
		db_col = $(this).parent(".container_drop_draggable").attr("id").replace("fields_"+$("#import_export_type").val()+"_","");
		correspondance_array.push(db_col+"="+encodeURIComponent($(this).attr("id").replace("filecol_","").replace("\\\.","."))); 
	});
		
	var type = $("#import_export_type").val();
	if (type!="") {
		$("#fields_"+type+" input[type=text]").each(function( index ) {
			var input = $(this);
			if(input.val() !="") {
				default_fields_array.push(input.attr(\'name\')+"="+input.val());
			}
		});
		$("#default_fields").val(default_fields_array.join("&"));
	}
':'   
	$("#fields_"+$("#import_export_type").val()+" .container_drop_draggable").find(".field_draggable").each(function( index, element ) {
		correspondance_array.push($(this).attr("id").replace("filecol_","").replace("\\\.","."));
	});
') . '
	$("#correspondance").val(correspondance_array.join("&"));
}
window.reset_fields = function() {
	div_id = "fields_"+$("#import_export_type").val();
	$("#"+div_id+" .container_drop_draggable").children(".field_draggable").not(".sel_by_def").detach().appendTo($("' . $contains_draggable_js . '"));
	$("#"+div_id+" .contains_draggable").children(".field_draggable.sel_by_def").detach().appendTo($("#"+div_id+" .container_drop_draggable"));	
	$("#"+div_id+" input[type=text]").each(function( index ) {
		$(this).val("");
	});
}

$("#rules_reset").on("click", function() {
	reset_fields();
});
$("#import_export_form").on("submit", function(e){
	' . (!empty($params['js_submit_array']) ? implode(',', $params['js_submit_array']) : '') . ' 
	set_correspondance(); 
	$("div").filter(":hidden").children("input, select, textarea").prop("disabled", true);
});


$(\'#rules_delete\').on(\'click\', function() {
	bootbox.confirm("'.$GLOBALS['STR_DELETE_CART_TITLE'].' " + $("#load_rule").val() + "?", function(result) {
		if(result) {
			$.ajax({
				url: \'' . get_current_url() . '\',
				dataType : \'text\',
				type : \'POST\',
				data : {
					import_export_type: $("#import_export_type").val(),
					mode: \'delete_select_rules\',
					rule_to_delete: $(\'#load_rule\').val(),
					ajax: true
				},
				success: function( data ) {
					$("#load_rule").remove();
					$("#load_rule_container").append(data);
					bootbox.alert({message: "' . filtre_javascript($GLOBALS['STR_MESSAGE_BOOTBOX_DELETE_IMPORT_EXPORT_CONFIGURATION_DONE'], true, false, true, true, false) . '",size: "small"});
				}
			});
		}
	});
});

function load_rules(this_load_rule) {
	$.ajax({
		url: \'' . get_current_url() . '\',
		dataType : \'json\',
		type : \'POST\',
		data : {
			mode: \'get_rules\',
			load_rule: this_load_rule,
			ajax: true
		},
		success: function( data_array ) {
			reset_fields();
			load_fields_correspondance(data_array["correspondance"], data_array);
			if(this_load_rule!="last_submit") $(\'#rule_name\').val(this_load_rule);
		}
	});
}

$(\'#rules_get\').on(\'click\', function() {
	load_rules($(\'#load_rule\').val())
});

window.set_rules = function() {
	if ($("#rule_name").val() !="") {';
	if (!empty($params['set_rules_additional_input_ids_array']) && in_array('group_by_value', $params['set_rules_additional_input_ids_array']) && in_array('order_by_value', $params['set_rules_additional_input_ids_array'])) {
		$this_js_content .= '
		var group_by_value_array = new Array();
		var order_by_value_array = new Array();

		$("select[name=\'group_by[]\']").each(function() {
			if (this.value != "") {
				group_by_value_array.push(this.value);
			}
		});

		$("select[name=\'order_by[]\']").each(function() {
			if (this.value != "") {
				order_by_value_array.push(this.value);
			}
		});
		
		var group_by_value = group_by_value_array.join("&");
		var order_by_value = order_by_value_array.join("&");
		';
	}
		$this_js_content .= '
		set_correspondance();';
		
		$this_js_content .= '
		$.ajax({
			url: \'' . get_current_url() . '\',
			dataType : \'json\',
			type : \'POST\',
			data : {
				mode: \'set_rules\',
				import_export_type: $("#import_export_type").val(),
				rule_name: $("#rule_name").val(),';
		if (!empty($params['set_rules_additional_input_ids_array'])) {
			foreach($params['set_rules_additional_input_ids_array'] as $this_input => $this_field) {
				if ($this_field == 'order_by_value' || $this_field == 'group_by_value') {
					$this_js_content .= $this_field.': '.$this_field.',
					';
				} elseif ($this_field == 'header' || $this_field == 'show_details' || $this_field == 'skip_empty_totals' || $this_field == 'page_break') {
					$this_js_content .= $this_field.': $("#'.$this_input.'").is(":checked"),
					';
				} else {
					$this_js_content .= $this_field.': $("#'.$this_input.'").val(),
					';
				}
			}
		}
		$this_js_content .= '';
		if($import_or_export == 'export') {
			if (!empty($params['queryBuilder_table_id'])) {
				$this_js_content .= '
				filter_rules: JSON.stringify($("#' . $params['queryBuilder_table_id'] . '_builder").queryBuilder("getRules", { skip_empty: true })),';
			}
		}
		$this_js_content .= '
				selected_lines: {';
		if($import_or_export == 'export') {
			if (!empty($GLOBALS['database_import_export_table_filters_titles_array'])) {
				// database_import_export_table_filters_titles_array : table de filtre pour "Restreindre le rapport aux "%s" sélectionnés" avec datatbale
				foreach($GLOBALS['database_import_export_table_filters_titles_array'] as $table_name => $table_text) {
					$datatable_id = 'table_filters_' . substr(md5($table_name), 0, 6);
					$this_js_content .= '
					'. $datatable_id.': ' . $datatable_id . '.rows( { selected: true } ).ids().toArray().join(","),';
				}
			}
		}
		$this_js_content .= '
					},
				ajax: true
			},
			success: function( data ) {
			}
		});
		bootbox.alert({message: "' . filtre_javascript($GLOBALS['STR_MESSAGE_BOOTBOX_SAVE_IMPORT_EXPORT_CONFIGURATION_DONE'], true, false, true, true, false) . '", size: "small"});
		$("#rule_name").val("");
	}
}
$(\'#rules_set\').on(\'click\', function() {
	var rules_already_exist = "";
	export_type = $("#import_export_type").val();

	$("#load_rule option").each(function()
	{
	  if ($(this).val() == $("#rule_name").val().toLowerCase()) {
		  rules_already_exist = 1;
	  }
	});
	if (rules_already_exist == "1") {
		// le nom de la sauvegarde existe déjà, donc on demande confirmation avant de réécrire la configuration
		bootbox.confirm("'.$GLOBALS['STR_OVERWRITE_SAVE'].'" + $("#rule_name").val() + "?", function(result) {if(result) {
			set_rules();
		}});
	} else {
		// pas de correspondance trouvée, on sauvegarde la nouvelle configuration
		set_rules();		
	}
	// il faut rafraichir le select des configurations avec la nouvelle qui vient d\'être créée
	$.ajax({
		url: \'' . get_current_url() . '\',
		dataType : \'json\',
		type : \'POST\',
		data : {
			import_export_type : export_type,
			mode: \'get_type_infos\',
			ajax: true
		},
		success: function( data ) {
			$("#load_rule").remove();
			$("#load_rule_container").append(data["select_rules"]);
		}
	});

});

';
	}
	$GLOBALS['js_ready_content_array'][] = $this_js_content;

	
	$hook_result = call_module_hook('import_export_init_post', $params, 'array');
	return $output;
}

/**
 * Génère les tableaux d'information des champs
 *
 * @param array $params
 * @return
 */
function get_database_field_properties(&$params) {
	if(!empty($params['import_or_export']) && $params['import_or_export'] == 'import') {
		$required_first = true;
	} else {
		$required_first = false;
	}
	$tpl_inputs = array();
	$fields_explanations_array = vb($GLOBALS['fields_explanations_array'], array());
	$sql = "SELECT *, texte_" . $_SESSION['session_langue'] . " as texte
		FROM peel_import_field
		WHERE " . get_filter_site_cond('import_field', null, true) . "";
	// Si table absente, pas d'erreur remontée
	$req = query($sql, false, null, true);
	while ($result = fetch_assoc($req)) {
		$fields_explanations_array[$result['champs']] = $result['texte'];
	}
	if(!empty($GLOBALS['database_fields_by_type_array'])) {
		// On génère des tableaux avec les tables contenant les champs importables, pour permettre à l'utilisateur de faire des concordances avec des drag & drop
		foreach(array_keys($GLOBALS['database_import_export_type_names_array']) as $this_type) {
			$table_field_names = &$GLOBALS['database_fields_by_type_array'][$this_type];
			//sort($table_field_names);
			// Pour récupérer le nom de table, d'habitude on utilise foreach(explode('|', $GLOBALS['database_import_export_table_by_type_array'][$this_type]) as $this_table) {
			// mais là on va utiliser ensuite des fonctions compatibles avec la syntaxe table1|table2
			$this_table_or_tables = vb($GLOBALS['database_import_export_table_by_type_array'][$this_type], $this_type);
			unset($selected_array);
			if($this_type == $params['type'] && !empty($params['ordered_fields_selected'])) {
				$selected_array = &$params['ordered_fields_selected'];
			} elseif(!empty($GLOBALS['database_fields_default_by_type_array'][$this_type])) {
				$selected_array = &$GLOBALS['database_fields_default_by_type_array'][$this_type];
			} else {
				$selected_array = &$table_field_names;
			}
			$temp_inputs = array(0 => array(), 1 => array(), 2 => array());
			$primary_key_array = array();
			foreach ($table_field_names as $this_field) {
				// On construit la liste des champs, qu'on va ordonner ensuite
				if(empty($GLOBALS['database_mode_by_type_array'][$this_type]) || $GLOBALS['database_mode_by_type_array'][$this_type] != 'virtual') {
					$required = get_field_required($this_field, $this_table_or_tables);
					$type = str_replace(',', ', ', vb($GLOBALS['database_field_infos_by_type_array'][$this_type][$this_field]));
					$maxlength = (!empty($GLOBALS['database_field_infos_by_type_array'][$this_type][$this_field])?get_field_maxlength($GLOBALS['database_field_infos_by_type_array'][$this_type][$this_field]):null);
				} else {
					$required = false;
					$type = 'varchar(255)';
					$maxlength = null;
				}
				$primary = in_array($this_field, $primary_key_array);
				if(!empty($params['simplified_type_presentation'])) {
					if(strpos($type, 'blob') !== false || strpos($type, 'text') !== false) {
						$type = 'texte';
					} elseif(strpos($type, 'char') !== false) {
						$type = get_field_maxlength($type) . '&nbsp;car.';
					} elseif(strpos($type, 'tinyint') !== false) {
						$type = '0&nbsp;ou&nbsp;1';
					} elseif(strpos($type, 'int') !== false || (!empty($GLOBALS['database_fields_format']['int']) && in_array($this_field, $GLOBALS['database_fields_format']['int']))) {
						$type = 'entier';
					} elseif(strpos($type, 'double') !== false || strpos($type, 'float') !== false || strpos($type, 'decimal') !== false) {
						$type = '#.##';
					}
				}
				if($required_first) {
					$index = ($required?($primary?0:1):2);
				} else {
					$index = 0;
				}
				$temp_inputs[$index][] = array('field' => $this_field,
					'field_title' => get_field_title($this_field, $this_type, true, null, true),
					'selected' => in_array($this_field, $selected_array),
					'explanation' => vb($fields_explanations_array[$this_field]),
					'primary' => $primary,
					'required' => $required,
					'default' => vb($params['defaults']['default_'.$this_type.'_'.$this_field]),
					'maxlength' => $maxlength,
					'type' => $type
				);
			}
			foreach(array(0,1,2) as $this_sort_key) {
				// On met d'abord les champs requis dans la liste
				foreach($temp_inputs[$this_sort_key] as $this_array) {
					$tpl_inputs[$this_type][] = $this_array;
				}
			}
		}
	}
	
	return $tpl_inputs;
}

/**
 * Gestion de l'import
 *
 * @param boolean $check_access_rights
 * @param array $params
 * @return
 */
function handle_import($check_access_rights = true, $params = array()) {
	// On isole le CSS pour pouvoir utiliser cette fonction éventuellement en front-office
	// CSS pour le tableau de correspondance des imports
	$disable_tokens = false; // Pour debug
		
	$output = import_export_init('import', $params);
	
	$type = $params['type'];
	$error_output = array();
	$mode = vb($params['mode']);

	// NB : $GLOBALS['database_fields_by_type_array'][$type] contient la liste des champs de toutes les tables susceptibles d'être importées (déjà filtrée avec les droits de l'utilisateur)

	switch ($mode) {
		case "import":
			// force_import_despite_of_error cette variable est envoyé en POST (via la fonction JS force_import_with_error_line) uniquement si l'utilisateur force l'import malgré les erreurs. Cette variable permet de modifier l'affichage associé à ce cas de figure
			$force_import_despite_of_error = !empty($_POST['force_import_despite_of_error']);
			$test_mode = !empty($_POST['test_mode']);
			$GLOBALS['nb_insert'] = 0;
			$GLOBALS['nb_update'] = 0;
			$joined_table_infos = array();
			if($check_access_rights) {
				if (a_priv('demo')) {
					$error_output[] = sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']));
					break;
				}
				if(empty($GLOBALS['database_import_export_type_names_array'][$type])) {
					// L'utilisateur n'a pas le droit d'importer dans la table demandée
					$error_output[] = sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']));
					break;
				}
			}
			if(empty($params['import_file'])) {
				$params['import_file'] = upload('import_file', false, 'data', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
			} else {
				$disable_tokens = true;
			}
			if(!empty($params['import_file']) && substr($params['import_file'], 0, 1) !== '/') {
				$params['import_file_fullpath'] = $GLOBALS['uploaddir'] . '/' . $params['import_file'];
			} else {
				$params['import_file_fullpath'] = $params['import_file'];
			}
			if (!$disable_tokens && !verify_token($_SERVER['PHP_SELF'] . $mode)) {
				$error_output[] = $GLOBALS['STR_INVALID_TOKEN'];
			} elseif (empty($params['type'])) {
				$error_output[] = $GLOBALS['STR_ADMIN_IMPORT_ERR_TYPE_NOT_CHOSEN'];
			} elseif (empty($params['ordered_fields_selected'])) {
				$error_output[] = $GLOBALS['STR_ADMIN_IMPORT_ERR_FIELDS_NOT_CHOSEN'];
			} elseif (empty($params['import_file_fullpath']) || !file_exists($params['import_file_fullpath'])) {
				// le fichier n'existe pas
				$error_output[] = $GLOBALS['STR_ADMIN_IMPORT_ERR_FILE_NOT_FOUND'];
				unset($params['import_file']);
			} else {
				if(empty($params['data_encoding'])) {
					// Détection de l'encodage
					if(!StringMb::detect_utf8_characters(StringMb::file_get_contents_utf8(str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], $params['import_file_fullpath'])))) {
						// Ce n'est pas de l'utf-8, donc on se met en mode iso-8859-1
						$params['data_encoding'] = 'iso-8859-1';
					} else {
						$params['data_encoding'] = 'utf-8';
					}
				}
				// Tout semble configuré et OK
				// On commence l'import

				// On ouvre le fichier pour charger la première ligne avec les titres de colonnes
				if($fp = StringMb::fopen_utf8($params['import_file_fullpath'], "rb")) {
					$general_configuration_is_valid = true;
					// On récupère la première ligne
					$this_line = StringMb::convert_encoding(fgets($fp, 16777216), GENERAL_ENCODING, $params['data_encoding']);
					if(!empty($this_line) && (StringMb::substr($this_line, -1) == "\r" || StringMb::substr($this_line, -1) == "\n")) {
						$this_line = StringMb::substr($this_line , 0, StringMb::strlen($this_line) - 1);
						if(!empty($this_line) && (StringMb::substr($this_line, -1) == "\r" || StringMb::substr($this_line, -1) == "\n")) {
							$this_line = StringMb::substr($this_line , 0, StringMb::strlen($this_line) - 1);
						}
					}
					if (empty($params['separator'])) {
						// détection automatique du séparateur dans le fichier
						if (strpos($this_line, "\t") !== false) {
							$params['separator'] = "\t";
						} elseif (strpos($this_line, ";") !== false) {
							$params['separator'] = ";";
						} elseif (strpos($this_line, ",") !== false) {
							$params['separator'] = ",";
						} else {
							$params['separator'] = "\t";
						}
					}
					$file_columns = explode($params['separator'], $this_line);

					// Vérification de cohérence entre $params['ordered_fields_selected'] et les colonnes réellement disponibles dans le fichier (normalement le traitement en AJAX gère déjà cela, mais il faut vérifier le $_POST quoiqu'il arrive)
					foreach(array_keys($file_columns) as $this_key) {
						if (StringMb::substr($file_columns[$this_key], 0, 1) == '"' && StringMb::substr($file_columns[$this_key], -1) == '"') {
							// Il faut traiter l'escape de guillemets dans les champs
							$file_columns[$this_key] = str_replace('""', '"', StringMb::substr($file_columns[$this_key], 1, StringMb::strlen($file_columns[$this_key]) - 2));
						}
						$file_columns[$this_key] = str_replace(array("\n","\r","\r\n","\n\r"," ","/","'","(",")","&"), array('','','','','_','_','_','_','_','_'), trim($file_columns[$this_key]));
					}
					foreach($params['ordered_fields_selected'] as $this_bdd_field => $this_file_field) {
						// NB : $this_file_field vaut true quand on a une valeur par défaut pour un champ et aucune correspondance avec le fichier importé
						if ($this_file_field !== true && (!in_array($this_file_field, $file_columns) || !in_array($this_bdd_field, $GLOBALS['database_fields_by_type_array'][$type]))) {
							// var_dump($this_file_field, $file_columns, $this_bdd_field, $GLOBALS['database_fields_by_type_array'][$type]);
							// Champ non présent dans le fichier
							// OU champ non existant en BDD, ou non défini pour un type virtuel
							unset($params['ordered_fields_selected'][$this_bdd_field]);
							if (!in_array($this_file_field, $file_columns)) {
								$error_output[] = sprintf($GLOBALS['STR_ADMIN_IMPORT_ERR_COLUMN_NOT_FOUND'], $this_file_field);
								continue;
							} else {
								$error_output[] = sprintf($GLOBALS['STR_ADMIN_IMPORT_ERR_COLUMN_NOT_HANDLED'], array_search($this_file_field, $file_columns), (!empty($this_file_field)?$this_file_field:'[-]'));
							}
							continue;
						}
					}
					$GLOBALS['line_number'] = 0;

					if(!empty($params['ordered_fields_selected']) && count($params['ordered_fields_selected'])){
						// On souhaite importer au moins un champ
						// Initialisations diverses
						// A FAIRE : gérer les types qui correspondent à plusieurs tables
						$table_name = $type;
						$primary_key_auto_increment = null;
						if(empty($GLOBALS['database_mode_by_type_array'][$type]) || $GLOBALS['database_mode_by_type_array'][$type] != 'virtual') {
							foreach($GLOBALS['database_field_infos_by_type_array'][$type] as $this_field => $this_field_type) {
								$field_maxlength_array[$this_field] = get_field_maxlength($this_field_type);
							}
							$primary_key = get_primary_key($table_name);
							$real_table_fields = get_table_fields($table_name);
							$primary_key_auto_increment = false;
							foreach($real_table_fields as $this_column) {
								$real_table_field_names[] = $this_column['Field'];
								if(in_array($this_column['Field'], get_array_from_string($primary_key))) {
									if($this_column['Extra'] == 'auto_increment') {
										// Il n'y a au maximum qu'un champ autoincrement par table
										$primary_key_auto_increment = true;
									} 
								}
							}
						}
						// On commence l'import des données
						while (!StringMb::feof($fp)) {
							// On passe ici pour chaque insertion ou MAJ
							unset($product_id);
							unset($set_sql_fields);
							unset($field_values);
							$last_treated_column = 0;
							$GLOBALS['line_number']++;
							$error_primary = null;
							$error_output_line_array = array();
							$primary_key_values = array();
							$line_in_database_infos = null;
							$text_in_wait = '';
							// On récupère une ligne de données :
							// Si une valeur de cas contient des sauts de ligne, alors on prend quand même la ligne suivante comme si c'était la continuité de cette ligne
							while (!StringMb::feof($fp) && $last_treated_column < count($file_columns)) {
								// Tant qu'on n'atteint pas fin de fichier
								$this_line = StringMb::convert_encoding(fgets($fp, 16777216), GENERAL_ENCODING, $params['data_encoding']);
								if(!empty($this_line) && (StringMb::substr($this_line, -1) == "\r" || StringMb::substr($this_line, -1) == "\n")) {
									$this_line = StringMb::substr($this_line , 0, StringMb::strlen($this_line) - 1);
									if(!empty($this_line) && (StringMb::substr($this_line, -1) == "\r" || StringMb::substr($this_line, -1) == "\n")) {
										$this_line = StringMb::substr($this_line , 0, StringMb::strlen($this_line) - 1);
									}
								}
								if (empty($this_line)) {
									// ligne vide ou sans données à importer, il faut ignorer la ligne et passer à la suivante
									continue;
								}
								// $output .= '<hr />Ligne du fichier CSV : $this_line';
								$line_values = explode($params['separator'], $this_line);
								if($last_treated_column >= count($file_columns)) {
									// On a fini une ligne, on réinitialise le texte en attente
									$text_in_wait = '';
								}
								// En cas de séparateur qui se retrouve dans des champs qui sont identifiés par des guillemets au début et à la fin, $line_values va contenir une colonne scindée en N morceaux. Donc $key va être incrémentée à chaque fois qu'un champ est complètement chargé
										
										
								foreach($line_values as $this_value) {
									// On récupère les valeurs présentes dans la ligne en cours. Pour gérer correctement les colonnes contenant un séparateur, il faut s'occuper de toutes les lignes, même si on n'en a sélectionné que quelques unes

									// Suppression de guillemets indésirables. Les guillemets avant et après une valeur sont supprimé. Ils peuvent être ajoutés à l'insu de créateur du fichier en éditant le fichier avec excel.
									$this_value = $text_in_wait . trim($this_value);
									$text_in_wait = '';
									if ($this_value == '""' || $this_value == "''") {
										$this_value = '';
									} elseif (StringMb::substr($this_value, 0, 1) == '"' && StringMb::substr($this_value, -1) == '"') {
										// Il faut traiter l'escape de guillemets dans les champs
										$this_value = str_replace('""', '"', StringMb::substr($this_value, 1, StringMb::strlen($this_value) - 2));
									} elseif(StringMb::substr($this_value, 0, 1) == '"') {
										// Guillemet au début et pas à la fin de la case => on attend le guillemet de fin
										// => on fusionne ce champ avec la suite, et on remet si nécessaire le séparateur qui n'aurait pas dû être retiré
										$text_in_wait = $this_value;
										if(StringMb::substr($this_line, -1) == $params['separator']) {
											// Si la ligne se terminait par un séparateur, on le remet
											$text_in_wait .= $params['separator'];
										} else {
											// sinon c'est que c'était un saut de ligne, et on met espace à la place
											// (dans les libellés, etc, on veut plutôt éviter les sauts de ligne)
											$text_in_wait .= ' ';
										}
										continue;
									}
									if(isset($file_columns[$last_treated_column])) {
										// Remarque : $file_columns[$last_treated_column] correspond au nom de champ de fichier
										if(in_array($file_columns[$last_treated_column], $params['ordered_fields_selected'], true)) {
											// On a sélectionné ce champ pour l'import
											$this_bdd_field = array_search($file_columns[$last_treated_column], $params['ordered_fields_selected'], true);
											if (!isset($field_values[$this_bdd_field])) {
												// On va fusionner des colonnes si plusieurs ont le même nom
												$field_values[$this_bdd_field] = '';
											}
											$field_values[$this_bdd_field] .= $this_value;
										}
									} else {
										// Colonne n'existe pas (problème)
										if ($last_treated_column+1 > count($file_columns)) {
											 // si un ligne n'a pas le bon nombre de colonne
											$error_output[] = sprintf($GLOBALS['STR_ADMIN_IMPORT_ERR_COLUMN_FILE_STRUCTURE'], $GLOBALS['line_number']);
										} else {
											$error_output[] = sprintf($GLOBALS['STR_ADMIN_IMPORT_ERR_COLUMN_NOT_KNOWN'], ($last_treated_column+1), $this_value);
										}
									}
									$last_treated_column++;
								}
							}
							// On ne tient pas compte de la ligne si elle ne contient que des données vides
							$skip_this_line = false;
							if(!empty($field_values)) {
								$skip_this_line = true;
								foreach($field_values as $this_value) {
									// pour chaque champ on vérifie si il y a une valeur
									if (!empty($this_value)) {
										// une valeur a été trouvée, on peut continuer.
										$skip_this_line = false;
									}
								}
							}
							if (!empty($skip_this_line)) {
								$skip_this_line = false;
								// toutes les valeurs de la ligne sont vide, on ne fait rien et on passe à la suivante
								continue;
							}
							// NB : Si ligne vide en fin de fichier, $field_values est vide => on ne veut pas compléter avec les valeurs par défaut, mais s'arrêter sans traiter cette ligne
							if (!empty($field_values)) {
								// On rajoute les valeurs par défaut (soit colonne sans correspondance, soit avec correspondance mais ligne avec case vide)
								foreach($params['ordered_fields_selected'] as $this_field_name => $this_value) {
									if ((isset($params['defaults']['default_'.$type.'_'.$this_field_name]) && $params['defaults']['default_'.$type.'_'.$this_field_name] !== '') && (!isset($field_values[$this_field_name]) || $field_values[$this_field_name] === '')) {
										// Il y a une valeur par défaut de disponible pour cette colonne, et la case est vide (car pas de correspondance de colonne, ou car valeur dans le fichier est vide)
										$field_values[$this_field_name] = $params['defaults']['default_'.$type.'_'.$this_field_name];
									}
								}
								// On a chargé toutes les colonnes sélectionnées dans $field_values, après avoir étudié toutes les colonnes (c'est nécessaire pour bien gérer les cas de sauts de ligne avec gestion des guillemets).
								
								// On décode le format des dates si nécessaire
								if(!isset($field_types)) {
									$field_types = get_table_field_types($table_name);
								}
								foreach($field_values as $this_field_name => $this_value) {
									if(!empty($field_types[$this_field_name])) {
										if($field_types[$this_field_name] == 'date' || $field_types[$this_field_name] == 'datetime') {
											$field_values[$this_field_name] = get_mysql_date_from_user_input($this_value);	
										} elseif(strpos($field_types[$this_field_name], 'float(') !== false || $field_types[$this_field_name] == 'double') {
											if (!empty($GLOBALS['database_fields_format']['time']) && in_array($this_field_name, $GLOBALS['database_fields_format']['time'])) {
												$field_values[$this_field_name] = get_time_from_user_input($this_value);
											} else {
												$field_values[$this_field_name] = get_float_from_user_input($this_value);
											}
										}
										
									/*
										pour pas prendre de risque on ne fait pas ça. mais structurellement ce serait bien. (problème si on rentre 3.5 pour dire 3h30 par exemple)
										 elseif(strpos($field_types[$this_field_name], 'int(') !== false) {
											$field_values[$this_field_name] = intval(get_float_from_user_input($this_value));
										}
									*/
									}
								}
							} 
							// On a lu une ligne du fichier, et on a fait la correspondance avec les noms de champs internes à PEEL pour pouvoir remplir proprement $field_values
							if (!empty($field_values)) {
								// PRET POUR LE TRAITEMENT de l'import d'une ligne
								// On a trouvé au moins un champ à importer

								// Vérifications générales avant les traitements spécifiques
								if(!empty($primary_key)) {
									// On gère les clés primaires qui doivent avoir été sélectionnées dans les colonnes à importer
									// (en cas de clé primaire composée de plusieurs colonnes, pas besoin non plus de remplir database_fields_required_array ou database_fields_required_by_table_array)
									foreach(explode(',', $primary_key) as $this_field_name) {
										if(empty($field_values[$this_field_name])) {
											if(!$primary_key_auto_increment) {
												$error_output_line_array[$this_field_name] = sprintf($GLOBALS['STR_ADMIN_PRIMARY_KEY_MANDATORY'], $this_field_name);
												$error_primary = true;
											}
										} else {
											// Gestion de l'unicité de la clé primaire simple ou multiple
											$primary_key_values[$this_field_name] = $field_values[$this_field_name];
										}
									}
									if(!$error_primary && !empty($primary_key_values)) {
										// On cherche si la ligne existe déjà dans la BDD
										$line_in_database_infos = get_table_rows($table_name, null, null, true, 1, null, false, create_sql_from_array($primary_key_values, ' AND '));
										/*if(!empty($line_in_database_infos)) {
											// comme on autorise les insertions et les mises à jour, on désactive ce bloc de code qui empêcherait l'utilisation de clé primaire existante dans le fichier d'import.
											$error_output_line_array[] = 'Le code "' . implode('" / "', explode(',', $primary_key)) . '" de "' . $table_name . '" existe déjà par ailleurs et ne peut pas être dupliqué.';
										}*/
									}
									if(!$line_in_database_infos) {
										// SEULEMENT SI c'est pour une insertion : Vérification que tous les champs obligatoires sont renseignés 
										foreach($GLOBALS['database_fields_by_type_array'][$type] as $this_field_name) {
											// On exclue les clés primaires ici, car gérées plus haut
											if(empty($primary_key) || !in_array($this_field_name, explode(',', $primary_key))) {
												if(get_field_required($this_field_name, $type) && (!isset($field_values[$this_field_name]) || $field_values[$this_field_name]=== null || $field_values[$this_field_name]==='')) {
													// Vérifie que les champs obligatoires sont bien remplis et non vide
													// On a géré les clé primaires plus haut
													$error_output_line_array[] = sprintf($GLOBALS['STR_ADMIN_FIELD_VALUE_MANDATORY'], $this_field_name);
												}
											}
										}
										if (!empty($GLOBALS['database_table_insert_forbidden_array'])) {
											if (in_array($table_name, $GLOBALS['database_table_insert_forbidden_array'])) {
												// on interdit l'insertion de données dans cette table
												$error_output_line_array[] = sprintf($GLOBALS['STR_ADMIN_INSERT_VALUE_FORBIDDEN'], $table_name);
											}
										}
									}
								}
								foreach($field_values as $this_field_name => $this_value) {
									$select_infos = get_select_infos($table_name, $this_field_name);
									if(!empty($select_infos)) {
										// On peut importer la clé, ou le titre d'un choix du select
										if(!isset($select_infos[$this_value])) {
											if(in_array($this_value, $select_infos, true)) {
												// On remplace le titre d'un choix par son code technique
												$field_values[$this_field_name] = $this_value = array_search($this_value, $select_infos, true);
											} else {
												// On explique que les clés ou les titres sont possibles
												$error_output_line_array[] = sprintf($GLOBALS['STR_ADMIN_COLUMN_CONTAINS_CHOICE'], get_field_title($this_field_name, $table_name, true, null, true), '"'.implode('" / "', array_keys($select_infos)) . '" ' . (array_keys($select_infos) != array_values($select_infos) ? $GLOBALS['STR_OR'] . ' "' . implode('" / "', $select_infos).'"' : ''));
											}
										}
									}
									
									if (!empty($field_maxlength_array[$this_field_name]) && StringMb::strlen($this_value)>$field_maxlength_array[$this_field_name]) {
										$error_output_line_array[] = sprintf($GLOBALS['STR_ADMIN_CODE_MAX_CHAR_REACHED'], $this_value, StringMb::strlen($this_value), $field_maxlength_array[$this_field_name]);
									}

									if($this_value && !in_array($this_field_name, array('CptCaracParam', 'CLT_TNF'))) {
										// on traite les champs de jointure avec une autre table
										$join_infos = get_join_infos($table_name, $this_field_name);
										if(!empty($join_infos)) {
											// Vérification des valeurs existantes dans les tables de référence (équivalent de CodeExiste en Delphi - ligne 2209 du fichier Delphi ufrmImportFichiers.pas)
											if($join_infos['target_field'] != 'Affaire' && !in_array($join_infos['target_table'], array('jourvent', 'budfact', 'clients'))) {
												// PS : On exclut les jointures sur l'affaire car jointure multiple gérée par ailleurs. On exclut la jointure sur table clients car gérée par ailleurs dans le hook import_line_pre (et notamment on peut vouloir rechercher d'abord le code client à partir du 411, donc on ne veut pas traiter ici). Et on exclut la vérification si jourvent ou budfact existe déjà car on va crééer dans le hook la ligne de facturation à partir du détail des lignes importées.
												// PS : Si clé primaire multiple avec la champ Affaire, on exclue du test ci-dessous car les erreurs dans les données sont gérées par ailleurs dans hook
												$sql_cond = word_real_escape_string($join_infos['target_field']) . '="' .real_escape_string($this_value). '"';
												$this_cache_id = md5($join_infos['target_table'] . $sql_cond);
												if(!array_key_exists($this_cache_id, $joined_table_infos)) {
													$joined_table_infos[$this_cache_id] = get_table_rows($join_infos['target_table'], null, null, true, 1, null, false, $sql_cond);
												}
												if(empty($joined_table_infos[$this_cache_id])) {
													$error_output_line_array[] = sprintf($GLOBALS['STR_ADMIN_VALUE_NOT_EXIST_IN_TABLE'], $this_value, $join_infos['target_table'], $join_infos['target_field']);
												} 
											}
										}
									}
									// Vérifie que les champs uniques (vides ou non) des colonnes importées sont bien uniques
									if(!$error_primary && get_field_unique($this_field_name, $type) && get_table_rows($table_name, null, null, true, 1, null, false, ' NOT (' . create_sql_from_array($primary_key_values, ' AND ') . ') AND ' . word_real_escape_string($this_field_name) . '="' .real_escape_string($this_value). '"')) {
										$error_output_line_array[] = sprintf($GLOBALS['STR_ADMIN_FIELD_UNIQUE'], $this_field_name);
									}
								}
								$error_output_line = implode(' ', $error_output_line_array);
								// On traite l'import de la ligne, soit ci-dessous, soit dans le hook juste après

								if($type == 'peel_produits') {
									// Mode admin à true pour create_or_update_product : 
									$output .= create_or_update_product($field_values, true, true, $test_mode);
								} elseif($type == 'peel_utilisateurs') {
									$output .= create_or_update_user($field_values, $test_mode);
								} else {
									if(!$error_primary) {
										// On gère les erreurs, comme ce qui est déjà fait pour datatables avec des hooks
										$params_list = array('type' => $type, 'table_values' => vb($field_values), 'field_infos' => $GLOBALS['database_field_infos_by_type_array'][$type], 'ordered_fields_selected' => $params['ordered_fields_selected'], 'existing_row' => $line_in_database_infos, 'test_mode' => $test_mode);
										// 4ème paramètre à true pour pouvoir utiliser $params_list en référence.
										$hook_result = call_module_hook('import_line_pre', $params_list, 'string', true);

										$error_output_line .= (!empty($hook_result['errors'])?' ':'') . $hook_result['errors'];
										$field_values = $hook_result['table_values'];


										$set_field_values = $field_values; // On ne va garder dans $set_field_values que les champs qui ne sont pas primaires
										foreach($primary_key_values as $this_field_name => $this_field_value) {
											// On MAJ les clés primaires à partir des field_values qui leur correspondent, au cas où un retraitement est fait dans le hook import_line_pre
											if(!empty($set_field_values[$this_field_name])){
												$primary_key_values[$this_field_name] = $set_field_values[$this_field_name];
											}
											unset($set_field_values[$this_field_name]);
										}
										if(!empty($real_table_field_names)) {
											// On ne garde que les champs qui existent réellement dans la table
											foreach(array_keys($set_field_values) as $this_field_name) {
												if(!in_array($this_field_name, $real_table_field_names)) {
													unset($set_field_values[$this_field_name]);
												}
											}
										}
										// Gérer la création ou la mise à jour
										$set_sql = create_sql_from_array($set_field_values);																					

										if(!empty($test_mode)) {
											// En mode simulation, on va faire le traitement post dans tous les cas pour récupérer les messages d'erreur
											$import_this_line = true;
										} elseif(empty($error_output_line)) {
											// En mode normal, on ne traite la ligne que si pas d'erreur
											$import_this_line = true;
										} else {
											$import_this_line = false;
										}
										if($import_this_line) {
											if ($line_in_database_infos) {
												if(empty($test_mode)) {
													$sql = 'UPDATE ' . word_real_escape_string($table_name) . ' 
														SET ' . $set_sql . '
														WHERE ' . create_sql_from_array($primary_key_values, ' AND ');
													query($sql);
												}
												$GLOBALS['nb_update']++;
											} else {
												if(empty($test_mode)) {
													if(!empty($primary_key_values)) {
														// On rajoute les champs primaires
														$set_sql .= ', ' . create_sql_from_array($primary_key_values, ', ');
													}

													query('INSERT INTO ' . word_real_escape_string($table_name) . '
														SET ' . $set_sql . '');
												}
												$GLOBALS['nb_insert']++;
											}
											if(empty($test_mode)) {
												// On fait aussi des traitements dans les données après mise à jour, comme le fait de mettre des codes en majuscules, etc
												$this_result_error = call_module_hook('import_line_post', array('type' => $type, 'field_values' => vb($field_values), 'field_infos' => $GLOBALS['database_field_infos_by_type_array'][$type], 'primary_key_values' => $primary_key_values, 'existing_row' => $line_in_database_infos, 'ordered_fields_selected' => $params['ordered_fields_selected'], 'test_mode' => $test_mode), 'string');
												if(!empty($this_result_error)) {
													if(!empty($error_output_line)) {
														$error_output_line .= ' ';
													}
													$error_output_line .= $this_result_error;
												}
											}
										}
									}
								}
								// Affichage du message de succès
								//$output_line .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => 'OK !'))->fetch();
								if(!empty($error_output_line)) {
									$error_output[] = $GLOBALS["STR_ADMIN_LINE"] . ' ' . $GLOBALS['line_number'] . $GLOBALS["STR_BEFORE_TWO_POINTS"] . ': ' . $error_output_line;
								}
							}
						}
						call_module_hook('import_post', array('type' => $type, 'test_mode' => $test_mode), 'string');
					} else {
						$error_output[] = 'ATTENTION : Problème apparemment dans ce fichier - Donc pas d\'action effectuée';
					}
					fclose($fp);
					if(empty($error_output) || !empty($force_import_despite_of_error)) {
						if(!empty($test_mode)) {
							$output .= '<b>'.StringMb::strtoupper($GLOBALS['STR_ADMIN_SIMULATION']).'</b> : ';
						}
						if($type == 'peel_produits') {
							$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_MSG_IMPORTATION_OK'], vn($GLOBALS['nbprod_insert']) + vn($GLOBALS['nbprod_update']) + vn($GLOBALS['nbprod_update_null']), vn($GLOBALS['nbprod_update']), vn($GLOBALS['nbprod_update_null']), vn($GLOBALS['nbprod_insert']), vn($GLOBALS['nbprod_categorie_insert']))))->fetch();
						} elseif($type == 'peel_utilisateurs') {
							$output .= sprintf($GLOBALS['STR_ADMIN_IMPORT_USER_END'], vn($GLOBALS['nb_insert']),vn($GLOBALS['nb_update'])).'<br /><br />';
						} else {
							$output .= sprintf($GLOBALS['STR_ADMIN_IMPORT_FILE_END'], vn($GLOBALS['nb_insert']),vn($GLOBALS['nb_update'])).'<br /><br />';
						}
					}
				} else {		
					$error_output[] = sprintf('Ouverture impossible de %s', $params['import_file']);
				}
			}
			$next_mode = 'import';
			if(!empty($test_mode) && empty($error_output)) {
				$output .= '<p>'.$GLOBALS["STR_ADMIN_NO_PROBLEM_OCCURED"].'</p>'; 
				// On va mettre test_mode à 1 dans le .tpl
			} else {
				// On va mettre un bouton pour revenir en arrière dans le .tpl
			}
			break;

		default:
			$next_mode = 'import';
			break;
	}
	// FORMULAIRE DE CHOIX D'IMPORTATION
	// 1ère étape : formulaire brut
	// OU Seconde étape : on affiche les anomalies et on demande de valider ou retour en arrière
	// OU Troisième étape : affichage des informations sur import réellement réalisé
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_import_form.tpl');
	if(!empty($params['import_file'])) {
		// On crée un titre pour afficher à l'utilisateur le nom du fichier utilisé
		$tpl->assign('import_file', get_uploaded_file_infos('import_file', $params['import_file'],'javascript:reinit_upload_field("import_file","[DIV_ID]");'));
	}
	if(!empty($params['defaults'])) {
		// A FAIRE : on pourrait faire une gestion générique d'autocomplete pour les champs par défaut si ils sont liés à des jointures
		$tpl->assign('defaults', $params['defaults']);
	}

	$tpl->assign('rules_array', get_import_export_saved_configuration_names('import'));

	$tpl->assign('action', get_current_url(true));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . 'import'));
	$tpl->assign('types_array', $GLOBALS['database_import_export_type_names_array']);
	$tpl->assign('inputs', get_database_field_properties($params));
	$tpl->assign('uploaddir', $GLOBALS['uploaddir']);
	$tpl->assign('data_encoding', $params['data_encoding']);
	$tpl->assign('import_output', $output);
	$tpl->assign('mode', $mode);
	$tpl->assign('next_mode', $next_mode);
	$tpl->assign('force_import_despite_of_error', vb($force_import_despite_of_error));
	$tpl->assign('error', implode('<br />', $error_output));
	$tpl->assign('type', vb($type));
	if($mode == '' || empty($general_configuration_is_valid)) {
		if(!empty($type) || !empty($params['import_file'])) {
			$tpl->assign('type_disabled', '');
			if(!empty($type)) {
				$GLOBALS['js_ready_content_array'][] = 'change_import_type();';
			}
		} else {
			$tpl->assign('type_disabled', ' disabled="disabled"');
		}
	}
	$tpl->assign('default_fields', vb($params['default_fields']));
	$tpl->assign('correspondance', vb($params['correspondance']));
	$tpl->assign('test_mode', vb($test_mode));
	$tpl->assign('general_configuration_is_valid', !empty($general_configuration_is_valid));
	$tpl->assign('separator', str_replace("\t", '\t', $params['separator']));
	$tpl->assign('example_href', $GLOBALS['administrer_url'] . '/import/exemple_prod.csv');
	$tpl->assign('site_name', $GLOBALS['site']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_SEND_EMAIL_TO_USERS', $GLOBALS['STR_ADMIN_SEND_EMAIL_TO_USERS']);
	$tpl->assign('STR_ADMIN_CORRESPONDANCE_COLUMN_FILE_AND_SITE', sprintf($GLOBALS['STR_ADMIN_CORRESPONDANCE_COLUMN_FILE_AND_SITE'], $GLOBALS['site']));
	$tpl->assign('STR_ADMIN_DEFAULT_VALUE', $GLOBALS['STR_ADMIN_DEFAULT_VALUE']);
	$tpl->assign('STR_ADMIN_IMPORTED_COLUMN', $GLOBALS['STR_ADMIN_IMPORTED_COLUMN']);
	$tpl->assign('STR_ADMIN_TYPE', $GLOBALS['STR_ADMIN_TYPE']);
	$tpl->assign('STR_ADMIN_SITE_COLUMN_IN_DATABASE', sprintf($GLOBALS['STR_ADMIN_SITE_COLUMN_IN_DATABASE'], $GLOBALS['site']));
	$tpl->assign('STR_ADMIN_IMPORT_MANDATORY_FIELD_INFORMATION_MESSAGE', $GLOBALS['STR_ADMIN_IMPORT_MANDATORY_FIELD_INFORMATION_MESSAGE']);
	$tpl->assign('STR_ADMIN_MOVE_COLUMN_WITH_DRAG_DROP', $GLOBALS['STR_ADMIN_MOVE_COLUMN_WITH_DRAG_DROP']);
	$tpl->assign('STR_ADMIN_SOURCE_FILE', $GLOBALS['STR_ADMIN_SOURCE_FILE']);
	$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
	$tpl->assign('STR_ADMIN_CHECK_DATA', $GLOBALS['STR_ADMIN_CHECK_DATA']);
	$tpl->assign('STR_ADMIN_CHECK_DATA_BEFORE_IMPORT', $GLOBALS['STR_ADMIN_CHECK_DATA_BEFORE_IMPORT']);
	$tpl->assign('STR_ADMIN_LINE_WITH_ERROR_NOT_IMPORT', $GLOBALS['STR_ADMIN_LINE_WITH_ERROR_NOT_IMPORT']);
	$tpl->assign('STR_ADMIN_IMPORT_TYPE', $GLOBALS['STR_ADMIN_IMPORT_TYPE']);
	$tpl->assign('STR_ADMIN_IMPORT_CORRESPONDANCE', $GLOBALS['STR_ADMIN_IMPORT_CORRESPONDANCE']);
	$tpl->assign('STR_ADMIN_IMPORT_FORM_TITLE', $GLOBALS['STR_ADMIN_IMPORT_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_IMPORT_FILE_FORMAT', $GLOBALS['STR_ADMIN_IMPORT_FILE_FORMAT']);
	$tpl->assign('STR_ADMIN_IMPORT_FILE_FORMAT_EXPLAIN', $GLOBALS['STR_ADMIN_IMPORT_FILE_FORMAT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_IMPORT_FILE_EXAMPLE', $GLOBALS['STR_ADMIN_IMPORT_FILE_EXAMPLE']);
	$tpl->assign('STR_ADMIN_IMPORT_IMPORT_MODE', $GLOBALS['STR_ADMIN_IMPORT_IMPORT_MODE']);
	$tpl->assign('STR_ADMIN_IMPORT_IMPORT_ALL_FIELDS', $GLOBALS['STR_ADMIN_IMPORT_IMPORT_ALL_FIELDS']);
	$tpl->assign('STR_ADMIN_IMPORT_IMPORT_SELECTED_FIELDS', $GLOBALS['STR_ADMIN_IMPORT_IMPORT_SELECTED_FIELDS']);
	$tpl->assign('STR_ADMIN_IMPORT_SELECT_FIELDS', $GLOBALS['STR_ADMIN_IMPORT_SELECT_FIELDS']);
	$tpl->assign('STR_WARNING', $GLOBALS['STR_WARNING']);
	$tpl->assign('STR_ADMIN_IMPORT_EXPLAIN', $GLOBALS['STR_ADMIN_IMPORT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_IMPORT_WARNING_ID', $GLOBALS['STR_ADMIN_IMPORT_WARNING_ID']);
	$tpl->assign('STR_ADMIN_IMPORT_FILE_NAME', $GLOBALS['STR_ADMIN_IMPORT_FILE_NAME']);
	$tpl->assign('STR_ADMIN_IMPORT_FILE_ENCODING', $GLOBALS['STR_ADMIN_IMPORT_FILE_ENCODING']);
	$tpl->assign('STR_ADMIN_IMPORT_SEPARATOR', $GLOBALS['STR_ADMIN_IMPORT_SEPARATOR']);
	$tpl->assign('STR_ADMIN_IMPORT_SEPARATOR_EXPLAIN', $GLOBALS['STR_ADMIN_IMPORT_SEPARATOR_EXPLAIN']);
	$tpl->assign('STR_ADMIN_IMPORT_STATUS', $GLOBALS['STR_ADMIN_IMPORT_STATUS']);
	$tpl->assign('STR_INIT_FILTER', $GLOBALS['STR_INIT_FILTER']);
	$tpl->assign('STR_LOAD_RULES', $GLOBALS['STR_LOAD_RULES']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_SAVE_RULES', $GLOBALS['STR_SAVE_RULES']);
	$tpl->assign('STR_CONTINUE_WITH_ERROR', $GLOBALS['STR_CONTINUE_WITH_ERROR']);
	$tpl->assign('STR_CONTINUE_WITH_ERROR_CONFIRM', $GLOBALS['STR_CONTINUE_WITH_ERROR_CONFIRM']);
	$tpl->assign('STR_BACK', $GLOBALS['STR_BACK']);
	$tpl->assign('STR_FILE', $GLOBALS['STR_FILE']);
	$tpl->assign('STR_VALIDATE', $GLOBALS['STR_VALIDATE']);
	$tpl->assign('STR_ADMIN_IMPORT_CORRESPONDANCE_EXPLANATION', $GLOBALS['STR_ADMIN_IMPORT_CORRESPONDANCE_EXPLANATION']);
	$tpl->assign('STR_ADMIN_IMPORT_SAVE_IMPORT_PARAMS', $GLOBALS['STR_ADMIN_IMPORT_SAVE_IMPORT_PARAMS']);
	$hook_result = call_module_hook('import_form_template_data', array(), 'array');
	foreach($hook_result as $this_key => $this_value) {
		$tpl->assign($this_key, $this_value);
	}
	return $tpl->fetch();
}

/**
 * Création ou MAJ d'un utilisateur
 *
 * @param array $field_values
 * @param boolean $test_mode
 * @return
 */
function create_or_update_user($field_values, $test_mode = false) {
	$output = '';
	if(!isset($GLOBALS['nb_insert'])) {
		$GLOBALS['nb_insert'] = 0;
		$GLOBALS['nb_update'] = 0;
	}
	// Nettoyage des données
	foreach($field_values as $this_key => $this_field) {
		// On retire les mentions dans le fichier qui ne sont pas significatives
		if(in_array($this_field, array('-', '?'))) {
			$field_values[$this_key] = '';
		}
	}
	// traitement spécifique enligne
	if (!empty($field_values['enligne']) && $field_values['enligne'] != 'OK') {
		$field_values['enligne'] = ($field_values['enligne'] == 'yes' ? 'OK':'NO');
	}
	if (!empty($field_values['gps'])) {
		// Données GPS latitude et longitude à convertir pour mettre dans les bonnes colonnes
		$gps = explode(',', $field_values['gps']);
		$field_values['latitude'] = trim(get_float_from_user_input($gps[0]));
		$field_values['longitude'] = trim(get_float_from_user_input($gps[1]));
		$field_values['address_hash'] = 'zz'; // Valeur particulière pour dire que la géolocalisation est forcée => ne sera pas regéocodé par le cron du module maps
	}
	// On rend priv cohérent et propre, et on rajoute $GLOBALS['users_imported_basic_right'] si demandé
	if(isset($field_values['priv'])) {
		$field_values['priv'] = explode('+', str_replace(',', '+', $field_values['priv']));
	}
	if(!empty($GLOBALS['users_imported_basic_right'])) {
		if(!isset($field_values['priv'])) {
			$field_values['priv'] = array();
		}
		$field_values['priv'][] = $GLOBALS['users_imported_basic_right'];
	}
	if(!empty($field_values['priv'])) {
		$field_values['priv'] = implode('+', array_unique($field_values['priv']));
	}
	// On ne garde que le dernier email d'une liste
	if(!empty($field_values['email'])) {
		$temp = explode(';', str_replace(',', ';', $field_values['email']));
		$field_values['email'] = trim(end($temp));
	}
	$field_values['pseudo'] = str_replace(array('@', ' ', '.', '&'), '_', vb($field_values['pseudo']));
	$field_values['code_client'] = $field_values['pseudo'];
	$field_values['email_bounce'] = (!empty($field_values['email'])?'':'5.X.X migrated');
	if(!empty($field_values['email']) && !EmailOK($field_values['email'])){
		unset($field_values['email']);
	}
	if(empty($field_values['email']) && empty($field_values['nom_famille']) && empty($field_values['societe'])) {
		return null;
	}
	if(!empty($field_values['telephone'])) {
		$field_values['telephone'] = str_ireplace(array('T : ', 'Tél. : ', 'Tél : ', 'Tel : ', 'Tel: ', 'Stdd : '), '', $field_values['telephone']);
	}
	$address_array = array();
	foreach(array('adresse', 'adresse1', 'adresse2') as $this_key) {
		if(!empty($field_values[$this_key])) {
			$address_array[] = $field_values[$this_key];
		}
	}
	$field_values['adresse'] = implode(' ', $address_array);
	if(!empty($field_values['pays']) && !is_numeric($field_values['pays'])) {
		$field_values['pays'] = get_country_id($field_values['pays']);
	}
	$insert_field_values = $field_values;
	// On complète les données si nécessaire, uniquement pour l'insertion
	// date_insert et date_insert sont gérés dans insere_utilisateur et maj_utilisateur
	if(empty($insert_field_values['etat'])) {
		// on importe des comptes qu'on active par défaut
		$insert_field_values['etat'] = '1';
	}
	if(empty($insert_field_values['priv'])) {
		$insert_field_values['priv'] = 'newsletter';
		$send_user_confirmation = false;
		$create_password_on_behalf_of_user = false;
	} else {
		// si on créer un vrai utilisateur, il faut envoyer le mot de passe par email
		$send_user_confirmation = true;
		$create_password_on_behalf_of_user = true;
	}
	// On cherche l'utilisateur et on le crée si nécessaire. Si l'utilisateur existe déjà, on renvoie true, et si création alors on renvoie l'id (ou 1 si test_mode = true)
	$this_result = insere_utilisateur($insert_field_values, true, $send_user_confirmation, false, false, $create_password_on_behalf_of_user, $test_mode);
	if(is_numeric($this_result)) {
		if(!$test_mode) {
			$output .= 'OK : Utilisateur créé n°' . $this_result . ' - email : ' . $field_values['email'] . ' <br />';
		}
		$GLOBALS['nb_insert']++;
	} else {
		if(!empty($GLOBALS['user_insert_existing_user'])) {
			// Utilisateur existe déjà
			$field_values['id_utilisateur'] = $this_result['id_utilisateur'];
			unset($field_values['date_insert']);
			unset($field_values['pays']);
			unset($field_values['etat']);
			if(strpos($this_result['priv'], 'admin') !== false) {
				// On ne modifie pas les droits si admin
				unset($field_values['priv']);
			}
			maj_utilisateur($field_values, false, $test_mode);
			unset($GLOBALS['user_insert_existing_user']);
			if(!$test_mode) {
				$output .= 'OK : Utilisateur MAJ  n°' . $this_result['id_utilisateur'] . ' - email : ' . $field_values['email'] . '<br /> ';
			}
			$GLOBALS['nb_update']++;
		} else {
			if(!$test_mode) {
				$output .= 'NOK : Utilisateur déjà existant pas modifié - email : ' . $field_values['email'] . '<br />';
			}
		}			
	}
	return $output;
}

/**
 * Gestion de l'export
 *
 * @param boolean $check_access_rights
 * @param array $params
 * @return
 */
function handle_export($check_access_rights = true, $params = array()) {

	$output = import_export_init('export', $params);

	$error_output = '';
	
	$type = $params['type'];
	if($check_access_rights) {
		if(empty($type)) {
			necessite_priv("admin*");
		} else {
			if(empty($GLOBALS['database_user_rights_by_type_array'][$type])) {
				// On autorise si pas de contrainte d'accès
				// die();
			} else {
				necessite_priv($GLOBALS['database_user_rights_by_type_array'][$type]);
			}
		}			
	}
	// Format généré : CSV, HTML ou PDF
	// On donne priorité à format dans GET pour permettre notamment des tests
	$params['format'] = vb($_GET['format'], vb($params['format'], 'csv'));
	$format = $params['format'];
	$mode = vb($params['mode']);
	if($mode == 'export' && in_array($type, array('ventes', 'one_line_per_product', 'one_line_per_order', 'ventes_chronopost', 'ventes_cegid')) && !empty($_GET['dateadded1']) && !empty($_GET['dateadded2'])) {
		// export direct à partir d'un lien
		$params['ordered_fields_selected'] = $GLOBALS['database_fields_by_type_array'][$type];
	}
	if (empty($type)) {
		$table_name = null;
	} else {
		$table_name = vb($GLOBALS['database_import_export_table_by_type_array'][$type], $type);
	}
	if (empty($params['ordered_fields_selected']) && $mode == 'export' && (empty($GLOBALS['export_domains_for_compta']) || empty($params['domain']) || !in_array($params['domain'], $GLOBALS['export_domains_for_compta']))) {
		// Il n'y a pas de choix de colonne pour l'export en comptabilité
		$mode = '';
		$error_output .= $GLOBALS['STR_ADMIN_CHOOSE_COLUMN'];
	} elseif ($type == "ventes_clients_par_produit" && empty($_GET['id']) && $mode == 'export') {
		$mode = '';
		$error_output .= $GLOBALS['STR_ADMIN_PRODUCT_ID_MANDATORY_IN_URL'];
	}
	switch ($mode) {
		case "export":
			$next_mode = 'export';
			// PREAMBULE : FAIRE VERIFICATIONS DIVERSES
			if(empty($GLOBALS['database_import_export_type_names_array'][$type])) {
				// L'utilisateur n'a pas le droit d'importer dans la table demandée
				$error_output .= sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']));
				break;
			}

		/*
			// Morceau de code désactivé, la gestion de l'export dans le fichier export.php est différente de celle faite ici
			// Pas de problème
			if(get_current_url(false, true) != '/modules/export/administrer/export.php') {
				// On appelle la génération de l'export de l'extérieur du module, donc on redirige pour la génération
				// On ne redirige que lorsqu'on a fait toutes les vérifications en amont, pour rester en dehors du module export si on le souhaite pour l'interface
				redirect_and_die(get_url($GLOBALS['wwwwroot'] . '/modules/export/export.php', array('type' => $type, 'mode' => $mode, 'format' => $format, 'separator' => $params['separator'], 'data_encoding' => $data_encoding)));
			} 
		*/

			// On va procéder à l'export :
			// ETAPE 1 : on va remplir les lignes de données à partir de la BDD, et de calculs
			// ETAPE 2 : on prendra uniquement les champs qu'on veut et dans le bon ordre, en mettant au bon format d'export
			$results_array = array();

			if(empty($_GET['debug'])) {
				// On ne veut pas polluer le fichier exporté par un quelconque message d'erreur
				@ini_set('display_errors', 0);
			}
			if($type == 'peel_utilisateurs') {
				// CLIENTS
				$cle = trim(vb($_GET['cle']));
				$priv = trim(vb($_GET['priv']));
				if (!empty($_GET['export']) && $_GET['export'] == 'search_user') {
					$sql_csv = afficher_liste_utilisateurs($priv, $cle, $_GET, 'date_insert', false, true);
				} else {
					$sql_csv = "SELECT u.*
						FROM peel_utilisateurs u
						WHERE " . get_filter_site_cond('utilisateurs', 'u', true) . "";
					if (!empty($_GET['priv'])) {
						$sql_csv .= " AND CONCAT('+',u.priv,'+') LIKE '%+" . nohtml_real_escape_string($_GET['priv']) . "+%'";
					}
					if (!empty($cle)) {
						$sql_csv .= " AND (u.code_client LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.email LIKE '%" . nohtml_real_escape_string($cle) . "%' OR u.ville LIKE  '%" . nohtml_real_escape_string($cle) . "%' OR u.nom_famille LIKE  '%" . nohtml_real_escape_string($cle) . "%' OR " . get_zip_cond($cle, 'u', false) . ")";
					}
				}	
				// **********
			} elseif($type == 'livraisons') {
				// LIVRAISONS
				if (empty($params["dateadded1"]) || empty($params["dateadded2"])) {
					$error_output .= 'Dates non définies';
				} else {
					if (!empty($params["id_statut_livraison"])) {
						$extra_sql = "AND id_statut_livraison = '" . intval($_GET["id_statut_livraison"]) . "'";
					} else {
						$extra_sql = "";
					}
					$sql_csv = "SELECT *, poids*quantite AS poids_calc, CONCAT('reference', ' - ', nom_produit) AS article_calc
						FROM peel_commandes c
						LEFT JOIN peel_commandes_articles ca ON ca.commande_id = c.id AND quantite!=0 AND " . get_filter_site_cond('commandes_articles', null, true) . "
						WHERE o_timestamp>='" . nohtml_real_escape_string($params["dateadded1"]) . "' AND o_timestamp<='" . nohtml_real_escape_string($params["dateadded2"]) . "' AND " . get_filter_site_cond('commandes', null, true) . " " . $extra_sql . "
						ORDER BY o_timestamp";
					// **********
				}
			} elseif ($type == "ventes_clients_par_produit") {
				// Export des clients qui ont acheté le produit $_GET['id']
				// Format du nom de fichier adapté pour Cegid, utilisé dans tous les cas
				$filename = "jdv_" . StringMB::strtolower(StringMb::substr(date('F', time()),0,4)).date('y', time()) . ".csv";
				$results_array = affiche_liste_clients_par_produit($_GET['id'], true);
				// **********
			} elseif(in_array($type, array('ventes', 'one_line_per_product', 'one_line_per_order', 'ventes_chronopost', 'ventes_cegid'))) {
				// Export des ventes selon un filtre de date et de statut de paiement
				if (empty($params["dateadded1"]) || empty($params["dateadded2"])) {
					$error_output .= 'Dates non définies';
				} else {
					if (!empty($params["id_statut_paiement"])) {
						$extra_sql = "AND id_statut_paiement = '" . intval($params["id_statut_paiement"]) . "'";
					} else {
						$extra_sql = "";
					}
					if (!empty($_POST['export_selected_order']) && !empty($_POST['order_id'])) {
						$sql_cond = "AND c.id IN (".implode(',', nohtml_real_escape_string($_POST['order_id'])).")";
					} else {
						$sql_cond = "AND c." . word_real_escape_string($params["date_field"]) . ">='" . nohtml_real_escape_string($params["dateadded1"]) . "' AND c." . word_real_escape_string($params["date_field"]) . "<='" . nohtml_real_escape_string($params["dateadded2"]) . "' " . $extra_sql . "";
					}
					$sql_csv = "SELECT *
						FROM peel_commandes c
						WHERE " . get_filter_site_cond('commandes', 'c', true) . " " . $sql_cond . " 
						ORDER BY c." . word_real_escape_string(vb($params["date_field"], 'o_timestamp')) . "";
					$total_cout_transport = 0;
					$total_cout_transport_ht = 0;
					$total_ht = 0;
					$total_tva = 0;
					$total = 0;
					$total_netapayer = 0;
					$total_total_produit_ht = 0;
					$total_total_produit = 0;
					$total_tarif_paiement_ht = 0;
					$total_tarif_paiement = 0;
					$query_csv = query($sql_csv);
					unset($sql_csv);
					$output_array = array();
					while ($commande = fetch_assoc($query_csv)) {
						if($type == 'one_line_per_product') {
							// Export simplifié avec liste des noms de produits et quantité
							$product_infos_array = get_product_infos_array_in_order($commande['id'], $commande['devise'], $commande['currency_rate']);
							foreach ($product_infos_array as $this_ordered_product) {
								if (!isset($output_array[$this_ordered_product['nom_produit']])) {
									$output_array[$this_ordered_product['nom_produit']] = 0;
								}
								$output_array[$this_ordered_product['nom_produit']] += $this_ordered_product['quantite'];
							}
							if(!empty($output_array)) {
								foreach($output_array as $product_name => $total_quantity) {
									$results_array[] = array('product_name' => $product_name, 'quantity_by_product' => intval($total_quantity));
								}
								unset($output_array);
							} 
						} else {
							// Préparation des données complémentaires à la commande
							$commande['numero'] = $commande['id'];
							if (!empty($commande['numero'])) {
								$commande['numero_facture'] = $commande['numero'];
							} else {
								$commande['numero_facture'] = $commande['id'];
							}

							$commande['date_vente'] = get_formatted_date($commande['o_timestamp'], 'short', 'long');
							$commande['date_achat'] = get_formatted_date($commande['a_timestamp'], 'short', 'long');

							// Compatibilité avec anciens PEEL : décodage des entités HTML stockées en BDD
							// Pas besoin de protégé du séparateur CSV, car on applique filtre_csv tout à la fin
							$commande['nom_bill'] = StringMb::htmlspecialchars_decode($commande['nom_bill'], ENT_QUOTES);
							$commande['societe_bill'] = StringMb::htmlspecialchars_decode($commande['societe_bill'], ENT_QUOTES);
							$commande['adresse_bill'] = StringMb::htmlspecialchars_decode($commande['adresse_bill'], ENT_QUOTES);
							$commande['ville_bill'] = StringMb::htmlspecialchars_decode($commande['ville_bill'], ENT_QUOTES);
							$commande['pays_bill'] = StringMb::htmlspecialchars_decode($commande['pays_bill'], ENT_QUOTES);

							$commande['montant_avec_avoir'] = $commande['montant']+$commande['avoir'];
							$commande['tva_produit'] = $commande['total_produit']-$commande['total_produit_ht'];

							$total_cout_transport += $commande['cout_transport'];
							$total_cout_transport_ht += $commande['cout_transport_ht'];
							$total_tva += $commande['total_tva'];
							$total_ht += $commande['montant_ht'];
							$total += $commande['montant']+$commande['avoir'];
							$total_netapayer += $commande['montant'];

							$commande['vat_arrays'][] = get_vat_array($commande['code_facture']);

							if (in_array('peel_transactions', listTables()) && in_array('reglements', $params['ordered_fields_selected'], true)) {
								// La variable de règlements est à mettre dans $GLOBALS['site_parameters']['export_order_custom_field']. Elle sera utilisée dans $$this_var plus bas dans le code.
								// Récupération des informations de règlement;
								$sql = "SELECT id, AMOUNT, datetime, type AS payment_technical_code
									FROM peel_transactions
									WHERE ORDER_ID = ".intval($commande['id']);
								$query = query($sql);
								$reglement_array = array();
								while ($result = fetch_assoc($query)) {
									$reglement_array[] = '' . get_formatted_date($result['datetime']) . $GLOBALS["STR_BEFORE_TWO_POINTS"] . ': ' . get_payment_name($result['payment_technical_code']) . ' - ' . $GLOBALS['STR_AMOUNT'] . $GLOBALS["STR_BEFORE_TWO_POINTS"] . ': ' . fprix($result['AMOUNT'], true, $commande['devise'], true, $commande['currency_rate']) . '';
								}
								$commande['reglements'] = implode('#', $reglement_array);
							}
							// Calculs pour le footer
							$product_infos_array = get_product_infos_array_in_order($commande['id'], $commande['devise'], $commande['currency_rate']);
							foreach ($product_infos_array as $this_ordered_product) {
								$ligne_total_produit_ht += $this_ordered_product['total_prix_ht'];
								$ligne_total_produit_ttc += $this_ordered_product['total_prix'];
							}

							if ($type == 'one_line_per_order') {
								// Une ligne par commande, avec des sommes sur les produits concernés
								$results_array[] = array('id' => intval($commande['id']), 'numero' => $commande['order_id'], 'o_timestamp' => $commande['date_vente'], 'nom_bill' => $commande['nom_bill'], 'societe_bill' => $commande['societe'], 'adresse_bill' => $commande['adresse'], 'ville_bill' => $commande['ville'], 'zip_bill' => $commande['code_postal'], 'pays_bill' => $commande['pays'], 'montant_ht' => fxsl($this_ordered_product['total_prix_ht']), 'tva_percent' => fxsl($this_ordered_product['tva_percent']), 'montant_avec_avoir' => fxsl($commande['montant_avec_avoir']), 'avoir' => fxsl($commande['avoir']), 'montant' => fxsl($commande['montant']+$commande['avoir']), 'cout_transport_ht' => fxsl($cout_transport_ht), 'tva_cout_transport' => fxsl($commande['cout_transport']-$commande['cout_transport_ht']), 'cout_transport' => fxsl($commande['cout_transport']), 'tarif_paiement_ht' => fxsl($commande['tarif_paiement_ht']), 'tva_tarif_paiement' => fxsl($commande['tarif_paiement']-$commande['tarif_paiement_ht']), 'tarif_paiement' => fxsl($commande['tarif_paiement']), 'paiement' => $commande['paiement'], 'total_produit_ht' => fxsl($commande['total_produit_ht']), 'tva_total_produit' => fxsl($commande['tva_total_produit']), 'total_produit' => fxsl($commande['total_produit']));
							} elseif($type == "ventes_chronopost") {
								// Une ligne par commande
								$results_array[] = array('unused1' => '', 'societe' => $commande['societe_ship'], 'nom' => $commande['nom_ship'], 'prenom' => $commande['prenom_ship'], 'adresse' => $commande['adresse_ship'], 'unused2' => '', 'unused3' => '',  'unused4' => '', 'zip' => $commande['zip_ship'], 'ville' => $commande['ville_ship'], 'pays' => get_country_iso_2_letter_code($commande['pays_ship']), 'telephone' => $commande['telephone_ship'], 'email' => $commande['email_ship'], 'id' => $commande['id'], 'unused5' => '', 'unused6' => '', 'export_default_product' => vn($GLOBALS['site_parameters']['order_chronopost_export_default_product']), 'contract_number' => vn($GLOBALS['site_parameters']['chronopost_contract_number']), 'sub_account_contract_number' => vn($GLOBALS['site_parameters']['chronopost_sub_account_contract_number']), 'unused7' => '', 'unused8' => '', 'M' => 'M', 'unused9' => '', 'unused10' => '', 'unused11' => '', 'total_poids' => $commande['total_poids'], 'unused12' => '', 'unused13' => '', 'unused14' => '', 'unused15' => '', 'unused16' => '', 'delivery_date' => date('d-m-Y', strtotime($commande['o_timestamp']) + 3600*24*vn($GLOBALS['site_parameters']['order_date_delivery_delay'],2)), 'unused17' => '', 'unused18' => '', 'unused19' => '', 'unused20' => '', 'unused21' => '', 'unused22' => '', 'unused23' => '', 'unused24' => '');
							} elseif($type == "ventes" || $type == "ventes_cegid") {
								// On affiche sur la première ligne d'une commande le premier produit avec les infos générales de coût de la commande, puis sur les lignes suivantes les produits suivants de la commande sans indication de coût de commande
								$i = 0;
								foreach ($product_infos_array as $this_ordered_product) {
									if ($this_ordered_product['quantite'] != 0) {
										// On affiche le coût de transport et de transaction uniquement sur la ligne du premier produit commandé
										$cout_transport = ($i == 0) ? $commande['cout_transport'] : "";
										$cout_transport_ht = ($i == 0) ? $commande['cout_transport_ht'] : "";
										$tva_cout_transport = $cout_transport - $cout_transport_ht;
										$tarif_paiement = ($i == 0) ? $commande['tarif_paiement'] : "";
										$tarif_paiement_ht = ($i == 0) ? $commande['tarif_paiement_ht'] : "";
										$tva_tarif_paiement = $tarif_paiement - $tarif_paiement_ht;

										$total_total_produit += $this_ordered_product['total_prix'];
										$total_total_produit_ht += $this_ordered_product['total_prix_ht'];
										$total_tarif_paiement += $tarif_paiement;
										$total_tarif_paiement_ht += $tarif_paiement_ht;
										$total_cout_transport_ht += $cout_transport_ht;
										$total_cout_transport += $cout_transport;
										
										$i++;
										if ($type == "ventes_cegid") {
											$first_reference_caractere = StringMb::substr($this_ordered_product['reference'],0,1);
											$general = ($first_reference_caractere == 0)?'706100':'70710'.$first_reference_caractere;
											$results_array[] = array('journal' => "VEN", 'date' => get_formatted_date($commande['f_datetime'], 'short'), 'general' => $general, 'auxiliaire' => '', 'reference' => intval($commande['numero_facture']), 'libelle' => $commande['nom_bill'], 'credit' => fxsl($this_ordered_product['total_prix_ht']+$this_ordered_product['total_prix_attribut_ht']), 'debit' => '');
										} else {
											// Ici : $type == "ventes"
											$results_array[] = array('id' => intval($commande['id']), 'numero' => $commande['order_id'], 'o_timestamp' => $commande['date_vente'], 'nom_bill' => $commande['nom_bill'], 'societe_bill' => $commande['societe'], 'adresse_bill' => $commande['adresse'], 'ville_bill' => $commande['ville'], 'zip_bill' => $commande['code_postal'], 'pays_bill' => $commande['pays'], 'nom_produit' => $this_ordered_product['nom_produit'], 'quantite' => $this_ordered_product['quantite'], 'prix_ht' => fxsl($this_ordered_product['prix_ht']), 'montant_ht' => fxsl($this_ordered_product['total_prix_ht']), 'tva_percent' => fxsl($this_ordered_product['tva_percent']), 'total_tva' => fxsl($this_ordered_product['total_prix'] - $this_ordered_product['total_prix_ht']), 'total_prix' => fxsl($this_ordered_product['total_prix']), 'cout_transport_ht' => fxsl($cout_transport_ht), 'tva_cout_transport' => fxsl($tva_cout_transport), 'cout_transport' => fxsl($cout_transport), 'tarif_paiement_ht' => fxsl($tarif_paiement_ht), 'tva_tarif_paiement' => fxsl($tva_tarif_paiement), 'tarif_paiement' => fxsl($tarif_paiement), 'paiement' => $commande['paiement'], 'montant_avec_avoir' => fxsl($commande['montant_avec_avoir']));
										}
									}
								}
							}
						}
					}
					if ($type == "ventes" && $params['footer']) {
						// On crée un petit tableau de totaux à la fin
						$results_array['footer1'] = array('prix_ht' => $GLOBALS["STR_ADMIN_BILL_TOTALS"] . $GLOBALS["STR_BEFORE_TWO_POINTS"].":", 'montant_ht' => fxsl($total_total_produit_ht), 'tva_percent' => '', 'total_tva' => fxsl($total_total_produit - $total_total_produit_ht), 'total_prix' => fxsl($total_total_produit), 'cout_transport_ht' => fxsl($total_cout_transport_ht), 'tva_cout_transport' => fxsl($total_cout_transport-$total_cout_transport_ht), 'cout_transport' => fxsl($total_cout_transport), 'tarif_paiement_ht' => fxsl($total_tarif_paiement_ht), 'tva_tarif_paiement' => fxsl($total_tarif_paiement - $total_tarif_paiement_ht), 'tarif_paiement' => fxsl($total_tarif_paiement), 'paiement' => $commande['paiement']);
						$results_array['footer2'] = array('prix_ht' => $GLOBALS["STR_ADMIN_TOTAL_HT_ALL_INCLUDE"] . $GLOBALS["STR_BEFORE_TWO_POINTS"].":", 'montant_ht' => fxsl($total_total_produit_ht + $total_cout_transport_ht + $total_tarif_paiement_ht));
						$results_array['footer3'] = array('prix_ht' => $GLOBALS["STR_ADMIN_TOTAL_TVA_ALL_INCLUDE"] . $GLOBALS["STR_BEFORE_TWO_POINTS"].":", 'montant_ht' => fxsl(($total_total_produit - $total_total_produit_ht) + $total_cout_transport - $total_cout_transport_ht + $total_tarif_paiement - $total_tarif_paiement_ht));
						$results_array['footer4'] = array('prix_ht' => $GLOBALS["STR_ADMIN_TOTAL_TTC_ALL_INCLUDE"] . $GLOBALS["STR_BEFORE_TWO_POINTS"].":", 'montant_ht' => fxsl($total_total_produit + $total_cout_transport + $total_tarif_paiement));
					}
				}
				// **********
			} elseif($type == 'peel_produits') {
				$where = '';
				if (!empty($_POST['categories'])) {
					$where = " c.id IN (" . implode(',',vn($_POST['categories'])) . ") AND " ;
				}
				// PRODUITS
				// On construit toutes les lignes de données
				$sql = "SELECT p.*, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
					FROM peel_produits p
					INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
					INNER JOIN peel_categories c ON c.id=pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
					WHERE " . $where ." ". get_filter_site_cond('produits', 'p', true) . "
					GROUP BY id
					ORDER BY id";
				$query = query($sql);
				$i = 0;
				while ($result = fetch_assoc($query)) {
					// On récupère les infos liées à chaque produit
					$product_attributs_id_array = array();
					$product_object = new Product($result['id'], $result, true, null, true, !check_if_module_active('micro_entreprise'));
					// Gestion des champs calculés
					$result['Listed_price_including_vat'] = fxsl($product_object->get_original_price(true, false, false));
					$result['Listed_price_excluding_vat'] = fxsl($product_object->get_original_price(false, false, false));
					$result['Sizes'] = implode(',', $product_object->get_possible_sizes('export'));
					$result['Colors'] = implode(',', $product_object->get_possible_colors());
					$result['Brand'] = implode(',', $product_object->get_product_brands());
					$result['Associated_products'] = implode(',', $product_object->get_product_references());
					$result['Category'] = implode(',', $product_object->get_possible_categories());

					// On rajoute des informations via un hook, par exemple informations relatives à des attributs
					$hook_result = call_module_hook('export_products_get_line_infos_array', array('id' => $product_object->id), 'array');
					$result = array_merge_recursive_distinct($result, $hook_result);

					// On génère la ligne
					$this_line_output = array();
					foreach($result as $this_field_name => $this_value) {
						if (($this_field_name == $GLOBALS["STR_ADMIN_PRICE_HT_WITHOUT_REDUCTION"] || $this_field_name == $GLOBALS["STR_ADMIN_PRICE_TTC_WITHOUT_REDUCTION"] || $this_field_name == $GLOBALS["STR_ADMIN_PRICE"]) && !empty($_POST['price_disable'])) {
							$result[$this_field_name] = '';
						} else {
							if (in_array($this_field_name, vb($GLOBALS['database_field_calc_by_table_array'][$type], array())) || StringMb::substr($this_field_name, 0, StringMb::strlen('descriptif_')) == 'descriptif_' || StringMb::substr($this_field_name, 0, StringMb::strlen('description_')) == 'description_') {
								$result[$this_field_name] = StringMb::html_entity_decode_if_needed($this_value);
							} else {
								$result[$this_field_name] = vb($this_value);
							}
						}
					}
					$results_array[] = $result;
					unset($product_object);
					$i++;
					if($i%10 == 0) {
						// On transfère au fur et à mesure pour faire patienter utilisateur, et pour éviter erreur du type : Script timed out before returning headers
						echo StringMb::convert_encoding($output, $params['data_encoding'], GENERAL_ENCODING);
						$output = '';
					}
				}

				echo StringMb::convert_encoding($output, $params['data_encoding'], GENERAL_ENCODING);
			} elseif($type == 'formatted_produits') {
				$where = '';
				if (!empty($_POST['categories'])) {
					$where .= " c.id IN (" . implode(',',vn($_POST['categories'])) . ") AND " ;
				}
				$sql = "SELECT p.*, p.nom_" . (!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']) . " AS nom, p.descriptif_" . $_SESSION['session_langue'] . " AS descriptif, p.image1
					FROM peel_produits p
					INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
					INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
					WHERE " . $where ." ". get_filter_site_cond('produits', 'p', true) . "
					GROUP BY p.id
					ORDER BY p.id";
				// var_dump($sql);die();
				$query = query($sql);
				while ($result = fetch_assoc($query)) {
					// On récupère les infos liées à chaque produit
					$product_object = new Product($result['id'], $result, true, null, true, !check_if_module_active('micro_entreprise'));
					$possible_sizes = $product_object->get_possible_sizes('infos', 0, true, false, false, true);
					$size_options_html = '';
					if (!empty($possible_sizes)) {
						$purchase_prix = $product_object->get_final_price();
						foreach ($possible_sizes as $this_size_id => $this_size_infos) {
							$option_content = $this_size_infos['name'];
							$option_content .= "<br/><span style='font-size:10px;'>" . $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_OVERCOST'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . fprix($purchase_prix + $this_size_infos['final_price_formatted'], true);
							$size_options_html .= $option_content . "</span><br/>";
						}
					}
					$possible_colors = $product_object->get_possible_colors();
					$color_options_html = '';
					if (!empty($possible_colors)) {
						// Code pour recupérer select des couleurs
						foreach ($possible_colors as $this_color_id => $this_color_name) {
							$color_options_html .= $this_color_name . '<br/>';
						}
					}
					$results_array[] = array('nom' => vb($result['nom']), 'descriptif' => vb($result['descriptif']), 'formatted_prix' => fprix($product_object->get_original_price(false, false, false), true) . "<br/><span style='font-size:10px;'>" . $GLOBALS["STR_ADMIN_ECOTAX"] .$GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '. fprix($product_object->get_ecotax(), true) . "</span>", 'image1' => (!empty($result['image1'])?"<img src='" . thumbs(vb($result['image1']), 80, 50, 'fit', null, null, true, true) . "'/>":''), 'formatted_colors' => (empty($color_options_html)?'<span>-</span>':$color_options_html), 'formatted_sizes' => (empty($size_options_html)?'<span>-</span>':$size_options_html));
					unset($product_object);
				}
			}
			if(empty($error_output)) {
				// On appelle un hook qui va éventuellement gérer l'import de la ligne si il gère ce $type d'import
				// NB : Si on veut forcer des titres dans la génération de la première ligne, dans le hook ci-dessous on peut compléter $GLOBALS['database_field_titles_by_type_array'][$type]
				
				if (!empty($params['group_by'])) {
					// il y a des valeurs vides dans ce qui est envoyé en POST. Il faut transmettre au hook un tableau n'ayant que des valeurs remplies
					$group_by_array = array();
					foreach($params['group_by'] as $this_value) {
						if (!empty($this_value)) {
							$group_by_array[] = $this_value;
						}
					}
				}
				if (!empty($params['order_by'])) {
					// il y a des valeurs vides dans ce qui est envoyé en POST. Il faut transmettre au hook un tableau n'ayant que des valeurs remplies
					$order_by_array = array();
					foreach($params['order_by'] as $this_value) {
						if (!empty($this_value)) {
							$order_by_array[] = $this_value;
						}
					}
				}
				$hook_result = call_module_hook('export', array('type' => $type, 'mode' => $mode, 'results_array' => $results_array, 'table_name' => $table_name, 'domain' => vb($params['domain']), 'format' => $format, 'separator' => $params['separator'], 'ordered_fields_selected' => vb($params['ordered_fields_selected']), 'group_by_array' => vb($group_by_array), 'order_by_array' => vb($order_by_array), 'max_subtotals_level' => vb($params['max_subtotals_level']), 'show_details' => vb($params['show_details']), 'skip_empty_totals' => vb($params['skip_empty_totals']), 'frm' => vb($params['frm']), 'report_header' => vb($params['report_header'])), 'array');

				if(isset($hook_result['disable_colresizable'])) {
					$params['disable_colresizable'] = $hook_result['disable_colresizable'];
				}
				
				if(!empty($hook_result['export_output'])) {
					// Le hook a préparé intégralement le résultat
					if(strlen($hook_result['export_output']) < 500 && trim(StringMb::strip_tags($hook_result['export_output'])) == '') {
						$output = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS["STR_ADMIN_NO_RESULT"]))->fetch();
					} else {
						$output = &$hook_result['export_output'];
					}
				} else {
					if(!empty($hook_result['export_done'])) {	
						// Un hook a traité l'export (même si résultat vide)
						if(!empty($results_array)) {
							$results_array = array_merge_recursive_distinct($results_array, vb($hook_result['results_array'], array()));
						} else {
							$results_array = &$hook_result['results_array'];
						}
					} else {
						// Aucun hook n'a traité d'export, donc on fait un export générique si c'est possible
						if(!empty($table_name) && empty($results_array) && (empty($GLOBALS['database_mode_by_type_array'][$type]) || $GLOBALS['database_mode_by_type_array'][$type] != 'virtual')) {
							$results_array = get_table_rows($table_name, null, null, false, null, null, false, null, null, null, vb($params['ordered_fields_selected']));
						}
					}

					// On récupère les résultats de la BDD si pas déjà fait plus haut
					if(empty($results_array) && !empty($sql_csv)) {
						$query_csv = query($sql_csv);
						while ($result = fetch_assoc($query_csv)) {
							$results_array[] = $result;
						}
					}
					if(!empty($params['ordered_fields_selected'])) {
						foreach($params['ordered_fields_selected'] as $this_field) {
							if(strpos($this_field, '_date') !== false || strpos($this_field, 'date_') === 0 || $this_field == 'date') {
								$GLOBALS['database_fields_format']['date'][] = $this_field;
							}
						}
					}
					$output .= get_export_table($results_array, $table_name, $format, $params['separator'], $params);
					if(!empty($params['page_bottom']) && ($format == 'pdf' || $format == 'html')) {
						$output .= '<div style="position:absolute;bottom:0px;">' . $params['page_bottom'] . '</div>';
					}
				}
				if(function_exists('t2web_database_connect')) {
					// En cas de gestion de connexion à une bdd différente pour les données sources par rapport aux données de configuration
					// => on bascule vers la connexion aux données de configuration
					// NB : c'est nécessaire pour pouvoir gérer les variables de configuration de minification par exemple lors de l'output HTML
					t2web_database_connect();
				}
				if($format == 'pdf' || $format == 'html') {
					$additional_header = '
<style>
h1 { font-size: 14px; color: #337733; align: center; }
h2 { font-size: 12px; }
.footer { padding-top:10px; margin-top: 10px;}
table { border-spacing: 0; border-collapse: collapse; border: 0px; }
table tr td, table tr th { font-size:11px; padding: 2px; border: 1px solid #e5e5e5; }
@media print {
	@page {
		size: A4 landscape;
	}
	body {
		max-width: 100%;
	}
}
</style>
					';
					if(!empty($params['report_header']) && empty($GLOBALS['disable_report_header'])) {
						$output = '<div class="report_header">'. $params['report_header'] . '</div>' . $output;
					}
					if(!empty($params['report_footer'])) {
						$output .= '<div class="report_footer">'. $params['report_footer'] . '</div>';
					}
				}
				/*// Test de performance
				$old_output = $output;
				for($i=0;$i<=50;$i++) {
					$output .= $old_output;
				}*/
				if($format == 'pdf') {
					$pdf_output = '';
					$use_light_pdf = true;
					if($use_light_pdf) {
						// Beaucoup plus rapide que HTML2PDF qui n'est pas raisonnable car il génère 1 page toutes les secondes environ
						require_once($GLOBALS['dirroot'].'/lib/class/pdf/light_html2pdf/light_html2pdf.class.php');
						try
						{
							$html2pdf = new LIGHT_HTML2PDF('L');
							$output = '<body style="font-size:8px">' . $additional_header . '<h1>' . $GLOBALS['DOC_TITLE'] . '</h1>' . $output . '</body>';
							$html2pdf->AddPage();
							$html2pdf->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), '', 12);
							$html2pdf->writeHTML($output);
							ob_start();
							$html2pdf->Output($GLOBALS['database_import_export_type_names_array'][$type] . '-' . str_replace('/', '-', get_formatted_date(time(), 'short')). '.pdf');
							$pdf_output .= ob_get_contents();
							ob_end_clean();
						} catch (Exception $e) {
							echo $e;
							exit;
						}
					} else {
						require_once($GLOBALS['dirroot'].'/lib/class/pdf/html2pdf/html2pdf.class.php');
						try
						{
							$html2pdf = new HTML2PDF('L', 'A4', 'fr', true, 'UTF-8', array(5, 5, 5, 5));
							// $html2pdf->setModeDebug();
							$html2pdf->setDefaultFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"));
							/*// Test de performance
							$old_output = $output;
							for($i=0;$i<=1000;$i++) {
								$output .= $old_output;
							} */
							$output = '<body style="font-size:8px">' . $additional_header . '<h1>' . $GLOBALS['DOC_TITLE'] . '</h1>'. $output . '</body>';
							$html2pdf->writeHTML($output);
							ob_start();
							$html2pdf->Output($GLOBALS['database_import_export_type_names_array'][$type] . '-' . str_replace('/', '-', get_formatted_date(time(), 'short')). '.pdf');
							$pdf_output .= ob_get_contents();
							ob_end_clean();
						}
						catch(HTML2PDF_exception $e) {
							echo $e;
							exit;
						}
					}
					// On envoie le PDF
					echo $pdf_output;
					unset($output);
				} elseif($format == 'html') {
					unset($GLOBALS['js_content_array']);
					unset($GLOBALS['js_ready_content_array']);
					if(empty($params['disable_colresizable'])) {
						$GLOBALS['js_files'][] = $GLOBALS['wwwroot']. '/lib/js/colResizable-1.6.min.js';
						$GLOBALS['js_ready_content_array'][] = '$("table").colResizable({postbackSafe:false, resizeMode:"flex", gripInnerHtml:"<div class=\'grip\'></div>"});';
					}
					output_light_html_page($output, $GLOBALS['DOC_TITLE'], $additional_header);
				} elseif($format == 'full_html_page') {
					include($GLOBALS['repertoire_modele'] . "/haut.php");
					echo '<h1>' . $GLOBALS['DOC_TITLE'] . '</h1>
					' . $output;
					include($GLOBALS['repertoire_modele'] . "/bas.php");
				} else {
					// On transmet le fichier qu'on vient de préparer
					if (!empty($params['filename'])) {
						// $params['filename'] contient le nom du fichier ainsi que son extension
						// Le nom du fichier est défini par l'utilisateur
						$filename = $params['filename'];
					} elseif(empty($filename)) {
						$filename = "export_".$type."_" . str_replace(array('/', ' '), '-', get_formatted_date(time(), 'short', true)) . ".csv";
					}
					output_csv_http_export_header($filename, 'csv', $params['data_encoding']);
					// On envoie le fichier CSV
					echo StringMb::convert_encoding($output, $params['data_encoding'], GENERAL_ENCODING);
					unset($output);
				}
				die();
				break;
			} else {
				// On va afficher l'erreur ci-dessous
			}
		default:
			$next_mode = 'export';
			$footer_optional_array = array();
			if(!empty($GLOBALS['database_footer_by_type_array'])) {
				foreach ($GLOBALS['database_footer_by_type_array'] as $this_type => $this_value) {
					$footer_optional_array[] = $this_type;
				}
			}
			
			// FORMULAIRE DE CHOIX D'EXPORTATION
			
			$tpl = $GLOBALS['tplEngine']->createTemplate('admin_export_form.tpl');
			$information_select_html = '';
			if(in_array('produits' , $GLOBALS['database_import_export_type_names_array'])) {
				// Formulaire complémentaire pour les produits
				$tpl_information_select = $GLOBALS['tplEngine']->createTemplate('admin_ventes_information_select.tpl');
				$tpl_information_select->assign('payment_status_options', get_payment_status_options(vb($_GET['statut'])));
				$tpl_information_select->assign('STR_ORDER_STATUT_PAIEMENT', $GLOBALS['STR_ORDER_STATUT_PAIEMENT']);
				$tpl_information_select->assign('STR_ADMIN_ALL_ORDERS', $GLOBALS['STR_ADMIN_ALL_ORDERS']);
				$information_select_html = $tpl_information_select->fetch();
			
				$tpl->assign('STR_ADMIN_EXPORT_PRODUCTS_CHOOSE_EXPORT_CRITERIA', $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CHOOSE_EXPORT_CRITERIA']);
				$tpl->assign('STR_ADMIN_SELECT_CATEGORIES_TO_EXPORT', $GLOBALS['STR_ADMIN_SELECT_CATEGORIES_TO_EXPORT']);
				$tpl->assign('STR_ADMIN_EXPORT_PRICES_DISABLE', $GLOBALS['STR_ADMIN_EXPORT_PRICES_DISABLE']);
				$tpl->assign('product_categories', get_categories_output(null, 'categories',  vb($params['categories']), 'option', '&nbsp;&nbsp;', null, null, true, 80));
			}

			
			$tpl->assign('rules_array', get_import_export_saved_configuration_names('export'));
			
			if (!empty($GLOBALS['database_export_sub_domains'])) {
				$tpl->assign('export_sub_domains', $GLOBALS['database_export_sub_domains']);
			}
			if (!empty($GLOBALS['database_group_by_type_array'])) {
				$this_group_by_type_array = array();
				foreach($GLOBALS['database_group_by_type_array'] as $this_type => $this_group_by_fields) {
					$this_group_by_type_array[$this_type] = explode(',', $this_group_by_fields);
				}
				$tpl->assign('group_by_type_array', $this_group_by_type_array);
				if (!empty($GLOBALS['max_subtotals_level_allowed'])) {
					$tpl->assign('max_subtotals_level_allowed', $GLOBALS['max_subtotals_level_allowed']);
				}
				$tpl->assign('show_details', vb($params['show_details']));
			}
			if (!empty($GLOBALS['database_order_by_type_array'])) {
				$this_order_by_type_array = array();
				foreach($GLOBALS['database_order_by_type_array'] as $this_type => $this_order_by_fields) {
					$field_array = array();
					foreach(explode(',', $this_order_by_fields) as $this_field) {
						$field_array[] = $this_field.' ASC';
						$field_array[] = $this_field.' DESC';
					}
					$this_order_by_type_array[$this_type] = $field_array;
				}
				$tpl->assign('order_by_type_array', $this_order_by_type_array);
			}
			
			if (!empty($GLOBALS['database_skip_empty_totals_fields_by_type_array'])) {
				$tpl->assign('skip_empty_totals', vb($params['skip_empty_totals']));
			}
			$tpl->assign('page_break', vb($params['page_break']));
			$tpl->assign('action', get_current_url(true));
			$tpl->assign('mode', $mode);
			$tpl->assign('next_mode', $next_mode);
			$tpl->assign('header', $params['header']);
			$tpl->assign('footer', $params['footer']);
			$tpl->assign('footer_optional_array', $footer_optional_array);
			$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . 'export'));
			$tpl->assign('STR_ADMIN_EXPORT_TYPE', $GLOBALS['STR_ADMIN_EXPORT_TYPE']);
			$tpl->assign('STR_ADMIN_EXPORT_COLUMNS', $GLOBALS['STR_ADMIN_EXPORT_COLUMNS']);
			$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
			$tpl->assign('inputs', get_database_field_properties($params));
			$tpl->assign('types_array', $GLOBALS['database_import_export_type_names_array']);
			$tpl->assign('uploaddir', $GLOBALS['uploaddir']);
			$tpl->assign('data_encoding', vb($params['data_encoding']));
			$tpl->assign('separator', str_replace("\t", '\t', $params['separator']));
			$tpl->assign('selected_type', $type);
			
			if(!empty($type)) {
				$GLOBALS['js_ready_content_array'][] = 'change_export_type(true);';
			}
			$tpl->assign('format', $format);
			$tpl->assign('admin_date_filter_form', get_admin_date_filter_form($GLOBALS['STR_ADMIN_VENTES_RESULTS_TITLE'], $information_select_html, null, false, false));
			$tpl->assign('STR_ADMIN_EXPORT_PAGE_BREAK_EACH_GROUP', $GLOBALS['STR_ADMIN_EXPORT_PAGE_BREAK_EACH_GROUP']);
			$tpl->assign('STR_ADMIN_TEXT_FOR_PDF_EXPORT', $GLOBALS['STR_ADMIN_TEXT_FOR_PDF_EXPORT']);
			$tpl->assign('STR_ADMIN_COLUMN_TTTLE_FIRST_LINE', $GLOBALS['STR_ADMIN_COLUMN_TTTLE_FIRST_LINE']);
			$tpl->assign('STR_ADMIN_ADD_FOOTER_FILE_EXPORT', $GLOBALS['STR_ADMIN_ADD_FOOTER_FILE_EXPORT']);
			$tpl->assign('STR_ADMIN_COLUMN_AVAILABLE', $GLOBALS['STR_ADMIN_COLUMN_AVAILABLE']);
			$tpl->assign('STR_ADMIN_GENERATE_FILE', $GLOBALS['STR_ADMIN_GENERATE_FILE']);
			$tpl->assign('STR_ADMIN_SELECTED_COLUMN_FOR_EXPORT', $GLOBALS['STR_ADMIN_SELECTED_COLUMN_FOR_EXPORT']);
			$tpl->assign('STR_ADMIN_FILE_COLUMN_EXPORTED', $GLOBALS['STR_ADMIN_FILE_COLUMN_EXPORTED']);
			$tpl->assign('STR_ADMIN_MOVE_COLUMN_WITH_DRAG_DROP_FOR_EXCLUDE', $GLOBALS['STR_ADMIN_MOVE_COLUMN_WITH_DRAG_DROP_FOR_EXCLUDE']);
			$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('STR_ADMIN_EXPORT', $GLOBALS['STR_ADMIN_EXPORT']);
			$tpl->assign('STR_ADMIN_TEXT_FOOTER_FOR_REPORT', $GLOBALS['STR_ADMIN_TEXT_FOOTER_FOR_REPORT']);
			$tpl->assign('STR_ADMIN_TEXT_HEADER_FOR_REPORT', $GLOBALS['STR_ADMIN_TEXT_HEADER_FOR_REPORT']);
			$tpl->assign('STR_ADMIN_IMPORT_FILE_FORMAT', $GLOBALS['STR_ADMIN_IMPORT_FILE_FORMAT']);
			$tpl->assign('STR_ADMIN_IMPORT_FILE_FORMAT_EXPLAIN', $GLOBALS['STR_ADMIN_IMPORT_FILE_FORMAT_EXPLAIN']);
			$tpl->assign('STR_ADMIN_IMPORT_FILE_EXAMPLE', $GLOBALS['STR_ADMIN_IMPORT_FILE_EXAMPLE']);
			$tpl->assign('STR_ADMIN_IMPORT_IMPORT_MODE', $GLOBALS['STR_ADMIN_IMPORT_IMPORT_MODE']);
			$tpl->assign('STR_ADMIN_IMPORT_SELECT_FIELDS', $GLOBALS['STR_ADMIN_IMPORT_SELECT_FIELDS']);
			$tpl->assign('STR_WARNING', $GLOBALS['STR_WARNING']);
			$tpl->assign('STR_ADMIN_IMPORT_EXPLAIN', $GLOBALS['STR_ADMIN_IMPORT_EXPLAIN']);
			$tpl->assign('STR_ADMIN_IMPORT_WARNING_ID', $GLOBALS['STR_ADMIN_IMPORT_WARNING_ID']);
			$tpl->assign('STR_ADMIN_IMPORT_FILE_NAME', $GLOBALS['STR_ADMIN_IMPORT_FILE_NAME']);
			$tpl->assign('STR_ADMIN_IMPORT_FILE_ENCODING', $GLOBALS['STR_ADMIN_IMPORT_FILE_ENCODING']);
			$tpl->assign('STR_ADMIN_IMPORT_SEPARATOR', $GLOBALS['STR_ADMIN_IMPORT_SEPARATOR']);
			$tpl->assign('STR_ADMIN_EXPORT_SEPARATOR_EXPLAIN', $GLOBALS['STR_ADMIN_EXPORT_SEPARATOR_EXPLAIN']);
			$tpl->assign('STR_ADMIN_EXPORT_CSV', $GLOBALS['STR_ADMIN_EXPORT_CSV']); 
			$tpl->assign('STR_ADMIN_EXPORT_PDF', $GLOBALS['STR_ADMIN_EXPORT_PDF']);
			$tpl->assign('STR_VALIDATE', $GLOBALS['STR_VALIDATE']);
			$tpl->assign('STR_INIT_FILTER', $GLOBALS['STR_INIT_FILTER']);
			$tpl->assign('STR_LOAD_RULES', $GLOBALS['STR_LOAD_RULES']);
			$tpl->assign('STR_SAVE_RULES', $GLOBALS['STR_SAVE_RULES']);
			$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
			$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
			$tpl->assign('STR_ORDER_BY', $GLOBALS['STR_ORDER_BY']);
			$tpl->assign('STR_GROUP_BY', $GLOBALS['STR_GROUP_BY']);
			$tpl->assign('STR_NB_MAX_SUBTOTAL', $GLOBALS['STR_NB_MAX_SUBTOTAL']);
			$tpl->assign('STR_ADMIN_EXPORT_SHOW_DETAILS', $GLOBALS['STR_ADMIN_EXPORT_SHOW_DETAILS']);
			$tpl->assign('STR_ADMIN_EXPORT_SKIP_EMPTY_TOTALS', $GLOBALS['STR_ADMIN_EXPORT_SKIP_EMPTY_TOTALS']);
			$tpl->assign('STR_ADMIN_BEGIN_DATE', $GLOBALS['STR_ADMIN_BEGIN_DATE']);
			$tpl->assign('STR_ADMIN_END_DATE', $GLOBALS['STR_ADMIN_END_DATE']);
			$hook_result = call_module_hook('export_form_template_data', array('domain' => vb($params['domain']), 'format' => vb($params['format'])), 'array');
			foreach($hook_result as $this_key => $this_value) {
				$tpl->assign($this_key, $this_value);
			}
			if(!empty($error_output)) {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $error_output))->fetch();
			}
			$output .= $tpl->fetch();
			return $output;
		break;
	}
}

/**
 * Générer un tableau de données, en CSV ou HTML
 *
 * @param array $lines
 * @param string $table_name
 * @param string $format "csv" ou "html"
 * @param string $separator
 * @param array $params
 * @param boolean $autoformat Si true, cela permet de formatter automatiquement les dates.
 * @return
 */
function get_export_table(&$lines, $table_name = null, $format = 'csv', $separator = "\t", &$params, $autoformat = true) {
	// On génère les lignes de résultat
	$output = '';
	// Création de la ligne des titres
	// Dans l'interface : mettre en première la ligne les titres de colonnes (si disponible pour le type d'export souhaité)
	// header vaut false pour les types virtuel, à mettre dans la configuration d'initialisation -> Priorité par rapport au champ du formulaire
	if(!empty($params['header'])) {
		// On affiche les titres des colonnes
		$output_array = array();
		if($format != 'csv') {
			$total_width = 0;
			$output .= '<tr style="background-color:#3030bf; color:#ffffff;">';
		}
		if(!empty($params['ordered_fields_selected'])) {
			foreach($params['ordered_fields_selected'] as &$this_field) {
				$temp = explode('.', $this_field, 2);
				$this_field_name = end($temp);
				if($format != 'csv') {
					$this_width = (!empty($GLOBALS['database_fields_width'][$this_field_name])?$GLOBALS['database_fields_width'][$this_field_name]:120);
					$total_width += $this_width;
					$output .= '<th class="center" style="width:' . $this_width . 'px; border-color:#6060bf; position:sticky">' . get_field_title($this_field, vb($params['type'], $table_name), true, $this_width/5, !in_array($this_field_name, array('Presence_Absence'))) . '</th>';
				} else {
					$output_array[] = filtre_csv(get_field_title($this_field, vb($params['type'], $table_name), true, null, true), $separator);
				}
				if(empty($GLOBALS['column_format'][$this_field])) {
					$GLOBALS['column_format'][$this_field] = null;
					if(!empty($GLOBALS['database_fields_format'])) {
					foreach($GLOBALS['database_fields_format'] as $this_format => $this_fields_array) {
						if(in_array($this_field, $this_fields_array)) {
							$GLOBALS['column_format'][$this_field] = $this_format;
							}
						}
					}
				}
			}
			unset($this_field);
		}
		
		if($format == 'csv') {
			$output .= implode($separator, $output_array) . "\r\n";
		} else {
			$total_width += $this_width;
			$output .= '</tr>
';
		}
	}
	if($format != 'csv') {
		// On attend d'avoir la largeur totale pour définir la table, avec fixed width pour garantir rapidité et respect de la largeur
		if(!empty($params['title'])) {
			// Si demandé, on rajoute un titre de la largeur de la table
			if(!empty($GLOBALS['database_fields_width'])) {
				// Pour ne pas interférer avec le fonctionnement de table-layout:fixed il faut que la première ligne ne soit pas un colspan, donc on crée une autre table
				$output = '<div><table style="margin:auto; table-layout:fixed; width:' . $total_width . 'px"><tr style="' . (!empty($params['color'])?'background-color:#' . $params['color']:'') . ';"><th style="padding:0px" colspan="' . count($params['ordered_fields_selected']) . '">' . $params['title'] . '</th></tr></table><table style="margin:auto; table-layout:fixed; width:' . $total_width . 'px">' . $output;
			} else {
				$output = '<div><table style="margin:auto"><tr style="' . (!empty($params['color'])?'background-color:#' . $params['color']:'') . ';"><th style="padding:0px" colspan="' . count($params['ordered_fields_selected']) . '">' . $params['title'] . '</th></tr>' . $output;
			}
		} else {
			$output = '<div><table style="margin:auto; table-layout:fixed; width:' . $total_width . 'px">' . $output;
		}
	}
	if(!empty($lines)) {
		$i = 0;
		foreach($lines as $this_key => &$this_line) {
			$output_array = array();
			if($format != 'csv') {
				$style = array();
				if($i%2) {
					$style[] = 'background-color:#f9f9f9';
				}
				if (StringMb::strpos($this_key, 'total') !== false) {
					$style[] = 'font-weight: bold';
				}
				if(!empty($params['styles_by_line_array']) && !empty($params['styles_by_line_array'][$this_key])) {
					$style[] = $params['styles_by_line_array'][$this_key];
				}
				$output .= '<tr style="' . implode('; ', $style) . '">';
				unset($colspan);
				if(!empty($params['colspanempty_by_line_array']) && !empty($params['colspanempty_by_line_array'][$this_key])) {
					$consecutive_notempty = 1;
					foreach($params['ordered_fields_selected'] as &$this_field) {
						if(!empty($last_notempty_field)) {
							if(empty($this_line[$this_field])) {
								$consecutive_notempty++;
								$colspan[$this_field] = 0;
								continue;
							}
							$colspan[$last_notempty_field] = $consecutive_notempty;
							$consecutive_notempty = 1;
						}
						$last_notempty_field = $this_field;
					}
					unset($this_field);
					$colspan[$last_notempty_field] = $consecutive_notempty;
				}
			}
			foreach($params['ordered_fields_selected'] as &$this_field) {
				$temp = explode('.', $this_field, 2);
				$this_field_name = end($temp);
				if(!empty($GLOBALS['column_format'][$this_field])) {
					$this_format = $GLOBALS['column_format'][$this_field];
				} elseif(!empty($GLOBALS['column_format'][$this_field_name])) {
					$this_format = $GLOBALS['column_format'][$this_field_name];
				} else {
					$this_format = null;
				}
				$this_value = vb($this_line[$this_field]);
				$class = null;
				$style = null;
				if (is_array($this_value)) {
					$this_value = implode(',', $this_value);
				} elseif($autoformat && $this_format == 'date') {
					$this_value = get_formatted_date($this_value, 'short', 'long');
					$class = 'center';
				} elseif($format != 'csv') {
					if(in_array($this_format, array('month', 'clock'))) {
						$class = 'center';
					} elseif($this_format == 'checkbox' && is_numeric($this_value) && in_array($this_value, array(0,-1,1,127))) {
						$this_value = '<input type="checkbox" disabled="disabled"' . ($this_value != 0 ? ' checked="checked"' : '') . ' />';
						$class = 'center';
						$style = 'padding: 0px';
					} elseif(in_array($this_format, array('float', 'time', 'currency', 'currency3', 'percent')) || (empty($this_format) && (is_numeric($this_value) || strpos($this_value, '&nbsp;€') !== false))) {
						$class = 'right';
						if(floatval(str_replace(array(',', 'h', "'", ' ', '&nbsp;'), '',$this_value))<0) {
							$class .= ' red';
						}
						if($this_value === null) {
							$class .= ' grey';
							// On n'affiche rien, on pourrait mettre une mention générique - ou N/A par exemple.
							// $this_value = '-'; 
							// $this_value = 'N/A'; 
						}
						if($this_value === 0 || $this_value === '0,00' || $this_value === "0h00'") {
							$class .= ' grey';
						}
					} elseif(strpos($this_field_name, 'E_Mail') !== false || strpos($this_field_name, 'Email') !== false) {
						// Césure entre N emails
						$this_value = str_replace(array(',', ';'), array(',<wbr>', ';<wbr>'), $this_value);
					}
				}
				if($format != 'csv') {
					if(isset($colspan[$this_field]) && $colspan[$this_field] === 0) {
						// On ne crée pas de case, car couverte par un colspan déjà mis
					} else {
						$output .= '<td' . (!empty($colspan[$this_field])?' colspan="' . $colspan[$this_field] . '"':'') . '' . ($class?' class="' . $class . '"':'') . '' . ($style?' style="' . $style . '"':'') . '>'. $this_value . '</td>' ;
					}
				} else {
					$output_array[] = filtre_csv($this_value, $separator);
				}
			}
			unset($this_field);
			if($format == 'csv') {
				$output .= implode($separator, $output_array) . "\r\n";
			} else {
				$output .= '</tr>
';
			}
			$i++;
		}
		unset($this_line);
	}
	if(!empty($params['footer'])) {
		/*
			// Si on veut faire un traitement particulier on peut mettre ici ce qu'on veut.
			// Si on veut ajouter des infos en bas du fichier sans tenir compte des clonne CSV, on peut s'inspirer de ce qui est là
			foreach($this_line as $key=>$this_value) {
				$output .= filtre_csv($key, $separator);
				$output .= $separator;
				$output .= filtre_csv($this_value, $separator);
				$output .= "\r\n";
			}
		*/
	}
	if($format != 'csv') {
		$output .= '</table></div>
';
		
	} else {
		// On ne veut qu'une seule ligne de noms de colonnes, et non pas une à chaque fois pour un sous-groupe
		// Comme $params est passé en référence, le plus simple est de changer la valeur de $params['header'] ici
		$params['header'] = false;
	}
	return $output;
}

/**
 * Gestion de l'insertion ou la mise à jour d'une commande.
 *
 * @param boolean $frm
 * @param string $bill_mode
 * @return
 */
function handle_order_insert_or_update($frm, $bill_mode='commander', $form_error_object = null) {
	if(empty($form_error_object)) {
		$form_error_object = new FormError();
	}
	$save_commande = true;
	$output = '';
	// on rentre l'info du mode de facture ici, on retrouvera cette donnée plus tard dans le tableau order_infos
	$frm['bill_mode'] = $bill_mode;
	for ($i = 1; $i <= 1000; $i++) {
		// $i <= 1000 : C'est le moyen le plus simple de traiter le formulaire, car les ids dans le formulaire ne se suivent par forcement, l'administrateur peut avoir supprimer le produit 1, ce qui fait que $frm["id1"] n'existe pas
		if (!empty($frm["id" . $i])) {
			// On vérifie si la commande ne comporte pas de produit avec une quantité inférieure au minimum requis
			$product_object = new Product($frm["id" . $i]);
			if (vn($product_object->quantity_min_order) > 1 && $frm["q" . $i] < $product_object->quantity_min_order) {
				$save_commande = false;
			}
			unset($product_object);
		}
	}
	if ($save_commande) {
		// Ajout d'une commande en db + affichage du détail de la commande
		if (!$form_error_object->count()) {
			$order_id = save_commande_in_database($frm);
		} else {
			if(!empty($frm['id'])) {
				$order_id = $frm['id'];
			} elseif(!empty($frm['commandeid'])) {
				$order_id = $frm['commandeid'];
			} else {
				$order_id = 0;
			}
		}
		// Affichage du formulaire éventuellement géré par le hook
		$hook_output = call_module_hook('handle_order_insert_or_update', array('form_error_object' => $form_error_object, 'save_commande' => $save_commande, 'frm' => $frm, 'bill_mode' => $bill_mode, 'order_id' => $order_id), 'array');
		if (!empty($frm['commandeid']) || $form_error_object->count()) {
			// lors d'une modification, ou de la création de facture avec une erreur
			if (!$form_error_object->count()) {
				$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_ORDER_UPDATED'] . (check_if_module_active('stock_advanced') ? ' ' . $GLOBALS['STR_ADMIN_COMMANDER_AND_STOCKS_UPDATED'] : '')))->fetch();
			}
			if (!empty($hook_output['result_commande'])) {
				$output .= $hook_output['result_commande'];
			} else {
				if (!empty($frm['commandeid'])) {
					$commandeid = $frm['commandeid'];
					$mode = $_GET['mode'];
				} else {
					$commandeid = null;
					$mode = 'ajout';
				}
				$output .= affiche_details_commande($commandeid, $mode, null, $bill_mode, $form_error_object, $frm);
			}
		} else {
			$hook_output = call_module_hook('handle_order_insert_fact_message_ok', array('bill_mode' => $bill_mode, 'order_id' => $order_id), 'array');
			// pas de commande_id, on est dans le cas d'une création
			if ($bill_mode == 'commander') {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMANDER_ORDER_CREATED'] . ' - <a href="' . $GLOBALS['administrer_url'] . '/commander.php?mode=modif&amp;commandeid=' . $order_id . '">' . $GLOBALS['STR_ADMIN_COMMANDER_LINK_ORDER_SUMMARY'] . '</a>'))->fetch();
			} elseif (!empty($hook_output['message_ok'])) {
				$output .= $hook_output['message_ok'];
			}
		}
		
		if (empty($hook_output['tracert_history_admin_disable'])) {
			if (empty($frm['id'])) {
				tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'CREATE_ORDER', intval(vn($frm['id_utilisateur'])));
			} else {
				tracert_history_admin(intval(vn($frm['id_utilisateur'])), 'EDIT_ORDER', $GLOBALS['STR_ADMIN_USER'] . $GLOBALS["STR_BEFORE_TWO_POINTS"] . ': ' . intval(vn($frm['id_utilisateur'])) . ', '.$GLOBALS['STR_ORDER_NAME'] . $GLOBALS["STR_BEFORE_TWO_POINTS"] . ': ' . intval(vn($frm['id'])));
			}
		}
	} else {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ORDER_MIN'].$GLOBALS['STR_REQUIRED_VALIDATE_ORDER']))->fetch();
		$output .= affiche_details_commande(null, 'ajout');
	}
	return $output;
}

/**
 * Récupère les noms des configurations sauvegardées pour l'import export
 * 
 * @param string $mode
 * @param string $type
 * @return
 */
function get_import_export_saved_configuration_names($mode = false, $type = null) {

	if (function_exists('t2_load_form_values')) {
	// NB : le code technique import_config est historique, mais concerne tout l'import / export en général
	$all_rules_array = t2_load_form_values('import_config', true, false, null);
	}
	$rules_name_array = array();
	if(!empty($all_rules_array)) {
		// $all_rules_array se présente sous la forme array(
		// nom_de_la_regle_1 => array('correspondance'=>"champ1=valeur1&champ2=valeur2"),array('default_fields'=>'champ1=valeur1&champ2=valeur2')
		// nom_de_la_regle_2 => array('correspondance'=>"champ1=valeur1&champ2=valeur2"),array('default_fields'=>'champ1=valeur1&champ2=valeur2')
		// ici on veut récupérer le nom seulement
		foreach($all_rules_array as $this_rules_name => $data_array) {
			 // si $mode === false on veut tout récupérer
			if (!empty($data_array) && ((!empty($data_array['mode']) && $data_array['mode'] == $mode) || $mode === false) && ((!empty($data_array['type']) && $data_array['type'] == $type) || $type === null)) {
				$rules_name_array[] = $this_rules_name;
			}
		}
	}
	return $rules_name_array;
}

/**
 *
 * Met à jour un template d'email en base de données 
 *
 * @param array $frm
 * @param string $id
 * @param object $form_error_object
 * @return
 */
function update_email_template($frm, $id, &$form_error_object) {
	$output = "";
	if (isset($frm['form_name'], $frm['form_subject'], $frm['form_text'])) {
		if (empty($frm['form_id_cat'])) $form_error_object->add('form_id_cat');
		if (empty($frm['form_name'])) $form_error_object->add('form_name');
		if (empty($frm['form_subject'])) $form_error_object->add('form_subject');
		if (!verify_token($_SERVER['PHP_SELF'].$id)) $form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);

		if (empty($frm['form_text'])) {
			$form_error_object->add('form_text');
		} elseif (strip_tags($frm['form_text']) != $frm['form_text']) {
			// ATTENTION ne pas utiliser StringMb::strip_tags car sinon les remplacements d'espaces divers altèreraient la validité du test ci-dessus
			// On corrige le HTML si nécessaire
			if (StringMb::strpos($frm['form_text'], '<br>') === false && StringMb::strpos($frm['form_text'], '<br />') === false && StringMb::strpos($frm['form_text'], '</p>') === false && StringMb::strpos($frm['form_text'], '<table') === false) {
				// Par exemple si on a mis des balises <b> ou <u> dans email sans mettre de <br /> nulle part, on rajoute <br /> en fin de ligne pour pouvoir nettoyer ensuite le HTML de manière cohérente
				$added_br = true;
				$frm['form_text'] = str_replace(array("\n"), "<br />\n", str_replace(array("\r\n", "\r"), "\n", $frm['form_text']));
			}
			$frm['form_text'] = StringMb::getCleanHTML($frm['form_text'], null, true, true, true, null, false);
			if (!empty($added_br)) {
				$frm['form_text'] = str_replace(array("<br />\n"), "\n", $frm['form_text']);
			}
		}

		if ($form_error_object->count()) {
			if ($form_error_object->has_error('token')) {
				$output = $form_error_object->text('token');
			} else {
				$output = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_FILL_IN_ALL']))->fetch();
			}
		} else {
			$image_haut = upload('image_haut', false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image_haut']));
			$image_bas = upload('image_bas', false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image_bas']));
			query('UPDATE peel_email_template SET
				site_id="' . intval(vn($frm['site_id'])) . '",
				technical_code="' . nohtml_real_escape_string(trim(vb($frm['form_technical_code']))) . '",
				name="' . nohtml_real_escape_string(trim($frm['form_name'])) . '",
				subject="' . real_escape_string(trim($frm['form_subject'])) . '",
				text="' . real_escape_string($frm['form_text']) . '",
				id_cat="' .intval($frm['form_id_cat']) . '",
				lang="' . nohtml_real_escape_string(trim($frm['form_lang'])) . '",
				default_signature_code ="' . nohtml_real_escape_string(vb($frm['default_signature_code'])) . '",
				image_haut ="' . nohtml_real_escape_string($image_haut) . '",
				image_bas ="' . nohtml_real_escape_string($image_bas) . '"
				WHERE id="' . intval($id) . '"');
			$output = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS["STR_ADMIN_EMAIL_TEMPLATES_MSG_UPDATED"]))->fetch();
		}
	}
	return $output;
}



/**
 *
 * Formulaire HTML de modification d'un template d'email existant
 *
 * @param array $frm
 * @param integer $id
 * @param string $update_message
 * @param array $params
 * @return
 */
function modif_email_template_form($frm, $id, $update_message, $params = array()) {
	$hook_params['frm'] = $frm;
	$hook_params['id'] = $id;
	$hook_params['params'] = $params;
	$hook_result = call_module_hook('create_or_update_template_form', $hook_params, 'array');

	$query_update = query('SELECT *
		FROM peel_email_template
		WHERE id="' . intval($id) . '" AND ' . get_filter_site_cond('email_template', null, true) . '
		LIMIT 1');
	$template_infos = fetch_assoc($query_update);
	// On va chercher les catégories
	$sql = 'SELECT technical_code, id, name_' . $_SESSION['session_langue'] . ' AS name, site_id
		FROM peel_email_template_cat
		WHERE ' . get_filter_site_cond('email_template_cat', null) . '
		ORDER BY name ASC';
	$query = query($sql);
	$tpl_categories_list = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_categories_list.tpl');
	$tpl_categories_list->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl_options = array();
	while ($row_categories = fetch_assoc($query)) {
		$tpl_options[] = array('value' => intval($row_categories['id']),
			'issel' => vb($frm['form_id_cat']) == $row_categories['id'] || $row_categories['id'] == $template_infos['id_cat'],
			'name' => get_site_info($row_categories) . $row_categories['name']
			);
	}
	$tpl_categories_list->assign('options', $tpl_options);
	$categories_list = $tpl_categories_list->fetch();

	$email_template_cat_infos = get_table_rows('peel_email_template_cat', $template_infos['id_cat'], null, true, 1);
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_output.tpl');
	$tpl->assign('params', $params);

	$tpl->assign('show_tag_list', show_tag_list('email_clients', 'form_text'));
	if($email_template_cat_infos['technical_code'] == 'mission_statements') {
		$tpl->assign('mission_statement_tag', show_tag_list('mission_statements', 'form_text'));
	} else {
		$tpl->assign('mission_statement_tag', null);
	}
	$tpl->assign('image_haut', get_uploaded_file_infos("image_haut" , $template_infos["image_haut"], get_current_url(false) . '?mode=supprfile&id=' . vb($template_infos['id']) . '&file=image_haut'));
    $tpl->assign('image_bas', get_uploaded_file_infos("image_bas" , $template_infos["image_bas"], get_current_url(false) . '?mode=supprfile&id=' . vb($template_infos['id']) . '&file=image_bas'));
	$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
	$tpl->assign('message_html', $update_message);
	$tpl->assign('create_template_href', $params['create_template_href']);
	$tpl->assign('id', $id);
	$tpl->assign('action', get_current_url());
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'].$id));
	$tpl->assign('categories_list', $categories_list);
	$tpl->assign('technical_code', (isset($frm['form_technical_code']) ? $frm['form_technical_code'] : vb($template_infos['technical_code'])));
	$tpl->assign('name', (isset($frm['form_name']) ? $frm['form_name'] : vb($template_infos['name'])));
	$tpl->assign('subject', vb($template_infos['subject']));
	// charge CKeditor
	$tpl->assign('form_text', getTextEditor('form_text', '100%', 500, StringMb::html_entity_decode_if_needed(vb($template_infos['text'])), null, 3));
	$tpl->assign('signature_template_options', get_email_template_options('technical_code', null, vb($template_infos['lang']), vb($template_infos['default_signature_code']), true));

	$tpl_langs = array();
	$langs_array = $GLOBALS['admin_lang_codes'];
	if (!empty($template_infos['lang']) && !in_array($template_infos['lang'], $GLOBALS['admin_lang_codes'])) {
		$langs_array[] = $template_infos['lang'];
	}
	foreach ($langs_array as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'issel' => vb($template_infos['lang']) == $lng
			);
	}
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($template_infos['site_id'])));
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('emailLinksExplanations', emailLinksExplanations());
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_TEXT', $GLOBALS['STR_TEXT']);
	$tpl->assign('STR_NUMBER', $GLOBALS['STR_NUMBER']);
	$tpl->assign('STR_CLICK_HERE', $GLOBALS['STR_CLICK_HERE']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	$tpl->assign('STR_ADMIN_SUBJECT', $GLOBALS['STR_ADMIN_SUBJECT']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_UPDATE_TEMPLATE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_UPDATE_TEMPLATE']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_SIGNATURE', $GLOBALS['STR_SIGNATURE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_WARNING', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_WARNING']);
	return $tpl->fetch();	
}

/**
 *
 * Ajoute un template d'email dans la base de données
 *
 * @param array $frm
 * @param object $form_error_object
 * @return
 */
function insert_email_template($frm, &$form_error_object) {
	// Insertion d'un nouveau template = requete sql
	$output = '';
	if (isset($frm['form_name'], $frm['form_subject'], $frm['form_text'], $frm['form_lang'])) {
		if (!verify_token($_SERVER['PHP_SELF'].'-ajout')) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (empty($frm['form_subject'])) {
			$form_error_object->add('form_subject');
		}
		if (empty($frm['form_text'])) {
			$form_error_object->add('form_text');
		}
		if (empty($frm['form_lang'])) {
			$form_error_object->add('form_lang');
		}
		if (empty($frm['form_id_cat'])) {
			$form_error_object->add('form_id_cat');
		}
		if (empty($frm['form_name'])) {
			$form_error_object->add('form_name');
		}
		if (!$form_error_object->count()) {
			$image_haut = upload('image_haut', false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image_haut']));
			$image_bas = upload('image_bas', false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image_bas']));
			query('INSERT INTO peel_email_template (site_id, technical_code, name, subject, text, lang, id_cat, default_signature_code, image_haut, image_bas ) VALUES(
				"' . nohtml_real_escape_string(trim(vn($frm['site_id']))) . '",
				"' . nohtml_real_escape_string(trim(vb($frm['form_technical_code']))) . '",
				"' . nohtml_real_escape_string(trim($frm['form_name'])) . '",
				"' . real_escape_string(trim($frm['form_subject'])) . '",
				"' . real_escape_string(trim($frm['form_text'])) . '",
				"' . nohtml_real_escape_string(trim($frm['form_lang'])) . '",
				"' . intval($frm['form_id_cat']) . '",
				"' . nohtml_real_escape_string(trim(vb($frm['default_signature_code']))) . '",
				"' . nohtml_real_escape_string($image_haut) . '",
				"' . nohtml_real_escape_string($image_bas) . '")');
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_MSG_TEMPLATE_CREATED']))->fetch();
		} else {
			if ($form_error_object->has_error('token')) {
				$output .= $form_error_object->text('token');
			} else {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_FILL_IN_ALL']))->fetch();
			}
		}
	}
	return $output;
}

/**
 *
 * Formulaire HTML d'ajout de template d'email HTML
 *
 * @param array $frm
 * @param string $insert_message
 * @param array $params
 * @return
 */
function insert_email_template_form($frm, $insert_message, $params = array()) {
	$hook_params['frm'] = $frm;
	$hook_params['params'] = $params;
	$hook_result = call_module_hook('create_or_update_template_form', $hook_params, 'array');
	// On va chercher les catégories
	$query = query('SELECT id, name_' . $_SESSION['session_langue'] . ' AS name, site_id
		FROM peel_email_template_cat
		WHERE ' . get_filter_site_cond('email_template_cat', null) . '
		ORDER BY name ASC');
	$tpl_categories_list = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_categories_list.tpl');
	$tpl_categories_list->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl_options = array();
	while ($row_categories = fetch_assoc($query)) {
		$tpl_options[] = array('value' => intval($row_categories['id']),
			'issel' => vb($frm['form_id_cat']) == $row_categories['id'],
			'name' => get_site_info($row_categories) . $row_categories['name']
			);
	}
	$tpl_categories_list->assign('options', $tpl_options);
	$categories_list = $tpl_categories_list->fetch();

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_output2.tpl');

	$tpl->assign('mission_statement_tag', null);
	$tpl->assign('params', $params);
	$tpl->assign('message_html', $insert_message);
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'].'-ajout'));
	$tpl->assign('action', get_current_url());
	$tpl->assign('show_tag_list', show_tag_list('clients', 'form_text'));
	$tpl->assign('categories_list', $categories_list);
	$tpl->assign('form_technical_code', vb($frm['form_technical_code']));
	$tpl->assign('form_name', vb($frm['form_name']));
	$tpl->assign('form_subject', vb($frm['form_subject']));
	// charge CKeditor
	$tpl->assign('form_text', getTextEditor('form_text', '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['form_text'])), null, 3));
	$tpl_langs = array();
	$langs_array = $GLOBALS['admin_lang_codes'];
	if (!empty($frm['form_lang']) && !in_array($frm['form_lang'], $GLOBALS['admin_lang_codes'])) {
		$langs_array[] = $frm['form_lang'];
	}
	foreach ($langs_array as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'issel' => vb($frm['form_lang']) == $lng || (empty($frm['form_lang']) && $lng == $_SESSION['session_langue'])
			);
	}
	$tpl->assign('signature_template_options', get_email_template_options('technical_code', null, null, null, true));
	$tpl->assign('langs', $tpl_langs);
	if (!empty($params['emailLinksExplanations'])) {
		$tpl->assign('emailLinksExplanations', emailLinksExplanations());
	}
	if (!empty($params['site_id'])) {
		$tpl->assign('site_id_select_options', get_site_id_select_options(vb($template_infos['site_id'])));
	}
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_MSG_LAYOUT_EXPLAINATION', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_MSG_LAYOUT_EXPLAINATION']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAGS_EXPLAIN', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAGS_EXPLAIN']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME']);
	$tpl->assign('STR_ADMIN_SUBJECT', $GLOBALS['STR_ADMIN_SUBJECT']);
	$tpl->assign('STR_TEXT', $GLOBALS['STR_TEXT']);
	$tpl->assign('STR_SIGNATURE', $GLOBALS['STR_SIGNATURE']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	
	return $tpl->fetch();
}

/**
 *
 * Bloc HTML de filtre sur les templates d'email
 *
 * @param array $frm
 * @return
 */
function email_template_search_form($frm) {
	// Filtre de recherche de modèle d'email
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_search.tpl');
	$form_error_object = new FormError();
	$tpl_options = array();
	// Récupération des catégories de template email
	$query = query('SELECT tc.id, tc.name_' . $_SESSION['session_langue'] . ' AS name, tc.site_id
		FROM peel_email_template_cat tc
		INNER JOIN peel_email_template t ON t.id_cat=tc.id AND t.active="TRUE" AND ' . get_filter_site_cond('email_template', 't', true) . '
		WHERE ' . get_filter_site_cond('email_template_cat', 'tc') . '
		GROUP BY tc.id
		ORDER BY name');
	while ($row_categories = fetch_assoc($query)) {
		$tpl_options[] = array('value' => intval($row_categories['id']),
			'issel' => vb($frm['form_lang_template']) == $row_categories['id'],
			'name' => get_site_info($row_categories) . $row_categories['name']
			);
	}
	$tpl->assign('options', $tpl_options);
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('name' => $lng,
			'value' => $lng,
			'issel' => vb($frm['form_lang_template']) == $lng
			);
	}
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('etat', vb($frm['etat']));
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
	$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
	$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
	$tpl->assign('STR_ADMIN_CHOOSE_SEARCH_CRITERIA', $GLOBALS['STR_ADMIN_CHOOSE_SEARCH_CRITERIA']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	return $tpl->fetch();
}



/**
 *
 * Gestion des modèles d'emails. Liste les templates d'email disponible.
 *
 * @param array $frm
 * @param array $params
 * @return
 */
function get_email_template_list($frm, $params = array()) {
	// Affichage de tous les templates
	$sql = 'SELECT id, technical_code, name, subject, text, lang, active, id_cat, site_id
		FROM peel_email_template
		WHERE ' . get_filter_site_cond('email_template', null, true);

	if (!empty($frm['form_lang_template'])) {
		$sql .= ' AND lang = "' . nohtml_real_escape_string($frm['form_lang_template']) . '"';
	}
	if (!empty($frm['form_id_cat'])) {
		$sql .= ' AND id_cat = "' . intval($frm['form_id_cat']) . '"';
	}
	if (isset($frm['etat']) && $frm['etat'] == "1") {
		$sql .= ' AND active = "TRUE"';
	} elseif (isset($frm['etat']) && $frm['etat'] == "0") {
		$sql .= ' AND active = "FALSE"';
	}

	$HeaderTitlesArray['id'] = $GLOBALS['STR_ADMIN_ID'];
	if (!empty($params['technical_code'])) {
		$HeaderTitlesArray['technical_code'] = $GLOBALS['STR_ADMIN_TECHNICAL_CODE'];
	}
	$HeaderTitlesArray['id_cat'] = $GLOBALS['STR_CATEGORY'];
	$HeaderTitlesArray['name'] = $GLOBALS['STR_ADMIN_NAME'];
	$HeaderTitlesArray['subject'] = $GLOBALS['STR_ADMIN_SUBJECT'];
	$HeaderTitlesArray['text'] = $GLOBALS['STR_ADMIN_HTML_TEXT'];
	$HeaderTitlesArray['lang'] = $GLOBALS['STR_ADMIN_LANGUAGE'];
	if (!empty($params['active'])) {
		$HeaderTitlesArray['active'] = $GLOBALS['STR_STATUS'];
	}
	$HeaderTitlesArray[] = $GLOBALS['STR_ADMIN_ACTION'];
	
	if (!empty($params['site_id'])) {
		$HeaderTitlesArray['site_id'] = $GLOBALS['STR_ADMIN_WEBSITE'];
	}
	$Links = new Multipage($sql, 'email_templates', 100);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = "technical_code, lang";
	$Links->SortDefault = "ASC";

	$results_array = $Links->query();

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_report.tpl');
	$tpl->assign('links_header_row', $Links->getHeaderRow());
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('create_href', vb($params['create_href']));
	$tpl->assign('entete_disable', vb($params['entete_disable']));
	$tpl->assign('head_links_multipage_disable', vb($params['head_links_multipage_disable']));
	$tpl->assign('STR_ADD_NEW_TEMPLATE', vb($GLOBALS['STR_ADD_NEW_TEMPLATE']));

	if (!empty($results_array)) {
		$tpl_results = array();
		$i = 0;
		$bold = 0;
		foreach ($results_array as $this_template) {
			// On récupère la catégorie du template (s'il en a une)
			$category_name = '';
			if ($this_template['id_cat'] != 0) {
				$query = query('SELECT name_' . $_SESSION['session_langue'] . ' AS name, site_id
					FROM peel_email_template_cat
					WHERE id=' . intval($this_template['id_cat']) . ' AND ' . get_filter_site_cond('email_template_cat', null));
				if($row_category = fetch_assoc($query)) {
					$category_name = get_site_info($row_category) . $row_category['name'];
				}else {
					$category_name = '';
				}
			}
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'id' => $this_template["id"],
				'technical_code' => StringMb::str_shorten_words($this_template["technical_code"], 20, '<br />'),
				'category_name' => $category_name,
				'name' => $this_template["name"],
				'subject' => StringMb::str_shorten_words($this_template["subject"], 40),
				'text' => StringMb::str_shorten(StringMb::str_shorten_words(StringMb::strip_tags($this_template["text"]), 40),1000),
				'lang' => $this_template["lang"],
				'etat_onclick' => 'change_status("email-templates", "' . $this_template['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . ($this_template["active"] != "TRUE" ? 'puce-blanche.gif' : 'puce-verte.gif'),
				'edit_href' => $params['modif_href'] . $this_template['id'],
				'site_name' => get_site_name($this_template['site_id'])
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('params', $params);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TITLE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TITLE']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_ADMIN_SUBJECT', $GLOBALS['STR_ADMIN_SUBJECT']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	$tpl->assign('STR_ADMIN_HTML_TEXT', $GLOBALS['STR_ADMIN_HTML_TEXT']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);

	return $tpl->fetch();
}


/**
 * Génère un bloc HTML d'explication sur l'usage des tags dans les modèles d'email.
 *
 * @return
 */
function emailLinksExplanations()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_emailLinksExplanations.tpl');
	if(empty($_SESSION['session_admin_multisite']) || $_SESSION['session_admin_multisite'] != $GLOBALS['site_id']) {
		$this_wwwroot =  get_site_wwwroot($_SESSION['session_admin_multisite'], $_SESSION['session_langue']);
	} else {
		$this_wwwroot =  $GLOBALS['wwwroot'];
	}
	$tpl->assign('link', $this_wwwroot);
	$tpl->assign('is_annonce_module_active', check_if_module_active('annonces'));
	$tpl->assign('is_vitrine_module_active', check_if_module_active('vitrine'));
	
	if(check_if_module_active('vitrine')) {
		$tpl->assign('explication_tag_windows', get_explication_tag_windows(true));
	}
	if(check_if_module_active('annonces')) {
		$tpl->assign('explication_tag_last_ads_verified', get_explication_tag_last_ads_verified(true));
		$tpl->assign('explication_tag_list_category_ads', get_explication_tag_list_category_ads(true));
		$tpl->assign('explication_tag_list_ads_by_category', get_explication_tag_list_ads_by_category(true));
	}
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_EXAMPLES_TITLE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_EXAMPLES_TITLE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAGS_EXPLAIN', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAGS_EXPLAIN']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_WWWROOT', $GLOBALS['STR_ADMIN_WWWROOT']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_SITE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_SITE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_PHP_SELF', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_PHP_SELF']);
	$tpl->assign('STR_ADMIN_REMOTE_ADDR', $GLOBALS['STR_ADMIN_REMOTE_ADDR']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_DATETIME', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_DATETIME']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_NEWSLETTER', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_NEWSLETTER']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_LINK_EXPLAIN', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_LINK_EXPLAIN']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_LINK_EXAMPLE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_LINK_EXAMPLE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_OTHER_AVAILABLE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_OTHER_AVAILABLE']);
	return $tpl->fetch();
}

/**
 * Affiche une liste de tag à utiliser dans le corps d'un email
 *
 * @param string $mode contient le nom de la table qui sert à composer la liste des tags. Si ce paramètre vaut mission_statement, alors on ne retourne que les tags concernés. Ce paramètre est utilisé pour afficher ou non les tags via une méthode ajax, cf $mode == 'get_mission_statement_tag' dans actions.php
 * @param string $message_field_name nom du champ textarea qui affiche le contenu de l'email. Le nom de ce champ est différent entre le formulaire de création de template et le formulaire d'envoi d'email
 * @return
 */
function show_tag_list($mode, $message_field_name = 'form_message')
{
	$output = '';
	// le hook retourne la liste des tags pour la configuration $mode demandé. Si le hook retourne un résultat, alors on le prend, sinon $mode a pour valeur le nom de la table qui sert de source pour générer les tags
	$hook_result = call_module_hook('show_tag_list', array('mode' => vb($mode)), 'array');
	if (!empty($hook_result)) {
		// le hook retourne un résultat, il est prioritaire sur le reste. Le tableau de retour du hook est formaté array('mode'=> array('tag1' => 'explication1', 'tag2' => 'explication2', ...))
		$all_tags_list_array = $hook_result;
	} elseif (in_array($mode, listTables())) {
		$tags_list_array = array();
		// le hook est vide, et le nom de $mode correspond à une table en BDD. On va prendre la liste de champs de cette table pour afficher les tags
		$table_field_names = get_table_field_names($mode);
		foreach($table_field_names as $this_field) {
			// get_table_field_names retourne un tableau de champs indexé numériquement (1=>champ1, 2=>champ2, ...) alors que l'on veut un autre format pour cette fonction. Le tableau doit avoir la forme 'champ'=>'explication'
			// => On compose un nouveau tableau formaté comme demandé plus bas dans la fonction
			$tags_list_array[$this_field] = vb($GLOBALS['database_field_titles_array'][$this_field]);
		}
		// tableau avec pour index le nom du mode (qui servira plus bas à afficher le titre si variable de langue présente) et en valeur le tableau de tag et explication associée
		$all_tags_list_array[$mode] = $tags_list_array;
	} else {
		$tags_list_array = array();
		$table_field_names = get_table_field_names('peel_utilisateurs');
		foreach($table_field_names as $this_field) {
			// tag générique PEEL
			$tags_list_array[$this_field] = "";
		}
		// tableau avec pour index le nom du mode (qui servira plus bas à afficher le titre si variable de langue présente) et en valeur le tableau de tag et explication associée
		$all_tags_list_array['peel_utilisateurs'] = $tags_list_array;
	}

	// code javascript permettant d'ajouter un tag dans le contenu HTML de l'éditeur CKEDITOR
	$GLOBALS['js_content_array'][] = '
		function add_tag_to_message(tag) {
			// $("#message").val($("#message").val() +" "+ tag);
			var val = CKEDITOR.instances["'.$message_field_name.'"].getData();
			CKEDITOR.instances["'.$message_field_name.'"].setData(val + " " + tag);
		}
';

	// Les tags sont affichés dans un encadré bleu, avec les liens pour afficher/masquer la totalité du bloc
	$output .= '<div class="alert alert-info" id="show_tag_list">' . $GLOBALS["STR_ADMIN_SHOW_TAG_LIST_EXPLANATION"] . '<br />';
	$output .= '<div class="less-content">';
	if (!empty($all_tags_list_array)) {
		foreach($all_tags_list_array as $this_taglist_name => $this_tag_list_array) {
			if (!empty($GLOBALS['STR_SPECIFIC_TAG_'. strtoupper($this_taglist_name) . '_EXPLAIN'])) {
				// Dans l'encadré on peut avoir des sous rubriques : pour les clients, pour les collaborateurs, pour le lettres de mission... Le titre de ces rubriques est composé du code technique du bloc de tag
				$output .= '<h3>'.$GLOBALS['STR_SPECIFIC_TAG_' . strtoupper($this_taglist_name) . '_EXPLAIN'].'</h3>';
			} 
			foreach($this_tag_list_array as $this_tag => $this_explain) {
				if (empty($this_explain)) {
					// pas de description trouvée, on rempli avec la description générique du titre
					$this_explain = vb($GLOBALS['database_field_titles_array'][$this_tag]);
				}
				$output .= '<a href="#" data-toggle="tooltip" title="'.$this_explain.'"  onclick="add_tag_to_message(\'['.strtoupper($this_tag).']\');return false;">['.strtoupper($this_tag).']</a> / ';
			}
		}
	}
	$output .= '</div>
			<p><a href="#" class="show-more">' . $GLOBALS["STR_ADMIN_MORE_INFOS"] . '...</a><a href="#" class="show-less collapse">' . $GLOBALS["STR_ADMIN_LESS_INFOS"] . '...</a></p>
			</div>';

	return $output;
}

/**
 * Affiche la affiche la liste des emails reçus
 *
 * @param array $recherche Array with all fields data
 * @param boolean $return_mode
 * @return
 */
function affiche_list_specific_form($recherche, $return_mode = false, $return = 'full_html')
{
	$output = '';
	$sql_cond = array();
	if (!empty($recherche)) {
		// Recherche par date
		if (!empty($recherche['numero_serie'])) {
			$sql_cond[] = ' s.numero_serie="' . nohtml_real_escape_string($recherche['numero_serie']) . '" ';
		}
		// Recherche par nom
		if (!empty($recherche['nom'])) {
			$sql_cond[] = ' s.nom LIKE "%' . nohtml_real_escape_string($recherche['nom']) . '%" ';
		}
		// Recherche par departement
		if (!empty($recherche['lieu_achat'])) {
			$sql_cond[] = ' s.lieu_achat= "' . nohtml_real_escape_string($recherche['lieu_achat']) . '" ';
		}
		// Recherche par email
		if (!empty($recherche['email'])) {
			$sql_cond[] = ' s.email= "' . nohtml_real_escape_string($recherche['email']) . '" ';
		}
	}
	$sql = "SELECT s.*
		FROM peel_specific_form s
		WHERE " . get_filter_site_cond('specific_form', 's', true) . "  " . (!empty($sql_cond)?' AND ' . implode(' AND ', $sql_cond):'') . "";
	$Links = new Multipage($sql, 'affiche_list_specific_form');
	$HeaderTitlesArray = array('','nom' => $GLOBALS['STR_LAST_NAME'], 'prenom' => $GLOBALS['STR_FIRST_NAME'],'email' => $GLOBALS['STR_EMAIL'],'genre' => $GLOBALS['STR_GENRE'],'tranche_age' => $GLOBALS['STR_TRANCHE_AGE'], 'numero_serie' => $GLOBALS["STR_ADMIN_SPECIFIC_FORM_SAVE_HIZERO_NUMBER_SERIE"], 'date_achat' => $GLOBALS['STR_DATE'], 'lieu_achat' => $GLOBALS["STR_ADMIN_SPECIFIC_FORM_SAVE_HIZERO_PLACE"],'departement' => $GLOBALS["STR_ADMIN_SPECIFIC_FORM_SAVE_HIZERO_DEPARTEMENT"],'nom_magasin' => $GLOBALS["STR_ADMIN_SPECIFIC_FORM_SAVE_HIZERO_NAME_SHOP"], 'offre_commercial' => $GLOBALS["STR_ADMIN_SPECIFIC_FORM_SAVE_HIZERO_COMMERCIAL_OFFER"], 'offre_newsletters' => $GLOBALS["STR_ADMIN_SPECIFIC_FORM_SAVE_HIZERO_NEWSLETTERS"]);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = "s.date_achat";
	$Links->SortDefault = "DESC";
	$results_array = $Links->Query();
	
	$output .= '
<form method="post" action=""' . get_current_url(false) . '"">
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">' .$GLOBALS["STR_ADMIN_SPECIFIC_FORM_SAVE_HIZERO"]. '</td>
		</tr>
		<tr>
			<td colspan="2">
				&nbsp;
				<input type="hidden" name="mode" value="search" />
			</td>
		</tr>';

	$output .= '
		<tr>
			<th>' . $GLOBALS['STR_ADMIN_NAME'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
			<td><input class="form-control" type="text" name="nom" value="' . StringMb::str_form_value(vb($recherche['nom'])) . '" /></td>
		</tr>
		<tr>
			<th>Email'. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
			<td><input class="form-control" type="text" name="email" value="' . StringMb::str_form_value(vb($recherche['email'])) . '" /></td>
		</tr>
		<tr>
			<th>Lieu d\'achat'. $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
			<td><input class="form-control" type="text" name="lieu_achat" value="' . StringMb::str_form_value(vb($recherche['lieu_achat'])) . '" /></td>
		</tr>
		<tr>
			<th>Numéro de série' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</th>
			<td><input class="form-control" type="numero_serie" name="numero_serie" value="' . StringMb::str_form_value(vb($recherche['numero_serie'])) . '" autocapitalize="none" /></td>
		</tr>';
	$output .= '
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" value="'.$GLOBALS['STR_SEARCH'].'" class="btn btn-primary" />
			</td>
		</tr>
	</table>
</form>
<form method="post" action=""' . get_current_url(false) . '"">
	<div class="table-responsive" style="margin-top:10px">
		<input type="hidden" name="mode" value="specific_form_delete" />';
$output_array = '
		<table id="tablesForm" class="table">
			' . $Links->getHeaderRow();
	$i = 0;
	if (empty($results_array)) {
		$output_array .= '<tr><td colspan="6" class="center"><b>'.$GLOBALS['STR_MODULE_WEBMAIL_ADMIN_NO_EMAIL_FOUND'].'</b></td></tr>';
	} else {
		foreach($results_array as $message) {
			$output_array .= tr_rollover($i, true) . '
				<td class="center" style="width:5px;">
					<input name="form_delete[]" type="checkbox" value="' . intval(vn($message['id'])) . '" id="cbx_' . intval(vn($message['id'])) . '" />
				</td>
				<td class="center" style="width:10%;">
					' . vb(vn($message['nom'])) . '
				</td>
				<td class="center" style="width:10%;">
					' . vb(vn($message['prenom'])) . '
				</td>
				<td class="center" style="width:10%;">
					' . vb(vn($message['email'])) . '
				</td>
				<td class="center" style="width:10%;">
					' . vb(vn($message['genre'])) . '
				</td>
				</td>
				<td class="center" style="width:10%;">
					' . vb(vn($message['tranche_age'])) . '
				</td>
				<td class="center" style="width:10%;">
					' . vb(vn($message['numero_serie'])) . '
				</td>
				<td class="center" style="width:10%;">
					' . vb(vn($message['date_achat'])) . '
				</td>
				<td class="center" style="width:10%;">
					' . vb(vn($message['lieu_achat'])) . '
				</td>
				<td class="center" style="width:10%;">
					' . vb(vn($message['departement'])) . '
				</td>
				<td class="center" style="width:10%;">
					' . vb(vn($message['nom_magasin'])) . '
				</td>
				<td class="center" style="width:10%;">
					' . intval(vn($message['offre_commercial'])) . '
				</td>
				<td class="center" style="width:10%;">
					' . intval(vn($message['offre_newsletters'])) . '
				</td>
			</tr>';
			$i++;
		}
	}
	$output_array .= '
		</table>';
	if ($return == 'html_array') {
		return $output_array;
	}
	$output .= $output_array;
	$output .= '
	</div>
	<div class="center">
		<input type="button" value="'.$GLOBALS["STR_ADMIN_CHECK_ALL"].'" onclick="if (markAllRows(\'tablesForm\')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
		<input type="button" value="'.$GLOBALS["STR_ADMIN_UNCHECK_ALL"].'" onclick="if (unMarkAllRows(\'tablesForm\')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
		<input type="submit" value="'.$GLOBALS["STR_DELETE"].'" class="btn btn-primary" name="save_number_serie_delete" />
		<a href="'.get_current_url(false).'?mode=export&sujet='.vb($_POST['sujet']).'" class="btn btn-primary" >'.$GLOBALS["STR_ADMIN_EXPORT"].'</a>
	</div>
	<div class="center">' . $Links->GetMultipage() . '</div>
</form>';
	if ($return_mode) {
		return $output;
	} elseif (!empty($output)) {
		echo $output;
	} else {
		return false;
	}
}
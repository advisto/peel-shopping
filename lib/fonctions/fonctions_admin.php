<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	|
// +----------------------------------------------------------------------+
// $Id: fonctions_admin.php 39495 2014-01-14 11:08:09Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * get_admin_menu()
 *
 * @return
 */
function get_admin_menu()
{
	if (IN_INSTALLATION) {
		$main_menu_items['install'] = array($GLOBALS['wwwroot'] . '/installation/' => $GLOBALS['STR_INSTALLATION']);
		$menu_items['install'][$GLOBALS['wwwroot'] . '/installation/index.php'] = $GLOBALS['STR_ADMIN_INSTALL_STEP1_TITLE'];
		$menu_items['install'][$GLOBALS['wwwroot'] . '/installation/bdd.php'] = $GLOBALS['STR_ADMIN_INSTALL_STEP2_TITLE'];
		$menu_items['install'][$GLOBALS['wwwroot'] . '/installation/choixbase.php'] = $GLOBALS['STR_ADMIN_INSTALL_STEP3_TITLE'];
		$menu_items['install'][$GLOBALS['wwwroot'] . '/installation/verifdroits.php'] = $GLOBALS['STR_ADMIN_INSTALL_STEP4_TITLE'];
		$menu_items['install'][$GLOBALS['wwwroot'] . '/installation/configuration.php'] = $GLOBALS['STR_ADMIN_INSTALL_STEP5_TITLE'];
		$menu_items['install'][$GLOBALS['wwwroot'] . '/installation/fin.php'] = $GLOBALS['STR_ADMIN_INSTALL_STEP6_TITLE'];
	} else {
		$main_menu_items['home'] = array($GLOBALS['administrer_url'] . '/' => $GLOBALS["STR_ADMIN_MENU_HOME_TITLE"]);
		$menu_items['home'][$GLOBALS['administrer_url'] . '/'] = $GLOBALS["STR_ADMIN_MENU_HOME_BACK"];
		$menu_items['home'][$GLOBALS['wwwroot'] . '/'] = $GLOBALS["STR_ADMIN_MENU_HOME_FRONT"];
		if (a_priv('admin_manage', true)) {
			$main_menu_items['manage'] = array($GLOBALS['administrer_url'] . '/sites.php' => $GLOBALS["STR_ADMIN_MENU_MANAGE_TITLE"]);
			$menu_items['manage']['manage_general'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_TITLE"];
			$menu_items['manage_general'][$GLOBALS['administrer_url'] . '/sites.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_SITES"];
			$menu_items['manage_general'][$GLOBALS['administrer_url'] . '/configuration.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_CONFIGURATION"];
			if (is_lot_module_active()) {
				$menu_items['manage_general'][$GLOBALS['wwwroot'] . '/modules/lot/administrer/lot.php?mode=edit_global_promotion_percent_by_threshold'] = $GLOBALS["STR_ADMIN_MENU_GLOBAL_PROMOTION_PERCENT_BY_THRESHOLD"];
			}
			$menu_items['manage_general'][$GLOBALS['administrer_url'] . '/societe.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_SOCIETE"];
			$menu_items['manage_general'][$GLOBALS['administrer_url'] . '/langues.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_LANGUES"];
			if (is_module_profile_active ()) {
				$menu_items['manage_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/profil/administrer/profil.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_PROFIL"];
			}
			$menu_items['manage_general'][$GLOBALS['administrer_url'] . '/clean_folders.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_CLEAN_FOLDERS"];
			if (is_butterflive_module_active ()) {
				$menu_items['manage_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/butterflive/admin/butterflive.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_BUTTERFLIVE"];
			}
			$menu_items['manage']['manage_payments'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_PAYMENT_AND_TAXES"];
			$menu_items['manage_payments'][$GLOBALS['administrer_url'] . '/paiement.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_PAYMENT"];
			if (is_devises_module_active ()) {
				$menu_items['manage_payments'][$GLOBALS['wwwroot_in_admin'] . '/modules/devises/administrer/devises.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DEVISES"];
			}
			$menu_items['manage_payments'][$GLOBALS['administrer_url'] . '/tva.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_TVA"];
			if (is_module_ecotaxe_active ()) {
				$menu_items['manage_payments'][$GLOBALS['administrer_url'] . '/ecotaxes.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_EXOTAXE"];
			}
			$menu_items['manage_payments'][$GLOBALS['administrer_url'] . '/statut_paiement.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PAYMENT_STATUS"];
			$menu_items['manage']['manage_delivery'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DELIVERY_HEADER"];
			$menu_items['manage_delivery'][$GLOBALS['administrer_url'] . '/pays.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_COUNTRIES"];
			$menu_items['manage_delivery'][$GLOBALS['administrer_url'] . '/zones.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_ZONES"];
			$menu_items['manage_delivery'][$GLOBALS['administrer_url'] . '/types.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DELIVERY"];
			$menu_items['manage_delivery'][$GLOBALS['administrer_url'] . '/tarifs.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DELIVERY_COST"];
			$menu_items['manage_delivery'][$GLOBALS['administrer_url'] . '/statut_livraison.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_STATUS"];
			if (is_icirelais_module_active ()) {
				$menu_items['manage_delivery'][$GLOBALS['wwwroot_in_admin'] . '/modules/icirelais/administrer/icirelais_file_synchronize_V2.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_ICIRELAIS"];
			}
			$menu_items['manage']['manage_emails'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_EMAILS_HEADER"];
			$menu_items['manage_emails'][$GLOBALS['administrer_url'] . '/email-templates.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_EMAIL"];
		}
		if (a_priv('admin_users', true)) {
			// Menu des utilisateurs
			$main_menu_items['users'] = array($GLOBALS['administrer_url'] . '/utilisateurs.php' => $GLOBALS["STR_ADMIN_MENU_USERS_USERS"]);
			$menu_items['users']['users_general'] = $GLOBALS["STR_ADMIN_MENU_USERS_USERS"];
			$menu_items['users_general'][$GLOBALS['administrer_url'] . '/utilisateurs.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_USERS_LIST"];
			$menu_items['users_general'][$GLOBALS['administrer_url'] . '/utilisateurs.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_USERS_USER_CREATE"];

			if (is_groups_module_active ()) {
				$menu_items['users_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/groups/administrer/groupes.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_GROUPS_LIST"];
			}
			$menu_items['users_general'][$GLOBALS['administrer_url'] . '/utilisateurs.php?mode=liste&priv=supplier'] = $GLOBALS["STR_ADMIN_MENU_USERS_SUPPLIERS_LIST"];
			$menu_items['users']['users_retaining'] = $GLOBALS["STR_ADMIN_MENU_USERS_RETAINING"];
			$menu_items['users_retaining'][$GLOBALS['administrer_url'] . '/newsletter.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_NEWSLETTER"];
			if (is_module_wanewsletter_active ()) {
				$menu_items['users_retaining'][$GLOBALS['wwwroot_in_admin'] . '/modules/newsletter/admin/admin.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_WANEWSLETTER"];
			}
			$menu_items['users_retaining'][$GLOBALS['administrer_url'] . '/codes_promos.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_CODE_PROMO"];
			if (is_good_clients_module_active ()) {
				$menu_items['users_retaining'][$GLOBALS['wwwroot_in_admin'] . '/modules/good_clients/administrer/bons_clients.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_BEST_CLIENTS"];
			}
			if (is_birthday_module_active ()) {
				$menu_items['users_retaining'][$GLOBALS['wwwroot_in_admin'] . '/modules/birthday/administrer/bons_anniversaires.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_BIRTHDAY"];
			}
			if (is_module_gift_checks_active ()) {
				$menu_items['users_retaining'][$GLOBALS['wwwroot_in_admin'] . '/modules/gift_check/administrer/cheques_cadeaux.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_GIFT_CHECKS"];
			}
			// Si le module commerciale existe, alors on affiche le menu relation client
			if (is_webmail_module_active() || is_relance_avance_module_active() || is_commerciale_module_active() || file_exists($GLOBALS['dirroot'] . '/modules/maps_users/administrer/map_google_search.php')) {
				$menu_items['users']['users_sales'] = $GLOBALS["STR_ADMIN_MENU_USERS_SALES_MANAGEMENT"];
				if (is_webmail_module_active()) {
					$menu_items['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/webmail_send.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND"];
					$menu_items['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/list_mails_send.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_SENT_EMAILS"];
					$menu_items['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/list_mails.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_LIST_EMAILS"];
				}
				if (is_relance_avance_module_active()) {
					$menu_items['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/relance_avance/administrer/relances.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_CONTACT_REMINDERS"];
				}
				if (is_commerciale_module_active()) {
					$menu_items['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/commerciale/administrer/list_admin_contact_planified.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_CLIENTS_TO_CONTACT"];
					$menu_items['users_sales'][$GLOBALS['administrer_url'] . '/utilisateurs.php?mode=search&commercial=' . $_SESSION['session_utilisateur']['id_utilisateur']] = sprintf($GLOBALS["STR_ADMIN_MENU_USERS_CLIENTS_PER_SALESMAN"], vb($_SESSION['session_utilisateur']['pseudo']));
				}
				if (file_exists($GLOBALS['dirroot'] . '/modules/maps_users/administrer/map_google_search.php')) {
					$menu_items['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/maps_users/administrer/map_google_search.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_USERS_MAP"];
				}
			}
		}
		if (a_priv('admin_products', true)) {
			$main_menu_items['products'] = array($GLOBALS['administrer_url'] . '/produits.php' => $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS"]);
			$menu_items['products']['products_general'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS"];
			$menu_items['products_general'][$GLOBALS['administrer_url'] . '/produits.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS_LIST"];
			$menu_items['products_general'][$GLOBALS['administrer_url'] . '/produits.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCT_ADD"];
			$menu_items['products_general'][$GLOBALS['administrer_url'] . '/positions.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS_ORDER"];
			$menu_items['products_general'][$GLOBALS['administrer_url'] . '/prix.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRICE_UPDATE"];
			$menu_items['products_general'][$GLOBALS['administrer_url'] . '/prix_pourcentage.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRICE_UPDATE_BY_PERCENTAGES"];
			if (is_telechargement_module_active ()) {
				$menu_items['products_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/telechargement/administrer/telechargement.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_DOWNLOADABLE_FILES"];
			}
			$menu_items['products']['products_categories'] = $GLOBALS["STR_ADMIN_CATEGORIES"];
			$menu_items['products_categories'][$GLOBALS['administrer_url'] . '/categories.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_CATEGORIES_LIST"];
			$menu_items['products_categories'][$GLOBALS['administrer_url'] . '/categories.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_CATEGORY_ADD"];
			$menu_items['products_categories'][$GLOBALS['administrer_url'] . '/marques.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_BRAND_LIST"];
			$menu_items['products']['products_attributes'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_ATTRIBUTES_HEADER"];
			$menu_items['products_attributes'][$GLOBALS['administrer_url'] . '/couleurs.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_COLORS"];
			$menu_items['products_attributes'][$GLOBALS['administrer_url'] . '/tailles.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_SIZES"];
			if (is_attributes_module_active ()) {
				$menu_items['products_attributes'][$GLOBALS['wwwroot_in_admin'] . '/modules/attributs/administrer/nom_attributs.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_ATTRIBUTES"];
				$menu_items['products_attributes'][$GLOBALS['wwwroot_in_admin'] . '/modules/attributs/administrer/attributs.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_OPTIONS"];
			}
			}
		if (a_priv('admin_sales', true)) {
			// Menu des ventes
			$main_menu_items['sales'] = array($GLOBALS['administrer_url'] . '/commander.php' => $GLOBALS["STR_ADMIN_MENU_SALES_SALES_TITLE"]);
			$menu_items['sales']['sales_general'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_HEADER"];
			$menu_items['sales_general'][$GLOBALS['administrer_url'] . '/commander.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_ORDERS"];
			$menu_items['sales_general'][$GLOBALS['administrer_url'] . '/commander.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_SALES_ORDER_CREATION"];
			if (is_payback_module_active ()) {
				$menu_items['sales_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/payback/administrer/retours.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PRODUCT_RETURN"];
			}
			if (is_module_export_ventes_active ()) {
				$menu_items['sales_general'][$GLOBALS['administrer_url'] . '/ventes.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_REPORT_HEADER"];
			} else {
				$menu_items['sales_general'][$GLOBALS['administrer_url'] . '/ventes.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_REPORT"];
			}

			if (is_kekoli_module_active ()) {
				$menu_items['sales_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/kekoli/administrer/kekoli.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_EXPORT"];
			}
			if (is_download_module_active ()) {
				$menu_items['sales_general'][$GLOBALS['administrer_url'] . '/commander.php?mode=download'] = $GLOBALS["STR_ADMIN_MENU_SALES_NUMERIC_SALES"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/hosting/administrer/hosting.php')) {
				$menu_items['sales']['sales_hosting'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_HOSTING_HEADER"];
				$menu_items['sales_hosting'][$GLOBALS['wwwroot_in_admin'] . '/modules/hosting/administrer/hosting.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_HOSTING"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/multisite/administrer/stats.php') || is_stats_module_active() || is_module_marge_active() || is_module_genere_pdf_active() || is_accounting_module_active()) {
				$menu_items['sales']['sales_accounting'] = $GLOBALS["STR_ADMIN_MENU_SALES_ACCOUNTING_HEADER"];
				if (file_exists($GLOBALS['dirroot'] . '/modules/multisite/administrer/stats.php')) {
					$menu_items['sales_accounting'][$GLOBALS['wwwroot_in_admin'] . '/modules/multisite/administrer/stats.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_MULTISITE_STATS"];
				}
				if (is_accounting_module_active() && a_priv('compta', true)) {
					$menu_items['sales_accounting'][$GLOBALS['wwwroot_in_admin'] . '/modules/accounting/administrer/index-compta.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_ACCOUNTING"];
				}
				if (is_stats_module_active ()) {
					$menu_items['sales_accounting'][$GLOBALS['wwwroot_in_admin'] . '/modules/statistiques/administrer/statcommande.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_STAT"];
				}
				if (is_module_marge_active ()) {
					$menu_items['sales_accounting'][$GLOBALS['wwwroot_in_admin'] . '/modules/marges/administrer/marges.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_MARGINS"];
				}
				if (is_module_genere_pdf_active ()) {
					$menu_items['sales_accounting'][$GLOBALS['wwwroot_in_admin'] . '/modules/facture_advanced/administrer/genere_pdf.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PDF_BILLS"];
				}
			}
			if (is_stock_advanced_module_active ()) {
				$menu_items['sales']['sales_stocks'] = $GLOBALS["STR_ADMIN_MENU_SALES_STOCKS_HEADER"];
				$menu_items['sales_stocks'][$GLOBALS['wwwroot_in_admin'] . '/modules/stock_advanced/administrer/stocks1clic.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_STOCK"];
				$menu_items['sales_stocks'][$GLOBALS['administrer_url'] . '/produits.php?mode=stocknul'] = $GLOBALS["STR_ADMIN_MENU_SALES_PRODUCTS_TO_ORDER"];
				$menu_items['sales_stocks'][$GLOBALS['wwwroot_in_admin'] . '/modules/stock_advanced/administrer/etatstock.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_STOCK_STATUS"];
				$menu_items['sales_stocks'][$GLOBALS['wwwroot_in_admin'] . '/modules/stock_advanced/administrer/alertes.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_STOCK_ALERTS"];
			}
			$menu_items['sales']['sales_delivery'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_HEADER"];
			if (is_module_export_livraisons_active ()) {
				$menu_items['sales_delivery'][$GLOBALS['administrer_url'] . '/livraisons.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_EXPORT"];
			} else {
				$menu_items['sales_delivery'][$GLOBALS['administrer_url'] . '/livraisons.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_REPORT"];
			}
			if (is_module_picking_active ()) {
				$menu_items['sales_delivery'][$GLOBALS['wwwroot_in_admin'] . '/modules/picking/administrer/picking.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PICKING_LIST"];
			}
		}
		if (a_priv('admin_content', true)) {
			$main_menu_items['content'] = array($GLOBALS['administrer_url'] . '/articles.php' => $GLOBALS["STR_ADMIN_MENU_CONTENT_TITLE"]);
			$menu_items['content']['content_articles'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_ARTICLES_HEADER"];
			$menu_items['content_articles'][$GLOBALS['administrer_url'] . '/articles.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_ARTICLES_LIST"];
			$menu_items['content_articles'][$GLOBALS['administrer_url'] . '/articles.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_ARTICLE_ADD"];
			$menu_items['content_articles'][$GLOBALS['administrer_url'] . '/rubriques.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_CATEGORIES_LIST"];
			$menu_items['content_articles'][$GLOBALS['administrer_url'] . '/rubriques.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_CATEGORY_ADD"];

			$menu_items['content']['content_general'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_HTML_HEADER"];
			$url_cgv = get_cgv_url(false);
			$menu_items['content_general'][$GLOBALS['administrer_url'] . '/cgv.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TERMS"];
			if (file_exists($GLOBALS['dirroot'] . '/modules/cgu-template/administrer/cgu-update.php')) {
				$menu_items['content_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/cgu-template/administrer/cgu-update.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TERMS_TEMPLATES"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/cgu-template/administrer/cgu.php')) {
				$menu_items['content_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/cgu-template/administrer/cgu.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TERMS_GENERATE"];
			}
			if (is_parrainage_module_active ()) {
				$menu_items['content_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/parrainage/administrer/parrain.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_SPONSOR_TERMS"];
			}
			$menu_items['content_general'][$GLOBALS['administrer_url'] . '/legal.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_LEGAL"];
			$menu_items['content_general'][$GLOBALS['administrer_url'] . '/plan.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_GOOGLEMAP"];
			$menu_items['content_general'][$GLOBALS['administrer_url'] . '/contacts.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_CONTACTS"];
			if (is_module_tagcloud_active ()) {
				$menu_items['content_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/tagcloud/administrer/tagcloud.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TAGCLOUD"];
			}
			if (is_module_faq_active()) {
				$menu_items['content_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/faq/administrer/faq.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_FAQ"];
			}
			if (is_lexique_module_active ()) {
				$menu_items['content_general'][$GLOBALS['wwwroot_in_admin'] . '/modules/lexique/administrer/lexique.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_LEXICAL"];
			}
			if (is_module_forum_active ()) {
				$menu_items['content']['content_forum'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_FORUM"];
				$menu_items['content_forum'][$GLOBALS['wwwroot_in_admin'] . '/modules/forum/administrer/list_forum_messages.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_FORUM_MESSAGES"];
				$menu_items['content_forum'][$GLOBALS['wwwroot_in_admin'] . '/modules/forum/administrer/list_forums.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_FORUMS"];
				$menu_items['content_forum'][$GLOBALS['wwwroot_in_admin'] . '/modules/forum/administrer/list_forum_cats.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_FORUM_CATEGORIES"];
			}
			$menu_items['content']['content_various'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_VARIOUS_HEADER"];
			$menu_items['content_various'][$GLOBALS['administrer_url'] . '/html.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_HTML"];
			if (is_module_banner_active()) {
				$menu_items['content_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/banner/administrer/banner.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_BANNERS"];
			}
			if (is_carrousel_module_active ()) {
				$menu_items['content_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/carrousel/administrer/carrousel.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_CARROUSEL"];
			}
			if (is_module_tagcloud_active()) {
				$menu_items['content_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/tagcloud/administrer/tagcloud.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TAGCLOUD"];
			}
			if (is_partenaires_module_active ()) {
				$menu_items['content_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/partenaires/administrer/categories_partenaires.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_PARTNERS_CATEGORIES"];
				$menu_items['content_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/partenaires/administrer/partenaires.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_PARTNERS"];
			}
			if (is_references_module_active()) {
				$menu_items['content_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/references/administrer/references.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_REFERENCES"];
				$menu_items['content_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/references/administrer/categories_references.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_REFERENCES_CATEGORIES"];
			}
		}
		if (a_priv('admin_moderation', true) || a_priv('admin_webmastering', true)) {
			$main_menu_items['webmastering'] = array($GLOBALS['administrer_url'] . '/produits_achetes.php' => $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_TITLE"]);
		}
		if (a_priv('admin_moderation', true)) {
			// Si le module vitrine existe ou module annonce
			if (is_vitrine_module_active() || is_annonce_module_active()) {
				if (is_annonce_module_active()) {
					$menu_items['webmastering']['moderation_ads'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_ADS_HEADER"];
					$menu_items['moderation_ads'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/annonces.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_ADS"];
					$menu_items['moderation_ads'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/annonces.php?mode=creation_gold'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_GOLD"];
					$menu_items['moderation_ads'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/categories.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_CATEGORIES"];
					$menu_items['moderation_ads'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/categories.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_CATEGORY_ADD"];
				}
				if (is_vitrine_module_active()) {
					$menu_items['webmastering']['moderation_stores'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_STORES_HEADER"];
					$menu_items['moderation_stores'][$GLOBALS['wwwroot_in_admin'] . '/modules/vitrine/administrer/vitrine.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_STORES"];
				}
				if (is_annonce_module_active()) {
					$menu_items['webmastering']['moderation_content'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_CONTENT"];
					$menu_items['moderation_content'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/black_list.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_BLACKLISTED_WORDS"];
					$menu_items['moderation_content'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/abus.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_ABUSE_REPORTS"];
				}
			}
			$menu_items['webmastering']['moderation_various'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_TITLE"];
			if (is_phone_cti_module_active()) {
				$menu_items['moderation_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/phone_cti/administrer/list_calls.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_PHONE_CALLS_KEYYO"];
			}
			$menu_items['moderation_various'][$GLOBALS['administrer_url'] . '/list_admin_actions.php?action_cat=PHONE'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_PHONE_CALLS"];
			$menu_items['moderation_various'][$GLOBALS['administrer_url'] . '/list_admin_actions.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_ADMIN_ACTIONS"];
			$menu_items['moderation_various'][$GLOBALS['administrer_url'] . '/connexion_user.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_USER_CONNEXIONS"];
		}
		if (a_priv('admin_webmastering', true)) {
			// Menu de webmastering
			$menu_items['webmastering']['webmastering_marketing'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_MARKETING"];
			if (is_module_avis_active ()) {
				$menu_items['webmastering_marketing'][$GLOBALS['wwwroot_in_admin'] . '/modules/avis/administrer/avis.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_OPINIONS"];
			}
			$menu_items['webmastering_marketing'][$GLOBALS['administrer_url'] . '/produits_achetes.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_BEST_PRODUCTS"];
			$menu_items['webmastering_marketing'][$GLOBALS['administrer_url'] . '/import_produits.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_IMPORT_PRODUCTS"];
			$menu_items['webmastering_marketing'][$GLOBALS['administrer_url'] . '/export_produits.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_EXPORT_PRODUCTS"];
			if (file_exists($GLOBALS['dirroot'] . '/modules/import/administrer/import_clients.php')) {
				$menu_items['webmastering_marketing'][$GLOBALS['wwwroot_in_admin'] . '/modules/import/administrer/import_clients.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_CLIENTS_IMPORT"];
			}
			if (is_module_export_clients_active ()) {
				$menu_items['webmastering_marketing'][$GLOBALS['wwwroot_in_admin'] . '/modules/export/administrer/export_clients.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_CLIENTS_EXPORT"];
			}
			if (is_expeditor_module_active ()) {
				$menu_items['webmastering_marketing'][$GLOBALS['wwwroot_in_admin'] . '/modules/expeditor/administrer/expeditor.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_EXPEDITOR"];
			}
			$menu_items['webmastering']['webmastering_seo'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_SEO_HEADER"];
			if (is_module_comparateur_active ()) {
				$menu_items['webmastering_seo'][$GLOBALS['wwwroot_in_admin'] . '/modules/comparateur/administrer/mysql2comparateur.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_COMPARATORS"];
			}
			$menu_items['webmastering_seo'][$GLOBALS['administrer_url'] . '/sitemap.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_SITEMAP"];
			$menu_items['webmastering_seo'][$GLOBALS['administrer_url'] . '/urllist.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_SITEMAP_URLLIST"];
			$menu_items['webmastering_seo'][$GLOBALS['administrer_url'] . '/meta.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_META"];
			if (is_affiliate_module_active ()) {
				$menu_items['webmastering']['webmastering_affiliate'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_AFFILIATE"];
				$menu_items['webmastering_affiliate'][$GLOBALS['administrer_url'] . '/commander.php?mode=affi'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_AFFILIATE_ORDERS"];
				$menu_items['webmastering_affiliate'][$GLOBALS['wwwroot_in_admin'] . '/modules/affiliation/administrer/ventes_affiliation.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_AFFILIATE_REPORT"];
				$menu_items['webmastering_affiliate'][$GLOBALS['wwwroot_in_admin'] . '/modules/affiliation/administrer/affiliation.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_AFFILIATE_TERMS"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/projects_management/administrer/projects.php')) {
				$menu_items['webmastering']['webmastering_projects'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_MANAGEMENT"];
				$menu_items['webmastering_projects'][$GLOBALS['wwwroot_in_admin'] . '/modules/projects_management/administrer/projects.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_TASKS"];
				$menu_items['webmastering_projects'][$GLOBALS['wwwroot_in_admin'] . '/modules/projects_management/administrer/project-custom-orders.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_SOLD"];
				$menu_items['webmastering_projects'][$GLOBALS['wwwroot_in_admin'] . '/modules/projects_management/administrer/project-events.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_CONTENT"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/calc/calc.php')) {
				$menu_items['webmastering']['webmastering_various'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_VARIOUS_HEADER"];
				$menu_items['webmastering_various'][$GLOBALS['wwwroot_in_admin'] . '/modules/calc/calc.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_CALC"];
			}
		}
	}
	$current_url = get_current_url(false);
	$current_url_full = get_current_url(true);

	$output = '
';
	$i = 0;
	foreach ($main_menu_items as $this_main_item => $this_main_array) {
		if (!empty($menu_items[$this_main_item]) && is_array($menu_items[$this_main_item])) {
			foreach(array_keys($menu_items[$this_main_item]) as $this_key) {
				$current_menu = (!empty($menu_items[$this_key][$current_url_full]));
				$full_match = true;
				if ($current_menu === false && !empty($menu_items[$this_key])) {
					$current_menu = (!empty($menu_items[$this_key][$current_url]));
					$full_match = false;
				}
				if(!empty($current_menu)) {
					break;
				}
			}
		} else {
			$current_menu = (!empty($menu_items[$this_main_item][$current_url_full]));
			$full_match = true;
			if ($current_menu === false && !empty($menu_items[$this_main_item])) {
				$current_menu = (!empty($menu_items[$this_main_item][$current_url]));
				$full_match = false;
			}
		}
		foreach ($this_main_array as $this_main_url => $this_main_title) {
			$main_class = array();
			$main_attributes = array();
			if ($current_menu !== false || !empty($this_main_array[$current_url]) || !empty($this_main_array[$current_url_full])) {
				$main_class[] = 'active';
			}
			if (!empty($menu_items[$this_main_item])) {
				$main_class[] = 'dropdown-toggle';
				$main_attributes[] = 'role="button" data-toggle="dropdown"';
				$this_main_url = '#';
			}
			if ($this_main_item == 'home') {
				$this_main_text = '<a id="menu_label_'.$this_main_item.'" title="' . $GLOBALS['STR_HOME'] . '" href="' . htmlspecialchars($this_main_url) . '" class="' . implode(' ', $main_class) . '" ' . implode(' ', $main_attributes) . '><span class="glyphicon glyphicon-home"></span></a>';
			} else {
				if (!empty($this_main_url) && !is_numeric($this_main_url)) {
					$this_main_text = '<a id="menu_label_'.$this_main_item.'" href="' . htmlspecialchars($this_main_url) . '" class="' . implode(' ', $main_class) . '" ' . implode(' ', $main_attributes) . '>' . $this_main_title . (!empty($menu_items[$this_main_item])?'<b class="caret"></b>':'') . '</a>';
				} else {
					$this_main_text = '<a id="menu_label_'.$this_main_item.'" href="#">' . $this_main_title . '</a>';
				}
			}
			if (!empty($menu_items[$this_main_item])) {
				$this_main_text .= '<ul class="sousMenu dropdown-menu" role="menu" aria-labelledby="menu_label_'.$this_main_item.'">
';
				foreach ($menu_items[$this_main_item] as $this_url => $this_submenu) {
					if (!empty($menu_items[$this_url]) && is_array($menu_items[$this_url])) {
						$this_main_text .= '<li class="dropdown-submenu">
							<a id="menu_'.substr(md5($this_url . $this_submenu),0,8).'" href="#" class="dropdown-toggle">' . String::strtoupper($this_submenu) . '</a>
							<ul class="sousMenu dropdown-menu" role="menu" aria-labelledby="menu_'.substr(md5($this_url . $this_submenu),0,8).'">
';
						foreach ($menu_items[$this_url] as $this_url => $this_title) {
							if (($current_url == $this_url && !$full_match) || $current_url_full == $this_url) {
								$class = ' class="active"';
							} elseif ($this_url == $GLOBALS['wwwroot_in_admin'] . '/modules/calc/calc.php') {
								$class = ' onclick="return(window.open(this.href)?false:true);"';
							} else {
								$class = '';
							}
							if (!empty($this_url) && !is_numeric($this_url)) {
								$this_text = '<a title="' . String::str_form_value($this_title) . '" href="' . htmlspecialchars($this_url) . '"' . $class . '>' . $this_title . '</a>';
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
			if ($req['l' . $i] != null && $req['l' . $i] != "" && intval($req['q' . $i]) > 0 && intval($req['p' . $i]) > 0)
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
		if ($req['produit'][$i] != null && $req['produit'][$i] != "" && intval($req['quantite'][$i]) > 0 && intval($req['prix'][$i]) > 0) {
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
		WHERE id = '" . intval($commandeid) . "'";
	$query = query($sql);
	$C = fetch_assoc($query);

	$custom_template_tags['ORDER_ID'] = $commandeid;
	$custom_template_tags['MODE'] = $mode;
	if ($prefered_mode == 'html' && is_module_factures_html_active()) {
		$template_technical_code = 'send_client_order_html';
		$custom_template_tags['URL_FACTURE'] = '<a href="' . $GLOBALS['wwwroot'] . '/modules/factures/commande_html.php?code_facture=' . urlencode($C['code_facture']) . '&partial=' . urlencode($partial) . '&mode=' . $mode . '" title="">' . $GLOBALS['STR_BOUGHT_FACTURE'] . '</a>';
	} else {
		$template_technical_code = 'send_client_order_pdf';
		$custom_template_tags['URL_FACTURE'] = $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . urlencode($C['code_facture']) . '&mode=' . $mode;
	}
	send_email($C['email'], '', '', $template_technical_code, $custom_template_tags, 'html', $GLOBALS['support_commande']);
}

/**
 * send_avis_expedition()
 *
 * @param integer $commandeid
 * @param integer $delivery_tracking
 * @return
 */
function send_avis_expedition($commandeid, $delivery_tracking)
{
	$resCom = query("SELECT *
		FROM peel_commandes
		WHERE id='" . intval($commandeid) . "'");
	$commande = fetch_object($resCom);
	$order_infos = get_order_infos_array($commande);

	$custom_template_tags['ORDER_ID'] = $commandeid;
	$custom_template_tags['TYPE'] = $commande->type;
	$custom_template_tags['COLIS'] = $delivery_tracking;
	$custom_template_tags['NOM_FAMILLE'] = $commande->nom_bill;
	$custom_template_tags['PRENOM'] = $commande->prenom_bill;
	$custom_template_tags['CLIENT_INFOS_SHIP'] = $order_infos['client_infos_ship'];
	$custom_template_tags['COUT_TRANSPORT'] = fprix($commande->cout_transport, true) . " " . $GLOBALS['STR_TTC'];

	$custom_template_tags['SHIPPED_ITEMS'] = '';
	$product_infos_array = get_product_infos_array_in_order($commandeid, $commande->devise, $commande->currency_rate);
	foreach ($product_infos_array as $this_ordered_product) {
		$custom_template_tags['SHIPPED_ITEMS'] .= $this_ordered_product["product_text"] . "\n";
		$custom_template_tags['SHIPPED_ITEMS'] .= $GLOBALS['STR_QUANTITY'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $this_ordered_product["quantite"] . "\n";
		$custom_template_tags['SHIPPED_ITEMS'] .= $GLOBALS['STR_PRICE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . fprix($this_ordered_product["total_prix"], true) . ' ' . $GLOBALS['STR_TTC'] . "\n\n";
	}
	send_email($commande->email, '', '', 'send_avis_expedition', $custom_template_tags, 'html', $GLOBALS['support_commande']);
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
	if (!($handle = String::fopen_utf8($GLOBALS['uploaddir'] . '/' . $local_filename, 'wb'))) {
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
	if (!($handle2 = String::fopen_utf8($GLOBALS['uploaddir'] . '/' . $destination_filename, 'wb'))) {
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
 * Fonction permettant de connaitre l'id d'un produit
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
		WHERE nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string(trim($name)) . '"' . (trim($name)!=$name ? ' OR nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string(String::strtolower($name)) . '"' : '') . '
		ORDER BY etat DESC, date_maj DESC
		LIMIT 1';
	$q = query($sql);
	if ($result = fetch_assoc($q)) {
		return $result['id'];
	} else {
		if($large_search) {
			$sql = 'SELECT id
				FROM peel_produits
				WHERE nom_' . $_SESSION['session_langue'] . ' LIKE "%' . nohtml_real_escape_string(String::strtolower(trim($name))) . '%"
				ORDER BY IF(nom_' . $_SESSION['session_langue'] . ' LIKE "' . nohtml_real_escape_string(String::strtolower(trim($name))) . '%",1,0) DESC, etat DESC, date_maj DESC
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
 * @param mixed $path
 * @return
 */
function execute_sql($path)
{
	$output = '';
	$sql = str_replace("\r\n", "\n", file_get_contents($path));
	$sql = str_replace("\r", "\n", $sql);
	// Toutes les lignes comprenant du SQL doivent se finir par ; sans aucun commentaire, sinon ça ne marchera pas
	while (String::strpos($sql, '; ') !== false) {
		$sql = str_replace("; ", ";", $sql);
	}
	$sql = str_replace(";\r", ";\n", $sql);
	// On supprime d'abord les commentaires
	$tab = explode("\n", $sql);
	$n = count($tab);
	for ($i = 0; $i < $n; $i++) {
		if ($tab[$i] == "" || String::substr($tab[$i], 0, 1) == '#' || String::substr($tab[$i], 0, 2) == '--') {
			// Cette ligne est un commentaire
			unset($tab[$i]);
		}
	}
	$sql = implode("\n", $tab);
	// On exécute les commandes SQL
	$tab = explode(";\n", $sql);
	ob_start();
	for ($i = 0; $i < count($tab); $i++) {
		query($tab[$i], false);
	}
	$output .= ob_get_contents();
	ob_end_clean();
	return $output;
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
			$get_options .= '<input type="hidden" name="' . $this_item . '" value="' . String::str_form_value($this_value) . '" />';
		}
	}
	$lang_select = '
<form id="langue" method="get" action="' . String::str_form_value(get_current_url(false)) . '" class="entryform form-inline">
	<div>'.$GLOBALS["STR_ADMIN_LANGUAGE"].$GLOBALS["STR_BEFORE_TWO_POINTS"].':
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
 * get_admin_site_select()
 *
 * @return
 */
function get_admin_site_select()
{
	$output = '';
	if (!IN_INSTALLATION) {
		$all_site_names = get_all_site_names();
		if (count($all_site_names) > 1) {
			$output .= '
<form method="get" id="ecom" action="' . get_current_url(false) . '" class="form-control">
	<select name="ecom" class="form-control" onchange="document.getElementById(\'ecom\').submit()">
		<option value="">' . $GLOBALS['STR_CHOOSE'] . '...</option>
';
			while ($res_boutik = fetch_assoc($query_boutik)) {
				$output .= '<option value="' . intval($res_boutik['id']) . '" ' . frmvalide($GLOBALS['site_parameters']['id'] == $res_boutik['id'], ' selected="selected"') . '>' . $res_boutik['nom'] . '</option>';
			}
			$output .= '
	</select>
</form>';
		}
	}
	return $output;
}

/**
 * Créer un code promo sur mesure
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_code_promo($frm)
{
	$qid = query("SELECT *
		FROM peel_codes_promos
		WHERE nom = '" . nohtml_real_escape_string(String::strtoupper($frm['nom'])) . "'");
	if ($result = fetch_assoc($qid)) {
		return false;
	}
	if (empty($frm["date_debut"])) {
		$frm["date_debut"] = get_formatted_date(time());
	} 
	if (empty($frm["date_fin"])) {
		$frm["date_fin"] = get_formatted_date(date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') + 30, date('Y'))));
	}
	if (empty($frm['on_type'])) {
		$frm['on_type'] = 2;
	}
	if (empty($frm['source'])) {
		$frm['source'] = 'CHQ';
	}
	if (!isset($frm['nombre_prevue'])) {
		$frm['nombre_prevue'] = 1;
	}
	if (!isset($frm['nb_used_per_client'])) {
		$frm['nb_used_per_client'] = 1;
	}

	$sql = "INSERT INTO peel_codes_promos (
		nom
		, date_debut
		, date_fin
		, remise_percent
		, remise_valeur
		, email_ami
		, email_acheteur
		, on_type
		, on_check
		, montant_min
		, etat
		, source
		, id_utilisateur
		, id_site
		, id_categorie
		, nombre_prevue
		, nb_used_per_client
	) VALUES (
		'" . nohtml_real_escape_string(String::strtoupper(vb($frm['nom']))) . "'
		, '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm["date_debut"])) . "'
		, '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm["date_fin"])) . "'
		, '" . (vb($frm['on_type']) == 1 ? floatval(get_float_from_user_input(vn($frm['remise_percent']))) : '0') . "'
		, '" . (vb($frm['on_type']) == 2 ? floatval(get_float_from_user_input(vn($frm['remise_valeur']))) : '0') . "'
		, '" . nohtml_real_escape_string(vb($frm['email_ami'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['email_acheteur'])) . "'
		, '" . intval($frm['on_type']) . "'
		, '" . intval(vb($frm['on_check'])) . "'
		, '" . floatval(get_float_from_user_input(vb($frm['montant_min']))) . "'
		, '" . intval(vn($frm['etat'])) . "'
		, '" . nohtml_real_escape_string(vb($frm['source'])) . "'
		, '" . intval(vb($frm['id_utilisateur'])) . "'
		, '" . intval($GLOBALS['site_parameters']['id']) . "'
		, '" . intval(vb($frm['id_categorie'])) . "'
		, '" . intval(vb($frm['nombre_prevue'])) . "'
		, '" . intval(vb($frm['nb_used_per_client'])) . "'
		)";
	query($sql);
	return insert_id();
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
			LEFT JOIN peel_categories pc ON pc.id=pcp.id_categorie
			WHERE pcp.id = '" . intval($id_codepromo) . "'";
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
			if (num_rows($requete) == 0) {
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
				send_email($email, '', '', 'envoie_client_code_promo', $custom_template_tags, 'html', $GLOBALS['support_sav_client']);
				return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_ADMIN_CODES_PROMOS_MSG_SENT_OK"], $cp['nom'], $user_infos['civilite'] . ' ' . $user_infos['prenom'] . ' ' . $user_infos['nom_famille'], $email)))->fetch();
			} else {
				return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS["STR_ADMIN_CODES_PROMOS_ERR_ALREADY_SENT"], $email . ' (' . $user_infos['prenom'] . ' ' . $user_infos['nom_famille'] . ')', $cp['nom'])))->fetch();
			}
		}
	}
	return false;
}

/**
 * nettoyer_dir()
 *
 * @param string $dir
 * @param integer $older_than_seconds
 */
function nettoyer_dir($dir, $older_than_seconds = 3)
{
	if (a_priv('demo')) {
		return false;
	}
	$files_deleted = 0;
	if ($dir != $GLOBALS['dirroot'] && $dir != $GLOBALS['dirroot'] . '/' && is_dir($dir) && ($dossier = opendir($dir))) {
		while ($file = readdir($dossier)) {
			if ($file != '.' && $file != '..' && $file[0] != '.' && filemtime($dir . '/' . $file) < time() - $older_than_seconds && is_file($dir . '/' . $file)) {
				// On efface les fichiers vieux de plus de $older_than_seconds secondes et qui ne sont pas des .htaccess
				unlink($dir . '/' . $file);
				$files_deleted++;
			} elseif ($file != '.' && $file != '..' && is_dir($dir . '/' . $file)) {
				// On efface récursivement le contenu des sous-dossiers
				$files_deleted += nettoyer_dir($dir . '/' . $file);
			}
		}
	}
	return $files_deleted;
}

/**
 * Affiche la liste des commandes
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_liste_commandes_admin($frm = null)
{
	$output = '';
	$sql_inner = '';
	$sql_cond = '';
	$sql = "";
	if(!empty($frm)) {
		if (!empty($frm['client_info'])) {
			$sql_cond .= ' AND (c.email LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"
				OR c.email_ship LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"
				OR c.email_bill LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"
				OR u.email LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"
				OR u.societe LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"
				OR u.nom_famille LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%"
				OR u.prenom LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%")';
			$sql_inner .= ' INNER JOIN peel_utilisateurs u ON c.id_utilisateur=u.id_utilisateur';
		}
		if (!empty($frm['searchProd'])) {
			$sql_cond .= ' AND ca.nom_produit LIKE "%' . nohtml_real_escape_string(String::strtolower(trim($frm['searchProd']))) . '%"';
			$sql_inner .= ' INNER JOIN peel_commandes_articles ca ON ca.commande_id=c.id';
		}
		if (isset($frm['statut_paiement']) && is_numeric($frm['statut_paiement'])) {
			$sql_cond .= ' AND c.id_statut_paiement="' . nohtml_real_escape_string($frm['statut_paiement']) . '"';
		}
		if (isset($frm['statut_livraison']) && is_numeric($frm['statut_livraison'])) {
			$sql_cond .= ' AND c.id_statut_livraison="' . nohtml_real_escape_string($frm['statut_livraison']) . '"';
		}
		if (!empty($frm['id'])) {
			$sql_cond .= ' AND (c.id="' . intval($frm['id']) . '" OR c.numero="' . intval($frm['id']) . '")';
		}
		if (!empty($frm['affi'])) {
			$sql_cond .= ' AND affilie = "1"';
		}
	}
	$sql = "SELECT c.*, c.id as order_id
		FROM peel_commandes c " . $sql_inner . "
		WHERE id_ecom = '" . intval($GLOBALS['site_parameters']['id']) . "'  " . $sql_cond . "";
	if(!empty($sql_inner)){
		$sql .="
		GROUP BY c.id";
	}
	$Links = new Multipage($sql, 'affiche_liste_commandes_admin');
	$HeaderTitlesArray = array($GLOBALS['STR_ADMIN_ACTION'], 'id' => $GLOBALS['STR_ADMIN_ID'], 'numero' => $GLOBALS["STR_ADMIN_COMMANDER_BILL_NUMBER"], 'o_timestamp' => $GLOBALS['STR_DATE'], 'montant' => $GLOBALS['STR_TOTAL'] . ' ' . (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']), $GLOBALS['STR_AVOIR'], 'id_utilisateur' => $GLOBALS['STR_CUSTOMER'], $GLOBALS['STR_PAYMENT'], $GLOBALS['STR_PAYMENT'], 'id_statut_paiement' => $GLOBALS['STR_PAYMENT'], 'id_statut_livraison' => $GLOBALS['STR_DELIVERY']);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = 'o_timestamp';
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
	$tpl->assign('is_fianet_sac_module_active', is_fianet_sac_module_active());
	$tpl->assign('is_duplicate_module_active', is_duplicate_module_active());
	if (!empty($results_array)) {
		$tpl_results = array();

		$tpl->assign('update_src', $GLOBALS['wwwroot_in_admin'] . '/images/update-on.png');
		$tpl->assign('links_header_row', $Links->getHeaderRow());

		$i = 0;
		foreach ($results_array as $order) {
			$this_sac_status = null;
			if (is_fianet_sac_module_active()) {
				require_once($GLOBALS['fonctionsfianet_sac']);
				// Même si la fonction get_sac_status permet de passer un tableau d'id de commande en paramètre, l'appel de la fonction ce fait ici pour des raisons 
				// de simplicité pour le moment. Une amélioration possible est d'appeler la fonction avant le foreach. Il faut pour cela récupérer 
				// les id de commandes du tableau $results_array.
				$get_sac_status = get_sac_status($order['order_id'], vb($_POST['fianet_sac_update_status']));
				$this_sac_status = $get_sac_status[$order['order_id']];
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
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'order_id' => $order['order_id'],
				'numero' => $order['numero'],
				'date' => get_formatted_date($order['o_timestamp']),
				'montant_prix' => fprix($montant_displayed, true, $order['devise'], true, $order['currency_rate']),
				'avoir_prix' => fprix($order['avoir'], true, $order['devise'], true, $order['currency_rate']),
				'modifUser' => $modifUser,
				'payment_name' => get_payment_name($order['paiement']),
				'payment_status_name' => get_payment_status_name($order['id_statut_paiement']),
				'delivery_status_name' => get_delivery_status_name($order['id_statut_livraison']),
				'dup_href' => get_current_url(false) . '?mode=duplicate&id=' . $order['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1),
				'dup_src' => $GLOBALS['administrer_url'] . '/images/duplicate.png',
				'this_sac_status' => $this_sac_status
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);

		$tpl->assign('payment_status_options2', get_payment_status_options());
		$tpl->assign('delivery_status_options2', get_delivery_status_options());
		$tpl->assign('links_multipage', $Links->GetMultipage());
	}
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
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
	$tpl->assign('STR_ADMIN_COMMANDER_NO_ORDER_FOUND', $GLOBALS['STR_ADMIN_COMMANDER_NO_ORDER_FOUND']);
	$tpl->assign('STR_NOTA_BENE', $GLOBALS['STR_NOTA_BENE']);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * Charge les détails d'une commande et les affiche
 *
 * @param integer $id
 * @param string $action Du type 'insere' ou 'ajout'
 * @param integer $user_id
 * @return
 */
function affiche_details_commande($id, $action, $user_id = 0)
{
	if(!empty($id)){
		$qid_commande = query("SELECT *
			FROM peel_commandes
			WHERE id_ecom = '" . intval($GLOBALS['site_parameters']['id']) . "' AND id = '" . intval($id) . "'");
		$commande = fetch_assoc($qid_commande);
	}
	if (!empty($commande) || $action == 'insere' || $action == 'ajout') {
		// Si nous somme en mode modif, alors on cherche les details de la commande
		if ($action != 'insere' && $action != 'ajout') {
			$date_facture = get_formatted_date($commande['a_timestamp']);
			// f_datetime est la date d'émission de la facture, insérée dans la BDD automatiquement au moment de l'intertion du numéro de facture, sinon par l'administrateur en back office.
			$f_datetime = get_formatted_date($commande['f_datetime']);
			// e_datetime est la date d'expédition de la commande, insérée dans la BDD automatiquement au moment du changement du statut de livraison de la facture, sinon par l'administrateur en back office.
			$e_datetime = get_formatted_date($commande['e_datetime']);

			if (display_prices_with_taxes_in_admin ()) {
				$montant_displayed = $commande['montant'];
			} else {
				$montant_displayed = $commande['montant_ht'];
			}
		} else {
			// $date_facture = Date du jour
			$date_facture = get_formatted_date(time());
			$montant_displayed = 0;
		}
		// Affiche le modeles d'une commande en detail
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
				$commande['pays_' . $state] = get_country_name(vn($user_array['pays']));
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
			$commande['pays_bill'] = get_country_name(vn($GLOBALS['site_parameters']['default_country_id']));
			$commande['pays_ship'] = get_country_name(vn($GLOBALS['site_parameters']['default_country_id']));
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
			$tpl->assign('allow_display_invoice_link', !empty($commande['numero']));
			$tpl->assign('facture_pdf_href', $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=facture');
			$tpl->assign('sendfacture_pdf_href', $GLOBALS['administrer_url'] . '/commander.php?mode=sendfacturepdf&id=' . vn($commande['id']) . '&code_facture=' . vb($commande['code_facture']) . '&bill_type=facture');
			$tpl->assign('proforma_pdf_href', $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=proforma');
			$tpl->assign('sendproforma_pdf_href', $GLOBALS['administrer_url'] . '/commander.php?mode=sendfacturepdf&id=' . vn($commande['id']) . '&code_facture=' . vb($commande['code_facture']) . '&bill_type=proforma');
			$tpl->assign('devis_pdf_href', $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=devis');
			$tpl->assign('senddevis_pdf_href', $GLOBALS['administrer_url'] . '/commander.php?mode=sendfacturepdf&id=' . vn($commande['id']) . '&code_facture=' . vb($commande['code_facture']) . '&bill_type=devis');
			$tpl->assign('bdc_pdf_href', $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=bdc');
			$tpl->assign('duplicate', $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=bdc');
			$tpl->assign('bdc_pdf_href', $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . vb($commande['code_facture']) . '&mode=bdc');
			
			$tpl->assign('is_duplicate_module_active', is_duplicate_module_active());
			$tpl->assign('dup_href', get_current_url(false) . '?mode=duplicate&id=' . $commande['id']);
			$tpl->assign('dup_src', $GLOBALS['administrer_url'] . '/images/duplicate.png');
			$tpl->assign('STR_ADMIN_ORDER_DUPLICATE', $GLOBALS['STR_ADMIN_ORDER_DUPLICATE']);
			$tpl->assign('STR_ADMIN_ORDER_DUPLICATE_WARNING', $GLOBALS['STR_ADMIN_ORDER_DUPLICATE_WARNING']);
			
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
			$tpl->assign('e_datetime', (empty($e_datetime) ? "" : vb($e_datetime)));
			$tpl->assign('f_datetime', (empty($f_datetime) ? "" : vb($f_datetime)));
			$tpl->assign('intracom_for_billing', vb($commande['intracom_for_billing']));
			$tpl->assign('commande_date', get_formatted_date(vb($commande['o_timestamp'])));
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
			$tpl->assign('icirelais', array(
				'src' => $GLOBALS['wwwroot'] . '/modules/icirelais/js/icirelais.js',
				'value' => vb($commande['delivery_tracking'])
			));
			$tpl->assign('STR_MODULE_ICIRELAIS_CONFIGURATION_TRACKING_URL_TITLE', $GLOBALS['STR_MODULE_ICIRELAIS_CONFIGURATION_TRACKING_URL_TITLE']);
			$tpl->assign('MODULE_ICIRELAIS_SETUP_TRACKING_URL', MODULE_ICIRELAIS_SETUP_TRACKING_URL);
			$tpl->assign('STR_MODULE_ICIRELAIS_COMMENT_TRACKING', $GLOBALS['STR_MODULE_ICIRELAIS_COMMENT_TRACKING']);
			$tpl->assign('STR_MODULE_ICIRELAIS_ERROR_TRACKING', $GLOBALS['STR_MODULE_ICIRELAIS_ERROR_TRACKING']);
			$tpl->assign('STR_MODULE_ICIRELAIS_CREATE_TRACKING', $GLOBALS['STR_MODULE_ICIRELAIS_CREATE_TRACKING']);
		}

		if((!empty($id) && $commande['montant'] > 0) || empty($id)) {
			$tpl->assign('payment_select', get_payment_select(vb($commande['payment_technical_code'])));
		}

		$tpl->assign('payment_status_options', get_payment_status_options(vn($commande['id_statut_paiement'])));
		$tpl->assign('delivery_status_options', get_delivery_status_options(vn($commande['id_statut_livraison'])));

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


		$tpl->assign('is_gifts_module_active', is_gifts_module_active());
		if (is_gifts_module_active()) {
			$tpl->assign('total_points', vn($commande['total_points']));
			$tpl->assign('points_etat', vn($commande['points_etat']));
		}
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
				'country_select_options' => get_country_select_options(vb($commande['pays_' . $value]), null, 'name', false, null, true, vb($commande['lang']))
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
					$possible_sizes[$line_data['taille_id']] = $line_data['taille'];
				}
				$size_options_html = '';
				if (!empty($possible_sizes)) {
					foreach ($possible_sizes as $this_size_id => $this_size_name) {
						$size_options_html .= '<option value="' . intval($this_size_id) . '" ' . frmvalide($this_size_name == $line_data['taille'], ' selected="selected"') . '>' . $this_size_name . '</option>';
					}
				}
				$possible_colors = $product_object->get_possible_colors();
				if (!empty($line_data['couleur']) && !in_array($line_data['couleur'], $possible_colors)) {
					$possible_colors[$line_data['couleur_id']] = $line_data['couleur'];
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

		$GLOBALS['js_content_array'][] = "new_order_line_html='".filtre_javascript(get_order_line(array('id' => '[id]', 'ref' => '[ref]', 'nom' => '[nom]', 'quantite' => '[quantite]', 'remise' => '[remise]', 'remise_ht' => '[remise_ht]', 'percent' => '[percent]', 'purchase_prix' => '[purchase_prix]', 'purchase_prix_ht' => '[purchase_prix_ht]', 'tva_percent' => '[tva_percent]', 'prix_cat' => '[prix_cat]', 'prix_cat_ht' => '[prix_cat_ht]'), '[color_options_html]', '[size_options_html]', '[tva_options_html]', '[i]'), true, true, false)."';";

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
		$tpl->assign('default_vat_select_options', get_vat_select_options('20.00'));
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_COMMANDER_WARNING_EDITION_NOT_ALLOWED', $GLOBALS['STR_ADMIN_COMMANDER_WARNING_EDITION_NOT_ALLOWED']);
		$tpl->assign('STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE', $GLOBALS['STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE']);
		$tpl->assign('STR_INVOICE', $GLOBALS['STR_INVOICE']);
		$tpl->assign('STR_ADMIN_CREATE_BILL_NUMBER_BEFORE', $GLOBALS['STR_ADMIN_CREATE_BILL_NUMBER_BEFORE']);
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

		return $tpl->fetch();
	} elseif (!empty($id)) {
		return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS["STR_ADMIN_COMMANDER_NO_ORDER_WITH_ID_FOUND"], $id)))->fetch();
	}
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
			WHERE id = '" . intval($frm['id']) . "'";
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
	$frm['total_ecotaxe_ttc'] = 0;
	$frm['total_ecotaxe_ht'] = 0;
	$frm['total_poids'] = 0;
	$frm['total_points'] = 0;
	if (!isset($frm['delivery_tracking'])) {
		$frm['delivery_tracking'] = null;
	}
	// On complète les données si nécessaire
	if (empty($frm['societe2']) && empty($frm['nom2']) && empty($frm['prenom2'])) {
		// On ne remplit automatiquement la société et le nom que si vraiment l'ensemble de l'adresse de livraison n'était pas définie
		$frm['societe2'] = vb($frm['societe1']);
		$frm['nom2'] = vb($frm['nom1']);
		$frm['prenom2'] = vb($frm['prenom1']);
	}
	foreach (array('societe', 'prenom', 'nom', 'adresse', 'code_postal', 'ville', 'pays', 'email', 'contact') as $this_item) {
		if (empty($frm[$this_item . '2'])) {
			$frm[$this_item . '2'] = vb($frm[$this_item . '1']);
		}
	}
	if (empty($frm['nb_produits'])) {
		$frm['nb_produits'] = 5;
	}

	if (empty($frm['commandeid'])) {
		if (!empty($frm['email1'])) {
			// On crée une nouvelle commande
			$sql = "SELECT id_utilisateur, email
				FROM peel_utilisateurs
				WHERE email = '" . nohtml_real_escape_string($frm['email1']) . "'";
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
			$frm['id_utilisateur'] = insere_utilisateur($new_user_infos, false, false, false);
		} else {
			// commande sans utilisateur associé.
			$frm['id_utilisateur'] = 0;
		}
	}
	// Calcul des coûts et insertion de la commande
	if ((empty($frm['currency_rate']) || empty($frm['devise']))) {
		if (isset($frm['devise']) && $frm['devise'] != $GLOBALS['site_parameters']['code']) {
			// Si la devise de la commande n'est pas celle de la boutique, alors on récupère le taux de change de la devise
			$res = query("SELECT p.conversion
				FROM peel_devises p
				WHERE p.code = '" . nohtml_real_escape_string($frm['devise']) . "'");
		}
		if (!empty($res) && $tab = fetch_assoc($res)) {
			$frm['currency_rate'] = $tab['conversion'];
		} else {
			// Valeur par défaut de la devise
			$frm['devise'] = $GLOBALS['site_parameters']['code'];
			$frm['currency_rate'] = $GLOBALS['site_parameters']['conversion'];
		}
	}
	// On récupère les informations sur les zones
	$sqlPays = 'SELECT p.id, p.pays_' . $_SESSION['session_langue'] . ' as pays, p.zone, z.tva, z.on_franco
		FROM peel_pays p
		LEFT JOIN peel_zones z ON z.id=p.zone
		WHERE p.etat = "1" AND p.pays_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($frm['pays2']) . '"
		LIMIT 1';
	$query = query($sqlPays);
	if ($result = fetch_assoc($query)) {
		$frm['pays'] = $result['pays'];
		$frm['zoneId'] = $result['zone'];
		if(!isset($frm['apply_vat'])){
			// Si $frm['apply_vat'] est déjà défini, alors on garde la valeur qui a priorité sur la configuration du pays en BDD
			$frm['apply_vat'] = ($result['tva'] && !is_user_tva_intracom_for_no_vat($frm['id_utilisateur']) && !is_micro_entreprise_module_active());
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
	// On calcul les totaux de produits
	for ($i = 1; $i <= $frm['nb_produits']; $i++) {
		if (isset($frm["p" . $i]) && isset($frm["q" . $i]) && isset($frm["t" . $i])) {
			if (is_conditionnement_module_active() && !empty($frm["cdt" . $i])) {
				// Les produits sont conditionnés sous forme de lot, mais lorsque ce module est activé
				// on souhaite gérer les quantités et les stocks par produits individuels
				$real_stock_used = $frm["cdt" . $i] * $frm["q" . $i];
			} else {
				// Cas général de gestion des quantités
				$real_stock_used = intval($frm["q" . $i]);
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
				// Pas de TVA applicable pour cette commande. Maintenant qu'ona bien calculé les HT, on fait en sorte que les TTC soient égaux aux HT
				$total_produit = $total_produit_ht;
				$total_remise = $total_remise_ht;
			}
		}
	}
	// Insertion des produits commandés
	$total_ttc = 0;

	for ($i = 1; $i <= $frm['nb_produits']; $i++) {
		if (!isset($frm["l" . $i]) || empty($frm["q" . $i])) {
			continue;
		}
		// Récupère les variables dans le formulaire
		$nom = $frm["l" . $i];
		$quantite = get_float_from_user_input($frm["q" . $i]);
		if (empty($quantite)) {
			continue;
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
			// Pas de TVA applicable pour cette commande. Maintenant qu'ona bien calculé les HT, on fait en sorte que les TTC soient égaux aux HT
			$frm["t" . $i] = 0;
			$prix_cat = $prix_cat_ht;
			$prix = $prix_ht;
			$remise = $remise_ht;
		}
		// Calcul remise en %
		$remise_percent = get_float_from_user_input(vn($frm["perc" . $i]));
		if (is_conditionnement_module_active() && !empty($frm["cdt" . $i])) {
			// Les produits sont conditionnés sous forme de lot, mais lorsque ce module est activé
			// on souhaite gérer les quantités et les stocks par produits individuels
			$real_stock_used = get_float_from_user_input($frm["cdt" . $i]) * $quantite;
		} else {
			// Cas général de gestion des quantités
			$real_stock_used = $quantite;
		}
		$total_prix = $prix * $real_stock_used;
		$total_prix_ht = $prix_ht * $real_stock_used;
		$tva = $total_prix - $total_prix_ht;
		// Lie la commande au produit
		if(!empty($frm["id" . $i])) {
			$this_article['product_id'] = $frm["id" . $i];
		} else {
			$this_article['product_id'] = get_product_id_by_name($nom);
		}
		$product_object = new Product($this_article['product_id'], null, false, null, true, !is_micro_entreprise_module_active());
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
		if (is_module_ecotaxe_active ()) {
			$product_ecotaxe_infos_query = query("SELECT e.*
				FROM peel_ecotaxes e
				INNER JOIN peel_produits p ON e.id = p.id_ecotaxe
				WHERE p.id='" . intval($this_article['product_id']) . "'");
			if ($product_ecotaxe_infos = fetch_assoc($product_ecotaxe_infos_query)) {
				if (!empty($product_ecotaxe_infos['id'])) {
					$this_article['ecotaxe_ttc'] = $product_ecotaxe_infos['prix_ttc'];
					$this_article['ecotaxe_ht'] = $product_ecotaxe_infos['prix_ht'];
				} else {
					$this_article['ecotaxe_ttc'] = 0;
					$this_article['ecotaxe_ht'] = 0;
				}
				// Valeurs globales pour l'ensemble des produits
				$frm['total_ecotaxe_ht'] += $this_article['ecotaxe_ht'];
				$frm['total_ecotaxe_ttc'] += $this_article['ecotaxe_ttc'];
			}
		}
		$this_article['etat_stock'] = $product_object->on_stock;

		$this_article['nom_attribut'] = vn($frm['nom_attribut_' . $i]);
		$this_article['total_prix_attribut'] = vn($frm['total_prix_attribut_' . $i]);

		$total_prix_attribut_ht = $this_article['total_prix_attribut'] / (1 + $tva / 100); // recupération du prix des attributs en ht pour utiliser dans le calcul de option_ht
		// Informations supplémentaires (non modifiable dans la mofification de la commande)
		$this_article['delai_stock'] = $product_object->delai_stock;

		$product_object->set_configuration($this_article['couleurId'], $this_article['tailleId'], null, is_reseller_module_active() && is_reseller()); // on fixe les options
		$this_article['poids'] = ($product_object->poids + $product_object->configuration_overweight) * $this_article['quantite'];
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
	}
    if ($frm['small_order_overcost_amount'] == '') {
        if($total_produit < $GLOBALS['site_parameters']['small_order_overcost_limit'] && $total_produit >= $GLOBALS['site_parameters']['minimal_amount_to_order']) {
            $small_order_overcost_amount = $GLOBALS['site_parameters']['small_order_overcost_amount'];
        } else {
            $small_order_overcost_amount = 0;
        }
    }  else {
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
	// Calcul du sous total pour pouvoir appliquer le cout du paiement en pourcentage
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
 * @return
 */
function get_order_line($line_data, $color_options_html, $size_options_html, $tva_options_html, $i)
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
	if (is_attributes_module_active()) {
		$attribute_display = str_replace("\n", '<br />', display_option_image(vb($line_data['nom_attribut']), true));
	}

	$output = '
				<tr class="top" id="line' . $i . '">
					<td>
						<img src="' . $GLOBALS['administrer_url'] . '/images/b_drop.png" alt="'.String::str_form_value($GLOBALS['STR_DELETE']) . '" onclick="bootbox.confirm(\''.filtre_javascript($GLOBALS["STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM"], true, true, true) .'\', function(result) {if(result) {delete_products_list_line(' . $i . ', \'order\');}}); return false;" title="' . String::str_form_value($GLOBALS["STR_ADMIN_PRODUCT_ORDERED_DELETE"]) . '" style="cursor:pointer" />
						<input class="form-control" name="nom_attribut_' . $i . '" type="hidden" value="' . String::str_form_value(vb($line_data['nom_attribut'])) . '" />
						<input class="form-control" name="total_prix_attribut_' . $i . '" type="hidden" value="' . String::str_form_value(vb($line_data['total_prix_attribut'])) . '" />
					</td>
					<td>
						<input class="form-control" name="id' . $i . '" style="width:100%" type="number" value="' . String::str_form_value(vb($line_data['id'])) . '" />
					</td>
					<td>
						<input class="form-control" id="ref' . $i . '" name="ref' . $i . '" style="width:100%" type="text" value="' . String::str_form_value(vb($line_data['ref'])) . '" />
					</td>
					<td>
						<input class="form-control" type="text" id="l' . $i . '" name="l' . $i . '" style="width:100%" value="' . String::str_form_value($line_data['nom']) . '" />' . (isset($line_data['on_download'])?($line_data['on_download'] == 1?'<br/><a href="' . get_current_url(false) . '?mode=download">'.$GLOBALS["STR_ADMIN_PRODUITS_NUMERIC_PRODUCT_SEND"].'</a>':''):'') . '
					</td>
					<td id="s' . $i . '" class="center"><select style="width:64px" name="size_' . $i . '" class="form-control">' . $size_options_html . '</select></td>
					<td id="c' . $i . '" class="center"><select style="width:64px" name="color_' . $i . '" class="form-control">' . $color_options_html . '</select></td>
					<td><input class="form-control" type="number" name="q' . $i . '" style="width:100%" value="' . String::str_form_value($line_data['quantite']) . '" id="q' . $i . '" /></td>
					<td><input class="form-control" type="text" name="p_cat' . $i . '" style="width:100%" value="' . String::str_form_value($prix_cat_displayed) . '" id="p_cat' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'percentage\');" /></td>
					<td><input class="form-control" type="text" name="remis' . $i . '" style="width:100%" value="' . String::str_form_value($unit_fixed_remise_displayed) . '" id="remis' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'amount\');" /></td>
					<td><input class="form-control" type="text" name="perc' . $i . '" style="width:100%" value="' . String::str_form_value($line_data['percent']) . '" id="perc' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'percentage\');" /></td>
					<td><input class="form-control" type="text" name="p' . $i . '" style="width:100%" value="' . String::str_form_value($purchase_prix_displayed) . '" id="p' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'final\');" /></td>
					<td id="t' . $i . '">
						<select name="t' . $i . '" class="form-control">' . $tva_options_html . '</select>
					</td>
					<td> ' . vb($attribute_display) . ' </td>
				</tr>
';

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
		LEFT JOIN peel_utilisateurs u ON u.id_utilisateur=a.id_user
		WHERE a.id_membre="' . intval($user_id) . '"
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
					<th class="menu" style="width:' . $width['remarque'] . '%;">'.$GLOBALS['STR_ADMIN_COMMENTS'].'</th>
				</tr>';
		}

		$texte = nl2br($res['remarque']);

		if ($res['data'] != "" && $res['action'] == 'SEND_EMAIL') {
			// Si un template a été envoyé, alors on récupère le contenu de ce template
			$data = explode('_', $res['data']);
			if (count($data) == 2) {
				$template_id = $data[1];
				if (is_numeric($template_id)) {
					$result_template = query('SELECT name
						FROM peel_email_template
						WHERE id="' . intval($template_id) . '"
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
	query('INSERT INTO peel_admins_actions(id_user, action, id_membre, data, remarque, raison, date)
		VALUES("' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '", "' . nohtml_real_escape_string($action) . '", "' . intval(vn($member_id)) . '", "' . nohtml_real_escape_string($data) . '", "' . nohtml_real_escape_string($remarque) . '", "' . nohtml_real_escape_string($raison) . '", "' . date('Y-m-d H:i:s', time()) . '")');
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
			$sql_cond .= ' AND (u.pseudo LIKE "%' . nohtml_real_escape_string($frm['client_info']) . '%")';
			$sql_inner .= ' INNER JOIN peel_utilisateurs u ON c.user_id=u.id_utilisateur';
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
		FROM peel_utilisateur_connexions c " . $sql_inner . "
		WHERE 1 " . $sql_cond . "";

	$Links = new Multipage($sql, 'affiche_liste_connexion_user');
	$HeaderTitlesArray = array('id' => $GLOBALS["STR_ADMIN_ID"], 'date' => $GLOBALS['STR_DATE'], 'user_ip' => $GLOBALS["STR_ADMIN_REMOTE_ADDR"]);
	if (file_exists($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php')) {
		include_once($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php');
		$geoIP = new geoIP();
		$HeaderTitlesArray[] = $GLOBALS['STR_COUNTRY']. '-IP';
		$HeaderTitlesArray[] = $GLOBALS['STR_COUNTRY'];
	}
	if(is_annonce_module_active()) {
		$HeaderTitlesArray[] = $GLOBALS["STR_MODULE_ANNONCES_ADS"];
	}
	$HeaderTitlesArray['user_login'] = $GLOBALS["STR_ADMIN_LOGIN"];
	$HeaderTitlesArray['user_id'] = $GLOBALS["STR_ADMIN_USER"];
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
				$current_user = get_user_information($connexion['user_id'], true);
				$tpl_result = array('id' => $connexion['id'],
					'date' => get_formatted_date($connexion['date'], 'short', true),
					'ip' => (!a_priv('demo') ? long2ip($connexion['user_ip']): '0.0.0.0 [demo]'),
					'user_id' => (!a_priv('demo')?'<a href="' . $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&amp;id_utilisateur=' . intval($connexion['user_id']) . '">'.$connexion['user_id'].'</a>':'private [demo]'),
					'prenom' => vb($current_user['prenom']),
					'nom_famille' => vb($current_user['nom_famille']),
					'user_login_displayed' => (!a_priv('demo')?(vb($current_user['etat'])?'<a href="' . $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&amp;id_utilisateur=' . intval($connexion['user_id']) . '">'.$connexion['user_login'].'</a>':'<span style="color:#AAAAAA">'.$connexion['user_login'].'</span>'):'private [demo]')
					);
				$rollover_style = null;
				if(!empty($geoIP)) {
					$country_id = $geoIP->geoIPCountryIDByAddr(long2ip($connexion['user_ip']));
					foreach(array('country_ip' => $country_id, 'country_account' => vn($current_user['pays'])) as $this_key => $this_value) {
						$sql = 'SELECT iso, pays_' . $_SESSION['session_langue'] . '
							FROM peel_pays
							WHERE id="' . intval($this_value) . '"
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
				if(is_annonce_module_active()) {
					$tpl_result['active_ads_count'] = $current_user['active_ads_count'];
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
		$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
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
		LEFT JOIN peel_utilisateurs u ON u.id_utilisateur= ' . intval($user_id) . '
		WHERE paa.id_user= ' . $_SESSION['session_utilisateur']['id_utilisateur'] . ' AND paa.id_membre = ' . intval($user_id) . ' AND ((paa.action = "PHONE_EMITTED") OR (paa.action = "PHONE_RECEIVED")) AND paa.data="NOT_ENDED_CALL"
		ORDER BY paa.date DESC
		LIMIT 1');
	$res = fetch_assoc($q);
	$output = '
	<form method="post" id="phone" action="' . get_current_url(false) . '#phone_section" >
		<input type="hidden" name="mode" value="phone_call" />
		<input type="hidden" name="id_utilisateur" value="' . intval($user_id) . '" />';
	if (!empty($res)) {
		// warning : phone call not ended;
		$output .= '
			<hr /><h2 id="phone_section" style="color:green">' . sprintf(($res['action'] == 'PHONE_EMITTED'?$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_STARTED_EMITTED"]:$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_STARTED_RECEIVED"]), vb($res['pseudo_membre'])) . ' : '.$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_STARTED_ON"].' ' . get_formatted_date($res['date']) . '</h2>
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
						<td></td>
						<td class="center"><input name="turn_off_phone" type="submit" value="'.$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_CLOSE"].'" class="btn btn-primary" /></td>
					</tr>
				</table>
			</center>';
	} else {
		$output .= '
				<h2 id="phone_section">'.$GLOBALS["STR_ADMIN_UTILISATEURS_MANAGE_CALLS"].'</h2>
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
					<table >
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
				</center>';
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
	return array('1' => $GLOBALS["STR_YES"],
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
 * Renvoie la liste des boutiques configurées
 *
 * @return
 */
function get_all_site_names()
{
	// A faire évoluer lors d'un passage en multiboutique
	$sql = "SELECT string, 1 AS site_id
		FROM peel_configuration
		WHERE technical_code='nom_" . $_SESSION['session_langue'] . "'";
	$query = query($sql);
	while ($result = fetch_assoc($query)) {
		$results['site_id'] = $result['string'];
	}
	if(!empty($results)){
		return $results;
	} else {
		return null;
	}
}

/**
 * insere_langue()
 *
 * @param array $frm Array with all fields data
 * @param boolean $try_alter_table_even_if_modules_not_active
 * @param mixed $force_update_database_lang_content 
 * @return
 */
function insere_langue($frm, $try_alter_table_even_if_modules_not_active = true, $force_update_database_lang_content = false)
{
	$output = '';
	$new_lang = String::strtolower($frm['lang']);
	if (empty($new_lang) || String::strlen($new_lang) != 2) {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS["STR_ADMIN_LANGUES_ERR_LANGUAGE_TWO_CHARS"]))->fetch();
		return $output;
	}
	if (num_rows(query("SELECT * FROM peel_langues WHERE lang='" . word_real_escape_string($new_lang) . "'"))) {
		// La langue existe déjà : on se met automatiquement en mode réparation des tables pour créer d'éventuelles colonnes manquantes
		$repair = true;
	}else {
		$repair = false;
	}

	unset($query_alter_table);
	// Ajouter les ALTER TABLE à la suite pour ajouter les champs de langues dans les differentes tables souhaitées.
	$query_alter_table[] = 'ALTER TABLE `peel_access_map` ADD `text_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	if ($try_alter_table_even_if_modules_not_active || is_affiliate_module_active ()) {
		$query_alter_table[] = 'ALTER TABLE `peel_affiliation` ADD `titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_affiliation` ADD `texte_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	}
	if ($try_alter_table_even_if_modules_not_active || is_attributes_module_active ()) {
		$query_alter_table[] = 'ALTER TABLE `peel_attributs` ADD `descriptif_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
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
	$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD `alpha_' . word_real_escape_string($new_lang) . '` CHAR( 1 ) NOT NULL DEFAULT ""';
	if(!in_array('nom_'.$new_lang, get_table_index('peel_categories', null, true))) {
		$query_alter_table[] = 'ALTER TABLE `peel_categories` ADD INDEX (`nom_' . word_real_escape_string($new_lang) . '`)';
	}
	if ($try_alter_table_even_if_modules_not_active || is_annonce_module_active()) {
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
	if ($try_alter_table_even_if_modules_not_active || is_stock_advanced_module_active()) {
		$query_alter_table[] = 'ALTER TABLE `peel_etatstock` ADD `nom_' . word_real_escape_string($new_lang) . '`  VARCHAR( 255 ) NOT NULL DEFAULT ""';
	}
	if ($try_alter_table_even_if_modules_not_active || is_module_faq_active ()) {
		$query_alter_table[] = 'ALTER TABLE `peel_faq` ADD `question_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_faq` ADD `answer_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
	}
	if ($try_alter_table_even_if_modules_not_active || is_annonce_module_active()) {
		$query_alter_table[] = 'ALTER TABLE `peel_gold_ads` ADD `text_intro_' . word_real_escape_string($new_lang) . '` VARCHAR( 80 ) NOT NULL DEFAULT ""';
	}
	$query_alter_table[] = 'ALTER TABLE `peel_import_field` ADD `texte_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_langues` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_legal` ADD `titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_legal` ADD `texte_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	if ($try_alter_table_even_if_modules_not_active || is_lexique_module_active()) {
		$query_alter_table[] = 'ALTER TABLE `peel_lexique` ADD `word_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_lexique` ADD `definition_' . word_real_escape_string($new_lang) . '` mediumtext NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_lexique` ADD `meta_title_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_lexique` ADD `meta_definition_' . word_real_escape_string($new_lang) . '` mediumtext NOT NULL';
	}
	if ($try_alter_table_even_if_modules_not_active || is_annonce_module_active()) {
		$query_alter_table[] = 'ALTER TABLE `peel_lot_vente` ADD `titre_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_lot_vente` ADD `description_' . word_real_escape_string($new_lang) . '` TEXT NOT NULL';
		if(in_array('search_fulltext', get_table_index('peel_lot_vente', null, true))) {
			// On regénère l'index FULLTEXT sur le colonnes des langues actives
			// Attention, cette commande prendra du temps si la table est de taille importante
			$query_alter_table[] = 'ALTER TABLE `peel_lot_vente` DROP INDEX (`search_fulltext`)';
		}
		unset($index_array);
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$index_array[]='titre_'.$lng;
			$index_array[]='description_'.$lng;
		}
		$query_alter_table[] = 'ALTER TABLE `peel_lot_vente` ADD FULLTEXT KEY `search_fulltext` ('.implode(',', real_escape_string($index_array)).')';
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
	if ($try_alter_table_even_if_modules_not_active || is_attributes_module_active ()) {
		$query_alter_table[] = 'ALTER TABLE `peel_nom_attributs` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	}
	$query_alter_table[] = 'ALTER TABLE `peel_paiement` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	if ($try_alter_table_even_if_modules_not_active || is_parrainage_module_active ()) {
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
	$query_alter_table[] = 'ALTER TABLE `peel_produits` ADD `name_' . word_real_escape_string($new_lang) . '` VARCHAR( 100 ) NOT NULL DEFAULT ""';
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
	set_configuration_variable(array('technical_code' => 'nom_' . word_real_escape_string($new_lang) . '', 'type' => 'string'), true);
	set_configuration_variable(array('technical_code' => 'logo_' . word_real_escape_string($new_lang) . '', 'type' => 'string'), true);
	if ($try_alter_table_even_if_modules_not_active || is_module_vacances_active()) {
		set_configuration_variable(array('technical_code' => 'module_vacances_client_msg_' . word_real_escape_string($new_lang) . '', 'type' => 'string'), true);
	}
	$query_alter_table[] = 'ALTER TABLE `peel_statut_paiement` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_statut_livraison` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_tailles` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_types` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_zones` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	// Ajout de la gestion des langues pour le contenu des newsletters qui sont géré en fonction de la langue définit par 'utilisateur
	$query_alter_table[] = 'ALTER TABLE `peel_newsletter` ADD `sujet_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
	$query_alter_table[] = 'ALTER TABLE `peel_newsletter` ADD `message_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';

	if ($try_alter_table_even_if_modules_not_active || is_references_module_active()) {
		$query_alter_table[] = 'ALTER TABLE `peel_references_categories` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_references_categories` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
		$query_alter_table[] = 'ALTER TABLE `peel_references` ADD `descriptif_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	}
	if ($try_alter_table_even_if_modules_not_active || is_partenaires_module_active()) {
		$query_alter_table[] = 'ALTER TABLE `peel_partenaires_categories` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_partenaires_categories` ADD `description_' . word_real_escape_string($new_lang) . '` MEDIUMTEXT NOT NULL';
	}
	// Ajout des langues au module vitrine
	if ($try_alter_table_even_if_modules_not_active || is_vitrine_module_active()) {
		$query_alter_table[] = 'ALTER TABLE `peel_vitrine_grossiste` ADD `nom_' . word_real_escape_string($new_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT ""';
		$query_alter_table[] = 'ALTER TABLE `peel_vitrine_grossiste` ADD `presentation_' . word_real_escape_string($new_lang) . '` text NOT NULL';
	}
	// Ajout des langues au module carrousel
	if (is_carrousel_module_active()) {
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
	if(!$repair) {
		if(empty($frm['flag'])) {
			if(!empty($GLOBALS['langs_flags_correspondance'][$new_lang])){
				$frm['flag'] = $GLOBALS['langs_flags_correspondance'][$new_lang];
			} else {
				$frm['flag'] = $new_lang . '.gif';
			}
		}
		if(!isset($frm['etat'])) {
			$frm['etat'] = 1;
		}
		$sql = "INSERT INTO peel_langues (
				lang
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
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_LANGUES_MSG_LANGUAGE_CREATED'], $new_lang)))->fetch();
	}
	if(!$repair || $force_update_database_lang_content) {
		// Import des données relatives à la langue créée
		$database_import_content = array(array('continents' => 'name'), array('pays' => 'pays'), array('ecotaxes' => 'nom'), array('email_template_cat' => 'name'), array('email_template' => 'name'), array('email_template' => 'subject'), array('email_template' => 'text'), array('etatstock' => 'nom'), array('langues' => 'nom'), array('import_field' => 'texte'), array('modules' => 'title'), array('paiement' => 'nom'), array('profil' => 'name'), array('statut_livraison' => 'nom'), array('statut_paiement' => 'nom'), array('types' => 'nom'), array('zones' => 'nom'));
		if(!is_bool($force_update_database_lang_content) && !is_array($force_update_database_lang_content) && String::strlen($force_update_database_lang_content)>1) {
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
								if(is_numeric($this_reference)) {
									$reference_column = 'id';
								} elseif(in_array($this_table_short_name, array('langues'))) {
									$reference_column = 'lang';
								} elseif(in_array($this_table_short_name, array('import_field'))) {
									$reference_column = 'champs';
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
								}else {
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
									WHERE ".implode(' AND ', $sql_line_array);
								$query = query($sql);
								if(fetch_assoc($query)) {
									$sql = "UPDATE peel_".word_real_escape_string($this_table_short_name)."
										SET ".implode(', ', $sql_set_array)."
										WHERE ".implode(' AND ', $sql_line_array);
									query($sql);
								}elseif(!in_array($this_table_short_name, array('langues')) && !empty($this_value)){
									if(in_array('etat', $table_field_names)){
										// La table a une colonne lang => on a une ligne par langue
										$sql_line_array[] = "etat='1'";
									}
									if($column_name == $this_field_prefix) {
										unset($sql_line_array['id']);
									}
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
			WHERE url_rewriting='' AND lang!='" . real_escape_string($new_lang)."' AND etat=1
			LIMIT 1";
		$query = query($sql);
		if(fetch_assoc($query)) {
			// Il y a déjà d'autres langues avec url_rewriting='' => on dit par défaut que cette langue est accessible dans le répertoire xx/ si pas d'autre règle existante
			$sql = "UPDATE peel_langues
				SET url_rewriting='".real_escape_string($new_lang)."/'
				WHERE lang='" . real_escape_string($new_lang)."' AND url_rewriting=''";
			query($sql);
		}
		if(!empty($imported_texts)){
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => String::strtoupper($new_lang) . ' - ' . $GLOBALS["STR_ADMIN_LANGUES_MSG_CONTENT_NOT_IMPORTED"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '.implode(', ', $imported_texts)))->fetch();
		}
		if(!empty($not_imported_texts)){
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => String::strtoupper($new_lang) . ' - ' . $GLOBALS["STR_ADMIN_LANGUES_ERR_CONTENT_NOT_IMPORTED"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . implode(', ', $not_imported_texts)))->fetch();
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
	function get_admin_date_filter_form($form_title, $information_select_html, $submit_html=null)
	{
		$output = '';
		$nowDay = date('d');
		$nowMonth = date('m');
		$nowYear = date('Y');

		// Génération de la liste des années de 2004 à maintenant
		for ($i = 2004; $i <= date('Y'); $i++) {
			$years1[]=$i;
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_date_filter_form.tpl');
		$tpl->assign('action', get_current_url(false));
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
				'issel' => ((isset($_GET['jour1']) && $c == $_GET['jour1']) || (!isset($_GET['jour1']) && $c == $nowDay))
			);
		}
		$tpl->assign('days_options', $days_options);
		
		$months_options = array();
		foreach ($GLOBALS['months_names'] as $this_month_number => $this_month) {
			if(!empty($this_month)) {
				$months_options[] = array(
					'value' => $this_month_number,
					'name' => String::ucfirst($this_month),
					'issel' => ((isset($_GET['mois1']) && $this_month_number == $_GET['mois1']) || (!isset($_GET['mois1']) && $this_month_number == $nowMonth))
				);
			}
		}
		$tpl->assign('months_options', $months_options);
		
		$years_options = array();
		for ($x3 = 0; $x3 <= count($years1) - 1; $x3++) {
			$years_options[] = array(
				'value' => $years1[$x3],
				'name' => $years1[$x3],
				'issel' => ((isset($_GET['an1']) && $years1[$x3] == $_GET['an1']) || (!isset($_GET['an1']) && $years1[$x3] == $nowYear))
			);
		}
		$tpl->assign('years_options', $years_options);
		
		$days2_options = array();
		for ($c = 1; $c <= 31; $c++) {
			$days2_options[] = array(
				'value' => $c,
				'name' => $c,
				'issel' => ((isset($_GET['jour2']) && $c == $_GET['jour2']) || (!isset($_GET['jour2']) && $c == $nowDay))
			);
		}
		$tpl->assign('days2_options', $days2_options);
		
		$months2_options = array();
		foreach ($GLOBALS['months_names'] as $this_month_number => $this_month) {
			if(!empty($this_month)) {
				$months2_options[] = array(
					'value' => $this_month_number,
					'name' => String::ucfirst($this_month),
					'issel' => ((isset($_GET['mois2']) && $this_month_number == $_GET['mois2']) || (!isset($_GET['mois2']) && $this_month_number == $nowMonth))
				);
			}
		}
		$tpl->assign('months2_options', $months2_options);
		
		$years2_options = array();
		for ($x = 0; $x <= count($years1) - 1; $x++) {
			$years2_options[] = array(
				'value' => $years1[$x],
				'name' => $years1[$x],
				'issel' => ((isset($_GET['an2']) && $years1[$x] == $_GET['an2']) || (!isset($_GET['an2']) && $years1[$x] == $nowYear))
			);
		}
		$order_date_field_array = array($GLOBALS['STR_ADMIN_PAIEMENT_DATE']=>'a_timestamp',$GLOBALS['STR_ADMIN_ORDER_CREATION_DATE']=>'o_timestamp',$GLOBALS['STR_ADMIN_COMMANDER_INVOICE_DATE']=>'f_datetime',$GLOBALS['STR_EXPEDITION_DATE']=>'e_datetime');
		foreach($order_date_field_array as $name => $this_field) {
			$order_date_field_options[] = array(
					'value' => $this_field,
					'name' => $name,
					'issel' => ((isset($_GET['order_date_field_filter']) && $this_field == $_GET['order_date_field_filter']))
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
		$output = $tpl->fetch();
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
		if (!checkdate($_GET['mois1'], $_GET['jour1'], $_GET['an1'])) {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $_GET['jour1'] . '-' . $_GET['mois1'] . '-' . $_GET['aa1'] . ' => '.$GLOBALS["STR_ERR_DATE_BAD"]))->fetch();
		} elseif (!checkdate($_GET['mois2'], $_GET['jour2'], $_GET['an2'])) {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $_GET['jour2'] . '-' . $_GET['mois2'] . '-' . $_GET['an2'] . ' => '.$GLOBALS["STR_ERR_DATE_BAD"]))->fetch();
		} else {
			$dateAdded1 = $_GET['an1'] . '-' . str_pad($_GET['mois1'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($_GET['jour1'], 2, 0, STR_PAD_LEFT) . " 00:00:00";
			$dateAdded2 = $_GET['an2'] . '-' . str_pad($_GET['mois2'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($_GET['jour2'], 2, 0, STR_PAD_LEFT) . " 23:59:59";
			if ($dateAdded2 < $dateAdded1) {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['strStartingOn'] . ' ' . get_formatted_date($dateAdded1) . '&nbsp;' . $GLOBALS['strTillDay'] . '  ' . get_formatted_date($dateAdded2) . ' => ' . $GLOBALS["STR_ADMIN_DATE1_DATE2_INCOHERENT"]))->fetch();
			}
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
	 * @param array $frm Array with all fields data
	 * @return
	 */
	function affiche_liste_produits($frm)
	{		
		construit_arbo_categorie($GLOBALS['categorie_options'], vb($_GET['cat_search']));
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_produits.tpl');
		if (empty($GLOBALS['categorie_options'])) {
			$tpl->assign('is_empty', true);
			$tpl->assign('href', $GLOBALS['administrer_url'] . '/categories.php?mode=ajout');
		} else {
			$tpl->assign('is_empty', false);
			$tpl->assign('site_parameters_prices', vb($GLOBALS['site_parameters']['edit_prices_on_products_list']));
			$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
			$tpl->assign('action', get_current_url(false) . '?page=' . (!empty($_GET['page']) ? $_GET['page'] : 1) . '&mode=recherche');
			$tpl->assign('categorie_options', $GLOBALS['categorie_options']);
			$tpl->assign('cat_search_zero_issel', (vb($_GET['cat_search']) == '0'));
			$tpl->assign('home_search_one_issel', (vb($_GET['home_search']) == 1));
			$tpl->assign('home_search_zero_issel', (vb($_GET['home_search']) === "0"));
			$tpl->assign('new_search_one_issel', (vb($_GET['new_search']) == 1));
			$tpl->assign('new_search_zero_issel', (vb($_GET['new_search']) === "0"));
			$tpl->assign('promo_search_one_issel', (vb($_GET['promo_search']) == 1));
			$tpl->assign('promo_search_zero_issel', (vb($_GET['promo_search']) === "0"));
			
			$tpl->assign('is_best_seller_module_active', is_best_seller_module_active());
			$tpl->assign('top_search_one_issel', (vb($_GET['top_search']) == 1));
			$tpl->assign('top_search_zero_issel', (vb($_GET['top_search']) === "0"));
			
			$tpl->assign('is_gifts_module_active', is_gifts_module_active());
			$tpl->assign('on_gift_one_issel', (vb($_GET['on_gift']) == 1));
			$tpl->assign('on_gift_zero_issel', (vb($_GET['on_gift']) === "0"));
			
			$tpl->assign('blank_src', $GLOBALS['wwwroot'] . '/images/blank.gif');
			$tpl->assign('STR_PHOTO_NOT_AVAILABLE_ALT', $GLOBALS['STR_PHOTO_NOT_AVAILABLE_ALT']);
			$tpl->assign('photo_not_available_src', $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($GLOBALS['site_parameters']['default_picture'], 80, 50, 'fit'));
			
			// Construction de la clause WHERE
			$where = "1";
			$table = "peel_produits p";

			if (isset($frm['reference_search']) && !empty($frm['reference_search'])) {
				$where .= " AND p.reference = '" . nohtml_real_escape_string($frm['reference_search']) . "'";
			}
			if (isset($frm['name_search']) && !empty($frm['name_search'])) {
				$where .= " AND p.nom_" . $_SESSION['session_langue'] . " LIKE '%" . nohtml_real_escape_string($frm['name_search']) . "%'";
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
			if (isset($frm['top_search']) && $frm['top_search'] != "null" && is_best_seller_module_active()) {
				$where .= " AND p.on_top = '" . nohtml_real_escape_string($frm['top_search']) . "'";
			}
			if (isset($frm['on_gift']) && $frm['on_gift'] != "null" && is_gifts_module_active()) {
				$where .= " AND p.on_gift = '" . nohtml_real_escape_string($frm['on_gift']) . "'";
			}
			if (isset($frm['cat_search']) && is_numeric($frm['cat_search'])) {
				$children_cat_list = get_children_cat_list(vn($frm['cat_search']));
				$where .= " AND p.id = pc.produit_id AND pc.categorie_id IN (" . implode(',', $children_cat_list) . ")";
				$table .= ", peel_produits_categories pc";
			}

			$sql = "SELECT p.*
				FROM " . $table . "
				WHERE " . $where;

			$Links = new Multipage($sql, 'affiche_liste_produits');
			$HeaderTitlesArray = array($GLOBALS['STR_ADMIN_ACTION'], 'reference' => $GLOBALS['STR_REFERENCE'], $GLOBALS['STR_CATEGORY'], $GLOBALS['STR_WEBSITE'], ('nom_' . $_SESSION['session_langue']) => $GLOBALS['STR_ADMIN_NAME'], 'prix' => $GLOBALS['STR_PRICE'] . ' ' . $GLOBALS['site_parameters']['symbole'] . ' ' . (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']), 'etat' => $GLOBALS['STR_STATUS']);
			if (is_stock_advanced_module_active()) {
				$HeaderTitlesArray['on_stock'] = $GLOBALS['STR_STOCK'];
			}
			if (is_gifts_module_active()) {
				$HeaderTitlesArray['points'] = $GLOBALS['STR_GIFT_POINTS'];
				$tpl->assign('STR_MODULE_GIFTS_ADMIN_GIFT', $GLOBALS['STR_MODULE_GIFTS_ADMIN_GIFT']);
			}
			$HeaderTitlesArray['date_maj'] = $GLOBALS['STR_ADMIN_UPDATED_DATE'];
			$HeaderTitlesArray[] = $GLOBALS['STR_ADMIN_SUPPLIER'];
			$HeaderTitlesArray[] = $GLOBALS['STR_PHOTO'];
			$HeaderTitlesArray['nb_view'] = $GLOBALS['STR_ADMIN_PRODUITS_VIEWS_COUNT'];
			$Links->HeaderTitlesArray = $HeaderTitlesArray;
			$Links->OrderDefault = "position, nom_" . $_SESSION['session_langue'] . ", prix";
			$Links->SortDefault = "ASC";
			$results_array = $Links->Query();
			
			$tpl->assign('nombre_produits', count($results_array));
			$tpl->assign('ajout_produits_href', $GLOBALS['administrer_url'] . '/produits.php?mode=ajout');
			$tpl->assign('is_duplicate_module_active', is_duplicate_module_active());
			$tpl->assign('is_stock_advanced_module_active', is_stock_advanced_module_active());
			$tpl->assign('is_gifts_module_active', is_gifts_module_active());

			$lignes = array();
			if (!empty($results_array)) {
				$i = 0;
				$tpl->assign('HeaderRow', $Links->getHeaderRow());
				foreach ($results_array as $ligne) {
					$product_object = new Product($ligne['id'], $ligne, true, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
					
					$tmpLigne = array('tr_rollover' => tr_rollover($i, true),
						'drop_confirm' =>  $GLOBALS["STR_ADMIN_DELETE_WARNING"],
						'id' => $ligne['id'],
						'name' => (!empty($ligne['nom_' . $_SESSION['session_langue']])?$ligne['nom_' . $_SESSION['session_langue']]:'['.$ligne['id'].']'),
						'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1),
						'drop_src' => $GLOBALS['administrer_url'] . '/images/b_drop.png',
						'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1),
						'edit_src' => $GLOBALS['administrer_url'] . '/images/b_edit.png',
						'dup_href' => get_current_url(false) . '?mode=duplicate&id=' . $ligne['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1),
						'dup_src' => $GLOBALS['administrer_url'] . '/images/duplicate.png',
						'reference' => $ligne['reference'],
						'cats' => array(),
						'sites' => array(),
						'modify_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1),
						'modify_label' => $ligne['nom_' . $_SESSION['session_langue']] . ($ligne['on_gift'] == 1 ? "&nbsp;(cadeau)" : ""),
						'prix' => fprix((display_prices_with_taxes_in_admin() ? $ligne['prix'] : $ligne['prix'] / (1 + $ligne['tva'] / 100)), false, $GLOBALS['site_parameters']['code'], false),
						'prix_suf' => (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']),
						'etat_onclick' => 'change_status("produits", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
						'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
						'date' => get_formatted_date($ligne['date_maj']),
						'product_name' => $product_object->name,
						'nb_view' => $ligne['nb_view'],
					);


					$sqlCAT = "SELECT c.id, c.nom_" . $_SESSION['session_langue'] . ", c2.nom_" . $_SESSION['session_langue'] . " AS parent_nom_" . $_SESSION['session_langue'] . "
						FROM peel_produits_categories pc
						INNER JOIN peel_categories c ON c.id = pc.categorie_id
						LEFT JOIN peel_categories c2 ON c2.id=c.parent_id
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
					$tmpLigne['sites'] = get_all_site_names();
					if (is_stock_advanced_module_active()) {
						if ($ligne['on_stock'] == 1) {
							$tmpLigne['stock_href'] = get_current_url(false) . '?mode=stock&id=' . $ligne['id'];
							$tmpLigne['stock_src'] = $GLOBALS['administrer_url'] . '/images/stock.gif';
						}
					}
					if (is_gifts_module_active()) {
						$tmpLigne['points'] = $ligne['points'];
					}
					if (!empty($ligne['id_utilisateur']) && $user_infos = get_user_information($ligne['id_utilisateur'])) {
						$tmpLigne['utilisateur_href'] = $GLOBALS['administrer_url'] . "/utilisateurs.php?mode=modif&id_utilisateur=" . $user_infos['id_utilisateur'];
						$tmpLigne['societe'] = $user_infos['societe'];
					}
					//Récupération de l'image principale du produit
					$main_product_picture = $product_object->get_product_main_picture();
					//Si l'image principale est trouvée
					if (!empty($main_product_picture)) {
						$this_thumbs = thumbs($main_product_picture, 80, 50, 'fit');
						$tmpLigne['product_src'] = $GLOBALS['repertoire_upload'] . '/thumbs/' . $this_thumbs;
					} 
					$i++;
					$lignes[] = $tmpLigne;
				}
			}
			$tpl->assign('lignes', $lignes);
			$tpl->assign('Multipage', $Links->GetMultipage());
			
		}
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
		return $tpl->fetch();
	}
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
		construit_arbo_categorie($GLOBALS['categorie_options'], $preselectionne);

		$sql = "SELECT p.id, oi.nom_produit as nom, oi.couleur, oi.taille, oi.delai_stock, oi.commande_id, oi.order_stock
			FROM peel_commandes_articles oi
			INNER JOIN peel_produits p ON oi.produit_id = p.id
			WHERE oi.order_stock>0";
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

		construit_arbo_rubrique($GLOBALS['rubrique_options'], $frm['rubriques']);

		// Construction de la clause WHERE
		$where = "WHERE 1";
		$table = "";
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
				LEFT JOIN peel_rubriques r ON ar.rubrique_id = r.id";
				if ($frm['cat_search'] === '0') {
					// recherche des articles sans associations
					$rubrique_condition = ' ar.rubrique_id IS NULL OR ar.rubrique_id=0';
				} else {
					$rubrique_condition = ' ar.rubrique_id IN ' . implode(',', get_children_cat_list(vn($frm['cat_search']), array(), 'rubriques'));
				}
				$where .= ' AND '.$rubrique_condition;
			}
		}
		$sql = "SELECT a.id, a.titre_" . $_SESSION['session_langue'] . ", a.etat
			FROM peel_articles a " . $table . " 
			" . $inner . "
			" . $where . "
			ORDER BY a.id DESC";
		$Links = new Multipage($sql, 'affiche_liste_articles');
		$results_array = $Links->Query();

		$tpl = $GLOBALS['tplEngine']->createTemplate('liste_articles.tpl');
		$tpl->assign('action', get_current_url(false) . '?start=0&mode=recherche');
		$tpl->assign('rubrique_options', $GLOBALS['rubrique_options']);
		$tpl->assign('text_in_title', vb($_POST['text_in_title']));
		$tpl->assign('text_in_article', vb($_POST['text_in_article']));
		$tpl->assign('cat_search', vb($_GET['cat_search']));
		$tpl->assign('ajout_href', get_current_url(false) . '?mode=ajout');
		$tpl->assign('Multipage', $Links->GetMultipage());
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
					'titre' => (!empty($ligne['titre_' . $_SESSION['session_langue']])?String::html_entity_decode_if_needed($ligne['titre_' . $_SESSION['session_langue']]):'[' . $ligne['id'] . ']'),
					'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1),
					'drop_src' => $GLOBALS['administrer_url'] . '/images/b_drop.png',
					'rubs' => array(),
					'modif_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
					'sites' => array(),
					'etat_onclick' => 'change_status("articles", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
					'modif_etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				);
				$sql = "SELECT r.id, r.nom_" . $_SESSION['session_langue'] . ", r2.nom_" . $_SESSION['session_langue'] . " AS parent_nom_" . $_SESSION['session_langue'] . "
					FROM peel_articles_rubriques pr
					LEFT JOIN peel_rubriques r ON r.id = pr.rubrique_id
					LEFT JOIN peel_rubriques r2 ON r2.id=r.parent_id
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
				$tmpLigne['sites'] = get_all_site_names();
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
		$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
		$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
		$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
		$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
		$tpl->assign('STR_ADMIN_ARTICLES_FORM_MODIFY', $GLOBALS['STR_ADMIN_ARTICLES_FORM_MODIFY']);
		echo $tpl->fetch();
	}
}

/**
 * Import d'un produit : mise à jour ou création du produit
 *
 * @param array $field_values Array with all fields data
 * @param array $columns_skipped
 * @param array $product_field_names Names of colums authorized (this array is not mandatory)
 * @return
 */
function create_or_update_product($field_values, $columns_skipped = array(), $product_field_names = array()) {
	if(!isset($GLOBALS['nbprod_update'])) {
		$GLOBALS['nbprod_update'] = 0;
		$GLOBALS['nbprod_update_null'] = 0;
		$GLOBALS['nbprod_insert'] = 0;
		$GLOBALS['nbprod_categorie_insert'] = 0;
	}
	// Gestion des champs impactant $field_values (transformation d'un nom en id par exemple)
	if (!empty($field_values['id_marque'])) {
		if(!is_array($field_values['id_marque'])) {
			$field_values['id_marque'] = array($field_values['id_marque']);
		}
		foreach($field_values['id_marque'] as $this_key => $this_field_value) {
			if(String::strlen($this_field_value)>0) {
				// La marque n'est pas vide - il faut que l'import soit compatible avec des noms de marque pouvant être des nombres
				// Par défaut on considère qu'une marque donnée est une id de marque, sinon on gère comme si c'était un nom si pas trouvée et non numérique
				$q = query('SELECT id
					FROM peel_marques
					WHERE id=' . intval($this_field_value));
				if ($brand = fetch_assoc($q)) {
					// Marque existante
					$field_values['id_marque'] = $brand['id'];
				} else {
					$sql_select_brand = 'SELECT id 
						FROM peel_marques
						WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_field_value).'"';
					$query_brand = query($sql_select_brand);
					if($brand = fetch_assoc($query_brand)){
						$field_values['id_marque'] = $brand['id'];
					}elseif(!empty($this_field_value) && !is_numeric($this_field_value)) {
						// Marque inexistante, on l'insère en base de données.
						$q = query('INSERT INTO peel_marques
							SET nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($this_field_value) . '", etat="1"');
						$field_values['id_marque'] = insert_id();
						echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_BRAND_CREATED'], $line_number, $field_values['id_marque'])))->fetch();
					}
				}
			}
		}
	}
	// Génération du SQL à partir de $field_values
	foreach($field_values as $this_field_name => $this_value) {
		if (!empty($this_field_name) && !in_array($this_field_name, $columns_skipped)) {
			if((empty($product_field_names) || in_array($this_field_name, $product_field_names)) && !in_array($this_field_name, array('id', 'Categorie'))) {
				// On ne tient compte que des colonnes présentes dans la table produits pour sql_fields, les autres champs sont traités séparément
				$set_sql_fields[$this_field_name] = word_real_escape_string($this_field_name) . "='" . real_escape_string($this_value) . "'";
			}
		} else {
			unset($field_values[$this_field_name]);
		}
	}
	if (!empty($field_values['id'])) {
		// On a spécifié une id Produit, donc on essaie de faire un UPDATE
		if (!empty($set_sql_fields)) {
			$sql = "UPDATE peel_produits
				SET " . implode(', ', $set_sql_fields) . "
				WHERE id='" . intval($field_values['id']) . "'";
			query($sql);
			if (affected_rows()) {
				$product_id = $field_values['id'];
				$GLOBALS['nbprod_update']++;
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_LINE_UPDATED'], $GLOBALS['line_number'], $product_id)))->fetch();
			} 
		}
		if (!isset($product_id)) {
			// On vérifie si le produit existe déjà (et donc n'a pas été modifié) ou si il est à créer
			$q = query("SELECT id
				FROM peel_produits
				WHERE id='" . intval($field_values['id']) . "'");
			if ($product = fetch_assoc($q)) {
				// Produit existe, et n'avait donc pas été modifié
				$GLOBALS['nbprod_update_null']++;
				$product_id = $field_values['id'];
			} else {
				// Produit inexistant : on va exécuter l'INSERT INTO plus loin en imposant l'id
				$set_sql_fields['id'] = "id='" . intval($field_values['id']) . "'";
			}
		}
	}
	if (!isset($product_id) && !empty($set_sql_fields)) {
		// Produit pas encore existant et $set_sql_fields est forcément non vide ici
		$sql = "INSERT INTO peel_produits
			SET " . implode(', ', $set_sql_fields);
		query($sql);
		$product_id = insert_id();
		$GLOBALS['nbprod_insert']++;
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_LINE_CREATED'], $GLOBALS['line_number'], $product_id)))->fetch();
	} elseif(!isset($product_id)) {
		echo 'Problem empty product_id';
		return false;
	}
	// Gestion des champs nécessitant d'écrire dans d'autres tables en connaissant $product_id
	foreach($field_values as $this_field_name => $this_field_value) {
		if($this_field_name == $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS']){
			// Gestion de la couleur
			query('DELETE FROM peel_produits_couleurs 
				WHERE produit_id="' . intval($product_id) . '"');
			$this_list_color = explode(",", $this_field_value);
			foreach($this_list_color as $this_id => $this_value){
				if(String::strlen($this_value)>0) {
					if(!is_numeric($this_value)) {
						$sql_select_color = 'SELECT * 
							FROM peel_couleurs
							WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_value).'"';
						$query_color = query($sql_select_color);
						if($color = fetch_assoc($query_color)){
							$this_value = $color['id'];
						}else{
							$sql_insert_color = 'INSERT INTO peel_couleurs (nom_'.$_SESSION['session_langue'].') 
								VALUES ("'.real_escape_string($this_value).'")';
							query($sql_insert_color);
							$this_value = insert_id();
						}
					}
					$sql_select_product_color = 'SELECT * 
						FROM peel_produits_couleurs 
						WHERE produit_id = "' . intval($product_id) . '" AND couleur_id = "' . intval($this_value) . '"';
					$query_select_product_color = query($sql_select_product_color);
					if(!fetch_assoc($query_select_product_color)){
						$sql_match_product_color = 'INSERT INTO peel_produits_couleurs(produit_id,couleur_id) 
							VALUES ("' . intval($product_id) . '","' . intval($this_value) . '")';
						query($sql_match_product_color);
					}
				}
			}
		} elseif($this_field_name == $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES']){
			// Gestion de la taille
			query('DELETE FROM peel_produits_tailles 
				WHERE produit_id="' . intval($product_id) . '"');
			$this_list_size = explode(",", $this_field_value);
			foreach($this_list_size as $this_id => $this_value){
				$this_list_size_and_price = explode("§", $this_value);
				$size_name = $this_list_size_and_price[0];
				if(String::strlen($size_name)>0) {
					$size_price = vn($this_list_size_and_price[1]);
					$size_price_reseller = vn($this_list_size_and_price[2]);
					// On ne fait pas de test is_numeric ou pas sur les tailles pour savoir si on parle d'id ou de nom, car une taille peut être un nombre !
					// Donc obligatoirement, on considère qu'une taille est rentrée par son nom
					$sql_size = 'SELECT * 
						FROM peel_tailles 
						WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($size_name).'"';
					$query_size = query($sql_size);
					if($size = fetch_assoc($query_size)){
						if(isset($this_list_size_and_price[1]) && get_float_from_user_input($size_price) != $size['prix']){
							query('UPDATE peel_tailles 
								SET prix = "'.real_escape_string(get_float_from_user_input($size_price)).'" 
								WHERE id="'.intval($size['id']).'"');
						}
						if(isset($this_list_size_and_price[2]) && get_float_from_user_input($size_price_reseller) != $size['prix_revendeur']){
							query('UPDATE peel_tailles 
								SET prix_revendeur = "'.real_escape_string(get_float_from_user_input($size_price_reseller)).'" 
								WHERE id="'.intval($size['id']).'"');
						}
						$this_size_id = $size['id'];
					}else{
						$sql_insert_size = 'INSERT INTO peel_tailles (nom_'.$_SESSION['session_langue'].', prix, prix_revendeur) 
							VALUES ("'.real_escape_string($size_name).'", "'.floatval(get_float_from_user_input(vn($size_price))).'", "'.floatval(get_float_from_user_input(vn($size_price_reseller))).'")';
						query($sql_insert_size);
						$this_size_id = insert_id();
					}
					$select_size_product = 'SELECT * 
						FROM peel_produits_tailles 
						WHERE produit_id = "' . intval($product_id) . '" AND taille_id = "' . intval($this_size_id) . '"';
					$query_size_product = query($select_size_product);
					if(!fetch_assoc($query_size_product)){
						$sql_match_product_size = 'INSERT INTO peel_produits_tailles (produit_id, taille_id) 
							VALUES ("' . intval($product_id) . '", "' . intval($this_size_id) . '")';
						query($sql_match_product_size);
					}
				}
			}
		} elseif (strpos($this_field_name, "§") !== false) {
			// Gestion des prix par lots : tarifs dégressifs
			// Nom du champs
			$this_bulk_discount = explode("§", $this_field_name);
			$this_quantity = $this_bulk_discount[0];
			$this_price_standard = $this_bulk_discount[1];
			$this_price_reseller = $this_bulk_discount[2];
			// Valeur du champs
			if(!empty($this_field_value)){
				$this_package_price = explode("§", $this_field_value);
				$quantity = $this_package_price[0];
				$price_standard = $this_package_price[1];
				$price_reseller = $this_package_price[2];
				if (is_lot_module_active()) {
					$sql_prix_lot = 'SELECT * 
						FROM peel_quantites 
						WHERE produit_id="' . intval($product_id) . '" AND quantite = "'.intval($quantity).'"';
					$query_prix_lot = query($sql_prix_lot);
					if(fetch_assoc($query_prix_lot)){
						$sql_update = 'UPDATE peel_quantites 
							SET quantite = "'.intval($quantity).'"';
						if(isset($this_price_standard) && isset($price_standard)){
							$sql_update.= ', prix ="'.nohtml_real_escape_string($price_standard ).'"';
						}
						if(isset($this_price_reseller) && isset($price_reseller)){
							$sql_update.= ', prix_revendeur ="'.nohtml_real_escape_string($price_reseller).'"';
						}
						$sql_update.= '
							WHERE produit_id="' . intval($product_id) . '" AND quantite = "'.intval($quantity).'"';
						query($sql_update);
						echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_TARIF_UPDATED'], vb($price_standard), vb($price_reseller), vb($quantity), $product_id)))->fetch();
					} else {
						if(isset($quantity) && $quantity > 0){
							$q = 'INSERT INTO peel_quantites 
								SET produit_id="' . intval($product_id) . '"';	
							$q.= ', quantite ="'.intval($quantity).'"';
							if(isset($this_price_standard) && isset($price_standard)){
								$q.= ', prix ="'.nohtml_real_escape_string($price_standard).'"';
							}
							if(isset($this_price_reseller) && isset($price_reseller)){
								$q.= ', prix_revendeur ="'.nohtml_real_escape_string($price_reseller).'"';
							}
							query($q);
							echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_TARIF_CREATED'], vb($price_standard), vb($price_reseller), vb($quantity), $product_id)))->fetch();
						}
					}
				}
			}
		} elseif (strpos($this_field_name, "#") !== false) {
			// Gestion des attributs
			// Pour chaque attribut, on sépare le nom de l'ID
			$nom_attrib = explode('#', $this_field_name);
			$q = query('SELECT id
				FROM peel_nom_attributs
				WHERE id=' . intval($nom_attrib[1]));
			if(!empty($nom_attrib[1])) {
				// attribut existant
				if ($att = fetch_assoc($q)) {
					$nom_attrib[1] = $att['id'];
				} else {
					// Attribut inexistant, on l'insère en base de données.
					$q = query('INSERT INTO peel_nom_attributs
						SET id=' . intval($nom_attrib[1]) . ', nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($nom_attrib[0]) . '", etat="1"');
					$nom_attrib[1] = insert_id();
					echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_ATTRIBUTE_CREATED'], $nom_attrib[0], $nom_attrib[1])))->fetch();
				}
				// Pour chaque attribut
				if (!empty($this_field_value)) {
					// On récupère toutes les options de cet attribut
					$id_options = explode(',', $this_field_value);
					// Pour chaque option de cet attribut
					foreach($id_options as $id_o) {
						// On sépare l'ID du nom
						$desc_option = explode('#', $id_o);
						if(!isset($desc_option[1])) {
							continue;
						}
						unset($attribute_ids);
						$sql = 'SELECT id, id_nom_attribut
							FROM peel_attributs
							WHERE id_nom_attribut="' . intval($nom_attrib[1]) . '"';
						if(!empty($desc_option[0])) {
							// Si on a spécifié l'id d'attribut, on ne prend que celui-là. 
							$sql .= ' AND id="' . intval($desc_option[0]) . '"';
						} elseif(!empty($desc_option[1])) {
							// Si on a spécifié le nom d'attribut, on ne prend que celui-là.
							$sql .= ' AND descriptif_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($desc_option[1]) . '"';
						}
						$q = query($sql);
						// Option existante
						while ($attribut = fetch_assoc($q)) {
							$attribute_ids[] = $attribut['id'];
						}
						if(empty($attribute_ids)) {
							// Option inexistante et différente d'upload ou de texte libre, on l'insère en base de donnée sinon on modifie l'attribut.
							if ($desc_option[1] == '__upload') {
								$q = query('UPDATE peel_nom_attributs
									SET upload=1
									WHERE id="' . intval($nom_attrib[1]) . '"');
								$attribute_ids[] = $desc_option[0];
							} elseif ($desc_option[1] == '__texte_libre') {
								$q = query('UPDATE peel_nom_attributs
									SET texte_libre=1
									WHERE id="' . intval($nom_attrib[1]) . '"');
								$attribute_ids[] = $desc_option[0];
							} else {
								$q = query('INSERT INTO peel_attributs
									SET id=' . intval($desc_option[0]) . '
									, id_nom_attribut=' . intval($nom_attrib[1]) . '
									, descriptif_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($desc_option[1]) . '"
									, mandatory=1', false, null, true);
								$this_id = insert_id();
								if(empty($this_id)) {
									// On change l'id si déjà prise en BDD
									// C'est un choix plutôt que d'effacer les attributs déjà existants
									$q = query('INSERT INTO peel_attributs
										SET id_nom_attribut=' . intval($nom_attrib[1]) . '
										, descriptif_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($desc_option[1]) . '"
										, mandatory=1', false, null, true);
									$this_id = insert_id();
								}
								$attribute_ids[] = $this_id;
								echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_OPTION_CREATED'], $desc_option[1], $this_id)))->fetch();
							}
						}
						foreach($attribute_ids as $this_attribute_id) {
							// Vérification que l'association entre les attributs, les options d'attributs et les produits existe, sinon, on l'ajoute
							$q = query('SELECT produit_id
								FROM peel_produits_attributs
								WHERE produit_id="' . intval($product_id) . '"
									AND nom_attribut_id="' . intval($nom_attrib[1]) . '"
									AND attribut_id="' . intval($this_attribute_id) . '"');
							if (!num_rows($q)) {
								query('INSERT INTO peel_produits_attributs
									SET produit_id="' . intval($product_id) . '",
										nom_attribut_id="' . intval($nom_attrib[1]) . '",
										attribut_id="' . intval($this_attribute_id) . '"');
							}
						}
					}
				}
			}
		}
	}	
	// Gestion de la catégorie
	unset($this_categories_array);
	if (!empty($field_values['categorie_id']) && !is_numeric($field_values['categorie_id']) && empty($field_values['Categorie'])) {
		// Compatibilité avec anciens champs appelés categorie_id et contenant des noms de catégories
		$field_values['Categorie'] = $field_values['categorie_id'];
		unset($field_values['categorie_id']);
	}
	if (!empty($field_values['Categorie'])) {
		// Ce champ contient une liste de catégories séparées par des virgules
		foreach(explode(',', $field_values['Categorie']) as $this_category) {
			if (is_numeric($this_category)) {
				// le champ Categorie est un id
				$this_categorie_id = intval($this_category);
			} else {
				// le champ Categorie n'est pas un nombre, on tente une recherche dans la BDD sur le nom de la catégorie.
				$q = query('SELECT id
					FROM peel_categories
					WHERE nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($this_category) . '"');
				// Catégorie existante, ou le champ Categorie du fichier n'est ni un ID, ni le nom de la catégorie
				if ($categorie = fetch_assoc($q)) {
					$this_categorie_id = $categorie['id'];
				} else {
					// Catégorie inexistante : on l'insère en base de données
					$q = query('INSERT INTO peel_categories
						SET nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($this_category) . '", etat="1"');
					$this_categorie_id = insert_id();
					echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_CATEGORY_CREATED'], $line_number, $this_categorie_id)))->fetch();
				}
			}
			$this_categories_array[] = $this_categorie_id;
		}
	}
	if (!empty($field_values['categorie_id'])) {
		// On a déjà testé plus haut si categorie_id était numérique ou non, et si pas numérique on l'a supprimé
		// donc là il est forcément numérique
		if (get_category_name($field_values['categorie_id']) !== false) {
			$this_categories_array[] = $field_values['categorie_id'];
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_REFERENCE_DOES_NOT_EXIST'], $field_values['categorie_id'])))->fetch();
		}
	}
	if (!empty($this_categories_array)) {
		foreach($this_categories_array as $this_categorie_id) {
			if (!empty($this_categorie_id)) {
				// Vérification que l'association entre les produits, les catégories de produits
				$q = query('SELECT produit_id, categorie_id
					FROM peel_produits_categories
					WHERE produit_id="' . intval($product_id) . '" AND categorie_id="' . intval($this_categorie_id) . '"');
				if (!num_rows($q)) {
					query('INSERT INTO peel_produits_categories
						SET produit_id="' . intval($product_id) . '",
							categorie_id="' . intval($this_categorie_id) . '"');
					$GLOBALS['nbprod_categorie_insert']++;
				}
			}
		}
	}
	// Gestion des stocks
	// Doit être fait à la fin car on doit déjà avoir les couleurs et tailles bien rentrées en base de données
	if(!empty($field_values["Stock"]) && is_stock_advanced_module_active()){
		// Format stock ou stock§color§size, et les combinaisons sont séparées par ,
		$this_list_stock = explode(",", $field_values["Stock"]);
		$stock_frm = array();
		foreach($this_list_stock as $this_id => $this_value){
			$this_list_infos = explode("§", $this_value);
			$stock_frm["id"][$this_id] = $product_id;
			$stock_frm["stock"][$this_id] = $this_list_infos[0];
			$this_value = vb($this_list_infos[1]);
			if(is_numeric($this_value)) {
				$stock_frm["couleur_id"][$this_id] = $this_value;
			} elseif(!empty($this_value) && !is_numeric($this_value)) {
				$sql_select_color = 'SELECT * 
					FROM peel_couleurs
					WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_value).'"';
				$query_color = query($sql_select_color);
				if($color = fetch_assoc($query_color)){
					$stock_frm["couleur_id"][$this_id] = $color['id'];
				}
			}
			if(!empty($this_list_infos[2])) {
				// Taille donnée forcément par son nom
				$sql_size = 'SELECT * 
					FROM peel_tailles 
					WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_list_infos[2]).'"';
				$query_size = query($sql_size);
				if($size = fetch_assoc($query_size)){
					$stock_frm["taille_id"][$this_id] = $size['id'];
				}
			}
		}
		echo insere_stock_produit($stock_frm);
	}
	if (is_stock_advanced_module_active() && !empty($field_values['on_stock']) && $field_values['on_stock'] == 1) {
		include_once($GLOBALS['fonctionsstock_advanced_admin']);
		insert_product_in_stock_table_if_not_exist($product_id, 1);
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
	$result = query('SELECT id, technical_code, name, lang
		FROM peel_email_template
		WHERE active = "TRUE"' . (!empty($get_signature)?' AND technical_code LIKE "signature%"':'') . (!empty($category_id)?' AND id_cat="' . intval($category_id) . '"':'') . (!empty($lang)?' AND (lang="' . vb($lang) . '" OR lang="")':'') . '
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
	<option value="' . $this_value . '" ' . (!empty($this_select)?$this_select:'') . '>' . '[' . String::strtoupper(vb($row_template['lang'])) . '] - ' . String::str_form_value(vb($row_template['name'])) . '</option>';
	}
	return $output;
}

?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	|
// +----------------------------------------------------------------------+
// $Id: fonctions_admin.php 35391 2013-02-19 17:12:55Z gboussin $
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
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/sites.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_SITES"];
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/configuration.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_CONFIGURATION"];
			if (is_carrousel_module_active ()) {
				$menu_items['manage'][$GLOBALS['wwwroot_in_admin'] . '/modules/carrousel/administrer/carrousel.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_CARROUSEL"];
			}
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/societe.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_SOCIETE"];
			if (is_devises_module_active ()) {
				$menu_items['manage'][$GLOBALS['wwwroot_in_admin'] . '/modules/devises/administrer/devises.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DEVISES"];
			}
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/langues.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_LANGUES"];
			if (is_module_ecotaxe_active ()) {
				$menu_items['manage'][$GLOBALS['administrer_url'] . '/ecotaxes.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_EXOTAXE"];
			}
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/tva.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_TVA"];
			if (is_module_profile_active ()) {
				$menu_items['manage'][$GLOBALS['wwwroot_in_admin'] . '/modules/profil/administrer/profil.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_PROFIL"];
			}
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/paiement.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_PAYMENT"];
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/clean_folders.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_CLEAN_FOLDERS"];
			if (is_butterflive_module_active ()) {
				$menu_items['manage'][$GLOBALS['wwwroot_in_admin'] . '/modules/butterflive/admin/butterflive.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_BUTTERFLIVE"];
			}
			$menu_items['manage'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_MANAGE_DELIVERY_HEADER"].' &nbsp; -';
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/pays.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_COUNTRIES"];
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/zones.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_ZONES"];
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/types.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DELIVERY"];
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/tarifs.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_DELIVERY_COST"];
			if (is_icirelais_module_active ()) {
				$menu_items['manage'][$GLOBALS['wwwroot_in_admin'] . '/modules/icirelais/administrer/icirelais_file_synchronize_V2.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_ICIRELAIS"];
			}
			$menu_items['manage'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_MANAGE_EMAILS_HEADER"].' &nbsp; -';
			$menu_items['manage'][$GLOBALS['administrer_url'] . '/email-templates.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_EMAIL"];
			if (is_webmail_module_active()) {
				$menu_items['manage'][$GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/webmail_send.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND"];
				$menu_items['manage'][$GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/list_mails_send.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_SENT_EMAILS"];
				$menu_items['manage'][$GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/list_mails.php'] = $GLOBALS["STR_ADMIN_MENU_MANAGE_LIST_EMAILS"];
			}
		}
		if (a_priv('admin_users', true)) {
			// Menu des utilisateurs
			$main_menu_items['users'] = array($GLOBALS['administrer_url'] . '/utilisateurs.php' => $GLOBALS["STR_ADMIN_MENU_USERS_USERS"]);
			$menu_items['users'][$GLOBALS['administrer_url'] . '/utilisateurs.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_USERS_LIST"];
			$menu_items['users'][$GLOBALS['administrer_url'] . '/utilisateurs.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_USERS_USER_CREATE"];

			if (is_groups_module_active ()) {
				$menu_items['users'][$GLOBALS['wwwroot_in_admin'] . '/modules/groups/administrer/groupes.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_GROUPS_LIST"];
			}
			$menu_items['users'][$GLOBALS['administrer_url'] . '/utilisateurs.php?mode=liste&priv=supplier'] = $GLOBALS["STR_ADMIN_MENU_USERS_SUPPLIERS_LIST"];
			$menu_items['users'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_USERS_RETAINING"].' &nbsp; -';
			$menu_items['users'][$GLOBALS['administrer_url'] . '/newsletter.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_NEWSLETTER"];
			if (is_module_wanewsletter_active ()) {
				$menu_items['users'][$GLOBALS['wwwroot_in_admin'] . '/modules/newsletter/admin/admin.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_WANEWSLETTER"];
			}
			$menu_items['users'][$GLOBALS['administrer_url'] . '/codes_promos.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_CODE_PROMO"];
			if (is_good_clients_module_active ()) {
				$menu_items['users'][$GLOBALS['wwwroot_in_admin'] . '/modules/good_clients/administrer/bons_clients.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_BEST_CLIENTS"];
			}
			if (is_birthday_module_active ()) {
				$menu_items['users'][$GLOBALS['wwwroot_in_admin'] . '/modules/birthday/administrer/bons_anniversaires.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_BIRTHDAY"];
			}
			if (is_module_gift_checks_active ()) {
				$menu_items['users'][$GLOBALS['wwwroot_in_admin'] . '/modules/gift_check/administrer/cheques_cadeaux.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_GIFT_CHECKS"];
			}
			if (is_gifts_module_active ()) {
				if (is_stock_advanced_module_active ()) {
					$menu_items['users'][$GLOBALS['wwwroot_in_admin'] . '/modules/gifts/administrer/cadeaux.php?mode=commande'] = $GLOBALS["STR_ADMIN_MENU_USERS_GIFTS_ORDERS"];
				}
			}
			// Si le module commerciale existe, alors on affiche le menu relation client
			if (is_commerciale_module_active()) {
				$menu_items['users'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_USERS_SALES_MANAGEMENT"].' &nbsp; -';
				if (is_relance_avance_module_active()) {
					$menu_items['users'][$GLOBALS['wwwroot_in_admin'] . '/modules/relance_avance/administrer/relances.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_CONTACT_REMINDERS"];
				}
				$menu_items['users'][$GLOBALS['wwwroot_in_admin'] . '/modules/commerciale/administrer/list_admin_contact_planified.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_CLIENTS_TO_CONTACT"];
				$menu_items['users'][$GLOBALS['administrer_url'] . '/utilisateurs.php?mode=search&commercial=' . $_SESSION['session_utilisateur']['id_utilisateur']] = sprintf($GLOBALS["STR_ADMIN_MENU_USERS_CLIENTS_PER_SALESMAN"], vb($_SESSION['session_utilisateur']['pseudo']));
				if (file_exists($GLOBALS['dirroot'] . '/modules/maps_users/administrer/map_google_search.php')) {
					$menu_items['users'][$GLOBALS['wwwroot_in_admin'] . '/modules/maps_users/administrer/map_google_search.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_USERS_MAP"];
				}
			}
		}
		if (a_priv('admin_products', true)) {
			$main_menu_items['products'] = array($GLOBALS['administrer_url'] . '/produits.php' => $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS"]);
			$menu_items['products'][$GLOBALS['administrer_url'] . '/produits.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS_LIST"];
			$menu_items['products'][$GLOBALS['administrer_url'] . '/produits.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCT_ADD"];
			$menu_items['products'][$GLOBALS['administrer_url'] . '/categories.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_CATEGORIES_LIST"];
			$menu_items['products'][$GLOBALS['administrer_url'] . '/categories.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_CATEGORY_ADD"];
			$menu_items['products'][$GLOBALS['administrer_url'] . '/positions.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRODUCTS_ORDER"];
			$menu_items['products'][$GLOBALS['administrer_url'] . '/prix.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRICE_UPDATE"];
			$menu_items['products'][$GLOBALS['administrer_url'] . '/prix_pourcentage.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_PRICE_UPDATE_BY_PERCENTAGES"];
			$menu_items['products'][$GLOBALS['administrer_url'] . '/marques.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_BRAND_LIST"];
			if (is_telechargement_module_active ()) {
				$menu_items['products'][$GLOBALS['wwwroot_in_admin'] . '/modules/telechargement/administrer/telechargement.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_DOWNLOADABLE_FILES"];
			}
			if (is_module_ecotaxe_active ()) {
				$menu_items['products'][$GLOBALS['administrer_url'] . '/ecotaxes.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_ECOTAX"];
			}
			if (is_references_module_active()) {
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/references/administrer/categories_references.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_REFERENCES_CATEGORIES"];
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/references/administrer/references.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_REFERENCES"];
			}
			$menu_items['products'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_PRODUCTS_ATTRIBUTES_HEADER"].' &nbsp; -';
			$menu_items['products'][$GLOBALS['administrer_url'] . '/couleurs.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_COLORS"];
			$menu_items['products'][$GLOBALS['administrer_url'] . '/tailles.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_SIZES"];
			if (is_attributes_module_active ()) {
				$menu_items['products'][$GLOBALS['wwwroot_in_admin'] . '/modules/attributs/administrer/nom_attributs.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_ATTRIBUTES"];
				$menu_items['products'][$GLOBALS['wwwroot_in_admin'] . '/modules/attributs/administrer/attributs.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_OPTIONS"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/hosting/administrer/hosting.php')) {
				$menu_items['products'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_PRODUCTS_HOSTING_HEADER"].' &nbsp; -';
				$menu_items['products'][$GLOBALS['wwwroot_in_admin'] . '/modules/hosting/administrer/hosting.php'] = $GLOBALS["STR_ADMIN_MENU_PRODUCTS_HOSTING"];
			}
		}
		if (a_priv('admin_sales', true)) {
			// Menu des ventes
			$main_menu_items['sales'] = array($GLOBALS['administrer_url'] . '/commander.php' => $GLOBALS["STR_ADMIN_MENU_SALES_SALES_TITLE"]);
			$menu_items['sales'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_SALES_SALES_HEADER"].' &nbsp; -';
			$menu_items['sales'][$GLOBALS['administrer_url'] . '/commander.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_ORDERS"];
			if (is_gifts_module_active ()) {
				if (is_stock_advanced_module_active ()) {
					$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/gifts/administrer/cadeaux.php?mode=commande'] = $GLOBALS["STR_ADMIN_MENU_SALES_GIFTS"];
				}
			}
			$menu_items['sales'][$GLOBALS['administrer_url'] . '/commander.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_SALES_ORDER_CREATION"];
			if (is_payback_module_active ()) {
				$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/payback/administrer/retours.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PRODUCT_RETURN"];
			}
			if (is_module_export_ventes_active ()) {
				$menu_items['sales'][$GLOBALS['administrer_url'] . '/ventes.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_REPORT_HEADER"];
			} else {
				$menu_items['sales'][$GLOBALS['administrer_url'] . '/ventes.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_REPORT"];
			}

			if (is_kekoli_module_active ()) {
				$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/kekoli/administrer/kekoli.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_EXPORT"];
			}
			if (is_download_module_active ()) {
				$menu_items['sales'][$GLOBALS['administrer_url'] . '/commander.php?mode=download'] = $GLOBALS["STR_ADMIN_MENU_SALES_NUMERIC_SALES"];
			}
			$menu_items['sales'][$GLOBALS['administrer_url'] . '/statut_paiement.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PAYMENT_STATUS"];

			if (is_stats_module_active() || is_module_marge_active() || is_module_genere_pdf_active() || is_accounting_module_active()) {
				$menu_items['sales'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_SALES_ACCOUNTING_HEADER"].' &nbsp; -';
				if (file_exists($GLOBALS['dirroot'] . '/modules/multisite/administrer/stats.php')) {
					$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/multisite/administrer/stats.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_MULTISITE_STATS"];
				}
				if (is_accounting_module_active() && a_priv('compta', true)) {
					$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/accounting/administrer/index-compta.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_ACCOUNTING"];
				}
				if (is_stats_module_active ()) {
					$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/statistiques/administrer/statcommande.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_SALES_STAT"];
				}
				if (is_module_marge_active ()) {
					$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/marges/administrer/marges.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_MARGINS"];
				}
				if (is_module_genere_pdf_active ()) {
					$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/facture_advanced/administrer/genere_pdf.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PDF_BILLS"];
				}
			}
			if (is_stock_advanced_module_active ()) {
				$menu_items['sales'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_SALES_STOCKS_HEADER"].' &nbsp; -';
				$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/stock_advanced/administrer/stocks1clic.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_STOCK"];
				$menu_items['sales'][$GLOBALS['administrer_url'] . '/produits.php?mode=stocknul'] = $GLOBALS["STR_ADMIN_MENU_SALES_PRODUCTS_TO_ORDER"];
				$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/stock_advanced/administrer/etatstock.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_STOCK_STATUS"];
				$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/stock_advanced/administrer/alertes.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_STOCK_ALERTS"];
			}
			$menu_items['sales'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_HEADER"].' &nbsp; -';
			if (is_module_export_livraisons_active ()) {
				$menu_items['sales'][$GLOBALS['administrer_url'] . '/livraisons.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_EXPORT"];
			} else {
				$menu_items['sales'][$GLOBALS['administrer_url'] . '/livraisons.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_REPORT"];
			}
			if (is_module_picking_active ()) {
				$menu_items['sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/picking/administrer/picking.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_PICKING_LIST"];
			}
			$menu_items['sales'][$GLOBALS['administrer_url'] . '/statut_livraison.php'] = $GLOBALS["STR_ADMIN_MENU_SALES_DELIVERY_STATUS"];
		}
		if (a_priv('admin_content', true)) {
			$main_menu_items['content'] = array($GLOBALS['administrer_url'] . '/articles.php' => $GLOBALS["STR_ADMIN_MENU_CONTENT_TITLE"]);
			$menu_items['content'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_CONTENT_ARTICLES_HEADER"].' &nbsp; -';
			$menu_items['content'][$GLOBALS['administrer_url'] . '/articles.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_ARTICLES_LIST"];
			$menu_items['content'][$GLOBALS['administrer_url'] . '/articles.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_ARTICLE_ADD"];
			$menu_items['content'][$GLOBALS['administrer_url'] . '/rubriques.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_CATEGORIES_LIST"];
			$menu_items['content'][$GLOBALS['administrer_url'] . '/rubriques.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_CATEGORY_ADD"];

			$menu_items['content'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_CONTENT_HTML_HEADER"].' &nbsp; -';
			$url_cgv = get_cgv_url(false);
			$menu_items['content'][$GLOBALS['administrer_url'] . '/cgv.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TERMS"];
			if (file_exists($GLOBALS['dirroot'] . '/modules/cgu-template/administrer/cgu-update.php')) {
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/cgu-template/administrer/cgu-update.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TERMS_TEMPLATES"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/cgu-template/administrer/cgu.php')) {
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/cgu-template/administrer/cgu.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TERMS_GENERATE"];
			}
			if (is_parrainage_module_active ()) {
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/parrainage/administrer/parrain.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_SPONSOR_TERMS"];
			}
			$menu_items['content'][$GLOBALS['administrer_url'] . '/legal.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_LEGAL"];
			$menu_items['content'][$GLOBALS['administrer_url'] . '/plan.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_GOOGLEMAP"];
			$menu_items['content'][$GLOBALS['administrer_url'] . '/contacts.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_CONTACTS"];
			if (is_module_tagcloud_active ()) {
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/tagcloud/administrer/tagcloud.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TAGCLOUD"];
			}
			if (is_module_faq_active()) {
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/faq/administrer/faq.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_FAQ"];
			}
			if (is_lexique_module_active ()) {
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/lexique/administrer/lexique.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_LEXICAL"];
			}
			if (is_module_forum_active ()) {
				$menu_items['content'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_CONTENT_FORUM"].' &nbsp; -';
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/forum/administrer/list_forum_messages.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_FORUM_MESSAGES"];
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/forum/administrer/list_forums.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_FORUMS"];
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/forum/administrer/list_forum_cats.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_FORUM_CATEGORIES"];
			}
			$menu_items['content'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_CONTENT_VARIOUS_HEADER"].' &nbsp; -';
			$menu_items['content'][$GLOBALS['administrer_url'] . '/html.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_HTML"];
			if (is_module_banner_active()) {
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/banner/administrer/banner.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_BANNERS"];
			}
			if (is_module_tagcloud_active()) {
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/tagcloud/administrer/tagcloud.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_TAGCLOUD"];
			}
			if (is_partenaires_module_active ()) {
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/partenaires/administrer/categories_partenaires.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_PARTNERS_CATEGORIES"];
				$menu_items['content'][$GLOBALS['wwwroot_in_admin'] . '/modules/partenaires/administrer/partenaires.php'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_PARTNERS"];
			}
		}
		if (a_priv('admin_webmastering', true)) {
			$main_menu_items['webmastering'] = array($GLOBALS['administrer_url'] . '/produits_achetes.php' => $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_TITLE"]);
			// Menu de webmastering
			$menu_items['webmastering'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_WEBMASTERING_MARKETING"].' &nbsp; -';
			if (is_module_avis_active ()) {
				$menu_items['webmastering'][$GLOBALS['wwwroot_in_admin'] . '/modules/avis/administrer/avis.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_OPINIONS"];
			}
			$menu_items['webmastering'][$GLOBALS['administrer_url'] . '/produits_achetes.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_BEST_PRODUCTS"];
			$menu_items['webmastering'][$GLOBALS['administrer_url'] . '/import_produits.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_IMPORT_PRODUCTS"];
			$menu_items['webmastering'][$GLOBALS['administrer_url'] . '/export_produits.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_EXPORT_PRODUCTS"];
			if (is_module_export_clients_active ()) {
				$menu_items['webmastering'][$GLOBALS['wwwroot_in_admin'] . '/modules/export/administrer/export_clients.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_CLIENTS_EXPORT"];
			}
			if (is_expeditor_module_active ()) {
				$menu_items['webmastering'][$GLOBALS['wwwroot_in_admin'] . '/modules/expeditor/administrer/expeditor.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_EXPEDITOR"];
			}
			$menu_items['webmastering'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_WEBMASTERING_SEO_HEADER"].' &nbsp; -';
			if (is_module_comparateur_active ()) {
				$menu_items['webmastering'][$GLOBALS['wwwroot_in_admin'] . '/modules/comparateur/administrer/mysql2comparateur.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_COMPARATORS"];
			}
			$menu_items['webmastering'][$GLOBALS['administrer_url'] . '/sitemap.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_SITEMAP"];
			$menu_items['webmastering'][$GLOBALS['administrer_url'] . '/urllist.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_SITEMAP_URLLIST"];
			$menu_items['webmastering'][$GLOBALS['administrer_url'] . '/meta.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_META"];
			if (is_affiliate_module_active ()) {
				$menu_items['webmastering'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_WEBMASTERING_AFFILIATE"].' &nbsp; -';
				$menu_items['webmastering'][$GLOBALS['administrer_url'] . '/commander.php?mode=affi'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_AFFILIATE_ORDERS"];
				$menu_items['webmastering'][$GLOBALS['wwwroot_in_admin'] . '/modules/affiliation/administrer/ventes_affiliation.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_AFFILIATE_REPORT"];
				$menu_items['webmastering'][$GLOBALS['wwwroot_in_admin'] . '/modules/affiliation/administrer/affiliation.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_AFFILIATE_TERMS"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/projects_management/administrer/projects.php')) {
				$menu_items['webmastering'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_MANAGEMENT"].' &nbsp; -';
				$menu_items['webmastering'][$GLOBALS['wwwroot_in_admin'] . '/modules/projects_management/administrer/projects.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_TASKS"];
				$menu_items['webmastering'][$GLOBALS['wwwroot_in_admin'] . '/modules/projects_management/administrer/project-custom-orders.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_SOLD"];
				$menu_items['webmastering'][$GLOBALS['wwwroot_in_admin'] . '/modules/projects_management/administrer/project-events.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_PROJECT_CONTENT"];
			}
			if (file_exists($GLOBALS['dirroot'] . '/modules/calc/calc.php')) {
				$menu_items['webmastering'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_WEBMASTERING_VARIOUS_HEADER"].' &nbsp; -';
				$menu_items['webmastering'][$GLOBALS['wwwroot_in_admin'] . '/modules/calc/calc.php'] = $GLOBALS["STR_ADMIN_MENU_WEBMASTERING_CALC"];
			}
		}
		if (a_priv('admin_moderation', true)) {
			// Si le module vitrine existe ou module annonce
			$main_menu_items['moderation'] = array($GLOBALS['administrer_url'] . '/list_admin_actions.php' => $GLOBALS["STR_ADMIN_MENU_MODERATION_TITLE"]);
			if (is_vitrine_module_active() || is_annonce_module_active()) {
				if (is_annonce_module_active()) {
					$menu_items['moderation'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_MODERATION_ADS_HEADER"].' &nbsp; -';
					$menu_items['moderation'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/annonces.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_ADS"];
					$menu_items['moderation'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/annonces.php?mode=creation_gold'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_GOLD"];
					$menu_items['moderation'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/categories.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_CATEGORIES"];
					$menu_items['moderation'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/categories.php?mode=ajout'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_CATEGORY_ADD"];
				}
				if (is_vitrine_module_active()) {
					$menu_items['moderation'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_MODERATION_STORES_HEADER"].' &nbsp; -';
					$menu_items['moderation'][$GLOBALS['wwwroot_in_admin'] . '/modules/vitrine/administrer/vitrine.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_STORES"];
				}
				if (is_annonce_module_active()) {
					$menu_items['moderation'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_MODERATION_CONTENT"].' &nbsp; -';
					$menu_items['moderation'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/black_list.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_BLACKLISTED_WORDS"];
					$menu_items['moderation'][$GLOBALS['wwwroot_in_admin'] . '/modules/annonces/administrer/abus.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_ABUSE_REPORTS"];
				}
			}
			$menu_items['moderation'][] = '- &nbsp; '.$GLOBALS["STR_ADMIN_MENU_MODERATION_VARIOUS_HEADER"].' &nbsp; -';
			if (is_phone_cti_module_active()) {
				$menu_items['moderation'][$GLOBALS['wwwroot_in_admin'] . '/modules/phone_cti/administrer/list_calls.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_PHONE_CALLS_KEYYO"];
			}
			$menu_items['moderation'][$GLOBALS['administrer_url'] . '/list_admin_actions.php?action_cat=PHONE'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_PHONE_CALLS"];
			$menu_items['moderation'][$GLOBALS['administrer_url'] . '/list_admin_actions.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_ADMIN_ACTIONS"];
			$menu_items['moderation'][$GLOBALS['administrer_url'] . '/connexion_user.php'] = $GLOBALS["STR_ADMIN_MENU_MODERATION_USER_CONNEXIONS"];
		}
	}
	$current_url = get_current_url(false);
	$current_url_full = get_current_url(true);

	$output = '
<ul id="menu1">
';
	$i = 0;
	foreach ($main_menu_items as $this_main_item => $this_main_array) {
		if ($i == 9) {
			$output .= '
</ul><ul id="menu2">';
		}
		$current_menu = (!empty($menu_items[$this_main_item][$current_url_full]));
		$full_match = true;
		if ($current_menu === false && !empty($menu_items[$this_main_item])) {
			$current_menu = (!empty($menu_items[$this_main_item][$current_url]));
			$full_match = false;
		}
		foreach ($this_main_array as $this_main_url => $this_main_title) {
			if ($current_menu !== false || !empty($this_main_array[$current_url]) || !empty($this_main_array[$current_url_full])) {
				$main_class = ' class="current"';
			} else {
				$main_class = '';
			}
			if ($this_main_item == 'home') {
				$this_main_text = '<a title="' . $GLOBALS['STR_HOME'] . '" href="' . htmlspecialchars($this_main_url) . '"' . $main_class . '><img src="' . $GLOBALS['administrer_url'] . '/modeles/images/home.png" alt="" style="padding: 0px 6px 0px 4px;" /></a>';
			} else {
				if (!empty($this_main_url) && !is_numeric($this_main_url)) {
					$this_main_text = '<a title="' . $this_main_title . '" href="' . htmlspecialchars($this_main_url) . '"' . $main_class . '>' . $this_main_title . '</a>';
				} else {
					$this_main_text = '<span>' . $this_main_title . '</span>';
				}
			}
			if (!empty($menu_items[$this_main_item])) {
				$this_main_text .= '<ul class="sousMenu">
';
				foreach ($menu_items[$this_main_item] as $this_url => $this_title) {
					if (($current_url == $this_url && !$full_match) || $current_url_full == $this_url) {
						$class = ' class="current"';
					} elseif ($this_url == $GLOBALS['wwwroot_in_admin'] . '/modules/calc/calc.php') {
						$class = ' onclick="return(window.open(this.href)?false:true);"';
					} else {
						$class = '';
					}
					if (!empty($this_url) && !is_numeric($this_url)) {
						$this_text = '<a title="' . $this_title . '" href="' . htmlspecialchars($this_url) . '"' . $class . '>' . $this_title . '</a>';
					} else {
						$this_text = '<span' . $main_class . '>' . $this_title . '</span>';
					}
					$this_main_text .= '<li>' . $this_text . '</li>';
				}
				$this_main_text .= '
</ul>';
			}
			$output .= '
	<li class="menu_main_item menu_' . $this_main_item . '">' . $this_main_text . '</li>
';
		}
		$i++;
	}
	$output .= '
</ul>
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
	if (!($handle = String::fopen_utf8($GLOBALS['uploaddir'] . '/' . $local_filename, 'rb'))) {
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
 * @return
 */
function get_product_id_by_name($name)
{
	// Si plusieurs produits existent avec un même nom, on prend celui qui est actif et mis à jour le plus récemment
	$sql = 'SELECT id
		FROM peel_produits
		WHERE LOWER(nom_' . $_SESSION['session_langue'] . ')="' . nohtml_real_escape_string(String::strtolower(trim($name))) . '" OR LOWER(nom_' . $_SESSION['session_langue'] . ')="' . nohtml_real_escape_string(String::strtolower($name)) . '"
		ORDER BY etat DESC, date_maj DESC
		LIMIT 1';
	$q = query($sql);
	if ($result = fetch_assoc($q)) {
		return $result['id'];
	} else {
		return false;
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
		$get_options .= '<input type="hidden" name="' . $this_item . '" value="' . String::str_form_value($this_value) . '" />';
	}
	$lang_select = '
<form id="langue" method="get" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
	<div>Langue des données administrées :
		' . $get_options . '<select name="langue" onchange="document.getElementById(\'langue\').submit()">
			<option value="">' . $GLOBALS['STR_CHOOSE'] . '...</option>
';
	foreach ($GLOBALS['lang_names'] as $this_lang => $this_lang_name) {
		$lang_select .= '<option value="' . $this_lang . '" ' . frmvalide($_SESSION['session_langue'] == $this_lang, ' selected="selected"') . '>' . $this_lang_name . '</option>';
	}

	$i = 0;
	$lang_select .= '
		</select> (sans influence sur la langue de l\'interface d\'administration)
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
<form method="get" id="ecom" action="' . get_current_url(false) . '" >
	<select name="ecom" onchange="document.getElementById(\'ecom\').submit()">
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
		$frm["date_debut"] = get_formatted_date();
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
			$remise = get_discount_text($cp['remise_valeur'], $cp['remise_percent'], display_prices_with_taxes_active());
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
 * @param string $chemin_du_repertoire
 * @param integer $older_than_seconds
 */
function nettoyer_dir($dir, $older_than_seconds = 3)
{
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
 * affiche_liste_commandes_admin()
 *
 * @return
 */
function affiche_liste_commandes_admin()
{
	// Affiche en liste les commandes
	$sql = "SELECT *, id as order_id
		FROM peel_commandes
		WHERE id_ecom='" . intval($GLOBALS['site_parameters']['id']) . "'";
	$Links = new Multipage($sql, 'affiche_liste_commandes_admin');
	$Links->OrderDefault = 'id';
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();
	// Affiche le modele des commandes lister
	include("modeles/commande_liste.php");
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
			if (display_prices_with_taxes_in_admin ()) {
				$montant_displayed = $commande['montant'];
			} else {
				$montant_displayed = $commande['montant_ht'];
			}
		} else {
			// $date_facture = Date du jour
			$date_facture = get_formatted_date();
			$montant_displayed = 0;
		}
		// Affiche le modeles d'une commande en detail
		include("modeles/commande_details.php");
	} elseif (!empty($id)) {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS["STR_ADMIN_COMMANDER_NO_ORDER_WITH_ID_FOUND"], $id)))->fetch();
	}
}

/**
 * affiche_recherche_commandes()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_recherche_commandes($frm)
{
	$sql_inner = '';
	$sql_cond = '';
	$sql = "";
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
	$sql = "SELECT c.*, c.id as order_id
		FROM peel_commandes c " . $sql_inner . "
		WHERE id_ecom = '" . intval($GLOBALS['site_parameters']['id']) . "'  " . $sql_cond . "";
	if(!empty($sql_inner)){
		$sql .="
		GROUP BY c.id";
	}
	$Links = new Multipage($sql, 'affiche_recherche_commandes');
	$Links->OrderDefault = 'o_timestamp';
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();
	if (!empty($sql)) {
		// Charge la liste des commandes et les affiche.
		if (!empty($results_array)) {
			include("modeles/commande_liste.php");
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_NO_RESULT']))->fetch();
			include("modeles/commande_liste.php");
		}
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_NO_RESULT']))->fetch();
		include("modeles/commande_liste.php");
	}
	// Affichage des commandes en liste
	// affiche_liste_commandes_admin();
}

/**
 * Permet d'envoyer à l'utilisateur sa facture au format pdf par email
 *
 * @param array $frm Array with all fields data
 * @return
 */
function send_facture_pdf_commandes($frm)
{
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
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_MSG_ORDER_SENT_OK'], intval($frm['id']), $result['email'])))->fetch();
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ERR_NO_EMAIL_KNOWN_FOR_ORDER'], intval($frm['id']))))->fetch();
		}
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ERR_NO_EMAIL_KNOWN_FOR_ORDER'], intval($frm['id']))))->fetch();
	}
}

/**
 * Fontion permettant de mettre à jour les points cadeaux
 *
 * @param array $frm Array with all fields data
 * @return
 */
function update_points($frm)
{
	if (!empty($frm)) {
		query("UPDATE peel_commandes
			SET points_etat='" . intval($frm['points_etat']) . "'
			WHERE id='" . intval($frm['id']) . "'");
		$points = intval($frm['points']);

		if ($frm['points_etat'] == 1) {
			query("UPDATE peel_utilisateurs
				SET points=points+'" . intval($points) . "'
				WHERE id_utilisateur='" . intval($frm['id_utilisateur']) . "'");
		} elseif ($frm['points_etat'] == 2) {
			query("UPDATE peel_utilisateurs
				SET points=points-'" . intval($points) . "'
				WHERE id_utilisateur='" . intval($frm['id_utilisateur']) . "'");
		}
	}
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
		} elseif(!empty($frm['email'])) {
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
		$frm['zone'] = $result['zone'];
		if(!isset($frm['apply_vat'])){
			// Si $frm['apply_vat'] est déjà défini, alors on garde la valeur qui a priorité sur la configuration du pays en BDD
			$frm['apply_vat'] = ($result['tva'] && !is_user_tva_intracom_for_no_vat($frm['id_utilisateur']) && !is_micro_entreprise_module_active());
		}
		$frm['zoneFranco'] = $result['on_franco'];
	} else {
		$frm['zone'] = false;
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
		$frm['total_poids'] += ($product_object->poids + $product_object->configuration_overweight) * $this_article['quantite'];
		$this_article['option'] = $product_object->format_prices($product_object->configuration_size_price_ht + $product_object->configuration_total_original_price_attributs_ht, $frm['apply_vat'], false, false, false) + $this_article['total_prix_attribut'];
		$this_article['option_ht'] = $product_object->format_prices($product_object->configuration_size_price_ht + $product_object->configuration_total_original_price_attributs_ht, false, false, false, false) + $total_prix_attribut_ht;

		$this_article['option'] = round($this_article['option'], 2); //On doit arrondir les valeurs tarifaires officielles
		$this_article['option_ht'] = round($this_article['option_ht'], 2); //On doit arrondir les valeurs tarifaires officielles
		$this_article['points'] = $product_object->points;

		/*
		  Non renseignés :
		  $this_article['giftlist_owners'] = ;
		  $this_article['points'] = ;
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
		$delivery_cost_infos = get_delivery_cost_infos($frm['total_poids'], $total_produit, vb($frm['type_transport']), $frm['zone'], $frm['nb_produits']);
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
		$cout_transport_ht = vn($cout_transport) / (1 + vn(get_float_from_user_input(vn($frm['tva_transport']))) / 100);
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
			<table class="admin_commande_details" id="line' . $i . '">
				<tr class="top">
					<td width="20">
						<img src="' . $GLOBALS['administrer_url'] . '/images/b_drop.png" alt="'.$GLOBALS['STR_DELETE'] . '" onclick="if(confirm(\''.$GLOBALS["STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM"] .'\')){delete_order_line(' . $i . ');} return false;" title="' . String::str_form_value($GLOBALS["STR_ADMIN_PRODUCT_ORDERED_DELETE"]) . '" style="cursor:pointer" />
						<input name="nom_attribut_' . $i . '" type="hidden" value="' . String::str_form_value(vb($line_data['nom_attribut'])) . '" />
						<input name="total_prix_attribut_' . $i . '" type="hidden" value="' . String::str_form_value(vb($line_data['total_prix_attribut'])) . '" />
					</td>
					<td width="40">
						<input name="id' . $i . '" style="width:100%" type="text" value="' . String::str_form_value(vb($line_data['id'])) . '" />
					</td>
					<td width="65">
						<input id="ref' . $i . '" name="ref' . $i . '" style="width:100%"  type="text" value="' . String::str_form_value(vb($line_data['ref'])) . '" />
					</td>
					<td>
						<input type="text" id="l' . $i . '" name="l' . $i . '" style="width:100%" value="' . String::str_form_value($line_data['nom']) . '" />' . (isset($line_data['on_download'])?($line_data['on_download'] == 1?'<br/><a href="' . get_current_url(false) . '?mode=download">'.$GLOBALS["STR_ADMIN_PRODUITS_NUMERIC_PRODUCT_SEND"].'</a>':''):'') . '
					</td>
					<td width="70" id="s' . $i . '" class="center"><select style="width:70px" name="size_' . $i . '">' . $size_options_html . '</select></td>
					<td width="70" id="c' . $i . '" class="center"><select style="width:70px" name="color_' . $i . '">' . $color_options_html . '</select></td>
					<td width="40"><input type="text" name="q' . $i . '" style="width:100%" value="' . String::str_form_value($line_data['quantite']) . '" id="q' . $i . '" /></td>
					<td width="70"><input type="text" name="p_cat' . $i . '" style="width:100%" value="' . String::str_form_value($prix_cat_displayed) . '" id="p_cat' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'percentage\');" /></td>
					<td width="60"><input type="text" name="remis' . $i . '" style="width:100%" value="' . String::str_form_value($unit_fixed_remise_displayed) . '" id="remis' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'amount\');" /></td>
					<td width="40"><input type="text" name="perc' . $i . '" style="width:100%" value="' . String::str_form_value($line_data['percent']) . '" id="perc' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'percentage\');" /></td>
					<td width="70"><input type="text" name="p' . $i . '" style="width:100%" value="' . String::str_form_value($purchase_prix_displayed) . '" id="p' . $i . '" onkeyup="order_line_calculate(' . $i . ', \'final\');" /></td>
					<td width="60" id="t' . $i . '">
						<select name="t' . $i . '">' . $tva_options_html . '</select>
					</td>
					<td width="120"> ' . vb($attribute_display) . ' </td>
				</tr>
			</table>
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
			<table style="background-color:#FFFFFF;" border="1" width="100%">
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
		$output .= '<br /><center><h2>'.$GLOBALS['STR_ADMIN_NO_ADMIN_ACTION_FOUND_FOR_THIS_USER'].'</h2></center><br />';
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
function affiche_recherche_connexion_user($frm = null)
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
	$Links->OrderDefault = 'id';
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();
	if (!empty($results_array)) {
		// Affichage des connexions en liste
		include("modeles/connexion_user_liste.php");
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_NO_RESULT']))->fetch();
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
	<form method="post" id="phone" action="' . get_current_url(false) . '#phone_event" >
		<a name="phone_event"></a>
		<input type="hidden" name="mode" value="phone_call" />
		<input type="hidden" name="id_utilisateur" value="' . intval($user_id) . '" />';
	if (!empty($res)) {
		// warning : phone call not ended;
		$output .= '
			<hr /><center><h2 id="phone_section" style="color:green">' . sprintf(($res['action'] == 'PHONE_EMITTED'?$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_STARTED_EMITTED"]:$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_STARTED_RECEIVED"]), vb($res['pseudo_membre'])) . ' : '.$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_STARTED_ON"].' ' . get_formatted_date($res['date']) . '</h2></center>
			<br />
			<center>
				<table width="100%">
					<tr>
						<th>'.$GLOBALS["STR_COMMENTS"].'</th>
						<td class="center">
							<textarea name="form_phone_comment" rows="5" cols="50" id="phone_comment" >' . (!empty($res['remarque'])?vb($res['remarque']):'') . '</textarea>
						</td>
					</tr>
					<tr>
						<td></td>
						<td class="center"><input name="turn_off_phone" type="submit" value="'.$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_CLOSE"].'" class="bouton" /></td>
					</tr>
				</table>
			</center>';
	} else {
		$output .= '
				<center><h2 id="phone_section">'.$GLOBALS["STR_ADMIN_UTILISATEURS_MANAGE_CALLS"].'</h2></center><br />
				<center>
					<table >
						<tr>
							<th>'.$GLOBALS["STR_COMMENTS"].'</th>
						</tr>
						<tr>
							<td class="center">
								<textarea name="form_phone_comment" rows="5" cols="50" id="phone_comment" >' . (!empty($_POST['phone_comment'])?$_POST['phone_comment']:'') . '</textarea>
							</td>
						</tr>
					</table>
					<table >
						<tr>
							<td class="center" style="width:50%;">
								<table width="100%">
									<tr>
										<td class="center"><input name="phone_emitted_submit" type="submit" value="'.$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_INITIATE"].'" class="bouton" /></td>
									</tr>
								</table>
							</td>
							<td class="center" style="width:50%;">
								<table width="100%">
									<tr>
										<td class="center"><input name="phone_received_submit" type="submit" value="'.$GLOBALS["STR_ADMIN_UTILISATEURS_CALL_RECEIVED_INITIATE"].'" class="bouton" /></td>
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
 * @param boolean $return_mode
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
		$result['nombre_produit'][$i] = sprintf($GLOBALS["STR_ADMIN_UTILISATEURS_SEARCH_PRODUCTS_AT_LEAST_N"], $i);
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
 * @param boolean or array $force_update_database_lang_content 
 * @return
 */
function insere_langue($frm, $try_alter_table_even_if_modules_not_active=true, $force_update_database_lang_content = false)
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
		foreach ($GLOBALS['lang_codes'] as $lng) {
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
		foreach ($GLOBALS['lang_codes'] as $lng) {
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
		foreach ($GLOBALS['lang_codes'] as $lng) {
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
								if(in_array('position', $table_field_names) && $reference_column == 'id'){
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
			WHERE url_rewriting='' AND lang!='" . real_escape_string($new_lang)."'
			LIMIT 1";
		$query = query($sql);
		if(fetch_assoc($query)) {
			// Il y a déjà d'autres langues avec url_rewriting='' => on dit par défaut que cette langue est accessible dans le répertoire xx/
			$sql = "UPDATE peel_langues
				SET url_rewriting='".real_escape_string($new_lang)."/'
				WHERE lang='" . real_escape_string($new_lang)."'";
			query($sql);
		}
		if(!empty($imported_texts)){
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS["STR_ADMIN_LANGUES_MSG_CONTENT_NOT_IMPORTED"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '.implode(', ', $imported_texts)))->fetch();
		}
		if(!empty($not_imported_texts)){
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS["STR_ADMIN_LANGUES_ERR_CONTENT_NOT_IMPORTED"] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . implode(', ', $not_imported_texts)))->fetch();
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
		$tpl->assign('date', get_formatted_date());
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
		$tpl->assign('years2_options', $years2_options);
		$tpl->assign('from_date_txt', $GLOBALS['strStartingOn']);
		$tpl->assign('until_date_txt', $GLOBALS['strTillDay']);
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
			
			$tpl->assign('is_produit_cadeaux_module_active', is_produit_cadeaux_module_active());
			$tpl->assign('gift_product_one_issel', (vb($_GET['gift_product']) == 1));
			$tpl->assign('gift_product_zero_issel', (vb($_GET['gift_product']) === "0"));
			
			$tpl->assign('blank_src', $GLOBALS['wwwroot'] . '/images/blank.gif');
			$tpl->assign('STR_PHOTO_NOT_AVAILABLE_ALT', $GLOBALS['STR_PHOTO_NOT_AVAILABLE_ALT']);
			$tpl->assign('photo_not_available_src', $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($GLOBALS['site_parameters']['default_picture'], 80, 50, 'fit'));
			
			
			$HeaderTitlesArray = array($GLOBALS['STR_ADMIN_ACTION'], 'reference' => $GLOBALS['STR_REFERENCE'], $GLOBALS['STR_CATEGORY'], $GLOBALS['STR_WEBSITE'], ('nom_' . $_SESSION['session_langue']) => $GLOBALS['STR_ADMIN_NAME'], 'prix' => $GLOBALS['STR_PRICE'] . ' ' . $GLOBALS['site_parameters']['symbole'] . ' ' . (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']), 'etat' => $GLOBALS['STR_STATUS'], 'on_stock' => $GLOBALS['STR_STOCK']);
						if (is_gifts_module_active()) {
				$HeaderTitlesArray['points'] = $GLOBALS['STR_GIFT_POINTS'];
			}
			$HeaderTitlesArray['date_maj'] = $GLOBALS['STR_ADMIN_UPDATED_DATE'];
			$HeaderTitlesArray[] = $GLOBALS['STR_ADMIN_SUPPLIER'];
			$HeaderTitlesArray[] = $GLOBALS['STR_PHOTO'];
			$HeaderTitlesArray['nb_view'] = $GLOBALS['STR_ADMIN_PRODUITS_VIEWS_COUNT'];
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
			if (is_best_seller_module_active() && isset($frm['top_search']) && $frm['top_search'] != "null") {
				$where .= " AND p.on_top = '" . nohtml_real_escape_string($frm['top_search']) . "'";
			}
			if (is_produit_cadeaux_module_active() && isset($frm['gift_product']) && $frm['gift_product'] != "null") {
				$where .= " AND p.on_gift = '" . nohtml_real_escape_string($frm['gift_product']) . "'";
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
		$tpl->assign('STR_ORDER', $GLOBALS['STR_ORDER']);
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
					$rubrique_condition = ' ar.rubrique_id IN ' . implode(',', get_children_cat_list(vn($frm['cat_search'])), array(), 'rubriques');
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
					'titre' => (!empty($ligne['titre_' . $_SESSION['session_langue']])?String::html_entity_decode_if_needed($ligne['titre_' . $_SESSION['session_langue']]):'[-]'),
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

?>
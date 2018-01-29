# This file should be in UTF8 without BOM - Accents examples: éèê
# +----------------------------------------------------------------------+
# | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
# +----------------------------------------------------------------------+
# | This file is part of PEEL Shopping 9.0.0, which is subject to an	 |
# | opensource GPL license: you are allowed to customize the code		 |
# | for your own needs, but must keep your changes under GPL 			 |
# | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
# +----------------------------------------------------------------------+
# | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
# +----------------------------------------------------------------------+
# $Id: peel_ad_content.sql 53676 2017-04-25 14:51:39Z sdelaporte $
#

-- configuration
UPDATE `peel_configuration` SET `string` = '263' WHERE `peel_configuration`.`technical_code` = "small_width";
UPDATE `peel_configuration` SET `string` = '172' WHERE `peel_configuration`.`technical_code` = "small_height";
UPDATE `peel_configuration` SET `string` = '800' WHERE `peel_configuration`.`technical_code` = "medium_width";
UPDATE `peel_configuration` SET `string` = '800' WHERE `peel_configuration`.`technical_code` = "medium_height";
UPDATE `peel_configuration` SET `string` = 'global' WHERE `peel_configuration`.`technical_code` = "category_count_method";
UPDATE `peel_configuration` SET `string` = '4' WHERE `peel_configuration`.`technical_code` = "product_category_pages_nb_column";
UPDATE `peel_configuration` SET `string` = '4' WHERE `peel_configuration`.`technical_code` = "search_pages_nb_column";
UPDATE `peel_configuration` SET `string` = '"home",  "ads_1", "ads_2", "ads_3","ads_4","ads_5", "other"' WHERE `peel_configuration`.`technical_code` LIKE 'main_menu_items_if_available';

-- catégorie de produit
INSERT INTO `peel_categories` (`id`, `technical_code`, `parent_id`, `reference`, `lang`, `etat`, `on_special`, `position`, `nb`, `color`, `type_affichage`, `background_menu`, `background_color`, `on_child`, `promotion_devises`, `promotion_percent`, `on_carrousel`, `allow_show_all_sons_products`, `poids`, `date_insere`, `date_maj`, `site_id`) VALUES
(1, 'ads', 0, '', '', 1, 0, 0, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3),
(16, 'forfait', 0, '', '', 1, 0, 0, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3);

-- produit offre Gold et abonnement
INSERT INTO `peel_produits` (`id`, `technical_code`, `alpha`, `reference`, `reference_fournisseur`, `ean_code`, `default_image`, `image1`, `image2`, `image3`, `image4`, `image5`, `image6`, `image7`, `image8`, `image9`, `image10`, `youtube_code`, `on_estimate`, `on_reseller`, `prix`, `prix_revendeur`, `prix_flash`, `prix_achat`, `poids`, `volume`, `display_price_by_weight`, `points`, `date_insere`, `date_maj`, `promotion`, `tva`, `etat`, `on_stock`, `delai_stock`, `seuil_stock`, `stock`, `affiche_stock`, `on_promo`, `on_new`, `on_rollover`, `on_special`, `on_perso`, `on_top`, `on_gift`, `on_gift_points`, `on_ref_produit`, `nb_ref_produits`, `recommanded_product_on_cart_page`, `comments`, `position`, `on_flash`, `flash_start`, `flash_end`, `id_marque`, `cost_guides`, `etat_stock`, `on_rupture`, `lang`, `prix_promo`, `paiement`, `type_prix`, `on_check`, `mp3`, `pdf`, `id_ecotaxe`, `extrait`, `on_download`, `zip`, `id_utilisateur`, `default_color_id`, `display_tab`, `nb_view`, `extra_link`, `allow_add_product_with_no_stock_in_cart`, `site_id`) VALUES
(1, 'annonce_g_1m_1c', 'A', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', 0, 0, 49.95000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, '0000-00-00', '0000-00-00', 0.00, 0.00, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 3, '', 0, "[SITE_ID]"),
(2, 'annonce_g_1m_2c', 'A', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, '0000-00-00', '0000-00-00', 0.00, 0.00, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 2, '', 0, "[SITE_ID]"),
(3, 'annonce_g_1m_3c', 'A', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, '0000-00-00', '0000-00-00', 0.00, 0.00, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 0, '', 0, "[SITE_ID]"),
(4, 'annonce_g_3m_1c', 'A', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, '0000-00-00', '0000-00-00', 0.00, 0.00, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 0, '', 0, "[SITE_ID]"),
(5, 'annonce_g_3m_2c', 'A', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, '0000-00-00', '0000-00-00', 0.00, 0.00, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 0, '', 0, "[SITE_ID]"),
(6, 'annonce_g_3m_3c', 'A', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, '0000-00-00', '0000-00-00', 0.00, 0.00, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 0, '', 0, "[SITE_ID]"),
(7, 'annonce_g_12m_1c', 'A', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, '0000-00-00', '0000-00-00', 0.00, 0.00, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 0, '', 0, "[SITE_ID]"),
(8, 'annonce_g_12m_2c', 'A', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, '0000-00-00', '0000-00-00', 0.00, 0.00, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 0, '', 0, "[SITE_ID]"),
(9, 'annonce_g_12m_3c', 'A', '', '', '', 1, '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, '0000-00-00', '0000-00-00', 0.00, 0.00, 1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 0, '', 0, "[SITE_ID]");

-- catégories d'annonces
INSERT INTO `peel_categories_annonces` (`id`, `parent_id`, `reference`, `lang`, `etat`, `on_special`, `position`, `nb`, `color`, `type_affichage`, `background_menu`, `background_color`, `on_child`, `promotion_devises`, `promotion_percent`, `risque_activite`, `site_id`) VALUES
(1, 0, '', '', 1, 0, 4, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 5, "[SITE_ID]"),
(2, 0, '', '', 1, 0, 5, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, "[SITE_ID]"),
(3, 0, '', '', 1, 0, 3, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, "[SITE_ID]"),
(4, 0, '', '', 1, 0, 2, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, "[SITE_ID]"),
(5, 0, '', '', 1, 0, 2, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, "[SITE_ID]");

-- annonces
INSERT INTO `peel_lot_vente` (`ref`, `prix`, `quantite`, `date_insertion`, `creation_date`, `id_personne`, `id_adresse`, `photo1`, `photo2`, `photo3`, `photo4`, `photo5`, `photo6`, `photo7`, `photo8`, `photo9`, `photo10`, `id_categorie`, `enligne`, `on_home`, `auto_update`, `lot_size_type`, `by_cron`, `gold`, `attributs_list`, `site_id`) VALUES
(1, '1130 € + charges', '', NOW(), NOW(), 2, 3, 'immeuble-annonce-yrb96m.jpg', '', '', '', '', '', '', '', '', '', 3, 'OK', 0, 'FALSE', 'detail', 0, 0, '', "[SITE_ID]"),
(2, '75', '', NOW(), NOW(), 2, 3, 'produit-montre-jaune-mkpmmf.jpg', '', '', '', '', '', '', '', '', '', 5, 'OK', 0, 'FALSE', 'detail', 0, 0, '', "[SITE_ID]"),
(3, '120', '300, par 4 minimum', NOW(), NOW(), 2, 3, 'produit-chaise-jaune-jc2mdn.jpg', '', '', '', '', '', '', '', '', '', 1, 'OK', 0, 'FALSE', 'gros,demigros', 0, 0, '', "[SITE_ID]"),
(4, '10', '2', NOW(), NOW(), 2, 3, 'produit-homme-tshirt-r4xqjg.jpg', '', '', '', '', '', '', '', '', '', 2, 'OK', 0, 'FALSE', 'detail', 0, 0, '', "[SITE_ID]"),
(5, 'à partir de 20 € par personne', '', NOW(), NOW(), 2, 3, 'produit-verres-cocktail-hudyes.jpg', '', '', '', '', '', '', '', '', '', 4, 'OK', 0, 'FALSE', 'gros,demigros,detail', 0, 0, '', "[SITE_ID]"),
(6, '225', '1', NOW(), NOW(), 2, 3, 'produit-bijou-perle-cbth9g.jpg', '', '', '', '', '', '', '', '', '', 2, 'OK', 0, 'FALSE', 'detail', 0, 1, '', "[SITE_ID]"),
(7, 'à partir de 15 €', 'Minimum 10 pièces', NOW(), NOW(), 2, 3, 'produit-femme-soutiengorge-p9wby4.jpg', '', '', '', '', '', '', '', '', '', 2, 'OK', 0, 'FALSE', 'gros,demigros', 0, 0, '', "[SITE_ID]"),
(8, '30', '1', NOW(), NOW(), 2, 3, 'produit-casque-jaune-vxuq4a.jpg', '', '', '', '', '', '', '', '', '', 5, 'OK', 0, 'FALSE', 'detail', 0, 1, '', "[SITE_ID]");

-- annonce Gold
INSERT INTO `peel_gold_ads` (`ad_id`, `user_id`, `categories_list`, `expiration_date`, `actif`, `update`, `special_update_rights`, `historic_infos`, `text_intro_fr`, `text_intro_en`, `text_intro_es`) VALUES
(8, 2, '05', '2050-10-31 17:09:44', 1, 0, 'FALSE', '', '', '', ''),
(6, 2, '02', '2050-10-31 15:56:11', 1, 0, 'FALSE', '', '', '', '');

-- publicité
INSERT INTO `peel_banniere` (`id`, `id_categorie`, `description`, `image`, `date_debut`, `date_fin`, `etat`, `hit`, `vue`, `lien`, `position`, `lang`, `target`, `tag_html`, `extra_javascript`, `width`, `height`, `search_words_list`, `alt`, `annonce_number`, `rang`, `on_home_page`, `on_other_page_category`, `on_first_page_category`, `on_announcement_creation_page`, `on_other_page`, `on_search_engine_page`, `on_background_site`, `keywords`, `list_id`, `pages_allowed`, `do_not_display_on_pages_related_to_user_ids_list`, `site_id`, `on_ad_page_details`, `on_ad_creation_page`) VALUES
(NULL, 0, 'PEEL', 'pub-2-qcq3bt.jpg', NOW(), '2030-12-31 00:00:00', 1, 0, 141, 'https://www.peel.fr/', 2, 'fr', '_self', '', '', '160', '600', '', NULL, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 'all', '', "[SITE_ID]", 0, 0),
(NULL, 0, 'PEEL', 'pub-3-h5k5jv.jpg', NOW(), '2030-12-31 00:00:00', 1, 0, 134, 'http://www.peel-shopping.com/', 3, 'fr', '_self', '', '', '160', '600', '', NULL, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 'all', '', "[SITE_ID]", 0, 0),
(NULL, 0, 'Page d\'accueil haut gauche ', 'pub-1-fb2yzy.jpg', NOW(), '2030-10-31 00:00:00', 1, 0, 145, 'https://www.advisto.fr', 1, '', '_self', '', '', '160', '600', '', NULL, 0, 0, 1, 0, 0, 0, 0, 0, 0, '', '', 'all', '', "[SITE_ID]", 0, 0);

-- module de publicité
UPDATE `peel_modules` SET `etat` = '1' WHERE `peel_modules`.`technical_code` = 'advertising1'; 
UPDATE `peel_modules` SET `display_mode` = '' WHERE `peel_modules`.`technical_code` = 'advertising1'; 
UPDATE `peel_modules` SET `location` = 'home_left' WHERE `peel_modules`.`technical_code` = 'advertising1';
UPDATE `peel_modules` SET `location` = 'right' WHERE  `peel_modules`.`technical_code` = 'advertising3';
UPDATE `peel_modules` SET `display_mode` = '' WHERE `peel_modules`.`technical_code` = 'advertising3';
UPDATE `peel_modules` SET `etat` = '1' WHERE `peel_modules`.`technical_code` = 'advertising3';

-- Création du porteur d'annonces
INSERT INTO `peel_utilisateurs` (`id_utilisateur`, `code_client`, `email`, `email_bounce`, `mot_passe`, `priv`, `civilite`, `prenom`, `pseudo`, `nom_famille`, `societe`, `intracom_for_billing`, `naissance`, `telephone`, `fax`, `portable`, `adresse`, `code_postal`, `ville`, `pays`, `address_bill_default`, `address_ship_default`, `cnil`, `newsletter`,  `commercial`,`remise_percent`, `remise_valeur`, `points`, `on_vacances`, `on_vacances_date`, `format`, `message`, `siret`, `ape`, `code_banque`, `code_guichet`, `numero_compte`, `cle_rib`, `domiciliation`, `iban`, `bic`, `url`, `description`, `message_client`, `date_insert`, `date_update`, `alerte`, `nom_utilisateur`, `region`, `avoir`, `statut_coupon`, `type`, `fonction`, `etat`, `id_parrain`, `id_groupe`, `origin`, `origin_other`, `lang`, `seg_buy`, `seg_want`, `seg_think`, `seg_followed`, `seg_who`, `Admis`, `Valid`, `promo`, `id_cat_1`, `id_cat_2`, `id_cat_3`, `activity`, `project_product_proposed`, `project_date_forecasted`, `commercial_contact_id`, `project_budget_ht`, `project_chances_estimated`, `ad_insert_delay`, `logo`, `description_document`, `document`, `on_client_module`, `on_photodesk`, `access_history`, `site_id`, `parameters`, `risque_historique`, `risque_contrepartie`, `note_annonceur`, `note_administrateur`, `risque_activite`, `control_plus`, `days_with_ads`, `platinum_status`, `platinum_until`, `platinum_activation_date`, `diamond_status`, `diamond_activation_date`, `diamond_until`) VALUES
(2, 'CLT20171', 'annonceur@test.fr', '', '1f052e603bed022f09030e3c6d29ff47', 'util', '', 'Annonceur', 'annonceur', 'Annonceur', 'Annonceur destock', '', '0000-00-00', '01 75 43 67 97', '', '', '51, boulevard de Strabsourg', '75010', 'Paris', 473, 'original_address', 'original_address', 1, 1,  1,  0.00, 0.00, 0, 0, '0000-00-00', 'html', '', '', '', '', '', '', '', '', '', '', '', '', '', NOW(), NOW(), '', '', '', 0.00000, '', '', '', 1, 0, 0, 0, '', 'fr', 'no_info', 'no_info', 'no_info', 'no_info', 'no_info', 'OK', 'YES', '', 0, 0, 0, '', '', '0000-00-00', 0, 0.00000, '0', 'max', '', '', '', 0, 0, 0, "[SITE_ID]", '', 0, 0, 0, -1, 0, 0, 0, 'NO', 0, '0000-00-00 00:00:00', 'YES', NOW(), 2577112111);

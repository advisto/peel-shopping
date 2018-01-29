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
# $Id: peel_shop_content.sql 53676 2017-04-25 14:51:39Z sdelaporte $
#
-- configuration
UPDATE `peel_configuration` SET `string` = '263' WHERE `peel_configuration`.`technical_code` = "small_width";
UPDATE `peel_configuration` SET `string` = '172' WHERE `peel_configuration`.`technical_code` = "small_height";
UPDATE `peel_configuration` SET `string` = '800' WHERE `peel_configuration`.`technical_code` = "medium_width";
UPDATE `peel_configuration` SET `string` = '800' WHERE `peel_configuration`.`technical_code` = "medium_height";
UPDATE `peel_configuration` SET `string` = 'global' WHERE `peel_configuration`.`technical_code` = "category_count_method";
UPDATE `peel_configuration` SET `string` = '4' WHERE `peel_configuration`.`technical_code` = "product_category_pages_nb_column";
UPDATE `peel_configuration` SET `string` = '4' WHERE `peel_configuration`.`technical_code` = "search_pages_nb_column";

-- rubrique
INSERT INTO `peel_rubriques` (`id`, `parent_id`, `image`, `lang`, `on_special`, `etat`, `position`, `articles_review`, `technical_code`, `date_insere`, `date_maj`, `site_id`) VALUES
(5, 0, '', '', 0, 1, 0, 0, '', NOW(), NOW(), "[SITE_ID]");

-- module
UPDATE `peel_modules` SET `etat` = '0' WHERE `peel_modules`.`technical_code` = 'last_views';

-- catégories
INSERT INTO `peel_categories` (`id`, `technical_code`, `parent_id`, `reference`, `lang`, `etat`, `on_special`, `position`, `nb`, `color`, `type_affichage`, `background_menu`, `background_color`, `on_child`, `promotion_devises`, `promotion_percent`, `on_carrousel`, `allow_show_all_sons_products`, `poids`, `date_insere`, `date_maj`, `site_id`) VALUES
(2, '', 0, '', '', 1, 0, 0, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(3, '', 0, '', '', 1, 0, 1, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(4, '', 0, '', '', 1, 0, 2, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(5, '', 0, '', '', 1, 0, 3, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(6, '', 0, '', '', 1, 0, 4, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(7, '', 4, '', '', 1, 0, 0, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(8, '', 2, '', '', 1, 0, 0, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(9, '', 2, '', '', 1, 0, 1, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(10, '', 3, '', '', 1, 0, 0, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(11, '', 3, '', '', 1, 0, 1, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(12, '', 6, '', '', 1, 0, 0, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(13, '', 6, '', '', 1, 0, 1, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(14, '', 2, '', '', 1, 0, 2, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]"),
(15, '', 2, '', '', 1, 0, 3, 0, '', 0, '#', '#', 0, 0.00000, 0.00000, 0, 0, 0.00, NOW(), NOW(), "[SITE_ID]");

-- association entre produits et catégories
INSERT INTO `peel_produits_categories` (`produit_id`, `categorie_id`) VALUES
(10, 7),
(11, 9),
(12, 11),
(13, 12),
(14, 5),
(15, 13),
(16, 9),
(17, 10),
(18, 8),
(19, 13),
(20, 14),
(21, 13),
(27, 8),
(28, 14),
(29, 9),
(30, 15);

-- produits
INSERT INTO `peel_produits` (`id`, `technical_code`, `alpha`, `reference`, `reference_fournisseur`, `ean_code`, `default_image`, `image1`, `image2`, `image3`, `image4`, `image5`, `image6`, `image7`, `image8`, `image9`, `image10`, `youtube_code`, `on_estimate`, `on_reseller`, `prix`, `prix_revendeur`, `prix_flash`, `prix_achat`, `poids`, `volume`, `display_price_by_weight`, `points`, `date_insere`, `date_maj`, `promotion`, `tva`, `etat`, `on_stock`, `delai_stock`, `seuil_stock`, `stock`, `affiche_stock`, `on_promo`, `on_new`, `on_rollover`, `on_special`, `on_perso`, `on_top`, `on_gift`, `on_gift_points`, `on_ref_produit`, `nb_ref_produits`, `recommanded_product_on_cart_page`, `comments`, `position`, `on_flash`, `flash_start`, `flash_end`, `id_marque`, `cost_guides`, `etat_stock`, `on_rupture`, `lang`, `prix_promo`, `paiement`, `type_prix`, `on_check`, `mp3`, `pdf`, `id_ecotaxe`, `extrait`, `on_download`, `zip`, `id_utilisateur`, `default_color_id`, `display_tab`, `nb_view`, `extra_link`, `allow_add_product_with_no_stock_in_cart`, `site_id`) VALUES
(10, '', 'F', '', '', '', 1, 'produit-chaise-jaune-ujxwxt.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 285.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 12, '', 0, "[SITE_ID]"),
(11, '', 'S', '', '', '', 1, 'produit-femme-soutiengorge-bvksdb.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 15.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 1, '', 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 1, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 1, 65, '', 0, "[SITE_ID]"),
(12, '', 'V', '', '', '', 1, 'produit-verres-cocktail-fmaxph.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 10.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 1, '', 0, "[SITE_ID]"),
(13, '', 'B', '', '', '', 1, 'produit-bijou-perle-vdnh9z.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 120.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 1, '', 0, "[SITE_ID]"),
(14, '', 'P', '', '', '', 1, 'produit-parfum-missdior-z2j63v.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 75.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 2, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 3, '', 0, "[SITE_ID]"),
(15, '', 'M', '', '', '', 1, 'produit-montre-jaune-yanah6.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 349.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 3, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 5, '', 0, "[SITE_ID]"),
(16, '', 'M', '', '', '', 1, 'produit-femme-maillotdebain-tuunms.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 25.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 5, '', 0, "[SITE_ID]"),
(17, '', 'S', '', '', '', 1, 'produit-sandwich-foccacia-vbessq.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 5.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 2, '', 0, "[SITE_ID]"),
(18, '', 'T', '', '', '', 1, 'produit-homme-tshirt-eudprq.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 15.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 11, '', 0, "[SITE_ID]"),
(19, '', 'C', '', '', '', 1, 'produit-casque-jaune-ffv7ar.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 149.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 3, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 5, '', 0, "[SITE_ID]"),
(20, '', 'C', '', '', '', 1, 'produit-enfant-chemise-j6yzkp.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 35.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 0, '', 0, "[SITE_ID]"),
(21, '', 'M', '', '', '', 1, 'produit-machine-cafe-rzjxf7.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 39.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 1, '', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 24, '', 0, "[SITE_ID]"),
(30, '', 'B', '', '', '', 1, 'produit-chaussures-bottines-dnnnfn.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 95.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 1, '', 0, "[SITE_ID]"),
(29, '', 'T', '', '', '', 1, 'produit-tshirt-usa-gf82cb.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 45.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 0, '', 0, "[SITE_ID]"),
(28, '', 'M', '', '', '', 1, 'produit-enfant-maillot-raye-fknphv.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 15.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 1, '', 0, "[SITE_ID]"),
(27, '', 'E', '', '', '', 1, 'produit-ensemble-chemise-veste-28hkpx.jpg', '', '', '', '', '', '', '', '', '', '', 0, 0, 149.00000, 0.00000, 0.00000, 0.00000, 0.00, 0.00, 0, 0, NOW(), NOW(), 0.00, 20.00, 1, 0, '', 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0.00, 0, 0, '', 0.00000, '', '', 0, '', '', 0, '', 0, '', 0, 0, 0, 1, '', 0, "[SITE_ID]");

-- Couleurs
INSERT INTO `peel_couleurs` (`id`, `prix`, `prix_revendeur`, `percent`, `position`, `mandatory`, `site_id`) VALUES
(1, 0.00000, 0.00000, 0.00000, 0, 0, "[SITE_ID]"),
(2, 0.00000, 0.00000, 0.00000, 0, 0, "[SITE_ID]");

-- Association entre produits et couleurs
INSERT INTO `peel_produits_couleurs` (`produit_id`, `couleur_id`, `default_image`, `image1`, `image2`, `image3`, `image4`, `image5`) VALUES
(11, 2, 0, '', '', '', '', ''),
(11, 1, 0, '', '', '', '', ''),
(21, 2, 1, '', '', '', '', ''),
(21, 1, 1, '', '', '', '', '');
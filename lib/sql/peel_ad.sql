# This file should be in UTF8 without BOM - Accents examples: éèê
# +----------------------------------------------------------------------+
# | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
# +----------------------------------------------------------------------+
# | This file is part of PEEL Shopping 9.2.1, which is subject to an	 |
# | opensource GPL license: you are allowed to customize the code		 |
# | for your own needs, but must keep your changes under GPL 			 |
# | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
# +----------------------------------------------------------------------+
# | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
# +----------------------------------------------------------------------+
# $Id: peel_ad.sql 53676 2017-04-25 14:51:39Z sdelaporte $
#
-- configuration
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'contact_form_short_mode', 'core', 'boolean', 'true', '', '0000-00-00 00:00:00.000000', '', '1', "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'ads_do_not_show_become_sponsor_in_ads_listed', 'core', 'boolean', 'true', '', '0000-00-00 00:00:00.000000', '', '1', "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'cat_info_sentence_disable', 'core', 'boolean', 'true', '', '0000-00-00 00:00:00.000000', '', '1', "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'home_vitrines_block_content_link_disable', 'core', 'boolean', 'true', '', '0000-00-00 00:00:00.000000', '', '1', "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'ads_home_presentation_search_category', 'core', 'integer', '5', '', NOW(), '', '1', "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'home_vitrines_block_default_picture', 'admin', 'string', 'Logo_neutre_annonceur.png', '', '2017-10-24 18:04:51', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'modules_block_class_by_technical_code_array', 'core', 'array', '"upsell" => "col-sm-12"', '', '2015-05-27 23:04:10', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'home_vitrines_block_nb_colonnes', 'admin', 'integer', '3', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'home_vitrines_block_nb_picture_max', 'admin', 'integer', '6', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'home_vitrines_block_picture_max_width', 'admin', 'integer', '250', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'home_vitrines_block_picture_max_height', 'admin', 'integer', '250', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'verified_ads_list_nb_picture_max', 'admin', 'integer', '6', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'verified_ads_list_picture_max_width', 'admin', 'integer', '250', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'verified_ads_list_picture_max_height', 'admin', 'integer', '250', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'verified_ads_list_nb_colonnes', 'admin', 'integer', '3', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'ads_do_not_show_become_sponsor_in_ads_listed', 'admin', 'boolean', 'true', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'cat_info_sentence_disable', 'admin', 'boolean', 'true', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'ads_home_last_sale_background_disabled', 'admin', 'boolean', 'true', '', '0000-00-00 00:00:00', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'ads_details_image', 'manuel', 'string', '', '', '2017-10-24 09:52:15', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'module_avis', 'core', 'integer', '0', '', '2017-11-10 17:18:59', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'module_pensebete', 'core', 'integer', '0', '', '2017-11-10 17:18:59', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'ads_position_of_share_text_in_ad_details', 'manuel', 'string', 'bottom_image', '', '2017-10-23 16:18:31', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'STR_TITLE_SEARCH_HEADER', 'manuel', 'string', 'Lorem ipsum dolor sit amet, consecteur adipiscing elit', '', '2017-10-19 15:51:04', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'ads_categories_depth_in_menu', 'manuel', 'integer', '1', '', '2017-10-19 16:23:50', '', 1, "[SITE_ID]");
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES
('ads_focus_image', 'manuel', 'string', '', '', '2017-10-24 10:35:04', '', 1, "[SITE_ID]"),
('modules_block_class_by_display_mode_array', 'manuel', 'array', '"banner_up" => "col-sm-12", "center_left" => "col-sm-6", "center_right" => "col-sm-6"', '', '2017-10-24 10:30:52', '', 1, "[SITE_ID]"),
('ads_list_ads_image', 'manuel', 'string', '', '', '2017-10-24 10:00:34', '', 1, "[SITE_ID]"),
('ads_details_image', 'manuel', 'string', '', '', '2017-10-24 09:52:15', '', 1, "[SITE_ID]");

-- modules
INSERT INTO `peel_modules` (`technical_code`, `location`, `display_mode`, `position`, `etat`, `title_fr`, `in_home`, `site_id`) VALUES
('become_verified', 'right', '', 9, 1, 'Devenez verified', 0, "[SITE_ID]"),
('upsell', 'center_middle_home', '', 1, 1, 'En vedette', 0, "[SITE_ID]"),
('product_new', 'center_middle_home', 'center_left', 3, 1, 'Dernières annonces de vente', 0, "[SITE_ID]"),
('search_by_list', 'center_middle_home', 'center_right', 4, 1, 'Dernières annonces de recherche', 0, "[SITE_ID]");
UPDATE `peel_modules` SET `etat` = '0' WHERE `peel_modules`.`technical_code` = "annonces";
UPDATE `peel_modules` SET `etat` = '0' WHERE `peel_modules`.`technical_code` = "search";
UPDATE `peel_modules` SET `etat` = '0' WHERE `peel_modules`.`technical_code` = "caddie";

INSERT INTO peel_html (`lang`, `contenu_html`, `etat`, `titre`, `o_timestamp`, `a_timestamp`, `emplacement`, `site_id`) VALUES
('fr', '<div class="col-md-12"> <div class="row"> <div class="col-md-3 col-sm-6 personnalized_footer_block_1"> [NEWSLETTER_FORM] <div class="personnalized_footer_company_logo"> <a href="[WWWROOT]"><img src="[WWWROOT]/upload/logo_footer_Made_with_PEEL.png" alt="logo footer Made with PEEL" /></a> </div> </div> <div class="col-md-3 col-sm-6 personnalized_footer_block_2"> <div> <span style="font-weight:bold;">VOTRE SOCIETE</span><br /> adresse<br /> code postal ville<br /> pays<br /> Tel. XX XX XX XX XX </div> </div> <div class="clearfix visible-sm"></div> <div class="col-md-3 col-sm-6 personnalized_footer_block_3"> <img src="[WWWROOT]/upload/bloc_paypal.png" alt=""> </div> <div class="col-md-3 col-sm-6 personnalized_footer_block_4"> <div class="footer_copyright right"> <div><span><a href="https://www.advisto.fr/">Une création PEEL création de site eCommerce</a></span></div> </div> <div class="footer_custom_social_icons"> <a href="https://fr-fr.facebook.com/" target="_blank"> <img class="footer_custom_address_facebook_logo" src="[WWWROOT]/modeles/peel9/images/facebook.png" alt="Facebook"> </a> <a href="https://twitter.com/?lang=fr" target="_blank"> <img class="footer_custom_address_twitter_logo" src="[WWWROOT]/modeles/peel9/images/twitter.png" alt="Twitter"> </a> <a href="[WWWROOT]/modules/rss/rss.php" onclick="return(window.open(this.href)?false:true);"> <img class="footer_custom_address_rss_logo" src="[WWWROOT]/modeles/peel9/images/rss.png" alt="RSS"> </a> </div> </div> </div> </div> <div class="col-md-12 personnalized_footer_links_block"> <ul class="link"> <li><a href="[WWWROOT]/utilisateurs/contact.php">Nous contacter</a></li> <li><a href="[WWWROOT]/legal.php">Informations légales</a></li> <li><a href="[WWWROOT]/cgv.php">Conditions de vente</a></li> <li><a href="[WWWROOT]/plan_acces.php">Plan d''accès</a></li>  </ul> </div>', 1, 'Footer personnalisable', NOW(), NOW(), 'footer_full_custom_html',"[SITE_ID]"),
('en', '<div class="col-md-12"> <div class="row"> <div class="col-md-3 col-sm-6 personnalized_footer_block_1"> [NEWSLETTER_FORM] <div class="personnalized_footer_company_logo"> <a href="[WWWROOT]"><img src="[WWWROOT]/upload/logo_footer_Made_with_PEEL.png" alt="logo footer Made with PEEL" /></a> </div> </div> <div class="col-md-3 col-sm-6 personnalized_footer_block_2"> <div> <span style="font-weight:bold;">YOUR COMPANY</span><br /> address<br /> zip code city<br /> country<br /> Tel. XX XX XX XX XX </div> </div> <div class="clearfix visible-sm"></div> <div class="col-md-3 col-sm-6 personnalized_footer_block_3"> <img src="[WWWROOT]/upload/bloc_paypal.png" alt=""> </div> <div class="col-md-3 col-sm-6 personnalized_footer_block_4"> <div class="footer_copyright right"> <div><span><a href="https://www.advisto.fr/">Created by PEEL eCommerce solution</a></span></div> </div> <div class="footer_custom_social_icons"> <a href="https://fr-fr.facebook.com/" target="_blank"> <img class="footer_custom_address_facebook_logo" src="[WWWROOT]/modeles/peel9/images/facebook.png" alt="Facebook"> </a> <a href="https://twitter.com/?lang=fr" target="_blank"> <img class="footer_custom_address_twitter_logo" src="[WWWROOT]/modeles/peel9/images/twitter.png" alt="Twitter"> </a> <a href="[WWWROOT]/modules/rss/rss.php" onclick="return(window.open(this.href)?false:true);"> <img class="footer_custom_address_rss_logo" src="[WWWROOT]/modeles/peel9/images/rss.png" alt="RSS"> </a> </div> </div> </div> </div> <div class="col-md-12 personnalized_footer_links_block"> <ul class="link"> <li><a href="[WWWROOT]/utilisateurs/contact.php">To contact us</a></li> <li><a href="[WWWROOT]/legal.php">Legal informations</a></li> <li><a href="[WWWROOT]/cgv.php">Terms and conditions</a></li> <li><a href="[WWWROOT]/plan_acces.php">Access map</a></li>  </ul> </div>', 1, 'Footer customizable', NOW(), NOW(), 'footer_full_custom_html',"[SITE_ID]"),
('es','<div class="col-md-12"> <div class="row"> <div class="col-md-3 col-sm-6 personnalized_footer_block_1"> [NEWSLETTER_FORM] <div class="personnalized_footer_company_logo"> <a href="[WWWROOT]"><img src="[WWWROOT]/upload/logo_footer_Made_with_PEEL.png" alt="logo footer Made with PEEL" /></a> </div> </div> <div class="col-md-3 col-sm-6 personnalized_footer_block_2"> <div> <span style="font-weight:bold;">TU COMPAÑÍA</span><br /> dirección<br /> código postal ciudad<br /> país<br /> Tel. XX XX XX XX XX </div> </div> <div class="clearfix visible-sm"></div> <div class="col-md-3 col-sm-6 personnalized_footer_block_3"> <img src="[WWWROOT]/upload/bloc_paypal.png" alt=""> </div> <div class="col-md-3 col-sm-6 personnalized_footer_block_4"> <div class="footer_copyright right"> <div><span><a href="https://www.advisto.fr/">Creado por PEEL eCommerce solution</a></span></div> </div> <div class="footer_custom_social_icons"> <a href="https://fr-fr.facebook.com/" target="_blank"> <img class="footer_custom_address_facebook_logo" src="[WWWROOT]/modeles/peel9/images/facebook.png" alt="Facebook"> </a> <a href="https://twitter.com/?lang=fr" target="_blank"> <img class="footer_custom_address_twitter_logo" src="[WWWROOT]/modeles/peel9/images/twitter.png" alt="Twitter"> </a> <a href="[WWWROOT]/modules/rss/rss.php" onclick="return(window.open(this.href)?false:true);"> <img class="footer_custom_address_rss_logo" src="[WWWROOT]/modeles/peel9/images/rss.png" alt="RSS"> </a> </div> </div> </div> </div> <div class="col-md-12 personnalized_footer_links_block"> <ul class="link"><li><a href="[WWWROOT]/utilisateurs/contact.php">Contáctenos</a></li> <li><a href="[WWWROOT]/legal.php">Información legal</a></li> <li><a href="[WWWROOT]/cgv.php">Condiciones de venta</a></li> <li><a href="[WWWROOT]/plan_acces.php">Mapa</a></li></ul> </div>', 1, 'Footer personalizable', NOW(), NOW(), 'footer_full_custom_html', "[SITE_ID]");
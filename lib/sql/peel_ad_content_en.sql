# This file should be in UTF8 without BOM - Accents examples: éèê
# +----------------------------------------------------------------------+
# | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
# +----------------------------------------------------------------------+
# | This file is part of PEEL Shopping 9.1.1, which is subject to an	 |
# | opensource GPL license: you are allowed to customize the code		 |
# | for your own needs, but must keep your changes under GPL 			 |
# | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
# +----------------------------------------------------------------------+
# | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
# +----------------------------------------------------------------------+
# $Id: peel_shop_content.sql 53676 2017-04-25 14:51:39Z sdelaporte $
#
-- Configuration

-- catégories
UPDATE peel_categories SET nom_en = 'Annonces Gold', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = '', image_header_en = '', alpha_en = 'A', sentence_displayed_on_product_en = '' WHERE id = 1;
UPDATE peel_categories SET nom_en = 'Flat rate', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = '', image_header_en = '', alpha_en = 'F', sentence_displayed_on_product_en = '' WHERE id = 16;

-- produits
UPDATE peel_produits SET nom_en = '', descriptif_en = '', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 1;
UPDATE peel_produits SET nom_en = '', descriptif_en = '', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 2;
UPDATE peel_produits SET nom_en = '', descriptif_en = '', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 3;
UPDATE peel_produits SET nom_en = '', descriptif_en = '', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 4;
UPDATE peel_produits SET nom_en = '', descriptif_en = '', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 5;
UPDATE peel_produits SET nom_en = '', descriptif_en = '', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 6;
UPDATE peel_produits SET nom_en = '', descriptif_en = '', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 7;
UPDATE peel_produits SET nom_en = '', descriptif_en = '', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 8;
UPDATE peel_produits SET nom_en = '', descriptif_en = '', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 9;

-- catégories d'annonces
UPDATE peel_categories_annonces SET `alpha_en`= 'D', `nom_en`= 'Decoration and household appliances', `description_en`= '<p>From furniture to household appliances and decoration, find all the ads relating to the equipment of the house.</p>', `image_en`= '', `meta_titre_en`= '', `meta_desc_en`= '', `meta_key_en`= '', `header_html_en`= '', `presentation_en`= '', `presentation2_en`= '' WHERE id = 1;
UPDATE peel_categories_annonces SET `alpha_en`= 'F', `nom_en`= 'Fashion', `description_en`= '', `image_en`= '', `meta_titre_en`= '', `meta_desc_en`= '', `meta_key_en`= '', `header_html_en`= '', `presentation_en`= '', `presentation2_en`= '' WHERE id = 2;
UPDATE peel_categories_annonces SET `alpha_en`= 'R', `nom_en`= 'Real estate', `description_en`= '', `image_en`= '', `meta_titre_en`= '', `meta_desc_en`= '', `meta_key_en`= '', `header_html_en`= '', `presentation_en`= '', `presentation2_en`= '' WHERE id = 3;
UPDATE peel_categories_annonces SET `alpha_en`= 'H', `nom_en`= 'Hobbies', `description_en`= '', `image_en`= '', `meta_titre_en`= '', `meta_desc_en`= '', `meta_key_en`= '', `header_html_en`= '', `presentation_en`= '', `presentation2_en`= '' WHERE id = 4;
UPDATE peel_categories_annonces SET `alpha_en`= 'H', `nom_en`= 'Search', `description_en`= '', `image_en`= '', `meta_titre_en`= '', `meta_desc_en`= '', `meta_key_en`= '', `header_html_en`= '', `presentation_en`= '', `presentation2_en`= '' WHERE id = 5;

-- annonces
UPDATE peel_lot_vente SET `titre_en`='For Rent: Apartment 3 rooms 80 m²', `description_en`='<p>3 rooms crossing 80 m² Loi Carrez</p> <p>3rd floor, elevator, 2 balconies</p> <p>Low loads</p>' WHERE `ref` = 1; 
UPDATE peel_lot_vente SET `titre_en`='Connected watch', `description_en`='<p>Search watch connected Android 4.0, Bluetooth, Wifi</p> <p>Good condition.</p>' WHERE `ref` = 2; 
UPDATE peel_lot_vente SET `titre_en`='Yellow armchair', `description_en`='<p>Vintage armchair, polyurethane foam shell, 100% synthetic fabric, removable cover.</p> <p>Minimum order 4 pieces, please contact us.</p> <p><strong>On-site visit only by appointment</strong></p>' WHERE `ref` = 3; 
UPDATE peel_lot_vente SET `titre_en`='Basic Tshirts', `description_en`='<p>Due to size error, I sell 2 never worn basic tshirts size L.&#160;</p> <p>To be removed on site. Shipping costs: 2,50 €.</p>' WHERE `ref` = 4; 
UPDATE peel_lot_vente SET `titre_en`='', `description_en`='' WHERE `ref` = 5; 
UPDATE peel_lot_vente SET `titre_en`='925 silver and pearl ring', `description_en`='<p>Genuine Pearl, Certificate of Authenticity, Size 54</p>' WHERE `ref` = 6; 
UPDATE peel_lot_vente SET `titre_en`='Bras clearance', `description_en`='<p>Vavavoum brand<br /> Color: dark blue</p> <p>From 80 to 105<br /> Bonnet A to C with underwire<br /> Removable shoulder straps</p>' WHERE `ref` = 7; UPDATE peel_lot_vente SET `titre_en`='New headphones', `description_en`='<p>Search headphones. It''s all new, never used.</p> <p>Delivery by appointment, possible shipping</p>' WHERE `ref` = 8; 

-- zone HTML personnalisable
UPDATE `peel_html` SET `etat` = '0' WHERE (`emplacement` = 'home' OR `emplacement` = 'home_middle_top' OR `emplacement` = 'header_login') AND site_id="[SITE_ID]" AND lang='en';
INSERT INTO `peel_html` (`id`, `contenu_html`, `etat`, `titre`, `o_timestamp`, `a_timestamp`, `emplacement`, `lang`, `site_id`) VALUES
(NULL, '<div>\r\n<div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 header_search_block">[FUNCTION=affiche_menu_recherche,true,header]</div>\r\n</div>', 1, 'Contenu header login', '2017-10-05 14:53:15', '2017-10-18 11:24:27', 'header_login', 'en', "[SITE_ID]"),
(NULL, '<div class="col-md-3 hidden-xs hidden-sm home-lg-left right">[MODULES_LEFT]</div>\n<div class="col-md-9">\n<div class="center"><img class="ads_home_welcome_zone_image" src="[WWWROOT]/upload/annonce_image_zone_accueil.jpg" alt="" width="763" height="350" /></div>\n<div class="center">\n<p style="font-size: 28px;"><span style="font-weight: bold;">Bienvenue</span> sur votre nouveau site !</p>\n<p style="font-size: 16px;">Votre nouveau site PEEL Shopping est entièrement administrable par vos soins.<br />\nVous pouvez remplir cette zone selon vos préférences ou encore choisir de ne pas l’afficher.</p>\n<a href="[WWWROOT]/administrer/html.php" class="btn btn-primary btn_personnalized_two_lines"> 		<span class="glyphicon glyphicon-pencil"> 		</span> 		<span style="font-weight: bold;">Administrer le contenu</span><br />\n<span class="btn_personnalized_second_line">de cette zone</span> 	</a></div>\n</div>\n<div class="clearfix"></div>', 1, 'Home site content', '2017-07-07 16:09:18', '2017-07-07 16:09:18', 'home', 'en', "[SITE_ID]"),
(NULL, '<div class="ads_home_information_container">\r\n	<div class="col-md-6 ads_home_information_left_block">\r\n		<div>\r\n			<p class="ads_home_information_left_block_title">Sellers</p>\r\n			<div class="home_infos_center">\r\n				<div class="col-xs-3 ads_home_information_left_block_logo"></div>\r\n				<div class="col-xs-9 ads_home_information_left_block_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>\r\n			</div>\r\n			<p class="center"><a href="[WWWROOT]/modules/annonces/creation_annonce.php" class="btn ads_personnalized_button_create_ads">Advertise</a></p>\r\n		</div>\r\n	</div>\r\n	<div class="col-md-6 ads_home_information_right_block">\r\n		<div>\r\n			<p class="ads_home_information_right_block_title">Buyers</p>\r\n			<div class="home_infos_center">\r\n				<div class="col-xs-3 ads_home_information_right_block_logo"></div>\r\n				<div class="col-xs-9 ads_home_information_right_block_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>\r\n			</div>\r\n			<p class="center"><a href="[WWWROOT]/modules/annonces/creation_annonce.php" class="btn ads_personnalized_button_show_ads">View ads</a></p>\r\n		</div>\r\n	</div>\r\n</div>', 1, 'Contenu d\'accueil de la boutique zone middle top', '2010-11-01 12:00:00', '2017-10-25 11:44:44', 'home_middle_top', 'en', "[SITE_ID]");

-- information légale
UPDATE `peel_legal` SET `titre_en`= 'legal information', `texte_en` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;

-- conditions de vente
UPDATE `peel_cgv` SET `titre_en`= 'Terms and conditions', `texte_en` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;


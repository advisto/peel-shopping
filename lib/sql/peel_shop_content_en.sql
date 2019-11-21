# This file should be in UTF8 without BOM - Accents examples: éèê
# +----------------------------------------------------------------------+
# | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
# +----------------------------------------------------------------------+
# | This file is part of PEEL Shopping 9.2.2, which is subject to an	 |
# | opensource GPL license: you are allowed to customize the code		 |
# | for your own needs, but must keep your changes under GPL 			 |
# | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
# +----------------------------------------------------------------------+
# | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
# +----------------------------------------------------------------------+
# $Id: peel_shop_content.sql 53676 2017-04-25 14:51:39Z sdelaporte $
#
-- configuration

-- zone HTML personnalisable
UPDATE `peel_html` SET `etat` = '0' WHERE (`emplacement` = 'home' OR `emplacement` = 'home_middle' OR `emplacement` = 'home_bottom') AND site_id="[SITE_ID]" AND lang='en';
INSERT INTO `peel_html` (`id`, `contenu_html`, `etat`, `titre`, `o_timestamp`, `a_timestamp`, `emplacement`, `lang`, `site_id`) VALUES
(NULL, '<p>[CLOSE_MAIN_CONTAINER]</p>\r\n<div class="eshopping_home_welcome_zone_personnalized_banner">\r\n<div class="container">\r\n<div class="row text-center">\r\n<div class="col-md-12">\r\n<div class="eshopping_home_welcome_zone_white_block">\r\n<p style="font-size: 28px;"><span style="font-weight: bold;">Welcome</span> to your new site!</p>\r\n<p style="font-size: 16px;">Your new site is fully administrable by you.<br />\r\nYou can fill this area according to your preferences or choose not to display it.</p>\r\n<a href="[WWWROOT]/administrer/html.php" class="btn btn-primary btn_personnalized_two_lines"> 						<span class="glyphicon glyphicon-pencil"> 						</span> 						<span style="font-weight: bold;">Administer the content</span><br />\r\n<span class="btn_personnalized_second_line">of this area</span> 					</a></div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n<p>[REOPEN_MAIN_CONTAINER]</p>', 1, 'Home site content', '2017-07-07 15:58:33', '2017-10-25 17:06:13', 'home', 'en', 1),
(NULL, '<div class="row text-center eshopping_home_middle_zone">\r\n<div class="col-md-8 eshopping_home_middle_zone_left_block">\r\n<div class="eshopping_home_middle_zone_left_block_image">\r\n<div>\r\n<p style="font-size: 44px; font-weight: bold; text-transform: uppercase;">Summer sales</p>\r\n<p style="font-size: 18px; font-weight: bold;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\r\n</div>\r\n</div>\r\n</div>\r\n<div class="col-md-4 eshopping_home_middle_zone_right_block">\r\n<div class="eshopping_home_middle_zone_right_block_image"></div>\r\n<div class="eshopping_home_middle_zone_right_block_button_banner"><a href="[WWWROOT]/administrer/html.php" class="btn btn-primary btn_personnalized_two_lines"> 				<span class="glyphicon glyphicon-pencil"> 				</span> 				<span style="font-weight: bold;">Administer the content</span><br />\r\n<span class="btn_personnalized_second_line">of this area</span> 			</a></div>\r\n</div>\r\n</div>', 1, 'Middle home page', '2017-10-05 14:53:15', '2017-10-05 14:53:15', 'home_middle', 'en', 1),
(NULL, '<div class="row text-center eshopping_home_prefooter_zone">\n<div class="col-md-8 eshopping_home_prefooter_zone_left_block">\n<div class="eshopping_home_prefooter_zone_left_block_image">\n<div class="eshopping_home_prefooter_zone_button_banner">\n<p>Customize this area</p>\n<a href="[WWWROOT]/administrer/html.php" class="btn btn-primary btn_personnalized_two_lines"> 					<span class="glyphicon glyphicon-pencil"> 					</span> 					<span style="font-weight: bold;">Administer the content</span><br />\n<span class="btn_personnalized_second_line">of this area</span> 				</a></div>\n</div>\n</div>\n<div class="col-md-4 eshopping_home_prefooter_zone_right_block">\n<div class="eshopping_home_prefooter_zone_right_block_image"></div>\n</div>\n</div>', 1, 'Bas de page du site', '2017-07-07 15:58:33', '2017-10-24 15:10:46', 'home_bottom', 'en', 1);

-- catégories
UPDATE peel_categories SET nom_en = 'Clothing', description_en = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pretium ultricies pharetra. Etiam eu nisi erat. Fusce quis finibus nibh, non ultrices dolor. Donec a auctor augue, sit amet tempor nulla. Phasellus sapien ante, molestie sed dapibus vitae, rhoncus quis enim. Suspendisse sit amet metus non enim blandit dignissim nec a lectus.&nbsp;</p>', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-vetements-enwcy6.jpg', image_header_en = 'categorie-vetements-sxng9k.png', alpha_en = 'C', sentence_displayed_on_product_en = '' WHERE id = 2;
UPDATE peel_categories SET nom_en = 'Food', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-alimentation-5yfpxx.jpg', image_header_en = 'categorie-alimentation-qhfa9y.png', alpha_en = 'F', sentence_displayed_on_product_en = '' WHERE id = 3;
UPDATE peel_categories SET nom_en = 'Furniture', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-meubles-fsd8nb.jpg', image_header_en = 'categorie-meubles-2mbpbq.png', alpha_en = 'F', sentence_displayed_on_product_en = '' WHERE id = 4;
UPDATE peel_categories SET nom_en = 'Perfume', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-parfum-sjzadm.jpg', image_header_en = 'categorie-parfums-qrj6gf.png', alpha_en = 'P', sentence_displayed_on_product_en = '' WHERE id = 5;
UPDATE peel_categories SET nom_en = 'Accessories', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-accessoires-v6cumm.jpg', image_header_en = 'categorie-accessoires-jtxsvb.png', alpha_en = 'A', sentence_displayed_on_product_en = '' WHERE id = 6;
UPDATE peel_categories SET nom_en = 'Sofas', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-fauteuils-gnrqvq.jpg', image_header_en = 'categorie-fauteuils-vrpzw7.png', alpha_en = 'S', sentence_displayed_on_product_en = '' WHERE id = 7;
UPDATE peel_categories SET nom_en = 'Men', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-homme-592sdz.jpg', image_header_en = 'categorie-hommes-rbsxcw.png', alpha_en = 'M', sentence_displayed_on_product_en = '' WHERE id = 8;
UPDATE peel_categories SET nom_en = 'Woman', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-femme-tnpgbs.jpg', image_header_en = 'categorie-femmes-qdsyqb.png', alpha_en = 'W', sentence_displayed_on_product_en = '' WHERE id = 9;
UPDATE peel_categories SET nom_en = 'Sandwiches', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-sandwiches-dbjpsh.jpg', image_header_en = 'categorie-sandwiches-d8wxa5.png', alpha_en = 'S', sentence_displayed_on_product_en = '' WHERE id = 10;
UPDATE peel_categories SET nom_en = 'Kitchenware', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-vaisselle-juvpes.jpg', image_header_en = 'categorie-vaisselle-x3eq9f.png', alpha_en = 'K', sentence_displayed_on_product_en = '' WHERE id = 11;
UPDATE peel_categories SET nom_en = 'Jewelry', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-bijoux-jp5hwd.jpg', image_header_en = 'categorie-bijoux-vy9ch8.png', alpha_en = 'J', sentence_displayed_on_product_en = '' WHERE id = 12;
UPDATE peel_categories SET nom_en = 'Connected objects', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-objets-connectes-uxnsx6.jpg', image_header_en = 'categorie-objets-connectes-hadgej.png', alpha_en = 'C', sentence_displayed_on_product_en = '' WHERE id = 13;
UPDATE peel_categories SET nom_en = 'Children', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-enfant-mhauuc.jpg', image_header_en = 'categorie-enfants-fydjr8.png', alpha_en = 'C', sentence_displayed_on_product_en = '' WHERE id = 14;
UPDATE peel_categories SET nom_en = 'Shoes', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', header_html_en = '', image_en = 'categorie-chaussures-jqtrxb.jpg', image_header_en = 'categorie-chaussures-wn9bqt.png', alpha_en = 'S', sentence_displayed_on_product_en = '' WHERE id = 15;

-- Produits
UPDATE peel_produits SET nom_en = 'Baboo armchair', descriptif_en = 'An armchair with a clean design! Everybody talks about it', description_en = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 10;
UPDATE peel_produits SET nom_en = 'Push-up bra', descriptif_en = 'Chic and comfortable, set is available', description_en = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 11;
UPDATE peel_produits SET nom_en = 'set of 4 cocktail glasses', descriptif_en = 'For all occasions', description_en = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 12;
UPDATE peel_produits SET nom_en = 'Pearl ring', descriptif_en = 'A charming style', description_en = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 13;
UPDATE peel_produits SET nom_en = 'Perfume', descriptif_en = 'Eau de Parfum', description_en = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 14;
UPDATE peel_produits SET nom_en = 'Smartwatch', descriptif_en = 'Always on time, always fit', description_en = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_en = 'Smartwatch', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 15;
UPDATE peel_produits SET nom_en = 'One piece swimsuit', descriptif_en = 'quality at a low price', description_en = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 16;
UPDATE peel_produits SET nom_en = 'Focaccia bread sandwich with tomato and salad', descriptif_en = 'Light and delicious! The Best Summer Sandwich', description_en = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 17;
UPDATE peel_produits SET nom_en = 'T-shirt', descriptif_en = 'Sober and elegant!', description_en = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 18;
UPDATE peel_produits SET nom_en = 'Wired headset', descriptif_en = 'Keep up the rhythm. Compact and design', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 19;
UPDATE peel_produits SET nom_en = 'check shirt', descriptif_en = 'for small woodcutters', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 20;
UPDATE peel_produits SET nom_en = 'Nespresso coffee machine', descriptif_en = 'What else ?', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 21;
UPDATE peel_produits SET nom_en = 'Shirt and jacket set', descriptif_en = 'Casual & Chic', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 27;
UPDATE peel_produits SET nom_en = 'Blue and white striped jersey', descriptif_en = 'For real little sailors', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 28;
UPDATE peel_produits SET nom_en = 'T-shirt "USA"', descriptif_en = 'A timeless classic', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 29;
UPDATE peel_produits SET nom_en = 'Soft flesh-coloured ankle boots', descriptif_en = 'Casual chic', description_en = '', meta_titre_en = '', meta_key_en = '', meta_desc_en = '', tab1_html_en = '', tab2_html_en = '', tab3_html_en = '', tab4_html_en = '', tab5_html_en = '', tab6_html_en = '', tab1_title_en = '', tab2_title_en = '', tab3_title_en = '', tab4_title_en = '', tab5_title_en = '', tab6_title_en = '' WHERE id = 30;
							
-- rubriques
UPDATE peel_rubriques SET nom_en = 'Terms of delivery', description_en = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> ', meta_titre_en = '', meta_key_en = '', meta_desc_en = '' WHERE id = 5; 

-- information légale
UPDATE `peel_legal` SET `titre_en`= 'legal information', `texte_en` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;

-- conditions de vente
UPDATE `peel_cgv` SET `titre_en`= 'Terms and conditions', `texte_en` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;

-- couleurs
UPDATE `peel_couleurs` SET `nom_en` = 'Red' WHERE id = 1;
UPDATE `peel_couleurs` SET `nom_en` = 'Black' WHERE id = 2;
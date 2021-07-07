# This file should be in UTF8 without BOM - Accents examples: éèê
# +----------------------------------------------------------------------+
# | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
# +----------------------------------------------------------------------+
# | This file is part of PEEL Shopping 9.4.0, which is subject to an	 |
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
UPDATE `peel_html` SET `etat` = '0' WHERE (`emplacement` = 'home' OR `emplacement` = 'home_middle' OR `emplacement` = 'home_bottom') AND site_id="[SITE_ID]" AND lang='fr';
INSERT INTO `peel_html` (`id`, `contenu_html`, `etat`, `titre`, `o_timestamp`, `a_timestamp`, `emplacement`, `lang`, `site_id`) VALUES
(NULL, '<div class="row text-center eshopping_home_prefooter_zone">\n<div class="col-md-8 eshopping_home_prefooter_zone_left_block">\n<div class="eshopping_home_prefooter_zone_left_block_image">\n<div class="eshopping_home_prefooter_zone_button_banner">\n<p>Personnalisez cet espace</p>\n<a href="[WWWROOT]/administrer/html.php" class="btn btn-primary btn_personnalized_two_lines"> 					<span class="glyphicon glyphicon-pencil"> 					</span> 					<span style="font-weight: bold;">Administrer le contenu</span><br />\n<span class="btn_personnalized_second_line">de cette zone</span> 				</a></div>\n</div>\n</div>\n<div class="col-md-4 eshopping_home_prefooter_zone_right_block">\n<div class="eshopping_home_prefooter_zone_right_block_image"></div>\n</div>\n</div>', 1, 'Bas de page du site', '2017-07-07 15:58:33', '2017-10-24 15:10:46', 'home_bottom', 'fr', 1),
(NULL, '[CLOSE_MAIN_CONTAINER]\n<div class="eshopping_home_welcome_zone_personnalized_banner">\n<div class="container">\n<div class="row text-center">\n<div class="col-md-12">\n<div class="eshopping_home_welcome_zone_white_block">\n<p style="font-size: 28px;"><span style="font-weight: bold;">Bienvenue</span> sur votre nouveau site !</p>\n<p style="font-size: 16px;">Votre nouveau site PEEL Shopping est entièrement administrable par vos soins.<br />\nVous pouvez remplir cette zone selon vos préférences ou encore choisir de ne pas l’afficher.</p>\n<a href="[WWWROOT]/administrer/html.php" class="btn btn-primary btn_personnalized_two_lines"> 						<span class="glyphicon glyphicon-pencil"> 						</span> 						<span style="font-weight: bold;">Administrer le contenu</span><br />\n<span class="btn_personnalized_second_line">de cette zone</span> 					</a></div>\n</div>\n</div>\n</div>\n</div>\n[REOPEN_MAIN_CONTAINER]', 1, 'Contenu d\'accueil du site', '2017-07-07 15:58:33', '2017-10-24 15:08:40', 'home', 'fr', 1),
(NULL, '<div class="row text-center eshopping_home_middle_zone">\n<div class="col-md-8 eshopping_home_middle_zone_left_block">\n<div class="eshopping_home_middle_zone_left_block_image">\n<div>\n<p style="font-size: 44px; font-weight: bold; text-transform: uppercase;">Soldes d\'été</p>\n<p style="font-size: 18px; font-weight: bold;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>\n</div>\n</div>\n</div>\n<div class="col-md-4 eshopping_home_middle_zone_right_block">\n<div class="eshopping_home_middle_zone_right_block_image"></div>\n<div class="eshopping_home_middle_zone_right_block_button_banner"><a href="[WWWROOT]/administrer/html.php" class="btn btn-primary btn_personnalized_two_lines"> 				<span class="glyphicon glyphicon-pencil"> 				</span> 				<span style="font-weight: bold;">Administrer le contenu</span><br />\n<span class="btn_personnalized_second_line">de cette zone</span> 			</a></div>\n</div>\n</div>', 1, 'Milieu accueil du site', '2017-10-05 14:53:15', '2017-10-24 15:09:59', 'home_middle', 'fr', 1);

-- categories
UPDATE peel_categories SET nom_fr = 'Vêtements', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut pretium ultricies pharetra. Etiam eu nisi erat. Fusce quis finibus nibh, non ultrices dolor. Donec a auctor augue, sit amet tempor nulla. Phasellus sapien ante, molestie sed dapibus vitae, rhoncus quis enim. Suspendisse sit amet metus non enim blandit dignissim nec a lectus.&nbsp;</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-vetements-7rz2dn.jpg', image_header_fr = 'tetiere-cngzaq.png', alpha_fr = 'V', sentence_displayed_on_product_fr = '' WHERE id = 2;
UPDATE peel_categories SET nom_fr = 'Alimentation', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-alimentation-xpezkv.jpg', image_header_fr = 'categorie-alimentation-3mcsqc.png', alpha_fr = 'A', sentence_displayed_on_product_fr = '' WHERE id = 3;
UPDATE peel_categories SET nom_fr = 'Meubles', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-meubles-fqtdhk.jpg', image_header_fr = 'categorie-meubles-gyyx4j.png', alpha_fr = 'M', sentence_displayed_on_product_fr = '' WHERE id = 4;
UPDATE peel_categories SET nom_fr = 'Parfums', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-parfum-2v7u3j.jpg', image_header_fr = 'categorie-parfums-xfxnej.png', alpha_fr = 'P', sentence_displayed_on_product_fr = '' WHERE id = 5;
UPDATE peel_categories SET nom_fr = 'Accessoires', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-accessoires-f5tsst.jpg', image_header_fr = 'categorie-accessoires-sxekzk.png', alpha_fr = 'A', sentence_displayed_on_product_fr = '' WHERE id = 6;
UPDATE peel_categories SET nom_fr = 'Fauteuils', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-fauteuils-rjcghq.jpg', image_header_fr = 'categorie-fauteuils-acv6jc.png', alpha_fr = 'F', sentence_displayed_on_product_fr = '' WHERE id = 7;
UPDATE peel_categories SET nom_fr = 'Homme', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-homme-khbjdn.jpg', image_header_fr = 'categorie-hommes-numctr.png', alpha_fr = 'H', sentence_displayed_on_product_fr = '' WHERE id = 8;
UPDATE peel_categories SET nom_fr = 'Femme', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-femme-bk7cwe.jpg', image_header_fr = 'categorie-femmes-63ksqk.png', alpha_fr = 'F', sentence_displayed_on_product_fr = '' WHERE id = 9;
UPDATE peel_categories SET nom_fr = 'Sandwiches', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-sandwiches-dr3ses.jpg', image_header_fr = 'categorie-sandwiches-dcc5fw.png', alpha_fr = 'S', sentence_displayed_on_product_fr = '' WHERE id = 10;
UPDATE peel_categories SET nom_fr = 'Vaisselle', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-vaisselle-sm7jjj.jpg', image_header_fr = 'categorie-vaisselle-5znvjz.png', alpha_fr = 'V', sentence_displayed_on_product_fr = '' WHERE id = 11;
UPDATE peel_categories SET nom_fr = 'Bijoux', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-bijoux-fbeazw.jpg', image_header_fr = 'categorie-bijoux-yuf94g.png', alpha_fr = 'B', sentence_displayed_on_product_fr = '' WHERE id = 12;
UPDATE peel_categories SET nom_fr = 'Objets connectés', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-objets-connectes-dwgt6b.jpg', image_header_fr = 'categorie-objets-connectes-whrhxf.png', alpha_fr = 'O', sentence_displayed_on_product_fr = '' WHERE id = 13;
UPDATE peel_categories SET nom_fr = 'Enfants', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-enfant-dkg58t.jpg', image_header_fr = 'categorie-enfants-wzbv6n.png', alpha_fr = 'E', sentence_displayed_on_product_fr = '' WHERE id = 14;
UPDATE peel_categories SET nom_fr = 'Chaussures', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = 'categorie-chaussures-bfspyu.jpg', image_header_fr = 'categorie-chaussures-fm8hp6.png', alpha_fr = 'C', sentence_displayed_on_product_fr = '' WHERE id = 15;

-- produits
UPDATE peel_produits SET nom_fr = 'Fauteuil Modèle Baboo', descriptif_fr = 'Un fauteuil au design épuré ! On n''a pas fini d''en parler.', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 10;
UPDATE peel_produits SET nom_fr = 'Soutien gorge Push-up', descriptif_fr = 'Chic et confortable Existe en ensemble', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', tab2_html_fr = '<p>Curabitur pellentesque id diam ut aliquam. Curabitur cursus diam velit, a molestie diam rhoncus at. Nulla ornare ultricies lacus, vel lacinia mauris hendrerit a. Vivamus ullamcorper lectus metus, id aliquam sem fringilla sed. Suspendisse pretium scelerisque volutpat. Phasellus a magna ut sapien blandit feugiat malesuada dignissim magna. Aliquam vitae nisi ut mauris sollicitudin mollis. Praesent laoreet orci vitae velit pharetra, sodales vehicula leo maximus.Curabitur venenatis erat eget est volutpat dignissim. Suspendisse id euismod ante. Duis pharetra augue at efficitur vehicula. Curabitur at lectus eu eros auctor fermentum. Curabitur facilisis odio ac est pulvinar, et ultrices tortor feugiat. Nulla tempus ante sit amet vehicula posuere. Morbi accumsan urna tristique nibh rhoncus, eu porttitor tortor ornare. Suspendisse libero nulla, accumsan in lacinia at, facilisis quis libero. ', tab3_html_fr = 'Curabitur venenatis erat eget est volutpat dignissim. Suspendisse id euismod ante. Duis pharetra augue at efficitur vehicula. Curabitur at lectus eu eros auctor fermentum. Curabitur facilisis odio ac est pulvinar, et ultrices tortor feugiat.', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = 'Conseils d''utilisation', tab2_title_fr = 'Conseils d''entretien', tab3_title_fr = 'Disponibilité', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 11;
UPDATE peel_produits SET nom_fr = 'Verres à cocktails Lot de 4', descriptif_fr = 'Pour toutes les occasions', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 12;
UPDATE peel_produits SET nom_fr = 'Bague perle', descriptif_fr = 'Un style plein de charme', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 13;
UPDATE peel_produits SET nom_fr = 'Parfum', descriptif_fr = 'Eau de Parfum', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 14;
UPDATE peel_produits SET nom_fr = 'Montre connectée', descriptif_fr = 'Toujours à l''heure Toujours en forme', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 15;
UPDATE peel_produits SET nom_fr = 'Maillot de bain 1 pièce', descriptif_fr = 'Bohème chic !', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 16;
UPDATE peel_produits SET nom_fr = 'Sandwich focaccia Tomate roquette', descriptif_fr = 'Délicieux et léger ! Le sandwich de l''été', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '<p>Ipsum Lorem</p>', tab2_html_fr = '<p>Test 2</p>', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = 'Lorem Ipsum', tab2_title_fr = 'Test 2', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 17;
UPDATE peel_produits SET nom_fr = 'T-shirt', descriptif_fr = 'Sobre et élégant !', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 18;
UPDATE peel_produits SET nom_fr = 'Casque filaire', descriptif_fr = 'Gardez le rythme Compact et design', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 19;
UPDATE peel_produits SET nom_fr = 'Chemise à carreaux', descriptif_fr = 'Pour les petits bûcherons', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 20;
UPDATE peel_produits SET nom_fr = 'Machine à café Nespresso', descriptif_fr = 'What else ?', description_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec feugiat viverra placerat. Ut vulputate at purus in viverra. Phasellus vitae tristique dui. Sed condimentum fermentum diam, in vehicula felis condimentum et. Nunc mollis non nisl at euismod. Sed ullamcorper ipsum nec lectus lacinia, sit amet bibendum mauris viverra. Praesent eros augue, maximus et nisl sit amet, dictum sollicitudin quam. Morbi sagittis, velit efficitur accumsan interdum, ex neque condimentum lorem, vitae iaculis nisl ipsum a nisi. In sit amet semper ipsum. Duis facilisis felis vitae lectus pretium tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean tincidunt aliquet purus at tristique. Cras sed finibus urna. Nulla lacinia tempus dui, eget tincidunt libero.</p><p>Sed sit amet arcu mollis, ullamcorper sapien ut, rhoncus tortor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque eget sem id massa volutpat aliquam. Pellentesque ac ullamcorper ante. Duis elementum, est quis semper posuere, mi tellus placerat mauris, in ultricies erat dui a nibh. Quisque in nisl urna. Aliquam vel tellus rhoncus, aliquam tellus ut, aliquet risus.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '<p>&nbsp;</p><p>Lorem ipsum dolor sit amet</p><p>&nbsp;</p><p>Nulla vitae justo tempus, sodales dui eu, sagittis lorem. Morbi volutpat eget erat vitae finibus.</p><p>&nbsp;</p><p>Lorem ipsum dolor sit amet</p><p>&nbsp;</p><p>Nulla vitae justo tempus, sodales dui eu, sagittis lorem. Morbi volutpat eget erat vitae finibus.</p><p>&nbsp;</p><p>Lorem ipsum dolor sit amet</p> <p>&nbsp;</p> <p>Nulla vitae justo tempus, sodales dui eu, sagittis lorem. Morbi volutpat eget erat vitae finibus.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet</p> <p>&nbsp;</p> <p>Nulla vitae justo tempus, sodales dui eu, sagittis lorem. Morbi volutpat eget erat vitae finibus.</p> <p>&nbsp;</p> <p>Lorem ipsum dolor sit amet</p> <p>&nbsp;</p> <p>Nulla vitae justo tempus, sodales dui eu, sagittis lorem. Morbi volutpat eget erat vitae finibus.</p> <p>&nbsp;</p> ', tab2_html_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam pharetra justo ut euismod laoreet. Duis sit amet mi nec est rhoncus gravida vitae at risus.</p> <ul> <li>Ut sed lorem consequat elit interdum convallis.</li> <li>Phasellus quis velit suscipit augue cursus vehicula.</li> <li>Suspendisse at ipsum ut diam tincidunt semper sit amet id leo.</li> <li>Donec posuere mi non velit tincidunt, quis rutrum elit rutrum.</li> <li>Integer a ligula quis nibh dictum convallis et nec lorem.</li> </ul> ', tab3_html_fr = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam pharetra justo ut euismod laoreet. Duis sit amet mi nec est rhoncus gravida vitae at risus. Vestibulum dapibus ligula ante, at convallis quam tincidunt id. Aenean pharetra lobortis ultrices. Nam id tortor eu dui semper gravida. Nam vitae consequat justo, et aliquam libero. Maecenas non mi vel sapien commodo tristique id sed odio. Morbi finibus non augue sit amet varius. Fusce quam ante, tincidunt et felis non, tincidunt elementum lacus. Nam sollicitudin lectus in elit vehicula mattis. Morbi laoreet sem non vehicula placerat. Nulla facilisi. Proin sapien tortor, pulvinar eget porttitor at, pellentesque sed ipsum. Phasellus ac ex vitae purus elementum venenatis.</p> ', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = 'caractéristiques', tab2_title_fr = 'Conseils d''entretien', tab3_title_fr = 'Conditions de garantie', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 21; 
UPDATE peel_produits SET nom_fr = 'Ensemble Chemise et veste', descriptif_fr = 'Casual & Chic', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 27;
UPDATE peel_produits SET nom_fr = 'Maillot rayé Bleu et blanc', descriptif_fr = 'En avant matelot !', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 28;
UPDATE peel_produits SET nom_fr = 'T-shirt USA', descriptif_fr = 'Un classique indémodable', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 29;
UPDATE peel_produits SET nom_fr = 'Bottines souples Couleur chair', descriptif_fr = 'Casual chic', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 30;

-- rubriques
UPDATE peel_rubriques SET nom_fr = 'Conditions de livraison', description_fr = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div> ', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '' WHERE id = 5; 

-- information légale
UPDATE `peel_legal` SET `titre_fr`= 'Informations légales', `texte_fr` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;

-- conditions de vente
UPDATE `peel_cgv` SET `titre_fr`= 'Conditions de vente', `texte_fr` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;

-- couleurs
UPDATE `peel_couleurs` SET `nom_fr` = 'Rouge' WHERE id = 1;
UPDATE `peel_couleurs` SET `nom_fr` = 'Noir' WHERE id = 2;
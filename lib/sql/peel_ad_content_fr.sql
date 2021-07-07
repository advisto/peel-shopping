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
-- Configuration

-- catégories
UPDATE peel_categories SET nom_fr = 'Annonces Gold', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = '', image_header_fr = '', alpha_fr = 'A', sentence_displayed_on_product_fr = '' WHERE id = 1;
UPDATE peel_categories SET nom_fr = 'Forfait', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', header_html_fr = '', image_fr = '', image_header_fr = '', alpha_fr = 'F', sentence_displayed_on_product_fr = '' WHERE id = 16;

-- produits
UPDATE peel_produits SET nom_fr = 'Annonce Gold 1 mois / 1 catégorie', descriptif_fr = '<span style="font-family: Arial;font-size:12px;"><b>Passer une annonce GOLD pour 1 mois sur [SITE_NAME] vous apporte une plus grande visibilité.</b><br /><br /> Les annonces GOLD bénéficient des avantages suivants :<br /><br />  - Publication toujours au dessus des annonces gratuites dans la ou les catégories de votre choix comme dans les recherches par mots-clefs <br /><br /> - Aspect visuel valorisant de votre annonce Gold<br /><br /> - Nombre illimité de modifications pour nos membres VERIFIED (nombre limité de modifications le cas échéant)<br /><br /> - Possibilité pour l'utilisateur de vous contacter directement depuis l'aperçu de l'annonce Gold<br /><br /> - Résumé de votre annonce depuis l'aperçu<br /><br /> - Possibilité d'intégrer des animations flash dans votre annonce<br /><br /> - Zoom sur la photo de votre article en passant le curseur de la souris sur l'image', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 1;
UPDATE peel_produits SET nom_fr = 'Annonce Gold 1 mois / 2 catégories', descriptif_fr = '<span style="font-family: Arial;font-size:12px;"><b>Passer une annonce GOLD pour 1 mois sur [SITE_NAME] vous apporte une plus grande visibilité.</b><br /><br /> Les annonces GOLD bénéficient des avantages suivants :<br /><br />  - Publication toujours au dessus des annonces gratuites dans la ou les catégories de votre choix comme dans les recherches par mots-clefs <br /><br /> - Aspect visuel valorisant de votre annonce Gold<br /><br /> - Nombre illimité de modifications pour nos membres VERIFIED (nombre limité de modifications le cas échéant)<br /><br /> - Possibilité pour l'utilisateur de vous contacter directement depuis l'aperçu de l'annonce Gold<br /><br /> - Résumé de votre annonce depuis l'aperçu<br /><br /> - Possibilité d'intégrer des animations flash dans votre annonce<br /><br /> - Zoom sur la photo de votre article en passant le curseur de la souris sur l'image', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 2;
UPDATE peel_produits SET nom_fr = 'Annonce Gold 1 mois / 3 catégories', descriptif_fr = '<span style="font-family: Arial;font-size:12px;"><b>Passer une annonce GOLD pour 1 mois sur [SITE_NAME] vous apporte une plus grande visibilité.</b><br /><br /> Les annonces GOLD bénéficient des avantages suivants :<br /><br />  - Publication toujours au dessus des annonces gratuites dans la ou les catégories de votre choix comme dans les recherches par mots-clefs <br /><br /> - Aspect visuel valorisant de votre annonce Gold<br /><br /> - Nombre illimité de modifications pour nos membres VERIFIED (nombre limité de modifications le cas échéant)<br /><br /> - Possibilité pour l'utilisateur de vous contacter directement depuis l'aperçu de l'annonce Gold<br /><br /> - Résumé de votre annonce depuis l'aperçu<br /><br /> - Possibilité d'intégrer des animations flash dans votre annonce<br /><br /> - Zoom sur la photo de votre article en passant le curseur de la souris sur l'image', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 3;
UPDATE peel_produits SET nom_fr = 'Annonce Gold 3 mois / 1 catégorie', descriptif_fr = '<span style="font-family: Arial;font-size:12px;"><b>Passer une annonce GOLD pour 3 mois sur [SITE_NAME] vous apporte une plus grande visibilité.</b><br /><br /> Les annonces GOLD bénéficient des avantages suivants :<br /><br />  - Publication toujours au dessus des annonces gratuites dans la ou les catégories de votre choix comme dans les recherches par mots-clefs <br /><br /> - Aspect visuel valorisant de votre annonce Gold<br /><br /> - Nombre illimité de modifications pour nos membres VERIFIED (nombre limité de modifications le cas échéant)<br /><br /> - Possibilité pour l'utilisateur de vous contacter directement depuis l'aperçu de l'annonce Gold<br /><br /> - Résumé de votre annonce depuis l'aperçu<br /><br /> - Possibilité d'intégrer des animations flash dans votre annonce<br /><br /> - Zoom sur la photo de votre article en passant le curseur de la souris sur l'image', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 4;
UPDATE peel_produits SET nom_fr = 'Annonce Gold 3 mois / 2 catégories', descriptif_fr = '<span style="font-family: Arial;font-size:12px;"><b>Passer une annonce GOLD pour 3 mois sur [SITE_NAME] vous apporte une plus grande visibilité.</b><br /><br /> Les annonces GOLD bénéficient des avantages suivants :<br /><br />  - Publication toujours au dessus des annonces gratuites dans la ou les catégories de votre choix comme dans les recherches par mots-clefs <br /><br /> - Aspect visuel valorisant de votre annonce Gold<br /><br /> - Nombre illimité de modifications pour nos membres VERIFIED (nombre limité de modifications le cas échéant)<br /><br /> - Possibilité pour l'utilisateur de vous contacter directement depuis l'aperçu de l'annonce Gold<br /><br /> - Résumé de votre annonce depuis l'aperçu<br /><br /> - Possibilité d'intégrer des animations flash dans votre annonce<br /><br /> - Zoom sur la photo de votre article en passant le curseur de la souris sur l'image', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 5;
UPDATE peel_produits SET nom_fr = 'Annonce Gold 3 mois / 3 catégories', descriptif_fr = '<span style="font-family: Arial;font-size:12px;"><b>Passer une annonce GOLD pour 3 mois sur [SITE_NAME] vous apporte une plus grande visibilité.</b><br /><br /> Les annonces GOLD bénéficient des avantages suivants :<br /><br />  - Publication toujours au dessus des annonces gratuites dans la ou les catégories de votre choix comme dans les recherches par mots-clefs <br /><br /> - Aspect visuel valorisant de votre annonce Gold<br /><br /> - Nombre illimité de modifications pour nos membres VERIFIED (nombre limité de modifications le cas échéant)<br /><br /> - Possibilité pour l'utilisateur de vous contacter directement depuis l'aperçu de l'annonce Gold<br /><br /> - Résumé de votre annonce depuis l'aperçu<br /><br /> - Possibilité d'intégrer des animations flash dans votre annonce<br /><br /> - Zoom sur la photo de votre article en passant le curseur de la souris sur l'image', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 6;
UPDATE peel_produits SET nom_fr = 'Annonce Gold 12 mois / 1 catégorie', descriptif_fr = '<span style="font-family: Arial;font-size:12px;"><b>Passer une annonce GOLD pour 12 mois sur [SITE_NAME] vous apporte une plus grande visibilité.</b><br /><br /> Les annonces GOLD bénéficient des avantages suivants :<br /><br />  - Publication toujours au dessus des annonces gratuites dans la ou les catégories de votre choix comme dans les recherches par mots-clefs <br /><br /> - Aspect visuel valorisant de votre annonce Gold<br /><br /> - Nombre illimité de modifications pour nos membres VERIFIED (nombre limité de modifications le cas échéant)<br /><br /> - Possibilité pour l'utilisateur de vous contacter directement depuis l'aperçu de l'annonce Gold<br /><br /> - Résumé de votre annonce depuis l'aperçu<br /><br /> - Possibilité d'intégrer des animations flash dans votre annonce<br /><br /> - Zoom sur la photo de votre article en passant le curseur de la souris sur l'image', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 7;
UPDATE peel_produits SET nom_fr = 'Annonce Gold 12 mois / 2 catégories', descriptif_fr = '<span style="font-family: Arial;font-size:12px;"><b>Passer une annonce GOLD pour 12 mois sur [SITE_NAME] vous apporte une plus grande visibilité.</b><br /><br /> Les annonces GOLD bénéficient des avantages suivants :<br /><br />  - Publication toujours au dessus des annonces gratuites dans la ou les catégories de votre choix comme dans les recherches par mots-clefs <br /><br /> - Aspect visuel valorisant de votre annonce Gold<br /><br /> - Nombre illimité de modifications pour nos membres VERIFIED (nombre limité de modifications le cas échéant)<br /><br /> - Possibilité pour l'utilisateur de vous contacter directement depuis l'aperçu de l'annonce Gold<br /><br /> - Résumé de votre annonce depuis l'aperçu<br /><br /> - Possibilité d'intégrer des animations flash dans votre annonce<br /><br /> - Zoom sur la photo de votre article en passant le curseur de la souris sur l'image', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 8;
UPDATE peel_produits SET nom_fr = 'Annonce Gold 12 mois / 3 catégories', descriptif_fr = '<span style="font-family: Arial;font-size:12px;"><b>Passer une annonce GOLD pour 12 mois sur [SITE_NAME] vous apporte une plus grande visibilité.</b><br /><br /> Les annonces GOLD bénéficient des avantages suivants :<br /><br />  - Publication toujours au dessus des annonces gratuites dans la ou les catégories de votre choix comme dans les recherches par mots-clefs <br /><br /> - Aspect visuel valorisant de votre annonce Gold<br /><br /> - Nombre illimité de modifications pour nos membres VERIFIED (nombre limité de modifications le cas échéant)<br /><br /> - Possibilité pour l'utilisateur de vous contacter directement depuis l'aperçu de l'annonce Gold<br /><br /> - Résumé de votre annonce depuis l'aperçu<br /><br /> - Possibilité d'intégrer des animations flash dans votre annonce<br /><br /> - Zoom sur la photo de votre article en passant le curseur de la souris sur l'image', description_fr = '', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '', tab1_html_fr = '', tab2_html_fr = '', tab3_html_fr = '', tab4_html_fr = '', tab5_html_fr = '', tab6_html_fr = '', tab1_title_fr = '', tab2_title_fr = '', tab3_title_fr = '', tab4_title_fr = '', tab5_title_fr = '', tab6_title_fr = '' WHERE id = 9;

-- catégories d'annonces
UPDATE peel_categories_annonces SET `alpha_fr`= 'M', `nom_fr`= 'Maison & décoration', `description_fr`= '', `image_fr`= '', `meta_titre_fr`= 'Site de démo PEEL Shopping - Équipement de la maison', `meta_desc_fr`= '', `meta_key_fr`= '', `header_html_fr`= '', `presentation_fr`= '<p>Du mobilier à l''électro-ménager en passant par la décoration, retrouvez toutes les annonces relatives à l''équipement de la maison.</p>', `presentation2_fr`= '' WHERE id = 1;
UPDATE peel_categories_annonces SET `alpha_fr`= 'M', `nom_fr`= 'Mode', `description_fr`= '', `image_fr`= '', `meta_titre_fr`= '', `meta_desc_fr`= '', `meta_key_fr`= '', `header_html_fr`= '', `presentation_fr`= '', `presentation2_fr`= '' WHERE id = 2;
UPDATE peel_categories_annonces SET `alpha_fr`= 'I', `nom_fr`= 'Immobilier', `description_fr`= '', `image_fr`= '', `meta_titre_fr`= '', `meta_desc_fr`= '', `meta_key_fr`= '', `header_html_fr`= '', `presentation_fr`= '', `presentation2_fr`= '' WHERE id = 3;
UPDATE peel_categories_annonces SET `alpha_fr`= 'L', `nom_fr`= 'Loisirs', `description_fr`= '', `image_fr`= '', `meta_titre_fr`= '', `meta_desc_fr`= '', `meta_key_fr`= '', `header_html_fr`= '', `presentation_fr`= '', `presentation2_fr`= '' WHERE id = 4;
UPDATE peel_categories_annonces SET `alpha_fr`= 'L', `nom_fr`= 'Recherche', `description_fr`= '', `image_fr`= '', `meta_titre_fr`= '', `meta_desc_fr`= '', `meta_key_fr`= '', `header_html_fr`= '', `presentation_fr`= '', `presentation2_fr`= '' WHERE id = 5;

-- annonces
UPDATE peel_lot_vente SET `titre_fr`='A louer : Appartement 3 pièces 80 m²', `description_fr`='<p>3 pièces traversant 80 m² Loi Carrez</p> <p>3ème étage, ascenceur, 2 balcons</p> <p>Faibles charges&#160;</p>' WHERE `ref`= 1;
UPDATE peel_lot_vente SET `titre_fr`='Montre connectée', `description_fr`='<p>Recherche montre connectée Android 4.0, bluetooth, wifi</p>' WHERE `ref`= 2; 
UPDATE peel_lot_vente SET `titre_fr`='Fauteuil pop vintage jaune', `description_fr`='<p>Fauteuil d''inspiration fifties, coque mousse polyuréthane, tissu 100% synthétique, déhoussable.</p> <p>Commande minimum 4 pièces, contactez-nous.</p> <p><strong>Visite sur place uniquement sur rendez-vous</strong></p>' WHERE `ref`= 3; 
UPDATE peel_lot_vente SET `titre_fr`='Tshirts basiques', `description_fr`='<p>Suite à erreur de taille, vends 2 tshirts basiques taille L jamais portés</p> <p>A venir chercher sur place. Compter 2,50 € de plus pour les frais de port.</p>' WHERE `ref`= 4; 
UPDATE peel_lot_vente SET `titre_fr`='Traiteur organisateur soirées cocktail', `description_fr`='<p>Confiez l''organisation de vos soirées, séminaires et réceptions&#160;à un spécialiste.&#160;</p> <p>Des menus sur mesure pour toutes vos occasions, que vous organisiez un déjeuner d''affaires,&#160;une soirée clients, ou un mariage !</p> <p>Nous vous proposons également des services à la carte, tels que : location de tables, chaises et couverts, décoration, personnel qualifié pour un service raffiné.</p> <p>Contactez-nous dès maintenant au 01 75 00 00 00 ou sur http://www.___.com</p>' WHERE `ref`= 5; 
UPDATE peel_lot_vente SET `titre_fr`='Bague argent 925 et perle', `description_fr`='<p>Perle véritable, certificat d''authenticité, Taille 54</p>' WHERE `ref`= 6; 
UPDATE peel_lot_vente SET `titre_fr`='Déstockage soutien-gorge Vavavoum', `description_fr`='<p>Marque&#160;Vavavoum<br /> Couleur : bleu nuit</p> <p>Du 80 au 105<br /> Bonnet A à C à armatures<br /> Bretelles amovibles</p>' WHERE `ref` = 7; 
UPDATE peel_lot_vente SET `titre_fr`='Casque neuf', `description_fr`='<p>Recherche casque audio. Neuf, jamais servi.</p> <p>Remise sur rendez-vous, envoi possible</p>' WHERE `ref`= 8;

-- zone HTML personnalisable
UPDATE `peel_html` SET `etat` = '0' WHERE (`emplacement` = 'home' OR `emplacement` = 'home_middle_top' OR `emplacement` = 'header_login') AND site_id="[SITE_ID]" AND lang='fr';
INSERT INTO `peel_html` (`id`, `contenu_html`, `etat`, `titre`, `o_timestamp`, `a_timestamp`, `emplacement`, `lang`, `site_id`) VALUES
(NULL, '<div class="ads_home_information_container">\r\n	<div class="col-md-6 ads_home_information_left_block">\r\n		<div>\r\n			<p class="ads_home_information_left_block_title">Vendeurs</p>\r\n			<div class="home_infos_center">\r\n				<div class="col-xs-3 ads_home_information_left_block_logo"></div>\r\n				<div class="col-xs-9 ads_home_information_left_block_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>\r\n			</div>\r\n			<p class="center"><a href="[WWWROOT]/modules/annonces/creation_annonce.php" class="btn ads_personnalized_button_create_ads">Passer une annonce</a></p>\r\n		</div>\r\n	</div>\r\n	<div class="col-md-6 ads_home_information_right_block">\r\n		<div>\r\n			<p class="ads_home_information_right_block_title">Acheteurs</p>\r\n			<div class="home_infos_center">\r\n				<div class="col-xs-3 ads_home_information_right_block_logo"></div>\r\n				<div class="col-xs-9 ads_home_information_right_block_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>\r\n			</div>\r\n			<p class="center"><a href="[WWWROOT]/modules/annonces/creation_annonce.php" class="btn ads_personnalized_button_show_ads">Consulter les annonces</a></p>\r\n		</div>\r\n	</div>\r\n</div>', 1, 'Contenu d\'accueil de la boutique zone middle top', '2010-11-01 12:00:00', '2017-10-25 11:44:44', 'home_middle_top', 'fr', "[SITE_ID]"),
(NULL, '<div>\n<div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 header_search_block">[FUNCTION=affiche_menu_recherche,true,header]</div>\n</div>', 1, 'Contenu header login', '2017-10-05 14:53:15', '2017-10-18 11:24:27', 'header_login', 'fr', "[SITE_ID]"),
(NULL, '<div class="col-md-3 hidden-xs hidden-sm home-lg-left right">[MODULES_LEFT]</div>\n<div class="col-md-9">\n<div class="center"><img class="ads_home_welcome_zone_image" src="[WWWROOT]/upload/annonce_image_zone_accueil.jpg" alt="" width="763" height="350" /></div>\n<div class="center">\n<p style="font-size: 28px;"><span style="font-weight: bold;">Bienvenue</span> sur votre nouveau site !</p>\n<p style="font-size: 16px;">Votre nouveau site PEEL Shopping est entièrement administrable par vos soins.<br />\nVous pouvez remplir cette zone selon vos préférences ou encore choisir de ne pas l’afficher.</p>\n<a href="[WWWROOT]/administrer/html.php" class="btn btn-primary btn_personnalized_two_lines"> 		<span class="glyphicon glyphicon-pencil"> 		</span> 		<span style="font-weight: bold;">Administrer le contenu</span><br />\n<span class="btn_personnalized_second_line">de cette zone</span> 	</a></div>\n</div>\n<div class="clearfix"></div>', 1, 'Contenu d\'accueil du site', '2017-07-07 16:09:18', '2017-10-25 11:55:15', 'home', 'fr', "[SITE_ID]");

-- information légale
UPDATE `peel_legal` SET `titre_fr`= 'Informations légales', `texte_fr` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;

-- conditions de vente
UPDATE `peel_cgv` SET `titre_fr`= 'Conditions de vente', `texte_fr` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;

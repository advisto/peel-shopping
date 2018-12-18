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
UPDATE peel_categories SET nom_es = '', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', header_html_es = '', image_es = '', image_header_es = '', alpha_es = '', sentence_displayed_on_product_es = '' WHERE id = 1;
UPDATE peel_categories SET nom_es = 'Tarifa plana', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', header_html_es = '', image_es = '', image_header_es = '', alpha_es = '', sentence_displayed_on_product_es = '' WHERE id = 16;

-- produits
UPDATE peel_produits SET nom_es = '', descriptif_es = '', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', tab1_html_es = '', tab2_html_es = '', tab3_html_es = '', tab4_html_es = '', tab5_html_es = '', tab6_html_es = '', tab1_title_es = '', tab2_title_es = '', tab3_title_es = '', tab4_title_es = '', tab5_title_es = '', tab6_title_es = '' WHERE id = 1;
UPDATE peel_produits SET nom_es = '', descriptif_es = '', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', tab1_html_es = '', tab2_html_es = '', tab3_html_es = '', tab4_html_es = '', tab5_html_es = '', tab6_html_es = '', tab1_title_es = '', tab2_title_es = '', tab3_title_es = '', tab4_title_es = '', tab5_title_es = '', tab6_title_es = '' WHERE id = 2;
UPDATE peel_produits SET nom_es = '', descriptif_es = '', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', tab1_html_es = '', tab2_html_es = '', tab3_html_es = '', tab4_html_es = '', tab5_html_es = '', tab6_html_es = '', tab1_title_es = '', tab2_title_es = '', tab3_title_es = '', tab4_title_es = '', tab5_title_es = '', tab6_title_es = '' WHERE id = 3;
UPDATE peel_produits SET nom_es = '', descriptif_es = '', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', tab1_html_es = '', tab2_html_es = '', tab3_html_es = '', tab4_html_es = '', tab5_html_es = '', tab6_html_es = '', tab1_title_es = '', tab2_title_es = '', tab3_title_es = '', tab4_title_es = '', tab5_title_es = '', tab6_title_es = '' WHERE id = 4;
UPDATE peel_produits SET nom_es = '', descriptif_es = '', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', tab1_html_es = '', tab2_html_es = '', tab3_html_es = '', tab4_html_es = '', tab5_html_es = '', tab6_html_es = '', tab1_title_es = '', tab2_title_es = '', tab3_title_es = '', tab4_title_es = '', tab5_title_es = '', tab6_title_es = '' WHERE id = 5;
UPDATE peel_produits SET nom_es = '', descriptif_es = '', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', tab1_html_es = '', tab2_html_es = '', tab3_html_es = '', tab4_html_es = '', tab5_html_es = '', tab6_html_es = '', tab1_title_es = '', tab2_title_es = '', tab3_title_es = '', tab4_title_es = '', tab5_title_es = '', tab6_title_es = '' WHERE id = 6;
UPDATE peel_produits SET nom_es = '', descriptif_es = '', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', tab1_html_es = '', tab2_html_es = '', tab3_html_es = '', tab4_html_es = '', tab5_html_es = '', tab6_html_es = '', tab1_title_es = '', tab2_title_es = '', tab3_title_es = '', tab4_title_es = '', tab5_title_es = '', tab6_title_es = '' WHERE id = 7;
UPDATE peel_produits SET nom_es = '', descriptif_es = '', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', tab1_html_es = '', tab2_html_es = '', tab3_html_es = '', tab4_html_es = '', tab5_html_es = '', tab6_html_es = '', tab1_title_es = '', tab2_title_es = '', tab3_title_es = '', tab4_title_es = '', tab5_title_es = '', tab6_title_es = '' WHERE id = 8;
UPDATE peel_produits SET nom_es = '', descriptif_es = '', description_es = '', meta_titre_es = '', meta_key_es = '', meta_desc_es = '', tab1_html_es = '', tab2_html_es = '', tab3_html_es = '', tab4_html_es = '', tab5_html_es = '', tab6_html_es = '', tab1_title_es = '', tab2_title_es = '', tab3_title_es = '', tab4_title_es = '', tab5_title_es = '', tab6_title_es = '' WHERE id = 9;

-- catégories d'annonces
UPDATE peel_categories_annonces SET `alpha_es`= 'D', `nom_es`= 'decoración y electrodomésticos', `description_es`= '', `image_es`= '', `meta_titre_es`= '', `meta_desc_es`= '', `meta_key_es`= '', `header_html_es`= '', `presentation_es`= '<p>Desde muebles a electrodomésticos y decoración, encuentre todos los anuncios relacionados con el equipamiento de la casa.</p>', `presentation2_es`= '' WHERE id = 1;
UPDATE peel_categories_annonces SET `alpha_es`= 'M', `nom_es`= 'Moda', `description_es`= '', `image_es`= '', `meta_titre_es`= '', `meta_desc_es`= '', `meta_key_es`= '', `header_html_es`= '', `presentation_es`= '', `presentation2_es`= '' WHERE id = 2;
UPDATE peel_categories_annonces SET `alpha_es`= 'I', `nom_es`= 'Inmueble', `description_es`= '', `image_es`= '', `meta_titre_es`= '', `meta_desc_es`= '', `meta_key_es`= '', `header_html_es`= '', `presentation_es`= '', `presentation2_es`= '' WHERE id = 3;
UPDATE peel_categories_annonces SET `alpha_es`= 'O', `nom_es`= 'Ocios', `description_es`= '', `image_es`= '', `meta_titre_es`= '', `meta_desc_es`= '', `meta_key_es`= '', `header_html_es`= '', `presentation_es`= '', `presentation2_es`= '' WHERE id = 4;
UPDATE peel_categories_annonces SET `alpha_es`= 'O', `nom_es`= 'Búsqueda', `description_es`= '', `image_es`= '', `meta_titre_es`= '', `meta_desc_es`= '', `meta_key_es`= '', `header_html_es`= '', `presentation_es`= '', `presentation2_es`= '' WHERE id = 5;

-- annonces
UPDATE peel_lot_vente SET `titre_es`='En alquiler: Apartamento 3 habitaciones 80 m²', `description_es`='<p>3 piezas atravesando 80 m² Loi Carrez</p> <p>3er piso, ascensor, 2 balcones</p> <p>Cargas bajas</p>' WHERE `ref` = 1; UPDATE peel_lot_vente SET `titre_es`='Reloj conectado', `description_es`='<p>Android 4.0, Bluetooth, Wifi</p> <p>Buen estado.</p>' WHERE `ref` = 2; 
UPDATE peel_lot_vente SET `titre_es`='Sillón amarillo', `description_es`='<p>Sillón vintage, carcasa de espuma de poliuretano, tejido 100% sintético, funda extraíble.</p> <p>Pedido mínimo 4 piezas, contáctenos.</p> <p><strong>Visita in situ solo con cita previa.</strong></p>' WHERE `ref` = 3; 
UPDATE peel_lot_vente SET `titre_es`='Camisetas básicas', `description_es`='<p>Debido al error de tamaño, venda 2 camisetas básicas tamaño L nunca usadas</p> <p>Para ser removido en el sitio. Cuesta 2,50 € más por los gastos de envío.</p>' WHERE `ref` = 4; 
UPDATE peel_lot_vente SET `titre_es`='', `description_es`='' WHERE `ref` = 5; 
UPDATE peel_lot_vente SET `titre_es`='Anillo de plata y perla ', `description_es`='<p>Perla genuina, certificado de autenticidad, tamaño 54</p>' WHERE `ref` = 6; 
UPDATE peel_lot_vente SET `titre_es`='liquidación de sostenes', `description_es`='<p>marca Vavavoum<br /> Color: azul oscuro</p> <p>De 80 a 105<br /> Bonete A a C con aros<br /> Tiras de hombro desmontables</p>' WHERE `ref` = 7; 
UPDATE peel_lot_vente SET `titre_es`='Nuevos auriculares', `description_es`='<p>Porque duplicado, vendo mis auriculares. Nueve, nunca sirvió.</p> <p>Entrega por cita, posible entrega</p>' WHERE `ref` = 8; 

-- zone HTML personnalisable
UPDATE `peel_html` SET `etat` = '0' WHERE (`emplacement` = 'home' OR `emplacement` = 'header_login') AND site_id="[SITE_ID]" AND lang='es';
INSERT INTO `peel_html` (`id`, `contenu_html`, `etat`, `titre`, `o_timestamp`, `a_timestamp`, `emplacement`, `lang`, `site_id`) VALUES
(NULL, '<div class="col-md-3 hidden-xs hidden-sm home-lg-left right">[MODULES_LEFT]</div>\r\n<div class="col-md-9">\r\n<div class="center"><img class="ads_home_welcome_zone_image" src="[WWWROOT]/upload/annonce_image_zone_accueil.jpg" alt="" width="763" height="350" /></div>\r\n<div class="center">\r\n<p style="font-size: 28px;"><span style="font-weight: bold;">Bienvenue</span> sur votre nouveau site !</p>\r\n<p style="font-size: 16px;">Votre nouveau site PEEL Shopping est entièrement administrable par vos soins.<br />\r\nVous pouvez remplir cette zone selon vos préférences ou encore choisir de ne pas l’afficher.</p>\r\n<a href="[WWWROOT]/administrer/html.php" class="btn btn-primary btn_personnalized_two_lines"> 		<span class="glyphicon glyphicon-pencil"> 		</span> 		<span style="font-weight: bold;">Administrer le contenu</span><br />\r\n<span class="btn_personnalized_second_line">de cette zone</span> 	</a></div>\r\n</div>\r\n<div class="clearfix"></div>', 1, 'Home site content', '2017-07-07 16:09:18', '2017-07-07 16:09:18', 'home', 'es', "[SITE_ID]"),
(NULL, '<div>\r\n<div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 header_search_block">[FUNCTION=affiche_menu_recherche,true,header]</div>\r\n</div>', 1, 'Contenu header login', '2017-10-05 14:53:15', '2017-10-18 11:24:27', 'header_login', 'es', "[SITE_ID]"),
(NULL, '<div class="ads_home_information_container">\r\n	<div class="col-md-6 ads_home_information_left_block">\r\n		<div>\r\n			<p class="ads_home_information_left_block_title">Vendedores</p>\r\n			<div class="home_infos_center">\r\n				<div class="col-xs-3 ads_home_information_left_block_logo"></div>\r\n				<div class="col-xs-9 ads_home_information_left_block_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>\r\n			</div>\r\n			<p class="center"><a href="[WWWROOT]/modules/annonces/creation_annonce.php" class="btn ads_personnalized_button_create_ads">Anunciar</a></p>\r\n		</div>\r\n	</div>\r\n	<div class="col-md-6 ads_home_information_right_block">\r\n		<div>\r\n			<p class="ads_home_information_right_block_title">Compradores</p>\r\n			<div class="home_infos_center">\r\n				<div class="col-xs-3 ads_home_information_right_block_logo"></div>\r\n				<div class="col-xs-9 ads_home_information_right_block_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>\r\n			</div>\r\n			<p class="center"><a href="[WWWROOT]/modules/annonces/creation_annonce.php" class="btn ads_personnalized_button_show_ads">Ver anuncios</a></p>\r\n		</div>\r\n	</div>\r\n</div>', 1, 'Contenu d\'accueil de la boutique zone middle top', '2010-11-01 12:00:00', '2017-10-25 11:44:44', 'home_middle_top', 'es', "[SITE_ID]");

-- information légale
UPDATE `peel_legal` SET `titre_es`= 'Información legal', `texte_es` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;

-- conditions de vente
UPDATE `peel_cgv` SET `titre_es`= 'Condiciones de venta', `texte_es` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;
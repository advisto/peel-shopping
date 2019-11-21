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
# $Id: peel_showcase_content.sql.sql 53676 2017-04-25 14:51:39Z sdelaporte $
#
-- configuration

-- zone HTML personnalisable
UPDATE `peel_html` SET `etat` = '0' WHERE (`emplacement` = 'home') AND site_id="[SITE_ID]" AND lang='fr';
INSERT INTO `peel_html` (`id`, `contenu_html`, `etat`, `titre`, `o_timestamp`, `a_timestamp`, `emplacement`, `lang`, `site_id`) VALUES
(NULL, '[CLOSE_MAIN_CONTAINER]\n<div class="showcase_home_welcome_zone_personnalized_banner"></div>\n[REOPEN_MAIN_CONTAINER]\n<div class="row text-center">\n	<div class="col-md-12">\n		<div class="showcase_home_welcome_zone_block">\n			<p style="font-size: 28px; font-weight: bold; margin-bottom: 0px;">Bienvenue sur votre nouveau site !</p>\n			<hr class="showcase_home_personnalized_hr" />\n			<p style="font-size: 16px;">Votre nouveau site PEEL Shopping est entièrement administrable par vos soins.<br />\n			Vous pouvez remplir cette zone selon vos préférences ou encore choisir de ne pas l’afficher.</p>\n			<a class="btn btn-primary btn_personnalized_two_lines" href="[WWWROOT]/administrer/html.php?mode=modif&amp;id=27">\n				<span class="glyphicon glyphicon-pencil">\n				</span>\n				<span style="font-weight: bold;">Administrer le contenu</span><br><span class="btn_personnalized_second_line">de cette zone</span>\n			</a>\n		</div>\n	</div>\n</div>\n<div class="row text-center">\n	<div class="showcase_home_contact_zone_block">\n		<div class="col-md-8">\n			<div class="showcase_home_contact_zone_img_block_left"><img src="[WWWROOT]/upload/vitrine_image_zone_contact_gauche.jpg" alt="" /></div>\n		</div>\n		<div class="col-md-4">\n			<div class="showcase_home_contact_zone_img_block_right"><img src="[WWWROOT]/upload/vitrine_image_zone_contact_droite.jpg" alt="" /></div>\n		</div>\n		<div class="col-md-12">\n			<div class="showcase_home_contact_zone_text_block">\n				<p style="font-size: 28px; font-weight: bold; margin-bottom: 0px;">Une question ?</p>\n				<hr class="showcase_home_personnalized_hr" />\n				<p style="font-size: 16px;">Notre équipe est à votre disposition</p>\n				<p style="font-size: 14px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n				<div class="btn_personnalized_contact_block"><a href="[WWWROOT]/utilisateurs/contact.php" class="btn btn_personnalized_contact"><span class="btn_personnalized_contact_container"></span><span style="font-size: 22px; font-weight: bold;">Nous contacter</span></a>\n				</div>\n			</div>\n		</div>\n	</div>\n</div>\n<div class="row text-center">\n	<div class="showcase_home_contact_zone_learn_more_block">\n		<div class="col-sm-4">\n			<div class="showcase_home_contact_zone_learn_more_single_block1" style="padding: 0px 15px;"><img src="[WWWROOT]/upload/vitrine_image_zone_learn_more_1.jpg" alt="" />\n			<div style="background-color: #eeeeee; padding: 30px 10px;">\n			<p style="font-size: 24px; font-weight: bold; color: #124e78;" class="showcase_home_contact_zone_learn_more_single_block_title">Lorem Ipsum</p>\n			<p style="font-size: 14px; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n			<a class="btn btn_personnalized_learn_more"><span>EN SAVOIR PLUS</span></a></div>\n			</div>\n		</div>\n		<div class="col-sm-4">\n			<div class="showcase_home_contact_zone_learn_more_single_block2" style="padding: 0px 15px;"><img src="[WWWROOT]/upload/vitrine_image_zone_learn_more_2.jpg" alt="" />\n				<div style="background-color: #eeeeee; padding: 30px 10px;">\n				<p style="font-size: 24px; font-weight: bold; color: #44af69;" class="showcase_home_contact_zone_learn_more_single_block_title">Lorem Ipsum</p>\n				<p style="font-size: 14px; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n				<a class="btn btn_personnalized_learn_more"><span>EN SAVOIR PLUS</span></a></div>\n			</div>\n		</div>\n		<div class="col-sm-4">\n			<div class="showcase_home_contact_zone_learn_more_single_block3" style="padding: 0px 15px;"><img src="[WWWROOT]/upload/vitrine_image_zone_learn_more_3.jpg" alt="" />\n				<div style="background-color: #eeeeee; padding: 30px 10px;">\n					<p style="font-size: 24px; font-weight: bold; color: #e33e12;" class="showcase_home_contact_zone_learn_more_single_block_title">Lorem Ipsum</p>\n					<p style="font-size: 14px; text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n					<a class="btn btn_personnalized_learn_more"><span>EN SAVOIR PLUS</span></a>\n				</div>\n			</div>\n		</div>\n	</div>\n</div>\n[CLOSE_MAIN_CONTAINER]\n<div class="showcase_home_middle_zone_personnalized_banner"></div>\n[REOPEN_MAIN_CONTAINER]\n<div class="row text-center">\n	<div class="col-md-12">\n		<div class="showcase_home_middle_zone_block">\n		<p style="font-size: 28px; font-weight: bold; color: #124e78;">Lorem ipsum dolor sit amet</p>\n		<hr class="showcase_home_personnalized_hr" />\n		<p style="font-size: 14px; color: #124e78;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\n		</div>\n	</div>\n</div>\n[CLOSE_MAIN_CONTAINER]\n<div style="background-color: #eeeeee; padding: 35px 0px;">\n	<div class="container">\n		<div class="row text-center">\n			<div class="col-md-4">\n				<div class="showcase_home_bottom_zone_single_block1"><img src="[WWWROOT]/upload/picto_showcase_1.png" alt="" />\n				<p style="font-size: 74px; color: #124e78;"><span class="counter">92</span>%</p>\n				<p style="font-size: 18px; color: #124e78;">Lorem Ipsum</p>\n				<p style="font-size: 28px; font-weight: bold; color: #124e78;">dolor sit amet</p>\n				</div>\n			</div>\n			<div class="col-md-4">\n			<div class="showcase_home_bottom_zone_single_block1"><img src="[WWWROOT]/upload/picto_showcase_2.png" alt="" />\n				<p style="font-size: 74px; color: #44af69;"><span class="counter">100</span>%</p>\n				<p style="font-size: 18px; color: #44af69;">Lorem Ipsum</p>\n				<p style="font-size: 28px; font-weight: bold; color: #44af69;">dolor sit amet</p>\n			</div>\n			</div>\n			<div class="col-md-4">\n				<div class="showcase_home_bottom_zone_single_block1"><img src="[WWWROOT]/upload/picto_showcase_3.png" alt="" />\n				<p style="font-size: 74px; color: #e33e12;"><span class="counter">24</span>h</p>\n				<p style="font-size: 18px; color: #e33e12;">Lorem Ipsum</p>\n				<p style="font-size: 28px; font-weight: bold; color: #e33e12;">dolor sit amet</p>\n				</div>\n			</div>\n		</div>\n	</div>\n</div>\n[REOPEN_MAIN_CONTAINER]', 1, 'Contenu d\'accueil du site', '2017-07-07 16:02:42', '2017-10-25 11:01:55', 'home', 'fr', "[SITE_ID]");

-- rubriques
UPDATE peel_rubriques SET nom_fr = 'Qui sommes-nous ?', description_fr = '<div class="showcase_page_who_are_we_welcome_zone_personnalized_banner"></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vel  sollicitudin velit. Nam congue dolor sed imperdiet dignissim. Aliquam  leo leo, lacinia et aliquam sed, dapibus ac dolor. Praesent sodales  mauris quis facilisis porta. Suspendisse placerat ex lorem. Aenean  scelerisque laoreet mauris venenatis consectetur. Praesent in orci  lectus. Curabitur molestie ornare enim. Vestibulum ante ipsum primis in  faucibus orci luctus et ultrices posuere cubilia Curae; In ut magna  vulputate, efficitur lacus et, malesuada dui. Cras at tortor finibus  risus porttitor dignissim eget a ipsum. Duis pulvinar lobortis maximus.</p>
<p>Interdum et malesuada fames ac ante ipsum primis in faucibus. Curabitur  nisl turpis, volutpat at accumsan aliquam, interdum et diam. Suspendisse  viverra venenatis condimentum. Phasellus cursus orci et aliquet  elementum. Vestibulum imperdiet ante ac pulvinar condimentum. Vivamus ac  augue non tortor posuere mollis. In eleifend eu libero vitae convallis.  Nulla ac orci interdum nisi feugiat tempus ac in quam. Nam non libero  sit amet enim iaculis finibus. Vivamus justo purus, accumsan in tortor  eu, luctus tincidunt orci. Nulla facilisi. Proin aliquet, sem ac  interdum lobortis, odio nunc consequat tortor, in dictum quam neque  aliquet eros. Donec eleifend porttitor mauris, id rutrum magna iaculis  nec. Suspendisse turpis odio, hendrerit eu egestas in, pellentesque  porta dui.</p>
<p>Nam eu iaculis turpis, eu luctus orci. Sed luctus, leo ut convallis  bibendum, risus risus lacinia arcu, quis pellentesque leo risus eget  justo. Curabitur hendrerit nulla id velit aliquet, vitae porttitor purus  efficitur. Fusce sed euismod felis. Phasellus vehicula justo eget  tempor maximus. Vestibulum ante ipsum primis in faucibus orci luctus et  ultrices posuere cubilia Curae; Etiam vehicula urna id augue rhoncus,  nec rutrum nisi condimentum. Aliquam sit amet arcu dictum, auctor ligula  a, molestie leo. Donec pellentesque nec nisi vitae semper. Donec eget  ullamcorper nisi. Donec sem ex, tempus quis nulla quis, aliquet  vestibulum massa. Phasellus rutrum ante vitae varius consectetur. Sed et  imperdiet ipsum, et consequat mi. In non consectetur neque, id  condimentum lorem. Phasellus in mauris nec urna ornare ullamcorper.</p>
<p>Donec ullamcorper ligula vitae felis imperdiet faucibus. Mauris porta  tincidunt ipsum, ac commodo elit consectetur nec. Sed cursus rutrum enim  in sodales. In sit amet massa nec lorem vestibulum placerat a sed  tortor. Mauris vitae condimentum dui. Mauris elementum ligula est, eu  pellentesque justo mollis vitae. Etiam leo mauris, hendrerit vitae  egestas quis, placerat ac tellus. Nullam gravida at neque varius rutrum.  Ut a convallis mi, et gravida velit.</p>
<p>Vivamus rutrum malesuada condimentum. In est leo, ultrices vitae cursus  ac, feugiat porttitor turpis. Donec et arcu a nunc faucibus pharetra vel  iaculis massa. Nullam eleifend condimentum mauris sit amet cursus.  Curabitur vitae elementum risus, eu gravida quam. Interdum et malesuada  fames ac ante ipsum primis in faucibus. Proin id eleifend massa. Quisque  porta, quam vitae ornare varius, lorem nisl ornare tortor, ac semper ex  nunc et erat. Fusce suscipit a justo sed tempor. Praesent pulvinar, sem  sit amet gravida vulputate, quam magna hendrerit felis, eget semper  elit arcu non metus. Vestibulum non odio vel dui finibus finibus. Fusce  ultrices sem ligula, condimentum cursus neque fringilla pellentesque.  Proin vel quam lectus. Aenean a erat lobortis, scelerisque dolor  aliquet, porta erat. Duis id placerat augue.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '' WHERE id = 1;
UPDATE peel_rubriques SET nom_fr = 'Notre métier', description_fr = '<div class="showcase_page_our_job_welcome_zone_personnalized_banner"></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean  tincidunt facilisis purus nec aliquam. Fusce egestas molestie elit, ut  tempus ante egestas et. Cras leo elit, finibus vel condimentum at,  condimentum sed sapien. Morbi congue vel metus id dapibus. Pellentesque  efficitur tincidunt malesuada. Cras dapibus congue tempus. Maecenas sed  urna risus. Cras consectetur risus a sagittis ultrices. Quisque gravida  facilisis metus. Nulla vel enim et libero luctus placerat eget sit amet  ligula. Integer dapibus sem non lorem interdum, id porta orci convallis.  Pellentesque habitant morbi tristique senectus et netus et malesuada  fames ac turpis egestas. Cras dictum lorem quis leo consectetur, in  tristique ipsum pretium. Proin placerat, sapien nec tristique  scelerisque, magna orci scelerisque odio, sit amet viverra augue magna  eget massa. In volutpat metus et ante hendrerit tempor.</p>
<p>In eleifend sem nisi, a finibus metus suscipit eu. Phasellus imperdiet  leo fermentum arcu lobortis, ac ornare elit tempus. Quisque dictum  pulvinar sagittis. Ut efficitur quis orci id dapibus. Etiam tincidunt  est enim, ultricies fermentum lorem finibus volutpat. Phasellus nec sem  neque. Maecenas in augue et felis aliquam aliquam. Nullam sed felis  aliquam, iaculis tellus vitae, vulputate lectus. Nam ullamcorper metus  vitae vestibulum ornare. Etiam urna justo, pulvinar vel orci eget,  auctor molestie neque. Etiam sit amet pulvinar nisi, sed bibendum massa.</p>
<p>Duis nunc enim, semper pharetra purus vel, porttitor bibendum ipsum. Ut  non ante tempor, finibus lacus ultricies, tristique nibh. Praesent  vulputate lorem eu feugiat congue. Nulla non aliquet sem. Vestibulum  vitae pretium dui. Curabitur iaculis nulla quis mauris viverra  dignissim. Vestibulum pharetra nibh ex, vitae pharetra enim semper sit  amet.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '' WHERE id = 2;
UPDATE peel_rubriques SET nom_fr = 'Nos partenaires', description_fr = '<div class="showcase_page_our_partners_welcome_zone_personnalized_banner"></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas  blandit turpis nibh, vitae dignissim mi vulputate a. In hac habitasse  platea dictumst. Maecenas non auctor ipsum. Quisque sed urna a leo  lacinia pharetra. Nulla rutrum sapien non augue blandit rutrum.  Vestibulum leo sem, tincidunt sit amet ex in, tempor mattis nunc. Cras  et sagittis diam, eget ultrices massa. Donec a quam nec nisl posuere  finibus eleifend quis tortor. Suspendisse vitae vehicula nulla. Donec  dignissim egestas diam, nec aliquet nunc pharetra sit amet.</p>
<p>Aenean eu tellus justo. In aliquet non nisi sed finibus. Aenean aliquam  diam nibh, at ornare enim tempor sed. Duis ornare congue magna.  Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere  cubilia Curae; Proin pellentesque, quam ut tempor ornare, orci tellus  suscipit quam, in iaculis tortor magna vitae nunc. Duis leo ipsum,  faucibus vel imperdiet et, tincidunt interdum orci. Sed scelerisque,  lectus vel efficitur rutrum, arcu enim vulputate nisi, sit amet maximus  justo enim eget odio. Etiam vitae ex faucibus, eleifend purus eget,  consectetur odio.</p>
<p>Suspendisse a molestie ante. Nullam vestibulum hendrerit sem non  condimentum. Sed iaculis, magna et faucibus consequat, mi leo finibus  quam, tempor cursus erat augue vehicula neque. In condimentum massa sed  mauris consectetur, id facilisis enim porttitor. Vivamus euismod euismod  diam vel posuere. Vestibulum mollis leo id nisi semper commodo. Proin  tellus odio, tristique vel lorem ac, vehicula porta magna. Suspendisse  arcu mi, facilisis lobortis facilisis et, ultricies nec libero. Mauris  ipsum nisl, porta sit amet lacinia vel, vehicula nec nisl. Class aptent  taciti sociosqu ad litora torquent per conubia nostra, per inceptos  himenaeos. Cras ultricies venenatis ex sit amet efficitur. Quisque  vestibulum mauris accumsan massa pellentesque convallis. Integer id  congue nunc.</p>
<p>Integer eleifend ante justo, sit amet fringilla nunc consequat commodo.  Nullam at feugiat nisi. Sed ullamcorper ornare blandit. Sed interdum  hendrerit nunc, vitae venenatis libero. Proin id augue dolor. Etiam sed  nibh eget neque condimentum gravida. Duis facilisis mi tempus venenatis  egestas. Proin lorem nibh, luctus nec vulputate eu, rutrum scelerisque  nibh.</p>
<p>Vestibulum imperdiet sodales rhoncus. Interdum et malesuada fames ac  ante ipsum primis in faucibus. Praesent condimentum, dui ac maximus  congue, lectus velit consequat quam, a consectetur metus neque a urna.  Aenean a egestas lacus. Integer scelerisque malesuada urna, at tincidunt  eros convallis non. Curabitur placerat turpis lacus, id ornare diam  fringilla ultricies. Interdum et malesuada fames ac ante ipsum primis in  faucibus. Aenean at metus eget lorem rhoncus efficitur ac at nibh. Ut  eu fringilla lacus. Donec varius orci quam, laoreet varius ligula dictum  a.</p>
<p>Nunc non diam a dolor faucibus suscipit. Nunc eu sagittis eros. Maecenas  in ex sem. Maecenas euismod nec dui quis maximus. Donec facilisis  laoreet sapien, ac laoreet ante. Vivamus fermentum tortor a ultricies  tempus. Proin non nisi aliquet, aliquet diam ac, efficitur urna. Sed  tincidunt ipsum ut erat consectetur, vel ornare massa fringilla.  Suspendisse sollicitudin odio nisl, non varius arcu sodales id. Quisque  vel odio ullamcorper, rhoncus urna sed, tristique sapien. Fusce  hendrerit, arcu in finibus varius, eros ligula molestie mauris, a luctus  lacus velit a ante. Integer ac convallis erat.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '' WHERE id = 3;
UPDATE peel_rubriques SET nom_fr = 'Notre structure', description_fr = '<div class="showcase_page_our_structure_welcome_zone_personnalized_banner"></div>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et porta  ex. Quisque eu accumsan diam. Nam libero justo, aliquam id ornare eget,  auctor et leo. Vestibulum eu placerat sem, sodales iaculis neque.  Aliquam lacinia velit quis odio convallis pretium. Integer hendrerit leo  non euismod sodales. Aenean rutrum faucibus imperdiet. Aenean vel  consequat augue, vitae tincidunt felis. Vivamus et ullamcorper enim.  Nunc ac metus placerat, ornare ligula quis, dictum velit. Duis luctus  maximus odio. Suspendisse rhoncus mi id semper dapibus. Donec hendrerit  fermentum posuere. Aenean mi ex, consectetur vitae turpis id, vulputate  blandit dolor. Integer dictum lobortis nunc eget tristique.</p>
<p>Vestibulum nec erat interdum, posuere elit et, volutpat magna. Integer  leo turpis, bibendum a ipsum quis, pharetra pulvinar arcu. Cras et  sapien vestibulum orci faucibus tempor eu sit amet lorem. Pellentesque  habitant morbi tristique senectus et netus et malesuada fames ac turpis  egestas. Sed aliquam, velit a malesuada tristique, quam arcu posuere  nulla, non feugiat felis sem nec eros. Suspendisse sed felis ac justo  vehicula ultrices sed non tellus. Etiam sed faucibus dui. Duis tincidunt  tempus nibh, quis mollis ex. Nullam congue libero ac sem pretium  aliquam. Maecenas ac neque sem. Aliquam ex urna, varius sit amet metus  faucibus, elementum dictum enim. Praesent non tellus id turpis  sollicitudin lobortis ac vitae nisi. Nam maximus id erat et condimentum.  Sed nec purus at purus aliquet pellentesque eget nec orci. Mauris  scelerisque orci non ex sodales ultrices. Donec metus neque, eleifend  nec vestibulum in, placerat ac lacus.</p>
<p>Vestibulum consectetur dapibus semper. Sed efficitur elit ut eros  laoreet tristique. Duis vitae nisi accumsan, tempus lacus et, eleifend  arcu. Nulla fermentum arcu nec tortor tristique dignissim. Nam at turpis  justo. Cras vehicula arcu sed elit suscipit laoreet. Phasellus urna  ligula, consequat a quam eget, convallis vulputate metus. Donec feugiat,  sapien blandit rutrum ultrices, orci orci condimentum tellus, sed  venenatis est lorem id eros. Fusce metus elit, sagittis sit amet nunc  eget, gravida condimentum leo.</p>
<p>In egestas erat neque, sit amet facilisis felis tincidunt laoreet.  Integer fermentum egestas leo id vestibulum. Nunc imperdiet lobortis  nisl, aliquam condimentum magna faucibus a. Fusce mattis imperdiet  malesuada. Pellentesque sed neque sit amet orci interdum efficitur. Duis  sollicitudin vulputate dolor, at sagittis tortor suscipit non. Aenean  non tellus eu magna dictum consectetur nec vel purus. Sed quis gravida  risus. In dictum ex risus. Aenean luctus sollicitudin tempor. Nam  imperdiet velit ex, vel viverra metus ullamcorper in. Aenean congue  ipsum aliquet eros malesuada, nec maximus neque ultricies. Nulla  eleifend leo at ligula molestie, ac condimentum dui gravida.</p>
<p>Praesent a tempus erat. Fusce sit amet sem nec velit congue sagittis at  non eros. Nulla vitae odio sed metus sagittis rhoncus id sed lorem.  Phasellus accumsan sapien vitae augue mollis, sit amet congue ante  elementum. Aliquam erat volutpat. Duis faucibus nisi convallis ipsum  pulvinar, quis feugiat magna pulvinar. Pellentesque interdum, ex vel  mattis suscipit, diam turpis gravida quam, porta ultricies dolor risus a  risus. Sed nec dapibus diam. Vivamus erat arcu, sagittis non sapien a,  cursus hendrerit libero. Aenean a malesuada felis, id tincidunt mauris.  Sed dictum ex sit amet mauris facilisis ullamcorper. Sed non tortor a  sem vestibulum consectetur in vehicula neque. Nulla volutpat, mauris eu  bibendum volutpat, velit ipsum ultricies erat, ac porta purus diam id  sem. Aenean vel ultricies tortor. Suspendisse finibus aliquet eros quis  elementum. Nulla ligula risus, varius sit amet ornare eget, porta eget  enim.</p>', meta_titre_fr = '', meta_key_fr = '', meta_desc_fr = '' WHERE id = 4;

-- information légale
UPDATE `peel_legal` SET `titre_fr`= 'Informations légales', `texte_fr` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;

-- information légale
UPDATE `peel_cgv` SET `titre_fr`= 'Conditions de vente', `texte_fr` = '<div id="lipsum"> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla laoreet dapibus risus, vitae tincidunt leo lobortis ut. Ut varius lobortis volutpat. Aenean quam elit, tempus ac viverra nec, molestie ut tortor. Fusce diam purus, lacinia facilisis mattis ac, euismod vitae metus. Etiam eu quam vel leo sollicitudin semper in eget massa. Etiam non leo congue, ultrices dui et, sodales ante. Vivamus eu sapien et lacus ornare bibendum.</p> <p>Nunc sapien ligula, dictum vitae facilisis eu, suscipit quis massa. Quisque lobortis lobortis ante, in tincidunt nibh mattis ut. Donec porttitor enim non orci iaculis vehicula. Integer placerat nunc nec mauris consectetur, eget sodales nibh lobortis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc elementum dapibus consectetur. Nunc suscipit scelerisque tincidunt. Sed euismod lobortis nulla aliquet pharetra. Pellentesque turpis nulla, gravida eu elementum non, elementum et neque. Nullam nec dapibus erat. Aenean gravida, ex ac ultricies tincidunt, felis mauris tempor mauris, sit amet egestas ex nulla vitae augue. Donec pretium fringilla condimentum. Nunc ut turpis vestibulum, lobortis turpis in, dapibus magna. Aliquam ac vulputate sapien. Phasellus vel auctor sem.</p> <p>Etiam iaculis nulla nec ligula rhoncus, non luctus erat maximus. Morbi fringilla nisl et fermentum facilisis. Phasellus ac tristique velit, vitae ornare ligula. Sed ut pellentesque tortor. Quisque ac ligula lorem. Maecenas lobortis augue eget tincidunt pulvinar. Pellentesque porta nulla felis, et tempor purus posuere vitae. Nunc fermentum velit quis magna pharetra, et semper dolor molestie. Vestibulum nec augue feugiat, blandit erat ut, sollicitudin lorem. Praesent semper egestas tortor ac eleifend.</p> <p>Sed risus ipsum, mattis ultricies enim quis, pellentesque ornare ex. In convallis nisi ac velit tincidunt, ut condimentum metus cursus. Vivamus auctor, nulla non cursus tincidunt, dui mi lobortis neque, vitae interdum eros eros vestibulum turpis. Pellentesque mollis ornare erat, vitae lobortis eros lacinia a. In hac habitasse platea dictumst. Integer id arcu hendrerit, maximus quam lobortis, ullamcorper massa. Morbi interdum sodales tincidunt. Nam imperdiet felis sem, at ultricies mauris maximus vel. Ut et arcu consectetur, luctus eros eu, tristique leo.</p> <p>Praesent metus orci, vulputate nec tincidunt non, accumsan nec nunc. Praesent ac aliquam tortor. Pellentesque efficitur nulla congue orci gravida pharetra. Etiam at scelerisque dolor. Nullam eget consectetur lectus. Vestibulum ac tortor eu nibh rhoncus semper. Aenean at vehicula dui. Quisque efficitur leo in condimentum lobortis. Vivamus eu enim sed enim ullamcorper hendrerit vitae quis eros. Maecenas sit amet tincidunt magna, non tincidunt augue.</p> </div>' WHERE id=1;

















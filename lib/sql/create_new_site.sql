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
# $Id: create_new_site.sql 55931 2018-01-29 08:37:36Z sdelaporte $
#

-- Fichier exécuté par la fonction execute_sql lors de la création d'un nouveau site. La création d'un nouveau site est automatique lors de l'installation, ou manuelle depuis l'administration.

--
-- Contenu de la table `peel_access_map`
--

INSERT INTO `peel_access_map` (`map_tag`,`date_insere`,`date_maj`,`site_id`) VALUES
('', NOW(), NOW(), "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_cgv`
--

INSERT INTO `peel_cgv` (`date_insere`, `date_maj`, `site_id`) VALUES
(NOW(), NOW(), "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_banniere`
--

INSERT INTO `peel_banniere` (`description`, `image`, `date_debut`, `date_fin`, `etat`, `hit`, `vue`, `lien`, `position`, `target`, `lang`, `width`, `height`, `search_words_list`, `tag_html`, `on_search_engine_page`, `keywords`, `site_id`) VALUES
('PEEL', 'peel_banner.jpg', NOW(), '2030-12-31 00:00:00', 1, 0, 0, 'https://www.peel.fr/', 0, '_self', 'fr', '200', '76', '', '', '0', '', "[SITE_ID]"),
('PEEL', 'peel_banner.jpg', NOW(), '2030-12-31 00:00:00', 1, 0, 0, 'http://www.peel-shopping.com/', 0, '_self', 'en', '200', '76', '', '', '0', '', "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_configuration`
--

INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES
('logo_es', 'sites.php', 'string', 'modeles/peel9/images/logo_Made_with_PEEL.png', '', NOW(), '', 1, "[SITE_ID]"),
('logo_fr', 'sites.php', 'string', 'modeles/peel9/images/logo_Made_with_PEEL.png', '', NOW(), '', 1, "[SITE_ID]"),
('logo_en', 'sites.php', 'string', 'modeles/peel9/images/logo_Made_with_PEEL.png', '', NOW(), '', 1, "[SITE_ID]"),
('display_footer_full_custom_html', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('compatibility_mode_with_htmlentities_encoding_content', 'core', 'boolean', 'false', '', NOW(), 'Si true : permet de décoder les données en BDD encodées par des versions de PEEL < 5.7.  Mettre à false pour une nouveau site, et à true si des données ont été migrées', 1, "[SITE_ID]"),
('post_variables_with_html_allowed_if_not_admin', 'core', 'array', '"description"', '', NOW(), 'Protection générale supplémentaire en complément de nohtml_real_escape_string', 1, "[SITE_ID]"),
('order_article_order_by', 'core', 'string', 'id', '', NOW(), 'Spécifie l''ordre des produits dans une commande, s''applique sur l''ensemble du site', 1, "[SITE_ID]"),
('allow_command_product_ongift', 'core', 'boolean', 'false', '', NOW(), 'Permet aux produits cadeaux (champ on_gift dans peel_produits) d''être également commandés comme des produits ordinaire, en plus d''être commandé avec les points cadeaux.', 1, "[SITE_ID]"),
('uploaded_file_max_size', 'core', 'integer', '4194304', '', NOW(), 'En octets / in bytes => Par défaut 4Mo / Au delà de cette limite, les fichiers ne seront pas acceptés', 1, "[SITE_ID]"),
('filesize_limit_keep_origin_file', 'core', 'integer', '300000', '', NOW(), 'Taille limite au delà de laquelle les images téléchargées sont regénérées par PHP et sauvegardées en JPG', 1, "[SITE_ID]"),
('image_max_width', 'core', 'integer', '1280', '', NOW(), 'Taille limite au delà de laquelle les images téléchargées sont converties en JPG à cette largeur maximum', 1, "[SITE_ID]"),
('image_max_height', 'core', 'integer', '1024', '', NOW(), 'Taille limite au delà de laquelle les images téléchargées sont converties en JPG à cette hauteur maximum', 1, "[SITE_ID]"),
('jpeg_quality', 'core', 'integer', '88', '', NOW(), 'Qualité pour les JPEG créés par le serveur / PHP default for JPEG quality: 75', 1, "[SITE_ID]"),
('session_cookie_basename', 'core', 'string', 'sid', '', NOW(), 'Sera complété par un hash de 8 caractères correspondant au chemin d''installation de ce site', 1, "[SITE_ID]"),
('sha256_encoding_salt', 'core', 'string', 'k)I8#;z=TIxnXmIPdW2TRzt4Ov89|#V~cU@]', '', NOW(), 'Used in password hash calculation. If you change it, old passwords will not be compatible anymore.', 1, "[SITE_ID]"),
('backoffice_directory_name', 'core', 'string', 'administrer', '', NOW(), 'Le nom du répertoire d''administration peut être changé, mais dans ce cas il faut aussi le changer manuellement dans l''arborescence du site sur le disque dur', 1, "[SITE_ID]"),
('cache_folder', 'core', 'string', 'cache', '', NOW(), 'Le nom du répertoire de cache peut être changé, mais dans ce cas il faut aussi le changer manuellement sur le disque dur.', 1, "[SITE_ID]"),
('force_display_reseller_prices_without_taxes', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('delivery_cost_calculation_mode', 'core', 'string', 'cheapest', '', NOW(), 'Par défaut : on prend les frais de port les moins chers qui correspondent aux contraintes poids / montant du caddie', 1, "[SITE_ID]"),
('force_sessions_for_subdomains', 'core', 'boolean', 'false', '', NOW(), 'Par défaut les cookies ne sont valables que pour un sous-domaine donné (exemple : www). C''est bien de faire cela par défaut car parfois cookie_domain bloque le déclenchement des sessions chez certains hébergeurs comme 1and1. Pour rendre disponible les cookies pour tous les sous-domaines mettez à true\r\n', 1, "[SITE_ID]"),
('admin_fill_empty_bill_number_by_number_format', 'core', 'boolean', 'true', '', NOW(), 'Dans l''édition de facture, si numéro de facture vide, on remplit par défaut automatiquement format de numéro à générer', 1, "[SITE_ID]"),
('payment_status_create_bill', 'core', 'string', 'being_checked,completed', '', NOW(), 'Dès qu''une commande est dans le statut $payment_status_create_bill, son numéro de facture est créé', 1, "[SITE_ID]"),
('smarty_avoid_check_template_files_update', 'core', 'boolean', 'false', '', NOW(), 'Passer à true si vous voulez accélérer un site en production. Attention : si true, alors les modifications que vous ferez sur les templates nécessiteront MAJ manuelle du cache', 1, "[SITE_ID]"),
('use_database_permanent_connection', 'core', 'boolean', 'false', '', NOW(), 'Valeurs possibles / Possible values : true, ''local'', ''no'' / false', 1, "[SITE_ID]"),
('allow_w3c_validator_access_admin', 'core', 'boolean', 'false', '', NOW(), 'ATTENTION SECURITE : cette valeur doit rester à false sauf cas exceptionnel de test technique de l''administration / SECURITY WARNING: this value must stay set to false, unless for administration technical tests', 1, "[SITE_ID]"),
('rating_max_value', 'core', 'integer', '5', '', NOW(), 'Nombre d''étoiles pour les votes / The number of stars allowed for voting', 1, "[SITE_ID]"),
('rating_unitwidth', 'core', 'integer', '21', '', NOW(), 'Largeur en pixels de chaque étoile de vote / The width (in pixels) of each rating unit (star, etc.)', 1, "[SITE_ID]"),
('sessions_duration', 'core', 'integer', '180', '', NOW(), 'Durée des sessions utilisateurs en minutes / User sessions duration in minutes (default : 180 min => 3h)', 1, "[SITE_ID]"),
('display_errors_for_ips', 'core', 'string', '', '', NOW(), 'Liste d''IPs, séparées par des espaces ou des virgules, pour lesquelles les erreurs PHP et SQL sont affichées / List of IPs, separated by space or comma, for which SQL & PHP errors are displayed', 1, "[SITE_ID]"),
('quotation_delay', 'core', 'string', '6 mois', '', NOW(), '', 1, "[SITE_ID]"),
('avoir', 'core', 'integer', '10', '', NOW(), '', 1, "[SITE_ID]"),
('commission_affilie', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('css', 'core', 'string', 'screen.css', '', NOW(), 'List of css file names inside /modeles/.../css/ separated by a coma', 1, "[SITE_ID]"),
('template_directory', 'core', 'string', 'peel9', '', NOW(), '', 1, "[SITE_ID]"),
('template_multipage', 'core', 'string', 'default_1', '', NOW(), '', 1, "[SITE_ID]"),
('email_paypal', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('email_commande', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('email_webmaster', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('nom_expediteur', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('email_client', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('on_logo', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('favicon', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('timemax', 'core', 'integer', '1800', '', NOW(), '', 1, "[SITE_ID]"),
('pays_exoneration_tva', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('seuil', 'core', 'integer', '5', '', NOW(), '', 1, "[SITE_ID]"),
('seuil_total', 'core', 'float', '0.00000', '', NOW(), '', 1, "[SITE_ID]"),
('seuil_total_reve', 'core', 'float', '0.00000', '', NOW(), '', 1, "[SITE_ID]"),
('module_retail', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_affilie', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_lot', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_parrain', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('module_gifts', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_cadeau', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_devise', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('devise_defaut', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_nuage', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_flash', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('module_cart_preservation', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_vacances', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('module_vacances_type', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('module_vacances_fournisseur', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_pub', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_rss', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_avis', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_precedent_suivant', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_faq', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('module_forum', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('module_giftlist', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('module_entreprise', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('sips', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('systempay_payment_count', 'core', 'string', '1', '', NOW(), '', 1, "[SITE_ID]"),
('systempay_payment_period', 'core', 'string', '0', '', NOW(), '', 1, "[SITE_ID]"),
('systempay_cle_test', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('systempay_cle_prod', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('systempay_test_mode', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('systempay_code_societe', 'core', 'string', '0', '', NOW(), '', 1, "[SITE_ID]"),
('paybox_cgi', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('paybox_site', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('paybox_rang', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('paybox_identifiant', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('email_moneybookers', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('secret_word', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('spplus', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('module_ecotaxe', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_filtre', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('nb_produit_page', 'core', 'integer', '10', '', NOW(), '', 1, "[SITE_ID]"),
('module_rollover', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('type_rollover', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('logo_affiliation', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('small_width', 'core', 'integer', '263', '', NOW(), '', 1, "[SITE_ID]"),
('small_height', 'core', 'integer', '172', '', NOW(), '', 1, "[SITE_ID]"),
('medium_width', 'core', 'integer', '800', '', NOW(), '', 1, "[SITE_ID]"),
('medium_height', 'core', 'integer', '800', '', NOW(), '', 1, "[SITE_ID]"),
('mode_transport', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('module_url_rewriting', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('display_prices_with_taxes', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('display_prices_with_taxes_in_admin', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('html_editor', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('format_numero_facture', 'core', 'string', '[order_id]', '', NOW(), '', 1, "[SITE_ID]"),
('default_country_id', 'core', 'integer', '', '', NOW(), '', 1, "[SITE_ID]"),
('nb_product', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('nb_on_top', 'core', 'integer', '12', '', NOW(), '', 1, "[SITE_ID]"),
('nb_last_views', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('auto_promo', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('act_on_top', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('tag_analytics', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('site_suspended', 'core', 'boolean', 'false', '', NOW(), '', 1, "[SITE_ID]"),
('small_order_overcost_limit', 'core', 'float', '0.00000', '', NOW(), '', 1, "[SITE_ID]"),
('small_order_overcost_amount', 'core', 'float', '0.00000', '', NOW(), '', 1, "[SITE_ID]"),
('small_order_overcost_tva_percent', 'core', 'float', '0.00', '', NOW(), '', 1, "[SITE_ID]"),
('module_captcha', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('allow_add_product_with_no_stock_in_cart', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('payment_status_decrement_stock', 'core', 'string', 'being_checked,completed', '', NOW(), '', 1, "[SITE_ID]"),
('module_socolissimo', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('module_icirelais', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('module_autosend', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('module_autosend_delay', 'core', 'integer', '5', '', NOW(), '', 1, "[SITE_ID]"),
('category_count_method', 'core', 'string', 'individual', '', NOW(), '', 1, "[SITE_ID]"),
('partner_count_method', 'core', 'string', 'individual', '', NOW(), '', 1, "[SITE_ID]"),
('admin_force_ssl', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('anim_prod', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('export_encoding', 'core', 'string', 'utf-8', '', NOW(), '', 1, "[SITE_ID]"),
('zoom', 'core', 'string', 'jqzoom', '', NOW(), '', 1, "[SITE_ID]"),
('enable_prototype', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('enable_jquery', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('send_email_active', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('minimal_amount_to_order', 'core', 'string', '0.00000', '', NOW(), '', 1, "[SITE_ID]"),
('display_nb_product', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('type_affichage_attribut', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('fb_admins', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('facebook_page_link', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('category_order_on_catalog', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('global_remise_percent', 'core', 'float', '0.00000', '', NOW(), '', 1, "[SITE_ID]"),
('availability_of_carrier', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('popup_width', 'core', 'integer', '310', '', NOW(), '', 1, "[SITE_ID]"),
('popup_height', 'core', 'integer', '160', '', NOW(), '', 1, "[SITE_ID]"),
('in_category', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('facebook_connect', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('fb_appid', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('fb_secret', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('fb_baseurl', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('module_conditionnement', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('keep_old_orders_intact', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('default_picture', 'core', 'string', 'image_defaut_peel.png', '', NOW(), '', 1, "[SITE_ID]"),
('module_tnt', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('sign_in_twitter', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('googlefriendconnect', 'core', 'integer', '0', '', NOW(), '', 1, "[SITE_ID]"),
('session_save_path', 'core', 'string', '', '', NOW(), 'Répertoire sur le disque pour stocker les sessions. Exemple : /home/example/sessions . Attention : ce répertoire en doit pas être accessible par http => il ne doit pas être à l''intérieur de votre répertoire peel. Laisser vide si on veut le répertoire défini par défaut dans php.ini du serveur', 1, "[SITE_ID]"),
('general_print_image', 'core', 'string', '{$GLOBALS[''repertoire_images'']}/imprimer.jpg', '', NOW(), '', 1, "[SITE_ID]"),
('general_home_image1', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('general_home_image2', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('general_product_image', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('general_send_email_image', 'core', 'string', '{$GLOBALS[''repertoire_images'']}/tell_friend.png', '', NOW(), '', 1, "[SITE_ID]"),
('general_give_your_opinion_image', 'core', 'string', '{$GLOBALS[''repertoire_images'']}/donnez_avis.png', '', NOW(), '', 1, "[SITE_ID]"),
('general_read_all_reviews_image', 'core', 'string', '{$GLOBALS[''repertoire_images'']}/tous_les_avis.png', '', NOW(), '', 1, "[SITE_ID]"),
('general_add_notepad_image', 'core', 'string', '{$GLOBALS[''repertoire_images'']}/ajout_pense_bete.png', '', NOW(), '', 1, "[SITE_ID]"),
('check_allowed_types', 'auto', 'boolean', 'false', '', NOW(), 'Vous pouvez activer une vérification du type MIME des fichiers téléchargés. Cela pose de nombreux problèmes car cette information n''est pas fiable et des navigateurs envoient des types MIME parfois imprévus => cette vérification est désactivée par défaut', 1, "[SITE_ID]"),
('allowed_types', 'auto', 'array', '"image/gif" => ".gif", "image/pjpeg" => ".jpg, .jpeg", "image/jpeg" => ".jpg, .jpeg", "image/x-png" => ".png", "image/png" => ".png", "text/plain" => ".html, .php, .txt, .inc, .csv", "text/comma-separated-values" => ".csv", "application/comma-separated-values" => ".csv", "text/csv" => ".csv", "application/vnd.ms-excel" => ".csv", "application/csv-tab-delimited-table" => ".csv", "application/octet-stream" => "", "application/pdf" => ".pdf", "application/force-download" => "", "application/x-shockwave-flash" => ".swf", "application/x-download" => "', '', NOW(), 'Cette variable est utilisée si check_allowed_types = true', 1, "[SITE_ID]"),
('extensions_valides_any', 'auto', 'array', '"jpg", "jpeg", "gif", "png", "ico", "swf", "csv", "txt", "pdf", "zip", "doc", "docx", "xls", "xlsx", "ppt", "pptx"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1, "[SITE_ID]"),
('extensions_valides_data', 'auto', 'array', '"csv", "txt"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1, "[SITE_ID]"),
('extensions_valides_image_or_pdf', 'auto', 'array', '"jpg", "jpeg", "gif", "png", "pdf"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1, "[SITE_ID]"),
('extensions_valides_image', 'auto', 'array', '"jpg", "jpeg", "gif", "png"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1, "[SITE_ID]"),
('extensions_valides_image_or_swf', 'auto', 'array', '"jpg", "jpeg", "gif", "png", "swf"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1, "[SITE_ID]"),
('extensions_valides_image_or_ico', 'auto', 'array', '"jpg", "jpeg", "gif", "png", "ico"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1, "[SITE_ID]"),
('uploaded_images_name_pattern', 'core', 'string', '^[0-9]{6}_[0-9]{6}_PEEL_[0-9a-z-A-Z]{8}\\.[jpg|png|gif]$', '', NOW(), 'Permet de valider le format des noms des images uploadées dans peel', 1, "[SITE_ID]"),
('site_general_columns_count', 'core', 'integer', '3', '', NOW(), '', 1, "[SITE_ID]"),
('product_details_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('ad_details_page_columns_count', 'core', 'integer', '3', '', NOW(), '', 1, "[SITE_ID]"),
('ads_list_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('blog_index_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('listecadeau_list_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('listecadeau_details_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('cart_preservation_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('references_page_columns_count', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('achat_maintenant_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('caddie_affichage_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('fin_commande_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('achat_index_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1, "[SITE_ID]"),
('edit_prices_on_products_list', 'core', 'string', 'edit', '', NOW(), '', 1, "[SITE_ID]"),
('show_qrcode_on_product_pages', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('minify_css', 'core', 'boolean', 'true', '', NOW(), 'Concatenation automatique des fichiers CSS pour plus de rapidité du site - ATTENTION : en cas de modification des fichiers CSS, vous devez cliquer sur le bouton de mise-à-jour "CSS & Javascript" dans le menu "Configuration" > "Nettoyage des dossiers", ou incrémenter manuellement la variable "minify_id_increment", ou supprimer les fichiers de cache CSS dans le dossier de cache // Automatic merge of CSS files in order to speed up pages loading - NOTICE : after any CSS file modification, you must click on the CSS update button on "Site Management" > "Cleaning File", or manually increment the "minify_id_increment" variable, or manually delete in the cache folder the CSS files which will be automatically regenerated', 1, "[SITE_ID]"),
('minify_js', 'core', 'boolean', 'true', '', NOW(), 'Concatenation automatique des fichiers Javascript pour plus de rapidité du site - ATTENTION : en cas de modification des fichiers Javascript, vous devez cliquer sur le bouton de mise-à-jour "CSS & Javascript" dans le menu "Configuration" > "Nettoyage des dossiers", ou incrémenter manuellement la variable "minify_id_increment", ou supprimer les fichiers de cache JS dans le dossier de cache // Automatic merge of Javascript files in order to speed up pages loading - NOTICE : after any Javascript file modification, you must click on the "CSS & Javascript" update button on "Site Management" > "Cleaning File", or manually increment the "minify_id_increment" variable, or manually delete in the cache folder the Javascript files which will be automatically regenerated', 1, "[SITE_ID]"),
('product_categories_depth_in_menu', 'core', 'integer', '1', '', NOW(), 'Profondeur du menu de catégories de produits. NB : Seules les catégories de produits avec position>0 s''afficheront, ce qui permet d''en exclure du menu en les mettant à position=0', 1, "[SITE_ID]"),
('content_categories_depth_in_menu', 'core', 'integer', '1', '', NOW(), 'Profondeur du menu de rubriques de contenu. NB : Seules les rubriques de contenu avec position>0 s''afficheront, ce qui permet d''en exclure du menu en les mettant à position=0', 1, "[SITE_ID]"),
('main_menu_items_if_available', 'core', 'array', '"home", "cat_*", "rub_*", "annonces", "vitrine", "other"', '', NOW(), 'Liste à définir dans l''ordre d''affichage parmi : "home", "catalog", "content", "news", "promotions", "annonces", "vitrine", "check", "account", "contact", "promotions", "admin", "cat_*", "rub_*", "art_*", "contact_form", "access_plan", "contact_us", "faq", "flash", "brand", "reseller"', 1, "[SITE_ID]"),
('template_engine', 'core', 'string', 'smarty', '', NOW(), 'Par défaut : smarty - Existe aussi en version de test : twig', 1, "[SITE_ID]"),
('catalog_products_columns_default', 'core', 'integer', '3', '', NOW(), '', 1, "[SITE_ID]"),
('associated_products_columns_default', 'core', 'integer', '4', '', NOW(), '', 1, "[SITE_ID]"),
('associated_products_display_mode', 'core', 'string', 'column', '', NOW(), '', 1, "[SITE_ID]"),
('show_on_estimate_text', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('show_add_to_cart_on_free_products', 'core', 'boolean', 'false', '', NOW(), '', 1, "[SITE_ID]"),
('show_short_description_on_product_details', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('category_show_more_on_catalog_if_no_order_allowed', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('show_on_affiche_guide', 'core', 'array', '"contact", "affiliate", "retailer", "faq", "forum", "lexique", "partner", "references", "access_plan"', '', NOW(), 'Liste à définir dans l''ordre d''affichage parmi : "contact", "affiliate", "retailer", "faq", "forum", "lexique", "partner", "references", "access_plan"', 1, "[SITE_ID]"),
('replace_words_in_lang_files', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('twitter_page_link', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('googleplus_page_link', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('skip_images_keywords', 'core', 'array', '', '', NOW(), '', 1, "[SITE_ID]"),
('appstore_link', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('categories_side_menu_item_max_length', 'core', 'integer', '28', '', NOW(), '', 1, "[SITE_ID]"),
('phone_cti_primary_site_list_calls_url', 'core', 'string', '', '', NOW(), '', 1, "[SITE_ID]"),
('email_accounts_for_bounces_handling', 'core', 'array', '', '', NOW(), 'Format : ''email'' => ''password''', 1, "[SITE_ID]"),
('tagcloud_display_count', 'core', 'integer', '16', '', NOW(), '', 1, "[SITE_ID]"),
('filter_stop_words', 'core', 'string', 'afin aie aient aies ailleurs ainsi ait alentour alias allaient allais allait allez allons alors apres aprs assez attendu aucun aucune aucuns audit aujourd aujourdhui auparavant auprs auquel aura aurai auraient aurais aurait auras aurez auriez aurions aurons auront aussi aussitot autant autour autre autrefois autres autrui aux auxdites auxdits auxquelles auxquels avaient avais avait avant avec avez aviez avions avoir avons ayant ayez ayons bah banco bas beaucoup ben bien bientot bis bon caha cahin car ceans ceci cela celle celles celui cent cents cependant certain certaine certaines certains certes ces cet cette ceux cgr chacun chacune champ chaque cher chez cinq cinquante combien comme comment contrario contre crescendo dabord daccord daffilee dailleurs dans daprs darrache davantage debout debut dedans dehors deja dela demain demblee depuis derechef derriere des desdites desdits desormais desquelles desquels dessous dessus deux devant devers devrait die differentes differents dire dis disent dit dito divers diverses dix doit donc dont dorenavant dos douze droite dudit duquel durant elle elles encore enfin ensemble ensuite entre envers environ essai est et etaient etais etait etant etat etc ete etes etiez etions etre eue eues euh eûmes eurent eus eusse eussent eusses eussiez eussions eut eutes eux expres extenso extremis facto faire fais faisaient faisais faisait faisons fait faites fallait faudrait faut flac fois font force fors fort forte fortiori frais fumes fur furent fus fusse fussent fusses fussiez fussions fut futes ghz grosso gure han haut hein hem heu hier hola hop hormis hors hui huit hum ibidem ici idem illico ils ipso item jadis jamais jusqu jusqua jusquau jusquaux jusque juste km² laquelle lautre lequel les lesquelles lesquels leur leurs lez loin lon longtemps lors lorsqu lorsque lot lots lui lun lune maint mainte maintenant maintes maints mais mal malgre meme memes mes mgr mhz mieux mil mille milliards millions mine minima mm² modo moi moins mon mot moult moyennant naguere neanmoins neuf nommes non nonante nonobstant nos notre nous nouveau nouveaux nouvelle nouvelles nul nulle octante ont onze ouais ou oui outre par parbleu parce parfois parmi parole partout pas passe passim pendant personne personnes petto peu peut peuvent peux piece pied pis plupart plus plusieurs plutot point posteriori pour pourquoi pourtant prealable presqu presque primo priori prix prou prs puis puisqu puisque quand quarante quasi quatorze quatre que quel quelle quelles quelqu quelque quelquefois quelques quelquun quelquune quels qui quiconque quinze quoi quoiqu quoique ref refs revoici revoila rien sans sauf secundo seize selon sensu sept septante sera serai seraient serais serait seras serez seriez serions serons seront ses seulement sic sien sine sinon sitot situ six soi soient soixante sommes son sont soudain sous souvent soyez soyons stricto suis sujet sur surtout sus tandis tant tantot tard tel telle tellement telles tels temps ter tes toi ton tot toujours tous tout toute toutefois toutes treize trente tres trois trop trs une unes uns usd vais valeur vas vends vers versa veut veux via vice vingt vingts vingt vis vite vitro vivo voici voie voient voila voire volontiers vont vos votre vous zero', 'fr', NOW(), 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatifs.', 1, "[SITE_ID]"),
('filter_stop_words', 'core', 'string', 'a able about above abst accordance according accordingly across act actually added adj affected affecting affects after afterwards again against ah all almost alone along already also although always am among amongst an and announce another any anybody anyhow anymore anyone anything anyway anyways anywhere apparently approximately are aren arent arise around as aside ask asking at auth available away awfully b back be became because become becomes becoming been before beforehand begin beginning beginnings begins behind being believe below beside besides between beyond biol both brief briefly but by c ca came can cannot can''t cause causes certain certainly co com come comes contain containing contains could couldnt d date did didn''t different do does doesn''t doing done don''t down downwards due during e each ed edu effect eg eight eighty either else elsewhere end ending enough especially et et-al etc even ever every everybody everyone everything everywhere ex except f far few ff fifth first five fix followed following follows for former formerly forth found four from further furthermore g gave get gets getting give given gives giving go goes gone got gotten h had happens hardly has hasn''t have haven''t having he hed hence her here hereafter hereby herein heres hereupon hers herself hes hi hid him himself his hither home how howbeit however hundred i id ie if i''ll im immediate immediately importance important in inc indeed index information instead into invention inward is isn''t it itd it''ll its itself i''ve j just k keep 	keeps kept kg km know known knows l largely last lately later latter latterly least less lest let lets like liked likely line little ''ll look looking looks ltd m made mainly make makes many may maybe me mean means meantime meanwhile merely mg might million miss ml more moreover most mostly mr mrs much mug must my myself n na name namely nay nd near nearly necessarily necessary need needs neither never nevertheless new next nine ninety no nobody non none nonetheless noone nor normally nos not noted nothing now nowhere o obtain obtained obviously of off often oh ok okay old omitted on once one ones only onto or ord other others otherwise ought our ours ourselves out outside over overall owing own p page pages part particular particularly past per perhaps placed please plus poorly possible possibly potentially pp predominantly present previously primarily probably promptly proud provides put q que quickly quite qv r ran rather rd re readily really recent recently ref refs regarding regardless regards related relatively research respectively resulted resulting results right run s said same saw say saying says sec section see seeing seem seemed seeming seems seen self selves sent seven several shall she shed she''ll shes should shouldn''t show showed shown showns shows significant significantly similar similarly since six slightly so some somebody somehow someone somethan something sometime sometimes somewhat somewhere soon sorry specifically specified specify specifying still stop strongly sub substantially successfully such sufficiently suggest sup sure 	t take taken taking tell tends th than thank thanks thanx that that''ll thats that''ve the their theirs them themselves then thence there thereafter thereby thered therefore therein there''ll thereof therere theres thereto thereupon there''ve these they theyd they''ll theyre they''ve think this those thou though thoughh thousand throug through throughout thru thus til tip to together too took toward towards tried tries truly try trying ts twice two u un under unfortunately unless unlike unlikely until unto up upon ups us use used useful usefully usefulness uses using usually v value various ''ve very via viz vol vols vs w want wants was wasn''t way we wed welcome we''ll went were weren''t we''ve what whatever what''ll whats when whence whenever where whereafter whereas whereby wherein wheres whereupon wherever whether which while whim whither who whod whoever whole who''ll whom whomever whos whose why widely willing wish with within without won''t words world would wouldn''t www x y yes yet you youd you''ll your youre yours yourself yourselves you''ve z zero', 'en', NOW(), 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatifs.', 1, "[SITE_ID]"),
('filter_stop_words', 'core', 'string', 'ab aber abgerufen abgerufene abgerufener abgerufenes acht ahnlich alle allein allem allen aller allerdings allerlei alles allgemein allmahlich allzu als alsbald also am an ander andere anderem anderen anderer andererseits anderes anderm andern andernfalls anders anerkannt anerkannte anerkannter anerkanntes anfangen anfing angefangen angesetze angesetzt angesetzten angesetzter ansetzen anstatt arbeiten auch auf aufgehort aufgrund aufhoren aufhorte aufzusuchen aus ausdrucken ausdruckt ausdruckte ausgenommen außen ausser außer ausserdem außerdem außerhalb author autor bald bearbeite bearbeiten bearbeitete bearbeiteten bedarf bedurfen bedurfte befragen befragte befragten befragter begann beginnen begonnen behalten behielt bei beide beiden beiderlei beides beim beinahe beitragen beitrugen bekannt bekannte bekannter bekennen benutzt bereits berichten berichtet berichtete berichteten besonders besser bestehen besteht betrachtlich bevor bezuglich bietet bin bis bis bisher bislang bist bleiben blieb bloss bloß boden brachte brachten brauchen braucht brauchte bringen bsp bzw ca da dabei dadurch dafur dagegen daher dahin damals damit danach daneben dank danke danken dann dannen daran darauf daraus darf darfst darin daruber daruberhinaus darum darunter das dass daß dasselbe davon davor dazu dein deine deinem deinen deiner deines dem demnach demselben den denen denn dennoch denselben der derart derartig derem deren derer derjenige derjenigen derselbe derselben derzeit des deshalb desselben dessen desto deswegen dich die diejenige dies diese dieselbe dieselben diesem diesen dieser dieses diesseits dinge dir direkt direkte direkten direkter doch doppelt dort dorther dorthin drauf drei dreißig drin dritte druber drunter du dunklen durch durchaus durfen durfte durfte durften eben ebenfalls ebenso ehe eher eigenen eigenes eigentlich ein einbaun eine einem einen einer einerseits eines einfach einfuhren einfuhrte einfuhrten eingesetzt einig einige einigem einigen einiger einigermaßen einiges einmal eins einseitig einseitige einseitigen einseitiger einst einstmals einzig ende entsprechend entweder er erganze erganzen erganzte erganzten erhalt erhalten erhielt erhielten erneut eroffne eroffnen eroffnet eroffnete eroffnetes erst erste ersten erster es etc etliche etwa etwas euch euer eure eurem euren eurer eures fall falls fand fast ferner finden findest findet folgende folgenden folgender folgendes folglich fordern fordert forderte forderten fortsetzen fortsetzt fortsetzte fortsetzten fragte frau frei freie freier freies fuer funf fur gab gangig gangige gangigen gangiger gangiges ganz ganze ganzem ganzen ganzer ganzes ganzlich gar gbr geb geben geblieben gebracht gedurft geehrt geehrte geehrten geehrter gefallen gefalligst gefallt gefiel gegeben gegen gehabt gehen geht gekommen gekonnt gemacht gemass gemocht genommen genug gern gesagt gesehen gestern gestrige getan geteilt geteilte getragen gewesen gewissermaßen gewollt geworden ggf gib gibt gleich gleichwohl gleichzeitig glucklicherweise gmbh gratulieren gratuliert gratulierte gute guten hab habe haben haette halb hallo hast hat hatt hatte hatte hatten hatten hattest hattet hen heraus herein heute heutige hier hiermit hiesige hin hinein hinten hinter hinterher hoch hochstens hundert ich igitt ihm ihn ihnen ihr ihre ihrem ihren ihrer ihres im immer immerhin important in indem indessen info infolge innen innerhalb ins insofern inzwischen irgend irgendeine irgendwas irgendwen irgendwer irgendwie irgendwo ist ja jahrig jahrige jahrigen jahriges je jede jedem jeden jedenfalls jeder jederlei jedes jedoch jemand jene jenem jenen jener jenes jenseits jetzt kam kann kannst kaum kein keine keinem keinen keiner keinerlei keines keines keineswegs klar klare klaren klares klein kleinen kleiner kleines koennen koennt koennte koennten komme kommen kommt konkret konkrete konkreten konkreter konkretes konn konnen konnt konnte konnte konnten konnten kunftig lag lagen langsam langst langstens lassen laut lediglich leer legen legte legten leicht leider lesen letze letzten letztendlich letztens letztes letztlich lichten liegt liest links mache machen machst macht machte machten mag magst mal man manche manchem manchen mancher mancherorts manches manchmal mann margin mehr mehrere mein meine meinem meinen meiner meines meist meiste meisten meta mich mindestens mir mit mithin mochte mochte mochten mochtest mogen moglich mogliche moglichen moglicher moglicherweise morgen morgige muessen muesst muesste muss muß mussen musst mußt mußt musste musste mußte mussten mussten nach nachdem nacher nachhinein nachste nacht nahm namlich naturlich neben nebenan nehmen nein neu neue neuem neuen neuer neues neun nicht nichts nie niemals niemand nimm nimmer nimmt nirgends nirgendwo noch notigenfalls nun nur nutzen nutzt nutzt nutzung ob oben oberhalb obgleich obschon obwohl oder oft ohne per pfui plotzlich pro reagiere reagieren reagiert reagierte rechts regelmaßig rief rund sage sagen sagt sagte sagten sagtest samtliche sang sangen schatzen schatzt schatzte schatzten schlechter schließlich schnell schon schreibe schreiben schreibens schreiber schwierig sechs sect sehe sehen sehr sehrwohl seht sei seid sein seine seinem seinen seiner seines seit seitdem seite seiten seither selber selbst senke senken senkt senkte senkten setzen setzt setzte setzten sich sicher sicherlich sie sieben siebte siehe sieht sind singen singt so sobald sodaß soeben sofern sofort sog sogar solange solc solch solche solchem solchen solcher solches soll sollen sollst sollt sollte sollten solltest somit sondern sonst sonstwo sooft soviel soweit sowie sowohl spater spielen startet startete starteten statt stattdessen steht steige steigen steigt stets stieg stiegen such suchen tages tat tat tatsachlich tatsachlichen tatsachlicher tatsachliches tausend teile teilen teilte teilten titel total trage tragen tragt trotzdem trug tun tust tut txt ubel uber uberall uberallhin uberdies ubermorgen ubrig ubrigens ueber um umso unbedingt und ungefahr unmoglich unmogliche unmoglichen unmoglicher unnotig uns unse unsem unsen unser unser unsere unserem unseren unserer unseres unserm unses unten unter unterbrach unterbrechen unterhalb unwichtig usw vergangen vergangene vergangener vergangenes vermag vermogen vermutlich veroffentlichen veroffentlicher veroffentlicht veroffentlichte veroffentlichten veroffentlichtes verrate verraten verriet verrieten version versorge versorgen versorgt versorgte versorgten versorgtes viel viele vielen vieler vieles vielleicht vielmals vier vollig vollstandig vom von vor voran vorbei vorgestern vorher vorne voruber wachen waere wahrend wahrend wahrenddessen wann war war ware waren waren warst warum was weder weg wegen weil weiß weiter weitere weiterem weiteren weiterer weiteres weiterhin welche welchem welchen welcher welches wem wen wenig wenige weniger wenigstens wenn wenngleich wer werde werden werdet weshalb wessen wichtig wie wieder wieso wieviel wiewohl will willst wir wird wirklich wirst wo wodurch wogegen woher wohin wohingegen wohl wohlweislich wolle wollen wollt wollte wollten wolltest wolltet womit woraufhin woraus worin wurde wurde wurden wurden zahlreich zB zehn zeitweise ziehen zieht zog zogen zu zudem zuerst zufolge zugleich zuletzt zum zumal zur zuruck zusammen zuviel zwanzig zwar zwei zwischen zwolf', 'de', NOW(), 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatifs.', 1, "[SITE_ID]"),
('filter_stop_words', 'core', 'string', 'algun alguna algunas alguno algunos ambos ampleamos ante antes aquel aquellas aquellos aqui arriba atras bajo bastante bien cada cierta ciertas cierto ciertos como con conseguimos conseguir consigo consigue consiguen consigues cual cuando dentro desde donde dos el ellas ellos empleais emplean emplear empleas empleo en encima entonces entre era eramos eran eras eres es esta estaba estado estais estamos estan estoy fin fue fueron fui fuimos gueno ha hace haceis hacemos hacen hacer haces hago incluso intenta intentais intentamos intentan intentar intentas intento ir la largo las lo los mientras mio modo muchos muy nos nosotros otro para pero podeis podemos poder podria podriais podriamos podrian podrias por por qué porque primero  puede pueden puedo quien sabe sabeis sabemos saben saber sabes ser si siendo sin sobre sois solamente solo somos soy su sus también teneis tenemos tener tengo tiempo tiene tienen todo trabaja trabajais trabajamos trabajan trabajar trabajas trabajo tras tuyo ultimo un una unas uno unos usa usais usamos usan usar usas uso va vais valor vamos van vaya verdad verdadera verdadero vosotras vosotros voy yo ', 'es', NOW(), 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatifs.', 1, "[SITE_ID]"),
('cron_login', 'core', 'array', '', '', NOW(), 'Format : ''login'' => ''password''', 1, "[SITE_ID]"),
('skip_home_top_products', 'core', 'boolean', 'false', '', NOW(), '', 1, "[SITE_ID]"),
('skip_home_special_products', 'core', 'boolean', 'false', '', NOW(), '', 1, "[SITE_ID]"),
('skip_home_new_products', 'core', 'boolean', 'false', '', NOW(), '', 1, "[SITE_ID]"),
('user_mandatory_fields', 'core', 'array', '"prenom" => "STR_ERR_FIRSTNAME", "nom_famille" => "STR_ERR_NAME", "adresse" => "STR_ERR_ADDRESS", "code_postal" => "STR_ERR_ZIP", "ville" => "STR_ERR_TOWN", "pays" => "STR_ERR_COUNTRY", "telephone" => "STR_ERR_TEL", "email" => "STR_ERR_EMAIL", "pseudo" => "STR_ERR_PSEUDO", "token" => "STR_INVALID_TOKEN"', '', NOW(), '', 1, "[SITE_ID]"),
('skip_home_ad_categories_presentation', 'core', 'boolean', 'false', '', NOW(), '', 1, "[SITE_ID]"),
('article_details_index_page_columns_count', 'core', 'integer', '3', '', NOW(), '', 1, "[SITE_ID]"),
('lire_index_page_columns_count', 'core', 'integer', '3', '', NOW(), '', 1, "[SITE_ID]"),
('site_index_page_columns_count', 'core', 'integer', '3', '', NOW(), '', 1, "[SITE_ID]"),
('display_nb_vote_graphic_view', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('display_content_category_diaporama', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('subcategorie_nb_column', 'core', 'integer', '4', '', NOW(), '', 1, "[SITE_ID]"),
('product_category_pages_nb_column', 'core', 'integer', '4', '', NOW(), '', 1, "[SITE_ID]"),
('search_pages_nb_column', 'core', 'integer', '4', '', NOW(), '', 1, "[SITE_ID]"),
('display_share_tools_on_product_pages', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('prices_precision', 'core', 'integer', '2', '', NOW(), 'Nombre de décimales pour l''affichage des prix / Decimal count for prices display', 1, "[SITE_ID]"),
('short_order_process', 'core', 'boolean', 'false', '', NOW(), 'Fin du process de commande, si le paramètre short_order_process est actif. Ce paramètre implique l''absence de paiement et de validation des CGV => Utile pour des demandes de devis', 1, "[SITE_ID]"),
('use_ads_as_products', 'core', 'boolean', 'false', '', NOW(), 'Permet d''ajouter des annonces au panier (nécessite le module d''annonce)', 1, "[SITE_ID]"),
('tva_annonce', 'core', 'float', '20.00', '', NOW(), 'Spécifie le taux de TVA à appliquer aux annonces lors de leur ajout au panier (fonctionne avec le paramètre use_ads_as_product).', 1, "[SITE_ID]"),
('used_uploader', 'core', 'string', 'fineuploader', '', NOW(), 'Définit quelle technologie d''upload utiliser / Defines which upload technology to use - possible values = standard, fineuploader', 1, "[SITE_ID]"),
('chart_product', 'core', 'string', 'flot', '', NOW(), '', 1, "[SITE_ID]"),
('insert_product_categories_in_menu', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('enable_gzhandler', 'core', 'boolean', 'false', '', NOW(), 'Si true : force PHP à compresser ses sorties HTTP', 1, "[SITE_ID]"),
('load_javascript_async', 'core', 'boolean', 'true', '', NOW(), 'Si true : force les fichiers js en fin de page HTML', 1, "[SITE_ID]"),
('global_promotion_percent_by_threshold', 'core', 'array', '', '', NOW(), '', 1, "[SITE_ID]"),
('minify_id_increment', 'core', 'integer', '0', '', NOW(), 'Sert pour générer un nom de fichier différent après chaque ?update=1 forcé par un administrateur', 1, "[SITE_ID]"),
('bootstrap_enabled', 'core', 'boolean', 'true', '', NOW(), 'Activer ou non Bootstrap en front-office', 1, "[SITE_ID]"),
('disable_add_to_cart_section_if_null_base_price_and_no_option', 'core', 'boolean', 'true', '', NOW(), 'Désactive l''affichage du bouton d''ajout au caddie si le produit est gratuit et sans option - Mettez à false si vous voulez gérer des processus de commande malgré l''absence de prix', 1, "[SITE_ID]"),
('paypal_additional_fields', 'core', 'string', '<input name="solution_type" value="Sole" type="hidden"><input name="landing_page" value="Billing" type="hidden">', '', NOW(), 'Permet d''ajouter des champs hidden au formulaire de communication à Paypal - par exemple : <input name="solution_type" value="Sole" type="hidden"><input name="landing_page" value="Billing" type="hidden">', 1, "[SITE_ID]"),
('autocomplete_hide_images', 'core', 'boolean', 'false', '', NOW(), 'Par défaut : false - Permet de ne pas afficher la vignette dans l''autocomplete de la recherche : c''est intéressant en cas d''absence complète d''image sur un site', '1', "[SITE_ID]"),
('autocomplete_fast_partial_search', 'core', 'boolean', 'false', '', NOW(), 'Par défaut : false - Permet d''accélerer les recherches en ne cherchant pas toutes les combinaisons possibles. En cas de trop grand nombre de produit, il n''est pas raisonnable de faire des recherches de type LIKE "%..."', '1', "[SITE_ID]"),
('load_site_specific_files_before_others', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger des fichiers de fonctions non prévus dans le logiciel', '1', "[SITE_ID]"),
('load_site_specific_files_after_others', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger des fichiers de fonctions non prévus dans le logiciel', '1', "[SITE_ID]"),
('load_site_specific_lang_folders', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger des fichiers de langue non prévus dans le logiciel', '1', "[SITE_ID]"),
('load_site_specific_js_files', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger des fichiers de javascript non prévus dans le logiciel', '1', "[SITE_ID]"),
('load_site_specific_js_content_array', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger du javascript non prévus dans le logiciel', '1', "[SITE_ID]"),
('load_site_specific_js_ready_content_array', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger du javascript non prévus dans le logiciel après le chargement de la page', '1', "[SITE_ID]"),
('show_special_on_content_category', 'core', 'boolean', 'true', '', NOW(), 'Permet d''afficher les articles sur la page d''accueil des rubriques.', '1', "[SITE_ID]"),
('insert_article_categories_in_menu', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('only_show_articles_with_picture_in_containers', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('main_menu_custom_titles', 'core', 'array', '', '', NOW(), '', 1, "[SITE_ID]"),
('main_menu_custom_urls', 'core', 'array', '', '', NOW(), '', 1, "[SITE_ID]"),
('menu_custom_submenus', 'core', 'array', '', '', NOW(), 'Works with menu_custom_urls and menu_custom_titles - Example: "main_menu_technical_code1" => "submenu_technical_code1", "main_menu_technical_code2" => "submenu_technical_code2"', 1, "[SITE_ID]"),
('menu_custom_urls', 'core', 'array', '', '', NOW(), 'Works with menu_custom_titles and menu_custom_submenus - You can create one variable per language to have different URLs - Example: "technical_code_1" => "http://www.test.com/url1", "technical_code_2" => "http://www.test.com/url2"', 1, "[SITE_ID]"),
('menu_custom_titles', 'core', 'array', '', '', NOW(), 'Works with menu_custom_urls and menu_custom_submenus - Example: "technical_code_1" => "STR_MENU_CUSTOM_TEXT_1", "technical_code_2" => "STR_MENU_CUSTOM_TEXT_2"', 1, "[SITE_ID]"),
('module_pensebete', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]"),
('statut_livraison_picto', 'core', 'array', '', '', NOW(), 'permet d''afficher des icônes cliquables pour changer le statut de livraison depuis la page de liste de commandes. Ce paramètre est un tableau qui prend le statut de livraison en index et l''image en valeur : "id_statut_livraison" => "nom+extension de l''image". Les images doivent être stockées dans le dossier administrer/images', 1, "[SITE_ID]"),
('user_job_array', 'core', 'array', '"leader" => STR_LEADER, "manager" => STR_MANAGER, "employee" => STR_EMPLOYEE', '', NOW(), '', 1, "[SITE_ID]"),
('redirect_user_after_login_by_priv', 'core', 'array', '', '', NOW(), 'paramètre contenant le code technique du profil d''utilisateur, dont la liste est consultable sur la page Configuration>Configuration>Profils d''utilisateurs (/modules/profil/administrer/profil.php, "[SITE_ID]"), et une url complète au choix, interne au site ou externe. Le format de ce paramètre est de type array : "profil" => "url"', 1, "[SITE_ID]"),
('email_sending_format_default', 'core', 'string', 'html', '', NOW(), '', 1, "[SITE_ID]"),
('display_recommanded_product_on_cart_page', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('skip_home_register_form', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('skip_home_affiche_compte', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('scroll_to_top', 'core', 'boolean', 'true', '', NOW(), '', 1, "[SITE_ID]"),
('anim_loading_page', 'core', 'integer', '1', '', NOW(), '', 1, "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_contacts`
--

INSERT INTO `peel_contacts` (`date_insere`, `date_maj`, `site_id`) VALUES
(NOW(), NOW(), "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_devises`
--

INSERT INTO `peel_devises` (`devise`, `conversion`, `symbole`, `symbole_place`, `code`, `etat`, `site_id`) VALUES
('Euro', 1.00000, '€', 1, 'EUR', 1, "[SITE_ID]"),
('CH Fr. Suisse', 1.41987, 'Fr', 1, 'CHF', 0, "[SITE_ID]"),
('US Dollar', 1.21553, '$', 0, 'USD', 0, "[SITE_ID]"),
('CA Dollar', 1.27708, '$', 0, 'CAD', 0, "[SITE_ID]"),
('JP Yen', 110.56900, '¥', 1, 'JPY', 0, "[SITE_ID]"),
('GB Pound', 0.83554, '£', 1, 'GBP', 0, "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_ecotaxes`
--

INSERT INTO `peel_ecotaxes` (`code`, `prix_ht`, `prix_ttc`, `site_id`) VALUES
('1.1', 10.87000, 13.04400, "[SITE_ID]"),
('1.2', 5.02000, 6.02400, "[SITE_ID]"),
('1.3', 1.67000, 2.00400, "[SITE_ID]"),
('1.4', 0.84000, 1.00800, "[SITE_ID]"),
('1.5', 0.42000, 0.50400, "[SITE_ID]"),
('1.6', 0.08000, 0.09600, "[SITE_ID]"),
('1.7', 3.34000, 4.00800, "[SITE_ID]"),
('1.8', 0.84000, 1.00800, "[SITE_ID]"),
('1.9', 0.42000, 0.50400, "[SITE_ID]"),
('1.10', 0.08000, 0.09600, "[SITE_ID]"),
('2.1', 0.84000, 1.00800, "[SITE_ID]"),
('2.2', 0.42000, 0.50400, "[SITE_ID]"),
('2.3', 0.08000, 0.09600, "[SITE_ID]"),
('3.1', 6.69000, 8.02800, "[SITE_ID]"),
('3.2', 3.34000, 4.00800, "[SITE_ID]"),
('3.3', 0.84000, 1.00800, "[SITE_ID]"),
('3.4', 0.84000, 1.00800, "[SITE_ID]"),
('3.5', 0.25000, 0.30000, "[SITE_ID]"),
('3.6', 0.42000, 0.50400, "[SITE_ID]"),
('3.7', 0.08000, 0.09600, "[SITE_ID]"),
('3.8', 0.01000, 0.01200, "[SITE_ID]"),
('4.1', 6.69000, 8.02800, "[SITE_ID]"),
('4.2', 3.34000, 4.00800, "[SITE_ID]"),
('4.3', 0.84000, 1.00800, "[SITE_ID]"),
('4.4', 0.84000, 1.00800, "[SITE_ID]"),
('4.5', 0.25000, 0.30000, "[SITE_ID]"),
('4.6', 0.08000, 0.09600, "[SITE_ID]"),
('6.1', 0.17000, 0.20400, "[SITE_ID]"),
('6.2', 1.25000, 1.50000, "[SITE_ID]"),
('7.1', 0.04000, 0.04800, "[SITE_ID]"),
('7.2', 0.17000, 0.20400, "[SITE_ID]"),
('7.3', 1.25000, 1.50000, "[SITE_ID]"),
('8.1', 0.84000, 1.00800, "[SITE_ID]"),
('8.2', 0.08000, 0.09600, "[SITE_ID]"),
('9.1', 0.08000, 0.09600, "[SITE_ID]"),
('9.2', 0.84000, 1.00800, "[SITE_ID]"),
('10.0', 10.87000, 13.04400, "[SITE_ID]");


-- --------------------------------------------------------

--
-- Contenu de la table `peel_html`
--

INSERT INTO peel_html (`lang`, `contenu_html`, `etat`, `titre`, `o_timestamp`, `a_timestamp`, `emplacement`, `site_id`) VALUES
('fr', 'Afin de vous proposer le meilleur service, [SITE] utilise des cookies. En naviguant sur le site, vous acceptez leur utilisation.', 0, 'En-tête du site', NOW(), NOW(), 'header', "[SITE_ID]"),
('en', 'In order to offer you the best service, [SITE] uses cookies. By navigating on this website, you accept their use.', 0, 'En-tête du site', NOW(), NOW(), 'header', "[SITE_ID]"),
('fr','<h1>La page demandée n''est pas disponible</h1>', 1, 'Page d''erreur 404', NOW(), NOW(), 'error404', "[SITE_ID]"),
('en','<h1>This page is not found</h1>', 1, 'Error 404 page content', NOW(), NOW(), 'error404', "[SITE_ID]"),
('fr','Merci de votre confiance, votre commande a été enregistrée avec succès.', 1, 'Fin du process de command court', NOW(), NOW(), 'end_process_order', "[SITE_ID]"),
('en','Thank you for your order. It has been successful.', 1, 'End of short order process', NOW(), NOW(), 'end_process_order', "[SITE_ID]"),
('fr', '<p>Interstitiel de publicité</p>', 0, 'Publicité', NOW(), NOW(), 'interstitiel', "[SITE_ID]"),
('en', '<p>Interstitial advertising</p>', 0, 'Advertising', NOW(), NOW(), 'interstitiel', "[SITE_ID]"),
('fr', '', 1, 'Bas de page du site', NOW(), NOW(), 'footer', "[SITE_ID]"),
('en', '', 1, 'Bottom of page', NOW(), NOW(), 'footer', "[SITE_ID]"),
('fr', '', 1, 'Contenu d''accueil du site', NOW(), NOW(), 'home', "[SITE_ID]"),
('en', '', 1, 'Home site content', NOW(), NOW(), 'home', "[SITE_ID]"),
('fr','', 0, 'Liens du footer', NOW(), NOW(), 'footer_link', "[SITE_ID]"),
('en','', 0, 'Footer link', NOW(), NOW(), 'footer_link', "[SITE_ID]"),
('fr', '', 0, 'Devenir revendeur', NOW(), NOW(), 'devenir_revendeur', "[SITE_ID]"),
('en', '', 0, 'Become a reseller', NOW(), NOW(), 'devenir_revendeur', "[SITE_ID]"),
('fr', '', 0, 'Milieu accueil du site', NOW(), NOW(), 'home_middle', "[SITE_ID]"),
('en', '', 0, 'Middle home page', NOW(), NOW(), 'home_middle', "[SITE_ID]");




-- --------------------------------------------------------

--
-- Contenu de la table `peel_legal`
--

INSERT INTO `peel_legal` (`date_insere`, `date_maj`, `site_id`) VALUES
(NOW(), NOW(), "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_import_field`
--

INSERT INTO `peel_import_field` (`champs`, `etat`, `site_id`) VALUES
('id', 0, "[SITE_ID]"),
('categorie_id', 0, "[SITE_ID]"),
('Categorie', 0, "[SITE_ID]"),
('id_marque', 1, "[SITE_ID]"),
('reference', 0, "[SITE_ID]"),
('nom_fr', 0, "[SITE_ID]"),
('descriptif_fr', 0, "[SITE_ID]"),
('description_fr', 0, "[SITE_ID]"),
('nom_en', 0, "[SITE_ID]"),
('descriptif_en', 0, "[SITE_ID]"),
('description_en', 0, "[SITE_ID]"),
('prix', 1, "[SITE_ID]"),
('prix_revendeur', 1, "[SITE_ID]"),
('prix_achat', 1, "[SITE_ID]"),
('tva', 1, "[SITE_ID]"),
('promotion', 1, "[SITE_ID]"),
('poids', 0, "[SITE_ID]"),
('points', 0, "[SITE_ID]"),
('image1', 1, "[SITE_ID]"),
('image2', 1, "[SITE_ID]"),
('image3', 1, "[SITE_ID]"),
('image4', 1, "[SITE_ID]"),
('image5', 1, "[SITE_ID]"),
('image6', 1, "[SITE_ID]"),
('image7', 0, "[SITE_ID]"),
('image8', 0, "[SITE_ID]"),
('image9', 0, "[SITE_ID]"),
('image10', 0, "[SITE_ID]"),
('on_stock', 1, "[SITE_ID]"),
('etat', 1, "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_meta`
--

INSERT INTO `peel_meta` (`technical_code`, `site_id`) VALUES
('', "[SITE_ID]"),
('contact', "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_modules`
--

INSERT INTO `peel_modules` (`technical_code`, `location`, `display_mode`, `position`, `etat`, `site_id`) VALUES
('catalogue', 'footer', 'sideblock', 1, 0, "[SITE_ID]"),
('tagcloud', 'below_middle', 'sideblocktitle', 2, 1, "[SITE_ID]"),
('search', 'header', '', 3, 1, "[SITE_ID]"),
('guide', 'footer', 'sideblock', 4, 1, "[SITE_ID]"),
('caddie', 'header', '', 1, 1, "[SITE_ID]"),
('account', 'below_middle', 'sideblocktitle', 2, 0, "[SITE_ID]"),
('best_seller', 'bottom_middle', '', 3, 1, "[SITE_ID]"),
('news', 'below_middle', 'sideblocktitle', 4, 1, "[SITE_ID]"),
('advertising', 'below_middle', 'sideblock', 5, 0, "[SITE_ID]"),
('menu', 'header', '', 4, 1, "[SITE_ID]"),
('ariane', 'above_middle', '', 5, 0, "[SITE_ID]"),
('advertising1', 'below_middle', 'sideblock', 10, 0, "[SITE_ID]"),
('advertising2', 'below_middle', 'sideblock', 11, 0, "[SITE_ID]"),
('advertising3', 'below_middle', 'sideblock', 12, 0, "[SITE_ID]"),
('advertising4', 'below_middle', 'sideblock', 10, 0, "[SITE_ID]"),
('advertising5', 'below_middle', 'sideblock', 11, 0, "[SITE_ID]"),
('last_views',  'below_middle',  'sideblocktitle', 2, 1, "[SITE_ID]"),
('brand', 'footer', 'sideblock', 13, 1, "[SITE_ID]"),
('paiement_secu', 'below_middle',  'sideblocktitle', 2, 0, "[SITE_ID]"),
('articles_rollover', 'below_middle',  '', 3, 0, "[SITE_ID]"),
('subscribe_newsletter', 'header',  '', 3, 0, "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_paiement`
--

INSERT INTO `peel_paiement` (`technical_code`, `position`, `tarif`, `tarif_percent`, `tva`, `etat`, `site_id`) VALUES
('check', 3, 0.00000, 0.00000, 0.00, 1, "[SITE_ID]"),
('paypal', 1, 0.00000, 0.00000, 0.00, 1, "[SITE_ID]"),
('transfer', 4, 0.00000, 0.00000, 0.00, 1, "[SITE_ID]"),
('moneybookers', 2, 0.00000, 0.00000, 0.00, 1, "[SITE_ID]"),
('pickup', 5, 0.00000, 0.00000, 0.00, 0, "[SITE_ID]"),
('delivery', 6, 0.00000, 0.00000, 0.00, 0, "[SITE_ID]"),
('cash', 7, 0.00000, 0.00000, 0.00, 0, "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_pays`
--

INSERT INTO `peel_pays` (`continent_id`, `lang`, `flag`, `etat`, `iso`, `iso3`, `iso_num`, `devise`, `position`, `risque_pays`, `site_id`) VALUES
(4, 'fr', 'fr.gif', 1, 'FR', 'FRA', 250, 'EUR', 0, 0, "[SITE_ID]"),
(3, 'en', 'af.gif', 1, 'AF', 'AFG', 4, 'AFA', 0, 0, "[SITE_ID]"),
(1, 'en', 'za.gif', 1, 'ZA', 'ZAF', 710, 'ZAR', 0, 0, "[SITE_ID]"),
(4, 'en', 'al.gif', 1, 'AL', 'ALB', 8, 'ALL', 0, 0, "[SITE_ID]"),
(1, 'fr', 'dz.gif', 1, 'DZ', 'DZA', 12, 'DZD', 0, 0, "[SITE_ID]"),
(4, 'en', 'de.gif', 1, 'DE', 'DEU', 276, 'EUR', 0, 0, "[SITE_ID]"),
(3, 'en', 'sa.gif', 1, 'SA', 'SAU', 682, 'SAR', 0, 0, "[SITE_ID]"),
(2, 'en', 'ar.gif', 1, 'AR', 'ARG', 32, 'ARS', 0, 0, "[SITE_ID]"),
(5, 'en', 'au.gif', 1, 'AU', 'AUS', 36, 'AUD', 0, 0, "[SITE_ID]"),
(4, 'en', 'at.gif', 1, 'AT', 'AUT', 40, 'EUR', 0, 0, "[SITE_ID]"),
(4, 'fr', 'be.gif', 1, 'BE', 'BEL', 56, 'EUR', 0, 0, "[SITE_ID]"),
(2, 'en', 'br.gif', 1, 'BR', 'BRA', 76, 'BRL', 0, 0, "[SITE_ID]"),
(4, 'en', 'bg.gif', 1, 'BG', 'BGR', 100, 'BGN', 0, 0, "[SITE_ID]"),
(2, 'en', 'ca.gif', 1, 'CA', 'CAN', 124, 'CAD', 0, 0, "[SITE_ID]"),
(2, 'en', 'cl.gif', 1, 'CL', 'CHL', 152, 'CLP', 0, 0, "[SITE_ID]"),
(3, 'en', 'cn.gif', 1, 'CN', 'CHN', 156, 'CNY', 0, 0, "[SITE_ID]"),
(2, 'en', 'co.gif', 1, 'CO', 'COL', 170, 'COP', 0, 0, "[SITE_ID]"),
(3, 'en', 'kr.gif', 1, 'KR', 'KOR', 410, 'KRW', 0, 0, "[SITE_ID]"),
(2, 'en', 'cr.gif', 1, 'CR', 'CRI', 188, 'CRC', 0, 0, "[SITE_ID]"),
(4, 'en', 'hr.gif', 1, 'HR', 'HRV', 191, 'HRK', 0, 0, "[SITE_ID]"),
(4, 'en', 'dk.gif', 1, 'DK', 'DNK', 208, 'DKK', 0, 0, "[SITE_ID]"),
(1, 'en', 'eg.gif', 1, 'EG', 'EGY', 818, 'EGP', 0, 0, "[SITE_ID]"),
(3, 'en', 'ae.gif', 1, 'AE', 'ARE', 784, 'AED', 0, 0, "[SITE_ID]"),
(2, 'en', 'ec.gif', 1, 'EC', 'ECU', 218, 'USD', 0, 0, "[SITE_ID]"),
(2, 'en', 'us.gif', 1, 'US', 'USA', 840, 'USD', 0, 0, "[SITE_ID]"),
(2, 'en', 'sv.gif', 1, 'SV', 'SLV', 222, 'USD', 0, 0, "[SITE_ID]"),
(4, 'en', 'es.gif', 1, 'ES', 'ESP', 724, 'EUR', 0, 0, "[SITE_ID]"),
(4, 'en', 'fi.gif', 1, 'FI', 'FIN', 246, 'EUR', 0, 0, "[SITE_ID]"),
(4, 'en', 'gr.gif', 1, 'GR', 'GRC', 300, 'EUR', 0, 0, "[SITE_ID]"),
(3, 'en', 'hk.gif', 1, 'HK', 'HKG', 344, 'HKD', 0, 0, "[SITE_ID]"),
(4, 'en', 'hu.gif', 1, 'HU', 'HUN', 348, 'HUF', 0, 0, "[SITE_ID]"),
(3, 'en', 'in.gif', 1, 'IN', 'IND', 356, 'INR', 0, 0, "[SITE_ID]"),
(3, 'en', 'id.gif', 1, 'ID', 'IDN', 360, 'IDR', 0, 0, "[SITE_ID]"),
(4, 'en', 'ie.gif', 1, 'IE', 'IRL', 372, 'EUR', 0, 0, "[SITE_ID]"),
(3, 'en', 'il.gif', 1, 'IL', 'ISR', 376, 'ILS', 0, 0, "[SITE_ID]"),
(4, 'en', 'it.gif', 1, 'IT', 'ITA', 380, 'EUR', 0, 0, "[SITE_ID]"),
(3, 'en', 'jp.gif', 1, 'JP', 'JPN', 392, 'JPY', 0, 0, "[SITE_ID]"),
(3, 'en', 'jo.gif', 1, 'JO', 'JOR', 400, 'JOD', 0, 0, "[SITE_ID]"),
(3, 'en', 'lb.gif', 1, 'LB', 'LBN', 422, 'USD', 0, 0, "[SITE_ID]"),
(3, 'en', 'my.gif', 1, 'MY', 'MYS', 458, 'MYR', 0, 0, "[SITE_ID]"),
(1, 'fr', 'ma.gif', 1, 'MA', 'MAR', 504, 'MAD', 0, 0, "[SITE_ID]"),
(2, 'en', 'mx.gif', 1, 'MX', 'MEX', 484, 'MXN', 0, 0, "[SITE_ID]"),
(4, 'en', 'bv.gif', 1, 'NO', 'NOK', 74, 'NOK', 0, 0, "[SITE_ID]"),
(5, 'en', 'nz.gif', 1, 'NZ', 'NZL', 554, 'NZD', 0, 0, "[SITE_ID]"),
(2, 'en', 'pe.gif', 1, 'PE', 'PER', 604, 'PEN', 0, 0, "[SITE_ID]"),
(3, 'en', 'pk.gif', 1, 'PK', 'PAK', 586, 'PKR', 0, 0, "[SITE_ID]"),
(4, 'en', 'nl.gif', 1, 'NL', 'NLD', 528, 'EUR', 0, 0, "[SITE_ID]"),
(3, 'en', 'ph.gif', 1, 'PH', 'PHL', 608, 'PHP', 0, 0, "[SITE_ID]"),
(4, 'en', 'pl.gif', 1, 'PL', 'POL', 616, 'PLN', 0, 0, "[SITE_ID]"),
(2, 'en', 'pr.gif', 1, 'PR', 'PRI', 630, 'USD', 0, 0, "[SITE_ID]"),
(4, 'en', 'pt.gif', 1, 'PT', 'PRT', 620, 'EUR', 0, 0, "[SITE_ID]"),
(4, 'en', 'cz.gif', 1, 'CZ', 'CZE', 203, 'CZK', 0, 0, "[SITE_ID]"),
(4, 'en', 'ro.gif', 1, 'RO', 'ROU', 642, 'ROL', 0, 0, "[SITE_ID]"),
(4, 'en', 'gb.gif', 1, 'GB', 'GBR', 826, 'GBP', 0, 0, "[SITE_ID]"),
(4, 'en', 'ru.gif', 1, 'RU', 'RUS', 643, 'RUB', 0, 0, "[SITE_ID]"),
(3, 'en', 'sg.gif', 1, 'SG', 'SGP', 702, 'SGD', 0, 0, "[SITE_ID]"),
(4, 'en', 'se.gif', 1, 'SE', 'SWE', 752, 'SEK', 0, 0, "[SITE_ID]"),
(4, 'en', 'ch.gif', 1, 'CH', 'CHE', 756, 'CHF', 0, 0, "[SITE_ID]"),
(3, 'en', 'tw.gif', 1, 'TW', 'TWN', 158, 'TWD', 0, 0, "[SITE_ID]"),
(3, 'en', 'th.gif', 1, 'TH', 'THA', 764, 'THB', 0, 0, "[SITE_ID]"),
(3, 'en', 'tr.gif', 1, 'TR', 'TUR', 792, 'TRL', 0, 0, "[SITE_ID]"),
(4, 'en', 'ua.gif', 1, 'UA', 'UKR', 804, 'UAH', 0, 0, "[SITE_ID]"),
(2, 'en', 've.gif', 1, 'VE', 'VEN', 862, 'VEB', 0, 0, "[SITE_ID]"),
(4, 'en', 'rs.gif', 1, 'RS', 'SRB', 688, 'CSD', 0, 0, "[SITE_ID]"),
(5, 'en', 'ws.gif', 1, 'WS', 'WSM', 882, 'WST', 0, 0, "[SITE_ID]"),
(4, 'en', 'ad.gif', 1, 'AD', 'AND', 20, 'EUR', 0, 0, "[SITE_ID]"),
(1, 'en', 'ao.gif', 1, 'AO', 'AGO', 24, 'AON', 0, 0, "[SITE_ID]"),
(2, 'en', 'ai.gif', 1, 'AI', 'AIA', 660, 'XCD', 0, 0, "[SITE_ID]"),
(6, 'en', 'aq.gif', 1, 'AQ', 'ATA', 10, 'USD', 0, 0, "[SITE_ID]"),
(2, 'en', 'ag.gif', 1, 'AG', 'ATG', 28, 'XCD', 0, 0, "[SITE_ID]"),
(3, 'en', 'am.gif', 1, 'AM', 'ARM', 51, 'AMD', 0, 0, "[SITE_ID]"),
(2, 'en', 'aw.gif', 1, 'AW', 'ABW', 533, 'AWG', 0, 0, "[SITE_ID]"),
(3, 'en', 'az.gif', 1, 'AZ', 'AZE', 31, 'AZM', 0, 0, "[SITE_ID]"),
(2, 'en', 'bs.gif', 1, 'BS', 'BHS', 44, 'BSD', 0, 0, "[SITE_ID]"),
(3, 'en', 'bh.gif', 1, 'BH', 'BHR', 48, 'BHD', 0, 0, "[SITE_ID]"),
(3, 'en', 'bd.gif', 1, 'BD', 'BGD', 50, 'BDT', 0, 0, "[SITE_ID]"),
(4, 'en', 'by.gif', 1, 'BY', 'BLR', 112, 'BYR', 0, 0, "[SITE_ID]"),
(2, 'en', 'bz.gif', 1, 'BZ', 'BLZ', 84, 'BZD', 0, 0, "[SITE_ID]"),
(1, 'fr', 'bj.gif', 1, 'BJ', 'BEN', 204, 'XOF', 0, 0, "[SITE_ID]"),
(2, 'en', 'bm.gif', 1, 'BM', 'BMU', 60, 'BMD', 0, 0, "[SITE_ID]"),
(3, 'en', 'bt.gif', 1, 'BT', 'BTN', 64, 'BTN', 0, 0, "[SITE_ID]"),
(2, 'en', 'bo.gif', 1, 'BO', 'BOL', 68, 'BOB', 0, 0, "[SITE_ID]"),
(4, 'en', 'ba.gif', 1, 'BA', 'BIH', 70, 'BAK', 0, 0, "[SITE_ID]"),
(1, 'en', 'bw.gif', 1, 'BW', 'BWA', 72, 'BWP', 0, 0, "[SITE_ID]"),
(4, 'en', 'bv.gif', 1, 'BV', 'BVT', 74, 'NOK', 0, 0, "[SITE_ID]"),
(3, 'en', 'io.gif', 1, 'IO', 'IOT', 86, 'GBP', 0, 0, "[SITE_ID]"),
(5, 'en', 'vg.gif', 1, 'VG', 'VGB', 92, 'USD', 0, 0, "[SITE_ID]"),
(5, 'en', 'bn.gif', 1, 'BN', 'BRN', 96, 'BND', 0, 0, "[SITE_ID]"),
(1, 'fr', 'bf.gif', 1, 'BF', 'BFA', 854, 'XOF', 0, 0, "[SITE_ID]"),
(1, 'en', 'bi.gif', 1, 'BI', 'BDI', 108, 'BIF', 0, 0, "[SITE_ID]"),
(3, 'en', 'kh.gif', 1, 'KH', 'KHM', 116, 'KHR', 0, 0, "[SITE_ID]"),
(1, 'fr', 'cm.gif', 1, 'CM', 'CMR', 120, 'XAF', 0, 0, "[SITE_ID]"),
(1, 'en', 'cv.gif', 1, 'CV', 'CPV', 132, 'CVE', 0, 0, "[SITE_ID]"),
(2, 'en', 'ky.gif', 1, 'KY', 'CYM', 136, 'KYD', 0, 0, "[SITE_ID]"),
(1, 'fr', 'cf.gif', 1, 'CF', 'CAF', 140, 'XAF', 0, 0, "[SITE_ID]"),
(1, 'fr', 'td.gif', 1, 'TD', 'TCD', 148, 'XAF', 0, 0, "[SITE_ID]"),
(5, 'en', 'cx.gif', 1, 'CX', 'CXR', 162, 'AUD', 0, 0, "[SITE_ID]"),
(5, 'en', 'cc.gif', 1, 'CC', 'CCK', 166, 'AUD', 0, 0, "[SITE_ID]"),
(1, 'fr', 'km.gif', 1, 'KM', 'COM', 174, 'KMF', 0, 0, "[SITE_ID]"),
(1, 'fr', 'cg.gif', 1, 'CG', 'COG', 178, 'XAF', 0, 0, "[SITE_ID]"),
(5, 'en', 'ck.gif', 1, 'CK', 'COK', 184, 'NZD', 0, 0, "[SITE_ID]"),
(2, 'en', 'cu.gif', 1, 'CU', 'CUB', 192, 'CUP', 0, 0, "[SITE_ID]"),
(4, 'en', 'cy.gif', 1, 'CY', 'CYP', 196, 'EUR', 0, 0, "[SITE_ID]"),
(1, 'fr', 'dj.gif', 1, 'DJ', 'DJI', 262, 'DJF', 0, 0, "[SITE_ID]"),
(2, 'en', 'dm.gif', 1, 'DM', 'DMA', 212, 'XCD', 0, 0, "[SITE_ID]"),
(2, 'en', 'do.gif', 1, 'DO', 'DOM', 214, 'DOP', 0, 0, "[SITE_ID]"),
(3, 'en', 'tp.gif', 1, 'TL', 'TLS', 626, 'USD', 0, 0, "[SITE_ID]"),
(1, 'en', 'gq.gif', 1, 'GQ', 'GNQ', 226, 'XAF', 0, 0, "[SITE_ID]"),
(1, 'en', 'er.gif', 1, 'ER', 'ERI', 232, 'ERN', 0, 0, "[SITE_ID]"),
(4, 'en', 'ee.gif', 1, 'EE', 'EST', 233, 'EEK', 0, 0, "[SITE_ID]"),
(1, 'en', 'et.gif', 1, 'ET', 'ETH', 231, 'ETB', 0, 0, "[SITE_ID]"),
(2, 'en', 'fk.gif', 1, 'FK', 'FLK', 238, 'FKP', 0, 0, "[SITE_ID]"),
(4, 'en', 'fo.gif', 1, 'FO', 'FRO', 234, 'DKK', 0, 0, "[SITE_ID]"),
(5, 'en', 'fj.gif', 1, 'FJ', 'FJI', 242, 'FJD', 0, 0, "[SITE_ID]"),
(2, 'fr', 'gf.gif', 1, 'GF', 'GUF', 254, 'EUR', 0, 0, "[SITE_ID]"),
(5, 'fr', 'pf.gif', 1, 'PF', 'PYF', 258, 'XPF', 0, 0, "[SITE_ID]"),
(6, 'fr', 'tf.gif', 1, 'TF', 'ATF', 260, 'EUR', 0, 0, "[SITE_ID]"),
(1, 'fr', 'ga.gif', 1, 'GA', 'GAB', 266, 'XAF', 0, 0, "[SITE_ID]"),
(1, 'en', 'gm.gif', 1, 'GM', 'GMB', 270, 'GMD', 0, 0, "[SITE_ID]"),
(3, 'en', 'ge.gif', 1, 'GE', 'GEO', 268, 'GEL', 0, 0, "[SITE_ID]"),
(1, 'en', 'gh.gif', 1, 'GH', 'GHA', 288, 'GHC', 0, 0, "[SITE_ID]"),
(4, 'en', 'gi.gif', 1, 'GI', 'GIB', 292, 'GIP', 0, 0, "[SITE_ID]"),
(4, 'en', 'gl.gif', 1, 'GL', 'GRL', 304, 'DKK', 0, 0, "[SITE_ID]"),
(2, 'en', 'gd.gif', 1, 'GD', 'GRD', 308, 'XCD', 0, 0, "[SITE_ID]"),
(2, 'fr', 'gp.gif', 1, 'GP', 'GLP', 312, 'EUR', 0, 0, "[SITE_ID]"),
(2, 'en', 'gu.gif', 1, 'GU', 'GUM', 316, 'USD', 0, 0, "[SITE_ID]"),
(2, 'en', 'gt.gif', 1, 'GT', 'GTM', 320, 'GTQ', 0, 0, "[SITE_ID]"),
(1, 'fr', 'gn.gif', 1, 'GN', 'GIN', 324, 'USD', 0, 0, "[SITE_ID]"),
(1, 'en', 'gw.gif', 1, 'GW', 'GNB', 624, 'XOF', 0, 0, "[SITE_ID]"),
(2, 'fr', 'ht.gif', 1, 'HT', 'HTI', 332, 'HTG', 0, 0, "[SITE_ID]"),
(5, 'en', 'hm.gif', 1, 'HM', 'HMD', 334, 'AUD', 0, 0, "[SITE_ID]"),
(2, 'en', 'hn.gif', 1, 'HN', 'HND', 340, 'HNL', 0, 0, "[SITE_ID]"),
(4, 'en', 'is.gif', 1, 'IS', 'ISL', 352, 'ISK', 0, 0, "[SITE_ID]"),
(3, 'en', 'ir.gif', 1, 'IR', 'IRN', 364, 'IRR', 0, 0, "[SITE_ID]"),
(3, 'en', 'iq.gif', 1, 'IQ', 'IRQ', 368, 'IQD', 0, 0, "[SITE_ID]"),
(1, 'fr', 'ci.gif', 1, 'CI', 'CIV', 384, 'XOF', 0, 0, "[SITE_ID]"),
(2, 'en', 'jm.gif', 1, 'JM', 'JAM', 388, 'JMD', 0, 0, "[SITE_ID]"),
(3, 'en', 'kz.gif', 1, 'KZ', 'KAZ', 398, 'KZT', 0, 0, "[SITE_ID]"),
(1, 'en', 'ke.gif', 1, 'KE', 'KEN', 404, 'KES', 0, 0, "[SITE_ID]"),
(5, 'en', 'ki.gif', 1, 'KI', 'KIR', 296, 'AUD', 0, 0, "[SITE_ID]"),
(3, 'en', 'kr.gif', 1, 'KR', 'KOR', 410, 'KRW', 0, 0, "[SITE_ID]"),
(3, 'en', 'kw.gif', 1, 'KW', 'KWT', 414, 'KWD', 0, 0, "[SITE_ID]"),
(3, 'en', 'kg.gif', 1, 'KG', 'KGZ', 417, 'KGS', 0, 0, "[SITE_ID]"),
(3, 'en', 'la.gif', 1, 'LA', 'LAO', 418, 'LAK', 0, 0, "[SITE_ID]"),
(4, 'en', 'lv.gif', 1, 'LV', 'LVA', 428, 'LVL', 0, 0, "[SITE_ID]"),
(1, 'en', 'ls.gif', 1, 'LS', 'LSO', 426, 'LSL', 0, 0, "[SITE_ID]"),
(1, 'en', 'lr.gif', 1, 'LR', 'LBR', 430, 'LRD', 0, 0, "[SITE_ID]"),
(1, 'en', 'ly.gif', 1, 'LY', 'LBY', 434, 'LYD', 0, 0, "[SITE_ID]"),
(4, 'en', 'li.gif', 1, 'LI', 'LIE', 438, 'CHF', 0, 0, "[SITE_ID]"),
(4, 'en', 'lt.gif', 1, 'LT', 'LTU', 440, 'LTL', 0, 0, "[SITE_ID]"),
(4, 'en', 'lu.gif', 1, 'LU', 'LUX', 442, 'EUR', 0, 0, "[SITE_ID]"),
(3, 'en', 'mo.gif', 1, 'MO', 'MAC', 446, 'MOP', 0, 0, "[SITE_ID]"),
(4, 'en', 'mk.gif', 1, 'MK', 'MKD', 807, 'EUR', 0, 0, "[SITE_ID]"),
(1, 'fr', 'mg.gif', 1, 'MG', 'MDG', 450, 'MGF', 0, 0, "[SITE_ID]"),
(1, 'en', 'mw.gif', 1, 'MW', 'MWI', 454, 'MWK', 0, 0, "[SITE_ID]"),
(3, 'en', 'mv.gif', 1, 'MV', 'MDV', 462, 'MVR', 0, 0, "[SITE_ID]"),
(1, 'fr', 'ml.gif', 1, 'ML', 'MLI', 466, 'XOF', 0, 0, "[SITE_ID]"),
(4, 'en', 'mt.gif', 1, 'MT', 'MLT', 470, 'EUR', 0, 0, "[SITE_ID]"),
(5, 'en', 'mh.gif', 1, 'MH', 'MHL', 584, 'USD', 0, 0, "[SITE_ID]"),
(2, 'fr', 'mq.gif', 1, 'MQ', 'MTQ', 474, 'EUR', 0, 0, "[SITE_ID]"),
(1, 'fr', 'mr.gif', 1, 'MR', 'MRT', 478, 'MRO', 0, 0, "[SITE_ID]"),
(1, 'en', 'mu.gif', 1, 'MU', 'MUS', 480, 'MUR', 0, 0, "[SITE_ID]"),
(1, 'fr', 'yt.gif', 1, 'YT', 'MYT', 175, 'EUR', 0, 0, "[SITE_ID]"),
(5, 'en', 'fm.gif', 1, 'FM', 'FSM', 583, 'USD', 0, 0, "[SITE_ID]"),
(4, 'en', 'md.gif', 1, 'MD', 'MDA', 498, 'MDL', 0, 0, "[SITE_ID]"),
(4, 'fr', 'mc.gif', 1, 'MC', 'MCO', 492, 'EUR', 0, 0, "[SITE_ID]"),
(3, 'en', 'mn.gif', 1, 'MN', 'MNG', 496, 'MNT', 0, 0, "[SITE_ID]"),
(2, 'en', 'ms.gif', 1, 'MS', 'MSR', 500, 'XCD', 0, 0, "[SITE_ID]"),
(1, 'en', 'mz.gif', 1, 'MZ', 'MOZ', 508, 'MZM', 0, 0, "[SITE_ID]"),
(3, 'en', 'mm.gif', 1, 'MM', 'MMR', 104, 'MMK', 0, 0, "[SITE_ID]"),
(1, 'en', 'na.gif', 1, 'NA', 'NAM', 516, 'NAD', 0, 0, "[SITE_ID]"),
(5, 'en', 'nr.gif', 1, 'NR', 'NRU', 520, 'AUD', 0, 0, "[SITE_ID]"),
(3, 'en', 'np.gif', 1, 'NP', 'NPL', 524, 'NPR', 0, 0, "[SITE_ID]"),
(5, 'fr', 'nc.gif', 1, 'NC', 'NCL', 540, 'XPF', 0, 0, "[SITE_ID]"),
(2, 'en', 'ni.gif', 1, 'NI', 'NIC', 558, 'NIO', 0, 0, "[SITE_ID]"),
(1, 'fr', 'ne.gif', 1, 'NE', 'NER', 562, 'XOF', 0, 0, "[SITE_ID]"),
(1, 'en', 'ng.gif', 1, 'NG', 'NGA', 566, 'NGN', 0, 0, "[SITE_ID]"),
(5, 'en', 'nu.gif', 1, 'NU', 'NIU', 570, 'NZD', 0, 0, "[SITE_ID]"),
(5, 'en', 'nf.gif', 1, 'NF', 'NFK', 574, 'AUD', 0, 0, "[SITE_ID]"),
(5, 'en', 'mp.gif', 1, 'MP', 'MNP', 580, 'USD', 0, 0, "[SITE_ID]"),
(3, 'en', 'om.gif', 1, 'OM', 'OMN', 512, 'OMR', 0, 0, "[SITE_ID]"),
(5, 'en', 'pw.gif', 1, 'PW', 'PLW', 585, 'USD', 0, 0, "[SITE_ID]"),
(2, 'en', 'pa.gif', 1, 'PA', 'PAN', 591, 'PAB', 0, 0, "[SITE_ID]"),
(5, 'en', 'pg.gif', 1, 'PG', 'PNG', 598, 'PGK', 0, 0, "[SITE_ID]"),
(2, 'en', 'py.gif', 1, 'PY', 'PRY', 600, 'PYG', 0, 0, "[SITE_ID]"),
(5, 'en', 'pn.gif', 1, 'PN', 'PCN', 612, 'NZD', 0, 0, "[SITE_ID]"),
(3, 'en', 'qa.gif', 1, 'QA', 'QAT', 634, 'QAR', 0, 0, "[SITE_ID]"),
(1, 'fr', 're.gif', 1, 'RE', 'REU', 638, 'EUR', 0, 0, "[SITE_ID]"),
(1, 'en', 'rw.gif', 1, 'RW', 'RWA', 646, 'RWF', 0, 0, "[SITE_ID]"),
(2, 'en', 'gs.gif', 1, 'GS', 'SGS', 239, 'USD', 0, 0, "[SITE_ID]"),
(2, 'en', 'kn.gif', 1, 'KN', 'KNA', 659, 'XCD', 0, 0, "[SITE_ID]"),
(2, 'en', 'lc.gif', 1, 'LC', 'LCA', 662, 'XCD', 0, 0, "[SITE_ID]"),
(2, 'en', 'vc.gif', 1, 'VC', 'VCT', 670, 'XCD', 0, 0, "[SITE_ID]"),
(5, 'en', 'ws.gif', 1, 'WS', 'WSM', 882, 'WST', 0, 0, "[SITE_ID]"),
(4, 'en', 'sm.gif', 1, 'SM', 'SMR', 674, 'EUR', 0, 0, "[SITE_ID]"),
(1, 'en', 'st.gif', 1, 'ST', 'STP', 678, 'STD', 0, 0, "[SITE_ID]"),
(1, 'fr', 'sn.gif', 1, 'SN', 'SEN', 686, 'XOF', 0, 0, "[SITE_ID]"),
(1, 'en', 'sc.gif', 1, 'SC', 'SYC', 690, 'SCR', 0, 0, "[SITE_ID]"),
(1, 'en', 'sl.gif', 1, 'SL', 'SLE', 694, 'SLL', 0, 0, "[SITE_ID]"),
(4, 'en', 'sk.gif', 1, 'SK', 'SVK', 703, 'SKK', 0, 0, "[SITE_ID]"),
(4, 'en', 'si.gif', 1, 'SI', 'SVN', 705, 'EUR', 0, 0, "[SITE_ID]"),
(1, 'en', 'so.gif', 1, 'SO', 'SOM', 706, 'SOS', 0, 0, "[SITE_ID]"),
(3, 'en', 'lk.gif', 1, 'LK', 'LKA', 144, 'LKR', 0, 0, "[SITE_ID]"),
(1, 'en', 'sh.gif', 1, 'SH', 'SHN', 654, 'SHP', 0, 0, "[SITE_ID]"),
(2, 'fr', 'pm.gif', 1, 'PM', 'SPM', 666, 'EUR', 0, 0, "[SITE_ID]"),
(1, 'en', 'sd.gif', 1, 'SD', 'SDN', 736, 'SDD', 0, 0, "[SITE_ID]"),
(2, 'en', 'sr.gif', 1, 'SR', 'SUR', 740, 'SRG', 0, 0, "[SITE_ID]"),
(4, 'en', 'sj.gif', 1, 'SJ', 'SJM', 744, 'NOK', 0, 0, "[SITE_ID]"),
(1, 'en', 'sz.gif', 1, 'SZ', 'SWZ', 748, 'SZL', 0, 0, "[SITE_ID]"),
(3, 'en', 'sy.gif', 1, 'SY', 'SYR', 760, 'SYP', 0, 0, "[SITE_ID]"),
(3, 'en', 'tj.gif', 1, 'TJ', 'TJK', 762, 'TJS', 0, 0, "[SITE_ID]"),
(1, 'en', 'tz.gif', 1, 'TZ', 'TZA', 834, 'TZS', 0, 0, "[SITE_ID]"),
(1, 'fr', 'tg.gif', 1, 'TG', 'TGO', 768, 'XOF', 0, 0, "[SITE_ID]"),
(5, 'en', 'tk.gif', 1, 'TK', 'TKL', 772, 'NZD', 0, 0, "[SITE_ID]"),
(5, 'en', 'to.gif', 1, 'TO', 'TON', 776, 'TOP', 0, 0, "[SITE_ID]"),
(2, 'en', 'tt.gif', 1, 'TT', 'TTO', 780, 'TTD', 0, 0, "[SITE_ID]"),
(1, 'fr', 'tn.gif', 1, 'TN', 'TUN', 788, 'TND', 0, 0, "[SITE_ID]"),
(3, 'en', 'tm.gif', 1, 'TM', 'TKM', 795, 'TMM', 0, 0, "[SITE_ID]"),
(2, 'en', 'tc.gif', 1, 'TC', 'TCA', 796, 'USD', 0, 0, "[SITE_ID]"),
(5, 'en', 'tv.gif', 1, 'TV', 'TUV', 798, 'AUD', 0, 0, "[SITE_ID]"),
(5, 'en', 'um.gif', 1, 'UM', 'UMI', 581, 'USD', 0, 0, "[SITE_ID]"),
(1, 'en', 'ug.gif', 1, 'UG', 'UGA', 800, 'UGX', 0, 0, "[SITE_ID]"),
(2, 'en', 'uy.gif', 1, 'UY', 'URY', 858, 'UYU', 0, 0, "[SITE_ID]"),
(3, 'en', 'uz.gif', 1, 'UZ', 'UZB', 860, 'UZS', 0, 0, "[SITE_ID]"),
(5, 'en', 'vu.gif', 1, 'VU', 'VUT', 548, 'VUV', 0, 0, "[SITE_ID]"),
(4, 'en', 'va.gif', 1, 'VA', 'VAT', 336, 'EUR', 0, 0, "[SITE_ID]"),
(3, 'en', 'vn.gif', 1, 'VN', 'VNM', 704, 'VND', 0, 0, "[SITE_ID]"),
(5, 'en', 'vi.gif', 1, 'VI', 'VIR', 850, 'USD', 0, 0, "[SITE_ID]"),
(5, 'fr', 'wf.gif', 1, 'WF', 'WLF', 876, 'XPF', 0, 0, "[SITE_ID]"),
(1, 'en', 'eh.gif', 1, 'EH', 'ESH', 732, 'MAD', 0, 0, "[SITE_ID]"),
(3, 'en', 'ye.gif', 1, 'YE', 'YEM', 887, 'YER', 0, 0, "[SITE_ID]"),
(1, 'fr', 'cd.gif', 1, 'CD', 'COD', 180, 'XAF', 0, 0, "[SITE_ID]"),
(1, 'en', 'zm.gif', 1, 'ZM', 'ZMB', 894, 'ZMK', 0, 0, "[SITE_ID]"),
(1, 'en', 'zw.gif', 1, 'ZW', 'ZWE', 716, 'ZWD', 0, 0, "[SITE_ID]"),
(2, 'en', 'bb.gif', 1, 'BB', 'BRB', 52, 'BBD', 0, 0, "[SITE_ID]"),
(4, 'en', 'yu.gif', 1, 'ME', 'MNE', 499, 'CSD', 0, 0, "[SITE_ID]");
-- --------------------------------------------------------

--
-- Contenu de la table `peel_profil`
--

INSERT INTO `peel_profil` (`priv`, `site_id`) VALUES
('util', "[SITE_ID]"),
('admin', "[SITE_ID]"),
('reve', "[SITE_ID]"),
('stop', "[SITE_ID]"),
('affi', "[SITE_ID]"),
('stand', "[SITE_ID]"),
('supplier', "[SITE_ID]"),
('newsletter', "[SITE_ID]"),
('admin_content', "[SITE_ID]"),
('admin_sales', "[SITE_ID]"),
('admin_products', "[SITE_ID]"),
('admin_webmastering', "[SITE_ID]"),
('admin_users', "[SITE_ID]"),
('admin_manage', "[SITE_ID]"),
('admin_moderation', "[SITE_ID]");


-- --------------------------------------------------------

--
-- Contenu de la table `peel_societe`
--

INSERT INTO `peel_societe` (`societe` , `adresse` , `adresse2` , `code_postal` , `code_postal2` , `ville` , `ville2` , `tel` , `tel2` , `fax` , `fax2` , `email` , `siren` , `tvaintra` , `nom` , `prenom` , `pays` , `pays2` , `siteweb` , `logo` , `code_banque` , `code_guichet` , `numero_compte` , `cle_rib` , `titulaire` , `domiciliation` , `cnil` , `iban` , `swift`, `site_id` )
VALUES ('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_statut_livraison`
--

INSERT INTO `peel_statut_livraison` (`position`, `technical_code`, `site_id`) VALUES
(0, "discussed", "[SITE_ID]"),
(1, "processing", "[SITE_ID]"),
(3, "dispatched", "[SITE_ID]"),
(6, "cancelled", "[SITE_ID]"),
(9, "waiting_for_supply", "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_statut_paiement`
--

INSERT INTO `peel_statut_paiement` (`position`, `technical_code`, `site_id`) VALUES
(0, "discussed", "[SITE_ID]"),
(1, "pending", "[SITE_ID]"),
(2, "being_checked", "[SITE_ID]"),
(3, "completed", "[SITE_ID]"),
(6, "cancelled", "[SITE_ID]"),
(9, "refunded", "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_tarifs`
--

INSERT INTO `peel_tarifs` (`poidsmax`, `totalmax`, `tarif`, `tva`, `site_id`) VALUES
(250.00, 0.00, 6.28, 20.00, "[SITE_ID]"),
(500.00, 0.00, 7.12, 20.00, "[SITE_ID]"),
(750.00, 0.00, 7.95, 20.00, "[SITE_ID]"),
(1000.00, 0.00, 8.37, 20.00, "[SITE_ID]"),
(1500.00, 0.00, 8.91, 20.00, "[SITE_ID]"),
(2000.00, 0.00, 9.33, 20.00, "[SITE_ID]"),
(3000.00, 0.00, 10.05, 20.00, "[SITE_ID]"),
(4000.00, 0.00, 10.88, 20.00, "[SITE_ID]"),
(5000.00, 0.00, 11.60, 20.00, "[SITE_ID]"),
(6000.00, 0.00, 12.32, 20.00, "[SITE_ID]"),
(7000.00, 0.00, 12.80, 20.00, "[SITE_ID]"),
(10000.00, 0.00, 14.05, 20.00, "[SITE_ID]"),
(15000.00, 0.00, 16.21, 20.00, "[SITE_ID]"),
(30000.00, 0.00, 21.95, 20.00, "[SITE_ID]"),
(0.00, 0.00, 0.00, 5.50, "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_tva`
--

INSERT INTO `peel_tva` (`tva`, `site_id`) VALUES
(20.00, "[SITE_ID]"),
(5.50, "[SITE_ID]"),
(2.10, "[SITE_ID]"),
(0.00, "[SITE_ID]"),
(10.00, "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_types`
--

INSERT INTO `peel_types` (`position`, `without_delivery_address`, `is_socolissimo`, `is_icirelais`, `etat`, `technical_code`, `site_id`) VALUES
(1, 0, 0, 0, 1, "colissimo_without_signature", "[SITE_ID]"),
(2, 0, 0, 0, 0, "colissimo_expert_international", "[SITE_ID]"),
(3, 0, 0, 0, 0, "chronopost", "[SITE_ID]"),
(4, 1, 0, 0, 1, "pickup", "[SITE_ID]"),
(5, 0, 0, 0, 0, "ups", "[SITE_ID]"),
(6, 0, 0, 0, 0, "dhl", "[SITE_ID]"),
(7, 0, 0, 0, 0, "fedex", "[SITE_ID]");

-- --------------------------------------------------------

--
-- Contenu de la table `peel_zones`
--

INSERT INTO `peel_zones` (`tva`, `position`, `on_franco`, `on_franco_nb_products`, `technical_code`, `site_id`) VALUES
(1, 1, 0, 0, "france_mainland", "[SITE_ID]"),
(1, 2, 0, 0, "europe", "[SITE_ID]"),
(1, 3, 0, 0, "france_and_overseas", "[SITE_ID]"),
(1, 4, 0, 0, "world", "[SITE_ID]");
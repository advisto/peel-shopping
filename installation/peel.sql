# +----------------------------------------------------------------------+
# | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
# +----------------------------------------------------------------------+
# | This file is part of PEEL Shopping 7.0.4, which is subject to an	 |
# | opensource GPL license: you are allowed to customize the code		 |
# | for your own needs, but must keep your changes under GPL 			 |
# | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
# +----------------------------------------------------------------------+
# | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
# +----------------------------------------------------------------------+
# $Id: peel.sql 38045 2013-09-05 09:11:49Z gboussin $
#

--
-- Structure de la table `peel_access_map`
--

CREATE TABLE IF NOT EXISTS `peel_access_map` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `map_tag` TEXT NOT NULL,
  `date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_access_map`
--

INSERT INTO `peel_access_map` (`id`,`map_tag`,`date_insere`,`date_maj`) VALUES
(1, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `peel_admins_actions`
--

CREATE TABLE IF NOT EXISTS `peel_admins_actions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL DEFAULT '0',
  `action` enum('','SEARCH_USER','ADD_FILTER','EDIT_FILTER','DEL_FILTER','EDIT_AD','SUP_AD','EDIT_VOTE','SUP_DETAILS','EDIT_PROFIL','EDIT_FORUM','SUP_FORUM','SUP_COMPTE','ACTIVATE_COMPTE','NOTES_RECUES','NOTES_DONNEES','NOTE_PROFIL','AUTRE','SEND_EMAIL','CREATE_ORDER','EDIT_ORDER','SUP_ORDER','PHONE_EMITTED','PHONE_RECEIVED','EVENT') NOT NULL DEFAULT '',
  `id_membre` int(11) unsigned NOT NULL DEFAULT '0',
  `data` varchar(255) NOT NULL DEFAULT '',
  `raison` varchar(255) NOT NULL DEFAULT '',
  `remarque` text NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_membre` (`id_membre`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `peel_admins_comments`
--

CREATE TABLE IF NOT EXISTS `peel_admins_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0',
  `timestamp` int(11) unsigned NOT NULL DEFAULT '0',
  `comments` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `peel_admins_contacts_planified`
--

CREATE TABLE IF NOT EXISTS `peel_admins_contacts_planified` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0',
  `timestamp` int(11) unsigned NOT NULL DEFAULT '0',
  `reason` enum('','interesting_profile','interested_by_product','payment_expected','follow_up','renewal_expected','planified','usual') NOT NULL DEFAULT '',
  `comments` varchar(255) NOT NULL DEFAULT '',
  `actif` enum('TRUE','FALSE') NOT NULL DEFAULT 'TRUE',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `peel_articles`
--

CREATE TABLE IF NOT EXISTS `peel_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image1` varchar(255) NOT NULL DEFAULT '',
  `on_special` tinyint(1) NOT NULL DEFAULT '0',
  `date_insere` date NOT NULL DEFAULT '0000-00-00',
  `date_maj` date NOT NULL DEFAULT '0000-00-00',
  `lang` varchar(2) NOT NULL DEFAULT '',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_articles`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_articles_rubriques`
--

CREATE TABLE IF NOT EXISTS `peel_articles_rubriques` (
  `article_id` int(11) NOT NULL DEFAULT '0',
  `rubrique_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`article_id`,`rubrique_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_articles_rubriques`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_attributs`
--

CREATE TABLE IF NOT EXISTS `peel_attributs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nom_attribut` int(11) NOT NULL DEFAULT '0',
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `prix` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_revendeur` float(15,5) NOT NULL DEFAULT '0.00000',
  `position` int(11) NOT NULL DEFAULT '0',
  `mandatory` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `peel_attributs`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_avis`
--

CREATE TABLE IF NOT EXISTS `peel_avis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produit` int(11) NOT NULL DEFAULT '0',
  `nom_produit` varchar(255) NOT NULL DEFAULT '',
  `id_utilisateur` int(11) NOT NULL DEFAULT '0',
  `prenom` varchar(255) NOT NULL DEFAULT '',
  `pseudo` varchar(64) NOT NULL DEFAULT '',
  `note` smallint(5) NOT NULL DEFAULT '0',
  `avis` varchar(255) NOT NULL DEFAULT '',
  `datestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL DEFAULT '',
  `lang` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_avis`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_banniere`
--

CREATE TABLE IF NOT EXISTS `peel_banniere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_categorie` int(11) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `date_debut` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_fin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `hit` int(11) NOT NULL DEFAULT '0',
  `vue` int(11) NOT NULL DEFAULT '0',
  `lien` varchar(255) NOT NULL DEFAULT '',
  `position` smallint(5) NOT NULL DEFAULT '0',
  `lang` char(2) NOT NULL DEFAULT '',
  `target` varchar(10) NOT NULL DEFAULT '',
  `tag_html` mediumtext NOT NULL,
  `extra_javascript` varchar(255) NOT NULL DEFAULT '',
  `width` varchar(8) NOT NULL DEFAULT '',
  `height` varchar(8) NOT NULL DEFAULT '',
  `search_words_list` text NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `annonce_number` int(11) NOT NULL DEFAULT 0,
  `rang` int(10) DEFAULT NULL,
  `on_home_page` tinyint(1) NOT NULL DEFAULT '0',
  `on_other_page_category` tinyint(1) NOT NULL DEFAULT '0',
  `on_first_page_category` tinyint(1) NOT NULL DEFAULT '0',
  `on_other_page` tinyint(1) NOT NULL DEFAULT '0',
  `on_search_engine_page` mediumtext NOT NULL,
  `keywords` mediumtext NOT NULL,
  `list_id` varchar(255) NOT NULL DEFAULT '',
  `pages_allowed` enum('all','odd','even') NOT NULL DEFAULT 'all',
  `do_not_display_on_pages_related_to_user_ids_list` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_banniere`
--

INSERT INTO `peel_banniere` (`description`, `image`, `date_debut`, `date_fin`, `etat`, `hit`, `vue`, `lien`, `position`, `target`, `lang`, `width`, `height`, `search_words_list`, `tag_html`, `on_search_engine_page`, `keywords`) VALUES
('PEEL', 'peel_banner.jpg', '2012-01-01 00:00:00', '2030-12-31 00:00:00', 1, 0, 0, 'https://www.peel.fr/', 0, '_self', 'fr', '200', '76', '', '', '', ''),
('PEEL', 'peel_banner.jpg', '2012-01-01 00:00:00', '2030-12-31 00:00:00', 1, 0, 0, 'http://www.peel-shopping.com/', 0, '_self', 'en', '200', '76', '', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `peel_butterflive`
--

CREATE TABLE IF NOT EXISTS `peel_butterflive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `param` varchar(25) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
--
-- Contenu de la table `peel_butterflive`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_categories`
--

CREATE TABLE IF NOT EXISTS `peel_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  `lang` varchar(2) NOT NULL DEFAULT '',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `on_special` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `nb` int(11) NOT NULL DEFAULT '0',
  `color` varchar(255) NOT NULL DEFAULT '',
  `type_affichage` tinyint(1) NOT NULL DEFAULT '0',
  `background_menu` varchar(255) NOT NULL DEFAULT '',
  `background_color` varchar(255) NOT NULL DEFAULT '',
  `on_child` tinyint(1) NOT NULL DEFAULT '0',
  `promotion_devises` float(15,5) NOT NULL DEFAULT '0.00000',
  `promotion_percent` float(15,5) NOT NULL DEFAULT '0.00000',
  `on_carrousel` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
--
-- Contenu de la table `peel_categories`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_cgv`
--

CREATE TABLE IF NOT EXISTS `peel_cgv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_cgv`
--

INSERT INTO `peel_cgv` (`id`, `date_insere`, `date_maj`) VALUES
(1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `peel_codes_promos`
--

CREATE TABLE IF NOT EXISTS `peel_codes_promos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL DEFAULT '0',
  `nom` varchar(25) NOT NULL DEFAULT '',
  `date_debut` date NOT NULL DEFAULT '0000-00-00',
  `date_fin` date NOT NULL DEFAULT '0000-00-00',
  `remise_percent` float(15,2) NOT NULL DEFAULT '0.00',
  `remise_valeur` float(15,5) NOT NULL DEFAULT '0.00000',
  `on_type` tinyint(1) NOT NULL DEFAULT '0',
  `montant_min` float(15,5) NOT NULL DEFAULT '0.00000',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `source` varchar(25) NOT NULL DEFAULT '',
  `email_ami` varchar(255) NOT NULL DEFAULT '',
  `email_acheteur` varchar(255) NOT NULL DEFAULT '',
  `on_check` tinyint(1) NOT NULL DEFAULT '0',
  `id_site` int(11) NOT NULL DEFAULT '0',
  `id_categorie` int(11) NOT NULL DEFAULT '0',
  `nombre_prevue` INT( 11 ) NOT NULL DEFAULT '0',
  `compteur_utilisation` INT( 11 ) NOT NULL DEFAULT '0',
  `nb_used_per_client` INT( 11 ) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `nom` (`nom`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_codes_promos`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_commandes`
--

CREATE TABLE IF NOT EXISTS `peel_commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL DEFAULT '',
  `o_timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `a_timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `f_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `e_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `commentaires` mediumtext NOT NULL,
  `montant` float(15,5) NOT NULL DEFAULT '0.00000',
  `montant_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `total_produit` float(15,5) NOT NULL DEFAULT '0.00000',
  `total_produit_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `paiement` varchar(255) NOT NULL DEFAULT '',
  `transport` varchar(255) NOT NULL DEFAULT '',
  `cout_transport` float(15,5) NOT NULL DEFAULT '0.00000',
  `cout_transport_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `lang` varchar(2) NOT NULL DEFAULT '',
  `total_points` int(11) NOT NULL DEFAULT '0',
  `points_etat` tinyint(1) NOT NULL DEFAULT '0',
  `code_promo` varchar(25) NOT NULL DEFAULT '',
  `numero` varchar(40) NOT NULL DEFAULT '',
  `societe_bill` varchar(255) NOT NULL DEFAULT '',
  `nom_bill` varchar(255) NOT NULL DEFAULT '',
  `prenom_bill` varchar(255) NOT NULL DEFAULT '',
  `telephone_bill` varchar(25) NOT NULL DEFAULT '',
  `email_bill` varchar(255) NOT NULL DEFAULT '',
  `adresse_bill` varchar(255) NOT NULL DEFAULT '',
  `ville_bill` varchar(255) NOT NULL DEFAULT '',
  `zip_bill` varchar(100) NOT NULL DEFAULT '',
  `pays_bill` varchar(255) NOT NULL DEFAULT '',
  `societe_ship` varchar(255) NOT NULL DEFAULT '',
  `nom_ship` varchar(255) NOT NULL DEFAULT '',
  `prenom_ship` varchar(255) NOT NULL DEFAULT '',
  `telephone_ship` varchar(25) NOT NULL DEFAULT '',
  `email_ship` varchar(255) NOT NULL DEFAULT '',
  `adresse_ship` varchar(255) NOT NULL DEFAULT '',
  `ville_ship` varchar(255) NOT NULL DEFAULT '',
  `zip_ship` varchar(100) NOT NULL DEFAULT '',
  `pays_ship` varchar(255) NOT NULL DEFAULT '',
  `montant_affilie` float(15,5) NOT NULL DEFAULT '0.00000',
  `affilie` tinyint(1) NOT NULL DEFAULT '0',
  `statut_affilie` tinyint(1) NOT NULL DEFAULT '0',
  `total_tva` float(15,5) NOT NULL DEFAULT '0.00000',
  `zone_tva` tinyint(1) NOT NULL DEFAULT '0',
  `zone_franco` tinyint(1) NOT NULL DEFAULT '0',
  `colis` varchar(255) NOT NULL DEFAULT '',
  `tarif_paiement` float(15,5) NOT NULL DEFAULT '0.00000',
  `tarif_paiement_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `tva_tarif_paiement` float(15,5) NOT NULL DEFAULT '0.00000',
  `unique_id` varchar(255) NOT NULL DEFAULT '',
  `nom_utilisateur` varchar(255) NOT NULL DEFAULT '',
  `contre_remboursement` varchar(255) NOT NULL DEFAULT '',
  `total_poids` float(12,2) NOT NULL DEFAULT '0.00',
  `tva_cout_transport` float(15,5) NOT NULL DEFAULT '0.00000',
  `id_unique` varchar(255) NOT NULL DEFAULT '',
  `total_remise` float(15,5) NOT NULL DEFAULT '0.00000',
  `total_remise_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `zone` varchar(128) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT '',
  `typeId` int(11) NOT NULL DEFAULT 0,
  `pays` varchar(128) NOT NULL DEFAULT '',
  `valeur_code_promo` float(15,5) NOT NULL DEFAULT '0.00000',
  `percent_code_promo` float(15,5) NOT NULL DEFAULT '0.00000',
  `avoir` float(15,5) NOT NULL DEFAULT '0.00000',
  `avoir_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `tva_avoir` float(15,5) NOT NULL DEFAULT '0.00000',
  `id_parrain` int(11) NOT NULL DEFAULT '0',
  `id_affilie` int(11) NOT NULL DEFAULT '0',
  `parrain` varchar(10) NOT NULL DEFAULT '0',
  `total_ecotaxe_ttc` float(13,2) NOT NULL DEFAULT '0.00',
  `total_ecotaxe_ht` float(13,2) NOT NULL DEFAULT '0.00',
  `devise` varchar(10) NOT NULL DEFAULT '',
  `currency_rate` float(15, 5) NOT NULL DEFAULT '1.00000',
  `percent_remise_user` float(15,5) NOT NULL DEFAULT '0.00000',
  `id_statut_paiement` int(11) NOT NULL DEFAULT '0',
  `id_statut_livraison` int(11) NOT NULL DEFAULT '0',
  `total_option` float(15,5) NOT NULL DEFAULT '0.00000',
  `total_option_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `tva_total_produit` float(15,5) NOT NULL DEFAULT '0.00000',
  `tva_total_option` float(15,5) NOT NULL DEFAULT '0.00000',
  `tva_total_remise` float(15,5) NOT NULL DEFAULT '0.00000',
  `tva_total_ecotaxe` float(15,5) NOT NULL DEFAULT '0.00000',
  `code_facture` varchar(10) NOT NULL DEFAULT '',
  `id_ecom` int(11) NOT NULL DEFAULT '0',
  `small_order_overcost_amount` FLOAT(15,5) NOT NULL DEFAULT '0.00000',
  `tva_small_order_overcost` FLOAT(15,5) NOT NULL DEFAULT '0.00000',
  `delivery_orderid` VARCHAR(16) NOT NULL DEFAULT '',
  `delivery_infos` VARCHAR(64) NOT NULL DEFAULT '',
  `delivery_tracking` mediumtext NOT NULL,
  `delivery_locationid` VARCHAR(64) NOT NULL DEFAULT '',
  `moneybookers_payment_methods` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `email` (`email`),
  KEY `id_statut_paiement` (`id_statut_paiement`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


--
-- Contenu de la table `peel_commandes`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_commandes_articles`
--

CREATE TABLE IF NOT EXISTS `peel_commandes_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commande_id` int(11) NOT NULL DEFAULT '0',
  `produit_id` int(11) NOT NULL DEFAULT '0',
  `categorie_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(255) NOT NULL DEFAULT '',
  `nom_produit` varchar(255) NOT NULL DEFAULT '',
  `prix` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_cat` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_cat_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_achat_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `total_prix` float(15,5) NOT NULL DEFAULT '0.00000',
  `total_prix_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `quantite` int(11) NOT NULL DEFAULT '0',
  `percent_remise_produit` float(10,2) NOT NULL DEFAULT '0.00',
  `remise` float(15,5) NOT NULL DEFAULT '0.00000',
  `remise_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `tva` float(15,5) NOT NULL DEFAULT '0.00000',
  `tva_percent` float(5,2) NOT NULL DEFAULT '0.00',
  `couleur` varchar(150) NOT NULL DEFAULT '',
  `taille` varchar(150) NOT NULL DEFAULT '',
  `couleur_id` int(11) NOT NULL DEFAULT '0',
  `taille_id` int(11) NOT NULL DEFAULT '0',
  `etat_stock` tinyint(1) NOT NULL DEFAULT '0',
  `delai_stock` varchar(100) NOT NULL DEFAULT '',
  `order_stock` int(11) NOT NULL DEFAULT '0',
  `prix_option` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_option_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `points` int(11) NOT NULL DEFAULT '0',
  `poids` float(10,2) NOT NULL DEFAULT '0.00',
  `email_check` varchar(255) NOT NULL DEFAULT '',
  `on_download` tinyint(1) NOT NULL DEFAULT '0',
  `statut_envoi` varchar(255) NOT NULL DEFAULT '',
  `nb_envoi` int(11) NOT NULL DEFAULT '0',
  `nb_download` int(11) NOT NULL DEFAULT '0',
  `date_download` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ecotaxe_ttc` float(10,5) NOT NULL DEFAULT '0.00000',
  `ecotaxe_ht` float(10,5) NOT NULL DEFAULT '0.00000',
  `attributs_list` MEDIUMTEXT NOT NULL,
  `nom_attribut` MEDIUMTEXT NOT NULL,
  `total_prix_attribut` float(15,5) NOT NULL DEFAULT '0.00000',
  `statut` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `commande_id` (`commande_id`),
  KEY `produit_id` (`produit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_commandes_articles`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_configuration`
--

CREATE TABLE IF NOT EXISTS `peel_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `origin` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT '',
  `string` text NOT NULL,
  `lang` varchar(2) NOT NULL DEFAULT '',
  `last_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `explain` text NOT NULL,
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `technical_code` (`technical_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `peel_configuration`
--

INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
(1, 'compatibility_mode_with_htmlentities_encoding_content', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', 'Si true : permet de décoder les données en BDD encodées par des versions de PEEL < 5.7.  Mettre à false pour une nouvelle boutique, et à true si des données ont été migrées', 1),
(2, 'post_variables_with_html_allowed_if_not_admin', 'core', 'array', '"description"', '', '2013-01-01 12:00:00', 'Protection générale supplémentaire en complément de nohtml_real_escape_string', 1),
(3, 'order_article_order_by', 'core', 'string', 'id', '', '2013-01-01 12:00:00', 'Spécifie l''ordre des produits dans une commande, s''applique sur l''ensemble de la boutique', 1),
(4, 'allow_command_product_ongift', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', 'Permet aux produits cadeaux (champ on_gift dans peel_produits) d''être également commandés comme des produits ordinaire, en plus d''être commandé avec les points cadeaux.', 1),
(5, 'uploaded_file_max_size', 'core', 'integer', '4194304', '', '2013-01-01 12:00:00', 'En octets / in bytes => Par défaut 4Mo / Au delà de cette limite, les fichiers ne seront pas acceptés', 1),
(6, 'filesize_limit_keep_origin_file', 'core', 'integer', '300000', '', '2013-01-01 12:00:00', 'Taille limite au delà de laquelle les images téléchargées sont regénérées par PHP et sauvegardées en JPG', 1),
(7, 'image_max_width', 'core', 'integer', '1024', '', '2013-01-01 12:00:00', 'Taille limite au delà de laquelle les images téléchargées sont converties en JPG à cette largeur maximum', 1),
(8, 'image_max_height', 'core', 'integer', '768', '', '2013-01-01 12:00:00', 'Taille limite au delà de laquelle les images téléchargées sont converties en JPG à cette hauteur maximum', 1),
(9, 'jpeg_quality', 'core', 'integer', '88', '', '2013-01-01 12:00:00', 'Qualité pour les JPEG créés par le serveur / PHP default for JPEG quality: 75', 1),
(10, 'session_cookie_basename', 'core', 'string', 'sid', '', '2013-01-01 12:00:00', 'Sera complété par un hash de 8 caractères correspondant au chemin d''installation de cette boutique', 1),
(11, 'sha256_encoding_salt', 'core', 'string', 'k)I8#;z=TIxnXmIPdW2TRzt4Ov89|#V~cU@]', '', '2013-01-01 12:00:00', 'Used in password hash calculation. If you change it, old passwords will not be compatible anymore.', 1),
(12, 'backoffice_directory_name', 'core', 'string', 'administrer', '', '2013-01-01 12:00:00', 'Le nom du répertoire d''administration peut être changé, mais dans ce cas il faut aussi le changer manuellement dans l''arborescence du site sur le disque dur', 1),
(13, 'cache_folder', 'core', 'string', 'cache', '', '2013-01-01 12:00:00', 'Le nom du répertoire de cache peut être changé, mais dans ce cas il faut aussi le changer manuellement sur le disque dur.', 1),
(14, 'force_display_reseller_prices_without_taxes', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', '', 1),
(15, 'delivery_cost_calculation_mode', 'core', 'string', 'cheapest', '', '2013-01-01 12:00:00', 'Par défaut : on prend les frais de port les moins chers qui correspondent aux contraintes poids / montant du caddie', 1),
(16, 'force_sessions_for_subdomains', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', 'Par défaut les cookies ne sont valables que pour un sous-domaine donné (exemple : www). C''est bien de faire cela par défaut car parfois cookie_domain bloque le déclenchement des sessions chez certains hébergeurs comme 1and1. Pour rendre disponible les cookies pour tous les sous-domaines mettez à true\r\n', 1),
(17, 'admin_fill_empty_bill_number_by_number_format', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', 'Dans l''édition de facture, si numéro de facture vide, on remplit par défaut automatiquement format de numéro à générer', 1),
(18, 'payment_status_create_bill', 'core', 'string', '1,2,3', '', '2013-01-01 12:00:00', 'Dès qu''une commande est dans le statut $payment_status_create_bill, son numéro de facture est créé', 1),
(19, 'smarty_avoid_check_template_files_update', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', 'Passer à true si vous voulez accélérer un site en production. Attention : si true, alors les modifications que vous ferez sur les templates nécessiteront MAJ manuelle du cache', 1),
(20, 'use_database_permanent_connection', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', 'Valeurs possibles / Possible values : true, ''local'', ''no'' / false', 1),
(21, 'allow_w3c_validator_access_admin', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', 'ATTENTION SECURITE : cette valeur doit rester à false sauf cas exceptionnel de test technique de l''administration / SECURITY WARNING: this value must stay set to false, unless for administration technical tests', 1),
(22, 'rating_max_value', 'core', 'integer', '5', '', '2013-01-01 12:00:00', 'Nombre d''étoiles pour les votes / The number of stars allowed for voting', 1),
(23, 'rating_unitwidth', 'core', 'integer', '21', '', '2013-01-01 12:00:00', 'Largeur en pixels de chaque étoile de vote / The width (in pixels) of each rating unit (star, etc.)', 1),
(24, 'sessions_duration', 'core', 'integer', '180', '', '2013-01-01 12:00:00', 'Durée des sessions utilisateurs en minutes / User sessions duration in minutes (default : 180 min => 3h)', 1),
(25, 'display_errors_for_ips', 'core', 'string', '', '', '2013-01-01 12:00:00', 'Liste d''IPs, séparées par des espaces ou des virgules, pour lesquelles les erreurs PHP et SQL sont affichées / List of IPs, separated by space or comma, for which SQL & PHP errors are displayed', 1),
(26, 'quotation_delay', 'core', 'string', '6 mois', '', '2013-01-01 12:00:00', '', 1),
(27, 'avoir', 'core', 'integer', '10', '', '2013-01-01 12:00:00', '', 1),
(28, 'commission_affilie', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(29, 'id', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(30, 'css', 'core', 'string', 'screen.css,menu.css', '', '2013-01-01 12:00:00', '', 1),
(31, 'template_directory', 'core', 'string', 'peel7', '', '2013-01-01 12:00:00', '', 1),
(32, 'template_multipage', 'core', 'string', 'default_1', '', '2013-01-01 12:00:00', '', 1),
(33, 'email_paypal', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(34, 'email_commande', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(35, 'email_webmaster', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(36, 'nom_expediteur', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(37, 'email_client', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(38, 'on_logo', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(39, 'favicon', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(40, 'timemax', 'core', 'integer', '1800', '', '2013-01-01 12:00:00', '', 1),
(41, 'pays_exoneration_tva', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(42, 'seuil', 'core', 'integer', '5', '', '2013-01-01 12:00:00', '', 1),
(43, 'seuil_total', 'core', 'float', '0.00000', '', '2013-01-01 12:00:00', '', 1),
(44, 'seuil_total_reve', 'core', 'float', '0.00000', '', '2013-01-01 12:00:00', '', 1),
(45, 'module_retail', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(46, 'module_affilie', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(47, 'module_lot', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(48, 'module_parrain', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(49, 'module_cadeau', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(50, 'module_devise', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(51, 'devise_defaut', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(52, 'module_nuage', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(53, 'module_flash', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(54, 'module_cart_preservation', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(55, 'module_vacances', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(56, 'module_vacances_type', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(57, 'module_vacances_fournisseur', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(58, 'module_pub', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(59, 'module_rss', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(60, 'module_avis', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(61, 'module_precedent_suivant', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(62, 'module_faq', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(63, 'module_forum', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(64, 'module_giftlist', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(65, 'module_entreprise', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(66, 'sips', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(67, 'systempay_payment_count', 'core', 'string', '1', '', '2013-01-01 12:00:00', '', 1),
(68, 'systempay_payment_period', 'core', 'string', '0', '', '2013-01-01 12:00:00', '', 1),
(69, 'systempay_cle_test', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(70, 'systempay_cle_prod', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(71, 'systempay_test_mode', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', '', 1),
(72, 'systempay_code_societe', 'core', 'string', '0', '', '2013-01-01 12:00:00', '', 1),
(73, 'paybox_cgi', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(74, 'paybox_site', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(75, 'paybox_rang', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(76, 'paybox_identifiant', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(77, 'email_moneybookers', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(78, 'secret_word', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(79, 'spplus', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(80, 'module_ecotaxe', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(81, 'module_filtre', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(82, 'nb_produit_page', 'core', 'integer', '10', '', '2013-01-01 12:00:00', '', 1),
(83, 'module_rollover', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(84, 'type_rollover', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(85, 'logo_affiliation', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(86, 'small_width', 'core', 'integer', '160', '', '2013-01-01 12:00:00', '', 1),
(87, 'small_height', 'core', 'integer', '160', '', '2013-01-01 12:00:00', '', 1),
(88, 'medium_width', 'core', 'integer', '300', '', '2013-01-01 12:00:00', '', 1),
(89, 'medium_height', 'core', 'integer', '300', '', '2013-01-01 12:00:00', '', 1),
(90, 'mode_transport', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(91, 'module_url_rewriting', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(92, 'display_prices_with_taxes', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(93, 'display_prices_with_taxes_in_admin', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(94, 'html_editor', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(95, 'format_numero_facture', 'core', 'string', '[id]', '', '2013-01-01 12:00:00', '', 1),
(96, 'default_country_id', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(97, 'nb_product', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(98, 'nb_on_top', 'core', 'integer', '5', '', '2013-01-01 12:00:00', '', 1),
(99, 'nb_last_views', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(100, 'auto_promo', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(101, 'act_on_top', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(102, 'tag_analytics', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(103, 'site_suspended', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', '', 1),
(104, 'small_order_overcost_limit', 'core', 'float', '0.00000', '', '2013-01-01 12:00:00', '', 1),
(105, 'small_order_overcost_amount', 'core', 'float', '0.00000', '', '2013-01-01 12:00:00', '', 1),
(106, 'small_order_overcost_tva_percent', 'core', 'float', '0.00', '', '2013-01-01 12:00:00', '', 1),
(107, 'module_captcha', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(108, 'allow_add_product_with_no_stock_in_cart', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(109, 'payment_status_decrement_stock', 'core', 'string', '2,3', '', '2013-01-01 12:00:00', '', 1),
(110, 'module_socolissimo', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(111, 'module_icirelais', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(112, 'module_autosend', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(113, 'module_autosend_delay', 'core', 'integer', '5', '', '2013-01-01 12:00:00', '', 1),
(114, 'category_count_method', 'core', 'string', 'individual', '', '2013-01-01 12:00:00', '', 1),
(115, 'partner_count_method', 'core', 'string', 'individual', '', '2013-01-01 12:00:00', '', 1),
(116, 'admin_force_ssl', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(117, 'anim_prod', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(118, 'export_encoding', 'core', 'string', 'utf-8', '', '2013-01-01 12:00:00', '', 1),
(119, 'zoom', 'core', 'string', 'jqzoom', '', '2013-01-01 12:00:00', '', 1),
(120, 'enable_prototype', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(121, 'enable_jquery', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(122, 'send_email_active', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(123, 'minimal_amount_to_order', 'core', 'string', '0.00000', '', '2013-01-01 12:00:00', '', 1),
(124, 'display_nb_product', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(125, 'type_affichage_attribut', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(126, 'fb_admins', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(127, 'facebook_page_link', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(128, 'category_order_on_catalog', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(129, 'global_remise_percent', 'core', 'float', '0.00000', '', '2013-01-01 12:00:00', '', 1),
(130, 'availability_of_carrier', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(131, 'popup_width', 'core', 'integer', '310', '', '2013-01-01 12:00:00', '', 1),
(132, 'popup_height', 'core', 'integer', '160', '', '2013-01-01 12:00:00', '', 1),
(133, 'in_category', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(134, 'facebook_connect', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(135, 'fb_appid', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(136, 'fb_secret', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(137, 'fb_baseurl', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(138, 'module_conditionnement', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(139, 'keep_old_orders_intact', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(140, 'default_picture', 'core', 'string', 'image_defaut_peel.png', '', '2013-01-01 12:00:00', '', 1),
(149, 'module_tnt', 'core', 'integer', '0', '', '2013-01-01 12:00:00', '', 1),
(150, 'sign_in_twitter', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(151, 'googlefriendconnect', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(152, 'session_save_path', 'core', 'string', '', '', '2013-01-01 12:00:00', 'Répertoire sur le disque pour stocker les sessions. Exemple : /home/example/sessions . Attention : ce répertoire en doit pas être accessible par http => il ne doit pas être à l''intérieur de votre répertoire peel. Laisser vide si on veut le répertoire défini par défaut dans php.ini du serveur', 1),
(153, 'general_print_image', 'core', 'string', '{$GLOBALS[''repertoire_images'']}/imprimer.jpg', '', '2013-01-01 12:00:00', '', 1),
(154, 'general_home_image1', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(155, 'general_home_image2', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(156, 'general_product_image', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(157, 'general_send_email_image', 'core', 'string', '{$GLOBALS[''repertoire_images'']}/tell_friend.jpg', '', '2013-01-01 12:00:00', '', 1),
(158, 'general_give_your_opinion_image', 'core', 'string', '{$GLOBALS[''repertoire_images'']}/donnez_avis.jpg', '', '2013-01-01 12:00:00', '', 1),
(159, 'general_read_all_reviews_image', 'core', 'string', '{$GLOBALS[''repertoire_images'']}/tous_les_avis.jpg', '', '2013-01-01 12:00:00', '', 1),
(160, 'general_add_notepad_image', 'core', 'string', '{$GLOBALS[''repertoire_images'']}/ajout_pense_bete.jpg', '', '2013-01-01 12:00:00', '', 1),
(161, 'check_allowed_types', 'auto', 'boolean', 'false', '', '2013-01-01 12:00:00', 'Vous pouvez activer une vérification du type MIME des fichiers téléchargés. Cela pose de nombreux problèmes car cette information n''est pas fiable et des navigateurs envoient des types MIME parfois imprévus => cette vérification est désactivée par défaut', 1),
(162, 'allowed_types', 'auto', 'array', '"image/gif" => ".gif", "image/pjpeg" => ".jpg, .jpeg", "image/jpeg" => ".jpg, .jpeg", "image/x-png" => ".png", "image/png" => ".png", "text/plain" => ".html, .php, .txt, .inc, .csv", "text/comma-separated-values" => ".csv", "application/comma-separated-values" => ".csv", "text/csv" => ".csv", "application/vnd.ms-excel" => ".csv", "application/csv-tab-delimited-table" => ".csv", "application/octet-stream" => "", "application/pdf" => ".pdf", "application/force-download" => "", "application/x-shockwave-flash" => ".swf", "application/x-download" => "', '', '2013-01-01 12:00:00', 'Cette variable est utilisée si check_allowed_types = true', 1),
(163, 'extensions_valides_any', 'auto', 'array', '"jpg", "jpeg", "gif", "png", "csv", "txt", "pdf", "zip"', '', '2013-01-01 12:00:00', 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(164, 'extensions_valides_data', 'auto', 'array', '"csv", "txt"', '', '2013-01-01 12:00:00', 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(165, 'extensions_valides_image_or_pdf', 'auto', 'array', '"jpg", "jpeg", "gif", "png", "pdf"', '', '2013-01-01 12:00:00', 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(166, 'extensions_valides_image', 'auto', 'array', '"jpg", "jpeg", "gif", "png"', '', '2013-01-01 12:00:00', 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(167, 'extensions_valides_image_or_swf', 'auto', 'array', '"jpg", "jpeg", "gif", "png", "swf"', '', '2013-01-01 12:00:00', 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(168, 'extensions_valides_image_or_ico', 'auto', 'array', '"jpg", "jpeg", "gif", "png", "ico"', '', '2013-01-01 12:00:00', 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(169, 'uploaded_images_name_pattern', 'core', 'string', '^[0-9]{6}_[0-9]{6}_PEEL_[0-9a-z-A-Z]{8}\\.[jpg|png|gif]$', '', '2013-01-01 12:00:00', 'Permet de valider le format des noms des images uploadées dans peel', 1),
(170, 'site_general_columns_count', 'core', 'integer', '3', '', '2013-01-01 12:00:00', '', 1),
(171, 'product_details_page_columns_count', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(172, 'ad_details_page_columns_count', 'core', 'integer', '3', '', '2013-01-01 12:00:00', '', 1),
(173, 'ads_list_page_columns_count', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(174, 'blog_index_page_columns_count', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(175, 'listecadeau_list_page_columns_count', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(176, 'listecadeau_details_page_columns_count', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(177, 'cart_preservation_page_columns_count', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(178, 'references_page_columns_count', 'core', 'integer', '1', '', '2013-01-01 12:00:00', '', 1),
(179, 'achat_maintenant_page_columns_count', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(180, 'caddie_affichage_page_columns_count', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(181, 'fin_commande_page_columns_count', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(182, 'achat_index_page_columns_count', 'core', 'integer', '2', '', '2013-01-01 12:00:00', '', 1),
(183, 'edit_prices_on_products_list', 'core', 'string', 'edit', '', '2013-01-01 12:00:00', '', 1),
(184, 'show_qrcode_on_product_pages', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', '', 1),
(185, 'minify_css', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', 'Concatenation automatique des fichiers CSS pour plus de rapidité du site - ATTENTION : nécessite suppression du cache manuellement en cas de modification des fichiers CSS / Automatic merge of CSS files in order to speed up pages loading - NOTICE : it is mandatory to delete cache files manually after any CSS file modification', 1),
(186, 'minify_js', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', 'Concatenation automatique des fichiers JS pour plus de rapidité du site, sauf pour jquery à cause de problèmes de compatiblité - ATTENTION : nécessite suppression du cache manuellement en cas de modification des fichiers JS / Automatic merge of JS files in order to speed up pages loading, excepted for jquery files due to compatibility problems - NOTICE : it is mandatory to delete cache files manually after any JS file modification', 1),
(187, 'product_categories_depth_in_menu', 'core', 'integer', '1', '', '2013-01-01 12:00:00', 'Profondeur du menu de catégories de produits. NB : Seules les catégories de produits avec position>0 s''afficheront, ce qui permet d''en exclure du menu en les mettant à position=0', 1),
(188, 'content_categories_depth_in_menu', 'core', 'integer', '1', '', '2013-01-01 12:00:00', 'Profondeur du menu de rubriques de contenu. NB : Seules les rubriques de contenu avec position>0 s''afficheront, ce qui permet d''en exclure du menu en les mettant à position=0', 1),
(189, 'main_menu_items_if_available', 'core', 'array', '"home", "catalog", "news", "promotions", "annonces", "vitrine", "check", "account", "contact", "admin"', '', '2013-01-01 12:00:00', 'Liste à définir dans l''ordre d''affichage parmi : "home", "catalog", "content", "news", "promotions", "annonces", "vitrine", "check", "account", "contact", "promotions", "admin"', 1),
(190, 'template_engine', 'core', 'string', 'smarty', '', '2013-01-01 12:00:00', 'Par défaut : smarty - Existe aussi en version de test : twig', 1),
(191, 'catalog_products_columns_default', 'core', 'integer', '3', '', '2013-01-01 12:00:00', '', 1),
(192, 'associated_products_columns_default', 'core', 'integer', '3', '', '2013-01-01 12:00:00', '', 1),
(193, 'associated_products_display_mode', 'core', 'string', 'column', '', '2013-01-01 12:00:00', '', 1),
(194, 'show_on_estimate_text', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', '', 1),
(195, 'show_add_to_cart_on_free_products', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', '', 1),
(196, 'show_short_description_on_product_details', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', '', 1),
(197, 'category_show_more_on_catalog_if_no_order_allowed', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', '', 1),
(198, 'show_on_affiche_guide', 'core', 'array', '"contact", "affiliate", "retailer", "faq", "forum", "lexique", "partner", "references", "access_plan"', '', '2013-01-01 12:00:00', 'Liste à définir dans l''ordre d''affichage parmi : "contact", "affiliate", "retailer", "faq", "forum", "lexique", "partner", "references", "access_plan"', 1),
(199, 'replace_words_in_lang_files', 'core', 'string', '', '', '2013-01-01 12:00:00', '', 1),
(200, 'twitter_page_link', 'core', 'string', '', '', '2013-05-01 12:00:00', '', 1),
(201, 'googleplus_page_link', 'core', 'string', '', '', '2013-05-01 12:00:00', '', 1),
(202, 'skip_images_keywords', 'core', 'array', '', '', '2013-05-01 12:00:00', '', 1),
(203, 'appstore_link', 'core', 'string', '', '', '2013-05-01 12:00:00', '', 1),
(223, 'categories_side_menu_item_max_length', 'core', 'integer', '28', '', '2013-05-01 12:00:00', '', 1),
(224, 'phone_cti_primary_site_list_calls_url', 'core', 'string', '', '', '2013-05-01 12:00:00', '', 1),
(225, 'email_accounts_for_bounces_handling', 'core', 'array', '', '', '2013-05-01 12:00:00', 'Format : ''email'' => ''password''', 1),
(226, 'tagcloud_display_count', 'core', 'integer', '12', '', '2013-05-01 12:00:00', '', 1),
(229, 'filter_stop_words', 'core', 'string', 'afin aie aient aies ailleurs ainsi ait alentour alias allaient allais allait allez allons alors apres aprs assez attendu aucun aucune aucuns audit aujourd aujourdhui auparavant auprs auquel aura aurai auraient aurais aurait auras aurez auriez aurions aurons auront aussi aussitot autant autour autre autrefois autres autrui aux auxdites auxdits auxquelles auxquels avaient avais avait avant avec avez aviez avions avoir avons ayant ayez ayons bah banco bas beaucoup ben bien bientot bis bon caha cahin car ceans ceci cela celle celles celui cent cents cependant certain certaine certaines certains certes ces cet cette ceux cgr chacun chacune champ chaque cher chez cinq cinquante combien comme comment contrario contre crescendo dabord daccord daffilee dailleurs dans daprs darrache davantage debout debut dedans dehors deja dela demain demblee depuis derechef derriere des desdites desdits desormais desquelles desquels dessous dessus deux devant devers devrait die differentes differents dire dis disent dit dito divers diverses dix doit donc dont dorenavant dos douze droite dudit duquel durant elle elles encore enfin ensemble ensuite entre envers environ essai est et etaient etais etait etant etat etc ete etes etiez etions etre eue eues euh eûmes eurent eus eusse eussent eusses eussiez eussions eut eutes eux expres extenso extremis facto faire fais faisaient faisais faisait faisons fait faites fallait faudrait faut flac fois font force fors fort forte fortiori frais fumes fur furent fus fusse fussent fusses fussiez fussions fut futes ghz grosso gure han haut hein hem heu hier hola hop hormis hors hui huit hum ibidem ici idem illico ils ipso item jadis jamais jusqu jusqua jusquau jusquaux jusque juste km² laquelle lautre lequel les lesquelles lesquels leur leurs lez loin lon longtemps lors lorsqu lorsque lot lots lui lun lune maint mainte maintenant maintes maints mais mal malgre meme memes mes mgr mhz mieux mil mille milliards millions mine minima mm² modo moi moins mon mot moult moyennant naguere neanmoins neuf nommes non nonante nonobstant nos notre nous nouveau nouveaux nouvelle nouvelles nul nulle octante ont onze ouais ou oui outre par parbleu parce parfois parmi parole partout pas passe passim pendant personne personnes petto peu peut peuvent peux piece pied pis plupart plus plusieurs plutot point posteriori pour pourquoi pourtant prealable presqu presque primo priori prix prou prs puis puisqu puisque quand quarante quasi quatorze quatre que quel quelle quelles quelqu quelque quelquefois quelques quelquun quelquune quels qui quiconque quinze quoi quoiqu quoique ref refs revoici revoila rien sans sauf secundo seize selon sensu sept septante sera serai seraient serais serait seras serez seriez serions serons seront ses seulement sic sien sine sinon sitot situ six soi soient soixante sommes son sont soudain sous souvent soyez soyons stricto suis sujet sur surtout sus tandis tant tantot tard tel telle tellement telles tels temps ter tes toi ton tot toujours tous tout toute toutefois toutes treize trente tres trois trop trs une unes uns usd vais valeur vas vends vers versa veut veux via vice vingt vingts vingt vis vite vitro vivo voici voie voient voila voire volontiers vont vos votre vous zero', 'fr', '2013-05-01 12:00:00', 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatifs.', 1),
(230, 'filter_stop_words', 'core', 'string', 'a able about above abst accordance according accordingly across act actually added adj affected affecting affects after afterwards again against ah all almost alone along already also although always am among amongst an and announce another any anybody anyhow anymore anyone anything anyway anyways anywhere apparently approximately are aren arent arise around as aside ask asking at auth available away awfully b back be became because become becomes becoming been before beforehand begin beginning beginnings begins behind being believe below beside besides between beyond biol both brief briefly but by c ca came can cannot can''t cause causes certain certainly co com come comes contain containing contains could couldnt d date did didn''t different do does doesn''t doing done don''t down downwards due during e each ed edu effect eg eight eighty either else elsewhere end ending enough especially et et-al etc even ever every everybody everyone everything everywhere ex except f far few ff fifth first five fix followed following follows for former formerly forth found four from further furthermore g gave get gets getting give given gives giving go goes gone got gotten h had happens hardly has hasn''t have haven''t having he hed hence her here hereafter hereby herein heres hereupon hers herself hes hi hid him himself his hither home how howbeit however hundred i id ie if i''ll im immediate immediately importance important in inc indeed index information instead into invention inward is isn''t it itd it''ll its itself i''ve j just k keep 	keeps kept kg km know known knows l largely last lately later latter latterly least less lest let lets like liked likely line little ''ll look looking looks ltd m made mainly make makes many may maybe me mean means meantime meanwhile merely mg might million miss ml more moreover most mostly mr mrs much mug must my myself n na name namely nay nd near nearly necessarily necessary need needs neither never nevertheless new next nine ninety no nobody non none nonetheless noone nor normally nos not noted nothing now nowhere o obtain obtained obviously of off often oh ok okay old omitted on once one ones only onto or ord other others otherwise ought our ours ourselves out outside over overall owing own p page pages part particular particularly past per perhaps placed please plus poorly possible possibly potentially pp predominantly present previously primarily probably promptly proud provides put q que quickly quite qv r ran rather rd re readily really recent recently ref refs regarding regardless regards related relatively research respectively resulted resulting results right run s said same saw say saying says sec section see seeing seem seemed seeming seems seen self selves sent seven several shall she shed she''ll shes should shouldn''t show showed shown showns shows significant significantly similar similarly since six slightly so some somebody somehow someone somethan something sometime sometimes somewhat somewhere soon sorry specifically specified specify specifying still stop strongly sub substantially successfully such sufficiently suggest sup sure 	t take taken taking tell tends th than thank thanks thanx that that''ll thats that''ve the their theirs them themselves then thence there thereafter thereby thered therefore therein there''ll thereof therere theres thereto thereupon there''ve these they theyd they''ll theyre they''ve think this those thou though thoughh thousand throug through throughout thru thus til tip to together too took toward towards tried tries truly try trying ts twice two u un under unfortunately unless unlike unlikely until unto up upon ups us use used useful usefully usefulness uses using usually v value various ''ve very via viz vol vols vs w want wants was wasn''t way we wed welcome we''ll went were weren''t we''ve what whatever what''ll whats when whence whenever where whereafter whereas whereby wherein wheres whereupon wherever whether which while whim whither who whod whoever whole who''ll whom whomever whos whose why widely willing wish with within without won''t words world would wouldn''t www x y yes yet you youd you''ll your youre yours yourself yourselves you''ve z zero', 'en', '2013-05-01 12:00:00', 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatifs.', 1),
(231, 'filter_stop_words', 'core', 'string', 'ab aber abgerufen abgerufene abgerufener abgerufenes acht ahnlich alle allein allem allen aller allerdings allerlei alles allgemein allmahlich allzu als alsbald also am an ander andere anderem anderen anderer andererseits anderes anderm andern andernfalls anders anerkannt anerkannte anerkannter anerkanntes anfangen anfing angefangen angesetze angesetzt angesetzten angesetzter ansetzen anstatt arbeiten auch auf aufgehort aufgrund aufhoren aufhorte aufzusuchen aus ausdrucken ausdruckt ausdruckte ausgenommen außen ausser außer ausserdem außerdem außerhalb author autor bald bearbeite bearbeiten bearbeitete bearbeiteten bedarf bedurfen bedurfte befragen befragte befragten befragter begann beginnen begonnen behalten behielt bei beide beiden beiderlei beides beim beinahe beitragen beitrugen bekannt bekannte bekannter bekennen benutzt bereits berichten berichtet berichtete berichteten besonders besser bestehen besteht betrachtlich bevor bezuglich bietet bin bis bis bisher bislang bist bleiben blieb bloss bloß boden brachte brachten brauchen braucht brauchte bringen bsp bzw ca da dabei dadurch dafur dagegen daher dahin damals damit danach daneben dank danke danken dann dannen daran darauf daraus darf darfst darin daruber daruberhinaus darum darunter das dass daß dasselbe davon davor dazu dein deine deinem deinen deiner deines dem demnach demselben den denen denn dennoch denselben der derart derartig derem deren derer derjenige derjenigen derselbe derselben derzeit des deshalb desselben dessen desto deswegen dich die diejenige dies diese dieselbe dieselben diesem diesen dieser dieses diesseits dinge dir direkt direkte direkten direkter doch doppelt dort dorther dorthin drauf drei dreißig drin dritte druber drunter du dunklen durch durchaus durfen durfte durfte durften eben ebenfalls ebenso ehe eher eigenen eigenes eigentlich ein einbaun eine einem einen einer einerseits eines einfach einfuhren einfuhrte einfuhrten eingesetzt einig einige einigem einigen einiger einigermaßen einiges einmal eins einseitig einseitige einseitigen einseitiger einst einstmals einzig ende entsprechend entweder er erganze erganzen erganzte erganzten erhalt erhalten erhielt erhielten erneut eroffne eroffnen eroffnet eroffnete eroffnetes erst erste ersten erster es etc etliche etwa etwas euch euer eure eurem euren eurer eures fall falls fand fast ferner finden findest findet folgende folgenden folgender folgendes folglich fordern fordert forderte forderten fortsetzen fortsetzt fortsetzte fortsetzten fragte frau frei freie freier freies fuer funf fur gab gangig gangige gangigen gangiger gangiges ganz ganze ganzem ganzen ganzer ganzes ganzlich gar gbr geb geben geblieben gebracht gedurft geehrt geehrte geehrten geehrter gefallen gefalligst gefallt gefiel gegeben gegen gehabt gehen geht gekommen gekonnt gemacht gemass gemocht genommen genug gern gesagt gesehen gestern gestrige getan geteilt geteilte getragen gewesen gewissermaßen gewollt geworden ggf gib gibt gleich gleichwohl gleichzeitig glucklicherweise gmbh gratulieren gratuliert gratulierte gute guten hab habe haben haette halb hallo hast hat hatt hatte hatte hatten hatten hattest hattet hen heraus herein heute heutige hier hiermit hiesige hin hinein hinten hinter hinterher hoch hochstens hundert ich igitt ihm ihn ihnen ihr ihre ihrem ihren ihrer ihres im immer immerhin important in indem indessen info infolge innen innerhalb ins insofern inzwischen irgend irgendeine irgendwas irgendwen irgendwer irgendwie irgendwo ist ja jahrig jahrige jahrigen jahriges je jede jedem jeden jedenfalls jeder jederlei jedes jedoch jemand jene jenem jenen jener jenes jenseits jetzt kam kann kannst kaum kein keine keinem keinen keiner keinerlei keines keines keineswegs klar klare klaren klares klein kleinen kleiner kleines koennen koennt koennte koennten komme kommen kommt konkret konkrete konkreten konkreter konkretes konn konnen konnt konnte konnte konnten konnten kunftig lag lagen langsam langst langstens lassen laut lediglich leer legen legte legten leicht leider lesen letze letzten letztendlich letztens letztes letztlich lichten liegt liest links mache machen machst macht machte machten mag magst mal man manche manchem manchen mancher mancherorts manches manchmal mann margin mehr mehrere mein meine meinem meinen meiner meines meist meiste meisten meta mich mindestens mir mit mithin mochte mochte mochten mochtest mogen moglich mogliche moglichen moglicher moglicherweise morgen morgige muessen muesst muesste muss muß mussen musst mußt mußt musste musste mußte mussten mussten nach nachdem nacher nachhinein nachste nacht nahm namlich naturlich neben nebenan nehmen nein neu neue neuem neuen neuer neues neun nicht nichts nie niemals niemand nimm nimmer nimmt nirgends nirgendwo noch notigenfalls nun nur nutzen nutzt nutzt nutzung ob oben oberhalb obgleich obschon obwohl oder oft ohne per pfui plotzlich pro reagiere reagieren reagiert reagierte rechts regelmaßig rief rund sage sagen sagt sagte sagten sagtest samtliche sang sangen schatzen schatzt schatzte schatzten schlechter schließlich schnell schon schreibe schreiben schreibens schreiber schwierig sechs sect sehe sehen sehr sehrwohl seht sei seid sein seine seinem seinen seiner seines seit seitdem seite seiten seither selber selbst senke senken senkt senkte senkten setzen setzt setzte setzten sich sicher sicherlich sie sieben siebte siehe sieht sind singen singt so sobald sodaß soeben sofern sofort sog sogar solange solc solch solche solchem solchen solcher solches soll sollen sollst sollt sollte sollten solltest somit sondern sonst sonstwo sooft soviel soweit sowie sowohl spater spielen startet startete starteten statt stattdessen steht steige steigen steigt stets stieg stiegen such suchen tages tat tat tatsachlich tatsachlichen tatsachlicher tatsachliches tausend teile teilen teilte teilten titel total trage tragen tragt trotzdem trug tun tust tut txt ubel uber uberall uberallhin uberdies ubermorgen ubrig ubrigens ueber um umso unbedingt und ungefahr unmoglich unmogliche unmoglichen unmoglicher unnotig uns unse unsem unsen unser unser unsere unserem unseren unserer unseres unserm unses unten unter unterbrach unterbrechen unterhalb unwichtig usw vergangen vergangene vergangener vergangenes vermag vermogen vermutlich veroffentlichen veroffentlicher veroffentlicht veroffentlichte veroffentlichten veroffentlichtes verrate verraten verriet verrieten version versorge versorgen versorgt versorgte versorgten versorgtes viel viele vielen vieler vieles vielleicht vielmals vier vollig vollstandig vom von vor voran vorbei vorgestern vorher vorne voruber wachen waere wahrend wahrend wahrenddessen wann war war ware waren waren warst warum was weder weg wegen weil weiß weiter weitere weiterem weiteren weiterer weiteres weiterhin welche welchem welchen welcher welches wem wen wenig wenige weniger wenigstens wenn wenngleich wer werde werden werdet weshalb wessen wichtig wie wieder wieso wieviel wiewohl will willst wir wird wirklich wirst wo wodurch wogegen woher wohin wohingegen wohl wohlweislich wolle wollen wollt wollte wollten wolltest wolltet womit woraufhin woraus worin wurde wurde wurden wurden zahlreich zB zehn zeitweise ziehen zieht zog zogen zu zudem zuerst zufolge zugleich zuletzt zum zumal zur zuruck zusammen zuviel zwanzig zwar zwei zwischen zwolf', 'de', '2013-05-01 12:00:00', 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatifs.', 1),
(232, 'filter_stop_words', 'core', 'string', 'algun alguna algunas alguno algunos ambos ampleamos ante antes aquel aquellas aquellos aqui arriba atras bajo bastante bien cada cierta ciertas cierto ciertos como con conseguimos conseguir consigo consigue consiguen consigues cual cuando dentro desde donde dos el ellas ellos empleais emplean emplear empleas empleo en encima entonces entre era eramos eran eras eres es esta estaba estado estais estamos estan estoy fin fue fueron fui fuimos gueno ha hace haceis hacemos hacen hacer haces hago incluso intenta intentais intentamos intentan intentar intentas intento ir la largo las lo los mientras mio modo muchos muy nos nosotros otro para pero podeis podemos poder podria podriais podriamos podrian podrias por por qué porque primero  puede pueden puedo quien sabe sabeis sabemos saben saber sabes ser si siendo sin sobre sois solamente solo somos soy su sus también teneis tenemos tener tengo tiempo tiene tienen todo trabaja trabajais trabajamos trabajan trabajar trabajas trabajo tras tuyo ultimo un una unas uno unos usa usais usamos usan usar usas uso va vais valor vamos van vaya verdad verdadera verdadero vosotras vosotros voy yo ', 'es', '2013-05-01 12:00:00', 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatifs.', 1),
(233, 'cron_login', 'core', 'array', '', '', '2013-05-01 12:00:00', 'Format : ''password'' => ''login''', 1),
(239, 'skip_home_top_products', 'core', 'boolean', 'false', '', '2013-05-01 12:00:00', '', 1),
(240, 'skip_home_special_products', 'core', 'boolean', 'false', '', '2013-05-01 12:00:00', '', 1),
(241, 'skip_home_new_products', 'core', 'boolean', 'false', '', '2013-05-01 12:00:00', '', 1),
(242, 'user_mandatory_fields', 'core', 'array', '"prenom" => "STR_ERR_FIRSTNAME", "nom_famille" => "STR_ERR_NAME", "adresse" => "STR_ERR_ADDRESS", "code_postal" => "STR_ERR_ZIP", "ville" => "STR_ERR_TOWN", "pays" => "STR_ERR_COUNTRY", "telephone" => "STR_ERR_TEL"', '', '2013-05-01 12:00:00', '', 1),
(243, 'skip_home_ad_categories_presentation', 'core', 'boolean', 'false', '', '2013-05-01 12:00:00', '', 1),
(244, 'article_details_index_page_columns_count', 'core', 'integer', '3', '', '2013-01-01 12:00:00', '', 1),
(245, 'lire_index_page_columns_count', 'core', 'integer', '3', '', '2013-01-01 12:00:00', '', 1),
(246, 'site_index_page_columns_count', 'core', 'integer', '3', '', '2013-01-01 12:00:00', '', 1),
(247, 'display_nb_vote_graphic_view', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', '', 1),
(248, 'display_content_category_diaporama', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', '', 1),
(249, 'subcategorie_nb_column', 'core', 'integer', '5', '', '2013-01-01 12:00:00', '', 1),
(250, 'product_category_pages_nb_column', 'core', 'integer', '3', '', '2013-01-01 12:00:00', '', 1),
(251, 'display_share_tools_on_product_pages', 'core', 'boolean', 'true', '', '2013-01-01 12:00:00', '', 1),
(252, 'prices_precision', 'core', 'integer', '2', '', '2013-01-01 12:00:00', 'Nombre de décimales pour l''affichage des prix / Decimal count for prices display', 1),
(253, 'short_order_process', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', 'Fin du process de commande, si le paramètre short_order_process est actif. Ce paramètre implique l''absence de paiement et de validation des CGV => Utile pour des demandes de devis', 1),
(254, 'use_ads_as_products', 'core', 'boolean', 'false', '', '2013-01-01 12:00:00', 'Permet d''ajouter des annonces au panier (nécessite le module d''annonce)', 1),
(255, 'tva_annonce', 'core', 'string', '19.6', '', '2013-01-01 12:00:00', 'Spécifie le taux de TVA à appliquer aux annonces lors de leur ajout au panier (fonctionne avec le paramètre use_ads_as_product).', 1);

-- --------------------------------------------------------

--
-- Structure de la table `peel_contacts`
--

CREATE TABLE IF NOT EXISTS `peel_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_contacts`
--

INSERT INTO `peel_contacts` (`id`, `date_insere`, `date_maj`) VALUES
(1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `peel_continents`
--

CREATE TABLE IF NOT EXISTS `peel_continents` (
  `id` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_continents`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_couleurs`
--

CREATE TABLE IF NOT EXISTS `peel_couleurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL DEFAULT '0',
  `mandatory` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `peel_couleurs`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_devises`
--

CREATE TABLE IF NOT EXISTS `peel_devises` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devise` varchar(50) NOT NULL DEFAULT '',
  `conversion` float(10,5) NOT NULL DEFAULT '0.00000',
  `symbole` varchar(10) NOT NULL DEFAULT '',
  `symbole_place` tinyint(1) NOT NULL DEFAULT '1',
  `code` varchar(3) NOT NULL DEFAULT '',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_devises`
--

INSERT INTO `peel_devises` (`id`, `devise`, `conversion`, `symbole`, `symbole_place`, `code`, `etat`) VALUES
(1, 'Euro', 1.00000, '€', 1, 'EUR', 1),
(2, 'CH Fr. Suisse', 1.41987, 'Fr', 1, 'CHF', 0),
(3, 'US Dollar', 1.21553, '$', 0, 'USD', 0),
(4, 'CA Dollar', 1.27708, '$', 0, 'CAD', 0),
(5, 'JP Yen', 110.56900, '¥', 1, 'JPY', 0),
(6, 'GB Pound', 0.83554, '£', 1, 'GBP', 0);

-- --------------------------------------------------------

--
-- Structure de la table `peel_diaporama`
--

CREATE TABLE IF NOT EXISTS `peel_diaporama` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_rubrique` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Structure de la table `peel_ecotaxes`
--

CREATE TABLE IF NOT EXISTS `peel_ecotaxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL DEFAULT '',
  `prix_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_ttc` float(15,5) NOT NULL DEFAULT '0.00000',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_ecotaxes`
--

INSERT INTO `peel_ecotaxes` (`id`, `code`, `prix_ht`, `prix_ttc`) VALUES
(1, '1.1', 10.87000, 13.00052),
(2, '1.2', 5.02000, 6.00392),
(3, '1.3', 1.67000, 1.99732),
(4, '1.4', 0.84000, 1.00464),
(5, '1.5', 0.42000, 0.50232),
(6, '1.6', 0.08000, 0.09568),
(7, '1.7', 3.34000, 3.99464),
(8, '1.8', 0.84000, 1.00464),
(9, '1.9', 0.42000, 0.50232),
(10, '1.10', 0.08000, 0.09568),
(11, '2.1', 0.84000, 1.00464),
(12, '2.2', 0.42000, 0.50232),
(13, '2.3', 0.08000, 0.09568),
(14, '3.1', 6.69000, 8.00124),
(15, '3.2', 3.34000, 3.99464),
(16, '3.3', 0.84000, 1.00464),
(17, '3.4', 0.84000, 1.00464),
(18, '3.5', 0.25000, 0.29900),
(19, '3.6', 0.42000, 0.50232),
(20, '3.7', 0.08000, 0.09568),
(21, '3.8', 0.01000, 0.01196),
(22, '4.1', 6.69000, 8.00124),
(23, '4.2', 3.34000, 3.99464),
(24, '4.3', 0.84000, 1.00464),
(25, '4.4', 0.84000, 1.00464),
(26, '4.5', 0.25000, 0.29900),
(27, '4.6', 0.08000, 0.09568),
(28, '6.1', 0.17000, 0.20332),
(29, '6.2', 1.25000, 1.49500),
(30, '7.1', 0.04000, 0.04784),
(31, '7.2', 0.17000, 0.20332),
(32, '7.3', 1.25000, 1.49500),
(33, '8.1', 0.84000, 1.00464),
(34, '8.2', 0.08000, 0.09568),
(35, '9.1', 0.08000, 0.09568),
(36, '9.2', 0.84000, 1.00464),
(37, '10.0', 10.87000, 13.00052);

-- --------------------------------------------------------

--
-- Structure de la table `peel_email_template`
--


CREATE TABLE IF NOT EXISTS `peel_email_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `text` mediumtext NOT NULL,
  `lang` varchar(2) NOT NULL DEFAULT '',
  `active` enum('TRUE','FALSE') NOT NULL DEFAULT 'TRUE',
  `id_cat` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_ecotaxes`
--

-- Le contenu est créé automatiquement lors de l'ajout de langue via le contenu de /lib/lang/database_email_template

-- --------------------------------------------------------

--
-- Structure de la table `peel_email_template_cat`
--

CREATE TABLE IF NOT EXISTS `peel_email_template_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_email_template_cat`
--

-- Le contenu est créé automatiquement lors de l'ajout de langue via le contenu de /lib/lang/database_email_template

-- --------------------------------------------------------

--
-- Structure de la table `peel_html`
--

CREATE TABLE IF NOT EXISTS `peel_html` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contenu_html` mediumtext NOT NULL,
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `titre` varchar(255) NOT NULL DEFAULT '',
  `o_timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `a_timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `emplacement` varchar(255) NOT NULL DEFAULT '',
  `lang` varchar(2) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_html`
--

INSERT INTO peel_html (id, lang, contenu_html, etat, titre, o_timestamp, a_timestamp, emplacement) VALUES
(1, 'fr', '<div class="header_few_words_center">[SITE]</div>\r\n<div class="header_few_words_right">Open eCommerce</div>', 1, 'En-tête du site', '2012-05-01 12:00:00', '2012-05-01 12:00:00', 'header'),
(2, 'fr', '<p>Bas de page du site personnalisable dans lequel on peut insérer des liens vers les partenaires</p>', 1, 'Bas de page du site', '2012-05-01 12:00:00', '2012-05-01 12:00:00', 'footer'),
(3, 'fr', '<p>Contenu personnalisable dans lequel on peut insérer des images, du texte HTML et des bannières publicitaires</p>', 1, 'Contenu d''accueil du site', '2012-05-01 12:00:00', '2012-05-01 12:00:00', 'home'),
(4, 'fr', '<p>Interstitiel de publicité</p>', 0, 'Publicité', '2012-05-01 12:00:00', '2012-05-01 12:00:00', 'interstitiel'),
(5, 'fr', '<p>Introduction personnalisable</p>', 0, 'Devenir revendeur', '2012-05-01 12:00:00', '2012-05-01 12:00:00', 'devenir_revendeur'),
(6, 'en', '<div class="header_few_words_center">[SITE]</div>\r\n<div class="header_few_words_right">Open eCommerce</div>', 1, 'En-tête de la boutique', '2012-05-01 12:00:00', '2012-05-01 12:00:00', 'header'),
(7, 'fr','<h2>La page demandée n''est pas disponible</h2><br />', 1, 'Page d''erreur 404', '2012-05-01 11:53:04', '2012-05-01 12:00:28', 'error404'),
(8, 'en','<h2>This page is not found</h2><br />', 1, 'Error 404 page content', '2012-05-01 11:53:04', '2012-05-01 12:00:28', 'error404'),
(9, 'fr','<p style="text-align: center;">Bas de page du site personnalisable dans lequel on peut insérer des liens (footer_link)</p>', 1, 'Liens du footer', '2012-05-01 12:53:04', '2012-05-01 12:00:28', 'footer_link'),
(10, 'en','Refer your friends to let them receive a credit of € 10.00 on their first order.
By inserting their email, your friends will receive five email a request for registration to validate their account. After validation of their account, they will each receive a credit of € 10.00.', 1, 'Introduction à la page parrainage', '2012-05-01 11:53:04', '2012-05-01 12:00:28', 'intro_parrainage'),
(11, 'fr','Parrainez vos amis pour leur faire bénéficier d''un avoir de 10,00 € sur leur première commande.
En insérant leurs emails, vos 5 amis recevront par email une demande d''inscription leur permettant de valider leur compte client. Après validation de leur compte, ils bénéficieront chacun d''un avoir de 10,00 €.', 1, 'Introduction à la page parrainage', '2012-05-01 12:53:04', '2012-05-01 12:00:28', 'intro_parrainage'),
(12, 'fr','Merci de votre confiance, votre commande a été enregistrée avec succès.', 1, 'Fin du process de command court', '2012-05-01 12:53:04', '2012-05-01 12:00:28', 'end_process_order'),
(13, 'en','Thank you for your order. It has been successful.', 1, 'End of short order process', '2012-05-01 12:53:04', '2012-05-01 12:00:28', 'end_process_order');

-- --------------------------------------------------------

--
-- Structure de la table `peel_import_field`
--

CREATE TABLE IF NOT EXISTS `peel_import_field` (
  `champs` varchar(255) NOT NULL DEFAULT '',
  `etat` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_import_field`
--

INSERT INTO `peel_import_field` (`champs`, `etat`) VALUES
('id', 0),
('categorie_id', 0),
('Categorie', 0),
('id_marque', 1),
('reference', 0),
('nom_fr', 0),
('descriptif_fr', 0),
('description_fr', 0),
('nom_en', 0),
('descriptif_en', 0),
('description_en', 0),
('prix', 1),
('prix_revendeur', 1),
('prix_achat', 1),
('tva', 1),
('promotion', 1),
('poids', 0),
('points', 0),
('image1', 1),
('image2', 1),
('image3', 1),
('image4', 1),
('image5', 1),
('image6', 1),
('image7', 0),
('image8', 0),
('image9', 0),
('image10', 0),
('on_stock', 1),
('etat', 1);

-- --------------------------------------------------------

--
-- Structure de la table `peel_langues`
--

CREATE TABLE IF NOT EXISTS `peel_langues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) NOT NULL DEFAULT '',
  `flag` varchar(255) NOT NULL DEFAULT '',
  `position` int(11) NOT NULL DEFAULT '0',
  `etat` int(11) NOT NULL DEFAULT '0',
  `url_rewriting` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_langues`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_legal`
--

CREATE TABLE IF NOT EXISTS `peel_legal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_legal`
--

INSERT INTO `peel_legal` (`id`, `date_insere`, `date_maj`) VALUES
(1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `peel_marques`
--

CREATE TABLE IF NOT EXISTS `peel_marques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL DEFAULT '',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `promotion_devises` FLOAT(15,5) NOT NULL DEFAULT '0.00000',
  `promotion_percent` FLOAT(15,5) NOT NULL DEFAULT '0.00000',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_marques`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_meta`
--

CREATE TABLE IF NOT EXISTS `peel_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_meta`
--

INSERT INTO `peel_meta` (`id`, `technical_code`) VALUES
(1, 'default'),
(2, 'contact');


-- --------------------------------------------------------

--
-- Structure de la table `peel_modules`
--

CREATE TABLE IF NOT EXISTS `peel_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `display_mode` varchar(255) NOT NULL DEFAULT '',
  `position` int(11) NOT NULL DEFAULT '0',
  `in_home` tinyint(1) NOT NULL DEFAULT '0',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `technical_code` (`technical_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_modules`
--

INSERT INTO `peel_modules` (`id`, `technical_code`, `location`, `display_mode`, `position`, `etat`) VALUES
(1, 'catalogue', 'left', 'sideblocktitle', 1, 1),
(2, 'tagcloud', 'left', 'sideblocktitle', 2, 1),
(3, 'search', 'header', '', 3, 1),
(4, 'guide', 'left', 'sideblocktitle', 4, 1),
(5, 'caddie', 'right', 'sideblocktitle', 1, 1),
(6, 'account', 'right', 'sideblocktitle', 2, 1),
(7, 'best_seller', 'right', 'sideblocktitle', 3, 1),
(8, 'news', 'right', 'sideblocktitle', 4, 1),
(9, 'advertising', 'right', 'sideblock', 5, 0),
(10, 'menu', 'header', '', 4, 1),
(11, 'ariane', 'header', '', 5, 0),
(12, 'advertising1', 'right', 'sideblock', 10, 0),
(13, 'advertising2', 'right', 'sideblock', 11, 0),
(14, 'advertising3', 'left', 'sideblock', 12, 0),
(15, 'advertising4', 'right', 'sideblock', 10, 0),
(16, 'advertising5', 'right', 'sideblock', 11, 0),
(17, 'last_views',  'left',  'sideblocktitle', 2, 1),
(18, 'brand', 'left', 'sideblocktitle', 13, 1),
(19, 'paiement_secu',  'left',  'sideblocktitle', 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `peel_newsletter`
--

CREATE TABLE IF NOT EXISTS `peel_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `format` varchar(5) NOT NULL DEFAULT '',
  `template_technical_code` varchar(255) NOT NULL DEFAULT '',
  `statut` varchar(100) NOT NULL DEFAULT '',
  `date_envoi` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_newsletter`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_nom_attributs`
--

CREATE TABLE IF NOT EXISTS `peel_nom_attributs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `texte_libre` tinyint(1) NOT NULL DEFAULT '0',
  `type_affichage_attribut` tinyint(1) NOT NULL DEFAULT '3',
  `show_description` tinyint(1) NOT NULL DEFAULT '1',
  `upload` tinyint(1) NOT NULL DEFAULT '0',
  `mandatory` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `technical_code` (`technical_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `peel_nom_attributs`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_paiement`
--

CREATE TABLE IF NOT EXISTS `peel_paiement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `position` int(11) NOT NULL DEFAULT '0',
  `tarif` float(13,5) NOT NULL DEFAULT '0.00000',
  `tarif_percent` float(5,2) NOT NULL DEFAULT '0.00',
  `tva` float(5,2) NOT NULL DEFAULT '0.00',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `technical_code` (`technical_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_paiement`
--

INSERT INTO `peel_paiement` (`id`, `technical_code`, `position`, `tarif`, `tarif_percent`, `tva`, `etat`) VALUES
(17, 'check', 3, 0.00000, 0.00000, 0.00, 1),
(18, 'paypal', 1, 0.00000, 0.00000, 0.00, 1),
(19, 'transfer', 4, 0.00000, 0.00000, 0.00, 1),
(20, 'moneybookers', 2, 0.00000, 0.00000, 0.00, 1),
(21, 'pickup', 5, 0.00000, 0.00000, 0.00, 0),
(22, 'delivery', 6, 0.00000, 0.00000, 0.00, 0),
(23, 'cash', 7, 0.00000, 0.00000, 0.00, 0);

-- --------------------------------------------------------

--
-- Structure de la table `peel_pays`
--

CREATE TABLE IF NOT EXISTS `peel_pays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `continent_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lang` varchar(2) NOT NULL DEFAULT '',
  `flag` varchar(255) NOT NULL DEFAULT '',
  `zone` int(11) NOT NULL DEFAULT '0',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `iso` varchar(2) NOT NULL DEFAULT '',
  `iso3` varchar(3) NOT NULL DEFAULT '',
  `iso_num` smallint(4) NOT NULL DEFAULT '0',
  `devise` varchar(3) NOT NULL DEFAULT '',
  `position` int(11) NOT NULL DEFAULT '0',
  `risque_pays` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `position` (`position`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=239 ;

--
-- Contenu de la table `peel_pays`
--

INSERT INTO `peel_pays` (`id`, `continent_id`, `lang`, `flag`, `zone`, `etat`, `iso`, `iso3`, `iso_num`, `devise`, `position`, `risque_pays`) VALUES
(1, 4, 'fr', 'fr.gif', 1, 1, 'FR', 'FRA', 250, 'EUR', 0, 0),
(2, 3, 'en', 'af.gif', 4, 1, 'AF', 'AFG', 4, 'AFA', 0, 0),
(3, 1, 'en', 'za.gif', 4, 1, 'ZA', 'ZAF', 710, 'ZAR', 0, 0),
(4, 4, 'en', 'al.gif', 4, 1, 'AL', 'ALB', 8, 'ALL', 0, 0),
(5, 1, 'fr', 'dz.gif', 4, 1, 'DZ', 'DZA', 12, 'DZD', 0, 0),
(6, 4, 'en', 'de.gif', 3, 1, 'DE', 'DEU', 276, 'EUR', 0, 0),
(7, 3, 'en', 'sa.gif', 4, 1, 'SA', 'SAU', 682, 'SAR', 0, 0),
(8, 2, 'en', 'ar.gif', 4, 1, 'AR', 'ARG', 32, 'ARS', 0, 0),
(9, 5, 'en', 'au.gif', 4, 1, 'AU', 'AUS', 36, 'AUD', 0, 0),
(10, 4, 'en', 'at.gif', 3, 1, 'AT', 'AUT', 40, 'EUR', 0, 0),
(11, 4, 'fr', 'be.gif', 3, 1, 'BE', 'BEL', 56, 'EUR', 0, 0),
(12, 2, 'en', 'br.gif', 4, 1, 'BR', 'BRA', 76, 'BRL', 0, 0),
(13, 4, 'en', 'bg.gif', 3, 1, 'BG', 'BGR', 100, 'BGN', 0, 0),
(14, 2, 'en', 'ca.gif', 4, 1, 'CA', 'CAN', 124, 'CAD', 0, 0),
(15, 2, 'en', 'cl.gif', 4, 1, 'CL', 'CHL', 152, 'CLP', 0, 0),
(16, 3, 'en', 'cn.gif', 4, 1, 'CN', 'CHN', 156, 'CNY', 0, 0),
(17, 2, 'en', 'co.gif', 4, 1, 'CO', 'COL', 170, 'COP', 0, 0),
(18, 3, 'en', 'kr.gif', 4, 1, 'KR', 'KOR', 410, 'KRW', 0, 0),
(19, 2, 'en', 'cr.gif', 4, 1, 'CR', 'CRI', 188, 'CRC', 0, 0),
(20, 4, 'en', 'hr.gif', 4, 1, 'HR', 'HRV', 191, 'HRK', 0, 0),
(21, 4, 'en', 'dk.gif', 3, 1, 'DK', 'DNK', 208, 'DKK', 0, 0),
(22, 1, 'en', 'eg.gif', 4, 1, 'EG', 'EGY', 818, 'EGP', 0, 0),
(23, 3, 'en', 'ae.gif', 4, 1, 'AE', 'ARE', 784, 'AED', 0, 0),
(24, 2, 'en', 'ec.gif', 4, 1, 'EC', 'ECU', 218, 'USD', 0, 0),
(25, 2, 'en', 'us.gif', 4, 1, 'US', 'USA', 840, 'USD', 0, 0),
(26, 2, 'en', 'sv.gif', 4, 1, 'SV', 'SLV', 222, 'USD', 0, 0),
(27, 4, 'en', 'es.gif', 3, 1, 'ES', 'ESP', 724, 'EUR', 0, 0),
(28, 4, 'en', 'fi.gif', 3, 1, 'FI', 'FIN', 246, 'EUR', 0, 0),
(29, 4, 'en', 'gr.gif', 3, 1, 'GR', 'GRC', 300, 'EUR', 0, 0),
(30, 3, 'en', 'hk.gif', 4, 1, 'HK', 'HKG', 344, 'HKD', 0, 0),
(31, 4, 'en', 'hu.gif', 3, 1, 'HU', 'HUN', 348, 'HUF', 0, 0),
(32, 3, 'en', 'in.gif', 4, 1, 'IN', 'IND', 356, 'INR', 0, 0),
(33, 3, 'en', 'id.gif', 4, 1, 'ID', 'IDN', 360, 'IDR', 0, 0),
(34, 4, 'en', 'ie.gif', 3, 1, 'IE', 'IRL', 372, 'EUR', 0, 0),
(35, 3, 'en', 'il.gif', 4, 1, 'IL', 'ISR', 376, 'ILS', 0, 0),
(36, 4, 'en', 'it.gif', 3, 1, 'IT', 'ITA', 380, 'EUR', 0, 0),
(37, 3, 'en', 'jp.gif', 4, 1, 'JP', 'JPN', 392, 'JPY', 0, 0),
(38, 3, 'en', 'jo.gif', 4, 1, 'JO', 'JOR', 400, 'JOD', 0, 0),
(39, 3, 'en', 'lb.gif', 4, 1, 'LB', 'LBN', 422, 'USD', 0, 0),
(40, 3, 'en', 'my.gif', 4, 1, 'MY', 'MYS', 458, 'MYR', 0, 0),
(41, 1, 'fr', 'ma.gif', 4, 1, 'MA', 'MAR', 504, 'MAD', 0, 0),
(42, 2, 'en', 'mx.gif', 4, 1, 'MX', 'MEX', 484, 'MXN', 0, 0),
(43, 4, 'en', 'bv.gif', 4, 1, 'NO', 'NOK', 74, 'NOK', 0, 0),
(44, 5, 'en', 'nz.gif', 4, 1, 'NZ', 'NZL', 554, 'NZD', 0, 0),
(45, 2, 'en', 'pe.gif', 4, 1, 'PE', 'PER', 604, 'PEN', 0, 0),
(46, 3, 'en', 'pk.gif', 4, 1, 'PK', 'PAK', 586, 'PKR', 0, 0),
(47, 4, 'en', 'nl.gif', 3, 1, 'NL', 'NLD', 528, 'EUR', 0, 0),
(48, 3, 'en', 'ph.gif', 4, 1, 'PH', 'PHL', 608, 'PHP', 0, 0),
(49, 4, 'en', 'pl.gif', 3, 1, 'PL', 'POL', 616, 'PLN', 0, 0),
(50, 2, 'en', 'pr.gif', 4, 1, 'PR', 'PRI', 630, 'USD', 0, 0),
(51, 4, 'en', 'pt.gif', 3, 1, 'PT', 'PRT', 620, 'EUR', 0, 0),
(52, 4, 'en', 'cz.gif', 3, 1, 'CZ', 'CZE', 203, 'CZK', 0, 0),
(53, 4, 'en', 'ro.gif', 3, 1, 'RO', 'ROU', 642, 'ROL', 0, 0),
(54, 4, 'en', 'gb.gif', 3, 1, 'GB', 'GBR', 826, 'GBP', 0, 0),
(55, 4, 'en', 'ru.gif', 4, 1, 'RU', 'RUS', 643, 'RUB', 0, 0),
(56, 3, 'en', 'sg.gif', 4, 1, 'SG', 'SGP', 702, 'SGD', 0, 0),
(57, 4, 'en', 'se.gif', 3, 1, 'SE', 'SWE', 752, 'SEK', 0, 0),
(58, 4, 'en', 'ch.gif', 4, 1, 'CH', 'CHE', 756, 'CHF', 0, 0),
(59, 3, 'en', 'tw.gif', 4, 1, 'TW', 'TWN', 158, 'TWD', 0, 0),
(60, 3, 'en', 'th.gif', 4, 1, 'TH', 'THA', 764, 'THB', 0, 0),
(61, 3, 'en', 'tr.gif', 4, 1, 'TR', 'TUR', 792, 'TRL', 0, 0),
(62, 4, 'en', 'ua.gif', 4, 1, 'UA', 'UKR', 804, 'UAH', 0, 0),
(63, 2, 'en', 've.gif', 4, 1, 'VE', 'VEN', 862, 'VEB', 0, 0),
(64, 4, 'en', 'rs.gif', 4, 1, 'RS', 'SRB', 688, 'CSD', 0, 0),
(65, 5, 'en', 'ws.gif', 4, 1, 'WS', 'WSM', 882, 'WST', 0, 0),
(66, 4, 'en', 'ad.gif', 4, 1, 'AD', 'AND', 20, 'EUR', 0, 0),
(67, 1, 'en', 'ao.gif', 4, 1, 'AO', 'AGO', 24, 'AON', 0, 0),
(68, 2, 'en', 'ai.gif', 4, 1, 'AI', 'AIA', 660, 'XCD', 0, 0),
(69, 6, 'en', 'aq.gif', 4, 1, 'AQ', 'ATA', 10, 'USD', 0, 0),
(70, 2, 'en', 'ag.gif', 4, 1, 'AG', 'ATG', 28, 'XCD', 0, 0),
(71, 3, 'en', 'am.gif', 4, 1, 'AM', 'ARM', 51, 'AMD', 0, 0),
(72, 2, 'en', 'aw.gif', 4, 1, 'AW', 'ABW', 533, 'AWG', 0, 0),
(73, 3, 'en', 'az.gif', 4, 1, 'AZ', 'AZE', 31, 'AZM', 0, 0),
(74, 2, 'en', 'bs.gif', 4, 1, 'BS', 'BHS', 44, 'BSD', 0, 0),
(75, 3, 'en', 'bh.gif', 4, 1, 'BH', 'BHR', 48, 'BHD', 0, 0),
(76, 3, 'en', 'bd.gif', 4, 1, 'BD', 'BGD', 50, 'BDT', 0, 0),
(77, 4, 'en', 'by.gif', 4, 1, 'BY', 'BLR', 112, 'BYR', 0, 0),
(78, 2, 'en', 'bz.gif', 4, 1, 'BZ', 'BLZ', 84, 'BZD', 0, 0),
(79, 1, 'fr', 'bj.gif', 4, 1, 'BJ', 'BEN', 204, 'XOF', 0, 0),
(80, 2, 'en', 'bm.gif', 4, 1, 'BM', 'BMU', 60, 'BMD', 0, 0),
(81, 3, 'en', 'bt.gif', 4, 1, 'BT', 'BTN', 64, 'BTN', 0, 0),
(82, 2, 'en', 'bo.gif', 4, 1, 'BO', 'BOL', 68, 'BOB', 0, 0),
(83, 4, 'en', 'ba.gif', 4, 1, 'BA', 'BIH', 70, 'BAK', 0, 0),
(84, 1, 'en', 'bw.gif', 4, 1, 'BW', 'BWA', 72, 'BWP', 0, 0),
(85, 4, 'en', 'bv.gif', 4, 1, 'BV', 'BVT', 74, 'NOK', 0, 0),
(86, 3, 'en', 'io.gif', 4, 1, 'IO', 'IOT', 86, 'GBP', 0, 0),
(87, 5, 'en', 'vg.gif', 4, 1, 'VG', 'VGB', 92, 'USD', 0, 0),
(88, 5, 'en', 'bn.gif', 4, 1, 'BN', 'BRN', 96, 'BND', 0, 0),
(89, 1, 'fr', 'bf.gif', 4, 1, 'BF', 'BFA', 854, 'XOF', 0, 0),
(90, 1, 'en', 'bi.gif', 4, 1, 'BI', 'BDI', 108, 'BIF', 0, 0),
(91, 3, 'en', 'kh.gif', 4, 1, 'KH', 'KHM', 116, 'KHR', 0, 0),
(92, 1, 'fr', 'cm.gif', 4, 1, 'CM', 'CMR', 120, 'XAF', 0, 0),
(93, 1, 'en', 'cv.gif', 4, 1, 'CV', 'CPV', 132, 'CVE', 0, 0),
(94, 2, 'en', 'ky.gif', 4, 1, 'KY', 'CYM', 136, 'KYD', 0, 0),
(95, 1, 'fr', 'cf.gif', 4, 1, 'CF', 'CAF', 140, 'XAF', 0, 0),
(96, 1, 'fr', 'td.gif', 4, 1, 'TD', 'TCD', 148, 'XAF', 0, 0),
(97, 5, 'en', 'cx.gif', 4, 1, 'CX', 'CXR', 162, 'AUD', 0, 0),
(98, 5, 'en', 'cc.gif', 4, 1, 'CC', 'CCK', 166, 'AUD', 0, 0),
(99, 1, 'fr', 'km.gif', 4, 1, 'KM', 'COM', 174, 'KMF', 0, 0),
(100, 1, 'fr', 'cg.gif', 4, 1, 'CG', 'COG', 178, 'XAF', 0, 0),
(101, 5, 'en', 'ck.gif', 4, 1, 'CK', 'COK', 184, 'NZD', 0, 0),
(102, 2, 'en', 'cu.gif', 4, 1, 'CU', 'CUB', 192, 'CUP', 0, 0),
(103, 4, 'en', 'cy.gif', 3, 1, 'CY', 'CYP', 196, 'EUR', 0, 0),
(104, 1, 'fr', 'dj.gif', 4, 1, 'DJ', 'DJI', 262, 'DJF', 0, 0),
(105, 2, 'en', 'dm.gif', 4, 1, 'DM', 'DMA', 212, 'XCD', 0, 0),
(106, 2, 'en', 'do.gif', 4, 1, 'DO', 'DOM', 214, 'DOP', 0, 0),
(107, 3, 'en', 'tp.gif', 4, 1, 'TL', 'TLS', 626, 'USD', 0, 0),
(108, 1, 'en', 'gq.gif', 4, 1, 'GQ', 'GNQ', 226, 'XAF', 0, 0),
(109, 1, 'en', 'er.gif', 4, 1, 'ER', 'ERI', 232, 'ERN', 0, 0),
(110, 4, 'en', 'ee.gif', 3, 1, 'EE', 'EST', 233, 'EEK', 0, 0),
(111, 1, 'en', 'et.gif', 4, 1, 'ET', 'ETH', 231, 'ETB', 0, 0),
(112, 2, 'en', 'fk.gif', 4, 1, 'FK', 'FLK', 238, 'FKP', 0, 0),
(113, 4, 'en', 'fo.gif', 4, 1, 'FO', 'FRO', 234, 'DKK', 0, 0),
(114, 5, 'en', 'fj.gif', 4, 1, 'FJ', 'FJI', 242, 'FJD', 0, 0),
(115, 2, 'fr', 'gf.gif', 2, 1, 'GF', 'GUF', 254, 'EUR', 0, 0),
(116, 5, 'fr', 'pf.gif', 2, 1, 'PF', 'PYF', 258, 'XPF', 0, 0),
(117, 6, 'fr', 'tf.gif', 2, 1, 'TF', 'ATF', 260, 'EUR', 0, 0),
(118, 1, 'fr', 'ga.gif', 4, 1, 'GA', 'GAB', 266, 'XAF', 0, 0),
(119, 1, 'en', 'gm.gif', 4, 1, 'GM', 'GMB', 270, 'GMD', 0, 0),
(120, 3, 'en', 'ge.gif', 4, 1, 'GE', 'GEO', 268, 'GEL', 0, 0),
(121, 1, 'en', 'gh.gif', 4, 1, 'GH', 'GHA', 288, 'GHC', 0, 0),
(122, 4, 'en', 'gi.gif', 4, 1, 'GI', 'GIB', 292, 'GIP', 0, 0),
(123, 4, 'en', 'gl.gif', 4, 1, 'GL', 'GRL', 304, 'DKK', 0, 0),
(124, 2, 'en', 'gd.gif', 4, 1, 'GD', 'GRD', 308, 'XCD', 0, 0),
(125, 2, 'fr', 'gp.gif', 2, 1, 'GP', 'GLP', 312, 'EUR', 0, 0),
(126, 2, 'en', 'gu.gif', 4, 1, 'GU', 'GUM', 316, 'USD', 0, 0),
(127, 2, 'en', 'gt.gif', 4, 1, 'GT', 'GTM', 320, 'GTQ', 0, 0),
(128, 1, 'fr', 'gn.gif', 4, 1, 'GN', 'GIN', 324, 'USD', 0, 0),
(129, 1, 'en', 'gw.gif', 4, 1, 'GW', 'GNB', 624, 'XOF', 0, 0),
(131, 2, 'fr', 'ht.gif', 4, 1, 'HT', 'HTI', 332, 'HTG', 0, 0),
(132, 5, 'en', 'hm.gif', 4, 1, 'HM', 'HMD', 334, 'AUD', 0, 0),
(133, 2, 'en', 'hn.gif', 4, 1, 'HN', 'HND', 340, 'HNL', 0, 0),
(134, 4, 'en', 'is.gif', 4, 1, 'IS', 'ISL', 352, 'ISK', 0, 0),
(135, 3, 'en', 'ir.gif', 4, 1, 'IR', 'IRN', 364, 'IRR', 0, 0),
(136, 3, 'en', 'iq.gif', 4, 1, 'IQ', 'IRQ', 368, 'IQD', 0, 0),
(137, 1, 'fr', 'ci.gif', 4, 1, 'CI', 'CIV', 384, 'XOF', 0, 0),
(138, 2, 'en', 'jm.gif', 4, 1, 'JM', 'JAM', 388, 'JMD', 0, 0),
(139, 3, 'en', 'kz.gif', 4, 1, 'KZ', 'KAZ', 398, 'KZT', 0, 0),
(140, 1, 'en', 'ke.gif', 4, 1, 'KE', 'KEN', 404, 'KES', 0, 0),
(141, 5, 'en', 'ki.gif', 4, 1, 'KI', 'KIR', 296, 'AUD', 0, 0),
(142, 3, 'en', 'kr.gif', 4, 1, 'KR', 'KOR', 410, 'KRW', 0, 0),
(143, 3, 'en', 'kw.gif', 4, 1, 'KW', 'KWT', 414, 'KWD', 0, 0),
(144, 3, 'en', 'kg.gif', 4, 1, 'KG', 'KGZ', 417, 'KGS', 0, 0),
(145, 3, 'en', 'la.gif', 4, 1, 'LA', 'LAO', 418, 'LAK', 0, 0),
(146, 4, 'en', 'lv.gif', 3, 1, 'LV', 'LVA', 428, 'LVL', 0, 0),
(147, 1, 'en', 'ls.gif', 4, 1, 'LS', 'LSO', 426, 'LSL', 0, 0),
(148, 1, 'en', 'lr.gif', 4, 1, 'LR', 'LBR', 430, 'LRD', 0, 0),
(149, 1, 'en', 'ly.gif', 4, 1, 'LY', 'LBY', 434, 'LYD', 0, 0),
(150, 4, 'en', 'li.gif', 4, 1, 'LI', 'LIE', 438, 'CHF', 0, 0),
(151, 4, 'en', 'lt.gif', 3, 1, 'LT', 'LTU', 440, 'LTL', 0, 0),
(152, 4, 'en', 'lu.gif', 3, 1, 'LU', 'LUX', 442, 'EUR', 0, 0),
(153, 3, 'en', 'mo.gif', 4, 1, 'MO', 'MAC', 446, 'MOP', 0, 0),
(154, 4, 'en', 'mk.gif', 4, 1, 'MK', 'MKD', 807, 'EUR', 0, 0),
(155, 1, 'fr', 'mg.gif', 4, 1, 'MG', 'MDG', 450, 'MGF', 0, 0),
(156, 1, 'en', 'mw.gif', 4, 1, 'MW', 'MWI', 454, 'MWK', 0, 0),
(157, 3, 'en', 'mv.gif', 4, 1, 'MV', 'MDV', 462, 'MVR', 0, 0),
(158, 1, 'fr', 'ml.gif', 4, 1, 'ML', 'MLI', 466, 'XOF', 0, 0),
(159, 4, 'en', 'mt.gif', 3, 1, 'MT', 'MLT', 470, 'EUR', 0, 0),
(160, 5, 'en', 'mh.gif', 4, 1, 'MH', 'MHL', 584, 'USD', 0, 0),
(161, 2, 'fr', 'mq.gif', 2, 1, 'MQ', 'MTQ', 474, 'EUR', 0, 0),
(162, 1, 'fr', 'mr.gif', 4, 1, 'MR', 'MRT', 478, 'MRO', 0, 0),
(163, 1, 'en', 'mu.gif', 4, 1, 'MU', 'MUS', 480, 'MUR', 0, 0),
(164, 1, 'fr', 'yt.gif', 2, 1, 'YT', 'MYT', 175, 'EUR', 0, 0),
(165, 5, 'en', 'fm.gif', 4, 1, 'FM', 'FSM', 583, 'USD', 0, 0),
(166, 4, 'en', 'md.gif', 4, 1, 'MD', 'MDA', 498, 'MDL', 0, 0),
(167, 4, 'fr', 'mc.gif', 4, 1, 'MC', 'MCO', 492, 'EUR', 0, 0),
(168, 3, 'en', 'mn.gif', 4, 1, 'MN', 'MNG', 496, 'MNT', 0, 0),
(169, 2, 'en', 'ms.gif', 4, 1, 'MS', 'MSR', 500, 'XCD', 0, 0),
(170, 1, 'en', 'mz.gif', 4, 1, 'MZ', 'MOZ', 508, 'MZM', 0, 0),
(171, 3, 'en', 'mm.gif', 4, 1, 'MM', 'MMR', 104, 'MMK', 0, 0),
(172, 1, 'en', 'na.gif', 4, 1, 'NA', 'NAM', 516, 'NAD', 0, 0),
(173, 5, 'en', 'nr.gif', 4, 1, 'NR', 'NRU', 520, 'AUD', 0, 0),
(174, 3, 'en', 'np.gif', 4, 1, 'NP', 'NPL', 524, 'NPR', 0, 0),
(176, 5, 'fr', 'nc.gif', 2, 1, 'NC', 'NCL', 540, 'XPF', 0, 0),
(177, 2, 'en', 'ni.gif', 4, 1, 'NI', 'NIC', 558, 'NIO', 0, 0),
(178, 1, 'fr', 'ne.gif', 4, 1, 'NE', 'NER', 562, 'XOF', 0, 0),
(179, 1, 'en', 'ng.gif', 4, 1, 'NG', 'NGA', 566, 'NGN', 0, 0),
(180, 5, 'en', 'nu.gif', 4, 1, 'NU', 'NIU', 570, 'NZD', 0, 0),
(181, 5, 'en', 'nf.gif', 4, 1, 'NF', 'NFK', 574, 'AUD', 0, 0),
(182, 5, 'en', 'mp.gif', 4, 1, 'MP', 'MNP', 580, 'USD', 0, 0),
(183, 3, 'en', 'om.gif', 4, 1, 'OM', 'OMN', 512, 'OMR', 0, 0),
(184, 5, 'en', 'pw.gif', 4, 1, 'PW', 'PLW', 585, 'USD', 0, 0),
(185, 2, 'en', 'pa.gif', 4, 1, 'PA', 'PAN', 591, 'PAB', 0, 0),
(186, 5, 'en', 'pg.gif', 4, 1, 'PG', 'PNG', 598, 'PGK', 0, 0),
(187, 2, 'en', 'py.gif', 4, 1, 'PY', 'PRY', 600, 'PYG', 0, 0),
(188, 5, 'en', 'pn.gif', 4, 1, 'PN', 'PCN', 612, 'NZD', 0, 0),
(189, 3, 'en', 'qa.gif', 4, 1, 'QA', 'QAT', 634, 'QAR', 0, 0),
(190, 1, 'fr', 're.gif', 2, 1, 'RE', 'REU', 638, 'EUR', 0, 0),
(191, 1, 'en', 'rw.gif', 4, 1, 'RW', 'RWA', 646, 'RWF', 0, 0),
(192, 2, 'en', 'gs.gif', 4, 1, 'GS', 'SGS', 239, 'USD', 0, 0),
(193, 2, 'en', 'kn.gif', 4, 1, 'KN', 'KNA', 659, 'XCD', 0, 0),
(194, 2, 'en', 'lc.gif', 4, 1, 'LC', 'LCA', 662, 'XCD', 0, 0),
(195, 2, 'en', 'vc.gif', 4, 1, 'VC', 'VCT', 670, 'XCD', 0, 0),
(196, 5, 'en', 'ws.gif', 4, 1, 'WS', 'WSM', 882, 'WST', 0, 0),
(197, 4, 'en', 'sm.gif', 4, 1, 'SM', 'SMR', 674, 'EUR', 0, 0),
(198, 1, 'en', 'st.gif', 4, 1, 'ST', 'STP', 678, 'STD', 0, 0),
(199, 1, 'fr', 'sn.gif', 4, 1, 'SN', 'SEN', 686, 'XOF', 0, 0),
(200, 1, 'en', 'sc.gif', 4, 1, 'SC', 'SYC', 690, 'SCR', 0, 0),
(201, 1, 'en', 'sl.gif', 4, 1, 'SL', 'SLE', 694, 'SLL', 0, 0),
(202, 4, 'en', 'sk.gif', 3, 1, 'SK', 'SVK', 703, 'SKK', 0, 0),
(203, 4, 'en', 'si.gif', 3, 1, 'SI', 'SVN', 705, 'EUR', 0, 0),
(204, 1, 'en', 'so.gif', 4, 1, 'SO', 'SOM', 706, 'SOS', 0, 0),
(205, 3, 'en', 'lk.gif', 4, 1, 'LK', 'LKA', 144, 'LKR', 0, 0),
(206, 1, 'en', 'sh.gif', 4, 1, 'SH', 'SHN', 654, 'SHP', 0, 0),
(207, 2, 'fr', 'pm.gif', 2, 1, 'PM', 'SPM', 666, 'EUR', 0, 0),
(208, 1, 'en', 'sd.gif', 4, 1, 'SD', 'SDN', 736, 'SDD', 0, 0),
(209, 2, 'en', 'sr.gif', 4, 1, 'SR', 'SUR', 740, 'SRG', 0, 0),
(210, 4, 'en', 'sj.gif', 4, 1, 'SJ', 'SJM', 744, 'NOK', 0, 0),
(211, 1, 'en', 'sz.gif', 4, 1, 'SZ', 'SWZ', 748, 'SZL', 0, 0),
(212, 3, 'en', 'sy.gif', 4, 1, 'SY', 'SYR', 760, 'SYP', 0, 0),
(213, 3, 'en', 'tj.gif', 4, 1, 'TJ', 'TJK', 762, 'TJS', 0, 0),
(214, 1, 'en', 'tz.gif', 4, 1, 'TZ', 'TZA', 834, 'TZS', 0, 0),
(215, 1, 'fr', 'tg.gif', 4, 1, 'TG', 'TGO', 768, 'XOF', 0, 0),
(216, 5, 'en', 'tk.gif', 4, 1, 'TK', 'TKL', 772, 'NZD', 0, 0),
(217, 5, 'en', 'to.gif', 4, 1, 'TO', 'TON', 776, 'TOP', 0, 0),
(218, 2, 'en', 'tt.gif', 4, 1, 'TT', 'TTO', 780, 'TTD', 0, 0),
(219, 1, 'fr', 'tn.gif', 4, 1, 'TN', 'TUN', 788, 'TND', 0, 0),
(220, 3, 'en', 'tm.gif', 4, 1, 'TM', 'TKM', 795, 'TMM', 0, 0),
(221, 2, 'en', 'tc.gif', 4, 1, 'TC', 'TCA', 796, 'USD', 0, 0),
(222, 5, 'en', 'tv.gif', 4, 1, 'TV', 'TUV', 798, 'AUD', 0, 0),
(223, 5, 'en', 'um.gif', 4, 1, 'UM', 'UMI', 581, 'USD', 0, 0),
(224, 1, 'en', 'ug.gif', 4, 1, 'UG', 'UGA', 800, 'UGX', 0, 0),
(225, 2, 'en', 'uy.gif', 4, 1, 'UY', 'URY', 858, 'UYU', 0, 0),
(226, 3, 'en', 'uz.gif', 4, 1, 'UZ', 'UZB', 860, 'UZS', 0, 0),
(227, 5, 'en', 'vu.gif', 4, 1, 'VU', 'VUT', 548, 'VUV', 0, 0),
(228, 4, 'en', 'va.gif', 4, 1, 'VA', 'VAT', 336, 'EUR', 0, 0),
(229, 3, 'en', 'vn.gif', 4, 1, 'VN', 'VNM', 704, 'VND', 0, 0),
(230, 5, 'en', 'vi.gif', 4, 1, 'VI', 'VIR', 850, 'USD', 0, 0),
(231, 5, 'fr', 'wf.gif', 2, 1, 'WF', 'WLF', 876, 'XPF', 0, 0),
(232, 1, 'en', 'eh.gif', 4, 1, 'EH', 'ESH', 732, 'MAD', 0, 0),
(233, 3, 'en', 'ye.gif', 4, 1, 'YE', 'YEM', 887, 'YER', 0, 0),
(234, 1, 'fr', 'cd.gif', 4, 1, 'CD', 'COD', 180, 'XAF', 0, 0),
(235, 1, 'en', 'zm.gif', 4, 1, 'ZM', 'ZMB', 894, 'ZMK', 0, 0),
(236, 1, 'en', 'zw.gif', 4, 1, 'ZW', 'ZWE', 716, 'ZWD', 0, 0),
(237, 2, 'en', 'bb.gif', 4, 1, 'BB', 'BRB', 52, 'BBD', 0, 0),
(238, 4, 'en', 'yu.gif', 4, 1, 'ME', 'MNE', 499, 'CSD', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `peel_pensebete`
--

CREATE TABLE IF NOT EXISTS `peel_pensebete` (
	`id` int(11) NOT NULL AUTO_INCREMENT ,
	`id_produit` int(11) NOT NULL  DEFAULT '0',
	`id_utilisateur` int(11) NOT NULL  DEFAULT '0',
	`date_insertion` date NOT NULL DEFAULT '0000-00-00',
	PRIMARY KEY ( `id` )
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
--
-- Contenu de la table `peel_pensebete`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_produits`
--

CREATE TABLE IF NOT EXISTS `peel_produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `alpha` char(1) NOT NULL DEFAULT '',
  `reference` varchar(100) NOT NULL DEFAULT '',
  `ean_code` VARCHAR(13) NOT NULL DEFAULT '',
  `default_image` tinyint(1) NOT NULL DEFAULT '1',
  `image1` varchar(255) NOT NULL DEFAULT '',
  `image2` varchar(255) NOT NULL DEFAULT '',
  `image3` varchar(255) NOT NULL DEFAULT '',
  `image4` varchar(255) NOT NULL DEFAULT '',
  `image5` varchar(255) NOT NULL DEFAULT '',
  `image6` varchar(255) NOT NULL DEFAULT '',
  `image7` varchar(255) NOT NULL DEFAULT '',
  `image8` varchar(255) NOT NULL DEFAULT '',
  `image9` varchar(255) NOT NULL DEFAULT '',
  `image10` varchar(255) NOT NULL DEFAULT '',
  `youtube_code` mediumtext NOT NULL,
  `on_estimate` tinyint(1) NOT NULL DEFAULT '0',
  `prix` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_revendeur` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_flash` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_achat` float(15,5) NOT NULL DEFAULT '0.00000',
  `poids` float(10,2) NOT NULL DEFAULT '0.00',
  `volume` float(10,2) NOT NULL DEFAULT '0.00',
  `display_price_by_weight` tinyint(1) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  `date_insere` date NOT NULL DEFAULT '0000-00-00',
  `date_maj` date NOT NULL DEFAULT '0000-00-00',
  `promotion` float(15,2) NOT NULL DEFAULT '0.00',
  `tva` float(5,2) NOT NULL DEFAULT '0.00',
  `etat` int(1) NOT NULL DEFAULT '0',
  `on_stock` tinyint(1) NOT NULL DEFAULT '0',
  `delai_stock` varchar(100) NOT NULL DEFAULT '',
  `seuil_stock` int(11) NOT NULL DEFAULT '0',
  `stock` int(11) NOT NULL DEFAULT '0',
  `affiche_stock` tinyint(1) NOT NULL DEFAULT '0',
  `on_promo` tinyint(1) NOT NULL DEFAULT '0',
  `on_new` tinyint(1) NOT NULL DEFAULT '0',
  `on_rollover` tinyint(1) NOT NULL DEFAULT '0',
  `on_special` tinyint(1) NOT NULL DEFAULT '0',
  `on_perso` tinyint(1) NOT NULL DEFAULT '0',
  `on_top` tinyint(1) NOT NULL DEFAULT '0',
  `on_gift` tinyint(1) NOT NULL DEFAULT '0',
  `on_gift_points` int(11) NOT NULL DEFAULT '0',
  `on_ref_produit` tinyint(1) NOT NULL DEFAULT '0',
  `nb_ref_produits` int(11) NOT NULL DEFAULT '0',
  `comments` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `on_flash` tinyint(1) NOT NULL DEFAULT '0',
  `flash_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `flash_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_marque` int(11) NOT NULL DEFAULT '0',
  `cost_guides` float(13,2) NOT NULL DEFAULT '0.00',
  `etat_stock` tinyint(1) NOT NULL DEFAULT '0',
  `on_rupture` tinyint(1) NOT NULL DEFAULT '0',
  `lang` varchar(2) NOT NULL DEFAULT '',
  `prix_promo` float(15,2) NOT NULL DEFAULT '0.00',
  `paiement` varchar(255) NOT NULL DEFAULT '',
  `type_prix` varchar(255) NOT NULL DEFAULT '',
  `on_check` tinyint(1) NOT NULL DEFAULT '0',
  `mp3` varchar(64) NOT NULL DEFAULT '',
  `pdf` varchar(25) NOT NULL DEFAULT '',
  `id_ecotaxe` int(11) NOT NULL DEFAULT '0',
  `extrait` varchar(64) NOT NULL DEFAULT '',
  `on_download` tinyint(1) NOT NULL DEFAULT '0',
  `zip` varchar(64) NOT NULL DEFAULT '',
  `id_utilisateur` int(11) NOT NULL DEFAULT '0',
  `default_color_id` int(11) NOT NULL DEFAULT '0',
  `display_tab` tinyint(1) NOT NULL DEFAULT '0',
  `nb_view` int(11) NOT NULL DEFAULT '0',
  `extra_link` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`),
  KEY `marque` (`id_marque`),
  KEY `position` (`position`),
  KEY `on_rollover` (`on_rollover`),
  KEY `on_special` (`on_special`),
  KEY `on_top` (`on_top`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_produits`
--



-- --------------------------------------------------------

--
-- Structure de la table `peel_produits_attributs`
--

CREATE TABLE IF NOT EXISTS `peel_produits_attributs` (
  `produit_id` int(11) NOT NULL DEFAULT '0',
  `nom_attribut_id` int(11) NOT NULL DEFAULT '0',
  `attribut_id` int(11) NOT NULL DEFAULT '0',
  KEY `nom_attribut_id` (`nom_attribut_id`),
  KEY `attribut_id` (`attribut_id`),
  KEY `produit_id` (`produit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_produits_attributs`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_produits_categories`
--

CREATE TABLE IF NOT EXISTS `peel_produits_categories` (
  `produit_id` int(11) NOT NULL DEFAULT '0',
  `categorie_id` int(11) NOT NULL DEFAULT '0',
  KEY `produit_id` (`produit_id`),
  KEY `categorie_id` (`categorie_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_produits_categories`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_produits_couleurs`
--

CREATE TABLE IF NOT EXISTS `peel_produits_couleurs` (
  `produit_id` int(11) NOT NULL DEFAULT '0',
  `couleur_id` int(11) NOT NULL DEFAULT '0',
  `default_image` tinyint(1) NOT NULL DEFAULT '1',
  `image1` varchar(255) NOT NULL DEFAULT '',
  `image2` varchar(255) NOT NULL DEFAULT '',
  `image3` varchar(255) NOT NULL DEFAULT '',
  `image4` varchar(255) NOT NULL DEFAULT '',
  `image5` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`produit_id`,`couleur_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_produits_couleurs`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_produits_references`
--

CREATE TABLE IF NOT EXISTS `peel_produits_references` (
  `produit_id` int(11) NOT NULL DEFAULT '0',
  `reference_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`produit_id`,`reference_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_produits_references`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_produits_tailles`
--

CREATE TABLE IF NOT EXISTS `peel_produits_tailles` (
  `produit_id` int(11) NOT NULL DEFAULT '0',
  `taille_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`produit_id`,`taille_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_produits_tailles`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_profil`
--

CREATE TABLE IF NOT EXISTS `peel_profil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `priv` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_profil`
--

INSERT INTO `peel_profil` (`id`, `priv`) VALUES
(1, 'util'),
(2, 'admin'),
(3, 'reve'),
(4, 'stop'),
(5, 'affi'),
(6, 'stand'),
(7, 'supplier'),
(8, 'newsletter'),
(9, 'reve_certif'),
(10, 'admin_content'),
(11, 'admin_sales'),
(12, 'admin_products'),
(13, 'admin_webmastering'),
(14, 'admin_moderation');


-- --------------------------------------------------------

--
-- Structure de la table `peel_rubriques`
--

CREATE TABLE IF NOT EXISTS `peel_rubriques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT '',
  `lang` varchar(2) NOT NULL DEFAULT '',
  `on_special` tinyint(1) NOT NULL DEFAULT '0',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `articles_review` tinyint(1) NOT NULL DEFAULT '1',
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_rubriques`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_security_codes`
--
CREATE TABLE IF NOT EXISTS `peel_security_codes` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(5) NOT NULL DEFAULT '',
	`time` int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_security_codes`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_societe`
--

CREATE TABLE IF NOT EXISTS `peel_societe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `societe` varchar(255) NOT NULL DEFAULT '',
  `adresse` varchar(255) NOT NULL DEFAULT '',
  `adresse2` varchar(255) NOT NULL DEFAULT '',
  `code_postal` varchar(100) NOT NULL DEFAULT '',
  `code_postal2` varchar(100) NOT NULL DEFAULT '',
  `ville` varchar(255) NOT NULL DEFAULT '',
  `ville2` varchar(255) NOT NULL DEFAULT '',
  `tel` varchar(32) NOT NULL DEFAULT '',
  `tel2` varchar(32) NOT NULL DEFAULT '',
  `fax` varchar(32) NOT NULL DEFAULT '',
  `fax2` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `siren` varchar(255) NOT NULL DEFAULT '',
  `tvaintra` varchar(255) NOT NULL DEFAULT '',
  `nom` varchar(255) NOT NULL DEFAULT '',
  `prenom` varchar(255) NOT NULL DEFAULT '',
  `pays` varchar(255) NOT NULL DEFAULT '',
  `pays2` varchar(255) NOT NULL DEFAULT '',
  `siteweb` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `code_banque` varchar(10) NOT NULL DEFAULT '',
  `code_guichet` varchar(10) NOT NULL DEFAULT '',
  `numero_compte` varchar(20) NOT NULL DEFAULT '',
  `cle_rib` varchar(5) NOT NULL DEFAULT '',
  `titulaire` varchar(255) NOT NULL DEFAULT '',
  `domiciliation` varchar(255) NOT NULL DEFAULT '',
  `cnil` varchar(30) NOT NULL DEFAULT '',
  `iban` varchar(255) NOT NULL DEFAULT '',
  `swift` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_societe`
--

INSERT INTO `peel_societe` ( `id` , `societe` , `adresse` , `adresse2` , `code_postal` , `code_postal2` , `ville` , `ville2` , `tel` , `tel2` , `fax` , `fax2` , `email` , `siren` , `tvaintra` , `nom` , `prenom` , `pays` , `pays2` , `siteweb` , `logo` , `code_banque` , `code_guichet` , `numero_compte` , `cle_rib` , `titulaire` , `domiciliation` , `cnil` , `iban` , `swift` )
VALUES (1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `peel_statut_livraison`
--

CREATE TABLE IF NOT EXISTS `peel_statut_livraison` (
  `id` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_statut_livraison`
--

INSERT INTO `peel_statut_livraison` (`id`, `position`) VALUES
(0, 0),
(1, 1),
(3, 3),
(6, 6),
(9, 9);

-- --------------------------------------------------------

--
-- Structure de la table `peel_statut_paiement`
--

CREATE TABLE IF NOT EXISTS `peel_statut_paiement` (
  `id` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_statut_paiement`
--

INSERT INTO `peel_statut_paiement` (`id`, `position`) VALUES
(0, 0),
(1, 1),
(2, 2),
(3, 3),
(6, 6),
(9, 9);

-- --------------------------------------------------------

--
-- Structure de la table `peel_tag_cloud`
--

CREATE TABLE IF NOT EXISTS `peel_tag_cloud` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(100) NOT NULL DEFAULT '',
  `nbsearch` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(2) NOT NULL DEFAULT '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_tag_cloud`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_tailles`
--

CREATE TABLE IF NOT EXISTS `peel_tailles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prix` float(15,5) NOT NULL default '0.00000',
  `prix_revendeur` float(15,5) NOT NULL DEFAULT '0.00000',
  `position` int(11) NOT NULL DEFAULT '0',
  `signe` char(1) NOT NULL DEFAULT '',
  `poids` float(15,5) NOT NULL DEFAULT '0.00000',
  `mandatory` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `peel_tailles`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_tarifs`
--

CREATE TABLE IF NOT EXISTS `peel_tarifs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poidsmin` float(10,2) NOT NULL DEFAULT '0.00',
  `poidsmax` float(10,2) NOT NULL DEFAULT '0.00',
  `totalmin` float(10,2) NOT NULL DEFAULT '0.00',
  `totalmax` float(10,2) NOT NULL DEFAULT '0.00',
  `tarif` float(10,2) NOT NULL DEFAULT '0.00',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `zone` int(11) NOT NULL DEFAULT '0',
  `tva` float(7,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_tarifs`
--

INSERT INTO `peel_tarifs` (`id`, `poidsmax`, `totalmax`, `tarif`, `type`, `zone`, `tva`) VALUES
(1, 250.00, 0.00, 6.28, 1, 1, 19.60),
(2, 500.00, 0.00, 7.12, 1, 1, 19.60),
(3, 750.00, 0.00, 7.95, 1, 1, 19.60),
(4, 1000.00, 0.00, 8.37, 1, 1, 19.60),
(6, 1500.00, 0.00, 8.91, 1, 1, 19.60),
(8, 2000.00, 0.00, 9.33, 1, 1, 19.60),
(9, 3000.00, 0.00, 10.05, 1, 1, 19.60),
(10, 4000.00, 0.00, 10.88, 1, 1, 19.60),
(11, 5000.00, 0.00, 11.60, 1, 1, 19.60),
(12, 6000.00, 0.00, 12.32, 1, 1, 19.60),
(13, 7000.00, 0.00, 12.80, 1, 1, 19.60),
(14, 10000.00, 0.00, 14.05, 1, 1, 19.60),
(15, 15000.00, 0.00, 16.21, 1, 1, 19.60),
(16, 30000.00, 0.00, 21.95, 1, 1, 19.60),
(51, 0.00, 0.00, 0.00, 4, 1, 5.50);


-- --------------------------------------------------------

--
-- Structure de la table `peel_tva`
--

CREATE TABLE IF NOT EXISTS `peel_tva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tva` float(5,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_tva`
--

INSERT INTO `peel_tva` (`id`, `tva`) VALUES
(1, 19.60),
(2, 5.50),
(3, 2.10),
(4, 0.00);

-- --------------------------------------------------------

--
-- Structure de la table `peel_types`
--

CREATE TABLE IF NOT EXISTS `peel_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL DEFAULT '0',
  `without_delivery_address` tinyint(1) NOT NULL DEFAULT '0',
  `is_socolissimo` tinyint(1) NOT NULL DEFAULT '0',
  `is_icirelais` tinyint(1) NOT NULL DEFAULT '0',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_types`
--

INSERT INTO `peel_types` (`id`, `position`, `without_delivery_address`, `is_socolissimo`, `is_icirelais`, `etat`) VALUES
(1, 1, 0, 0, 0, 1),
(2, 2, 0, 0, 0, 0),
(3, 3, 0, 0, 0, 0),
(4, 4, 1, 0, 0, 1),
(5, 5, 0, 0, 0, 0),
(6, 6, 0, 0, 0, 0),
(7, 7, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `peel_utilisateurs`
--

CREATE TABLE IF NOT EXISTS `peel_utilisateurs` (
  `id_utilisateur` int(11) NOT NULL auto_increment,
  `code_client` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `email_bounce` varchar(60) NOT NULL DEFAULT '',
  `mot_passe` varchar(32) NOT NULL DEFAULT '',
  `priv` varchar(255) NOT NULL DEFAULT '',
  `civilite` varchar(20) NOT NULL DEFAULT '',
  `prenom` varchar(64) NOT NULL DEFAULT '',
  `pseudo` varchar(64) NOT NULL DEFAULT '',
  `nom_famille` varchar(64) NOT NULL DEFAULT '',
  `societe` varchar(255) NOT NULL DEFAULT '',
  `intracom_for_billing` varchar(15) NOT NULL DEFAULT '',
  `naissance` date NOT NULL DEFAULT '0000-00-00',
  `telephone` varchar(32) NOT NULL DEFAULT '',
  `fax` varchar(32) NOT NULL DEFAULT '',
  `portable` varchar(32) NOT NULL DEFAULT '',
  `adresse` varchar(255) NOT NULL DEFAULT '',
  `code_postal` varchar(100) NOT NULL DEFAULT '',
  `ville` varchar(255) NOT NULL DEFAULT '',
  `pays` int(11) NOT NULL DEFAULT '1',
  `cnil` tinyint(1) NOT NULL DEFAULT '1',
  `newsletter` tinyint(1) NOT NULL DEFAULT '1',
  `commercial` tinyint(1) NOT NULL DEFAULT '1',
  `remise_percent` float(5,2) NOT NULL DEFAULT '0.00',
  `remise_valeur` float(5,2) NOT NULL DEFAULT '0.00',
  `points` int(4) NOT NULL DEFAULT '0',
  `on_vacances` tinyint(1) NOT NULL DEFAULT '0',
  `on_vacances_date` date NOT NULL DEFAULT '0000-00-00',
  `format` varchar(5) NOT NULL DEFAULT '',
  `message` mediumtext NOT NULL,
  `siret` varchar(20) NOT NULL DEFAULT '',
  `ape` varchar(5) NOT NULL DEFAULT '',
  `code_banque` varchar(15) NOT NULL DEFAULT '',
  `code_guichet` varchar(15) NOT NULL DEFAULT '',
  `numero_compte` varchar(15) NOT NULL DEFAULT '',
  `cle_rib` varchar(15) NOT NULL DEFAULT '',
  `domiciliation` varchar(180) NOT NULL DEFAULT '',
  `iban` varchar(60) NOT NULL DEFAULT '',
  `bic` varchar(60) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `message_client` varchar(255) NOT NULL DEFAULT '',
  `date_insert` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `alerte` mediumtext NOT NULL,
  `nom_utilisateur` varchar(255) NOT NULL DEFAULT '',
  `region` varchar(255) NOT NULL DEFAULT '',
  `avoir` float(15,5) NOT NULL DEFAULT '0.00000',
  `statut_coupon` varchar(125) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT '',
  `fonction` varchar(255) NOT NULL DEFAULT '',
  `etat` int(1) NOT NULL DEFAULT '1',
  `id_parrain` int(11) NOT NULL DEFAULT '0',
  `id_groupe` int(11) NOT NULL DEFAULT '0',
  `origin` int(11) NOT NULL DEFAULT '0',
  `origin_other` varchar(255) NOT NULL DEFAULT '',
  `lang` varchar(2) NOT NULL DEFAULT '',
  `seg_buy` enum('no','one_old','one_recent','multi_old','multi_recent','no_info') NOT NULL DEFAULT 'no_info',
  `seg_want` enum('min_contact','max_contact','no_matter','no_info') NOT NULL DEFAULT 'no_info',
  `seg_think` enum('never_budget','no_budget','unsatisfied','satisfied','not_interested','interested','newbie','no_matter','no_info') NOT NULL DEFAULT 'no_info',
  `seg_followed` enum('no','poor','correct','no_info') NOT NULL DEFAULT 'no_info',
  `seg_who` enum('independant','partner','company_small','company_medium','company_big','person','no_info') NOT NULL DEFAULT 'no_info',
  `Admis` enum('NO','OK') NOT NULL DEFAULT 'OK',
  `Valid` enum('NO','YES','AGENT','PROSP') NOT NULL DEFAULT 'YES',
  `promo` varchar(20) NOT NULL DEFAULT '',
  `id_cat_1` tinyint(1) NOT NULL DEFAULT '0',
  `id_cat_2` tinyint(1) NOT NULL DEFAULT '0',
  `id_cat_3` tinyint(1) NOT NULL DEFAULT '0',
  `activity` varchar(255) NOT NULL DEFAULT '',
  `project_product_proposed` varchar(255) NOT NULL DEFAULT '',
  `project_date_forecasted` date NOT NULL DEFAULT '0000-00-00',
  `commercial_contact_id` int(11) NOT NULL DEFAULT '0',
  `project_budget_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `project_chances_estimated` varchar(255) NOT NULL DEFAULT '0',
  `ad_insert_delay` enum('max', 'medium', 'min') NOT NULL DEFAULT 'max',
  `logo` VARCHAR(255) NOT NULL DEFAULT '',
  `description_document` TEXT NOT NULL,
  `document` VARCHAR(255) NOT NULL DEFAULT '',
  `on_client_module` TINYINT(1) NOT NULL DEFAULT '0',
  `on_photodesk` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id_utilisateur`),
  KEY `code_client` (`code_client`),
  KEY `email` (`email`),
  KEY `pseudo` (`pseudo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Contenu de la table `peel_utilisateurs`
--


-- --------------------------------------------------------

--
-- Structure de la table `peel_utilisateurs_codes_promos`
--

CREATE TABLE IF NOT EXISTS `peel_utilisateurs_codes_promos` (
  `id_utilisateur` int(11) NOT NULL DEFAULT '0',
  `id_code_promo` int(11) NOT NULL DEFAULT '0',
  `nom_code` varchar(25) NOT NULL DEFAULT '',
  `la_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `utilise` tinyint(3) NOT NULL DEFAULT '0',
  `valeur` varchar(25) NOT NULL DEFAULT '',
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `nom_code` (`nom_code`),
  KEY `la_date` (`la_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_utilisateurs_codes_promos`
--

-- --------------------------------------------------------

--
-- Structure de la table `peel_utilisateur_connexions`
--
CREATE TABLE IF NOT EXISTS `peel_utilisateur_connexions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_login` varchar(64) NOT NULL DEFAULT '',
  `user_ip` int(15) unsigned NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_login` (`user_login`(2)),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Structure de la table `peel_webmail`
--
CREATE TABLE IF NOT EXISTS `peel_webmail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` text NOT NULL,
  `message` text NOT NULL,
  `email` text NOT NULL,
  `nom` varchar(255) NOT NULL DEFAULT '',
  `prenom` varchar(255) NOT NULL DEFAULT '',
  `telephone` varchar(32) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `heure` text NOT NULL,
  `ip` text NOT NULL,
  `read` enum('NO','READ','SEND','TREATED') NOT NULL DEFAULT 'NO',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `commande_id` int(11) NOT NULL DEFAULT '0',
  `dispo` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Structure de la table `peel_zones`
--

CREATE TABLE IF NOT EXISTS `peel_zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `tva` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `on_franco` tinyint(1) NOT NULL DEFAULT '0',
  `on_franco_amount` FLOAT(15,5) NOT NULL DEFAULT '0.00000',
  `on_franco_nb_products` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_zones`
--

INSERT INTO `peel_zones` (`id`, `tva`, `position`, `on_franco`, `on_franco_nb_products`) VALUES
(1, 1, 1, 0, 0),
(2, 1, 2, 0, 0),
(3, 1, 3, 0, 0),
(4, 1, 4, 0, 0);

-- --------------------------------------------------------
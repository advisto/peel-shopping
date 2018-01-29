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
# $Id: peel.sql 55482 2017-12-11 14:58:04Z sdelaporte $
#

--
-- Structure de la table `peel_access_map`
--

CREATE TABLE IF NOT EXISTS `peel_access_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_tag` TEXT NOT NULL,
  `date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `site_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_access_map`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `site_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_membre` (`id_membre`),
  KEY `site_id` (`site_id`)
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
-- Structure de la table `peel_adresses`
--

CREATE TABLE IF NOT EXISTS `peel_adresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int(11) NOT NULL DEFAULT '0',
  `civilite` varchar(20) NOT NULL DEFAULT '',
  `prenom` varchar(64) NOT NULL DEFAULT '',
  `nom_famille` varchar(64) NOT NULL DEFAULT '',
  `societe` varchar(255) NOT NULL DEFAULT '',
  `telephone` varchar(32) NOT NULL DEFAULT '',
  `portable` varchar(32) NOT NULL DEFAULT '',
  `adresse` varchar(255) NOT NULL DEFAULT '',
  `code_postal` varchar(100) NOT NULL DEFAULT '',
  `ville` varchar(255) NOT NULL DEFAULT '',
  `pays` int(11) NOT NULL DEFAULT '0',
  `nom` varchar(255) NOT NULL DEFAULT '',
  `address_type` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `longitude` varchar(255) NOT NULL DEFAULT '',
  `latitude` varchar(255) NOT NULL DEFAULT '',
  `address_hash` varchar(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `peel_articles`
--

CREATE TABLE IF NOT EXISTS `peel_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image1` varchar(255) NOT NULL DEFAULT '',
  `on_special` tinyint(1) NOT NULL DEFAULT '0',
  `on_reseller` tinyint(1) NOT NULL DEFAULT '0',
  `on_rollover` tinyint(1) NOT NULL DEFAULT '0',
  `date_insere` date NOT NULL DEFAULT '0000-00-00',
  `date_maj` date NOT NULL DEFAULT '0000-00-00',
  `lang` varchar(2) NOT NULL DEFAULT '',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


-- --------------------------------------------------------

--
-- Structure de la table `peel_articles_rubriques`
--

CREATE TABLE IF NOT EXISTS `peel_articles_rubriques` (
  `article_id` int(11) NOT NULL DEFAULT '0',
  `rubrique_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`article_id`,`rubrique_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `site_id` int( 11 ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `date_validation` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL DEFAULT '',
  `lang` char(2) NOT NULL DEFAULT '',
  `detail` varchar(255) NOT NULL DEFAULT '',
  `item_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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
  `annonce_number` int(11) NOT NULL DEFAULT '0',
  `rang` int(10) DEFAULT NULL,
  `on_home_page` tinyint(1) NOT NULL DEFAULT '0',
  `on_other_page_category` tinyint(1) NOT NULL DEFAULT '0',
  `on_first_page_category` tinyint(1) NOT NULL DEFAULT '0',
  `on_announcement_creation_page` tinyint(1) NOT NULL DEFAULT '0',
  `on_other_page` tinyint(1) NOT NULL DEFAULT '0',
  `on_search_engine_page` tinyint(1) NOT NULL DEFAULT '0',
  `on_background_site` tinyint(1) NOT NULL DEFAULT '0',
  `keywords` mediumtext NOT NULL,
  `list_id` varchar(255) NOT NULL DEFAULT '',
  `pages_allowed` enum('all','odd','even') NOT NULL DEFAULT 'all',
  `do_not_display_on_pages_related_to_user_ids_list` varchar(255) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_banniere`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `allow_show_all_sons_products` tinyint(1) NOT NULL DEFAULT '0',
  `poids` float(10,2) NOT NULL DEFAULT '0.00000',
  `date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `peel_cgv`
--

CREATE TABLE IF NOT EXISTS `peel_cgv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `site_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_cgv`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `promo_code_combinable` tinyint(1) NOT NULL DEFAULT '0',
  `id_site` int(11) NOT NULL DEFAULT '0',
  `id_categorie` int(11) NOT NULL DEFAULT '0',
  `nombre_prevue` int( 11 ) NOT NULL DEFAULT '0',
  `compteur_utilisation` int( 11 ) NOT NULL DEFAULT '0',
  `nb_used_per_client` int( 11 ) NOT NULL DEFAULT '1',
  `site_id` int(11) NOT NULL DEFAULT '0',
  `product_filter` varchar(255) NOT NULL DEFAULT '',
  `cat_not_apply_code_promo` TEXT NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `nom` (`nom`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `peel_commandes`
--

CREATE TABLE IF NOT EXISTS `peel_commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `id_utilisateur` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL DEFAULT '',
  `o_timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `a_timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `f_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `e_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `commentaires` mediumtext NOT NULL,
  `commentaires_admin` mediumtext NOT NULL,
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
  `typeId` int(11) NOT NULL DEFAULT '0',
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
  `code_facture` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `id_ecom` int(11) NOT NULL DEFAULT '0',
  `small_order_overcost_amount` float(15,5) NOT NULL DEFAULT '0.00000',
  `tva_small_order_overcost` float(15,5) NOT NULL DEFAULT '0.00000',
  `delivery_orderid` varchar(16) NOT NULL DEFAULT '',
  `delivery_infos` varchar(64) NOT NULL DEFAULT '',
  `delivery_tracking` mediumtext NOT NULL,
  `delivery_locationid` varchar(64) NOT NULL DEFAULT '',
  `moneybookers_payment_methods` varchar(50) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `email` (`email`),
  KEY `id_statut_paiement` (`id_statut_paiement`),
  KEY `code_facture` (`code_facture`(2)),
  KEY `id_ecom` (`id_ecom`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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
  `percent_remise_produit` float(5,2) NOT NULL DEFAULT '0.00',
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
  `prenom_check` varchar(255) NOT NULL DEFAULT '',
  `nom_check` varchar(255) NOT NULL DEFAULT '',
  `on_download` tinyint(1) NOT NULL DEFAULT '0',
  `statut_envoi` varchar(255) NOT NULL DEFAULT '',
  `nb_envoi` int(11) NOT NULL DEFAULT '0',
  `nb_download` int(11) NOT NULL DEFAULT '0',
  `date_download` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ecotaxe_ttc` float(15,5) NOT NULL DEFAULT '0.00000',
  `ecotaxe_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `attributs_list` MEDIUMTEXT NOT NULL,
  `nom_attribut` MEDIUMTEXT NOT NULL,
  `total_prix_attribut` float(15,5) NOT NULL DEFAULT '0.00000',
  `statut` tinyint(1) NOT NULL DEFAULT '0',
  `commentaires_admin` varchar(255) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `commande_id` (`commande_id`),
  KEY `produit_id` (`produit_id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `technical_code` (`technical_code`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `peel_configuration`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

-- --------------------------------------------------------

--
-- Structure de la table `peel_contacts`
--

CREATE TABLE IF NOT EXISTS `peel_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `site_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_contacts`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

-- --------------------------------------------------------

--
-- Structure de la table `peel_continents`
--

CREATE TABLE IF NOT EXISTS `peel_continents` (
  `id` tinyint(1) unsigned NOT NULL,
  `site_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
-- --------------------------------------------------------

--
-- Structure de la table `peel_couleurs`
--

CREATE TABLE IF NOT EXISTS `peel_couleurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prix` float(15,5) NOT NULL default '0.00000',
  `prix_revendeur` float(15,5) NOT NULL DEFAULT '0.00000',
  `percent` float(15,5) NOT NULL DEFAULT '0.00000',
  `position` int(11) NOT NULL DEFAULT '0',
  `mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `site_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `peel_devises`
--

CREATE TABLE IF NOT EXISTS `peel_devises` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devise` varchar(50) NOT NULL DEFAULT '',
  `conversion` float(15,5) NOT NULL DEFAULT '1.00000',
  `symbole` varchar(10) NOT NULL DEFAULT '',
  `symbole_place` tinyint(1) NOT NULL DEFAULT '1',
  `code` varchar(3) NOT NULL DEFAULT '',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  `main` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_devises`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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

-- --------------------------------------------------------

--
-- Structure de la table `peel_ecotaxes`
--

CREATE TABLE IF NOT EXISTS `peel_ecotaxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) NOT NULL DEFAULT '',
  `prix_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `prix_ttc` float(15,5) NOT NULL DEFAULT '0.00000',
  `coefficient` float(15,5) NOT NULL DEFAULT '0.00000',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_ecotaxes`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `default_signature_code` varchar(255) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_email_template`
--

-- Le contenu est créé automatiquement lors de l'ajout de langue via le contenu de /lib/lang/database_email_template

-- --------------------------------------------------------

--
-- Structure de la table `peel_email_template_cat`
--

CREATE TABLE IF NOT EXISTS `peel_email_template_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL DEFAULT '0',
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
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
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_html`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

-- --------------------------------------------------------

--
-- Structure de la table `peel_import_field`
--

CREATE TABLE IF NOT EXISTS `peel_import_field` (
  `champs` varchar(255) NOT NULL DEFAULT '',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `peel_import_field`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
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
  `site_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_legal`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

-- --------------------------------------------------------

--
-- Structure de la table `peel_marques`
--

CREATE TABLE IF NOT EXISTS `peel_marques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL DEFAULT '',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `promotion_devises` float(15,5) NOT NULL DEFAULT '0.00000',
  `promotion_percent` float(15,5) NOT NULL DEFAULT '0.00000',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
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
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_meta`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`),
  KEY `technical_code` (`technical_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_modules`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `site_id` int( 11 ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
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
  `disable_reductions` tinyint(1) NOT NULL DEFAULT '0',
  `site_id` int( 11 ) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `technical_code` (`technical_code`),
  KEY `site_id` (`site_id`)
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
  `tarif` float(15,5) NOT NULL DEFAULT '0.00000',
  `tarif_percent` float(5,2) NOT NULL DEFAULT '0.00',
  `tva` float(5,2) NOT NULL DEFAULT '0.00',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `totalmin` float(15,5) NOT NULL DEFAULT '0.00000',
  `totalmax` float(15,5) NOT NULL DEFAULT '0.00000',
  `site_id` int( 11 ) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `technical_code` (`technical_code`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_paiement`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `prices_thousands_separator` CHAR(1) NOT NULL,
  `prices_decimal_separator` CHAR(1) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `risque_pays` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `site_id` int( 11 ) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `position` (`position`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `peel_pays`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `reference_fournisseur` varchar(100) NOT NULL DEFAULT '',
  `ean_code` varchar(13) NOT NULL DEFAULT '',
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
  `on_reseller` tinyint(1) NOT NULL DEFAULT '0',
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
  `promotion` float(5,2) NOT NULL DEFAULT '0.00',
  `tva` float(5,2) NOT NULL DEFAULT '0.00',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
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
  `recommanded_product_on_cart_page` tinyint(1) NOT NULL DEFAULT '0',
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
  `prix_promo` float(15,5) NOT NULL DEFAULT '0.00000',
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
  `allow_add_product_with_no_stock_in_cart` tinyint(1) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `marque` (`id_marque`),
  KEY `position` (`position`),
  KEY `on_rollover` (`on_rollover`),
  KEY `on_special` (`on_special`),
  KEY `on_top` (`on_top`),
  KEY `reference` (`reference` (2)),
  KEY `site_id` (`site_id`)
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
  `quantity` int(11) NOT NULL DEFAULT '0',
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
  `position` int(11) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_profil`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `site_id` (`site_id`)
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
  `societe_type` varchar(255) NOT NULL DEFAULT '',
  `id_marques` varchar(255) NOT NULL DEFAULT '',
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
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_societe`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

-- --------------------------------------------------------

--
-- Structure de la table `peel_statut_livraison`
--

CREATE TABLE IF NOT EXISTS `peel_statut_livraison` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `position` int(11) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_statut_livraison`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

-- --------------------------------------------------------

--
-- Structure de la table `peel_statut_paiement`
--

CREATE TABLE IF NOT EXISTS `peel_statut_paiement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `position` int(11) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_statut_paiement`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

-- --------------------------------------------------------

--
-- Structure de la table `peel_tag_cloud`
--

CREATE TABLE IF NOT EXISTS `peel_tag_cloud` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(100) NOT NULL DEFAULT '',
  `nbsearch` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(2) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT '0',
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
  `poids` float(10,2) NOT NULL DEFAULT '0.00000',
  `mandatory` tinyint(1) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
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
  `totalmin` float(15,5) NOT NULL DEFAULT '0.00000',
  `totalmax` float(15,5) NOT NULL DEFAULT '0.00000',
  `tarif` float(10,2) NOT NULL DEFAULT '0.00',
  `type` int(11) NOT NULL DEFAULT '0',
  `zone` int(11) NOT NULL DEFAULT '0',
  `tva` float(5,2) NOT NULL DEFAULT '0.00',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_tarifs`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

-- --------------------------------------------------------

--
-- Structure de la table `peel_tva`
--

CREATE TABLE IF NOT EXISTS `peel_tva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tva` float(5,2) NOT NULL DEFAULT '0.00',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_tva`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

-- --------------------------------------------------------

--
-- Structure de la table `peel_types`
--

CREATE TABLE IF NOT EXISTS `peel_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `on_franco_amount` float(15,5) NOT NULL DEFAULT '0.00000',
  `position` int(11) NOT NULL DEFAULT '0',
  `without_delivery_address` tinyint(1) NOT NULL DEFAULT '0',
  `is_socolissimo` tinyint(1) NOT NULL DEFAULT '0',
  `is_icirelais` tinyint(1) NOT NULL DEFAULT '0',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_types`
--

-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

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
  `address_bill_default` varchar(32) NOT NULL DEFAULT '',
  `address_ship_default` varchar(32) NOT NULL DEFAULT '',
  `cnil` tinyint(1) NOT NULL DEFAULT '1',
  `newsletter` tinyint(1) NOT NULL DEFAULT '1',
  `newsletter_validation_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `commercial` tinyint(1) NOT NULL DEFAULT '1',
  `commercial_validation_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
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
  `etat` tinyint(1) NOT NULL DEFAULT '1',
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
  `id_cat_1` int(11) NOT NULL DEFAULT '0',
  `id_cat_2` int(11) NOT NULL DEFAULT '0',
  `id_cat_3` int(11) NOT NULL DEFAULT '0',
  `activity` varchar(255) NOT NULL DEFAULT '',
  `project_product_proposed` varchar(255) NOT NULL DEFAULT '',
  `project_date_forecasted` date NOT NULL DEFAULT '0000-00-00',
  `commercial_contact_id` int(11) NOT NULL DEFAULT '0',
  `project_budget_ht` float(15,5) NOT NULL DEFAULT '0.00000',
  `project_chances_estimated` varchar(255) NOT NULL DEFAULT '0',
  `ad_insert_delay` enum('max', 'medium', 'min') NOT NULL DEFAULT 'max',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `description_document` TEXT NOT NULL,
  `document` varchar(255) NOT NULL DEFAULT '',
  `on_client_module` tinyint(1) NOT NULL DEFAULT '0',
  `on_photodesk` tinyint(1) NOT NULL DEFAULT '0',
  `access_history` tinyint(1) NOT NULL DEFAULT '0',
  `site_id` int(11) NOT NULL DEFAULT '0',
  `parameters` TEXT NOT NULL,
  PRIMARY KEY  (`id_utilisateur`),
  KEY `code_client` (`code_client`),
  KEY `email` (`email`),
  KEY `pseudo` (`pseudo`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
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
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_login` (`user_login`(2)),
  KEY `date` (`date`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

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
  `site_id` int(11) NOT NULL DEFAULT '0',
  `update_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Structure de la table `peel_zones`
--

CREATE TABLE IF NOT EXISTS `peel_zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL DEFAULT '',
  `tva` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `on_franco` tinyint(1) NOT NULL DEFAULT '0',
  `on_franco_amount` float(15,5) NOT NULL DEFAULT '0.00000',
  `on_franco_reseller_amount` float(15,5) NOT NULL DEFAULT '0.00000',
  `on_franco_nb_products` int(5) NOT NULL DEFAULT '0',
  `payment_technical_code` varchar(255) NOT NULL DEFAULT '',
  `site_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_zones`
--
-- Le contenu est créé automatiquement lors de la création du site et l'exécution du fichier create_new_site.sql

CREATE TABLE `peel_transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `reference` varchar(255) NOT NULL,
  `report_id` int(11) UNSIGNED NOT NULL,
  `orders_id` int(11) UNSIGNED NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `reconciliation` enum('','auto','manual') NOT NULL,
  `comment` varchar(255) NOT NULL,
  `type` enum('','cc_report','cc_emitted','paypal_wire','checks','cash','wire','prelevement','reimbursement','fee','pret','sicav','salaire','atos','check','transfer','cmcic','cmcic_by_3','atos_by_3','cetelem','systempay','systempay_3x','spplus','paybox','bluepaid','bluepaid_abonnement','kwixo','kwixo_rnp','kwixo_credit','ogone','postfinance','worldpay','omnikassa','moneybookers','paypal') NOT NULL,
  `datetime` datetime NOT NULL,
  `DATE_COMPTA` varchar(255) NOT NULL,
  `LIBELLE_OPERATION` varchar(255) NOT NULL,
  `REF` varchar(255) NOT NULL,
  `DATE_OPERATION` varchar(255) NOT NULL,
  `DATE_VALEUR` varchar(255) NOT NULL,
  `MONTANT_DEBIT` varchar(255) NOT NULL,
  `MONTANT_CREDIT` varchar(255) NOT NULL,
  `L_STATUS` varchar(255) NOT NULL,
  `L_AMT` varchar(255) NOT NULL,
  `L_FEEAMT` varchar(255) NOT NULL,
  `ENTETE` varchar(255) NOT NULL,
  `TRANSACTION_ID` varchar(255) NOT NULL,
  `MERCHANT_ID` varchar(255) NOT NULL,
  `PAYMENT_MEANS` varchar(255) NOT NULL,
  `ORIGIN_AMOUNT` varchar(255) NOT NULL,
  `AMOUNT` varchar(255) NOT NULL,
  `CURRENCY_CODE` varchar(255) NOT NULL,
  `PAYMENT_DATE` varchar(255) NOT NULL,
  `PAYMENT_TIME` varchar(255) NOT NULL,
  `CARD_VALIDITY` varchar(255) NOT NULL,
  `CARD_TYPE` varchar(255) NOT NULL,
  `CARD_NUMBER` varchar(255) NOT NULL,
  `RESPONSE_CODE` varchar(255) NOT NULL,
  `CVV_RESPONSE_CODE` varchar(255) NOT NULL,
  `COMPLEMENTARY_CODE` varchar(255) NOT NULL,
  `CERTIFICATE` varchar(255) NOT NULL,
  `AUTHORIZATION_ID` varchar(255) NOT NULL,
  `CAPTURE_DATE` varchar(255) NOT NULL,
  `TRANSACTION_STATUS` varchar(255) NOT NULL,
  `RETURN_CONTEXT` varchar(255) NOT NULL,
  `AUTORESPONSE_STATUS` varchar(255) NOT NULL,
  `ORDER_ID` varchar(255) NOT NULL,
  `CUSTOMER_ID` varchar(255) NOT NULL,
  `CUSTOMER_IP_ADDRESS` varchar(255) NOT NULL,
  `ACCOUNT_SERIAL` varchar(255) NOT NULL,
  `SESSION_ID` varchar(255) NOT NULL,
  `TRANSACTION_CONDITION` varchar(255) NOT NULL,
  `CAVV_UCAF` varchar(255) NOT NULL,
  `COMPLEMENTARY_INFO` varchar(255) NOT NULL,
  `BANK_RESPONSE_CODE` varchar(255) NOT NULL,
  `MODE_REGLEMENT` varchar(255) NOT NULL,
  `3D_LS` varchar(255) NOT NULL,
  `bank` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Index pour la table `peel_transactions`
--
ALTER TABLE `peel_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `TRANSACTION_STATUS` (`TRANSACTION_STATUS`),
  ADD KEY `orders_id` (`orders_id`),
  ADD KEY `PAYMENT_DATE` (`PAYMENT_DATE`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `site_name` (`site_name`);

--
-- AUTO_INCREMENT pour la table `peel_transactions`
--
ALTER TABLE `peel_transactions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
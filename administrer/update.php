<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: update.php 55482 2017-12-11 14:58:04Z sdelaporte $
define('IN_PEEL_ADMIN', true);
define('IN_PEEL_CONFIGURE', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_UPDATE'];

$output = '';
$current_version = 0;


if(!empty($_GET['version'])) {
	$current_version = $_GET['version'];
} elseif(!empty($GLOBALS['site_parameters']['peel_database_version'])) {
	$current_version = $GLOBALS['site_parameters']['peel_database_version'];
} else {
	// Détection automatique de la version de la base de données
	$commandes_fields = get_table_field_names('peel_commandes', null, true);
	$sites_fields = get_table_field_names('peel_sites', null, true);
	$types_fields = get_table_field_names('peel_types', null, true);
	$pays_fields = get_table_field_names('peel_pays', null, true);
	$utilisateurs_fields = get_table_field_names('peel_utilisateurs', null, true);
	$codes_promo_fields = get_table_field_names('peel_codes_promos', null, true);
	$produits_fields = get_table_field_names('peel_produits', null, true);
	$bannieres_fields = get_table_field_names('peel_banniere', null, true);
	$tarifs_fields = get_table_field_names('peel_tarifs', null, true);
	$quantites_fields = get_table_field_names('peel_quantites', null, true);
	$adresses_fields = get_table_field_names('peel_adresses', null, true);
	$profil_fields = get_table_field_names('peel_profil', null, true);
	$meta_fields = get_table_field_names('peel_meta', null, true);
	$paiement_fields = get_table_field_names('peel_paiement', null, true);
	$nom_attributs_fields = get_table_field_names('peel_nom_attributs', null, true);
	$configuration_fields = get_table_field_names('peel_configuration', null, true);

	if(empty($configuration_fields)) {
		// Si la table peel_configuration est absente, on est sur une version inférieur à 7
		$sql = "SELECT *
			FROM peel_profil
			WHERE priv LIKE 'admin_content'";
		$query = query($sql);
		$result_admin_content = fetch_assoc($query);
		
		if(!empty($sites_fields) && !in_array('module_captcha', $sites_fields)) {
			$current_version = '5.71';
		} elseif(!in_array('without_delivery_address', $types_fields)) {
			$current_version = '6.0.1';
		} elseif(!in_array('iso3', $pays_fields)) {
			$current_version = '6.0.3';
		} elseif(!in_array('origin', $utilisateurs_fields)) {
			$current_version = '6.0.4';
		} elseif(!in_array('texte_libre', $nom_attributs_fields)) {
			$current_version = '6.1';
		} elseif(!empty($sites_fields) &&!in_array('nb_last_views', $sites_fields)) {
			$current_version = '6.1.1';
		} elseif(!empty($sites_fields) &&!in_array('module_precedent_suivant', $sites_fields)) {
			$current_version = '6.2';
		} elseif(!in_array('technical_code', $produits_fields)) {
			$current_version = '6.3';
		} elseif(!empty($sites_fields) && !in_array('module_conditionnement', $sites_fields)) {
			$current_version = '6.3.1';
		} elseif(!in_array('on_home_page', $bannieres_fields)) {
			$current_version = '6.4';
		} elseif(!$result_admin_content) {
			$current_version = '6.4.1';
		} elseif(!in_array('totalmin', $tarifs_fields)) {
			$current_version = '6.4.2';
		} 
	}
	if(empty($current_version)) {
		$sql = "SELECT *
			FROM peel_configuration
			WHERE technical_code LIKE 'autocomplete_hide_images'";
		$query = query($sql);
		$result_autocomplete_hide_images = fetch_assoc($query);
		
		if(!in_array('do_not_display_on_pages_related_to_user_ids_list', $bannieres_fields)) {
			$current_version = '7.0.1';
		} elseif(in_array('next_contact_reason', $utilisateurs_fields)) {
			$current_version = '7.0.2';
		} elseif(!in_array('f_datetime', $commandes_fields)) {
			$current_version = '7.0.3';
		} elseif(!in_array('totalmin', $paiement_fields)) {
			$current_version = '7.0.4';
		} elseif(empty($result_autocomplete_hide_images)) {
			$current_version = '7.1.0';
		} elseif(!in_array('order_id', $commandes_fields)) {
			$current_version = '7.1.4';
		} elseif(in_array('reve_certif', $profil_fields)) {
			$current_version = '7.2.0';
		} elseif(empty($adresses_fields) || empty($GLOBALS['site_parameters']['peel_database_version'])) {
			$current_version = '7.2.1';
		}
	}
	if(empty($current_version)) {
		$current_version = PEEL_VERSION;
	}
}

$output .= '<p>' . $GLOBALS['STR_ADMIN_UPDATE_VERSION_DETECTED'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '.$current_version.'</p>';
$sql_update_array['5.71'] = "";
if(file_exists($GLOBALS['dirroot'] . '/modules/attributs')) {
	$sql_update_array['5.71'] .= "
CREATE TABLE IF NOT EXISTS `peel_attributs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nom_attribut` int(11) NOT NULL default '0',
  `descriptif_fr` varchar(255) NOT NULL default '',
  `descriptif_en` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `prix` float(15,5) NOT NULL default '0.00000',
  `prix_revendeur` float(15,5) NOT NULL default '0.00000',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `peel_produits_attributs` (
  `produit_id` int(11) NOT NULL default '0',
  `nom_attribut_id` int(11) NOT NULL default '0',
  `attribut_id` int(11) NOT NULL default '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `peel_nom_attributs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_fr` varchar(255) NOT NULL default '',
  `nom_en` varchar(255) NOT NULL default '',
  `etat` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/tagcloud')) {
	$sql_update_array['5.71'] .= "
CREATE TABLE IF NOT EXISTS `peel_tag_cloud` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(100) NOT NULL default '',
  `nbsearch` int(11) NOT NULL default '0',
  `lang` varchar(2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/affiliation')) {
	$sql_update_array['5.71'] .= "
ALTER TABLE `peel_affiliation` CHANGE `texte_fr` `texte_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_affiliation` CHANGE `texte_en` `texte_en` MEDIUMTEXT NOT NULL ;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/faq')) {
	$sql_update_array['5.71'] .= "
ALTER TABLE `peel_faq` CHANGE `answer_fr` `answer_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_faq` CHANGE `answer_en` `answer_en` MEDIUMTEXT NOT NULL ;
";
}
	
if(file_exists($GLOBALS['dirroot'] . '/modules/parrainage')) {
	$sql_update_array['5.71'] .= "
ALTER TABLE `peel_parrain` CHANGE `texte_fr` `texte_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_parrain` CHANGE `texte_en` `texte_en` MEDIUMTEXT NOT NULL ;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/gifts')) {
	$sql_update_array['5.71'] .= "
ALTER TABLE `peel_commandes_cadeaux` CHANGE `adresse_client` `adresse_client` VARCHAR( 255 ) NOT NULL ;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/avis')) {
	$sql_update_array['5.71'] .= "
CREATE TABLE IF NOT EXISTS `peel_avis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produit` int(11) NOT NULL default '0',
  `nom_produit` varchar(255) NOT NULL default '',
  `id_utilisateur` int(11) NOT NULL default '0',
  `prenom` varchar(255) NOT NULL default '',
  `pseudo` varchar(50) NOT NULL default '',
  `note` smallint(5) NOT NULL default '0',
  `avis` varchar(255) NOT NULL default '',
  `datestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `etat` tinyint(1) NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
}
$sql_update_array['5.71'] .= "
-- FAIT après la version 5.71 :
ALTER TABLE `peel_tarifs` ADD `totalmax` float(15,5) NOT NULL DEFAULT '0.00000';
ALTER TABLE `peel_articles` CHANGE `texte_fr` `texte_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_articles` CHANGE `texte_en` `texte_en` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_categories` CHANGE `description_fr` `description_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_cgv` CHANGE `texte_fr` `texte_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_cgv` CHANGE `texte_en` `texte_en` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_contacts` CHANGE `texte_fr` `texte_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_contacts` CHANGE `texte_en` `texte_en` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_legal` CHANGE `texte_fr` `texte_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_legal` CHANGE `texte_en` `texte_en` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_marques` CHANGE `description_fr` `description_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_marques` CHANGE `description_en` `description_en` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_produits` CHANGE `descriptif_fr` `descriptif_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_produits` CHANGE `descriptif_en` `descriptif_en` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_produits` CHANGE `description_fr` `description_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_produits` CHANGE `description_en` `description_en` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_rubriques` CHANGE `description_fr` `description_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_rubriques` CHANGE `description_en` `description_en` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_sites` ADD `tag_analytics` TEXT NOT NULL;
ALTER TABLE `peel_sites` ADD `small_order_overcost_tva_percent` TEXT NOT NULL;
ALTER TABLE `peel_sites` ADD `site_suspended` ENUM('TRUE','FALSE') NOT NULL DEFAULT 'FALSE';
ALTER TABLE `peel_sites` ADD `module_forum` tinyint(4) NOT NULL default '0';
ALTER TABLE `peel_sites` ADD `module_giftlist` tinyint(4)  NOT NULL default '0';
ALTER TABLE `peel_sites` ADD `allow_add_product_with_no_stock_in_cart` tinyint(4)  NOT NULL default '0';
ALTER TABLE `peel_utilisateurs` ADD `giftlistname` VARCHAR(255);
ALTER TABLE `peel_commandes_articles` ADD `listcadeaux_owner` int(8);
ALTER TABLE `peel_categories` CHANGE `alpha` `alpha_fr` CHAR( 1 ) NOT NULL ;
ALTER TABLE `peel_categories` ADD `alpha_en` CHAR( 1 ) NOT NULL AFTER `alpha_fr` ;
ALTER TABLE `peel_categories` CHANGE `image` `image_fr` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `peel_categories` ADD `image_en` VARCHAR( 255 ) NOT NULL AFTER `image_fr` ;
ALTER TABLE `peel_produits` ADD `default_image` VARCHAR( 255 ) NOT NULL AFTER `description_en` ;
ALTER TABLE `peel_produits` ADD `tab1_html_fr` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab2_html_fr` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab3_html_fr` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab4_html_fr` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab5_html_fr` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab6_html_fr` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab1_html_en` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab2_html_en` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab3_html_en` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab4_html_en` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab5_html_en` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab6_html_en` mediumtext NOT NULL;
ALTER TABLE `peel_produits` ADD `tab1_title_fr` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab2_title_fr` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab3_title_fr` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab4_title_fr` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab5_title_fr` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab6_title_fr` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab1_title_en` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab2_title_en` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab3_title_en` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab4_title_en` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab5_title_en` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `tab6_title_en` varchar(255) NOT NULL;
ALTER TABLE `peel_produits` ADD `youtube_code` text NOT NULL AFTER `image10` ;
ALTER TABLE `peel_produits_couleurs` ADD `default_image` VARCHAR( 255 ) NOT NULL AFTER `couleur_id` ;
ALTER TABLE `peel_sites` ADD `display_nb_product` TINYINT( 1 ) NOT NULL ;
ALTER TABLE `peel_sites` ADD `module_captcha` TINYINT( 4 ) NOT NULL DEFAULT '0' AFTER `module_lot` ;
ALTER TABLE `peel_sites` ADD `module_socolissimo` tinyint(4) NOT NULL default '0';
CREATE TABLE IF NOT EXISTS `peel_forums` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `titre` varchar(255) NOT NULL DEFAULT '',
 `corps` text NOT NULL,
 `id_cat` smallint(5) unsigned NOT NULL DEFAULT '0',
 `id_sujet` int(11) unsigned NOT NULL DEFAULT '0',
 `id_membre` int(11) unsigned NOT NULL DEFAULT '0',
 `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 `date_modif` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`compteur` mediumint(7) unsigned NOT NULL DEFAULT '0',
 `post_it` enum('TRUE','FALSE') NOT NULL DEFAULT 'FALSE',
 `verrouillage` enum('TRUE','FALSE') NOT NULL DEFAULT 'FALSE',
 PRIMARY KEY (`id`),
 KEY `id_cat` (`id_cat`),
 KEY `id_sujet` (`id_sujet`),
 KEY `date_modif` (`date_modif`),
 KEY `id_membre` (`id_membre`)
 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
CREATE TABLE IF NOT EXISTS `peel_forums_cat` (
 `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 `nom` varchar(255) NOT NULL DEFAULT '',
 `description` varchar(255) NOT NULL DEFAULT '',
 `poids` tinyint(4) unsigned NOT NULL DEFAULT '0',
 `id_cat2` smallint(5) unsigned NOT NULL DEFAULT '0',
 `nb_sujets` int(11) unsigned NOT NULL DEFAULT '0',
 `nb_reponses` int(11) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 KEY `id_cat2` (`id_cat2`)
 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
CREATE TABLE IF NOT EXISTS `peel_forums_cat2` (
 `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 `nom` varchar(255) NOT NULL DEFAULT '',
 PRIMARY KEY (`id`)
 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `peel_forums_suivis` (
 `id_forum` int(11) unsigned NOT NULL DEFAULT '0',
 `id_sujet` int(11) unsigned NOT NULL DEFAULT '0',
 `id_membre` int(11) unsigned NOT NULL DEFAULT '0',
 `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
 KEY `id_sujet` (`id_sujet`,`id_membre`),
 KEY `id_membre` (`id_membre`)
 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `peel_listecadeau` (
 `id` int(11) NOT NULL auto_increment,
 `id_produit` int(11) NOT NULL default '0',
 `id_utilisateur` int(11) NOT NULL default '0',
 `date_insertion` date NOT NULL default '0000-00-00',
 `quantite` int(11) NOT NULL default '0',
 PRIMARY KEY  (`id`)
 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
 
if(file_exists($GLOBALS['dirroot'] . '/modules/lexique')) {
	$sql_update_array['5.71'] .= "
	CREATE TABLE IF NOT EXISTS `peel_lexique` (
		`id` INT( 11 ) NOT NULL ,
		`word_fr` VARCHAR( 255 ) NOT NULL ,
		`word_en` VARCHAR( 255 ) NOT NULL ,
		`definition_fr` TEXT NOT NULL ,
		`definition_en` TEXT NOT NULL ,
		`meta_title_fr` VARCHAR( 255 ) NOT NULL ,
		`meta_title_en` VARCHAR( 255 ) NOT NULL ,
		`meta_definition_fr` mediumtext NOT NULL ,
		`meta_definition_en` mediumtext NOT NULL ,
		`etat` TINYINT( 4 ) NOT NULL DEFAULT '0'
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
}
$sql_update_array['5.71'] .= "
CREATE TABLE IF NOT EXISTS `peel_security_codes` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`code` varchar(5) NOT NULL DEFAULT '',
	`time` int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
 ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `peel_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technical_code` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `display_mode` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `etat` tinyint(1) NOT NULL,
  `title_fr` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `technical_code` (`technical_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_modules`
--

INSERT INTO `peel_modules` (`id`, `technical_code`, `location`, `display_mode`, `position`, `etat`, `title_fr`, `title_en`) VALUES
(1, 'catalogue', 'left', 'sideblocktitle', 1, 1, 'Catalogue', 'Catalog'),
(2, 'tagcloud', 'left', 'sideblocktitle', 2, 1, 'Les plus recherchés', 'Top search'),
(3, 'search', 'header', '', 3, 1, 'Rechercher', 'Search'),
(4, 'guide', 'left', 'sideblocktitle', 4, 1, 'Informations', 'Information'),
(5, 'caddie', 'header', '', 1, 1, 'Votre panier', 'Your cart'),
(6, 'account', 'right', 'sideblocktitle', 2, 0, 'Mon compte', 'My account'),
(7, 'best_seller', 'right', 'sideblocktitle', 3, 1, 'Meilleures ventes', 'Best Selling'),
(8, 'news', 'right', 'sideblocktitle', 4, 1, 'A la une', 'News'),
(9, 'advertising', 'right', 'sideblock', 5, 1, 'Publicité', 'Advertising'),
(10, 'menu', 'header', '', 4, 1, 'Menu', 'Menu'),
(11, 'ariane', 'header', '', 5, 0, 'Fil d\'ariane', 'Breadcrumb');



ALTER TABLE `peel_marques` ADD `meta_titre_fr` VARCHAR( 255 ) NOT NULL ,
 ADD `meta_titre_en` VARCHAR( 255 ) NOT NULL ,
 ADD `meta_desc_fr` mediumtext NOT NULL ,
 ADD `meta_desc_en` mediumtext NOT NULL ,
 ADD `meta_key_fr` mediumtext NOT NULL ,
 ADD `meta_key_en` mediumtext NOT NULL;
ALTER TABLE `peel_marques` ADD `promotion` FLOAT( 15, 5 ) NOT NULL ,
 ADD `promotion_type` TINYINT( 4 ) NOT NULL DEFAULT '0';
ALTER TABLE `peel_categories` ADD `on_child` TINYINT( 4 ) NOT NULL ;
ALTER TABLE `peel_sites` ADD `nb_on_top` INT( 11 ) NOT NULL DEFAULT '0',
 ADD `act_on_top` TINYINT( 4 ) NOT NULL DEFAULT '0';
ALTER TABLE `peel_paiement` ADD `technical_code` VARCHAR( 255 ) NOT NULL AFTER `etat` ;
ALTER TABLE `peel_langues` ADD `url_rewriting` VARCHAR( 255 ) NOT NULL default '' ;

CREATE TABLE IF NOT EXISTS `peel_email_template` (
`id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(60) NOT NULL,
 `subject` varchar(255) NOT NULL,
 `text` text NOT NULL,
 `lang` varchar(2) NOT NULL,
 `active` enum('TRUE','FALSE') NOT NULL DEFAULT 'TRUE',
 `id_cat` int(11) NOT NULL DEFAULT '0',
 `technical_code` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `peel_email_template_cat` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name_fr` varchar(255) NOT NULL,
 `name_en` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1;


ALTER TABLE `peel_sites` ADD `small_order_limit` FLOAT( 15, 5 ) NOT NULL DEFAULT '0',
 ADD `small_order_overcost_amount` FLOAT( 15, 5 ) NOT NULL DEFAULT '0',
 ADD `tva_percent` FLOAT( 5, 2 ) NOT NULL DEFAULT '0';

ALTER TABLE `peel_commandes` ADD `small_order_overcost_amount` FLOAT( 15, 5 ) NOT NULL DEFAULT '0';
ALTER TABLE `peel_commandes` ADD `delivery_tracking` mediumtext NOT NULL;

CREATE TABLE IF NOT EXISTS `peel_import_field` (
 `champs` varchar(255) NOT NULL,
 `etat` tinyint(4) NOT NULL,
 `texte` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Structure de la table `peel_banniere`
--

CREATE TABLE IF NOT EXISTS `peel_banniere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `date_debut` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_fin` datetime NOT NULL default '0000-00-00 00:00:00',
  `etat` tinyint(4) NOT NULL default '0',
  `hit` int(11) NOT NULL default '0',
  `vue` int(11) NOT NULL default '0',
  `lien` varchar(255) NOT NULL default '',
  `position` int(1) NOT NULL default '0',
  `target` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `peel_sites` ADD `admin_force_ssl` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_produits` ADD `display_tab` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `peel_categories` ADD `promotion_devises` FLOAT( 15, 5 ) NOT NULL default '0';
ALTER TABLE `peel_categories` ADD `promotion_percent` FLOAT( 15, 5 ) NOT NULL default '0';

ALTER TABLE `peel_marques` ADD `promotion_devises` FLOAT( 15, 5 ) NOT NULL default '0';
ALTER TABLE `peel_marques` ADD `promotion_percent` FLOAT( 15, 5 ) NOT NULL default '0';

ALTER TABLE `peel_commandes` ADD `percent_remise_user` FLOAT( 15, 5 ) NOT NULL default '0.00000';
ALTER TABLE `peel_commandes` ADD `delivery_orderid` VARCHAR( 16 ) NOT NULL default '';
ALTER TABLE `peel_commandes` ADD `delivery_infos` VARCHAR( 64 ) NOT NULL default '';
";
// Création du champ technical_code dans la table peel_paiement
$sql_update_array['5.71'] .= 'UPDATE `peel_commandes` SET paiement="paypal" WHERE paiement="Carte bancaire (Paypal)" OR paiement="Paypal / CB";
UPDATE `peel_commandes` SET paiement="check" WHERE paiement="Chèque";
UPDATE `peel_commandes` SET paiement="transfer" WHERE paiement="Virement" OR paiement="Virement bancaire";
';

if(in_array('fr', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['5.71'] .= '
	UPDATE `peel_paiement` SET technical_code="paypal" WHERE nom_fr="Carte bancaire (Paypal)" OR nom_fr="Paypal / CB";
	UPDATE `peel_paiement` SET technical_code="check" WHERE nom_fr="Chèque";
	UPDATE `peel_paiement` SET technical_code="transfer" WHERE nom_fr="Virement" OR nom_fr="Virement bancaire";';
}
if(in_array('en', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['5.71'] .= '
	UPDATE `peel_paiement` SET technical_code="paypal" WHERE nom_en="Paypal" OR nom_en="Paypal / CB";
	UPDATE `peel_paiement` SET technical_code="check" WHERE nom_en="Check";
	UPDATE `peel_paiement` SET technical_code="transfer" WHERE nom_en="Wire payment";';
}
// Détéction automatique du module CB installé sur le site.
$payment_technical_code_array = array('cmcic', 'bluepaid', 'sips', 'ogone', 'fianet', 'lemonway', 'omnikassa', 'paybox', 'sadabell', 'worldpay', 'systempay', 'fianet');
$module_dir = $GLOBALS['dirroot'] . "/modules";
$payment_module_installed = array();
if ($handle = opendir($module_dir)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".." && is_dir($module_dir . '/' . $file)) {
			if(in_array($file, $payment_technical_code_array)) {
				$payment_module_installed[] = $file;
			}
		}
	}
	closedir($handle);
}
if (count($payment_module_installed) == 1) {
	// Il y a un seul module CB trouvé, il faut faire la mise à jour des codes technique dans les commandes.
	$sql_update_array['5.71'] .= '
	UPDATE peel_commandes SET paiement = "'.word_real_escape_string($payment_module_installed[0]).'" WHERE paiement = "Carte bancaire";';
} elseif(count($payment_module_installed) > 1) {
	// Si plusieurs module trouvés, ça pose un problème puisque l'on ne peut pas choisir arbitrairement un module par rapport à un autre. C'est à l'admin de choisir, donc on l'avertit : 
	$message['5.71'] = 'plusieurs module CB sont installés, veuillez mettre à jour votre base de données en exécutant la requête suivante :
	UPDATE peel_commandes SET paiement = "XXXXX" WHERE paiement = "Carte bancaire"
	XXXXX doit être remplacé par le code_technique du module CB actuellement utilisé sur le site.';
}
$sql_update_array['6.0'] = "
-- FAIT après la version 6.0 :
";
$sql_update_array['6.0.1'] = "
-- FAIT après la version 6.0.1 :
ALTER TABLE `peel_types` ADD `without_delivery_address` INT NOT NULL;
";
$sql_update_array['6.0.2'] = "
-- FAIT après la version 6.0.2 :
";
$sql_update_array['6.0.3'] = "
-- FAIT après la version 6.0.3 :
ALTER TABLE `peel_banniere` ADD `lang` char(2) NOT NULL default '';
ALTER TABLE `peel_commandes` ADD `moneybookers_payment_methods` varchar(50) NOT NULL default '';
ALTER TABLE `peel_pays` ADD `iso3` varchar(3) NOT NULL default '';
ALTER TABLE `peel_pays` ADD `iso_num` smallint(4) NOT NULL default '0';";
if(in_array('fr', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['6.0.3'] .= "
	INSERT INTO `peel_modules` (`technical_code`, `location`, `display_mode`, `position`, `etat`, `title_fr`) VALUES
	('advertising1', 'right', 'sideblock', 10, 1, 'Publicité espace 1'),
	('advertising2', 'right', 'sideblock', 11, 1, 'Publicité espace 2'),
	('advertising3', 'left', 'sideblock', 12, 1, 'Publicité espace 3'),
	('advertising4', 'right', 'sideblock', 10, 1, 'Publicité espace 4'),
	('advertising5', 'right', 'sideblock', 11, 1, 'Publicité espace 5);
	INSERT INTO `peel_paiement` (`technical_code`, `nom_fr`, `position`, `tarif`, `tva`, `etat`) VALUES
	('pickup', 'Paiement lors de l''enlèvement sur place', 5, 0.00000, 0.00, 0),
	('delivery', 'Paiement à la livraison', 6, 0.00000, 0.00, 0);";
}
if(in_array('en', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['6.0.3'] .= "
	INSERT INTO `peel_modules` (`id`, `technical_code`, `location`, `display_mode`, `position`, `etat`, `title_en`) VALUES
	(12, 'advertising1', 'right', 'sideblock', 10, 1, 'Advertising location 1'),
	(13, 'advertising2', 'right', 'sideblock', 11, 1, 'Advertising location 2'),
	(14, 'advertising3', 'left', 'sideblock', 12, 1, 'Advertising location 3'),
	(15, 'advertising4', 'right', 'sideblock', 10, 1, 'Advertising location 4'),
	(16, 'advertising5', 'right', 'sideblock', 11, 1, 'Advertising location 5');
	INSERT INTO `peel_paiement` (`id`, `technical_code`, `nom_en`, `position`, `tarif`, `tva`, `etat`) VALUES
	(21, 'pickup', 'Payment upon pickup', 5, 0.00000, 0.00, 0),
	(22, 'delivery', 'Payment upon delivery', 6, 0.00000, 0.00, 0);";
}
$sql_update_array['6.0.4'] = "
-- FAIT après la version 6.0.4 :
ALTER TABLE `peel_utilisateurs` ADD `origin` INT( 11 ) NULL default '0', ADD `origin_other` VARCHAR( 255 )  NOT NULL default '';
ALTER TABLE `peel_sites` ADD `module_autosend` tinyint( 1 ) NOT NULL default '0', ADD `module_autosend_delay` INT( 11 ) NOT NULL default '0';
ALTER TABLE `peel_sites` ADD `minimal_amout_to_order` FLOAT( 15, 5 ) NOT NULL DEFAULT '0.00000' AFTER `small_order_overcost_tva_percent`;
ALTER TABLE peel_utilisateurs CHANGE  `avoir` `avoir` float(15,5) NOT NULL default '0.00000';
ALTER TABLE peel_commandes CHANGE  `numero` `numero` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `peel_commandes` ADD `tva_small_order_overcost` FLOAT( 15, 5 ) NOT NULL DEFAULT '0.00000' AFTER `small_order_overcost_amount`;
ALTER TABLE `peel_commandes` ADD `delivery_locationid` varchar(64) NOT NULL DEFAULT '' AFTER `delivery_tracking`;
CREATE TABLE IF NOT EXISTS `peel_butterflive` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `param` varchar(25) NOT NULL default '',
 `value` text NOT NULL default '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
ALTER TABLE `peel_sites` ADD `nom_expediteur` VARCHAR( 255 ) NOT NULL default '' AFTER `email_client`;
";
if(file_exists($GLOBALS['dirroot'] . '/modules/pensebete')) {
	$sql_update_array['6.0.4'] .= "
CREATE TABLE IF NOT EXISTS `peel_pensebete` (
	`id` int(11) NOT NULL AUTO_INCREMENT ,
	`id_produit` int(11) NOT NULL ,
	`id_utilisateur` int(11) NOT NULL ,
	`date_insertion` date NOT NULL ,
	PRIMARY KEY ( `id` )
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
}
$sql_update_array['6.1'] = "
-- FAIT après la version 6.1 :
ALTER TABLE `peel_codes_promos` DROP `nb_valide`;
ALTER TABLE `peel_commandes` DROP `code_cheque`,  DROP `code_bon`, DROP `valeur_cheque_cadeau`;
";
if(file_exists($GLOBALS['dirroot'] . '/modules/avis')) {
	$sql_update_array['6.1'] .= "
ALTER TABLE `peel_avis` ADD `lang` char(2) NOT NULL default '';";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/attributs')) {
	$sql_update_array['6.1'] .= "
ALTER TABLE `peel_attributs` ADD `position` int(11) NOT NULL default '0';
ALTER TABLE `peel_nom_attributs` ADD `texte_libre` tinyint(4) NOT NULL default '0';";
}
$sql_update_array['6.1.1'] = "
-- FAIT après la version 6.1.1 :
ALTER TABLE `peel_produits` ADD `on_estimate` tinyint(1) NOT NULL default '0', ADD `on_gift` tinyint(1) NOT NULL default '0', ADD `on_gift_points` int(11) NOT NULL default '0',  ADD `extra_link` varchar(255) NOT NULL default '';

ALTER TABLE `peel_rubriques` ADD `texte_libre` tinyint(4) NOT NULL default '0';
ALTER TABLE `peel_categories` ADD `on_carrousel` tinyint(4) NOT NULL default '0';
ALTER TABLE `peel_sites` ADD  `nb_last_views` INT( 11 ) NOT NULL DEFAULT  '0', ADD `secret_word` varchar(255) NOT NULL default '', ADD `type_affichage_attribut` tinyint(1) NOT NULL default '0', ADD `fb_admins` varchar(25) NOT NULL default '', ADD `facebook_page_link` varchar(25) NOT NULL default '';
ALTER TABLE `peel_banniere` ADD `tag_html` mediumtext NOT NULL default '';
ALTER TABLE `peel_commandes_articles` ADD `id` TINYINT( 1 ) NOT NULL DEFAULT '0', ADD `statut` TINYINT( 1 ) NOT NULL DEFAULT '0';

INSERT INTO `peel_devises` (`devise`, `conversion`, `symbole`, `symbole_place`, `code`, `etat`) VALUES
('CH Fr. Suisse', 1.41987, 'Fr', 1, 'CHF', 0),
('US Dollar', 1.21553, '$', 0, 'USD', 0),
('CA Dollar', 1.27708, '$', 0, 'CAD', 0),
('JP Yen', 110.56900, '¥', 1, 'JPY', 0),
('GB Pound', 0.83554, '£', 1, 'GBP', 0);

INSERT INTO peel_html (lang, contenu_html, etat, titre, o_timestamp, a_timestamp, emplacement) VALUES
('fr', '<p>Introduction personnalisable</p>', 0, 'Devenir revendeur', NOW(), NOW(), 'devenir_revendeur'),
('en', '<div class=\"header_few_words_center\">[SITE]</div>\r\n<div class=\"header_few_words_right\">Open eCommerce</div>', 1, 'En-tête de la boutique', NOW(), NOW(), 'header');

INSERT INTO `peel_email_template` (`name`, `subject`, `text`, `lang`, `active`, `id_cat`, `technical_code`) VALUES
('[SITE] Commande n° [ORDER_ID] - Annulation des points', '[SITE] Commande n° [ORDER_ID] - Annulation des points', 'Bonjour [USER_NAME],\r\n\r\nUnder the loyalty program of [SITE] and because of the change in status of your order number [ORDER_ID], [CANCELED_POINTS] points allocated to this command have been withdrawn from your account. \r\n Your balance is now: [USER_POINTS] points. \r\n\r\n We thank you for your trust. \r\n\r\n see you soon on [SITE].', 'fr', 'TRUE', 1, 'payback_points_cancellation'),
('[SITE] Commande n° [ORDER_ID] - Points cancellation', '[SITE] Commande n° [ORDER_ID] - Points cancellation', 'Bonjour [USER_NAME],\r\n\r\nDans le cadre du programme de fidélité de [SITE] et en raison du changement de statut de votre commande numéro [ORDER_ID], [CANCELED_POINTS]points affectés à cette commande viennent d''être retirés de votre compte.\r\nVotre solde est à présent de : [USER_POINTS]points.\r\n\r\nNous vous remercions pour votre confiance.\r\n\r\nÀ bientôt sur [SITE].', 'en', 'TRUE', 1, 'payback_points_cancellation'),
('[SITE] Commande n° [ORDER_ID] - Remise des points', '[SITE] Commande n° [ORDER_ID] - Remise des points', 'Bonjour [USER_NAME],\r\n\r\nDans le cadre du programme de fidélité de [SITE] et en raison du changement de statut de votre commande numéro [ORDER_ID], [CANCELED_POINTS]points affectés à cette commande viennent d''être ajouté à votre compte.\r\nVotre solde est à présent de : [USER_POINTS]points.\r\n\r\nNous vous remercions pour votre confiance.\r\n\r\nÀ bientôt sur [SITE].', 'fr', 'TRUE', 1, 'payback_points_resetting'),
('[SITE] Commnde n° [ORDER_ID] - Discount points', '[SITE] Commande n° [ORDER_ID] - Discount points', 'Bonjour [USER_NAME],\r\n\r\nUnder the loyalty program of [SITE] and because of the change in status of your order number [ORDER_ID], [CANCELED_POINTS] points allocated to this command have been added from your account. \r\n Your balance is now: [USER_POINTS] points. \r\n\r\n We thank you for your trust. \r\n\r\n see you soon on [SITE]..', 'en', 'TRUE', 1, 'payback_points_resetting');
";
if(file_exists($GLOBALS['dirroot'] . '/modules/gifts')) {
	$sql_update_array['6.1.1'] .= "
ALTER TABLE `peel_commandes_cadeaux` CHANGE  `nom_cadeau` `produit_id` int(11) NOT NULL default '0';
";
}
$sql_update_array['6.2'] = "
-- FAIT après la version 6.2 :
ALTER TABLE `peel_banniere` ADD `id_categorie` INT(11) NOT NULL DEFAULT '0', ADD `height` VARCHAR(8) NOT NULL DEFAULT '', ADD `width` VARCHAR(8) NOT NULL DEFAULT '', ADD `extra_javascript` VARCHAR(255) NOT NULL DEFAULT '', ADD `appearance` enum('FIRST_PAGE_ONLY','ALL_BUT_FIRST','ALL') NOT NULL DEFAULT 'FIRST_PAGE_ONLY', ADD `rang` int(11) NOT NULL DEFAULT '0', CHANGE `etat` `etat` tinyint(1) NOT NULL DEFAULT '0';";
if(file_exists($GLOBALS['dirroot'] . '/modules/attributs')) {
	$sql_update_array['6.2'] .= "
ALTER TABLE `peel_nom_attributs` ADD `upload` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `etat` `etat` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `texte_libre` `texte_libre` tinyint(1) NOT NULL DEFAULT '0';";
}

$sql_update_array['6.2'] .= "
ALTER TABLE `peel_sites` ADD `module_precedent_suivant` tinyint(1) NOT NULL DEFAULT '1',ADD `module_flash` tinyint(1) NOT NULL DEFAULT '0', ADD `popup_width` INT(11) NOT NULL DEFAULT '260', ADD `quotation_delay` varchar(25) NOT NULL DEFAULT '6 mois', ADD `in_category` tinyint(1) NOT NULL DEFAULT '0', ADD `popup_height` INT(11) NOT NULL DEFAULT '130', ADD `availability_of_carrier` int(11) NOT NULL DEFAULT '0', ADD `facebook_connect` varchar(255) NOT NULL DEFAULT '0', ADD `fb_appid` varchar(255) NOT NULL DEFAULT '0', ADD `fb_secret` varchar(255) NOT NULL DEFAULT '0', ADD `fb_baseurl` varchar(255) NOT NULL DEFAULT '0', ADD `template_multipage` varchar(255) NOT NULL DEFAULT 'default_1', ADD `auto_promo` tinyint(1) NOT NULL DEFAULT '0', CHANGE `logo` `logo_fr` varchar(255) NOT NULL DEFAULT '', ADD `logo_en` varchar(255) NOT NULL DEFAULT '', ADD `category_order_on_catalog` tinyint(1) NOT NULL DEFAULT '0', ADD `module_cart_preservation` TINYINT( 1 ) NOT NULL DEFAULT '1', ADD `module_vacances` TINYINT( 1 ) NOT NULL DEFAULT '0', ADD `module_vacances_type` TINYINT( 1 ) NOT NULL DEFAULT '0', ADD `module_vacances_client_msg_fr` VARCHAR( 255 ) NOT NULL DEFAULT '', ADD `favicon` VARCHAR( 255 ) NOT NULL DEFAULT '', ADD `rueducommerce_mmid` VARCHAR( 25 ) NOT NULL DEFAULT '', ADD `module_vacances_client_msg_en` VARCHAR( 255 ) NOT NULL DEFAULT '', CHANGE `mode_transport` `mode_transport` tinyint(1) NOT NULL DEFAULT '1' , CHANGE `act_on_top` `act_on_top` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `display_prices_with_taxes` `display_prices_with_taxes` tinyint(1) NOT NULL DEFAULT '1' , CHANGE `allow_add_product_with_no_stock_in_cart` `allow_add_product_with_no_stock_in_cart` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `html_editor` `html_editor` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `display_prices_with_taxes_in_admin` `display_prices_with_taxes_in_admin` tinyint(1) NOT NULL DEFAULT '1' , CHANGE `type_rollover` `type_rollover` tinyint(1) NOT NULL DEFAULT '2' , ADD `module_vacances_fournisseur` tinyint(1) NOT NULL DEFAULT '1';";

$sql_update_array['6.2'] .= "
UPDATE `peel_sites` SET `template_multipage` = 'default_1' WHERE `id` = 1;
-- L'état d'utilisateur n'était pas géré sur les versions antérieur de peel, mais un champ était présent et à 0 par défaut

UPDATE `peel_utilisateurs` SET `etat` = 1;

ALTER TABLE `peel_societe` CHANGE `tel` `tel` varchar(32) NOT NULL DEFAULT '0', CHANGE `fax` `fax` varchar(32) NOT NULL DEFAULT '0';
ALTER TABLE `peel_sites` CHANGE `module_captcha` `module_captcha` tinyint(0) NOT NULL DEFAULT '1', ADD `global_remise_percent` float(15,5) NOT NULL DEFAULT '0.00';
ALTER TABLE `peel_types` ADD `etat` tinyint(1) NOT NULL DEFAULT '0';
-- Ajout du champ etat dans la table peel_types
UPDATE peel_types SET etat = 1;

INSERT INTO `peel_html` (lang, contenu_html, etat, titre, o_timestamp, a_timestamp, emplacement) VALUES
('fr','<p>Bas de page de la boutique personnalisable dans lequel on peut insérer des liens (footer_link)</p>', 1, 'Liens du Footer', NOW(), NOW(), 'footer_link'),
('fr','<h1>La page demandée n''est pas disponible</h1><br />', 1, 'Page d''erreur 404', NOW(), NOW(), 'error404'),
('en','<h1>This page is not found</h1><br />', 1, 'Error 404 page content', NOW(), NOW(), 'error404');
INSERT INTO `peel_modules` (`technical_code`, `location`, `display_mode`, `position`, `etat`, `title_fr`, `title_en`) VALUES 
('paiement_secu', 'left', 'sideblocktitle', 2, 0, 'Paiement sécurisé', 'Secure payment');
ALTER TABLE `peel_utilisateurs` ADD `project_date_forecasted` DATE NOT NULL DEFAULT '0000-00-00' , ADD `project_product_proposed` VARCHAR( 255 ) NOT NULL DEFAULT '' , ADD `promo` VARCHAR( 20 ) NOT NULL DEFAULT'' , ADD `id_cat_1` int(11) NOT NULL DEFAULT '0' , ADD `id_cat_2` int(11) NOT NULL DEFAULT '0' , ADD `id_cat_3` int(11) NOT NULL DEFAULT '0' , ADD `commercial_contact_id` int(11) DEFAULT '0' , ADD `project_budget_ht` float(15,5) DEFAULT '0' , ADD `project_chances_estimated` varchar(255) NOT NULL DEFAULT '' , ADD `logo` varchar(255) DEFAULT '' , ADD `ad_insert_delay` enum('max', 'medium', 'min') NOT NULL DEFAULT 'max' , ADD `lang` varchar(2) NOT NULL DEFAULT '' , ADD `on_vacances` tinyint(1) NOT NULL DEFAULT '0' , ADD `on_vacances_date` date NOT NULL DEFAULT '0000-00-00' , ADD `seg_buy` enum('no','one_old','one_recent','multi_old','multi_recent','no_info') NOT NULL DEFAULT 'no_info' , ADD `seg_want` enum('min_contact','max_contact','no_matter','no_info') NOT NULL DEFAULT 'no_info' , ADD `seg_think` enum('never_budget','no_budget','unsatisfied','satisfied','not_interested','interested','newbie','no_matter','no_info') NOT NULL DEFAULT 'no_info' , ADD `seg_followed` enum('no','poor','correct','no_info') NOT NULL DEFAULT 'no_info' , ADD `seg_who` enum('independant','partner','company_small','company_medium','company_big','person','no_info') NOT NULL DEFAULT 'no_info', ADD `id_salerepresentative` int(11) NOT NULL default '0', CHANGE `etat` `etat` int(1) NOT NULL DEFAULT '1', ADD `Valid` enum('NO','YES','AGENT','PROSP') NOT NULL DEFAULT 'YES', CHANGE `newsletter` `newsletter` tinyint(1) NOT NULL DEFAULT '1' , ADD `next_contact_reason` enum('','renewal_expected','payment_expected','planified','commercial_action','usual') NULL DEFAULT '' , CHANGE `commercial` `commercial` tinyint(1) NOT NULL DEFAULT '1' , CHANGE `cnil` `cnil` tinyint(1) NOT NULL DEFAULT '1' , ADD `Admis` enum('NO','OK') NOT NULL DEFAULT 'OK';

ALTER TABLE `peel_pays` ADD `continent_id` tinyint(1) unsigned NOT NULL DEFAULT '0', ADD `risque_pays` tinyint(1) unsigned NOT NULL DEFAULT '0';


ALTER TABLE `peel_modules` ADD `in_home` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_codes_promos` ADD `nb_used_per_client` int(11) NOT NULL DEFAULT '1', CHANGE `remise_percent` `remise_percent` float(15,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `peel_produits` ADD `nb_ref_produits` int(11) NOT NULL DEFAULT '0', ADD `on_ref_produit` tinyint(1) NOT NULL DEFAULT '0',ADD `nb_view` int(11) NOT NULL DEFAULT '0', ADD `ean_code` VARCHAR(13) NOT NULL DEFAULT '', CHANGE `display_price_by_weight` `display_price_by_weight` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `prix_promo` `prix_promo` float(15,2) NOT NULL DEFAULT '0.00' , CHANGE `promotion` `promotion` float(15,2) NOT NULL DEFAULT '0.00' , CHANGE `affiche_stock` `affiche_stock` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `etat_stock` `etat_stock` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `comments` `comments` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE `peel_zones` ADD `on_franco_amount` float(15,5) NOT NULL DEFAULT '0.00000', ADD on_franco_nb_products tinyint(5) NOT NULL DEFAULT 0;
ALTER TABLE `peel_newsletter` ADD `message_en` mediumtext NOT NULL DEFAULT '' , ADD `sujet_en` mediumtext NOT NULL DEFAULT '' , ADD `message_fr` mediumtext NOT NULL DEFAULT '' , ADD `sujet_fr` mediumtext NOT NULL DEFAULT '' , DROP COLUMN `message`, DROP COLUMN `sujet`;

ALTER TABLE `peel_utilisateurs_codes_promos` CHANGE `utilise` `utilise` tinyint(3) NOT NULL DEFAULT '0' ;

ALTER TABLE `peel_profil` ADD `document` varchar(255) NOT NULL DEFAULT '' , ADD `description_document` text NOT NULL DEFAULT '' ;
INSERT INTO `peel_profil` (`name`, `priv`) VALUES ('Revendeur certified', 'reve_certif');

ALTER TABLE `peel_commandes_articles` CHANGE `statut` `statut` tinyint(1) NOT NULL DEFAULT '0' ;
ALTER TABLE `peel_commandes` CHANGE `affilie` `affilie` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `points_etat` `points_etat` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `statut_affilie` `statut_affilie` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `zone_tva` `zone_tva` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `zone_franco` `zone_franco` tinyint(1) NOT NULL DEFAULT '0' ;
ALTER TABLE `peel_categories` CHANGE `on_carrousel` `on_carrousel` tinyint(1) NOT NULL DEFAULT '0' , CHANGE `type_affichage` `type_affichage` tinyint(1) NOT NULL DEFAULT '0' ;
ALTER TABLE `peel_tarifs` CHANGE `type` `type` tinyint(1) NOT NULL DEFAULT '0' ;
";
if(file_exists($GLOBALS['dirroot'] . '/modules/stock_advanced')) {
	$sql_update_array['6.2'] .= "
ALTER TABLE `peel_etatstock` CHANGE `valeur` `valeur` tinyint(1) NOT NULL DEFAULT '0' ;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/pensebete')) {
	$sql_update_array['6.2'] .= "
ALTER TABLE `peel_pensebete` CHANGE `date_insertion` `date_insertion` date NOT NULL DEFAULT '0000-00-00' , CHANGE `id_produit` `id_produit` int(11) NOT NULL DEFAULT '0' , CHANGE `id_utilisateur` `id_utilisateur` int(11) NOT NULL DEFAULT '0' ;";
}
$sql_update_array['6.2'] .= "
ALTER TABLE `peel_utilisateurs` ADD `description_document` TEXT NOT NULL;

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
`id_user` int(11) NOT NULL,
`commande_id` int(11) NOT NULL,
`dispo` varchar(50) NOT NULL DEFAULT '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Structure de la table `peel_admins_contacts_planified`
--
CREATE TABLE IF NOT EXISTS `peel_admins_contacts_planified` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`user_id` int(11) unsigned NOT NULL,
`admin_id` int(11) unsigned NOT NULL,
`timestamp` int(11) unsigned NOT NULL,
`reason` enum('','interesting_profile','interested_by_product','payment_expected','follow_up') NOT NULL,
`comments` varchar(255) NOT NULL,
`actif` enum('TRUE','FALSE') NOT NULL DEFAULT 'TRUE',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `peel_admins_actions` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`id_user` int(11) unsigned NOT NULL DEFAULT '0',
`action` enum('','SEARCH_USER','ADD_FILTER','EDIT_FILTER','DEL_FILTER','EDIT_AD','SUP_AD','EDIT_VOTE','SUP_DETAILS','EDIT_PROFIL','EDIT_FORUM','SUP_FORUM','SUP_COMPTE','ACTIVATE_COMPTE','NOTES_RECUES','NOTES_DONNEES','NOTE_PROFIL','AUTRE','SEND_EMAIL','CREATE_ORDER','EDIT_ORDER','SUP_ORDER','PHONE_EMITTED','PHONE_RECEIVED','EVENT') NOT NULL DEFAULT '',
`id_membre` int(11) unsigned NOT NULL DEFAULT '0',
`data` varchar(255) NOT NULL DEFAULT '',
`raison` varchar(255) NOT NULL DEFAULT '',
`remarque` text NOT NULL DEFAULT '',
`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
KEY `id_user` (`id_user`),
KEY `id_membre` (`id_membre`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `peel_utilisateur_connexions` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`user_id` int(11) unsigned NOT NULL DEFAULT '0',
`user_login` varchar(255) NOT NULL DEFAULT '',
`user_ip` int(15) unsigned NOT NULL DEFAULT '0',
`date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`),
KEY `user_login` (`user_login`(2)),
KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `peel_admins_comments` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_user` int(11) unsigned NOT NULL,
`admin_id` int(11) unsigned NOT NULL,
`timestamp` int(11) unsigned NOT NULL,
`comments` text NOT NULL,
PRIMARY KEY (`id`),
KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `peel_continents` (
`id` tinyint(1) unsigned NOT NULL DEFAULT '0',
`name_fr` varchar(100) NOT NULL DEFAULT '',
`name_en` varchar(100) NOT NULL DEFAULT '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_continents`
--

INSERT INTO `peel_continents` (`id`, `name_fr`, `name_en`) VALUES
(1, 'Afrique', 'Africa'),
(2, 'Amérique', 'Americas'),
(3, 'Asie', 'Asia'),
(4, 'Europe', 'Europe'),
(5, 'Océanie', 'Oceania'),
(6, 'Antartique', 'Antartic');

DROP TABLE peel_pays;

--
-- Structure de la table `peel_pays`
--

CREATE TABLE IF NOT EXISTS `peel_pays` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`continent_id` tinyint(1) unsigned NOT NULL,
`lang` varchar(2) NOT NULL DEFAULT '',
`nom_fr` varchar(150) NOT NULL DEFAULT '',
`nom_en` varchar(150) NOT NULL DEFAULT '',
`pays_fr` varchar(255) NOT NULL DEFAULT '',
`pays_en` varchar(255) NOT NULL DEFAULT '',
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
KEY `nom_fr` (`nom_fr`),
KEY `nom_en` (`nom_en`),
KEY `position` (`position`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=239 ;

--
-- Contenu de la table `peel_pays`
--

INSERT INTO `peel_pays` (`id`, `continent_id`, `lang`, `nom_fr`, `nom_en`, `pays_fr`, `pays_en`, `flag`, `zone`, `etat`, `iso`, `iso3`, `iso_num`, `devise`, `position`, `risque_pays`) VALUES
(1, 4, 'fr', '', '', 'France', 'France', 'fr.gif', 1, 1, 'FR', 'FRA', 250, 'EUR', 79, 0),
(2, 3, 'en', '', '', 'Afghanistan', 'Afghanistan', 'af.gif', 4, 1, 'AF', 'AFG', 4, 'AFA', 1, 0),
(3, 1, 'en', '', '', 'Afrique du sud', 'South Africa', 'za.gif', 4, 1, 'ZA', 'ZAF', 710, 'ZAR', 2, 0),
(4, 4, 'en', '', '', 'Albanie', 'Albania', 'al.gif', 4, 1, 'AL', 'ALB', 8, 'ALL', 3, 0),
(5, 1, 'fr', '', '', 'Algérie', 'Algeria', 'dz.gif', 4, 1, 'DZ', 'DZA', 12, 'DZD', 4, 0),
(6, 4, 'en', '', '', 'Allemagne', 'Germany', 'de.gif', 3, 1, 'DE', 'DEU', 276, 'EUR', 5, 0),
(7, 3, 'en', '', '', 'Arabie saoudite', 'Saudi Arabia', 'sa.gif', 4, 1, 'SA', 'SAU', 682, 'SAR', 12, 0),
(8, 2, 'en', '', '', 'Argentine', 'Argentina', 'ar.gif', 4, 1, 'AR', 'ARG', 32, 'ARS', 13, 0),
(9, 5, 'en', '', '', 'Australie', 'Australia', 'au.gif', 4, 1, 'AU', 'AUS', 36, 'AUD', 16, 0),
(10, 4, 'en', '', '', 'Autriche', 'Austria', 'at.gif', 3, 1, 'AT', 'AUT', 40, 'EUR', 21, 0),
(11, 4, 'fr', '', '', 'Belgique', 'Belgium', 'be.gif', 3, 1, 'BE', 'BEL', 56, 'EUR', 27, 0),
(12, 2, 'en', '', '', 'Brésil', 'Brazil', 'br.gif', 4, 1, 'BR', 'BRA', 76, 'BRL', 36, 0),
(13, 4, 'en', '', '', 'Bulgarie', 'Bulgaria', 'bg.gif', 3, 1, 'BG', 'BGR', 100, 'BGN', 38, 0),
(14, 2, 'en', '', '', 'Canada', 'Canada', 'ca.gif', 4, 1, 'CA', 'CAN', 124, 'CAD', 43, 0),
(15, 2, 'en', '', '', 'Chili', 'Chile', 'cl.gif', 4, 1, 'CL', 'CHL', 152, 'CLP', 47, 0),
(16, 3, 'en', '', '', 'Chine (Rép. pop.)', 'China', 'cn.gif', 4, 1, 'CN', 'CHN', 156, 'CNY', 48, 0),
(17, 2, 'en', '', '', 'Colombie', 'Colombia', 'co.gif', 4, 1, 'CO', 'COL', 170, 'COP', 50, 0),
(18, 3, 'en', '', '', 'Corée du Sud', 'Korea (Republic of) (South)', 'kr.gif', 4, 1, 'KR', 'KOR', 410, 'KRW', 56, 0),
(19, 2, 'en', '', '', 'Costa Rica', 'Costa Rica', 'cr.gif', 4, 1, 'CR', 'CRI', 188, 'CRC', 57, 0),
(20, 4, 'en', '', '', 'Croatie', 'Croatia', 'hr.gif', 4, 1, 'HR', 'HRV', 191, 'HRK', 59, 0),
(21, 4, 'en', '', '', 'Danemark', 'Denmark', 'dk.gif', 3, 1, 'DK', 'DNK', 208, 'DKK', 61, 0),
(22, 1, 'en', '', '', 'Egypte', 'Egypt', 'eg.gif', 4, 1, 'EG', 'EGY', 818, 'EGP', 65, 0),
(23, 3, 'en', '', '', 'Emirats arabes unis', 'United Arab Emirates', 'ae.gif', 4, 1, 'AE', 'ARE', 784, 'AED', 67, 0),
(24, 2, 'en', '', '', 'Equateur', 'Ecuador', 'ec.gif', 4, 1, 'EC', 'ECU', 218, 'USD', 68, 0),
(25, 2, 'en', '', '', 'Etats-Unis', 'USA (United States of America)', 'us.gif', 4, 1, 'US', 'USA', 840, 'USD', 72, 0),
(26, 2, 'en', '', '', 'El Salvador', 'El Salvador', 'sv.gif', 4, 1, 'SV', 'SLV', 222, 'USD', 66, 0),
(27, 4, 'en', '', '', 'Espagne', 'Spain', 'es.gif', 3, 1, 'ES', 'ESP', 724, 'EUR', 70, 0),
(28, 4, 'en', '', '', 'Finlande', 'Finland', 'fi.gif', 3, 1, 'FI', 'FIN', 246, 'EUR', 78, 0),
(29, 4, 'en', '', '', 'Grèce', 'Greece', 'gr.gif', 3, 1, 'GR', 'GRC', 300, 'EUR', 95, 0),
(30, 3, 'en', '', '', 'Hong Kong', 'Hong Kong', 'hk.gif', 4, 1, 'HK', 'HKG', 344, 'HKD', 105, 0),
(31, 4, 'en', '', '', 'Hongrie', 'Hungary', 'hu.gif', 3, 1, 'HU', 'HUN', 348, 'HUF', 106, 0),
(32, 3, 'en', '', '', 'Inde', 'India', 'in.gif', 4, 1, 'IN', 'IND', 356, 'INR', 107, 0),
(33, 3, 'en', '', '', 'Indonésie', 'Indonesia', 'id.gif', 4, 1, 'ID', 'IDN', 360, 'IDR', 108, 0),
(34, 4, 'en', '', '', 'Irlande', 'Ireland', 'ie.gif', 3, 1, 'IE', 'IRL', 372, 'EUR', 111, 0),
(35, 3, 'en', '', '', 'Isra', 'Israel', 'il.gif', 4, 1, 'IL', 'ISR', 376, 'ILS', 113, 0),
(36, 4, 'en', '', '', 'Italie', 'Italy', 'it.gif', 3, 1, 'IT', 'ITA', 380, 'EUR', 114, 0),
(37, 3, 'en', '', '', 'Japon', 'Japan', 'jp.gif', 4, 1, 'JP', 'JPN', 392, 'JPY', 116, 0),
(38, 3, 'en', '', '', 'Jordanie', 'Jordan', 'jo.gif', 4, 1, 'JO', 'JOR', 400, 'JOD', 117, 0),
(39, 3, 'en', '', '', 'Liban', 'Lebanon', 'lb.gif', 4, 1, 'LB', 'LBN', 422, 'USD', 126, 0),
(40, 3, 'en', '', '', 'Malaisie', 'Malaysia', 'my.gif', 4, 1, 'MY', 'MYS', 458, 'MYR', 135, 0),
(41, 1, 'fr', '', '', 'Maroc', 'Morocco', 'ma.gif', 4, 1, 'MA', 'MAR', 504, 'MAD', 141, 0),
(42, 2, 'en', '', '', 'Mexique', 'Mexico', 'mx.gif', 4, 1, 'MX', 'MEX', 484, 'MXN', 145, 0),
(43, 4, 'en', '', '', 'Norvége', 'Norway - Bouvet Island', 'bv.gif', 4, 1, 'BV', 'BVT', 74, 'NOK', 160, 0),
(44, 5, 'en', '', '', 'Nouvelle-Zélande', 'New Zealand', 'nz.gif', 4, 1, 'NZ', 'NZL', 554, 'NZD', 164, 0),
(45, 2, 'en', '', '', 'Pérou', 'Peru', 'pe.gif', 4, 1, 'PE', 'PER', 604, 'PEN', 175, 0),
(46, 3, 'en', '', '', 'Pakistan', 'Pakistan', 'pk.gif', 4, 1, 'PK', 'PAK', 586, 'PKR', 169, 0),
(47, 4, 'en', '', '', 'Pays-Bas', 'Netherlands', 'nl.gif', 3, 1, 'NL', 'NLD', 528, 'EUR', 174, 0),
(48, 3, 'en', '', '', 'Philippines', 'Philippines', 'ph.gif', 4, 1, 'PH', 'PHL', 608, 'PHP', 176, 0),
(49, 4, 'en', '', '', 'Pologne', 'Poland', 'pl.gif', 3, 1, 'PL', 'POL', 616, 'PLN', 178, 0),
(50, 2, 'en', '', '', 'Porto Rico', 'Puerto Rico', 'pr.gif', 4, 1, 'PR', 'PRI', 630, 'USD', 179, 0),
(51, 4, 'en', '', '', 'Portugal', 'Portugal', 'pt.gif', 3, 1, 'PT', 'PRT', 620, 'EUR', 180, 0),
(52, 4, 'en', '', '', 'Tchèque (Rep.)', 'Czech (Rep.)', 'cz.gif', 3, 1, 'CZ', 'CZE', 203, 'CZK', 213, 0),
(53, 4, 'en', '', '', 'Roumanie', 'Romania', 'ro.gif', 3, 1, 'RO', 'ROU', 642, 'ROL', 182, 0),
(54, 4, 'en', '', '', 'Royaume-Uni (UK)', 'United Kingdom (UK)', 'gb.gif', 3, 1, 'GB', 'GBR', 826, 'GBP', 183, 0),
(55, 4, 'en', '', '', 'Russie', 'Russia', 'ru.gif', 4, 1, 'RU', 'RUS', 643, 'RUB', 184, 0),
(56, 3, 'en', '', '', 'Singapour', 'Singapore', 'sg.gif', 4, 1, 'SG', 'SGP', 702, 'SGD', 198, 0),
(57, 4, 'en', '', '', 'Suède', 'Sweden', 'se.gif', 3, 1, 'SE', 'SWE', 752, 'SEK', 204, 0),
(58, 4, 'en', '', '', 'Suisse', 'Switzerland', 'ch.gif', 4, 1, 'CH', 'CHE', 756, 'CHF', 205, 0),
(59, 3, 'en', '', '', 'Taiwan', 'Taiwan', 'tw.gif', 4, 1, 'TW', 'TWN', 158, 'TWD', 210, 0),
(60, 3, 'en', '', '', 'Thailande', 'Thailand', 'th.gif', 4, 1, 'TH', 'THA', 764, 'THB', 215, 0),
(61, 3, 'en', '', '', 'Turquie', 'Turkey', 'tr.gif', 4, 1, 'TR', 'TUR', 792, 'TRL', 223, 0),
(62, 4, 'en', '', '', 'Ukraine', 'Ukraine', 'ua.gif', 4, 1, 'UA', 'UKR', 804, 'UAH', 225, 0),
(63, 2, 'en', '', '', 'Venezuela', 'Venezuela', 've.gif', 4, 1, 'VE', 'VEN', 862, 'VEB', 229, 0),
(64, 4, 'en', '', '', 'Serbie', 'Serbia', 'yu.gif', 4, 1, 'RS', 'SRB', 688, 'CSD', 234, 0),
(65, 5, 'en', '', '', 'Samoa', 'Samoa', 'ws.gif', 4, 1, 'WS', 'WSM', 882, 'WST', 192, 0),
(66, 4, 'en', '', '', 'Andorre', 'Andorra', 'ad.gif', 4, 1, 'AD', 'AND', 20, 'EUR', 6, 0),
(67, 1, 'en', '', '', 'Angola', 'Angola', 'ao.gif', 4, 1, 'AO', 'AGO', 24, 'AON', 7, 0),
(68, 2, 'en', '', '', 'Anguilla', 'Anguilla', 'ai.gif', 4, 1, 'AI', 'AIA', 660, 'XCD', 8, 0),
(69, 6, 'en', '', '', 'Antarctique', 'Antarctica', 'aq.gif', 4, 1, 'AQ', 'ATA', 10, 'USD', 9, 0),
(70, 2, 'en', '', '', 'Antigua et Barbuda', 'Antigua and Barbuda', 'ag.gif', 4, 1, 'AG', 'ATG', 28, 'XCD', 10, 0),
(71, 3, 'en', '', '', 'Arménie', 'Armenia', 'am.gif', 4, 1, 'AM', 'ARM', 51, 'AMD', 14, 0),
(72, 2, 'en', '', '', 'Aruba', 'Aruba', 'aw.gif', 4, 1, 'AW', 'ABW', 533, 'AWG', 15, 0),
(73, 3, 'en', '', '', 'Azerbaidjan', 'Azerbaijan', 'az.gif', 4, 1, 'AZ', 'AZE', 31, 'AZM', 22, 0),
(74, 2, 'en', '', '', 'Bahamas', 'Bahamas, The', 'bs.gif', 4, 1, 'BS', 'BHS', 44, 'BSD', 23, 0),
(75, 3, 'en', '', '', 'Bahrein', 'Bahrain', 'bh.gif', 4, 1, 'BH', 'BHR', 48, 'BHD', 24, 0),
(76, 3, 'en', '', '', 'Bangladesh', 'Bangladesh', 'bd.gif', 4, 1, 'BD', 'BGD', 50, 'BDT', 25, 0),
(77, 4, 'en', '', '', 'Biélorussie', 'Belarus', 'by.gif', 4, 1, 'BY', 'BLR', 112, 'BYR', 32, 0),
(78, 2, 'en', '', '', 'Belize', 'Belize', 'bz.gif', 4, 1, 'BZ', 'BLZ', 84, 'BZD', 28, 0),
(79, 1, 'fr', '', '', 'Bénin', 'Benin', 'bj.gif', 4, 1, 'BJ', 'BEN', 204, 'XOF', 29, 0),
(80, 2, 'en', '', '', 'Bermudes (Les)', 'Bermuda', 'bm.gif', 4, 1, 'BM', 'BMU', 60, 'BMD', 30, 0),
(81, 3, 'en', '', '', 'Bhoutan', 'Bhutan', 'bt.gif', 4, 1, 'BT', 'BTN', 64, 'BTN', 31, 0),
(82, 2, 'en', '', '', 'Bolivie', 'Bolivia', 'bo.gif', 4, 1, 'BO', 'BOL', 68, 'BOB', 33, 0),
(83, 4, 'en', '', '', 'Bosnie-Herzégovine', 'Bosnia and Herzegovina', 'ba.gif', 4, 1, 'BA', 'BIH', 70, 'BAK', 34, 0),
(84, 1, 'en', '', '', 'Botswana', 'Botswana', 'bw.gif', 4, 1, 'BW', 'BWA', 72, 'BWP', 35, 0),
(85, 4, 'en', '', '', 'Norvège - Bouvet (Ile)', 'Norway - Bouvet Island', 'bv.gif', 4, 1, 'BV', 'BVT', 74, 'NOK', 161, 0),
(86, 3, 'en', '', '', 'Terr. Brit. de l''Océan Indien - Diego Garcia', 'British Indian Ocean Territory - Diego Garcia', 'io.gif', 4, 1, 'IO', 'IOT', 86, 'GBP', 214, 0),
(87, 5, 'en', '', '', 'Vierges britanniques (Iles)', 'Virgin Islands, British (Tortola)', 'vg.gif', 4, 1, 'VG', 'VGB', 92, 'USD', 231, 0),
(88, 5, 'en', '', '', 'Brunei', 'Brunei', 'bn.gif', 4, 1, 'BN', 'BRN', 96, 'BND', 37, 0),
(89, 1, 'fr', '', '', 'Burkina Faso', 'Burkina Faso', 'bf.gif', 4, 1, 'BF', 'BFA', 854, 'XOF', 39, 0),
(90, 1, 'en', '', '', 'Burundi', 'Burundi', 'bi.gif', 4, 1, 'BI', 'BDI', 108, 'BIF', 40, 0),
(91, 3, 'en', '', '', 'Cambodge', 'Cambodia', 'kh.gif', 4, 1, 'KH', 'KHM', 116, 'KHR', 41, 0),
(92, 1, 'fr', '', '', 'Cameroun', 'Cameroon', 'cm.gif', 4, 1, 'CM', 'CMR', 120, 'XAF', 42, 0),
(93, 1, 'en', '', '', 'Cap Vert', 'Cape Verde', 'cv.gif', 4, 1, 'CV', 'CPV', 132, 'CVE', 44, 0),
(94, 2, 'en', '', '', 'Cayman (Iles)', 'Cayman (Islands)', 'ky.gif', 4, 1, 'KY', 'CYM', 136, 'KYD', 45, 0),
(95, 1, 'fr', '', '', 'Centrafricaine (Rep.)', 'Central African Republic', 'cf.gif', 4, 1, 'CF', 'CAF', 140, 'XAF', 46, 0),
(96, 1, 'fr', '', '', 'Tchad', 'Chad', 'td.gif', 4, 1, 'TD', 'TCD', 148, 'XAF', 212, 0),
(97, 5, 'en', '', '', 'Australie - Christmas (Ile)', 'Australia - Christmas Island', 'cx.gif', 4, 1, 'CX', 'CXR', 162, 'AUD', 17, 0),
(98, 5, 'en', '', '', 'Australie - Cocos (Keeling) (Iles)', 'Australia - Cocos (Keeling) Islands', 'cc.gif', 4, 1, 'CC', 'CCK', 166, 'AUD', 18, 0),
(99, 1, 'fr', '', '', 'Comores', 'Comoros', 'km.gif', 4, 1, 'KM', 'COM', 174, 'KMF', 51, 0),
(100, 1, 'fr', '', '', 'Congo', 'Congo', 'cg.gif', 4, 1, 'CG', 'COG', 178, 'XAF', 52, 0),
(101, 5, 'en', '', '', 'Cook (Iles)', 'Cook Islands', 'ck.gif', 4, 1, 'CK', 'COK', 184, 'NZD', 54, 0),
(102, 2, 'en', '', '', 'Cuba', 'Cuba', 'cu.gif', 4, 1, 'CU', 'CUB', 192, 'CUP', 60, 0),
(103, 4, 'en', '', '', 'Chypre', 'Cyprus', 'cy.gif', 3, 1, 'CY', 'CYP', 196, 'EUR', 49, 0),
(104, 1, 'fr', '', '', 'Djibouti', 'Djibouti', 'dj.gif', 4, 1, 'DJ', 'DJI', 262, 'DJF', 62, 0),
(105, 2, 'en', '', '', 'Dominique', 'Dominica', 'dm.gif', 4, 1, 'DM', 'DMA', 212, 'XCD', 64, 0),
(106, 2, 'en', '', '', 'Dominicaine (Rep.)', 'Dominican (Republic)', 'do.gif', 4, 1, 'DO', 'DOM', 214, 'DOP', 63, 0),
(107, 3, 'en', '', '', 'Timor oriental', 'East Timor - Leste', 'tp.gif', 4, 1, 'TL', 'TLS', 626, 'USD', 216, 0),
(108, 1, 'en', '', '', 'Guinée Equatoriale', 'Equatorial Guinea', 'gq.gif', 4, 1, 'GQ', 'GNQ', 226, 'XAF', 101, 0),
(109, 1, 'en', '', '', 'Erythr', 'Eritrea', 'er.gif', 4, 1, 'ER', 'ERI', 232, 'ERN', 69, 0),
(110, 4, 'en', '', '', 'Estonie', 'Estonia', 'ee.gif', 3, 1, 'EE', 'EST', 233, 'EEK', 71, 0),
(111, 1, 'en', '', '', 'Ethiopie', 'Ethiopia', 'et.gif', 4, 1, 'ET', 'ETH', 231, 'ETB', 74, 0),
(112, 2, 'en', '', '', 'Falkland (Iles Malouines)', 'Falkland Islands (Malvinas)', 'fk.gif', 4, 1, 'FK', 'FLK', 238, 'FKP', 75, 0),
(113, 4, 'en', '', '', 'Féroé (Iles)', 'Faroe Islands', 'fo.gif', 4, 1, 'FO', 'FRO', 234, 'DKK', 76, 0),
(114, 5, 'en', '', '', 'Fidji (République des)', 'Fiji', 'fj.gif', 4, 1, 'FJ', 'FJI', 242, 'FJD', 77, 0),
(115, 2, 'fr', '', '', 'France - Guyane', 'France - Guiana', 'gf.gif', 2, 1, 'GF', 'GUF', 254, 'EUR', 81, 0),
(116, 5, 'fr', '', '', 'France - Polynésie', 'France - Polynesia', 'pf.gif', 2, 1, 'PF', 'PYF', 258, 'XPF', 84, 0),
(117, 6, 'fr', '', '', 'France - Terres australes', 'France - Southern Territories', 'tf.gif', 2, 1, 'TF', 'ATF', 260, 'EUR', 87, 0),
(118, 1, 'fr', '', '', 'Gabon', 'Gabon', 'ga.gif', 4, 1, 'GA', 'GAB', 266, 'XAF', 89, 0),
(119, 1, 'en', '', '', 'Gambie', 'Gambia', 'gm.gif', 4, 1, 'GM', 'GMB', 270, 'GMD', 90, 0),
(120, 3, 'en', '', '', 'Géorgie', 'Georgia', 'ge.gif', 4, 1, 'GE', 'GEO', 268, 'GEL', 91, 0),
(121, 1, 'en', '', '', 'Ghana', 'Ghana', 'gh.gif', 4, 1, 'GH', 'GHA', 288, 'GHC', 93, 0),
(122, 4, 'en', '', '', 'Gibraltar', 'Gibraltar', 'gi.gif', 4, 1, 'GI', 'GIB', 292, 'GIP', 94, 0),
(123, 4, 'en', '', '', 'Groenland', 'Greenland', 'gl.gif', 4, 1, 'GL', 'GRL', 304, 'DKK', 97, 0),
(124, 2, 'en', '', '', 'Grenade', 'Grenada', 'gd.gif', 4, 1, 'GD', 'GRD', 308, 'XCD', 96, 0),
(125, 2, 'fr', '', '', 'France - Guadeloupe', 'France - Guadeloupe', 'gp.gif', 2, 1, 'GP', 'GLP', 312, 'EUR', 80, 0),
(126, 2, 'en', '', '', 'Guam', 'Guam', 'gu.gif', 4, 1, 'GU', 'GUM', 316, 'USD', 98, 0),
(127, 2, 'en', '', '', 'Guatemala', 'Guatemala', 'gt.gif', 4, 1, 'GT', 'GTM', 320, 'GTQ', 99, 0),
(128, 1, 'fr', '', '', 'Guin', 'Guinea', 'gn.gif', 4, 1, 'GN', 'GIN', 324, 'USD', 100, 0),
(129, 1, 'en', '', '', 'Guinée-Bissau', 'Guinea-Bissau', 'gw.gif', 4, 1, 'GW', 'GNB', 624, 'XOF', 102, 0),
(131, 2, 'fr', '', '', 'Haiti', 'Haiti', 'ht.gif', 4, 1, 'HT', 'HTI', 332, 'HTG', 103, 0),
(132, 5, 'en', '', '', 'Australie - Heard et McDonald (Iles)', 'Australia - Heard and McDonald (Islands)', 'hm.gif', 4, 1, 'HM', 'HMD', 334, 'AUD', 19, 0),
(133, 2, 'en', '', '', 'Honduras', 'Honduras', 'hn.gif', 4, 1, 'HN', 'HND', 340, 'HNL', 104, 0),
(134, 4, 'en', '', '', 'Islande', 'Iceland', 'is.gif', 4, 1, 'IS', 'ISL', 352, 'ISK', 112, 0),
(135, 3, 'en', '', '', 'Iran', 'Iran (Islamic Rep. of)', 'ir.gif', 4, 1, 'IR', 'IRN', 364, 'IRR', 110, 0),
(136, 3, 'en', '', '', 'Irak', 'Iraq', 'iq.gif', 4, 1, 'IQ', 'IRQ', 368, 'IQD', 109, 0),
(137, 1, 'fr', '', '', 'Cote d''Ivoire', 'Ivory Coast', 'ci.gif', 4, 1, 'CI', 'CIV', 384, 'XOF', 58, 0),
(138, 2, 'en', '', '', 'Jamaique', 'Jamaica', 'jm.gif', 4, 1, 'JM', 'JAM', 388, 'JMD', 115, 0),
(139, 3, 'en', '', '', 'Kazakhstan', 'Kazakhstan', 'kz.gif', 4, 1, 'KZ', 'KAZ', 398, 'KZT', 118, 0),
(140, 1, 'en', '', '', 'Kenya', 'Kenya', 'ke.gif', 4, 1, 'KE', 'KEN', 404, 'KES', 119, 0),
(141, 5, 'en', '', '', 'Kiribati', 'Kiribati', 'ki.gif', 4, 1, 'KI', 'KIR', 296, 'AUD', 121, 0),
(142, 3, 'en', '', '', 'Corée (Rep. de) (Sud)', 'Korea (Republic of) (South)', 'kr.gif', 4, 1, 'KR', 'KOR', 410, 'KRW', 55, 0),
(143, 3, 'en', '', '', 'Koweit', 'Kuwait', 'kw.gif', 4, 1, 'KW', 'KWT', 414, 'KWD', 122, 0),
(144, 3, 'en', '', '', 'Kirghizistan', 'Kyrgyzstan', 'kg.gif', 4, 1, 'KG', 'KGZ', 417, 'KGS', 120, 0),
(145, 3, 'en', '', '', 'Laos', 'Laos (People''s Democratic Rep. of)', 'la.gif', 4, 1, 'LA', 'LAO', 418, 'LAK', 123, 0),
(146, 4, 'en', '', '', 'Lettonie', 'Latvia', 'lv.gif', 3, 1, 'LV', 'LVA', 428, 'LVL', 125, 0),
(147, 1, 'en', '', '', 'Lesotho', 'Lesotho', 'ls.gif', 4, 1, 'LS', 'LSO', 426, 'LSL', 124, 0),
(148, 1, 'en', '', '', 'Libéria', 'Liberia', 'lr.gif', 4, 1, 'LR', 'LBR', 430, 'LRD', 127, 0),
(149, 1, 'en', '', '', 'Libye', 'Libya (Libyan Arab Jamahiriya)', 'ly.gif', 4, 1, 'LY', 'LBY', 434, 'LYD', 128, 0),
(150, 4, 'en', '', '', 'Liechtenstein', 'Liechtenstein', 'li.gif', 4, 1, 'LI', 'LIE', 438, 'CHF', 129, 0),
(151, 4, 'en', '', '', 'Lituanie', 'Lithuania', 'lt.gif', 3, 1, 'LT', 'LTU', 440, 'LTL', 130, 0),
(152, 4, 'en', '', '', 'Luxembourg', 'Luxembourg', 'lu.gif', 3, 1, 'LU', 'LUX', 442, 'EUR', 131, 0),
(153, 3, 'en', '', '', 'Macao', 'Macau', 'mo.gif', 4, 1, 'MO', 'MAC', 446, 'MOP', 132, 0),
(154, 4, 'en', '', '', 'Macédoine', 'Macedonia (F.Y.R.O.M.)', 'mk.gif', 4, 1, 'MK', 'MKD', 807, 'EUR', 133, 0),
(155, 1, 'fr', '', '', 'Madagascar', 'Madagascar', 'mg.gif', 4, 1, 'MG', 'MDG', 450, 'MGF', 134, 0),
(156, 1, 'en', '', '', 'Malawi', 'Malawi', 'mw.gif', 4, 1, 'MW', 'MWI', 454, 'MWK', 136, 0),
(157, 3, 'en', '', '', 'Maldives (Iles)', 'Maldives (Islands)', 'mv.gif', 4, 1, 'MV', 'MDV', 462, 'MVR', 137, 0),
(158, 1, 'fr', '', '', 'Mali', 'Mali', 'ml.gif', 4, 1, 'ML', 'MLI', 466, 'XOF', 138, 0),
(159, 4, 'en', '', '', 'Malte', 'Malta', 'mt.gif', 3, 1, 'MT', 'MLT', 470, 'EUR', 139, 0),
(160, 5, 'en', '', '', 'Marshall (Iles)', 'Marshall Islands', 'mh.gif', 4, 1, 'MH', 'MHL', 584, 'USD', 142, 0),
(161, 2, 'fr', '', '', 'France - Martinique', 'France - Martinique', 'mq.gif', 2, 1, 'MQ', 'MTQ', 474, 'EUR', 82, 0),
(162, 1, 'fr', '', '', 'Mauritanie', 'Mauritania', 'mr.gif', 4, 1, 'MR', 'MRT', 478, 'MRO', 144, 0),
(163, 1, 'en', '', '', 'Maurice', 'Mauritius (Island)', 'mu.gif', 4, 1, 'MU', 'MUS', 480, 'MUR', 143, 0),
(164, 1, 'fr', '', '', 'France - Mayotte', 'France - Mayotte', 'yt.gif', 2, 1, 'YT', 'MYT', 175, 'EUR', 83, 0),
(165, 5, 'en', '', '', 'Micronésie (Etats fédérés de)', 'Micronesia', 'fm.gif', 4, 1, 'FM', 'FSM', 583, 'USD', 146, 0),
(166, 4, 'en', '', '', 'Moldavie', 'Moldova', 'md.gif', 4, 1, 'MD', 'MDA', 498, 'MDL', 147, 0),
(167, 4, 'fr', '', '', 'Monaco', 'Monaco', 'mc.gif', 4, 1, 'MC', 'MCO', 492, 'EUR', 148, 0),
(168, 3, 'en', '', '', 'Mongolie', 'Mongolia', 'mn.gif', 4, 1, 'MN', 'MNG', 496, 'MNT', 149, 0),
(169, 2, 'en', '', '', 'Montserrat', 'Montserrat', 'ms.gif', 4, 1, 'MS', 'MSR', 500, 'XCD', 150, 0),
(170, 1, 'en', '', '', 'Mozambique', 'Mozambique', 'mz.gif', 4, 1, 'MZ', 'MOZ', 508, 'MZM', 151, 0),
(171, 3, 'en', '', '', 'Myanmar', 'Myanmar (Burma)', 'mm.gif', 4, 1, 'MM', 'MMR', 104, 'MMK', 152, 0),
(172, 1, 'en', '', '', 'Namibie', 'Namibia', 'na.gif', 4, 1, 'NA', 'NAM', 516, 'NAD', 153, 0),
(173, 5, 'en', '', '', 'Nauru', 'Nauru', 'nr.gif', 4, 1, 'NR', 'NRU', 520, 'AUD', 154, 0),
(174, 3, 'en', '', '', 'Nepal', 'Nepal', 'np.gif', 4, 1, 'NP', 'NPL', 524, 'NPR', 155, 0),
(176, 5, 'fr', '', '', 'France - Nouvelle Calédonie', 'France - New Caledonia', 'nc.gif', 2, 1, 'NC', 'NCL', 540, 'XPF', 163, 0),
(177, 2, 'en', '', '', 'Nicaragua', 'Nicaragua', 'ni.gif', 4, 1, 'NI', 'NIC', 558, 'NIO', 156, 0),
(178, 1, 'fr', '', '', 'Niger', 'Niger', 'ne.gif', 4, 1, 'NE', 'NER', 562, 'XOF', 157, 0),
(179, 1, 'en', '', '', 'Nigeria', 'Nigeria', 'ng.gif', 4, 1, 'NG', 'NGA', 566, 'NGN', 158, 0),
(180, 5, 'en', '', '', 'Niue', 'Niue', 'nu.gif', 4, 1, 'NU', 'NIU', 570, 'NZD', 159, 0),
(181, 5, 'en', '', '', 'Australie - Norfolk (Ile)', 'Australia - Norfolk Island', 'nf.gif', 4, 1, 'NF', 'NFK', 574, 'AUD', 20, 0),
(182, 5, 'en', '', '', 'Mariannes du Nord (Iles)', 'Northern Marianas (Islands)', 'mp.gif', 4, 1, 'MP', 'MNP', 580, 'USD', 140, 0),
(183, 3, 'en', '', '', 'Oman', 'Oman', 'om.gif', 4, 1, 'OM', 'OMN', 512, 'OMR', 166, 0),
(184, 5, 'en', '', '', 'Palau', 'Palau', 'pw.gif', 4, 1, 'PW', 'PLW', 585, 'USD', 170, 0),
(185, 2, 'en', '', '', 'Panama', 'Panama', 'pa.gif', 4, 1, 'PA', 'PAN', 591, 'PAB', 171, 0),
(186, 5, 'en', '', '', 'Papouasie-Nouvelle-Guinée', 'Papua New Guinea', 'pg.gif', 4, 1, 'PG', 'PNG', 598, 'PGK', 172, 0),
(187, 2, 'en', '', '', 'Paraguay', 'Paraguay', 'py.gif', 4, 1, 'PY', 'PRY', 600, 'PYG', 173, 0),
(188, 5, 'en', '', '', 'Pitcairn (Iles)', 'Pitcairn Islands', 'pn.gif', 4, 1, 'PN', 'PCN', 612, 'NZD', 177, 0),
(189, 3, 'en', '', '', 'Qatar', 'Qatar', 'qa.gif', 4, 1, 'QA', 'QAT', 634, 'QAR', 181, 0),
(190, 1, 'fr', '', '', 'France - Réunion', 'France - Réunion', 're.gif', 2, 1, 'RE', 'REU', 638, 'EUR', 85, 0),
(191, 1, 'en', '', '', 'Rwanda', 'Rwanda', 'rw.gif', 4, 1, 'RW', 'RWA', 646, 'RWF', 185, 0),
(192, 2, 'en', '', '', 'Géorgie du Sud et Sandwich du Sud (Iles)', 'South Georgia and the South Sandwich Islands', 'gs.gif', 4, 1, 'GS', 'SGS', 239, 'USD', 92, 0),
(193, 2, 'en', '', '', 'Saint-Kitts et Nevis', 'Saint Kitts and Nevis', 'kn.gif', 4, 1, 'KN', 'KNA', 659, 'XCD', 187, 0),
(194, 2, 'en', '', '', 'Sainte Lucie', 'Saint Lucia', 'lc.gif', 4, 1, 'LC', 'LCA', 662, 'XCD', 191, 0),
(195, 2, 'en', '', '', 'Saint-Vincent-et-Grenadines', 'Saint Vincent and the Grenadines', 'vc.gif', 4, 1, 'VC', 'VCT', 670, 'XCD', 189, 0),
(196, 5, 'en', '', '', 'Samoa', 'Samoa', 'ws.gif', 4, 1, 'WS', 'WSM', 882, 'WST', 193, 0),
(197, 4, 'en', '', '', 'Saint-Marin (Rép. de)', 'San Marino', 'sm.gif', 4, 1, 'SM', 'SMR', 674, 'EUR', 188, 0),
(198, 1, 'en', '', '', 'Sao Tome et Principe (Rép.)', 'Sao Tome and Principe', 'st.gif', 4, 1, 'ST', 'STP', 678, 'STD', 194, 0),
(199, 1, 'fr', '', '', 'Sénégal', 'Senegal', 'sn.gif', 4, 1, 'SN', 'SEN', 686, 'XOF', 195, 0),
(200, 1, 'en', '', '', 'Seychelles', 'Seychelles (Islands)', 'sc.gif', 4, 1, 'SC', 'SYC', 690, 'SCR', 196, 0),
(201, 1, 'en', '', '', 'Sierra Leone', 'Sierra Leone', 'sl.gif', 4, 1, 'SL', 'SLE', 694, 'SLL', 197, 0),
(202, 4, 'en', '', '', 'Slovaquie', 'Slovakia (Republic)', 'sk.gif', 3, 1, 'SK', 'SVK', 703, 'SKK', 199, 0),
(203, 4, 'en', '', '', 'Slovénie', 'Slovenia', 'si.gif', 3, 1, 'SI', 'SVN', 705, 'EUR', 200, 0),
(204, 1, 'en', '', '', 'Somalie', 'Somalia', 'so.gif', 4, 1, 'SO', 'SOM', 706, 'SOS', 201, 0),
(205, 3, 'en', '', '', 'Sri Lanka', 'Sri Lanka', 'lk.gif', 4, 1, 'LK', 'LKA', 144, 'LKR', 203, 0),
(206, 1, 'en', '', '', 'Sainte Hélène', 'Saint Helena', 'sh.gif', 4, 1, 'SH', 'SHN', 654, 'SHP', 190, 0),
(207, 2, 'fr', '', '', 'France - Saint-Pierre-et-Miquelon', 'France - Saint-Pierre and Miquelon', 'pm.gif', 2, 1, 'PM', 'SPM', 666, 'EUR', 86, 0),
(208, 1, 'en', '', '', 'Soudan', 'Sudan', 'sd.gif', 4, 1, 'SD', 'SDN', 736, 'SDD', 202, 0),
(209, 2, 'en', '', '', 'Suriname', 'Suriname', 'sr.gif', 4, 1, 'SR', 'SUR', 740, 'SRG', 206, 0),
(210, 4, 'en', '', '', 'Norvège - Svalbard et Jan Mayen (Iles)', 'Norway - Svalbard and Jan Mayen', 'sj.gif', 4, 1, 'SJ', 'SJM', 744, 'NOK', 162, 0),
(211, 1, 'en', '', '', 'Swaziland', 'Swaziland', 'sz.gif', 4, 1, 'SZ', 'SWZ', 748, 'SZL', 207, 0),
(212, 3, 'en', '', '', 'Syrie', 'Syria', 'sy.gif', 4, 1, 'SY', 'SYR', 760, 'SYP', 208, 0),
(213, 3, 'en', '', '', 'Tadjikistan', 'Tajikistan (Republic of)', 'tj.gif', 4, 1, 'TJ', 'TJK', 762, 'TJS', 209, 0),
(214, 1, 'en', '', '', 'Tanzanie', 'Tanzania', 'tz.gif', 4, 1, 'TZ', 'TZA', 834, 'TZS', 211, 0),
(215, 1, 'fr', '', '', 'Togo', 'Togo', 'tg.gif', 4, 1, 'TG', 'TGO', 768, 'XOF', 217, 0),
(216, 5, 'en', '', '', 'Nouvelle-Zélande - Tokelau', 'New Zealand - Tokelau', 'tk.gif', 4, 1, 'TK', 'TKL', 772, 'NZD', 165, 0),
(217, 5, 'en', '', '', 'Tonga', 'Tonga', 'to.gif', 4, 1, 'TO', 'TON', 776, 'TOP', 218, 0),
(218, 2, 'en', '', '', 'Trinité et Tobago', 'Trinidad and Tobago', 'tt.gif', 4, 1, 'TT', 'TTO', 780, 'TTD', 219, 0),
(219, 1, 'fr', '', '', 'Tunisie', 'Tunisia', 'tn.gif', 4, 1, 'TN', 'TUN', 788, 'TND', 220, 0),
(220, 3, 'en', '', '', 'Turkménistan', 'Turkmenistan', 'tm.gif', 4, 1, 'TM', 'TKM', 795, 'TMM', 221, 0),
(221, 2, 'en', '', '', 'Turks et Caïques (Iles)', 'Turks and Caicos Islands', 'tc.gif', 4, 1, 'TC', 'TCA', 796, 'USD', 222, 0),
(222, 5, 'en', '', '', 'Tuvalu', 'Tuvalu', 'tv.gif', 4, 1, 'TV', 'TUV', 798, 'AUD', 224, 0),
(223, 5, 'en', '', '', 'Etats-Unis : Iles mineures éloignées', 'USA: Minor Outlying Islands', 'um.gif', 4, 1, 'UM', 'UMI', 581, 'USD', 73, 0),
(224, 1, 'en', '', '', 'Ouganda', 'Uganda', 'ug.gif', 4, 1, 'UG', 'UGA', 800, 'UGX', 167, 0),
(225, 2, 'en', '', '', 'Uruguay', 'Uruguay', 'uy.gif', 4, 1, 'UY', 'URY', 858, 'UYU', 226, 0),
(226, 3, 'en', '', '', 'Ouzbékistan', 'Uzbekistan', 'uz.gif', 4, 1, 'UZ', 'UZB', 860, 'UZS', 168, 0),
(227, 5, 'en', '', '', 'Vanuatu', 'Vanuatu', 'vu.gif', 4, 1, 'VU', 'VUT', 548, 'VUV', 227, 0),
(228, 4, 'en', '', '', 'Vatican (Etat du)', 'Vatican', 'va.gif', 4, 1, 'VA', 'VAT', 336, 'EUR', 228, 0),
(229, 3, 'en', '', '', 'Vietnam', 'Vietnam', 'vn.gif', 4, 1, 'VN', 'VNM', 704, 'VND', 232, 0),
(230, 5, 'en', '', '', 'Vierges Américaines (Iles)', 'Virgin Islands (USA)', 'vi.gif', 4, 1, 'VI', 'VIR', 850, 'USD', 230, 0),
(231, 5, 'fr', '', '', 'France - Wallis et Futuna', 'France - Wallis and Futuna', 'wf.gif', 2, 1, 'WF', 'WLF', 876, 'XPF', 88, 0),
(232, 1, 'en', '', '', 'Sahara Occidental', 'Western Sahara', 'eh.gif', 4, 1, 'EH', 'ESH', 732, 'MAD', 186, 0),
(233, 3, 'en', '', '', 'Yemen', 'Yemen (Rep. of)', 'ye.gif', 4, 1, 'YE', 'YEM', 887, 'YER', 233, 0),
(234, 1, 'fr', '', '', 'Congo Zaïre (Rep. Dem.)', 'Congo Zaire (Dem. Rep.)', 'cd.gif', 4, 1, 'CD', 'COD', 180, 'XAF', 53, 0),
(235, 1, 'en', '', '', 'Zambie', 'Zambia', 'zm.gif', 4, 1, 'ZM', 'ZMB', 894, 'ZMK', 235, 0),
(236, 1, 'en', '', '', 'Zimbabwe', 'Zimbabwe', 'zw.gif', 4, 1, 'ZW', 'ZWE', 716, 'ZWD', 236, 0),
(237, 2, 'en', '', '', 'Barbade', 'Barbados', 'bb.gif', 4, 1, 'BB', 'BRB', 52, 'BBD', 26, 0),
(238, 4, 'en', '', '', 'Monténégro', 'Montenegro', 'yu.gif', 4, 1, 'ME', 'MNE', 499, 'CSD', 234, 0);

/*
Ajout de continent_id sur une table existante :
- créer la colonne
- copier via phpMyAdmin ou créer la table contenant cette info dans une table peel_pays_temp
OU
simplement noter le lieu où on peut la trouver : par exemple : peel_mysql.peel_pays
- faire un update avec jointure pour récupérer les infos :
UPDATE mabdd.peel_pays, peel_mysql.peel_pays SET mabdd.peel_pays.continent_id=peel_mysql.peel_pays.continent_id WHERE mabdd.peel_pays.id=peel_mysql.peel_pays.id
- c'est fini => si on a utilisé une bdd temp, alors la supprimer

OU :

comme les id des pays n'ont pas changé, vu qu'on rajoute continent_id, c'est que l'utilisateur veut peut-être diverses MAJ et auquel cas ça peut justifier de remplacer complètement la table => faire le remplacement de la talbe par la nouvelle - ATTENTION aux zone_id
*/


--
-- Structure de la table `peel_access_map`
--

CREATE TABLE `peel_access_map` (
`id` INT NOT NULL AUTO_INCREMENT,
`text_fr` TEXT NOT NULL,
`text_en` TEXT NOT NULL,
`map_tag` TEXT NOT NULL,
`date_insere` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`date_maj` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Contenu de la table `peel_access_map`
--

INSERT INTO `peel_access_map` (`id`,`text_fr`,`text_en`,`map_tag`,`date_insere`,`date_maj`) VALUES
(1,\"Plan d'accès google maps\",\"Plan of access google maps\",\"Insérez votre tag google map\",'','');";


$sql_update_array['6.3'] = "";
if(file_exists($GLOBALS['dirroot'] . '/modules/cart_preservation')) {
	$sql_update_array['6.3'] .= "
	CREATE TABLE IF NOT EXISTS `peel_save_cart` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`sc_timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`id_utilisateur` int(11) NOT NULL DEFAULT '0',
	`produit_id` int(11) NOT NULL DEFAULT '0',
	`nom_produit` varchar(255) NOT NULL DEFAULT '',
	`quantite` int(11) NOT NULL DEFAULT '0',
	`couleur` varchar(150) NOT NULL DEFAULT '',
	`taille` varchar(150) NOT NULL DEFAULT '',
	`couleur_id` int(11) NOT NULL DEFAULT '0',
	`taille_id` int(11) NOT NULL DEFAULT '0',
	`nom_attribut` varchar(255) NOT NULL DEFAULT '',
	`id_attribut` varchar(100) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `id_utilisateur` (`id_utilisateur`),
	KEY `produit_id` (`produit_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
}
$sql_update_array['6.3'] .= "
-- FAIT après la version 6.3 :
ALTER TABLE `peel_produits` ADD `technical_code` varchar(255) CHARACTER SET utf8 NOT NULL;
ALTER TABLE `peel_categories` ADD `technical_code` varchar(255) CHARACTER SET utf8 NOT NULL;
";

if(file_exists($GLOBALS['dirroot'] . '/modules/attributs')) {
	$sql_update_array['6.3'] .= "
ALTER TABLE `peel_nom_attributs` ADD `technical_code` varchar(255) CHARACTER SET utf8 NOT NULL;
ALTER TABLE `peel_attributs` ADD `technical_code` varchar(255) CHARACTER SET utf8 NOT NULL;";
}

$sql_update_array['6.3.1'] = "
-- FAIT après la version 6.3.1 :
ALTER TABLE `peel_rubriques` ADD `technical_code` varchar(255) CHARACTER SET utf8 NOT NULL;
ALTER TABLE `peel_admins_actions` CHANGE `remarque` `remarque` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `peel_admins_comments` CHANGE `id_user` `id_user` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',CHANGE `admin_id` `admin_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',CHANGE `timestamp` `timestamp` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `peel_admins_contacts_planified` CHANGE `user_id` `user_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',CHANGE `admin_id` `admin_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',CHANGE `timestamp` `timestamp` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',CHANGE `reason` `reason` ENUM( '', 'interesting_profile', 'interested_by_product', 'payment_expected', 'follow_up' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `peel_butterflive` CHANGE `value` `value` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
";
if(file_exists($GLOBALS['dirroot'] . '/modules/attributs')) {
	$sql_update_array['6.3.1'] .= "
ALTER TABLE `peel_attributs` ADD `mandatory` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `peel_nom_attributs` ADD `mandatory` TINYINT( 1 ) NOT NULL DEFAULT '0';";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/avis')) {
	$sql_update_array['6.3.1'] .= "
ALTER TABLE `peel_avis` CHANGE `pseudo` `pseudo` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
";
}
$sql_update_array['6.3.1'] .= "
ALTER TABLE `peel_meta` ADD `technical_code` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `peel_commandes` CHANGE `paiement` `paiement` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',CHANGE `transport` `transport` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `peel_commandes` ADD `typeId` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `peel_continents` CHANGE `id` `id` TINYINT( 1 ) UNSIGNED NOT NULL ;
ALTER TABLE `peel_couleurs` ADD `mandatory` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `peel_devises` CHANGE `code` `code` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `peel_html` CHANGE `lang` `lang` VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '';
ALTER TABLE `peel_newsletter` ADD `template_technical_code` VARCHAR( 255 ) NOT NULL DEFAULT '';
ALTER TABLE `peel_paiement` ADD `tarif_percent` float(5,2) NOT NULL DEFAULT '0.00000';

ALTER TABLE `peel_profil` CHANGE `document` `document` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `peel_societe` ADD `adresse2` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `peel_societe` ADD `code_postal2` varchar(8) NOT NULL DEFAULT '';
ALTER TABLE `peel_societe` ADD `ville2` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `peel_societe` ADD `tel2` varchar(32) NOT NULL DEFAULT '';
ALTER TABLE `peel_societe` ADD `fax2` varchar(32) NOT NULL DEFAULT '';
ALTER TABLE `peel_societe` ADD `pays2` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `peel_sites` CHANGE `template_multipage` `template_multipage` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default_1';
ALTER TABLE `peel_sites` CHANGE `small_width` `small_width` SMALLINT( 4 ) NOT NULL DEFAULT '160',CHANGE `small_height` `small_height` SMALLINT( 4 ) NOT NULL DEFAULT '160',CHANGE `medium_width` `medium_width` SMALLINT( 4 ) NOT NULL DEFAULT '300',CHANGE `medium_height` `medium_height` SMALLINT( 4 ) NOT NULL DEFAULT '300';
ALTER TABLE `peel_sites` CHANGE `facebook_connect` `facebook_connect` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '', CHANGE `fb_appid` `fb_appid` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',CHANGE `fb_secret` `fb_secret` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',CHANGE `fb_baseurl` `fb_baseurl` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `peel_sites` ADD `module_conditionnement` TINYINT( 1 ) NOT NULL DEFAULT '0', ADD `payment_status_decrement_stock` varchar(8) NOT NULL DEFAULT '3', ADD `keep_old_orders_intact` INT( 11 ) NOT NULL DEFAULT '0', ADD `default_picture` VARCHAR( 255 ) NOT NULL DEFAULT '';
ALTER TABLE `peel_sites` DROP INDEX `nom_fr`;
ALTER TABLE `peel_sites` ADD INDEX `nom_fr` ( `nom_fr` ) ;
ALTER TABLE `peel_sites` DROP INDEX `nom_en`;
ALTER TABLE `peel_sites` ADD INDEX `nom_en` ( `nom_en` );
ALTER TABLE `peel_utilisateurs` ADD `fonction` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `peel_utilisateurs` ADD `activity` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `peel_utilisateurs` ADD `on_client_module` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_utilisateurs` ADD `on_photodesk` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_utilisateurs` ADD `email_bounce` varchar(60) NOT NULL DEFAULT '' AFTER email;
ALTER TABLE `peel_tailles` ADD `mandatory` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_zones` ADD `technical_code` varchar(255) NOT NULL DEFAULT '';

-- Mise à jour des commandes suite à des problèmes de recalcul des commandes sur la 6.3 ( http://advisto75.dyndns.org:8080/mantis/view.php?id=7034 )
SELECT commande_id, ca.id,round( sum(ca.prix*ca.quantite),2) AS somme_produits_ttc , montant AS montant_total_facture_ttc, round( sum(ca.prix*ca.quantite),2)-round(montant,2) AS difference_ttc
FROM `peel_commandes_articles` ca inner join peel_commandes c ON ca.commande_id=c.id
WHERE id_statut_paiement=3
GROUP BY c.id
HAVING round( sum(ca.prix*ca.quantite),2)!=round(montant,2)
ORDER BY `ca`.`commande_id` ASC LIMIT 0, 30 ;

UPDATE `peel_commandes_articles`
SET remise=quantite*remise, remise_ht=quantite*remise_ht
WHERE ABS((prix_cat-prix)*quantite-remise)>1 AND ABS((prix_cat-prix)*quantite-remise*quantite)<1
";
$sql_update_array['6.4'] = "
-- FAIT après la version 6.4 :
ALTER TABLE `peel_banniere` ADD `on_first_page_category` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_banniere` ADD `on_other_page_category` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_banniere` ADD `on_home_page` TINYINT( 1 ) NOT NULL DEFAULT '0',
ADD `on_other_page` TINYINT( 1 ) NOT NULL DEFAULT '0', ADD `keywords` mediumtext NOT NULL;
ALTER TABLE`peel_utilisateurs`  ADD INDEX `pseudo` ( `pseudo` ) ;
";
$sql_update_array['6.4.1'] = "
-- FAIT après la version 6.4.1 :
TRUNCATE TABLE `peel_statut_livraison`;
ALTER TABLE `peel_statut_livraison` CHANGE `id` `id` INT( 11 ) NOT NULL ;
INSERT INTO `peel_statut_livraison` (`id`, `nom_fr`, `nom_en`, `position`) VALUES
(0, 'En cours de discussion', 'Being discussed', 0),
(1, 'En cours de traitement', 'Processing', 1),
(3, 'Expédiée', 'Order dispatched', 5),
(6, 'Annulée', 'Order cancelled', 6),
(9, 'Approvisionnement', 'Approvisionnement', 7);

TRUNCATE TABLE `peel_statut_paiement`;
ALTER TABLE `peel_statut_paiement` CHANGE `id` `id` INT( 11 ) NOT NULL ;
INSERT INTO `peel_statut_paiement` (`id`, `nom_fr`, `nom_en`, `position`) VALUES
(0, 'En cours de discussion', 'Being discussed', 0),
(1, 'En attente de paiement', 'Order registered', 1),
(2, 'Paiement en cours de vérification', 'Payment pending', 2),
(3, 'Réglée', 'Order completed', 3),
(6, 'Annulée', 'Order cancelled', 6),
(9, 'Remboursée', 'Order refunded', 7);

ALTER TABLE `peel_profil` ADD INDEX ( `priv` );
INSERT INTO `peel_paiement` (`technical_code`, `nom_fr`, `nom_en`, `position`, `tarif`, `tva`, `etat`) VALUES
('cash', 'Mandat cash', 'Cash / Western Union', 7, 0.00000, 0.00, 0);

ALTER TABLE `peel_banniere` CHANGE `etat` `etat` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_banniere` ADD `search_words_list` text NOT NULL;
ALTER TABLE `peel_banniere` ADD `annonce_number`  int(11) NOT NULL DEFAULT 0;
ALTER TABLE `peel_banniere` ADD `list_id` varchar(255) NOT NULL DEFAULT '';

ALTER TABLE `peel_commandes` CHANGE `zip_bill` `zip_bill` varchar(100) NOT NULL DEFAULT '';
ALTER TABLE `peel_commandes` CHANGE `zip_ship` `zip_ship` varchar(100) NOT NULL DEFAULT '';

ALTER TABLE `peel_sites` CHANGE `payment_status_decrement_stock` `payment_status_decrement_stock` varchar(32) NOT NULL DEFAULT '2;3';

ALTER TABLE `peel_societe` CHANGE `code_postal` `code_postal` varchar(100) NOT NULL DEFAULT '';
ALTER TABLE `peel_societe` CHANGE `code_postal2` `code_postal2` varchar(100) NOT NULL DEFAULT '';

ALTER TABLE `peel_utilisateurs` CHANGE `code_postal` `code_postal` varchar(100) NOT NULL DEFAULT '';

INSERT INTO `peel_profil` (`name`, `priv`, `description_document`) VALUES
('Administrateur Contenu', 'admin_content', ''),
('Administrateur Ventes', 'admin_sales', ''),
('Administrateur Produits', 'admin_products', ''),
('Administrateur Webmastering', 'admin_webmastering', ''),
('Administrateur Modération', 'admin_moderation', '');
";

$sql_update_array['6.4.2'] = '';
if($current_version<='6.4.2') {
	$query = query("SELECT email_webmaster, email_client, nom_expediteur, email_paypal
		FROM peel_sites");
	if($result = fetch_assoc($query)) {
		$sql_update_array['6.4.2'] .= "
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
	('email_webmaster', 'core', 'string', '".real_escape_string($result['email_webmaster'])."', '', NOW(), '', 1);
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
	('email_client', 'core', 'string', '".real_escape_string($result['email_client'])."', '', NOW(), '', 1);
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
	('nom_expediteur', 'core', 'string', '".real_escape_string($result['nom_expediteur'])."', '', NOW(), '', 1);
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
	('email_paypal', 'core', 'string', '".real_escape_string($result['email_paypal'])."', '', NOW(), '', 1);";
	}
}
if(file_exists($GLOBALS['dirroot'] . '/modules/attributs')) {
	$sql_update_array['6.4.2'] .= "
ALTER TABLE `peel_nom_attributs` ADD `type_affichage_attribut` tinyint(1) NOT NULL DEFAULT '0';";
}
$sql_update_array['6.4.2'] .= "
-- FAIT après la version 6.4.2 :

ALTER TABLE `peel_banniere` ADD `on_search_engine_page` TINYINT( 1 ) NOT NULL ;
ALTER TABLE `peel_produits_attributs` ADD INDEX ( `nom_attribut_id` , `attribut_id` ) ;
ALTER TABLE `peel_produits_attributs` ADD INDEX ( `produit_id` ) ;
ALTER TABLE `peel_ecotaxes` CHANGE `nom` `nom_fr` MEDIUMTEXT NOT NULL ;
ALTER TABLE `peel_profil` CHANGE `name` `name_fr` VARCHAR( 100 ) NOT NULL DEFAULT '';
ALTER TABLE `peel_profil` CHANGE `description_document` `description_document_fr` TEXT NOT NULL;
ALTER TABLE `peel_profil` CHANGE `document` `document_fr` VARCHAR( 255 ) NOT NULL DEFAULT '';
ALTER TABLE `peel_tarifs` ADD `poidsmin` FLOAT( 10, 2 ) NOT NULL DEFAULT '0' AFTER `id` ;
ALTER TABLE `peel_tarifs` ADD `totalmin` FLOAT( 10, 2 ) NOT NULL DEFAULT '0' AFTER `poidsmax` ;
ALTER TABLE `peel_import_field` CHANGE `texte` `texte_fr` VARCHAR( 255 ) NOT NULL ;

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
(1, 'compatibility_mode_with_htmlentities_encoding_content', 'core', 'boolean', 'false', '', NOW(), 'Si true : permet de décoder les données en BDD encodées par des versions de PEEL < 5.7.  Mettre à false pour une nouvelle boutique, et à true si des données ont été migrées', 1),
(2, 'post_variables_with_html_allowed_if_not_admin', 'core', 'array', '\"description\"', '', NOW(), 'Protection générale supplémentaire en complément de nohtml_real_escape_string', 1),
(3, 'order_article_order_by', 'core', 'string', 'id', '', NOW(), 'Spécifie l''ordre des produits dans une commande, s''applique sur l''ensemble de la boutique', 1),
(4, 'allow_command_product_ongift', 'core', 'boolean', 'false', '', NOW(), 'Permet aux produits cadeaux (champ on_gift dans peel_produits) d''être également commandés comme des produits ordinaire, en plus d''être commandé avec les points cadeaux.', 1),
(5, 'uploaded_file_max_size', 'core', 'integer', '4194304', '', NOW(), 'En octets / in bytes => Par défaut 4Mo / Au delà de cette limite, les fichiers ne seront pas acceptés', 1),
(6, 'filesize_limit_keep_origin_file', 'core', 'integer', '300000', '', NOW(), 'Taille limite au delà de laquelle les images téléchargées sont regénérées par PHP et sauvegardées en JPG', 1),
(7, 'image_max_width', 'core', 'integer', '1024', '', NOW(), 'Taille limite au delà de laquelle les images téléchargées sont converties en JPG à cette largeur maximum', 1),
(8, 'image_max_height', 'core', 'integer', '768', '', NOW(), 'Taille limite au delà de laquelle les images téléchargées sont converties en JPG à cette hauteur maximum', 1),
(9, 'jpeg_quality', 'core', 'integer', '88', '', NOW(), 'Qualité pour les JPEG créés par le serveur / PHP default for JPEG quality: 75', 1),
(10, 'session_cookie_basename', 'core', 'string', 'sid', '', NOW(), 'Sera complété par un hash de 8 caractères correspondant au chemin d''installation de cette boutique', 1),
(11, 'sha256_encoding_salt', 'core', 'string', 'k)I8#;z=TIxnXmIPdW2TRzt4Ov89|#V~cU@]', '', NOW(), 'Used in password hash calculation. If you change it, old passwords will not be compatible anymore.', 1),
(12, 'backoffice_directory_name', 'core', 'string', 'administrer', '', NOW(), 'Le nom du répertoire d''administration peut être changé, mais dans ce cas il faut aussi le changer manuellement dans l''arborescence du site sur le disque dur', 1),
(13, 'cache_folder', 'core', 'string', 'cache', '', NOW(), 'Le nom du répertoire de cache peut être changé, mais dans ce cas il faut aussi le changer manuellement sur le disque dur.', 1),
(14, 'force_display_reseller_prices_without_taxes', 'core', 'boolean', 'true', '', NOW(), '', 1),
(15, 'delivery_cost_calculation_mode', 'core', 'string', 'cheapest', '', NOW(), 'Par défaut : on prend les frais de port les moins chers qui correspondent aux contraintes poids / montant du caddie', 1),
(16, 'force_sessions_for_subdomains', 'core', 'boolean', 'false', '', NOW(), 'Par défaut les cookies ne sont valables que pour un sous-domaine donné (exemple : www). C''est bien de faire cela par défaut car parfois cookie_domain bloque le déclenchement des sessions chez certains hébergeurs comme 1and1. Pour rendre disponible les cookies pour tous les sous-domaines mettez à true\r\n', 1),
(17, 'admin_fill_empty_bill_number_by_number_format', 'core', 'boolean', 'true', '', NOW(), 'Dans l''édition de facture, si numéro de facture vide, on remplit par défaut automatiquement format de numéro à générer', 1),
(18, 'payment_status_create_bill', 'core', 'string', '1,2,3', '', NOW(), 'Dès qu''une commande est dans le statut \$payment_status_create_bill, son numéro de facture est créé', 1),
(19, 'smarty_avoid_check_template_files_update', 'core', 'boolean', 'false', '', NOW(), 'Passer à true si vous voulez accélérer un site en production. Attention : si true, alors les modifications que vous ferez sur les templates nécessiteront MAJ manuelle du cache', 1),
(20, 'use_database_permanent_connection', 'core', 'boolean', 'false', '', NOW(), 'Valeurs possibles : true, ''local'', ''no'' / false', 1),
(21, 'allow_w3c_validator_access_admin', 'core', 'boolean', 'false', '', NOW(), 'ATTENTION SECURITE : la ligne ci-dessous doit rester à false sauf cas exceptionnel de test technique de l''administration', 1),
(22, 'rating_max_value', 'core', 'integer', '5', '', NOW(), '', 1),
(23, 'rating_unitwidth', 'core', 'integer', '21', '', NOW(), 'The width (in pixels) of each rating unit (star, etc.)', 1),
(24, 'sessions_duration', 'core', 'integer', '180', '', NOW(), '', 1),
(25, 'display_errors_for_ips', 'core', 'string', '', '', NOW(), '', 1),
(26, 'quotation_delay', 'core', 'string', '6 mois', '', NOW(), '', 1),
(27, 'avoir', 'core', 'integer', '10', '', NOW(), '', 1),
(28, 'commission_affilie', 'core', 'integer', '0', '', NOW(), '', 1),
(29, 'id', 'core', 'integer', '1', '', NOW(), '', 1),
(30, 'css', 'core', 'string', 'screen.css', '', NOW(), '', 1),
(31, 'template_directory', 'core', 'string', 'peel9', '', NOW(), '', 1),
(32, 'template_multipage', 'core', 'string', 'default_1', '', NOW(), '', 1),
(33, 'email_paypal', 'core', 'string', '', '', NOW(), '', 1),
(34, 'email_commande', 'core', 'string', '', '', NOW(), '', 1),
(35, 'email_webmaster', 'core', 'string', '', '', NOW(), '', 1),
(36, 'nom_expediteur', 'core', 'string', '', '', NOW(), '', 1),
(37, 'email_client', 'core', 'string', '', '', NOW(), '', 1),
(38, 'on_logo', 'core', 'integer', '1', '', NOW(), '', 1),
(39, 'favicon', 'core', 'string', '', '', NOW(), '', 1),
(40, 'timemax', 'core', 'integer', '1800', '', NOW(), '', 1),
(41, 'pays_exoneration_tva', 'core', 'string', '', '', NOW(), '', 1),
(42, 'seuil', 'core', 'integer', '5', '', NOW(), '', 1),
(43, 'seuil_total', 'core', 'float', '0.00000', '', NOW(), '', 1),
(44, 'seuil_total_reve', 'core', 'float', '0.00000', '', NOW(), '', 1),
(45, 'module_retail', 'core', 'integer', '1', '', NOW(), '', 1),
(46, 'module_affilie', 'core', 'integer', '1', '', NOW(), '', 1),
(47, 'module_lot', 'core', 'integer', '1', '', NOW(), '', 1),
(48, 'module_parrain', 'core', 'integer', '0', '', NOW(), '', 1),
(49, 'module_cadeau', 'core', 'integer', '1', '', NOW(), '', 1),
(50, 'module_devise', 'core', 'integer', '1', '', NOW(), '', 1),
(51, 'devise_defaut', 'core', 'integer', '1', '', NOW(), '', 1),
(52, 'module_nuage', 'core', 'integer', '1', '', NOW(), '', 1),
(53, 'module_flash', 'core', 'integer', '0', '', NOW(), '', 1),
(54, 'module_cart_preservation', 'core', 'integer', '1', '', NOW(), '', 1),
(55, 'module_vacances', 'core', 'integer', '0', '', NOW(), '', 1),
(56, 'module_vacances_type', 'core', 'integer', '0', '', NOW(), '', 1),
(57, 'module_vacances_fournisseur', 'core', 'integer', '1', '', NOW(), '', 1),
(58, 'module_pub', 'core', 'integer', '1', '', NOW(), '', 1),
(59, 'module_rss', 'core', 'integer', '1', '', NOW(), '', 1),
(60, 'module_avis', 'core', 'integer', '1', '', NOW(), '', 1),
(61, 'module_precedent_suivant', 'core', 'integer', '1', '', NOW(), '', 1),
(62, 'module_faq', 'core', 'integer', '0', '', NOW(), '', 1),
(63, 'module_forum', 'core', 'integer', '0', '', NOW(), '', 1),
(64, 'module_giftlist', 'core', 'integer', '0', '', NOW(), '', 1),
(65, 'module_entreprise', 'core', 'integer', '0', '', NOW(), '', 1),
(66, 'sips', 'core', 'string', '', '', NOW(), '', 1),
(67, 'systempay_payment_count', 'core', 'string', '1', '', NOW(), '', 1),
(68, 'systempay_payment_period', 'core', 'string', '0', '', NOW(), '', 1),
(69, 'systempay_cle_test', 'core', 'string', '', '', NOW(), '', 1),
(70, 'systempay_cle_prod', 'core', 'string', '', '', NOW(), '', 1),
(71, 'systempay_test_mode', 'core', 'boolean', 'true', '', NOW(), '', 1),
(72, 'systempay_code_societe', 'core', 'string', '0', '', NOW(), '', 1),
(73, 'paybox_cgi', 'core', 'string', '', '', NOW(), '', 1),
(74, 'paybox_site', 'core', 'string', '', '', NOW(), '', 1),
(75, 'paybox_rang', 'core', 'string', '', '', NOW(), '', 1),
(76, 'paybox_identifiant', 'core', 'string', '', '', NOW(), '', 1),
(77, 'email_moneybookers', 'core', 'string', '', '', NOW(), '', 1),
(78, 'secret_word', 'core', 'string', '', '', NOW(), '', 1),
(79, 'spplus', 'core', 'string', '', '', NOW(), '', 1),
(80, 'module_ecotaxe', 'core', 'integer', '1', '', NOW(), '', 1),
(81, 'module_filtre', 'core', 'integer', '1', '', NOW(), '', 1),
(82, 'nb_produit_page', 'core', 'integer', '10', '', NOW(), '', 1),
(83, 'module_rollover', 'core', 'integer', '1', '', NOW(), '', 1),
(84, 'type_rollover', 'core', 'integer', '2', '', NOW(), '', 1),
(85, 'logo_affiliation', 'core', 'string', '', '', NOW(), '', 1),
(86, 'small_width', 'core', 'integer', '160', '', NOW(), '', 1),
(87, 'small_height', 'core', 'integer', '160', '', NOW(), '', 1),
(88, 'medium_width', 'core', 'integer', '300', '', NOW(), '', 1),
(89, 'medium_height', 'core', 'integer', '300', '', NOW(), '', 1),
(90, 'mode_transport', 'core', 'integer', '1', '', NOW(), '', 1),
(91, 'module_url_rewriting', 'core', 'integer', '1', '', NOW(), '', 1),
(92, 'display_prices_with_taxes', 'core', 'integer', '1', '', NOW(), '', 1),
(93, 'display_prices_with_taxes_in_admin', 'core', 'integer', '1', '', NOW(), '', 1),
(94, 'html_editor', 'core', 'integer', '0', '', NOW(), '', 1),
(95, 'format_numero_facture', 'core', 'string', '[id]', '', NOW(), '', 1),
(96, 'default_country_id', 'core', 'integer', '1', '', NOW(), '', 1),
(97, 'nb_product', 'core', 'integer', '0', '', NOW(), '', 1),
(98, 'nb_on_top', 'core', 'integer', '5', '', NOW(), '', 1),
(99, 'nb_last_views', 'core', 'integer', '0', '', NOW(), '', 1),
(100, 'auto_promo', 'core', 'integer', '0', '', NOW(), '', 1),
(101, 'act_on_top', 'core', 'integer', '0', '', NOW(), '', 1),
(102, 'tag_analytics', 'core', 'string', '', '', NOW(), '', 1),
(103, 'site_suspended', 'core', 'boolean', 'false', '', NOW(), '', 1),
(104, 'small_order_overcost_limit', 'core', 'float', '0.00000', '', NOW(), '', 1),
(105, 'small_order_overcost_amount', 'core', 'float', '0.00000', '', NOW(), '', 1),
(106, 'small_order_overcost_tva_percent', 'core', 'float', '0.00', '', NOW(), '', 1),
(107, 'module_captcha', 'core', 'integer', '1', '', NOW(), '', 1),
(108, 'allow_add_product_with_no_stock_in_cart', 'core', 'integer', '0', '', NOW(), '', 1),
(109, 'payment_status_decrement_stock', 'core', 'string', '2,3', '', NOW(), '', 1),
(110, 'module_socolissimo', 'core', 'integer', '0', '', NOW(), '', 1),
(111, 'module_icirelais', 'core', 'integer', '0', '', NOW(), '', 1),
(112, 'module_autosend', 'core', 'integer', '0', '', NOW(), '', 1),
(113, 'module_autosend_delay', 'core', 'integer', '5', '', NOW(), '', 1),
(114, 'category_count_method', 'core', 'string', 'individual', '', NOW(), '', 1),
(115, 'partner_count_method', 'core', 'string', 'individual', '', NOW(), '', 1),
(116, 'admin_force_ssl', 'core', 'integer', '0', '', NOW(), '', 1),
(117, 'anim_prod', 'core', 'integer', '0', '', NOW(), '', 1),
(118, 'export_encoding', 'core', 'string', 'utf-8', '', NOW(), '', 1),
(119, 'zoom', 'core', 'string', 'jqzoom', '', NOW(), '', 1),
(120, 'enable_prototype', 'core', 'integer', '1', '', NOW(), '', 1),
(121, 'enable_jquery', 'core', 'integer', '1', '', NOW(), '', 1),
(122, 'send_email_active', 'core', 'integer', '1', '', NOW(), '', 1),
(123, 'minimal_amount_to_order', 'core', 'string', '0.00000', '', NOW(), '', 1),
(124, 'display_nb_product', 'core', 'integer', '0', '', NOW(), '', 1),
(125, 'type_affichage_attribut', 'core', 'integer', '0', '', NOW(), '', 1),
(126, 'fb_admins', 'core', 'string', '', '', NOW(), '', 1),
(127, 'facebook_page_link', 'core', 'string', '', '', NOW(), '', 1),
(128, 'category_order_on_catalog', 'core', 'integer', '0', '', NOW(), '', 1),
(129, 'global_remise_percent', 'core', 'string', '0.00000', '', NOW(), '', 1),
(130, 'availability_of_carrier', 'core', 'integer', '0', '', NOW(), '', 1),
(131, 'popup_width', 'core', 'integer', '310', '', NOW(), '', 1),
(132, 'popup_height', 'core', 'integer', '160', '', NOW(), '', 1),
(133, 'in_category', 'core', 'integer', '0', '', NOW(), '', 1),
(134, 'facebook_connect', 'core', 'string', '', '', NOW(), '', 1),
(135, 'fb_appid', 'core', 'string', '', '', NOW(), '', 1),
(136, 'fb_secret', 'core', 'string', '', '', NOW(), '', 1),
(137, 'fb_baseurl', 'core', 'string', '', '', NOW(), '', 1),
(138, 'module_conditionnement', 'core', 'integer', '0', '', NOW(), '', 1),
(139, 'keep_old_orders_intact', 'core', 'integer', '0', '', NOW(), '', 1),
(140, 'default_picture', 'core', 'string', 'image_defaut_peel.png', '', NOW(), '', 1),
(149, 'module_tnt', 'core', 'integer', '', '', NOW(), '', 1),
(150, 'sign_in_twitter', 'core', 'string', '', '', NOW(), '', 1),
(151, 'googlefriendconnect', 'core', 'string', '', '', NOW(), '', 1),
(152, 'session_save_path', 'core', 'string', '', '', NOW(), 'Répertoire sur le disque pour stocker les sessions. Exemple : /home/example/sessions . Attention : ce répertoire en doit pas être accessible par http => il ne doit pas être à l''intérieur de votre répertoire peel. Laisser vide si on veut le répertoire défini par défaut dans php.ini du serveur', 1),
(153, 'general_print_image', 'core', 'string', '{\$GLOBALS[''repertoire_images'']}/imprimer.jpg', '', NOW(), '', 1),
(154, 'general_home_image1', 'core', 'string', '', '', NOW(), '', 1),
(155, 'general_home_image2', 'core', 'string', '', '', NOW(), '', 1),
(156, 'general_product_image', 'core', 'string', '', '', NOW(), '', 1),
(157, 'general_send_email_image', 'core', 'string', '{\$GLOBALS[''repertoire_images'']}/tell_friend.png', '', NOW(), '', 1),
(158, 'general_give_your_opinion_image', 'core', 'string', '{\$GLOBALS[''repertoire_images'']}/donnez_avis.png', '', NOW(), '', 1),
(159, 'general_read_all_reviews_image', 'core', 'string', '{\$GLOBALS[''repertoire_images'']}/tous_les_avis.png', '', NOW(), '', 1),
(160, 'general_add_notepad_image', 'core', 'string', '{\$GLOBALS[''repertoire_images'']}/ajout_pense_bete.png', '', NOW(), '', 1),
(161, 'check_allowed_types', 'auto', 'boolean', 'false', '', NOW(), 'Vous pouvez activer une vérification du type MIME des fichiers téléchargés. Cela pose de nombreux problèmes car cette information n''est pas fiable et des navigateurs envoient des types MIME parfois imprévus => cette vérification est désactivée par défaut', 1),
(162, 'allowed_types', 'auto', 'array', '\"image/gif\" => \".gif\", \"image/pjpeg\" => \".jpg, .jpeg\", \"image/jpeg\" => \".jpg, .jpeg\", \"image/x-png\" => \".png\", \"image/png\" => \".png\", \"text/plain\" => \".html, .php, .txt, .inc, .csv\", \"text/comma-separated-values\" => \".csv\", \"application/comma-separated-values\" => \".csv\", \"text/csv\" => \".csv\", \"application/vnd.ms-excel\" => \".csv\", \"application/csv-tab-delimited-table\" => \".csv\", \"application/octet-stream\" => \"\", \"application/pdf\" => \".pdf\", \"application/force-download\" => \"\", \"application/x-shockwave-flash\" => \".swf\", \"application/x-download\" => \"\"', '', NOW(), 'Cette variable est utilisée si check_allowed_types = true', 1),
(163, 'extensions_valides_any', 'auto', 'array', '\"jpg\", \"jpeg\", \"gif\", \"png\", \"csv\", \"txt\", \"pdf\", \"zip\"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(164, 'extensions_valides_data', 'auto', 'array', '\"csv\", \"txt\"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(165, 'extensions_valides_image_or_pdf', 'auto', 'array', '\"jpg\", \"jpeg\", \"gif\", \"png\", \"pdf\"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(166, 'extensions_valides_image', 'auto', 'array', '\"jpg\", \"jpeg\", \"gif\", \"png\"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(167, 'extensions_valides_image_or_swf', 'auto', 'array', '\"jpg\", \"jpeg\", \"gif\", \"png\", \"swf\"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(168, 'extensions_valides_image_or_ico', 'auto', 'array', '\"jpg\", \"jpeg\", \"gif\", \"png\", \"ico\"', '', NOW(), 'Vérification en fonction des extensions des fichiers téléchargés', 1),
(169, 'uploaded_images_name_pattern', 'core', 'string', '^[0-9]{6}_[0-9]{6}_PEEL_[0-9a-z-A-Z]{8}\\.[jpg|png|gif]$', '', NOW(), 'Permet de valider le format des noms des images uploadées dans peel', 1),
(170, 'site_general_columns_count', 'core', 'integer', '3', '', NOW(), '', 1),
(171, 'product_details_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1),
(172, 'ad_details_page_columns_count', 'core', 'integer', '3', '', NOW(), '', 1),
(173, 'ads_list_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1),
(174, 'blog_index_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1),
(175, 'listecadeau_list_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1),
(176, 'listecadeau_details_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1),
(177, 'cart_preservation_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1),
(178, 'references_page_columns_count', 'core', 'integer', '1', '', NOW(), '', 1),
(179, 'achat_maintenant_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1),
(180, 'caddie_affichage_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1),
(181, 'fin_commande_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1),
(182, 'achat_index_page_columns_count', 'core', 'integer', '2', '', NOW(), '', 1),
(183, 'edit_prices_on_products_list', 'core', 'string', 'edit', '', NOW(), '', 1),
(184, 'show_qrcode_on_product_pages', 'core', 'boolean', 'true', '', NOW(), '', 1),
(185, 'minify_css', 'core', 'boolean', 'false', '', NOW(), '', 1),
(186, 'minify_js', 'core', 'boolean', 'false', '', NOW(), '', 1),
(187, 'product_categories_depth_in_menu', 'core', 'integer', '1', '', NOW(), '', 1),
(188, 'content_categories_depth_in_menu', 'core', 'integer', '1', '', NOW(), 'Seules les rubriques de contenu avec position>0 s''afficheront, ce qui permet d''en exclure du menu en les mettant à position=0', 1),
(189, 'main_menu_items_if_available', 'core', 'array', '\"home\", \"catalog\", \"news\", \"promotions\", \"annonces\", \"vitrine\", \"check\", \"account\", \"contact\", \"admin\"', '', NOW(), 'Liste à définir dans l''ordre d''affichage parmi : \"home\", \"catalog\", \"content\", \"news\", \"promotions\", \"annonces\", \"vitrine\", \"check\", \"account\", \"contact\", \"promotions\", \"admin\"', 1),
(190, 'template_engine', 'core', 'string', 'smarty', '', NOW(), 'Par défaut : smarty - Existe aussi en version de test : twig', 1);

-- A n''effectuer que lorsque les données sont migrées : 
-- DROP TABLE `peel_sites`;
";
	$sql_update_array['7.0.1'] = "
-- FAIT après la version 7.0.1 :
ALTER TABLE `peel_nom_attributs` ADD `show_description` tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE `peel_commandes_articles` ADD `attributs_list` MEDIUMTEXT NOT NULL;
ALTER TABLE `peel_banniere` ADD `do_not_display_on_pages_related_to_user_ids_list` varchar(255) NOT NULL DEFAULT '';
-- Si pas déjà le cas dans votre table peel_commandes_articles suite à des migrations, on force nom_attribut en tant que MEDIUMTEXT
ALTER TABLE `peel_commandes_articles` CHANGE `nom_attribut` `nom_attribut` MEDIUMTEXT NOT NULL;
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
('catalog_products_columns_default', 'core', 'integer', '3', '', NOW(), '', 1),
('associated_products_columns_default', 'core', 'integer', '3', '', NOW(), '', 1),
('associated_products_display_mode', 'core', 'string', 'column', '', NOW(), '', 1),
('show_on_estimate_text', 'core', 'boolean', 'true', '', NOW(), '', 1),
('show_add_to_cart_on_free_products', 'core', 'boolean', 'true', '', NOW(), '', 1),
('show_short_description_on_product_details', 'core', 'boolean', 'true', '', NOW(), '', 1),
('category_show_more_on_catalog_if_no_order_allowed', 'core', 'boolean', 'true', '', NOW(), '', 1);
";
$sql_update_array['7.0.2'] = "
-- FAIT après la version 7.0.2 :
ALTER TABLE `peel_utilisateurs` DROP `next_contact_reason`;
UPDATE `peel_configuration` SET `type`='boolean' WHERE `technical_code` LIKE 'site_suspended' OR `technical_code` LIKE 'systempay_test_mode';
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
('show_on_affiche_guide', 'core', 'array', '\"contact\", \"affiliate\", \"retailer\", \"faq\", \"forum\", \"lexique\", \"partner\", \"references\", \"access_plan\"', '', NOW(), 'Liste à définir dans l''ordre d''affichage parmi : \"contact\", \"affiliate\", \"retailer\", \"faq\", \"forum\", \"lexique\", \"partner\", \"references\", \"access_plan\"', 1),
('replace_words_in_lang_files', 'core', 'string', '', '', NOW(), '', 1),
('twitter_page_link', 'core', 'string', '', '', NOW(), '', 1),
('googleplus_page_link', 'core', 'string', '', '', NOW(), '', 1),
('skip_images_keywords', 'core', 'array', '', '', NOW(), '', 1),
('appstore_link', 'core', 'string', '', '', NOW(), '', 1),
('categories_side_menu_item_max_length', 'core', 'integer', '28', '', NOW(), '', 1),
('phone_cti_primary_site_list_calls_url', 'core', 'string', '', '', NOW(), '', 1),
('email_accounts_for_bounces_handling', 'core', 'array', '', '', NOW(), 'Format : ''email'' => ''password''', 1),
('tagcloud_display_count', 'core', 'integer', '12', '', NOW(), '', 1),
('cron_login', 'core', 'array', '', '', NOW(), 'Format : ''password'' => ''login''', 1),
('filter_stop_words', 'core', 'string', 'afin aie aient aies ailleurs ainsi ait alentour alias allaient allais allait allez allons alors apres aprs assez attendu aucun aucune aucuns audit aujourd aujourdhui auparavant auprs auquel aura aurai auraient aurais aurait auras aurez auriez aurions aurons auront aussi aussitot autant autour autre autrefois autres autrui aux auxdites auxdits auxquelles auxquels avaient avais avait avant avec avez aviez avions avoir avons ayant ayez ayons bah banco bas beaucoup ben bien bientot bis bon caha cahin car ceans ceci cela celle celles celui cent cents cependant certain certaine certaines certains certes ces cet cette ceux cgr chacun chacune champ chaque cher chez cinq cinquante combien comme comment contrario contre crescendo dabord daccord daffilee dailleurs dans daprs darrache davantage debout debut dedans dehors deja dela demain demblee depuis derechef derriere des desdites desdits desormais desquelles desquels dessous dessus deux devant devers devrait die differentes differents dire dis disent dit dito divers diverses dix doit donc dont dorenavant dos douze droite dudit duquel durant elle elles encore enfin ensemble ensuite entre envers environ essai est et etaient etais etait etant etat etc ete etes etiez etions etre eue eues euh eûmes eurent eus eusse eussent eusses eussiez eussions eut eutes eux expres extenso extremis facto faire fais faisaient faisais faisait faisons fait faites fallait faudrait faut flac fois font force fors fort forte fortiori frais fumes fur furent fus fusse fussent fusses fussiez fussions fut futes ghz grosso gure han haut hein hem heu hier hola hop hormis hors hui huit hum ibidem ici idem illico ils ipso item jadis jamais jusqu jusqua jusquau jusquaux jusque juste km² laquelle lautre lequel les lesquelles lesquels leur leurs lez loin lon longtemps lors lorsqu lorsque lot lots lui lun lune maint mainte maintenant maintes maints mais mal malgre meme memes mes mgr mhz mieux mil mille milliards millions mine minima mm² modo moi moins mon mot moult moyennant naguere neanmoins neuf nommes non nonante nonobstant nos notre nous nouveau nouveaux nouvelle nouvelles nul nulle octante ont onze ouais ou oui outre par parbleu parce parfois parmi parole partout pas passe passim pendant personne personnes petto peu peut peuvent peux piece pied pis plupart plus plusieurs plutot point posteriori pour pourquoi pourtant prealable presqu presque primo priori prix prou prs puis puisqu puisque quand quarante quasi quatorze quatre que quel quelle quelles quelqu quelque quelquefois quelques quelquun quelquune quels qui quiconque quinze quoi quoiqu quoique ref refs revoici revoila rien sans sauf secundo seize selon sensu sept septante sera serai seraient serais serait seras serez seriez serions serons seront ses seulement sic sien sine sinon sitot situ six soi soient soixante sommes son sont soudain sous souvent soyez soyons stricto suis sujet sur surtout sus tandis tant tantot tard tel telle tellement telles tels temps ter tes toi ton tot toujours tous tout toute toutefois toutes treize trente tres trois trop trs une unes uns usd vais valeur vas vends vers versa veut veux via vice vingt vingts vingt vis vite vitro vivo voici voie voient voila voire volontiers vont vos votre vous zero', 'fr', NOW(), 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatif.', 1),
('filter_stop_words', 'core', 'string', 'a able about above abst accordance according accordingly across act actually added adj affected affecting affects after afterwards again against ah all almost alone along already also although always am among amongst an and announce another any anybody anyhow anymore anyone anything anyway anyways anywhere apparently approximately are aren arent arise around as aside ask asking at auth available away awfully b back be became because become becomes becoming been before beforehand begin beginning beginnings begins behind being believe below beside besides between beyond biol both brief briefly but by c ca came can cannot can''t cause causes certain certainly co com come comes contain containing contains could couldnt d date did didn''t different do does doesn''t doing done don''t down downwards due during e each ed edu effect eg eight eighty either else elsewhere end ending enough especially et et-al etc even ever every everybody everyone everything everywhere ex except f far few ff fifth first five fix followed following follows for former formerly forth found four from further furthermore g gave get gets getting give given gives giving go goes gone got gotten h had happens hardly has hasn''t have haven''t having he hed hence her here hereafter hereby herein heres hereupon hers herself hes hi hid him himself his hither home how howbeit however hundred i id ie if i''ll im immediate immediately importance important in inc indeed index information instead into invention inward is isn''t it itd it''ll its itself i''ve j just k keep 	keeps kept kg km know known knows l largely last lately later latter latterly least less lest let lets like liked likely line little ''ll look looking looks ltd m made mainly make makes many may maybe me mean means meantime meanwhile merely mg might million miss ml more moreover most mostly mr mrs much mug must my myself n na name namely nay nd near nearly necessarily necessary need needs neither never nevertheless new next nine ninety no nobody non none nonetheless noone nor normally nos not noted nothing now nowhere o obtain obtained obviously of off often oh ok okay old omitted on once one ones only onto or ord other others otherwise ought our ours ourselves out outside over overall owing own p page pages part particular particularly past per perhaps placed please plus poorly possible possibly potentially pp predominantly present previously primarily probably promptly proud provides put q que quickly quite qv r ran rather rd re readily really recent recently ref refs regarding regardless regards related relatively research respectively resulted resulting results right run s said same saw say saying says sec section see seeing seem seemed seeming seems seen self selves sent seven several shall she shed she''ll shes should shouldn''t show showed shown showns shows significant significantly similar similarly since six slightly so some somebody somehow someone somethan something sometime sometimes somewhat somewhere soon sorry specifically specified specify specifying still stop strongly sub substantially successfully such sufficiently suggest sup sure 	t take taken taking tell tends th than thank thanks thanx that that''ll thats that''ve the their theirs them themselves then thence there thereafter thereby thered therefore therein there''ll thereof therere theres thereto thereupon there''ve these they theyd they''ll theyre they''ve think this those thou though thoughh thousand throug through throughout thru thus til tip to together too took toward towards tried tries truly try trying ts twice two u un under unfortunately unless unlike unlikely until unto up upon ups us use used useful usefully usefulness uses using usually v value various ''ve very via viz vol vols vs w want wants was wasn''t way we wed welcome we''ll went were weren''t we''ve what whatever what''ll whats when whence whenever where whereafter whereas whereby wherein wheres whereupon wherever whether which while whim whither who whod whoever whole who''ll whom whomever whos whose why widely willing wish with within without won''t words world would wouldn''t www x y yes yet you youd you''ll your youre yours yourself yourselves you''ve z zero', 'en', NOW(), 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatif.', 1),
('filter_stop_words', 'core', 'string', 'ab aber abgerufen abgerufene abgerufener abgerufenes acht ahnlich alle allein allem allen aller allerdings allerlei alles allgemein allmahlich allzu als alsbald also am an ander andere anderem anderen anderer andererseits anderes anderm andern andernfalls anders anerkannt anerkannte anerkannter anerkanntes anfangen anfing angefangen angesetze angesetzt angesetzten angesetzter ansetzen anstatt arbeiten auch auf aufgehort aufgrund aufhoren aufhorte aufzusuchen aus ausdrucken ausdruckt ausdruckte ausgenommen außen ausser außer ausserdem außerdem außerhalb author autor bald bearbeite bearbeiten bearbeitete bearbeiteten bedarf bedurfen bedurfte befragen befragte befragten befragter begann beginnen begonnen behalten behielt bei beide beiden beiderlei beides beim beinahe beitragen beitrugen bekannt bekannte bekannter bekennen benutzt bereits berichten berichtet berichtete berichteten besonders besser bestehen besteht betrachtlich bevor bezuglich bietet bin bis bis bisher bislang bist bleiben blieb bloss bloß boden brachte brachten brauchen braucht brauchte bringen bsp bzw ca da dabei dadurch dafur dagegen daher dahin damals damit danach daneben dank danke danken dann dannen daran darauf daraus darf darfst darin daruber daruberhinaus darum darunter das dass daß dasselbe davon davor dazu dein deine deinem deinen deiner deines dem demnach demselben den denen denn dennoch denselben der derart derartig derem deren derer derjenige derjenigen derselbe derselben derzeit des deshalb desselben dessen desto deswegen dich die diejenige dies diese dieselbe dieselben diesem diesen dieser dieses diesseits dinge dir direkt direkte direkten direkter doch doppelt dort dorther dorthin drauf drei dreißig drin dritte druber drunter du dunklen durch durchaus durfen durfte durfte durften eben ebenfalls ebenso ehe eher eigenen eigenes eigentlich ein einbaun eine einem einen einer einerseits eines einfach einfuhren einfuhrte einfuhrten eingesetzt einig einige einigem einigen einiger einigermaßen einiges einmal eins einseitig einseitige einseitigen einseitiger einst einstmals einzig ende entsprechend entweder er erganze erganzen erganzte erganzten erhalt erhalten erhielt erhielten erneut eroffne eroffnen eroffnet eroffnete eroffnetes erst erste ersten erster es etc etliche etwa etwas euch euer eure eurem euren eurer eures fall falls fand fast ferner finden findest findet folgende folgenden folgender folgendes folglich fordern fordert forderte forderten fortsetzen fortsetzt fortsetzte fortsetzten fragte frau frei freie freier freies fuer funf fur gab gangig gangige gangigen gangiger gangiges ganz ganze ganzem ganzen ganzer ganzes ganzlich gar gbr geb geben geblieben gebracht gedurft geehrt geehrte geehrten geehrter gefallen gefalligst gefallt gefiel gegeben gegen gehabt gehen geht gekommen gekonnt gemacht gemass gemocht genommen genug gern gesagt gesehen gestern gestrige getan geteilt geteilte getragen gewesen gewissermaßen gewollt geworden ggf gib gibt gleich gleichwohl gleichzeitig glucklicherweise gmbh gratulieren gratuliert gratulierte gute guten hab habe haben haette halb hallo hast hat hatt hatte hatte hatten hatten hattest hattet hen heraus herein heute heutige hier hiermit hiesige hin hinein hinten hinter hinterher hoch hochstens hundert ich igitt ihm ihn ihnen ihr ihre ihrem ihren ihrer ihres im immer immerhin important in indem indessen info infolge innen innerhalb ins insofern inzwischen irgend irgendeine irgendwas irgendwen irgendwer irgendwie irgendwo ist ja jahrig jahrige jahrigen jahriges je jede jedem jeden jedenfalls jeder jederlei jedes jedoch jemand jene jenem jenen jener jenes jenseits jetzt kam kann kannst kaum kein keine keinem keinen keiner keinerlei keines keines keineswegs klar klare klaren klares klein kleinen kleiner kleines koennen koennt koennte koennten komme kommen kommt konkret konkrete konkreten konkreter konkretes konn konnen konnt konnte konnte konnten konnten kunftig lag lagen langsam langst langstens lassen laut lediglich leer legen legte legten leicht leider lesen letze letzten letztendlich letztens letztes letztlich lichten liegt liest links mache machen machst macht machte machten mag magst mal man manche manchem manchen mancher mancherorts manches manchmal mann margin mehr mehrere mein meine meinem meinen meiner meines meist meiste meisten meta mich mindestens mir mit mithin mochte mochte mochten mochtest mogen moglich mogliche moglichen moglicher moglicherweise morgen morgige muessen muesst muesste muss muß mussen musst mußt mußt musste musste mußte mussten mussten nach nachdem nacher nachhinein nachste nacht nahm namlich naturlich neben nebenan nehmen nein neu neue neuem neuen neuer neues neun nicht nichts nie niemals niemand nimm nimmer nimmt nirgends nirgendwo noch notigenfalls nun nur nutzen nutzt nutzt nutzung ob oben oberhalb obgleich obschon obwohl oder oft ohne per pfui plotzlich pro reagiere reagieren reagiert reagierte rechts regelmaßig rief rund sage sagen sagt sagte sagten sagtest samtliche sang sangen schatzen schatzt schatzte schatzten schlechter schließlich schnell schon schreibe schreiben schreibens schreiber schwierig sechs sect sehe sehen sehr sehrwohl seht sei seid sein seine seinem seinen seiner seines seit seitdem seite seiten seither selber selbst senke senken senkt senkte senkten setzen setzt setzte setzten sich sicher sicherlich sie sieben siebte siehe sieht sind singen singt so sobald sodaß soeben sofern sofort sog sogar solange solc solch solche solchem solchen solcher solches soll sollen sollst sollt sollte sollten solltest somit sondern sonst sonstwo sooft soviel soweit sowie sowohl spater spielen startet startete starteten statt stattdessen steht steige steigen steigt stets stieg stiegen such suchen tages tat tat tatsachlich tatsachlichen tatsachlicher tatsachliches tausend teile teilen teilte teilten titel total trage tragen tragt trotzdem trug tun tust tut txt ubel uber uberall uberallhin uberdies ubermorgen ubrig ubrigens ueber um umso unbedingt und ungefahr unmoglich unmogliche unmoglichen unmoglicher unnotig uns unse unsem unsen unser unser unsere unserem unseren unserer unseres unserm unses unten unter unterbrach unterbrechen unterhalb unwichtig usw vergangen vergangene vergangener vergangenes vermag vermogen vermutlich veroffentlichen veroffentlicher veroffentlicht veroffentlichte veroffentlichten veroffentlichtes verrate verraten verriet verrieten version versorge versorgen versorgt versorgte versorgten versorgtes viel viele vielen vieler vieles vielleicht vielmals vier vollig vollstandig vom von vor voran vorbei vorgestern vorher vorne voruber wachen waere wahrend wahrend wahrenddessen wann war war ware waren waren warst warum was weder weg wegen weil weiß weiter weitere weiterem weiteren weiterer weiteres weiterhin welche welchem welchen welcher welches wem wen wenig wenige weniger wenigstens wenn wenngleich wer werde werden werdet weshalb wessen wichtig wie wieder wieso wieviel wiewohl will willst wir wird wirklich wirst wo wodurch wogegen woher wohin wohingegen wohl wohlweislich wolle wollen wollt wollte wollten wolltest wolltet womit woraufhin woraus worin wurde wurde wurden wurden zahlreich zB zehn zeitweise ziehen zieht zog zogen zu zudem zuerst zufolge zugleich zuletzt zum zumal zur zuruck zusammen zuviel zwanzig zwar zwei zwischen zwolf', 'de', NOW(), 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatif.', 1),
('filter_stop_words', 'core', 'string', 'algun alguna algunas alguno algunos ambos ampleamos ante antes aquel aquellas aquellos aqui arriba atras bajo bastante bien cada cierta ciertas cierto ciertos como con conseguimos conseguir consigo consigue consiguen consigues cual cuando dentro desde donde dos el ellas ellos empleais emplean emplear empleas empleo en encima entonces entre era eramos eran eras eres es esta estaba estado estais estamos estan estoy fin fue fueron fui fuimos gueno ha hace haceis hacemos hacen hacer haces hago incluso intenta intentais intentamos intentan intentar intentas intento ir la largo las lo los mientras mio modo muchos muy nos nosotros otro para pero podeis podemos poder podria podriais podriamos podrian podrias por por qué porque primero  puede pueden puedo quien sabe sabeis sabemos saben saber sabes ser si siendo sin sobre sois solamente solo somos soy su sus también teneis tenemos tener tengo tiempo tiene tienen todo trabaja trabajais trabajamos trabajan trabajar trabajas trabajo tras tuyo ultimo un una unas uno unos usa usais usamos usan usar usas uso va vais valor vamos van vaya verdad verdadera verdadero vosotras vosotros voy yo ', 'es', NOW(), 'Liste de mots sans accents, de 3 lettres et plus (les mots de moins de 3 lettres sont considérés dans tous les cas comme non significatifs) séparés par des espaces. Cette liste permet de filtrer une chaine pour trouver des mots clés significatif.', 1),
('skip_home_top_products', 'core', 'boolean', 'false', '', NOW(), '', 1),
('skip_home_special_products', 'core', 'boolean', 'false', '', NOW(), '', 1),
('skip_home_new_products', 'core', 'boolean', 'false', '', NOW(), '', 1),
('user_mandatory_fields', 'core', 'array', '\"prenom\" => \"STR_ERR_FIRSTNAME\", \"nom_famille\" => \"STR_ERR_NAME\", \"adresse\" => \"STR_ERR_ADDRESS\", \"code_postal\" => \"STR_ERR_ZIP\", \"ville\" => \"STR_ERR_TOWN\", \"pays\" => \"STR_ERR_COUNTRY\", \"telephone\" => \"STR_ERR_TEL\"', '', NOW(), '', 1),
('skip_home_ad_categories_presentation', 'core', 'boolean', 'false', '', NOW(), '', 1);
";
if(file_exists($GLOBALS['dirroot'] . '/modules/lot')) {
	$sql_update_array['7.0.2'] .= "
-- Uniquement pour Premium :
ALTER TABLE `peel_quantites` ADD `promotion_percent` float(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `peel_quantites` ADD `cat_id` int(11) NOT NULL DEFAULT '0';
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/carrousel')) {
	$sql_update_array['7.0.2'] .= "
-- Uniquement pour Carrousel
ALTER TABLE `peel_vignettes_carrousels` ADD `target` varchar(255) NOT NULL DEFAULT '';
";
}
$sql_update_array['7.0.3'] = "
-- FAIT après la version 7.0.3 :
ALTER TABLE `peel_commandes` ADD `f_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
UPDATE peel_commandes SET f_datetime=a_timestamp WHERE numero!='' AND f_datetime='0000-00-00 00:00:00';
ALTER TABLE `peel_commandes` ADD `e_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
UPDATE peel_commandes SET id_statut_livraison=0 WHERE id_statut_livraison=1 AND id_statut_paiement IN (0,1);
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
('article_details_index_page_columns_count', 'core', 'integer', '3', '', NOW(), '', 1),
('lire_index_page_columns_count', 'core', 'integer', '3', '', NOW(), '', 1),
('site_index_page_columns_count', 'core', 'integer', '3', '', NOW(), '', 1),
('display_nb_vote_graphic_view', 'core', 'boolean', 'true', '', NOW(), '', 1),
('display_content_category_diaporama', 'core', 'boolean', 'true', '', NOW(), '', 1),
('subcategorie_nb_column', 'core', 'integer', '5', '', NOW(), '', 1),
('product_category_pages_nb_column', 'core', 'integer', '3', '', NOW(), '', 1),
('display_share_tools_on_product_pages', 'core', 'boolean', 'true', '', NOW(), '', 1),
('prices_precision', 'core', 'integer', '2', '', NOW(), 'Nombre de décimales pour l''affichage des prix / Decimal count for prices display', 1),
('short_order_process', 'core', 'boolean', 'false', '', NOW(), 'Fin du process de commande, si le paramètre short_order_process est actif. Ce paramètre implique l''absence de paiement et de validation des CGV => Utile pour des demandes de devis', 1),
('use_ads_as_products', 'core', 'boolean', 'false', '', NOW(), 'Permet d''ajouter des annonces au panier (nécessite le module d''annonce)', 1),
('tva_annonce', 'core', 'string', '19.6', '', NOW(), 'Spécifie le taux de TVA à appliquer aux annonces lors de leur ajout au panier (fonctionne avec le paramètre use_ads_as_products).', 1);

CREATE TABLE IF NOT EXISTS `peel_diaporama` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_rubrique` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `peel_email_template` ADD `default_signature_code` VARCHAR( 255 ) NOT NULL;
";
$sql_update_array['7.0.4'] = "
-- FAIT après la version 7.0.4 :
-- Le champ on_ad_creation_page est un nouveau champ qui permet de positionner une bannière publicitaire sur la page de création d'annonce.
-- => Il faut ajouter ce champ lors de la mise à jour du code sur nos sites d'annonces
ALTER TABLE `peel_banniere` ADD `on_ad_creation_page` TINYINT( 1 ) NOT NULL AFTER `on_search_engine_page` ;
ALTER TABLE `peel_banniere` CHANGE `on_search_engine_page` `on_search_engine_page` tinyint(1) NOT NULL DEFAULT '0' ;
ALTER TABLE `peel_paiement` ADD `totalmin` FLOAT( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `peel_paiement` ADD `totalmax` FLOAT( 10, 2 ) NOT NULL DEFAULT '0';
ALTER TABLE `peel_utilisateurs` CHANGE `priv` `priv` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `peel_commandes_articles` CHANGE `percent_remise_produit` `percent_remise_produit` float(5,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `peel_commandes_articles` CHANGE `ecotaxe_ttc` `ecotaxe_ttc` float(15,5) NOT NULL DEFAULT '0.00000';
ALTER TABLE `peel_commandes_articles` CHANGE `ecotaxe_ht` `ecotaxe_ht` float(15,5) NOT NULL DEFAULT '0.00000';
ALTER TABLE `peel_devises` CHANGE `conversion` `conversion` float(15,5) NOT NULL DEFAULT '1.00000';
ALTER TABLE `peel_paiement` CHANGE `tarif` `tarif` float(15,5) NOT NULL DEFAULT '0.00000';
ALTER TABLE `peel_produits` CHANGE `promotion` `promotion` float(5,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `peel_produits` CHANGE `prix_promo` `prix_promo` float(15,5) NOT NULL DEFAULT '0.00000';
ALTER TABLE `peel_tarifs` CHANGE `totalmin` `totalmin` float(15,5) NOT NULL DEFAULT '0.00000';
ALTER TABLE `peel_tarifs` CHANGE `totalmax` `totalmax` float(15,5) NOT NULL DEFAULT '0.00000';
ALTER TABLE `peel_tarifs` CHANGE `tva` `tva` float(5,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `peel_commandes` CHANGE `code_facture` `code_facture` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '';
ALTER TABLE `peel_commandes` ADD INDEX `code_facture` (`code_facture`(2));

-- NB : Par défaut Bootstrap n'est pas activé ci-dessous dans le contexte de migration de votre site pour garder la compatibilité avec votre ancienne charte graphique
-- Vous pouvez modifier ces valeurs sur la page de \"gestion des variables de configuration\"
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
('used_uploader', 'core', 'boolean', 'fineuploader', '', NOW(), 'Définit quelle technologie d''upload utiliser / Defines which upload technology to use - possible values = standard, fineuploader', 1),
('chart_product', 'core', 'string', 'flot', '', NOW(), '', 1),
('insert_product_categories_in_menu', 'core', 'boolean', 'true', '', NOW(), '', 1),
('enable_gzhandler', 'core', 'boolean', 'false', '', NOW(), 'Si true : force PHP à compresser ses sorties HTTP', 1),
('load_javascript_async', 'core', 'boolean', 'false', '', NOW(), 'Si true : force les fichiers js en fin de page HTML', 1),
('global_promotion_percent_by_threshold', 'core', 'array', '', '', NOW(), '', 1),
('minify_id_increment', 'core', 'string', '0', '', NOW(), 'Sert pour générer un nom de fichier différent après chaque ?update=1 forcé par un administrateur', 1),
('bootstrap_enabled', 'core', 'boolean', 'true', '', NOW(), 'Activer ou non Bootstrap en front-office', 1),
('disable_add_to_cart_section_if_null_base_price_and_no_option', 'core', 'boolean', 'true', '', NOW(), 'Désactive l''affichage du bouton d''ajout au caddie si le produit est gratuit et sans option - Mettez à false si vous voulez gérer des processus de commande malgré l''absence de prix', 1),
('paypal_additional_fields', 'core', 'string', '<input name=\"solution_type\" value=\"Sole\" type=\"hidden\"><input name=\"landing_page\" value=\"Billing\" type=\"hidden\">', '', NOW(), 'Permet d''ajouter des champs hidden au formulaire de communication à Paypal - par exemple : <input name=\"solution_type\" value=\"Sole\" type=\"hidden\"><input name=\"landing_page\" value=\"Billing\" type=\"hidden\">', 1);
";
/*
$sql_update_array['7.0.4'] .= "
-- SI NECESSAIRE SEULEMENT : Champs ajouté dans la version 6.4.1, mais pas mis dans le script de migration SQL. Cette ligne est donc à exécuter si en cas de migration à partir de la version 6.4.1 ou inférieur
ALTER TABLE `peel_banniere` ADD `alt` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `peel_banniere` ADD `pages_allowed` VARCHAR( 255 ) NOT NULL ;
-- A excéuter seulement si vous ne voulez pas garder menu.css dans modeles/xxx/css/  (c'est la configuration nouvelle dans 7.1)
UPDATE peel_configuration SET string=\"screen.css\" WHERE technical_code='css';
-- A excéuter seulement si vous ne voulez pas mettre Bootstrap dans votre charte graphique
UPDATE peel_configuration SET string=\"false\" WHERE technical_code='bootstrap_enabled';
-- A excéuter seulement si vous voulez prendre les nouveaux réglages de chartes graphiques verticalisées de la version 7.1
UPDATE peel_modules SET location=\"below_middle\" WHERE location=\"left\" OR location=\"right\";
UPDATE peel_modules SET location=\"header\" WHERE technical_code=\"caddie\"; 
UPDATE peel_modules SET location=\"header\" WHERE technical_code=\"search\";
UPDATE peel_modules SET location=\"footer\" WHERE technical_code=\"brand\";
UPDATE peel_modules SET etat=\"0\" WHERE technical_code IN (\"account\",\"catalogue\",\"paiement_secu\");
UPDATE peel_modules SET location=\"bottom_middle\" WHERE technical_code IN (\"best_seller\");
-- Fin de section à exécuter au cas par cas";
*/
$sql_update_array['7.1.0'] = "
-- FAIT APRES VERSION 7.1.0
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
('autocomplete_hide_images', 'core', 'boolean', 'false', '', NOW(), 'Par défaut : false - Permet de ne pas afficher la vignette dans l''autocomplete de la recherche : c''est intéressant en cas d''absence complète d''image sur un site', '1'),
('autocomplete_fast_partial_search', 'core', 'boolean', 'false', '', NOW(), 'Par défaut : false - Permet d''accélerer les recherches en ne cherchant pas toutes les combinaisons possibles. En cas de trop grand nombre de produit, il n''est pas raisonnable de faire des recherches de type LIKE \"%...\"', '1'),
('load_site_specific_files_before_others', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger des fichiers de fonctions non prévus dans le logiciel', '1'),
('load_site_specific_files_after_others', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger des fichiers de fonctions non prévus dans le logiciel', '1'),
('load_site_specific_lang_folders', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger des fichiers de langue non prévus dans le logiciel', '1'),
('load_site_specific_js_files', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger des fichiers de javascript non prévus dans le logiciel', '1'),
('load_site_specific_js_content_array', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger du javascript non prévus dans le logiciel', '1'),
('load_site_specific_js_ready_content_array', 'core', 'array', '', '', NOW(), 'Par défaut : vide - Permet de charger du javascript non prévus dans le logiciel après le chargement de la page', '1'),
('insert_article_categories_in_menu', 'core', 'boolean', 'true', '', NOW(), '', 1),
('only_show_articles_with_picture_in_containers', 'core', 'boolean', 'true', '', NOW(), '', 1);
";
if($current_version<='7.1.0') {
		$sql_update_array['6.4.2'] .= "
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES ('wwwroot', 'core', 'string', '".real_escape_string($GLOBALS['wwwroot'])."', '', NOW(), '', 1);";
}
$sql_update_array['7.1.4'] = "
-- FAIT APRES VERSION 7.1.X
-- Ajout du champ site_id pour la gestion multisite.
ALTER TABLE `peel_admins_actions` ADD `site_id` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `peel_commandes` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `id_ecom` (`id_ecom`), ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_codes_promos` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_articles` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_categories` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_configuration` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_html` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_produits` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_rubriques` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_tarifs` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_utilisateurs` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_utilisateur_connexions` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_zones` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_societe` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_langues` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_devises` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_marques` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_meta` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_cgv` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_contacts` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_legal` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_access_map` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_tailles` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_couleurs` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_banniere` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_nom_attributs` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_attributs` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_continents` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_ecotaxes` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_email_template` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_email_template_cat` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_import_field` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_modules` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_newsletter` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_paiement` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_pays` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_profil` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_statut_livraison` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_statut_paiement` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_tva` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_types` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_webmail` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_commandes_articles` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);

-- MODULE PREMIUM
-- Exécuter ce qui suit que si vous avez le module premium
";
if(file_exists($GLOBALS['dirroot'] . '/modules/faq')) {
	$sql_update_array['7.1.4'] .= "
ALTER TABLE `peel_faq` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/tagcloud')) {
	$sql_update_array['7.1.4'] .= "
ALTER TABLE `peel_tag_cloud` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/groups')) {
	$sql_update_array['7.1.4'] .= "
ALTER TABLE `peel_groupes` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/lexique')) {
	$sql_update_array['7.1.4'] .= "
ALTER TABLE `peel_lexique` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/lot')) {
	$sql_update_array['7.1.4'] .= "
ALTER TABLE `peel_quantites` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/parrainage')) {
	$sql_update_array['7.1.4'] .= "
ALTER TABLE `peel_parrain` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/sauvegarde_recherche')) {
	$sql_update_array['7.1.4'] .= "
ALTER TABLE `peel_alertes` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/affiliation')) {
	$sql_update_array['7.1.4'] .= "
ALTER TABLE `peel_affiliation` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/stock_advanced')) {
	$sql_update_array['7.1.4'] .= "
ALTER TABLE `peel_etatstock` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
$sql_update_array['7.1.4'] .= "

-- MODULES À LA CARTE
";
if(file_exists($GLOBALS['dirroot'] . '/modules/carrousel')) {
	$sql_update_array['7.1.4'] .= "
-- Exécuter ce qui suit que si vous avez le module carrousel
ALTER TABLE `peel_carrousels` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_vignettes_carrousels` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/annonces')) {
	$sql_update_array['7.1.4'] .= "
-- Exécuter ce qui suit que si vous avez le module d'annonce
ALTER TABLE `peel_lot_vente` ADD `creation_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `peel_lot_vente` ADD `site_id` int(11) NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
ALTER TABLE `peel_categories_annonces`  ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `site_id` (`site_id`);
";
}
$sql_update_array['7.1.4'] .= "
-- Ajout de filtre des catégories à exclure de l'application d'un code promo
ALTER TABLE `peel_codes_promos` ADD `cat_not_apply_code_promo` TEXT NOT NULL;

-- Ajout de filtre pour appliquer un code promo à un choix de produit seulement.
ALTER TABLE `peel_codes_promos` ADD `product_filter` varchar(255) NOT NULL DEFAULT '';

-- Ajout du commentaires reservé à l'administrateur pour une commande.
ALTER TABLE `peel_commandes` ADD `commentaires_admin` mediumtext NOT NULL;

-- Ajout du paramètrage de l'affichage d'un article dans le bloc \"article à la une\".
ALTER TABLE `peel_articles` ADD `on_rollover` tinyint(1) NOT NULL DEFAULT '0';

-- Ajout du champ on_reseller dans les produits et les articles de contenu pour permettre d'afficher certain contenu en fonction de ce statut d'utilisateur
ALTER TABLE `peel_produits` ADD `on_reseller` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_articles` ADD `on_reseller` tinyint(1) NOT NULL DEFAULT '0';

-- Configuration pour ajouter le produit dans la popup d'ajout au panier
ALTER TABLE `peel_produits` ADD `recommanded_product_on_cart_page` tinyint(1) NOT NULL DEFAULT '0';

-- Ajout de swf dans la liste d'extension générique de fichiers autorisés pour l'upload
UPDATE  peel_configuration SET `string` = '\"jpg\", \"jpeg\", \"gif\", \"png\", \"ico\", \"swf\", \"csv\", \"txt\", \"pdf\", \"zip\"'  WHERE technical_code = 'extensions_valides_any';

-- Ajout de champs obligatoires dans la variable de configuration user_mandatory_fields
UPDATE peel_configuration SET string='\"prenom\" => \"STR_ERR_FIRSTNAME\", \"nom_famille\" => \"STR_ERR_NAME\", \"adresse\" => \"STR_ERR_ADDRESS\", \"code_postal\" => \"STR_ERR_ZIP\", \"ville\" => \"STR_ERR_TOWN\", \"pays\" => \"STR_ERR_COUNTRY\", \"telephone\" => \"STR_ERR_TEL\", \"email\" => \"STR_ERR_EMAIL\", \"pseudo\" => \"STR_ERR_PSEUDO\", \"token\" => \"STR_INVALID_TOKEN\"' WHERE technical_code='user_mandatory_fields';

-- Ajout de configuration
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`) VALUES
('email_format', 'core', 'string', 'html', '', NOW(), 'update7.2', 1),
('show_special_on_content_category', 'core', 'boolean', 'true', '', NOW(), 'update7.2 - Permet d''afficher les articles sur la page d''accueil des rubriques.', '1'),
('insert_article_categories_in_menu', 'core', 'boolean', 'true', '', NOW(), 'update7.2', 1),
('menu_custom_submenus', 'core', 'array', '', '', NOW(), 'update7.2 - Works with menu_custom_urls and menu_custom_titles - Example: \"main_menu_technical_code1\" => \"submenu_technical_code1\", \"main_menu_technical_code2\" => \"submenu_technical_code2\"', 1),
('menu_custom_urls', 'core', 'array', '', '', NOW(), 'update7.2 - Works with menu_custom_titles and menu_custom_submenus - You can create one variable per language to have different URLs - Example: \"technical_code_1\" => \"http://www.test.com/url1\", \"technical_code_2\" => \"http://www.test.com/url2\"', 1),
('menu_custom_titles', 'core', 'array', '', '', NOW(), 'update7.2 - Works with menu_custom_urls and menu_custom_submenus - Example: \"technical_code_1\" => \"STR_MENU_CUSTOM_TEXT_1\", \"technical_code_2\" => \"STR_MENU_CUSTOM_TEXT_2\"', 1),
('module_pensebete', 'core', 'integer', '1', '', NOW(), 'update7.2', 1),
('statut_livraison_picto', 'core', 'array', '', '', NOW(), 'update7.2 - permet d''afficher des icônes cliquables pour changer le statut de livraison depuis la page de liste de commandes. Ce paramètre est un tableau qui prend le statut de livraison en index et l''image en valeur : \"id_statut_livraison\" => \"nom+extension de l''image\". Les images doivent être stockées dans le dossier administrer/images', 1),
('user_job_array', 'core', 'array', '\"leader\" => STR_LEADER, \"manager\" => STR_MANAGER, \"employee\" => STR_EMPLOYEE', '', NOW(), 'update7.2', 1),
('redirect_user_after_login_by_priv', 'core', 'array', '', '', NOW(), 'update7.2 - paramètre contenant le code technique du profil d''utilisateur, dont la liste est consultable sur la page Configuration>Configuration>Profils d''utilisateurs (/modules/profil/administrer/profil.php), et une url complète au choix, interne au site ou externe. Le format de ce paramètre est de type array : \"profil\" => \"url\"', 1),
('site_id_showed_by_default_if_domain_not_found', 'core', 'integer', '1', '', NOW(), 'update7.2 - For multisite : to allow any alias on a hosting to reach the main site - Put 0 if you want to only allow configured domains', 1),
('module_lot', 'core', 'integer', '1', '', NOW(), 'update7.2', 1),
('email_sending_format_default', 'core', 'string', 'html', '', NOW(), 'update7.2', 1),
('display_recommanded_product_on_cart_page', 'core', 'boolean', 'true', '', NOW(), 'update7.2', 1);

-- Ajout du module d'inscription/désincription à la newlsetter et le carrousel des articles à la unes";

$sql_update_array['7.1.4'] .= "
INSERT INTO `peel_modules` (`technical_code`, `location`, `display_mode`, `position`, `etat`) VALUES
('subscribe_newsletter', 'header',  '', 3, 0),
('articles_rollover', 'below_middle',  '', 3, 0);";

if(in_array('fr', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_modules` SET title_fr = 'Inscription newsletter' WHERE `technical_code`='subscribe_newsletter';
UPDATE `peel_modules` SET title_fr = 'Articles à la une' WHERE `technical_code`='articles_rollover';
";
}
if(in_array('en', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_modules` SET title_en = 'Subscribe newsletter' WHERE `technical_code`='subscribe_newsletter';
UPDATE `peel_modules` SET title_en = 'Best articles' WHERE `technical_code`='articles_rollover';
";
}
if(in_array('es', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_modules` SET title_es = 'Suscripción al boletín' WHERE `technical_code`='subscribe_newsletter';
UPDATE `peel_modules` SET title_es = 'Best articles' WHERE `technical_code`='articles_rollover';
";
}
$sql_update_array['7.1.4'] .= "
-- Modification du type des champs id_cat_ (tinyint(1) avant)
ALTER TABLE `peel_utilisateurs` CHANGE `id_cat_1` `id_cat_1` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `peel_utilisateurs` CHANGE `id_cat_2` `id_cat_2` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `peel_utilisateurs` CHANGE `id_cat_3` `id_cat_3` int(11) NOT NULL DEFAULT '0';

-- Utilisation de technical_code pour la gestion des statuts de paiement et de livraison
-- L'id 0 pose problème lors de l'ajout de l'auto increment sur le champ id. Un nombre est ajouté à l'id 0 pour ne pas avoir ce problème, et suffisament élévé pour ne pas avoir de conflit avec les paiements existants.
UPDATE `peel_statut_livraison` SET `id`= `id`+100 WHERE id = 0;
UPDATE `peel_statut_paiement` SET `id`= `id`+100 WHERE id = 0;
UPDATE `peel_commandes` SET `id_statut_paiement`= `id_statut_paiement`+100 WHERE id_statut_paiement = 0;
UPDATE `peel_commandes` SET `id_statut_livraison`= `id_statut_livraison`+100 WHERE id_statut_livraison = 0;
-- Ajout des champs technical code. Pour ces tables des codes techniques ont été ajoutés pour permettre leur manipulations par le code sans pour autant connaitre l'id à l'avance. Ce procédé est plus propore et est rendu indispensable suite au passage du multisite.
ALTER TABLE `peel_statut_paiement` ADD `technical_code` varchar(255) NOT NULL;
ALTER TABLE `peel_statut_livraison` ADD `technical_code` varchar(255) NOT NULL;
ALTER TABLE `peel_types` ADD `technical_code` varchar(255) NOT NULL;
ALTER TABLE `peel_tarifs` CHANGE `type` `type` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `peel_email_template_cat` ADD `technical_code` varchar(255) NOT NULL;

-- Ajout de l'auto increment sur le champ id des tables.
ALTER TABLE `peel_statut_livraison` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ;
ALTER TABLE `peel_statut_paiement` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ;

-- modification des configurations lié aux changements de statuts, pour remplacer les ids par les code techniques.
UPDATE  peel_configuration SET `string` = 'being_checked,completed'  WHERE technical_code = 'payment_status_create_bill' AND `string` = '2,3';
UPDATE  peel_configuration SET `string` = 'pending,being_checked,completed'  WHERE technical_code = 'payment_status_create_bill' AND `string` = '1,2,3';
UPDATE  peel_configuration SET `string` = 'being_checked,completed'  WHERE technical_code = 'payment_status_decrement_stock' AND `string` = '2,3';
UPDATE  peel_configuration SET `string` = 'pending,being_checked,completed'  WHERE technical_code = 'payment_status_decrement_stock' AND `string` = '1,2,3';

-- Mise à jour des enregistrements existants, uniquement valable pour la configuration standard
-- pour les statuts de livraison (l'id 100 pour discussed a été défini plus haut dans ce script)
UPDATE `peel_statut_livraison` SET `technical_code`= \"discussed\" WHERE id = 100;
UPDATE `peel_statut_livraison` SET `technical_code`= \"processing\" WHERE id = 1;
UPDATE `peel_statut_livraison` SET `technical_code`= \"dispatched\" WHERE id = 3;
UPDATE `peel_statut_livraison` SET `technical_code`= \"cancelled\" WHERE id = 6;
UPDATE `peel_statut_livraison` SET `technical_code`= \"waiting_for_supply\" WHERE id = 9;
-- pour les statuts de paiement (l'id 100 pour discussed a été défini plus haut dans ce script)
UPDATE `peel_statut_paiement` SET `technical_code`= \"discussed\" WHERE id = 100;
UPDATE `peel_statut_paiement` SET `technical_code`= \"pending\" WHERE id = 1;
UPDATE `peel_statut_paiement` SET `technical_code`= \"being_checked\" WHERE id = 2;
UPDATE `peel_statut_paiement` SET `technical_code`= \"completed\" WHERE id = 3;
UPDATE `peel_statut_paiement` SET `technical_code`= \"cancelled\" WHERE id = 6;
UPDATE `peel_statut_paiement` SET `technical_code`= \"refunded\" WHERE id = 9;

-- ajout des valeurs dans le champ code technique pour peel_zones
UPDATE `peel_zones` SET `technical_code`= \"france_mainland\" WHERE id = 1;
UPDATE `peel_zones` SET `technical_code`= \"france_and_overseas\" WHERE id = 2;
UPDATE `peel_zones` SET `technical_code`= \"europe\" WHERE id = 3;
UPDATE `peel_zones` SET `technical_code`= \"world\" WHERE id = 4;
-- ajout  des valeurs dans le champ code technique pour peel_types
UPDATE `peel_types` SET `technical_code`= \"colissimo_without_signature\" WHERE id = 1;
UPDATE `peel_types` SET `technical_code`= \"colissimo_expert_international\" WHERE id = 2;
UPDATE `peel_types` SET `technical_code`= \"chronopost\" WHERE id = 3;
UPDATE `peel_types` SET `technical_code`= \"pickup\" WHERE id = 4;
UPDATE `peel_types` SET `technical_code`= \"ups\" WHERE id = 5;
UPDATE `peel_types` SET `technical_code`= \"dhl\" WHERE id = 6;
UPDATE `peel_types` SET `technical_code`= \"fedex\" WHERE id = 7;

-- Si module premium : Le champ valeur est utilisé par la fonction insere_langue pour mettre à jour le contenu du champ nom, à la place de l'id
";
if(file_exists($GLOBALS['dirroot'] . '/modules/annonces')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_etatstock` SET `valeur`= \"2\" WHERE id = 0;
";
}
$sql_update_array['7.1.4'] .= "

-- ajout  des valeurs dans le champ code technique pour peel_types
UPDATE `peel_email_template_cat` SET `technical_code`= \"automatic_sending\" WHERE id = 1;
UPDATE `peel_email_template_cat` SET `technical_code`= \"various\" WHERE id = 2;
UPDATE `peel_email_template_cat` SET `technical_code`= \"product\" WHERE id = 3;
UPDATE `peel_email_template_cat` SET `technical_code`= \"sales\" WHERE id = 4;
UPDATE `peel_email_template_cat` SET `technical_code`= \"site_and_clients_information\" WHERE id = 5;
UPDATE `peel_email_template_cat` SET `technical_code`= \"payment_modes\" WHERE id = 6;
UPDATE `peel_email_template_cat` SET `technical_code`= \"newsletter\" WHERE id = 7;

-- Ajout de / derrière le tag [WWWROOT] suite à la suppression de / en dur dans le code lors du remplacement automatique du tag.
UPDATE `peel_email_template` SET `text` = REPLACE(`text`, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_email_template` SET `text` = REPLACE(`text`, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_html` SET `contenu_html` = REPLACE(`contenu_html`, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_html` SET `contenu_html` = REPLACE(`contenu_html`, '[wwwroot]', '[wwwroot]/');

--
-- MODIFICATIONS MULTILINGUES. Choisir les lignes à exécuter qui correspondent aux langues installées sur votre site.
--

";
if(in_array('fr', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "
-- FR
-- Ajout de / derrière le tag [WWWROOT] suite à la suppression de / en dur dans le code lors du remplacement automatique du tag.
UPDATE `peel_produits` SET descriptif_fr = REPLACE(descriptif_fr, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET descriptif_fr = REPLACE(descriptif_fr, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_produits` SET description_fr = REPLACE(description_fr, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET description_fr = REPLACE(description_fr, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_rubriques` SET description_fr = REPLACE(description_fr, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_rubriques` SET description_fr = REPLACE(description_fr, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET texte_fr = REPLACE(texte_fr, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET texte_fr = REPLACE(texte_fr, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET chapo_fr = REPLACE(chapo_fr, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET chapo_fr = REPLACE(chapo_fr, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_categories` SET description_fr = REPLACE(description_fr, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_categories` SET description_fr = REPLACE(description_fr, '[wwwroot]', '[wwwroot]/');
-- Correction de titre d'email.
UPDATE `peel_email_template` SET subject = \"Confirmation de la commande n°[ORDER_ID]\" WHERE subject = \"Confirmation de la commande [ORDER_ID]\";
UPDATE `peel_email_template` SET subject = \"Enregistrement de la commande n°[ORDER_ID] sur [SITE]\" WHERE subject = \"[ORDER_ID] Enregistrement de la commande [SITE]\";
UPDATE `peel_email_template` SET name = \"Confirmation de la commande n°[ORDER_ID]\" WHERE name = \"Confirmation de la commande [ORDER_ID]\";
UPDATE `peel_email_template` SET name = \"Enregistrement de la commande n°[ORDER_ID] sur [SITE]\" WHERE name = \"[ORDER_ID] Enregistrement de la commande [SITE]\";
-- Ajout du champ sentence_displayed_on_product
ALTER TABLE `peel_categories` ADD `sentence_displayed_on_product_fr` varchar(255) NOT NULL DEFAULT '';
-- Modification du drapeaux de la langues
UPDATE `peel_langues` SET flag = '/images/fr.png' WHERE flag='fr.gif';
";
}
if(in_array('en', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "
-- EN
-- Ajout de / derrière le tag [WWWROOT] suite à la suppression de / en dur dans le code lors du remplacement automatique du tag.
UPDATE `peel_produits` SET descriptif_en = REPLACE(descriptif_en, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET descriptif_en = REPLACE(descriptif_en, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_produits` SET description_en = REPLACE(description_en, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET description_en = REPLACE(description_en, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_rubriques` SET description_en = REPLACE(description_en, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_rubriques` SET description_en = REPLACE(description_en, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET texte_en = REPLACE(texte_en, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET texte_en = REPLACE(texte_en, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET chapo_en = REPLACE(chapo_en, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET chapo_en = REPLACE(chapo_en, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_categories` SET description_en = REPLACE(description_en, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_categories` SET description_en = REPLACE(description_en, '[wwwroot]', '[wwwroot]/');
-- Correction de titre d'email.
UPDATE `peel_email_template` SET subject = \"Order confirmation #[ORDER_ID] on [SITE]\" WHERE subject = \"Order confirmation #[ORDER_ID]\";
UPDATE `peel_email_template` SET name = \"Order confirmation #[ORDER_ID] on [SITE]\" WHERE name = \"Order confirmation #[ORDER_ID]\";
-- Ajout du champ sentence_displayed_on_product
ALTER TABLE `peel_categories` ADD `sentence_displayed_on_product_en` varchar(255) NOT NULL DEFAULT '';
-- Modification du drapeaux de la langues
UPDATE `peel_langues` SET flag = '/images/en.png' WHERE flag='uk.gif';

";
}
if(in_array('de', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "

-- DE
-- Ajout de / derrière le tag [WWWROOT] suite à la suppression de / en dur dans le code lors du remplacement automatique du tag.
UPDATE `peel_produits` SET descriptif_de = REPLACE(descriptif_de, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET descriptif_de = REPLACE(descriptif_de, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_produits` SET description_de = REPLACE(description_de, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET description_de = REPLACE(description_de, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_rubriques` SET description_de = REPLACE(description_de, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_rubriques` SET description_de = REPLACE(description_de, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET texte_de = REPLACE(texte_de, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET texte_de = REPLACE(texte_de, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET chapo_de = REPLACE(chapo_de, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET chapo_de = REPLACE(chapo_de, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_categories` SET description_de = REPLACE(description_de, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_categories` SET description_de = REPLACE(description_de, '[wwwroot]', '[wwwroot]/');
-- Correction de titre d'email
UPDATE `peel_email_template` SET subject = \"Bestellbestätigung Nr.[ORDER_ID]\" WHERE subject = \"Bestellbestätigung [ORDER_ID]\";
UPDATE `peel_email_template` SET name = \"Bestellbestätigung Nr.[ORDER_ID]\" WHERE name = \"Bestellbestätigung [ORDER_ID]\";
-- Ajout du champ sentence_displayed_on_product
ALTER TABLE `peel_categories` ADD `sentence_displayed_on_product_de` varchar(255) NOT NULL DEFAULT '';
-- Modification du drapeaux de la langues
UPDATE `peel_langues` SET flag = '/images/de.png' WHERE flag='de.gif';
";
}
if(in_array('es', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "

-- ES
-- Ajout de / derrière le tag [WWWROOT] suite à la suppression de / en dur dans le code lors du remplacement automatique du tag.
UPDATE `peel_produits` SET descriptif_es = REPLACE(descriptif_es, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET descriptif_es = REPLACE(descriptif_es, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_produits` SET description_es = REPLACE(description_es, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET description_es = REPLACE(description_es, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_rubriques` SET description_es = REPLACE(description_es, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_rubriques` SET description_es = REPLACE(description_es, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET texte_es = REPLACE(texte_es, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET texte_es = REPLACE(texte_es, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET chapo_es = REPLACE(chapo_es, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET chapo_es = REPLACE(chapo_es, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_categories` SET description_es = REPLACE(description_es, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_categories` SET description_es = REPLACE(description_es, '[wwwroot]', '[wwwroot]/');
-- Ajout du champ sentence_displayed_on_product
ALTER TABLE `peel_categories` ADD `sentence_displayed_on_product_es` varchar(255) NOT NULL DEFAULT '';
-- Modification du drapeaux de la langues
UPDATE `peel_langues` SET flag = '/images/es.png' WHERE flag='es.gif';
";
}
if(in_array('ro', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "


-- RO
-- Ajout de / derrière le tag [WWWROOT] suite à la suppression de / en dur dans le code lors du remplacement automatique du tag.
UPDATE `peel_produits` SET descriptif_ro = REPLACE(descriptif_ro, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET descriptif_ro = REPLACE(descriptif_ro, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_produits` SET description_ro = REPLACE(description_ro, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET description_ro = REPLACE(description_ro, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_rubriques` SET description_ro = REPLACE(description_ro, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_rubriques` SET description_ro = REPLACE(description_ro, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET texte_ro = REPLACE(texte_ro, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET texte_ro = REPLACE(texte_ro, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET chapo_ro = REPLACE(chapo_ro, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET chapo_ro = REPLAce(chapo_ro, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_categories` SET description_ro = REPLACE(description_ro, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_categories` SET description_ro = REPLAce(description_ro, '[wwwroot]', '[wwwroot]/');
-- Correction de titre d'email.
UPDATE `peel_email_template` SET subject = \"Înregistrare de la commanda [ORDER_ID] pe [SITE]\" WHERE subject = \"[ORDER_ID] Înregistrare de la commanda [SITE]\";
UPDATE `peel_email_template` SET name = \"Înregistrare de la commanda [ORDER_ID] pe [SITE]\" WHERE name = \"[ORDER_ID] Înregistrare de la commanda [SITE]\";
-- Ajout du champ sentence_displayed_on_product
ALTER TABLE `peel_categories` ADD `sentence_displayed_on_product_ro` varchar(255) NOT NULL DEFAULT '';
-- Modification du drapeaux de la langues
UPDATE `peel_langues` SET flag = '/images/ro.png' WHERE flag='ro.gif';
";
}
if(in_array('nl', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "


-- NL
-- Ajout de / derrière le tag [WWWROOT] suite à la suppression de / en dur dans le code lors du remplacement automatique du tag.
UPDATE `peel_produits` SET descriptif_nl = REPLACE(descriptif_nl, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET descriptif_nl = REPLACE(descriptif_nl, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_produits` SET description_nl = REPLACE(description_nl, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET description_nl = REPLACE(description_nl, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_rubriques` SET description_nl = REPLACE(description_nl, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_rubriques` SET description_nl = REPLACE(description_nl, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET texte_nl = REPLACE(texte_nl, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET texte_nl = REPLACE(texte_nl, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET chapo_nl = REPLACE(chapo_nl, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET chapo_nl = REPLACE(chapo_nl, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_categories` SET description_nl = REPLACE(description_nl, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_categories` SET description_nl = REPLACE(description_nl, '[wwwroot]', '[wwwroot]/');
-- Ajout du champ sentence_displayed_on_product
ALTER TABLE `peel_categories` ADD `sentence_displayed_on_product_nl` varchar(255) NOT NULL DEFAULT '';
-- Modification du drapeaux de la langues
UPDATE `peel_langues` SET flag = '/images/nl.png' WHERE flag='nl.gif';
";
}
if(in_array('pt', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "


-- PT
-- Ajout de / derrière le tag [WWWROOT] suite à la suppression de / en dur dans le code lors du remplacement automatique du tag.
UPDATE `peel_produits` SET descriptif_pt = REPLACE(descriptif_pt, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET descriptif_pt = REPLACE(descriptif_pt, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_produits` SET description_pt = REPLACE(description_pt, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET description_pt = REPLACE(description_pt, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_rubriques` SET description_pt = REPLACE(description_pt, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_rubriques` SET description_pt = REPLACE(description_pt, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET texte_pt = REPLACE(texte_pt, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET texte_pt = REPLACE(texte_pt, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET chapo_pt = REPLACE(chapo_pt, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET chapo_pt = REPLACE(chapo_pt, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_categories` SET description_pt = REPLACE(description_pt, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_categories` SET description_pt = REPLACE(description_pt, '[wwwroot]', '[wwwroot]/');
-- Ajout du champ sentence_displayed_on_product
ALTER TABLE `peel_categories` ADD `sentence_displayed_on_product_pt` varchar(255) NOT NULL DEFAULT '';
-- Modification du drapeaux de la langues
UPDATE `peel_langues` SET flag = '/images/pt.png' WHERE flag='pt.gif';
";
}
if(in_array('ru', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "


-- RU
-- Ajout de / derrière le tag [WWWROOT] suite à la suppression de / en dur dans le code lors du remplacement automatique du tag.
UPDATE `peel_produits` SET descriptif_ru = REPLACE(descriptif_ru, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET descriptif_ru = REPLACE(descriptif_ru, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_produits` SET description_ru = REPLACE(description_ru, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET description_ru = REPLACE(description_ru, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_rubriques` SET description_ru = REPLACE(description_ru, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_rubriques` SET description_ru = REPLACE(description_ru, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET texte_ru = REPLACE(texte_ru, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET texte_ru = REPLACE(texte_ru, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET chapo_ru = REPLACE(chapo_ru, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET chapo_ru = REPLACE(chapo_ru, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_categories` SET description_ru = REPLACE(description_ru, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_categories` SET description_ru = REPLACE(description_ru, '[wwwroot]', '[wwwroot]/');
-- Ajout du champ sentence_displayed_on_product
ALTER TABLE `peel_categories` ADD `sentence_displayed_on_product_ru` varchar(255) NOT NULL DEFAULT '';
-- Modification du drapeaux de la langues
UPDATE `peel_langues` SET flag = '/images/ru.png' WHERE flag='ru.gif';
";
}
if(in_array('eo', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['7.1.4'] .= "
-- EO
-- Ajout de / derrière le tag [WWWROOT] suite à la suppression de / en dur dans le code lors du remplacement automatique du tag.
UPDATE `peel_produits` SET descriptif_eo = REPLACE(descriptif_eo, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET descriptif_eo = REPLACE(descriptif_eo, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_produits` SET description_eo = REPLACE(description_eo, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_produits` SET description_eo = REPLACE(description_eo, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_rubriques` SET description_eo = REPLACE(description_eo, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_rubriques` SET description_eo = REPLACE(description_eo, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET texte_eo = REPLACE(texte_eo, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET texte_eo = REPLACE(texte_eo, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_articles` SET chapo_eo = REPLACE(chapo_eo, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_articles` SET chapo_eo = REPLACE(chapo_eo, '[wwwroot]', '[wwwroot]/');
UPDATE `peel_categories` SET description_eo = REPLACE(description_eo, '[WWWROOT]', '[WWWROOT]/');
UPDATE `peel_categories` SET description_eo = REPLACE(description_eo, '[wwwroot]', '[wwwroot]/');
-- Ajout du champ sentence_displayed_on_product
ALTER TABLE `peel_categories` ADD `sentence_displayed_on_product_eo` varchar(255) NOT NULL DEFAULT '';
-- Modification du drapeaux de la langues
UPDATE `peel_langues` SET flag = '/images/eo.png' WHERE flag='eo.gif';
";
}
$sql_update_array['7.1.4'] .= "
-- Lors du passage d'une version 7.1 ou inférieur vers 7.2, un seul site est concerné, donc tout le contenu et paramètre doivent avoir site_id =1
UPDATE `peel_admins_actions` SET site_id = 1;
UPDATE `peel_commandes` SET site_id = 1;
UPDATE `peel_codes_promos` SET site_id = 1;
UPDATE `peel_articles` SET site_id = 1;
UPDATE `peel_categories` SET site_id = 1;
UPDATE `peel_configuration` SET site_id = 1;
UPDATE `peel_html` SET site_id = 1;
UPDATE `peel_produits` SET site_id = 1;
UPDATE `peel_rubriques` SET site_id = 1;
UPDATE `peel_tarifs` SET site_id = 1;
UPDATE `peel_utilisateurs` SET site_id = 1;
UPDATE `peel_utilisateur_connexions` SET site_id = 1;
UPDATE `peel_zones` SET site_id = 1;
UPDATE `peel_societe` SET site_id = 1;
UPDATE `peel_langues` SET site_id = 1;
UPDATE `peel_devises` SET site_id = 1;
UPDATE `peel_marques` SET site_id = 1;
UPDATE `peel_meta` SET site_id = 1;
UPDATE `peel_cgv` SET site_id = 1;
UPDATE `peel_contacts` SET site_id = 1;
UPDATE `peel_legal` SET site_id = 1;
UPDATE `peel_access_map` SET site_id = 1;
UPDATE `peel_tailles` SET site_id = 1;
UPDATE `peel_couleurs` SET site_id = 1;
UPDATE `peel_banniere` SET site_id = 1;
UPDATE `peel_continents` SET site_id = 1;
UPDATE `peel_ecotaxes` SET site_id = 1;
UPDATE `peel_email_template` SET site_id = 1;
UPDATE `peel_email_template_cat` SET site_id = 1;
UPDATE `peel_import_field` SET site_id = 1;
UPDATE `peel_modules` SET site_id = 1;
UPDATE `peel_newsletter` SET site_id = 1;
UPDATE `peel_paiement` SET site_id = 1;
UPDATE `peel_pays` SET site_id = 1;
UPDATE `peel_profil` SET site_id = 1;
UPDATE `peel_statut_livraison` SET site_id = 1;
UPDATE `peel_statut_paiement` SET site_id = 1;
UPDATE `peel_tva` SET site_id = 1;
UPDATE `peel_types` SET site_id = 1;
UPDATE `peel_webmail` SET site_id = 1;
UPDATE `peel_commandes_articles` SET site_id = 1;
-- Module premium
";
if(file_exists($GLOBALS['dirroot'] . '/modules/attributs')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_nom_attributs` SET site_id = 1;
UPDATE `peel_attributs` SET site_id = 1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/faq')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_faq` SET site_id = 1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/groups')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_groupes` SET site_id = 1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/lexique')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_lexique` SET site_id = 1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/lot')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_quantites` SET site_id = 1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/parrainage')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_parrain` SET site_id = 1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/sauvegarde_recherche')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_alertes` SET site_id = 1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/affiliation')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_affiliation` SET site_id = 1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/annonces')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_etatstock` SET site_id = 1;
";
}
$sql_update_array['7.1.4'] .= "
-- Modules à la carte
";
if(file_exists($GLOBALS['dirroot'] . '/modules/carrousel')) {
	$sql_update_array['7.1.4'] .= "
UPDATE `peel_carrousels` SET site_id = 1;
UPDATE `peel_vignettes_carrousels` SET site_id = 1;
";
}
$sql_update_array['7.1.4'] .= "

-- Les administrateurs doivent avoir les droits nécesaire pour administrer les boutiques.
UPDATE peel_utilisateurs SET site_id = 0 WHERE `priv` LIKE 'admin%';";

$sql_update_array['7.1.4'] .= "
-- L'id du type de livraison et de la tva sont incrementés, il faut prévoir int(11)
ALTER TABLE `peel_zones` CHANGE `tva` `tva` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `peel_tarifs` CHANGE `type` `type` int(11) NOT NULL DEFAULT '0';

-- Le numéro d'une commande affiché en front office n'est plus le champ id de la table peel_commandes.
ALTER TABLE `peel_commandes` ADD `order_id` int(11)  NOT NULL DEFAULT '0';
UPDATE `peel_commandes` SET `order_id`=`id`;
-- Le format utilisé pour généré le numéro de facture ne doit plus être l'id technique mais le champ order_id dans peel_commandes
UPDATE  peel_configuration SET `string` = '[order_id]'  WHERE technical_code = 'format_numero_facture' AND `string` = '[id]';
-- Modification du type pour minify_id_increment sinon le système ne trouve pas la variable lors d'une MAJ de propriété du site et la recrée
UPDATE  peel_configuration SET `type` = 'integer'  WHERE `type`='string' AND `technical_code` = 'minify_id_increment';
-- recalcul des CSS et JS lors de l'exécution de ce SQL
UPDATE  peel_configuration SET `string` = `string`+1  WHERE `technical_code` = 'minify_id_increment';

-- NOTE POUR L'ADMINISTRATEUR, REMPLISSAGE MANUEL A FAIRE => La valeur de string doit être l'url de votre site, la même valeur que la variable \$GLOBALS['wwwroot'] présente dans le fichier lib/setup/info.inc.php sur votre ancienne version.";
if (!empty($GLOBALS['wwwroot'])) {
	$sql_update_array['7.1.4'] .= "
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) 
VALUES ('wwwroot', 'core', 'string', '" . nohtml_real_escape_string($GLOBALS['wwwroot']) . "', '', NOW(), 'update7.2 - format : http://www.example.com', 1, 1);
";
}
$sql_update_array['7.1.4'] .= "
DROP TABLE IF EXISTS peel_butterflive;
";

$sql_update_array['7.2.0'] = "
-- FAIT APRES VERSION 7.2.0
UPDATE `peel_configuration` SET string = REPLACE(string, ', \"lang\" => \"STR_EMPTY_FIELD\"', '') WHERE technical_code='user_mandatory_fields';
DELETE FROM `peel_profil` WHERE priv='reve_certif';
";
$sql_update_array['7.2.1'] = "
-- FAIT APRES VERSION 7.2.1
ALTER TABLE `peel_zones` ADD `on_franco_reseller_amount` FLOAT(15,5) NOT NULL DEFAULT '0.00000' AFTER `on_franco_amount`; 
";

if(file_exists($GLOBALS['dirroot'] . '/modules/annonces')) {
	$sql_update_array['7.2.1'] .= "
-- UNIQUEMENT POUR MODULE D'ANNONCES
ALTER TABLE `peel_user_contacts` ADD `temp` DATETIME NOT NULL DEFAULT '00000-00-00 00:00:00';
UPDATE peel_user_contacts SET temp=FROM_UNIXTIME(date);
ALTER TABLE `peel_user_contacts` CHANGE `date` `date` DATETIME NOT NULL DEFAULT '00000-00-00 00:00:00';
UPDATE peel_user_contacts SET date=temp;
ALTER TABLE `peel_user_contacts` DROP temp;
ALTER TABLE `peel_lot_vente` ADD `on_home` tinyint(1) NOT NULL DEFAULT '0';
";
}

if(file_exists($GLOBALS['dirroot'] . '/modules/crons')) {
	$sql_update_array['7.2.1'] .= "
ALTER TABLE `peel_crons` ADD `stopped_at_site_id` int(11) unsigned NOT NULL DEFAULT '0';";
}

$sql_update_array['7.2.1'] .= "
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `peel_utilisateurs` ADD `address_bill_default` VARCHAR( 32 ) NOT NULL DEFAULT '' AFTER `pays`;
ALTER TABLE `peel_utilisateurs` ADD `address_ship_default` VARCHAR( 32 ) NOT NULL DEFAULT '' AFTER `address_bill_default`;
ALTER TABLE `peel_profil` CHANGE `priv` `priv` VARCHAR(255) NOT NULL DEFAULT '';
";

if(file_exists($GLOBALS['dirroot'] . '/modules/cart_preservation')) {
	$sql_update_array['7.2.1'] .= "
ALTER TABLE `peel_save_cart` ADD `products_list_name` varchar(150) NOT NULL DEFAULT '';
ALTER TABLE `peel_save_cart` ADD `saved_products_list_id` int(11) NOT NULL DEFAULT '0';
";
}
$sql_update_array['7.2.1'] .= "
INSERT INTO `peel_configuration` (`technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES 
('skip_home_affiche_compte', 'core', 'boolean', 'true', '', NOW(), '', 1, 1),
('skip_home_register_form', 'core', 'boolean', 'true', '', NOW(), '', 1, 1),
('scroll_to_top', 'core', 'boolean', 'true', '', NOW(), '', 1, 1),
('modules_lang_folders_array', 'modules', 'array', '[forum] => \"/modules/forum/lang/\", [agenda] => \"/modules/agenda/lang/\", [participants] => \"/modules/participants/lang/\", [sauvegarde_recherche] => \"/modules/sauvegarde_recherche/lang/\", [photos_gallery] => \"/modules/photos_gallery/lang/\", [sign_in_twitter] => \"/modules/sign_in_twitter/lang/\", [references] => \"/modules/references/lang/\", [icirelais] => \"/modules/icirelais/lang/\", [exaprint] => \"/modules/exaprint/lang/\", [groups_advanced] => \"/modules/groups_advanced/lang/\", [annonces] => \"/modules/annonces/lang/\", [abonnement] => \"/modules/abonnement/lang/\", [vitrine] => \"/modules/vitrine/lang/\", [affiliation] => \"/modules/affiliation/lang/\", [listecadeau] => \"/modules/listecadeau/lang/\", [blog] => \"/modules/blog/lang/\", [payback] => \"/modules/payback/lang/\", [tnt] => \"/modules/tnt/lang/\", [vatlayer] => \"/modules/vatlayer/lang/\", [telechargement] => \"/modules/telechargement/lang/\", [devis] => \"/modules/devis/lang/\", [exaprint] => \"/modules/exaprint/lang/\", [kiala] => \"/modules/kiala/lang/\"', '', NOW(), '', 1, 0),
('modules_configuration_variable_array', 'modules', 'array', '[affiliation] => \"module_affilie\", [reseller] => \"module_retail\", [gift_check] => \"module_cadeau\", [tagcloud] => \"module_nuage\", [banner] => \"module_pub\", [devises] => \"module_devise\", [parrainage] => \"module_parrain\", [micro_entreprise] => \"module_entreprise\", [facebook_connect] => \"facebook_connect\", [googlefriendconnect] => \"googlefriendconnect\", [sign_in_twitter] => \"sign_in_twitter\"', '', NOW(), '', 1, 0),
('modules_fonctions_variable_array', 'modules', 'array', '[devises] => \"fonctionsdevises\", [sips] => \"fonctionsatos\", [profil] => \"fonctionsprofile\", [good_clients] => \"fonctionsgoodclients\", [facture_advanced] => \"fonctionsgenerepdf\", [statistiques] => \"fonctionsstats\", [welcome_ad] => \"fonctionswelcomead\", [reseller_map] => \"fonctionsresellermap\", [maps] => \"fonctionsmap\", [precedent_suivant] => \"fonctionsprecedentsuivant\", [url_rewriting] => \"rewritefile\", [banner] => \"fonctionsbanner\", [cart_popup] => \"fonctionscartpoup\", [advanced_search] => \"fonctionssearch\", [category_promotion] => \"fonctionscatpromotions\", [marques_promotion] => \"fonctionsmarquepromotions\", [groups_advanced] => \"fonctionsgroupsadvanced\", [parrainage] => \"fonctionsparrain\", [micro_entreprise] => \"fonctionsmicro\", [photos_gallery] => \"fonctionsphotosgallery\", [sign_in_twitter] => \"fonctionssignintwitter\", [phone_cti] => \"fonctionsphonecti\", [exaprint] => \"fonctionsadministrerexaprint\", [payment_by_product] => \"fonctionspaymentbyproduct\", [affiliation] => \"fonctionsaffiliate\", [listecadeau] => \"fonctionsgiftlist\", [gifts] => \"fonctionsgift\", [newsletter] => \"fonctionswanewsletter\", [facebook_connect] => \"fonctionfacebookconnect\", [ariane_panier] => \"fonctionsarianepanier\"', '', NOW(), '', 1, 0),
('modules_no_library_load_array', 'modules', 'array', '\"sips\", \"cmcic\", \"bluepaid\", \"fianet\", \"fianet_sac\", \"ogone\", \"omnikassa\", \"paybox\", \"spplus\", \"systempay\", \"moneybookers\", \"paypal\", \"birthday\", \"good_clients\", \"facture_advanced\", \"statistiques\", \"expeditor\", \"chart\", \"kekoli\", \"reseller_map\", \"photodesk\"', '', NOW(), '', 1, 0),
('modules_front_office_only_array', 'modules', 'array', '\"commerciale\"', '', NOW(), '', 1, 0),
('modules_back_office_only_array', 'modules', 'array', '\"exaprint\"', '', NOW(), '', 1, 0),
('modules_front_office_js_array', 'modules', 'array', '[forum] => \"/modules/forum/forum.js\"', '', NOW(), '', 1, 0),
('modules_no_optional_array', 'modules', 'array', '\"forum\", \"reseller\", \"thumbs\", \"attributs\", \"marques_promotion\", \"category_promotion\", \"devises\", \"ecotaxe\", \"url_rewriting\", \"annonces\", \"abonnement\", \"references\"', '', NOW(), '', 1, 0),
('modules_admin_functions_array', 'modules', 'array', '[tagcloud] => \"/modules/tagcloud/administrer/fonctions.php\", [devises] => \"/modules/devises/administrer/fonctions.php\", [gift_check] => \"/modules/gift_check/administrer/fonctions.php\", [attributs] => \"/modules/attributs/administrer/fonctions.php\", [avis] => \"/modules/avis/administrer/fonctions.php\", [lot] => \"/modules/lot/administrer/fonctions.php\", [annonces] => \"/modules/annonces/administrer/fonctions.php\", [abonnement] => \"/modules/abonnement/administrer/fonctions.php\", [banner] => \"/modules/banner/administrer/fonctions.php\", [vitrine] => \"/modules/vitrine/administrer/fonctions.php\", [lexique] => \"/modules/lexique/administrer/fonctions.php\", [stock_advanced] => \"/modules/stock_advanced/administrer/fonctions.php\", [payment_by_product] => \"/modules/payment_by_product/administrer/fonctions.php\", [download] => \"/modules/download/administrer/fonctions.php\", [affiliation] => \"/modules/affiliation/administrer/fonctions.php\", [partenaires] => \"/modules/partenaires/administrer/fonctions.php\", [parrainage] => \"/modules/parrainage/administrer/fonctions.php\", [webmail] => \"/modules/webmail/administrer/fonctions.php\", [profil] => \"/modules/profil/administrer/fonctions.php\", [telechargement] => \"/modules/telechargement/administrer/fonctions.php\", [faq] => \"/modules/faq/administrer/fonctions.php\", [groups] => \"/modules/groups/administrer/fonctions.php\", [references] => \"/modules/references/administrer/fonctions.php\", [relance_avance] => \"/modules/relance_avance/administrer/fonctions.php\", [comparateur] => \"/modules/comparateur/administrer/fonctions.php\"', '', NOW(), '', 1, 0),
('modules_crons_functions_array', 'modules', 'array', '[annonces] => \"/modules/annonces/administrer/fonctions.php\"', '', NOW(), '', 1, 0),
('modules_front_office_functions_files_array', 'modules', 'array', '[url_rewriting] => \"/modules/url_rewriting/rewrite.php\", [devises] => \"/modules/devises/fonctions.php\", [reseller] => \"/modules/reseller/fonctions.php\", [menus] => \"/modules/menus/fonctions.php\", [best_seller] => \"/modules/best_seller/fonctions.php\", [last_views] => \"/modules/last_views/fonctions.php\", [gift_check] => \"/modules/gift_check/fonctions.php\", [relance_avance] => \"/modules/relance_avance\", [spam] => \"/modules/spam/fonctions.php\", [carrousel] => \"/modules/carrousel/fonctions.php\", [stock_advanced] => \"/modules/stock_advanced/fonctions.php\", [download] => \"/modules/download/fonctions.php\", [facebook] => \"/modules/facebook/fonctions.php\", [facebook_connect] => \"/modules/facebook_connect/fonctions.php\", [sign_in_twitter] => \"/modules/sign_in_twitter/fonctions.php\", [googlefriendconnect] => \"/modules/googlefriendconnect/fonctions.php\", [openid] => \"/modules/openid/fonctions.php\", [cmcic] => \"/modules/cmcic/cmcic.php\", [bluepaid] => \"/modules/bluepaid/fonctions.php\", [fianet_sac] => \"/modules/fianet_sac/fonctions.php\",  [omnikassa] => \"/modules/omnikassa/fonctions.php\", [paybox] => \"/modules/paybox/fonctions.php\", [spplus] => \"/modules/spplus/fonctions.php\", [systempay] => \"/modules/systempay/functions.php\", [moneybookers] => \"/modules/moneybookers/fonctions.php\", [paypal] => \"/modules/paypal/fonctions.php\", [faq] => \"/modules/faq/fonctions.php\", [lexique] => \"/modules/lexique/fonctions.php\", [avis] => \"/modules/avis/fonctions.php\", [comparateur] => \"/modules/comparateur/administrer/fonctions.php\", [profil] => \"/modules/profil/fonctions.php\", [lot] => \"/modules/lot/fonctions.php\", [birthday] => \"/modules/birthday/administrer/bons_anniversaires.php\", [good_clients] => \"/modules/good_clients\", [groups] => \"/modules/groups/fonctions.php\", [facture_advanced] => \"/modules/facture_advanced\", [statistiques] => \"/modules/statistiques\", [expeditor] => \"/modules/expeditor\", [duplicate] => \"/modules/duplicate/administrer/fonctions.php\", [welcome_ad] => \"/modules/welcome_ad/fonctions.php\", [chart] => \"/modules/chart/open-flash-chart.php\", [kekoli] => \"/modules/kekoli/administrer/fonctions.php\", [tnt] => \"/modules/tnt/fonctions.php,/modules/tnt/class/Tnt.php\", [reseller_map] => \"/modules/reseller_map/fonctions.php\", [clients] => \"/modules/clients/fonctions.php\", [photodesk] => \"/modules/photodesk/fonctions.php\", [conditionnement] => \"/modules/conditionnement/fonctions.php\", [commerciale] => \"/modules/commerciale/administrer/fonctions.php\", [webmail] => \"/modules/webmail/fonctions.php\", [agenda] => \"/modules/agenda/fonctions.php\", [sauvegarde_recherche] => \"/modules/sauvegarde_recherche/fonctions.php\", [exaprint] => \"/modules/exaprint/administrer/fonctions.php\", [annonces] => \"/modules/annonces/class/Annonce.php,/modules/annonces/fonctions.php,/modules/annonces/display_annonce.php\", [cart_popup] => \"/modules/cart_popup/fonctions.php\", [tagcloud] => \"/modules/tagcloud/fonctions.php\", [banner] => \"/modules/banner/fonctions.php\", [rss] => \"/modules/rss/fonctions.php\", [pensebete] => \"/modules/pensebete/fonctions.php\", [thumbs] => \"/modules/thumbs/fonctions.php\", [search] => \"/modules/search/fonctions.php\", [attributs] => \"/modules/attributs/fonctions.php\", [marques_promotion] => \"/modules/marques_promotion/fonctions.php\", [category_promotion] => \"/modules/category_promotion/fonctions.php\", [micro_entreprise] => \"/modules/micro_entreprise/fonctions.php\", [gifts] => \"/modules/gifts/fonctions.php\", [precedent_suivant] => \"/modules/precedent_suivant/fonctions.php\", [ariane_panier] => \"/modules/ariane_panier/fonctions.php\", [cart_preservation] => \"/modules/cart_preservation/fonctions.php\", [parrainage] => \"/modules/parrainage/fonctions.php\", [affiliation] => \"/modules/affiliation/fonctions.php\", [ecotaxe] => \"/modules/ecotaxe/fonctions.php\", [devis] => \"/modules/devis/fonctions.php\", [captcha] => \"/modules/captcha/fonctions.php\", [vacances] => \"/modules/vacances/fonctions.php\", [newsletter] => \"/modules/newsletter/peel/fonctions.php\", [direaunami] => \"/modules/direaunami\", [factures] => \"/modules/factures\", [export] => \"/modules/export\", [picking] => \"/modules/picking\", [marges] => \"/modules/marges\", [flash] => \"/modules/flash\", [iphone-ads] => \"/modules/iphone-ads\", [bounces] => \"/modules/bounces\", [vatlayer] => \"/modules/vatlayer/functions.php\", [faq] => \"/modules/faq/fonctions.php\"', '', NOW(), '', 1, 0);
UPDATE `peel_configuration` SET `string`='peel7' WHERE technical_code = 'template_directory';
";

$sql_update_array['8.0.0'] = '';

if(file_exists($GLOBALS['dirroot'] . '/modules/faq')) {
	$sql_update_array['8.0.0'] .= "
ALTER TABLE `peel_faq` ADD `categorie` int(11) unsigned NOT NULL DEFAULT '0', ADD KEY `categorie` (`categorie`);

CREATE TABLE IF NOT EXISTS `peel_faq_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `on_special` tinyint(1) NOT NULL DEFAULT '0',
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
}
// Correction pour certains sites qui par le passé n'étaient pas en configuré standard 1,2,3 ou 2,3 pour certaines variables
$sql_update_array['8.0.1'] = "
UPDATE  peel_configuration SET `string` = 'completed'  WHERE technical_code = 'payment_status_create_bill' AND `string` = '3';
UPDATE  peel_configuration SET `string` = 'completed'  WHERE technical_code = 'payment_status_decrement_stock' AND `string` = '3';
ALTER TABLE `peel_utilisateurs` ADD `parameters` TEXT NOT NULL;
";
if(file_exists($GLOBALS['dirroot'] . '/modules/annonces') || file_exists($GLOBALS['dirroot'] . '/modules/messaging')) {
	$sql_update_array['8.0.1'] .= "
ALTER TABLE `peel_user_contacts` CHANGE `status` `status` ENUM('TRUE','FALSE','FILTERED','NO_EMAIL','READ','SEND','TREATED','TRASH') NOT NULL DEFAULT 'TRUE';
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/partenaires')) {
	$sql_update_array['8.0.1'] .= "
ALTER TABLE `peel_partenaires` ADD `date_insert` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', ADD `date_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
";
}
$sql_update_array['8.0.2'] = "
ALTER TABLE `peel_couleurs` ADD `prix` FLOAT( 15, 5 ) NOT NULL DEFAULT '0.00000', ADD `prix_revendeur` FLOAT( 15, 5 ) NOT NULL DEFAULT '0.00000', ADD `percent` FLOAT( 15, 5 ) NOT NULL DEFAULT '0.00000';
ALTER TABLE `peel_commandes_articles` ADD `prenom_check` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `email_check` , ADD `nom_check` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `prenom_check` ;

ALTER TABLE `peel_utilisateurs` ADD `attributs_list` MEDIUMTEXT NOT NULL;
";
if(file_exists($GLOBALS['dirroot'] . '/modules/avis')) {
	$sql_update_array['8.0.2'] .= "
ALTER TABLE `peel_avis` ADD `date_validation` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `datestamp`;
UPDATE `peel_avis` SET date_validation=datestamp WHERE etat=1;
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/partenaires')) {
	$sql_update_array['8.0.2'] .= "
ALTER TABLE `peel_partenaires_categories` ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0';
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/avis')) {
	$sql_update_array['8.0.2'] .= "
ALTER TABLE `peel_avis` ADD `detail` VARCHAR( 255 ) NOT NULL DEFAULT '';
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/telechargement')) {
	$sql_update_array['8.0.2'] .= "
ALTER TABLE `peel_telechargement` ADD `priv_restriction` VARCHAR(255) NOT NULL DEFAULT '' AFTER `user_restriction`;
";
}
$sql_update_array['8.0.3'] = "";
if(file_exists($GLOBALS['dirroot'] . '/modules/annonces')) {
	$sql_update_array['8.0.3'] .= "
	ALTER TABLE `peel_abus_comment` ADD `type` VARCHAR(255) NOT NULL DEFAULT '';
	ALTER TABLE `peel_gold_ads_to_users`  ADD `creation_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';
	";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/faq')) {
	foreach($GLOBALS['admin_lang_codes'] as $this_lang) {
		$sql_update_array['8.0.3'] .= '
ALTER TABLE `peel_faq_categories` ADD `nom_' . word_real_escape_string($this_lang) . '` VARCHAR( 255 ) NOT NULL DEFAULT "";
ALTER TABLE `peel_faq_categories` ADD `description_' . word_real_escape_string($this_lang) . '` MEDIUMTEXT NOT NULL;
';
	}
}
if(file_exists($GLOBALS['dirroot'] . '/modules/partenaires')) {
	$sql_update_array['8.0.3'] .= "
ALTER TABLE `peel_partenaires` 
  ADD  `description` varchar(255) NOT NULL DEFAULT '',
  ADD `hit` int(11) NOT NULL DEFAULT '0',
  ADD `vue` int(11) NOT NULL DEFAULT '0',
  ADD `site_id` int(11) unsigned NOT NULL DEFAULT '0';
  
CREATE TABLE IF NOT EXISTS `peel_partenaires_clicks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `partner_id` int(11) unsigned NOT NULL DEFAULT '0',
  `page_viewed` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `peel_partenaires_views` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `partner_id` int(11) unsigned NOT NULL DEFAULT '0',
  `page_viewed` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `partner_id` (`partner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/crons')) {
	$sql_update_array['8.0.3'] .= "
ALTER TABLE `peel_crons` ADD `stopped_at_site_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' AFTER `last_exec_result`;
";
}
$sql_update_array['8.0.4'] = "
ALTER TABLE `peel_ecotaxes` ADD `coefficient` float(15,5) NOT NULL DEFAULT '0.00000';
";

$sql_update_array['8.0.4'] .= "
ALTER TABLE `peel_categories` ADD `date_insere` datetime NOT NULL;
ALTER TABLE `peel_categories` ADD `date_maj` datetime NOT NULL;
ALTER TABLE `peel_categories` ADD `allow_show_all_sons_products` tinyint(1) NOT NULL DEFAULT '0';
";

$sql_update_array['8.0.4'] .= "
ALTER TABLE `peel_rubriques` ADD `date_insere` datetime NOT NULL;
ALTER TABLE `peel_rubriques` ADD `date_maj` datetime NOT NULL;
";

if(file_exists($GLOBALS['dirroot'] . '/modules/lot')) {
	$sql_update_array['8.0.4'] .= "
ALTER TABLE `peel_quantites`  ADD `zone_id` int(11) NOT NULL DEFAULT '0';
";
}

if(file_exists($GLOBALS['dirroot'] . '/modules/avis')) {
	$sql_update_array['8.0.4'] .= "
ALTER TABLE `peel_avis`  ADD `item_id` int(11) NOT NULL DEFAULT '0';
";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/annonces')) {
	$sql_update_array['8.0.4'] .= "
ALTER TABLE `peel_lot_vente` ADD `id_adresse` int(11) NOT NULL DEFAULT '0';
ALTER TABLE `peel_lot_vente` ADD INDEX ( `id_adresse` );
";
}
$sql_update_array['8.0.4'] .= "
CREATE TABLE `peel_transactions` (
  `id` int(11) UNSIGNED NOT NULL,
  `reference` varchar(255) NOT NULL,
  `report_id` int(11) UNSIGNED NOT NULL,
  `orders_id` int(11) UNSIGNED NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `reconciliation` enum('','auto','manual') NOT NULL,
  `comment` varchar(255) NOT NULL,
  `type` enum('','cc_report','cc_emitted','paypal_wire','checks','cash','wire','prelevement','reimbursement','fee','pret','sicav','salaire','check','transfer','cmcic','cmcic_by_3','atos','atos_by_3','cetelem','systempay','systempay_3x','spplus','paybox','bluepaid','bluepaid_abonnement','kwixo','kwixo_rnp','kwixo_credit','ogone','postfinance','worldpay','omnikassa','moneybookers','paypal') NOT NULL,
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
  
ALTER TABLE `peel_produits_references` ADD `quantity` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `peel_webmail` ADD `update_datetime` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
";
if(file_exists($GLOBALS['dirroot'] . '/modules/annonces') || file_exists($GLOBALS['dirroot'] . '/modules/messaging')) {
	$sql_update_array['8.0.4'] .= "
ALTER TABLE `peel_user_contacts` CHANGE `status` `status` SET('TRUE','FALSE','FILTERED','NO_EMAIL','READ','SEND','TREATED','TRASH','DELETED') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'TRUE'; 
";
}
$sql_update_array['8.0.5'] = "
ALTER TABLE `peel_devises` ADD `main` tinyint(1) NOT NULL default '0';
ALTER TABLE `peel_categories` ADD `poids` FLOAT(10,2) NOT NULL DEFAULT '0.00000';
ALTER TABLE `peel_utilisateurs` ADD `access_history` TINYINT(1) NOT NULL DEFAULT '1';
ALTER TABLE `peel_produits` ADD `allow_add_product_with_no_stock_in_cart` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_produits` ADD `reference_fournisseur` VARCHAR(100) NOT NULL DEFAULT '' AFTER `reference`;
ALTER TABLE `peel_commandes_articles` ADD `commentaires_admin` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `peel_codes_promos` ADD `promo_code_combinable` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `peel_types` ADD `on_franco_amount` FLOAT(15,5) NOT NULL DEFAULT '0.00000';
ALTER TABLE `peel_zones` ADD `payment_technical_code` VARCHAR(255) NOT NULL DEFAULT '';";

if(file_exists($GLOBALS['dirroot'] . '/modules/banner')) {
	$sql_update_array['8.0.5'] .= "
ALTER TABLE `peel_banniere` ADD `on_background_site` TINYINT(1) NOT NULL DEFAULT '0' AFTER `on_search_engine_page`;";
}
if(file_exists($GLOBALS['dirroot'] . '/modules/conditionnement')) {
	$sql_update_array['8.0.5'] .= "
ALTER TABLE `peel_produits` ADD `conditioning_text` VARCHAR(255) NOT NULL DEFAULT '';";
}

$sql_update_array['8.0.5'] .= "
ALTER TABLE `peel_pays` ADD `prices_decimal_separator` CHAR(1) NOT NULL, ADD `prices_thousands_separator` CHAR(1) NOT NULL AFTER `prices_decimal_separator`;
ALTER TABLE `peel_marques` ADD `date_insere` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `promotion_percent`, ADD `date_maj` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `date_insere`; 
ALTER TABLE `peel_societe` ADD `societe_type` VARCHAR(255) NOT NULL DEFAULT '' AFTER `site_id`, ADD `id_marques` VARCHAR(255) NOT NULL DEFAULT '' AFTER `societe_type`; 
ALTER TABLE `peel_profil` ADD `position` INT(11) NOT NULL DEFAULT '0' AFTER `priv`;
";
foreach($GLOBALS['admin_lang_codes'] as $this_lang) {
	$sql_update_array['8.0.5'] .= "
	ALTER TABLE `peel_categories` ADD `image_header_".$this_lang."` VARCHAR(255) NOT NULL DEFAULT '' AFTER `image_".$this_lang."`;";
	
	
}
if(file_exists($GLOBALS['dirroot'] . '/modules/annonces')) {
	$sql_update_array['8.0.5'] .= "
	INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'website_type', 'core', 'string', 'ad', '', NOW(), '', '1', '1');";
} else {
	$sql_update_array['8.0.5'] .= "
	INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'website_type', 'core', 'string', 'shop', '', NOW(), '', '1', '1');";
}
$sql_update_array['8.0.5'] .= "
	INSERT INTO `peel_configuration` (`id`, `technical_code`, `origin`, `type`, `string`, `lang`, `last_update`, `explain`, `etat`, `site_id`) VALUES (NULL, 'anim_loading_page', 'core', 'integer', '1', '', NOW(), '', '1', '1');
	ALTER TABLE `peel_utilisateurs` ADD `newsletter_validation_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `newsletter`; 
	ALTER TABLE `peel_utilisateurs` ADD `commercial_validation_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `commercial`;

";
if(in_array('fr', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['8.0.5'] .= "
	INSERT INTO `peel_email_template` (`id`, `technical_code`, `name`, `subject`, `text`, `lang`, `active`, `id_cat`, `default_signature_code`, `site_id`) VALUES
	(NULL, 'user_double_optin_registration', 'Validation de l\'inscription sur [SITE]', 'Validation de l\'inscription sur [SITE]', 'Bonjour,\r\n\r\nVous venez de vous inscrire sur [SITE]. Rappel des informations transmises : \r\n[FIELDS]\r\n\r\nVeuillez cliquer sur le lien ci dessous pour activer votre compte : \r\n<a href=\'[VALIDATION_LINK]\'>[VALIDATION_LINK]</a>\r\n', 'fr', 'TRUE', 1, '', 1),
	(NULL, 'confirm_newsletter_registration', 'Inscription à la newsletter / offres commerciales', 'inscription [TYPE] de [SITE]', 'Bonjour,\r\n\r\nVous vous êtes inscrit [TYPE] du site [SITE].\r\nPour confirmer cette inscription veuillez cliquer sur le lien suivant :\r\n<a href=\'[CONFIRM_NEWSLETTER_REGISTER_LINK]\'>[CONFIRM_NEWSLETTER_REGISTER_LINK]</a>', 'fr', 'TRUE', 1, '', 1);";
}

if(in_array('en', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['8.0.5'] .= "
	INSERT INTO `peel_email_template` (`id`, `technical_code`, `name`, `subject`, `text`, `lang`, `active`, `id_cat`, `default_signature_code`, `site_id`) VALUES
	(NULL, 'user_double_optin_registration', 'Validation of the inscription on [SITE]', 'Validation of the inscription on [SITE]', 'Hello,\r\n\r\nYou have just registered on [SITE]. Reminder of the information transmitted:\r\n[FIELDS]\r\n\r\nPlease click on the link below to activate your account:\r\n<a href=\'[VALIDATION_LINK]\'> [VALIDATION_LINK] </a>', 'en', 'TRUE', 1, '', 1),
	(NULL, 'confirm_newsletter_registration', 'newsletter subscription / commercial offers', 'register [TYPE] from [SITE]', 'Hello,\r\nYou registered [TYPE] of the [SITE] site.\r\nTo confirm this registration please click on the following link:\r\n<a href=\'[CONFIRM_NEWSLETTER_REGISTER_LINK]\'>[CONFIRM_NEWSLETTER_REGISTER_LINK]</a>', 'en', 'TRUE', 1, '', 1);";
}

if(in_array('es', $GLOBALS['admin_lang_codes'])) {
	$sql_update_array['8.0.5'] .= "
	INSERT INTO `peel_email_template` (`id`, `technical_code`, `name`, `subject`, `text`, `lang`, `active`, `id_cat`, `default_signature_code`, `site_id`) VALUES
	(NULL, 'confirm_newsletter_registration', 'Registro [TYPE] de [SITE]', 'suscripción al boletín / ofertas comerciales', 'Hola, \r\nSe registró [TYPE] del sitio [SITE]. \r\nPara confirmar este registro, haga clic en el siguiente enlace: \r\n <a href=\'[CONFIRM_NEWSLETTER_REGISTER_LINK]\'> [CONFIRM_NEWSLETTER_REGISTER_LINK] </a>', 'es', 'TRUE', 1, '', 1),
	(NULL, 'user_double_optin_registration', 'Validación de la inscripción en [SITE]', 'Validación de la inscripción en [SITE]', 'Hola, \r\nacaba de registrarse en [SITE].\r\n Recordase de la información transmitida:\r\n [FIELDS].\r\n\r\nHaga clic en el enlace debajo para activar su cuenta:\r\n<a href=\'[VALIDATION_LINK]\'> [VALIDATION_LINK] </a>', 'es', 'TRUE', 1, '', 1);";
}

if(file_exists($GLOBALS['dirroot'] . '/modules/attributs')) {
	$sql_update_array['8.0.5'] .= "
	ALTER TABLE `peel_nom_attributs` ADD `disable_reductions` TINYINT(1) NOT NULL DEFAULT '0';";
}
$sql_update_array['8.0.5'] .= "
	ALTER TABLE `peel_adresses` ADD `longitude` VARCHAR(255) NOT NULL DEFAULT '' AFTER `email`, ADD `latitude` VARCHAR(255) NOT NULL DEFAULT '' AFTER `longitude`; 
	ALTER TABLE `peel_adresses` ADD `address_hash` VARCHAR(2) NOT NULL DEFAULT '' AFTER `latitude`;
";
// FIN du SQL par version
if(!isset($sql_update_array[PEEL_VERSION])) {
	$sql_update_array[PEEL_VERSION] = "";	
}

ksort($sql_update_array);

$output .= '<p>' . 'PEEL PHP' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '. PEEL_VERSION . '</p>';
if (!empty($current_version)) {
	// On a determiné la version de la base de données, donc on peut procéder au différent traitement. Si current_version est vide, c'est qu'on est pas en mesure de définir la version de la bdd, donc on ne fait rien.
	if($current_version == PEEL_VERSION) {
		// OK
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => 'OK'))->fetch();
	} elseif(!empty($_GET['do_update'])) {
		foreach($sql_update_array as $this_version => $this_sql) {
			if($this_version >= $current_version && vn($GLOBALS['site_parameters']['peel_database_version']) < PEEL_VERSION) {
				if($this_version == '7.1.4') {
					// Nouveau : site_id dans peel_utilisateur => il faut MAJ pour l'administrateur loggué
					$_SESSION['session_utilisateur']['site_id'] = 0;
				} elseif($this_version == '8.0.0') {
					// On active les modules pour lesquels aucune variable qui définit son activation n'est trouvée dans la BDD en créant la variable module_xxxx à 1
					load_site_parameters();
					preload_modules();
					foreach($GLOBALS['modules_on_disk'] as $this_module => $folder_path) {
						if(!class_exists(StringMb::ucfirst($this_module)) && !empty($GLOBALS['site_parameters']['modules_front_office_functions_files_array'][$this_module])) {
							// On gère uniquement les modules light préconfigurés - pour les autres il faudra aller dans l'administration gérer la configuration des modules dans sites.php
							// En effet, par défaut plus tard on considèrera que si la variable de configuration module_xxxx pas trouvée, on considère que le module n'est pas activé.
							set_configuration_variable(array('technical_code' => vb($GLOBALS['site_parameters']['modules_configuration_variable_array'][$this_module], 'module_' . $this_module), 'string' => 1, 'type' => 'integer', 'site_id' => 0, 'origin' => 'modules'), false);
						}
					}
				}
				$output .= execute_sql(null, null, true, $GLOBALS['site_id'], 1, $this_sql, false);
				foreach(array_keys($sql_update_array) as $this_version_next) {
					if($this_version_next>$this_version) {
						set_configuration_variable(array('technical_code' => 'peel_database_version', 'string' => $this_version_next, 'type' => 'string', 'site_id' => 0, 'origin' => 'update.php'), true);
						break;
					}
				}
				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_UPDATE_VERSION_OK'], $current_version, $GLOBALS['site_parameters']['peel_database_version'])))->fetch();
				if (!empty($message[$current_version])) {
					$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $message[$current_version]))->fetch();
				}
				$current_version = $GLOBALS['site_parameters']['peel_database_version'];
			}
		}
	} else {
		$output .= '<p class="alert alert-danger center">' . $GLOBALS['STR_ADMIN_UPDATE_VERSION_WARNING'] . '<br /><br /><a class="btn btn-primary btn-warning" href="' . $GLOBALS['administrer_url'] . '/update.php?do_update=1">' . $GLOBALS["STR_ADMIN_UPDATE"] . '</a></p>';
	}
}

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


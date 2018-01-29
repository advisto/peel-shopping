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
// $Id: configuration.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_INSTALLATION', 5);
include("../configuration.inc.php");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_INSTALL_STEP5_TITLE'];
$frm_error = $_GET;
$output = '';
$messages = '';
unset($_SESSION['session_install_finished']);
unset($_SESSION['session_sql_output']);
unset($_SESSION['session_sql_filepos']);

// Pour les remplacement dans la fonction create_or_update_site, on définit le nom du site
$GLOBALS['site'] = $_SESSION['session_install_site_name'];

if (!isset($_SESSION['session_peel_sql'])) {
	$error_msg = execute_sql("peel.sql", null, true);

	$site_data['site_id'] = 1;
	$site_data['enable_jquery'] = 1;
	$messages .= create_or_update_site($site_data, false, 'insere', vb($_SESSION['session_install_langs']));
	$_SESSION['session_peel_sql'] = true;
	$messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_SQL_FILE_EXECUTED']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': installation/peel.sql'))->fetch();
	if(!empty($error_msg)) {
		$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
	}
}
if (!isset($_SESSION['session_peel_sql_premium']) && file_exists("peel_premium.sql")) {
	$error_msg = execute_sql("peel_premium.sql", null, true);
	$_SESSION['session_peel_sql_premium'] = true;
	$messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_SQL_FILE_EXECUTED']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': installation/peel_premium.sql'))->fetch();
	if(!empty($error_msg)) {
		$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
	}
}
// Exécution de l'installation des modules
ob_start();
// Configuration des modules les plus fréquents afin de permettre une compatibilité avec les modules des versions précédant la version 8
$modules_lang_folders_array = array('forum' => '/modules/forum/lang/',
		'agenda' => '/modules/agenda/lang/',
		'participants' => '/modules/participants/lang/',
		'sauvegarde_recherche' => '/modules/sauvegarde_recherche/lang/',
		'photos_gallery' => '/modules/photos_gallery/lang/',
		'sign_in_twitter' => '/modules/sign_in_twitter/lang/',
		'references' => '/modules/references/lang/',
		'icirelais' => '/modules/icirelais/lang/',
		'groups_advanced' => '/modules/groups_advanced/lang/',
		'annonces' => '/modules/annonces/lang/',
		'abonnement' => '/modules/abonnement/lang/',
		'vitrine' => '/modules/vitrine/lang/',
		'affiliation' => '/modules/affiliation/lang/',
		'listecadeau' => '/modules/listecadeau/lang/',
		'blog' =>  '/modules/blog/lang/',
		'payback' => '/modules/payback/lang/',
		'tnt' => '/modules/tnt/lang/',
		'telechargement' => '/modules/telechargement/lang/', // Module de téléchargement
		'vatlayer' => '/modules/vatlayer/lang/', // Module de vérification de numéro de TVA intracommunautaire par API
		'devis' => '/modules/devis/lang/', // Module pour afficher un formulaire de demande de devis en front
		'exaprint' => '/modules/exaprint/lang/', // Module d'impression d'étiquettes pour les livraisons DPD (icirelais)
		'kiala' => '/modules/kiala/lang/', // Module de sélection de point relais Kiala
		'call_back_form' => '/modules/call_back_form/lang/', // Module de demande de rappel téléphonique
		'product_references_by_options' => '/modules/product_references_by_options/lang/', // Module de sélection de référence différentes par déclinaison de produit.
		'ups' => '/modules/ups/lang/',
		);
set_configuration_variable(array('technical_code' => 'modules_lang_folders_array', 'string' => $modules_lang_folders_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_configuration_variable_array = array('affiliation' => 'module_affilie', 'reseller' => 'module_retail', 'gift_check' => 'module_cadeau', 'tagcloud' => 'module_nuage',
		'banner' => 'module_pub', 'devises' => 'module_devise', 'parrainage' => 'module_parrain', 'micro_entreprise' => 'module_entreprise', 
		'facebook_connect' => 'facebook_connect', 'googlefriendconnect' => 'googlefriendconnect', 'sign_in_twitter' => 'sign_in_twitter');
set_configuration_variable(array('technical_code' => 'modules_configuration_variable_array', 'string' => $modules_configuration_variable_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
// Pour compatibilité avec anciennes versions de PEEL, on fait la correspondance avec anciens noms de variable de fonctions
$modules_fonctions_variable_array = array('devises' => 'fonctionsdevises', 'sips' => 'fonctionsatos', 'profil' => 'fonctionsprofile', 
		'good_clients' => 'fonctionsgoodclients', 'facture_advanced' => 'fonctionsgenerepdf', 'statistiques' => 'fonctionsstats', 
		'welcome_ad' => 'fonctionswelcomead', 'reseller_map' => 'fonctionsresellermap', 'maps' => 'fonctionsmap',
		'precedent_suivant' => 'fonctionsprecedentsuivant', 'url_rewriting' => 'rewritefile', 'banner' => 'fonctionsbanner', 'cart_popup' => 'fonctionscartpoup',
		'advanced_search' => 'fonctionssearch', 'category_promotion' => 'fonctionscatpromotions', 'marques_promotion' => 'fonctionsmarquepromotions',
		'good_clients' => 'fonctionsgoodclients', 'groups_advanced' => 'fonctionsgroupsadvanced', 'parrainage' => 'fonctionsparrain',
		'micro_entreprise' => 'fonctionsmicro', 'photos_gallery' => 'fonctionsphotosgallery', 'sign_in_twitter' => 'fonctionssignintwitter',
		'phone_cti' => 'fonctionsphonecti', 'exaprint' => 'fonctionsadministrerexaprint', 'payment_by_product' => 'fonctionspaymentbyproduct',
		'affiliation' => 'fonctionsaffiliate',	'listecadeau' => 'fonctionsgiftlist', 'gifts' => 'fonctionsgift', 'newsletter' => 'fonctionswanewsletter', 
		'facebook_connect' => 'fonctionfacebookconnect', 'ariane_panier' => 'fonctionsarianepanier'
		);
set_configuration_variable(array('technical_code' => 'modules_fonctions_variable_array', 'string' => $modules_fonctions_variable_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_no_library_load_array = array('sips', 'cmcic', 'bluepaid', 'fianet', 'fianet_sac', 'ogone', 'omnikassa', 'paybox', 'spplus', 'systempay', 'moneybookers', 'paypal',
		'birthday', 'good_clients', 'facture_advanced', 'statistiques', 'expeditor', 
		'chart', 'reseller_map', 'photodesk');
set_configuration_variable(array('technical_code' => 'modules_no_library_load_array', 'string' => $modules_no_library_load_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_front_office_only_array = array();
set_configuration_variable(array('technical_code' => 'modules_front_office_only_array', 'string' => $modules_front_office_only_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_back_office_only_array = array('commerciale','exaprint');
set_configuration_variable(array('technical_code' => 'modules_back_office_only_array', 'string' => $modules_back_office_only_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_front_office_js_array = array('forum' => '/modules/forum/forum.js');
set_configuration_variable(array('technical_code' => 'modules_front_office_js_array', 'string' => $modules_front_office_js_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_no_optional_array = array('forum', 'reseller', 'thumbs', 'attributs', 'marques_promotion', 'category_promotion', 'devises', 'ecotaxe', 'url_rewriting', 'annonces', 'abonnement', 'references');
set_configuration_variable(array('technical_code' => 'modules_no_optional_array', 'string' => $modules_no_optional_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_admin_functions_array = array('tagcloud' => '/modules/tagcloud/administrer/fonctions.php',
		'devises' => '/modules/devises/administrer/fonctions.php',
		'gift_check' => '/modules/gift_check/administrer/fonctions.php', 
		'attributs' => '/modules/attributs/administrer/fonctions.php', 
		'avis' => '/modules/avis/administrer/fonctions.php',
		'lot' => '/modules/lot/administrer/fonctions.php',
		'annonces' => '/modules/annonces/administrer/fonctions.php',
		'abonnement' => '/modules/abonnement/administrer/fonctions.php',
		'banner' => '/modules/banner/administrer/fonctions.php',
		'vitrine' => '/modules/vitrine/administrer/fonctions.php',
		'lexique' => '/modules/lexique/administrer/fonctions.php',
		'stock_advanced' => '/modules/stock_advanced/administrer/fonctions.php',
		'payment_by_product' => '/modules/payment_by_product/administrer/fonctions.php',
		'download' => '/modules/download/administrer/fonctions.php',
		'affiliation' => '/modules/affiliation/administrer/fonctions.php',
		'partenaires' => '/modules/partenaires/administrer/fonctions.php',
		'parrainage' => '/modules/parrainage/administrer/fonctions.php',
		'webmail' => '/modules/webmail/administrer/fonctions.php',
		'profil' => '/modules/profil/administrer/fonctions.php', // Module gestion des profils
		'telechargement' => '/modules/telechargement/administrer/fonctions.php', // Module de téléchargement
		'faq' => '/modules/faq/administrer/fonctions.php', // Module de faq
		'groups' => '/modules/groups/administrer/fonctions.php', // Module de groupes
		'references' => '/modules/references/administrer/fonctions.php', // Module de references
		'birthday' => '/modules/birthday/administrer/bons_anniversaires.php', // Module des bons anniversaires
		'comparateur' => '/modules/comparateur/administrer/fonctions.php', // Modules comparateur de prix
		'commerciale' => '/modules/commerciale/administrer/fonctions.php', 
		'duplicate' => '/modules/duplicate/administrer/fonctions.php', // Module de duplication de produit
		'relance_avance' => '/modules/relance_avance/administrer/fonctions.php', 
		'kekoli' => '/modules/kekoli/administrer/fonctions.php', // Module KEKOLI
		'exaprint' => '/modules/exaprint/administrer/fonctions.php',
		'maps' => '/modules/maps/administrer/fonctions.php',
		);
set_configuration_variable(array('technical_code' => 'modules_admin_functions_array', 'string' => $modules_admin_functions_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_crons_functions_array = array('annonces' => '/modules/annonces/administrer/fonctions.php');
set_configuration_variable(array('technical_code' => 'modules_crons_functions_array', 'string' => $modules_crons_functions_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_front_office_functions_files_array = array('url_rewriting' => '/modules/url_rewriting/rewrite.php',
		'devises' => '/modules/devises/fonctions.php',
		'reseller' => '/modules/reseller/fonctions.php', 
		'menus' => '/modules/menus/fonctions.php', // Affichage en page d'accueil des produits à la une
		'best_seller' => '/modules/best_seller/fonctions.php', // Affichage en page d'accueil des produits meilleures ventes, 
		'last_views' => '/modules/last_views/fonctions.php', // Affichage en page d'accueil des produits récemment consultés
		'gift_check' => '/modules/gift_check/fonctions.php', // Fonctions de gestion et utilisation de chèques cadeaux
		'relance_avance' => '/modules/relance_avance', 
		'spam' => '/modules/spam/fonctions.php', 
		'carrousel' => '/modules/carrousel/fonctions.php',
		'stock_advanced' => '/modules/stock_advanced/fonctions.php',
		'download' => '/modules/download/fonctions.php',
		'facebook' => '/modules/facebook/fonctions.php', 
		'facebook_connect' => '/modules/facebook_connect/fonctions.php', // Module de fonctionnalités facebook
		'sign_in_twitter' => '/modules/sign_in_twitter/fonctions.php', // Module sign_in_twitter
		'googlefriendconnect' => '/modules/googlefriendconnect/fonctions.php',
		'openid' => '/modules/openid/fonctions.php', 
		'cmcic' => '/modules/cmcic/cmcic.php',
		'bluepaid' => '/modules/bluepaid/fonctions.php',
		'fianet_sac' => '/modules/fianet_sac/fonctions.php',
		'omnikassa' => '/modules/omnikassa/fonctions.php',
		'paybox' => '/modules/paybox/fonctions.php',
		'spplus' => '/modules/spplus/fonctions.php',
		'systempay' => '/modules/systempay/functions.php',
		'moneybookers' => '/modules/moneybookers/fonctions.php',
		'paypal' => '/modules/paypal/fonctions.php',
		'faq' => '/modules/faq/fonctions.php', 
		'lexique' => '/modules/lexique/fonctions.php',
		'avis' => '/modules/avis/fonctions.php', // Module "donner son avis"
		'profil' => '/modules/profil/fonctions.php', // Module gestion des profils
		'lot' => '/modules/lot/fonctions.php', // Module de gestion des lots
		'birthday' => '/modules/birthday', // Module des bons anniversaires
		'good_clients' => '/modules/good_clients', // Module des bons clients
		'groups' => '/modules/groups/fonctions.php', // Module de gestion des groupes
		'facture_advanced' => '/modules/facture_advanced', // Module de generation de facture pdf
		'statistiques' => '/modules/statistiques', // Module de statistiques
		'expeditor' => '/modules/expeditor', // Module d'interconnexion avec Expeditor
		'duplicate' => '/modules/duplicate', // Module de duplication de produit
		'welcome_ad' => '/modules/welcome_ad/fonctions.php', // Module d'affichage d'interstitiel de publicité à l'arrivée d'un nouvel utilisateur sur le site
		'chart' => '/modules/chart/open-flash-chart.php', // Module de graphiques flash 
		'tnt' => '/modules/tnt/fonctions.php,' . '/modules/tnt/class/Tnt.php',
		'reseller_map' => '/modules/reseller_map/fonctions.php', // Module du google map des revendeurs
		'clients' => '/modules/clients/fonctions.php', // Module Clients
		'photodesk' => '/modules/photodesk/fonctions.php', // Module Photodesk
		'conditionnement' => '/modules/conditionnement/fonctions.php', // Module de gestion de la vente en gros
		'commerciale' => '/modules/commerciale', 
		'webmail' => '/modules/webmail/fonctions.php', 
		'agenda' => '/modules/agenda/fonctions.php',
		'sauvegarde_recherche' => '/modules/sauvegarde_recherche/fonctions.php',
		'crons' => '/modules/crons/functions/emails.php',
		'exaprint' => '/modules/exaprint',
		'annonces' => '/modules/annonces/class/Annonce.php,' . '/modules/annonces/fonctions.php,'. '/modules/annonces/display_annonce.php',
		'cart_popup' => '/modules/cart_popup/fonctions.php', // Module d'affichage de popup lors de l'ajout au caddie
		'abonnement' => '/modules/abonnement/fonctions.php',
		'vitrine' => '/modules/vitrine/fonctions.php',
		'tagcloud' => '/modules/tagcloud/fonctions.php', // Module d'affichage des produits les plus recherchés sous forme de nuage de mots
		'banner' => '/modules/banner/fonctions.php', // Module d'affichage de publicité
		'rss' => '/modules/rss/fonctions.php',
		'pensebete' => '/modules/pensebete/fonctions.php',
		'thumbs' => '/modules/thumbs/fonctions.php',
		'search' => '/modules/search/fonctions.php',
		'attributs' => '/modules/attributs/fonctions.php', 
		'marques_promotion' => '/modules/marques_promotion/fonctions.php',
		'category_promotion' => '/modules/category_promotion/fonctions.php',
		'micro_entreprise' => '/modules/micro_entreprise/fonctions.php',
		'gifts' =>  '/modules/gifts/fonctions.php',
		'precedent_suivant' => '/modules/precedent_suivant/fonctions.php',
		'ariane_panier' => '/modules/ariane_panier/fonctions.php', // Module ariane_panier
		'cart_preservation' => '/modules/cart_preservation/fonctions.php',
		'parrainage' => '/modules/parrainage/fonctions.php',
		'affiliation' => '/modules/affiliation/fonctions.php',
		'ecotaxe' => '/modules/ecotaxe/fonctions.php',
		'devis' => '/modules/devis/fonctions.php',
		'captcha' => '/modules/captcha/fonctions.php',
		'vacances' => '/modules/vacances/fonctions.php', // Module de gestion des vacances administrateur / fournisseurs
		'newsletter' => '/modules/newsletter/peel/fonctions.php',
		'direaunami' => '/modules/direaunami',
		'factures' => '/modules/factures',
		'export' => '/modules/export',
		'picking' => '/modules/picking',
		'marges' => '/modules/marges',
		'flash' => '/modules/flash',
		'iphone-ads' => '/modules/iphone-ads',
		'bounces' => '/modules/bounces',
		'vatlayer' => '/modules/vatlayer/functions.php',
		'counter' => '/modules/counter/functions.php',
		'faq' => '/modules/faq/fonctions.php'
		);
set_configuration_variable(array('technical_code' => 'modules_front_office_functions_files_array', 'string' => $modules_front_office_functions_files_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$error_msg = ob_get_contents();
ob_end_clean();

if(!empty($error_msg)) {
	$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
}

preload_modules();
foreach($GLOBALS['modules_on_disk'] as $this_module => $folder_path) {
	if(class_exists(StringMb::ucfirst($this_module))) {
		// Module complet avec classe permettant de gérer proprement l'installation
		$class_name = StringMb::ucfirst($this_module);
		// La syntaxe $class_name::check_install() n'est pas valide pour PHP<5.3 => on utilise call_user_func_array
		if(!call_user_func_array(array($class_name, 'check_install'), array())) {
			if(!isset($GLOBALS[$class_name])) {
				$GLOBALS[$class_name] = new $class_name();
			}
			$installed = $GLOBALS[$class_name]->install();
			$messages .= $GLOBALS[$class_name]->get_messages();
		}
	} elseif(!empty($modules_front_office_functions_files_array[$this_module])) {
		// On gère uniquement les modules light préconfigurés - pour les autres il faudra aller dans l'administration gérer la configuration des modules dans sites.php
		// On active le module si pas spécifié dans le SQL d'installation du site, en créant la variable module_xxxx à 1
		// En effet, par défaut plus tard on considèrera que si la variable de configuration module_xxxx pas trouvée, on considère que le module n'est pas activé.
		set_configuration_variable(array('technical_code' => vb($GLOBALS['site_parameters']['modules_configuration_variable_array'][$this_module], 'module_' . $this_module), 'string' => 1, 'type' => 'integer', 'site_id' => 0, 'origin' => 'modules'), false);
		// On exécute le SQL pour les modules préconfigurés uniquement
		// Pour les autres modules qui peuvent éventuellement avoir été mis dans le dossier modules/ avant le lancement de l'installation, ils seront gérés par la page de configuration générale sites.php
		foreach(array('peel_' . $this_module . '.sql', '' . $this_module . '.sql') as $this_filename) {
			if (file_exists($folder_path . '/' . $this_filename)) {
				// Exécution du SQL d'installation d'un module
				$error_msg = execute_sql($folder_path . '/' . $this_filename, null, true);
				$messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_SQL_FILE_EXECUTED']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': /' . $this_module . '/' . $this_filename))->fetch();
				if(!empty($error_msg)) {
					$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
				}
			}
		}
	}
}
foreach($_SESSION['session_install_langs'] as $this_lang) {
	// Les langues pour le site ont été créée par la fonction create_or_update_site 
	// On fait une réparation de langue après l'installation des modules.
	// 4ème paramètre à false => On ne gère pas les index dans insere_langue, mais après.
	insere_langue(array('lang' => $this_lang, 'site_id' => 1), true, false, false);
}
if (check_if_module_active('annonces')) {
	// Spécifiquement pour le module d'annonce, on gère l'index search_fulltext une fois que tous les champs par langue ont été créé.
	$index_array= array();
	foreach ($_SESSION['session_install_langs'] as $lng) {
		$index_array[]='titre_'.$lng;
		$index_array[]='description_'.$lng;
	}
	query('ALTER TABLE `peel_lot_vente` ADD FULLTEXT KEY `search_fulltext` ('.implode(',', real_escape_string($index_array)).')');
}
if (!isset($_SESSION['session_peel_sql_website_type']) && !empty($_SESSION['session_install_website_type']) && file_exists($GLOBALS['dirroot']."/lib/sql/peel_".$_SESSION['session_install_website_type'].".sql")) {
	// Exécution du SQL spécifique au type de site installé. On exécute ce code après la configuration des modules pour permettre au fichier de adapter la configuration des modules au type de site.
	$error_msg = execute_sql($GLOBALS['dirroot']."/lib/sql/peel_".$_SESSION['session_install_website_type'].".sql", null, true, 1);
	$_SESSION['session_peel_sql_website_type'] = true;
	set_configuration_variable(array('technical_code' => 'website_type', 'string' => $_SESSION['session_install_website_type'], 'type' => 'string', 'site_id' => 1, 'origin' => 'core'), false);

	if(!empty($error_msg)) {
		$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
	} else {
		$messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_SQL_FILE_EXECUTED']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': lib/sql/peel_'.$_SESSION['session_install_website_type'].'.sql'))->fetch();
	}
}
if (!empty($_SESSION['session_install_website_type']) && !empty($_SESSION['session_install_fill_db']) && file_exists($GLOBALS['dirroot']."/lib/sql/peel_".$_SESSION['session_install_website_type']."_content.sql")) {
	// Exécution de fichier qui contient le contenu, sans le contenu par langue
	$error_msg = execute_sql($GLOBALS['dirroot']."/lib/sql/peel_".$_SESSION['session_install_website_type']."_content.sql", null, true, 1);
	if(!empty($error_msg)) {
		$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
	} else {
		$messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_SQL_FILE_EXECUTED']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': lib/sql/peel_'.$_SESSION['session_install_website_type'].'_content.sql'))->fetch();
	}
	foreach ($_SESSION['session_install_langs'] as $lng) {
		if(file_exists($GLOBALS['dirroot']."/lib/sql/peel_".$_SESSION['session_install_website_type']."_content_".$lng.".sql")) {
			// Exécution des fichiers de contenu dans la langue paramétrée
			$error_msg = execute_sql($GLOBALS['dirroot']."/lib/sql/peel_".$_SESSION['session_install_website_type']."_content_".$lng.".sql", null, true, 1);
			if(!empty($error_msg)) {
				$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
			} else {
				$messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_SQL_FILE_EXECUTED']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': lib/sql/peel_'.$_SESSION['session_install_website_type'].'_content_'.$lng.'.sql'))->fetch();
			}
		}
	}
}
if (file_exists("info.inc.src.php")) {
	$fic = file_get_contents("info.inc.src.php");
} else {
	$messages .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_FILE_MISSING']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': installation/info.inc.src.php'))->fetch();
}
$form_messages = '';
if (!empty($frm_error['error_mail'])) {
	$form_messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_EMAIL']))->fetch();
}
if (!empty($frm_error['error_pseudo'])) {
	$form_messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_PSEUDO']))->fetch();
}
if (!empty($frm_error['error_motdepasse'])) {
	$form_messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_PASSWORD']))->fetch();
}

/*
// Gestion de la récupération des anciennes données de configuration
// A activer si vous voulez utiliser $old_config_file_content dans l'affichage utilisateur
if (file_exists("../lib/setup/info.inc.php")) {
	$old_config_file_content=file_get_contents("../lib/setup/info.inc.src");
}
*/

$fic = preg_replace("/votre_serveur_mysql/", $_SESSION['session_install_serveur'], $fic);
$fic = preg_replace("/votre_utilisateur_mysql/", $_SESSION['session_install_utilisateur'], $fic);
$fic = preg_replace("/votre_motdepasse_mysql/", $_SESSION['session_install_motdepasse'], $fic);
$fic = preg_replace("/bdd_mysql/", $_SESSION['session_install_choixbase'], $fic);

$fp = StringMb::fopen_utf8($GLOBALS['dirroot'] . "/lib/setup/info.inc.php", "wb");
if($fp !== false) {
	fputs($fp, $fic);
	fclose($fp);
} else {
	$form_messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_INSTALL_FILE_NOK'], '/lib/setup/info.inc.php')))->fetch();
}
$tpl = $GLOBALS['tplEngine']->createTemplate('installation_configuration.tpl');
$tpl->assign('step_title', $GLOBALS['DOC_TITLE']);
$tpl->assign('next_step_url', 'fin.php');
$tpl->assign('messages', $messages);
$tpl->assign('form_messages', $form_messages);
$tpl->assign('STR_MANDATORY', $GLOBALS['STR_MANDATORY']);
$tpl->assign('STR_PASSWORD', $GLOBALS['STR_PASSWORD']);
$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
$tpl->assign('STR_CONTINUE', $GLOBALS['STR_CONTINUE']);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_INSTALL_ADMIN_EMAIL', $GLOBALS['STR_ADMIN_INSTALL_ADMIN_EMAIL']);

$output .= $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

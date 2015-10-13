<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: configuration.php 47354 2015-10-12 20:57:13Z sdelaporte $
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
		'exaprint' => '/modules/exaprint/lang/',
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
		'exaprint' => '/modules/exaprint/lang/', // Module pour afficher un formulaire de demande de devis en front
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
$modules_no_library_load_array = array('relance_avance', 'sips', 'cmcic', 'bluepaid', 'fianet', 'fianet_sac', 'ogone', 'omnikassa', 'paybox', 'spplus', 'systempay', 'moneybookers', 'paypal',
		'comparateur', 'birthday', 'good_clients', 'facture_advanced', 'statistiques', 'expeditor', 
		'chart', 'kekoli', 'reseller_map', 'maps', 'photodesk');
set_configuration_variable(array('technical_code' => 'modules_no_library_load_array', 'string' => $modules_no_library_load_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_front_office_only_array = array('commerciale');
set_configuration_variable(array('technical_code' => 'modules_front_office_only_array', 'string' => $modules_front_office_only_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_back_office_only_array = array('exaprint');
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
		'groups' => '/modules/groups/administrer/fonctions.php' // Module de groupes
		);
set_configuration_variable(array('technical_code' => 'modules_admin_functions_array', 'string' => $modules_admin_functions_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_crons_functions_array = array('annonces' => '/modules/annonces/administrer/fonctions.php');
set_configuration_variable(array('technical_code' => 'modules_crons_functions_array', 'string' => $modules_crons_functions_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$modules_front_office_functions_files_array = array('url_rewriting' => '/modules/url_rewriting/rewrite.php',
		'devises' => '/modules/devises/fonctions.php',
		'forum' => '/modules/forum/functions.php', 
		'reseller' => '/modules/reseller/fonctions.php', 
		'menus' => '/modules/menus/fonctions.php', // Affichage en page d'accueil des produits à la une
		'best_seller' => '/modules/best_seller/fonctions.php', // Affichage en page d'accueil des produits meilleures ventes, 
		'last_views' => '/modules/last_views/fonctions.php', // Affichage en page d'accueil des produits récemment consultés
		'gift_check' => '/modules/gift_check/fonctions.php', // Fonctions de gestion et utilisation de chèques cadeaux
		'relance_avance' => '/modules/relance_avance/administrer/fonctions.php', 
		'spam' => '/modules/spam/fonctions.php', 
		'carrousel' => '/modules/carrousel/fonctions.php',
		'stock_advanced' => '/modules/stock_advanced/fonctions.php',
		'payment_by_product' => '/modules/payment_by_product/fonctions.php', // Module de gestion des moyens de payment par produit
		'download' => '/modules/download/fonctions.php',
		'facebook' => '/modules/facebook/fonctions.php', 
		'facebook_connect' => '/modules/facebook_connect/fonctions.php', // Module de fonctionnalités facebook
		'sign_in_twitter' => '/modules/sign_in_twitter/fonctions.php', // Module sign_in_twitter
		'googlefriendconnect' => '/modules/googlefriendconnect/fonctions.php',
		'openid' => '/modules/openid/fonctions.php', 
		'sips' => '/modules/sips/fonctions.php',
		'cmcic' => '/modules/cmcic/cmcic.php',
		'bluepaid' => '/modules/bluepaid/fonctions.php',
		'fianet' => '/modules/fianet/fonctions.php',
		'fianet_sac' => '/modules/fianet_sac/fonctions.php',
		'ogone' => '/modules/ogone/fonctions.php',
		'omnikassa' => '/modules/omnikassa/fonctions.php',
		'paybox' => '/modules/paybox/fonctions.php',
		'spplus' => '/modules/spplus/fonctions.php',
		'systempay' => '/modules/systempay/functions.php',
		'moneybookers' => '/modules/moneybookers/fonctions.php',
		'paypal' => '/modules/paypal/fonctions.php',
		'faq' => '/modules/faq/fonctions.php', 
		'lexique' => '/modules/lexique/fonctions.php',
		'avis' => '/modules/avis/fonctions.php', // Module "donner son avis"
		'comparateur' => '/modules/comparateur/administrer/fonctions.php', // Modules comparateur de prix
		'profil' => '/modules/profil/fonctions.php', // Module gestion des profils
		'lot' => '/modules/lot/fonctions.php', // Module de gestion des lots
		'birthday' => '/modules/birthday/administrer/bons_anniversaires.php', // Module des bons anniversaires
		'good_clients' => '/modules/good_clients', // Module des bons clients
		'groups' => '/modules/groups/fonctions.php', // Module de gestion des groupes
		'facture_advanced' => '/modules/facture_advanced', // Module de generation de facture pdf
		'statistiques' => '/modules/statistiques', // Module de statistiques
		'expeditor' => '/modules/expeditor', // Module d'interconnexion avec Expeditor
		'duplicate' => '/modules/duplicate/administrer/fonctions.php', // Module de duplication de produit
		'welcome_ad' => '/modules/welcome_ad/fonctions.php', // Module d'affichage d'interstitiel de publicité à l'arrivée d'un nouvel utilisateur sur le site
		'chart' => '/modules/chart/open-flash-chart.php', // Module de graphiques flash 
		'kekoli' => '/modules/kekoli/administrer/fonctions.php', // Module KEKOLI
		'tnt' => '/modules/tnt/fonctions.php,' . '/modules/tnt/class/Tnt.php',
		'socolissimo' => '/modules/socolissimo/fonctions.php',
		'icirelais' => '/modules/icirelais/fonctions.php',
		'telechargement' => '/modules/telechargement/fonctions.php', // Module de téléchargement
		'partenaires' => '/modules/partenaires/fonctions.php', // Module de gestion des partenaires
		'reseller_map' => '/modules/reseller_map/fonctions.php', // Module du google map des revendeurs
		'maps' => '/modules/maps/fonctions.php', // Module de map
		'clients' => '/modules/clients/fonctions.php', // Module Clients
		'photodesk' => '/modules/photodesk/fonctions.php', // Module Photodesk
		'conditionnement' => '/modules/conditionnement/fonctions.php', // Module de gestion de la vente en gros
		'commerciale' => '/modules/commerciale/administrer/fonctions.php', 
		'webmail' => '/modules/webmail/fonctions.php', 
		'agenda' => '/modules/agenda/fonctions.php',
		'participants' => '/modules/participants/fonctions.php',
		'sauvegarde_recherche' => '/modules/sauvegarde_recherche/fonctions.php',
		'crons' => '/modules/crons/functions/emails.php',
		'photos_gallery' => '/modules/photos_gallery/fonctions.php',
		'references' => '/modules/references/fonctions.php',
		'exaprint' => '/modules/exaprint/administrer/fonctions.php',
		'abonnement' => '/modules/abonnement/fonctions.php',
		'annonces' => '/modules/annonces/class/Annonce.php,' . '/modules/annonces/fonctions.php,'. '/modules/annonces/display_annonce.php',
		'vitrine' => '/modules/vitrine/fonctions.php',
		'cart_popup' => '/modules/cart_popup/fonctions.php', // Module d'affichage de popup lors de l'ajout au caddie
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
		'listecadeau' => '/modules/listecadeau/fonctions.php',
		'gifts' =>  '/modules/gifts/fonctions.php',
		'blog' => '/modules/blog/fonctions.php',
		'payback' => '/modules/payback/fonctions.php',
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
		'groups_advanced' => '/modules/groups_advanced/fonctions.php', 
		'direaunami' => '/modules/direaunami',
		'factures' => '/modules/factures',
		'export' => '/modules/export',
		'picking' => '/modules/picking',
		'marges' => '/modules/marges',
		'flash' => '/modules/flash',
		'iphone-ads' => '/modules/iphone-ads',
		'bounces' => '/modules/bounces',
		'vatlayer' => '/modules/vatlayer/functions.php',
		'faq' => '/modules/faq/fonctions.php'
		);
set_configuration_variable(array('technical_code' => 'modules_front_office_functions_files_array', 'string' => $modules_front_office_functions_files_array, 'type' => 'array', 'site_id' => 0, 'origin' => 'modules'), true);
$error_msg = ob_get_contents();
ob_end_clean();

if(!empty($error_msg)) {
	$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
}

	
$modules_dir = $GLOBALS['dirroot'] . "/modules";
if ($handle = opendir($modules_dir)) {
	while ($this_module = readdir($handle)) {
		if ($this_module != "." && $this_module != ".." && is_dir($modules_dir . '/' . $this_module)) {
			if(class_exists(String::ucfirst($this_module))) {
				// Module complet avec classe permettant de gérer proprement l'installation
				$class_name = String::ucfirst($this_module);
				if($install_or_uninstall != $class_name::check_install()) {
					if(!isset($GLOBALS[$class_name])) {
						$GLOBALS[$class_name] = new $class_name();
					}
					if($install_or_uninstall) {
						$installed = $GLOBALS[$class_name]->install();
					} else {
						$uninstalled = $GLOBALS[$class_name]->uninstall();
					}
					$messages .= $GLOBALS[$class_name]->get_messages();
				}
			} elseif(!empty($modules_front_office_functions_files_array[$this_module])) {
				// On active le module si pas spécifié dans le SQL d'installation du site, en créant la variable module_xxxx à 1
				// En effet, par défaut si la variable de configuration module_xxxx pas trouvée, on considère que le module n'est pas activé.
				set_configuration_variable(array('technical_code' => vb($GLOBALS['site_parameters']['modules_configuration_variable_array'][$this_module], 'module_' . $this_module), 'string' => 1, 'type' => 'integer', 'site_id' => 0, 'origin' => 'modules'), false);
				// On exécute le SQL pour les modules préconfigurés uniquement
				// Pour les autres modules qui peuvent éventuellement avoir été mis dans le dossier modules/ avant le lancement de l'installation, ils seront gérés par la page de configuration générale sites.php
				foreach(array('peel_' . $this_module . '.sql', '' . $this_module . '.sql') as $this_filename) {
					if (file_exists($modules_dir . '/' . $this_module . '/' . $this_filename)) {
						// Exécution du SQL d'installation d'un module
						$error_msg = execute_sql($modules_dir . '/' . $this_module . '/' . $this_filename, null, true);
						$messages .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_INSTALL_SQL_FILE_EXECUTED']. $GLOBALS['STR_BEFORE_TWO_POINTS'].': /' . $this_module . '/' . $this_filename))->fetch();
						if(!empty($error_msg)) {
							$messages .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
						}
					}
				}
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

$fp = String::fopen_utf8($GLOBALS['dirroot'] . "/lib/setup/info.inc.php", "wb");
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

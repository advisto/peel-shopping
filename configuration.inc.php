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
// $Id: configuration.inc.php 55428 2017-12-07 16:06:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	define('IN_PEEL', true);
} else {
	return;
}
// Toutes les configurations de base qui sont à modifier lorsqu'on change d'hébergement
// sont stockées dans /lib/setup/info.inc.php
// Le présent fichier de configuration est standard et n'a pas besoin d'être modifié.
// Néanmoins vous pouvez modifier des valeurs ci-dessous si vous maîtrisez les implications sur l'ensemble du code
if (version_compare(PHP_VERSION, '5.4', '>=')) {
	// On veut de la compatibilité avec la formalisation des objets qui est compatible PHP4 mais ne passe pas en strict dans PHP 5
	// Dans PHP>=5.4, E_STRICT est incorporé dans E_ALL, on l'exclut donc ici
	@error_reporting(E_ALL & ~E_STRICT);
} else {
	@error_reporting(E_ALL);
}
if (version_compare(PHP_VERSION, '5.1.2', '<')) {
	// Si PHP5 n'est pas présent sur le serveur, on affiche un message d'erreur et on s'arrête
	// NB : Si on allait plus loin, le chargement du moteur de template n'étant pas compatible PHP5, ça mettrait des erreurs diverses à la place
	echo '<div>PHP ' . PHP_VERSION . ' < 5.1.2 => check .htaccess or your server configuration to enable PHP >= 5.2</div>';
	die();
}

// Pour fonctionner correctement en UTF8, il faut que l'extension mbstring de PHP soit activée sur le serveur.
// Si elle ne l'est pas, la classe String fait appel aux fonctions non compatibles multibyte à la place,
// ce qui génèrerait des dysfonctionnement sur les chaines de caractères avec caractères non ANSI
// ---
// ATTENTION : GENERAL_ENCODING ne peut être changé que par des développeurs avertis
// Si on veut implémenter l'encodage il y a aussi à changer :
// - la déclaration default charset dans le .htaccess à la racine
// - le format de stockage à changer en BDD
// - l'encodage des fichiers PHP (qui sont par défaut depuis PEEL 6.0 en UTF8 sans BOM)
define('PEEL_VERSION', '9.0.0');
if (!defined('IN_CRON')) {
	define('GENERAL_ENCODING', 'utf-8'); // En minuscules. ATTENTION : Seulement pour développeurs avertis
}
if (!defined('IN_INSTALLATION')) {
	define('IN_INSTALLATION', false);
}
// Pour éviter l'emission des erreurs pour faciliter la migration, on désactive l'affichage des messages d'erreur. Plus bas dans le code on refait un calcul selon l'ip pour savoir si on affiche les erreurs ou non.
define('DISPLAY_ERRORS_DURING_INIT', 0);
$GLOBALS['dirroot'] = dirname(__FILE__);
$GLOBALS['repertoire_achat'] = $GLOBALS['dirroot'] . "/achat";
$GLOBALS['libdir'] = $GLOBALS['dirroot'] . "/lib";
$GLOBALS['invoicedir'] = $GLOBALS['dirroot'] . "/invoice";
$GLOBALS['uploaddir'] = $GLOBALS['dirroot'] . "/upload";

include($GLOBALS['dirroot'] . "/lib/fonctions/fonctions.php");
handle_php_default_setup();
handle_register_globals();

$GLOBALS['display_errors'] = 0; // Initialisation de la variable de gestion des erreurs
$GLOBALS['script_start_time'] = array_sum(explode(' ', microtime()));
$GLOBALS['notification_output_array'] = array();

// ***********************************
// * DEBUT CONFIGURATION PAR DEFAUT  *
// Les valeurs ci-dessous sont ensuite remplacées après l'installation par les valeurs contenues dans la table peel_configuration
// Si vous voulez imposer ce paramètre après l'installation, mettez vos lignes dans la section plus bas appelée FORCE SITE_PARAMETERS
$GLOBALS['site_parameters']['mysql_extension'] = 'mysqli'; // Mettre "mysqli" (par défaut, à laisser dans 99% des cas) ou "mysql". Si mysqli n'est pas disponible, mysql sera utilisé à la place
$GLOBALS['site_parameters']['backoffice_directory_name'] = 'administrer'; // VOIR DANS FORCE SITE_PARAMETERS
$GLOBALS['site_parameters']['cache_folder'] = 'cache';
$GLOBALS['site_parameters']['css'] = 'screen.css';
$GLOBALS['site_parameters']['sha256_encoding_salt'] = "k)I8#;z=TIxnXmIPdW2TRzt4Ov89|#V~cU@]";
$GLOBALS['site_parameters']['id'] = '1';
$GLOBALS['site_parameters']['complete_lang_files'] = array('fr', 'en', 'es');
$GLOBALS['site_parameters']['display_warning_if_connection_problem'] = true;
$GLOBALS['site_parameters']['session_cookie_basename'] = 'sid'; // Valeur par défaut pour que l'installation ait bien le même nom de session que par la suite
$GLOBALS['site_parameters']['bootstrap_enabled'] = true;
$GLOBALS['site_parameters']['only_show_products_with_picture_in_containers'] = true;

// Ci-dessous :
// Valeurs par défaut de variables servant dans le processus installation car nécessaires dans les fichiers de langues 
// => les valeurs n'ont pas d'importance car pas utilisées, mais elles doivent être définies
$GLOBALS['site_parameters']['quotation_delay'] = null;
$GLOBALS['site_parameters']['avoir'] = null;
$GLOBALS['site_parameters']['commission_affilie'] = null;
// * FIN CONFIGURATION PAR DEFAUT *
// ***********************************
if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli' && !class_exists('mysqli')) {
	$GLOBALS['site_parameters']['mysql_extension'] = 'mysql';
}

$GLOBALS['ip_for_debug_mode'] = '';
foreach(explode(',', str_replace(array(' ', ';'), array(',', ','), $GLOBALS['ip_for_debug_mode'])) as $this_ip_part) {
	if (!empty($this_ip_part) && ($this_ip_part == '*' || strpos($_SERVER['REMOTE_ADDR'], $this_ip_part) === 0)) {
		define('PEEL_DEBUG', true);
		define('DEBUG_TEMPLATES', true);
		$GLOBALS['display_errors'] = 1;
		break;
	}
}

// Compatibilité serveur web IIS (sans gestion d'URL rewriting)
if (!isset($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
	if (!empty($_SERVER['QUERY_STRING'])) {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}
// Fin compatibilité IIS
if (empty($_SERVER["HTTP_HOST"])) {
	// Gestion d'un appel en local sur le serveur ou en cas de bug du serveur web
	$_SERVER["HTTP_HOST"] = '127.0.0.1';
}
// On détecte l'URL de base du site pour l'installation uniquement
// Si $GLOBALS['wwwroot'] est précisé dans /lib/setup/info.inc.php, alors il aura la priorité
$GLOBALS['apparent_folder'] = get_apparent_folder();
$GLOBALS['apparent_folder_main'] = $GLOBALS['apparent_folder'];
if (substr($GLOBALS['apparent_folder'], 0, 1) != '/') {
	// Protection contre des requêtes de hackers du type GET http://xxxxx/  qui ne commencent anormalement pas par / et qui pourraient permettre d'inclure l'URL dans wwwroot
	$GLOBALS['apparent_folder'] = '/' . $GLOBALS['apparent_folder'];
}

require($GLOBALS['dirroot'] . "/lib/fonctions/database.php");

if (!IN_INSTALLATION && is_dir($GLOBALS['dirroot'] . '/installation')) {
	// Le site est configuré mais a toujours le répertoire d'installation présent
	$GLOBALS['installation_folder_active'] = true;
} elseif (!IN_INSTALLATION) {
	// Chargement des variables de connexion à la BDD et de la configuration wwwroot
	require($GLOBALS['dirroot'] . "/lib/setup/info.inc.php");
}
if (!empty($_SERVER["HTTP_HOST"])) {
	$GLOBALS['detected_wwwroot'] = 'http://' . $_SERVER["HTTP_HOST"] . substr($GLOBALS['apparent_folder'], 0, strlen($GLOBALS['apparent_folder']) - 1);
	if (empty($GLOBALS['wwwroot']) || substr($GLOBALS['wwwroot'], 0, 4) !== 'http') {
		// Si wwwroot n'est pas défini dans lib/setup/info.inc.php (par exemple pour fonctionnement multisite)
		// ou si wwwroot ne semble pas valable, on utilise la valeur détectée automatiquement
		$GLOBALS['wwwroot'] = $GLOBALS['detected_wwwroot'];
	}
} elseif(!defined('IN_CRON')) {
	// Si HTTP_HOST pas défini (problème de configuration du serveur a priori - Vu sur serveur avec appel https://xxxx:443/ : le fait de préciser port fait que HTTP_HOST pas défini par Apache)
	$GLOBALS['detected_wwwroot'] = $GLOBALS['wwwroot'];
	$temp_array = explode('/', $GLOBALS['wwwroot']);
	// On récupère HTTP_HOST après le second / et avant un troisième
	if(!empty($temp_array[2])) {
		$_SERVER["HTTP_HOST"] = $temp_array[2];
	} else {
		$_SERVER["HTTP_HOST"] = '';
	}
}
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === '443')) {
	$GLOBALS['detected_wwwroot'] = str_replace('http://', 'https://', $GLOBALS['detected_wwwroot']);
	$GLOBALS['wwwroot'] = str_replace('http://', 'https://', $GLOBALS['wwwroot']);
	// @ini_set('session.cookie_secure', '1');
}
if(!empty($GLOBALS['wwwroot'])) {
	$GLOBALS['wwwroot_main'] = $GLOBALS['wwwroot'];
} else {
	$GLOBALS['wwwroot_main'] = '';
}

/*
 * Déclaration des objets et des fonctions
 *
 */
require($GLOBALS['dirroot'] . "/lib/class/Module.php");
require($GLOBALS['dirroot'] . "/lib/class/Cache.php");
require($GLOBALS['dirroot'] . "/lib/class/StringMb.php");
require($GLOBALS['dirroot'] . "/lib/class/Caddie.php");
require($GLOBALS['dirroot'] . "/lib/class/FormError.php");
require($GLOBALS['dirroot'] . "/lib/class/Multipage.php");
require($GLOBALS['dirroot'] . "/lib/class/Product.php");
require($GLOBALS['dirroot'] . "/lib/class/SHA256.php");
require($GLOBALS['dirroot'] . "/lib/fonctions/images.php");
require($GLOBALS['dirroot'] . "/lib/fonctions/order.php");
require($GLOBALS['dirroot'] . "/lib/fonctions/emails.php");
require($GLOBALS['dirroot'] . "/lib/fonctions/user.php");
require($GLOBALS['dirroot'] . "/lib/fonctions/format.php");

if (!IN_INSTALLATION) {
	if (empty($GLOBALS['installation_folder_active'])) {
		db_connect($GLOBALS['database_object']);
		if (!$GLOBALS['database_object']) {
			die('database_object is null');
		}
		if(!defined('IN_CRON')) {
			// Les fichiers de crons sont utilisés en dehors de la logique de multisite. Si des crons spécifiques ont besoin d'utiliser des ressources multisites (templates d'email par exemple), le fichier de cron contiendra la configuration de site et de langue, mais ce n'est pas nécessaire pour les cas standards.
			// On remplit $GLOBALS['site_parameters'] qui sera ensuite surchargé pour la langue choisie par set_lang_configuration_and_texts()
			load_site_parameters();
		} else {
			// Approche pragmatique, on charge par défaut la configuration pour site_id = 1 pour l'exécution d'un cron. Si le fichier de cron à exécuter s'applique sur d'autres sites, la fonction load_site_parameters sera à nouveau appelé à l'intérieur de ce fichier de cron avec $GLOBALS['site_id'] qui convient.
			load_site_parameters(null, false, 1);
		}
		// A ce stade : $GLOBALS['site_id'] est détecté
		$parameters_loaded = true;
	}
} else {
	// Pour l'installation
	if(empty($GLOBALS['site_id'])) {
		$GLOBALS['site_id'] = 1;
	}
	// On ne définit que le dossier modeles/ car dans l'installation on ne va pas chercher la configuration en BDD
	load_site_parameters();
}
if ((empty($GLOBALS['site_parameters']['peel_database_version']) && empty($GLOBALS['installation_folder_active'])) || (!empty($GLOBALS['site_parameters']['peel_database_version']) && $GLOBALS['site_parameters']['peel_database_version'] != PEEL_VERSION)) {
	// Contexte de migration
	$GLOBALS['database_wrong_version'] = true;
	if(empty($GLOBALS['site_parameters']['peel_database_version'])) {
		// Ancienne version, on désactive le test sur les champs site_id des tables
		$GLOBALS['site_parameters']['multisite_disable'] = true;
	}
}
// ***********************************
// * FORCER DES VALEURS DE SITE_PARAMETERS *
// Le fonctionnement normal est l'utilisation de variables de configurations stockés dans la table peel_configuration et éditable dans l'administration (section Variables de configuration)
// Si néanmoins vous voulez forcer en PHP des paramètres site_parameters en priorité sur la table peel_configuration, vous pouvez les imposer ci-après.
// Pär exemple retirez les // devant la ligne suivante et remplacez adminfolder par votre valeur :
// $GLOBALS['site_parameters']['backoffice_directory_name'] = 'adminfolder';
// $GLOBALS['site_parameters']['site_suspended'] = true;
// * FIN SITE_PARAMETERS *
// ***********************************
if (!isset($GLOBALS['site_parameters']['display_errors_for_ips']) && empty($GLOBALS['database_wrong_version'])) {
	$GLOBALS['display_errors'] = 1;
} elseif (empty($GLOBALS['database_wrong_version']) && !empty($GLOBALS['site_parameters']['display_errors_for_ips'])) {
	// On ne veut pas afficher  les erreurs dans le contexte d'une migration
	foreach(explode(',', str_replace(array(' ', ';'), array(',', ','), $GLOBALS['site_parameters']['display_errors_for_ips'])) as $this_ip_part) {
		if (!empty($this_ip_part) && ($this_ip_part == '*' || strpos($_SERVER['REMOTE_ADDR'], $this_ip_part) === 0)) {
			// IP utilisée détectée comme commençant par une IP listée dans display_errors_for_ips
			$GLOBALS['display_errors'] = 1;
			break;
		}
	}
}
if (!empty($GLOBALS['site_parameters']['default_socket_timeout'])) {
	@ini_set('default_socket_timeout', $GLOBALS['site_parameters']['default_socket_timeout']);
}
@ini_set('display_errors', $GLOBALS['display_errors']);

if (!empty($GLOBALS['site_parameters']['enable_gzhandler'])) {
	ob_start('ob_gzhandler');
}
$GLOBALS['modules_installed'] = array();
require($GLOBALS['dirroot'] . '/lib/fonctions/modules_handler.php');
// Module d'URL Rewriting et gestion des modules de sites qui peuvent contenir des définitions d'URL
// A gérer avant la gestion des langues ci-après

// Chargement de modules complémentaires
if (!empty($GLOBALS['site_parameters']['load_site_specific_files_before_others'])) {
	foreach($GLOBALS['site_parameters']['load_site_specific_files_before_others'] as $this_file_relative_path) {
		if(file_exists($GLOBALS['dirroot'] . $this_file_relative_path)) {
			include($GLOBALS['dirroot'] . $this_file_relative_path);
			if(StringMb::strpos($this_file_relative_path, '/modules/') !==false) {
				$temp = StringMb::substr($this_file_relative_path, StringMb::strpos($this_file_relative_path, '/modules/')+1);
				$temp2 = explode('/', $temp);
			}
			$GLOBALS['modules_installed'][$temp2[1]] = $temp2[1];
			$GLOBALS['modules_loaded_functions'][] = $this_file_relative_path;
		}
	}
}

// Démarrage de la session PHP
handle_sessions();

// Nettoyage des données et suppressions des magic_quotes si nécessaire
// NB : ne peut pas être mis dans une fonction si $$xxxx ne renvoie pas le tableau superglobal dans toutes les versions de PHP
// NB2 : doit être fait APRES l'ouverture de la session car ça retire le HTML des données si on n'est pas administrateur
foreach(array('_POST', '_GET', '_COOKIE', '_REQUEST') as $this_global_array) {
	if (function_exists('array_walk_recursive')) {
		// PHP 5+ uniquement
		array_walk_recursive($$this_global_array, 'cleanDataDeep');
	} else {
		$$this_global_array = array_map('cleanDataDeep', $$this_global_array);
	}
}
	
// Nom pour le cookie qui contiendra les produits du panier. Le nom du cookie est différent pour chaque installation de PEEL.
// Le cookie sera initialisé dans la fonction update de la classe Caddie, uniquement si la variable de configuration save_caddie_in_cookie === true.
$GLOBALS['caddie_cookie_name'] = vb($GLOBALS['site_parameters']['caddie_cookie_name']) . substr(md5($GLOBALS['wwwroot_main']), 0, 8);

$GLOBALS['google_pub_count'] = 0;

if ((((!empty($_GET['update']) && $_GET['update'] == 1) || (!empty($_GET['update_thumbs']) && $_GET['update_thumbs'] == 1)) && (!est_identifie() || !a_priv("admin*", true) || is_user_bot())) || ((!empty($_GET['devise']) || !empty($_GET['nombre'])) && is_user_bot())) {
	// Page de MAJ du cache : les moteurs ne doivent pas pouvoir activer ou référencer ces pages => redirection 301
	redirect_and_die(get_current_url(true, false, array('update', 'update_thumbs', 'devise', 'nombre', 'multipage')), true);
}

if (IN_INSTALLATION >= 4 && empty($_SESSION['session_install_finished'])) {
	if(!empty($_SESSION['session_install_utilisateur'])) {
		// Pour l'installation, la connexion à la BDD ne peut avoir lieu qu'après avoir chargé les sesssions
		$GLOBALS['serveur_mysql'] = $_SESSION['session_install_serveur'];
		$GLOBALS['utilisateur_mysql'] = $_SESSION['session_install_utilisateur'];
		$GLOBALS['mot_de_passe_mysql'] = $_SESSION['session_install_motdepasse'];
	}
	db_connect($GLOBALS['database_object'], false);
	if (!$GLOBALS['database_object']) {
		redirect_and_die("bdd.php?err=1");
	}
	if (IN_INSTALLATION >= 5) {
		if(!empty($_SESSION['session_install_choixbase'])) {
			$GLOBALS['nom_de_la_base'] = $_SESSION['session_install_choixbase'];
		}
		if (!select_db(vb($GLOBALS['nom_de_la_base']), $GLOBALS['database_object'], true)) {
			redirect_and_die("choixbase.php?err=1");
		}
	}
}
// NB: The module has to be loaded even if LOAD_NO_OPTIONAL_MODULE is defined
load_modules('url_rewriting');
require($GLOBALS['dirroot'] . "/lib/fonctions/url_standard.php");

$GLOBALS['repertoire_modele'] = $GLOBALS['dirroot'] . "/modeles/" . vb($GLOBALS['site_parameters']['template_directory']);

$GLOBALS['lang_codes'] = array(); // Variable globale récupérant les codes Langue
$GLOBALS['admin_lang_codes'] = array(); // Variable globale récupérant les codes Langue des langues administrables (actives, ou désactivées mais administrables : pastille orange)
$GLOBALS['lang_flags'] = array(); // Variable globale récupérant l'URL des drapeaux de langues
$GLOBALS['lang_names'] = array(); // Variable globale récupérant le nom de la langue dans sa propre langue
$GLOBALS['langs_flags_correspondance'] = array(); // Possibilité de mettre correspondance entre langue et drapeau de pays si aucune image de langue n'existe. Par exemple : 'en'=>'uk.gif'
$GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'] = null;
// We get the activated languages
load_active_languages_list(vb($GLOBALS['site_id']));

if(defined('IN_PEEL_ADMIN') || IN_INSTALLATION) {
	// Chargement des fonctions d'administration
	include($GLOBALS['dirroot'] . "/lib/fonctions/fonctions_admin.php");
	$GLOBALS['load_admin_lang'] = true;
}

// Si nécessaire dans get_identified_lang, on redirige si langue pas définie
$_SESSION['session_langue'] = get_identified_lang((defined('IN_PEEL_ADMIN')?$GLOBALS['admin_lang_codes']:$GLOBALS['lang_codes']));
if(empty($_SESSION['session_langue'])) {
	if(empty($GLOBALS['admin_lang_codes']) && defined('IN_PEEL_ADMIN')) {
		redirect_and_die(get_url('/'));
	} else {
		// Protection en cas de problème majeur
		die();
	}
}

// On est maintenant sûr que la langue est correctement définie et que le fichier de langue associé existe
// Dans la ligne suivante, on modifie notamment $GLOBALS['site'] et $GLOBALS['wwwroot'], $GLOBALS['wwwroot_in_admin'] et $GLOBALS['administrer_url']
// On charge les fichiers de langue de base, on va gérer les modules ensuite
if(!in_array($_SESSION['session_langue'], $GLOBALS['site_parameters']['complete_lang_files'])) {
	// Si la langue utilisée n'est pas une langue principale du logiciel, on considère que le fichiers de langue peuvent être incomplets
	// => on charge la langue anglaise par défaut avant la langue spécifiée => si il manque une chaine de caractères, c'est la version anglaise qui sera présente
	$GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$_SESSION['session_langue']] = array('en');
}
if(!defined('IN_CRON')) {
	// Les fichiers de crons sont utilisés en dehors de la logique de multisite. Si des crons spécifiques ont besoin d'utiliser des ressources multisites (templates d'email par exemple), le fichier de cron contiendra la configuration de site et de langue, mais ce n'est pas nécessaire pour les cas standards.
	// Si SKIP_LANG est défini, seules les variables globales seront définies
	// La fonction suivante va définir $GLOBALS['wwwroot'], wwwroot_in_admin, et toutes les autres variables globales générales qui peuvent être affectées par l'URL rewriting
	set_lang_configuration_and_texts($_SESSION['session_langue'], vb($GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$_SESSION['session_langue']]), true, false, !empty($GLOBALS['load_admin_lang']), true, defined('SKIP_SET_LANG'));
	// A ce stade : $GLOBALS['wwwroot'] contient l'URL du domaine avec le sous-domaine de la langue si défini, ou autre domaine
}
$_SESSION['session_langue'] = check_language($_SESSION['session_langue'], (defined('IN_PEEL_ADMIN')?$GLOBALS['admin_lang_codes']:$GLOBALS['lang_codes']));

if (!IN_INSTALLATION && empty($GLOBALS['installation_folder_active'])) {
	if (empty($_POST) && !defined('IN_CRON') && !defined('IN_IPN')) {
		// IN_IPN : On ne souhaite pas avoir de redirection du http vers https dans le cas d'un appel à un fichier IPN. Sinon la redirection fait que les informations en POST sont perdus, et la mise à jour automatique des statuts ne fonctionne pas. Dans le cadre d'IPN, avoir une belle URL n'apporte rien, donc on peut passer ce bloc de code.
		if (StringMb::strpos(StringMb::strtolower(StringMb::rawurldecode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])), str_replace(array('http://', 'https://'), '', StringMb::strtolower(StringMb::rawurldecode($GLOBALS['wwwroot'])))) === false && StringMb::strpos(str_replace(array('http://', 'https://'), '', StringMb::strtolower(StringMb::rawurldecode($GLOBALS['wwwroot']))), StringMb::strtolower(StringMb::rawurldecode($_SERVER['HTTP_HOST']))) !== false && StringMb::strpos(StringMb::strtolower(StringMb::rawurldecode(get_url('/'))), StringMb::strtolower(StringMb::rawurldecode($GLOBALS['apparent_folder']))) !== false) {
			// Dans le cas où un site est accessible via un domaine directement
			// Si on est sur une URL qui ne contient pas wwwroot, mais le domaine est bien contenu dans wwwroot => on veut donc rajouter le sous-domaine
			// NB : Il manque donc un sous-domaine, mais on n'est pas sur une URL alternative (en effet, on fait attention à se trouver uniquement dans des cas "normaux" d'absence de sous-domaine, pas d'autres cas plus complexes de configuration avec plusieurs chemins serveurs)
			// par exemple : wwwroot indique un sous-domaine tel que www, alors que l'URL en cours ne contient pas www => on redirige vers une URL qui respecte la configuration de wwwroot
			redirect_and_die(StringMb::substr($GLOBALS['wwwroot'], 0, StringMb::strlen($GLOBALS['wwwroot']) - StringMb::strlen($GLOBALS['apparent_folder']) + 1) . $_SERVER['REQUEST_URI'], true);
		}
		if (StringMb::strpos(StringMb::strtolower(rawurldecode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])), 'www.' . str_replace(array('http://', 'https://'), '', StringMb::strtolower(rawurldecode($GLOBALS['wwwroot'])))) === 0) {
			// Si on a www. en trop par rapport à ce qui est prévu dans wwwroot, on retire le www.
			redirect_and_die(StringMb::substr($GLOBALS['wwwroot'], 0, StringMb::strlen($GLOBALS['wwwroot']) - StringMb::strlen($GLOBALS['apparent_folder']) + 1) . $_SERVER['REQUEST_URI'], true);
		}
		// redirections éventuelles si la langue est définie sans cohérence avec ce qui est configuré dans peel_langues
		if (StringMb::substr($_SERVER['REQUEST_URI'], StringMb::strlen($GLOBALS['apparent_folder']) - 1, 4) == '/' . $_SESSION['session_langue'] . '/' && empty($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']])) {
			// Exemple : on redirige domaine.com/en vers domaine.com/
			redirect_and_die($GLOBALS['wwwroot'] . StringMb::substr($_SERVER['REQUEST_URI'], 3 + StringMb::strlen($GLOBALS['apparent_folder']) - 1), true);
		}
		if (!empty($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']]) && strpos($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']], '//') !== false) {
			if ($GLOBALS['detected_wwwroot'] != $GLOBALS['wwwroot'] && $GLOBALS['detected_wwwroot'] == str_replace(array('www.'), array($_SESSION['session_langue'] . '.'), $GLOBALS['wwwroot_main'])) {
				// Exemple : on redirige en.domaine.com vers domaine-specifique-pour-langue-en.com
				redirect_and_die($GLOBALS['wwwroot'] . StringMb::substr($_SERVER['REQUEST_URI'], StringMb::strlen($GLOBALS['apparent_folder']) - 1), true);
			}
			if (StringMb::substr($_SERVER['REQUEST_URI'], StringMb::strlen($GLOBALS['apparent_folder']) - 1, 4) == '/' . $_SESSION['session_langue'] . '/') {
				// Exemple : on redirige domaine.com/en vers domaine-specifique-pour-langue-en.com
				redirect_and_die($GLOBALS['wwwroot'] . StringMb::substr($_SERVER['REQUEST_URI'], 3 + StringMb::strlen($GLOBALS['apparent_folder']) - 1), true);
			}
			if (StringMb::substr_count($GLOBALS['wwwroot'], '/') == 2 && StringMb::strpos(StringMb::strtolower(StringMb::rawurldecode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])), str_replace(array('http://', 'https://'), '', StringMb::strtolower(StringMb::rawurldecode($GLOBALS['wwwroot'])))) === false && StringMb::strpos(str_replace(array('http://', 'https://'), '', StringMb::strtolower(StringMb::rawurldecode($GLOBALS['wwwroot']))), StringMb::strtolower(StringMb::rawurldecode($_SERVER['HTTP_HOST']))) !== false) {
				// Dans le cas où un site est accesible via un domaine directement (pas via un répertoire) :
				// Si on est sur une URL qui ne contient pas wwwroot, mais le domaine est bien contenu dans wwwroot => on veut donc rajouter le sous-domaine
				// NB : Il manque donc un sous-domaine, mais on n'est pas sur une URL alternative (en effet, on fait attention à se trouver uniquement dans des cas "normaux" d'absence de sous-domaine, pas d'autres cas plsu complexes de configuration avec plusieurs chemins serveurs)
				// par exemple : wwwroot indique un sous-domaine tel que www, alors que l'URL en cours ne contient pas www => on redirige vers une URL qui respecte la configuration de wwwroot
				redirect_and_die($GLOBALS['wwwroot'] . StringMb::substr($_SERVER['REQUEST_URI'], StringMb::strlen($GLOBALS['apparent_folder']) - 1), true);
			}
		}
		handle_setup_redirections(get_current_url());
	}
	// Détermine les variables en fonction du site
	if (vb($GLOBALS['site_parameters']['admin_force_ssl'])) {
		if (defined('IN_PEEL_ADMIN')) {
			// On ne fait pas la redirection sur sites.php pour éviter de bloquer totalement l'administrateur si https ne marche pas
			if (strpos($_SERVER['PHP_SELF'], 'sites.php') === false && strpos($_SERVER['PHP_SELF'], 'ipn.php') === false && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off')) {
				// Attention : on perd les POST si il y en avait, mais on ne veut pas pour des raisons de sécurité exclure le cas où il y aurait des POST
				redirect_and_die(str_replace('http://', 'https://', get_current_url()));
			}
		}
	}
	if (empty($GLOBALS['site'])) {
		// $GLOBALS['site'] est déjà défini si on a exécuté set_lang_configuration_and_texts plus haut
		$GLOBALS['site'] = vb($GLOBALS['site_parameters']['nom_' . $_SESSION['session_langue']]);
	}
	if (empty($GLOBALS['site'])) {
		$GLOBALS['site'] = $GLOBALS['wwwroot'];
	}
	$GLOBALS['support'] = vb($GLOBALS['site_parameters']['email_webmaster']);
	// Email support client
	$GLOBALS['support_sav_client'] = vb($GLOBALS['site_parameters']['email_client']);
	if (empty($GLOBALS['support_sav_client'])) {
		$GLOBALS['support_sav_client'] = $GLOBALS['support'];
	}
	// Email envoi des commandes
	$GLOBALS['support_commande'] = vb($GLOBALS['site_parameters']['email_commande']);
	if (empty($GLOBALS['support_commande'])) {
		$GLOBALS['support_commande'] = $GLOBALS['support'];
	}
} else {
	$GLOBALS['site'] = '';
	$GLOBALS['support'] = '';
	$GLOBALS['support_sav_client'] = '';
	$GLOBALS['support_commande'] = '';
	$GLOBALS['repertoire_images'] = '';
}

if (!IN_INSTALLATION) {
	// Module des gestion des devises
	// NB: The module has to be loaded even if LOAD_NO_OPTIONAL_MODULE is defined
	load_modules('devises');
}
// Gestion de la devise de l'utilisateur
if (empty($_SESSION['session_devise']) || empty($_SESSION['session_devise']['code'])) {
	// Initialisation de la devise utilisateur car pas encore définie
	if (check_if_module_active('devises')) {
		// On séléctionne de préférence la devise de référence du site - si pas active, alors on prend la première devise qu'on trouve en tenant compte de la variable position croissante
		set_current_devise(vb($GLOBALS['site_parameters']['code']));
	}
	if (empty($_SESSION['session_devise']) || empty($_SESSION['session_devise']['code'])) {
		if(!empty($GLOBALS['site_parameters']['code'])) {
			// Site sans module de gestion des devises
			$_SESSION['session_devise']['symbole'] = StringMb::html_entity_decode(str_replace('&euro;', '€', vb($GLOBALS['site_parameters']['symbole'])));
			$_SESSION['session_devise']['symbole_place'] = vb($GLOBALS['site_parameters']['symbole_place']);
			$_SESSION['session_devise']['conversion'] = 1;
			$_SESSION['session_devise']['code'] = vb($GLOBALS['site_parameters']['code']);
		} else {
			// Installation
			$_SESSION['session_devise']['code'] = 'EUR';
			$_SESSION['session_devise']['symbole'] = ' €';
			$_SESSION['session_devise']['conversion'] = 1;
			$_SESSION['session_devise']['symbole_place'] = 1;
		}
	}
}

if (!defined('IN_CRON') && !defined('IN_IPN') && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') && strpos($GLOBALS['wwwroot'], 'https://') === 0 && strpos($_SERVER['PHP_SELF'], 'sites.php') === false && strpos($_SERVER['PHP_SELF'], 'ipn.php') === false && strpos($GLOBALS['wwwroot'], $_SERVER['HTTP_HOST']) !== false) {
	// On accède en http et non pas en https à un site explicitement configuré en https
	// Attention : on perd les POST si il y en avait, mais on ne veut pas pour des raisons de sécurité exclure le cas où il y aurait des POST
	// On ne souhaite pas faire la redirection si le nom de domaine utilisé n'est pas le domaine principal. Il faut faire la redirection uniquement si le $_SERVER['HTTP_HOST'] est présent dans wwwroot
	redirect_and_die(str_replace('http://', 'https://', get_current_url()), true);
}

// Si allow_w3c_validator_access_admin est défini à true (ce qui n'est pas le cas par défaut par sécurité), alors le moteur de validation W3C est autorisé pour accéder dans l'administration, en mode démo (limite les risques de faille de sécurité de le mettre en démo)
$GLOBALS['force_demo_rights'] = (vb($GLOBALS['site_parameters']['allow_w3c_validator_access_admin']) && strpos($_SERVER['REMOTE_ADDR'], '128.30.52.') === 0 && substr(vb($_SERVER['HTTP_USER_AGENT']),0, 3) == 'W3C');
if($GLOBALS['force_demo_rights']) {
	// Emulation mode demo pour un utilisateur précis
	$_SESSION['session_utilisateur']['priv'] = 'demo';
	$_SESSION['session_utilisateur']['id_utilisateur'] = 9999999999999999999;
	$_SESSION['session_utilisateur']['email'] = 'demo@demo.fr';
	$_SESSION['session_utilisateur']['prenom'] = 'demo';
	$_SESSION['session_utilisateur']['nom_famille'] = 'demo';
	$_SESSION['session_utilisateur']['pseudo'] = 'demo';
	$_SESSION['session_utilisateur']['site_id'] = '1';
}

// Liste par défaut des privilèges qui ne peuvent pas se connecter à leur compte :
//	- Les revendeurs en attente de validation
//	- Les affiliés en attente de validation
//	- Les inscrits à la newsletter
//	- Les inscrits aux téléchargements
$GLOBALS['disable_login_by_privilege'] = vb($GLOBALS['site_parameters']['disable_login_by_privilege'], array('load', 'newsletter', 'stop', 'stand'));

// Force le login d'un utilisateur précis en fonction de son IP et de son user agent
$GLOBALS['forced_login'] = (!empty($GLOBALS['site_parameters']['allow_forced_login']) && !est_identifie() && StringMb::strpos(vb($_SERVER['REMOTE_ADDR']), $GLOBALS['site_parameters']['allow_forced_login']['ip']) !== false && StringMb::strpos(vb($_SERVER['HTTP_USER_AGENT']), $GLOBALS['site_parameters']['allow_forced_login']['user_agent']) !== false);
if($GLOBALS['forced_login']) {
	user_login_now($GLOBALS['site_parameters']['allow_forced_login']['email'], null, false);
}

handle_template_engine_init((defined('DEBUG_TEMPLATES') && DEBUG_TEMPLATES) || (!empty($_GET['update']) && $_GET['update'] == 1) || (!defined('IN_CRON') && (strpos($GLOBALS['wwwroot'], '://localhost')!==false || strpos($GLOBALS['wwwroot'], '://127.0.0.1')!==false)) || empty($GLOBALS['site_parameters']['smarty_avoid_check_template_files_update']));

// Chargement de librairies - Les librairies de type display sont chargées après display_custom.php
if (!IN_INSTALLATION && empty($GLOBALS['installation_folder_active'])) {
	if (file_exists($GLOBALS['repertoire_modele'] . "/fonctions/display_custom.php")) {
		include($GLOBALS['repertoire_modele'] . "/fonctions/display_custom.php");
	}
}
include($GLOBALS['dirroot'] . "/lib/fonctions/display.php");
include($GLOBALS['dirroot'] . "/lib/fonctions/display_user_forms.php");
include($GLOBALS['dirroot'] . "/lib/fonctions/display_product.php");
include($GLOBALS['dirroot'] . "/lib/fonctions/display_article.php");

handle_site_suspended();

if (!IN_INSTALLATION || IN_INSTALLATION >= 5) {
	if (defined('IN_PEEL_ADMIN')) {
		// Protection de l'administration, qui vient en doublon de vérification en haut de chaque fichier d'admin
		// pour plus de sécurité
		if(!defined('IN_RPC')) {
			// On redirige si problème de sécurité
			necessite_identification();
			necessite_priv("admin*");
		} elseif (!est_identifie() || !a_priv("admin*", true)) {
			// On ne redirige pas, on s'arrête
			die();
		}
		if (!isset($_SESSION['session_admin_multisite']) && isset($GLOBALS['site_id'])) {
			// session_admin_multisite permet de récupérer les données d'un site à administrer. Cette valeur est choisissable par l'admin dans la page administer/index.php.
			$all_sites_name_array = get_all_sites_name_array(false, false, true);
			if(count($all_sites_name_array)>1) {
				$_SESSION['session_admin_multisite'] = $GLOBALS['site_id'];
			} else {
				$_SESSION['session_admin_multisite'] = 0;
			}
		}
		$GLOBALS['disable_google_ads'] = true;
	} elseif(!empty($_GET['email']) || !empty($_POST['email']) || !empty($_GET['mot_passe']) || !empty($_POST['mot_passe']) || !empty($_GET['password_once']) || !empty($_POST['password_once'])) {
		// Adsense : "il est interdit de nous transmettre des informations que nous pourrions identifier comme étant des informations personnelles"
		$GLOBALS['disable_google_ads'] = true;
		$GLOBALS['robots_noindex'] = true;
	}
	// Chargement des modules
	// Des modules optionnels, sont vendus séparément ou dans le package du module Premium
	// Contactez PEEL sur https://www.peel.fr/ ou au 01 75 43 67 97 pour obtenir un de ces modules
	load_modules();
}

if (!defined('SKIP_SET_LANG')) {
	// On charge les fichiers de langue des modules
	set_lang_configuration_and_texts($_SESSION['session_langue'], vb($GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$_SESSION['session_langue']]), false, true, false);
}
if (!defined('LOAD_NO_OPTIONAL_MODULE') && !empty($GLOBALS['site_parameters']['load_site_specific_files_after_others'])) {
	// Fichiers nécessitant que les fonctions d'URL soient toutes définies et les constantes de langue
	foreach($GLOBALS['site_parameters']['load_site_specific_files_after_others'] as $this_file_relative_path) {
		if(file_exists($GLOBALS['dirroot'] . $this_file_relative_path)) {
			include($GLOBALS['dirroot'] . $this_file_relative_path);
			if(StringMb::strpos($this_file_relative_path, '/modules/') !==false) {
				$temp = StringMb::substr($this_file_relative_path, StringMb::strpos($this_file_relative_path, '/modules/')+1);
				$temp2 = explode('/', $temp);
				$GLOBALS['modules_installed'][$temp2[1]] = $temp2[1];
			}
		}
	}
}

if (!empty($GLOBALS['installation_folder_active'])) {
	// Le site est configuré mais a toujours le répertoire d'installation présent
	echo print_delete_installation_folder();
	die();
}
if (!empty($_POST['password_first_hash']) && !empty($_POST['password_length']) && !empty($_POST['email_or_pseudo'])) {
	// Login automatique en POST quand on vient d'une application tierce, avec la première étape de hash appliquée au mot de passe
	$utilisateur = user_login_now($_POST['email_or_pseudo'], $_POST['password_first_hash'], true, true, $_POST['password_length']);
	if ($utilisateur) {
		redirect_and_die(get_current_url(true));
	} else {
		redirect_and_die(get_url('membre'));
	}
}

if (!IN_INSTALLATION) {
	// Initialisation de l'objet caddie si nécessaire
	if (!isset($_SESSION['session_caddie']) || empty($_SESSION['session_caddie'])) {
		$_SESSION['session_caddie'] = new Caddie(get_current_user_promotion_percentage());
	} elseif (!empty($_SESSION['session_caddie']->commande_id)) {
		// Une commande est en cours : on vérifie son statut de paiement et si elle est payée, alors on réinitialise le caddie
		$query_com = query("SELECT c.*, sp.nom_" . $_SESSION['session_langue'] . " AS statut_paiement
			FROM peel_commandes c
			LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
			WHERE c.id ='" . intval($_SESSION['session_caddie']->commande_id) . "' AND c.id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND " . get_filter_site_cond('commandes', 'c') . "");
		$result_com = fetch_object($query_com);
		if ($result_com && in_array($result_com->statut_paiement, array('being_checked', 'completed'))) {
			$_SESSION['session_caddie']->init();
		}
	}
	// Initialisation de la session session_ariane_panier
	if (!isset($_SESSION['session_ariane_panier']) || empty($_SESSION['session_ariane_panier'])) {
		$_SESSION['session_ariane_panier'] = array('in_caddie' => false, 'in_step1' => false, 'in_step2' => false, 'in_step3' => false);
	}
	// Suppression de la session session_redirect_after_login si un utilisateur sort de la page membre.php après une redirection sans s'être connecté
	if ((!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'membre') !== false) && !empty($_SESSION['session_redirect_after_login']) && !est_identifie() && !defined('LOAD_NO_OPTIONAL_MODULE') && !defined('IN_ACCES_ACCOUNT') && !defined('IN_GET_PASSWORD')) {
		unset($_SESSION['session_redirect_after_login']);
	}
}
if (!isset($_SESSION['session_country_detected']) && !empty($_SERVER['REMOTE_ADDR']) && check_if_module_active('geoip')) {
	// Géolocalisation de l'IP une fois pour toutes, et ensuite on garde l'information en session
	if(!class_exists('geoIP')) {
		include($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php');
	}
	$geoIP = new geoIP();
	$_SESSION['session_country_detected'] = $geoIP->geoIPCountryIDByAddr($_SERVER['REMOTE_ADDR']);
	$geoIP->geoIPClose();
	unset($geoIP);
}
// Gestion de l'affichage de contenu spécifique en fonction du pays du visiteur. Cette fonction nécessite une mise en place spécifique en SQL et n'est pas standard.
if(isset($_GET['site_country'])) {
	// L'utilisateur souhaite voir la version du site correspondant au pays $_GET['site_country']
	if(!empty($GLOBALS['site_parameters']['site_country_modify_allowed_array']) && !empty($_SESSION['session_country_detected']) && in_array(strval($_SESSION['session_country_detected']), $GLOBALS['site_parameters']['site_country_modify_allowed_array']) && empty($_SESSION['session_utilisateur']['site_country'])) {
		//  l'utilisateur est géolocalisé dans un pays qui est autorisé pour lui permettre de choisir son pays
		// ET l'utilisateur n'a pas de pays forcé dans peel_utilisateurs
		if(in_array(strval($_GET['site_country']), $GLOBALS['site_parameters']['site_country_allowed_array'])) {
			// Le choix en GET est autorisé => on le prend
			set_session_site_country(intval($_GET['site_country']));
		}
	}
	// On redirige 302 après avoir défini le site_country
	redirect_and_die(get_current_url(true, false, array('site_country')));
}
if(!isset($_SESSION['session_site_country'])) {
	// On définit pour quel pays on montre les données du site, lors de la première page vue par l'utilisateur
	if(!empty($_SESSION['session_utilisateur']['site_country'])) {
		// Pays forcé pour un utilisateur
		set_session_site_country(intval($_SESSION['session_utilisateur']['site_country']));
		// on essaie de prendre la devise correspondant au pays si elle est disponible sur le site pour la forcer, sinon on laisse session_devise tel que défini plus tôt dans ce fichier
	} elseif(!empty($_SESSION['session_country_detected']) && !empty($GLOBALS['site_parameters']['site_country_allowed_array']) && in_array(strval($_SESSION['session_country_detected']), $GLOBALS['site_parameters']['site_country_allowed_array'])) {
		set_session_site_country(intval($_SESSION['session_country_detected']));
		// on essaie de prendre la devise correspondant au pays si elle est disponible sur le site, sinon on laisse session_devise tel que défini plus tôt dans ce fichier
	} elseif(!empty($GLOBALS['site_parameters']['default_country_id']) && (empty($GLOBALS['site_parameters']['site_country_allowed_array']) || (!empty($GLOBALS['site_parameters']['site_country_allowed_array']) && in_array(strval($GLOBALS['site_parameters']['default_country_id']), $GLOBALS['site_parameters']['site_country_allowed_array'])))) {
		set_session_site_country(intval($GLOBALS['site_parameters']['default_country_id']));
	} else {
		set_session_site_country(0);
	}
	if(!empty($_SESSION['session_site_country']) && check_if_module_active('devises')) {
		set_current_devise(null, $_SESSION['session_site_country']);
	}
}
if(!empty($GLOBALS['site_parameters']['login_force_keep_current_page']) && !defined('IN_ACCES_ACCOUNT') && !defined('IN_COMPTE') && !defined('IN_REGISTER') && !defined('IN_GET_PASSWORD') && !defined('IN_404_ERROR_PAGE') && !defined('IN_CHART_DATA') && !defined('IN_QRCODE') && !defined('IN_RPC')) {
	$_SESSION['session_redirect_after_login'] = get_current_url(true); 
}
account_update();
if(!defined('PEEL_PREFETCH')) {
	call_module_hook('configuration_end', array());
}
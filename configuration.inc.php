<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: configuration.inc.php 39443 2014-01-06 16:44:24Z sdelaporte $
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
// Désactivation de scream qui altère le fonctionnement normale de error_reporting
@ini_set('scream.enabled', false);
// Eviter de bloquer sur la récupération d'une information venant d'un serveur extérieur
@ini_set('default_socket_timeout', 4);
if (function_exists('ini_set')) {
	// Cette valeur est ensuite modifiée quand on accède à la base de données suivant la configuration du site
	@ini_set('display_errors', 1);
}
// Initialisation de variable de gestion des erreurs
$GLOBALS['display_errors'] = 0;

if (!function_exists('ini_get') || @ini_get('register_globals')) {
	// Code à laisser absolument en début de fichier
	// Protection si register_globals est à ON
	foreach (array('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES') as $array_name) {
		foreach ($$array_name as $key => $value) {
			if (isset($GLOBALS[$key])) {
				unset($GLOBALS[$key]);
			}
			if (isset($$key)) {
				// Au cas où pour d'anciennes versions de PHP
				unset($$key);
			}
		}
		unset($key);
		unset($value);
	}
}

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
if (!defined('IN_PEEL')) {
	define('IN_PEEL', true);
}
define('PEEL_VERSION', '7.1.3');
$GLOBALS['ip_for_debug_mode'] = '';
foreach(explode(',', str_replace(array(' ', ';'), array(',', ','), $GLOBALS['ip_for_debug_mode'])) as $this_ip_part) {
	if (!empty($this_ip_part) && ($this_ip_part == '*' || strpos($_SERVER['REMOTE_ADDR'], $this_ip_part) === 0)) {
		define('PEEL_DEBUG', true);
		define('DEBUG_TEMPLATES', true);
		$GLOBALS['display_errors'] = 1;
		break;
	}
}
if (!defined('PEEL_DEBUG')) {
	define('PEEL_DEBUG', false);
	define('DEBUG_TEMPLATES', false);
}
if (!defined('IN_INSTALLATION')) {
	define('IN_INSTALLATION', false);
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
if (!defined('IN_CRON')) {
	define('GENERAL_ENCODING', 'utf-8'); // En minuscules. ATTENTION : Seulement pour développeurs avertis
}
if (function_exists('mb_internal_encoding')) {
	@mb_internal_encoding(GENERAL_ENCODING);
}
if (function_exists('mb_detect_order')) {
	@mb_detect_order(GENERAL_ENCODING);
}
if (function_exists('mb_http_input')) {
	@mb_http_input(GENERAL_ENCODING);
}
if (function_exists('mb_http_output')) {
	@mb_http_output(GENERAL_ENCODING);
}
// Sur 1and1 par exemple, les fonctions ci-dessus ne fonctionnent pas pour des raisons inconnues, on utilise donc également ini_set ci-dessous
@ini_set('mbstring.internal_encoding', GENERAL_ENCODING);
@ini_set('mbstring.detect_order', GENERAL_ENCODING);
@ini_set('mbstring.http_input', GENERAL_ENCODING);
@ini_set('mbstring.http_output', GENERAL_ENCODING);
// la fonction date_default_timezone_set existe depuis PHP 5.1.0
if (version_compare(PHP_VERSION, '5.1.0', '>=')) {
	// Supprimer les warnings dans certains cas de configuration serveur en version PHP >= 5.3
	@date_default_timezone_set(@date_default_timezone_get());
}
// Compatibilité serveur web IIS (sans gestion d'URL rewriting)
if (!isset($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
	if (!empty($_SERVER['QUERY_STRING'])) {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}
// Fin compatibilité IIS
$GLOBALS['dirroot'] = dirname(__FILE__);
// On détecte l'URL de base du site pour l'installation uniquement
// Si $GLOBALS['wwwroot'] est précisé dans /lib/setup/info.inc.php, alors il aura la priorité
if (!empty($_SERVER['SCRIPT_FILENAME'])) {
	$file_called_real_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME']));
} else {
	$file_called_real_path = str_replace('\\', '/', @realpath('./'));
}
$formatted_dirroot = str_replace('\\', '/', $GLOBALS['dirroot']);
if (strpos($file_called_real_path, 'public_html') === false && strpos($formatted_dirroot, 'public_html') !== false) {
	$file_called_real_path = str_replace('private_html/', 'public_html/', $file_called_real_path);
}
if (!empty($file_called_real_path) && strpos($file_called_real_path, $formatted_dirroot) !== false) {
	// Cas normal
	if ($formatted_dirroot == $file_called_real_path) {
		$peel_subfolder = '';
	} else {
		// CAS PARTICULIER : Sur 1&1 par exemple quand on est en multidomaine, le début de dirroot est /kunden/ alors que sinon il n'y a pas /kunden
		// Pour être le plus compatible possible, on commence donc par strpos($file_called_real_path, $formatted_dirroot)
		$peel_subfolder = substr($file_called_real_path, strpos($file_called_real_path, $formatted_dirroot) + strlen($formatted_dirroot));
	}
} else {
	// Au cas où __FILE__ et SCRIPT_FILENAME ne seraient pas cohérents à cause d'alias de dossiers sur l'hébergement
	// Dans ce cas on considère arbitrairement qu'il n'y a pas de sous-dossier à prendre
	$peel_subfolder = '';
}
$file_called_relative_path = str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
$GLOBALS['apparent_folder'] = substr($file_called_relative_path, 0, strlen($file_called_relative_path) - strlen($peel_subfolder));

if (empty($GLOBALS['apparent_folder']) || substr($GLOBALS['apparent_folder'], strlen($GLOBALS['apparent_folder']) - 1) != '/') {
	$GLOBALS['apparent_folder'] .= '/';
}
require($GLOBALS['dirroot'] . "/lib/fonctions/fonctions.php");
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
		// Si wwwroot ne semble pas valable, on utilise la valeur détectée automatiquement
		$GLOBALS['wwwroot'] = $GLOBALS['detected_wwwroot'];
	}
} else {
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
	if (function_exists('ini_set')) {
		// ini_set('session.cookie_secure', '1');
	}
}
// wwwroot sera par la suite potentiellement modifié en cas d'URL rewriting sur la langue en cours
// On sauvegarde donc son contenu dans wwwroot_main pour s'en reservir dans l'URL Rewriting
$GLOBALS['wwwroot_main'] = $GLOBALS['wwwroot'];

$GLOBALS['repertoire_achat'] = $GLOBALS['dirroot'] . "/achat";
$GLOBALS['libdir'] = $GLOBALS['dirroot'] . "/lib";
$GLOBALS['invoicedir'] = $GLOBALS['dirroot'] . "/invoice";
$GLOBALS['uploaddir'] = $GLOBALS['dirroot'] . "/upload";

/*
 * Déclaration des objets et des fonctions
 *
 */
require($GLOBALS['dirroot'] . "/lib/class/Cache.php");
require($GLOBALS['dirroot'] . "/lib/class/String.php");
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

// A partir de cette ligne vous n'avez a priori pas de variable à modifier.
if (!IN_INSTALLATION) {
	if (empty($_POST)) {
		if (String::strpos(String::strtolower(String::rawurldecode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])), str_replace(array('http://', 'https://'), '', String::strtolower(String::rawurldecode($GLOBALS['wwwroot'])))) === false && String::strpos(str_replace(array('http://', 'https://'), '', String::strtolower(String::rawurldecode($GLOBALS['wwwroot']))), String::strtolower(String::rawurldecode($_SERVER['HTTP_HOST']))) !== false) {
			// Dans le cas où un site est accessible via un domaine directement
			// Si on est sur une URL qui ne contient pas wwwroot, mais le domaine est bien contenu dans wwwroot => on veut donc rajouter le sous-domaine
			// NB : Il manque donc un sous-domaine, mais on n'est pas sur une URL alternative (en effet, on fait attention à se trouver uniquement dans des cas "normaux" d'absence de sous-domaine, pas d'autres cas plus complexes de configuration avec plusieurs chemins serveurs)
			// par exemple : wwwroot indique un sous-domaine tel que www, alors que l'URL en cours ne contient pas www => on redirige vers une URL qui respecte la configuration de wwwroot
			redirect_and_die(String::substr($GLOBALS['wwwroot'], 0, String::strlen($GLOBALS['wwwroot']) - String::strlen($GLOBALS['apparent_folder']) + 1) . $_SERVER['REQUEST_URI'], true);
		}
		if (String::strpos(String::strtolower(rawurldecode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])), 'www.' . str_replace(array('http://', 'https://'), '', String::strtolower(rawurldecode($GLOBALS['wwwroot'])))) === 0) {
			// Si on a www. en trop par rapport à ce qui est prévu dans wwwroot, on retire le www.
			redirect_and_die(String::substr($GLOBALS['wwwroot'], 0, String::strlen($GLOBALS['wwwroot']) - String::strlen($GLOBALS['apparent_folder']) + 1) . $_SERVER['REQUEST_URI'], true);
		}
	}
	if (empty($GLOBALS['installation_folder_active'])) {
		db_connect($GLOBALS['database_object']);
		// On remplit $GLOBALS['site_parameters'] qui sera ensuite surchargé pour la langue choisie par set_lang_configuration_and_texts()
		load_site_parameters();
		$parameters_loaded = true;
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
if (!isset($GLOBALS['site_parameters']['display_errors_for_ips'])) {
	$GLOBALS['display_errors'] = 1;
} elseif (!empty($GLOBALS['site_parameters']['display_errors_for_ips'])) {
	foreach(explode(',', str_replace(array(' ', ';'), array(',', ','), $GLOBALS['site_parameters']['display_errors_for_ips'])) as $this_ip_part) {
		if (!empty($this_ip_part) && ($this_ip_part == '*' || strpos($_SERVER['REMOTE_ADDR'], $this_ip_part) === 0)) {
			// IP utilisée détectée comme commençant par une IP listée dans display_errors_for_ips
			$GLOBALS['display_errors'] = 1;
			break;
		}
	}
}
if (!empty($GLOBALS['site_parameters']['no_display_tag_analytics_for_ip'])) {
	foreach(explode(',', str_replace(array(' ', ';'), array(',', ','), $GLOBALS['site_parameters']['no_display_tag_analytics_for_ip'])) as $this_ip_part) {
		if (!empty($this_ip_part) && ($this_ip_part == '*' || strpos($_SERVER['REMOTE_ADDR'], $this_ip_part) === 0)) {
			// IP utilisée détectée comme commençant par une IP listée dans no_display_tag_analytics_for_ip
			$no_display_tag_analytics = true;
			break;
		}
	}
}
if (function_exists('ini_set')) {
	@ini_set("gd.jpeg_ignore_warning", 1); // Ignore les alertes créées par la fonction jpeg2wbmp() et la fonction imagecreatefromjpeg()
	@ini_set('display_errors', $GLOBALS['display_errors']);
}
if (!empty($GLOBALS['site_parameters']['enable_gzhandler'])) {
	ob_start('ob_gzhandler');
}

/*
 * Déclaration des sessions
 *
 * Ouverture d'une session, utilise une variable tableau SESSION
 * pour stocker les variables à traquer
 */
// Paramétrage des sessions
// Pour permettre d'avoir à la fois des cookies de session valides pour N sous-domaines, et à la fois
// pour éviter que plusieurs boutiques PEEL tournent dans des sous-domaines différents, on prend
// un nom de cookie de session différent pour chaque installation de PEEL.
$GLOBALS['session_cookie_name'] = vb($GLOBALS['site_parameters']['session_cookie_basename']) . substr(md5($GLOBALS['wwwroot_main']), 0, 8);
if (function_exists('ini_set')) {
	if (!empty($GLOBALS['site_parameters']['sessions_duration'])) {
		@ini_set('session.gc_maxlifetime', 60 * $GLOBALS['site_parameters']['sessions_duration']);
		@ini_set('session.cache_expire', $GLOBALS['site_parameters']['sessions_duration']);
	}
	@ini_set('session.use_cookies', '1');
	@ini_set('session.use_only_cookies', '1'); // évite les attaques avec session id dans l'URL
	@ini_set('session.use_trans_sid', '0'); // empêche la propagation des SESSION_ID dans les URL
	@ini_set('session.hash_function', '1'); // Hash avec SHA-1 et non pas MD5
	@ini_set('url_rewriter.tags', '');
	@ini_set('session.name', $GLOBALS['session_cookie_name']);
	if (!empty($GLOBALS['site_parameters']['session_save_path'])) {
		@ini_set('session.save_path', $GLOBALS['site_parameters']['session_save_path']);
	}
	if (vb($GLOBALS['site_parameters']['force_sessions_for_subdomains']) && get_site_domain(true)) {
		// On ne passe pas ici si l'URL est à la base d'IP et non pas de domaine
		@ini_set('session.cookie_domain', '.' . get_site_domain());
	}
}
session_start();
if (!isset($_SESSION['session_initiated']) && isset($_COOKIE[$GLOBALS['session_cookie_name']])) {
	// Protection contre les fixations de session : l'utilisateur déclare qu'il possède une session alors que le serveur ne la connait pas
	// => il ne faut pas prendre l'identifiant de session proposé par l'utilisateur
	@session_regenerate_id();
}
$_SESSION['session_initiated'] = true;
// Protection contre les vols de sessions
// On ne se base pas sur les IP car certains FAI ne permettent pas de naviguer avec une seule IP (AOL,...)
// Lorsqu'on installe un plugin sur un navigateur ou qu'on le met à jour, le HTTP_USER_AGENT est modifié, mais on redémarre le navigateur
// Donc cette protection est bien compatible avec tout type de user_agents
if (!isset($_SERVER['HTTP_USER_AGENT'])) {
	$_SERVER['HTTP_USER_AGENT'] = '';
}
if (isset($_SESSION['session_user_agent'])) {
	if ($_SESSION['session_user_agent'] != sha1('GcFsD5EOvgSvQFtL4nIy' . $_SERVER['HTTP_USER_AGENT'])) {
		// On suppose qu'il y a vol de session => on la désactive
		session_unset();
		session_destroy();
	}
} else {
	$_SESSION['session_user_agent'] = sha1('GcFsD5EOvgSvQFtL4nIy' . $_SERVER['HTTP_USER_AGENT']);
}
// Initialisation de SESSION si nécessaire
if (!isset($_SESSION)) {
	$_SESSION = array();
}

if(!empty($_SERVER['RAW_HTTP_COOKIE'])){
	// On complète les informations de cookie si le serveur envoie les cookies encryptés, et donc refuse les cookies provenant du navigateur par sécurité
	foreach(explode(';', $_SERVER['RAW_HTTP_COOKIE']) as $this_cookie){
		if(strpos($this_cookie, '=') !== false){
			list($key,$value) = explode('=', $this_cookie, 2);
			$key = rawurldecode(trim($key));
			if(!array_key_exists($key, $_COOKIE)){
				$_COOKIE[$key] = rawurldecode(trim($value));
			}
		}
	}
}
$GLOBALS['google_pub_count'] = 0;
// Nettoyage des données et suppressions des magic_quotes si nécessaire
// Doit être fait après l'ouverture de la session car ça retire le HTML des données si on n'est pas administrateur
@set_magic_quotes_runtime(0);
if (function_exists('array_walk_recursive')) {
	array_walk_recursive($_POST, 'cleanDataDeep');
	array_walk_recursive($_GET, 'cleanDataDeep');
	array_walk_recursive($_COOKIE, 'cleanDataDeep');
	array_walk_recursive($_REQUEST, 'cleanDataDeep');
} else {
	$_POST = array_map('cleanDataDeep', $_POST);
	$_GET = array_map('cleanDataDeep', $_GET);
	$_COOKIE = array_map('cleanDataDeep', $_COOKIE);
	$_REQUEST = array_map('cleanDataDeep', $_REQUEST);
}
if (((!empty($_GET['update']) && $_GET['update'] == 1) || !empty($_GET['devise']) || !empty($_GET['nombre'])) && (!est_identifie() || !a_priv("admin*", true) || is_user_bot())) {
	// Page de MAJ du cache : les moteurs ne doivent pas pouvoir activer ou référencer ces pages => redirection 301
	redirect_and_die(get_current_url(true, false, array('update', 'devise', 'nombre')), true);
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

require($GLOBALS['dirroot'] . '/lib/fonctions/modules_handler.php');
// Module d'URL Rewriting et gestion des modules de sites qui peuvent contenir des définitions d'URL
// A gérer avant la gestion des langues ci-après
$GLOBALS['rewritefile'] = $GLOBALS['dirroot'] . "/modules/url_rewriting/rewrite.php";

$GLOBALS['fonctionsannonces'] = $GLOBALS['dirroot'] . "/modules/annonces/fonctions.php";
$GLOBALS['fonctionsvitrine'] = $GLOBALS['dirroot'] . "/modules/vitrine/fonctions.php";

// Chargement de modules complémentaires
if (!defined('LOAD_NO_OPTIONAL_MODULE') && !empty($GLOBALS['site_parameters']['load_site_specific_files_before_others'])) {
	foreach($GLOBALS['site_parameters']['load_site_specific_files_before_others'] as $this_file_relative_path) {
		if(file_exists($GLOBALS['dirroot'] . $this_file_relative_path)) {
			include($GLOBALS['dirroot'] . $this_file_relative_path);
		}
	}
}
if (!defined('LOAD_NO_OPTIONAL_MODULE') && !empty($GLOBALS['site_parameters']['load_site_specific_lang_folders'])) {
	foreach($GLOBALS['site_parameters']['load_site_specific_lang_folders'] as $this_key => $this_file_relative_path) {
		if(file_exists($GLOBALS['dirroot'] . $this_file_relative_path)) {
			// Ces fichiers de langue sont chargés en derniers grâce à leur clé élevée, et sont donc prioritaires
			$GLOBALS['modules_lang_directory_array'][1000 + $this_key] = $this_file_relative_path;
		}
	}
}

if (is_module_url_rewriting_active()) {
	// NB: The module has to be loaded even if LOAD_NO_OPTIONAL_MODULE is defined
	require($GLOBALS['rewritefile']);
}
require($GLOBALS['dirroot'] . "/lib/fonctions/url_standard.php");

if(!isset($GLOBALS['site_parameters']['template_directory']) || !file_exists($GLOBALS['dirroot'] . "/modeles/" . $GLOBALS['site_parameters']['template_directory'])) {
	$modeles_dir = $GLOBALS['dirroot'] . "/modeles";
	if ($handle = opendir($modeles_dir)) {
		while ($file = readdir($handle)) {
			if ($file != "." && $file != ".." && is_dir($modeles_dir . '/' . $file)) {
				if(empty($GLOBALS['repertoire_modele']) || substr($GLOBALS['repertoire_modele'], 0, 4)!='peel') {
					// On prend de préférence un répertoire de nom différent de peelXXX
					$GLOBALS['site_parameters']['template_directory'] = $file;
				}
			}
		}
	}
}
$GLOBALS['repertoire_modele'] = $GLOBALS['dirroot'] . "/modeles/" . vb($GLOBALS['site_parameters']['template_directory']);

$GLOBALS['lang_codes'] = array(); // Variable de session récuperant les codes Langue
$GLOBALS['admin_lang_codes'] = array(); // Variable de session récuperant les codes Langue des langues administrables (actives, ou désactivées mais administrables : pastille orange)
$GLOBALS['lang_flags'] = array(); // Variable de session récuperant l'URL des drapeaux de langues
$GLOBALS['lang_names'] = array(); // Variable de session récuperant le nom de la langue dans sa propre langue
$GLOBALS['langs_flags_correspondance'] = array('en'=>'uk.gif');
$GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'] = null;
if (!IN_INSTALLATION && empty($GLOBALS['installation_folder_active'])) {
	// We get the activated languages
	$sqlLng = "SELECT *
		FROM peel_langues
		WHERE 1
		GROUP BY lang
		ORDER BY IF(etat = '1'". (!empty($_GET['langue'])?" OR lang='" . word_real_escape_string($_GET['langue']) . "'":'') . ", 1, 0) DESC, position ASC";
	$resLng = query($sqlLng);
	while ($lng = fetch_assoc($resLng)) {
		if($lng['etat'] == 1 || (!empty($_GET['langue']) && $lng['lang'] == $_GET['langue'])) {
			$GLOBALS['lang_codes'][] = $lng['lang'];
			$GLOBALS['admin_lang_codes'][] = $lng['lang'];
		} elseif($lng['etat'] == -1) {
			// Langue administrable mais pas en production
			$GLOBALS['admin_lang_codes'][] = $lng['lang'];
		}
		$GLOBALS['lang_flags'][$lng['lang']] = $lng['flag'];
		$GLOBALS['lang_names'][$lng['lang']] = $lng["nom_" . $lng['lang']];
		$GLOBALS['lang_etat'][$lng['lang']] = $lng['etat'];
		$GLOBALS['lang_url_rewriting'][$lng['lang']] = $lng["url_rewriting"];
		if(!empty($lng["load_default_lang_files_before_main_lang"])) {
			$GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$lng['lang']] = explode(',', $lng["load_default_lang_files_before_main_lang"]);
		}
	}
	if(empty($GLOBALS['lang_codes'])){
		// Si on n'a pas trouvé au moins une langue, on prend les langues même inactives
		$GLOBALS['lang_codes'] = array_keys($GLOBALS['lang_etat']);
	}
	// Initialisation de la SESSION langue
	foreach($GLOBALS['lang_codes'] as $this_lang) {
		$GLOBALS['get_lang_rewrited_wwwroot'][$this_lang] = get_lang_rewrited_wwwroot($this_lang);
		$GLOBALS['langs_array_by_wwwroot'][$GLOBALS['get_lang_rewrited_wwwroot'][$this_lang]][] = $this_lang;
	}
} else {
	// Récupération des langues possibles pour l'installation
	$lang_dir = $GLOBALS['dirroot'] . "/lib/lang";
	if ($handle = opendir($lang_dir)) {
		while ($file = readdir($handle)) {
			if ($file != "." && $file != ".." && is_file($lang_dir . '/' . $file) && strtolower($file) == $file) {
				if (substr($file, 0, strlen('admin_install_')) == 'admin_install_' && substr($file, strlen('admin_install_')+2) == '.php') {
					$lng = substr($file, strlen('admin_install_'), 2);
					// Fichier du type admin_install_xx.php pour l'interface d'installation
					$GLOBALS['lang_codes'][] = $lng;
					$GLOBALS['admin_lang_codes'][] = $lng;
					if(!empty($GLOBALS['langs_flags_correspondance'][$lng])){
						$GLOBALS['lang_flags'][$lng] = $GLOBALS['langs_flags_correspondance'][$lng];
					} else {
						$GLOBALS['lang_flags'][$lng] = $lng . '.gif';
					}
					if(file_exists($GLOBALS['dirroot'] . '/lib/lang/database_langues_'.$lng.'.php')) {
						include($GLOBALS['dirroot'] . '/lib/lang/database_langues_'.$lng.'.php');
					} else {
						include($GLOBALS['dirroot'] . '/lib/lang/database_langues_en.php');
					}
					if(!empty($peel_langues['nom']) && !empty($peel_langues['nom'][$lng])) {
						// Variable locale et non pas globale pour peel_langues car issue de @include($GLOBALS['dirroot'] . '/lib/lang/database_langues_'.$_SESSION['session_langue'].'.php');
						$GLOBALS['lang_names'][$lng] = $peel_langues['nom'][$lng];
					} else {
						$GLOBALS['lang_names'][$lng] = $lng;
					}
					$GLOBALS['lang_etat'][$lng] = 1;
					$GLOBALS['lang_url_rewriting'][$lng] = '';
				}elseif (substr($file, 2) == '.php') {
					$lng = substr($file, 0, 2);
					// Fichier du type xx.php pour savoir quelles langues on peut installer pour le site
					$GLOBALS['available_languages'][] = $lng;
				}
			}
		}
	}
	sort($GLOBALS['admin_lang_codes']);
}

foreach($GLOBALS['lang_codes'] as $this_lang) {
	// Ajout des gros drapeaux
	if(file_exists($GLOBALS['dirroot'] . '/images/'.$this_lang.'.png')) {
		$GLOBALS['lang_flags_big'][$this_lang] = $GLOBALS['wwwroot'] . '/images/'.$this_lang.'.png';
	}
}

if(defined('IN_PEEL_ADMIN') || IN_INSTALLATION) {
	// Chargement des fonctions d'administration
	include($GLOBALS['dirroot'] . "/lib/fonctions/fonctions_admin.php");
	$GLOBALS['load_admin_lang'] = true;
}

// Si nécessaire dans get_identified_lang, on redirige si langue pas définie
$_SESSION['session_langue'] = get_identified_lang((defined('IN_PEEL_ADMIN')?$GLOBALS['admin_lang_codes']:$GLOBALS['lang_codes']));
// On est maintenant sûr que la langue est correctement définie et que le fichier de langue associé existe
// Dans la ligne suivante, on modifie notamment $GLOBALS['site'] et $GLOBALS['wwwroot'], $GLOBALS['wwwroot_in_admin'] et $GLOBALS['administrer_url']
// On charge les fichiers de langue de base, on va gérer les modules ensuite
if(!in_array($_SESSION['session_langue'], $GLOBALS['site_parameters']['complete_lang_files'])) {
	// Si la langue utilisée n'est pas une langue principale du logiciel, on considère que le fichiers de langue peuvent être incomplets
	// => on charge la langue anglaise par défaut avant la langue spécifiée => si il manque une chaine de caractères, c'est la version anglaise qui sera présente
	$GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$_SESSION['session_langue']] = array('en');
}
// Si SKIP_LANG est défini, seules les variables globales seront définies
set_lang_configuration_and_texts($_SESSION['session_langue'], vb($GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$_SESSION['session_langue']]), true, false, !empty($GLOBALS['load_admin_lang']), true, defined('SKIP_SET_LANG'));

if (!IN_INSTALLATION && empty($GLOBALS['installation_folder_active'])) {
	// redirections éventuelles si la langue est définie sans cohérence avec ce qui est configuré dans peel_langues
	if (empty($_POST) && !empty($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']]) && strpos($GLOBALS['lang_url_rewriting'][$_SESSION['session_langue']], '://') !== false) {
		if ($GLOBALS['detected_wwwroot'] != $GLOBALS['wwwroot'] && $GLOBALS['detected_wwwroot'] == str_replace(array('www.'), array($_SESSION['session_langue'] . '.'), $GLOBALS['wwwroot_main'])) {
			// Exemple : on redirige en.domaine.com vers domaine-specifique-pour-langue-en.com
			redirect_and_die($GLOBALS['wwwroot'] . String::substr($_SERVER['REQUEST_URI'], String::strlen($GLOBALS['apparent_folder']) - 1), true);
		}
		if (String::substr($_SERVER['REQUEST_URI'], String::strlen($GLOBALS['apparent_folder']) - 1, 4) == '/' . $_SESSION['session_langue'] . '/') {
			// Exemple : on redirige domaine.com/en vers domaine-specifique-pour-langue-en.com
			redirect_and_die($GLOBALS['wwwroot'] . String::substr($_SERVER['REQUEST_URI'], 3 + String::strlen($GLOBALS['apparent_folder']) - 1), true);
		}
		if (String::substr_count($GLOBALS['wwwroot'], '/') == 2 && String::strpos(String::strtolower(String::rawurldecode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])), str_replace(array('http://', 'https://'), '', String::strtolower(String::rawurldecode($GLOBALS['wwwroot'])))) === false && String::strpos(str_replace(array('http://', 'https://'), '', String::strtolower(String::rawurldecode($GLOBALS['wwwroot']))), String::strtolower(String::rawurldecode($_SERVER['HTTP_HOST']))) !== false) {
			// Dans le cas où un site est accesible via un domaine directement (pas via un répertoire) :
			// Si on est sur une URL qui ne contient pas wwwroot, mais le domaine est bien contenu dans wwwroot => on veut donc rajouter le sous-domaine
			// NB : Il manque donc un sous-domaine, mais on n'est pas sur une URL alternative (en effet, on fait attention à se trouver uniquement dans des cas "normaux" d'absence de sous-domaine, pas d'autres cas plsu complexes de configuration avec plusieurs chemins serveurs)
			// par exemple : wwwroot indique un sous-domaine tel que www, alors que l'URL en cours ne contient pas www => on redirige vers une URL qui respecte la configuration de wwwroot
			redirect_and_die($GLOBALS['wwwroot'] . String::substr($_SERVER['REQUEST_URI'], String::strlen($GLOBALS['apparent_folder']) - 1), true);
		}
	}
	// Initialisation de la devise
	if (empty($_SESSION['session_devise']) || empty($_SESSION['session_devise']['code'])) {
		$_SESSION['session_devise']['symbole'] = String::html_entity_decode(str_replace('&euro;', '€', vb($GLOBALS['site_parameters']['symbole'])));
		$_SESSION['session_devise']['symbole_place'] = vb($GLOBALS['site_parameters']['symbole_place']);
		$_SESSION['session_devise']['conversion'] = 1;
		$_SESSION['session_devise']['code'] = vb($GLOBALS['site_parameters']['code']);
	}
	// Détermine les variables en fonction du site
	if (vb($GLOBALS['site_parameters']['admin_force_ssl'])) {
		if (defined('IN_PEEL_ADMIN')) {
			// On ne fait pas la redirection sur sites.php pour éviter de bloquer totalement l'administrateur si https ne marche pas
			if (strpos($_SERVER['PHP_SELF'], 'sites.php') === false && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off')) {
				// Attention : on perd les POST si il y en avait, mais on ne veut pas pour des raisons de sécurité exclure le cas où il y aurait des POST
				redirect_and_die(str_replace('http://', 'https://', get_current_url()));
			}
		}
	}
	if (empty($GLOBALS['site'])) {
		// $GLOBALS['site'] est déjà défini si on a exécuté set_lang_configuration_and_texts plus haut
		$GLOBALS['site'] = vb($GLOBALS['site_parameters']['nom_' . $_SESSION['session_langue']]);
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
	$_SESSION['session_devise']['code'] = 'EUR';
	$_SESSION['session_devise']['symbole'] = ' €';
	$_SESSION['session_devise']['conversion'] = 1;
	$_SESSION['session_devise']['symbole_place'] = 1;
}

if (!defined('IN_CRON') && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') && strpos($GLOBALS['wwwroot'], 'https://') === 0) {
	// On accède en http et non pas en https à un site explicitement configuré en https
	// Attention : on perd les POST si il y en avait, mais on ne veut pas pour des raisons de sécurité exclure le cas où il y aurait des POST
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
}

// Chargement du moteur de template : Smarty ou Twig
include($GLOBALS['dirroot'] . "/lib/templateEngines/EngineTpl.php");
/* @var $GLOBALS['tplEngine'] EngineTpl */
if(DEBUG_TEMPLATES || (!empty($_GET['update']) && $_GET['update'] == 1) || (strpos($GLOBALS['wwwroot'], '://localhost')!==false || strpos($GLOBALS['wwwroot'], '://127.0.0.1')!==false) || !vb($GLOBALS['site_parameters']['smarty_avoid_check_template_files_update'])) {
	// On force la mise à jour du cache des templates
	$templates_force_compile = true;
} else {
	$templates_force_compile = false;
}
if(!in_array(vb($GLOBALS['site_parameters']['template_engine']), array('smarty', 'twig'))) {
	$GLOBALS['site_parameters']['template_engine'] = 'smarty';
}
if($GLOBALS['site_parameters']['template_engine'] == 'twig') {
	require $GLOBALS['dirroot'] . '/lib/templateEngines/twig/Autoloader.php';
	Twig_Autoloader::register();
}
$GLOBALS['tplEngine'] = EngineTpl::create($GLOBALS['site_parameters']['template_engine'], $GLOBALS['repertoire_modele'] . '/' . $GLOBALS['site_parameters']['template_engine'] . '/', $templates_force_compile, DEBUG_TEMPLATES);
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
	$GLOBALS['disable_google_ads'] = true;
}

if (!IN_INSTALLATION) {
	if (!defined('IN_PATHFILE') && !defined('IN_IPN') && !defined('IN_PEEL_ADMIN') && !defined('IN_ACCES_ACCOUNT') && !defined('IN_GET_PASSWORD') && vb($GLOBALS['site_parameters']['site_suspended']) && !a_priv('admin*', false)) {
		echo '<div class="center" style="font-size:14px; font-weight:bold;"><br /><br />' . $GLOBALS['STR_UPDATE_WEBSITE'] . '<br /><br />' . $GLOBALS['STR_THANKS_UNDERSTANDING'] . '</div>';
		die();
	}
	// DEBUT DES MODULES OPTIONNELS :
	// Ces modules optionnels, non GPL, sont vendus séparément ou dans le module Premium
	// Contactez PEEL sur https://www.peel.fr/ ou au 01 75 43 67 97 pour obtenir un de ces modules
	// Module des gestion des devises
	$GLOBALS['fonctionsdevises'] = $GLOBALS['dirroot'] . "/modules/devises/fonctions.php";
	if (is_devises_module_active()) {
		// NB: The module has to be loaded even if LOAD_NO_OPTIONAL_MODULE is defined
		include($GLOBALS['fonctionsdevises']);
		if (!empty($_GET['devise'])) {
			set_current_devise($_GET['devise']);
			// On redirige 302 après avoir défini la devise (les moteurs ont déjà plus tôt eu droit à redirection 301)
			redirect_and_die(get_current_url(true, false, array('devise')));
		}
	}
}
if (!IN_INSTALLATION || IN_INSTALLATION >= 5) {
	// Module de forum
	$GLOBALS['fonctionsforum'] = $GLOBALS['dirroot'] . "/modules/forum/functions.php";
	if (is_module_forum_active()) {
		$GLOBALS['modules_lang_directory_array'][] = '/modules/forum/lang/';
		include($GLOBALS['fonctionsforum']);
	}
	// Module de revendeur
	$GLOBALS['fonctionsreseller'] = $GLOBALS['dirroot'] . "/modules/reseller/fonctions.php";
	if (is_reseller_module_active()) {
		// NB: The module has to be loaded even if LOAD_NO_OPTIONAL_MODULE is defined
		include($GLOBALS['fonctionsreseller']);
	}
	// Affichage en page d'accueil des produits à la une
	$GLOBALS['fonctionsmenus'] = $GLOBALS['dirroot'] . "/modules/menus/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_rollover_module_active()) {
		include($GLOBALS['fonctionsmenus']);
	}
	// Affichage en page d'accueil des produits meilleures ventes
	$GLOBALS['fonctionsbestseller'] = $GLOBALS['dirroot'] . '/modules/best_seller/fonctions.php';
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_best_seller_module_active()) {
		include($GLOBALS['fonctionsbestseller']);
	}
	// Affichage en page d'accueil des produits récemment consultés
	$GLOBALS['fonctionslastviews'] = $GLOBALS['dirroot'] . '/modules/last_views/fonctions.php';
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_last_views_module_active()) {
		include($GLOBALS['fonctionslastviews']);
	}
	// Fonctions de gestion et utilisation de chèques cadeaux
	$GLOBALS['fonctionsgiftcheck'] = $GLOBALS['dirroot'] . '/modules/gift_check/administrer/fonctions.php';
	$GLOBALS['fonctionscheck'] = $GLOBALS['dirroot'] . "/modules/gift_check/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_module_gift_checks_active()) {
		include($GLOBALS['fonctionscheck']);
	}
	// Module d'affichage des produits les plus recherchés sous forme de nuage de mots
	$GLOBALS['fonctionstagcloud'] = $GLOBALS['dirroot'] . "/modules/tagcloud/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_module_tagcloud_active()) {
		include($GLOBALS['fonctionstagcloud']);
	}
	// Module d'affichage de publicité
	$GLOBALS['fonctionsbanner'] = $GLOBALS['dirroot'] . "/modules/banner/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_module_banner_active()) {
		include($GLOBALS['fonctionsbanner']);
	}
	// Module "RSS"
	$GLOBALS['fonctionsrss'] = $GLOBALS['dirroot'] . "/modules/rss/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_module_rss_active()) {
		include($GLOBALS['fonctionsrss']);
	}
	// Module "Pense-Bete"
	$GLOBALS['fonctionspensebete'] = $GLOBALS['dirroot'] . "/modules/pensebete/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_module_pensebete_active()) {
		include($GLOBALS['fonctionspensebete']);
	}
	// Fonctions Thumbs
	$GLOBALS['fonctionsthumbs'] = $GLOBALS['dirroot'] . "/modules/thumbs/fonctions.php";
	if (is_thumbs_module_active()) {
		include($GLOBALS['fonctionsthumbs']);
	}
	// Module de recherche par catégorie
	$GLOBALS['fonctionssearch'] = $GLOBALS['dirroot'] . "/modules/search/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_advanced_search_active()) {
		include($GLOBALS['fonctionssearch']);
	}
	// Affichage des attributs produits
	$GLOBALS['fonctionsattributs'] = $GLOBALS['dirroot'] . "/modules/attributs/fonctions.php";
	if (is_attributes_module_active()) {
		// Utilisé par Product => nécessaire pour rpc.php => Pas d'exclusion LOAD_NO_OPTIONAL_MODULE
		include($GLOBALS['fonctionsattributs']);
	}
	// Module de gestion des promotions par marques
	$GLOBALS['fonctionsmarquepromotions'] = $GLOBALS['dirroot'] . "/modules/marques_promotion/fonctions.php";
	if (is_marque_promotion_module_active()) {
		// Utilisé par Product => nécessaire pour rpc.php => Pas d'exclusion LOAD_NO_OPTIONAL_MODULE
		include($GLOBALS['fonctionsmarquepromotions']);
	}
	// Module de gestion des promotions par catégorie
	$GLOBALS['fonctionscatpromotions'] = $GLOBALS['dirroot'] . "/modules/category_promotion/fonctions.php";
	if (is_category_promotion_module_active()) {
		// Utilisé par Product => nécessaire pour rpc.php => Pas d'exclusion LOAD_NO_OPTIONAL_MODULE
		include($GLOBALS['fonctionscatpromotions']);
	}
	// Module des produits en telechargement
	$GLOBALS['fonctionsdownload'] = $GLOBALS['dirroot'] . "/modules/download/fonctions.php";
	$GLOBALS['fonctionsadmindownload'] = $GLOBALS['dirroot'] . "/modules/download/administrer/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_download_module_active()) {
		include($GLOBALS['fonctionsdownload']);
		if(defined('IN_PEEL_ADMIN')) {
			include($GLOBALS['fonctionsadmindownload']);
		}
	}
	// Module de parrainage
	$GLOBALS['fonctionsparrain'] = $GLOBALS['dirroot'] . "/modules/parrainage/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_parrainage_module_active()) {
		include($GLOBALS['fonctionsparrain']);
	}
	// Module affiliation
	$GLOBALS['fonctionsaffiliate'] = $GLOBALS['dirroot'] . "/modules/affiliation/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_affiliate_module_active()) {
		include($GLOBALS['fonctionsaffiliate']);
		$GLOBALS['modules_lang_directory_array'][] = '/modules/affiliation/lang/';
		if (!empty($_GET['affilie'])) {
			// Initialisation de la sesssion affilié
			$_SESSION['session_affilie'] = intval($_GET['affilie']);
		}
	}
	// Module micro-entreprise
	$GLOBALS['fonctionsmicro'] = $GLOBALS['dirroot'] . "/modules/micro_entreprise/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_micro_entreprise_module_active()) {
		include($GLOBALS['fonctionsmicro']);
	}
	// Module de gestion des stocks avancés
	$GLOBALS['fonctionsstock_advanced_admin'] = $GLOBALS['dirroot'] . "/modules/stock_advanced/administrer/fonctions.php";
	$GLOBALS['fonctionsstock_advanced'] = $GLOBALS['dirroot'] . "/modules/stock_advanced/fonctions.php";
	if ((!defined('LOAD_NO_OPTIONAL_MODULE') || defined('FORCE_STOCK_MANAGER')) && is_stock_advanced_module_active()) {
		include($GLOBALS['fonctionsstock_advanced']);
	}
	// Module liste de cadeaux
	$GLOBALS['fonctionsgiftlist'] = $GLOBALS['dirroot'] . "/modules/listecadeau/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_giftlist_module_active()) {
		include($GLOBALS['fonctionsgiftlist']);
		$GLOBALS['modules_lang_directory_array'][] = '/modules/listecadeau/lang/';
	}
	// Module des cadeaux
	$GLOBALS['fonctionsgift'] = $GLOBALS['dirroot'] . '/modules/gifts/fonctions.php';
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_gifts_module_active()) {
		include($GLOBALS['fonctionsgift']);
	}
	// Module ecotaxe
	$GLOBALS['fonctionsecotaxe'] = $GLOBALS['dirroot'] . "/modules/ecotaxe/fonctions.php";
	if (is_module_ecotaxe_active()) {
		// Utilisé par Product => nécessaire pour rpc.php => Pas d'exclusion LOAD_NO_OPTIONAL_MODULE
		include($GLOBALS['fonctionsecotaxe']);
	}
	// Module de gestion du lexique
	$GLOBALS['fonctionslexique_admin'] = $GLOBALS['dirroot'] . "/modules/lexique/administrer/fonctions.php";
	$GLOBALS['fonctionslexique'] = $GLOBALS['dirroot'] . "/modules/lexique/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_lexique_module_active()) {
		include($GLOBALS['fonctionslexique']);
	}
	// Module SoColissimo SIMPLICITE
	$GLOBALS['fonctionssocolissimo'] = $GLOBALS['dirroot'] . "/modules/socolissimo/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_socolissimo_module_active()) {
		include($GLOBALS['fonctionssocolissimo']);
	}
	// Module ICI relais
	$GLOBALS['fonctionsicirelais'] = $GLOBALS['dirroot'] . "/modules/icirelais/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_icirelais_module_active()) {
		include($GLOBALS['fonctionsicirelais']);
		$GLOBALS['modules_lang_directory_array'][] = '/modules/icirelais/lang/';
	}
	// Module TNT
	$fonctionstnt = $GLOBALS['dirroot'] . "/modules/tnt/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_tnt_module_active()) {
		include($fonctionstnt);
		include_once($GLOBALS['dirroot'] . "/modules/tnt/class/Tnt.php");
		$GLOBALS['modules_lang_directory_array'][] = '/modules/tnt/lang/';
	}
	// Module de Wanewsletter
	$GLOBALS['fonctionswanewsletter'] = $GLOBALS['dirroot'] . "/modules/newsletter/peel/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_module_wanewsletter_active()) {
		include($GLOBALS['fonctionswanewsletter']);
	}
	// Module de Blog
	$GLOBALS['fonctionsblog'] = $GLOBALS['dirroot'] . "/modules/blog/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_module_blog_active()) {
		include($GLOBALS['fonctionsblog']);
		$GLOBALS['modules_lang_directory_array'][] = '/modules/blog/lang/';
	}
	// Module de Butterflive
	$GLOBALS['fonctionsbutterflive'] = $GLOBALS['dirroot'] . "/modules/butterflive/utils.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && !defined('IN_PEEL_ADMIN') && is_butterflive_module_active()) {
		include($GLOBALS['fonctionsbutterflive']);
	}
	// Module de Payback
	$GLOBALS['fonctionspayback'] = $GLOBALS['dirroot'] . "/modules/payback/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_payback_module_active()) {
		$GLOBALS['modules_lang_directory_array'][] = '/modules/payback/lang/';
		include($GLOBALS['fonctionspayback']);
	}
	// Module d'affichage des éléments précédent et suivant sur les fiches produit
	$GLOBALS['fonctionsprecedentsuivant'] = $GLOBALS['dirroot'] . "/modules/precedent_suivant/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_module_precedent_suivant_active()) {
		include($GLOBALS['fonctionsprecedentsuivant']);
	}
	// Module captcha
	$GLOBALS['fonctionscaptcha'] = $GLOBALS['dirroot'] . "/modules/captcha/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_captcha_module_active()) {
		include($GLOBALS['fonctionscaptcha']);
	}
	// Module de gestion des promotions par catégorie
	$GLOBALS['fonctionsfaq'] = $GLOBALS['dirroot'] . "/modules/faq/fonctions.php";
	// Module "donner son avis"
	$GLOBALS['fonctionsavis'] = $GLOBALS['dirroot'] . "/modules/avis/fonctions.php";
	// Modules comparateur de prix
	$GLOBALS['fonctionscomparateur'] = $GLOBALS['dirroot'] . "/modules/comparateur/administrer/fonctions.php";
	// Module gestion des profils
	$GLOBALS['fonctionsprofile'] = $GLOBALS['dirroot'] . "/modules/profil/administrer/fonctions.php";
	// Module de gestion des lots
	$GLOBALS['fonctionsadminlot'] = $GLOBALS['dirroot'] . "/modules/lot/administrer/fonctions.php";
	$GLOBALS['fonctionslot'] = $GLOBALS['dirroot'] . "/modules/lot/fonctions.php";
	// Module des bons anniversaires
	$GLOBALS['fonctionsbirthday'] = $GLOBALS['dirroot'] . '/modules/birthday/administrer/bons_anniversaires.php';
	// Module des bons clients
	$GLOBALS['fonctionsgoodclients'] = $GLOBALS['dirroot'] . '/modules/good_clients/administrer/bons_clients.php';
	// Module de gestion des groupes
	$GLOBALS['fonctionsgroups'] = $GLOBALS['dirroot'] . "/modules/groups/administrer/fonctions.php";
	// Module de generation de facture pdf
	$GLOBALS['fonctionsgenerepdf'] = $GLOBALS['dirroot'] . "/modules/facture_advanced/administrer/genere_pdf.php";
	// Module de statistiques
	$GLOBALS['fonctionsstats'] = $GLOBALS['dirroot'] . "/modules/statistiques/administrer/statcommande.php";
	// Module d'interconnexion avec Expeditor
	$GLOBALS['fonctionsexpeditor'] = $GLOBALS['dirroot'] . "/modules/expeditor/administrer/expeditor.php";
	// Module de duplication de produit
	$GLOBALS['fonctionsduplicate'] = $GLOBALS['dirroot'] . "/modules/duplicate/administrer/fonctions.php";
	// Module d'affichage d'interstitiel de publicité à l'arrivée d'un nouvel utilisateur sur le site
	$GLOBALS['fonctionswelcomead'] = $GLOBALS['dirroot'] . "/modules/welcome_ad/fonctions.php";
	// Module de graphiques flash
	$GLOBALS['fonctionschart'] = $GLOBALS['dirroot'] . "/modules/chart/open-flash-chart.php";
	// Module KEKOLI
	$GLOBALS['fonctionskekoli'] = $GLOBALS['dirroot'] . "/modules/kekoli/administrer/fonctions.php";
	// Module de devis
	$GLOBALS['fonctionsdevis'] = $GLOBALS['dirroot'] . "/modules/devis/fonctions.php";
	// Module de téléchargement
	$GLOBALS['fonctionstelechargement'] = $GLOBALS['dirroot'] . "/modules/telechargement/administrer/fonctions.php";
	// Module de gestion des partenaires
	$GLOBALS['fonctionspartenaires'] = $GLOBALS['dirroot'] . "/modules/partenaires/fonctions.php";
	// Module de gestion des références
	$GLOBALS['fonctionsreferences'] = $GLOBALS['dirroot'] . "/modules/references/fonctions.php";
	// Module du google map des revendeurs
	$GLOBALS['fonctionsresellermap'] = $GLOBALS['dirroot'] . "/modules/reseller_map/fonctions.php";
	// Module de map
	$GLOBALS['fonctionsmap'] = $GLOBALS['dirroot'] . "/modules/maps/fonctions.php";
	// Module Clients
	$GLOBALS['fonctionsclients'] = $GLOBALS['dirroot'] . "/modules/clients/fonctions.php";
	// Module Photodesk
	$GLOBALS['fonctionsphotodesk'] = $GLOBALS['dirroot'] . "/modules/photodesk/fonctions.php";
	// Module ariane_panier
	$GLOBALS['fonctionsarianepanier'] = $GLOBALS['dirroot'] . "/modules/ariane_panier/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_module_ariane_panier_active()) {
		include($GLOBALS['fonctionsarianepanier']);
	}
	// Module de gestion des vacances administrateur / fournisseurs
	$GLOBALS['fonctionsvacances'] = $GLOBALS['dirroot'] . "/modules/vacances/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_module_vacances_active()) {
		include($GLOBALS['fonctionsvacances']);
	}
	// Module de conservation du panier
	$GLOBALS['fonctionscartpreservation'] = $GLOBALS['dirroot'] . "/modules/cart_preservation/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_cart_preservation_module_active()) {
		include($GLOBALS['fonctionscartpreservation']);
	}

	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_welcome_ad_module_active()) {
		include($GLOBALS['fonctionswelcomead']);
	}
	// Module d'affichage de popup lors de l'ajout au caddie
	$GLOBALS['fonctionscartpoup'] = $GLOBALS['dirroot'] . "/modules/cart_popup/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_cart_popup_module_active()) {
		include($GLOBALS['fonctionscartpoup']);
	}
	// FIN DES MODULES OPTIONNELS COMPRIS DANS PEEL PREMIUM
	// Module de gestion des moyens de payment par produit
	$GLOBALS['fonctionspaymentbyproduct'] = $GLOBALS['dirroot'] . "/modules/payment_by_product/fonctions.php";
	$GLOBALS['fonctionspaymentbyproduct_admin'] = $GLOBALS['dirroot'] . "/modules/payment_by_product/administrer/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_payment_by_product_module_active()) {
		include($GLOBALS['fonctionspaymentbyproduct']);
	}
	// Librairies de paiement bancaire en fonction des installations
	$GLOBALS['fonctionsatos'] = $GLOBALS['dirroot'] . "/modules/sips/fonctions.php";
	$GLOBALS['fonctionscmcic'] = $GLOBALS['dirroot'] . "/modules/cmcic/cmcic.php";
	$GLOBALS['fonctionsbluepaid'] = $GLOBALS['dirroot'] . "/modules/bluepaid/fonctions.php";
	$GLOBALS['fonctionsfianet'] = $GLOBALS['dirroot'] . "/modules/fianet/fonctions.php";
	$GLOBALS['fonctionsfianet_sac'] = $GLOBALS['dirroot'] . "/modules/fianet_sac/fonctions.php";
	$GLOBALS['fonctionsogone'] = $GLOBALS['dirroot'] . "/modules/ogone/fonctions.php";
	$GLOBALS['fonctionsomnikassa'] = $GLOBALS['dirroot'] . "/modules/omnikassa/fonctions.php";
	$GLOBALS['fonctionspaybox'] = $GLOBALS['dirroot'] . "/modules/paybox/fonctions.php";
	$GLOBALS['fonctionsspplus'] = $GLOBALS['dirroot'] . "/modules/spplus/fonctions.php";
	$GLOBALS['fonctionssystempay'] = $GLOBALS['dirroot'] . "/modules/systempay/functions.php";
	$GLOBALS['fonctionsmoneybookers'] = $GLOBALS['dirroot'] . "/modules/moneybookers/fonctions.php";
	$GLOBALS['fonctionspaypal'] = $GLOBALS['dirroot'] . "/modules/paypal/fonctions.php";
	// Module d'envoi de newsletter
	// Modules optionnels, non GPL : Contactez PEEL sur https://www.peel.fr/ ou au 01 75 43 67 97
	$GLOBALS['newsletterfile'] = $GLOBALS['dirroot'] . "/modules/newsletter/fonctions/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && file_exists($GLOBALS['newsletterfile'])) {
		include($GLOBALS['newsletterfile']);
	}
	// Module de gestion des annonces
	if (is_annonce_module_active()) {
		$GLOBALS['modules_lang_directory_array'][] = '/modules/annonces/lang/';
		include($GLOBALS['fonctionsannonces']);
		include($GLOBALS['dirroot'] . "/modules/annonces/display_annonce.php");
		include($GLOBALS['dirroot'] . "/modules/annonces/class/Annonce.php");
		if (defined('IN_PEEL_ADMIN') || defined('IN_CRON')) {
			// On inclue ces fonctions sur toute l'administration pour pouvoir manipuler des notions d'annonces
			include($GLOBALS['dirroot'] . "/modules/annonces/administrer/fonctions.php");
		}
	}
	// Module de gestion des abonnements
	$GLOBALS['fonctionsabonnement'] = $GLOBALS['dirroot'] . "/modules/abonnement/fonctions.php";
	$GLOBALS['fonctionsabonnement_admin'] = $GLOBALS['dirroot'] . "/modules/abonnement/administrer/fonctions.php";
	if (is_abonnement_module_active()) {
		include($GLOBALS['fonctionsabonnement']);
		$GLOBALS['modules_lang_directory_array'][] = '/modules/abonnement/lang/';
		if (defined('IN_PEEL_ADMIN')) {
			// On inclue ces fonctions sur toute l'administration pour pouvoir manipuler des notions d'abonnement
			include($GLOBALS['fonctionsabonnement_admin']);
		}
	}
	// Module sauvegarde recherche
	$fonctionsuser_alerts = $GLOBALS['dirroot'] . "/modules/user_alerts/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_user_alerts_module_active()) {
		include($fonctionsuser_alerts);
	}
	// Module relance avancée
	$GLOBALS['fonctionrelance_avance'] = $GLOBALS['dirroot'] . "/modules/relance_avance/administrer/fonctions.php";
	// Module spam
	$GLOBALS['fonctionsspam'] = $GLOBALS['dirroot'] . "/modules/spam/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_spam_module_active()) {
		include($GLOBALS['fonctionsspam']);
	}
	// Module de gestion des carrousels
	$GLOBALS['fonctionscarrousel'] = $GLOBALS['dirroot'] . '/modules/carrousel/fonctions.php';
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_carrousel_module_active()) {
		include($GLOBALS['fonctionscarrousel']);
	}
	// Module de fonctionnalités facebook
	$GLOBALS['fonctionsfacebook'] = $GLOBALS['dirroot'] . '/modules/facebook/fonctions.php';
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_facebook_module_active()) {
		include($GLOBALS['fonctionsfacebook']);
	}
	// Module de fonctionnalités facebook
	$GLOBALS['fonctionfacebookconnect'] = $GLOBALS['dirroot'] . "/modules/facebook_connect/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_facebook_connect_module_active()) {
		include($GLOBALS['fonctionfacebookconnect']);
	}
	// Module sign_in_twitter
	$GLOBALS['fonctionssignintwitter'] = $GLOBALS['dirroot'] . "/modules/sign_in_twitter/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_sign_in_twitter_module_active()) {
		$GLOBALS['modules_lang_directory_array'][] = '/modules/sign_in_twitter/lang/';
		include($GLOBALS['fonctionssignintwitter']);
	}
	// Module openid
	$GLOBALS['fonctionsopenid'] = $GLOBALS['dirroot'] . "/modules/openid/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_openid_module_active()) {
		include($GLOBALS['fonctionsopenid']);
	}
	// Module googlefriendconnect
	$GLOBALS['fonctionsgooglefriendconnect'] = $GLOBALS['dirroot'] . "/modules/googlefriendconnect/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_googlefriendconnect_module_active()) {
		include($GLOBALS['fonctionsgooglefriendconnect']);
	}
	// Module interconnexion téléphonie
	$GLOBALS['fonctionsphonecti'] = $GLOBALS['dirroot'] . "/modules/phone_cti/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_phone_cti_module_active()) {
		include($GLOBALS['fonctionsphonecti']);
	}
	// Module de gestion de la vente en gros
	$GLOBALS['fonctionsconditionnement'] = $GLOBALS['dirroot'] . '/modules/conditionnement/fonctions.php';
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_conditionnement_module_active()) {
		include($GLOBALS['fonctionsconditionnement']);
	}
	// Module vitrine
	$GLOBALS['fonctionsadministrervitrine'] = $GLOBALS['dirroot'] . "/modules/vitrine/administrer/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_vitrine_module_active()) {
		$GLOBALS['modules_lang_directory_array'][] = '/modules/vitrine/lang/';
		include($GLOBALS['fonctionsvitrine']);
		if (defined('IN_PEEL_ADMIN')) {
			include($GLOBALS['fonctionsadministrervitrine']);
		}
	}
	// Module Commerciale
	$GLOBALS['fonctionscommerciale'] = $GLOBALS['dirroot'] . "/modules/commerciale/administrer/fonctions.php";
	if (defined('IN_PEEL_ADMIN') && !defined('LOAD_NO_OPTIONAL_MODULE') && is_commerciale_module_active()) {
		include($GLOBALS['fonctionscommerciale']);
	}
	// Module Webmail
	$GLOBALS['fonctionswebmail'] = $GLOBALS['dirroot'] . "/modules/webmail/fonctions.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_webmail_module_active()) {
		include($GLOBALS['fonctionswebmail']);
	}
	// Module Cron
	$GLOBALS['fonctionscrons'] = $GLOBALS['dirroot'] . "/modules/crons/crons.php";
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_crons_module_active()) {
		include($GLOBALS['dirroot'] . "/modules/crons/functions/emails.php");
	}
}
if (!IN_INSTALLATION) {
	// Initialisation de l'objet caddie si nécessaire
	if (!isset($_SESSION['session_caddie']) || empty($_SESSION['session_caddie'])) {
		$_SESSION['session_caddie'] = new Caddie(get_current_user_promotion_percentage());
	}
	// Initialisation de l'objet tnt si le module existe
	if (!defined('LOAD_NO_OPTIONAL_MODULE') && is_tnt_module_active()) {
		$GLOBALS['web_service_tnt'] = new Tnt($GLOBALS['site_parameters']['tnt_username'], $GLOBALS['site_parameters']['tnt_password']);
	}
	// Test pour savoir si une commande est en cours.
	// Si tel est le cas, on vérifie son statut de paiement et si elle est payée, alors on réinitialise le caddie
	if (!empty($_SESSION['session_caddie']->commande_id)) {
		$query_com = query("SELECT *
			FROM peel_commandes
			WHERE id ='" . intval($_SESSION['session_caddie']->commande_id) . "' AND id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'");
		$result_com = fetch_object($query_com);
		if ($result_com && in_array($result_com->id_statut_paiement, array('2', '3'))) {
			$_SESSION['session_caddie']->init();
			unset($_SESSION['session_commande']);
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
if (!defined('SKIP_SET_LANG')) {
	// On charge les fichiers de langue des modules
	set_lang_configuration_and_texts($_SESSION['session_langue'], vb($GLOBALS['load_default_lang_files_before_main_lang_array_by_lang'][$_SESSION['session_langue']]), false, true, false);
}
if ((!IN_INSTALLATION || IN_INSTALLATION >= 5) && !defined('LOAD_NO_OPTIONAL_MODULE') && !empty($GLOBALS['site_parameters']['load_site_specific_files_after_others'])) {
	// Fichiers nécessitant que les fonctions d'URL soient toutes définies et les constantes de langue
	foreach($GLOBALS['site_parameters']['load_site_specific_files_after_others'] as $this_file_relative_path) {
		if(file_exists($GLOBALS['dirroot'] . $this_file_relative_path)) {
			include($GLOBALS['dirroot'] . $this_file_relative_path);
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
		redirect_and_die($GLOBALS['wwwroot'] . '/membre.php');
	}
}
?>
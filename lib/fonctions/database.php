<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database.php 36232 2013-04-05 13:16:01Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

// If you want to use a database server different from MySQL,
// you can change the following functions which are called from everywhere in PEEL
/**
 * db_connect()
 *
 * @param object $database_object
 * @param string $database_name
 * @param string $serveur_mysql
 * @param string $utilisateur_mysql
 * @param string $mot_de_passe_mysql
 * @return
 */
function db_connect(&$database_object, $database_name = null, $serveur_mysql = null, $utilisateur_mysql = null, $mot_de_passe_mysql = null)
{
	// Connexion à la BDD
	if(empty($serveur_mysql)) {
		$serveur_mysql = $GLOBALS['serveur_mysql'];
	}
	if(empty($serveur_mysql) || $serveur_mysql=='votre_serveur_mysql') {
		return null;
	}
	if(empty($utilisateur_mysql)) {
		$utilisateur_mysql = $GLOBALS['utilisateur_mysql'];
	}
	if(empty($mot_de_passe_mysql)) {
		$mot_de_passe_mysql = $GLOBALS['mot_de_passe_mysql'];
	}
	if(empty($database_name) && $database_name!==false) {
		$database_name = $GLOBALS['nom_de_la_base'];
	}
	if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
		$port = @ini_get("mysqli.default_port");
		if(empty($port)) {
			// Port par défaut
			$port = 3306;
		}
		$socket = @ini_get("mysqli.default_socket");
		if($socket === false) {
			// Socket par défaut
			$socket = null;
		}
		// Gestion des connexions du type server:socket ou server:port
		$server_infos = explode(':',$serveur_mysql);
		if(isset($server_infos[1])) {
			if(is_numeric($server_infos[1])){
				$port = $server_infos[1];
			} else {
				$socket = $server_infos[1];
			}
		}
		if(isset($GLOBALS['site_parameters']['use_database_permanent_connection']) && ($GLOBALS['site_parameters']['use_database_permanent_connection'] === true || ($GLOBALS['site_parameters']['use_database_permanent_connection'] == 'local' && (strpos($GLOBALS['wwwroot'], '://localhost')!==false || strpos($GLOBALS['wwwroot'], '://127.0.0.1')!==false)))) {
			// L'utilisation de pconnect est souvent plus rapide, mais peut créer des problèmes divers
			// Pour le travail en local sur un PC winbows, l'amélioration de performance peut être très grande
			$database_object = new mysqli('p:'.$server_infos[0], $utilisateur_mysql, $mot_de_passe_mysql, '', $port, $socket);
		} else {
			$database_object = new mysqli($server_infos[0], $utilisateur_mysql, $mot_de_passe_mysql, '', $port, $socket);
		}
		if (mysqli_connect_error()) {
			$error_no = mysqli_connect_errno();
			$error_text = mysqli_connect_error();
		}
	} else {
		$database_object = mysql_connect($serveur_mysql, $utilisateur_mysql, $mot_de_passe_mysql);
	}
	if(!empty($error_no)) {
		$sujet_du_mail = 'MySQL connection problem (' . mysqli_connect_errno() . '): '.mysqli_connect_error();
		$contenu_du_mail = "The page " . $_SERVER['REQUEST_URI'] . " had an error while trying to connect to MySQL on " . $serveur_mysql . " - the user is " . $utilisateur_mysql . ". Please check if MySQL is currently launched and if the connection parameters are valid.";
		$contenu_du_mail .= "\n\nLa page " . $_SERVER['REQUEST_URI'] . " a provoqué une erreur lors de sa tentative de connexion à MySQL situé sur le serveur " . $serveur_mysql . " - l'utilisateur est " . $utilisateur_mysql . ". Il faudrait vérifier si le serveur MySQL est actuellement lancé et si les paramètres de connexion sont valides.";
		if (!empty($support)) {
			send_email($support, $sujet_du_mail, $contenu_du_mail, null, null, 'html', '', null);
		}
		if (!empty($display_warning_if_connection_problem)) {
			echo $sujet_du_mail;
		}
		trigger_error($serveur_mysql. ' - ' .$sujet_du_mail, E_USER_NOTICE);
		die();
	}
	if(!empty($database_name)) {
		$selection_de_la_base = select_db($database_name, $database_object);
	}
	return $database_object;
}

/**
 * select_db()
 *
 * @param string $database_name
 * @param object $database_object
 * @param boolean $continue_if_error
 * @return
 */
function select_db($database_name, &$database_object, $continue_if_error = false)
{
	if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
		$selection_de_la_base = $database_object->select_db($database_name);
	} else {
		$selection_de_la_base = mysql_select_db($database_name, $database_object);
	}
	if (!$selection_de_la_base && !$continue_if_error) {
		$sujet_du_mail = "Database selection problem - Problème de sélection de la base de données";
		$contenu_du_mail = "The page " . $_SERVER['REQUEST_URI'] . " had an error while trying to connect to MySQL database " . $database_name;
		$contenu_du_mail .= "\n\nLa page " . $_SERVER['REQUEST_URI'] . " a provoqué une erreur lors de sa tentative de sélection de la base de données " . $database_name . ".";
		if (!empty($support)) {
			send_email($support, $sujet_du_mail, $contenu_du_mail, null, null, 'html', '', null);
		}
		$contenu_display = 'MySQL database database_object problem';
		if (!empty($display_warning_if_database_object_problem)) {
			echo $contenu_display;
		}
		trigger_error($contenu_display . ' ' . $database_name, E_USER_NOTICE);
		die();
	}
	// Définition des paramètres de connexion à MySQL
	if (GENERAL_ENCODING == 'utf-8') {
		query("SET NAMES 'utf8'", false, $database_object);
	} elseif (GENERAL_ENCODING == 'iso-8859-1') {
		query("SET NAMES 'iso-8859-1'", false, $database_object);
	} else {
		// Please check if you need to convert GENERAL_ENCODING encoding name to mysql name
		query("SET NAMES '" . GENERAL_ENCODING . "'", false, $database_object);
	}
	return $selection_de_la_base;
}

/**
 * The query() function is meant to be called anywhere you want to make a query.
 * Thus, it allows a code independant from the database server functions.
 *
 * @param string $query
 * @param boolean $die_if_error If you want by default the program to stop if an error occurs, put $die_if_error at true by default
 * @param mixed $database_object
 * @param mixed $silent_if_error
 * @param mixed $security_sql_filter
 * @return It returns the link to the result of the query
 * @return
 */
function query($query, $die_if_error = false, $database_object = null, $silent_if_error = false, $security_sql_filter = true)
{
	if (defined('IN_PEEL_ADMIN') && a_priv('demo') && ((strpos(strtolower($query), 'insert ') !== false && strpos(strtolower($query), 'into ') !== false) || strpos(strtolower($query), 'update ') !== false || strpos(strtolower($query), 'delete ') !== false || strpos(strtolower($query), 'alter ') !== false)) {
		// L'utilisateur ayant le profil "demo" ne peut pas faire de modification des données
		return false;
	}
	if ($security_sql_filter && (strpos(strtolower($query), 'information_schema') !== false || strpos(strtolower($query), 'loadfile') !== false)) {
		// On empêche l'exécution de requêtes contenant certains mots clé
		return false;
	}
	if(empty($database_object)) {
		$database_object = &$GLOBALS['database_object'];
	}
	if (defined('PEEL_DEBUG') && PEEL_DEBUG == true) {
		$start_time = microtime_float();
	}
	$i = 0;
	while (empty($query_values)) {
		if ($i > 0) {
			// Si on veut réessayer la requête, on regarde si c'est adapté de réinitialiser la connexion
			if (empty($error_number) || in_array($error_number, array(111, 126, 127, 141, 144, 145, 1034, 1053, 1137, 1152, 1154, 1156, 1184, 1205, 2003, 2006, 2013))) {
				// Liste des erreurs : http://dev.mysql.com/doc/mysql/fr/Error-messages.html
				// par ailleurs : 2013 : Lost connection to MySQL server during query
				// 2006 MySQL server has gone away
				if(!empty($database_object)) {
					// On se reconnecte après une petite pause pour laisser au serveur la possibilité de gérer un problème
					sleep(1);
				}
				// On force une reconnexion
				db_connect($database_object);
			} else {
				// Si l'erreur n'est pas reconnue, on s'arrête là
				break;
			}
		}
		unset($error_number);
		unset($error_name);
		if(!empty($database_object)) {
			if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
				if ($silent_if_error) {
					$query_values = @$database_object->query($query);
				} else {
					$query_values = $database_object->query($query);
				}
			} else {
				if ($silent_if_error) {
					$query_values = @mysql_query($query, $database_object);
				} else {
					$query_values = @mysql_query($query, $database_object);
				}
			}
		}
		if (empty($query_values) && !empty($database_object)) {
			// Si problème dans la requête, on récupère les codes d'erreur
			if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
				$error_number = $database_object->errno;
				$error_name = $database_object->error;
			} else {
				$error_number = mysql_errno($database_object);
				$error_name = mysql_error($database_object);
			}
		}
		$i++;
		if ($i >= 2) {
			break;
		}
	}
	if (defined('PEEL_DEBUG') && PEEL_DEBUG == true) {
		$end_time = microtime_float();
		$GLOBALS['peel_debug'][] = array('sql' => $query, 'duration' => $end_time - $start_time);
	}
	if (!empty($query_values)) {
		return $query_values;
	} else {
		if (!$silent_if_error) {
			$error_message = vb($GLOBALS['STR_SQL_ERROR']) . ' - ' . $query . " - Error number " . vb($error_number) . ' - ' . vb($error_name) . " - " . vb($GLOBALS['STR_PAGE']) . ' ' . vb($_SERVER['REQUEST_URI']) . ' - IP ' . vb($_SERVER['REMOTE_ADDR']);
			if (!empty($GLOBALS['display_errors'])) {
				if (a_priv('admin*', false) && empty($GLOBALS['display_errors'])) {
					// Erreurs pas visibles => on rend quand même visible si on est loggué en administrateur
					echo '[admin info : ' . $error_message . ']<br />';
				}
			}
			trigger_error($error_message , E_USER_NOTICE);
		}
		if ($die_if_error) {
			die();
		} else {
			return false;
		}
	}
}

/**
 * fetch_row()
 *
 * @param mixed $query_result
 * @return
 */
function fetch_row($query_result)
{
	if (!empty($query_result)) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			return $query_result->fetch_row();
		} else {
			return mysql_fetch_row($query_result);
		}
	} else {
		return null;
	}
}

/**
 * fetch_assoc()
 *
 * @param mixed $query_result
 * @return
 */
function fetch_assoc($query_result)
{
	if (!empty($query_result)) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			return $query_result->fetch_assoc();
		} else {
			return mysql_fetch_assoc($query_result);
		}
	} else {
		return null;
	}
}

/**
 * fetch_object()
 *
 * @param mixed $query_result
 * @return
 */
function fetch_object($query_result)
{
	if (!empty($query_result)) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			return $query_result->fetch_object();
		} else {
			return mysql_fetch_object($query_result);
		}
	} else {
		return null;
	}
}

/**
 * num_rows()
 *
 * @param mixed $query_result
 * @return
 */
function num_rows($query_result)
{
	if (!empty($query_result)) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			return $query_result->num_rows;
		} else {
			return mysql_num_rows($query_result);
		}
	} else {
		return null;
	}
}

/**
 * insert_id()
 *
 * @return
 */
function insert_id($database_object = null)
{
	if (empty($database_object)) {
		$database_object = &$GLOBALS['database_object'];
	}
	if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
		return $database_object->insert_id;
	} else {
		return mysql_insert_id();
	}
}

/**
 * affected_rows()
 *
 * @return
 */
function affected_rows($database_object = null)
{
	if(empty($database_object)) {
		$database_object = &$GLOBALS['database_object'];
	}
	if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
		return $database_object->affected_rows;
	} else {
		return mysql_affected_rows();
	}
}

/**
 * real_escape_string()
 *
 * @param mixed $value String or array
 * @return
 */
function real_escape_string($value)
{
	if (is_array($value)) {
		foreach($value as $this_key => $this_value) {
			$value[$this_key] = real_escape_string($this_value);
		}
	} elseif(!empty($GLOBALS['database_object'])) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			$value = $GLOBALS['database_object']->real_escape_string($value);
		} else {
			$value = mysql_real_escape_string($value);
		}
	} else {
		$value = null;
	}
	return $value;
}

/**
 * Protège les données pour insertion dans MySQL
 * ET supprime les tags HTML pour protéger de toute sorte de XSS
 *
 * @param mixed $value
 * @param mixed $allowed_tags
 * @return
 */
function nohtml_real_escape_string($value, $allowed_tags = null)
{
	if (is_array($value)) {
		foreach($value as $this_key => $this_value) {
			$value[$this_key] = nohtml_real_escape_string($this_value, $allowed_tags);
		}
	} elseif(!empty($GLOBALS['database_object'])) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			$value = $GLOBALS['database_object']->real_escape_string(@String::strip_tags($value, $allowed_tags));
		} else {
			$value = mysql_real_escape_string(@String::strip_tags($value, $allowed_tags));
		}
	} else {
		$value = null;
	}
	return $value;
}

/**
 * Applique real_escape_string dans le cas où on n'insère qu'un seul mot, de moins de 30 caractères
 *
 * @param mixed $value
 * @return
 */
function word_real_escape_string($value)
{
	if (is_array($value)) {
		foreach($value as $this_key => $this_value) {
			$value[$this_key] = word_real_escape_string($this_value);
		}
	} elseif(!empty($GLOBALS['database_object'])) {
		$value = String::substr($value, 0, min(String::strpos(str_replace(array('+', ',', ';', '(', ')', '!', '=', '`', '|', '&'), ' ', $value) . ' ', ' '), 60));
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			$value = $GLOBALS['database_object']->real_escape_string($value);
		} else {
			$value = mysql_real_escape_string($value);
		}
	} else {
		$value = null;
	}
	return $value;
}

/**
 * create_sql_from_array()
 *
 * @param mixed $array
 * @return
 */
function create_sql_from_array($array)
{
	$sql = "";
	$i = 0;

	foreach($array as $key => $value) {
		if ($i > 0) {
			$sql .= ', ';
		}
		$sql .= "`" . word_real_escape_string($key) . "`= '" . real_escape_string($value) . "'";
		$i++;
	}

	return $sql;
}

/**
 * get_table_fields()
 *
 * @param mixed $table_name
 * @param mixed $database_object
 * @param boolean $silent_if_error
 * @return
 */
function get_table_fields($table_name, $database_object = null, $silent_if_error = false)
{
	$sql = "SHOW COLUMNS FROM `" . word_real_escape_string($table_name) . "`";
	$query = query($sql, false, $database_object, $silent_if_error);
	while ($result = fetch_assoc($query)) {
		$fields[] = $result;
	}
	if (empty($fields)) {
		return null;
	} else {
		return $fields;
	}
}

/**
 * get_table_fields()
 *
 * @param mixed $table_name
 * @param mixed $link_identifier
 * @param boolean $silent_if_error
 * @return
 */
function get_table_field_names($table_name, $link_identifier = null, $silent_if_error = false)
{
	$fields = get_table_fields($table_name, $link_identifier, $silent_if_error);
	if (empty($fields)) {
		return null;
	} else {
		foreach($fields as $this_field) {
			$results[] = $this_field['Field'];
		}
		return $results;
	}
}

/**
 * get_table_index()
 *
 * @param mixed $table_name
 * @param mixed $link_identifier
 * @param boolean $silent_if_error
 * @return
 */
function get_table_index($table_name, $link_identifier = null, $silent_if_error = false)
{
	$sql = "SHOW INDEX FROM `" . word_real_escape_string($table_name) . "`";
	$query = query($sql, false, $link_identifier, $silent_if_error);
	while ($result = fetch_assoc($query)) {
		$fields[] = $result;
	}
	if (empty($fields)) {
		return array();
	} else {
		foreach($fields as $this_field) {
			$results[] = $this_field['Column_name'];
		}
		return $results;
	}
}

/**
 * db_close()
 *
 * @return
 */
function db_close($database_object = null)
{
	if(empty($database_object)) {
		$database_object = &$GLOBALS['database_object'];
	}
	if(!empty($database_object)) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			$database_object->close();
		} else {
			mysql_close($GLOBALS['database_object']);	
		}
	}
}

?>
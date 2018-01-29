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
// $Id: database.php 55332 2017-12-01 10:44:06Z sdelaporte $
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
 * @param boolean $continue_if_error
 * @return
 */
function db_connect(&$database_object, $database_name = null, $serveur_mysql = null, $utilisateur_mysql = null, $mot_de_passe_mysql = null, $continue_if_error = false)
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
	if(!empty($error_no) && !$continue_if_error) {
		$sujet_du_mail = 'MySQL connection problem (' . mysqli_connect_errno() . '): '.mysqli_connect_error();
		$contenu_du_mail = "The page " . $_SERVER['REQUEST_URI'] . " had an error while trying to connect to MySQL on " . $serveur_mysql . " - the user is " . $utilisateur_mysql . ". Please check if MySQL is currently launched and if the connection parameters are valid.";
		$contenu_du_mail .= "\n\nLa page " . $_SERVER['REQUEST_URI'] . " a provoqué une erreur lors de sa tentative de connexion à MySQL situé sur le serveur " . $serveur_mysql . " - l'utilisateur est " . $utilisateur_mysql . ". Il faudrait vérifier si le serveur MySQL est actuellement lancé et si les paramètres de connexion sont valides.";
		if (!empty($GLOBALS['support'])) {
			send_email($GLOBALS['support'], $sujet_du_mail, $contenu_du_mail, null, null, null, '', null);
		}
		if (!empty($GLOBALS['site_parameters']['display_warning_if_connection_problem'])) {
			echo $sujet_du_mail;
		}
		trigger_error($serveur_mysql. ' - ' .$sujet_du_mail, E_USER_NOTICE);
		die();
	}
	if(!empty($database_name)) {
		$GLOBALS['selection_de_la_base'] = select_db($database_name, $database_object, $continue_if_error);
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
		$GLOBALS['selection_de_la_base'] = $database_object->select_db($database_name);
	} else {
		$GLOBALS['selection_de_la_base'] = mysql_select_db($database_name, $database_object);
	}
	if (!$GLOBALS['selection_de_la_base'] && !$continue_if_error) {
		if(is_object($database_object) && !empty($database_object->error)) {
			$contenu_display = $database_object->error;
		} else {
			$contenu_display = 'MySQL database selection problem: ' . $database_name;
		}
		$sujet_du_mail = "Database selection problem";
		$contenu_du_mail = "The page " . $_SERVER['REQUEST_URI'] . " had an error while trying to connect to MySQL database - " . $contenu_display;
		if (!empty($GLOBALS['support'])) {
			send_email($GLOBALS['support'], $sujet_du_mail, $contenu_du_mail, null, null, null, '', null);
		}
		if (!empty($display_warning_if_database_object_problem)) {
			echo $contenu_display;
		}
		trigger_error($contenu_display, E_USER_ERROR);
		// Le script s'arrête sur une fatal error
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
	return $GLOBALS['selection_de_la_base'];
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
	if(empty($query)) {
		return false;
	}
	if(!empty($GLOBALS['database_wrong_version'])) {
		// Dans le contexte d'une migration, on ne veut pas que les erreurs s'affiche pour permettre à l'utilisateur de se connecter à l'administration est au fichier update.php. Lorsque les erreurs s'affichent on
		$silent_if_error = true;
	}
	if (defined('IN_PEEL_ADMIN') && a_priv('demo') && ((strpos(strtolower($query), 'insert ') !== false && strpos(strtolower($query), 'into ') !== false) || strpos(strtolower($query), 'update ') !== false || strpos(strtolower($query), 'delete ') !== false || strpos(strtolower($query), 'alter ') !== false)) {
		// L'utilisateur ayant le profil "demo" ne peut pas faire de modification des données
		return false;
	}
	if ($security_sql_filter && (strpos(strtolower($query), 'information_schema') !== false || strpos(strtolower($query), 'loadfile') !== false || strpos(strtolower($query), 'union all') !== false || strpos(strtolower($query), 'union select') !== false) || strpos(strtolower($query), 'benchmark(') !== false) {
		// On empêche l'exécution de requêtes contenant certains mots clé
		return false;
	}
	if(empty($database_object)) {
		$database_object = &$GLOBALS['database_object'];
	}
	if (defined('PEEL_DEBUG') && PEEL_DEBUG) {
		$start_time = microtime_float();
	}
	$i = 0;
	unset($GLOBALS['last_query_result']);
	while (empty($GLOBALS['last_query_result'])) {
		if ($i > 0) {
			// Si on veut réessayer la requête, on regarde si c'est adapté de réinitialiser la connexion
			if (empty($error_number) || in_array($error_number, array(111, 126, 127, 141, 144, 145, 1034, 1053, 1137, 1152, 1154, 1156, 1184, 1205, 1317, 2003, 2006, 2013))) {
				// Liste des erreurs : https://dev.mysql.com/doc/refman/5.5/en/error-messages-server.html
				// par ailleurs : 2013 : Lost connection to MySQL server during query
				// 2006 MySQL server has gone away
				if(!empty($database_object)) {
					// On se reconnecte après une petite pause pour laisser au serveur la possibilité de gérer un problème
					sleep(1);
				}
				// On force une reconnexion
				db_connect($database_object);
			} elseif($error_number == 1364 && StringMb::strpos($query, 'sql_mode') === false) {
				// Si problème "Field doesn't have a default values" on passe en mode compatibilité définitivement pour les prochaines pages vues
				set_configuration_variable(array('technical_code' => 'mysql_sql_mode_force', 'string' => 'MYSQL40', 'site_id' => 0, 'origin' => 'auto'), true);
				// Pour le reste de la génération de page, on passe en mode compatibilité
				query("SET @@session.sql_mode='MYSQL40'");
				break;
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
					$GLOBALS['last_query_result'] = @$database_object->query($query);
				} else {
					$GLOBALS['last_query_result'] = $database_object->query($query);
				}
			} else {
				if ($silent_if_error) {
					$GLOBALS['last_query_result'] = @mysql_query($query, $database_object);
				} else {
					$GLOBALS['last_query_result'] = mysql_query($query, $database_object);
				}
			}
		}
		if (empty($GLOBALS['last_query_result']) && !empty($database_object)) {
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
	if (defined('PEEL_DEBUG') && PEEL_DEBUG) {
		$end_time = microtime_float();
		$GLOBALS['peel_debug'][] = array('sql' => $query, 'duration' => $end_time - $start_time, 'start' => $start_time - $GLOBALS['script_start_time']);
	}
	if (!empty($GLOBALS['last_query_result'])) {
		return $GLOBALS['last_query_result'];
	} else {
		if (!$silent_if_error || in_array($error_number, array(1118))) {
			// Si l'erreur est 1118 (Row size too large. The maximum row size for the used table type, not counting BLOBs, is 65535.) qui peut arriver lors d'un ALTER TABLE ADD alors on affiche quand même l'erreur pour meilleure gestion par l'administrateur
			$error_message = vb($GLOBALS['STR_SQL_ERROR']) . ' ' . vb($error_number) . ' - ' . vb($error_name) . " - " . vb($GLOBALS['STR_PAGE']) . ' ' . vb($_SERVER['REQUEST_URI']) . ' - IP ' . vb($_SERVER['REMOTE_ADDR']) . ' - ' . $query . ' - Error number ';
			if (defined('PEEL_DEBUG') && PEEL_DEBUG) {
				$error_message .= print_r(debug_backtrace(), true);
			}
			if (empty($GLOBALS['display_errors']) && a_priv('admin*', false)) {
				// Erreurs pas visibles => on rend quand même visible si on est loggué en administrateur
				echo '[admin info : ' . $error_message . ']<br />';
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
 * @param mixed $fetch_array
 * @return
 */
function fetch_row($query_result = null, $fetch_array = false)
{
	if($query_result === null && isset($GLOBALS['last_query_result'])) {
		$query_result = &$GLOBALS['last_query_result'];
	}
	if (!empty($query_result)) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			if(!$fetch_array) {
				return $query_result->fetch_row();
			} else {
				return $query_result->fetch_array();
			}
		} else {
			if(!$fetch_array) {
				return mysql_fetch_row($query_result);
			} else {
				return mysql_fetch_array($query_result);
			}
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
function fetch_assoc($query_result = null)
{
	if($query_result === null && isset($GLOBALS['last_query_result'])) {
		$query_result = &$GLOBALS['last_query_result'];
	}
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
function fetch_object($query_result = null)
{
	if($query_result === null && isset($GLOBALS['last_query_result'])) {
		$query_result = &$GLOBALS['last_query_result'];
	}
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
function num_rows($query_result = null)
{
	if($query_result === null && isset($GLOBALS['last_query_result'])) {
		$query_result = &$GLOBALS['last_query_result'];
	}
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
			$value = $GLOBALS['database_object']->real_escape_string(@StringMb::strip_tags($value, $allowed_tags));
		} else {
			$value = mysql_real_escape_string(@StringMb::strip_tags($value, $allowed_tags));
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
		$value = StringMb::substr($value, 0, min(StringMb::strpos(str_replace(array('+', ',', ';', '(', ')', '!', '=', '`', '|', '&'), ' ', $value) . ' ', ' '), 60));
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
 * @param string $table_name
 * @param mixed $database_object
 * @param boolean $silent_if_error
 * @param array $fields_filtered
 * @return
 */
function get_table_fields($table_name, $database_object = null, $silent_if_error = false, $fields_filtered = null)
{
	$sql = "SHOW COLUMNS FROM `" . word_real_escape_string($table_name) . "`";
	$query = query($sql, false, $database_object, $silent_if_error);
	while ($result = fetch_assoc($query)) {
		$fields[] = $result;
		$field_names[] = $result['Field'];
	}
	if($fields_filtered !== null && !empty($GLOBALS['site_parameters']['products_check_existing_fields'])) {
		// D'abord on nettoie le tableau $fields_filtered
		foreach($fields_filtered as $this_key => $this_field) {
			$temp = explode(' ', trim($this_field));
			$this_field = $temp[0];
			if(StringMb::strpos($this_field, '.') !== false) {
				$temp = explode('.', $this_field);
				$this_field = $temp[1];
			}
			$fields_filtered[$this_key] = $this_field;
		}
		// On ne garde que les champs de $fields présents dans $fields_filtered
		foreach($fields as $this_key => $this_field) {
			if(!in_array($this_field, $fields_filtered)) {
				unset($fields[$this_key]);
			}
		}
	}
	if (empty($fields)) {
		return null;
	} else {
		return $fields;
	}
}

/**
 * get_table_field_names()
 *
 * @param string $table_name
 * @param mixed $link_identifier
 * @param boolean $silent_if_error
 * @param array $fields_filtered
 * @return
 */
function get_table_field_names($table_name, $link_identifier = null, $silent_if_error = false, $fields_filtered = null)
{
	static $fields;
	if(!isset($fields[$table_name])) {
		$fields[$table_name] = get_table_fields($table_name, $link_identifier, $silent_if_error);
	}
	if (empty($fields[$table_name])) {
		return null;
	} else {
		foreach($fields[$table_name] as $this_field) {
			$results[] = $this_field['Field'];
		}
		if($fields_filtered !== null) {
			if (!empty($GLOBALS['site_parameters']['products_check_existing_fields'])) {
				foreach($fields_filtered as $this_key => $this_field) {
					// D'abord on extrait les noms de champs du tableau $fields_filtered
					$temp = explode(' ', trim(str_replace('=', ' = ', $this_field)));
					$this_field = $temp[0];
					if(StringMb::strpos($this_field, '.') !== false) {
						$temp = explode('.', $this_field);
						$this_field = $temp[1];
					}
					// On ne garde que les champs de $fields_filtered présents dans $results
					if(!in_array($this_field, $results)) {
						unset($fields_filtered[$this_key]);
					}
				}
				$results = $fields_filtered;
				return $results;
			} else {
				// Si products_check_existing_fields est vide, alors on retourne la liste passée en paramètre tel quel
				return $fields_filtered;
			}
		}
		return $results;
	}
}

/**
 * get_table_field_types()
 *
 * @param mixed $table_name
 * @param mixed $link_identifier
 * @param boolean $silent_if_error
 * @param array $fields_filtered
 * @return
 */
function get_table_field_types($table_name, $link_identifier = null, $silent_if_error = false, $fields_filtered = null)
{
	static $fields;
	if(!isset($fields[$table_name])) {
		$fields[$table_name] = get_table_fields($table_name, $link_identifier, $silent_if_error, $fields_filtered);
	}
	if (empty($fields[$table_name])) {
		return null;
	} else {
		foreach($fields[$table_name] as $this_field) {
			$results[$this_field['Field']] = $this_field['Type'];
		}
		return $results;
	}
}

/**
 * get_table_field_names()
 *
 * @param mixed $table_name
 * @param mixed $link_identifier
 * @param boolean $silent_if_error
 * @return
 */
function get_existing_table_fields($table_name, $link_identifier = null, $silent_if_error = false)
{
	static $fields;
	if(!isset($fields[$table_name])) {
		$fields[$table_name] = get_table_fields($table_name, $link_identifier, $silent_if_error);
	}
	if (empty($fields[$table_name])) {
		return null;
	} else {
		foreach($fields[$table_name] as $this_field) {
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
 * Renvoie un tableau avec la liste des tables de la base courante
 *
 * @param string $name_part Chaîne de caractère devant faire partie du nom des tables recherchées
 * @return array liste des tables indexée par leurs noms
 * @access public
 */
function &listTables($name_part = null)
{
	// Récupère la liste des tables contenues dans la base courante
	static $tables_list;
	if (!isset($tables_list[$name_part])) {
		$sql = "SHOW TABLES FROM `".word_real_escape_string($GLOBALS['nom_de_la_base']) . "`";
		$result = query($sql);
		while ($table_name = fetch_row($result)) {
			if (empty($name_part) || StringMb::strpos($table_name[0], $name_part) !== false) {
				$tables_list[$name_part][$table_name[0]] = $table_name[0];
			}
		}
	}
	return $tables_list[$name_part];
}

/**
 * Renvoie un tableau avec la liste des bases de données accessibles
 *
 * @param string $name_part Chaîne de caractère devant faire partie du nom des tables recherchées
 * @return array liste des bases indexée par leurs noms
 * @access public
 */
function &list_dbs($name_part = null)
{
	// Récupère la liste des tables contenues dans la base courante
	static $databases_list;
	if (!isset($databases_list[$name_part])) {
		$sql = "SHOW DATABASES";
		$result = query($sql);
		while ($table_name = fetch_row($result)) {
			if ((empty($name_part) || StringMb::strpos($table_name[0], $name_part) !== false) && $table_name[0] != "information_schema" && $table_name[0] != "mysql") {
				$databases_list[$name_part][$table_name[0]] = $table_name[0];
			}
		}
	}
	return $databases_list[$name_part];
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


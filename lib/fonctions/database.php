<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database.php 64973 2020-11-09 13:07:30Z sdelaporte $
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
		$GLOBALS['database_names_array'][$GLOBALS['implicit_database_object_var']] = $GLOBALS['nom_de_la_base'];
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
			$database_object = @new mysqli('p:'.$server_infos[0], $utilisateur_mysql, $mot_de_passe_mysql, '', $port, $socket);
		} else {
			$database_object = @new mysqli($server_infos[0], $utilisateur_mysql, $mot_de_passe_mysql, '', $port, $socket);
		}
		if (mysqli_connect_error()) {
			$error_no = mysqli_connect_errno();
			$error_text = mysqli_connect_error();
		}
	} else {
		$database_object = mysql_connect($serveur_mysql, $utilisateur_mysql, $mot_de_passe_mysql);
	}
	if(!empty($error_no)) {
		if(!$continue_if_error) {
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
		} else {
			return false;
		}
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
	if(empty($database_object) || !empty($database_object->connect_errno)) {
		return false;
	}
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
	@query("SET SESSION sql_mode = '' ", false, $database_object);
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
	if ($security_sql_filter && (strpos(strtolower($query), 'information_schema') !== false || strpos(strtolower($query), 'loadfile') !== false || (empty($GLOBALS['site_parameters']['security_sql_filter_union_skip']) && (strpos(strtolower($query), 'union all') !== false || strpos(strtolower($query), 'union select') !== false))) || strpos(strtolower($query), 'benchmark(') !== false) {
		// On empêche l'exécution de requêtes contenant certains mots clé
		return false;
	}
	if(empty($database_object)) {
		$database_object = &$GLOBALS[$GLOBALS['implicit_database_object_var']];
	}
	if(empty($database_object) || !empty($database_object->connect_errno)) {
		return false;
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
		if(strpos(strtolower($query), 'drop table') === 0 || strpos(strtolower($query), 'alter table') === 0 || (!empty($error_number) && $error_number == 1054)) {
			// Intialisation de cache de table
			unset($_SESSION['table_infos']);
			unset($_SESSION['table_fields_infos']);
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
		// Pas d'erreur
		return $GLOBALS['last_query_result'];
	} else {
		// Erreur
		$GLOBALS['last_sql_error_message'] = vb($GLOBALS['STR_SQL_ERROR']) . ' ' . vb($error_number) . ' - ' . vb($error_name) . " - " . vb($GLOBALS['STR_PAGE']) . ' ' . vb($_SERVER['REQUEST_URI']) . ' - IP ' . vb($_SERVER['REMOTE_ADDR']) . ' - ' . $query;
		// if (a_priv('admin*', false) || (defined('PEEL_DEBUG') && PEEL_DEBUG)) {
		if (defined('PEEL_DEBUG') && PEEL_DEBUG) {
			$GLOBALS['last_sql_error_message'] .= print_r(debug_backtrace(), true);
		}
		if (!$silent_if_error || in_array($error_number, array(1118))) {
			// Si l'erreur est 1118 (Row size too large. The maximum row size for the used table type, not counting BLOBs, is 65535.) qui peut arriver lors d'un ALTER TABLE ADD alors on affiche quand même l'erreur pour meilleure gestion par l'administrateur
			if (empty($GLOBALS['display_errors']) && a_priv('admin*', false)) {
				// Erreurs pas visibles => on rend quand même visible si on est loggué en administrateur
				echo '[admin info : ' . $GLOBALS['last_sql_error_message'] . ']<br />';
			}
			trigger_error($GLOBALS['last_sql_error_message'] , E_USER_NOTICE);
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
		$database_object = &$GLOBALS[$GLOBALS['implicit_database_object_var']];
	}
	if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
		return $database_object->insert_id;
	} else {
		return mysql_insert_id($database_object);
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
		$database_object = &$GLOBALS[$GLOBALS['implicit_database_object_var']];
	}
	if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
		return $database_object->affected_rows;
	} else {
		return mysql_affected_rows($database_object);
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
	} elseif(!empty($GLOBALS[$GLOBALS['implicit_database_object_var']])) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			$value = $GLOBALS[$GLOBALS['implicit_database_object_var']]->real_escape_string($value);
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
	} elseif(!empty($GLOBALS[$GLOBALS['implicit_database_object_var']])) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			$value = $GLOBALS[$GLOBALS['implicit_database_object_var']]->real_escape_string(@StringMb::strip_tags($value, $allowed_tags));
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
	} elseif(!empty($GLOBALS[$GLOBALS['implicit_database_object_var']])) {
		$value = StringMb::substr($value, 0, min(StringMb::strpos(str_replace(array('+', ',', ';', '(', ')', '!', '=', '`', '|', '&'), ' ', $value) . ' ', ' '), 60));
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			$value = $GLOBALS[$GLOBALS['implicit_database_object_var']]->real_escape_string($value);
		} else {
			$value = mysql_real_escape_string($value);
		}
	} else {
		$value = null;
	}
	return $value;
}

/**
 * Génère du SQL à partir d'un tableau champ => valeur
 *
 * @param mixed $array
 * @param string $separator
 * @return
 */
function create_sql_from_array($array, $separator = ',')
{
	$sql_array = array();
	foreach($array as $key => $value) {
		$sql_array[] = "`" . word_real_escape_string($key) . "`='" . real_escape_string($value) . "'";
	}
	return implode($separator, $sql_array);
}

/**
 * Renvoie les informations des champs d'une table avec SHOW COLUMNS
 *
 * @param string $table_name
 * @param mixed $database_object
 * @param boolean $silent_if_error
 * @param array $fields_allowed : si non nul, on ne garde que les champs présents dans $fields_allowed
 * @return
 */
function get_table_fields($table_name, $database_object = null, $silent_if_error = false, $fields_allowed = null)
{
	static $fields;
	$cache_id = $GLOBALS['implicit_database_object_var'] . '_' . $table_name . '_' . serialize($fields_allowed);
	//debug_print_backtrace();
	if($database_object === null && !empty($GLOBALS['store_table_fields_infos_in_session']) && isset($_SESSION['table_fields_infos'][$cache_id]) && (empty($GLOBALS['store_table_fields_infos_in_session_excluded']) || !in_array($table_name, $GLOBALS['store_table_fields_infos_in_session_excluded'], true))) {
		// Pour certains sites faisant beaucoup usage de SHOW COLUMNS, c'est intéressant de mettre les informations en cache de session. L'initialisation de $GLOBALS['store_table_fields_infos_in_session'] est à gérer par ailleurs de manière spécifique, en ne concernant pas les administrateurs par exemple.
		$fields[$cache_id] = $_SESSION['table_fields_infos'][$cache_id];
	} elseif(!isset($fields[$cache_id])) {
		$fields[$cache_id] = null;
		$sql = "SHOW COLUMNS FROM `" . word_real_escape_string($table_name) . "`";
		$query = query($sql, false, $database_object, $silent_if_error);
		while ($result = fetch_assoc($query)) {
			$fields[$cache_id][] = $result;
		}
		if(!empty($fields[$cache_id]) && $fields_allowed !== null && !empty($GLOBALS['site_parameters']['products_check_existing_fields'])) {
			// D'abord on nettoie le tableau $fields_allowed
			foreach($fields_allowed as $this_key => $this_field) {
				$temp = explode(' ', trim($this_field));
				$this_field = $temp[0];
				if(StringMb::strpos($this_field, '.') !== false) {
					$temp = explode('.', $this_field, 2);
					$this_field = end($temp);
				}
				$fields_allowed[$this_key] = $this_field;
			}
			// On ne garde que les champs de $fields présents dans $fields_allowed
			foreach($fields[$cache_id] as $this_key => $this_field) {
				if(!in_array($this_field['Field'], $fields_allowed)) {
					unset($fields[$cache_id][$this_key]);
				}
			}
		}
		if(!empty($GLOBALS['store_table_infos_in_session'])) {
			$_SESSION['table_fields_infos'][$cache_id] = $fields[$cache_id];
		}
	}
	if (empty($fields[$cache_id])) {
		return null;
	} else {
		return $fields[$cache_id];
	}
}

/**
 * Renvoie les noms des champs d'une table
 *
 * @param string $table_name
 * @param mixed $database_object
 * @param boolean $silent_if_error
 * @param array $fields_to_be_filtered_array : tableau des champs souhaités. Chaque champ peut être un simple nom de champ, mais aussi "p.meta_desc_fr AS meta_desc"
 * @return
 */
function get_table_field_names($table_name, $database_object = null, $silent_if_error = false, $fields_to_be_filtered_array = null)
{
	static $field_names;
	$cache_id = $GLOBALS['implicit_database_object_var']  . '_' . $table_name . '_' . serialize($fields_to_be_filtered_array);
	if(!isset($field_names[$cache_id])) {
		$fields = get_table_fields($table_name, $database_object, $silent_if_error);
		if (!empty($fields)) {
			foreach($fields as $this_field) {
				$field_names[$cache_id][] = $this_field['Field'];
			}
			if($fields_to_be_filtered_array !== null) {
				if (!empty($GLOBALS['site_parameters']['products_check_existing_fields'])) {
					foreach($fields_to_be_filtered_array as $this_key => $this_field) {
						// D'abord on extrait les noms de champs du tableau $fields_to_be_filtered_array
						$temp = explode(' ', trim(str_replace('=', ' = ', $this_field)));
						$this_field = $temp[0];
						if(StringMb::strpos($this_field, '.') !== false) {
							$temp = explode('.', $this_field, 2);
							$this_field = end($temp);
						}
						// On ne garde que les champs de $fields_to_be_filtered_array correspondant à des champs de $field_names
						if(!in_array($this_field, $field_names, true)) {
							unset($fields_to_be_filtered_array[$this_key]);
						}
					}
				} else {
					// Si products_check_existing_fields est vide, alors on retourne la liste passée en paramètre tel quel
				}
				$field_names[$cache_id] = $fields_to_be_filtered_array;
			}
		} else {
			$field_names[$cache_id] = array();
		}
	}
	return $field_names[$cache_id];
}

/**
 * get_table_field_types()
 *
 * @param mixed $table_name
 * @param mixed $database_object
 * @param boolean $silent_if_error
 * @param array $fields_allowed
 * @return
 */
function get_table_field_types($table_name, $database_object = null, $silent_if_error = false, $fields_allowed = null)
{
	static $fields;
	$cache_id = $GLOBALS['implicit_database_object_var']  . '_' . $table_name . '_' . serialize($fields_allowed);
	if(!isset($fields[$cache_id])) {
		$fields[$cache_id] = get_table_fields($table_name, $database_object, $silent_if_error, $fields_allowed);
	}
	if (empty($fields[$cache_id])) {
		return null;
	} else {
		foreach($fields[$cache_id] as $this_field) {
			$results[$this_field['Field']] = $this_field['Type'];
		}
		return $results;
	}
}

/**
 * Récupération des informations de jointures entre des tables
 * Exemple : si la table tmppasse contient un champ Collaborateur, on peut récupérer les informations liées à la jointure sur ce champ avec get_join_infos('tmppasse', 'Collaborateur') => target_table est la table 'collaborateurs', target_field est 'Collaborateur' (le nom du champ dans collaborateurs), et result_field est 'Nom_Prenom' (la valeur de titre dans collaborateurs)
 *
 * @param string $table_name Dans le cas général, on met ici le nom de la table
 * @param string $this_field_name Nom du champ pour lequel il pourrait y avoir une jointure
 * @param string $table_view
 * @param array $field_join_infos_array
 * @return
 */
function get_join_infos($table_name, $this_field_name, $table_view = null, $field_join_infos_array = null) {
	$temp = explode('.', $this_field_name, 2);
	$this_field_name = end($temp);
	if($field_join_infos_array === null && !empty($GLOBALS['database_field_join_infos_array'])) {
		$field_join_infos_array = $GLOBALS['database_field_join_infos_array'];
	}
	if(empty($table_view)) {
		$table_view = $table_name;
	}
	
	if(!empty($field_join_infos_array[$table_name . '.' . $this_field_name])) {
		$raw_join_infos = $field_join_infos_array[$table_name . '.' . $this_field_name];
	} elseif(!empty($field_join_infos_array[$this_field_name]) && (empty($table_name) || strpos($field_join_infos_array[$this_field_name], $table_name . '.') === false)) {
		// On ne prend que des jointures valables, sans prendre les définitions de jointure impliquant un champ distant qui est dans la table en cours
		$raw_join_infos = $field_join_infos_array[$this_field_name];
	}
	if(!empty($raw_join_infos)) {
		$temp = explode('.', $raw_join_infos, 2);
		$temp2 = explode('/', end($temp), 2);
		$join_infos['table'] = $table_view;
		$join_infos['field'] = $this_field_name;
		$join_infos['target_table'] = str_replace('CONCAT(', '', $temp[0]);
		$join_infos['target_field'] = (!empty($temp2[1])?$temp2[1]:$this_field_name);
		$join_infos['target_full_field'] = $join_infos['target_table'] . '.' . $join_infos['target_field'];
		$join_infos['raw_join_infos'] = $raw_join_infos;
		$temp3 = explode('/', $raw_join_infos, 2);
		$join_infos['result_field'] = $temp3[0];
		return $join_infos;
	} else {
		return null;
	}
}

/**
 * Récupération des valeurs possibles pour un champ
 *
 * @param string $table_name
 * @param string $this_field_name
 * @return
 */
function get_select_infos($table_name, $this_field_name) {
	if(!empty($GLOBALS['database_fields_select_values_array'][$table_name . '.' . $this_field_name])) {
		return $GLOBALS['database_fields_select_values_array'][$table_name . '.' . $this_field_name];
	} elseif(!empty($GLOBALS['database_fields_select_values_array'][$this_field_name])) {
		return $GLOBALS['database_fields_select_values_array'][$this_field_name];
	}
	return null;
}

/**
 * Récupération de lignes venant de tables quelconques, selon diverses règles
 *
 * @param string $table
 * @param mixed $id  Valeur de l'id si champ simple, ou tableau du type key1=>val1, key2=>val2
 * @param string $search
 * @param boolean $one_row_mode
 * @param integer $limit
 * @param boolean $npu
 * @param mixed $one_col_mode
 * @param string $sql_cond
 * @param string $group_by
 * @param string $forced_order_by
 * @param mixed $cols
 * @param boolean $forced_cgst
 * @param boolean $row_key_col
 * @return
 */
function &get_table_rows($table, $id = null, $search = null, $one_row_mode = false, $limit = null, $npu = null, $one_col_mode = false, $sql_cond = null, $group_by = null, $forced_order_by = null, $cols = null, $forced_cgst = null, $row_key_col = null, $search_field_forced = null) {
	$results = array();
	$where_array = array();
	if(empty($row_key_col) && !empty($one_col_mode) && $one_col_mode !== true) {
		// Pour gérer $row_key_col il ne faut pas récupérer que la colonne correspondant à $one_col_mode
		$cols = $one_col_mode;
	}
	$hook_result = call_module_hook('database_table_rows_configure', array('table' => $table, 'forced_cgst' => $forced_cgst, 'search_field_forced' => $search_field_forced), 'array');
	if(!empty($hook_result['where'])) {
		$where_array[] = '(' . $hook_result['where'] . ')';
	}
	$search_fields_array = vb($hook_result['search_fields_array'], array());
	
	if(!empty($hook_result['table'])) {
		$table = $hook_result['table'];
	}
	$order_by = vb($hook_result['order_by']);
	$primary_key = vb($hook_result['primary_key']);
	if(!empty($hook_result['fields_list'])) {
		$fields_list = $hook_result['fields_list'];
	} elseif(!empty($cols)) {
		// $cols sous forme de liste ou de tableau
		if(is_array($cols)) {
			$fields_list = implode(', ', $cols);
		} else {
			$fields_list = $cols;
		}
	} else {
		$fields_list = '*';
	}
	$database_field_infos_array = array();
	if((!empty($id) && empty($primary_key)) || (empty($search_fields_array) && strlen($search))) {
		// on veut ne charger qu'une seule fois get_table_fields($table)
		$database_field_infos_array = get_table_fields($table);
	}
	if(empty($search_fields_array) && strlen($search)) {
		// Cas général si pas de hook spécifiant la recherche
		// On recherche sur les champs texte
		foreach($database_field_infos_array as $this_field_infos) {
			if(strpos($this_field_infos['Type'], 'text(') !== false || strpos($this_field_infos['Type'], 'blob') !== false || strpos($this_field_infos['Type'], 'char(') !== false) {
				$search_fields_array[] = $this_field_infos['Field'];
			}
		}
	}
	// A mettre  ? Compliqué suivant les cas...
	if(!empty($id)) {
		$where_array = array_merge_recursive_distinct($where_array, get_where_array_from_id_infos($table, $primary_key, $id, $database_field_infos_array));
		$limit = 1;
	}
	if($npu !== null && in_array($table, $GLOBALS['database_tables_with_npu_array'])) {
		if($npu === true) {
			$npu = 1;
		} elseif($npu === false) {
			$npu = 0;
		}
		$where_array[] = 'NPU="'.intval($npu).'"';
	}
	if(!empty($sql_cond)) {
		if(is_array($sql_cond)) {
			$sql_cond = implode(') AND (', $sql_cond);
		}
		$where_array[] = '('.$sql_cond.')';
	}	   
	if(strlen($search) && !empty($search_fields_array)) {
		$search_sql_cond = array();
		if($search !== '%') {
			$search = $search . '%';
		}
		foreach($search_fields_array as $this_field) {
			$search_sql_cond[] = word_real_escape_string($this_field).' LIKE "'.real_escape_string($search).'"';
		}
		$where_array[] = '('.implode(' OR ', $search_sql_cond).')';
	}
	foreach($where_array as $this_key => $this_value) {
		if(in_array($this_value, array('', 1, '1'), true)) {
			unset($where_array[$this_key]);
		}
	}
	if(!count($where_array)) {
		$where_array[] = 1;
	}
	$sql = 'SELECT ' . $fields_list . '
		FROM ' . word_real_escape_string($table) . '
		WHERE ' . implode(' AND ', $where_array);
	if(!empty($group_by)) {
		$sql .= '
			GROUP BY '. real_escape_string($group_by);
	}
	if(!empty($forced_order_by)) {
		$order_by = $forced_order_by;
	}
	if(!empty($order_by) && (empty($id) || !empty($forced_order_by))) {
		$sql .= '
			ORDER BY '.(strpos($order_by, 'IF') === false ? real_escape_string($order_by) : $order_by);
	}
	if($one_row_mode) {
		$limit = 1;
	}
	if(!empty($limit)) {
		$sql .= '
			LIMIT '.intval($limit);
	}
	$query = query($sql);
	while ($result = fetch_assoc($query)) {
		// echo memory_get_usage() . "\n"; 
		if(!empty($row_key_col) && $one_col_mode !== true) {
			$results[$result[$row_key_col]] = $result[$one_col_mode];
			continue;
		} elseif($one_col_mode) {
			$result = current($result);
		}
		
		// il n'est pas cohérent d'appeler la fonction avec one_row_mode et row_key_col en même temps
		if($one_row_mode) {
			return $result;
		} elseif(!empty($row_key_col)) {
			$results[$result[$row_key_col]] = $result;
		} else {
			$results[] = $result;
		}
	}
	if($one_row_mode) {
		// Pas de résultat trouvé
		$results = null;
	}
	return $results;
}

/**
 * Génération d'une condition where à partir de divers format d'id
 *
 * @param string $table
 * @param string $primary_key
 * @param mixed $id
 * @param array $database_field_infos_array
 * @return
 */
function get_where_array_from_id_infos($table, $primary_key, $id, $database_field_infos_array = null) {
	$where_array = array();
	if(empty($primary_key)) {
		if(!empty($database_field_infos_array)) {
			$primary_key = get_primary_key($table, $database_field_infos_array);
		} else {
			$primary_key = get_primary_key($table);
		}
	}	   
	foreach(explode(',', $primary_key) as $this_key => $this_field) {
		// Gestion de clé primaire simple ou multiple
		if(is_array($id) && isset($id[$this_field])) {
			// $id est a priori le résultat de get_primary_key_values_from_datatables_id
			// Dans le cas d'une clé multiple, $id est ici un tableau qui donne les valeurs de chaque champ
			$this_id = $id[$this_field];
		} elseif(is_array($id) && isset($id[$this_key])) {
			// $id est un tableau de valeurs ordonné de la même manière que get_primary_key
			$this_id = $id[$this_key];
		} elseif(!is_array($id)) {
			$this_id = $id;
		} else {
			continue;
		}
		$where_array[] = word_real_escape_string($this_field).'="'.real_escape_string($this_id).'"';
	}
	return $where_array;
}

/**
 * Récupération de la clé primaire d'une table
 * Dans le cas d'une clé primaire contenant plusieurs champs, on veut récupérer la liste séparée par des virgules
 *
 * @param string $table_name
 * @param array $database_field_infos_array
 * @param boolean $get_first_field_only
 * @return
 */
function get_primary_key($table_name = null, $database_field_infos_array = null, $get_first_field_only = false) {
	static $primary_key_array_by_table;
	$cache_id = $GLOBALS['implicit_database_object_var']  . '_' . $table_name;
	if(!isset($primary_key_array_by_table[$cache_id])) {
		$primary_key_array_by_table[$cache_id] = array();
		if(!empty($GLOBALS['database_primary_keys_by_table_array']) && !empty($GLOBALS['database_primary_keys_by_table_array'][$table_name])) {
			// Par défaut on prend la première colonne si pas d'autre trouvée ensuite
			$primary_key_array_by_table[$cache_id] = $GLOBALS['database_primary_keys_by_table_array'][$table_name];
		} else {
			if(empty($database_field_infos_array)) {
				// Optimisation si $database_field_infos_array n'est pas vide
				// => pas besoin d'avoir une clé de cache particulière si on a ou non $database_field_infos_array vide
				$database_field_infos_array = get_table_fields($table_name);
			}
			if(!empty($database_field_infos_array)) {
				foreach($database_field_infos_array as $this_infos) {
					if(empty($default_field)) {
						// Par défaut on prend la première colonne si pas d'autre trouvée ensuite
						$default_field = $this_infos['Field'];
					} elseif(in_array(strtolower($this_infos['Field']), array('id', 'compteur'), true)) {
						$default_field = $this_infos['Field'];
					}
					if($this_infos['Key'] == 'PRI') {
						$primary_key_array_by_table[$cache_id][] = $this_infos['Field'];
					}
				}
			}
			if(empty($primary_key_array_by_table[$cache_id]) && !empty($default_field)) {
				// Par défaut on prend 'id' ou 'compteur', ou à défaut la première colonne
				$primary_key_array_by_table[$cache_id][] = $default_field;
			}
		}
	}
	if($get_first_field_only) {
		return current($primary_key_array_by_table[$cache_id]);
	} else { 
		return implode(',', $primary_key_array_by_table[$cache_id]);
	}	
}

/**
 * Renvoie si champ unique dans une table (clé primaire, ou configuré pour être unique dans $GLOBALS['database_fields_unique_by_table_array']
 *
 * @param string $field_name
 * @param string $table_name
 * @param array $primary_key_array
 * @return
 */
function get_field_unique($field_name, $table_name, $primary_key_array = null) {
	if ($primary_key_array === null) {
		$primary_key_array = explode(',', get_primary_key($table_name));
	}
	if (!empty($primary_key_array) && count($primary_key_array) == 1 && in_array($field_name, $primary_key_array, true)) {
		// Si la clé primaire concerne plusieurs colonnes, alors on ne gère pas ici l'unicité
		return true;
	} elseif (!empty($GLOBALS['database_fields_unique_by_table_array'][$table_name])) {
		return in_array($field_name, get_array_from_string($GLOBALS['database_fields_unique_by_table_array'][$table_name]), true);
	} else {
		return false;
	}
}

/**
 * get_field_maxlength()
 *
 * @param string $field_type
 * @return
 */
function get_field_maxlength($field_type, $return_decimals = false) {
	if(strpos($field_type, 'int(') !== false || strpos($field_type, 'float(') !== false || strpos($field_type, 'text(') !== false || strpos($field_type, 'char(') !== false) {
		$temp = explode('(', $field_type);
		$temp2 = explode(')', $temp[1]);
		if(!$return_decimals) {
			$maxlength = intval($temp2[0]);
		} else {
			$maxlength = intval(vn($temp2[1]));
		}
	} else {
		$maxlength = null;
	}
	if($return_decimals && ((strpos($field_type, 'float(') !== false && empty($maxlength)) || strpos($field_type, 'double') !== false)) {
		$maxlength = 2;
	}
	return $maxlength;
}

/**
 * Renvoie le nom d'un champ
 *
 * @param string $field_name
 * @param string $type_or_table_name
 * @param boolean $force_no_empty
 * @param integer $max_length
 * @param boolean $add_table_name_if_full_field_name
 * @return
 */
function get_field_title($field_name, $type_or_table_name, $force_no_empty = false, $max_length = null, $add_table_name_if_full_field_name = false) {
	static $field_titles;
	$cache_id = $field_name  . '_' . $type_or_table_name . '_' . serialize($force_no_empty) . '_' . serialize($add_table_name_if_full_field_name);
	if(!isset($field_titles[$cache_id])) {
		if(strpos($field_name, '.') !== false) {
			// Nom complet, du type : table.champ => cette information de table est prioritaire
			$temp = explode('.', $field_name, 2);
			$field_name = $temp[1];
			$this_table = $temp[0];
			if($field_name == 'tacnfact.Presence_Absence') {
				// On ne veut pas de préfixe de table ajouté
				$add_table_name_if_full_field_name = false;
			}
		} else {
			$this_table = $type_or_table_name;
			$add_table_name_if_full_field_name = false;
		}
		if (isset($GLOBALS['database_field_titles_by_type_array'][$type_or_table_name][$field_name])) {
			// La notion de type de rapport est prioritaire sur la notion de table de stockage des données
			$return = $GLOBALS['database_field_titles_by_type_array'][$type_or_table_name][$field_name];
		}
		if ((!isset($return) || (empty($return) && $force_no_empty)) && isset($GLOBALS['database_field_titles_by_table_array'][$this_table][$field_name])) {
			$return = $GLOBALS['database_field_titles_by_table_array'][$this_table][$field_name];
		}
		if((!isset($return) || (empty($return) && $force_no_empty)) && !empty($GLOBALS['database_import_export_table_by_type_array'][$type_or_table_name])) {
			// $type_or_table_name est apparemment un type qui a une correspondance vers une table
			$this_table_or_tables = $GLOBALS['database_import_export_table_by_type_array'][$type_or_table_name];
			if(isset($GLOBALS['database_field_titles_by_table_array'][$this_table_or_tables][$field_name])) {
				$return = $GLOBALS['database_field_titles_by_table_array'][$this_table_or_tables][$field_name];
			}
		}
		if ((!isset($return) || (empty($return) && $force_no_empty)) && isset($GLOBALS['database_field_titles_array'][$field_name])) {
			// Titre d'ordre général, non spécifique à un type ou une table
			$return = $GLOBALS['database_field_titles_array'][$field_name];
		}
		if ((!isset($return) || (empty($return) && $force_no_empty))) {
			// Ajout d'un espace avant majuscule suivie d'une minuscule
			$output = preg_replace('/(?<! )(?<!^)[A-Z][a-z]/', ' $0', $field_name);
			// Ajout d'un espace après un mot et avant majuscule
			$output = preg_replace('/(?<! )(?<!^)(?<![A-Z])[A-Z]/', ' $0', $output);
			// Ajout d'un espace avant un nombre
			$output = preg_replace('/(?<! )(?<!^)(?<![0-9])[0-9]/', ' $0', $output);
			$return = trim(str_replace('  ', ' ', str_replace('_', ' ', $output)));
		}
		if($add_table_name_if_full_field_name) {
			$return = (!empty($GLOBALS["STR_MODULE_TEMPS_TABLE_NAMES_ARRAY"][$this_table]) ? $GLOBALS["STR_MODULE_TEMPS_TABLE_NAMES_ARRAY"][$this_table] . $GLOBALS["STR_BEFORE_TWO_POINTS"] . ': ' : '') . $return;
		}
		$field_titles[$cache_id] = $return;
	}
	if(!empty($max_length)) {
		return StringMb::str_shorten_words($field_titles[$cache_id], $max_length, '', true, false, '.');
	} else {
		return $field_titles[$cache_id];
	}
}

/**
 * Renvoie si un champ est obligatoire ou non
 *
 * @param string $field_name
 * @param string $type_or_table_name
 * @return
 */
function get_field_required($field_name, $type_or_table_name) {
	$return = (!empty($GLOBALS['database_fields_required_array']) && in_array($field_name, $GLOBALS['database_fields_required_array'], true));
	foreach(explode('|', $type_or_table_name) as $table_name) { 
		$return = ($return || (!empty($GLOBALS['database_fields_required_by_table_array']) && !empty($GLOBALS['database_fields_required_by_table_array'][$table_name]) && in_array($field_name, get_array_from_string(vb($GLOBALS['database_fields_required_by_table_array'][$table_name])), true)));
		if(!$return && !empty($table_name)) {
			$primary_key = get_primary_key($table_name);
			if(in_array($field_name, get_array_from_string($primary_key), true)) {
				// Si on rajoute strpos($primary_key, ',') === false &&  : Obligatoire seulement si clé primaire simple
				// Sinon par défaut ici, tout élément de clé primaire est obligatoire
				$return = true;
			}
		}
		if($return) break;
	}
	return $return;
}

/**
 * get_existing_table_fields()
 *
 * @param mixed $table_name
 * @param mixed $database_object
 * @param boolean $silent_if_error
 * @return
 */
function get_existing_table_fields($table_name, $database_object = null, $silent_if_error = false)
{
	static $fields;
	$cache_id = $GLOBALS['implicit_database_object_var']  . '_' . $table_name;
	if(!isset($fields[$cache_id])) {
		$fields[$cache_id] = get_table_fields($table_name, $database_object, $silent_if_error);
	}
	if (empty($fields[$cache_id])) {
		return null;
	} else {
		foreach($fields[$cache_id] as $this_field) {
			$results[] = $this_field['Field'];
		}
		return $results;
	}
}

/**
 * get_table_index()
 *
 * @param mixed $table_name
 * @param mixed $database_object
 * @param boolean $silent_if_error
 * @return
 */
function get_table_index($table_name, $database_object = null, $silent_if_error = false)
{
	$sql = "SHOW INDEX FROM `" . word_real_escape_string($table_name) . "`";
	$query = query($sql, false, $database_object, $silent_if_error);
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
 * @param string $table_type
 * @return array liste des tables indexée par leurs noms
 * @access public
 */
function &listTables($name_part = null, $table_type = null)
{
	// Récupère la liste des tables contenues dans la base courante
	static $tables_array;
	$cache_id = $GLOBALS['implicit_database_object_var'] . '_' . $name_part . '_' . $table_type;
	if(!empty($GLOBALS['store_table_infos_in_session']) && isset($_SESSION['table_infos'][$cache_id])) {
		// Pour certains sites faisant beaucoup usage de SHOW TABLES, c'est intéressant de mettre les informations en cache de session. L'initialisation de $GLOBALS['store_table_infos_in_session'] est à gérer par ailleurs de manière spécifique, en ne concernant pas les administrateurs par exemple.
		$tables_array[$cache_id] = $_SESSION['table_infos'][$cache_id];
	} elseif (!isset($tables_array[$cache_id])) {
		// Compatibilité avec connexions BDD multiples : si 
		$database_name = $GLOBALS['database_names_array'][$GLOBALS['implicit_database_object_var']];
		if(!empty($table_type)) {
			$sql = "SHOW FULL TABLES FROM `" . word_real_escape_string($database_name) . "` WHERE TABLE_TYPE LIKE '" . real_escape_string($table_type) . "'";
		} else {
			$sql = "SHOW TABLES FROM `" . word_real_escape_string($database_name) . "`";
		}
		$result = query($sql);
		while ($table_name = fetch_row($result)) {
			if (empty($name_part) || StringMb::strpos($table_name[0], $name_part) !== false) {
				$tables_array[$cache_id][$table_name[0]] = $table_name[0];
			}
		}
		if(!empty($GLOBALS['store_table_infos_in_session'])) {
			$_SESSION['table_infos'][$cache_id] = $tables_array[$cache_id];
		}
	}
	return $tables_array[$cache_id];
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
	$cache_id = $GLOBALS['implicit_database_object_var'] . '_' . $name_part;
	if (!isset($databases_list[$cache_id])) {
		$sql = "SHOW DATABASES";
		$result = query($sql);
		while ($table_name = fetch_row($result)) {
			if ((empty($name_part) || StringMb::strpos($table_name[0], $name_part) !== false) && $table_name[0] != "information_schema" && $table_name[0] != "mysql") {
				$databases_list[$cache_id][$table_name[0]] = $table_name[0];
			}
		}
	}
	return $databases_list[$cache_id];
}

/**
 * db_close()
 *
 * @return
 */
function db_close($database_object = null)
{
	if(empty($database_object)) {
		$database_object = &$GLOBALS[$GLOBALS['implicit_database_object_var']];
	}
	if(!empty($database_object)) {
		if($GLOBALS['site_parameters']['mysql_extension'] == 'mysqli') {
			$database_object->close();
		} else {
			mysql_close($GLOBALS[$GLOBALS['implicit_database_object_var']]);	
		}
	}
}


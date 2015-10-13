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
// $Id: modules_handler.php 47350 2015-10-12 20:16:45Z gboussin $

if (!defined('IN_PEEL')) {
    die();
}


/**
 * Charge les modules
 * Pour obtenir des modules optionnels : Contactez PEEL sur https://www.peel.fr/ ou au 01 75 43 67 97
 *
 * @return
 */
function load_modules($technical_code = null) {
	$modules_to_check = array_keys(array_merge_recursive(vb($GLOBALS['site_parameters']['modules_front_office_functions_files_array'], array()), vb($GLOBALS['site_parameters']['modules_admin_functions_array'], array()), vb($GLOBALS['site_parameters']['modules_crons_functions_array'], array()), vb($GLOBALS['site_parameters']['modules_lang_folders_array'], array())));
	foreach($modules_to_check as $this_module) {
		if((empty($technical_code) || $technical_code == $this_module) && empty($GLOBALS['modules_installed'][$this_module])) {
			// Pour la compatibilité avec d'anciennes versions, on stocke le chemin vers les fichiers de fonctions dans une variable globale
			if(!empty($GLOBALS['site_parameters']['modules_front_office_functions_files_array'][$this_module])) {
				$GLOBALS[vb($GLOBALS['site_parameters']['modules_fonctions_variable_array'][$this_module], 'fonctions'. $this_module)] = $GLOBALS['dirroot'] . str_replace(',', ',' . $GLOBALS['dirroot'], $GLOBALS['site_parameters']['modules_front_office_functions_files_array'][$this_module]);
			}
			if ((in_array($this_module, $GLOBALS['site_parameters']['modules_no_optional_array']) || !defined('LOAD_NO_OPTIONAL_MODULE') || (defined('LOAD_MODULE_FORCED') && in_array($this_module, LOAD_MODULE_FORCED))) && check_if_module_active($this_module)) {
				// On a une protection pour éviter de charger deux fois le même fichier de fonctions en cas de doublon de configuration
				// En revanche, rien n'empêche de charger plusieurs librairies de fonctions pour un même module
				if((empty($GLOBALS['site_parameters']['modules_no_library_load_array']) || !in_array($this_module, $GLOBALS['site_parameters']['modules_no_library_load_array']))) {
					if(!empty($GLOBALS['site_parameters']['modules_front_office_functions_files_array'][$this_module])) {
						foreach(explode(',', $GLOBALS['site_parameters']['modules_front_office_functions_files_array'][$this_module]) as $this_file) {
							if(String::strpos($this_file, '.php') !== false && !in_array($this_file, vb($GLOBALS['modules_loaded_functions'], array()))) {
								if(String::strpos($this_file, 'administrer/') === false || (defined('IN_PEEL_ADMIN') || defined('IN_CRON'))) {
									include($GLOBALS['dirroot'] . $this_file);
								}
								$GLOBALS['modules_loaded_functions'][] = $this_file;
								$GLOBALS['modules_loaded_functions'][] = $this_file;
							}
						}
					}
					if((defined('IN_PEEL_ADMIN') || defined('IN_CRON')) && !empty($GLOBALS['site_parameters']['modules_admin_functions_array'][$this_module]) && !in_array($GLOBALS['site_parameters']['modules_admin_functions_array'][$this_module], vb($GLOBALS['modules_loaded_functions'], array()))) {
						$GLOBALS['fonctions_admin_'. $this_module] = $GLOBALS['dirroot'] . $GLOBALS['site_parameters']['modules_admin_functions_array'][$this_module];
						include($GLOBALS['fonctions_admin_'. $this_module]);
						$GLOBALS['modules_loaded_functions'][] = $GLOBALS['site_parameters']['modules_admin_functions_array'][$this_module];
					}
					if(defined('IN_CRON') && !empty($GLOBALS['site_parameters']['modules_crons_functions_array'][$this_module]) && !in_array($GLOBALS['site_parameters']['modules_crons_functions_array'][$this_module], vb($GLOBALS['modules_loaded_functions'], array()))) {
						$GLOBALS['fonctions_cron_'. $this_module] = $GLOBALS['dirroot'] . $GLOBALS['site_parameters']['modules_crons_functions_array'][$this_module];
						include($GLOBALS['fonctions_cron_'. $this_module]);
						$GLOBALS['modules_loaded_functions'][] = $GLOBALS['site_parameters']['modules_crons_functions_array'][$this_module];
					}
				}
				if(!empty($GLOBALS['site_parameters']['modules_lang_folders_array'][$this_module])) {
					$GLOBALS['modules_lang_folders_to_load_array'][] = $GLOBALS['site_parameters']['modules_lang_folders_array'][$this_module];
				}
				$GLOBALS['modules_installed'][$this_module] = $this_module;
			}
		}
	}
	if(empty($technical_code)) {
		// On charge les fichiers de langue des modules
		if (!empty($GLOBALS['site_parameters']['load_site_specific_lang_folders'])) {
			foreach($GLOBALS['site_parameters']['load_site_specific_lang_folders'] as $this_key => $this_file_relative_path) {
				if(!in_array($this_file_relative_path, vb($GLOBALS['modules_lang_folders_to_load_array'], array()))) {
					if(file_exists($GLOBALS['dirroot'] . $this_file_relative_path)) {
						// Ces fichiers de langue sont chargés en derniers grâce à leur clé élevée, et sont donc prioritaires
						$GLOBALS['modules_lang_folders_to_load_array'][1000 + $this_key] = $this_file_relative_path;
					}
				}
			}
		}
	}
}

/**
 * Renvoie si un module est présent et activé ou non - Peut être appelé avant ou après le chargement d'un module
 * 
 * @param string $module_name Nom du module à tester. Le nom du module doit être le même que le dossier
 * @param string $specific_file_name Nom du fichier specifique à tester si nécessaire.
 *
 * @return
 */
function check_if_module_active($module_name, $specific_file_name=null) {
	$automatically_activate_if_no_configuration_available = array('thumbs');
	if (empty($module_name) || !isset($GLOBALS['site_parameters']['modules_configuration_variable_array'])) {
		// Nom du module vide ou pas renseigné - Ou pas de configuration valide disponible, comme lors de l'installation
		return false;
	}
	$module_configuration_variable = vb($GLOBALS['site_parameters']['modules_configuration_variable_array'][$module_name], 'module_' . $module_name);
	$module_configured = ((in_array($module_name, $automatically_activate_if_no_configuration_available) && !isset($GLOBALS['site_parameters'][$module_configuration_variable])) || !empty($GLOBALS['site_parameters'][$module_configuration_variable]));
	$module_enable_for_this_lang = (empty($GLOBALS['site_parameters'][$module_name . '_allowed_langs_array']) || in_array($_SESSION['session_langue'], $GLOBALS['site_parameters'][$module_name . '_allowed_langs_array']));
	if ($module_configured && $module_enable_for_this_lang) {
		// Si le paramètre est absent, ou qu'il est activé en back office. La validité de l'absence du paramètre est nécessaire pour des raisons de compatibilité pour certains modules. Donc la désactivation du module est à faire depuis le back office, en passant la valeur à 0 ou false (selon le type de la configuration)
		if (empty($specific_file_name)) {
			// cas standard
			if (file_exists($GLOBALS['dirroot'] . '/modules/'.$module_name)) {
				// dossier trouvé et module actif, la fonction retourne un résultat positif
				return true;
			}
		} elseif (file_exists($GLOBALS['dirroot'] . '/modules/' . $module_name . '/' . $specific_file_name)) {
			// fichier trouvé et module actif, la fonction retourne un résultat positif
			return true;
		}
	}
	// Si on passe par ici, les tests qui permettent l'activation du module ont échoué. Soit la désactivation du module est faite depuis l'administration, ou aucun fichier respectant la norme de nommage n'est présent dans le module
	return false;
}


/**
 * Appelle la fonction correspondant au $hook pour chaque module installé
 * La fonction doit s'appeler : [nom du module]_[nom du hook]
 *
 * @return
 */
function call_module_hook($hook, $params, $mode = 'boolean') {
	if($mode == 'boolean') {
		$output = true;
	} elseif($mode == 'array') {
		$output = array();	
	} else {
		$output = null;
	}
	if (defined('PEEL_DEBUG') && PEEL_DEBUG) {
		$start_time = microtime_float();
	}
	foreach(vb($GLOBALS['modules_installed'], array()) as $this_module) {
		// On charge le hook, soit en tant que fonction, soit en tant que méthode de la classe du module
		$function_name = $this_module . '_hook_' . $hook;
		$class_name = String::ucfirst($this_module);
		$method_name = 'hook_' . $hook;
		unset($result);
		if(function_exists($function_name)) {
			$result = $function_name($params);
		} elseif(method_exists($class_name, $method_name)) {
			$result = $class_name::$method_name($params);
		}
		if(isset($result)) {
			if($mode == 'boolean') {
				$output = ($output && $result);
			} elseif($mode == 'array') {
				if(!is_array($result)) {
					$result = array($result);
				}
				$output = array_merge_recursive($output, $result);
			} elseif($mode == 'max') {
				$output = max($output, $result);
			} elseif($mode == 'min') {
				if($output === null) {
					$output = $result;
				} else {
					$output = min($output, $result);
				}
			} elseif($mode == 'unique') {
				$output = $result;
			} elseif($mode == 'add') {
				$output += $result;
			} else {
				$output .= $result;
			}
		}
	}
	if (defined('PEEL_DEBUG') && PEEL_DEBUG) {
		$end_time = microtime_float();
		$GLOBALS['peel_debug'][] = array('text' => 'Hook ' . $hook, 'duration' => $end_time - $start_time, 'start' => $start_time - $GLOBALS['script_start_time']);
	}
	return $output; 
}
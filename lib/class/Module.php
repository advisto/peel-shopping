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
// $Id: Module.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 *
 * @brief Module permet de gérer des addons sur l'architecture PEEL
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: Module.php 55332 2017-12-01 10:44:06Z sdelaporte $
 * @access public
 */
abstract class Module {
	// Technical data
	public $technical_code;
	public $activation_variable;
	// Administration data
	public $name_by_lang;
	public $name;
    public $description_by_lang;
 	public $vendor;
	// Version data
    public $dependencies;
    public $version;
    public $database_version;
    public $peel_versions_compatibility_infos;
	// Other
    private $_errors;
    private $_messages;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        if (empty($this->name_by_lang[$_SESSION['session_langue']])) {
            $this->name_by_lang[$_SESSION['session_langue']] = vb($this->name_by_lang['en'], vb($this->name_by_lang['fr'], $this->technical_code));
        }
		$this->name = $this->name_by_lang[$_SESSION['session_langue']];
        if ($this->activation_variable === null) {
            $this->activation_variable = 'module_'.$this->technical_code;
        }
   }
	
    /**
     * Renvoie les erreurs formattées
	 * 
	 * @return
     */
    public function get_messages()
    {
		$output = '';
		if(!empty($this->_errors)) {
			foreach($this->_errors as $this_error) {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $this_error))->fetch();
			}
		}
 		if(!empty($this->_messages)) {
			foreach($this->_messages as $this_message) {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $this_message))->fetch();
			}
		}
		return $output;
	}

	/**
     * Vérifie l'installation du module
	 * 
	 * @return
     */
	static public function check_install()
    {
		return null;
	}
	
    /**
     * Installe le module
	 * 
	 * @return
     */
    public function install()
    {
        // Hook de début d'installation
        call_module_hook('module_install_pre', array('object' => $this));

        // Vérifie si module déjà installé
        if (self::check_install()) {
            $this->_errors['installed'] = $this->name . ' déjà installé';
            return true;
        }
		// Vérifie la version du module
		if (!$this->check_compatibility()) {
            $this->_errors['version'] = $this->name . ' - version incompatible';
        }
		// Vérifie les dependences avec d'autres modules
        if (!empty($this->dependencies) && count($this->dependencies) > 0) {
            foreach ($this->dependencies as $this_dependency) {
				if (!in_array($this_dependency, $GLOBALS['modules_installed'])) {
                    $this->_errors['dependency'] = $this->name . ' - module required: ' . $this_dependency;
                }
            }
        }
        // Réaliser l'installation
        $this->execute('install');

        // Activer le module pour tous les sites
        $this->enable();

		// Hook de fin d'installation
        call_module_hook('module_install_post', array('object' => $this));

        return true;
    }
	
    /**
     * Désinstalle un module
	 * 
	 * @return
     */
    public function uninstall()
    {
        // Hook de début de désinstallation
        call_module_hook('module_uninstall_pre', array('object' => $this));

        // On ne vérifie pas si module déjà installé ou pas, $this->execute('uninstall') doit être appelé dans tous les cas pour compléter désinstallation si pas propre

        // Réaliser la désinstallation
        $this->execute('uninstall');

		// Hook de fin de désinstallation
        call_module_hook('module_uninstall_post', array('object' => $this));

        return true;
    }

    /**
     * Exécute une action
	 * 
	 * @param string $mode
	 * @return
	 */
    public function execute($mode = null)
    {
		return null;
	}

    /**
     * Active le module
	 * 
     * @param integer $site_id
	 * @return
     */
    public function enable($site_id = 0)
    {
		return set_configuration_variable(array('technical_code' => $this->activation_variable, 'string' => 1, 'type' => 'integer', 'site_id' => $site_id, 'origin' => $this->technical_code), true);
	}

    /**
     * Désactive le module
	 * 
     * @param integer $site_id
	 * @return
     */
    public function disable($site_id = 0)
    {
		return set_configuration_variable(array('technical_code' => $this->activation_variable, 'string' => 0, 'type' => 'integer', 'site_id' => $site_id, 'origin' => $this->technical_code), true);
	}
	
    /**
     * Désactive le module
	 * 
     * @param integer $site_id
	 * @return
     */
    public function check_compatibility()
    {
        if ((!empty($this->peel_versions_compatibility['min']) && version_compare(PEEL_VERSION, $this->peel_versions_compatibility_infos['min'], '<')) || (!empty($this->peel_versions_compatibility_infos['max']) && version_compare(PEEL_VERSION, $this->peel_versions_compatibility_infos['max'], '>'))) {
            return false;
        } else {
            return true;
        }
    }
}


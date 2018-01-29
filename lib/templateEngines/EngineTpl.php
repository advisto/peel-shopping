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
// $Id: EngineTpl.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Engine factory and interface for concrete EngineTpl adapters
 *
 * @abstract
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: EngineTpl.php 55332 2017-12-01 10:44:06Z sdelaporte $
 * @access public
 */
abstract class EngineTpl {
	private $name;
	private $create_time;
	/**
	 * Creates EngineTpl adapter of specified type
	 *
	 * @param int $type Specifies type of engine template to use, value must be one of EngineTpl::TYPE_* constants
	 * @param string $templadeDir Sets template directory of template sources
	 * @param boolean $forceCompile Enables/disables templates (re)compilation on every invocation if possible
	 * @param boolean $debugging Enables/disables debugging mode if possible
	 * @return EngineTpl
	 */
	public static function create($type, $templadeDir, $forceCompile = false, $debugging = false)
	{
		switch ($type) {
			case 'smarty':
				require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'SmartyEngine.php';
				$e = new SmartyEngine($templadeDir, $forceCompile, $debugging);
				$e->name = $type;
				return $e;

			case 'twig':
				require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TwigEngine.php';
				$e = new TwigEngine($templadeDir, $forceCompile, $debugging);
				$e->name = $type;
				return $e;
		}
	}

	/**
	 * Returns created adapter type name
	 *
	 * @return string adapter type name
	 */
	final public function getName()
	{
		return $this->name;
	}

	/**
	 * assigns global Template variable(s)
	 *
	 * @param array $ |string $tpl_var the template variable name(s)
	 * @param mixed $value the value to assign
	 * @return
	 */
	abstract public function assign($tpl_var, $value = null);

	/**
	 * displays a Template
	 *
	 * @param string $template the resource handle of the template file or template object
	 */
	abstract public function display($template);

	/**
	 * fetches a rendered template and returns the outoput
	 *
	 * @param string $template the resource handle of the template file
	 * @return string rendered template output
	 */
	abstract public function fetch($template);

	/**
	 * This creates a template object which later can be rendered by the display or fetch
	 *
	 * @param string $template the resource handle of the template file
	 * @param array $data associative array containing the name/value pairs of variables which get assigned to the template object
	 * @return Template template object
	 */
	abstract public function createTemplate($template, array $data = null);
}


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
// $Id: TwigTemplate.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Template.php';
/**
 * Simplified alias class for Twig_Template
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: TwigTemplate.php 55332 2017-12-01 10:44:06Z sdelaporte $
 * @access public
 */
class TwigTemplate implements Template {
	/**
	 *
	 * @var Twig_Template
	 */
	private $sit;
	private $context = array();

	public function __construct($sit, $context)
	{
		$this->sit = $sit;
		$this->context = $context;
	}

	public function assign($tpl_var, $value = null)
	{
		$this->context[$tpl_var] = $value;
	}

	public function display()
	{
		$this->sit->display($this->context);
	}

	public function fetch()
	{
		//try {
			return $this->sit->render($this->context);
		//} catch (Twig_Error_Syntax $e) {
		//	return null;
		//}
	}
}


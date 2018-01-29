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
// $Id: Template.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * General Template interface
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: Template.php 55332 2017-12-01 10:44:06Z sdelaporte $
 * @access public
 */
interface Template {
	/**
	 * assigns Template variable(s)
	 *
	 * @param array $ |string $tpl_var the template variable name(s)
	 * @param mixed $value the value to assign
	 * @return
	 */
	function assign($tpl_var, $value = null);

	/**
	 * displays a Template
	 */
	function display();

	/**
	 * fetches a rendered template and returns the outoput
	 *
	 * @return string rendered template output
	 */
	function fetch();
}


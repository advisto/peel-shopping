<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: FormError.php 35805 2013-03-10 20:43:50Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * FormError
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: FormError.php 35805 2013-03-10 20:43:50Z gboussin $
 * @access public
 */
class FormError {
	var $error = array();

	/**
	 * formError::bg()
	 *
	 * @param mixed $name
	 * @return
	 */
	function bg($name)
	{
		if (isset($this->error[$name])) {
			return ' class="form_item_error"';
		}
	}

	/**
	 * formError::text()
	 *
	 * @param mixed $name
	 * @return
	 */
	function text($name = null)
	{
		if (!empty($name) && isset($this->error[$name])) {
			$output = ((!empty($this->error[$name])) ? $this->error[$name] : $GLOBALS['strEmptyField']);
		} elseif (empty($name) && $this->count()) {
			$output = '';
			foreach($this->error as $this_name => $this_value) {
				if (!empty($this->error[$this_name])) {
					$output .= $this->error[$this_name];
				} else {
					$output .= '[' . $this_name . ']' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $GLOBALS['strEmptyField'];
				}
				$output .= '<br />';
			}
		}
		if(!empty($output)) {
			return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $output))->fetch();
		} else {
			return false;
		}
	}

	/**
	 * formError::add()
	 *
	 * @param string $name
	 * @param string $text
	 * @return
	 */
	function add($name, $text = null)
	{
		$this->error[$name] = ($text ? $text : '');
	}

	/**
	 * formError::has_error()
	 *
	 * @param string $name
	 * @return
	 */
	function has_error($name)
	{
		return isset($this->error[$name]);
	}

	/**
	 * formError::count()
	 *
	 * @return
	 */
	function count()
	{
		return count($this->error);
	}

	/**
	 * Valide les informations
	 *
	 * @param array $frm Array with all fields data
	 * @param array $empty_field_messages_array
	 * @return
	 */
	function valide_form(&$frm, $empty_field_messages_array = array())
	{
		foreach($empty_field_messages_array as $this_field => $this_message) {
			if (empty($frm[$this_field])) {
				$this->add($this_field, $this_message);
			}
		}
	}
}

?>
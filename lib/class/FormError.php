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
// $Id: FormError.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * FormError
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: FormError.php 55332 2017-12-01 10:44:06Z sdelaporte $
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
	function text($name = null, $return_without_style = false)
	{
		if (!empty($name) && isset($this->error[$name])) {
			$output = ((!empty($this->error[$name])) ? $this->error[$name] : $GLOBALS['STR_EMPTY_FIELD']);
		} elseif (empty($name) && $this->count()) {
			$output = '';
			foreach($this->error as $this_name => $this_value) {
				if (!empty($this->error[$this_name])) {
					$output .= $this->error[$this_name];
				} else {
					$output .= '[' . $this_name . ']' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $GLOBALS['STR_EMPTY_FIELD'];
				}
				$output .= '<br />';
			}
		}
		if(!empty($output)) {
			if (!empty($return_without_style)) {
				return $output;
			} else {
				return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $output))->fetch();
			}
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
	 * Valide les informations d'un tableau de données et ajoute des erreurs à l'objet d'erreur si nécessaire
	 *
	 * @param array $frm Array with all fields data
	 * @param array $error_field_messages_array
	 * @param array $field_minimal_length_messages_array
	 * @param array $field_validation_function_names_array
	 * @return
	 */
	function valide_form(&$frm, $error_field_messages_array = array(), $field_minimal_lengths_array = array(), $field_validation_function_names_array = array())
	{
		if(!empty($error_field_messages_array)) {
			foreach($error_field_messages_array as $this_field => $this_message) {
				if (((empty($frm[$this_field]) && vb($frm[$this_field]) !== '0') || (is_array($frm[$this_field]) && count($frm[$this_field]) == 0)) || (!empty($field_minimal_lengths_array[$this_field]) && StringMb::strlen($frm[$this_field])<$field_minimal_lengths_array[$this_field]) || (!empty($field_validation_function_names_array[$this_field]) && $field_validation_function_names_array[$this_field]($frm[$this_field]) === false)) {
					if(StringMb::substr($this_message, 0, 4) == 'STR_' && isset($GLOBALS[$this_message])) {
						$this_text = $GLOBALS[$this_message];
					} else {
						$this_text = $this_message;
					}
					$this->add($this_field, $this_text);
				} elseif (is_array($this_message)) {
					foreach($this_message as $key=>$error_message) {
						$this->add($this_field[$key], $error_message);
					}
				}
			}
		}
	}
}


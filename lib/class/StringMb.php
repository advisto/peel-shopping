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
// $Id: StringMb.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

$GLOBALS['ucfirsts'] = array('zh' => false, 'ja' => false);

/**
 * String : this class allows full compatibility with utf8 by using mbstring if available
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: StringMb.php 55332 2017-12-01 10:44:06Z sdelaporte $
 * @access public
 */
class StringMb {
	/**
	 * Returns the length of the given string.
	 *
	 * @param string $string
	 * @return
	 */
	public static function strlen($string)
	{
		if (function_exists('mb_strlen') && GENERAL_ENCODING != 'iso-8859-1') {
			return mb_strlen($string);
		} else {
			return strlen($string);
		}
	}

	/**
	 * Returns the numeric position of the first occurrence of needle in the haystack  string.
	 * Unlike the strrpos() before PHP 5, this function can take a full string as the needle  parameter and the entire string will be used.
	 *
	 * @param string $haystack The string to search in
	 * @param string $needle If needle is not a string, it is converted to an integer and applied as the ordinal value of a character.
	 * @param integer $offset The optional offset parameter allows you to specify which character in haystack to start searching. The position returned is still relative to the beginning of haystack.
	 * @return
	 */
	public static function strpos($haystack, $needle, $offset = 0)
	{
		if(is_array($haystack) && defined('PEEL_DEBUG')) {
			var_dump(debug_backtrace());
		}
		if($needle!=='' && $needle!== null) {
			if (function_exists('mb_strpos')) {
				return mb_strpos($haystack, $needle, $offset);
			} else {
				return strpos($haystack, $needle, $offset);
			}
		} else {
			return false;
		}
	}

	/**
	 * Returns the numeric position of the last occurrence of needle in the haystack string. Note that the needle in this case can only be a single character in PHP 4.
	 * If a string is passed as the needle, then only the first character of that string will be used.
	 *
	 * @param string $haystack The string to search in
	 * @param string $needle If needle is not a string, it is converted to an integer and applied as the ordinal value of a character.
	 * @param integer $offset May be specified to begin searching an arbitrary number of characters into the string. Negative values will stop searching at an arbitrary point prior to the end of the string.
	 * @return
	 */
	public static function strrpos($haystack, $needle, $offset = 0)
	{
		if ($offset > 0) {
			$offset = min($offset, StringMb::strlen($haystack));
		} elseif ($offset < 0) {
			$offset = max($offset, - StringMb::strlen($haystack));
		}
		if (function_exists('mb_strrpos')) {
			if (empty($offset)) {
				return mb_strrpos($haystack, $needle);
			} elseif (version_compare(PHP_VERSION, '5.2.0', '>=')) {
				// $offset pour mb_strrpos est introduit avec PHP 5.2
				return mb_strrpos($haystack, $needle, $offset);
			} elseif (version_compare(PHP_VERSION, '5.0.0', '>=')) {
				// $offset pour strrpos est introduit avec PHP 5 et non pas 5.2
				return strrpos($haystack, $needle, $offset);
			} else {
				return false;
			}
		} else {
			if (empty($offset)) {
				return strrpos($haystack, $needle);
			} else {
				return strrpos($haystack, $needle, $offset);
			}
		}
	}

	/**
	 * Returns the portion of string specified by the start and length parameters.
	 *
	 * @param string $string
	 * @param integer $start
	 * @param integer $length
	 * @return
	 */
	public static function substr($string, $start, $length = null)
	{
		if (function_exists('mb_substr')) {
			if ($length !== null) {
				return mb_substr($string, $start, $length);
			} else {
				return mb_substr($string, $start);
			}
		} else {
			if ($length !== null) {
				return substr($string, $start, $length);
			} else {
				return substr($string, $start);
			}
		}
	}

	/**
	 * Returns string with all alphabetic characters converted to lowercase.
	 *
	 * @param string $string The input string.
	 * @return
	 */
	public static function strtolower($string)
	{
		if(empty($GLOBALS['site_parameters']['string_case_change_forbidden']) || empty($GLOBALS['site_parameters']['string_case_change_forbidden'][$_SESSION['session_langue']])) {
			if (function_exists('mb_strtolower')) {
				return mb_strtolower($string);
			} else {
				return strtolower($string);
			}
		} else {
			return $string;
		}
	}

	/**
	 * Returns string with all alphabetic characters converted to uppercase.
	 *
	 * @param string $string The input string.
	 * @return
	 */
	public static function strtoupper($string)
	{
		if(empty($GLOBALS['site_parameters']['string_case_change_forbidden']) || empty($GLOBALS['site_parameters']['string_case_change_forbidden'][$_SESSION['session_langue']])) {
			if (function_exists('mb_strtoupper')) {
				return mb_strtoupper($string);
			} else {
				return strtoupper($string);
			}
		} else {
			return $string;
		}
	}

	/**
	 * Returns string with first letter uppercase.
	 *
	 * @param string $string The input string.
	 * @return
	 */
	public static function ucfirst($string)
	{
		if(empty($GLOBALS['site_parameters']['string_case_change_forbidden']) || empty($GLOBALS['site_parameters']['string_case_change_forbidden'][$_SESSION['session_langue']])) {
			if (function_exists('mb_ucfirst')) {
				return mb_ucfirst($string);
			} else {
				return StringMb::strtoupper(StringMb::substr($string, 0, 1)) . StringMb::substr($string, 1);
			}
		} else {
			return $string;
		}
	}

	/**
	 * Returns the number of times the needle substring occurs in the haystack string. Please note that needle is case sensitive.
	 * WARNING : This functions has only 2 arguments, as the mb_substr_count has less arguments than the non-multibyte function substr_count
	 *
	 * @param string $string The input string.
	 * @param integer $searched
	 * @return
	 */
	public static function substr_count($string, $searched)
	{
		if (function_exists('mb_substr_count')) {
			return mb_substr_count($string, $searched);
		} else {
			return substr_count($string, $searched);
		}
	}

	/**
	 * Adds a seperator every $max_part_length characters
	 *
	 * @param string $string The input string.
	 * @param integer $max_part_length
	 * @param string $separator
	 * @return
	 */
	public static function cut_with_separator($string, $max_part_length = 40, $separator = ' ')
	{
		$left_string = '';
		$right_string = $string;
		while(StringMb::strlen($right_string)>$max_part_length) {
			// On évite de faire une fonction récursive pour meilleure performance => on fait une boucle
			// on coupe après $max_part_length caractères, en faisant attention aux entités HTML gérées par str_shorten
			$new_part = StringMb::str_shorten($right_string, $max_part_length, '', null, null);
			$left_string .= $new_part . $separator;
			$right_string = StringMb::substr($right_string, StringMb::strlen($new_part));
		}
		return $left_string.$right_string;
	}

	/**
	 * Raccourcit une chaine de caractère en insérant au milieu ou à la fin un séparateur
	 *
	 * @param string $string
	 * @param integer $length_limit
	 * @param string $middle_separator
	 * @param string $ending_if_no_middle_separator
	 * @param mixed $ideal_length_with_clean_cut_if_possible
	 * @return
	 */
	public static function str_shorten($string, $length_limit, $middle_separator = '', $ending_if_no_middle_separator = '...', $ideal_length_with_clean_cut_if_possible = null)
	{
		$length = StringMb::strlen($string);
		if ($ideal_length_with_clean_cut_if_possible > 0 && $length > $ideal_length_with_clean_cut_if_possible) {
			// Gestion d'une coupure propre si possible entre $ideal_length_with_clean_cut_if_possible et $length_limit
			$middle_separator = null;
			foreach(array('.', '!', '?', ';', ':', ',', ' ', '=', '+', '-', '{', '}', '[', ']', '(', ')', '<', '>', '_', '#', '*') as $this_separator) {
				// On fait un test sur les séparateurs, du plus important au moins important
				$possible_cut = StringMb::strpos($string, $this_separator, $ideal_length_with_clean_cut_if_possible);
				if ($possible_cut !== false && $possible_cut < $length_limit - StringMb::strlen($ending_if_no_middle_separator)) {
					// On prend cette valeur comme étant la limite de coupure à faire
					$length_limit = $possible_cut + StringMb::strlen($ending_if_no_middle_separator);
					// Cette gestion a la priorité sur une coupure au milieu
					$middle_separator = null;
					// On s'arrête car on a trouvé une coupure convenable
					break;
				}
			}
		}
		if (!empty($middle_separator)) {
			$ending = '';
		} else {
			$ending = $ending_if_no_middle_separator;
		}
		if (StringMb::strlen($middle_separator) + StringMb::strlen($ending) >= $length_limit) {
			// CAS PARTICULIER (protection) : Dans le cas où la largeur max est inférieur à la largeur du séparateur, on coupe simplement la chaîne à la longueur max
			return StringMb::substr($string, 0, $length_limit);
		} elseif ($length > $length_limit) {
			// $cut_size est le nombre de caractères à retirer
			$cut_size = $length + StringMb::strlen($middle_separator) + StringMb::strlen($ending) - $length_limit;
			if (!empty($middle_separator)) {
				// On coupe au milieu
				$cut_start = ceil(($length - $cut_size) / 2);
				$ending_text = StringMb::substr($string, $cut_start + $cut_size);
				if (StringMb::strlen($ending_text) && StringMb::strpos(StringMb::substr($ending_text, 0, 8), '&') === false && StringMb::strpos(StringMb::substr($ending_text, 0, 8), ';') !== false) {
					// Si le texte de fin commence par un morceau de fin d'entité, alors on retire ce morceau.
					$ending_text = StringMb::substr($ending_text, StringMb::strpos($ending_text, ';') + 1);
				}
				if (StringMb::strlen($ending_text)>=8) {
					$tested = StringMb::substr($ending_text, -8);
					if (StringMb::strpos($tested, '&') !== false) {
						$tested = StringMb::substr($tested, StringMb::strrpos($tested, '&'));
						if(StringMb::strpos($tested, ';') === false) {
							// Si le texte se termine par une entité pas finie (ex : &#34) on la retire.
							$ending_text = StringMb::substr($ending_text, 0, StringMb::strrpos($ending_text, '&'));
						}
					}
				}
			} else {
				// On coupe à la fin de la chaine
				$cut_start = $length - $cut_size;
				$ending_text = '';
			}
			$beginning_text = StringMb::substr($string, 0, $cut_start);
			if (StringMb::strlen($beginning_text)>=8) {
				$tested = StringMb::substr($beginning_text, -8);
				if (StringMb::strpos($tested, '&') !== false) {
					$tested = StringMb::substr($tested, StringMb::strrpos($tested, '&'));
					if(StringMb::strpos($tested, ';') === false) {
						// Si le texte se termine par une entité pas finie (ex : &#34) on la retire.
						$beginning_text = StringMb::substr($beginning_text, 0, StringMb::strrpos($beginning_text, '&'));
					}
				}
			}
			$string = $beginning_text . $middle_separator . $ending_text . $ending;
			while (StringMb::substr_count($string, '(') > StringMb::substr_count($string, ')')) {
				// Si le texte coupé a des parenthèses fermantes manquantes
				$string .= ')';
			}
			return $string;
		} else {
			return $string;
		}
	}

	/**
	 * On rajoute des espaces à l'intérieur des mots trop longs => à utiliser pour éviter de casser une mise en page
	 *
	 * @param string $string
	 * @param integer $length_limit
	 * @param string $separator
	 * @param boolean $force_shorten_if_special_content
	 * @return
	 */
	public static function str_shorten_words($string, $length_limit = 100, $separator = " ", $force_shorten_if_special_content = false, $add_separator_instead_of_cutting = true)
	{
		// On coupe autour de tous les mots
		$sentences_array = explode("\n", $string);
		foreach($sentences_array as $this_main_sentence_key => $this_main_sentence) {
			$string_array = explode("\t", $this_main_sentence);
			foreach($string_array as $this_main_key => $this_main_string) {
				$tab = explode(' ', $this_main_string);
				foreach($tab as $key => $this_string) {
					// "quote=" => Compatibilité avec les enchaînements de quote dans lesquels il n'y a pas d'espace
					// On met une condition strlen (et non pas StringMb::strlen) pour aller plus rapide
					if (strlen($this_string) > $length_limit && ($force_shorten_if_special_content || (StringMb::strpos($this_string, 'http') === false && StringMb::strpos($this_string, 'quote=') === false && StringMb::strpos($this_string, '[/quote]') === false))) {
						if($add_separator_instead_of_cutting) {
							$tab[$key] = StringMb::cut_with_separator($this_string, $length_limit, $separator);
						} else {
							$tab[$key] = StringMb::str_shorten($this_string, $length_limit, $separator, null, null);
						}
					}
				}
				$string_array[$this_main_key] = implode(' ', $tab);
			}
			// on reconstitue la chaîne initiale
			$sentences_array[$this_main_sentence_key] = implode("\t", $string_array);
		}
		$string = implode("\n", $sentences_array);
		return $string;
	}

	/**
	 * convert_accents()
	 *
	 * @param string $string
	 * @param boolean $convert_umlaut
	 * @param boolean $strip_umlaut
	 * @return
	 */
	public static function convert_accents($string, $convert_umlaut = false, $strip_umlaut = true)
	{
		$string = str_replace(array('à', 'á', 'â', 'ã', 'å'), 'a', $string);
		$string = str_replace(array('À', 'Á', 'Â', 'Ã', 'Å'), 'A', $string);
		$string = str_replace(array('è', 'é', 'ê', 'ë'), 'e' , $string);
		$string = str_replace(array('È', 'É', 'Ê', 'Ë'), 'E' , $string);
		$string = str_replace(array('ì', 'í', 'î', 'ï'), 'i' , $string);
		$string = str_replace(array('Ì', 'Í', 'Î', 'Ï'), 'I' , $string);
		$string = str_replace(array('ò', 'ó', 'ô', 'õ', 'ø'), 'o' , $string);
		$string = str_replace(array('Ò', 'Ó', 'Ô', 'Õ', 'Ø'), 'O' , $string);
		$string = str_replace(array('ù', 'ú', 'û'), 'u' , $string);
		$string = str_replace(array('Ù', 'Ú', 'Û'), 'U' , $string);
		$string = str_replace(array('æ', 'œ', 'ý', 'ÿ', 'ç', 'ß', 'ñ'), array('ae', 'oe', 'y', 'y', 'c', 'ss', 'n'), $string);
		$string = str_replace(array('Æ', 'Œ', 'Ý', 'Ÿ', 'Ç', 'ß', 'Ñ'), array('AE', 'OE', 'Y', 'Y', 'C', 'SS', 'N'), $string);
		if ($convert_umlaut) {
			$string = str_replace(array('ä', 'ö', 'ü'), array('ae', 'oe', 'ue'), $string);
			$string = str_replace(array('Ä', 'Ö', 'Ü'), array('AE', 'OE', 'UE'), $string);
		} elseif ($strip_umlaut) {
			$string = str_replace(array('ä', 'ö', 'ü'), array('a', 'o', 'u'), $string);
			$string = str_replace(array('Ä', 'Ö', 'Ü'), array('A', 'O', 'U'), $string);
		}
		return $string;
	}

	/**
	 * Converts the character encoding of string $string to $new_encoding from optionally $original_encoding.
	 * Examples of encodings : UTF-16, UTF-8, JIS, ISO-8859-1, ISO-8859-15
	 * If the mbstring is not defined, it return the original $string
	 *
	 * @param string $string
	 * @param string $new_encoding
	 * @param string $original_encoding
	 * @return
	 */
	public static function convert_encoding($string, $new_encoding, $original_encoding = null)
	{
		$new_encoding = strtolower($new_encoding);
		$original_encoding = strtolower($original_encoding);
		if (empty($original_encoding)) {
			$original_encoding = GENERAL_ENCODING;
		}
		// Le sigle euro n'est pas dans iso-8859-1, et par ailleurs iso-8859-15 n'est pas sur
		// tous les serveurs => on va donc contourner le problème.
		if ($new_encoding == $original_encoding) {
			return $string;
		} elseif ($new_encoding == 'iso-8859-1' && $original_encoding == 'utf-8') {
			$euro_iso = mb_convert_encoding('€', "CP1252", 'utf-8');
			return str_replace('-,/)[_', $euro_iso, utf8_decode(str_replace('€', '-,/)[_', $string)));
		} elseif ($new_encoding == 'utf-8' && $original_encoding == 'iso-8859-1') {
			return StringMb::utf8_encode($string);
		} elseif (function_exists('mb_convert_encoding') && (!function_exists('mb_list_encodings') || (in_array(StringMb::strtoupper($new_encoding), mb_list_encodings()) && in_array(StringMb::strtoupper($original_encoding), mb_list_encodings())))) {
			return mb_convert_encoding($string, $new_encoding, $original_encoding);
		} else {
			return $string;
		}
	}

	/**
	 * Convert all applicable characters to HTML entities
	 * Cette fonction sert si on veut afficher du contenu brut dans du HTML ou du XML : elle transforme les caractères spéciaux en entités HTML,
	 * et traite les incohérences qui pourraient créer des invalidités du contenu XHTML ou XML
	 * Pour traiter du contenu de fichier XML, utiliser avec $encode_only_isolated_amperstands = true ce qui ne modifie pas le texte hormis corriger les & isolés en &amp;
	 *
	 * @param string $string
	 * @param string $flags the optional second flags parameter lets you define what will be done with 'single' and "double" quotes
	 * @param string $charset defines character set used in conversion
	 * @param boolean $suppr_endline
	 * @param boolean $encode_only_isolated_amperstands
	 * @return
	 */
	public static function htmlentities ($string, $flags = ENT_COMPAT, $charset = GENERAL_ENCODING, $suppr_endline = false, $encode_only_isolated_amperstands = false, $decode_html_entities_first = false)
	{
		if ($suppr_endline) {
			$string = str_replace(array("\r", "\n"), ' ', $string);
		}
		if ($decode_html_entities_first) {
			$string = html_entity_decode ($string, $flags, $charset);
		}
		// On retire des caractères non SGML
		$string = str_replace(array('•', '™', '€', '’'), array('', '', '&euro;', "'"), $string);
		if ($encode_only_isolated_amperstands) {
			// On ne remplace que les & qui sont tout seuls. On ne touche pas au reste
			// ?! => assertion négative (et comme c'est une assertion, ça ne rentre pas de le résultat)
			$string = str_replace('&amp;amp;', '&amp;', preg_replace('/&(?!#?[xX]?([0-9a-zA-Z]{1,9});)/', '&amp;', $string));
		} else {
			// On encode les entités, mais si il y en avait déjà, dans ce cas on se retrouverait avec une entité du type &amp;entité;
			// => On reconstruit donc ensuite les entités qui auraient été cassées grave au preg_replace
			// ?= => assertion positive (et comme c'est une assertion, ça ne rentre pas de le résultat)
			$string = preg_replace('/&amp;(?=#?[xX]?([0-9a-zA-Z]{1,9});)/', '&', htmlentities($string, $flags, $charset));
		}
		// Si le stringe se termine par une entité pas finie (ex : &#34) on la retire.
		// NB : comme l'entité n'est pas finie, on ne l'a pas détectée dans les preg_replace, donc elle est sous la forme &amp;#
		if (StringMb::strpos($string, '&amp;#') !== false && StringMb::strpos($string, '&amp;#') >= StringMb::strlen($string) - 9) {
			if (StringMb::substr($string, StringMb::strlen($string) - 6, 6) == '&amp;#') {
				$string = StringMb::substr($string, 0, StringMb::strlen($string) - 6);
			} elseif (StringMb::substr($string, StringMb::strlen($string) - 7, 6) == '&amp;#') {
				$string = StringMb::substr($string, 0, StringMb::strlen($string) - 7);
			} elseif (StringMb::substr($string, StringMb::strlen($string) - 8, 6) == '&amp;#' && StringMb::strpos(StringMb::substr($string, StringMb::strlen($string) - 8), ';') === false) {
				$string = StringMb::substr($string, 0, StringMb::strlen($string) - 8);
			} elseif (StringMb::substr($string, StringMb::strlen($string) - 9, 6) == '&amp;#' && StringMb::strpos(StringMb::substr($string, StringMb::strlen($string) - 9), ';') === false) {
				$string = StringMb::substr($string, 0, StringMb::strlen($string) - 8);
			}
		}
		return $string;
	}

	/**
	 * Méthode de compatibilité avec anciennes versions de PEEL utilisant str_htmlentities au lieu de htmlentities
	 *
	 * @param mixed $string
	 * @param boolean $suppr_endline
	 * @param boolean $encode_only_isolated_amperstands
	 * @return
	 */
	public static function str_htmlentities($string, $suppr_endline = false, $encode_only_isolated_amperstands = false)
	{
		return StringMb::htmlentities($string, ENT_COMPAT, GENERAL_ENCODING, $suppr_endline, $encode_only_isolated_amperstands);
	}

	/**
	 * Méthode de compatibilité avec anciennes versions de PEEL utilisant textEncode au lieu de htmlentities
	 *
	 * @param mixed $string
	 * @param boolean $suppr_endline
	 * @param boolean $encode_only_isolated_amperstands
	 * @return
	 */
	public static function textEncode($string, $suppr_endline = false, $encode_only_isolated_amperstands = false)
	{
		return StringMb::htmlentities($string, ENT_COMPAT, GENERAL_ENCODING, $suppr_endline, $encode_only_isolated_amperstands);
	}

	/**
	 * Encode une chaine de caractères pour affichage dans un value=""
	 *
	 * @param string $value
	 * @param mixed $flags
	 * @return
	 */
	public static function str_form_value($value, $flags = ENT_COMPAT)
	{
		if (function_exists('html_entity_decode') && (version_compare(PHP_VERSION, '5.0.0', '>=') || GENERAL_ENCODING == 'iso-8859-1')) {
			// Le 4è argument de htmlspecialchars appelé $double_encode n'est pas disponible avant PHP 5.2.3
			// Il faut donc appeler htmlentities_decode d'abord pour éviter le double encodage des entités HTML
			return @htmlspecialchars(StringMb::html_entity_decode($value, ENT_QUOTES, GENERAL_ENCODING), $flags, GENERAL_ENCODING);
		} else {
			// Version simplifiée si PHP < 4.3
			// ou si PHP >=4.3 et <5 car sinon pas de support de UTF-8
			return str_replace('"', '&quot;', $value);
		}
	}

	/**
	 * This function is StringMb::htmlspecialchars_decode with php4 compatibility
	 *
	 * @param mixed $string
	 * @param mixed $style
	 * @return
	 */
	public static function htmlspecialchars_decode($string, $style = ENT_COMPAT)
	{
		$translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS, $style));
		if ($style === ENT_QUOTES) {
			$translation['&#039;'] = '\'';
		}
		return strtr($string, $translation);
	}

	/**
	 * StringMb::html_entity_decode()
	 *
	 * @param string $string
	 * @param mixed $quote_style
	 * @param mixed $charset
	 * @return
	 */
	public static function html_entity_decode($string, $quote_style = ENT_COMPAT, $charset = GENERAL_ENCODING)
	{
		if (version_compare(PHP_VERSION, '5.0.0', '>=')) {
			return html_entity_decode($string, $quote_style, $charset);
		} else {
			// Nécessaire pour éviter le bogue : "Warning: cannot yet handle MBCS in html entity decode"
			return html_entity_decode($string, $quote_style);
		}
	}

	/**
	 * StringMb::html_entity_decode_if_needed()
	 *
	 * @param string $string
	 * @return
	 */
	public static function html_entity_decode_if_needed($string)
	{
		if (!empty($GLOBALS['site_parameters']['compatibility_mode_with_htmlentities_encoding_content']) && StringMb::strpos($string, '<') === false) {
			return StringMb::html_entity_decode($string);
		} else {
			return $string;
		}
	}

	/**
	 * StringMb::strip_tags()
	 *
	 * @param string $string
	 * @return
	 */
	public static function strip_tags($string, $allowed_tags = null)
	{
		$string = str_replace(array('<br />', '<br>', '</p>', '</td>', '</tr>', '</div>', '</h3>', '</h4>'), array("\n", "\n", "\n", ' ', ' ', ' ', ' ', ' '), str_replace(array('<h1', '</h1>','<h2', '</h2>', '<li', "\t", "\r\n", "\r"), array(' - <h1', '</h1> - ', ' - <h2', '</h2> - ', "\n- <li", ' ', "\n", "\n"), $string));
		if(StringMb::strpos($string, '<') !== false && StringMb::strpos($string, '>') !== false) {
			// Evite de couper une chaine avec juste < dedans 
			// Change "abc<123" en "abc< #@µ$123" puis on retire l'espace ensuites
			$string = preg_replace("/(<)(\d)/", "$1 #@µ$2", $string);
			$string = strip_tags($string, $allowed_tags);
			$string = preg_replace("/(<) #@µ(\d)/", "$1$2", $string);
		}
		return str_replace(array(' -  -  - ', ' -  - '), ' - ', str_replace(array("\n\n\n\n\n", "\n\n\n\n", "\n\n\n", '     ','    ', '   ', '  '), array("\n\n", "\n\n", "\n\n", ' ', ' ', ' ', ' '), $string));
	}

	/**
	 * Fonction de compatibilité avec de vieilles versions de PEEL ou du contenu qui vient d'ailleurs
	 *
	 * @param mixed $string
	 * @return
	 */
	public static function nl2br_if_needed($string)
	{
		$has_no_br = StringMb::strpos($string, '&lt;br') === false && StringMb::strpos($string, '<br') === false;
		// Attention aux balises param
		$has_no_p = StringMb::strpos($string, '&lt;p ') === false && StringMb::strpos($string, '<p ') === false && StringMb::strpos($string, '&lt;p&gt;') === false && StringMb::strpos($string, '<p>') === false;
		$has_no_table = StringMb::strpos($string, '&lt;table') === false && StringMb::strpos($string, '<table') === false;
		$has_no_ul = StringMb::strpos($string, '&lt;ul') === false && StringMb::strpos($string, '<ul') === false;
		$has_no_script = StringMb::strpos($string, '&lt;script') === false && StringMb::strpos($string, '<script') === false;
		$has_no_div = StringMb::strpos($string, '&lt;div') === false && StringMb::strpos($string, '<div') === false;
		if ($has_no_br && $has_no_p && $has_no_table && $has_no_ul && $has_no_script && $has_no_div) {
			$string = str_replace(array("\n"), "<br />\n", str_replace(array("\r\n", "\r"), "\n", $string));
		}
		return $string;
	}

	/**
	 * Détecte si au moins un caractère est manifestement de l'UTF8
	 *
	 * @param mixed $string
	 * @return
	 */
	public static function detect_utf8_characters($string)
	{
		// On n'utilise pas mb_ detect_ encoding à cause de ses multiples bugs
		return preg_match('%(?:
			[\xC2-\xDF][\x80-\xBF]				# non-overlong 2-byte
			|\xE0[\xA0-\xBF][\x80-\xBF]     	# excluding overlongs
			|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
			|\xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
			|\xF0[\x90-\xBF][\x80-\xBF]{2}		# planes 1-3
			|[\xF1-\xF3][\x80-\xBF]{3} 			# planes 4-15
			|\xF4[\x80-\x8F][\x80-\xBF]{2}		# plane 16
			)+%xs', $string);
	}

	/**
	 * Si vous avez des utilisateurs sous windows qui saisissent du contenu dans une interface qui l'insère
	 * dans une base en ISO-88591 vous risquez  d'avoir des surprises.
	 * Cette fonction permet de "nettoyer" l'encodage windows cp1552 en iso-88591 propre*
	 *
	 * @param mixed $string
	 * @return
	 */
	public static function utf8_encode($string)
	{
		$cp1252_map = array("\xc2\x80" => "\xe2\x82\xac",/* EURO SIGN */
			"\xc2\x82" => "\xe2\x80\x9a",/* SINGLE LOW-9 QUOTATION MARK */
			"\xc2\x83" => "\xc6\x92",/* LATIN SMALL LETTER F WITH HOOK */
			"\xc2\x84" => "\xe2\x80\x9e",/* DOUBLE LOW-9 QUOTATION MARK */
			"\xc2\x85" => "\xe2\x80\xa6",/* HORIZONTAL ELLIPSIS */
			"\xc2\x86" => "\xe2\x80\xa0",/* DAGGER */
			"\xc2\x87" => "\xe2\x80\xa1",/* DOUBLE DAGGER */
			"\xc2\x88" => "\xcb\x86",/* MODIFIER LETTER CIRCUMFLEX ACCENT */
			"\xc2\x89" => "\xe2\x80\xb0",/* PER MILLE SIGN */
			"\xc2\x8a" => "\xc5\xa0",/* LATIN CAPITAL LETTER S WITH CARON */
			"\xc2\x8b" => "\xe2\x80\xb9",/* SINGLE LEFT-POINTING ANGLE QUOTATION */
			"\xc2\x8c" => "\xc5\x92",/* LATIN CAPITAL LIGATURE OE */
			"\xc2\x8e" => "\xc5\xbd",/* LATIN CAPITAL LETTER Z WITH CARON */
			"\xc2\x91" => "\xe2\x80\x98",/* LEFT SINGLE QUOTATION MARK */
			"\xc2\x92" => "\xe2\x80\x99",/* RIGHT SINGLE QUOTATION MARK */
			"\xc2\x93" => "\xe2\x80\x9c",/* LEFT DOUBLE QUOTATION MARK */
			"\xc2\x94" => "\xe2\x80\x9d",/* RIGHT DOUBLE QUOTATION MARK */
			"\xc2\x95" => "\xe2\x80\xa2",/* BULLET */
			"\xc2\x96" => "\xe2\x80\x93",/* EN DASH */
			"\xc2\x97" => "\xe2\x80\x94",/* EM DASH */
			"\xc2\x98" => "\xcb\x9c",/* SMALL TILDE */
			"\xc2\x99" => "\xe2\x84\xa2",/* TRADE MARK SIGN */
			"\xc2\x9a" => "\xc5\xa1",/* LATIN SMALL LETTER S WITH CARON */
			"\xc2\x9b" => "\xe2\x80\xba",/* SINGLE RIGHT-POINTING ANGLE QUOTATION*/
			"\xc2\x9c" => "\xc5\x93",/* LATIN SMALL LIGATURE OE */
			"\xc2\x9e" => "\xc5\xbe",/* LATIN SMALL LETTER Z WITH CARON */
			"\xc2\x9f" => "\xc5\xb8"/* LATIN CAPITAL LETTER Y WITH DIAERESIS*/
			);
		return strtr(utf8_encode($string), $cp1252_map);
	}

	/**
	 * Fonction qui nettoie le HTML
	 *
	 * @param string $text
	 * @param integer $max_width
	 * @param boolean $allow_form
	 * @param boolean $allow_object
	 * @param boolean $allow_class
	 * @param mixed $additional_config
	 * @param boolean $safe
	 * @param string $additional_elements
	 * @param integer $max_caracters_length On coupe le texte si le nombre de caractères dépasse la valeur autorisée ads_max_caracters_length de 10%, avant de le passer dans getCleanHTML qui va regénérer les balises de cloture manquantes - En cas de langue avec beaucoup de caractères spéciaux, cette valeur doit être fortement inférieure à la taille du champ en base de données
	 * @param integer $max_octets_length On coupe sans ménagement le texte si la taille en octets dépasse la valeur autorisée ads_max_octets_length de 10%, avant de le passer dans getCleanHTML qui va regénérer les balises de cloture manquantes - ads_max_caracters_length doit être inférieur à la taille du champs en base de données en laissant la place pour les balises de cloture (champ TEXT = 65 536 octets)
	 * @param integer $max_word_and_url_length
	 * @return
	 */
	public static function getCleanHTML($text, $max_width = null, $allow_form = false, $allow_object = false, $allow_class = false, $additional_config = null, $safe = true, $additional_elements = null, $max_caracters_length = 50000, $max_octets_length = 59000, $max_word_and_url_length = 100)
	{
		require_once($GLOBALS['dirroot'] . "/lib/fonctions/htmlawed.php");
		if (empty($text)) {
			return false;
		}
		$text = trim(str_replace(array('’', ' lang="EN-GB"', ' lang=EN-GB', ' mso-ansi-language: EN-GB', '<span>', '<SPAN>', '<font>', '<FONT>', '<strong></strong>', '<b></b>', '<STRONG></STRONG>', '<B></B>',
					"</td><br /><td", "<br />\n<td", "<br />\r\n<td", '<br /><td', '</td><br />', "<br />\n<tr", "<br />\r\n<tr", '<br /><tr', '</tr><br />',
					"</TD><br /><TD", "<br />\n<TD", "<br />\r\n<TD", '<br /><TD', "<br />\n<TR", "<br />\r\n<TR", '<br /><TR', '<TR><br />', '<tr><br />',
					'face=\'"arial,\'', 'sans-serif?', "<br /><LI", '<br /><TBODY>', '<br /><tbody>', '<TBODY><br />', '<tbody><br />', '<br /><COL', '<br /><col' , '<HR>', ' style=""', '<font>', '<span>', '<em><em>', '<b><b>', '<u><u>', '<i><i>',
					'      ', '     ', '    ', '   ', '  ', ' class=MsoNormal', ' class="MsoNormal"', ' style="mso-bidi-font-weight: normal"', ' style=""', ' align=""',
					'...', '-----', '_____', ':', '  ', '\\'),
				array("'", '', '', '', '', '', '', '', '', '', '', '',
					"</td><td", "\n<td", "\n<td", '<td', '</td>', "\n<tr", "\n<tr", '<tr', '</tr>',
					"</TD><TD", "\n<TD", "\n<TD", '<TD', "\n<TR", "\n<TR", '<TR', '<TR>', '<tr>',
					'face=\'arial\'', 'sans-serif', "<LI", '<TBODY>', '<tbody>', '<TBODY>', '<tbody>', '<COL', '<col' , '<hr />', '', '', '', '<em>', '<b>', '<u>', '<i>',
					' ', ' ', ' ', ' ', ' ', '', '', '', '', '',
					'... ', '----- ', '_____ ', ': ', ' ', ''),
				$text));
		// On raccourcit tout ce qui dépasse 100 caractères de long sans espace : ce n'est pas normal car plus haut, on a ajouté des espaces derrières les ; et autres ...
		$text = StringMb::str_shorten_words($text, $max_word_and_url_length, ' ');
		$text = str_replace(array(': //', ': 808'), array('://', ':808'), $text);
		if(!empty($max_caracters_length) && StringMb::strlen(vb($text)) > $max_caracters_length * 1.1) {
			// On coupe le texte si il dépasse la valeur autorisée de 10%, avant de le passer dans getCleanHTML qui va regénérer les balises de cloture manquantes
			$text = StringMb::substr($text, 0, $max_caracters_length);
		}
		if(!empty($max_octets_length) && StringMb::strlen(vb($text)) > $max_octets_length * 1.1) {
			// On coupe le texte si il dépasse la valeur autorisée, avant de le passer dans getCleanHTML qui va regénérer les balises de cloture manquantes
			// Coupure en octets, pas en caractères => substr et non pas StringMb::substr
			$text = substr($text, 0, $max_octets_length);
		}
		// $html_config['tidy']=1;
		// ATTENTION : clean_ms_char corrompt le UTF8, donc il ne faut pas l'appliquer (si c'était compatible on aurait mis la valeur 2)
		$html_config['clean_ms_char'] = 0;
		$html_config['schemes'] = 'href: ftp, http, https, mailto; classid:clsid; *:http, https, data';
		// $html_config['keep_bad']=1;
		if (!empty($safe)) {
			$html_config['safe'] = 1;
		}
		$html_config['comment'] = 1;
		// $html_config['show_setting']='settings';
		$html_config['elements'] = '*' . ($allow_object?'+object':'') . '' . ($allow_form?'':'-form') . '+embed-rb-rbc-rp-rt-rtc-ruby' . $additional_elements;
		$html_config['make_tag_strict'] = 0;
		$html_config['no_deprecated_attr'] = 0;
		if (empty($allow_class)) {
			if (empty($html_config['deny_attribute'])) {
				$html_config['deny_attribute'] = 'class';
			} else {
				$html_config['deny_attribute'] .= ',class';
			}
		}
		if (!empty($additional_config)) {
			$html_config += $additional_config;
		}
		// $html_config['balance']=0;
		/*	$C['and_mark'] = empty($C['and_mark']) ? 0 : 1;
		$C['anti_link_spam'] = (isset($C['anti_link_spam']) && is_array($C['anti_link_spam']) && count($C['anti_link_spam']) == 2 && (empty($C['anti_link_spam'][0]) or hl_regex($C['anti_link_spam'][0])) && (empty($C['anti_link_spam'][1]) or hl_regex($C['anti_link_spam'][1]))) ? $C['anti_link_spam'] : 0;
		$C['anti_mail_spam'] = isset($C['anti_mail_spam']) ? $C['anti_mail_spam'] : 0;
		$C['balance'] = isset($C['balance']) ? (bool)$C['balance'] : 1;
		$C['cdata'] = isset($C['cdata']) ? $C['cdata'] : (empty($C['safe']) ? 3 : 0);
		$C['clean_ms_char'] = empty($C['clean_ms_char']) ? 0 : $C['clean_ms_char'];
		$C['comment'] = isset($C['comment']) ? $C['comment'] : (empty($C['safe']) ? 3 : 0);
		$C['css_expression'] = empty($C['css_expression']) ? 0 : 1;
		$C['hexdec_entity'] = isset($C['hexdec_entity']) ? $C['hexdec_entity'] : 1;
		$C['hook'] = (!empty($C['hook']) && function_exists($C['hook'])) ? $C['hook'] : 0;
		$C['hook_tag'] = (!empty($C['hook_tag']) && function_exists($C['hook_tag'])) ? $C['hook_tag'] : 0;
		$C['keep_bad'] = isset($C['keep_bad']) ? $C['keep_bad'] : 6;
		$C['lc_std_val'] = isset($C['lc_std_val']) ? (bool)$C['lc_std_val'] : 1;
		$C['make_tag_strict'] = isset($C['make_tag_strict']) ? $C['make_tag_strict'] : 1;
		$C['named_entity'] = isset($C['named_entity']) ? (bool)$C['named_entity'] : 1;
		$C['no_deprecated_attr'] = isset($C['no_deprecated_attr']) ? $C['no_deprecated_attr'] : 1;
		$C['parent'] = isset($C['parent'][0]) ? StringMb::strtolower($C['parent']) : 'body';
		$C['show_setting'] = !empty($C['show_setting']) ? $C['show_setting'] : 0;
		$C['tidy'] = empty($C['tidy']) ? 0 : $C['tidy'];
		$C['unique_ids'] = isset($C['unique_ids']) ? $C['unique_ids'] : 1;
		$C['xml:lang'] = isset($C['xml:lang']) ? $C['xml:lang'] : 0;
	*/

		$text_clean = htmLawed($text, $html_config);

		if (!empty($max_width)) {
			// On remplace les largeurs trop importantes par de plus faibles
			foreach(array('width="' => array('"'), 'width = "' => array('"'), "width='" => array("'"), "width = '" => array("'"), 'width:' => array(';', '"', "'"), 'width :' => array(';', '"', "'"), 'position:' => array(';', '"', "'"), 'position :' => array(';', '"', "'"), 'font-size :' => array(';', '"', "'"), 'font-size:' => array(';', '"', "'")) as $begin_item => $end_item_array) {
				$text_end = StringMb::strlen($text_clean);
				$new_text_clean = '';
				$pointer = 0;
				while (($begin_pointer = StringMb::strpos($text_clean, $begin_item, $pointer)) !== false) {
					$end_pointer = false;
					foreach($end_item_array as $end_item) {
						$this_end_pointer = StringMb::strpos($text_clean, $end_item, $begin_pointer + StringMb::strlen($begin_item));
						if (empty($end_pointer) || ($this_end_pointer < $end_pointer && $this_end_pointer > $begin_pointer)) {
							$end_pointer = $this_end_pointer;
						}
					}
					if ($end_pointer === false || $end_pointer < $begin_pointer) {
						// On n'a pas trouvé la fin de l'expression : on abandonne ce remplacement et on passe au suivant
						break;
					}
					$item_value = StringMb::substr($text_clean, $begin_pointer + StringMb::strlen($begin_item), $end_pointer - ($begin_pointer + StringMb::strlen($begin_item)));
					$item_value = str_replace(array('px', 'pt'), array(''), trim($item_value));
					if (strpos($item_value, 'em') !== false) {
						$item_value = intval(16 * str_replace(array('em'), array(''), $item_value));
					}
					if (is_numeric($item_value) && $item_value > 24 && StringMb::substr($begin_item, 0, 4) == 'font') {
						$item_value = 24;
					} elseif (is_numeric($item_value) && $item_value > $max_width) {
						$item_value = $max_width;
					} elseif (is_numeric($item_value) && $item_value < 0) {
						$item_value = 0;
					}
					if ($item_value == 'absolute') {
						// On empêche le positionnement absolu
						$item_value = 'relative';
					}
					$new_text_clean .= StringMb::substr($text_clean, $pointer, $begin_pointer - $pointer) . $begin_item . $item_value;
					if (is_numeric($item_value) && substr($begin_item, -1) == ':') {
						$new_text_clean .= 'px';
					}
					$pointer = $end_pointer;
				}
				$text_clean = $new_text_clean . StringMb::substr($text_clean, $pointer, $text_end - $pointer);
			}
		}
		$text_clean = str_replace(array(' alt="alt"', 'td align="middle"', 'Verdana,;', '</td><', '</tr><', '<br /><', "\n\n\n\n", "\n\n\n", "\n\n", "\r\n\r\n\r\n", "\r\n\r\n", "\r\n", 'font-size: xx-large', 'font size="9"', 'font size="8"', 'font size="7"', ' style=""', ' align=""'),
			array(' alt=""', 'td align="center"', 'Verdana;', "</td>\n<", "</tr>\n<", "<br />\n<", "\n", "\n", "\n", "\n", "\n", "\n", 'font-size: x-large', 'font size="6"', 'font size="6"', 'font size="6"', '', ''), $text_clean);
		return $text_clean;
	}

	/**
	 * Ouvre un fichier
	 * 
	 * C'est une fonction de compatibilité avec du contenu qui n'est pas en UTF8 sans BOM comme il devrait être
	 * De manière générale, tout fichier manipulé par PEEL est censé avoir un nom encodé en UTF8, pour gérer toute langue internationale, ce que ne peut pas faire ISO. 
	 * Néanmoins en cas d'import de fichiers par FTP manuel, ou d'URL donnée avec lien vers un autre site, les fichiers risquent d'être mis en ISO.
	 * Il est préférable de mettre le mode de compatibilité $try_filename_in_iso_8859_if_file_not_found = true
	 *
	 * @param string $filename
	 * @param string $mode
	 * @param boolean $force_filename_in_iso_8859
	 * @param boolean $try_filename_in_iso_8859_if_file_not_found
	 * @return
	 */
	public static function fopen_utf8($filename, $mode, $force_filename_in_iso_8859 = false, $try_filename_in_iso_8859_if_file_not_found = true)
	{
		if($force_filename_in_iso_8859 && StringMb::detect_utf8_characters($filename)){
			// On ne veut pas que le nom soit en UTF8
			$filename = StringMb::convert_encoding($filename, 'iso-8859-1', GENERAL_ENCODING);
		}
		$file = @fopen($filename, $mode);
		if(empty($file) && $try_filename_in_iso_8859_if_file_not_found){
			// Si le fichier a été enregistré non pas par l'application PEEL Shopping mais par un tiers, qui n'a pas mis le nom en UTF8
			$filename = StringMb::convert_encoding($filename, 'iso-8859-1', GENERAL_ENCODING);
			$file = @fopen($filename, $mode);
		}
		if (!empty($file) && (substr($mode, 0, 1) == 'r' || strpos($mode, '+') !== false)) {
			// Rewind ne va pas marcher si le fichier est en HTTP
			$bom = fread($file, 3);
			// On retire le BOM en début de fichier UTF8 si on en trouve un
			// Le BOM est détecté avec pack("CCC", 0xef, 0xbb, 0xbf) ou "\xEF\xBB\xBF" ou b'\xEF\xBB\xBF' depuis PHP 5.2.1
			if ($bom != "\xEF\xBB\xBF") {
				// On n'a pas trouvé de BOM, donc on revient au début du fichier - sinon on ne fait rien, donc on a passé le BOM
				if (strpos($filename, 'http://') !== 0 && strpos($filename, 'https://') !== 0) {
					rewind($file);
				} else {
					// Rewind ne va pas marcher si le fichier est en HTTP
					fclose($file);
					$file = @fopen($filename, $mode);
				}
			}
		}
		return $file;
	}

	/**
	 * Renvoie le contenu d'un fichier
	 * 
	 * De manière générale, tout fichier manipulé par PEEL est censé avoir un nom encodé en UTF8, pour gérer toute langue internationale, ce que ne peut pas faire ISO. 
	 * Néanmoins en cas d'import de fichiers par FTP manuel, ou d'URL donnée avec lien vers un autre site, les fichiers risquent d'être mis en ISO.
	 * Il est préférable de mettre le mode de compatibilité $try_filename_in_iso_8859_if_file_not_found = true
	 *
	 * @param string $filename
	 * @param boolean $force_filename_in_iso_8859
	 * @param boolean $try_filename_in_iso_8859_if_file_not_found
	 * @return
	 */
	public static function file_get_contents_utf8($filename, $force_filename_in_iso_8859 = false, $try_filename_in_iso_8859_if_file_not_found = true)
	{
		if($force_filename_in_iso_8859 && StringMb::detect_utf8_characters($filename)){
			// On ne veut pas que le nom soit en UTF8
			$filename = StringMb::convert_encoding($filename, 'iso-8859-1', GENERAL_ENCODING);
		}
		$file = @file_get_contents($filename);
		if(empty($file) && $try_filename_in_iso_8859_if_file_not_found){
			// Si le fichier a été enregistré non pas par l'application PEEL Shopping mais par un tiers, qui n'a pas mis le nom en UTF8
			$filename = StringMb::convert_encoding($filename, 'iso-8859-1', GENERAL_ENCODING);
			$file = @file_get_contents($filename);
		}
		$bom = substr($file, 0, 3);
		// On retire le BOM en début de fichier UTF8 si on en trouve un
		// Le BOM est détecté avec pack("CCC", 0xef, 0xbb, 0xbf) ou "\xEF\xBB\xBF" ou b'\xEF\xBB\xBF' depuis PHP 5.2.1
		if ($bom == "\xEF\xBB\xBF") {
			// On a trouvé un BOM, on le retire donc
			$file = substr($file, 3);
		}
		return $file;
	}
		
	/**
	 * Tests for end-of-file on a file pointer
	 * In contrary of the default feof function, it returns true if $handle === false, and if timeout in feof
	 * It wan be safely used with while (!StringMb::feof($file)) { ... }
	 *
	 * @param mixed $handle
	 * @return
	 */
	public static function feof($handle) {
		static $timeout;
		if($handle === false) {
			return true;
		}		
		// gestion des timeouts : feof renvoie false si il y a eu un timeout, et on change cela en true pour pouvoir faire des tests simples ensuite avec des while(!StringMb::feof($file)) { ... }
		if(!isset($timeout)) {
			$timeout = @ini_get('default_socket_timeout');
		}
		if(empty($timeout)) {
			$timeout = 10;
		}
		$start = microtime(true);
		$result = feof($handle);
		if(!$result && (microtime(true) - $start >= $timeout)) {
			return true;
		}
		return $result;
	}

	/**
	 * Returns string compatible with Apache without the AllowEncodedSlashes directive ON => avoids systematic 404 error when %2F in URL (when it is present outside of GET)
	 *
	 * @param string $string The input string.
	 * @param boolean $avoid_slash
	 * @return
	 */
	public static function rawurlencode($string, $avoid_slash = true)
	{
		if ($avoid_slash) {
			return rawurlencode(str_replace('/', '-', $string));
		} else {
			return rawurlencode($string);
		}
	}

	/**
	 * Returns rawurldecode
	 *
	 * @param string $string The input string.
	 * @param boolean $avoid_slash
	 * @return
	 */
	public static function rawurldecode($string, $avoid_slash = false)
	{
		if ($avoid_slash) {
			return str_replace('/', '-', rawurldecode($string));
		} else {
			return rawurldecode($string);
		}
	}
}


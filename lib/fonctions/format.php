<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: format.php 48453 2016-01-11 09:52:17Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Nettoyage des données PGC
 *
 * @param mixed $value
 * @param mixed $key
 * @return
 */
function cleanDataDeep(&$value, $key = null)
{
	$bad_strings = array("Content-Type:", "text/plain;", "MIME-Version:", "Content-Transfer-Encoding:", "Content-Transfer-Encoding: 7Bit", "bcc:");
	if (is_array($value)) {
		if (function_exists('array_walk_recursive')) {
			array_walk_recursive($value, 'cleanDataDeep');
		}else{
			$value = array_map('cleanDataDeep', $value);
		}
	} else {
		if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
			// Si magic_quotes est activé dans la configuration de l'hébergement, alors on annule ses effets ici
			$value = stripslashes($value);
		}
		if (!defined('DISABLE_INPUT_ENCODING_CONVERT') && (!defined('IN_PEEL_ADMIN') || !a_priv('admin*', false))) {
			foreach($bad_strings as $bad_string) {
				if (String::strpos($value, $bad_string) !== false) {
					// On interdit les bad_strings qui pourraient servir à des injections diverses
					$value = '';
				}
			}
			if($key!==null){
				// On ne passe ici que si $key est défini, donc si on appelle cette fonction avec array_walk_recursive et non pas array_map
				$key_parts=explode('_', str_replace('form_', '', $key));
				if (!empty($GLOBALS['site_parameters']['post_variables_with_html_allowed_if_not_admin']) && !in_array($key_parts[0], $GLOBALS['site_parameters']['post_variables_with_html_allowed_if_not_admin'])) {
					// Un utilisateur sans droit administrateur ne peut jamais donner de HTML => protège de toute sorte de XSS
					$value = String::strip_tags($value);
				}
			}
		}
		// On convertit les données en UTF8 si on n'a pas vu de caractère spécifique UTF8
		if(!defined('DISABLE_INPUT_ENCODING_CONVERT') && !String::detect_utf8_characters($value)){
			// A défaut, on considère que l'encodage est en ISO ou CP1252. Si ce n'est pas le cas, ça ne marchera pas.
			// Mais de toutes façons, il n'y a pas de raison de recevoir autre chose que de l'UTF8
			// Donc cette conversion est sensée servir très occasionnellement : par exemple lors de la MAJ d'un ancien site, dont les URL étaient encodées en ISO8859
			// La plupart du temps, ici on a à faire à de l'ASCII classique sans accent, donc aucun changement concret, mais on fait le remplacement au cas où
			$value = String::utf8_encode($value);
		}
		if(strlen($value)>20 && String::strpos($value, 'myEventWatcherDiv')!==false) {
			// On fait un test sur strlen (sans String::, c'est plus rapide) d'abord pour éviter de faire le test strpos lorsque ce n'est pas utile pour accélérer
			// On nettoie ce qui est laissé par CKEditor
			$value = str_replace(array('<div id="myEventWatcherDiv" style="display: none;">&nbsp;</div>', '<div style="display:none;" id="myEventWatcherDiv">&nbsp;</div>', '<div style="display: none;" id="myEventWatcherDiv">&nbsp;</div>'), '', $value);
		}
	}
	return $value;
}

/**
 * Affiche le mot "checked" si la variable est vraie sinon rien
 *
 * @param mixed $variable_to_test
 * @param string $true_value
 * @param string $false_value
 * @return
 */
function frmvalide($variable_to_test, $true_value = 'checked="checked"', $false_value = "")
{
	if ($variable_to_test) {
		return $true_value;
	} else {
		return $false_value;
	}
}

/**
 * Variable blanche
 * if $var n'est pas défini, retourne $default, sinon retourne $var
 *
 * @param mixed $var
 * @param string $default
 * @return
 */
function vb(&$var, $default = null)
{
	return isset($var) ? $var : $default;
}

/**
 * Variable nulle
 * if $var n'est pas défini, retourne $default, sinon retourne $var
 *
 * @param mixed $var
 * @param integer $default
 * @return
 */
function vn(&$var, $default = 0)
{
	return isset($var) ? $var : $default;
}

/**
 * getPregConditionCompatAccents()
 *
 * @param mixed $string
 * @param string $delimiter
 * @return
 */
function getPregConditionCompatAccents($string, $delimiter = '/')
{
	$string = preg_quote(String::convert_accents($string), $delimiter);
	return str_replace(array("a", "c", "e", "i", "o", "u", "n", "y"),
		array("[aáåâäàã]", "[cç]", "[eêéèë]", "[iíîïì]", "[oóôöòõ]", "[uûüùú]", "[nñ]", "[yÿý]"), $string);
}

/**
 * url2Link()
 *
 * @param mixed $string
 * @return
 */
function url2Link($string)
{
	if(String::strpos($string, '<a ') === false && String::strpos($string, '<img ') === false) {
		return preg_replace('/(http|mailto|news|ftp|https)\:\/\/(([-éa-z0-9\/\.\?_=#@:;,!~&%])*)/i', "<a href=\"$1://$2\" target=\"_blank\">$1://$2</a>", $string);
	} else {
		return $string;
	}
}

/**
 * linkFormat()
 *
 * @param mixed $text
 * @return
 */
function linkFormat($text)
{
	// A chaque imbrication correcte, on recommence
	while (String::strpos($text, '[link="') !== false && String::strpos($text, '[/link]', String::strpos($text, '[link="')) !== false) {
		// echo 'Iteration <br />';
		// Traitement pour chaque quote
		// Il y a au moins un bon quote à remplacer
		$quote_begin = 0;
		while (String::strpos($text, '[link="', $quote_begin + 1) !== false) {
			// on se positionne sur la dernière imbrication
			$quote_begin = String::strpos($text, '[link="', $quote_begin + 1);
		}
		if (String::strpos($text, '[/link]', $quote_begin) === false) {
			$text .= '[/link]';
		}
		$old_text = $text;
		$quote_end = String::strpos($text, '[/link]', $quote_begin) + String::strlen('[/link]');
		$quote_text = String::substr($old_text, $quote_begin + strlen('[link="'), $quote_end - $quote_begin - String::strlen('[link="') - String::strlen('[/link]'));
		// $quote_text = str_replace('&quot;]', ' a écrit :</b><br />', $quote_text);
		$quote_text = '<a href="' . $quote_text;
		$quote_text = str_replace("]", ">", $quote_text);
		$quote_text .= '</a>';
		// echo $quote_text.'<br />';
		$text = '';
		if ($quote_begin > 0) {
			$text .= String::substr($old_text, 0, $quote_begin);
		}
		$text .= $quote_text;
		if ($quote_end < String::strlen($old_text)) {
			$text .= String::substr($old_text, $quote_end);
		}
		unset($old_text);
	}
	// On clean si une fermeture de quote en trop (on ne sait jamais)
	$text = str_replace("[/link]", "", $text);
	return $text;
}

/**
 * Transforme tout nombre formaté en un float au format PHP
 * Exemples : 12 004,34 ou 12,324.50
 *
 * @param mixed $string
 * @param float $from_currency_rate
 * @return
 */
function get_float_from_user_input($string, $from_currency_rate = 1)
{
	if(is_array($string)) {
		return $string;
	}
	foreach(array('.', ',') as $separator) {
		$array_temp = explode($separator, $string);
		if (count($array_temp) > 2) {
			// Plus de deux occurences du séparateur => ne peut être un séparateur de décimales
			$string = str_replace($separator, '', $string);
		}
		unset($array_temp);
		if (strpos($string, $separator) !== false && strpos($string, $separator) == strlen($string) - 3) {
			// Un séparateur de millier seul ou un nombre avec 3 chiffres après la virgule
			// => on imagine que c'est un nombre avec 3 chiffres après la virgule  => on ne fait rien
		}
	}
	if (strpos($string, ',') !== false && strpos($string, '.') !== false) {
		if (strpos($string, ',') < strpos($string, '.')) {
			// La virgule est spérateur de milliers
			$string = str_replace(',', '', $string);
		} else {
			// Le point est spérateur de milliers
			$string = str_replace('.', '', $string);
		}
	}
	// Maintenant, il ne doit plus rester de séparateur de millier
	return floatval(preg_replace("([^-0-9.])", "", str_replace(',', '.', $string))) / $from_currency_rate;
}

/**
 * Formatte une chaine de caractère pour insertion dans du javascript
 *
 * @param string $string
 * @param boolean $addslashes
 * @param boolean $allow_escape_single_quote
 * @param boolean $allow_escape_double_quote
 * @param boolean $skip_endline
 * @param boolean $inside_html
 * @return
 */
function filtre_javascript($string, $addslashes = true, $allow_escape_single_quote = true, $allow_escape_double_quote = true, $skip_endline = true, $inside_html = true)
{
	if ($addslashes) {
		$string = addslashes($string);
		if (!$allow_escape_single_quote) {
			$string = str_replace("\'", "'", $string);
		}
		if (!$allow_escape_double_quote) {
			$string = str_replace('\"', '"', $string);
		} elseif($inside_html) {
			$string = str_replace('\"', '&quot;', $string);
		}
	}
	if($skip_endline) {
		$string = str_replace(array("\t", "\r\n", "\n"), array(" ", " ", " "), $string);
	} else {
		// Exécution des sauts de lignes dans alert() par exemple
		$string = str_replace(array("\t", "\r\n", "\n"), array(" ", '\n', '\n'), $string);
	}
	return $string;
}

/**
 * filtre_pdf()
 *
 * @param string $string
 * @return
 */
function filtre_pdf($string)
{
	return str_replace('<br />', "\r\n", String::html_entity_decode_if_needed(String::htmlspecialchars_decode($string, ENT_QUOTES)));
}

/**
 * Formatte une chaine de caractère pour insertion dans du CSV
 *
 * @param string $string
 * @param string $separator
 * @return
 */
function filtre_csv($string, $separator = "\t")
{
	$string = str_replace(array($separator, "\r\n", "\n", "\r"), array(" ", " ", " ", " "), String::html_entity_decode(String::htmlspecialchars_decode($string, ENT_QUOTES)));
	return $string;
}

/**
 * Formatte un nombre pour insertion dans du CSV
 *
 * @param string $number_string
 * @param string $separator
 * @return
 */
function fxsl($number_string, $separator = ',')
{
	$number_string = number_format(floatval(str_replace(array(",", " "), array(".", ""), $number_string)), 2, $separator, '');
	return $number_string;
}

/**
 * Filtre une chaine de caractères pour insertion dans une URL
 * On essaie de couper proprement, pour une longueur entre 30 et 60 caractères
 *
 * @param mixed $string
 * @param mixed $convert_string_to_lower
 * @return
 */
function rewriting_urlencode($string, $convert_string_to_lower = true)
{
	if ($convert_string_to_lower == true) {
		$string = String::strtolower($string);
	}
	$string = preg_replace('/[^a-zA-Z0-9_]/', "-", utf8_decode(String::strip_tags(String::convert_accents($string))));
	$string = preg_replace('/[-]{2,}/', "-", $string);
	$url_part = String::rawurlencode(String::str_shorten($string, 60, '', '', 30));
	return $url_part;
}

/**
 * get_currency_international_numerical_code()
 *
 * @param mixed $currency_code
 * @return
 */
function get_currency_international_numerical_code($currency_code)
{
	$currencies = array('CHF' => '756',
		'EUR' => '978',
		'USD' => '840',
		'CAD' => '124',
		'JPY' => '392',
		'GBP' => '826',
		'AUD' => '036',
		'NOK' => '578',
		'SEK' => '752',
		'DKK' => '208'
		);
	if (!empty($currencies[$currency_code])) {
		return $currencies[$currency_code];
	} else {
		return null;
	}
}

/**
 * get_country_iso_2_letter_code()
 *
 * @param mixed $country_id_or_name
 * @param boolean $guess_if_not_found
 * @return
 */
function get_country_iso_2_letter_code($country_id_or_name, $guess_if_not_found = true)
{
	$sql = 'SELECT iso
		FROM peel_pays
		WHERE (id="' . nohtml_real_escape_string($country_id_or_name) . '" OR pays_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($country_id_or_name) . '") AND ' . get_filter_site_cond('pays') . '
		LIMIT 1';
	$query = query($sql);
	if ($obj = fetch_object($query)) {
		$result = $obj->iso;
	}
	if (!empty($result)) {
		return $result;
	} elseif ($guess_if_not_found && !is_numeric($country_id_or_name)) {
		// On renvoie les 2 premières lettres plutôt que rien du tout, on a des chances que ce soit bon
		return String::substr(String::strtoupper($country_id_or_name), 0, 2);
	} else {
		return false;
	}
}

/**
 * get_country_iso_3_letter_code()
 *
 * @param mixed $country_id_or_name
 * @param boolean $guess_if_not_found
 * @return
 */
function get_country_iso_3_letter_code($country_id_or_name, $guess_if_not_found = true)
{
	$sql = 'SELECT iso3
		FROM peel_pays
		WHERE (id="' . nohtml_real_escape_string($country_id_or_name) . '" OR pays_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($country_id_or_name) . '") AND ' . get_filter_site_cond('pays') . '
		LIMIT 1';
	$query = query($sql);
	if ($obj = fetch_object($query)) {
		$result = $obj->iso3;
	}
	if (!empty($result)) {
		return $result;
	} elseif ($guess_if_not_found && !is_numeric($country_id_or_name)) {
		// On renvoie les 3 premières lettres plutôt que rien du tout, on a des chances que ce soit bon
		return String::substr(String::strtoupper($country_id_or_name), 0, 3);
	} else {
		return false;
	}
}

/**
 * get_country_iso_num_code()
 *
 * @param mixed $country_id_or_name
 * @return
 */
function get_country_iso_num_code($country_id_or_name)
{
	$sql = 'SELECT iso_num
		FROM peel_pays
		WHERE (id="' . nohtml_real_escape_string($country_id_or_name) . '" OR pays_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($country_id_or_name) . '") AND ' . get_filter_site_cond('pays') . '
		LIMIT 1';
	$query = query($sql);
	if ($obj = fetch_object($query)) {
		$result = $obj->iso_num;
	}
	if (!empty($result)) {
		return $result;
	} else {
		return false;
	}
}

/**
 * fdate()
 *
 * @param mixed $date_nok
 * @return
 */
function fdate(&$date_nok)
{
	$date_ok = get_formatted_date($date_nok, 'short', 'long');
	return $date_ok;
}

/**
 * Afficher une date formatée, en évitant les problèmes liés aux noms de mois sur les serveurs qui ne sont pas installés pour gérer le multilingue :
 * Si on appelle get_formatted_date() sur une chaine déjà formattée par get_formatted_date(), la date est bien préservée
 * On peut donc appliquer cette fonction sur une date provenant d'un formulaire ou de la base de données
 *
 * @param mixed $datetime_or_timestamp Si nulle : date du jour. Si === '' ou === 0, alors c'est pas de de date
 * @param string $mode can be 'short', 'long', 'standard', 'nice', 'veryshort', 'timestamp1000'
 * @param mixed $hour_minute can be a boolean => displays the hour:minute or not, or "long" or "short" to define the format
 * @return
 */
function get_formatted_date($datetime_or_timestamp = null, $mode = 'short', $hour_minute = false)
{
	// Décommentez la fonction suivante et commentez l'autre, si votre serveur n'arrive pas à afficher les dates correctement (traductions).
	// $date = strftime(str_replace('%A', $GLOBALS['day_of_week'][(0 + strftime('%w', strtotime($datetime_or_timestamp)))], str_replace('%B', $GLOBALS['months_names'][(0 + strftime('%m', strtotime($datetime_or_timestamp)))], $GLOBALS['date_format_long'])), strtotime($datetime_or_timestamp));
	if (!empty($GLOBALS['date_format_'.$mode])) {
		$format = $GLOBALS['date_format_'.$mode];
	} elseif($mode != 'timestamp' && $mode != 'timestamp1000') {
		$format = $GLOBALS['date_format_long'];
	} else {
		$format = null;
	}
	if($format !== null) {
		if ($hour_minute===true) {
			$format .= ' ' . $GLOBALS['time_format_long'];
		}elseif (!empty($hour_minute)) {
			$format .= ' ' . vb($GLOBALS['time_format_'.$hour_minute]);
		}
	}
	if (empty($datetime_or_timestamp) || $datetime_or_timestamp==='0') {
		$date = '';
	} elseif (!is_numeric($datetime_or_timestamp)) {
		if (substr($datetime_or_timestamp, 0, 10) == '0000-00-00') {
			$date = '';
		} elseif (is_numeric(substr($datetime_or_timestamp, 0, 4))) {
			// Format MySQL => on convertit en timestamp
			$date = strtotime($datetime_or_timestamp);
		} else {
			// Chaine déjà formattée : on la reformate en tenant compte du format utilisé à l'origine pour formatter.
			$date = strtotime(get_mysql_date_from_user_input($datetime_or_timestamp));
		}
		if($date === -1) {
			// strtotime retourne un timestamp en cas de succès, false sinon. 
			// Mais avant PHP 5.1.0, cette fonction retournait -1 en cas d'échec. => on passe donc ici à false 
			$date = false;
		}
	} else {
		$date = $datetime_or_timestamp;
	}
	if($date!=='' && $date!==false && $date!==null && is_numeric($date) && $format !== null) {
		// Format numérique timestamp => on convertit en date
		$date = strftime($format, $date);
	} elseif($mode == 'timestamp1000' && is_numeric($date)) {
		// ms instead of seconds - on passe en chaine de caractères pour compatibilité avec entiers 32 bits si serveur n'est pas en 64 bits.
		$date = '' . $date . '000';
	}
	return $date;
}

/**
 * Transforme une date formattée par get_formatted_date() en date MySQL
 * Si la date est vide, ou partielle, ce qui manque est remplacé par des 0
 *
 * @param string $string
 * @param boolean $use_current_hour_min_sec_if_missing Force l'ajout de l'heure H:i:s correspondant à l'instant t, si cette heure n'est pas spécifiée ; permet de générer facilement une date t + X jours
 * @return
 */
function get_mysql_date_from_user_input($string, $use_current_hour_min_sec_if_missing = false)
{
	if (is_numeric(substr($string, 0, 4)) && substr($string, 4, 1) == '-' && !is_numeric(substr($GLOBALS['date_format_short'], 0, 4))) {
		// Date au format MySQL
		$supposed_string_format = 'Y-m-d H:i:s';
	} else {
		// Date formattée : 
		// dans PEEL, toute date formattée est censée venir de get_formatted_date
		// Pour éviter tout problème de cohérence avec get_formatted_date au niveau de l'ordre jour / mois / année, on utilise $GLOBALS['date_format_short'] et non pas $GLOBALS['date_basic_format_short'] dans la ligne ci-après
		// On supprime les % pour avoir les bonnes valeurs utilisables ensuite avec date() pour la génération finale
		// Pour l'heure, on utilise le format standard compatible dans date(), car le format utilisateur va rester de toutes façons dans le même ordre
		$supposed_string_format = $GLOBALS['date_format_short'].' '.$GLOBALS['time_basic_format_long'];
	}
	$user_date_format_array = explode('-', str_replace(array('%', ' ', '/', '.', ':', '_', 'h', ','), array('', '-', '-', '-', '-', '-', '-', '-'), $supposed_string_format));
	$user_date_array = explode('-', str_replace(array(' ', '/', '.', ':', '_', 'h', ','), array('-', '-', '-', '-', '-', '-', '-'), $string));
	foreach($user_date_format_array as $this_key => $this_letter) {
		if(isset($user_date_array[$this_key])) {
			$this_date_array[$this_letter] = $user_date_array[$this_key];
		}
	}
	if($use_current_hour_min_sec_if_missing && count($user_date_array)<=3){
		$hour = ' '.date('H:i:s');
	} elseif(count($user_date_array)>=5) {
		// On met l'heure seulement si on l'a : permet de ne pas donner 00:00:00 si par exemple on veut utiliser le résultat en recherche avec un LIKE "...%"
		$hour = ' '.str_pad(vb($this_date_array['H']), 2, 0, STR_PAD_LEFT).':'. str_pad(vb($this_date_array['i']), 2, 0, STR_PAD_LEFT).':'. str_pad(vb($this_date_array['s']), 2, 0, STR_PAD_LEFT);
	}
	return str_pad(vb($this_date_array['Y']), 4, 0, STR_PAD_LEFT).'-'. str_pad(vb($this_date_array['m']), 2, 0, STR_PAD_LEFT).'-'. str_pad(vb($this_date_array['d']), 2, 0, STR_PAD_LEFT) . vb($hour);
}

/**
 * Affiche une durée en jours / heures / minutes / secondes
 *
 * @param int $total_seconds Délai en secondes
 * @param boolean $show_seconds
 * @param string $display_mode
 * @return
 */
function get_formatted_duration($total_seconds, $show_seconds = false, $display_mode = 'day')
{
	$result = array();
	if (!is_numeric($total_seconds) || $total_seconds < 0) {
		return false;
	}
	$days = $total_seconds / (3600 * 24);
	$hours = $total_seconds / 3600 - floor($days) * 24;
	$minutes = $total_seconds / 60 - floor($days) * 60 * 24 - floor($hours) * 60;
	$seconds = $total_seconds - floor($days) * 3600 * 24 - floor($hours) * 3600 - floor($minutes) * 60;
	$weeks = $total_seconds / (3600 * 24 * 7);
	$months = $total_seconds / (3600 * 24 * 30);

	if ($display_mode == 'month') {
		if ($months >= 1) {
			$result[] = floor($months) . ' ' . str_replace('(s)', ($months>1?'s':''), $GLOBALS['STR_MONTHS']);
		} elseif ($weeks >= 1) {
			$result[] = floor($weeks) . ' ' . str_replace('(s)', ($weeks>1?'s':''), $GLOBALS['strWeeks']);
		} elseif ($days >= 1) {
			$result[] = floor($days) . ' ' . str_replace('(s)', ($days>1?'s':''), $GLOBALS['strDays']);
		}
	} else {
		if ($days >= 1) {
			$result[] = floor($days) . '' . str_replace('(s)', ($days>1?'s':''), $GLOBALS['strShortDays']);
		}
		if ($hours >= 1) {
			$result[] = floor($hours) . '' . str_replace('(s)', ($hours>1?'s':''), $GLOBALS['strShortHours']);
		}
		if ($minutes >= 1) {
			$result[] = floor($minutes) . '' . str_replace('(s)', ($minutes>1?'s':''), $GLOBALS['strShortMinutes']);
		}
		if ($seconds >= 1 && ($show_seconds || $total_seconds<60)) {
			$result[] = floor($seconds) . '' . str_replace('(s)', ($seconds>1?'s':''), $GLOBALS['strShortSecs']);
		}
	}
	if(is_numeric($display_mode)) {
		$temp = array_chunk($result, $display_mode);
		$result = $temp[0];
	}
	return implode(' ', $result);
}

/**
 * Remplace les tags d'un texte au format [TAG] par les valeurs correspondantes.
 * Les tags doivent correspondre à des clés du tableau $custom_template_tags ou à des tags génériques du site.
 * Les tags génériques sont :
 *    - SITE / SITE_NAME : nom du site
 *    - WWWROOT : URL générale du site
 *    - PHP_SELF : script PHP exécuté
 *    - REPERTOIRE_IMAGES : Repertoire qui contient les images de la charte graphique dans le dossier modele
 *    - CATALOG_URL : URL de la page principal du catalogue, listant toutes les catégories
 *    - CURRENT_URL : URL appelée par l'utilisateur
 *    - REMOTE_ADDR : IP de l'utilisateur
 *    - DATETIME : date et heure actuelle
 *    - DATE : date actuelle
 *    - TIMESTAMP : Timestamp actuelle
 * On ne traite pas ici les tags qui nécessitent de connaître le numéro de facture (on les remplacera juste avant utilisation du texte)
 *
 * @param mixed $text
 * @param array $custom_template_tags
 * @param boolean $replace_only_custom_tags
 * @param string $format : null => does not touch the format of the tag, "text" or "html"
 * @param string $lang
 * @param boolean $avoid_load_urls A mettre à true pour éviter appels récursifs infinis
 * @return
 */
function template_tags_replace($text, $custom_template_tags = array(), $replace_only_custom_tags = false, $format = null, $lang = null, $avoid_load_urls = false)
{
	if(is_array($text)) {
		$temp = array();
		foreach(array_keys($text) as $this_key) {
			if(strpos($this_key, '[') !== false) {
				$this_new_key = template_tags_replace($this_key, $custom_template_tags, $replace_only_custom_tags, $format, $lang, $avoid_load_urls);
			} else {
				$this_new_key = $this_key;
			}
			// On construit un nouveau tableau au fur et à mesurepour garder l'ordre initial, même si des clés ont leur texte modifié
			$temp[$this_new_key] = template_tags_replace($text[$this_key], $custom_template_tags, $replace_only_custom_tags, $format, $lang, $avoid_load_urls);
		}
		return $temp;
	}
	if (empty($lang)) {
		$lang = $_SESSION['session_langue'];
	}
	if(strpos($text, '[') !== false && strpos($text, ']') !== false) {
		$template_tags = array();
		if(!$replace_only_custom_tags) {
			// On rajoute les tags génériques au site
			$template_tags['SITE'] = $GLOBALS['site'];
			$template_tags['SITE_NAME'] = $GLOBALS['site'];
			$template_tags['WWWROOT'] = get_lang_rewrited_wwwroot($lang);
			if(!$avoid_load_urls) {
				$template_tags['CATALOG_URL'] = get_product_category_url();
			}
			$template_tags['PHP_SELF'] = $_SERVER['PHP_SELF'];
			$template_tags['CURRENT_URL'] = get_current_url(false);
			$template_tags['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
			$template_tags['DATETIME'] = get_formatted_date(time(), 'long', true);
			$template_tags['DATE'] = get_formatted_date(time(), 'long', false);
			$template_tags['DATE_SHORT'] = get_formatted_date(time(), 'short', false);
			$template_tags['TIMESTAMP'] = time();
			// Gestion des tags [CODE_PROMO_SOURCE=XXXXXXXXXX]
			foreach(array('CODE_PROMO_SOURCE' => false, 'FUNCTION' => true, 'HTML' => true, 'GLOBALS' => true, 'BEST_SELLER_CARROUSEL' => true, 'CONTENT_CARROUSEL' => true) as $this_function_tag => $arg_mandatory) {
				$tag_begin = -1;
				while (String::strpos($text, '[' . $this_function_tag . '=', $tag_begin + 1) !== false && String::strpos($text, ']', String::strpos($text, '[' . $this_function_tag . '=', $tag_begin + 1)) !== false) {
					// Traitement pour chaque tag
					// Il y a au moins un bon quote à remplacer
					// on se positionne sur la dernière imbrication
					$tag_begin = String::strpos($text, '[' . $this_function_tag . '=', $tag_begin + 1);
					$this_tag = String::substr($text, $tag_begin+1, String::strpos($text, ']', $tag_begin+1)-$tag_begin-1);
					$tag_name_array = explode('=', $this_tag, 2);
					$this_arg = vb($tag_name_array[1]);
					if(!$arg_mandatory || !empty($this_arg)) {
						if($this_function_tag == 'CODE_PROMO_SOURCE') {
							// On va chercher les codes 1 par 1 en faisant SELECT * WHERE nb_valide>0 ORDER BY id ASC et mettre nb_valide=nb_valide-1
							$sql = 'SELECT id, nom
								FROM peel_codes_promos cp
								WHERE ' . get_filter_site_cond('codes_promos', 'cp') . ' AND nb_valide>0 AND (nombre_prevue=0 OR compteur_utilisation<nombre_prevue) AND source="'.real_escape_string($this_arg).'" AND cp.etat = "1" AND ("' . date('Y-m-d', time()) . '" BETWEEN cp.date_debut AND cp.date_fin)
								ORDER BY id ASC
								LIMIT 1';
							$query = query($sql);
							if ($obj = fetch_object($query)) {
								$template_tags[$this_tag] = $obj->nom;
								$sql = 'UPDATE peel_codes_promos
									SET nb_valide=nb_valide-1
									WHERE id="'.intval($obj->id).'" AND ' . get_filter_site_cond('codes_promos');
								$query = query($sql);
							}
						} elseif($this_function_tag == 'FUNCTION') {
							// SECURITE : Liste des fonctions autorisées ci-dessous, sinon la fonction appelée doit commencer par le préfixe 'get_tag_function_'
							$allowed_functions = array('');
							$this_arg_array=explode(',', $this_arg, 2);
							$this_arg = $this_arg_array[0];
							$this_params_array = get_array_from_string(vb($this_arg_array[1]));
							if(in_array($this_arg, $allowed_functions)) {
								$function_name = $this_arg;
							} else {
								$function_name = 'get_tag_function_' . $this_arg;
							}
							if(function_exists($function_name)) {
								$template_tags[$this_tag] = $function_name($this_params_array);
							} else {
								$template_tags[$this_tag] = '[' . $function_name . ' not found]'; 
							}
						} elseif($this_function_tag == 'GLOBALS') {
							// SECURITE : Liste des variables globales autorisées ci-dessous, sinon la variable appelée doit commencer par le préfixe 'tag_'
							$allowed_functions = array('');
							if(in_array($this_arg, $allowed_functions)) {
								$function_name = $this_arg;
							} else {
								$function_name = 'tag_' . $this_arg;
							}
							$template_tags[$this_tag] = vb($GLOBALS[$function_name]);
						} elseif($this_function_tag == 'RSS') {
							// Pour chaque tag RSS, on remplace par le contenu du flux
							$template_tags[$this_tag] = get_rss_feed_content($this_arg);
						} elseif($this_function_tag == 'HTML') {
							// Pour chaque tag HTML, on remplace par le contenu de la zone HTML correspondante
							$template_tags[$this_tag] = affiche_contenu_html($this_arg, true);
						} 
					}
				}
			}
			if(String::strpos($text, '[CONTACT_FORM]') !== false) {
				// Affichage du formulaire de contact, avec gestion des erreurs 
				$template_tags['CONTACT_FORM'] = handle_contact_form($_POST, true);
			} elseif(String::strpos($text, '[BEST_SELLER_CARROUSEL]') !== false) {
				$template_tags['BEST_SELLER_CARROUSEL'] = affiche_best_seller_produit_colonne(true);
			} elseif(String::strpos($text, '[CONTENT_CARROUSEL]') !== false) {
				$template_tags['CONTENT_CARROUSEL'] = Carrousel::display('content_carrousel', true);
			} elseif(String::strpos($text, '[CLIENT_REFERENCES]') !== false) {
				$template_tags['CLIENT_REFERENCES'] = affiche_reference_multipage(null, '', 'reference', 12, 'general', true, 0, 4, false);
			}
			if(String::strpos($text, '[CLOSE_MAIN_CONTAINER]') !== false) {
				$template_tags['CLOSE_MAIN_CONTAINER'] = '</div></div></div>';
				if(defined('IN_RUBRIQUE') || defined('IN_RUBRIQUE_ARTICLE')) {
					$template_tags['CLOSE_MAIN_CONTAINER'] .= '</div></div>';
				} elseif(defined('IN_HOME')) {
					$template_tags['CLOSE_MAIN_CONTAINER'] .= '</div>';
				}
			}
			if(String::strpos($text, '[REOPEN_MAIN_CONTAINER]') !== false) {
				$template_tags['REOPEN_MAIN_CONTAINER'] = '<div class="middle_column container"><div class="middle_column_repeat row"><div class="col-md-12">';
				if(defined('IN_RUBRIQUE') || defined('IN_RUBRIQUE_ARTICLE')) {
					$template_tags['REOPEN_MAIN_CONTAINER'] .= '<div class="rub_wrapper special_content"><div class="rub_content">';
				} elseif(defined('IN_HOME')) {
					$template_tags['REOPEN_MAIN_CONTAINER'] .= '<div class="page_home_content">';
				}
			}
			if (empty($custom_template_tags['NEWSLETTER']) && String::strpos($text, '[NEWSLETTER]') !== false) {
				// On envoie un message qui contient un tag NEWSLETTER et dont on n'a pas spécifié explicitement le contenu => on récupère son contenu automatiqueemnt
				// On prend la dernière newsletter rentrée en BDD - pas de possibilité de faire autrement, sinon il faut passer par le module de gestion de newsletter
				$news_infos = get_last_newsletter(null, $lang);
				if (!empty($news_infos)) {
					// On remplace les tags à l'intérieur de la newsletter pour éviter problèmes et avoir besoin de passer le traitement en double sur l'intégralité du texte
					// Par ailleurs on évite de se retrouver dans une boucle si le texte de la newsletter indiquait (de manière erronée !) un tag [NEWSLETTER]
					$custom_template_tags['NEWSLETTER'] = template_tags_replace(str_replace('[NEWSLETTER]', '', $news_infos['message_' . $frm['lang']]), $custom_template_tags, $replace_only_custom_tags, $format, $lang);
				}
			}
			// Appel aux fonctions propres à chaque module pour récupérer des listes de tags à remplacer
			$template_tags = array_merge($template_tags, call_module_hook('template_tags', array('text' => $text), 'array'));
		}
		if (!empty($custom_template_tags) && is_array($custom_template_tags)) {
			foreach(array('GENDER,CIVILITE', 'NOM_FAMILLE,LASTNAME,LAST_NAME,NOM,NAME', 'FIRST_NAME,FIRSTNAME,PRENOM', 'PSEUDO,LOGIN') as $this_tags_list) {
				// Compatibilité avec autres tags
				foreach(explode(',', $this_tags_list) as $this_tag) {
					if (isset($custom_template_tags[$this_tag])) {
						// Dès qu'on trouve une valeur, on remplit tous les autres (sauf si déjà défini, au cas où il y aurait ambiguité sur un nom
						foreach(explode(',', $this_tags_list) as $replaced_tag) {
							if ($replaced_tag != $this_tag && !isset($custom_template_tags[$replaced_tag])) {
								$custom_template_tags[$replaced_tag] = $custom_template_tags[$this_tag];
							}
						}
					}
				}
			}
			$template_tags = array_merge($template_tags, $custom_template_tags);
		}
		foreach($template_tags as $this_tag => $this_tag_value) {
			// On supprime les ajouts automatiques par l'éditeur de texte
			$text = str_replace('<p>['.$this_tag.']</p>', '['.$this_tag.']', $text);
			// Remplacement de tous les tags en majuscules ou minuscules avant de traiter les dates et heures
			// Si un tag est un mix avec majuscules et minuscules, le remplacement est fait quelques lignes plus loin
			if($format == 'text') {
				$this_tag_value = String::strip_tags($this_tag_value);
			} elseif($format == 'html') {
				$this_tag_value = String::nl2br_if_needed($this_tag_value);
			}
			// ATTENTION : A FAIRE AVANT la gestion des tags de dates à cause de différences entre minuscules et majuscules dans ces tags
			$text = str_replace(array('[' . String::strtoupper($this_tag) . ']', '[' . String::strtolower($this_tag) . ']'), str_replace('&euro;', '€', $this_tag_value), $text);
		}
		if(!$replace_only_custom_tags) {
			// On rajoute des tags de date qui sont en minuscules ou majuscules
			foreach(array('d', 'D', 'j', 'l', 'N', 's', 'w', 'z', 'W', 'F', 'm', 'M', 'n', 't', 'L', 'o', 'Y', 'y', 'a', 'A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u', 'U') as $this_date_item) {
				// Explications de chaque valeur sur : http://fr.php.net/manual/fr/function.date.php
				$template_tags[$this_date_item] = date($this_date_item);
			}
			for($i=0 ; $i<=10 ; $i++) {
				// Gestion de tags YEAR-N
				$template_tags[str_replace('YEAR-0', 'YEAR', 'YEAR-'.$i)] = date('Y')-$i;
			}
		}
		// On gère tous les tags qui restent à remplacer sans modification de la casse
		foreach($template_tags as $this_tag => $this_tag_value) {
			if($format == 'text') {
				$this_tag_value = String::strip_tags($this_tag_value);
			} elseif($format == 'html') {
				$this_tag_value = String::nl2br_if_needed($this_tag_value);
			}
			$text = str_replace('[' . $this_tag . ']', $this_tag_value, $text);
		}
	}
	if(!empty($GLOBALS['site_parameters']['replace_words_after_tags_replace'])) {
		// Remplacement de mots clés par des versions personnalisées pour le site
		foreach($GLOBALS['site_parameters']['replace_words_after_tags_replace'] as $replaced => $new) {
			if(strpos($text, $replaced) !== false) {
				$text = str_replace($replaced, $new, $text);
			}
		}
	}
	return $text;
}

/**
 * Génère les entêtes HTTP pour un fichier CSV
 *
 * @param mixed $filename
 * @param mixed $type
 * @param mixed $page_encoding
 * @return
 */
function output_csv_http_export_header($filename, $type = 'excel', $page_encoding)
{
	if (a_priv('demo')) {
		output_light_html_page($GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], String::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch());
		die();
	}

	if ($type == 'excel') {
		header("Content-Type: application/vnd.ms-excel");
	} else {
		header('Content-Type: application/csv-tab-delimited-table; charset=' . $page_encoding);
	}
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-disposition: filename=" . $filename);
}

/**
 * output_xml_http_export_header()
 *
 * @param string $filename
 * @param string $page_encoding
 * @param string $content_type
 * @param integer $cache_duration_in_seconds
 * @return
 */
function output_xml_http_export_header($filename, $page_encoding, $content_type = 'application/svg+xml', $cache_duration_in_seconds = null)
{
	if (a_priv('demo')) {
		output_light_html_page($GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], String::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch());
		die();
	}
	header('Content-Type: '.$content_type.'; charset=' . $page_encoding);
	if(!empty($cache_duration_in_seconds)) {
		header('Pragma: public');
		header('Cache-Control: public, max-age=' . $cache_duration_in_seconds . ', must-revalidate');
	} else {
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	}
	header("Content-disposition: filename=" . $filename);
}

/**
 * Corrige le contenu à afficher, notamment pour avoir du https même si http est stocké en BDD
 *
 * @param string $output
 * @param boolean $replace_template_tags
 * @param string $format : null => does not touch the format of the tag, "text" or "html"
 * @param string $lang
 * @return
 */
function correct_output(&$output, $replace_template_tags = false, $format = null, $lang = null)
{
	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
		$wwwroot_to_replace = str_replace('https://', 'http://', $GLOBALS['wwwroot']);
		$output = str_replace($wwwroot_to_replace, $GLOBALS['wwwroot'], $output);
	}
	if($replace_template_tags) {
		$output = template_tags_replace($output, array(), false, $format, $lang);
	}
}

/**
 * tabSmileys()
 *
 * @return
 */
function tabSmileys ()
{
	$tab = array(1 => ':)',
		2 => ':(',
		3 => ';)',
		4 => ':D',
		5 => ';;)',
		6 => '>:D<',
		7 => ':-/',
		8 => ':x',
		9 => ':">',
		10 => ':P',
		11 => ':-*',
		12 => '=((',
		13 => ':-O',
		14 => 'X(',
		15 => ':>',
		16 => 'B-)',
		17 => ':-S',
		18 => '#:-S',
		19 => '>:)',
		20 => ':((',
		21 => ':))',
		22 => ':|',
		23 => '/:)',
		24 => '=))',
		25 => 'O:)',
		26 => ':-B',
		27 => '=;',
		28 => 'I-)',
		29 => '|-|',
		30 => 'L-)',
		31 => '8-|',
		32 => ':-$',
		33 => '[-(',
		34 => ':O)',
		35 => '8-}',
		36 => '<:-P',
		37 => '(:|',
		38 => '=P~',
		39 => ':-?',
		40 => '#-o',
		41 => '=D>',
		42 => ':-SS',
		43 => '@-)',
		44 => ':^o',
		45 => ':-w',
		46 => ':-<',
		47 => '>:P',
		48 => '<):)',
		49 => ':@)',
		50 => '3:-O',
		51 => ':(|)',
		52 => '~:>',
		53 => '@};-',
		54 => '%%-',
		55 => '**==',
		56 => '(~~)',
		57 => '~O)',
		58 => '*-:)',
		59 => '8-X',
		60 => '=:)',
		61 => '>-)',
		62 => ':-L',
		63 => '[-O<',
		64 => '$-)',
		65 => ':-"',
		66 => 'b-(',
		67 => ':)>-',
		68 => '[-X',
		69 => ':D/',
		70 => '>:/',
		71 => ';))',
		72 => ':-@',
		73 => '^:)^',
		74 => ':-j',
		75 => '(*)');
	return $tab;
}

/**
 * smileysFormat()
 *
 * @param mixed $string
 * @return
 */
function smileysFormat ($string)
{
	$tab = tabSmileys ();
	krsort($tab);
	foreach($tab as $img => $key) {
		$string = str_replace(htmlentities($key, ENT_COMPAT), '<img src="' . $GLOBALS['wwwroot'] . '/images/smileys/' . $img . '.gif" alt="" align="absMiddle" />', $string);
	}
	return $string;
}

/**
 * Convertit un tableau en chaine de caractère simple à gérer par un utilisateur
 *
 * @param mixed $array
 * @param mixed $disable_ad_quote pour ne pas ajouter de guillemets autour de la valeur à retourner.
 * @return
 */
function get_string_from_array($array, $disable_ad_quote=false)
{
	if(is_array($array)) {
		// NB : Pas besoin de remplacer " par \" dans les valeurs des chaines, le décodage tient compte des " en association avec les virgules uniquement
		if($array===array_values($array)) {
			// On ne précise pas les clés du tableau
			if ($disable_ad_quote) {
				$string = '' . implode(', ', $array) . '';
			} else {
				$string = '"' . implode('", "', $array) . '"';
			}
		}else {
			foreach($array as $this_key => $this_value) {
				if($this_value === true){
					$array[$this_key] = 'true';
				} elseif($this_value === false){
					$array[$this_key] = 'false';
				} elseif($this_value === null){
					$array[$this_key] = 'null';
				} else {
					$array[$this_key] = '"' . $this_value . '"';
				}
			}
			$string = trim(str_replace(array('Array ', '    ', '  ', '  '), array('Array', ' ', ' ', ' '), str_replace(array("Array,", "),", "(,", ",)"), array("Array ", ")", "(", ")"), str_replace(array("\r\n", "\n"), ',', print_r($array, true)))));
			$string = trim(String::substr($string, String::strlen('Array('), String::strlen($string) - String::strlen('Array(')-1));
		}
	} else {
		$string = $array;
	}
	return $string;
}

/**
 * Convertit une chaine de caractère simple à gérer par un utilisateur en un tableau PHP
 *
 * @param mixed $string
 * @return
 */
function get_array_from_string($string)
{
	$string = str_replace('Array ', 'Array', trim(str_replace(array("\t", "\r\n", "\r"), array(' ', "\n", ''), $string)));
	if(String::substr($string, 0, String::strlen('Array')) == 'Array') {
		$string = String::substr($string, String::strlen('Array('), String::strlen($string) - String::strlen('Array(')-1);
	}
	$parts = explode(',', str_replace("\n", ',', $string));
	$result = array();
	foreach($parts as $this_part_key => $this_part) {
		if(empty($skip_part_key_array) || !in_array($this_part_key, $skip_part_key_array)) {
			$this_part = trim($this_part);
			if(!empty($this_part)){
				$line = explode('=>', $this_part, 2);
				if(!isset($line[1])) {
					$this_value = trim($line[0]);
				} else {
					$this_key = trim($line[0]);
					$this_value = trim($line[1]);
				}
				if(in_array(String::substr($this_value, 0, 1), array('"', "'", '['))) {
					// On retire le séparateur de début
					$this_value = String::substr($this_value, 1, String::strlen($this_value)-1);						
					$i=1;
					while(!in_array(String::substr($this_value, -1), array('"', "'", ']')) && !empty($parts[$this_part_key+$i])) {
						// On rajoute la suite tant qu'on n'a pas de séparateur de fin : il y avait une ou des virgules dans le texte
						$this_value .= ','.$parts[$this_part_key+$i];
						$skip_part_key_array[] = $this_part_key+$i;
						$i++;
					}
					// On retire le séparateur de fin
					$this_value = String::substr($this_value, 0, String::strlen($this_value)-1);
				}
				if($this_value == 'true'){
					$this_value = true;
				} elseif($this_value == 'false'){
					$this_value = false;
				} elseif($this_value == 'null'){
					$this_value = null;
				}
				if(!isset($line[1])) {
					$result[] = $this_value;
				} else {
					if(in_array(String::substr($this_key, 0, 1), array('"', "'", '['))) {
						$this_key = String::substr($this_key, 1, String::strlen($this_key)-2);
					}
					$result[$this_key] = $this_value;
				}
			}
		}
	}
	return $result;
}

/**
 * Nettoie une chaine des stop words, retire les mots trop courts, et renvoie une liste de mots clés
 *
 * @param mixed $string_or_array
 * @param integer $min_length Doit être compatible avec la longueur minimale des stop words dans $GLOBALS['site_parameters']['filter_stop_words'] => normalement doit être >= 3
 * @param integer $max_length
 * @param boolean $allow_numeric
 * @param boolean $get_long_keywords_first
 * @param integer $max_words
 * @return
 */
function get_keywords_from_text($string_or_array, $min_length = 3, $max_length = 20, $allow_numeric = false, $get_long_keywords_first = true, $max_words = 7) {
	$keywords_array = array();
	if(is_array($string_or_array)) {
		$string = implode(' ', array_unique($string_or_array));
	} else {
		$string = $string_or_array;
	}
	$filter_stop_words_array = array_unique(explode(' ', str_replace(array("\t", "\r", "\n"), ' ', vb($GLOBALS['site_parameters']['filter_stop_words']))));
	// On passe le texte en minuscules
	$string = String::strtolower(' '.String::convert_accents($string));
	// On retire les caractères de ponctuation divers
	$string = str_replace(array(",", ".", "?", "!", ':', ';', "-", "+", '*', "d'", '/', '\\', '(', ')', '[', ']', '{', '}',  "'", '"', '<', '>', '«', '»', '´', '  '), " ", $string);
	// On récupère dans le texte les mots clés candidats
	foreach(explode(' ', $string) as $this_word) {
		if(String::strlen($this_word)>=$min_length && ($allow_numeric || !is_numeric($this_word))){
			$keywords_array[$this_word] = $this_word;
		}
	}
	// On retire à la fin les stop words (moins il y a d'éléments à vérifier, plus c'est rapide)
	$keywords_array = array_diff($keywords_array, $filter_stop_words_array);
	if($get_long_keywords_first) {
		// On garde les mots les plus longs en priorité
		$keywords_lengths = array();
		foreach($keywords_array as $this_word) {
			$keywords_lengths[$this_word] = String::strlen($this_word);
		}
		arsort($keywords_lengths);
		$keywords_array = array_keys($keywords_lengths);
	}
	// On ne garde que la longueur de tableau demandée
	while($max_words !== null && count($keywords_array)>$max_words) {
		array_pop($keywords_array);
	}
	return $keywords_array;
}

/**
 * Highlights terms in text
 *
 * @param string $text
 * @param array $terms
 * @param array $found_words_array
 * @param array $found_tags
 * @return
 */
function highlight_found_text($text, $terms, &$found_words_array, $found_tags = array('<span class="search_tag">', '</span>')) {
	$bbcode = array('[tagsearch]', '[/tagsearch]');
	if(!is_array($terms)) {
		$terms = array($terms);
	}
	foreach ($terms as $this_term) {
		if((String::strlen($text)<80  && String::strlen($this_term)>0) || String::strlen($this_term)>=3) { 
			$preg_condition = getPregConditionCompatAccents($this_term);
			if (stripos($text, $this_term) !== false) {
				$text = preg_replace('/' . $preg_condition . '/iu', $bbcode[0] . '$0' . $bbcode[1], $text, -1);
				$found_words_array[] = $this_term;
			}
		}
	}
	// on remplace le BBcode par les tags demandés - On le fait à la fin pour éviter les problèmes d'échappement avec preg
	return str_replace($bbcode, $found_tags, $text);
}

/**
 * Convert birthday date to age
 *
 * @param string $date
 * @return
 */
function userAgeFormat ($date)
{
 return floor((date('Ymd') - str_replace("-", "", $date)) / 10000);
}

/**
 * Supprime les caractères entre et autour des chiffres dans un numéro de téléphone.
 *
 * @param mixed $phone_number
 * @return
 */
function get_formatted_phone_number($phone_number)
{
	return str_replace(array(' ','/','.','-',')','(','_'), "", $phone_number);
}

/**
 * Vérifie le format d'un mot de passe si une contrainte est configurée
 * 
 * Exemple de regexp qui peut être mise dans $GLOBALS['site_parameters']['password_regexp'] :
 * #.*^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#
 * Explications :
 *	#.*^                   # Start of group
 *	 (?=.*[0-9])           #   must contains one digit from 0-9
 *	 (?=.*[a-z])           #   must contains one lowercase characters
 *	 (?=.*[A-Z])           #   must contains one uppercase characters
 *	 (?=.*\W)              #   must contains one special symbols (ou un caractère accentué)
 *	 .*$#
 * 
 * @param string $string
 * @return
 */
function check_password_format($string) {
	if(!empty($GLOBALS['site_parameters']['password_regexp'])) {
		$result = preg_match($GLOBALS['site_parameters']['password_regexp'], $string);
		return !empty($result);
	} else {
		return true;
	}
}
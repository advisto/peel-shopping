<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 39392 2013-12-20 11:08:42Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * securityCodeCreate()
 *
 * @param string $code
 * @param string $fileName
 * @param integer $noise_level
 * @param integer $noise_max_size
 * @return
 */
function securityCodeCreate($code, $fileName, $noise_level = 1000, $noise_max_size = 3)
{
	// Parametres
	$font = $GLOBALS['dirroot'] . "/modules/captcha/security_codes/bkant.ttf";
	$fontSize = 25;
	$imageWidth = 200;
	$imageHeight = 70;
	// Create image
	$image = imagecreatetruecolor($imageWidth, $imageHeight);
	// On créé une image
	$colorWhite = imagecolorallocate($image, 255, 255, 255);
	$colorBlack = imagecolorallocate($image, 0, 0, 0);
	// On la remplit de blanc
	imagefill($image, 0, 0, $colorWhite);
	$x = mt_rand(15, 20);
	$y = mt_rand(0, 7);

	for ($i = 0; $i < String::strlen($code); $i++) {
		$x_rand = mt_rand(32, 37) * $i + 20;
		$y_rand = mt_rand(35, 55);
		$f_rand = mt_rand(-30, 30);
		$color = imagecolorallocate($image, mt_rand(30, 100), mt_rand(30, 100), mt_rand(30, 100));
		imagettftext ($image, $fontSize, $f_rand, $x_rand, $y_rand, $color, $font, $code{$i});
	}
	for($i = 1;$i <= $noise_level;$i++) {
		// Boucle pour faire 200 points de $color
		$x = mt_rand(0, $imageWidth);
		$y = mt_rand(0, $imageHeight);
		$color = imagecolorallocate($image, mt_rand(0, 180), mt_rand(0, 180), mt_rand(0, 180));
		if (rand(1, 5) > 1) {
			imagesetpixel($image, $x, $y, $color);
		} else {
			$size = rand(1, $noise_max_size);
			imagefilledarc($image, $x, $y, $size, $size, 0, 360, $color, IMG_ARC_PIE);
		}
	}

	imagepng($image, $fileName);
	imagedestroy($image);
}

/**
 * get_captcha_inside_form()
 *
 * @param array $frm
 * @return HTML
 */
function get_captcha_inside_form(&$frm)
{
	$output = '';
	// Code security
	$codeSecurityPath = '/modules/captcha/security_codes/' . '%s.png';
	$generateNewCode = false;

	if (empty($frm['code_id'])) {
		$generateNewCode = true;
	} elseif (isset($frm['form_regenerate_code']) && $frm['form_regenerate_code'] == '1') {
		$generateNewCode = true;
	} else {
		$test = sprintf($GLOBALS['dirroot'] . $codeSecurityPath, $frm['code_id']);
		if (!file_exists($test)) {
			$generateNewCode = true;
		} else {
			$code_id = $frm['code_id'];
			$codeSecurityPath = sprintf($codeSecurityPath, $code_id);
		}
	}

	if ($generateNewCode === true) {
		// Réinitialisation de la donnée du formulaire
		$frm['code'] = null;
		$code = '';
		while ($code < 10000) {
			$n = mt_rand(0, 9);
			if ($n != 1 && $n != 7 && ($code !== '' || $n != 0)) {
				// On ne prend pas de 1 ou de 7 pour éviter confusions
				$code .= $n;
			}
		}
		query('INSERT INTO peel_security_codes
			SET code="' . nohtml_real_escape_string($code) . '", time="' . time() . '"');
		$code_id = insert_id();
		$codeSecurityPath = sprintf($codeSecurityPath, $code_id);
		securityCodeCreate($code, $GLOBALS['dirroot'] . $codeSecurityPath);
	}

	$output .= '<img src="' . $GLOBALS['wwwroot'] . $codeSecurityPath . '" alt="Captcha" /><input type="hidden" name="code_id" value="' . intval($code_id) . '" />';
	return $output;
}

/**
 *
 * @param string $code
 * @param class $id
 * @return
 */
function check_captcha($code, $id)
{
	$q_code = query('SELECT COUNT(*)
		FROM peel_security_codes
		WHERE id="' . nohtml_real_escape_string($id) . '" AND code="' . nohtml_real_escape_string($code) . '"');
	if ($r_code = fetch_row($q_code)) {
		return $r_code[0];
	} else {
		return false;
	}
}
/**
 *
 * @param integer $form_object_id
 * @return
 */
function delete_captcha($form_object_id)
{
	$codeSecurityPath = $GLOBALS['dirroot'] . '/modules/captcha/security_codes/' . $form_object_id . '.png';

	query('DELETE
		FROM peel_security_codes
		WHERE id="' . nohtml_real_escape_string($form_object_id) . '"');
	@unlink($codeSecurityPath);
}

/**
 * clean_security_codes()
 *
 * @param integer $older_than_hours
 * @return
 */
function clean_security_codes($older_than_hours = 12)
{
	// On supprime tout ce qui dépasse $older_than_hours heures
	query('DELETE FROM peel_security_codes
		WHERE time<="' . intval(time() - 3600 * $older_than_hours) . '"');
	$dir = $GLOBALS['dirroot'] . '/modules/captcha/security_codes/';
	$i = 0;
	if ($handle = opendir($dir)) {
		while ($file = readdir($handle)) {
			// On supprime les anciens fichiers de plus de $older_than_hours heures qui ne sont pas des fichiers de typo (.ttf)
			if ($file != '.' && $file != '..' && $file[0] != '.' && String::strpos($file, '.ttf') === false && filemtime($dir . $file) < time() - 3600 * $older_than_hours) {
				@unlink($dir . $file);
				$i++;
			}
		}
	}
	if (!empty($GLOBALS['contentMail'])) {
		$GLOBALS['contentMail'] .= 'Suppression des fichiers de plus de ' . $older_than_hours . ' heures dans le dossier ' . $dir . '/ : ';
		$GLOBALS['contentMail'] .= 'Ok - ' . $i . ' fichiers supprimés' . "\r\n\r\n";
	}
}

?>
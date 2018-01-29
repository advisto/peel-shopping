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
// $Id: images.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * On redimensionne l'image pour qu'elle ne dépasse pas la taille de destination
 * Le ratio largeur / hauteur va être celui de l'image source, sans modification
 *
 * @param string $origin_filename_with_path
 * @param string $new_filename_with_path
 * @param integer $destinationW Largeur maximale de l'image générée
 * @param integer $destinationH Hauteur maximale de l'image générée
 * @param boolean $resize_even_if_smaller Si true, on force le changement de taille de l'image même si elle est plus petite que $destinationW et $destinationH
 * @param boolean $allow_keep_origin_file_if_resize_not_needed Si true, permet de ne pas recréer l'image via PHP => évite la perte de données, et permet de garder des GIF animés
 * @param integer $filesize_limit_keep_origin_file Taille limite pour appliquer la règle ci-dessus
 * @param mixed $jpeg_quality
 * @param float $gammacorrect
 * @return
 */
function image_resize($origin_filename_with_path, $new_filename_with_path, $destinationW = null, $destinationH = null, $resize_even_if_smaller = false, $allow_keep_origin_file_if_resize_not_needed = true, $filesize_limit_keep_origin_file = 102400, $jpeg_quality = 85, $gammacorrect = 1.0)
{
	$origin_file_extension = pathinfo($origin_filename_with_path, PATHINFO_EXTENSION);
	if (StringMb::strtolower($origin_file_extension) === 'png' && function_exists('imagecreatefrompng')) {
		$source = @imagecreatefrompng($origin_filename_with_path);
	} elseif (StringMb::strtolower($origin_file_extension) === 'gif' && function_exists('imagecreatefromgif')) {
		$source = @imagecreatefromgif($origin_filename_with_path);
	}
	if (empty($source) && function_exists('imagecreatefromjpeg')) {
		// On essaie quoiqu'il arrive de décoder en JPEG
		$source = @imagecreatefromjpeg($origin_filename_with_path);
		if (empty($source)) {
			$source = @imagecreatefrompng($origin_filename_with_path);
		}
	}
	if (empty($source)) {
		return false;
	}
	$sourceW = @imagesx($source);
	$sourceH = @imagesy($source);
	if (empty($sourceW) || empty($sourceH)) {
		return false;
	}
	// Zoom par défaut si l'image est plus petite que la limite $image_max_width x $image_max_height
	$default_zoom = 1;

	if ((!empty($destinationW) && $sourceW * $default_zoom > $destinationW) || (!empty($destinationH) && $sourceH * $default_zoom > $destinationH) || ((!empty($destinationW) || !empty($destinationH)) && $resize_even_if_smaller)) {
		if(empty($destinationW)){
			$destinationW = $sourceW;
		}
		if(empty($destinationH)){
			$destinationH = $sourceH;
		}
		if ($sourceH * $destinationW > $destinationH * $sourceW) {
			// on met au même format que celui de la taille demandée
			$destinationW = ($sourceW * $destinationH) / $sourceH;
		} else {
			$destinationH = ($sourceH * $destinationW) / $sourceW;
		}
	} else {
		$destinationW = $sourceW * $default_zoom;
		$destinationH = $sourceH * $default_zoom;
	}
	if ($destinationW != $sourceW || $destinationH != $sourceH || !$allow_keep_origin_file_if_resize_not_needed || filesize($origin_filename_with_path) > $filesize_limit_keep_origin_file || abs(1-$gammacorrect)>=0.1) {
		// On crée la nouvelle image seulement si c'est nécessaire, pour ne pas perdre en qualité
		$im = imagecreatetruecolor($destinationW, $destinationH);
		// Pour éviter les bordures noires : on remplit de blanc le bord
		$bg = imagecolorallocate($im, 255, 255, 255);
		imagefill($im, 0, 0, $bg);
		// On fait la copie en redimensionnant
		imagecopyresampled($im, $source, 0, 0, 0, 0, $destinationW, $destinationH, $sourceW, $sourceH);
		imagedestroy($source);
		if (abs(1-$gammacorrect)>=0.1) {
			imagegammacorrect ($im, 1.0, $gammacorrect);
		}
		if (!imagejpeg($im, $new_filename_with_path, $jpeg_quality)) {
			return false;
		}
		imagedestroy($im);
		if ($new_filename_with_path != $origin_filename_with_path && !empty($origin_filename_with_path)) {
			unlink($origin_filename_with_path);
		}
		return $new_filename_with_path;
	} else {
		imagedestroy($source);
		return $origin_filename_with_path;
	}
}


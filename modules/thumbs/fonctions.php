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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Charge l'image dont le nom est $source_filename dans le répertoire d'upload, et crée une vignette pour cette image si elle n'existe pas déjà
 * NB : On n'affiche les éventuels messages d'erreur sur une image uniquement si l'IP est concernée par l'affichage des erreurs
 * Cela évite d'avoir ce message envoyé lors de la préparation de page, de sorte qu'une erreurs headers already sent serait déclenchée
 *
 * @param string $source_filename
 * @param integer $width
 * @param integer $height
 * @param string $method
 * @param string $source_folder
 * @param string $thumb_folder
 * @param boolean $thumb_rename
 * @param boolean $return_absolute_path
 * @param boolean $allow_return_path_to_local_original_if_unchanged
 */
function thumbs($source_filename, $width, $height, $method = 'fit', $source_folder = null, $thumb_folder = null, $thumb_rename = true, $return_absolute_path=false, $allow_return_path_to_local_original_if_unchanged = true)
{
	static $tpl_error;
	if (empty($source_filename)) {
		return false;
	}
	$source_filename = str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], $source_filename);
	if(strpos($source_filename, '/') === 0 && !empty($source_folder)) {
		// On force le chemin du dossier à root car le chemin relatif est imposé par le nom du fichier
		$source_folder = $GLOBALS['dirroot'];
	}
	$file_type = get_file_type($source_filename);
	if($file_type == 'pdf') {
		// Gestion des pdf
		$source_filename = 'logoPDF_small.png';
		$source_folder = $GLOBALS['dirroot'] .'/images/';
	} elseif($file_type == 'zip') {
		// Gestion des zip
		$source_filename = 'zip.png';
		$source_folder = $GLOBALS['dirroot'] .'/images/';
	} elseif($file_type != 'image') {
		// Gestion des autres documents
		$source_filename = 'document.png';
		$source_folder = $GLOBALS['dirroot'] .'/images/';
	}
	if (empty($thumb_folder)) {
		if(strpos($source_filename, '//') !== false || strpos($source_filename, '/'.$GLOBALS['site_parameters']['cache_folder'].'/') === false) {
			$thumb_folder = $GLOBALS['uploaddir'] . '/thumbs/';
			if(!empty($GLOBALS['site_parameters']['thumbs_using_subfolders'])) {
				$use_subfolders = true;
			}
		} else {
			$thumb_folder = $GLOBALS['dirroot'].'/'.$GLOBALS['site_parameters']['cache_folder'].'/';
		}
	}
	if(strpos($source_filename, '//')!==false) {
		// URL présente dans le nom de l'image
		$source_path = str_replace(' ', '%20', $source_filename);
		// Pas possible de récupèrer la date et l'heure de dernière modification de l'image
		$srcTime = 0;
	} else {
		if ($source_folder === null) {
			if(strpos($source_filename, '/') === false) {
				$source_folder = $GLOBALS['uploaddir'];
			} else {
				$source_folder = $GLOBALS['dirroot'];
			}
		}
		if(StringMb::substr($source_folder, -1) != '/') {
			$source_folder .= '/';
		}
		if(strpos($source_filename, $source_folder) === false) {
			$source_path = $source_folder . $source_filename;
		} else {
			$source_path = $source_filename;
		}
		// On récupère la date et l'heure de dernière modification de l'image
		$srcTime = @filemtime($source_path);
	}
	// C'est possible uniquement si l'image est en local, et pas via HTTP
	if(empty($srcTime) && strpos($source_filename, '//')===false && !file_exists($source_path)){
		// L'image est en local et pourtant il n'est pas possible d'avoir sa date de mise à jour
		// Donc c'est a priori qu'elle n'existe pas, et on l'a vérifié tout de même
		if (!empty($GLOBALS['display_errors']) && a_priv('admin*', false)) {
			$GLOBALS['notification_output_array'][] = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_THUMBS_IMAGE_NOT_AVAILABLE_MESSAGE'] .' '. $source_path))->fetch();
		}
		return false;
	}

	if (empty($thumb_rename)) {
		// On veut que le nom de la vignette soit le même que celui de l'image source
		$thumb_filename = @basename($source_filename);
		$thumb_path = $thumb_folder . $thumb_filename;
	} elseif($thumb_rename === true) {
		$extension = @pathinfo($source_path , PATHINFO_EXTENSION);
		$nom = @basename($source_path, '.' . $extension);
		// On récupère la taille de l'image pour l'adjoindre au nom
		$inWidth = vb($width);
		$inHeight = vb($height);
		// On génère le nom de l'image cache
		$folder_hash = StringMb::substr(md5($source_path . '-' . $width . 'x' . $height . '-' . $method), 0, vn($GLOBALS['site_parameters']['thumbs_name_suffix_length'], 6));
		$thumb_filename = $nom . '-' . $folder_hash . '.' . $extension;
		if(!empty($use_subfolders)) {
			$folder1 = StringMb::substr($folder_hash, 0, 2);
			$folder2 = ''; // StringMb::substr($folder_hash, 2, 2);
			$thumb_path = $thumb_folder . $folder1 . '/' . (!empty($folder2) ? $folder2 . '/':'') . $thumb_filename;
		} else {
			$thumb_path = $thumb_folder . $thumb_filename;
		}
	} else {
		$thumb_filename = $thumb_rename;
		$thumb_path = $thumb_folder . $thumb_filename;
	}
	$thumb_path_filemtime=@filemtime($thumb_path);
	// Si on peut avoir la date de modification de 'image source srcTime :
	// => ALORS : Si la vignette n'existe pas ou qu'elle est plus vieille que la source, alors on la calcule
	// Sinon, si image source accessible via HTTP et on ne peut pas avoir la date de modification de 'image source srcTime :
	// => ALORS : Si la vignette n'existe pas ou qu'elle est datée de plus de 10 jours, on la calcule
	if ((!empty($_GET['update']) && $_GET['update'] == 1) || (!empty($_GET['update_thumbs']) && $_GET['update_thumbs'] == 1) || (!empty($srcTime) && $srcTime > $thumb_path_filemtime) || (empty($srcTime) && (empty($thumb_path_filemtime) || time()-24*10*3600>$thumb_path_filemtime))) {
		if(!empty($GLOBALS['site_parameters']['skip_images_keywords'])){
			// On ne veut pas générer le thumb, et ATTENTION : on le prend si il existe
			foreach($GLOBALS['site_parameters']['skip_images_keywords'] as $this_keyword){
				if(strpos($source_filename, $this_keyword)!==false) {
					// On va prendre cette image uniquement si elle existe déjà, mais on ne va pas la générer (par exemple : problème temporaire de site distant)
					$skip_creation = true;
				}
			}
		}
		if(empty($skip_creation)){
			$imgInfo = @getimagesize($source_path);
			if(empty($imgInfo)){
				// L'image ne semble pas valide
				if (!empty($GLOBALS['display_errors']) && a_priv('admin*', false)) {
					$GLOBALS['notification_output_array'][] = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_THUMBS_PICTURE_NOT_SUPPORTED'] . ' ' . $source_path))->fetch();
				}
				$skip_creation = true;
			}
		}
		if(!empty($skip_creation)){
			if(empty($thumb_path_filemtime)){
				// Le thumb n'existe pas du tout : on ne renvoie rien
				return false;
			} else {
				// Le thumb existe même si arrivé à échéance, et le site distant ne renvoie plus rien du tout
				// => on met à jour le timestamp du fichier pour éviter de chercher à recharger à chaque fois le fichier
				touch($thumb_path);
			}
		}elseif(empty($skip_creation)){
			// On a trouvé l'image source et on veut générer le thumb
			$srcWidth = $imgInfo[0];
			$srcHeight = $imgInfo[1];
			$srcType = $imgInfo[2];
			switch ($srcType) {
				case 1 : $srcType = "gif";
					break;
				case 2 : $srcType = "jpeg";
					break;
				case 3 : $srcType = "png";
					break;
				default: $srcType = "???";
			}
			if ($method == "stretch") {
				// Methode avec taille exacte
				// Exact size
				$outWidth = $width;
				$outHeight = $height;
			} else {
				// Methode avec taille proportionnelle
				// Max size : resize
				$xRatio = ($width) ? ($srcWidth / $width) : 0;
				$yRatio = ($height) ? ($srcHeight / $height): 0;
				$ratio = max($xRatio, $yRatio, 1);
				$outWidth = intval($srcWidth / $ratio);
				$outHeight = intval($srcHeight / $ratio);
			}
			if($allow_return_path_to_local_original_if_unchanged && strpos($source_path, '//') === false && ($return_absolute_path === true || strpos($return_absolute_path, '//') !== false) && $srcWidth == $outWidth && $srcHeight == $outHeight) {
				// On évite de générer un thumbs de la même taille que l'image originale
				// Du coup à chaque appel au thumb, il y aura eu un @getimagesize en plus du @filemtime => un peu plus lent, mais pas beaucoup tant que ce n'est pas un appel http
				// On gagne au final ici le fait de ne pas avoir généré de thumb qui soit stocké sur le disque, et la qualité de l'image est celle de l'original, et si c'est un GIF animé il l'est toujours
				return str_replace($GLOBALS['dirroot'], $GLOBALS['wwwroot'], $source_path);
			}
			// Création de l'image de sortie
			$outImg = imagecreatetruecolor ($outWidth, $outHeight);
			// Load src image
			switch ($srcType) {
				case "png":
					$srcImg = imagecreatefrompng($source_path);
					// avant de copier
					// on désactive le blending de chaque pixel
					imagealphablending($outImg, false);
					// on définit l'alpha de destination
					imagesavealpha($outImg, true);
					break;
				case "gif":
					$srcImg = imagecreatefromgif($source_path);
					// On récupère la couleur transparente de l'image source si elle existe
					$src_transparent_index = imagecolortransparent($srcImg);
					if($src_transparent_index!=(-1) && $src_transparent_index<imagecolorstotal($srcImg)) {
						$transparent_color = imagecolorsforindex($srcImg, $src_transparent_index);
					}
					if(!empty($transparent_color))
					{
						$out_transparent = imagecolorallocate($outImg, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
						$background = imagecolortransparent($outImg, $out_transparent);
					}
					break;
				case "jpeg":
					$srcImg = imagecreatefromjpeg($source_path);
					break;
				default:
					if (!empty($GLOBALS['display_errors'])  && a_priv('admin*', false)) {
						$GLOBALS['notification_output_array'][] = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_THUMBS_PICTURE_NOT_SUPPORTED'] . ' ' . $source_path))->fetch();
					}
					return false;
			}
			if(!empty($srcImg)) {
				if(empty($background)) {
					// Applique un fond blanc à l'image
					$background = imagecolorallocate($outImg, 255, 255, 255);
				}
				imagefill($outImg, 0, 0, $background);
				// Retaille l'image
				imagecopyresampled($outImg, $srcImg, 0, 0, 0, 0, $outWidth, $outHeight, $srcWidth, $srcHeight);
				if(!empty($use_subfolders)) {
					if(!is_dir($thumb_folder . $folder1)) {
						mkdir($thumb_folder . $folder1);
					}
					if(!empty($folder2) && !is_dir($thumb_folder . $folder1 . '/' . $folder2)) {
						mkdir($thumb_folder . $folder1 . '/'. $folder2);
					}
				}
				// Sauvegarde dans le répertoire Cache
				switch ($srcType) {
					case "png":
						$res = imagepng($outImg, $thumb_path);
						break;
					case "gif":
						$res = imagegif($outImg, $thumb_path);
						break;
					case "jpeg":
						$res = imagejpeg($outImg, $thumb_path, vn($GLOBALS['site_parameters']['jpeg_quality']));
						break;
					default:
				}
			}
			if (empty($res)) {
				if (!empty($GLOBALS['display_errors'])  && a_priv('admin*', false)) {
					$GLOBALS['notification_output_array'][] = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_THUMBS_CANNOT_SAVE_PICTURE']))->fetch();
				}
				if(empty($thumb_path_filemtime)){
					return false;
				} else {
					// Le thumb existe même si arrivé à échéance, et le site ne renvoie plus rien du tout
					// => on met à jour le timestamp du fichier pour éviter de chercher à recharger à chaque fois le fichier
					touch($thumb_path);
				}
			}
		}
	}
	if(!empty($return_absolute_path) && !empty($thumb_filename)) {
		if($return_absolute_path === true) {
			return $GLOBALS['repertoire_upload'] . '/thumbs/' . StringMb::rawurlencode($thumb_filename);
		} else {
			return $return_absolute_path . StringMb::rawurlencode($thumb_filename);
		}
	} else {
		return StringMb::rawurlencode($thumb_filename);
	}
}


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 36232 2013-04-05 13:16:01Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Charge l'image dont le nom est $image dans le répertoire d'upload, et crée une vignette pour cette image si elle n'existe pas déjà
 * NB : On n'affiche les éventuels messages d'erreur sur une image uniquement si l'IP est concernée par l'affichage des erreurs
 * Cela évite d'avoir ce message envoyé lors de la préparation de page, de sorte qu'une erreurs headers already sent serait déclenchée
 *
 * @param string $image
 * @param integer $width
 * @param integer $height
 * @param string $method
 * @param string $path
 * @param string $new_file_path
 * @param boolean $rename_file
 * @param boolean $return_absolute_path
 * @return string Nom du fichier de la vignette
 */
function thumbs($image, $width, $height, $method = 'fit', $path = null, $new_file_path = null, $rename_file = true, $return_absolute_path=false)
{
	if (empty($image)) {
		return false;
	}
	if ($path === null) {
		if(strpos($image, '/cache')===false) {
			$path = $GLOBALS['uploaddir'];
		} else {
			$path = $GLOBALS['dirroot'];
		}
	}
	if (empty($new_file_path)) {
		if(strpos($image, '/cache')===false) {
			$new_file_path = $GLOBALS['uploaddir'] . '/thumbs/';
		} else {
			$new_file_path = $GLOBALS['dirroot'].'/cache';
		}
	}
	if(strpos($image, '://')!==false) {
		// URL présente dans le nom de l'image
		$imageFile = $image;
		// Pas possible de récupèrer la date et l'heure de dernière modification de l'image
		$srcTime = 0;
	} else {
		$imageFile = $path . '/' . $image;
		// On récupère la date et l'heure de dernière modification de l'image
		$srcTime = @filemtime($imageFile);
	}
	// C'est possible uniquement si l'image est en local, et pas via HTTP
	if(empty($srcTime) && strpos($image, '://')===false && !file_exists($imageFile)){
		// L'image est en local et pourtant il n'est pas possible d'avoir sa date de mise à jour
		// Donc c'est a priori qu'elle n'existe pas, et on l'a vérifié tout de même
		if (!empty($GLOBALS['display_errors']) && a_priv('admin*', false)) {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_THUMBS_IMAGE_NOT_AVAILABLE_MESSAGE'] .' '. $imageFile))->fetch();
		}
		return false;
	}

	if (empty($rename_file)) {
		// On veut que le nom de la vignette soit le même que celui de l'image source
		$cachedThumbFileName = $image;
	} else {
		$extension = @pathinfo($imageFile , PATHINFO_EXTENSION);
		$nom = @basename($imageFile, '.' . $extension);
		// On récupère la taille de l'image^pour l'adjoindre au nom
		$inWidth = vb($width);
		$inHeight = vb($height);
		// On génère le nom de l'image cache
		$cachedThumbFileName = $nom . '-' . String::substr(md5($imageFile . '-' . $width . 'x' . $height . '-' . $method), 0, 4) . '.' . $extension;
	}

	$cachedThumbFile = $new_file_path . $cachedThumbFileName;
	$cachedThumbFile_filemtime=@filemtime($cachedThumbFile);
	// Si on peut avoir la date de modification de 'image source srcTime :
	// => ALORS : Si la vignette n'existe pas ou qu'elle est plus vieille que la source, alors on la calcule
	// Sinon, si image source accessible via HTTP et on ne peut pas avoir la date de modification de 'image source srcTime :
	// => ALORS : Si la vignette n'existe pas ou qu'elle est datée de plus de 10 jours, on la calcule
	if ((!empty($_GET['update']) && $_GET['update'] == 1) || (!empty($srcTime) && $srcTime > $cachedThumbFile_filemtime) || (empty($srcTime) && (empty($cachedThumbFile_filemtime) || time()-24*10*3600>$cachedThumbFile_filemtime))) {
		if(!empty($GLOBALS['skip_images_keywords'])){
			foreach($GLOBALS['skip_images_keywords'] as $this_keyword){
				if(strpos($image, $this_keyword)!==false) {
					$skip_creation = true;
				}
			}
		}
		if(empty($skip_creation)){
			$imgInfo = @getimagesize($imageFile);
			if(empty($imgInfo)){
				// L'image ne semble pas valide
				if (!empty($GLOBALS['display_errors'])  && a_priv('admin*', false)) {
					echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_THUMBS_PICTURE_NOT_SUPPORTED'] . ' ' . $imageFile))->fetch();
				}
				$skip_creation = true;
			}
		}
		if(!empty($skip_creation)){
			if(empty($cachedThumbFile_filemtime)){
				// Le thumb n'existe pas du tout : on ne renvoie rien
				return false;
			} else {
				// Le thumb existe même si arrivé à échéance, et le site ne renvoie plus rien du tout
				// => on met à jour le timestamp du fichier pour éviter de chercher à recharger à chaque fois le fichier
				touch($cachedThumbFile);
				return false;
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
			// Création de l'image de sortie
			$outImg = imagecreatetruecolor ($outWidth, $outHeight);
			// Applique un fond blanc à l'image
			imagefill($outImg, 0, 0, imagecolorallocate($outImg, 255, 255, 255));
			// Load src image
			switch ($srcType) {
				case "png":
					$srcImg = imagecreatefrompng($imageFile);
					// avant de copier
					// on désactive le blending de chaque pixel
					imagealphablending($outImg, false);
					// on définit l'alpha de destination
					imagesavealpha($outImg, true);
					break;
				case "gif":
					$srcImg = imagecreatefromgif($imageFile);
					break;
				case "jpeg":
					$srcImg = imagecreatefromjpeg($imageFile);
					break;
				default:
					if (!empty($GLOBALS['display_errors'])  && a_priv('admin*', false)) {
						echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_THUMBS_PICTURE_NOT_SUPPORTED']))->fetch();
					}
					return false;
			} ;
			// Retaille l'image
			imagecopyresampled($outImg, $srcImg, 0, 0, 0, 0, $outWidth, $outHeight, $srcWidth, $srcHeight);
			// Sauvegarde dans le répertoire Cache
			switch ($srcType) {
				case "png":
					$res = imagepng($outImg, $cachedThumbFile);
					break;
				case "gif":
					$res = imagegif($outImg, $cachedThumbFile);
					break;
				case "jpeg":
					$res = imagejpeg($outImg, $cachedThumbFile, vn($GLOBALS['site_parameters']['jpeg_quality']));
					break;
				default:
					if (!empty($GLOBALS['display_errors'])  && a_priv('admin*', false)) {
						echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_THUMBS_PICTURE_NOT_SUPPORTED'] . ' ' . $imageFile))->fetch();
					}
					if(empty($cachedThumbFile_filemtime)){
						return false;
					}
			}
			if (!$res) {
				if (!empty($GLOBALS['display_errors'])  && a_priv('admin*', false)) {
					echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_THUMBS_CANNOT_SAVE_PICTURE']))->fetch();
				}
				if(empty($cachedThumbFile_filemtime)){
					return false;
				}
			}
		}
	}
	if(!empty($return_absolute_path)) {
		return $GLOBALS['repertoire_upload'] . '/thumbs/' . $cachedThumbFileName;
	} else {
		return $cachedThumbFileName;
	}
}

?>
<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fine_uploader.php 36927 2013-05-23 16:15:39Z gboussin $

include("configuration.inc.php");

// Include the uploader class
require($GLOBALS['dirroot'].'/lib/class/FineUploader.php');

$uploader = new FineUploader();

$file_kind = 'any';
if (!empty($GLOBALS['site_parameters']['extensions_valides_'.$file_kind])) {
	// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
	$uploader->allowedExtensions = $GLOBALS['site_parameters']['extensions_valides_'.$file_kind];
}

// Specify max file size in bytes.
$uploader->sizeLimit = $GLOBALS['site_parameters']['uploaded_file_max_size'];

// Specify the input name set in the javascript.
$uploader->inputName = key($_FILES);

// If you want to use resume feature for uploader, specify the folder to save parts.
$uploader->chunksFolder = $GLOBALS['dirroot'].'/'.$GLOBALS['site_parameters']['cache_folder'];

$save_path = '/'.$GLOBALS['site_parameters']['cache_folder'];
$save_full_path = $GLOBALS['dirroot'].$save_path;

$rename_file = false; // Si false : on ne fait que retraiter le nom de base
$extension = String::strtolower(pathinfo($uploader->getName(), PATHINFO_EXTENSION));
if (empty($new_file_name_without_extension)) {
	// Si aucun nom forcé, on en crée un
	$new_file_name_without_extension = format_filename_base($uploader->getName(), $rename_file);
}
$the_new_file_name = $new_file_name_without_extension . '.' . $extension;

$load = true;
if(empty($new_file_name_without_extension)) {
	$load = false;
}
if($uploader->inputName == 'name1') {
	
}
if($load) {
	// To save the upload with a specified name, set the second parameter.
	$result = $uploader->handleUpload($save_full_path, $the_new_file_name);

	// To return a name used for uploaded file you can use the following line.
	$result['uploadName'] = $uploader->getUploadName();
	
	if (!empty($GLOBALS['site_parameters']['extensions_valides_image']) && in_array($extension, $GLOBALS['site_parameters']['extensions_valides_image'])) {
		// Les fichiers image sont convertis en jpg uniquement si nécessaire - sinon on garde le fichier d'origine
		$the_new_jpg_name = $new_file_name_without_extension . '.jpg';
		// On charge l'image, et si sa taille est supérieure à $destinationW ou $destinationH, ou si elle fait plus de $GLOBALS['site_parameters']['filesize_limit_keep_origin_file'] octets, on doit la régénèrer (sinon on la garde telle qu'elle était)
		// Si on est dans le cas où on la regénère, on la convertit en JPEG à qualité $GLOBALS['site_parameters']['jpeg_quality'] % (par défaut dans PHP c'est 75%, et dans PEEL on utilise 88% par défaut) et on la sauvegarde sous son nouveau nom
		$image_resize_result = image_resize($save_full_path . '/' . $the_new_file_name, $save_full_path . '/' . $the_new_jpg_name, $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], false, true, $GLOBALS['site_parameters']['filesize_limit_keep_origin_file'], $GLOBALS['site_parameters']['jpeg_quality']);
		if (!empty($image_resize_result)) {
			// Le redimensionnement de l'image a eu lieu
			$result['uploadName'] = basename($image_resize_result);
		}
	}
	if ($extension == 'pdf') {
		$type = 'pdf';
	} else {
		$type = 'img';
	}
	// On renvoie le HTML qu'on veut afficher à la place du bouton upload
	$tpl = $GLOBALS['tplEngine']->createTemplate('uploaded_file.tpl');
	$file_infos = array('name' => $result['uploadName'],
			'form_name' => $uploader->inputName,
			'form_value' => $save_path.'/'.$result['uploadName'],
			'drop_src' => $GLOBALS['administrer_url'] . '/images/b_drop.png',
			'drop_href' => 'javascript:reinit_upload_field("'.$uploader->inputName.'");',
			'url' => str_replace($GLOBALS['dirroot'], $GLOBALS['wwwroot'], $save_full_path).'/'.$result['uploadName'],
			'type' => $type
		);
	$tpl->assign('f', $file_infos);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$result['html'] = $tpl->fetch();
	
	// On renvoie le résultat au jQuery
	header("Content-Type: text/plain");
	echo json_encode($result);
}
?>
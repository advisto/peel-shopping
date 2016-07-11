<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fine_uploader.php 50572 2016-07-07 12:43:52Z sdelaporte $

define('IN_FINE_UPLOADER', true);
include("configuration.inc.php");

if (vb($GLOBALS['site_parameters']['used_uploader']) != 'fineuploader') {
	die();
}

// Include the uploader class
require($GLOBALS['dirroot'].'/lib/class/FineUploader.php');

$uploader = new FineUploader();

$file_kind = 'any';
if (!empty($GLOBALS['site_parameters']['extensions_valides_'.$file_kind])) {
	// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
	$uploader->allowedExtensions = $GLOBALS['site_parameters']['extensions_valides_'.$file_kind];
}

// Specify max file size in bytes.
$uploader->sizeLimit = min($uploader->toBytes(ini_get('upload_max_filesize')), $uploader->toBytes(ini_get('post_max_size')), $GLOBALS['site_parameters']['uploaded_file_max_size']);

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
	@ignore_user_abort(true);
	@set_time_limit(0);
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
	// On renvoie le HTML qu'on veut afficher à la place du bouton upload
	unset($GLOBALS['js_ready_content_array']);
	$tpl = $GLOBALS['tplEngine']->createTemplate('uploaded_file.tpl');
	if(String::strpos($uploader->inputName, 'upload_multiple') !== false) {
		$uploader->inputName .= '[]';
	}
	$file_infos = get_uploaded_file_infos($uploader->inputName, $save_path.'/'.$result['uploadName'], 'javascript:reinit_upload_field("'.$uploader->inputName.'", "[DIV_ID]");');
	$tpl->assign('f', $file_infos);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$result['html'] = $tpl->fetch();
	if(String::strpos($uploader->inputName, 'upload_multiple') !== false) {
		// Ajout de champ vide
		// Name vaudra upload_multiple[], mais l'id de la div généré va être upload_multiple_openarray__closearray_
		$file_infos = get_uploaded_file_infos('upload_multiple[]', null, 'javascript:reinit_upload_field("upload_multiple[]");');
		$tpl->assign('f', $file_infos);
		$result['html'] .= $tpl->fetch();
		$GLOBALS['js_ready_content_array'][] = 'init_fineuploader($("#upload_multiple_openarray__closearray_"));';
	}
	if(!empty($GLOBALS['js_ready_content_array'])) {
		$result['html'] .= '
<script><!--//--><![CDATA[//><!--
	(function($) {
		$(document).ready(function() {
			' . implode("\n", $GLOBALS['js_ready_content_array']) . '
		});
	})(jQuery);
//--><!]]></script>
';
	}
	// On renvoie le résultat au jQuery
	header("Content-Type: text/plain");
	echo json_encode($result);
}

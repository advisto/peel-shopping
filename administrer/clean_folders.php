<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: clean_folders.php 35064 2013-02-08 14:16:40Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$DOC_TITLE = $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_TITLE'];

$errorMsg = '';
$value['file_shortpath'] = '';
$value['tx_qualite'] = $GLOBALS['site_parameters']['jpeg_quality'];
$value['enlighten'] = '';
$value['size_ko'] = 500;
$accepted_formats['form_image'] = array('jpg', 'JPG', 'jpeg', 'JPEG');

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");

$output = '';
// si un clic a été fait sur le bouton supprimer alors on vide le réperoire
if (vb($_GET['suppr']) == 'thumbs') {
	$files_deleted = nettoyer_dir($GLOBALS['uploaddir'] . '/thumbs');
} elseif (vb($_GET['suppr']) == 'cache') {
	$files_deleted = nettoyer_dir($GLOBALS['dirroot'] . '/' . $GLOBALS['site_parameters']['cache_folder']);
}
if (isset($files_deleted)) {
	$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CLEAN_FOLDERS_MSG_FILES_DELETED'], $files_deleted)))->fetch();
}
$show_form = true;
if (isset($_POST['file_shortpath']) && isset($_POST['tx_qualite'])) {
	if (empty($_POST['file_shortpath'])) {
		$errorMsg .= ' ' . $GLOBALS['STR_ADMIN_ERR_EMPTY_PATH'] . '<br />';
	}
	if (empty($_POST['tx_qualite'])) {
		$errorMsg .= ' ' . $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_ERR_EMPTY_QUALITY'];
	} elseif (is_numeric($_POST['tx_qualite'])) {
		if ($_POST['tx_qualite'] > 100 || $_POST['tx_qualite'] <= 0) {
			$errorMsg .= $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_QUALITY_LABEL'];
		}
	} else {
		$errorMsg .= $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_ERR_QUALITY'];
	}
	if (!empty($errorMsg)) {
		$value['file_shortpath'] = $_POST['file_shortpath'];
		$value['tx_qualite'] = $_POST['tx_qualite'];
		$value['enlighten'] = vb($_POST['enlighten']);
		$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $errorMsg))->fetch();
	} else {
		$show_form = false;
		$i = 0;
		$j = 0;
		if (substr($_POST['file_shortpath'], -1) == '/') {
			$_POST['file_shortpath'] = substr($_POST['file_shortpath'], 0, strlen($_POST['file_shortpath'])-1);
		}
		$chemin_final = $dirroot . '/' . $_POST['file_shortpath'];
		if (is_dir($chemin_final)) {
			if ($dir_pointer = opendir($chemin_final)) {
				while ($filename = readdir($dir_pointer)) {
					if ($filename != '.' && $filename != '..' && is_file($chemin_final . '/' . $filename) && filesize($chemin_final . '/' . $filename) >= vn($_POST['size_ko']) * 1024) {
						$array = explode('.', $filename);
						$extension = $array[count($array) - 1];
						if (in_array(strtolower($extension), $accepted_formats['form_image'])) {
							echo filesize($chemin_final . '/' . $filename) . ' - ' . $chemin_final . '/' . $filename;
							image_resize($chemin_final . '/' . $filename, $chemin_final . '/' . $filename, $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], false, true, vn($_POST['size_ko']) * 1024, $_POST['tx_qualite'], (vb($_POST['enlighten']) == 'on'?1.6:1.0));
							$i++;
						}
					} else {
						$j++;
						if ($j % 100 == 0) {
							echo $j . '... ';
							// Force l'envoi du HTML juste généré au navigateur, pour que l'utilisateur suive en temps réel l'avancée
							flush();
						}
						// echo $chemin_final . '/' . $filename.'<br />';
					}
				}
				closedir($dir_pointer);
			} else {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_ERR_OPEN_DIRECTORY']))->fetch();
			}
		} elseif (is_file($chemin_final) && filesize($chemin_final) >= vn($_POST['size_ko']) * 1024) {
			image_resize($chemin_final, $chemin_final, $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], false, true, vn($_POST['size_ko']) * 1024, $_POST['tx_qualite'], 1.0);
			$i++;
		}
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_CLEAN_FOLDERS_MSG_IMAGES_OPTIMIZED_OK'], $i)))->fetch();
	}
}

if ($show_form) {
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_clean_folders.tpl');
	$tpl->assign('action_thumbs', $GLOBALS['administrer_url'] . '/clean_folders.php?suppr=thumbs');
	$tpl->assign('action_cache', $GLOBALS['administrer_url'] . '/clean_folders.php?suppr=cache');
	$tpl->assign('action_images', $GLOBALS['administrer_url'] . '/clean_folders.php');
	$tpl->assign('dirroot', $GLOBALS['dirroot']);
	$tpl->assign('file_shortpath', $value['file_shortpath']);
	$tpl->assign('tx_qualite', $value['tx_qualite']);
	$tpl->assign('size_ko', $value['size_ko']);
	$tpl->assign('enlighten', $value['enlighten']);
	$tpl->assign('STR_TEXT_CONFIG', $GLOBALS['STR_TEXT_CONFIG']);
	$tpl->assign('STR_CLEAN', $GLOBALS['STR_CLEAN']);
	$tpl->assign('STR_SUBMIT', $GLOBALS['STR_SUBMIT']);
	$tpl->assign('STR_ADMIN_CLEAN_FOLDERS_QUALITY', $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_QUALITY']);
	$tpl->assign('STR_ADMIN_CLEAN_FOLDERS_MINIMAL_SIZE', $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_MINIMAL_SIZE']);
	$tpl->assign('STR_KILOBYTE', $GLOBALS['STR_KILOBYTE']);
	$tpl->assign('STR_ADMIN_CLEAN_FOLDERS_EMPTY_CACHE', $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_EMPTY_CACHE']);
	$tpl->assign('STR_ADMIN_CLEAN_FOLDERS_EMPTY_CACHE_EXPLAIN', $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_EMPTY_CACHE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_CLEAN_FOLDERS_OPTIMIZE_IMAGES_EXPLAIN', $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_OPTIMIZE_IMAGES_EXPLAIN']);
	$tpl->assign('STR_ADMIN_IMAGE_SHORT_PATH', $GLOBALS['STR_ADMIN_IMAGE_SHORT_PATH']);
	$tpl->assign('STR_ADMIN_CLEAN_FOLDERS_ENLIGHTEN_IMAGE', $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_ENLIGHTEN_IMAGE']);
	$tpl->assign('STR_ADMIN_CONFIRM_JAVASCRIPT', $GLOBALS['STR_ADMIN_CONFIRM_JAVASCRIPT']);
	$output .= $tpl->fetch();
}
echo $output;
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

?>
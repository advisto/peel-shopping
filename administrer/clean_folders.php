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
// $Id: clean_folders.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_CLEAN_FOLDERS_TITLE'];

$errorMsg = '';
$form_values = array();
$form_values['file_shortpath'] = '';
$form_values['tx_qualite'] = $GLOBALS['site_parameters']['jpeg_quality'];
$form_values['enlighten'] = '';
$form_values['size_ko'] = 500;
$accepted_formats['form_image'] = array('jpg', 'JPG', 'jpeg', 'JPEG');

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

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
		$form_values['file_shortpath'] = $_POST['file_shortpath'];
		$form_values['tx_qualite'] = $_POST['tx_qualite'];
		$form_values['enlighten'] = vb($_POST['enlighten']);
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
				while (false !== ($filename = readdir($dir_pointer))) {
					if ($filename != '.' && $filename != '..' && is_file($chemin_final . '/' . $filename) && filesize($chemin_final . '/' . $filename) >= vn($_POST['size_ko']) * 1024) {
						$array = explode('.', $filename);
						$extension = $array[count($array) - 1];
						if (in_array(StringMb::strtolower($extension), $accepted_formats['form_image'])) {
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
if (!empty($_GET['create_thumbs_subfolders']) || !empty($_GET['delete_thumbs_not_in_subfolders'])) {
	$folder_origin = '/home/back214750/.snapshots/daily.0/localhost/home/algomtl/domains/algomtl.com/public_html/upload/thumbs/'; //$GLOBALS['uploaddir'] . '/thumbs/';
	$folder = $GLOBALS['uploaddir'] . '/thumbs/';
	if (is_dir($folder_origin)) {
		$dir_pointer = opendir($folder_origin);
		if(!empty($dir_pointer)) {
			$i = 0;
			while (false !== ($filename = readdir($dir_pointer))) {
				if ($filename != '.' && $filename != '..' && is_file($folder_origin . '/' . $filename)) {
					$filename_no_ext = pathinfo($filename, PATHINFO_FILENAME);
					if(StringMb::substr($filename_no_ext, -5, 1) != '-') {
						echo '<b>' . $filename . ' NOK nom</b><br />';
					} else {
						if(!empty($_GET['create_thumbs_subfolders'])) {
							$folder1 = StringMb::substr($filename_no_ext, -4, 2);
							$folder2 = ''; //StringMb::substr($filename_no_ext, -2, 2);
							if(!is_file($folder . '/' . $folder1 . '/' . (!empty($folder2) ? $folder2 . '/':'') . $filename)) {
								if(!is_dir($folder . '/' . $folder1)) {
									mkdir($folder . '/' . $folder1);
								}
								if(!empty($folder2) && !is_dir($folder . '/' . $folder1 . '/' . $folder2)) {
									mkdir($folder . '/' . $folder1 . '/' . $folder2);
								}
								if(empty($_GET['test'])) {
									copy($folder_origin . '/' . $filename, $folder . '/' . $folder1.'/' . (!empty($folder2) ? $folder2 . '/':'') . $filename);
								}
								echo '' . $filename . ' copied<br />';
							} else {
								echo '' . $filename . ' already exists<br />';
							}
						}
						if(!empty($_GET['delete_thumbs_not_in_subfolders']) && !empty($filename)) {
							if(empty($_GET['test'])) {
								unlink($folder_origin . '/' . $filename);
							}
							echo '' . $filename . ' deleted<br />';
						}
					}
				}
				$i++;
				if($i==100) {
					// On recharge la page pour recommencer
					echo '<meta http-equiv="refresh" content="1; url='.get_current_url(true).'">';
					die();
				}
				if($i%20==0) {
					sleep(1);
				}
			}
			closedir($dir_pointer);
		}
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf('%s not found', $folder)))->fetch();
	}
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => 'FINISHED'))->fetch();
}

if ($show_form) {
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_clean_folders.tpl');
	$tpl->assign('action_thumbs', $GLOBALS['administrer_url'] . '/clean_folders.php?suppr=thumbs');
	$tpl->assign('action_cache', $GLOBALS['administrer_url'] . '/clean_folders.php?suppr=cache');
	$tpl->assign('action_images', $GLOBALS['administrer_url'] . '/clean_folders.php');
	$tpl->assign('dirroot', $GLOBALS['dirroot']);
	$tpl->assign('file_shortpath', $form_values['file_shortpath']);
	$tpl->assign('tx_qualite', $form_values['tx_qualite']);
	$tpl->assign('size_ko', $form_values['size_ko']);
	$tpl->assign('enlighten', $form_values['enlighten']);
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
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_REFRESH', $GLOBALS['STR_REFRESH']);
	$output .= $tpl->fetch();
}
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_haut.php 38972 2013-11-24 19:26:15Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_haut.tpl');
$tpl->assign('lang', $_SESSION['session_langue']);
$tpl->assign('sortie_href', $GLOBALS['wwwroot'] . '/sortie.php');
$tpl->assign('STR_ADMIN_DISCONNECT', $GLOBALS['STR_ADMIN_DISCONNECT']);
$tpl->assign('doc_title', String::ucfirst(String::str_shorten(trim(String::strip_tags(String::html_entity_decode_if_needed(str_replace(array("\r", "\n"), '', vb($DOC_TITLE))))), 80, '', '', 65)));
$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);
$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
if($_SESSION['session_langue'] == 'fr') {
	$tpl->assign('generator', 'https://www.peel.fr/');
} else{
	$tpl->assign('generator', 'http://www.peel-shopping.com/');
}
if (!IN_INSTALLATION) {
	$admin_welcome = $GLOBALS['STR_HELLO'] . '&nbsp;' . vb($_SESSION['session_utilisateur']['prenom']) . '';
} else {
	$admin_welcome = $GLOBALS['STR_HELLO'];
}
$tpl->assign('admin_welcome', $admin_welcome);
$tpl->assign('page_title', str_replace($GLOBALS['site'], '<a href="' . $GLOBALS['wwwroot'] . '/">' . $GLOBALS['site'] . '</a>', $DOC_TITLE));
$tpl->assign('logo_src', $GLOBALS['wwwroot'] . '/images/logo-peel-admin.png');
$tpl->assign('admin_menu', get_admin_menu());
$tpl->assign('is_demo_error', ((empty($_COOKIE['demo_warning_close']) || $_COOKIE['demo_warning_close']!='closed') && a_priv('demo')));
$tpl->assign('flags', affiche_flags(true, null, false, $GLOBALS['admin_lang_codes'], true, 26));
$tpl->assign('site', $GLOBALS['site']);
$tpl->assign('GENERAL_ENCODING', GENERAL_ENCODING);
$tpl->assign('IN_INSTALLATION', IN_INSTALLATION);
$tpl->assign('STR_ADMIN_DEMO_WARNING', $GLOBALS['STR_ADMIN_DEMO_WARNING']);
$tpl->assign('STR_ADMINISTRATION', $GLOBALS['STR_ADMINISTRATION']);
if (!empty($GLOBALS['site_parameters']['favicon'])) {
	$tpl->assign('favicon_href', $GLOBALS['repertoire_upload'] . '/' . $GLOBALS['site_parameters']['favicon']);
}
$GLOBALS['js_files'][-10] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery.js';
$GLOBALS['js_files'][-5] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-ui.js';
if (is_annonce_module_active()) {
	$GLOBALS['css_files'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/annonces/rating_bar/rating.css';
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/annonces/rating_bar/js/rating.js';
}
$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/advisto.js';
$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/admin_all_functions.js';
if (vn($GLOBALS['site_parameters']['html_editor']) == '1') {
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/nicEditor/nicEdit.js';
}
$GLOBALS['css_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/css/jquery-ui.css';
if(file_exists($GLOBALS['dirroot'] . '/lib/js/jquery.ui.datepicker-'.$_SESSION['session_langue'].'.js')) {
	// Configuration pour une langue donnée
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery.ui.datepicker-'.$_SESSION['session_langue'].'.js';
}
$datepicker_format = str_replace(array('%d','%m','%Y','%y'), array('dd','mm','yy','y'), $GLOBALS['date_format_short']);
$GLOBALS['js_ready_content_array'][] = '
		$(".datepicker").datepicker({
			dateFormat: "'.$datepicker_format.'",
			changeMonth: true,
			changeYear: true,
			yearRange: "1902:2037"
		});
		$(".datepicker").attr("placeholder","'.str_replace(array('d', 'm', 'y'), array(String::substr(String::strtolower($GLOBALS['strDays']), 0, 1), String::substr(String::strtolower($GLOBALS['strMonths']), 0, 1), String::substr(String::strtolower($GLOBALS['strYears']), 0, 1)), str_replace('y', 'yy', $datepicker_format)).'");
';
if(!empty($_SERVER['HTTP_USER_AGENT']) && (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad'))) {
	// Quand on rentre la date on ne veut pas avoir le clavier qui s'affiche car on se sert du datepicker
	$GLOBALS['js_ready_content_array'][] = '
		$(".datepicker").prop("readonly", true);
		$(".datepicker").css("background-color", "white");
';
}
if(!empty($GLOBALS['load_timepicker'])) {
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-ui-timepicker-addon.js';
	if(file_exists($GLOBALS['dirroot'] . '/lib/js/jquery-ui-timepicker-'.$_SESSION['session_langue'].'.js')) {
		// Configuration pour une langue donnée
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-ui-timepicker-'.$_SESSION['session_langue'].'.js';
	}
	$datepicker_time_format = str_replace(array('h','%H','%M','%S'), array("'h'",'HH','mm','ss'), $GLOBALS['time_format_long']);
	$GLOBALS['js_ready_content_array'][] = '
		load_timepicker = true;
		$(".datetimepicker").datetimepicker({
			dateFormat: "'.$datepicker_format.'",
			changeMonth: true,
			changeYear: true,
			showTimePicker: true,
			showSecond: true,
			timeFormat: "'.$datepicker_time_format.'",
			yearRange: "2012:2037"
		});
		$(".datetimepicker").attr("placeholder","'.str_replace(array('HH', 'MM', 'ss', 'd', 'm', 'y', "'"), array('00', '00', '00', String::substr(String::strtolower($GLOBALS['strDays']), 0, 1), String::substr(String::strtolower($GLOBALS['strMonths']), 0, 1), String::substr(String::strtolower($GLOBALS['strYears']), 0, 1), ""), str_replace('y', 'yy', $datepicker_format . ' ' . str_replace('mm', 'MM', $datepicker_time_format))).'");
';
	if(!empty($_SERVER['HTTP_USER_AGENT']) && (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad'))) {
		// Quand on rentre la date on ne veut pas avoir le clavier qui s'affiche car on se sert du datepicker
		$GLOBALS['js_ready_content_array'][] = '
		$(".datetimepicker").prop("readonly", true);
		$(".datetimepicker").css("background-color", "white");
';
	}
}
if (vb($GLOBALS['site_parameters']['enable_prototype']) == 1 && empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
	// Attention, prototype.js a des incompatibilités avec Bootstrap
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/prototype.js';
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/effects.js';
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/controls.js';
}
if (!IN_INSTALLATION) {
	if (is_module_forum_active()) {
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/forum/forum.js';
	}
	if (is_webmail_module_active()) {
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/function.js';
	}
}
if (vb($GLOBALS['site_parameters']['used_uploader']) == 'fineuploader') {
	// Fineuploader désactivé pour IE8
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-fineuploader.js';
	$GLOBALS['css_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/css/fineuploader.css';
	$GLOBALS['js_content_array'][] = '
		function init_fineuploader(object) {
			object.fineUploader({
				multiple: false,
				request: {
						endpoint: "'.$GLOBALS['wwwroot_in_admin'].'/fine_uploader.php?origin='.urlencode($_SERVER['SCRIPT_FILENAME']).'",
						inputName: object.attr("id")
					},
				text: {
					uploadButton: "' . String::str_form_value($GLOBALS["STR_DOWNLOAD"]) . '",
					cancelButton: "' . String::str_form_value($GLOBALS["STR_CANCEL"]) . '",
					failUpload: "' . String::str_form_value($GLOBALS["STR_FTP_GET_FAILED"]) . '",
					formatProgress: "{percent}% ' . String::str_form_value($GLOBALS["STR_OUT_OF"]) . ' {total_size}"
				}
			}).on("complete", function(event, id, fileName, responseJSON) {
				if (responseJSON.success) {
					object.replaceWith(responseJSON.html);
				}
			});
		}
		function reinit_upload_field(input_name) {
			$("#"+input_name).replaceWith("<div class=\"uploader\" id=\""+input_name+"\"></div>");
			init_fineuploader($("#"+input_name));
		}
';
	$GLOBALS['js_ready_content_array'][] = '
		$("input[type=file]").each(function () {
			$(this).replaceWith("<div class=\"uploader\" id=\""+$(this).attr("name")+"\"></div>");
		});
		$(".uploader").each(function () {
			init_fineuploader($(this));
		});
';
}
// Bootstrap obligatoire dans l'administration
$GLOBALS['css_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/css/bootstrap.css';
$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/bootstrap.js';
// On met en dernier fichiers CSS du site pour qu'ils aient priorité
$GLOBALS['css_files'][] = $GLOBALS['repertoire_css'] . '/admin.css';
// Pour PHP 32 bits, les dates ne doivent pas aller avant 1902 et après 2038, car l'intervalle de validité d'un timestamp va du Vendredi 13 Décembre 1901 20:45:54 UTC au Mardi 19 Janvier 2038 03:14:07 UTC.
if(!empty($GLOBALS['sortable_rpc'])) {
	$GLOBALS['js_ready_content_array'][] = '
		$(".sortable").sortable({
			placeholder: "highlight",
			opacity: 0.6,
			axis: "y",
			delay: 150,
			placeholder: "horizontal_placeholder",
			forcePlaceholderSize: true,
			helper: function(e, tr)
			{
				// Correctif pour la largeur de la ligne déplacée
				var $originals = tr.children();
				var $helper = tr.clone();
				$helper.children().each(function(index)
				{
					// Set helper cell sizes to match the original sizes
					$(this).width($originals.eq(index).width())
				});
				return $helper;
			},
			update: function() {
				var order = $(this).sortable("serialize");
				$.post("'.$GLOBALS['administrer_url'] . '/' . $GLOBALS['sortable_rpc'].'", order);
				$(".position").fadeTo(600,0.2);
			}
		});
		$(".sortable").disableSelection(); // on désactive la possibilité au navigateur de faire des sélections
';
}
$tpl->assign('error_text_to_display', vb($GLOBALS['error_text_to_display']));
$tpl->assign('css_files', get_css_files_to_load(!empty($GLOBALS['site_parameters']['minify_css'])));
// Les fichiers js sont traités dans le footer
$tpl->assign('js_files', null);
output_general_http_header();
echo $tpl->fetch();

?>
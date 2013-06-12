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
// $Id: haut.php 37040 2013-05-30 13:17:16Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}
$js_document_ready_array=array();
$js_array=array();

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_haut.tpl');
$tpl->assign('doc_title', vb($DOC_TITLE));
$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);
$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
if (!IN_INSTALLATION) {
	$admin_welcome = $GLOBALS['STR_HELLO'] . '&nbsp;' . vb($_SESSION['session_utilisateur']['prenom']) . '';
} else {
	$admin_welcome = $GLOBALS['STR_HELLO'];
}
$tpl->assign('admin_welcome', $admin_welcome);
$tpl->assign('page_title', str_replace($GLOBALS['site'], '<a href="' . $GLOBALS['wwwroot'] . '/" style="margin-right:70px;">' . $GLOBALS['site'] . '</a>', $DOC_TITLE));
$tpl->assign('logo_src', $GLOBALS['wwwroot'] . '/images/logo-peel.png');
$tpl->assign('admin_menu', get_admin_menu());
$tpl->assign('is_demo_error', a_priv('demo'));
$tpl->assign('flags', affiche_flags(true, null, false, $GLOBALS['admin_lang_codes']));
$tpl->assign('site', $GLOBALS['site']);
$tpl->assign('GENERAL_ENCODING', GENERAL_ENCODING);
$tpl->assign('IN_INSTALLATION', IN_INSTALLATION);
$tpl->assign('STR_ADMIN_DEMO_WARNING', $GLOBALS['STR_ADMIN_DEMO_WARNING']);
$tpl->assign('STR_ADMINISTRATION', $GLOBALS['STR_ADMINISTRATION']);
if (!empty($GLOBALS['site_parameters']['favicon'])) {
	$tpl->assign('favicon_href', $GLOBALS['repertoire_upload'] . '/' . $GLOBALS['site_parameters']['favicon']);
}
if (is_annonce_module_active()) {
	$GLOBALS['css_files'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/annonces/rating_bar/rating.css';
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/annonces/rating_bar/js/rating.js';
}
$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/admin_all_functions.js';
if (vn($GLOBALS['site_parameters']['html_editor']) == '1') {
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/nicEditor/nicEdit.js';
}
$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery.js';
$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-ui.js';
$GLOBALS['css_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/css/jquery-ui.css';
if(file_exists($GLOBALS['dirroot'] . '/lib/js/jquery.ui.datepicker-'.$_SESSION['session_langue'].'.js')) {
	// Configuration pour une langue donnée
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery.ui.datepicker-'.$_SESSION['session_langue'].'.js';
}
$js_document_ready_array[] = '
		$(".datepicker").datepicker({                    
			dateFormat: "'.str_replace(array('%d','%m','%Y','%y'), array('dd','mm','yy','y'), $GLOBALS['date_format_short']).'",
			changeMonth: true,
			changeYear: true,
			yearRange: "1902:2037"
		});
';
if(!empty($GLOBALS['load_timepicker'])) {
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-ui-timepicker-addon.js';
	if(file_exists($GLOBALS['dirroot'] . '/lib/js/jquery-ui-timepicker-'.$_SESSION['session_langue'].'.js')) {
		// Configuration pour une langue donnée
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-ui-timepicker-'.$_SESSION['session_langue'].'.js';
	}
	$js_document_ready_array[] = '
		$(".datetimepicker").datetimepicker({                    
			dateFormat: "'.str_replace(array('%d','%m','%Y','%y'), array('dd','mm','yy','y'), $GLOBALS['date_format_short']).'",
			changeMonth: true,
			changeYear: true,
			showTimePicker: true,
			showSecond: true,
			timeFormat: "'.str_replace(array('h','%H','%M','%S'), array("'h'",'HH','mm','ss'), $GLOBALS['time_format_long']).'",
			yearRange: "2012:2037"
		});
';
}
if (vb($GLOBALS['site_parameters']['enable_prototype']) == 1) {
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
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-fineuploader.js';
	$GLOBALS['css_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/css/fineuploader.css';
	$js_document_ready_array[] = '
		$("input[type=file]").each(function () {
			$(this).replaceWith("<div class=\"uploader\" id=\""+$(this).attr("name")+"\"></div>");
		});
		$(".uploader").each(function () {
			init_fineuploader($(this));
		});
';
	$js_array[] = '
		function reinit_upload_field(input_name) {
			jQuery("#"+input_name).replaceWith("<div class=\"uploader\" id=\""+input_name+"\"></div>");
			init_fineuploader(jQuery("#"+input_name));
		}
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
';
}
// On met en dernier fichiers CSS du site pour qu'ils aient priorité
$GLOBALS['css_files'][] = $GLOBALS['administrer_url'] . '/modeles/css/menu.css';
$GLOBALS['css_files'][] = $GLOBALS['administrer_url'] . '/modeles/css/admin.css';
// Pour PHP 32 bits, les dates ne doivent pas aller avant 1902 et après 2038, car l'intervalle de validité d'un timestamp va du Vendredi 13 Décembre 1901 20:45:54 UTC au Mardi 19 Janvier 2038 03:14:07 UTC.
if(!empty($GLOBALS['sortable_rpc'])) {
	$js_document_ready_array[] = '
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
$js_content_array[] = '
(function($) {
    $(document).ready(function() {
		'.implode(' ', $js_document_ready_array).'
	});
})(jQuery);
'.implode(' ', $js_array).'
';
$tpl->assign('css_files', array_unique($GLOBALS['css_files']));
// L'ordre des fichiers js doit être respecté ensuite dans le template
$tpl->assign('js_files', array_unique($GLOBALS['js_files']));
if(!empty($js_content_array)) {
	$tpl->assign('js_content', implode("\n", $js_content_array));
}
header('Content-type: text/html; charset=' . GENERAL_ENCODING);
echo $tpl->fetch();

?>
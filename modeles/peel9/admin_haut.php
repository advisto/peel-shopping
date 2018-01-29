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
// $Id: admin_haut.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_haut.tpl');
$tpl->assign('lang', $_SESSION['session_langue']);
$tpl->assign('sortie_href', get_url('sortie'));
$tpl->assign('STR_ADMIN_DISCONNECT', $GLOBALS['STR_ADMIN_DISCONNECT']);
$tpl->assign('doc_title', StringMb::ucfirst(StringMb::str_shorten(trim(StringMb::strip_tags(StringMb::html_entity_decode_if_needed(str_replace(array("\r", "\n"), '', vb($GLOBALS['DOC_TITLE']))))), 80, '', '', 65)));
$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
$tpl->assign('wwwroot_in_admin', $GLOBALS['wwwroot_in_admin']);
if($_SESSION['session_langue'] == 'fr') {
	$tpl->assign('generator', 'https://www.peel.fr/');
} else {
	$tpl->assign('generator', 'http://www.peel-shopping.com/');
}
if (!IN_INSTALLATION) {
	$admin_welcome = $GLOBALS['STR_HELLO'] . '&nbsp;' . vb($_SESSION['session_utilisateur']['prenom']) . '';
} else {
	$admin_welcome = $GLOBALS['STR_HELLO'];
}
if (StringMb::strpos($GLOBALS['DOC_TITLE'], '<a ') === false) {
	$tpl->assign('page_title', str_replace($GLOBALS['site'], '<a href="' . $GLOBALS['wwwroot'] . '/">' . $GLOBALS['site'] . '</a>', $GLOBALS['DOC_TITLE']));
} else {
	// Un lien est déjà présent dans DOC_TITLE, il ne faut pas faire de remplacement de lien.
	$tpl->assign('page_title', $GLOBALS['DOC_TITLE']);
}
$tpl->assign('admin_welcome', $admin_welcome);
$tpl->assign('logo_src', get_url('/images/logo-peel-admin.png'));
$tpl->assign('admin_menu', get_admin_menu());
$tpl->assign('is_demo_error', ((empty($_COOKIE['demo_warning_close']) || $_COOKIE['demo_warning_close']!='closed') && a_priv('demo')));
$tpl->assign('flags', affiche_flags(true, null, false, $GLOBALS['admin_lang_codes'], false, 26));
$tpl->assign('site', $GLOBALS['site']);
$tpl->assign('GENERAL_ENCODING', GENERAL_ENCODING);
$tpl->assign('IN_INSTALLATION', IN_INSTALLATION);
$tpl->assign('STR_ADMIN_DEMO_WARNING', $GLOBALS['STR_ADMIN_DEMO_WARNING']);
$tpl->assign('STR_ADMINISTRATION', $GLOBALS['STR_ADMINISTRATION']);
if (!empty($GLOBALS['site_parameters']['favicon'])) {
	$tpl->assign('favicon_href', get_url_from_uploaded_filename($GLOBALS['site_parameters']['favicon']));
}
$GLOBALS['js_files'][-10] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery.js';
$GLOBALS['js_files'][-5] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/jquery-ui.js';
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
$GLOBALS['js_ready_content_array'][] = get_datepicker_javascript();

if (vb($GLOBALS['site_parameters']['enable_prototype']) == 1 && empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
	// Attention, prototype.js a des incompatibilités avec Bootstrap
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/prototype.js';
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/effects.js';
	$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/controls.js';
}
if (vb($GLOBALS['site_parameters']['used_uploader']) == 'fineuploader') {
	// Par défaut on peut utiliser fineuploader sur toutes les pages de l'administration
	init_fineuploader_interface();
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
		if ($(".admin_commande_details").length==0) {
			$(".sortable").disableSelection(); // on désactive la possibilité au navigateur de faire des sélections, sauf dans la page de commande.
		}
';
}
$tpl->assign('output_create_or_update_order', vb($GLOBALS['output_create_or_update_order']));
$tpl->assign('notification_output', implode('', $GLOBALS['notification_output_array']));
$tpl->assign('css_files', get_css_files_to_load(!empty($GLOBALS['site_parameters']['minify_css'])));
// Les fichiers js sont traités dans le footer
$tpl->assign('js_files', null);

$hook_result = call_module_hook('admin_header_template_data', array(), 'array');
foreach($hook_result as $this_key => $this_value) {
	$tpl->assign($this_key, $this_value);
}

output_general_http_header();
echo $tpl->fetch();


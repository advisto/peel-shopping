<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: sitemap.php 66961 2021-05-24 13:26:45Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_white_label,admin_webmastering");

$all_sites_name_array = get_all_sites_name_array(false, false, true);
if ((empty($_SESSION['session_admin_multisite']) && count($all_sites_name_array)>1) || (!empty($_SESSION['session_admin_multisite']) && $_SESSION['session_admin_multisite']!=$GLOBALS['site_id'])) {
	// Possibilité de générer le sitemap uniquement pour le domaine en cours d'utilisation, et pas pour le site administré.
	redirect_and_die($GLOBALS['administrer_url'] . '/');
}

if (!empty($_GET['encoding'])) {
	$file_encoding = $_GET['encoding'];
} else {
	$file_encoding = 'utf-8';
}
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_SITEMAP_TITLE'];
$form_error_object = new FormError();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_sitemap.tpl');
$tpl->assign('href', $GLOBALS['wwwroot'] . '/sitemap.xml');
$tpl->assign('STR_ADMIN_SITEMAP_TITLE', $GLOBALS['STR_ADMIN_SITEMAP_TITLE']);
$tpl->assign('STR_ADMIN_SITEMAP_OPEN', $GLOBALS['STR_ADMIN_SITEMAP_OPEN']);
echo $tpl->fetch();


switch (vb($_REQUEST['mode'])) {
	case "lire" :
		if (!verify_token($_SERVER['PHP_SELF'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			create_multisite_google_sitemap();
		} elseif ($form_error_object->has_error('token')) {
			echo $form_error_object->text('token');
		}
		form2xml();
		break;

	default :
		form2xml();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * form2xml()
 *
 * @return
 */
function form2xml()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_sitemap_form2xml.tpl');
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF']));
	$tpl->assign('STR_ADMIN_SITEMAP_CREATE_BUTTON', $GLOBALS['STR_ADMIN_SITEMAP_CREATE_BUTTON']);
	echo $tpl->fetch();
}


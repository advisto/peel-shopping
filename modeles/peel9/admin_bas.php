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
// $Id: admin_bas.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_bas.tpl');
$tpl->assign('site_href', $GLOBALS['wwwroot'] . '/');
$tpl->assign('site', $GLOBALS['site']);
$tpl->assign('PEEL_VERSION', PEEL_VERSION);
$tpl->assign('peel_website_href', 'https://www.peel.fr/');
$tpl->assign('peel_support_href', 'https://www.peel.fr/utilisateurs/contact.php');
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_PEEL_SOFTWARE', $GLOBALS['STR_ADMIN_PEEL_SOFTWARE']);
$tpl->assign('STR_ADMIN_VERSION', $GLOBALS['STR_ADMIN_VERSION']);
$tpl->assign('STR_ADMIN_DISCONNECT', $GLOBALS['STR_ADMIN_DISCONNECT']);
$tpl->assign('STR_ADMIN_SUPPORT', $GLOBALS['STR_ADMIN_SUPPORT']);
$tpl->assign('STR_ADMIN_CONTACT_PEEL', $GLOBALS['STR_ADMIN_CONTACT_PEEL']);
$tpl->assign('STR_ADMIN_CONTACT_PEEL_ADDRESS', $GLOBALS['STR_ADMIN_CONTACT_PEEL_ADDRESS']);
$tpl->assign('sortie_href', get_url('sortie'));
$debug_infos = '';
if (defined('PEEL_DEBUG') && PEEL_DEBUG) {
	$tpl->assign('peel_debug', $GLOBALS['peel_debug']);
}
$tpl->assign('js_output', get_javascript_output(false, false));

$hook_result = call_module_hook('admin_footer_template_data', array(), 'array');
foreach($hook_result as $this_key => $this_value) {
	$tpl->assign($this_key, $this_value);
}

echo $tpl->fetch();

db_close();

<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: societe_liste.php 35805 2013-03-10 20:43:50Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_societe_liste.tpl');

$tpl_results = array();
while ($r = fetch_object($qid)) {
	$tpl_results[] = array('href' => get_current_url(false) . '?mode=modif&id=' . $r->id,
		'societe' => $r->societe,
		'email' => $r->email
		);
}
$tpl->assign('results', $tpl_results);
$tpl1->assign('STR_ADMIN_SOCIETE_LIST_TITLE', $GLOBALS['STR_ADMIN_SOCIETE_LIST_TITLE']);
$tpl1->assign('STR_COMPANY', $GLOBALS['STR_COMPANY']);
$tpl1->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
$tpl1->assign('STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND', $GLOBALS['STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND']);
echo $tpl->fetch();

?>
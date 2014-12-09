<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: compte.php 43040 2014-10-29 13:36:21Z sdelaporte $

include("configuration.inc.php");

define('IN_COMPTE', true);
$GLOBALS['page_name'] = 'compte';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_COMPTE'];

if (check_if_module_active('profil')) {
	include($GLOBALS['fonctionsprofile']);
}
// Desactive le compte de l'utilisateur depuis le front-office
if (est_identifie() && !empty($_GET['unsubscribe_account']) && !empty($GLOBALS['site_parameters']['disable_account_by_user_in_front_office'])) {
	$sql = "UPDATE peel_utilisateurs SET `etat`=0 WHERE id_utilisateur=" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . " AND " . get_filter_site_cond('utilisateurs');
	if(query($sql)) {
		$_SESSION['session_display_popup']['message_text'] = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ACCOUNT_DESACTIVATED']))->fetch();
	}
	redirect_and_die('sortie.php');
}
if (!est_identifie()) {
	$_SESSION['session_redirect_after_login'] = get_current_url(true);
	redirect_and_die($GLOBALS['wwwroot'] . '/membre.php');
}

$GLOBALS['page_related_to_user_id'] = $_SESSION['session_utilisateur']['id_utilisateur'];

include($GLOBALS['repertoire_modele'] . "/haut.php");
if (vb($_GET['error']) == 'admin_rights') {
	echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_NO_RIGHTS_TO_ACCESS_ADMIN']))->fetch();
}
echo print_compte(true);
include($GLOBALS['repertoire_modele'] . "/bas.php");


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
// $Id: contact.php 66961 2021-05-24 13:26:45Z sdelaporte $
define('IN_CONTACT', true);

include("../configuration.inc.php");
if (check_if_module_active('geoip') && !empty($GLOBALS['site_parameters']['filter_no_europ_enable'])) {
	include("../modules/geoip/filter_no_europ.php");
}
if (empty($_GET) && empty($_POST) && get_contact_url(false, false) != get_current_url(false)) {
	redirect_and_die(get_contact_url(false, false), true);
}

$GLOBALS['page_name'] = 'contact';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_CONTACT_US'];
$output = '';
$output .= handle_contact_form($_POST);

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

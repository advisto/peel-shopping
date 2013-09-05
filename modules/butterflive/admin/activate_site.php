<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) Butterflive - en collaboration avec contact@peel.fr    |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// $Id: activate_site.php 37904 2013-08-27 21:19:26Z gboussin $

define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

require_once("../utils.php");
require_once("../include/add_json_functions.php");
require_once("config.php");

$DOC_TITLE = $GLOBALS["STR_MODULE_BUTTERFLIVE_ACTIVATE_TITLE"];
include($GLOBALS['dirroot'] . "/".$GLOBALS['site_parameters']['backoffice_directory_name']."/modeles/haut.php");

butterflive_output_style();

$display_main_page = true;
$badCredentials = false;

if (isset($_REQUEST['password']))
{
	$activate_url = str_replace("/admin/activate_site.php","/tracker",get_current_url(false));
	$url = BUTTERFLIVE_WEBSITE_URL."remote/url_activate?l=".urlencode(get_butterflive_param('mail'))."&p=".urlencode($_REQUEST['password'])."&url=".urlencode($activate_url);
	$content = file_get_contents($url);

	$result = json_decode($content);

	if ($result->status == 'ok') {
		echo '<div class="good">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_ACTIVATE_OK"].'</div>';
		echo '<a href="butterflive.php">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_BACK_TO_CONFIGURATION"].'</a>';
		$display_main_page = false;
	} else {
		// status = error
		if ($result->code == "badcredentials") {
			$badCredentials = true;
		} else {
			echo '<div class="bad">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_ACCOUNT_CONNECTION"].'</div>';
		}
	}
}

if ($display_main_page)
{
	$mail = get_butterflive_param('mail');
	echo '
<table class="main_table">
		<tr>
			<td class="entete" colspan="2">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_CONFIGURE_TITLE"].'</td>
		</tr>
</table>
<br />
'.$GLOBALS["STR_MODULE_BUTTERFLIVE_CONFIGURE_EXPLAIN"].'<br /><br />';
if ($badCredentials) {
	echo '<div class="bad">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_PASSWORD_INVALID"].'</div><br />';
}
	echo '
<form method="post" action="'. get_current_url(false) .'?start=0" enctype="multipart/form-data">
	<table width="40%">
		<tr>
			<td>'.$GLOBALS["STR_EMAIL"].'</td>
			<td>
				<input type="text" size="30" name="login" value="'. $mail .'" readonly="true" style="background-color:#ccc" /><br /><br />
			</td>
		</tr>
		<tr>
			<td>'.$GLOBALS["STR_PASSWORD"].'</td>
			<td>
				<input type="password" size="30" name="password" value="" /><br /><br />
			</td>
		</tr>
		<tr>
			<td>
				<input class="bouton" type="submit" value="'.$GLOBALS["STR_MODULE_BUTTERFLIVE_ACTIVATE_WEBSITE"].'" />
			</td>
		</tr>
		<tr>
			<td>
				<a href="' . BUTTERFLIVE_WEBSITE_URL . "login/recover?email=" . $mail .'">'.$GLOBALS["STR_FORGOT_YOUR_PASSWORD"].'</a>
			</td>
		</tr>

	</table>
</form>
';
}

include($GLOBALS['dirroot'] . "/".$GLOBALS['site_parameters']['backoffice_directory_name']."/modeles/bas.php");


?>
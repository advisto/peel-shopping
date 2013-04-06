<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) Butterflive - en collaboration avec contact@peel.fr    |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// $Id: butterflive.php 36232 2013-04-05 13:16:01Z gboussin $

define('IN_PEEL_ADMIN', true);
//define('BUTTERFLIVE_WEBSITE_URL', 'http://www.butterflive.com/');

require_once "../utils.php";
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage");

require_once("config.php");

$DOC_TITLE = $GLOBALS["STR_MODULE_BUTTERFLIVE_TITLE"];
include($GLOBALS['dirroot'] . "/".$GLOBALS['site_parameters']['backoffice_directory_name']."/modeles/haut.php");


if (isset($_REQUEST['hashcode']))
{
	// Action lancée à l'activation du compte (lorsque l'utilisateur clique sur le lien dans son email d'activation)
	activation_compte();
}
elseif (vb($_REQUEST['mode']) == "modif")
{
	modif_data();
	affiche_admin();
}
elseif (vb($_REQUEST['mode']) == "creation_compte" )
{
	if (vb($_REQUEST['password']) != "" && vb($_REQUEST['password']) == vb($_REQUEST['passwordverif']))
	{
		// Action lancée pour créer un compte.
		create_account();
	}
	else
	{
		display_premiere_utilisation();
	}
}
elseif (vb($_REQUEST['mode']) == "display_account_config")
{
	// Affichage de la page permettant de saisir son compte déjà existant.
	display_account_config();
}
elseif (vb($_REQUEST['mode']) == "utiliser_compte")
{
	utiliser_compte();
}
elseif(premiere_utilisation() || vb($_REQUEST['mode']) == "display_creation_compte")
{
	display_premiere_utilisation();
}
else
{
	// Affiche la page principale d'administration (qui propose l'activation/désactivation du compte).
	affiche_admin();
}





include($GLOBALS['dirroot'] . "/".$GLOBALS['site_parameters']['backoffice_directory_name']."/modeles/bas.php");


/**
 * FONCTIONS
 */

function utiliser_compte()
{
	include("../include/add_json_functions.php");
	echo '
<table class="main_table">
		<tr>
			<td class="entete" colspan="2">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_CONFIGURE"].'</td>
		</tr>
</table>
';

	butterflive_output_style();
	$mail = $_REQUEST['mail'];

	$url = BUTTERFLIVE_WEBSITE_URL."remote/getKeyByMail?mail=".urlencode($mail);
	$resultTxt = file_get_contents($url);
	$result = json_decode($resultTxt);

	if ($result == false) {
		echo "<div class='bad'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_CONFIGURATION"]."</div>";
	} elseif ($result->status == "ok") {
		$key = $result->key;
		butterflive_update_key($key);
		set_butterflive_param('mail', $mail);
		echo "<div class='good'>".sprintf($GLOBALS["STR_MODULE_BUTTERFLIVE_MSG_CONFIGURATION_OK"], $mail)."</div><br />";
		echo "
			<form method=\"get\" action=" . get_current_url(false) . ""." enctype=\"multipart/form-data\">
			<input class=\"bouton\" type=\"submit\" value=\"".$GLOBALS["STR_CONTINUE"]."\"><br /><br />
			</form>";
	} else {
		echo "<div class='bad'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_NO_KEY_FOUND_FOR_THIS_EMAIL"]."</div>";
		echo "
			<form method=\"get\" action=\"" . get_current_url(false) ."\" enctype=\"multipart/form-data\">
			<input class=\"bouton\" type=\"submit\" value=\"".$GLOBALS["STR_BACK"]."\"><br /><br />
			</form>";
	}
}

function display_account_config()
{
echo '
<table class="main_table">
		<tr>
			<td class="entete" colspan="2">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_CONFIGURE"].'</td>
		</tr>
</table>
<br />
'.$GLOBALS["STR_MODULE_BUTTERFLIVE_YOU_HAVE_ALREADY_ACCOUNT"].' :<br /><br />
<form method="post" action="' .  get_current_url(false)  . '?start=0" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="utiliser_compte" />
	<table width="40%">
		<tr>
			<td>'.$GLOBALS["STR_EMAIL"].'</td>
			<td>
				<input type="text" size="30" name="mail" value="" /><br /><br />
			</td>
		</tr>
		<tr>
			<td>
				<input class="bouton" type="submit" value="'.$GLOBALS["STR_MODULE_BUTTERFLIVE_USE_THIS_ACCOUNT"].'" /><br /><br />
			</td>
		</tr>
		<tr>
			<td>
				<a href="'.BUTTERFLIVE_WEBSITE_URL."login/recover".'">'.$GLOBALS["STR_FORGOT_YOUR_PASSWORD"].'</a>
			</td>
		</tr>
	</table>
</form>
'.sprintf($GLOBALS["STR_MODULE_BUTTERFLIVE_NEW_ACCOUNT_OPEN_EXPLAIN"], get_current_url(false) .'?mode=display_creation_compte').'
';
}

function butterflive_update_key($key)
{
	set_butterflive_param('key', $key);
	set_butterflive_param('activation', 'checked');
}

function activation_compte()
{
	require_once("../include/add_json_functions.php");

	butterflive_output_style();

	$url = BUTTERFLIVE_WEBSITE_URL."remote/validatemailService?hashcode=".urlencode($_REQUEST['hashcode']);
	$resultTxt = file_get_contents($url);
	$result = json_decode($resultTxt);

	/*
	 * The message can be:
	 * {
	 * 	"status"=>"ok",
	 *  "code"=>"accountvalidated",
	 *  "key"=>"[the butterflive key]"
	 * }
	 * or:
	 * {
	 * 	"status"=>"pending",
	 *  "code"=>"accountpending",
	 * }
	 * or:
	 * {
	 * 	"status"=>"error",
	 *  "code"=>"the error code",
	 *  "message"=>"a message to display",
	 *  "key"=>"[the butterflive key] (if any)"
	 * }
	 *
	 *
	 * The code can take many different values. You should check these special ones:
	 * 	- 'tokennotfound': means the mail is already used in an existing account
	 *  - 'accountalreadyvalidated': means the account is already validated (you can access the key in the "key" property of the JSON object)
	 *
	 */

	if ($result == false) {
		echo "<div class='bad'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_SERVER_CONNEXION"] ."</div>";
	} elseif ($result->status == 'ok') {
		// Message de succès
		echo "<div class='good'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_MSG_ACCOUNT_VALIDATED_OK"]."</div>";
		butterflive_update_key($result->key);
	} elseif ($result->status == 'pending') {
		// Message de succès
		echo "<div class='bad'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_MSG_RETRY_VALIDATION"]."</div>";
	}
	else
	{
		if ($result->code == 'accountalreadyvalidated')
		{
			// L'adresse email existe déjà
			echo "<div class='bad'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_ALREADY_VALIDATED"]."</div>";
			butterflive_update_key($result->key);
		}
		elseif ($result->code == 'tokennotfound')
		{
			// L'adresse email existe déjà
			echo "<div class='bad'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_INVALID_LINK"]."</div>";
			butterflive_update_key($result->key);
		}
		else
		{
			//Erreur générale
			echo "<div class='bad'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_RETRY_LATER"]."</div>";
		}
	}

}

function create_account()
{
	require_once("../include/add_json_functions.php");

	butterflive_output_style();

	//Mail
	$mail = $_REQUEST['mail'];

	$lastname = $_SESSION['session_utilisateur']['nom_famille'];
	$firstname = $_SESSION['session_utilisateur']['prenom'];
	$companyname = $_SESSION['session_utilisateur']['societe'];

	// Let's get the country code
	$sqlUserCountry = 'SELECT iso
		FROM peel_pays
		WHERE id="' . intval($_SESSION['session_utilisateur']['pays']) . '"
		LIMIT 1';
	$resUserCountry = query($sqlUserCountry);
	if ($country = fetch_assoc($resUserCountry)) {
		$countrycode = $country['iso'];
	}

	//Password
	$password = $_REQUEST['password'];

	//Origin
	$origin = 'peel';

	//URL
	$websiteurl = get_current_url(false);

	//Industry
	$industry = 41;

	//Type
	$websitetype = array();

	//$trackerUrl = get_current_url(false);
	//$trackerUrl = str_replace("admin/butterflive.php", "tracker/", $trackerUrl);

	$url = BUTTERFLIVE_WEBSITE_URL."remote/createAccountService?lastname=".urlencode($lastname)."&mail=".urlencode($mail)."&password=".urlencode($password)."&companyname=".urlencode($companyname)."&countrycode=".urlencode($countrycode)."&origin=".urlencode($origin)."&websiteurl=".urlencode($websiteurl)."&industry=".urlencode($industry)."&firstname=".urlencode($firstname)."&language=fr&validateMailUrl=".urlencode($websiteurl);
	$resultTxt = file_get_contents($url);
	$result = json_decode($resultTxt);


	/*
	 * The message can be:
	 * {
	 * 	"status"=>"ok",
	 *  "token"=>"[the token to validate by mail]"
	 * }
	 * or:
	 * {
	 * 	"status"=>"error",
	 *  "code"=>"the error code",
	 *  "message"=>"a message to display"
	 * }
	 *
	 * The error code can take many different values. You should check this special one:²
	 * 	- 'useralreadyexist': means the mail is already used in an existing account
	 */

	if ($result == false) {
		echo "<div class='bad'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_CONFIGURATION"]."</div>";
	} elseif ($result->status == 'ok') {
		// Message de succès
		set_butterflive_param('mail', $mail);
		echo "<div class='good'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_MSG_INSCRIPTION_OK"]."</div>";
	}
	else
	{
		if ($result->code == 'useralreadyexist')
		{
			//L'adresse mail existe déjà
			echo "<div class='bad'>".sprintf($GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_ALREADY_EXISTING_ACCOUNT"], $mail)." <a href='butterflive.php?mode=utiliser_compte&mail=".urlencode($mail)."'>".sprintf($GLOBALS["STR_MODULE_BUTTERFLIVE_LINK_PEEL_TO_BUTTERFLIVE"], $mail)."</a>. <a href='butterflive.php'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_USER_ANOTHER_EMAIL"]."</a>.</div>";
		}
		else
		{
			//Erreur générale
			echo "<div class='bad'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_ACCOUNT_CREATION_RETRY_LATER"]."</div>";
		}
	}


}

function premiere_utilisation()
{
	if (isset ($_REQUEST['mode']))
	{
		if ((vb($_REQUEST['mode']) == "creation_compte" &&
			(vb($_REQUEST['password']) == "" || vb($_REQUEST['password']) != vb($_REQUEST['passwordverif'])))) {

		} else {
			return false;
		}
	}

	$key = get_butterflive_param('key');
	return $key?false:true;
}

function display_premiere_utilisation()
{
	// Récupération de l'adresse email par défaut
	$row = fetch_row(query("SELECT email
		FROM peel_utilisateurs
		WHERE priv = 'admin'
		ORDER BY id_utilisateur
		LIMIT 1"));
	$mail = $row[0];

	// Paragraphe de présentation
echo '
<style>
<!--
div.butterflive-admin {
	padding: 0px;
}

div.left-side {
	float: left;
	width: 49%;
	margin: 10px 0px;
	color: #333;
}

div.right-side {
	float: right;
	width: 49%;
}

input[type=\'text\'],input[type=\'password\'] {
	width: 300px;
	background-color: transparent;
}

table.creation-form {
	width: 500px;
	border: 1px solid #3353A1;
}

table.creation-form tr td {
	padding: 1px 5px;
}

table.creation-form tr td.menu {
	margin-bottom: 5px;
}

table.creation-form tr td input {
	margin-bottom: 3px;
}

table.creation-form tr td input.bouton {
	cursor: pointer;
}

table.creation-form tr td input.bouton:hover {
	background-color: #FB7203;
}
-->
</style>
<div class="butterflive-admin">
<table class="main_table">
	<tr>
		<td class="entete" colspan="2">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_OPEN_ACCOUNT_TITLE"].'</td>
	</tr>
</table>
<div class="left-side">
<div>'.$GLOBALS["STR_MODULE_BUTTERFLIVE_WELCOME"].'</div><br />
<div>'.$GLOBALS["STR_MODULE_BUTTERFLIVE_WELCOME_EXPLAIN"].'</div><br />
<div>'.$GLOBALS["STR_MODULE_BUTTERFLIVE_WELCOME_EXPLAIN_ACTIVATE"] .'</div>
<br style="clear: both" />
<form method="get" action="'. get_current_url(false) .'?start=0" enctype="multipart/form-data"><input type="hidden" name="mode"	value="display_account_config" /> <input class="bouton" type="submit" value="'.$GLOBALS["STR_MODULE_BUTTERFLIVE_I_ALREADY_HAVE_AN_ACCOUNT"].'"> <br />
<br />';
 if (isset($_REQUEST['password']) && vb($_REQUEST['password']) != vb($_REQUEST['passwordverif'])) {
		echo "<div class='global_error'>".$GLOBALS["STR_MODULE_BUTTERFLIVE_ERR_PASSWORD"]."</div><br />";
	}
	echo '
</form>
<form method="post"	action="'. get_current_url(false) .'?start=0" enctype="multipart/form-data"><input type="hidden" name="mode" value="creation_compte" />
<table class="creation-form">
	<tr>
		<td colspan="2" class="menu">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_ACCOUNT_CREATION"].'</td>
	</tr>
	<tr>
		<td>'.$GLOBALS["STR_EMAIL"].'</td>
		<td><input type="text" size="30" name="mail"
			value="' .  $mail  . '" /></td>
	</tr>
	<tr>
		<td>'.$GLOBALS["STR_PASSWORD"].'</td>
		<td><input type="password" size="20" name="password" value="" /></td>
	</tr>
	<tr>
		<td>'.$GLOBALS["STR_PASSWORD_CONFIRMATION"].'</td>
		<td><input type="password" size="20" name="passwordverif" value="" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input class="bouton" type="submit" value="'.$GLOBALS["STR_MODULE_BUTTERFLIVE_ACCOUNT_CREATE"].'" /></td>
	</tr>
</table>
</form>
</div>
<div class="right-side"><iframe src="http://www.butterflive.com/frames/peel/index.php" width="100%" height="600"> </iframe></div>
</div>
<div style="clear:both"></div>
';
}

/**
 * Affiche la page principale d'administration (qui propose l'activation/désactivation du compte).
 */
function affiche_admin()
{
	butterflive_output_style();
echo '
<div style="width:40%;margin-right: 5%;float:left;">
<table class="main_table">
		<tr>
			<td class="entete" colspan="2">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_CONFIGURE"].'</td>
		</tr>
</table>
<br />
<script><!--//--><![CDATA[//><!--
jQuery(document).ready(function() {
	jQuery.getJSON(\'check_website_url.php\', function(data) {
		jQuery("#checkkey").removeClass("wait");
		if (data.status == \'OK\')
		{

			jQuery("#checkkey").addClass("good");
		}
		else
		{
			jQuery("#checkkey").addClass("bad");
		}
		jQuery("#checkkey").html(data.text);
	});
});
//--><!]]></script>
<div id="checkkey" class="wait">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_PARAMETERS_VERIFY"].'</div><br />
';
	$conf = get_butterflive_conf();
echo '
<form method="post" action="' .  get_current_url(false)  . '?start=0" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="modif" />
	<table class="full_width">
		<tr>
			<td class="top">
				'.$GLOBALS["STR_MODULE_BUTTERFLIVE_ID"].'
			</td>
			<td>
				' .  $conf['mail']  . '<br />
				<a href="' .  get_current_url(false)  . '?mode=display_account_config">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_USE_OTHER_ACCOUNT"].'</a>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="checkbox" name="butterflive" value="true" ' .  $conf['activation']  . '/> '.$GLOBALS["STR_MODULE_BUTTERFLIVE_ACTIVATE"].' <br /><br />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input class="bouton" type="submit" value="'.$GLOBALS["STR_MODULE_BUTTERFLIVE_SAVE_BUTTON"].'" />
			</td>
		</tr>

	</table>
</form>
<br />
<br />
<br />
<br />
<table class="main_table">
		<tr>
			<td class="entete" colspan="2">'.$GLOBALS["STR_MODULE_BUTTERFLIVE_USE_TITLE"].'</td>
		</tr>
</table>
<p>'.$GLOBALS["STR_MODULE_BUTTERFLIVE_DOWNLOAD_EXPLAIN"].'</p>
<a href="http://butterflive.fr/telecharger"><div id="download-widget"><div class="version-widget">'.$GLOBALS["STR_DOWNLOAD"].'</div></div></a>
</div>

<div style="width:40%;margin-right: 2%;float:left;">
<iframe src="http://www.butterflive.com/frames/peel/index.php?mode=logged" style="width:100%; height:400px;"></iframe>
</div>
<div style="clear:both"></div>
';
}

function modif_data()
{
	if (isset($_POST["butterflive"]))
	{
		set_butterflive_param("activation", "checked");
	}
	else
	{
		set_butterflive_param("activation", "unchecked");
	}
}

function get_butterflive_conf()
{
	$conf = array();
	$res = query("SELECT param,value FROM peel_butterflive");
	while ($resultat = fetch_assoc($res))
	{
		$conf[] = $resultat;
	}

	$configuration = array();
	foreach ($conf as $row)
	{
		$configuration[$row['param']] = $row['value'];
	}

	return $configuration;
}
?>
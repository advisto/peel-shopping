{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: access_account_form.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<h1 class="page_title">{$acces_account_txt}</h1>
<div class="page_content">
<p><b>{$new_customer}</b></p>
{$msg_new_customer|nl2br_if_needed}<br />
<br />
<p><b>{$still_customer}</b></p>
{$msg_still_customer|nl2br_if_needed}<p><a class="notice" href="{$pass_perdu_href|escape:'html'}">{$pass_perdu_txt|nl2br_if_needed}</a></p>
<form class="entryform" method="post" action="membre.php">
	<table class="access_account_form">
		<tr>
			<td>{$email_or_pseudo}:</td>
			<td><input type="text" name="email" size="20" value="{$email|str_form_value}" /><br />{$email_error}</td>
		</tr>
		<tr>
			<td>{$STR_PASSWORD}:</td>
			<td><input type="password" name="mot_passe" size="20" value="{$password|str_form_value}" /><br />{$password_error}</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p>{$token}<input type="submit" value="{$login_txt|str_form_value}" class="clicbouton" /></p></td>
		</tr>
	</table>
</form>
</div>
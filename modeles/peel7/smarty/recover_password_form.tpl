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
// $Id: recover_password_form.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<h1 class="page_title">{$get_password}</h1>
{if !empty($message)}
{$message}
{else}
<form class="entryform" method="post" action="{$action|escape:'html'}">
	{if isset($email)}
		<p style="text-justify">{$email.msg_insert|nl2br_if_needed}</p>
		<p>{$email.label}: <input type="email" name="email" size="25" value="{$email.value|str_form_value}" /></p>
		{$email.error}
	{elseif isset($pass)}
		{$pass.empty_field_error}{$pass.mismatch_password_error}
		<p style="text-justify">{$pass.msg_insert_new_password|nl2br_if_needed}{$STR_BEFORE_TWO_POINTS}:</p>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="rec_password_once">{$pass.STR_NEW_PASSWORD} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>

			<span class="enregistrementdroite"><input type="password" name="password_once" id="rec_password_once" size="25" value="{$pass.password_once|str_form_value}" /></span>{$pass.password_once_error}
		</div>
		 <div class="enregistrement">
			<span class="enregistrementgauche">&nbsp;</span>
			<span class="enregistrementdroite" id="pwd_level_image"></span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche">&nbsp;</span>
			<span class="enregistrementdroite">{$pass.STR_STRONG_PASSWORD_NOTIFICATION}</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="rec_password_twice">{$pass.STR_NEW_PASSWORD_CONFIRM} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>

			<span class="enregistrementdroite"><input type="password" name="password_twice" id="rec_password_twice" size="25" value="{$pass.password_twice|str_form_value}" /></span>	{$pass.password_twice_error}
		</div>
	{/if}
	
	<p class="center"><input type="submit" value="{$STR_SEND|str_form_value}" class="clicbouton" /></p>
	{$token}
	<p><a href="{$login_href|escape:'html'}">{$login_txt}</a> | <a href="{$home_href|escape:'html'}">{$home_txt}</a></p>
</form>
{$js_password_control}
{/if}
{* Smarty
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
// $Id: change_password_form.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
*}<h1 property="name" class="page_title">{$change_password}</h1>
{if !empty($noticemsg)}
	{$noticemsg}
{else}
	{if isset($token_error)}{$token_error}{/if}
	<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="ancien_mot_passe">{$STR_OLD_PASSWORD} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="password" class="form-control" name="ancien_mot_passe" id="ancien_mot_passe" size="32" autocapitalize="none" value="{$old_password|str_form_value}" /> {$old_password_error}{$old_password_error2}</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="nouveau_mot_passe">{$STR_NEW_PASSWORD} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="password" class="form-control" name="nouveau_mot_passe" id="nouveau_mot_passe" size="32" autocapitalize="none" value="{$new_password|str_form_value}" /> {$new_password_error}</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche">&nbsp;</span>
			<span class="enregistrementdroite"><div id="pwd_level_image"></div><span class="enregistrementdroite">{$STR_STRONG_PASSWORD_NOTIFICATION}</span></span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="nouveau_mot_passe">{$STR_NEW_PASSWORD_CONFIRM} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="password" class="form-control" name="nouveau_mot_passe2" id="nouveau_mot_passe2" size="32" autocapitalize="none" value="{$new_password_confirm|str_form_value}" /> {$new_password_confirm_error}</span>
		</div>
		<p class="center" style="margin-top:10px">{$token}<input type="submit" value="{$STR_CHANGE|str_form_value}" class="btn btn-primary" />&nbsp;&nbsp;&nbsp;<input type="reset" value="{$STR_EMPTY_FIELDS|str_form_value}" class="btn btn-warning" /></p>
	</form>
	{$js_password_control}
{/if}
{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_newsletter.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}<form method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table cellpadding="5" class="full_width">
		<tr>
			<td class="entete">{$STR_ADMIN_NEWSLETTERS_FORM_TITLE}</td>
		</tr>
		<tr>
			<td>
				<div class="global_help">{$STR_ADMIN_NEWSLETTERS_WARNING}</div>
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td>
				<select name="template_technical_code" id="template_technical_code">
					{$template_technical_code_options}
				</select>
			</td>
		</tr>
		{foreach $langs as $l}
		<tr>
			<td>{$STR_ADMIN_SUBJECT} {$l.lng}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td><input type="text" name="sujet_{$l.lng}" style="width:100%" value="{$l.sujet|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_MESSAGE} {$l.lng}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td>{$l.message_te}</td>
		</tr>
		{/foreach}
		<tr>
			<td class="center"><p><input class="bouton" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
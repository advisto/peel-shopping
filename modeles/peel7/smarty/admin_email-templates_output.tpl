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
// $Id: admin_email-templates_output.tpl 37972 2013-08-30 14:35:54Z sdelaporte $
*}{$action_html}
<h2 style="margin-left:30px; color:#009900;"><a href="{$href|escape:'html'}">{$STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE}{$STR_BEFORE_TWO_POINTS}: {$STR_ADMIN_CLICK_HERE}</a></h2>
<br />
<div class="global_help" style="color:#ff0000;">{$STR_ADMIN_EMAIL_TEMPLATES_WARNING}</div>
<h2 style="margin-left:30px;">{$STR_ADMIN_EMAIL_TEMPLATES_UPDATE_TEMPLATE} {$STR_NUMBER}{$id}</h2><br />
<center>
	<form action="{$action|escape:'html'}" method="post" name="form_modif">
		{$form_token}
		<table class="full_width">
			<tr>
				<td class="top">
					<table class="full_width" cellspacing="3">
						<tr>
							<td width="100">{$STR_CATEGORY}{$STR_BEFORE_TWO_POINTS}:</td>
							<td>{$categories_list}</td>
						</tr>
						<tr>
							<td width="100">{$STR_SIGNATURE}{$STR_BEFORE_TWO_POINTS}:</td>
							<td>
								<select name="default_signature_code">
									{$signature_template_options}
								</select>
							</td>
						</tr>
						<tr>
							<td>{$STR_ADMIN_TECHNICAL_CODE}</td>
							<td><input name="form_technical_code" size="60" type="text" id="technical_code" value="{$technical_code|str_form_value}" /></td>
						</tr>
						<tr>
							<td>{$STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME}</td>
							<td><input name="form_name" size="60" type="text" id="template_name" value="{$name|str_form_value}" /></td>
						</tr>
						<tr>
							<td>{$STR_ADMIN_SUBJECT}</td>
							<td><input name="form_subject" size="60" type="text" id="template_subject" value="{$subject|str_form_value}" /></td>
						</tr>
						<tr>
							<td>{$STR_TEXT}</td>
							<td><textarea name="form_text" id="template_text" style="width:90%; height:300px;">{$text}</textarea></td>
						</tr>
						<tr>
							<td>{$STR_ADMIN_LANGUAGE}</td>
							<td>
							{foreach $langs as $l}
								<input type="radio" name="form_lang" id="template_lang" value="{$l.lng|str_form_value}"{if $l.issel} checked="checked"{/if} /> {$l.lng}
							{/foreach}
							</td>
						</tr>
						<tr>
							<td colspan="2"><br /><center><input name="form_submit_update" type="submit" value="{$STR_ADMIN_EMAIL_TEMPLATES_UPDATE_TEMPLATE|str_form_value}" class="bouton" /></center></td>
						</tr>
					</table>
				</td>
				<td class="top">{$emailLinksExplanations}</td>
			</tr>
		</table>
	</form>
</center>
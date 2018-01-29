{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_email-templates_output2.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}{$action_html}
<form class="entryform form-inline" role="form" action="email-templates.php" method="post" name="form_ajout">
	<div class="entete">{$STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE}</div>
	<div class="alert alert-info">{$STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN}</div>
	<div class="alert alert-info">{$STR_ADMIN_EMAIL_TEMPLATES_MSG_LAYOUT_EXPLAINATION}</div>
	<div class="row">
		{$form_token}
		<div class="col-md-6">
			<table class="full_width">
				<tr>
					<td style="width:100px">{$STR_CATEGORY}{$STR_BEFORE_TWO_POINTS}:</td>
					<td>{$categories_list}</td>
				</tr>
				<tr>
					<td>{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}: </td>
					<td>
						<select class="form-control" name="site_id"style="width:90%">
							{$site_id_select_options}
						</select>
					</td>
				</tr>
				<tr>
					<td>{$STR_SIGNATURE}{$STR_BEFORE_TWO_POINTS}:</td>
					<td>
						<select class="form-control" name="default_signature_code" style="width:90%">
							{$signature_template_options}
						</select>
					</td>
				</tr>
					<td>{$STR_ADMIN_TECHNICAL_CODE}</td>
					<td><input name="form_technical_code" style="width:90%" type="text" class="form-control" id="technical_code" value="{$form_technical_code|str_form_value}" /></td>
				</tr>
				<tr>
					<td>{$STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME}</td>
					<td><input name="form_name" style="width:90%" type="text" class="form-control" id="template_name" value="{$form_name|str_form_value}" /></td>
				</tr>
				<tr>
					<td>{$STR_ADMIN_SUBJECT}</td>
					<td><input name="form_subject" style="width:90%" type="text" class="form-control" id="template_subject" value="{$form_subject|str_form_value}" /></td>
				</tr>
				<tr>
					<td>{$STR_TEXT}</td>
					<td><textarea class="form-control" name="form_text" id="template_text" style="width:90%; height:300px;">{$form_text}</textarea></td>
				</tr>
				<tr>
					<td>{$STR_ADMIN_LANGUAGE}</td>
					<td>
					{foreach $langs as $l}
						<input type="radio" name="form_lang" id="template_lang_{$l.lng|str_form_value}" value="{$l.lng|str_form_value}"{if $l.issel} checked="checked"{/if} /> {$l.lng}
					{/foreach}
					</td>
				</tr>
				<tr>
					<td colspan="2"><br /><center><input name="submit_ajout" type="submit" value="{$STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE|str_form_value}" class="btn btn-primary" /></center></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6">{$emailLinksExplanations}</div>
	</div>
</form>
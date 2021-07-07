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
// $Id: admin_email-templates_output2.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
*}{$message_html}
<form class="entryform form-inline" role="form" action="{$action}" method="post" name="form_ajout">
	{if !empty($params.intro)}
	<div class="entete">{$STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE}</div>
	<div class="alert alert-info">{$STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN}</div>
	<div class="alert alert-info">{$STR_ADMIN_EMAIL_TEMPLATES_MSG_LAYOUT_EXPLAINATION}</div>
	{else}
		<h2>{$STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE}</h2><br />
	{/if}
	<div class="row">
		{$form_token}
		{if !empty($params.emailLinksExplanations)}
			<div class="col-md-6">
		{else}
			<div class="col-md-12">
		{/if}
			<table class="full_width templates_output">
				<tr>
					<td style="width:100px">{$STR_CATEGORY}{$STR_BEFORE_TWO_POINTS}:</td>
					<td>{$categories_list}</td>
				</tr>
				{if !empty($params.site_id)}
					<tr>
						<td>{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}: </td>
						<td>
							<select class="form-control" name="site_id" style="width:90%">
								{$site_id_select_options}
							</select>
						</td>
					</tr>
				{/if}
				{if !empty($params.signature)}
				<tr>
					<td>{$STR_SIGNATURE}{$STR_BEFORE_TWO_POINTS}:</td>
					<td>
						<select class="form-control" name="default_signature_code" style="width:90%">
							{$signature_template_options}
						</select>
					</td>
				</tr>
				{/if}
				{if !empty($params.technical_code)}
				<tr>
					<td>{$STR_ADMIN_TECHNICAL_CODE}</td>
					<td><input name="form_technical_code" style="width:90%" type="text" class="form-control" id="technical_code" value="{$form_technical_code|str_form_value}" /></td>
				</tr>
				{else}
				<tr>
					<td colspan="2"><input name="form_technical_code" type="hidden" class="form-control" id="technical_code" value="{$form_technical_code|str_form_value}" /></td>
				</tr>
				{/if}
				<tr>
					<td>{$STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME}</td>
					<td><input name="form_name" style="width:90%" type="text" class="form-control" id="template_name" value="{$form_name|str_form_value}" /></td>
				</tr>
				<tr>
					<td>{$STR_ADMIN_SUBJECT}</td>
					<td><input name="form_subject" style="width:90%" type="text" class="form-control" id="template_subject" value="{$form_subject|str_form_value}" /></td>
				</tr>
				<tr>
					<td colspan="2">{$show_tag_list}</td>
				</tr>
				<tr>
					<td colspan="2" id="mission_statement_tag">{$mission_statement_tag}</td>
				</tr>
			</table>
		</div>
		{if !empty($params.emailLinksExplanations)}
		<div class="col-md-6">{$emailLinksExplanations}</div>
		{/if}
		<div class="col-md-12">
			<table>
				<tr>
					<td>{$STR_TEXT}</td>
					<td>{$form_text}</td>
				</tr>
				{if !empty($params.image)}
					<tr>
						<td class="title_label">Image haut{$STR_BEFORE_TWO_POINTS}:</td>
						<td>
						{if isset($image_haut)}
							{include file="uploaded_file.tpl" f=$image_haut STR_DELETE=$STR_DELETE_THIS_FILE}
						{else}
							<input name="image_haut" type="file" value="" />
						{/if}
						</td>
					</tr>
					<tr>
						<td class="title_label">Image bas{$STR_BEFORE_TWO_POINTS}:</td>
						<td>
						{if isset($image_bas)}
							{include file="uploaded_file.tpl" f=$image_bas STR_DELETE=$STR_DELETE_THIS_FILE}
						{else}
							<input name="image_bas" type="file" value="" />
						{/if}
						</td>
					</tr>
				{/if}
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
	</div>
</form>
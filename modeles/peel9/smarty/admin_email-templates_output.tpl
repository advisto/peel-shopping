{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_email-templates_output.tpl 64741 2020-10-21 13:48:51Z sdelaporte $
*}{$message_html}
{if !empty($params.intro)}
<h2 style="margin-left:30px; color:#009900;"><a href="{$create_template_href|escape:'html'}">{$STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE}{$STR_BEFORE_TWO_POINTS}: {$STR_CLICK_HERE}</a></h2>
<br />
<div class="alert alert-info">{$STR_ADMIN_EMAIL_TEMPLATES_WARNING}</div>
{/if}
<h2>{$STR_ADMIN_EMAIL_TEMPLATES_UPDATE_TEMPLATE} {$STR_NUMBER}{$id}</h2><br />
<form class="entryform form-inline" role="form" action="{$action|escape:'html'}" method="post" name="form_modif">
	<div class="row">
		{$form_token}
		{if !empty($params.emailLinksExplanations)}
			<div class="col-md-6">
		{else}
			<div class="col-md-12">
		{/if}
					<table class="full_width templates_output" cellspacing="3">
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
							<td><input name="form_technical_code" style="width:90%" type="text" class="form-control" id="technical_code" value="{$technical_code|str_form_value}" /></td>
						</tr>
						{/if}
						<tr>
							<td>{$STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME}</td>
							<td><input name="form_name" style="width:90%" type="text" class="form-control" id="template_name" value="{$name|str_form_value}" /></td>
						</tr>
						<tr>
							<td>{$STR_ADMIN_SUBJECT}</td>
							<td><input name="form_subject" style="width:90%" type="text" class="form-control" id="template_subject" value="{$subject|str_form_value}" /></td>
						</tr>
						<tr id="show_tag_list">
							<td colspan="2">{$show_tag_list}</td>
						</tr>
						<tr>
							<td colspan="2" id="mission_statement_tag">{$mission_statement_tag}</td>
						</tr>
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
								<input type="radio" name="form_lang" id="template_lang" value="{$l.lng|str_form_value}"{if $l.issel} checked="checked"{/if} /> {$l.lng}
							{/foreach}
							</td>
						</tr>
						<tr>
							<td colspan="2"><br /><center><input name="form_submit_update" type="submit" value="{$STR_ADMIN_EMAIL_TEMPLATES_UPDATE_TEMPLATE|str_form_value}" class="btn btn-primary" /></center></td>
						</tr>
					</table>
		</div>
		{if !empty($params.emailLinksExplanations)}
		<div class="col-md-6">{$emailLinksExplanations}</div>
		{/if}
	</div>
</form>
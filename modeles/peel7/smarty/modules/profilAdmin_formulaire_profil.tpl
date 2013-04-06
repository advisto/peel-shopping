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
// $Id: profilAdmin_formulaire_profil.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}<form method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_MODULE_PROFIL_ADMIN_TITLE}</td>
		</tr>
		<tr>
			<td colspan="2">{$STR_MODULE_PROFIL_ADMIN_EXPLAIN}</td>
		</tr>
{foreach $langs as $l}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_LANGUAGES_SECTION_HEADER} {$l.lng|upper}</td></tr>
		<tr>
			<td>{$STR_ADMIN_NAME} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td width="540"><input type="text" name="name_{$l.lng}" style="width:100%" value="{$l.name|str_form_value}" /></td>
		</tr>
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_DESCRIPTION} {$l.lng|upper} ({$STR_MODULE_PROFIL_ADMIN_DESCRIPTION_EXPLAIN} {$l.name})</td>
		</tr>
		<tr>
			<td class="left" colspan="2">
				<textarea id="description_document_{$l.lng}" name="description_document_{$l.lng}">{$l.description_document}</textarea>
			</td>
		</tr>
	{if $l.document}
		<tr>
			<td class="label">{$STR_ADMIN_FILE} {$l.lng|upper} (zip, pdf ou image){$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				{$STR_ADMIN_FILE_NAME} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}: {$l.document}<br /><a href="{$l.document_href|escape:'html'}" target="_blank">{$STR_MODULE_PROFIL_ADMIN_UPLOAD_DOCUMENT}</a> - <a href="{$l.document_delete_href|escape:'html'}"><img src="{$document_delete_icon_src|escape:'html'}" width="16" height="16" alt="" />{$STR_ADMIN_DELETE_THIS_FILE}</a><br />
				<br />
				<input type="hidden" name="document_{$l.lng}" value="{$l.document|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center">{$l.this_image_html}</td>
		</tr>
	{else}
		<tr>
			<td class="label">{$STR_ADMIN_FILE} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:250px" name="document_{$l.lng}" type="file" value="" /></td>
		</tr>
	{/if}
{/foreach}
		<tr>
			<td>{$STR_MODULE_PROFIL_ADMIN_ABBREVIATE}{$STR_BEFORE_TWO_POINTS}:</td>
			{if $mode == "insere"}
			<td><input maxlength="15" type="text" name="priv" size="15" value="{$priv|str_form_value}" /></td>
			{else}
			<td>{$priv}<input type="hidden" name="priv" value="{$priv|str_form_value}" /></td>
			{/if}
		</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="bouton" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
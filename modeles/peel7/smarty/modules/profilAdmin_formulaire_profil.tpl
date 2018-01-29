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
// $Id: profilAdmin_formulaire_profil.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_MODULE_PROFIL_ADMIN_TITLE}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_MODULE_PROFIL_ADMIN_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td class="left">{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}: </td>
			<td>
				<select class="form-control" name="site_id">
					{$site_id_select_options}
				</select>
			</td>
		</tr>
{foreach $langs as $l}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_LANGUAGES_SECTION_HEADER} - {$lang_names[$l.lng]|upper}</h2></td></tr>
		<tr>
			<td>{$STR_ADMIN_NAME} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td style="width:540px"><input type="text" class="form-control" name="name_{$l.lng}" style="width:100%" value="{$l.name|str_form_value}" /></td>
		</tr>
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_DESCRIPTION} {$l.lng|upper} ({$STR_MODULE_PROFIL_ADMIN_DESCRIPTION_EXPLAIN} {$l.name})</td>
		</tr>
		<tr>
			<td class="left" colspan="2">
				<textarea class="form-control" id="description_document_{$l.lng}" name="description_document_{$l.lng}">{$l.description_document}</textarea>
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_FILE} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
			{if isset($l.document)}
				{include file="uploaded_file.tpl" f=$l.document STR_DELETE=$STR_DELETE_THIS_FILE}
			{else}
				<input name="document_{$l.lng}" type="file" value="" />
			{/if}
			</td>
		</tr>
{/foreach}
		<tr>
			<td>{$STR_MODULE_PROFIL_ADMIN_ABBREVIATE}{$STR_BEFORE_TWO_POINTS}:</td>
			{if $mode == "insere"}
			<td><input type="text" class="form-control" name="priv" value="{$priv|str_form_value}" /></td>
			{else}
			<td>{$priv}<input type="hidden" name="priv" value="{$priv|str_form_value}" /></td>
			{/if}
		</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="btn btn-primary" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
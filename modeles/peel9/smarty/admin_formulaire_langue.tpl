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
// $Id: admin_formulaire_langue.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_LANGUES_ADD_OR_MODIFY_LANGUAGE}</div>
<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="full_width">
		{foreach $langs as $l}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_LANGUAGES_SECTION_HEADER} - {$lang_names[$l.lng]|upper}</h2></td></tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_NAME} {$l.lng|upper}:</td>
			<td style="width:50%"><input type="text" class="form-control" name="nom_{$l.lng}" value="{$l.nom|str_form_value}" /></td>
		</tr>
		{/foreach}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</h2></td></tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_LANGUES_FORMAT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{if $is_modif}
				<input type="hidden" name="lang" value="{$lang|str_form_value}" />{$lang|str_form_value}
			{else}
				<input type="text" class="form-control" name="lang" style="width:50px" maxlength="2" value="{$lang|str_form_value}" />
				<div class="alert alert-info"><p>{$STR_ADMIN_LANGUES_CODE_ISO_EXPLAIN}</p></div>
			{/if}
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_LANGUES_FLAG_PATH}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width: 100%" name="flag" type="text" class="form-control" value="{$flag|str_form_value}" /></td>
		</tr>
		<tr>
			<td class="title_label">{$STR_STATUS}</td>
			<td>
				<input type="radio" name="etat" value="1"{if $etat == '1'} checked="checked"{/if} /> {$STR_YES}&nbsp;
				<input type="radio" name="etat" value="0"{if $etat == '0' OR empty($etat)} checked="checked"{/if} /> {$STR_NO}&nbsp;
				<input type="radio" name="etat" value="-1"{if $etat == '-1'} checked="checked"{/if} /> {$STR_ADMINISTRATION}
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_POSITION}</td>
			<td><input type="number" class="form-control" name="position" style="width:150px" value="{$position|str_form_value}" /></td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_LANGUES_URL_REWRITING}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width: 100%" name="url_rewriting" type="text" class="form-control" value="{$url_rewriting|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" {if $site_id_select_multiple} name="site_id[]" multiple="multiple" size="5"{else} name="site_id"{/if}>
					{$site_id_select_options}
				</select>
			</td>
		</tr>
		<tr><td colspan="2" class="center"><p><input class="btn btn-primary" type="submit" value="{$titre_bouton|str_form_value}" /></p></td></tr>
	</table>
</form>
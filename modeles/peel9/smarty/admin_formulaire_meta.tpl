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
// $Id: admin_formulaire_meta.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="full_width" style="padding:6px;">
		<tr>
			<td class="entete">{$STR_ADMIN_META_PAGE_TITLE}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="bloc"><h2>{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</h2></td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_TECHNICAL_CODE}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td><input type="text" class="form-control" name="technical_code" size="70" value="{$technical_code|str_form_value}" placeholder="http://.... {$STR_OR} $GLOBALS['page_name']" /></td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td>
				<select class="form-control" {if $site_id_select_multiple} name="site_id[]" multiple="multiple" size="5"{else} name="site_id"{/if}>
					{$site_id_select_options}
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		{foreach $langs as $l}
		<tr>
			<td class="bloc"><h2>{$STR_ADMIN_LANGUAGES_SECTION_HEADER} - {$lang_names[$l.lng]|upper}</h2></td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_META_TITLE} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr >
			<td style="padding:6px;"><input type="text" class="form-control" name="meta_titre_{$l.lng}" size="70" value="{$l.meta_titre|str_form_value}" /></td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_META_KEYWORDS} {$l.lng|upper} ({$STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN}){$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td><textarea class="form-control" name="meta_key_{$l.lng}" style="width:100%" rows="5" cols="54">{$l.meta_key|nl2br_if_needed|strip_tags}</textarea></td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_META_DESCRIPTION} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td><textarea class="form-control" name="meta_desc_{$l.lng}" style="width:100%" rows="10" cols="54">{$l.meta_desc|nl2br_if_needed|strip_tags}</textarea></td>
		</tr>
		{/foreach}
		<tr>
			<td class="center"><p><input class="btn btn-primary" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
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
// $Id: tagcloudAdmin_formulaire_recherche.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="tagcloudAdmin_formulaire_recherche" cellpadding="5">
		<tr>
			<td class="entete" colspan="2">{$titre}</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_TAGCLOUD_ADMIN_TAG_NAME}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" name="tag_name" style="width:100%" maxlength="20" value="{$tag_name|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_MODULE_TAGCLOUD_ADMIN_SEARCHES_COUNT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" name="nbsearch" style="width:100%" maxlength="20" value="{$nbsearch|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_LANGUAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" name="lang">
				{foreach $options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
				{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}: </td>
			<td>
				<select class="form-control" name="site_id">
					{$site_id_select_options}
				</select>
			</td>
		</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="btn btn-primary" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
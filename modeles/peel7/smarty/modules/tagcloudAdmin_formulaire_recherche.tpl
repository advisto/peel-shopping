{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: tagcloudAdmin_formulaire_recherche.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<form method="post" action="{$action|escape:'html'}">
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="tagcloudAdmin_formulaire_recherche" cellpadding="5">
		<tr>
			<td class="entete" colspan="2">{$titre}</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_TAGCLOUD_ADMIN_TAG_NAME}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="tag_name" style="width:100%" maxlength="20" value="{$tag_name|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_MODULE_TAGCLOUD_ADMIN_SEARCHES_COUNT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="nbsearch" style="width:100%" maxlength="20" value="{$nbsearch|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_LANGUAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select name="lang">
				{foreach $options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
				{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="bouton" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
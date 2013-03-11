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
// $Id: tagcloudAdmin_liste_recherche.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<table class="full_width">
	<tr>
		<td class="entete" colspan="4">{$STR_MODULE_TAGCLOUD_ADMIN_LIST_TITLE}</td>
	</tr>
	<tr>
		<td colspan="4"><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_MODULE_TAGCLOUD_ADMIN_ADD_SEARCH}</a></td>
	</tr>
	{if isset($results)}
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_MODULE_TAGCLOUD_ADMIN_TAG_NAME}</td>
		<td class="menu">{$STR_ADMIN_LANGUAGE}</td>
		<td class="menu">{$STR_MODULE_TAGCLOUD_ADMIN_SEARCH_COUNT}</td>
	</tr>
	{foreach $results as $res}
		{$res.tr_rollover}
		<td class="center">
			<a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_DELETE|str_form_value} {$res.tag_name}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
			<a title="{$STR_MODULE_TAGCLOUD_ADMIN_MODIFY_THIS_TAG}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a>
		</td>
		<td><a title="{$STR_MODULE_TAGCLOUD_ADMIN_MODIFY_THIS_TAG}" href="{$res.edit_href|escape:'html'}">{$res.tag_name}</a></td>
		<td class="center"><b>{$res.lang}</b></td>
		<td class="center"><b>{$res.nbsearch}</b></td>
	</tr>
	{/foreach}
	{else}
	<tr><td><b>{$STR_MODULE_TAGCLOUD_ADMIN_NOTHING_FOUND}</b></td></tr>
	{/if}
</table>
<p>{$links_multipage}</p>	
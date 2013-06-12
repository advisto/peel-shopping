{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: bannerAdmin_liste.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete" colspan="11">{$STR_MODULE_BANNER_ADMIN_LIST_TITLE}</td>
	</tr>
	<tr>
		<td colspan="11"><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_MODULE_BANNER_ADMIN_CREATE}</a></td>
	</tr>
	{if isset($results)}
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_MODULE_BANNER_ADMIN_PLACE}</td>
		<td class="menu">{$STR_ADMIN_POSITION}</td>
		<td class="menu">{$STR_ADMIN_NAME}</td>
		<td class="menu">{$STR_ADMIN_IMAGE}</td>
		<td class="menu">{$STR_ADMIN_BEGIN_DATE}</td>
		<td class="menu">{$STR_ADMIN_END_DATE}</td>
		<td class="menu">{$STR_MODULE_BANNER_ADMIN_HIT}</td>
		<td class="menu">{$STR_MODULE_BANNER_ADMIN_VIEWED}</td>
		<td class="menu">{$STR_ADMIN_LANGUAGE}</td>
		<td class="menu">{$STR_STATUS}</td>
	</tr>
	{foreach $results as $res}
	{$res.tr_rollover}
		<td class="center"><a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_DELETE|str_form_value} {$res.description}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
			<a title="{$STR_MODULE_BANNER_ADMIN_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a>
		</td>
		<td class="center" class="position">{$res.position}</td>
		<td class="center">{$res.rang}</td>
		<td class="center">{$res.description}</td>
		<td class="center">
		{if isset($res.swf)}
			{$res.swf}
		{elseif isset($res.src)}
			<img src="{$res.src|escape:'html'}" style="max-width:250px; max-height:60px" />
		{/if}
		</td>
		<td class="center">{$res.date_debut}</td>
		<td class="center">{$res.date_fin}</td>
		<td class="center">{$res.hit}</td>
		<td class="center">{$res.vue}</td>
		<td class="center">{$res.lang}</td>
		<td class="center"><img class="change_status" src="{$res.modif_etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
	</tr>
	{/foreach}
	{else}
	<tr><td colspan="11"><p class="global_error">{$STR_MODULE_BANNER_ADMIN_NOTHING_FOUND}</p></td></tr>
	{/if}
	<tr><td colspan="11" align="center">{$links_multipage}</td></tr>
</table>
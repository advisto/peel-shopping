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
// $Id: devisesAdmin_liste.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete" colspan="6">{$STR_MODULE_DEVISES_ADMIN_LIST_TITLE}</td>
	</tr>
	<tr>
		<td colspan="6"><p><a href="{$ajout_href|escape:'html'}">{$STR_MODULE_DEVISES_ADMIN_CREATE}</a> - <a href="{$update_rates_href|escape:'html'}">{$STR_MODULE_DEVISES_ADMIN_CREATE_EXPLAIN}</a></p></td>
	</tr>
	{if !empty($results)}
		<tr>
			<td class="menu">{$STR_ADMIN_ACTION}</td>
			<td class="menu">{$STR_DEVISE}</td>
			<td class="menu">{$STR_ADMIN_SYMBOL}</td>
			<td class="menu">{$STR_ADMIN_CONVERSION}</td>
			<td class="menu">{$STR_ADMIN_CODE}</td>
			<td class="menu">{$STR_STATUS}</td>
		</tr>
		{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center"><a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_DELETE|str_form_value} {$res.devise}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a> &nbsp; <a title="{$STR_MODULE_DEVISES_ADMIN_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
			<td class="center"><a title="{$STR_MODULE_DEVISES_ADMIN_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.devise|html_entity_decode_if_needed}</a></td>
			<td class="center">{$res.symbole|html_entity_decode_if_needed}</td>
			<td class="center">{$res.conversion}</td>
			<td class="center">{$res.code}</td>
			<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
		</tr>
		{/foreach}
	{else}
	<tr><td><b>{$STR_MODULE_DEVISES_ADMIN_NOTHING_FOUND}</b></td></tr>
	{/if}
</table><br />
<a href="{$modif_href|escape:'html'}">{$STR_MODULE_DEVISES_ADMIN_DEFAULT_CURRENCY}</a><br />
{$STR_MODULE_DEVISES_ADMIN_DEFAULT_CURRENCY_EXPLAIN}
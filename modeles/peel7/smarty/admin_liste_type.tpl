{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_type.tpl 35064 2013-02-08 14:16:40Z gboussin $
*}<table class="main_table">
	<thead>
	<tr>
			<td class="entete" colspan="4">{$STR_ADMIN_TYPES_TITLE}</td>
	</tr>
	<tr>
			<td colspan="4"><div class="global_help">{$STR_ADMIN_TYPES_EXPLAIN}</div></td>
	</tr>
	<tr>
			<td colspan="4"><p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_ADMIN_TYPES_CREATE}</a></p></td>
	</tr>
	{if isset($results)}
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_SHIPPING_TYPE}</td>
		<td class="menu">{$STR_ADMIN_POSITION}</td>
		<td class="menu" width="100">{$STR_STATUS}</td>
	</tr>
	</thead>
	<tbody class="sortable">
	{foreach $results as $res}
	{$res.tr_rollover}
			<td class="center"><a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a> &nbsp; <a title="{$STR_ADMIN_TYPES_UPDATE}" href="{$res.modif_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
			<td style="padding-left:10px"><a title="{$STR_ADMIN_TYPES_UPDATE|str_form_value}" href="{$res.modif_href|escape:'html'}">{$res.nom}</a></td>
			<td class="center position">{$res.position}</td>
			<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
	</tr>
	{/foreach}
	{else}
	</thead>
	<tbody class="sortable">
		<tr><td colspan="4"><b>{$STR_ADMIN_TYPES_NOTHING_FOUND}</b></td></tr>
	{/if}
	</tbody>
</table>
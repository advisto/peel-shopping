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
// $Id: admin_liste_couleur.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<table class="main_table">
	<thead>
		<tr>
			<td class="entete" colspan="3">{$STR_ADMIN_COULEURS_COLORS_TITLE}</td>
		</tr>
		<tr>
			<td colspan="3"><div class="global_help">{$STR_ADMIN_COULEURS_LIST_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td colspan="3"><p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_ADMIN_COULEURS_ADD_COLOR_BUTTON}</a></p></td>
		</tr>
{if isset($results)}
		<tr>
			<td class="menu">{$STR_ADMIN_ACTION}</td>
			<td class="menu">{$STR_ADMIN_NAME}</td>
			<td class="menu">{$STR_ADMIN_POSITION}</td>
		</tr>
	</thead>
	<tbody class="sortable">
		{foreach $results as $res}
		{$res.tr_rollover}
			<td><a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a></td>
			<td><a title="{$STR_ADMIN_COULEURS_MODIFY_COLOR|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.nom}</a></td>
			<td class="center position">{$res.position}</td>
		</tr>
		{/foreach}
{else}
	</thead>
	<tbody>
		<tr><td colspan="3"><b>{$STR_ADMIN_COULEURS_NO_COLOR_FOUND}</b></td></tr>
{/if}
	</tbody>
</table>
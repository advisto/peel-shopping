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
// $Id: admin_liste_taille.tpl 35064 2013-02-08 14:16:40Z gboussin $
*}<table class="main_table">
	<thead>
		<tr>
			<td class="entete" colspan="5">{$STR_ADMIN_TAILLES_TITRE}</td>
		</tr>
		<tr>
			<td colspan="5"><div class="global_help">{$STR_ADMIN_TAILLES_LIST_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td colspan="5"><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_ADMIN_TAILLES_CREATE}</a></td>
		</tr>
{if isset($results)}
		<tr>
			<td class="menu">{$STR_ADMIN_ACTION}</td>
			<td class="menu">{$STR_SIZE}</td>
			<td class="menu">{$STR_PRICE}</td>
			<td class="menu">{$STR_ADMIN_RESELLER_PRICE}</td>
			<td class="menu">{$STR_ADMIN_POSITION}</td>
		</tr>
	</thead>
	<tbody class="sortable">
		{foreach $results as $res}
		{$res.tr_rollover}
			<td><a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" alt="{$STR_DELETE|str_form_value} {$res.nom}" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a></td>
			<td class="center"><a title="{$STR_ADMIN_TAILLES_UPDATE|str_form_value}" href="{$res.modif_href|escape:'html'}">{$res.nom}</a></td>
			<td class="center">{$res.prix}</td>
			<td class="center">{$res.prix_revendeur}</td>
			<td class="center position">{$res.position}</td>
		</tr>
		{/foreach}
{else}
	</thead>
	<tbody>
		<tr><td colspan="5"><b>{$STR_ADMIN_TAILLES_NOTHING_FOUND}</b></td></tr>
{/if}
	</tbody>
</table>
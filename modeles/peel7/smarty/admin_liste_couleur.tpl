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
// $Id: admin_liste_couleur.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}
<div class="entete">{$STR_ADMIN_COULEURS_COLORS_TITLE}</div>
<div class="alert alert-info">{$STR_ADMIN_COULEURS_LIST_EXPLAIN}</div>
<p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_ADMIN_COULEURS_ADD_COLOR_BUTTON}</a></p>
<div class="table-responsive">
	<table id="admin_liste_couleurs" class="table">
	{if isset($results)}
		<thead>
			<tr>
				<td class="menu">{$STR_ADMIN_ACTION}</td>
				<td class="menu">{$STR_ADMIN_NAME}</td>
				<td class="menu">{$STR_ADMIN_POSITION}</td>
				<td class="menu">{$STR_ADMIN_WEBSITE}</td>
			</tr>
		</thead>
		<tbody class="sortable">
			{foreach $results as $res}
			{$res.tr_rollover}
				<td><a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a></td>
				<td><a title="{$STR_ADMIN_COULEURS_MODIFY_COLOR|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.nom}</a></td>
				<td class="center position">{$res.position}</td>
				<td class="center">{$res.site_name}</td>
			</tr>
			{/foreach}
		</tbody>
	{else}
		<tbody>
			<tr><td colspan="4"><div class="alert alert-warning">{$STR_ADMIN_COULEURS_NO_COLOR_FOUND}</div></td></tr>
		</tbody>
	{/if}
	</table>
</div>
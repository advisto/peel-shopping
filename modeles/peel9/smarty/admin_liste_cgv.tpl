{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_cgv.tpl 64741 2020-10-21 13:48:51Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_CGV_TITLE}</div>
<table> 
	<tr>
		<td><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" /></td>
		<td><a href="{$add_href|escape:'html'}">{$STR_ADMIN_CGV_ADD}</a></td>
	</tr>
</table>
<div class="table-responsive">
	<table class="table">
	{if isset($results)}
		<thead>
			<tr>
				<td class="menu">{$STR_ADMIN_ACTION}</td>
				<td class="menu">{$STR_ADMIN_WEBSITE}</td>
				{if !empty($STR_ADMIN_SITE_COUNTRY)}
					<td class="menu">{$STR_ADMIN_SITE_COUNTRY}</td>
				{/if}
			</tr>
		</thead>
		<tbody class="sortable">
		{foreach $results as $res}
			{$res.tr_rollover}
				<td class="center"><a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a> &nbsp; <a title="{$STR_ADMIN_CGV_UPDATE}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
				<td class="center">{$res.site_name}</td>
				{if !empty($res.site_country)}
					<td class="center">{$res.site_country}</td>
				{/if}
			</tr>
		{/foreach}
		</tbody>
	{else}
		<tbody class="sortable">
			<tr><td colspan="6"><div class="alert alert-warning">{$STR_ADMIN_CGV_NO_FOUND}</div></td></tr>
		</tbody>
	{/if}
	</table>
</div>
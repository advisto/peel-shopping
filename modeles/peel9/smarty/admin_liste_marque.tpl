{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_marque.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
*}<table class="admin_liste_marque">
	<tr><td colspan="8" class="entete">{$STR_ADMIN_MARQUES_TITLE}</td></tr>
	<tr>
		<td colspan="8">
			<div style="margin-top:5px;">
				<p><a href="{$href|escape:'html'}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {$STR_ADMIN_MARQUES_ADD_BRAND}</a></p>
			</div>
		</td>
	</tr>
	{$links_header_row}
	{if isset($results)}
	{foreach $results as $res}
	{$res.tr_rollover}
		<td class="center"><a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
			<a title="{$STR_ADMIN_MARQUES_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
		<td class="center">{$res.id}</td>
		<td class="center">{if !empty($res.img_src)}<img src="{$res.img_src|escape:'html'}" alt="" />{/if}</td>
		<td class="center"><a title="{$STR_ADMIN_MARQUES_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.nom|html_entity_decode_if_needed}</a></td>
		<td class="center position">{$res.position}</td>
		<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
		<td class="center position">{$res.site_name}</td>
	{if !empty($STR_ADMIN_SITE_COUNTRY)}
		<td class="center position">{$res.site_country}</td>
	{/if}
	</tr>
	{/foreach}
	{else}
	<tr><td colspan="8"><div class="alert alert-warning">{$STR_ADMIN_MARQUES_NOTHING_FOUND}</div></td></tr>
	{/if}
	<tr><td class="center" colspan="8">{$links_multipage}</td></tr>
</table>
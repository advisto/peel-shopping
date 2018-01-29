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
// $Id: admin_liste_zone.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_ZONES_TITLE}</div>
<div><p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_ADMIN_ZONES_CREATE}</a></p></div>
{if isset($results)}
<div class="table-responsive">
	<table class="table">
		<tr>
			<td class="menu">{$STR_ADMIN_ACTION}</td>
			<td class="menu">{$STR_SHIPPING_ZONE}</td>
			<td class="menu">{$STR_VAT}</td>
			<td class="menu">{$STR_ADMIN_ZONES_FREE_DELIVERY}</td>
			<td class="menu">{$STR_ADMIN_POSITION}</td>
			<td class="menu">{$STR_ADMIN_WEBSITE}</td>
		</tr>
		{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center"><a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a> &nbsp; <a title="{$STR_ADMIN_ZONES_UPDATE}" href="{$res.modif_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
			<td style="padding-left:10px"><a title="{$STR_ADMIN_ZONES_UPDATE|str_form_value}" href="{$res.modif_href|escape:'html'}">{$res.nom}</a></td>
			<td class="center">{if $res.tva == 1}{$STR_YES}{else}{$STR_NO}{/if}</td>
			<td class="center">{if $res.on_franco == 1}{$STR_YES}{else}{$STR_NO}{/if}</td>
			<td class="center position">{$res.position}</td>
			<td class="center position">{$res.site_name}</td>
		</tr>
		{/foreach}
	</table>
</div>
{else}
<div class="alert alert-warning">{$STR_ADMIN_ZONES_NOTHING_FOUND}</div>
{/if}
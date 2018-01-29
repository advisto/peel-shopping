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
// $Id: admin_liste_tarif.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_TARIFS_TITLE}</div>
<div class="alert alert-info">
	<p>{$STR_ADMIN_TARIFS_CONFIG_STATUS} <b><a href="sites.php" class="alert-link">{if $mode_transport == 1}{$STR_ADMIN_ACTIVATED}{else}{$STR_ADMIN_DEACTIVATED} {"=>"|htmlspecialchars} {$STR_ADMIN_TARIFS_CONFIG_DEACTIVATED_COMMENT}{/if}</a></b></p>
	{$STR_ADMIN_TARIFS_SETUP_FREE_EXPLAIN}
</div>
<div><p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_ADMIN_TARIFS_CREATE}</a></p></div>
{if isset($results)}
<div class="table-responsive">
	<table class="table">
		<tr>
			<td class="menu">{$STR_ADMIN_ACTION}</td>
			<td class="menu">{$STR_SHIPPING_ZONE}</td>
			<td class="menu">{$STR_SHIPPING_TYPE}</td>
			<td class="menu">{$STR_ADMIN_TARIFS_MINIMAL_WEIGHT_SHORT}</td>
			<td class="menu">{$STR_ADMIN_TARIFS_MAXIMAL_WEIGHT_SHORT}</td>
			<td class="menu">{$STR_ADMIN_TARIFS_MINIMAL_TOTAL_SHORT} ({$site_symbole} {$STR_TTC})</td>
			<td class="menu">{$STR_ADMIN_TARIFS_MAXIMAL_TOTAL_SHORT} ({$site_symbole} {$STR_TTC})</td>
			<td class="menu">{$STR_ADMIN_TARIFS_TARIFS} ({$site_symbole} {$STR_TTC})</td>
			<td class="menu">{$STR_ADMIN_WEBSITE}</td>
		</tr>
		{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center"><a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.poidsmax}{$STR_ADMIN_GRAMS_SHORT} - {$res.tarif} {$site_symbole} {$STR_TTC}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" width="16" height="16" alt="" /></a> <a title="{$STR_ADMIN_TARIFS_UPDATE}" href="{$res.modif_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
			<td class="center">{$res.zone_name}</td>
			<td class="center"><a title="{$STR_ADMIN_TARIFS_UPDATE|str_form_value}" href="{$res.modif_href|escape:'html'}">{$res.delivery_type_name}</a></td>
			<td class="center">{$res.poidsmin}</td>
			<td class="center">{$res.poidsmax}</td>
			<td class="center">{$res.totalmin}</td>
			<td class="center">{$res.totalmax}</td>
			<td class="center">{$res.tarif} {$site_symbole}</td>
			<td class="center">{$res.site_name}</td>
		</tr>
		{/foreach}
	</table>
</div>
{else}
<div class="alert alert-warning">{$STR_ADMIN_TARIFS_NOTHING_FOUND}</div>
{/if}
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
// $Id: admin_liste_tarif.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete" colspan="8">{$STR_ADMIN_TARIFS_TITLE}</td>
	</tr>
	<tr>
		<td colspan="8"><p>{$STR_ADMIN_TARIFS_CONFIG_STATUS} <b><a href="sites.php">{if $mode_transport == 1}{$STR_ADMIN_ACTIVATED}{else}{$STR_ADMIN_DEACTIVATED} {"=>"|htmlspecialchars} {$STR_ADMIN_TARIFS_CONFIG_DEACTIVATED_COMMENT}{/if}</a></b></p>
			<p>{$STR_ADMIN_TARIFS_SETUP_FREE_EXPLAIN}</p>
		</td>
	</tr>
	<tr>
		<td colspan="6"><p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_ADMIN_TARIFS_CREATE}</a></p></td>
	</tr>
	{if isset($results)}
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_SHIPPING_ZONE}</td>
		<td class="menu">{$STR_SHIPPING_TYPE}</td>
		<td class="menu">{$STR_ADMIN_TARIFS_MINIMAL_WEIGHT_SHORT}</td>
		<td class="menu">{$STR_ADMIN_TARIFS_MAXIMAL_WEIGHT_SHORT}</td>
		<td class="menu">{$STR_ADMIN_TARIFS_MINIMAL_TOTAL_SHORT} ({$site_symbole} {$STR_TTC})</td>
		<td class="menu">{$STR_ADMIN_TARIFS_MAXIMAL_TOTAL_SHORT} ({$site_symbole} {$STR_TTC})</td>
		<td class="menu">{$STR_ADMIN_TARIFS_TARIFS} ({$site_symbole} {$STR_TTC})</td>
	</tr>
	{foreach $results as $res}
	{$res.tr_rollover}
		<td class="center"><a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_DELETE|str_form_value} {$res.poidsmax}{$STR_ADMIN_GRAMS_SHORT} - {$res.tarif} {$site_symbole} {$STR_TTC}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" width="16" height="16" alt="" /></a> <a title="{$STR_ADMIN_TARIFS_UPDATE}" href="{$res.modif_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
		<td class="center">{$res.zone_name}</td>
		<td class="center"><a title="{$STR_ADMIN_TARIFS_UPDATE|str_form_value}" href="{$res.modif_href|escape:'html'}">{$res.delivery_type_name}</a></td>
		<td class="center">{$res.poidsmin}</td>
		<td class="center">{$res.poidsmax}</td>
		<td class="center">{$res.totalmin}</td>
		<td class="center">{$res.totalmax}</td>
		<td class="center">{$res.tarif} {$site_symbole}</td>
	</tr>
	{/foreach}
	{else}
	<tr><td><b>{$STR_ADMIN_TARIFS_NOTHING_FOUND}</b></td></tr>
	{/if}
</table>
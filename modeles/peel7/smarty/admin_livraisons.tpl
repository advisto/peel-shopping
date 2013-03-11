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
// $Id: admin_livraisons.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}{if isset($results)}
<p class="label center">{$period_text}</p>
<div class="global_help">{$STR_ADMIN_LIVRAISONS_EXPLAIN}</div>
<table cellpadding="2" class="main_table">
	<tr>
		<td class="center menu"><b>{$STR_DATE}</b></td>
		<td class="center menu"><b>{$STR_ORDER_NAME}</b></td>
		<td class="center menu"><b>{$STR_AMOUNT} {$ttc_ht}</b></td>
		<td class="center menu"><b>{$STR_FIRST_NAME}</b></td>
		<td class="center menu"><b>{$STR_LAST_NAME}</b></td>
		<td class="center menu"><b>{$STR_SHIP_ADDRESS}</b></td>
		<td class="center menu"><b>{$STR_ZIP}</b></td>
		<td class="center menu"><b>{$STR_TOWN}</b></td>
		<td class="center menu"><b>{$STR_TELEPHONE}</b></td>
		<td class="center menu"><b>{$STR_EMAIL}</b></td>
	</tr>
	{foreach $results as $res}
	{$res.tr_rollover}
		<td class="center">{$res.date}</td>
		<td class="center">{if $res.notcheckUserInfo}<img src="{$update_src|escape:'html'}" alt="update-on.png" />{/if} {$res.id} / <a href="{$res.commande_edit_href|escape:'html'}">{$STR_ADMIN_SEE}</a></td>
		<td class="center">{$res.prix}</td>
		<td class="center">{$res.prenom_bill}</td>
		<td class="center">{$res.nom_bill}</td>
		<td class="center">{$res.adresse_bill}</td>
		<td class="center">{$res.zip_bill}</td>
		<td class="center">{$res.ville_bill}</td>
		<td class="center">{$res.telephone_bill}</td>
		<td class="center"><a href="mailto:{$res.email}">{$res.email}</a> / <a href="{$res.util_edit_href|escape:'html'}">{$STR_ADMIN_SEE}</a></td>
	</tr>
	{/foreach}
	{if isset($export_encoding) AND isset($export_href)}
	<tr>
		<td colspan="10" class="label">
			<div style="float:right;" class="global_help">{$export_encoding_explain}</div>
		</td>
	</tr>
	<tr>
		<td colspan="10" class="label" align="right">
			<a href="{$export_href|escape:'html'}" class="label"><img src="{$excel_src|escape:'html'}" align="absmiddle" alt="" /> {$STR_ADMIN_LIVRAISONS_EXCEL_EXPORT}</a>
		</td>
	</tr>
	{/if}
</table>
<p class="label center"><font size="+1" color="green">{$STR_ADMIN_ASKED_STATUS}{$STR_BEFORE_TWO_POINTS}: {if isset($delivery_status)}{$delivery_status}{else}{$STR_ADMIN_ALL_ORDERS}{/if}</font></p>
{else}
<p class="label center">{$period_text}</p>
<div class="center">{$STR_ADMIN_LIVRAISONS_NO_ORDER_FOUND}</div>
{/if}
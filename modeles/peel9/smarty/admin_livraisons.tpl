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
// $Id: admin_livraisons.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{if isset($results)}
<p class="title_label center">{$period_text}</p>
<div class="alert alert-info">{$STR_ADMIN_LIVRAISONS_EXPLAIN}</div>
<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<td class="center menu">{$STR_DATE}</td>
				<td class="center menu">{$STR_ORDER_NAME}</td>
				<td class="center menu">{$STR_AMOUNT} {$ttc_ht}</td>
				<td class="center menu">{$STR_FIRST_NAME}</td>
				<td class="center menu">{$STR_LAST_NAME}</td>
				<td class="center menu">{$STR_SHIP_ADDRESS}</td>
				<td class="center menu">{$STR_ZIP}</td>
				<td class="center menu">{$STR_TOWN}</td>
				<td class="center menu">{$STR_TELEPHONE}</td>
				<td class="center menu">{$STR_EMAIL}</td>
			</tr>
		</thead>
		<tbody>
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
		</tbody>
	</table>
</div>
<p class="title_label center"><font size="+1" color="green">{$STR_ADMIN_ASKED_STATUS}{$STR_BEFORE_TWO_POINTS}: {if isset($delivery_status)}{$delivery_status}{else}{$STR_ADMIN_ALL_ORDERS}{/if}</font></p>
{if isset($export_encoding) && isset($export_href)}
<div class="title_label" align="right">
	<a href="{$export_href|escape:'html'}" class="title_label"><img src="{$excel_src|escape:'html'}" align="absmiddle" alt="" /> {$STR_ADMIN_LIVRAISONS_EXCEL_EXPORT}</a>
</div>
<div class="title_label">
	<div class="alert alert-info">{$export_encoding_explain}</div>
</div>
{/if}
{else}
<p class="title_label center">{$period_text}</p>
<div class="center">{$STR_ADMIN_LIVRAISONS_NO_ORDER_FOUND}</div>
{/if}
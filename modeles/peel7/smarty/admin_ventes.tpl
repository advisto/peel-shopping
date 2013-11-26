{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_ventes.tpl 38734 2013-11-15 19:47:31Z gboussin $
*}{if isset($results)}
<br /><div class="alert alert-info">{$STR_ADMIN_VENTES_FORM_EXPLAIN}</div><br />
<p class="title_label center">{$period_text}</p>
<div class="table-responsive">
<table class="table">
	<thead>
		<tr>
			<td class="center menu">{$STR_DATE}</td>
			<td class="center menu">{$STR_ORDER_NAME}</td>
			<td class="center menu">{$STR_STATUS}</td>
			<td class="center menu">{$STR_EMAIL}</td>
			<td class="center menu">{$STR_AMOUNT} {$STR_HT}</td>
			<td class="center menu">{$STR_VAT}</td>
			<td class="center menu">{$STR_AMOUNT} {$STR_TTC}</td>
			<td class="center menu">{$STR_ADMIN_INCLUDING_DELIVERY_COST}</td>
		</tr>
	</thead>
	<tbody>
		{foreach $results as $res}
		{$res.tr_rollover}
			<td>{$res.date}</td>
			<td class="center">{$res.id} / <a href="{$res.modif_href|escape:'html'}">Voir</a></td>
			<td class="center">{$res.statut_paiement}</td>
			<td class="center"><a href="mailto:{$res.email}">{$res.email}</a></td>
			<td class="center">{$res.montant_ht_prix} {$res.montant_ht_devise_commande}</td>
			<td class="center">{$res.total_tva_prix} {$res.total_tva_devise_commande}</td>
			<td class="center">{$res.montant_prix} {$res.montant_devise_commande}</td>
			<td class="center">{$res.cout_transport_prix} {$res.cout_transport_devise_commande} {$STR_TTC}</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="4" class="title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$STR_ADMIN_BILL_TOTALS}</td>
			<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalVenteHt_prix} {$STR_HT}</td>
			<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalTva_prix}</td>
			<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalVente_prix} {$STR_TTC}</td>
			<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalTransport_prix}</td>
		</tr>
		{foreach $vats as $v}
		<tr>
			<td colspan="6" class="title_label" style="border-top-width: 0px;">&nbsp;</td>
			<td class="title_label">{$STR_ADMIN_TOTAL_VAT} {if $v.rate == 'transport'}{$v.rate}{else}{$v.rate}%{/if}</td>
			<td class="center title_label">{$v.prix}</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="6" class="title_label" style="border-top-width: 0px;">&nbsp;</td>
			<td class="title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$STR_ADMIN_TOTAL_VAT}</td>
			<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalTva_prix}</td>
		</tr>
	</tbody>
</table>
</div>
{if $is_module_export_ventes_active}
	<div style="padding-bottom:15px">
		<a href="{$export_href|escape:'html'}" class="title_label"><img src="{$excel_src|escape:'html'}" align="absmiddle" alt="" />&nbsp;{$STR_ADMIN_VENTES_EXPORT_EXCEL}</a><br/>
		<a href="{$export_href_one_line_per_order|escape:'html'}" class="title_label"><img src="{$excel_src|escape:'html'}" align="absmiddle" alt="" />&nbsp;{$STR_ADMIN_VENTES_EXPORT_EXCEL_ONE_LINE_PER_ORDER}</a>
	</div>
{/if}
{if $only_delivered}
<p class="title_label center"><font size="+1" color="green">{$STR_MODULE_KEKOLI_ADMIN_ONLY_DELIVERED}</font></p>
{/if}
<p class="title_label center"><font size="+1" color="green">{$STR_ADMIN_ASKED_STATUS}{$STR_BEFORE_TWO_POINTS}: {if isset($payment_status_name)}{$payment_status_name}{else}{$STR_ADMIN_ALL_ORDERS}{/if}</font></p>
{else}
<p class="title_label center">{$period_text}</p>
<div class="alert alert-warning center">{$STR_ADMIN_VENTES_NO_ORDER_FOUND}</div>
{/if}
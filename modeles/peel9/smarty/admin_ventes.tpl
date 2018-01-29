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
// $Id: admin_ventes.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{if isset($results)}
<br /><div class="alert alert-info">{$STR_ADMIN_VENTES_FORM_EXPLAIN}</div><br />
<p class="title_label center">{$period_text}</p>
{if $is_module_export_ventes_active}
<form method="POST" action="{$form_action}">
{/if}
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					{if $is_module_export_ventes_active}<td class="center menu"></td>{/if}
					<td class="center menu">{$STR_DATE}</td>
					<td class="center menu">{$STR_ORDER_NAME}</td>
					<td class="center menu">{$STR_STATUS}</td>
					<td class="center menu">{$STR_EMAIL}</td>
					<td class="center menu">{$STR_AMOUNT} {$STR_HT}</td>
					<td class="center menu">{$STR_VAT}</td>
					<td class="center menu">{$STR_AMOUNT} {$STR_TTC}</td>
					<td class="center menu">{$STR_ADMIN_INCLUDING_DELIVERY_COST}</td>
					<td class="center menu">{$STR_PDF_AVOIR}</td>
					<td class="center menu">{$STR_NET}</td>
				</tr>
			</thead>
			<tbody>
				{foreach $results as $res}
				{$res.tr_rollover}
					{if $is_module_export_ventes_active}<td><input type="checkbox" name="order_id[]" value="{$res.id}"></td>{/if}
					<td>{$res.date}</td>
					<td class="center">{$res.id} / <a href="{$res.modif_href|escape:'html'}">Voir</a></td>
					<td class="center">{$res.statut_paiement}</td>
					<td class="center"><a href="mailto:{$res.email}">{$res.email}</a></td>
					<td class="center">{$res.montant_ht_prix} {$res.montant_ht_devise_commande}</td>
					<td class="center">{$res.total_tva_prix} {$res.total_tva_devise_commande}</td>
					<td class="center">{$res.montant_prix} {$res.montant_devise_commande}</td>
					<td class="center">{$res.cout_transport_prix} {$res.cout_transport_devise_commande} {$STR_TTC}</td>
					<td class="center">{$res.avoir} {$res.avoir_devise_commande}</td>
					<td class="center">{$res.netapayer} {$res.netapayer_devise_commande}</td>
				</tr>
				{/foreach}
				<tr>
					<td colspan="{if $is_module_export_ventes_active}5{else}4{/if}" class="title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$STR_ADMIN_BILL_TOTALS}</td>
					<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalVenteHt_prix} {$STR_HT}</td>
					<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalTva_prix}</td>
					<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalVente_prix} {$STR_TTC}</td>
					<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalTransport_prix}</td>
					<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$total_avoir}</td>
					<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalNet_a_payer} {$STR_TTC}</td>
				</tr>
				{foreach $vats as $v}
				<tr>
					<td colspan="{if $is_module_export_ventes_active}9{else}8{/if}" class="title_label" style="border-top-width: 0px;">&nbsp;</td>
					<td class="title_label">{$STR_ADMIN_TOTAL_VAT} {if $v.rate == 'transport'}{$v.rate}{else}{$v.rate}%{/if}</td>
					<td class="center title_label">{$v.prix}</td>
				</tr>
				{/foreach}
				<tr>
					<td colspan="{if $is_module_export_ventes_active}9{else}8{/if}" class="title_label" style="border-top-width: 0px;">&nbsp;</td>
					<td class="title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$STR_ADMIN_TOTAL_VAT}</td>
					<td class="center title_label" style="border-top:solid 1px #000000; padding-bottom:15px">{$totalTva_prix}</td>
				</tr>
			</tbody>
		</table>
	</div>
{if $is_module_export_ventes_active}
	<div style="padding-bottom:15px">
		<a href="{$export_href_chronopost|escape:'html'}" class="title_label"><img src="{$excel_src|escape:'html'}" align="absmiddle" alt="" />&nbsp;{$STR_ADMIN_VENTES_EXPORT_EXCEL_CHRONOPOST}</a><br />
		<input type="radio" name="mode" value="standard" />&nbsp;<img src="{$excel_src|escape:'html'}" align="absmiddle" alt="" /><span class="title_label">{$STR_ADMIN_VENTES_EXPORT_EXCEL}.</span>&nbsp;<a href="{$export_href|escape:'html'}">{$STR_ADMIN_VENTES_ALL_EXPORT_EXCEL}</a><br />
		<input type="radio" name="mode" value="one_line_per_order"/>&nbsp<img src="{$excel_src|escape:'html'}" align="absmiddle" alt="" />;<span class="title_label">{$STR_ADMIN_VENTES_EXPORT_EXCEL_ONE_LINE_PER_ORDER}.</span>&nbsp;<a href="{$export_href_one_line_per_order|escape:'html'}">{$STR_ADMIN_VENTES_ALL_EXPORT_EXCEL}</a><br />
		<input type="radio" name="mode" value="one_line_per_product" />&nbsp;<img src="{$excel_src|escape:'html'}" align="absmiddle" alt="" /><span class="title_label">{$STR_ADMIN_VENTES_EXPORT_EXCEL_ONE_LINE_PER_PRODUCT}.</span>&nbsp;<a href="{$export_href_one_line_per_product|escape:'html'}">{$STR_ADMIN_VENTES_ALL_EXPORT_EXCEL}</a>

	</div>
	<input type="submit" class="btn btn-primary" name="export_selected_order" value="{$STR_ADMIN_VENTES_EXPORT_SELECTED_ORDER}" />
</form>
{/if}
{if $only_delivered}
<p class="title_label center"><font size="+1" color="green">{$STR_MODULE_KEKOLI_ADMIN_ONLY_DELIVERED}</font></p>
{/if}
<p class="title_label center"><font size="+1" color="green">{$STR_ADMIN_ASKED_STATUS}{$STR_BEFORE_TWO_POINTS}: {if isset($payment_status_name)}{$payment_status_name}{else}{$STR_ADMIN_ALL_ORDERS}{/if}</font></p>
{else}
<p class="title_label center">{$period_text}</p>
<div class="alert alert-warning center">{$STR_ADMIN_VENTES_NO_ORDER_FOUND}</div>
{/if}
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
// $Id: liste_commandes.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<h1 property="name" class="liste_commandes">{$STR_ORDER_HISTORY}</h1>
{if isset($STR_NO_ORDER)}
<div><p>{$STR_NO_ORDER}</p></div>
{else}
<div class="table-responsive">
<table class="table caddie" aria-label="{$STR_TABLE_SUMMARY_ORDERS|str_form_value}">
	<caption></caption>
	<thead>
		<tr>
			<th class="center" scope="col" style="width:60px">&nbsp;</th>
			<th class="center" scope="col">{$STR_ORDER_NUMBER}</th>
			<th class="center" scope="col">{$STR_DATE}</th>
			<th class="center" scope="col">{$STR_ORDER_STATUT_PAIEMENT}</th>
			<th class="center" scope="col">{$STR_ORDER_STATUT_LIVRAISON}</th>
			<th class="center" scope="col">{if $display_prices_with_taxes_active}{$STR_AMOUNT} {$STR_TTC}{else}{$STR_AMOUNT} {$STR_HT}{/if}</th>
		</tr>
	</thead>
	<tbody>
	{foreach $orders as $o}
	<tr style="background-color: #{cycle values="F4F4F4,ffffff"}">
		<td class="center">
			<a href="{$o.href|escape:'html'}"><img src="{$o.info_src|escape:'html'}" width="21" height="21" alt="info" /></a>
		{if !empty($o.facture_href)}
			<br /><a onclick="return(window.open(this.href)?false:true);" href="{$o.facture_href|escape:'html'}" style="white-space: nowrap;"><img src="{$o.pdf_src|escape:'html'}" width="8" height="11" alt="" />&nbsp;{$STR_PDF_BILL}</a>
		{/if}
		</td>
		<td class="center">{$o.order_id}</td>
		<td class="center">{$o.date}</td>
		<td class="center">{if !$o.paid}<a href="{$o.href|escape:'html'}">{$o.payment_status_name}</a>{else}{$o.payment_status_name}{/if}</td>
		<td class="center">{$o.delivery_status_name}</td>
		<td class="center">{$o.prix}</td>
	</tr>
	{/foreach}
	</tbody>
</table>
</div>
<div class="center">{$multipage}</div>
{/if}
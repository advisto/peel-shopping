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
// $Id: exaprint.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}{if isset($results)}
<br />{$STR_ADMIN_VENTES_FORM_EXPLAIN}<br />
<p class="title_label center">{$period_text}</p>
<table class="main_table">
	<tr>
		<td class="center menu"><b>{$STR_DATE}</b></td>
		<td class="center menu"><b>{$STR_ORDER}</b></td>
		<td class="center menu"><b>{$LANG.STR_ORDER_STATUT_LIVRAISON}</b></td>
		<td class="center menu"><b>{$LANG.STR_EXAPRINT_ORDER_STATUT_CARRIER}</b></td>
		<td class="center menu"><b>{$STR_EMAIL}</b></td>
		<td class="center menu"><b>{$STR_AMOUNT} {$STR_HT}</b></td>
		<td class="center menu"><b>{$STR_VAT}</b></td>
		<td class="center menu"><b>{$STR_AMOUNT} {$STR_TTC}</b></td>
		<td class="center menu"><b>{$STR_ADMIN_INCLUDING_DELIVERY_COST}</b></td>
	</tr>
	{foreach $results as $res}
	{$res.tr_rollover}
		<td>{$res.date}</td>
		<td class="center">{$res.id} / <a href="{$res.modif_href|escape:'html'}">Voir</a></td>
		<td class="center">{$res.statut_livraison}</td>
		<td class="center">{$res.transporteur}</td>
		<td class="center"><a href="mailto:{$res.email}">{$res.email}</a></td>
		<td class="center">{$res.montant_ht_prix} {$res.montant_ht_devise_commande}</td>
		<td class="center">{$res.total_tva_prix} {$res.total_tva_devise_commande}</td>
		<td class="center">{$res.montant_prix} {$res.montant_devise_commande}</td>
		<td class="center">{$res.cout_transport_prix} {$res.cout_transport_devise_commande} {$STR_TTC}</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="5" class="title_label">{$STR_ADMIN_BILL_TOTALS}</td>
		<td class="center title_label">{$totalVenteHt_prix} {$STR_HT}</td>
		<td class="center title_label">{$totalTva_prix}</td>
		<td class="center title_label">{$totalVente_prix} {$STR_TTC}</td>
		<td class="center title_label">{$totalTransport_prix}</td>
	</tr>
	<tr>
		<td colspan="9" class="title_label">&nbsp;</td>
	</tr>
	{foreach $vats as $v}
	<tr>
		<td colspan="7" class="title_label">&nbsp;</td>
		<td class="title_label">{$STR_ADMIN_TOTAL_VAT} {if $v.rate == 'transport'}{$v.rate}{else}{$v.rate}%{/if}</td>
		<td class="center title_label">{$v.prix}</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="9" class="title_label">&nbsp;</td>
	</tr>
	<tr>
	{if $only_delivered}
	<p class="title_label center"><font size="+1" color="green">{$STR_MODULE_KEKOLI_ADMIN_ONLY_DELIVERED}</font></p>
	{/if}
	{if $is_module_export_ventes_active}
		<td colspan="5" class="title_label" align="left" style="padding-bottom:15px"><a href="{$export_href|escape:'html'}" class="title_label"><img src="{$excel_src|escape:'html'}" align="absmiddle" alt="" />&nbsp;{$LANG.STR_EXAPRINT_VENTES_EXPORT_EXCEL}</a></td>
		<td colspan="2" class="title_label">&nbsp;</td>
	{else}
		<td colspan="6" class="title_label">&nbsp;</td>
	{/if}
		<td class="title_label">{$STR_ADMIN_TOTAL_VAT}</td>
		<td class="center title_label" >{$totalTva_prix}</td>
	</tr>
</table>
<p class="title_label center"><font size="+1" color="green">{$LANG.STR_EXAPRINT_ASKED_DELIVERY}{$STR_BEFORE_TWO_POINTS}: {if isset($statut_livraison_name)}{$statut_livraison_name}{else}{$STR_ADMIN_ALL_ORDERS}{/if}</font></p>
<p class="title_label center"><font size="+1" color="green">{$LANG.STR_EXAPRINT_ASKED_CARRIER}{$STR_BEFORE_TWO_POINTS}: {if isset($statut_transporteur_name)}{$statut_transporteur_name}{else}{$STR_ADMIN_ALL_ORDERS}{/if}</font></p>
{else}
<p class="title_label center">{$period_text}</p>
<div class="center"><b>{$STR_ADMIN_VENTES_NO_ORDER_FOUND}</b></div>
{/if}
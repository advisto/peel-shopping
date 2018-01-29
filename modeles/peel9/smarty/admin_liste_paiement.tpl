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
// $Id: admin_liste_paiement.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_PAIEMENT_TITLE}</div>
<div class="alert alert-info">
	{$STR_ADMIN_PAIEMENT_EXPLAIN}
	<b>NB{$STR_BEFORE_TWO_POINTS}:</b> {$STR_ADMIN_PAIEMENT_TECHNICAL_CODE_EXPLAIN}
</div>
<table> 
	<tr>
		<td><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" /></td>
		<td><a href="{$add_href|escape:'html'}">{$STR_ADMIN_PAIEMENT_ADD_PAYMENT_MEAN}</a></td>
	</tr>
</table>
<div class="table-responsive">
	<table class="table">
	{if isset($results)}
		<thead>
			<tr>
				<td class="menu">{$STR_ADMIN_ACTION}</td>
				<td class="menu">{$STR_ADMIN_TECHNICAL_CODE}</td>
				<td class="menu">{$STR_ADMIN_PAIEMENT_PAYMENT_MEAN}</td>
				<td class="menu">{$STR_ADMIN_POSITION}</td>
				<td class="menu">{$STR_ADMIN_PAIEMENT_ORDER_OVERCOST}</td>
				<td class="menu">{$STR_STATUS}</td>
				<td class="menu">{$STR_ADMIN_WEBSITE}</td>
			</tr>
		</thead>
		<tbody class="sortable">
		{foreach $results as $res}
			{$res.tr_rollover}
				<td class="center"><a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a> &nbsp; <a title="{$STR_ADMIN_PAIEMENT_UPDATE}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
				<td class="center">{$res.technical_code}</td>
				<td style="padding-left:10px"><a title="{$STR_ADMIN_PAIEMENT_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.nom}</a>{$res.explain}</td>
				<td class="center position">{$res.position}</td>
				<td class="center">{$res.prix}</td>
				<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
				<td class="center">{$res.site_name}</td>
			</tr>
		{/foreach}
		</tbody>
	{else}
		<tbody class="sortable">
			<tr><td colspan="7"><div class="alert alert-warning">{$STR_ADMIN_PAIEMENT_NO_PAYMENT_MEAN_FOUND}</div></td></tr>
		</tbody>
	{/if}
	</table>
</div>
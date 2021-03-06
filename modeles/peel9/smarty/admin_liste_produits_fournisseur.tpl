{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_produits_fournisseur.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_PRODUITS_SUPPLIER_PRODUCTS} {$societe|html_entity_decode_if_needed}</div>
<div style="margin-top:5px;">
	<p><a href="{$add_href|escape:'html'}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {$STR_ADMIN_CATEGORIES_ADD_PRODUCT}</a></p>
</div>
{if isset($results)}
<div class="table-responsive">
	<table class="table">
		<tr>
			<th class="menu">{$STR_ADMIN_ACTION}</th>
			<th class="menu">{$STR_REFERENCE}</th>
			<th class="menu">{$STR_CATEGORY}</th>
			<th class="menu">{$STR_WEBSITE}</th>
			<th class="menu">{$STR_ADMIN_NAME}</th>
			<th class="menu">{$STR_PRICE} {$site_symbole} {$ttc_ht}</th>
			<th class="menu">{$STR_STATUS}</th>
			<th class="menu">{$STR_STOCK}</th>
			{if $is_gifts_module_active}
			<th class="menu">{$STR_GIFT_POINTS}</th>
			{/if}
			<th class="menu" align="center">{$STR_ADMIN_UPDATED_DATE}</th>
			<th class="menu">{$STR_ADMIN_PRODUITS_SUPPLIER}</th>
		</tr>
	{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center">
				<a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" class="title_label" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
				<a title="{$STR_MODIFY|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" alt="edit" /></a>
			</td>
			<td class="center">{$res.reference}</td>
			<td class="center">
			{foreach $res.cats as $c}
				{$c|html_entity_decode_if_needed}<br />
			{foreachelse}
				<span style="color:red">-</span><br />
			{/foreach}
			</td>
			<td class="center">
				{$site_name|html_entity_decode_if_needed}
			</td>
			<td class="center"><a class="title_label" title="{$STR_ADMIN_PRODUITS_UPDATE}" href="{$res.edit_href|escape:'html'}">{$res.nom|html_entity_decode_if_needed}</a></td>
			<td class="center">{$res.prix} {$ttc_ht} </td>
			<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" /></td>
			{if $is_stock_advanced_module_active}
			<td class="center">{if $res.on_stock == 1}<a title="{$STR_ADMIN_PRODUITS_MANAGE_STOCKS|str_form_value}" href="{$res.stock_href|escape:'html'}"><img src="{$res.stock_src|escape:'html'}" /></a>{else}"n.a"{/if}</td>
			{/if}
			{if $is_gifts_module_active}
			<td class="center">{$res.points} pts</td>
			{/if}
			<td class="center">{$res.date}</td>
			<td class="center">
			{if !empty($res.util)}
				<a href="{$res.util.href|escape:'html'}">{$res.util.societe|html_entity_decode_if_needed}</a><br />
			{else}
				<span style="color:red">-</span>
			{/if}
			</td>
		</tr>
	{/foreach}
	</table>
</div>
<div class="center">{$links_multipage}</div>{else}
<div class="alert alert-warning">{$STR_ADMIN_PRODUITS_NOTHING_FOUND}</div>
{/if}

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
// $Id: caddie_products_summary_table.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<table class="caddie" cellpadding="2"  summary="{$STR_TABLE_SUMMARY_CADDIE|str_form_value}">
	<tr>
		<th colspan="3" scope="col">{$STR_PRODUCT}</th>
		<th scope="col">{$STR_UNIT_PRICE} {$taxes_displayed}</th>
		<th scope="col">{$STR_OPTION_PRICE}</th>
		<th scope="col">{$STR_QUANTITY}</th>
		{if $is_conditionnement_module_active}<td class="center">{$STR_CONDITIONNEMENT}</td><td class="center">{$STR_CONDITIONNEMENT_QTY}</td>{/if}
		<th scope="col">{$STR_REMISE} {$taxes_displayed}</th>
		<th scope="col">{$STR_TOTAL_PRICE} {$taxes_displayed}</th>
	</tr>
	{foreach $products as $p}
	<tr>
		<td scope="row" class="lignecaddie_suppression">
			<a onclick="return confirm('{$STR_DELETE_PROD_CART|filtre_javascript:true:true:true}');" href="{$p.delete_href|escape:'html'}">
				<img src="{$suppression_src|escape:'html'}" alt="{$STR_DELETE_PROD_CART|str_form_value}" />
			</a>
		</td>
		<td class="lignecaddie_produit_image">
			<a href="{$p.urlprod_with_cid}"><img src="{$p.src|escape:'html'}" alt="" /></a>
		</td>
		<td class="lignecaddie_produit_details">
			{if $with_form_fields}
			<input type="hidden" name="id[{$p.numero_ligne}]" value="{$p.id|str_form_value}" />
			<input type="hidden" name="listcadeaux_owner[{$p.numero_ligne}]" value="{$p.listcadeaux_owner|str_form_value}" />
			<input type="hidden" name="option[{$p.numero_ligne}]" value="{$p.option|str_form_value}" />
			{if $is_attributes_module_active}
			<input type="hidden" name="id_attribut[{$p.numero_ligne}]" value="{$p.id_attribut|str_form_value}" />
			{/if}
			{/if}
			{if isset($p.listcadeaux_owner_name)}
			<span class="offered_by">{$STR_FOR_GIFT} {$p.listcadeaux_owner_name}</span><br />
			{/if}
			<a href="{$p.urlprod_with_cid}">{$p.name}</a>
			{if isset($p.delivery_stock)}
			<br />{$STR_DELIVERY_STOCK}{$STR_BEFORE_TWO_POINTS}: {$p.delivery_stock}<br />
			{/if}
			{if $is_attributes_module_active && !empty($p.configuration_attributs_description)}
			<br />{$p.configuration_attributs_description}
			{/if}
			{if isset($p.color)}
			<br />{$STR_COLOR}{$STR_BEFORE_TWO_POINTS}: {$p.color.name} <input type="hidden" name="couleurId[{$p.numero_ligne}]" value="{$p.color.id|str_form_value}" />
			{else}
			<input type="hidden" name="couleurId[{$p.numero_ligne}]" value="0" />
			{/if}
			{if isset($p.size)}
			<br />{$STR_SIZE}{$STR_BEFORE_TWO_POINTS}: {$p.size.name} <input type="hidden" name="tailleId[{$p.numero_ligne}]" value="{$p.size.id|str_form_value}" />
			{else}
			<input type="hidden" name="tailleId[{$p.numero_ligne}]" value="0" />
			{/if}
			{if !empty($p.email_check)}
			<br />{$STR_EMAIL_FRIEND}{$STR_BEFORE_TWO_POINTS}: {$p.email_check}<input type="hidden" name="email_check[{$p.numero_ligne}]" value="{$p.email_check|str_form_value}" />
			{else}
			<input type="hidden" value="" name="email_check[{$p.numero_ligne}]" />
			{/if}
			{if isset($p.vacances)}
			<div class="vacances_available_caddie">
			{$STR_HOLIDAY_AVAILABLE_CADDIE} {$p.vacances.nbjours} {$STR_DAYS}<span>({$p.vacances.date})</span>
			</div>
			{/if}
		</td>
		<td class="lignecaddie_prix_unitaire" align="center">
			{if isset($p.prix_promo)}
				<del>{$p.prix}</del><br />{$p.prix_promo}
			{else}
				{$p.prix}
			{/if}
			{if isset($p.prix_ecotaxe)}
			<br /><em>{$STR_ECOTAXE}{$STR_BEFORE_TWO_POINTS}: {$p.prix_ecotaxe}</em>
			{/if}
		</td>
		<td class="lignecaddie_prix" align="center">
			{if isset($p.option_prix)}
				{if isset($p.option_prix_remise)}
				<del>{$p.option_prix_remise}</del><br />
				{/if}
				{$p.option_prix}
			{else}
				-
			{/if}
		</td>
		<td class="lignecaddie_quantite" align="center">
			{if $with_form_fields && is_array($p.quantite)}
				<input type="text" size="3" style="width:23px" name="quantite[{$p.numero_ligne}]" value="{$p.quantite.value|str_form_value}" {if isset($p.quantite.message)} onchange="if(this.value>{$p.quantite.stock_commandable}) {ldelim}this.value='{$p.quantite.stock_commandable}'; alert('{$p.quantite.message|filtre_javascript:true:true:true}');{rdelim}"{/if} />
				<input type="submit" value="" name="" class="bouton_ok" />
			{else}
				{$p.quantite}
			{/if}
		</td>
		{if $is_conditionnement_module_active}<td class="lignecaddie_prix" align="center">{$STR_CONDITIONNEMENT}</td><td class="lignecaddie_prix" align="center">{$STR_CONDITIONNEMENT_QTY}</td>{/if}
		<td class="lignecaddie_prix" align="center">- {if isset($p.remise)}{$p.remise}{/if}</td>
		<td class="lignecaddie_prix" align="center">{$p.total_prix}</td>
	</tr>
	{/foreach}
</table>
{if $with_totals_summary}
<div id="step2caddie">
	{if isset($tarif_paiement)}
	<p>
		<label>{$STR_FRAIS_GESTION}{$STR_BEFORE_TWO_POINTS}:</label>
		{$tarif_paiement}
	</p>
	{/if}
	{if isset($total_ecotaxe)}
	<p>
		<label>{$STR_ECOTAXE} {$taxes_displayed}{$STR_BEFORE_TWO_POINTS}:</label>
		{$total_ecotaxe}
	</p>
	{/if}
	{if isset($total_remise)}
	<p>
		<label>{$STR_REMISE} {$STR_INCLUDED} {$taxes_displayed}{$STR_BEFORE_TWO_POINTS}:</label>
		{$total_remise}
	</p>
	{if isset($code_promo)}
	<p class="italic">
		<label>{$STR_WITH_PROMO_CODE} {$code_promo.value}{$STR_BEFORE_TWO_POINTS}:</label>
		{$code_promo.total} {if $code_promo.cat_name}{$STR_ON_CATEGORY} {$code_promo.cat_name}{/if}
	</p>
	{/if}
	{/if}
	{if isset($sool)}
	<p>
		<label>{$STR_SMALL_ORDER_OVERCOST_TEXT} ({$STR_OFFERED} {$STR_FROM} {$sool.limit_prix} {$STR_TTC}) {$taxes_displayed}{$STR_BEFORE_TWO_POINTS}:</label>
		{$sool.prix}
	</p>
	{/if}
	{if isset($transport)}
	<p>
		<label>{$transport.shipping_text} {$taxes_displayed}{$STR_BEFORE_TWO_POINTS}:</label> {$transport.prix}
	</p>
	{/if}
	{if isset($micro)}
	<p>
		<label>{$STR_TOTAL_HT}{$STR_BEFORE_TWO_POINTS}:</label>
		{$micro.prix_th}
	</p>
	<p>
		<label>{$STR_VAT}{$STR_BEFORE_TWO_POINTS}:</label>
		{$micro.prix_tva}
	</p>
	{else}
	<p>{$STR_NO_VAT_APPLIABLE}</p>
	{/if}
	{if isset($prix_avoir)}
	<p>
		<label>{$STR_AVOIR}{$STR_BEFORE_TWO_POINTS}:</label>
		- {$prix_avoir}
	</p>
	{/if}
	<p class="caddie_net_to_pay">
		<label>{$net_txt} {$STR_TTC}{$STR_BEFORE_TWO_POINTS}:</label>
		{$prix_total}
	</p>
	{if $total_points > 0}
	<p>
		<label>{$STR_ORDER_POINT}{$STR_BEFORE_TWO_POINTS}:</label>
		{$total_points}&nbsp;{$STR_GIFT_POINTS}
	</p>
	{/if}
</div>
{/if}
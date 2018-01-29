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
// $Id: critere_stock.tpl 54211 2017-07-04 13:08:18Z sdelaporte $
*}{if $is_form}
<form class="entryform form-inline" role="form" enctype="multipart/form-data" method="post" action="{$action|escape:'html'}" id="{$form_id}">
{/if}
	<div class="affiche_critere_stock well {$update_class}">
{if $is_form}
	{if isset($affiche_attributs_form_part)}{$affiche_attributs_form_part}{/if}
	{if isset($affiche_etat_stock)}{$affiche_etat_stock}{/if}
	{if isset($stock_remain_all)}
		<p class="title_label">{$STR_STOCK_ATTRIBUTS}{$STR_BEFORE_TWO_POINTS}: {$stock_remain_all}</p>
	{/if}
	{if isset($product_soon_available)}
		<p class="title_label">{$product_soon_available}</p>
	{/if}
{/if}
{if isset($delai_stock)}
		<p class="title_label">{$STR_DELIVERY_STOCK}{$STR_BEFORE_TWO_POINTS}: {$delai_stock}</p>
{/if}
{if isset($etat_stock)}
		{$etat_stock}
{else}
	{if isset($formulaire_alerte)}
		{$formulaire_alerte}
	{/if}
{/if}
{if $is_form}
	{if !$condensed_display_mode}
		{if $is_color}
		<table class="color_option">
			<tr>
				<td class="attribut-color">
					<label>{$STR_COLOR}{$STR_BEFORE_TWO_POINTS}:</label>
				</td>
				<td>
					<select class="form-control" name="couleur" id="{$id_select_color}" onchange="{$color_on_change_action}">
						<option value="0">{$STR_CHOOSE_COLOR}</option>
					{foreach $colors as $c}
						<option value="{$c.id|str_form_value}"{if $c.issel} selected="selected"{/if}{if !$c.isavailable} disabled="disabled"{/if}>{$c.name}{$c.suffix}{if !$c.isavailable} - {$STR_NO_AVAILABLE}{/if}</option>
					{/foreach}
					</select>
				</td>
			</tr>
		</table>
		{/if}
		{if $is_sizes}
		<table class="size_option">
			<tr>
				<td class="attribut-cell">
					<label>{$STR_SIZE}{$STR_BEFORE_TWO_POINTS}:</label>
				</td>
				<td>
					<select class="form-control" id="{$id_select_size}" name="taille" onchange="update_product_price{$save_suffix_id}();bootbox_sizes_options(this);">
						<option value="0">{$STR_CHOOSE_SIZE}</option>
						{foreach $sizes_options as $so}
							<option {if !empty($so.bootbox_sizes_options)}{$so.bootbox_sizes_options}{/if} value="{$so.id|intval}"{if $so.issel} selected="selected"{/if}{if !$so.isavailable} disabled="disabled"{/if} {if $so.found_stock_info >0}style="font-weight:bold;"{/if}>
							{$so.name}{$so.suffix}
							</option>
						{/foreach}
					</select>
				</td>
			</tr>
		</table>
		{/if}
	{elseif !empty($stock_options)}
		<p class="retour">
			<select class="form-control" name="critere" id="critere" onchange="document.location='{$urlprod_with_cid}'+getElementById('critere').value.split('|')[0]+'&amp;liste='+getElementById('critere').value;">
				{foreach $stock_options as $so}
					{if $so.isavailable}
					<option value="{$so.value}"{if $so.issel} selected="selected"{/if}>{$so.label}</option>
					{else}
					<option value="null">{$so.couleur_nom} &nbsp; {$so.taille}{$STR_BEFORE_TWO_POINTS}: {$STR_NO_AVAILABLE}</option>
					{/if}
				{/foreach}
			</select>
		</p>
	{/if}
{/if}
		{$display_javascript_for_price_update}
		<div property="offers" typeof="Offer" class="product_affiche_prix">{$product_affiche_prix}</div>
{if $is_form}
	{if empty($on_estimate)}
		<table>
		{if !empty($display_order_minimum)}
			<tr>
				<td><span class="product_affiche_order_min">{$STR_ORDER_MIN} {$qte_value}</span></td>
			<tr>
		{/if}
			<tr>
				<td style="vertical-align:bottom">
					<div class="product_quantity">
		{if !empty($qte_hidden)}
						<input type="hidden" name="qte" value="{$qte_value|str_form_value}" />
		{else}
						<label>{$STR_QUANTITY}{$STR_BEFORE_TWO_POINTS}: </label><input type="number" class="form-control" name="qte" value="{$qte_value|str_form_value}" style="width: 100px" />
		{/if}
					</div>
					<div class="product_order">
		{if isset($giftlist)}
						<input type="hidden" name="listcadeaux_owner" value="{$giftlist.listcadeaux_owner|str_form_value}" />
						<input type="hidden" name="id" value="{$giftlist.id|intval}" />
						<input type="hidden" id="list_mode" name="mode" value="" />
						{$giftlist.form}<br /><br />
		{/if}
		{if isset($save_cart_id)}
								<input type="hidden" id="save_cart_id" name="save_cart_id" value="{$save_cart_id}" />
		{/if}
						<script><!--//--><![CDATA[//><!--
						function verif_form{$save_suffix_id}(check_color, check_size) {ldelim}
							if (check_color == 1 && document.getElementById("couleur{$save_suffix_id}").options[document.getElementById("couleur{$save_suffix_id}").selectedIndex].value == 0) {ldelim}
								bootbox.alert("{$STR_NONE_COLOR_SELECTED|filtre_javascript:true:false:true:false}");
								return false;
							{rdelim} else if (check_size == 1 && document.getElementById("taille{$save_suffix_id}").options[document.getElementById("taille{$save_suffix_id}").selectedIndex].value == 0) {ldelim}
								bootbox.alert("{$STR_NONE_SIZE_SELECTED|filtre_javascript:true:false:true:false}");
								return false;
							{rdelim} else {ldelim}
								return true;
							{rdelim}
						{rdelim}
						//--><!]]></script>
						<input type="submit" class="btn btn-primary submit-once-only" onclick="{if !empty($popup_stock_alert)}alert('{$popup_stock_alert}');return false;{/if}if (verif_form{$save_suffix_id}({$color_array_result}, {$sizes_infos_array_result}) == true) {ldelim}{$anim_prod_var}{rdelim} else {ldelim} return false; {rdelim}" value="{$STR_ADD_CART|str_form_value}" />
					</div>
				</td>
			</tr>
		</table>
	{/if}
{/if}
	</div>
{if $is_form}
	{if isset($conditionnement)}
	<input name="conditionnement" type="hidden" value="{$conditionnement|str_form_value}" />
	{/if}
</form>
{/if}
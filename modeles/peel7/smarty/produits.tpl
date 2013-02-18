{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produits.tpl 35112 2013-02-11 11:09:34Z gboussin $
*}{if $is_associated_product}
	<div class="associated_product">
{/if}
{if isset($titre_mode)}
	{if $titre_mode == 'associated'}
		<h3 class="other_product_buy_title">{$titre}</h3>
	{elseif $titre_mode == 'home'}
		<h2 class="home_title">{$titre}</h2>
	{elseif $titre_mode == 'category'}
		<table class="product_title"><tr><td><h2>{$titre}</h2></td><td class="right" style="padding-right: 10px;">{$filtre}</td></tr></table>
	{elseif $titre_mode == 'default'}
		<h2>{$titre}</h2>
	{/if}
{/if}
{if $no_results}{if isset($no_results_msg)}<p>{$no_results_msg}</p>{/if}
{else}
	<table class="produits {if $allow_order}allow_order{/if}">
	{foreach $products as $prod}
		{if $prods_line_mode}
		<tr>
			<td{if isset($titre_mode) && $titre_mode == 'associated'} property="isRelatedTo"{/if} typeof="product">
		{else}
			{if $prod.is_row}
		<tr>
			{/if}
			<td{if isset($titre_mode) && $titre_mode == 'associated'} property="isRelatedTo"{/if} typeof="product" class="produit_col{if $prod.display_border} bordure{/if}">
		{/if}
		{if isset($prod.save_cart)}
				<div class="save_cart_individual_action">
					<img src="{$prod.save_cart.src|escape:'html'}" width="8" height="11" alt="" />
					<a href="{$prod.save_cart.href|escape:'html'}" onclick="return confirm('{$prod.save_cart.confirm_msg|filtre_javascript:true:true:true}');" title="{$prod.save_cart.title}">{$prod.save_cart.label}</a>
				</div>
		{/if}
		{if $prods_line_mode}
				<table>
			{if isset($prod.flash)}
					<tr>
						<td colspan="6" class="col_flash">
							{$prod.flash}
						</td>
					</tr>
			{/if}
					<tr>
						<td class="col_image" style="width:10%;">
							<a title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}"><img property="image" src="{$prod.image.src|escape:'html'}"{if $prod.image.width} width="{$prod.image.width}"{/if}{if $prod.image.height} height="{$prod.image.height}"{/if} alt="{$prod.image.alt}" /></a>
						</td>
						<td style="width:45%;">
							<a property="url" href="{$prod.href|escape:'html'}" title="{$prod.name|str_form_value}"><span property="name">{$prod.name}</span></a>
						</td>
						<td style="text-align:center; width:12%;">
			{if isset($prod.on_estimate)}
							{$prod.on_estimate}
			{/if}
						</td>
						<td style="text-align:center; width:10%;">
			{if isset($prod.stock_state)}
								{$prod.stock_state}
			{/if}
						</td>
						<td class="col_zoom" style="width:10%;">
			{if isset($prod.image.zoom)}
							<a href="{$prod.image.zoom.href|escape:'html'}" {if $prod.image.zoom.is_lightbox}class="lightbox"{else}onclick="return(window.open(this.href)?false:true);"{/if} title="{$prod.name|str_form_value}">{$prod.image.zoom.label}</a>
			{/if} <br />
							<p class="col_detail"><a title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}">{$details_text}</a></p>
						</td>
			{if isset($prod.check_critere_stock)}
						<td class="fc_add_to_cart">
						<!-- Ajout au panier -->
							{$prod.check_critere_stock}
						</td>
			{/if}
					</tr>
			{if isset($prod.admin)}
					<tr>
						<td colspan="6"><a href="{$prod.admin.href|escape:'html'}" class="label">{$prod.admin.label}</a></td>
					</tr>
			{/if}
				</table><hr />
		{else}
				<table class="{$cartridge_product_css_class}">
			{if isset($prod.flash)}
					<tr>
						<td colspan="2" class="fc_flash">{$prod.flash}</td>
					</tr>
			{/if}
					<tr>
						<td colspan="2" class="fc_titre_produit">
							<a property="url" title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}"><span property="name">{$prod.name}</span></a>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="fc_image center middle" style="width:{$small_width}px; height:{$small_height}px;">
							<a title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}"><img property="image" src="{$prod.image.src|escape:'html'}"{if $prod.image.width} width="{$prod.image.width}"{/if}{if $prod.image.height} height="{$prod.image.height}"{/if} alt="{$prod.image.alt}" /></a>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="fc_prix">
							{if isset($prod.on_estimate)}
							{$prod.on_estimate}
							{/if}
						</td>
					</tr>
					<tr>
						<td class="fc_zoom">
							{if isset($prod.image.zoom)}
							<a href="{$prod.image.zoom.href|escape:'html'}" {if $prod.image.zoom.is_lightbox}class="lightbox"{else}onclick="return(window.open(this.href)?false:true);"{/if} title="{$prod.name|str_form_value}">{$prod.image.zoom.label}</a>
							{/if}
						</td>
						<td class="fc_detail"><a class="plus_detail" href="{$prod.href|escape:'html'}" title="{$prod.name|str_form_value}">{$details_text}</a></td>
					</tr>
			{if isset($prod.check_critere_stock)}
					<tr>
						<td colspan="2" class="fc_add_to_cart">
							<!-- Ajout au panier -->
							{$prod.check_critere_stock}
						</td>
					</tr>
			{/if}
				</table>
		{/if}
			</td>
		{if isset($prod.empty_cells)}
			{for $var=1 to $prod.empty_cells}
			<td></td>
			{/for}
		</tr>
		{/if}
	{/foreach}
		<tr>
			<td class="center" colspan="{$n_columns}">{$multipage}</td>
		</tr>
	</table>
{/if}
{if $is_associated_product}
</div>
{/if}

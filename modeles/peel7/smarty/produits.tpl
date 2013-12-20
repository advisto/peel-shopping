{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produits.tpl 39392 2013-12-20 11:08:42Z gboussin $
*}{if $is_associated_product}
	<div class="associated_product">
{/if}
{if isset($titre_mode)}
	{if $titre_mode == 'associated'}
		<h{$title_level} class="other_product_buy_title">{$titre}</h{$title_level}>
	{elseif $titre_mode == 'home'}
		<h{$title_level} class="home_title">{$titre}</h{$title_level}>
	{elseif $titre_mode == 'category'}
		<h{$title_level} class="products_title">{$titre}</h{$title_level}><div class="pull-right">{$filtre}</div><div class="clearfix"></div>
	{elseif $titre_mode == 'default'}
		<h{$title_level} class="products_title">{$titre}</h{$title_level}>
	{/if}
{/if}
{if $no_results}{if isset($no_results_msg)}<p>{$no_results_msg}</p>{/if}
{else}
	<div class="produits row {if $allow_order}allow_order{/if}">
	{foreach $products as $prod}
		{if $prods_line_mode}
		<div{if isset($titre_mode) && $titre_mode == 'associated'} property="isRelatedTo"{/if} typeof="product" class="{if $prod.display_border} bordure{/if} col-sm-12 center">
		{else}
		<div{if isset($titre_mode) && $titre_mode == 'associated'} property="isRelatedTo"{/if} typeof="product" class="produit_col{if $prod.display_border} bordure{/if} col-sm-{floor(12/$nb_col_sm)} col-md-{floor(12/$nb_col_md)} center">
		{/if}
		{if isset($prod.save_cart)}
				<div class="save_cart_individual_action">
					<img src="{$prod.save_cart.src|escape:'html'}" width="8" height="11" alt="" />
					<a href="{$prod.save_cart.href|escape:'html'}" data-confirm="{$prod.save_cart.confirm_msg|str_form_value}" title="{$prod.save_cart.title}">{$prod.save_cart.label}</a>
				</div>
		{/if}
		{if $prods_line_mode}
				<table class="line-item">
			{if isset($prod.flash)}
					<tr>
						<td colspan="6" class="col_flash">
							{$prod.flash}
						</td>
					</tr>
			{/if}
					<tr>
						<td class="col_image">
							<a title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}"><img property="image" src="{$prod.image.src|escape:'html'}"{if $prod.image.width} width="{$prod.image.width}"{/if}{if $prod.image.height} height="{$prod.image.height}"{/if} alt="{$prod.image.alt|str_form_value}" /></a>
						</td>
						<td class="col_product_description">
							<table>
								<tr>
									<td class="fc_titre_produit"><a property="url" href="{$prod.href|escape:'html'}" title="{$prod.name|str_form_value}"><span property="name">{$prod.name}</span></a></td>
								</tr>
								<tr>
									<td><p><a href="{$prod.href|escape:'html'}" class="col_description">{$prod.description}</a></p></td>
								</tr>
							</table>
						</td>
						<td style="text-align:center; width:22%;">
			{if isset($prod.on_estimate)}
							{$prod.on_estimate}
			{/if}
						</td>
						<td class="col_zoom" style="width:10%;">
			{if isset($prod.image.zoom)}
							<a href="{$prod.image.zoom.href|escape:'html'}" {if $prod.image.zoom.is_lightbox}class="lightbox" onclick="return false;"{else}onclick="return(window.open(this.href)?false:true);"{/if} title="{$prod.name|str_form_value}">{$prod.image.zoom.label}</a>
			{/if} <br />
							<p class="col_detail"><a title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}">{$details_text}</a></p>
			{if isset($prod.stock_state)}
							{$prod.stock_state}
			{/if}
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
						<td colspan="6"><a href="{$prod.admin.href|escape:'html'}" class="title_label">{$prod.admin.label}</a></td>
					</tr>
			{/if}
				</table>
		{else}
				<table class="{$cartridge_product_css_class}">
					<tr>
						<td class="fc_titre_produit">
							<a property="url" title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}"><span property="name">{$prod.name}</span></a>
						</td>
					</tr>
					<tr>
						<td class="fc_image center middle" style="width:{$small_width}px; height:{$small_height}px;">
							<span class="image_zoom">
								<a title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}"><img property="image" src="{$prod.image.src|escape:'html'}"{if $prod.image.width} width="{$prod.image.width}"{/if}{if $prod.image.height} height="{$prod.image.height}"{/if} alt="{$prod.image.alt|str_form_value}" /></a>
								{if isset($prod.image.zoom)}<span class="fc_zoom"><a href="{$prod.image.zoom.href|escape:'html'}" {if $prod.image.zoom.is_lightbox}class="lightbox" onclick="return false;"{else}onclick="return(window.open(this.href)?false:true);"{/if} title="{$prod.name|str_form_value}"><span class="glyphicon glyphicon-fullscreen"></span></a></span>{/if}
							</span>
						</td>
					</tr>
					<tr>
						<td>
							{if !empty($prod.description)}
							<div class="description_text"><a href="{$prod.href|escape:'html'}">{$prod.description}</a></div>
							{/if}
							{if isset($prod.flash)}
							<div class="alert alert-warning">{$prod.flash}</div>
							{/if}
							{if isset($prod.on_estimate)}<div class="fc_prix">{$prod.on_estimate}</div>{/if}
						</td>
					</tr>
			{if isset($prod.check_critere_stock)}
					<tr>
						<td class="fc_add_to_cart">
							<!-- Ajout au panier -->
							{$prod.check_critere_stock}
						</td>
					</tr>
			{/if}
				</table>
		{/if}
		</div>
		{if $prod.i%$nb_col_md==0}
		<div class="clearfix visible-md visible-lg"></div>
		{/if}
		{if $prod.i%$nb_col_sm==0}
		<div class="clearfix visible-sm"></div>
		{/if}
	{/foreach}
	</div>
	<div class="clearfix"></div>
	<div class="center">{$multipage}</div>
{/if}
{if $is_associated_product}
</div>
{/if}

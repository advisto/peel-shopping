{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produits.tpl 49918 2016-05-16 21:48:13Z sdelaporte $
*}{if $is_associated_product}
	<hr />
	<div class="associated_product list-group">
{/if}
{if isset($titre_mode) && !empty($titre)}
	{if $titre_mode == 'associated'}
		<h{$title_level} class="other_product_buy_title">{$titre}</h{$title_level}>
	{elseif $titre_mode == 'home'}
		<h{$title_level} class="home_title">{$titre}</h{$title_level}>
	{elseif $titre_mode == 'category'}
		<h{$title_level} class="products_title">{$titre}</h{$title_level}>
	{elseif $titre_mode == 'default'}
		<h{$title_level} class="products_title">{$titre}</h{$title_level}>
	{/if}
{/if}
{if !empty($filtre)}<div class="pull-right">{$filtre}</div><div class="clearfix"></div>{/if}
{if $no_results}{if isset($no_results_msg)}<p>{$no_results_msg}</p>{/if}
{else}
	<div class="produits row {if $allow_order}allow_order{/if}">
		{foreach $products as $prod}
			{if $prods_line_mode}
		<div class="col-sm-12">
			<div{if isset($titre_mode) && $titre_mode == 'associated'} property="isRelatedTo"{/if} typeof="product" class="center{if $prod.display_border} bordure{/if}{if $is_associated_product} list-group-item{/if}">
			{else}
		<div>
			<div{if isset($titre_mode) && $titre_mode == 'associated'} property="isRelatedTo"{/if} typeof="product" class="produit_col{if $prod.display_border} bordure{/if} col-sm-{floor(12/$nb_col_sm)} col-md-{floor(12/$nb_col_md)} center">
			{/if}
			{if isset($prod.save_cart)}
				<div class="save_cart_individual_action">
					<img src="{$prod.save_cart.src|escape:'html'}" width="8" height="11" alt="" />
					<a href="{$prod.save_cart.href|escape:'html'}" data-confirm="{$prod.save_cart.confirm_msg|str_form_value}" title="{$prod.save_cart.title}">{$prod.save_cart.label}</a>
				</div>
			{/if}
			{if $prods_line_mode}
				<div class="line-item">
				{if isset($prod.flash)}
					<div class="col_flash">
						{$prod.flash}
					</div>
				{/if}
				{if isset($prod.gallery_button)}
					<div class="col_gallery_button">
						{$prod.gallery_button}
					</div>
				{/if}
					<div class="row">
						<div class="col_image col-md-2">
							{if !empty($prod.image)}<a title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}"><img property="image" src="{$prod.image.src|escape:'html'}"{if $prod.image.width} width="{$prod.image.width}"{/if}{if $prod.image.height} height="{$prod.image.height}"{/if} alt="{$prod.image.alt|str_form_value}" /></a>{/if}
						</div>
						<div class="col_product_description col-md-{if isset($prod.check_critere_stock)}6{else}8{/if}">
							<div class="fc_titre_produit"><a property="url" href="{$prod.href|escape:'html'}" title="{$prod.name|str_form_value}"><span property="name">{$prod.name}</span></a></div>
							<div><p><a href="{$prod.href|escape:'html'}" class="col_description">{$prod.description}</a></p></div>
						</div>
						<div class="col_zoom col-md-2">
				{if isset($prod.image.zoom)}
					{if $prod.image.zoom.is_lightbox}
							<a href="{$prod.image.zoom.href|escape:'html'}" class="lightbox" onclick="return false;" title="{$prod.name|str_form_value}">{$prod.image.zoom.label}</a>
					{elseif $prod.image.zoom.file_type!='image'}
							<a href="{$prod.image.zoom.href|escape:'html'}" onclick="return(window.open(this.href)?false:true);" title="{$prod.name|str_form_value}">{$prod.image.zoom.label}</a>
					{/if}
				{/if}
							<br />
							<p class="col_detail"><a title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}">{$details_text}</a></p>
				{if isset($prod.stock_state)}
					{$prod.stock_state}
				{/if}
						</div>
					<div class="fc_add_to_cart col-md-2">
				{if isset($prod.check_critere_stock)}
							<!-- Ajout au panier -->
							{$prod.check_critere_stock}
				{else}
						<div class="fc_prix">{if !empty($prod.on_estimate)}{$prod.on_estimate}{else}<span class="prix">&nbsp;</span>{/if}</div>
				{/if}
					</div>
				</div>
				{if isset($prod.admin)}
					<div>
						<a href="{$prod.admin.href|escape:'html'}" class="title_label">{$prod.admin.label}</a>
					</div>
				{/if}
				{if isset($prod.modify_product_by_owner)}
					<div>
						<a href="{$prod.modify_product_by_owner.href|escape:'html'}" class="title_label">{$prod.modify_product_by_owner.label}</a>
					</div>
				{/if}
				</div>
			{else}
				<table class="full-width {$cartridge_product_css_class}">
					<tr>
						<td class="fc_titre_produit">
							<a property="url" title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}"><span property="name">{$prod.name}</span></a>
						</td>
					</tr>
					<tr>
						<td class="fc_image center middle" style="width:{$small_width}px; height:{$small_height}px;">
							<span class="image_zoom">
							{if !empty($prod.image)}
								<a title="{$prod.name|str_form_value}" href="{$prod.href|escape:'html'}"><img property="image" src="{$prod.image.src|escape:'html'}"{if $prod.image.width} width="{$prod.image.width}"{/if}{if $prod.image.height} height="{$prod.image.height}"{/if} alt="{$prod.image.alt|str_form_value}" /></a>
								{if isset($prod.image.zoom)}
									{if $prod.image.zoom.is_lightbox}
								<span class="fc_zoom"><a href="{$prod.image.zoom.href|escape:'html'}" class="lightbox" onclick="return false;" title="{$prod.name|str_form_value}"><span class="glyphicon glyphicon-fullscreen"></span></a></span>
									{elseif !empty($prod.image.zoom.file_type) && $prod.image.zoom.file_type!='image'}
								<span class="fc_zoom"><a href="{$prod.image.zoom.href|escape:'html'}" onclick="return(window.open(this.href)?false:true);" title="{$prod.name|str_form_value}"><span class="glyphicon glyphicon-fullscreen"></span></a></span>
									{/if}
								{/if}
							{/if}
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
							{if empty($prod.check_critere_stock)}<div class="fc_prix">{if !empty($prod.on_estimate)}{$prod.on_estimate}{else}<span class="prix">&nbsp;</span>{/if}</div>{/if}
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
				{if !empty($prod.product_list_html_zone)}
					<tr>
						<td>
							{$prod.product_list_html_zone}
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
		</div>
		{/foreach}
		{if !empty($total)}
		<div class="col-sm-12">
			<span class="prix">{$STR_TOTAL}{$STR_BEFORE_TWO_POINTS}: {$total}</span>
		</div>
		{/if}
	</div>
	<div class="clearfix"></div>
	<div class="center">{$multipage}</div>
{/if}
{if $is_associated_product}
</div>
{/if}

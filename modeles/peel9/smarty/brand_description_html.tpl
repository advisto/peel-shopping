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
// $Id: brand_description_html.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{if $is_error}
<h1 property="name" class="brand_description_html">{$error_header}</h1>
<p class="alert alert-danger">{$error_content}</p>
{else}
	{foreach $data as $item name=data}
		{if $item.display_brand}
			{if count($data)==1}
<h{if count($data)==1}1{else}2{/if} class="brand_description_html">{$item.nom|html_entity_decode_if_needed}</h{if count($data)==1}1{else}2{/if}>
				{if $item.admin_content}
<p class="center"><a href="{$item.admin_link.href|escape:'html'}" class="title_label">{$item.admin_link.name}</a></p>
				{/if}
<table class="full_width">
	<tr>
		<td class="left" style="padding:5px; width:{$item.small_width}px;">
				{if $item.has_image}
			{if !empty($item.image.href)}<a href="{$item.image.href|escape:'html'}">{/if}<img src="{$item.image.src|escape:'html'}" alt="" />{if !empty($item.image.href)}</a>{/if}
				{/if}
		</td>
		<td class="articles_count">
			{if !empty($item.href)}<a href="{$item.href|escape:'html'}">{/if}{$item.nb_produits_txt}{if !empty($item.href)}</a>{/if}
		</td>
	</tr>
				{if $item.description}
	<tr><td colspan="3" style="padding:5px;">{$item.description|html_entity_decode_if_needed}</td></tr>
				{/if}
</table>
			{else}
<div class="center {if count($data)==1}col-md-12{else}col-md-3 col-sm-4{/if}">
	<h{if count($data)==1}1{else}2{/if} class="brand_description_html">{if !empty($item.href)}<a href="{$item.href|escape:'html'}">{/if}{$item.nom|html_entity_decode_if_needed}{if !empty($item.href)}</a>{/if}</h{if count($data)==1}1{else}2{/if}>
				{if $item.admin_content}
	<p class="center"><a href="{$item.admin_link.href|escape:'html'}" class="title_label">{$item.admin_link.name}</a></p>
				{/if}
	<div style="min-height: 150px">
				{if $item.has_image}
		{if !empty($item.image.href)}<a href="{$item.image.href|escape:'html'}">{/if}<img src="{$item.image.src|escape:'html'}" alt="" />{if !empty($item.image.href)}</a>{/if}
				{/if}
	</div>
	<div class="articles_count">
		{if !empty($item.href)}<a href="{$item.href|escape:'html'}">{/if}{$item.nb_produits_txt}{if !empty($item.href)}</a>{/if}
	</div>
</div>
				{if $smarty.foreach.data.iteration%4==0}
<div class="clearfix visible-md visible-lg"></div>
				{/if}
				{if $smarty.foreach.data.iteration%3==0}
<div class="clearfix visible-sm"></div>
				{/if}
			{/if}
		{/if}
	{/foreach}
{/if}
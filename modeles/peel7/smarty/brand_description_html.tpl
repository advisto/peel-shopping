{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: brand_description_html.tpl 39162 2013-12-04 10:37:44Z gboussin $
*}{if $is_error}
<h1 class="brand_description_html">{$error_header}</h1>
<p class="alert alert-danger">{$error_content}</p>
{else}
	{foreach $data as $item}
<h{if count($data)==1}1{else}2{/if} class="brand_description_html">{$item.nom|html_entity_decode_if_needed}</h{if count($data)==1}1{else}2{/if}>
<table>
	<tr>
		<td class="top" style="width:50%; padding:10px;">
			{if $item.display_brand}
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
						<td class="left" style="padding:10px">
							{if !empty($item.href)}<a href="{$item.href|escape:'html'}">{/if}<span style="font-size:16px; font-weight:bold;">{$item.nom|html_entity_decode_if_needed}</span>{if !empty($item.href)}</a>{/if}
						</td>
						<td class="articles_count">
							{if !empty($item.href)}<a href="{$item.href|escape:'html'}">{/if}{$item.nb_produits_txt}{if !empty($item.href)}</a>{/if}
						</td>
					</tr>
				{if !empty($item.description)}
					<tr><td colspan="3" style="padding:5px;">{$item.description|html_entity_decode_if_needed}</td></tr>
				{/if}
				</table>
			{/if}
		</td>
	</tr>
</table>
	{/foreach}
{/if}
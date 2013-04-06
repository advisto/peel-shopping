{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: mini_caddie.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}<div id="fly_to_basket_destination"></div>
<table class="minicaddie">
	<tr>
		<td><a href="{$affichage_href|escape:'html'}"><img src="{$logo_src|escape:'html'}" alt="" /></a></td>
		<td><h2><a href="{$affichage_href|escape:'html'}">{$STR_CADDIE}</a></h2><p>{$count_products} {$products_txt}</p></td>
	</tr>
	{if $has_details}
	<tr>
		<td colspan="2">
			<table>
				{foreach $products as $item}
				<tr>
					<td class="product_name"><div>{$item.quantite} x <a href="{$item.href|escape:'html'}">{$item.name}{if isset($item.color)}<br />{$item.color.label}: {$item.color.value}{/if}{if isset($item.size)}<br />{$item.size.label}: {$item.size.value}{/if}</a></div></td>
					<td class="product_price"><div>{$item.price}</div></td>
				</tr>
				{/foreach}
				{if isset($transport)}
					<tr><td>{$transport.label}:</td><td class="right">{$transport.value}</td></tr>
				{/if}
				{if isset($total)}
					<tr><td>{$total.label}:</td><td class="right">{$total.value}</td></tr>
				{/if}
				<tr><td colspan="2" class="center"><a href="{$affichage_href|escape:'html'}" class="bouton">{$STR_DETAILS_ORDER}</a></td></tr>
			</table>
		</td>
	</tr>
	{/if}
</table>	
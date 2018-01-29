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
// $Id: prix.tpl 55709 2018-01-11 15:36:30Z sdelaporte $
*}<table class="{$table_css_class}">
{if $hide_price}
	<tr>
		<td class="middle">{$STR_PLEASE_LOGIN}</td>
	</tr>
{else}
	{if $display_old_price_inline}
		{if !empty($prix_ht_without_ecotax)}
	<tr>
		<td>
			<span class="prix">{$prix_ht_without_ecotax.prix}</span>
		</td>
	</tr> 
	<tr>
			{if isset($original_price)}
		<td class="middle"><del>{$original_price}</del></td>
			{/if}
		<td{if $display_old_price_inline && isset($original_price)} colspan="2"{/if}><span class="ecotaxe"><i> + {$prix_ht_without_ecotax.label}: {$prix_ht_without_ecotax.prix_ecotaxe}</i> =&nbsp;</span><span class="ecotaxe"{if !empty($item_id)} id="{$item_id}"{/if}>{if !empty($STR_FROM)}{$STR_FROM}{/if} {$final_price}{if !empty($conditionnement)}{$STR_CONDITIONING_TEXT}{/if}</span></td>
	</tr>
		{else}
	<tr>
			{if isset($original_price)}
		<td class="middle"><del>{$original_price}</del></td>
			{/if}
		<td>
			<span class="prix"{if !empty($item_id)} id="{$item_id}"{/if}>{if !empty($STR_FROM)}{$STR_FROM}{/if} {$final_price} {if !empty($conditionnement)}{$STR_CONDITIONING_TEXT}{/if}</span>
		</td>
	</tr>
		{/if}
	{else}
	<tr>
		<td>
			<span class="prix"{if !empty($item_id)} id="{$item_id}"{/if}>{if !empty($STR_FROM)}{$STR_FROM}{/if} {$final_price}{if !empty($conditionnement)}{$STR_CONDITIONING_TEXT}{/if}</span>
		</td>
	</tr>
		{if isset($original_price)}
	<tr>
		<td class="middle"><del>{$original_price}</del></td>
	</tr>
		{/if}
	{/if}
	
	
	
	
	
	
	{if !empty($ecotax)}
	<tr>
		<td{if $display_old_price_inline && isset($original_price)} colspan="2"{/if}><span class="ecotaxe"><i> {$ecotax.label}: {$ecotax.prix}</i></span></td>
	</tr>
	{/if} 
	{if isset($measurement)}
	<tr>
		<td{if $display_old_price_inline && isset($original_price)} colspan="2"{/if}><p>{$measurement.label} {$measurement.prix}</p></td>
	</tr>
	{/if}
{/if}
</table>
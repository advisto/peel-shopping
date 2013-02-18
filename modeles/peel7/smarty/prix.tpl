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
// $Id: prix.tpl 35120 2013-02-11 11:56:45Z gboussin $
*}<table class="{$table_css_class}">
{if $display_old_price_inline}
	<tr>
	{if isset($original_price)}
		<td class="middle"><del>{$original_price}</del></td>
	{/if}	
		<td>
			<span class="prix"{if !empty($item_id)} id="{$item_id}"{/if}>
				{$final_price}
			</span>
		</td>
	</tr>
{else}
	<tr>
		<td>
			<span class="prix"{if !empty($item_id)} id="{$item_id}"{/if}>
				{$final_price}
			</span>
		</td>
	</tr>
	{if isset($original_price)}
	<tr>
		<td class="middle"><del>{$original_price}</del></td>
	</tr>
	{/if}
{/if}
{if isset($ecotax)}
	<tr>
		<td{if $display_old_price_inline AND isset($original_price)} colspan="2"{/if}><span class="ecotaxe"><i> {$ecotax.label}: {$ecotax.prix}</i></span></td>
	</tr>
{/if}
{if isset($measurement)}
	<tr>
		<td{if $display_old_price_inline AND isset($original_price)} colspan="2"{/if}><p>{$measurement.label} {$measurement.prix}</p></td>
	</tr>
{/if}
</table>
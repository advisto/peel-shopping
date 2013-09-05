{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: subcategories_table.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<table class="sub_category">
{foreach $cats as $cat}
	{if $cat.row_start}
	<tr>
	{/if}
	{if isset($cat.href)}
		<td class="center" style="width:{$cat.width}%">
			{if isset($cat.src)}
				<a href="{$cat.href|escape:'html'}"><img src="{$cat.src|escape:'html'}" alt="{$cat.name|html_entity_decode_if_needed}" /></a>
				<br />
			{/if}
			<a href="{$cat.href|escape:'html'}">{$cat.name|html_entity_decode_if_needed}</a>
		</td>
	{/if}
	{if $cat.row_end}
		{for $var=1 to $cat.empty_cells}
		<td></td>
		{/for}
	</tr>
	{/if}
{/foreach}
</table>
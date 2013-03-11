{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: product_in_container_html.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<table class="full_expand_in_container center">
	<tr>
		<td colspan="2"><a class="module_product_title" href="{$href|escape:'html'}" title="{$name|str_form_value}">{$name}</a></td>
	</tr>
	{if isset($src)}
	<tr>
		<td colspan="2" style="height:150px; vertical-align:middle;"><a href="{$href|escape:'html'}" title="{$name|str_form_value}"><img src="{$src|escape:'html'}" alt="{$name|str_form_value}" class="product_image" /></a></td>
	</tr>
	{/if}
	<tr>
		<td><div class="fc_more_detail"><a href="{$href|escape:'html'}" title="{$name|str_form_value}">{$more_detail_label}</a></div></td>
		{if isset($on_estimate)}
			<td class="right" style="width:110px;">{$on_estimate}</td>
		{/if}
	</tr>
</table>
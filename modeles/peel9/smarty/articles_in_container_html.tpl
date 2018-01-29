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
// $Id: articles_in_container_html.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<table class="full_width center product_in_container">
	<tr>
		<td class="module_product_title"><a href="{$href|escape:'html'}" title="{$name|str_form_value}">{$name}</a></td>
	</tr>
	{if isset($src)}
	<tr>
		<td class="module_product_image"><a href="{$href|escape:'html'}" title="{$name|str_form_value}"><img src="{$src|escape:'html'}" alt="{$name|str_form_value}" class="product_image" /></a></td>
	</tr>
	{/if}
	<tr>
		<td><div><a href="{$href|escape:'html'}" class="btn btn-info btn-sm" title="{$name|str_form_value}">{$more_detail_label}</a></div></td>
	</tr>
</table>
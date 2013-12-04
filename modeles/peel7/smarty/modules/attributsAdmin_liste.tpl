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
// $Id: attributsAdmin_liste.tpl 39162 2013-12-04 10:37:44Z gboussin $
*}<table class="full_width">
	<tr><td colspan="4" class="entete">{$STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTE_OPTIONS_LIST} <strong>{$nom|html_entity_decode_if_needed}</strong></td></tr>
	<tr>
		<td colspan="4"><div class="alert alert-info">{$STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTE_OPTIONS_LIST_EXPLAIN}</div></td>
	</tr>
	<tr>
		<td colspan="4"><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_MODULE_ATTRIBUTS_ADMIN_CREATE_OPTION}</a></td>
	</tr>
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_LIST}</td>
		<td class="menu">{$STR_PRICE}</td>
		<td class="menu">{$STR_PHOTO}</td>
	</tr>
{if $num_results == 0}
	<tr><td colspan="4"><b>{$STR_MODULE_ATTRIBUTS_ADMIN_NO_OPTION_DEFINED}</b></td></tr>
{else}
	{foreach $results as $res}
		{$res.tr_rollover}
		<td class="center">
			<a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" href="{$res.drop_href|escape:'html'}"><img src="{$res.drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
		</td>
		<td class="center">
			<a title="{$STR_MODIFY|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.descriptif|html_entity_decode_if_needed}</a>
		</td>
		<td class="center">{$res.prix} {$STR_TTC}</td>
		<td class="center">{if !empty($res.img_src)}<img src="{$res.img_src|escape:'html'}" alt="" />{/if}</td>
		</tr>
	{/foreach}
{/if}
</table>
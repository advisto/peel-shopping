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
// $Id: admin_liste_categorie.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}{if $is_category_promotion_module_active}
	{$colspan = 8}
{else}
	{$colspan = 7}
{/if}
<table class="full_width" cellpadding="2">
	<tr><td colspan="{$colspan}" class="entete">{$STR_ADMIN_CATEGORIES_LIST_TITLE}</td></tr>
	<tr><td colspan="{$colspan}"><p><img src="{$add_src|escape:'html'}" alt="" /><a href="{$add_href|escape:'html'}">{$STR_ADMIN_CATEGORIES_CREATE}</a></p></td></tr>
	<tr>
		<td colspan="{$colspan}">
			<img src="{$cat_src|escape:'html'}" width="16" height="16" alt="" class="middle" /> {$STR_ADMIN_CATEGORIES_ADD_SUBCATEGORY}
		</td>
	</tr>
	<tr>
		<td colspan="{$colspan}">
			<img src="{$prod_src|escape:'html'}" width="16" height="16" alt="" class="middle" /> {$STR_ADMIN_CATEGORIES_ADD_PRODUCT}
		</td>
	</tr>
	<tr>
		<td colspan="{$colspan}">
			<img src="{$drop_src|escape:'html'}" width="16" height="16" alt="" class="middle" /> {$STR_ADMIN_CATEGORIES_DELETE_CATEGORY}
		</td>
	</tr>
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_ADMIN_ID}</td>
		<td class="menu">{$STR_ADMIN_IMAGE}</td>
		<td class="menu" align="left">{$STR_ADMIN_CATEGORIES}</td>
		<td class="menu" align="left">{$STR_WEBSITE}</td>
{if $is_category_promotion_module_active}
		<td class="menu">{$STR_PROMOTION}</td>
{/if}
		<td class="menu">{$STR_ADMIN_POSITION}</td>
		<td class="menu">{$STR_STATUS}</td>
	</tr>
	{$categorie_options}
</table>
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
// $Id: admin_liste_rubrique.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}<table class="main_table">
	<tr><td colspan="6" class="entete">{$STR_ADMIN_RUBRIQUES_LIST_TITLE}</td></tr>
	<tr>
		<td colspan="6">
			<p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" />
			<a href="{$ajout_href|escape:'html'}">{$STR_ADMIN_RUBRIQUES_ADD}</a></p>
		</td>
	</tr>
	<tr>
		<td colspan="6">
			<img src="{$rubrique_src|escape:'html'}" width="16" height="16" alt="" class="middle" /> {$STR_ADMIN_RUBRIQUES_ADD_SUBCATEGORY}
		</td>
	</tr>
	<tr>
		<td colspan="6">
			<img src="{$prod_cat_src|escape:'html'}" width="16" height="16" alt="" class="middle" /> {$STR_ADMIN_RUBRIQUES_ADD_ARTICLE}
		</td>
	</tr>
	<tr>
		<td colspan="6">
			<img src="{$drop_src|escape:'html'}" width="16" height="16" alt="" class="middle" /> {$STR_ADMIN_RUBRIQUES_DELETE_CATEGORY}
		</td>
	</tr>
	<tr>
		<td colspan="6">{$STR_ADMIN_RUBRIQUES_POSITION_EXPLAIN}</td>
	</tr>
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_ADMIN_IMAGE}</td>
		<td class="menu">{$STR_ADMIN_RUBRIQUE}</td>
		<td class="menu">{$STR_WEBSITE}</td>
		<td class="menu">{$STR_ADMIN_POSITION}</td>
		<td class="menu">{$STR_STATUS}</td>
	</tr>
	{$rubrique_options}
</table>
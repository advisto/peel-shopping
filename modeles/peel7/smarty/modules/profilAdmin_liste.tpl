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
// $Id: profilAdmin_liste.tpl 39162 2013-12-04 10:37:44Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete" colspan="3">{$STR_MODULE_PROFIL_ADMIN_TITLE}</td>
	</tr>
	<tr>
		<td colspan="3">
			<table>
				<tr>
					<td><br /><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" /></td>
					<td><a href="{$add_href|escape:'html'}">{$STR_MODULE_PROFIL_ADMIN_CREATE}</a></td>
				</tr>
			</table>
			<p class="alert alert-info">{$STR_MODULE_PROFIL_ADMIN_LIST_EXPLAIN}</p>
		</td>
	</tr>
	{if isset($results)}
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_ADMIN_PROFIL}</td>
		<td class="menu">{$STR_MODULE_PROFIL_ADMIN_ABBREVIATE}</td>
	</tr>
	{foreach $results as $res}
	{$res.tr_rollover}
		<td class="center"><a title="{$STR_MODULE_PROFIL_ADMIN_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
		<td class="center"><a title="{$STR_MODULE_PROFIL_ADMIN_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.name}</a></td>
		<td class="center">{$res.priv}</td>
	</tr>
	{/foreach}
	{else}
	<tr><td colspan="3"><div class="alert alert-warning">{$STR_MODULE_PROFIL_ADMIN_NOTHING_FOUND}</div></td></tr>
	{/if}
</table>
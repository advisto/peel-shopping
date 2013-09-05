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
// $Id: admin_liste_tva.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete">{$STR_ADMIN_TVA_TITLE}</td>
	</tr>
</table>
<div class="global_help">{$STR_ADMIN_TVA_FORM_EXPLAIN}</div>
<table class="main_table">
	<tr>
		<td colspan="2">
			<table>
				<tr>
					<td><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" /></td>
					<td><a href="{$add_href|escape:'html'}">{$STR_ADMIN_TVA_CREATE}</a></td>
				</tr>
			</table><br />
		</td>
	</tr>
	{if isset($results)}
	<tr>
		<td class="menu" style="width:80px;">{$STR_ADMIN_ACTION}</td>
		<td class="menu" style="width:200px;">{$STR_ADMIN_VAT_PERCENTAGE}</td>
	</tr>
	{foreach $results as $res}
	{$res.tr_rollover}
		<td class="center"><a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_ADMIN_TVA_DELETE}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a> &nbsp; <a title="{$STR_ADMIN_TVA_UPDATE}" href="{$res.modif_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
		<td class="center"><a title="{$STR_ADMIN_TVA_UPDATE|str_form_value}" href="{$res.modif_href|escape:'html'}">{$res.tva}</a> %</td>
	</tr>
	{/foreach}
	{else}
	<tr><td><b>{$STR_ADMIN_TVA_NOTHING_FOUND}</b></td></tr>
	{/if}
</table>
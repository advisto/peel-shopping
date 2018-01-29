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
// $Id: admin_liste_tva.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_TVA_TITLE}</div>
<div class="alert alert-info">{$STR_ADMIN_TVA_FORM_EXPLAIN}</div>
<div><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" /> <a href="{$add_href|escape:'html'}">{$STR_ADMIN_TVA_CREATE}</a></div>
{if isset($results)}
<div class="table-responsive">
	<table class="table">
		<tr>
			<td class="menu" style="width:80px;">{$STR_ADMIN_ACTION}</td>
			<td class="menu" style="width:200px;">{$STR_ADMIN_VAT_PERCENTAGE}</td>
			<td class="menu">{$STR_ADMIN_WEBSITE}</td>
	{if !empty($STR_ADMIN_SITE_COUNTRY)}
			<td class="menu">{$STR_ADMIN_SITE_COUNTRY}</td>
	{/if}
		</tr>
	{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center"><a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_ADMIN_TVA_DELETE}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a> &nbsp; <a title="{$STR_ADMIN_TVA_UPDATE}" href="{$res.modif_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
			<td class="center"><a title="{$STR_ADMIN_TVA_UPDATE|str_form_value}" href="{$res.modif_href|escape:'html'}">{$res.tva}</a> %</td>
			<td class="center">{$res.site_name}</td>
		{if !empty($STR_ADMIN_SITE_COUNTRY)}
			<td class="center">{$res.site_country}</td>
		{/if}
		</tr>
	{/foreach}
	</table>
</div>
{else}
<div class="alert alert-warning">{$STR_ADMIN_TVA_NOTHING_FOUND}</div>
{/if}
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
// $Id: admin_liste_home.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_HTML_TITLE}</div>
<p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_ADMIN_HTML_CREATE}</a></p>
<div class="alert alert-info"><b>{$STR_NOTA_BENE}{$STR_BEFORE_TWO_POINTS}:</b> {$STR_ADMIN_HTML_EXPLAIN}</div>
<div class="table-responsive">
	<table class="table">
	{if isset($results)}
		{$links_header_row}
		{foreach $results as $res}
		{$res.tr_rollover}
		<td class="center" width="50">
			<a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.titre}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value} {$res.titre}" /></a>
			<a title="{$STR_ADMIN_HTML_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" alt="edit" /></a>
		</td>
		<td class="center">{$res.lang|html_entity_decode_if_needed}</td>
		<td class="center">{$res.titre|html_entity_decode_if_needed}</td>
		<td class="center" width="150">{$res.date}</td>
		<td class="center">{$res.emplacement|html_entity_decode_if_needed}</td>
		<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
		<td class="center" width="150">{$res.site_name}</td>
	{if isset($res.site_country)}
		<td class="center" width="150">{$res.site_country}</td>
	{/if}
		</tr>
		{/foreach}
	{else}
		<tr><td><b>-</b></td></tr>
	{/if}
	</table>
</div>
{$links_multipage}
{if $is_welcome_ad_module_active}
<br /><a href="{$wwwroot}/" onclick="thisdate=new Date;thisdate.setFullYear(thisdate.getFullYear()-1);document.cookie='info_inter=; path=/; expires='+thisdate.toGMTString();">{$STR_ADMIN_HTML_DELETE_COOKIE_LINK}</a>
{/if}
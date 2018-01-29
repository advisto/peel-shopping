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
// $Id: bannerAdmin_liste.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}
<div class="entete">{$STR_MODULE_BANNER_ADMIN_LIST_TITLE}</div>
<div><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_MODULE_BANNER_ADMIN_CREATE}</a></div>
{if isset($results)}
<div class="table-responsive">
	<table class="table">
		{$links_header_row}
		{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center"><a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.description}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
				<a title="{$STR_MODULE_BANNER_ADMIN_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a>
			</td>
			<td class="center" class="position">{$res.position}</td>
			<td class="center">{$res.rang}</td>
			<td class="center">{$res.description}</td>
			<td class="center">
			{if isset($res.swf)}
				{$res.swf}
			{elseif isset($res.src)}
				<img src="{$res.src|escape:'html'}" style="max-width:250px; max-height:60px" />
			{/if}
			</td>
			<td class="center">{$res.date_debut}</td>
			<td class="center">{$res.date_fin}</td>
			<td class="center">{$res.hit}</td>
			<td class="center">{$res.vue}</td>
			<td class="center">{$res.lang}</td>
			<td class="center"><img class="change_status" src="{$res.modif_etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
			<td class="center">{$res.site_name}</td>
		</tr>
		{/foreach}
	</table>
</div>
{else}
<div class="alert alert-danger">{$STR_MODULE_BANNER_ADMIN_NOTHING_FOUND}</div>
{/if}
<div align="center">{$links_multipage}</div>

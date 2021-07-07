{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_email-templates_report.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
*}
<br />
{if empty($entete_disable)}
<div class="entete">{$STR_ADMIN_EMAIL_TEMPLATES_TITLE}</div>
{/if}
{if !empty($create_href)}
<div><a href="{$create_href}" class="btn btn-xs btn-warning">{$STR_ADD_NEW_TEMPLATE}</a><br /><br /></div>
{/if}
{if isset($results)}
{if empty($head_links_multipage_disable)}
<div>{$links_multipage}</div>
{/if}
<div class="table-responsive email_templates_report">
	<table class="table">
		{$links_header_row}
		{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center"><b>{$res.id}</b></td>
			{if !empty($params.technical_code)}<td class="center"><b>{$res.technical_code}</b></td>{/if}
			<td class="center"><b>{$res.category_name}</b></td>
			<td class="center"><b>{$res.name}</b></td>
			<td class="center">{$res.subject|htmlentities}</td>
			<td style="padding:8px;">{$res.text|htmlentities}</td>
			<td class="center" style="padding-left:5px;padding-right:5px;">{$res.lang}</td>
			{if !empty($params.active)}<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>{/if}
			<td class="center"><a href="{$res.edit_href|escape:'html'}">{$STR_MODIFY}</a></td>
			{if !empty($params.site_id)}<td class="center">{$res.site_name}</td>{/if}
		</tr>
		{/foreach}
	</table>
</div>
<div>{$links_multipage}</div>
{/if}
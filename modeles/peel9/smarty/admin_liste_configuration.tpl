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
// $Id: admin_liste_configuration.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_CONFIGURATION_TITLE}</div>
<div style="margin-top:5px;">
	<p><a href="{$add_href|escape:'html'}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {$STR_ADMIN_CONFIGURATION_CREATE}</a></p>
</div>
<div class="alert alert-info">
	<b>{$STR_NOTA_BENE}{$STR_BEFORE_TWO_POINTS}:</b> {$STR_ADMIN_CONFIGURATION_EXPLAIN}
</div>
<div class="table-responsive">
	<table class="table">
	{if isset($results)}
		{$HeaderRow}
		{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center" width="50">
				<a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.technical_code}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value} {$res.technical_code}" /></a>
				<a title="{$STR_ADMIN_CONFIGURATION_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" alt="edit" /></a>
			</td>
			<td class="center">{$res.lang|html_entity_decode_if_needed}</td>
			<td class="center">{$res.type|html_entity_decode_if_needed}</td>
			<td class="center">{$res.technical_code|html_entity_decode_if_needed}</td>
			<td class="center">{$res.string|html_entity_decode_if_needed|escape:'html'}{$res.comment}</td>
			<td class="center" width="150">{$res.date}</td>
			<td class="center">{$res.origin|html_entity_decode_if_needed}</td>
			<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
			<td class="center">{$res.site_id}</td>
		</tr>
		{/foreach}
	{else}
		<tr><td><b>-</b></td></tr>
	{/if}
	</table>
	<div class="center">{$Multipage}</div>
</div>
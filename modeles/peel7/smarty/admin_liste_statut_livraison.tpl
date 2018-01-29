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
// $Id: admin_liste_statut_livraison.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_STATUT_LIVRAISON_TITLE}</div>
<p class="alert alert-info">{$STR_ADMIN_STATUT_LIVRAISON_EXPLAIN}</p>
<p><img src="{$add_button_url|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_status_url|escape:'html'}">{$STR_ADMIN_STATUT_LIVRAISON_CREATE}</a></p>
{if isset($results)}
<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<td class="menu">{$STR_ADMIN_TECHNICAL_CODE}</td>
				<td class="menu">{$STR_ADMIN_STATUT_STATUS_TYPE}</td>
				<td class="menu">{$STR_ADMIN_POSITION}</td>
				<td class="menu">{$STR_ADMIN_WEBSITE}</td>
			</tr>
		</thead>
		<tbody class="sortable">
		{foreach $results as $res}
			{$res.tr_rollover}
				<td class="center">{$res.technical_code}</td>
				<td><a title="{$STR_ADMIN_STATUT_UPDATE|str_form_value}" href="{$res.modif_href|escape:'html'}">{$res.nom}</a></td>
				<td class="center position">{$res.position}</td>
				<td class="center">{$res.site_name}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
{else}
<div class="alert alert-warning">{$STR_ADMIN_STATUT_NO_STATUS_FOUND}</div>
{/if}
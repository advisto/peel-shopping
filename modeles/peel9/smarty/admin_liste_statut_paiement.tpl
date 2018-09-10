{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_statut_paiement.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}
<div class="entete">{$STR_ADMIN_STATUT_PAIEMENT_TITLE}</div>
<p class="alert alert-info">{$STR_ADMIN_STATUT_PAIEMENT_EXPLAIN}</p>
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
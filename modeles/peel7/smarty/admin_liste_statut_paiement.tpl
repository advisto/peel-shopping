{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_statut_paiement.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}
<table class="main_table">
	<thead>
		<tr>
			<td class="entete" colspan="3">{$STR_ADMIN_STATUT_PAIEMENT_TITLE}</td>
		</tr>
		<tr>
			<td colspan="3"><p class="global_help">{$STR_ADMIN_STATUT_PAIEMENT_EXPLAIN}</p></td>
		</tr>
{if isset($results)}
		<tr>
			<td class="menu">{$STR_ADMIN_ID}</td>
			<td class="menu">{$STR_ADMIN_STATUT_STATUS_TYPE}</td>
			<td class="menu">{$STR_ADMIN_POSITION}</td>
		</tr>
	</thead>
	<tbody class="sortable">
	{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center">{$res.id}</td>
			<td><a title="{$STR_ADMIN_STATUT_UPDATE|str_form_value}" href="{$res.modif_href|escape:'html'}">{$res.nom}</a></td>
			<td class="center position">{$res.position}</td>
		</tr>
	{/foreach}
	</tbody>
{else}
	<tr><td colspan="3"><b>{$STR_ADMIN_STATUT_NO_STATUS_FOUND}</b></td></tr>
{/if}
</table>
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
// $Id: admin_liste_code_promo.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_CODES_PROMOS_LIST_TITLE}</div>
<p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" />
<a href="codes_promos.php?mode=ajout&amp;on_type=1">{$STR_ADMIN_CODES_PROMOS_CREATE_PERCENTAGE_REBATE}</a>
<img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" />
<a href="codes_promos.php?mode=ajout&amp;on_type=2">{$STR_ADMIN_CODES_PROMOS_CREATE_AMOUNT_REBATE} {$site_symbole}</a></p>
{if $are_results}
<div class="table-responsive">
	<table class="table">
		{$links_header_row}
		{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center">
				<a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
				<a title="{$STR_MODIFY|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" alt="{$STR_MODIFY|str_form_value}" /></a>
			</td>
			<td class="center"><a title="{$STR_MODIFY|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.nom}</a></td>
			<td class="center">{$res.date_debut}</td>
			<td class="center">{$res.date_fin}</td>
			<td class="center">{if $res.on_type == 1}{$res.percent} % {else}{$res.valeur}{/if}</td>
			<td class="center">{$res.montant_min}</td>
			<td class="center">{if !empty($res.category_name)}{$res.category_name}{else}{$STR_ADMIN_ALL_CATEGORIES}{/if}</td>
			<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
			<td class="center">{$res.source}</td>
			<td class="center">{$res.site_name}</td>
		</tr>
		{/foreach}
	</table>
</div>
<div class="center">{$links_multipage}</div>
{else}
<div class="title_label">{$STR_ADMIN_CODES_PROMOS_NOT_FOUND}</div>
{/if}

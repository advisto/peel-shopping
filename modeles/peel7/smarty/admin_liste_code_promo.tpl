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
// $Id: admin_liste_code_promo.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete" colspan="9">{$STR_ADMIN_CODES_PROMOS_LIST_TITLE}</td>
	</tr>
	<tr>
		<td colspan="9">
			<p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" />
			<a href="codes_promos.php?mode=ajout&amp;on_type=1">{$STR_ADMIN_CODES_PROMOS_CREATE_PERCENTAGE_REBATE}</a>
			<img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" />
			<a href="codes_promos.php?mode=ajout&amp;on_type=2">{$STR_ADMIN_CODES_PROMOS_CREATE_AMOUNT_REBATE} {$site_symbole}</a></p>
		</td>
	</tr>
	{if $are_results}
		{$links_header_row}
		{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center">
				<a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
				<a title="{$STR_MODIFY|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" alt="{$STR_MODIFY|str_form_value}" /></a>
			</td>
			<td class="center">{$res.nom}</td>
			<td class="center">{$res.date_debut}</td>
			<td class="center">{$res.date_fin}</td>
			<td class="center">{if $res.on_type == 1}{$res.percent} % {else}{$res.valeur}{/if}</td>
			<td class="center">{$res.montant_min}</td>
			<td class="center">{if !empty($res.category_name)}{$res.category_name}{else}{$STR_ADMIN_ALL_CATEGORIES}{/if}</td>
			<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
			<td class="center">{$res.source}</td>
		</tr>
		{/foreach}
		<tr><td class="center" colspan="9">{$links_multipage}</td></tr>
	{else}
		<tr><td colspan="9" class="label">{$STR_ADMIN_CODES_PROMOS_NOT_FOUND}</td></tr>
	{/if}
</table>
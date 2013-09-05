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
// $Id: avis_public_list.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}
<h2>{$STR_MODULE_AVIS_PEOPLE_OPINION_ABOUT_PRODUCT}: {if $type == 'produit'}{$product_name}{elseif $type == 'annonce'}{$annonce_titre}{/if}</h2>
{if $are_results}
	<b>{$STR_MODULE_AVIS_AVERAGE_RATING_GIVEN}</b> 
	{for $foo=1 to $avisnote}<img src="{$star_src|escape:'html'}" alt="" />{/for}
	{if $display_nb_vote_graphic_view}
		<a href="{$all_results_url}">{$total_vote} {$STR_POSTED_OPINIONS|lower}</a>
		<table class="notation_tab">
			{foreach $notations as $notation}
			<tr>
				<td class="bar_contener">
					<div style="width:100%">
						<div class="bar" style="width:{$notation.width}%;"></div>
					</div>
				</td>
				<td style="width:85px;">
					{for $foo=1 to {$notation.note}}<img src="{$star_src|escape:'html'}" alt="" />{/for}
				</td>
				<td>
					<a href="{$notation.link}">{$notation.nb_this_vote} {if $notation.nb_this_vote>1} {$STR_POSTED_OPINIONS|lower} {else} {$STR_POSTED_OPINION|lower} {/if}</a>
				</td>
			</tr>
			{/foreach}
		</table>
	{/if}
	<table class="avis_public_list">
		{foreach $results as $res}
		<tr>
			<td class="top{if $res.i} td_avis{/if}">
				<i>{$STR_MODULE_AVIS_OPINION_POSTED_BY} {$res.pseudo} {$STR_ON_DATE_SHORT} {$res.date}</i><br />{$res.avis|html_entity_decode_if_needed|nl2br_if_needed}
			</td>
		</tr>
		{/foreach}
	</table>
{else}
	{if $type == 'produit'}
	<div style="margin-top: 10px;">{$STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT}.</div>
	{elseif $type == 'annonce'}
	<div style="margin-top: 5px;">{$STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD}.</div>
	{/if}
{/if}
{if $type == 'produit'}
<p style="margin-top: 20px;">
	<a href="{$urlprod}">{$STR_BACK_TO_PRODUCT|escape:'html'}</a>
</p>
{elseif $type == 'annonce'}
<p style="margin-top: 20px;">
	<a href="{$urlannonce}">{$STR_MODULE_ANNONCES_BACK_TO_AD|escape:'html'}</a>
</p>
{/if}
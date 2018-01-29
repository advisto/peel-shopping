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
// $Id: admin_arbo_categorie.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}{$tr_rollover}
	<td class="center">
		<a title="{$STR_ADMIN_CATEGORIES_ADD_SUBCATEGORY|str_form_value}" href="{$ajout_cat_href|escape:'html'}"><img src="{$ajout_cat_src|escape:'html'}" width="24" alt="" /></a>
		&nbsp;<a title="{$STR_ADMIN_CATEGORIES_ADD_PRODUCT|str_form_value}" href="{$ajout_prod_href|escape:'html'}"><img src="{$ajout_prod_src|escape:'html'}" width="24" alt="" /></a>
		&nbsp;<a title="{$STR_ADMIN_CATEGORIES_DELETE_CATEGORY|str_form_value}" data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" href="{$sup_cat_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="" /></a>
	</td>
	<td class="center">{$cat_id}</td>
	<td class="left">{$indent}{if isset($image)}<img src="{$image.src|escape:'html'}" alt="{$image.name|str_form_value}" />{/if}</td>
	<td class="left">{$indent}<a href="{$modif_href|escape:'html'}">{$cat_nom|html_entity_decode_if_needed}</a></td>
	<td class="left">{$site_name|html_entity_decode_if_needed}</td>
	{if isset($promotion)}
	<td class="center">{$promotion.percent} % / {$promotion.prix}</td>
	{/if}
	<td class="center">{$STR_ADMIN_LEVEL} {$depth}<br />{if isset($up_href)}<a href="{$up_href|escape:'html'}"><img src="{$up_src|escape:'html'}" alt="" /></a>{/if} {$STR_NUMBER}{$cat_position} <a href="{$desc_href|escape:'html'}"><img src="{$desc_src|escape:'html'}" alt="" /></a></td>
	<td class="center"><img class="change_status" src="{$modif_src|escape:'html'}" alt="" onclick="{$etat_onclick|escape:'html'}" /></td>
</tr>
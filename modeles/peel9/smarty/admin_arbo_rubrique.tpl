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
// $Id: admin_arbo_rubrique.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{$tr_rollover}
<td class="center">
	<a title="{$STR_ADMIN_RUBRIQUES_ADD_SUBCATEGORY|str_form_value}" href="{$ajout_rub_href|escape:'html'}"><img src="{$rubrique_src|escape:'html'}" width="24" /></a>
	&nbsp;<a title="{$STR_ADMIN_ARTICLES_FORM_ADD|str_form_value}" href="{$ajout_art_href|escape:'html'}"><img src="{$prod_cat_src|escape:'html'}" width="24" /></a>
	&nbsp;<a title="{$STR_ADMIN_RUBRIQUES_DELETE_CATEGORY|str_form_value} {$nom|escape:'html'}" data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" href="{$sup_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
</td>
<td class="left" style="padding-left:10px">{$indent}{if !empty($image)}<img src="{$image_src|escape:'html'}" alt="{$image|str_form_value}" />{/if}</td>
<td class="left">{$indent}<a href="{$modif_href|escape:'html'}">{$nom|html_entity_decode_if_needed}</a></td>
<td class="center">{$site_name|html_entity_decode_if_needed}</td>
<td class="center">{$STR_ADMIN_LEVEL} {$depth}<br />{if $position > 1}<a href="{$up_href|escape:'html'}"><img src="{$up_src|escape:'html'}" alt="" /></a>{/if} {$STR_NUMBER}{$position} <a href="{$desc_href|escape:'html'}"><img src="{$desc_src|escape:'html'}" alt="" /></a></td>
<td class="center"><img class="change_status" src="{$etat_src|escape:'html'}" alt="" onclick="{$etat_onclick|escape:'html'}" /></td>
</tr>
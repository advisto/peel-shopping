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
// $Id: admin_liste_marque.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}<table class="admin_liste_marque">
	<tr><td colspan="6" class="entete">{$STR_ADMIN_MARQUES_TITLE}</td></tr>
	<tr><td colspan="6"><p><img src="{$add_src}" width="16" height="16" alt="" class="middle" /><a href="{$href|escape:'html'}">{$STR_ADMIN_MARQUES_ADD_BRAND}</a></p></td></tr>
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_ADMIN_ID}</td>
		<td class="menu">{$STR_ADMIN_IMAGE}</td>
		<td class="menu">{$STR_BRAND}</td>
		<td class="menu">{$STR_ADMIN_POSITION}</td>
		<td class="menu">{$STR_STATUS}</td>
	</tr>
	{if isset($results)}
	{foreach $results as $res}
	{$res.tr_rollover}
		<td class="center"><a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
			<a title="{$STR_ADMIN_MARQUES_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
		<td class="center">{$res.id}</td>
		<td class="center">{if !empty($res.img_src)}<img src="{$res.img_src|escape:'html'}" alt="" />{/if}</td>
		<td class="center"><a title="{$STR_ADMIN_MARQUES_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.nom|html_entity_decode_if_needed}</a></td>
		<td class="center position">{$res.position}</td>
		<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
	</tr>
	{/foreach}
	{else}
		<tr><td><b>{$STR_ADMIN_MARQUES_NOTHING_FOUND}</b></td></tr>
	{/if}
	<tr><td class="center" colspan="4">{$links_multipage}</td></tr>
</table>
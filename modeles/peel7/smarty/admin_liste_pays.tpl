{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_pays.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}<table class="main_table">
	<thead>
		<tr>
			<td class="entete" colspan="5">{$STR_ADMIN_PAYS_TITLE}</td>
		</tr>
		<tr>
			<td colspan="5"><div class="global_help">{$STR_ADMIN_PAYS_LIST_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td colspan="5"><p><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" class="middle" /><a href="{$add_href|escape:'html'}">{$STR_ADMIN_PAYS_CREATE}</a></p></td>
		</tr>
		<tr>
			<td colspan="5">
				<form action="{$action|escape:'html'}" method="post">
					{$STR_ADMIN_PAYS_ZONE_UPDATE_LABEL}{$STR_BEFORE_TWO_POINTS}: 
					<select name="zones">
					{foreach $options as $o}
						<option value="{$o.value|str_form_value}">{$o.name}</option>
					{/foreach}
					</select>  <input type="radio" value="1" name="etat" /> {$STR_ADMIN_ACTIVATE} / <input type="radio" value="0" name="etat" /> {$STR_ADMIN_DEACTIVATE} <input class="bouton" type="submit" value="{$STR_VALIDATE|str_form_value}" />
				</form>
			</td>
		</tr>
{if isset($results)}
		<tr>
			<td class="menu">{$STR_ADMIN_ACTION}</td>
			<td class="menu center">{$STR_COUNTRY}</td>
			<td class="menu center">{$STR_ADMIN_MENU_MANAGE_ZONES}</td>
			<td class="menu center">{$STR_ADMIN_POSITION}</td>
			<td class="menu center">{$STR_STATUS}</td>
		</tr>
	</thead>
	<tbody class="sortable">
	{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center"><a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_DELETE|str_form_value} {$res.nom}" href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a> &nbsp; <a title="{$STR_ADMIN_PAYS_MODIFY}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
			<td style="padding-left:10px">{$res.pays}</td>
			<td class="center">{$res.zone}</td>
			<td class="center position">{$res.position}</td>
			<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
		</tr>
	{/foreach}
{else}
	</thead>
	<tbody>
		<tr><td colspan="5"><b>{$STR_ADMIN_PAYS_NOTHING_FOUND}</b></td></tr>
{/if}
	</tbody>
</table>
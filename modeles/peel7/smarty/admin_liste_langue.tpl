{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_langue.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete" colspan="7">{$STR_ADMIN_LANGUES_TITLE}</td>
	</tr>
	<tr>
		<td colspan="7">
			<table>
				<tr>
					<td><img src="{$add_src|escape:'html'}" width="16" height="16" alt="" /></td>
					<td><a href="{$add_href|escape:'html'}">{$STR_ADMIN_LANGUES_ADD_LANGUAGE}</a></td>
				</tr>
			</table>
			<p><a href="langues.php?mode=repair">{$STR_ADMIN_LANGUES_REPAIR_LINK}</a></p>
			<div class="global_help">
				<b>{$STR_WARNING}{$STR_BEFORE_TWO_POINTS}:</b><br />
				<ul>
					<li>{$STR_ADMIN_LANGUES_EXPLAIN1}</li>
					<li>{$STR_ADMIN_LANGUES_EXPLAIN2}</li>
					<li>{$STR_ADMIN_LANGUES_EXPLAIN3}</li>
				</ul>
			</div>
		</td>
	</tr>
	{if isset($results)}
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_ADMIN_LANGUAGE}</td>
		<td class="menu">{$STR_ADMIN_LANGUES_EXTENSION}</td>
		<td class="menu">{$STR_ADMIN_FLAG}</td>
		<td class="menu">{$STR_ADMIN_URL_REWRITING}</td>
		<td class="menu">{$STR_ADMIN_POSITION}</td>
		<td class="menu">{$STR_STATUS}</td>
	</tr>
	{foreach $results as $res}
	{$res.tr_rollover}
		<td class="center"><a title="{$STR_ADMIN_LANGUES_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$edit_src|escape:'html'}" width="16" height="16" alt="" /></a></td>
		<td class="center"><a title="{$STR_ADMIN_LANGUES_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.nom}</a></td>
		<td class="center">{$res.lang}</td>
		<td class="center">{if isset($res.flag_src)}<img src="{$res.flag_src|escape:'html'}" alt="" />{/if}</td>
		<td class="center">{$res.url_rewriting}</td>
		<td class="center position">{$res.position}</td>
		<td class="center"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" onclick="{$res.etat_onclick|escape:'html'}" /></td>
	</tr>
	{/foreach}
	{else}
	<tr><td><b>{$STR_ADMIN_LANGUES_NOTHING_FOUND}</b></td></tr>
	{/if}
</table>
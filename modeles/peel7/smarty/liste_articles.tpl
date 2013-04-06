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
// $Id: liste_articles.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}<table class="main_table">
	<tr>
		<td colspan="5">
			<form method="post" action="{$action|escape:'html'}">
				<table class="main_table">
					<tr><td colspan="4" class="entete">{$STR_ADMIN_CHOOSE_SEARCH_CRITERIA}</td></tr>
					<tr>
						<td class="label">{$STR_ADMIN_SEARCH_CRITERIA}{$STR_BEFORE_TWO_POINTS}:</td>
						<td>{$STR_ADMIN_RUBRIQUE}{$STR_BEFORE_TWO_POINTS}:
							<select size="1" name="cat_search" >
								<option value="null">{$STR_ADMIN_RUBRIQUES_ALL}</option>
								<option value="0" {if $cat_search=='0'} selected="selected"{/if}{$STR_ADMIN_RUBRIQUES_NONE_RELATED}</option>
								{$rubrique_options}
							</select>
						</td>
						<td>{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:
							<select size="1" name="etat">
								<option value="null">{$STR_ADMIN_ARTICLES_ALL}</option>
								<option value="1">{$STR_ADMIN_ONLINE}</option>
								<option value="0">{$STR_ADMIN_OFFLINE}</option>
							</select>
						</td>
					</tr>
					<tr>
						<td></td>
						<td class="top">{$STR_ADMIN_SEARCH_IN_TITLE}{$STR_BEFORE_TWO_POINTS}: <input type="text" name="text_in_title" size="15" value="{$text_in_title|html_entity_decode_if_needed|str_form_value}" /></td>
						<td class="top">{$STR_ADMIN_SEARCH_IN_ARTICLE}{$STR_BEFORE_TWO_POINTS}: <input type="text" name="text_in_article" size="15" value="{$text_in_article|html_entity_decode_if_needed|str_form_value}" /></td>
					</tr>
					<tr>
						<td class="center" colspan="4"><p><input class="bouton" type="submit" value="{$STR_SEARCH|str_form_value}" name="action" /></p></td>
					</tr>
				  </table>
			</form>
		</td>
	</tr>
	<tr>
		<td class="entete" colspan="5">{$STR_ADMIN_ARTICLES_ARTICLES_LIST}</td>
	</tr>
	<tr>
		<td colspan="5">
			<table>
				<tr>
					<td><img src="images/add.png" width="16" height="16" alt="" /></td>
					<td><a href="{$ajout_href|escape:'html'}">{$STR_ADMIN_ARTICLES_FORM_ADD}</a></td>
				</tr>
			</table>
		</td>
	</tr>
{if $is_empty}
	<tr><td><b>{$STR_ADMIN_ARTICLES_NOTHING_FOUND_FOR_LANG} {$langue}.</b></td></tr>
{else}
	<tr>
		<th class="menu">{$STR_ADMIN_ACTION}</th>
		<th class="menu">{$STR_ADMIN_RUBRIQUE}</th>
		<th class="menu">{$STR_ADMIN_TITLE}</th>
		<th class="menu">{$STR_WEBSITE}</th>
		<th class="menu">{$STR_STATUS}</th>
	</tr>
	{foreach $lignes as $li}
	{$li.tr_rollover}
		<td class="center"><a onclick="return confirm('{$STR_ADMIN_DELETE_WARNING|filtre_javascript:true:true:true}');" title="{$STR_DELETE|str_form_value} {$li.titre|htmlspecialchars}" href="{$li.drop_href|escape:'html'}"><img src="{$li.drop_src|escape:'html'}" alt="" /></a></td>
		<td>
			{if empty($li.rubs)}
				<span style="color:red">-</span><br />
			{else}
				{foreach $li.rubs as $ru}
					{if empty($ru)}
						<span style="color:red">-</span><br />
					{else}
						{if !empty($ru.parent_nom)}
							<span style="color:#666666">{$ru.parent_nom|html_entity_decode_if_needed}</span> &gt; 
						{/if}
						{$ru.nom|html_entity_decode_if_needed}<br />
					{/if}
				{/foreach}
			{/if}
		</td>
		<td><a title="{$STR_ADMIN_ARTICLES_FORM_MODIFY|str_form_value}" href="{$li.modif_href|escape:'html'}">{$li.titre|html_entity_decode_if_needed}</a></td>
		<td class="center">
			{if empty($li.sites)}
			<span style="color:red">-</span><br />
			{else}
				{foreach $li.sites as $si}
				{$si|html_entity_decode_if_needed}
				{/foreach}
			{/if}
		</td>
		<td class="center"><img class="change_status" src="{$li.modif_etat_src|escape:'html'}" alt="" onclick="{$li.etat_onclick|escape:'html'}" /></td>
	</tr>
	{/foreach}
{/if}
	<tr><td class="center" colspan="4">{$Multipage}</td></tr>
</table>
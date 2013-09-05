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
// $Id: admin_connexion_user_liste.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<table class="full_width">
	<tr>
		<td class="entete" colspan="11">{$STR_ADMIN_CONNEXION_USER_TITLE}</td>
	</tr>
	{if $display_search_form}
	<tr>
		<td colspan="11">
			<form method="get" action="{$action|escape:'html'}">
				<table class="full_width" class="center">
					<tr>
						<td>{$STR_ADMIN_DATE}{$STR_BEFORE_TWO_POINTS}:</td>
						<td>{$STR_ADMIN_REMOTE_ADDR}{$STR_BEFORE_TWO_POINTS}:</td>
						<td>{$STR_PSEUDO}{$STR_BEFORE_TWO_POINTS}:</td>
						<td>{$STR_ADMIN_USER}{$STR_BEFORE_TWO_POINTS}:</td>
					</tr>
					<tr>
						<td><input type="text" name="date" class="datepicker" value="{$date|str_form_value}" /></td>
						<td><input type="text" name="user_ip" value="{$user_ip|str_form_value}" /></td>
						<td><input type="text" name="client_info" value="{$client_info|str_form_value}" /></td>
						<td><input type="text" name="user_id" value="{$user_id|str_form_value}" /></td>
						<td class="center"><input type="hidden" name="mode" value="recherche" /><input type="submit" class="bouton" value="{$STR_SEARCH|str_form_value}" /></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	{/if}
</table>
<br /><br />
<form method="post" action="{$action_maj|escape:'html'}">
	{$form_token}
	<table class="full_width">
	{if isset($results)}
	<tr>
		<td class="right">
			<input type="hidden" name="mode" value="maj_statut" />
			<table id="tablesForm" class="full_width" cellpadding="2">
				{$links_header_row}
				{foreach $results as $res}
				{$res.tr_rollover}
					<td class="center">{$res.id}</td>
					<td class="center">{$res.date}</td>
					<td class="center">{$res.ip}</td>
					{if isset($res.country_ip)}<td class="center">{$res.country_ip}</td>{/if}
					{if isset($res.country_account)}<td class="center">{$res.country_account}</td>{/if}
					{if isset($res.active_ads_count)}<td class="center">{$res.active_ads_count}</td>{/if}
					<td class="center">{$res.user_login_displayed}</td>
					<td class="center">{$res.user_id}</td>
				</tr>
				{/foreach}
			</table>
		</td>
	</tr>
	<tr><td class="center">{$links_multipage}</td></tr>
	{else}
	<tr><td><b>{$STR_ADMIN_CONNEXION_NOTHING_FOUND}</b></td></tr>
	{/if}
	</table>
</form>
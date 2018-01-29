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
// $Id: admin_connexion_user_liste.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_CONNEXION_USER_TITLE}</div>
{if $display_search_form}
<form class="entryform form-inline" role="form" method="get" action="{$action|escape:'html'}">
	<div class="row">	
		<div class="col-sm-3 center">
			<label for="search_date">{$STR_ADMIN_DATE}{$STR_BEFORE_TWO_POINTS}:</label><br />
			<input type="text" class="form-control datepicker" id="search_date" name="date" value="{$date|str_form_value}" style="width:110px;" />
		</div>
		<div class="col-sm-3 center">
			<label for="search_user_ip">{$STR_ADMIN_REMOTE_ADDR}{$STR_BEFORE_TWO_POINTS}:</label><br />
			<input type="text" class="form-control" id="search_user_ip" name="user_ip" value="{$user_ip|str_form_value}" />
		</div>
		<div class="col-sm-3 center">
			<label for="search_client_info">{$STR_ADMIN_USER}{$STR_BEFORE_TWO_POINTS}:</label><br />
			<input type="text" class="form-control" id="search_client_info" name="client_info" value="{$client_info|str_form_value}" />
		</div>
		<div class="col-sm-3 center">
			<label for="search_user_id">{$STR_ADMIN_ID}{$STR_BEFORE_TWO_POINTS}:</label><br />
			<input type="text" class="form-control" id="search_user_id" name="user_id" value="{$user_id|str_form_value}" />
		</div>
		<div class="clearfix"></div>
		<div class="col-sm-12 center" style="padding-top:15px">
			<input type="hidden" name="mode" value="recherche" /><input type="submit" class="btn btn-primary" value="{$STR_SEARCH|str_form_value}" />
		</div>
	</div>
</form>
{/if}
<br />
<form class="entryform form-inline" role="form" method="post" action="{$action_maj|escape:'html'}">
	{$form_token}
	{if isset($results)}
	<div class="table-responsive">
		<table class="table">
			<tr>
				<td class="right">
					<input type="hidden" name="mode" value="maj_statut" />
					<table id="tablesForm" class="full_width">
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
							<td class="center">{$res.site_id}</td>
						</tr>
						{/foreach}
					</table>
				</td>
			</tr>
		</table>
	</div>
	<div class="center">{$links_multipage}</div>
	{else}
	<div class="alert alert-warning">{$STR_ADMIN_CONNEXION_NOTHING_FOUND}</div>
	{/if}
</form>
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
// $Id: admin_list_admin_action.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	<table class="full_width" >
		<tr>
			<td class="entete" colspan="2">{$title}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_ADMIN_ACTIONS_CALLS_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td><input type="hidden" name="mode" value="recherche" /></td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_ADMIN_ACTIONS_MODERATOR}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
			{if isset($options_ids)}
			<select class="form-control" name="admin_id" id="admin_id">
				<option value="">{$STR_CHOOSE}...</option>
			{foreach $options_ids as $o}
				<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
			{/foreach}
			</select>
			{else}
				{$STR_ADMIN_ADMIN_ACTIONS_NO_MODERATOR_WITH_ACTIONS_FOUND}
			{/if}
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_ADMIN_ACTIONS_ACTIONS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" name="action_cat">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $options_actions as $o}
						<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_ADMIN_ACTIONS_CONCERNED_ACCOUNT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input name="account" type="text" class="form-control" value="{if !empty($account)}{$account|str_form_value}{/if}" />
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_DATE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" name="date" id="date" onkeyup="display_input2_element(this.id)" onchange="display_input2_element(this.id)" onclick="display_input2_element(this.id)" style="width:100px;">
					<option value="1"{if $date == '1'} selected="selected"{/if}>{$STR_ADMIN_DATE_ON|ucfirst}</option>
					<option value="2"{if $date == '2'} selected="selected"{/if}>{$STR_ADMIN_DATE_STARTING|ucfirst}</option>
					<option value="3"{if $date == '3'} selected="selected"{/if}>{$STR_ADMIN_DATE_BETWEEN_START|ucfirst}</option>
				</select>
				<input id="date_input1" name="date_input1" maxlength="10" size="10" type="text" class="form-control datepicker" value="{$date_input1|str_form_value}" title="{$STR_ADMIN_INPUT_SEARCH|str_form_value}" style="width:110px;" />
				<span id="date_input2_span" style="display:none"> {$STR_ADMIN_DATE_BETWEEN_AND} <input id="date_input2" name="date_input2" maxlength="10" size="10" type="text" class="form-control datepicker" value="{$date_input2|str_form_value}" title="" style="width:110px;" /></span>
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_ADMIN_ACTIONS_DATA}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input name="search" maxlength="100" type="text" class="form-control" title="{$STR_ADMIN_INPUT_SEARCH|str_form_value}" value="{if !empty($search)}{$search|str_form_value}{/if}" />
				<select class="form-control" name="type" style="width:200px;">
					<option value="1"{if $type == '1'} selected="selected"{/if}>{$STR_SEARCH_ALL_WORDS}</option>
					<option value="2"{if $type == '2'} selected="selected"{/if}>{$STR_SEARCH_ANY_WORDS}</option>
					<option value="3"{if $type == '3'} selected="selected"{/if}>{$STR_SEARCH_EXACT_SENTENCE}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><input class="btn btn-primary" type="submit" value="{$STR_SEARCH|str_form_value}" name="post" /></td>
		</tr>
	</table>
</form>
{if isset($results)}
<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	<div class="center" >
		<input type="hidden" name="mode" value="supp" />
		{$links_multipage}
	</div>
	<div class="table-responsive" >
		<table id="tablesForm" class="table">
			{$links_header_row}
			{foreach $results as $res}
			{$res.tr_rollover}
				<td>
					<input name="form_delete[]" type="checkbox" value="{$res.id|str_form_value}" id="cbx_{$res.id}" />
				</td>
				<td class="center">
					{$res.date}
				</td>
				<td class="center">
					<a href="{$res.modif_admin_href|escape:'html'}">{$res.admin}</a>
				</td>
				<td class="center">
					{$res.action}
				</td>
				<td class="center">
				{if $res.is_membre}
					<a href="{$res.modif_membre_href|escape:'html'}">{$res.membre}</a>
				{else}
					-
				{/if}
				</td>
				<td class="center">
				{if !empty($res.data)}
					{if !empty($res.tpl_technical_code)}
					<b>{$STR_ADMIN_ADMIN_ACTIONS_TEMPLATE}{$STR_BEFORE_TWO_POINTS}:</b> {$res.tpl_technical_code} - {$res.tpl_lang|upper}<br />
					{else}
					{$res.data}<br />
					{/if}
				{/if}
				{if !empty($res.raison)}
					<b>{$STR_ADMIN_REASON}{$STR_BEFORE_TWO_POINTS}:</b> {$res.raison}<br />
				{/if}
				{if !empty($res.remarque)}
					<b>{$STR_ADMIN_REMARK}{$STR_BEFORE_TWO_POINTS}:</b> {$res.remarque}<br />
				{/if}
				</td>
			</tr>
			{/foreach}
		</table>
	</div>
	<div class="center">
		<input type="button" value="{$STR_ADMIN_CHECK_ALL|str_form_value}" onclick="if (markAllRows('tablesForm')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
		<input type="button" value="{$STR_ADMIN_UNCHECK_ALL|str_form_value}" onclick="if (unMarkAllRows('tablesForm')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
		<input type="submit" value="{$STR_ADMIN_ADMIN_ACTIONS_DELETE_ACTION|str_form_value}" class="btn btn-primary" name="delete_message_up" />
	</div>
	<div class="center">{$links_multipage}</div>
</form>
{else}
<p class="alert alert-danger">{$STR_ADMIN_ADMIN_ACTIONS_NO_ACTION_FOUND}</p>
{/if}
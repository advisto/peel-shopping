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
// $Id: admin_date_filter_form.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="get" action="{$action|escape:'html'}">
	<table class="main_table">
		<tr><td class="entete">{$form_title}</td></tr>
		<tr><td class="title_label center"><p>{$STR_ADMIN_TODAY_DATE} {$date}</p></td></tr>
		{if !$only_information_select_html_displayed}
		<tr>
			<td class="title_label center">
				<table class="center" style="margin:auto;">
					<tr>
						<td><b>{$from_date_txt}</b>{$STR_BEFORE_TWO_POINTS}:</td>
						<td>
							<select class="form-control" name="jour1" style="width:70px">
							{foreach $days_options as $do}
								<option value="{$do.value|str_form_value}"{if $do.issel} selected="selected"{/if}>{$do.name}</option>
							{/foreach}
							</select>
							<select class="form-control" name="mois1" style="width:130px">
							{foreach $months_options as $mo}
								<option value="{$mo.value|str_form_value}"{if $mo.issel} selected="selected"{/if}>{$mo.name}</option>
							{/foreach}
							</select>
							<select class="form-control" name="an1" style="width:90px">
							{foreach $years_options as $yo}
								<option value="{$yo.value|str_form_value}"{if $yo.issel} selected="selected"{/if}>{$yo.name}</option>
							{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td><b>{$until_date_txt}</b>{$STR_BEFORE_TWO_POINTS}:</td>
						<td>
							<select class="form-control" name="jour2" style="width:70px">
							{foreach $days2_options as $do}
								<option value="{$do.value|str_form_value}"{if $do.issel} selected="selected"{/if}>{$do.name}</option>
							{/foreach}
							</select>
							<select class="form-control" name="mois2" style="width:130px">
							{foreach $months2_options as $mo}
								<option value="{$mo.value|str_form_value}"{if $mo.issel} selected="selected"{/if}>{$mo.name}</option>
							{/foreach}
							</select>
							<select class="form-control" name="an2" style="width:90px">
							{foreach $years2_options as $yo}
								<option value="{$yo.value|str_form_value}"{if $yo.issel} selected="selected"{/if}>{$yo.name}</option>
							{/foreach}
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="title_label center">{$STR_ADMIN_ORDER_DATE_FIELD_FILTER}{$STR_BEFORE_TWO_POINTS}: 
				<select class="form-control" name="order_date_field_filter" style="width:200px; margin:auto;">
				{foreach $order_date_field_options as $date_field}
					<option value="{$date_field.value|str_form_value}"{if $date_field.issel} selected="selected"{/if}>{$date_field.name}</option>
				{/foreach}
				</select>
			</td>
		</tr>
		{/if}
		<tr>
			<td class="center title_label">{$information_select_html}</td>
		</tr>
		<tr>
			<td class="center"><p>{if isset($submit_html)}{$submit_html}{else}<input type="submit" name="submit" value="{$STR_ADMIN_DISPLAY_RESULTS|str_form_value}" class="btn btn-primary" />{/if}</p></td>
		</tr>
	</table>
</form>	
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
// $Id: admin_date_filter_form.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<form method="get" action="{$action|escape:'html'}">
	<table class="full_width" class="main_table">
		<tr><td class="entete" colspan="2">{$form_title}</td></tr>
		<tr><td class="label center" colspan="2"><p>{$STR_ADMIN_TODAY_DATE} {$date}</p></td></tr>
		<tr><td class="label right"><b>{$from_date_txt}</b>{$STR_BEFORE_TWO_POINTS}: <select name="jour1">
			{foreach $days_options as $do}
				<option value="{$do.value|str_form_value}"{if $do.issel} selected="selected"{/if}>{$do.name}</option>
			{/foreach}
			</select>
			<select name="mois1">
			{foreach $months_options as $mo}
				<option value="{$mo.value|str_form_value}"{if $mo.issel} selected="selected"{/if}>{$mo.name}</option>
			{/foreach}
			</select>
			<select name="an1">
			{foreach $years_options as $yo}
				<option value="{$yo.value|str_form_value}"{if $yo.issel} selected="selected"{/if}>{$yo.name}</option>
			{/foreach}
			</select>
		</td>
		<td class="label"><b>{$until_date_txt}</b>{$STR_BEFORE_TWO_POINTS}: <select name="jour2">
			{foreach $days2_options as $do}
				<option value="{$do.value|str_form_value}"{if $do.issel} selected="selected"{/if}>{$do.name}</option>
			{/foreach}
			</select>
			<select name="mois2">
			{foreach $months2_options as $mo}
				<option value="{$mo.value|str_form_value}"{if $mo.issel} selected="selected"{/if}>{$mo.name}</option>
			{/foreach}
			</select>
			<select name="an2">
			{foreach $years2_options as $yo}
				<option value="{$yo.value|str_form_value}"{if $yo.issel} selected="selected"{/if}>{$yo.name}</option>
			{/foreach}
			</select>
		</td>
		</tr>
		<tr>
			<td colspan="2" class="center label">{$information_select_html}</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p>{if isset($submit_html)}{$submit_html}{else}<input type="submit" name="submit" value="{$STR_ADMIN_DISPLAY_RESULTS|str_form_value}" class="bouton" />{/if}</p></td>
		</tr>
	</table>
</form>	
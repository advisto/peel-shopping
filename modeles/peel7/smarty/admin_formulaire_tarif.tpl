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
// $Id: admin_formulaire_tarif.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<p>{$STR_ADMIN_TARIFS_CONFIG_STATUS}<b><a href="sites.php">{if $mode_transport == 1}{$STR_ADMIN_ACTIVATED}{else}{$STR_ADMIN_DEACTIVATED} {"=>"|htmlspecialchars} {$STR_ADMIN_TARIFS_CONFIG_DEACTIVATED_COMMENT}{/if}</a></b></p>
<form method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_TARIFS_FORM_TITLE}</td>
		</tr>
		<tr>
			<td width="250">{$STR_SHIPPING_ZONE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select name="zone">
				{foreach $zones_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
				{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$STR_SHIPPING_TYPE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select name="type">
				{foreach $type_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
				{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_TARIFS_MINIMAL_WEIGHT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="poidsmin" style="width:100px" value="{$poidsmin|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_TARIFS_MAXIMAL_WEIGHT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="poidsmax" style="width:100px" value="{$poidsmax|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_TARIFS_MINIMAL_TOTAL} ({$site_symbole} {$STR_TTC}){$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="totalmin" style="width:100px" value="{$totalmin|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_TARIFS_MAXIMAL_TOTAL} ({$site_symbole} {$STR_TTC}){$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="totalmax" style="width:100px" value="{$totalmax|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_TARIF} ({$site_symbole} {$STR_TTC}){$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="tarif" style="width:100px" value="{$tarif|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_VAT_PERCENTAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select name="tva">{$vat_select_options}</select>
			</td>
		</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="bouton" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
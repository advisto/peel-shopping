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
// $Id: admin_formulaire_zone.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<form method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_ZONES_FORM_TITLE}</td>
		</tr>
		{foreach $langs as $l}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_LANGUAGES_SECTION_HEADER} {$l.lng|upper}</td></tr>
		<tr>
			<td class="label">{$STR_ADMIN_NAME} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:460px" type="text" name="nom_{$l.lng}" value="{$l.nom|str_form_value}" /></td>
   	 	</tr>
		{/foreach}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</td></tr>
		<tr>
			<td>{$STR_ADMIN_ZONES_DOES_VAT_APPLY_IN_ZONE}</td>
			<td><input type="checkbox" name="tva" value="1"{if !empty($tva)} checked="checked"{/if} /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_ZONES_DELIVERY_COSTS_IN_ZONE}<br /></td>
			<td><input type="checkbox" name="on_franco" value="1"{if !empty($on_franco)} checked="checked"{/if} /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="global_help">{$STR_ADMIN_ZONES_DELIVERY_COSTS_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_ZONES_FRANCO_LIMIT_AMOUNT}<br /></td>
			<td><input type="text" name="on_franco_amount" value="{$on_franco_amount|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="global_help">{$STR_ADMIN_ZONES_FRANCO_LIMIT_AMOUNT_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_ZONES_FRANCO_LIMIT_PRODUCTS}<br /></td>
			<td><input type="text" name="on_franco_nb_products" value="{$on_franco_nb_products|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="global_help">{$STR_ADMIN_ZONES_FRANCO_LIMIT_PRODUCTS_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_POSITION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="number" name="position" value="{$position|str_form_value}" /></td>
		</tr>
		</tr>
		{if $is_fianet_module_active}
		<tr>
			<td colspan="2"><div class="global_help">{$STR_ADMIN_ZONES_TECHNICAL_CODE_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_TECHNICAL_CODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="technical_code" value="{$technical_code|str_form_value}" /></td>
		</tr>
		{/if}
		<tr>
		<tr>
			<td class="center" colspan="2"><p><input class="bouton" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
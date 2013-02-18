{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_pays.tpl 35064 2013-02-08 14:16:40Z gboussin $
*}<form method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_PAYS_ADD_COUNTRY}</td>
		</tr>
		{foreach $langs as $l}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_LANGUAGES_SECTION_HEADER} {$l.lng|upper}</td></tr>
		<tr>
			<td class="label">{$STR_COUNTRY} {$l.lng|upper}:</td>
			<td><input style="width:460px" type="text" name="pays_{$l.lng}" value="{$l.pays|str_form_value}" /></td>
		</tr>
		{/foreach}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_PAYS_ISO_CODES_HEADER}</td></tr>
		<tr>
			<td class="label">{$STR_ADMIN_PAYS_ISO_2}</td>
			<td><input style="width:45px" type="text" name="iso" value="{$iso|str_form_value}" /></td>
		</tr>
		<tr>
			<td class="label">{$STR_ADMIN_PAYS_ISO_3}</td>
			<td><input style="width:45px" type="text" name="iso3" value="{$iso3|str_form_value}" /></td>
		</tr>
		<tr>
			<td class="label">{$STR_ADMIN_PAYS_ISO_NUMERIC}</td>
			<td><input style="width:45px" type="text" name="iso_num" value="{$iso_num|str_form_value}" /></td>
		</tr>
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</td></tr>
		<tr>
			<td class="label">{$STR_STATUS}</td>
			<td>
			 	<input type="radio" name="etat" value="1"{if $etat == '1'} checked="checked"{/if} /> {$STR_YES}&nbsp;
				<input type="radio" name="etat" value="0"{if $etat == '0' OR empty($etat)} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td class="label">{$STR_SHIPPING_ZONE}</td>
			<td>
				<select name="zone">
				{foreach $options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
				{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td class="label">{$STR_ADMIN_POSITION}</td>
			<td><input type="text" name="position" style="width:100%" value="{$position|str_form_value}" /></td>
		</tr>
		<tr><td colspan="2" class="center"><p><input class="bouton" type="submit" value="{$titre_bouton|str_form_value}" /></p></td></tr>
	</table>
</form>
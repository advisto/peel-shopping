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
// $Id: admin_formulaire_ecotaxes.tpl 37943 2013-08-29 09:31:55Z gboussin $
*}<form method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table cellpadding="5" class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_ECOTAXES_FORM_TITLE}</td>
		</tr>
		<tr>
			<td width="150">{$STR_ADMIN_CODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="code" style="width:100%" value="{$code|str_form_value}" /></td>
		</tr>
		{foreach $langs as $l}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_LANGUAGES_SECTION_HEADER} {$l.lng|upper}</td></tr>
		<tr>
			<td class="label">{$STR_ADMIN_NAME} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" name="nom_{$l.lng}" value="{$l.nom|str_form_value}" /></td>
   	 	</tr>
		{/foreach}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</td></tr>
		<tr>
			<td>{$STR_PRICE} {$STR_HT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="prix" style="width:100%" value="{$prix_ht|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_TAXE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><select name="taxes">{$vat_options}</select></td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p><input class="bouton" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
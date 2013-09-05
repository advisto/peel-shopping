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
// $Id: admin_formulaire_taille.tpl 37943 2013-08-29 09:31:55Z gboussin $
*}<form method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_TAILLES_FORM_TITLE}</td>
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
			<td class="label">{$STR_ADMIN_TAILLES_OVERWEIGHT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="poids" style="width:100px" value="{$poids|str_form_value}" /> {$STR_ADMIN_GRAMS}</td>
		</tr>
		<tr>
			<td class="label">{$STR_ADMIN_TAILLES_OVERCOST}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="prix" style="width:100px" value="{$prix|str_form_value}" /> <b>{$site_symbole} {$STR_TTC}</b></td>
		</tr>

		<tr>
			<td class="label">{$STR_ADMIN_TAILLES_OVERCOST_RESELLER}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="prix_revendeur" style="width:100px" value="{$prix_revendeur|str_form_value}" /> <b>{$site_symbole} {$STR_TTC}</b></td>
		</tr>
		<tr>
			<td class="label">{$STR_ADMIN_TAILLES_SIGN}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><select name="signe"><option value="+"{if $signe == "+"} selected="selected"{/if}>+</option><option value="-"{if $signe == "-"} selected="selected"{/if}>-</option></select></td></tr>
		<tr>
			<td class="label">{$STR_ADMIN_POSITION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="number" name="position" value="{$position|str_form_value}" /></td>
   	 	</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="bouton" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
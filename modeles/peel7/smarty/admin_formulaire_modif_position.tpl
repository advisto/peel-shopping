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
// $Id: admin_formulaire_modif_position.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<form method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="positionner" />
	<input type="hidden" name="catid" value="{$catid|str_form_value}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="5">{$STR_ADMIN_POSITIONS_FORM_EXPLAIN} {$category_name}</td>
		</tr>
		<tr class="bloc">
			<td class="center menu">{$STR_PRODUCT}</td>
			<td class="menu center">{$STR_PRICE}</td>
			<td class="menu center">{$STR_ADMIN_POSITION}</td>
		</tr>
{foreach $results as $res}
		{$res.tr_rollover}
			<td class="center"><input type="hidden" name="id[]" value="{$res.value|str_form_value}" /><a href="{$res.modif_href|escape:'html'}">{$res.name}</a></td>
			<td class="center">{$res.prix}</td>
			<td class="center"><input type="text" name="position[]" size="2" value="{$res.position|str_form_value}" /></td>
		</tr>
{/foreach}
		<tr><td colspan="3" align="center"><p><input type="submit" value="{$STR_ADMIN_POSITIONS_POSITION_PRODUCTS|str_form_value}" class="bouton" /></p></td></tr>
	</table>
</form>
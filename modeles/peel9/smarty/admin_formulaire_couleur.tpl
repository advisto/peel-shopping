{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_couleur.tpl 60372 2019-04-12 12:35:34Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_COULEURS_FORM_TITLE}</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}: </td>
			<td>
				<select class="form-control" name="site_id">
					{$site_id_select_options}
				</select>
			</td>
		</tr>
		{foreach $langs as $l}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_LANGUAGES_SECTION_HEADER} - {$lang_names[$l.lng]|upper}</h2></td></tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_NAME} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" name="nom_{$l.lng}" value="{$l.nom|str_form_value}" /></td>
   	 	</tr>
		{/foreach}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</h2></td></tr>
		<tr>
			<td class="title_label">{$STR_PRICE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="prix" value="{$prix|str_form_value}" /><b> {$site_symbole} {$STR_TTC}</b></td>
 		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_RESELLER_PRICE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="prix_revendeur" value="{$prix_revendeur|str_form_value}" /><b> {$site_symbole} {$STR_TTC}</b></td>
 		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_PRIX_POURCENTAGE_ENTER_PERCENTAGE_PRODUCT_PRICE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="percent" value="{$percent|str_form_value}" /> %</td>
 		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_POSITION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="number" class="form-control" name="position" value="{$position|str_form_value}" /></td>
 		</tr>
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_FILE_NAME}</td></tr>
		<td class="title_label">{$STR_IMAGE}{$STR_BEFORE_TWO_POINTS}:</td>
		<tr>
			<td>
				{if isset($image)}
				<img src="{$image.src|escape:'html'}" /><br />
					{$STR_ADMIN_FILE_NAME}{$STR_BEFORE_TWO_POINTS}: {$image.nom}&nbsp;
				<a href="{$image.drop_href|escape:'html'}"><img src="{$image.drop_src|escape:'html'}" width="16" height="16" alt="" />{$STR_ADMIN_DELETE_IMAGE}</a>
				<input type="hidden" name="image" value="{$image.nom|str_form_value}" />
				{else}
				<input name="image" type="file" value="" />
				{/if}
			</td>
		</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="btn btn-primary" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
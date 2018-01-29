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
// $Id: attributsAdmin_formulaire_nom.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_MODULE_ATTRIBUTS_ADMIN_UPDATE_TITLE}</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}: </td>
			<td>
				<select class="form-control" name="site_id">
					{$site_id_select_options}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="etat" value="1" {if $etat == '1'} checked="checked"{/if} /> {$STR_ADMIN_ACTIVATED}
				<input type="radio" name="etat" value="0" {if $etat == '0' OR empty($etat)} checked="checked"{/if} /> {$STR_ADMIN_DEACTIVATED}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_MANDATORY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="mandatory" value="1" {if $mandatory == '1'} checked="checked"{/if} /> {$STR_YES} 
				<input type="radio" name="mandatory" value="0" {if $mandatory != '1'} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		{foreach $langs as $lng}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_LANGUAGES_SECTION_HEADER} - {$lang_names[$lng.code]|upper}</h2></td></tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_NAME} {$lng.code|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" name="nom_{$lng.code}" value="{$lng.value|str_form_value}" /></td>
   	 	</tr>
		{/foreach}
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_MODULE_ATTRIBUTS_ADMIN_PARAMETERS}</h2></td>
		</tr>
		<tr>
			<td><label for="texte_libre_attribute">{$STR_ADMIN_TECHNICAL_CODE}{$STR_BEFORE_TWO_POINTS}:</label></td>
			<td><input type="text" class="form-control" name="technical_code" value="{$technical_code|str_form_value}"></td>
		</tr>
		<tr>
			<td>{$STR_TYPE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" id="texte_libre_attribute" name="attribut_type" value="0" {if $texte_libre == '0' OR empty($texte_libre)} checked="checked"{/if} /> <label for="texte_libre_attribute">{$STR_MODULE_ATTRIBUTS_ADMIN_OPTIONS_LIST_ATTRIBUTE}</label><br />
				<input type="radio" id="texte_non_libre_attribute" name="attribut_type" value="1" {if $texte_libre == '1' && $upload == '0'} checked="checked"{/if} /> <label for="texte_non_libre_attribute">{$STR_MODULE_ATTRIBUTS_ADMIN_FREE_TEXT_ATTRIBUTE}</label><br />
				<input type="radio" id="upload_attribute" name="attribut_type" value="2" {if $upload == '1'} checked="checked"{/if} /> <label for="upload_attribute">{$STR_MODULE_ATTRIBUTS_ADMIN_UPLOAD_ATTRIBUTE}</label>
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_ATTRIBUTS_ADMIN_DISPLAY_MODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="type_affichage_attribut" value="0" {if $type_affichage_attribut == '0'} checked="checked"{/if} /> {$STR_MODULE_ATTRIBUTS_ADMIN_SELECT_MENU}
				<input type="radio" name="type_affichage_attribut" value="1" {if $type_affichage_attribut == '1'} checked="checked"{/if} /> {$STR_MODULE_ATTRIBUTS_ADMIN_RADIO_BUTTONS}
				<input type="radio" name="type_affichage_attribut" value="2" {if $type_affichage_attribut == '2'} checked="checked"{/if} /> {$STR_MODULE_ATTRIBUTS_ADMIN_CHECKBOX}
				<input type="radio" name="type_affichage_attribut" value="4" {if $type_affichage_attribut == '4'} checked="checked"{/if} /> {$STR_ADMIN_ATTRIBUT_STYLE_LINK}
				<input type="radio" name="type_affichage_attribut" value="3" {if $type_affichage_attribut == '3'} checked="checked"{/if} /> {$STR_MODULE_ATTRIBUTS_ADMIN_DEFAULT_DISPLAY_MODE}
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_ATTRIBUTS_ADMIN_NO_PROMOTION_OPTION_ATTRIBUT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="checkbox" name="disable_reductions" value="1" {if $disable_reductions} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p><input type="hidden" name="show_description" value="{$show_description|str_form_value}"><input class="btn btn-primary" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
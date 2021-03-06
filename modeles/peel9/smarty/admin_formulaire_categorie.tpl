{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_categorie.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{if isset($cat_href)}{$STR_ADMIN_CATEGORIES_FORM_MODIFY} "{$nom}" - <a href="{$cat_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_ADMIN_SEE_RESULT_IN_REAL}</a>{else}{$STR_ADMIN_CATEGORIES_FORM_ADD_BUTTON}{/if}</td>
		</tr>
		<tr>
			<td class="title_label" colspan="2"></td>
		</tr>
		<tr>
			<td class="top">{$STR_ADMIN_CATEGORIES_PARENT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" name="parent_id" style="width:100%" size="20">
					<option value="0"{if $issel_parent_zero} selected="selected"{/if}>{$STR_ADMIN_AT_ROOT}</option>
					{$categorie_options}
				</select>
			</td>
		</tr>
		<tr>
			<td class="top">{$STR_ADMIN_TECHNICAL_CODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" name="technical_code" value="{$technical_code|str_form_value}" /></td>
		</tr>
		<tr>
			<td class="top">{$STR_ADMIN_DISPLAY_ON_HOMEPAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="checkbox" name="on_special" value="1"{if $is_on_special} checked="checked"{/if} /></td>
		</tr>
		{if $is_carrousel_module_active}
		<tr>
			<td class="top">{$STR_ADMIN_CATEGORIES_DISPLAY_IN_CARROUSEL}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="checkbox" name="on_carrousel" value="1"{if $is_on_carrousel} checked="checked"{/if} /></td>
		</tr>
		<tr>
			<td class="top">{$STR_ADMIN_CARROUSEL_CATEGORY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" name="carrousel_id" size="5">
					<option value="">----</option>
					{foreach $carrousel_list as $cl}
					<option value="{$cl.value}" {if $cl.issel} selected="selected"{/if}>{$cl.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		{/if}
		{if $cart_force_exapaq_delivery_mode}
		<tr>
			<td class="top">{$STR_ADMIN_SELECT_ICIRELAIS_SHIPPING}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="on_exapaq_delivery" value="1" {if $on_exapaq_delivery == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="on_exapaq_delivery" value="0" {if $on_exapaq_delivery == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		{/if}
		<tr>
			<td>{$STR_ADMIN_POSITION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="number" class="form-control" name="position" value="{$position|str_form_value}" /></td>
		</tr>
		{if isset($poids)}
		<tr>
			<td>{$STR_ADMIN_PRODUITS_WEIGHT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" name="poids" value="{$poids|str_form_value}" /></td>
		</tr>
		{/if}
		<tr>
			<td>{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" {if $site_id_select_multiple} name="site_id[]" multiple="multiple" size="5"{else} name="site_id"{/if}>
					{$site_id_select_options}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="etat" value="1"{if $etat == '1'} checked="checked"{/if} /> {$STR_ADMIN_ONLINE}<br />
				<input type="radio" name="etat" value="0"{if $etat == '0' OR empty($etat)} checked="checked"{/if} /> {$STR_ADMIN_OFFLINE}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_CATEGORIES_DISPLAY_MODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="type_affichage" value="0"{if $type_affichage == '0'} checked="checked"{/if} /> {$STR_ADMIN_IN_COLUMNS}<br />
				<input type="radio" name="type_affichage" value="1"{if $type_affichage == '1'} checked="checked"{/if} /> {$STR_ADMIN_IN_LINES}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_ZONES_FREE_DELIVERY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" name="franco" value="{$franco|str_form_value}" /></td>
		</tr>
		{foreach $langs as $l}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_LANGUAGES_SECTION_HEADER} - {$lang_names[$l.lng]|upper}</h2></td></tr>
		<tr>
			<td class="title_label" colspan="2">{$STR_ADMIN_NAME} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><input style="width:100%" type="text" class="form-control" name="nom_{$l.lng}" value="{$l.nom|str_form_value}" /></td>
		</tr>
		<tr>
			<td class="title_label" colspan="2">{$STR_ADMIN_NAME_SHORT} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><input style="width:100%" type="text" class="form-control" name="nom_court_{$l.lng}" value="{$l.nom_court|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_DESCRIPTION} {$l.lng|upper}:</td>
		</tr>
		<tr>
			<td colspan="2">{$l.description_te}</td>
		</tr>
		<tr>
			<td class="title_label" colspan="2">{$STR_ADMIN_META_TITLE} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" class="form-control" name="meta_titre_{$l.lng}" size="70" value="{$l.meta_titre|str_form_value}" /></td>
		</tr>
		<tr>
			<td class="title_label" colspan="2">{$STR_ADMIN_META_KEYWORDS} {$l.lng|upper} ({$STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN}){$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="form-control" name="meta_key_{$l.lng}" style="width:100%" rows="2" cols="54">{$l.meta_key|nl2br_if_needed|html_entity_decode_if_needed|strip_tags}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_META_DESCRIPTION} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="form-control" name="meta_desc_{$l.lng}" style="width:100%" rows="3" cols="54">{$l.meta_desc|nl2br_if_needed|html_entity_decode_if_needed|strip_tags}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_HEADER_HTML_TEXT}</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea class="form-control" style="width:100%; height:150px;" id="header_html_{$l.lng}" name="header_html_{$l.lng}" rows="10" cols="54">{$l.header_html|html_entity_decode_if_needed}</textarea>
			</td>
 	 	</tr>
		{if $enable_categorie_sentence_displayed_on_product}
		<tr>
			<td class="top">{$STR_ADMIN_SENTENCE_DISPLAYED_ON_PRODUCT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" name="sentence_displayed_on_product_{$l.lng}" value="{$l.sentence_displayed_on_product|str_form_value}" /></td>
		</tr>
		{/if}
		<tr>
			<td>{$STR_IMAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
		{if !empty($l.image)}
				{include file="uploaded_file.tpl" f=$l.image STR_DELETE=$STR_DELETE_THIS_FILE}
		{else}
				<input name="image_{$l.lng}" type="file" value="" />
		{/if}
			</td>
		</tr>
		<tr>
			<td>{$STR_IMAGE_HEADER}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
		{if !empty($l.image_header)}
				{include file="uploaded_file.tpl" f=$l.image_header STR_DELETE=$STR_DELETE_THIS_FILE}
		{else}
				<input name="image_header_{$l.lng}" type="file" value="" />
		{/if}
			</td>
		</tr>
		{/foreach}
	{if $is_category_promotion_module_active || $is_lot_module_active}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</h2></td></tr>
		{if $is_category_promotion_module_active}
		<tr>
			<td class="title_label">{$STR_ADMIN_CATEGORIES_DISCOUNT_IN_CATEGORY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="promotion_devises" value="{$promotion_devises|str_form_value}" /> {$site_symbole} {$STR_TTC}
				<input style="width:100px" type="text" class="form-control" name="promotion_percent" value="{$promotion_percent|str_form_value}" />%
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_CATEGORIES_DISCOUNT_APPLY_TO_SONS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="on_child" value="1"{if $on_child == '1'} checked="checked"{/if} /> {$STR_YES} - {$STR_ADMIN_CATEGORIES_DISCOUNT_APPLY_TO_SONS_EXPLAIN}<br />
				<input type="radio" name="on_child" value="0"{if $on_child == '0'} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		{/if}
		{if $is_lot_module_active}
			{if $mode == "maj"}
			<tr>
				<td colspan="2" class="title_label">{$STR_ADMIN_CATEGORIES_LOT_PRICE}{$STR_BEFORE_TWO_POINTS}:</td>
			</tr>
			<tr>
				<td class="title_label">{$lot_explanation_table}</td>
			</tr>
			<tr>
				<td class="title_label">
					<a href="{$lot_href|escape:'html'}">{$STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE}</a>
					{if isset($lot_supprime_href)}
					/ <a href="{$lot_supprime_href|escape:'html'}" data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}">{$STR_DELETE}</a>
					{/if}
				</td>
			</tr>
			{else}
			<tr>
				<td colspan="2"><div class="alert alert-info"><p>{$STR_ADMIN_CATEGORIES_LOT_PRICE_HANDLE_EXPLAIN}</p></div></td>
			</tr>
			{/if}
		{/if}
	{/if}
		<tr>
			<td colspan="2" class="top bloc">{$STR_ADMIN_CUSTOMIZE_APPEARANCE}</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_BACKGROUND_COLOR}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input style="width:100%" type="text" class="form-control" name="background_color" value="{$background_color|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_BACKGROUND_COLOR_FOR_MENU}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input style="width:100%" type="text" class="form-control" name="background_menu" value="{$background_menu|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p><input class="btn btn-primary" type="submit" value="{$titre_soumet|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
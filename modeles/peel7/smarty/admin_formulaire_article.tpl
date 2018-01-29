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
// $Id: admin_formulaire_article.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}{if !$rubrique_options}
<div class="entete">{$STR_ADMIN_ARTICLES_FORM_ADD}</div>
<p><a href="{$add_category_url}">{$STR_ADMIN_ARTICLES_CREATE_CATEGORY_FIRST}</a></p>
{else}
<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{if isset($art_href)}{$STR_ADMIN_ARTICLES_FORM_MODIFY} "{$titre}" - <a href="{$art_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_ADMIN_SEE_RESULT_IN_REAL}</a>{else}{$STR_ADMIN_ARTICLES_FORM_ADD}{/if}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</h2></td></tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_ARTICLES_CATEGORIE} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" name="rubriques[]" multiple="multiple" size="10" style="width: 100%">
				{$rubrique_options}
				</select>
				{$rubrique_error}
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="etat" value="1"{if $etat == '1'} checked="checked"{/if} /> {$STR_ADMIN_ONLINE}<br />
				<input type="radio" name="etat" value="0"{if $etat == '0' OR empty($etat)} checked="checked"{/if} /> {$STR_ADMIN_OFFLINE}
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" {if $site_id_select_multiple} name="site_id[]" multiple="multiple" size="5"{else} name="site_id"{/if}>
					{$site_id_select_options}
				</select>
			</td>
		</tr>
	{if !empty($STR_ADMIN_SITE_COUNTRY)}
		<tr>
			<td class="title_label">{$STR_ADMIN_SITE_COUNTRY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				{$site_country_checkboxes}
			</td>
		</tr>
	{/if}
		<tr>
			<td class="title_label">{$STR_ADMIN_POSITION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="number" class="form-control" name="position" value="{$position|html_entity_decode_if_needed|str_form_value}" />
			</td>
		</tr>
		{if $is_rollover_module_active}
			<tr>
				<td class="title_label top">{$STR_ADMIN_ARTICLES_IS_ON_ROLLOVER}{$STR_BEFORE_TWO_POINTS}:</td>
				<td><input type="checkbox" name="on_rollover" value="1"{if $is_on_rollover} checked="checked"{/if} /></td>
			</tr>
		{/if}
		<tr>
			<td class="title_label">{$STR_ADMIN_DISPLAY_ON_CONTENT_CATEGORY_PAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="on_special" value="1"{if $on_special == '1'} checked="checked"{/if} /> {$STR_YES}<br />
				<input type="radio" name="on_special" value="0"{if $on_special == '0' OR empty($on_special)} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_ARTICLES_IS_ON_RESELLER}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="on_reseller" value="1"{if $on_reseller == '1'} checked="checked"{/if} /> {$STR_YES}<br />
				<input type="radio" name="on_reseller" value="0"{if $on_reseller == '0' OR empty($on_reseller)} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td class="title_label" style="margin-top:5px;">{$STR_ADMIN_TECHNICAL_CODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="text" class="form-control" name="technical_code" value="{$technical_code|html_entity_decode_if_needed|str_form_value}" /><br />
			</td>
		</tr>
		<tr>
			<td class="title_label">{if isset($image1) && $image1.type == 'img'}{$STR_IMAGE}{else}{$STR_FILE}{/if}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
			{if isset($image1)}
				{include file="uploaded_file.tpl" f=$image1 STR_DELETE=$STR_DELETE_THIS_FILE}
			{else}
				<input name="image1" type="file" value="" />
			{/if}
			</td>
		</tr>
		{foreach $langs as $l}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_LANGUAGES_SECTION_HEADER} - {$lang_names[$l.lng]|upper}</h2></td></tr>
		<tr>
			<td colspan="2"><b>{$STR_ADMIN_TITLE} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</b></td>
		</tr>
		<tr>
			<td colspan="2"><input style="width:100%" type="text" class="form-control" name="titre_{$l.lng}" value="{$l.titre|html_entity_decode_if_needed|str_form_value}" />{$l.error}</td>
		</tr>
		<tr>
			<td class="title_label" colspan="2">{$STR_ADMIN_ARTICLE_SHORT_DESCRIPTION}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2">{$l.chapo_te}</td>
		</tr>
		<tr>
			<td class="title_label" colspan="2">{$STR_ADMIN_ARTICLES_COMPLETE_TEXT}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2">{$l.texte_te}</td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_META_TITLE} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" class="form-control" name="meta_titre_{$l.lng}" size="70" value="{$l.meta_titre|html_entity_decode_if_needed|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_META_KEYWORDS} {$l.lng|upper} ({$STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN}){$STR_BEFORE_TWO_POINTS}:</td>
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
		{/foreach}
		<tr>
			<td colspan="2" class="center"><p><input class="btn btn-primary" type="submit" value="{$normal_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
{/if}
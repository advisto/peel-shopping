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
// $Id: bannerAdmin_formulaire_banniere.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<form method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$title}</td>
		</tr>
		<tr>
			<td>{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
			  <input type="radio" name="etat" value="1"{if $etat == "1"} checked="checked"{/if} /> {$STR_ADMIN_ONLINE}<br />
			  <input type="radio" name="etat" value="0"{if $etat == "0" OR empty($etat)} checked="checked"{/if} /> {$STR_ADMIN_OFFLINE}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_DESCRIPTION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="description" style="width:100%" value="{$description|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_LINK}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="url" name="lien" style="width:100%" placeholder="http://" value="{$lien|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_EXTRA_JAVASCRIPT}{$STR_BEFORE_TWO_POINTS}: <br /></td>
			<td><textarea name="extra_javascript" style="width:60%" rows="10" cols="54">{$extra_javascript}</textarea></td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_TAG_HTML}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><textarea name="tag_html" style="width:60%" rows="10" cols="54">{$tag_html}</textarea></td>
		</tr>
		<tr>
			<td colspan="2"><div class="global_help">{$STR_MODULE_BANNER_ADMIN_TAG_HTML_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_TARGET}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select name="target">
					<option value="_self"{if $target == "_self"} selected="selected"{/if}>{$STR_MODULE_BANNER_ADMIN_TARGET_SELF}</option>
					<option value="_blank"{if $target == "_blank"} selected="selected"{/if}>{$STR_MODULE_BANNER_ADMIN_TARGET_BLANK}</option>
					<option value="_top"{if $target == "_top"} selected="selected"{/if}>{$STR_MODULE_BANNER_ADMIN_TARGET_TOP}</option>
					<option value="_parent"{if $target == "_parent"} selected="selected"{/if}>{$STR_MODULE_BANNER_ADMIN_TARGET_PARENT}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_PLACE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="number" name="position" style="width:150px" value="{$position|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="global_help">{$STR_MODULE_BANNER_ADMIN_PLACE_EXPLAIN}</div>
				{$banner_help}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_POSITION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="text" name="rang" style="width:150px" value="{$rang|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="global_help">{$STR_MODULE_BANNER_ADMIN_POSITION_EXPLAIN}</div>
			</td>
		</tr>
	{if $is_annonce_module_active}
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_AD_PLACE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="number" name="annonce_number" style="width:150px" value="{$annonce_number|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_AD_ID}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="number" name="list_id" style="width:150px" value="{$list_id}" />
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_ODD_EVEN_ALL}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="pages_allowed" value="all" {if $pages_allowed == 'all'} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_ODD_EVEN_ODD}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="pages_allowed" value="odd" {if $pages_allowed == 'odd'} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_ODD_EVEN_EVEN}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="pages_allowed" value="even" {if $pages_allowed == 'even'} checked="checked"{/if} />
			</td>
		</tr>
	{/if}
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_START_PUBLICATION_DATE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="date_debut" style="width:150px" class="datepicker" value="{$date_debut|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_END_PUBLICATION_DATE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="text" name="date_fin" style="width:150px" class="datepicker" value="{$date_fin|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="global_help">{$STR_MODULE_BANNER_ADMIN_DATES_EXPLAIN}</div>
			</td>
		</tr>
		<tr>
			<td>{$STR_CATEGORY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select name="id_categorie">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $cat_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="global_help">{$STR_MODULE_BANNER_ADMIN_SPACE_EXPLAIN}</div>
			</td>
		</tr>
	{if $is_annonce_module_active}
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_ON_AD_PAGE_DETAILS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="checkbox" name="on_ad_page_details" value="1" {if $on_ad_page_details} checked="checked"{/if} />
			</td>
		</tr>
	{/if}
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_ON_FIRST_PAGE_CATEGORY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="checkbox" name="on_first_page_category" value="1" {if $on_first_page_category} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_ON_OTHER_PAGE_CATEGORY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="checkbox" name="on_other_page_category" value="1" {if $on_other_page_category} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_ON_HOME_PAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="checkbox" name="on_home_page" value="1" {if $on_home_page} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_ON_OTHER_PAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="checkbox" name="on_other_page" value="1" {if $on_other_page} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_ON_SEARCH_ENGINE_PAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="checkbox" name="on_search_engine_page" value="1" {if $on_search_engine_page} checked="checked"{/if} />
			</td>
		</tr>
		<tr>
			<td colspan="2">{$STR_MODULE_BANNER_ADMIN_IMAGE_OR_FLASH}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2">
			{if isset($image)}
				{if isset($image.swf)}
					{$image.swf}
				{else}
					<img src="{$image.src|escape:'html'}" />
				{/if}
				<br />
				{$STR_ADMIN_FILE_NAME}{$STR_BEFORE_TWO_POINTS}:{$image.nom}&nbsp;
				<a href="{$image.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" width="16" height="16" alt="" />{$STR_ADMIN_DELETE_IMAGE}</a>
				<input type="hidden" name="image" value="{$image.nom|str_form_value}" />
			{else}
				<input style="width: 100%" name="image" type="file" value="" />
			{/if}
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_WIDTH}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="number" name="width" style="width:150px" value="{$width|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_MODULE_BANNER_ADMIN_HEIGHT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="number" name="height" style="width:150px" value="{$height|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="global_help">{$STR_MODULE_BANNER_ADMIN_SIZE_EXPLAIN}</div>
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_LANGUAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" name="lang" style="width:100%" value="{$lang|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p><input class="bouton" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>
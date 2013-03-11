{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: search_form.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<form class="search_form" action="{$action|escape:'html'}" method="get">
	<h2>{$STR_SEARCH_PRODUCT}</h2>
	<ul class="attribute_select_search attribute_select_search_part1">
		<li class="input">
			{$STR_SEARCH}{$STR_BEFORE_TWO_POINTS}: <input type="text" id="search_" name="search" size="48" value="{$value|str_form_value}" onclick="updateTextField('search_', '{$STR_ENTER_KEY|filtre_javascript:true:true:true}');" onblur="updateTextField('search_', '{$STR_ENTER_KEY|filtre_javascript:true:true:true}', 'blur');" />
			<select name="match">
				<option value="1"{if $match == 1}selected="selected"{/if}>{$STR_SEARCH_ALL_WORDS}</option>
				<option value="2"{if $match == 2}selected="selected"{/if}>{$STR_SEARCH_ANY_WORDS}</option>
				<option value="3"{if $match == 3}selected="selected"{/if}>{$STR_SEARCH_EXACT_SENTENCE}</option>
			</select>
		</li>
	</ul>
{if $is_advanced_search_active}
	<ul class="attribute_select_search attribute_select_search_part2">
	{if !$is_annonce_module_active}
		{if !empty($select_categorie)}
		<li class="attribute_categorie">
			 <select name="categorie">
				<option value="">{$STR_CAT_LB}</option>
				{$select_categorie}
			</select>
		</li>
		{/if}
		{foreach $select_attributes as $sa}
			{$sa}
		{/foreach}
		{$custom_attribute}
	{else}
		<li class="select_categorie_annonce">
			{$STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD}{$STR_BEFORE_TWO_POINTS}: <select name="cat_select">
				<option value="">{$STR_MODULE_ANNONCES_AD_CATEGORY}</option>
				{foreach $cat_ann_opts as $cao}
					<option value="{$cao.value|str_form_value}"{if $cao.issel} selected="selected"{/if}>{$cao.name}</option>
				{/foreach}
			</select>
		</li>
		<li class="select_type">
		{if $ads_contain_lot_sizes}
			<select name="cat_detail">
				<option value="">{$STR_TYPE}</option>
				<option value="gros"{if !empty($cat_detail) AND $cat_detail == 'gros'} selected="selected"{/if}>{$STR_MODULE_ANNONCES_OFFER_GROS}</option>
				<option value="demigros"{if !empty($cat_detail) AND $cat_detail == 'demigros'} selected="selected"{/if}>{$STR_MODULE_ANNONCES_OFFER_DEMIGROS}</option>
				<option value="detail"{if !empty($cat_detail) AND $cat_detail == 'detail'} selected="selected"{/if}>{$STR_MODULE_ANNONCES_OFFER_DETAIL}</option>
			</select>
		{/if}
			<input name="cat_statut" type="checkbox" value="1" {if !empty($cat_statut) AND $cat_statut == 1} checked="checked"{/if} />{$STR_MODULE_ANNONCES_ALT_VERIFIED_ADS}
		</li>
		{if !empty($ad_lang_select)}
		<li class="ad_lang">
			{$ad_lang_select}
		</li>
		{/if}
		<li class="input">
			{$STR_TOWN} / {$STR_ZIP}{$STR_BEFORE_TWO_POINTS}: <input type="text" id="city_zip" name="city_zip" size="60" value="{$city_zip|str_form_value}" />
		</li>
		<li class="select_country_annonce">{$STR_COUNTRY}{$STR_BEFORE_TWO_POINTS}:
			<select name="country">
				<option value="">{$STR_CHOOSE}...</option>
				{$country}
			</select>
			{foreach $continent_inputs as $c}
				<input type="checkbox" name="continent[]" value="{$c.value|str_form_value}"{if $c.issel} checked="checked"{/if} /> {$c.name}
			{/foreach}
		</li>
		{if !empty($near_position)}
		<li class="near_position">
			{$near_position}
		</li>
		{/if}
	{/if}
	</ul>
{/if}
	<div class="attribute_select_search attribute_select_search_part3">
		<input class="clicbouton" type="submit" value="{$STR_SEARCH|str_form_value}" />
	</div>
</form>
<br />
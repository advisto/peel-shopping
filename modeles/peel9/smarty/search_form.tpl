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
// $Id: search_form.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}
{if !empty($quick_add_product_from_search_page)}
	<h1 class="products_list_brief">{$LANG.STR_EASY_LIST}</h1>
	<form class="entryform form-inline search_form" action="{$wwwroot|escape:'html'}/achat/caddie_ajout.php?from=search_page" method="post" target="_blank">
		<table class="table admin_commande_details">
			<thead>
			<tr style="background-color:#EEEEEE;{if empty($produits_options)}display:none;{/if}" id="head_admin_commande_details">
			<td class="title_label" style="width:65px"></td>
			<th class="menu center">{$LANG.STR_PHOTO}</th>
			<th class="menu center">{$LANG.STR_REFERENCE} </th>
			<th class="menu center">{$LANG.STR_NAME}</th>
			{if $display_barcode}<th class="menu center">{$LANG.STR_BARCODE}</th>{/if}
			<th class="menu center">{$LANG.STR_BRAND} </th>
			<th class="menu center">{$LANG.STR_CATEGORY}</th>
			<th class="menu center">{$LANG.STR_PDF_PRIX_TTC}</th>
			<th class="menu center">{$LANG.STR_START_PRICE} TTC</th>
			<th class="menu center">{$LANG.STR_QUANTITY} </th>
			</thead>
			<tbody id="dynamic_order_lines">{foreach $produits_options as $o}
				<tr class="top" id="line{$o.i}"><td><span class="glyphicon glyphicon-remove-sign" title="{$LANG.STR_DELETE}" onclick="if(bootbox.confirm('{$LANG.STR_DELETE_PROD_CART|filtre_javascript:true:true:false}', function(result) {ldelim}if(result) {ldelim}delete_products_list_line({$o.i}, true, {$o.product_id});{rdelim} {rdelim} ))return false;" style="cursor:pointer"></span> </td><td class="center"> <img src="{$o.photo_src}" /> <input type="hidden" value="{$o.product_id}" name="produit_id[]"> </td> <td class="center">{$o.ref}</td> <td class="center"><a href="{$o.href_produit}" target="_blank">{$o.nom_produit}</a></td>{if $display_barcode}<td class="center"><img src="{$o.barcode_image_src}" /><br />{$o.ean_code}</td>{/if}<td class="center">{$o.brand_link_html}</td> <td class="center"><a href="{$o.href_category}" target="_blank">{$o.category_name}</a></td> <td class="center">{$o.prix}</td> <td class="center">{$o.minimal_price}</td><td class="center" id="display_quantity_{$o.id}"><input onchange="update_session_search_product_list(this.value, {$o.id}, 'update_session_add');" style="width: 35px;" type="text" value="{$o.quantite}" id="products_list_line_quantity_{$o.product_id}" name="qte[]"></td></tr>
				{/foreach}</tbody>
		</table>
		<input id="nb_produits" type="hidden" name="nb_produits" value="0" />
		<div id="product_list_submit" style="{if empty($produits_options)}display:none;{/if}">
			<div>{$LANG.STR_WHAT_DO_YOU_DO_WITH_THAT_LIST}</div>
			<br />
			<button type="submit" class="btn btn-primary" name="save_product_list_in_reminder">
				<img alt="{$LANG.STR_AJOUT_PENSE_BETE|str_form_value}" src="{$repertoire_images}/ajout_pense_bete.jpg"> {$LANG.STR_AJOUT_PENSE_BETE}
			</button>
			<button type="submit" class="btn btn-primary" name="add_cart">
				<span class="glyphicon glyphicon-shopping-cart"></span> {$LANG.STR_ADD_CART}
			</button>
			<button type="submit" class="btn btn-primary" name="export_pdf">
				<img style="height:25px;" src="{$wwwroot}/images/logoPDF_small.png" /> {$STR_MODULE_FACTURES_ADVANCED_EXPORT_LIST_PDF}
			</button>
			{if $search_product_list_save_cart}
			<input type="text" style="width:220px;" class="form-control" name="products_list_name" required placeholder="{$LANG.STR_NAME}" value="{$products_list_name|str_form_value}" />
			<button type="submit" class="btn btn-primary" name="save_product_list">
				<span class="glyphicon glyphicon-save"></span>{$LANG.STR_SAVE_CART} 
			</button>
			{/if}
		</div>
	</form>
	<label for="search_">{$STR_SCAN_CODE_AND_ADD_LIST|str_form_value}</label>
	<div class="scan_field_background">
		<input type="text" autocomplete="off" class="form-control" id="search_" name="search" size="48" value="{$value|str_form_value}" oninput="lookup(this.value, '{$id_utilisateur}', '{$zone_tva}', '{$devise}', '{$currency_rate}', 'search_product_list','#suggestions','search_product_list','{$rpc_path}');" onclick="lookup(this.value, '{$id_utilisateur}', '{$zone_tva}', '{$devise}', '{$currency_rate}', 'search_product_list','#suggestions','search_product_list','{$rpc_path}');" autofocus />
	</div>
	<div id="suggestions" class="alert alert-info">
		<p>{$LANG.STR_SCAN_HELP}</p>
	</div>
{else}
<form class="entryform form-inline search_form" action="{$action|escape:'html'}" method="get">
	{if $display == 'full'}<h2>{$STR_SEARCH} {$search}</h2>{/if}
	<ul class="attribute_select_search attribute_select_search_part1">
		<li class="input">
			{$STR_SEARCH}{$STR_BEFORE_TWO_POINTS}: <input type="text" class="form-control" id="search_advanced_input" name="search" size="48" value="{$value|str_form_value}" placeholder="{$STR_ENTER_KEY|str_form_value}" {if !empty($quick_add_product_from_search_page)} oninput="lookup(this.value, '{$id_utilisateur}', '{$zone_tva}', '{$devise}', '{$currency_rate}', 'search_product_list','#suggestions','search_product_list','{$rpc_path}');"{/if}/>
	{if !empty($match_display)}
			<select class="form-control" name="match" style="width:150px">
				<option value="1"{if $match == 1} selected="selected"{/if}>{$STR_SEARCH_ALL_WORDS}</option>
				<option value="2"{if $match == 2} selected="selected"{/if}>{$STR_SEARCH_ANY_WORDS}</option>
				<option value="3"{if $match == 3} selected="selected"{/if}>{$STR_SEARCH_EXACT_SENTENCE}</option>
			</select>
	{/if}
		</li>
{if !empty($STR_MODULE_ANNONCES_DATE_END_PAST)}
		<li>
			<input name="date_end[]" type="checkbox" value="future"{if $date_end_future} checked="checked"{/if}> <span>{$STR_MODULE_ANNONCES_DATE_END_FUTURE}</span><br />
			<input name="date_end[]" type="checkbox" value="past"{if $date_end_past} checked="checked"{/if}> <span>{$STR_MODULE_ANNONCES_DATE_END_PAST}</span><br />
			{if isset($all_sites)}
			<input name="all_sites[]" type="checkbox" value="1"{if $all_sites} checked="checked"{/if}> <span>{$STR_MODULE_DREAMTAKEOFF_SEARCH_ALL_SITES}</span>
			{/if}
		</li>
{/if}
	</ul>
{if $is_advanced_search_active}
	<ul class="attribute_select_search attribute_select_search_part2">
	{if !$is_annonce_module_active && $display != 'module_ads'}
		{if !empty($select_categorie)}
		<li class="attribute_categorie">
			 <select class="form-control" name="categorie">
				<option value="">{$STR_CAT_LB}</option>
				{$select_categorie}
			</select>
		</li>
		{/if}
		{foreach $select_attributes as $sa}
			{$sa}
		{/foreach}
		{$custom_attribute}
	{/if}
	{if $is_annonce_module_active && $display != 'module_products'}
		<li class="select_categorie_annonce">
			{$STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD}{$STR_BEFORE_TWO_POINTS}: 
			{if $search_form_category_display_mode == 'checkbox'}
			<div class="row">
				{foreach $cat_ann_opts as $cao}
				<div class="col-sm-4 col-lg-3">
					<input name="cat_select[]" type="checkbox" value="{$cao.value|str_form_value}"{if $cao.issel} checked="checked"{/if}> {$cao.name}
				</div>
				{/foreach}
			</div>
			{else}
			<select class="form-control" name="cat_select">
				<option value="">{$STR_MODULE_ANNONCES_AD_CATEGORY}</option>
				{foreach $cat_ann_opts as $cao}
					<option value="{$cao.value|str_form_value}"{if $cao.issel} selected="selected"{/if}>{$cao.name}</option>
				{/foreach}
			</select>
			{/if}
		</li>
	{if !empty($additional_fields_form)}
		<li class="search_additional_field_form">
			{$additional_fields_form}
		</li>			
	{/if}
		<li class="select_type">
		{if $ads_contain_lot_sizes}
			{$STR_TYPE}{$STR_BEFORE_TWO_POINTS}: 
			<select class="form-control" name="cat_detail">
				<option value="gros"{if !empty($cat_detail) && $cat_detail == 'gros'} selected="selected"{/if}>{$STR_MODULE_ANNONCES_OFFER_GROS}</option>
				<option value="demigros"{if !empty($cat_detail) && $cat_detail == 'demigros'} selected="selected"{/if}>{$STR_MODULE_ANNONCES_OFFER_DEMIGROS}</option>
				<option value="detail"{if !empty($cat_detail) && $cat_detail == 'detail'} selected="selected"{/if}>{$STR_MODULE_ANNONCES_OFFER_DETAIL}</option>
			</select>
		{/if}
			{if $display == 'full' && empty($user_verified_status_disable)}<input name="cat_statut" type="checkbox" value="1" {if !empty($cat_statut) && $cat_statut == 1} checked="checked"{/if} /> {$STR_MODULE_ANNONCES_ALT_VERIFIED_ADS}{/if}
		</li>
		{if $display == 'full'}
			{if !empty($ad_lang_select)}
			<li class="ad_lang">
				{$ad_lang_select}
			</li>
			{/if}
			{if !empty($display_city_zip)}
			<li class="input">
				{$STR_TOWN} / {$STR_ZIP}{$STR_BEFORE_TWO_POINTS}: <input type="text" class="form-control"  id="city_zip" name="city_zip" size="60" value="{$city_zip|str_form_value}" />
			</li>
			{/if}
			{if !empty($display_location)}
			<li class="input">
				{$STR_LOCATION}{$STR_BEFORE_TWO_POINTS}: {$display_location}
			</li>
			{/if}
			{if !empty($country) || !empty($continent_inputs)}
			<li class="select_country_annonce">{$STR_COUNTRY}{$STR_BEFORE_TWO_POINTS}:
				{if !empty($country)}
				<select class="form-control" name="country">
					<option value="">{$STR_CHOOSE}...</option>
					{$country}
				</select>
				{/if}
				{if !empty($continent_inputs)}
					{foreach $continent_inputs as $c}
						<input type="checkbox" name="continent[]" value="{$c.value|str_form_value}"{if $c.issel} checked="checked"{/if} /> {$c.name}
					{/foreach}
				{/if}
			</li>
			{/if}
			{if !empty($near_position)}
			<li class="near_position">
				{$near_position}
			</li>
			{/if}
		{/if}
	{/if}
	</ul>
{/if}
	<div class="attribute_select_search attribute_select_search_part3">
		<input class="btn btn-primary" type="submit" value="{$STR_SEARCH|str_form_value}" />
	</div>
</form>
	{if !empty($display_save_search_button)}
		{$display_save_search_button}
	{/if}
<br />
{/if}
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
// $Id: search_result.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{if $is_annonce_module_active}
	{if !empty($res_affiche_annonces)}
<h1 property="name" class="search_result">{if !empty($search)}{$search|strtoupper}{else}{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_MODULE_ANNONCES_SEARCH_RESULT_ADS}{/if} - {$ads_found} {$STR_MODULE_ANNONCES_ADS} {if !empty($STR_AT_LEAST_ONE_CAMPAIGN)}{$STR_AT_LEAST_ONE_CAMPAIGN}{/if}</h1>
{$res_affiche_annonces}
	{elseif $page<1 && empty($result_affichage_produit)}
<h1 property="name" class="search_result">{if !empty($search)}{$search|strtoupper}{else}{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_MODULE_ANNONCES_SEARCH_RESULT_ADS}{/if}</h1>
{if empty($arts_found) && empty($brands_found)}<div>{$STR_MODULE_ANNONCES_SEARCH_NO_RESULT_ADS}</div><br />{/if}
	{/if}
{/if}
{if !$is_annonce_module_active}
	{if !empty($result_affichage_produit)}
<h1 property="name" class="search_result">{if !empty($search)}{$search|strtoupper}{else}{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_PRODUCT}{/if} - {$products_found} {$STR_PRODUCTS}</h1>
{$result_affichage_produit} 
	{elseif $page<1 && empty($res_affiche_annonces) &&  (!empty($search) || isset($result_affichage_produit))}
<h1 property="name" class="search_result">{if !empty($search)}{$search|strtoupper}{else}{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_PRODUCT}{/if}</h1>
{if isset($result_affichage_produit) && empty($arts_found) && empty($brands_found)}<div>{$STR_SEARCH_NO_RESULT_PRODUCT}</div><br />{/if}
	{/if}
{/if}
{if !empty($are_terms)}
	{if !empty($arts_found)}
<h2 class="search_result">{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_ARTICLE} - {$arts_found|@count} {$STR_ARTICLES}</h2>
		{foreach $arts_found as $art}
<p>
	<b>{$art.num}. <a href="{$art.category_href|escape:'html'}">{$art.rubrique|html_entity_decode_if_needed}</a></b> {if !empty($art.content_href)}- <a href="{$art.content_href|escape:'html'}">{$art.titre|html_entity_decode_if_needed}</a>{/if}<br />
	{$art.texte}
</p>
		{/foreach}
	{elseif isset($arts_found) && $page<1 && (!$is_annonce_module_active || ($is_annonce_module_active && $search_in_product_and_ads)) && empty($res_affiche_annonces) && empty($result_affichage_produit) && empty($brands_found)}
<h2 class="search_result">{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_ARTICLE}</h2>
<div>{$STR_SEARCH_NO_RESULT_ARTICLE}</div><br />
	{/if}
	{if !empty($brands_found)}
<h2 class="search_result">{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_BRAND} - {$brands_found|@count} {$STR_BRANDS}</h2>
		{foreach $brands_found as $brand}
<p>
	<b>{$brand.num}. <a href="{$brand.href|escape:'html'}">{$brand.nom|html_entity_decode_if_needed}</a></b> - {$brand.description|html_entity_decode_if_needed}
</p>
		{/foreach}
	{elseif isset($brands_found) && $page<1 && !$is_annonce_module_active && empty($res_affiche_annonces) && empty($result_affichage_produit) && empty($arts_found)}
<h2 class="search_result">{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_BRAND}</h2>
<div>{$STR_SEARCH_NO_RESULT_BRAND}</div><br />
	{/if}
{/if}
{if !empty($search_complementary_results_array)}
	{foreach $search_complementary_results_array as $search_complementary_results}
		{if !empty($search_complementary_results.results)}
<h2 class="search_result">{$STR_RESULT_SEARCH} {$search|strtoupper} {$search_complementary_results.title} - {$search_complementary_results.results|@count} {if ($search_complementary_results.results|@count)>1}{$STR_RESULTS|replace:'(s)':'s'}{else}{$STR_RESULTS|replace:'(s)':''}{/if}</h2>
			{foreach $search_complementary_results.results as $result}
				{if !empty($result.html)}
{$result.html}
				{else}
<p>
	<b>{$result.num}. <a href="{$result.href|escape:'html'}">{$result.name|html_entity_decode_if_needed}</a></b> - {$result.description|html_entity_decode_if_needed}
</p>
				{/if}
			{/foreach}
<div class="clearfix"></div>
		{elseif !empty($search_complementary_results.no_result)}
<h2 class="search_result">{$STR_RESULT_SEARCH} {$search|strtoupper} {$search_complementary_results.title}</h2>
<div>{$search_complementary_results.no_result}</div><br />
		{/if}
	{/foreach}
{/if}
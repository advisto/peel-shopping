{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: search_result.tpl 39162 2013-12-04 10:37:44Z gboussin $
*}{if $is_annonce_module_active}
	{if !empty($res_affiche_annonces)}
<h1 class="search_result">{if !empty($search)}{$search|strtoupper}{else}{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_MODULE_ANNONCES_SEARCH_RESULT_ADS}{/if}</h1>
{$res_affiche_annonces}
	{elseif $page<1 && empty($result_affichage_produit) && empty($arts_found) && empty($brands_found)}
<h1 class="search_result">{if !empty($search)}{$search|strtoupper}{else}{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_MODULE_ANNONCES_SEARCH_RESULT_ADS}{/if}</h1>
<div>{$STR_MODULE_ANNONCES_SEARCH_NO_RESULT_ADS}</div><br />
	{/if}
{/if}
{if !$is_annonce_module_active}
	{if !empty($result_affichage_produit)}
<h1 class="search_result">{if !empty($search)}{$search|strtoupper}{else}{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_PRODUCT}{/if}</h1>
{$result_affichage_produit}
	{elseif $page<1 && empty($res_affiche_annonces) && empty($arts_found) && empty($brands_found)}
<h1 class="search_result">{if !empty($search)}{$search|strtoupper}{else}{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_PRODUCT}{/if}</h1>
<div>{$STR_SEARCH_NO_RESULT_PRODUCT}</div><br />
	{/if}
{/if}
{if !empty($are_terms)}
	{if !empty($arts_found)}
<h2 class="search_result">{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_ARTICLE}</h2>
		{foreach $arts_found as $art}
<p>
	<b>{$art.num}. <a href="{$art.category_href|escape:'html'}">{$art.rubrique|html_entity_decode_if_needed}</a></b> - <a href="{$art.content_href|escape:'html'}">{$art.titre|html_entity_decode_if_needed}</a><br />
	{$art.texte}
</p>
		{/foreach}
	{elseif $page<1 && !$is_annonce_module_active && empty($res_affiche_annonces) && empty($result_affichage_produit) && empty($brands_found)}
<h2 class="search_result">{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_ARTICLE}</h2>
<div>{$STR_SEARCH_NO_RESULT_ARTICLE}</div><br />
	{/if}
	{if !empty($brands_found)}
<h2 class="search_result">{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_BRAND}</h2>
		{foreach $brands_found as $brand}
<p>
	<b>{$brand.num}. <a href="{$brand.href|escape:'html'}">{$brand.nom|html_entity_decode_if_needed}</a></b> - {$brand.description|html_entity_decode_if_needed}
</p>
		{/foreach}
	{elseif $page<1 && !$is_annonce_module_active && empty($res_affiche_annonces) && empty($result_affichage_produit) && empty($arts_found)}
<h2 class="search_result">{$STR_RESULT_SEARCH} {$search|strtoupper} {$STR_SEARCH_RESULT_BRAND}</h2>
<div>{$STR_SEARCH_NO_RESULT_BRAND}</div><br />
	{/if}
{/if}
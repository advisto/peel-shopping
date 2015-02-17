{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: compte.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
*}<h1 property="name" class="page_title">{$compte}</h1>
<div class="page_content">
<p>{$msg_support}</p>
	{if $est_identifie}
		<p>{$compte} {$number} {$code_client}</p>
		<h3>{$my_order}</h3>
		- <a href="{$order_history_href|escape:'html'}">{$order_history}</a><br />
		- <a href="{$product_ordered_history_href|escape:'html'}">{$STR_PRODUCTS_PURCHASED_LIST}</a><br />
		{if isset($cart_preservation)}
		- <a href="{$cart_preservation.href|escape:'html'}">{$cart_preservation.txt}</a><br />
		{/if}
		
		{if isset($return_history)}
		<h3>{$return_history.header}</h3>
		- <a href="{$return_history.href|escape:'html'}">{$return_history.txt}</a><br />
		{/if}
		
		{if !empty($download_links)}
		<h3>{$STR_DOWNLOAD_CENTER}</h3>
			{foreach $download_links as $item}
		- <a href="{$item.href|escape:'html'}">{$item.name}</a><br />
			{/foreach}
		{/if}
		
		{if isset($ads)}
		<h3>{$ads.header}</h3>
		- <a href="{$ads.list_href|escape:'html'}">{$ads.STR_MODULE_ANNONCES_MY_AD_LIST}</a><br />
		- <a href="{$ads.create_href|escape:'html'}">{$ads.STR_MODULE_ANNONCES_AD_CREATE}</a><br />
		- <a href="{$ads.buy_href|escape:'html'}">{$ads.STR_MODULE_ANNONCES_BUY_GOLD_ADS}</a><br />
		{/if}
		
		{if isset($page_agenda)}
		<h3>{$STR_MODULE_AGENDA_TITRE}</h3>
		- <a href="{$page_agenda.href|escape:'html'}">{$page_agenda.txt}</a><br />
		{/if}

		{if isset($page_creation_produit)}
		<h3>{$STR_CATALOGUE}</h3>
		- <a href="{$page_creation_produit.href|escape:'html'}">{$page_creation_produit.txt}</a><br />
		{/if}
		
		{if isset($shop)}
		<h3>{$shop.header}</h3>
		- <a href="{$shop.href|escape:'html'}">{$shop.txt}</a><br />
		{/if}
		
		<h3>{$change_params.header}</h3>
		- <a href="{$change_password.href|escape:'html'}">{$change_password.txt}</a><br />
		- <a href="{$change_params.href|escape:'html'}">{$change_params.txt}</a><br />
		{if isset($MON_COMPTE_BLOG)}
			{$MON_COMPTE_BLOG}
		{/if}
		
		{if isset($giftlist)}
		<h3>{$giftlist.header}</h3>
		- <a href="{$giftlist.href|escape:'html'}">{$giftlist.txt}</a><br />
		{/if}
		
		{if isset($pensebete)}
		<h3>{$pensebete.header}</h3>
		- <a href="{$pensebete.href|escape:'html'}">{$pensebete.txt}</a><br />
		{/if}
		
		{if isset($parrainage)}
		<h3>{$parrainage.header}</h3>
		- <a href="{$parrainage.href|escape:'html'}">{$parrainage.txt}</a><br />
		{/if}
		
		{if isset($produit_cadeaux)}
		<h3>{$produit_cadeaux.header}</h3>
		- <a href="{$produit_cadeaux.href|escape:'html'}">{$produit_cadeaux.txt}</a><br />
		- {$produit_cadeaux.points_label}: {$produit_cadeaux.points}<br />
		{/if}
		
		{if isset($code_promo_utilise)}
		<h3>{$code_promo_utilise.header}</h3>
		{foreach $code_promo_utilise.data as $item}
			- {$item.code_promo} {$item.discount_text}<br />
		{/foreach}
		{/if}
		
		{if isset($code_promo_valide)}
		<h3>{$code_promo_valide.header}</h3>
		{foreach $code_promo_valide.data as $item}
			- {$item.nom_code} {$item.discount_text} {$item.code_promo_valid_from} {$item.date_from} {$item.flash_to} {$item.date_to}<br />
		{/foreach}
		{/if}
		
		{if isset($remise_percent)}
		<br />- {$remise_percent.label}: {$remise_percent.value} %<br />
		{/if}
		
		{if isset($avoir)}
		<br />- {$avoir.label}: {$avoir.value}<br />
		{/if}
		
		{if isset($gift)}
		<h3>{$gift.header}: {$gift.gifts_points}</h3>
		- <a href="{$gift.href|escape:'html'}">{$gift.txt}</a><br />
		{/if}
		
		{if isset($affiliate)}
		<h3>{$affiliate.account}</h3>
		{$affiliate.account_msg}<br />
		<br />
		{$affiliate.account_url} <b>{$affiliate.account_href}</b><br />
		<br />
		- <a href="{$affiliate.account_prod_href|escape:'html'}">{$affiliate.STR_AFFILIATE_ACCOUNT_PROD}</a><br />
		- <a href="{$affiliate.account_ban_href|escape:'html'}">{$affiliate.STR_AFFILIATE_ACCOUNT_BAN}</a><br />
		- <a href="{$affiliate.account_sell_href|escape:'html'}">{$affiliate.STR_AFFILIATE_ACCOUNT_SELL}</a><br />
		{/if}

		{if isset($sauvegarde_recherche)}
			<h3>{$STR_MODULE_SAUVEGARDE_SEARCH_LIST}</h3>
			- <a href="{$sauvegarde_recherche.href|escape:'html'}">{$sauvegarde_recherche.txt}</a><br />
		{/if}
		
		{if isset($profile)}
		<h3>{$profile.header}</h3>
		{$profile.content}<br />
			{if !empty($profile.href)}
			- <a href="{$profile.href|escape:'html'}">{$profile.txt}</a><br />
			{/if}
		{/if}
		{if isset($user_alerts)}
		- <a href="{$user_alerts.href|escape:'html'}">{$user_alerts.txt}</a><br />
		{/if}
		
		{if isset($disable_account)}
		<br />
		- <a data-confirm="{$confirm_disable_account}" href="{$disable_account_href|escape:'html'}">{$disable_account_text}</a><br /><br />
		{/if}
		
		{if isset($admin)}
		<h3>{$admin.txt}</h3>
		- <a href="{$admin.href|escape:'html'}">{$admin.txt}</a><br /><br />
		{/if}
		
		<br />
		- <a href="{$logout.href|escape:'html'}">{$logout.txt}</a><br />

		{if isset($ABONNEMENT_MODULE)}{$ABONNEMENT_MODULE}{/if}
		
		{if isset($annonce)}
		<p class="center"><b>{$annonce.label}: {$annonce.credit}</b></p>
		{/if}
		
	{else}
		- <a href="{$login_href|escape:'html'}">{$login}</a><br />
		- <a href="{$register_href|escape:'html'}">{$register}</a><br />
	{/if}
	{if isset($downloadable_file_link_array)}
		<table class="full_width">
		{foreach $downloadable_file_link_array as $item}
			<tr>
				<td align="center">
					<a href="{$item.link}">{$item.date} - {$item.name} - {$STR_MODULE_TELECHARGEMENT_FOR_DOWNLOAD}</a>
				</td>
			</tr>
		{/foreach}
		</table>
	{/if}
</div>
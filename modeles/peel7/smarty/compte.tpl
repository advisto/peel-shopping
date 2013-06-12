{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: compte.tpl 37032 2013-05-29 22:21:19Z gboussin $
*}<h1 class="page_title">{$compte}</h1>
<div class="page_content">
<p>{$msg_support}</p>
	{if $est_identifie}
		<p>{$compte} {$number} {$code_client}</p>
		<br />
		<h3>{$my_order}</h3>
		- <a href="{$order_history_href|escape:'html'}">{$order_history}</a><br />
		{if isset($cart_preservation)}
		- <a href="{$cart_preservation.href|escape:'html'}">{$cart_preservation.txt}</a><br />
		{/if}
		
		<br />
		{if isset($return_history)}
		<h3>{$return_history.header}</h3>
		- <a href="{$return_history.href|escape:'html'}">{$return_history.txt}</a><br />
		<br />
		{/if}
		
		{if !empty($download_links)}
		<h3>{$STR_DOWNLOAD_CENTER}</h3>
			{foreach $download_links as $item}
		- <a href="{$item.href|escape:'html'}">{$item.name}</a><br />
			{/foreach}
		<br />
		{/if}
		
		{if isset($ads)}
		<h3>{$ads.header}</h3>
		- <a href="{$ads.list_href|escape:'html'}">{$ads.STR_MODULE_ANNONCES_MY_AD_LIST}</a><br />
		- <a href="{$ads.create_href|escape:'html'}">{$ads.STR_MODULE_ANNONCES_AD_CREATE}</a><br />
		- <a href="{$ads.buy_href|escape:'html'}">{$ads.STR_MODULE_ANNONCES_BUY_GOLD_ADS}</a><br />
		<br />
		{/if}
		
		{if isset($shop)}
		<h3>{$shop.header}</h3>
		- <a href="{$shop.href|escape:'html'}">{$shop.txt}</a><br />
		<br />
		{/if}
		
		<h3>{$change_params.header}</h3>
		- <a href="{$change_password.href|escape:'html'}">{$change_password.txt}</a><br />
		- <a href="{$change_params.href|escape:'html'}">{$change_params.txt}</a><br />
		{if isset($MON_COMPTE_BLOG)}
			{$MON_COMPTE_BLOG}
		{/if}
		
		{if isset($giftlist)}
		<br />
		<h3>{$giftlist.header}</h3>
		- <a href="{$giftlist.href|escape:'html'}">{$giftlist.txt}</a><br />
		{/if}
		
		{if isset($pensebete)}
		<br />
		<h3>{$pensebete.header}</h3>
		- <a href="{$pensebete.href|escape:'html'}">{$pensebete.txt}</a><br />
		{/if}
		
		{if isset($parrainage)}
		<br />
		<h3>{$parrainage.header}</h3>
		- <a href="{$parrainage.href|escape:'html'}">{$parrainage.txt}</a><br />
		{/if}
		
		{if isset($produit_cadeaux)}
		<br />
		<h3>{$produit_cadeaux.header}</h3>
		- <a href="{$produit_cadeaux.href|escape:'html'}">{$produit_cadeaux.txt}</a><br />
		- {$produit_cadeaux.points_label}: {$produit_cadeaux.points}<br />
		{/if}
		
		{if isset($code_promo_utilise)}
		<br />
		<h3>{$code_promo_utilise.header}</h3>
		{foreach $code_promo_utilise.data as $item}
			- {$item.code_promo} {$item.discount_text}<br />
		{/foreach}
		{/if}
		
		{if isset($code_promo_valide)}
		<br />
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
		<br />
		<h3>{$gift.header}</h3>
		- <a href="{$gift.href|escape:'html'}">{$gift.txt}</a><br />
		{/if}
		
		{if isset($affiliate)}
		<br />
		<h3>{$affiliate.account}</h3>
		{$affiliate.account_msg}<br />
		<br />
		{$affiliate.account_url} <b>{$affiliate.account_href}</b><br />
		<br />
		- <a href="{$affiliate.account_prod_href|escape:'html'}">{$affiliate.STR_AFFILIATE_ACCOUNT_PROD}</a><br />
		- <a href="{$affiliate.account_ban_href|escape:'html'}">{$affiliate.STR_AFFILIATE_ACCOUNT_BAN}</a><br />
		- <a href="{$affiliate.account_sell_href|escape:'html'}">{$affiliate.STR_AFFILIATE_ACCOUNT_SELL}</a><br />
		{/if}
		
		{if isset($profile)}
		<h3>{$profile.header}</h3>
		- <a href="{$profile.href|escape:'html'}">{$profile.txt}</a><br />
		{/if}
		{if isset($user_alerts)}
		- <a href="{$user_alerts.href|escape:'html'}">{$user_alerts.txt}</a><br />
		{/if}
		
		<br />
		- <a href="{$logout.href|escape:'html'}">{$logout.txt}</a><br />
		
		{if isset($admin)}
		<br /><br />
		- <a href="{$admin.href|escape:'html'}">{$admin.txt}</a><br /><br />
		{/if}
		
		{if isset($ABONNEMENT_MODULE)}{$ABONNEMENT_MODULE}{/if}
		
		{if isset($annonce)}
		<p class="center"><b>{$annonce.label}: {$annonce.credit}</b></p>
		{/if}
		
	{else}
		- <a href="{$login_href|escape:'html'}">{$login}</a><br />
		- <a href="{$register_href|escape:'html'}">{$register}</a><br />
	{/if}
</div>
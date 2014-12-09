{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: compte.tpl 43061 2014-10-30 16:54:39Z sdelaporte $
#}<h1 property="name" class="page_title">{{ compte }}</h1>
<div class="page_content">
<p>{{ msg_support }}</p>
	{% if est_identifie %}
		<p>{{ compte }} {{ number }} {{ code_client }}</p>
		<h3>{{ my_order }}</h3>
		- <a href="{{ order_history_href|escape('html') }}">{{ order_history }}</a><br />
		{% if (cart_preservation) %}
		- <a href="{{ cart_preservation.href|escape('html') }}">{{ cart_preservation.txt }}</a><br />
		{% endif %}
		
		{% if (return_history) %}
		<h3>{{ return_history.header }}</h3>
		- <a href="{{ return_history.href|escape('html') }}">{{ return_history.txt }}</a><br />
		{% endif %}
		
		{% if (download_links) %}
		<h3>{{ STR_DOWNLOAD_CENTER }}</h3>
			{% for item in download_links %}
		- <a href="{{ item.href|escape('html') }}">{{ item.name }}</a><br />
			{% endfor %}
		{% endif %}
		
		{% if (ads) %}
		<h3>{{ ads.header }}</h3>
		- <a href="{{ ads.list_href|escape('html') }}">{{ ads.STR_MODULE_ANNONCES_MY_AD_LIST }}</a><br />
		- <a href="{{ ads.create_href|escape('html') }}">{{ ads.STR_MODULE_ANNONCES_AD_CREATE }}</a><br />
		- <a href="{{ ads.buy_href|escape('html') }}">{{ ads.STR_MODULE_ANNONCES_BUY_GOLD_ADS }}</a><br />
		{% endif %}
		
		{% if page_agenda is defined %}
		<h3>{{ STR_MODULE_AGENDA_TITRE }}</h3>
		- <a href="{{ page_agenda.href|escape('html') }}">{{ page_agenda.txt }}</a><br />
		{% endif %}

		{% if page_creation_produit is defined %}
		<h3>{{ STR_CATALOGUE }}</h3>
		- <a href="{{ page_creation_produit.href|escape('html') }}">{{ page_creation_produit.txt }}</a><br />
		{% endif %}
		
		{% if (shop) %}
		<h3>{{ shop.header }}</h3>
		- <a href="{{ shop.href|escape('html') }}">{{ shop.txt }}</a><br />
		{% endif %}

		<h3>{{ change_params.header }}</h3>
		- <a href="{{ change_password.href|escape('html') }}">{{ change_password.txt }}</a><br />
		- <a href="{{ change_params.href|escape('html') }}">{{ change_params.txt }}</a><br />
		{% if (MON_COMPTE_BLOG) %}
			{{ MON_COMPTE_BLOG }}
		{% endif %}
		
		{% if (giftlist) %}
		<h3>{{ giftlist.header }}</h3>
		- <a href="{{ giftlist.href|escape('html') }}">{{ giftlist.txt }}</a><br />
		{% endif %}
		
		{% if (pensebete) %}
		<h3>{{ pensebete.header }}</h3>
		- <a href="{{ pensebete.href|escape('html') }}">{{ pensebete.txt }}</a><br />
		{% endif %}
		
		{% if (parrainage) %}
		<h3>{{ parrainage.header }}</h3>
		- <a href="{{ parrainage.href|escape('html') }}">{{ parrainage.txt }}</a><br />
		{% endif %}
		
		{% if (produit_cadeaux) %}
		<h3>{{ produit_cadeaux.header }}</h3>
		- <a href="{{ produit_cadeaux.href|escape('html') }}">{{ produit_cadeaux.txt }}</a><br />
		- {{ produit_cadeaux.points_label }}: {{ produit_cadeaux.points }}<br />
		{% endif %}
		
		{% if (code_promo_utilise) %}
		<h3>{{ code_promo_utilise.header }}</h3>
		{% for item in code_promo_utilise.data %}
			- {{ item.code_promo }} {{ item.discount_text }}<br />
		{% endfor %}
		{% endif %}
		
		{% if (code_promo_valide) %}
		<h3>{{ code_promo_valide.header }}</h3>
		{% for item in code_promo_valide.data %}
			- {{ item.nom_code }} {{ item.discount_text }} {{ item.code_promo_valid_from }} {{ item.date_from }} {{ item.flash_to }} {{ item.date_to }}<br />
		{% endfor %}
		{% endif %}
		
		{% if (remise_percent) %}
		<br />- {{ remise_percent.label }}: {{ remise_percent.value }} %<br />
		{% endif %}
		
		{% if (avoir) %}
		<br />- {{ avoir.label }}: {{ avoir.value }}<br />
		{% endif %}
		
		{% if (gift) %}
		<br />
		<h3>{{ gift.header }}: {{ gift.gifts_points }}</h3>
		- <a href="{{ gift.href|escape('html') }}">{{ gift.txt }}</a><br />
		{% endif %}
		
		{% if (affiliate) %}
		<h3>{{ affiliate.account }}</h3>
		{{ affiliate.account_msg }}<br />
		<br />
		{{ affiliate.account_url }} <b>{{ affiliate.account_href }}</b><br />
		<br />
		- <a href="{{ affiliate.account_prod_href|escape('html') }}">{{ affiliate.STR_AFFILIATE_ACCOUNT_PROD }}</a><br />
		- <a href="{{ affiliate.account_ban_href|escape('html') }}">{{ affiliate.STR_AFFILIATE_ACCOUNT_BAN }}</a><br />
		- <a href="{{ affiliate.account_sell_href|escape('html') }}">{{ affiliate.STR_AFFILIATE_ACCOUNT_SELL }}</a><br />
		{% endif %}
		
		{% if sauvegarde_recherche is defined %}
			<h3>{{ STR_MODULE_SAUVEGARDE_SEARCH_LIST }}</h3>
			- <a href="{{ sauvegarde_recherche.href|escape('html') }}">{{ sauvegarde_recherche.txt }}</a><br />
		{% endif %}

		{% if (profile) %}
		<h3>{{ profile.header }}</h3>
		{{ profile.content }}
		- <a href="{{ profile.href|escape('html') }}">{{ profile.txt }}</a><br />
		{% endif %}
 		
		{% if disable_account is defined %}
		<br />
		- <a data-confirm="{{ confirm_disable_account }}" href="{{ disable_account_href|escape('html') }}">{{ disable_account_text }}</a><br /><br />
		{% endif %}

		{% if (user_alerts) %}
		- <a href="{{ user_alerts.href|escape('html') }}">{{ user_alerts.txt }}</a><br />
		{% endif %}
		
		{% if (admin) %}
		<h3>{{ admin.txt }}</h3>
		- <a href="{{ admin.href|escape('html') }}">{{ admin.txt }}</a><br /><br />
		{% endif %}
		
		<br />
		- <a href="{{ logout.href|escape('html') }}">{{ logout.txt }}</a><br />

		{% if (ABONNEMENT_MODULE) %}{{ ABONNEMENT_MODULE }}{% endif %}
		
		{% if (annonce) %}
		<p class="center"><b>{{ annonce.label }}: {{ annonce.credit }}</b></p>
		{% endif %}
		
	{% else %}
		- <a href="{{ login_href|escape('html') }}">{{ login }}</a><br />
		- <a href="{{ register_href|escape('html') }}">{{ register }}</a><br />
	{% endif %}
	{% if (downloadable_file_link_array) %}
		<table class="full_width">
		{% for item in downloadable_file_link_array %}
			<tr>
				<td align="center">
					<a href="{{ item.link }}">{{ item.date }} - {{ item.name }} - {{ STR_MODULE_TELECHARGEMENT_FOR_DOWNLOAD }}</a>
				</td>
			</tr>
		{% endfor %}
		</table>
	{% endif %}
</div>
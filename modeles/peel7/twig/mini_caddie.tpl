{# Twig
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
// $Id: mini_caddie.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<div id="fly_to_basket_destination"></div>
<div class="dropdown">
	<a class="dropdown-toggle btn btn-default caddie-button" href="#" data-toggle="dropdown"><span class="glyphicon glyphicon-shopping-cart"></span><span class="badge minicaddie_badge_xs">{{ count_products }}</span><span class="caret"></span></a>
	{# <div class="minicaddie hidden-xs"><a href="{{ affichage_href|escape('html') }}" style="text-decoration:none"><span class="glyphicon glyphicon-shopping-cart minicaddie_md"></span><span class="minicaddie_badge_md">{{ count_products }}</span></a></div> #}
	<div class="dropdown-menu">
		<table class="minicaddie">
			<tr>
				<td><a href="{{ affichage_href|escape('html') }}"><img src="{{ logo_src|escape('html') }}" alt="" /></a></td>
				<td><h2><a href="{{ affichage_href|escape('html') }}">{{ STR_CADDIE }}</a></h2><p>{{ count_products }} {{ products_txt }}</p></td>
			</tr>
			{% if has_details %}
			<tr>
				<td colspan="2">
					<table>
						{% for item in products %}
						<tr>
							<td class="product_name">{% if (item.picture) %}<div class="autocomplete_image"><img src="{{ item.picture }}" alt="{{ item.name|str_form_value }}" /></div>{% endif %}<div>{{ item.quantite }} x <a href="{{ item.href|escape('html') }}">{{ item.name }}{% if (item.color) %}<br />{{ item.color.label }}: {{ item.color.value }}{% endif %}{% if (item.size) %}<br />{{ item.size.label }}: {{ item.size.value }}{% endif %}</a></div></td>
							<td class="product_price"><div>{{ item.price }}</div></td>
						</tr>
						{% endfor %}
						{% if (transport) %}
							<tr><td>{{ transport.label }}:</td><td class="right">{{ transport.value }}</td></tr>
						{% endif %}
						{% if (total) and mini_caddy_total_display_disable is empty %}
							<tr><td>{{ total.label }}:</td><td class="right">{{ total.value }}</td></tr>
						{% endif %}
						<tr><td colspan="2" class="center"><a href="{{ affichage_href|escape('html') }}" class="btn btn-primary">{{ STR_DETAILS_ORDER }}</a></td></tr>
					</table>
				</td>
			</tr>
			{% endif %}
		</table>
	</div>
</div>		
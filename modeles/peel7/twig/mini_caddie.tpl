{# Twig
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
// $Id: mini_caddie.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}<div id="fly_to_basket_destination"></div>
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
					<td class="product_name"><div>{{ item.quantite }} x <a href="{{ item.href|escape('html') }}">{{ item.name }}{% if (item.color) %}<br />{{ item.color.label }}: {{ item.color.value }}{% endif %}{% if (item.size) %}<br />{{ item.size.label }}: {{ item.size.value }}{% endif %}</a></div></td>
					<td class="product_price"><div>{{ item.price }}</div></td>
				</tr>
				{% endfor %}
				{% if (transport) %}
					<tr><td>{{ transport.label }}:</td><td class="right">{{ transport.value }}</td></tr>
				{% endif %}
				{% if (total) %}
					<tr><td>{{ total.label }}:</td><td class="right">{{ total.value }}</td></tr>
				{% endif %}
				<tr><td colspan="2" class="center"><a href="{{ affichage_href|escape('html') }}" class="bouton">{{ STR_DETAILS_ORDER }}</a></td></tr>
			</table>
		</td>
	</tr>
	{% endif %}
</table>	
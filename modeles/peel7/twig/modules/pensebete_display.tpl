{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: pensebete_display.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<h2>{{ STR_MODULE_PENSEBETE_PENSE_BETE_PRODUIT }}</h2>
{% if are_prods %}
	<table class="reminder_array" cellpadding="2"  summary="{{ STR_TABLE_SUMMARY_CADDIE|str_form_value }}">
		<tr>
			<th colspan="3" scope="col">{{ STR_PRODUCT }}</th>
			<th scope="col">{{ STR_REMISE }}</th>
			<th scope="col">{{ STR_UNIT_PRICE }} {{ STR_TTC }}</th>
		</tr>
		{% for p in prods %}
		<tr>
			<td class="lignecaddie_suppression"><a href="{{ p.del_href|escape('html') }}"><img src="{{ del_src|escape('html') }}" alt="{{ STR_DELETE_PROD_CART }}" /></a>
			</td>
			<td class="lignecaddie_produit_image">
			{% if (p.img) %}
				<a href="{{ p.urlprod }}"><img src="{{ p.img }}" width="100" /></a>
			{% endif %}
			</td>
			<td class="lignecaddie_produit_details"><a href="{{ p.urlprod }}">{{ p.name|html_entity_decode }}</a></td>
			<td class="lignecaddie_prix center">{% if (p.promotion) %}{{ p.promotion }} % {% else %}-{% endif %}</td>
			<td class="lignecaddie_prix">{{ p.prix }} {{ STR_TTC }}</td>
		</tr>
		{% endfor %}
	</table>
{% else %}
<p>{{ STR_MODULE_PENSEBETE_NO_PRODUCT_IN_REMINDER }}</p>
{% endif %}
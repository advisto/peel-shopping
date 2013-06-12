{# Twig
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
// $Id: admin_liste_produits_acommander.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}{% if is_empty %}
<p>{{ STR_ADMIN_PRODUITS_NO_PRODUCT_TO_ORDER }}</p>
{% else %}
<table class="main_table">
	<tr>
		<td class="entete" colspan="4">{{ STR_ADMIN_PRODUITS_LIST_TO_ORDER_TITLE }}</td>
	</tr>
	<tr>
		<td class="menu">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu">{{ STR_PRODUCT }}</td>
		<td class="menu center">{{ STR_ADMIN_PRODUITS_TO_ORDER }}</td>
		<td class="menu center">{{ STR_ADMIN_PRODUITS_ORDER_DETAIL }}</td>
	</tr>
	{% for p in products %}
	<tr>
		<td class="label center"><a href="{{ p.stock_href|escape('html') }}"><img src="{{ p.stock_src|escape('html') }}" alt="" /></a></td>
		<td class="label"><a href="{{ p.modif_href|escape('html') }}">{{ p.nom|html_entity_decode_if_needed }}</a><br />{{ STR_COLOR }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.couleur }}<br />{{ STR_SIZE }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.taille }}<br />{{ STR_ADMIN_PRODUITS_SUPPLY_FORECASTED }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.delai_stock }}</td>
		<td class="label center">{{ p.order_stock }}</td>
		<td class="center"><a href="{{ p.commander_href|escape('html') }}">{{ STR_ORDER }} {{ p.commande_id }}</a></td>
	</tr>
	{% endfor %}
	<tr><td class="center" colspan="4">{{ Multipage }}</td></tr>
</table>
{% endif %}
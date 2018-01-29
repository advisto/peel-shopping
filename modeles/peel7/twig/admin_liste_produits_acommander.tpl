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
// $Id: admin_liste_produits_acommander.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}{% if is_empty %}
<p class="alert alert-warning">{{ STR_ADMIN_PRODUITS_NO_PRODUCT_TO_ORDER }}</p>
{% else %}
<div class="entete">{{ STR_ADMIN_PRODUITS_LIST_TO_ORDER_TITLE }}</div>
<div class="table-responsive">
	<table class="table">
		<tr>
			<td class="menu">{{ STR_ADMIN_ACTION }}</td>
			<td class="menu">{{ STR_PRODUCT }}</td>
			<td class="menu center">{{ STR_ADMIN_PRODUITS_TO_ORDER }}</td>
			<td class="menu center">{{ STR_ADMIN_PRODUITS_ORDER_DETAIL }}</td>
		</tr>
		{% for p in products %}
		<tr>
			<td class="title_label center"><a href="{{ p.stock_href|escape('html') }}"><img src="{{ p.stock_src|escape('html') }}" alt="" /></a></td>
			<td class="title_label"><a href="{{ p.modif_href|escape('html') }}">{{ p.nom|html_entity_decode_if_needed }}</a><br />{{ STR_COLOR }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.couleur }}<br />{{ STR_SIZE }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.taille }}<br />{{ STR_ADMIN_PRODUITS_SUPPLY_FORECASTED }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.delai_stock }}</td>
			<td class="title_label center">{{ p.order_stock }}</td>
			<td class="center"><a href="{{ p.commander_href|escape('html') }}">{{ STR_ORDER_NAME }} {{ p.commande_id }}</a></td>
		</tr>
		{% endfor %}
	</table>
</div>
<div class="center">{{ Multipage }}</div>
{% endif %}
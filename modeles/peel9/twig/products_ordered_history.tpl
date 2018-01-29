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
// $Id: products_ordered_history.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}
<h1 class="liste_commandes">{{ STR_PRODUCTS_PURCHASED_LIST }}</h1>
{% if STR_NO_ORDER is defined %}
<div><p>{{ STR_NO_ORDER }}</p></div>
{% else %}
<div class="table-responsive">
	<table class="table">
		{{ links_header_row }}
		{% for prod in products %}
		<tr >
			<td class="center">{% if prod.href_produit %}<a href="{{ prod.href_produit }}">{% endif %}{{ prod.nom_produit }}{% if prod.href_produit %}</a>{% endif %}</td>
			<td class="center">{{ prod.quantite }}</td>
			<td class="center">{{ prod.o_timestamp }}</td>
			<td class="center">{{ prod.numero }}</td>
		</tr>
		{% endfor %}
	</table>
</div>
<div>{{ links_multipage }}</div>
{% endif %}
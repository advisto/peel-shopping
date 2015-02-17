{# Twig
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
// $Id: products_ordered_history.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
#}
<h2 class="liste_commandes">{{ STR_PRODUCTS_PURCHASED_LIST }}</h2>
{% if STR_NO_ORDER is defined %}
<div><p>{{ STR_NO_ORDER }}</p></div>
{% else %}
	<div class="table-responsive">
		<table class="table">
			{{ links_header_row }}
			{% for prod in products %}
			<tr style="background-color: #{cycle values="F4F4F4,ffffff"}">
				<td class="center"><a href="{{ prod.href_produit}">{{ prod.nom_produit }}</a></td>
				<td class="center">{{ prod.quantite }}</td>
				<td class="center">{{ prod.o_timestamp }}</td>
				<td class="center">{{ prod.numero }}</td>
			</tr>
			{% endfor %}
		</table>
	</div>
<div>{{ links_multipage }}</div>
{% endif %}
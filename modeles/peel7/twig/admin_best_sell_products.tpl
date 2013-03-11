{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_best_sell_products.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<table class="main_table">
	<tr>
		<td class="entete">{{ STR_ADMIN_PRODUITS_ACHETES_MOST_WANTED }}</td>
	</tr>
	<tr>
		<td>
			<table class="admin_best_sell_products">
				<tr>
					<th class="menu">{{ STR_PRODUCT }}</th>
					<th class="menu">{{ STR_ADMIN_PRODUITS_ACHETES_COUNT_IN_PREFERED }}</th>
					<th class="menu" width="120">{{ STR_QUANTITY }}</th>
					<th class="menu" width="120">{{ STR_AMOUNT }}</th>
				</tr>
			{% for p in prods %}
				{{ p.tr_rollover }}
					<td>{{ p.lien }}</td>
					<td class="center">{{ p.nombre }}</td>
					<td class="right">{{ p.quantite_totale }}</td>
					<td class="right">{{ p.prix }}</td>
				</tr>
			{% endfor %}
			</table>
		</td>
	</tr>
</table>
{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_home_delivery_desc2.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<h3>{{ STR_ADMIN_INDEX_DELIVERY_DESC2 }}{{ STR_BEFORE_TWO_POINTS }}:</h3>
<div class="table-responsive">
	<table class="home_block_data_table">
		<tr>
			<th>{{ STR_ADMIN_ID }} / {{ STR_ADMIN_NAME }}</th>
			<th>{{ STR_DATE }}</th>
			<th>{{ STR_TOTAL }} {{ STR_TTC }}</th>
			<th>{{ STR_DELIVERY }}</th>
		</tr>
		{% for res in results %}
		{{ res.tr_rollover }}
			<td>{{ res.id }}<br />{{ res.nom_bill }}</td>
			<td>{{ res.date }}</td>
			<td>{{ res.prix }}</td>
			<td>{{ res.statut_livraison }}</td>
		</tr>
		{% endfor %}
	</table>
</div>
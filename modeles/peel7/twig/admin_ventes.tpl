{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_ventes.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}{% if (results) %}
<br />{{ STR_ADMIN_VENTES_FORM_EXPLAIN }}<br />
<p class="label center">{{ period_text }}</p>
<table class="main_table">
	<tr>
		<td class="center menu"><b>{{ STR_DATE }}</b></td>
		<td class="center menu"><b>{{ STR_ORDER }}</b></td>
		<td class="center menu"><b>{{ STR_STATUS }}</b></td>
		<td class="center menu"><b>{{ STR_EMAIL }}</b></td>
		<td class="center menu"><b>{{ STR_AMOUNT }} {{ STR_HT }}</b></td>
		<td class="center menu"><b>{{ STR_VAT }}</b></td>
		<td class="center menu"><b>{{ STR_AMOUNT }} {{ STR_TTC }}</b></td>
		<td class="center menu"><b>{{ STR_ADMIN_INCLUDING_DELIVERY_COST }}</b></td>
	</tr>
	{% for res in results %}
	{{ res.tr_rollover }}
		<td>{{ res.date }}</td>
		<td class="center">{{ res.id }} / <a href="{{ res.modif_href|escape('html') }}">Voir</a></td>
		<td class="center">{{ res.statut_paiement }}</td>
		<td class="center"><a href="mailto:{{ res.email }}">{{ res.email }}</a></td>
		<td class="center">{{ res.montant_ht_prix }} {{ res.montant_ht_devise_commande }}</td>
		<td class="center">{{ res.total_tva_prix }} {{ res.total_tva_devise_commande }}</td>
		<td class="center">{{ res.montant_prix }} {{ res.montant_devise_commande }}</td>
		<td class="center">{{ res.cout_transport_prix }} {{ res.cout_transport_devise_commande }} {{ STR_TTC }}</td>
	</tr>
	{% endfor %}
	<tr>
		<td colspan="4" class="label" style="border-top:solid 1px #000000; padding-bottom:15px">{{ STR_ADMIN_BILL_TOTALS }}</td>
		<td class="center label" style="border-top:solid 1px #000000; padding-bottom:15px">{{ totalVenteHt_prix }} {{ STR_HT }}</td>
		<td class="center label" style="border-top:solid 1px #000000; padding-bottom:15px">{{ totalTva_prix }}</td>
		<td class="center label" style="border-top:solid 1px #000000; padding-bottom:15px">{{ totalVente_prix }} {{ STR_TTC }}</td>
		<td class="center label" style="border-top:solid 1px #000000; padding-bottom:15px">{{ totalTransport_prix }}</td>
	</tr>
	<tr>
		<td colspan="8" class="label">&nbsp;</td>
	</tr>
	{% for v in vats %}
	<tr>
		<td colspan="6" class="label">&nbsp;</td>
		<td class="label">{{ STR_ADMIN_TOTAL_VAT }} {% if v.rate == 'transport' %}{{ v.rate }}{% else %}{{ v.rate }}%{% endif %}</td>
		<td class="center label">{{ v.prix }}</td>
	</tr>
	{% endfor %}
	<tr>
		<td colspan="8" class="label">&nbsp;</td>
	</tr>
	<tr>
	{% if only_delivered %}
	<p class="label center"><font size="+1" color="green">{{ STR_MODULE_KEKOLI_ADMIN_ONLY_DELIVERED }}</font></p>
	{% endif %}
	{% if is_module_export_ventes_active %}
		<td colspan="4" class="label" align="right" style="padding-bottom:15px"><a href="{{ export_href|escape('html') }}" class="label"><img src="{{ excel_src|escape('html') }}" align="absmiddle" alt="" />&nbsp;{{ STR_ADMIN_VENTES_EXPORT_EXCEL }}</a></td>
		<td colspan="2" class="label">&nbsp;</td>
	{% else %}
		<td colspan="6" class="label">&nbsp;</td>
	{% endif %}
		<td class="label" style="border-top:solid 1px #000000; padding-bottom:15px">{{ STR_ADMIN_TOTAL_VAT }}</td>
		<td class="center label" style="border-top:solid 1px #000000; padding-bottom:15px">{{ totalTva_prix }}</td>
	</tr>
</table>
<p class="label center"><font size="+1" color="green">{{ STR_ADMIN_ASKED_STATUS }}{{ STR_BEFORE_TWO_POINTS }}: {% if (payment_status_name) %}{{ payment_status_name }}{% else %}{{ STR_ADMIN_ALL_ORDERS }}{% endif %}</font></p>
{% else %}
<p class="label center">{{ period_text }}</p>
<div class="center"><b>{{ STR_ADMIN_VENTES_NO_ORDER_FOUND }}</b></div>
{% endif %}
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
// $Id: liste_commandes.tpl 37960 2013-08-29 16:47:44Z gboussin $
#}<h2 class="liste_commandes">{{ STR_ORDER_HISTORY }}</h2>
{% if (STR_NO_ORDER) %}
<div><p>{{ STR_NO_ORDER }}</p></div>
{% else %}
<table class="caddie" cellpadding="2" summary="{{ STR_TABLE_SUMMARY_ORDERS|str_form_value }}">
	<caption></caption>
	<tr>
		<th class="center" scope="col" style="width:60px">&nbsp;</th>
		<th class="center" scope="col">{{ STR_ORDER_NUMBER }}</th>
		<th class="center" scope="col">{{ STR_DATE }}</th>
		<th class="center" scope="col">{{ STR_ORDER_STATUT_PAIEMENT }}</th>
		<th class="center" scope="col">{{ STR_ORDER_STATUT_LIVRAISON }}</th>
		<th class="center" scope="col">{{ STR_AMOUNT }}</th>
	</tr>
	{% for o in orders %}
	<tr style="background-color: #{{ cycle values="F4F4F4,ffffff" }}">
		<td class="center">
			<a href="{{ o.href|escape('html') }}"><img src="{{ o.info_src|escape('html') }}" width="21" height="21" alt="info" /></a><br /><img src="{{ o.pdf_src|escape('html') }}" width="8" height="11" alt="" />&nbsp;
		{% if o.facture_href %}
			<a onclick="return(window.open(this.href)?false:true);" href="{{ o.facture_href|escape('html') }}">{{ STR_PDF_BILL }}</a>
		{% endif %}
		</td>
		<td class="center">{{ o.numero }}</td>
		<td class="center">{{ o.date }}</td>
		<td class="center">{% if not(o.paid) %}<a href="{{ o.href|escape('html') }}">{{ o.payment_status_name }}</a>{% else %}{{ o.payment_status_name }}{% endif %}</td>
		<td class="center">{{ o.delivery_status_name }}</td>
		<td class="center">{{ o.prix }}</td>
	</tr>
	{% endfor %}
	<tr><td colspan="6" align="center">{{ multipage }}</td></tr>
</table>
{% endif %}
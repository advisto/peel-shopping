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
// $Id: caddie_products_summary_table.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<table class="caddie" cellpadding="2"  summary="{{ STR_TABLE_SUMMARY_CADDIE|str_form_value }}">
	<tr>
		<th colspan="3" scope="col">{{ STR_PRODUCT }}</th>
		<th scope="col">{{ STR_UNIT_PRICE }} {{ taxes_displayed }}</th>
		<th scope="col">{{ STR_OPTION_PRICE }}</th>
		<th scope="col">{{ STR_QUANTITY }}</th>
		{% if is_conditionnement_module_active %}<td class="center">{{ STR_CONDITIONNEMENT }}</td><td class="center">{{ STR_CONDITIONNEMENT_QTY }}</td>{% endif %}
		<th scope="col">{{ STR_REMISE }} {{ taxes_displayed }}</th>
		<th scope="col">{{ STR_TOTAL_PRICE }} {{ taxes_displayed }}</th>
	</tr>
	{% for p in products %}
	<tr>
		<td scope="row" class="lignecaddie_suppression">
			<a onclick="return confirm('{{ STR_DELETE_PROD_CART|filtre_javascript(true,true,true) }}');" href="{{ p.delete_href|escape('html') }}">
				<img src="{{ suppression_src|escape('html') }}" alt="{{ STR_DELETE_PROD_CART|str_form_value }}" />
			</a>
		</td>
		<td class="lignecaddie_produit_image">
			<a href="{{ p.urlprod_with_cid }}"><img src="{{ p.src|escape('html') }}" alt="" /></a>
		</td>
		<td class="lignecaddie_produit_details">
			{% if with_form_fields %}
			<input type="hidden" name="id[{{ p.numero_ligne }}]" value="{{ p.id|str_form_value }}" />
			<input type="hidden" name="listcadeaux_owner[{{ p.numero_ligne }}]" value="{{ p.listcadeaux_owner|str_form_value }}" />
			<input type="hidden" name="option[{{ p.numero_ligne }}]" value="{{ p.option|str_form_value }}" />
			{% if is_attributes_module_active %}
			<input type="hidden" name="id_attribut[{{ p.numero_ligne }}]" value="{{ p.id_attribut|str_form_value }}" />
			{% endif %}
			{% endif %}
			{% if (p.listcadeaux_owner_name) %}
			<span class="offered_by">{{ STR_FOR_GIFT }} {{ p.listcadeaux_owner_name }}</span><br />
			{% endif %}
			<a href="{{ p.urlprod_with_cid }}">{{ p.name }}</a>
			{% if (p.delivery_stock) %}
			<br />{{ STR_DELIVERY_STOCK }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.delivery_stock }}<br />
			{% endif %}
			{% if is_attributes_module_active and (p.configuration_attributs_description) %}
			<br />{{ p.configuration_attributs_description }}
			{% endif %}
			{% if (p.color) %}
			<br />{{ STR_COLOR }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.color.name }} <input type="hidden" name="couleurId[{{ p.numero_ligne }}]" value="{{ p.color.id|str_form_value }}" />
			{% else %}
			<input type="hidden" name="couleurId[{{ p.numero_ligne }}]" value="0" />
			{% endif %}
			{% if (p.size) %}
			<br />{{ STR_SIZE }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.size.name }} <input type="hidden" name="tailleId[{{ p.numero_ligne }}]" value="{{ p.size.id|str_form_value }}" />
			{% else %}
			<input type="hidden" name="tailleId[{{ p.numero_ligne }}]" value="0" />
			{% endif %}
			{% if (p.email_check) %}
			<br />{{ STR_EMAIL_FRIEND }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.email_check }}<input type="hidden" name="email_check[{{ p.numero_ligne }}]" value="{{ p.email_check|str_form_value }}" />
			{% else %}
			<input type="hidden" value="" name="email_check[{{ p.numero_ligne }}]" />
			{% endif %}
			{% if (p.vacances) %}
			<div class="vacances_available_caddie">
			{{ STR_HOLIDAY_AVAILABLE_CADDIE }} {{ p.vacances.nbjours }} {{ STR_DAYS }}<span>({{ p.vacances.date }})</span>
			</div>
			{% endif %}
		</td>
		<td class="lignecaddie_prix_unitaire" align="center">
			{% if (p.prix_promo) %}
				<del>{{ p.prix }}</del><br />{{ p.prix_promo }}
			{% else %}
				{{ p.prix }}
			{% endif %}
			{% if (p.prix_ecotaxe) %}
			<br /><em>{{ STR_ECOTAXE }}{{ STR_BEFORE_TWO_POINTS }}: {{ p.prix_ecotaxe }}</em>
			{% endif %}
		</td>
		<td class="lignecaddie_prix" align="center">
			{% if (p.option_prix) %}
				{% if (p.option_prix_remise) %}
				<del>{{ p.option_prix_remise }}</del><br />
				{% endif %}
				{{ p.option_prix }}
			{% else %}
				-
			{% endif %}
		</td>
		<td class="lignecaddie_quantite" align="center">
			{% if with_form_fields and p.quantite.value %}
				<input type="text" size="3" style="width:23px" name="quantite[{{ p.numero_ligne }}]" value="{{ p.quantite.value|str_form_value }}" {% if (p.quantite.message) %} onchange="if(this.value>{{ p.quantite.stock_commandable }}) {ldelim }}this.value='{{ p.quantite.stock_commandable }}'; alert('{{ p.quantite.message|filtre_javascript(true,true,true) }}');{rdelim }}"{% endif %} />
				<input type="submit" value="" name="" class="bouton_ok" />
			{% else %}
				{{ p.quantite }}
			{% endif %}
		</td>
		{% if is_conditionnement_module_active %}<td class="lignecaddie_prix" align="center">{{ STR_CONDITIONNEMENT }}</td><td class="lignecaddie_prix" align="center">{{ STR_CONDITIONNEMENT_QTY }}</td>{% endif %}
		<td class="lignecaddie_prix" align="center">- {% if (p.remise) %}{{ p.remise }}{% endif %}</td>
		<td class="lignecaddie_prix" align="center">{{ p.total_prix }}</td>
	</tr>
	{% endfor %}
</table>
{% if with_totals_summary %}
<div id="step2caddie">
	{% if (tarif_paiement) %}
	<p>
		<label>{{ STR_FRAIS_GESTION }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		{{ tarif_paiement }}
	</p>
	{% endif %}
	{% if (total_ecotaxe) %}
	<p>
		<label>{{ STR_ECOTAXE }} {{ taxes_displayed }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		{{ total_ecotaxe }}
	</p>
	{% endif %}
	{% if (total_remise) %}
	<p>
		<label>{{ STR_REMISE }} {{ STR_INCLUDED }} {{ taxes_displayed }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		{{ total_remise }}
	</p>
	{% if (code_promo) %}
	<p class="italic">
		<label>{{ STR_WITH_PROMO_CODE }} {{ code_promo.value }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		{{ code_promo.total }} {% if code_promo.cat_name %}{{ STR_ON_CATEGORY }} {{ code_promo.cat_name }}{% endif %}
	</p>
	{% endif %}
	{% endif %}
	{% if (sool) %}
	<p>
		<label>{{ STR_SMALL_ORDER_OVERCOST_TEXT }} ({{ STR_OFFERED }} {{ STR_FROM }} {{ sool.limit_prix }} {{ STR_TTC }}) {{ taxes_displayed }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		{{ sool.prix }}
	</p>
	{% endif %}
	{% if (transport) %}
	<p>
		<label>{{ transport.shipping_text }} {{ taxes_displayed }}{{ STR_BEFORE_TWO_POINTS }}:</label> {{ transport.prix }}
	</p>
	{% endif %}
	{% if (micro) %}
	<p>
		<label>{{ STR_TOTAL_HT }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		{{ micro.prix_th }}
	</p>
	<p>
		<label>{{ STR_VAT }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		{{ micro.prix_tva }}
	</p>
	{% else %}
	<p>{{ STR_NO_VAT_APPLIABLE }}</p>
	{% endif %}
	{% if (prix_avoir) %}
	<p>
		<label>{{ STR_AVOIR }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		- {{ prix_avoir }}
	</p>
	{% endif %}
	<p class="caddie_net_to_pay">
		<label>{{ net_txt }} {{ STR_TTC }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		{{ prix_total }}
	</p>
	{% if total_points > 0 %}
	<p>
		<label>{{ STR_ORDER_POINT }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		{{ total_points }}&nbsp;{{ STR_GIFT_POINTS }}
	</p>
	{% endif %}
</div>
{% endif %}
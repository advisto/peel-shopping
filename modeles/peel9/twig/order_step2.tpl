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
// $Id: order_step2.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<h1 property="name" class="order_step2">&nbsp;{{ STR_STEP2 }}</h1>
<div class="totalcaddie">
	<p>{{ STR_DATE }}{{ STR_BEFORE_TWO_POINTS }}: {{ date }}</p>
	<div class="row formulaire-achat">
		<div class="col-sm-6">
			<fieldset>
				<legend>{{ STR_INVOICE_ADDRESS }}</legend>
				{% if (societe1) %}
				<p>{{ STR_SOCIETE }}{{ STR_BEFORE_TWO_POINTS }}:	<span class="right">{{ societe1 }}</span></p>
				{% endif %}
				<p>{{ STR_CUSTOMER }}{{ STR_BEFORE_TWO_POINTS }}:	<span class="right">{{ nom1 }} {{ prenom1 }}</span></p>
				<p>{{ STR_TELEPHONE }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ contact1 }}</span></p>
				<p>{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ email1 }}</span></p>
				<p>{{ STR_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ adresse1 }}</span></p>
				<p>{{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ code_postal1 }}</span></p>
				<p>{{ STR_TOWN }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ ville1 }}</span></p>
				<p>{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ pays1 }}</span></p>
				{% if (commentaires) %}
				<p>{{ STR_COMMENTS }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ commentaires|nl2br_if_needed }}</span></p>
				{% endif %}
			</fieldset>
		</div>
		{% if is_mode_transport %}
		<div class="col-sm-6">
			<fieldset>
				<legend>{{ STR_SHIP_ADDRESS }}</legend>
			{% if is_delivery_address_necessary_for_delivery_type %}
				{% if (societe2) %}
				<p>{{ STR_SOCIETE }}{{ STR_BEFORE_TWO_POINTS }}:	<span class="right">{{ societe2 }}</span></p>
				{% endif %}
				<p>{{ STR_CUSTOMER }}{{ STR_BEFORE_TWO_POINTS }}:	<span class="right">{{ nom2 }} {{ prenom2 }}</span></p>
				<p>{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ email2 }}</span></p>
				<p>{{ STR_TELEPHONE }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ contact2 }}</span></p>
				<p>{{ STR_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ adresse2 }}</span></p>
				<p>{{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ code_postal2 }}</span></p>
				<p>{{ STR_TOWN }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ ville2 }}</span></p>
				<p>{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ pays2 }}</span></p>
				<p>{{ STR_PAYMENT }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ payment }}</span></p>
				<p>{{ STR_DELIVERY }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ shipping_zone|html_entity_decode_if_needed }} - {{ shipping_type|html_entity_decode_if_needed }}</span></p>
			{% elseif (shipping_type) %}
				<p>{{ STR_SHIPPING_TYPE }}{{ STR_BEFORE_TWO_POINTS }}: <span class="right">{{ shipping_type|html_entity_decode_if_needed }}</span></p>
			{% endif %}
			</fieldset>
		</div>
	</div>
	{% endif %}
	<div class="clearfix"></div>
	<form class="entryform form-inline" role="form" action="{{ action|escape('html') }}" method="post">
		{% if (icirelais_id_delivery_points_radio_inputs) %}
		{{ icirelais_id_delivery_points_radio_inputs }}
		{% endif %}
		{% if (get_tnt_id_delivery_points_radio_inputs) %}
		{{ get_tnt_id_delivery_points_radio_inputs }}
		{% endif %}
		{{ caddie_products_summary_table }}
		<div class="clearfix"></div>
		<div class="center">
			<input type="submit" value="{{ STR_ORDER|str_form_value }}" class="btn btn-lg btn-primary" />
		</div>
	</form>
</div>
<div id="left">{{ STR_BACK_TO_CADDIE_TXT|nl2br_if_needed }}</div>
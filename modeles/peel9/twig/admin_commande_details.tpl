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
// $Id: admin_commande_details.tpl 55293 2017-11-27 17:19:03Z sdelaporte $
#}<table class="main_table">
	<tr>
		<td class="entete" colspan="2">{{ STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE }}</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
{% if action_name != "insere" and action_name != "ajout" %}
	<tr>
		<td colspan="2">
			<table class="main_table">
				<tr>
					<td colspan="2">
					{% if allow_display_invoice_link %}
						<p><b>{{ STR_INVOICE|upper }}{{ STR_BEFORE_TWO_POINTS }}:</b>
						<img src="{{ pdf_src|escape('html') }}" width="8" height="11" alt="" /> <a href="{{ facture_pdf_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_INVOICE }} PDF</a>
						<img src="{{ pdf_src|escape('html') }}" width="8" height="11" alt="" /> <a href="{{ sendfacture_pdf_href|escape('html') }}" data-confirm="{{ STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL_CONFIRM|str_form_value }}">{{ STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL }}</a>
						{% if is_module_factures_html_active %}
						- <a href="{{ facture_html_href|escape('html') }}">{{ STR_INVOICE }} HTML</a>
						{% endif %}
						</p>
					{% else %}
						{% if STR_ADMIN_CREATE_BILL_NUMBER_BEFORE %}
							<div class="alert alert-info">{{ STR_ADMIN_CREATE_BILL_NUMBER_BEFORE }}</div>
						{% endif %}
					{% endif %}
						{% if specific_pdf_fact_button is empty %}
							<b>{% if button_group_title %}{{ button_group_title|upper }}{% else %}{{ bill_anchor|upper }}{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</b>
							<a href="{{ proforma_pdf_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">
								<img style="width:20px;" src="/images/logoPDF_small.png" alt="logo PDF" /> {{ bill_anchor }}
							</a>
						{% endif %}
						{% if sendproforma_pdf_href %}
							<img src="{{ pdf_src|escape('html') }}" width="8" height="11" alt="" /> <a href="{{ sendproforma_pdf_href|escape('html') }}" data-confirm="{{ bill_send_pdf_anchor_confirm|str_form_value }}">{{ bill_send_pdf_anchor }}</a>
						{% endif %}
						{% if devis_pdf_href %}
						<p><b>{{ STR_QUOTATION|upper }}{{ STR_BEFORE_TWO_POINTS }}:</b>
							<img src="{{ pdf_src|escape('html') }}" width="8" height="11" alt="" /> <a href="{{ devis_pdf_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_QUOTATION }} PDF</a>
						{% if senddevis_pdf_href %}
							<img src="{{ pdf_src|escape('html') }}" width="8" height="11" alt="" /> <a href="{{ senddevis_pdf_href|escape('html') }}" data-confirm="{{ STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL_CONFIRM|str_form_value }}">{{ STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL }}</a>
						{% endif %}
						</p>
						{% endif %}
						{% if disable_bdc is empty %}
						<p><b>{{ STR_ORDER_FORM|upper }}{{ STR_BEFORE_TWO_POINTS }}:</b> <img src="{{ pdf_src|escape('html') }}" width="8" height="11" alt="" /> <a href="{{ bdc_pdf_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_ORDER_FORM }} PDF</a></p>
						{% endif %}
						
						<div class="row">
						{% if specific_pdf_fact_button %}
							<div class="col-md-3">
								<a href="{{ proforma_pdf_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">
									{{ bill_anchor }}
								</a>
							</div>
						{% endif %}
						{% if convert_bill_button %}
							<div class="col-md-3">{{ convert_bill_button }}</div>
						{% endif %}
						{% if send_bill_by_mail %}
							<div class="col-md-6">
							{{ send_bill_by_mail }}
							</div>
						{% endif %}
						</div>
						{% if message %}
						<div class="row">
							<div class="col-md-12">
								<br />
								{{ message }}
							</div>
						</div>
						{% endif %}
						
						
						
						
						
						
						
						
						
						

					{% if is_module_factures_html_active and disable_html_bill is empty %}
						<form class="entryform form-inline" role="form" method="post" action="{{ bdc_action|escape('html') }}">
							<p><b>{{ STR_ORDER_FORM }} HTML</b> {{ STR_ADMIN_COMMANDER_WITH_PARTIAL_AMOUNT }}
							<input type="hidden" name="bdc_code_facture" value="{{ bdc_code_facture|str_form_value }}" />
							<input type="hidden" name="bdc_id" value="{{ bdc_id|str_form_value }}" />
							<input type="hidden" name="bdc_mode" value="bdc" />
							<input type="text" class="form-control" id="bdc_partial" name="bdc_partial" value="{{ bdc_partial|str_form_value }}" style="width:90px" /> {{ bdc_devise }}{{ STR_BEFORE_TWO_POINTS }}:
							<a id="partial_amount_link" onclick="get_partial_amount_link('{{ partial_amount_link_js }}');" target="{{ partial_amount_link_target }}" class="btn btn-primary" href="{{ partial_amount_link_href|escape('html') }}">{{ STR_ADMIN_COMMANDER_OPEN_IN_BROWSER }}</a>
							<input type="submit" name="bdc_sendclient" class="btn btn-primary" value="{{ STR_ADMIN_SEND_TO_CLIENT_BY_EMAIL|str_form_value }}" onclick="return confirm('{{ STR_ADMIN_COMMANDER_SEND_BY_EMAIL_CONFIRM|filtre_javascript(true,true,true) }}');" /></p>
						</form>
					{% endif %}
					{% if is_duplicate_module_active %}
						<a href="{{ dup_href|escape('html') }}" data-confirm="{{ STR_ADMIN_ORDER_DUPLICATE_WARNING|str_form_value }}" title="{{ STR_ADMIN_ORDER_DUPLICATE|str_form_value }}"><img src="{{ dup_src|escape('html') }}" alt="" /></a> <a href="{{ dup_href|escape('html') }}">{{ STR_ADMIN_ORDER_DUPLICATE }}</a>
					{% endif %}
					</td>
				</tr>
			</table>
		</td>
	</tr>
	{% if is_tnt_module_active and etiquette_tnt %}
		{{ etiquette_tnt }}
	{% endif %}
	{% if trackingCreation %}
		<tr>
			<td>{{ trackingCreation }}</td>
		</tr>
	{% endif %}
	{% if is_fianet_sac_module_active %}
		<tr>
			<td><b>{{ STR_ADMIN_COMMANDER_FIANET_FUNCTIONS }}</b>{{ STR_BEFORE_TWO_POINTS }}: {{ fianet_analyse_commandes }}</td>
		</tr>
	{% endif %}
</table>
{% if information_on_this_order_disabled is empty %}
<form id="facturation_form" class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<table>
		{% if order_detail_fields_disable.montant_displayed_prix is empty and montant_displayed_prix>0 %}
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_COMMANDER_ORDER_TOTAL }}{{ STR_BEFORE_TWO_POINTS }}</h2></td>
			</tr>
			<tr>
				<td colspan="2">
					<div class="well">
						{{ STR_ADMIN_TOTAL_TTC_ALL_INCLUDE }} : {{ montant }} {{ ttc }}
							<br />
						{{ STR_ADMIN_INCLUDING_VAT }} : {{ amount_tva }}
							<br />
						{{ STR_ADMIN_TOTAL_HT_ALL_INCLUDE }} : {{ amount_ht }} {{ ht }}
					</div>
				</td>
			</tr>
		{% endif %}
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_COMMANDER_INFORMATION_ON_THIS_ORDER }}</h2></td>
		</tr>
		<tr>
			<td>{{ STR_ORDER_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ order_id }}</td>
		</tr>
		{% if is_kiala_module_active and shortkpid %}
		<tr>
			<td>{{ STR_MODULE_KIALA_TRACKING_ID }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ shortkpid }}</td>
		</tr>
		{% endif %}
		{% if is_ups_module_active and appuId %}
		<tr>
			<td>{{ STR_MODULE_UPS_TRACKING_ID }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ appuId }}</td>
		</tr>
		{% endif %}
		{% if marketplace_orderid %}
		<tr>
			<td>{{ STR_ADMIN_MARKETPLACE_ORDER_ID }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ marketplace_orderid }}</td>
		</tr>
		{% endif %}
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_PAYMENT_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control datepicker" name="a_timestamp" value="{{ date_facture|str_form_value }}" style="width:110px" />
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_INVOICE_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" name="f_datetime" class="form-control datepicker" value="{{ f_datetime|str_form_value }}" style="width:110px" />
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_DELIVERY_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" name="e_datetime" class="form-control datepicker" value="{{ e_datetime|str_form_value }}" style="width:110px" />
			</td>
		</tr>
		{% if (intracom_for_billing) %}
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_VAT_INTRACOM }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ intracom_for_billing }}</td>
		</tr>
		{% endif %}
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_ORDER_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ commande_date }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_ORDER_AUTHOR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input name="email" type="text" class="form-control" value="{{ email|str_form_value }}" /> <a href="{{ email_href|escape('html') }}">{{ email }}</a></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_AUTOCOMPLETE_ORDER_ADRESSES }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input name="autocomplete_order_adresses_with_account_info" type="checkbox" /></td>
		</tr>
	{% else %}
		<form id="facturation_form" class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
		<table style="width:100%;">
	{% endif %}
{% else %}
</table>
<form id="facturation_form" class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<table style="width:100%;">
{% endif %}
	{% if order_detail_fields_disable.numero is empty %}
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_BILL_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control" name="numero" value="{{ numero|str_form_value }}" /><br />
				<div class="alert alert-info"><p>{{ STR_ADMIN_COMMANDER_BILL_NUMBER_EXPLAIN }}</p></div>
			</td>
		</tr>
	{% endif %}
		{% if internal_order_enable %}
 		<tr>
			<td>{{ STR_REFERENCE_IF_KNOWN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="commande_interne" value="{{ commande_interne|str_form_value }}" /></td>
		</tr>
		{% endif %}
		{% if order_detail_fields_disable.site_id is empty %}
		<tr>
			<td>{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" {% if site_id_select_multiple %} name="site_id[]" multiple="multiple" size="5"{% else %} name="site_id"{% endif %}>
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
		{% endif %}
		{% if mode_transport %}
		<tr>
			<td class="form_commande_detail">{{ STR_ADMIN_COMMANDER_TRACKING_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="form_commande_detail">
				<input id="delivery_tracking" name="delivery_tracking" value="{{ delivery_tracking|trim|str_form_value }}" type="text" class="form-control" />
				{% if is_icirelais_module_active %}<div id="tracking_url"></div><br /><a href="javascript:setTracking('{{ MODULE_ICIRELAIS_SETUP_TRACKING_URL|filtre_javascript(true,true,true) }}','{{ STR_MODULE_ICIRELAIS_COMMENT_TRACKING|filtre_javascript(true,true,true) }}','{{ STR_MODULE_ICIRELAIS_ERROR_TRACKING|filtre_javascript(true,true,true) }}')">{{ STR_MODULE_ICIRELAIS_CREATE_TRACKING }}</a>{% endif %}
			</td>
		</tr>
		{% endif %}
		{% if order_detail_fields_disable.payment_select is empty %}
		<tr>
			<td width="350">{{ STR_PAYMENT_MEAN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			{% if (payment_select) %}
				{{ payment_select }}
			{% else %}
				<div class="alert alert-info">{{ STR_ADMIN_COMMANDER_PAYMENT_MEAN_EXPLAIN }}</div>
			{% endif %}
			</td>
		</tr>
		{% endif %}
		{% if order_detail_fields_disable.statut_paiement is empty %}
		<tr>
			<td>{{ STR_ORDER_STATUT_PAIEMENT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="statut_paiement">{{ payment_status_options }}</select>
			</td>
		</tr>
		{% endif %}
		{% if (mode_transport) %}
		<tr>
			<td>{{ STR_ORDER_STATUT_LIVRAISON }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="statut_livraison">{{ delivery_status_options }}</select>

			</td>
		</tr>
		<tr>
			<td>{{ STR_SHIPPING_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="type_transport">
					<option value="">{{ STR_SHIP_TYPE_CHOOSE }}</option>
					{{ delivery_type_options }}
				</select>
				<input type="hidden" name="transport" value="{{ transport|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td>{{ STR_SHIPPING_COST }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control" name="cout_transport" value="{{ cout_transport|str_form_value }}" style="width:100px;" /> {{ devise }} {{ STR_TTC }} {{ STR_ADMIN_INCLUDING_VAT }} 
				<select class="form-control" name="tva_transport">{{ vat_select_options }}</select><br />
				<div class="alert alert-info"><p>({{ STR_ADMIN_COMMANDER_SHIPPING_COST_EXPLAIN }})</p></div>
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2">
				<input type="hidden" name="cout_transport" value="{{ cout_transport|str_form_value }}" />
				<input type="hidden" name="tva_transport" value="{{ tva_transport|str_form_value }}" />
				<input type="hidden" name="type_transport" value="{{ type_transport|str_form_value }}" />
				<input type="hidden" name="transport" value="{{ transport|str_form_value }}" />
			</td>
		</tr>
		{% endif %}
		{% if is_devises_module_active and order_detail_fields_disable.devise is empty %}
		<tr>
			<td>{{ STR_ADMIN_USED_CURRENCY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="devise">
				{% for o in devises_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
				{% endfor %}
				</select>
			</td>
		</tr>
		{% endif %}
		{% if order_detail_fields_disable.tva_small_order_overcost is empty %}
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_SMALL_ORDERS_OVERCOST }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control" name="small_order_overcost_amount" value="{{ small_order_overcost_amount|str_form_value }}" /> {{ devise }} TTC dont TVA <input type="text" class="form-control" name="tva_small_order_overcost" value="{{ tva_small_order_overcost|str_form_value }}" /> {{ devise }}
			</td>
		</tr>
		{% endif %}
		{% if order_detail_fields_disable.currency_rate is empty %}
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_CURRENCY_EXCHANGE_USED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="currency_rate" value="{{ currency_rate|str_form_value }}" /></td>
		</tr>
		{% endif %}
		{% if (total_remise_prix) %}
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_INCLUDING_DISCOUNT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><b>{{ total_remise_prix }}</b></td>
		</tr>
		{% endif %}
		{% if (code_promo) %}
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_COUPON_USED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><b>{{ code_promo }}</b></td>
		</tr>
		{% endif %}
		{% if order_detail_fields_disable.avoir is empty %}
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_INCLUDING_CREDIT_NOTE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input name="avoir" type="text" class="form-control" value="{{ avoir_prix|str_form_value }}" /> {{ devise }}</td>
		</tr>
		{% endif %}
		{% if is_affilie %}
		<tr>
			<td class="label_rouge">{{ STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION }}</td>
			<td class="label_rouge"><strong>{{ affilie_prix }}</strong></td>
		</tr>
		<tr>
			<td class="label_rouge">{{ STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS }}</td>
			<td class="label_rouge">
			<select class="form-control" name="statut_affilie">
				<option value="0"{% if statut_affilie == 0 %} selected="selected"{% endif %}>{{ STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS_TO_COME }}</option>
				<option value="1"{% if statut_affilie == 1 %} selected="selected"{% endif %}>{{ STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS_DONE }}</option>
			</select>
			</td>
		</tr>
		<tr>
			<td class="label_rouge">{{ STR_ADMIN_COMMANDER_AFFILIATE_RELATED_TO_ORDER }}</td>
			<td class="label_rouge"><a href="{{ affilie_href|escape('html') }}">{{ affilie_email }}</a></td>
		</tr>
		{% endif %}
		{% if is_gifts_module_active %}
		<tr>
			<td>{{ STR_ADMIN_COMMANDER_GIFT_POINTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ total_points }} {{ STR_GIFT_POINTS }}<br />
				<input type="hidden" name="delivery_locationid" value="{{ delivery_locationid|str_form_value }}" />
				<input type="hidden" name="points" value="{{ total_points|str_form_value }}" />
				{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:
				<select class="form-control" name="points_etat" style="width:200px;">
					<option value="0"{% if points_etat == 0 %} selected="selected"{% endif %}>{{ STR_ADMIN_COMMANDER_NOT_ATTRIBUTED }}</option>
					<option value="1"{% if points_etat == 1 %} selected="selected"{% endif %}>{{ STR_ADMIN_COMMANDER_ATTRIBUTED }}</option>
					<option value="2"{% if points_etat == 2 %} selected="selected"{% endif %}>{{ STR_ADMIN_COMMANDER_CANCELED }}</option>
				</select>
			</td>
		</tr>
		{% endif %}
		<tr>
			<td class="title_label">{{ STR_ORDER_SUSPECT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input value="1" {% if order_suspect ==1 %} checked="checked"{% endif %} name="suspect" type="checkbox" /></td>
		</tr>
		{% if order_detail_fields_disable.commentaires is empty %}
		<tr>
			<td colspan="2" class="title_label">{{ STR_COMMENTS }}{{ STR_BEFORE_TWO_POINTS }}:<br />
				<textarea class="form-control" name="commentaires" style="width:100%" rows="5" cols="54">{{ commentaires|trim }}</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		{% endif %}
		{% if order_detail_fields_disable.commentaires_admin is empty %}
		<tr>
			<td colspan="2" class="title_label">{{ STR_ADMIN_COMMENTS }}{{ STR_BEFORE_TWO_POINTS }}:<br />
				{{ commentaires_admin|trim }}
			</td>
		</tr>
		{% endif %}
		{% if order_detail_fields_disable.client_address is empty %}
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_COMMANDER_CLIENT_INFORMATION }}</h2></td>
		</tr>
	{% for c in client_infos %}
		{% if c.value == 'bill' %}
		<tr>
			<td colspan="2" class="title_label">{{ STR_INVOICE_ADDRESS }}</td>
		</tr>
		<tr>
			<td colspan="2"><p class="alert alert-info">{{ STR_ADMIN_COMMANDER_BILL_ADDRESS_EXPLAIN }}</p></td>
		</tr>
		{% endif %}
		{% if c.value == 'ship' %}
		<tr {% if order_adresse_difference_color and c.value == 'ship' %} class="bg-danger"{% endif %}>
			<td colspan="2" class="title_label">{{ STR_ADMIN_COMMANDER_SHIPPING_ADDRESS }}</td>
		</tr>
		{% endif %}
		<tr {% if order_adresse_difference_color and c.value == 'ship' %} class="bg-danger"{% endif %}>
			<td>{{ STR_SOCIETE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="societe{{ c.i }}" style="width:100%" value="{{ c.societe|str_form_value }}" /></td>
		</tr>
		<tr {% if order_adresse_difference_color and c.value == 'ship' %} class="bg-danger"{% endif %}>
			<td>{{ STR_LAST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="nom{{ c.i }}" style="width:100%" value="{{ c.nom|str_form_value }}" /></td>
		</tr>
		<tr {% if order_adresse_difference_color and c.value == 'ship' %} class="bg-danger"{% endif %}>
			<td>{{ STR_FIRST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="prenom{{ c.i }}" style="width:100%" value="{{ c.prenom|str_form_value }}" /></td>
		</tr>
		<tr {% if order_adresse_difference_color and c.value == 'ship' %} class="bg-danger"{% endif %}>
			<td>{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="email" class="form-control" name="email{{ c.i }}" style="width:100%" value="{{ c.email|str_form_value }}" /></td>
		</tr>
		<tr {% if order_adresse_difference_color and c.value == 'ship' %} class="bg-danger"{% endif %}>
			<td>{{ STR_TELEPHONE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="contact{{ c.i }}" style="width:100%" value="{{ c.telephone|str_form_value }}" /></td>
		</tr>
		<tr {% if order_adresse_difference_color and c.value == 'ship' %} class="bg-danger"{% endif %}>
			<td>{{ STR_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><textarea class="form-control" name="adresse{{ c.i }}">{{ c.adresse }}</textarea></td>
		</tr>
		<tr {% if order_adresse_difference_color and c.value == 'ship' %} class="bg-danger"{% endif %}>
			<td>{{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="code_postal{{ c.i }}" style="width:100%" value="{{ c.zip|str_form_value }}" /></td>
		</tr>
		<tr {% if order_adresse_difference_color and c.value == 'ship' %} class="bg-danger"{% endif %}>
			<td>{{ STR_TOWN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="ville{{ c.i }}" style="width:100%" value="{{ c.ville|str_form_value }}" /></td>
		</tr>
		<tr {% if order_adresse_difference_color and c.value == 'ship' %} class="bg-danger"{% endif %}>
			<td>{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="pays{{ c.i }}">
					{{ c.country_select_options }}
				</select>
			</td>
		</tr>
	{% endfor %}
	{% endif %}
	{{ specific_field_form_part }}
	{% for f in specific_fields %}
		<tr>
			{% if f.field_title %}
				<td>{{ f.field_title }}{% if f.mandatory %}<span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</td>
				<td>{% include "specific_field.tpl" with {'f':f} %}{{ f.error_text }}</td>
			{% elseif f.field_type == "checkbox" %}
				<td></td>
				<td>{% include "specific_field.tpl" with {'f':f} %}{{ f.error_text }}</td>
			{% else %}
				<td colspan="2">{% include "specific_field.tpl" with {'f':f} %}{{ f.error_text }}</td>
			{% endif %}
		</tr>
	{% endfor %}
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_COMMANDER_ORDERED_PRODUCTS_LIST }}</h2></td>
		</tr>
	</table>
	<div class="table-responsive">
		<table class="table admin_commande_details">
			<thead>
		{% if hook_order_line_html_head %}
			<tr style="background-color:#EEEEEE;">
				<td style="width:20px"></td>
				{{ hook_order_line_html_head }}
			</tr>
		{% else %}
				<tr>
					<td colspan="9" class="title_label">{{ STR_ADMIN_COMMANDER_PRICES_MUST_BE_IN_ORDER_CURRENCY }}</td>
				</tr>
				<tr style="background-color:#EEEEEE">
					<td style="width:20px"></td>
					<td class="title_label center" style="width:60px">{{ STR_ADMIN_ID }}</td>
					<td class="title_label center" style="width:65px">{{ STR_REFERENCE }}</td>
					<td class="title_label center" style="min-width:80px">{{ STR_ADMIN_COMMANDER_PRODUCT_NAME }}</td>
					<td class="title_label center" style="width:70px">{{ STR_SIZE }}</td>
					<td class="title_label center" style="width:70px">{{ STR_COLOR }}</td>
					<td class="title_label center" style="width:40px">{{ STR_QUANTITY_SHORT }}</td>
					<td class="title_label center" style="width:70px">{{ STR_ADMIN_COMMANDER_PRODUCT_LISTED_PRICE }} {{ ttc_ht }} </td>
					<td class="title_label center" style="width:60px">{{ STR_REMISE }} {{ devise }}</td>
					<td class="title_label center" style="width:60px">{{ STR_REMISE }} %</td>
					<td class="title_label center" style="width:70px">{{ STR_UNIT_PRICE }} {{ ttc_ht }}</td>
					<td class="title_label center" style="width:70px">{{ STR_ADMIN_VAT_PERCENTAGE }}</td>
					<td class="title_label center" style="width:120px">{{ STR_ADMIN_CUSTOM_ATTRIBUTES }}</td>
					<td class="title_label center" style="width:20px">{{ STR_IMAGE }}</td>
				</tr>
			{% endif %}
			</thead>
			{# Attention : pour éviter bug IE8, il ne doit pas y avoir d'espaces entre tbody et tr ! #}
			<tbody class="sortable ui-sortable" id="dynamic_order_lines">{% for o in order_lines %}{{ o }}{% endfor %}</tbody>
		</table>
	</div>
	<div class="center">
		{% if (code_promo) %}
				{% if percent_code_promo > 0 %}
				<input type="hidden" name="percent_code_promo" value="{{ percent_code_promo|str_form_value }}" />
				{% elseif valeur_code_promo > 0 %}
				<input type="hidden" name="valeur_code_promo" value="{{ valeur_code_promo|str_form_value }}" />
				{% endif %}
				<input type="hidden" name="code_promo" value="{{ code_promo|str_form_value }}" />
			{% endif %}
				{{ form_token }}
				<input type="hidden" name="action" value="{{ action_name|str_form_value }}" />
				<input type="hidden" name="id" value="{{ id|str_form_value }}" />
				<input type="hidden" name="id_utilisateur" value="{{ id_utilisateur|str_form_value }}" />
				<input type="hidden" name="lang" value="{{ lang|str_form_value }}" />
				<p><input id="nb_produits" type="hidden" name="nb_produits" value="{{ nb_produits|str_form_value }}" /></p>
		</div>
{% if is_order_modification_allowed %}
	<div class="entete">{{ STR_ADMIN_COMMANDER_ADD_PRODUCTS_TO_ORDER }}</div>
	<div class="add_line_order">
		{% if add_empty_line %}
			{{ add_empty_line }}
		{% else %}
		<script><!--//--><![CDATA[//><!--
			var arr0 = {
				"id" : "0", 
				"ref" : "",
				"nom" : "",
				"quantite" : "1",
				"image_thumbs" : "",
				"image_large" : "",
				"purchase_prix_ht" : "0.00",
				"tva_options_html" : "{{ tva_options_html|filtre_javascript(true,true,true,true,false) }}",
				"color_options_html" : "",
				"size_options_html" : "",
				"purchase_prix" : "0.00",
				"prix_cat" : "0.00",
				"prix_cat_ht" : "0.00",
				"remise" : "0",
				"remise_ht" : "0",
				"percent" : "0"
			}
			//--><!]]></script>
		<p style="margin-top:0px;"><input value="{{ STR_ADMIN_ADD_EMPTY_LINE|str_form_value }}" name="add_product" class="btn btn-primary" type="button" onclick="add_products_list_line(arr0, '{{ STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER|filtre_javascript(true,true,true) }}', 'order'); return false;" /> 
		{% endif %}
		{{ STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" class="form-control" id="suggestions_input" name="suggestions_input" style="width:200px" value="" onkeyup="lookup(this.value, '{{ id_utilisateur }}', '{{ zone_tva }}', '{{ devise }}', '{{ currency_rate }}', 'order', '#suggestions', 'products', '{{ rpc_path }}', '{{ this_page }}');" onclick="lookup(this.value, '{{ id_utilisateur }}', '{{ zone_tva }}', '{{ devise }}', '{{ currency_rate }}', 'order', '#suggestions', 'products', '{{ rpc_path }}', '{{ this_page }}');" /></p>
		<div class="suggestions" id="suggestions"></div>
	</div>
{% endif %}
<br />
	{% if (get_mode) %}
		{% if get_mode == "insere" or get_mode == "ajout" %}
						<input type="submit" value="{{ STR_ADMIN_UTILISATEURS_CREATE_ORDER|str_form_value }}" class="btn btn-primary" />
		{% else %}
			{% if is_order_modification_allowed %}
						<input type="submit" value="{{ STR_ADMIN_FORM_SAVE_CHANGES|str_form_value }}" class="btn btn-primary" />
			{% else %}
						{{ STR_ADMIN_COMMANDER_WARNING_EDITION_NOT_ALLOWED }}
			{% endif %}
		{% endif %}
	{% else %}
						<input type="submit" value="{{ STR_ADMIN_FORM_SAVE_CHANGES|str_form_value }}" class="btn btn-primary" />
	{% endif %}
</form>
{% if (parrainage_form) %}
<form class="entryform form-inline" role="form" method="post" action="{{ parrainage_form.action }}">
	<input type="hidden" name="mode" value="parrain" />
	<input type="hidden" name="id" value="{{ parrainage_form.id|str_form_value }}" />
	<input type="hidden" name="id_parrain" value="{{ parrainage_form.id_parrain|str_form_value }}" />
	<input type="hidden" name="email_parrain" value="{{ parrainage_form.email|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td colspan="2" class="entete">{{ STR_ADMIN_COMMANDER_SPONSORSHIP_MODULE }}</td>
		</tr>
		<tr>
			<td colspan="2">
				{{ STR_ADMIN_COMMANDER_ORDER_EMITTED_BY_GODCHILD }} <a href="{{ parrainage_form.href|escape('html') }}">{{ parrainage_form.email }}</a>.<br />
				{{ STR_ADMIN_COMMANDER_THANK_SPONSOR_WITH_CREDIT_OF }} <input type="text" class="form-control" size="5" maxlength="3" name="avoir" value="{{ site_avoir|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }}<br />
				<div class="alert alert-info"><p>{{ STR_ADMIN_COMMANDER_THANK_SPONSOR_WITH_CREDIT_EXPLAIN }}</p></div>
			</td>
		</tr>
		<tr>
			<td class="center" colspan="2">
				<input type="submit" class="btn btn-primary" value="{{ STR_ADMIN_COMMANDER_GIVE_CREDIT|str_form_value }}" />
			</td>
		</tr>
	</table>
</form>
{% endif %}
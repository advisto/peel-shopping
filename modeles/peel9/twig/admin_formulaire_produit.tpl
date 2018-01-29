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
// $Id: admin_formulaire_produit.tpl 55294 2017-11-27 17:22:30Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<input type="hidden" name="format" value="html" />
	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab1" data-toggle="tab">{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</a></li>
	{% for l in langs %}
			<li><a href="#tab_{{ l.lng|upper }}" data-toggle="tab">{{ lang_names[l.lng] }}</a></li>
	{% endfor %} 
			<li><a href="#tab2" data-toggle="tab">{{ STR_ADMIN_PRODUITS_FILES_HEADER }}</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab1">
	<table class="full_width">
		<tr>
			<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}{{ STR_BEFORE_TWO_POINTS }}:</h2></td>
		</tr>
		<tr>
			<td class="title_label top">{{ STR_CATEGORY }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" id="categories" name="categories[]" multiple="multiple" size="10" style="width: 100%">
				{{ categorie_options }}
				</select>
				{{ categorie_error }}
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_PRODUITS_POSITION_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}: </td>
			<td><input type="text" class="form-control" value="{{ position|html_entity_decode_if_needed|str_form_value }}" name="position" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}: </td>
			<td>
				<select class="form-control" name="site_id" {% if site_id_select_multiple %} name="site_id[]" multiple="multiple" size="5"{% else %} name="site_id"{% endif %}>
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
	{% if (STR_ADMIN_SITE_COUNTRY) %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_SITE_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				{{ site_country_checkboxes }}
			</td>
		</tr>
	{% endif %}
	{% if is_module_gift_checks_active %}
		<tr>
			<td class="title_label top">{{ STR_ADMIN_PRODUITS_IS_GIFT_CHECK }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" id="on_check" name="on_check" value="1"{% if is_on_check %} checked="checked"{% endif %} /></td>
		</tr>
	{% endif %}
	{% if products_table_additionnal_fields %}
		{% for field in products_table_additionnal_fields_array %}
 		<tr>
			<td class="title_label top">{{ field.title }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			{% if field.type=='checkbox' %}
			<td><input type="checkbox" value="1" {% if field.value ==1 %} checked="checked"{% endif %} id="{{ field.name|str_form_value }}" name="{{ field.name|str_form_value }}" /></td>
			{% else %}
			<td><input type="text" class="form-control" value="{{ field.value|str_form_value }}" id="{{ field.name|str_form_value }}" name="{{ field.name|str_form_value }}" /></td>
			{% endif %}
		</tr>
		{% endfor %} 
	{% endif %}
	{% if skip_home_special_products %}
		<tr>
			<td class="title_label top">{{ STR_ADMIN_PRODUITS_IS_ON_HOME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_special" value="1"{% if is_on_special %} checked="checked"{% endif %} /></td>
		</tr>
	{% endif %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_IS_ON_NEW }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_new" value="1"{% if is_on_new %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="title_label top">{{ STR_ADMIN_PRODUITS_IS_ON_PROMOTIONS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			{% if site_auto_promo == '0' %}
			<td><input type="checkbox" name="on_promo" value="1"{% if is_on_promo %} checked="checked"{% endif %} /></td>
			{% else %}
			<td class="top"><div class="alert alert-info">{{ STR_ADMIN_PRODUITS_IS_ON_PROMOTIONS_EXPLAIN }}</div></td>
			{% endif %}
		</tr>
		<tr>
			<td class="title_label top">{{ STR_ADMIN_PRODUITS_IS_ON_RESELLER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_reseller" value="1"{% if is_on_reseller %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="title_label top">{{ STR_ADMIN_PRODUITS_EXTRA_LINK }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="extra_link" value="{{ extra_link|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
	{% if is_best_seller_module_active %}
		<tr>
			<td class="title_label top">{{ STR_ADMIN_PRODUITS_BEST_SELLERS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_top" value="1"{% if is_on_top %} checked="checked"{% endif %} /></td>
		</tr>
	{% endif %}
	{% if display_recommanded_product_on_cart_page %}
		<tr>
			<td class="title_label top">{{ STR_ADMIN_PRODUITS_IS_ON_CART_PAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="recommanded_product_on_cart_page" value="1"{% if is_recommanded_product_on_cart_page %} checked="checked"{% endif %} /></td>
		</tr>
	{% endif %}
	{% if is_rollover_module_active %}
		<tr>
			<td class="title_label top">{{ STR_ADMIN_PRODUITS_IS_ON_ROLLOVER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_rollover" value="1"{% if is_on_rollover %} checked="checked"{% endif %} /></td>
		</tr>
	{% endif %}
		<tr>
			<td class="title_label top"><label for="on_estimate">{{ STR_ADMIN_PRODUITS_IS_ON_ESTIMATE }}</label>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" id="on_estimate" name="on_estimate" value="1"{% if is_on_estimate %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			 <input type="radio" name="etat" value="1"{% if etat == '1' %} checked="checked"{% endif %} /> {{ STR_ADMIN_ONLINE }}<br />
			 <input type="radio" name="etat" value="0"{% if etat == '0' or not(etat) %} checked="checked"{% endif %} /> {{ STR_ADMIN_OFFLINE }}
			</td>
		</tr>
	{% if product_multiple_references_form %}
		<tr>
			<td class="title_label top">{{ STR_ADMIN_PRODUCT_MULTIPLE_REFERENCE_FORM }} {{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ product_multiple_references_form }}</td>
		</tr>
	{% endif %}
		<tr>
			<td class="title_label">{{ STR_REFERENCE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="reference" value="{{ reference|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_REFERENCE_FOURNISSEUR }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="reference_fou" value="{{ reference_fou|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_TECHNICAL_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="technical_code" value="{{ technical_code|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_EAN_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="ean_code" value="{{ ean_code|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_PRICE_IN }} <b>{{ site_symbole }} {{ ttc_ht }}</b>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input type="text" class="form-control" name="prix" value="{{ prix|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_PRICE_PROMOTION }} <b>{{ site_symbole }} {{ ttc_ht }}</b>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input type="text" class="form-control" name="prix_promo" value="{{ prix_promo|str_form_value }}" /></td>
		</tr>
	{% if is_reseller_module_active %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_RESELLER_PRICE_IN }} <b>{{ site_symbole }} {{ reseller_price_taxes_txt }}</b>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input type="text" class="form-control" name="prix_revendeur" value="{{ prix_revendeur|str_form_value }}" /></td>
		</tr>
	{% endif %}
	{% if is_conditionnement_module_active %}
		<tr>
			<td class="title_label">{{ STR_CONDITIONNEMENT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input type="text" class="form-control" name="conditionnement" value="{{ conditionnement|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_CONDITIONNEMENT_TEXT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input type="text" class="form-control" name="conditioning_text" value="{{ conditioning_text|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_UNIT_PER_PALLET }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input type="text" class="form-control" name="unit_per_pallet" value="{{ unit_per_pallet|str_form_value }}" /></td>
		</tr>
	{% endif %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_PURCHASE_PRICE_IN }} <b>{{ site_symbole }} {{ STR_HT }}</b>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input type="text" class="form-control" name="prix_achat" value="{{ prix_achat|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_VAT_PERCENTAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="tva">{{ vat_select_options }}</select>
			</td>
		</tr>
	{% if is_module_ecotaxe_active %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_ECOTAX }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="id_ecotaxe" style="width:500px">
					<option value="">{{ STR_CHOOSE }}...</option>
					<option value="">{{ STR_ADMIN_NOT_APPLICABLE }}</option>
					{% for o in ecotaxe_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.code }} {{ o.nom|str_shorten(50,'','...') }}{{ STR_BEFORE_TWO_POINTS }}: {{ o.prix }} {{ STR_TTC }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
	{% else %}
		<input type="hidden" name="id_ecotaxe" value="" />
	{% endif %}
	{% if (payment_by_product) %}
		{{ payment_by_product }}
	{% endif %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_DISCOUNT_PERCENTAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="promotion" value="{{ promotion|str_form_value }}" /> % {{ STR_ADMIN_PRODUITS_DISCOUNT_PERCENTAGE_OVER_LISTED_PRICE }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_WEIGHT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="poids" value="{{ poids|str_form_value }}" /> {{ STR_ADMIN_PRODUITS_WEIGHT_UNIT }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_VOLUME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="volume" value="{{ volume|str_form_value }}" /> {{ STR_ADMIN_PRODUITS_WEIGHT_UNIT }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_DISPLAY_PRICE_PER_KILO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="radio" name="display_price_by_weight" value="1"{% if display_price_by_weight == '1' %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_DISPLAY_PRICE_PER_LITER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="radio" name="display_price_by_weight" value="2"{% if display_price_by_weight == '2' %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_DISPLAY_NO_PRICE_PER_UNIT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="radio" name="display_price_by_weight" value="0"{% if display_price_by_weight == '0' %} checked="checked"{% endif %} /></td>
		</tr>
	{% if is_lot_module_active %}
		<tr>
			<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_PRODUITS_LOT_PRICE }}{{ STR_BEFORE_TWO_POINTS }}:</h2></td>
		</tr>
		{% if mode == "maj" %}
		
			{% if module_departement_active %}
				{% for lot in lots %}
			<tr>
				<td class="title_label">{{ lot.lot_explanation_table }}</td>
			</tr>
			<tr>
				<td class="title_label">
					<a href="{{ lot.lot.lot_href|escape('html') }}">{{ STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE }} {{ lot.zone_name }}</a>
					{% if lot.lot_supprime_href is defined %}
					/ <a href="{{ lot.lot_supprime_href|escape('html') }}" data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}">{{ STR_DELETE }}</a>
					{% endif %}
				</td>
			</tr>
				{% endfor %}
			{% else %}
			<tr>
				<td class="title_label">{{ lot_explanation_table }}</td>
			</tr>
			<tr>
				<td class="title_label">
					<a href="{{ lot_href|escape('html') }}">{{ STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE }}</a>
					{% if (lot_supprime_href) %}
					/ <a href="{{ lot_supprime_href|escape('html') }}" data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}">{{ STR_DELETE }}</a>
					{% endif %}
				</td>
			</tr>
			{% endif %}
		{% else %}
		<tr>
			<td class="title_label" colspan="2">{{ STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE_EXPLAIN }}</td>
		</tr>
		{% endif %}
	{% endif %}
		<tr>
			<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_PRODUITS_LINK_PRODUCT_TO_SUPPLIER }}{{ STR_BEFORE_TWO_POINTS }}:</h2></td>
		</tr>
		<tr>
			<td colspan="2">
				<select class="form-control" name="id_utilisateur" style="width:100%" size="5">
					<option value="0">-------------------------------------------</option>
					{% for o in util_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_PRODUITS_CHOOSE_BRAND }}{{ STR_BEFORE_TWO_POINTS }}:</h2></td>
		</tr>
		<tr>
			<td colspan="2">
				<select class="form-control" name="id_marque" style="width:100%" size="5">
					<option value="0">-------------------------------------------</option>
					{% for o in marques_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
	{% if (gestion_stock) %}
		{{ gestion_stock }}
	{% endif %}
		<tr>
			<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_PRODUITS_CHOOSE_REFERENCE }}{{ STR_BEFORE_TWO_POINTS }}:</h2></td>
		</tr>
		<tr>
			<td colspan="2">
				<script><!--//--><![CDATA[//><!--
					 var new_order_line_html = '<tr class="top" id="sortable_[i]"><td><img src="{{ administrer_url }}/images/b_drop.png" alt="{{ STR_DELETE }}" onclick="if(bootbox.confirm(\'{{ STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM|filtre_javascript(true,true,false) }}\', function(result) {if(result) {admin_delete_products_list_line([i], true);} } ))return false;" title="{{ STR_ADMIN_PRODUCT_ORDERED_DELETE }}" style="cursor:pointer" /> <input type="hidden" name="references[]" value="[id]"></td><td>[ref] [nom]</td></tr>';
				//--><!]]></script>
				<div class="full_width" style="border: 1px #000000 dotted; background-color: #FAFAFA; padding:5px">
					<table class="table admin_commande_details">
						<thead>
							<tr style="background-color:#EEEEEE">
								<td colspan="{% if associated_product_multiple_add_to_cart %}3{% else %}2{% endif %}" class="title_label center" style="width:65px">{{ STR_REFERENCE }} - {{ STR_ADMIN_NAME }}</td>
							</tr>
						</thead>
						{# Attention : pour éviter bug IE8, il ne doit pas y avoir d'espaces entre tbody et tr ! #}
						<tbody id="dynamic_order_lines">{% for o in produits_options %}<tr class="top" id="sortable_{{ o.i }}">
									<td>
										<img src="{{ administrer_url }}/images/b_drop.png" alt="{{ STR_DELETE }}" onclick="if(bootbox.confirm('{{ STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM|filtre_javascript(true,true,false) }}', function(result) {if(result) {admin_delete_products_list_line({{ o.i }}, true);} } ))return false;" title="{{ STR_ADMIN_PRODUCT_ORDERED_DELETE }}" style="cursor:pointer" />
										<input type="hidden" name="references[]" value="{{ o.value|str_form_value }}">
									</td>
									<td>{{ o.reference }} {{ o.name }}</td>
									{% if associated_product_multiple_add_to_cart %}<td><input type="text" name="quantity_product_reference[]" value="{{ o.qt }}" /></td>{% endif %}
								</tr>{% endfor %}</tbody>
					</table>
					<p style="margin-top:0px;">{{ STR_DELETE }} {{ STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" class="form-control" id="suggestions_input" name="suggestions_input" style="width:200px" value="" onkeyup="lookup(this.value, '', '', '', '', '', '#suggestions', 'products');" onclick="lookup(this.value, '', '', '', '', '', '#suggestions', 'products');" /></p>
					<div class="suggestions" id="suggestions"></div>
					<input id="nb_produits" type="hidden" name="nb_produits" value="{{ nb_produits|str_form_value }}" />
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_PRODUITS_CHOOSE_REFERENCE_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td class="top title_label">{{ STR_ADMIN_PRODUITS_AUTO_REF_PRODUCT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_ref_produit" value="1"{% if is_on_ref_produit %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="top title_label">{{ STR_ADMIN_PRODUITS_AUTO_REF_NUMBER_PRODUCTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input name="nb_ref_produits" type="text" class="form-control" value="{{ nb_ref_produits|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_PRODUITS_MANAGE_CRITERIA }}</h2></td>
		</tr>
	{% if is_attributes_module_active %}
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_PRODUITS_MANAGE_CRITERIA_INTRO }}{{ STR_BEFORE_TWO_POINTS }}: {% if mode == "maj" %}<a href="{{ produits_attributs_href|escape('html') }}" class="alert-link" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_PRODUITS_MANAGE_CRITERIA_LINK }}</a>{% else %}{{ STR_ADMIN_PRODUITS_MANAGE_CRITERIA_TEASER }} <a href="{{ nom_attributs_href|escape('html') }}">{{ nom_attributs_href }}</a>{% endif %}</div></td>
		</tr>
	{% endif %}
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_PRODUITS_MANAGE_COLORS_SIZES_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td class="title_label" colspan="2">{{ STR_ADMIN_PRODUITS_OTHER_OPTION }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_PRODUITS_PRODUCT_COLORS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">
				<select class="form-control" name="couleurs[]" multiple="multiple" style="width:100%" size="5">
					<option value="">-------------------------------------------</option>
					{% for o in couleurs_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<td class="title_label" colspan="2">{{ STR_ADMIN_PRODUITS_OTHER_OPTION }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_PRODUITS_PRODUCT_SIZES }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">
				<select class="form-control" name="tailles[]" multiple="multiple" style="width:100%" size="5">
					<option value="">-------------------------------------------</option>
					{% for o in tailles_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }}{% if (o.prix) %} - {{ o.prix }} {{ STR_TTC }}{% endif %}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
	{% if is_download_module_active %}
		<tr>
			<td class="bloc" colspan="2"><h2>{{ STR_ADMIN_PRODUITS_DOWNLOAD_PRODUCTS_HEADER }}{{ STR_BEFORE_TWO_POINTS }}:</h2></td>
		</tr>
		<tr>
			<td class="top title_label">{{ STR_ADMIN_PRODUITS_IS_ON_DOWLOAD }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_download" value="1"{% if is_on_download %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="top title_label">{{ STR_ADMIN_PRODUITS_FILE_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input name="zip" type="text" class="form-control" value="{{ zip|str_form_value }}" /></td>
		</tr>
	{% endif %}
	{% if is_flash_sell_module_active %}
		<tr><td class="bloc" colspan="2"><h2>{{ STR_ADMIN_PRODUITS_FLASH_SALE }}</h2></td></tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_PRODUITS_FLASH_SALE_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_FLASH_PRICE }} <b>{{ site_symbole }} {{ ttc_ht }}</b>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input type="text" class="form-control" name="prix_flash" value="{{ prix_flash|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_FLASH_START_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="flash_start" class="datetimepicker" value="{% if (flash_start) %}{{ flash_start|str_form_value }}{% endif %}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_FLASH_END_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="flash_end" class="datetimepicker" value="{% if (flash_end) %}{{ flash_end|str_form_value }}{% endif %}" /></td>
		</tr>
		<tr>
			<td class="top title_label">{{ STR_ADMIN_PRODUITS_IS_ON_FLASH }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_flash" value="1"{% if is_on_flash %} checked="checked"{% endif %} /></td>
		</tr>
	{% endif %}
	{% if is_gifts_module_active %}
		<tr><td class="bloc" colspan="2"><h2>{{ STR_ADMIN_PRODUITS_GIFT_CHECK_HEADER }}</h2></td></tr><tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_PRODUITS_GIFT_CHECK_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td class="top title_label">{{ STR_ADMIN_PRODUITS_IS_ON_GIFT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" id="on_gift" name="on_gift" value="1"{% if is_on_gift %} checked="checked"{% endif %} /></td>
		</tr>
		<tr class="title_label" id="on_gift_points_tr">
			<td>{{ STR_ADMIN_PRODUITS_GIFT_POINTS_NEEDED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="number" class="form-control" value="{{ on_gift_points|html_entity_decode_if_needed|str_form_value }}" name="on_gift_points" id="on_gift_points" size="3" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_GIFT_POINTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="number" class="form-control" name="points" value="{{ points|str_form_value }}" /></td>
		</tr>
	{% endif %}
	</table>
				</div>
			<div class="tab-pane" id="tab2">
	<table class="full_width">
		<tr>
			<td colspan="2" class="bloc"><h2>{{ STR_ADMIN_PRODUITS_FILES_HEADER }}{{ STR_BEFORE_TWO_POINTS }}:</h2></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{{ STR_ADMIN_PRODUITS_VIDEO_TAG }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2" class="title_label"><textarea class="form-control" name="youtube_code" style="height:70px;width: 100%;" rows="50" cols="10">{{ youtube_code }}</textarea></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="default_image" value="{{ default_image|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info"><p>{{ STR_ADMIN_PRODUITS_FILES_EXPLAIN }}</p></div></td>
		</tr>
	{% for i in files|keys %}
		{% if (files.i) %}
		<tr>
			<td class="title_label">{% if files.i.type == 'img' %}{{ STR_IMAGE }} {% else %}{{ STR_FILE }} {% endif %}{{ i }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{% include "uploaded_file.tpl" with {'f':files.i,'STR_DELETE':STR_DELETE_THIS_FILE } %}</td>
		</tr>
		{% else %}
		<tr>
			<td class="title_label">{{ STR_FILE }} {{ i }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input name="image{{ i }}" type="file" value="" /></td>
		</tr>
		{% endif %}
	{% endfor %}
	{% for c in colors %}
		<tr>
			<td colspan="2" class="title_label"><br />{{ STR_ADMIN_PRODUITS_FILE_FOR_COLOR }} {{ c.nom }} ({{ STR_ADMIN_PRODUITS_DEFAULT_COLOR_IN_FRONT }} <input type="radio" name="default_color_id"{% if c.issel %} checked="checked"{% endif %}value="{{ c.coul }}" />)</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_PRODUITS_FILES_EXPLAIN }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input class="form-control" name="default_image{{ c.id }}" value="{{ c.default_image|str_form_value }}" /> {{ STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER_CONSTRAINT }}
			</td>
		</tr>
		{% if (c.images) %}
			{% for i in c.images|keys %}
		<tr>
			<td class="title_label">{{ STR_IMAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				{% if c.images.i %}
				{% include "uploaded_file.tpl" with {'f':c.images.i,'STR_DELETE':STR_DELETE_THIS_FILE } %}
				{% else %}
				<input class="form-control" name="imagecouleur{{ c.id }}_{{ i }}" type="file" value="" />
				{% endif %}
			{% endfor %}
		{% else %}
		<tr>
			<td class="title_label" id="td_{{ c.id }}" colspan="2"><a href="#" onclick="addImagesFields('{{ c.id|filtre_javascript }}','{{ upload_images_per_color|filtre_javascript }}');return false">{{ STR_ADMIN_PRODUITS_ADD_INPUT_FOR_THIS_COLOR }}</a></td>
		</tr>
		{% endif %}
	{% endfor %}
	</table>
			</div>
	{% for l in langs %}
			<div class="tab-pane" id="tab_{{ l.lng|upper }}">
			<div class="bloc"><h2>{{ STR_ADMIN_PRODUITS_TEXT_RELATED_IN }} {{ l.lng|upper }}</h2></div>
		{% if (not (product_name_forced_lang)) or l.lng==product_name_forced_lang %}
 		<label>{{ STR_ADMIN_NAME }} {{ l.lng|upper }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label>
		<input type="text" class="form-control" name="nom_{{ l.lng }}" value="{{ l.nom|html_entity_decode_if_needed|str_form_value }}" /><br />
		{{ l.nom_error }}
		{% endif %}
		<label>{{ STR_ADMIN_PRODUITS_SHORT_DESCRIPTION }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		<input style="width:100%" name="descriptif_{{ l.lng }}" type="text" class="form-control" value="{{ l.descriptif|html_entity_decode_if_needed|str_form_value }}" />
		<label>{{ STR_ADMIN_PRODUITS_DESCRIPTION }}{{ STR_BEFORE_TWO_POINTS }}:<br /></label>
		{{ l.description_te }}</td>
		<label>{{ STR_ADMIN_META_TITLE }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		<div class="alert alert-info">{{ STR_ADMIN_META_TITLE_EXPLAIN }}</div>
		<input type="text" class="form-control" name="meta_titre_{{ l.lng }}" size="70" value="{{ l.meta_titre|html_entity_decode_if_needed|str_form_value }}" />
		<label>{{ STR_ADMIN_META_KEYWORDS }} {{ l.lng|upper }} ({{ STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN }}){{ STR_BEFORE_TWO_POINTS }}:</label>
		<div class="alert alert-info">{{ STR_ADMIN_META_KEYWORDS_EXPLAIN }}</div>
		<textarea class="form-control" name="meta_key_{{ l.lng }}" style="width:100%" rows="2" cols="54">{{ l.meta_key|html_entity_decode_if_needed|strip_tags }}</textarea>
		<label>{{ STR_ADMIN_META_DESCRIPTION }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		<div class="alert alert-info">{{ STR_ADMIN_META_DESCRIPTION_EXPLAIN }}</div>
		<textarea class="form-control" name="meta_desc_{{ l.lng }}" style="width:100%" rows="3" cols="54">{{ l.meta_desc|nl2br_if_needed|html_entity_decode_if_needed|strip_tags }}</textarea>
		<br />
		{% if is_id %}
		<div class="bloc"><h2>{{ STR_ADMIN_PRODUITS_MANAGE_TABS_TITLE }} {{ l.lng|upper }}</h2></div>
		<div class="alert alert-info">{{ STR_ADMIN_PRODUITS_MANAGE_TABS_EXPLAIN }}</div>
		<label><a href="{{ l.modif_tab_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_PRODUITS_MANAGE_TAB }} {{ l.lng|upper }}</a></label>
		{% else %}
		<div class="bloc"><h2>{{ STR_ADMIN_PRODUITS_MANAGE_TAB_EXPLAIN }}</h2></div>
		{% endif %}
			</div>
	{% endfor %}
			<div class="tab-pane" id="tab4">
	<table class="full_width">
	</table>
			</div>
			<div class="tab-pane" id="tab5">
	<table class="full_width">
	</table>
			</div>
		</div>
	</div>
	<div class="center" style="padding:10px;">
				<script><!--//--><![CDATA[//><!--
				function verif_form() {
					// Pas de catégorie sélectionnée, pourtant obligatoire sauf pour les chèques cadeaux.
					if($('#on_check').is(':checked')) {
						return true;
					} else if ($("#categories option:selected").text()=="") {
						bootbox.alert("{{ STR_ERR_CAT|filtre_javascript(true,false,true,false) }}");
						return false;
					} else {
						return true;
					}
				}
				//--><!]]></script>
				<p><input class="btn btn-primary" {%if allow_products_without_category is empty %} onclick="if (verif_form() == false) {% endif %} { return false; }}" type="submit" value="{{ normal_bouton|str_form_value }}" /></p>
	</div>
</form>
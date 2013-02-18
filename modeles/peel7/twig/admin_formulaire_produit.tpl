{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_produit.tpl 35330 2013-02-16 18:27:13Z gboussin $
#}<form method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<input type="hidden" name="format" value="html" />
	<table class="main_table">
	{% if create_product_process %}
		<tr>
			<td colspan="2">
				<table class="full_width">
					<tr>
						<td class="entete">{% if get_mode == "modif" %}{{ STR_ADMIN_PRODUITS_UPDATE }} "{{ nom }}" - <a href="{{ prod_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_SEE_RESULT_IN_REAL }}</a>{% else %}{{ STR_ADMIN_PRODUITS_ADD }}{% endif %}</td>
						<td class="entete" align="right" width="280">{{ STR_ADMIN_PRODUITS_VIEWS_COUNT }}{{ STR_BEFORE_TWO_POINTS }}: {{ nb_view }}</td>
					</tr>
				</table>
			</td>
		</tr>
	{% endif %}
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td class="top" style="width:320px">{{ STR_CATEGORY }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select id="categories" name="categories[]" multiple="multiple" size="7" style="width: 100%">
				{{ categorie_options }}
				</select>
				{{ categorie_error }}
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="global_help">{{ STR_ADMIN_PRODUITS_POSITION_EXPLAIN }}</div></td>

		</tr>
		<tr>
			<td>{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}: </td>
			<td><input type="text" value="{{ position|html_entity_decode_if_needed|str_form_value }}" name="position" size="1" /></td>
		</tr>
	{% if is_module_gift_checks_active %}
		<tr>
			<td class="top">{{ STR_ADMIN_PRODUITS_IS_GIFT_CHECK }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" id="on_check" name="on_check" value="1"{% if is_on_check %} checked="checked"{% endif %} /></td>
		</tr>
	{% endif %}
		<tr>
			<td class="top">{{ STR_ADMIN_PRODUITS_IS_ON_HOME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_special" value="1"{% if is_on_special %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_PRODUITS_IS_ON_NEW }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_new" value="1"{% if is_on_new %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="top">{{ STR_ADMIN_PRODUITS_IS_ON_PROMOTIONS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			{% if site_auto_promo == '0' %}
			<td><input type="checkbox" name="on_promo" value="1"{% if is_on_promo %} checked="checked"{% endif %} /></td>
			{% else %}
			<td class="top"><div class="global_help">{{ STR_ADMIN_PRODUITS_IS_ON_PROMOTIONS_EXPLAIN }}</div></td>
			{% endif %}
		</tr>
		<tr>
			<td class="top">{{ STR_ADMIN_PRODUITS_EXTRA_LINK }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:250px" type="text" name="extra_link" value="{{ extra_link|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
	{% if is_best_seller_module_active %}
		<tr>
			<td class="top">{{ STR_ADMIN_PRODUITS_BEST_SELLERS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_top" value="1"{% if is_on_top %} checked="checked"{% endif %} /></td>
		</tr>
	{% endif %}
	{% if is_rollover_module_active %}
		<tr>
			<td class="top">{{ STR_ADMIN_PRODUITS_IS_ON_ROLLOVER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_rollover" value="1"{% if is_on_rollover %} checked="checked"{% endif %} /></td>
		</tr>
	{% endif %}
		<tr>
			<td class="top"><label for="on_estimate">{{ STR_ADMIN_PRODUITS_IS_ON_ESTIMATE }}</label>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" id="on_estimate" name="on_estimate" value="1"{% if is_on_estimate %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			 <input type="radio" name="etat" value="1"{% if etat == '1' %} checked="checked"{% endif %} /> {{ STR_ADMIN_ONLINE }}<br />
			 <input type="radio" name="etat" value="0"{% if etat == '0' or not(etat) %} checked="checked"{% endif %} /> {{ STR_ADMIN_OFFLINE }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_REFERENCE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:250px" type="text" name="reference" value="{{ reference|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_PRODUITS_EAN_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:250px" type="text" name="ean_code" value="{{ ean_code|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
	{% for l in langs %}
		<tr>
			<td class="label">{{ STR_ADMIN_NAME }} {{ l.lng|upper }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:250px" type="text" name="nom_{{ l.lng }}" value="{{ l.nom|html_entity_decode_if_needed|str_form_value }}" /><br />
				{{ l.nom_error }}
			</td>
		</tr>
	{% endfor %}
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_PRICE_IN }} <b>{{ site_symbole }} {{ ttc_ht }}</b>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input style="width:150px" type="text" name="prix" value="{{ prix|str_form_value }}" /></td>
		</tr>
	{% if is_reseller_module_active %}
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_RESELLER_PRICE_IN }} <b>{{ site_symbole }} {{ reseller_price_taxes_txt }}</b>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input style="width:150px" type="text" name="prix_revendeur" value="{{ prix_revendeur|str_form_value }}" /></td>
		</tr>
	{% endif %}
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_PURCHASE_PRICE_IN }} <b>{{ site_symbole }} {{ STR_HT }}</b>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input style="width:150px" type="text" name="prix_achat" value="{{ prix_achat|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_VAT_PERCENTAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select name="tva" style="width:150px">{{ vat_select_options }}</select>
			</td>
		</tr>
	{% if is_module_ecotaxe_active %}
		<tr>
			<td class="label">{{ STR_ADMIN_ECOTAX }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select name="id_ecotaxe" style="width:500px">
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
			<td class="label">{{ STR_ADMIN_PRODUITS_DISCOUNT_PERCENTAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="promotion" value="{{ promotion|str_form_value }}" style="width:150px" /> % {{ STR_ADMIN_PRODUITS_DISCOUNT_PERCENTAGE_OVER_LISTED_PRICE }}</td>
		</tr>
	{% if is_gifts_module_active %}
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_GIFT_POINTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="points" value="{{ points|str_form_value }}" style="width:150px" /></td>
		</tr>
	{% endif %}
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_WEIGHT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="poids" value="{{ poids|str_form_value }}" style="width:150px" /> {{ STR_ADMIN_PRODUITS_WEIGHT_UNIT }}</td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_VOLUME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="volume" value="{{ volume|str_form_value }}" style="width:150px" /> {{ STR_ADMIN_PRODUITS_WEIGHT_UNIT }}</td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_DISPLAY_PRICE_PER_KILO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="radio" name="display_price_by_weight" value="1"{% if display_price_by_weight == '1' %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_DISPLAY_PRICE_PER_LITER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="radio" name="display_price_by_weight" value="2"{% if display_price_by_weight == '2' %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_DISPLAY_NO_PRICE_PER_UNIT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="radio" name="display_price_by_weight" value="0"{% if display_price_by_weight == '0' %} checked="checked"{% endif %} /></td>
		</tr>
	{% if is_lot_module_active %}
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_LOT_PRICE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		{% if mode == "maj" %}
		<tr>
			<td class="label">{{ lot_explanation_table }}</td>
		</tr>
		<tr>
			<td class="label">
				<a href="{{ lot_href|escape('html') }}">{{ STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE }}</a>
				{% if (lot_supprime_href) %}
				/ <a href="{{ lot_supprime_href|escape('html') }}" onclick="return confirm('{{ STR_ADMIN_DELETE_WARNING|filtre_javascript(true,true,true) }}');">Effacer les prix par lot</a>
				{% endif %}
			</td>
		</tr>
		{% else %}
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE_EXPLAIN }}</td>
		</tr>
		{% endif %}
	{% endif %}
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_FILES_HEADER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_PRODUITS_FILES_EXPLAIN }}</td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="default_image" value="{{ default_image|str_form_value }}" /></td>
		</tr>
	{% for i in files|keys %}
		{% if (files.i) %}
		<tr>
			<td class="label">{% if files.i.type == 'img' %}{{ STR_ADMIN_IMAGE }} {% else %}{{ STR_ADMIN_FILE }} {% endif %}{{ i }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{% include "uploaded_file.tpl" with {'f':f,'STR_DELETE':STR_ADMIN_DELETE_THIS_FILE} }}</td>
		</tr>
		{% else %}
		<tr>
			<td class="label">{{ STR_ADMIN_FILE }} {{ i }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:250px" name="image{{ i }}" type="file" value="" /></td>
		</tr>
		{% endif %}
	{% endfor %}
	{% for c in colors %}
		<tr>
			<td colspan="2" class="label"><br />{{ STR_ADMIN_PRODUITS_FILE_FOR_COLOR }} {{ c.nom }} ({{ STR_ADMIN_PRODUITS_DEFAULT_COLOR_IN_FRONT }} <input type="radio" name="default_color_id"{% if c.issel %} checked="checked"{% endif %}value="{{ c.coul }}" />)</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_PRODUITS_FILES_EXPLAIN }}</td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input name="default_image{{ c.cmp_default_image }}" value="{{ c.default_image|str_form_value }}" /> {{ STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER_CONSTRAINT }}
			</td>
		</tr>
		{% if (c.images) %}
		{% for i in c.images|keys %}
		{% if (f) %}
		<tr>
			<td class="label">{{ STR_ADMIN_IMAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				{{ STR_ADMIN_FILE_NAME }}{{ STR_BEFORE_TWO_POINTS }}: {{ files.i.nom }} &nbsp;
				<a href="{{ files.i.sup_href|escape('html') }}">
				<img src="{{ drop_src|escape('html') }}" width="16" height="16" alt="" />{{ STR_ADMIN_DELETE_IMAGE }}</a>
				<input type="hidden" name="imagecouleur{{ files.i.id }}_{{ i }}" value="{{ files.i.nom|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center">{% if files.i.is_pdf %}<img src="{{ pdf_logo_src|escape('html') }}" alt="pdf" width="100" height="100" />{% else %}<img src="{{ files.i.src|escape('html') }}" alt="" />{% endif %}</td>
		</tr>
		{% else %}
		<tr>
			<td class="label">{{ STR_ADMIN_IMAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input style="width: 100%" name="imagecouleur{{ files.i.id }}_{{ i }}" type="file" value="" />
			</td>
		</tr>
		{% endif %}
		{% endfor %}
		{% else %}
		<tr>
			<td class="label" id="td_{{ c.nom }}" colspan="2"><a href="" onclick="addImagesFields('{{ c.nom|filtre_javascript }}','{{ upload_images_per_color|filtre_javascript }}');return false">{{ STR_ADMIN_PRODUITS_ADD_INPUT_FOR_THIS_COLOR }}</a></td>
		</tr>
		{% endif %}
	{% endfor %}
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_PRODUITS_VIDEO_TAG }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2" class="label"><textarea name="youtube_code" style="height:70px;width: 100%;" rows="50" cols="10">{{ youtube_code }}</textarea></td>
		</tr>
	{% for l in langs %}
		{% if is_id %}
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_MANAGE_TABS_TITLE }} {{ l.lng|upper }}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="global_help">{{ STR_ADMIN_PRODUITS_MANAGE_TABS_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td colspan="2" class="label"><a href="{{ l.modif_tab_href|escape('html') }}">{{ STR_ADMIN_PRODUITS_MANAGE_TAB }} {{ l.lng|upper }}</a></td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_MANAGE_TAB_EXPLAIN }}</td>
		</tr>
		{% endif %}
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_TEXT_RELATED_IN }} {{ l.lng|upper }}</td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_PRODUITS_SHORT_DESCRIPTION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><input style="width:100%" name="descriptif_{{ l.lng }}" type="text" value="{{ l.descriptif|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_PRODUITS_DESCRIPTION }}{{ STR_BEFORE_TWO_POINTS }}:<br /></td>
		</tr>
		<tr>
			<td colspan="2">{{ l.description_te }}</td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_META_TITLE }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><div class="global_help">{{ STR_ADMIN_META_TITLE_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="meta_titre_{{ l.lng }}" size="70" value="{{ l.meta_titre|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_META_KEYWORDS }} {{ l.lng|upper }} ({{ STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN }}){{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><div class="global_help">{{ STR_ADMIN_META_KEYWORDS_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="meta_key_{{ l.lng }}" style="width:100%" rows="2" cols="54">{{ l.meta_key|html_entity_decode_if_needed|strip_tags }}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_META_DESCRIPTION }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><div class="global_help">{{ STR_ADMIN_META_DESCRIPTION_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="meta_desc_{{ l.lng }}" style="width:100%" rows="3" cols="54">{{ l.meta_desc|nl2br_if_needed|html_entity_decode_if_needed|strip_tags }}</textarea></td>
		</tr>
	{% endfor %}
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_LINK_PRODUCT_TO_SUPPLIER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">
				<select name="id_utilisateur" style="width:100%" size="5">
					<option value="0">-------------------------------------------</option>
					{% for o in util_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_CHOOSE_BRAND }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">
				<select name="id_marque" style="width:100%" size="5">
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
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_CHOOSE_REFERENCE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">
				<select name="references[]" multiple="multiple" style="width:100%" size="5">
					<option value="">-------------------------------------------</option>			
					{% for o in produits_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.reference }} - {{ o.name }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="global_help">{{ STR_ADMIN_PRODUITS_CHOOSE_REFERENCE_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td class="top label">{{ STR_ADMIN_PRODUITS_AUTO_REF_PRODUCT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_ref_produit" value="1"{% if is_on_ref_produit %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="top">{{ STR_ADMIN_PRODUITS_AUTO_REF_NUMBER_PRODUCTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:150px" name="nb_ref_produits" type="text" value="{{ nb_ref_produits|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_PRODUITS_MANAGE_CRITERIA }}</td>
		</tr>
	{% if is_attributes_module_active %}
		<tr>
			<td colspan="2">
				<div class="global_help">{{ STR_ADMIN_PRODUITS_MANAGE_CRITERIA_INTRO }}{{ STR_BEFORE_TWO_POINTS }}: {% if mode == "maj" %}<a href="{{ produits_attributs_href|escape('html') }}">{{ STR_ADMIN_PRODUITS_MANAGE_CRITERIA_LINK }}</a>{% else %}{{ STR_ADMIN_PRODUITS_MANAGE_CRITERIA_TEASER }} <a href="{{ nom_attributs_href|escape('html') }}">{{ nom_attributs_href }}</a>{% endif %}</div></td>
		</tr>
	{% endif %}
		<tr>
			<td colspan="2">
				<div class="global_help">{{ STR_ADMIN_PRODUITS_MANAGE_COLORS_SIZES_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_PRODUITS_OTHER_OPTION }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_PRODUITS_PRODUCT_COLORS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">
				<select name="couleurs[]" multiple="multiple" style="width:100%" size="5">
					<option value="">-------------------------------------------</option>
					{% for o in couleurs_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_PRODUITS_OTHER_OPTION }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_PRODUITS_PRODUCT_SIZES }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">
				<select name="tailles[]" multiple="multiple" style="width:100%" size="5">
					<option value="">-------------------------------------------</option>
					{% for o in tailles_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }}{% if (o.prix) %} - {{ o.prix }} {{ STR_TTC }}{% endif %}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
	{% if is_download_module_active %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_PRODUITS_DOWNLOAD_PRODUCTS_HEADER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td class="top label">{{ STR_ADMIN_PRODUITS_IS_ON_DOWLOAD }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_download" value="1"{% if is_on_download %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="top">{{ STR_ADMIN_PRODUITS_FILE_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:150px" name="zip" type="text" value="{{ zip|str_form_value }}" /></td>
		</tr>
	{% endif %}
	{% if is_flash_sell_module_active %}
		<tr><td class="bloc" colspan="2">{{ STR_ADMIN_PRODUITS_FLASH_SALE }}</td></tr>
		<tr>
			<td colspan="2">
				<div class="global_help">{{ STR_ADMIN_PRODUITS_FLASH_SALE_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_PRODUITS_FLASH_PRICE }} <b>{{ site_symbole }} {{ ttc_ht }}</b>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input style="width:150px" type="text" name="prix_flash" value="{{ prix_flash|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_PRODUITS_FLASH_START_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:150px" type="text" name="flash_start" class="datetimepicker" value="{% if (flash_start) %}{{ flash_start|str_form_value }}{% endif %}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_PRODUITS_FLASH_END_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:150px" type="text" name="flash_end" class="datetimepicker" value="{% if (flash_end) %}{{ flash_end|str_form_value }}{% endif %}" /></td>
		</tr>
		<tr>
			<td class="top">{{ STR_ADMIN_PRODUITS_IS_ON_FLASH }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_flash" value="1"{% if is_on_flash %} checked="checked"{% endif %} /></td>
		</tr>
	{% endif %}
	{% if is_module_gift_checks_active %}
		<tr><td class="bloc" colspan="2">{{ STR_ADMIN_PRODUITS_GIFT_CHECK_HEADER }}</td></tr><tr>
			<td colspan="2"><p>{{ STR_ADMIN_PRODUITS_GIFT_CHECK_EXPLAIN }}</p></td>
		</tr>
		<tr>
			<td class="top">{{ STR_ADMIN_PRODUITS_IS_ON_GIFT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" id="on_gift" name="on_gift" value="1"{% if is_on_gift %} checked="checked"{% endif %} /></td>
		</tr>
		<tr id="on_gift_points_tr">
			<td>{{ STR_ADMIN_PRODUITS_GIFT_POINTS_NEEDED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" value="{{ on_gift_points|html_entity_decode_if_needed|str_form_value }}" name="on_gift_points" id="on_gift_points" size="3" /></td>
		</tr>
	{% endif %}
		<tr>
			<td colspan="2" align="center" style="padding:10px;">
				<script><!--//--><![CDATA[//><!--
				function verif_form() {
					// Pas de catégorie sélectionnée, pourtant obligatoire.
					if (document.getElementById('categories').selectedIndex < 0) {
						alert("{{ STR_ERR_CAT|filtre_javascript(true,false,true) }}");
						return false;
					 }} else {
						return true;
					 }}
				 }}
				//--><!]]></script>
				<p><input class="bouton" onclick="if (verif_form() == false) { return false; }}" type="submit" value="{{ normal_bouton|str_form_value }}" /></p>
			</td>
		</tr>
	</table>
</form>
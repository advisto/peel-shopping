{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_site.tpl 39392 2013-12-20 11:08:42Z gboussin $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_SITES_TITLE }}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_GENERAL_PARAMETERS }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SITE_ACTIVATION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="site_suspended" value="false"{% if site_suspended == false %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_SITE_ACTIVATED }}
				<input type="radio" name="site_suspended" value="true"{% if site_suspended != false %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_SITE_SUSPENDED }}
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN }}<br />
			<b>{{ STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN2 }}{{ STR_BEFORE_TWO_POINTS }}: {{ membre_admin_href }}</b><br />
			{{ STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN3 }}</div></td>
		</tr>
	{% for l in langs %}
		<tr>
			<td>{{ STR_ADMIN_SITES_SITE_NAME }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="nom_{{ l.lng }}" value="{{ l.nom|str_form_value }}" /></td>
		</tr>
	{% endfor %}
		<tr>
			<td>{{ STR_ADMIN_SITES_SITE_COUNTRY_PRESELECTED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><select class="form-control" name="default_country_id">{{ country_select_options }}</select></td>
   	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_TEMPLATE_USED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			{% if (directory_options) %}
				<select class="form-control" name="template_directory">
					<option value="">{{ STR_CHOOSE }}...</option>
				{% for o in directory_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.value }}</option>
				{% endfor %}
				</select>
			{% endif %}
			</td>
   	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PAGE_LINKS_DISPLAY_MODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="template_multipage">
					<option>{{ STR_CHOOSE }}...</option>
					<option value="default_1"{% if template_multipage == 'default_1' %} selected="selected"{% endif %}>{{ STR_ADMIN_SITES_DISPLAY }} n°1</option>
					<option value="default_2"{% if template_multipage == 'default_2' %} selected="selected"{% endif %}>{{ STR_ADMIN_SITES_DISPLAY }} n°2</option>
					<option value="default_3"{% if template_multipage == 'default_3' %} selected="selected"{% endif %}>{{ STR_ADMIN_SITES_DISPLAY }} n°3</option>
					<option value="default_4"{% if template_multipage == 'default_4' %} selected="selected"{% endif %}>{{ STR_ADMIN_SITES_DISPLAY }} n°4</option>
					{% if template_multipage and template_multipage != 'default_1' and template_multipage != 'default_2' and template_multipage != 'default_3' %}<option value="{{ template_multipage }}" selected="selected">{{ STR_ADMIN_SITES_DISPLAY }} "{{ template_multipage }}"</option>{% endif %}
				</select>
			</td>
   	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_CSS_FILES }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="css" value="{{ css|str_form_value }}" /></td>
   	 	</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_CSS_FILES_EXPLAIN }}</div></td>
   	 	</tr>
	{% for l in langs %}
		<tr>
			<td>{{ STR_ADMIN_SITES_LOGO_URL }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="logo_{{ l.lng }}" value="{{ l.logo|str_form_value }}" /></td>
		</tr>
	{% endfor %}
		<tr>
			<td>{{ STR_ADMIN_SITES_LOGO_HEADER_DISPLAY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="on_logo" value="1"{% if on_logo == '1' %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="on_logo" value="0"{% if on_logo == '0' %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
   	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_FAVICON }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			{% if (favicon) %}
				<img src="{{ favicon.src|escape('html') }}" alt="{{ favicon.favicon|str_form_value }}" width="32" /> &nbsp; &nbsp; &nbsp;
				<a href="{{ favicon.sup_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" width="16" height="16" alt="" />{{ STR_ADMIN_DELETE_THIS_FILE }}</a>
				<input type="hidden" name="favicon" value="{{ favicon.favicon|str_form_value }}" />
			{% else %}
				<input style="max-width:250px" type="file" name="favicon" value="" />
			{% endif %}
			</td>
   	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_ZOOM_SELECTION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="zoom" value="jqzoom" {% if zoom == 'jqzoom' %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_JQZOOM }}
				<input type="radio" name="zoom" value="cloud-zoom" {% if zoom == 'cloud-zoom' %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_CLOUD_ZOOM }}
				<input type="radio" name="zoom" value="lightbox" {% if zoom == 'lightbox' %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_LIGHTBOX }}
				<input type="radio" name="zoom" value="" {% if zoom == '' %} checked="checked"{% endif %} /> {{ STR_NONE }}
			</td>
   	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION }} {{ STR_BEFORE_TWO_POINTS }}:</td>
 			<td>
				<input type="checkbox" name="enable_prototype" value="1"{% if enable_prototype %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_JAVASCRIPT_AJAX_ACTIVATE }}
				<input type="checkbox" name="enable_jquery" value="1"{% if enable_jquery %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_JAVASCRIPT_JQUERY_ACTIVATE }}
			</td>
   	 	</tr>
		<tr>
 			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION_EXPLAIN }}</div>
			</td>
	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="export_encoding">
					<option value="utf-8"{% if export_encoding == 'utf-8' %} selected="selected"{% endif %}>{{ STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_UTF8 }}</option>
					<option value="iso-8859-1"{% if export_encoding == 'iso-8859-1' %} selected="selected"{% endif %}>{{ STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_ISO }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_autosend" value="1" {% if module_autosend == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_autosend" value="0" {% if module_autosend == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" maxlength="3" type="text" class="form-control" name="module_autosend_delay" value="{{ module_autosend_delay|str_form_value }}" /> {{ STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS_EXPLAIN }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_CATEGORY_COUNT_METHOD }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="radio" value="individual" name="category_count_method" {% if category_count_method == 'individual' %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_CATEGORY_COUNT_INDIVIDUAL }}
			<input type="radio" value="global" name="category_count_method" {% if category_count_method == 'global' %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_CATEGORY_COUNT_GLOBAL }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_CART_POPUP_SIZE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control" maxlength="3" name="popup_width" value="{{ popup_width|str_form_value }}" style="width:100px" /> px *
				<input type="text" class="form-control" maxlength="3" name="popup_height" value="{{ popup_height|str_form_value }}" style="width:100px" /> px
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_CART_POPUP_SIZE_EXPLAIN }}</div></td>
   	 	</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_SECURITY }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_ADMIN_FORCE_SSL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="admin_force_ssl" value="1" {% if admin_force_ssl == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="admin_force_ssl" value="0" {% if admin_force_ssl == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_ADMIN_FORCE_SSL_EXPLAIN }}<br />
<a href="{{ membre_href|escape('html') }}">{{ STR_ADMIN_SITES_HTTPS_TEST }}</a></div>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SESSIONS_DURATION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" maxlength="5" type="text" class="form-control" name="sessions_duration" value="{{ sessions_duration|str_form_value }}" /> {{ STR_MINUTES }} </td>
   	 	</tr>
 		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_SESSIONS_DURATION_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" value="{{ display_errors_for_ips|str_form_value }}" name="display_errors_for_ips" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PRODUCTS_DISPLAY }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PRODUCTS_COUNT_IN_MENU }}{{ STR_BEFORE_TWO_POINTS }}:</td>
 			<td>
				<input type="radio" name="display_nb_product" value="1" {% if display_nb_product == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="display_nb_product" value="0" {% if display_nb_product == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
   	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_THUMBS_SIZE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control" maxlength="3" name="small_width" value="{{ small_width|str_form_value }}" /> px. *
				<input type="text" class="form-control" maxlength="3" name="small_height" value="{{ small_height|str_form_value }}" /> px.
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_IMAGES_SIZE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control" maxlength="3" style="width:100px" name="medium_width" value="{{ medium_width|str_form_value }}" /> px. *
				<input type="text" class="form-control" maxlength="3" style="width:100px" name="medium_height" value="{{ medium_height|str_form_value }}" /> px.
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PRODUCTS_FILTER_DISPLAY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_filtre" value="1" {% if module_filtre == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_filtre" value="0" {% if module_filtre == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_ALLOW_ADD_PRODUCT_IN_LIST_PAGES }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" value="1" name="category_order_on_catalog" {% if category_order_on_catalog == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" value="0" name="category_order_on_catalog" {% if category_order_on_catalog == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="type_affichage_attribut" value="0" {% if type_affichage_attribut == 0 %} checked="checked"{% endif %} /> {{ STR_MODULE_ATTRIBUTS_ADMIN_SELECT_MENU }}
				<input type="radio" name="type_affichage_attribut" value="1" {% if type_affichage_attribut == 1 %} checked="checked"{% endif %} /> {{ STR_MODULE_ATTRIBUTS_ADMIN_RADIO_BUTTONS }}
				<input type="radio" name="type_affichage_attribut" value="2" {% if type_affichage_attribut == '2' %} checked="checked"{% endif %} /> {{ STR_MODULE_ATTRIBUTS_ADMIN_CHECKBOX }}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY_EXPLAIN }}</div>
			</td>
		<tr>
			<td>{{ STR_ADMIN_SITES_PRODUCTS_PER_PAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" maxlength="3" type="number" class="form-control" name="nb_produit_page" value="{{ nb_produit_page|str_form_value }}" /></td>
   	 	</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_PRODUCTS_PER_PAGE_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_ADD_TO_CART_ANIMATION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="anim_prod" value="1" {% if anim_prod == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="anim_prod" value="0" {% if anim_prod == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
   	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_DEFAULT_PRODUCT_PAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			{% if default_picture %}
				<img src="{{ default_picture_url|escape('html') }}" alt="{{ default_picture|str_form_value }}" width="32" /> &nbsp; &nbsp; &nbsp;
				<a href="{{ default_picture_delete_url|escape('html') }}"><img src="{{ default_picture_delete_icon_url|escape('html') }}" width="16" height="16" alt="" />{{ STR_ADMIN_DELETE_THIS_FILE }}</a>
				<input type="hidden" name="default_picture" value="{{ default_picture|str_form_value }}" />
			{% else %}
				<input style="max-width:250px" type="file" name="default_picture" value="{{ default_picture|str_form_value }}" />
			{% endif %}
			</td>
   	 	</tr>
	{% if is_best_seller_module_active %}
		<tr>
			<td>{{ STR_ADMIN_SITES_TOP_SALES_CONFIGURATION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="act_on_top" value="1" {% if act_on_top == 1 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_AUTO_TOP_SALES }}
				<input type="radio" name="act_on_top" value="0" {% if act_on_top == 0 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_CONFIGURED_TOP_SALES }}
			</td>
   	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_TOP_SALES_MAX_PRODUCTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" maxlength="3" type="number" class="form-control" name="nb_on_top" value="{{ nb_on_top|str_form_value }}" /></td>
   	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_LAST_VISITS_MAX_PRODUCTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" maxlength="3" type="number" class="form-control" name="nb_last_views" value="{{ nb_last_views|str_form_value }}" /></td>
   	 	</tr>
	{% endif %}
		<tr>
			<td>{{ STR_ADMIN_SITES_AUTO_PROMOTIONS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="auto_promo" value="1" {% if auto_promo == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="auto_promo" value="0" {% if auto_promo == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_CONFIGURED_PROMOTIONS }} <a href="{{ promotions_href|escape('html') }}">{{ promotions_href }}</a>.</div>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_GLOBAL_DISCOUNT_PERCENTAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" maxlength="8" type="text" class="form-control" name="global_remise_percent" value="{{ global_remise_percent|str_form_value }}" /> % </td>
   	 	</tr>

		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control" maxlength="2" size="3" name="pays_exoneration_tva" value="{{ pays_exoneration_tva|str_form_value }}" /> {{ STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_SHORT_EXPLAIN }}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_BILLING_HEADER }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_BILLING_NUMBER_FORMAT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="format_numero_facture" value="{{ format_numero_facture|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_BILLING_NUMBER_FORMAT_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_HEADER }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="quotation_delay" value="{{ quotation_delay|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_SMALL_ORDERS }}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_SMALL_ORDERS_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SMALL_ORDERS_LIMIT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="small_order_overcost_limit" value="{{ small_order_overcost_limit|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SMALL_ORDERS_AMOUNT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="small_order_overcost_amount" value="{{ small_order_overcost_amount|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SMALL_ORDERS_VAT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="small_order_overcost_tva_percent" value="{{ small_order_overcost_tva_percent|str_form_value }}" /> %</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="minimal_amount_to_order" value="{{ minimal_amount_to_order|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_SITES_ORDERS_UPDATING_LIMITATION }} <a name="a_keep_old_orders_intact"></a></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBID }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="keep_old_orders_intact" value="0" {% if keep_old_orders_intact == 0 %} checked="checked"{% endif %} /> {{ STR_NO }} <br />
				<input type="radio" name="keep_old_orders_intact" value="1" {% if keep_old_orders_intact == 1 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_LAST_YEAR }}<br />
				<input type="radio" name="keep_old_orders_intact" value="2" {% if keep_old_orders_intact > 1 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE }} <input style="width:100px" type="text" class="form-control datepicker" name="keep_old_orders_intact_date" value="{{ keep_old_orders_intact_date|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_DELIVERY_COST_HEADER }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="seuil_total" value="{{ seuil_total|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }} - {{ STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_DELIVERY_COST_RESELLER_FRANCO_LIMIT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="seuil_total_reve" value="{{ seuil_total_reve|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }} - {{ STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_DELIVERY_COST_METHOD }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="mode_transport">
					<option value="0" {% if mode_transport == 0 %} selected="selected"{% endif %}>{{ STR_ADMIN_SITES_DELIVERY_COST_NONE }}</option>
					<option value="1" {% if mode_transport == 1 %} selected="selected"{% endif %}>{{ STR_ADMIN_SITES_DELIVERY_COST_GENERAL }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_DELIVERY_COST_METHOD_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" value="{{ nb_product|str_form_value }}" name="nb_product" /> {{ STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART }}</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART_EXPLAIN }}</div>
				<div class="alert alert-info">{{ STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_EXPLAIN }}{{ STR_BEFORE_TWO_POINTS }}: {% if zones %}{% for z in zones %}<a href="{{ z.href|escape('html') }}">{{ z.nom }}</a>{% if 0 %}, {% endif %}{% endfor %}{% else %}<b><a href="{{ zones_href|escape('html') }}">{{ STR_ADMIN_SITES_DELIVERY_FRANCO_NO_ZONE }}</a></b>{% endif %}.</div>
			</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_VAT_DISPLAY_MODE_HEADER }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_VAT_DISPLAY_MODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="display_prices_with_taxes" value="1" {% if display_prices_with_taxes == 1 %} checked="checked"{% endif %} /> {{ STR_TTC }}
				<input type="radio" name="display_prices_with_taxes" value="0" {% if display_prices_with_taxes == 0 %} checked="checked"{% endif %} /> {{ STR_HT }}
				&nbsp; &nbsp; {{ STR_ADMIN_SITES_VAT_DISPLAY_MODE_EXPLAIN }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_VAT_DISPLAY_MODE_IN_ADMIN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="display_prices_with_taxes_in_admin" value="1" {% if display_prices_with_taxes_in_admin == 1 %} checked="checked"{% endif %} /> {{ STR_TTC }}
				<input type="radio" name="display_prices_with_taxes_in_admin" value="0" {% if display_prices_with_taxes_in_admin == 0 %} checked="checked"{% endif %} /> {{ STR_HT }}
			</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_ECOTAX_MODULE }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_ecotaxe" value="1" {% if module_ecotaxe == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_ecotaxe" value="0" {% if module_ecotaxe == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_CURRENCIES_MODULE }}</td>
		</tr>
	{% if is_fonctionsdevises %}
		<tr>
			<td>{{ STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_devise" value="1" {% if module_devise == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_devise" value="0" {% if module_devise == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY_EXPLAIN }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_DEFAULT_CURRENCY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="devise_defaut">
				{% for o in devices_options %}
				<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
				{% endfor %}		
				</select> {{ STR_ADMIN_SITES_DEFAULT_CURRENCY_WARNING }} - <a href="{{ devises_href|escape('html') }}">{{ STR_ADMIN_SITES_CURRENCIES_LINK }}</a>
			</td>
		</tr>
	{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
	{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_TEXT_EDITOR }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_TEXT_EDITOR_EXPLAIN }}</td>
			<td>
				<input type="radio" name="html_editor" value="0" {% if html_editor == 0 %} checked="checked"{% endif %} /> <b>{{ STR_ADMIN_SITES_TEXT_EDITOR_FCKEDITOR }}</b><br />
				<input type="radio" name="html_editor" value="3" {% if html_editor == 3 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_TEXT_EDITOR_CKEDITOR }}<br />
				<input type="radio" name="html_editor" value="1" {% if html_editor == 1 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_TEXT_EDITOR_NICEDITOR }}<br />
				<input type="radio" name="html_editor" value="4" {% if html_editor == 4 %} checked="checked"{% endif %} /> TinyMCE<br />
			</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_EMAIL_CONFIGURATION }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_EMAIL_SENDING_ALLOWED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="send_email_active" value="1" {% if send_email_active == 1 %} checked="checked"{% endif %} /> {{ STR_ADMIN_ACTIVATE }}
				<input type="radio" name="send_email_active" value="0" {% if send_email_active == 0 %} checked="checked"{% endif %} /> {{ STR_ADMIN_DEACTIVATE }} {{ STR_ADMIN_SITES_EMAIL_SENDING_DEACTIVATE_EXPLAIN }}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_EMAIL_CONFIGURATION_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td>{{ STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="email_webmaster" value="{{ email_webmaster|str_form_value }}" /> {{ STR_MODULE_PREMIUM_MANDATORY_EMAIL }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SUPPORT_SENDER_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="nom_expediteur" value="{{ nom_expediteur|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_SUPPORT_SENDER_NAME_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td>{{ STR_MODULE_WEBMAIL_ADMIN_ORDER_MANAGEMENT_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="email_commande" value="{{ email_commande|str_form_value }}" /> {{ STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN }}</td>
		</tr>
		<tr>
			<td>{{ STR_MODULE_WEBMAIL_ADMIN_CLIENT_SERVICE_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="email_client" value="{{ email_client|str_form_value }}" /> {{ STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN }}</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULES_POSITIONS }}</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_ADMIN_SITES_MODULES_POSITIONS_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="full_width">
			{% for m in modules %}
				{{ m.tr_rollover }}
					<td>
						<div class="edit_module_title">{{ m.title }}{{ STR_BEFORE_TWO_POINTS }}:</div>
					</td>
					<td>
						<div class="edit_module_attribut">
							<table>
								<tr>
									<td>
										<label for="display_mode_{{ m.id }}_left">{{ STR_ADMIN_DISPLAY_MODE }}</label>{{ STR_BEFORE_TWO_POINTS }}:
									</td>
									<td>
										<input type="text" class="form-control" name="display_mode_{{ m.id }}" value="{{ m.display_mode }}" size="10" />
									</td>
								</tr>
								<tr>
									<td>{{ STR_ADMIN_PLACE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
									<td>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_above_middle" value="above_middle"{% if m.location == 'above_middle' %} checked="checked"{% endif %}{% if m.is_above_middle_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_above_middle">{{ STR_ADMIN_SITES_ABOVE_MIDDLE }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_below_middle" value="below_middle"{% if m.location == 'below_middle' %} checked="checked"{% endif %}{% if m.is_below_middle_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_below_middle">{{ STR_ADMIN_SITES_BELOW_MIDDLE }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_footer" value="footer"{% if m.location == 'footer' %} checked="checked"{% endif %}{% if m.is_footer_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_footer">{{ STR_ADMIN_SITES_BOTTOM }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_header" value="header"{% if m.location == 'header' %} checked="checked"{% endif %}{% if m.is_header_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_header">{{ STR_ADMIN_SITES_TOP }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_top_middle" value="top_middle"{% if m.location == 'top_middle' %} checked="checked"{% endif %}{% if m.is_top_middle_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_top_middle">{{ STR_ADMIN_SITES_CENTER_TOP }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_center_middle" value="center_middle"{% if m.location == 'center_middle' %} checked="checked"{% endif %}{% if m.is_center_middle_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_center_middle">{{ STR_ADMIN_SITES_CENTER_MIDDLE }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_center_middle_home" value="center_middle_home"{% if m.location == 'center_middle_home' %} checked="checked"{% endif %}{% if m.is_center_middle_home_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_center_middle_home">{{ STR_ADMIN_SITES_CENTER_MIDDLE_HOME }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_bottom_middle" value="bottom_middle"{% if m.location == 'bottom_middle' %} checked="checked"{% endif %}{% if m.is_bottom_middle_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_bottom_middle">{{ STR_ADMIN_SITES_CENTER_BOTTOM }}</label><br />
									{% if is_module_banner_active and is_vitrine_module_active %}
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_top_vitrine" value="top_vitrine"{% if m.location == 'top_vitrine' %} checked="checked"{% endif %}{% if m.is_top_vitrine_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_top_vitrine">{{ STR_ADMIN_SITES_USER_SHOPS_TOP }}</label><br />
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_bottom_vitrine" value="bottom_vitrine"{% if m.location == 'bottom_vitrine' %} checked="checked"{% endif %}{% if m.is_bottom_vitrine_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_bottom_vitrine">{{ STR_ADMIN_SITES_USER_SHOPS_BOTTOM }}</label><br />
									{% endif %}
									{% if is_annonce_module_active %}
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_top_annonce" value="top_annonce"{% if m.location == 'top_annonce' %} checked="checked"{% endif %}{% if m.is_annonce_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_top_annonce">{{ STR_ADMIN_SITES_POSITION_ADS_TOP }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_sponso_cat" value="sponso_cat"{% if m.location == 'sponso_cat' %} checked="checked"{% endif %}{% if m.is_annonce_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_sponso_cat">{{ STR_ADMIN_SITES_POSITION_ADS_SPONSOR }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_ad_detail_bottom" value="ad_detail_bottom"{% if m.location == 'ad_detail_bottom"' %} checked="checked"{% endif %}{% if m.is_annonce_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_ad_detail_bottom">{{ STR_ADMIN_SITES_POSITION_AD_BOTTOM }}</label><br />
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_ad_detail_top" value="ad_detail_top"{% if m.location == 'ad_detail_top"' %} checked="checked"{% endif %}{% if m.is_annonce_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_ad_detail_top">{{ STR_ADMIN_SITES_POSITION_AD_TOP }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_middle_annonce" value="middle_annonce"{% if m.location == 'middle_annonce' %} checked="checked"{% endif %}{% if m.is_annonce_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_middle_annonce">{{ STR_ADMIN_SITES_POSITION_ADS_MIDDLE }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_bottom_annonce" value="bottom_annonce"{% if m.location == 'bottom_annonce' %} checked="checked"{% endif %}{% if m.is_annonce_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_bottom_annonce">{{ STR_ADMIN_SITES_POSITION_ADS_BOTTOM }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_left_annonce" value="left_annonce"{% if m.location == 'left_annonce' %} checked="checked"{% endif %}{% if m.is_annonce_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_left_annonce">{{ STR_ADMIN_SITES_POSITION_ADS_LEFT }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_right_annonce" value="right_annonce"{% if m.location == 'right_annonce' %} checked="checked"{% endif %}{% if m.is_annonce_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_right_annonce">{{ STR_ADMIN_SITES_POSITION_ADS_RIGHT }}</label>
									{% endif %}
									{% if is_iphone_ads_module_active %}
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_splashscreen" value="iphone_ads_splashscreen"{% if m.location == 'iphone_ads_splashscreen"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_splashscreen">{{ STR_ADMIN_SITES_POSITION_IPHONE_HOME }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_bottom_annonce" value="iphone_ads_bottom_annonce"{% if m.location == 'iphone_ads_bottom_annonce"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_bottom_annonce">{{ STR_ADMIN_SITES_POSITION_IPHONE_ADS_BOTTOM }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_top_annonce" value="iphone_ads_top_annonce"{% if m.location == 'iphone_ads_top_annonce"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_top_annonce">{{ STR_ADMIN_SITES_POSITION_IPHONE_ADS_TOP }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_ad_detail_top" value="iphone_ads_ad_detail_top"{% if m.location == 'iphone_ads_ad_detail_top"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_ad_detail_top">{{ STR_ADMIN_SITES_POSITION_IPHONE_AD_TOP }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_ad_detail_bottom" value="iphone_ads_ad_detail_bottom"{% if m.location == 'iphone_ads_ad_detail_bottom"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_ad_detail_bottom">{{ STR_ADMIN_SITES_POSITION_IPHONE_AD_BOTTOM }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_favoris_bottom" value="iphone_ads_favoris_bottom"{% if m.location == 'iphone_ads_favoris_bottom"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_favoris_bottom">{{ STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_BOTTOM }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_favoris_top" value="iphone_ads_favoris_top"{% if m.location == 'iphone_ads_favoris_top"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_favoris_top">{{ STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_TOP }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_account_bottom" value="iphone_ads_account_bottom"{% if m.location == 'iphone_ads_account_bottom"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_account_bottom">{{ STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_BOTTOM }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_account_top" value="iphone_ads_account_top"{% if m.location == 'iphone_ads_account_top"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_account_top">{{ STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_TOP }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_create_account_top" value="iphone_ads_create_account_top"{% if m.location == 'iphone_ads_create_account_top"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_create_account_top">{{ STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_TOP }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_create_account_bottom" value="iphone_ads_create_account_bottom"{% if m.location == 'iphone_ads_create_account_bottom"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_create_account_bottom">{{ STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_BOTTOM }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_publish_top" value="iphone_ads_publish_top"{% if m.location == 'iphone_ads_publish_top"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /> <label for="{{ m.id }}_iphone_ads_publish_top">{{ STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_TOP }}</label>
										<input type="radio" name="module_{{ m.id }}" id="{{ m.id }}_iphone_ads_publish_bottom" value="iphone_ads_publish_bottom"{% if m.location == 'iphone_ads_publish_bottom"' %} checked="checked"{% endif %}{% if m.is_iphone_place_off %} disabled="disabled"{% endif %} /><label for="{{ m.id }}_iphone_ads_publish_bottom">{{ STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_BOTTOM }}</label>
									{% endif %}
									</td>
								</tr>
								<tr>
									<td>
										<label for="etat_{{ m.id }}">{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</label>
									</td>
									<td>
										<input type="checkbox" name="etat_{{ m.id }}" id="etat_{{ m.id }}" value="1"{% if m.etat == '1' %} checked="checked"{% endif %} />
									</td>
								</tr>
								<tr>
									<td>
										<label for="home_{{ m.id }}">{{ STR_ADMIN_SITES_ON_HOMEPAGE_ONLY }}{{ STR_BEFORE_TWO_POINTS }}:</label>
									</td>
									<td>
										<input type="checkbox" name="home_{{ m.id }}" id="home_{{ m.id }}" value="1"{% if m.in_home == '1' %} checked="checked"{% endif %} />
									</td>
								</tr>
								<tr>
									<td>
										<label for="position_{{ m.id }}_left">{{ STR_ADMIN_POSITION }}</label>{{ STR_BEFORE_TWO_POINTS }}:
									</td>
									<td>
										<input type="text" class="form-control" name="position_{{ m.id }}" value="{{ m.position|str_form_value }}" size="1" />
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			{% endfor %}
				</table>
			</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_PAYPAL_MODULE }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PAYPAL_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="email_paypal" value="{{ email_paypal|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_MONEYBOOKERS_MODULE }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_MONEYBOOKERS_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="email_moneybookers" value="{{ email_moneybookers|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD }}{{ STR_BEFORE_TWO_POINTS }}:<br />
			</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="secret_word" value="{{ secret_word|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_KEKOLI_MODULE }}</td>
		</tr>
 		<tr>
			<td>{{ STR_ADMIN_SITES_DELIVERY_CARRIER_DELAY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:50px" type="text" class="form-control" name="availability_of_carrier" value="{{ availability_of_carrier|str_form_value }}" /> jours</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_ANALYTICS_TAG }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_ANALYTICS_TAG_EXPLAIN|htmlspecialchars }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td colspan="2"><textarea class="form-control" name="tag_analytics" style="width:100%" rows="5" cols="54">{{ tag_analytics }}</textarea></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_TAG_CLOUD_MODULE }}</td>
		</tr>
		{% if is_fonctionstagcloud %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_nuage" value="1" {% if module_nuage == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_nuage" value="0" {% if module_nuage == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_FLASH_SALES_MODULE }}</td>
		</tr>
		{% if is_flash_sell_module_active %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_flash" id="module_flash1" value="1" {% if module_flash == 1 %} checked="checked"{% endif %} /> {{ STR_YES }} 
				<input type="radio" name="module_flash" id="module_flash0" value="0" {% if module_flash == 0 %} checked="checked"{% endif %} /> {{ STR_NO }} 
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_CONTACT_PEEL_FOR_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_ADVERTISING }}</td>
		</tr>
		{% if is_fonctionsbanner %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_pub" value="1" {% if module_pub == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_pub" value="0" {% if module_pub == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_ROLLOVER_HEADER }}</td>
		</tr>
		{% if is_fonctionsmenus %}
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_ROLLOVER_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_ROLLOVER_DISPLAY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_rollover" value="1" {% if module_rollover == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_rollover" value="0" {% if module_rollover == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_ROLLOVER_DISPLAY_MODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="type_rollover" value="1" {% if type_rollover == 1 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_ROLLOVER_DISPLAY_REPLACE }}
				<input type="radio" name="type_rollover" value="2" {% if type_rollover == 2 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_ROLLOVER_DISPLAY_SCROLLING }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_RSS_MODULE }}</td>
		</tr>
		{% if is_fonctionsrss %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_rss" value="1" {% if module_rss == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_rss" value="0" {% if module_rss == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_OPINIONS_MODULE }}</td>
		</tr>
		{% if is_fonctionsavis %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_avis" value="1" {% if module_avis == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_avis" value="0" {% if module_avis == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_CAPTCHA_ACTIVATION }}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_CAPTCHA_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_CAPTCHA_DISPLAY_MODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_captcha" value="1" {% if module_captcha == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_captcha" value="0" {% if module_captcha == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% if is_fonctionsprecedentsuivant %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION }}</td>
		</tr>
{% if	(STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION_EXPLAIN) %}
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION_EXPLAIN }}</div></td>
		</tr>
{% endif %}
		<tr>
			<td>{{ STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_precedent_suivant" value="1" {% if module_precedent_suivant == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_precedent_suivant" value="0" {% if module_precedent_suivant == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_MODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="in_category" value="1" {% if in_category == 1 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_DIRECT_PARENT }}<br />
				<input type="radio" name="in_category" value="0" {% if in_category == 0 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_ALL_PARENTS }}
			</td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_STOCKS_MODULE }}</td>
		</tr>
	{% if is_stock_advanced_module_active %}
		<tr>
			<td colspan="2">{{ STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_ALLOW_ORDERS_WITHOUT_STOCKS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="allow_add_product_with_no_stock_in_cart" value="1" {% if allow_add_product_with_no_stock_in_cart == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="allow_add_product_with_no_stock_in_cart" value="0" {% if allow_add_product_with_no_stock_in_cart == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_STOCKS_BOOKING_SECONDS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="timemax" value="{{ timemax|str_form_value }}" /> {{ STR_ADMIN_SITES_STOCKS_BOOKING_DEFAULT }}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_STOCKS_BOOKING_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_STOCKS_LIMIT_ALERT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="seuil" value="{{ seuil|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="payment_status_decrement_stock" value="1,2,3"{% if payment_status_decrement_stock == '1,2,3' %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="payment_status_decrement_stock" value="2,3"{% if payment_status_decrement_stock == '2,3' %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS_EXPLAIN }}</div></td>
		</tr>
	{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
	{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_CART_SAVE_MODULE }}</td>
		</tr>
		{% if is_fonctionscartpreservation %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_cart_preservation" id="module_cart_preservation1" value="1" {% if module_cart_preservation == 1 %} checked="checked"{% endif %} /> <label for="module_cart_preservation1">{{ STR_YES }}</label>
				<input type="radio" name="module_cart_preservation" id="module_cart_preservation0" value="0" {% if module_cart_preservation == 0 %} checked="checked"{% endif %} /> <label for="module_cart_preservation0">{{ STR_NO }}</label>
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_RESELLER_MANAGE }}</td>
		</tr>
		{% if is_fonctionsreseller %}
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_RESELLER_MANAGE_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_retail" value="1" {% if module_retail == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_retail" value="0" {% if module_retail == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_AFFILIATION_MODULE }}</td>
		</tr>
		{% if is_fonctionsaffiliate %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_affilie" value="1" {% if module_affilie == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_affilie" value="0" {% if module_affilie == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_AFFILIATION_COMMISSION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" maxlength="2" type="text" class="form-control" name="commission_affilie" value="{{ commission_affilie|str_form_value }}" /> %</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_AFFILIATION_LOGO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="logo_affiliation" value="{{ logo_affiliation|str_form_value }}" /></td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_PRODUCT_LOTS_MODULE }}</td>
		</tr>
		{% if is_fonctionslot %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_lot" value="1" {% if module_lot == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_lot" value="0" {% if module_lot == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_SPONSOR_MODULE }}</td>
		</tr>
		{% if is_fonctionsparrain %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_parrain" value="1" {% if module_parrain == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_parrain" value="0" {% if module_parrain == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td style="width:40%">{{ STR_ADMIN_SITES_SPONSOR_COMMISSION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" maxlength="3" type="text" class="form-control" name="avoir" value="{{ avoir|str_form_value }}" /> {{ site_symbole }}</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_GIFT_CHECKS_MODULE }}</td>
		</tr>
		{% if is_fonctionsgiftcheck %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_cadeau" value="1" {% if module_cadeau == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_cadeau" value="0" {% if module_cadeau == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_FAQ_MODULE }}</td>
		</tr>
		{% if is_fonctionsfaq %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_faq" value="1" {% if module_faq == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_faq" value="0" {% if module_faq == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_URL_REWRITING_MODULE }}</td>
		</tr>
		{% if is_rewritefile %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_url_rewriting" value="1" {% if module_url_rewriting == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_url_rewriting" value="0" {% if module_url_rewriting == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_MICROBUSINESS_MODULE }}</td>
		</tr>
		{% if is_fonctionsmicro %}
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SITES_MICROBUSINESS_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_entreprise" value="1" {% if module_entreprise == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_entreprise" value="0" {% if module_entreprise == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_BIRTHDAY_MODULE }}</td>
		</tr>
		{% if is_fonctionsbirthday %}
		<tr>
			<td colspan="2">{{ STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT }}</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_CATEGORIES_PROMOTION }}</td>
		</tr>
		{% if is_fonctionscatpromotions %}
		<tr>
			<td colspan="2">{{ STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT }}</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_TRADEMARK_PROMOTION }}</td>
		</tr>
		{% if is_fonctionsmarquepromotions %}
		<tr>
			<td colspan="2">{{ STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT }}</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_PREMIUM_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_COMPARATOR_EXPLAIN }}</td>
		</tr>
		{% if is_fonctionscomparateur %}
		<tr>
			<td colspan="2">{{ STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT }}</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_PRODUCT_CONDITIONING_MODULE }}</td>
		</tr>
		{% if fonctionsconditionnement %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_conditionnement" value="1" {% if module_conditionnement == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_conditionnement" value="0" {% if module_conditionnement == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_GOOGLE_FRIENDS_CONNECT_MODULE }}</td>
		</tr>
		{% if is_fonctionsgooglefriendconnect %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="googlefriendconnect" value="1" {% if googlefriendconnect == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="googlefriendconnect" value="0" {% if googlefriendconnect == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_GOOGLE_FRIENDS_CONNECT_SITE_ID }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="" type="text" class="form-control" name="googlefriendconnect_site_id" value="{{ googlefriendconnect_site_id|str_form_value }}" /> </td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_TWITTER_SIGN_IN }}</td>
		</tr>
		{% if is_fonctionssignintwitter %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="sign_in_twitter" value="1" {% if sign_in_twitter == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="sign_in_twitter" value="0" {% if sign_in_twitter == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_TWITTER_CONSUMER_KEY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="" type="text" class="form-control" name="twitter_consumer_key" value="{{ twitter_consumer_key|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_TWITTER_CONSUMER_SECRET }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="" type="text" class="form-control" name="twitter_consumer_secret" value="{{ twitter_consumer_secret|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_TWITTER_OAUTH_CALLBACK }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="" type="text" class="form-control" name="twitter_oauth_callback" value="{{ twitter_oauth_callback|str_form_value }}" /></td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_VACANCY_MODULE }}</td>
		</tr>
	{% if is_fonctionsvacances %}
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_vacances" value="1" {% if module_vacances == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_vacances" value="0" {% if module_vacances == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_VACANCY_MODULE_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_vacances_type" value="0" {% if module_vacances_type == 0 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_VACANCY_MODULE_TYPE_ADMIN }}
				<input type="radio" name="module_vacances_type" value="1" {% if module_vacances_type == 1 %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_VACANCY_MODULE_TYPE_SUPPLIER }}
			</td>
		</tr>
		{% for l in langs %}
		<tr>
			<td>{{ STR_ADMIN_SITES_VACANCY_ADMIN_MESSAGE }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" value="{{ l.module_vacances_value|str_form_value }}" name="module_vacances_client_msg_{{ l.lng }}" id="module_vacances_client_msg_{{ l.lng }}" size="100" /></td>
		</tr>
		{% endfor %}
	{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
	{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_FORUM_MODULE }}</td>
		</tr>
		{% if is_fonctionsforum %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_forum" value="1" {% if module_forum == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_forum" value="0" {% if module_forum == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_GIFTS_LIST }}</td>
		</tr>
		{% if is_fonctionsgiftlist %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_giftlist" value="1" {% if module_giftlist == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_giftlist" value="0" {% if module_giftlist == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_SO_COLISSIMO_MODULE }}</td>
		</tr>
		{% if is_fonctionssocolissimo %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_socolissimo" value="1" {% if module_socolissimo == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_socolissimo" value="0" {% if module_socolissimo == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SO_COLISSIMO_FOID }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:200px" type="text" class="form-control" name="socolissimo_foid" value="{{ socolissimo_foid|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SO_COLISSIMO_SHA1_KEY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:200px" type="text" class="form-control" name="socolissimo_sha1_key" value="{{ socolissimo_sha1_key|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SO_COLISSIMO_URL_KO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="socolissimo_urlko" value="{{ socolissimo_urlko|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:30px" type="text" class="form-control" name="socolissimo_preparationtime" value="{{ socolissimo_preparationtime|str_form_value }}" /> {{ STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME_EXPLAIN }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SO_COLISSIMO_FORWARDINGCHARGES }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:30px" type="text" class="form-control" name="socolissimo_forwardingcharges" value="{{ socolissimo_forwardingcharges|str_form_value }}" /> {{ site_symbole }} - {{ STR_ADMIN_SITES_SO_COLISSIMO_FORWARDINGCHARGES_EXPLAIN }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:30px" type="text" class="form-control" name="socolissimo_firstorder" value="{{ socolissimo_firstorder|str_form_value }}" /> {{ STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER_EXPLAIN }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:30px" type="text" class="form-control" name="socolissimo_pointrelais" value="{{ socolissimo_pointrelais|str_form_value }}" /> {{ STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS_EXPLAIN }}</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_EXPEDITOR_MODULE }}</td>
		</tr>
		{% if is_fonctionsexpeditor %}
		<tr>
			<td colspan="2">{{ STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT }}</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_ICI_RELAIS_MODULE }}</td>
		</tr>
		{% if is_fonctionsicirelais %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="module_icirelais" value="1" {% if module_icirelais == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="module_icirelais" value="0" {% if module_icirelais == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_TNT_MODULE }}</td>
		</tr>
		{% if is_fonctionstnt %}
			<tr>
				<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
				<td>
					<input type="radio" name="module_tnt" value="1" {% if module_tnt == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
					<input type="radio" name="module_tnt" value="0" {% if module_tnt == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
				</td>
			</tr>
			<tr>
				<td>{{ STR_ADMIN_SITES_TNT_USERNAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
				<td><input style="width:100%" type="text" class="form-control" name="tnt_username" value="{{ tnt_username|str_form_value }}" /></td>
			</tr>
			<tr>
				<td>{{ STR_ADMIN_SITES_TNT_PASSWORD }}{{ STR_BEFORE_TWO_POINTS }}:</td>
				<td><input style="width:100%" type="text" class="form-control" name="tnt_password" value="{{ tnt_password|str_form_value }}" /></td>
			</tr>
			<tr>
				<td>{{ STR_ADMIN_SITES_TNT_ACCOUNT_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
				<td><input style="width:100%" type="text" class="form-control" name="tnt_account_number" value="{{ tnt_account_number|str_form_value }}" /></td>
			</tr>
			<tr>
				<td>{{ STR_ADMIN_SITES_TNT_EXPEDITION_DELAY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
				<td><input style="width:100%" type="text" class="form-control" name="expedition_delay" value="{{ expedition_delay|str_form_value }}" /></td>
			</tr>
		{% else %}
			<tr>
				<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
			</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_SIPS_MODULE }}</td>
		</tr>
		{% if is_fonctionsatos %}
		<tr>
			<td>{{ STR_ADMIN_SITES_SIPS_CERTIFICATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="sips" value="{{ sips|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_SITES_SIPS_EXPLAIN }}</td>
		</tr>
		{% else %}
		<input type="hidden" name="sips" value="{{ sips|str_form_value }}" />
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_SPPLUS_MODULE }}</td>
		</tr>
		{% if is_fonctionsspplus %}
		<tr>
			<td>{{ STR_ADMIN_SITES_SPPLUS_EXTERNAL_URL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="spplus" value="{{ spplus|str_form_value }}" /></td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><input type="hidden" name="spplus" value="{{ spplus|str_form_value }}" /><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_PAYBOX_MODULE }}</td>
		</tr>
		{% if is_fonctionspaybox %}
		<tr>
			<td>{{ STR_ADMIN_SITES_PAYBOX_CGI }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="paybox_cgi" placeholder="http://" value="{{ paybox_cgi|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PAYBOX_SITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="paybox_site" value="{{ paybox_site|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PAYBOX_RANG }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="paybox_rang" value="{{ paybox_rang|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_PAYBOX_ID }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="paybox_identifiant" value="{{ paybox_identifiant|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_SITES_PAYBOX_TEST_EXPLAIN }}</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_SYSTEMPAY }}</td>
		</tr>
		{% if is_fonctionssystempay %}
		<tr>
			<td>{{ STR_ADMIN_SITES_SYSTEMPAY_CERTIFICATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="systempay_cle_prod" value="{{ systempay_cle_prod|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SYSTEMPAY_TEST }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="systempay_cle_test" value="{{ systempay_cle_test|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SYSTEMPAY_ID }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="systempay_code_societe" value="{{ systempay_code_societe|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SYSTEMPAY_OCCURENCES }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="systempay_payment_count" value="{{ systempay_payment_count|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SYSTEMPAY_DAYS_BETWEEN_OCCURENCES }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="systempay_payment_period" value="{{ systempay_payment_period|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_SYSTEMPAY_TEST_MODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<label for="non">{{ STR_NO }}</label><input type="radio" name="systempay_test_mode" value="false" id="non" {% if systempay_test_mode == false %} checked="checked"{% endif %} />
				<label for="oui">{{ STR_YES }}</label><input type="radio" name="systempay_test_mode" value="true" id="oui" {% if systempay_test_mode == true %} checked="checked"{% endif %} />
			</td>
		</tr>
		{% else %}
			<tr>
				<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
			</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_PARTNERS_MODULE }}</td>
		</tr>
		{% if is_fonctionspartenaires %}
		<tr>
			<td>{{ STR_ADMIN_SITES_PARTNERS_DISPLAY_MODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="radio" value="individual" name="partner_count_method" {% if partner_count_method == 'individual' %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_PARTNERS_INDIVIDUAL }}
			<input type="radio" value="global" name="partner_count_method" {% if partner_count_method == 'global' %} checked="checked"{% endif %} /> {{ STR_ADMIN_SITES_PARTNERS_GLOBAL }}</td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_FACEBOOK_MODULE }}</td>
		</tr>
		{% if is_fonctionsfacebook %}
		<tr>
			<td>{{ STR_ADMIN_SITES_FACEBOOK_ADMIN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="fb_admins" value="{{ fb_admins|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_FACEBOOK_PAGE_LINK }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="facebook_page_link" placeholder="http://" value="{{ facebook_page_link|str_form_value }}" /></td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td class="bloc" colspan="2">{{ STR_ADMIN_SITES_MODULE }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_ADMIN_SITES_FACEBOOK_CONNECT }}</td>
		</tr>
		{% if is_fonctionfacebookconnect %}
		<tr>
			<td>{{ STR_ADMIN_ACTIVATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="facebook_connect" value="1" {% if facebook_connect == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="facebook_connect" value="0" {% if facebook_connect == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_FACEBOOK_APPID }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="" type="text" class="form-control" name="fb_appid" value="{{ fb_appid|str_form_value }}" /> </td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_FACEBOOK_SECRET }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="" type="text" class="form-control" name="fb_secret" value="{{ fb_secret|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SITES_FACEBOOK_BASEURL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="" type="text" class="form-control" name="fb_baseurl" value="{{ fb_baseurl|str_form_value }}" /></td>
		</tr>
		{% else %}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{{ STR_ADMIN_SITES_CONTACT_PEEL_TO_GET_MODULE }}</a></td>
		</tr>
		{% endif %}
		<tr>
			<td colspan="2" class="center"><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></td>
		</tr>
	</table>
</form>
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
// $Id: admin_liste_produits.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}{% if is_empty %}
	<p><a href="{{ href|escape('html') }}">{{ STR_ADMIN_PRODUITS_CREATE_CATEGORY_FIRST }}</a></p>
{% else %}
<form class="entryform form-inline" role="form" method="get" action="{{ action|escape('html') }}">
	<div class="entete">{{ STR_ADMIN_SEARCH_CRITERIA }}</div>
	<div>
		<div class="row">
			<div class="col-md-3 col-sm-6" style="margin-top:10px; margin-bottom:10px;">
				{{ STR_CATEGORY }}{{ STR_BEFORE_TWO_POINTS }}:
				<select class="form-control" size="1" name="cat_search" >
					<option value="null">{{ STR_ADMIN_ALL_CATEGORIES }}</option>
					<option value="0"{% if cat_search_zero_issel %} selected="selected"{% endif %}>{{ STR_ADMIN_PRODUITS_NO_CATEGORY_RELATED }}</option>
					{{ categorie_options }}
				</select>
			</div>
			<div class="col-md-3 col-sm-6" style="margin-top:10px; margin-bottom:10px;">
				{{ STR_REFERENCE }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" class="form-control" name="reference_search" size="15" value="" />
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-md-3 col-sm-6" style="margin-top:10px; margin-bottom:10px;">
				{{ STR_BRAND }}{{ STR_BEFORE_TWO_POINTS }}:
+ 				<select class="form-control" name="brand_search">
					<option value="0">{{ STR_CHOOSE }}</option>
					{% for o in marques_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }}</option>
					{% endfor %}
				</select>
			</div>
			<div class="col-md-3 col-sm-6" style="margin-top:10px; margin-bottom:10px;">
				{{ STR_ADMIN_PRODUCT_NAME }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" class="form-control" name="name_search" size="15" value="" />
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="clearfix visible-md visible-lg"></div>
			<div class="col-md-3 col-sm-6" style="margin-top:10px; margin-bottom:10px;">
				{{ STR_ADMIN_PRODUITS_IS_PRODUCT_IN }} <strong>{{ STR_ADMIN_OUR_SELECTION }}</strong> ?<br />
				<span>
					<input type="radio" name="home_search" value="null" checked="checked" /> {{ STR_ADMIN_ANY }}&nbsp;
					<input type="radio" name="home_search"{% if home_search_one_issel %} checked="checked"{% endif %} value="1" /> {{ STR_YES }}
					<input type="radio" name="home_search"{% if home_search_zero_issel %} checked="checked"{% endif %} value="0" /> {{ STR_NO }}
				</span>
			</div>
			<div class="col-md-3 col-sm-6" style="margin-top:10px; margin-bottom:10px;">
				{{ STR_ADMIN_PRODUITS_IS_PRODUCT_IN }} <strong>{{ STR_NOUVEAUTES }}</strong> ?<br />
				<span>
					<input type="radio" name="new_search" value="null" checked="checked" /> {{ STR_ADMIN_ANY }} &nbsp;
					<input type="radio" name="new_search"{% if new_search_one_issel %} checked="checked"{% endif %} value="1" /> {{ STR_YES }}
					<input type="radio" name="new_search"{% if new_search_zero_issel %} checked="checked"{% endif %} value="0" /> {{ STR_NO }}
				</span>
			</div>
			<div class="col-md-3 col-sm-6" style="margin-top:10px; margin-bottom:10px;">
				{{ STR_ADMIN_PRODUITS_IS_PRODUCT_IN }} <strong>{{ STR_PROMOTION }}</strong> ?<br />
				<span>
					<input type="radio" name="promo_search" value="null" checked="checked" /> {{ STR_ADMIN_ANY }} &nbsp;
					<input type="radio" name="promo_search"{% if promo_search_one_issel %} checked="checked"{% endif %} value="1" /> {{ STR_YES }} &nbsp;
					<input type="radio" name="promo_search"{% if promo_search_zero_issel %} checked="checked"{% endif %} value="0" /> {{ STR_NO }}
				</span>
			</div>
			<div class="clearfix visible-md visible-lg"></div>
		{% if is_best_seller_module_active %}
			<div class="col-md-3 col-sm-6" style="margin-top:10px; margin-bottom:10px;">
				{{ STR_ADMIN_PRODUITS_IS_PRODUCT_IN }} <strong>{{ STR_TOP }}</strong> ?<br />
				<span>
					<input type="radio" name="top_search" value="null" checked="checked" /> {{ STR_ADMIN_ANY }} &nbsp;
					<input type="radio" name="top_search"{% if top_search_one_issel %} checked="checked"{% endif %} value="1" /> {{ STR_YES }} &nbsp;
					<input type="radio" name="top_search"{% if top_search_zero_issel %} checked="checked"{% endif %} value="0" /> {{ STR_NO }}
				</span>
			</div>
		{% endif %}
			<div class="clearfix visible-sm"></div>
		{% if is_gifts_module_active %}
			<div class="col-md-3 col-sm-6" style="margin-top:10px; margin-bottom:10px;">
				{{ STR_ADMIN_PRODUITS_IS_PRODUCT }} <strong>{{ STR_MODULE_GIFTS_ADMIN_GIFT }}</strong> ?<br />
				<span>
					<input type="radio" name="on_gift" value="null" checked="checked" /> {{ STR_ADMIN_ANY }} &nbsp;
					<input type="radio" name="on_gift"{% if on_gift_one_issel %} checked="checked"{% endif %} value="1" /> {{ STR_YES }}
					<input type="radio" name="on_gift"{% if on_gift_zero_issel %} checked="checked"{% endif %} value="0" /> {{ STR_NO }}
				</span>
			</div>
		{% endif %}
			<div class="col-md-3 col-sm-6 center pull-right" style="padding-top:10px; padding-bottom:10px">
				<input class="btn btn-primary" type="submit" value="{{ STR_SEARCH|str_form_value }}" name="action" />
			</div>
		</div>
	</div>
</form>
<div class="entete">{{ STR_ADMIN_PRODUITS_PRODUCTS_LIST }} <span style="float:right;">{{ STR_ADMIN_PRODUITS_PRODUCTS_COUNT }}{{ STR_BEFORE_TWO_POINTS }}: {{ nombre_produits }}</span></div>
<div><img src="images/add.png" width="16" height="16" alt="" class="middle" /><a href="{{ ajout_produits_href|escape('html') }}">{{ STR_ADMIN_CATEGORIES_ADD_PRODUCT }}</a></div>
	{% if is_duplicate_module_active %}
<div class="alert alert-info"><b>{{ STR_NOTA_BENE }}{{ STR_BEFORE_TWO_POINTS }}:</b> {{ STR_ADMIN_PRODUITS_DUPLICATE_WARNING }}</div>
	{% endif %}
	{% if not (lignes) %}
<div class="alert alert-warning">{{ STR_ADMIN_PRODUITS_NOTHING_FOUND }}</div>
	{% else %}
<div class="table-responsive">
	<table class="table">
		{{ HeaderRow }}
	{% for li in lignes %}
		{{ li.tr_rollover }}
		<td class="center">
			<a data-confirm="{{ li.drop_confirm|str_form_value }}" class="title_label" title="{{ STR_DELETE|str_form_value }} {{ li.name }}" href="{{ li.drop_href|escape('html') }}"><img src="{{ li.drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a>
			<a title="{{ STR_MODIFY|str_form_value }}" href="{{ li.edit_href|escape('html') }}"><img src="{{ li.edit_src|escape('html') }}" alt="edit" /></a>
			{% if is_duplicate_module_active %}
			<a title="{{ STR_ADMIN_PRODUITS_DUPLICATE|str_form_value }}" href="{{ li.dup_href|escape('html') }}"><img src="{{ li.dup_src|escape('html') }}" alt="" /></a>
			{% endif %}
		</td>
		<td class="center">
		<input type="text" onchange="update_reference(this, '{{ li.id|str_form_value }}', '{{ administrer_url|str_form_value }}')" style="width:100px" value="{{ li.reference }}" id="reference{{ li.id }}" name="reference_product" class="form-control">
		</td>
		<td class="center">
			{% if not (li.cats) %}
				<span style="color:red">-</span><br />
			{% else %}
				{% for ca in li.cats %}
					{% if (ca.parent_nom) %}
					<span style="color:#666666">{{ ca.parent_nom|html_entity_decode_if_needed }}</span> &gt; 	
					{% endif %}
					{{ ca.nom|html_entity_decode_if_needed }}<br />
				{% endfor %}
			{% endif %}
		</td>
		<td class="center">
			{{ li.site_name|html_entity_decode_if_needed }}
		</td>
		<td class="center"><a class="title_label" title="{{ STR_ADMIN_PRODUITS_UPDATE|str_form_value }}" href="{{ li.modify_href|escape('html') }}">{{ li.modify_label|html_entity_decode_if_needed }}</a></td>
		<td class="center">{% if site_parameters_prices=='edit' %}<input type="text" class="form-control" name="price_per_product_id[{{ li.id|str_form_value }}]" value="{{ li.prix|str_form_value }}" style="width:65px" onchange="update_price(this, '{{ li.id|str_form_value }}', '{{ administrer_url|str_form_value }}')" />{% else %}{{ li.prix }}{% endif %}</td>
		<td class="center"><img class="change_status" src="{{ li.etat_src|escape('html') }}" alt="" onclick="{{ li.etat_onclick|escape('html') }}" /></td>
		{% if is_stock_advanced_module_active %}
		<td class="center">
			{% if (li.stock_href) %}
				<a title="{{ STR_ADMIN_PRODUITS_MANAGE_STOCKS }}" href="{{ li.stock_href|escape('html') }}"><img src="{{ li.stock_src|escape('html') }}" alt="" /></a>
			{% else %}
				-
			{% endif %}
		</td>
		{% endif %}
		{% if is_gifts_module_active %}
		<td class="center">{{ li.points }} pts</td>
		{% endif %}
		<td class="center">{{ li.date }}</td>
		<td class="center">
			<select style="width:120px" class="form-control" size="1" name="societe" id="societe{{ li.id }}" onchange="update_supplier(this, '{{ li.id|str_form_value }}', '{{ administrer_url|str_form_value }}')">
				<option value="null">{{ STR_ADMIN_SUPPLIER }}</option>
				{% for so in supplier_options %} 
					 <option value="{{ so.id_utilisateur }}" {% if li.societe and li.societe == so.societe %} selected="selected"{% endif %}>{{ so.prenom }} {{ so.nom_famille }}</option>
				{% endfor %}
			</select>
		</td>
		<td class="center">
			{% if (li.product_src) %}
				<a href="{{ li.modify_href|escape('html') }}" title="{{ STR_ADMIN_PRODUITS_UPDATE|str_form_value }}"><img src="{{ li.product_src|escape('html') }}" alt="{{ li.product_name|str_form_value }}" /></a>
			{% elseif (photo_not_available_src) %}
				<a href="{{ li.modify_href|escape('html') }}" title="{{ STR_ADMIN_PRODUITS_UPDATE|str_form_value }}"><img src="{{ photo_not_available_src|escape('html') }}" alt="{{ STR_PHOTO_NOT_AVAILABLE_ALT|str_form_value }}" /></a>
			{% endif %}
		</td>
		<td class="center">{{ li.nb_view }}</td>
	</tr>
	{% endfor %}
	</table>
</div>
<div class="center">{{ Multipage }} <a href="{{ delete_all_href|str_form_value }}" data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" class="btn btn-danger">{{ STR_ADMIN_DELETE_ALL_RESULTS }}</a></div>
	{% endif %}
{% endif %}
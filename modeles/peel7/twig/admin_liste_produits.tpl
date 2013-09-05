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
// $Id: admin_liste_produits.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}{% if is_empty %}
	<p><a href="{{ href|escape('html') }}">{{ STR_ADMIN_PRODUITS_CREATE_CATEGORY_FIRST }}</a></p>
{% else %}
<table class="main_table">
	<tr>
		<td colspan="13">
			<form method="get" action="{{ action|escape('html') }}">
				<table class="admin_liste_produits">
					<tr><td class="entete" colspan="2" style="padding:5px">{{ STR_ADMIN_SEARCH_CRITERIA }}</td></tr>
					<tr>
						<td colspan="2" style="padding:5px">{{ STR_CATEGORY }}{{ STR_BEFORE_TWO_POINTS }}:
							<select size="1" name="cat_search" >
								<option value="null">{{ STR_ADMIN_ALL_CATEGORIES }}</option>
								<option value="0"{% if cat_search_zero_issel %} selected="selected"{% endif %}>{{ STR_ADMIN_PRODUITS_NO_CATEGORY_RELATED }}</option>
								{{ categorie_options }}
							</select>
						</td>
					</tr>
					<tr>
						<td class="top" style="padding:5px">{{ STR_ADMIN_PRODUITS_IS_PRODUCT_IN }} <strong>{{ STR_ADMIN_OUR_SELECTION }}</strong> ?<br />
							<span>
								<input type="radio" name="home_search" value="null" checked="checked" /> {{ STR_ADMIN_ANY }}&nbsp;
								<input type="radio" name="home_search"{% if home_search_one_issel %} checked="checked"{% endif %} value="1" /> {{ STR_YES }}
								<input type="radio" name="home_search"{% if home_search_zero_issel %} checked="checked"{% endif %} value="0" /> {{ STR_NO }}
							</span>
						</td>
						<td class="top" style="padding:5px">{{ STR_ADMIN_PRODUITS_IS_PRODUCT_IN }} <strong>{{ STR_NOUVEAUTES }}</strong> ?<br />
							<span>
								<input type="radio" name="new_search" value="null" checked="checked" /> {{ STR_ADMIN_ANY }} &nbsp;
								<input type="radio" name="new_search"{% if new_search_one_issel %} checked="checked"{% endif %} value="1" /> {{ STR_YES }}
								<input type="radio" name="new_search"{% if new_search_zero_issel %} checked="checked"{% endif %} value="0" /> {{ STR_NO }}
							</span>
						</td>
					</tr>
					<tr>
						<td class="top" style="padding:5px">{{ STR_ADMIN_PRODUITS_IS_PRODUCT_IN }} <strong>{{ STR_PROMOTION }}</strong> ?<br />
							<span>
								<input type="radio" name="promo_search" value="null" checked="checked" /> {{ STR_ADMIN_ANY }} &nbsp;
								<input type="radio" name="promo_search"{% if promo_search_one_issel %} checked="checked"{% endif %} value="1" /> {{ STR_YES }} &nbsp;
								<input type="radio" name="promo_search"{% if promo_search_zero_issel %} checked="checked"{% endif %} value="0" /> {{ STR_NO }}
							</span>
						</td>
						<td class="top" style="padding:5px">
						{% if is_best_seller_module_active %}
							{{ STR_ADMIN_PRODUITS_IS_PRODUCT_IN }} <strong>{{ STR_TOP }}</strong> ?<br />
							<span>
								<input type="radio" name="top_search" value="null" checked="checked" /> {{ STR_ADMIN_ANY }} &nbsp;
								<input type="radio" name="top_search"{% if top_search_one_issel %} checked="checked"{% endif %} value="1" /> {{ STR_YES }} &nbsp;
								<input type="radio" name="top_search"{% if top_search_zero_issel %} checked="checked"{% endif %} value="0" /> {{ STR_NO }}
							</span>
						{% endif %}
						</td>
					</tr>
					{% if is_gifts_module_active %}
					<tr>
						<td class="top" style="padding:5px">{{ STR_ADMIN_PRODUITS_IS_PRODUCT }} <strong>{{ STR_MODULE_GIFTS_ADMIN_GIFT }}</strong> ?<br />
							<span>
								<input type="radio" name="on_gift" value="null" checked="checked" /> {{ STR_ADMIN_ANY }} &nbsp;
								<input type="radio" name="on_gift"{% if on_gift_one_issel %} checked="checked"{% endif %} value="1" /> {{ STR_YES }}
								<input type="radio" name="on_gift"{% if on_gift_zero_issel %} checked="checked"{% endif %} value="0" /> {{ STR_NO }}
							</span>
						</td>
						<td></td>
					</tr>
					{% endif %}
					<tr>
						<td class="top" style="padding:5px">{{ STR_REFERENCE }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" name="reference_search" size="15" value="" /></td>
						<td class="top" style="padding:5px">{{ STR_ADMIN_PRODUCT_NAME }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" name="name_search" size="15" value="" /></td>
					</tr>
					<tr>
						<td class="center" colspan="2" style="padding:5px"><p><input class="bouton" type="submit" value="{{ STR_SEARCH|str_form_value }}" name="action" /></p></td>
					</tr>
			  	</table>
			</form>
		</td>
	</tr>
	<tr>
		<td class="entete" colspan="13">{{ STR_ADMIN_PRODUITS_PRODUCTS_LIST }} <span style="float:right;">{{ STR_ADMIN_PRODUITS_PRODUCTS_COUNT }}{{ STR_BEFORE_TWO_POINTS }}: {{ nombre_produits }}</span></td>
	</tr>
	<tr>
		<td colspan="13"><img src="images/add.png" width="16" height="16" alt="" class="middle" /><a href="{{ ajout_produits_href|escape('html') }}">{{ STR_ADMIN_CATEGORIES_ADD_PRODUCT }}</a></td>
	</tr>
	{% if is_duplicate_module_active %}
	<tr>
		<td colspan="13"><b>{{ STR_NOTA_BENE }}{{ STR_BEFORE_TWO_POINTS }}:</b> {{ STR_ADMIN_PRODUITS_DUPLICATE_WARNING }}</td>
	</tr>
	{% endif %}
	{% if not (lignes) %}
	<tr><td><b>{{ STR_ADMIN_PRODUITS_NOTHING_FOUND }}</b></td></tr>
	{% else %}
	{{ HeaderRow }}
	{% for li in lignes %}
		{{ li.tr_rollover }}
		<td class="center">
			<a onclick="return confirm('{{ li.drop_confirm|filtre_javascript(true,true,true) }}');" class="label" title="{{ STR_DELETE|str_form_value }} {{ li.name }}" href="{{ li.drop_href|escape('html') }}"><img src="{{ li.drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a>
			<a title="{{ STR_MODIFY|str_form_value }}" href="{{ li.edit_href|escape('html') }}"><img src="{{ li.edit_src|escape('html') }}" alt="edit" /></a>
			{% if is_duplicate_module_active %}
			<a title="{{ STR_ADMIN_PRODUITS_DUPLICATE|str_form_value }}" href="{{ li.dup_href|escape('html') }}"><img src="{{ li.dup_src|escape('html') }}" alt="" /></a>
			{% endif %}
		</td>
		<td class="center">{{ li.reference }}</td>
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
			{% if not (li.sites) %}
				<span style="color:red">-</span><br />
			{% else %}
				{% for site in li.sites %}
				{{ site|html_entity_decode_if_needed }}<br />
				{% endfor %}
			{% endif %}
		</td>
		<td class="center"><a class="label" title="{{ STR_ADMIN_PRODUITS_UPDATE|str_form_value }}" href="{{ li.modify_href|escape('html') }}">{{ li.modify_label|html_entity_decode_if_needed }}</a></td>
		<td class="center">{% if site_parameters_prices=='edit' %}<input type="text" name="price_per_product_id[{{ li.id|str_form_value }}]" value="{{ li.prix|str_form_value }}" style="width:65px" onchange="update_price(this, '{{ li.id|str_form_value }}', '{{ administrer_url|str_form_value }}')" />{% else %}{{ li.prix }}{% endif %}</td>
		<td class="center"><img class="change_status" src="{{ li.etat_src|escape('html') }}" alt="" onclick="{{ li.etat_onclick|escape('html') }}" /></td>
		<td class="center">
			{% if is_stock_advanced_module_active %}
				{% if (li.stock_href) %}
					<a title="{{ STR_ADMIN_PRODUITS_MANAGE_STOCKS }}" href="{{ li.stock_href|escape('html') }}"><img src="{{ li.stock_src|escape('html') }}" alt="" /></a>
				{% else %}
					-
				{% endif %}
			{% endif %}
		</td>
		{% if is_gifts_module_active %}
		<td class="center">{{ li.points }} pts</td>
		{% endif %}
		<td class="center">{{ li.date }}</td>
		<td class="center">
			{% if (li.utilisateur_href) %}
				<a href="{{ li.utilisateur_href|escape('html') }}">{{ li.societe|html_entity_decode_if_needed }}</a><br />
			{% else %}
				<span style="color:red">-</span>
			{% endif %}
		</td>
		<td class="center">
			{% if (li.product_src) %}
				<a href="{{ li.modify_href|escape('html') }}" title="{{ STR_ADMIN_PRODUITS_UPDATE|str_form_value }}"><img src="{{ li.product_src|escape('html') }}" alt="{{ li.product_name|str_form_value }}" /></a>
			{% else %}
				<a href="{{ li.modify_href|escape('html') }}" title="{{ STR_ADMIN_PRODUITS_UPDATE|str_form_value }}"><img src="{{ photo_not_available_src|escape('html') }}" alt="{{ STR_PHOTO_NOT_AVAILABLE_ALT|str_form_value }}" /></a>
			{% endif %}
		</td>
		<td class="center">{{ li.nb_view }}</td>
	</tr>
	{% endfor %}
	{% endif %}
	<tr><td class="center" colspan="13">{{ Multipage }}</td></tr>
</table>
{% endif %}
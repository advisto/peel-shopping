{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: search_form.tpl 38950 2013-11-22 20:57:51Z gboussin $
#}<form class="search_form" action="{{ action|escape('html') }}" method="get">
	<h2>{{ STR_SEARCH }}{{ search }}</h2>
	<ul class="attribute_select_search attribute_select_search_part1">
		<li class="input">
			{{ STR_SEARCH }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" class="form-control"  id="search_" name="search" size="48" value="{{ value|str_form_value }}" placeholder="{{ STR_ENTER_KEY|str_form_value }}" />
			<select class="form-control" name="match">
				<option value="1"{% if match == 1 %} selected="selected"{% endif %}>{{ STR_SEARCH_ALL_WORDS }}</option>
				<option value="2"{% if match == 2 %} selected="selected"{% endif %}>{{ STR_SEARCH_ANY_WORDS }}</option>
				<option value="3"{% if match == 3 %} selected="selected"{% endif %}>{{ STR_SEARCH_EXACT_SENTENCE }}</option>
			</select>
		</li>
	</ul>
{% if is_advanced_search_active %}
	<ul class="attribute_select_search attribute_select_search_part2">
	{% if not is_annonce_module_active %}
		{% if (select_categorie) %}
		<li class="attribute_categorie">
			 <select class="form-control" name="categorie">
				<option value="">{{ STR_CAT_LB }}</option>
				{{ select_categorie }}
			</select>
		</li>
		{% endif %}
		{% for sa in select_attributes %}
			{{ sa }}
		{% endfor %}
		{{ custom_attribute }}
	{% else %}
		<li class="select_categorie_annonce">
			{{ STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD }}{{ STR_BEFORE_TWO_POINTS }}: <select class="form-control" name="cat_select">
				<option value="">{{ STR_MODULE_ANNONCES_AD_CATEGORY }}</option>
				{% for cao in cat_ann_opts %}
					<option value="{{ cao.value|str_form_value }}"{% if cao.issel %} selected="selected"{% endif %}>{{ cao.name }}</option>
				{% endfor %}
			</select>
		</li>
		<li class="select_type">
		{% if ads_contain_lot_sizes %}
			<select class="form-control" name="cat_detail">
				<option value="">{{ STR_TYPE }}</option>
				<option value="gros"{% if (cat_detail) and cat_detail == 'gros' %} selected="selected"{% endif %}>{{ STR_MODULE_ANNONCES_OFFER_GROS }}</option>
				<option value="demigros"{% if (cat_detail) and cat_detail == 'demigros' %} selected="selected"{% endif %}>{{ STR_MODULE_ANNONCES_OFFER_DEMIGROS }}</option>
				<option value="detail"{% if (cat_detail) and cat_detail == 'detail' %} selected="selected"{% endif %}>{{ STR_MODULE_ANNONCES_OFFER_DETAIL }}</option>
			</select>
		{% endif %}
			<input name="cat_statut" type="checkbox" value="1" {% if (cat_statut) and cat_statut == 1 %} checked="checked"{% endif %} /> {{ STR_MODULE_ANNONCES_ALT_VERIFIED_ADS }}
		</li>
		{% if (ad_lang_select) %}
		<li class="ad_lang">
			{{ ad_lang_select }}
		</li>
		{% endif %}
		<li class="input">
			{{ STR_TOWN }} / {{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" class="form-control"  id="city_zip" name="city_zip" size="60" value="{{ city_zip|str_form_value }}" />
		</li>
		<li class="select_country_annonce">{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:
			<select class="form-control" name="country">
				<option value="">{{ STR_CHOOSE }}...</option>
				{{ country }}
			</select>
			{% for c in continent_inputs %}
				<input type="checkbox" name="continent[]" value="{{ c.value|str_form_value }}"{% if c.issel %} checked="checked"{% endif %} /> {{ c.name }}
			{% endfor %}
		</li>
		{% if (near_position) %}
		<li class="near_position">
			{{ near_position }}
		</li>
		{% endif %}
	{% endif %}
	</ul>
{% endif %}
	<div class="attribute_select_search attribute_select_search_part3">
		<input class="btn btn-primary" type="submit" value="{{ STR_SEARCH|str_form_value }}" />
	</div>
</form>
<br />
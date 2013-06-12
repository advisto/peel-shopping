{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_arbo_categorie.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}{{ tr_rollover }}
	<td class="center">
		<a title="{{ STR_ADMIN_CATEGORIES_ADD_SUBCATEGORY|str_form_value }}" href="{{ ajout_cat_href|escape('html') }}"><img src="{{ ajout_cat_src|escape('html') }}" width="24" alt="" /></a>
		&nbsp;<a title="{{ STR_ADMIN_CATEGORIES_ADD_PRODUCT|str_form_value }}" href="{{ ajout_prod_href|escape('html') }}"><img src="{{ ajout_prod_src|escape('html') }}" width="24" alt="" /></a>
		&nbsp;<a title="{{ STR_ADMIN_CATEGORIES_DELETE_CATEGORY|str_form_value }}" onclick="return confirm('{{ STR_ADMIN_DELETE_WARNING|filtre_javascript(true,true,true) }}');" href="{{ sup_cat_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="" /></a>
	</td>
	<td class="center">{{ cat_id }}</td>
	<td class="left">{{ indent }}{% if (image) %}<img src="{{ image.src|escape('html') }}" alt="{{ image.name|str_form_value }}" />{% endif %}</td>
	<td class="left">{{ indent }}<a href="{{ modif_href|escape('html') }}">{{ cat_nom|html_entity_decode_if_needed }}</a></td>
	<td class="left">
	{% if sites_names %}
		{% for sn in sites_names %}
			{{ sn|html_entity_decode_if_needed }}<br />
		{% endfor %}
	{% else %}
		<span style="color:red">-</span><br />
	{% endif %}
	</td>
	{% if (promotion) %}
	<td class="center">{{ promotion.percent }} % / {{ promotion.prix }}</td>
	{% endif %}
	<td class="center">{{ STR_ADMIN_LEVEL }} {{ depth }}<br />{% if (up_href) %}<a href="{{ up_href|escape('html') }}"><img src="{{ up_src|escape('html') }}" alt="" /></a>{% endif %} {{ STR_NUMBER }}{{ cat_position }} <a href="{{ desc_href|escape('html') }}"><img src="{{ desc_src|escape('html') }}" alt="" /></a></td>
	<td class="center"><img class="change_status" src="{{ modif_src|escape('html') }}" alt="" onclick="{{ etat_onclick|escape('html') }}" /></td>
</tr>
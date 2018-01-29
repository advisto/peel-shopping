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
// $Id: products_list_brief.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}{% if (cat) %}
	<div class="product_breadcrumb">
		{{ breadcrumb }}
	</div>
	{% if main_categories %}
		<div class="well list_main_categorie">
		{{ main_categories }}
		</div>
	{% endif %}
	<div>
		{% if (cat.image) %}
		<div style="padding-left:10px; padding-bottom:10px;" class="pull-right"><img alt="{{ cat.image.name }}" src="{{ cat.image.src|escape('html') }}" style="max-height: 110px;" /></div>
		{% endif %}
		<h1 property="name" class="products_list_brief">{{ cat.name|html_entity_decode_if_needed }}</h1>
		{% if (cat.admin) %}
		<p class="center"><a href="{{ cat.admin.href|escape('html') }}" class="title_label">{{ cat.admin.label }}</a></p>
		{% endif %}
		{% if (cat.offline) %}
		<p style="color: red;">{{ cat.offline }}</p>
		{% endif %}
		<div style="text-align:justify">{{ cat.description|html_entity_decode_if_needed|trim|nl2br_if_needed }}</div>
		{% if (cat.promotion) %}
		<p class="center"> {{ cat.promotion.label }} <b>{{ cat.promotion.discount_text }}</b></p>
		{% endif %}
	</div>
{% endif %}
{% if (subcategories) %}
	<div class="clearfix"></div>
	{{ subcategories }}
{% endif %}
{{ associated_products }}
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
// $Id: products_list_brief.tpl 38971 2013-11-24 19:14:28Z gboussin $
#}{% if (cat) %}
	<div>
		{% if (cat.image) %}
		<div style="padding-left:10px; padding-bottom:10px;" class="pull-right"><img alt="{{ cat.image.name }}" src="{{ cat.image.src|escape('html') }}" style="max-height: 110px;" /></div>
		{% endif %}
		<h1 class="products_list_brief">{{ cat.name|html_entity_decode_if_needed }}</h1>
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
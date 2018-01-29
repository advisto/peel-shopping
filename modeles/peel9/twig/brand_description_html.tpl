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
// $Id: brand_description_html.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}{% if is_error %}
<h1 property="name" class="brand_description_html">{{ error_header }}</h1>
<p class="alert alert-danger">{{ error_content }}</p>
{% else %}
	{% for item in data %}
{% if item.display_brand %}
	{% if data|length==1 and (item.description) %}
	<h{% if data|length==1 %}1{% else %}2{% endif %} class="brand_description_html">{{ item.nom|html_entity_decode_if_needed }}</h{% if data|length==1 %}1{% else %}2{% endif %}>
		{% if item.admin_content %}
	<p class="center"><a href="{{ item.admin_link.href|escape('html') }}" class="title_label">{{ item.admin_link.name }}</a></p>
		{% endif %}
	<table class="full_width">
		<tr>
			<td class="left" style="padding:5px; width:{{ item.small_width }}px;">
			{% if item.has_image %}
				{% if (item.image.href) %}<a href="{{ item.image.href|escape('html') }}">{% endif %}<img src="{{ item.image.src|escape('html') }}" alt="" />{% if (item.image.href) %}</a>{% endif %}
			{% endif %}
			</td>
			<td class="articles_count">
				{% if (item.href) %}<a href="{{ item.href|escape('html') }}">{% endif %}{{ item.nb_produits_txt }}{% if (item.href) %}</a>{% endif %}
			</td>
		</tr>
		{% if item.description %}
		<tr><td colspan="3" style="padding:5px;">{{ item.description|html_entity_decode_if_needed }}</td></tr>
		{% endif %}
</table>
	{% else %}
	<div class="center {% if data|length==1 %}col-md-12{% else %} col-md-3 col-sm-4{% endif %} ">
		<h{% if data|length==1 %}1{% else %}2{% endif %} class="brand_description_html">{% if (item.href) %}<a href="{{ item.href|escape('html') }}">{% endif %}{{ item.nom|html_entity_decode_if_needed }}{% if (item.href) %}</a>{% endif %}</h{% if data|length==1 %}1{% else %}2{% endif %}>
		{% if item.admin_content %}
		<p class="center"><a href="{{ item.admin_link.href|escape('html') }}" class="title_label">{{ item.admin_link.name }}</a></p>
		{% endif %}
		<div style="min-height: 150px">
		{% if item.has_image %}
			{% if (item.image.href) %}<a href="{{ item.image.href|escape('html') }}">{% endif %}<img src="{{ item.image.src|escape('html') }}" alt="" />{% if (item.image.href) %}</a>{% endif %}
		{% endif %}
		</div>
		<div class="articles_count">
			{% if (item.href) %}<a href="{{ item.href|escape('html') }}">{% endif %}{{ item.nb_produits_txt }}{% if (item.href) %}</a>{% endif %}
		</div>
	</div>
	{% if loop.index%4==0 %}
	<div class="clearfix visible-md visible-lg"></div>
	{% endif %}
	{% if loop.index%3==0 %}
	<div class="clearfix visible-sm"></div>
	{% endif %}
	{% endif %}
{% endif %}
	{% endfor %}
{% endif %}
{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: brand_description_html.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}{% if is_error %}
<h1 class="brand_description_html">{{ error_header }}</h1>
<p class="alert alert-danger">{{ error_content }}</p>
{% else %}
	{% for item in data %}
<h{% if (data|length)==1 %}1{% else %}2{% endif %} class="brand_description_html">{{ item.nom|html_entity_decode_if_needed }}</h{% if (data|length)==1 %}1{% else %}2{% endif %}>
<table>
	<tr>
		<td class="top" style="width:50%; padding:10px;">
			{% if item.display_brand %}
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
						<td class="left" style="padding:10px">
							{% if (item.href) %}<a href="{{ item.href|escape('html') }}">{% endif %}<span style="font-size:16px; font-weight:bold;">{{ item.nom|html_entity_decode_if_needed }}</span>{% if (item.href) %}</a>{% endif %}
						</td>
						<td class="articles_count">
							{% if (item.href) %}<a href="{{ item.href|escape('html') }}">{% endif %}{{ item.nb_produits_txt }}{% if (item.href) %}</a>{% endif %}
						</td>
					</tr>
				{% if (item.description) %}
					<tr><td colspan="3" style="padding:5px;">{{ item.description|html_entity_decode_if_needed }}</td></tr>
				{% endif %}
				</table>
			{% endif %}
		</td>
	</tr>
</table>
	{% endfor %}
{% endif %}
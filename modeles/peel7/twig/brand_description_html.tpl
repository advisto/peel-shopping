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
// $Id: brand_description_html.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}{% if is_error %}
<h2 class="brand_description_html">{{ error_header }}</h2>
<p class="global_error">{{ error_content }}</p>
{% else %}
	{% for item in data %}
<h2 class="brand_description_html">{{ item.nom|html_entity_decode_if_needed }}</h2>
<table>
	<tr>
		<td class="top" style="width:50%; padding:10px;">
			{% if item.display_brand %}
				{% if item.admin_content %}
					<p class="center"><a href="{{ item.admin_link.href|escape('html') }}" class="label">{{ item.admin_link.name }}</a></p>
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
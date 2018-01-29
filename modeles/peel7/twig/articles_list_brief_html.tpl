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
// $Id: articles_list_brief_html.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}{% if is_not_empty %}
	<h1 property="name" class="page_title">{{ name|html_entity_decode_if_needed }}</h1>
{% endif %}
<div class="rub_content" {% if is_not_empty %}{{ technical_code }}{% endif %}">
	{% if is_not_empty %}
		{% if (offline_rub_txt) %}
			<p style="color: red;">{{ offline_rub_txt }}</p>
		{% endif %}
		{% if (main_image) %}
			{% if main_image.file_type!='image' %}
				<a style="margin: 5px;" href="{{ main_image.href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ wwwroot }}/images/logoPDF_small.png" alt="{{ name }}" /></a>
			{% else %}
				<p><img style="margin: 5px;" src="{{ main_image.src|escape('html') }}" alt="{{ name|escape('html') }}" /></p>
			{% endif %}
		{% endif %}
		{{ description|html_entity_decode_if_needed|trim|nl2br_if_needed }}
		{% if (descriptions_clients) %}
		{{ descriptions_clients }}
		{% endif %}
		{% if (reference_multipage) %}
		{{ reference_multipage }}
		{% endif %}
	{% endif %}
	{% if add_cart_by_reference is defined %}
	{{ add_cart_by_reference }}
	{% endif %}
	{% if (rubriques_sons_html) %}
	{{ rubriques_sons_html }}
	{% endif %}
	{% if (articles_html) %}
	{{ articles_html }}
	{% endif %}
	{% if (diaporama) %}
	{{ diaporama }}
	{% endif %}
	{% if (admin) %}
	<p><a href="{{ admin.href|escape('html') }}" class="title_label admin_link">{{ admin.modify_content_category_txt }}</a></p>
	{% endif %}
</div>
{% if (plus) %}
<div class="rub_content_plus">
	{% for art in plus.arts %}
		<div class="rub_content_plus_item">
		<h4 class="side_titre">{{ art.titre|upper|html_entity_decode_if_needed }}</h4>
		<div class="side_text">{{ art.texte|html_entity_decode_if_needed }}</div>
		</div>
	{% endfor %}
</div>
{% endif %}
{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: articles_list_brief_html.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}{% if is_not_empty %}
	<h1 class="page_title">{{ name|html_entity_decode_if_needed }}</h1>
{% endif %}
<div class="rub_content">
	{% if is_not_empty %}
		{% if (offline_rub_txt) %}
			<p style="color: red;">{{ offline_rub_txt }}</p>
		{% endif %}
		{% if (image_src) %}
			<p><img style="margin: 5px;" src="{{ image_src|escape('html') }}" alt="{{ name }}" /></p>
		{% endif %}
		{{ description|html_entity_decode_if_needed|trim|nl2br_if_needed }}
		{% if (descriptions_clients) %}
		{{ descriptions_clients }}
		{% endif %}
		{% if (reference_multipage) %}
		{{ reference_multipage }}
		{% endif %}
	{% endif %}
	{% if (rubriques_sons_html) %}
	{{ rubriques_sons_html }}
	{% endif %}
	{% if (articles_html) %}
	{{ articles_html }}
	{% endif %}
	{% if (admin) %}
	<p><a href="{{ admin.href|escape('html') }}" class="label admin_link">{{ admin.modify_content_category_txt }}</a></p>
	{% endif %}
</div>
{% if (plus) %}
	<img class="rub_content_plus_img" src="{{ plus.src|escape('html') }}" alt="" />
	<div class="rub_content_plus">
		{% for art in plus.arts %}
			<div class="rub_content_plus_item">
			<h4 class="side_titre">{{ art.titre|upper|html_entity_decode_if_needed }}</h4>
			<div class="side_text">{{ art.texte|html_entity_decode_if_needed }}</div>
			</div>
		{% endfor %}
	</div>
{% endif %}
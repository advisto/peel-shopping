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
// $Id: articles_html.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}{% if is_content %}
	<div class="rubrique">
		{% for item in data %}
		<div class="rubrique_title">
			{% if item.src %}
			<img src="{{ item.src|escape('html') }}" style="margin: 5px;" height="100" align="left" alt="" />
			{% endif %}
			<h3><a href="{{ item.href|escape('html') }}">{{ item.titre|html_entity_decode_if_needed }}</a></h3>
			{{ item.chapo|html_entity_decode_if_needed }}
			{% if item.is_texte and category_content_show_explicit_buttons_if_articles_more_to_read %}
			<div class="inside_rubrique row">
				<div class="left col-sm-6"><a href="{{ item.href|escape('html') }}" class="btn btn-primary btn-red"><span style="margin-right: -5px;" class="glyphicon glyphicon-circle-arrow-right">&#160;</span>{{ item.titre|html_entity_decode_if_needed }}</a></div>
				<div class="right col-sm-6">
			{% else %}
			<div class="inside_rubrique">
				<div class="right">
			{% endif %}
					<a href="{{ haut_de_page_href|escape('html') }}"><span style="margin-right: -5px;" class="glyphicon glyphicon-circle-arrow-up">&#160;</span>{{ haut_de_page_txt }}</a>
				</div>
			</div>
			<p style="clear:both;"></p>
			<hr />
		</div>
		{% endfor %}
	</div>
{% endif %}
<p>{{ multipage }}</p>
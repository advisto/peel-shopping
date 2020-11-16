{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: articles_html.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}{% if is_content %}
	<div class="rubrique row">
		{% if STR_TITLE_ARTICLE_HTML %}{% if IN_HOME is empty %}<h1>{{ STR_TITLE_ARTICLE_HTML }}</h1>{% else %}<h2 class="home_title">{{ STR_TITLE_ARTICLE_HTML }}</h2>{% endif %}{% endif %}
		{% for item in data %}
		<div class="rubrique_title col-md-{(12/articles_html_pages_nb_column)|floor} col-sm-{(12/articles_html_pages_nb_column)|floor}">
			{% if item.src %}
			<img src="{{ item.src|escape('html') }}" style="margin: 5px;" height="100" align="left" alt="" />
			{% endif %}
			{% if item.titre %}
				<h3><a href="{{ item.href|escape('html') }}">{{ item.titre|html_entity_decode_if_needed }}</a></h3>
				{% if display_chapo_disable is empty %}
				{{ item.chapo|html_entity_decode_if_needed }}
				{% endif %}
				{% if item.is_texte and category_content_show_explicit_buttons_if_articles_more_to_read %}
				<div class="inside_rubrique row">
					<div class="left col-md-6"><a style="white-space: normal;" href="{{ item.href|escape('html') }}" class="btn btn-primary btn-red"><span style="margin-right: -5px;" class="glyphicon glyphicon-circle-arrow-right">&#160;</span>{{ item.titre|html_entity_decode_if_needed }}</a></div>
					<div class="right col-md-6">
				{% else %}
				<div class="inside_rubrique">
					<div class="right">
				{% endif %}
						<a href="{{ haut_de_page_href|escape('html') }}"><span style="margin-right: -5px;" class="glyphicon glyphicon-circle-arrow-up">&#160;</span>{{ haut_de_page_txt }}</a>
					</div>
				</div>
			{% endif %}
			<p style="clear:both;"></p>
			<hr />
		</div>
			{% if item.i%(12/(12/articles_html_pages_nb_column|floor))==0 %}
				<div class="clearfix"></div>
			{% endif %}
		{% endfor %}
	</div>
{% endif %}
<p>{{ multipage }}</p>
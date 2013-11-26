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
// $Id: articles_html.tpl 38964 2013-11-24 15:22:17Z gboussin $
#}{% if is_content %}
	<table class="rubrique">
		{% for item in data %}
		<tr>
			<td class="title">
				{% if item.src %}
				<img src="{{ item.src|escape('html') }}" style="margin: 5px;" height="100" align="left" alt="" />
				{% endif %}
				<h3><a href="{{ item.href|escape('html') }}">{{ item.titre|html_entity_decode_if_needed }}</a></h3>
				<p>{{ item.chapo|html_entity_decode_if_needed }}</p>
				<table class="inside_rubrique">
					<tr>
					{% if item.is_texte %}
						<td><a href="{{ item.href|escape('html') }}">{{ STR_MORE_DETAILS }}</a></td>
					{% endif %}
						<td class="right"><a href="{{ haut_de_page_href|escape('html') }}">{{ haut_de_page_txt }}</a></td>
					</tr>
				</table>
				<p style="clear:both;"></p>
				<hr />
			</td>
		</tr>
		{% endfor %}
	</table>
{% endif %}
<p>{{ multipage }}</p>
{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rubriques_sons_html.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<table >
	<tr><td><b>{{ list_rubriques_txt }}:</b></td></tr>
	{% for item in data %}
	<tr>
		<td>
			<ul>
				<li>
					{% if (item.image_src) %}
					<img src="{{ item.image_src|escape('html') }}" alt="" /><br />
					{% endif %}
					<h3><a href="{{ item.href|escape('html') }}">{% if (item.lien_src) %}<img src="{{ item.lien_src|escape('html') }}" alt="{{ item.name }}" />{% else %}{{ item.name }}{% endif %}</a></h3>
					<p>{{ description }}</p>
				</li>
			</ul>
		</td>
	</tr>
	{% endfor %}
</table>
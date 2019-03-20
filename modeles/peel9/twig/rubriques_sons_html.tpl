{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rubriques_sons_html.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<h3>{{ list_rubriques_txt }}:</h3>
<ul>
{% for item in data %}
	<li>
		{% if (item.image_src) %}
		<img src="{{ item.image_src|escape('html') }}" alt="" /><br />
		{% endif %}
		<b><a href="{{ item.href|escape('html') }}">{% if (item.lien_src) %}<img src="{{ item.lien_src|escape('html') }}" alt="{{ item.name }}" />{% else %}{{ item.name }}{% endif %}</a></b>
		{% if (description) %}<p>{{ description }}</p>{% endif %}
	</li>
{% endfor %}
</ul>
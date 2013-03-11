{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: recursive_items_display.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}{% for it in items %}
<li class="{% if it.has_sons %}plus{% else %}minus{% endif %}{% if it.is_current %} current{% endif %}{% if (it.technical_code) %} m_item_{{ it.technical_code }}{% endif %}">
	{% if (it.href) %}
		{% set max_length=item_max_length %}
		{% if (it.nb) %}
		{% set max_length=max_length-(it.nb|length)-5 %}
		{% endif %}
		{% if it.has_sons and location == 'left' %}
		{% set max_length=max_length-3 %}
		{% endif %}
		<a href="{{ it.href|escape('html') }}">
			{% if it.has_sons and location == 'left' %}
				<span class="menu_categorie_link">{{ it.name|str_shorten(max_length) }}{% if (it.nb) %} ({{ it.nb }}){% endif %}</span><span style="float:right; display:block"><img src="{{ sons_ico_src|escape('html') }}" alt="+" /></span>
			{% else %}
				{{ it.name|str_shorten(max_length) }}{% if (it.nb) %} ({{ it.nb }}){% endif %}
			{% endif %}
		</a>
	{% endif %}
	{% if it.has_sons and (it.SONS) %}
		{% if not (it.href) %}
			<a href="">{% if location == 'left' %}<img src="{{ sons_ico_src|escape('html') }}" alt="+" />{% endif %}</a>
		{% endif %}
		<ul class="sousMenu level{{ it.depth }}">{{ it.SONS }}</ul>
	{% endif %}
</li>
{% endfor %}
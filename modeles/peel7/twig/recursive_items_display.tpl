{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: recursive_items_display.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
#}{% for it in items %}
<li class="{% if it.has_sons %}dropdown-submenu plus{% else %}minus{% endif %}{% if it.is_current %} current{% endif %}{% if (it.technical_code) %} m_item_{{ it.technical_code }}{% endif %}">
		{% set max_length=item_max_length %}
		{% if (it.nb) %}
		{% set max_length=max_length-(it.nb|length)-3 %}
		{% endif %}
		{% if it.has_sons and location == 'left' %}
		{% set max_length=max_length-3 %}
		{% endif %}
	{% if it.has_sons and (it.SONS) %}
		<a id="{{ it.id }}" class="dropdown-toggle" href="{{ it.href }}">{% if (it.nb) %}<span class="nb_item badge pull-right">{{ it.nb }}</span> {% endif %}{{ it.name|str_shorten(max_length) }}</a>
		<ul class="sousMenu level{{ it.depth }}" dropdown-menu" aria-labelledby="{{ it.id }}" role="menu">{{ it.SONS }}</ul>
	{% elseif (it.href) %}
		<a href="{{ it.href|escape('html') }}">
			{% if it.has_sons and location == 'left' %}
				<span class="menu_categorie_link">{% if (it.nb) %} <span class="nb_item badge pull-right">{{ it.nb }}</span> {% endif %}{{ it.name|str_shorten(max_length) }}</span><span class="glyphicon glyphicon-chevron-right" alt="+"></span>
			{% else %}
				{% if (it.nb) %} <span class="nb_item badge pull-right">{{ it.nb }}</span> {% endif %}{{ it.name|str_shorten(max_length) }}
			{% endif %}
		</a>
	{% endif %}
</li>
{% endfor %}
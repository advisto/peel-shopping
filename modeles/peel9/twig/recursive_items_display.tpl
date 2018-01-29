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
// Id: recursive_items_display.tpl 46354 2015-07-14 20:05:02Z sdelaporte 
#}
{% if (columns) %}
<div class="col-sm-{{ (12 // columns) }}"><ul>
{% endif %}
{% for it in items %}
	{% set max_length=it.item_max_length %}
	{% if (it.nb) %}
		{% set max_length=max_length-(it.nb|length)-3 %}
	{% endif %}
	{% if it.has_sons and location == 'left' %}
		{% set max_length=max_length-3 %}
	{% endif %}
	{% if display_mode=='option' %}
<option value="{{ it.value|str_form_value }}"{% if it.is_selected %} selected="selected" class="bold"{% endif %}{% if it.disabled %} style="color:#AAAAAA"{% endif %}>{{ it.indent }}{{ it.name|str_shorten(max_length) }}</option>{% if it.has_sons and (it.SONS) %}{{ it.SONS }}{% endif %}
	{% elseif display_mode=='checkbox' %}
<div class="col-md-4"><input name="{{ input_name|str_form_value }}[]" type="checkbox" value="{{ it.value|str_form_value }}"{% if it.is_selected %} checked="checked" class="bold"{% endif %} /> {{ it.indent }}{{ it.name|str_shorten(max_length) }}</div>{% if it.has_sons and (it.SONS) %}{{ it.SONS }}{% endif %}
	{% elseif display_mode=='div' %}
		{% if it.depth == 1 %}
			<div class="col-md-4">
		{% endif %}
		{% if it.has_sons and (it.SONS) %}
			<a id="{{ it.id }}" class="dropdown-toggle" href="{{ it.href }}">
				{% if (it.nb) %}<span class="nb_item badge pull-right">{{ it.nb }}</span> {% endif %}
					<h4>{{ it.name|str_shorten(max_length) }}</h4>
			</a>
			<div class="listsousMenu level{{ it.depth }}" aria-labelledby="{{ it.id }}" role="menu">{{ it.SONS }}</div>
		{% elseif (it.href) %}
			<a href="{{ it.href|escape(html) }}">
				{% if it.has_sons and location == 'left' %}
					<span class="menu_categorie_link">{% if (it.nb) %} <span class="nb_item badge pull-right">{{ it.nb }}</span> {% endif %}<h4>{{ it.name|str_shorten(max_length) }}</h4></span> <span class="glyphicon glyphicon-chevron-right" title="+"></span>
				{% else %}
					{% if it.depth == 1 %}
						{% if (it.nb) %}<span class="nb_item badge pull-right">{{ it.nb }}</span> {% endif %}<h4>{{ it.name|str_shorten(max_length) }}</h4>
					{% else %}
						{% if (it.nb) %}<span class="nb_item badge pull-right">{{ it.nb }}</span> {% endif %}<h5>{{ it.name|str_shorten(max_length) }}</h5>
					{% endif %}
				{% endif %}
			</a>
		{% endif %}
		{% if it.depth == 1 %}
			<div class="clearfix"></div>
			</div>
		{% endif %}
	{% else %}
<li class="{% if it.has_sons %}dropdown-submenu plus{% else %}minus{% endif %}{% if it.is_current %} current{% endif %}{% if (it.technical_code) %} m_item_{{ it.technical_code }}{% endif %}{% if it.class %} {{ it.class }}{% endif %}">
	{% if it.has_sons and (it.SONS) %}
		{{ it.indent }}<a id="{{ it.id }}" class="dropdown-toggle" href="{{ it.href }}">{{ it.name|str_shorten(max_length) }}{% if (it.nb) %}<span class="nb_item badge pull-right">{{ it.nb }}</span> {% endif %}</a>
		<ul class="sousMenu level{{ it.depth }} dropdown-menu" aria-labelledby="{{ it.id }}" role="menu">{{ it.SONS }}</ul>
	{% elseif (it.href) %}
		{{ it.indent }}<a href="{{ it.href|escape('html') }}">{% if it.has_sons and location == 'left' %}<span class="menu_categorie_link">{{ it.name|str_shorten(max_length) }}</span> <span class="glyphicon glyphicon-chevron-right" title="+"></span>{% else %}{{ it.name|str_shorten(max_length) }}{% endif %}{% if (it.nb) %} <span class="nb_item badge pull-right">{{ it.nb }}</span> {% endif %}</a>
	{% endif %}
</li>
	{% endif %}
	{% if (columns) and loop.index%(( items|length /columns)|round(1, 'ceil'))==0 and loop.index<(items|length) %}
</ul></div><div class="col-sm-{{ (12 // columns) }}"><ul>
	{% endif %}
{% endfor %}
{% if (columns) %}
</ul></div>
{% endif %}

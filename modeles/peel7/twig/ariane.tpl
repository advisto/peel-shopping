{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: ariane.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}<div property="breadcrumb" class="breadcrumb">
	{% if ariane %}{% if ariane.href %}<a href="{{ ariane.href|escape('html') }}" title="{{ ariane.txt }}">{% endif %}<span class="glyphicon glyphicon-home" alt="{{ ariane.txt }}"></span>{% if ariane.href %}</a>{% endif %}{% endif %}
	{% if other.txt %}
		{% if ariane %} &gt; {% endif %}
		{% if other.href %}<span typeof="Breadcrumb"><a property="url" href="{{ other.href|escape('html') }}" title="{{ other.txt }}"><span property="title">{% endif %}{{ other.txt }}{% if other.href %}</span></a></span>{% endif %}
	{% endif %}
	{% if buttons %}<div class="breadcrumb_buttons">{{ buttons }}</div><div class="clearfix"></div>{% endif %}
</div>
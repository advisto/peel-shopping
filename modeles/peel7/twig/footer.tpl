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
// $Id: footer.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<div class="col-sm-{{ footer_columns_width_sm }} col-md-{{ footer_columns_width_md }} footer_col">
	<ul class="link">
{% for l in links %}
	<li><a href="{{ l.href|escape('html') }}">{% if l.selected %}<b>{% endif %}{{ l.label }}{% if l.selected %}</b>{% endif %}</a></li>
{% endfor %}
{{ footer_additional_link }}
{% if links_2 is defined %}
	</ul>
</div>
<div class="col-sm-{{ footer_columns_width_sm }} col-md-{{ footer_columns_width_md }} footer_col">
	<ul class="link">
	{% for l in links_2 %}
		<li><a href="{{ l.href|escape('html') }}">{% if l.selected %}<b>{% endif %}{{ l.label }}{% if l.selected %}</b>{% endif %}</a></li>
	{% endfor %}
{% endif %}
{% if facebook_page is defined %}
	<li>{{ facebook_page }}</li>
{% endif %}
{% if propulse is defined %}
		<li class="li_separated">{{ propulse }} <a href="https://www.peel.fr/" onclick="return(window.open(this.href)?false:true);">{{ STR_SITE_GENERATOR }}</a></li>
{% endif %}
{% if site %}<li{% if propulse is empty %} class="li_separated"{% endif %}>&copy;{{ site }}{{ date }}</li>{% endif %}
	</ul>
</div>
{{ footer_additional }}
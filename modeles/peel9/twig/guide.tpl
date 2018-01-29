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
// Id: guide.tpl 44077 2015-02-17 10:20:38Z sdelaporte 
#}
{% if not affiche_guide_returned_link_list_without_ul %}
<ul>
{% endif  %}
	{% for l in links %}
	<li class="minus"><a href="{{ l.href|escape('html') }}">{% if l.selected %}<b>{% endif %}{{ l.label }}{% if l.selected %}</b>{% endif  %}</a></li>
	{% endfor %}
	{% if menu_contenu %}
		{{ menu_contenu }}
	{% endif  %}
	
{% if not affiche_guide_returned_link_list_without_ul %}
</ul>
{% endif %}
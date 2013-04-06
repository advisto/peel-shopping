{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: brand_link_html.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}{% if as_list %}
<ul>
{% for link in links %}
	<li {% if link.is_current %} class="current"{% endif %}><a href="{{ link.href|escape('html') }}">{{ link.value|html_entity_decode_if_needed }}</a></li>
{% endfor %}
</ul>
{% else %}
{% for link in links %}
	<a href="{{ link.href|escape('html') }}">{{ link.value|html_entity_decode_if_needed }}</a>
{% endfor %}
{% endif %}
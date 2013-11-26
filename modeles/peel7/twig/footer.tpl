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
// $Id: footer.tpl 39020 2013-11-26 00:36:36Z gboussin $
#}<ul class="link">
	{% for l in links %}
	<li><a href="{{ l.href|escape('html') }}">{% if l.selected %}<b>{% endif %}{{ l.label }}{% if l.selected %}</b>{% endif %}</a></li>
	{% endfor %}
	{% if (facebook_page) %}
	<li>{{ facebook_page }}</li>
	{% endif %}
	<li style="padding-top:10px;">{{ propulse }} <a href="https://www.peel.fr/" onclick="return(window.open(this.href)?false:true);">PEEL sites ecommerce</a></li>
	<li>&copy;{{ site }}</li>
</ul>
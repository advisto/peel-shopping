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
// $Id: admin_bas.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}</div>
		<div class="main_footer_wide"><div class="main_footer"><a href="{{ site_href|escape('html') }}" style="margin-right:70px;">{{ site }}</a> <a href="{{ peel_website_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_PEEL_SOFTWARE }}</a> - {{ STR_ADMIN_VERSION }} {{ PEEL_VERSION }} - <a href="{{ sortie_href|escape('html') }}">{{ STR_ADMIN_DISCONNECT }}</a></div></div>
		<div class="under_footer">{{ STR_ADMIN_SUPPORT }}{{ STR_BEFORE_TWO_POINTS }}: <a href="{{ peel_website_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_CONTACT_PEEL }}</a> - {{ STR_ADMIN_CONTACT_PEEL_ADDRESS }}</div>
	</div>
	<!-- Fin Total -->
	{% if (peel_debug) %}
		{% for key in peel_debug|keys %}
			<span {% if peel_debug.key.duration<0.010 %}style="color:grey"{% else %}{% if peel_debug.key.duration>0.100 %}style="color:red"{% endif %}{% endif %}>{{ key }}{{ STR_BEFORE_TWO_POINTS }}: {{ peel_debug.key.duration*1000 }} ms - {% if (peel_debug.key.sql) %}{{ peel_debug.key.sql }}{% endif %} {% if (peel_debug.key.template) %}{{ peel_debug.key.template }}{% endif %}</span><br />
		{% endfor %}
	{% endif %}
</body>
</html>
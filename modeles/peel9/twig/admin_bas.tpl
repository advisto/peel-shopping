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
// $Id: admin_bas.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}				</div>
			</div>
		</div>
		<div class="push"></div>
	</div>
	<div id="footer">
		<div class="container">
			<footer class="footer">
				<div class="main_footer_wide"><div class="main_footer">{% if (site) %}<a href="{{ site_href|escape('html') }}">{{ site }}</a> - {% endif %}<a href="{{ peel_website_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_PEEL_SOFTWARE }}</a> - {{ STR_ADMIN_VERSION }} {{ PEEL_VERSION }}</div></div>
				<div class="under_footer">{{ STR_ADMIN_SUPPORT }}{{ STR_BEFORE_TWO_POINTS }}: <a href="{{ peel_website_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_CONTACT_PEEL }}</a> - <a href="https://www.advisto.fr/" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_CONTACT_PEEL_ADDRESS }}</a></div>
			</footer>
		</div>
	</div>
	<!-- Fin Total -->
	{{ js_output }}
	{% if (peel_debug) %}
		{% for key,value in peel_debug %}
			<span {% if value.duration<0.010 %}style="color:grey"{% else %}{% if value.duration>0.100 %}style="color:red"{% endif %}{% endif %}>{{ key }}{{ STR_BEFORE_TWO_POINTS }}: {{ (value.duration*1000)|number_format(2) }} ms - Start{{ STR_BEFORE_TWO_POINTS }}{{ value.start*1000|number_format(2) }} ms  - {% if (value.sql) %}{{ value.sql }}{% endif %}{% if (value.template) %}{{ value.template }}{% endif %}{% if (value.text) %}{{ value.text }}{% endif %}</span><br />
		{% endfor %}
	{% endif %}
</body>
</html>
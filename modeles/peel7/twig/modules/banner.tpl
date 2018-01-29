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
// $Id: banner.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}{% for ban in banners %}
<div class="ba_pu" style="margin-top:3px;">
{% if (ban.html) %}
{{ ban.html }}
{% else %}
	{% if (ban.lien) %}
	<a href="{{ ban.href|escape('html') }}" {{ ban.extra_javascript }}{% if (ban.target) and ban.target != 'self' %} {% if ban.target == '_blank' %}onclick="return(window.open(this.href)?false:true);"{% else %}target="{{ ban.target }}"{% endif %}{% endif %}>
	{% endif %}
	{% if (ban.swf) %}
		{{ ban.swf }}
	{% else %}
		<img src="{{ ban.src|escape('html') }}" alt="{{ ban.lien }}"{% if ban.width != max_banner_width %} width="{{ ban.width }}px"{% endif %}{% if ban.height != max_banner_height %} height="{{ ban.height }}px"{% endif %} />
	{% endif %}
	{% if (ban.lien) %}
	</a>
	{% endif %}
{% endif %}
</div>
{% endfor %}

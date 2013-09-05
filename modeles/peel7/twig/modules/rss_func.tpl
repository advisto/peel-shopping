{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rss_func.tpl 37943 2013-08-29 09:31:55Z gboussin $
#}<div class="rss_bloc">
	{% if (fb_src) and (fb_href) %}
	<a style="margin-right:5px;" href="{{ fb_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ fb_src|escape('html') }}" alt="facebook" title="facebook" /></a>
	{% endif %}
{% if twitter_href %}
 	<a style="margin-right:5px;" href="{{ twitter_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ twitter_src|escape('html') }}" alt="twitter" style="vertical-align:top;" title="twitter" /></a>
{% endif %}
{% if googleplus_href %}
 	<a style="margin-right:5px;" href="{{ googleplus_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ googleplus_src|escape('html') }}" alt="google+" style="vertical-align:top;" title="google+" /></a>
{% endif %}
	<a href="{{ href|escape('html') }}" {% if rss_new_window %}onclick="return(window.open(this.href)?false:true);"{% endif %}><img src="{{ src|escape('html') }}" alt="rss" style="vertical-align:top;" title="RSS" /></a>
</div>
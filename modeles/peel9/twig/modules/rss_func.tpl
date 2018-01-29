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
// $Id: rss_func.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<div class="rss_bloc col-sm-{{ block_columns_width_sm }} col-md-{{ block_columns_width_md }} footer_col">
	{% if (fb_src) and (fb_href) %}
	<a style="margin-right:5px;" href="{{ fb_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ fb_src|escape('html') }}" alt="F" title="Facebook" width="48" height="48" /></a>
	{% endif %}
{% if googleplus_href %}
 	<a style="margin-right:5px;" rel="publisher" href="{{ googleplus_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ googleplus_src|escape('html') }}" alt="G+" style="vertical-align:top;" title="Google+" width="48" height="48" /></a>
{% endif %}
{% if twitter_href %}
 	<a style="margin-right:5px;" href="{{ twitter_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ twitter_src|escape('html') }}" alt="T" style="vertical-align:top;" title="Twitter" width="48" height="48" /></a>
{% endif %}
{% if viadeo_href %}
 	<a style="margin-right:5px;" href="{{ viadeo_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ viadeo_src|escape('html') }}" alt="T" style="vertical-align:top;" title="Viadeo" width="48" height="48" /></a>
{% endif %}
{% if linkedin_href %}
 	<a style="margin-right:5px;" href="{{ linkedin_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ linkedin_src|escape('html') }}" alt="T" style="vertical-align:top;" title="Linkedin" width="48" height="48" /></a>
{% endif %}
{% if href %}
	<a href="{{ href|escape('html') }}" {% if rss_new_window %}onclick="return(window.open(this.href)?false:true);"{% endif %}><img src="{{ src|escape('html') }}" alt="rss" style="vertical-align:top;" title="RSS" width="48" height="48" /></a>
{% endif %}
</div>
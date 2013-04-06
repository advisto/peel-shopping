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
// $Id: HTMLHead.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}
<head>
	{{ meta }}
	<base href="{{ wwwroot }}/" />
	{% if (favicon_href) %}<link rel="icon" type="image/x-icon" href="{{ favicon_href }}" />
	<link rel="shortcut icon" type="image/x-icon" href="{{ favicon_href }}" />{% endif %}
	{% if (link_rss_html) %}{{ link_rss_html }}{% endif %}
{% for css_href in css_files %}
	<link rel="stylesheet" media="all" href="{{ css_href|escape('html') }}" />
{% endfor %}
	{% if (bg_colors) %}
	<style>
		body {ldelim }} background-color:{{ bg_colors.body }}; {rdelim }}
		#menu1 li, .main_menu_wide {ldelim }} background-color:{{ bg_colors.menu }}; {rdelim }}
		<!--[if IE]>
			#contact_form{ldelim }}height:100% !important;{rdelim }}
		<![endif]-->
	</style>
	{% endif %}
{% for js_href in js_files %}
	<script src="{{ js_href|escape('html') }}"></script>
{% endfor %}
	{{ js_output }}
	{% if js_content %}
	<script><!--//--><![CDATA[//><!--
		{{ js_content }}
	//--><!]]></script>
	{% endif %}
	<!--[if lt IE 9]>
	<script src="{{ wwwroot }}/lib/js/html5shiv.js"></script>
	<![endif]-->
</head>
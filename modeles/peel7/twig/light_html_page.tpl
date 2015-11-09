{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: light_html_page.tpl 47592 2015-10-30 16:40:22Z sdelaporte $
#}<!DOCTYPE html>
<html lang="{{ lang }}" dir="ltr">
{% if not full_head_section_text %}
	<head>
		{% if charset %}<meta charset="{{ charset }}" />{% endif %}
		<title>{{ title }}</title>
		{{ additional_header }}
	{% if (css_files) %}
		{% for css_href in css_files %}
		<link rel="stylesheet" media="all" href="{{ css_href|escape('html') }}" />
		{% endfor %}
	{% endif %}
		{% if (js_output) %}{{ js_output }}{% endif %}
		<!--[if lt IE 9]>
		<script src="{{ wwwroot }}/lib/js/html5shiv.js"></script>
		<script src="{{ wwwroot }}/lib/js/respond.js"></script>
		<![endif]-->
	</head>
{% else %}
	{{ full_head_section_text }}
{% endif %}
	<body class="light" {% if (onload) %} onload="onload"{% endif %} vocab="http://schema.org/">
		{{ body }}
	</body>
</html>
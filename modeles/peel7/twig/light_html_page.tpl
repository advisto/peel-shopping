{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: light_html_page.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<!DOCTYPE html>
<html lang="{{ lang }}" dir="ltr">
{% if not full_head_section_text %}
	<head>
		<meta charset="{{ charset }}" />
		<title>{{ title }}</title>
		{{ additional_header }}
		<!--[if lt IE 9]>
		<script src="{{ wwwroot }}/lib/js/html5shiv.js"></script>
		<![endif]-->
	</head>
{% else %}
	{{ full_head_section_text }}
{% endif %}
{% if (css_files) %}
	{% for css_href in css_files %}
	<link rel="stylesheet" media="all" href="{{ css_href|escape('html') }}" />
	{% endfor %}
{% endif %}
{% if (js_files) %}
	{% for js_href in js_files %}
	<script src="{{ js_href|escape('html') }}"></script>
	{% endfor %}
{% endif %}
	<body vocab="http://schema.org/">
		{{ body }}
	</body>
</html>
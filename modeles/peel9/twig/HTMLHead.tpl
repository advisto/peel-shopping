{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: HTMLHead.tpl 53849 2017-05-19 12:29:39Z sdelaporte $
#}
<head{% if head_attributes is defined %} {{ head_attributes }}{% endif %}>
	{{ content_tag_htmlhead }}
	{{ meta }}
	{% if (favicon_href) %}<link rel="icon" type="image/x-icon" href="{{ favicon_href }}" />
	<link rel="shortcut icon" type="image/x-icon" href="{{ favicon_href }}" />{% endif %}
	{% if (link_rss_html) %}{{ link_rss_html }}{% endif %}
{% for css_href in css_files %}
	<link rel="stylesheet" media="all" href="{{ css_href|escape('html') }}" {% if (css_href|strpos('print')) %}media="print"{% else %}media="all"{% endif %} />
{% endfor %}
	<style>
	{% if background_banner %}
	{% for this_background in background_banner %}
	#main_content{
		background:url('{{ this_background.url_img }}') no-repeat scroll top center rgba(0, 0, 0, 0);
		cursor:pointer;
	}
	{% endfor %}
	</style>
	{% elseif bg_colors %}
	<style>
		body { background-color:{{ bg_colors.body }}; }
		#menu1 li, .main_menu_wide { background-color:{{ bg_colors.menu }}; }
		<!--[if IE]>
			#contact_form{height:100% !important;}
		<![endif]-->
	{% endif %}
	{% if site_static_css_style %}
		{{ site_static_css_style }}
	{% endif %}
	{% if google_recpatcha %}
		<script src='https://www.google.com/recaptcha/api.js' async defer></script>
	{% endif %}
	</style>
	{{ js_output }}
	{% for html in html_head %}
		{{ html }}
	{% endfor %}
	{{ css_output }}
	<!--[if lt IE 9]>
	<script src="{{ wwwroot }}/lib/js/html5shiv.js"></script>
	<script src="{{ wwwroot }}/lib/js/respond.js"></script>
	<![endif]-->
</head>
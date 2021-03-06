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
// $Id: admin_haut.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<!DOCTYPE html>
<html lang="{{ lang }}" dir="ltr">
<head>
	{% if GENERAL_ENCODING %}<meta charset="{{ GENERAL_ENCODING }}" />{% endif %}
	<meta name="generator" content="{{ generator|str_form_value }}" />
	<meta name="robots" content="noindex,nofollow" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>{{ doc_title }}</title>
	{% if (favicon_href) %}<link rel="icon" type="image/x-icon" href="{{ favicon_href }}" />
	<link rel="shortcut icon" type="image/x-icon" href="{{ favicon_href }}" />{% endif %}
{% if (css_files) %}
	{% for css_href in css_files %}
	<link rel="stylesheet" media="all" href="{{ css_href|escape('html') }}" {% if (css_href|strpos('print')) %}media="print"{% else %}media="all"{% endif %}  />
	{% endfor %}
{% endif %}
{% if (js_files) %}
	{% for js_href in js_files %}
	<script src="{{ js_href|escape('html') }}"></script>
	{% endfor %}
{% endif %}
{% if js_content %}
	<script><!--//--><![CDATA[//><!--
		{{ js_content }}
	//--><!]]></script>
{% endif %}
	<!--[if lt IE 9]>
	<script src="{{ wwwroot }}/lib/js/html5shiv.js"></script>
	<script src="{{ wwwroot }}/lib/js/respond.js"></script>
	<![endif]-->
	{% if css_output %}
	{{ css_output }}
	{% endif %}
</head>
<body>
	<!-- Début Total -->
	<div id="total">
		<div class="navbar navbar-inverse navbar-static-top">
			<div class="navbar-inner">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a href="{{ administrer_url }}/" class="navbar-brand"><img src="{{ logo_src|escape('html') }}" alt="{{ site }}" /></a>
					</div>
					<div class="navbar-collapse collapse">
						<nav>
							<ul id="menu1" class="nav navbar-nav">
								{{ admin_menu }}
							</ul>
							<ul class="nav nav-pills pull-right">
								<li style="margin-top: 8px; margin-bottom: 3px">{# flags_links_array|join('&nbsp;') #}{{ flags }}</li>
								<li style="margin-top: 3px"><a href="{{ sortie_href|escape('html') }}" title="{{ STR_ADMIN_DISCONNECT|str_form_value }}"><span class="glyphicon glyphicon-off"></span></a></li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
		<div class="main_header_wide">
			<div class="main_header container">
				<header>
					<div class="row">
						<div id="page_title" class="col-md-12"><h1 property="name">{{ page_title }}</h1></div>
					</div>
				</header>
			</div>
		</div>
		<div class="container">
			<div class="main_content row">
				<div class="col-md-12">
					{{ output_create_or_update_order }}
					{{ notification_output }}
{% if is_demo_error %}
					<p class="alert alert-danger fade in">{{ STR_ADMIN_DEMO_WARNING }} <button class="close remember-close" aria-hidden="true" data-dismiss="alert" type="button" id="demo_warning_close">×</button></p>
{% endif %}
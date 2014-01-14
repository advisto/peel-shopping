{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rss.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
#}<?xml version="1.0" encoding="{{ page_encoding }}"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>{{ STR_RSS_TITLE }}</title>
		<link>{{ wwwroot }}</link>
		<description>{{ STR_MODULE_RSS_DESCRIPTION }}</description>
		<language>{{ language }}</language>
		<pubDate>{{ pubDate }}</pubDate>
		<generator>{{ generator }}</generator>
		<atom:link href="{{ link }}" rel="self" type="application/rss+xml" />
		{{ image_xml }}
		{% for it in items %}
		<item>
			<title>{{ it.title|strip_tags }}</title>
			<link>{{ it.guid }}</link>	
			{% if it.promotion_rss > 0 %}<promotion>- {{ it.promotion_rss }}</promotion>{% endif %}
			<guid>{{ it.guid }}</guid>
			<pubDate>{{ it.pubDate }} GMT</pubDate>
			<description>{{ it.description|str_shorten(1000,'','...') }}</description>
		</item>
	{% endfor %}
	</channel>
</rss>
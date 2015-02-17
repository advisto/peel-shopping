{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: alpha.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
#}<h1 property="name" class="page_title">{{ title }}</h1>
<div class="page_content">
	<table class="full_width" cellpadding="3">
		{% for letter in map %}
			<tr><td colspan="2">{{ letter.value }}</td></tr>
			{% for item in letter.items %}
				<tr><td><a href="{{ item.href|escape('html') }}">{{ item.name|html_entity_decode_if_needed }} ({{ item.count }})</a></td></tr>
			{% endfor %}
		{% endfor %}
	</table>
</div>
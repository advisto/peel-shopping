{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: categorie_accueil.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<h2>{{ header }}</h2>
<table class="categorie_accueil">
{% for cat in cats %}
	{% if cat.row_start %}
	<tr>
	{% endif %}
	{% if (cat.href) %}
		<td class="center" style="width:50%">
			<p><a href="{{ cat.href|escape('html') }}">{{ cat.name|html_entity_decode_if_needed }}</a></p>
		{% if (cat.src) %}
			<p><a href="{{ cat.href|escape('html') }}"><img src="{{ cat.src|escape('html') }}" alt="{{ cat.name|html_entity_decode_if_needed }}" /></a></p>
		{% endif %}
		</td>
	{% endif %}
	{% if cat.row_end %}
		{% for var in 1..cat.empty_cells %}
		<td></td>
		{% endfor %}
	</tr>
	{% endif %}
{% endfor %}
</table>
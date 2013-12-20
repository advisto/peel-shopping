{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: subcategories_table.tpl 39392 2013-12-20 11:08:42Z gboussin $
#}<div class="sub_category row">
{% for cat in cats %}
	{% if cat.href %}
		<div class="center col-md-{{ (12 // nb_col_md) }} col-sm-{{ (12 // nb_col_sm) }}">
			{% if (cat.src) %}
				<a href="{{ cat.href|escape('html') }}"><img src="{{ cat.src|escape('html') }}" alt="{{ cat.name|html_entity_decode_if_needed }}" /></a>
				<br />
			{% endif %}
			<a href="{{ cat.href|escape('html') }}" class="sub_category_title">{{ cat.name|html_entity_decode_if_needed }}</a>
		</div>
	{% endif %}
	{% if cat.i%nb_col_md==0 %}
	<div class="clearfix visible-md visible-lg"></div>
	{% endif %}
	{% if cat.i%nb_col_sm==0 %}
	<div class="clearfix visible-sm"></div>
	{% endif %}
{% endfor %}
</div>

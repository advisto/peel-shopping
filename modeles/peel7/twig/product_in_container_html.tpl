{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: product_in_container_html.tpl 47145 2015-10-04 11:56:35Z sdelaporte $
#}<table class="full_width center product_in_container">
	<tr>
		<td {% if (on_estimate) %}colspan="2"{% endif %} class="module_product_title"><a href="{{ href|escape('html') }}" title="{{ name|str_form_value }}">{{ name }}</a></td>
	</tr>
	{% if (src) %}
	<tr>
		<td {% if (on_estimate) %}colspan="2"{% endif %} class="module_product_image"><a href="{{ href|escape('html') }}" title="{{ name|str_form_value }}"><img src="{{ src|escape('html') }}" alt="{{ name|str_form_value }}" class="product_image" /></a></td>
	</tr>
	{% endif %}
	<tr>
		<td><div><a href="{{ href|escape('html') }}" class="btn btn-info btn-sm" title="{{ name|str_form_value }}">{{ more_detail_label }}</a></div></td>
		{% if (on_estimate) %}
			<td class="right" style="width:110px;">{{ on_estimate }}</td>
		{% endif %}
	</tr>
	{% if user is defined %}
	<tr>
		<td>{{ user.pseudo }}</td>
	</tr>
	{% endif %}
</table>
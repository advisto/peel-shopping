{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: product_in_container_html.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<table class="full_width center product_in_container">
	{% if src is defined %}
	<tr>
		<td class="module_product_image"><a href="{{ href|escape('html') }}" title="{{ name|str_form_value }}"><img src="{{ src|escape('html') }}" alt="{{ name|str_form_value }}" class="product_image" /></a></td>
	</tr>
	{% endif %}
	<tr>
		<td class="module_product_title">
	{% if thumbnail_promotion %}
			<div class="produit_thumbnail_promotion"><span>-{{ promotion }}</span></div>
	{% endif %}
			<a href="{{ href|escape('html') }}" title="{{ name|str_form_value }}">{{ name }}</a>
		</td>
	</tr>
	{% if descriptif and product_description_product_in_container_disabled is empty %}
	<tr>
		<td>
			<div class="description_text"><a href="{{ href|escape('html') }}">{{ descriptif }}</a></div>
		</td>
	</tr>
	{% endif %}
	{% if on_estimate is defined %}
	<tr>
			<td>{{ on_estimate }}</td>
	</tr>
	{% endif %}
	{% if user is defined %}
	<tr>
		<td>{{ user.pseudo }}</td>
	</tr>
	{% endif %}
</table>
{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: prix.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
#}<table class="{{ table_css_class }}">
{% if hide_price %}
	<tr>
		<td class="middle">{{ STR_PLEASE_LOGIN }}</td>
{% else %}
	{% if display_old_price_inline %}
	<tr>
		{% if (original_price) %}
		<td class="middle"><del>{{ original_price }}</del></td>
		{% endif %}	
		<td>
			<span class="prix"{% if (item_id) %} id="{{ item_id }}"{% endif %}>{{ final_price }}</span>
		</td>
	</tr>
	{% else %}
	<tr>
		<td>
			<span class="prix"{% if (item_id) %} id="{{ item_id }}"{% endif %}{{ final_price }}</span>
		</td>
	</tr>
		{% if (original_price) %}
	<tr>
		<td class="middle"><del>{{ original_price }}</del></td>
	</tr>
		{% endif %}
	{% endif %}
	{% if (ecotax) %}
	<tr>
		<td{% if display_old_price_inline and (original_price) %} colspan="2"{% endif %}><span class="ecotaxe"><i> {{ ecotax.label }}: {{ ecotax.prix }}</i></span></td>
	</tr>
	{% endif %}
	{% if (measurement) %}
	<tr>
		<td{% if display_old_price_inline and (original_price) %} colspan="2"{% endif %}><p>{{ measurement.label }} {{ measurement.prix }}</p></td>
	</tr>
	{% endif %}
{% endif %}
</table>
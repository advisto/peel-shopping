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
// $Id: prix.tpl 55288 2017-11-27 16:53:44Z sdelaporte $
#}<table class="{{ table_css_class }}">
{% if hide_price %}
	<tr>
		<td class="middle">{{ STR_PLEASE_LOGIN }}</td>
	</tr>
{% else %}
	{% if display_old_price_inline %}
		{% if prix_ht_without_ecotax %}
	<tr>
		<td>
			<span class="prix">{{ prix_ht_without_ecotax.prix }}</span>
		</td>
	</tr> 
	<tr>
			{% if original_price is defined %}
		<td class="middle"><del>{{ original_price }}</del></td>
			{% endif %}
		<td{% if display_old_price_inline and original_price is defined %} colspan="2"{% endif %}><span class="ecotaxe"><i> + {{ prix_ht_without_ecotax.label }}: {{ prix_ht_without_ecotax.prix_ecotaxe }}</i> =&nbsp;</span><span class="ecotaxe"{% if item_id %} id="{{ item_id }}"{% endif %}>{% if STR_FROM %}{{ STR_FROM }}{% endif %} {{ final_price }}{% if conditionnement %}{{ STR_CONDITIONING_TEXT }}{% endif %}</span></td>
	</tr>
		{% else %}
	<tr>
			{% if original_price is defined %}
		<td class="middle"><del>{{ original_price }}</del></td>
			{% endif %}
		<td>
			<span class="prix"{% if item_id %} id="{{ item_id }}"{% endif %}>{% if STR_FROM %}{{ STR_FROM }}{% endif %} {{ final_price }} {% if conditionnement %}{{ STR_CONDITIONING_TEXT }}{% endif %}</span>
		</td>
	</tr>
		{% endif %}
	{% else %}
	<tr>
		<td>
			<span class="prix"{% if item_id %} id="{{ item_id }}"{% endif %}>{% if STR_FROM %}{{ STR_FROM }}{% endif %} {{ final_price }} {% if conditionnement %}{{ STR_CONDITIONING_TEXT }}{% endif %}</span>
		</td>
	</tr>
		{% if original_price is defined %}
	<tr>
		<td class="middle"><del>{{ original_price }}</del></td>
	</tr>
		{% endif %}
	{% endif %}
	
	
	
	
	
	
	{% if ecotax %}
	<tr>
		<td{% if display_old_price_inline and original_price is defined %} colspan="2"{% endif %}><span class="ecotaxe"><i> {{ ecotax.label }}: {{ ecotax.prix }}</i></span></td>
	</tr>
	{% endif %} 
	{% if measurement is defined %}
	<tr>
		<td{% if display_old_price_inline and original_price is defined %} colspan="2"{% endif %}><p>{{ measurement.label }} {{ measurement.prix }}</p></td>
	</tr>
	{% endif %}
{% endif %}
</table>
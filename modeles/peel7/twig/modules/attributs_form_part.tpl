{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: attributs_form_part.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}
{# On renvoie le formulaire sous forme de table ou de HTML simple #}
{% if display_mode=='table' %}
<p style="font-weight:bold; margin:2px">{{ STR_MODULE_ATTRIBUTS_OPTIONS_ATTRIBUTS }}{{ STR_BEFORE_TWO_POINTS }}:</p>
<table class="attributs_form_part">
{% endif %}
{% for a in attributes_text_array %}
	{% if display_mode=='selected_text' %}
		{# On renvoie le texte des attributs sélectionnés #}
		{% if (a.options) %}
			{{ a.name }}{{ STR_BEFORE_TWO_POINTS }}: {% for o in a.options %}{% if o.issel %}{{ o.text }} {% endif %}{% endfor %}<br />
		{% else %}
			{{ a.name }}{{ STR_BEFORE_TWO_POINTS }}: {{ a.input_value }}<br />
		{% endif %}
	{% else %}
		{% if display_mode=='table' %}
	<tr>
		<td class="attribut-cell">
		{% endif %}
		{{ a.name }}{{ STR_BEFORE_TWO_POINTS }}:
		{% if a.input_type=='select' %}
			<select id="{{ a.input_id }}" name="{{ a.input_name }}" onchange="{{ a.onchange }}">
			{% for o in a.options %}	
				<option value="{{ o.value }}" {% if o.issel %} selected="selected"{% endif %}>{{ o.text }}</option>
			{% endfor %}
			</select>
		{% elseif a.input_type=='radio' or a.input_type=='checkbox' %}
			{% for o in a.options %}
			<br /><input type="{{ a.input_type }}" value="{{ o.value }}" id="{{ o.id }}" name="{{ o.name }}" {% if o.issel %} checked="checked"{% endif %} onclick="{{ o.onclick }}" /><label for="{{ o.id }}">{{ o.text }}</label>
			{% endfor %}
		{% else %}
			<input type="{{ a.input_type }}" name="{{ a.input_name }}" value="{{ a.input_value }}" />
		{% endif %}
		{{ a.text }}
		{% if display_mode=='table' %}
		</td>
	</tr>
		{% endif %}
	{% endif %}
{% endfor %}
{% if display_mode=='table' %}
</table>
{% endif %}
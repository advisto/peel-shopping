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
// $Id: attributs_form_part.tpl 39392 2013-12-20 11:08:42Z gboussin $
#}
{# On renvoie le formulaire sous forme de table ou de HTML simple #}
{% if display_mode=='table' %}
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
		<label for="{% if a.input_type=='radio' or a.input_type=='checkbox' %}{{ o.id }}{% else %}{{ a.input_id }}{% endif %}">{{ a.name }}{{ STR_BEFORE_TWO_POINTS }}:</label>
		{% if a.input_type=='select' %}
			<select class="form-control" id="{{ a.input_id }}" name="{{ a.input_name }}" onchange="{{ a.onchange }}"{% if a.input_class %} class="{{ a.input_class }}"{% endif %}>
			{% for o in a.options %}	
				<option value="{{ o.value }}" {% if o.issel %} selected="selected"{% endif %}>{{ o.text }}</option>
			{% endfor %}
			</select>
		{% elseif a.input_type=='radio' or a.input_type=='checkbox' %}
			{% for o in a.options %}
			{% if a.max_label_length>=5 %}<br />{% endif %}<input type="{{ a.input_type }}" value="{{ o.value }}" id="{{ o.id }}" name="{{ o.name }}" {% if o.issel %} checked="checked"{% endif %} onclick="{{ o.onclick }}" /><label for="{{ o.id }}">{{ o.text }}</label>
			{% endfor %}
		{% elseif a.input_type %}
			<input id="{{ a.input_id }}" type="{{ a.input_type }}" name="{{ a.input_name }}" value="{{ a.input_value }}"{% if a.input_class %} class="{{ a.input_class }}"{% endif %} />
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
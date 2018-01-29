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
// $Id: attributs_form_part.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
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
		{% if display_mode=='table' or display_mode=='table_part' %}
	<tr>
		<td class="attribut-cell">
		{% endif %}
		{% if a.input_type!='radio' and a.input_type!='checkbox' %}<label for="{{ a.input_id }}">{% if a.name=='Auteur' %}<h3 class='auteur_page_produit'>{% endif %}{{ a.name }}{{ STR_BEFORE_TWO_POINTS }}:{% if a.name=='Auteur' %}</h3>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label>{% else %}{{ a.name }}{% endif %}
		{% if display_mode=='table' or display_mode=='table_part' %}
		</td>
		<td class="attribut-cell">
		{% endif %}
		{% if a.input_type=='select' %}
			<select class="form-control" id="{{ a.input_id }}" name="{{ a.input_name }}" onchange="{{ a.onchange }}"{% if a.input_class %} class="{{ a.input_class }}"{% endif %}>
			{% if attribut_first_select_option_is_empty %}
				<option value="">{{ LANG.STR_CHOOSE }}</option>
			{% endif %}
			{% for o in a.options %}	
				<option value="{{ o.value }}" {% if o.issel %} selected="selected"{% endif %}>{{ o.text }}</option>
			{% endfor %}
			</select>
		{% elseif a.input_type=='radio' or a.input_type=='checkbox' %}
			{% for o in a.options %}
			{% if a.max_label_length>=5 %}<br />{% endif %}<input type="{{ a.input_type }}" value="{{ o.value }}" id="{{ o.id }}" name="{{ o.name }}" {% if o.issel %} checked="checked"{% endif %} onclick="{{ o.onclick }}" /><label for="{{ o.id }}">{{ o.text }}</label>
			{% endfor %}
		{% elseif a.input_type=='link' %}
			{% for o in a.options %}
				<a href="{{ wwwroot }}/search.php?{{ o.name }}={{ o.value }}">{{ o.text }}</a>
			{% endfor %}
		{% elseif a.input_type %}
			<input id="{{ a.input_id }}" type="{{ a.input_type }}" name="{{ a.input_name }}" value="{{ a.input_value }}"{% if a.input_class %} class="{{ a.input_class }}"{% endif %} />
		{% endif %}
		{{ a.text }}
		{% if display_mode=='table' or display_mode=='table_part' %}
		</td>
	</tr>
		{% endif %}
	{% endif %}
{% endfor %}
{% if display_mode=='table' %}
</table>
{% endif %}
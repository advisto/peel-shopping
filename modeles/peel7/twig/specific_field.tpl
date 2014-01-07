{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: specific_field.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}{% if f.field_type == "radio" %}
	{% for o in f.options %}
		<input type="radio" value="{{ o.value|str_form_value }}"{% if o.issel %} checked="checked"{% endif %} id="{{ f.field_name }}#{{ o.value|str_form_value }}" name="{{ f.field_name }}[]"/> <label for="{{ f.field_name }}#{{ o.value|str_form_value }}">{{ o.name }}</label><br />
	{% endfor %}
{% elseif f.field_type == "checkbox" %}
	{% for o in f.options %}
	<input type="checkbox" value="{{ o.value|str_form_value }}"{% if o.issel %} checked="checked"{% endif %} id="{{ f.field_name }}#{{ o.value|str_form_value }}" name="{{ f.field_name }}[]" /> <label for="{{ f.field_name }}#{{ o.value|str_form_value }}">{{ o.name }}</label><br />
	{% endfor %}
{% else %}
<select class="form-control" id="{{ f.field_name }}" name="{{ f.field_name }}">
	<option value="">{{ f.STR_CHOOSE }}...</option>
	{% for o in f.options %}
	<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
	{% endfor %}
</select>
{% endif %}
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
// $Id: specific_field.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
#}{% if f.field_type == "radio" %}
	{% for o in f.options %}
		<input type="radio" value="{{ o.value|str_form_value }}"{% if o.issel %} checked="checked"{% endif %} id="{{ f.field_name|str_form_value }}#{{ o.value|str_form_value }}" name="{{ f.field_name|str_form_value }}[]" /> <label for="{{ f.field_name|str_form_value }}#{{ o.value|str_form_value }}">{{ o.name }}</label><br />
	{% endfor %}
{% elseif f.field_type == "checkbox" %}
	{% for o in f.options %}
	<input type="checkbox" value="{{ o.value|str_form_value }}"{% if o.issel %} checked="checked"{% endif %} id="{{ f.field_name|str_form_value }}#{{ o.value|str_form_value }}" name="{{ f.field_name|str_form_value }}[]" /> <label for="{{ f.field_name|str_form_value }}#{{ o.value|str_form_value }}">{{ o.name }}</label><br />
	{% endfor %}
{% else %}
<select class="form-control" id="{{ f.field_name }}" name="{{ f.field_name }}">
	<option value="">{{ f.STR_CHOOSE }}...</option>
	{% for o in f.options %}
	<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
	{% endfor %}
</select>
{% elseif f.field_type == "text" %}
	{% for o in f.options %}
		<input type="text" value="{{ o.value|str_form_value }}" id="{{ f.field_name }}#{{ o.value|str_form_value }}" name="{{ f.field_name }}" class="form-control" /><br />
	{% endfor %}
{% elseif f.field_type == "password" %}
	{% for o in f.options %}
		<input type="password" id="{{ f.field_name }}" name="{{ f.field_name }}" class="form-control" value="" /><br />
	{% endfor %}
{% elseif f.field_type == "datepicker" %}
	{% for o in f.options %}
		<input type="text" value="{{ o.value|str_form_value }}" id="{{ f.field_name }}#{{ o.value|str_form_value }}" name="{{ f.field_name }}" class="form-control datepicker" /><br />
	{% endfor %}
{% elseif f.field_type == "upload" %}
	{% for o in f.options %}
		<input class="uploader" name="{{ f.field_name }}" type="file" value="" id="{{ f.field_name }}" /><br />
	{% endfor %}
{% elseif f.field_type == "hidden" %}
	{% for o in f.options %}
		<input name="{{ f.field_name }}" type="hidden" value="{{ o.value|str_form_value }}" id="{{ f.field_name }}" /><br />
	{% endfor %}
{% elseif f.field_type == "textarea" %}
	{% for o in f.options %}
		<textarea class="form-control" name="{{ f.field_name }}" id="{{ f.field_name }}">{{ o.value }}</textarea>
	{% endfor %}
{% elseif f.field_type == "separator" %}
	{* Ici on permet de mettre un séparateur à la place d'un champ. C'est pratique pour faire différent bloc dans un formulaire, avec un titre par bloc *}
	{% for o in f.options %}
		<{{ o.name }}>{{ o.value }}</{{ o.name }}>
	{% endfor %}
{% endif %}
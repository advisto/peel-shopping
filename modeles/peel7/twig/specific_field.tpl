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
// $Id: specific_field.tpl 47242 2015-10-08 15:28:40Z gboussin $
#}{% if f.field_type == "radio" %}
	{% for o in f.options %}
		<input type="radio" value="{{ o.value|str_form_value }}"{% if o.issel %} checked="checked"{% endif %} id="{{ f.field_name|str_form_value }}#{{ o.value|str_form_value }}" name="{{ f.field_name|str_form_value }}[]" /> <label for="{{ f.field_name|str_form_value }}#{{ o.value|str_form_value }}">{{ o.name }}</label><br />
	{% endfor %}
{% elseif f.field_type == "checkbox" %}
	{% for o in f.options %}
	<input type="checkbox" value="{{ o.value|str_form_value }}"{% if o.issel %} checked="checked"{% endif %} id="{{ f.field_name|str_form_value }}#{{ o.value|str_form_value }}" name="{{ f.field_name|str_form_value }}[]" /> <label for="{{ f.field_name|str_form_value }}#{{ o.value|str_form_value }}">{{ o.name }}</label><br />
	{% endfor %}
{% elseif f.field_type == "select" %}
<select id="{{ f.field_name|str_form_value }}" name="{{ f.field_name|str_form_value }}" class="form-control">
	<option value="">{{ f.STR_CHOOSE }}...</option>
{% for o in f.options %}
	<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
{% endfor %}
</select>
{% elseif f.field_type == "password" %}
<input type="password" id="{{ f.field_name|str_form_value }}" name="{{ f.field_name|str_form_value }}" value="{{ f.field_value|str_form_value }}" class="form-control" />
{% elseif f.field_type == "datepicker" %}
<input type="text" value="{{ f.field_value|str_form_value }}" id="{{ f.field_name|str_form_value }}#{{ f.field_value|str_form_value }}" name="{{ f.field_name|str_form_value }}" class="form-control datepicker" />
{% elseif f.field_type == "upload" %}
	{% if f.upload_infos %}
		{% if  site_parameters.used_uploader=="fineuploader" %}
{% if f.upload_file_display_title is not empty }<div class="upload_file_field_title">{{ f.field_title }}</div>{% endif %}<div id="{{ f.field_name|str_form_value }}" class="uploader"></div>
		{% else %}
<input name="{{ f.field_name|str_form_value }}" type="file" value="" id="{{ f.field_name|str_form_value }}" />
		{% endif %}
	{% else %}
{% include "uploaded_file.tpl" with {'f':f.upload_infos,'STR_DELETE':f.upload_infos.STR_DELETE_THIS_FILE } %}
	{% endif %}
{% elseif f.field_type == "hidden" %}
<input name="{{ f.field_name|str_form_value }}" type="hidden" value="{{ f.field_value|str_form_value }}" id="{{ f.field_name }}" />
{% elseif f.field_type == "textarea" %}
<textarea name="{{ f.field_name|str_form_value }}" id="{{ f.field_name }}"class="form-control">{{ f.field_value }}</textarea>
{% elseif f.field_type == "html" %}
{{ f.text_editor_html }}
{% elseif f.field_type == "separator" or f.field_type == "tag" %}
{* Ici on permet de mettre du HTML. C'est pratique pour faire différents blocs dans un formulaire, avec un titre par bloc *}
{{ f.field_value }}
{% elseif f.field_type == "text" or f.field_type %}
<input type="text" value="{{ f.field_value|str_form_value }}" id="{{ f.field_name|str_form_value }}" name="{{ f.field_name|str_form_value }}" class="form-control" />
{% endif %}
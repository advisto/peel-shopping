{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	 |
// | opensource GPL license: you are allowed to customize the code		 |
// | for your own needs, but must keep your changes under GPL			 |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		 |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	 |
// +----------------------------------------------------------------------+
// Id: specific_field.tpl 52564 2017-01-22 15:55:13Z sdelaporte 
#}
{% if text_only and f.field_type != "upload" %}
	{{ f.field_value }}
{% elseif f.field_type == "radio" %}
	{% if f.options %}
		{% for o in f.options %}
<input {% if f.readonly and o.issel is empty %} readonly="readonly"{% endif %} type="radio" value="{{ o.value|str_form_value }}" {% if o.issel %} checked="checked"{% endif %} {% if disabled %} disabled="disabled"{% endif %} id="{{ f.field_id|str_form_value }}#{{ o.value|str_form_value }}" name="{{ f.field_name|str_form_value }}" /> <label for="{{ f.field_name }}#{{ o.value|str_form_value }}" >{{ o.name }}</label>{% if o.br %}<br />{% endif %}
		{% endfor %}
	{% endif %}
{% elseif f.field_type == "checkbox" %}
	{% if f.options %}
		{% for o in f.options %}
<input {% if f.readonly and o.issel is empty %} readonly="readonly"{% endif %} type="checkbox" value="{{ o.value|str_form_value }}" {% if o.issel %} checked="checked"{% endif %}{% if disabled %} disabled="disabled"{% endif %} id="{{ f.field_id|str_form_value }}#{{ o.value|str_form_value }}" name="{{ f.field_name|str_form_value }}[]" /> <label for="{{ f.field_name }}#{{ o.value|str_form_value }}" >{{ o.name }}</label>{% if o.br %}<br />{% endif %}
		{% endfor %}
	{% endif %}
{% elseif f.field_type == "select" %}
<select {% if f.multiple %} multiple="multiple" size="5" name="{{ f.field_name|str_form_value }}[]" {% else %} name="{{ f.field_name|str_form_value }}" {% endif %} {% if f.readonly %} readonly="readonly"{% endif %} id="{{ f.field_id|str_form_value }}" {% if disabled %} disabled="disabled"{% endif %} class="form-control" onchange="{{ f.javascript|str_form_value }}" >
	{% if f.options|length >1 and f.readonly is empty %}
	<option value="">{{ f.STR_CHOOSE }}...</option>
	{% endif %}
	{% if f.options %}
		{% for o in f.options %}
	<option value="{{ o.value|str_form_value }}" {% if o.issel %} selected="selected"{% endif %} {% if o.issel is empty and f.readonly %}disabled="disabled"{% endif %}>{{ o.name }}</option>
		{% endfor %}
	{% endif %}
</select>
{% elseif f.field_type == "password" %}
<input {% if disabled %} disabled="disabled"{% endif %} type="password" id="{{ f.field_id|str_form_value }}" name="{{ f.field_name|str_form_value }}" value="{{ f.field_value|str_form_value }}" class="form-control" />
{% elseif f.field_type == "number" %}
<input {% if disabled %} disabled="disabled"{% endif %} type="number" step="any" id="{{ f.field_id|str_form_value }}" name="{{ f.field_name|str_form_value }}" value="{{ f.field_value|str_form_value }}" class="form-control" />
{% elseif f.field_type == "datepicker" %}
<input {% if disabled %} disabled="disabled"{% endif %} type="text" value="{{ f.field_value|str_form_value }}" id="{{ f.field_id|str_form_value }}#{{ f.field_value|str_form_value }}" name="{{ f.field_name|str_form_value }}" class="form-control datepicker" />
{% elseif f.field_type == "upload" %}
	{% if f.upload_infos is empty %}
		{% if site_parameters.used_uploader=="fineuploader" %}
{% if f.upload_file_display_title is not empty %}<div class="upload_file_field_title">{{ f.field_title }}</div>{% endif %}<div id="{{ f.field_id|replace({'[':'_openarray_'})|replace({']':'_closearray_'})|str_form_value }}" data-name="{{ f.field_name|replace({'[':'_openarray_'})|replace({']':'_closearray_'})|str_form_value }}" class="uploader"></div>
		{% else %}
<input name="{{ f.field_name|str_form_value }}" type="file" value="" id="{{ f.field_id|replace({'[':'_openarray_'})|replace({']':'_closearray_'})|str_form_value }}" />
		{% endif %}
	{% else %}
{% include "uploaded_file.tpl" with {'f':f.upload_infos,'STR_DELETE':f.upload_infos.STR_DELETE_THIS_FILE } %}
	{% endif %}
{% elseif f.field_type == "hidden" %}
<input name="{{ f.field_name|str_form_value }}" type="hidden" value="{{ f.field_value|str_form_value }}" id="{{ f.field_id }}" />
{% elseif f.field_type == "textarea" %}
<textarea rows="4" {% if f.readonly %} readonly="readonly"{% endif %} name="{{ f.field_name|str_form_value }}" id="{{ f.field_id }}" class="form-control"{% if f.field_placeholder %} placeholder="{{ f.field_placeholder|str_form_value }}" {% endif %}>{{ f.field_value }}</textarea>
{% elseif f.field_type == "html" %}
{{ f.text_editor_html }}
{% elseif f.field_type == "separator" or f.field_type == "tag" %}
{* Ici on permet de mettre du HTML. C'est pratique pour faire différents blocs dans un formulaire, avec un titre par bloc *}
{{ f.field_value }}
{% elseif f.field_type == "text" or f.field_type is empty %}
<input {% if disabled %} disabled="disabled"{% endif %} {% if f.readonly %} readonly="readonly"{% endif %} type="text" value="{{ f.field_value|str_form_value }}" id="{{ f.field_id|str_form_value }}" name="{{ f.field_name|str_form_value }}" class="form-control" {% if f.javascript %} onkeyup="{{ f.javascript|str_form_value }}" onchange="{{ f.javascript|str_form_value }}" onclick="{{ f.javascript|str_form_value }}" data-onload="{{ f.javascript|str_form_value }}" {% endif %}{% if f.field_maxlength %} maxlength="{{ f.field_maxlength|str_form_value }}" {% endif %}{% if f.field_placeholder %} placeholder="{{ f.field_placeholder|str_form_value }}" {% endif %} />
{% endif %}
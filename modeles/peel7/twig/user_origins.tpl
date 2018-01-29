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
// $Id: user_origins.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}
{% if origin_infos.user_origin_multiple %}
	<br class="clearfix visible-xs" />
	{% for o in origin_infos.options %}
	<input name="origin[]" type="checkbox" value="{{ o.value|str_form_value }}"{% if o.issel %} checked="checked"{% endif %} onclick="origin_change(this.value, {{ origin_infos.origin_other_ids_for_javascript }})" > {{ o.name }}<br />
	{% endfor %}
{% else %}
<select class="form-control" id="origin" name="origin" onchange="origin_change(this.value, {{ origin_infos.origin_other_ids_for_javascript }})">
	<option value="">{{ origin_infos.STR_CHOOSE }}...</option>
	{% for o in origin_infos.options %}
	<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
	{% endfor %}
</select>
<span id="origin_other" {% if not origin_infos.is_origin_other_activated %} style="display:none"{% endif %}><input name="origin_other" value="{{ origin_infos.origin_other|str_form_value }}" class="origin_other form-control" /></span>
{% endif %}
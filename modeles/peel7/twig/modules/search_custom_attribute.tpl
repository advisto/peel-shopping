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
// $Id: search_custom_attribute.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}{% for att_id in attributes|keys %}
{% if (att.options) %}
	<li class="attribute_{{ att_id }}" >
		<select class="form-control" name="custom_attribut[{{ att_id }}]" >
			<option value="">{{ select_attrib_txt }} {{ attributes.att_id.name }}</option>
			{% for o in attributes.att_id.options %}
			<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
			{% endfor %}
		</select>
{% else %}
		{{ att.name }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" class="form-control" name="custom_attribut[{{ att_id }}]" value="{{ att.value }}" />
{% endif %}
	</li>
{% endfor %}
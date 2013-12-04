{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: search_select_attribute.tpl 39162 2013-12-04 10:37:44Z gboussin $
#}{% if (options) %}
<li class="attribute_{{ categorie }}">
	<select class="form-control" name="{{ categorie }}">
	{% if (label) %}
		<option value="">{{ label }}</option>
	{% endif %}
	{% for o in options %}
		<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
	{% endfor %}
	</select>
</li>
{% endif %}
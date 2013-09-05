{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: user_origins.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}<select id="origin" name="origin" onchange="origin_change(this.value, {{ origin_infos.origin_other_ids_for_javascript }})">
	<option value="">{{ origin_infos.STR_CHOOSE }}...</option>
	{% for o in origin_infos.options %}
	<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
	{% endfor %}
</select>
<span id="origin_other" {% if not origin_infos.is_origin_other_activated %} style="display:none"{% endif %}><input name="origin_other" value="{{ origin_infos.origin_other|str_form_value }}" class="origin_other" /></span>